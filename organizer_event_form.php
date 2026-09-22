<?php
require_once __DIR__ . "/config.php";
require_once __DIR__ . "/auth_organizer.php";

$edit_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$event = [
    'event_id' => 0, 'title' => '', 'description' => '', 'banner_img' => '',
    'location' => '', 'map_url' => '', 'start_date' => '', 'end_date' => '',
    'time' => '', 'category_id' => '', 'province_id' => '',
];

// ກວດສອບຈຳນວນງານຂອງ Organizer ນີ້
$count_stmt = $pdo->prepare("SELECT COUNT(*) FROM events WHERE organizer_id = ?");
$count_stmt->execute([$organizer_id]);
$total_events = (int)$count_stmt->fetchColumn();

$limit_reached = (!$edit_id && $total_events >= 2);

if ($edit_id) {
    $stmt = $pdo->prepare("SELECT * FROM events WHERE event_id = ? AND organizer_id = ?");
    $stmt->execute([$edit_id, $organizer_id]);
    $found = $stmt->fetch();
    if (!$found) { header("Location: organizer_events.php"); exit; }
    $event = $found;
}

$categories = $pdo->query("SELECT * FROM categories ORDER BY category_name")->fetchAll();
$provinces  = $pdo->query("SELECT * FROM provinces ORDER BY province_name")->fetchAll();
?>
<!doctype html>
<html lang="lo">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title><?= $edit_id ? 'ແກ້ໄຂງານ' : 'ເພີ່ມງານໃໝ່' ?> | LAOeventMarket</title>
<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Lao:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">
<link rel="stylesheet" href="assets/organizer.css">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css">

<style>
.crop-modal-overlay {
    position: fixed; top: 0; left: 0; width: 100%; height: 100%;
    background: rgba(0, 0, 0, 0.7); display: none; justify-content: center; align-items: center; z-index: 9999;
}
.crop-modal-overlay.active { display: flex; }
.crop-modal-box {
    background: #fff; padding: 20px; border-radius: 12px; width: 90%; max-width: 600px;
}
.crop-container { width: 100%; max-height: 400px; overflow: hidden; margin-bottom: 15px; }
.crop-container img { max-width: 100%; }
.preview-box { margin-top: 10px; width: 100%; max-height: 200px; border-radius: 8px; display: none; object-fit: cover; }

