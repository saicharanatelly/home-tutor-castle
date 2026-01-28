CREATE DATABASE IF NOT EXISTS home_castle_tutor;
USE home_castle_tutor;

-- Admin Table
CREATE TABLE admins (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO admins (username, password, email) VALUES
('admin', '$2y$10$YourHashedPasswordHere', 'admin@homecastletutor.com');

-- Content Management Table
CREATE TABLE website_content (
    id INT PRIMARY KEY AUTO_INCREMENT,
    page_name VARCHAR(50) NOT NULL,
    section_name VARCHAR(50) NOT NULL,
    content TEXT,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Banner Images Table
CREATE TABLE banners (
    id INT PRIMARY KEY AUTO_INCREMENT,
    page_name VARCHAR(50) NOT NULL,
    image_path VARCHAR(255) NOT NULL,
    alt_text VARCHAR(100),
    is_active BOOLEAN DEFAULT TRUE,
    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Student Requirements Table
CREATE TABLE student_requirements (
    id INT PRIMARY KEY AUTO_INCREMENT,
    student_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    grade_level VARCHAR(50),
    subjects TEXT,
    location VARCHAR(100),
    requirements TEXT,
    status ENUM('pending', 'processing', 'matched', 'completed') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
-- Contacts Table
CREATE TABLE contacts (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    subject VARCHAR(200) NOT NULL,
    message TEXT NOT NULL,
    status ENUM('unread', 'read', 'replied') DEFAULT 'unread',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    admin_notes TEXT
);

-- Tutors Table
CREATE TABLE tutors (
    id INT PRIMARY KEY AUTO_INCREMENT,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    phone VARCHAR(20) NOT NULL,
    qualification TEXT NOT NULL,
    experience VARCHAR(50),
    subjects TEXT NOT NULL,
    grade_levels TEXT NOT NULL,
    location VARCHAR(100) NOT NULL,
    teaching_mode ENUM('home', 'online', 'both') DEFAULT 'both',
    hourly_rate DECIMAL(10,2),
    status ENUM('pending', 'verified', 'rejected', 'active') DEFAULT 'pending',
    verification_documents TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Student Requirements (Updated)
CREATE TABLE student_requirements (
    id INT PRIMARY KEY AUTO_INCREMENT,
    student_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    grade_level VARCHAR(50),
    subjects TEXT,
    location VARCHAR(100),
    requirements TEXT,
    preferred_mode ENUM('home', 'online', 'both') DEFAULT 'both',
    preferred_time VARCHAR(50),
    status ENUM('pending', 'processing', 'matched', 'completed', 'cancelled') DEFAULT 'pending',
    assigned_tutor_id INT,
    admin_notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (assigned_tutor_id) REFERENCES tutors(id) ON DELETE SET NULL
);

-- Website Content Management
CREATE TABLE website_content (
    id INT PRIMARY KEY AUTO_INCREMENT,
    page_name VARCHAR(50) NOT NULL,
    section_name VARCHAR(50) NOT NULL,
    content_title VARCHAR(200),
    content TEXT,
    image_path VARCHAR(255),
    display_order INT DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Banners/Sliders
CREATE TABLE banners (
    id INT PRIMARY KEY AUTO_INCREMENT,
    page_name VARCHAR(50) NOT NULL,
    image_path VARCHAR(255) NOT NULL,
    title VARCHAR(200),
    subtitle TEXT,
    button_text VARCHAR(50),
    button_link VARCHAR(255),
    display_order INT DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);