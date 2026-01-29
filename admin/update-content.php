<?php
session_start();
include '../includes/config.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit();
}

// Handle content update
$success = $error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $page = mysqli_real_escape_string($conn, $_POST['page']);
    $content = mysqli_real_escape_string($conn, $_POST['content']);
    
    // Check if content exists
    $check_query = "SELECT * FROM website_content WHERE page_name = '$page'";
    $check_result = mysqli_query($conn, $check_query);
    
    if (mysqli_num_rows($check_result) > 0) {
        // Update existing content
        $sql = "UPDATE website_content SET content = '$content', updated_at = NOW() WHERE page_name = '$page'";
    } else {
        // Insert new content
        $sql = "INSERT INTO website_content (page_name, content) VALUES ('$page', '$content')";
    }
    
    if (mysqli_query($conn, $sql)) {
        $_SESSION['success'] = "Content updated successfully!";
        header('Location: update-content.php');
        exit();
    } else {
        $error = "Error updating content: " . mysqli_error($conn);
    }
}

// Fetch all pages content
$pages_query = "SELECT * FROM website_content ORDER BY page_name";
$pages_result = mysqli_query($conn, $pages_query);

// Get current page content if editing
$current_content = '';
if (isset($_GET['page'])) {
    $page_name = mysqli_real_escape_string($conn, $_GET['page']);
    $content_query = "SELECT * FROM website_content WHERE page_name = '$page_name'";
    $content_result = mysqli_query($conn, $content_query);
    if (mysqli_num_rows($content_result) > 0) {
        $content_row = mysqli_fetch_assoc($content_result);
        $current_content = $content_row['content'];
    }
}

