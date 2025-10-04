<?php
include_once 'config/database.php';
requireLogin();

$message = '';
$error = '';
$category_id = intval($_GET['id'] ?? 0);

if ($category_id <= 0) {
    header("Location: view_categories.php");
    exit();
}

// Get category data
$query = "SELECT * FROM categories WHERE id = $category_id";
$result = $connection->query($query);
$category = $result ? $result->fetch_assoc() : null;

if (!$category) {
    header("Location: view_categories.php");
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');

    if (empty($name)) {
        $error = 'اسم الفئة مطلوب';
    } else {
        // Check if category name already exists
        $check_query = "SELECT id FROM categories WHERE name = '$name' AND id != $category_id";
        $check_result = $connection->query($check_query);

        if ($check_result && $check_result->num_rows > 0) {
            $error = 'اسم الفئة موجود بالفعل';
        } else {
            // Update category
            $update_query = "UPDATE categories SET name = '$name' WHERE id = $category_id";
            if ($connection->query($update_query)) {
                $message = 'تم تحديث الفئة بنجاح';
                
                // Refresh category data
                $result = $connection->query($query);
                $category = $result ? $result->fetch_assoc() : null;
            } else {
                $error = 'حدث خطأ أثناء تحديث الفئة';
            }
        }
    }
}
?>

<?php
    $page_title = "تعديل الفئة - نظام إدارة الأخبار";
    include 'header.php';
?>
<div class="p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>تعديل الفئة</h2>
        <a href="view_categories.php" class="btn btn-outline-primary">
            <i class="fas fa-list"></i> عرض الفئات
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">تعديل بيانات الفئة</h5>
                </div>
                <div class="card-body">
                    <?php if ($error): ?>
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle"></i> <?php echo $error; ?>
                        </div>
                    <?php endif; ?>
                    <?php if ($message): ?>
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle"></i> <?php echo $message; ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="mb-3">
                            <label for="name" class="form-label">اسم الفئة <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" 
                                    value="<?php echo htmlspecialchars($category['name']); ?>" 
                                    required placeholder="مثل: أخبار رياضية">
                        </div>
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="view_categories.php" class="btn btn-secondary me-md-2">
                                <i class="fas fa-times"></i> إلغاء
                            </a>
                            <button type="submit" class="btn btn-warning">
                                <i class="fas fa-save"></i> حفظ التغييرات
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
    include 'footer.php';
?>