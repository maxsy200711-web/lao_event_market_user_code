<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . "/config.php";

$q = trim($_GET['q'] ?? '');
$province = (int)($_GET['province'] ?? 0);
$category = (int)($_GET['category'] ?? 0);
$date = trim($_GET['date'] ?? '');

$provinces = $pdo->query("SELECT province_id, province_name FROM provinces ORDER BY province_name")->fetchAll();
$categories = $pdo->query("SELECT category_id, category_name FROM categories ORDER BY category_name")->fetchAll();

// ດຶງສະເພາະງານທີ່ວັນທີສິ້ນສຸດ (end_date) ຍັງບໍ່ທັນກາຍວັນທີປັດຈຸບັນ (e.end_date >= CURDATE())
$sql = "SELECT e.event_id, e.title, e.description, e.banner_img, e.location,
               e.start_date, e.end_date, e.time,
               p.province_name, c.category_name
        FROM events e
        INNER JOIN provinces p ON p.province_id = e.province_id
        INNER JOIN categories c ON c.category_id = e.category_id
        WHERE e.status = 'approved' AND e.end_date >= CURDATE()";
$params = [];

if ($q !== '') {
    $sql .= " AND (e.title LIKE :q1 
              OR e.description LIKE :q2 
              OR e.location LIKE :q3 
              OR p.province_name LIKE :q4 
              OR c.category_name LIKE :q5)";
    $searchTerm = "%$q%";
    $params['q1'] = $searchTerm;
    $params['q2'] = $searchTerm;
    $params['q3'] = $searchTerm;
    $params['q4'] = $searchTerm;
    $params['q5'] = $searchTerm;
}
if ($province > 0) {
    $sql .= " AND e.province_id = :province";
    $params['province'] = $province;
}
if ($category > 0) {
    $sql .= " AND e.category_id = :category";
    $params['category'] = $category;
}
if ($date !== '') {
    $sql .= " AND :date BETWEEN e.start_date AND e.end_date";
    $params['date'] = $date;
}

$sql .= " ORDER BY e.start_date ASC, e.event_id DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$events = $stmt->fetchAll();

function imageUrl(?string $path): string {
    if (!$path) return '';
    if (preg_match('/^https?:\/\//i', $path)) return $path;
    return 'uploads/' . ltrim($path, '/');
}
function eventDate(string $start, ?string $end): string {
    if (!$end || $start === $end) return date('d/m/Y', strtotime($start));
    return date('d/m', strtotime($start)) . ' - ' . date('d/m/Y', strtotime($end));
}

$heroImages = [
    "https://images.unsplash.com/photo-1531058240690-006c04463396?auto=format&fit=crop&w=1600&q=80",
    "https://images.unsplash.com/photo-1511578314322-379afb476865?auto=format&fit=crop&w=1600&q=80",
    "https://images.unsplash.com/photo-1472653816316-3ad6f10a6592?auto=format&fit=crop&w=1600&q=80"
];
?> 
<!DOCTYPE html>
<html lang="lo">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>LAOeventMarket</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Lao:wght@100..900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">
<style>
:root {
  --primary: #10B981;
  --primary-hover: #059669;
  --dark: #111827;
  --bg: #F8FAFC;
  --border: #E2E8F0;
  --text-muted: #64748B;
  --font-lao: 'Noto Sans Lao', sans-serif;
}

*, *::before, *::after {
  box-sizing: border-box;
  margin: 0;
  padding: 0;
}

html, body {
  width: 100%;
  overflow-x: hidden;
  font-family: var(--font-lao);
  background-color: var(--bg);
  color: #334155;
  scroll-behavior: smooth;
}

input, select, button, textarea {
  font-family: var(--font-lao);
}

.wrapper {
  width: 100%;
  max-width: 1440px;
  margin: 0 auto;
  padding: 0 20px;
}

