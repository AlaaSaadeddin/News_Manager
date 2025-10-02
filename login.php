<?php

//I check in the database file if the connection is success 
include 'config/database.php';

$error = '';
$message = '';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    // Simple validation
    if (empty($email) || empty($password)) {
        $error = 'جميع الحقول مطلوبة.';
    } else {
        $sql = "SELECT id, name, email, password FROM users WHERE email = '$email'";
        $result = $connection->query($sql);
        if ($result && $result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                // Login success
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_email'] = $user['email'];
                header("Location: dashboard.php");
            } else { 
                $error = 'كلمة المرور او البريد غير صحيح.';
            }
        } else {
            $error = 'كلمة المرور او البريد غير صحيح.';
        }
    }
}

?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول - نظام إدارة الأخبار</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200..1000&family=Edu+NSW+ACT+Cursive&family=Epunda+Slab:ital,wght@0,300..900;1,300..900&family=Montserrat:ital,wght@0,100..900;1,100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Cairo','Arial', sans-serif; }
        .login-container { max-width: 400px; margin: 50px auto; }
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
        <div class="login-container">

            <div class="card">
                <div class="card-header bg-primary text-white text-center">
                    <h4>تسجيل الدخول</h4>
                </div>
                <div class="card-body">
                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="mb-3">
                            <label for="email" class="form-label">البريد الإلكتروني</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">كلمة المرور</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">تسجيل الدخول</button>
                    </form>
                    <div class="text-center mt-3">
                        <a href="register.php">ليس لديك حساب؟ أنشئ حساباً جديداً</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>