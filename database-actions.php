<?php
ob_start();
session_start();

//THIS PART WILL EXECUTE IF "Export Selected Teacher Database" IS SELECTED
if (isset($_POST['database-export']) && $_POST['database-export'] == 'Export Selected Teacher Database') {
    //the selected teacher found on database-export-drop.php
    $dbTeacherExport = $_POST["dbTeacherSelect"];

    //if else is chosen aside from "no teacher selected"
    if ($dbTeacherExport != '0') {
        //<!-- THIS PART CREATES THE .SQL FILE -->
        //strtolower since database naming convention are lowercase letters
        //$exportTeacherName = $dbTeacherExport;

        // from export-db.php
        set_time_limit(3000);
        $tables = false;
        $backup_name = false;

        $mysqli = new mysqli("localhost","root","",$dbTeacherExport);
        $mysqli->select_db($dbTeacherExport);
        $mysqli->query("SET NAMES 'utf8mb4'");

        $queryTables = $mysqli->query('SHOW TABLES');
        while($row = $queryTables->fetch_row()) {
            $target_tables[] = $row[0];
        }

        if($tables !== false) {
            $target_tables = array_intersect( $target_tables, $tables);
        }

        $content = "SET SQL_MODE = \"NO_AUTO_VALUE_ON_ZERO\";\r\nSET time_zone = \"+00:00\";\r\n\r\n\r\n/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;\r\n/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;\r\n/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;\r\n/*!40101 SET NAMES utf8 */;\r\n--\r\n-- Database: `".$dbTeacherExport."`\r\n--\r\n\r\n\r\n";

        foreach($target_tables as $table){
            if (empty($table)){
                continue;
            }
            $result	= $mysqli->query('SELECT * FROM `'.$table.'`');
            $fields_amount = $result->field_count;
            $rows_num = $mysqli->affected_rows;

            $res = $mysqli->query('SHOW CREATE TABLE `'.$table . '`');
            $TableMLine = $res->fetch_row();
            $content .= "\n\n".$TableMLine[1].";\n\n";

            $TableMLine[1] = str_ireplace('CREATE TABLE `','CREATE TABLE IF NOT EXISTS `',$TableMLine[1]);

            for ($i = 0, $st_counter = 0; $i < $fields_amount;   $i++, $st_counter=0) {
                while($row = $result->fetch_row())	{ //when started (and every after 100 command cycle):
                    if ($st_counter%100 == 0 || $st_counter == 0 )	{
                        $content .= "\nINSERT INTO `".$table."` VALUES";
                    }
                    $content .= "\n(";
                    for($j = 0; $j < $fields_amount; $j++){
                        $row[$j] = str_replace("\n","\\n", addslashes($row[$j]) );
                        if (isset($row[$j])){
                            $content .= '"'.$row[$j].'"' ;
                        }
                        else{
                            $content .= '""';
                        }
                        if ($j<($fields_amount-1)){
                            $content.= ',';
                        }
                    }
                    $content .=")";
                    //every after 100 command cycle [or at last line] ....p.s. but should be inserted 1 cycle earlier
                    if ( (($st_counter+1)%100==0 && $st_counter!=0) || $st_counter+1==$rows_num) {
                        $content .= ";";
                    }
                    else {
                        $content .= ",";
                    }
                    $st_counter=$st_counter+1;
                }
            } $content .= "\n\n\n";
        }
        $content .= "\r\n\r\n/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;\r\n/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;\r\n/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;";
        //filename format
        //$backup_name = $backup_name ? $backup_name : $dbTeacherExport.'___('.date('H-i-s').'_'.date('d-m-Y').').sql';
        $backup_name = $backup_name ? $backup_name : $dbTeacherExport . '_db_exported on ' . date('Y-m-d') . '.sql';
        ob_get_clean();
        header('Content-Type: application/octet-stream');
        header("Content-Transfer-Encoding: Binary");
        header('Content-Length: '. (function_exists('mb_strlen') ? mb_strlen($content, '8bit'): strlen($content)) );
        header("Content-disposition: attachment; filename=\"".$backup_name."\"");
        echo $content;
    }
    else{
        $_SESSION["exportTeacherDBMsg"] = "No Teacher selected!";
    }
    //returns to the database-export-drop.php
    header("location: database-export-drop.php");
}

//<!-- THIS PART WILL EXECUTE IF "Drop All Databases" IS SELECTED -->
if (isset($_POST['database-drop']) && $_POST['database-drop'] == 'Delete All Databases') {
    //connect to the server
    $serverConnect = mysqli_connect('localhost','root','');
    if ($serverConnect->connect_error){
        exit('Error connecting to the server.');
    }
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    //put all existing databases in phpmyadmin in array
    $showDatabaseStmt = "SHOW DATABASES";
    $result = mysqli_query($serverConnect,$showDatabaseStmt);
    $databaseList = array();

    while ($row = $result->fetch_assoc()){
        //echo $databaseList['Database'] . "<br/>";
        //store the result array to the provided array
        $databaseList[] = $row['Database'];
    }

    $i = 0;
    $count = count($databaseList);

    while ($i < $count){
        if ($databaseList[$i] != "admin" &&
            $databaseList[$i] != "teacher" &&
            $databaseList[$i] != "authorized users" &&
            $databaseList[$i] != "information_schema" &&
            $databaseList[$i] != "performance_schema" &&
            $databaseList[$i] != "phpmyadmin" &&
            $databaseList[$i] != "mysql"){
                //skip those databases. no action required
                $dbListVar = $databaseList[$i];
                $dropDatabaseStmt = "DROP DATABASE `$dbListVar`";
                $result = mysqli_query($serverConnect, $dropDatabaseStmt);
        }
        $i++;
    }

    mysqli_close($serverConnect);

    //returns to the database-export-drop.php
    $_SESSION["dropTeacherDBMsg"] = "Teacher databases deleted successfully!";
    header("location: database-export-drop.php");
}

//THIS PART WILL EXECUTE IF "RETURN TO ADMINISTRATOR MENU" IS SELECTED
if (isset($_POST['return-to-admin-main']) && $_POST['return-to-admin-main'] == 'Return to Administrator Menu') {
    header("location: admin-main.php");
}
ob_end_clean();
?>
