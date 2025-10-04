<?php
include_once 'config/database.php';
requireLogin();
$user_id = $_SESSION['user_id'];

$sql = "
    SELECT n.*, 
           c.name AS category_name, 
           u.name AS user_name 
    FROM news n 
    LEFT JOIN categories c ON n.category_id = c.id 
    LEFT JOIN users u ON n.user_id = u.id 
    WHERE n.status = 'active' && n.user_id = $user_id
    ORDER BY n.id DESC
";

$result = $connection->query($sql);
$news_list = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $news_list[] = $row;
    }
}
?>

<?php
    $page_title = "عرض الأخبار - نظام إدارة الأخبار";
    include 'header.php';
?>

<div class="p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>جميع الأخبار</h2>
        <a href="add_news.php" class="btn btn-primary">
            <i class="fas fa-plus"></i> إضافة خبر جديد
        </a>
    </div>

    <?php if (empty($news_list)): ?>
        <div class="alert alert-info text-center">
            <i class="fas fa-info-circle"></i> لا توجد أخبار متاحة حالياً
            <br>
            <a href="add_news.php" class="btn btn-primary mt-2">
                <i class="fas fa-plus"></i> إضافة خبر جديد
            </a>
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
                            <?php foreach ($news_list as $index => $news): ?>
                                <tr>
                                    <td><?php echo $index + 1; ?></td>
                                    <td>
                                        <?php if ($news['image']): ?>
                                            <?php 
                                            // Check if it's a URL (starts with http/https)
                                            if (strpos($news['image'], 'http') === 0): ?>
                                                <img src="<?php echo $news['image']; ?>" 
                                                        class="news-image rounded" 
                                                        alt="<?php echo htmlspecialchars($news['title']); ?>"
                                                        onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iNDAiIHZpZXdCb3g9IjAgMCA2MCA0MCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHJlY3Qgd2lkdGg9IjYwIiBoZWlnaHQ9IjQwIiBmaWxsPSIjZjhmOWZhIi8+CjxwYXRoIGQ9Ik0yNSAyMEwyMCAxNUgxNVYyNUgyMFYyMFoiIGZpbGw9IiM2Yzc1N2QiLz4KPC9zdmc+'">
                                            <?php else: ?>
                                                <img src="uploads/<?php echo $news['image']; ?>" 
                                                        class="news-image rounded" 
                                                        alt="<?php echo htmlspecialchars($news['title']); ?>"
                                                        onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iNDAiIHZpZXdCb3g9IjAgMCA2MCA0MCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHJlY3Qgd2lkdGg9IjYwIiBoZWlnaHQ9IjQwIiBmaWxsPSIjZjhmOWZhIi8+CjxwYXRoIGQ9Ik0yNSAyMEwyMCAxNUgxNVYyNUgyMFYyMFoiIGZpbGw9IiM2Yzc1N2QiLz4KPC9zdmc+'">
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <div class="news-image bg-light rounded d-flex align-items-center justify-content-center">
                                                <i class="fas fa-image text-muted"></i>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <strong><?php echo htmlspecialchars($news['title']); ?></strong>
                                    </td>
                                    <td class="news-content">
                                        <small><?php echo htmlspecialchars($news['content']); ?></small>
                                    </td>
                                    <td>
                                        <?php if ($news['category_name']): ?>
                                            <span class="badge bg-info"><?php echo htmlspecialchars($news['category_name']); ?></span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">غير محدد</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <small><?php echo htmlspecialchars($news['user_name']); ?></small>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="edit_news.php?id=<?php echo $news['id']; ?>" 
                                                class="btn btn-sm btn-outline-primary" title="تعديل">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="delete_news.php?id=<?php echo $news['id']; ?>" 
                                                class="btn btn-sm btn-outline-danger" title="حذف"
                                                onclick="return confirm('هل أنت متأكد من حذف هذا الخبر؟')">
                                                <i class="fas fa-trash"></i>
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
                <strong>إجمالي الأخبار:</strong> <?php echo count($news_list); ?> خبر
            </div>
        </div>
    <?php endif; ?>
</div>
           
<?php
    include 'footer.php';
?>