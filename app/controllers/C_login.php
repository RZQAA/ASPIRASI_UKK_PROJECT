<?php
session_start();
include '../models/m_user.php';

$user = new User();

$username = $_POST['username'];
$password = $_POST['password'];

$siswa = $user->loginSiswa($username, $password);
$admin = $user->loginAdmin($username, $password);

if ($siswa) {

    $_SESSION['siswa'] = $siswa;
    $_SESSION['role'] = 'siswa';

    header("Location: ../views/siswa/dashboard.php");
    exit;

} elseif ($admin) {

    $_SESSION['admin'] = $admin;
    $_SESSION['role'] = 'admin';

    header("Location: ../views/guru/dashboard.php");
    exit;

} else {

    $_SESSION['error'] = "Username atau password yang kamu masukkan salah.";

    header("Location: ../views/auth/login.php");
    exit;
}