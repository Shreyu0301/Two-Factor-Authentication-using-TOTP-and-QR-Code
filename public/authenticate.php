<?php
// public/authenticate.php
session_start();
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../src/db.php';

$email = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

$stmt = $pdo->prepare('SELECT id, password_hash, twofa_enabled FROM users WHERE email = ?');
$stmt->execute([$email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user || !password_verify($password, $user['password_hash'])) {
    header('Location: index.php?error=' . urlencode('Invalid username or password'));
    exit;
}

$_SESSION['user_id'] = $user['id'];

if ($user['twofa_enabled']) {
    header('Location: verify.php');
    exit;
} else {
    header('Location: setup_2fa.php');
    exit;
}
