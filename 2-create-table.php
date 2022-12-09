<?php
    session_start();

    //THIS PART JUST CALLS THE PHP FILE FOR SCANNING OF ATTENDANCE LOG FOLDER 
    include ('1-scan-directory.php');
?>

<?php
    // THIS CREATES A TABLE FOR EVERY FILE FOUND IN FOLDER
    foreach ($files_arr as $file_name) {

        // splits the filnename
        $split = explode('.',$file_name);
        $temp = explode('_',$split[0]);


        // TABLE NAME = Course code - Group number (Schedule)
        // &#10; is for new line
        $date = $temp[0];
        $teacher = $temp[1];
        $cg = $temp[3].'-'.$temp[2].'('.$temp[4].')';
        //$_SESSION['table'] = $cg;

        // checks if class is a teamteach
        if(strpos($temp[3], 'TM') !== false){
            
            // connect to the teamteach database
            $tm_db = new mysqli("localhost", "root", "", "teamteach");
            if ($tm_db->connect_error) {
                die("Connection failed: " . $tm_db->connect_error);
            }
            else{
                $course = explode('-',$temp[2]);
                $course_code = "$course[1]-$temp[2]";

                // stores the teamteach names based on the course
                $get=mysqli_query($tm_db,"select Teacher,Partner from `teamteach` where 
                        Course='$course_code'");

                while($row = $get->fetch_assoc()) {
                    foreach($row as $db_name){
                        connect_to_db($date, $dir, $file_name, $cg, $db_name);
                    }
                }
            }
        }
        else{
            connect_to_db($date, $dir, $file_name, $cg, $teacher);
        }
    }
?>        


<?php
    function connect_to_db($date, $dir, $file_name, $cg, $teacher)
    {            
        $_SESSION["Teacher name"] = $teacher;        
        //$_SESSION["Class Selected"] = $temp[3].'  '.$temp[2];
        //$_SESSION["table"] = $cg;

        // path = directory + filename
        $path = $dir.$file_name;

        include '0-connect.php';


        // sql to create table if it doesn't exist yet
        $create_table = "CREATE TABLE IF NOT EXISTS `{$cg}` (
            RFID VARCHAR(255) NOT NULL,
            ID VARCHAR(255),
            Surname VARCHAR(255) NOT NULL,
            Name VARCHAR(255) NOT NULL,
            Date VARCHAR(255) NOT NULL,
            Status VARCHAR(255) NOT NULL,
            Time VARCHAR(255) NOT NULL,
            PRIMARY KEY (`RFID`,`ID`,`Date`))";
            
        // adds table to database
        mysqli_query($db, $create_table);
                    
        // THIS PART STORES THE NAME AND STATUS OF THE STUDENT INTO THE DATABASE  
        if(($file = fopen($path, "r")) !== FALSE) {

            // skips the first line
            fgetcsv($file);
                
            // reads thru the csv file
            while(($ar = fgetcsv($file)) !== FALSE) {
                // SQL query to store data in database
                // table name is teacher name-schedule-g#

                // checks if row already exists in the table, adds if yes, does nothing if no                        
                $check=mysqli_query($db,"select * from `$cg` where 
                        RFID='$ar[0]' and ID='$ar[1]' and Surname='$ar[2]' and Name='$ar[3]'
                        and Date='$date'");
                $checkrows=mysqli_num_rows($check);

                if($checkrows>0) { 
                    while($row = $check->fetch_assoc()) {
                        if (empty($row['Time'])){
                            $db->query("UPDATE `$cg` 
                                        SET Status='$ar[4]', Time='$ar[5]'
                                        WHERE ID='$ar[1]' AND Surname='$ar[2]' AND Name='$ar[3]' 
                                        AND Date='$date'");
                        }
                    }
                }

                else{
                    // adds row, no entry found
                    $insert = "INSERT INTO `$cg`(RFID,ID,Surname,Name,Date,Status,Time) 
                               VALUES('$ar[0]','$ar[1]','$ar[2]','$ar[3]','$date','$ar[4]','$ar[5]')";
                    $result = mysqli_query($db, $insert) or die('Error querying database.');
                    }
            }
        }

        // closes and deletes the file
        fclose($file);
        //unlink($path);
        mysqli_close($db);
?>

<?php
    }               
?>

<!DOCTYPE html>
<html>   
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;1,100;1,300;1,400;1,700&display=swap');

        html * {
        font-size: 16px;
        line-height: 1.625;
        font-family: Lato, sans-serif;
        }

        btn{
            background-color: #dc3545;
            color: white;
            text-align: center;
            display:inline-block;
            font-weight:400;
            vertical-align:middle;
            cursor:pointer;
            border:1px solid transparent;
            padding:.375rem .75rem;
            font-size:1rem;
            line-height:1.5;
            border-radius:.25rem;
        }

        .topnav {
            background-color: #4f6d7a;
            overflow: hidden;
        }
    
        /* Style the links inside the navigation bar */
        .topnav a {
            float: left;
            color: #f2f2f2;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
            font-size: 17px;
            text-transform: capitalize;
        }
    
        /* Change the color of links on hover */
        .topnav a:hover {
            text-decoration: none;
            background-color:darkgray;
            color: black;
        }
    
        /* Add a color to the active/current link */
        .topnav a.active {
            background-color: #dd6e42;
            color: white;
        }

    </style>

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

        <link rel="stylesheet" 
              href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
            <title>
                Class Selection
            </title>
    </head>

<body style="text-align:center;background-color: #eaeaea">
    <?php
        // THIS IS WHERE WE CONNECT TO THE DATABASE OF THE TEACHER WHO SIGNED IN
        // the session below is to pass the name to the other php file
        $teacher_name = "christopher james m labrador";
        $_SESSION["Teacher name"] = $teacher_name;
        include '0-connect.php';

        $show_tables = $db->query("SHOW TABLES");
    ?>
    <div >
        <nav class="topnav">
            <a style="color:white;background-color: #4f6d7a;text-decoration:none">
                <?php echo $teacher_name?>
            </a>

            <a style="float:right;color:white"
               href="#sign-out"> Sign Out
            </a>

        </nav>

        <h1 style= "padding-top: 50px; padding-bottom:10px; color:#dd6e42;
                    font-size: 28px; font-weight: 500;">
            PLEASE SELECT YOUR CLASS       
        </h1>
            
        <?php
            // displays the tables as buttons
            while($table_name = $show_tables->fetch_assoc()){
                foreach($table_name as $table){
        ?>

        <div style="display:inline-block; margin-block-start: 30pt;
                    margin: 40px 30px;">
            <form method="GET" action="http://localhost/attendance%20monitoring/3-display-selection.php">
                <input style="text-transform: uppercase; background-color: #dc3545; color: white;
                              text-align: center; display:inline-block; font-weight:400;
                              vertical-align:middle; cursor:pointer; border:1px solid transparent;
                              padding:.375rem .75rem; font-size:1rem; line-height:1.5; border-radius:.25rem;" 
                       class="btn" type="submit" 
                       name="table" value="<?php echo $table;?>"
                />
            </form>
        </div>

        <?php
                }
            }
        ?>
    </div>
            

    </body>
</html>
