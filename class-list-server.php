<!-- reference page: https://code.tutsplus.com/tutorials/how-to-upload-a-file-in-php-with-example--cms-31763 -->

<?php
session_start();

$message = ''; 
if (isset($_POST['uploadBtn']) && $_POST['uploadBtn'] == 'Upload') {
    if (isset($_FILES['uploadedFile']) && $_FILES['uploadedFile']['error'] === UPLOAD_ERR_OK) {
        // get details of the uploaded file
        $fileTmpPath = $_FILES['uploadedFile']['tmp_name'];
        $fileName = $_FILES['uploadedFile']['name'];
        $fileSize = $_FILES['uploadedFile']['size'];
        $fileType = $_FILES['uploadedFile']['type'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        // sanitize file-name
        //$newFileName = md5(time() . $fileName) . '.' . $fileExtension;
        //$newFileName = $fileName . '.' . $fileExtension;
        $newFileName = $fileName;

        // check if file has one of the following extensions
        $allowedfileExtensions = array('csv'); //array('txt', 'xls', 'csv');


        //move the created file to the currently logged in user's designated folder
        /* similar to this: https://www.javatpoint.com/php-mysql-login-system */
        //database credentials, running MySQL with default setting (user 'root' with no password)
        define('DB_SERVER', 'localhost'); //host name
        define('DB_USERNAME', 'root'); //host password
        define('DB_PASSWORD', ''); //database password
        define('DB_NAME', 'teacher'); //database name to connect to (teacher)

        //attempt to connect to MySQL database
        $databaseLink = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

        //check the connection to the database
        if($databaseLink == false){
            //die() kinda functions like an exit() function
            die("Error connecting to the server." . mysqli_connect_error());
        }

        //on the 'teacher' database, 'login' table in phpmyadmin, search for the id number in the session array
        //mysql and sessions (use curly braces) https://stackoverflow.com/questions/5746614/session-variable-in-mysql-query
        $sql = "SELECT *FROM login WHERE IDNumber = {$_SESSION['currentUser']}";
        //$result = mysqli_query($databaseLink, $sql);
        //$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        //$count = mysqli_num_rows($result);

        if ($result = $databaseLink->query($sql)){
            //test later: https://www.tutorialspoint.com/fetch-a-specific-column-value-name-in-mysql
            //see solution here for sessions: https://www.simplilearn.com/tutorials/php-tutorial/php-login-form
            while ($row = $result->fetch_assoc()) {
                //set the $row[""] to the column you want to use
                $firstName = $row["firstName"];
                $lastName = $row["lastName"];
            }
        }

        mysqli_close($databaseLink);


        if (in_array($fileExtension, $allowedfileExtensions)) {
            // directory in which the uploaded file will be moved
            $uploadFileDir = './'. $firstName . " " . $lastName . '/';
            //concatenate file directory to file name. i.e. ./class-list-directory/filename.csv
            $dest_path_temp = $uploadFileDir . $newFileName;

            //move_uploaded_file(string $from, string $to): bool
            if (move_uploaded_file($fileTmpPath, $dest_path_temp)) {
                //answer is a combo of both links!
                //https://stackoverflow.com/questions/35740176/read-specific-column-in-csv-to-array
                //https://webdiretto.it/to-extract-single-column-values-from-csv-file-php/
                //set the row
                $row = 6;
                //counter
                $i = 1;
                $arrayCount = 0;
                $names = array();
                $idNumbers = array();

                //row first, then column
                if (($handle = fopen($dest_path_temp, "r")) !== FALSE) {
                    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                        if ($i >= $row) {
                            //should be like that. ex. if get 2nd column then
                            //$column = 1; $column < 2
                            for ($column = 5; $column < 6; $column++) {
                                $names[$arrayCount] = explode(",", $data[$column]);
                                $names[$arrayCount][1] = trim($names[$arrayCount][1]);
                                $arrayCount++;
                            }
                        }
                        $i++;
                    }
                    fclose($handle);

                    //id number
                    $row = 6;
                    $arrayCount = 0;
                    //counter
                    $i = 1;
                    if (($handle = fopen("test.csv", "r")) !== FALSE) {
                        while (($data2 = fgetcsv($handle, 1000, ",")) !== FALSE) {
                            if ($i >= $row) {
                                //should be like that. ex. if get 2nd column then
                                //$column = 1; $column < 2
                                for ($column = 1; $column < 2; $column++) {
                                    $idNumbers[$arrayCount] = $data2[$column];
                                    $arrayCount++;
                                }
                            }
                            $i++;
                        }
                        fclose($handle);
                    }

                    $counttest = 0;
                    foreach ($names as &$line) { //& reference: https://stackoverflow.com/questions/25198792/array-unshift-in-multidimensional-array-insert-at-first-element-in-all-arrays
                        array_unshift($line, $idNumbers[$counttest]);
                        array_unshift($line, "");
                        $counttest++;
                    }

                    //put header
                    $header_csv = array("RFID", "ID Number", "Lastname", "Firstname");
                    array_unshift($names, $header_csv);


                    $handle = fopen($dest_path_temp, "w");
                    foreach ($names as $line) {
                        fputcsv($handle, $line, ',');
                    }
                    fclose($handle);


                    /**
                     * //solution: https://stackoverflow.com/questions/1269562/how-to-create-an-array-from-a-csv-file-using-php-and-the-fgetcsv-function
                     * //open the file (read mode) TAKE NOTE OF THE FILENAME
                     * $file = fopen($dest_path_temp, "r");
                     * //prepare the array to be put through fgetcsv
                     * $csv = array();
                     *
                     * //put the csv file contents to the prepared array
                     * while (($line = fgetcsv($file)) !== FALSE) {
                     * $csv[] = $line;
                     * }
                     * //close the file for security
                     * fclose($file);
                     *
                     * //https://www.php.net/manual/en/function.array-unshift.php
                     * //array_unshift puts the to be placed element (value) to the beginning of the array
                     * array_unshift($csv[0], "RFID");
                     *
                     * //loop through the prepared array starting from the second row to put the blank cells
                     * //in the first column. note that i = 0 means the first row in the csv file!
                     * // https://stackoverflow.com/questions/4414623/what-is-the-best-way-to-loop-through-this-array-in-php
                     * for ($i = 1; $i < count($csv); $i++){
                     * array_unshift($csv[$i],"");
                     * }
                     *
                     * //open the file again TAKE NOTE OF THE FILENAME
                     * $fileWrite = fopen ($dest_path_temp, "w+");
                     *
                     * //write the new edited array to the csv file
                     * foreach ($csv as $line){
                     * fputcsv($fileWrite, $line,',');
                     * }
                     *
                     * //close the file for security
                     * fclose($fileWrite);
                     **/
                    $message = 'File is successfully uploaded.';
                } else {
                    $message = 'There was some error moving the file to upload directory. Please make sure the upload directory is writable by web server.';
                }
            } else {
                $message = 'Upload failed. Allowed file types: ' . implode(',', $allowedfileExtensions);
            }
        } else {
            $message = 'There is some error in the file upload. Please check the following error.<br>';
            $message .= 'Error:' . $_FILES['uploadedFile']['error'];
        }
    }
    $_SESSION['message'] = $message;

    header("Location: class-list-upload.php");
}
?>