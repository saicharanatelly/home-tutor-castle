<?php
session_start();
include '../includes/config.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit();
}

// Create banners directory if it doesn't exist
$banners_dir = '../uploads/banners/';
if (!file_exists($banners_dir)) {
    mkdir($banners_dir, 0777, true);
}

// Handle banner upload
$success = $error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['banner_image'])) {
    $position = mysqli_real_escape_string($conn, $_POST['position']);
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $subtitle = mysqli_real_escape_string($conn, $_POST['subtitle']);
    $button_text = mysqli_real_escape_string($conn, $_POST['button_text']);
    $button_link = mysqli_real_escape_string($conn, $_POST['button_link']);
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    
    // Handle file upload
    $image_path = '';
    if ($_FILES['banner_image']['error'] == 0) {
        $file_name = time() . '_' . basename($_FILES['banner_image']['name']);
        $target_file = $banners_dir . $file_name;
        
        // Check file type
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        
        if (in_array($imageFileType, $allowed_types)) {
            if (move_uploaded_file($_FILES['banner_image']['tmp_name'], $target_file)) {
                $image_path = 'uploads/banners/' . $file_name;
            } else {
                $error = "Failed to upload image.";
            }
        } else {
            $error = "Only JPG, JPEG, PNG, GIF & WEBP files are allowed.";
        }
    }
    
    if (empty($error)) {
        $sql = "INSERT INTO banners (position, title, subtitle, button_text, button_link, image_path, is_active) 
                VALUES ('$position', '$title', '$subtitle', '$button_text', '$button_link', '$image_path', '$is_active')";
        
        if (mysqli_query($conn, $sql)) {
            $_SESSION['success'] = "Banner added successfully!";
            header('Location: update-banners.php');
            exit();
        } else {
            $error = "Error saving banner: " . mysqli_error($conn);
        }
    }
}

// Handle banner deletion
if (isset($_GET['delete'])) {
    $id = mysqli_real_escape_string($conn, $_GET['delete']);
    
    // Get banner info to delete image file
    $banner_query = "SELECT image_path FROM banners WHERE id = '$id'";
    $banner_result = mysqli_query($conn, $banner_query);
    $banner = mysqli_fetch_assoc($banner_result);
    
    // Delete image file if exists
    if ($banner && !empty($banner['image_path'])) {
        $image_file = '../' . $banner['image_path'];
        if (file_exists($image_file)) {
            unlink($image_file);
        }
    }
    
    // Delete from database
    $sql = "DELETE FROM banners WHERE id = '$id'";
    if (mysqli_query($conn, $sql)) {
        $_SESSION['success'] = "Banner deleted successfully!";
        header('Location: update-banners.php');
        exit();
    } else {
        $error = "Error deleting banner: " . mysqli_error($conn);
    }
}

// Handle banner status toggle
if (isset($_GET['toggle'])) {
    $id = mysqli_real_escape_string($conn, $_GET['toggle']);
    $sql = "UPDATE banners SET is_active = NOT is_active WHERE id = '$id'";
    if (mysqli_query($conn, $sql)) {
        $_SESSION['success'] = "Banner status updated!";
        header('Location: update-banners.php');
        exit();
    }
}

// Fetch all banners
$banners_query = "SELECT * FROM banners ORDER BY position, created_at DESC";
$banners_result = mysqli_query($conn, $banners_query);

// Check if banners table exists, create if not
$table_check = mysqli_query($conn, "SHOW TABLES LIKE 'banners'");
if (mysqli_num_rows($table_check) == 0) {
    // Create banners table
    $create_table = "CREATE TABLE banners (
        id INT PRIMARY KEY AUTO_INCREMENT,
        position VARCHAR(50) NOT NULL COMMENT 'home, about, etc.',
        title VARCHAR(255),
        subtitle TEXT,
        button_text VARCHAR(100),
        button_link VARCHAR(500),
        image_path VARCHAR(500) NOT NULL,
        is_active BOOLEAN DEFAULT 1,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";
    mysqli_query($conn, $create_table);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Banners - Admin Panel</title>
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
            min-height: 100vh;
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
        
        .banners-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }
        
        .banner-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
        }
        
        .banner-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.12);
        }
        
        .banner-image {
            height: 200px;
            width: 100%;
            object-fit: cover;
        }
        
        .banner-content {
            padding: 20px;
        }
        
        .banner-position {
            display: inline-block;
            background: var(--light-gray);
            color: var(--royal-violet);
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 15px;
            text-transform: uppercase;
        }
        
        .banner-title {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 10px;
            color: var(--primary-purple);
        }
        
        .banner-subtitle {
            color: #666;
            font-size: 14px;
            margin-bottom: 15px;
            line-height: 1.5;
        }
        
        .banner-actions {
            display: flex;
            gap: 8px;
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid var(--medium-gray);
        }
        
        .action-btn {
            flex: 1;
            padding: 8px 12px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 13px;
            font-weight: 500;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
        }
        
        .btn-toggle { 
            background: #28a745;
            color: white;
        }
        
        .btn-edit { 
            background: #17a2b8;
            color: white;
        }
        
        .btn-delete { 
            background: #dc3545;
            color: white;
        }
        
        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        
        .status-badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }
        
        .status-active { 
            background: #d4edda;
            color: #155724;
        }
        
        .status-inactive { 
            background: #f8d7da;
            color: #721c24;
        }
        
        /* Form Styles */
        .form-container {
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            margin-bottom: 40px;
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
            display: none;
        }
        
        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .checkbox-group input[type="checkbox"] {
            width: 18px;
            height: 18px;
        }
        
        .mobile-menu-toggle {
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
            
            .mobile-menu-toggle {
                display: block;
            }
        }
        
        @media (max-width: 768px) {
            .main-content {
                padding: 20px;
            }
            
            .banners-grid {
                grid-template-columns: 1fr;
            }
            
            .form-row {
                grid-template-columns: 1fr;
            }
            
            .page-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }
        }
    </style>
