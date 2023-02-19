<!--
THIS PAGE IS FOR DISPLAYING THE LOGIN INFO FOR THE SELECTED TEACHER AND
THE TEXT FIELDS TO BE UPDATED.
After selecting the "update" button, it will
redirect to teacher-login-update.php
-->
<?php
session_start();

if (isset($_POST['uploadBtn']) && $_POST['uploadBtn'] == 'Modify Teacher Password') {
    $teacherSelected = $_POST['teacherSelect'];
    //for the teacher-login-update.php file
    //the variable should be the idnumber selected
    //$_SESSION["teacherToBeUpdated"] = $_POST['teacherSelect'];

    if ($teacherSelected == '0'){
	    $conn = new mysqli("localhost", "root", "", "temp");
	    // Check connection
	    if ($conn->connect_error) {
		    die("Connection failed: " . $conn->connect_error);
	    }
	    $sql = "INSERT INTO temptb (varname, val) VALUES ('modifyLoginMsg', 'No teacher selected!')";
	
	    if (mysqli_query($conn, $sql)) {
		    mysqli_close($conn);
	    }
        //$_SESSION['modifyLoginMsg'] = "No teacher selected!";
        header("Location: teacher-login-select.php");
    }
    else{
        $teacherLoginDB = mysqli_connect('localhost', 'root', '', 'teacher');

        if ($teacherLoginDB->connect_error){
            exit('Error connecting to the teacher database in the server!');
        }
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

        $searchTeacherStmt = $teacherLoginDB->prepare("SELECT * FROM login WHERE IDNumber = ?");
        $searchTeacherStmt->bind_param("s", $teacherSelected);
        $searchTeacherStmt->execute();

        $resultSearch = $searchTeacherStmt->get_result();
        if ($resultSearch->num_rows == 0){
            $message = "The teacher is not registered in the database. Please
                        register them through the Register Teacher page.";
        }
        else{
            while($row = $resultSearch->fetch_assoc()){
                $teacherIDNum = $row["IDNumber"];
                $teacherFName = $row["firstName"];
                $teacherLname = $row["lastName"];
                $teacherPassword = $row["password"];
            }
        }
	
	    $conn = new mysqli("localhost", "root", "", "temp");
	    // Check connection
	    if ($conn->connect_error) {
		    die("Connection failed: " . $conn->connect_error);
	    }
	    $sql = "INSERT INTO temptb (varname, val) VALUES ('rederenceIDNum', '$teacherIDNum')";
	
	    if (mysqli_query($conn, $sql)) {
		    mysqli_close($conn);
	    }
        //$_SESSION["referenceIDNum"] = $teacherIDNum;
        $searchTeacherStmt->close();
        mysqli_close($teacherLoginDB);
    }
}
?>

<html lang = "en">
<head>
    <title> Modify Teacher Details</title>
    <link type = "text/css" rel="stylesheet" href ="css/register-teacher-style.css"/>
</head>

<body>
<div class = "modifyLoginCon">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <form method = "POST" action = "teacher-login-update.php">
        <p class = "instructions">
            <center>
                <div class = "form-group">
                    <h1> Input New Teacher Password </h1>
                    <p class = "notification"> Enter the new password below for <?php echo $teacherFName . " ". $teacherLname?>.</p>
                    <input type="password" class = "password" name = "password" placeholder="Enter new password" required/>
                </div>
        <br/>
                <div class = "form-group">
                    <input type="submit" name="uploadBtn" class="btn btn-info" value="Update to New Teacher Password"/>
                </div>
            </center>
        </p>
    </form>
</div>
</body>
</html>
