<?php
session_start();
require_once __DIR__ . '/config.php';

if (empty($_SESSION['user_id'])) { header('Location: login.php'); exit; }
$userId = (int)$_SESSION['user_id'];
$message = '';
$error = '';

$stmt = $pdo->prepare('SELECT user_id, full_name, email, phone, role_id FROM users WHERE user_id = ?');
$stmt->execute([$userId]);
$user = $stmt->fetch();
if (!$user) { header('Location: logout.php'); exit; }
if (($user['role_id'] ?? 'USER') === 'ORGANIZER') { header('Location: organizer_dashboard.php'); exit; }

$stmt = $pdo->prepare("SELECT status FROM organizer_requests WHERE user_id = ? ORDER BY request_id DESC LIMIT 1");
$stmt->execute([$userId]);
$request = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && (!$request || $request['status'] !== 'pending')) {
    $organizerName = trim($_POST['organizer_name'] ?? '');
    $companyName = trim($_POST['company_name'] ?? '');
    $phone = trim($_POST['phone'] ?? $user['phone'] ?? '');
    if ($organizerName === '' || !preg_match('/^[0-9+ ]{8,20}$/', $phone)) {
        $error = 'Please enter an organizer name and a valid phone number.';
    } else {
        $stmt = $pdo->prepare("INSERT INTO organizer_requests (user_id, organizer_name, company_name, phone, status) VALUES (?, ?, ?, ?, 'pending')");
        $stmt->execute([$userId, $organizerName, $companyName, $phone]);
        $request = ['status' => 'pending'];
        $message = 'Your request has been sent to the administrator.';
    }
}
?><!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Become an Organizer</title><style>body{font-family:Arial;display:grid;place-items:center;min-height:100vh;background:#f1f5f9}.card{background:#fff;padding:30px;border-radius:14px;width:min(430px,calc(100% - 40px));box-shadow:0 10px 25px #0001}label{display:block;margin:14px 0 6px}input,textarea,button{width:100%;box-sizing:border-box;padding:12px;border:1px solid #dbe3ea;border-radius:7px}textarea{min-height:80px}button{margin-top:18px;background:#10b981;color:#fff;border:0;font-weight:bold}.message{color:#047857}.error{color:#b91c1c}a{color:#059669}</style></head><body><div class="card"><h2>Become an Organizer</h2><p>Account: <?= htmlspecialchars($user['email']) ?></p><?php if($message): ?><p class="message"><?= htmlspecialchars($message) ?></p><?php endif; ?><?php if($error): ?><p class="error"><?= htmlspecialchars($error) ?></p><?php endif; ?><?php if($request && $request['status'] === 'pending'): ?><p>Your request is pending admin approval.</p><?php elseif($request && $request['status'] === 'rejected'): ?><p class="error">Your previous request was rejected. You may submit again.</p><form method="post"><label>Organizer name</label><input name="organizer_name" required><label>Company name</label><input name="company_name"><label>Phone number</label><input name="phone" value="<?= htmlspecialchars($user['phone'] ?? '') ?>" required><button>Send request</button></form><?php else: ?><form method="post"><label>Organizer name</label><input name="organizer_name" required><label>Company name</label><input name="company_name"><label>Phone number</label><input name="phone" value="<?= htmlspecialchars($user['phone'] ?? '') ?>" required><button>Send request</button></form><?php endif; ?><p><a href="user_profile.php">Back to profile</a></p></div></body></html>
