<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
if (isLoggedIn()) {
    header('Location: ' . SITE_URL . '/admin/');
    exit;
}
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = trim($_POST['username'] ?? '');
    $pass = $_POST['password'] ?? '';
    if ($user === '' || $pass === '') {
        $error = 'Please enter username and password.';
    } else {
        try {
            $pdo = getDB();
            $stmt = $pdo->prepare("SELECT id, username, password_hash, role, full_name FROM admins WHERE username = ?");
            $stmt->execute([$user]);
            $admin = $stmt->fetch();
            if ($admin && password_verify($pass, $admin['password_hash'])) {
                loginUser(['id' => $admin['id'], 'username' => $admin['username'], 'role' => $admin['role'], 'full_name' => $admin['full_name']]);
                header('Location: ' . SITE_URL . '/admin/');
                exit;
            }
        } catch (Exception $e) {}
        $error = 'Invalid username or password.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | <?php echo e(SITE_NAME); ?></title>
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/style.css">
    <style>.login-wrap{max-width:360px;margin:4rem auto;padding:2rem;background:var(--white);border-radius:var(--radius-lg);box-shadow:var(--shadow);}</style>
</head>
<body>
    <div class="login-wrap">
        <h1 style="text-align:center; color:var(--green);">Admin Login</h1>
        <p style="text-align:center; color:var(--gray);"><?php echo e(SITE_NAME); ?></p>
        <?php if ($error): ?><div class="alert alert-error"><?php echo e($error); ?></div><?php endif; ?>
        <form method="post">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" class="form-control" required autofocus>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Log In</button>
        </form>
    </div>
</body>
</html>
