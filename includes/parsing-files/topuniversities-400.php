<?php

// Find all table cells
foreach($html->find('#ranking-table tbody tr') as $element_table_row) {
	$univeristy = $element_table_row->find('span', 3);
	if(!empty($univeristy)) {
		$univeristy = $univeristy->plaintext;
		
		$ranking = $element_table_row->find('span', 0);
		$ranking = $ranking->plaintext;
		
		include('includes/insert-row.php');
	}
}

?>