<?php
// student-requests.php - Admin Student Requests Management
session_start();

// Check if session is already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Database configuration
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'home_castle_tutor';

// Create connection
$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Set timezone
date_default_timezone_set('Asia/Kolkata');

// Check admin authentication
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit();
}

// Handle status update
$success = $error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_status'])) {
    $id = mysqli_real_escape_string($conn, $_POST['request_id'] ?? '');
    $status = mysqli_real_escape_string($conn, $_POST['status'] ?? '');
    $notes = mysqli_real_escape_string($conn, $_POST['admin_notes'] ?? '');
    
    if (!empty($id) && !empty($status)) {
        $sql = "UPDATE student_requirements SET status = '$status', admin_notes = '$notes', updated_at = NOW() WHERE id = '$id'";
        if (mysqli_query($conn, $sql)) {
            $_SESSION['success'] = "Status updated successfully!";
            header('Location: student-requests.php');
            exit();
        } else {
            $error = "Error updating status: " . mysqli_error($conn);
        }
    } else {
        $error = "Invalid request data!";
    }
}

// Handle delete
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $id = mysqli_real_escape_string($conn, $_GET['delete']);
    $sql = "DELETE FROM student_requirements WHERE id = '$id'";
    if (mysqli_query($conn, $sql)) {
        $_SESSION['success'] = "Request deleted successfully!";
        header('Location: student-requests.php');
        exit();
    } else {
        $error = "Error deleting request: " . mysqli_error($conn);
    }
}

// Filter handling
$filter = isset($_GET['filter']) && in_array($_GET['filter'], ['all', 'pending', 'processing', 'matched', 'completed', 'cancelled']) 
    ? $_GET['filter'] 
    : 'all';
    
$search = isset($_GET['search']) ? trim(mysqli_real_escape_string($conn, $_GET['search'])) : '';

// Build query
$query_conditions = "1=1";

if ($filter != 'all') {
    $query_conditions .= " AND status = '$filter'";
}

if (!empty($search)) {
    $search_term = "%$search%";
    $query_conditions .= " AND (student_name LIKE '$search_term' OR email LIKE '$search_term' OR subjects LIKE '$search_term')";
}

// Get total count for pagination
$count_query = "SELECT COUNT(*) as total FROM student_requirements WHERE $query_conditions";
$count_result = mysqli_query($conn, $count_query);
$total_row = mysqli_fetch_assoc($count_result);
$total = $total_row['total'];

// Pagination
$per_page = 10;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$offset = ($page - 1) * $per_page;
$total_pages = ceil($total / $per_page);

// Main query with pagination
$query = "SELECT * FROM student_requirements WHERE $query_conditions ORDER BY created_at DESC LIMIT $per_page OFFSET $offset";
$result = mysqli_query($conn, $query);

// Get statistics
$stats_query = "SELECT 
    COUNT(*) as total,
    SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
    SUM(CASE WHEN status = 'processing' THEN 1 ELSE 0 END) as processing,
    SUM(CASE WHEN status = 'matched' THEN 1 ELSE 0 END) as matched,
    SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed,
    SUM(CASE WHEN status = 'cancelled' THEN 1 ELSE 0 END) as cancelled
    FROM student_requirements";
