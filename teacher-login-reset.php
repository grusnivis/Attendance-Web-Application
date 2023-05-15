<!--
THIS PAGE RESETS THE TEACHER'S PASSWORD.
-->
<?php
session_start();

if (isset($_POST['reset-password']) && $_POST['reset-password'] == 'Reset Teacher Password') {
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
        exit;
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
                $teacherEmail = $row["email"];
            }

            //creates randomized password
            //https://www.geeksforgeeks.org/php-random_bytes-function/
            //https://paragonie.com/blog/2015/07/how-safely-generate-random-strings-and-integers-in-php
            try {
                $passwordReset = bin2hex(random_bytes('4'));
            } catch (Exception $e) {
                echo "Failed to find randomness for password.";
            }

            //turns the password into a hash for security
            $hashedPassWord = password_hash($passwordReset, PASSWORD_BCRYPT);

            //insert hashed password
            $statementInsert = $teacherLoginDB->prepare("UPDATE login SET password = ? WHERE IDNumber = ?");
            $statementInsert->bind_param("ss", $hashedPassWord, $teacherIDNum);
            $statementInsert->execute();
            $statementInsert->close();

            //send email for teacher's password
            // the necessary email addresses
            // edit the email address here!
            $from = '19102579@usc.edu.ph';
            $to = $teacherEmail;

            //$boundary = md5("random"); // define boundary with a md5 hashed value

            //header
            //$headers = "MIME-Version: 1.0\r\n"; // Defining the MIME version
            $headers = "From:" . $from; // Sender Email
            //$headers .= "Content-Type: text/plain;"; // Defining Content-Type
            //$headers .= "boundary = $boundary\r\n"; //Defining the Boundary

            //plain text
            //$body = "--$boundary\r\n";
            //$body .= "Content-Type: text/plain; charset=ISO-8859-1\r\n";
            //$body .= "Content-Transfer-Encoding: base64\r\n\r\n";
            $body = "Welcome, $teacherFName $teacherLname! 
        
Your password has been reset as per your request.
Your temporary password to access the Attendance Monitoring System is: $passwordReset. 
Please change your password after logging in. 
Thank you!";

            $sentMailResult = mail($to, "Password Reset for Attendance Monitoring System Account", $body, $headers);

            if ($sentMailResult) {
                $conn = new mysqli("localhost", "root", "", "temp");
                // Check connection
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }
                $str = "The selected teacher\'s new password is sent successfully!";
                $sql = "INSERT INTO temptb (varname, val) VALUES ('modifyLoginMsg', '$str')";

                if (mysqli_query($conn, $sql)) {
                    mysqli_close($conn);
                }

                header("Location: teacher-login-select.php");
                exit;
            } else {
                $conn = new mysqli("localhost", "root", "", "temp");
                // Check connection
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }
                $str = "Sending the teacher\'s password failed.";
                $sql = "INSERT INTO temptb (varname, val) VALUES ('modifyLoginMsg', '$str')";

                if (mysqli_query($conn, $sql)) {
                    mysqli_close($conn);
                }
                //$_SESSION["modifyLoginMsg"] = "Updating the teacher's password failed.";
            }
            $searchTeacherStmt->close();
            mysqli_close($teacherLoginDB);
            header("Location: teacher-login-select.php");
            exit;
        }
    }
}

//returns to administrator menu
if (isset($_POST['return-to-admin-main']) && $_POST['return-to-admin-main'] == 'Return to Administrator Menu'){
    ob_end_clean();
    header("location: admin-main.php");
    exit;
}
?>