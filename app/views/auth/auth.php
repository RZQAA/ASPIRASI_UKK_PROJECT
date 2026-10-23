<?php
session_start();

function cekLogin()
{
    if (!isset($_SESSION['admin']) && !isset($_SESSION['siswa'])) {
        header("Location: ../auth/login.php");
        exit;
    }
}

function cekAdmin()
{
    cekLogin();

    // Kalau yang login bukan admin
    if (!isset($_SESSION['admin'])) {

        // Kalau ternyata siswa, kembalikan ke dashboard siswa
        if (isset($_SESSION['siswa'])) {
            header("Location: ../siswa/dashboard.php");
            exit;
        }

        header("Location: ../auth/login.php");
        exit;
    }
}

function cekSiswa()
{
    cekLogin();

    // Kalau yang login bukan siswa
    if (!isset($_SESSION['siswa'])) {

        // Kalau ternyata admin, kembalikan ke dashboard guru
        if (isset($_SESSION['admin'])) {
            header("Location: ../guru/dashboard.php");
            exit;
        }

        header("Location: ../auth/login.php");
        exit;
    }
}
?>