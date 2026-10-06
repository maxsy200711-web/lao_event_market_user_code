<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/auth_admin.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !hash_equals($_SESSION['admin_csrf'] ?? '', (string)($_POST['csrf'] ?? ''))) { http_response_code(400); exit('Invalid request.'); }
$id=(int)($_POST['event_id']??0); $status=$_POST['status']??'';
if(!$id || !in_array($status,['pending','approved','rejected'],true)){ header('Location: admin_events.php'); exit; }
$stmt=$pdo->prepare('UPDATE events SET organizer_id=?,category_id=?,province_id=?,title=?,description=?,banner_img=?,location=?,map_url=?,start_date=?,end_date=?,time=?,status=? WHERE event_id=?');
$stmt->execute([(int)($_POST['organizer_id']??0),(int)($_POST['category_id']??0),(int)($_POST['province_id']??0),trim($_POST['title']??''),trim($_POST['description']??''),trim($_POST['banner_img']??''),trim($_POST['location']??''),trim($_POST['map_url']??''),$_POST['start_date']??'',$_POST['end_date']??'',trim($_POST['time']??''),$status,$id]);
header('Location: admin_events.php?saved=1'); exit;
