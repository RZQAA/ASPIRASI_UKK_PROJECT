<?php
// Menampilkan semua error (untuk debugging)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Memulai session jika belum ada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Menghubungkan ke model Aspirasi
require_once "../models/M_Aspirasi.php";
$model = new M_Aspirasi();

// Mengambil parameter 'aksi' dari URL (GET)
// Jika tidak ada, default = string kosong
$aksi = $_GET['aksi'] ?? '';


// SECTION 1: LOGIKA


// Menampilkan dashboard siswa
if ($aksi == "dashboard_siswa") {

    // Mengambil ID siswa dari session
    $id_user = $_SESSION['siswa']['id_siswa'] ?? 0;

    // Jika belum login, redirect ke halaman login
    if ($id_user == 0) {
        header("Location: ../views/auth/login.php");
        exit;
    }

    // Mengambil data aspirasi milik siswa
    $aspirasi_list = $model->tampilSiswa($id_user);

    // Menampilkan halaman dashboard siswa
    include "../views/siswa/dashboard.php";
    exit;
}


// Menambah aspirasi baru
if ($aksi == "tambah_aspirasi") {

    // Ambil ID siswa dari session
    $id_user = $_SESSION['siswa']['id_siswa'] ?? 0;

    // Jika ada data POST
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        // Default tidak ada gambar
        $gambar = '';

        // Cek apakah ada file yang dikirim
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] !== UPLOAD_ERR_NO_FILE) {

            // Cek apakah upload berhasil
            if ($_FILES['foto']['error'] !== UPLOAD_ERR_OK) {
                echo "<script>
                    alert('Foto gagal diupload!');
                    window.history.back();
                </script>";
                exit;
            }

            $namaFile = $_FILES['foto']['name'];
            $tmpFile  = $_FILES['foto']['tmp_name'];
            $ukuran   = $_FILES['foto']['size'];

            // Ambil ekstensi file
            $ekstensi = strtolower(
                pathinfo($namaFile, PATHINFO_EXTENSION)
            );

            // Format yang diperbolehkan
            $formatValid = ['jpg', 'jpeg', 'png'];

            if (!in_array($ekstensi, $formatValid)) {
                echo "<script>
                    alert('Format foto harus JPG, JPEG, atau PNG!');
                    window.history.back();
                </script>";
                exit;
            }

            // Maksimal 2 MB
            if ($ukuran > 2 * 1024 * 1024) {
                echo "<script>
                    alert('Ukuran foto maksimal 2 MB!');
                    window.history.back();
                </script>";
                exit;
            }

            // Nama file baru
            $namaBaru = uniqid('aspirasi_') . '.' . $ekstensi;

            // PATH SESUAI FOLDER KAMU
            // app/controllers
            //      ↓ ../
            // app/views/assets/uploads
            $folder = __DIR__ . "/../views/assets/uploads/";

            // Buat folder jika belum ada
            if (!is_dir($folder)) {
                mkdir($folder, 0777, true);
            }

            // Pindahkan file
            if (!move_uploaded_file($tmpFile, $folder . $namaBaru)) {
                echo "<script>
                    alert('Gagal menyimpan foto ke folder uploads!');
                    window.history.back();
                </script>";
                exit;
            }

            // Simpan nama file
            $gambar = $namaBaru;
        }

        // ==========================================
        // EDIT ASPIRASI SISWA
        // ==========================================

        if ($aksi == "edit_aspirasi") {

            // Pastikan siswa sudah login
            if (!isset($_SESSION['siswa'])) {
                header("Location: ../views/auth/login.php");
                exit;
            }

            $id_siswa = $_SESSION['siswa']['id_siswa'];

            // Jika request POST
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {

                $id_aspirasi = $_POST['id_aspirasi'] ?? 0;
                $judul = $_POST['topik'] ?? '';
                $id_kategori = $_POST['kategori'] ?? '';
                $isi = $_POST['isi_laporan'] ?? '';

                // Foto lama
                $dataLama = $model->cariId($id_aspirasi);

                if (!$dataLama || $dataLama['id_siswa'] != $id_siswa || $dataLama['status'] != 'Menunggu') {
                    echo "<script>
                    alert('Laporan tidak dapat diedit!');
                    window.location='../views/siswa/riwayat.php';
                  </script>";
                    exit;
                }

                // Default gunakan foto lama
                $foto = null;

                // Kalau siswa upload foto baru
                if (isset($_FILES['foto']) && $_FILES['foto']['error'] !== UPLOAD_ERR_NO_FILE) {

                    if ($_FILES['foto']['error'] !== UPLOAD_ERR_OK) {
                        echo "<script>
                        alert('Foto gagal diupload!');
                        window.history.back();
                      </script>";
                        exit;
                    }

                    $namaFile = $_FILES['foto']['name'];
                    $tmpFile = $_FILES['foto']['tmp_name'];
                    $ukuran = $_FILES['foto']['size'];

                    $ekstensi = strtolower(
                        pathinfo($namaFile, PATHINFO_EXTENSION)
                    );

                    $formatValid = ['jpg', 'jpeg', 'png'];

                    if (!in_array($ekstensi, $formatValid)) {
                        echo "<script>
                        alert('Format foto harus JPG, JPEG, atau PNG!');
                        window.history.back();
                      </script>";
                        exit;
                    }

                    if ($ukuran > 2 * 1024 * 1024) {
                        echo "<script>
                        alert('Ukuran foto maksimal 2 MB!');
                        window.history.back();
                      </script>";
                        exit;
                    }

                    $namaBaru = uniqid('aspirasi_') . '.' . $ekstensi;

                    $folder = __DIR__ . "/../views/assets/uploads/";

                    if (!is_dir($folder)) {
                        mkdir($folder, 0777, true);
                    }

                    if (!move_uploaded_file($tmpFile, $folder . $namaBaru)) {
                        echo "<script>
                        alert('Gagal menyimpan foto!');
                        window.history.back();
                      </script>";
                        exit;
                    }

                    // Foto baru
                    $foto = $namaBaru;

                    // Hapus foto lama kalau ada
                    if (!empty($dataLama['foto'])) {
                        $fotoLama = $folder . $dataLama['foto'];

                        if (file_exists($fotoLama)) {
                            unlink($fotoLama);
                        }
                    }
                }

                // Simpan perubahan
                $update = $model->edit(
                    $id_aspirasi,
                    $id_siswa,
                    $judul,
                    $id_kategori,
                    $isi,
                    $foto
                );

                if ($update) {
                    echo "<script>
                    alert('Laporan berhasil diedit!');
                    window.location='../views/siswa/riwayat.php';
                  </script>";
                } else {
                    echo "<script>
                    alert('Laporan gagal diedit!');
                    window.history.back();
                  </script>";
                }

                exit;
            }

            // Kalau GET, ambil data untuk form edit
            $id_aspirasi = $_GET['id'] ?? 0;
            $data = $model->cariId($id_aspirasi);

            if (!$data || $data['id_siswa'] != $id_siswa || $data['status'] != 'Menunggu') {
                echo "<script>
                alert('Laporan tidak dapat diedit!');
                window.location='../views/siswa/riwayat.php';
              </script>";
                exit;
            }

            include "../views/siswa/edit.php";
            exit;
        }

        // ==========================================
        // SIMPAN DATA ASPIRASI
        // ==========================================
        // DILUAR IF FOTO supaya tanpa foto juga tetap bisa
        $simpan = $model->simpan(
            $id_user,
            $_POST['topik'],
            $_POST['kategori'],
            $_POST['isi_laporan'],
            $gambar
        );

        if ($simpan) {
            echo "<script>
                alert('Aspirasi berhasil dikirim!');
                window.location='../views/siswa/dashboard.php';
            </script>";
        } else {
            echo "<script>
                alert('Aspirasi gagal disimpan!');
                window.history.back();
            </script>";
        }

        exit;
    }

    // Tampilkan form
    include "../views/siswa/form.php";
    exit;
}


