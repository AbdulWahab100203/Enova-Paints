<?php
// --- SIMPLIFIED TEST SCRIPT ---
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// 1. Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Method not allowed. This script only accepts POST requests.']);
    exit;
}

// 2. Get the data sent from the form
$input = json_decode(file_get_contents('php://input'), true);

// 3. Check if the data is valid JSON
if (json_last_error() !== JSON_ERROR_NONE) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Invalid data format received. The script expects JSON.']);
    exit;
}

// 4. If everything is okay, send a success response back with the data
echo json_encode([
    'success' => true,
    'message' => 'Test successful! The PHP script received your data.',
    'data_received' => $input
]);
?> 