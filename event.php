<?php
session_start();
require_once __DIR__ . "/config.php";

$event_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($event_id <= 0) {
    header("Location: index.php");
    exit;
}

// -------------------------------------------------------------
// 1. ປະມວນຜົນການສົ່ງຣີວິວ (Review Submission Handler)
// -------------------------------------------------------------
$review_error = '';
$review_success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_review'])) {
    if (!isset($_SESSION['user_id'])) {
        $review_error = 'ກະລຸນາເຂົ້າສູ່ລະບົບກ່ອນຂຽນຣີວິວ';
    } else {
        $user_id = (int)$_SESSION['user_id'];
        $rating  = (int)($_POST['rating'] ?? 0);
        $comment = trim($_POST['comment'] ?? '');

        if ($rating < 1 || $rating > 5) {
            $review_error = 'ກະລຸນາເລືອກຄະແນນດາວ (1-5 ດາວ)';
        } else {
            // ເພີ່ມ ຫຼື ອັບເດດຣີວິວ
            $stmt = $pdo->prepare("
                INSERT INTO reviews (user_id, event_id, rating, comment, create_at) 
                VALUES (?, ?, ?, ?, NOW())
                ON DUPLICATE KEY UPDATE rating = VALUES(rating), comment = VALUES(comment), create_at = NOW()
            ");
            if ($stmt->execute([$user_id, $event_id, $rating, $comment])) {
                $review_success = 'ບັນທຶກການຣີວິວສຳເລັດແລ້ວ!';
            } else {
                $review_error = 'ເກີດຂໍ້ຜິດພາດໃນການບັນທຶກຣີວິວ';
            }
        }
    }
}

