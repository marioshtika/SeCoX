<?php		
	// insert new rows
	$query = "REPLACE INTO parsingrows VALUES (NULL, '".$univeristy."', '".$ranking."', '', '', '', '', '".$row['title']."')";		
	$mysqli->query($query);
?>