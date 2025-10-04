<?php
// Include the database configuration
include_once 'config/database.php';
requireLogin();

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $name =trim($_POST['name']);

    // Validate the category name
    if (empty($name)) {
        $error = 'اسم الفئة مطلوب';
    } else {
        // Check if category name already exists
        $check_query = "SELECT id FROM categories WHERE name = '$name'";
        $result = $connection->query($check_query);

        if ($result && $result->num_rows > 0) {
            $error = 'اسم الفئة موجود بالفعل';
        } else {
            // Insert new category into the database
            $insert_query = "INSERT INTO categories (name) VALUES ('$name')";
            $insert_result = $connection->query($insert_query);

            if ($insert_result) {
                $message = 'تم إضافة الفئة بنجاح';

                // Clear the form
                $_POST = array();
            } else {
                $error = 'حدث خطأ أثناء إضافة الفئة';
            }
        }
    }
}
?>


<?php
    $page_title = "إضافة فئة - نظام إدارة الأخبار";
    include 'header.php';
?>

<div class="p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>إضافة فئة جديدة</h2>
        <a href="view_categories.php" class="btn btn-outline-primary">
            <i class="fas fa-list"></i> عرض الفئات
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">بيانات الفئة</h5>
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
                                    value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>" 
                                    required placeholder="مثل: أخبار رياضية">
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-plus"></i> إضافة الفئة
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