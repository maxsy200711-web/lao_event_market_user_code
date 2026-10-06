<?php
$error = $_GET['error'] ?? '';
$selectedRole = $_GET['role'] ?? 'user';
if (!in_array($selectedRole, ['user', 'organizer'], true)) {
    $selectedRole = 'user';
}
?>
<!doctype html>
<html lang="lo">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register - LAOeventMarket</title>
    <link rel="stylesheet" href="assets/auth.css">
</head>
<body>
<main class="auth-card">
    <a class="auth-brand" href="index.php" aria-label="LAOeventMarket home">
        <?php include __DIR__ . '/site_logo.php'; ?>
    </a>
    <p class="auth-title">Create a new account</p>

    <?php if ($error === 'exists'): ?><p class="auth-message auth-error">Username, email, or phone number is already registered.</p>
    <?php elseif ($error === 'mail'): ?><p class="auth-message auth-error">We could not send the verification email. Check SMTP settings.</p>
    <?php elseif ($error === 'invalid'): ?><p class="auth-message auth-error">Please complete all required fields correctly.</p>
    <?php elseif ($error === 'organizer_exists'): ?><p class="auth-message auth-error">This organizer email or phone is already registered.</p>
    <?php elseif ($error === 'use_registration_form'): ?><p class="auth-message auth-error">Please register using this form so your email can be verified and Organizer accounts can be reviewed by Admin.</p>
    <?php endif; ?>

    <form class="auth-form" action="register_start.php" method="post">
        <label for="registerRole">Register as *</label>
        <select name="role" id="registerRole" required>
            <option value="user" <?= $selectedRole === 'user' ? 'selected' : '' ?>>Normal User</option>
            <option value="organizer" <?= $selectedRole === 'organizer' ? 'selected' : '' ?>>Organizer</option>
        </select>

        <label for="regUsername">Username *</label>
        <input id="regUsername" name="username" required maxlength="100" autocomplete="username">

        <div id="organizerFields" class="organizer-fields <?= $selectedRole === 'organizer' ? 'show' : '' ?>">
            <label for="organizerName">Organizer name *</label>
            <input id="organizerName" name="organizer_name" maxlength="100" <?= $selectedRole === 'organizer' ? 'required' : '' ?> value="<?= htmlspecialchars($_GET['organizer_name'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
            <label for="companyName">Company name *</label>
            <input id="companyName" name="company_name" maxlength="150" <?= $selectedRole === 'organizer' ? 'required' : '' ?> value="<?= htmlspecialchars($_GET['company_name'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
            <p class="auth-hint">After email verification, Admin must approve your Organizer account before you can log in.</p>
        </div>

        <label for="regEmail">Email *</label>
        <input id="regEmail" type="email" name="email" required autocomplete="email">
        <label for="regPhone">Phone number *</label>
        <input id="regPhone" type="tel" name="phone" pattern="[0-9+ ]{8,20}" required autocomplete="tel">
        <button class="auth-submit" type="submit">Register and send OTP</button>
    </form>
    <p class="auth-switch">Already have an account? <a href="login.php">Login</a></p>
    <div class="auth-home"><a href="index.php">Back to home</a></div>
</main>
<script>
const role = document.getElementById('registerRole');
const fields = document.getElementById('organizerFields');
const organizerInputs = fields.querySelectorAll('input');
function updateOrganizerFields() {
    const isOrganizer = role.value === 'organizer';
    fields.classList.toggle('show', isOrganizer);
    organizerInputs.forEach(input => {
        input.required = isOrganizer;
        input.disabled = !isOrganizer;
    });
}
role.addEventListener('change', updateOrganizerFields);
updateOrganizerFields();
</script>
<script src="assets/site-i18n.js"></script>
    </body>
</html>
