<?php
session_start();
include '../includes/config.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit();
}

// Mark as read
if (isset($_GET['mark_read'])) {
    $id = mysqli_real_escape_string($conn, $_GET['mark_read']);
    mysqli_query($conn, "UPDATE contacts SET status = 'read' WHERE id = '$id'");
    header('Location: contact-messages.php');
    exit();
}

// Mark as replied
if (isset($_GET['mark_replied'])) {
    $id = mysqli_real_escape_string($conn, $_GET['mark_replied']);
    mysqli_query($conn, "UPDATE contacts SET status = 'replied' WHERE id = '$id'");
    header('Location: contact-messages.php');
    exit();
}

// Delete message
if (isset($_GET['delete'])) {
    $id = mysqli_real_escape_string($conn, $_GET['delete']);
    mysqli_query($conn, "DELETE FROM contacts WHERE id = '$id'");
    $success = "Message deleted successfully!";
}

// Update admin notes
if (isset($_POST['update_notes'])) {
    $id = mysqli_real_escape_string($conn, $_POST['message_id']);
    $notes = mysqli_real_escape_string($conn, $_POST['admin_notes']);
    mysqli_query($conn, "UPDATE contacts SET admin_notes = '$notes' WHERE id = '$id'");
    $success = "Notes updated successfully!";
}

// Filter and search
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';
$search = isset($_GET['search']) ? $_GET['search'] : '';

$query = "SELECT * FROM contacts WHERE 1=1";
if ($filter != 'all') {
    $query .= " AND status = '$filter'";
}
if (!empty($search)) {
    $query .= " AND (name LIKE '%$search%' OR email LIKE '%$search%' OR subject LIKE '%$search%')";
}
$query .= " ORDER BY created_at DESC";

