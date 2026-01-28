<?php
// student-requests.php - Complete solution without AJAX
session_start();

// Check if session is already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Database configuration directly in the file to avoid include issues
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
            $success = "Status updated successfully!";
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
        $success = "Request deleted successfully!";
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
    <title>Student Requests - Home Tutor Castle Admin</title>
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
            overflow-x: hidden; /* Prevent horizontal scroll */
        }
        
        .admin-container {
            margin-left: 260px;
            padding: 2rem;
            width: calc(100% - 260px);
        }
        
        @media (max-width: 768px) {
            .admin-container {
                margin-left: 70px;
                width: calc(100% - 70px);
            }
        }
        
        @media (max-width: 576px) {
            .admin-container {
                margin-left: 0;
                width: 100%;
                padding: 1rem;
            }
        }
        
        .page-title {
            color: var(--primary-purple);
            margin-bottom: 2rem;
            font-family: 'Poppins', sans-serif;
            font-size: 2rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .page-title i {
            color: var(--magenta-pink);
        }
        
        .alert {
            padding: 1rem 1.5rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            animation: slideDown 0.3s ease;
        }
        
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .alert-success {
            background: linear-gradient(135deg, #D4EDDA, #C8E6C9);
            color: #155724;
            border-left: 5px solid #4CAF50;
        }
        
        .alert-error {
            background: linear-gradient(135deg, #F8D7DA, #F5C6CB);
            color: #721C24;
            border-left: 5px solid #F44336;
        }
        
        /* Statistics Cards */
        .stats-bar {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
            width: 100%;
            overflow: hidden;
        }
        
        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            text-align: center;
            transition: var(--transition);
            border-top: 4px solid;
            overflow: hidden;
            min-width: 0; /* Prevent overflow */
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(94, 43, 151, 0.15);
        }
        
        .stat-card:nth-child(1) { border-top-color: var(--primary-purple); }
        .stat-card:nth-child(2) { border-top-color: #FF9800; }
        .stat-card:nth-child(3) { border-top-color: #2196F3; }
        .stat-card:nth-child(4) { border-top-color: #4CAF50; }
        .stat-card:nth-child(5) { border-top-color: #F44336; }
        
        .stat-number {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--primary-purple);
            font-family: 'Poppins', sans-serif;
            margin-bottom: 0.5rem;
            line-height: 1;
        }
        
        .stat-label {
            color: #666;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 500;
        }
        
        /* Filter Section */
        .filter-section {
            background: white;
            padding: 1.5rem;
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            margin-bottom: 2rem;
            display: flex;
            gap: 1rem;
            align-items: flex-end;
            flex-wrap: wrap;
            width: 100%;
            overflow: hidden;
        }
        
        .filter-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            flex: 1;
            min-width: 150px;
        }
        
        .filter-group label {
            font-weight: 500;
            color: var(--dark-gray);
            font-size: 0.9rem;
            font-family: 'Poppins', sans-serif;
        }
        
        .filter-select, .search-input {
            padding: 0.75rem 1rem;
            border: 2px solid var(--medium-gray);
            border-radius: 8px;
            font-size: 0.95rem;
            font-family: 'Inter', 'Roboto', sans-serif;
            transition: var(--transition);
            background: var(--light-gray);
            width: 100%;
            max-width: 100%;
            box-sizing: border-box;
        }
        
        .filter-select:focus, .search-input:focus {
            outline: none;
            border-color: var(--royal-violet);
            box-shadow: 0 0 0 3px rgba(94, 43, 151, 0.1);
            background: white;
        }
        
        .filter-buttons {
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
        }
        
        .btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-family: 'Poppins', sans-serif;
            font-weight: 500;
            font-size: 0.95rem;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            white-space: nowrap;
        }
        
        .btn-filter {
            background: linear-gradient(135deg, var(--royal-violet), var(--magenta-pink));
            color: white;
            box-shadow: 0 4px 15px rgba(94, 43, 151, 0.2);
        }
        
        .btn-filter:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(94, 43, 151, 0.3);
        }
        
        .btn-reset {
            background: var(--medium-gray);
            color: var(--dark-gray);
        }
        
        .btn-reset:hover {
            background: #ced4da;
        }
        
        /* Table Styles - No Horizontal Scroll */
        .table-container {
            background: white;
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            margin-bottom: 2rem;
            width: 100%;
            overflow: hidden;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed; /* Fixed layout for better control */
        }
        
        thead {
            background: var(--primary-purple);
        }
        
        th {
            padding: 1rem;
            text-align: left;
            color: white;
            font-weight: 600;
            font-family: 'Poppins', sans-serif;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        /* Set column widths */
        th:nth-child(1) { width: 80px; } /* ID */
        th:nth-child(2) { width: 180px; } /* Name */
        th:nth-child(3) { width: 180px; } /* Contact */
        th:nth-child(4) { width: 80px; } /* Grade */
        th:nth-child(5) { width: 200px; } /* Subjects */
        th:nth-child(6) { width: 150px; } /* Location */
        th:nth-child(7) { width: 100px; } /* Status */
        th:nth-child(8) { width: 120px; } /* Date */
        th:nth-child(9) { width: 120px; } /* Actions */
        
        td {
            padding: 0.75rem 1rem;
            border-bottom: 1px solid var(--medium-gray);
            font-size: 0.9rem;
            font-family: 'Inter', 'Roboto', sans-serif;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            vertical-align: middle;
        }
        
        tr:hover {
            background: rgba(94, 43, 151, 0.05);
        }
        
        .status {
            padding: 0.4rem 0.8rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 500;
            font-family: 'Poppins', sans-serif;
            display: inline-block;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            white-space: nowrap;
        }
        
        .status-pending { 
            background: linear-gradient(135deg, #FFF3CD, #FFEAA7);
            color: #856404;
            border: 1px solid #FFEAA7;
        }
        
        .status-processing { 
            background: linear-gradient(135deg, #CCE5FF, #B3D9FF);
            color: #004085;
            border: 1px solid #B3D9FF;
        }
        
        .status-matched { 
            background: linear-gradient(135deg, #D4EDDA, #C8E6C9);
            color: #155724;
            border: 1px solid #C8E6C9;
        }
        
        .status-completed { 
            background: linear-gradient(135deg, #D1ECF1, #B2EBF2);
            color: #0C5460;
            border: 1px solid #B2EBF2;
        }
        
        .status-cancelled { 
            background: linear-gradient(135deg, #F8D7DA, #F5C6CB);
            color: #721C24;
            border: 1px solid #F5C6CB;
        }
        
        .action-buttons {
            display: flex;
            gap: 0.5rem;
        }
        
        .btn-action {
            width: 32px;
            height: 32px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: var(--transition);
            color: white;
            flex-shrink: 0;
        }
        
        .btn-action:hover {
            transform: translateY(-2px) scale(1.1);
        }
        
        .btn-view { background: #2196F3; }
        .btn-edit { background: #FF9800; }
        .btn-delete { background: #F44336; }
        
        /* Pagination */
        .pagination {
            display: flex;
            justify-content: center;
            gap: 0.5rem;
            flex-wrap: wrap;
        }
        
        .page-link {
            padding: 0.75rem 1rem;
            border: 2px solid var(--medium-gray);
            background: white;
            border-radius: 8px;
            text-decoration: none;
            color: var(--dark-gray);
            transition: var(--transition);
            font-family: 'Poppins', sans-serif;
            font-weight: 500;
            min-width: 40px;
            text-align: center;
            white-space: nowrap;
        }
        
        .page-link:hover {
            border-color: var(--royal-violet);
            color: var(--royal-violet);
            transform: translateY(-2px);
        }
        
        .page-link.active {
            background: linear-gradient(135deg, var(--royal-violet), var(--magenta-pink));
            color: white;
            border-color: transparent;
        }
        
        /* Modal Styles - Embedded in page */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 1000;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(5px);
        }
        
        .modal-content {
            background: white;
            padding: 2rem;
            border-radius: var(--radius);
            max-width: 500px;
            width: 90%;
            max-height: 80vh;
            overflow-y: auto;
            box-shadow: 0 20px 50px rgba(0,0,0,0.2);
            animation: modalFadeIn 0.3s ease;
        }
        
        @keyframes modalFadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid var(--light-gray);
        }
        
        .modal-header h3 {
            color: var(--primary-purple);
            font-family: 'Poppins', sans-serif;
            font-size: 1.5rem;
        }
        
        .close-modal {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: #666;
            transition: var(--transition);
        }
        
        .close-modal:hover {
            color: var(--royal-violet);
            transform: rotate(90deg);
        }
        
        .modal-body {
            margin-bottom: 1.5rem;
        }
        
        .detail-row {
            display: flex;
            margin-bottom: 0.75rem;
            padding-bottom: 0.75rem;
            border-bottom: 1px solid var(--light-gray);
            flex-wrap: wrap;
        }
        
        .detail-row:last-child {
            border-bottom: none;
        }
        
        .detail-label {
            font-weight: 600;
            min-width: 150px;
            color: var(--dark-gray);
            font-family: 'Poppins', sans-serif;
            margin-bottom: 0.25rem;
        }
        
        .detail-value {
            flex: 1;
            color: #555;
            min-width: 200px;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: var(--dark-gray);
            font-family: 'Poppins', sans-serif;
        }
        
        .form-control {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid var(--medium-gray);
            border-radius: 8px;
            font-size: 0.95rem;
            font-family: 'Inter', 'Roboto', sans-serif;
            transition: var(--transition);
            background: var(--light-gray);
            box-sizing: border-box;
        }
        
        .form-control:focus {
            outline: none;
            border-color: var(--royal-violet);
            box-shadow: 0 0 0 3px rgba(94, 43, 151, 0.1);
            background: white;
        }
        
        .modal-footer {
            display: flex;
            justify-content: flex-end;
            gap: 1rem;
            padding-top: 1.5rem;
            border-top: 2px solid var(--light-gray);
        }
        
        .btn-update {
            background: linear-gradient(135deg, var(--royal-violet), var(--magenta-pink));
            color: white;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-family: 'Poppins', sans-serif;
            font-weight: 500;
            transition: var(--transition);
        }
        
        .btn-update:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(94, 43, 151, 0.3);
        }
        
        .btn-cancel {
            background: var(--medium-gray);
            color: var(--dark-gray);
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-family: 'Poppins', sans-serif;
            font-weight: 500;
            transition: var(--transition);
        }
        
        .btn-cancel:hover {
            background: #ced4da;
        }
        
        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 3rem 1rem;
            color: #666;
            width: 100%;
        }
        
        .empty-state i {
            font-size: 4rem;
            color: #ddd;
            margin-bottom: 1rem;
        }
        
        /* Responsive Design */
        @media (max-width: 1200px) {
            .admin-container {
                padding: 1.5rem;
            }
            
            .stats-bar {
                grid-template-columns: repeat(3, 1fr);
            }
        }
        
        @media (max-width: 992px) {
            .stats-bar {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .filter-section {
                flex-direction: column;
                align-items: stretch;
            }
            
            .filter-group {
                min-width: auto;
            }
            
            .filter-buttons {
                width: 100%;
                justify-content: center;
            }
            
            /* Adjust table for medium screens */
            table {
                display: block;
            }
            
            th, td {
                padding: 0.5rem;
                font-size: 0.85rem;
            }
            
            th:nth-child(1) { width: 60px; }
            th:nth-child(2) { width: 150px; }
            th:nth-child(3) { width: 150px; }
            th:nth-child(4) { width: 60px; }
            th:nth-child(5) { width: 150px; }
            th:nth-child(6) { width: 120px; }
            th:nth-child(7) { width: 80px; }
            th:nth-child(8) { width: 100px; }
            th:nth-child(9) { width: 100px; }
        }
        
        @media (max-width: 768px) {
            .admin-container {
                margin-left: 0;
                padding: 1rem;
                width: 100%;
            }
            
            .stats-bar {
                grid-template-columns: 1fr;
            }
            
            .page-title {
                font-size: 1.5rem;
            }
            
            /* Stack table rows on mobile */
            .table-container {
                overflow-x: auto;
            }
            
            table {
                min-width: 800px;
            }
            
            .action-buttons {
                flex-direction: column;
            }
            
            .btn-action {
                width: 100%;
                height: auto;
                padding: 0.5rem;
            }
            
            .detail-row {
                flex-direction: column;
            }
            
            .detail-label {
                min-width: 100%;
                margin-bottom: 0.25rem;
            }
            
            .detail-value {
                min-width: 100%;
            }
        }
        
        @media (max-width: 480px) {
            .filter-buttons {
                flex-direction: column;
            }
            
            .btn {
                width: 100%;
                justify-content: center;
            }
            
            .pagination {
                flex-direction: column;
                align-items: center;
            }
            
            .page-link {
                width: 40px;
                height: 40px;
                display: flex;
                align-items: center;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <?php 
    // Include dashboard sidebar
    if (file_exists('dashboard.php')) {
        include 'dashboard.php';
    } else {
        echo '<div style="padding: 2rem; background: white; border-radius: var(--radius);">
                <h1 style="color: var(--primary-purple);">Admin Panel</h1>
                <p>Dashboard sidebar not found. Please ensure dashboard.php exists in the admin folder.</p>
              </div>';
    }
    ?>
    
    <div class="admin-container">
        <h1 class="page-title">
            <i class="fas fa-user-graduate"></i> Student Requests
        </h1>
        
        <?php if(!empty($success)): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($success); ?>
            </div>
        <?php endif; ?>
        
        <?php if(!empty($error)): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>
        
        <!-- Statistics -->
        <div class="stats-bar">
            <div class="stat-card">
                <div class="stat-number"><?php echo $stats['total'] ?? 0; ?></div>
                <div class="stat-label">Total Requests</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-number"><?php echo $stats['pending'] ?? 0; ?></div>
                <div class="stat-label">Pending</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-number"><?php echo $stats['processing'] ?? 0; ?></div>
                <div class="stat-label">Processing</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-number"><?php echo $stats['matched'] ?? 0; ?></div>
                <div class="stat-label">Matched</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-number"><?php echo $stats['completed'] ?? 0; ?></div>
                <div class="stat-label">Completed</div>
            </div>
        </div>
        
        <!-- Filters -->
        <div class="filter-section">
            <div class="filter-group">
                <label for="filter">Status Filter</label>
                <select id="filter" class="filter-select" onchange="updateFilter()">
                    <option value="all" <?php echo $filter == 'all' ? 'selected' : ''; ?>>All Requests</option>
                    <option value="pending" <?php echo $filter == 'pending' ? 'selected' : ''; ?>>Pending</option>
                    <option value="processing" <?php echo $filter == 'processing' ? 'selected' : ''; ?>>Processing</option>
                    <option value="matched" <?php echo $filter == 'matched' ? 'selected' : ''; ?>>Matched</option>
                    <option value="completed" <?php echo $filter == 'completed' ? 'selected' : ''; ?>>Completed</option>
                    <option value="cancelled" <?php echo $filter == 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                </select>
            </div>
            
            <div class="filter-group">
                <label for="search">Search</label>
                <input type="text" id="search" class="search-input" 
                       placeholder="Search by name, email, or subject..." 
                       value="<?php echo htmlspecialchars($search); ?>"
                       onkeyup="if(event.key === 'Enter') updateSearch()">
            </div>
            
            <div class="filter-buttons">
                <button class="btn btn-filter" onclick="updateSearch()">
                    <i class="fas fa-search"></i> Search
                </button>
                <a href="student-requests.php" class="btn btn-reset">
                    <i class="fas fa-redo"></i> Reset
                </a>
            </div>
        </div>
        
        <!-- Table -->
        <div class="table-container">
            <?php if(mysqli_num_rows($result) > 0): ?>
                <table>
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
                                <span class="status status-<?php echo $row['status']; ?>">
                                    <?php echo ucfirst($row['status']); ?>
                                </span>
                            </td>
                            <td>
                                <?php echo date('d M Y', strtotime($row['created_at'])); ?><br>
                                <small><?php echo date('h:i A', strtotime($row['created_at'])); ?></small>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn-action btn-view" onclick="viewRequest(<?php echo $row['id']; ?>)" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn-action btn-edit" onclick="editRequest(<?php echo $row['id']; ?>)" title="Edit Status">
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
                <div class="empty-state">
                    <i class="fas fa-inbox"></i>
                    <h3>No Student Requests Found</h3>
                    <p>No requests match your current filters.</p>
                    <a href="student-requests.php" class="btn btn-filter" style="margin-top: 1rem;">
                        <i class="fas fa-redo"></i> Clear Filters
                    </a>
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
    
    <!-- View Modal - Embedded directly -->
    <div id="viewModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Request Details</h3>
                <button class="close-modal" onclick="closeModal('viewModal')">&times;</button>
            </div>
            <div class="modal-body" id="requestDetails">
                <!-- Content will be loaded dynamically -->
            </div>
            <div class="modal-footer">
                <button class="btn-cancel" onclick="closeModal('viewModal')">Close</button>
            </div>
        </div>
    </div>
    
    <!-- Edit Modal - Embedded directly -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Update Request Status</h3>
                <button class="close-modal" onclick="closeModal('editModal')">&times;</button>
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
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn-cancel" onclick="closeModal('editModal')">Cancel</button>
                <button type="submit" form="editForm" class="btn-update">Update Status</button>
            </div>
        </div>
    </div>
    
    <script>
        // Update filter and search
        function updateFilter() {
            const filter = document.getElementById('filter').value;
            const search = document.getElementById('search').value;
            window.location.href = `student-requests.php?filter=${filter}&search=${encodeURIComponent(search)}`;
        }
        
        function updateSearch() {
            const filter = document.getElementById('filter').value;
            const search = document.getElementById('search').value;
            window.location.href = `student-requests.php?filter=${filter}&search=${encodeURIComponent(search)}`;
        }
        
        // View request details - no AJAX needed
        function viewRequest(id) {
            // Create a simple alert or redirect to a details page
            // Since we don't have AJAX, we'll use a simple approach
            window.location.href = `request-details.php?id=${id}`;
        }
        
        // Edit request - populate form with data from PHP
        function editRequest(id) {
            // We need to get the current status and notes
            // For now, we'll just set the ID and show the modal
            // The user will need to manually set the status
            document.getElementById('editRequestId').value = id;
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
        
        // Close modal when clicking outside
        window.onclick = function(event) {
            if (event.target.classList.contains('modal')) {
                closeModal('viewModal');
                closeModal('editModal');
            }
        }
        
        // Auto-close alerts after 5 seconds
        setTimeout(() => {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                alert.style.opacity = '0';
                alert.style.transform = 'translateY(-10px)';
                setTimeout(() => alert.remove(), 300);
            });
        }, 5000);
        
        // Prevent form resubmission on page refresh
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