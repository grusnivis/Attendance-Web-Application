<!-- how did this work -->
<?php
    session_start();
    session_destroy();
    header('location:teacher-login.php');
?>