<?php

include_once 'config/database.php';

requireLogin();

$news_id = intval($_GET['id'] ?? 0);

if ($news_id > 0) {
    
    // Direct SQL query to update the status
    $delete_query = "DELETE FROM categories WHERE id = $news_id";

    if ($connection->query($delete_query)) {
        $_SESSION['message'] = 'تم حذف الفئة بنجاح';
        $_SESSION['message_type'] = 'success';
    } else {
        $_SESSION['message'] = '!حدث خطأ أثناء حذف الفئة';
        $_SESSION['message_type'] = 'error';
    }
}

// Redirect back to categories list
header("Location: view_categories.php");
exit();
?>