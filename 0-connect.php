<?php
// THIS PART CONNECTS TO THE DATABASE
$servername = "localhost";
$username = "root";
$password = "";
$dbname = $_SESSION["teacherName"];
//$dbname = "monitoring";

// Create connection with server
$conn = new mysqli($servername, $username, $password);
$create = $conn->query("CREATE Database IF NOT EXISTS `$dbname`");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create connection with database in server
$db = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}
?>