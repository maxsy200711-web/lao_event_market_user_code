<?php
require_once __DIR__ . "/config.php";
require_once __DIR__ . "/auth_organizer.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: organizer_stalls.php");
    exit;
}

$event_id     = (int) ($_POST['event_id'] ?? 0);
$stall_name   = trim($_POST['stall_name'] ?? '');
$stall_number = trim($_POST['stall_number'] ?? '');
$categories   = trim($_POST['categories'] ?? '');
$description  = trim($_POST['description'] ?? '');
$contact_info = trim($_POST['contact_info'] ?? '');

// ---- ຢືນຢັນວ່າ event ນີ້ເປັນຂອງ organizer ຄົນນີ້ ----
$stmt = $pdo->prepare("SELECT event_id FROM events WHERE event_id = ? AND organizer_id = ?");
$stmt->execute([$event_id, $organizer_id]);
if (!$stmt->fetch() || $stall_name === '') {
    header("Location: organizer_stalls.php?event_id=$event_id");
    exit;
}

// ---- ອັບໂຫຼດຮູບ (ຖ້າມີ) ----
$image = null;
if (!empty($_FILES['image']['name']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $uploadDir = __DIR__ . '/uploads/';
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
    $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
    if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'], true)) {
        $image = 'stall_' . uniqid() . '.' . $ext;
        move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $image);
    }
}

$stmt = $pdo->prepare("INSERT INTO event_stalls (event_id, stall_name, stall_number, categories, description, contact_info, image)
                        VALUES (?,?,?,?,?,?,?)");
$stmt->execute([$event_id, $stall_name, $stall_number, $categories, $description, $contact_info, $image]);

header("Location: organizer_stalls.php?event_id=$event_id&saved=1");
exit;
