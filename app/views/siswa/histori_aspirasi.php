<?php

session_start();

require_once "../../models/m_aspirasi.php";

$model = new M_Aspirasi();

$id_siswa = $_SESSION['siswa']['id_siswa'] ?? 0;
$nama_siswa = $_SESSION['siswa']['nama_siswa'] ?? 'Siswa';

if ($id_siswa == 0) {
    header("Location: ../auth/login.php");
    exit;
}

$histori = $model->tampilHistoriSiswa($id_siswa);
?>

<!DOCTYPE html>
<html lang="id" class="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Histori Aspirasi - E-Lapor</title>

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

        <!-- OVERLAY MOBILE -->
        <div id="sidebarOverlay"
            onclick="toggleSidebar()"
            class="fixed inset-0 bg-black/50 z-40 hidden md:hidden">
        </div>

        <!-- SIDEBAR -->
        <aside id="sidebar"
            class="hidden md:flex flex-col w-72 bg-blue-950 dark:bg-slate-900 text-blue-100 dark:text-slate-200 p-6 fixed h-full shadow-2xl z-50 border-r border-blue-900/50 dark:border-slate-800">

            <!-- LOGO -->
            <div class="flex items-center justify-between mb-12 mt-2">
                <div class="flex items-center gap-4">
                    <div class="bg-gradient-to-br from-blue-500 to-sky-400 p-2.5 rounded-2xl text-white shadow-lg shadow-blue-500/30">
                        <i class="fas fa-bullhorn text-2xl"></i>
                    </div>

                    <div>
                        <h1 class="text-xl font-bold tracking-tight text-white">Aspirasi Care</h1>
                        <p class="text-[10px] text-sky-400 font-semibold tracking-wider uppercase">Portal Siswa</p>
                    </div>
                </div>

                <button onclick="toggleSidebar()" class="md:hidden text-blue-200 hover:text-white text-xl">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <!-- NAVIGATION -->
            <nav class="space-y-2 flex-1">
                <p class="text-[10px] font-bold uppercase tracking-wider text-blue-400/70 dark:text-slate-500 mb-3 px-3">
                    Menu Siswa
                </p>

                <a href="dashboard.php"
                    class="flex items-center gap-3.5 p-3.5 rounded-xl text-blue-200/80 dark:text-slate-400 hover:bg-blue-900/50 dark:hover:bg-slate-800 hover:text-white transition-all font-medium">
                    <i class="fas fa-chart-line w-5 text-sky-400"></i>
                    Dashboard
                </a>

                <a href="form.php"
                    class="flex items-center gap-3.5 p-3.5 rounded-xl text-blue-200/80 dark:text-slate-400 hover:bg-blue-900/50 dark:hover:bg-slate-800 hover:text-white transition-all font-medium">
                    <i class="fas fa-edit w-5 text-sky-400"></i>
                    Sampaikan Aspirasi
                </a>

                <a href="riwayat.php"
                    class="flex items-center gap-3.5 p-3.5 rounded-xl text-blue-200/80 dark:text-slate-400 hover:bg-blue-900/50 dark:hover:bg-slate-800 hover:text-white transition-all font-medium">
                    <i class="fas fa-history w-5 text-sky-400"></i>
                    Riwayat Saya
                </a>

                <!-- MENU AKTIF -->
                <a href="histori_aspirasi.php"
                    class="flex items-center gap-3.5 p-3.5 rounded-xl bg-gradient-to-r from-blue-600 to-sky-500 text-white shadow-lg shadow-blue-900/40 font-semibold">
                    <i class="fas fa-archive w-5 text-white"></i>
                    Histori Aspirasi
                </a>
            </nav>

            <!-- PROFILE -->
            <div class="mt-auto pt-6 border-t border-blue-900/80 dark:border-slate-800">

                <div class="flex items-center gap-3 p-3 mb-4 bg-blue-900/40 dark:bg-slate-800/60 rounded-2xl border border-blue-800/40 dark:border-slate-700">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-600 to-sky-500 flex items-center justify-center text-white font-bold shadow-md shrink-0">
                        <i class="fas fa-user text-sm"></i>
                    </div>

                    <div class="overflow-hidden">
                        <p class="text-sm font-bold truncate text-white">
                            <?= htmlspecialchars($nama_siswa) ?>
                        </p>
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
                    <i class="fas fa-sign-out-alt"></i>
                    Keluar
                </a>
            </div>
        </aside>

        <!-- MAIN -->
        <main class="flex-1 md:ml-72 p-4 sm:p-6 md:p-10 transition-colors duration-300">

            <!-- MOBILE HEADER -->
            <div class="flex md:hidden justify-between items-center mb-5 bg-white dark:bg-slate-900 p-3 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm">

                <div class="flex items-center gap-3 min-w-0">
                    <button onclick="toggleSidebar()"
                        class="p-2.5 bg-blue-600 rounded-xl text-white shrink-0">
                        <i class="fas fa-bars"></i>
                    </button>

                    <div class="min-w-0">
                        <h1 class="font-bold text-slate-800 dark:text-white text-sm truncate">
                            Aspirasi Care
                        </h1>
                        <p class="text-[9px] text-slate-400 dark:text-slate-500">
                            Histori Aspirasi
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-2 shrink-0">

                    <!-- THEME -->
                    <button onclick="toggleTheme()"
                        class="p-2.5 bg-slate-100 dark:bg-slate-800 rounded-xl text-slate-700 dark:text-slate-300">
                        <i id="themeIconMobile" class="fas fa-moon"></i>
                    </button>

                    <!-- LOGOUT MOBILE -->
                    <a href="../../../logout.php"
                        onclick="return confirm('Yakin ingin keluar?')"
                        class="p-2.5 bg-rose-50 dark:bg-rose-950/30 rounded-xl text-rose-500 dark:text-rose-400 border border-rose-100 dark:border-rose-900/40">
                        <i class="fas fa-sign-out-alt"></i>
                    </a>

                </div>
            </div>

            <div class="max-w-5xl mx-auto">

                <!-- HEADER -->
                <div class="mb-5 sm:mb-8 bg-white dark:bg-slate-900 p-5 sm:p-8 rounded-2xl sm:rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-sm transition-colors">
                    <h2 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white tracking-tight mb-1">
                        Histori Aspirasi 📚
                    </h2>

                    <p class="text-slate-500 dark:text-slate-400 text-xs sm:text-sm font-medium">
                        Riwayat aspirasi Anda yang telah selesai ditangani.
                    </p>
                </div>

                <!-- ========================= -->
                <!-- TAMPILAN DESKTOP -->
                <!-- ========================= -->

                <div class="hidden md:block bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/80 dark:border-slate-800 overflow-hidden shadow-sm transition-colors">

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">

                            <thead class="bg-slate-50/80 dark:bg-slate-800/40 text-slate-400 dark:text-slate-500 border-b border-slate-100 dark:border-slate-800">
                                <tr>
                                    <th class="px-6 py-4 text-left font-extrabold uppercase tracking-widest text-[10px]">
                                        Aspirasi
                                    </th>

                                    <th class="px-6 py-4 text-left font-extrabold uppercase tracking-widest text-[10px]">
                                        Tanggapan
                                    </th>

                                    <th class="px-6 py-4 text-center font-extrabold uppercase tracking-widest text-[10px]">
                                        Rating Anda
                                    </th>

                                    <th class="px-6 py-4 text-center font-extrabold uppercase tracking-widest text-[10px]">
                                        Status & Tanggal
                                    </th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">

                                <?php if ($histori && $histori->num_rows > 0): ?>

                                    <?php while ($row = $histori->fetch_assoc()): ?>

                                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors group">

                                            <!-- ASPIRASI -->
                                            <td class="px-6 py-5">
                                                <p class="font-bold text-slate-800 dark:text-white group-hover:text-blue-600 dark:group-hover:text-sky-400 transition-colors">
                                                    <?= htmlspecialchars($row['judul']) ?>
                                                </p>

                                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 max-w-[300px] leading-relaxed font-medium">
                                                    <?= htmlspecialchars($row['isi_aspirasi']) ?>
                                                </p>
                                            </td>

                                            <!-- FEEDBACK -->
                                            <td class="px-6 py-5">
                                                <p class="text-sm text-slate-600 dark:text-slate-300 max-w-[300px] bg-slate-50 dark:bg-slate-800/60 p-4 rounded-2xl border border-slate-100 dark:border-slate-700/80 font-medium">
                                                    <?= htmlspecialchars($row['feedback'] ?: 'Tidak ada keterangan tambahan.') ?>
                                                </p>
                                            </td>

                                            <!-- RATING -->
                                            <td class="px-6 py-5 text-center">
                                                <div class="flex justify-center items-center gap-1">
                                                    <?php
                                                    $rating = (float) $row['rating'];

                                                    for ($i = 1; $i <= 5; $i++):
                                                    ?>

                                                        <?php if ($rating >= $i): ?>
                                                            <i class="fas fa-star text-amber-400"></i>
                                                        <?php else: ?>
                                                            <i class="far fa-star text-slate-300 dark:text-slate-700"></i>
                                                        <?php endif; ?>

                                                    <?php endfor; ?>
                                                </div>

                                                <span class="inline-block mt-2 text-[10px] font-black text-slate-700 dark:text-slate-200 bg-slate-100 dark:bg-slate-800 px-3 py-1 rounded-lg border border-slate-200 dark:border-slate-700">
                                                    <?= number_format($rating, 1) ?> / 5
                                                </span>
                                            </td>

                                            <!-- STATUS -->
                                            <td class="px-6 py-5 text-center">
                                                <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-[10px] font-black bg-emerald-100 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800/50 uppercase tracking-wider">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-current animate-pulse"></span>
                                                    SELESAI
                                                </span>

                                                <p class="text-xs font-semibold text-slate-400 dark:text-slate-500 mt-2">
                                                    <?= htmlspecialchars($row['tanggal_selesai']) ?>
                                                </p>
                                            </td>

                                        </tr>

                                    <?php endwhile; ?>

                                <?php else: ?>

                                    <tr>
                                        <td colspan="4" class="px-6 py-20 text-center">

                                            <div class="flex flex-col items-center justify-center">

                                                <div class="w-20 h-20 bg-slate-50 dark:bg-slate-800 rounded-full flex items-center justify-center mb-4 border border-slate-100 dark:border-slate-700">
                                                    <i class="fas fa-history text-3xl text-slate-300 dark:text-slate-600"></i>
                                                </div>

                                                <p class="font-bold text-slate-700 dark:text-slate-300 text-base">
                                                    Belum ada histori aspirasi.
                                                </p>

                                                <p class="text-xs text-slate-400 dark:text-slate-500 mt-1 max-w-sm mx-auto leading-relaxed font-medium">
                                                    Aspirasi yang sudah selesai dan diberi rating akan muncul di sini.
                                                </p>

                                            </div>

                                        </td>
                                    </tr>

                                <?php endif; ?>

                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- ========================= -->
                <!-- TAMPILAN MOBILE -->
                <!-- ========================= -->

                <div class="md:hidden space-y-4">

                    <?php if ($histori && $histori->num_rows > 0): ?>

                        <?php
                        $histori->data_seek(0);
                        ?>

                        <?php while ($row = $histori->fetch_assoc()): ?>

                            <?php $rating = (float) $row['rating']; ?>

                            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-sm p-4">

                                <!-- JUDUL -->
                                <div class="mb-4">
                                    <div class="flex items-start justify-between gap-3">

                                        <div class="min-w-0">
                                            <p class="text-[10px] font-extrabold uppercase tracking-widest text-slate-400 dark:text-slate-500 mb-1">
                                                Aspirasi
                                            </p>

                                            <h3 class="font-bold text-slate-800 dark:text-white text-sm leading-snug break-words">
                                                <?= htmlspecialchars($row['judul']) ?>
                                            </h3>
                                        </div>

                                        <span class="shrink-0 inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[9px] font-black bg-emerald-100 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800/50">
                                            <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                                            SELESAI
                                        </span>

                                    </div>
                                </div>

                                <!-- ISI ASPIRASI -->
                                <div class="mb-3">
                                    <p class="text-[10px] font-extrabold uppercase tracking-widest text-slate-400 dark:text-slate-500 mb-1.5">
                                        Isi Aspirasi
                                    </p>

                                    <div class="p-3.5 bg-slate-50 dark:bg-slate-800/60 rounded-xl border border-slate-100 dark:border-slate-700/80">
                                        <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed font-medium break-words">
                                            <?= htmlspecialchars($row['isi_aspirasi']) ?>
                                        </p>
                                    </div>
                                </div>

                                <!-- TANGGAPAN -->
                                <div class="mb-3">
                                    <p class="text-[10px] font-extrabold uppercase tracking-widest text-slate-400 dark:text-slate-500 mb-1.5">
                                        Tanggapan
                                    </p>

                                    <div class="p-3.5 bg-blue-50/60 dark:bg-slate-800/60 rounded-xl border border-blue-100 dark:border-slate-700/80">
                                        <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed font-medium break-words">
                                            <?= htmlspecialchars($row['feedback'] ?: 'Tidak ada keterangan tambahan.') ?>
                                        </p>
                                    </div>
                                </div>

                                <!-- RATING + TANGGAL -->
                                <div class="flex items-center justify-between gap-3 pt-3 border-t border-slate-100 dark:border-slate-800">

                                    <div>
                                        <p class="text-[9px] font-extrabold uppercase tracking-widest text-slate-400 dark:text-slate-500 mb-1">
                                            Rating
                                        </p>

                                        <div class="flex items-center gap-1">
                                            <?php for ($i = 1; $i <= 5; $i++): ?>

                                                <?php if ($rating >= $i): ?>
                                                    <i class="fas fa-star text-amber-400 text-xs"></i>
                                                <?php else: ?>
                                                    <i class="far fa-star text-slate-300 dark:text-slate-700 text-xs"></i>
                                                <?php endif; ?>

                                            <?php endfor; ?>

                                            <span class="ml-1 text-[10px] font-black text-slate-600 dark:text-slate-300">
                                                <?= number_format($rating, 1) ?>/5
                                            </span>
                                        </div>
                                    </div>

                                    <div class="text-right">
                                        <p class="text-[9px] font-extrabold uppercase tracking-widest text-slate-400 dark:text-slate-500 mb-1">
                                            Selesai
                                        </p>

                                        <p class="text-[10px] font-semibold text-slate-500 dark:text-slate-400">
                                            <?= htmlspecialchars($row['tanggal_selesai']) ?>
                                        </p>
                                    </div>

                                </div>

                            </div>

                        <?php endwhile; ?>

                    <?php else: ?>

                        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-10 text-center">

                            <div class="w-16 h-16 bg-slate-50 dark:bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-4 border border-slate-100 dark:border-slate-700">
                                <i class="fas fa-history text-2xl text-slate-300 dark:text-slate-600"></i>
                            </div>

                            <p class="font-bold text-slate-700 dark:text-slate-300 text-sm">
                                Belum ada histori aspirasi.
                            </p>

                            <p class="text-xs text-slate-400 dark:text-slate-500 mt-1 leading-relaxed">
                                Aspirasi yang sudah selesai dan diberi rating akan muncul di sini.
                            </p>

                        </div>

                    <?php endif; ?>

                </div>

            </div>
        </main>
    </div>

    <script>
        // SIDEBAR MOBILE
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');

            if (sidebar.classList.contains('hidden')) {
                sidebar.classList.remove('hidden');
                sidebar.classList.add('flex');
                overlay.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            } else {
                sidebar.classList.add('hidden');
                sidebar.classList.remove('flex');
                overlay.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }
        }

        // DARK MODE
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