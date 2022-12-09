<?php
$firstName = '';
$lastName = '';
$IDNum = '';
$password = '';
$email = '';

if (isset ($_POST["submit"])) {
    //ucfirst - returns the first character of the string capitalized (https://www.php.net/manual/en/function.ucfirst.php)
    $firstName = strtoupper($_POST["first-name"]);
    $lastName = strtoupper($_POST["last-name"]);
    $IDNum = $_POST["IDNum"];
    $password = $_POST["password"];
    //check for email input validity?
    $email = $_POST["email"];

    //<--- PART 1: insert username and password fields into database. Database: Teacher. Table: login --->
    //(from admin-database-config.php)

    //attempt to connect to MySQL database
    $link = mysqli_connect("localhost", "root", "", "teacher");

    // Check connection
    if ($link->connect_error) {
        die("Connection failed: " . $link->connect_error);
    }

    $databaseInsert = "INSERT INTO login VALUES ('$IDNum','$password', '$firstName','$lastName', '$email')";

    if (mysqli_query($link, $databaseInsert)) {
        //put in proper placement in the webpage
        echo "Teacher information stored in the database successfully.";
    } else {
        echo "Something went wrong in storing the data to the database." . mysqli_error($link);
    }
    mysqli_close($link);

    //<--- PART 2: make the folder of the corresponding teacher that registered, and put them into the authorized users masterlist (folder + csv file) --->

    //folder name should be "./firstName lastName/". take note of the slashes and the period
    $teacherFolderName = "./" . $firstName . " " . $lastName . "/";

    if (file_exists($teacherFolderName)) {
        //do nothing
    } else {
        //https://www.php.net/manual/en/function.mkdir.php
        mkdir($teacherFolderName, 0777, true);
    }

    //creating the Authorized Users Masterlist folder and csv file
    //take note of the slashes and the period!
    $authorizedUsersFolder = "./Authorized User Masterlist/";
    if (file_exists($authorizedUsersFolder)) {
        //do nothing
    } else {
        //https://www.php.net/manual/en/function.mkdir.php
        mkdir($authorizedUsersFolder, 0777, true);
    }
    $authorizedUsersCSV = $authorizedUsersFolder. "AuthorizedUsers.csv";

    //maybe make some into functions?
    if (!file_exists($authorizedUsersCSV)) {
        //mkdir($authorizedUsersFolder, 0777, true);
        $authorizedUsersPointer = fopen($authorizedUsersCSV, "a");
        $authorizedUsersContents[0] = array("RFID", "ID Number", "Last Name", "First Name");
        $authorizedUsersContents[1] = array("", 'IDNum' => $IDNum,'last-name' => $lastName, 'first-name' => $firstName);

        fwrite($authorizedUsersPointer, implode(",", $authorizedUsersContents[0]) . "\r\n");
        fwrite($authorizedUsersPointer, implode(",", $authorizedUsersContents[1]) . "\r\n");

    } else {
        //each entry in the array corresponds to each cell in the csv file in one row!
        $authorizedUsersContents[0] = array("", 'IDNum' => $IDNum,'last-name' => $lastName, 'first-name' => $firstName);

        //'a' mode is for writing only + puts the pointer to the END of the file
        $authorizedUsersPointer = fopen($authorizedUsersCSV, "a");
        fwrite($authorizedUsersPointer, implode(",", $authorizedUsersContents[0]) . "\r\n");
    }
    fclose($authorizedUsersPointer);


    //<--- PART 3: Creating the registered teacher's database --->
    // Create connection
    $servername = "localhost";
    $username = "root";
    $password = "";

    $conn = new mysqli($servername, $username, $password);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    //create database if not exist
    $DBname = $firstName . " " . $lastName;
    //take note of the small quotations! use the symbol before the 1 key on keyboard
    //solution: https://stackoverflow.com/questions/21032122/how-to-name-a-mysql-database-after-user-input
    $checkDB = "CREATE DATABASE IF NOT EXISTS `$DBname`";
    if($conn->query($checkDB) === TRUE){
        $statusMsg = "Teacher's own database is created successfully!";
        echo $statusMsg;
        //$conn->query("USE $DBname");
        //is there a table creation process here? if so put here. use the "USE" statement
    }
    else{
        echo "Error creating the teacher's database: " . $conn->error;
    }

    mysqli_close($conn);
}
?>

<!-- HTML start -->
<html lang='en'>
<head>
    <title> Register Teacher </title>
    <link type="text/css" rel="stylesheet" href="css/class-list-upload-style.css"/>
    <!-- this PHP file is responsible for registering teachers!
    stylesheet is from the Uploading Class List style. -->
</head>

<body>
<?php
if (isset($_SESSION['message']) && $_SESSION['message']) {
    echo '<p class = "notification">' . $_SESSION['message'] . '</p>';
    unset($_SESSION['message']);
}
?>

<div class="registerTeacherCon">
    <!-- important for the register teacher form! -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <form method="POST">
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
                        <input type="text" class="firstName" placeholder="First Name" name="first-name"
                               value="<?php echo $firstName; ?>"/>
                    </td>
                </div>
            </tr>

            <div class="form-group">
                <tr>
                    <td>
                        <p class="instructions"> Last Name </p>
                    </td>
                    <td>
                        <input type="text" class="lastName" placeholder="Last Name" name="last-name"
                               value="<?php echo $lastName; ?>"/>
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
                        <input type="text" class="IDNum" placeholder="ID Number" name="IDNum"
                               value="<?php echo $IDNum; ?>"/>
                    </td>
                </tr>
            </div>

            <div class="Password">
                <tr>
                    <td>
                        <p class="instructions"> Password </p>
                    </td>
                    <td>
                        <input type="password" class="password" placeholder="Password" name="password"
                               value="<?php echo $password; ?>"/>
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
                        <input type="email" class="email" placeholder="Email" name="email"
                               value="<?php echo $email; ?>"/>
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
</div>
</body>
</html>