<?php
// ============================================
// SESSION START MUST BE AT THE VERY TOP
// ============================================
session_start();

// Set page metadata
$page_title = "Subscription Plans | Home Tutor Castle";
$page_description = "Choose from our Foundation, Intermediate, and Veteran plans for personalized tutoring";
$page_keywords = "tutoring plans, subscription, foundation, intermediate, veteran, home tutor";

// Start output buffering to prevent header issues
ob_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($page_title); ?></title>
    <meta name="description" content="<?php echo htmlspecialchars($page_description); ?>">
    <meta name="keywords" content="<?php echo htmlspecialchars($page_keywords); ?>">
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Inter:wght@300;400;500&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary-purple: #3B0A6A;
            --royal-violet: #5E2B97;
            --magenta-pink: #C13C91;
            --warm-orange: #F6A04D;
            --light-bg: #F8F9FA;
            --dark-bg: #1A1A2E;
            --text-dark: #333333;
            --text-light: #6C757D;
            --white: #FFFFFF;
            --shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            font-size: 16px;
            line-height: 1.6;
            color: var(--text-dark);
            background-color: var(--light-bg);
            overflow-x: hidden;
        }
        
        h1, h2, h3, h4, button {
            font-family: 'Poppins', sans-serif;
            font-weight: 700;
            color: var(--primary-purple);
        }
        
        h1 {
            font-size: 3.5rem;
            margin-bottom: 1.5rem;
            background: linear-gradient(135deg, var(--primary-purple), var(--royal-violet));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        h2 {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            color: var(--royal-violet);
        }
        
        h3 {
            font-size: 1.8rem;
            margin-bottom: 1rem;
            color: var(--primary-purple);
        }
        
        .container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        /* Hero Section */
        .plans-hero {
            background: linear-gradient(135deg, var(--primary-purple) 0%, var(--royal-violet) 100%);
            color: var(--white);
            padding: 100px 0 80px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .plans-hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 100" preserveAspectRatio="none"><path d="M0,0V100H1000V0C800,50 600,70 400,60C200,50 100,30 0,0Z" fill="%23F8F9FA"/></svg>');
            background-size: 100% auto;
            background-repeat: no-repeat;
            background-position: bottom;
            opacity: 0.1;
        }
        
        .plans-hero h1 {
            color: var(--white);
            -webkit-text-fill-color: var(--white);
            font-size: 3.5rem;
            margin-bottom: 1rem;
            animation: fadeInUp 0.8s ease-out;
        }
        
        .plans-hero p {
            font-size: 1.2rem;
            max-width: 700px;
            margin: 0 auto 2rem;
            opacity: 0.9;
            animation: fadeInUp 0.8s ease-out 0.2s both;
        }
        
        /* Plans Section */
        .plans-section {
            padding: 100px 0;
            background-color: var(--light-bg);
        }
        
        .section-header {
            text-align: center;
            margin-bottom: 60px;
        }
        
        .section-subtitle {
            color: var(--text-light);
            font-size: 1.1rem;
            max-width: 600px;
            margin: 0 auto;
        }
        
        .plans-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 30px;
            margin-top: 50px;
        }
        
        .plan-card {
            background: var(--white);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: var(--shadow);
            transition: var(--transition);
            position: relative;
            animation: fadeIn 0.6s ease-out;
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        
        .plan-card:hover {
            transform: translateY(-15px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.12);
        }
        
        .plan-card:nth-child(1):hover {
            border-top: 5px solid var(--warm-orange);
        }
        
        .plan-card:nth-child(2):hover {
            border-top: 5px solid var(--magenta-pink);
        }
        
        .plan-card:nth-child(3):hover {
            border-top: 5px solid var(--royal-violet);
        }
        
        .plan-header {
            padding: 30px;
            text-align: center;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            position: relative;
        }
        
        .plan-badge {
            position: absolute;
            top: 20px;
            right: 20px;
            background: var(--warm-orange);
            color: var(--white);
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .plan-icon {
            width: 80px;
            height: 80px;
            background: var(--white);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .plan-icon i {
            font-size: 2rem;
            color: var(--primary-purple);
        }
        
        .plan-name {
            font-size: 1.8rem;
            margin-bottom: 10px;
            color: var(--primary-purple);
        }
        
        .plan-tagline {
            color: var(--text-light);
            font-size: 1rem;
            margin-bottom: 20px;
        }
        
        .plan-price {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--royal-violet);
            margin-bottom: 5px;
        }
        
        .plan-duration {
            color: var(--text-light);
            font-size: 0.9rem;
        }
        
        .plan-features {
            padding: 30px;
            flex-grow: 1;
        }
        
        .features-list {
            list-style: none;
        }
        
        .features-list li {
            padding: 12px 0;
            border-bottom: 1px solid #f0f0f0;
            display: flex;
            align-items: flex-start;
        }
        
        .features-list li:last-child {
            border-bottom: none;
        }
        
        .features-list li i {
            color: var(--magenta-pink);
            margin-right: 12px;
            margin-top: 5px;
            flex-shrink: 0;
        }
        
        .plan-footer {
            padding: 0 30px 30px;
            text-align: center;
        }
        
        .btn-plan {
            display: inline-block;
            background: linear-gradient(135deg, var(--magenta-pink), var(--royal-violet));
            color: var(--white);
            padding: 15px 40px;
            border-radius: 50px;
            font-weight: 600;
            text-decoration: none;
            border: none;
            cursor: pointer;
            transition: var(--transition);
            font-size: 1rem;
            box-shadow: 0 5px 15px rgba(193, 60, 145, 0.3);
            position: relative;
            overflow: hidden;
            z-index: 1;
        }
        
        .btn-plan::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, var(--royal-violet), var(--primary-purple));
            transition: var(--transition);
            z-index: -1;
        }
        
        .btn-plan:hover::before {
            left: 0;
        }
        
        .btn-plan:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(193, 60, 145, 0.4);
        }
        
        /* Comparison Section */
        .comparison-section {
            padding: 80px 0;
            background-color: var(--white);
        }
        
        .comparison-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 40px;
            box-shadow: var(--shadow);
            border-radius: 10px;
            overflow: hidden;
        }
        
        .comparison-table th {
            background-color: var(--primary-purple);
            color: var(--white);
            padding: 20px;
            text-align: center;
            font-weight: 600;
        }
        
        .comparison-table td {
            padding: 15px 20px;
            border-bottom: 1px solid #f0f0f0;
        }
        
        .comparison-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        .comparison-table .feature-name {
            font-weight: 500;
            color: var(--text-dark);
        }
        
        .feature-check {
            color: var(--magenta-pink);
            font-size: 1.2rem;
        }
        
        /* FAQ Section */
        .faq-section {
            padding: 80px 0;
            background-color: var(--light-bg);
        }
        
        .faq-container {
            max-width: 800px;
            margin: 0 auto;
        }
        
        .faq-item {
            background: var(--white);
            border-radius: 10px;
            margin-bottom: 15px;
            overflow: hidden;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
            transition: var(--transition);
        }
        
        .faq-item:hover {
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .faq-question {
            padding: 20px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: var(--primary-purple);
        }
        
        .faq-answer {
            padding: 0 20px;
            max-height: 0;
            overflow: hidden;
            transition: var(--transition);
        }
        
        .faq-item.active .faq-answer {
            padding: 0 20px 20px;
            max-height: 500px;
        }
        
        .faq-toggle {
            transition: var(--transition);
        }
        
        .faq-item.active .faq-toggle {
            transform: rotate(45deg);
        }
        
        /* CTA Section */
        .cta-section {
            padding: 100px 0;
            background: linear-gradient(135deg, var(--primary-purple) 0%, var(--royal-violet) 100%);
            color: var(--white);
            text-align: center;
        }
        
        .cta-section h2 {
            color: var(--white);
            -webkit-text-fill-color: var(--white);
            margin-bottom: 1rem;
        }
        
        .cta-section p {
            max-width: 600px;
            margin: 0 auto 30px;
            opacity: 0.9;
            font-size: 1.1rem;
        }
        
        .btn-cta {
            background: var(--magenta-pink);
            color: var(--white);
            padding: 18px 50px;
            border-radius: 50px;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            transition: var(--transition);
            font-size: 1.1rem;
            border: 2px solid var(--magenta-pink);
        }
        
        .btn-cta:hover {
            background: transparent;
            color: var(--white);
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }
        
        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }
        
        .animate-on-scroll {
            opacity: 0;
            transform: translateY(30px);
            transition: opacity 0.6s ease, transform 0.6s ease;
        }
        
        .animate-on-scroll.animated {
            opacity: 1;
            transform: translateY(0);
        }
        
        /* Responsive */
        @media (max-width: 992px) {
            h1 {
                font-size: 2.8rem;
            }
            
            h2 {
                font-size: 2.2rem;
            }
            
            .plans-container {
                grid-template-columns: 1fr;
                max-width: 500px;
                margin: 0 auto;
            }
            
            .comparison-table {
                display: block;
                overflow-x: auto;
            }
        }
        
        @media (max-width: 768px) {
            h1 {
                font-size: 2.2rem;
            }
            
            h2 {
                font-size: 1.8rem;
            }
            
            .plans-hero {
                padding: 80px 0 60px;
            }
            
            .plans-section, .comparison-section, .faq-section, .cta-section {
                padding: 60px 0;
            }
            
            .plan-card {
                max-width: 100%;
            }
        }
        
        /* Ripple effect for buttons */
        .ripple-effect {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.7);
            transform: scale(0);
            animation: ripple 0.6s linear;
            pointer-events: none;
            width: 100px;
            height: 100px;
            margin-left: -50px;
            margin-top: -50px;
        }
        
        @keyframes ripple {
            to {
                transform: scale(4);
                opacity: 0;
            }
        }
    </style>
