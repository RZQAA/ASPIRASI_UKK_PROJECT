-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 11, 2026 at 04:19 AM
-- Server version: 8.4.3
-- PHP Version: 8.3.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sarana_aspirasi`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id_admin` int NOT NULL,
  `username` varchar(30) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `password` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id_admin`, `username`, `password`) VALUES
(1, 'admin', '123');

-- --------------------------------------------------------

--
-- Table structure for table `aspirasi`
--

CREATE TABLE `aspirasi` (
  `id_aspirasi` int NOT NULL,
  `id_input` int DEFAULT NULL,
  `id_kategori` int DEFAULT NULL,
  `judul` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `isi_aspirasi` text COLLATE utf8mb4_general_ci,
  `foto` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `feedback` text COLLATE utf8mb4_general_ci,
  `rating` decimal(2,1) DEFAULT NULL,
  `id_admin` int DEFAULT NULL,
  `id_siswa` int NOT NULL,
  `tanggal_input` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `aspirasi`
--

INSERT INTO `aspirasi` (`id_aspirasi`, `id_input`, `id_kategori`, `judul`, `isi_aspirasi`, `foto`, `status`, `feedback`, `rating`, `id_admin`, `id_siswa`, `tanggal_input`) VALUES
(39, NULL, 1, 'lampu mati', 'RUSAKKK', NULL, 'Selesai', '', NULL, NULL, 1, '2026-09-04'),
(40, NULL, 2, 'lampu mati', 'lampu rusakk', NULL, 'Selesai', '', NULL, NULL, 1, '2026-09-04'),
(41, NULL, 1, 'lampu mati', 'matiii lampuunyaa tolong di ganti ini ruangan nya gelap', NULL, 'Selesai', 'selesai bosku', NULL, NULL, 1, '2026-09-04'),
(42, NULL, 3, 'lampu mati', 'dsadwd\r\n\r\n', NULL, 'Selesai', '', NULL, NULL, 1, '2026-09-04'),
(43, NULL, 3, 'ruangan ini kotor banyak sampahh', 'ruangan deket lab rpl kotorr banyak sampah', '', 'Selesai', '', NULL, NULL, 1, '2026-09-09'),
(44, NULL, 3, 'ruangan ini kotor banyak sampahh', 'kotor ruangan dekeet lab akl', '', 'Selesai', '', NULL, NULL, 1, '2026-09-09'),
(45, NULL, 3, 'ruangan ini kotor banyak sampahh', 'kotor', '', 'Selesai', '', NULL, NULL, 1, '2026-09-09'),
(46, NULL, 3, 'ruangan ini kotor banyak sampahh', 'dksakdsa', '', 'Selesai', '', NULL, NULL, 1, '2026-09-09'),
(47, NULL, 3, 'ruangan ini kotor banyak sampahh', 'kotor', 'aspirasi_6aa0aa9a79b09.jpg', 'Selesai', '', NULL, NULL, 1, '2026-09-09'),
(48, NULL, 3, 'ruangan ini kotor banyak sampahh', 'solo', 'aspirasi_6aa0af7139735.png', 'Selesai', '', NULL, NULL, 1, '2026-09-09'),
(49, NULL, 3, 'ruangan ini kotor banyak sampahh', 'dasdas', 'aspirasi_6aa0b9c7d303e.jpg', 'Selesai', 'selesaiii', NULL, NULL, 1, '2026-09-09'),
(51, NULL, 1, 'dsadsa', 'dsad', '', 'Menunggu', '', NULL, NULL, 1, '2026-09-09'),
(52, NULL, 1, 'dsadsa', 'sdasadsa', '', 'Menunggu', '', NULL, NULL, 1, '2026-09-09'),
(53, NULL, 2, 'dsadsadsa', 'dsadsa', '', 'Menunggu', '', NULL, NULL, 1, '2026-09-09'),
(55, NULL, 1, 'dsadsadsa', 'dsad', '', 'Diproses', '', NULL, NULL, 1, '2026-09-11'),
(56, NULL, 2, 'dasdasdwaa', 'dsadwa', '', 'Diproses', '', NULL, NULL, 1, '2026-09-11'),
(57, NULL, 2, 'dsadadwda', 'sadsad', '', 'Diproses', '', NULL, NULL, 1, '2026-09-11'),
(64, NULL, 2, 'mantap', 'dsadsa', '', 'Selesai', '', NULL, NULL, 1, '2026-09-11');

-- --------------------------------------------------------

--
-- Table structure for table `histori_aspirasi`
--

CREATE TABLE `histori_aspirasi` (
  `id_history` int NOT NULL,
  `id_aspirasi` int DEFAULT NULL,
  `id_siswa` int DEFAULT NULL,
  `id_kategori` int DEFAULT NULL,
  `id_admin` int DEFAULT NULL,
  `judul` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `isi_aspirasi` text COLLATE utf8mb4_general_ci,
  `foto` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `feedback` text COLLATE utf8mb4_general_ci,
  `rating` decimal(2,1) DEFAULT NULL,
  `tanggal_input` date DEFAULT NULL,
  `tanggal_selesai` datetime DEFAULT NULL,
  `tanggal_history` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `histori_aspirasi`
--

INSERT INTO `histori_aspirasi` (`id_history`, `id_aspirasi`, `id_siswa`, `id_kategori`, `id_admin`, `judul`, `isi_aspirasi`, `foto`, `status`, `feedback`, `rating`, `tanggal_input`, `tanggal_selesai`, `tanggal_history`) VALUES
(20, 39, 1, 1, 0, 'lampu mati', 'RUSAKKK', NULL, 'Selesai', '', 4.5, '2026-09-04', '2026-09-04 10:41:59', '2026-09-04 10:41:59'),
(21, 40, 1, 2, 0, 'lampu mati', 'lampu rusakk', NULL, 'Selesai', '', 5.0, '2026-09-04', '2026-09-04 10:51:23', '2026-09-04 10:51:23'),
(22, 41, 1, 1, 0, 'lampu mati', 'matiii lampuunyaa tolong di ganti ini ruangan nya gelap', NULL, 'Selesai', 'selesai bosku', 5.0, '2026-09-04', '2026-09-04 13:05:02', '2026-09-04 13:05:02'),
(23, 44, 1, 3, NULL, 'ruangan ini kotor banyak sampahh', 'kotor ruangan dekeet lab akl', '', 'Selesai', '', 5.0, '2026-09-09', '2026-09-09 07:57:53', '2026-09-09 07:57:53'),
(24, 43, 1, 3, NULL, 'ruangan ini kotor banyak sampahh', 'ruangan deket lab rpl kotorr banyak sampah', '', 'Selesai', '', 5.0, '2026-09-09', '2026-09-09 07:57:59', '2026-09-09 07:57:59'),
(25, 48, 1, 3, NULL, 'ruangan ini kotor banyak sampahh', 'solo', 'aspirasi_6aa0af7139735.png', 'Selesai', '', 5.0, '2026-09-09', '2026-09-09 08:23:42', '2026-09-09 08:23:42'),
(26, 47, 1, 3, NULL, 'ruangan ini kotor banyak sampahh', 'kotor', 'aspirasi_6aa0aa9a79b09.jpg', 'Selesai', '', 5.0, '2026-09-09', '2026-09-09 08:23:54', '2026-09-09 08:23:54'),
(27, 46, 1, 3, NULL, 'ruangan ini kotor banyak sampahh', 'dksakdsa', '', 'Selesai', '', 5.0, '2026-09-09', '2026-09-09 08:23:59', '2026-09-09 08:23:59'),
(28, 45, 1, 3, NULL, 'ruangan ini kotor banyak sampahh', 'kotor', '', 'Selesai', '', 5.0, '2026-09-09', '2026-09-09 08:24:04', '2026-09-09 08:24:04'),
(29, 42, 1, 3, NULL, 'lampu mati', 'dsadwd\r\n\r\n', '', 'Selesai', '', 4.5, '2026-09-04', '2026-09-09 08:24:20', '2026-09-09 08:24:20'),
(30, 64, 1, 2, NULL, 'mantap', 'dsadsa', '', 'Selesai', '', 5.0, '2026-09-11', '2026-09-11 09:08:43', '2026-09-11 09:08:43'),
(31, 49, 1, 3, NULL, 'ruangan ini kotor banyak sampahh', 'dasdas', 'aspirasi_6aa0b9c7d303e.jpg', 'Selesai', 'selesaiii', 4.0, '2026-09-09', '2026-09-11 09:14:22', '2026-09-11 09:14:22');

-- --------------------------------------------------------

--
-- Table structure for table `kategori`
--

CREATE TABLE `kategori` (
  `id_kategori` int NOT NULL,
  `nama_kategori` enum('kerusakan','fasilitas','kebersihan','lainnya') COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kategori`
