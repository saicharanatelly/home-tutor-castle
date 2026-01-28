<?php
// sidebar-navbar.php - Combined sidebar and navbar for admin panel
// Make sure to call session_start() before including this file

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Castle Tutor Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-purple: #3B0A6A;
            --royal-violet: #5E2B97;
            --magenta-pink: #C13C91;
            --warm-orange: #F6A04D;
            --light-gray: #F8F9FA;
            --medium-gray: #E9ECEF;
            --dark-gray: #343A40;
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
            background: linear-gradient(135deg, #f8f4ff 0%, #fff 100%);
            color: var(--dark-gray);
            min-height: 100vh;
        }
        
        .admin-wrapper {
            display: flex;
            min-height: 100vh;
        }
        
        /* Sidebar Styles */
        .sidebar {
            width: 260px;
            background: linear-gradient(180deg, var(--primary-purple) 0%, var(--royal-violet) 100%);
            color: white;
            padding: 1.5rem 0;
            position: fixed;
            height: 100vh;
            left: 0;
            top: 0;
            z-index: 1000;
            overflow-y: auto;
            transition: var(--transition);
            box-shadow: 5px 0 20px rgba(0, 0, 0, 0.1);
        }
        
        .sidebar .logo {
            padding: 0 1.5rem 2rem;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            margin-bottom: 1rem;
        }
        
        .sidebar .logo h2 {
            font-family: 'Poppins', sans-serif;
            font-size: 1.4rem;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            color: white;
        }
        
        .sidebar .logo p {
            font-size: 0.9rem;
            opacity: 0.8;
            font-weight: 300;
        }
        
        .sidebar .nav-links {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            padding: 0 1rem;
        }
        
        .sidebar .nav-links a {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem 1.25rem;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            border-radius: var(--radius);
            transition: var(--transition);
            position: relative;
            font-family: 'Poppins', sans-serif;
            font-size: 0.95rem;
        }
        
        .sidebar .nav-links a i {
            width: 24px;
            text-align: center;
            font-size: 1.1rem;
        }
        
        .sidebar .nav-links a span {
            flex: 1;
        }
        
        .sidebar .nav-links a:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            transform: translateX(5px);
        }
        
        .sidebar .nav-links a.active {
            background: linear-gradient(90deg, var(--magenta-pink), var(--warm-orange));
            color: white;
            box-shadow: 0 5px 15px rgba(193, 60, 145, 0.3);
        }
        
        .sidebar .nav-links a.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            background: white;
            border-radius: 0 4px 4px 0;
        }
        
        .sidebar .badge {
            background: var(--warm-orange);
            color: white;
            padding: 0.25rem 0.5rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            min-width: 24px;
            text-align: center;
        }
        
        /* Main Content Area */
        .main-content {
            flex: 1;
            margin-left: 260px;
            transition: var(--transition);
        }
        
        /* Navbar/Header Styles */
        .navbar {
            background: white;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: var(--shadow);
            position: sticky;
            top: 0;
            z-index: 999;
        }
        
        .navbar-left h1 {
            font-family: 'Poppins', sans-serif;
            font-size: 1.5rem;
            color: var(--primary-purple);
            margin: 0;
        }
        
        .user-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .user-avatar {
            width: 45px;
            height: 45px;
            background: linear-gradient(135deg, var(--royal-violet), var(--magenta-pink));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.2rem;
        }
        
        .user-details {
            display: flex;
            flex-direction: column;
        }
        
        .user-details strong {
            font-family: 'Poppins', sans-serif;
            color: var(--dark-gray);
        }
        
        .user-details p {
            font-size: 0.85rem;
            color: #666;
        }
        
        .logout-btn {
            background: linear-gradient(135deg, var(--royal-violet), var(--magenta-pink));
            color: white;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: var(--radius);
            cursor: pointer;
            font-family: 'Poppins', sans-serif;
            font-weight: 500;
            text-decoration: none;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.9rem;
        }
        
        .logout-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(94, 43, 151, 0.3);
        }
        
        /* Content Container */
        .content-container {
            padding: 2rem;
            min-height: calc(100vh - 80px);
        }
        
        /* Mobile Toggle Button */
        .menu-toggle {
            display: none;
            background: none;
            border: none;
            color: var(--primary-purple);
            font-size: 1.5rem;
            cursor: pointer;
            padding: 0.5rem;
        }
        
        /* Responsive Design */
        @media (max-width: 992px) {
            .sidebar {
                width: 240px;
                transform: translateX(-100%);
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
            
            .navbar {
                padding: 1rem;
            }
            
            .content-container {
                padding: 1.5rem;
            }
        }
        
        @media (max-width: 768px) {
            .user-info {
                flex-wrap: wrap;
            }
            
            .logout-btn {
                padding: 0.5rem 1rem;
                font-size: 0.85rem;
            }
            
            .user-details {
                display: none;
            }
        }
        
        @media (max-width: 576px) {
            .content-container {
                padding: 1rem;
            }
            
            .logout-btn span {
                display: none;
            }
            
            .logout-btn {
                padding: 0.5rem;
                border-radius: 50%;
                width: 40px;
                height: 40px;
                justify-content: center;
            }
            
            .navbar-left h1 {
                font-size: 1.2rem;
            }
        }
        
        /* Mobile Menu Overlay */
        .mobile-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
            backdrop-filter: blur(3px);
        }
        
        .mobile-overlay.active {
            display: block;
        }
    </style>
