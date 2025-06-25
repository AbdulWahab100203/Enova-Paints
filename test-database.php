<?php
// Test database connection and tables
require_once 'config/database.php';

echo "<h1>Database Connection Test</h1>";

// Test database connection
echo "<h2>1. Testing Database Connection</h2>";
try {
    $pdo = getDatabaseConnection();
    echo "<p style='color: green;'>✅ Database connection successful!</p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Database connection failed: " . $e->getMessage() . "</p>";
    exit;
}

// Check if tables exist
echo "<h2>2. Checking Database Tables</h2>";
$tables = ['contact_submissions', 'dealer_applications', 'admin_users', 'authorized_dealers'];

foreach ($tables as $table) {
    try {
        $stmt = $pdo->query("SHOW TABLES LIKE '$table'");
        if ($stmt->rowCount() > 0) {
            echo "<p style='color: green;'>✅ Table '$table' exists</p>";
        } else {
            echo "<p style='color: red;'>❌ Table '$table' does not exist</p>";
        }
    } catch (Exception $e) {
        echo "<p style='color: red;'>❌ Error checking table '$table': " . $e->getMessage() . "</p>";
    }
}

// Check sample data
echo "<h2>3. Checking Sample Data</h2>";

// Check contact submissions
try {
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM contact_submissions");
    $result = $stmt->fetch();
    echo "<p>Contact submissions: " . $result['count'] . " records</p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error checking contact_submissions: " . $e->getMessage() . "</p>";
}

// Check dealer applications
try {
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM dealer_applications");
    $result = $stmt->fetch();
    echo "<p>Dealer applications: " . $result['count'] . " records</p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error checking dealer_applications: " . $e->getMessage() . "</p>";
}

// Check admin users
try {
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM admin_users");
    $result = $stmt->fetch();
    echo "<p>Admin users: " . $result['count'] . " records</p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error checking admin_users: " . $e->getMessage() . "</p>";
}

// Check authorized dealers
try {
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM authorized_dealers");
    $result = $stmt->fetch();
    echo "<p>Authorized dealers: " . $result['count'] . " records</p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error checking authorized_dealers: " . $e->getMessage() . "</p>";
}

// Show recent submissions
echo "<h2>4. Recent Contact Submissions</h2>";
try {
    $stmt = $pdo->query("
        SELECT id, first_name, last_name, email, subject, created_at 
        FROM contact_submissions 
        ORDER BY created_at DESC 
        LIMIT 5
    ");
    $submissions = $stmt->fetchAll();
    
    if (count($submissions) > 0) {
        echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr><th>ID</th><th>Name</th><th>Email</th><th>Subject</th><th>Date</th></tr>";
        foreach ($submissions as $submission) {
            echo "<tr>";
            echo "<td>" . $submission['id'] . "</td>";
            echo "<td>" . $submission['first_name'] . " " . $submission['last_name'] . "</td>";
            echo "<td>" . $submission['email'] . "</td>";
            echo "<td>" . $submission['subject'] . "</td>";
            echo "<td>" . $submission['created_at'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No contact submissions found.</p>";
    }
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error fetching contact submissions: " . $e->getMessage() . "</p>";
}

// Show recent dealer applications
echo "<h2>5. Recent Dealer Applications</h2>";
try {
    $stmt = $pdo->query("
        SELECT id, business_name, contact_person, email, status, created_at 
        FROM dealer_applications 
        ORDER BY created_at DESC 
        LIMIT 5
    ");
    $applications = $stmt->fetchAll();
    
    if (count($applications) > 0) {
        echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr><th>ID</th><th>Business</th><th>Contact</th><th>Email</th><th>Status</th><th>Date</th></tr>";
        foreach ($applications as $application) {
            echo "<tr>";
            echo "<td>" . $application['id'] . "</td>";
            echo "<td>" . $application['business_name'] . "</td>";
            echo "<td>" . $application['contact_person'] . "</td>";
            echo "<td>" . $application['email'] . "</td>";
            echo "<td>" . $application['status'] . "</td>";
            echo "<td>" . $application['created_at'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No dealer applications found.</p>";
    }
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error fetching dealer applications: " . $e->getMessage() . "</p>";
}

echo "<h2>6. Next Steps</h2>";
echo "<p>If all tests pass, your forms should now save data to the database.</p>";
echo "<p><a href='contact.html'>Test Contact Form</a></p>";
echo "<p><a href='dealers.html'>Test Dealer Form</a></p>";
echo "<p><a href='test-contact.html'>Simple Contact Test</a></p>";
?> 