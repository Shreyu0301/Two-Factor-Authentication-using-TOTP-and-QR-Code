<?php
// public/index.php
session_start();
if (!empty($_SESSION['authenticated'])) {
    header('Location: dashboard.php');
    exit;
}
$error = $_GET['error'] ?? '';
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Login - Secure App</title>
<link rel="stylesheet" href="styles.css">
</head>
<body>
  <div class="container">
    <div class="login-box">
      <h1>Secure App</h1>
      <?php if ($error): ?>
  <div class="banner banner-error">
    <span class="icon">❌</span>
    <div><?=htmlspecialchars($error)?></div>
  </div>
<?php endif; ?>

      <form action="authenticate.php" method="post" autocomplete="off">
        <div class="input-group">
          <label for="username">Email</label>
          <input type="text" id="username" name="username" required>
        </div>
        <div class="input-group">
          <label for="password">Password</label>
          <input type="password" id="password" name="password" required>
        </div>
        <button type="submit" class="btn">Login</button>
      </form>
    </div>
  </div>
</body>
</html>
