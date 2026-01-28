// Mobile Menu Toggle
document.addEventListener('DOMContentLoaded', function() {
    const mobileMenuBtn = document.getElementById('mobileMenuBtn');
    const navLinks = document.querySelector('.nav-links');
    
    if (mobileMenuBtn) {
        mobileMenuBtn.addEventListener('click', function() {
            navLinks.classList.toggle('active');
            mobileMenuBtn.innerHTML = navLinks.classList.contains('active') 
                ? '<i class="fas fa-times"></i>' 
                : '<i class="fas fa-bars"></i>';
        });
    }
    
    // Close mobile menu when clicking on a link
    document.querySelectorAll('.nav-links a').forEach(link => {
        link.addEventListener('click', () => {
            navLinks.classList.remove('active');
            mobileMenuBtn.innerHTML = '<i class="fas fa-bars"></i>';
        });
    });
    
    // Search Tutors Function
    window.searchTutors = function() {
        const location = document.getElementById('locationSearch').value;
        const subject = document.getElementById('subjectSearch').value;
        
        if (!location && !subject) {
            alert('Please enter location or subject to search');
            return;
        }
        
        // Simulate search process
        const searchBtn = document.querySelector('.search-button');
        const originalText = searchBtn.innerHTML;
        
        searchBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Searching...';
        searchBtn.disabled = true;
        
        setTimeout(() => {
            alert(`Searching tutors for ${subject ? subject + ' in ' : ''}${location || 'all locations'}`);
            searchBtn.innerHTML = originalText;
            searchBtn.disabled = false;
        }, 1500);
    };
    
    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            const href = this.getAttribute('href');
            
            if (href === '#') return;
            
            e.preventDefault();
            const targetElement = document.querySelector(href);
            
            if (targetElement) {
                window.scrollTo({
                    top: targetElement.offsetTop - 80,
                    behavior: 'smooth'
                });
            }
        });
    });
    
    // Form validation for student portal
    const requirementForm = document.querySelector('.requirement-form form');
    if (requirementForm) {
        requirementForm.addEventListener('submit', function(e) {
            const phone = document.getElementById('phone');
            const phoneRegex = /^[0-9]{10}$/;
            
            if (!phoneRegex.test(phone.value)) {
                e.preventDefault();
                alert('Please enter a valid 10-digit phone number');
                phone.focus();
                return false;
            }
            
            return true;
        });
    }
});