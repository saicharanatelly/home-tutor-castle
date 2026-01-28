<?php
include 'includes/header.php';

// Database configuration - Direct connection to avoid include loops
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'home_castle_tutor';

// Connect to database
$conn = @mysqli_connect($host, $user, $pass, $dbname);

// Initialize banners array
$banners = [];

if ($conn) {
    try {
        // Check if banners table exists
        $tableCheck = mysqli_query($conn, "SHOW TABLES LIKE 'banners'");
        
        if (mysqli_num_rows($tableCheck) > 0) {
            // Table exists, fetch banners
            $result = mysqli_query($conn, "SELECT * FROM banners WHERE status = 'active' ORDER BY display_order ASC LIMIT 5");
            
            if ($result && mysqli_num_rows($result) > 0) {
                while ($banner = mysqli_fetch_assoc($result)) {
                    $banners[] = $banner;
                }
            }
        }
    } catch (Exception $e) {
        // Silently fail and use default banners
        error_log("Banner fetch error: " . $e->getMessage());
    }
}

// Use default banners if database fetch failed or no banners found
if (empty($banners)) {
    $banners = [
        [
            'title' => 'We Care For Your Future',
            'subtitle' => 'Find Experienced Tutor - ONLINE & HOME TUTORS WITHIN 30 MINUTES',
            'image_url' => 'https://images.unsplash.com/photo-1523050854058-8df90110c9f1?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80',
        ],
        [
            'title' => 'Expert Tutors For Every Subject',
            'subtitle' => '3000+ Verified Tutors Across 50+ Subjects',
            'image_url' => 'https://images.unsplash.com/photo-1503676260728-1c00da094a0b?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80',
        ],
        [
            'title' => 'Score Higher, Learn Better',
            'subtitle' => '98% Success Rate with Personalized Learning Plans',
            'image_url' => 'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80',
        ]
    ];
}

// Close connection if opened
if (isset($conn) && $conn) {
    mysqli_close($conn);
}
?>
<link rel="stylesheet" href="style.css">
<!-- Hero Section with Animated Banner Slider -->
<section class="hero">
    <div class="hero-banner-slider">
        <div class="slider-container">
            <?php foreach($banners as $index => $banner): ?>
                <div class="slider-slide">
                    <div class="slide-image" style="background-image: url('<?php echo htmlspecialchars($banner['image_url']); ?>');"></div>
                    <div class="slide-overlay"></div>
                    <div class="slide-content">
                        <h1 class="slide-title animate-title"><?php echo htmlspecialchars($banner['title']); ?></h1>
                        <p class="slide-subtitle animate-subtitle"><?php echo htmlspecialchars($banner['subtitle']); ?></p>
                        <?php if(!empty($banner['button_text'])): ?>
                            <a href="<?php echo htmlspecialchars($banner['button_link']); ?>" class="slide-button animate-button">
                                <?php echo htmlspecialchars($banner['button_text']); ?>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <!-- Slider Controls -->
        <div class="slider-controls">
            <button class="slider-prev"><i class="fas fa-chevron-left"></i></button>
            <div class="slider-dots"></div>
            <button class="slider-next"><i class="fas fa-chevron-right"></i></button>
        </div>
    </div>
    
    <!-- Search Box Overlay -->
<div class="hero-search-overlay">
    <div class="hero-content">
        <div class="search-box animate-search">
            <div class="search-input-group">
                <i class="fas fa-map-marker-alt"></i>
                <input type="text" class="search-input" placeholder="Find Tutors in Nearby Location" id="locationSearch">
            </div>

            <div class="search-input-group">
                <i class="fas fa-book"></i>
                <input type="text" class="search-input" placeholder="Enter Subject or Grade" id="subjectSearch">
            </div>

            <button class="search-button" onclick="searchTutors()">
                <i class="fas fa-search"></i> Search Tutors
            </button>
        </div>
    </div>
</div>

