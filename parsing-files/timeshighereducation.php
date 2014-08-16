<?php

// Find all table cells
foreach($html->find('table tbody tr') as $element_table_row) {
	$univeristy = $element_table_row->find('td', 1);
	if(!empty($univeristy)) {
		// remove double spaces
		$univeristy = preg_replace( "/\s+/", " ", $univeristy->plaintext);
		
		$ranking = $element_table_row->find('td strong', 0);
		$ranking = $ranking->plaintext;
		
		include('includes/insert-row.php');
	}
}

?>