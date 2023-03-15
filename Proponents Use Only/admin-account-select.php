<!-- HTML START -->
<html lang='en'>
<head>
    <title> [FOR PROPONENTS USE ONLY] Change Administrator Password </title>
    <link type="text/css" rel="stylesheet" href="css/register-admin-style.css"/>
</head>

<body>
<div class="selectAdminCon">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"></script>

    <form method="POST" action="admin-account-modify.php">
        <h1>Change Administrator Password</h1>
        <p><b><u>FOR PROPONENTS USE ONLY</u></b></p>
        <p class="instructions">
            Please select the registered administrator that you want to modify the password from in
            the dropdown box below:
            <br/>
            <br/>
            <!-- THIS PART CONNECTS TO THE ADMINISTRATOR DATABASE - CREDENTIALS TABLE AND OUTPUTS IT TO THE DROPDOWN BOX -->
            <?php
            //https://stackoverflow.com/questions/5189662/populate-a-drop-down-box-from-a-mysql-table-in-php
            $credentialsTableCon = new mysqli("localhost", "root", "", 'admin');
            // Check connection
            if ($credentialsTableCon->connect_error) {
                die("Connection failed: " . $credentialsTableCon->connect_error);
            }

            $sqlQuery = "SELECT username FROM credentials ORDER BY Username ASC";
            $result = mysqli_query($credentialsTableCon, $sqlQuery);

            echo "<select name = 'adminSelect' class='dropup center-block' style='margin-left: 0%;padding: 5px;font-size:17px' required>";
            echo "<option disabled value = '0'>Select an Administrator</option>";
            while ($row = mysqli_fetch_array($result)) {
                echo "<option value = '" . $row['username'] . "'>" . $row['username'] . "</option>";
            }
            echo "</select>";
            mysqli_close($credentialsTableCon);
            ?>
        </p>
        <div class="form-group">
            <!-- change button text through the value attribute -->
            <center><input type="submit" name="admin-selected" class="btn btn-info"
                           value="Change Administrator Password"/></center>
            <hr/>

            <input type="submit" name="return-to-register-admin" class="btn btn-info"
                   style="color:300px; color: white; background: #dc3545;" value="Return to Register Administrator"
                   formnovalidate/>

        </div>
    </form>
</div>
<?php
if (isset($_SESSION["changeAccMsg"]) && $_SESSION["changeAccMsg"]) {
    echo '<p class = "notification">' . $_SESSION["changeAccMsg"] . '</p>';
    unset($_SESSION["changeAccMsg"]);
}
?>
</body>
</html>

