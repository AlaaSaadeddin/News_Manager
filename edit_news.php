<?php
include_once 'config/database.php';
requireLogin();

$allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
$message = '';
$error = '';
$news_id = intval($_GET['id'] ?? 0);

if ($news_id <= 0) {
    header("Location: view_news.php");
    exit();
}

// Get news data
$query = "SELECT * FROM news WHERE id = $news_id AND status = 'active'";
$result = $connection->query($query);
$news = $result ? $result->fetch_assoc() : null;

if (!$news) {
    header("Location: view_news.php");
    exit();
}

// Get categories for dropdown
$query = "SELECT * FROM categories ORDER BY name";
$result = $connection->query($query);
$categories = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $category_id = isset($_POST['category_id']) && $_POST['category_id'] !== '' ? intval($_POST['category_id']) : null;
    $content = trim($_POST['content'] ?? '');
    $image_name = $news['image']; // Keep existing image by default

    // Handle file upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $max_size = 5 * 1024 * 1024; // 5MB

        if (in_array($_FILES['image']['type'], $allowed_types) && $_FILES['image']['size'] <= $max_size) {
            $upload_dir = 'uploads/';
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }

            $file_extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $new_image_name = uniqid() . '.' . $file_extension;
            $target_path = $upload_dir . $new_image_name;

            if (move_uploaded_file($_FILES['image']['tmp_name'], $target_path)) {
                // Delete old image if it exists
                if ($news['image'] && file_exists($upload_dir . $news['image'])) {
                    unlink($upload_dir . $news['image']);
                }
                $image_name = $new_image_name;
            } else {
                $error = 'حدث خطأ أثناء رفع الصورة';
            }
        } else {
            $error = 'نوع الملف غير مدعوم أو حجم الملف كبير جداً (الحد الأقصى 5 ميجابايت)';
        }
    }

    // Check if title and content are valid
    if (empty($title) || empty($content)) {
        $error = 'العنوان والمحتوى مطلوبان';
    }

    if (empty($error)) {
        $update_query = $category_id === null
            ? "UPDATE news SET title = '$title', category_id = NULL, content = '$content', image = '$image_name' WHERE id = $news_id"
            : "UPDATE news SET title = '$title', category_id = $category_id, content = '$content', image = '$image_name' WHERE id = $news_id";

        if ($connection->query($update_query)) {
            $message = 'تم تحديث الخبر بنجاح';
            header("Location: edit_news.php?id=$news_id"); // Reload the page
            exit(); // Ensure the script stops executing
        } else {
            $error = 'حدث خطأ أثناء تحديث الخبر';
        }
    }
}
?>

<?php
    $page_title = "تعديل الخبر - نظام إدارة الأخبار";
    include 'header.php';
?>
            <!-- Main Content -->
            <div class="col-md-9 col-lg-10">
                <div class="p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2>تعديل الخبر</h2>
                        <a href="view_news.php" class="btn btn-outline-primary">
                            <i class="fas fa-list"></i> عرض الأخبار
                        </a>
                    </div>

                    <div class="row justify-content-center">
                        <div class="col-md-10">
                            <div class="card">
                                <div class="card-header bg-warning text-dark">
                                    <h5 class="mb-0">تعديل بيانات الخبر</h5>
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
                                                           value="<?php echo htmlspecialchars($news['title']); ?>" 
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
                                                                    <?php echo ($news['category_id'] == $category['id']) ? 'selected' : ''; ?>>
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
                                                      placeholder="أدخل تفاصيل الخبر"><?php echo htmlspecialchars($news['content']); ?></textarea>
                                        </div>

                                        <div class="mb-3">
                                            <label for="image" class="form-label">صورة الخبر</label>
                                            <?php if ($news['image']): ?>
                                                <div class="mb-2">
                                                    <label class="form-label text-muted">الصورة الحالية:</label><br>
                                                    <?php if (strpos($news['image'], 'http') === 0): ?>
                                                        <img src="<?php echo $news['image']; ?>" class="current-image img-thumbnail" alt="Current Image">
                                                    <?php else: ?>
                                                        <img src="uploads/<?php echo $news['image']; ?>" class="current-image img-thumbnail" alt="Current Image">
                                                    <?php endif; ?>
                                                </div>
                                            <?php endif; ?>
                                            <input type="file" class="form-control" id="image" name="image" 
                                                   accept="image/jpeg,image/png,image/gif" onchange="previewImage(this)">
                                            <small class="form-text text-muted">
                                                الأنواع المدعومة: JPG, PNG, GIF | الحد الأقصى: 5 ميجابايت | اتركه فارغاً للاحتفاظ بالصورة الحالية
                                            </small>
                                            <div id="imagePreview"></div>
                                        </div>

                                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                            <a href="view_news.php" class="btn btn-secondary me-md-2">
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