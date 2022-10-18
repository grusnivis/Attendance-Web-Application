<?php
    session_start();

    // THIS FILE READS DATA STORED IN THE DATABASE
   $servername = "localhost";
   $username = "root";
   $password = "";
   $dbname = "monitoring";
     
   // connect the database with the server
   $db = new mysqli($servername,$username,$password,$dbname);
     
    // if error occurs 
    if ($db -> connect_errno)
    {
       echo "Failed to connect to MySQL: " . $db -> connect_error;
       exit();
    }
 
    $cg = $_GET["table"];
    $_SESSION['table'] = $cg;

    // INSESRT TABLE NAME HERE
    $result = ($db->query("SELECT * FROM `$cg`"));

    //declare array to store the data of database
    $row = []; 
 
    // fetch all data from db into array if table not empty
    //if ($result->num_rows > 0) 
    //{
    //    $row = $result->fetch_all(MYSQLI_ASSOC);  
    //}   
?>
  
<!DOCTYPE html>
<html>
<style>
    td,th {
        border: 1px solid black;
        padding: 10px;
        margin: 5px;
        text-align: center;
    }
</style>

<h1>
	<center>Attendance Monitoring System</center>
</h1>

<body>
    <thead>
        <tr>
        <?php  
            $show_col = $db->query("SHOW COLUMNS FROM `$cg`");           
            while($row = $show_col->fetch_assoc()){
                $columns[] = $row['Field'];
            }   

            echo "<table style=margin-left:auto;margin-right:auto>";

                echo "<th>";
                echo $columns[0];
                echo "</th>";

                echo "<th>";
                echo "NAME";
                echo "</th>";

                // for the RFID column
                $result = $db->query("SELECT RFID FROM `$cg`"); 
                // concatenates firstname with lastname
                $concat= $db->query("SELECT Concat(FirstName, ' ', LastName)
                                     AS name FROM `$cg`");
                
                // displays RFID AND fullname 
                while ($row = $result->fetch_assoc() AND $row2 = $concat->fetch_assoc()) { 
                    echo "<tr>";

                    foreach($row as $value) {
                        echo "<td>" . $value . "</td>";
                    }

                    $fullname = $row2['name'];
                    $name=urlencode($fullname);
                    echo "<td>" . "<a href=4-monitoring.php/?name=$name> $fullname </a>" . "</td>";                    
                    echo "</tr>";
                }
            echo "</table>";
        ?>
        </tr>
    </thead>
</body>
</html>
  
<?php   
    mysqli_close($db);
?>