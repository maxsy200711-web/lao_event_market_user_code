<?php
$error = $_GET['error'] ?? '';
$selectedRole = $_GET['role'] ?? 'user';
if (!in_array($selectedRole, ['user', 'organizer'], true)) {
    $selectedRole = 'user';
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register - LAOeventMarket</title>
    <style>
        body{font-family:Arial;display:grid;place-items:center;min-height:100vh;margin:0;background:linear-gradient(#0f172acc,#0f172acc),url('https://media02.stockfood.com/largepreviews/MjIxMDQzODg0OQ==/71304479-Night-market-in-Vientiane-on-the-banks-of-the-Mekong-Laos.jpg') center/cover}
        .card{background:#fff;padding:32px;border-radius:16px;width:min(420px,calc(100% - 56px));box-shadow:0 20px 30px #0005}
        h1{text-align:center;color:#10b981}p{text-align:center;color:#64748b}
        label{display:block;margin:14px 0 6px;font-size:14px}input,select,button{width:100%;height:44px;box-sizing:border-box;padding:0 12px;border:1px solid #dbe3ea;border-radius:8px}
        button{margin-top:18px;background:#10b981;color:white;border:0;font-weight:bold;cursor:pointer}.error{color:#b91c1c;background:#fee2e2;padding:10px;border-radius:7px}
        a{color:#059669}.organizer-fields{display:none}.organizer-fields.show{display:block}.hint{font-size:12px;margin:7px 0;text-align:left;color:#64748b}
    </style>
</head>
<body>
<div class="card">
    <h1>LAOeventMarket</h1>
    <p>Create a new account</p>

    <?php if ($error === 'exists'): ?><p class="error">Username, email, or phone number is already registered.</p>
    <?php elseif ($error === 'mail'): ?><p class="error">We could not send the verification email. Check SMTP settings.</p>
    <?php elseif ($error === 'invalid'): ?><p class="error">Please complete all required fields correctly.</p>
    <?php elseif ($error === 'organizer_exists'): ?><p class="error">This organizer email or phone is already registered.</p>
    <?php endif; ?>

    <form action="register_start.php" method="post">
        <label>Register as *</label>
        <select name="role" id="registerRole" required>
            <option value="user" <?= $selectedRole === 'user' ? 'selected' : '' ?>>Normal User</option>
            <option value="organizer" <?= $selectedRole === 'organizer' ? 'selected' : '' ?>>Organizer</option>
        </select>

        <label>Username *</label>
        <input name="username" required maxlength="100" autocomplete="username">

        <div id="organizerFields" class="organizer-fields <?= $selectedRole === 'organizer' ? 'show' : '' ?>">
            <label>Organizer name *</label>
            <input name="organizer_name" maxlength="100" <?= $selectedRole === 'organizer' ? 'required' : '' ?> value="<?= htmlspecialchars($_GET['organizer_name'] ?? '') ?>">
            <label>Company name *</label>
            <input name="company_name" maxlength="150" <?= $selectedRole === 'organizer' ? 'required' : '' ?> value="<?= htmlspecialchars($_GET['company_name'] ?? '') ?>">
            <p class="hint">Organizer information will be saved after your email is verified.</p>
        </div>

        <label>Email *</label>
        <input type="email" name="email" required autocomplete="email">
        <label>Phone number *</label>
        <input type="tel" name="phone" pattern="[0-9+ ]{8,20}" required autocomplete="tel">
        <button>Register and send OTP</button>
    </form>
    <p>Already have an account? <a href="login.php">Login</a></p>
</div>
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
</body>
</html>
