<?php
require_once __DIR__ . '/session_bootstrap.php';

function require_reauth($reason = 'confirm') {
    $now = time();
    if (empty($_SESSION['user_id'])) {
        header('Location: /2fa/public/login.php');
        exit;
    }

    if (empty($_SESSION['last_auth_time']) || ($now - $_SESSION['last_auth_time']) > REAUTH_WINDOW) {
        $_SESSION['post_reauth_target'] = $_SERVER['REQUEST_URI'];
        header('Location: /2fa/public/reauth.php?reason=' . urlencode($reason));
        exit;
    }
}
?>
