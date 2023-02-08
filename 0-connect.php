<?php
    $conn = new mysqli("localhost", "root", "", "temp");
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $sql = "SELECT val FROM temptb WHERE varname = 'teacherName' ORDER BY id DESC LIMIT 1";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $tempvar1 = $row["val"];
        mysqli_close($conn);
    }
// THIS PART CONNECTS TO THE DATABASE
$servername = "localhost";
$username = "root";
$password = "";
$dbname = $tempvar1;
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
