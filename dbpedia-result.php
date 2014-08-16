<?php include('includes/header.php');?>

<?php
	if(isset($_GET['site'])) {
	
		// The maximum execution time, in seconds. If set to zero, no time limit is imposed.
		set_time_limit(0);
		
		$query = "SELECT * FROM parsingrows WHERE site = '".$_GET['site']."' ORDER BY university";
		$result = $mysqli->query($query) or die($mysqli->error.__LINE__);
	
		echo '<table border="1">';
		
		// GOING THROUGH THE DATA
		if($result->num_rows > 0) {
			while($row = $result->fetch_assoc()) {
				echo '<tr>';
				echo '<td>'.$row['university'].'</td>';
				echo '<td>'.$row['ranking'].'</td>';
				echo '<td><a href="'.$row['dbpedia-uri'].'">'.$row['dbpedia-uri'].'</a></td>';
				echo '<td>'.$row['dbpedia-score'].'</td>';
				echo '</tr>';
			}
		} else {
			echo 'NO RESULTS'; 
		}
		
		echo '</table>';
	}
?>

<?php include('includes/footer.php');?>