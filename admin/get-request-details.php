<?php
// get-request-details.php - AJAX endpoint for request details
session_start();

// Check admin authentication
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('HTTP/1.1 403 Forbidden');
    exit('Access denied');
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
    header('HTTP/1.1 500 Internal Server Error');
    exit('Database connection failed');
}

// Get request ID
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id <= 0) {
    header('HTTP/1.1 400 Bad Request');
    exit('Invalid request ID');
}

// Fetch request details
$query = "SELECT * FROM student_requirements WHERE id = '$id'";
$result = mysqli_query($conn, $query);

if (!$result || mysqli_num_rows($result) == 0) {
    echo '<div class="alert alert-error">Request not found!</div>';
    exit();
}

$row = mysqli_fetch_assoc($result);

// Format dates
$created_date = date('d M Y, h:i A', strtotime($row['created_at']));
$updated_date = !empty($row['updated_at']) ? date('d M Y, h:i A', strtotime($row['updated_at'])) : 'Not updated yet';

// Status badge
$status_class = 'status-' . $row['status'];
$status_text = ucfirst($row['status']);
?>

<div class="detail-row">
    <div class="detail-label">Request ID:</div>
    <div class="detail-value"><strong>#<?php echo str_pad($row['id'], 5, '0', STR_PAD_LEFT); ?></strong></div>
</div>

<div class="detail-row">
    <div class="detail-label">Student Name:</div>
    <div class="detail-value"><?php echo htmlspecialchars($row['student_name']); ?></div>
</div>

<div class="detail-row">
    <div class="detail-label">Parent Name:</div>
    <div class="detail-value"><?php echo htmlspecialchars($row['parent_name'] ?? 'Not provided'); ?></div>
</div>

<div class="detail-row">
    <div class="detail-label">Email:</div>
    <div class="detail-value"><?php echo htmlspecialchars($row['email']); ?></div>
</div>

<div class="detail-row">
    <div class="detail-label">Phone:</div>
    <div class="detail-value"><?php echo htmlspecialchars($row['phone']); ?></div>
</div>

<div class="detail-row">
    <div class="detail-label">Grade Level:</div>
    <div class="detail-value"><?php echo htmlspecialchars($row['grade_level']); ?></div>
</div>

<div class="detail-row">
    <div class="detail-label">Subjects:</div>
    <div class="detail-value"><?php echo htmlspecialchars($row['subjects']); ?></div>
</div>

<div class="detail-row">
    <div class="detail-label">Location:</div>
    <div class="detail-value"><?php echo htmlspecialchars($row['location']); ?></div>
</div>

<div class="detail-row">
    <div class="detail-label">Preferred Days:</div>
    <div class="detail-value"><?php echo htmlspecialchars($row['preferred_days'] ?? 'Flexible'); ?></div>
</div>

<div class="detail-row">
    <div class="detail-label">Preferred Time:</div>
    <div class="detail-value"><?php echo htmlspecialchars($row['preferred_time'] ?? 'Flexible'); ?></div>
</div>

<div class="detail-row">
    <div class="detail-label">Budget:</div>
    <div class="detail-value">₹<?php echo number_format($row['budget'], 2); ?></div>
</div>

<div class="detail-row">
    <div class="detail-label">Additional Requirements:</div>
    <div class="detail-value"><?php echo nl2br(htmlspecialchars($row['additional_requirements'] ?? 'None')); ?></div>
</div>

<div class="detail-row">
    <div class="detail-label">Status:</div>
    <div class="detail-value">
        <span class="status-badge <?php echo $status_class; ?>">
            <?php echo $status_text; ?>
        </span>
    </div>
</div>

<div class="detail-row">
    <div class="detail-label">Admin Notes:</div>
    <div class="detail-value"><?php echo nl2br(htmlspecialchars($row['admin_notes'] ?? 'No notes yet')); ?></div>
</div>

<div class="detail-row">
    <div class="detail-label">Created:</div>
    <div class="detail-value"><?php echo $created_date; ?></div>
</div>

<div class="detail-row">
    <div class="detail-label">Last Updated:</div>
    <div class="detail-value"><?php echo $updated_date; ?></div>
</div>

<?php
mysqli_close($conn);
?>