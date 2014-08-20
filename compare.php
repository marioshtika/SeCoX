<?php include('includes/header.php');?>

<h1>Compare</h1>
<hr>

<?php
	$query = "SELECT * FROM parsingrows ORDER BY `dbpedia-uri`";
	$result = $mysqli->query($query) or die($mysqli->error.__LINE__);

	echo '<table class="table">';
	echo '<tr>';
	echo '<th>University / DBpedia URI</th>';
	echo '<th><a href="http://www.timeshighereducation.co.uk/"><img src="http://www.timeshighereducation.co.uk/favicon.ico" width="20" border="0"></a></th>';
	echo '<th><a href="http://www.topuniversities.com/"><img src="http://www.topuniversities.com/sites/qs.topuni/files/favicon_0.png" width="20" border="0"></a></th>';
	echo '<th><a href="http://www.shanghairanking.com/"><img src="http://www.shanghairanking.com/image/favicon.ico" width="20" border="0"></a></th>';
	echo '<th><a href="http://www.leidenranking.com/"><img src="http://www.leidenranking.com/favicon.ico" width="20" border="0"></a></th>';
	echo '<th><a href="http://www.urapcenter.org/"><img src="http://www.urapcenter.org/2013/favicon.ico" width="20" border="0"></a></th>';
	echo '<th><a href="http://www.shanghairanking.com/"><img src="http://www.webometrics.info/sites/default/files/logo2_0.png" width="20" border="0"></a></th>';
	echo '</tr>';
	
	// GOING THROUGH THE DATA
	if($result->num_rows > 0) {
		$dbpedia_uri = '';
		$score1 = '-';
		$score2 = '-';
		$score3 = '-';
		$score4 = '-';
		$score5 = '-';
		$score6 = '-';
		
		while($row_parsing = $result->fetch_assoc()) {
			//echo '-'.$row_parsing['dbpedia-uri'].' - '.$row_parsing['ranking'].'<br />';
			
			if($dbpedia_uri != $row_parsing['dbpedia-uri']) {
				if(!empty($dbpedia_uri)) {
					//url decode
					$dbpedia_uri = urldecode($dbpedia_uri);
					echo '<tr>';
					echo '<td><a href="'.$dbpedia_uri.'" target="blank">'.$dbpedia_uri.'</a></td>';
					echo '<td>'.$score1.'</td>';
					echo '<td>'.$score2.'</td>';
					echo '<td>'.$score3.'</td>';
					echo '<td>'.$score4.'</td>';
					echo '<td>'.$score5.'</td>';
					echo '<td>'.$score6.'</td>';
					echo '</tr>';
				}
				
				$dbpedia_uri = $row_parsing['dbpedia-uri'];
				$score1 = '-';
				$score2 = '-';
				$score3 = '-';
				$score4 = '-';
				$score5 = '-';
				$score6 = '-';
			}
			
			if($row_parsing['site'] == "www.timeshighereducation.co.uk") {
				$score1 = $row_parsing['ranking'];
			} else if($row_parsing['site'] == "www.topuniversities.com") {
				$score2 = $row_parsing['ranking'];
			} else if($row_parsing['site'] == "www.shanghairanking.com") {
				$score3 = $row_parsing['ranking'];
			} else if($row_parsing['site'] == "www.leidenranking.com") {
				$score4 = $row_parsing['ranking'];
			} else if($row_parsing['site'] == "www.urapcenter.org") {
				$score5 = $row_parsing['ranking'];
			} else if($row_parsing['site'] == "www.webometrics.info") {
				$score6 = $row_parsing['ranking'];
			}
		}
	} else {
		echo 'NO RESULTS'; 
	}
	
	echo '</table>';
?>

<?php include('includes/footer.php');?>