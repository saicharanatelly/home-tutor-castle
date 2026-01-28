<!-- Footer -->
<footer class="footer">
    <div class="footer-content">
        <div class="footer-column">
            <h4>Home Castle Tutor</h4>
            <p>Premium tutoring services connecting students with expert educators since 2021.</p>
            <div class="social-links">
                <a href="#"><i class="fab fa-facebook-f"></i></a>
                <a href="#"><i class="fab fa-twitter"></i></a>
                <a href="#"><i class="fab fa-instagram"></i></a>
                <a href="#"><i class="fab fa-linkedin-in"></i></a>
                <a href="#"><i class="fab fa-youtube"></i></a>
            </div>
        </div>
        
        <div class="footer-column">
            <h4>Quick Links</h4>
            <ul>
                <li><a href="index.php"><i class="fas fa-chevron-right"></i> Home</a></li>
                <li><a href="services.php"><i class="fas fa-chevron-right"></i> Services</a></li>
                <li><a href="subscription-plans.php"><i class="fas fa-chevron-right"></i> Plans</a></li>
                <li><a href="blogs.php"><i class="fas fa-chevron-right"></i> Blogs</a></li>
                <li><a href="about.php"><i class="fas fa-chevron-right"></i> About Us</a></li>
            </ul>
        </div>
        
        <div class="footer-column">
            <h4>Services</h4>
            <ul>
                <li><a href="#"><i class="fas fa-chevron-right"></i> Home Tuition</a></li>
                <li><a href="#"><i class="fas fa-chevron-right"></i> Online Classes</a></li>
                <li><a href="#"><i class="fas fa-chevron-right"></i> Test Preparation</a></li>
                <li><a href="#"><i class="fas fa-chevron-right"></i> Skill Development</a></li>
                <li><a href="#"><i class="fas fa-chevron-right"></i> Career Counseling</a></li>
            </ul>
        </div>
        
        <!-- Newsletter Column -->
        <div class="footer-column newsletter-column">
            <h4>Stay Updated</h4>
            <p>Subscribe to our newsletter for the latest updates and educational tips.</p>
            
            <div class="newsletter-form">
                <form id="newsletter-form" method="POST" action="subscribe.php">
                    <div class="input-group">
                        <input type="email" name="email" placeholder="Enter your email" required>
                        <button type="submit" class="newsletter-btn">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </div>
                    <div class="form-message" id="newsletter-message"></div>
                </form>
                <p class="newsletter-note">We respect your privacy. Unsubscribe at any time.</p>
            </div>
            
            <div class="app-download">
                <h5>Download Our App</h5>
                <div class="app-buttons">
                    <a href="#" class="app-btn">
                        <i class="fab fa-google-play"></i>
                        <div>
                            <span>GET IT ON</span>
                            <strong>Google Play</strong>
                        </div>
                    </a>
                    <a href="#" class="app-btn">
                        <i class="fab fa-apple"></i>
                        <div>
                            <span>Download on the</span>
                            <strong>App Store</strong>
                        </div>
                    </a>
                </div>
            </div>
        </div>
        
        <div class="footer-column">
            <h4>Contact Info</h4>
            <ul class="contact-info">
                <li>
                    <i class="fas fa-map-marker-alt"></i>
                    <span>123 Education Street, Knowledge City, Delhi - 110001</span>
                </li>
                <li>
                    <i class="fas fa-phone"></i>
                    <span>+91 9876543210</span>
                </li>
                <li>
                    <i class="fas fa-envelope"></i>
                    <span>info@homecastletutor.com</span>
                </li>
                <li>
                    <i class="fas fa-clock"></i>
                    <span>Mon-Sat: 9:00 AM - 7:00 PM</span>
                </li>
            </ul>
        </div>
    </div>
    
    <!-- Footer Bottom -->
    <div class="footer-bottom">
        <div class="container">
            <div class="footer-bottom-content">
                <div class="payment-methods">
                    <h5>Secure Payment Methods:</h5>
                    <div class="payment-icons">
                        <i class="fab fa-cc-visa" title="Visa"></i>
                        <i class="fab fa-cc-mastercard" title="Mastercard"></i>
                        <i class="fab fa-cc-paypal" title="PayPal"></i>
                        <i class="fab fa-google-pay" title="Google Pay"></i>
                        <i class="fab fa-cc-apple-pay" title="Apple Pay"></i>
                        <i class="fas fa-university" title="Bank Transfer"></i>
                    </div>
                </div>
                
                <div class="certifications">
                    <div class="cert-badge">
                        <i class="fas fa-shield-alt"></i>
                        <span>SSL Secured</span>
                    </div>
                    <div class="cert-badge">
                        <i class="fas fa-lock"></i>
                        <span>Privacy Protected</span>
                    </div>
                    <div class="cert-badge">
                        <i class="fas fa-award"></i>
                        <span>Trusted Service</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="copyright">
        <p>&copy; 2024 Home Castle Tutor. All rights reserved. | 
            <a href="privacy.php">Privacy Policy</a> | 
            <a href="terms.php">Terms of Service</a> | 
            <a href="refund.php">Refund Policy</a>
        </p>
        <p class="developed-by">Developed with <i class="fas fa-heart"></i> by Home Castle Tutor Team</p>
    </div>
