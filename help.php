<!DOCTYPE html>
<html lang="lo">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ຄູ່ມືການໃຊ້ງານ - LAOeventMarket</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Font (Noto Sans Lao) -->
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Lao:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">
    <link rel="stylesheet" href="assets/site-shell.css">
    <style>
        body {
            font-family: 'Noto Sans Lao', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-800">
    <?php include __DIR__ . '/site_header.php'; ?>

    <!-- Header Banner -->
    <section class="bg-emerald-600 text-white py-12 px-4 text-center">
        <div class="max-w-4xl mx-auto">
            <h1 class="text-3xl md:text-4xl font-bold mb-3">📖 ຄູ່ມືການໃຊ້ງານເວັບໄຊ້</h1>
            <p class="text-emerald-100 text-base md:text-lg">ຮຽນຮູ້ວິທີການໃຊ້ງານ LAOeventMarket ເພື່ອຄົ້ນຫາ ຫຼື ລົງໂຄສະນາງານຕະຫຼາດນັດໄດ້ຢ່າງງ່າຍດາຍ</p>
        </div>
    </section>

    <!-- Main Content Container -->
    <main class="max-w-5xl mx-auto px-4 py-10">

        <!-- Quick Navigation -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 mb-8">
            <h2 class="text-lg font-bold mb-4 text-gray-700"><i class="fa-solid fa-list-ul text-emerald-600 mr-2"></i> ຫົວຂໍ້ຄູ່ມື</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 text-sm">
                <a href="#search-guide" class="p-3 bg-emerald-50 text-emerald-700 rounded-lg font-medium hover:bg-emerald-100 transition flex items-center gap-2">
                    <i class="fa-solid fa-magnifying-glass"></i> 1. ການຄົ້ນຫາງານ
                </a>
                <a href="#auth-guide" class="p-3 bg-emerald-50 text-emerald-700 rounded-lg font-medium hover:bg-emerald-100 transition flex items-center gap-2">
                    <i class="fa-solid fa-user-plus"></i> 2. ການສະໝັກສະມາຊິກ
                </a>
                <a href="#organizer-guide" class="p-3 bg-emerald-50 text-emerald-700 rounded-lg font-medium hover:bg-emerald-100 transition flex items-center gap-2">
                    <i class="fa-solid fa-calendar-plus"></i> 3. ສຳລັບຜູ້ຈັດງານ
                </a>
                <a href="#faq" class="p-3 bg-emerald-50 text-emerald-700 rounded-lg font-medium hover:bg-emerald-100 transition flex items-center gap-2">
                    <i class="fa-solid fa-circle-question"></i> 4. ຄຳຖາມທີ່ພົບເລື້ອຍ
                </a>
            </div>
        </div>

        <!-- Section 1: Search Guide -->
        <section id="search-guide" class="bg-white p-6 md:p-8 rounded-xl shadow-sm border border-gray-100 mb-8 scroll-mt-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center font-bold text-lg">1</div>
                <h2 class="text-xl font-bold text-gray-800">ວິທີການຄົ້ນຫາງານຕະຫຼາດນັດ (ສຳລັບຜູ້ເຂົ້າຊົມ)</h2>
            </div>
            <p class="text-gray-600 mb-6 md:pl-13">ທ່ານສາມາດຄົ້ນຫາງານຕະຫຼາດນັດທີ່ທ່ານສົນໃຈໄດ້ຢ່າງງ່າຍດາຍດ້ວຍ 3 ວິທີດັ່ງນີ້:</p>
            
            <div class="space-y-6 md:pl-6">
                <!-- Search Methods -->
                <div class="flex items-start gap-3">
                    <i class="fa-solid fa-check-circle text-emerald-500 mt-1 text-lg"></i>
                    <div>
                        <strong class="text-gray-800 text-base">ຄົ້ນຫາດ້ວຍຄຳສັບ (Keyword):</strong>
                        <p class="text-sm text-gray-600 mt-0.5">ພິມຊື່ງານ, ເມືອງ, ແຂວງ ຫຼື ສະຖານທີ່ ໃສ່ໃນຊ່ອງຄົ້ນຫາ ແລ້ວກົດປຸ່ມ <strong>"ຄົ້ນຫາ"</strong></p>
                    </div>
                </div>

                <div class="flex items-start gap-3">
                    <i class="fa-solid fa-check-circle text-emerald-500 mt-1 text-lg"></i>
                    <div>
                        <strong class="text-gray-800 text-base">ຄົ້ນຫາຕາມວັນທີ:</strong>
                        <p class="text-sm text-gray-600 mt-0.5">ເລືອກວັນທີທີ່ທ່ານຕ້ອງການໄປທ່ຽວຊົມໃນຊ່ອງ <strong>"dd/mm/yyyy"</strong> ເພື່ອເບິ່ງງານທີ່ຈັດໃນມື້ນັ້ນ</p>
                    </div>
                </div>

                <div class="flex items-start gap-3">
                    <i class="fa-solid fa-check-circle text-emerald-500 mt-1 text-lg"></i>
                    <div class="w-full">
                        <strong class="text-gray-800 text-base">ຄົ້ນຫາຕາມປະເພດງານຕະຫຼາດນັດ (Categories):</strong>
                        <p class="text-sm text-gray-600 mt-0.5 mb-4">ທ່ານສາມາດເລືອກເບິ່ງງານຕະຫຼາດນັດຕາມໝວດໝູ່ທີ່ທ່ານສົນໃຈໄດ້ທັງໝົດ 10 ປະເພດ ດັ່ງນີ້:</p>
                        
                        <!-- 10 Categories Detailed Grid -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
                            <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg border border-gray-100 hover:border-emerald-200 transition">
                                <span class="text-2xl">🏺</span>
                                <div>
                                    <div class="font-bold text-gray-800">Antiques & Collectibles</div>
                                    <div class="text-xs text-gray-500">ຂອງເກົ່າ ແລະ ຂອງສະສົມ</div>
                                </div>
                            </div>

                            <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg border border-gray-100 hover:border-emerald-200 transition">
                                <span class="text-2xl">🚗</span>
                                <div>
                                    <div class="font-bold text-gray-800">Automotive & Vehicles</div>
                                    <div class="text-xs text-gray-500">ຍານພາຫະນະ ແລະ ອາໄຫຼ່</div>
                                </div>
                            </div>

                            <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg border border-gray-100 hover:border-emerald-200 transition">
                                <span class="text-2xl">🎭</span>
                                <div>
                                    <div class="font-bold text-gray-800">Events & Entertainment</div>
                                    <div class="text-xs text-gray-500">ງານອີເວັ້ນ ແລະ ຄວາມບັນເທີງ</div>
                                </div>
                            </div>

                            <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg border border-gray-100 hover:border-emerald-200 transition">
                                <span class="text-2xl">👗</span>
                                <div>
                                    <div class="font-bold text-gray-800">Fashion & Clothing</div>
                                    <div class="text-xs text-gray-500">ເສື້ອຜ້າ ແລະ ແຟຊັ່ນ</div>
                                </div>
                            </div>

                            <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg border border-gray-100 hover:border-emerald-200 transition">
                                <span class="text-2xl">🍲</span>
                                <div>
                                    <div class="font-bold text-gray-800">Food & Beverages</div>
                                    <div class="text-xs text-gray-500">ອາຫານ ແລະ ເຄື່ອງດື່ມ</div>
                                </div>
                            </div>

                            <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg border border-gray-100 hover:border-emerald-200 transition">
                                <span class="text-2xl">🎁</span>
                                <div>
                                    <div class="font-bold text-gray-800">Handicrafts & Gifts</div>
                                    <div class="text-xs text-gray-500">ຫັດຖະກຳ ແລະ ຂອງຂວັນ</div>
                                </div>
                            </div>

                            <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg border border-gray-100 hover:border-emerald-200 transition">
                                <span class="text-2xl">💻</span>
                                <div>
                                    <div class="font-bold text-gray-800">IT & Electronics</div>
                                    <div class="text-xs text-gray-500">ອຸປະກອນໄອທີ ແລະ ເອເລັກໂຕຣນິກ</div>
                                </div>
                            </div>

                            <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg border border-gray-100 hover:border-emerald-200 transition">
                                <span class="text-2xl">🌾</span>
                                <div>
                                    <div class="font-bold text-gray-800">OTOP & Local Products</div>
                                    <div class="text-xs text-gray-500">ຜະລິດຕະພັນທ້ອງຖິ່ນ/OTOP</div>
                                </div>
                            </div>

                            <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg border border-gray-100 hover:border-emerald-200 transition">
                                <span class="text-2xl">🪴</span>
                                <div>
                                    <div class="font-bold text-gray-800">Plants & Gardening</div>
                                    <div class="text-xs text-gray-500">ຕົ້ນໄມ້ ແລະ ອຸປະກອນເຮັດສວນ</div>
                                </div>
                            </div>

                            <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg border border-gray-100 hover:border-emerald-200 transition">
                                <span class="text-2xl">📦</span>
                                <div>
                                    <div class="font-bold text-gray-800">Others</div>
                                    <div class="text-xs text-gray-500">ປະເພດອື່ນໆ</div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </section>

        <!-- Section 2: Auth Guide -->
        <section id="auth-guide" class="bg-white p-6 md:p-8 rounded-xl shadow-sm border border-gray-100 mb-8 scroll-mt-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center font-bold text-lg">2</div>
                <h2 class="text-xl font-bold text-gray-800">ວິທີການສະໝັກສະມາຊິກ ແລະ ເຂົ້າສູ່ລະບົບ</h2>
            </div>
            <div class="space-y-3 text-gray-600 md:pl-6">
                <p>1. ກົດປຸ່ມ <strong>"ເຂົ້າສູ່ລະບົບ / ສະໝັກສະມາຊິກ"</strong> ຢູ່ແຈເທິງຂວາມືຂອງໜ້າເວັບໄຊ.</p>
                <p>2. ຖ້າຍັງບໍ່ທັນມີບັນຊີ: ກົດ <strong>"ສະໝັກສະມາຊິກ"</strong> ແລ້ວກອກຂໍ້ມູນ ອີເມວ, ຊື່ຜູ້ໃຊ້ ແລະ ລະຫັດຜ່ານ.</p>
                <p>3. ຖ້າມີບັນຊີແລ້ວ: ກອກ ອີເມວ ແລະ ລະຫັດຜ່ານ ເພື່ອເຂົ້າສູ່ລະບົບໄດ້ທັນທີ.</p>
            </div>
        </section>

        <!-- Section 3: Organizer Guide -->
        <section id="organizer-guide" class="bg-white p-6 md:p-8 rounded-xl shadow-sm border border-gray-100 mb-8 scroll-mt-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center font-bold text-lg">3</div>
                <h2 class="text-xl font-bold text-gray-800">ວິທີການລົງທະບຽນສ້າງງານຕະຫຼາດນັດ (ສຳລັບຜູ້ຈັດງານ)</h2>
            </div>
            <p class="text-gray-600 mb-4 md:pl-6">ຖ້າທ່ານເປັນຜູ້ຈັດງານ ແລະ ຕ້ອງການໂປຣໂມດງານຕະຫຼາດນັດຂອງທ່ານໃຫ້ເປັນທີ່ຮູ້ຈັກ:</p>
            
            <ol class="list-decimal list-inside space-y-2 text-gray-600 md:pl-6 text-sm md:text-base">
                <li>ເຂົ້າສູ່ລະບົບດ້ວຍບັນຊີຜູ້ຈັດງານ.</li>
                <li>ກົດປຸ່ມ <strong>"ລົງທະບຽນງານຕະຫຼາດນັດ"</strong> ຢູ່ສ່ວນລຸ່ມຂອງເວັບໄຊ.</li>
                <li>ກອກຂໍ້ມູນລາຍລະອຽດຂອງງານ:
                    <ul class="list-disc list-inside pl-6 mt-1 space-y-1 text-gray-500 text-sm">
                        <li>ຊື່ງານຕະຫຼາດນັດ ແລະ ເລືອກປະເພດງານ (ຈາກ 10 ປະເພດຂ້າງເທິງ)</li>
                        <li>ວັນທີ, ເວລາ ເປີດ-ປິດ ງານ</li>
                        <li>ສະຖານທີ່ຈັດງານ (ແຂວງ, ເມືອງ, ແຜນທີ່)</li>
                        <li>ຮູບພາບໂປສເຕີ ແລະ ເນື້ອໃນລາຍລະອຽດງານ</li>
                    </ul>
                </li>
                <li>ກົດປຸ່ມ <strong>"ບັນທຶກ/ສົ່ງຂໍ້ມູນ"</strong> ເພື່ອໃຫ້ທີມງານກວດເຊັກ ແລະ ອະນຸມັດ.</li>
            </ol>
        </section>

        <!-- Section 4: FAQ -->
        <section id="faq" class="bg-white p-6 md:p-8 rounded-xl shadow-sm border border-gray-100 mb-8 scroll-mt-6">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center font-bold text-lg">4</div>
                <h2 class="text-xl font-bold text-gray-800">ຄຳຖາມທີ່ພົບເລື້ອຍ (FAQ)</h2>
            </div>

            <div class="space-y-4 md:pl-6">
                <div class="border-b border-gray-100 pb-3">
                    <h3 class="font-semibold text-gray-800 mb-1">❓ ການລົງໂຄສະນາງານຕະຫຼາດນັດມີຄ່າໃຊ້ຈ່າຍບໍ?</h3>
                    <p class="text-sm text-gray-600">💡 ທ່ານສາມາດລົງຂໍ້ມູນງານຕະຫຼາດນັດໄດ້ **ຟຣີ** ໂດຍບໍ່ມີຄ່າໃຊ້ຈ່າຍ (ຫຼື ເບິ່ງແພັກເກດໂປຣໂມດເພີ່ມເຕີມໄດ້ໃນລະບົບ).</p>
                </div>
                <div class="border-b border-gray-100 pb-3">
                    <h3 class="font-semibold text-gray-800 mb-1">❓ ຖ້າຕ້ອງການແກ້ໄຂຂໍ້ມູນງານ ຕ້ອງເຮັດແນວໃດ?</h3>
                    <p class="text-sm text-gray-600">💡 ທ່ານສາມາດເຂົ້າໄປທີ່ເມນູ "ຈັດການງານຂອງຂ້ອຍ" ເພື່ອແກ້ໄຂຂໍ້ມູນ ຫຼື ຕິດຕໍ່ທີມງານຊ່ວຍເຫຼືອ.</p>
                </div>
            </div>
        </section>

        <!-- Contact Support Box -->
        <div class="bg-slate-900 text-white p-6 md:p-8 rounded-xl flex flex-col md:flex-row items-center justify-between gap-6">
            <div>
                <h3 class="text-xl font-bold mb-2">ຕ້ອງການຄວາມຊ່ວຍເຫຼືອເພີ່ມເຕີມ?</h3>
                <p class="text-slate-400 text-sm">ຖ້າທ່ານພົບປັບຫາໃນການໃຊ້ງານ ທີມງານເຮົາພ້ອມໃຫ້ຄຳປຶກສາຕະຫຼອດເວລາ</p>
            </div>
            <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
                <a href="tel:02099326122" class="bg-emerald-600 hover:bg-emerald-500 text-white px-5 py-2.5 rounded-lg text-sm font-medium transition text-center flex items-center justify-center gap-2">
                    <i class="fa-solid fa-phone"></i> 020 99 326 122
                </a>
                <a href="mailto:info@laoeventmarket.la" class="bg-slate-800 hover:bg-slate-700 text-white px-5 py-2.5 rounded-lg text-sm font-medium transition text-center flex items-center justify-center gap-2">
                    <i class="fa-solid fa-envelope"></i> ສົ່ງອີເມວ
                </a>
            </div>
        </div>

    </main>

    <?php include __DIR__ . '/site_footer.php'; ?>
    <script src="assets/site-i18n.js"></script>
</body>
</html>
