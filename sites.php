<?php include('includes/header.php');?>

<?php
	$query = "SELECT * FROM rankingsites ORDER BY `title` ASC, `url-page` ASC";
	$result = $mysqli->query($query) or die($mysqli->error.__LINE__);

	echo '<table border="1">';
	
	// GOING THROUGH THE DATA
	if($result->num_rows > 0) {
		echo '<tr>';
		echo '<th>Sites</th>';
		echo '<th>Source</th>';
		echo '</tr>';
		while($row_rankings = $result->fetch_assoc()) {
			echo '<tr>';
			echo '<td>'.$row_rankings['title'].'</td>';
			echo '<td><a href="'.$row_rankings['ranking-url'].'" target="_blank">'.$row_rankings['ranking-url'].'</a>'; 
			if($row_rankings['url-page'] != null) {
				echo ' (page '.$row_rankings['url-page'].')';
			}
			echo '</td>';
			echo '</tr>';
		}
	} else {
		echo 'NO RESULTS'; 
	}
	
	echo '</table>';
?>

<?php include('includes/footer.php');?>