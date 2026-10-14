<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$message = trim($_POST['message'] ?? '');
if (empty($name) || empty($email) || empty($message)) {
    header('Location: ' . SITE_URL . '/contact.php');
    exit;
}
try {
    $pdo = getDB();
    $stmt = $pdo->prepare("INSERT INTO contact_submissions (name, email, phone, subject, message) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([
        $name,
        $email,
        trim($_POST['phone'] ?? ''),
        trim($_POST['subject'] ?? ''),
        $message
    ]);
} catch (Exception $e) {}
header('Location: ' . SITE_URL . '/contact.php?sent=1');
exit;
