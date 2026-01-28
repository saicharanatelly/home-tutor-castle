<?php
include 'includes/header.php';
include 'includes/config.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $subject = mysqli_real_escape_string($conn, $_POST['subject']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);
    
    // Insert into contacts table
    $sql = "INSERT INTO contacts (name, email, phone, subject, message, status) 
            VALUES ('$name', '$email', '$phone', '$subject', '$message', 'unread')";
    
    if (mysqli_query($conn, $sql)) {
        $success = "Thank you for contacting us! We'll get back to you within 24 hours.";
        
        // Send email notification (optional)
        $to = "info@homecastletutor.com";
        $email_subject = "New Contact Form Submission: $subject";
        $email_body = "Name: $name\nEmail: $email\nPhone: $phone\nSubject: $subject\nMessage:\n$message";
        $headers = "From: $email";
        mail($to, $email_subject, $email_body, $headers);
    } else {
        $error = "Error submitting form. Please try again.";
    }
}

// Create contacts table if not exists
$create_table = "CREATE TABLE IF NOT EXISTS contacts (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    subject VARCHAR(200) NOT NULL,
    message TEXT NOT NULL,
    status ENUM('unread', 'read', 'replied') DEFAULT 'unread',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    admin_notes TEXT
)";
mysqli_query($conn, $create_table);
?>

<!-- Contact Hero Section -->
<section class="contact-hero">
    <div class="container">
        <h1>Contact Us</h1>
        <p>Get in touch with our team for any queries or support</p>
    </div>
</section>

<!-- Contact Section -->
<section class="contact-section">
    <div class="container">
        <div class="contact-container">
            <!-- Contact Info -->
            <div class="contact-info">
                <h2>Get in Touch</h2>
                <p>We're here to help you with any questions about our services. Reach out to us through any of these channels.</p>
                
                <div class="contact-methods">
                    <div class="contact-method">
                        <div class="method-icon">
                            <i class="fas fa-phone"></i>
                        </div>
                        <div class="method-content">
                            <h4>Call Us</h4>
                            <p>+91 9876543210</p>
                            <p>Mon-Sat: 9:00 AM - 7:00 PM</p>
                        </div>
                    </div>
                    
                    <div class="contact-method">
                        <div class="method-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="method-content">
                            <h4>Email Us</h4>
                            <p>info@homecastletutor.com</p>
                            <p>support@homecastletutor.com</p>
                        </div>
                    </div>
                    
                    <div class="contact-method">
                        <div class="method-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div class="method-content">
                            <h4>Visit Us</h4>
                            <p>123 Education Street,</p>
                            <p>Knowledge City, Delhi - 110001</p>
                        </div>
                    </div>
                    
                    <div class="contact-method">
                        <div class="method-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="method-content">
                            <h4>Business Hours</h4>
                            <p>Monday - Friday: 9am - 7pm</p>
                            <p>Saturday: 9am - 2pm</p>
                            <p>Sunday: Closed</p>
                        </div>
                    </div>
                </div>
                
                <div class="social-contact">
                    <h4>Follow Us</h4>
                    <div class="social-icons">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                        <a href="#"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
            </div>
            
            <!-- Contact Form -->
            <div class="contact-form">
                <h2>Send Message</h2>
                <p>Fill out the form below and we'll get back to you shortly.</p>
                
                <?php if(isset($success)): ?>
                    <div class="alert success">
                        <i class="fas fa-check-circle"></i> <?php echo $success; ?>
                    </div>
                <?php endif; ?>
                
                <?php if(isset($error)): ?>
                    <div class="alert error">
                        <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
                    </div>
                <?php endif; ?>
                
                <form method="POST" action="">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="name">Full Name *</label>
                            <input type="text" id="name" name="name" required placeholder="Enter your name">
                        </div>
                        
                        <div class="form-group">
                            <label for="email">Email Address *</label>
                            <input type="email" id="email" name="email" required placeholder="Enter your email">
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="phone">Phone Number</label>
                            <input type="tel" id="phone" name="phone" placeholder="Enter your phone number">
                        </div>
                        
                        <div class="form-group">
                            <label for="subject">Subject *</label>
                            <input type="text" id="subject" name="subject" required placeholder="Enter subject">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="message">Message *</label>
                        <textarea id="message" name="message" rows="6" required placeholder="Enter your message..."></textarea>
                    </div>
                    
                    <button type="submit" class="submit-btn">
                        <i class="fas fa-paper-plane"></i> Send Message
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="faq-section">
    <div class="container">
        <h2>Frequently Asked Questions</h2>
        
        <div class="faq-container">
            <div class="faq-item">
                <div class="faq-question">
                    <h4>How quickly do you respond to contact form submissions?</h4>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    <p>We typically respond within 24 hours during business days. For urgent matters, please call our helpline number.</p>
                </div>
            </div>
            
            <div class="faq-item">
                <div class="faq-question">
                    <h4>What information should I include in my message?</h4>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    <p>Please include your name, contact details, student's grade level, subjects needed, preferred timing, and any specific requirements.</p>
                </div>
            </div>
            
            <div class="faq-item">
                <div class="faq-question">
                    <h4>Do you provide home tuition in my area?</h4>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    <p>We provide services in over 50+ cities across India. Please mention your location in the contact form and we'll check availability.</p>
                </div>
            </div>
            
            <div class="faq-item">
                <div class="faq-question">
                    <h4>Can I meet the tutor before starting classes?</h4>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    <p>Yes, we provide a free demo session with the tutor before you make any commitment. This helps ensure compatibility.</p>
                </div>
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
body, p, .contact-hero p, .contact-info > p, .contact-form > p, .method-content p,
.faq-answer p, .form-group label, .alert, .social-contact h4 {
    font-family: 'Inter', 'Roboto', sans-serif;
}

