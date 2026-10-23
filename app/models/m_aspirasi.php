<?php
// Mengambil file koneksi database
require_once 'M_koneksi.php';

// Class untuk mengelola data aspirasi
class M_Aspirasi
{
    private $db;

    // Constructor
    public function __construct()
    {
        global $koneksi;

        // Mengecek apakah koneksi tersedia
        if ($koneksi != null) {
            $this->db = $koneksi->koneksi;
        } else {
            die("Koneksi database tidak tersedia.");
        }
    }


    // SECTION 1: FUNGSI UNTUK SISWA


    // Menyimpan aspirasi baru
    public function simpan($id_siswa, $judul, $id_kategori, $isi, $foto)
    {
        $query = "INSERT INTO aspirasi
                  (id_siswa, id_kategori, judul, isi_aspirasi, foto, status, feedback, tanggal_input)
                  VALUES
                  ('$id_siswa', '$id_kategori', '$judul', '$isi', '$foto', 'Menunggu', '', NOW())";

        return $this->db->query($query);
    }

    // Mengedit aspirasi milik siswa
    public function edit($id_aspirasi, $id_siswa, $judul, $id_kategori, $isi, $foto = null)
    {
        // Kalau tidak mengganti foto
        if ($foto === null) {

            $query = "UPDATE aspirasi SET
                    judul = '$judul',
                    id_kategori = '$id_kategori',
                    isi_aspirasi = '$isi'
                  WHERE id_aspirasi = '$id_aspirasi'
                  AND id_siswa = '$id_siswa'
                  AND status = 'Menunggu'";
        } else {

            $query = "UPDATE aspirasi SET
                    judul = '$judul',
                    id_kategori = '$id_kategori',
                    isi_aspirasi = '$isi',
                    foto = '$foto'
                  WHERE id_aspirasi = '$id_aspirasi'
                  AND id_siswa = '$id_siswa'
                  AND status = 'Menunggu'";
        }

        return $this->db->query($query);
    }

    public function updateSiswa($id_aspirasi, $id_siswa, $judul, $id_kategori, $isi, $foto)
    {
        $query = "UPDATE aspirasi SET
                judul = '$judul',
                id_kategori = '$id_kategori',
                isi_aspirasi = '$isi',
                foto = '$foto'
              WHERE id_aspirasi = '$id_aspirasi'
              AND id_siswa = '$id_siswa'
              AND status = 'Menunggu'";

        return $this->db->query($query);
    }

    public function cariIdSiswa($id_aspirasi, $id_siswa)
    {
        $query = "SELECT *
              FROM aspirasi
              WHERE id_aspirasi = '$id_aspirasi'
              AND id_siswa = '$id_siswa'
              AND status = 'Menunggu'";

        $result = $this->db->query($query);

        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }

