<?php
// check if there is a no session ID so I can start a session.
if (!session_id()) {
    session_start();
}

$connection = new mysqli("localhost","root","", "news_system");



//keeping the upload path here so I don't have to rewrite every time
$upload_dir = 'uploads/';


//check if db connection is successful everytime
if($connection->connect_error){
    die("فشل الاتصال بقاعدة البيانات: " . $connection->connect_error);
}

// a function to check if the user is logged in
// used for pages that needed user to login before viewing page
function requireLogin() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
    }
}

?>