</head>
<body>
    <!-- Mobile Menu Toggle -->
    <button class="mobile-menu-toggle" onclick="toggleSidebar()">
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
                ['update-banners.php', 'fa-images', 'Update Banners'],
                ['settings.php', 'fa-cog', 'Settings'],
                ['logout.php', 'fa-sign-out-alt', 'Logout']
            ];
            
            foreach ($menu_items as $item) {
                $is_active = ($current_page == $item[0]) ? 'active' : '';
                echo "<a href=\"{$item[0]}\" class=\"menu-item {$is_active}\">
                        <i class=\"fas {$item[1]}\"></i>
                        <span>{$item[2]}</span>
                      </a>";
            }
            ?>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="page-header">
            <div>
                <h1>Manage Banners</h1>
                <p>Add, edit, or remove website banners</p>
            </div>
            <button onclick="showForm()" class="btn-primary">
                <i class="fas fa-plus"></i> Add New Banner
            </button>
        </div>

        <!-- Display Success/Error Messages -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <?php 
                echo $_SESSION['success']; 
                unset($_SESSION['success']);
                ?>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($error)): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <!-- Add Banner Form -->
        <div id="addBannerForm" class="form-container" style="display: none;">
            <h2>Add New Banner</h2>
            <form method="POST" enctype="multipart/form-data" onsubmit="return validateForm()">
                <div class="form-row">
                    <div class="form-group">
                        <label for="position">Banner Position *</label>
                        <select name="position" id="position" class="form-control" required>
                            <option value="">Select Position</option>
                            <option value="home">Home Page</option>
                            <option value="about">About Page</option>
                            <option value="services">Services Page</option>
                            <option value="contact">Contact Page</option>
                            <option value="courses">Courses Page</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="title">Title</label>
                        <input type="text" name="title" id="title" class="form-control" placeholder="Enter banner title">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="subtitle">Subtitle/Description</label>
                    <textarea name="subtitle" id="subtitle" class="form-control" rows="3" placeholder="Enter banner description"></textarea>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="button_text">Button Text</label>
                        <input type="text" name="button_text" id="button_text" class="form-control" placeholder="e.g., Learn More">
                    </div>
                    <div class="form-group">
                        <label for="button_link">Button Link</label>
                        <input type="url" name="button_link" id="button_link" class="form-control" placeholder="https://example.com">
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="banner_image">Banner Image *</label>
                        <input type="file" name="banner_image" id="banner_image" class="form-control" accept="image/*" required onchange="previewImage(event)">
                        <img id="imagePreview" class="image-preview" alt="Image Preview">
                    </div>
                    <div class="form-group">
                        <div class="checkbox-group">
                            <input type="checkbox" name="is_active" id="is_active" value="1" checked>
                            <label for="is_active">Active (Show on website)</label>
                        </div>
                        <small class="text-muted">Uncheck to temporarily hide this banner</small>
                    </div>
                </div>
                
                <div class="form-group" style="display: flex; gap: 10px; margin-top: 30px;">
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-save"></i> Save Banner
                    </button>
                    <button type="button" onclick="hideForm()" class="btn-primary" style="background: #6c757d;">
                        <i class="fas fa-times"></i> Cancel
                    </button>
                </div>
            </form>
        </div>

        <!-- Banners Grid -->
        <h2>Existing Banners</h2>
        <?php if (mysqli_num_rows($banners_result) > 0): ?>
            <div class="banners-grid">
                <?php while ($banner = mysqli_fetch_assoc($banners_result)): ?>
                    <div class="banner-card">
                        <img src="../<?php echo htmlspecialchars($banner['image_path']); ?>" 
                             alt="<?php echo htmlspecialchars($banner['title']); ?>" 
                             class="banner-image"
                             onerror="this.src='https://via.placeholder.com/400x200?text=Banner+Image'">
                        
                        <div class="banner-content">
                            <span class="banner-position"><?php echo ucfirst($banner['position']); ?></span>
                            
                            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 10px;">
                                <h3 class="banner-title"><?php echo htmlspecialchars($banner['title'] ?: 'No Title'); ?></h3>
                                <span class="status-badge <?php echo $banner['is_active'] ? 'status-active' : 'status-inactive'; ?>">
                                    <?php echo $banner['is_active'] ? 'Active' : 'Inactive'; ?>
                                </span>
                            </div>
                            
                            <p class="banner-subtitle">
                                <?php echo htmlspecialchars(substr($banner['subtitle'] ?: 'No description', 0, 100)); ?>
                                <?php if (strlen($banner['subtitle'] ?: '') > 100): ?>...<?php endif; ?>
                            </p>
                            
                            <?php if ($banner['button_text']): ?>
                                <div style="margin: 10px 0;">
                                    <small style="color: var(--royal-violet);">
                                        <i class="fas fa-link"></i> 
                                        Button: <?php echo htmlspecialchars($banner['button_text']); ?>
                                    </small>
                                </div>
                            <?php endif; ?>
                            
                            <small style="color: #888; display: block; margin-top: 10px;">
                                <i class="far fa-calendar"></i> 
                                Added: <?php echo date('M d, Y', strtotime($banner['created_at'])); ?>
                            </small>
                            
                            <div class="banner-actions">
                                <button onclick="toggleStatus(<?php echo $banner['id']; ?>)" 
                                        class="action-btn btn-toggle">
                                    <i class="fas fa-power-off"></i>
                                    <?php echo $banner['is_active'] ? 'Deactivate' : 'Activate'; ?>
                                </button>
                                
                                <a href="edit-banner.php?id=<?php echo $banner['id']; ?>" 
                                   class="action-btn btn-edit" style="text-decoration: none;">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                
                                <button onclick="confirmDelete(<?php echo $banner['id']; ?>)" 
                                        class="action-btn btn-delete">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <div style="text-align: center; padding: 50px 20px; background: white; border-radius: 12px; margin-top: 20px;">
                <i class="fas fa-images" style="font-size: 60px; color: var(--medium-gray); margin-bottom: 20px;"></i>
                <h3 style="color: var(--dark-gray); margin-bottom: 10px;">No Banners Found</h3>
                <p style="color: #666; margin-bottom: 20px;">You haven't added any banners yet. Click "Add New Banner" to get started.</p>
                <button onclick="showForm()" class="btn-primary">
                    <i class="fas fa-plus"></i> Add Your First Banner
                </button>
            </div>
        <?php endif; ?>
    </div>

    <script>
        // Toggle sidebar on mobile
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('active');
        }

        // Show/hide add banner form
        function showForm() {
            document.getElementById('addBannerForm').style.display = 'block';
            window.scrollTo({top: 0, behavior: 'smooth'});
        }
        
        function hideForm() {
            document.getElementById('addBannerForm').style.display = 'none';
            document.getElementById('imagePreview').style.display = 'none';
            document.getElementById('imagePreview').src = '';
        }

        // Image preview
        function previewImage(event) {
            const preview = document.getElementById('imagePreview');
            const file = event.target.files[0];
            
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                }
                reader.readAsDataURL(file);
            }
        }

        // Form validation
        function validateForm() {
            const position = document.getElementById('position').value;
            const image = document.getElementById('banner_image').value;
            
            if (!position) {
                alert('Please select a banner position');
                return false;
            }
            
            if (!image) {
                alert('Please select an image for the banner');
                return false;
            }
            
            return true;
        }

        // Toggle banner status
        function toggleStatus(bannerId) {
            if (confirm('Are you sure you want to change the status of this banner?')) {
                window.location.href = '?toggle=' + bannerId;
            }
        }

        // Delete banner confirmation
        function confirmDelete(bannerId) {
            if (confirm('Are you sure you want to delete this banner? This action cannot be undone.')) {
                window.location.href = '?delete=' + bannerId;
            }
        }

        // Auto-hide alerts after 5 seconds
        setTimeout(() => {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                alert.style.opacity = '0';
                alert.style.transition = 'opacity 0.5s';
                setTimeout(() => alert.style.display = 'none', 500);
            });
        }, 5000);

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const toggleBtn = document.querySelector('.mobile-menu-toggle');
            
            if (window.innerWidth <= 1200 && 
                sidebar.classList.contains('active') &&
                !sidebar.contains(event.target) &&
                !toggleBtn.contains(event.target)) {
                sidebar.classList.remove('active');
            }
        });
    </script>
</body>
</html>