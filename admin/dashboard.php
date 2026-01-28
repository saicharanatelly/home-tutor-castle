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

// Recent activities
$recent_students = mysqli_query($conn, "SELECT * FROM student_requirements ORDER BY created_at DESC LIMIT 5");
$recent_contacts = mysqli_query($conn, "SELECT * FROM contacts ORDER BY created_at DESC LIMIT 5");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Admin Panel</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/admin.css">
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="logo">
            <h2><i class="fas fa-graduation-cap"></i> Home Castle Tutor</h2>
            <p>Admin Panel</p>
        </div>
        
        <div class="nav-links">
            <a href="dashboard.php" class="active">
                <i class="fas fa-tachometer-alt"></i> <span>Dashboard</span>
            </a>
            <a href="student-requests.php">
                <i class="fas fa-user-graduate"></i> <span>Student Requests</span>
            </a>
            <a href="manage-tutors.php">
                <i class="fas fa-chalkboard-teacher"></i> <span>Manage Tutors</span>
            </a>
            <a href="contact-messages.php">
                <i class="fas fa-envelope"></i> <span>Contact Messages</span>
                <?php if($unread_contacts > 0): ?>
                    <span class="badge"><?php echo $unread_contacts; ?></span>
                <?php endif; ?>
            </a>
            <a href="update-content.php">
                <i class="fas fa-edit"></i> <span>Update Content</span>
            </a>
            <a href="update-banners.php">
                <i class="fas fa-images"></i> <span>Update Banners</span>
            </a>
            <a href="reports.php">
                <i class="fas fa-chart-bar"></i> <span>Reports</span>
            </a>
            <a href="settings.php">
                <i class="fas fa-cog"></i> <span>Settings</span>
            </a>
        </div>
    </div>
    
    <!-- Main Content -->
    <div class="main-content">
        <!-- Header -->
        <div class="header">
            <h1>Dashboard Overview</h1>
            <div class="user-info">
                <div class="user-avatar">
                    <i class="fas fa-user"></i>
                </div>
                <div>
                    <strong>Welcome, <?php echo $_SESSION['admin_username']; ?></strong>
                    <p style="font-size: 0.9rem; color: #666;">Administrator</p>
                </div>
                <a href="logout.php" class="logout-btn">Logout</a>
            </div>
        </div>
        
        <!-- Stats -->
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
        </div>
        
        <!-- Dashboard Grid -->
        <div class="dashboard-grid">
            <!-- Recent Activity -->
            <div class="recent-activity">
                <div class="section-title">
                    <h3>Recent Student Requests</h3>
                    <a href="student-requests.php" class="view-all">View All</a>
                </div>
                
                <table>
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
                            <td><?php echo $row['student_name']; ?></td>
                            <td><?php echo $row['email']; ?></td>
                            <td><?php echo $row['grade_level']; ?></td>
                            <td><?php echo substr($row['subjects'], 0, 20) . '...'; ?></td>
                            <td>
                                <span class="status-badge status-<?php echo $row['status']; ?>">
                                    <?php echo ucfirst($row['status']); ?>
                                </span>
                            </td>
                            <td>
                                <a href="view-request.php?id=<?php echo $row['id']; ?>" class="btn btn-small">
                                    View
                                </a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
            
            <!-- Quick Actions -->
            <div class="quick-actions">
                <div class="section-title">
                    <h3>Quick Actions</h3>
                </div>
                
                <div class="quick-actions-grid">
                    <a href="add-tutor.php" class="action-card">
                        <i class="fas fa-user-plus"></i>
                        <h4>Add New Tutor</h4>
                    </a>
                    
                    <a href="update-content.php" class="action-card">
                        <i class="fas fa-edit"></i>
                        <h4>Update Content</h4>
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
                
                <div style="margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid #eee;">
                    <h4 style="margin-bottom: 1rem; color: var(--dark-gray);">Recent Messages</h4>
                    <?php while($contact = mysqli_fetch_assoc($recent_contacts)): ?>
                    <div style="background: var(--light-gray); padding: 0.8rem; border-radius: 5px; margin-bottom: 0.5rem;">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <strong style="font-size: 0.9rem;"><?php echo $contact['name']; ?></strong>
                            <span class="status-badge status-unread">Unread</span>
                        </div>
                        <p style="font-size: 0.8rem; color: #666; margin-top: 0.3rem;"><?php echo $contact['subject']; ?></p>
                    </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // Auto refresh dashboard every 60 seconds
        setTimeout(function() {
            location.reload();
        }, 60000);
    </script>
</body>
</html>