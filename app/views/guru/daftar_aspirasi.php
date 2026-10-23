<?php
session_start();
require_once "../../models/m_aspirasi.php";
$model = new M_Aspirasi();
$semua_aspirasi = $model->tampilSemua();

$success = isset($_GET['success']) && $_GET['success'] == '1';
?>

<!DOCTYPE html>
<html lang="id" class="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Aspirasi - E-Lapor Guru</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Poppins', 'sans-serif']
                    }
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: #d4d4d8;
            border-radius: 10px;
        }

        .dark ::-webkit-scrollbar-thumb {
            background: #475569;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #0f766e;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: scale(0.95) translateY(-10px);
            }

            to {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }

        .modal-show {
            animation: fadeIn 0.3s ease-out forwards;
        }

        /* GOOGLE TRANSLATE */
        .goog-te-banner-frame.skiptranslate {
            display: none !important;
        }

        iframe.goog-te-banner-frame {
            display: none !important;
        }

        .goog-te-balloon-frame {
            display: none !important;
        }

        body {
            top: 0 !important;
        }

        @keyframes successPop {
            0% {
                opacity: 0;
                transform: scale(0.7);
            }

            70% {
                opacity: 1;
                transform: scale(1.05);
            }

            100% {
                opacity: 1;
                transform: scale(1);
            }
        }

        .success-show {
            animation: successPop 0.35s ease-out forwards;
        }

        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }

        .scrollbar-hide {
            scrollbar-width: none;
        }

        #mobileSidebar {
            transition: .3s ease;
        }

        #sidebarOverlay {
            transition: .3s ease;
        }
    </style>
</head>

