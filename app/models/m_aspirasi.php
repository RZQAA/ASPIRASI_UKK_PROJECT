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
// Menampilkan semua aspirasi dengan filter
public function tampilSemua($filter = [])
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
              WHERE NOT EXISTS (
                  SELECT 1
                  FROM histori_aspirasi h
                  WHERE h.id_aspirasi = a.id_aspirasi
              )";

    $types = "";
    $params = [];

    // Filter berdasarkan tanggal
    if (!empty($filter['tanggal'])) {
        $tanggal = $filter['tanggal'];

        $query .= " AND DATE(a.tanggal_input) = ?";
        $types .= "s";
        $params[] = $tanggal;
    }

    // Filter berdasarkan bulan
    if (!empty($filter['bulan'])) {
        $bulan = $filter['bulan'];

        $query .= " AND DATE_FORMAT(a.tanggal_input, '%Y-%m') = ?";
        $types .= "s";
        $params[] = $bulan;
    }

    // Filter berdasarkan siswa
    if (!empty($filter['id_siswa'])) {
        $id_siswa = (int) $filter['id_siswa'];

        $query .= " AND a.id_siswa = ?";
        $types .= "i";
        $params[] = $id_siswa;
    }

    // Filter berdasarkan kategori
    if (!empty($filter['id_kategori'])) {
        $id_kategori = (int) $filter['id_kategori'];

        $query .= " AND a.id_kategori = ?";
        $types .= "i";
        $params[] = $id_kategori;
    }

    // Urutan aspirasi
    $query .= " ORDER BY
                CASE
                    WHEN a.status = 'Menunggu' THEN 1
                    WHEN a.status = 'Diproses' THEN 2
                    WHEN a.status = 'Selesai' THEN 3
                    ELSE 4
                END,
                a.tanggal_input DESC";

    $stmt = $this->db->prepare($query);

    if (!$stmt) {
        return false;
    }

    // Mengikat parameter jika ada filter
    if (!empty($params)) {
        $bindParams = [$types];

        foreach ($params as $key => $value) {
            $bindParams[] = &$params[$key];
        }

        call_user_func_array(
            [$stmt, 'bind_param'],
            $bindParams
        );
    }

    if (!$stmt->execute()) {
        $stmt->close();
        return false;
    }

    return $stmt->get_result();
}


// Mengambil daftar siswa untuk pilihan filter
public function ambilSiswaFilter()
{
    return $this->db->query(
        "SELECT id_siswa, nama_siswa
         FROM siswa
         ORDER BY nama_siswa ASC"
    );
}


// Mengambil daftar kategori untuk pilihan filter
public function ambilKategoriFilter()
{
    return $this->db->query(
        "SELECT id_kategori, nama_kategori
         FROM kategori
         ORDER BY nama_kategori ASC"
    );
}


   // Menghitung statistik dashboard
public function hitungStatistik()
{
    $stats = [
        'total' => $this->db->query(
            "SELECT COUNT(*) AS jml FROM aspirasi"
        )->fetch_assoc()['jml'],

        'menunggu' => $this->db->query(
            "SELECT COUNT(*) AS jml FROM aspirasi
             WHERE status = 'Menunggu'"
        )->fetch_assoc()['jml'],

        'proses' => $this->db->query(
            "SELECT COUNT(*) AS jml FROM aspirasi
             WHERE status = 'Diproses'"
        )->fetch_assoc()['jml'],

        'selesai' => $this->db->query(
            "SELECT COUNT(*) AS jml FROM aspirasi
             WHERE status = 'Selesai'"
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
    // Menyimpan rating dan memindahkan aspirasi ke histori
public function simpanRating($id_aspirasi, $id_siswa, $rating)
{
    // Validasi rating
    if (
        !is_numeric($rating) ||
        $rating < 0.5 ||
        $rating > 5 ||
        fmod((float) $rating, 0.5) != 0
    ) {
        return false;
    }

    // Mulai transaksi
    $this->db->begin_transaction();

    try {

        // Ambil data aspirasi milik siswa yang sudah selesai
        $stmt = $this->db->prepare(
            "SELECT *
             FROM aspirasi
             WHERE id_aspirasi = ?
             AND id_siswa = ?
             AND status = 'Selesai'
             FOR UPDATE"
        );

        $stmt->bind_param("ii", $id_aspirasi, $id_siswa);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            throw new Exception("Aspirasi tidak ditemukan atau belum selesai.");
        }

        $data = $result->fetch_assoc();
        $stmt->close();

        // Simpan data ke histori
        $stmt = $this->db->prepare(
            "INSERT INTO histori_aspirasi (
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
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())"
        );

        $stmt->bind_param(
            "iiiisssssds",
            $data['id_aspirasi'],
            $data['id_siswa'],
            $data['id_kategori'],
            $data['id_admin'],
            $data['judul'],
            $data['isi_aspirasi'],
            $data['foto'],
            $data['status'],
            $data['feedback'],
            $rating,
            $data['tanggal_input']
        );

        if (!$stmt->execute()) {
            throw new Exception("Gagal menyimpan histori.");
        }

        $stmt->close();

        // Hapus aspirasi aktif setelah histori berhasil disimpan
        $stmt = $this->db->prepare(
            "DELETE FROM aspirasi
             WHERE id_aspirasi = ?
             AND id_siswa = ?
             AND status = 'Selesai'"
        );

        $stmt->bind_param("ii", $id_aspirasi, $id_siswa);
        $stmt->execute();

        if ($stmt->affected_rows !== 1) {
            throw new Exception("Gagal menghapus aspirasi aktif.");
        }

        $stmt->close();

        // Simpan seluruh perubahan
        $this->db->commit();

        return true;

    } catch (Throwable $e) {

        // Batalkan seluruh perubahan jika terjadi kegagalan
        $this->db->rollback();

        return false;
    }
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
