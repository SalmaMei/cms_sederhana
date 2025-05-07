<?php
// Handle Delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM articles WHERE id = ?");
    $stmt->execute([$id]);
    header('Location: ?page=articles&message=deleted');
    exit();
}

// Handle Status Update
if (isset($_GET['status'])) {
    $id = $_GET['id'];
    $status = $_GET['status'];
    $stmt = $pdo->prepare("UPDATE articles SET status = ? WHERE id = ?");
    $stmt->execute([$status, $id]);
    header('Location: ?page=articles&message=updated');
    exit();
}

// Get all articles with category and author info
$stmt = $pdo->query("SELECT articles.*, categories.name as category_name, users.username 
                     FROM articles 
                     LEFT JOIN categories ON articles.category_id = categories.id 
                     JOIN users ON articles.user_id = users.id 
                     ORDER BY created_at DESC");
$articles = $stmt->fetchAll();

// Get all categories for the form
$stmt = $pdo->query("SELECT * FROM categories ORDER BY name");
$categories = $stmt->fetchAll();
?>

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Articles</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Articles</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <?php if (isset($_GET['message'])): ?>
            <?php if ($_GET['message'] == 'created'): ?>
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h5><i class="icon fas fa-check"></i> Success!</h5>
                    Article has been created successfully.
                </div>
            <?php elseif ($_GET['message'] == 'updated'): ?>
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h5><i class="icon fas fa-check"></i> Success!</h5>
                    Article has been updated successfully.
                </div>
            <?php elseif ($_GET['message'] == 'deleted'): ?>
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h5><i class="icon fas fa-check"></i> Success!</h5>
                    Article has been deleted successfully.
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Article List</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addArticleModal">
                                <i class="fas fa-plus"></i> Add New Article
                            </button>
                        </div>
                    </div>
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover text-nowrap">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Category</th>
                                    <th>Author</th>
                                    <th>Status</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($articles as $article): ?>
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
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#editArticleModal<?php echo $article['id']; ?>">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <a href="?page=articles&delete=<?php echo $article['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this article?')">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                            <?php if ($article['status'] == 'draft'): ?>
                                                <a href="?page=articles&id=<?php echo $article['id']; ?>&status=published" class="btn btn-sm btn-success">
                                                    <i class="fas fa-check"></i>
                                                </a>
                                            <?php else: ?>
                                                <a href="?page=articles&id=<?php echo $article['id']; ?>&status=draft" class="btn btn-sm btn-warning">
                                                    <i class="fas fa-times"></i>
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Edit Article Modal -->
                                <div class="modal fade" id="editArticleModal<?php echo $article['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="editArticleModalLabel<?php echo $article['id']; ?>" aria-hidden="true">
                                    <div class="modal-dialog modal-lg" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editArticleModalLabel<?php echo $article['id']; ?>">Edit Article</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form action="?page=articles&action=edit" method="post">
                                                <div class="modal-body">
                                                    <input type="hidden" name="id" value="<?php echo $article['id']; ?>">
                                                    <div class="form-group">
                                                        <label for="title">Title</label>
                                                        <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($article['title']); ?>" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="content">Content</label>
                                                        <textarea class="form-control" id="content" name="content" rows="5" required><?php echo htmlspecialchars($article['content']); ?></textarea>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="category_id">Category</label>
                                                        <select class="form-control" id="category_id" name="category_id">
                                                            <option value="">Select Category</option>
                                                            <?php foreach ($categories as $category): ?>
                                                                <option value="<?php echo $category['id']; ?>" <?php echo ($article['category_id'] == $category['id']) ? 'selected' : ''; ?>>
                                                                    <?php echo htmlspecialchars($category['name']); ?>
                                                                </option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="status">Status</label>
                                                        <select class="form-control" id="status" name="status">
                                                            <option value="draft" <?php echo ($article['status'] == 'draft') ? 'selected' : ''; ?>>Draft</option>
                                                            <option value="published" <?php echo ($article['status'] == 'published') ? 'selected' : ''; ?>>Published</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary">Save changes</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Add Article Modal -->
<div class="modal fade" id="addArticleModal" tabindex="-1" role="dialog" aria-labelledby="addArticleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addArticleModalLabel">Add New Article</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="?page=articles&action=add" method="post">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="title">Title</label>
                        <input type="text" class="form-control" id="title" name="title" required>
                    </div>
                    <div class="form-group">
                        <label for="content">Content</label>
                        <textarea class="form-control" id="content" name="content" rows="5" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="category_id">Category</label>
                        <select class="form-control" id="category_id" name="category_id">
                            <option value="">Select Category</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?php echo $category['id']; ?>">
                                    <?php echo htmlspecialchars($category['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select class="form-control" id="status" name="status">
                            <option value="draft">Draft</option>
                            <option value="published">Published</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Article</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
// Handle Add Article
if (isset($_GET['action']) && $_GET['action'] == 'add') {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $title = $_POST['title'];
        $content = $_POST['content'];
        $category_id = !empty($_POST['category_id']) ? $_POST['category_id'] : null;
        $status = $_POST['status'];
        $user_id = $_SESSION['user_id'];
        $slug = strtolower(str_replace(' ', '-', $title));

        $stmt = $pdo->prepare("INSERT INTO articles (title, slug, content, category_id, user_id, status) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$title, $slug, $content, $category_id, $user_id, $status]);
        
        header('Location: ?page=articles&message=created');
        exit();
    }
}

// Handle Edit Article
if (isset($_GET['action']) && $_GET['action'] == 'edit') {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $id = $_POST['id'];
        $title = $_POST['title'];
        $content = $_POST['content'];
        $category_id = !empty($_POST['category_id']) ? $_POST['category_id'] : null;
        $status = $_POST['status'];
        $slug = strtolower(str_replace(' ', '-', $title));

        $stmt = $pdo->prepare("UPDATE articles SET title = ?, slug = ?, content = ?, category_id = ?, status = ? WHERE id = ?");
        $stmt->execute([$title, $slug, $content, $category_id, $status, $id]);
        
        header('Location: ?page=articles&message=updated');
        exit();
    }
}
?> 