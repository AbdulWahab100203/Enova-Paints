-- Solvex Database Clean Setup
-- This file will create a fresh database structure
-- Run this if you want to start fresh

-- Create database
CREATE DATABASE IF NOT EXISTS solvex_admin;
USE solvex_admin;

-- =============================================
-- DROP EXISTING TABLES (if they exist)
-- =============================================
DROP TABLE IF EXISTS activity_log;
DROP TABLE IF EXISTS contact_replies;
DROP TABLE IF EXISTS dealer_reviews;
DROP TABLE IF EXISTS contact_submissions;
DROP TABLE IF EXISTS dealer_applications;
DROP TABLE IF EXISTS authorized_dealers;
DROP TABLE IF EXISTS admin_settings;
DROP TABLE IF EXISTS admin_users;

-- =============================================
-- CONTACT FORM SUBMISSIONS TABLE
-- =============================================
CREATE TABLE contact_submissions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(50),
    subject ENUM('general', 'product', 'technical', 'quote', 'dealer', 'other') NOT NULL,
    message TEXT NOT NULL,
    status ENUM('new', 'read', 'replied', 'archived') DEFAULT 'new',
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    admin_notes TEXT,
    replied_by INT,
    replied_at TIMESTAMP NULL,
    INDEX idx_status (status),
    INDEX idx_created_at (created_at),
    INDEX idx_email (email),
    INDEX idx_subject (subject)
);

-- =============================================
-- DEALER APPLICATIONS TABLE
-- =============================================
CREATE TABLE dealer_applications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    business_name VARCHAR(255) NOT NULL,
    contact_person VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(50) NOT NULL,
    address TEXT NOT NULL,
    experience VARCHAR(100),
    additional_message TEXT,
    status ENUM('pending', 'reviewed', 'approved', 'rejected', 'archived') DEFAULT 'pending',
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    admin_notes TEXT,
    reviewed_by INT,
    reviewed_at TIMESTAMP NULL,
    approval_date TIMESTAMP NULL,
    rejection_reason TEXT,
    INDEX idx_status (status),
    INDEX idx_created_at (created_at),
    INDEX idx_email (email),
    INDEX idx_business_name (business_name)
);

-- =============================================
-- ADMIN USERS TABLE
-- =============================================
CREATE TABLE admin_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    full_name VARCHAR(255) NOT NULL,
    role ENUM('admin', 'moderator', 'viewer') DEFAULT 'moderator',
    is_active BOOLEAN DEFAULT TRUE,
    last_login TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_username (username),
    INDEX idx_email (email),
    INDEX idx_role (role)
);

-- =============================================
-- CONTACT REPLIES TABLE
-- =============================================
CREATE TABLE contact_replies (
    id INT AUTO_INCREMENT PRIMARY KEY,
    contact_submission_id INT NOT NULL,
    admin_user_id INT NOT NULL,
    reply_message TEXT NOT NULL,
    reply_type ENUM('email', 'phone', 'internal_note') DEFAULT 'email',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (contact_submission_id) REFERENCES contact_submissions(id) ON DELETE CASCADE,
    FOREIGN KEY (admin_user_id) REFERENCES admin_users(id) ON DELETE CASCADE,
    INDEX idx_contact_submission_id (contact_submission_id),
    INDEX idx_admin_user_id (admin_user_id)
);

-- =============================================
-- DEALER REVIEWS TABLE
-- =============================================
CREATE TABLE dealer_reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    dealer_application_id INT NOT NULL,
    admin_user_id INT NOT NULL,
    review_notes TEXT,
    decision ENUM('approve', 'reject', 'request_info') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (dealer_application_id) REFERENCES dealer_applications(id) ON DELETE CASCADE,
    FOREIGN KEY (admin_user_id) REFERENCES admin_users(id) ON DELETE CASCADE,
    INDEX idx_dealer_application_id (dealer_application_id),
    INDEX idx_admin_user_id (admin_user_id)
);

-- =============================================
-- AUTHORIZED DEALERS TABLE (from dealers.html)
-- =============================================
CREATE TABLE authorized_dealers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    dealer_name VARCHAR(255) NOT NULL,
    address TEXT NOT NULL,
    phone VARCHAR(50) NOT NULL,
    city VARCHAR(100) NOT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_city (city),
    INDEX idx_is_active (is_active)
);

-- =============================================
-- ADMIN SETTINGS TABLE
-- =============================================
CREATE TABLE admin_settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(100) UNIQUE NOT NULL,
    setting_value TEXT,
    setting_description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- =============================================
-- ACTIVITY LOG TABLE
-- =============================================
CREATE TABLE activity_log (
    id INT AUTO_INCREMENT PRIMARY KEY,
    admin_user_id INT,
    action VARCHAR(100) NOT NULL,
    table_name VARCHAR(50),
    record_id INT,
    old_values JSON,
    new_values JSON,
    ip_address VARCHAR(45),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (admin_user_id) REFERENCES admin_users(id) ON DELETE SET NULL,
    INDEX idx_admin_user_id (admin_user_id),
    INDEX idx_action (action),
    INDEX idx_created_at (created_at)
);