/* Dynamic Header Layout */
header.top {
  background: #ffffff;
  border-bottom: 1px solid var(--border);
  position: sticky;
  top: 0;
  z-index: 100;
  width: 100%;
}
.topbar {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 12px 0;
  gap: 16px;
  flex-wrap: wrap;
}
.brand {
  display: flex;
  align-items: center;
  gap: 8px;
  text-decoration: none;
  font-size: 20px;
  color: var(--dark);
  font-weight: 700;
  white-space: nowrap;
}
.brand b { color: var(--primary); }
.brand-mark {
  background: #ECFDF5;
  color: var(--primary);
  padding: 8px;
  border-radius: 8px;
  display: flex;
}
.top-search {
  flex: 1;
  max-width: 500px;
  position: relative;
  min-width: 200px;
}
.top-search i {
  position: absolute;
  left: 14px;
  top: 50%;
  transform: translateY(-50%);
  color: var(--text-muted);
}
.top-search input {
  width: 100%;
  padding: 10px 16px 10px 42px;
  border-radius: 20px;
  border: 1px solid var(--border);
  outline: none;
  font-size: 14px;
  background: #F1F5F9;
}
.actions {
  display: flex;
  align-items: center;
  gap: 12px;
  white-space: nowrap;
}
.icon-btn {
  background: transparent;
  border: none;
  font-size: 22px;
  cursor: pointer;
  color: var(--text-muted);
}
.btn {
  padding: 8px 16px;
  border-radius: 8px;
  text-decoration: none;
  font-size: 14px;
  font-weight: 500;
  cursor: pointer;
  display: inline-block;
  white-space: nowrap;
}
.btn-outline { border: 1px solid var(--border); color: var(--dark); }
.btn-green { background: var(--primary); color: white; border: none; }
.btn-green:hover { background: var(--primary-hover); }

/* Language Switcher Dropdown */
.lang-wrap { position: relative; }
.lang-btn {
  background: transparent;
  border: 1px solid var(--border);
  padding: 6px 10px;
  border-radius: 6px;
  cursor: pointer;
  display: flex;
  align-items: center;
  gap: 6px;
  font-size: 13px;
}
.lang-menu {
  display: none;
  position: absolute;
  right: 0;
  top: 100%;
  background: white;
  border: 1px solid var(--border);
  border-radius: 8px;
  box-shadow: 0 4px 12px rgba(0,0,0,0.1);
  margin-top: 4px;
  z-index: 10;
  min-width: 110px;
}
.lang-menu.show { display: block; }
.lang-menu button {
  width: 100%;
  text-align: left;
  padding: 8px 12px;
  background: none;
  border: none;
  cursor: pointer;
  font-size: 14px;
}
.lang-menu button:hover { background: var(--bg); }

/* Navigation Bar */
.nav {
  display: flex;
  gap: 24px;
  padding: 10px 0;
  border-top: 1px solid var(--border);
  overflow-x: auto;
  white-space: nowrap;
  -webkit-overflow-scrolling: touch;
}
.nav::-webkit-scrollbar { display: none; }
.nav a {
  text-decoration: none;
  color: var(--text-muted);
  font-size: 14px;
  display: flex;
  align-items: center;
  gap: 6px;
  font-weight: 500;
  flex-shrink: 0;
}
.nav a.active, .nav a:hover {
  color: var(--primary);
}

/* Full Width Hero Banner Section */
.hero { position: relative; width: 100%; }
.hero-slider {
  position: relative;
  width: 100%;
  height: 380px;
  overflow: hidden;
  background: var(--dark);
}
.slide {
  position: absolute;
  inset: 0;
  opacity: 0;
  transition: opacity 0.8s ease-in-out;
  background-size: cover;
  background-position: center;
}
.slide.active { opacity: 0.55; }
.hero-content {
  position: absolute;
  inset: 0;
  display: flex;
  align-items: center;
  z-index: 2;
  color: white;
}
.hero-copy h1 {
  font-size: 32px;
  font-weight: 700;
  line-height: 1.25;
}
.hero-copy em { color: var(--primary); font-style: normal; }
.hero-copy p {
  margin-top: 12px;
  max-width: 600px;
  font-size: 15px;
  color: #E2E8F0;
}