// -------------------------------------------------------------
// 2. ດຶງຂໍ້ມູນງານຕະຫຼາດນັດ + ຂໍ້ມູນ Organizer (ເພີ່ມ o.is_verified)
// -------------------------------------------------------------
$stmt = $pdo->prepare("
    SELECT e.*, o.organizer_name, o.company_name, o.email as org_email, o.phone as org_phone, o.is_verified 
    FROM events e
    LEFT JOIN organizers o ON e.organizer_id = o.organizer_id
    WHERE e.event_id = ?
");
$stmt->execute([$event_id]);
$event = $stmt->fetch();

if (!$event) {
    header("Location: index.php");
    exit;
}

// -------------------------------------------------------------
// 3. ດຶງຂໍ້ມູນຮ້ານຄ້າ (Stalls)
// -------------------------------------------------------------
$stall_stmt = $pdo->prepare("SELECT * FROM event_stalls WHERE event_id = ? ORDER BY stall_id DESC");
$stall_stmt->execute([$event_id]);
$stalls = $stall_stmt->fetchAll();

// -------------------------------------------------------------
// 4. ດຶງຂໍ້ມູນຣີວິວ (Reviews) + ຄະແນນສະເລ່ຍ
// -------------------------------------------------------------
$review_stmt = $pdo->prepare("
    SELECT r.*, u.full_name AS username 
    FROM reviews r
    LEFT JOIN users u ON r.user_id = u.user_id
    WHERE r.event_id = ?
    ORDER BY r.create_at DESC
");
$review_stmt->execute([$event_id]);
$reviews = $review_stmt->fetchAll();

$avg_stmt = $pdo->prepare("SELECT AVG(rating) as avg_rating, COUNT(*) as total_reviews FROM reviews WHERE event_id = ?");
$avg_stmt->execute([$event_id]);
$rating_data = $avg_stmt->fetch();
$avg_rating = round($rating_data['avg_rating'] ?? 0, 1);
$total_reviews = $rating_data['total_reviews'] ?? 0;
?>
<!DOCTYPE html>
<html lang="lo">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($event['title']) ?> - LAOeventMarket</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Lao:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- FontAwesome Icon Library -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #10B981;
            --primary-hover: #059669;
            --dark: #111827;
            --bg: #F8FAFC;
            --border: #E2E8F0;
            --text-muted: #64748B;
            --font-lao: 'Noto Sans Lao', sans-serif;
            --star-color: #F59E0B;
            --verified-color: #2563EB;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: var(--font-lao); }
        body { background-color: var(--bg); color: #334155; min-height: 100vh; }

        /* Top Header */
        header.top {
            background: #ffffff;
            border-bottom: 1px solid var(--border);
            padding: 14px 28px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 100;
        }
        .brand {
            display: flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            font-size: 20px;
            color: var(--dark);
            font-weight: 700;
        }
        .brand b { color: var(--primary); }
        .brand-mark { background: #ECFDF5; color: var(--primary); padding: 6px 10px; border-radius: 8px; display: flex; }

        .btn-back {
            text-decoration: none;
            color: var(--dark);
            border: 1px solid var(--border);
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #fff;
            transition: 0.2s;
        }
        .btn-back:hover { background: #F1F5F9; }

        /* Container Layout */
        .container { max-width: 1100px; margin: 30px auto; padding: 0 20px; }

        /* Event Banner Card */
        .event-card {
            background: #ffffff;
            border-radius: 16px;
            border: 1px solid var(--border);
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0,0,0,0.03);
            margin-bottom: 30px;
        }
        .event-banner {
            width: 100%;
            max-height: 420px;
            object-fit: cover;
            display: block;
            background: #E2E8F0;
        }
        .event-details { padding: 30px; }
        .badge-cat {
            display: inline-block;
            background: #ECFDF5;
            color: var(--primary);
            font-size: 13px;
            font-weight: 600;
            padding: 4px 12px;
            border-radius: 20px;
            margin-bottom: 12px;
        }
        .event-title { font-size: 28px; font-weight: 700; color: var(--dark); margin-bottom: 16px; }

        /* Info Grid */
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 16px;
            background: #F8FAFC;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 24px;
        }
        .info-item { display: flex; align-items: center; gap: 12px; }
        .info-item i { font-size: 20px; color: var(--primary); background: #fff; padding: 12px; border-radius: 10px; border: 1px solid var(--border); }
        .info-text small { display: block; font-size: 12px; color: var(--text-muted); }
        .info-text span { font-size: 14px; font-weight: 600; color: var(--dark); }

        .section-desc { margin-bottom: 24px; }
        .section-desc h3 { font-size: 18px; color: var(--dark); margin-bottom: 8px; }
        .section-desc p { line-height: 1.7; color: #475569; font-size: 15px; }

        /* Organizer Box */
        .org-box {
            border-top: 1px solid var(--border);
            padding-top: 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
        }
        .org-info { display: flex; align-items: center; gap: 12px; }
        .org-avatar { width: 46px; height: 46px; background: #10B98115; color: var(--primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 20px; }
        
        .org-details h4 { 
            font-size: 15px; 
            color: var(--dark); 
            display: flex; 
            align-items: center; 
            gap: 6px; 
        }
        .verified-badge {
            color: var(--verified-color);
            font-size: 15px;
        }
        
        .org-details p { font-size: 13px; color: var(--text-muted); }

        /* Stalls Section */
        .stalls-section { margin-top: 40px; }
        .stalls-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; }
        .stalls-header h2 { font-size: 22px; color: var(--dark); display: flex; align-items: center; gap: 8px; }

        .stalls-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
            gap: 20px;
        }
        .stall-card {
            background: #ffffff;
            border: 1px solid var(--border);
            border-radius: 12px;
            overflow: hidden;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .stall-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.05);
        }
        .stall-img {
            width: 100%;
            height: 160px;
            object-fit: cover;
            background: #F1F5F9;
        }
        .stall-body { padding: 16px; }
        .stall-number {
            display: inline-block;
            background: #3B82F615;
            color: #2563EB;
            font-size: 12px;
            font-weight: 700;
            padding: 2px 8px;
            border-radius: 6px;
            margin-bottom: 6px;
        }
        .stall-title { font-size: 16px; font-weight: 700; color: var(--dark); margin-bottom: 6px; }
        .stall-cat { font-size: 12px; color: var(--primary); font-weight: 600; margin-bottom: 10px; }
        .stall-desc { font-size: 13px; color: var(--text-muted); line-height: 1.5; margin-bottom: 12px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
        .stall-contact { font-size: 13px; color: #334155; border-top: 1px dashed var(--border); padding-top: 10px; display: flex; align-items: center; gap: 6px; }

        .empty-stalls {
            text-align: center;
            padding: 40px;
            background: #ffffff;
            border: 1px dashed var(--border);
            border-radius: 12px;
            color: var(--text-muted);
        }
        .empty-stalls i { font-size: 40px; margin-bottom: 8px; display: block; color: #CBD5E1; }

        /* Review & Rating Styles */
        .reviews-section {
            margin-top: 40px;
            background: #ffffff;
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 28px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.03);
        }
        .reviews-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
            border-bottom: 1px solid var(--border);
            padding-bottom: 16px;
        }
        .rating-summary {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .rating-num {
            font-size: 32px;
            font-weight: 700;
            color: var(--dark);
        }
        .stars-outer {
            color: #CBD5E1;
            font-size: 18px;
        }
        .stars-outer .filled {
            color: var(--star-color);
        }

        .rating-input {
            display: flex;
            flex-direction: row-reverse;
            justify-content: flex-end;
            gap: 8px;
            margin-bottom: 16px;
        }
        .rating-input input { display: none; }
        .rating-input label {
            font-size: 28px;
            color: #CBD5E1;
            cursor: pointer;
            transition: color 0.2s, transform 0.1s;
        }
        .rating-input label:hover,
        .rating-input label:hover ~ label,
        .rating-input input:checked ~ label {
            color: var(--star-color);
        }
        .rating-input label:hover { transform: scale(1.15); }

        .review-form {
            background: #F8FAFC;
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 30px;
        }
        .review-form textarea {
            width: 100%;
            height: 90px;
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 10px;
            font-size: 14px;
            resize: vertical;
            outline: none;
            margin-bottom: 12px;
        }
        .review-form textarea:focus { border-color: var(--primary); }
        .btn-submit-review {
            background: var(--primary);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.2s;
        }
        .btn-submit-review:hover { background: var(--primary-hover); }

        .review-item {
            border-bottom: 1px solid var(--border);
            padding: 16px 0;
        }
        .review-item:last-child { border-bottom: none; }
        .review-user {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 6px;
        }
        .review-username { font-weight: 600; color: var(--dark); font-size: 14px; }
        .review-date { font-size: 12px; color: var(--text-muted); }
        .review-comment { font-size: 14px; color: #475569; line-height: 1.5; }
        
        .alert {
            padding: 10px 14px;
            border-radius: 8px;
            font-size: 13px;
            margin-bottom: 16px;
        }
        .alert-error { background: #FEE2E2; color: #DC2626; }
        .alert-success { background: #ECFDF5; color: #059669; }
    </style>
</head>
<body>

<header class="top">
    <a class="brand" href="index.php">
        <span class="brand-mark"><i class="fa-solid fa-house"></i></span>
        <span><b>LAO</b>event<b>Market</b></span>
    </a>
    <a href="index.php" class="btn-back"><i class="fa-solid fa-arrow-left"></i> ກັບໜ້າຫຼັກ</a>
</header>

<div class="container">
    <!-- Event Details Card -->
    <div class="event-card">
        <?php if (!empty($event['banner_img'])): ?>
            <img src="uploads/<?= htmlspecialchars($event['banner_img']) ?>" class="event-banner" alt="<?= htmlspecialchars($event['title']) ?>">
        <?php else: ?>
            <div style="height: 200px; background: #E2E8F0; display: flex; align-items: center; justify-content: center; color: #94A3B8;">
                <i class="fa-regular fa-image" style="font-size: 48px;"></i>
            </div>
        <?php endif; ?>

        <div class="event-details">
            <span class="badge-cat"><?= htmlspecialchars($event['category'] ?? 'General') ?></span>
            <h1 class="event-title"><?= htmlspecialchars($event['title']) ?></h1>

            <div class="info-grid">
                <!-- Location Section Dynamic Integration -->
                <div class="info-item">
                    <i class="fa-solid fa-location-dot"></i>
                    <div class="info-text">
                        <small>ສະຖານທີ່ຈັດງານ</small>
                        <span>
                            <?php if (!empty($event['map_url'])): ?>
                                <a href="<?= htmlspecialchars($event['map_url']) ?>" target="_blank" style="color: #2563EB; text-decoration: underline;">
                                    <?= htmlspecialchars($event['location_name'] ?? $event['location'] ?? 'ເບິ່ງແຜນທີ່') ?>
                                </a>
                            <?php else: ?>
                                <a href="https://www.google.com/maps/search/?api=1&query=<?= urlencode($event['location_name'] ?? $event['location'] ?? '') ?>" 
                                   target="_blank" 
                                   style="color: #2563EB; text-decoration: underline;" 
                                   title="ຄລິກເພື່ອເບິ່ງແຜນທີ່ Google Maps">
                                    <?= htmlspecialchars($event['location_name'] ?? $event['location'] ?? 'ເບິ່ງແຜນທີ່') ?>
                                </a>
                            <?php endif; ?>
                        </span>
                    </div>
                </div>

                <div class="info-item">
                    <i class="fa-solid fa-calendar-days"></i>
                    <div class="info-text">
                        <small>ໄລຍະເວລາຈັດງານ</small>
                        <span><?= htmlspecialchars($event['start_date'] ?? '') ?> ຫາ <?= htmlspecialchars($event['end_date'] ?? '') ?></span>
                    </div>
                </div>
                <div class="info-item">
                    <i class="fa-regular fa-clock"></i>
                    <div class="info-text">
                        <small>ເວລາເປີດ-ປິດ</small>
                        <span><?= htmlspecialchars(!empty($event['time']) ? $event['time'] : '') ?></span>
                    </div>
                </div>
            </div>

            <div class="section-desc">
                <h3>ລາຍລະອຽດງານ</h3>
                <p><?= nl2br(htmlspecialchars($event['description'] ?? 'ບໍ່ມີລາຍລະອຽດເພີ່ມເຕີມ')) ?></p>
            </div>

            <div class="org-box">
                <div class="org-info">
                    <div class="org-avatar"><i class="fa-regular fa-user"></i></div>
                    <div class="org-details">
                        <h4>
                            <?= htmlspecialchars($event['company_name'] ?: $event['organizer_name']) ?>
                            <?php if (!empty($event['is_verified']) && ($event['is_verified'] == 1 || $event['is_verified'] === 'verified')): ?>
                                <i class="fa-solid fa-circle-check verified-badge" title="ບັນຊີນີ້ได้รับการຢືນຢັນແລ້ວ"></i>
                            <?php endif; ?>
                        </h4>
                        <p>ຜູ້ຈັດງານອົງກອນ / ບຸກຄົນ</p>
                    </div>
                </div>
                <div style="font-size: 13px; color: var(--text-muted);">
                    <?php if (!empty($event['org_email'])): ?>
                        <div><i class="fa-regular fa-envelope"></i> <?= htmlspecialchars($event['org_email']) ?></div>
                    <?php endif; ?>
                    <?php if (!empty($event['org_phone'])): ?>
                        <div><i class="fa-solid fa-phone"></i> <?= htmlspecialchars($event['org_phone']) ?></div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Stalls Section -->
    <div class="stalls-section">
        <div class="stalls-header">
            <h2><i class="fa-solid fa-store" style="color: var(--primary);"></i> ຮ້ານຄ້າພາຍໃນງານ (<?= count($stalls) ?> ຮ້ານ)</h2>
        </div>

        <?php if (!empty($stalls)): ?>
            <div class="stalls-grid">
                <?php foreach ($stalls as $stall): ?>
                    <div class="stall-card">
                        <?php if (!empty($stall['image'])): ?>
                            <img src="uploads/<?= htmlspecialchars($stall['image']) ?>" class="stall-img" alt="<?= htmlspecialchars($stall['stall_name']) ?>">
                        <?php else: ?>
                            <div class="stall-img" style="display: flex; align-items: center; justify-content: center; color: #CBD5E1;">
                                <i class="fa-solid fa-store" style="font-size: 40px;"></i>
                            </div>
                        <?php endif; ?>
                        
                        <div class="stall-body">
                            <?php if (!empty($stall['stall_number'])): ?>
                                <span class="stall-number">ລັອກ: <?= htmlspecialchars($stall['stall_number']) ?></span>
                            <?php endif; ?>
                            <h3 class="stall-title"><?= htmlspecialchars($stall['stall_name']) ?></h3>
                            
                            <?php if (!empty($stall['categories'])): ?>
                                <div class="stall-cat"><i class="fa-solid fa-tag"></i> <?= htmlspecialchars($stall['categories']) ?></div>
                            <?php endif; ?>

                            <?php if (!empty($stall['description'])): ?>
                                <p class="stall-desc"><?= htmlspecialchars($stall['description']) ?></p>
                            <?php endif; ?>

                            <?php if (!empty($stall['contact_info'])): ?>
                                <div class="stall-contact">
                                    <i class="fa-solid fa-phone-volume" style="color: var(--primary);"></i>
                                    <span><?= htmlspecialchars($stall['contact_info']) ?></span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="empty-stalls">
                <i class="fa-solid fa-store-slash"></i>
                <p>ຍັງບໍ່ມີຂໍ້ມູນຮ້ານຄ້າໃນງານນີ້</p>
            </div>
        <?php endif; ?>
    </div>

    <!-- Reviews and Ratings Section -->
    <div class="reviews-section">
        <div class="reviews-header">
            <h2><i class="fa-solid fa-star" style="color: var(--star-color);"></i> ຣີວິວ ແລະ ຄະແນນ</h2>
            <div class="rating-summary">
                <span class="rating-num"><?= $avg_rating ?></span>
                <div>
                    <div class="stars-outer">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <i class="fa-solid fa-star <?= $i <= round($avg_rating) ? 'filled' : '' ?>"></i>
                        <?php endfor; ?>
                    </div>
                    <small style="color: var(--text-muted);">(ທັງໝົດ <?= $total_reviews ?> ຣີວິວ)</small>
                </div>
            </div>
        </div>

        <!-- Alert messages -->
        <?php if ($review_error): ?>
            <div class="alert alert-error"><?= htmlspecialchars($review_error) ?></div>
        <?php endif; ?>
        <?php if ($review_success): ?>
            <div class="alert alert-success"><?= htmlspecialchars($review_success) ?></div>
        <?php endif; ?>

        <!-- Form ໃຫ້ຄະແນນ ແລະ ຂຽນຣີວິວ -->
        <?php if (isset($_SESSION['user_id'])): ?>
            <form action="" method="POST" class="review-form">
                <h4 style="margin-bottom: 8px;">ຂຽນຣີວິວຂອງທ່ານ:</h4>
                <div class="rating-input">
                    <input type="radio" id="star5" name="rating" value="5" required /><label for="star5" title="5 ດາວ"><i class="fa-solid fa-star"></i></label>
                    <input type="radio" id="star4" name="rating" value="4" /><label for="star4" title="4 ດາວ"><i class="fa-solid fa-star"></i></label>
                    <input type="radio" id="star3" name="rating" value="3" /><label for="star3" title="3 ດາວ"><i class="fa-solid fa-star"></i></label>
                    <input type="radio" id="star2" name="rating" value="2" /><label for="star2" title="2 ດາວ"><i class="fa-solid fa-star"></i></label>
                    <input type="radio" id="star1" name="rating" value="1" /><label for="star1" title="1 ດາວ"><i class="fa-solid fa-star"></i></label>
                </div>
                <textarea name="comment" placeholder="ຂຽນຄຳຄິດເຫັນ ຫຼື ຄວາມປະທັບໃຈຂອງທ່ານຕໍ່ງານນີ້..."></textarea>
                <button type="submit" name="submit_review" class="btn-submit-review">
                    <i class="fa-solid fa-paper-plane"></i> ສົ່ງຣີວິວ
                </button>
            </form>
        <?php else: ?>
            <div style="background: #F8FAFC; border: 1px dashed var(--border); padding: 16px; border-radius: 8px; text-align: center; margin-bottom: 24px; font-size: 14px;">
                ກະລຸນາ <a href="login.php" style="color: var(--primary); font-weight: 600;">ເຂົ້າສູ່ລະບົບ</a> ເພື່ອໃຫ້ຄະແນນ ແລະ ຂຽນຣີວິວ
            </div>
        <?php endif; ?>

        <!-- ລາຍການຣີວິວ -->
        <div class="reviews-list">
            <?php if (!empty($reviews)): ?>
                <?php foreach ($reviews as $rev): ?>
                    <div class="review-item">
                        <div class="review-user">
                            <div>
                                <span class="review-username"><?= htmlspecialchars($rev['username'] ?: 'ຜູ້ໃຊ້ທົ່ວໄປ') ?></span>
                                <div class="stars-outer" style="display: inline-block; margin-left: 8px; font-size: 14px;">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <i class="fa-solid fa-star <?= $i <= $rev['rating'] ? 'filled' : '' ?>"></i>
                                    <?php endfor; ?>
                                </div>
                            </div>
                            <span class="review-date"><?= date('d/m/Y H:i', strtotime($rev['create_at'])) ?></span>
                        </div>
                        <?php if (!empty($rev['comment'])): ?>
                            <p class="review-comment"><?= nl2br(htmlspecialchars($rev['comment'])) ?></p>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="text-align: center; color: var(--text-muted); padding: 20px 0;">ຍັງບໍ່ມີຣີວິວສຳລັບງານນີ້</p>
            <?php endif; ?>
        </div>
    </div>
</div>

</body>
</html>