--

INSERT INTO `kategori` (`id_kategori`, `nama_kategori`) VALUES
(1, 'kerusakan'),
(2, 'fasilitas'),
(3, 'kebersihan'),
(4, 'lainnya');

-- --------------------------------------------------------

--
-- Table structure for table `siswa`
--

CREATE TABLE `siswa` (
  `id_siswa` int NOT NULL,
  `nama_siswa` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kelas` varchar(10) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `username` varchar(30) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `password` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `siswa`
--

INSERT INTO `siswa` (`id_siswa`, `nama_siswa`, `kelas`, `username`, `password`) VALUES
(1, 'Budi', 'XI RPL', 'budi', '123'),
(3, 'razaqa', 'rpl 2', 'RZQ', '123'),
(4, 'nessa', 'rpl 2', 'ness', '123'),
(5, 'gilang', 'rpl 2', 'putra', '123'),
(6, 'fadly', 'rpl 2', 'fadly', '123');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id_admin`);

--
-- Indexes for table `aspirasi`
--
ALTER TABLE `aspirasi`
  ADD PRIMARY KEY (`id_aspirasi`),
  ADD KEY `id_kategori` (`id_kategori`),
  ADD KEY `id_admin` (`id_admin`),
  ADD KEY `id_input` (`id_input`),
  ADD KEY `id_siswa` (`id_siswa`);

