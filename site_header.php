<header class="top">
  <div class="topbar wrapper">
    <a class="brand" href="index.php">
      <?php include __DIR__ . '/site_logo.php'; ?>
    </a>

    <!-- ຊ່ອງຄົ້ນຫາເທິງ Header -->
    <form class="top-search" method="get" action="index.php#events">
      <i class="ti ti-search"></i>
      <input name="q" value="<?= htmlspecialchars($q ?? '') ?>" data-i18n-placeholder="search" placeholder="ຄົ້ນຫາ...">
      <?php if (($province ?? 0) > 0): ?>
        <input type="hidden" name="province" value="<?= $province ?>">
      <?php endif; ?>
      <?php if (($category ?? 0) > 0): ?>
        <input type="hidden" name="category" value="<?= $category ?>">
      <?php endif; ?>
      <?php if (($date ?? '') !== ''): ?>
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
    <a href="index.php#events"><span data-i18n="events">ງານຕະຫຼາດນັດ</span></a>
    <a href="index.php#categories"><span data-i18n="categories">ປະເພດງານ</span></a>
    <a href="blog.php"><span data-i18n="news">ຂ່າວສານ/ບົດຄວາມ</span></a>
    <a href="index.php#contact"><span data-i18n="contact">ຕິດຕໍ່</span></a>
  </nav>
</header>