</footer>

<style>
/* Footer Styles with New Color Scheme */
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
    --transition: all 0.3s ease;
}

.footer {
    background: linear-gradient(135deg, var(--primary-purple), var(--royal-violet));
    color: var(--white);
    padding: 4rem 5% 2rem;
    position: relative;
    overflow: hidden;
}

.footer::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 5px;
    background: linear-gradient(90deg, var(--magenta-pink), var(--warm-orange));
    animation: shimmer 3s infinite linear;
}

@keyframes shimmer {
    0% {
        background-position: -1000px 0;
    }
    100% {
        background-position: 1000px 0;
    }
}

.footer-content {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 3rem;
    max-width: 1400px;
    margin: 0 auto 3rem;
    position: relative;
    z-index: 1;
}

.footer-column h4 {
    font-size: 1.2rem;
    margin-bottom: 1.5rem;
    color: var(--white);
    font-family: 'Poppins', sans-serif;
    font-weight: 600;
    position: relative;
    display: inline-block;
}

.footer-column h4::after {
    content: '';
    position: absolute;
    bottom: -5px;
    left: 0;
    width: 40px;
    height: 2px;
    background: var(--warm-orange);
    transition: width 0.3s ease;
}

.footer-column:hover h4::after {
    width: 100%;
}

.footer-column p {
    color: rgba(255, 255, 255, 0.8);
    line-height: 1.6;
    margin-bottom: 1.5rem;
    font-family: 'Inter', 'Roboto', sans-serif;
}

.footer-column ul {
    list-style: none;
}

.footer-column ul li {
    margin-bottom: 0.8rem;
    transition: transform 0.3s ease;
}

.footer-column ul li:hover {
    transform: translateX(5px);
}

.footer-column ul li a {
    color: rgba(255, 255, 255, 0.8);
    text-decoration: none;
    transition: var(--transition);
    display: flex;
    align-items: center;
    gap: 8px;
    font-family: 'Inter', 'Roboto', sans-serif;
}

.footer-column ul li a:hover {
    color: var(--warm-orange);
}

.footer-column ul li i {
    color: var(--warm-orange);
    font-size: 0.8rem;
    transition: transform 0.3s ease;
}

.footer-column ul li:hover i {
    transform: translateX(3px);
}

/* Contact Info */
.contact-info li {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    margin-bottom: 1rem;
}

.contact-info i {
    color: var(--warm-orange);
    margin-top: 3px;
    font-size: 1rem;
    flex-shrink: 0;
}

.contact-info span {
    color: rgba(255, 255, 255, 0.8);
    line-height: 1.5;
}

