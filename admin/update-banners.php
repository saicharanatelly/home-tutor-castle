<?php
session_start();
include '../includes/config.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

// Handle form submissions
$message = '';
$message_type = '';

// Add new banner
if (isset($_POST['add_banner'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $subtitle = mysqli_real_escape_string($conn, $_POST['subtitle']);
    
    // Handle file upload
    $image_url = '';
    if (isset($_FILES['banner_image']) && $_FILES['banner_image']['error'] == 0) {
        $upload_dir = '../assets/images/banners/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }
        
        $file_name = time() . '_' . basename($_FILES['banner_image']['name']);
        $file_path = $upload_dir . $file_name;
        
        $allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
        $file_type = $_FILES['banner_image']['type'];
        
        if (in_array($file_type, $allowed_types)) {
            if (move_uploaded_file($_FILES['banner_image']['tmp_name'], $file_path)) {
                $image_url = 'assets/images/banners/' . $file_name;
            } else {
                $message = 'Error uploading file.';
                $message_type = 'error';
            }
        } else {
            $message = 'Invalid file type. Only JPG, PNG, GIF, and WebP are allowed.';
            $message_type = 'error';
        }
    }
    
    $button_text = mysqli_real_escape_string($conn, $_POST['button_text']);
    $button_link = mysqli_real_escape_string($conn, $_POST['button_link']);
    $position = mysqli_real_escape_string($conn, $_POST['position']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $display_order = (int)$_POST['display_order'];
    
    if ($image_url) {
        $sql = "INSERT INTO banners (title, subtitle, image_url, button_text, button_link, position, status, display_order) 
                VALUES ('$title', '$subtitle', '$image_url', '$button_text', '$button_link', '$position', '$status', '$display_order')";
        
        if (mysqli_query($conn, $sql)) {
            $message = 'Banner added successfully!';
            $message_type = 'success';
        } else {
            $message = 'Error adding banner: ' . mysqli_error($conn);
            $message_type = 'error';
        }
    } elseif (empty($_FILES['banner_image']['name'])) {
        $message = 'Please select an image for the banner.';
        $message_type = 'error';
    }
}

// Update banner
if (isset($_POST['update_banner'])) {
    $id = (int)$_POST['banner_id'];
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $subtitle = mysqli_real_escape_string($conn, $_POST['subtitle']);
    $button_text = mysqli_real_escape_string($conn, $_POST['button_text']);
    $button_link = mysqli_real_escape_string($conn, $_POST['button_link']);
    $position = mysqli_real_escape_string($conn, $_POST['position']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $display_order = (int)$_POST['display_order'];
    
    // Handle image update if new file is uploaded
    $update_image = '';
    if (isset($_FILES['banner_image']) && $_FILES['banner_image']['error'] == 0) {
        $upload_dir = '../assets/images/banners/';
        $file_name = time() . '_' . basename($_FILES['banner_image']['name']);
        $file_path = $upload_dir . $file_name;
        
        $allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
        $file_type = $_FILES['banner_image']['type'];
        
        if (in_array($file_type, $allowed_types)) {
            if (move_uploaded_file($_FILES['banner_image']['tmp_name'], $file_path)) {
                // Delete old image
                $old_image_query = mysqli_query($conn, "SELECT image_url FROM banners WHERE id = $id");
                if ($old_image_row = mysqli_fetch_assoc($old_image_query)) {
                    $old_image_path = '../' . $old_image_row['image_url'];
                    if (file_exists($old_image_path)) {
                        unlink($old_image_path);
                    }
                }
                $update_image = ", image_url = 'assets/images/banners/$file_name'";
            }
        }
    }
    
    $sql = "UPDATE banners SET 
            title = '$title',
            subtitle = '$subtitle',
            button_text = '$button_text',
            button_link = '$button_link',
            position = '$position',
            status = '$status',
            display_order = '$display_order'
            $update_image
            WHERE id = $id";
    
    if (mysqli_query($conn, $sql)) {
        $message = 'Banner updated successfully!';
        $message_type = 'success';
    } else {
        $message = 'Error updating banner: ' . mysqli_error($conn);
        $message_type = 'error';
    }
}

// Delete banner
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    
    // Get image path before deleting
    $image_query = mysqli_query($conn, "SELECT image_url FROM banners WHERE id = $id");
    if ($image_row = mysqli_fetch_assoc($image_query)) {
        $image_path = '../' . $image_row['image_url'];
        if (file_exists($image_path)) {
            unlink($image_path);
        }
    }
    
    $sql = "DELETE FROM banners WHERE id = $id";
    
    if (mysqli_query($conn, $sql)) {
        $message = 'Banner deleted successfully!';
        $message_type = 'success';
    } else {
        $message = 'Error deleting banner: ' . mysqli_error($conn);
        $message_type = 'error';
    }
}

// Toggle banner status
if (isset($_GET['toggle'])) {
    $id = (int)$_GET['toggle'];
    
    $sql = "UPDATE banners SET status = IF(status = 'active', 'inactive', 'active') WHERE id = $id";
    
    if (mysqli_query($conn, $sql)) {
        $message = 'Banner status updated!';
        $message_type = 'success';
    } else {
        $message = 'Error updating status: ' . mysqli_error($conn);
        $message_type = 'error';
    }
}

// Fetch all banners
$banners_query = "SELECT * FROM banners ORDER BY position, display_order, created_at DESC";
$banners_result = mysqli_query($conn, $banners_query);

// Count banners by status
$active_count = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM banners WHERE status = 'active'"));
$inactive_count = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM banners WHERE status = 'inactive'"));
$total_count = $active_count + $inactive_count;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Banners - Admin Panel</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-purple: #3B0A6A;
            --royal-violet: #5E2B97;
            --magenta-pink: #C13C91;
            --warm-orange: #F6A04D;
            --white: #FFFFFF;
            --light-gray: #F8F9FA;
            --medium-gray: #E9ECEF;
            --dark-gray: #343A40;
            --success-green: #28a745;
            --danger-red: #dc3545;
            --warning-yellow: #ffc107;
            --info-blue: #17a2b8;
            --shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            --radius: 12px;
            --transition: all 0.3s ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', 'Roboto', sans-serif;
            background: #f5f7fa;
            color: var(--dark-gray);
            line-height: 1.6;
        }

        .admin-container {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: 250px;
            background: linear-gradient(135deg, var(--primary-purple), var(--royal-violet));
            color: var(--white);
            position: fixed;
            height: 100vh;
            overflow-y: auto;
        }

        .logo {
            padding: 1.5rem;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .logo h2 {
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
            color: var(--white);
        }

        .logo span {
            color: var(--warm-orange);
        }

        .nav-links {
            padding: 1.5rem 0;
        }

        .nav-links a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 0.8rem 1.5rem;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: var(--transition);
        }

        .nav-links a:hover,
        .nav-links a.active {
            background: rgba(255, 255, 255, 0.1);
            color: var(--white);
            border-left: 4px solid var(--warm-orange);
        }

        .nav-links i {
            width: 20px;
            text-align: center;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            margin-left: 250px;
            padding: 2rem;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--medium-gray);
        }

        .header h1 {
            font-family: 'Poppins', sans-serif;
            color: var(--primary-purple);
            font-weight: 600;
        }

        .admin-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .admin-avatar {
            width: 45px;
            height: 45px;
            background: var(--royal-violet);
            color: var(--white);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: var(--white);
            padding: 1.5rem;
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            text-align: center;
            transition: var(--transition);
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(94, 43, 151, 0.15);
        }

        .stat-card.total { border-top: 4px solid var(--royal-violet); }
        .stat-card.active { border-top: 4px solid var(--success-green); }
        .stat-card.inactive { border-top: 4px solid var(--danger-red); }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            font-family: 'Poppins', sans-serif;
            margin-bottom: 0.5rem;
        }

        .stat-card.total .stat-number { color: var(--royal-violet); }
        .stat-card.active .stat-number { color: var(--success-green); }
        .stat-card.inactive .stat-number { color: var(--danger-red); }

        .stat-label {
            color: #666;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Message Alert */
        .alert {
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            animation: slideDown 0.3s ease;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .alert.success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert.error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .alert.info {
            background: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
        }

        .alert .close-btn {
            background: none;
            border: none;
            cursor: pointer;
            font-size: 1.2rem;
            color: inherit;
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
            flex-wrap: wrap;
        }

        .btn {
            padding: 0.8rem 1.5rem;
            border-radius: 30px;
            border: none;
            font-family: 'Poppins', sans-serif;
            font-weight: 500;
            cursor: pointer;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }

        .btn-primary {
            background: var(--magenta-pink);
            color: var(--white);
        }

        .btn-primary:hover {
            background: var(--royal-violet);
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(193, 60, 145, 0.3);
        }

        .btn-success {
            background: var(--success-green);
            color: var(--white);
        }

        .btn-warning {
            background: var(--warning-yellow);
            color: var(--dark-gray);
        }

        .btn-danger {
            background: var(--danger-red);
            color: var(--white);
        }

        .btn-outline {
            background: transparent;
            color: var(--royal-violet);
            border: 2px solid var(--royal-violet);
        }

        .btn-outline:hover {
            background: var(--royal-violet);
            color: var(--white);
        }

        /* Banners Table */
        .table-container {
            background: var(--white);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            overflow: hidden;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background: linear-gradient(135deg, var(--primary-purple), var(--royal-violet));
            color: var(--white);
        }

        th {
            padding: 1rem;
            text-align: left;
            font-family: 'Poppins', sans-serif;
            font-weight: 500;
        }

        tbody tr {
            border-bottom: 1px solid var(--medium-gray);
            transition: var(--transition);
        }

        tbody tr:hover {
            background: rgba(94, 43, 151, 0.05);
        }

        td {
            padding: 1rem;
            vertical-align: middle;
        }

        .banner-preview {
            width: 100px;
            height: 60px;
            border-radius: 6px;
            overflow: hidden;
            background: var(--light-gray);
        }

        .banner-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .status-badge {
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .status-active {
            background: #d4edda;
            color: #155724;
        }

        .status-inactive {
            background: #f8d7da;
            color: #721c24;
        }

        .actions {
            display: flex;
            gap: 0.5rem;
        }

        .action-btn {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            border: none;
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .edit-btn {
            background: #e3f2fd;
            color: #1976d2;
        }

        .edit-btn:hover {
            background: #bbdefb;
        }

        .delete-btn {
            background: #ffebee;
            color: #d32f2f;
        }

        .delete-btn:hover {
            background: #ffcdd2;
        }

        .toggle-btn {
            background: #fff3e0;
            color: #f57c00;
        }

        .toggle-btn:hover {
            background: #ffe0b2;
        }

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }

        .modal.active {
            display: flex;
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .modal-content {
            background: var(--white);
            border-radius: var(--radius);
            width: 90%;
            max-width: 600px;
            max-height: 90vh;
            overflow-y: auto;
            animation: slideUp 0.3s ease;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .modal-header {
            padding: 1.5rem;
            border-bottom: 1px solid var(--medium-gray);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-header h3 {
            font-family: 'Poppins', sans-serif;
            color: var(--primary-purple);
            font-weight: 600;
        }

        .close-modal {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: #666;
        }

        .modal-body {
            padding: 1.5rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: var(--primary-purple);
        }

        .form-control {
            width: 100%;
            padding: 0.8rem 1rem;
            border: 2px solid var(--medium-gray);
            border-radius: 8px;
            font-family: 'Inter', 'Roboto', sans-serif;
            transition: var(--transition);
        }

        .form-control:focus {
            outline: none;
            border-color: var(--royal-violet);
            box-shadow: 0 0 0 3px rgba(94, 43, 151, 0.1);
        }

        .file-preview {
            margin-top: 0.5rem;
        }

        .file-preview img {
            max-width: 200px;
            max-height: 120px;
            border-radius: 8px;
            border: 2px solid var(--medium-gray);
        }

        .modal-footer {
            padding: 1.5rem;
            border-top: 1px solid var(--medium-gray);
            text-align: right;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 3rem;
            color: #666;
        }

        .empty-state i {
            font-size: 3rem;
            color: var(--medium-gray);
            margin-bottom: 1rem;
        }

        /* Responsive */
        @media (max-width: 992px) {
            .sidebar {
                width: 70px;
            }

            .sidebar .logo h2,
            .sidebar .nav-links a span {
                display: none;
            }

            .main-content {
                margin-left: 70px;
            }

            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .main-content {
                padding: 1rem;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            table {
                display: block;
                overflow-x: auto;
            }

            .actions {
                flex-direction: column;
            }

            .action-btn {
                width: 30px;
                height: 30px;
            }
        }

        @media (max-width: 480px) {
            .admin-container {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }

            .main-content {
                margin-left: 0;
            }

            .nav-links {
                display: flex;
                overflow-x: auto;
            }

            .nav-links a {
                padding: 0.8rem;
                white-space: nowrap;
            }
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="logo">
                <h2>Home<span>Castle</span></h2>
                <p style="font-size: 0.8rem; margin-top: 5px; opacity: 0.8;">Admin Panel</p>
            </div>
            
            <nav class="nav-links">
                <a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> <span>Dashboard</span></a>
                <a href="update-banners.php" class="active"><i class="fas fa-images"></i> <span>Banners</span></a>
                <a href="manage-users.php"><i class="fas fa-users"></i> <span>Users</span></a>
                <a href="manage-tutors.php"><i class="fas fa-chalkboard-teacher"></i> <span>Tutors</span></a>
                <a href="manage-services.php"><i class="fas fa-concierge-bell"></i> <span>Services</span></a>
                <a href="blog-posts.php"><i class="fas fa-blog"></i> <span>Blog Posts</span></a>
                <a href="settings.php"><i class="fas fa-cog"></i> <span>Settings</span></a>
                <a href="logout.php"><i class="fas fa-sign-out-alt"></i> <span>Logout</span></a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <div class="header">
                <h1>Manage Banners</h1>
                <div class="admin-info">
                    <div class="admin-avatar">
                        <?php echo substr($_SESSION['admin_name'], 0, 1); ?>
                    </div>
                    <div>
                        <strong><?php echo $_SESSION['admin_name']; ?></strong>
                        <p style="font-size: 0.8rem; color: #666;">Administrator</p>
                    </div>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="stats-grid">
                <div class="stat-card total">
                    <div class="stat-number"><?php echo $total_count; ?></div>
                    <div class="stat-label">Total Banners</div>
                </div>
                <div class="stat-card active">
                    <div class="stat-number"><?php echo $active_count; ?></div>
                    <div class="stat-label">Active Banners</div>
                </div>
                <div class="stat-card inactive">
                    <div class="stat-number"><?php echo $inactive_count; ?></div>
                    <div class="stat-label">Inactive Banners</div>
                </div>
            </div>

            <!-- Message Alert -->
            <?php if ($message): ?>
                <div class="alert <?php echo $message_type; ?>">
                    <span><?php echo $message; ?></span>
                    <button class="close-btn" onclick="this.parentElement.style.display='none'">&times;</button>
                </div>
            <?php endif; ?>

            <!-- Action Buttons -->
            <div class="action-buttons">
                <button class="btn btn-primary" onclick="openAddModal()">
                    <i class="fas fa-plus"></i> Add New Banner
                </button>
                <a href="dashboard.php" class="btn btn-outline">
                    <i class="fas fa-arrow-left"></i> Back to Dashboard
                </a>
            </div>

            <!-- Banners Table -->
            <div class="table-container">
                <?php if (mysqli_num_rows($banners_result) > 0): ?>
                    <table>
                        <thead>
                            <tr>
                                <th>Preview</th>
                                <th>Title</th>
                                <th>Position</th>
                                <th>Order</th>
                                <th>Status</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($banner = mysqli_fetch_assoc($banners_result)): ?>
                                <tr>
                                    <td>
                                        <div class="banner-preview">
                                            <img src="../<?php echo $banner['image_url']; ?>" alt="<?php echo $banner['title']; ?>">
                                        </div>
                                    </td>
                                    <td>
                                        <strong><?php echo $banner['title']; ?></strong><br>
                                        <small style="color: #666;"><?php echo substr($banner['subtitle'], 0, 50) . '...'; ?></small>
                                    </td>
                                    <td>
                                        <span class="badge" style="background: rgba(94, 43, 151, 0.1); color: var(--royal-violet); padding: 0.3rem 0.8rem; border-radius: 4px;">
                                            <?php echo ucfirst($banner['position']); ?>
                                        </span>
                                    </td>
                                    <td><?php echo $banner['display_order']; ?></td>
                                    <td>
                                        <span class="status-badge status-<?php echo $banner['status']; ?>">
                                            <?php echo ucfirst($banner['status']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php echo date('M d, Y', strtotime($banner['created_at'])); ?>
                                    </td>
                                    <td>
                                        <div class="actions">
                                            <button class="action-btn edit-btn" onclick="openEditModal(
                                                <?php echo $banner['id']; ?>,
                                                '<?php echo addslashes($banner['title']); ?>',
                                                '<?php echo addslashes($banner['subtitle']); ?>',
                                                '<?php echo addslashes($banner['button_text']); ?>',
                                                '<?php echo addslashes($banner['button_link']); ?>',
                                                '<?php echo $banner['position']; ?>',
                                                '<?php echo $banner['status']; ?>',
                                                <?php echo $banner['display_order']; ?>,
                                                '<?php echo $banner['image_url']; ?>'
                                            )">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <a href="?toggle=<?php echo $banner['id']; ?>" class="action-btn toggle-btn" title="Toggle Status">
                                                <i class="fas fa-power-off"></i>
                                            </a>
                                            <a href="?delete=<?php echo $banner['id']; ?>" 
                                               class="action-btn delete-btn" 
                                               title="Delete"
                                               onclick="return confirm('Are you sure you want to delete this banner?')">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="empty-state">
                        <i class="fas fa-images"></i>
                        <h3>No Banners Found</h3>
                        <p>Add your first banner by clicking the "Add New Banner" button above.</p>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>

    <!-- Add Banner Modal -->
    <div class="modal" id="addModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Add New Banner</h3>
                <button class="close-modal" onclick="closeAddModal()">&times;</button>
            </div>
            <form method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="title">Banner Title *</label>
                        <input type="text" id="title" name="title" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="subtitle">Banner Subtitle *</label>
                        <textarea id="subtitle" name="subtitle" class="form-control" rows="3" required></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="banner_image">Banner Image *</label>
                        <input type="file" id="banner_image" name="banner_image" class="form-control" accept="image/*" required onchange="previewImage(this, 'addPreview')">
                        <div class="file-preview" id="addPreview"></div>
                        <small style="color: #666;">Recommended size: 1920x600px, Max size: 2MB</small>
                    </div>
                    
                    <div class="form-row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <div class="form-group">
                            <label for="button_text">Button Text (Optional)</label>
                            <input type="text" id="button_text" name="button_text" class="form-control" placeholder="e.g., Get Started">
                        </div>
                        
                        <div class="form-group">
                            <label for="button_link">Button Link (Optional)</label>
                            <input type="text" id="button_link" name="button_link" class="form-control" placeholder="e.g., /services">
                        </div>
                    </div>
                    
                    <div class="form-row" style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1rem;">
                        <div class="form-group">
                            <label for="position">Position *</label>
                            <select id="position" name="position" class="form-control" required>
                                <option value="hero">Hero Section</option>
                                <option value="featured">Featured</option>
                                <option value="sidebar">Sidebar</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="display_order">Display Order *</label>
                            <input type="number" id="display_order" name="display_order" class="form-control" value="0" min="0" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="status">Status *</label>
                            <select id="status" name="status" class="form-control" required>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline" onclick="closeAddModal()">Cancel</button>
                    <button type="submit" name="add_banner" class="btn btn-primary">Add Banner</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Banner Modal -->
    <div class="modal" id="editModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Edit Banner</h3>
                <button class="close-modal" onclick="closeEditModal()">&times;</button>
            </div>
            <form method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" id="edit_id" name="banner_id">
                    
                    <div class="form-group">
                        <label for="edit_title">Banner Title *</label>
                        <input type="text" id="edit_title" name="title" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="edit_subtitle">Banner Subtitle *</label>
                        <textarea id="edit_subtitle" name="subtitle" class="form-control" rows="3" required></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="edit_banner_image">Banner Image</label>
                        <input type="file" id="edit_banner_image" name="banner_image" class="form-control" accept="image/*" onchange="previewImage(this, 'editPreview')">
                        <div class="file-preview" id="editPreview"></div>
                        <small style="color: #666;">Leave empty to keep existing image</small>
                    </div>
                    
                    <div class="form-row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <div class="form-group">
                            <label for="edit_button_text">Button Text</label>
                            <input type="text" id="edit_button_text" name="button_text" class="form-control">
                        </div>
                        
                        <div class="form-group">
                            <label for="edit_button_link">Button Link</label>
                            <input type="text" id="edit_button_link" name="button_link" class="form-control">
                        </div>
                    </div>
                    
                    <div class="form-row" style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1rem;">
                        <div class="form-group">
                            <label for="edit_position">Position *</label>
                            <select id="edit_position" name="position" class="form-control" required>
                                <option value="hero">Hero Section</option>
                                <option value="featured">Featured</option>
                                <option value="sidebar">Sidebar</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="edit_display_order">Display Order *</label>
                            <input type="number" id="edit_display_order" name="display_order" class="form-control" min="0" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="edit_status">Status *</label>
                            <select id="edit_status" name="status" class="form-control" required>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline" onclick="closeEditModal()">Cancel</button>
                    <button type="submit" name="update_banner" class="btn btn-primary">Update Banner</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Modal Functions
        function openAddModal() {
            document.getElementById('addModal').classList.add('active');
        }

        function closeAddModal() {
            document.getElementById('addModal').classList.remove('active');
            document.getElementById('addPreview').innerHTML = '';
            document.querySelector('#addModal form').reset();
        }

        function openEditModal(id, title, subtitle, buttonText, buttonLink, position, status, order, imageUrl) {
            document.getElementById('edit_id').value = id;
            document.getElementById('edit_title').value = title;
            document.getElementById('edit_subtitle').value = subtitle;
            document.getElementById('edit_button_text').value = buttonText;
            document.getElementById('edit_button_link').value = buttonLink;
            document.getElementById('edit_position').value = position;
            document.getElementById('edit_status').value = status;
            document.getElementById('edit_display_order').value = order;
            
            // Show existing image preview
            const previewDiv = document.getElementById('editPreview');
            previewDiv.innerHTML = `<img src="../${imageUrl}" alt="${title}" style="max-width: 200px; max-height: 120px; border-radius: 8px;">`;
            
            document.getElementById('editModal').classList.add('active');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.remove('active');
            document.getElementById('editPreview').innerHTML = '';
        }

        // Image Preview Function
        function previewImage(input, previewId) {
            const preview = document.getElementById(previewId);
            preview.innerHTML = '';
            
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.style.maxWidth = '200px';
                    img.style.maxHeight = '120px';
                    img.style.borderRadius = '8px';
                    preview.appendChild(img);
                }
                
                reader.readAsDataURL(input.files[0]);
            }
        }

        // Close modals when clicking outside
        window.onclick = function(event) {
            const modals = document.querySelectorAll('.modal');
            modals.forEach(modal => {
                if (event.target === modal) {
                    modal.classList.remove('active');
                    document.getElementById('addPreview').innerHTML = '';
                    document.getElementById('editPreview').innerHTML = '';
                }
            });
        }

        // Auto-hide message alert after 5 seconds
        setTimeout(() => {
            const alert = document.querySelector('.alert');
            if (alert) {
                alert.style.display = 'none';
            }
        }, 5000);
    </script>
</body>
</html>