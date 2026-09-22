<?php
session_start(); require_once __DIR__ . '/config.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: login.php'); exit; }
$identifier = trim($_POST['username'] ?? ''); $password = $_POST['password'] ?? '';
$stmt = $pdo->prepare('SELECT * FROM users WHERE email = ? OR full_name = ? LIMIT 1');
$stmt->execute([$identifier, $identifier]); $user = $stmt->fetch();
if (!$user || empty($user['password']) || !password_verify($password, $user['password'])) { header('Location: login.php?error=1'); exit; }
session_regenerate_id(true);
$_SESSION['user_id'] = $user['user_id'] ?? $user['id'];
$_SESSION['user_email'] = $user['email']; $_SESSION['full_name'] = $user['full_name']; $_SESSION['role'] = 'user';
header('Location: index.php'); exit;
