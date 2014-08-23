<?php include('includes/header.php');?>

<h1>Extract Entities</h1>
<hr>

<?php
	if(isset($_GET['id'])) {
	
		// The maximum execution time, in seconds. If set to zero, no time limit is imposed.
		set_time_limit(0);
		date_default_timezone_set('Europe/Athens');
		// import html dom class
		include('includes/parse-html/simple_html_dom.php');
		
		$query_pages = "SELECT * FROM rankingpages WHERE `site-id` = ".$_GET['id'];
		$result_pages = $mysqli->query($query_pages) or die($mysqli->error.__LINE__);
		if($result_pages->num_rows > 0) {
			while($row_pages = $result_pages->fetch_assoc()) {
				// Create DOM from URL or file
				$html = file_get_html($row_pages['ranking-url']);
				
				include('includes/parsing-files/'.$row_pages['parsing-file']);
			}
		}
		
		$date = date('d/m/Y H:i');
		$query_sites = "UPDATE rankingsites SET `last-update` = '".$date."' WHERE id = ".$_GET['id'];
		$result_sites = $mysqli->query($query_sites) or die($mysqli->error.__LINE__);
		
		echo '<div class="alert alert-success text-center" role="alert"><b>Well done!</b> You successfully parsed your source. </div>';
		
	}
	$query_sites = "SELECT * FROM rankingsites";
	$result_sites = $mysqli->query($query_sites) or die($mysqli->error.__LINE__);
	
	?>
	<script>
	function parseButton(e) {
		if (!confirm('This action will insert the information parsed from the site (It will overide existing information)\nThis will take several minutes. Please do not close this window until it is finished.\nAre you sure you want to continue?')) {
			e.preventDefault();
		}
	}
	</script>
	<?php
	
	echo '<table class="table table-bordered">';
	
	// GOING THROUGH THE DATA
	if($result_sites->num_rows > 0) {
		echo '<tr>';
		echo '<th>Site</th>';
		echo '<th class="text-center">Parsed Universities</th>';
		echo '<th class="text-center">Last Update</th>';
		echo '<th class="text-center"></th>';
		echo '</tr>';
		while($row_sites = $result_sites->fetch_assoc()) {
			echo '<tr>';
			echo '<td>'.$row_sites['site'].'</td>';
			echo '<td class="text-center">';
			$query_university_count = "SELECT COUNT(*) as all_universities FROM parsingrows WHERE `site-id` = '".$row_sites['id']."'";
			$result_university_count = $mysqli->query($query_university_count) or die($mysqli->error.__LINE__);
			$row_university_count = $result_university_count->fetch_assoc();
			echo $row_university_count['all_universities'];
			echo '</td>';
			echo '<td class="text-center">'.$row_sites['last-update'].'</td>';
			echo '<td class="text-center"><a href="parse.php?id='.$row_sites['id'].'" class="btn btn-danger btn-sm" role="button" onclick="parseButton(event);"><span class="glyphicon glyphicon-floppy-save"></span> Parse</a></td>'; 
			echo '</tr>';
		}
	} else {
		echo 'NO RESULTS'; 
	}
	
	echo '</table>';

?>
		
<?php include('includes/footer.php');?>