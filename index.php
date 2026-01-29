<?php
include 'includes/header.php';

// Database configuration - Direct connection
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'home_castle_tutor';

// Connect to database
$conn = @mysqli_connect($host, $user, $pass, $dbname);

// Initialize banners array
$banners = [];
$stats = [
    ['number' => '3000+', 'label' => 'Expert Tutors'],
    ['number' => '98%', 'label' => 'Success Rate'],
    ['number' => '5500+', 'label' => 'Students'],
    ['number' => '2100+', 'label' => 'Demo Classes'],
    ['number' => '700+', 'label' => 'Active Leads'],
    ['number' => '5000+', 'label' => 'Admissions']
];

// Popular locations
$popularLocations = ['Delhi NCR', 'Mumbai', 'Bangalore', 'Chennai', 'Hyderabad', 'Kolkata', 'Pune', 'Ahmedabad'];

if ($conn) {
    try {
        // Check if banners table exists
        $tableCheck = mysqli_query($conn, "SHOW TABLES LIKE 'banners'");
        
        if (mysqli_num_rows($tableCheck) > 0) {
            // Fetch active banners for home page position, ordered by created date
            $result = mysqli_query($conn, "SELECT * FROM banners WHERE position = 'home' AND is_active = 1 ORDER BY created_at DESC");
            
            if ($result && mysqli_num_rows($result) > 0) {
                while ($banner = mysqli_fetch_assoc($result)) {
                    $banners[] = [
                        'title' => $banner['title'],
                        'subtitle' => $banner['subtitle'],
                        'image_url' => '../' . $banner['image_path'],
                        'button_text' => $banner['button_text'],
                        'button_link' => $banner['button_link']
                    ];
                }
            }
        }
        
        // If no banners found from database, use default
        if (empty($banners)) {
            throw new Exception("No banners found");
        }
        
    } catch (Exception $e) {
        // Use default banners if database fetch failed
        $banners = [
            [
                'title' => 'We Care For Your Future',
                'subtitle' => 'Find Experienced Tutor - ONLINE & HOME TUTORS WITHIN 30 MINUTES',
                'image_url' => 'https://images.unsplash.com/photo-1523050854058-8df90110c9f1?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80',
                'button_text' => 'Find Tutors Now',
                'button_link' => 'student-portal.php'
            ]
        ];
    }
    
    // Close connection
    mysqli_close($conn);
} else {
    // Use default banners if connection failed
    $banners = [
        [
            'title' => 'We Care For Your Future',
            'subtitle' => 'Find Experienced Tutor - ONLINE & HOME TUTORS WITHIN 30 MINUTES',
            'image_url' => 'https://images.unsplash.com/photo-1523050854058-8df90110c9f1?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80',
            'button_text' => 'Find Tutors Now',
            'button_link' => 'student-portal.php'
        ]
    ];
}
?>

