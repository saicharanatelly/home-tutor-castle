<?php
include 'includes/header.php';
include 'includes/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $grade = mysqli_real_escape_string($conn, $_POST['grade']);
    $subjects = mysqli_real_escape_string($conn, $_POST['subjects']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $requirements = mysqli_real_escape_string($conn, $_POST['requirements']);
    
    $sql = "INSERT INTO student_requirements (student_name, email, phone, grade_level, subjects, location, requirements) 
            VALUES ('$name', '$email', '$phone', '$grade', '$subjects', '$location', '$requirements')";
    
    if (mysqli_query($conn, $sql)) {
        $success = "Your requirements have been submitted successfully! We'll contact you within 30 minutes.";
    } else {
        $error = "Error submitting requirements. Please try again.";
    }
}
?>

<section class="portal-hero">
    <div class="container">
        <h1>Student Portal</h1>
        <p>Post your requirements and find the perfect tutor</p>
    </div>
</section>

<section class="requirement-form">
    <div class="container">
        <div class="form-container">
            <h2>Post Your Requirements</h2>
            <p>Fill the form below and our AI will match you with the best tutors</p>
            
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
                <div class="form-group">
                    <label for="name">Student Name *</label>
                    <input type="text" id="name" name="name" required placeholder="Enter student name">
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="email">Email *</label>
                        <input type="email" id="email" name="email" required placeholder="Enter email address">
                    </div>
                    
                    <div class="form-group">
                        <label for="phone">Phone Number *</label>
                        <input type="tel" id="phone" name="phone" required placeholder="Enter phone number">
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="grade">Grade/Class *</label>
                        <select id="grade" name="grade" required>
                            <option value="">Select Grade</option>
                            <option value="KG">KG</option>
                            <option value="1-5">Class 1-5</option>
                            <option value="6-8">Class 6-8</option>
                            <option value="9-10">Class 9-10</option>
                            <option value="11-12">Class 11-12</option>
                            <option value="College">College</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="location">Location *</label>
                        <input type="text" id="location" name="location" required placeholder="Enter your location">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="subjects">Subjects Required *</label>
                    <input type="text" id="subjects" name="subjects" required placeholder="e.g., Mathematics, Physics, Chemistry">
                </div>
                
                <div class="form-group">
                    <label for="requirements">Specific Requirements</label>
                    <textarea id="requirements" name="requirements" rows="4" placeholder="Any specific requirements, preferred timing, etc."></textarea>
                </div>
                
                <button type="submit" class="submit-btn">
                    <i class="fas fa-paper-plane"></i> Submit Requirements
                </button>
            </form>
        </div>
    </div>
</section>

<style>
.portal-hero {
    background: linear-gradient(135deg, var(--primary-orange), var(--primary-dark));
    color: var(--white);
    padding: 4rem 5%;
    text-align: center;
}

.portal-hero h1 {
    font-size: 3rem;
    margin-bottom: 1rem;
}

.portal-hero p {
    font-size: 1.2rem;
    opacity: 0.9;
}

.requirement-form {
    padding: 4rem 5%;
    background: var(--light-gray);
}

.container {
    max-width: 1200px;
    margin: 0 auto;
}

.form-container {
    background: var(--white);
    padding: 3rem;
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    max-width: 800px;
    margin: 0 auto;
}

.form-container h2 {
    color: var(--dark-gray);
    margin-bottom: 0.5rem;
    font-size: 2rem;
}

.form-container > p {
    color: #666;
    margin-bottom: 2rem;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.5rem;
}

.form-group label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 600;
    color: var(--dark-gray);
}

.form-group input,
.form-group select,
.form-group textarea {
    width: 100%;
    padding: 0.8rem 1rem;
    border: 2px solid var(--medium-gray);
    border-radius: 8px;
    font-size: 1rem;
    transition: var(--transition);
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
    outline: none;
    border-color: var(--primary-orange);
}

.submit-btn {
    background: var(--primary-orange);
    color: var(--white);
    border: none;
    padding: 1rem 2rem;
    border-radius: 30px;
    font-size: 1.1rem;
    font-weight: 600;
    cursor: pointer;
    transition: var(--transition);
    width: 100%;
    margin-top: 1rem;
}

.submit-btn:hover {
    background: var(--primary-dark);
    transform: translateY(-2px);
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

@media (max-width: 768px) {
    .form-row {
        grid-template-columns: 1fr;
    }
    
    .form-container {
        padding: 2rem;
    }
    
    .portal-hero h1 {
        font-size: 2.2rem;
    }
}
</style>

<?php
include 'includes/footer.php';
?>