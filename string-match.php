<?php include('includes/header.php');?>

<h1>String Match</h1>
<hr>

<?php
	if(isset($_GET['site'])) {
	
		// The maximum execution time, in seconds. If set to zero, no time limit is imposed.
		set_time_limit(0);
		
		$query = "SELECT * FROM parsingrows WHERE site = '".$_GET['site']."' AND `dbpedia-uri` <> '' ORDER BY university";
		$result = $mysqli->query($query) or die($mysqli->error.__LINE__);
		
		// GOING THROUGH THE DATA
		if($result->num_rows > 0) {
			while($row = $result->fetch_assoc()) {
				$id = $row['id'];
				//url decode
				$dbpedia_uri = urldecode($row['dbpedia-uri']);
				
				//url encode
				$dbpedia_uri = urlencode($row['dbpedia-uri']);
				
				$url = "http://dbpedia.org/sparql?default-graph-uri=http%3A%2F%2Fdbpedia.org&query=select+DISTINCT+str%28%3Fn%29+as+%3Fname+where+%7B%0D%0A%7B%3C".$dbpedia_uri."%3E+rdfs%3Alabel+%3Fn.+%0D%0AFILTER%28langMatches%28lang%28%3Fn%29%2C+%22EN%22%29%29+%7D%0D%0AUNION%0D%0A%7B%3C".$dbpedia_uri."%3E+foaf%3Aname+%3Fn.+%0D%0AFILTER%28langMatches%28lang%28%3Fn%29%2C+%22EN%22%29%29+%7D%0D%0AUNION%0D%0A%7B%3C".$dbpedia_uri."%3E+dbpprop%3Aname+%3Fn.+%0D%0AFILTER%28langMatches%28lang%28%3Fn%29%2C+%22EN%22%29%29+%7D%0D%0A%7D&format=application%2Fsparql-results%2Bjson&timeout=3000&debug=on";
				$json = file_get_contents($url);
				$obj = json_decode($json);
				$bindings = $obj->results->bindings;
				
				$oliver_best_match = '';
				$max_oliver_percent = 0;
				
				foreach($bindings as $binding) {
					$binding_value = $binding->name->value;
					
					similar_text($row['university'], $binding_value, $oliver_percent);
					
					if($oliver_percent > $max_oliver_percent) {
						$max_oliver_percent = $oliver_percent;
						$oliver_best_match = $binding_value;
					}
				}
				
				$query_update = "UPDATE parsingrows SET `oliver-score` = '".$max_oliver_percent."' WHERE id = ".$id;
				$result_update = $mysqli->query($query_update) or die($mysqli->error.__LINE__);
			}
		} else {
			echo 'NO RESULTS'; 
		}
		
		echo "Done";

	} else {
		$query = "SELECT * FROM rankingsites GROUP BY title";
		$result = $mysqli->query($query) or die($mysqli->error.__LINE__);
		
		?>
		<script>
		function matchButton(e) {
			if (!confirm('This action will update the links score (It will overide existing scores)\nThis will take several minutes. Please do not close this window until it is finished.\nAre you sure you want to continue?')) {
				e.preventDefault();
			}
		}
		</script>
		<?php
		
		echo '<table class="table">';
		
		// GOING THROUGH THE DATA
		if($result->num_rows > 0) {
			echo '<tr>';
			echo '<th>Sites</th>';
			echo '<th>Dbpedia Links Count</th>';
			echo '<th>Wrong matches</th>';
			echo '<th>Match String</th>';
			echo '<th>View string match</th>';
			echo '</tr>';
			while($row_rankings = $result->fetch_assoc()) {
				echo '<tr>';
				echo '<td>'.$row_rankings['title'].'</td>';
				
				echo '<td>';
				$query_links_count = "SELECT COUNT(*) as all_links_row FROM parsingrows WHERE site = '".$row_rankings['title']."' AND `dbpedia-uri` <> ''";
				$result_links_count = $mysqli->query($query_links_count) or die($mysqli->error.__LINE__);
				$row_links_count = $result_links_count->fetch_assoc();
				echo $row_links_count['all_links_row'];
				echo '</td>';
				
				echo '<td>';
				$query_matches_count = "SELECT COUNT(*) as all_matches_row FROM parsingrows WHERE site = '".$row_rankings['title']."' AND `oliver-score` > ".$oliver_score[$row_rankings['title']]." AND `dbpedia-uri` <> ''";
				$result_matches_count = $mysqli->query($query_matches_count) or die($mysqli->error.__LINE__);
				$row_matches_count = $result_matches_count->fetch_assoc();
				echo $row_links_count['all_links_row'] - $row_matches_count['all_matches_row'];
				echo '</td>';
				
				
				echo '<td><a href="string-match.php?site='.$row_rankings['title'].'" class="btn btn-danger" role="button" onclick="matchButton(event);">Match String</a></td>';
				echo '<td><a href="string-match-result.php?site='.$row_rankings['title'].'" class="btn btn-success" role="button">View string match</a></td>';
				echo '</tr>';
			}
		} else {
			echo 'NO RESULTS'; 
		}
		
		echo '</table>';
	}
?>

<?php include('includes/footer.php');?>