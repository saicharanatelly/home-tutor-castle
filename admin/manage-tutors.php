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
    $success = "Tutor verified successfully!";
}

if (isset($_GET['reject'])) {
    $id = mysqli_real_escape_string($conn, $_GET['reject']);
    mysqli_query($conn, "UPDATE tutors SET status = 'rejected' WHERE id = '$id'");
    $success = "Tutor rejected successfully!";
}

if (isset($_GET['delete'])) {
    $id = mysqli_real_escape_string($conn, $_GET['delete']);
    mysqli_query($conn, "DELETE FROM tutors WHERE id = '$id'");
    $success = "Tutor deleted successfully!";
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
        $success = "Tutor added successfully!";
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
    <style>
        .container {
            margin: 2rem;
        }
        
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }
        
        .btn-add {
            background: var(--primary-orange);
            color: white;
            padding: 0.8rem 1.5rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
        }
        
        .btn-add:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
        }
        
        .tutors-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .tutor-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            overflow: hidden;
            transition: transform 0.3s ease;
        }
        
        .tutor-card:hover {
            transform: translateY(-5px);
        }
        
        .tutor-header {
            background: linear-gradient(135deg, var(--primary-orange), var(--primary-dark));
            color: white;
            padding: 1.5rem;
        }
        
        .tutor-name {
            font-size: 1.3rem;
            margin: 0 0 0.5rem 0;
        }
        
        .tutor-contact {
            font-size: 0.9rem;
            opacity: 0.9;
        }
        
        .tutor-body {
            padding: 1.5rem;
        }
        
        .tutor-info {
            margin-bottom: 1rem;
        }
        
        .info-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.5rem;
        }
        
        .info-label {
            font-weight: 600;
            color: var(--dark-gray);
        }
        
        .info-value {
            color: #666;
        }
        
        .status-badge {
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
            display: inline-block;
        }
        
        .status-pending { background: #fff3cd; color: #856404; }
        .status-verified { background: #d4edda; color: #155724; }
        .status-rejected { background: #f8d7da; color: #721c24; }
        .status-active { background: #d1ecf1; color: #0c5460; }
        
        .tutor-actions {
            display: flex;
            gap: 0.5rem;
            margin-top: 1rem;
            border-top: 1px solid #eee;
            padding-top: 1rem;
        }
        
        .action-btn {
            flex: 1;
            padding: 0.5rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }
        
        .btn-verify { background: #28a745; color: white; }
        .btn-reject { background: #dc3545; color: white; }
        .btn-edit { background: #17a2b8; color: white; }
        .btn-delete { background: #6c757d; color: white; }
        
        .action-btn:hover {
            opacity: 0.9;
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
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }
        
        .modal-content {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            max-width: 500px;
            width: 90%;
            max-height: 80vh;
            overflow-y: auto;
        }
        
        .form-group {
            margin-bottom: 1rem;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: var(--dark-gray);
        }
        
        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 0.8rem;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
        }
        
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }
    </style>
</head>
<body>
    <?php include 'dashboard.php'; ?>
    
    <div class="container">
        <div class="page-header">
            <h1>Manage Tutors</h1>
            <button class="btn-add" onclick="openAddModal()">
                <i class="fas fa-plus"></i> Add New Tutor
            </button>
        </div>
        
        <?php if(isset($success)): ?>
            <div style="background: #d4edda; color: #155724; padding: 1rem; border-radius: 5px; margin-bottom: 1rem;">
                <i class="fas fa-check-circle"></i> <?php echo $success; ?>
            </div>
        <?php endif; ?>
        
        <?php if(isset($error)): ?>
            <div style="background: #f8d7da; color: #721c24; padding: 1rem; border-radius: 5px; margin-bottom: 1rem;">
                <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
            </div>
        <?php endif; ?>
        
        <!-- Filters -->
        <div style="background: white; padding: 1.5rem; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 2rem;">
            <div style="display: flex; gap: 1rem; align-items: center;">
                <div style="display: flex; gap: 0.5rem;">
                    <a href="?filter=all" class="filter-btn <?php echo $filter == 'all' ? 'active' : ''; ?>">All</a>
                    <a href="?filter=pending" class="filter-btn <?php echo $filter == 'pending' ? 'active' : ''; ?>">Pending</a>
                    <a href="?filter=verified" class="filter-btn <?php echo $filter == 'verified' ? 'active' : ''; ?>">Verified</a>
                    <a href="?filter=active" class="filter-btn <?php echo $filter == 'active' ? 'active' : ''; ?>">Active</a>
                </div>
                
                <form method="GET" style="flex: 1;">
                    <input type="text" name="search" value="<?php echo $search; ?>" 
                           placeholder="Search tutors..." 
                           style="width: 100%; padding: 0.8rem; border: 1px solid #ddd; border-radius: 5px;">
                    <input type="hidden" name="filter" value="<?php echo $filter; ?>">
                </form>
            </div>
        </div>
        
        <!-- Tutors Grid -->
        <div class="tutors-grid">
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
                            <span class="info-value"><?php echo htmlspecialchars($tutor['experience']); ?></span>
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
                    
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span class="status-badge status-<?php echo $tutor['status']; ?>">
                            <?php echo ucfirst($tutor['status']); ?>
                        </span>
                        <div style="color: #666; font-size: 0.9rem;">
                            <i class="fas fa-clock"></i> <?php echo date('d M Y', strtotime($tutor['created_at'])); ?>
                        </div>
                    </div>
                    
                    <div class="tutor-actions">
                        <?php if($tutor['status'] == 'pending'): ?>
                            <a href="?verify=<?php echo $tutor['id']; ?>" class="action-btn btn-verify">Verify</a>
                            <a href="?reject=<?php echo $tutor['id']; ?>" class="action-btn btn-reject">Reject</a>
                        <?php endif; ?>
                        
                        <button class="action-btn btn-edit" onclick="editTutor(<?php echo $tutor['id']; ?>)">Edit</button>
                        <a href="?delete=<?php echo $tutor['id']; ?>" 
                           class="action-btn btn-delete"
                           onclick="return confirm('Are you sure you want to delete this tutor?')">Delete</a>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
    
    <!-- Add Tutor Modal -->
    <div id="addModal" class="modal">
        <div class="modal-content">
            <h3 style="margin-bottom: 1.5rem;">Add New Tutor</h3>
            <form method="POST" action="">
                <div class="form-row">
                    <div class="form-group">
                        <label>Full Name *</label>
                        <input type="text" name="full_name" required>
                    </div>
                    <div class="form-group">
                        <label>Email *</label>
                        <input type="email" name="email" required>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label>Phone *</label>
                        <input type="text" name="phone" required>
                    </div>
                    <div class="form-group">
                        <label>Qualification *</label>
                        <input type="text" name="qualification" required>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label>Experience</label>
                        <input type="text" name="experience" placeholder="e.g., 5 years">
                    </div>
                    <div class="form-group">
                        <label>Hourly Rate (₹)</label>
                        <input type="number" name="hourly_rate" step="0.01">
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
                        <input type="text" name="location" required>
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
                    </select>
                </div>
                
                <div style="display: flex; gap: 1rem; margin-top: 1.5rem;">
                    <button type="submit" name="add_tutor" class="btn-add">Add Tutor</button>
                    <button type="button" onclick="closeModal()" class="action-btn btn-delete">Cancel</button>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        function openAddModal() {
            document.getElementById('addModal').style.display = 'flex';
        }
        
        function closeModal() {
            document.getElementById('addModal').style.display = 'none';
        }
        
        // Close modal when clicking outside
        window.onclick = function(event) {
            if (event.target.classList.contains('modal')) {
                closeModal();
            }
        }
    </script>
</body>
</html>