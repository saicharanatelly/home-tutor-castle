<?php
// blogs.php - User Blog Page
include 'includes/config.php';
include 'includes/header.php';

// Get pagination
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$per_page = 9;
$offset = ($page - 1) * $per_page;

// Get category filter
$category = isset($_GET['category']) ? mysqli_real_escape_string($conn, $_GET['category']) : '';

// Get search query
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';

// Build query for published posts
$query_conditions = "status = 'published' AND published_at IS NOT NULL";

if (!empty($category)) {
    $query_conditions .= " AND category = '$category'";
}

if (!empty($search)) {
    $query_conditions .= " AND (title LIKE '%$search%' OR content LIKE '%$search%' OR excerpt LIKE '%$search%')";
}

// Get total count
$count_query = "SELECT COUNT(*) as total FROM blog_posts WHERE $query_conditions";
$count_result = mysqli_query($conn, $count_query);
$total_row = mysqli_fetch_assoc($count_result);
$total = $total_row['total'];
$total_pages = ceil($total / $per_page);

// Get posts with pagination
$query = "SELECT * FROM blog_posts WHERE $query_conditions ORDER BY published_at DESC LIMIT $per_page OFFSET $offset";
$result = mysqli_query($conn, $query);

// Get categories
$categories_result = mysqli_query($conn, "SELECT category, COUNT(*) as count FROM blog_posts WHERE status = 'published' GROUP BY category ORDER BY count DESC");
$categories = [];
while($cat = mysqli_fetch_assoc($categories_result)) {
    $categories[] = $cat;
}

// Get popular posts
$popular_query = "SELECT * FROM blog_posts WHERE status = 'published' ORDER BY views DESC LIMIT 5";
$popular_result = mysqli_query($conn, $popular_query);