<body class="bg-zinc-50 dark:bg-zinc-950 text-zinc-800 dark:text-zinc-100 antialiased selection:bg-teal-200 selection:text-teal-900 transition-colors duration-300">
    <div id="sidebarOverlay"
        class="fixed inset-0 z-40 bg-black/50 backdrop-blur-sm hidden transition-all duration-300">
    </div>

    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <aside
            id="mobileSidebar"
            class="fixed top-0 left-0 md:flex flex-col w-72 h-screen bg-teal-950 dark:bg-zinc-900 text-teal-100 dark:text-zinc-300 p-6 shadow-2xl z-40 border-r border-teal-900/40 dark:border-zinc-800 -translate-x-full md:translate-x-0 transition-transform duration-300">
            <!-- Logo Area -->
            <div class="flex items-center gap-4 mb-12 mt-2">
                <div class="bg-gradient-to-br from-teal-400 to-emerald-500 p-2.5 rounded-xl text-white shadow-lg shadow-teal-500/30">
                    <i class="fas fa-chalkboard-teacher text-2xl"></i>
                </div>
                <h1 class="text-2xl font-bold text-white tracking-tight">E-Lapor</h1>
            </div>

            <!-- Navigation -->
            <nav class="space-y-3 flex-1">
                <p class="text-[10px] font-bold uppercase tracking-wider text-teal-600/70 dark:text-zinc-500 mb-4 px-2">Menu Utama</p>
                <a href="dashboard.php" class="flex items-center gap-3 p-3.5 rounded-xl text-teal-200/80 dark:text-zinc-400 hover:bg-teal-900 dark:hover:bg-zinc-800 hover:text-white transition-all group">
                    <i class="fas fa-th-large w-5 text-teal-600/70 group-hover:text-teal-400 transition-colors"></i>
                    <span class="font-medium">Dashboard</span>
                </a>
                <a href="daftar_aspirasi.php" class="flex items-center gap-3 p-3.5 rounded-xl bg-gradient-to-r from-teal-500 to-teal-600 text-white shadow-md shadow-teal-900/50 transform transition-transform hover:scale-[1.02]">
                    <i class="fas fa-list-ul w-5 text-teal-100"></i>
                    <span class="font-medium">Semua Aspirasi</span>
                </a>
                <a href="histori_aspirasi.php" class="flex items-center gap-3 p-3.5 rounded-xl text-teal-200/80 dark:text-zinc-400 hover:bg-teal-900 dark:hover:bg-zinc-800 hover:text-white transition-all group">
                    <i class="fas fa-history w-5 text-teal-600/70 group-hover:text-teal-400 transition-colors"></i>
                    <span class="font-medium">Histori Aspirasi</span>
                </a>
            </nav>

            <!-- Profile Bottom -->
            <div class="mt-auto pt-6 border-t border-teal-900/80 dark:border-zinc-800">
                <div class="flex items-center gap-3 p-3 bg-teal-900/50 dark:bg-zinc-800/60 rounded-xl mb-4 border border-teal-800/50 dark:border-zinc-700">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-teal-700 to-teal-800 flex items-center justify-center border-2 border-teal-600 shrink-0">
                        <i class="fas fa-user-tie text-white text-sm"></i>
                    </div>
                    <div class="overflow-hidden">
                        <p class="text-sm font-semibold truncate text-white">Guru / Admin</p>
                        <p class="text-xs text-teal-400 dark:text-teal-300 font-medium">Staff Sekolah</p>
                    </div>
                </div>

                <div class="flex items-center gap-2 mb-3">
                    <button onclick="toggleTheme()" class="flex-1 flex items-center justify-center gap-2 p-2.5 bg-teal-900/30 dark:bg-zinc-800 hover:bg-teal-900/60 dark:hover:bg-zinc-700 rounded-xl text-xs font-semibold text-teal-200 dark:text-zinc-300 transition-all border border-teal-800/40 dark:border-zinc-700">
                        <i id="themeIcon" class="fas fa-moon text-teal-400"></i>
                        <span id="themeText">Dark Mode</span>
                    </button>
                </div>

                <a href="../../../logout.php" class="w-full flex items-center justify-center gap-2 p-3 text-red-400 hover:bg-red-500/10 hover:text-red-300 rounded-xl transition-all text-sm font-semibold border border-red-500/20">
                    <i class="fas fa-sign-out-alt"></i> Keluar
                </a>
            </div>
        </aside>


        <!-- Main Content -->
        <main class="flex-1 md:ml-72 pt-24 md:pt-10 p-6 relative transition-colors duration-300">


            <!-- Mobile Menu Button -->
            <button
                onclick="toggleSidebar()"
                class="md:hidden mb-4 flex items-center justify-center w-12 h-12 rounded-xl bg-teal-600 text-white shadow-lg">

                <i class="fas fa-bars text-lg"></i>

            </button>
            <div class=" z-40 mb-6 md:mb-10 bg-white dark:bg-zinc-900 p-4 md:p-6 rounded-2xl shadow-sm border border-zinc-200/60 dark:border-zinc-800 transition-colors">
                <h2 class="text-2xl md:text-3xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-zinc-800 to-teal-700 dark:from-zinc-100 dark:to-teal-400 mb-1">
                    Daftar Aspirasi 📋
                </h2>
                <p class="text-zinc-500 dark:text-zinc-400 text-sm font-medium">Kelola status dan berikan keterangan pada laporan siswa.</p>
            </div>

            <!-- FILTER STATUS -->
            <div class="px-6 py-5 border-b border-slate-100 dark:border-slate-800">
                <div class="flex overflow-x-auto items-center gap-2 pb-2 scrollbar-hide">

                    <button
                        type="button"
                        onclick="filterAspirasi('Semua', this)"
                        class="filter-btn active-filter px-4 py-2 rounded-xl text-xs font-bold bg-blue-600 text-white transition-all">
                        Semua
                    </button>

                    <button
                        type="button"
                        onclick="filterAspirasi('Menunggu', this)"
                        class="filter-btn px-4 py-2 rounded-xl text-xs font-bold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 transition-all hover:bg-amber-100 hover:text-amber-700">
                        Menunggu
                    </button>

                    <button
                        type="button"
                        onclick="filterAspirasi('Diproses', this)"
                        class="filter-btn px-4 py-2 rounded-xl text-xs font-bold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 transition-all hover:bg-sky-100 hover:text-sky-700">
                        Diproses
                    </button>

                    <button
                        type="button"
                        onclick="filterAspirasi('Selesai', this)"
                        class="filter-btn px-4 py-2 rounded-xl text-xs font-bold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 transition-all hover:bg-emerald-100 hover:text-emerald-700">
                        Selesai
                    </button>

                </div>
            </div>
            <!-- DESKTOP TABLE -->
            <div class="hidden md:block bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200/60 dark:border-zinc-800 overflow-hidden shadow-sm hover:shadow-md transition-all">
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[900px] text-sm">
                        <thead class="bg-zinc-50/80 dark:bg-zinc-800/50 text-zinc-500 dark:text-zinc-400 border-b border-zinc-100 dark:border-zinc-800">
                            <tr>
                                <th class="px-6 py-4 text-left font-semibold uppercase tracking-wider text-[11px]">Pelapor</th>
                                <th class="px-6 py-4 text-left font-semibold uppercase tracking-wider text-[11px]">Aspirasi</th>
                                <th class="px-6 py-4 text-left font-semibold uppercase tracking-wider text-[11px]">Status</th>
                                <th class="px-6 py-4 text-center font-semibold uppercase tracking-wider text-[11px]">Rating Siswa</th>
                                <th class="px-6 py-4 text-center font-semibold uppercase tracking-wider text-[11px]">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                            <?php while ($row = $semua_aspirasi->fetch_assoc()):
                                $status = $row['status'];
                                $color = ($status == 'Selesai') ? 'emerald' : (($status == 'Diproses') ? 'sky' : 'amber');
                            ?>
                                <tr data-status="<?= htmlspecialchars($status) ?>" class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors group">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-9 h-9 rounded-full bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center text-zinc-500 dark:text-zinc-400 text-xs font-bold border border-zinc-200 dark:border-zinc-700">
                                                <?= substr(htmlspecialchars($row['nama_siswa']), 0, 1) ?>
                                            </div>
                                            <div>
                                                <p class="font-bold text-zinc-800 dark:text-zinc-200 group-hover:text-teal-700 dark:group-hover:text-teal-400 transition-colors"><?= htmlspecialchars($row['nama_siswa']) ?></p>
                                                <p class="text-[10px] font-medium text-zinc-400">ID: #ASP-<?= $row['id_input'] ?></p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <p class="font-semibold text-zinc-700 dark:text-zinc-300"><?= htmlspecialchars($row['judul']) ?></p>
                                        <p class="text-xs text-zinc-500 dark:text-zinc-400 truncate max-w-[250px] mt-0.5"><?= htmlspecialchars($row['isi_aspirasi']) ?></p>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[10px] font-bold bg-<?= $color ?>-100 text-<?= $color ?>-700 dark:bg-<?= $color ?>-950/60 dark:text-<?= $color ?>-400 border border-<?= $color ?>-200 dark:border-<?= $color ?>-800 uppercase tracking-wide">
                                            <span class="w-1.5 h-1.5 rounded-full bg-<?= $color ?>-500"></span>
                                            <?= strtoupper($status) ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <?php if ($row['rating'] !== null): ?>
                                            <div class="flex items-center justify-center gap-1">
                                                <?php
                                                $rating = (float)$row['rating'];
                                                $fullStars = floor($rating);
                                                $hasHalf = ($rating - $fullStars) >= 0.5;
                                                ?>
                                                <?php for ($i = 0; $i < $fullStars; $i++): ?>
                                                    <i class="fas fa-star text-amber-400"></i>
                                                <?php endfor; ?>
                                                <?php if ($hasHalf): ?>
                                                    <i class="fas fa-star-half-stroke text-amber-400"></i>
                                                <?php endif; ?>
                                                <span class="ml-1 text-xs font-bold text-zinc-600 dark:text-zinc-300 bg-zinc-100 dark:bg-zinc-800 px-2 py-0.5 rounded-md border border-zinc-200 dark:border-zinc-700">
                                                    <?= number_format($rating, 1) ?>
                                                </span>
                                            </div>
                                        <?php else: ?>
                                            <span class="text-[11px] text-zinc-400 font-medium bg-zinc-50 dark:bg-zinc-800/60 px-3 py-1 rounded-full border border-zinc-100 dark:border-zinc-700">
                                                Belum dinilai
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <button
                                            onclick='openEditModal(
                                                <?= json_encode($row["id_aspirasi"]) ?>,
                                                <?= json_encode($row["nama_siswa"]) ?>,
                                                <?= json_encode($status) ?>,
                                                <?= json_encode($row["judul"]) ?>,
                                                <?= json_encode($row["foto"] ?? "") ?>,
                                                <?= json_encode($row["isi_aspirasi"]) ?>
                                            )'
                                            class="bg-teal-50 dark:bg-teal-950/50 text-teal-600 dark:text-teal-400 px-4 py-2 rounded-xl text-xs font-bold hover:bg-teal-600 hover:text-white border border-teal-100 dark:border-teal-800 hover:border-teal-600 transition-all duration-300 shadow-sm hover:shadow-md hover:-translate-y-0.5">
                                            Tanggapi
                                        </button>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- MOBILE CARD VIEW -->
            <div class="md:hidden space-y-4 mt-5">

                <?php
                $semua_aspirasi->data_seek(0);

                while ($row = $semua_aspirasi->fetch_assoc()):

                    $status = $row['status'];

                    $statusColor =
                        ($status == 'Selesai') ? 'emerald' : (($status == 'Diproses') ? 'sky' : 'amber');

                ?>

                    <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200 dark:border-zinc-800 p-5 shadow-sm">


                        <!-- Header -->
                        <div class="flex justify-between gap-3">

                            <div>

                                <h3 class="font-bold text-zinc-800 dark:text-zinc-100">
                                    <?= htmlspecialchars($row['judul']) ?>
                                </h3>
                                <p class="text-xs text-zinc-400 mt-1">
                                    <?= htmlspecialchars($row['nama_siswa']) ?>
                                </p>
                            </div>
                            <span
                                class="shrink-0 px-3 py-1 rounded-full text-[10px] font-bold
        bg-<?= $statusColor ?>-100 
        text-<?= $statusColor ?>-700">

                                <?= strtoupper($status) ?>
                            </span>
                        </div>
                        <!-- Isi Aspirasi -->
                        <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-4 line-clamp-3">
                            <?= htmlspecialchars($row['isi_aspirasi']) ?>
                        </p>

                        <!-- Rating -->

                        <div class="mt-4 flex items-center justify-between">

                            <div>
                                <p class="text-[11px] text-zinc-400">
                                    Rating Siswa
                                </p>

                                <?php if ($row['rating'] !== null): ?>
                                    <div class="text-amber-400 text-sm mt-1">
                                        ⭐ <?= number_format($row['rating'], 1) ?>
                                    </div>
                                <?php else: ?>
                                    <p class="text-xs text-zinc-400 mt-1">
                                        Belum dinilai
                                    </p>
                                <?php endif; ?>

                            </div>
                            <!-- Button -->
                            <button
                                onclick='openEditModal(
                                <?= json_encode($row["id_aspirasi"]) ?>,
                                <?= json_encode($row["nama_siswa"]) ?>,
                                <?= json_encode($row["status"]) ?>,
                                <?= json_encode($row["judul"]) ?>,
                                <?= json_encode($row["foto"] ?? "") ?>,
                                <?= json_encode($row["isi_aspirasi"]) ?>
                            )'
                                class="px-4 py-2 bg-teal-600 text-white rounded-xl text-xs font-bold shadow">
                                Tanggapi
                            </button>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </main>
    </div>

    <!-- Modal Edit -->
    <div id="modalEdit" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-zinc-900/60 backdrop-blur-sm transition-opacity"></div>

        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-lg px-3 md:px-4 max-h-[95vh]">
            <div class="bg-white dark:bg-zinc-900 rounded-[2rem] shadow-2xl overflow-hidden modal-show border border-zinc-100 dark:border-zinc-800 max-h-[90vh] flex flex-col w-full">
                <!-- Modal Header -->
                <div class="relative h-24 md:h-28 bg-gradient-to-r from-teal-600 to-teal-500 p-5 md:p-6 flex items-end overflow-hidden">
                    <div class="absolute -right-4 -top-4 w-24 h-24 bg-white/10 rounded-full blur-xl"></div>
                    <div class="absolute left-1/2 -top-10 w-32 h-32 bg-emerald-400/20 rounded-full blur-2xl"></div>

                    <button onclick="closeModal()" class="absolute top-4 right-4 w-8 h-8 flex items-center justify-center bg-black/10 hover:bg-black/20 text-white rounded-full transition-all backdrop-blur-md">
                        <i class="fas fa-times"></i>
                    </button>
                    <div class="flex items-center gap-4 relative z-10">
                        <div class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center text-teal-600 shadow-lg shadow-black/10">
                            <i class="fas fa-edit text-xl"></i>
                        </div>
                        <div class="text-white">
                            <h3 class="text-lg font-bold leading-tight">Berikan Tanggapan</h3>
                            <p class="text-xs text-teal-50 font-medium mt-0.5" id="display_siswa"></p>
                        </div>
                    </div>
                </div>

                <form
                    id="formTanggapan"
                    action="../../controllers/C_Aspirasi.php?aksi=tanggapi_guru"
                    method="POST"
                    class="p-5 md:p-8 space-y-6 overflow-y-auto flex-1"
                    onsubmit="return cekStatus()">
                    <input type="hidden" name="id_aspirasi" id="modal_id">

                    <div class="space-y-5">
                        <div class="p-4 bg-zinc-50 dark:bg-zinc-800/60 rounded-2xl border border-zinc-100 dark:border-zinc-700/80">
                            <p class="text-[10px] font-bold text-zinc-400 uppercase tracking-wider mb-1">
                                Topik Laporan
                            </p>
                            <p class="text-sm font-semibold text-zinc-700 dark:text-zinc-200" id="display_judul"></p>
                        </div>

                        <div>
                            <h4 class="font-bold">
                                Penjelasan Siswa
                            </h4>

                            <p id="modalPenjelasan">
                            </p>
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold text-zinc-500 dark:text-zinc-400 uppercase tracking-wider mb-2 ml-1">
                                Update Status
                            </label>
                            <div class="grid grid-cols-3 gap-3">

                                <!-- MENUNGGU -->
                                <label class="cursor-not-allowed">
                                    <input
                                        type="radio"
                                        name="status"
                                        value="Menunggu"
                                        class="peer hidden"
                                        id="status_menunggu"
                                        disabled>

                                    <div class="text-center py-2.5 px-3 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-zinc-100 dark:bg-zinc-800 text-zinc-400 font-bold text-[11px] opacity-60">
                                        MENUNGGU
                                    </div>
                                </label>


                                <!-- DIPROSES -->
                                <label class="cursor-pointer" id="label_diproses">
                                    <input
                                        type="radio"
                                        name="status"
                                        value="Diproses"
                                        class="peer hidden"
                                        id="status_diproses">

                                    <div class="text-center py-2.5 px-3 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-400 font-bold text-[11px] peer-checked:bg-sky-50 dark:peer-checked:bg-sky-950/50 peer-checked:text-sky-600 dark:peer-checked:text-sky-400 peer-checked:border-sky-300 dark:peer-checked:border-sky-800 peer-checked:shadow-sm hover:bg-zinc-50 dark:hover:bg-zinc-700/50 transition-all">
                                        DIPROSES
                                    </div>
                                </label>


                                <!-- SELESAI -->
                                <label class="cursor-pointer" id="label_selesai">
                                    <input
                                        type="radio"
                                        name="status"
                                        value="Selesai"
                                        class="peer hidden"
                                        id="status_selesai">

                                    <div class="text-center py-2.5 px-3 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-400 font-bold text-[11px] peer-checked:bg-emerald-50 dark:peer-checked:bg-emerald-950/50 peer-checked:text-emerald-600 dark:peer-checked:text-emerald-400 peer-checked:border-emerald-300 dark:peer-checked:border-emerald-800 peer-checked:shadow-sm hover:bg-zinc-50 dark:hover:bg-zinc-700/50 transition-all">
                                        SELESAI
                                    </div>
                                </label>

                            </div>

                            <div class="mt-4">
                                <p class="text-xs font-bold text-zinc-500 uppercase mb-2">
                                    Foto Bukti
                                </p>

                                <div id="fotoContainer">
                                    <p class="text-xs text-zinc-400">
                                        Tidak ada foto bukti.
                                    </p>
                                </div>
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-zinc-500 dark:text-zinc-400 uppercase tracking-wider mb-2 ml-1">
                                    Keterangan / Feedback
                                </label>
                                <textarea
                                    name="feedback"
                                    rows="3"
                                    placeholder="Contoh: Perbaikan akan segera dilakukan..."
                                    class="w-full p-4 bg-zinc-50 dark:bg-zinc-800/60 border border-zinc-200 dark:border-zinc-700 rounded-2xl focus:ring-4 focus:ring-teal-500/10 focus:border-teal-500 outline-none transition-all text-sm resize-none text-zinc-700 dark:text-zinc-200"></textarea>
                            </div>
                        </div>

                        <div class="mt-8 flex flex-col md:flex-row gap-3">
                            <button
                                type="button"
                                onclick="closeModal()"
                                class="w-full md:flex-1 py-3.5 bg-zinc-100 dark:bg-zinc-800 text-zinc-500 dark:text-zinc-300 rounded-2xl font-bold text-sm hover:bg-zinc-200 dark:hover:bg-zinc-700 transition-colors">
                                Batal
                            </button>
                            <button
                                type="submit"
                                class="w-full md:flex-[2] py-3.5 bg-teal-600 text-white rounded-2xl font-bold text-sm hover:bg-teal-700 shadow-lg shadow-teal-600/30 transition-all active:scale-95">
                                Simpan Perubahan
                            </button>
                        </div>
                </form>
            </div>
        </div>
    </div>

    <div id="statusNotification" class="fixed top-6 right-6 z-[9999] hidden w-full max-w-sm">
        <div class="bg-white dark:bg-zinc-900 rounded-2xl shadow-2xl shadow-black/10 border border-amber-200 dark:border-amber-800/60 p-5 transform transition-all">
            <div class="flex items-start gap-4">
                <div class="w-10 h-10 rounded-xl bg-amber-50 dark:bg-amber-950/60 text-amber-500 border border-amber-100 dark:border-amber-800/40 flex items-center justify-center flex-shrink-0 shadow-inner">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="flex-1 mt-0.5">
                    <h4 class="font-bold text-zinc-800 dark:text-zinc-100 text-sm">Status Tidak Valid</h4>
                    <p id="statusNotificationText" class="text-xs text-zinc-500 dark:text-zinc-400 mt-1 leading-relaxed"></p>
                </div>
                <button type="button" onclick="tutupNotif()" class="text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200 hover:bg-zinc-100 dark:hover:bg-zinc-800 p-1 rounded-lg transition-colors">
                    <i class="fas fa-times w-4 h-4 flex items-center justify-center"></i>
                </button>
            </div>
        </div>
    </div>

    <?php if ($success): ?>
        <div id="successNotification" class="fixed inset-0 z-[9999] flex items-center justify-center bg-zinc-950/40 backdrop-blur-sm">
            <div id="successBox" class="bg-white dark:bg-zinc-900 rounded-[2rem] shadow-2xl border border-zinc-200 dark:border-zinc-800 p-8 w-full max-w-sm mx-4 text-center">
                <div class="w-20 h-20 mx-auto mb-5 rounded-full bg-emerald-100 dark:bg-emerald-950/50 flex items-center justify-center">
                    <i class="fas fa-check text-3xl text-emerald-500"></i>
                </div>

                <h3 class="text-xl font-black text-zinc-800 dark:text-white">
                    Berhasil!
                </h3>

                <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-2">
                    Tanggapan berhasil disimpan.
                </p>
            </div>
        </div>
    <?php endif; ?>

    <script>
        function toggleTheme() {
            const html = document.documentElement;
            const themeIcon = document.getElementById('themeIcon');
            const themeText = document.getElementById('themeText');

            if (html.classList.contains('dark')) {
                html.classList.remove('dark');
                localStorage.theme = 'light';
                if (themeText) themeText.innerText = 'Dark Mode';
                if (themeIcon) themeIcon.className = 'fas fa-moon text-teal-400';
            } else {
                html.classList.add('dark');
                localStorage.theme = 'dark';
                if (themeText) themeText.innerText = 'Light Mode';
                if (themeIcon) themeIcon.className = 'fas fa-sun text-amber-400';
            }
        }

        function openSidebar() {
            document.getElementById('mobileSidebar').classList.remove('-translate-x-full');
            document.getElementById('sidebarOverlay').classList.remove('hidden');
        }

        function closeSidebar() {
            document.getElementById('mobileSidebar').classList.add('-translate-x-full');
            document.getElementById('sidebarOverlay').classList.add('hidden');
        }

        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
            const themeText = document.getElementById('themeText');
            const themeIcon = document.getElementById('themeIcon');
            if (themeText) themeText.innerText = 'Light Mode';
            if (themeIcon) themeIcon.className = 'fas fa-sun text-amber-400';
        }

        const modal = document.getElementById('modalEdit');

        function openEditModal(id, siswa, status, judul, foto, penjelasan) {

            document.getElementById('modal_id').value = id;

            document.getElementById('formTanggapan').dataset.statusLama = status;

            document.getElementById('display_siswa').innerText =
                "Pelapor: " + siswa;

            document.getElementById('display_judul').innerText =
                judul;
           document.getElementById('modalPenjelasan').innerText = 
                penjelasan;


            // =========================
            // TAMPILKAN FOTO
            // =========================

            const fotoContainer = document.getElementById('fotoContainer');

            if (foto && foto.trim() !== '') {

                fotoContainer.innerHTML = `
            <img
                src="../assets/uploads/${foto}"
                alt="Foto bukti aspirasi"
                class="w-full max-h-72 object-contain rounded-xl border border-zinc-200 dark:border-zinc-700">
        `;

            } else {

                fotoContainer.innerHTML = `
            <p class="text-xs text-zinc-400">
                Tidak ada foto bukti.
            </p>
        `;
            }


            // =========================
            // STATUS
            // =========================

            const radioDiproses = document.getElementById('status_diproses');
            const radioSelesai = document.getElementById('status_selesai');

            const labelDiproses = document.getElementById('label_diproses');
            const labelSelesai = document.getElementById('label_selesai');


            // Reset
            radioDiproses.checked = false;
            radioSelesai.checked = false;

            radioDiproses.disabled = false;
            radioSelesai.disabled = false;

            labelDiproses.classList.remove(
                'opacity-50',
                'cursor-not-allowed',
                'pointer-events-none'
            );

            labelSelesai.classList.remove(
                'opacity-50',
                'cursor-not-allowed',
                'pointer-events-none'
            );


            // ==========================================
            // MENUNGGU → DIPROSES
            // ==========================================

            if (status === 'Menunggu') {

                radioDiproses.disabled = false;
                radioSelesai.disabled = true;

                labelDiproses.classList.remove('opacity-50');
                labelDiproses.classList.add('cursor-pointer');

                labelSelesai.classList.add(
                    'opacity-50',
                    'cursor-not-allowed',
                    'pointer-events-none'
                );

            }


            // ==========================================
            // DIPROSES → SELESAI
            // ==========================================
            else if (status === 'Diproses') {

                radioDiproses.disabled = true;
                radioSelesai.disabled = false;

                labelDiproses.classList.add(
                    'opacity-50',
                    'cursor-not-allowed',
                    'pointer-events-none'
                );

                labelSelesai.classList.remove('opacity-50');
                labelSelesai.classList.add('cursor-pointer');

            }


            // ==========================================
            // SELESAI → TIDAK BISA DIUBAH
            // ==========================================
            else if (status === 'Selesai') {

                radioDiproses.disabled = true;
                radioSelesai.disabled = true;

                labelDiproses.classList.add(
                    'opacity-50',
                    'cursor-not-allowed',
                    'pointer-events-none'
                );

                labelSelesai.classList.add(
                    'opacity-50',
                    'cursor-not-allowed',
                    'pointer-events-none'
                );

            }


            modal.classList.remove('hidden');
        }

        function closeModal() {
            modal.classList.add('hidden');
        }

        function cekStatus() {

            const form = document.getElementById('formTanggapan');
            const statusLama = form.dataset.statusLama;
            const statusDipilih = document.querySelector(
                'input[name="status"]:checked'
            );

            // Belum memilih status
            if (!statusDipilih) {

                document.getElementById('statusNotificationText').innerText =
                    'Silakan pilih status laporan terlebih dahulu.';

                document.getElementById('statusNotification').classList.remove('hidden');

                return false;
            }

            const statusBaru = statusDipilih.value;


            // ==========================================
            // ATURAN STATUS
            // ==========================================

            // Menunggu hanya boleh ke Diproses
            if (
                statusLama === 'Menunggu' &&
                statusBaru !== 'Diproses'
            ) {

                document.getElementById('statusNotificationText').innerText =
                    'Laporan dengan status Menunggu hanya dapat diubah menjadi Diproses.';

                document.getElementById('statusNotification').classList.remove('hidden');

                return false;
            }


            // Diproses hanya boleh ke Selesai
            if (
                statusLama === 'Diproses' &&
                statusBaru !== 'Selesai'
            ) {

                document.getElementById('statusNotificationText').innerText =
                    'Laporan dengan status Diproses hanya dapat diubah menjadi Selesai.';

                document.getElementById('statusNotification').classList.remove('hidden');

                return false;
            }


            // Selesai tidak boleh diubah lagi
            if (statusLama === 'Selesai') {

                document.getElementById('statusNotificationText').innerText =
                    'Laporan yang sudah Selesai tidak dapat diubah lagi.';

                document.getElementById('statusNotification').classList.remove('hidden');

                return false;
            }


            return true;
        }

        function tutupNotif() {
            document.getElementById('statusNotification').classList.add('hidden');
        }

        window.onclick = function(event) {
            if (event.target == modal.querySelector('.absolute.inset-0')) {
                closeModal();
            }
        }
    </script>

    <div id="google_translate_element" style="display:none;"></div>
    <script>
        function googleTranslateElementInit() {
            new google.translate.TranslateElement({
                pageLanguage: 'id',
                includedLanguages: 'id,en'
            }, 'google_translate_element');
        }

        <?php if ($success): ?>
            const successBox = document.getElementById('successBox');

            successBox.classList.add('success-show');

            setTimeout(() => {
                document.getElementById('successNotification').remove();

                window.history.replaceState({},
                    document.title,
                    window.location.pathname
                );
            }, 1800);
        <?php endif; ?>
    </script>


    <script src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
    <script>
        function filterAspirasi(status, tombol) {

            const rows = document.querySelectorAll('tbody tr[data-status]');
            const buttons = document.querySelectorAll('.filter-btn');

            // Reset semua tombol
            buttons.forEach(button => {
                button.classList.remove('bg-blue-600', 'text-white');
                button.classList.add(
                    'bg-slate-100',
                    'dark:bg-slate-800',
                    'text-slate-600',
                    'dark:text-slate-300'
                );
            });

            // Aktifkan tombol yang dipilih
            tombol.classList.remove(
                'bg-slate-100',
                'dark:bg-slate-800',
                'text-slate-600',
                'dark:text-slate-300'
            );

            tombol.classList.add('bg-blue-600', 'text-white');

            // Filter data
            rows.forEach(row => {

                const statusRow = row.dataset.status;

                if (status === 'Semua' || statusRow === status) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }

            });
        }

        function toggleSidebar() {

            const sidebar = document.getElementById('sidebar');

            sidebar.classList.toggle('hidden');

        }

        function toggleSidebar() {

            const sidebar = document.getElementById('sidebar');

            sidebar.classList.toggle('hidden');

        }
        document.addEventListener('click', function(e) {

            const sidebar = document.getElementById('sidebar');

            const button = e.target.closest('button');


            if (
                window.innerWidth < 768 &&
                !sidebar.contains(e.target) &&
                !button?.onclick
            ) {

                sidebar.classList.add('hidden');

            }

        });
        const sidebar = document.getElementById("mobileSidebar");
        const overlay = document.getElementById("sidebarOverlay");


        function toggleSidebar() {

            if (sidebar.classList.contains("-translate-x-full")) {

                // buka
                sidebar.classList.remove("-translate-x-full");
                overlay.classList.remove("hidden");

            } else {

                // tutup
                sidebar.classList.add("-translate-x-full");
                overlay.classList.add("hidden");

            }

        }


        // klik luar sidebar
        overlay.addEventListener("click", function() {

            sidebar.classList.add("-translate-x-full");
            overlay.classList.add("hidden");

        });


        // tekan ESC nutup
        document.addEventListener("keydown", function(e) {

            if (e.key === "Escape") {

                sidebar.classList.add("-translate-x-full");
                overlay.classList.add("hidden");

            }

        });
    </script>

</body>

</html>