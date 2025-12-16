<?php
// public/verify.php
session_start();
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../src/db.php';

use Sonata\GoogleAuthenticator\GoogleAuthenticator;
use Sonata\GoogleAuthenticator\GoogleQrUrl;

if (empty($_SESSION['user_id'])) { 
    header('Location: index.php'); 
    exit; 
}

$uid = $_SESSION['user_id'];

// fetch user
$stmt = $pdo->prepare('SELECT email, twofa_secret FROM users WHERE id = ?');
$stmt->execute([$uid]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$ga = new GoogleAuthenticator();
$error = '';

if (empty($user['twofa_secret'])) {
    // Generate a new secret if none
    $newSecret = $ga->generateSecret();
    $stmt = $pdo->prepare('UPDATE users SET twofa_secret=? WHERE id=?');
    $stmt->execute([$newSecret, $uid]);
    $user['twofa_secret'] = $newSecret;
}

// if form posted, verify code
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $code = trim($_POST['code'] ?? '');
    if ($ga->checkCode($user['twofa_secret'], $code)) {
        session_regenerate_id(true);
        $_SESSION['authenticated'] = true;
        $_SESSION['login_success'] = 'Login successful ✅';
        header('Location: dashboard.php');
        exit;
    } else {
        $error = 'Invalid code ❌';
    }
}

// generate QR code URL every time secret created
$qrCodeUrl = GoogleQrUrl::generate($user['email'], $user['twofa_secret'], 'My2FA-App');
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Verify</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <div class="container">
    <div class="login-box">
      <h1>Two-Factor Authentication</h1>
      
      <?php if ($error): ?>
        <div class="banner banner-error">
          ❌ <?=htmlspecialchars($error)?>
        </div>
      <?php endif; ?>

      <!-- Show QR code image -->
      <p>Scan this QR code with Google Authenticator:</p>
      <img src="<?=htmlspecialchars($qrCodeUrl)?>" alt="QR Code">
      <p>Then enter the 6-digit code below:</p>

      <form method="post">
        <div class="input-group">
          <label for="code">Code</label>
          <input id="code" name="code" required>
        </div>
        <button class="btn">Verify</button>
      </form>
    </div>
  </div>
</body>
</html>
