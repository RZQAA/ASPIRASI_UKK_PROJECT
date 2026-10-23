<?php

// Mengecek apakah session sudah aktif atau belum
// Kalau belum, maka session akan dimulai
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$nama_siswa = $_SESSION['nama'] ?? $_SESSION['siswa']['nama_siswa'] ?? 'Siswa';

?>

<!DOCTYPE html>
<html lang="id" class="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sampaikan Aspirasi - E-Lapor</title>

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

            <!-- LOGO -->
            <div class="flex items-center gap-4 mb-12 mt-2">
                <div class="bg-gradient-to-br from-blue-500 to-sky-400 p-2.5 rounded-2xl text-white shadow-lg shadow-blue-500/30">
                    <i class="fas fa-bullhorn text-2xl"></i>
                </div>

                <div>
                    <h1 class="text-xl font-bold tracking-tight text-white">Aspirasi Care</h1>
                    <p class="text-[10px] text-sky-400 font-semibold tracking-wider uppercase">Portal Siswa</p>
                </div>
            </div>

            <!-- NAVIGATION -->
            <nav class="space-y-2 flex-1">
                <p class="text-[10px] font-bold uppercase tracking-wider text-blue-400/70 dark:text-slate-500 mb-3 px-3">
                    Menu Siswa
                </p>

                <a href="dashboard.php"
                    onclick="closeSidebar()"
                    class="flex items-center gap-3.5 p-3.5 rounded-xl text-blue-200/80 dark:text-slate-400 hover:bg-blue-900/50 dark:hover:bg-slate-800 hover:text-white transition-all font-medium">
                    <i class="fas fa-chart-line w-5 text-sky-400"></i> Dashboard
                </a>

                <!-- MENU AKTIF -->
                <a href="form.php"
                    onclick="closeSidebar()"
                    class="flex items-center gap-3.5 p-3.5 rounded-xl bg-gradient-to-r from-blue-600 to-sky-500 text-white shadow-lg shadow-blue-900/40 font-semibold">
                    <i class="fas fa-edit w-5 text-white"></i> Sampaikan Aspirasi
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

            <!-- PROFILE -->
            <div class="mt-auto pt-6 border-t border-blue-900/80 dark:border-slate-800">

                <div class="flex items-center gap-3 p-3 mb-4 bg-blue-900/40 dark:bg-slate-800/60 rounded-2xl border border-blue-800/40 dark:border-slate-700">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-600 to-sky-500 flex items-center justify-center text-white font-bold shadow-md shrink-0">
                        <i class="fas fa-user text-sm"></i>
                    </div>

                    <div class="overflow-hidden">
                        <p class="text-sm font-bold truncate text-white">
                            <?= htmlspecialchars($nama_siswa); ?>
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
                    <i class="fas fa-sign-out-alt"></i> Keluar
                </a>
            </div>
        </aside>

        <!-- MAIN CONTENT -->
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

                    <h1 class="font-bold text-slate-800 dark:text-white">
                        Aspirasi Care
                    </h1>
                </div>

                <button onclick="toggleTheme()"
                    class="w-10 h-10 flex items-center justify-center bg-slate-100 dark:bg-slate-800 rounded-xl text-slate-700 dark:text-slate-300">
                    <i id="themeIconMobile" class="fas fa-moon"></i>
                </button>
            </div>

            <div class="max-w-3xl mx-auto">

                <!-- TOP NAVBAR / BACK BUTTON -->
                <div class="flex justify-between items-center mb-6 sm:mb-8 bg-white dark:bg-slate-900 p-4 sm:p-6 rounded-2xl sm:rounded-3xl shadow-sm border border-slate-200/80 dark:border-slate-800 transition-colors">

                    <a href="dashboard_siswa.php"
                        class="text-blue-600 dark:text-sky-400 font-bold text-xs uppercase tracking-wider flex items-center gap-2 hover:translate-x-[-4px] transition-transform">
                        <i class="fas fa-arrow-left"></i>
                        <span>Kembali ke Dashboard</span>
                    </a>

                </div>

                <!-- NOTIFIKASI SUCCESS / ERROR -->
                <?php if (isset($_SESSION['message'])): ?>

                    <div class="mb-6 p-4 rounded-2xl <?= $_SESSION['msg_type'] == 'success'
                                                            ? 'bg-emerald-50 dark:bg-emerald-950/50 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800'
                                                            : 'bg-rose-50 dark:bg-rose-950/50 text-rose-700 dark:text-rose-300 border border-rose-200 dark:border-rose-800'; ?> flex items-center gap-3 animate-pulse shadow-sm">

                        <i class="fas <?= $_SESSION['msg_type'] == 'success'
                                            ? 'fa-check-circle'
                                            : 'fa-exclamation-circle'; ?>"></i>

                        <p class="text-sm font-bold">
                            <?php echo htmlspecialchars($_SESSION['message']); ?>
                        </p>

                        <?php
                        unset($_SESSION['message']);
                        unset($_SESSION['msg_type']);
                        ?>

                    </div>

                <?php endif; ?>

                <!-- HEADER FORM -->
                <div class="mb-6 sm:mb-8 bg-white dark:bg-slate-900 p-5 sm:p-8 rounded-2xl sm:rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-sm transition-colors">

                    <h2 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white tracking-tight mb-1">
                        Sampaikan Aspirasimu ✍️
                    </h2>

                    <p class="text-slate-500 dark:text-slate-400 text-xs sm:text-sm font-medium leading-relaxed">
                        Suaramu membantu kami memperbaiki fasilitas dan layanan sekolah.
                    </p>

                </div>

                <!-- FORM CARD -->
                <div class="bg-white dark:bg-slate-900 rounded-2xl sm:rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-sm hover:shadow-lg transition-all overflow-hidden">

                    <form action="../../controllers/C_Aspirasi.php?aksi=tambah_aspirasi"
                        method="POST"
                        enctype="multipart/form-data"
                        class="p-5 sm:p-8 space-y-6">

                        <!-- INPUT TOPIK -->
                        <div>
                            <label for="topik"
                                class="block text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-2 ml-1">
                                Judul / Topik Laporan
                            </label>

                            <input type="text"
                                id="topik"
                                name="topik"
                                required
                                placeholder="Contoh: Lampu kelas XII rusak"
                                class="w-full px-4 sm:px-5 py-3.5 sm:py-4 bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all text-slate-800 dark:text-white text-sm font-medium">
                        </div>

                        <!-- PILIHAN KATEGORI -->
                        <div>
                            <label for="kategori"
                                class="block text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-2 ml-1">
                                Kategori
                            </label>

                            <select id="kategori"
                                name="kategori"
                                required
                                class="w-full px-4 sm:px-5 py-3.5 sm:py-4 bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all cursor-pointer text-slate-800 dark:text-white text-sm font-medium">

                                <option value="" class="dark:bg-slate-900">
                                    Pilih Kategori
                                </option>

                                <option value="1" class="dark:bg-slate-900">
                                    Kerusakan
                                </option>

                                <option value="2" class="dark:bg-slate-900">
                                    Fasilitas
                                </option>

                                <option value="3" class="dark:bg-slate-900">
                                    Kebersihan
                                </option>

                                <option value="4" class="dark:bg-slate-900">
                                    Lainnya
                                </option>

                            </select>
                        </div>

                        <!-- ISI ASPIRASI -->
                        <div>
                            <label for="isi"
                                class="block text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-2 ml-1">
                                Isi Laporan
                            </label>

                            <textarea id="isi"
                                name="isi_laporan"
                                rows="6"
                                required
                                placeholder="Jelaskan secara rinci..."
                                class="w-full p-4 sm:p-5 bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all text-slate-800 dark:text-white text-sm font-medium resize-none"></textarea>
                        </div>

                        <!-- KIRIM FOTO -->
                        <div>

                            <div>
                                <label class="block text-sm font-semibold mb-2">
                                    Foto Bukti
                                </label>

                                <div class="flex gap-3">

                                    <!-- INPUT UPLOAD -->
                                    <input type="file"
                                        id="foto"
                                        name="foto"
                                        accept="image/jpeg,image/png,image/jpg"
                                        class="w-full p-3 border rounded-xl">


                                    <!-- BUKA KAMERA -->
                                    <button type="button"
                                        onclick="bukaKamera()"
                                        class="px-4 bg-blue-600 text-white rounded-xl">
                                        <i class="fas fa-camera"></i>
                                    </button>


                                    <!-- GANTI KAMERA -->
                                    <button type="button"
                                        onclick="gantiKamera()"
                                        class="px-4 bg-gray-700 text-white rounded-xl">
                                        <i class="fas fa-sync"></i>
                                    </button>

                                </div>
                                <div id="kameraBox" class="hidden mt-4">
                                    <video id="video"
                                        autoplay
                                        class="w-full rounded-xl border">
                                    </video>
                                    <button type="button"
                                        onclick="ambilFoto()"
                                        class="mt-3 bg-emerald-600 text-white px-4 py-2 rounded-xl">
                                        Ambil Foto
                                    </button>
                                </div>
                                <canvas id="canvas" class="hidden"></canvas>
                                <img id="preview"
                                    class="hidden mt-3 rounded-xl w-full">
                            </div>

                            <p class="text-xs text-zinc-400 mt-1">
                                Format JPG, JPEG, atau PNG. Maksimal 2 MB.
                            </p>
                        </div>

                        <!-- TOMBOL SUBMIT -->
                        <div class="pt-2 sm:pt-4">

                            <button type="submit"
                                name="submit_aspirasi"
                                class="w-full bg-gradient-to-r from-blue-600 to-sky-500 hover:from-blue-700 hover:to-sky-600 text-white font-bold py-3.5 sm:py-4 rounded-2xl shadow-lg shadow-blue-500/25 transition-all duration-300 hover:-translate-y-0.5 flex items-center justify-center gap-2 group active:scale-[0.98] uppercase text-xs tracking-wider">

                                <span>Kirim Laporan Sekarang</span>

                                <i class="fas fa-paper-plane group-hover:translate-x-1 transition-transform"></i>

                            </button>

                        </div>

                    </form>
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
            (!('theme' in localStorage) &&
                window.matchMedia('(prefers-color-scheme: dark)').matches)
        ) {
            document.documentElement.classList.add('dark');

            const themeText = document.getElementById('themeText');
            const themeIcon = document.getElementById('themeIcon');
            const themeIconMobile = document.getElementById('themeIconMobile');

            if (themeText) themeText.innerText = 'Light Mode';
            if (themeIcon) themeIcon.className = 'fas fa-sun text-amber-400';
            if (themeIconMobile) themeIconMobile.className = 'fas fa-sun text-amber-400';
        }

        // Fungsi preview gambar
        function previewImage(event) {
            const reader = new FileReader();
            const previewText = document.getElementById('preview-text');
            const previewImg = document.getElementById('image-preview');

            reader.onload = function() {
                if (reader.readyState === 2) {
                    previewImg.src = reader.result;
                    previewImg.classList.remove('hidden');

                    if (previewText) {
                        previewText.classList.add('hidden');
                    }
                }
            }

            if (event.target.files[0]) {
                reader.readAsDataURL(event.target.files[0]);
            }
        }

        let stream;
        let kameraBelakang = true;

        // BUKA KAMERA
        async function bukaKamera() {
            document.getElementById('kameraBox')
                .classList.remove('hidden');
            try {
                if (stream) {
                    stream.getTracks().forEach(track => track.stop());
                }
                stream = await navigator.mediaDevices.getUserMedia({
                    video: {
                        facingMode: kameraBelakang ?
                            "environment" : "user"
                    }
                });
                document.getElementById('video').srcObject = stream;
            } catch (error) {
                console.log(error);
                alert(
                    "Kamera gagal dibuka. Pastikan izin kamera sudah aktif."
                );
            }
        }

        // GANTI KAMERA DEPAN BELAKANG
        function gantiKamera() {
            kameraBelakang = !kameraBelakang;
            bukaKamera();
        }



        function ambilFoto() {

            let video = document.getElementById('video');
            let canvas = document.getElementById('canvas');
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            let ctx = canvas.getContext('2d');

            ctx.drawImage(
                video,
                0,
                0,
                canvas.width,
                canvas.height
            );

            canvas.toBlob(function(blob) {
                let file = new File(
                    [blob],
                    "kamera_" + Date.now() + ".png", {
                        type: "image/png"
                    }
                );

                let dt = new DataTransfer();
                dt.items.add(file);
                document.getElementById('foto').files = dt.files;
                let preview = document.getElementById('preview');
                preview.src = URL.createObjectURL(blob);
                preview.classList.remove('hidden');
            });

            // matikan kamera
            stream.getTracks().forEach(track => {
                track.stop();
            });
        }
    </script>

</body>

</html>