<?php include('includes/header.php');?>

<?php
	$query = "SELECT * FROM rankingsites";
	$result = $mysqli->query($query) or die($mysqli->error.__LINE__);

	echo '<table border="1">';
	
	// GOING THROUGH THE DATA
	if($result->num_rows > 0) {
		while($row_rankings = $result->fetch_assoc()) {
			echo '<tr>';
			echo '<td>'.$row_rankings['title'].'</td>'; 
			echo '<td><a href="result.php?site='.$row_rankings['title'].'">View Results</a></td>'; 
			//echo '<td><a href="'.$row_rankings['ranking-url'].'" target="_blank">View Site</a></td>'; 
			echo '<td>'.$row_rankings['last-update'].'</td>'; 
			echo '<td><a href="parse.php?id='.$row_rankings['id'].'">Parse Now</a></td>';
			echo '</tr>';
		}
	} else {
		echo 'NO RESULTS'; 
	}
	
	echo '</table>';
?>
<br /><br /><br />
<?php
	$query = "SELECT * FROM parsingrows ORDER BY `dbpedia-uri`";
	$result = $mysqli->query($query) or die($mysqli->error.__LINE__);

	echo '<table border="1">';
	echo '<tr><th>dbpedia URI</th><th>www.timeshighereducation.co.uk</th><th>www.topuniversities.com</th><th>www.shanghairanking.com</th></tr>';
	// GOING THROUGH THE DATA
	if($result->num_rows > 0) {
		$dbpediaURI = '';
		$score1 = '-';
		$score2 = '-';
		$score3 = '-';
		
		while($row_parsing = $result->fetch_assoc()) {
			//echo '-'.$row_parsing['dbpedia-uri'].' - '.$row_parsing['ranking'].'<br />';
			
			if($dbpediaURI != $row_parsing['dbpedia-uri']) {
				if(!empty($dbpediaURI)) {
					echo '<tr>';
					echo '<td>'.$dbpediaURI.'</td>';
					echo '<td>'.$score1.'</td>';
					echo '<td>'.$score2.'</td>';
					echo '<td>'.$score3.'</td>';
					echo '</tr>';
				}
				
				$dbpediaURI = $row_parsing['dbpedia-uri'];
				$score1 = '-';
				$score2 = '-';
				$score3 = '-';
			}
			
			if($row_parsing['site'] == "www.timeshighereducation.co.uk") {
				$score1 = $row_parsing['ranking'];
			} else if($row_parsing['site'] == "www.topuniversities.com") {
				$score2 = $row_parsing['ranking'];
			} else if($row_parsing['site'] == "www.shanghairanking.com") {
				$score3 = $row_parsing['ranking'];
			}
		}
	} else {
		echo 'NO RESULTS'; 
	}
	
	echo '</table>';
?>

<?php include('includes/footer.php');?>