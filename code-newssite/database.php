<?php
// Content of database.php

$mysqli = new mysqli('localhost', 'sunshinenews', 'newssite', 'sunshine_news');

if($mysqli->connect_errno) {
	printf("Connection Failed: %s\n", $mysqli->connect_error);
	exit;
}
?>