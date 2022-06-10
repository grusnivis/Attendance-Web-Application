<?php
error_reporting(E_ALL & ~E_NOTICE);
ini_set("display_errors", 1);
  
require_once 'ims-blti/blti.php';
$lti = new BLTI("secret", false, false);

ob_start();
session_start();
header('Content-Type: text/html; charset=utf-8'); 
?>

<html lang = "en">

    <head>
        <title>Group H - Web Application</title>

        <style>
         body {
            padding-top: 30px;
            padding-bottom: 70px;
            background-color: #ADABAB;
         }
         
        h1{
            text-align: left;
            font-family: "Futura Lt BT", "Century Gothic", monospace;
            padding-left: 100px;
            color: #017572;
            margin-bottom: 100px;
        }

        h2{
            text-align: left;
            font-family: "Freestyle Script", "MV Boli", monospace;
            font-size: 40px;
            padding-left: 100px;
            color: #017572; 
            margin-bottom: 60px;
        }

        h3{
            text-align: center;
            font-family: "Futura Lt BT", "Century Gothic", monospace;
            font-size: 25px;
            color:#777777;
            padding-top: 25px;
            margin-bottom: 80px;
        }

        h5{
            text-align: left;
            font-family: monospace, "Century Gothic";
            padding-left: 100px;
            color: #017572; 
        }

        .box {
            height: 100px;
            width: 1140px;
        }

        .div1 {
            background-color: #ADABAB;
            float: left;
            height: 400%;
            width: 75%;
        }

        .div2 {
            text-align: right;
            background: #f8f8ff;
            height: 400%;
        }

        .form-signin {
            max-width: 1090px;
            margin: 0 auto;
            color: #d9534f;
         }
         
         .form-signin .form-signin-heading,
         .form-signin .checkbox {
            margin-bottom: 10px;
         }
         
         .form-signin .checkbox {
            font-weight: normal;
         }
         
         .form-signin .form-control {
            position: relative;
            height: auto;
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            box-sizing: border-box;
            padding: 10px;
            font-size: 17px;
         }
         
         .form-signin .form-control:focus {
            z-index: 2;
         }
         
         .form-signin input[type="text"] {
            margin-bottom: 15px;
            border-bottom-right-radius: 0;
            border-bottom-left-radius: 0;
            border-color:#017572;
         }
         
         .form-signin input[type="password"] {
            margin-bottom: 70px;
            border-top-left-radius: 0;
            border-top-right-radius: 0;
            border-color:#017572;
         }
      </style>
    </head>

    <body>
    <?php
        if ($lti->valid) {
    ?>
        <?php
            unset($_SESSION["username"]);
            unset($_SESSION["password"]);
        ?> <br/>

        <div class="box">
        <div class="div1">
            <h1>ATTENDANCE SYSTEM</h1>
            <h2>Welcome!</h2>
            <h5>This is the web app of Group H <br/>
                designed for their research entitled <br/>
                "Development of an Attendance Monitoring System <br/> 
                with a Portable RFID-based Logging Device" </h5>
        </div>

        <div class="div2">
            <h3>Login</h3>
            <div class = "container form-signin"> 
                <?php
                    $msg = '';
                    
                    if (isset($_POST['login']) && !empty($_POST['username']) 
                    && !empty($_POST['password'])) {
                        
                    if ($_POST['username'] == 'admin' && 
                        $_POST['password'] == '1234') {
                        $_SESSION['valid'] = true;
                        $_SESSION['timeout'] = time();
                        $_SESSION['username'] = 'admin';
                        
                        $msg = 'Login Successful!';
                    }else {
                        $msg = 'Wrong username or password';
                    }
                    }
                ?>
            </div>

            <div class = "container">      
                <form class = "form-signin" role = "form" 
                    action = "<?php echo htmlspecialchars($_SERVER['PHP_SELF']); 
                    ?>" method = "post">
                    <h4 class = "form-signin-heading"><?php echo $msg; ?></h4>
                    <input type = "text" class = "form-control" 
                        name = "username" placeholder = "username = admin" 
                        required autofocus></br>
                    <input type = "password" class = "form-control"
                        name = "password" placeholder = "password = 1234" required>
                    <button class = "btn btn-lg btn-primary btn-block" type = "submit" 
                        name = "login">LOGIN</button>
                </form>
            </div> 
        </div>
    <?php
        } else {
    ?>
        <h2>This was not a valid LTI launch</h2>
        <p>Error message: <?= $lti->message ?></p>
    <?php
        }
    ?>
    </body>

</html> 