--
-- Indexes for table `histori_aspirasi`
--
ALTER TABLE `histori_aspirasi`
  ADD PRIMARY KEY (`id_history`),
  ADD KEY `id_aspirasi` (`id_aspirasi`);

--
-- Indexes for table `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id_kategori`);

--
-- Indexes for table `siswa`
--
ALTER TABLE `siswa`
  ADD PRIMARY KEY (`id_siswa`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id_admin` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `aspirasi`
--
ALTER TABLE `aspirasi`
  MODIFY `id_aspirasi` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT for table `histori_aspirasi`
--
ALTER TABLE `histori_aspirasi`
  MODIFY `id_history` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id_kategori` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `siswa`
--
ALTER TABLE `siswa`
  MODIFY `id_siswa` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `aspirasi`
--
ALTER TABLE `aspirasi`
  ADD CONSTRAINT `aspirasi_ibfk_1` FOREIGN KEY (`id_kategori`) REFERENCES `kategori` (`id_kategori`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `aspirasi_ibfk_2` FOREIGN KEY (`id_admin`) REFERENCES `admin` (`id_admin`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `aspirasi_ibfk_3` FOREIGN KEY (`id_siswa`) REFERENCES `siswa` (`id_siswa`);

--
-- Constraints for table `histori_aspirasi`
--
ALTER TABLE `histori_aspirasi`
  ADD CONSTRAINT `histori_aspirasi_ibfk_1` FOREIGN KEY (`id_aspirasi`) REFERENCES `aspirasi` (`id_aspirasi`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
