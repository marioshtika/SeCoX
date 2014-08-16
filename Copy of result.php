<?php include('includes/header.php');?>

<?php
	if(isset($_GET['site'])) {
	
		// The maximum execution time, in seconds. If set to zero, no time limit is imposed.
		set_time_limit(0);
		
		$query = "SELECT * FROM parsingrows WHERE site = '".$_GET['site']."' ORDER BY ranking";
		$result = $mysqli->query($query) or die($mysqli->error.__LINE__);
	
		echo 'Oliver = Similar Text from World\'s Best Algorithms by Oliver<br />';
		echo 'Levenshtein = Levenshtein distance between two strings<br /><br />';
		
		echo '<table border="1">';
		
		// GOING THROUGH THE DATA
		if($result->num_rows > 0) {
			while($row = $result->fetch_assoc()) {
				echo '<tr>';
				echo '<td>'.$row['university'].'</td>';
				
				echo '<td>';
				$url = "http://dbpedia.org/sparql?default-graph-uri=http%3A%2F%2Fdbpedia.org&query=select+DISTINCT+str%28%3Fn%29+as+%3Fname+where+%7B%0D%0A%7B%3C".$row['dbpedia-uri']."%3E+rdfs%3Alabel+%3Fn.+%0D%0AFILTER%28langMatches%28lang%28%3Fn%29%2C+%22EN%22%29%29+%7D%0D%0AUNION%0D%0A%7B%3C".$row['dbpedia-uri']."%3E+foaf%3Aname+%3Fn.+%0D%0AFILTER%28langMatches%28lang%28%3Fn%29%2C+%22EN%22%29%29+%7D%0D%0AUNION%0D%0A%7B%3C".$row['dbpedia-uri']."%3E+dbpprop%3Aname+%3Fn.+%0D%0AFILTER%28langMatches%28lang%28%3Fn%29%2C+%22EN%22%29%29+%7D%0D%0A%7D&format=application%2Fsparql-results%2Bjson&timeout=3000&debug=on";
				$json = file_get_contents($url);
				$obj = json_decode($json);
				$bindings = $obj->results->bindings;
				
				$oliver_best_match = '';
				$max_oliver_percent = 0;
				
				$levenshtein_best_match = '';
				$min_levenshtein_percent = 99999;
				
				foreach($bindings as $binding) {
					$binding_value = $binding->name->value;
					echo $binding_value;
					echo "<br />";
					
					similar_text($row['university'], $binding_value, $oliver_percent);
					echo " (Oliver = ".$oliver_percent.")";
					echo "<br />";
					if($oliver_percent > $max_oliver_percent) {
						$max_oliver_percent = $oliver_percent;
						$oliver_best_match = $binding_value;
					}
					
					$levenshtein_percent = levenshtein($row['university'], $binding_value);
					echo " (Levenshtein = ".$levenshtein_percent.")";
					echo "<br />";
					if($levenshtein_percent < $min_levenshtein_percent) {
						$min_levenshtein_percent = $levenshtein_percent;
						$levenshtein_best_match = $binding_value;
					}	
					echo "<br />";
				}
				
				echo 'best oliver match: '.$oliver_best_match;
				echo '<br />';
				echo 'best levenshtein match: '.$levenshtein_best_match;
				echo '</td>';
				
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