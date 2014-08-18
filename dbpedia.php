<?php include('includes/header.php');?>

<h1>DBpedia</h1>
<hr>

<?php
	if(isset($_GET['site'])) {
		// require models
		require_once('includes/dbpedia-api/BaseAPI.php');
		require_once('includes/dbpedia-api/DBpediaSpotlight.php');

		// The maximum execution time, in seconds. If set to zero, no time limit is imposed.
		set_time_limit(0);
		
		$query = "SELECT * FROM parsingrows WHERE site = '".$_GET['site']."'";
		$result = $mysqli->query($query) or die($mysqli->error.__LINE__);
		
		// GOING THROUGH THE DATA
		if($result->num_rows > 0) {
			while($row = $result->fetch_assoc()) {
				$id = $row['id'];
				$university = $row['university'];
				//html decode
				$university = htmlspecialchars_decode($university);
				$ranking = $row['ranking'];
				
				// init NLP DBpediaSpotlight
				$api = new DBpediaSpotlight;
				$api->init_nlp($university);	
				$api->query();
				if(isset($api)) {
					$curl_info = $api->getCurlInfo();	
					$entity = $api->getEntities();
					$dbpedia_uri = (isset($entity[0]['name'])) ? $entity[0]['name'] : '';
					$dbpedia_score = (isset($entity[0]['score'])) ? $entity[0]['score'] : 0.0;
					
					//echo $university.' = ';
					//echo '('.$ranking.') -> '.$dbpedia_uri.' -> score: ' . $dbpedia_score . '<br />';

					$query_update = "UPDATE parsingrows SET `dbpedia-uri` = '".$dbpedia_uri."', `dbpedia-score` = '".$dbpedia_score."' WHERE id = ".$id;
					$result_update = $mysqli->query($query_update) or die($mysqli->error.__LINE__);
				}
			}	
		}
		
		echo 'Done';
	} else {
		$query = "SELECT * FROM rankingsites GROUP BY title";
		$result = $mysqli->query($query) or die($mysqli->error.__LINE__);
		
		?>
		<script>
		function linkButton(e) {
			if (!confirm('This action will update the links from dbpedia (It will overide existing links)\nThis will take several minutes. Please do not close this window until it is finished.\nAre you sure you want to continue?')) {
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
			echo '<th>University Count</th>';
			echo '<th>Dbpedia Links Count</th>';
			echo '<th>Links to Dbpedia</th>';
			echo '<th>View links to Dbpedia</th>';
			echo '</tr>';
			while($row_rankings = $result->fetch_assoc()) {
				echo '<tr>';
				echo '<td>'.$row_rankings['title'].'</td>';
				echo '<td>';
				$query_count = "SELECT COUNT(*) as all_row FROM parsingrows WHERE site = '".$row_rankings['title']."'";
				$result_count = $mysqli->query($query_count) or die($mysqli->error.__LINE__);
				$row_count = $result_count->fetch_assoc();
				echo $row_count['all_row'];
				echo '</td>';
				echo '<td>';
				$query_count = "SELECT COUNT(*) as all_row FROM parsingrows WHERE site = '".$row_rankings['title']."' AND `dbpedia-uri` <> ''";
				$result_count = $mysqli->query($query_count) or die($mysqli->error.__LINE__);
				$row_count = $result_count->fetch_assoc();
				echo $row_count['all_row'];
				echo '</td>';
				echo '<td><a href="dbpedia.php?site='.$row_rankings['title'].'" class="btn btn-danger" role="button" onclick="linkButton(event);">Link to Dbpedia</a></td>';
				echo '<td><a href="dbpedia-result.php?site='.$row_rankings['title'].'" class="btn btn-success" role="button">View links to Dbpedia</a></td>';
				echo '</tr>';
			}
		} else {
			echo 'NO RESULTS'; 
		}
		
		echo '</table>';
	}
?>

<?php include('includes/footer.php');?>