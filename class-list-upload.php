<html>
    <head> 
        <title> Upload Class Lists </title>
        <link type = "text/css" rel = "stylesheet" href = "css/class-list-upload-style.css"/>
        <!-- this PHP file is responsible for displaying the file upload form!
             the file for uploading the lists to the server is the class-list-server.php -->
    </head>
    <body>
        <?php
            if (isset($_SESSION['message']) && $_SESSION['message']){
                echo '<p class = "notification">' .$_SESSION['message']. '</p>';
                unset($_SESSION['message']);
            }
        ?>
        <form method = "POST" action = "class-list-server.php" enctype = "multipart/form-data">
            <!-- 
            When you use the multipart/form-data value for the enctype attribute, 
            it allows you to upload files using the POST method. 
            Also, it makes sure that the characters are not encoded 
            when the form is submitted.
            -->

            <!-- <div class = "upload-wrapper"> -->
            <div class = "main-con">
                <h1> Class List Uploading </h1>
                <p class = "instructions"> Upload your .csv file containing the class list here. </p>
                <div class = "upload-con" >
                    <span class = "file-name"> Upload .csv file here! </span>
                    <label for = "file-upload"> Browse<input type = "file" id = "file-upload" name = "uploadedFile"> </label>
                </div>
                <input type = "submit" name = "uploadBtn" value = "Upload" />
            </div>
            <div class = "read-file-con">
                <h1> Test Output </h1>
                <p> Before writing .csv file</p>
                <?php
                    /* 
                    name of csv file,
                    1000 - length of longest line, 
                    "," - optional delimiter parameter. default is comma
                    https://code.tutsplus.com/articles/read-a-csv-to-array-in-php--cms-39471
                    */
                    $row = 1;
                    $open = fopen("CPE 3202_g2 S 1-430PM.csv", "r");
                    if($open !== FALSE){
                        echo "<table>\n";
                        while(($data = fgetcsv($open, 1000, ',')) !== FALSE){
                            echo "<tr>";
                            for ($i = 0; $i < count($data); $i++){
                                echo "<td><p class = 'instructions'>".$data[$i]." "."</p></td>";
                            }
                            echo "</tr>\n";
                        }
                        echo "</table>\n";
                    }
                    fclose($open);
                ?>
                <br/> <br/>
                <?php
                //WRITING CSV FILES
                $ToBeWritten =[
                    ['START', '9:00'],
                    ['END', '10:00'],
                    ['RFID', 'NAME'],
                    ['1111', 'student1'],
                    ['2222', 'student2'],
                    ['3333', 'student3'],
                    ['4444', 'student4'],
                    ['5555', 'student5']
                ];
                //prepare file for writing
                //open .csv file for writing
                $openFile = fopen("CPE 3202_g2 S 1-430PM.csv", 'w');

                //error when file cannot be opened
                if ($openFile === false){
                    die('There was an error opening the file ' . $openFile);
                }

                //write each ROW at a time to the target file
                foreach ($ToBeWritten as $row){
                    fputcsv($openFile, $row);
                }

                //then you close the .csv file
                fclose($openFile)
                ?>

                <?php
                //FOR TESTING PURPOSES ONLY
                //after writing to .csv file
                echo "<p> After writing to .csv file </p>";

                $row = 1;
                $openTest = fopen("CPE 3202_g2 S 1-430PM.csv", "r");
                if($openTest !== FALSE){
                    echo "<table>\n";
                    while(($dataTest = fgetcsv($openTest, 1000, ',')) !== FALSE){
                        echo "<tr>";
                        for ($i = 0; $i < count($dataTest); $i++){
                            echo "<td><p class = 'instructions'>".$dataTest[$i]." "."</p></td>";
                        }
                        echo "</tr>\n";
                    }
                    echo "</table>\n";
                }
                fclose($openTest);
                /*
                 * Do not edit. Tutorials for writing to csv files
                 * 1) https://stackoverflow.com/questions/17149935/how-to-edit-a-particular-line-of-the-csv-file-using-php
                 * 2) https://stackoverflow.com/questions/35440228/php-update-specific-row-in-csv-file
                 * 3) https://stackoverflow.com/questions/39950770/csv-change-one-field-column
                 */
                ?>
            </div>
        </form>
    </body>
</html>