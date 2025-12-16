require_once __DIR__ . '/../includes/session_bootstrap.php';

$_SESSION['user_id'] = $userId; // your variable from login check
$_SESSION['session_started'] = time();
$_SESSION['last_activity'] = time();
$_SESSION['last_auth_time'] = time();
session_regenerate_id(true);

header('Location: /2fa/public/dashboard.php');
exit;
