<?php

// CONNECT TO THE DATABASE
$DB_NAME = 'diplomatiki';
$DB_HOST = 'localhost';
$DB_USER = 'admin';
$DB_PASS = 'admin';

$mysqli = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
if (mysqli_connect_errno()) {
	printf("Connect failed: %s\n", mysqli_connect_error());
	exit();
}
?>