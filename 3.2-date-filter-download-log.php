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
        $teacher = $row["val"];
        mysqli_close($conn);
    }

if (isset($_POST["download_s_csv"])){
    header("Location: 3.2-date-filter-csv-summary.php");
}

if (isset($_POST["download_csv"])){
    header("Location: 3.2-date-filter-csv-detailed.php");
}

//specific date
if (isset($_POST["download"])){
    header("Location: 3.2-date-filter-csv-specificdate.php");
}

//SUMMARY PDF
if (isset($_POST["download_s_pdf"])){
    $teacher_name = strtoupper($teacher);
    include '5-pdf-summary.php';
}

if (isset($_POST["download_pdf"])){
    $teacher_name = strtoupper($teacher);
    include '5-pdf-detailed.php';
}
?>
