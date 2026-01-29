<?php
session_start();
include '../includes/config.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit();
}

// Get statistics
$total_students = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM student_requirements"))['count'];
$pending_requests = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM student_requirements WHERE status = 'pending'"))['count'];
$total_tutors = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM tutors"))['count'];
$pending_tutors = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM tutors WHERE status = 'pending'"))['count'];
$unread_contacts = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM contacts WHERE status = 'unread'"))['count'];

// Get blog statistics
$total_posts = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM blog_posts"))['count'];
$published_posts = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM blog_posts WHERE status = 'published'"))['count'];

// Recent activities
$recent_students = mysqli_query($conn, "SELECT * FROM student_requirements ORDER BY created_at DESC LIMIT 5");
$recent_contacts = mysqli_query($conn, "SELECT * FROM contacts ORDER BY created_at DESC LIMIT 5");
$recent_posts = mysqli_query($conn, "SELECT * FROM blog_posts ORDER BY created_at DESC LIMIT 5");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Admin Panel</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600&family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-purple: #3B0A6A;
            --royal-violet: #5E2B97;
            --magenta-pink: #C13C91;
            --warm-orange: #F6A04D;
            --dark-gray: #333333;
            --light-gray: #f8f9fa;
            --medium-gray: #e9ecef;
            --success-green: #28a745;
            --warning-yellow: #ffc107;
            --danger-red: #dc3545;
            --info-blue: #17a2b8;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', 'Roboto', sans-serif;
            background-color: var(--light-gray);
            color: var(--dark-gray);
            line-height: 1.6;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
            color: var(--primary-purple);
        }

        h1 {
            font-size: 32px;
            font-weight: 700;
        }

        h2 {
            font-size: 24px;
            font-weight: 600;
        }

        h3 {
            font-size: 20px;
            font-weight: 500;
        }

        /* Main Layout */
        .dashboard-container {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar Styles - Exact match with manage-tutors.php */
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

        .sidebar-header p {
            font-size: 12px;
            opacity: 0.8;
            font-family: 'Inter', sans-serif;
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
            position: relative;
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

        .menu-item i {
            width: 20px;
            text-align: center;
            font-size: 16px;
        }

        .menu-item span {
            font-size: 14px;
        }

        .badge {
            background: var(--warm-orange);
            color: white;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
            margin-left: auto;
        }

        .sidebar-footer {
            padding: 20px;
            border-top: 1px solid rgba(255,255,255,0.1);
            position: absolute;
            bottom: 0;
            width: 100%;
        }

        .admin-profile {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .admin-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--magenta-pink);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            color: white;
        }

        .admin-info h4 {
            color: white;
            font-size: 14px;
            margin-bottom: 3px;
        }

        .admin-info p {
            font-size: 12px;
            opacity: 0.8;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            margin-left: 250px;
            padding: 30px;
            min-height: 100vh;
        }

        /* Header */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid var(--medium-gray);
        }

        .header-actions {
            display: flex;
            gap: 15px;
            align-items: center;
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

        /* Statistics Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }

        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            display: flex;
            align-items: center;
            gap: 20px;
            transition: all 0.3s ease;
            border-top: 4px solid;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.12);
        }

        .stat-card.students {
            border-top-color: var(--royal-violet);
        }

        .stat-card.tutors {
            border-top-color: var(--magenta-pink);
        }

        .stat-card.pending {
            border-top-color: var(--warm-orange);
        }

        .stat-card.contacts {
            border-top-color: var(--info-blue);
        }

        .stat-card.blogs {
            border-top-color: var(--success-green);
        }

        .stat-icon {
            width: 70px;
            height: 70px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 30px;
            color: white;
        }

        .stat-card.students .stat-icon {
            background: linear-gradient(135deg, var(--royal-violet), var(--primary-purple));
        }

        .stat-card.tutors .stat-icon {
            background: linear-gradient(135deg, var(--magenta-pink), #e83e8c);
        }

        .stat-card.pending .stat-icon {
            background: linear-gradient(135deg, var(--warm-orange), #ff8c42);
        }

        .stat-card.contacts .stat-icon {
            background: linear-gradient(135deg, var(--info-blue), #138496);
        }

        .stat-card.blogs .stat-icon {
            background: linear-gradient(135deg, var(--success-green), #20c997);
        }

        .stat-content {
            flex: 1;
        }

        .stat-content h3 {
            font-size: 16px;
            color: #666;
            margin-bottom: 8px;
            font-weight: 500;
        }

        .stat-number {
            font-size: 32px;
            font-weight: 700;
            color: var(--primary-purple);
            font-family: 'Poppins', sans-serif;
        }

        /* Dashboard Grid */
        .dashboard-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
        }

        @media (max-width: 1200px) {
            .dashboard-grid {
                grid-template-columns: 1fr;
            }
        }

        .section-card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        }

        .section-title {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid var(--medium-gray);
        }

        .section-title h3 {
            color: var(--primary-purple);
            font-size: 20px;
            margin: 0;
        }

        .view-all {
            color: var(--magenta-pink);
            text-decoration: none;
            font-weight: 500;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 5px;
            transition: all 0.3s ease;
        }

        .view-all:hover {
            color: var(--primary-purple);
        }

        /* Recent Activity Table */
        .activity-table {
            width: 100%;
            border-collapse: collapse;
        }

        .activity-table thead {
            background: var(--light-gray);
        }

        .activity-table th {
            padding: 12px 15px;
            text-align: left;
            color: var(--dark-gray);
            font-weight: 600;
            font-family: 'Poppins', sans-serif;
            font-size: 14px;
            border-bottom: 2px solid var(--medium-gray);
        }

        .activity-table td {
            padding: 15px;
            border-bottom: 1px solid var(--medium-gray);
            font-size: 14px;
            font-family: 'Inter', sans-serif;
            vertical-align: middle;
        }

        .activity-table tr:hover {
            background: rgba(94, 43, 151, 0.05);
        }

        .status-badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-family: 'Poppins', sans-serif;
            display: inline-block;
        }

        .status-pending { 
            background: #fff3cd;
            color: #856404;
        }

        .status-processing { 
            background: #cce5ff;
            color: #004085;
        }

        .status-matched { 
            background: #d4edda;
            color: #155724;
        }

        .status-completed { 
            background: #d1ecf1;
            color: #0c5460;
        }

        .status-draft { 
            background: #fff3cd;
            color: #856404;
        }

        .status-published { 
            background: #d4edda;
            color: #155724;
        }

        .status-archived { 
            background: #f8d7da;
            color: #721c24;
        }

        .status-unread { 
            background: var(--magenta-pink);
            color: white;
        }

        .btn-small {
            background: var(--royal-violet);
            color: white;
            padding: 6px 15px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 12px;
            font-weight: 500;
            transition: all 0.3s ease;
            display: inline-block;
        }

        .btn-small:hover {
            background: var(--primary-purple);
            transform: translateY(-2px);
        }

        /* Quick Actions */
        .quick-actions-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            margin-bottom: 25px;
        }

        .action-card {
            background: var(--light-gray);
            padding: 20px;
            border-radius: 10px;
            text-decoration: none;
            text-align: center;
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }

        .action-card:hover {
            background: white;
            border-color: var(--royal-violet);
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(94, 43, 151, 0.1);
        }

        .action-card i {
            font-size: 28px;
            color: var(--royal-violet);
            margin-bottom: 10px;
        }

        .action-card h4 {
            color: var(--dark-gray);
            font-size: 14px;
            margin: 0;
            font-weight: 500;
        }

        /* Recent Messages */
        .messages-list {
            margin-top: 25px;
            padding-top: 20px;
            border-top: 2px solid var(--medium-gray);
        }

        .message-item {
            background: var(--light-gray);
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 10px;
            transition: all 0.3s ease;
        }

        .message-item:hover {
            background: white;
            box-shadow: 0 3px 10px rgba(0,0,0,0.08);
        }

        .message-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
        }

        .message-name {
            font-weight: 600;
            color: var(--dark-gray);
            font-size: 14px;
        }

        .message-subject {
            color: #666;
            font-size: 13px;
            line-height: 1.4;
        }

        /* Recent Posts */
        .posts-list {
            margin-top: 25px;
        }

        .post-item {
            background: var(--light-gray);
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 10px;
            transition: all 0.3s ease;
        }

        .post-item:hover {
            background: white;
            box-shadow: 0 3px 10px rgba(0,0,0,0.08);
        }

        .post-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
        }

        .post-title {
            font-weight: 600;
            color: var(--dark-gray);
            font-size: 14px;
            display: -webkit-box;
            -webkit-line-clamp: 1;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .post-meta {
            color: #666;
            font-size: 12px;
            display: flex;
            align-items: center;
            gap: 10px;
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

        /* Responsive Design */
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

        @media (max-width: 992px) {
            .dashboard-grid {
                grid-template-columns: 1fr;
            }
            
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .quick-actions-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .main-content {
                padding: 20px;
            }
            
            .page-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }
            
            .header-actions {
                width: 100%;
                justify-content: flex-end;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .activity-table {
                display: block;
                overflow-x: auto;
            }
            
            .quick-actions-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 480px) {
            .stat-card {
                flex-direction: column;
                text-align: center;
                padding: 20px;
            }
            
            .stat-icon {
                width: 60px;
                height: 60px;
                font-size: 24px;
            }
            
            .section-card {
                padding: 20px 15px;
            }
            
            .action-card {
                padding: 15px;
            }
            
            .message-item, .post-item {
                padding: 12px;
            }
        }

        /* Welcome Section */
        .welcome-section {
            background: linear-gradient(135deg, var(--royal-violet), var(--primary-purple));
            color: white;
            padding: 25px;
            border-radius: 12px;
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 5px 20px rgba(94, 43, 151, 0.2);
        }

        .welcome-content h2 {
            color: white;
            font-size: 24px;
            margin-bottom: 10px;
        }

        .welcome-content p {
            opacity: 0.9;
            font-size: 14px;
        }

        .welcome-icon {
            font-size: 60px;
            opacity: 0.2;
        }

        /* Admin Info */
        .admin-info-section {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .admin-avatar-large {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--magenta-pink), var(--warm-orange));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 18px;
        }

        .admin-details h4 {
            color: white;
            margin-bottom: 5px;
            font-size: 16px;
        }

        .admin-details p {
            opacity: 0.8;
            font-size: 13px;
        }

        .logout-btn {
            background: rgba(255,255,255,0.2);
            color: white;
            padding: 8px 20px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .logout-btn:hover {
            background: rgba(255,255,255,0.3);
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <!-- Mobile Menu Toggle -->
    <button class="menu-toggle" onclick="toggleSidebar()">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Sidebar - Updated with Manage Blogs -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <h2>Home Castle Tutor</h2>
            <p>Admin Dashboard</p>
        </div>
        
        <div class="sidebar-menu">
            <?php
            // Get current page for active state
            $current_page = basename($_SERVER['PHP_SELF']);
            ?>
            
            <a href="dashboard.php" class="menu-item <?php echo $current_page == 'dashboard.php' ? 'active' : ''; ?>">
                <i class="fas fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
            <a href="student-requests.php" class="menu-item <?php echo $current_page == 'student-requests.php' ? 'active' : ''; ?>">
                <i class="fas fa-user-graduate"></i>
                <span>Student Requests</span>
            </a>
            <a href="manage-tutors.php" class="menu-item <?php echo $current_page == 'manage-tutors.php' ? 'active' : ''; ?>">
                <i class="fas fa-chalkboard-teacher"></i>
                <span>Manage Tutors</span>
            </a>
            <a href="manage-blogs.php" class="menu-item <?php echo $current_page == 'manage-blogs.php' ? 'active' : ''; ?>">
                <i class="fas fa-blog"></i>
                <span>Manage Blogs</span>
            </a>
            <a href="contact-messages.php" class="menu-item <?php echo $current_page == 'contact-messages.php' ? 'active' : ''; ?>">
                <i class="fas fa-envelope"></i>
                <span>Contact Messages</span>
                <?php if($unread_contacts > 0): ?>
                    <span class="badge"><?php echo $unread_contacts; ?></span>
                <?php endif; ?>
            </a>
            <a href="update-content.php" class="menu-item <?php echo $current_page == 'update-content.php' ? 'active' : ''; ?>">
                <i class="fas fa-edit"></i>
                <span>Update Content</span>
            </a>
            <a href="update-banners.php" class="menu-item <?php echo $current_page == 'update-banners.php' ? 'active' : ''; ?>">
                <i class="fas fa-images"></i>
                <span>Update Banners</span>
            </a>
            <a href="reports.php" class="menu-item <?php echo $current_page == 'reports.php' ? 'active' : ''; ?>">
                <i class="fas fa-chart-bar"></i>
                <span>Reports</span>
            </a>
            <a href="settings.php" class="menu-item <?php echo $current_page == 'settings.php' ? 'active' : ''; ?>">
                <i class="fas fa-cog"></i>
                <span>Settings</span>
            </a>
        </div>
        
        <div class="sidebar-footer">
            <div class="admin-profile">
                <div class="admin-avatar">
                    <?php 
                    $admin_name = $_SESSION['admin_username'] ?? 'Admin';
                    echo strtoupper(substr($admin_name, 0, 1)); 
                    ?>
                </div>
                <div class="admin-info">
                    <h4><?php echo htmlspecialchars($admin_name); ?></h4>
                    <p>Administrator</p>
                </div>
                <a href="logout.php" style="margin-left: auto; color: white; opacity: 0.8;" title="Logout">
                    <i class="fas fa-sign-out-alt"></i>
                </a>
            </div>
        </div>
    </div>
    
    <!-- Main Content -->
    <div class="main-content">
        <!-- Welcome Section -->
        <div class="welcome-section">
            <div class="welcome-content">
                <h2>Welcome back, <?php echo htmlspecialchars($_SESSION['admin_username']); ?>!</h2>
                <p>Here's what's happening with your tutoring platform today.</p>
            </div>
            <div class="admin-info-section">
                <div class="admin-avatar-large">
                    <?php echo strtoupper(substr($_SESSION['admin_username'], 0, 1)); ?>
                </div>
                <div class="admin-details">
                    <h4><?php echo htmlspecialchars($_SESSION['admin_username']); ?></h4>
                    <p>Administrator</p>
                </div>
                <a href="logout.php" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </div>
        
        <!-- Statistics Cards -->
        <div class="stats-grid">
            <div class="stat-card students">
                <div class="stat-icon">
                    <i class="fas fa-user-graduate"></i>
                </div>
                <div class="stat-content">
                    <h3>Total Students</h3>
                    <div class="stat-number"><?php echo $total_students; ?></div>
                </div>
            </div>
            
            <div class="stat-card tutors">
                <div class="stat-icon">
                    <i class="fas fa-chalkboard-teacher"></i>
                </div>
                <div class="stat-content">
                    <h3>Total Tutors</h3>
                    <div class="stat-number"><?php echo $total_tutors; ?></div>
                </div>
            </div>
            
            <div class="stat-card pending">
                <div class="stat-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-content">
                    <h3>Pending Requests</h3>
                    <div class="stat-number"><?php echo $pending_requests; ?></div>
                </div>
            </div>
            
            <div class="stat-card contacts">
                <div class="stat-icon">
                    <i class="fas fa-envelope"></i>
                </div>
                <div class="stat-content">
                    <h3>Unread Messages</h3>
                    <div class="stat-number"><?php echo $unread_contacts; ?></div>
                </div>
            </div>
            
            <div class="stat-card blogs">
                <div class="stat-icon">
                    <i class="fas fa-blog"></i>
                </div>
                <div class="stat-content">
                    <h3>Blog Posts</h3>
                    <div class="stat-number"><?php echo $total_posts; ?> <small style="font-size: 14px; color: #666;">(<?php echo $published_posts; ?> published)</small></div>
                </div>
            </div>
        </div>
        
        <!-- Dashboard Grid -->
        <div class="dashboard-grid">
            <!-- Recent Activity -->
            <div class="section-card">
                <div class="section-title">
                    <h3><i class="fas fa-history"></i> Recent Student Requests</h3>
                    <a href="student-requests.php" class="view-all">
                        View All <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
                
                <table class="activity-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Grade</th>
                            <th>Subjects</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = mysqli_fetch_assoc($recent_students)): ?>
                        <tr>
                            <td><strong><?php echo htmlspecialchars($row['student_name']); ?></strong></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td><?php echo htmlspecialchars($row['grade_level']); ?></td>
                            <td title="<?php echo htmlspecialchars($row['subjects']); ?>">
                                <?php echo htmlspecialchars(substr($row['subjects'], 0, 20)); ?>
                                <?php if(strlen($row['subjects']) > 20): ?>...<?php endif; ?>
                            </td>
                            <td>
                                <span class="status-badge status-<?php echo $row['status']; ?>">
                                    <?php echo ucfirst($row['status']); ?>
                                </span>
                            </td>
                            <td>
                                <a href="student-requests.php?view=<?php echo $row['id']; ?>" class="btn-small">
                                    <i class="fas fa-eye"></i> View
                                </a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                        <?php if(mysqli_num_rows($recent_students) == 0): ?>
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 30px; color: #666;">
                                <i class="fas fa-user-graduate" style="font-size: 40px; margin-bottom: 10px; opacity: 0.3;"></i>
                                <p>No recent student requests</p>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <!-- Quick Actions & Recent Content -->
            <div class="section-card">
                <div class="section-title">
                    <h3><i class="fas fa-bolt"></i> Quick Actions</h3>
                </div>
                
                <div class="quick-actions-grid">
                    <a href="manage-tutors.php?action=add" class="action-card">
                        <i class="fas fa-user-plus"></i>
                        <h4>Add New Tutor</h4>
                    </a>
                    
                    <a href="manage-blogs.php" class="action-card">
                        <i class="fas fa-blog"></i>
                        <h4>Manage Blogs</h4>
                    </a>
                    
                    <a href="contact-messages.php" class="action-card">
                        <i class="fas fa-envelope"></i>
                        <h4>View Messages</h4>
                    </a>
                    
                    <a href="reports.php" class="action-card">
                        <i class="fas fa-chart-bar"></i>
                        <h4>View Reports</h4>
                    </a>
                </div>
                
                <!-- Recent Blog Posts -->
                <div class="posts-list">
                    <div class="section-title" style="border: none; padding: 0; margin: 20px 0 15px 0;">
                        <h4 style="font-size: 16px; color: var(--primary-purple); margin: 0;">
                            <i class="fas fa-newspaper"></i> Recent Blog Posts
                        </h4>
                        <a href="manage-blogs.php" style="font-size: 12px;">View All</a>
                    </div>
                    
                    <?php while($post = mysqli_fetch_assoc($recent_posts)): ?>
                    <div class="post-item">
                        <div class="post-header">
                            <div class="post-title" title="<?php echo htmlspecialchars($post['title']); ?>">
                                <?php echo htmlspecialchars(substr($post['title'], 0, 40)); ?>
                                <?php if(strlen($post['title']) > 40): ?>...<?php endif; ?>
                            </div>
                            <span class="status-badge status-<?php echo $post['status']; ?>">
                                <?php echo ucfirst($post['status']); ?>
                            </span>
                        </div>
                        <div class="post-meta">
                            <span><i class="fas fa-calendar"></i> <?php echo date('M d', strtotime($post['created_at'])); ?></span>
                            <span><i class="fas fa-folder"></i> <?php echo htmlspecialchars($post['category']); ?></span>
                        </div>
                    </div>
                    <?php endwhile; ?>
                    <?php if(mysqli_num_rows($recent_posts) == 0): ?>
                    <div style="text-align: center; padding: 20px; color: #666;">
                        <i class="fas fa-newspaper" style="font-size: 30px; margin-bottom: 10px; opacity: 0.3;"></i>
                        <p style="font-size: 14px;">No blog posts yet</p>
                        <a href="manage-blogs.php" class="btn-small" style="margin-top: 10px; display: inline-block;">
                            <i class="fas fa-plus"></i> Create First Post
                        </a>
                    </div>
                    <?php endif; ?>
                </div>
                
                <!-- Recent Messages -->
                <div class="messages-list">
                    <div class="section-title" style="border: none; padding: 0; margin: 20px 0 15px 0;">
                        <h4 style="font-size: 16px; color: var(--primary-purple); margin: 0;">
                            <i class="fas fa-comments"></i> Recent Messages
                        </h4>
                        <a href="contact-messages.php" style="font-size: 12px;">View All</a>
                    </div>
                    
                    <?php mysqli_data_seek($recent_contacts, 0); // Reset pointer ?>
                    <?php while($contact = mysqli_fetch_assoc($recent_contacts)): ?>
                    <div class="message-item">
                        <div class="message-header">
                            <div class="message-name"><?php echo htmlspecialchars($contact['name']); ?></div>
                            <span class="status-badge status-unread">New</span>
                        </div>
                        <div class="message-subject">
                            <?php echo htmlspecialchars($contact['subject']); ?>
                            <br>
                            <small style="color: #999;"><?php echo date('M d, h:i A', strtotime($contact['created_at'])); ?></small>
                        </div>
                    </div>
                    <?php endwhile; ?>
                    <?php if(mysqli_num_rows($recent_contacts) == 0): ?>
                    <div style="text-align: center; padding: 20px; color: #666;">
                        <i class="fas fa-envelope" style="font-size: 30px; margin-bottom: 10px; opacity: 0.3;"></i>
                        <p style="font-size: 14px;">No recent messages</p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // Sidebar Toggle for Mobile
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('active');
        }
        
        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const menuToggle = document.querySelector('.menu-toggle');
            if (window.innerWidth <= 1200 && 
                !sidebar.contains(event.target) && 
                !menuToggle.contains(event.target) && 
                sidebar.classList.contains('active')) {
                sidebar.classList.remove('active');
            }
        });
        
        // Auto refresh dashboard every 60 seconds
        setTimeout(function() {
            location.reload();
        }, 60000);
        
        // Add animation to stat cards on load
        document.addEventListener('DOMContentLoaded', function() {
            const statCards = document.querySelectorAll('.stat-card');
            statCards.forEach((card, index) => {
                card.style.animationDelay = `${index * 0.1}s`;
                card.style.animation = 'fadeInUp 0.5s ease forwards';
            });
        });
    </script>
</body>
</html>