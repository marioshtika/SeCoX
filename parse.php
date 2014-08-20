<?php include('includes/header.php');?>

<h1>Parse</h1>
<hr>

<?php
	if(isset($_GET['id'])) {
	
		// The maximum execution time, in seconds. If set to zero, no time limit is imposed.
		set_time_limit(0);
		date_default_timezone_set('Europe/Athens');
		
		$query = "SELECT * FROM rankingsites WHERE id = " . $_GET['id'];
		$result = $mysqli->query($query) or die($mysqli->error.__LINE__);
		$row = $result->fetch_assoc();
		
		// import html dom class
		include('includes/parse-html/simple_html_dom.php');
		
		// Create DOM from URL or file
		$html = file_get_html($row['ranking-url']);
		
		include('includes/parsing-files/'.$row['parsing-file']);
		
		$date = date('d/m/Y H:i');
		$query = "UPDATE rankingsites SET `last-update` = '".$date."' WHERE id = ".$_GET['id'];
		$result = $mysqli->query($query) or die($mysqli->error.__LINE__);
		
		echo "Done";
		
	} else {
		$query = "SELECT * FROM rankingsites ORDER BY `title` ASC, `url-page` ASC";
		$result = $mysqli->query($query) or die($mysqli->error.__LINE__);
		
		?>
		<script>
		function parseButton(e) {
			if (!confirm('This action will insert the information parsed from the site (It will overide existing information)\nThis will take several minutes. Please do not close this window until it is finished.\nAre you sure you want to continue?')) {
				e.preventDefault();
			}
		}
		</script>
		<?php
		
		echo '<table class="table">';
		
		// GOING THROUGH THE DATA
		if($result->num_rows > 0) {
			echo '<tr>';
			echo '<th>Source</th>';
			echo '<th>Script Parsing File</th>';
			echo '<th>Last Update</th>';
			echo '<th>Parse</th>';
			echo '</tr>';
			while($row_rankings = $result->fetch_assoc()) {
				echo '<tr>';
				echo '<td><a href="'.$row_rankings['ranking-url'].'" target="_blank">'.$row_rankings['title'].'</a>'; 
				if($row_rankings['url-page'] != null) {
					echo ' (page '.$row_rankings['url-page'].')';
				}
				echo '</td>';
				echo '<td>'.$row_rankings['parsing-file'].'</td>'; 
				echo '<td>'.$row_rankings['last-update'].'</td>'; 
				echo '<td><a href="parse.php?id='.$row_rankings['id'].'" class="btn btn-danger" role="button" onclick="parseButton(event);">Parse Now</a></td>'; 
				echo '</tr>';
			}
		} else {
			echo 'NO RESULTS'; 
		}
		
		echo '</table>';
	}
?>
		
<?php include('includes/footer.php');?>