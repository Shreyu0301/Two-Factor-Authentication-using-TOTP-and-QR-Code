<?php
require_once __DIR__ . '/../includes/require_reauth.php';
require_reauth('update_account');
?>
<?php
require_once __DIR__ . '/../includes/session_bootstrap.php';
require_once __DIR__ . '/../src/db.php'; // ✅ your existing DB file

$reason = $_GET['reason'] ?? 'confirm';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['password'] ?? '';
    $userId = $_SESSION['user_id'] ?? null;

    if (!$userId) {
        header('Location: /2fa/public/login.php');
        exit;
    }

    $stmt = $pdo->prepare("SELECT password FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['last_auth_time'] = time();
        $target = $_SESSION['post_reauth_target'] ?? '/2fa/public/dashboard.php';
        unset($_SESSION['post_reauth_target']);
        header('Location: ' . $target);
        exit;
    } else {
        $error = "Invalid password.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Confirm Identity</title>
</head>
<body>
  <h2>Please re-enter your password to continue</h2>
  <?php if ($error) echo "<p style='color:red;'>$error</p>"; ?>
  <form method="POST">
    <label>Password:</label><br>
    <input type="password" name="password" required>
    <br><br>
    <button type="submit">Confirm</button>
  </form>
</body>
</html>
