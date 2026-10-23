<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once "../../models/m_aspirasi.php";

$model = new M_Aspirasi();

$id_siswa = $_SESSION['siswa']['id_siswa'] ?? 0;
$nama_siswa = $_SESSION['siswa']['nama_siswa'] ?? 'Siswa';

if ($id_siswa == 0) {
    header("Location: ../auth/login.php");
    exit;
}

// ===============================
// FILTER + PAGINATION
// ===============================

$per_halaman = 15;

// Ambil filter
$filter = $_GET['filter'] ?? '';

// Filter yang diperbolehkan
$filter_valid = ['', 'Menunggu', 'Diproses', 'Selesai'];

if (!in_array($filter, $filter_valid)) {
    $filter = '';
}

// Ambil halaman
$halaman = isset($_GET['halaman']) ? (int) $_GET['halaman'] : 1;

if ($halaman < 1) {
    $halaman = 1;
}

// Hitung total berdasarkan filter
$total_laporan = $model->hitungTotalSiswa(
    $id_siswa,
    $filter
);

// Hitung jumlah halaman
$total_halaman = ceil($total_laporan / $per_halaman);

if ($total_halaman > 0 && $halaman > $total_halaman) {
    $halaman = $total_halaman;
}

// Hitung offset
$offset = ($halaman - 1) * $per_halaman;

// Ambil data
$aspirasi_list = $model->tampilSiswa(
    $id_siswa,
    $per_halaman,
    $offset,
    $filter
);

$ratingSuccess = isset($_GET['rating']) && $_GET['rating'] === 'success';
$ratingFailed = isset($_GET['rating']) && $_GET['rating'] === 'failed';

?>

<!DOCTYPE html>
<html lang="id" class="light">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Riwayat Aspirasi - E-Lapor</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"
        rel="stylesheet">

    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">

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

        @keyframes fadeIn {

            from {
                opacity: 0;
                transform: scale(0.95);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }

        }

        .animate-modal {
            animation: fadeIn 0.2s ease-out forwards;
        }

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


