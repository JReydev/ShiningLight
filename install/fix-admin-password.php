<?php
/**
 * One-time fix: set admin password to "admin123" if login fails.
 * Run once: http://localhost/ShiningLight/install/fix-admin-password.php
 * Then delete this file or remove from public access.
 */
require_once __DIR__ . '/../config/database.php';
$new_hash = password_hash('admin123', PASSWORD_DEFAULT);
try {
    $pdo = getDB();
    $stmt = $pdo->prepare("UPDATE admins SET password_hash = ? WHERE username = 'admin'");
    $stmt->execute([$new_hash]);
    $n = $stmt->rowCount();
    echo $n ? '<p style="color:green;">Password updated. You can now log in with username <strong>admin</strong> and password <strong>admin123</strong>. Delete this file (fix-admin-password.php) when done.</p>' : '<p>No admin user found.</p>';
} catch (Exception $e) {
    echo '<p style="color:red;">Error: ' . htmlspecialchars($e->getMessage()) . '</p>';
}
echo '<p><a href="../admin/login.php">Go to Admin Login</a></p>';
