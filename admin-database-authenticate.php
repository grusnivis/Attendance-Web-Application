<!-- 
    Kathryn Marie P. Sigaya - 220714

    based on these tutorials:
    https://www.tutorialrepublic.com/php-tutorial/php-mysql-login-system.php
    https://www.javatpoint.com/php-mysql-login-system
-->

<?php
    include ('admin-database-config.php');

    $username = $_POST['username'];
    $password = $_POST['password'];

    //MySQLI injection prevention - clean up data retrieved from an HTML form
    $username = stripcslashes($username);
    $password = stripcslashes($password);
    $username = mysqli_real_escape_string($link, $username);
    $password = mysqli_real_escape_string($link, $password);

    //on the 'credentials' table in phpmyadmin, search for the username and password inputted
    $sql = "SELECT *FROM credentials WHERE username = '$username' AND password = '$password'";
    $result = mysqli_query($link, $sql);
    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
    $count = mysqli_num_rows($result);

    //if username and password 
    if ($count == 1){
        header("location: admin-main.php");
    }
    else{
        //definitely think of another solution aside from this
        $login_err = "Invalid administrator username or password.";
        header("location: admin-login.php");
    }

    mysqli_close($link);
?>