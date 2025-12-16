<?php
// public/verify_phone.php
session_start();
require __DIR__ . '/../src/db.php';

if (empty($_SESSION['pending_phone_user_id'])) {
    header('Location: index.php');
    exit;
}

$userId = (int)$_SESSION['pending_phone_user_id'];
$devCode = $_GET['devcode'] ?? '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $code = trim($_POST['code'] ?? '');
    $stmt = $pdo->prepare('SELECT verification_code, verification_expires_at FROM users WHERE id = ?');
    $stmt->execute([$userId]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        $error = 'User not found.';
    } else {
        $now = new DateTime();
        $exp = $row['verification_expires_at'] ? new DateTime($row['verification_expires_at']) : null;
        if (!$code || $code !== $row['verification_code']) {
            $error = 'Invalid code.';
        } elseif ($exp && $now > $exp) {
            $error = 'Code expired.';
        } else {
            // verify phone
            $pdo->prepare('UPDATE users SET phone_verified = 1, verification_code = NULL, verification_expires_at = NULL WHERE id = ?')->execute([$userId]);
            // proceed to 2FA setup or login
            $_SESSION['user_id'] = $userId;
            header('Location: setup_2fa.php');
            exit;
        }
    }
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Verify Phone</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <button class="theme-toggle" id="themeToggle" aria-label="Toggle theme">🌓</button>
  <div class="container">
    <div class="login-box">
      <h1>Verify Phone</h1>
      <?php if ($error): ?>
        <div class="banner banner-error"><span class="icon">❌</span><div><?=htmlspecialchars($error)?></div></div>
      <?php endif; ?>
      <?php if ($devCode): ?>
        <div class="banner banner-success"><span class="icon">ℹ️</span><div>Dev only: your code is <strong><?=htmlspecialchars($devCode)?></strong></div></div>
      <?php endif; ?>
      <form method="post" autocomplete="off">
        <div class="input-group">
          <label for="code">Enter the 6-digit code sent to your phone</label>
          <input id="code" name="code" required pattern="\\d{6}" placeholder="123456">
        </div>
        <button class="btn" type="submit">Verify</button>
      </form>
    </div>
  </div>
  <script src="theme.js"></script>
</body>
</html>
