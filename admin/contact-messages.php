<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
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
    $_SESSION['success'] = "Message deleted successfully!";
    header('Location: contact-messages.php');
    exit();
}

// Update admin notes
if (isset($_POST['update_notes'])) {
    $id = mysqli_real_escape_string($conn, $_POST['message_id']);
    $notes = mysqli_real_escape_string($conn, $_POST['admin_notes']);
    mysqli_query($conn, "UPDATE contacts SET admin_notes = '$notes' WHERE id = '$id'");
    $_SESSION['success'] = "Notes updated successfully!";
    header('Location: contact-messages.php');
    exit();
}

// Filter and search
$filter = isset($_GET['filter']) ? mysqli_real_escape_string($conn, $_GET['filter']) : 'all';
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';

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
    <link rel="stylesheet" href="css/admin-styles.css">
    <style>
        :root {
            --primary-orange: #FF6B35;
            --dark-gray: #333;
            --light-gray: #f8f9fa;
            --success: #28a745;
            --warning: #ffc107;
            --danger: #dc3545;
            --info: #17a2b8;
        }
        
        .container {
            margin: 2rem;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }
        
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            flex-wrap: wrap;
            gap: 1rem;
        }
        
        .page-header h1 {
            color: var(--dark-gray);
            margin: 0;
            font-size: 1.8rem;
        }
        
        .stats-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0,0,0,0.15);
        }
        
        .stat-number {
            font-size: 2.5rem;
            font-weight: bold;
            color: var(--primary-orange);
            line-height: 1;
            margin-bottom: 0.5rem;
        }
        
        .stat-label {
            color: #666;
            font-size: 0.95rem;
            font-weight: 500;
        }
        
        .filters-section {
            background: white;
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }
        
        .filters-row {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            margin-bottom: 1rem;
            align-items: center;
        }
        
        .filter-group {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }
        
        .filter-btn {
            padding: 0.5rem 1.2rem;
            border: 2px solid #e0e0e0;
            background: white;
            border-radius: 25px;
            cursor: pointer;
            text-decoration: none;
            color: #666;
            font-weight: 500;
            transition: all 0.3s ease;
            font-size: 0.9rem;
        }
        
        .filter-btn:hover {
            border-color: var(--primary-orange);
            color: var(--primary-orange);
        }
        
        .filter-btn.active {
            background: var(--primary-orange);
            color: white;
            border-color: var(--primary-orange);
        }
        
        .search-form {
            flex: 1;
            min-width: 300px;
            position: relative;
        }
        
        .search-form input {
            width: 100%;
            padding: 0.8rem 1rem 0.8rem 2.5rem;
            border: 2px solid #e0e0e0;
            border-radius: 25px;
            font-size: 0.95rem;
            transition: border-color 0.3s ease;
        }
        
        .search-form input:focus {
            outline: none;
            border-color: var(--primary-orange);
        }
        
        .search-form i {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
        }
        
        .messages-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        .message-item {
            padding: 1.5rem;
            border-bottom: 1px solid #eee;
            transition: background 0.3s ease;
        }
        
        .message-item:last-child {
            border-bottom: none;
        }
        
        .message-item:hover {
            background: #f8f9fa;
        }
        
        .message-item.unread {
            background: linear-gradient(90deg, rgba(255, 107, 53, 0.05) 0%, rgba(255, 107, 53, 0.02) 100%);
            border-left: 4px solid var(--primary-orange);
        }
        
        .message-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1rem;
            gap: 1rem;
            flex-wrap: wrap;
        }
        
        .message-info h4 {
            margin: 0 0 0.5rem 0;
            color: var(--dark-gray);
            font-size: 1.1rem;
        }
        
        .message-info .meta {
            color: #666;
            font-size: 0.9rem;
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }
        
        .message-info .meta span {
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
        }
        
        .status-badge {
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .status-unread { background: #d1ecf1; color: #0c5460; }
        .status-read { background: #d4edda; color: #155724; }
        .status-replied { background: #cce5ff; color: #004085; }
        
        .message-actions {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
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
            font-weight: 500;
        }
        
        .btn-sm {
            padding: 0.4rem 0.8rem;
            font-size: 0.85rem;
        }
        
        .btn-view { background: var(--info); color: white; }
        .btn-view:hover { background: #138496; }
        
        .btn-reply { background: var(--success); color: white; }
        .btn-reply:hover { background: #218838; }
        
        .btn-mark { background: var(--warning); color: #212529; }
        .btn-mark:hover { background: #e0a800; }
        
        .btn-delete { background: var(--danger); color: white; }
        .btn-delete:hover { background: #c82333; }
        
        .btn-export { 
            background: #6c757d; 
            color: white;
            padding: 0.7rem 1.2rem;
        }
        .btn-export:hover { background: #5a6268; }
        
        .message-content {
            margin: 1rem 0;
            color: #666;
            line-height: 1.6;
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 8px;
        }
        
        .admin-notes {
            background: #fff3cd;
            padding: 1rem;
            border-radius: 8px;
            margin-top: 1rem;
            border-left: 4px solid #ffc107;
        }
        
        .admin-notes strong {
            color: #856404;
        }
        
        .notes-actions {
            margin-top: 0.5rem;
        }
        
        .btn-edit {
            background: none;
            border: none;
            color: var(--primary-orange);
            cursor: pointer;
            font-size: 0.9rem;
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            padding: 0.3rem 0.5rem;
            border-radius: 4px;
            transition: background 0.3s ease;
        }
        
        .btn-edit:hover {
            background: rgba(255, 107, 53, 0.1);
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
            animation: fadeIn 0.3s ease;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        .modal-content {
            background: white;
            padding: 2rem;
            border-radius: 12px;
            max-width: 700px;
            width: 90%;
            max-height: 85vh;
            overflow-y: auto;
            animation: slideIn 0.3s ease;
        }
        
        @keyframes slideIn {
            from { transform: translateY(-20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        
        .modal-content h3 {
            margin-top: 0;
            color: var(--dark-gray);
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #eee;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #555;
        }
        
        .form-group textarea {
            width: 100%;
            padding: 1rem;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            min-height: 120px;
            font-family: inherit;
            font-size: 0.95rem;
            transition: border-color 0.3s ease;
        }
        
        .form-group textarea:focus {
            outline: none;
            border-color: var(--primary-orange);
        }
        
        .modal-actions {
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid #eee;
        }
        
        .no-messages {
            text-align: center;
            padding: 3rem;
            color: #666;
        }
        
        .no-messages i {
            font-size: 3rem;
            color: #ddd;
            margin-bottom: 1rem;
        }
        
        .message-preview {
            max-height: 100px;
            overflow: hidden;
            position: relative;
        }
        
        .message-preview::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 30px;
            background: linear-gradient(transparent, #f8f9fa);
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .container {
                margin: 1rem;
            }
            
            .stats-cards {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .message-header {
                flex-direction: column;
            }
            
            .message-actions {
                width: 100%;
            }
        }
        
        @media (max-width: 480px) {
            .stats-cards {
                grid-template-columns: 1fr;
            }
            
            .search-form {
                min-width: 100%;
            }
            
            .filters-row {
                flex-direction: column;
                align-items: stretch;
            }
        }
    </style>
</head>
<body>
    <?php include 'dashboard.php'; ?>
    
    <div class="container">
        <div class="page-header">
            <h1><i class="fas fa-envelope-open-text"></i> Contact Messages</h1>
            <div>
                <a href="export-contacts.php" class="btn btn-export">
                    <i class="fas fa-file-export"></i> Export CSV
                </a>
            </div>
        </div>
        
        <?php if(isset($_SESSION['success'])): ?>
            <div style="background: #d4edda; color: #155724; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.5rem;">
                <i class="fas fa-check-circle"></i> <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>
        
        <!-- Stats -->
        <div class="stats-cards">
            <?php
            $stats = [
                'all' => ['label' => 'Total Messages', 'icon' => 'fas fa-envelope'],
                'unread' => ['label' => 'Unread', 'icon' => 'fas fa-envelope'],
                'read' => ['label' => 'Read', 'icon' => 'fas fa-envelope-open'],
                'replied' => ['label' => 'Replied', 'icon' => 'fas fa-reply']
            ];
            
            foreach($stats as $key => $stat):
                $count = mysqli_fetch_assoc(mysqli_query($conn, 
                    "SELECT COUNT(*) as count FROM contacts" . ($key != 'all' ? " WHERE status = '$key'" : "")
                ))['count'];
            ?>
            <div class="stat-card">
                <div class="stat-number"><?php echo $count; ?></div>
                <div class="stat-label">
                    <i class="<?php echo $stat['icon']; ?>"></i> <?php echo $stat['label']; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <!-- Filters -->
        <div class="filters-section">
            <div class="filters-row">
                <div class="filter-group">
                    <a href="?filter=all" class="filter-btn <?php echo $filter == 'all' ? 'active' : ''; ?>">
                        <i class="fas fa-layer-group"></i> All
                    </a>
                    <a href="?filter=unread" class="filter-btn <?php echo $filter == 'unread' ? 'active' : ''; ?>">
                        <i class="fas fa-envelope"></i> Unread
                    </a>
                    <a href="?filter=read" class="filter-btn <?php echo $filter == 'read' ? 'active' : ''; ?>">
                        <i class="fas fa-envelope-open"></i> Read
                    </a>
                    <a href="?filter=replied" class="filter-btn <?php echo $filter == 'replied' ? 'active' : ''; ?>">
                        <i class="fas fa-reply"></i> Replied
                    </a>
                </div>
                
                <form method="GET" class="search-form">
                    <i class="fas fa-search"></i>
                    <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" 
                           placeholder="Search messages by name, email, or subject...">
                    <input type="hidden" name="filter" value="<?php echo $filter; ?>">
                </form>
            </div>
            
            <?php if(!empty($search)): ?>
            <div style="color: #666; font-size: 0.9rem;">
                <i class="fas fa-search"></i> Searching for: "<?php echo htmlspecialchars($search); ?>"
                <a href="?filter=<?php echo $filter; ?>" style="margin-left: 1rem; color: var(--primary-orange); text-decoration: none;">
                    <i class="fas fa-times"></i> Clear search
                </a>
            </div>
            <?php endif; ?>
        </div>
        
        <!-- Messages List -->
        <div class="messages-container">
            <?php if($total == 0): ?>
            <div class="no-messages">
                <i class="fas fa-inbox"></i>
                <h3>No messages found</h3>
                <p>No contact messages <?php echo !empty($search) ? 'matching your search' : 'available'; ?></p>
            </div>
            <?php else: ?>
                <?php while($message = mysqli_fetch_assoc($result)): ?>
                <div class="message-item <?php echo $message['status'] == 'unread' ? 'unread' : ''; ?>">
                    <div class="message-header">
                        <div class="message-info">
                            <h4>
                                <?php echo htmlspecialchars($message['name']); ?>
                                <small style="color: var(--primary-orange); font-weight: 500;">
                                    &lt;<?php echo htmlspecialchars($message['email']); ?>&gt;
                                </small>
                            </h4>
                            <div class="meta">
                                <?php if($message['phone']): ?>
                                <span><i class="fas fa-phone"></i> <?php echo htmlspecialchars($message['phone']); ?></span>
                                <?php endif; ?>
                                <span><i class="fas fa-clock"></i> <?php echo date('d M Y, h:i A', strtotime($message['created_at'])); ?></span>
                                <span><i class="fas fa-tag"></i> <?php echo htmlspecialchars($message['subject']); ?></span>
                            </div>
                        </div>
                        
                        <div style="display: flex; gap: 1rem; align-items: center;">
                            <span class="status-badge status-<?php echo $message['status']; ?>">
                                <?php echo ucfirst($message['status']); ?>
                            </span>
                            
                            <div class="message-actions">
                                <button class="btn btn-sm btn-view" onclick="viewMessage(<?php echo $message['id']; ?>)">
                                    <i class="fas fa-eye"></i>
                                </button>
                                
                                <?php if($message['status'] == 'unread'): ?>
                                    <a href="?mark_read=<?php echo $message['id']; ?>&filter=<?php echo $filter; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?>" 
                                       class="btn btn-sm btn-mark">
                                        <i class="fas fa-check"></i>
                                    </a>
                                <?php endif; ?>
                                
                                <?php if($message['status'] != 'replied'): ?>
                                    <a href="?mark_replied=<?php echo $message['id']; ?>&filter=<?php echo $filter; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?>" 
                                       class="btn btn-sm btn-reply">
                                        <i class="fas fa-reply"></i>
                                    </a>
                                <?php endif; ?>
                                
                                <button class="btn btn-sm btn-reply" onclick="replyMessage('<?php echo htmlspecialchars($message['email']); ?>', '<?php echo htmlspecialchars($message['subject']); ?>')">
                                    <i class="fas fa-envelope"></i>
                                </button>
                                
                                <button class="btn btn-sm btn-delete" 
                                        onclick="deleteMessage(<?php echo $message['id']; ?>, '<?php echo htmlspecialchars(addslashes($message['name'])); ?>')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="message-content">
                        <div class="message-preview">
                            <?php echo nl2br(htmlspecialchars($message['message'])); ?>
                        </div>
                        <button onclick="viewMessage(<?php echo $message['id']; ?>)" class="btn-edit" style="margin-top: 0.5rem;">
                            <i class="fas fa-eye"></i> View Full Message
                        </button>
                    </div>
                    
                    <?php if($message['admin_notes']): ?>
                    <div class="admin-notes">
                        <strong>Admin Notes:</strong><br>
                        <?php echo nl2br(htmlspecialchars($message['admin_notes'])); ?>
                        <div class="notes-actions">
                            <button onclick="editNotes(<?php echo $message['id']; ?>)" class="btn-edit">
                                <i class="fas fa-edit"></i> Edit Notes
                            </button>
                        </div>
                    </div>
                    <?php else: ?>
                    <div style="margin-top: 0.5rem;">
                        <button onclick="editNotes(<?php echo $message['id']; ?>)" class="btn-edit">
                            <i class="fas fa-plus"></i> Add Notes
                        </button>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endwhile; ?>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- View Modal -->
    <div id="viewModal" class="modal">
        <div class="modal-content">
            <h3><i class="fas fa-envelope"></i> Message Details</h3>
            <div id="messageDetails"></div>
            <div class="modal-actions">
                <button onclick="closeModal()" class="btn" style="background: #6c757d; color: white;">Close</button>
            </div>
        </div>
    </div>
    
    <!-- Notes Modal -->
    <div id="notesModal" class="modal">
        <div class="modal-content">
            <h3><i class="fas fa-sticky-note"></i> Admin Notes</h3>
            <form method="POST" id="notesForm">
                <input type="hidden" name="message_id" id="messageId">
                
                <div class="form-group">
                    <label for="adminNotes">Notes:</label>
                    <textarea name="admin_notes" id="adminNotes" placeholder="Add notes about this message..."></textarea>
                </div>
                
                <div class="modal-actions">
                    <button type="submit" name="update_notes" class="btn btn-reply">
                        <i class="fas fa-save"></i> Save Notes
                    </button>
                    <button type="button" onclick="closeModal()" class="btn" style="background: #6c757d; color: white;">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        function viewMessage(id) {
            // Simple AJAX implementation (you should create ajax/get-message.php)
            fetch('ajax/get-message.php?id=' + id)
                .then(response => response.text())
                .then(data => {
                    if(data.trim() === '') {
                        data = `
                            <div style="color: #666;">
                                <p>Loading message details...</p>
                                <p>Create ajax/get-message.php to load full message details.</p>
                            </div>
                        `;
                    }
                    document.getElementById('messageDetails').innerHTML = data;
                    document.getElementById('viewModal').style.display = 'flex';
                    
                    // Mark as read when viewing if unread
                    if (!data.includes('status-read') && !data.includes('status-replied')) {
                        window.location.href = '?mark_read=' + id + '&filter=<?php echo $filter; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?>';
                    }
                })
                .catch(error => {
                    document.getElementById('messageDetails').innerHTML = `
                        <div style="color: #dc3545;">
                            <p>Error loading message details.</p>
                        </div>
                    `;
                    document.getElementById('viewModal').style.display = 'flex';
                });
        }
        
        function editNotes(id) {
            // Get current notes (you should implement AJAX endpoint)
            let currentNotes = '';
            document.querySelectorAll('.message-item').forEach(item => {
                const notesDiv = item.querySelector('.admin-notes');
                if(notesDiv && item.querySelector('button[onclick*="' + id + '"]')) {
                    const notesText = notesDiv.querySelector('br') ? 
                        notesDiv.innerHTML.split('<br>').slice(1).join('<br>').replace(/<[^>]+>/g, '').trim() : '';
                    currentNotes = notesText.replace(/&nbsp;/g, ' ').replace(/&amp;/g, '&');
                }
            });
            
            document.getElementById('messageId').value = id;
            document.getElementById('adminNotes').value = currentNotes;
            document.getElementById('notesModal').style.display = 'flex';
        }
        
        function replyMessage(email, subject) {
            const mailtoLink = `mailto:${email}?subject=Re: ${encodeURIComponent(subject)}`;
            window.open(mailtoLink, '_blank');
        }
        
        function deleteMessage(id, name) {
            if(confirm(`Are you sure you want to delete message from "${name}"? This action cannot be undone.`)) {
                window.location.href = '?delete=' + id + '&filter=<?php echo $filter; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?>';
            }
        }
        
        function closeModal() {
            document.getElementById('viewModal').style.display = 'none';
            document.getElementById('notesModal').style.display = 'none';
        }
        
        // Close modal when clicking outside or pressing ESC
        window.onclick = function(event) {
            if (event.target.classList.contains('modal')) {
                closeModal();
            }
        }
        
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeModal();
            }
        });
        
        // Auto-close success message after 5 seconds
        setTimeout(() => {
            const successMsg = document.querySelector('[style*="background: #d4edda"]');
            if(successMsg) {
                successMsg.style.opacity = '0';
                successMsg.style.transition = 'opacity 0.5s ease';
                setTimeout(() => successMsg.remove(), 500);
            }
        }, 5000);
        
        // Real-time search (optional)
        const searchInput = document.querySelector('input[name="search"]');
        if(searchInput) {
            let searchTimer;
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimer);
                searchTimer = setTimeout(() => {
                    this.form.submit();
                }, 500);
            });
        }
    </script>
</body>
</html>