<?php include('includes/header.php');?>

<?php
	if(isset($_GET['site'])) {
	
		// The maximum execution time, in seconds. If set to zero, no time limit is imposed.
		set_time_limit(0);
		
		$query = "SELECT * FROM parsingrows WHERE site = '".$_GET['site']."' AND `dbpedia-uri` <> '' ORDER BY university";
		$result = $mysqli->query($query) or die($mysqli->error.__LINE__);
	
		echo 'Oliver = Similar Text from World\'s Best Algorithms by Oliver<br />';
		echo 'Levenshtein = Levenshtein distance between two strings<br /><br />';
		
		echo '<table border="1">';
		echo '<tr>';
		echo '<th>University</td>';
		echo '<th>DBpedia URI</td>';
		echo '<th>Possible Names</td>';
		echo '<th>Oliver best match</th>';
		echo '<th>Levenshtein best match</th>';
		echo '<th>DBpedia score</th>';
		echo '<th>Oliver score</th>';
		echo '<th>Levenshtein score</th>';
		echo '</tr>';
				
		// GOING THROUGH THE DATA
		if($result->num_rows > 0) {
			while($row = $result->fetch_assoc()) {
				$id = $row['id'];
				//url decode
				$dbpedia_uri = urldecode($row['dbpedia-uri']);
				
				echo '<tr>';
				echo '<td>'.$row['university'].'</td>';
				echo '<td><a href="'.$dbpedia_uri.'">'.$dbpedia_uri.'</a></td>';
				//url encode
				$dbpedia_uri = urlencode($row['dbpedia-uri']);
				echo '<td>';
				$url = "http://dbpedia.org/sparql?default-graph-uri=http%3A%2F%2Fdbpedia.org&query=select+DISTINCT+str%28%3Fn%29+as+%3Fname+where+%7B%0D%0A%7B%3C".$dbpedia_uri."%3E+rdfs%3Alabel+%3Fn.+%0D%0AFILTER%28langMatches%28lang%28%3Fn%29%2C+%22EN%22%29%29+%7D%0D%0AUNION%0D%0A%7B%3C".$dbpedia_uri."%3E+foaf%3Aname+%3Fn.+%0D%0AFILTER%28langMatches%28lang%28%3Fn%29%2C+%22EN%22%29%29+%7D%0D%0AUNION%0D%0A%7B%3C".$dbpedia_uri."%3E+dbpprop%3Aname+%3Fn.+%0D%0AFILTER%28langMatches%28lang%28%3Fn%29%2C+%22EN%22%29%29+%7D%0D%0A%7D&format=application%2Fsparql-results%2Bjson&timeout=3000&debug=on";
				$json = file_get_contents($url);
				$obj = json_decode($json);
				$bindings = $obj->results->bindings;
				
				$oliver_best_match = '';
				$max_oliver_percent = 0;
				
				$levenshtein_best_match = '';
				$min_levenshtein_percent = 99999;
				
				foreach($bindings as $binding) {
					$binding_value = $binding->name->value;
					echo $binding_value;
					echo "<br />";
					
					similar_text($row['university'], $binding_value, $oliver_percent);
					echo " (Oliver = ".$oliver_percent.")";
					echo "<br />";
					if($oliver_percent > $max_oliver_percent) {
						$max_oliver_percent = $oliver_percent;
						$oliver_best_match = $binding_value;
					}
					
					$levenshtein_percent = levenshtein($row['university'], $binding_value);
					echo " (Levenshtein = ".$levenshtein_percent.")";
					echo "<br />";
					if($levenshtein_percent < $min_levenshtein_percent) {
						$min_levenshtein_percent = $levenshtein_percent;
						$levenshtein_best_match = $binding_value;
					}	
				}
				
				if($oliver_best_match == $levenshtein_best_match) {
					$class_best_match = 'style="background:green;color:white"';
				} else {
					$class_best_match = 'style="background:red;color:white"';
				}
				
				echo '<td '.$class_best_match.'>'.$oliver_best_match.'</td>';
				echo '<td '.$class_best_match.'>'.$levenshtein_best_match.'</td>';
				echo '<td>'.$row['dbpedia-score'].'</td>';
				echo '<td>'.$max_oliver_percent.'</td>';
				echo '<td>'.$min_levenshtein_percent.'</td>';
				echo '</tr>';
				
				$query_update = "UPDATE parsingrows SET `oliver-score` = '".$max_oliver_percent."', `levenshtein-score` = '".$min_levenshtein_percent."' WHERE id = ".$id;
				$result_update = $mysqli->query($query_update) or die($mysqli->error.__LINE__);
			}
		} else {
			echo 'NO RESULTS'; 
		}
		
		echo '</table>';
	} else {
		$query = "SELECT * FROM rankingsites GROUP BY title";
		$result = $mysqli->query($query) or die($mysqli->error.__LINE__);

		echo '<table border="1">';
		
		// GOING THROUGH THE DATA
		if($result->num_rows > 0) {
			echo '<tr>';
			echo '<th>Sites</th>';
			echo '<th>Dbpedia Links Count</th>';
			echo '<th>Match String</th>';
			echo '<th>View string match</th>';
			echo '</tr>';
			while($row_rankings = $result->fetch_assoc()) {
				echo '<tr>';
				echo '<td>'.$row_rankings['title'].'</td>';
				echo '<td>';
				$query_count = "SELECT COUNT(*) as all_row FROM parsingrows WHERE site = '".$row_rankings['title']."' AND `dbpedia-uri` <> ''";
				$result_count = $mysqli->query($query_count) or die($mysqli->error.__LINE__);
				$row_count = $result_count->fetch_assoc();
				echo $row_count['all_row'];
				echo '</td>';
				echo '<td><a href="string-match.php?site='.$row_rankings['title'].'">Match String</a></td>';
				echo '<td><a href="string-match-result.php?site='.$row_rankings['title'].'">View string match</a></td>';
				echo '</tr>';
			}
		} else {
			echo 'NO RESULTS'; 
		}
		
		echo '</table>';
	}
?>

<?php include('includes/footer.php');?>