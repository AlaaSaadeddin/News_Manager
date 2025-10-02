<?php
// Include the db config
include_once 'config/database.php';

requireLogin();

$news_id = intval($_GET['id'] ?? 0);
if ($news_id > 0) {
    // Direct SQL query to update the status
    $update_query = "UPDATE news SET status = 'deleted' WHERE id = $news_id AND status = 'active'";

    if ($connection->query($update_query)) {
        $_SESSION['message'] = 'تم حذف الخبر بنجاح';
        $_SESSION['message_type'] = 'success';
    } else {
        $_SESSION['message'] = 'حدث خطأ أثناء حذف الخبر';
        $_SESSION['message_type'] = 'error';
    }
}

// Redirect back to the news list
header("Location: deleted_news.php");
exit();
?>