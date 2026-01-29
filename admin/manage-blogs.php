<?php
session_start();
include '../includes/config.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit();
}

// Handle actions
$success = $error = '';

// Add/Edit blog post
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = isset($_POST['id']) ? mysqli_real_escape_string($conn, $_POST['id']) : '';
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $content = mysqli_real_escape_string($conn, $_POST['content']);
    $excerpt = mysqli_real_escape_string($conn, $_POST['excerpt']);
    $author = mysqli_real_escape_string($conn, $_POST['author']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $tags = mysqli_real_escape_string($conn, $_POST['tags']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $meta_title = mysqli_real_escape_string($conn, $_POST['meta_title']);
    $meta_description = mysqli_real_escape_string($conn, $_POST['meta_description']);
    $meta_keywords = mysqli_real_escape_string($conn, $_POST['meta_keywords']);
    
    // Generate slug
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));
    
    // Handle file upload
    $featured_image = '';
    if (isset($_FILES['featured_image']) && $_FILES['featured_image']['error'] == 0) {
        $upload_dir = '../uploads/blog/';
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        $file_name = time() . '_' . basename($_FILES['featured_image']['name']);
        $target_file = $upload_dir . $file_name;
        
        // Check file type
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        
        if (in_array($imageFileType, $allowed_types)) {
            if (move_uploaded_file($_FILES['featured_image']['tmp_name'], $target_file)) {
                $featured_image = 'uploads/blog/' . $file_name;
            }
        }
    } elseif (!empty($_POST['existing_image'])) {
        $featured_image = mysqli_real_escape_string($conn, $_POST['existing_image']);
    }
    
    if (empty($id)) {
        // Add new post
        $sql = "INSERT INTO blog_posts (title, slug, content, excerpt, featured_image, author, category, tags, status, meta_title, meta_description, meta_keywords, published_at) 
                VALUES ('$title', '$slug', '$content', '$excerpt', '$featured_image', '$author', '$category', '$tags', '$status', '$meta_title', '$meta_description', '$meta_keywords', 
                " . ($status == 'published' ? 'NOW()' : 'NULL') . ")";
    } else {
        // Update existing post
        $sql = "UPDATE blog_posts SET 
                title = '$title',
                slug = '$slug',
                content = '$content',
                excerpt = '$excerpt',
                featured_image = '$featured_image',
                author = '$author',
                category = '$category',
                tags = '$tags',
                status = '$status',
                meta_title = '$meta_title',
                meta_description = '$meta_description',
                meta_keywords = '$meta_keywords',
                published_at = " . ($status == 'published' && empty($_POST['published_at']) ? 'NOW()' : 'published_at') . "
                WHERE id = '$id'";
    }
    
    if (mysqli_query($conn, $sql)) {
        $_SESSION['success'] = empty($id) ? "Blog post added successfully!" : "Blog post updated successfully!";
        header('Location: manage-blogs.php');
        exit();
    } else {
        $error = "Error: " . mysqli_error($conn);
    }
}

// Handle delete
if (isset($_GET['delete'])) {
    $id = mysqli_real_escape_string($conn, $_GET['delete']);
    $sql = "DELETE FROM blog_posts WHERE id = '$id'";
    if (mysqli_query($conn, $sql)) {
        $_SESSION['success'] = "Blog post deleted successfully!";
        header('Location: manage-blogs.php');
        exit();
    } else {
        $error = "Error deleting blog post: " . mysqli_error($conn);
    }
}

// Handle status change
if (isset($_GET['toggle_status'])) {
    $id = mysqli_real_escape_string($conn, $_GET['toggle_status']);
    $current_status = mysqli_fetch_assoc(mysqli_query($conn, "SELECT status FROM blog_posts WHERE id = '$id'"))['status'];
    $new_status = $current_status == 'published' ? 'draft' : 'published';
    
    $sql = "UPDATE blog_posts SET status = '$new_status', published_at = " . ($new_status == 'published' ? 'NOW()' : 'NULL') . " WHERE id = '$id'";
    if (mysqli_query($conn, $sql)) {
        $_SESSION['success'] = "Status updated successfully!";
        header('Location: manage-blogs.php');
        exit();
    }
}

