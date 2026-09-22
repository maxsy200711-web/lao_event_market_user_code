<!DOCTYPE html>
<html lang="lo">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LAOeventMarket - Auth</title>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Lao:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Noto Sans Lao', sans-serif; }
        
        body { 
            min-height: 100vh; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            padding: 20px; 
            position: relative;
            overflow: hidden;
        }

        .bg-slide {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: linear-gradient(rgba(15, 23, 42, 0.75), rgba(15, 23, 42, 0.75)), 
                              url('https://media02.stockfood.com/largepreviews/MjIxMDQzODg0OQ==/71304479-Night-market-in-Vientiane-on-the-banks-of-the-Mekong-Laos.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            z-index: -1;
        }

        .auth-card { 
            width: 100%; 
            max-width: 420px; 
            background: rgba(255, 255, 255, 0.96); 
            backdrop-filter: blur(8px);
            border-radius: 16px; 
            padding: 32px 28px; 
            box-shadow: 0 20px 25px -5px rgba(0,0,0,0.4); 
        }
        
        .brand-logo { text-align: center; font-size: 24px; font-weight: 700; color: #10B981; text-decoration: none; display: block; margin-bottom: 4px; }
        .brand-logo span { color: #000; }
        .subtitle { text-align: center; font-size: 13px; color: #64748B; margin-bottom: 20px; }
        
        .role-switch { display: flex; background: #F1F5F9; border-radius: 10px; padding: 4px; margin-bottom: 20px; }
        .role-btn { flex: 1; padding: 10px 0; font-size: 13px; font-weight: 600; color: #64748B; border: none; background: transparent; border-radius: 8px; cursor: pointer; transition: 0.2s; }
        .role-btn.active { background: #10B981; color: #ffffff; }

        .form-group { margin-bottom: 14px; }
        .form-group label { display: block; font-size: 13px; font-weight: 500; margin-bottom: 6px; }
        .form-control { width: 100%; height: 42px; border: 1px solid #E2E8F0; border-radius: 8px; padding: 0 14px; font-size: 14px; outline: none; background: #fff; }
        .btn-submit { width: 100%; height: 44px; background: #10B981; color: #fff; border: none; border-radius: 8px; font-size: 14px; font-weight: 600; cursor: pointer; transition: 0.2s; margin-bottom: 16px; }
        .btn-submit:hover { background: #059669; }

        .divider { display: flex; align-items: center; text-align: center; margin: 16px 0; color: #94A3B8; font-size: 12px; }
        .divider::before, .divider::after { content: ''; flex: 1; border-bottom: 1px solid #E2E8F0; }
        .divider span { padding: 0 10px; }

        .btn-social { width: 100%; height: 44px; border: 1px solid #E2E8F0; border-radius: 8px; background: #fff; font-size: 14px; font-weight: 500; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 10px; margin-bottom: 12px; transition: 0.2s; text-decoration: none; color: #334155; }
        .btn-social:hover { background: #F8FAFC; }
        .btn-facebook { background: #1877F2; color: #fff; border: none; }
        .btn-facebook:hover { background: #166FE5; }
        .btn-tiktok { background: #000000; color: #fff; border: none; }
        .btn-tiktok:hover { background: #111; }

        .hidden { display: none; }
    </style>
</head>
<body>

<div class="bg-slide"></div>

<div class="auth-card">
    <a href="index.php" class="brand-logo">LAO<span>event</span>Market</a>
    <p class="subtitle">ເຂົ້າສູ່ລະບົບ ຫຼື ລົງທະບຽນ</p>

    <div class="role-switch">
        <button type="button" class="role-btn active" onclick="toggleRole('user')">
            <i class="ti ti-user"></i> ຜູ້ໃຊ້ທົ່ວໄປ
        </button>
        <button type="button" class="role-btn" onclick="toggleRole('organizer')">
            <i class="ti ti-building-store"></i> ຜູ້ຈັດງານ (Organizer)
        </button>
    </div>

    <!-- 1. Form ສໍາລັບ User (Gmail OTP) -->
    <div id="userSection">
        <form action="send_otp.php" method="POST">
            <input type="hidden" name="role" value="user">
            <div class="form-group">
                <label>ອີເມວ (Gmail)</label>
                <input type="email" name="email" class="form-control" placeholder="example@gmail.com" required>
            </div>
            <button type="submit" class="btn-submit">ສົ່ງລະຫັດ OTP ໄປທີ່ Gmail</button>
        </form>
    </div>

    <!-- 2. Form ສໍາລັບ Organizer (Gmail OTP + Facebook/TikTok) -->
    <div id="organizerSection" class="hidden">
        <form action="send_otp.php" method="POST">
            <input type="hidden" name="role" value="organizer">
            <div class="form-group">
                <label>ອີເມວຜູ້ຈັດງານ (Gmail)</label>
                <input type="email" name="email" class="form-control" placeholder="organizer@gmail.com" required>
            </div>
            <button type="submit" class="btn-submit">ສົ່ງລະຫັດ OTP ໄປທີ່ Gmail</button>
        </form>

        <div class="divider">
            <span>ຫຼື ເຂົ້າສູ່ລະບົບຜ່ານ</span>
        </div>

        <a href="social_login.php?provider=facebook&role=organizer" class="btn-social btn-facebook">
            <i class="ti ti-brand-facebook" style="font-size: 20px;"></i> ເຂົ້າສູ່ລະບົບຜ່ານ Facebook
        </a>

        <a href="social_login.php?provider=tiktok&role=organizer" class="btn-social btn-tiktok">
            <i class="ti ti-brand-tiktok" style="font-size: 20px;"></i> ເຂົ້າສູ່ລະບົບຜ່ານ TikTok
        </a>
    </div>
</div>

<script>
function toggleRole(role) {
    const userSec = document.getElementById('userSection');
    const orgSec = document.getElementById('organizerSection');
    const btns = document.querySelectorAll('.role-btn');

    if (role === 'user') {
        userSec.classList.remove('hidden');
        orgSec.classList.add('hidden');
        btns[0].classList.add('active');
        btns[1].classList.remove('active');
    } else {
        userSec.classList.add('hidden');
        orgSec.classList.remove('hidden');
        btns[1].classList.add('active');
        btns[0].classList.remove('active');
    }
}
</script>
</body>
</html>