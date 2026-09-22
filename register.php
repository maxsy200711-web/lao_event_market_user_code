<!DOCTYPE html>
<html lang="lo">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LAOeventMarket - Register</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Lao:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Noto Sans Lao', sans-serif;
        }

        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(rgba(17, 24, 39, 0.65), rgba(17, 24, 39, 0.65)), 
                        url('https://media02.stockfood.com/largepreviews/MjIxMDQzODg0OQ==/71304479-Night-market-in-Vientiane-on-the-banks-of-the-Mekong-Laos.jpg') center/cover no-repeat;
            padding: 20px;
            position: relative;
        }

        .lang-dropdown {
            position: absolute;
            top: 20px;
            right: 20px;
        }

        .lang-btn {
            background: rgba(255, 255, 255, 0.9);
            border: none;
            padding: 8px 14px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 6px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .lang-menu {
            display: none;
            position: absolute;
            right: 0;
            top: 110%;
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            min-width: 110px;
            z-index: 10;
        }

        .lang-menu.show { display: block; }

        .lang-menu button {
            width: 100%;
            padding: 8px 14px;
            background: none;
            border: none;
            text-align: left;
            font-size: 13px;
            cursor: pointer;
        }

        .lang-menu button:hover { background: #F1F5F9; }

        .auth-card {
            width: 100%;
            max-width: 420px;
            background: #ffffff;
            border-radius: 16px;
            padding: 32px 28px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.3);
        }

        .brand-logo {
            text-align: center;
            font-size: 24px;
            font-weight: 700;
            color: #10B981;
            text-decoration: none;
            display: block;
            margin-bottom: 4px;
        }

        .brand-logo span { color: #000000; }

        .subtitle {
            text-align: center;
            font-size: 13px;
            color: #64748B;
            margin-bottom: 20px;
        }

        .role-switch {
            display: flex;
            background: #F1F5F9;
            border-radius: 10px;
            padding: 4px;
            margin-bottom: 20px;
        }

        .role-btn {
            flex: 1;
            text-align: center;
            padding: 8px 0;
            font-size: 13px;
            font-weight: 500;
            color: #64748B;
            border: none;
            background: transparent;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .role-btn.active {
            background: #10B981;
            color: #ffffff;
        }

        .form-row-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }

        .form-group { margin-bottom: 14px; }

        .form-group label {
            display: block;
            font-size: 13px;
            font-weight: 500;
            color: #111827;
            margin-bottom: 6px;
        }

        .form-control {
            width: 100%;
            height: 42px;
            border: 1px solid #E2E8F0;
            border-radius: 8px;
            padding: 0 14px;
            font-size: 14px;
            outline: none;
            box-sizing: border-box;
        }

        .form-control:focus { border-color: #10B981; }

        .btn-submit {
            width: 100%;
            height: 44px;
            background: #10B981;
            color: #ffffff;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 10px;
        }

        .btn-submit:hover { background: #059669; }

        .auth-footer {
            text-align: center;
            font-size: 13px;
            color: #64748B;
            margin-top: 20px;
        }

        .auth-footer a {
            color: #10B981;
            text-decoration: none;
            font-weight: 500;
        }

        .form-error {
            background: #FEE2E2;
            color: #B91C1C;
            font-size: 12px;
            padding: 8px 10px;
            border-radius: 8px;
            margin-bottom: 14px;
            text-align: center;
        }
    </style>
</head>
<body>

<div class="lang-dropdown">
    <button class="lang-btn" id="langBtn">
        <i class="ti ti-world"></i> <span id="langLabel">ລາວ</span> <i class="ti ti-chevron-down"></i>
    </button>
    <div class="lang-menu" id="langMenu">
        <button onclick="changeLang('lo')">ລາວ</button>
        <button onclick="changeLang('en')">English</button>
        <button onclick="changeLang('zh')">中文</button>
    </div>
</div>

<div class="auth-card">
    <a href="index.php" class="brand-logo">LAO<span>event</span>Market</a>
    <p class="subtitle" data-i18n="registerSubtitle">ລົງທະບຽນສ້າງບັນຊີໃໝ່</p>

    <?php
    // ສະແດງຂໍ້ຄວາມ error ຈາກ register_process.php (ຖ້າມີ)
    $errMap = [
        'empty_fields'      => 'ກະລຸນາປ້ອນຂໍ້ມູນໃຫ້ຄົບຖ້ວນ',
        'password_mismatch' => 'ລະຫັດຜ່ານ ແລະ ຢືນຢັນລະຫັດຜ່ານ ບໍ່ຕົງກັນ',
        'password_short'    => 'ລະຫັດຜ່ານຕ້ອງມີຢ່າງໜ້ອຍ 6 ຕົວອັກສອນ',
        'email_exists'      => 'ອີເມວນີ້ຖືກນຳໃຊ້ແລ້ວ',
        'phone_exists'      => 'ເບີໂທນີ້ຖືກນຳໃຊ້ແລ້ວ',
        'phone_invalid'     => 'ຮູບແບບເບີໂທບໍ່ຖືກຕ້ອງ',
        'system_error'      => 'ມີຂໍ້ຜິດພາດ ກະລຸນາລອງໃໝ່',
    ];
    $err = $_GET['error'] ?? '';
    if ($err !== '' && isset($errMap[$err])) {
        echo '<p class="form-error">' . htmlspecialchars($errMap[$err]) . '</p>';
    }
    $roleGet = ($_GET['role'] ?? 'user') === 'organizer' ? 'organizer' : 'user';
    ?>

    <div class="role-switch">
        <button type="button" class="role-btn <?= $roleGet === 'user' ? 'active' : '' ?>" onclick="setRole('user', this)">
            <i class="ti ti-user"></i> <span data-i18n="roleUser">ຜູ້ໃຊ້ທົ່ວໄປ</span>
        </button>
        <button type="button" class="role-btn <?= $roleGet === 'organizer' ? 'active' : '' ?>" onclick="setRole('organizer', this)">
            <i class="ti ti-building"></i> <span data-i18n="roleOrganizer">ຜູ້ຈັດງານ</span>
        </button>
    </div>

    <form action="register_process.php" method="POST">
        <input type="hidden" name="role" id="roleInput" value="<?= $roleGet ?>">

        <div class="form-group">
            <label data-i18n="fullname">ຊື່ ແລະ ນາມສະກຸນ</label>
            <input type="text" name="fullname" class="form-control" data-i18n-ph="fullnamePh" placeholder="ປ້ອນຊື່ ແລະ ນາມສະກຸນ" required>
        </div>

        <div class="form-row-2">
            <div class="form-group">
                <label data-i18n="email">ອີເມວ</label>
                <input type="email" name="email" class="form-control" placeholder="name@email.com" required>
            </div>
            <div class="form-group">
                <label data-i18n="phone">ເບີໂທ</label>
                <input type="tel" name="phone" class="form-control" data-i18n-ph="phonePh" placeholder="020 xxxxxxxx"
                       pattern="[0-9+ ]{8,20}" required>
            </div>
        </div>

        <div class="form-group">
            <label data-i18n="password">ລະຫັດຜ່ານ</label>
            <input type="password" name="password" class="form-control" data-i18n-ph="passPh" placeholder="ຢ່າງໜ້ອຍ 6 ຕົວອັກສອນ" required>
        </div>

        <div class="form-group">
            <label data-i18n="confirmPassword">ຢືນຢັນລະຫັດຜ່ານ</label>
            <input type="password" name="confirm_password" class="form-control" data-i18n-ph="confirmPassPh" placeholder="ປ້ອນລະຫັດຜ່ານອີກຄັ້ງ" required>
        </div>

        <button type="submit" class="btn-submit" data-i18n="registerBtn">ສ້າງບັນຊີ</button>
    </form>

    <div class="auth-footer">
        <span data-i18n="hasAccount">ມີບັນຊີຢູ່ແລ້ວບໍ?</span> <a href="login.php" data-i18n="loginHere">ເຂົ້າສູ່ລະບົບ</a>
    </div>
</div>

<script>
const langData = {
    lo: {
        label: "ລາວ",
        registerSubtitle: "ລົງທະບຽນສ້າງບັນຊີໃໝ່",
        roleUser: "ຜູ້ໃຊ້ທົ່ວໄປ",
        roleOrganizer: "ຜູ້ຈັດງານ",
        fullname: "ຊື່ ແລະ ນາມສະກຸນ",
        fullnamePh: "ປ້ອນຊື່ ແລະ ນາມສະກຸນ",
        email: "ອີເມວ",
        phone: "ເບີໂທ",
        phonePh: "020 xxxxxxxx",
        password: "ລະຫັດຜ່ານ",
        passPh: "ຢ່າງໜ້ອຍ 6 ຕົວອັກສອນ",
        confirmPassword: "ຢືນຢັນລະຫັດຜ່ານ",
        confirmPassPh: "ປ້ອນລະຫັດຜ່ານອີກຄັ້ງ",
        registerBtn: "ສ້າງບັນຊີ",
        hasAccount: "ມີບັນຊີຢູ່ແລ້ວບໍ?",
        loginHere: "ເຂົ້າສູ່ລະບົບ"
    },
    en: {
        label: "English",
        registerSubtitle: "Create a new account",
        roleUser: "General User",
        roleOrganizer: "Organizer",
        fullname: "Full Name",
        fullnamePh: "Enter your full name",
        email: "Email",
        phone: "Phone Number",
        phonePh: "020 xxxxxxxx",
        password: "Password",
        passPh: "At least 6 characters",
        confirmPassword: "Confirm Password",
        confirmPassPh: "Enter password again",
        registerBtn: "Create Account",
        hasAccount: "Already have an account?",
        loginHere: "Sign In"
    },
    zh: {
        label: "中文",
        registerSubtitle: "注册新账号",
        roleUser: "普通用户",
        roleOrganizer: "主办方",
        fullname: "姓名",
        fullnamePh: "请输入您的姓名",
        email: "电子邮箱",
        phone: "电话号码",
        phonePh: "020 xxxxxxxx",
        password: "密码",
        passPh: "至少 6 个字符",
        confirmPassword: "确认密码",
        confirmPassPh: "请再次输入密码",
        registerBtn: "创建账号",
        hasAccount: "已有账号？",
        loginHere: "立即登录"
    }
};

const langBtn = document.getElementById('langBtn');
const langMenu = document.getElementById('langMenu');

langBtn.addEventListener('click', () => langMenu.classList.toggle('show'));

function changeLang(lang) {
    document.getElementById('langLabel').innerText = langData[lang].label;

    document.querySelectorAll('[data-i18n]').forEach(el => {
        const key = el.getAttribute('data-i18n');
        if (langData[lang][key]) el.innerText = langData[lang][key];
    });

    document.querySelectorAll('[data-i18n-ph]').forEach(el => {
        const key = el.getAttribute('data-i18n-ph');
        if (langData[lang][key]) el.placeholder = langData[lang][key];
    });

    localStorage.setItem('pref_lang', lang);
    langMenu.classList.remove('show');
}

function setRole(role, btn) {
    document.getElementById('roleInput').value = role;
    document.querySelectorAll('.role-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
}

const savedLang = localStorage.getItem('pref_lang') || 'lo';
changeLang(savedLang);
</script>

</body>
</html>