/* Floating Filter Card Layout */
.filter-card {
  background: white;
  border: 1px solid var(--border);
  border-radius: 12px;
  padding: 16px;
  margin-top: -35px;
  position: relative;
  z-index: 10;
  display: flex;
  gap: 12px;
  box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.08);
  align-items: center;
  flex-wrap: wrap;
}
.filter-search {
  flex: 2;
  min-width: 240px;
  display: flex;
  align-items: center;
  gap: 10px;
  border: 1px solid var(--border);
  border-radius: 8px;
  padding: 10px 14px;
}
.filter-field {
  flex: 1;
  min-width: 180px;
  display: flex;
  align-items: center;
  gap: 10px;
  border: 1px solid var(--border);
  border-radius: 8px;
  padding: 10px 14px;
}
.filter-search input, .filter-field select, .filter-field input {
  border: none;
  outline: none;
  width: 100%;
  background: transparent;
  font-size: 14px;
}
.filter-submit { height: 46px; padding: 0 28px; font-size: 15px; width: auto; }

/* Category Grid Display */
.section { padding: 40px 0; }
.section-title { margin-bottom: 20px; font-size: 20px; font-weight: 700; }
.category-row {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(110px, 1fr));
  gap: 16px;
}
.category {
  display: flex;
  flex-direction: column;
  align-items: center;
  text-decoration: none;
  color: var(--dark);
  font-size: 13px;
  text-align: center;
}
.cat-icon {
  width: 56px;
  height: 56px;
  border-radius: 50%;
  background: white;
  border: 1px solid var(--border);
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 24px;
  color: var(--primary);
  margin-bottom: 8px;
  transition: all 0.2s ease;
}
.category:hover .cat-icon {
  transform: translateY(-4px);
  border-color: var(--primary);
  box-shadow: 0 6px 16px rgba(16, 185, 129, 0.18);
}

/* Event Cards Responsive Grid */
.section-head { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
.view-all { color: var(--primary); text-decoration: none; font-size: 14px; font-weight: 600; }
.event-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
  gap: 20px;
}
.event-card {
  background: white;
  border-radius: 12px;
  border: 1px solid var(--border);
  overflow: hidden;
  display: flex;
  flex-direction: column;
  transition: transform 0.2s, box-shadow 0.2s;
}
.event-card:hover { transform: translateY(-4px); box-shadow: 0 12px 24px rgba(0,0,0,0.06); }
.event-photo {
  height: 180px;
  position: relative;
  background: #E2E8F0;
  display: block;
}
.event-photo img { width: 100%; height: 100%; object-fit: cover; }
.event-photo .placeholder { height: 100%; display: flex; align-items: center; justify-content: center; font-size: 36px; color: var(--text-muted); }
.date-badge {
  position: absolute;
  top: 12px;
  left: 12px;
  background: var(--primary);
  color: white;
  font-size: 12px;
  padding: 4px 10px;
  border-radius: 6px;
  font-weight: 500;
}
.heart {
  position: absolute;
  top: 12px;
  right: 12px;
  background: white;
  border: none;
  border-radius: 50%;
  width: 32px;
  height: 32px;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  box-shadow: 0 2px 6px rgba(0,0,0,0.15);
}
.event-body { padding: 16px; flex: 1; display: flex; flex-direction: column; }
.category-tag { font-size: 12px; color: var(--primary); font-weight: 600; }
.event-body h3 { font-size: 16px; margin: 6px 0 10px; line-height: 1.4; }
.event-body h3 a { color: var(--dark); text-decoration: none; }
.event-body p { font-size: 13px; color: var(--text-muted); margin-top: auto; display: flex; align-items: center; gap: 4px; }

