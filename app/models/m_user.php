<?php
// Menghubungkan file ini dengan file koneksi database
include 'M_koneksi.php';

// Membuat class User yang mewarisi (extends) class Koneksi
class User extends Koneksi {

    // Fungsi untuk login sebagai siswa
    public function loginSiswa($username, $password) {

        // Menjalankan query untuk mengambil data siswa berdasarkan username
        $data = mysqli_query($this->koneksi,
            "SELECT * FROM siswa WHERE username='$username'"
        );

        // Mengecek apakah data yang ditemukan hanya 1 (username valid)
        if (mysqli_num_rows($data) == 1) {

            // Mengambil data hasil query dalam bentuk array asosiatif
            $row = mysqli_fetch_assoc($data);

            // Membandingkan password input dengan password di database
            // (Masih menggunakan plain text, belum aman)
            if ($password == $row['password']) {

                // Jika cocok, kembalikan data user
                return $row;
            }
        }

        // Jika username tidak ditemukan atau password salah
        return false;
    }

    // Fungsi untuk login sebagai admin
    public function loginAdmin($username, $password) {

        // Query untuk mengambil data admin berdasarkan username
        $data = mysqli_query($this->koneksi,
            "SELECT * FROM admin WHERE username='$username'"
        );

        // Mengecek apakah data ditemukan
        if (mysqli_num_rows($data) == 1) {

            // Mengambil data admin
            $row = mysqli_fetch_assoc($data);

            // Mengecek kecocokan password
            if ($password == $row['password']) {

                // Jika cocok, kembalikan data admin
                return $row;
            }
        }

        // Jika gagal login
        return false;
    }
}