        return false;
    }


    // Menampilkan semua aspirasi milik siswa tertentu
    public function tampilSiswa($id_siswa, $limit = null, $offset = 0, $status = '')
    {
        $query = "SELECT 
                a.*,
                k.nama_kategori
              FROM aspirasi a
              LEFT JOIN kategori k 
                ON a.id_kategori = k.id_kategori
              WHERE a.id_siswa = '$id_siswa'
              AND NOT EXISTS (
                  SELECT 1
                  FROM histori_aspirasi h
                  WHERE h.id_aspirasi = a.id_aspirasi
              )";

        // Filter status
        if ($status != '') {
            $query .= " AND a.status = '$status'";
        }

        $query .= " ORDER BY a.id_aspirasi DESC";

        // Pagination
        if ($limit !== null) {
            $query .= " LIMIT $limit OFFSET $offset";
        }

        return $this->db->query($query);
    }

    public function hitungTotalSiswa($id_siswa, $status = '')
    {
        $query = "SELECT COUNT(*) AS total
              FROM aspirasi a
              WHERE a.id_siswa = '$id_siswa'
              AND NOT EXISTS (
                  SELECT 1
                  FROM histori_aspirasi h
                  WHERE h.id_aspirasi = a.id_aspirasi
              )";

        // Filter status
        if ($status != '') {
            $query .= " AND a.status = '$status'";
        }

        $result = $this->db->query($query);

        if ($result) {
            $data = $result->fetch_assoc();
            return (int) $data['total'];
        }

        return 0;
    }


    // SECTION 2: FUNGSI UNTUK GURU


    // Menampilkan semua aspirasi yang masih aktif
    public function tampilSemua()
    {
        $query = "SELECT 
                    a.*,
                    s.nama_siswa,
                    k.nama_kategori
                  FROM aspirasi a
                  JOIN siswa s 
                    ON a.id_siswa = s.id_siswa
                  LEFT JOIN kategori k 
                    ON a.id_kategori = k.id_kategori
                  WHERE a.status != 'Selesai'
                  ORDER BY 
                    CASE
                        WHEN a.status = 'Menunggu' THEN 1
                        WHEN a.status = 'Diproses' THEN 2
                        ELSE 3
                    END,
                    a.tanggal_input DESC";

        return $this->db->query($query);
    }


    // Menghitung statistik dashboard
    public function hitungStatistik()
    {
        $stats = [

            'total' => $this->db->query(
                "SELECT COUNT(*) as jml FROM aspirasi"
            )->fetch_assoc()['jml'],

            'menunggu' => $this->db->query(
                "SELECT COUNT(*) as jml FROM aspirasi WHERE status = 'Menunggu'"
            )->fetch_assoc()['jml'],

            'proses' => $this->db->query(
                "SELECT COUNT(*) as jml FROM aspirasi WHERE status = 'DiProses'"
            )->fetch_assoc()['jml'],

            'selesai' => $this->db->query(
                "SELECT COUNT(*) as jml FROM aspirasi WHERE status = 'Selesai'"
            )->fetch_assoc()['jml']
        ];

        return $stats;
    }


    // Guru memberikan tanggapan ke aspirasi
    public function tanggapi($id, $status, $feedback)
    {
        // Ambil status saat ini dari database
        $queryData = "SELECT status
              FROM aspirasi
              WHERE id_aspirasi = '$id'";

        $result = $this->db->query($queryData);

        // Cek apakah query berhasil
        if (!$result) {
            return false;
        }

        // Ambil data
        $data = $result->fetch_assoc();

        // Kalau data tidak ditemukan
        if (!$data) {
            return false;
        }

        $statusLama = $data['status'];


        // ==========================================
        // ATURAN PERPINDAHAN STATUS
        // ==========================================

        // Menunggu hanya boleh menjadi Diproses
        if ($statusLama === 'Menunggu' && $status !== 'Diproses') {
            return false;
        }

        // Diproses hanya boleh menjadi Selesai
        if ($statusLama === 'Diproses' && $status !== 'Selesai') {
            return false;
        }

        // Selesai tidak boleh diubah lagi
        if ($statusLama === 'Selesai') {
            return false;
        }


        // Pastikan status baru valid
        $statusValid = ['Menunggu', 'Diproses', 'Selesai'];

        if (!in_array($status, $statusValid, true)) {
            return false;
        }


        // ==========================================
        // UPDATE STATUS DAN FEEDBACK
        // ==========================================

        $query = "UPDATE aspirasi SET
            status = '$status',
            feedback = '$feedback'
          WHERE id_aspirasi = '$id'";

        $update = $this->db->query($query);

        // Cek apakah UPDATE berhasil
        if (!$update) {
            return false;
        }

        return true;
    }

    // SECTION 3: RATING SISWA


    // Siswa memberikan rating pada aspirasi yang sudah selesai
    public function simpanRating($id_aspirasi, $rating)
    {
        // Ambil data aspirasi
        $query = "SELECT
                    a.id_aspirasi,
                    a.id_siswa,
                    a.id_kategori,
                    a.id_admin,
                    a.judul,
                    a.isi_aspirasi,
                    a.foto,
                    a.status,
                    a.feedback,
                    a.tanggal_input
                  FROM aspirasi a
                  WHERE a.id_aspirasi = '$id_aspirasi'
                  AND a.status = 'Selesai'";

        $result = $this->db->query($query);

        if (!$result || $result->num_rows == 0) {
            return false;
        }

        $data = $result->fetch_assoc();

        // Masukkan data lengkap ke histori
        $insert = "INSERT INTO histori_aspirasi (
                    id_aspirasi,
                    id_siswa,
                    id_kategori,
                    id_admin,
                    judul,
                    isi_aspirasi,
                    foto,
                    status,
                    feedback,
                    rating,
                    tanggal_input,
                    tanggal_selesai
                ) VALUES (
                    '{$data['id_aspirasi']}',
                    '{$data['id_siswa']}',
                    '{$data['id_kategori']}',
                    " . ($data['id_admin'] !== null ? "'{$data['id_admin']}'" : "NULL") . ",
                    '{$data['judul']}',
                    '{$data['isi_aspirasi']}',
                    '{$data['foto']}',
                    '{$data['status']}',
                    '{$data['feedback']}',
                    '$rating',
                    '{$data['tanggal_input']}',
                    NOW()
                )";

        if (!$this->db->query($insert)) {
            return false;
        }

        return true;
    }


    // SECTION 4: FUNGSI UMUM


    // Mencari satu data berdasarkan ID
    public function cariId($id)
    {
        return $this->db->query(
            "SELECT * FROM aspirasi WHERE id_aspirasi = $id"
        )->fetch_assoc();
    }


    // Menghapus aspirasi milik siswa
    public function hapus($id_aspirasi, $id_siswa)
    {
        $query = "DELETE FROM aspirasi
              WHERE id_aspirasi = '$id_aspirasi'
              AND id_siswa = '$id_siswa'
              AND status = 'Menunggu'";

        return $this->db->query($query);
    }




    // SECTION 5: FUNGSI UNTUK MEMINDAHKAN KE HISTORI ASPIRASI


    // Memindahkan aspirasi yang sudah selesai dan sudah diberi rating ke history
    public function pindahKeHistory($id_aspirasi)
    {
        // Ambil data aspirasi lengkap
        $query = "SELECT 
                    a.id_aspirasi,
                    ia.id_siswa,
                    a.id_kategori,
                    a.id_admin,
                    a.judul,
                    a.isi_aspirasi,
                    a.status,
                    a.feedback,
                    a.rating,
                    ia.tanggal_input
                  FROM aspirasi a
                  JOIN input_aspirasi ia
                    ON a.id_input = ia.id_input
                  WHERE a.id_aspirasi = $id_aspirasi";

        $result = $this->db->query($query);

        // Kalau data tidak ditemukan
        if (!$result || $result->num_rows == 0) {
            return false;
        }

        $data = $result->fetch_assoc();

        // Pastikan laporan sudah SELESAI
        if ($data['status'] != 'Selesai') {
            return false;
        }

        // Pastikan siswa sudah memberikan rating
        if ($data['rating'] === null) {
            return false;
        }

        // Simpan ke history
        $insert = "INSERT INTO history_aspirasi (
                    id_aspirasi,
                    id_siswa,
                    id_kategori,
                    id_admin,
                    judul,
                    isi_aspirasi,
                    status,
                    feedback,
                    rating,
                    tanggal_input,
                    tanggal_selesai
               ) VALUES (
                    '{$data['id_aspirasi']}',
                    '{$data['id_siswa']}',
                    '{$data['id_kategori']}',
                    " . ($data['id_admin'] !== null ? "'{$data['id_admin']}'" : "NULL") . ",
                    '" . $this->db->real_escape_string($data['judul']) . "',
                    '" . $this->db->real_escape_string($data['isi_aspirasi']) . "',
                    '" . $this->db->real_escape_string($data['status']) . "',
                    '" . $this->db->real_escape_string($data['feedback']) . "',
                    '{$data['rating']}',
                    '{$data['tanggal_input']}',
                    NOW()
               )";

        // Jalankan INSERT
        if (!$this->db->query($insert)) {
            return false;
        }

        // Kalau berhasil masuk history,
        // baru hapus dari aspirasi aktif
        $delete = "DELETE FROM aspirasi
                   WHERE id_aspirasi = $id_aspirasi";

        return $this->db->query($delete);
    }


    // Menampilkan histori
    public function tampilHistori()
    {
        $query = "SELECT
                    h.*,
                    s.nama_siswa,
                    k.nama_kategori
                  FROM histori_aspirasi h
                  LEFT JOIN siswa s
                    ON h.id_siswa = s.id_siswa
                  LEFT JOIN kategori k
                    ON h.id_kategori = k.id_kategori
                  ORDER BY h.tanggal_history DESC";

        return $this->db->query($query);
    }


    // Menampilkan histori siswa
    public function tampilHistoriSiswa($id_siswa)
    {
        $query = "SELECT
                    h.*
                  FROM histori_aspirasi h
                  WHERE h.id_siswa = '$id_siswa'
                  ORDER BY h.tanggal_selesai DESC";

        return $this->db->query($query);
    }
}
