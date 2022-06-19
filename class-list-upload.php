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
            <div class = "upload-wrapper">
                <span class = "file-name"> Upload .csv file here! </span>
                <label for = "file-upload"> Browse<input type = "file" id = "file-upload" name = "uploadedFile"> </label>
            </div>

            <input type = "submit" name = "uploadBtn" value = "Upload" />
        </form>
    </body>
</html>


