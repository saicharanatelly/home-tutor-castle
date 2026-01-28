<?php
include 'includes/header.php';
?>

<section class="about-hero">
    <div class="container">
        <h1>About Home Castle Tutor</h1>
        <p>Transforming education since 2021</p>
    </div>
</section>

<section class="about-content">
    <div class="container">
        <div class="about-section">
            <h2>Who We Are</h2>
            <p><strong>Home Castle Tutor</strong> is the fastest growing and extensive educational platform known for its excellent tutoring & teaching services and initiated in the year 2021. As a team, faculty members & tutors of Home Castle Tutor make subjects, concepts, and courses simple and easy for the students looking for the top grades & ranks in their academic career.</p>
            
            <p>Home Castle Tutor connects students with expert teachers in a digital classroom or specialized one-to-one home tuition to help them understand tough subjects and improve their grades. Whether students need assistance with a complex subject or want to enhance their knowledge, we have instructors that can assist them.</p>
            
            <p>Home Castle Tutor is a tailored, student-focused learning platform that precisely matches the needs of students. We have over five years of experience recruiting top instructors for home tuitions and building new e-learning programs.</p>
        </div>
        
        <div class="vision-mission">
            <div class="vm-card">
                <div class="vm-icon">
                    <i class="fas fa-eye"></i>
                </div>
                <h3>Our Vision</h3>
                <p>Our vision is to empower and help students with the relevant information and proper knowledge related to their subjects and courses through our LMS (Learning Management System) and Home Castle Tutors' faculty. It will help them to make a hurdle-free path while studying for their dream educational career and alma mater. We are always there to help students get to one of the top ranks in their academic careers at each stage of their studies.</p>
            </div>
            
            <div class="vm-card">
                <div class="vm-icon">
                    <i class="fas fa-bullseye"></i>
                </div>
                <h3>Our Mission</h3>
                <p>Ambitiously, our mission at Home Castle Tutor is to provide the best home tutors and Online teachers, guidance, information, and counseling to the students for their academic career growth with pride, originality, and sincerity. With the most validated educational content our teachers, tutors, and team are dedicated to helping each student by all means in making their verdict easier towards their studies.</p>
            </div>
        </div>
        
        <div class="team-section">
            <h2>Our Team</h2>
            <p>Our team has a creative, innovative, and transformative mind working in seamless sync to create a path to better education for students. We are a team of young and energetic enthusiasts who help students and their parents to make choices and decisions regarding Courses, Subjects, and Concepts in a very easier way and better than ever before.</p>
            
            <div class="team-grid">
                <div class="team-member">
                    <div class="member-img">
                        <i class="fas fa-user-tie"></i>
                    </div>
                    <h4>Academic Experts</h4>
                    <p>Subject matter experts with teaching experience</p>
                </div>
                
                <div class="team-member">
                    <div class="member-img">
                        <i class="fas fa-chalkboard-teacher"></i>
                    </div>
                    <h4>Certified Tutors</h4>
                    <p>Qualified and verified teaching professionals</p>
                </div>
                
                <div class="team-member">
                    <div class="member-img">
                        <i class="fas fa-headset"></i>
                    </div>
                    <h4>Support Team</h4>
                    <p>24/7 customer support and guidance</p>
                </div>
                
                <div class="team-member">
                    <div class="member-img">
                        <i class="fas fa-laptop-code"></i>
                    </div>
                    <h4>Tech Team</h4>
                    <p>Building innovative learning platforms</p>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.about-hero {
    background: linear-gradient(135deg, var(--primary-light), var(--primary-orange));
    color: var(--white);
    padding: 5rem 5%;
    text-align: center;
}

.about-hero h1 {
    font-size: 3.5rem;
    margin-bottom: 1rem;
}

.about-hero p {
    font-size: 1.2rem;
    opacity: 0.9;
}

.about-content {
    padding: 5rem 5%;
}

.about-section {
    margin-bottom: 4rem;
}

.about-section h2 {
    color: var(--dark-gray);
    margin-bottom: 1.5rem;
    font-size: 2.2rem;
}

.about-section p {
    margin-bottom: 1.5rem;
    line-height: 1.8;
    color: #555;
    font-size: 1.1rem;
}

.vision-mission {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 2rem;
    margin: 4rem 0;
}

.vm-card {
    background: var(--white);
    padding: 2.5rem;
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    border-top: 5px solid var(--primary-orange);
}

.vm-icon {
    width: 60px;
    height: 60px;
    background: var(--primary-orange);
    color: var(--white);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    margin-bottom: 1.5rem;
}

.vm-card h3 {
    color: var(--dark-gray);
    margin-bottom: 1rem;
    font-size: 1.5rem;
}

.vm-card p {
    color: #666;
    line-height: 1.7;
}

.team-section h2 {
    text-align: center;
    margin-bottom: 1.5rem;
    font-size: 2.2rem;
    color: var(--dark-gray);
}

.team-section > p {
    text-align: center;
    max-width: 800px;
    margin: 0 auto 3rem;
    color: #666;
    font-size: 1.1rem;
}

.team-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 2rem;
}

.team-member {
    text-align: center;
    padding: 2rem;
    background: var(--white);
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    transition: var(--transition);
}

.team-member:hover {
    transform: translateY(-10px);
}

.member-img {
    width: 100px;
    height: 100px;
    background: linear-gradient(135deg, var(--primary-light), var(--primary-orange));
    border-radius: 50%;
    margin: 0 auto 1.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2.5rem;
    color: var(--white);
}

.team-member h4 {
    color: var(--dark-gray);
    margin-bottom: 0.5rem;
    font-size: 1.3rem;
}

.team-member p {
    color: #666;
    font-size: 0.95rem;
}

@media (max-width: 768px) {
    .about-hero h1 {
        font-size: 2.5rem;
    }
    
    .vision-mission {
        grid-template-columns: 1fr;
    }
}
</style>

<?php
include 'includes/footer.php';
?>