<!-- Hero Section with Banner Slider -->
<section class="hero-banner-slider">
    <div class="slider-container">
        <?php if(count($banners) > 1): ?>
            <!-- Multiple banners - slider -->
            <div class="slider-wrapper">
                <?php foreach($banners as $index => $banner): ?>
                    <div class="slider-slide <?php echo $index === 0 ? 'active' : ''; ?>">
                        <div class="banner-background">
                            <div class="banner-image" style="background-image: url('<?php echo htmlspecialchars($banner['image_url']); ?>');"></div>
                            <div class="banner-overlay"></div>
                        </div>
                        <div class="hero-container">
                            <div class="hero-content">
                                <h1 class="hero-title">We Care For Your Future</h1>
                                <div class="hero-subtitle-container">
                                    <div class="hero-subtitle-line">Find Experienced Tutors</div>
                                    <div class="hero-subtitle-line">Online & Home Tutors within <span class="highlight-text">30 MINUTES</span></div>
                                </div>
                                
                                <!-- Location Search Form -->
                                <div class="location-search-container">
                                    <h3 class="search-title">Find Tutors in Nearby Location</h3>
                                    
                                    <div class="search-form">
                                        <div class="search-input-group">
                                            <i class="fas fa-map-marker-alt search-icon"></i>
                                            <input type="text" 
                                                   class="search-input" 
                                                   id="locationSearch" 
                                                   placeholder="Your Location Nearby"
                                                   onfocus="this.placeholder=''" 
                                                   onblur="this.placeholder='Your Location Nearby'">
                                        </div>
                                        <button class="search-button" onclick="searchTutors()">
                                            <i class="fas fa-search"></i> Search Now
                                        </button>
                                    </div>
                                    
                                    <!-- Popular Locations -->
                                    <div class="popular-locations">
                                        <span class="popular-label">Popular Location:</span>
                                        <div class="location-tags">
                                            <?php 
                                            $displayLocations = array_slice($popularLocations, 0, 4);
                                            foreach($displayLocations as $location): 
                                            ?>
                                                <span class="location-tag"><?php echo $location; ?></span>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <!-- Slider Controls -->
            <button class="slider-prev">
                <i class="fas fa-chevron-left"></i>
            </button>
            <button class="slider-next">
                <i class="fas fa-chevron-right"></i>
            </button>
            
            <!-- Slider Dots -->
            <div class="slider-dots"></div>
            
        <?php else: ?>
            <!-- Single banner -->
            <div class="banner-background">
                <div class="banner-image" style="background-image: url('<?php echo htmlspecialchars($banners[0]['image_url']); ?>');"></div>
                <div class="banner-overlay"></div>
            </div>
            <div class="hero-container">
                <div class="hero-content">
                    <h1 class="hero-title">We Care For Your Future</h1>
                    <div class="hero-subtitle-container">
                        <div class="hero-subtitle-line">Find Experienced Tutors</div>
                        <div class="hero-subtitle-line">Online & Home Tutors within <span class="highlight-text">30 MINUTES</span></div>
                    </div>
                    
                    <!-- Location Search Form -->
                    <div class="location-search-container">
                        <h3 class="search-title">Find Tutors in Nearby Location</h3>
                        
                        <div class="search-form">
                            <div class="search-input-group">
                                <i class="fas fa-map-marker-alt search-icon"></i>
                                <input type="text" 
                                       class="search-input" 
                                       id="locationSearch" 
                                       placeholder="Your Location Nearby"
                                       onfocus="this.placeholder=''" 
                                       onblur="this.placeholder='Your Location Nearby'">
                            </div>
                            <button class="search-button" onclick="searchTutors()">
                                <i class="fas fa-search"></i> Search Now
                            </button>
                        </div>
                        
                        <!-- Popular Locations -->
                        <div class="popular-locations">
                            <span class="popular-label">Popular Location:</span>
                            <div class="location-tags">
                                <?php 
                                $displayLocations = array_slice($popularLocations, 0, 4);
                                foreach($displayLocations as $location): 
                                ?>
                                    <span class="location-tag"><?php echo $location; ?></span>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Animated Statistics Section -->
<section class="animated-stats-section">
    <div class="container">
        <div class="stats-grid">
            <?php foreach($stats as $index => $stat): ?>
                <div class="stat-item" data-aos="fade-up" data-aos-delay="<?php echo $index * 100; ?>">
                    <div class="stat-circle">
                        <span class="stat-number" data-target="<?php echo str_replace('+', '', $stat['number']); ?>">
                            0<?php echo strpos($stat['number'], '%') !== false ? '%' : '+'; ?>
                        </span>
                    </div>
                    <h3 class="stat-label"><?php echo $stat['label']; ?></h3>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<style>
/* Hero Banner Slider Styles */
.hero-banner-slider {
    position: relative;
    height: 85vh;
    min-height: 700px;
    overflow: hidden;
}

.slider-container {
    position: relative;
    height: 100%;
    width: 100%;
}

.slider-wrapper {
    position: relative;
    height: 100%;
}

.slider-slide {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    opacity: 0;
    transition: opacity 1s ease-in-out;
}

.slider-slide.active {
    opacity: 1;
}

.banner-background {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
}

.banner-image {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
}

.banner-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, rgba(59, 10, 106, 0.85) 0%, rgba(94, 43, 151, 0.8) 50%, rgba(193, 60, 145, 0.7) 100%);
}

.hero-container {
    position: relative;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 2;
}

.hero-content {
    text-align: center;
    max-width: 800px;
    padding: 0 20px;
    color: white;
}

.hero-title {
    font-size: 3.5rem;
    font-weight: 700;
    margin-bottom: 20px;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
    animation: fadeInUp 1s ease;
    line-height: 1.2;
}

.hero-subtitle-container {
    margin-bottom: 40px;
    animation: fadeInUp 1s ease 0.3s both;
}

