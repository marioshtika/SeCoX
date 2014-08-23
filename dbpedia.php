<?php include('includes/header.php');?>

<h1>Entity Link</h1>
<hr>

<?php
	if(isset($_GET['site'])) {
		// require models
		require_once('includes/dbpedia-api/BaseAPI.php');
		require_once('includes/dbpedia-api/DBpediaSpotlight.php');

		// The maximum execution time, in seconds. If set to zero, no time limit is imposed.
		set_time_limit(0);
		
		// Report all errors except E_NOTICE and E_WARNING
		error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);

		$query = "SELECT * FROM parsingrows WHERE `site-id` = '".$_GET['site']."'";
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

					$query_update = "UPDATE parsingrows SET `dbpedia-uri` = '".$dbpedia_uri."', `dbpedia-score` = '".$dbpedia_score."' WHERE id = ".$id;
					$result_update = $mysqli->query($query_update) or die($mysqli->error.__LINE__);
				}
			}	
		}
		
		echo '<div class="alert alert-success text-center" role="alert"><b>Well done!</b> You successfully linked your entities. </div>';
	}

	$query_sites = "SELECT * FROM rankingsites";
	$result_sites = $mysqli->query($query_sites) or die($mysqli->error.__LINE__);
	
	?>
	<script>
	function linkButton(e) {
		if (!confirm('This action will update the links from dbpedia (It will overide existing links)\nThis will take several minutes. Please do not close this window until it is finished.\nAre you sure you want to continue?')) {
			e.preventDefault();
		}
	}
	</script>
	<?php
	
	echo '<table class="table table-bordered">';
	
	// GOING THROUGH THE DATA
	if($result_sites->num_rows > 0) {
		echo '<tr>';
		echo '<th>Site</th>';
		echo '<th class="text-center">Parsed Universities</th>';
		echo '<th class="text-center">Linked Entities</th>';
		echo '<th class="text-center"></th>';
		echo '</tr>';
		while($row_sites = $result_sites->fetch_assoc()) {
			echo '<tr>';
			echo '<td>'.$row_sites['site'].'</td>';
			echo '<td class="text-center">';
			$query_university_count = "SELECT COUNT(*) as all_university_row FROM parsingrows WHERE `site-id` = '".$row_sites['id']."'";
			$result_university_count = $mysqli->query($query_university_count) or die($mysqli->error.__LINE__);
			$row_university_count = $result_university_count->fetch_assoc();
			echo $row_university_count['all_university_row'];
			echo '</td>';
			echo '<td class="text-center">';
			$query_dbpedia_count = "SELECT COUNT(*) as all_dbpedia_row FROM parsingrows WHERE `site-id` = '".$row_sites['id']."' AND `dbpedia-uri` <> ''";
			$result_dbpedia_count = $mysqli->query($query_dbpedia_count) or die($mysqli->error.__LINE__);
			$row_dbpedia_count = $result_dbpedia_count->fetch_assoc();
			echo $row_dbpedia_count['all_dbpedia_row'];
			echo '</td>';
			echo '<td class="text-center">';
			echo '<a href="dbpedia.php?site='.$row_sites['id'].'" class="btn btn-danger" role="button" onclick="linkButton(event);"><span class="glyphicon glyphicon-link"></span> Link</a> ';
			echo '<a href="dbpedia-result.php?site='.$row_sites['id'].'" class="btn btn-success" role="button"><span class="glyphicon glyphicon-list-alt"></span> View</a>';
			echo '</td>';
			echo '</tr>';
		}
	} else {
		echo 'NO RESULTS'; 
	}
	
	echo '</table>';

?>

<?php include('includes/footer.php');?>