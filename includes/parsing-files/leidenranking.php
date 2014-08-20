<?php

// Find all table cells
foreach($html->find('table tbody tr') as $element_table_row) {
	$univeristy = $element_table_row->find('td', 1);
	if(!empty($univeristy)) {
		$univeristy = $univeristy->datatooltip; // I changed attribute 'data-tooltip' to 'datatooltip' from sites/leidenranking.html
				
		$ranking = $element_table_row->find('td', 0);
		$ranking = $ranking->plaintext;
		
		include('includes/insert-row.php');
	}
}

?>