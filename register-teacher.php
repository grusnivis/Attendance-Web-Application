<?php
$firstName = '';
$lastName = '';
$IDNum = '';
$password = '';
$email = '';

if (isset ($_POST["submit"])){
    //ucfirst - returns the first character of the string capitalized (https://www.php.net/manual/en/function.ucfirst.php)
    $firstName = ucfirst($_POST["first-name"]);
    $lastName = ucfirst($_POST["last-name"]);
    $IDNum = $_POST["IDNum"];
    $password = $_POST["password"];
    //check for email input validity?
    $email = $_POST["email"];

    //<--- PART 1: insert username and password fields into database. Database: Teacher. Table: login --->
    //(from admin-database-config.php
    define('DB_SERVER', 'localhost'); //host name
    define('DB_USERNAME', 'root'); //host password
    define('DB_PASSWORD', ''); //database password
    define('DB_NAME', 'teacher'); //database name to connect to!!!

    //attempt to connect to MySQL database
    $link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

    // Check connection
    if ($link -> connect_error) {
        die("Connection failed: " . $link->connect_error);
    }

    $databaseInsert = "INSERT INTO login VALUES ('$IDNum','$password','$firstName','$lastName')";

    if(mysqli_query($link, $databaseInsert)){
        //put in proper placement in the webpage
        echo "Data stored in the database successfully.";
    } else{
        echo "Something went wrong in storing the data." . mysqli_error($link);
    }
    mysqli_close($link);

    //<--- PART 2: make the folder of the corresponding teacher that registered, and put them into the teacher list --->
    //folder name should be "/Web Application/firstName lastName/". take note of the slashes
    //change the folder address in the final deployment!
    $teacherFolderName = "C:/Users/Kath/Desktop/Web Application/" . $firstName . " " . $lastName . "/";

    if(file_exists($teacherFolderName)){
        //do nothing
    }
    else{
        //https://www.php.net/manual/en/function.mkdir.php
        mkdir($teacherFolderName, 0777, true);
    }

    //take note of the slashes
    $teacherListFolder = "C:/Users/Kath/Desktop/Web Application/Teacher List";
    $teacherListFolderCSV = $teacherListFolder . "/TeacherList.csv";

    //maybe make some into functions?
    if (file_exists($teacherListFolder)){
        $teacherListPointer = fopen($teacherListFolderCSV, "a");
        $teacherListDetails = array(
                array("", 'first-name' => $firstName, 'last-name' => $lastName)
            );

        foreach ($teacherListDetails as $line){
            fputcsv($teacherListPointer, $line, ',');
        }
    }
    else{
        //make folder
        mkdir($teacherListFolder, 0777, true);

        //each entry in the array corresponds to each cell in the csv file in one row!
        $teacherListContents[0] = array("RFID", "First Name", "Last Name");
        $teacherListContents[1] = array("", 'first-name' => $firstName, 'last-name' => $lastName);

        //'a' mode is for writing only + puts the pointer to the end of the file
        $teacherListPointer = fopen($teacherListFolderCSV, "a");

        foreach ($teacherListContents as $line){
            fputcsv($teacherListPointer, $line, ',');
        }
    }
    fclose($teacherListPointer);
}
?>

<!-- HTML start -->
<html lang = 'en'>
<head>
    <title> Register Teacher </title>
    <link type = "text/css" rel = "stylesheet" href = "css/class-list-upload-style.css"/>
    <!-- this PHP file is responsible for registering teachers!
    stylesheet is from the Uploading Class List style. -->
</head>

<body>
<?php
if (isset($_SESSION['message']) && $_SESSION['message']){
    echo '<p class = "notification">' .$_SESSION['message']. '</p>';
    unset($_SESSION['message']);
}
?>

<div class = "registerTeacherCon">
    <!-- important for the register teacher form! -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <form method = "POST">
        <table>
            <h1> Register Teacher </h1>
            <p class = "instructions">
                Fill up all the fields below for registering.
                Return to the Administrator Menu <a href = "admin-main.php"> here.</a>
            </p>

           <tr>
               <div class = "form-group">
                   <td>
                       <p class = "instructions">  First Name </p>
                   </td>
                   <td>
                       <input type = "text" class = "firstName" placeholder = "First Name" name = "first-name" value = "<?php echo $firstName;?>"/>
                   </td>
               </div>
           </tr>

        <div class = "form-group">
            <tr>
                <td>
                    <p class = "instructions">  Last Name </p>
                </td>
                <td>
                    <input type = "text" class = "lastName" placeholder = "Last Name" name = "last-name" value = "<?php echo $lastName;?>"/>
                </td>
            </tr>
        </div>

        <div class = "form-group">
            <tr>
                <td>
                    <p class = "instructions"> ID Number </p>
                </td>
                <td>
                    <input type = "text" class = "IDNum" placeholder = "ID Number" name = "IDNum" value = "<?php echo $IDNum;?>"/>
                </td>
            </tr>
        </div>

        <div class = "Password">
            <tr>
                <td>
                    <p class = "instructions">  Password </p>
                </td>
                <td>
                    <input type = "text" class = "password" placeholder = "Password" name = "password" value = "<?php echo $password;?>"/>
                </td>
            </tr>
        </div>

        <div class = "form-group">
            <tr>
                <td>
                    <p class = "instructions"> Email </p>
                </td>
                <td>
                    <input type = "text" class = "email" placeholder = "Email" name = "email" value = "<?php echo $email;?>"/>
                </td>
            </tr>
        </div>

        <div class = "form-group">
            <tr>
                <td colspan = "2" align="center">
                    <!-- change button text through the value attribute -->
                    <input type = "submit" name = "submit" class = "btn btn-info" value = "Register"/>
                </td>
            </tr>
        </div>
    </form>
</table>
</div>
</body>
</html>
