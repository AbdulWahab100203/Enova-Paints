<?php
// Database configuration file
// Update these settings according to your database setup

// Database connection settings
define('DB_HOST', 'localhost');
define('DB_NAME', 'solvex_admin');
define('DB_USER', 'root'); // Change to your database username
define('DB_PASS', ''); // Change to your database password
define('DB_CHARSET', 'utf8');

// Email settings
define('CONTACT_EMAIL', 'info@solvex.com');
define('DEALER_EMAIL', 'dealers@solvex.com');

// Company information
define('COMPANY_NAME', 'Solvex by Enova Industries');
define('COMPANY_PHONE', '+92 308 1626206');
define('COMPANY_ADDRESS', 'Enova Industries Ltd., Johar Town, Lahore, Pakistan');

// Database connection function
function getDatabaseConnection() {
    try {
        $pdo = new PDO(
            "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET,
            DB_USER,
            DB_PASS,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false
            ]
        );
        return $pdo;
    } catch (PDOException $e) {
        error_log('Database connection failed: ' . $e->getMessage());
        throw new Exception('Database connection failed');
    }
}

// Test database connection
function testDatabaseConnection() {
    try {
        $pdo = getDatabaseConnection();
        $stmt = $pdo->query('SELECT 1');
        return true;
    } catch (Exception $e) {
        return false;
    }
}
?> 