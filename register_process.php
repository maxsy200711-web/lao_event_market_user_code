<?php
// Legacy registration endpoint disabled: all sign-ups must go through the
// OTP + Organizer approval flow in register_start.php / set_password.php.
$role = strtolower(trim((string)($_POST['role'] ?? 'user')));
if (!in_array($role, ['user', 'organizer'], true)) $role = 'user';
header('Location: register.php?error=use_registration_form&role=' . rawurlencode($role));
exit;