/* Social Links */
.social-links {
    display: flex;
    gap: 1rem;
    margin-top: 1.5rem;
}

.social-links a {
    width: 40px;
    height: 40px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--white);
    text-decoration: none;
    transition: var(--transition);
}

.social-links a:hover {
    background: var(--magenta-pink);
    transform: translateY(-3px);
    box-shadow: 0 5px 15px rgba(193, 60, 145, 0.3);
}

/* Newsletter Section */
.newsletter-column {
    position: relative;
}

.newsletter-form {
    margin-bottom: 2rem;
    background: rgba(255, 255, 255, 0.1);
    padding: 1.5rem;
    border-radius: 10px;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.input-group {
    display: flex;
    gap: 0.5rem;
    margin-bottom: 0.5rem;
}

.input-group input {
    flex: 1;
    padding: 0.8rem 1rem;
    border: 2px solid rgba(255, 255, 255, 0.2);
    border-radius: 30px;
    background: rgba(255, 255, 255, 0.1);
    color: var(--white);
    font-family: 'Inter', 'Roboto', sans-serif;
    transition: var(--transition);
}

.input-group input:focus {
    outline: none;
    border-color: var(--warm-orange);
    background: rgba(255, 255, 255, 0.15);
}

.input-group input::placeholder {
    color: rgba(255, 255, 255, 0.6);
}

.newsletter-btn {
    background: linear-gradient(135deg, var(--magenta-pink), var(--royal-violet));
    color: var(--white);
    border: none;
    width: 50px;
    height: 50px;
    border-radius: 50%;
    cursor: pointer;
    transition: var(--transition);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
}

.newsletter-btn:hover {
    transform: translateY(-2px) scale(1.1);
    box-shadow: 0 5px 20px rgba(193, 60, 145, 0.4);
}

.form-message {
    min-height: 20px;
    font-size: 0.85rem;
    margin-top: 0.5rem;
    text-align: center;
}

.newsletter-note {
    font-size: 0.8rem;
    color: rgba(255, 255, 255, 0.6);
    margin-top: 0.5rem;
    text-align: center;
}

/* App Download */
.app-download {
    margin-top: 2rem;
}

.app-download h5 {
    font-family: 'Poppins', sans-serif;
    font-size: 1rem;
    margin-bottom: 1rem;
    color: var(--white);
    font-weight: 500;
}

.app-buttons {
    display: flex;
    flex-direction: column;
    gap: 0.8rem;
}

.app-btn {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 0.8rem 1rem;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 8px;
    text-decoration: none;
    transition: var(--transition);
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.app-btn:hover {
    background: rgba(255, 255, 255, 0.2);
    transform: translateY(-2px);
    border-color: var(--warm-orange);
}

.app-btn i {
    font-size: 1.5rem;
    color: var(--warm-orange);
}

.app-btn div {
    display: flex;
    flex-direction: column;
}

.app-btn span {
    font-size: 0.7rem;
    color: rgba(255, 255, 255, 0.7);
    font-family: 'Inter', 'Roboto', sans-serif;
}

.app-btn strong {
    font-size: 0.9rem;
    color: var(--white);
    font-family: 'Poppins', sans-serif;
    font-weight: 600;
}

/* Footer Bottom */
.footer-bottom {
    border-top: 1px solid rgba(255, 255, 255, 0.1);
    padding: 2rem 0;
    margin-top: 2rem;
}

.container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 20px;
}

.footer-bottom-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 2rem;
}

.payment-methods h5 {
    font-family: 'Poppins', sans-serif;
    color: rgba(255, 255, 255, 0.9);
    margin-bottom: 1rem;
    font-size: 0.9rem;
    font-weight: 500;
}

.payment-icons {
    display: flex;
    gap: 1rem;
    font-size: 1.8rem;
    color: rgba(255, 255, 255, 0.8);
}

.payment-icons i {
    transition: var(--transition);
    cursor: pointer;
}

