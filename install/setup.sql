-- Shining Light Christian College - Database Schema
CREATE DATABASE IF NOT EXISTS shining_light_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE shining_light_db;

-- Admin users (role: admin, editor, viewer)
CREATE TABLE IF NOT EXISTS admins (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    role ENUM('admin','editor','viewer') DEFAULT 'editor',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Site settings (key-value)
CREATE TABLE IF NOT EXISTS site_settings (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(100) UNIQUE NOT NULL,
    setting_value TEXT,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Bible verse for footer (rotates or single)
CREATE TABLE IF NOT EXISTS bible_verses (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    verse_text TEXT NOT NULL,
    reference VARCHAR(50) NOT NULL,
    is_active TINYINT(1) DEFAULT 1,
    sort_order INT DEFAULT 0
);

-- Pages content (About, Mission, Vision, etc.)
CREATE TABLE IF NOT EXISTS page_content (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    page_slug VARCHAR(50) UNIQUE NOT NULL,
    title VARCHAR(200) NOT NULL,
    content LONGTEXT,
    meta_description VARCHAR(255),
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Faculty/Staff profiles
CREATE TABLE IF NOT EXISTS faculty (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    title VARCHAR(100),
    department VARCHAR(100),
    bio TEXT,
    photo VARCHAR(255),
    email VARCHAR(100),
    sort_order INT DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Academic programs
CREATE TABLE IF NOT EXISTS programs (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    description TEXT,
    level VARCHAR(50),
    duration VARCHAR(50),
    sort_order INT DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1
);

-- Academic calendar events
CREATE TABLE IF NOT EXISTS calendar_events (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    event_date DATE NOT NULL,
    end_date DATE,
    description TEXT,
    event_type VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Enrollments
CREATE TABLE IF NOT EXISTS enrollments (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    application_number VARCHAR(20) UNIQUE NOT NULL,
    status ENUM('pending','under_review','approved','rejected') DEFAULT 'pending',
    -- Student
    student_first_name VARCHAR(50) NOT NULL,
    student_last_name VARCHAR(50) NOT NULL,
    student_middle_name VARCHAR(50),
    date_of_birth DATE NOT NULL,
    gender ENUM('male','female','other') NOT NULL,
    grade_level VARCHAR(50) NOT NULL,
    school_year VARCHAR(20) NOT NULL,
    previous_school VARCHAR(200),
    -- Parent/Guardian 1
    parent1_name VARCHAR(100) NOT NULL,
    parent1_relation VARCHAR(50),
    parent1_phone VARCHAR(30) NOT NULL,
    parent1_email VARCHAR(100) NOT NULL,
    parent1_address TEXT,
    -- Parent/Guardian 2 (optional)
    parent2_name VARCHAR(100),
    parent2_relation VARCHAR(50),
    parent2_phone VARCHAR(30),
    parent2_email VARCHAR(100),
    -- Documents (stored filenames)
    document_birth_cert VARCHAR(255),
    document_report_card VARCHAR(255),
    document_photo VARCHAR(255),
    document_other VARCHAR(255),
    notes TEXT,
    admin_notes TEXT,
    submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    reviewed_at TIMESTAMP NULL,
    reviewed_by INT UNSIGNED,
    FOREIGN KEY (reviewed_by) REFERENCES admins(id) ON DELETE SET NULL
);

-- News & Announcements
CREATE TABLE IF NOT EXISTS news (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    excerpt TEXT,
    content LONGTEXT NOT NULL,
    featured_image VARCHAR(255),
    category VARCHAR(50),
    is_published TINYINT(1) DEFAULT 0,
    published_at TIMESTAMP NULL,
    author_id INT UNSIGNED,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (author_id) REFERENCES admins(id) ON DELETE SET NULL
);

-- Gallery images
CREATE TABLE IF NOT EXISTS gallery (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200),
    description TEXT,
    image_path VARCHAR(255) NOT NULL,
    category VARCHAR(50),
    sort_order INT DEFAULT 0,
    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS contact_submissions (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(30),
    subject VARCHAR(200),
    message TEXT NOT NULL,
    is_read TINYINT(1) DEFAULT 0,
    submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Default admin (password: admin123 - CHANGE IN PRODUCTION)
-- Hash below is bcrypt for "admin123". Previous hash was for "password" and caused login to fail.
INSERT INTO admins (username, password_hash, email, full_name, role) VALUES
('admin', '$2y$10$5Oan0tavzvAIXDlmYUs5X.IFXGHDU666uBFOkqu2Nn191sFYcJ5Ym', 'slcc.educ@gmail.com', 'Administrator', 'admin');

-- Default contact info (Binangonan, Rizal)
INSERT INTO site_settings (setting_key, setting_value) VALUES
('address', 'Rizal Ave. Ext. Hiway Batingan, Binangonan, Philippines, 1940'),
('phone', '0997 619 9710'),
('facebook_url', 'https://www.facebook.com/slcc.eagles')
ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value);

-- Default Bible verse
INSERT INTO bible_verses (verse_text, reference, is_active, sort_order) VALUES
('Let your light so shine before men, that they may see your good works, and glorify your Father which is in heaven.', 'Matthew 5:16', 1, 0);

-- Default page content placeholders
INSERT INTO page_content (page_slug, title, content, meta_description) VALUES
('about_history', 'Our History', '<p>Shining Light Christian College Inc. was founded with a vision to provide faith-centered education that nurtures the mind, body, and spirit. Our journey began with a small group of dedicated educators and has grown into a thriving community of learners.</p>', 'Learn about our history and founding.'),
('about_mission', 'Our Mission', '<p>To provide excellent Christ-centered education that develops students academically, spiritually, and morally, preparing them to be leaders who shine God\'s light in the world.</p>', 'Our mission statement.'),
('about_vision', 'Our Vision', '<p>To be a leading Christian educational institution recognized for academic excellence, character formation, and service to the community.</p>', 'Our vision for the future.');
