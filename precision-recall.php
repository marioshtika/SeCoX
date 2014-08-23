<?php include('includes/header.php');?>

<h1>Precision Recall</h1>
<hr>

<?php

	$query = "SELECT * FROM rankingsites";
	$result = $mysqli->query($query) or die($mysqli->error.__LINE__);
		
	echo '<table class="table table-bordered">';
	
	// GOING THROUGH THE DATA
	if($result->num_rows > 0) {
		echo '<tr>';
		echo '<th>Site</th>';
		echo '<th>Precision</th>';
		echo '<th>Recall</th>';
		echo '<th>F-measure</th>';
		echo '</tr>';
		
		$sum_correct_answers = 0;
		$sum_incorrect_answers = 0;
		$sum_not_found_answers = 0;
		
		while($row_rankings = $result->fetch_assoc()) {
			// found answers
			$query_links_count = "SELECT COUNT(*) as all_links_row FROM parsingrows WHERE `site-id` = '".$row_rankings['id']."' AND `dbpedia-uri` <> ''";
			$result_links_count = $mysqli->query($query_links_count) or die($mysqli->error.__LINE__);
			$row_links_count = $result_links_count->fetch_assoc();
			$all_answers = $row_links_count['all_links_row'];
			// correct answers
			$query_matches_count = "SELECT COUNT(*) as all_matches_row FROM parsingrows WHERE `site-id` = '".$row_rankings['id']."' AND `oliver-score` > ".$row_rankings['best-oliver-score']." AND `dbpedia-uri` <> ''";
			$result_matches_count = $mysqli->query($query_matches_count) or die($mysqli->error.__LINE__);
			$row_matches_count = $result_matches_count->fetch_assoc();
			$correct_answers = $row_matches_count['all_matches_row'];
			$sum_correct_answers += $correct_answers;
			// all links
			$query_university_count = "SELECT COUNT(*) as all_university_row FROM parsingrows WHERE `site-id` = '".$row_rankings['id']."'";
			$result_university_count = $mysqli->query($query_university_count) or die($mysqli->error.__LINE__);
			$row_university_count = $result_university_count->fetch_assoc();
			$all_links = $row_university_count['all_university_row'];
			// incorrect answers
			$incorrect_answers = $all_answers - $correct_answers;
			$sum_incorrect_answers += $incorrect_answers;
			
			$not_found_answers = $all_links - $all_answers;
			$sum_not_found_answers += $not_found_answers;
			
			echo '<tr>';
			
			echo '<td>'.$row_rankings['site'].'</td>';

			echo '<td>';
			$precision = ($correct_answers + $incorrect_answers != 0) ? $correct_answers/($correct_answers + $incorrect_answers) : 0;
			echo $correct_answers.' / '.($correct_answers + $incorrect_answers).' = '.number_format((float)$precision, 4, '.', '');
			echo '</td>';
			
			echo '<td>';
			$recall = ($correct_answers + $not_found_answers != 0) ? $correct_answers/($correct_answers + $not_found_answers) : 0;
			echo $correct_answers.' / '.($correct_answers + $not_found_answers).' = '.number_format((float)$recall, 4, '.', '');
			echo '</td>';
			
			echo '<td>';
			$f_measure = ($precision + $recall != 0) ? 2 * ($precision * $recall) / ($precision + $recall) : 0;
			echo number_format((float)$f_measure, 4, '.', '');
			echo '</td>';
			
			echo '</tr>';
		}
	} else {
		echo 'NO RESULTS'; 
	}
	
	echo '</table>';
	
	echo '<b>Average Precision: </b>';
	$average_precision = ($sum_correct_answers + $sum_incorrect_answers != 0) ? $sum_correct_answers / ($sum_correct_answers + $sum_incorrect_answers) : 0;
	echo $sum_correct_answers.' / '.($sum_correct_answers + $sum_incorrect_answers).' = '.number_format((float)$average_precision, 4, '.', '');
	echo '<br />';
	
	echo '<b>Average Recall: </b>';
	$average_recall = ($sum_correct_answers + $sum_not_found_answers != 0) ? $sum_correct_answers / ($sum_correct_answers + $sum_not_found_answers) : 0;
	echo $sum_correct_answers.' / '.($sum_correct_answers + $sum_not_found_answers).' = '.number_format((float)$average_recall, 4, '.', '');;
	
?>

<?php include('includes/footer.php');?>