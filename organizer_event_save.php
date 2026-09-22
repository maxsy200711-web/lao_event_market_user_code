<?php
require_once __DIR__ . "/config.php";
require_once __DIR__ . "/auth_organizer.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $event_id    = (int)($_POST['event_id'] ?? 0);
    $title       = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $start_date  = $_POST['start_date'] ?? '';
    $end_date    = $_POST['end_date'] ?? '';
    $time        = trim($_POST['time'] ?? '');
    $location    = trim($_POST['location'] ?? '');
    $province_id = (int)($_POST['province_id'] ?? 0);
    $category_id = (int)($_POST['category_id'] ?? 0);
    $map_url     = trim($_POST['map_url'] ?? '');
    $cropped_img = $_POST['cropped_image'] ?? '';

    if (empty($title) || empty($start_date) || empty($end_date) || empty($location) || !$province_id || !$category_id) {
        header("Location: organizer_event_form.php?id={$event_id}&error=1");
        exit;
    }

    $upload_dir = __DIR__ . "/uploads/";
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    $banner_filename = null;

    // ດຶງຂໍ້ມູນງານເກົ່າ ຖ້າເປັນການ Edit
    if ($event_id > 0) {
        $stmt_old = $pdo->prepare("SELECT banner_img FROM events WHERE event_id = ? AND organizer_id = ?");
        $stmt_old->execute([$event_id, $organizer_id]);
        $old_event = $stmt_old->fetch();
        if ($old_event) {
            $banner_filename = $old_event['banner_img']; // ເກັບຊື່ຮູບເກົ່າໄວ້ກ່ອນ
        }
    }

    // ຖ້າມີການອັບໂຫຼດ/ຕັດຮູບໃໝ່ ເຂົ້າ his
    if (!empty($cropped_img)) {
        // 1. ລຶບຮູບເກົ່າອອກຈາກ Server (ຖ້າມີ)
        if (!empty($banner_filename) && file_exists($upload_dir . $banner_filename)) {
            unlink($upload_dir . $banner_filename);
        }

        // 2. ແປງ Base64 ເປັນໄຟລ໌ຮູບໃໝ່
        $image_parts = explode(";base64,", $cropped_img);
        $image_type_aux = explode("image/", $image_parts[0]);
        $image_base64 = base64_decode($image_parts[1]);

        $new_filename = 'event_' . uniqid() . '_' . time() . '.jpg';
        $file_path = $upload_dir . $new_filename;

        file_put_contents($file_path, $image_base64);
        $banner_filename = $new_filename; // ປ່ຽນເປັນຊື່ຮູບໃໝ່
    }

    // ບັນທຶກ ຫຼື ອັບເດດຂໍ້ມູນໃນ Database
// ບັນທຶກ ຫຼື ອັບເດດຂໍ້ມູນໃນ Database
    if ($event_id > 0) {
        // ເພີ່ມ status = 'pending' ເພື່ອໃຫ້ Admin ອະນຸມັດຄືນໃໝ່ເມື່ອມີການແກ້ໄຂ
        $stmt = $pdo->prepare("UPDATE events SET 
            title = ?, 
            description = ?, 
            banner_img = ?, 
            location = ?, 
            map_url = ?, 
            start_date = ?, 
            end_date = ?, 
            time = ?, 
            category_id = ?, 
            province_id = ?, 
            status = 'pending' 
            WHERE event_id = ? AND organizer_id = ?");
            
        $stmt->execute([
            $title, 
            $description, 
            $banner_filename, 
            $location, 
            $map_url, 
            $start_date, 
            $end_date, 
            $time, 
            $category_id, 
            $province_id, 
            $event_id, 
            $organizer_id
        ]);
    } else {
        // ກໍລະນີສ້າງງານໃໝ່ ສະຖານະເປັນ 'pending' ຢູ່ແລ້ວ
        $stmt = $pdo->prepare("INSERT INTO events 
            (organizer_id, category_id, province_id, title, description, banner_img, location, map_url, start_date, end_date, time, status) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending')");
        $stmt->execute([
            $organizer_id, $category_id, $province_id, $title, $description, 
            $banner_filename, $location, $map_url, $start_date, $end_date, $time
        ]);
    }

    header("Location: organizer_events.php?success=1");
    exit;
}
$event_id = (int)($_POST['event_id'] ?? 0);

// ຖ້າເປັນການສ້າງງານໃໝ່ (ບໍ່ແມ່ນ Edit)
if ($event_id === 0) {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM events WHERE organizer_id = ?");
    $stmt->execute([$organizer_id]);
    if ($stmt->fetchColumn() >= 2) {
        die("ທ່ານນຳໃຊ້ໂຄຕາສ້າງງານຟຣີຄົບ 2 ງານແລ້ວ.");
    }
}