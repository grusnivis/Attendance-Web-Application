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
                <?php
                    /* 
                    name of csv file,
                    1000 - length of longest line, 
                    "," - optional delimiter parameter. default is comma
                    https://code.tutsplus.com/articles/read-a-csv-to-array-in-php--cms-39471
                    */
                    $row = 1;
                    $open = fopen("7-20_Teacher1_CPE 3202_g2_22629.csv", "r");
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
            </div>
        </form>
    </body>
</html>