<?php
//done file
// session_start();
include 'config/database.php';
requireLogin();

$message = '';
$error = '';
$categories = [];

// Fetch categories
$category_query = "SELECT id, name FROM categories ORDER BY name ASC";
$category_result = $connection->query($category_query);
if ($category_result && $category_result->num_rows > 0) {
    while ($row = $category_result->fetch_assoc()) {
        $categories[] = $row;
    }
}

if (isset($_POST['add_news'])) {
    $title = trim($_POST['title'] ?? '');
    $category_id = $_POST['category_id'] ?? '';
    $content = trim($_POST['content'] ?? '');
    $user_id = $_SESSION['user_id'];
    $image_name = '';

    // Validate required fields
    if (empty($title) || empty($content)) {
        $error = "يرجى إدخال العنوان والمحتوى.";
    }


    // Handle image upload
    if (!$error && isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $allowed = ['image/jpeg', 'image/png', 'image/gif'];
        $max_size = 5 * 1024 * 1024;
        $type = $_FILES['image']['type'];
        $size = $_FILES['image']['size'];
        if (!in_array($type, $allowed) || $size > $max_size) {
            $error = "نوع الصورة غير مدعوم أو حجمها كبير جداً.";
        } else {
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }

            $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $image_name = uniqid() . '.' . $ext;
            $target_path = $upload_dir . $image_name;

            if (!move_uploaded_file($_FILES['image']['tmp_name'], $target_path)) {
                $error = "فشل في رفع الصورة.";
            }
        }
    }

    if (!$error) {
        // Set category_id as null if empty
        $category_id = $category_id ? intval($category_id) : 'NULL';

        // Directly construct the SQL query with the values
        $query = "INSERT INTO news (title, category_id, content, image, user_id, status) 
                VALUES ('$title', $category_id, '$content', '$image_name', $user_id, 'active')";

        // Execute the query
        if ($connection->query($query)) {
            $message = "تم إضافة الخبر بنجاح.";
            $_POST = []; // Clear the POST data
        } else {
            $error = "حدث خطأ أثناء حفظ الخبر: " . $connection->error;
            // Delete the uploaded image if the insertion fails
            if ($image_name && file_exists($target_path)) {
                unlink($target_path);
            }
        }
    }
}
?>

<?php
    $page_title = "إضافة خبر - نظام إدارة الأخبار";
    include 'header.php';
?>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10">
                <div class="p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2>إضافة خبر جديد</h2>
                        <a href="view_news.php" class="btn btn-outline-primary">
                            <i class="fas fa-list"></i> عرض الأخبار
                        </a>
                    </div>

                    <div class="row justify-content-center">
                        <div class="col-md-10">
                            <div class="card">
                                <div class="card-header bg-primary text-white">
                                    <h5 class="mb-0">بيانات الخبر</h5>
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

                                    <form method="POST" enctype="multipart/form-data">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <div class="mb-3">
                                                    <label for="title" class="form-label">عنوان الخبر <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" id="title" name="title" 
                                                           value="<?php echo isset($_POST['title']) ? htmlspecialchars($_POST['title']) : ''; ?>" 
                                                           required placeholder="أدخل عنوان الخبر">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label for="category_id" class="form-label">الفئة</label>
                                                    <select class="form-select" id="category_id" name="category_id">
                                                        <option value="">اختر الفئة</option>
                                                        <?php foreach ($categories as $category): ?>
                                                            <option value="<?php echo $category['id']; ?>"
                                                                    <?php echo (isset($_POST['category_id']) && $_POST['category_id'] == $category['id']) ? 'selected' : ''; ?>>
                                                                <?php echo htmlspecialchars($category['name']); ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="content" class="form-label">تفاصيل الخبر <span class="text-danger">*</span></label>
                                            <textarea class="form-control" id="content" name="content" rows="6" required
                                                      placeholder="أدخل تفاصيل الخبر"><?php echo isset($_POST['content']) ? htmlspecialchars($_POST['content']) : ''; ?></textarea>
                                        </div>

                                        <div class="mb-3">
                                            <label for="image" class="form-label">صورة الخبر</label>
                                            <input type="file" class="form-control" id="image" name="image" 
                                                   accept="image/jpeg,image/png,image/gif" onchange="previewImage(this)">
                                            <small class="form-text text-muted">
                                                الأنواع المدعومة: JPG, PNG, GIF | الحد الأقصى: 5 ميجابايت
                                            </small>
                                            <div id="imagePreview"></div>
                                        </div>

                                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                            <a href="dashboard.php" class="btn btn-secondary me-md-2">
                                                <i class="fas fa-times"></i> إلغاء
                                            </a>
                                           <button type="submit" name="add_news" id="add_news" class="btn btn-primary">
                                                <i class="fas fa-plus"></i> إضافة الخبر
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function previewImage(input) {
            const preview = document.getElementById('imagePreview');
            preview.innerHTML = '';
            
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'image-preview img-thumbnail';
                    preview.appendChild(img);
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</body>
</html>