
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
    session_start();
?>

<html>
    <head>
        <title>Attendance Monitoring System [Administrator]</title>
        <link type = "text/css" rel = "stylesheet" href = "css/admin-login-style.css" />
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
            <h1>Attendance Monitoring System <i><u> [Administrator] </u></i></h1>
            <h3> Welcome! </h3>
            <h4> This is the <b> administrator login page </b> for <br/>Group H's thesis entitled
                <i> Development of an <br/>Attendance Monitoring System with a <br/>Portable RFID-based Logging Device.</i>
                <br/>Please input your credentials below.
            </h4>
            <p> <b> <i>Are you a teacher? <a href = "teacher-login.php" > Log in here! </a> </i></b></p>

            <hr>

            <div class = "con-form">
                <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
                <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

                <form class = "form-signin" role = "form" action = "admin-database-authenticate.php" method = "post">
                    <h4 class = "form-signin-heading"><?php echo $msg = ''; ?></h4>
                    <!-- use "placeholder" for the filler text in boxes -->
                    <input type = "text" class = "form-control" name = "username" placeholder = "Username" required autofocus>
                    <input type = "password" class = "form-control" name = "password" placeholder = "Password" required >
                    <br class = "breakspace"/>
                    <input type="submit" name="buttonLogin" class="btn btn-info" value="Login"/>
                </form>
                <br class="breakspaceForNotif"/>
                <?php
                if (isset($_SESSION['adminLoginMsg']) && $_SESSION['adminLoginMsg']) {
                    echo '<p class = "notification">';
                    echo $_SESSION["adminLoginMsg"];
                    echo '</p>';
                    unset ($_SESSION["adminLoginMsg"]);
                }
                ?>
            </div> 
        </div>
    
    <?php
    /*
        } else {
    ?>
        <h2>This was not a valid LTI launch</h2>
        <p>Error message: <?= $lti->message ?></p>
    <?php
        }
    */
    ?>
    </body>
</html> 