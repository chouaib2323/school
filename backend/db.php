<?php
$mysqli = new mysqli("localhost", "root", "", "school");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}
?>
