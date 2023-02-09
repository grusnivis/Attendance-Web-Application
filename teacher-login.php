<?php
/*
    error_reporting(E_ALL & ~E_NOTICE);
    ini_set("display_errors", 1);
  
    require_once 'ims-blti/blti.php';
    $lti = new BLTI("secret", false, false);

    ob_start();
    session_start();
    header('Content-Type: text/html; charset=utf-8'); 
    */
?>

<?php
//You only assign a session variable once, so page 1 is correct,
//then it is available for the whole session, uses session_start()
//at the top of each page.
// https://stackoverflow.com/questions/9571125/cant-pass-php-session-variables-to-multiple-pages
//session_start();
?>

<html>
<head>
    <title>Group H - Attendance Monitoring System</title>
    <link type="text/css" rel="stylesheet" href="css/teacher-login-style.css"/>
</head>
<body>
<?php
/*
    if ($lti -> valid) {
?>
<?php
    unset($_SESSION["username"]);
    unset($_SESSION["password"]);
*/
?>
<br/>
<div class="con-main">
    <h1>Attendance Monitoring System</h1>
    <h3> Welcome! </h3>
    <h4> Group H 2022-2023 designed this web application
        <br/>for the thesis entitled
        <i> Development of an Attendance Monitoring System with a Portable RFID-based Logging Device. </i>
        <p> <b> <i> Are you an administrator? <a href="admin-login.php"> Log in here! </a> </i></b></p>
    </h4>
    <hr>

    <div class="con-form">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

        <form class="form-signin" role="form" action="database-authenticate.php" method="post">
            <h4 class="form-signin-heading"><?php echo $msg = ''; ?></h4>
            <!-- use "placeholder" for the filler text in boxes -->
            <input type="text" class="form-control" name="IDNum" placeholder="ID Number" required autofocus>
            <input type="password" class="form-control" name="password" placeholder="Password" required>
            <br class="breakspace"/>
            <input type="submit" name="buttonLogin" class="btn btn-info" value="Login"/>
            <br class="breakspaceForNotif"/>
            <?php
            if (isset($_SESSION['teacherLoginMsg']) && $_SESSION['teacherLoginMsg']) {
                echo '<p class = "notification">';
                echo $_SESSION["teacherLoginMsg"];
                echo '</p>';
                unset ($_SESSION["teacherLoginMsg"]);
            }
            ?>
        </form>
    </div>
</div>

</body>
</html> 