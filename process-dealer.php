<?php
// Dealer application processing
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

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
        $errors[] = ucfirst(str_replace('_', ' ', $field)) . ' is required';
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
$message = !empty($input['message']) ? htmlspecialchars(trim($input['message'])) : '';

// Prepare email content
$to = 'dealers@solvex.com'; // Change to your dealer applications email
$subject = 'New Dealer Application - Solvex';
$headers = "From: $email\r\n";
$headers .= "Reply-To: $email\r\n";
$headers .= "Content-Type: text/html; charset=UTF-8\r\n";

$email_body = "
<html>
<head>
    <title>New Dealer Application</title>
</head>
<body>
    <h2>New Dealer Application</h2>
    <p><strong>Business Name:</strong> $businessName</p>
    <p><strong>Contact Person:</strong> $contactPerson</p>
    <p><strong>Email:</strong> $email</p>
    <p><strong>Phone:</strong> $phone</p>
    <p><strong>Address:</strong></p>
    <p>" . nl2br($address) . "</p>
    " . (!empty($experience) ? "<p><strong>Experience:</strong> $experience</p>" : "") . "
    " . (!empty($message) ? "<p><strong>Additional Information:</strong></p><p>" . nl2br($message) . "</p>" : "") . "
    <hr>
    <p><small>This application was submitted from the Solvex website dealer page.</small></p>
</body>
</html>
";

// Send email
$mail_sent = mail($to, $subject, $email_body, $headers);

// Log the application (optional)
$log_entry = date('Y-m-d H:i:s') . " - Business: $businessName, Contact: $contactPerson, Email: $email\n";
file_put_contents('dealer_applications.txt', $log_entry, FILE_APPEND | LOCK_EX);

if ($mail_sent) {
    echo json_encode([
        'success' => true,
        'message' => 'Thank you for your application. We\'ll review it and get back to you soon!'
    ]);
} else {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Failed to submit application. Please try again later.'
    ]);
}
?> 