<?php
// config.php - Updated with constant protection
// Check if constants are already defined before defining them

if (!defined('HTC_CONFIG_LOADED')) {
    define('HTC_CONFIG_LOADED', true);
    
    // Debug mode (set to false in production)
    if (!defined('DEBUG_MODE')) {
        define('DEBUG_MODE', true);
    }
    
    // Database configuration
    if (!defined('DB_HOST')) {
        define('DB_HOST', 'localhost');
    }
    
    if (!defined('DB_USER')) {
        define('DB_USER', 'root');
    }
    
    if (!defined('DB_PASS')) {
        define('DB_PASS', '');
    }
    
    if (!defined('DB_NAME')) {
        define('DB_NAME', 'home_castle_tutor');
    }
    
    // Base URL configuration
    if (!defined('BASE_URL')) {
        define('BASE_URL', 'http://localhost/home-castle-tutor/');
    }
    
    // Set timezone
    date_default_timezone_set('Asia/Kolkata');
    
    // Error reporting (development only - disable in production)
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    
    // Check if session is already started
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    // Create database connection with error handling
    try {
        $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        
        if (!$conn) {
            throw new Exception("Database connection failed: " . mysqli_connect_error());
        }
        
        // Set connection charset to UTF-8
        mysqli_set_charset($conn, "utf8mb4");
        
        // Optional: Set connection timeout
        mysqli_options($conn, MYSQLI_OPT_CONNECT_TIMEOUT, 10);
        
    } catch (Exception $e) {
        // Log error and display user-friendly message
        error_log("Database Error: " . $e->getMessage());
        
        // Custom error page or message
        if (php_sapi_name() !== 'cli') {
            http_response_code(500);
            echo "<h2>Database Connection Error</h2>";
            echo "<p>We're experiencing technical difficulties. Please try again later.</p>";
            if (defined('DEBUG_MODE') && DEBUG_MODE) {
                echo "<small>Debug: " . htmlspecialchars($e->getMessage()) . "</small>";
            }
        } else {
            echo "Database Error: " . $e->getMessage() . "\n";
        }
        exit;
    }
}

// Alternative version without the global flag (backward compatibility)
// This ensures the connection is always available
if (!isset($conn)) {
    $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    if (!$conn) {
        die("Database connection failed: " . mysqli_connect_error());
    }
}
?>