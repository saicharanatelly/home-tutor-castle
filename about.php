<?php
// Start session at the very top
session_start();

$page_title = "About Us | Home Tutor Castle";
$page_description = "Home Tutor Castle is transforming education in India with certified teachers, personalized tutoring, and quality learning solutions for every student.";
$page_keywords = "about home tutor castle, our story, mission vision values, certified tutors, quality education";

// Start output buffering
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
</head>
<body>
    <!-- Include Header -->
    <?php 
    ob_end_flush();
    if (file_exists('includes/header.php')) {
        include('includes/header.php');
    } else {
        echo '<header style="background: #3B0A6A; padding: 20px; color: white;">
                <div class="container" style="max-width: 1200px; margin: 0 auto;">
                    <h1 style="margin: 0;">Home Tutor Castle</h1>
                </div>
              </header>';
    }
    ob_start();
    ?>

    <!-- Hero Section -->
    <section class="about-hero animated-hero">
        <div class="container">
            <div class="hero-content">
                <h1 class="fade-in-up">Transforming Education in India</h1>
                <p class="hero-subtitle fade-in-up delay-1">Quality learning experiences for every child, everywhere</p>
                <div class="hero-stats fade-in-up delay-2">
                    <div class="stat-item">
                        <div class="stat-number" data-count="2022">0</div>
                        <div class="stat-label">Founded</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number" data-count="5000">0</div>
                        <div class="stat-label">+ Students</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number" data-count="1000">0</div>
                        <div class="stat-label">+ Tutors</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number" data-count="98">0</div>
                        <div class="stat-label">% Satisfaction</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="hero-wave">
            <svg viewBox="0 0 1200 120" preserveAspectRatio="none">
                <path d="M0,0V46.29c47.79,22.2,103.59,32.17,158,28,70.36-5.37,136.33-33.31,206.8-37.5C438.64,32.43,512.34,53.67,583,72.05c69.27,18,138.3,24.88,209.4,13.08,36.15-6,69.85-17.84,104.45-29.34C989.49,25,1113-14.29,1200,52.47V0Z" opacity=".25" fill="currentColor"></path>
                <path d="M0,0V15.81C13,36.92,27.64,56.86,47.69,72.05,99.41,111.27,165,111,224.58,91.58c31.15-10.15,60.09-26.07,89.67-39.8,40.92-19,84.73-46,130.83-49.67,36.26-2.85,70.9,9.42,98.6,31.56,31.77,25.39,62.32,62,103.63,73,40.44,10.79,81.35-6.69,119.13-24.28s75.16-39,116.92-43.05c59.73-5.85,113.28,22.88,168.9,38.84,30.2,8.66,59,6.17,87.09-7.5,22.43-10.89,48-26.93,60.65-49.24V0Z" opacity=".5" fill="currentColor"></path>
                <path d="M0,0V5.63C149.93,59,314.09,71.32,475.83,42.57c43-7.64,84.23-20.12,127.61-26.46,59-8.63,112.48,12.24,165.56,35.4C827.93,77.22,886,95.24,951.2,90c86.53-7,172.46-45.71,248.8-84.81V0Z" fill="currentColor"></path>
            </svg>
        </div>
    </section>

    <!-- Our Story Section -->
    <section class="about-story">
        <div class="container">
            <div class="story-content slide-in-left">
                <h2 class="section-title">OUR STORY</h2>
                
                <div class="story-text">
                    <p>We believe every child in India deserves a safe, reliable, and high-quality learning experience. Driven by our CEO's vision, we focus on certifying and upskilling teachers across India with advanced soft skills, child psychology awareness, and modern teaching practices to deliver consistent academic results.</p>
                    
                    <p>We provide <strong>Personalized 1:1 Home Tuition</strong>, online tuition classes across India, and academic partnerships with Colleges, Universities, and Private Institutions, ensuring flexible and result-oriented learning for students from all backgrounds.</p>
                    
                    <p>From early education <strong>(K3 / Pre-Primary)</strong> to crucial academic stages such as <strong>Class 9, Class 10, Class 11, and Class 12 (CBSE, ICSE, State Boards)</strong>, we match students with verified, background-checked, subject-expert tutors. Our students also receive premium, custom-designed study materials, created by an elite panel of Indian education specialists, aligned with Indian school curricula and competitive exam requirements.</p>
                    
                    <p>With a strong focus on concept clarity, exam preparation, and academic confidence, we help students succeed through trusted home tutors and online teachers in India.</p>
                </div>
            </div>
            
            <div class="story-image slide-in-right">
                <div class="image-frame">
                    <div class="floating-element element-1">
                        <i class="fas fa-child"></i>
                    </div>
                    <div class="floating-element element-2">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <div class="floating-element element-3">
                        <i class="fas fa-trophy"></i>
                    </div>
                    <div class="image-placeholder">
                        <i class="fas fa-heart"></i>
                        <p>Every Child Deserves Quality Education</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Mission, Vision & Values Section -->
    <section class="mission-section">
        <div class="container">
            <h2 class="section-title text-center fade-in-up">Our Mission, Vision & Values</h2>
            <p class="section-subtitle text-center fade-in-up delay-1">Driving educational excellence across India</p>
            
            <div class="mission-cards">
                <div class="mission-card card-1 pop-in">
                    <div class="card-icon">
                        <div class="icon-wrapper mission-icon">
                            <i class="fas fa-bullseye"></i>
                        </div>
                    </div>
                    <div class="card-content">
                        <h3>Our Mission</h3>
                        <p>At HTC, our mission is to provide sustainable and quality teaching opportunities in India by training educators with domain experts, modern teaching methods, and classroom-ready skills.</p>
                        <p class="highlight-text">Through our certified and well-prepared teachers, we aim to secure children's education and help parents feel fully assured about their child's learning outcomes, safety, and academic growth.</p>
                    </div>
                    <div class="card-wave">
                        <svg viewBox="0 0 500 150" preserveAspectRatio="none">
                            <path d="M0.00,49.98 C149.99,150.00 349.20,-49.98 500.00,49.98 L500.00,150.00 L0.00,150.00 Z" fill="currentColor"></path>
                        </svg>
                    </div>
                </div>
                
                <div class="mission-card card-2 pop-in delay-1">
                    <div class="card-icon">
                        <div class="icon-wrapper vision-icon">
                            <i class="fas fa-eye"></i>
                        </div>
                    </div>
                    <div class="card-content">
                        <h3>Our Vision</h3>
                        <p>Our vision is to empower students across India with the right skills, strong knowledge foundation, and self-confidence to shape a better future.</p>
                        <p class="highlight-text">We strive to achieve this by offering innovative, affordable, and high-quality education solutions, designed to support lifelong learning, holistic development, and continuous academic growth.</p>
                    </div>
                    <div class="card-wave">
                        <svg viewBox="0 0 500 150" preserveAspectRatio="none">
                            <path d="M0.00,49.98 C149.99,150.00 349.20,-49.98 500.00,49.98 L500.00,150.00 L0.00,150.00 Z" fill="currentColor"></path>
                        </svg>
                    </div>
                </div>
                
                <div class="mission-card card-3 pop-in delay-2">
                    <div class="card-icon">
                        <div class="icon-wrapper values-icon">
                            <i class="fas fa-handshake"></i>
                        </div>
                    </div>
                    <div class="card-content">
                        <h3>Our Values</h3>
                        <p>We are dedicated to delivering consistently high-quality education by maintaining rigorous standards and continuously improving our teaching practices and learning solutions.</p>
                        <p class="highlight-text">Guided by <strong>integrity, innovation, and inclusivity</strong>, we strive to create a trusted, forward-thinking, and inclusive learning ecosystem for students, parents, and educators.</p>
                    </div>
                    <div class="card-wave">
                        <svg viewBox="0 0 500 150" preserveAspectRatio="none">
                            <path d="M0.00,49.98 C149.99,150.00 349.20,-49.98 500.00,49.98 L500.00,150.00 L0.00,150.00 Z" fill="currentColor"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Education Stages Section -->
    <section class="stages-section">
        <div class="container">
            <h2 class="section-title text-center fade-in-up">Comprehensive Academic Coverage</h2>
            <p class="section-subtitle text-center fade-in-up delay-1">Supporting students at every educational stage</p>
            
            <div class="stages-timeline">
                <div class="timeline-item scale-in">
                    <div class="stage-icon">
                        <i class="fas fa-baby"></i>
                    </div>
                    <div class="stage-content">
                        <h3>Early Education</h3>
                        <p><strong>K3 / Pre-Primary</strong> - Foundational learning and cognitive development</p>
                    </div>
                    <div class="timeline-connector"></div>
                </div>
                
                <div class="timeline-item scale-in delay-1">
                    <div class="stage-icon">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                    <div class="stage-content">
                        <h3>Middle School</h3>
                        <p><strong>Class 6-8</strong> - Concept building and skill development</p>
                    </div>
                    <div class="timeline-connector"></div>
                </div>
                
                <div class="timeline-item scale-in delay-2">
                    <div class="stage-icon">
                        <i class="fas fa-school"></i>
                    </div>
                    <div class="stage-content">
                        <h3>High School</h3>
                        <p><strong>Class 9-10</strong> - Board exam preparation and career guidance</p>
                    </div>
                    <div class="timeline-connector"></div>
                </div>
                
                <div class="timeline-item scale-in delay-3">
                    <div class="stage-icon">
                        <i class="fas fa-university"></i>
                    </div>
                    <div class="stage-content">
                        <h3>Senior Secondary</h3>
                        <p><strong>Class 11-12</strong> - Stream specialization and competitive exam prep</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features-section">
        <div class="container">
            <h2 class="section-title text-center fade-in-up">The HTC Advantage</h2>
            <p class="section-subtitle text-center fade-in-up delay-1">Why thousands choose Home Tutor Castle</p>
            
            <div class="features-grid">
                <div class="feature-card rotate-in">
                    <div class="feature-icon">
                        <i class="fas fa-user-shield"></i>
                    </div>
                    <h3>Verified & Certified Tutors</h3>
                    <p>All tutors undergo rigorous background checks, certification, and continuous skill enhancement</p>
                </div>
                
                <div class="feature-card rotate-in delay-1">
                    <div class="feature-icon">
                        <i class="fas fa-book-open"></i>
                    </div>
                    <h3>Custom Study Materials</h3>
                    <p>Premium, curriculum-aligned materials designed by our elite panel of education specialists</p>
                </div>
                
                <div class="feature-card rotate-in delay-2">
                    <div class="feature-icon">
                        <i class="fas fa-laptop-house"></i>
                    </div>
                    <h3>Flexible Learning Modes</h3>
                    <p>Choose between 1:1 home tuition or online classes based on your convenience</p>
                </div>
                
                <div class="feature-card rotate-in delay-3">
                    <div class="feature-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h3>Progress Tracking</h3>
                    <p>Regular assessments and detailed progress reports for continuous improvement</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container">
            <div class="cta-content fade-in-up">
                <h2>Join India's Trusted Learning Community</h2>
                <p>Experience the difference with certified tutors and personalized education</p>
                <div class="cta-buttons">
                    <a href="contact.php" class="btn btn-primary btn-glow">
                        <i class="fas fa-user-graduate"></i> Find a Tutor
                    </a>
                    <a href="subscription-plans.php" class="btn btn-secondary btn-outline">
                        <i class="fas fa-crown"></i> View Plans
                    </a>
                    <a href="tutor-apply.php" class="btn btn-tertiary btn-outline">
                        <i class="fas fa-chalkboard-teacher"></i> Become a Tutor
                    </a>
                </div>
            </div>
        </div>
    </section>

    <style>
    /* CSS Variables */
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
        --white: #ffffff;
        --shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        --transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* Global Styles */
    body {
        margin: 0;
        padding: 0;
        font-family: 'Inter', sans-serif;
        color: var(--dark-gray);
        background: var(--white);
        line-height: 1.6;
    }

    h1, h2, h3, h4, h5, h6 {
        font-family: 'Poppins', sans-serif;
        font-weight: 600;
        line-height: 1.2;
    }

    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
    }

    /* Animation Classes */
    .fade-in-up {
        opacity: 0;
        transform: translateY(30px);
        animation: fadeInUp 0.8s ease forwards;
    }

    .slide-in-left {
        opacity: 0;
        transform: translateX(-50px);
        animation: slideInLeft 0.8s ease forwards;
    }

    .slide-in-right {
        opacity: 0;
        transform: translateX(50px);
        animation: slideInRight 0.8s ease forwards;
    }

    .pop-in {
        opacity: 0;
        transform: scale(0.8);
        animation: popIn 0.6s ease forwards;
    }

    .scale-in {
        opacity: 0;
        transform: scale(0.9);
        animation: scaleIn 0.6s ease forwards;
    }

    .rotate-in {
        opacity: 0;
        transform: rotateY(-90deg);
        animation: rotateIn 0.8s ease forwards;
    }

    .delay-1 { animation-delay: 0.2s; }
    .delay-2 { animation-delay: 0.4s; }
    .delay-3 { animation-delay: 0.6s; }
    .delay-4 { animation-delay: 0.8s; }

    @keyframes fadeInUp {
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes slideInLeft {
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    @keyframes slideInRight {
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    @keyframes popIn {
        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    @keyframes scaleIn {
        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    @keyframes rotateIn {
        to {
            opacity: 1;
            transform: rotateY(0);
        }
    }

    @keyframes float {
        0%, 100% { transform: translateY(0) rotate(0deg); }
        50% { transform: translateY(-20px) rotate(5deg); }
    }

    @keyframes pulse {
        0%, 100% { transform: scale(1); opacity: 1; }
        50% { transform: scale(1.1); opacity: 0.8; }
    }

    @keyframes glow {
        0%, 100% { box-shadow: 0 0 20px rgba(193, 60, 145, 0.3); }
        50% { box-shadow: 0 0 30px rgba(193, 60, 145, 0.6); }
    }

    /* Hero Section */
    .about-hero {
        background: linear-gradient(135deg, var(--primary-purple) 0%, var(--royal-violet) 100%);
        color: var(--white);
        padding: 100px 0 150px;
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    .hero-content {
        position: relative;
        z-index: 2;
    }

    .about-hero h1 {
        font-size: 3.5rem;
        margin-bottom: 1.5rem;
        font-weight: 700;
        text-shadow: 0 2px 10px rgba(0,0,0,0.2);
        background: linear-gradient(135deg, #fff 0%, #f0f0f0 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .hero-subtitle {
        font-size: 1.4rem;
        opacity: 0.95;
        margin-bottom: 3rem;
        font-weight: 300;
        max-width: 600px;
        margin-left: auto;
        margin-right: auto;
    }

    .hero-stats {
        display: flex;
        justify-content: center;
        gap: 3rem;
        margin-top: 4rem;
        flex-wrap: wrap;
    }

    .stat-item {
        text-align: center;
        padding: 1.5rem 2rem;
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        border-radius: 15px;
        border: 1px solid rgba(255, 255, 255, 0.2);
        transition: var(--transition);
        min-width: 160px;
    }

    .stat-item:hover {
        transform: translateY(-10px);
        background: rgba(255, 255, 255, 0.2);
        border-color: rgba(255, 255, 255, 0.3);
        box-shadow: 0 15px 30px rgba(0,0,0,0.2);
    }

    .stat-number {
        font-size: 2.8rem;
        font-weight: 700;
        color: var(--warm-orange);
        margin-bottom: 0.5rem;
        font-family: 'Poppins', sans-serif;
        text-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }

    .stat-label {
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        color:#fefafa;
        opacity: 0.9;
        font-weight: 500;
    }

    .hero-wave {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        color: var(--light-gray);
        line-height: 0;
    }

    .hero-wave svg {
        display: block;
        width: 100%;
        height: 80px;
    }

    /* Story Section */
    .about-story {
        padding: 100px 0;
        background: var(--light-gray);
    }

    .about-story .container {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 4rem;
        align-items: center;
    }

    .section-title {
        font-size: 2.5rem;
        color: var(--primary-purple);
        margin-bottom: 2rem;
        position: relative;
        display: inline-block;
    }

    .section-title:after {
        content: '';
        position: absolute;
        left: 0;
        bottom: -10px;
        width: 60px;
        height: 4px;
        background: linear-gradient(90deg, var(--magenta-pink), var(--warm-orange));
        border-radius: 2px;
        animation: widthGrow 1.5s ease forwards;
    }

    @keyframes widthGrow {
        from { width: 0; }
        to { width: 60px; }
    }

    .text-center .section-title:after {
        left: 50%;
        transform: translateX(-50%);
    }

    .section-subtitle {
        font-size: 1.2rem;
        color: #666;
        margin-bottom: 3rem;
        max-width: 600px;
        margin-left: auto;
        margin-right: auto;
        line-height: 1.6;
    }

    .story-text p {
        margin-bottom: 1.5rem;
        line-height: 1.8;
        color: #555;
        font-size: 1.1rem;
        text-align: justify;
    }

    .story-text strong {
        color: var(--royal-violet);
        font-weight: 600;
        position: relative;
    }

    .story-text strong:after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        width: 100%;
        height: 2px;
        background: linear-gradient(90deg, transparent, var(--warm-orange), transparent);
        opacity: 0.5;
    }

    .story-image {
        position: relative;
    }

    .image-frame {
        background: linear-gradient(135deg, var(--white), var(--light-gray));
        border-radius: 20px;
        padding: 3rem;
        box-shadow: var(--shadow);
        position: relative;
        overflow: hidden;
        border: 1px solid rgba(94, 43, 151, 0.1);
    }

    .image-placeholder {
        width: 100%;
        height: 320px;
        background: linear-gradient(135deg, var(--royal-violet), var(--primary-purple));
        border-radius: 15px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: var(--white);
        font-size: 5rem;
        transition: var(--transition);
        position: relative;
        overflow: hidden;
    }

    .image-placeholder:before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        transition: 0.5s;
    }

    .image-placeholder:hover:before {
        left: 100%;
    }

    .image-placeholder:hover {
        transform: scale(1.02);
        box-shadow: 0 15px 35px rgba(94, 43, 151, 0.3);
    }

    .image-placeholder i {
        animation: pulse 2s infinite;
    }

    .image-placeholder p {
        margin-top: 1.5rem;
        font-size: 1.3rem;
        font-weight: 600;
        text-align: center;
        max-width: 80%;
        line-height: 1.4;
    }

    .floating-element {
        position: absolute;
        width: 70px;
        height: 70px;
        background: var(--white);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--royal-violet);
        font-size: 1.8rem;
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        animation: float 4s ease-in-out infinite;
        z-index: 2;
        transition: var(--transition);
        border: 3px solid transparent;
    }

    .floating-element:hover {
        transform: scale(1.1);
        border-color: var(--warm-orange);
    }

    .element-1 {
        top: 30px;
        right: 30px;
        background: linear-gradient(135deg, var(--warm-orange), #FF8A00);
        color: var(--white);
        animation-delay: 0s;
    }

    .element-2 {
        bottom: 40px;
        left: 40px;
        background: linear-gradient(135deg, var(--magenta-pink), #f5576c);
        color: var(--white);
        animation-delay: 0.7s;
    }

    .element-3 {
        top: 40%;
        right: 20%;
        background: linear-gradient(135deg, var(--royal-violet), var(--primary-purple));
        color: var(--white);
        animation-delay: 1.4s;
    }

    /* Mission Section */
    .mission-section {
        padding: 100px 0;
        background: var(--white);
    }

    .mission-cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
        gap: 3rem;
        margin-top: 4rem;
    }

    .mission-card {
        background: var(--white);
        border-radius: 20px;
        overflow: hidden;
        box-shadow: var(--shadow);
        transition: var(--transition);
        position: relative;
        border: 1px solid var(--medium-gray);
        padding-top: 60px;
    }

    .mission-card:hover {
        transform: translateY(-15px);
        box-shadow: 0 20px 50px rgba(94, 43, 151, 0.15);
    }

    .card-icon {
        position: absolute;
        top: -30px;
        left: 50%;
        transform: translateX(-50%);
        z-index: 2;
    }

    .icon-wrapper {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        color: var(--white);
        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        border: 5px solid var(--white);
        transition: var(--transition);
        animation: glow 3s infinite;
    }

    .mission-card:hover .icon-wrapper {
        transform: scale(1.1) rotate(360deg);
    }

    .mission-icon { 
        background: linear-gradient(135deg, var(--magenta-pink), var(--royal-violet)); 
    }
    
    .vision-icon { 
        background: linear-gradient(135deg, var(--warm-orange), var(--magenta-pink)); 
    }
    
    .values-icon { 
        background: linear-gradient(135deg, var(--royal-violet), var(--primary-purple)); 
    }

    .card-content {
        padding: 40px 30px 40px;
        text-align: center;
        position: relative;
        z-index: 1;
    }

    .mission-card h3 {
        color: var(--primary-purple);
        margin-bottom: 1.5rem;
        font-size: 1.6rem;
        margin-top: 0.5rem;
    }

    .mission-card p {
        color: #555;
        line-height: 1.7;
        font-size: 1.05rem;
        margin-bottom: 1rem;
    }

    .highlight-text {
        background: linear-gradient(135deg, rgba(94, 43, 151, 0.05), rgba(246, 160, 77, 0.05));
        padding: 1.5rem;
        border-radius: 10px;
        margin-top: 1.5rem;
        border-left: 4px solid var(--warm-orange);
        font-weight: 500;
        color: #444;
    }

    .card-wave {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        color: var(--light-gray);
        line-height: 0;
    }

    /* Stages Section */
    .stages-section {
        padding: 100px 0;
        background: linear-gradient(135deg, var(--light-gray) 0%, #f0f2f5 100%);
    }

    .stages-timeline {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 2rem;
        margin-top: 3rem;
        position: relative;
    }

    .timeline-item {
        background: var(--white);
        padding: 2.5rem 2rem;
        border-radius: 15px;
        text-align: center;
        box-shadow: var(--shadow);
        transition: var(--transition);
        position: relative;
        border-top: 5px solid transparent;
    }

    .timeline-item:hover {
        transform: translateY(-10px);
        border-top-color: var(--royal-violet);
        box-shadow: 0 15px 40px rgba(94, 43, 151, 0.15);
    }

    .stage-icon {
        width: 80px;
        height: 80px;
        margin: 0 auto 1.5rem;
        background: linear-gradient(135deg, var(--royal-violet), var(--magenta-pink));
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--white);
        font-size: 2.2rem;
        transition: var(--transition);
    }

    .timeline-item:hover .stage-icon {
        transform: scale(1.1) rotate(360deg);
        background: linear-gradient(135deg, var(--magenta-pink), var(--warm-orange));
    }

    .stage-content h3 {
        color: var(--primary-purple);
        margin-bottom: 0.8rem;
        font-size: 1.3rem;
    }

    .stage-content p {
        color: #666;
        line-height: 1.6;
        font-size: 0.95rem;
    }

    .stage-content strong {
        color: var(--royal-violet);
        font-weight: 600;
    }

    .timeline-connector {
        position: absolute;
        top: 50%;
        right: -1rem;
        width: 2rem;
        height: 2px;
        background: linear-gradient(90deg, var(--royal-violet), var(--magenta-pink));
        display: none;
    }

    @media (min-width: 768px) {
        .timeline-connector {
            display: block;
        }
    }

    /* Features Section */
    .features-section {
        padding: 100px 0;
        background: var(--white);
    }

    .features-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 2.5rem;
        margin-top: 3rem;
    }

    .feature-card {
        background: var(--white);
        padding: 2.5rem 2rem;
        border-radius: 15px;
        text-align: center;
        box-shadow: var(--shadow);
        transition: var(--transition);
        border: 1px solid var(--medium-gray);
        position: relative;
        overflow: hidden;
    }

    .feature-card:before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 5px;
        background: linear-gradient(90deg, var(--royal-violet), var(--magenta-pink));
        transform: scaleX(0);
        transform-origin: left;
        transition: transform 0.5s ease;
    }

    .feature-card:hover:before {
        transform: scaleX(1);
    }

    .feature-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(94, 43, 151, 0.15);
    }

    .feature-icon {
        width: 80px;
        height: 80px;
        margin: 0 auto 1.5rem;
        background: linear-gradient(135deg, var(--royal-violet), var(--primary-purple));
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--white);
        font-size: 2.2rem;
        transition: var(--transition);
    }

    .feature-card:hover .feature-icon {
        transform: scale(1.1);
        background: linear-gradient(135deg, var(--magenta-pink), var(--warm-orange));
    }

    .feature-card h3 {
        color: var(--primary-purple);
        margin-bottom: 1rem;
        font-size: 1.4rem;
    }

    .feature-card p {
        color: #666;
        line-height: 1.6;
        font-size: 0.95rem;
    }

    /* CTA Section */
    .cta-section {
        padding: 100px 0;
        background: linear-gradient(135deg, var(--primary-purple) 0%, var(--royal-violet) 100%);
        color: var(--white);
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    .cta-content h2 {
        font-size: 2.8rem;
        margin-bottom: 1.5rem;
        color: var(--white);
        text-shadow: 0 2px 5px rgba(0,0,0,0.2);
    }

    .cta-content p {
        font-size: 1.3rem;
        opacity: 0.95;
        margin-bottom: 3rem;
        max-width: 600px;
        margin-left: auto;
        margin-right: auto;
        line-height: 1.6;
    }

    .cta-buttons {
        display: flex;
        gap: 1.5rem;
        justify-content: center;
        flex-wrap: wrap;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        gap: 0.8rem;
        padding: 1.2rem 2.5rem;
        border-radius: 12px;
        font-weight: 600;
        text-decoration: none;
        transition: var(--transition);
        border: 2px solid transparent;
        font-family: 'Poppins', sans-serif;
        font-size: 1rem;
        position: relative;
        overflow: hidden;
    }

    .btn:before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        transition: 0.5s;
    }

    .btn:hover:before {
        left: 100%;
    }

    .btn-primary {
        background: var(--warm-orange);
        color: var(--white);
    }

    .btn-secondary {
        background: transparent;
        color: var(--white);
        border-color: var(--white);
    }

    .btn-tertiary {
        background: var(--magenta-pink);
        color: var(--white);
    }

    .btn-glow {
        box-shadow: 0 5px 20px rgba(246, 160, 77, 0.3);
        animation: glow 3s infinite;
    }

    .btn-outline:hover {
        background: var(--white);
        color: var(--royal-violet);
        border-color: var(--white);
    }

    .btn:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.2);
    }

    /* Responsive Design */
    @media (max-width: 1200px) {
        .about-story .container {
            gap: 3rem;
        }
        
        .mission-cards {
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        }
    }

    @media (max-width: 992px) {
        .about-story .container {
            grid-template-columns: 1fr;
            gap: 3rem;
        }
        
        .story-image {
            order: -1;
        }
        
        .hero-stats {
            gap: 2rem;
        }
        
        .stat-item {
            min-width: 140px;
            padding: 1.2rem 1.5rem;
        }
        
        .stat-number {
            font-size: 2.2rem;
        }
        
        .mission-cards {
            grid-template-columns: 1fr;
            max-width: 500px;
            margin-left: auto;
            margin-right: auto;
        }
    }

    @media (max-width: 768px) {
        .about-hero {
            padding: 80px 0 120px;
        }
        
        .about-hero h1 {
            font-size: 2.5rem;
        }
        
        .hero-subtitle {
            font-size: 1.2rem;
        }
        
        .section-title {
            font-size: 2rem;
        }
        
        .hero-stats {
            gap: 1rem;
        }
        
        .stat-item {
            padding: 1rem;
            min-width: 120px;
        }
        
        .stages-timeline {
            grid-template-columns: 1fr;
        }
        
        .features-grid {
            grid-template-columns: 1fr;
        }
        
        .cta-buttons {
            flex-direction: column;
            align-items: center;
            gap: 1rem;
        }
        
        .btn {
            width: 100%;
            max-width: 300px;
            justify-content: center;
        }
        
        .image-placeholder {
            height: 250px;
            font-size: 4rem;
        }
        
        .image-placeholder p {
            font-size: 1.1rem;
        }
        
        .floating-element {
            width: 60px;
            height: 60px;
            font-size: 1.5rem;
        }
    }

    @media (max-width: 576px) {
        .about-hero h1 {
            font-size: 2rem;
        }
        
        .hero-stats {
            flex-direction: column;
            align-items: center;
        }
        
        .stat-item {
            width: 100%;
            max-width: 250px;
        }
        
        .cta-content h2 {
            font-size: 2rem;
        }
        
        .cta-content p {
            font-size: 1.1rem;
        }
        
        .image-frame {
            padding: 2rem;
        }
    }
    </style>

    <!-- Include Footer -->
    <?php 
    ob_end_flush();
    if (file_exists('includes/footer.php')) {
        include('includes/footer.php');
    } else {
        echo '<footer style="background: #3B0A6A; color: white; padding: 40px 0; text-align: center;">
                <div class="container" style="max-width: 1200px; margin: 0 auto; padding: 0 20px;">
                    <p>&copy; ' . date('Y') . ' Home Tutor Castle. All rights reserved.</p>
                </div>
              </footer>';
    }
    ?>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Counter animation for statistics
        const counters = document.querySelectorAll('.stat-number');
        
        counters.forEach(counter => {
            const target = parseInt(counter.getAttribute('data-count'));
            const increment = target / 100;
            let current = 0;
            
            const updateCounter = () => {
                if (current < target) {
                    current += increment;
                    counter.textContent = Math.floor(current);
                    setTimeout(updateCounter, 20);
                } else {
                    counter.textContent = target;
                }
            };
            
            // Start counter when element is in viewport
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        updateCounter();
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.5 });
            
            observer.observe(counter.parentElement);
        });
        
        // Add scroll animation trigger
        const animateOnScroll = () => {
            const elements = document.querySelectorAll('.fade-in-up, .slide-in-left, .slide-in-right, .pop-in, .scale-in, .rotate-in');
            
            elements.forEach(element => {
                const elementTop = element.getBoundingClientRect().top;
                const elementVisible = 150;
                
                if (elementTop < window.innerHeight - elementVisible) {
                    element.style.animationPlayState = 'running';
                }
            });
        };
        
        // Trigger animations on load
        window.addEventListener('load', animateOnScroll);
        window.addEventListener('scroll', animateOnScroll);
        
        // Add hover effects to cards
        const cards = document.querySelectorAll('.mission-card, .timeline-item, .feature-card');
        cards.forEach(card => {
            card.addEventListener('mouseenter', () => {
                card.style.transform = 'translateY(-10px)';
            });
            
            card.addEventListener('mouseleave', () => {
                card.style.transform = 'translateY(0)';
            });
        });
        
        // Add click ripple effect to buttons
        const buttons = document.querySelectorAll('.btn');
        buttons.forEach(button => {
            button.addEventListener('click', function(e) {
                const ripple = document.createElement('span');
                const rect = this.getBoundingClientRect();
                const size = Math.max(rect.width, rect.height);
                const x = e.clientX - rect.left - size / 2;
                const y = e.clientY - rect.top - size / 2;
                
                ripple.style.cssText = `
                    position: absolute;
                    border-radius: 50%;
                    background: rgba(255, 255, 255, 0.7);
                    transform: scale(0);
                    animation: ripple 0.6s linear;
                    width: ${size}px;
                    height: ${size}px;
                    top: ${y}px;
                    left: ${x}px;
                    pointer-events: none;
                `;
                
                this.style.position = 'relative';
                this.style.overflow = 'hidden';
                this.appendChild(ripple);
                
                setTimeout(() => {
                    ripple.remove();
                }, 600);
            });
        });
        
        // Add CSS for ripple effect
        const style = document.createElement('style');
        style.textContent = `
            @keyframes ripple {
                to {
                    transform: scale(4);
                    opacity: 0;
                }
            }
        `;
        document.head.appendChild(style);
        
        // Animate timeline connectors
        const timelineItems = document.querySelectorAll('.timeline-item');
        timelineItems.forEach((item, index) => {
            if (index < timelineItems.length - 1) {
                const connector = item.querySelector('.timeline-connector');
                if (connector) {
                    setTimeout(() => {
                        connector.style.width = '0';
                        connector.style.transition = 'width 1s ease';
                        setTimeout(() => {
                            connector.style.width = '2rem';
                        }, index * 300 + 500);
                    }, 1000);
                }
            }
        });
        
        // Smooth scrolling for anchor links
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
        
        // Initialize floating elements animation
        const floatingElements = document.querySelectorAll('.floating-element');
        floatingElements.forEach((element, index) => {
            element.style.animationDelay = `${index * 0.3}s`;
        });
    });
    </script>
</body>
</html>