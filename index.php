<?php
session_start();
require_once 'config/database.php';

// Redirect to login if not logged in
if (!isset($_SESSION['user_id']) && !isset($_GET['page'])) {
    header('Location: index.php?page=login');
    exit();
}

// Define allowed pages
$allowed_pages = [
    'dashboard' => 'pages/dashboard.php',
    'articles' => 'pages/articles.php',
    'categories' => 'pages/categories.php',
    'users' => 'pages/users.php',
    'login' => 'pages/login.php'
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CMS Sederhana</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
</head>
<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <?php
        if (isset($_SESSION['user_id'])) {
            include 'includes/header.php';
            include 'includes/sidebar.php';
        }
        ?>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <?php
            if (!isset($_SESSION['user_id'])) {
                include 'pages/login.php';
            } else {
                $page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
                
                // Check if page is allowed and exists
                if (isset($allowed_pages[$page]) && file_exists($allowed_pages[$page])) {
                    include $allowed_pages[$page];
                } else {
                    // If page doesn't exist, redirect to dashboard
                    header('Location: index.php?page=dashboard');
                    exit();
                }
            }   
            ?>
        </div>

        <?php include 'includes/footer.php'; ?>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
    
    <!-- Custom Scripts -->
    <script>
    $(document).ready(function() {
        // Enable all tooltips
        $('[data-toggle="tooltip"]').tooltip();
        
        // Enable all popovers
        $('[data-toggle="popover"]').popover();
        
        // Handle sidebar menu active state
        var currentPage = '<?php echo $page; ?>';
        $('.nav-sidebar a[href*="page=' + currentPage + '"]').addClass('active');
    });
    </script>
</body>
</html> 