</head>
<body>
    <div class="admin-wrapper">
        <!-- Mobile Overlay -->
        <div class="mobile-overlay" id="mobileOverlay" onclick="toggleSidebar()"></div>
        
        <!-- Sidebar -->
        <div class="sidebar" id="sidebar">
            <div class="logo">
                <h2><i class="fas fa-graduation-cap"></i> Home Castle Tutor</h2>
                <p>Admin Panel</p>
            </div>
            
            <div class="nav-links">
                <?php
                // Get current page for active state
                $current_page = basename($_SERVER['PHP_SELF']);
                ?>
                
                <a href="dashboard.php" class="<?php echo $current_page == 'dashboard.php' ? 'active' : ''; ?>">
                    <i class="fas fa-tachometer-alt"></i> <span>Dashboard</span>
                </a>
                <a href="student-requests.php" class="<?php echo $current_page == 'student-requests.php' ? 'active' : ''; ?>">
                    <i class="fas fa-user-graduate"></i> <span>Student Requests</span>
                </a>
                <a href="manage-tutors.php" class="<?php echo $current_page == 'manage-tutors.php' ? 'active' : ''; ?>">
                    <i class="fas fa-chalkboard-teacher"></i> <span>Manage Tutors</span>
                </a>
                <a href="contact-messages.php" class="<?php echo $current_page == 'contact-messages.php' ? 'active' : ''; ?>">
                    <i class="fas fa-envelope"></i> <span>Contact Messages</span>
                    <?php 
                    // You can add your unread count logic here
                    // Example: if($unread_contacts > 0):
                    // <span class="badge"><?php echo $unread_contacts; ?></span>
                    // endif;
                    ?>
                </a>
                <a href="update-content.php" class="<?php echo $current_page == 'update-content.php' ? 'active' : ''; ?>">
                    <i class="fas fa-edit"></i> <span>Update Content</span>
                </a>
                <a href="update-banners.php" class="<?php echo $current_page == 'update-banners.php' ? 'active' : ''; ?>">
                    <i class="fas fa-images"></i> <span>Update Banners</span>
                </a>
                <a href="reports.php" class="<?php echo $current_page == 'reports.php' ? 'active' : ''; ?>">
                    <i class="fas fa-chart-bar"></i> <span>Reports</span>
                </a>
                <a href="settings.php" class="<?php echo $current_page == 'settings.php' ? 'active' : ''; ?>">
                    <i class="fas fa-cog"></i> <span>Settings</span>
                </a>
            </div>
        </div>
        
        <!-- Main Content Area -->
        <div class="main-content">
            <!-- Navbar -->
            <div class="navbar">
                <div class="navbar-left">
                    <button class="menu-toggle" onclick="toggleSidebar()">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h1>
                        <?php 
                        // Page titles mapping
                        $page_titles = [
                            'dashboard.php' => 'Dashboard Overview',
                            'student-requests.php' => 'Student Requests',
                            'manage-tutors.php' => 'Manage Tutors',
                            'contact-messages.php' => 'Contact Messages',
                            'update-content.php' => 'Update Content',
                            'update-banners.php' => 'Update Banners',
                            'reports.php' => 'Reports & Analytics',
                            'settings.php' => 'Settings'
                        ];
                        echo $page_titles[$current_page] ?? 'Admin Panel';
                        ?>
                    </h1>
                </div>
                
                <div class="user-info">
                    <div class="user-avatar">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="user-details">
                        <strong>Welcome, <?php echo isset($_SESSION['admin_username']) ? htmlspecialchars($_SESSION['admin_username']) : 'Admin'; ?></strong>
                        <p>Administrator</p>
                    </div>
                    <a href="logout.php" class="logout-btn">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Logout</span>
                    </a>
                </div>
            </div>
            
            <!-- Content Container -->
            <div class="content-container">
                <!-- Page content will be inserted here -->
                <!-- DO NOT CLOSE the body/html tags here - they will be closed in the individual page files -->
                
                <!-- Example: In your student-requests.php, you would start with: -->
                <!-- <?php include 'includes/sidebar-navbar.php'; ?> -->
                <!-- Then your page content -->
                <!-- Then close with: -->
                <!-- </div></div></body></html> -->