<?php
// THIS PART CONNECTS TO THE DATABASE
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "monitoring";

// Create connection
$db = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}
?>
