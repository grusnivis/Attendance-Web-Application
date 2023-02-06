
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
    //session_start();
?>

<html>
    <head>
        <title>Group H - Web Application</title>
        <link type = "text/css" rel = "stylesheet" href = "css/teacher-login-style.css" />
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
            <h1>Attendance System <i><u> [Administrator] </u></i></h1>
            <h3> Welcome! </h3>
            <h4> Welcome to the administrator login menu. Please </br>
            Input your credentials below.
            </h4>
            </h4>

            <hr>
            <!-- commented this out. not needed anymore i think
            <div class = "container form-signin"> 
                <?php
                /*
                    $msg = '';
                    
                    if (isset($_POST['login']) && !empty($_POST['username']) && !empty($_POST['password'])) {                
                        if ($_POST['username'] == 'admin' &&  $_POST['password'] == '1234') {
                        $_SESSION['valid'] = true;
                        $_SESSION['timeout'] = time();
                        $_SESSION['username'] = 'admin';
                        
                        $msg = 'Login Successful!';

                        header("location:teacher-menu.php");
                    }
                    
                    else {
                        $msg = 'Wrong username or password!';
                    }
                    }
                */
                ?>
            </div>
            -->
            
            <div class = "con-form">  
                <form class = "form-signin" role = "form" action = "admin-database-authenticate.php" method = "post">
                    <h4 class = "form-signin-heading"><?php echo $msg = ''; ?></h4>
                    <!-- use "placeholder" for the filler text in boxes -->
                    <input type = "text" class = "form-control" name = "username" placeholder = "username" required autofocus>
                    <input type = "password" class = "form-control" name = "password" placeholder = "password" required >
                    <br class = "breakspace"/>
                    <button class = "buttonLogin" type = "submit" name = "login">Login</button>
                </form>
                <p> Are you a teacher? <b> <a href = "teacher-login.php" > Log in here! </a> </b></p>
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