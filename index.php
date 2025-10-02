<?php
session_start();

//Return user to dashbaord if he is logged in
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}else{
     header("Location: login.php");
    exit();
}
?>
