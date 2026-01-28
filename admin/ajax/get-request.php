<?php
// ajax/get-request.php
session_start();
require_once '../includes/config.php';

if (!isset($_SESSION['admin_logged_in'])) {
    die('Unauthorized access');
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$edit_mode = isset($_GET['edit']);

if ($id <= 0) {
    die('Invalid request ID');
}

// Prepare and execute query
$sql = "SELECT * FROM student_requirements WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);

if (!$row) {
    if ($edit_mode) {
        echo json_encode(['error' => 'Request not found']);
    } else {
        echo '<div class="alert alert-error">Request not found!</div>';
    }
    exit;
}

if ($edit_mode) {
    // Return JSON for edit form
    header('Content-Type: application/json');
    echo json_encode([
        'id' => $row['id'],
        'status' => $row['status'],
        'admin_notes' => $row['admin_notes'] ?? ''
    ]);
} else {
    // Return HTML for view modal
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
        <div class="detail-label">Email:</div>
        <div class="detail-value"><?php echo htmlspecialchars($row['email']); ?></div>
    </div>
    
    <div class="detail-row">
        <div class="detail-label">Phone:</div>
        <div class="detail-value"><?php echo htmlspecialchars($row['phone']); ?></div>
    </div>
    
    <?php if(!empty($row['parent_name'])): ?>
    <div class="detail-row">
        <div class="detail-label">Parent Name:</div>
        <div class="detail-value"><?php echo htmlspecialchars($row['parent_name']); ?></div>
    </div>
    <?php endif; ?>
    
    <?php if(!empty($row['parent_phone'])): ?>
    <div class="detail-row">
        <div class="detail-label">Parent Phone:</div>
        <div class="detail-value"><?php echo htmlspecialchars($row['parent_phone']); ?></div>
    </div>
    <?php endif; ?>
    
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
    
    <?php if(!empty($row['preferred_time'])): ?>
    <div class="detail-row">
        <div class="detail-label">Preferred Time:</div>
        <div class="detail-value"><?php echo htmlspecialchars($row['preferred_time']); ?></div>
    </div>
    <?php endif; ?>
    
    <?php if(!empty($row['preferred_mode'])): ?>
    <div class="detail-row">
        <div class="detail-label">Preferred Mode:</div>
        <div class="detail-value"><?php echo htmlspecialchars($row['preferred_mode']); ?></div>
    </div>
    <?php endif; ?>
    
    <?php if(!empty($row['notes'])): ?>
    <div class="detail-row">
        <div class="detail-label">Student Notes:</div>
        <div class="detail-value"><?php echo nl2br(htmlspecialchars($row['notes'])); ?></div>
    </div>
    <?php endif; ?>
    
    <div class="detail-row">
        <div class="detail-label">Status:</div>
        <div class="detail-value">
            <span class="status status-<?php echo $row['status']; ?>">
                <?php echo ucfirst($row['status']); ?>
            </span>
        </div>
    </div>
    
    <?php if(!empty($row['admin_notes'])): ?>
    <div class="detail-row">
        <div class="detail-label">Admin Notes:</div>
        <div class="detail-value"><?php echo nl2br(htmlspecialchars($row['admin_notes'])); ?></div>
    </div>
    <?php endif; ?>
    
    <div class="detail-row">
        <div class="detail-label">Submitted:</div>
        <div class="detail-value">
            <?php echo date('d M Y, h:i A', strtotime($row['created_at'])); ?>
        </div>
    </div>
    
    <?php if($row['created_at'] != $row['updated_at']): ?>
    <div class="detail-row">
        <div class="detail-label">Last Updated:</div>
        <div class="detail-value">
            <?php echo date('d M Y, h:i A', strtotime($row['updated_at'])); ?>
        </div>
    </div>
    <?php endif; ?>
    <?php
}
?>