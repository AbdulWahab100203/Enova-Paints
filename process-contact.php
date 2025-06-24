<?php
// Contact form processing
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

// Debug: Log the received data
error_log('Contact form submission received: ' . print_r($input, true));

// Validate required fields
$required_fields = ['first_name', 'last_name', 'email', 'subject', 'message'];
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
$first_name = htmlspecialchars(trim($input['first_name']));
$last_name = htmlspecialchars(trim($input['last_name']));
$email = filter_var(trim($input['email']), FILTER_SANITIZE_EMAIL);
$phone = !empty($input['phone']) ? htmlspecialchars(trim($input['phone'])) : '';
$subject = htmlspecialchars(trim($input['subject']));
$message = htmlspecialchars(trim($input['message']));

// Prepare email content
$to = 'info@solvex.com'; // Change to your email
$subject = 'New Contact Form Submission - Solvex';
$headers = "From: $email\r\n";
$headers .= "Reply-To: $email\r\n";
$headers .= "Content-Type: text/html; charset=UTF-8\r\n";

$email_body = "
<html>
<head>
    <title>New Contact Form Submission</title>
</head>
<body>
    <h2>New Contact Form Submission</h2>
    <p><strong>Name:</strong> $first_name $last_name</p>
    <p><strong>Email:</strong> $email</p>
    " . (!empty($phone) ? "<p><strong>Phone:</strong> $phone</p>" : "") . "
    <p><strong>Subject:</strong> $subject</p>
    <p><strong>Message:</strong></p>
    <p>" . nl2br($message) . "</p>
    <hr>
    <p><small>This message was sent from the Solvex website contact form.</small></p>
</body>
</html>
";

// Send email
$mail_sent = mail($to, $subject, $email_body, $headers);

// Log the submission (optional)
$log_entry = date('Y-m-d H:i:s') . " - Name: $first_name $last_name, Email: $email, Phone: $phone, Subject: $subject\n";
file_put_contents('contact_log.txt', $log_entry, FILE_APPEND | LOCK_EX);

if ($mail_sent) {
    echo json_encode([
        'success' => true,
        'message' => 'Thank you for your message. We\'ll get back to you soon!'
    ]);
} else {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Failed to send message. Please try again later.'
    ]);
}
?> 