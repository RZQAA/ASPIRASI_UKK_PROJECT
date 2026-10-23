<?php
session_start();

require_once "../../models/m_aspirasi.php";

$model = new M_Aspirasi();
$histori = $model->tampilHistori();
?>

<!DOCTYPE html>
<html lang="id" class="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Histori Aspirasi - E-Lapor Guru</title>

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
    </style>
</head>

<body class="bg-zinc-50 dark:bg-zinc-950 text-zinc-800 dark:text-zinc-100 antialiased selection:bg-teal-200 selection:text-teal-900 transition-colors duration-300">

    <div class="min-h-screen flex">
        <button onclick="openSidebar()"
            class="md:hidden fixed top-5 left-5 z-30 bg-teal-600 text-white w-12 h-12 rounded-xl shadow-lg flex items-center justify-center">
            <i class="fas fa-bars"></i>
        </button>
        <div
            id="sidebarOverlay"
            onclick="closeSidebar()"
            class="fixed inset-0 bg-black/50 backdrop-blur-sm z-30 hidden md:hidden">
        </div>
        <!-- Sidebar -->
        <aside
            id="mobileSidebar"
            class="fixed top-0 left-0 md:flex flex-col w-72 h-screen bg-teal-950 dark:bg-zinc-900 text-teal-100 dark:text-zinc-300 p-6 shadow-2xl z-40 border-r border-teal-900/40 dark:border-zinc-800 -translate-x-full md:translate-x-0 transition-transform duration-300">
            <!-- Logo Area -->
            <div class="flex items-center gap-4 mb-12 mt-8">
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
                <a href="daftar_aspirasi.php" class="flex items-center gap-3 p-3.5 rounded-xl text-teal-200/80 dark:text-zinc-400 hover:bg-teal-900 dark:hover:bg-zinc-800 hover:text-white transition-all group">
                    <i class="fas fa-list-ul w-5 text-teal-600/70 group-hover:text-teal-400 transition-colors"></i>
                    <span class="font-medium">Semua Aspirasi</span>
                </a>
                <a href="histori_aspirasi.php" class="flex items-center gap-3 p-3.5 rounded-xl bg-gradient-to-r from-teal-500 to-teal-600 text-white shadow-md shadow-teal-900/50 transform transition-transform hover:scale-[1.02]">
                    <i class="fas fa-history w-5 text-teal-100"></i>
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
        <main class="flex-1 md:ml-72 pt-24 md:pt-10 px-4 md:px-6 relative transition-colors duration-300 overflow-x-hidden">
            <div class="mb-10 bg-white dark:bg-zinc-900 p-6 rounded-2xl shadow-sm border border-zinc-200/60 dark:border-zinc-800 transition-colors">
                <h2 class="text-3xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-zinc-800 to-teal-700 dark:from-zinc-100 dark:to-teal-400 mb-1">
                    Histori Aspirasi 📚
                </h2>
                <p class="text-zinc-500 dark:text-zinc-400 text-sm font-medium">
                    Daftar aspirasi yang telah selesai dan diberikan rating oleh siswa.
                </p>
            </div>

            <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200/60 dark:border-zinc-800 shadow-sm overflow-hidden">
                <!-- ================= DESKTOP TABLE ================= -->
                <div class="hidden md:block overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-zinc-50/80 dark:bg-zinc-800/50 text-zinc-500 dark:text-zinc-400 border-b border-zinc-100 dark:border-zinc-800">
                            <tr>
                                <th class="px-6 py-4 text-left text-[11px] uppercase">
                                    Pelapor
                                </th>
                                <th class="px-6 py-4 text-left text-[11px] uppercase">
                                    Aspirasi
                                </th>
                                <th class="px-6 py-4 text-center text-[11px] uppercase">
                                    Status
                                </th>
                                <th class="px-6 py-4 text-center text-[11px] uppercase">
                                    Rating
                                </th>
                                <th class="px-6 py-4 text-center text-[11px] uppercase">
                                    Tanggal Selesai
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                            <?php if ($histori && $histori->num_rows > 0): ?>
                                <?php while ($row = $histori->fetch_assoc()): ?>
                                    <tr class="hover:bg-teal-50/30 dark:hover:bg-zinc-800/40 transition">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <div class="w-9 h-9 rounded-full bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center font-bold">
                                                    <?= substr(htmlspecialchars($row['nama_siswa']), 0, 1) ?>
                                                </div>
                                                <div>
                                                    <p class="font-bold">
                                                        <?= htmlspecialchars($row['nama_siswa']) ?>
                                                    </p>
                                                    <p class="text-xs text-zinc-400">
                                                        ID #ASP-<?= htmlspecialchars($row['id_aspirasi']) ?>
                                                    </p>
                                            </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <p class="font-semibold">
                                                <?= htmlspecialchars($row['judul']) ?>
                                            </p>
                                            <p class="text-xs text-zinc-500">
                                                <?= htmlspecialchars($row['isi_aspirasi']) ?>
                                            </p>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <span class="px-3 py-1 rounded-full text-xs bg-emerald-100 text-emerald-700">
                                                SELESAI
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-center text-amber-400">
                                            ⭐ <?= number_format($row['rating'], 1) ?>
                                        </td>
                                        <td class="px-6 py-4 text-center text-xs">
                                            <?= htmlspecialchars($row['tanggal_selesai']) ?>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center py-10 text-zinc-400">
                                        Belum ada histori aspirasi.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- ================= MOBILE CARD ================= -->
                <div class="md:hidden p-4 space-y-4">
                    <?php
                    $historiMobile = $model->tampilHistori();
                    ?>
                    <?php if ($historiMobile && $historiMobile->num_rows > 0): ?>
                        <?php while ($row = $historiMobile->fetch_assoc()): ?>
                            <div class="bg-zinc-50 dark:bg-zinc-800 rounded-2xl p-4 border border-zinc-200 dark:border-zinc-700">
                                <div class="flex items-center gap-3 mb-4">
                                    <div class="w-10 h-10 rounded-full bg-teal-100 text-teal-700 flex items-center justify-center font-bold">
                                        <?= substr(htmlspecialchars($row['nama_siswa']), 0, 1) ?>
                                    </div>
                                    <div>
                                        <p class="font-bold">
                                            <?= htmlspecialchars($row['nama_siswa']) ?>
                                        </p>
                                        <p class="text-xs text-zinc-400">
                                            ID #ASP-<?= htmlspecialchars($row['id_aspirasi']) ?>
                                        </p>
                                    </div>
                                </div>
                                <p class="font-semibold text-sm">
                                    <?= htmlspecialchars($row['judul']) ?>
                                </p>
                                <p class="text-sm text-zinc-500 mt-1 mb-4">
                                    <?= htmlspecialchars($row['isi_aspirasi']) ?>
                                </p>
                                <div class="flex justify-between items-center">
                                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700">
                                        SELESAI
                                    </span>
                                    <span class="text-amber-500 font-bold">
                                        ⭐ <?= number_format($row['rating'], 1) ?>
                                    </span>
                                </div>
                                <p class="text-xs text-zinc-400 mt-3">
                                    <?= htmlspecialchars($row['tanggal_selesai']) ?>
                                </p>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <div class="text-center py-10 text-zinc-400">
                            Belum ada histori aspirasi.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </main>

        <script>
            function openSidebar() {
                document.getElementById('mobileSidebar').classList.remove('-translate-x-full');
                document.getElementById('sidebarOverlay').classList.remove('hidden');
            }

            function closeSidebar() {
                document.getElementById('mobileSidebar').classList.add('-translate-x-full');
                document.getElementById('sidebarOverlay').classList.add('hidden');
            }

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
        </script>
</body>

</html>