h1, h2, h3, h4, .contact-hero h1, .contact-info h2, .contact-form h2,
.faq-section h2, .method-content h4, .faq-question h4, .submit-btn {
    font-family: 'Poppins', sans-serif;
}

/* Contact Page Styles */
.contact-hero {
    background: linear-gradient(135deg, var(--primary-purple), var(--royal-violet));
    color: var(--white);
    padding: 5rem 5%;
    text-align: center;
}

.contact-hero h1 {
    font-size: 3.5rem;
    margin-bottom: 1rem;
    font-weight: 700;
}

.contact-hero p {
    font-size: 1.2rem;
    opacity: 0.9;
}

.contact-section {
    padding: 5rem 5%;
    background: var(--light-gray);
}

.contact-container {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 4rem;
    max-width: 1200px;
    margin: 0 auto;
}

.contact-info h2,
.contact-form h2 {
    color: var(--primary-purple);
    margin-bottom: 1rem;
    font-size: 2rem;
    font-weight: 600;
}

.contact-info > p,
.contact-form > p {
    color: #666;
    margin-bottom: 2rem;
    line-height: 1.6;
}

.contact-methods {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
    margin-bottom: 3rem;
}

.contact-method {
    display: flex;
    gap: 1.5rem;
    align-items: flex-start;
}

.method-icon {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, var(--royal-violet), var(--magenta-pink));
    color: var(--white);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    flex-shrink: 0;
}

.method-content h4 {
    color: var(--primary-purple);
    margin-bottom: 0.5rem;
    font-size: 1.2rem;
    font-weight: 500;
}

.method-content p {
    color: #666;
    margin-bottom: 0.2rem;
    font-size: 0.95rem;
}

.social-contact {
    margin-top: 2rem;
    padding-top: 2rem;
    border-top: 1px solid var(--medium-gray);
}

.social-contact h4 {
    color: var(--primary-purple);
    margin-bottom: 1rem;
    font-weight: 600;
}

.social-icons {
    display: flex;
    gap: 1rem;
}

.social-icons a {
    width: 45px;
    height: 45px;
    background: var(--royal-violet);
    color: var(--white);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    transition: var(--transition);
}

.social-icons a:hover {
    background: var(--magenta-pink);
    transform: translateY(-3px);
}

