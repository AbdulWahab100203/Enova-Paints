<?php
// Debug form submission
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Log all incoming data
error_log('=== DEBUG FORM SUBMISSION ===');
error_log('Request Method: ' . $_SERVER['REQUEST_METHOD']);
error_log('Content Type: ' . $_SERVER['CONTENT_TYPE'] ?? 'Not set');
error_log('Raw input: ' . file_get_contents('php://input'));

// Check if it's a POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'error' => 'Method not allowed. Expected POST, got ' . $_SERVER['REQUEST_METHOD']
    ]);
    exit;
}

// Get JSON input
$raw_input = file_get_contents('php://input');
$input = json_decode($raw_input, true);

// Log the parsed input
error_log('Parsed JSON: ' . print_r($input, true));

// Check if JSON parsing failed
if (json_last_error() !== JSON_ERROR_NONE) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => 'Invalid JSON: ' . json_last_error_msg(),
        'raw_input' => $raw_input
    ]);
    exit;
}

// Check if input is null or empty
if (empty($input)) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => 'No data received or empty input',
        'raw_input' => $raw_input
    ]);
    exit;
}

// Validate required fields
$required_fields = ['first_name', 'last_name', 'email', 'subject', 'message'];
$errors = [];

foreach ($required_fields as $field) {
    if (empty($input[$field])) {
        $errors[] = ucfirst(str_replace('_', ' ', $field)) . ' is required';
    }
}

// If there are errors, return them
if (!empty($errors)) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'errors' => $errors,
        'received_data' => $input
    ]);
    exit;
}

// Test database connection
try {
    require_once 'config/database.php';
    $pdo = getDatabaseConnection();
    error_log('Database connection successful');
} catch (Exception $e) {
    error_log('Database connection failed: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Database connection failed: ' . $e->getMessage()
    ]);
    exit;
}

// Test database insert
try {
    $stmt = $pdo->prepare("
        INSERT INTO contact_submissions 
        (first_name, last_name, email, phone, subject, message, ip_address, user_agent) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ");
    
    $stmt->execute([
        $input['first_name'],
        $input['last_name'],
        $input['email'],
        $input['phone'] ?? '',
        $input['subject'],
        $input['message'],
        $_SERVER['REMOTE_ADDR'] ?? '',
        $_SERVER['HTTP_USER_AGENT'] ?? ''
    ]);
    
    $submission_id = $pdo->lastInsertId();
    error_log("Test submission saved with ID: $submission_id");
    
    echo json_encode([
        'success' => true,
        'message' => 'Debug test successful! Data saved to database.',
        'submission_id' => $submission_id,
        'received_data' => $input
    ]);
    
} catch (PDOException $e) {
    error_log('Database insert failed: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Database insert failed: ' . $e->getMessage(),
        'received_data' => $input
    ]);
    exit;
}
?> 