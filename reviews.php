<?php
// reviews.php - Customer Reviews Page
include 'includes/config.php';
include 'includes/header.php';

// Get pagination
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$per_page = 10;
$offset = ($page - 1) * $per_page;

// Get filters
$rating = isset($_GET['rating']) ? intval($_GET['rating']) : 0;
$sort = isset($_GET['sort']) ? mysqli_real_escape_string($conn, $_GET['sort']) : 'newest';

// Build query conditions
$query_conditions = "status = 'published'";

if ($rating > 0 && $rating <= 5) {
    $query_conditions .= " AND rating = $rating";
}

// Sort options
$sort_options = [
    'newest' => 'created_at DESC',
    'oldest' => 'created_at ASC',
    'highest' => 'rating DESC, created_at DESC',
    'lowest' => 'rating ASC, created_at DESC',
    'helpful' => 'helpful_count DESC'
];

$order_by = isset($sort_options[$sort]) ? $sort_options[$sort] : 'created_at DESC';

// Get total count
$count_query = "SELECT COUNT(*) as total FROM reviews WHERE $query_conditions";
$count_result = mysqli_query($conn, $count_query);
$total_row = mysqli_fetch_assoc($count_result);
$total = $total_row['total'];
$total_pages = ceil($total / $per_page);

// Get reviews with pagination
$query = "SELECT * FROM reviews WHERE $query_conditions ORDER BY $order_by LIMIT $per_page OFFSET $offset";
$result = mysqli_query($conn, $query);

// Get rating statistics
$stats_query = "SELECT 
    COUNT(*) as total_reviews,
    AVG(rating) as avg_rating,
    SUM(CASE WHEN rating = 5 THEN 1 ELSE 0 END) as five_star,
    SUM(CASE WHEN rating = 4 THEN 1 ELSE 0 END) as four_star,
    SUM(CASE WHEN rating = 3 THEN 1 ELSE 0 END) as three_star,
    SUM(CASE WHEN rating = 2 THEN 1 ELSE 0 END) as two_star,
    SUM(CASE WHEN rating = 1 THEN 1 ELSE 0 END) as one_star
    FROM reviews WHERE status = 'published'";
$stats_result = mysqli_query($conn, $stats_query);
$stats = mysqli_fetch_assoc($stats_result);

// Calculate percentages
if ($stats['total_reviews'] > 0) {
    $stats['avg_rating'] = round($stats['avg_rating'], 1);
    $stats['five_percent'] = round(($stats['five_star'] / $stats['total_reviews']) * 100);
    $stats['four_percent'] = round(($stats['four_star'] / $stats['total_reviews']) * 100);
    $stats['three_percent'] = round(($stats['three_star'] / $stats['total_reviews']) * 100);
    $stats['two_percent'] = round(($stats['two_star'] / $stats['total_reviews']) * 100);
    $stats['one_percent'] = round(($stats['one_star'] / $stats['total_reviews']) * 100);
}

// Handle review submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_review'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $rating = intval($_POST['rating']);
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $content = mysqli_real_escape_string($conn, $_POST['content']);
    $user_type = mysqli_real_escape_string($conn, $_POST['user_type']);
    
    // Validate rating
    if ($rating < 1 || $rating > 5) {
        $rating = 5;
    }
    
    // Insert review
    $insert_query = "INSERT INTO reviews (name, email, rating, title, content, user_type, status, created_at) 
                     VALUES ('$name', '$email', $rating, '$title', '$content', '$user_type', 'pending', NOW())";
    
    if (mysqli_query($conn, $insert_query)) {
        $success_message = "Thank you for your review! It will be published after approval.";
    } else {
        $error_message = "Sorry, there was an error submitting your review. Please try again.";
    }
}