<body
    class="bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 font-sans antialiased selection:bg-blue-200 selection:text-blue-900 transition-colors duration-300">


    <!-- =============================== -->
    <!-- NOTIFICATION RATING -->
    <!-- =============================== -->

    <?php if ($ratingSuccess || $ratingFailed): ?>

        <div
            id="ratingNotification"
            class="fixed inset-0 z-[999] flex items-center justify-center bg-slate-950/40 backdrop-blur-sm">

            <div
                id="ratingNotificationBox"
                class="bg-white dark:bg-slate-900 rounded-3xl shadow-2xl p-8 w-[90%] max-w-sm text-center scale-90 opacity-0 transition-all duration-500">

                <?php if ($ratingSuccess): ?>

                    <div
                        class="w-20 h-20 mx-auto mb-5 rounded-full bg-emerald-100 dark:bg-emerald-950/40 flex items-center justify-center">

                        <i class="fas fa-check text-4xl text-emerald-500"></i>

                    </div>

                    <h3 class="text-xl font-black text-slate-800 dark:text-white">
                        Rating Berhasil!
                    </h3>

                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-2">
                        Rating kamu berhasil disimpan.
                    </p>

                <?php else: ?>

                    <div
                        class="w-20 h-20 mx-auto mb-5 rounded-full bg-rose-100 dark:bg-rose-950/40 flex items-center justify-center">

                        <i class="fas fa-times text-4xl text-rose-500"></i>

                    </div>

                    <h3 class="text-xl font-black text-slate-800 dark:text-white">
                        Rating Gagal
                    </h3>

                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-2">
                        Rating gagal disimpan. Silakan coba lagi.
                    </p>

                <?php endif; ?>

            </div>

        </div>

    <?php endif; ?>


    <div class="min-h-screen flex">


        <!-- =============================== -->
        <!-- SIDEBAR -->
        <!-- =============================== -->

        <aside
            id="sidebar"
            class="fixed inset-y-0 left-0 w-72 bg-blue-950 dark:bg-slate-900 text-blue-100 dark:text-slate-200 p-6 shadow-2xl z-50 border-r border-blue-900/50 dark:border-slate-800 flex flex-col -translate-x-full md:translate-x-0 transition-transform duration-300">


            <!-- CLOSE MOBILE -->
            <div class="flex md:hidden justify-end mb-4">

                <button
                    onclick="toggleSidebar()"
                    class="p-2 text-blue-200 hover:text-white hover:bg-blue-900/50 rounded-xl transition-all">

                    <i class="fas fa-times text-lg"></i>

                </button>

            </div>


            <!-- LOGO -->

            <div class="flex items-center gap-4 mb-12 mt-2">

                <div
                    class="bg-gradient-to-br from-blue-500 to-sky-400 p-2.5 rounded-2xl text-white shadow-lg shadow-blue-500/30">

                    <i class="fas fa-bullhorn text-2xl"></i>

                </div>

                <div>

                    <h1 class="text-xl font-bold tracking-tight text-white">
                        Aspirasi Care
                    </h1>

                    <p class="text-[10px] text-sky-400 font-semibold tracking-wider uppercase">
                        Portal Siswa
                    </p>

                </div>

            </div>


            <!-- NAVIGATION -->

            <nav class="space-y-2 flex-1">

                <p
                    class="text-[10px] font-bold uppercase tracking-wider text-blue-400/70 dark:text-slate-500 mb-3 px-3">

                    Menu Siswa

                </p>


                <a
                    href="dashboard.php"
                    class="flex items-center gap-3.5 p-3.5 rounded-xl text-blue-200/80 dark:text-slate-400 hover:bg-blue-900/50 dark:hover:bg-slate-800 hover:text-white transition-all font-medium">

                    <i class="fas fa-chart-line w-5 text-sky-400"></i>

                    Dashboard

                </a>


                <a
                    href="form.php"
                    class="flex items-center gap-3.5 p-3.5 rounded-xl text-blue-200/80 dark:text-slate-400 hover:bg-blue-900/50 dark:hover:bg-slate-800 hover:text-white transition-all font-medium">

                    <i class="fas fa-edit w-5 text-sky-400"></i>

                    Sampaikan Aspirasi

                </a>


                <a
                    href="riwayat.php"
                    class="flex items-center gap-3.5 p-3.5 rounded-xl bg-gradient-to-r from-blue-600 to-sky-500 text-white shadow-lg shadow-blue-900/40 font-semibold">

                    <i class="fas fa-history w-5 text-white"></i>

                    Riwayat Saya

                </a>


                <a
                    href="histori_aspirasi.php"
                    class="flex items-center gap-3.5 p-3.5 rounded-xl text-blue-200/80 dark:text-slate-400 hover:bg-blue-900/50 dark:hover:bg-slate-800 hover:text-white transition-all font-medium">

                    <i class="fas fa-archive w-5 text-sky-400"></i>

                    Histori Aspirasi

                </a>

            </nav>


            <!-- PROFILE + LOGOUT -->

            <div
                class="mt-auto pt-6 border-t border-blue-900/80 dark:border-slate-800">


                <div
                    class="flex items-center gap-3 p-3 mb-4 bg-blue-900/40 dark:bg-slate-800/60 rounded-2xl border border-blue-800/40 dark:border-slate-700">

                    <div
                        class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-600 to-sky-500 flex items-center justify-center text-white font-bold shadow-md shrink-0">

                        <i class="fas fa-user text-sm"></i>

                    </div>


                    <div class="overflow-hidden">

                        <p
                            class="text-sm font-bold truncate text-white">

                            <?= htmlspecialchars($nama_siswa) ?>

                        </p>

                        <p class="text-[10px] text-sky-400 font-medium">
                            Siswa Aktif
                        </p>

                    </div>

                </div>


                <!-- DARK MODE -->

                <div class="flex items-center gap-2 mb-3">

                    <button
                        onclick="toggleTheme()"
                        class="flex-1 flex items-center justify-center gap-2 p-3 bg-blue-900/30 dark:bg-slate-800 hover:bg-blue-900/60 dark:hover:bg-slate-700 rounded-xl text-xs font-semibold text-blue-200 dark:text-slate-300 transition-all border border-blue-800/40 dark:border-slate-700">

                        <i
                            id="themeIcon"
                            class="fas fa-moon text-sky-400"></i>

                        <span id="themeText">
                            Dark Mode
                        </span>

                    </button>

                </div>


                <!-- LOGOUT SIDEBAR -->

                <a
                    href="../../../logout.php"
                    onclick="return confirm('Yakin ingin keluar?')"
                    class="w-full flex items-center justify-center gap-2 p-3 text-rose-400 hover:bg-rose-500/10 rounded-xl transition-all font-semibold text-xs border border-rose-500/20">

                    <i class="fas fa-sign-out-alt"></i>

                    Keluar

                </a>

            </div>

        </aside>


        <!-- =============================== -->
        <!-- MAIN -->
        <!-- =============================== -->

        <main
            class="flex-1 md:ml-72 p-4 sm:p-6 md:p-10 transition-colors duration-300">


            <!-- =============================== -->
            <!-- MOBILE HEADER -->
            <!-- =============================== -->

            <div
                class="flex md:hidden justify-between items-center mb-6 bg-white dark:bg-slate-900 p-4 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm">


                <div class="flex items-center gap-3">


                    <!-- MENU -->

                    <button
                        onclick="toggleSidebar()"
                        class="p-2.5 bg-blue-600 text-white rounded-xl shadow-md">

                        <i class="fas fa-bars"></i>

                    </button>


                    <div class="bg-blue-600 p-2 rounded-xl text-white">

                        <i class="fas fa-bullhorn"></i>

                    </div>


                    <h1 class="font-bold text-slate-800 dark:text-white">
                        Aspirasi Care
                    </h1>

                </div>


                <div class="flex items-center gap-2">


                    <!-- DARK MODE -->

                    <button
                        onclick="toggleTheme()"
                        class="p-2.5 bg-slate-100 dark:bg-slate-800 rounded-xl text-slate-700 dark:text-slate-300">

                        <i
                            id="themeIconMobile"
                            class="fas fa-moon"></i>

                    </button>


                    <!-- LOGOUT MOBILE -->

                    <a
                        href="../../../logout.php"
                        onclick="return confirm('Yakin ingin keluar?')"
                        title="Keluar"
                        class="p-2.5 bg-rose-50 dark:bg-rose-950/30 text-rose-500 dark:text-rose-400 rounded-xl border border-rose-200 dark:border-rose-900/50 hover:bg-rose-500 hover:text-white transition-all">

                        <i class="fas fa-sign-out-alt"></i>

                    </a>

                </div>

            </div>


            <!-- =============================== -->
            <!-- CONTENT -->
            <!-- =============================== -->

            <div class="max-w-5xl mx-auto">


                <!-- HEADER -->

                <div
                    class="mb-6 sm:mb-8 flex flex-col lg:flex-row lg:items-center justify-between gap-4 sm:gap-6 bg-white dark:bg-slate-900 p-5 sm:p-6 md:p-8 rounded-2xl sm:rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-sm">


                    <div>

                        <h2
                            class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white tracking-tight">

                            Riwayat Aspirasi 📜

                        </h2>


                        <p
                            class="text-slate-500 dark:text-slate-400 mt-1 font-medium text-xs sm:text-sm leading-relaxed">

                            Pantau balasan dan status laporan pengaduan kamu secara real-time.

                        </p>

                    </div>


                    <!-- FILTER STATUS -->

                    <div class="flex flex-wrap items-center gap-2">


                        <a
                            href="?filter="
                            class="px-3 sm:px-4 py-2 sm:py-2.5 rounded-xl text-[11px] sm:text-xs font-bold transition-all
                            <?= $filter == ''
                                ? 'bg-blue-600 text-white shadow-md shadow-blue-500/20'
                                : 'bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700'
                            ?>">

                            Semua

                        </a>


                        <a
                            href="?filter=Menunggu"
                            class="px-3 sm:px-4 py-2 sm:py-2.5 rounded-xl text-[11px] sm:text-xs font-bold transition-all
                            <?= $filter == 'Menunggu'
                                ? 'bg-amber-500 text-white shadow-md shadow-amber-500/20'
                                : 'bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700'
                            ?>">

                            Menunggu

                        </a>


                        <a
                            href="?filter=Diproses"
                            class="px-3 sm:px-4 py-2 sm:py-2.5 rounded-xl text-[11px] sm:text-xs font-bold transition-all
                            <?= $filter == 'Diproses'
                                ? 'bg-sky-500 text-white shadow-md shadow-sky-500/20'
                                : 'bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700'
                            ?>">

                            Diproses

                        </a>


                        <a
                            href="?filter=Selesai"
                            class="px-3 sm:px-4 py-2 sm:py-2.5 rounded-xl text-[11px] sm:text-xs font-bold transition-all
                            <?= $filter == 'Selesai'
                                ? 'bg-emerald-500 text-white shadow-md shadow-emerald-500/20'
                                : 'bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700'
                            ?>">

                            Selesai

                        </a>

                    </div>


                    <!-- TOTAL -->

                    <div
                        class="bg-blue-50 dark:bg-slate-800/80 px-4 sm:px-6 py-3 sm:py-4 rounded-2xl border border-blue-100 dark:border-slate-700 flex items-center gap-3 sm:gap-4 w-full lg:w-auto shrink-0 shadow-inner">


                        <div
                            class="w-10 h-10 sm:w-12 sm:h-12 bg-blue-600 text-white rounded-xl flex items-center justify-center font-black text-xl shadow-md shadow-blue-500/30">

                            <?= $total_laporan ?>

                        </div>


                        <div>

                            <span
                                class="text-slate-400 dark:text-slate-400 text-[10px] font-extrabold uppercase tracking-widest block">

                                Total Laporan

                            </span>

                            <span
                                class="text-slate-700 dark:text-slate-200 font-bold text-xs">

                                Terkirim

                            </span>

                        </div>

                    </div>

                </div>


                <!-- =============================== -->
                <!-- LIST ASPIRASI -->
                <!-- =============================== -->

                <div class="grid gap-4">


                    <?php if ($total_laporan > 0): ?>


                        <?php while ($row = $aspirasi_list->fetch_assoc()):


                            $status = $row['status'];

                            $feedback = !empty($row['feedback'])
                                ? $row['feedback']
                                : 'Belum ada tanggapan dari guru.';


                            $borderColor = "border-amber-400";

                            $iconClass = "fa-clock text-amber-500 bg-amber-50 dark:bg-amber-950/40";

                            $statusBadge = "bg-amber-100 text-amber-700 dark:bg-amber-950/60 dark:text-amber-400 border border-amber-200 dark:border-amber-800/50";


                            if ($status == 'Selesai') {

                                $borderColor = "border-emerald-400";

                                $iconClass = "fa-check-circle text-emerald-500 bg-emerald-50 dark:bg-emerald-950/40";

                                $statusBadge = "bg-emerald-100 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800/50";

                            } elseif ($status == 'Proses' || $status == 'Diproses') {

                                $borderColor = "border-sky-400";

                                $iconClass = "fa-sync-alt fa-spin text-sky-500 bg-sky-50 dark:bg-sky-950/40";

                                $statusBadge = "bg-sky-100 text-sky-700 dark:bg-sky-950/60 dark:text-sky-400 border border-sky-200 dark:border-sky-800/50";

                            }

                        ?>


                            <div
                                class="bg-white dark:bg-slate-900 rounded-2xl sm:rounded-3xl border-l-[5px] <?= $borderColor ?> border border-slate-200/80 dark:border-slate-800 shadow-sm hover:shadow-lg transition-all p-4 sm:p-6 group">


                                <div
                                    class="flex flex-col md:flex-row md:items-center justify-between gap-6">


                                    <!-- INFO -->

                                    <div
                                        class="flex items-start gap-3 sm:gap-5 min-w-0">


                                        <div
                                            class="w-11 h-11 sm:w-14 sm:h-14 rounded-xl sm:rounded-2xl flex items-center justify-center text-2xl <?= $iconClass ?> shrink-0 shadow-inner transition-transform group-hover:scale-110">

                                            <i
                                                class="fas <?= explode(' ', $iconClass)[0] ?>"></i>

                                        </div>


                                        <div class="min-w-0">


                                            <h3
                                                class="font-bold text-slate-800 dark:text-white text-sm sm:text-base md:text-lg break-words group-hover:text-blue-600 dark:group-hover:text-sky-400 transition-colors">

                                                <?= htmlspecialchars($row['judul']) ?>

                                            </h3>


                                            <div
                                                class="flex flex-wrap items-center gap-3 mt-2">


                                                <span
                                                    class="text-[10px] font-extrabold uppercase px-3 py-1 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-700">

                                                    <i class="fas fa-tag mr-1 text-sky-500"></i>

                                                    <?= htmlspecialchars($row['nama_kategori'] ?? 'UMUM') ?>

                                                </span>


                                                <span
                                                    class="text-xs text-slate-400 dark:text-slate-500 font-medium">

                                                    <i class="far fa-calendar-alt mr-1"></i>

                                                    <?= date('d M Y', strtotime($row['tanggal_input'])) ?>

                                                </span>


                                                <!-- STATUS MOBILE -->

                                                <span
                                                    class="md:hidden px-3 py-1 rounded-full text-[10px] font-black <?= $statusBadge ?>">

                                                    <?= strtoupper($status) ?>

                                                </span>

                                            </div>

                                        </div>

                                    </div>


                                    <!-- BUTTON AREA -->

                                    <div
                                        class="flex flex-wrap gap-2 items-center justify-between md:justify-end border-t md:border-t-0 pt-4 md:pt-0 border-slate-100 dark:border-slate-800">


                                        <!-- STATUS DESKTOP -->

                                        <span
                                            class="hidden md:inline-flex items-center px-4 py-2 rounded-full text-[10px] font-black tracking-wider uppercase <?= $statusBadge ?>">

                                            <span
                                                class="w-1.5 h-1.5 rounded-full bg-current mr-2 animate-pulse"></span>

                                            <?= strtoupper($status) ?>

                                        </span>


                                        <!-- BUTTON -->

                                        <div
                                            class="flex flex-wrap gap-2 items-center w-full md:w-auto">


                                            <!-- LIHAT DETAIL -->

                                            <button
                                                type="button"
                                                onclick='openModal(
                                                    <?= json_encode($row["judul"]) ?>,
                                                    <?= json_encode($row["isi_aspirasi"]) ?>,
                                                    <?= json_encode($feedback) ?>,
                                                    <?= json_encode($status) ?>,
                                                    <?= json_encode($row["id_aspirasi"]) ?>,
                                                    <?= json_encode($row["rating"] ?? "") ?>,
                                                    <?= json_encode($row["foto"] ?? "") ?>
                                                )'
                                                class="shrink-0 flex-1 sm:flex-none bg-gradient-to-r from-blue-600 to-sky-500 hover:from-blue-700 hover:to-sky-600 text-white px-4 sm:px-6 py-3 rounded-xl text-[11px] sm:text-xs font-bold shadow-md shadow-blue-500/20 active:scale-95 transition-all">

                                                Lihat Detail

                                            </button>


                                            <?php if ($status == 'Menunggu'): ?>


                                                <!-- EDIT -->

                                                <button
                                                    type="button"
                                                    onclick='openEditModal(
                                                        <?= json_encode($row["id_aspirasi"]) ?>,
                                                        <?= json_encode($row["judul"]) ?>,
                                                        <?= json_encode($row["id_kategori"]) ?>,
                                                        <?= json_encode($row["isi_aspirasi"]) ?>,
                                                        <?= json_encode($row["foto"] ?? "") ?>
                                                    )'
                                                    class="shrink-0 flex-1 sm:flex-none px-4 sm:px-5 py-3 rounded-xl bg-amber-500 hover:bg-amber-600 text-white text-[11px] sm:text-xs font-bold transition-all">

                                                    <i class="fas fa-edit mr-1"></i>

                                                    Edit

                                                </button>


                                                <!-- HAPUS -->

                                                <a
                                                    href="../../controllers/C_Aspirasi.php?aksi=hapus_aspirasi&id=<?= $row['id_aspirasi'] ?>"
                                                    onclick="return confirm('Yakin ingin menghapus laporan ini?')"
                                                    class="shrink-0 flex-1 sm:flex-none px-4 sm:px-5 py-3 rounded-xl bg-rose-500 hover:bg-rose-600 text-white text-[11px] sm:text-xs font-bold transition-all">

                                                    <i class="fas fa-trash mr-1"></i>

                                                    Hapus

                                                </a>


                                            <?php endif; ?>


                                        </div>

                                    </div>

                                </div>

                            </div>


                        <?php endwhile; ?>


                    <?php else: ?>


                        <!-- EMPTY -->

                        <div
                            class="text-center py-24 bg-white dark:bg-slate-900 rounded-[3rem] border-2 border-dashed border-slate-200 dark:border-slate-800">


                            <div
                                class="w-20 h-20 bg-slate-50 dark:bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-4 border border-slate-100 dark:border-slate-700">

                                <i
                                    class="fas fa-folder-open text-3xl text-slate-300 dark:text-slate-600"></i>

                            </div>


                            <p
                                class="text-slate-600 dark:text-slate-300 font-bold text-base">

                                Kamu belum pernah mengirim aspirasi.

                            </p>


                            <p
                                class="text-slate-400 dark:text-slate-500 text-xs mt-1">

                                Mulai buat pengaduan atau saran fasilitas sekolah dengan mudah.

                            </p>


                            <a
                                href="form.php"
                                class="mt-4 bg-blue-600 text-white px-6 py-2.5 rounded-xl text-xs font-bold inline-block hover:bg-blue-700 shadow-md shadow-blue-500/20 transition-all">

                                Kirim Sekarang →

                            </a>

                        </div>


                    <?php endif; ?>


                    <!-- =============================== -->
                    <!-- PAGINATION -->
                    <!-- =============================== -->

                    <?php if ($total_halaman > 1): ?>


                        <div
                            class="flex flex-col sm:flex-row items-center justify-between gap-4 mt-6">


                            <p
                                class="text-xs font-medium text-slate-400 dark:text-slate-500">

                                Halaman

                                <span
                                    class="font-bold text-slate-700 dark:text-slate-200">

                                    <?= $halaman ?>

                                </span>

                                dari

                                <span
                                    class="font-bold text-slate-700 dark:text-slate-200">

                                    <?= $total_halaman ?>

                                </span>

                            </p>


                            <div class="flex items-center gap-2">


                                <?php if ($halaman > 1): ?>

                                    <a
                                        href="?filter=<?= urlencode($filter) ?>&halaman=<?= $halaman - 1 ?>"
                                        class="w-10 h-10 flex items-center justify-center rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-600 dark:text-slate-300 hover:bg-blue-600 hover:text-white hover:border-blue-600 transition-all shadow-sm">

                                        <i class="fas fa-chevron-left text-xs"></i>

                                    </a>

                                <?php endif; ?>


                                <?php for ($i = 1; $i <= $total_halaman; $i++): ?>

                                    <a
                                        href="?filter=<?= urlencode($filter) ?>&halaman=<?= $i ?>"
                                        class="w-10 h-10 flex items-center justify-center rounded-xl text-xs font-bold transition-all
                                        <?= $i == $halaman
                                            ? 'bg-blue-600 text-white shadow-md shadow-blue-500/30'
                                            : 'bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-600 dark:text-slate-300 hover:bg-blue-50 dark:hover:bg-slate-800'
                                        ?>">

                                        <?= $i ?>

                                    </a>

                                <?php endfor; ?>


                                <?php if ($halaman < $total_halaman): ?>

                                    <a
                                        href="?filter=<?= urlencode($filter) ?>&halaman=<?= $halaman + 1 ?>"
                                        class="w-10 h-10 flex items-center justify-center rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-600 dark:text-slate-300 hover:bg-blue-600 hover:text-white hover:border-blue-600 transition-all shadow-sm">

                                        <i class="fas fa-chevron-right text-xs"></i>

                                    </a>

                                <?php endif; ?>


                            </div>

                        </div>


                    <?php endif; ?>


                </div>

            </div>

        </main>

    </div>


    <!-- =============================== -->
    <!-- MODAL DETAIL -->
    <!-- =============================== -->

    <div
        id="modalDetail"
        class="fixed inset-0 z-[100] hidden flex items-center justify-center p-3 sm:p-4 bg-slate-900/80 backdrop-blur-md">


        <div
            class="bg-white dark:bg-slate-900 w-full max-w-lg max-h-[92vh] rounded-2xl sm:rounded-[2.5rem] shadow-2xl overflow-hidden animate-modal border border-slate-200 dark:border-slate-800">


            <!-- HEADER -->

            <div
                class="bg-gradient-to-r from-blue-950 to-blue-900 dark:from-slate-950 dark:to-slate-900 p-8 text-white relative">


                <div class="flex items-center gap-4">


                    <div
                        class="w-12 h-12 bg-white/10 rounded-2xl flex items-center justify-center border border-white/10">

                        <i class="fas fa-file-alt text-xl text-sky-400"></i>

                    </div>


                    <div>

                        <h3 class="text-xl font-black tracking-tight">
                            Detail Pengaduan
                        </h3>

                        <p class="text-xs text-sky-300 opacity-80">
                            Riwayat Tanggapan & Status
                        </p>

                    </div>

                </div>


                <button
                    onclick="closeModal()"
                    class="absolute top-8 right-8 text-white/50 hover:text-white transition-colors">

                    <i class="fas fa-times text-2xl"></i>

                </button>

            </div>


            <!-- BODY -->

            <div
                class="p-5 sm:p-8 space-y-5 sm:space-y-6 max-h-[75vh] overflow-y-auto">


                <!-- ISI -->

                <div class="space-y-2">


                    <label
                        class="text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest ml-1">

                        Isi Aspirasi Anda

                    </label>


                    <div
                        class="p-5 bg-slate-50 dark:bg-slate-800/60 rounded-2xl border border-slate-100 dark:border-slate-700/80">


                        <h4
                            id="m_judul"
                            class="font-black text-slate-800 dark:text-white text-base mb-2 leading-tight">
                        </h4>


                        <p
                            id="m_isi"
                            class="text-sm text-slate-600 dark:text-slate-300 leading-relaxed font-medium">
                        </p>


                    </div>

                </div>


                <!-- FOTO -->

                <div
                    id="m_foto_container"
                    class="space-y-2 hidden">


                    <label
                        class="text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest ml-1">

                        Foto Bukti

                    </label>


                    <div
                        class="bg-slate-50 dark:bg-slate-800/60 rounded-2xl border border-slate-100 dark:border-slate-700/80 p-3">


                        <img
                            id="m_foto"
                            src=""
                            alt="Foto bukti aspirasi"
                            class="w-full max-h-[350px] object-cover rounded-xl">

                    </div>

                </div>


                <!-- FEEDBACK -->

                <div class="space-y-2">


                    <label
                        class="text-[10px] font-extrabold text-blue-600 dark:text-sky-400 uppercase tracking-widest ml-1">

                        Tanggapan Guru / Admin

                    </label>


                    <div
                        id="m_box_feedback"
                        class="p-6 rounded-2xl border-2 transition-colors duration-300">


                        <div class="flex items-center gap-2 mb-3">

                            <span
                                id="m_status"
                                class="text-[10px] font-black px-3 py-1 rounded-lg">
                            </span>

                        </div>


                        <p
                            id="m_feedback"
                            class="text-sm font-bold text-slate-700 dark:text-slate-200 italic leading-relaxed">
                        </p>

                    </div>


                    <!-- RATING -->

                    <form
                        id="ratingForm"
                        method="POST"
                        action="../../controllers/C_Aspirasi.php?aksi=simpan_rating"
                        class="mt-6 pt-6 border-t border-slate-100 dark:border-slate-800">


                        <input
                            type="hidden"
                            name="id_aspirasi"
                            id="ratingIdAspirasi">


                        <input
                            type="hidden"
                            name="rating"
                            id="ratingInput">


                        <div class="text-center">


                            <label
                                class="block text-[10px] font-extrabold text-amber-500 uppercase tracking-widest mb-3">

                                Beri Rating Laporan

                            </label>


                            <div
                                id="starRating"
                                class="flex justify-center gap-1.5">


                                <?php for ($i = 1; $i <= 5; $i++): ?>

                                    <button
                                        type="button"
                                        class="star-btn text-3xl text-slate-300 dark:text-slate-700 hover:scale-110 transition-transform"
                                        data-value="<?= $i ?>">

                                        <i class="fas fa-star"></i>

                                    </button>

                                <?php endfor; ?>


                            </div>


                            <p
                                class="text-[11px] text-slate-400 dark:text-slate-500 mt-2">

                                Klik bagian kiri/kanan bintang untuk rating setengah (0.5)

                            </p>

                        </div>


                        <button
                            type="submit"
                            class="w-full mt-4 py-3.5 bg-gradient-to-r from-amber-500 to-amber-600 text-white rounded-xl font-bold text-xs uppercase tracking-wider shadow-md shadow-amber-500/20 hover:from-amber-600 hover:to-amber-700 active:scale-95 transition-all">

                            Simpan Rating

                        </button>


                    </form>

                </div>


                <!-- CLOSE -->

                <button
                    onclick="closeModal()"
                    class="w-full py-4 bg-slate-900 dark:bg-slate-800 text-white rounded-xl font-bold text-xs shadow-lg hover:bg-slate-800 dark:hover:bg-slate-700 active:scale-95 transition-all tracking-widest uppercase border border-slate-800 dark:border-slate-700">

                    Tutup Detail

                </button>


            </div>

        </div>

    </div>


    <!-- =============================== -->
    <!-- MODAL EDIT -->
    <!-- =============================== -->

    <div
        id="modalEdit"
        class="fixed inset-0 z-[110] hidden flex items-center justify-center p-4 bg-slate-900/80 backdrop-blur-md">


        <div
            class="bg-white dark:bg-slate-900 w-full max-w-2xl max-h-[90vh] rounded-[2.5rem] shadow-2xl overflow-hidden border border-slate-200 dark:border-slate-800">


            <!-- HEADER -->

            <div
                class="bg-gradient-to-r from-amber-500 to-orange-500 p-8 text-white relative">


                <div class="flex items-center gap-4">


                    <div
                        class="w-12 h-12 bg-white/10 rounded-2xl flex items-center justify-center border border-white/10">

                        <i class="fas fa-edit text-xl"></i>

                    </div>


                    <div>

                        <h3 class="text-xl font-black tracking-tight">
                            Edit Pengaduan
                        </h3>

                        <p class="text-xs text-amber-100">
                            Ubah laporan aspirasi kamu
                        </p>

                    </div>

                </div>


                <button
                    type="button"
                    onclick="closeEditModal()"
                    class="absolute top-8 right-8 text-white/70 hover:text-white transition-colors">

                    <i class="fas fa-times text-2xl"></i>

                </button>

            </div>


            <!-- FORM -->

            <form
                action="../../controllers/C_Aspirasi.php?aksi=update_aspirasi"
                method="POST"
                enctype="multipart/form-data">


                <input
                    type="hidden"
                    name="id_aspirasi"
                    id="edit_id_aspirasi">


                <div
                    class="p-8 space-y-6 max-h-[70vh] overflow-y-auto">


                    <!-- JUDUL -->

                    <div>

                        <label
                            class="block text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-2 ml-1">

                            Judul / Topik Laporan

                        </label>


                        <input
                            type="text"
                            id="edit_judul"
                            name="topik"
                            required
                            class="w-full px-5 py-4 bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 rounded-2xl focus:ring-4 focus:ring-amber-500/10 focus:border-amber-500 outline-none transition-all text-slate-800 dark:text-white text-sm font-medium">

                    </div>


                    <!-- KATEGORI -->

                    <div>

                        <label
                            class="block text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-2 ml-1">

                            Kategori

                        </label>


                        <select
                            id="edit_kategori"
                            name="kategori"
                            required
                            class="w-full px-5 py-4 bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 rounded-2xl focus:ring-4 focus:ring-amber-500/10 focus:border-amber-500 outline-none transition-all cursor-pointer text-slate-800 dark:text-white text-sm font-medium">


                            <option
                                value=""
                                class="dark:bg-slate-900">

                                Pilih Kategori

                            </option>


                            <option
                                value="1"
                                class="dark:bg-slate-900">

                                Kerusakan

                            </option>


                            <option
                                value="2"
                                class="dark:bg-slate-900">

                                Fasilitas

                            </option>


                            <option
                                value="3"
                                class="dark:bg-slate-900">

                                Kebersihan

                            </option>


                            <option
                                value="4"
                                class="dark:bg-slate-900">

                                Lainnya

                            </option>


                        </select>

                    </div>


                    <!-- ISI -->

                    <div>

                        <label
                            class="block text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-2 ml-1">

                            Isi Laporan

                        </label>


                        <textarea
                            id="edit_isi"
                            name="isi_laporan"
                            rows="6"
                            required
                            class="w-full p-5 bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 rounded-2xl focus:ring-4 focus:ring-amber-500/10 focus:border-amber-500 outline-none transition-all text-slate-800 dark:text-white text-sm font-medium resize-none"></textarea>

                    </div>


                    <!-- FOTO -->

                    <div>

                        <label
                            class="block text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-2 ml-1">

                            Foto Bukti

                        </label>


                        <!-- FOTO LAMA -->

                        <div
                            id="edit_foto_lama"
                            class="hidden mb-3 p-3 bg-slate-50 dark:bg-slate-800/60 rounded-2xl border border-slate-200 dark:border-slate-700">


                            <p
                                class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-2">

                                Foto Saat Ini

                            </p>


                            <img
                                id="edit_foto_preview"
                                src=""
                                alt="Foto bukti"
                                class="w-full max-h-[250px] object-cover rounded-xl">

                        </div>


                        <input
                            type="file"
                            name="foto"
                            accept="image/jpeg,image/png,image/jpg"
                            class="w-full p-3 border border-slate-200 dark:border-slate-700 rounded-xl bg-slate-50 dark:bg-slate-800 text-sm text-slate-600 dark:text-slate-300">


                        <p class="text-xs text-slate-400 mt-2">

                            Kosongkan jika tidak ingin mengganti foto.

                        </p>

                    </div>


                    <!-- BUTTON -->

                    <div class="flex gap-3 pt-2">


                        <button
                            type="button"
                            onclick="closeEditModal()"
                            class="flex-1 py-4 bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-slate-200 rounded-xl font-bold text-xs hover:bg-slate-300 dark:hover:bg-slate-700 transition-all">

                            Batal

                        </button>


                        <button
                            type="submit"
                            class="flex-1 py-4 bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-white rounded-xl font-bold text-xs shadow-md shadow-amber-500/20 active:scale-95 transition-all">

                            <i class="fas fa-save mr-1"></i>

                            Simpan Perubahan

                        </button>

                    </div>


                </div>

            </form>

        </div>

    </div>


    <!-- =============================== -->
    <!-- JAVASCRIPT -->
    <!-- =============================== -->

    <script>


        // ===============================
        // SIDEBAR MOBILE
        // ===============================

        function toggleSidebar() {

            const sidebar = document.getElementById('sidebar');

            if (!sidebar) return;

            sidebar.classList.toggle('-translate-x-full');

        }


        // ===============================
        // DARK MODE
        // ===============================

        function toggleTheme() {

            const html = document.documentElement;

            const themeIcon = document.getElementById('themeIcon');

            const themeIconMobile = document.getElementById('themeIconMobile');

            const themeText = document.getElementById('themeText');


            if (html.classList.contains('dark')) {

                html.classList.remove('dark');

                localStorage.theme = 'light';


                if (themeText) {
                    themeText.innerText = 'Dark Mode';
                }


                if (themeIcon) {
                    themeIcon.className = 'fas fa-moon text-sky-400';
                }


                if (themeIconMobile) {
                    themeIconMobile.className = 'fas fa-moon';
                }


            } else {

                html.classList.add('dark');

                localStorage.theme = 'dark';


                if (themeText) {
                    themeText.innerText = 'Light Mode';
                }


                if (themeIcon) {
                    themeIcon.className = 'fas fa-sun text-amber-400';
                }


                if (themeIconMobile) {
                    themeIconMobile.className = 'fas fa-sun text-amber-400';
                }

            }

        }


        // ===============================
        // LOAD DARK MODE
        // ===============================

        if (
            localStorage.theme === 'dark' ||
            (
                !('theme' in localStorage) &&
                window.matchMedia('(prefers-color-scheme: dark)').matches
            )
        ) {

            document.documentElement.classList.add('dark');


            const themeText = document.getElementById('themeText');

            const themeIcon = document.getElementById('themeIcon');

            const themeIconMobile = document.getElementById('themeIconMobile');


            if (themeText) {
                themeText.innerText = 'Light Mode';
            }


            if (themeIcon) {
                themeIcon.className = 'fas fa-sun text-amber-400';
            }


            if (themeIconMobile) {
                themeIconMobile.className = 'fas fa-sun text-amber-400';
            }

        }


        // ===============================
        // MODAL DETAIL
        // ===============================

        function openModal(
            judul,
            isi,
            feedback,
            status,
            idAspirasi,
            rating,
            foto
        ) {


            document.getElementById('ratingIdAspirasi').value = idAspirasi;


            currentRating = rating
                ? parseFloat(rating)
                : 0;


            document.getElementById('ratingInput').value = currentRating;

            tampilkanBintang(currentRating);


            document.getElementById('m_judul').innerText = judul;

            document.getElementById('m_isi').innerText = isi;

            document.getElementById('m_feedback').innerText = feedback;


            // FOTO

            const fotoContainer =
                document.getElementById('m_foto_container');

            const fotoElement =
                document.getElementById('m_foto');


            if (foto) {

                fotoElement.src =
                    '../assets/uploads/' + foto;

                fotoContainer.classList.remove('hidden');

            } else {

                fotoElement.src = '';

                fotoContainer.classList.add('hidden');

            }


            // STATUS

            const statusEl =
                document.getElementById('m_status');

            const boxEl =
                document.getElementById('m_box_feedback');


            statusEl.innerText =
                status.toUpperCase();


            if (status === 'Selesai') {

                boxEl.className =
                    "p-6 rounded-2xl border-emerald-200 dark:border-emerald-800/60 bg-emerald-50/50 dark:bg-emerald-950/20 border-2";


                statusEl.className =
                    "text-[10px] font-black px-3 py-1 rounded-lg bg-emerald-600 text-white";


            } else if (
                status === 'Diproses' ||
                status === 'Proses'
            ) {

                boxEl.className =
                    "p-6 rounded-2xl border-sky-200 dark:border-sky-800/60 bg-sky-50/50 dark:bg-sky-950/20 border-2";


                statusEl.className =
                    "text-[10px] font-black px-3 py-1 rounded-lg bg-sky-600 text-white";


            } else {

                boxEl.className =
                    "p-6 rounded-2xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/40 border-2";


                statusEl.className =
                    "text-[10px] font-black px-3 py-1 rounded-lg bg-slate-400 text-white";

            }


            document
                .getElementById('modalDetail')
                .classList
                .remove('hidden');


            document.body.style.overflow = 'hidden';

        }


        // ===============================
        // EDIT MODAL
        // ===============================

        function openEditModal(
            id,
            judul,
            kategori,
            isi,
            foto
        ) {


            document.getElementById(
                'edit_id_aspirasi'
            ).value = id;


            document.getElementById(
                'edit_judul'
            ).value = judul;


            document.getElementById(
                'edit_kategori'
            ).value = kategori;


            document.getElementById(
                'edit_isi'
            ).value = isi;


            // FOTO LAMA

            const fotoContainer =
                document.getElementById('edit_foto_lama');


            const fotoPreview =
                document.getElementById('edit_foto_preview');


            if (foto) {

                fotoPreview.src =
                    '../assets/uploads/' + foto;

                fotoContainer.classList.remove('hidden');

            } else {

                fotoPreview.src = '';

                fotoContainer.classList.add('hidden');

            }


            document
                .getElementById('modalEdit')
                .classList
                .remove('hidden');


            document.body.style.overflow = 'hidden';

        }


        // ===============================
        // CLOSE EDIT
        // ===============================

        function closeEditModal() {

            document
                .getElementById('modalEdit')
                .classList
                .add('hidden');


            document.body.style.overflow = 'auto';

        }


        // ===============================
        // CLOSE DETAIL
        // ===============================

        function closeModal() {

            document
                .getElementById('modalDetail')
                .classList
                .add('hidden');


            document.body.style.overflow = 'auto';

        }


        // ===============================
        // RATING
        // ===============================

        let currentRating = 0;


        const starButtons =
            document.querySelectorAll('.star-btn');


        const ratingInput =
            document.getElementById('ratingInput');


        starButtons.forEach(button => {


            button.addEventListener(
                'mousemove',
                function(event) {


                    const rect =
                        this.getBoundingClientRect();


                    const mouseX =
                        event.clientX - rect.left;


                    const value =
                        parseInt(this.dataset.value);


                    let hoverRating =
                        mouseX < rect.width / 2
                            ? value - 0.5
                            : value;


                    tampilkanBintang(hoverRating);

                }
            );


            button.addEventListener(
                'click',
                function(event) {


                    const rect =
                        this.getBoundingClientRect();


                    const mouseX =
                        event.clientX - rect.left;


                    const value =
                        parseInt(this.dataset.value);


                    currentRating =
                        mouseX < rect.width / 2
                            ? value - 0.5
                            : value;


                    ratingInput.value =
                        currentRating;


                    tampilkanBintang(
                        currentRating
                    );

                }
            );

        });


        function tampilkanBintang(rating) {


            starButtons.forEach(button => {


                const value =
                    parseInt(button.dataset.value);


                const icon =
                    button.querySelector('i');


                if (rating >= value) {


                    icon.className =
                        "fas fa-star";


                    button.classList.remove(
                        'text-slate-300',
                        'dark:text-slate-700'
                    );


                    button.classList.add(
                        'text-amber-400'
                    );


                } else if (
                    rating === value - 0.5
                ) {


                    icon.className =
                        "fas fa-star-half-stroke";


                    button.classList.remove(
                        'text-slate-300',
                        'dark:text-slate-700'
                    );


                    button.classList.add(
                        'text-amber-400'
                    );


                } else {


                    icon.className =
                        "fas fa-star";


                    button.classList.remove(
                        'text-amber-400'
                    );


                    button.classList.add(
                        'text-slate-300',
                        'dark:text-slate-700'
                    );

                }

            });

        }


        // ===============================
        // CLICK OUTSIDE MODAL
        // ===============================

        window.onclick = function(event) {


            const modal =
                document.getElementById('modalDetail');


            if (event.target == modal) {

                closeModal();

            }

        }


        // ===============================
        // RATING NOTIFICATION
        // ===============================

        const ratingNotification =
            document.getElementById(
                'ratingNotification'
            );


        const ratingNotificationBox =
            document.getElementById(
                'ratingNotificationBox'
            );


        if (
            ratingNotification &&
            ratingNotificationBox
        ) {


            setTimeout(() => {


                ratingNotificationBox.classList.remove(
                    'scale-90',
                    'opacity-0'
                );


                ratingNotificationBox.classList.add(
                    'scale-100',
                    'opacity-100'
                );


            }, 100);


            setTimeout(() => {


                ratingNotificationBox.classList.remove(
                    'scale-100',
                    'opacity-100'
                );


                ratingNotificationBox.classList.add(
                    'scale-90',
                    'opacity-0'
                );


                setTimeout(() => {


                    ratingNotification.remove();


                    const url =
                        new URL(window.location.href);


                    url.searchParams.delete(
                        'rating'
                    );


                    window.history.replaceState(
                        {},
                        document.title,
                        url
                    );


                }, 500);


            }, 2200);

        }

    </script>

</body>

</html>