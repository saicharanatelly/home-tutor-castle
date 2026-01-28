<?php
// request-details.php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit();
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Database connection
$conn = mysqli_connect('localhost', 'root', '', 'home_castle_tutor');
$sql = "SELECT * FROM student_requirements WHERE id = $id";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Request Details</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        .detail { margin-bottom: 10px; }
        .label { font-weight: bold; color: #333; }
        .back-btn { margin-top: 20px; }
    </style>
</head>
<body>
    <h1>Request Details #<?php echo $id; ?></h1>
    <?php if($row): ?>
        <?php foreach($row as $key => $value): ?>
            <div class="detail">
                <span class="label"><?php echo ucfirst(str_replace('_', ' ', $key)); ?>:</span>
                <span><?php echo htmlspecialchars($value); ?></span>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Request not found.</p>
    <?php endif; ?>
    <div class="back-btn">
        <a href="student-requests.php">← Back to Requests</a>
    </div>
</body>
</html>