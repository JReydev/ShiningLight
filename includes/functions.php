<?php
/**
 * Helper functions
 */
function e($s) {
    return htmlspecialchars((string) $s, ENT_QUOTES, 'UTF-8');
}

function generateApplicationNumber() {
    return 'SL' . date('Y') . '-' . strtoupper(substr(uniqid(), -6));
}

function slugify($text) {
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
    $text = preg_replace('~[^-\w]+~', '', $text);
    $text = trim($text, '-');
    $text = strtolower($text);
    return $text ?: 'n-a';
}

function getSetting($key, $default = '') {
    try {
        $pdo = getDB();
        $stmt = $pdo->prepare("SELECT setting_value FROM site_settings WHERE setting_key = ?");
        $stmt->execute([$key]);
        $row = $stmt->fetch();
        return $row ? $row['setting_value'] : $default;
    } catch (Exception $e) {
        return $default;
    }
}

function getBibleVerse() {
    try {
        $pdo = getDB();
        $stmt = $pdo->query("SELECT verse_text, reference FROM bible_verses WHERE is_active = 1 ORDER BY sort_order, RAND() LIMIT 1");
        return $stmt->fetch();
    } catch (Exception $e) {
        return ['verse_text' => 'Let your light so shine before men.', 'reference' => 'Matthew 5:16'];
    }
}

function safeUpload($file, $subdir = '') {
    $allowed = ['pdf', 'jpg', 'jpeg', 'png', 'gif'];
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, $allowed)) return [false, 'Invalid file type.'];
    if ($file['size'] > MAX_UPLOAD_MB * 1024 * 1024) return [false, 'File too large.'];
    $dir = UPLOAD_PATH . $subdir;
    if (!is_dir($dir)) mkdir($dir, 0755, true);
    $name = date('Ymd_His') . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
    $path = $dir . $name;
    if (move_uploaded_file($file['tmp_name'], $path)) return [true, $subdir ? $subdir . '/' . $name : $name];
    return [false, 'Upload failed.'];
}

function sendMail($to, $subject, $bodyHtml, $bodyText = '') {
    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    $headers .= "From: " . SITE_NAME . " <" . ADMIN_EMAIL . ">\r\n";
    return @mail($to, $subject, $bodyHtml, $headers);
}
