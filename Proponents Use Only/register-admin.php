<?php
session_start();
?>

<!-- HTML start -->
<html lang='en'>
<head>
    <title> [FOR PROPONENTS USE ONLY] Register Administrator </title>
    <link type="text/css" rel="stylesheet" href="css/register-admin-style.css"/>
    <!-- this PHP file is responsible for registering teachers!
    stylesheet is from the Uploading Class List style. -->
</head>

<body>

<div class="registerAdminCon">
    <!-- important for the register teacher form! -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <form method="POST" action="register-admin-server.php" enctype="multipart/form-data">
        <table>
            <h1> Register Teacher </h1>
            <p><b><u>FOR PROPONENTS USE ONLY</u></b></p>
            <p class="instructions">
                Fill up all the text fields below for registering <br/> the administrator into the Attendance Monitoring System.</a>
            </p>

            <?php
            $statusMsg = "";
            echo $statusMsg;
            ?>

            <div class="form-group">
                <tr>
                    <td>
                        <p class="teacherInfo"> <b>USERNAME</b></p>
                    </td>
                    <td>
                        <!-- retain type = "text" due to teachers having letters in their ID number -->
                        <input type="text" class="username" placeholder="Username" name="username" required/>
                    </td>
                </tr>
            </div>

            <div class="Password">
                <tr>
                    <td>
                        <p class="teacherInfo"><b>PASSWORD</b></p>
                    </td>
                    <td>
                        <input type="password" class="password" placeholder="Password" name="password" required/>
                    </td>
                </tr>
            </div>

            <div class="form-group">
                <tr>
                    <td colspan="2" align="center">
                        <!-- change button text through the value attribute -->
                        <input type="submit" name="register" class="btn btn-info" style="width:230px" value="Register"/>
                    </td>
                </tr>
            </div>
    </form>
    </table>
    <br/>
    <hr/>

    <!--the double period brings back 1 level of the directory
    https://stackoverflow.com/questions/18862482/php-header-move-up-one-directory
    -->
    <input type="submit" name="change-admin-password" class="btn btn-info" style="color:300px; color: white; background: #dc3545;" value="Change Administrator Password" formnovalidate/>
    <p class = "instructions">Go back to the administrator menu <a href="../admin-main.php">here</a>.</p>
    <!-- THIS PART IS FOR DISPLAYING IF THE PUSHING TO LOGIN TABLE AND CREATING USER DATABASE IS SUCCESSFUL -->
    <?php
    if (isset($_SESSION['registerAdminMsg']) && $_SESSION['registerAdminMsg']) {
        echo '<p class = "notification">' . $_SESSION['registerAdminMsg'] . '</p>';
        unset($_SESSION['registerAdminMsg']);
    }
    ?>
</div>
</body>
</html>