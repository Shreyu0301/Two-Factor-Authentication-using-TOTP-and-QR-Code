<?php
// ======================
// session_bootstrap.php
// ======================

// SETTINGS
define('INACTIVITY_TIMEOUT', 30);  // 30 seconds for testing

define('ABSOLUTE_TIMEOUT', 60); // 1 minute for testing

define('REAUTH_WINDOW', 300);       // 5 minutes

// Secure session cookies
$cookieParams = session_get_cookie_params();
session_set_cookie_params([
    'lifetime' => 0,
    'path' => $cookieParams['path'],
    'domain' => $cookieParams['domain'],
    'secure' => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
    'httponly' => true,
    'samesite' => 'Lax'
]);

session_start();

// Regenerate session to prevent fixation
function session_strong_regenerate($force = false) {
    if ($force || !isset($_SESSION['last_regen']) || $_SESSION['last_regen'] < time() - 300) {
        session_regenerate_id(true);
        $_SESSION['last_regen'] = time();
    }
}

// If user logged in
if (!empty($_SESSION['user_id'])) {
    $now = time();

    // 1️⃣ Inactivity timeout
    if (isset($_SESSION['last_activity']) && ($now - $_SESSION['last_activity']) > INACTIVITY_TIMEOUT) {
        session_unset();
        session_destroy();
        header('Location: /2fa/public/login.php?timeout=inactive');
        exit;
    }

    // 2️⃣ Absolute timeout
    if (isset($_SESSION['session_started']) && ($now - $_SESSION['session_started']) > ABSOLUTE_TIMEOUT) {
        session_unset();
        session_destroy();
        header('Location: /2fa/public/login.php?timeout=expired');
        exit;
    }

    // Update activity time
    $_SESSION['last_activity'] = $now;
}
?>
