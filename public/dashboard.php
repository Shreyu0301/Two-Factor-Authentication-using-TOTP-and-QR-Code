<?php
require_once __DIR__ . '/../includes/session_bootstrap.php';
if (empty($_SESSION['user_id'])) {
    header('Location: /2fa/public/login.php');
    exit;
}
?>
<!doctype html>
<html><head><meta charset="utf-8"><title>Dashboard</title><link rel="stylesheet" href="styles.css"></head>
<body>
  <?php if (!empty($_SESSION['login_success'])): ?>
  <div class="banner banner-success">
    <span class="icon">✔️</span>
    <div><?=htmlspecialchars($_SESSION['login_success'])?></div>
  </div>
  <?php unset($_SESSION['login_success']); ?>
<?php endif; ?>

  <div class="container">
    <div class="login-box">
      <h1>Welcome — you are authenticated</h1>
      <p><a href="logout.php">Logout</a></p>
    </div>
  </div>
</body></html>
