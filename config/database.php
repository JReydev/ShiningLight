<?php
/**
 * Shining Light Christian College - Database Configuration
 */
define('DB_HOST', 'localhost');
define('DB_NAME', 'shining_light_db');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

$siteUrl = trim((string) getenv('SHINING_LIGHT_SITE_URL'));
if ($siteUrl === '' && isset($_SERVER['HTTP_HOST'])) {
    $host = (string) $_SERVER['HTTP_HOST'];
    if (preg_match('/\A(?:[A-Za-z0-9.-]+|\[[0-9A-Fa-f:.]+\])(?::\d{1,5})?\z/', $host)) {
        $scheme = (!empty($_SERVER['HTTPS']) && strtolower((string) $_SERVER['HTTPS']) !== 'off')
            ? 'https'
            : 'http';
        $scriptDirectory = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/'));
        if (in_array(basename($scriptDirectory), ['admin', 'install', 'student', 'teacher'], true)) {
            $scriptDirectory = dirname($scriptDirectory);
        }
        $siteUrl = $scheme . '://' . $host . rtrim($scriptDirectory, '/');
    }
}
if ($siteUrl === '') {
    $siteUrl = 'http://localhost/ShiningLight';
}
define('SITE_URL', rtrim($siteUrl, '/'));
define('SITE_NAME', 'Shining Light Christian College Inc.');
define('ADMIN_EMAIL', 'slcc.educ@gmail.com');
define('FACEBOOK_URL', 'https://www.facebook.com/slcc.eagles');
define('DEFAULT_ADDRESS', 'Rizal Ave. Ext. Hiway Batingan, Binangonan, Philippines, 1940');
define('DEFAULT_PHONE', '0997 619 9710');
define('UPLOAD_PATH', __DIR__ . '/../uploads/');
define('MAX_UPLOAD_MB', 5);
define('GOOGLE_SHEET_ID', '12CG5KPg_goo33W_miH25_wBU1YagWLBej_xXgzQHOmk');

$pdo = null;
function getDB() {
    global $pdo;
    if ($pdo === null) {
        $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;
        $pdo = new PDO($dsn, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
    }
    return $pdo;
}
