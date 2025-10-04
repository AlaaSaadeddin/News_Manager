<!-- header.php -->
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title ?? 'نظام إدارة الأخبار'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200..1000&family=Edu+NSW+ACT+Cursive&family=Epunda+Slab:ital,wght@0,300..900;1,300..900&family=Montserrat:ital,wght@0,100..900;1,100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Cairo','Arial', sans-serif; }
        .navbar-brand { font-weight: bold; }
        .news-card { transition: transform 0.2s; }
        .news-card:hover { transform: translateY(-5px); }
        .current-image,.img-thumbnail{ width:300px; height:auto;}
        .news-image { width:300px; height:auto; object-fit: cover; }
        .sidebar { background-color: #f8f9fa; min-height: calc(100vh - 56px); }
        .sidebar .nav-link { color: #333; border-radius: 5px; margin: 2px 0; }
        .sidebar .nav-link:hover { background-color: #e9ecef; }
        .sidebar .nav-link.active { background-color: #007bff; color: white; }
    </style>

</head>
<body>

<nav class="navbar navbar-expand-xl bg-primary navbar-dark">
    <!-- Container wrapper -->
    <div class="container-fluid">
        <!-- Navbar brand -->
         <a class="navbar-brand" href="dashboard.php">
                <i class="fas fa-newspaper"></i> نظام إدارة الأخبار
            </a>

        <!-- <a class="navbar-brand" href="#">Brand</a> -->

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <i class="fas fa-bars"></i>
        </button>
                <!-- Collapsible wrapper -->
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="dashboard.php">
                        <i class="fas fa-home"></i> الصفحة الرئيسية
                    </a>
                </li>
                    <li class="nav-item">
                        <a class="nav-link" href="add_category.php">
                            <i class="fas fa-plus-circle"></i> إضافة فئة
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="view_categories.php">
                            <i class="fas fa-list"></i> عرض الفئات
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="add_news.php">
                            <i class="fas fa-plus"></i> إضافة خبر
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="view_news.php">
                            <i class="fas fa-newspaper"></i> عرض جميع الأخبار
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="deleted_news.php">
                            <i class="fas fa-trash"></i> الأخبار المحذوفة
                        </a>
                    </li>


                
            </ul>

            <!-- Icons -->
            <ul class="navbar-nav d-flex flex-row me-auto">
                <!-- Link -->
                    
                <li class="nav-item me-3">
                    <p class="navbar-text text-white me-3">مرحباً، <?php echo $_SESSION['user_name']; ?></p>
                </li>
                <li class="nav-item me-5">
                    <a class="nav-link text-white" href="logout.php">
                        <i class="fas fa-sign-out-alt"></i> تسجيل الخروج
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>


<div class="container-fluid">
    <div class="row">
            <!-- Main Content -->
            <div class="col-md-12 col-lg-12">