$stats_result = mysqli_query($conn, $stats_query);
$stats = mysqli_fetch_assoc($stats_result);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Requests - Home Castle Tutor Admin</title>
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

        /* Sidebar Styles - Matches manage-tutors.php exactly */
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

        /* Alerts */
        .alert {
            padding: 16px 20px;
            border-radius: 8px;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 12px;
            animation: slideIn 0.3s ease;
            border-left: 4px solid transparent;
        }

        @keyframes slideIn {
            from {
                transform: translateY(-10px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border-left-color: var(--success-green);
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border-left-color: var(--danger-red);
        }

        /* Statistics Cards */
        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            text-align: center;
            border-top: 4px solid var(--royal-violet);
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-number {
            font-size: 32px;
            font-weight: 700;
            color: var(--primary-purple);
            margin-bottom: 8px;
            font-family: 'Poppins', sans-serif;
        }

        .stat-label {
            color: #666;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 500;
        }

        .stat-icon {
            font-size: 28px;
            color: var(--royal-violet);
            margin-bottom: 15px;
        }

        /* Filters */
        .filters-section {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            margin-bottom: 30px;
        }

        .filters-container {
            display: flex;
            gap: 20px;
            align-items: center;
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

        .filter-btn:hover {
            background: #dee2e6;
        }

        .filter-btn.active {
            background: var(--primary-purple);
            color: white;
            border-color: var(--primary-purple);
        }

        .search-box {
            flex: 1;
            max-width: 400px;
        }

        .search-input {
            width: 100%;
            padding: 12px 20px;
            border: 2px solid var(--medium-gray);
            border-radius: 8px;
            font-size: 14px;
            font-family: 'Inter', sans-serif;
            transition: all 0.3s ease;
            background: white;
        }

        .search-input:focus {
            outline: none;
            border-color: var(--royal-violet);
            box-shadow: 0 0 0 3px rgba(94, 43, 151, 0.1);
        }

        /* Table Container */
        .table-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            margin-bottom: 30px;
            width: 100%;
            overflow-x: auto;
        }

        .requests-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 1000px;
        }

        .requests-table thead {
            background: linear-gradient(135deg, var(--royal-violet), var(--primary-purple));
        }

        .requests-table th {
            padding: 15px;
            text-align: left;
            color: white;
            font-weight: 600;
            font-family: 'Poppins', sans-serif;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border: none;
        }

        .requests-table td {
            padding: 15px;
            border-bottom: 1px solid var(--medium-gray);
            font-size: 14px;
            font-family: 'Inter', 'Roboto', sans-serif;
            vertical-align: middle;
        }

        .requests-table tr:hover {
            background: rgba(94, 43, 151, 0.05);
        }

        /* Status Badges - Matching manage-tutors.php style */
        .status-badge {
            padding: 6px 15px;
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

        .status-cancelled { 
            background: #f8d7da;
            color: #721c24;
        }

        .action-buttons {
            display: flex;
            gap: 8px;
        }

        .btn-action {
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

        .btn-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }

        .btn-view { 
            background: var(--info-blue);
        }

        .btn-edit { 
            background: var(--warning-yellow);
        }

        .btn-delete { 
            background: var(--danger-red);
        }

        /* Pagination */
        .pagination {
            display: flex;
            justify-content: center;
            gap: 8px;
            flex-wrap: wrap;
            margin-top: 30px;
        }

        .page-link {
            padding: 10px 15px;
            border: 2px solid var(--medium-gray);
            background: white;
            border-radius: 8px;
            text-decoration: none;
            color: var(--dark-gray);
            transition: all 0.3s ease;
            font-family: 'Poppins', sans-serif;
            font-weight: 500;
            min-width: 40px;
            text-align: center;
            font-size: 14px;
        }

        .page-link:hover {
            border-color: var(--royal-violet);
            color: var(--royal-violet);
        }

        .page-link.active {
            background: linear-gradient(135deg, var(--royal-violet), var(--magenta-pink));
            color: white;
            border-color: transparent;
        }

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
            max-width: 550px;
            max-height: 85vh;
            overflow-y: auto;
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
            animation: modalSlideIn 0.4s ease;
            border: 1px solid var(--medium-gray);
        }

        @keyframes modalSlideIn {
            from {
                transform: translateY(-30px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .modal-header {
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid var(--medium-gray);
        }

        .modal-header h3 {
            color: var(--primary-purple);
            font-size: 22px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .detail-row {
            display: flex;
            margin-bottom: 12px;
            padding-bottom: 12px;
            border-bottom: 1px solid var(--medium-gray);
        }

        .detail-label {
            font-weight: 600;
            min-width: 150px;
            color: var(--dark-gray);
            font-family: 'Inter', sans-serif;
            font-size: 14px;
        }

        .detail-value {
            flex: 1;
            color: #555;
            font-size: 14px;
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

        .modal-footer {
            display: flex;
            justify-content: flex-end;
            gap: 15px;
            padding-top: 20px;
            border-top: 2px solid var(--medium-gray);
        }

        .btn-update {
            background: linear-gradient(135deg, var(--magenta-pink), var(--royal-violet));
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-family: 'Poppins', sans-serif;
            font-weight: 500;
            transition: all 0.3s ease;
            font-size: 14px;
        }

        .btn-update:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(195, 60, 145, 0.35);
        }

        .btn-cancel {
            background: #6c757d;
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-family: 'Poppins', sans-serif;
            font-weight: 500;
            transition: all 0.3s ease;
            font-size: 14px;
        }

        .btn-cancel:hover {
            background: #5a6268;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 60px 40px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            color: #666;
            grid-column: 1 / -1;
        }

        .empty-state i {
            font-size: 60px;
            color: var(--medium-gray);
            margin-bottom: 20px;
            opacity: 0.7;
        }

        .empty-state h3 {
            font-size: 22px;
            margin-bottom: 10px;
            color: var(--primary-purple);
        }

        .empty-state p {
            font-size: 16px;
            margin-bottom: 20px;
            color: #666;
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
            .stats-container {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .filters-container {
                flex-direction: column;
                align-items: stretch;
            }
            
            .search-box {
                max-width: 100%;
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
            
            .modal-content {
                padding: 25px 20px;
            }
            
            .stats-container {
                grid-template-columns: 1fr;
            }
            
            .detail-row {
                flex-direction: column;
                gap: 5px;
            }
            
            .detail-label {
                min-width: auto;
            }
        }

        @media (max-width: 480px) {
            .action-buttons {
                flex-wrap: wrap;
                justify-content: center;
            }
            
            .btn-action {
                width: 32px;
                height: 32px;
                font-size: 12px;
            }
            
            .modal-footer {
                flex-direction: column;
            }
            
            .modal-footer button {
                width: 100%;
            }
            
            .pagination {
                gap: 5px;
            }
            
            .page-link {
                padding: 8px 12px;
                min-width: 36px;
            }
        }

        /* No Results */
        .no-results {
            text-align: center;
            padding: 60px 40px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            color: #666;
            margin: 20px 0;
        }

        .no-results i {
            font-size: 60px;
            color: var(--medium-gray);
            margin-bottom: 20px;
            opacity: 0.7;
        }

        .no-results h3 {
            font-size: 22px;
            margin-bottom: 10px;
            color: var(--primary-purple);
        }

        .no-results p {
            font-size: 16px;
            margin-bottom: 20px;
            color: #666;
        }
    </style>
</head>
<body>
    <!-- Mobile Menu Toggle -->
    <button class="menu-toggle" onclick="toggleSidebar()">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Sidebar - Exact match with manage-tutors.php -->
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
                <i class="fas fa-home"></i>
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
            <a href="contact-messages.php" class="menu-item <?php echo $current_page == 'contact-messages.php' ? 'active' : ''; ?>">
                <i class="fas fa-envelope"></i>
                <span>Contact Messages</span>
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
        <div class="page-header">
            <h1><i class="fas fa-user-graduate" style="color: var(--royal-violet); margin-right: 12px;"></i> Student Requests</h1>
            <div class="header-actions">
                <button class="btn-primary" onclick="exportData()">
                    <i class="fas fa-download"></i> Export CSV
                </button>
            </div>
        </div>
        
        <!-- Success/Error Messages -->
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
        
        <!-- Statistics Cards -->
        <div class="stats-container">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-file-alt"></i>
                </div>
                <div class="stat-number"><?php echo $stats['total'] ?? 0; ?></div>
                <div class="stat-label">Total Requests</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-number"><?php echo $stats['pending'] ?? 0; ?></div>
                <div class="stat-label">Pending</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-spinner"></i>
                </div>
                <div class="stat-number"><?php echo $stats['processing'] ?? 0; ?></div>
                <div class="stat-label">Processing</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-handshake"></i>
                </div>
                <div class="stat-number"><?php echo $stats['matched'] ?? 0; ?></div>
                <div class="stat-label">Matched</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-number"><?php echo $stats['completed'] ?? 0; ?></div>
                <div class="stat-label">Completed</div>
            </div>
        </div>
        
        <!-- Filters Section -->
        <div class="filters-section">
            <div class="filters-container">
                <div class="filter-buttons">
                    <a href="?filter=all" class="filter-btn <?php echo $filter == 'all' ? 'active' : ''; ?>">
                        All Requests
                    </a>
                    <a href="?filter=pending" class="filter-btn <?php echo $filter == 'pending' ? 'active' : ''; ?>">
                        Pending
                    </a>
                    <a href="?filter=processing" class="filter-btn <?php echo $filter == 'processing' ? 'active' : ''; ?>">
                        Processing
                    </a>
                    <a href="?filter=matched" class="filter-btn <?php echo $filter == 'matched' ? 'active' : ''; ?>">
                        Matched
                    </a>
                    <a href="?filter=completed" class="filter-btn <?php echo $filter == 'completed' ? 'active' : ''; ?>">
                        Completed
                    </a>
                    <a href="?filter=cancelled" class="filter-btn <?php echo $filter == 'cancelled' ? 'active' : ''; ?>">
                        Cancelled
                    </a>
                </div>
                
                <div class="search-box">
                    <form method="GET">
                        <input type="text" 
                               name="search" 
                               value="<?php echo htmlspecialchars($search); ?>" 
                               placeholder="Search by name, email, or subjects..." 
                               class="search-input">
                        <input type="hidden" name="filter" value="<?php echo $filter; ?>">
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Table -->
        <div class="table-container">
            <?php if(mysqli_num_rows($result) > 0): ?>
                <table class="requests-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Student Name</th>
                            <th>Contact</th>
                            <th>Grade</th>
                            <th>Subjects</th>
                            <th>Location</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><strong>#<?php echo str_pad($row['id'], 5, '0', STR_PAD_LEFT); ?></strong></td>
                            <td>
                                <strong><?php echo htmlspecialchars($row['student_name']); ?></strong>
                                <?php if(!empty($row['parent_name'])): ?>
                                    <br><small>Parent: <?php echo htmlspecialchars($row['parent_name']); ?></small>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div><i class="fas fa-envelope"></i> <?php echo htmlspecialchars($row['email']); ?></div>
                                <small><i class="fas fa-phone"></i> <?php echo htmlspecialchars($row['phone']); ?></small>
                            </td>
                            <td><?php echo htmlspecialchars($row['grade_level']); ?></td>
                            <td>
                                <div title="<?php echo htmlspecialchars($row['subjects']); ?>">
                                    <?php echo htmlspecialchars(substr($row['subjects'], 0, 30)); ?>
                                    <?php if(strlen($row['subjects']) > 30): ?>...<?php endif; ?>
                                </div>
                            </td>
                            <td>
                                <i class="fas fa-map-marker-alt"></i> 
                                <?php echo htmlspecialchars($row['location']); ?>
                            </td>
                            <td>
                                <span class="status-badge status-<?php echo $row['status']; ?>">
                                    <?php echo ucfirst($row['status']); ?>
                                </span>
                            </td>
                            <td>
                                <?php echo date('d M Y', strtotime($row['created_at'])); ?><br>
                                <small><?php echo date('h:i A', strtotime($row['created_at'])); ?></small>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn-action btn-view" onclick="showRequestDetails(<?php echo $row['id']; ?>)" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn-action btn-edit" onclick="showEditForm(<?php echo $row['id']; ?>)" title="Edit Status">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <a href="?delete=<?php echo $row['id']; ?>&filter=<?php echo $filter; ?>&search=<?php echo urlencode($search); ?>&page=<?php echo $page; ?>"
                                       class="btn-action btn-delete"
                                       onclick="return confirm('Are you sure you want to delete request #<?php echo $row['id']; ?>?')"
                                       title="Delete Request">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="no-results">
                    <i class="fas fa-user-graduate"></i>
                    <h3>No Student Requests Found</h3>
                    <p><?php echo empty($search) ? 'No student requests in the database yet.' : 'No requests match your search criteria.'; ?></p>
                    <?php if(!empty($search)): ?>
                        <a href="?filter=all" class="btn-primary" style="margin-top: 20px; display: inline-block;">
                            <i class="fas fa-times"></i> Clear Search
                        </a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Pagination -->
        <?php if($total_pages > 1): ?>
        <div class="pagination">
            <?php if($page > 1): ?>
                <a href="?page=1&filter=<?php echo $filter; ?>&search=<?php echo urlencode($search); ?>" class="page-link" title="First Page">
                    <i class="fas fa-angle-double-left"></i>
                </a>
                <a href="?page=<?php echo $page - 1; ?>&filter=<?php echo $filter; ?>&search=<?php echo urlencode($search); ?>" class="page-link" title="Previous Page">
                    <i class="fas fa-angle-left"></i>
                </a>
            <?php endif; ?>
            
            <?php
            $start = max(1, $page - 2);
            $end = min($total_pages, $page + 2);
            
            for($i = $start; $i <= $end; $i++): ?>
                <a href="?page=<?php echo $i; ?>&filter=<?php echo $filter; ?>&search=<?php echo urlencode($search); ?>" 
                   class="page-link <?php echo $i == $page ? 'active' : ''; ?>"
                   title="Page <?php echo $i; ?>">
                    <?php echo $i; ?>
                </a>
            <?php endfor; ?>
            
            <?php if($page < $total_pages): ?>
                <a href="?page=<?php echo $page + 1; ?>&filter=<?php echo $filter; ?>&search=<?php echo urlencode($search); ?>" class="page-link" title="Next Page">
                    <i class="fas fa-angle-right"></i>
                </a>
                <a href="?page=<?php echo $total_pages; ?>&filter=<?php echo $filter; ?>&search=<?php echo urlencode($search); ?>" class="page-link" title="Last Page">
                    <i class="fas fa-angle-double-right"></i>
                </a>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>
    
    <!-- View Modal -->
    <div id="viewModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3><i class="fas fa-eye" style="color: var(--royal-violet);"></i> Request Details</h3>
                <button class="btn-cancel" onclick="closeModal('viewModal')" style="padding: 8px 15px;">Close</button>
            </div>
            <div class="modal-body" id="requestDetails">
                <!-- Content loaded via AJAX -->
            </div>
        </div>
    </div>
    
    <!-- Edit Modal -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3><i class="fas fa-edit" style="color: var(--royal-violet);"></i> Update Request Status</h3>
                <button class="btn-cancel" onclick="closeModal('editModal')" style="padding: 8px 15px;">Cancel</button>
            </div>
            <div class="modal-body">
                <form id="editForm" method="POST">
                    <input type="hidden" name="request_id" id="editRequestId">
                    <input type="hidden" name="update_status" value="1">
                    
                    <div class="form-group">
                        <label for="editStatus">Status</label>
                        <select name="status" id="editStatus" class="form-control" required>
                            <option value="pending">Pending</option>
                            <option value="processing">Processing</option>
                            <option value="matched">Matched</option>
                            <option value="completed">Completed</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="editNotes">Admin Notes</label>
                        <textarea name="admin_notes" id="editNotes" class="form-control" rows="4" 
                                  placeholder="Add any notes or remarks about this request..."></textarea>
                    </div>
                    
                    <div class="modal-footer">
                        <button type="submit" form="editForm" class="btn-update">
                            <i class="fas fa-save"></i> Update Status
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
<script>
    // Sidebar Toggle for Mobile
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        sidebar.classList.toggle('active');
    }
    
    function showRequestDetails(requestId) {
        // Show loading state
        document.getElementById('requestDetails').innerHTML = `
            <div style="text-align: center; padding: 2rem;">
                <i class="fas fa-spinner fa-spin fa-2x" style="color: var(--royal-violet); margin-bottom: 1rem;"></i>
                <p>Loading request details...</p>
            </div>
        `;
        
        openModal('viewModal');
        
        // Load request details via AJAX
        fetch(`get-request-details.php?id=${requestId}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.text();
            })
            .then(html => {
                document.getElementById('requestDetails').innerHTML = html;
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('requestDetails').innerHTML = `
                    <div class="alert alert-error">
                        <i class="fas fa-exclamation-circle"></i> 
                        Failed to load request details. Please try again.
                    </div>
                `;
            });
    }
    
    function showEditForm(requestId) {
        document.getElementById('editRequestId').value = requestId;
        openModal('editModal');
    }
    
    function openModal(modalId) {
        document.getElementById(modalId).style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }
    
    function closeModal(modalId) {
        document.getElementById(modalId).style.display = 'none';
        document.body.style.overflow = 'auto';
    }
    
    function exportData() {
        const filter = "<?php echo $filter; ?>";
        const search = "<?php echo urlencode($search); ?>";
        window.location.href = `export-requests.php?filter=${filter}&search=${search}`;
    }
    
    // Auto-submit search on Enter
    document.querySelector('.search-input')?.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            this.form.submit();
        }
    });
    
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
    
    // Close modal when clicking outside
    document.addEventListener('click', function(event) {
        if (event.target.classList.contains('modal')) {
            closeModal('viewModal');
            closeModal('editModal');
        }
    });
    
    // Close modal with Escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeModal('viewModal');
            closeModal('editModal');
        }
    });
    
    // Auto-close alerts
    setTimeout(() => {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 300);
        });
    }, 5000);
    
    // Prevent form resubmission
    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }
</script>
</body>
</html>

<?php
// Close database connection
mysqli_close($conn);
?>