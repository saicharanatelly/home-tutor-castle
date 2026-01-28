<?php
session_start();
include '../../includes/config.php';

if (!isset($_SESSION['admin_logged_in'])) {
    exit('Unauthorized access');
}

$id = mysqli_real_escape_string($conn, $_GET['id']);
$message = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM contacts WHERE id = '$id'"));

if ($_GET['notes'] ?? false) {
    header('Content-Type: application/json');
    echo json_encode($message);
    exit;
}

if ($message): ?>
<div style="line-height: 1.8;">
    <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1.5rem; padding-bottom: 1rem; border-bottom: 1px solid #eee;">
        <div>
            <h4 style="margin: 0 0 0.5rem 0; color: var(--dark-gray);">
                <?php echo htmlspecialchars($message['name']); ?>
            </h4>
            <div style="color: #666; font-size: 0.9rem;">
                <div><i class="fas fa-envelope"></i> <?php echo htmlspecialchars($message['email']); ?></div>
                <div><i class="fas fa-phone"></i> <?php echo htmlspecialchars($message['phone']); ?></div>
            </div>
        </div>
        <div>
            <span style="padding: 0.3rem 0.8rem; border-radius: 20px; background: <?php 
                $colors = [
                    'unread' => '#d1ecf1',
                    'read' => '#d4edda',
                    'replied' => '#cce5ff'
                ];
                echo $colors[$message['status']] ?? '#f8f9fa';
            ?>; color: #000;">
                <?php echo ucfirst($message['status']); ?>
            </span>
        </div>
    </div>
    
    <div style="margin-bottom: 1.5rem;">
        <strong style="display: block; margin-bottom: 0.5rem; color: var(--dark-gray);">Subject:</strong>
        <div style="background: #f8f9fa; padding: 1rem; border-radius: 5px;">
            <?php echo htmlspecialchars($message['subject']); ?>
        </div>
    </div>
    
    <div style="margin-bottom: 1.5rem;">
        <strong style="display: block; margin-bottom: 0.5rem; color: var(--dark-gray);">Message:</strong>
        <div style="background: #f8f9fa; padding: 1rem; border-radius: 5px; white-space: pre-wrap;">
            <?php echo htmlspecialchars($message['message']); ?>
        </div>
    </div>
    
    <div style="color: #666; font-size: 0.9rem; margin-bottom: 1rem;">
        <i class="fas fa-clock"></i> Submitted on: <?php echo date('d M Y H:i', strtotime($message['created_at'])); ?>
    </div>
    
    <?php if($message['admin_notes']): ?>
    <div style="background: #fff3cd; padding: 1rem; border-radius: 5px; margin-top: 1rem;">
        <strong style="display: block; margin-bottom: 0.5rem; color: var(--dark-gray);">Admin Notes:</strong>
        <div style="white-space: pre-wrap;"><?php echo htmlspecialchars($message['admin_notes']); ?></div>
    </div>
    <?php endif; ?>
    
    <div style="margin-top: 1.5rem; display: flex; gap: 1rem;">
        <a href="mailto:<?php echo htmlspecialchars($message['email']); ?>" 
           style="padding: 0.5rem 1rem; background: var(--primary-orange); color: white; text-decoration: none; border-radius: 5px;">
            <i class="fas fa-reply"></i> Reply via Email
        </a>
        <a href="?mark_replied=<?php echo $message['id']; ?>" 
           style="padding: 0.5rem 1rem; background: #28a745; color: white; text-decoration: none; border-radius: 5px;">
            <i class="fas fa-check"></i> Mark as Replied
        </a>
    </div>
</div>
<?php else: ?>
<p>Message not found.</p>
<?php endif; ?>