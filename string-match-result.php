<?php include('includes/header.php');?>

<h1>Check Match</h1>
<hr>

<?php
	if(isset($_GET['site'])) {
	
		// The maximum execution time, in seconds. If set to zero, no time limit is imposed.
		set_time_limit(0);
		
		$query_site = "SELECT * FROM rankingsites WHERE id = ".$_GET['site'];
		$result_site = $mysqli->query($query_site) or die($mysqli->error.__LINE__);
		$row_site = $result_site->fetch_assoc();
		
		$query = "SELECT * FROM parsingrows WHERE `site-id` = '".$_GET['site']."' AND `dbpedia-uri` <> '' ORDER BY university";
		$result = $mysqli->query($query) or die($mysqli->error.__LINE__);
	
		echo '<table class="table table-bordered">';
		echo '<tr>';
		echo '<th>University</td>';
		echo '<th>DBpedia URI</td>';
		echo '<th class="text-center">Oliver&nbsp;score&nbsp;('.$row_site['best-oliver-score'].'%)</th>';
		echo '</tr>';
		
		
		// GOING THROUGH THE DATA
		if($result->num_rows > 0) {
			while($row = $result->fetch_assoc()) {
				//url decode
				$dbpedia_uri = urldecode($row['dbpedia-uri']);
				
				// style class for true positive rows
				if($row['oliver-score'] > $row_site['best-oliver-score']) {
					$class_row = '';
				} else {
					$class_row = 'class="danger"';
				}
				
				echo '<tr '.$class_row.'>';
				echo '<td>'.$row['university'].'</td>';
				echo '<td><a href="'.$dbpedia_uri.'" target="_blank">'.$dbpedia_uri.'</a></td>';
				echo '<td class="text-center">'.$row['oliver-score'].'%</td>';
				echo '</tr>';
			}
		} else {
			echo 'NO RESULTS'; 
		}
		
		echo '</table>';
	}
?>

<?php include('includes/footer.php');?>