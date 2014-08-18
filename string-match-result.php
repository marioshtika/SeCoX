<?php include('includes/header.php');?>

<h1>String Match Result</h1>
<hr>

<?php
	if(isset($_GET['site'])) {
	
		// The maximum execution time, in seconds. If set to zero, no time limit is imposed.
		set_time_limit(0);
		
		$query = "SELECT * FROM parsingrows WHERE site = '".$_GET['site']."' AND `dbpedia-uri` <> '' ORDER BY university";
		$result = $mysqli->query($query) or die($mysqli->error.__LINE__);
	
		echo '<table class="table">';
		echo '<tr>';
		echo '<th>University</td>';
		echo '<th>DBpedia URI</td>';
		echo '<th>DBpedia score</th>';
		echo '<th>Oliver score</th>';
		echo '<th>Levenshtein score</th>';
		echo '</tr>';
		
		
		// GOING THROUGH THE DATA
		if($result->num_rows > 0) {
			while($row = $result->fetch_assoc()) {
				//url decode
				$dbpedia_uri = urldecode($row['dbpedia-uri']);
				
				// style class for true positive rows
				if(($row['dbpedia-score'] > 0.203) && ($row['oliver-score'] > 5) && ($row['levenshtein-score'] < 31)) {
					$class_row = '';
				} else {
					$class_row = 'class="danger"';
				}
				
				echo '<tr '.$class_row.'>';
				echo '<td>'.$row['university'].'</td>';
				echo '<td><a href="'.$dbpedia_uri.'" target="_blank">'.$dbpedia_uri.'</a></td>';
				echo '<td>'.$row['dbpedia-score'].'</td>';
				echo '<td>'.$row['oliver-score'].'%</td>';
				echo '<td>'.$row['levenshtein-score'].'</td>';
				echo '</tr>';
			}
		} else {
			echo 'NO RESULTS'; 
		}
		
		echo '</table>';
	}
?>

<?php include('includes/footer.php');?>