.contact-form {
    background: var(--white);
    padding: 3rem;
    border-radius: var(--radius);
    box-shadow: var(--shadow);
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.5rem;
    margin-bottom: 1.5rem;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-group label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 500;
    color: var(--primary-purple);
}

.form-group input,
.form-group textarea {
    width: 100%;
    padding: 0.8rem 1rem;
    border: 2px solid var(--medium-gray);
    border-radius: 8px;
    font-size: 1rem;
    transition: var(--transition);
    font-family: 'Inter', 'Roboto', sans-serif;
}

.form-group input:focus,
.form-group textarea:focus {
    outline: none;
    border-color: var(--royal-violet);
    box-shadow: 0 0 0 3px rgba(94, 43, 151, 0.1);
}

.submit-btn {
    background: var(--magenta-pink);
    color: var(--white);
    border: none;
    padding: 1rem 2rem;
    border-radius: 30px;
    font-size: 1.1rem;
    font-weight: 500;
    cursor: pointer;
    transition: var(--transition);
    width: 100%;
    margin-top: 1rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
}

.submit-btn:hover {
    background: var(--royal-violet);
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(193, 60, 145, 0.3);
}

.alert {
    padding: 1rem;
    border-radius: 8px;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 10px;
}

.alert.success {
    background: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.alert.error {
    background: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

/* FAQ Section */
.faq-section {
    padding: 5rem 5%;
    background: var(--white);
}

.faq-section h2 {
    text-align: center;
    margin-bottom: 3rem;
    color: var(--primary-purple);
    font-size: 2.5rem;
    font-weight: 600;
}

.faq-container {
    max-width: 800px;
    margin: 0 auto;
}

.faq-item {
    border: 1px solid var(--medium-gray);
    border-radius: 8px;
    margin-bottom: 1rem;
    overflow: hidden;
    transition: var(--transition);
}

.faq-item:hover {
    border-color: var(--royal-violet);
}

.faq-question {
    padding: 1.5rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    cursor: pointer;
    background: var(--light-gray);
    transition: var(--transition);
}

.faq-question:hover {
    background: rgba(94, 43, 151, 0.05);
}

.faq-question h4 {
    color: var(--primary-purple);
    font-size: 1.1rem;
    margin: 0;
    flex: 1;
    font-weight: 500;
}

.faq-question i {
    color: var(--magenta-pink);
    transition: var(--transition);
}

.faq-answer {
    padding: 0 1.5rem;
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.3s ease;
}

.faq-answer p {
    padding: 1.5rem 0;
    color: #666;
    line-height: 1.6;
    margin: 0;
    border-top: 1px solid var(--medium-gray);
}

.faq-item.active .faq-question i {
    transform: rotate(180deg);
}

.faq-item.active .faq-answer {
    max-height: 500px;
}

/* Responsive Design */
@media (max-width: 992px) {
    .contact-container {
        grid-template-columns: 1fr;
        gap: 3rem;
    }
    
    .contact-hero h1 {
        font-size: 2.8rem;
    }
}

@media (max-width: 768px) {
    .form-row {
        grid-template-columns: 1fr;
    }
    
    .contact-form {
        padding: 2rem;
    }
    
    .contact-hero h1 {
        font-size: 2.2rem;
    }
    
    .contact-method {
        flex-direction: column;
        text-align: center;
    }
    
    .method-icon {
        margin: 0 auto;
    }
    
    .faq-section h2 {
        font-size: 2rem;
    }
}
</style>

<script>
// FAQ Toggle Functionality
document.addEventListener('DOMContentLoaded', function() {
    const faqItems = document.querySelectorAll('.faq-item');
    
    faqItems.forEach(item => {
        const question = item.querySelector('.faq-question');
        
        question.addEventListener('click', () => {
            // Close other items
            faqItems.forEach(otherItem => {
                if (otherItem !== item && otherItem.classList.contains('active')) {
                    otherItem.classList.remove('active');
                }
            });
            
            // Toggle current item
            item.classList.toggle('active');
        });
    });
    
    // Phone number validation
    const phoneInput = document.getElementById('phone');
    if (phoneInput) {
        phoneInput.addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    }
});
</script>

<?php
include 'includes/footer.php';
?>