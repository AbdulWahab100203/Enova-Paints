<?php
// PHP Configuration Check
echo "<h1>PHP Configuration Check</h1>";

// Check PHP version
echo "<h2>PHP Version</h2>";
echo "<p>PHP Version: " . phpversion() . "</p>";

// Check if PDO is available
echo "<h2>PDO Extension</h2>";
if (extension_loaded('pdo')) {
    echo "<p style='color: green;'>✅ PDO extension is loaded</p>";
    if (extension_loaded('pdo_mysql')) {
        echo "<p style='color: green;'>✅ PDO MySQL driver is available</p>";
    } else {
        echo "<p style='color: red;'>❌ PDO MySQL driver is NOT available</p>";
    }
} else {
    echo "<p style='color: red;'>❌ PDO extension is NOT loaded</p>";
}

// Check if JSON extension is available
echo "<h2>JSON Extension</h2>";
if (extension_loaded('json')) {
    echo "<p style='color: green;'>✅ JSON extension is loaded</p>";
} else {
    echo "<p style='color: red;'>❌ JSON extension is NOT loaded</p>";
}

// Check file permissions
echo "<h2>File Permissions</h2>";
$files_to_check = [
    'process-contact.php',
    'process-dealer.php',
    'config/database.php',
    'test-database.php'
];

foreach ($files_to_check as $file) {
    if (file_exists($file)) {
        if (is_readable($file)) {
            echo "<p style='color: green;'>✅ $file is readable</p>";
        } else {
            echo "<p style='color: red;'>❌ $file is NOT readable</p>";
        }
    } else {
        echo "<p style='color: red;'>❌ $file does NOT exist</p>";
    }
}

// Check if we can write to current directory
echo "<h2>Directory Permissions</h2>";
$current_dir = getcwd();
if (is_writable($current_dir)) {
    echo "<p style='color: green;'>✅ Current directory is writable</p>";
} else {
    echo "<p style='color: red;'>❌ Current directory is NOT writable</p>";
}

// Check error reporting
echo "<h2>Error Reporting</h2>";
echo "<p>Error Reporting Level: " . error_reporting() . "</p>";
echo "<p>Display Errors: " . (ini_get('display_errors') ? 'On' : 'Off') . "</p>";
echo "<p>Log Errors: " . (ini_get('log_errors') ? 'On' : 'Off') . "</p>";

// Check if we can connect to database
echo "<h2>Database Connection Test</h2>";
try {
    require_once 'config/database.php';
    $pdo = getDatabaseConnection();
    echo "<p style='color: green;'>✅ Database connection successful</p>";
    
    // Test a simple query
    $stmt = $pdo->query("SELECT 1 as test");
    $result = $stmt->fetch();
    echo "<p style='color: green;'>✅ Database query successful</p>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Database connection failed: " . $e->getMessage() . "</p>";
}

// Check server information
echo "<h2>Server Information</h2>";
echo "<p>Server Software: " . ($_SERVER['SERVER_SOFTWARE'] ?? 'Unknown') . "</p>";
echo "<p>Document Root: " . ($_SERVER['DOCUMENT_ROOT'] ?? 'Unknown') . "</p>";
echo "<p>Script Name: " . ($_SERVER['SCRIPT_NAME'] ?? 'Unknown') . "</p>";

// Check if mod_rewrite is available (for .htaccess)
echo "<h2>Apache Modules</h2>";
if (function_exists('apache_get_modules')) {
    $modules = apache_get_modules();
    if (in_array('mod_rewrite', $modules)) {
        echo "<p style='color: green;'>✅ mod_rewrite is available</p>";
    } else {
        echo "<p style='color: orange;'>⚠️ mod_rewrite is NOT available</p>";
    }
} else {
    echo "<p style='color: orange;'>⚠️ Cannot check Apache modules</p>";
}

echo "<h2>Next Steps</h2>";
echo "<p>If you see any red ❌ marks above, those need to be fixed.</p>";
echo "<p><a href='debug-test.html'>Run Debug Test</a></p>";
echo "<p><a href='test-database.php'>Test Database</a></p>";
echo "<p><a href='contact.html'>Test Contact Form</a></p>";
?> 