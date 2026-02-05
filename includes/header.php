<?php
// Start session only if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Castle Tutor - Premium Tutoring Services</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
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
            --shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            --radius: 12px;
            --transition: all 0.3s ease;
        }

        body {
            font-family: 'Inter', 'Roboto', sans-serif;
            margin: 0;
        }
        
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Poppins', sans-serif;
        }
        
        /* Header Styles */
        .header {
            background: linear-gradient(135deg, var(--primary-purple), var(--royal-violet));
            box-shadow: var(--shadow);
            position: sticky;
            top: 0;
            z-index: 1000;
            width: 100%;
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.2rem 5%;
            max-width: 1400px;
            margin: 0 auto;
            position: relative;
        }

        .logo {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--white);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
            font-family: 'Poppins', sans-serif;
            z-index: 1001;
        }

        .logo-img {
            height: 110px;
            width: auto;
        }

        .logo span {
            color: var(--warm-orange);
        }

        .nav-links {
            display: flex;
            gap: 2.5rem;
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .nav-links a {
            text-decoration: none;
            color: var(--white);
            font-weight: 500;
            font-size: 1rem;
            transition: var(--transition);
            padding: 0.5rem 0;
            position: relative;
            font-family: 'Poppins', sans-serif;
        }

        .nav-links a:hover {
            color: var(--warm-orange);
        }

        .nav-links a::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 3px;
            background: var(--warm-orange);
            transition: var(--transition);
        }

        .nav-links a:hover::after {
            width: 100%;
        }

        .cta-button {
            background: var(--magenta-pink);
            color: var(--white);
            padding: 0.8rem 2rem;
            border-radius: 30px;
            text-decoration: none;
            font-weight: 500;
            transition: var(--transition);
            border: 2px solid var(--magenta-pink);
            font-family: 'Poppins', sans-serif;
            font-size: 16px;
            display: inline-block;
        }

        .cta-button:hover {
            background: transparent;
            color: var(--magenta-pink);
            transform: translateY(-2px);
        }

        .mobile-menu-btn {
            display: none;
            background: none;
            border: none;
            font-size: 1.5rem;
            color: var(--white);
            cursor: pointer;
            z-index: 1002;
            padding: 0.5rem;
        }
        
        /* Mobile Menu Styles */
        .mobile-menu {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 999;
        }
        
        .mobile-menu.active {
            display: block;
        }
        
        .mobile-menu-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            z-index: 999;
        }
        
        .mobile-menu-content {
            position: fixed;
            top: 0;
            left: -300px;
            width: 280px;
            height: 100%;
            background: linear-gradient(135deg, var(--primary-purple), var(--royal-violet));
            padding: 80px 20px 20px;
            overflow-y: auto;
            transition: var(--transition);
            z-index: 1000;
            box-shadow: 5px 0 20px rgba(0, 0, 0, 0.3);
        }
        
        .mobile-menu.active .mobile-menu-content {
            left: 0;
        }
        
        .mobile-nav-links {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }
        
        .mobile-nav-links li {
            width: 100%;
        }
        
        .mobile-nav-links a {
            display: block;
            color: var(--white);
            text-decoration: none;
            padding: 1rem 1.5rem;
            font-size: 1.1rem;
            border-radius: 8px;
            transition: var(--transition);
            font-family: 'Poppins', sans-serif;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .mobile-nav-links a:hover {
            background: rgba(255, 255, 255, 0.1);
            color: var(--warm-orange);
        }
        
        .mobile-cta-button {
            background: var(--magenta-pink);
            color: var(--white);
            padding: 1rem 1.5rem;
            border-radius: 30px;
            text-decoration: none;
            font-weight: 500;
            transition: var(--transition);
            border: 2px solid var(--magenta-pink);
            font-family: 'Poppins', sans-serif;
            font-size: 16px;
            display: block;
            text-align: center;
            margin-top: 2rem;
            width: 100%;
        }
        
        .mobile-cta-button:hover {
            background: transparent;
            color: var(--magenta-pink);
        }
        
        .close-menu-btn {
            position: absolute;
            top: 20px;
            right: 20px;
            background: none;
            border: none;
            color: var(--white);
            font-size: 1.5rem;
            cursor: pointer;
            z-index: 1001;
        }
        
        .locations-section {
            padding: 4rem 5%;
            background: var(--light-gray);
        }
        
        .locations-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .location-card {
            background: var(--white);
            padding: 2rem;
            border-radius: var(--radius);
            text-align: center;
            font-weight: 600;
            color: var(--dark-gray);
            box-shadow: var(--shadow);
            transition: var(--transition);
            border: 2px solid transparent;
            font-family: 'Poppins', sans-serif;
        }
        
        .location-card:hover {
            border-color: var(--royal-violet);
            transform: translateY(-5px);
            color: var(--royal-violet);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .mobile-menu-btn {
                display: block;
            }
            
            .nav-links {
                display: none;
            }
            
            .navbar .cta-button {
                display: none;
            }
        }
        
        @media (min-width: 769px) {
            .mobile-menu {
                display: none !important;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <nav class="navbar">
           <a href="index.php" class="logo">
             <img src="images/logo.png" alt="Home Castle Tutor Logo" class="logo-img">
             <!-- <span>Home Castle Tutor</span> -->
           </a> 
            <!-- Desktop Navigation -->
            <ul class="nav-links">
                <li><a href="index.php">Home</a></li>
                <li><a href="about.php">About Us</a></li>
                <li><a href="services.php">HTC Services</a></li>
                <li><a href="subscription-plans.php">Subscriptions</a></li>
                <li><a href="blogs.php">Blogs</a></li>
                <li><a href="reviews.php">Reviews</a></li>
                <li><a href="contact.php">Contact Us</a></li>
            </ul>
            
            <!-- Desktop CTA Button -->
            <a href="student-portal.php" class="cta-button">Student Portal</a>
            
            <!-- Mobile Menu Button -->
            <button class="mobile-menu-btn" id="mobileMenuBtn">
                <i class="fas fa-bars"></i>
            </button>
        </nav>
    </header>
    
    <!-- Mobile Menu (Hidden by default) -->
    <div class="mobile-menu" id="mobileMenu">
        <div class="mobile-menu-overlay" id="mobileMenuOverlay"></div>
        <div class="mobile-menu-content">
            <button class="close-menu-btn" id="closeMenuBtn">
                <i class="fas fa-times"></i>
            </button>
            <ul class="mobile-nav-links">
                <li><a href="index.php">Home</a></li>
                <li><a href="services.php">Services</a></li>
                <li><a href="subscription-plans.php">Subscription Plans</a></li>
                <li><a href="blogs.php">Blogs</a></li>
                <li><a href="reviews.php">Reviews</a></li>
                <li><a href="about.php">About Us</a></li>
                <li><a href="contact.php">Contact Us</a></li>
            </ul>
            <a href="student-portal.php" class="mobile-cta-button">Student Portal</a>
        </div>
    </div>

    <script>
        // Mobile menu functionality
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuBtn = document.getElementById('mobileMenuBtn');
            const closeMenuBtn = document.getElementById('closeMenuBtn');
            const mobileMenu = document.getElementById('mobileMenu');
            const mobileMenuOverlay = document.getElementById('mobileMenuOverlay');
            const mobileNavLinks = document.querySelectorAll('.mobile-nav-links a');
            
            // Open mobile menu
            if (mobileMenuBtn) {
                mobileMenuBtn.addEventListener('click', function() {
                    mobileMenu.classList.add('active');
                    document.body.style.overflow = 'hidden'; // Prevent scrolling
                    
                    // Change hamburger icon to X
                    const icon = this.querySelector('i');
                    icon.classList.remove('fa-bars');
                    icon.classList.add('fa-times');
                });
            }
            
            // Close mobile menu
            function closeMobileMenu() {
                mobileMenu.classList.remove('active');
                document.body.style.overflow = ''; // Restore scrolling
                
                // Change X icon back to hamburger
                const icon = mobileMenuBtn.querySelector('i');
                icon.classList.remove('fa-times');
                icon.classList.add('fa-bars');
            }
            
            // Close menu when clicking close button
            if (closeMenuBtn) {
                closeMenuBtn.addEventListener('click', closeMobileMenu);
            }
            
            // Close menu when clicking overlay
            if (mobileMenuOverlay) {
                mobileMenuOverlay.addEventListener('click', closeMobileMenu);
            }
            
            // Close menu when clicking on any mobile nav link
            mobileNavLinks.forEach(link => {
                link.addEventListener('click', closeMobileMenu);
            });
            
            // Close menu with Escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && mobileMenu.classList.contains('active')) {
                    closeMobileMenu();
                }
            });
        });
    </script>