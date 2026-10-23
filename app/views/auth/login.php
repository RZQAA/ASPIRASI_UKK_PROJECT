<?php
session_start();
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Aspirasi Care</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="../assets/login.css">
</head>

<body>

    <div class="split-layout">

        <!-- BAGIAN KIRI: BRANDING -->
        <div class="brand-panel">
            <!-- Orbs Background -->
            <div class="orb orb-1"></div>
            <div class="orb orb-2"></div>
            <div class="orb orb-3"></div>

            <div class="brand-content">
                <div class="logo-wrapper">
                    <svg class="logo-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                    </svg>
                    <span class="logo-text">Aspirasi Care.</span>
                </div>

                <h1 class="brand-title">
                    Suaramu <br>
                    Membentuk <br>
                    <span>Perubahan.</span>
                </h1>

                <p class="brand-desc">
                    Platform pengaduan dan aspirasi sekolah yang aman dan transparan. Laporkan kendala atau berikan masukan untuk sekolah yang lebih baik.
                </p>
            </div>

            <div class="brand-footer">
                <div class="pulse-dot"></div>
                Sistem Aktif & Terenkripsi
            </div>
        </div>

        <!-- BAGIAN KANAN: FORM LOGIN -->
        <div class="form-panel">
            <div class="form-card">

                <div class="form-header">
                    <h2 class="form-title">Masuk Akun</h2>
                    <p class="form-subtitle">Gunakan NIS atau username yang telah diberikan oleh pihak sekolah.</p>
                </div>
                <?php if (isset($_SESSION['error'])): ?>

                    <div class="error-alert">
                        <?= $_SESSION['error']; ?>
                    </div>

                    <?php unset($_SESSION['error']); ?>

                <?php endif; ?>

                <form method="post" action="../../controllers/C_login.php">

                    <!-- Input Username -->
                    <div class="input-group">
                        <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <!-- perhatikan placeholder spasi kosong wajib ada agar trik CSS floating label berjalan -->
                        <input type="text" name="username" id="username" class="input-field" placeholder=" " required autocomplete="username">
                        <label for="username" class="input-label">Username / NIS</label>
                    </div>

                    <!-- Input Password -->
                    <div class="input-group">
                        <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                        <input type="password" name="password" id="password" class="input-field" placeholder=" " required autocomplete="current-password">
                        <label for="password" class="input-label">Kata Sandi</label>
                    </div>

                    <button type="submit" class="btn-submit">
                        Masuk
                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                        </svg>
                    </button>

                </form>

                <div class="system-info">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                    Portal Pengaduan Internal Sekolah
                </div>

            </div>
        </div>

    </div>

</body>

</html>