/* Box ເຕືອນເມື່ອກາຍໂຄຕາ */
.paywall-box {
    background: #FFFBEB;
    border: 1px solid #FCD34D;
    border-radius: 12px;
    padding: 24px;
    text-align: center;
    color: #92400E;
}
.paywall-box i { font-size: 48px; color: #F59E0B; margin-bottom: 12px; }
.paywall-box h3 { font-size: 20px; font-weight: 700; margin-bottom: 8px; }
.paywall-box p { font-size: 14px; margin-bottom: 16px; color: #78350F; }
.btn-pay { background: #F59E0B; color: #fff; padding: 10px 20px; border-radius: 8px; text-decoration: none; font-weight: 600; display: inline-block; }
</style>
</head>
<body>
<?php include __DIR__ . "/organizer_topbar.php"; ?>
<div class="app-shell">
    <?php $active = 'add_event'; include __DIR__ . "/organizer_sidebar.php"; ?>

    <div class="content">
        <p class="page-title"><?= $edit_id ? 'ແກ້ໄຂງານຕະຫຼາດນັດ' : 'ສ້າງງານຕະຫຼາດນັດໃໝ່' ?></p>

        <?php if ($limit_reached): ?>
            <!-- ຖ້າກາຍໂຄຕາ 2 ງານ ໃຫ້ສະແດງ Box ແຈ້ງເຕືອນໃຫ້ອັບເກຣດ/ເຕີມເງິນ -->
            <div class="panel" style="max-width:640px">
                <div class="paywall-box">
                    <i class="ti ti-lock"></i>
                    <h3>ທ່ານນຳໃຊ້ໂຄຕາສ້າງງານຟຣີຄົບແລ້ວ (2/2 ງານ)</h3>
                    <p>ບັນຊີຂອງທ່ານສາມາດສ້າງງານໄດ້ຟຣີ 2 ງານ. ຖ້າຕ້ອງການສ້າງງານເພີ່ມ ກະລຸນາຊຳລະເງິນເພື່ອອັບເກຣດແພັກເກັດ.</p>
                    <a href="payment.php" class="btn-pay">ຊຳລະເງິນເພີ່ມງານ (500,000 ກີບ/ງານ)</a>
                </div>
            </div>
        <?php else: ?>

            <?php if (isset($_GET['error'])): ?><div class="alert alert-error">ກະລຸນາປ້ອນຂໍ້ມູນໃຫ້ຄົບຖ້ວນ</div><?php endif; ?>

            <div class="panel" style="max-width:640px">
                <form action="organizer_event_save.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="event_id" value="<?= (int) $event['event_id'] ?>">
                    <input type="hidden" name="cropped_image" id="cropped_image">

                    <label class="field-label">ຫົວຂໍ້ງານ</label>
                    <input class="f-input" type="text" name="title" required value="<?= htmlspecialchars($event['title']) ?>">

                    <label class="field-label">ລາຍລະອຽດງານ</label>
                    <textarea class="f-textarea" name="description"><?= htmlspecialchars($event['description']) ?></textarea>

                    <div class="form-row">
                        <div>
                            <label class="field-label">ວັນທີເລີ່ມ</label>
                            <input class="f-input" type="date" name="start_date" required value="<?= htmlspecialchars($event['start_date']) ?>">
                        </div>
                        <div>
                            <label class="field-label">ວັນທີສິ້ນສຸດ</label>
                            <input class="f-input" type="date" name="end_date" required value="<?= htmlspecialchars($event['end_date']) ?>">
                        </div>
                        <div>
                            <label class="field-label">ເວລາຈັດງານ</label>
                            <input class="f-input" type="text" name="time" placeholder="ຕົວຢ່າງ: 08:00 - 20:00" value="<?= htmlspecialchars($event['time']) ?>">
                        </div>
                        <div>
                            <label class="field-label">ສະຖານທີ່</label>
                            <input class="f-input" type="text" name="location" required value="<?= htmlspecialchars($event['location']) ?>">
                        </div>
                        <div>
                            <label class="field-label">ແຂວງ</label>
                            <select class="f-select" name="province_id" required>
                                <option value="">ເລືອກແຂວງ</option>
                                <?php foreach ($provinces as $p): ?>
                                <option value="<?= $p['province_id'] ?>" <?= $p['province_id'] == $event['province_id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($p['province_name']) ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label class="field-label">ໝວດໝູ່</label>
                            <select class="f-select" name="category_id" required>
                                <option value="">ເລືອກໝວດໝູ່</option>
                                <?php foreach ($categories as $c): ?>
                                <option value="<?= $c['category_id'] ?>" <?= $c['category_id'] == $event['category_id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($c['category_name']) ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <label class="field-label">ຮູບ Banner</label>
                    <label class="upload-box" for="banner_input">
                        <i class="ti ti-upload"></i>
                        ເລືອກຮູບ Banner ແລະ ຕັດຂອບ (JPG, PNG)
                        <?php if ($event['banner_img']): ?>
                            <span class="fname">ໄຟລ໌ປັດຈຸບັນ: <?= htmlspecialchars($event['banner_img']) ?></span>
                        <?php endif; ?>
                    </label>
                    <input type="file" id="banner_input" accept="image/*" style="display:none">
                    
                    <img id="previewImage" class="preview-box" src="" alt="Preview">

                    <label class="field-label" style="margin-top:12px;">ລິ້ງ Google Maps</label>
                    <input class="f-input" type="url" name="map_url" placeholder="https://maps.google.com/..." value="<?= htmlspecialchars($event['map_url']) ?>">

                    <div style="display:flex;gap:10px;margin-top:16px">
                        <button type="submit" class="btn btn-green"><i class="ti ti-device-floppy"></i> ບັນທຶກງານ</button>
                        <a href="organizer_events.php" class="btn btn-outline">ຍົກເລີກ</a>
                    </div>
                </form>
            </div>
        <?php endif; ?>
    </div>
</div>

<div class="crop-modal-overlay" id="cropModal">
    <div class="crop-modal-box">
        <p style="font-weight:600; margin-bottom:10px;">ຕັດຂອບຮູບ Banner (ອັດຕາສ່ວນ 16:9)</p>
        <div class="crop-container">
            <img id="imageToCrop" src="">
        </div>
        <div style="display:flex; justify-content:flex-end; gap:10px;">
            <button type="button" class="btn btn-outline" onclick="closeCropModal()">ຍົກເລີກ</button>
            <button type="button" class="btn btn-green" id="btnCrop">ຕັດຮູບ ແລະ ເລືອກ</button>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
<script>
let cropper = null;
const bannerInput = document.getElementById('banner_input');
const imageToCrop = document.getElementById('imageToCrop');
const cropModal = document.getElementById('cropModal');
const btnCrop = document.getElementById('btnCrop');
const croppedImageInput = document.getElementById('cropped_image');
const previewImage = document.getElementById('previewImage');

if (bannerInput) {
    bannerInput.addEventListener('change', function(e) {
        const files = e.target.files;
        if (files && files.length > 0) {
            const file = files[0];
            const reader = new FileReader();
            reader.onload = function(e) {
                imageToCrop.src = e.target.result;
                cropModal.classList.add('active');
                
                if (cropper) {
                    cropper.destroy();
                }
                cropper = new Cropper(imageToCrop, {
                    aspectRatio: 16 / 9,
                    viewMode: 1,
                });
            };
            reader.readAsDataURL(file);
        }
    });
}

if (btnCrop) {
    btnCrop.addEventListener('click', function() {
        if (!cropper) return;
        
        const canvas = cropper.getCroppedCanvas({
            width: 1280,
            height: 720
        });
        
        const base64Image = canvas.toDataURL('image/jpeg', 0.9);
        croppedImageInput.value = base64Image;
        
        previewImage.src = base64Image;
        previewImage.style.display = 'block';
        
        closeCropModal();
    });
}

function closeCropModal() {
    cropModal.classList.remove('active');
    if (cropper) {
        cropper.destroy();
        cropper = null;
    }
    if (bannerInput) bannerInput.value = '';
}
</script>
</body>
</html>