/* Call To Action Banner */
.cta { background: var(--primary); color: white; padding: 40px 0; margin-top: 40px; }
.cta-inner { display: flex; justify-content: space-between; align-items: center; gap: 20px; flex-wrap: wrap; }
.cta-btn { background: white; color: var(--primary); padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: 700; white-space: nowrap; }
.stats { display: flex; gap: 24px; font-size: 14px; flex-wrap: wrap; }

/* Footer */
footer { background: var(--dark); color: #94A3B8; padding: 40px 0 24px; font-size: 14px; }
.footer-main {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 32px;
  margin-bottom: 32px;
}
.footer-main h3 { color: white; margin-bottom: 16px; font-size: 15px; }
.footer-main a { color: #94A3B8; text-decoration: none; display: block; margin-bottom: 10px; }
.footer-main a:hover { color: white; }
.footer-brand { color: white; margin-bottom: 12px; }
.copyright { text-align: center; border-top: 1px solid #1E293B; padding-top: 20px; font-size: 13px; }

/* RESPONSIVE MEDIA QUERIES */
@media (max-width: 992px) {
  .hero-slider { height: 320px; }
  .hero-copy h1 { font-size: 26px; }
  .filter-card { flex-direction: column; align-items: stretch; margin-top: -20px; }
  .filter-search, .filter-field { width: 100%; }
  .filter-submit { width: 100%; }
}

@media (max-width: 600px) {
  .wrapper { padding: 0 16px; }
  .topbar { flex-direction: column; align-items: stretch; gap: 12px; }
  .actions { justify-content: space-between; width: 100%; }
  .top-search { max-width: 100%; }
  
  .hero-slider { height: 260px; }
  .hero-copy h1 { font-size: 22px; }
  .hero-copy p { font-size: 13px; }
  
  .category-row {
    grid-template-columns: repeat(3, 1fr);
    gap: 12px;
  }
  .cat-icon { width: 48px; height: 48px; font-size: 20px; }
  .category span { font-size: 12px; }

  .event-grid {
    grid-template-columns: 1fr;
  }

  .cta-inner { flex-direction: column; text-align: center; }
  .stats { justify-content: center; }
  .cta-btn { width: 100%; text-align: center; }
}
</style>
</head>
<body>

<header class="top">
  <div class="topbar wrapper">
    <a class="brand" href="index.php">
      <span class="brand-mark"><i class="ti ti-home-2"></i></span>
      <span><b>LAO</b>event<b>Market</b></span>
    </a>

    <!-- ຊ່ອງຄົ້ນຫາເທິງ Header -->
    <form class="top-search" method="get" action="index.php#events">
      <i class="ti ti-search"></i>
      <input name="q" value="<?= htmlspecialchars($q) ?>" data-i18n-placeholder="search" placeholder="ຄົ້ນຫາ...">
      <?php if ($province > 0): ?>
        <input type="hidden" name="province" value="<?= $province ?>">
      <?php endif; ?>
      <?php if ($category > 0): ?>
        <input type="hidden" name="category" value="<?= $category ?>">
      <?php endif; ?>
      <?php if ($date !== ''): ?>
        <input type="hidden" name="date" value="<?= htmlspecialchars($date) ?>">
      <?php endif; ?>
    </form>

    <div class="actions">
      <button class="icon-btn" title="Favorites"><i class="ti ti-heart"></i></button>
      <button class="icon-btn" title="Notifications"><i class="ti ti-bell"></i></button>

      <div class="lang-wrap">
        <button class="lang-btn" id="langBtn"><i class="ti ti-world"></i> <span id="langText">ລາວ</span> <i class="ti ti-chevron-down"></i></button>
        <div class="lang-menu" id="langMenu">
          <button data-lang="lo">ລາວ</button>
          <button data-lang="zh">中文</button>
          <button data-lang="en">English</button>
        </div>
      </div>

      <!-- ເອີ້ນໃຊ້ Authentication Widget -->
      <?php include __DIR__ . '/auth_widget.php'; ?>
    </div>
  </div>

  <nav class="nav wrapper">
    <a class="active" href="index.php"><i class="ti ti-home"></i><span data-i18n="home">ໜ້າຫຼັກ</span></a>
    <a href="#events"><span data-i18n="events">ງານຕະຫຼາດນັດ</span></a>
    <a href="#categories"><span data-i18n="categories">ປະເພດງານ</span></a>
    <a href="#news"><span data-i18n="news">ຂ່າວສານ/ບົດຄວາມ</span></a>
    <a href="#contact"><span data-i18n="contact">ຕິດຕໍ່</span></a>
  </nav>
</header>

<main>
<section class="hero">
<div class="hero-slider">
  <div class="slide active" style="background-image: url('https://media02.stockfood.com/largepreviews/MjIxMDQzODg0OQ==/71304479-Night-market-in-Vientiane-on-the-banks-of-the-Mekong-Laos.jpg');"></div>
</div>

  <div class="hero-content wrapper">
    <div class="hero-copy">
      <h1><span data-i18n="hero1">ຮວບຮວມງານຕະຫຼາດນັດ</span><br><em data-i18n="hero2">ໃນທຸກທົ່ວປະເທດລາວ</em></h1>
      <p data-i18n="heroDesc">ຄົ້ນຫາງານຕະຫຼາດນັດ ໃຫ້ບ້ານທ່ານໄດ້ງ່າຍ ແລະ ວ່ອງໄວ ອັບເດດຂ່າວສານ ແລະ ກິດຈະກຳທີ່ໜ້າສົນໃຈທຸກມື້</p>
    </div>
  </div>
</section>

<!-- ຟອມ Filter Card ສໍາລັບຄົ້ນຫາແບບລະອຽດ -->
<form class="filter-card wrapper" method="get" action="index.php#events">
  <div class="filter-search">
    <i class="ti ti-search"></i>
    <input name="q" value="<?= htmlspecialchars($q) ?>" data-i18n-placeholder="eventSearch" placeholder="ຄົ້ນຫາງານຕະຫຼາດນັດ... (ຊື່ງານ, ສະຖານທີ່, ປະເພດ)">
  </div>
  <label class="filter-field">
    <i class="ti ti-map-pin"></i>
    <select name="province">
      <option value="0" data-i18n="allRegions">ທຸກພາກ</option>
      <?php foreach ($provinces as $p): ?>
        <option value="<?= (int)$p['province_id'] ?>" <?= $province === (int)$p['province_id'] ? 'selected' : '' ?>>
          <?= htmlspecialchars($p['province_name']) ?>
        </option>
      <?php endforeach; ?>
    </select>
  </label>
  <label class="filter-field">
    <i class="ti ti-calendar"></i>
    <input type="date" name="date" value="<?= htmlspecialchars($date) ?>">
  </label>
  <?php if ($category > 0): ?>
    <input type="hidden" name="category" value="<?= $category ?>">
  <?php endif; ?>
  <button class="btn btn-green filter-submit" type="submit" data-i18n="searchBtn">ຄົ້ນຫາ</button>
</form>

<section class="section wrapper" id="categories">
  <div class="section-title"><h2 data-i18n="categoryTitle">ປະເພດງານຕະຫຼາດນັດ</h2></div>
  <div class="category-row">
    <?php
    $icons = ['ti-tools-kitchen-2','ti-basket','ti-shirt','ti-gift','ti-plant-2','ti-camera','ti-music','ti-grid-dots'];
    foreach ($categories as $i => $cat):
    ?>
      <a class="category" href="index.php?category=<?= (int)$cat['category_id'] ?>#events">
        <span class="cat-icon"><i class="ti <?= $icons[$i % count($icons)] ?>"></i></span>
        <span><?= htmlspecialchars($cat['category_name']) ?></span>
      </a>
    <?php endforeach; ?>
  </div>
</section>

<section class="section wrapper" id="events">
  <div class="section-head">
    <h2 data-i18n="recommended">ງານຕະຫຼາດນັດແນະນຳ</h2>
    <a href="index.php" class="view-all" data-i18n="viewAll">ເບິ່ງທັງໝົດ <i class="ti ti-arrow-right"></i></a>
  </div>

  <?php if (!$events): ?>
    <div style="text-align: center; padding: 48px 0;">
      <i class="ti ti-calendar-off" style="font-size: 50px; color: var(--text-muted);"></i>
      <h3 data-i18n="noEvents" style="margin-top: 12px;">ບໍ່ພົບງານຕະຫຼາດນັດ</h3>
      <p data-i18n="tryAgain">ລອງປ່ຽນຄຳຄົ້ນຫາ ຫຼື ຕົວກອງ</p>
    </div>
  <?php else: ?>
  <div class="event-grid">
    <?php foreach ($events as $event): ?>
      <article class="event-card">
        <a href="event.php?id=<?= (int)$event['event_id'] ?>" class="event-photo">
          <?php if (!empty($event['banner_img'])): ?>
            <img src="<?= htmlspecialchars(imageUrl($event['banner_img'])) ?>" alt="<?= htmlspecialchars($event['title']) ?>">
          <?php else: ?>
            <div class="placeholder"><i class="ti ti-photo"></i></div>
          <?php endif; ?>
          <span class="date-badge"><?= htmlspecialchars(eventDate($event['start_date'], $event['end_date'])) ?></span>
          <button type="button" class="heart"><i class="ti ti-heart"></i></button>
        </a>
        <div class="event-body">
          <span class="category-tag"><?= htmlspecialchars($event['category_name']) ?></span>
          <h3><a href="event.php?id=<?= (int)$event['event_id'] ?>"><?= htmlspecialchars($event['title']) ?></a></h3>
          <p><i class="ti ti-map-pin"></i><?= htmlspecialchars($event['location'] ?: $event['province_name']) ?></p>
        </div>
      </article>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>
</section>

<section class="cta">
  <div class="wrapper cta-inner">
    <div>
      <h2 data-i18n="organizerQuestion">ທ່ານເປັນຜູ້ຈັດງານບໍ?</h2>
      <p data-i18n="organizerDesc">ລົງທະບຽນງານຕະຫຼາດນັດຂອງທ່ານ ໃຫ້ຄົນຮູ້ຈັກງ່າຍຂຶ້ນ</p>
    </div>
    <div class="stats">
      <span><i class="ti ti-calendar-event"></i> <?= count($events) ?>+ <small data-i18n="events">ງານ</small></span>
      <span><i class="ti ti-building-store"></i> <?= count($events) ?>+ <small data-i18n="organizers">ຜູ້ຈັດງານ</small></span>
      <span><i class="ti ti-users"></i> <small data-i18n="users">ຜູ້ໃຊ້</small></span>
    </div>
  </div>
</section>
</main>

<footer id="contact">
  <div class="footer-main wrapper">
    <div>
      <div class="brand footer-brand"><span class="brand-mark"><i class="ti ti-home-2"></i></span><span><b>LAO</b>event<b>Market</b></span></div>
      <p data-i18n="footerDesc">ເວັບໄຊລວບລວມງານຕະຫຼາດນັດໃນປະເທດລາວ ຊ່ວຍໃຫ້ທ່ານຄົ້ນຫາກິດຈະກຳໄດ້ງ່າຍຂຶ້ນ</p>
    </div>
    <div>
      <h3 data-i18n="quickMenu">ເມນູດ່ວນ</h3>
      <a href="index.php" data-i18n="home">ໜ້າຫຼັກ</a>
      <a href="#events" data-i18n="events">ງານຕະຫຼາດນັດ</a>
      <a href="#location" data-i18n="locations">ສະຖານທີ່</a>
      <a href="#categories" data-i18n="categories">ປະເພດງານ</a>
    </div>
    <div>
      <h3 data-i18n="support">ສະໜັບສະໜູນ</h3>
      <a href="#" data-i18n="faq">ຄຳຖາມທີ່ພົບເລື້ອຍ</a>
      <a href="#" data-i18n="guide">ຄູ່ມືການໃຊ້ງານ</a>
      <a href="#" data-i18n="privacy">ນະໂຍບາຍຄວາມເປັນສ່ວນຕົວ</a>
    </div>
    <div>
      <h3 data-i18n="contact">ຕິດຕໍ່ພວກເຮົາ</h3>
      <p><i class="ti ti-phone"></i> 020 99 326 122</p>
      <p><i class="ti ti-mail"></i> info@laoeventmarket.la</p>
    </div>
  </div>
  <div class="copyright">© <?= date('Y') ?> LAOeventMarket. All rights reserved.</div>
</footer>

<script>
const translations = {
  lo: {
    search: "ຄົ້ນຫາ...",
    loginRegister: "ເຂົ້າສູ່ລະບົບ / ສະໝັກສະມາຊິກ",
    home: "ໜ້າຫຼັກ",
    events: "ງານຕະຫຼາດນັດ",
    locations: "ສະຖານທີ່",
    categories: "ປະເພດງານ",
    organizers: "ຜູ້ຈັດງານ",
    news: "ຂ່າວສານ/ບົດຄວາມ",
    contact: "ຕິດຕໍ່",
    hero1: "ຮວບຮວມງານຕະຫຼາດນັດ",
    hero2: "ໃນທຸກທົ່ວປະເທດລາວ",
    heroDesc: "ຄົ້ນຫາງານຕະຫຼາດນັດ ໃຫ້ບ້ານທ່ານໄດ້ງ່າຍ ແລະ ວ່ອງໄວ ອັບເດດຂ່າວສານ ແລະ ກິດຈະກຳທີ່ໜ້າສົນໃຈທຸກມື້",
    eventSearch: "ຄົ້ນຫາງານຕະຫຼາດນັດ... (ຊື່ງານ, ສະຖານທີ່, ປະເພດ)",
    allRegions: "ທຸກພາກ",
    searchBtn: "ຄົ້ນຫາ",
    categoryTitle: "ປະເພດງານຕະຫຼາດນັດ",
    recommended: "ງານຕະຫຼາດນັດແນະນຳ",
    viewAll: "ເບິ່ງທັງໝົດ",
    noEvents: "ບໍ່ພົບງານຕະຫຼາດນັດ",
    tryAgain: "ລອງປ່ຽນຄຳຄົ້ນຫາ ຫຼື ຕົວກອງ",
    organizerQuestion: "ທ່ານເປັນຜູ້ຈັດງານບໍ?",
    organizerDesc: "ລົງທະບຽນງານຕະຫຼາດນັດຂອງທ່ານ ໃຫ້ຄົນຮູ້ຈັກງ່າຍຂຶ້ນ",
    freeRegister: "ລົງທະບຽນງານຟຣີ",
    users: "ຜູ້ໃຊ້",
    footerDesc: "ເວັບໄຊລວບລວມງານຕະຫຼາດນັດໃນປະເທດລາວ ຊ່ວຍໃຫ້ທ່ານຄົ້ນຫາກິດຈະກຳໄດ້ງ່າຍຂຶ້ນ",
    quickMenu: "ເມນູດ່ວນ",
    support: "ສະໜັບສະໜູນ",
    faq: "ຄຳຖາມທີ່ພົບເລື້ອຍ",
    guide: "ຄູ່ມືການໃຊ້ງານ",
    privacy: "ນະໂຍບາຍຄວາມເປັນສ່ວນຕົວ"
  },
  zh: {
    search: "搜索...",
    loginRegister: "登录 / 注册",
    home: "首页",
    events: "集市活动",
    locations: "地点",
    categories: "活动分类",
    organizers: "主办方",
    news: "新闻资讯",
    contact: "联系我们",
    hero1: "汇集老挝全国",
    hero2: "各大集市与活动",
    heroDesc: "轻松快速搜索附近的集市活动，每日更新最新资讯与精彩活动",
    eventSearch: "搜索集市活动... (活动名称、地点、类型)",
    allRegions: "所有地区",
    searchBtn: "搜索",
    categoryTitle: "活动分类",
    recommended: "推荐集市活动",
    viewAll: "查看全部",
    noEvents: "未找到相关活动",
    tryAgain: "请尝试更改搜索词或筛选条件",
    organizerQuestion: "您是活动主办方吗？",
    organizerDesc: "注册您的集市活动，让更多人了解与参与",
    freeRegister: "免费发布活动",
    users: "用户",
    footerDesc: "老挝集市活动信息汇总平台，助您轻松发现精彩活动",
    quickMenu: "快捷菜单",
    support: "支持与帮助",
    faq: "常见问题",
    guide: "隐私政策"
  },
  en: {
    search: "Search...",
    loginRegister: "Login / Register",
    home: "Home",
    events: "Events",
    locations: "Locations",
    categories: "Categories",
    organizers: "Organizers",
    news: "News",
    contact: "Contact",
    hero1: "Gathering Events & Markets",
    hero2: "All Across Laos",
    heroDesc: "Find event markets near you quickly and easily. Stay updated daily with exciting activities.",
    eventSearch: "Search events... (Name, location, category)",
    allRegions: "All Regions",
    searchBtn: "Search",
    categoryTitle: "Event Categories",
    recommended: "Recommended Events",
    viewAll: "View All",
    noEvents: "No events found",
    tryAgain: "Try changing your search keywords or filters",
    organizerQuestion: "Are you an event organizer?",
    organizerDesc: "Register your event market to reach more people easily",
    freeRegister: "Register Event Free",
    users: "Users",
    footerDesc: "A platform gathering event markets in Laos, helping you discover activities easily",
    quickMenu: "Quick Links",
    support: "Support",
    faq: "FAQ",
    guide: "User Guide",
    privacy: "Privacy Policy"
  }
};

const langBtn = document.getElementById('langBtn');
const langMenu = document.getElementById('langMenu');
const langText = document.getElementById('langText');

langBtn.addEventListener('click', () => langMenu.classList.toggle('show'));

document.querySelectorAll('[data-lang]').forEach(btn => {
  btn.addEventListener('click', () => {
    const lang = btn.getAttribute('data-lang');
    setLanguage(lang);
    langMenu.classList.remove('show');
  });
});

function setLanguage(lang) {
  const dict = translations[lang] || translations.lo;
  langText.innerText = lang === 'lo' ? 'ລາວ' : (lang === 'zh' ? '中文' : 'English');

  document.querySelectorAll('[data-i18n]').forEach(el => {
    const key = el.getAttribute('data-i18n');
    if (dict[key]) el.innerText = dict[key];
  });

  document.querySelectorAll('[data-i18n-placeholder]').forEach(el => {
    const key = el.getAttribute('data-i18n-placeholder');
    if (dict[key]) el.placeholder = dict[key];
  });

  localStorage.setItem('preferred_lang', lang);
}

const savedLang = localStorage.getItem('preferred_lang') || 'lo';
setLanguage(savedLang);

const slides = document.querySelectorAll('.hero-slider .slide');
let currentSlide = 0;
if (slides.length > 1) {
  setInterval(() => {
    slides[currentSlide].classList.remove('active');
    currentSlide = (currentSlide + 1) % slides.length;
    slides[currentSlide].classList.add('active');
  }, 3500);
}
</script>
</body>
</html>