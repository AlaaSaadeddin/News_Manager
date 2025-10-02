<?php
include_once 'config/database.php';
requireLogin();

// Get all categories
$sql = "
    SELECT c.*, COUNT(n.id) AS news_count 
    FROM categories c 
    LEFT JOIN news n ON c.id = n.category_id AND n.status = 'active'
    GROUP BY c.id 
    ORDER BY c.id DESC
";

$result = $connection->query($sql);
$categories = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }
}
?>

<?php
    $page_title = "عرض الفئات - نظام إدارة الأخبار";
    include 'header.php';
?>
            <!-- Main Content -->
            <div class="col-md-9 col-lg-10">
                <div class="p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2>جميع الفئات</h2>
                        <a href="add_category.php" class="btn btn-primary">
                            <i class="fas fa-plus"></i> إضافة فئة جديدة
                        </a>
                    </div>

                        <div class="mt-3">
                            <div class="alert alert-light">
                                <i class="fas fa-info-circle"></i>
                                <strong>ملاحظة:</strong> لا يمكن حذف الفئات التي تحتوي على أخبار. يجب حذف جميع الأخبار المرتبطة بالفئة أولاً.
                            </div>
                        </div>
                    <?php if (empty($categories)): ?>
                        <div class="alert alert-info text-center">
                            <i class="fas fa-info-circle"></i> لا توجد فئات متاحة حالياً
                            <br>
                            <a href="add_category.php" class="btn btn-primary mt-2">
                                <i class="fas fa-plus"></i> إضافة فئة جديدة
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
                                                <th>اسم الفئة</th>
                                                <th>عدد الأخبار</th>
                                                <th>الإجراءات</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($categories as $index => $category): ?>
                                                <tr>
                                                    <td><?php echo $index + 1; ?></td>
                                                    <td>
                                                        <strong><?php echo htmlspecialchars($category['name']); ?></strong>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-primary">
                                                            <?php echo $category['news_count']; ?> خبر
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <a href="edit_categories.php?id=<?php echo $category['id']; ?>" 
                                                           class="btn btn-sm btn-outline-primary">
                                                            <i class="fas fa-edit"></i> تعديل
                                                        </a>
                                                        <?php if ($category['news_count'] == 0): ?>
                                                            <a href="delete_category.php?id=<?php echo $category['id']; ?>" 
                                                               class="btn btn-sm btn-outline-danger"
                                                               onclick="return confirm('هل أنت متأكد من حذف هذه الفئة؟')">
                                                                <i class="fas fa-trash"></i> حذف
                                                            </a>
                                                        <?php else: ?>
                                                            <button class="btn btn-sm btn-outline-secondary" disabled 
                                                                    title="لا يمكن حذف فئة تحتوي على أخبار">
                                                                <i class="fas fa-lock"></i> لا يمكن حذف
                                                            </button>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>