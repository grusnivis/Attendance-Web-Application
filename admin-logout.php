<?php
session_start();
session_unset();
session_destroy();
ob_end_clean();
header('location:admin-login.php');
?>