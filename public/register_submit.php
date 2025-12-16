<?php
// public/register_submit.php
session_start();
require __DIR__ . '/../src/db.php';

function redirect_with($path, $params) {
    $q = http_build_query($params);
    header('Location: ' . $path . ($q ? ('?' . $q) : ''));
    exit;
}

$fullName = trim($_POST['full_name'] ?? '');
$username = trim($_POST['username'] ?? '');
$email    = trim($_POST['email'] ?? '');
$phone    = trim($_POST['phone'] ?? '');
$password = $_POST['password'] ?? '';

if (!$fullName || !$username || !$email || !$phone || !$password) {
    redirect_with('register.php', ['error' => 'All fields are required.']);
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    redirect_with('register.php', ['error' => 'Invalid email address.']);
}

if (strlen($password) < 8) {
    redirect_with('register.php', ['error' => 'Password must be at least 8 characters.']);
}

try {
    // ensure uniqueness
    $check = $pdo->prepare('SELECT 1 FROM users WHERE email = ? OR username = ?');
    $check->execute([$email, $username]);
    if ($check->fetch()) {
        redirect_with('register.php', ['error' => 'Email or username already in use.']);
    }

    $hash = password_hash($password, PASSWORD_DEFAULT);

    // create verification code (6 digits) valid for 10 minutes
    $code = str_pad((string)random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    $expiresAt = (new DateTime('+10 minutes'))->format('Y-m-d H:i:s');

    $stmt = $pdo->prepare('INSERT INTO users (email, password_hash, username, full_name, phone, phone_verified, verification_code, verification_expires_at) VALUES (?,?,?,?,?,0,?,?)');
    $stmt->execute([$email, $hash, $username, $fullName, $phone, $code, $expiresAt]);

    $_SESSION['pending_phone_user_id'] = (int)$pdo->lastInsertId();

    // In production, send $code via SMS (Twilio, etc.). For now, we will show it on screen in verify page for testing.
    redirect_with('verify_phone.php', ['devcode' => $code]);
} catch (Exception $e) {
    redirect_with('register.php', ['error' => 'Registration failed.']);
}
