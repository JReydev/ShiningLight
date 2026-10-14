<?php
/**
 * Authentication & role-based access
 */
session_start();

function isLoggedIn() {
    return isset($_SESSION['admin_id']) && !empty($_SESSION['admin_id']);
}

function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: ' . SITE_URL . '/admin/login.php');
        exit;
    }
}

function requireRole($allowed_roles) {
    requireLogin();
    if (!in_array($_SESSION['admin_role'], (array) $allowed_roles)) {
        header('Location: ' . SITE_URL . '/admin/index.php');
        exit;
    }
}

function currentAdmin() {
    return [
        'id' => $_SESSION['admin_id'] ?? null,
        'username' => $_SESSION['admin_username'] ?? null,
        'role' => $_SESSION['admin_role'] ?? null,
        'full_name' => $_SESSION['admin_full_name'] ?? null,
    ];
}

function loginUser($admin) {
    $_SESSION['admin_id'] = $admin['id'];
    $_SESSION['admin_username'] = $admin['username'];
    $_SESSION['admin_role'] = $admin['role'];
    $_SESSION['admin_full_name'] = $admin['full_name'];
}

function logoutUser() {
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $p = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
    }
    session_destroy();
}