$result = mysqli_query($conn, $query);
$total = mysqli_num_rows($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Messages - Admin Panel</title>
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
        
        .stats-cards {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1rem;
            margin-bottom: 2rem;
        }
        
        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            text-align: center;
        }
        
        .stat-number {
            font-size: 2rem;
            font-weight: bold;
            color: var(--primary-orange);
        }
        
        .stat-label {
            color: #666;
            font-size: 0.9rem;
            margin-top: 0.5rem;
        }
        
        .filters {
            background: white;
            padding: 1.5rem;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
            display: flex;
            gap: 1rem;
        }
        
        .filter-btn {
            padding: 0.5rem 1rem;
            border: 1px solid #ddd;
            background: white;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            color: var(--dark-gray);
            transition: all 0.3s ease;
        }
        
        .filter-btn.active {
            background: var(--primary-orange);
            color: white;
            border-color: var(--primary-orange);
        }
        
        .messages-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        .message-item {
            padding: 1.5rem;
            border-bottom: 1px solid #eee;
            transition: background 0.3s ease;
        }
        
        .message-item:hover {
            background: #f8f9fa;
        }
        
        .message-item.unread {
            background: #f0f8ff;
            border-left: 4px solid var(--primary-orange);
        }
        
        .message-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }
        
        .message-info h4 {
            margin: 0 0 0.5rem 0;
            color: var(--dark-gray);
        }
        
        .message-info .meta {
            color: #666;
            font-size: 0.9rem;
        }
        
        .message-status {
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }
        
        .status-unread { background: #d1ecf1; color: #0c5460; }
        .status-read { background: #d4edda; color: #155724; }
        .status-replied { background: #cce5ff; color: #004085; }
        
        .message-actions {
            display: flex;
            gap: 0.5rem;
        }
        
        .btn {
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .btn-view { background: #17a2b8; color: white; }
        .btn-reply { background: #28a745; color: white; }
        .btn-mark { background: #ffc107; color: #212529; }
        .btn-delete { background: #dc3545; color: white; }
        
        .message-content {
            margin: 1rem 0;
            color: #666;
            line-height: 1.6;
        }
        
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
            max-width: 600px;
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
        }
        
        .form-group textarea {
            width: 100%;
            padding: 0.8rem;
            border: 1px solid #ddd;
            border-radius: 5px;
            min-height: 100px;
        }
    </style>
</head>
<body>
    <?php include 'dashboard.php'; ?>
    
    <div class="container">
        <div class="page-header">
            <h1>Contact Messages</h1>
            <div>
                <a href="export-contacts.php" class="btn" style="background: #6c757d; color: white;">
                    <i class="fas fa-download"></i> Export CSV
                </a>
            </div>
        </div>
        
        <?php if(isset($success)): ?>
            <div style="background: #d4edda; color: #155724; padding: 1rem; border-radius: 5px; margin-bottom: 1rem;">
                <i class="fas fa-check-circle"></i> <?php echo $success; ?>
            </div>
        <?php endif; ?>
        
        <!-- Stats -->
        <div class="stats-cards">
            <?php
            $stats = [
                'all' => 'Total Messages',
                'unread' => 'Unread',
                'read' => 'Read',
                'replied' => 'Replied'
            ];
            
            foreach($stats as $key => $label):
                $count = mysqli_fetch_assoc(mysqli_query($conn, 
                    "SELECT COUNT(*) as count FROM contacts" . ($key != 'all' ? " WHERE status = '$key'" : "")
                ))['count'];
            ?>
            <div class="stat-card">
                <div class="stat-number"><?php echo $count; ?></div>
                <div class="stat-label"><?php echo $label; ?></div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <!-- Filters -->
        <div class="filters">
            <div style="display: flex; gap: 0.5rem;">
                <a href="?filter=all" class="filter-btn <?php echo $filter == 'all' ? 'active' : ''; ?>">All</a>
                <a href="?filter=unread" class="filter-btn <?php echo $filter == 'unread' ? 'active' : ''; ?>">Unread</a>
                <a href="?filter=read" class="filter-btn <?php echo $filter == 'read' ? 'active' : ''; ?>">Read</a>
                <a href="?filter=replied" class="filter-btn <?php echo $filter == 'replied' ? 'active' : ''; ?>">Replied</a>
            </div>
            
            <form method="GET" style="flex: 1;">
                <input type="text" name="search" value="<?php echo $search; ?>" 
                       placeholder="Search messages..." style="width: 100%; padding: 0.8rem; border: 1px solid #ddd; border-radius: 5px;">
                <input type="hidden" name="filter" value="<?php echo $filter; ?>">
            </form>
        </div>
        
        <!-- Messages List -->
        <div class="messages-container">
            <?php while($message = mysqli_fetch_assoc($result)): ?>
            <div class="message-item <?php echo $message['status'] == 'unread' ? 'unread' : ''; ?>">
                <div class="message-header">
                    <div class="message-info">
                        <h4>
                            <?php echo htmlspecialchars($message['name']); ?>
                            <small style="color: #666; font-weight: normal;">&lt;<?php echo htmlspecialchars($message['email']); ?>&gt;</small>
                        </h4>
                        <div class="meta">
                            <span><i class="fas fa-phone"></i> <?php echo $message['phone']; ?></span> |
                            <span><i class="fas fa-clock"></i> <?php echo date('d M Y H:i', strtotime($message['created_at'])); ?></span>
                        </div>
                    </div>
                    
                    <div style="display: flex; gap: 1rem; align-items: center;">
                        <span class="message-status status-<?php echo $message['status']; ?>">
                            <?php echo ucfirst($message['status']); ?>
                        </span>
                        
                        <div class="message-actions">
                            <button class="btn btn-view" onclick="viewMessage(<?php echo $message['id']; ?>)">
                                <i class="fas fa-eye"></i>
                            </button>
                            
                            <?php if($message['status'] == 'unread'): ?>
                                <a href="?mark_read=<?php echo $message['id']; ?>" class="btn btn-mark">
                                    <i class="fas fa-check"></i> Mark Read
                                </a>
                            <?php endif; ?>
                            
                            <?php if($message['status'] != 'replied'): ?>
                                <a href="?mark_replied=<?php echo $message['id']; ?>" class="btn btn-reply">
                                    <i class="fas fa-reply"></i> Mark Replied
                                </a>
                            <?php endif; ?>
                            
                            <button class="btn btn-reply" onclick="replyMessage('<?php echo htmlspecialchars($message['email']); ?>')">
                                <i class="fas fa-envelope"></i> Reply
                            </button>
                            
                            <button class="btn btn-delete" 
                                    onclick="if(confirm('Delete this message?')) window.location='?delete=<?php echo $message['id']; ?>'">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
                
                <div class="message-content">
                    <strong>Subject:</strong> <?php echo htmlspecialchars($message['subject']); ?><br>
                    <strong>Message:</strong><br>
                    <?php echo nl2br(htmlspecialchars(substr($message['message'], 0, 200))); ?>...
                </div>
                
                <?php if($message['admin_notes']): ?>
                <div style="background: #f8f9fa; padding: 1rem; border-radius: 5px; margin-top: 1rem;">
                    <strong>Admin Notes:</strong><br>
                    <?php echo nl2br(htmlspecialchars($message['admin_notes'])); ?>
                    <button onclick="editNotes(<?php echo $message['id']; ?>)" 
                            style="background: none; border: none; color: var(--primary-orange); cursor: pointer; margin-top: 0.5rem;">
                        <i class="fas fa-edit"></i> Edit Notes
                    </button>
                </div>
                <?php endif; ?>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
    
    <!-- View Modal -->
    <div id="viewModal" class="modal">
        <div class="modal-content">
            <h3>Message Details</h3>
            <div id="messageDetails"></div>
            <button onclick="closeModal()" style="margin-top: 1rem; padding: 0.5rem 1rem;">Close</button>
        </div>
    </div>
    
    <!-- Notes Modal -->
    <div id="notesModal" class="modal">
        <div class="modal-content">
            <h3>Edit Admin Notes</h3>
            <form method="POST" id="notesForm">
                <input type="hidden" name="message_id" id="messageId">
                
                <div class="form-group">
                    <label>Admin Notes:</label>
                    <textarea name="admin_notes" id="adminNotes"></textarea>
                </div>
                
                <div style="display: flex; gap: 1rem;">
                    <button type="submit" name="update_notes" class="btn btn-reply">Save Notes</button>
                    <button type="button" onclick="closeModal()" class="btn btn-delete">Cancel</button>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        function viewMessage(id) {
            fetch('ajax/get-message.php?id=' + id)
                .then(response => response.text())
                .then(data => {
                    document.getElementById('messageDetails').innerHTML = data;
                    document.getElementById('viewModal').style.display = 'flex';
                    
                    // Mark as read when viewing
                    if (!data.includes('status-read') && !data.includes('status-replied')) {
                        window.location.href = '?mark_read=' + id;
                    }
                });
        }
        
        function editNotes(id) {
            fetch('ajax/get-message.php?id=' + id + '&notes=1')
                .then(response => response.json())
                .then(data => {
                    document.getElementById('messageId').value = data.id;
                    document.getElementById('adminNotes').value = data.admin_notes || '';
                    document.getElementById('notesModal').style.display = 'flex';
                });
        }
        
        function replyMessage(email) {
            window.location.href = 'mailto:' + email;
        }
        
        function closeModal() {
            document.getElementById('viewModal').style.display = 'none';
            document.getElementById('notesModal').style.display = 'none';
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