.payment-icons i:hover {
    color: var(--warm-orange);
    transform: translateY(-3px);
}

.certifications {
    display: flex;
    gap: 1.5rem;
    flex-wrap: wrap;
}

.cert-badge {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 0.5rem 1rem;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 20px;
    transition: var(--transition);
}

.cert-badge:hover {
    background: rgba(255, 255, 255, 0.2);
    transform: translateY(-2px);
}

.cert-badge i {
    color: var(--warm-orange);
    font-size: 0.9rem;
}

.cert-badge span {
    font-size: 0.8rem;
    color: rgba(255, 255, 255, 0.9);
    font-family: 'Inter', 'Roboto', sans-serif;
    font-weight: 500;
}

/* Copyright */
.copyright {
    text-align: center;
    padding-top: 2rem;
    margin-top: 2rem;
    border-top: 1px solid rgba(255, 255, 255, 0.1);
    color: rgba(255, 255, 255, 0.7);
    font-size: 0.9rem;
    font-family: 'Inter', 'Roboto', sans-serif;
}

.copyright a {
    color: var(--warm-orange);
    text-decoration: none;
    transition: var(--transition);
    margin: 0 10px;
}

.copyright a:hover {
    color: var(--white);
    text-decoration: underline;
}

.developed-by {
    margin-top: 1rem;
    font-size: 0.85rem;
    color: rgba(255, 255, 255, 0.6);
}

.developed-by i {
    color: var(--magenta-pink);
    margin: 0 5px;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% {
        transform: scale(1);
        color: var(--magenta-pink);
    }
    50% {
        transform: scale(1.2);
        color: var(--warm-orange);
    }
}

/* Responsive Design */
@media (max-width: 992px) {
    .footer-content {
        grid-template-columns: repeat(2, 1fr);
        gap: 2rem;
    }
    
    .footer-bottom-content {
        flex-direction: column;
        text-align: center;
    }
}

@media (max-width: 768px) {
    .footer {
        padding: 3rem 5% 2rem;
    }
    
    .footer-content {
        grid-template-columns: 1fr;
        text-align: center;
    }
    
    .footer-column h4::after {
        left: 50%;
        transform: translateX(-50%);
    }
    
    .social-links {
        justify-content: center;
    }
    
    .contact-info li {
        justify-content: center;
    }
    
    .app-buttons {
        max-width: 300px;
        margin: 0 auto;
    }
    
    .payment-icons {
        justify-content: center;
        flex-wrap: wrap;
    }
    
    .certifications {
        justify-content: center;
    }
}

@media (max-width: 480px) {
    .input-group {
        flex-direction: column;
    }
    
    .newsletter-btn {
        width: 100%;
        border-radius: 30px;
        height: 45px;
        margin-top: 0.5rem;
    }
    
    .app-btn {
        padding: 0.6rem;
    }
}
</style>

<script>
// Newsletter Form Submission
document.addEventListener('DOMContentLoaded', function() {
    const newsletterForm = document.getElementById('newsletter-form');
    const newsletterMessage = document.getElementById('newsletter-message');
    
    if (newsletterForm) {
        newsletterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const email = formData.get('email');
            
            // Basic email validation
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                newsletterMessage.textContent = 'Please enter a valid email address.';
                newsletterMessage.style.color = '#ff6b6b';
                return;
            }
            
            // Show loading state
            newsletterMessage.textContent = 'Subscribing...';
            newsletterMessage.style.color = '#F6A04D';
            
            // Simulate API call (replace with actual AJAX call)
            setTimeout(() => {
                newsletterMessage.textContent = 'Thank you for subscribing! Check your email for confirmation.';
                newsletterMessage.style.color = '#4CAF50';
                newsletterForm.reset();
                
                // Reset message after 5 seconds
                setTimeout(() => {
                    newsletterMessage.textContent = '';
                }, 5000);
            }, 1500);
        });
    }
});
</script>

<!-- JavaScript -->
<script src="js/main.js"></script>
</body>
</html>