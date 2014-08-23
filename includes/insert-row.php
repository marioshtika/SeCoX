<?php		
	// insert new rows
	$query = "REPLACE INTO parsingrows VALUES (NULL, '".$univeristy."', '".$ranking."', '', '', '', '".$row_pages['site-id']."')";
	$mysqli->query($query);
?>