// SECTION 2: LOGIKA GURU


// Menampilkan semua aspirasi untuk guru
if ($aksi == "daftar_aspirasi_guru") {

    // Cek apakah user adalah guru
    if (!isset($_SESSION['guru'])) {
        header("Location: ../views/auth/login.php");
        exit;
    }

    // Mengambil semua data aspirasi
    $semua_aspirasi = $model->tampilSemua();

    // Menampilkan halaman daftar aspirasi guru
    include "../views/guru/daftar_aspirasi.php";
    exit;
}


// Proses tanggapan dari guru
if ($aksi == "tanggapi_guru") {

    // Pastikan request adalah POST
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        // Mengambil data dari form
        $id       = $_POST['id_aspirasi'] ?? 0;
        $status   = $_POST['status'] ?? '';
        $feedback = $_POST['feedback'] ?? '';

        // Mengupdate data aspirasi
        $update = $model->tanggapi($id, $status, $feedback);

        // Jika berhasil
        if ($update) {
            header("Location: ../views/guru/daftar_aspirasi.php?success=1");
            exit;
        } else {
            header("Location: ../views/guru/daftar_aspirasi.php");
            exit;
        }
    }
}


// SECTION 3: RATING SISWA


// Menyimpan rating dari siswa
if ($aksi == "simpan_rating") {

    // Pastikan siswa sudah login
    if (!isset($_SESSION['siswa'])) {
        header("Location: ../views/auth/login.php");
        exit;
    }

    // Pastikan request menggunakan POST
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        // Ambil data dari form
        $id_aspirasi = $_POST['id_aspirasi'] ?? 0;
        $rating = $_POST['rating'] ?? 0;

        // Validasi rating harus antara 0.5 sampai 5
        // dan hanya boleh kelipatan 0.5
        if (
            !is_numeric($rating) ||
            $rating < 0.5 ||
            $rating > 5 ||
            fmod((float)$rating, 0.5) != 0
        ) {
            echo "<script>
                    alert('Rating tidak valid!');
                    window.history.back();
                  </script>";
            exit;
        }

        // Simpan rating + pindahkan ke histori
        $update = $model->simpanRating($id_aspirasi, $rating);

        // Jika berhasil
        if ($update) {
            header("Location: ../views/siswa/riwayat.php?rating=success");
            exit;
        } else {
            header("Location: ../views/siswa/riwayat.php?rating=failed");
            exit;
        }
    }
}


