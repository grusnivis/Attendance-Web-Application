<?php
session_start();
?>

<!-- HTML start -->
<html lang='en'>
<head>
    <title> Register Teacher </title>
    <link type="text/css" rel="stylesheet" href="css/register-teacher-style.css"/>
    <!-- this PHP file is responsible for registering teachers!
    stylesheet is from the Uploading Class List style. -->
</head>

<body>

<div class="registerTeacherCon">
    <!-- important for the register teacher form! -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <form method="POST" action="register-teacher-server.php" enctype="multipart/form-data">
        <table>
            <h1> Register Teacher </h1>
            <p class="instructions">
                Fill up all the fields below for registering.
                Return to the Administrator Menu <a href="admin-main.php"> here.</a>
            </p>

            <?php
            $statusMsg = "";
            echo $statusMsg;
            ?>

            <tr>
                <div class="form-group">
                    <td>
                        <p class="instructions"> First Name </p>
                    </td>
                    <td>
                        <input type="text" class="firstName" placeholder="First Name" name="first-name"/>
                    </td>
                </div>
            </tr>

            <div class="form-group">
                <tr>
                    <td>
                        <p class="instructions"> Last Name </p>
                    </td>
                    <td>
                        <input type="text" class="lastName" placeholder="Last Name" name="last-name"/>
                    </td>
                </tr>
            </div>

            <div class="form-group">
                <tr>
                    <td>
                        <p class="instructions"> ID Number </p>
                    </td>
                    <td>
                        <!-- retain type = "text" due to teachers having letters in their ID number -->
                        <input type="text" class="IDNum" placeholder="ID Number" name="IDNum"/>
                    </td>
                </tr>
            </div>

            <div class="Password">
                <tr>
                    <td>
                        <p class="instructions"> Password </p>
                    </td>
                    <td>
                        <input type="password" class="password" placeholder="Password" name="password"/>
                    </td>
                </tr>
            </div>

            <div class="form-group">
                <tr>
                    <td>
                        <p class="instructions"> Email </p>
                    </td>
                    <td>
                        <!-- use input type = "email" for automatic email validation -->
                        <input type="email" class="email" placeholder="Email" name="email"/>
                    </td>
                </tr>
            </div>

            <div class="form-group">
                <tr>
                    <td colspan="2" align="center">
                        <!-- change button text through the value attribute -->
                        <input type="submit" name="submit" class="btn btn-info" value="Register"/>
                    </td>
                </tr>
            </div>
    </form>
    </table>
    <!-- THIS PART IS FOR DISPLAYING IF THE PUSHING TO LOGIN TABLE AND CREATING USER DATABASE IS SUCCESSFUL -->
    <?php
    if (isset($_SESSION['registerTeacherMsg']) && $_SESSION['registerTeacherMsg']) {
        echo '<p class = "notification">' . $_SESSION['registerTeacherMsg'] . '</p>';
        unset($_SESSION['registerTeacherMsg']);
    }
    ?>
</div>
</body>
</html>