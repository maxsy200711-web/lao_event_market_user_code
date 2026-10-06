<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/auth_admin.php';
$id=(int)($_GET['id']??0);
$stmt=$pdo->prepare('SELECT * FROM events WHERE event_id=?'); $stmt->execute([$id]); $event=$stmt->fetch();
if(!$event){ header('Location: admin_events.php'); exit; }
$organizers=$pdo->query('SELECT organizer_id,organizer_name,company_name FROM organizers ORDER BY organizer_name')->fetchAll();
$categories=$pdo->query('SELECT category_id,category_name,category_name_en FROM categories ORDER BY category_name')->fetchAll();
$provinces=$pdo->query('SELECT province_id,province_name FROM provinces ORDER BY province_name')->fetchAll();
if(empty($_SESSION['admin_csrf'])) $_SESSION['admin_csrf']=bin2hex(random_bytes(32));
?>
<!doctype html><html lang="lo"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Edit Event | Admin</title><link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Lao:wght@400;500;600;700&display=swap" rel="stylesheet"><link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css"><link rel="stylesheet" href="assets/admin.css?v=20261002-pagination"></head><body>
<?php include __DIR__ . '/admin_topbar.php'; ?><div class="app-shell"><?php $active='events'; include __DIR__ . '/admin_sidebar.php'; ?><main class="content"><p class="page-title" style="margin-bottom:16px">Edit Event #<?= $id ?></p><section class="panel" style="max-width:850px"><form method="post" action="admin_event_save.php"><input type="hidden" name="csrf" value="<?= htmlspecialchars($_SESSION['admin_csrf']) ?>"><input type="hidden" name="event_id" value="<?= $id ?>">
<label class="field-label">Title</label><input class="f-input" name="title" required maxlength="200" value="<?= htmlspecialchars($event['title']) ?>">
<label class="field-label">Organizer</label><select class="f-input" name="organizer_id" required><?php foreach($organizers as $o): ?><option value="<?= (int)$o['organizer_id'] ?>" <?= (int)$event['organizer_id']===(int)$o['organizer_id']?'selected':'' ?>><?= htmlspecialchars($o['company_name']?:$o['organizer_name']) ?></option><?php endforeach; ?></select>
<label class="field-label">Category</label><select class="f-input" name="category_id" required><?php foreach($categories as $c): ?><option value="<?= (int)$c['category_id'] ?>" <?= (int)$event['category_id']===(int)$c['category_id']?'selected':'' ?>><?= htmlspecialchars($c['category_name'].(!empty($c['category_name_en'])?' ('.$c['category_name_en'].')':'')) ?></option><?php endforeach; ?></select>
<label class="field-label">Province</label><select class="f-input" name="province_id" required><?php foreach($provinces as $p): ?><option value="<?= (int)$p['province_id'] ?>" <?= (int)$event['province_id']===(int)$p['province_id']?'selected':'' ?>><?= htmlspecialchars($p['province_name']) ?></option><?php endforeach; ?></select>
<label class="field-label">Description</label><textarea class="f-input" name="description" rows="5"><?= htmlspecialchars($event['description']??'') ?></textarea>
<label class="field-label">Location</label><input class="f-input" name="location" required maxlength="255" value="<?= htmlspecialchars($event['location']) ?>">
<label class="field-label">Map URL</label><input class="f-input" type="url" name="map_url" value="<?= htmlspecialchars($event['map_url']??'') ?>">
<div style="display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:12px"><div><label class="field-label">Start date</label><input class="f-input" type="date" name="start_date" required value="<?= htmlspecialchars($event['start_date']) ?>"></div><div><label class="field-label">End date</label><input class="f-input" type="date" name="end_date" required value="<?= htmlspecialchars($event['end_date']) ?>"></div><div><label class="field-label">Time</label><input class="f-input" name="time" maxlength="50" value="<?= htmlspecialchars($event['time']??'') ?>"></div></div>
<label class="field-label">Banner image path / URL</label><input class="f-input" name="banner_img" maxlength="255" value="<?= htmlspecialchars($event['banner_img']??'') ?>">
<label class="field-label">Publication status</label><select class="f-input" name="status"><option value="pending" <?= $event['status']==='pending'?'selected':'' ?>>Pending</option><option value="approved" <?= $event['status']==='approved'?'selected':'' ?>>Approved</option><option value="rejected" <?= $event['status']==='rejected'?'selected':'' ?>>Rejected</option></select>
<button class="btn" style="background:#10B981;color:#fff" type="submit">Save Event</button> <a class="btn btn-outline" href="admin_events.php">Cancel</a></form></section></main></div></body></html>
