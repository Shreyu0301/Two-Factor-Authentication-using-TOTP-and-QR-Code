<?php
// public/register.php
session_start();
$error = $_GET['error'] ?? '';
$notice = $_GET['notice'] ?? '';
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Create Account</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <button class="theme-toggle" id="themeToggle" aria-label="Toggle theme">🌓</button>
  <div class="container">
    <div class="login-box">
      <h1>Create Account</h1>
      <?php if ($error): ?>
        <div class="banner banner-error"><span class="icon">❌</span><div><?=htmlspecialchars($error)?></div></div>
      <?php endif; ?>
      <?php if ($notice): ?>
        <div class="banner banner-success"><span class="icon">✔️</span><div><?=htmlspecialchars($notice)?></div></div>
      <?php endif; ?>
      <form action="register_submit.php" method="post" autocomplete="off">
        <div class="input-group">
          <label for="full_name">Full Name</label>
          <input type="text" id="full_name" name="full_name" required>
        </div>
        <div class="input-group">
          <label for="username">Username</label>
          <input type="text" id="username" name="username" required>
        </div>
        <div class="input-group">
          <label for="email">Email</label>
          <input type="email" id="email" name="email" required>
        </div>
        <div class="input-group">
          <label for="phone">Phone Number</label>
          <input type="tel" id="phone" name="phone" placeholder="+1 555 555 5555" required>
        </div>
        <div class="input-group">
          <label for="password">Password</label>
          <input type="password" id="password" name="password" required>
        </div>
        <button type="submit" class="btn">Create Account</button>
      </form>
      <p style="margin-top:12px;">Already have an account? <a href="index.php">Login</a></p>
    </div>
  </div>
  <script src="theme.js"></script>
</body>
</html>
