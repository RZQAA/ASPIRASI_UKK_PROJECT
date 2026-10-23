<?php

require_once "../../models/m_aspirasi.php";
require_once "../auth/auth.php";

cekSiswa();

$model = new M_Aspirasi();

$id_siswa = $_SESSION['siswa']['id_siswa'];
$nama_siswa = $_SESSION['siswa']['nama_siswa'] ?? 'Siswa';

$data_db = $model->tampilSiswa($id_siswa);

$total = 0;
$menunggu = 0;
$tuntas = 0;
$list_aspirasi = [];

if ($data_db) {
    while ($row = $data_db->fetch_assoc()) {
        $total++;

        if ($row['status'] == 'Menunggu') {
            $menunggu++;
        }

        if ($row['status'] == 'Selesai') {
            $tuntas++;
        }

        $list_aspirasi[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="id" class="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Siswa - E-Lapor</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

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
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }

        .dark ::-webkit-scrollbar-thumb {
            background: #475569;
        }
    </style>
</head>

<body class="bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 font-sans antialiased selection:bg-blue-200 selection:text-blue-900 transition-colors duration-300">

    <div class="min-h-screen flex">

        <!-- MOBILE OVERLAY -->
        <div id="sidebarOverlay"
            onclick="closeSidebar()"
            class="fixed inset-0 bg-black/50 z-40 hidden md:hidden">
        </div>

        <!-- SIDEBAR -->
        <aside id="sidebar"
            class="fixed top-0 left-0 z-50 h-screen w-72 bg-blue-950 dark:bg-slate-900 text-blue-100 dark:text-slate-200 p-6 shadow-2xl border-r border-blue-900/50 dark:border-slate-800 transform -translate-x-full md:translate-x-0 transition-transform duration-300 flex flex-col">

            <!-- TOMBOL CLOSE MOBILE -->
            <div class="flex items-center justify-between mb-6 md:hidden">
                <span class="text-white font-bold text-sm">Menu Siswa</span>

                <button onclick="closeSidebar()"
                    class="w-10 h-10 rounded-xl bg-white/10 text-white flex items-center justify-center hover:bg-white/20 transition-all">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="flex items-center gap-4 mb-12 mt-2">
                <div class="bg-gradient-to-br from-blue-500 to-sky-400 p-2.5 rounded-2xl text-white shadow-lg shadow-blue-500/30">
                    <i class="fas fa-bullhorn text-2xl"></i>
                </div>

                <div>
                    <h1 class="text-xl font-bold tracking-tight text-white">Aspirasi Care</h1>
                    <p class="text-[10px] text-sky-400 font-semibold tracking-wider uppercase">Portal Siswa</p>
                </div>
            </div>

            <nav class="space-y-2 flex-1">
                <p class="text-[10px] font-bold uppercase tracking-wider text-blue-400/70 dark:text-slate-500 mb-3 px-3">
                    Menu Siswa
                </p>

                <a href="dashboard_siswa.php"
                    onclick="closeSidebar()"
                    class="flex items-center gap-3.5 p-3.5 rounded-xl bg-gradient-to-r from-blue-600 to-sky-500 text-white shadow-lg shadow-blue-900/40 font-semibold">
                    <i class="fas fa-chart-line w-5 text-white"></i> Dashboard
                </a>

                <a href="form.php"
                    onclick="closeSidebar()"
                    class="flex items-center gap-3.5 p-3.5 rounded-xl text-blue-200/80 dark:text-slate-400 hover:bg-blue-900/50 dark:hover:bg-slate-800 hover:text-white transition-all font-medium">
                    <i class="fas fa-edit w-5 text-sky-400"></i> Sampaikan Aspirasi
                </a>

                <a href="riwayat.php"
                    onclick="closeSidebar()"
                    class="flex items-center gap-3.5 p-3.5 rounded-xl text-blue-200/80 dark:text-slate-400 hover:bg-blue-900/50 dark:hover:bg-slate-800 hover:text-white transition-all font-medium">
                    <i class="fas fa-history w-5 text-sky-400"></i> Riwayat Saya
                </a>

                <a href="histori_aspirasi.php"
                    onclick="closeSidebar()"
                    class="flex items-center gap-3.5 p-3.5 rounded-xl text-blue-200/80 dark:text-slate-400 hover:bg-blue-900/50 dark:hover:bg-slate-800 hover:text-white transition-all font-medium">
                    <i class="fas fa-archive w-5 text-sky-400"></i> Histori Aspirasi
                </a>
            </nav>

            <div class="mt-auto pt-6 border-t border-blue-900/80 dark:border-slate-800">

                <div class="flex items-center gap-3 p-3 mb-4 bg-blue-900/40 dark:bg-slate-800/60 rounded-2xl border border-blue-800/40 dark:border-slate-700">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-600 to-sky-500 flex items-center justify-center text-white font-bold shadow-md shrink-0">
                        <i class="fas fa-user text-sm"></i>
                    </div>

                    <div class="overflow-hidden">
                        <p class="text-sm font-bold truncate text-white"><?= htmlspecialchars($nama_siswa) ?></p>
                        <p class="text-[10px] text-sky-400 font-medium">Siswa Aktif</p>
                    </div>
                </div>

                <div class="flex items-center gap-2 mb-3">
                    <button onclick="toggleTheme()"
                        class="flex-1 flex items-center justify-center gap-2 p-3 bg-blue-900/30 dark:bg-slate-800 hover:bg-blue-900/60 dark:hover:bg-slate-700 rounded-xl text-xs font-semibold text-blue-200 dark:text-slate-300 transition-all border border-blue-800/40 dark:border-slate-700">
                        <i id="themeIcon" class="fas fa-moon text-sky-400"></i>
                        <span id="themeText">Dark Mode</span>
                    </button>
                </div>

                <a href="../../../logout.php"
                    onclick="return confirm('Yakin ingin keluar?')"
                    class="w-full flex items-center justify-center gap-2 p-3 text-rose-400 hover:bg-rose-500/10 rounded-xl transition-all font-semibold text-xs border border-rose-500/20">
                    <i class="fas fa-sign-out-alt"></i> Keluar
                </a>
            </div>
        </aside>

        <!-- MAIN -->
        <main class="flex-1 md:ml-72 p-4 sm:p-6 md:p-10 transition-colors duration-300">

            <!-- MOBILE HEADER -->
            <div class="flex md:hidden justify-between items-center mb-6 bg-white dark:bg-slate-900 p-4 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm">

                <button onclick="openSidebar()"
                    class="w-10 h-10 flex items-center justify-center bg-blue-600 text-white rounded-xl shadow-sm">
                    <i class="fas fa-bars"></i>
                </button>

                <div class="flex items-center gap-3">
                    <div class="bg-blue-600 p-2 rounded-xl text-white">
                        <i class="fas fa-bullhorn"></i>
                    </div>
                    <h1 class="font-bold text-slate-800 dark:text-white">Aspirasi Care</h1>
                </div>

                <button onclick="toggleTheme()"
                    class="w-10 h-10 flex items-center justify-center bg-slate-100 dark:bg-slate-800 rounded-xl text-slate-700 dark:text-slate-300">
                    <i id="themeIconMobile" class="fas fa-moon"></i>
                </button>
            </div>

            <div class="max-w-5xl mx-auto">

                <!-- WELCOME -->
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-6 sm:mb-10 bg-white dark:bg-slate-900 p-5 sm:p-8 rounded-2xl sm:rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-sm transition-colors">
                    <div class="min-w-0">
                        <h2 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white tracking-tight break-words">
                            Selamat Datang, <?= htmlspecialchars($nama_siswa) ?>! 👋
                        </h2>

                        <p class="text-slate-500 dark:text-slate-400 mt-2 font-medium text-xs sm:text-sm leading-relaxed">
                            Suaramu sangat berarti untuk kemajuan sekolah kita.
                        </p>
                    </div>
                </div>

                <!-- STATISTIK -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6 mb-6 sm:mb-10">

                    <div class="bg-white dark:bg-slate-900 p-5 sm:p-6 rounded-2xl sm:rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-sm hover:shadow-lg transition-all group">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-slate-400 dark:text-slate-500 text-[10px] font-extrabold uppercase tracking-widest mb-1">Total Aspirasi</p>
                                <p class="text-3xl font-black text-slate-900 dark:text-white"><?= $total ?></p>
                            </div>

                            <div class="bg-blue-50 dark:bg-blue-950/50 text-blue-600 dark:text-sky-400 w-14 h-14 rounded-2xl flex items-center justify-center text-2xl group-hover:bg-blue-600 group-hover:text-white transition-colors shadow-inner">
                                <i class="fas fa-paper-plane"></i>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-slate-900 p-5 sm:p-6 rounded-2xl sm:rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-sm hover:shadow-lg transition-all group relative overflow-hidden">
                        <div class="absolute left-0 top-0 w-1.5 h-full bg-amber-400"></div>

                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-slate-400 dark:text-slate-500 text-[10px] font-extrabold uppercase tracking-widest mb-1">Menunggu</p>
                                <p class="text-3xl font-black text-slate-900 dark:text-white"><?= $menunggu ?></p>
                            </div>

                            <div class="bg-amber-50 dark:bg-amber-950/40 text-amber-500 w-14 h-14 rounded-2xl flex items-center justify-center text-2xl group-hover:bg-amber-500 group-hover:text-white transition-colors shadow-inner">
                                <i class="fas fa-clock"></i>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-slate-900 p-5 sm:p-6 rounded-2xl sm:rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-sm hover:shadow-lg transition-all group">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-slate-400 dark:text-slate-500 text-[10px] font-extrabold uppercase tracking-widest mb-1">Tuntas</p>
                                <p class="text-3xl font-black text-slate-900 dark:text-white"><?= $tuntas ?></p>
                            </div>

                            <div class="bg-emerald-50 dark:bg-emerald-950/40 text-emerald-500 w-14 h-14 rounded-2xl flex items-center justify-center text-2xl group-hover:bg-emerald-500 group-hover:text-white transition-colors shadow-inner">
                                <i class="fas fa-check-double"></i>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- CTA -->
                <div class="bg-gradient-to-br from-blue-700 to-sky-600 rounded-2xl sm:rounded-3xl p-5 sm:p-8 text-white shadow-xl mb-6 sm:mb-10 relative overflow-hidden group">

                    <div class="absolute -right-6 -top-6 w-48 h-48 bg-white/10 rounded-full blur-2xl"></div>

                    <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">

                        <div>
                            <h3 class="text-xl sm:text-2xl font-black tracking-tight mb-2">
                                Ingin menyampaikan aspirasi baru?
                            </h3>

                            <p class="text-blue-100 text-xs sm:text-sm max-w-lg leading-relaxed font-medium">
                                Laporkan masalah, berikan ide kreatif, atau sampaikan saran positif untuk kemajuan fasilitas dan lingkungan sekolah kita bersama.
                            </p>
                        </div>

                        <a href="form.php"
                            class="w-full md:w-auto shrink-0 bg-white text-blue-700 px-5 sm:px-6 py-3.5 rounded-xl font-bold hover:bg-slate-100 shadow-lg transition-all active:scale-95 inline-flex items-center justify-center gap-2 text-xs uppercase tracking-wider">
                            <i class="fas fa-plus-circle text-blue-600"></i> Buat Aspirasi
                        </a>

                    </div>
                </div>

                <!-- TABEL ASPIRASI -->
                <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/80 dark:border-slate-800 overflow-hidden shadow-sm">

                    <div class="p-4 sm:p-6 border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/50 flex items-center gap-3">
                        <span class="bg-blue-100 dark:bg-blue-950/60 text-blue-700 dark:text-sky-400 p-2 rounded-xl">
                            <i class="fas fa-history text-xs"></i>
                        </span>

                        <h3 class="font-bold text-slate-800 dark:text-white text-sm tracking-wide">
                            Aspirasi Terakhir Kamu
                        </h3>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">

                            <thead class="bg-slate-50/80 dark:bg-slate-800/40 text-slate-400 dark:text-slate-500 border-b border-slate-100 dark:border-slate-800">
                                <tr>
                                    <th class="px-4 sm:px-6 py-4 text-left font-extrabold uppercase tracking-widest text-[10px]">Topik Aspirasi</th>
                                    <th class="px-4 sm:px-6 py-4 text-left font-extrabold uppercase tracking-widest text-[10px]">Kategori</th>
                                    <th class="px-4 sm:px-6 py-4 text-left font-extrabold uppercase tracking-widest text-[10px]">Status</th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">

                                <?php if ($total > 0): ?>

                                    <?php
                                    $limit_data = array_slice($list_aspirasi, 0, 5);

                                    foreach ($limit_data as $row):
                                        $status = $row['status'];
                                        $statusBadge = "bg-amber-100 text-amber-700 dark:bg-amber-950/60 dark:text-amber-400 border border-amber-200 dark:border-amber-800/50";

                                        if ($status == 'Selesai') {
                                            $statusBadge = "bg-emerald-100 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800/50";
                                        } elseif ($status == 'Diproses' || $status == 'Proses') {
                                            $statusBadge = "bg-sky-100 text-sky-700 dark:bg-sky-950/60 dark:text-sky-400 border border-sky-200 dark:border-sky-800/50";
                                        }
                                    ?>

                                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">

                                            <td class="px-4 sm:px-6 py-4">
                                                <p class="font-bold text-slate-800 dark:text-white text-sm">
                                                    <?= htmlspecialchars($row['judul']) ?>
                                                </p>
                                            </td>

                                            <td class="px-4 sm:px-6 py-4">
                                                <span class="inline-flex items-center gap-1 px-3 py-1 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 rounded-lg text-[10px] font-extrabold uppercase border border-slate-200 dark:border-slate-700">
                                                    <?= htmlspecialchars($row['nama_kategori'] ?? 'Umum') ?>
                                                </span>
                                            </td>

                                            <td class="px-4 sm:px-6 py-4">
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider <?= $statusBadge ?>">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-current mr-2 animate-pulse"></span>
                                                    <?= strtoupper($status) ?>
                                                </span>
                                            </td>

                                        </tr>

                                    <?php endforeach; ?>

                                <?php else: ?>

                                    <tr>
                                        <td colspan="3" class="px-6 py-20 text-center">

                                            <div class="flex flex-col items-center justify-center">

                                                <div class="w-16 h-16 bg-slate-50 dark:bg-slate-800 rounded-full flex items-center justify-center mb-4 border border-slate-100 dark:border-slate-700">
                                                    <i class="fas fa-inbox text-2xl text-slate-300 dark:text-slate-600"></i>
                                                </div>

                                                <p class="font-bold text-slate-700 dark:text-slate-300 text-sm">
                                                    Belum ada aspirasi yang dikirim.
                                                </p>

                                                <p class="text-xs text-slate-400 dark:text-slate-500 mt-1">
                                                    Mulai buat aspirasi pertamamu sekarang.
                                                </p>

                                            </div>

                                        </td>
                                    </tr>

                                <?php endif; ?>

                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </main>
    </div>

    <script>
        function openSidebar() {
            document.getElementById('sidebar').classList.remove('-translate-x-full');
            document.getElementById('sidebarOverlay').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeSidebar() {
            document.getElementById('sidebar').classList.add('-translate-x-full');
            document.getElementById('sidebarOverlay').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        function toggleTheme() {
            const html = document.documentElement;
            const themeIcon = document.getElementById('themeIcon');
            const themeIconMobile = document.getElementById('themeIconMobile');
            const themeText = document.getElementById('themeText');

            if (html.classList.contains('dark')) {
                html.classList.remove('dark');
                localStorage.theme = 'light';

                if (themeText) themeText.innerText = 'Dark Mode';
                if (themeIcon) themeIcon.className = 'fas fa-moon text-sky-400';
                if (themeIconMobile) themeIconMobile.className = 'fas fa-moon';
            } else {
                html.classList.add('dark');
                localStorage.theme = 'dark';

                if (themeText) themeText.innerText = 'Light Mode';
                if (themeIcon) themeIcon.className = 'fas fa-sun text-amber-400';
                if (themeIconMobile) themeIconMobile.className = 'fas fa-sun text-amber-400';
            }
        }

        if (
            localStorage.theme === 'dark' ||
            (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)
        ) {
            document.documentElement.classList.add('dark');

            const themeText = document.getElementById('themeText');
            const themeIcon = document.getElementById('themeIcon');
            const themeIconMobile = document.getElementById('themeIconMobile');

            if (themeText) themeText.innerText = 'Light Mode';
            if (themeIcon) themeIcon.className = 'fas fa-sun text-amber-400';
            if (themeIconMobile) themeIconMobile.className = 'fas fa-sun text-amber-400';
        }
    </script>

</body>
</html>