// Increment views for single post view
if (isset($_GET['slug'])) {
    $slug = mysqli_real_escape_string($conn, $_GET['slug']);
    mysqli_query($conn, "UPDATE blog_posts SET views = views + 1 WHERE slug = '$slug'");
    
    // Get single post
    $single_query = "SELECT * FROM blog_posts WHERE slug = '$slug' AND status = 'published'";
    $single_result = mysqli_query($conn, $single_query);
    $post = mysqli_fetch_assoc($single_result);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($post) ? htmlspecialchars($post['title']) . ' - Home Castle Tutor' : 'Blog - Home Castle Tutor'; ?></title>
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
            line-height: 1.6;
            color: var(--dark-gray);
            background-color: white;
        }
        
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
            color: var(--primary-purple);
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        /* Header 
        .header {
            background: linear-gradient(135deg, var(--primary-purple) 0%, var(--royal-violet) 100%);
            color: white;
            padding: 80px 0;
            text-align: center;
            margin-bottom: 60px;
        }
        
        .header h1 {
            color: white;
            font-size: 48px;
            margin-bottom: 20px;
        }
        
        .header p {
            font-size: 18px;
            opacity: 0.9;
            max-width: 600px;
            margin: 0 auto;
        }
        */
        /* Blog Grid */
        .blog-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 30px;
            margin-bottom: 60px;
        }
        
        .blog-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        
        .blog-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.12);
        }
        
        .blog-image {
            height: 220px;
            width: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }
        
        .blog-card:hover .blog-image {
            transform: scale(1.05);
        }
        
        .blog-content {
            padding: 25px;
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        
        .blog-category {
            display: inline-block;
            background: var(--light-gray);
            color: var(--royal-violet);
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 15px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .blog-title {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 15px;
            line-height: 1.4;
        }
        
        .blog-excerpt {
            color: #666;
            font-size: 15px;
            margin-bottom: 20px;
            flex: 1;
        }
        
        .blog-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: auto;
            padding-top: 20px;
            border-top: 1px solid var(--medium-gray);
        }
        
        .blog-date {
            font-size: 14px;
            color: #999;
        }
        
        .blog-date i {
            margin-right: 5px;
        }
        
        .read-more {
            color: var(--magenta-pink);
            text-decoration: none;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            transition: all 0.3s ease;
        }
        
        .read-more:hover {
            color: var(--primary-purple);
            gap: 8px;
        }
        
        /* Blog Layout */
        .blog-layout {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 40px;
            margin-bottom: 60px;
        }
        
        /* Sidebar */
        .sidebar-widget {
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            margin-bottom: 30px;
        }
        
        .widget-title {
            font-size: 18px;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid var(--medium-gray);
        }
        
        .category-list {
            list-style: none;
        }
        
        .category-list li {
            margin-bottom: 12px;
        }
        
        .category-list a {
            display: flex;
            justify-content: space-between;
            color: var(--dark-gray);
            text-decoration: none;
            padding: 10px 15px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        
        .category-list a:hover {
            background: var(--light-gray);
            color: var(--royal-violet);
        }
        
        .category-count {
            background: var(--light-gray);
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 500;
        }
        
        .popular-post {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
            padding-bottom: 20px;
            border-bottom: 1px solid var(--medium-gray);
        }
        
        .popular-post:last-child {
            margin-bottom: 0;
            padding-bottom: 0;
            border-bottom: none;
        }
        
        .popular-image {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
            flex-shrink: 0;
        }
        
        .popular-content h4 {
            font-size: 15px;
            margin-bottom: 8px;
            line-height: 1.4;
        }
        
        .popular-date {
            font-size: 12px;
            color: #999;
        }
        
        /* Search */
        .search-box {
            position: relative;
        }
        
        .search-input {
            width: 100%;
            padding: 12px 50px 12px 20px;
            border: 2px solid var(--medium-gray);
            border-radius: 8px;
            font-size: 15px;
            transition: all 0.3s ease;
        }
        
        .search-input:focus {
            outline: none;
            border-color: var(--royal-violet);
            box-shadow: 0 0 0 3px rgba(94, 43, 151, 0.1);
        }
        
        .search-button {
            position: absolute;
            right: 5px;
            top: 50%;
            transform: translateY(-50%);
            background: var(--royal-violet);
            color: white;
            border: none;
            width: 40px;
            height: 40px;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .search-button:hover {
            background: var(--primary-purple);
        }
        
        /* Single Post */
        .single-post {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        }
        
        .post-header {
            padding: 50px 40px 30px;
            text-align: center;
        }
        
        .post-title {
            font-size: 36px;
            margin-bottom: 20px;
            line-height: 1.3;
        }
        
        .post-meta {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-bottom: 30px;
            color: #666;
            font-size: 14px;
        }
        
        .post-meta span {
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .post-image {
            width: 100%;
            max-height: 500px;
            object-fit: cover;
            margin-bottom: 40px;
        }
        
        .post-content {
            padding: 0 40px 40px;
            font-size: 16px;
            line-height: 1.8;
        }
        
        .post-content h2, .post-content h3 {
            margin: 30px 0 15px;
        }
        
        .post-content p {
            margin-bottom: 20px;
        }
        
        .post-content img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            margin: 20px 0;
        }
        
        .post-content ul, .post-content ol {
            margin: 20px 0;
            padding-left: 30px;
        }
        
        .post-content li {
            margin-bottom: 10px;
        }
        
        .post-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 40px;
            padding-top: 30px;
            border-top: 1px solid var(--medium-gray);
        }
        
        .tag {
            background: var(--light-gray);
            color: var(--dark-gray);
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 14px;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .tag:hover {
            background: var(--royal-violet);
            color: white;
        }
        
        /* Pagination */
        .pagination {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin: 60px 0;
        }
        
        .page-link {
            padding: 12px 18px;
            border: 2px solid var(--medium-gray);
            background: white;
            border-radius: 8px;
            text-decoration: none;
            color: var(--dark-gray);
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .page-link:hover {
            border-color: var(--royal-violet);
            color: var(--royal-violet);
        }
        
        .page-link.active {
            background: var(--royal-violet);
            color: white;
            border-color: var(--royal-violet);
        }
        
        /* No Results */
        .no-results {
            text-align: center;
            padding: 60px 20px;
            grid-column: 1 / -1;
        }
        
        .no-results i {
            font-size: 60px;
            color: #ddd;
            margin-bottom: 20px;
        }
        
        .no-results h3 {
            color: var(--primary-purple);
            margin-bottom: 10px;
        }
        
        /* Responsive */
        @media (max-width: 992px) {
            .blog-layout {
                grid-template-columns: 1fr;
            }
            
            .blog-grid {
                grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            }
            
            .post-title {
                font-size: 30px;
            }
        }
        
        @media (max-width: 768px) {
            .header {
                padding: 60px 0;
            }
            
            .header h1 {
                font-size: 36px;
            }
            
            .blog-grid {
                grid-template-columns: 1fr;
            }
            
            .post-header {
                padding: 30px 20px;
            }
            
            .post-content {
                padding: 0 20px 30px;
            }
        }
    </style>
</head>
<body>
    <?php if(isset($post)): ?>
    <!-- Single Blog Post -->
    <div class="container" style="padding-top: 40px;">
        <article class="single-post">
            <div class="post-header">
                <h1 class="post-title"><?php echo htmlspecialchars($post['title']); ?></h1>
                <div class="post-meta">
                    <span><i class="fas fa-user"></i> <?php echo htmlspecialchars($post['author']); ?></span>
                    <span><i class="fas fa-calendar"></i> <?php echo date('F j, Y', strtotime($post['published_at'])); ?></span>
                    <span><i class="fas fa-eye"></i> <?php echo number_format($post['views']); ?> views</span>
                    <span><i class="fas fa-folder"></i> <?php echo htmlspecialchars($post['category']); ?></span>
                </div>
            </div>
            
            <?php if($post['featured_image']): ?>
                <img src="<?php echo htmlspecialchars($post['featured_image']); ?>" alt="<?php echo htmlspecialchars($post['title']); ?>" class="post-image">
            <?php endif; ?>
            
            <div class="post-content">
                <?php echo nl2br(htmlspecialchars_decode($post['content'])); ?>
                
                <?php if($post['tags']): ?>
                <div class="post-tags">
                    <?php 
                    $tags = explode(',', $post['tags']);
                    foreach($tags as $tag):
                        $tag = trim($tag);
                        if(!empty($tag)):
                    ?>
                        <a href="blogs.php?search=<?php echo urlencode($tag); ?>" class="tag"><?php echo htmlspecialchars($tag); ?></a>
                    <?php 
                        endif;
                    endforeach; 
                    ?>
                </div>
                <?php endif; ?>
            </div>
        </article>
        
        <!-- Back to Blogs -->
        <div style="text-align: center; margin: 40px 0;">
            <a href="blogs.php" class="read-more" style="font-size: 16px;">
                <i class="fas fa-arrow-left"></i> Back to All Blogs
            </a>
        </div>
    </div>
    
    <?php else: ?>
    <!-- Blog List Page -->
    <div class="header">
        <div class="container">
            <h1>Our Blog</h1>
            <p>Stay updated with the latest tips, news, and insights about home tutoring and education</p>
        </div>
    </div>
    
    <div class="container">
        <div class="blog-layout">
            <!-- Main Content -->
            <main>
                <!-- Search and Filters -->
                <div style="display: flex; gap: 20px; margin-bottom: 40px; flex-wrap: wrap;">
                    <form method="GET" class="search-box" style="flex: 1;">
                        <input type="text" name="search" class="search-input" placeholder="Search blog posts..." value="<?php echo htmlspecialchars($search); ?>">
                        <button type="submit" class="search-button">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>
                    
                    <?php if(!empty($category)): ?>
                        <a href="blogs.php" class="btn-primary" style="padding: 12px 25px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; background: var(--magenta-pink); color: white; border-radius: 8px; font-weight: 500;">
                            <i class="fas fa-times"></i> Clear Filter
                        </a>
                    <?php endif; ?>
                </div>
                
                <!-- Blog Grid -->
                <?php if(mysqli_num_rows($result) > 0): ?>
                    <div class="blog-grid">
                        <?php while($post = mysqli_fetch_assoc($result)): ?>
                        <article class="blog-card">
                            <?php if($post['featured_image']): ?>
                                <img src="<?php echo htmlspecialchars($post['featured_image']); ?>" alt="<?php echo htmlspecialchars($post['title']); ?>" class="blog-image">
                            <?php else: ?>
                                <div style="height: 220px; background: linear-gradient(135deg, var(--royal-violet), var(--magenta-pink)); display: flex; align-items: center; justify-content: center; color: white;">
                                    <i class="fas fa-image fa-3x"></i>
                                </div>
                            <?php endif; ?>
                            
                            <div class="blog-content">
                                <span class="blog-category"><?php echo htmlspecialchars($post['category']); ?></span>
                                <h3 class="blog-title"><?php echo htmlspecialchars($post['title']); ?></h3>
                                <p class="blog-excerpt"><?php echo htmlspecialchars(substr($post['excerpt'] ?: strip_tags($post['content']), 0, 150)); ?>...</p>
                                
                                <div class="blog-meta">
                                    <span class="blog-date">
                                        <i class="fas fa-calendar"></i> <?php echo date('M j, Y', strtotime($post['published_at'])); ?>
                                    </span>
                                    <a href="blogs.php?slug=<?php echo htmlspecialchars($post['slug']); ?>" class="read-more">
                                        Read More <i class="fas fa-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </article>
                        <?php endwhile; ?>
                    </div>
                    
                    <!-- Pagination -->
                    <?php if($total_pages > 1): ?>
                    <div class="pagination">
                        <?php if($page > 1): ?>
                            <a href="?page=1&category=<?php echo $category; ?>&search=<?php echo urlencode($search); ?>" class="page-link">
                                <i class="fas fa-angle-double-left"></i>
                            </a>
                            <a href="?page=<?php echo $page - 1; ?>&category=<?php echo $category; ?>&search=<?php echo urlencode($search); ?>" class="page-link">
                                <i class="fas fa-angle-left"></i>
                            </a>
                        <?php endif; ?>
                        
                        <?php
                        $start = max(1, $page - 2);
                        $end = min($total_pages, $page + 2);
                        
                        for($i = $start; $i <= $end; $i++): ?>
                            <a href="?page=<?php echo $i; ?>&category=<?php echo $category; ?>&search=<?php echo urlencode($search); ?>" 
                               class="page-link <?php echo $i == $page ? 'active' : ''; ?>">
                                <?php echo $i; ?>
                            </a>
                        <?php endfor; ?>
                        
                        <?php if($page < $total_pages): ?>
                            <a href="?page=<?php echo $page + 1; ?>&category=<?php echo $category; ?>&search=<?php echo urlencode($search); ?>" class="page-link">
                                <i class="fas fa-angle-right"></i>
                            </a>
                            <a href="?page=<?php echo $total_pages; ?>&category=<?php echo $category; ?>&search=<?php echo urlencode($search); ?>" class="page-link">
                                <i class="fas fa-angle-double-right"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                    
                <?php else: ?>
                    <div class="no-results">
                        <i class="fas fa-search"></i>
                        <h3>No Blog Posts Found</h3>
                        <p><?php echo empty($search) ? 'No blog posts available yet. Check back soon!' : 'No posts match your search criteria.'; ?></p>
                        <?php if(!empty($search)): ?>
                            <a href="blogs.php" class="btn-primary" style="margin-top: 20px; display: inline-block; padding: 12px 25px; text-decoration: none; background: var(--magenta-pink); color: white; border-radius: 8px; font-weight: 500;">
                                <i class="fas fa-times"></i> Clear Search
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </main>
            
            <!-- Sidebar -->
            <aside>
                <!-- Categories -->
                <div class="sidebar-widget">
                    <h3 class="widget-title">Categories</h3>
                    <ul class="category-list">
                        <li>
                            <a href="blogs.php">
                                All Categories
                                <span class="category-count"><?php echo $total; ?></span>
                            </a>
                        </li>
                        <?php foreach($categories as $cat): ?>
                        <li>
                            <a href="blogs.php?category=<?php echo urlencode($cat['category']); ?>">
                                <?php echo htmlspecialchars($cat['category']); ?>
                                <span class="category-count"><?php echo $cat['count']; ?></span>
                            </a>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                
                <!-- Popular Posts -->
                <div class="sidebar-widget">
                    <h3 class="widget-title">Popular Posts</h3>
                    <?php while($popular = mysqli_fetch_assoc($popular_result)): ?>
                    <div class="popular-post">
                        <?php if($popular['featured_image']): ?>
                            <img src="<?php echo htmlspecialchars($popular['featured_image']); ?>" alt="<?php echo htmlspecialchars($popular['title']); ?>" class="popular-image">
                        <?php else: ?>
                            <div style="width: 80px; height: 80px; background: var(--light-gray); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #999;">
                                <i class="fas fa-image"></i>
                            </div>
                        <?php endif; ?>
                        <div class="popular-content">
                            <h4>
                                <a href="blogs.php?slug=<?php echo htmlspecialchars($popular['slug']); ?>" style="color: var(--dark-gray); text-decoration: none;">
                                    <?php echo htmlspecialchars(substr($popular['title'], 0, 50)); ?>...
                                </a>
                            </h4>
                            <div class="popular-date">
                                <i class="fas fa-calendar"></i> <?php echo date('M j, Y', strtotime($popular['published_at'])); ?>
                            </div>
                        </div>
                    </div>
                    <?php endwhile; ?>
                </div>
                
                <!-- Subscribe -->
                <div class="sidebar-widget" style="background: linear-gradient(135deg, var(--royal-violet), var(--primary-purple)); color: white;">
                    <h3 class="widget-title" style="color: white;">Stay Updated</h3>
                    <p style="margin-bottom: 20px;">Subscribe to our newsletter for the latest blog posts and updates.</p>
                    <form id="subscribeForm" style="position: relative;">
                        <input type="email" placeholder="Your email address" required 
                               style="width: 100%; padding: 12px 20px; border: none; border-radius: 6px; font-size: 15px; margin-bottom: 15px;">
                        <button type="submit" 
                                style="width: 100%; padding: 12px; background: var(--magenta-pink); color: white; border: none; border-radius: 6px; font-weight: 500; cursor: pointer; transition: all 0.3s ease;">
                            Subscribe
                        </button>
                    </form>
                </div>
            </aside>
        </div>
    </div>
    <?php endif; ?>
    
    <script>
        // Subscribe Form
        document.getElementById('subscribeForm')?.addEventListener('submit', function(e) {
            e.preventDefault();
            const email = this.querySelector('input[type="email"]').value;
            if (email) {
                alert('Thank you for subscribing! You will receive updates at ' + email);
                this.reset();
            }
        });
        
        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    </script>
</body>
</html>

<?php
include 'includes/footer.php';
// Close database connection
mysqli_close($conn);
?>