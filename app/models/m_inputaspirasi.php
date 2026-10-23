<?php
include 'M_koneksi.php';

class InputAspirasi extends Koneksi {
    public function simpan($id){
        mysqli_query($this->koneksi,
        "INSERT INTO input_aspirasi VALUES (NULL,'$id',NOW())");
        return mysqli_insert_id($this->koneksi);
    }
}


 // Menjalankan query INSERT ke tabel input_aspirasi
        // NULL → untuk kolom ID (biasanya auto increment)
        // '$id' → ID user / siswa yang mengirim aspirasi
        // NOW() → waktu saat data dimasukkan