// SECTION 4: UMUM (HAPUS DATA DAN EDIT DATA)


// ==========================================
// HAPUS ASPIRASI SISWA DAN EDIT SISWA
// ==========================================

if ($aksi == "update_aspirasi") {

    if (!isset($_SESSION['siswa'])) {
        header("Location: ../views/auth/login.php");
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("Location: ../views/siswa/riwayat.php");
        exit;
    }


    $id_siswa = $_SESSION['siswa']['id_siswa'] ?? 0;

    $id_aspirasi = $_POST['id_aspirasi'] ?? 0;
    $judul       = $_POST['topik'] ?? '';
    $kategori    = $_POST['kategori'] ?? '';
    $isi         = $_POST['isi_laporan'] ?? '';


    if ($id_siswa == 0 || $id_aspirasi == 0) {
        header("Location: ../views/siswa/riwayat.php");
        exit;
    }


    // AMBIL DATA LAMA
    $dataLama = $model->cariIdSiswa($id_aspirasi, $id_siswa);

    if (!$dataLama) {
        echo "<script>
            alert('Laporan tidak ditemukan atau sudah tidak bisa diedit!');
            window.location='../views/siswa/riwayat.php';
        </script>";
        exit;
    }


    // FOTO LAMA
    $gambar = $dataLama['foto'] ?? '';


    // JIKA ADA FOTO BARU
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] !== UPLOAD_ERR_NO_FILE) {

        if ($_FILES['foto']['error'] !== UPLOAD_ERR_OK) {
            echo "<script>
                alert('Foto gagal diupload!');
                window.history.back();
            </script>";
            exit;
        }


        $namaFile = $_FILES['foto']['name'];
        $tmpFile  = $_FILES['foto']['tmp_name'];
        $ukuran   = $_FILES['foto']['size'];

        $ekstensi = strtolower(
            pathinfo($namaFile, PATHINFO_EXTENSION)
        );

        $formatValid = ['jpg', 'jpeg', 'png'];


        if (!in_array($ekstensi, $formatValid)) {
            echo "<script>
                alert('Format foto harus JPG, JPEG, atau PNG!');
                window.history.back();
            </script>";
            exit;
        }


        if ($ukuran > 2 * 1024 * 1024) {
            echo "<script>
                alert('Ukuran foto maksimal 2 MB!');
                window.history.back();
            </script>";
            exit;
        }


        $namaBaru = uniqid('aspirasi_') . '.' . $ekstensi;

        $folder = __DIR__ . "/../views/assets/uploads/";


        if (!is_dir($folder)) {
            mkdir($folder, 0777, true);
        }


        if (!move_uploaded_file($tmpFile, $folder . $namaBaru)) {
            echo "<script>
                alert('Gagal menyimpan foto baru!');
                window.history.back();
            </script>";
            exit;
        }


        // Simpan nama foto baru
        $gambar = $namaBaru;
    }


    // UPDATE DATABASE
    $update = $model->updateSiswa(
        $id_aspirasi,
        $id_siswa,
        $judul,
        $kategori,
        $isi,
        $gambar
    );


    if ($update) {

        echo "<script>
            alert('Laporan berhasil diperbarui!');
            window.location='../views/siswa/riwayat.php';
        </script>";
    } else {

        echo "<script>
            alert('Laporan gagal diperbarui!');
            window.history.back();
        </script>";
    }

    exit;
}

if ($aksi == "hapus_aspirasi") {

    // Pastikan siswa sudah login
    if (!isset($_SESSION['siswa'])) {
        header("Location: ../views/auth/login.php");
        exit;
    }

    $id_siswa = $_SESSION['siswa']['id_siswa'];
    $id = $_GET['id'] ?? 0;

    // Ambil data terlebih dahulu
    $data = $model->cariId($id);

    // Pastikan laporan milik siswa dan masih Menunggu
    if (!$data || $data['id_siswa'] != $id_siswa || $data['status'] != 'Menunggu') {

        echo "<script>
                alert('Laporan tidak dapat dihapus!');
                window.location='../views/siswa/riwayat.php';
              </script>";
        exit;
    }

    // Hapus foto jika ada
    if (!empty($data['foto'])) {

        $foto = __DIR__ . "/../views/assets/uploads/" . $data['foto'];

        if (file_exists($foto)) {
            unlink($foto);
        }
    }

    // Hapus data aspirasi
    $hapus = $model->hapus($id, $id_siswa);

    if ($hapus) {
        echo "<script>
                alert('Laporan berhasil dihapus!');
                window.location='../views/siswa/riwayat.php';
              </script>";
    } else {
        echo "<script>
                alert('Laporan gagal dihapus!');
                window.history.back();
              </script>";
    }

    exit;
}
