<?php
/**
 * One-time setup: create database and tables.
 * Run once via: http://localhost/ShiningLight/install/
 * Then delete or protect the install folder.
 */
header('Content-Type: text/html; charset=utf-8');
$step = $_GET['step'] ?? '1';
$msg = '';
$ok = false;

if ($step === '2' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $host = trim($_POST['host'] ?? 'localhost');
    $user = trim($_POST['user'] ?? 'root');
    $pass = $_POST['pass'] ?? '';
    $name = trim($_POST['name'] ?? 'shining_light_db');
    try {
        $pdo = new PDO("mysql:host=$host;charset=utf8mb4", $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
        $pdo->exec("CREATE DATABASE IF NOT EXISTS `$name` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        $pdo->exec("USE `$name`");
        $sql = file_get_contents(__DIR__ . '/setup.sql');
        $sql = preg_replace('/^CREATE\s+DATABASE.*?;/ims', '', $sql);
        $sql = preg_replace('/^USE\s+\S+.*?;/im', '', $sql);
        $stmts = array_filter(array_map('trim', explode(';', $sql)));
        foreach ($stmts as $st) {
            if ($st !== '') $pdo->exec($st . ';');
        }
        $msg = 'Database and tables created successfully.';
        $ok = true;
        $upload_dirs = [__DIR__ . '/../uploads', __DIR__ . '/../uploads/enrollments', __DIR__ . '/../uploads/news', __DIR__ . '/../uploads/faculty', __DIR__ . '/../uploads/gallery'];
        foreach ($upload_dirs as $d) {
            if (!is_dir($d)) mkdir($d, 0755, true);
        }
        $msg .= ' Upload folders created. Default admin: username <strong>admin</strong>, password <strong>admin123</strong>. Change this after first login.';
    } catch (Exception $e) {
        $msg = 'Error: ' . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setup | Shining Light</title>
    <style>body{font-family:sans-serif;max-width:500px;margin:3rem auto;padding:1rem;} input,button{width:100%;padding:0.5rem;margin:0.25rem 0;} .ok{color:green;} .err{color:red;}</style>
</head>
<body>
    <h1>Shining Light – Install</h1>
    <?php if ($msg): ?>
    <p class="<?php echo $ok ? 'ok' : 'err'; ?>"><?php echo $msg; ?></p>
    <?php if ($ok): ?><p><a href="../">Go to site</a> | <a href="../admin/">Admin</a></p><?php endif; ?>
    <?php elseif ($step === '1'): ?>
    <p>This will create the database and tables. Ensure MySQL is running (e.g. XAMPP).</p>
    <form method="post" action="?step=2">
        <label>MySQL Host</label>
        <input type="text" name="host" value="localhost" required>
        <label>MySQL User</label>
        <input type="text" name="user" value="root">
        <label>MySQL Password</label>
        <input type="password" name="pass">
        <label>Database Name</label>
        <input type="text" name="name" value="shining_light_db" required>
        <button type="submit">Create Database &amp; Tables</button>
    </form>
    <?php endif; ?>
</body>
</html>
