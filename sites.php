<?php include('includes/header.php');?>

<h1>Sites</h1>
<hr>

<?php
	$query = "SELECT * FROM rankingsites";
	$result = $mysqli->query($query) or die($mysqli->error.__LINE__);

	echo '<table class="table table-bordered">';
	
	// GOING THROUGH THE DATA
	if($result->num_rows > 0) {
		echo '<tr>';
		echo '<th>Description</th>';
		echo '<th>Site</th>';
		echo '</tr>';
		while($row_rankings = $result->fetch_assoc()) {
			echo '<tr>';
			echo '<td>'.$row_rankings['site-description'].'</td>';
			echo '<td><a href="'.$row_rankings['site-url'].'" target="_blank">'.$row_rankings['site'].'</a></td>';
			echo '</tr>';
		}
	} else {
		echo 'NO RESULTS'; 
	}
	
	echo '</table>';
?>

<?php include('includes/footer.php');?>