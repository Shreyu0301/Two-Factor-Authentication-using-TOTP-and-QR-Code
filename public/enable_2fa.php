<?php
// public/enable_2fa.php
session_start();
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../src/db.php';
use Sonata\GoogleAuthenticator\GoogleAuthenticator;

if (empty($_SESSION['user_id'])) { header('Location: index.php'); exit; }

$uid = $_SESSION['user_id'];
$stmt = $pdo->prepare('SELECT twofa_secret FROM users WHERE id = ?');
$stmt->execute([$uid]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$user) { header('Location: index.php'); exit; }

$code = trim($_POST['code'] ?? '');
$ga = new GoogleAuthenticator();

if ($ga->checkCode($user['twofa_secret'], $code)) {
    $pdo->prepare('UPDATE users SET twofa_enabled = 1 WHERE id = ?')->execute([$uid]);
    session_regenerate_id(true);
    $_SESSION['authenticated'] = true;
    header('Location: dashboard.php');
    exit;
} else {
    header('Location: setup_2fa.php?error=' . urlencode('Invalid code, try again.'));
    exit;
}
