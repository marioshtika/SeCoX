<?php include('includes/header.php');?>

<h1>Merged Data</h1>
<hr>
<a href="download/merged-data.csv" class="btn btn-success" role="button"><span class="glyphicon glyphicon-download-alt"></span> Download Merged Data</a>
<br /><br />
<?php
	$query = "SELECT * FROM parsingrows, rankingsites WHERE parsingrows.`site-id` = rankingsites.id AND parsingrows.`oliver-score` >= rankingsites.`best-oliver-score` ORDER BY `dbpedia-uri`";
	$result = $mysqli->query($query) or die($mysqli->error.__LINE__);

	echo '<table class="table table-bordered">';
	echo '<tr>';
	echo '<th>University / DBpedia URI</th>';
	echo '<th class="text-center"><a href="http://www.timeshighereducation.co.uk/" target="_blank"><img src="http://www.timeshighereducation.co.uk/favicon.ico" width="20" border="0"></a></th>';
	echo '<th class="text-center"><a href="http://www.topuniversities.com/" target="_blank"><img src="http://www.topuniversities.com/sites/qs.topuni/files/favicon_0.png" width="20" border="0"></a></th>';
	echo '<th class="text-center"><a href="http://www.shanghairanking.com/" target="_blank"><img src="http://www.shanghairanking.com/image/favicon.ico" width="20" border="0"></a></th>';
	echo '<th class="text-center"><a href="http://www.leidenranking.com/" target="_blank"><img src="http://www.leidenranking.com/favicon.ico" width="20" border="0"></a></th>';
	echo '<th class="text-center"><a href="http://www.urapcenter.org/" target="_blank"><img src="http://www.urapcenter.org/2013/favicon.ico" width="20" border="0"></a></th>';
	echo '<th class="text-center"><a href="http://www.webometrics.info/" target="_blank"><img src="http://www.webometrics.info/sites/default/files/logo2_0.png" width="20" border="0"></a></th>';
	echo '</tr>';
	
	// GOING THROUGH THE DATA
	if($result->num_rows > 0) {
	
		$data = array();
		$data[] = array("University", "DBpedia URI", "timeshighereducation", "topuniversities", "shanghairanking", "leidenranking", "urapcenter", "webometrics");
		$dbpedia_uri = '';
		$score1 = '';
		$score2 = '';
		$score3 = '';
		$score4 = '';
		$score5 = '';
		$score6 = '';
		
		while($row_parsing = $result->fetch_assoc()) {
			//echo '-'.$row_parsing['dbpedia-uri'].' - '.$row_parsing['ranking'].'<br />';
			
			if($dbpedia_uri != $row_parsing['dbpedia-uri']) {
				if(!empty($dbpedia_uri)) {
					//url decode
					$dbpedia_uri = urldecode($dbpedia_uri);
					echo '<tr>';
					echo '<td>'.$univeristy.'<br /><a href="'.$dbpedia_uri.'" target="blank">'.$dbpedia_uri.'</a></td>';
					echo '<td class="text-center">'.$score1.'</td>';
					echo '<td class="text-center">'.$score2.'</td>';
					echo '<td class="text-center">'.$score3.'</td>';
					echo '<td class="text-center">'.$score4.'</td>';
					echo '<td class="text-center">'.$score5.'</td>';
					echo '<td class="text-center">'.$score6.'</td>';
					echo '</tr>';
					$data[] = array($univeristy, $dbpedia_uri, $score1, $score2, $score3, $score4, $score5, $score6);
				}
				
				$dbpedia_uri = $row_parsing['dbpedia-uri'];
				$univeristy = $row_parsing['university'];
				$score1 = '';
				$score2 = '';
				$score3 = '';
				$score4 = '';
				$score5 = '';
				$score6 = '';
			}
			
			if($row_parsing['site-id'] == "1") {
				$score1 = $row_parsing['ranking'];
			} else if($row_parsing['site-id'] == "2") {
				$score2 = $row_parsing['ranking'];
			} else if($row_parsing['site-id'] == "3") {
				$score3 = $row_parsing['ranking'];
			} else if($row_parsing['site-id'] == "4") {
				$score4 = $row_parsing['ranking'];
			} else if($row_parsing['site-id'] == "5") {
				$score5 = $row_parsing['ranking'];
			} else if($row_parsing['site-id'] == "6") {
				$score6 = $row_parsing['ranking'];
			}
		}
	} else {
		echo 'NO RESULTS'; 
	}
	
	echo '</table>';
	
	$fp = fopen('download/merged-data.csv', 'w');

	foreach ($data as $fields) {
		fputcsv($fp, $fields);
	}

	fclose($fp);
?>

<a href="download/merged-data.csv" class="btn btn-success" role="button"><span class="glyphicon glyphicon-download-alt"></span> Download Merged Data</a>

<?php include('includes/footer.php');?>
