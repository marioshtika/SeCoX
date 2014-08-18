<?php include('includes/header.php');?>

<h1>Sites</h1>
<hr>

<?php
	$query = "SELECT * FROM rankingsites ORDER BY `title` ASC, `url-page` ASC";
	$result = $mysqli->query($query) or die($mysqli->error.__LINE__);

	echo '<table class="table">';
	
	// GOING THROUGH THE DATA
	if($result->num_rows > 0) {
		echo '<tr>';
		echo '<th>Site / Source</th>';
		echo '<th>Pages</th>';
		echo '</tr>';
		while($row_rankings = $result->fetch_assoc()) {
			echo '<tr>';
			echo '<td>'.$row_rankings['title'].'<br /><a href="'.$row_rankings['ranking-url'].'" target="_blank">'.$row_rankings['ranking-url'].'</a></td>'; 
			echo '<td>';
			if($row_rankings['url-page'] != null) {
				echo 'page&nbsp;'.$row_rankings['url-page'];
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