<?php
// AIScan waitlist capture — appends emails to emails.csv on the server.
header('Content-Type: application/json');

$email = isset($_POST['email']) ? trim($_POST['email']) : '';
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => 'invalid email']);
    exit;
}

$clean = str_replace([',', "\n", "\r", '"'], ' ', $email);
$ip    = $_SERVER['REMOTE_ADDR'] ?? '';
$line  = date('c') . ',' . $clean . ',' . $ip . "\n";

$file = __DIR__ . '/emails.csv';
if (file_put_contents($file, $line, FILE_APPEND | LOCK_EX) === false) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'error' => 'could not save']);
    exit;
}

echo json_encode(['ok' => true]);
?>