// Handle helpful count increment
if (isset($_GET['helpful']) && isset($_GET['id'])) {
    $review_id = intval($_GET['id']);
    // Here you would typically track user votes with IP/session to prevent multiple votes
    mysqli_query($conn, "UPDATE reviews SET helpful_count = helpful_count + 1 WHERE id = $review_id");
    header("Location: reviews.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Reviews - Home Castle Tutor</title>
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
            --success-green: #28a745;
            --warning-yellow: #ffc107;
            --danger-red: #dc3545;
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
        
        /* Page Header */
        .page-header {
            background: linear-gradient(135deg, var(--primary-purple) 0%, var(--royal-violet) 100%);
            color: white;
            padding: 80px 0;
            text-align: center;
            margin-bottom: 60px;
            border-radius: 0 0 20px 20px;
        }
        
        .page-header h1 {
            color: white;
            font-size: 48px;
            margin-bottom: 20px;
            text-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .page-header p {
            font-size: 18px;
            opacity: 0.95;
            max-width: 600px;
            margin: 0 auto;
            font-weight: 300;
            line-height: 1.6;
        }
        
        /* Rating Summary */
        .rating-summary {
            background: white;
            border-radius: 15px;
            padding: 40px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            margin-bottom: 40px;
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 40px;
        }
        
        .rating-overview {
            text-align: center;
            padding: 20px;
            border-right: 2px solid var(--light-gray);
        }
        
        .average-rating {
            font-size: 60px;
            font-weight: 700;
            color: var(--primary-purple);
            line-height: 1;
            margin-bottom: 10px;
        }
        
        .stars-large {
            color: var(--warm-orange);
            font-size: 24px;
            margin-bottom: 15px;
        }
        
        .total-reviews {
            color: #666;
            font-size: 14px;
        }
        
        .rating-bars {
            padding: 20px 0;
        }
        
        .rating-bar {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }
        
        .rating-label {
            width: 80px;
            font-size: 14px;
            color: #666;
        }
        
        .rating-bar-inner {
            flex: 1;
            height: 8px;
            background: var(--medium-gray);
            border-radius: 4px;
            margin: 0 15px;
            overflow: hidden;
        }
        
        .rating-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--warm-orange), var(--magenta-pink));
            border-radius: 4px;
        }
        
        .rating-percent {
            width: 40px;
            text-align: right;
            font-size: 14px;
            font-weight: 500;
        }
        
        /* Review Filters */
        .review-filters {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            flex-wrap: wrap;
            gap: 20px;
        }
        
        .filter-buttons {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        
        .filter-btn {
            padding: 10px 20px;
            border: 2px solid var(--medium-gray);
            background: white;
            border-radius: 8px;
            text-decoration: none;
            color: var(--dark-gray);
            font-weight: 500;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .filter-btn:hover, .filter-btn.active {
            border-color: var(--royal-violet);
            color: var(--royal-violet);
            background: rgba(94, 43, 151, 0.05);
        }
        
        .filter-btn.active {
            background: var(--royal-violet);
            color: white;
        }
        
        .sort-dropdown {
            position: relative;
            min-width: 200px;
        }
        
        .sort-select {
            width: 100%;
            padding: 10px 20px;
            border: 2px solid var(--medium-gray);
            border-radius: 8px;
            background: white;
            font-size: 15px;
            color: var(--dark-gray);
            cursor: pointer;
            appearance: none;
            padding-right: 40px;
        }
        
        .sort-dropdown i {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--royal-violet);
            pointer-events: none;
        }
        
        /* Review Cards */
        .review-card {
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            margin-bottom: 25px;
            transition: all 0.3s ease;
        }
        
        .review-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.12);
        }
        
        .review-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 20px;
        }
        
        .reviewer-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .reviewer-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--royal-violet), var(--magenta-pink));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
            font-weight: 600;
        }
        
        .reviewer-details h4 {
            font-size: 18px;
            margin-bottom: 5px;
        }
        
        .reviewer-type {
            font-size: 13px;
            color: var(--magenta-pink);
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .review-rating {
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .star {
            color: var(--warm-orange);
            font-size: 18px;
        }
        
        .review-meta {
            display: flex;
            gap: 15px;
            font-size: 14px;
            color: #999;
            margin-top: 5px;
        }
        
        .review-title {
            font-size: 20px;
            margin-bottom: 15px;
            color: var(--primary-purple);
        }
        
        .review-content {
            color: #555;
            line-height: 1.7;
            margin-bottom: 20px;
        }
        
        .review-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 20px;
            border-top: 1px solid var(--medium-gray);
        }
        
        .helpful-btn {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            border: 1px solid var(--medium-gray);
            background: white;
            border-radius: 6px;
            color: #666;
            text-decoration: none;
            font-size: 14px;
            transition: all 0.3s ease;
        }
        
        .helpful-btn:hover {
            background: var(--light-gray);
            color: var(--royal-violet);
            border-color: var(--royal-violet);
        }
        
        .helpful-btn i {
            color: var(--success-green);
        }
        
        .review-date {
            font-size: 14px;
            color: #999;
        }
        
        /* Review Form */
        .review-form-container {
            background: white;
            border-radius: 15px;
            padding: 40px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            margin: 60px 0;
        }
        
        .form-header {
            text-align: center;
            margin-bottom: 40px;
        }
        
        .form-header h3 {
            font-size: 28px;
            margin-bottom: 10px;
        }
        
        .form-header p {
            color: #666;
            max-width: 600px;
            margin: 0 auto;
        }
        
        .form-group {
            margin-bottom: 25px;
        }
        
        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--dark-gray);
        }
        
        .form-control {
            width: 100%;
            padding: 12px 20px;
            border: 2px solid var(--medium-gray);
            border-radius: 8px;
            font-size: 15px;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            outline: none;
            border-color: var(--royal-violet);
            box-shadow: 0 0 0 3px rgba(94, 43, 151, 0.1);
        }
        
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        
        /* Star Rating Input */
        .rating-input {
            display: flex;
            flex-direction: row-reverse;
            justify-content: flex-end;
            gap: 5px;
        }
        
        .rating-input input {
            display: none;
        }
        
        .rating-input label {
            cursor: pointer;
            font-size: 30px;
            color: var(--medium-gray);
            transition: color 0.3s ease;
        }
        
        .rating-input label:hover,
        .rating-input label:hover ~ label,
        .rating-input input:checked ~ label {
            color: var(--warm-orange);
        }
        
        .rating-text {
            margin-top: 10px;
            font-size: 14px;
            color: #666;
        }
        
        textarea.form-control {
            min-height: 150px;
            resize: vertical;
        }
        
        .submit-btn {
            background: linear-gradient(135deg, var(--royal-violet), var(--primary-purple));
            color: white;
            border: none;
            padding: 15px 40px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: block;
            margin: 0 auto;
            min-width: 200px;
        }
        
        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(94, 43, 151, 0.3);
        }
        
        /* Alert Messages */
        .alert {
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 30px;
            border-left: 4px solid;
        }
        
        .alert-success {
            background: rgba(40, 167, 69, 0.1);
            border-color: var(--success-green);
            color: var(--success-green);
        }
        
        .alert-error {
            background: rgba(220, 53, 69, 0.1);
            border-color: var(--danger-red);
            color: var(--danger-red);
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
        
        /* No Reviews */
        .no-reviews {
            text-align: center;
            padding: 60px 20px;
            grid-column: 1 / -1;
        }
        
        .no-reviews i {
            font-size: 60px;
            color: #ddd;
            margin-bottom: 20px;
        }
        
        .no-reviews h3 {
            color: var(--primary-purple);
            margin-bottom: 10px;
        }
        
        /* Responsive */
        @media (max-width: 992px) {
            .rating-summary {
                grid-template-columns: 1fr;
                gap: 30px;
            }
            
            .rating-overview {
                border-right: none;
                border-bottom: 2px solid var(--light-gray);
                padding-bottom: 30px;
            }
            
            .form-row {
                grid-template-columns: 1fr;
            }
            
            .review-filters {
                flex-direction: column;
                align-items: stretch;
            }
        }
        
        @media (max-width: 768px) {
            .page-header {
                padding: 60px 0;
            }
            
            .page-header h1 {
                font-size: 36px;
            }
            
            .review-header {
                flex-direction: column;
                gap: 15px;
            }
            
            .reviewer-info {
                width: 100%;
            }
            
            .review-rating {
                align-self: flex-start;
            }
            
            .rating-summary,
            .review-form-container {
                padding: 30px;
            }
            
            .review-actions {
                flex-direction: column;
                gap: 15px;
                align-items: flex-start;
            }
        }
        
        @media (max-width: 480px) {
            .page-header {
                padding: 50px 0;
            }
            
            .page-header h1 {
                font-size: 32px;
            }
            
            .rating-summary,
            .review-form-container,
            .review-card {
                padding: 20px;
            }
            
            .average-rating {
                font-size: 48px;
            }
            
            .review-title {
                font-size: 18px;
            }
            
            .filter-buttons {
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <!-- Page Header -->
    <div class="page-header">
        <div class="container">
            <h1>Customer Reviews</h1>
            <p>See what our students and parents are saying about Home Castle Tutor</p>
        </div>
    </div>
    
    <div class="container">
        <!-- Rating Summary -->
        <div class="rating-summary">
            <div class="rating-overview">
                <div class="average-rating">
                    <?php echo number_format($stats['avg_rating'] ?? 0, 1); ?>
                </div>
                <div class="stars-large">
                    <?php
                    $avg_rating = $stats['avg_rating'] ?? 0;
                    for($i = 1; $i <= 5; $i++):
                        if($i <= floor($avg_rating)):
                            echo '<i class="fas fa-star"></i>';
                        elseif($i == ceil($avg_rating) && fmod($avg_rating, 1) >= 0.5):
                            echo '<i class="fas fa-star-half-alt"></i>';
                        else:
                            echo '<i class="far fa-star"></i>';
                        endif;
                    endfor;
                    ?>
                </div>
                <div class="total-reviews">
                    Based on <?php echo $stats['total_reviews'] ?? 0; ?> reviews
                </div>
            </div>
            
            <div class="rating-bars">
                <?php for($i = 5; $i >= 1; $i--): 
                    $percent = $stats[$i . '_percent'] ?? 0;
                ?>
                <div class="rating-bar">
                    <div class="rating-label">
                        <?php echo $i; ?> <i class="fas fa-star" style="color: var(--warm-orange); font-size: 12px;"></i>
                    </div>
                    <div class="rating-bar-inner">
                        <div class="rating-fill" style="width: <?php echo $percent; ?>%"></div>
                    </div>
                    <div class="rating-percent"><?php echo $percent; ?>%</div>
                </div>
                <?php endfor; ?>
            </div>
        </div>
        
        <!-- Review Filters -->
        <div class="review-filters">
            <div class="filter-buttons">
                <a href="reviews.php" class="filter-btn <?php echo $rating == 0 ? 'active' : ''; ?>">
                    All Reviews
                </a>
                <?php for($i = 5; $i >= 1; $i--): ?>
                <a href="reviews.php?rating=<?php echo $i; ?>" 
                   class="filter-btn <?php echo $rating == $i ? 'active' : ''; ?>">
                    <i class="fas fa-star"></i> <?php echo $i; ?> Star
                </a>
                <?php endfor; ?>
            </div>
            
            <div class="sort-dropdown">
                <select class="sort-select" onchange="window.location.href='reviews.php?rating=<?php echo $rating; ?>&sort='+this.value">
                    <option value="newest" <?php echo $sort == 'newest' ? 'selected' : ''; ?>>Newest First</option>
                    <option value="oldest" <?php echo $sort == 'oldest' ? 'selected' : ''; ?>>Oldest First</option>
                    <option value="highest" <?php echo $sort == 'highest' ? 'selected' : ''; ?>>Highest Rated</option>
                    <option value="lowest" <?php echo $sort == 'lowest' ? 'selected' : ''; ?>>Lowest Rated</option>
                    <option value="helpful" <?php echo $sort == 'helpful' ? 'selected' : ''; ?>>Most Helpful</option>
                </select>
                <i class="fas fa-chevron-down"></i>
            </div>
        </div>
        
        <!-- Alert Messages -->
        <?php if(isset($success_message)): ?>
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i> <?php echo $success_message; ?>
        </div>
        <?php endif; ?>
        
        <?php if(isset($error_message)): ?>
        <div class="alert alert-error">
            <i class="fas fa-exclamation-circle"></i> <?php echo $error_message; ?>
        </div>
        <?php endif; ?>
        
        <!-- Reviews List -->
        <?php if(mysqli_num_rows($result) > 0): ?>
            <?php while($review = mysqli_fetch_assoc($result)): 
                $initial = strtoupper(substr($review['name'], 0, 1));
            ?>
            <div class="review-card">
                <div class="review-header">
                    <div class="reviewer-info">
                        <div class="reviewer-avatar">
                            <?php echo $initial; ?>
                        </div>
                        <div class="reviewer-details">
                            <h4><?php echo htmlspecialchars($review['name']); ?></h4>
                            <div class="reviewer-type"><?php echo htmlspecialchars($review['user_type']); ?></div>
                            <div class="review-meta">
                                <span><i class="fas fa-calendar"></i> <?php echo date('F j, Y', strtotime($review['created_at'])); ?></span>
                                <?php if($review['verified_purchase']): ?>
                                <span><i class="fas fa-check-circle" style="color: var(--success-green);"></i> Verified Student</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="review-rating">
                        <?php for($i = 1; $i <= 5; $i++): ?>
                            <?php if($i <= $review['rating']): ?>
                                <i class="fas fa-star star"></i>
                            <?php else: ?>
                                <i class="far fa-star star"></i>
                            <?php endif; ?>
                        <?php endfor; ?>
                        <span style="font-weight: 500; margin-left: 5px;"><?php echo $review['rating']; ?>/5</span>
                    </div>
                </div>
                
                <?php if(!empty($review['title'])): ?>
                <h3 class="review-title"><?php echo htmlspecialchars($review['title']); ?></h3>
                <?php endif; ?>
                
                <div class="review-content">
                    <?php echo nl2br(htmlspecialchars($review['content'])); ?>
                </div>
                
                <div class="review-actions">
                    <a href="reviews.php?helpful=1&id=<?php echo $review['id']; ?>" class="helpful-btn">
                        <i class="fas fa-thumbs-up"></i>
                        Helpful (<?php echo $review['helpful_count']; ?>)
                    </a>
                    <div class="review-date">
                        Posted <?php echo date('M j, Y', strtotime($review['created_at'])); ?>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
            
            <!-- Pagination -->
            <?php if($total_pages > 1): ?>
            <div class="pagination">
                <?php if($page > 1): ?>
                    <a href="?page=1&rating=<?php echo $rating; ?>&sort=<?php echo $sort; ?>" class="page-link">
                        <i class="fas fa-angle-double-left"></i>
                    </a>
                    <a href="?page=<?php echo $page - 1; ?>&rating=<?php echo $rating; ?>&sort=<?php echo $sort; ?>" class="page-link">
                        <i class="fas fa-angle-left"></i>
                    </a>
                <?php endif; ?>
                
                <?php
                $start = max(1, $page - 2);
                $end = min($total_pages, $page + 2);
                
                for($i = $start; $i <= $end; $i++): ?>
                    <a href="?page=<?php echo $i; ?>&rating=<?php echo $rating; ?>&sort=<?php echo $sort; ?>" 
                       class="page-link <?php echo $i == $page ? 'active' : ''; ?>">
                        <?php echo $i; ?>
                    </a>
                <?php endfor; ?>
                
                <?php if($page < $total_pages): ?>
                    <a href="?page=<?php echo $page + 1; ?>&rating=<?php echo $rating; ?>&sort=<?php echo $sort; ?>" class="page-link">
                        <i class="fas fa-angle-right"></i>
                    </a>
                    <a href="?page=<?php echo $total_pages; ?>&rating=<?php echo $rating; ?>&sort=<?php echo $sort; ?>" class="page-link">
                        <i class="fas fa-angle-double-right"></i>
                    </a>
                <?php endif; ?>
            </div>
            <?php endif; ?>
            
        <?php else: ?>
            <div class="no-reviews">
                <i class="fas fa-comment-alt"></i>
                <h3>No Reviews Yet</h3>
                <p><?php echo $rating > 0 ? 'No reviews found with this rating.' : 'Be the first to share your experience!'; ?></p>
                <?php if($rating > 0): ?>
                    <a href="reviews.php" class="filter-btn" style="margin-top: 20px;">
                        View All Reviews
                    </a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        
        <!-- Review Form -->
        <div class="review-form-container">
            <div class="form-header">
                <h3>Share Your Experience</h3>
                <p>Your feedback helps us improve and helps others make informed decisions</p>
            </div>
            
            <form method="POST" action="reviews.php">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label" for="name">Your Name *</label>
                        <input type="text" id="name" name="name" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label" for="email">Email Address *</label>
                        <input type="email" id="email" name="email" class="form-control" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="user_type">You are a *</label>
                    <select id="user_type" name="user_type" class="form-control" required>
                        <option value="">Select your role</option>
                        <option value="Student">Student</option>
                        <option value="Parent">Parent</option>
                        <option value="Teacher">Teacher</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Your Rating *</label>
                    <div class="rating-input">
                        <input type="radio" id="star5" name="rating" value="5" required>
                        <label for="star5"><i class="fas fa-star"></i></label>
                        <input type="radio" id="star4" name="rating" value="4">
                        <label for="star4"><i class="fas fa-star"></i></label>
                        <input type="radio" id="star3" name="rating" value="3">
                        <label for="star3"><i class="fas fa-star"></i></label>
                        <input type="radio" id="star2" name="rating" value="2">
                        <label for="star2"><i class="fas fa-star"></i></label>
                        <input type="radio" id="star1" name="rating" value="1">
                        <label for="star1"><i class="fas fa-star"></i></label>
                    </div>
                    <div class="rating-text">Click on a star to rate your experience</div>
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="title">Review Title</label>
                    <input type="text" id="title" name="title" class="form-control" placeholder="Summarize your experience">
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="content">Your Review *</label>
                    <textarea id="content" name="content" class="form-control" 
                              placeholder="Share details about your experience with Home Castle Tutor..." required></textarea>
                </div>
                
                <button type="submit" name="submit_review" class="submit-btn">
                    <i class="fas fa-paper-plane"></i> Submit Review
                </button>
            </form>
        </div>
    </div>
    
    <script>
        // Star rating interaction
        document.querySelectorAll('.rating-input input').forEach(star => {
            star.addEventListener('change', function() {
                const rating = this.value;
                const labels = document.querySelectorAll('.rating-input label');
                labels.forEach((label, index) => {
                    if (5 - index <= rating) {
                        label.style.color = 'var(--warm-orange)';
                    }
                });
            });
        });
        
        // Form submission confirmation
        document.querySelector('form').addEventListener('submit', function(e) {
            const rating = document.querySelector('input[name="rating"]:checked');
            if (!rating) {
                e.preventDefault();
                alert('Please select a rating before submitting.');
                return;
            }
            
            if (confirm('Thank you for your review! It will be published after approval. Continue?')) {
                return true;
            } else {
                e.preventDefault();
            }
        });
        
        // Filter buttons active state
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
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