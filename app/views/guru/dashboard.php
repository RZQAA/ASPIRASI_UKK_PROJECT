<?php
require_once "../../models/m_aspirasi.php";
require_once "../auth/auth.php";

cekAdmin();

$model = new M_Aspirasi();

$stats = $model->hitungStatistik();
$semua_aspirasi = $model->tampilSemua();

?>
<!DOCTYPE html>
<html lang="id" class="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Guru - E-Lapor</title>
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


        /* mobile sidebar animation */

        #mobileSidebar,
        #sidebarOverlay {
            transition: .3s ease;
        }
    </style>

</head>

<body class="bg-zinc-50 dark:bg-zinc-950 text-zinc-800 dark:text-zinc-100 antialiased selection:bg-teal-200 selection:text-teal-900 transition-colors duration-300">

    <div class="min-h-screen flex">
        <!-- MOBILE OVERLAY -->
        <div
            id="sidebarOverlay"
            onclick="closeSidebar()"
            class="fixed inset-0 bg-black/40 backdrop-blur-sm z-30 hidden md:hidden">
        </div>
        <!-- MOBILE BUTTON -->
        <button
            onclick="openSidebar()"
            class="md:hidden fixed top-5 left-5 z-40 bg-teal-900 text-white w-11 h-11 rounded-xl shadow-lg flex items-center justify-center">
            <i class="fas fa-bars"></i>
        </button>
        <!-- SIDEBAR -->

        <aside
            id="mobileSidebar"
            class="fixed md:flex flex-col w-72 bg-teal-950 dark:bg-zinc-900 text-teal-100 dark:text-zinc-300 p-6 h-full shadow-2xl z-40 border-r border-teal-900/40 dark:border-zinc-800 -translate-x-full md:translate-x-0">
            <!-- Logo -->
            <div class="flex items-center gap-4 mb-12 mt-2">
                <div class="bg-gradient-to-br from-teal-400 to-emerald-500 p-2.5 rounded-xl text-white shadow-lg shadow-teal-500/30">
                    <i class="fas fa-chalkboard-teacher text-2xl"></i>
                </div>
                <h1 class="text-2xl font-bold text-white tracking-tight">
                    E-Lapor
                </h1>
            </div>
            <!-- Navigation -->
            <nav class="space-y-3 flex-1">
                <p class="text-[10px] font-bold uppercase tracking-wider text-teal-600/70 dark:text-zinc-500 mb-4 px-2">
                    Menu Utama
                </p>
                <a href="dashboard.php"
                    class="flex items-center gap-3 p-3.5 rounded-xl bg-gradient-to-r from-teal-500 to-teal-600 text-white shadow-md shadow-teal-900/50 transform transition-transform hover:scale-[1.02]">
                    <i class="fas fa-th-large w-5 text-teal-100"></i>
                    <span class="font-medium">
                        Dashboard
                    </span>
                </a>
                <a href="daftar_aspirasi.php"
                    class="flex items-center gap-3 p-3.5 rounded-xl text-teal-200/80 dark:text-zinc-400 hover:bg-teal-900 dark:hover:bg-zinc-800 hover:text-white transition-all group">
                    <i class="fas fa-list-ul w-5 text-teal-600/70 group-hover:text-teal-400 transition-colors"></i>
                    <span class="font-medium">
                        Semua Aspirasi
                    </span>
                </a>
                <a href="histori_aspirasi.php"
                    class="flex items-center gap-3 p-3.5 rounded-xl text-teal-200/80 dark:text-zinc-400 hover:bg-teal-900 dark:hover:bg-zinc-800 hover:text-white transition-all group">
                    <i class="fas fa-history w-5 text-teal-600/70 group-hover:text-teal-400 transition-colors"></i>
                    <span class="font-medium">
                        Histori Aspirasi
                    </span>
                </a>
            </nav>
            <!-- PROFILE -->
            <div class="mt-auto pt-6 border-t border-teal-900/80 dark:border-zinc-800">
                <div class="flex items-center gap-3 p-3 bg-teal-900/50 dark:bg-zinc-800/60 rounded-xl mb-4 border border-teal-800/50 dark:border-zinc-700">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-teal-700 to-teal-800 flex items-center justify-center border-2 border-teal-600 shrink-0">
                        <i class="fas fa-user-tie text-white text-sm"></i>
                    </div>
                    <div class="overflow-hidden">
                        <p class="text-sm font-semibold truncate text-white">
                            Guru / Admin
                        </p>
                        <p class="text-xs text-teal-400 dark:text-teal-300 font-medium">
                            Staff Sekolah
                        </p>
                    </div>
                </div>
                <div class="flex items-center gap-2 mb-3">
                    <button onclick="toggleTheme()"
                        class="flex-1 flex items-center justify-center gap-2 p-2.5 bg-teal-900/30 dark:bg-zinc-800 hover:bg-teal-900/60 dark:hover:bg-zinc-700 rounded-xl text-xs font-semibold text-teal-200 dark:text-zinc-300 transition-all border border-teal-800/40 dark:border-zinc-700">
                        <i id="themeIcon" class="fas fa-moon text-teal-400"></i>
                        <span id="themeText">
                            Dark Mode
                        </span>
                    </button>
                </div>
                <a href="../../../logout.php"
                    class="w-full flex items-center justify-center gap-2 p-3 text-red-400 hover:bg-red-500/10 hover:text-red-300 rounded-xl transition-all text-sm font-semibold border border-red-500/20">
                    <i class="fas fa-sign-out-alt"></i>
                    Keluar
                </a>
            </div>
        </aside>

        <!-- Main Content -->

       <main class="flex-1 md:ml-72 pt-24 md:pt-10 p-6 relative transition-colors duration-300">

            <!-- Header -->
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-10 bg-white dark:bg-zinc-900 p-6 rounded-2xl shadow-sm border border-zinc-200/60 dark:border-zinc-800 transition-colors">
                <div>
                    <h2 class="text-3xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-zinc-800 to-teal-700 dark:from-zinc-100 dark:to-teal-400 mb-1">
                        Panel Kendali Guru 👋
                    </h2>

                    <p class="text-zinc-500 dark:text-zinc-400 text-sm font-medium">
                        Monitor dan respon aspirasi siswa secara real-time.
                    </p>
                </div>

                <div class="flex items-center gap-4">
                    <div class="text-right hidden sm:block bg-zinc-50 dark:bg-zinc-800/60 p-3 rounded-xl border border-zinc-100 dark:border-zinc-700/60">

                        <p class="text-sm font-bold text-zinc-700 dark:text-zinc-200">
                            <i class="far fa-calendar-alt text-teal-600 dark:text-teal-400 mr-1"></i>
                            <?= date('l, d M Y') ?>
                        </p>

                        <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-1">
                            Status Sistem:
                            <span class="inline-flex items-center gap-1 text-teal-600 dark:text-teal-400 font-semibold">
                                <span class="w-2 h-2 rounded-full bg-teal-500 animate-pulse"></span>
                                Aktif
                            </span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Stat Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
                <!-- Total -->
                <div class="bg-white dark:bg-zinc-900 p-6 rounded-2xl border border-zinc-200/60 dark:border-zinc-800 shadow-sm hover:shadow-lg hover:shadow-teal-900/5 hover:-translate-y-1 transition-all duration-300 group">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-zinc-400 dark:text-zinc-500 text-[11px] font-bold uppercase tracking-wider mb-1">
                                Total Masuk
                            </p>
                            <p class="text-3xl font-black text-zinc-800 dark:text-zinc-100">
                                <?= $stats['total'] ?>
                            </p>
                        </div>
                        <div class="bg-teal-50 dark:bg-teal-950/60 text-teal-600 dark:text-teal-400 w-14 h-14 rounded-2xl flex items-center justify-center text-2xl group-hover:bg-teal-600 group-hover:text-white transition-colors duration-300 shadow-inner">
                            <i class="fas fa-envelope-open-text"></i>
                        </div>
                    </div>
                </div>

                <!-- Menunggu -->
                <div class="bg-white dark:bg-zinc-900 p-6 rounded-2xl border border-zinc-200/60 dark:border-zinc-800 shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300 group relative overflow-hidden">
                    <div class="absolute left-0 top-0 w-1 h-full bg-amber-400"></div>
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-zinc-400 dark:text-zinc-500 text-[11px] font-bold uppercase tracking-wider mb-1">
                                Perlu Respon
                            </p>
                            <p class="text-3xl font-black text-zinc-800 dark:text-zinc-100">
                                <?= $stats['menunggu'] ?>
                            </p>
                        </div>
                        <div class="bg-amber-50 dark:bg-amber-950/60 text-amber-500 w-14 h-14 rounded-2xl flex items-center justify-center text-2xl group-hover:bg-amber-500 group-hover:text-white transition-colors duration-300 shadow-inner">

                            <i class="fas fa-exclamation-circle"></i>
                        </div>
                    </div>
                </div>
                <!-- Content Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">

                    <!-- Table Section -->
                    <div class="lg:col-span-2 min-w-0">
                        <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200/60 dark:border-zinc-800 overflow-hidden shadow-sm hover:shadow-md transition-shadow">

                            <div class="p-6 border-b border-zinc-100 dark:border-zinc-800 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 bg-zinc-50/50 dark:bg-zinc-800/40">
                                <h3 class="font-bold text-zinc-800 dark:text-zinc-100 text-sm tracking-wide flex items-center gap-3">
                                    <span class="bg-teal-100 dark:bg-teal-950/60 text-teal-700 dark:text-teal-400 p-1.5 rounded-lg">
                                        <i class="fas fa-stream text-xs"></i>
                                    </span>
                                    Antrean Aspirasi Terbaru
                                </h3>

                                <a href="daftar_aspirasi.php" class="text-teal-600 dark:text-teal-400 text-xs font-bold hover:text-teal-800 dark:hover:text-teal-300 hover:underline flex items-center gap-1">
                                    Lihat Semua <i class="fas fa-arrow-right text-[10px]"></i>
                                </a>
                            </div>

                            <div class="overflow-x-auto">
                                <table class="w-full text-sm min-w-[650px]">
                                    <thead class="bg-zinc-50/80 dark:bg-zinc-800/50 text-zinc-500 dark:text-zinc-400 border-b border-zinc-100 dark:border-zinc-800">
                                        <tr>
                                            <th class="px-6 py-4 text-left font-semibold text-xs uppercase tracking-wider">Pelapor</th>
                                            <th class="px-6 py-4 text-left font-semibold text-xs uppercase tracking-wider">Topik</th>
                                            <th class="px-6 py-4 text-left font-semibold text-xs uppercase tracking-wider">Status</th>
                                            <th class="px-6 py-4 text-center font-semibold text-xs uppercase tracking-wider">Aksi</th>
                                        </tr>
                                    </thead>

                                    <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                                        <?php if ($semua_aspirasi->num_rows > 0): ?>
                                            <?php while ($row = $semua_aspirasi->fetch_assoc()):
                                                $status = $row['status'];
                                                $color = ($status == 'Selesai') ? 'emerald' : (($status == 'Proses') ? 'sky' : 'amber');
                                            ?>

                                                <tr class="hover:bg-teal-50/30 dark:hover:bg-zinc-800/40 transition-colors group">
                                                    <td class="px-6 py-4">
                                                        <div class="flex items-center gap-3">
                                                            <div class="w-8 h-8 rounded-full bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center text-zinc-500 dark:text-zinc-400 text-xs font-bold border border-zinc-200 dark:border-zinc-700">
                                                                <?= substr(htmlspecialchars($row['nama_siswa']), 0, 1) ?>
                                                            </div>

                                                            <div>
                                                                <p class="font-bold text-zinc-700 dark:text-zinc-200 group-hover:text-teal-700 dark:group-hover:text-teal-400 transition-colors">
                                                                    <?= htmlspecialchars($row['nama_siswa']) ?>
                                                                </p>

                                                                <p class="text-[10px] font-medium text-zinc-400">
                                                                    ID: #<?= $row['id_input'] ?>
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>

                                            <?php endwhile; ?>
                                        <?php else: ?>

                                            <tr>
                                                <td colspan="4" class="px-6 py-12 text-center text-zinc-400">
                                                    <div class="flex flex-col items-center justify-center">
                                                        <i class="fas fa-inbox text-4xl mb-3 text-zinc-300 dark:text-zinc-600"></i>
                                                        <p class="font-medium">Belum ada aspirasi masuk.</p>
                                                    </div>
                                                </td>
                                            </tr>

                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>
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

                    if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                        document.documentElement.classList.add('dark');
                        const themeText = document.getElementById('themeText');
                        const themeIcon = document.getElementById('themeIcon');
                        if (themeText) themeText.innerText = 'Light Mode';
                        if (themeIcon) themeIcon.className = 'fas fa-sun text-amber-400';
                    }

                    function openSidebar() {
                        const sidebar = document.getElementById('mobileSidebar');
                        const overlay = document.getElementById('sidebarOverlay');

                        sidebar.classList.remove('-translate-x-full');
                        overlay.classList.remove('hidden');
                    }

                    function closeSidebar() {
                        const sidebar = document.getElementById('mobileSidebar');
                        const overlay = document.getElementById('sidebarOverlay');

                        sidebar.classList.add('-translate-x-full');
                        overlay.classList.add('hidden');
                    }
                </script>
</body>

</html>