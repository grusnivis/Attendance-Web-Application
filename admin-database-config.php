<!--
    Kathryn Marie P. Sigaya, 2207014
-->
<?php
    /* similar to this: https://www.javatpoint.com/php-mysql-login-system */
    //database credentials, running MySQL with default setting (user 'root' with no password)
    define('DB_SERVER', 'localhost'); //host name
    define('DB_USERNAME', 'root'); //host password
    define('DB_PASSWORD', ''); //database password
    define('DB_NAME', 'admin'); //database name to connect to!!!

    //attempt to connect to MySQL database
    $link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

    //check the connection to the database
    if($link == false){
        //die() kinda functions like an exit() function
        die("Error connecting to the server." . mysqli_connect_error());
    }
?>