</head>
<body>
    <!-- Include Header -->
    <?php 
    // Flush output buffer before including header
    ob_end_flush();
    
    // Check if header.php exists
    if (file_exists('includes/header.php')) {
        include('includes/header.php');
    } else {
        // Fallback header if includes/header.php doesn't exist
        echo '<header style="background: var(--primary-purple); padding: 20px; color: white;">';
        echo '<div class="container">';
        echo '<h1 style="margin: 0;">Home Tutor Castle</h1>';
        echo '</div>';
        echo '</header>';
    }
    
    // Start buffering again for the main content
    ob_start();
    ?>
    
    <!-- Hero Section -->
    <section class="plans-hero">
        <div class="container">
            <h1>Choose Your Learning Journey</h1>
            <p>Select the perfect tutoring plan that matches your academic goals and learning style. Each plan is carefully designed to provide maximum value and results.</p>
            <a href="#plans" class="btn-plan">View All Plans</a>
        </div>
    </section>
    
    <!-- Plans Section -->
    <section id="plans" class="plans-section">
        <div class="container">
            <div class="section-header animate-on-scroll">
                <h2>Our Premium Subscription Plans</h2>
                <p class="section-subtitle">Three distinct plans designed to cater to different learning needs and academic goals</p>
            </div>
            
            <div class="plans-container">
                <!-- Foundation Plan -->
                <div class="plan-card animate-on-scroll">
                    <div class="plan-header">
                        <div class="plan-badge">Most Popular</div>
                        <div class="plan-icon">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                        <h3 class="plan-name">Foundation Plan</h3>
                        <p class="plan-tagline">Concept Builder</p>
                        <div class="plan-price">₹4,999<span class="plan-duration">/month</span></div>
                    </div>
                    
                    <div class="plan-features">
                        <ul class="features-list">
                            <li><i class="fas fa-check-circle"></i> Strong concept-building & foundational learning</li>
                            <li><i class="fas fa-check-circle"></i> Full academic-year syllabus coverage</li>
                            <li><i class="fas fa-check-circle"></i> Chapter-wise tests and regular assessments</li>
                            <li><i class="fas fa-check-circle"></i> Continuous revision & doubt-clearing sessions</li>
                            <li><i class="fas fa-check-circle"></i> Monthly Parent–Teacher Meetings (PTMs)</li>
                            <li><i class="fas fa-check-circle"></i> Monthly academic progress reports</li>
                            <li><i class="fas fa-check-circle"></i> Year-end course completion certificate</li>
                            <li><i class="fas fa-check-circle"></i> Tutor replacement support (as needed)</li>
                            <li><i class="fas fa-check-circle"></i> Conceptual & application-based questions</li>
                            <li><i class="fas fa-check-circle"></i> 24×7 academic doubt-support (online)</li>
                            <li><i class="fas fa-check-circle"></i> Customized homework & practice worksheets</li>
                            <li><i class="fas fa-check-circle"></i> Free demo and trial classes</li>
                        </ul>
                    </div>
                    
                    <div class="plan-footer">
                        <a href="contact.php?plan=foundation" class="btn-plan">Choose Foundation Plan</a>
                    </div>
                </div>
                
                <!-- Intermediate Plan -->
                <div class="plan-card animate-on-scroll" style="animation-delay: 0.2s;">
                    <div class="plan-header">
                        <div class="plan-icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <h3 class="plan-name">Intermediate Plan</h3>
                        <p class="plan-tagline">Exam Ready</p>
                        <div class="plan-price">₹7,999<span class="plan-duration">/month</span></div>
                    </div>
                    
                    <div class="plan-features">
                        <ul class="features-list">
                            <li><i class="fas fa-check-circle"></i> Regular revision cycles & concept reinforcement</li>
                            <li><i class="fas fa-check-circle"></i> Structured syllabus-based teaching</li>
                            <li><i class="fas fa-check-circle"></i> Exam-oriented preparation strategy</li>
                            <li><i class="fas fa-check-circle"></i> Practice tests & performance-based assessments</li>
                            <li><i class="fas fa-check-circle"></i> Weekly progress tracking & reports</li>
                            <li><i class="fas fa-check-circle"></i> Weekly Parent–Teacher Meetings (PTMs)</li>
                            <li><i class="fas fa-check-circle"></i> Assignments, worksheets & test papers</li>
                            <li><i class="fas fa-check-circle"></i> 24×7 student support for doubts & queries</li>
                            <li><i class="fas fa-check-circle"></i> Tutor replacement support</li>
                            <li><i class="fas fa-check-circle"></i> Free demo classes</li>
                        </ul>
                    </div>
                    
                    <div class="plan-footer">
                        <a href="contact.php?plan=intermediate" class="btn-plan">Choose Intermediate Plan</a>
                    </div>
                </div>
                
                <!-- Veteran Plan -->
                <div class="plan-card animate-on-scroll" style="animation-delay: 0.4s;">
                    <div class="plan-header">
                        <div class="plan-badge">Premium</div>
                        <div class="plan-icon">
                            <i class="fas fa-crown"></i>
                        </div>
                        <h3 class="plan-name">Veteran Plan</h3>
                        <p class="plan-tagline">Scholar / Entrance Pro</p>
                        <div class="plan-price">₹12,999<span class="plan-duration">/month</span></div>
                    </div>
                    
                    <div class="plan-features">
                        <ul class="features-list">
                            <li><i class="fas fa-check-circle"></i> Advanced-level concept mastery</li>
                            <li><i class="fas fa-check-circle"></i> Intensive revision & mock-test preparation</li>
                            <li><i class="fas fa-check-circle"></i> Complete syllabus + advanced topic coverage</li>
                            <li><i class="fas fa-check-circle"></i> Higher-order problem-solving practice</li>
                            <li><i class="fas fa-check-circle"></i> Test series, mock exams & performance analysis</li>
                            <li><i class="fas fa-check-circle"></i> Dedicated academic mentor</li>
                            <li><i class="fas fa-check-circle"></i> Personalized learning roadmap</li>
                            <li><i class="fas fa-check-circle"></i> Priority 24×7 academic support</li>
                            <li><i class="fas fa-check-circle"></i> Custom assignments & doubt-solving sessions</li>
                            <li><i class="fas fa-check-circle"></i> Tutor replacement support</li>
                            <li><i class="fas fa-check-circle"></i> Free demo & evaluation classes</li>
                        </ul>
                    </div>
                    
                    <div class="plan-footer">
                        <a href="contact.php?plan=veteran" class="btn-plan">Choose Veteran Plan</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Comparison Section -->
    <section class="comparison-section">
        <div class="container">
            <div class="section-header animate-on-scroll">
                <h2>Plan Comparison</h2>
                <p class="section-subtitle">Compare features across all plans to make an informed decision</p>
            </div>
            
            <div class="comparison-table animate-on-scroll">
                <table>
                    <thead>
                        <tr>
                            <th>Features</th>
                            <th>Foundation Plan</th>
                            <th>Intermediate Plan</th>
                            <th>Veteran Plan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="feature-name">Parent-Teacher Meetings</td>
                            <td><i class="fas fa-check feature-check"></i> Monthly</td>
                            <td><i class="fas fa-check feature-check"></i> Weekly</td>
                            <td><i class="fas fa-check feature-check"></i> As Needed</td>
                        </tr>
                        <tr>
                            <td class="feature-name">Progress Reports</td>
                            <td><i class="fas fa-check feature-check"></i> Monthly</td>
                            <td><i class="fas fa-check feature-check"></i> Weekly</td>
                            <td><i class="fas fa-check feature-check"></i> Real-time</td>
                        </tr>
                        <tr>
                            <td class="feature-name">Doubt Support</td>
                            <td><i class="fas fa-check feature-check"></i> 24×7 Online</td>
                            <td><i class="fas fa-check feature-check"></i> 24×7 Student Support</td>
                            <td><i class="fas fa-check feature-check"></i> Priority 24×7</td>
                        </tr>
                        <tr>
                            <td class="feature-name">Personalized Mentor</td>
                            <td><i class="fas fa-times"></i></td>
                            <td><i class="fas fa-times"></i></td>
                            <td><i class="fas fa-check feature-check"></i> Dedicated</td>
                        </tr>
                        <tr>
                            <td class="feature-name">Advanced Topics</td>
                            <td><i class="fas fa-times"></i></td>
                            <td><i class="fas fa-times"></i></td>
                            <td><i class="fas fa-check feature-check"></i> Included</td>
                        </tr>
                        <tr>
                            <td class="feature-name">Mock Test Series</td>
                            <td><i class="fas fa-times"></i></td>
                            <td><i class="fas fa-check feature-check"></i> Practice Tests</td>
                            <td><i class="fas fa-check feature-check"></i> Comprehensive</td>
                        </tr>
                        <tr>
                            <td class="feature-name">Homework & Worksheets</td>
                            <td><i class="fas fa-check feature-check"></i> Customized</td>
                            <td><i class="fas fa-check feature-check"></i> Standard</td>
                            <td><i class="fas fa-check feature-check"></i> Custom</td>
                        </tr>
                        <tr>
                            <td class="feature-name">Tutor Replacement</td>
                            <td><i class="fas fa-check feature-check"></i> Available</td>
                            <td><i class="fas fa-check feature-check"></i> Available</td>
                            <td><i class="fas fa-check feature-check"></i> Priority</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
    
    <!-- FAQ Section -->
    <section class="faq-section">
        <div class="container">
            <div class="section-header animate-on-scroll">
                <h2>Frequently Asked Questions</h2>
                <p class="section-subtitle">Find answers to common questions about our subscription plans</p>
            </div>
            
            <div class="faq-container">
                <div class="faq-item animate-on-scroll">
                    <div class="faq-question">
                        <span>Can I switch between plans after subscribing?</span>
                        <i class="fas fa-plus faq-toggle"></i>
                    </div>
                    <div class="faq-answer">
                        <p>Yes, you can upgrade or downgrade your plan at any time. When upgrading, you'll only pay the prorated difference for the remainder of your billing cycle. Downgrades will take effect at the start of your next billing cycle.</p>
                    </div>
                </div>
                
                <div class="faq-item animate-on-scroll">
                    <div class="faq-question">
                        <span>What happens if I'm not satisfied with my tutor?</span>
                        <i class="fas fa-plus faq-toggle"></i>
                    </div>
                    <div class="faq-answer">
                        <p>All our plans include tutor replacement support. If you're not satisfied with your tutor for any reason, simply contact our support team, and we'll arrange a replacement tutor at no additional cost within 24-48 hours.</p>
                    </div>
                </div>
                
                <div class="faq-item animate-on-scroll">
                    <div class="faq-question">
                        <span>Are the demo classes really free?</span>
                        <i class="fas fa-plus faq-toggle"></i>
                    </div>
                    <div class="faq-answer">
                        <p>Absolutely! All our plans include free demo classes with no obligation to subscribe. This allows you to experience our teaching methodology and assess tutor compatibility before making a commitment.</p>
                    </div>
                </div>
                
                <div class="faq-item animate-on-scroll">
                    <div class="faq-question">
                        <span>What payment methods do you accept?</span>
                        <i class="fas fa-plus faq-toggle"></i>
                    </div>
                    <div class="faq-answer">
                        <p>We accept all major credit/debit cards, net banking, UPI, and popular digital wallets. All transactions are secured with 256-bit SSL encryption for your safety.</p>
                    </div>
                </div>
                
                <div class="faq-item animate-on-scroll">
                    <div class="faq-question">
                        <span>Can I cancel my subscription anytime?</span>
                        <i class="fas fa-plus faq-toggle"></i>
                    </div>
                    <div class="faq-answer">
                        <p>Yes, you can cancel your subscription at any time. If you cancel before the end of your billing period, you'll continue to have access to the service until the period ends. No refunds are provided for partial months.</p>
                    </div>
                </div>
                
                <div class="faq-item animate-on-scroll">
                    <div class="faq-question">
                        <span>Do you offer discounts for long-term subscriptions?</span>
                        <i class="fas fa-plus faq-toggle"></i>
                    </div>
                    <div class="faq-answer">
                        <p>Yes! We offer special discounts for 6-month and annual subscriptions. Contact our sales team for custom quotes and institutional pricing for multiple students.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container">
            <h2 class="animate-on-scroll">Ready to Transform Your Learning Experience?</h2>
            <p class="animate-on-scroll">Join thousands of successful students who have achieved their academic goals with Home Tutor Castle</p>
            <a href="contact.php" class="btn-cta animate-on-scroll">Book a Free Demo Class Now</a>
        </div>
    </section>
    
    <!-- Include Footer -->
    <?php 
    // Flush the main content buffer
    ob_end_flush();
    
    // Check if footer.php exists
    if (file_exists('includes/footer.php')) {
        include('includes/footer.php');
    } else {
        // Fallback footer if includes/footer.php doesn't exist
        echo '<footer style="background: var(--primary-purple); color: white; padding: 40px 0; text-align: center;">';
        echo '<div class="container">';
        echo '<p>&copy; ' . date('Y') . ' Home Tutor Castle. All rights reserved.</p>';
        echo '</div>';
        echo '</footer>';
    }
    ?>
    
    <script>
        // Scroll animation
        document.addEventListener('DOMContentLoaded', function() {
            const animateElements = document.querySelectorAll('.animate-on-scroll');
            
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('animated');
                    }
                });
            }, { threshold: 0.1 });
            
            animateElements.forEach(element => {
                observer.observe(element);
            });
            
            // FAQ toggle functionality
            const faqQuestions = document.querySelectorAll('.faq-question');
            
            faqQuestions.forEach(question => {
                question.addEventListener('click', () => {
                    const faqItem = question.parentElement;
                    
                    // Close other FAQ items
                    document.querySelectorAll('.faq-item').forEach(item => {
                        if (item !== faqItem && item.classList.contains('active')) {
                            item.classList.remove('active');
                        }
                    });
                    
                    // Toggle current FAQ item
                    faqItem.classList.toggle('active');
                });
            });
            
            // Plan card hover animation
            const planCards = document.querySelectorAll('.plan-card');
            
            planCards.forEach(card => {
                card.addEventListener('mouseenter', () => {
                    card.style.transform = 'translateY(-15px)';
                });
                
                card.addEventListener('mouseleave', () => {
                    card.style.transform = 'translateY(0)';
                });
            });
            
            // Add ripple effect to buttons
            const buttons = document.querySelectorAll('.btn-plan, .btn-cta');
            
            buttons.forEach(button => {
                button.addEventListener('click', function(e) {
                    // Create ripple element
                    const ripple = document.createElement('span');
                    const rect = this.getBoundingClientRect();
                    const size = Math.max(rect.width, rect.height);
                    const x = e.clientX - rect.left - size / 2;
                    const y = e.clientY - rect.top - size / 2;
                    
                    ripple.style.width = ripple.style.height = size + 'px';
                    ripple.style.left = x + 'px';
                    ripple.style.top = y + 'px';
                    ripple.classList.add('ripple-effect');
                    
                    // Remove existing ripples
                    const existingRipples = this.querySelectorAll('.ripple-effect');
                    existingRipples.forEach(existingRipple => {
                        existingRipple.remove();
                    });
                    
                    // Add new ripple
                    this.appendChild(ripple);
                    
                    // Remove ripple after animation completes
                    setTimeout(() => {
                        ripple.remove();
                    }, 600);
                });
            });
            
            // Smooth scroll for anchor links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function(e) {
                    const href = this.getAttribute('href');
                    
                    if (href !== '#') {
                        e.preventDefault();
                        const target = document.querySelector(href);
                        
                        if (target) {
                            window.scrollTo({
                                top: target.offsetTop - 80,
                                behavior: 'smooth'
                            });
                        }
                    }
                });
            });
            
            // Highlight current plan if coming from URL parameter
            const urlParams = new URLSearchParams(window.location.search);
            const planParam = urlParams.get('highlight');
            
            if (planParam) {
                const planCards = document.querySelectorAll('.plan-card');
                planCards.forEach(card => {
                    const planName = card.querySelector('.plan-name').textContent.toLowerCase();
                    if (planName.includes(planParam.toLowerCase())) {
                        card.style.boxShadow = '0 20px 40px rgba(246, 160, 77, 0.3)';
                        card.style.border = '2px solid var(--warm-orange)';
                        
                        // Scroll to the plan
                        setTimeout(() => {
                            card.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        }, 500);
                    }
                });
            }
        });
    </script>
</body>
</html>