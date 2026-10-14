<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

session_start();
if (empty($_SESSION['enroll_csrf'])) $_SESSION['enroll_csrf'] = bin2hex(random_bytes(16));

$errors = [];
$required = [
    'student_first_name', 'student_last_name', 'date_of_birth', 'gender', 'grade_level', 'school_year',
    'parent1_name', 'parent1_relation', 'parent1_phone', 'parent1_email'
];
foreach ($required as $f) {
    if (empty(trim($_POST[$f] ?? ''))) $errors[] = "Missing required field: " . str_replace('_', ' ', $f);
}
if (empty($_POST['csrf']) || !hash_equals($_SESSION['enroll_csrf'], $_POST['csrf'])) $errors[] = 'Invalid request.';
if (!isset($_FILES['doc_birth_cert']['error']) || $_FILES['doc_birth_cert']['error'] === UPLOAD_ERR_NO_FILE) $errors[] = 'Birth certificate is required.';
if (!isset($_FILES['doc_report_card']['error']) || $_FILES['doc_report_card']['error'] === UPLOAD_ERR_NO_FILE) $errors[] = 'Report card is required.';
if (!isset($_FILES['doc_photo']['error']) || $_FILES['doc_photo']['error'] === UPLOAD_ERR_NO_FILE) $errors[] = '2x2 ID photo is required.';

if (!empty($errors)) {
    $_SESSION['enroll_errors'] = $errors;
    header('Location: ' . SITE_URL . '/enroll.php');
    exit;
}

$app_number = generateApplicationNumber();
$upload_dir = 'enrollments/';

$doc_birth = '';
$doc_report = '';
$doc_photo = '';
$doc_other = '';

list($ok1, $doc_birth) = safeUpload($_FILES['doc_birth_cert'], $upload_dir);
if (!$ok1) { $_SESSION['enroll_errors'] = [$doc_birth]; header('Location: ' . SITE_URL . '/enroll.php'); exit; }
list($ok2, $doc_report) = safeUpload($_FILES['doc_report_card'], $upload_dir);
if (!$ok2) { $_SESSION['enroll_errors'] = [$doc_report]; header('Location: ' . SITE_URL . '/enroll.php'); exit; }
list($ok3, $doc_photo) = safeUpload($_FILES['doc_photo'], $upload_dir);
if (!$ok3) { $_SESSION['enroll_errors'] = [$doc_photo]; header('Location: ' . SITE_URL . '/enroll.php'); exit; }
if (!empty($_FILES['doc_other']['name']) && $_FILES['doc_other']['error'] !== UPLOAD_ERR_NO_FILE) {
    list($ok4, $doc_other) = safeUpload($_FILES['doc_other'], $upload_dir);
    if (!$ok4) $doc_other = '';
}

try {
    $pdo = getDB();
    $stmt = $pdo->prepare("
        INSERT INTO enrollments (
            application_number, status,
            student_first_name, student_last_name, student_middle_name, date_of_birth, gender, grade_level, school_year, previous_school,
            parent1_name, parent1_relation, parent1_phone, parent1_email, parent1_address,
            parent2_name, parent2_relation, parent2_phone, parent2_email,
            document_birth_cert, document_report_card, document_photo, document_other, notes
        ) VALUES (?, 'pending', ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->execute([
        $app_number,
        trim($_POST['student_first_name']), trim($_POST['student_last_name']), trim($_POST['student_middle_name'] ?? ''),
        $_POST['date_of_birth'], $_POST['gender'], trim($_POST['grade_level']), trim($_POST['school_year']), trim($_POST['previous_school'] ?? ''),
        trim($_POST['parent1_name']), trim($_POST['parent1_relation']), trim($_POST['parent1_phone']), trim($_POST['parent1_email']), trim($_POST['parent1_address'] ?? ''),
        trim($_POST['parent2_name'] ?? ''), trim($_POST['parent2_relation'] ?? ''), trim($_POST['parent2_phone'] ?? ''), trim($_POST['parent2_email'] ?? ''),
        $doc_birth, $doc_report, $doc_photo, $doc_other, trim($_POST['notes'] ?? '')
    ]);
} catch (Exception $e) {
    $_SESSION['enroll_errors'] = ['Could not save application. Please try again.'];
    header('Location: ' . SITE_URL . '/enroll.php');
    exit;
}

$parent_email = trim($_POST['parent1_email']);
$student_name = trim($_POST['student_first_name']) . ' ' . trim($_POST['student_last_name']);
$subject = 'Enrollment Application Received - ' . SITE_NAME;
$body = '
<!DOCTYPE html><html><head><meta charset="UTF-8"></head><body style="font-family:sans-serif;line-height:1.6;color:#333;">
<h2 style="color:#27571d;">Application Received</h2>
<p>Dear Parent/Guardian,</p>
<p>We have received the enrollment application for <strong>' . htmlspecialchars($student_name) . '</strong>.</p>
<p><strong>Application Number:</strong> ' . htmlspecialchars($app_number) . '</p>
<p>Please save this number. You can check your application status at any time at:<br>
<a href="' . SITE_URL . '/enroll-status.php">' . SITE_URL . '/enroll-status.php</a></p>
<p>Our admissions team will review your application and contact you if needed.</p>
<p>Blessings,<br>' . SITE_NAME . '</p>
</body></html>';
sendMail($parent_email, $subject, $body);

$_SESSION['enroll_csrf'] = bin2hex(random_bytes(16));
header('Location: ' . SITE_URL . '/enroll.php?success=1&app=' . urlencode($app_number));
exit;
