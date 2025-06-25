<?php
// Dealer application processing
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// --- DATABASE & APP CONFIGURATION ---
// Database connection settings
define('DB_HOST', 'localhost');
define('DB_NAME', 'solvex_admin');
define('DB_USER', 'root'); // IMPORTANT: Change to your database username
define('DB_PASS', '');     // IMPORTANT: Change to your database password
define('DB_CHARSET', 'utf8');

// Email settings
define('DEALER_EMAIL', 'dealers@solvex.com');

// Company information
define('COMPANY_NAME', 'Solvex by Enova Industries');

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
        throw new Exception('Database connection failed: ' . $e->getMessage());
    }
}
// --- END CONFIGURATION ---

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

// Get JSON input
$input = json_decode(file_get_contents('php://input'), true);

// Validate required fields
$required_fields = ['businessName', 'contactPerson', 'email', 'phone', 'address'];
$errors = [];

foreach ($required_fields as $field) {
    if (empty($input[$field])) {
        $errors[] = ucfirst(preg_replace('/([A-Z])/', ' $1', $field)) . ' is required';
    }
}

// Validate email
if (!empty($input['email']) && !filter_var($input['email'], FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Please enter a valid email address';
}

// If there are errors, return them
if (!empty($errors)) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'errors' => $errors
    ]);
    exit;
}

// Sanitize input
$businessName = htmlspecialchars(trim($input['businessName']));
$contactPerson = htmlspecialchars(trim($input['contactPerson']));
$email = filter_var(trim($input['email']), FILTER_SANITIZE_EMAIL);
$phone = htmlspecialchars(trim($input['phone']));
$address = htmlspecialchars(trim($input['address']));
$experience = !empty($input['experience']) ? htmlspecialchars(trim($input['experience'])) : '';
$additional_message = !empty($input['message']) ? htmlspecialchars(trim($input['message'])) : '';
$ip_address = $_SERVER['REMOTE_ADDR'] ?? '';
$user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';

// Main process: connect to DB and save data
try {
    $pdo = getDatabaseConnection();
    
    $stmt = $pdo->prepare("
        INSERT INTO dealer_applications 
        (business_name, contact_person, email, phone, address, experience, additional_message, ip_address, user_agent) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    
    $stmt->execute([
        $businessName,
        $contactPerson,
        $email,
        $phone,
        $address,
        $experience,
        $additional_message,
        $ip_address,
        $user_agent
    ]);
    
    $application_id = $pdo->lastInsertId();
    
    // Prepare and send email
    $to = DEALER_EMAIL;
    $email_subject = 'New Dealer Application - ' . COMPANY_NAME;
    $headers = "From: $email\r\nReply-To: $email\r\nContent-Type: text/html; charset=UTF-8\r\n";
    $email_body = "
        <html><body>
        <h2>New Dealer Application</h2>
        <p><strong>Application ID:</strong> $application_id</p>
        <p><strong>Business Name:</strong> $businessName</p>
        <p><strong>Contact Person:</strong> $contactPerson</p>
        <p><strong>Email:</strong> $email</p>
        <p><strong>Phone:</strong> $phone</p>
        <p><strong>Address:</strong><br>" . nl2br($address) . "</p>
        " . (!empty($experience) ? "<p><strong>Experience:</strong> $experience</p>" : "") . "
        " . (!empty($additional_message) ? "<p><strong>Additional Information:</strong><br>" . nl2br($additional_message) . "</p>" : "") . "
        <hr>
        <p><small>This application was submitted from the " . COMPANY_NAME . " website dealer page.</small></p>
        </body></html>
    ";

    $mail_sent = mail($to, $email_subject, $email_body, $headers);
    
    // Success response
    echo json_encode([
        'success' => true,
        'message' => 'Thank you for your application. We\'ll review it and get back to you soon!',
        'application_id' => $application_id,
        'mail_sent' => $mail_sent
    ]);

} catch (Exception $e) {
    error_log($e->getMessage()); // Log the actual error
    
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'A server error occurred. Please try again later.',
        'details' => $e->getMessage() // For debugging; consider removing in production
    ]);
    exit;
}
?> 