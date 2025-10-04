<?php
include_once 'config/database.php';
requireLogin();

$user_id = $_SESSION['user_id'];

// Function to get an integer parameter safely from GET
function getIntParam($key) {
    return isset($_GET[$key]) ? intval($_GET[$key]) : 0;
}

// Restore deleted news if confirmed
$restore_id = getIntParam('restore');
if ($restore_id > 0) {
    $restore_query = "UPDATE news SET status = 'active' WHERE id = $restore_id AND status = 'deleted'";
    if ($connection->query($restore_query)) {
        $_SESSION['message'] = 'تم استعادة الخبر بنجاح';
        $_SESSION['message_type'] = 'success';
    } else {
        $_SESSION['message'] = '!حدث خطأ أثناء استعادة الخبر';
        $_SESSION['message_type'] = 'error';
    }

    header("Location: deleted_news.php");
    exit();
}

// Get deleted news with category and user info
$query = "SELECT n.id, n.title, n.content, n.image, c.name AS category_name, u.name AS user_name
          FROM news n
          LEFT JOIN categories c ON n.category_id = c.id
          LEFT JOIN users u ON n.user_id = u.id
          WHERE n.status = 'deleted' AND n.user_id = $user_id
          ORDER BY n.id DESC";

$result = $connection->query($query);


if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $deleted_news[] = $row;
    }
}

?>

<!-- get shared navigation bar -->
<?php
    $page_title = " حذف الأخبار - نظام إدارة الأخبار";
    include 'header.php';
?>
<div class="p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>الأخبار المحذوفة</h2>
    </div>

    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-<?php echo ($_SESSION['message_type'] == 'success') ? 'success' : 'danger'; ?> alert-dismissible fade show">
            <i class="fas fa-<?php echo ($_SESSION['message_type'] == 'success') ? 'check-circle' : 'exclamation-triangle'; ?>"></i>
            <?php echo $_SESSION['message']; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['message'], $_SESSION['message_type']); ?>
    <?php endif; ?>

    <?php if (empty($deleted_news)): ?>
        <div class="alert alert-info text-center">
            <i class="fas fa-info-circle"></i> لا توجد أخبار محذوفة حالياً
            <br>
            <small class="text-muted">الأخبار المحذوفة ستظهر هنا ويمكن استعادتها</small>
        </div>
    <?php else: ?>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>الصورة</th>
                                <th>العنوان</th>
                                <th>المحتوى</th>
                                <th>الفئة</th>
                                <th>الكاتب</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($deleted_news as $index => $news): ?>
                                <tr class="deleted-row">
                                    <td><?php echo $index + 1; ?></td>
                                    <td>
                                        <?php if ($news['image']): ?>
                                            <?php if (strpos($news['image'], 'http') === 0): ?>
                                                <img src="<?php echo $news['image']; ?>" 
                                                        class="news-image rounded opacity-50" 
                                                        alt="<?php echo htmlspecialchars($news['title']); ?>">
                                            <?php else: ?>
                                                <img src="uploads/<?php echo $news['image']; ?>" 
                                                        class="news-image rounded opacity-50" 
                                                        alt="<?php echo htmlspecialchars($news['title']); ?>">
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <div class="news-image bg-light rounded d-flex align-items-center justify-content-center opacity-50">
                                                <i class="fas fa-image text-muted"></i>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <strong class="text-muted"><?php echo htmlspecialchars($news['title']); ?></strong>
                                    </td>
                                    <td class="news-content">
                                        <small class="text-muted"><?php echo htmlspecialchars($news['content']); ?></small>
                                    </td>
                                    <td>
                                        <?php if ($news['category_name']): ?>
                                            <span class="badge bg-secondary"><?php echo htmlspecialchars($news['category_name']); ?></span>
                                        <?php else: ?>
                                            <span class="badge bg-light text-dark">غير محدد</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <small class="text-muted"><?php echo htmlspecialchars($news['user_name']); ?></small>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="deleted_news.php?restore=<?php echo $news['id']; ?>" 
                                                class="btn btn-sm btn-outline-success" title="استعادة"
                                                onclick="return confirm('هل أنت متأكد من استعادة هذا الخبر؟')">
                                                <i class="fas fa-undo"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="mt-3">
            <div class="alert alert-light">
                <i class="fas fa-info-circle"></i>
                <strong>إجمالي الأخبار المحذوفة:</strong> <?php echo count($deleted_news); ?> خبر
            </div>
        </div>
    <?php endif; ?>
</div>
<?php
    include 'footer.php';
?>