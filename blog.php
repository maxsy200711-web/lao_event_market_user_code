<!DOCTYPE html>
<html lang="lo">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ຂ່າວສານ ແລະ ບົດຄວາມ - LAOeventMarket</title>
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
    <section class="bg-emerald-600 text-white py-12 px-4 text-center relative overflow-hidden">
        <div class="max-w-4xl mx-auto relative z-10">
            <h1 class="text-3xl md:text-4xl font-bold mb-3">📰 ຂ່າວສານ & ບົດຄວາມ LAOeventMarket</h1>
            <p class="text-emerald-100 text-base md:text-lg mb-6">ສູນລວມຂ່າວສານ, ເທຣນງານຕະຫຼາດນັດ, ເທັກນິກການຈັດງານ ແລະ ໄຮໄລ້ກິດຈະກຳທີ່ໜ້າສົນໃຈໃນທົ່ວປະເທດລາວ</p>

            <!-- Search Bar inside Banner -->
            <div class="max-w-xl mx-auto flex items-center bg-white rounded-lg p-1 shadow-md">
                <input type="text" placeholder="ຄົ້ນຫາບົດຄວາມ ຫຼື ຂ່າວສານ..." class="w-full px-4 py-2 text-gray-700 focus:outline-none text-sm md:text-base">
                <button class="bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2 rounded-md font-medium text-sm transition">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
            </div>
        </div>
    </section>

    <!-- Main Content Container -->
    <main class="max-w-6xl mx-auto px-4 py-10">

        <!-- Highlight Featured Article (ບົດຄວາມເດັ່ນປະຈຳເດືອນ) -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-12 grid grid-cols-1 lg:grid-cols-12">
            <div class="lg:col-span-7 h-64 lg:h-auto relative overflow-hidden">
                <img src="https://images.unsplash.com/photo-1531058240690-006c446962d8?auto=format&fit=crop&q=80&w=1000" alt="Featured Article" class="w-full h-full object-cover hover:scale-105 transition duration-500">
                <span class="absolute top-4 left-4 bg-emerald-600 text-white text-xs px-3 py-1.5 rounded-full font-semibold shadow">🌟 ບົດຄວາມແນະນຳ</span>
            </div>
            <div class="lg:col-span-5 p-6 md:p-8 flex flex-col justify-between">
                <div>
                    <div class="flex items-center gap-3 text-xs text-gray-500 mb-3">
                        <span class="bg-emerald-50 text-emerald-700 px-2.5 py-1 rounded-md font-medium"><i class="fa-regular fa-folder mr-1"></i> ເທັກນິກຜູ້ຈັດງານ</span>
                        <span><i class="fa-regular fa-calendar mr-1"></i> 25 ມັງກອນ 2026</span>
                    </div>
                    <h2 class="text-xl md:text-2xl font-bold text-gray-800 mb-3 hover:text-emerald-600 transition cursor-pointer leading-snug">
                        5 ເທັກນິກການຈັດງານຕະຫຼາດນັດ ໃຫ້ດຶງດູດຜູ້ຄົນ ແລະ ເພີ່ມຍອດຂາຍທະລຸເປົ້າ
                    </h2>
                    <p class="text-gray-600 text-sm leading-relaxed mb-4">
                        ການຈັດງານຕະຫຼາດນັດໃຫ້ປະສົບຜົນເລັດ ບໍ່ພຽງແຕ່ມີສະຖານທີ່ດີເທົ່ານັ້ນ ແຕ່ຍັງຕ້ອງມີການວາງແຜນການໂປຣໂມດ, ການຈັດໂຊນຮ້ານຄ້າ, ການສ້າງບັນຍາກາດ ແລະ ການດຶງດູດຮ້ານຄ້າທີ່ມີຄຸນນະພາບ...
                    </p>
                </div>
                <div class="pt-4 border-t border-gray-100 flex items-center justify-between">
                    <span class="text-xs text-gray-400"><i class="fa-regular fa-eye mr-1"></i> ເຂົ້າຊົມ 1,250 ຄັ້ງ</span>
                    <a href="#" class="inline-flex items-center text-emerald-600 font-bold text-sm hover:translate-x-1 transition gap-1">
                        ອ່ານບົດຄວາມລະອຽດ <i class="fa-solid fa-arrow-right text-xs"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Layout Grid: Left Content (Articles) + Right Sidebar -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <!-- Left Column: Article List (2 Columns in Grid) -->
            <div class="lg:col-span-2">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                        <i class="fa-solid fa-newspaper text-emerald-600"></i> ບົດຄວາມທັງໝົດ
                    </h3>
                    <span class="text-xs text-gray-500">ສະແດງ 1 - 6 ຈາກ 24 ບົດຄວາມ</span>
                </div>

                <!-- Articles Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">

                    <!-- Article Card 1 -->
                    <article class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition flex flex-col justify-between">
                        <div>
                            <div class="h-48 bg-gray-200 overflow-hidden relative">
                                <img src="https://images.unsplash.com/photo-1488459716781-31db52582fe9?auto=format&fit=crop&q=80&w=500" alt="Article 1" class="w-full h-full object-cover hover:scale-105 transition duration-300">
                                <span class="absolute bottom-2 left-2 bg-black/60 text-white text-[10px] px-2 py-0.5 rounded">OTOP & ຫັດຖະກຳ</span>
                            </div>
                            <div class="p-5">
                                <div class="flex items-center gap-2 text-xs text-gray-400 mb-2">
                                    <span><i class="fa-regular fa-calendar mr-1"></i> 20 ມັງກອນ 2026</span>
                                    <span>•</span>
                                    <span><i class="fa-regular fa-clock mr-1"></i> ອ່ານ 3 ນາທີ</span>
                                </div>
                                <h4 class="font-bold text-gray-800 text-base mb-2 hover:text-emerald-600 transition cursor-pointer line-clamp-2">
                                    ລວມ 10 ງານຕະຫຼາດນັດ OTOP ແລະ ຫັດຖະກຳ ທີ່ບໍ່ຄວນພາດໃນປີນີ້
                                </h4>
                                <p class="text-gray-600 text-sm line-clamp-2 leading-relaxed">
                                    ສຳລັບໃຜທີ່ມັກສິນຄ້າຫັດຖະກຳລາວ, ຜ້າໄໝ, ແລະ ຜະລິດຕະພັນທ້ອງຖິ່ນ ເຮົາໄດ້ລວມງານໃຫຍ່ທີ່ກຳລັງຈະຈັດຂຶ້ນມາໃຫ້ແລ້ວ.
                                </p>
                            </div>
                        </div>
                        <div class="p-5 pt-0 flex items-center justify-between border-t border-gray-50 mt-2">
                            <span class="text-xs text-gray-400"><i class="fa-regular fa-eye mr-1"></i> 850</span>
                            <a href="#" class="text-emerald-600 font-semibold text-sm hover:underline flex items-center gap-1">
                                ອ່ານຕໍ່ <i class="fa-solid fa-angle-right text-xs"></i>
                            </a>
                        </div>
                    </article>

                    <!-- Article Card 2 -->
                    <article class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition flex flex-col justify-between">
                        <div>
                            <div class="h-48 bg-gray-200 overflow-hidden relative">
                                <img src="https://images.unsplash.com/photo-1555396273-367ea4eb4db5?auto=format&fit=crop&q=80&w=500" alt="Article 2" class="w-full h-full object-cover hover:scale-105 transition duration-300">
                                <span class="absolute bottom-2 left-2 bg-black/60 text-white text-[10px] px-2 py-0.5 rounded">ອາຫານ & ເຄື່ອງດື່ມ</span>
                            </div>
                            <div class="p-5">
                                <div class="flex items-center gap-2 text-xs text-gray-400 mb-2">
                                    <span><i class="fa-regular fa-calendar mr-1"></i> 15 ມັງກອນ 2026</span>
                                    <span>•</span>
                                    <span><i class="fa-regular fa-clock mr-1"></i> ອ່ານ 4 ນາທີ</span>
                                </div>
                                <h4 class="font-bold text-gray-800 text-base mb-2 hover:text-emerald-600 transition cursor-pointer line-clamp-2">
                                    ພາໄປເລາະ Food Fest: ລວມຮ້ານເດັດ ແລະ ເມນູຍອດຮິດທີ່ຕ້ອງລອງ
                                </h4>
                                <p class="text-gray-600 text-sm line-clamp-2 leading-relaxed">
                                    ສາຍກິນຫ້າມພາດ! ເກັບບັນຍາກາດງານຕະຫຼາດນັດອາຫານ ພ້ອມແນະນຳເມນູເດັດທີ່ມາແລ້ວຕ້ອງໄດ້ຊິມ.
                                </p>
                            </div>
                        </div>
                        <div class="p-5 pt-0 flex items-center justify-between border-t border-gray-50 mt-2">
                            <span class="text-xs text-gray-400"><i class="fa-regular fa-eye mr-1"></i> 1,120</span>
                            <a href="#" class="text-emerald-600 font-semibold text-sm hover:underline flex items-center gap-1">
                                ອ່ານຕໍ່ <i class="fa-solid fa-angle-right text-xs"></i>
                            </a>
                        </div>
                    </article>

                    <!-- Article Card 3 (ຮູບພາບກ່ຽວກັບການຕະຫຼາດ ແລະ ການວາງແຜນ) -->
                    <article class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition flex flex-col justify-between">
                        <div>
                            <div class="h-48 bg-gray-100 overflow-hidden relative">
                                <!-- ຮູບພາບ Digital Marketing & Strategy -->
                                <img src="https://images.unsplash.com/photo-1460925895917-afdab827c52f?auto=format&fit=crop&q=80&w=600"
                                    alt="ການຕະຫຼາດ"
                                    class="w-full h-full object-cover hover:scale-105 transition duration-300"
                                    onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1533750516457-a7f992034fec?auto=format&fit=crop&q=80&w=600';">
                                <span class="absolute bottom-2 left-2 bg-black/60 text-white text-[10px] px-2 py-0.5 rounded">ການຕະຫຼາດ</span>
                            </div>
                            <div class="p-5">
                                <div class="flex items-center gap-2 text-xs text-gray-400 mb-2">
                                    <span><i class="fa-regular fa-calendar mr-1"></i> 10 ມັງກອນ 2026</span>
                                    <span>•</span>
                                    <span><i class="fa-regular fa-clock mr-1"></i> ອ່ານ 5 ນາທີ</span>
                                </div>
                                <h4 class="font-bold text-gray-800 text-base mb-2 hover:text-emerald-600 transition cursor-pointer line-clamp-2">
                                    ວິທີການໂປຣໂມດງານຕະຫຼາດນັດເທິງ LAOeventMarket ໃຫ້ເຂົ້າເຖິງຄົນຫຼາຍທີ່ສຸດ
                                </h4>
                                <p class="text-gray-600 text-sm line-clamp-2 leading-relaxed">
                                    ແນະນຳວິທີການນຳໃຊ້ແພລດຟອມ LAOeventMarket ເພື່ອປະຊາສຳພັນງານຂອງທ່ານໃຫ້ເຂົ້າເຖິງກຸ່ມເປົ້າໝາຍ.
                                </p>
                            </div>
                        </div>
                        <div class="p-5 pt-0 flex items-center justify-between border-t border-gray-50 mt-2">
                            <span class="text-xs text-gray-400"><i class="fa-regular fa-eye mr-1"></i> 640</span>
                            <a href="#" class="text-emerald-600 font-semibold text-sm hover:underline flex items-center gap-1">
                                ອ່ານຕໍ່ <i class="fa-solid fa-angle-right text-xs"></i>
                            </a>
                        </div>
                    </article>

                    <!-- Article Card 4 -->
                    <article class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition flex flex-col justify-between">
                        <div>
                            <div class="h-48 bg-gray-200 overflow-hidden relative">
                                <img src="https://images.unsplash.com/photo-1511578314322-379afb476865?auto=format&fit=crop&q=80&w=500" alt="Article 4" class="w-full h-full object-cover hover:scale-105 transition duration-300">
                                <span class="absolute bottom-2 left-2 bg-black/60 text-white text-[10px] px-2 py-0.5 rounded">ໄຮໄລ້ງານ</span>
                            </div>
                            <div class="p-5">
                                <div class="flex items-center gap-2 text-xs text-gray-400 mb-2">
                                    <span><i class="fa-regular fa-calendar mr-1"></i> 05 ມັງກອນ 2026</span>
                                    <span>•</span>
                                    <span><i class="fa-regular fa-clock mr-1"></i> ອ່ານ 2 ນາທີ</span>
                                </div>
                                <h4 class="font-bold text-gray-800 text-base mb-2 hover:text-emerald-600 transition cursor-pointer line-clamp-2">
                                    ສະຫຼຸບໄຮໄລ້ບັນຍາກາດງານ IT & Electronics Expo ປະຈຳຕົ້ນປີ
                                </h4>
                                <p class="text-gray-600 text-sm line-clamp-2 leading-relaxed">
                                    ປະມວນພາບ ແລະ ບັນຍາກາດຄວາມຄຶກຄື້ນພາຍໃນງານມະຫາກຳໄອທີ ແລະ ອຸປະກອນເອເລັກໂຕຣນິກ.
                                </p>
                            </div>
                        </div>
                        <div class="p-5 pt-0 flex items-center justify-between border-t border-gray-50 mt-2">
                            <span class="text-xs text-gray-400"><i class="fa-regular fa-eye mr-1"></i> 930</span>
                            <a href="#" class="text-emerald-600 font-semibold text-sm hover:underline flex items-center gap-1">
                                ອ່ານຕໍ່ <i class="fa-solid fa-angle-right text-xs"></i>
                            </a>
                        </div>
                    </article>

                </div>

                <!-- Pagination -->
                <div class="flex justify-center items-center gap-2 my-8">
                    <button class="px-3.5 py-2 border border-gray-200 rounded-lg text-sm text-gray-500 hover:bg-gray-100 disabled:opacity-50">ກ່ອນໜ້າ</button>
                    <button class="px-3.5 py-2 bg-emerald-600 text-white rounded-lg text-sm font-semibold">1</button>
                    <button class="px-3.5 py-2 border border-gray-200 rounded-lg text-sm text-gray-600 hover:bg-gray-100">2</button>
                    <button class="px-3.5 py-2 border border-gray-200 rounded-lg text-sm text-gray-600 hover:bg-gray-100">3</button>
                    <button class="px-3.5 py-2 border border-gray-200 rounded-lg text-sm text-gray-600 hover:bg-gray-100">ຖັດໄປ</button>
                </div>
            </div>

            <!-- Right Column: Sidebar -->
            <aside class="space-y-8">

                <!-- Categories Widget -->
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                    <h4 class="font-bold text-gray-800 border-b border-gray-100 pb-3 mb-4 flex items-center justify-between">
                        <span>📂 ໝວດໝູ່ບົດຄວາມ</span>
                    </h4>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li>
                            <a href="#" class="flex items-center justify-between py-1.5 px-2 rounded-md hover:bg-emerald-50 hover:text-emerald-600 transition">
                                <span><i class="fa-solid fa-angle-right text-xs mr-2 text-emerald-500"></i> ຂ່າວສານ & ປະກາດ</span>
                                <span class="bg-gray-100 text-gray-500 text-xs px-2 py-0.5 rounded-full">8</span>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="flex items-center justify-between py-1.5 px-2 rounded-md hover:bg-emerald-50 hover:text-emerald-600 transition">
                                <span><i class="fa-solid fa-angle-right text-xs mr-2 text-emerald-500"></i> ເທັກນິກການຈັດງານ</span>
                                <span class="bg-gray-100 text-gray-500 text-xs px-2 py-0.5 rounded-full">5</span>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="flex items-center justify-between py-1.5 px-2 rounded-md hover:bg-emerald-50 hover:text-emerald-600 transition">
                                <span><i class="fa-solid fa-angle-right text-xs mr-2 text-emerald-500"></i> ໄຮໄລ້ງານຕະຫຼາດນັດ</span>
                                <span class="bg-gray-100 text-gray-500 text-xs px-2 py-0.5 rounded-full">12</span>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="flex items-center justify-between py-1.5 px-2 rounded-md hover:bg-emerald-50 hover:text-emerald-600 transition">
                                <span><i class="fa-solid fa-angle-right text-xs mr-2 text-emerald-500"></i> ການຕະຫຼາດ & ເຄັດລັບ</span>
                                <span class="bg-gray-100 text-gray-500 text-xs px-2 py-0.5 rounded-full">4</span>
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Popular Articles Widget (ບົດຄວາມຍອດຮິດ) -->
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                    <h4 class="font-bold text-gray-800 border-b border-gray-100 pb-3 mb-4">
                        🔥 ບົດຄວາມຍອດຮິດ
                    </h4>
                    <div class="space-y-4">
                        <div class="flex gap-3 items-center">
                            <span class="text-xl font-bold text-emerald-600 w-6">01</span>
                            <div>
                                <a href="#" class="text-sm font-semibold text-gray-800 hover:text-emerald-600 line-clamp-2">5 ເທັກນິກການຈັດງານຕະຫຼາດນັດ ໃຫ້ດຶງດູດຜູ້ຄົນ...</a>
                                <span class="text-[11px] text-gray-400"><i class="fa-regular fa-eye"></i> 1,250 ຄັ້ງ</span>
                            </div>
                        </div>
                        <div class="flex gap-3 items-center border-t border-gray-50 pt-3">
                            <span class="text-xl font-bold text-emerald-600 w-6">02</span>
                            <div>
                                <a href="#" class="text-sm font-semibold text-gray-800 hover:text-emerald-600 line-clamp-2">ພາໄປເລາະ Food Fest: ລວມຮ້ານເດັດ...</a>
                                <span class="text-[11px] text-gray-400"><i class="fa-regular fa-eye"></i> 1,120 ຄັ້ງ</span>
                            </div>
                        </div>
                        <div class="flex gap-3 items-center border-t border-gray-50 pt-3">
                            <span class="text-xl font-bold text-emerald-600 w-6">03</span>
                            <div>
                                <a href="#" class="text-sm font-semibold text-gray-800 hover:text-emerald-600 line-clamp-2">ສະຫຼຸບໄຮໄລ້ບັນຍາກາດງານ IT Expo...</a>
                                <span class="text-[11px] text-gray-400"><i class="fa-regular fa-eye"></i> 930 ຄັ້ງ</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Popular Tags Widget -->
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                    <h4 class="font-bold text-gray-800 border-b border-gray-100 pb-3 mb-4">
                        🏷️ ແທັກຍອດຮິດ (Popular Tags)
                    </h4>
                    <div class="flex flex-wrap gap-2 text-xs">
                        <a href="#" class="bg-gray-100 text-gray-600 px-3 py-1.5 rounded-lg hover:bg-emerald-600 hover:text-white transition">#ງານຕະຫຼາດນັດ</a>
                        <a href="#" class="bg-gray-100 text-gray-600 px-3 py-1.5 rounded-lg hover:bg-emerald-600 hover:text-white transition">#OTOP</a>
                        <a href="#" class="bg-gray-100 text-gray-600 px-3 py-1.5 rounded-lg hover:bg-emerald-600 hover:text-white transition">#FoodFest</a>
                        <a href="#" class="bg-gray-100 text-gray-600 px-3 py-1.5 rounded-lg hover:bg-emerald-600 hover:text-white transition">#ຜູ້ຈັດງານ</a>
                        <a href="#" class="bg-gray-100 text-gray-600 px-3 py-1.5 rounded-lg hover:bg-emerald-600 hover:text-white transition">#ໂປຣໂມດງານ</a>
                        <a href="#" class="bg-gray-100 text-gray-600 px-3 py-1.5 rounded-lg hover:bg-emerald-600 hover:text-white transition">#ນະຄອນຫຼວງວຽງຈັນ</a>
                    </div>
                </div>

                <!-- Newsletter Subscription Box -->
                <div class="bg-emerald-50 p-6 rounded-xl border border-emerald-100 text-center">
                    <div class="w-12 h-12 bg-emerald-600 text-white rounded-full flex items-center justify-center mx-auto mb-3 text-lg shadow">
                        <i class="fa-regular fa-envelope"></i>
                    </div>
                    <h4 class="font-bold text-gray-800 text-base mb-1">ສະໝັກຮັບຂ່າວສານ</h4>
                    <p class="text-xs text-gray-600 mb-4">ຮັບອັບເດດບົດຄວາມ ແລະ ຂ່າວສານງານຕະຫຼາດນັດໃໝ່ໆ ກ່ອນໃຜ</p>
                    <form action="#" class="space-y-2">
                        <input type="email" placeholder="ກໍານົດອີເມວຂອງທ່ານ..." class="w-full px-3 py-2 text-xs border border-gray-200 rounded-lg focus:outline-none focus:border-emerald-600">
                        <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white py-2 rounded-lg text-xs font-bold transition">
                            ສະໝັກຮັບຂ່າວສານ
                        </button>
                    </form>
                </div>

            </aside>

        </div>

    </main>

    <?php include __DIR__ . '/site_footer.php'; ?>
    <script src="assets/site-i18n.js"></script>
</body>

</html>
