<?php
// config.php - Hostinger Compatible Version
// Auto-detects environment and configures accordingly

// Determine if we're on localhost or Hostinger
function isLocalhost() {
    $whitelist = array('127.0.0.1', '::1', 'localhost');
    return in_array($_SERVER['REMOTE_ADDR'], $whitelist) || 
           strpos($_SERVER['HTTP_HOST'], 'localhost') !== false ||
           strpos($_SERVER['HTTP_HOST'], '127.0.0.1') !== false;
}

// Check if constants are already defined before defining them
if (!defined('HTC_CONFIG_LOADED')) {
    define('HTC_CONFIG_LOADED', true);
    
    // ====================
    // ENVIRONMENT DETECTION
    // ====================
    $is_local = isLocalhost();
    
    // Debug mode (true on local, false on production)
    if (!defined('DEBUG_MODE')) {
        define('DEBUG_MODE', $is_local);
    }
    
    // ====================
    // DATABASE CONFIGURATION
    // ====================
    if ($is_local) {
        // Local development configuration
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
    } else {
        // Hostinger production configuration
        if (!defined('DB_HOST')) {
            define('DB_HOST', 'localhost'); // Hostinger uses localhost
        }
        if (!defined('DB_USER')) {
            define('DB_USER', 'u242249309_htc'); // Your Hostinger username
        }
        if (!defined('DB_PASS')) {
            define('DB_PASS', 'Htc@321$'); // Your Hostinger database 
        }
        if (!defined('DB_NAME')) {
            define('DB_NAME', 'u242249309_htc'); // Your Hostinger database name
        }
    }
    
    // ====================
    // URL CONFIGURATION
    // ====================
    if (!defined('BASE_URL')) {
        if ($is_local) {
            define('BASE_URL', 'http://localhost/home-castle-tutor/');
        } else {
            // Auto-detect protocol and domain
            $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
            $domain = $_SERVER['HTTP_HOST'];
            $base_path = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
            
            // Remove 'public_html' from path if present
            $base_path = str_replace('/public_html', '', $base_path);
            
            // Ensure path ends with slash
            $base_path = ($base_path === '') ? '/' : $base_path . '/';
            
            define('BASE_URL', $protocol . $domain . $base_path);
        }
    }
    
    // ====================
    // SECURITY SETTINGS
    // ====================
    // Set timezone
    date_default_timezone_set('Asia/Kolkata');
    
    // Error reporting based on environment
    if (DEBUG_MODE) {
        // Development mode - show all errors
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        ini_set('log_errors', 1);
    } else {
        // Production mode - hide errors from users
        error_reporting(0);
        ini_set('display_errors', 0);
        ini_set('log_errors', 1);
        
        // Log errors to file on production
        ini_set('error_log', dirname(__DIR__) . '/logs/php_errors.log');
        
        // Create logs directory if it doesn't exist
        if (!is_dir(dirname(__DIR__) . '/logs')) {
            @mkdir(dirname(__DIR__) . '/logs', 0755, true);
        }
    }
    
    // ====================
    // SESSION MANAGEMENT
    // ====================
    // Check if session is already started
    if (session_status() === PHP_SESSION_NONE) {
        // Secure session settings
        ini_set('session.cookie_httponly', 1);
        ini_set('session.use_only_cookies', 1);
        ini_set('session.cookie_secure', !$is_local); // HTTPS only on production
        
        session_start();
        
        // Regenerate session ID periodically for security
        if (!isset($_SESSION['CREATED'])) {
            $_SESSION['CREATED'] = time();
        } elseif (time() - $_SESSION['CREATED'] > 1800) {
            // 30 minutes
            session_regenerate_id(true);
            $_SESSION['CREATED'] = time();
        }
    }
    
    // ====================
    // DATABASE CONNECTION
    // ====================
    try {
        $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        
        if (!$conn) {
            throw new Exception("Database connection failed: " . mysqli_connect_error());
        }
        
        // Set connection charset to UTF-8
        mysqli_set_charset($conn, "utf8mb4");
        
        // Set connection timeout
        mysqli_options($conn, MYSQLI_OPT_CONNECT_TIMEOUT, 10);
        
        // Set timezone for database connection
        mysqli_query($conn, "SET time_zone = '+05:30'");
        
        // Set SQL mode (compatible with Hostinger)
        mysqli_query($conn, "SET sql_mode = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION'");
        
    } catch (Exception $e) {
        // Log error
        error_log("Database Error [" . date('Y-m-d H:i:s') . "]: " . $e->getMessage());
        
        // Display user-friendly message
        if (php_sapi_name() !== 'cli') {
            http_response_code(500);
            
            if (DEBUG_MODE) {
                // Show detailed error in development
                echo "<h2>Database Connection Error</h2>";
                echo "<p><strong>Error:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
                echo "<p><strong>Host:</strong> " . DB_HOST . "</p>";
                echo "<p><strong>User:</strong> " . DB_USER . "</p>";
                echo "<p><strong>Database:</strong> " . DB_NAME . "</p>";
                echo "<p><strong>Environment:</strong> " . ($is_local ? 'Local' : 'Production') . "</p>";
            } else {
                // Generic error in production
                echo '<!DOCTYPE html>
                <html lang="en">
                <head>
                    <meta charset="UTF-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <title>Service Temporarily Unavailable</title>
                    <style>
                        body { font-family: Arial, sans-serif; text-align: center; padding: 50px; background: #f8f9fa; }
                        .container { max-width: 600px; margin: 0 auto; }
                        h1 { color: #dc3545; }
                        p { color: #6c757d; }
                        .btn { display: inline-block; margin-top: 20px; padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 5px; }
                    </style>
                </head>
                <body>
                    <div class="container">
                        <h1>⚠️ Service Temporarily Unavailable</h1>
                        <p>We\'re currently experiencing technical difficulties. Our team has been notified and is working to resolve the issue.</p>
                        <p>Please try again in a few minutes.</p>
                        <a href="' . BASE_URL . '" class="btn">Refresh Page</a>
                    </div>
                </body>
                </html>';
            }
        } else {
            echo "Database Error: " . $e->getMessage() . "\n";
        }
        exit;
    }
}

// Alternative version without the global flag (backward compatibility)
if (!isset($conn)) {
    try {
        $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        
        if (!$conn) {
            die("Database connection failed: " . mysqli_connect_error());
        }
    } catch (Exception $e) {
        die("Critical Database Error: " . $e->getMessage());
    }
}

// ====================
// HELPER FUNCTIONS
// ====================

/**
 * Get the current environment
 */
function getEnvironment() {
    return isLocalhost() ? 'development' : 'production';
}

/**
 * Log debug information
 */
function debugLog($message) {
    if (DEBUG_MODE) {
        error_log("[DEBUG] " . $message);
    }
}

/**
 * Sanitize input data
 */
function sanitize($input) {
    global $conn;
    if (isset($conn)) {
        return mysqli_real_escape_string($conn, trim($input));
    }
    return htmlspecialchars(trim($input));
}

/**
 * Redirect to a URL
 */
function redirect($url) {
    if (!headers_sent()) {
        header('Location: ' . $url);
        exit;
    } else {
        echo '<script>window.location.href="' . $url . '";</script>';
        exit;
    }
}

/**
 * Generate CSRF token
 */
function generateCsrfToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Validate CSRF token
 */
function validateCsrfToken($token) {
    if (!empty($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token)) {
        return true;
    }
    return false;
}
?>