.hero-subtitle-line {
    font-size: 1.8rem;
    margin-bottom: 10px;
    opacity: 0.95;
    font-weight: 500;
    letter-spacing: 0.5px;
}

.highlight-text {
    color: #F6A04D;
    font-weight: 700;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}

/* Location Search Container */
.location-search-container {
    background: rgba(255, 255, 255, 0.95);
    border-radius: 20px;
    padding: 30px;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
    max-width: 700px;
    margin: 0 auto;
    animation: fadeInUp 1s ease 0.6s both;
}

.search-title {
    color: #3B0A6A;
    font-size: 1.8rem;
    margin-bottom: 25px;
    font-weight: 600;
}

.search-form {
    display: flex;
    gap: 15px;
    margin-bottom: 25px;
}

.search-input-group {
    flex: 1;
    position: relative;
}

.search-icon {
    position: absolute;
    left: 20px;
    top: 50%;
    transform: translateY(-50%);
    color: #5E2B97;
    font-size: 1.2rem;
}

.search-input {
    width: 100%;
    padding: 18px 20px 18px 55px;
    border: 2px solid #e0e0e0;
    border-radius: 12px;
    font-size: 1.1rem;
    font-family: 'Inter', sans-serif;
    transition: all 0.3s ease;
    background: white;
}

.search-input:focus {
    outline: none;
    border-color: #5E2B97;
    box-shadow: 0 0 0 3px rgba(94, 43, 151, 0.1);
}

.search-button {
    background: linear-gradient(135deg, #F6A04D, #FF8A00);
    color: white;
    border: none;
    padding: 18px 35px;
    border-radius: 12px;
    font-size: 1.1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 10px;
    white-space: nowrap;
    box-shadow: 0 10px 25px rgba(246, 160, 77, 0.3);
}

.search-button:hover {
    transform: translateY(-2px);
    box-shadow: 0 15px 35px rgba(246, 160, 77, 0.4);
    background: linear-gradient(135deg, #FF8A00, #F6A04D);
}

/* Popular Locations */
.popular-locations {
    text-align: left;
}

.popular-label {
    display: block;
    color: #333;
    font-size: 1rem;
    font-weight: 600;
    margin-bottom: 15px;
    opacity: 0.9;
}

.location-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
}

.location-tag {
    background: rgba(94, 43, 151, 0.1);
    color: #5E2B97;
    padding: 10px 20px;
    border-radius: 8px;
    font-size: 0.95rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    border: 1px solid rgba(94, 43, 151, 0.2);
}

.location-tag:hover {
    background: rgba(94, 43, 151, 0.2);
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(94, 43, 151, 0.1);
}

/* Slider Controls */
.slider-prev,
.slider-next {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    background: rgba(255, 255, 255, 0.2);
    border: none;
    color: white;
    width: 50px;
    height: 50px;
    border-radius: 50%;
    font-size: 1.2rem;
    cursor: pointer;
    z-index: 10;
    transition: all 0.3s ease;
    backdrop-filter: blur(10px);
}

.slider-prev:hover,
.slider-next:hover {
    background: rgba(255, 255, 255, 0.3);
    transform: translateY(-50%) scale(1.1);
}

.slider-prev {
    left: 30px;
}

.slider-next {
    right: 30px;
}

.slider-dots {
    position: absolute;
    bottom: 30px;
    left: 0;
    width: 100%;
    display: flex;
    justify-content: center;
    gap: 10px;
    z-index: 10;
}

.slider-dot {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.5);
    cursor: pointer;
    transition: all 0.3s ease;
}

.slider-dot.active {
    background: white;
    transform: scale(1.2);
}

/* Animated Stats Section */
.animated-stats-section {
    padding: 80px 0;
    background: linear-gradient(135deg, #3B0A6A 0%, #5E2B97 100%);
    position: relative;
    overflow: hidden;
}

.animated-stats-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="%23ffffff" fill-opacity="0.05" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,112C672,96,768,96,864,112C960,128,1056,160,1152,160C1248,160,1344,128,1392,112L1440,96L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>');
    background-size: cover;
    background-position: center;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 30px;
    position: relative;
    z-index: 1;
}

.stat-item {
    text-align: center;
    color: white;
}

.stat-circle {
    width: 140px;
    height: 140px;
    margin: 0 auto 20px;
    border-radius: 50%;
    border: 3px solid rgba(255, 255, 255, 0.2);
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    overflow: hidden;
}

