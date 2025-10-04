<?php
include('config/database.php');

$error = '';
$message = '';

function validateData($data){
    $data = trim($data);
    $data = htmlspecialchars($data);
    return $data;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_account'])) {
    
    // trim input
    $name             = validateData($_POST['name']);
    $email            = validateData($_POST['email']);
    $password         = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Validation
    if (empty($name) || empty($email) || empty($password) || empty($confirm_password)) {
        $error = "جميع الحقول مطلوبة.";
    } elseif ($password !== $confirm_password) {
        $error = "كلمتا المرور غير متطابقتين.";
    } else {
        // Check if email already exists
        $check_query = "SELECT id FROM users WHERE email = '$email' LIMIT 1";
        $result = $connection->query($check_query); 

        if ($result && $result->num_rows > 0) {
            $error = "البريد الإلكتروني مستخدم بالفعل.";
        } else {
            // Hash the password
            $hashed_password = password_hash(validateData($_POST["password"]),PASSWORD_BCRYPT);

            // Insert into database
            $insert_query = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$hashed_password')";
            $insert_result = $connection->query($insert_query); 

            if ($insert_result) {
                $message = "تم إنشاء الحساب بنجاح! يمكنك تسجيل الدخول الآن.";
            } else {
                $error = "حدث خطأ أثناء إنشاء الحساب. حاول مرة أخرى.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إنشاء حساب - نظام إدارة الأخبار</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200..1000&family=Edu+NSW+ACT+Cursive&family=Epunda+Slab:ital,wght@0,300..900;1,300..900&family=Montserrat:ital,wght@0,100..900;1,100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Cairo','Arial', sans-serif; }
        .register-container { max-width: 400px; margin: 50px auto; }
        .header { text-align:center; max-width: 700px; margin: 50px auto; }
        .card { box-shadow: 0 0 10px rgba(0,0,0,0.1); }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="feature-icon">
                <h3 class="display-4 mb-4"><i class="fas fa-newspaper"></i>نظام إدارة الأخبار</h3>
            </div>
            <p class="lead mb-4">
                منصة شاملة لإدارة ونشر الأخبار مع إمكانيات متقدمة لتنظيم المحتوى والفئات
            </p>
        </div>
        <div class="register-container">
            <div class="card">
                <div class="card-header bg-primary text-white text-center">
                    <h4>إنشاء حساب جديد</h4>
                </div>
                <div class="card-body">
                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>
                    <?php if ($message): ?>
                        <div class="alert alert-success"><?php echo $message; ?></div>
                    <?php endif; ?>
                   

                    <form method="POST">
                        <div class="mb-3">
                            <label for="name" class="form-label">الاسم</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">البريد الإلكتروني</label>
                            <input type="email" class="form-control" id="email" name="email"  required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">كلمة المرور</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">تأكيد كلمة المرور</label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                        </div>
                        <button type="submit" name="create_account"  class="btn btn-primary w-100">إنشاء حساب</button>
                    </form>
                    <div class="text-center mt-3">
                        <a href="login.php">لديك حساب بالفعل؟ سجل دخولك</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>