-- =============================================
-- INSERT DEFAULT DATA
-- =============================================

-- Insert default admin user (password: admin123 - CHANGE THIS!)
INSERT INTO admin_users (username, email, password_hash, full_name, role) VALUES 
('admin', 'admin@solvex.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'System Administrator', 'admin');

-- Insert authorized dealers from dealers.html
INSERT INTO authorized_dealers (dealer_name, address, phone, city) VALUES
('GFC Fans', 'GFC Fans GT Rd, Small Industrial Area, Gujrat, 50700', '0340 1111008', 'Gujrat'),
('Lahore Fans', 'pir-ahmad, Kotli, N-5, Gujranwala', '0304 1116600', 'Gujranwala'),
('Fans World', 'Fans World, 123, Multan Road, Lahore, Pakistan', '0300 1234567', 'Lahore'),
('Enova Distributors Sialkot', 'Enova Distributors, Cantt, Sialkot, Pakistan', '0300 4567890', 'Sialkot'),
('Enova Distributors Rawalpindi', 'Enova Distributors, Main Bypass Road, Rawalpindi, Pakistan', '0300 4567890', 'Rawalpindi'),
('Enova Distributors Bahawalpur', 'Enova Distributors, Main Market, Bahawalpur, Pakistan', '0300 4567890', 'Bahawalpur');

-- Insert default settings
INSERT INTO admin_settings (setting_key, setting_value, setting_description) VALUES
('contact_email', 'info@solvex.com', 'Email address for contact form submissions'),
('dealer_email', 'dealers@solvex.com', 'Email address for dealer applications'),
('company_name', 'Solvex by Enova Industries', 'Company name for email templates'),
('company_phone', '+92 308 1626206', 'Company phone number'),
('company_address', 'Enova Industries Ltd., Johar Town, Lahore, Pakistan', 'Company address'),
('auto_notify_new_contacts', 'true', 'Send email notification for new contact submissions'),
('auto_notify_new_dealers', 'true', 'Send email notification for new dealer applications'),
('contact_form_enabled', 'true', 'Enable/disable contact form'),
('dealer_form_enabled', 'true', 'Enable/disable dealer application form');

-- =============================================
-- SAMPLE DATA FOR TESTING
-- =============================================

-- Insert sample contact submissions
INSERT INTO contact_submissions (first_name, last_name, email, phone, subject, message, ip_address) VALUES
('John', 'Doe', 'john@example.com', '+1234567890', 'general', 'This is a test message for general inquiry.', '192.168.1.1'),
('Jane', 'Smith', 'jane@example.com', '+1234567891', 'product', 'I need information about your interior paints.', '192.168.1.2'),
('Mike', 'Johnson', 'mike@example.com', '+1234567892', 'quote', 'Please provide a quote for exterior painting project.', '192.168.1.3');

-- Insert sample dealer applications
INSERT INTO dealer_applications (business_name, contact_person, email, phone, address, experience, additional_message, ip_address) VALUES
('ABC Paint Store', 'Ahmed Khan', 'ahmed@abcpaint.com', '+923001234567', 'Main Street, Karachi, Pakistan', '5 years', 'Interested in becoming a dealer.', '192.168.1.4'),
('XYZ Hardware', 'Fatima Ali', 'fatima@xyzhardware.com', '+923001234568', 'Commercial Area, Islamabad, Pakistan', '3 years', 'Looking forward to partnership.', '192.168.1.5');

-- =============================================
-- CREATE INDEXES FOR BETTER PERFORMANCE
-- =============================================
CREATE INDEX idx_contact_status_created ON contact_submissions(status, created_at);
CREATE INDEX idx_dealer_status_created ON dealer_applications(status, created_at);
CREATE INDEX idx_activity_log_admin_action ON activity_log(admin_user_id, action);
CREATE INDEX idx_contact_email_subject ON contact_submissions(email, subject);
CREATE INDEX idx_dealer_business_email ON dealer_applications(business_name, email);

-- =============================================
-- CREATE VIEWS FOR EASIER DATA ACCESS
-- =============================================

-- View for contact submissions with full details
CREATE VIEW contact_submissions_view AS
SELECT 
    cs.*,
    CONCAT(cs.first_name, ' ', cs.last_name) as full_name,
    COUNT(cr.id) as reply_count,
    au.full_name as admin_name
FROM contact_submissions cs
LEFT JOIN admin_users au ON cs.replied_by = au.id
LEFT JOIN contact_replies cr ON cs.id = cr.contact_submission_id
GROUP BY cs.id;

-- View for dealer applications with full details
CREATE VIEW dealer_applications_view AS
SELECT 
    da.*,
    COUNT(dr.id) as review_count,
    au.full_name as admin_name
FROM dealer_applications da
LEFT JOIN admin_users au ON da.reviewed_by = au.id
LEFT JOIN dealer_reviews dr ON da.id = dr.dealer_application_id
GROUP BY da.id;

-- =============================================
-- STORED PROCEDURES
-- =============================================

DELIMITER //

-- Procedure to get contact statistics for dashboard
CREATE PROCEDURE GetContactStats(IN days_back INT)
BEGIN
    SELECT 
        COUNT(*) as total_submissions,
        COUNT(CASE WHEN status = 'new' THEN 1 END) as new_submissions,
        COUNT(CASE WHEN status = 'read' THEN 1 END) as read_submissions,
        COUNT(CASE WHEN status = 'replied' THEN 1 END) as replied_submissions,
        COUNT(CASE WHEN status = 'archived' THEN 1 END) as archived_submissions
    FROM contact_submissions 
    WHERE created_at >= DATE_SUB(NOW(), INTERVAL days_back DAY);
END //

-- Procedure to get dealer statistics for dashboard
CREATE PROCEDURE GetDealerStats(IN days_back INT)
BEGIN
    SELECT 
        COUNT(*) as total_applications,
        COUNT(CASE WHEN status = 'pending' THEN 1 END) as pending_applications,
        COUNT(CASE WHEN status = 'approved' THEN 1 END) as approved_applications,
        COUNT(CASE WHEN status = 'rejected' THEN 1 END) as rejected_applications
    FROM dealer_applications 
    WHERE created_at >= DATE_SUB(NOW(), INTERVAL days_back DAY);
END //

-- Procedure to process contact form submission
CREATE PROCEDURE ProcessContactSubmission(
    IN p_first_name VARCHAR(100),
    IN p_last_name VARCHAR(100),
    IN p_email VARCHAR(255),
    IN p_phone VARCHAR(50),
    IN p_subject ENUM('general', 'product', 'technical', 'quote', 'dealer', 'other'),
    IN p_message TEXT,
    IN p_ip_address VARCHAR(45),
    IN p_user_agent TEXT
)
BEGIN
    INSERT INTO contact_submissions (
        first_name, last_name, email, phone, subject, message, 
        ip_address, user_agent
    ) VALUES (
        p_first_name, p_last_name, p_email, p_phone, p_subject, p_message,
        p_ip_address, p_user_agent
    );
    
    SELECT LAST_INSERT_ID() as submission_id;
END //

-- Procedure to process dealer application
CREATE PROCEDURE ProcessDealerApplication(
    IN p_business_name VARCHAR(255),
    IN p_contact_person VARCHAR(255),
    IN p_email VARCHAR(255),
    IN p_phone VARCHAR(50),
    IN p_address TEXT,
    IN p_experience VARCHAR(100),
    IN p_additional_message TEXT,
    IN p_ip_address VARCHAR(45),
    IN p_user_agent TEXT
)
BEGIN
    INSERT INTO dealer_applications (
        business_name, contact_person, email, phone, address,
        experience, additional_message, ip_address, user_agent
    ) VALUES (
        p_business_name, p_contact_person, p_email, p_phone, p_address,
        p_experience, p_additional_message, p_ip_address, p_user_agent
    );
    
    SELECT LAST_INSERT_ID() as application_id;
END //

DELIMITER ;

-- =============================================
-- TEST QUERIES
-- =============================================

-- Test: Get all contact submissions
SELECT 
    id,
    CONCAT(first_name, ' ', last_name) as full_name,
    email,
    subject,
    status,
    created_at
FROM contact_submissions 
ORDER BY created_at DESC;

-- Test: Get all dealer applications
SELECT 
    id,
    business_name,
    contact_person,
    email,
    status,
    created_at
FROM dealer_applications 
ORDER BY created_at DESC;

-- Test: Get contact statistics
SELECT 
    COUNT(*) as total_submissions,
    COUNT(CASE WHEN status = 'new' THEN 1 END) as new_submissions,
    COUNT(CASE WHEN status = 'read' THEN 1 END) as read_submissions,
    COUNT(CASE WHEN status = 'replied' THEN 1 END) as replied_submissions
FROM contact_submissions;

-- Test: Get dealer statistics
SELECT 
    COUNT(*) as total_applications,
    COUNT(CASE WHEN status = 'pending' THEN 1 END) as pending_applications,
    COUNT(CASE WHEN status = 'approved' THEN 1 END) as approved_applications,
    COUNT(CASE WHEN status = 'rejected' THEN 1 END) as rejected_applications
FROM dealer_applications;

-- Test: Get authorized dealers
SELECT 
    dealer_name,
    address,
    phone,
    city
FROM authorized_dealers 
WHERE is_active = TRUE
ORDER BY city, dealer_name; 