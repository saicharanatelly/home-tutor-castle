<?php
// Start session at the very top
session_start();

$page_title = "Our Services | Home Tutor Castle";
$page_description = "Home Tutor Castle connects experienced home tutors and online tutors with students and parents, offering convenient and personalized learning solutions.";
$page_keywords = "home tutoring services, online tutors, private tutors, tutor matching, AI tutor matching, qualified tutors";

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
    <section class="services-hero">
        <div class="container">
            <div class="hero-content">
                <h1>Connecting Students with <span>Expert Tutors</span></h1>
                <p class="hero-subtitle">Home Tutor Castle is a trusted home tutoring website that connects experienced home tutors and online tutors with students and parents, offering convenient and personalized learning solutions.</p>
                <a href="#our-services" class="cta-button">Explore Our Services</a>
            </div>
            <div class="hero-image">
                <div class="floating-stats">
                    <div class="stat">
                        <span class="number">5000+</span>
                        <span class="label">Expert Tutors</span>
                    </div>
                    <div class="stat">
                        <span class="number">98%</span>
                        <span class="label">Success Rate</span>
                    </div>
                    <div class="stat">
                        <span class="number">45 Days</span>
                        <span class="label">Money Back</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Our Services Section -->
    <section id="our-services" class="services-section">
        <div class="container">
            <div class="section-header">
                <h2>Our Services</h2>
                <p>We provide affordable home tutors and online tutors in India and other countries, connecting students and parents with experienced private teachers for personalized learning. With a transparent tutor-matching process, we help students achieve academic excellence through professional guidance and quality education.</p>
            </div>
            
            <div class="services-grid">
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-home"></i>
                    </div>
                    <h3>Home Tutors</h3>
                    <p>Professional home tutors for one-on-one personalized attention at your convenience</p>
                    <a href="#" class="service-link">Learn More <i class="fas fa-arrow-right"></i></a>
                </div>
                
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-laptop"></i>
                    </div>
                    <h3>Online Tutors</h3>
                    <p>Virtual learning with certified online tutors from anywhere, anytime</p>
                    <a href="#" class="service-link">Learn More <i class="fas fa-arrow-right"></i></a>
                </div>
                
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-briefcase"></i>
                    </div>
                    <h3>Teaching Jobs</h3>
                    <p>Opportunities for qualified teachers and tutors to join our platform</p>
                    <a href="#" class="service-link">Learn More <i class="fas fa-arrow-right"></i></a>
                </div>
                
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <h3>School Admissions</h3>
                    <p>Guidance and support for school admissions and educational counseling</p>
                    <a href="#" class="service-link">Learn More <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
        </div>
    </section>

    <!-- Whom We Serve Section -->
    <section class="serve-section">
        <div class="container">
            <div class="section-header">
                <h2>Whom We Serve</h2>
                <p>We cater to diverse educational needs across different segments</p>
            </div>
            
            <div class="serve-categories">
                <div class="category-card">
                    <div class="category-icon students">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                    <h4>Students</h4>
                    <p>K-12 to College students seeking academic excellence</p>
                </div>
                
                <div class="category-card">
                    <div class="category-icon parents">
                        <i class="fas fa-users"></i>
                    </div>
                    <h4>Parents</h4>
                    <p>Parents looking for reliable tutors for their children</p>
                </div>
                
                <div class="category-card">
                    <div class="category-icon tutors">
                        <i class="fas fa-chalkboard-teacher"></i>
                    </div>
                    <h4>Tutors</h4>
                    <p>Qualified educators seeking teaching opportunities</p>
                </div>
                
                <div class="category-card">
                    <div class="category-icon schools">
                        <i class="fas fa-school"></i>
                    </div>
                    <h4>Schools</h4>
                    <p>Educational institutions for partnership & collaborations</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Our Facilities Section -->
    <section class="facilities-section">
        <div class="container">
            <div class="section-header">
                <h2>Our Facilities</h2>
                <p>We provide comprehensive educational solutions with premium facilities</p>
            </div>
            
            <div class="facilities-grid">
                <div class="facility-card highlight-card">
                    <div class="facility-header">
                        <div class="facility-icon">
                            <i class="fas fa-user-check"></i>
                        </div>
                        <h3>Qualified and Experienced Tutors</h3>
                    </div>
                    <p>Home Tutor Castle ensures only <strong>Qualified and Experienced Tutors</strong> are available on our platform, providing quality education through professional guidance.</p>
                </div>
                
                <div class="facility-card">
                    <div class="facility-header">
                        <div class="facility-icon">
                            <i class="fas fa-mobile-alt"></i>
                        </div>
                        <h3>Online Learning Platform and App</h3>
                    </div>
                    <p>Our comprehensive Online Learning Platform, Website, and Mobile App make it easy for students and parents to connect with the right home teacher and meet their tutoring expectations.</p>
                </div>
                
                <div class="facility-card">
                    <div class="facility-header">
                        <div class="facility-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h3>100% Verified and Accurate Leads</h3>
                    </div>
                    <p>Tutors receive 100% Accurate and Verified Leads after successful profile verification on our website. See our <a href="subscription-plans.php" style="color: var(--magenta-pink);">Subscription Plans</a> for details.</p>
                </div>
                
                <div class="facility-card">
                    <div class="facility-header">
                        <div class="facility-icon">
                            <i class="fas fa-handshake"></i>
                        </div>
                        <h3>Transparent and Hassle Free Services</h3>
                    </div>
                    <p>We offer completely Transparent and Hassle Free Services with no hidden terms. We prioritize customer privacy and use completely secured payment methods.</p>
                </div>
                
                <div class="facility-card highlight">
                    <div class="facility-header">
                        <div class="facility-icon">
                            <i class="fas fa-award"></i>
                        </div>
                        <h3>100% Satisfaction Guarantee</h3>
                    </div>
                    <p>We stand by the quality of our services with our satisfaction guarantee</p>
                    <div class="guarantee-box">
                        <div class="guarantee-icon">
                            <i class="fas fa-money-bill-wave"></i>
                        </div>
                        <div class="guarantee-content">
                            <h4>45 Days Money Back Guarantee</h4>
                            <p>We offer a no questions asked refund policy for 45 days from the date you complete your profile verification.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Our Process Section -->
    <section class="process-section">
        <div class="container">
            <div class="section-header">
                <h2>Our Process</h2>
                <p>Simple steps to connect with the perfect tutor</p>
            </div>
            
            <div class="process-container">
                <div class="process-steps">
                    <div class="process-step">
                        <div class="step-number">1</div>
                        <div class="step-content">
                            <h3>Post Requirements</h3>
                            <p>Share your specific needs and preferences</p>
                        </div>
                    </div>
                    
                    <div class="process-step">
                        <div class="step-number">2</div>
                        <div class="step-content">
                            <h3>AI Matching</h3>
                            <p><strong>Our AI matches</strong> you with the most suitable tutors based on your requirements</p>
                        </div>
                    </div>
                    
                    <div class="process-step">
                        <div class="step-number">3</div>
                        <div class="step-content">
                            <h3>Review Matches</h3>
                            <p><strong>We match you</strong> with the best tutors and you can review their profiles</p>
                        </div>
                    </div>
                    
                    <div class="process-step">
                        <div class="step-number">4</div>
                        <div class="step-content">
                            <h3>Demo Session</h3>
                            <p>Take a free demo class with matched tutors</p>
                        </div>
                    </div>
                    
                    <div class="process-step">
                        <div class="step-number">5</div>
                        <div class="step-content">
                            <h3>Start Learning</h3>
                            <p>Begin your personalized learning journey</p>
                        </div>
                    </div>
                </div>
                
                <div class="process-image">
                    <div class="ai-matching-visual">
                        <!-- Student Profile -->
                        <div class="profile-box student-profile">
                            <i class="fas fa-user-graduate"></i>
                            <h5>Student Profile</h5>
                            <p>Academic needs & preferences</p>
                        </div>
                        
                        <!-- Tutor Profile -->
                        <div class="profile-box tutor-profile">
                            <i class="fas fa-chalkboard-teacher"></i>
                            <h5>Tutor Profile</h5>
                            <p>Expertise & availability</p>
                        </div>
                        
                        <!-- AI Matching Engine -->
                        <div class="ai-center">
                            <i class="fas fa-brain"></i>
                            <h4>AI Matching Engine</h4>
                            <p>Smart tutor matching algorithm</p>
                        </div>
                        
                        <!-- Perfect Match Result -->
                        <div class="profile-box match-result">
                            <i class="fas fa-heart"></i>
                            <h5>Perfect Match</h5>
                            <p>Optimal tutor-student pairing</p>
                        </div>
                        
                        <!-- Connecting Lines -->
                        <div class="connecting-lines">
                            <!-- Student to AI -->
                            <div class="line student-to-ai">
                                <div class="dot start"></div>
                                <div class="dot end"></div>
                            </div>
                            
                            <!-- Tutor to AI -->
                            <div class="line tutor-to-ai">
                                <div class="dot start"></div>
                                <div class="dot end"></div>
                            </div>
                            
                            <!-- AI to Match -->
                            <div class="line ai-to-match">
                                <div class="dot start"></div>
                                <div class="dot end"></div>
                            </div>
                        </div>
                        
                        <!-- Direction Arrows -->
                        <div class="connection-arrow arrow-student"></div>
                        <div class="connection-arrow arrow-tutor"></div>
                        <div class="connection-arrow arrow-match"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container">
            <div class="cta-content">
                <h2>Ready to Transform Learning Experience?</h2>
                <p>Join thousands of satisfied students and parents who have achieved academic excellence with Home Tutor Castle</p>
                <div class="cta-buttons">
                    <a href="student-portal.php" class="cta-button primary">Find a Tutor Now</a>
                    <a href="subscription-plans.php" class="cta-button secondary">View Subscription Plans</a>
                </div>
            </div>
        </div>
    </section>

    <style>
    /* CSS Variables for New Color Scheme */
    :root {
        --primary-purple: #3B0A6A;
        --royal-violet: #5E2B97;
        --magenta-pink: #C13C91;
        --warm-orange: #F6A04D;
        --white: #FFFFFF;
        --light-gray: #F8F9FA;
        --medium-gray: #E9ECEF;
        --dark-gray: #343A40;
        --text-color: #333333;
        --shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        --radius: 12px;
        --transition: all 0.3s ease;
    }

    /* Font Family Declarations */
    body, p, .hero-subtitle, .section-header p, .service-card p, .category-card p, 
    .facility-card p, .step-content p, .image-overlay p, .cta-content p {
        font-family: 'Inter', 'Roboto', sans-serif;
        font-size: 16px;
        line-height: 1.6;
    }

    h1, h2, h3, h4, .section-header h2, .service-card h3, .category-card h4,
    .facility-card h3, .step-content h3, .image-overlay h4, .cta-content h2,
    .cta-button, .service-link, .guarantee-content h4 {
        font-family: 'Poppins', sans-serif;
    }

    /* Global Styles */
    body {
        margin: 0;
        padding: 0;
        background-color: var(--white);
        color: var(--text-color);
    }

    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
    }

    /* Hero Section */
    .services-hero {
        background: linear-gradient(135deg, rgba(59, 10, 106, 0.1) 0%, rgba(255, 255, 255, 1) 100%);
        padding: 80px 0;
        overflow: hidden;
    }

    .services-hero .container {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 4rem;
        align-items: center;
    }

    .hero-content h1 {
        font-size: 3.2rem;
        color: var(--primary-purple);
        margin-bottom: 1.5rem;
        line-height: 1.2;
        font-weight: 700;
        animation: fadeInUp 1s ease-out;
    }

    .hero-content h1 span {
        color: var(--royal-violet);
    }

    .hero-subtitle {
        font-size: 1.2rem;
        color: #666;
        margin-bottom: 2.5rem;
        line-height: 1.8;
        max-width: 90%;
        animation: fadeInUp 1s ease-out 0.2s both;
    }

    .cta-button {
        background: var(--magenta-pink);
        color: var(--white);
        padding: 15px 35px;
        border-radius: 30px;
        text-decoration: none;
        font-weight: 600;
        transition: var(--transition);
        border: 2px solid var(--magenta-pink);
        font-family: 'Poppins', sans-serif;
        font-size: 16px;
        display: inline-block;
        animation: fadeInUp 1s ease-out 0.4s both;
    }

    .cta-button:hover {
        background: transparent;
        color: var(--magenta-pink);
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(193, 60, 145, 0.2);
    }

    .hero-image {
        position: relative;
        animation: fadeInRight 1s ease-out;
    }

    .floating-stats {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
        background: var(--white);
        padding: 2.5rem;
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        max-width: 300px;
        margin-left: auto;
        border: 2px solid rgba(94, 43, 151, 0.1);
        transform: translateY(0);
        transition: var(--transition);
        animation: float 3s ease-in-out infinite;
    }

    .floating-stats:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(94, 43, 151, 0.15);
    }

    .floating-stats .stat {
        text-align: center;
        padding: 1rem;
        border-bottom: 1px solid var(--medium-gray);
        opacity: 0;
        transform: translateY(20px);
        animation: fadeInUpStat 0.5s ease-out forwards;
    }

    .floating-stats .stat:nth-child(1) { animation-delay: 0.6s; }
    .floating-stats .stat:nth-child(2) { animation-delay: 0.8s; }
    .floating-stats .stat:nth-child(3) { animation-delay: 1s; }

    .floating-stats .stat:last-child {
        border-bottom: none;
    }

    .floating-stats .number {
        display: block;
        font-size: 2.2rem;
        font-weight: 700;
        color: var(--royal-violet);
        margin-bottom: 0.5rem;
    }

    .floating-stats .label {
        color: #666;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-weight: 500;
    }

    /* Services Section */
    .services-section {
        padding: 80px 0;
        background: var(--white);
    }

    .section-header {
        text-align: center;
        margin-bottom: 4rem;
    }

    .section-header h2 {
        font-size: 2.5rem;
        color: var(--primary-purple);
        margin-bottom: 1.5rem;
        font-weight: 700;
        position: relative;
        display: inline-block;
    }

    .section-header h2::after {
        content: '';
        position: absolute;
        bottom: -10px;
        left: 50%;
        transform: translateX(-50%);
        width: 80px;
        height: 4px;
        background: linear-gradient(90deg, var(--royal-violet), var(--magenta-pink));
        border-radius: 2px;
    }

    .section-header p {
        color: #666;
        font-size: 1.1rem;
        max-width: 900px;
        margin: 2rem auto 0;
        line-height: 1.7;
    }

    .services-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 2rem;
    }

    .service-card {
        background: var(--white);
        padding: 2.5rem 2rem;
        border-radius: var(--radius);
        text-align: center;
        box-shadow: var(--shadow);
        transition: var(--transition);
        border: 2px solid transparent;
        position: relative;
        overflow: hidden;
        opacity: 0;
        transform: translateY(30px);
        animation: fadeInUpCard 0.6s ease-out forwards;
    }

    .service-card:nth-child(1) { animation-delay: 0.2s; }
    .service-card:nth-child(2) { animation-delay: 0.3s; }
    .service-card:nth-child(3) { animation-delay: 0.4s; }
    .service-card:nth-child(4) { animation-delay: 0.5s; }

    .service-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--royal-violet), var(--magenta-pink));
        opacity: 0;
        transition: var(--transition);
    }

    .service-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(94, 43, 151, 0.15);
    }

    .service-card:hover::before {
        opacity: 1;
    }

    .service-icon {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, var(--royal-violet), var(--magenta-pink));
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        color: var(--white);
        font-size: 2rem;
        transition: var(--transition);
    }

    .service-card:hover .service-icon {
        transform: scale(1.1) rotate(5deg);
        box-shadow: 0 10px 20px rgba(94, 43, 151, 0.3);
    }

    .service-card h3 {
        color: var(--primary-purple);
        margin-bottom: 1rem;
        font-size: 1.4rem;
        font-weight: 600;
    }

    .service-card p {
        color: #666;
        margin-bottom: 1.5rem;
        line-height: 1.6;
        font-size: 0.95rem;
    }

    .service-link {
        color: var(--magenta-pink);
        text-decoration: none;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: var(--transition);
        font-family: 'Poppins', sans-serif;
    }

    .service-link:hover {
        gap: 1rem;
        color: var(--royal-violet);
    }

    /* Serve Section */
    .serve-section {
        padding: 80px 0;
        background: linear-gradient(135deg, rgba(94, 43, 151, 0.05) 0%, rgba(255, 255, 255, 1) 100%);
    }

    .serve-categories {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 2rem;
    }

    .category-card {
        background: var(--white);
        padding: 2.5rem 2rem;
        border-radius: var(--radius);
        text-align: center;
        box-shadow: var(--shadow);
        transition: var(--transition);
        opacity: 0;
        transform: translateY(30px);
        animation: fadeInUpCard 0.6s ease-out forwards;
    }

    .category-card:nth-child(1) { animation-delay: 0.2s; }
    .category-card:nth-child(2) { animation-delay: 0.3s; }
    .category-card:nth-child(3) { animation-delay: 0.4s; }
    .category-card:nth-child(4) { animation-delay: 0.5s; }

    .category-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 30px rgba(94, 43, 151, 0.15);
    }

    .category-icon {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        font-size: 2rem;
        color: var(--white);
        transition: var(--transition);
    }

    .category-card:hover .category-icon {
        transform: rotate(15deg) scale(1.1);
    }

    .category-icon.students {
        background: linear-gradient(135deg, var(--royal-violet), #764ba2);
    }

    .category-icon.parents {
        background: linear-gradient(135deg, var(--magenta-pink), #f5576c);
    }

    .category-icon.tutors {
        background: linear-gradient(135deg, #4facfe, var(--warm-orange));
    }

    .category-icon.schools {
        background: linear-gradient(135deg, var(--warm-orange), #38f9d7);
    }

    .category-card h4 {
        color: var(--primary-purple);
        margin-bottom: 0.8rem;
        font-size: 1.3rem;
        font-weight: 600;
    }

    .category-card p {
        color: #666;
        font-size: 0.95rem;
        line-height: 1.5;
    }

    /* Facilities Section */
    .facilities-section {
        padding: 80px 0;
        background: var(--white);
    }

    .facilities-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
        gap: 2rem;
    }

    .facility-card {
        background: var(--white);
        padding: 2rem;
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        transition: var(--transition);
        border-left: 4px solid var(--royal-violet);
        height: 100%;
        opacity: 0;
        transform: translateY(30px);
        animation: fadeInUpCard 0.6s ease-out forwards;
    }

    .facility-card:nth-child(1) { animation-delay: 0.2s; }
    .facility-card:nth-child(2) { animation-delay: 0.3s; }
    .facility-card:nth-child(3) { animation-delay: 0.4s; }
    .facility-card:nth-child(4) { animation-delay: 0.5s; }
    .facility-card:nth-child(5) { animation-delay: 0.6s; }

    .facility-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 30px rgba(94, 43, 151, 0.1);
    }

    .facility-card.highlight-card {
        background: linear-gradient(135deg, rgba(94, 43, 151, 0.05), rgba(255, 255, 255, 1));
        border-left: 4px solid var(--warm-orange);
    }

    .facility-card.highlight-card h3 {
        color: var(--primary-purple);
    }

    .facility-card.highlight {
        background: linear-gradient(135deg, rgba(94, 43, 151, 0.1), rgba(255, 255, 255, 1));
        border: 2px solid var(--royal-violet);
        grid-column: span 2;
    }

    .facility-header {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .facility-icon {
        width: 60px;
        height: 60px;
        background: var(--royal-violet);
        color: var(--white);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        flex-shrink: 0;
        transition: var(--transition);
    }

    .facility-card:hover .facility-icon {
        transform: scale(1.1);
        background: linear-gradient(135deg, var(--royal-violet), var(--magenta-pink));
    }

    .facility-card h3 {
        color: var(--primary-purple);
        font-size: 1.3rem;
        flex: 1;
        font-weight: 600;
        line-height: 1.4;
    }

    .facility-card p {
        color: #666;
        line-height: 1.7;
        margin-bottom: 1.5rem;
        font-size: 0.95rem;
    }

    .facility-card p strong {
        color: var(--primary-purple);
    }

    .guarantee-box {
        background: var(--white);
        padding: 1.5rem;
        border-radius: 10px;
        border: 2px dashed var(--warm-orange);
        display: flex;
        align-items: center;
        gap: 1.5rem;
        margin-top: 1.5rem;
        transition: var(--transition);
    }

    .guarantee-box:hover {
        border-style: solid;
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(246, 160, 77, 0.1);
    }

    .guarantee-icon {
        width: 60px;
        height: 60px;
        background: var(--warm-orange);
        color: var(--white);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        flex-shrink: 0;
        animation: rotate 3s linear infinite;
    }

    .guarantee-content h4 {
        color: var(--primary-purple);
        margin-bottom: 0.5rem;
        font-size: 1.2rem;
        font-weight: 600;
    }

    .guarantee-content p {
        color: #666;
        font-size: 0.9rem;
        margin-bottom: 0;
        line-height: 1.5;
    }

    /* Process Section */
    .process-section {
        padding: 80px 0;
        background: linear-gradient(135deg, rgba(94, 43, 151, 0.05) 0%, rgba(255, 255, 255, 1) 100%);
    }

    .process-container {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 4rem;
        align-items: center;
    }

    .process-steps {
        display: flex;
        flex-direction: column;
        gap: 2rem;
    }

    .process-step {
        display: flex;
        align-items: center;
        gap: 1.5rem;
        padding: 1.5rem;
        background: var(--white);
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        transition: var(--transition);
        opacity: 0;
        transform: translateX(-30px);
        animation: fadeInLeftStep 0.6s ease-out forwards;
    }

    .process-step:nth-child(1) { animation-delay: 0.2s; }
    .process-step:nth-child(2) { animation-delay: 0.3s; }
    .process-step:nth-child(3) { animation-delay: 0.4s; }
    .process-step:nth-child(4) { animation-delay: 0.5s; }
    .process-step:nth-child(5) { animation-delay: 0.6s; }

    .process-step:hover {
        transform: translateX(10px);
        box-shadow: 0 10px 25px rgba(94, 43, 151, 0.15);
    }

    .step-number {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, var(--royal-violet), var(--magenta-pink));
        color: var(--white);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        font-weight: 700;
        flex-shrink: 0;
        transition: var(--transition);
    }

    .process-step:hover .step-number {
        transform: scale(1.1) rotate(360deg);
    }

    .step-content h3 {
        color: var(--primary-purple);
        margin-bottom: 0.5rem;
        font-size: 1.3rem;
        font-weight: 600;
    }

    .step-content p {
        color: #666;
        font-size: 0.95rem;
        line-height: 1.5;
    }

    .step-content p strong {
        color: var(--primary-purple);
    }

    .process-image {
        position: relative;
        height: 500px;
        animation: fadeInRight 1s ease-out;
    }

    /* AI Matching Visualization */
    .ai-matching-visual {
        background: linear-gradient(135deg, rgba(59, 10, 106, 0.05), rgba(255, 255, 255, 1));
        border-radius: var(--radius);
        padding: 3rem;
        height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        position: relative;
        box-shadow: var(--shadow);
        border: 2px solid rgba(94, 43, 151, 0.1);
        overflow: hidden;
    }

    .ai-center {
        background: linear-gradient(135deg, var(--royal-violet), var(--magenta-pink));
        width: 180px;
        height: 180px;
        border-radius: 50%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: var(--white);
        margin-bottom: 2rem;
        z-index: 3;
        box-shadow: 0 15px 35px rgba(94, 43, 151, 0.4);
        position: relative;
        transition: all 0.5s ease;
        animation: float 3s ease-in-out infinite;
    }

    .ai-center:hover {
        transform: scale(1.05);
        box-shadow: 0 20px 40px rgba(94, 43, 151, 0.5);
    }

    .ai-center i {
        font-size: 3.5rem;
        margin-bottom: 0.8rem;
        animation: pulse 2s infinite;
    }

    .ai-center h4 {
        font-size: 1.1rem;
        margin: 0;
        font-weight: 700;
        text-align: center;
        letter-spacing: 0.5px;
    }

    .ai-center p {
        font-size: 0.85rem;
        opacity: 0.9;
        margin: 0.3rem 0 0;
        text-align: center;
        font-weight: 300;
        max-width: 140px;
    }

    .connecting-lines {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        z-index: 1;
    }

    .line {
        position: absolute;
        width: 180px;
        height: 3px;
        background: linear-gradient(90deg, transparent, var(--magenta-pink), transparent);
        animation: lineFlow 3s infinite linear;
        filter: drop-shadow(0 0 5px rgba(193, 60, 145, 0.3));
    }

    .student-to-ai {
        top: 45%;
        left: 10%;
        transform: rotate(-25deg);
        animation-delay: 0.5s;
    }

    .tutor-to-ai {
        top: 45%;
        right: 10%;
        transform: rotate(25deg);
        animation-delay: 1s;
    }

    .ai-to-match {
        bottom: 20%;
        left: 50%;
        transform: translateX(-50%) rotate(90deg);
        width: 120px;
        animation-delay: 1.5s;
    }

    .dot {
        position: absolute;
        width: 14px;
        height: 14px;
        background: var(--warm-orange);
        border-radius: 50%;
        animation: bounce 1.5s infinite;
        box-shadow: 0 0 10px rgba(246, 160, 77, 0.5);
    }

    .dot.start {
        left: 0;
        top: -5px;
    }

    .dot.end {
        right: 0;
        top: -5px;
    }

    .profile-box {
        position: absolute;
        background: var(--white);
        padding: 1.5rem;
        border-radius: 15px;
        text-align: center;
        color: var(--primary-purple);
        width: 160px;
        box-shadow: 0 10px 25px rgba(94, 43, 151, 0.15);
        transition: all 0.3s ease;
        z-index: 2;
        border: 2px solid rgba(94, 43, 151, 0.1);
    }

    .profile-box:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(94, 43, 151, 0.25);
        border-color: var(--royal-violet);
    }

    .profile-box.student-profile {
        top: 25%;
        left: 5%;
        animation: floatSide 4s ease-in-out infinite;
    }

    .profile-box.tutor-profile {
        top: 25%;
        right: 5%;
        animation: floatSide 4s ease-in-out infinite 0.5s;
    }

    .profile-box.match-result {
        bottom: 10%;
        left: 50%;
        transform: translateX(-50%);
        background: linear-gradient(135deg, var(--warm-orange), #FF8A00);
        color: var(--white);
        padding: 1.5rem;
        width: 180px;
        animation: pulseGlow 2s infinite;
        border: none;
        box-shadow: 0 10px 30px rgba(246, 160, 77, 0.4);
    }

    .profile-box.match-result:hover {
        background: linear-gradient(135deg, #FF8A00, var(--warm-orange));
        box-shadow: 0 15px 35px rgba(246, 160, 77, 0.6);
    }

    .profile-box i {
        font-size: 2.5rem;
        margin-bottom: 1rem;
        display: block;
    }

    .profile-box.student-profile i {
        color: var(--royal-violet);
    }

    .profile-box.tutor-profile i {
        color: var(--magenta-pink);
    }

    .profile-box.match-result i {
        color: var(--white);
        animation: heartbeat 1.5s infinite;
    }

    .profile-box h5 {
        font-size: 1rem;
        margin: 0 0 0.3rem 0;
        font-weight: 700;
        font-family: 'Poppins', sans-serif;
    }

    .profile-box p {
        font-size: 0.85rem;
        margin: 0;
        opacity: 0.8;
        font-weight: 400;
        line-height: 1.3;
    }

    .match-result h5 {
        font-size: 1.1rem;
        margin-bottom: 0.5rem;
    }

    .connection-arrow {
        position: absolute;
        width: 0;
        height: 0;
        border-style: solid;
        z-index: 1;
    }

    .arrow-student {
        top: 40%;
        left: 28%;
        border-width: 8px 0 8px 15px;
        border-color: transparent transparent transparent var(--royal-violet);
        opacity: 0.7;
        animation: arrowPulse 2s infinite;
    }

    .arrow-tutor {
        top: 40%;
        right: 28%;
        border-width: 8px 15px 8px 0;
        border-color: transparent var(--magenta-pink) transparent transparent;
        opacity: 0.7;
        animation: arrowPulse 2s infinite 0.5s;
    }

    .arrow-match {
        bottom: 25%;
        left: 50%;
        transform: translateX(-50%);
        border-width: 15px 8px 0 8px;
        border-color: var(--warm-orange) transparent transparent transparent;
        opacity: 0.7;
        animation: arrowPulse 2s infinite 1s;
    }

    /* CTA Section */
    .cta-section {
        padding: 80px 0;
        background: linear-gradient(135deg, var(--primary-purple), var(--royal-violet));
        color: var(--white);
        text-align: center;
    }

    .cta-content h2 {
        font-size: 2.5rem;
        margin-bottom: 1.5rem;
        font-weight: 700;
        color: var(--white);
        animation: fadeInUp 1s ease-out;
    }

    .cta-content p {
        font-size: 1.2rem;
        opacity: 0.9;
        margin-bottom: 2.5rem;
        max-width: 600px;
        margin-left: auto;
        margin-right: auto;
        animation: fadeInUp 1s ease-out 0.2s both;
    }

    .cta-buttons {
        display: flex;
        gap: 1.5rem;
        justify-content: center;
        animation: fadeInUp 1s ease-out 0.4s both;
    }

    .cta-button.primary {
        background: var(--magenta-pink);
        color: var(--white);
        padding: 1rem 2.5rem;
        border-radius: 30px;
        font-weight: 600;
        text-decoration: none;
        transition: var(--transition);
        border: 2px solid var(--magenta-pink);
        font-family: 'Poppins', sans-serif;
    }

    .cta-button.primary:hover {
        background: transparent;
        color: var(--white);
        border-color: var(--white);
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
    }

    .cta-button.secondary {
        background: transparent;
        color: var(--white);
        border: 2px solid var(--white);
        padding: 1rem 2.5rem;
        border-radius: 30px;
        font-weight: 600;
        text-decoration: none;
        transition: var(--transition);
        font-family: 'Poppins', sans-serif;
    }

    .cta-button.secondary:hover {
        background: var(--white);
        color: var(--primary-purple);
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
    }

    /* Animation Keyframes */
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

    @keyframes fadeInUpStat {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes fadeInUpCard {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes fadeInLeftStep {
        from {
            opacity: 0;
            transform: translateX(-30px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    @keyframes fadeInRight {
        from {
            opacity: 0;
            transform: translateX(30px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    @keyframes float {
        0%, 100% {
            transform: translateY(0);
        }
        50% {
            transform: translateY(-15px);
        }
    }

    @keyframes floatSide {
        0%, 100% {
            transform: translateY(0);
        }
        50% {
            transform: translateY(-10px);
        }
    }

    @keyframes lineFlow {
        0% {
            background-position: -200% center;
        }
        100% {
            background-position: 200% center;
        }
    }

    @keyframes pulse {
        0%, 100% {
            opacity: 1;
            transform: scale(1);
        }
        50% {
            opacity: 0.8;
            transform: scale(1.1);
        }
    }

    @keyframes pulseGlow {
        0%, 100% {
            box-shadow: 0 10px 30px rgba(246, 160, 77, 0.4);
        }
        50% {
            box-shadow: 0 10px 40px rgba(246, 160, 77, 0.7);
        }
    }

    @keyframes bounce {
        0%, 100% {
            transform: translateY(0);
        }
        50% {
            transform: translateY(-15px);
        }
    }

    @keyframes heartbeat {
        0%, 100% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.1);
        }
    }

    @keyframes arrowPulse {
        0%, 100% {
            opacity: 0.3;
        }
        50% {
            opacity: 1;
        }
    }

    @keyframes rotate {
        from {
            transform: rotate(0deg);
        }
        to {
            transform: rotate(360deg);
        }
    }

    /* Responsive Design */
    @media (max-width: 1200px) {
        .facility-card.highlight {
            grid-column: span 1;
        }
        
        .ai-matching-visual {
            height: 450px;
            padding: 2rem;
        }
        
        .ai-center {
            width: 160px;
            height: 160px;
        }
        
        .profile-box {
            width: 140px;
            padding: 1.2rem;
        }
        
        .line {
            width: 150px;
        }
    }

    @media (max-width: 992px) {
        .services-hero .container {
            grid-template-columns: 1fr;
            text-align: center;
            gap: 3rem;
        }
        
        .hero-content h1 {
            font-size: 2.8rem;
        }
        
        .hero-subtitle {
            max-width: 100%;
        }
        
        .floating-stats {
            margin: 0 auto;
        }
        
        .process-container {
            grid-template-columns: 1fr;
            gap: 3rem;
        }
        
        .ai-matching-visual {
            height: 400px;
            margin-top: 2rem;
        }
        
        .ai-center {
            width: 140px;
            height: 140px;
            margin-bottom: 1.5rem;
        }
        
        .ai-center i {
            font-size: 2.8rem;
        }
        
        .profile-box {
            width: 130px;
            padding: 1rem;
        }
        
        .profile-box.student-profile {
            left: 3%;
        }
        
        .profile-box.tutor-profile {
            right: 3%;
        }
        
        .line {
            width: 130px;
        }
    }

    @media (max-width: 768px) {
        .services-hero, .services-section, .serve-section, 
        .facilities-section, .process-section, .cta-section {
            padding: 60px 0;
        }
        
        .hero-content h1 {
            font-size: 2.2rem;
        }
        
        .section-header h2 {
            font-size: 2rem;
        }
        
        .services-grid, .facilities-grid {
            grid-template-columns: 1fr;
        }
        
        .serve-categories {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .cta-buttons {
            flex-direction: column;
            align-items: center;
            gap: 1rem;
        }
        
        .cta-button.primary,
        .cta-button.secondary {
            width: 100%;
            max-width: 300px;
            text-align: center;
        }
        
        .facility-card.highlight {
            grid-column: span 1;
        }
        
        .ai-matching-visual {
            height: 350px;
        }
        
        .student-profile, .tutor-profile {
            display: none;
        }
    }

    @media (max-width: 576px) {
        .hero-content h1 {
            font-size: 1.8rem;
        }
        
        .section-header h2 {
            font-size: 1.8rem;
        }
        
        .serve-categories {
            grid-template-columns: 1fr;
        }
        
        .floating-stats {
            padding: 1.5rem;
        }
        
        .ai-matching-visual {
            height: 300px;
            padding: 1rem;
        }
        
        .ai-center {
            width: 120px;
            height: 120px;
        }
        
        .ai-center i {
            font-size: 2.5rem;
        }
        
        .ai-center h4 {
            font-size: 0.8rem;
        }
        
        .ai-center p {
            font-size: 0.65rem;
        }
        
        .profile-box {
            width: 90px;
            padding: 0.6rem;
        }
        
        .profile-box i {
            font-size: 1.6rem;
            margin-bottom: 0.5rem;
        }
        
        .profile-box h5 {
            font-size: 0.8rem;
        }
        
        .profile-box p {
            font-size: 0.65rem;
        }
        
        .profile-box.student-profile,
        .profile-box.tutor-profile {
            top: 20%;
        }
        
        .line {
            width: 80px;
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
        // Initialize animations for AI matching visualization
        const lines = document.querySelectorAll('.line');
        lines.forEach((line, index) => {
            line.style.animationDelay = (index * 0.5) + 's';
        });
        
        // Animate the dots
        const dots = document.querySelectorAll('.dot');
        dots.forEach((dot, index) => {
            dot.style.animationDelay = (index * 0.3) + 's';
        });
        
        // Add hover effects to cards
        const cards = document.querySelectorAll('.service-card, .category-card, .facility-card');
        cards.forEach(card => {
            card.addEventListener('mouseenter', () => {
                card.style.transform = 'translateY(-8px)';
            });
            
            card.addEventListener('mouseleave', () => {
                card.style.transform = 'translateY(0)';
            });
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
        
        // Add scroll animation for elements
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);
        
        // Observe animated elements
        const animatedElements = document.querySelectorAll('.service-card, .category-card, .facility-card, .process-step');
        animatedElements.forEach(element => {
            observer.observe(element);
        });
        
        // Interactive AI center
        const aiCenter = document.querySelector('.ai-center');
        if (aiCenter) {
            aiCenter.addEventListener('mouseenter', () => {
                aiCenter.style.animation = 'none';
                setTimeout(() => {
                    aiCenter.style.animation = 'float 3s ease-in-out infinite';
                }, 10);
            });
        }
        
        // Animate stats on hero section
        const stats = document.querySelectorAll('.floating-stats .stat');
        if (stats.length > 0) {
            setTimeout(() => {
                stats.forEach((stat, index) => {
                    setTimeout(() => {
                        stat.style.opacity = '1';
                        stat.style.transform = 'translateY(0)';
                    }, index * 200);
                });
            }, 1000);
        }
        
        // Add click effect to buttons
        const buttons = document.querySelectorAll('.cta-button, .btn-plan');
        buttons.forEach(button => {
            button.addEventListener('click', function(e) {
                // Create ripple effect
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
    });
    
    // Handle window resize for responsive adjustments
    window.addEventListener('resize', function() {
        const aiVisual = document.querySelector('.ai-matching-visual');
        if (aiVisual && window.innerWidth < 768) {
            const studentProfile = document.querySelector('.student-profile');
            const tutorProfile = document.querySelector('.tutor-profile');
            if (studentProfile) studentProfile.style.display = 'none';
            if (tutorProfile) tutorProfile.style.display = 'none';
        } else if (aiVisual) {
            const studentProfile = document.querySelector('.student-profile');
            const tutorProfile = document.querySelector('.tutor-profile');
            if (studentProfile) studentProfile.style.display = 'block';
            if (tutorProfile) tutorProfile.style.display = 'block';
        }
    });
    </script>
</body>
</html>