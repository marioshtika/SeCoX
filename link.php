<?php include('includes/header.php');?>

<?php
	if(isset($_GET['site'])) {
	
		// The maximum execution time, in seconds. If set to zero, no time limit is imposed.
		set_time_limit(0);
		
		$query = "SELECT * FROM parsingrows WHERE site = '".$_GET['site']."'";
		$result = $mysqli->query($query) or die($mysqli->error.__LINE__);
		
	/*
	// lookup in dbpedia
	$dbpedia_url = "http://lookup.dbpedia.org/api/search.asmx/KeywordSearch?QueryClass=organisation&QueryString=".$univeristy;
	$xml=simplexml_load_file($dbpedia_url);
	
	// insert new rows
	$query = "INSERT INTO parsingrows VALUES (NULL, '".$univeristy."', ".$ranking.", '".$xml->Result->URI."','".$row['title']."')";
	$mysqli->query($query);
	*/
	
	// init NLP DBpediaSpotlight
	$api = new DBpediaSpotlight;
	$api->init_nlp($univeristy);	
	$api->query();
	$curl_info = $api->getCurlInfo();	
	$entity = $api->getEntities();
	$dbpedia_uri = (isset($entity[0]['name'])) ? $entity[0]['name'] : '';
	$dbpedia_score = (isset($entity[0]['score'])) ? $entity[0]['score'] : 0.0;
	
	//echo '('.$ranking.') -> '.$dbpedia_uri.' -> score: ' . $dbpedia_score . '<br />';	
	//echo $univeristy.'<br />';
?>

<?php include('includes/footer.php');?>