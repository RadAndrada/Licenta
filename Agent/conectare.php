<?php
$hostname = 'localhost';
$username = 'root';
$password = '';
$db = 'imobiliare';
$mysqli = new mysqli($hostname, $username, $password, $db);

if ($mysqli->connect_error) {
    die("Conexiunea la baza de date a esuat: " . $mysqli->connect_error);
}
?>