<?php
// receive.php

error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');

// 1. Only allow POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Only POST allowed']);
    exit;
}

// 2. Validate API key
$expected_api_key = 'c74e0f5a-8b92-41a1-9ab0-7a3d2062e834'; // Match Moodle plugin setting

if (!isset($_POST['api_key']) || $_POST['api_key'] !== $expected_api_key) {
    http_response_code(403);
    echo json_encode(['status' => 'error', 'message' => 'Invalid API key']);
    exit;
}

// 3. Handle CSV file upload
if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'CSV file upload failed']);
    exit;
}

// Ensure upload directory exists
$upload_dir = __DIR__ . '/uploads';
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0775, true);
}

// Get sanitized server name and timestamp
$servername = isset($_POST['servername']) ? preg_replace('/[^a-zA-Z0-9_\-]/', '_', $_POST['servername']) : 'unknown';
$timestamp = date('Ymd_His');

// Build destination CSV file name
$csv_filename = "results_{$servername}_{$timestamp}.csv";
$csv_path = $upload_dir . '/' . $csv_filename;

// Move uploaded file
if (!move_uploaded_file($_FILES['file']['tmp_name'], $csv_path)) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Failed to save uploaded CSV']);
    exit;
}

// 4. Handle JSON result data
$results_json = $_POST['results'] ?? '';
$results_data = json_decode($results_json, true);

if (json_last_error() !== JSON_ERROR_NONE || !is_array($results_data)) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Invalid JSON in results field']);
    exit;
}

// 5. Save JSON log to logs folder
$log_dir = __DIR__ . '/logs';
if (!is_dir($log_dir)) {
    mkdir($log_dir, 0775, true);
}
$log_file = "$log_dir/result_{$servername}_{$timestamp}.json";
file_put_contents($log_file, json_encode($results_data, JSON_PRETTY_PRINT));

// 6. Respond to sender
echo json_encode([
    'status' => 'success',
    'message' => 'CSV and results received successfully',
    'saved_csv' => basename($csv_path),
    'saved_json' => basename($log_file)
]);