.stat-circle::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 50%;
    transform: scale(0);
    transition: transform 0.6s ease;
}

.stat-item:hover .stat-circle::before {
    transform: scale(1);
}

.stat-number {
    font-size: 2.8rem;
    font-weight: 700;
    font-family: 'Poppins', sans-serif;
    color: #F6A04D;
    position: relative;
    z-index: 1;
}

.stat-label {
    font-size: 1.1rem;
    font-weight: 500;
    opacity: 0.9;
    letter-spacing: 0.5px;
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

@keyframes countUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Responsive Design */
@media (max-width: 1200px) {
    .hero-title {
        font-size: 3rem;
    }
    .hero-subtitle-line {
        font-size: 1.6rem;
    }
    .search-title {
        font-size: 1.6rem;
    }
}

@media (max-width: 768px) {
    .hero-banner-slider {
        height: 80vh;
        min-height: 650px;
    }
    
    .hero-title {
        font-size: 2.5rem;
    }
    
    .hero-subtitle-line {
        font-size: 1.3rem;
    }
    
    .search-form {
        flex-direction: column;
    }
    
    .search-input,
    .search-button {
        width: 100%;
        text-align: center;
    }
    
    .search-button {
        justify-content: center;
    }
    
    .location-tags {
        justify-content: center;
    }
    
    .slider-prev,
    .slider-next {
        width: 40px;
        height: 40px;
        font-size: 1rem;
    }
    
    .slider-prev {
        left: 15px;
    }
    
    .slider-next {
        right: 15px;
    }
    
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .stat-circle {
        width: 120px;
        height: 120px;
    }
    
    .stat-number {
        font-size: 2.2rem;
    }
    
    .location-search-container {
        padding: 25px 20px;
        margin: 0 15px;
    }
}

@media (max-width: 480px) {
    .hero-banner-slider {
        height: 75vh;
        min-height: 600px;
    }
    
    .hero-title {
        font-size: 2rem;
    }
    
    .hero-subtitle-line {
        font-size: 1.1rem;
    }
    
    .search-title {
        font-size: 1.3rem;
    }
    
    .search-input {
        padding: 15px 15px 15px 45px;
        font-size: 1rem;
    }
    
    .search-button {
        padding: 15px 25px;
        font-size: 1rem;
    }
    
    .location-tag {
        padding: 8px 15px;
        font-size: 0.9rem;
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
        gap: 40px;
    }
}
</style>

<!-- How We Work Section -->
<section class="how-we-work" id="how-it-works">
    <div class="section-title">
        <h2>How We Function to Deliver the Best</h2>
        <p>For students and parents</p>
    </div>
    
    <div class="process-steps">
        <div class="step-card">
            <div class="step-icon">1</div>
            <h3>Define Your Needs</h3>
            <p>Post your child's specific requirements from KG to College and unlock our network of certified educators</p>
        </div>
        
        <div class="step-card">
            <div class="step-icon">2</div>
            <h3>Get Matched & Demo</h3>
            <p>Receive instant profiles of verified, expert tutors. Schedule a free demo to find the perfect fit</p>
        </div>
        
        <div class="step-card">
            <div class="step-icon">3</div>
            <h3>Personalize & Learn</h3>
            <p>Take a free 1-1 demo, at home or online with tutors, with custom-crafted study materials</p>
        </div>
        
        <div class="step-card">
            <div class="step-icon">4</div>
            <h3>Track & Succeed</h3>
            <p>Monitor progress with detailed feedback, skill reports, and achieve academic excellence</p>
        </div>
    </div>

    <!-- Choose Tutor By Choice Section -->
    <section class="tutor-choice-section">
        <div class="container">
            <div class="section-header text-center">
                <h2 class="section-title">Choose Your Tutor By Choice</h2>
                <p class="section-subtitle">Tutor flexibility and choices at Home Tutor Castle</p>
            </div>

            <div class="choice-grid">
                <!-- Academic Level -->
                <div class="choice-card">
                    <h3 class="choice-title">Tutors by Academic Level</h3>
                    <ul class="choice-list">
                        <li>Nursery & KG</li>
                        <li>Primary (Classes 1–5)</li>
                        <li>Upper Primary (Classes 6–8)</li>
                        <li>Secondary (Classes 9–10)</li>
                        <li>Senior Secondary (Classes 11–12)</li>
                        <li>Graduate Level</li>
                    </ul>
                </div>

                <!-- Subject Stream -->
                <div class="choice-card">
                    <h3 class="choice-title">Tutors by Subject Stream</h3>
                    <ul class="choice-list">
                        <li>Science</li>
                        <li>Commerce</li>
                        <li>Humanities / Arts</li>
                        <li>Engineering Subjects</li>
                        <li>Medical Studies</li>
                        <li>Competitive Exam Subjects</li>
                    </ul>
                </div>

                <!-- Competitive Exams -->
                <div class="choice-card">
                    <h3 class="choice-title">Tutors for Competitive Exams</h3>
                    <ul class="choice-list">
                        <li>JEE (Main & Advanced)</li>
                        <li>NEET (MBBS / BDS)</li>
                        <li>UPSC CSE Foundation</li>
                        <li>MHT-CET</li>
                        <li>CA-CPT / Foundation</li>
                        <li>Management Aptitude (BBA / MBA)</li>
                    </ul>
                </div>

                <!-- Educational Board -->
                <div class="choice-card">
                    <h3 class="choice-title">Tutors by Educational Board</h3>
                    <ul class="choice-list">
                        <li>CBSE</li>
                        <li>ICSE / ISC</li>
                        <li>IGCSE</li>
                        <li>A-Levels</li>
                        <li>State Boards</li>
                        <li>International Boards</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
    
    <div class="hire-tutor-section">
        <h3>Find Your Perfect Tutor Now</h3>
        <p>Connect with the best tutors who match your requirements and learning style</p>
        <a href="student-portal.php" class="btn-large">Hire a Tutor</a>
    </div>
</section>

<!-- How It Works For Tutors -->
<section class="how-it-works-tutors">
    <div class="container">
        <div class="section-header text-center">
            <span class="section-tag">FOR TUTORS</span>
            <h2 class="section-title">How It Works for Tutors</h2>
            <p class="section-subtitle">
                Join Home Tutor Castle and unlock verified teaching opportunities nationwide
            </p>
        </div>

        <div class="tutor-steps-grid">
            <div class="tutor-step-card">
                <div class="step-number">1</div>
                <h3>Get Verified Opportunities</h3>
                <p>
                    Create your free profile and access a continuous flow of 
                    <strong>100% verified leads</strong> for both online and home tuition jobs near you.
                </p>
            </div>

            <div class="tutor-step-card">
                <div class="step-number">2</div>
                <h3>Earn Elite Certification</h3>
                <p>
                    Complete our soft-skills and pedagogical training to become an
                    <strong>HTC Certified Educator</strong> and boost your credibility.
                </p>
            </div>

            <div class="tutor-step-card">
                <div class="step-number">3</div>
                <h3>Teach Anytime, Anywhere</h3>
                <p>
                    Enjoy flexible scheduling and the comfort of teaching from home,
                    offline at student locations, or through online classes.
                </p>
            </div>

            <div class="tutor-step-card">
                <div class="step-number">4</div>
                <h3>Track, Grow & Earn More</h3>
                <p>
                    Monitor classes with detailed feedback, soft-skill reports,
                    performance insights, and maximize your earnings.
                </p>
            </div>
        </div>

        <div class="tutor-cta-box">
            <h3>Ready to Start Teaching with Home Tutor Castle?</h3>
            <p>
                Register as a tutor, unlock premium opportunities, and start earning with verified students.
            </p>
            <a href="auth/signup.php?type=tutor" class="btn tutor-register-btn">
                Register as a Tutor
            </a>
        </div>
    </div>
</section>

<!-- How It Works For Institutions -->
<section class="how-it-works-institutions">
    <div class="container">
        <div class="section-header text-center">
            <span class="section-tag">FOR SCHOOLS & COLLEGES</span>
            <h2 class="section-title">How It Works for Institutions</h2>
            <p class="section-subtitle">
                Partner with Home Tutor Castle to access verified faculty, boost branding, and scale effortlessly
            </p>
        </div>

        <div class="institution-steps-grid">
            <div class="institution-card">
                <div class="step-icon">1</div>
                <h3>Commission-Free Faculty Hiring</h3>
                <p>
                    Get admission support and hire <strong>verified tutors</strong> from our national network
                    with zero commission and optimized budgets.
                </p>
            </div>

            <div class="institution-card">
                <div class="step-icon">2</div>
                <h3>Enhanced Branding & Outreach</h3>
                <p>
                    Earn elite certification and promote your institution through our
                    soft-skills programs and nationwide digital outreach channels.
                </p>
            </div>

            <div class="institution-card">
                <div class="step-icon">3</div>
                <h3>Streamlined Recruitment</h3>
                <p>
                    Post faculty requirements directly on your dashboard and receive
                    qualified candidates within days — faster, simpler, smarter.
                </p>
            </div>

            <div class="institution-card">
                <div class="step-icon">4</div>
                <h3>Student Referrals & Performance Tracking</h3>
                <p>
                    Track classes using detailed feedback, soft-skill reports,
                    and performance insights to drive measurable academic success.
                </p>
            </div>
        </div>

        <!-- CTA -->
        <div class="institution-cta">
            <h3>Partner with Home Tutor Castle</h3>
            <p>
                Join our B2B ecosystem and unlock faculty excellence, branding growth,
                and student success under one platform.
            </p>
            <a href="b2b-enrollment.php" class="btn institution-cta-btn">
                Join Us
            </a>
        </div>
    </div>
</section>

<!-- Popular Locations (Full Section) -->
<section class="locations-section">
    <div class="section-title">
        <h2>Popular Locations</h2>
        <p>We provide tutors in major cities across the country</p>
    </div>
    
    <div class="locations-grid">
        <?php foreach($popularLocations as $location): ?>
            <div class="location-card"><?php echo $location; ?></div>
        <?php endforeach; ?>
    </div>
</section>

<!-- Testimonials Section -->
<section class="testimonials-section">
    <div class="container">
        <div class="section-title">
            <h2>What Our Students & Parents Say</h2>
            <p>Success stories from our satisfied community</p>
        </div>
        
        <div class="testimonials-grid">
            <div class="testimonial-card">
                <div class="testimonial-header">
                    <div class="student-avatar">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                    <div class="student-info">
                        <h4>Rohan Sharma</h4>
                        <p>Class 10, CBSE</p>
                    </div>
                    <div class="rating">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                </div>
                <p class="testimonial-text">"My math tutor from Home Castle Tutor helped me improve from 65% to 92% in just 3 months. The personalized attention made all the difference!"</p>
            </div>
            
            <div class="testimonial-card">
                <div class="testimonial-header">
                    <div class="student-avatar">
                        <i class="fas fa-user-friends"></i>
                    </div>
                    <div class="student-info">
                        <h4>Mrs. Gupta</h4>
                        <p>Parent of Class 8 Student</p>
                    </div>
                    <div class="rating">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                    </div>
                </div>
                <p class="testimonial-text">"Finding a qualified tutor for my daughter was so easy through this platform. The tutor is professional and my daughter's grades have improved significantly."</p>
            </div>
            
            <div class="testimonial-card">
                <div class="testimonial-header">
                    <div class="student-avatar">
                        <i class="fas fa-user-tie"></i>
                    </div>
                    <div class="student-info">
                        <h4>David Chen</h4>
                        <p>JEE Aspirant</p>
                    </div>
                    <div class="rating">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                </div>
                <p class="testimonial-text">"The JEE coaching I received through Home Castle Tutor was exceptional. My tutor's guidance helped me secure a rank under 5000 in JEE Mains."</p>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action Section -->
<section class="final-cta-section">
    <div class="container">
        <div class="cta-content">
            <h2>Ready to Start Your Learning Journey?</h2>
            <p>Join thousands of successful students who have achieved academic excellence with our expert tutors.</p>
            <div class="cta-buttons">
                <a href="student-portal.php" class="cta-button primary">
                    <i class="fas fa-user-graduate"></i> Find a Tutor
                </a>
                <a href="auth/signup.php?type=tutor" class="cta-button secondary">
                    <i class="fas fa-chalkboard-teacher"></i> Become a Tutor
                </a>
                <a href="contact.php" class="cta-button outline">
                    <i class="fas fa-headset"></i> Get Free Consultation
                </a>
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Banner Slider Functionality
    const slides = document.querySelectorAll('.slider-slide');
    const dotsContainer = document.querySelector('.slider-dots');
    const prevBtn = document.querySelector('.slider-prev');
    const nextBtn = document.querySelector('.slider-next');
    
    if (slides.length > 1) {
        // Create dots
        slides.forEach((_, index) => {
            const dot = document.createElement('div');
            dot.className = 'slider-dot';
            if (index === 0) dot.classList.add('active');
            dot.addEventListener('click', () => goToSlide(index));
            dotsContainer.appendChild(dot);
        });
        
        const dots = document.querySelectorAll('.slider-dot');
        let currentSlide = 0;
        let slideInterval;
        
        function goToSlide(n) {
            slides.forEach(slide => slide.classList.remove('active'));
            dots.forEach(dot => dot.classList.remove('active'));
            
            currentSlide = n;
            slides[currentSlide].classList.add('active');
            dots[currentSlide].classList.add('active');
            
            resetTimer();
        }
        
        function nextSlide() {
            let next = currentSlide + 1;
            if (next >= slides.length) next = 0;
            goToSlide(next);
        }
        
        function prevSlide() {
            let prev = currentSlide - 1;
            if (prev < 0) prev = slides.length - 1;
            goToSlide(prev);
        }
        
        function startTimer() {
            slideInterval = setInterval(nextSlide, 5000);
        }
        
        function resetTimer() {
            clearInterval(slideInterval);
            startTimer();
        }
        
        // Event listeners
        prevBtn.addEventListener('click', () => {
            prevSlide();
            resetTimer();
        });
        
        nextBtn.addEventListener('click', () => {
            nextSlide();
            resetTimer();
        });
        
        // Pause on hover
        const slider = document.querySelector('.hero-banner-slider');
        slider.addEventListener('mouseenter', () => clearInterval(slideInterval));
        slider.addEventListener('mouseleave', startTimer);
        
        // Start the slideshow
        startTimer();
    }
    
    // Animated Statistics Counter
    const statsSection = document.querySelector('.animated-stats-section');
    const statNumbers = document.querySelectorAll('.stat-number');
    
    if (statsSection && statNumbers.length > 0) {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    // Animate each statistic
                    statNumbers.forEach((stat, index) => {
                        const target = parseInt(stat.getAttribute('data-target'));
                        const isPercentage = stat.parentElement.textContent.includes('%');
                        
                        let current = 0;
                        const increment = target / 60; // 60 frames over 1.5 seconds
                        const duration = 1500;
                        const stepTime = duration / 60;
                        
                        const timer = setInterval(() => {
                            current += increment;
                            if (current >= target) {
                                current = target;
                                clearInterval(timer);
                            }
                            
                            if (isPercentage) {
                                stat.textContent = Math.round(current) + '%';
                            } else {
                                stat.textContent = Math.round(current) + '+';
                            }
                        }, stepTime);
                        
                        // Add animation class
                        stat.style.animation = 'countUp 0.5s ease';
                    });
                    
                    observer.disconnect();
                }
            });
        }, { threshold: 0.3 });
        
        observer.observe(statsSection);
    }
    
    // Search function
    window.searchTutors = function() {
        const location = document.getElementById('locationSearch').value;
        
        if (!location) {
            alert('Please enter your location to search for tutors');
            document.getElementById('locationSearch').focus();
            return;
        }
        
        // Redirect to search page with location parameter
        window.location.href = 'search.php?location=' + encodeURIComponent(location);
    };
    
    // Location tag click handler
    document.querySelectorAll('.location-tag').forEach(tag => {
        tag.addEventListener('click', function() {
            const location = this.textContent;
            document.getElementById('locationSearch').value = location;
            searchTutors();
        });
    });
    
    // Enter key support for search input
    document.getElementById('locationSearch').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            searchTutors();
        }
    });
    
    // Add scroll animations for other sections
    const scrollObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    });
    
    // Observe all sections
    document.querySelectorAll('section').forEach(section => {
        if (!section.classList.contains('hero-banner-slider') && 
            !section.classList.contains('animated-stats-section')) {
            section.style.opacity = '0';
            section.style.transform = 'translateY(20px)';
            section.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
            scrollObserver.observe(section);
        }
    });
    
    // Add hover effect to stat items
    document.querySelectorAll('.stat-item').forEach(item => {
        item.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-10px)';
            this.style.transition = 'transform 0.3s ease';
        });
        
        item.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
    
    // Auto-focus search input for better UX
    setTimeout(() => {
        document.getElementById('locationSearch').focus();
    }, 1000);
});
</script>

<?php
include 'includes/footer.php';
?>