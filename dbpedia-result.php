<?php include('includes/header.php');?>

<h1>Entity Link Result</h1>
<hr>

<?php
	if(isset($_GET['site'])) {
	
		// The maximum execution time, in seconds. If set to zero, no time limit is imposed.
		set_time_limit(0);
		
		$query = "SELECT * FROM parsingrows WHERE `site-id` = '".$_GET['site']."' ORDER BY university";
		$result = $mysqli->query($query) or die($mysqli->error.__LINE__);
	
		echo '<table class="table table-bordered">';
		echo '<tr>';
		echo '<th>University</td>';
		echo '<th>DBpedia URI</td>';
		echo '<th class="text-center">Rank</th>';
		echo '</tr>';
		
		// GOING THROUGH THE DATA
		if($result->num_rows > 0) {
			while($row = $result->fetch_assoc()) {
				echo '<tr>';
				echo '<td>'.$row['university'].'</td>';
				echo '<td><a href="'.$row['dbpedia-uri'].'">'.$row['dbpedia-uri'].'</a></td>';
				echo '<td class="text-center">'.$row['ranking'].'</td>';
				echo '</tr>';
			}
		} else {
			echo 'NO RESULTS'; 
		}
		
		echo '</table>';
	}
?>

<?php include('includes/footer.php');?>