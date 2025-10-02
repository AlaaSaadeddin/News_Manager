<?php
// debug_db.php - أداة فحص قاعدة البيانات
include_once 'config/database.php';

echo "<h3>فحص قاعدة البيانات</h3>";

$database = new Database();
$db = $database->getConnection();

if ($db === null) {
    echo "<div style='color: red;'>خطأ: لا يمكن الاتصال بقاعدة البيانات</div>";
    exit;
}

try {
    // 1. فحص الجداول الموجودة
    echo "<h4>1. الجداول الموجودة:</h4>";
    $tables = $db->query("SELECT name FROM sqlite_master WHERE type='table'")->fetchAll();
    foreach ($tables as $table) {
        echo "- " . $table['name'] . "<br>";
    }

    // 2. فحص جدول المستخدمين
    echo "<h4>2. جدول المستخدمين:</h4>";
    $users = $db->query("SELECT * FROM users")->fetchAll();
    if (empty($users)) {
        echo "لا يوجد مستخدمين<br>";
    } else {
        echo "<table border='1'>";
        echo "<tr><th>ID</th><th>Name</th><th>Email</th><th>Created</th></tr>";
        foreach ($users as $user) {
            echo "<tr>";
            echo "<td>" . $user['id'] . "</td>";
            echo "<td>" . htmlspecialchars($user['name']) . "</td>";
            echo "<td>" . htmlspecialchars($user['email']) . "</td>";
            echo "<td>" . $user['created_at'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }

    // 3. فحص جدول الفئات
    echo "<h4>3. جدول الفئات:</h4>";
    $categories = $db->query("SELECT * FROM categories")->fetchAll();
    if (empty($categories)) {
        echo "لا توجد فئات<br>";
    } else {
        foreach ($categories as $cat) {
            echo "- " . htmlspecialchars($cat['name']) . "<br>";
        }
    }

    // 4. إضافة خيار لحذف جميع المستخدمين (للاختبار)
    if (isset($_GET['clear_users'])) {
        $db->exec("DELETE FROM users");
        echo "<div style='color: green;'>تم حذف جميع المستخدمين</div>";
        header("refresh:2;url=debug_db.php");
    }

    echo "<br><a href='?clear_users=1' style='color: red;'>حذف جميع المستخدمين (للاختبار)</a><br>";
    echo "<br><a href='register.php'>العودة لصفحة التسجيل</a><br>";

} catch (Exception $e) {
    echo "<div style='color: red;'>خطأ: " . $e->getMessage() . "</div>";
}
?>