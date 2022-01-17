<?php
//ENTER YOUR DATABASE CONNECTION INFO BELOW:
$hostname="localhost";
$database="terminplaner";
$username="root";
$password="<PASSWORD>";
$port="3306";

//DO NOT EDIT BELOW THIS LINE
$mysqli = @(new mysqli($hostname, $username, $password, $database));
if ($mysqli->connect_error) {
	echo "Fehler bei der Verbindung: " .
	mysqli_connect_error() . "<hr />";
	exit();
}
if (!$mysqli->set_charset("utf8")) {
	echo "Fehler beim Laden von UTF8 ". $mysqli->error;
}
?> 
