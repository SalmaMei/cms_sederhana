<?php
// Get total articles
$stmt = $pdo->query("SELECT COUNT(*) FROM articles");
$total_articles = $stmt->fetchColumn();

// Get total categories
$stmt = $pdo->query("SELECT COUNT(*) FROM categories");
$total_categories = $stmt->fetchColumn();

// Get total users
$stmt = $pdo->query("SELECT COUNT(*) FROM users");
$total_users = $stmt->fetchColumn();

// Get published articles count
$stmt = $pdo->query("SELECT COUNT(*) FROM articles WHERE status = 'published'");
$published_articles = $stmt->fetchColumn();

// Get recent articles
$stmt = $pdo->query("SELECT articles.*, users.username, categories.name as category_name 
                     FROM articles 
                     JOIN users ON articles.user_id = users.id 
                     LEFT JOIN categories ON articles.category_id = categories.id
                     ORDER BY created_at DESC 
                     LIMIT 5");
$recent_articles = $stmt->fetchAll();

// Get articles by category
$stmt = $pdo->query("SELECT c.name, COUNT(a.id) as total 
                     FROM categories c 
                     LEFT JOIN articles a ON c.id = a.category_id 
                     GROUP BY c.id");
$category_stats = $stmt->fetchAll();

// Get current time for greeting
$hour = date('H');
$greeting = '';
if ($hour >= 5 && $hour < 12) {
    $greeting = 'Selamat Pagi';
} elseif ($hour >= 12 && $hour < 15) {
    $greeting = 'Selamat Siang';
} elseif ($hour >= 15 && $hour < 19) {
    $greeting = 'Selamat Sore';
} else {
    $greeting = 'Selamat Malam';
}
?>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <!-- Welcome Message -->
        <div class="row">
            <div class="col-12">
                <div class="card bg-gradient-success">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <h2 class="text-white">
                                    <?php echo $greeting; ?>, <?php echo htmlspecialchars($_SESSION['username']); ?>!
                                </h2>
                                <p class="text-white">
                                    Selamat datang kembali di CMS Sederhana. Berikut adalah ringkasan aktivitas terbaru.
                                </p>
                            </div>
                            <div class="col-md-4 text-right">
                                <i class="fas fa-user-circle fa-5x text-white opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Info boxes -->
        <div class="row">
            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box mb-3">
                    <span class="info-box-icon bg-info elevation-1"><i class="fas fa-newspaper"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Articles</span>
                        <span class="info-box-number"><?php echo $total_articles; ?></span>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box mb-3">
                    <span class="info-box-icon bg-success elevation-1"><i class="fas fa-check-circle"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Published</span>
                        <span class="info-box-number"><?php echo $published_articles; ?></span>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box mb-3">
                    <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-folder"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Categories</span>
                        <span class="info-box-number"><?php echo $total_categories; ?></span>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box mb-3">
                    <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-users"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Users</span>
                        <span class="info-box-number"><?php echo $total_users; ?></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Recent Articles -->
            <div class="col-md-8">
                <div class="card card-outline card-info">
                    <div class="card-header border-transparent">
                        <h3 class="card-title">Recent Articles</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table m-0">
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Category</th>
                                        <th>Author</th>
                                        <th>Status</th>
                                        <th>Created</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($recent_articles as $article): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($article['title']); ?></td>
                                        <td>
                                            <span class="badge badge-info">
                                                <?php echo htmlspecialchars($article['category_name'] ?? 'Uncategorized'); ?>
                                            </span>
                                        </td>
                                        <td><?php echo htmlspecialchars($article['username']); ?></td>
                                        <td>
                                            <?php if ($article['status'] == 'published'): ?>
                                                <span class="badge badge-success">Published</span>
                                            <?php else: ?>
                                                <span class="badge badge-warning">Draft</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo date('M d, Y', strtotime($article['created_at'])); ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer clearfix">
                        <a href="?page=articles" class="btn btn-sm btn-info float-right">View All Articles</a>
                    </div>
                </div>
            </div>

            <!-- Category Stats -->
            <div class="col-md-4">
                <div class="card card-outline card-success">
                    <div class="card-header">
                        <h3 class="card-title">Articles by Category</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <ul class="products-list product-list-in-card pl-2 pr-2">
                            <?php foreach ($category_stats as $stat): ?>
                            <li class="item">
                                <div class="product-info">
                                    <a href="javascript:void(0)" class="product-title">
                                        <?php echo htmlspecialchars($stat['name']); ?>
                                        <span class="badge badge-success float-right"><?php echo $stat['total']; ?></span>
                                    </a>
                                    <div class="progress progress-sm">
                                        <div class="progress-bar bg-success" style="width: <?php echo ($stat['total'] / $total_articles * 100); ?>%"></div>
                                    </div>
                                </div>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <div class="card-footer text-center">
                        <a href="?page=categories" class="uppercase">View All Categories</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section> 