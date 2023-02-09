<!-- 
    Kathryn Marie P. Sigaya - 220707

    based on these tutorials:
    https://www.tutorialrepublic.com/php-tutorial/php-mysql-login-system.php
    https://www.javatpoint.com/php-mysql-login-system
-->

<?php
    session_start();
    include ('database-config.php');

    $IDNum = $_POST['IDNum'];
    $password = $_POST['password'];

    //MySQLI injection prevention - clean up data retrieved from an HTML form
    $username = stripcslashes($IDNum);
    $password = stripcslashes($password);

    //attempt to connect to MySQL database
    $link = mysqli_connect("localhost", "root", "", "teacher");
    $username = mysqli_real_escape_string($link, $username); //$link can be located at database-config.php
    $password = mysqli_real_escape_string($link, $password);

    //on the 'login' table in phpmyadmin, search for the username and password inputted
    $sql = "SELECT *FROM login WHERE IDNumber = '$IDNum' AND password = '$password'";
    $result = mysqli_query($link, $sql);
    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
    $countLogin = mysqli_num_rows($result);

    //if username and password 
    if ($countLogin == 1){
        //see solution here for sessions: https://www.simplilearn.com/tutorials/php-tutorial/php-login-form
        //$_SESSION['currentUser'] = $IDNum;
        $_SESSION['currentUser'] = $IDNum;
        header("location: 2-create-table.php");
    }
    else{
        $_SESSION["teacherLoginMsg"] = "Invalid username or password!";
        header("location: teacher-login.php");
    }

    mysqli_close($link);
?>