// Default pages if not in database
$default_pages = ['home', 'about', 'services', 'contact', 'privacy', 'terms'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Content - Admin Panel</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-purple: #3B0A6A;
            --royal-violet: #5E2B97;
            --magenta-pink: #C13C91;
            --warm-orange: #F6A04D;
            --dark-gray: #333333;
            --light-gray: #f8f9fa;
            --medium-gray: #e9ecef;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--light-gray);
            color: var(--dark-gray);
            line-height: 1.6;
        }
        
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
            color: var(--primary-purple);
        }
        
        .dashboard-container {
            display: flex;
            min-height: 100vh;
        }
        
        /* Sidebar Styles */
        .sidebar {
            width: 250px;
            background: linear-gradient(180deg, var(--primary-purple) 0%, var(--royal-violet) 100%);
            color: white;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            z-index: 1000;
            box-shadow: 3px 0 15px rgba(0,0,0,0.1);
        }
        
        .sidebar-header {
            padding: 25px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            text-align: center;
        }
        
        .sidebar-header h2 {
            color: white;
            font-size: 22px;
            margin-bottom: 5px;
        }
        
        .sidebar-menu {
            padding: 20px 0;
        }
        
        .menu-item {
            padding: 15px 25px;
            display: flex;
            align-items: center;
            gap: 12px;
            color: rgba(255,255,255,0.9);
            text-decoration: none;
            font-family: 'Inter', sans-serif;
            font-weight: 500;
            transition: all 0.3s ease;
            border-left: 4px solid transparent;
        }
        
        .menu-item:hover {
            background: rgba(255,255,255,0.1);
            color: white;
            border-left-color: var(--magenta-pink);
        }
        
        .menu-item.active {
            background: rgba(255,255,255,0.15);
            color: white;
            border-left-color: var(--warm-orange);
        }
        
        .main-content {
            flex: 1;
            margin-left: 250px;
            padding: 30px;
            min-height: 100vh;
        }
        
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid var(--medium-gray);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--magenta-pink), var(--royal-violet));
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-family: 'Poppins', sans-serif;
            font-weight: 500;
            font-size: 15px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            text-decoration: none;
            box-shadow: 0 4px 12px rgba(195, 60, 145, 0.25);
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(195, 60, 145, 0.35);
        }
        
        .alert {
            padding: 16px 20px;
            border-radius: 8px;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 12px;
            border-left: 4px solid transparent;
        }
        
        .alert-success {
            background: #d4edda;
            color: #155724;
            border-left-color: #28a745;
        }
        
        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border-left-color: #dc3545;
        }
        
        .content-wrapper {
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 30px;
        }
        
        .pages-list {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            height: fit-content;
        }
        
        .pages-list h3 {
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid var(--medium-gray);
        }
        
        .page-link {
            display: block;
            padding: 12px 15px;
            margin-bottom: 10px;
            background: var(--light-gray);
            border-radius: 8px;
            text-decoration: none;
            color: var(--dark-gray);
            transition: all 0.3s ease;
            border-left: 4px solid transparent;
        }
        
        .page-link:hover {
            background: var(--medium-gray);
            border-left-color: var(--royal-violet);
        }
        
        .page-link.active {
            background: linear-gradient(135deg, var(--light-gray), #f0f0f0);
            border-left-color: var(--magenta-pink);
            font-weight: 500;
        }
        
        .editor-container {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: var(--dark-gray);
            font-weight: 500;
            font-size: 14px;
        }
        
        .form-control {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid var(--medium-gray);
            border-radius: 8px;
            font-size: 14px;
            font-family: 'Inter', sans-serif;
            transition: all 0.3s ease;
            background: white;
        }
        
        textarea.form-control {
            min-height: 300px;
            resize: vertical;
        }
        
        .form-control:focus {
            outline: none;
            border-color: var(--royal-violet);
            box-shadow: 0 0 0 3px rgba(94, 43, 151, 0.1);
        }
        
        .editor-toolbar {
            background: var(--light-gray);
            padding: 10px;
            border: 2px solid var(--medium-gray);
            border-bottom: none;
            border-radius: 8px 8px 0 0;
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        
        .editor-btn {
            background: white;
            border: 1px solid var(--medium-gray);
            padding: 8px 12px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .editor-btn:hover {
            background: var(--light-gray);
        }
        
        .mobile-menu-toggle {
            display: none;
            background: var(--primary-purple);
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 20px;
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 1001;
        }
        
        @media (max-width: 1200px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }
            
            .sidebar.active {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .mobile-menu-toggle {
                display: block;
            }
        }
        
        @media (max-width: 992px) {
            .content-wrapper {
                grid-template-columns: 1fr;
            }
        }
        
        @media (max-width: 768px) {
            .main-content {
                padding: 20px;
            }
            
            .page-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }
        }
    </style>
</head>
<body>
    <!-- Mobile Menu Toggle -->
    <button class="mobile-menu-toggle" onclick="toggleSidebar()">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <h2>Home Castle Tutor</h2>
            <p>Admin Panel</p>
        </div>
        
        <div class="sidebar-menu">
            <?php
            $current_page = basename($_SERVER['PHP_SELF']);
            $menu_items = [
                ['dashboard.php', 'fa-tachometer-alt', 'Dashboard'],
                ['student-requests.php', 'fa-user-graduate', 'Student Requests'],
                ['manage-tutors.php', 'fa-chalkboard-teacher', 'Manage Tutors'],
                ['manage-blogs.php', 'fa-blog', 'Manage Blogs'],
                ['contact-messages.php', 'fa-envelope', 'Contact Messages'],
                ['update-content.php', 'fa-edit', 'Update Content'],
                ['update-banners.php', 'fa-images', 'Update Banners'],
                ['reports.php', 'fa-chart-bar', 'Reports'],
                ['settings.php', 'fa-cog', 'Settings']
            ];
            
            foreach ($menu_items as $item) {
                $active = $current_page == $item[0] ? 'active' : '';
                echo '<a href="' . $item[0] . '" class="menu-item ' . $active . '">
                    <i class="fas ' . $item[1] . '"></i>
                    <span>' . $item[2] . '</span>
                </a>';
            }
            ?>
        </div>
    </div>
    
    <!-- Main Content -->
    <div class="main-content">
        <div class="page-header">
            <h1><i class="fas fa-edit" style="color: var(--royal-violet); margin-right: 12px;"></i> Update Website Content</h1>
        </div>
        
        <?php if(isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>
        
        <?php if(isset($error)): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
            </div>
        <?php endif; ?>
        
        <div class="content-wrapper">
            <!-- Pages List -->
            <div class="pages-list">
                <h3>Select Page</h3>
                <?php 
                $current = isset($_GET['page']) ? $_GET['page'] : 'home';
                foreach($default_pages as $page): 
                    $page_name = ucfirst($page);
                    $active = $current == $page ? 'active' : '';
                ?>
                    <a href="?page=<?php echo $page; ?>" class="page-link <?php echo $active; ?>">
                        <i class="fas fa-file-alt"></i> <?php echo $page_name; ?> Page
                    </a>
                <?php endforeach; ?>
            </div>
            
            <!-- Content Editor -->
            <div class="editor-container">
                <form method="POST">
                    <div class="form-group">
                        <label>Page</label>
                        <select name="page" class="form-control" required>
                            <?php foreach($default_pages as $page): ?>
                                <option value="<?php echo $page; ?>" <?php echo ($current == $page) ? 'selected' : ''; ?>>
                                    <?php echo ucfirst($page); ?> Page
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Content</label>
                        <div class="editor-toolbar">
                            <button type="button" class="editor-btn" onclick="formatText('bold')"><i class="fas fa-bold"></i></button>
                            <button type="button" class="editor-btn" onclick="formatText('italic')"><i class="fas fa-italic"></i></button>
                            <button type="button" class="editor-btn" onclick="formatText('underline')"><i class="fas fa-underline"></i></button>
                            <button type="button" class="editor-btn" onclick="formatText('insertUnorderedList')"><i class="fas fa-list-ul"></i></button>
                            <button type="button" class="editor-btn" onclick="formatText('insertOrderedList')"><i class="fas fa-list-ol"></i></button>
                            <button type="button" class="editor-btn" onclick="formatText('createLink')"><i class="fas fa-link"></i></button>
                        </div>
                        <textarea name="content" class="form-control" required><?php echo htmlspecialchars($current_content); ?></textarea>
                    </div>
                    
                    <div style="display: flex; gap: 15px; margin-top: 30px;">
                        <button type="submit" class="btn-primary" style="flex: 1;">
                            <i class="fas fa-save"></i> Save Content
                        </button>
                        <button type="button" class="btn-primary" onclick="previewContent()" style="background: #17a2b8;">
                            <i class="fas fa-eye"></i> Preview
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script>
        // Sidebar Toggle
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('active');
        }
        
        // Text Formatting
        function formatText(command) {
            const textarea = document.querySelector('textarea[name="content"]');
            const start = textarea.selectionStart;
            const end = textarea.selectionEnd;
            const selectedText = textarea.value.substring(start, end);
            
            if (command === 'createLink') {
                const url = prompt('Enter URL:');
                if (url) {
                    const link = `<a href="${url}" target="_blank">${selectedText || 'Link'}</a>`;
                    textarea.value = textarea.value.substring(0, start) + link + textarea.value.substring(end);
                }
            } else {
                document.execCommand(command, false, null);
            }
            textarea.focus();
        }
        
        // Preview Content
        function previewContent() {
            const content = document.querySelector('textarea[name="content"]').value;
            const previewWindow = window.open('', 'Preview', 'width=800,height=600');
            previewWindow.document.write(`
                <!DOCTYPE html>
                <html>
                <head>
                    <title>Content Preview</title>
                    <style>
                        body { font-family: Arial, sans-serif; padding: 20px; line-height: 1.6; }
                        .preview-content { max-width: 800px; margin: 0 auto; }
                    </style>
                </head>
                <body>
                    <div class="preview-content">${content}</div>
                </body>
                </html>
            `);
            previewWindow.document.close();
        }
    </script>
</body>
</html>