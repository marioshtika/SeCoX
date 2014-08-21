<?php include('includes/header.php');?>

<h1>Precision Recall</h1>
<hr>

<?php

	$query = "SELECT * FROM rankingsites GROUP BY title";
	$result = $mysqli->query($query) or die($mysqli->error.__LINE__);
		
	echo '<table class="table">';
	
	// GOING THROUGH THE DATA
	if($result->num_rows > 0) {
		echo '<tr>';
		echo '<th>Sites</th>';
		echo '<th>Precision</th>';
		echo '<th>Recall</th>';
		echo '</tr>';
		while($row_rankings = $result->fetch_assoc()) {
			// found answers
			$query_links_count = "SELECT COUNT(*) as all_links_row FROM parsingrows WHERE site = '".$row_rankings['title']."' AND `dbpedia-uri` <> ''";
			$result_links_count = $mysqli->query($query_links_count) or die($mysqli->error.__LINE__);
			$row_links_count = $result_links_count->fetch_assoc();
			$all_answers = $row_links_count['all_links_row'];
			// correct answers
			$query_matches_count = "SELECT COUNT(*) as all_matches_row FROM parsingrows WHERE site = '".$row_rankings['title']."' AND `oliver-score` > ".$oliver_score[$row_rankings['title']]." AND `dbpedia-uri` <> ''";
			$result_matches_count = $mysqli->query($query_matches_count) or die($mysqli->error.__LINE__);
			$row_matches_count = $result_matches_count->fetch_assoc();
			$correct_answers = $row_matches_count['all_matches_row'];
			// all links
			$query_university_count = "SELECT COUNT(*) as all_university_row FROM parsingrows WHERE site = '".$row_rankings['title']."'";
			$result_university_count = $mysqli->query($query_university_count) or die($mysqli->error.__LINE__);
			$row_university_count = $result_university_count->fetch_assoc();
			$all_links = $row_university_count['all_university_row'];
			// incorrect answers
			$incorrect_answers = $all_answers - $correct_answers;
			$not_found_answers = $all_links - $all_answers;
			
			echo '<tr>';
			
			echo '<td>'.$row_rankings['title'].'</td>';

			echo '<td>';
			echo ($correct_answers + $incorrect_answers != 0) ? $correct_answers.' / '.($correct_answers + $incorrect_answers).' = '.$correct_answers/($correct_answers + $incorrect_answers) : '0';
			echo '</td>';
			
			echo '<td>';
			echo ($correct_answers + $not_found_answers != 0) ? $correct_answers.' / '.($correct_answers + $not_found_answers).' = '.$correct_answers/($correct_answers + $not_found_answers) : '0';
			echo '</td>';
			
			echo '</tr>';
		}
	} else {
		echo 'NO RESULTS'; 
	}
	
	echo '</table>';
?>

<?php include('includes/footer.php');?>