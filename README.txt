LAOeventMarket - User Frontend
================================

Technology:
- PHP 8+
- MySQL / MariaDB
- PDO
- HTML/CSS/JavaScript
- Noto Sans Lao / Noto Sans SC
- Tabler Icons CDN

1) Create the database using the SQL provided by the project.
2) Put this folder inside XAMPP:
   C:\xampp\htdocs\lao_event_market_user
3) Edit config.php if MySQL username/password differs.
4) Make sure XAMPP Apache + MySQL are running.
5) Open:
   http://localhost/lao_event_market_user/

IMPORTANT:
- events shown on the user page come only from events.status='approved'.
- banner_img is read from the events table. If it contains "mybanner.jpg",
  the page looks for uploads/mybanner.jpg.
- It also accepts a full http/https image URL.
- The database schema supplied by the project has only ONE name field for
  event/category/province, so the 3-language switch translates the interface
  labels only. Event/category/province data itself remains whatever language
  is stored in MySQL. For fully multilingual DB content, add *_lo, *_zh, *_en
  columns or translation tables later.

Password:
- register.php stores passwords using password_hash().
- login.php checks them using password_verify().
- Existing users whose passwords are plain text will not pass password_verify()
  until their passwords are migrated to password_hash().
