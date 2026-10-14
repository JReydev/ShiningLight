<?php
/**
 * Shining Light Christian College - Database Configuration
 */
define('DB_HOST', 'localhost');
define('DB_NAME', 'shining_light_db');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

define('SITE_URL', 'http://localhost/ShiningLight');
define('SITE_NAME', 'Shining Light Christian College Inc.');
define('ADMIN_EMAIL', 'slcc.educ@gmail.com');
define('FACEBOOK_URL', 'https://www.facebook.com/slcc.eagles');
define('DEFAULT_ADDRESS', 'Rizal Ave. Ext. Hiway Batingan, Binangonan, Philippines, 1940');
define('DEFAULT_PHONE', '0997 619 9710');
define('UPLOAD_PATH', __DIR__ . '/../uploads/');
define('MAX_UPLOAD_MB', 5);
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
