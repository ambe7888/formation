<?php
function sanitize_text(string $value): string
{
    return trim(strip_tags($value));
}

$name = sanitize_text($_POST['name'] ?? '');
$phone = sanitize_text($_POST['phone'] ?? '');
$email = sanitize_text($_POST['email'] ?? '');
$course = sanitize_text($_POST['course'] ?? '');
$month = sanitize_text($_POST['month'] ?? '');
$message = sanitize_text($_POST['message'] ?? '');

if ($name === '' || $phone === '' || $email === '' || $course === '' || $month === '') {
    $errorQuery = 'status=error#inscription';
    if ($course !== '') {
        $errorQuery = 'status=error&course=' . urlencode($course) . '#inscription';
    }

    header('Location: index.php?' . $errorQuery);
    exit;
}

$entry = [
    'name' => $name,
    'phone' => $phone,
    'email' => $email,
    'course' => $course,
    'month' => $month,
    'message' => $message,
    'created_at' => date('c'),
];

$dataFile = __DIR__ . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'registrations.json';
$registrations = [];

if (file_exists($dataFile)) {
    $existing = file_get_contents($dataFile);
    $decoded = json_decode($existing ?: '[]', true);
    if (is_array($decoded)) {
        $registrations = $decoded;
    }
}

$registrations[] = $entry;
file_put_contents($dataFile, json_encode($registrations, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

header('Location: index.php?status=success&course=' . urlencode($course) . '#inscription');
exit;
