<!-- 
    Kathryn Marie P. Sigaya - 220707

    based on these tutorials:
    https://www.tutorialrepublic.com/php-tutorial/php-mysql-login-system.php
    https://www.javatpoint.com/php-mysql-login-system
-->

<?php
    include ('database-config.php');
    session_start();

    $IDNum = $_POST['IDNum'];
    $password = $_POST['password'];

    //MySQLI injection prevention - clean up data retrieved from an HTML form
    $username = stripcslashes($IDNum);
    $password = stripcslashes($password);
    $username = mysqli_real_escape_string($link, $username);
    $password = mysqli_real_escape_string($link, $password);

    //on the 'login' table in phpmyadmin, search for the username and password inputted
    $sql = "SELECT *FROM login WHERE IDNumber = '$IDNum' AND password = '$password'";
    $result = mysqli_query($link, $sql);
    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
    $count = mysqli_num_rows($result);

    //if username and password 
    if ($count == 1){
        //see solution here for sessions: https://www.simplilearn.com/tutorials/php-tutorial/php-login-form
        $_SESSION['currentUser'] = $IDNum;
        header("location: teacher-main.php");
    }
    else{
        //definitely think of another solution aside from this
        $login_err = "Invalid username or password.";
        header("location: teacher-login.php");
    }

    mysqli_close($link);
?>