</section>
<section>
     <div class="hero-stats animate-stats">
            <div class="stat-item">
                <span class="stat-number" data-target="3000">0</span>
                <span class="stat-label">Expert Tutors</span>
            </div>
            <div class="stat-item">
                <span class="stat-number" data-target="98">0</span>
                <span class="stat-label">Success Rate</span>
            </div>
            <div class="stat-item">
                <span class="stat-number" data-target="5500">0</span>
                <span class="stat-label">Students</span>
            </div>
            <div class="stat-item">
                <span class="stat-number" data-target="2100">0</span>
                <span class="stat-label">Demo Classes</span>
            </div>
            <div class="stat-item">
                <span class="stat-number" data-target="700">0</span>
                <span class="stat-label">Leads</span>
            </div>
            <div class="stat-item">
                <span class="stat-number" data-target="5000">0</span>
                <span class="stat-label">Admissions</span>
            </div>
        </div>
</section>

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

<!-- Popular Locations -->
<section class="locations-section">
    <div class="section-title">
        <h2>Popular Locations</h2>
        <p>We provide tutors in major cities across the country</p>
    </div>
    
    <div class="locations-grid">
        <div class="location-card">Delhi NCR</div>
        <div class="location-card">Mumbai</div>
        <div class="location-card">Bangalore</div>
        <div class="location-card">Chennai</div>
        <div class="location-card">Hyderabad</div>
        <div class="location-card">Kolkata</div>
        <div class="location-card">Pune</div>
        <div class="location-card">Ahmedabad</div>
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
// Banner Slider Functionality
document.addEventListener('DOMContentLoaded', function() {
    const slides = document.querySelectorAll('.slider-slide');
    const dotsContainer = document.querySelector('.slider-dots');
    const prevBtn = document.querySelector('.slider-prev');
    const nextBtn = document.querySelector('.slider-next');
    
    if (slides.length > 0) {
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
        
        // Initialize first slide
        slides[0].classList.add('active');
        
        function goToSlide(n) {
            // Reset all slides and dots
            slides.forEach(slide => slide.classList.remove('active'));
            dots.forEach(dot => dot.classList.remove('active'));
            
            // Update current slide
            currentSlide = n;
            
            // Activate current slide and dot
            slides[currentSlide].classList.add('active');
            dots[currentSlide].classList.add('active');
            
            // Reset timer
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
    
    // Animated Stats Counter - Fixed Version
    const stats = document.querySelector(".hero-stats");
    const numbers = document.querySelectorAll(".stat-number");

    if (stats && numbers.length > 0) {
        const observer = new IntersectionObserver(entries => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    stats.classList.add("visible");

                    numbers.forEach(num => {
                        const target = +num.dataset.target;
                        const duration = 1500; // 1.5 seconds
                        const steps = 60;
                        const increment = target / steps;
                        let current = 0;
                        let step = 0;

                        const timer = setInterval(() => {
                            current += increment;
                            step++;
                            
                            if (step >= steps) {
                                clearInterval(timer);
                                num.textContent = target === 98 ? "98%" : target + "+";
                            } else {
                                num.textContent = Math.floor(current) + (target === 98 ? "" : "+");
                            }
                        }, duration / steps);
                    });

                    observer.disconnect();
                }
            });
        }, { threshold: 0.3 });

        observer.observe(stats);
    }
    
    // Search function
    window.searchTutors = function() {
        const location = document.getElementById('locationSearch').value;
        const subject = document.getElementById('subjectSearch').value;
        
        if (!location && !subject) {
            alert('Please enter location or subject to search');
            return;
        }
        
        // Simulate search (replace with actual redirect)
        const searchUrl = 'search.php?' + 
            (location ? 'location=' + encodeURIComponent(location) + '&' : '') +
            (subject ? 'subject=' + encodeURIComponent(subject) : '');
        
        window.location.href = searchUrl;
    };
    
    // Add scroll animations
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const scrollObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-in');
            }
        });
    }, observerOptions);
    
    // Observe all sections for animation
    document.querySelectorAll('section').forEach(section => {
        scrollObserver.observe(section);
    });
});
</script>

<?php
include 'includes/footer.php';
?>