// Fetch blog posts
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';
$search = isset($_GET['search']) ? $_GET['search'] : '';

$query = "SELECT * FROM blog_posts WHERE 1=1";
if ($filter != 'all') {
    $query .= " AND status = '$filter'";
}
if (!empty($search)) {
    $query .= " AND (title LIKE '%$search%' OR content LIKE '%$search%' OR author LIKE '%$search%')";
}
$query .= " ORDER BY created_at DESC";

$result = mysqli_query($conn, $query);

// Get post for editing
$edit_post = null;
if (isset($_GET['edit'])) {
    $id = mysqli_real_escape_string($conn, $_GET['edit']);
    $edit_result = mysqli_query($conn, "SELECT * FROM blog_posts WHERE id = '$id'");
    $edit_post = mysqli_fetch_assoc($edit_result);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Blogs - Admin Panel</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-purple: #3B0A6A;
            --royal-violet: #5E2B97;
            --magenta-pink: #C13C91;
            --warm-orange: #F6A04D;
            --dark-gray: #333333;
            --light-gray: #f8f9fa;
            --medium-gray: #e9ecef;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--light-gray);
            color: var(--dark-gray);
            line-height: 1.6;
        }
        
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
            color: var(--primary-purple);
        }
        
        .dashboard-container {
            display: flex;
            min-height: 100vh;
        }
        
        /* Sidebar Styles */
        .sidebar {
            width: 250px;
            background: linear-gradient(180deg, var(--primary-purple) 0%, var(--royal-violet) 100%);
            color: white;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            z-index: 1000;
            box-shadow: 3px 0 15px rgba(0,0,0,0.1);
        }
        
        .sidebar-header {
            padding: 25px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            text-align: center;
        }
        
        .sidebar-header h2 {
            color: white;
            font-size: 22px;
            margin-bottom: 5px;
        }
        
        .sidebar-menu {
            padding: 20px 0;
        }
        
        .menu-item {
            padding: 15px 25px;
            display: flex;
            align-items: center;
            gap: 12px;
            color: rgba(255,255,255,0.9);
            text-decoration: none;
            font-family: 'Inter', sans-serif;
            font-weight: 500;
            transition: all 0.3s ease;
            border-left: 4px solid transparent;
        }
        
        .menu-item:hover {
            background: rgba(255,255,255,0.1);
            color: white;
            border-left-color: var(--magenta-pink);
        }
        
        .menu-item.active {
            background: rgba(255,255,255,0.15);
            color: white;
            border-left-color: var(--warm-orange);
        }
        
        .main-content {
            flex: 1;
            margin-left: 250px;
            padding: 30px;
        }
        
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid var(--medium-gray);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--magenta-pink), var(--royal-violet));
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-family: 'Poppins', sans-serif;
            font-weight: 500;
            font-size: 15px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            text-decoration: none;
            box-shadow: 0 4px 12px rgba(195, 60, 145, 0.25);
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(195, 60, 145, 0.35);
        }
        
        .alert {
            padding: 16px 20px;
            border-radius: 8px;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 12px;
            border-left: 4px solid transparent;
        }
        
        .alert-success {
            background: #d4edda;
            color: #155724;
            border-left-color: #28a745;
        }
        
        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border-left-color: #dc3545;
        }
        
        .filters-section {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            margin-bottom: 30px;
        }
        
        .filter-buttons {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        
        .filter-btn {
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            color: var(--dark-gray);
            background: var(--medium-gray);
            transition: all 0.3s ease;
            font-size: 14px;
            font-weight: 500;
            font-family: 'Inter', sans-serif;
            border: 2px solid transparent;
        }
        
        .filter-btn.active {
            background: var(--primary-purple);
            color: white;
            border-color: var(--primary-purple);
        }
        
        .blogs-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 25px;
            margin-bottom: 30px;
        }
        
        .blog-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
        }
        
        .blog-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.12);
        }
        
        .blog-image {
            height: 200px;
            width: 100%;
            object-fit: cover;
        }
        
        .blog-content {
            padding: 20px;
        }
        
        .blog-title {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 10px;
            color: var(--primary-purple);
        }
        
        .blog-excerpt {
            color: #666;
            font-size: 14px;
            margin-bottom: 15px;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        .blog-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid var(--medium-gray);
        }
        
        .status-badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }
        
        .status-draft { background: #fff3cd; color: #856404; }
        .status-published { background: #d4edda; color: #155724; }
        .status-archived { background: #f8d7da; color: #721c24; }
        
        .blog-actions {
            display: flex;
            gap: 8px;
        }
        
        .action-btn {
            width: 36px;
            height: 36px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            color: white;
            font-size: 14px;
        }
        
        .btn-edit { background: #17a2b8; }
        .btn-delete { background: #dc3545; }
        .btn-toggle { background: #28a745; }
        
        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 2000;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(4px);
            padding: 20px;
        }
        
        .modal-content {
            background: white;
            padding: 35px;
            border-radius: 12px;
            width: 100%;
            max-width: 800px;
            max-height: 85vh;
            overflow-y: auto;
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: var(--dark-gray);
            font-weight: 500;
            font-size: 14px;
            font-family: 'Inter', sans-serif;
        }
        
        .form-control {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid var(--medium-gray);
            border-radius: 8px;
            font-size: 14px;
            font-family: 'Inter', sans-serif;
            transition: all 0.3s ease;
            background: white;
        }
        
        .form-control:focus {
            outline: none;
            border-color: var(--royal-violet);
            box-shadow: 0 0 0 3px rgba(94, 43, 151, 0.1);
        }
        
        textarea.form-control {
            min-height: 150px;
            resize: vertical;
        }
        
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        
        .image-preview {
            width: 100%;
            max-height: 200px;
            object-fit: cover;
            border-radius: 8px;
            margin-top: 10px;
            border: 2px dashed var(--medium-gray);
            padding: 10px;
        }
        
        .editor-toolbar {
            background: var(--light-gray);
            padding: 10px;
            border: 2px solid var(--medium-gray);
            border-bottom: none;
            border-radius: 8px 8px 0 0;
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        
        .editor-btn {
            background: white;
            border: 1px solid var(--medium-gray);
            padding: 8px 12px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .editor-btn:hover {
            background: var(--light-gray);
        }
        
        /* Mobile Menu Toggle */
        .menu-toggle {
            display: none;
            background: var(--primary-purple);
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 20px;
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 1001;
        }
        
        @media (max-width: 1200px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }
            
            .sidebar.active {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .menu-toggle {
                display: block;
            }
        }
        
        @media (max-width: 768px) {
            .main-content {
                padding: 20px;
            }
            
            .form-row {
                grid-template-columns: 1fr;
            }
            
            .blogs-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- Mobile Menu Toggle -->
    <button class="menu-toggle" onclick="toggleSidebar()">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <h2>Home Castle Tutor</h2>
            <p>Admin Panel</p>
        </div>
        
        <div class="sidebar-menu">
            <?php
            $current_page = basename($_SERVER['PHP_SELF']);
            $menu_items = [
                ['dashboard.php', 'fa-tachometer-alt', 'Dashboard'],
                ['student-requests.php', 'fa-user-graduate', 'Student Requests'],
                ['manage-tutors.php', 'fa-chalkboard-teacher', 'Manage Tutors'],
                ['manage-blogs.php', 'fa-blog', 'Manage Blogs'],
                ['contact-messages.php', 'fa-envelope', 'Contact Messages'],
                ['update-content.php', 'fa-edit', 'Update Content'],
                ['update-banners.php', 'fa-images', 'Update Banners'],
                ['reports.php', 'fa-chart-bar', 'Reports'],
                ['settings.php', 'fa-cog', 'Settings']
            ];
            
            foreach ($menu_items as $item) {
                $active = $current_page == $item[0] ? 'active' : '';
                echo '<a href="' . $item[0] . '" class="menu-item ' . $active . '">
                    <i class="fas ' . $item[1] . '"></i>
                    <span>' . $item[2] . '</span>
                </a>';
            }
            ?>
        </div>
    </div>
    
    <!-- Main Content -->
    <div class="main-content">
        <div class="page-header">
            <h1><i class="fas fa-blog" style="color: var(--royal-violet); margin-right: 12px;"></i> Manage Blog Posts</h1>
            <button class="btn-primary" onclick="openBlogModal()">
                <i class="fas fa-plus"></i> Add New Post
            </button>
        </div>
        
        <?php if(isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>
        
        <?php if(isset($error)): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
            </div>
        <?php endif; ?>
        
        <!-- Filters Section -->
        <div class="filters-section">
            <div class="filter-buttons">
                <a href="?filter=all" class="filter-btn <?php echo $filter == 'all' ? 'active' : ''; ?>">All Posts</a>
                <a href="?filter=draft" class="filter-btn <?php echo $filter == 'draft' ? 'active' : ''; ?>">Drafts</a>
                <a href="?filter=published" class="filter-btn <?php echo $filter == 'published' ? 'active' : ''; ?>">Published</a>
                <a href="?filter=archived" class="filter-btn <?php echo $filter == 'archived' ? 'active' : ''; ?>">Archived</a>
            </div>
        </div>
        
        <!-- Blogs Grid -->
        <div class="blogs-grid">
            <?php if(mysqli_num_rows($result) > 0): ?>
                <?php while($post = mysqli_fetch_assoc($result)): ?>
                <div class="blog-card">
                    <?php if($post['featured_image']): ?>
                        <img src="../<?php echo htmlspecialchars($post['featured_image']); ?>" alt="<?php echo htmlspecialchars($post['title']); ?>" class="blog-image">
                    <?php else: ?>
                        <div style="height: 200px; background: linear-gradient(135deg, var(--royal-violet), var(--magenta-pink)); display: flex; align-items: center; justify-content: center; color: white;">
                            <i class="fas fa-image fa-3x"></i>
                        </div>
                    <?php endif; ?>
                    
                    <div class="blog-content">
                        <h3 class="blog-title"><?php echo htmlspecialchars($post['title']); ?></h3>
                        <p class="blog-excerpt"><?php echo htmlspecialchars(substr($post['excerpt'] ?: strip_tags($post['content']), 0, 150)); ?>...</p>
                        
                        <div class="blog-meta">
                            <div>
                                <span class="status-badge status-<?php echo $post['status']; ?>">
                                    <?php echo ucfirst($post['status']); ?>
                                </span>
                                <div style="margin-top: 5px; font-size: 12px; color: #666;">
                                    <i class="fas fa-calendar"></i> <?php echo date('d M Y', strtotime($post['created_at'])); ?>
                                </div>
                            </div>
                            
                            <div class="blog-actions">
                                <button class="action-btn btn-toggle" onclick="toggleStatus(<?php echo $post['id']; ?>)" title="<?php echo $post['status'] == 'published' ? 'Unpublish' : 'Publish'; ?>">
                                    <i class="fas fa-power-off"></i>
                                </button>
                                <button class="action-btn btn-edit" onclick="editBlog(<?php echo $post['id']; ?>)" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <a href="?delete=<?php echo $post['id']; ?>" class="action-btn btn-delete" onclick="return confirm('Are you sure you want to delete this post?')" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div style="grid-column: 1 / -1; text-align: center; padding: 60px 20px;">
                    <i class="fas fa-blog" style="font-size: 60px; color: #ddd; margin-bottom: 20px;"></i>
                    <h3 style="color: var(--primary-purple); margin-bottom: 10px;">No Blog Posts Found</h3>
                    <p style="color: #666;">Start by adding your first blog post!</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Blog Modal -->
    <div id="blogModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3><i class="fas fa-blog" style="color: var(--royal-violet);"></i> <?php echo $edit_post ? 'Edit Blog Post' : 'Add New Blog Post'; ?></h3>
                <button class="btn-cancel" onclick="closeBlogModal()" style="padding: 8px 15px; background: #6c757d; color: white; border: none; border-radius: 6px; cursor: pointer;">Cancel</button>
            </div>
            <form id="blogForm" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?php echo $edit_post['id'] ?? ''; ?>">
                <input type="hidden" name="existing_image" id="existingImage" value="<?php echo $edit_post['featured_image'] ?? ''; ?>">
                
                <div class="form-group">
                    <label for="title">Post Title *</label>
                    <input type="text" id="title" name="title" class="form-control" value="<?php echo htmlspecialchars($edit_post['title'] ?? ''); ?>" required>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="author">Author</label>
                        <input type="text" id="author" name="author" class="form-control" value="<?php echo htmlspecialchars($edit_post['author'] ?? 'Admin'); ?>">
                    </div>
                    <div class="form-group">
                        <label for="category">Category</label>
                        <select id="category" name="category" class="form-control">
                            <option value="General" <?php echo ($edit_post['category'] ?? 'General') == 'General' ? 'selected' : ''; ?>>General</option>
                            <option value="Education" <?php echo ($edit_post['category'] ?? '') == 'Education' ? 'selected' : ''; ?>>Education</option>
                            <option value="Tips" <?php echo ($edit_post['category'] ?? '') == 'Tips' ? 'selected' : ''; ?>>Tips</option>
                            <option value="News" <?php echo ($edit_post['category'] ?? '') == 'News' ? 'selected' : ''; ?>>News</option>
                            <option value="Tutoring" <?php echo ($edit_post['category'] ?? '') == 'Tutoring' ? 'selected' : ''; ?>>Tutoring</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="featured_image">Featured Image</label>
                    <input type="file" id="featured_image" name="featured_image" class="form-control" accept="image/*" onchange="previewImage(event)">
                    <?php if($edit_post && $edit_post['featured_image']): ?>
                        <img src="../<?php echo htmlspecialchars($edit_post['featured_image']); ?>" alt="Current Image" class="image-preview" id="imagePreview">
                    <?php else: ?>
                        <img src="" alt="Image Preview" class="image-preview" id="imagePreview" style="display: none;">
                    <?php endif; ?>
                </div>
                
                <div class="form-group">
                    <label for="excerpt">Excerpt</label>
                    <textarea id="excerpt" name="excerpt" class="form-control" rows="3"><?php echo htmlspecialchars($edit_post['excerpt'] ?? ''); ?></textarea>
                    <small style="color: #666;">A short summary of your post (optional)</small>
                </div>
                
                <div class="form-group">
                    <label for="content">Content *</label>
                    <div class="editor-toolbar">
                        <button type="button" class="editor-btn" onclick="formatText('bold')"><i class="fas fa-bold"></i></button>
                        <button type="button" class="editor-btn" onclick="formatText('italic')"><i class="fas fa-italic"></i></button>
                        <button type="button" class="editor-btn" onclick="formatText('underline')"><i class="fas fa-underline"></i></button>
                        <button type="button" class="editor-btn" onclick="formatText('insertUnorderedList')"><i class="fas fa-list-ul"></i></button>
                        <button type="button" class="editor-btn" onclick="formatText('insertOrderedList')"><i class="fas fa-list-ol"></i></button>
                        <button type="button" class="editor-btn" onclick="formatText('createLink')"><i class="fas fa-link"></i></button>
                    </div>
                    <textarea id="content" name="content" class="form-control" rows="10" required><?php echo htmlspecialchars($edit_post['content'] ?? ''); ?></textarea>
                </div>
                
                <div class="form-group">
                    <label for="tags">Tags</label>
                    <input type="text" id="tags" name="tags" class="form-control" value="<?php echo htmlspecialchars($edit_post['tags'] ?? ''); ?>">
                    <small style="color: #666;">Separate tags with commas (e.g., education, tips, learning)</small>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select id="status" name="status" class="form-control">
                            <option value="draft" <?php echo ($edit_post['status'] ?? 'draft') == 'draft' ? 'selected' : ''; ?>>Draft</option>
                            <option value="published" <?php echo ($edit_post['status'] ?? '') == 'published' ? 'selected' : ''; ?>>Published</option>
                            <option value="archived" <?php echo ($edit_post['status'] ?? '') == 'archived' ? 'selected' : ''; ?>>Archived</option>
                        </select>
                    </div>
                </div>
                
                <div style="margin-top: 30px; padding-top: 20px; border-top: 2px solid var(--medium-gray);">
                    <h4 style="margin-bottom: 20px; color: var(--primary-purple);">SEO Settings (Optional)</h4>
                    <div class="form-group">
                        <label for="meta_title">Meta Title</label>
                        <input type="text" id="meta_title" name="meta_title" class="form-control" value="<?php echo htmlspecialchars($edit_post['meta_title'] ?? ''); ?>">
                    </div>
                    <div class="form-group">
                        <label for="meta_description">Meta Description</label>
                        <textarea id="meta_description" name="meta_description" class="form-control" rows="3"><?php echo htmlspecialchars($edit_post['meta_description'] ?? ''); ?></textarea>
                    </div>
                    <div class="form-group">
                        <label for="meta_keywords">Meta Keywords</label>
                        <input type="text" id="meta_keywords" name="meta_keywords" class="form-control" value="<?php echo htmlspecialchars($edit_post['meta_keywords'] ?? ''); ?>">
                    </div>
                </div>
                
                <div style="display: flex; gap: 15px; margin-top: 30px; padding-top: 20px; border-top: 2px solid var(--medium-gray);">
                    <button type="submit" class="btn-primary" style="flex: 1;">
                        <i class="fas fa-save"></i> <?php echo $edit_post ? 'Update Post' : 'Publish Post'; ?>
                    </button>
                    <button type="button" onclick="closeBlogModal()" class="btn-cancel" style="padding: 12px 25px;">Cancel</button>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        // Sidebar Toggle
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('active');
        }
        
        // Blog Modal Functions
        function openBlogModal() {
            document.getElementById('blogModal').style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }
        
        function closeBlogModal() {
            document.getElementById('blogModal').style.display = 'none';
            document.body.style.overflow = 'auto';
        }
        
        function editBlog(id) {
            window.location.href = '?edit=' + id;
        }
        
        function toggleStatus(id) {
            if (confirm('Are you sure you want to change the status of this post?')) {
                window.location.href = '?toggle_status=' + id;
            }
        }
        
        // Image Preview
        function previewImage(event) {
            const input = event.target;
            const preview = document.getElementById('imagePreview');
            
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
        
        // Text Formatting
        function formatText(command) {
            const textarea = document.getElementById('content');
            const start = textarea.selectionStart;
            const end = textarea.selectionEnd;
            const selectedText = textarea.value.substring(start, end);
            
            if (command === 'createLink') {
                const url = prompt('Enter URL:');
                if (url) {
                    const link = `<a href="${url}" target="_blank">${selectedText || 'Link'}</a>`;
                    textarea.value = textarea.value.substring(0, start) + link + textarea.value.substring(end);
                }
            } else {
                document.execCommand(command, false, null);
            }
            textarea.focus();
        }
        
        // Auto open modal if editing
        <?php if($edit_post): ?>
        document.addEventListener('DOMContentLoaded', function() {
            openBlogModal();
        });
        <?php endif; ?>
        
        // Close modal when clicking outside
        document.addEventListener('click', function(event) {
            if (event.target.classList.contains('modal')) {
                closeBlogModal();
            }
        });
        
        // Close modal with Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeBlogModal();
            }
        });
    </script>
</body>
</html>