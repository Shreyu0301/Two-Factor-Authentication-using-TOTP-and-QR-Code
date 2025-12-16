<?php
require_once __DIR__ . '/../includes/session_bootstrap.php';
if (empty($_SESSION['user_id'])) {
    header('Location: /2fa/public/login.php');
    exit;
}
?>
<?php
// public/setup_2fa.php
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
$stmt = $pdo->prepare('SELECT email, twofa_secret, twofa_enabled FROM users WHERE id = ?');
$stmt->execute([$uid]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) { header('Location: index.php'); exit; }
if ($user['twofa_enabled']) { header('Location: verify.php'); exit; }

$ga = new GoogleAuthenticator();

$secret = $user['twofa_secret'] ?? null;
if (!$secret) {
    $secret = $ga->generateSecret();
    $upd = $pdo->prepare('UPDATE users SET twofa_secret = ? WHERE id = ?');
    $upd->execute([$secret, $uid]);
}

$qrUrl = GoogleQrUrl::generate($user['email'], $secret, 'MyApp');
$error = $_GET['error'] ?? '';
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Setup 2FA</title><link rel="stylesheet" href="styles.css"></head>
<body>
  <div class="container">
    <div class="login-box">
      <h1>Two-Factor Setup</h1>
      <?php if ($error): ?><p class="error"><?=htmlspecialchars($error)?></p><?php endif; ?>
      <p>Scan this QR in Google Authenticator / Authy, or enter the secret manually.</p>
      <img src="<?=htmlspecialchars($qrUrl)?>" alt="QR Code" class="qr-code">
      <p><strong>Secret:</strong> <?=htmlspecialchars($secret)?></p>

      <form action="enable_2fa.php" method="post">
        <div class="input-group">
          <label for="code">Enter current code from the app to enable 2FA</label>
          <input id="code" name="code" required placeholder="123456">
        </div>
        <button class="btn" type="submit">Verify & Enable 2FA</button>
      </form>
    </div>
  </div>
</body>
</html>
