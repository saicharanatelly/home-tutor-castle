<?php
session_start();
include '../includes/config.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit();
}

// Handle actions
if (isset($_GET['verify'])) {
    $id = mysqli_real_escape_string($conn, $_GET['verify']);
    mysqli_query($conn, "UPDATE tutors SET status = 'verified' WHERE id = '$id'");
    $_SESSION['success'] = "Tutor verified successfully!";
    header('Location: manage-tutors.php');
    exit();
}

if (isset($_GET['reject'])) {
    $id = mysqli_real_escape_string($conn, $_GET['reject']);
    mysqli_query($conn, "UPDATE tutors SET status = 'rejected' WHERE id = '$id'");
    $_SESSION['success'] = "Tutor rejected successfully!";
    header('Location: manage-tutors.php');
    exit();
}

if (isset($_GET['delete'])) {
    $id = mysqli_real_escape_string($conn, $_GET['delete']);
    mysqli_query($conn, "DELETE FROM tutors WHERE id = '$id'");
    $_SESSION['success'] = "Tutor deleted successfully!";
    header('Location: manage-tutors.php');
    exit();
}

// Add new tutor
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_tutor'])) {
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $qualification = mysqli_real_escape_string($conn, $_POST['qualification']);
    $experience = mysqli_real_escape_string($conn, $_POST['experience']);
    $subjects = mysqli_real_escape_string($conn, $_POST['subjects']);
    $grade_levels = mysqli_real_escape_string($conn, $_POST['grade_levels']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $teaching_mode = mysqli_real_escape_string($conn, $_POST['teaching_mode']);
    $hourly_rate = mysqli_real_escape_string($conn, $_POST['hourly_rate']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    
    $sql = "INSERT INTO tutors (full_name, email, phone, qualification, experience, subjects, grade_levels, location, teaching_mode, hourly_rate, status) 
            VALUES ('$full_name', '$email', '$phone', '$qualification', '$experience', '$subjects', '$grade_levels', '$location', '$teaching_mode', '$hourly_rate', '$status')";
    
    if (mysqli_query($conn, $sql)) {
        $_SESSION['success'] = "Tutor added successfully!";
        header('Location: manage-tutors.php');
        exit();
    } else {
        $error = "Error adding tutor: " . mysqli_error($conn);
    }
}

// Fetch tutors
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';
$search = isset($_GET['search']) ? $_GET['search'] : '';

$query = "SELECT * FROM tutors WHERE 1=1";
if ($filter != 'all') {
    $query .= " AND status = '$filter'";
}
if (!empty($search)) {
    $query .= " AND (full_name LIKE '%$search%' OR email LIKE '%$search%' OR subjects LIKE '%$search%')";
}
$query .= " ORDER BY created_at DESC";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Tutors - Admin Panel</title>
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

        /* Tutors Grid */
        .tutors-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(380px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }

        .tutor-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            border: 1px solid var(--medium-gray);
        }

        .tutor-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.12);
        }

        .tutor-header {
            background: linear-gradient(135deg, var(--royal-violet), var(--primary-purple));
            color: white;
            padding: 25px;
            position: relative;
        }

        .tutor-name {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .tutor-contact {
            font-size: 14px;
            opacity: 0.9;
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .tutor-contact i {
            margin-right: 10px;
            width: 16px;
        }

        .tutor-body {
            padding: 25px;
        }

        .tutor-info {
            margin-bottom: 20px;
        }

        .info-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
            padding-bottom: 12px;
            border-bottom: 1px solid var(--medium-gray);
        }

        .info-item:last-child {
            border-bottom: none;
        }

        .info-label {
            font-weight: 600;
            color: var(--dark-gray);
            font-size: 13px;
            font-family: 'Inter', sans-serif;
        }

        .info-value {
            color: #666;
            font-size: 13px;
            text-align: right;
            max-width: 60%;
            font-family: 'Inter', sans-serif;
        }

        .tutor-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid var(--medium-gray);
        }

        .status-badge {
            padding: 6px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-family: 'Poppins', sans-serif;
        }

        .status-pending {
            background: #fff3cd;
            color: #856404;
        }

        .status-verified {
            background: #d4edda;
            color: #155724;
        }

        .status-rejected {
            background: #f8d7da;
            color: #721c24;
        }

        .status-active {
            background: #d1ecf1;
            color: #0c5460;
        }

        .tutor-date {
            color: #888;
            font-size: 12px;
            display: flex;
            align-items: center;
            gap: 6px;
            font-family: 'Inter', sans-serif;
        }

        .tutor-actions {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }

        .action-btn {
            flex: 1;
            padding: 10px 15px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 13px;
            font-weight: 500;
            transition: all 0.3s ease;
            text-decoration: none;
            text-align: center;
            font-family: 'Inter', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }

        .btn-verify {
            background: var(--success-green);
            color: white;
        }

        .btn-verify:hover {
            background: #218838;
        }

        .btn-reject {
            background: var(--danger-red);
            color: white;
        }

        .btn-reject:hover {
            background: #c82333;
        }

        .btn-edit {
            background: var(--info-blue);
            color: white;
        }

        .btn-edit:hover {
            background: #138496;
        }

        .btn-delete {
            background: #6c757d;
            color: white;
        }

        .btn-delete:hover {
            background: #5a6268;
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

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid var(--medium-gray);
            border-radius: 8px;
            font-size: 14px;
            font-family: 'Inter', sans-serif;
            transition: all 0.3s ease;
            background: white;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: var(--royal-violet);
            box-shadow: 0 0 0 3px rgba(94, 43, 151, 0.1);
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .modal-actions {
            display: flex;
            gap: 15px;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid var(--medium-gray);
        }

        .modal-actions button {
            flex: 1;
            padding: 12px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 15px;
            font-weight: 500;
            transition: all 0.3s ease;
            font-family: 'Poppins', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .modal-submit {
            background: linear-gradient(135deg, var(--magenta-pink), var(--royal-violet));
            color: white;
            box-shadow: 0 4px 12px rgba(195, 60, 145, 0.25);
        }

        .modal-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(195, 60, 145, 0.35);
        }

        .modal-cancel {
            background: #6c757d;
            color: white;
        }

        .modal-cancel:hover {
            background: #5a6268;
        }

        /* No Results */
        .no-results {
            text-align: center;
            padding: 60px 40px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            color: #666;
            grid-column: 1 / -1;
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
            .tutors-grid {
                grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
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
            
            .tutors-grid {
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
            
            .header-actions {
                width: 100%;
                justify-content: flex-end;
            }
            
            .modal-content {
                padding: 25px 20px;
            }
        }

        @media (max-width: 480px) {
            .tutor-actions {
                flex-wrap: wrap;
            }
            
            .action-btn {
                min-width: calc(50% - 5px);
            }
            
            .modal-actions {
                flex-direction: column;
            }
            
            .modal-actions button {
                width: 100%;
            }
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
            .menu-toggle {
                display: block;
            }
        }

        /* Stats Cards */
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
            <p>Admin Dashboard</p>
        </div>
        
        <div class="sidebar-menu">
            <a href="dashboard.php" class="menu-item">
                <i class="fas fa-home"></i>
                <span>Dashboard</span>
            </a>
            <a href="manage-tutors.php" class="menu-item active">
                <i class="fas fa-chalkboard-teacher"></i>
                <span>Manage Tutors</span>
            </a>
            <a href="student-requests.php" class="menu-item">
                <i class="fas fa-user-graduate"></i>
                <span>Student Requests</span>
            </a>
            <a href="assignments.php" class="menu-item">
                <i class="fas fa-handshake"></i>
                <span>Assignments</span>
            </a>
            <a href="payments.php" class="menu-item">
                <i class="fas fa-credit-card"></i>
                <span>Payments</span>
            </a>
            <a href="reports.php" class="menu-item">
                <i class="fas fa-chart-bar"></i>
                <span>Reports</span>
            </a>
            <a href="settings.php" class="menu-item">
                <i class="fas fa-cog"></i>
                <span>Settings</span>
            </a>
        </div>
        
        <div class="sidebar-footer">
            <div class="admin-profile">
                <div class="admin-avatar">
                    <?php 
                    $admin_name = $_SESSION['admin_name'] ?? 'Admin';
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
            <h1><i class="fas fa-chalkboard-teacher" style="color: var(--royal-violet); margin-right: 12px;"></i> Manage Tutors</h1>
            <div class="header-actions">
                <button class="btn-primary" onclick="openAddModal()">
                    <i class="fas fa-plus"></i> Add New Tutor
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
        
        <!-- Filters Section -->
        <div class="filters-section">
            <div class="filters-container">
                <div class="filter-buttons">
                    <a href="?filter=all" class="filter-btn <?php echo $filter == 'all' ? 'active' : ''; ?>">
                        All Tutors
                    </a>
                    <a href="?filter=pending" class="filter-btn <?php echo $filter == 'pending' ? 'active' : ''; ?>">
                        Pending
                    </a>
                    <a href="?filter=verified" class="filter-btn <?php echo $filter == 'verified' ? 'active' : ''; ?>">
                        Verified
                    </a>
                    <a href="?filter=active" class="filter-btn <?php echo $filter == 'active' ? 'active' : ''; ?>">
                        Active
                    </a>
                    <a href="?filter=rejected" class="filter-btn <?php echo $filter == 'rejected' ? 'active' : ''; ?>">
                        Rejected
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
        
        <!-- Tutors Grid -->
        <div class="tutors-grid">
            <?php if(mysqli_num_rows($result) > 0): ?>
                <?php while($tutor = mysqli_fetch_assoc($result)): ?>
                <div class="tutor-card">
                    <div class="tutor-header">
                        <h3 class="tutor-name"><?php echo htmlspecialchars($tutor['full_name']); ?></h3>
                        <div class="tutor-contact">
                            <div><i class="fas fa-envelope"></i> <?php echo htmlspecialchars($tutor['email']); ?></div>
                            <div><i class="fas fa-phone"></i> <?php echo htmlspecialchars($tutor['phone']); ?></div>
                        </div>
                    </div>
                    
                    <div class="tutor-body">
                        <div class="tutor-info">
                            <div class="info-item">
                                <span class="info-label">Qualification:</span>
                                <span class="info-value"><?php echo htmlspecialchars($tutor['qualification']); ?></span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Experience:</span>
                                <span class="info-value"><?php echo htmlspecialchars($tutor['experience']); ?> years</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Subjects:</span>
                                <span class="info-value"><?php echo htmlspecialchars($tutor['subjects']); ?></span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Grade Levels:</span>
                                <span class="info-value"><?php echo htmlspecialchars($tutor['grade_levels']); ?></span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Location:</span>
                                <span class="info-value"><?php echo htmlspecialchars($tutor['location']); ?></span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Teaching Mode:</span>
                                <span class="info-value"><?php echo ucfirst($tutor['teaching_mode']); ?></span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Hourly Rate:</span>
                                <span class="info-value">₹<?php echo number_format($tutor['hourly_rate'], 2); ?></span>
                            </div>
                        </div>
                        
                        <div class="tutor-footer">
                            <span class="status-badge status-<?php echo $tutor['status']; ?>">
                                <?php echo ucfirst($tutor['status']); ?>
                            </span>
                            <div class="tutor-date">
                                <i class="fas fa-calendar-alt"></i> 
                                <?php echo date('d M Y', strtotime($tutor['created_at'])); ?>
                            </div>
                        </div>
                        
                        <div class="tutor-actions">
                            <?php if($tutor['status'] == 'pending'): ?>
                                <a href="?verify=<?php echo $tutor['id']; ?>&filter=<?php echo $filter; ?>&search=<?php echo urlencode($search); ?>" 
                                   class="action-btn btn-verify">
                                    <i class="fas fa-check"></i> Verify
                                </a>
                                <a href="?reject=<?php echo $tutor['id']; ?>&filter=<?php echo $filter; ?>&search=<?php echo urlencode($search); ?>" 
                                   class="action-btn btn-reject">
                                    <i class="fas fa-times"></i> Reject
                                </a>
                            <?php endif; ?>
                            
                            <button class="action-btn btn-edit" onclick="editTutor(<?php echo $tutor['id']; ?>)">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <a href="?delete=<?php echo $tutor['id']; ?>&filter=<?php echo $filter; ?>&search=<?php echo urlencode($search); ?>" 
                               class="action-btn btn-delete"
                               onclick="return confirm('Are you sure you want to delete tutor: <?php echo addslashes($tutor['full_name']); ?>? This action cannot be undone.')">
                                <i class="fas fa-trash"></i> Delete
                            </a>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="no-results">
                    <i class="fas fa-chalkboard-teacher"></i>
                    <h3>No Tutors Found</h3>
                    <p><?php echo empty($search) ? 'No tutors in the database yet.' : 'No tutors match your search criteria.'; ?></p>
                    <?php if(!empty($search)): ?>
                        <a href="?filter=all" class="btn-primary" style="margin-top: 20px; display: inline-block;">
                            <i class="fas fa-times"></i> Clear Search
                        </a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Add Tutor Modal -->
    <div id="addModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3><i class="fas fa-user-plus" style="color: var(--royal-violet);"></i> Add New Tutor</h3>
            </div>
            <form method="POST" action="">
                <div class="form-row">
                    <div class="form-group">
                        <label>Full Name *</label>
                        <input type="text" name="full_name" required placeholder="Enter full name">
                    </div>
                    <div class="form-group">
                        <label>Email *</label>
                        <input type="email" name="email" required placeholder="Enter email address">
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label>Phone *</label>
                        <input type="text" name="phone" required placeholder="Enter phone number">
                    </div>
                    <div class="form-group">
                        <label>Qualification *</label>
                        <input type="text" name="qualification" required placeholder="e.g., M.Sc, B.Ed">
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label>Experience (Years)</label>
                        <input type="number" name="experience" placeholder="e.g., 5" min="0" step="0.5">
                    </div>
                    <div class="form-group">
                        <label>Hourly Rate (₹)</label>
                        <input type="number" name="hourly_rate" placeholder="e.g., 500" min="0" step="50">
                    </div>
                </div>
                
                <div class="form-group">
                    <label>Subjects *</label>
                    <input type="text" name="subjects" required placeholder="e.g., Mathematics, Physics, Chemistry">
                </div>
                
                <div class="form-group">
                    <label>Grade Levels *</label>
                    <input type="text" name="grade_levels" required placeholder="e.g., 1-10, 11-12, College">
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label>Location *</label>
                        <input type="text" name="location" required placeholder="Enter city/area">
                    </div>
                    <div class="form-group">
                        <label>Teaching Mode</label>
                        <select name="teaching_mode">
                            <option value="both">Both Home & Online</option>
                            <option value="home">Home Tuition Only</option>
                            <option value="online">Online Only</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <label>Status</label>
                    <select name="status">
                        <option value="pending">Pending</option>
                        <option value="verified">Verified</option>
                        <option value="active">Active</option>
                        <option value="rejected">Rejected</option>
                    </select>
                </div>
                
                <div class="modal-actions">
                    <button type="submit" name="add_tutor" class="modal-submit">
                        <i class="fas fa-plus"></i> Add Tutor
                    </button>
                    <button type="button" onclick="closeModal()" class="modal-cancel">
                        <i class="fas fa-times"></i> Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        // Modal Functions
        function openAddModal() {
            document.getElementById('addModal').style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }
        
        function closeModal() {
            document.getElementById('addModal').style.display = 'none';
            document.body.style.overflow = 'auto';
        }
        
        // Sidebar Toggle for Mobile
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('active');
        }
        
        // Close modal when clicking outside
        document.addEventListener('click', function(event) {
            if (event.target.classList.contains('modal')) {
                closeModal();
            }
        });
        
        // Close modal with Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeModal();
            }
        });
        
        // Edit Tutor Function
        function editTutor(id) {
            alert('Edit functionality for tutor ID: ' + id + ' will be implemented soon.');
            // Future implementation: Fetch tutor data via AJAX and open edit modal
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
    </script>
</body>
</html>