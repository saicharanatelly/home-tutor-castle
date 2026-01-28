<?php
// includes/session.php

function startSessionIfNotStarted() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
        
        // Set default session variables if needed
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        
        // Regenerate session ID periodically for security
        if (!isset($_SESSION['last_regeneration'])) {
            $_SESSION['last_regeneration'] = time();
        } elseif (time() - $_SESSION['last_regeneration'] > 1800) { // 30 minutes
            session_regenerate_id(true);
            $_SESSION['last_regeneration'] = time();
        }
    }
}

// Call this function wherever you need session
startSessionIfNotStarted();
?>