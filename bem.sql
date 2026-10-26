-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 15, 2026 at 06:49 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bem`
--

-- --------------------------------------------------------

--
-- Table structure for table `disposisi`
--

CREATE TABLE `disposisi` (
  `id` int(10) UNSIGNED NOT NULL,
  `surat_masuk_id` int(10) UNSIGNED NOT NULL,
  `dari_user_id` int(10) UNSIGNED NOT NULL COMMENT 'User yang memberikan disposisi',
  `kepada_user_id` int(10) UNSIGNED NOT NULL COMMENT 'User yang menerima disposisi',
  `catatan` text DEFAULT NULL,
  `tanggal` date NOT NULL,
  `status` enum('belum_dibaca','sudah_dibaca','selesai') NOT NULL DEFAULT 'belum_dibaca',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Disposisi / pendelegasian surat masuk';

-- --------------------------------------------------------

--
-- Table structure for table `klasifikasi`
--

CREATE TABLE `klasifikasi` (
  `id` int(10) UNSIGNED NOT NULL,
  `kode` varchar(20) NOT NULL,
  `nama_klasifikasi` varchar(100) NOT NULL,
  `keterangan` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Kategori / jenis surat';

--
-- Dumping data for table `klasifikasi`
--

INSERT INTO `klasifikasi` (`id`, `kode`, `nama_klasifikasi`, `keterangan`, `created_at`) VALUES
(1, 'UM', 'Surat Umum', 'Surat yang bersifat umum dan tidak masuk kategori lain', '2025-01-01 00:00:00'),
(2, 'UND', 'Undangan', 'Surat undangan rapat, seminar, atau kegiatan', '2025-01-01 00:00:00'),
(3, 'SK', 'Surat Keputusan', 'Surat Keputusan resmi dari pimpinan atau instansi', '2025-01-01 00:00:00'),
(4, 'PKS', 'Perjanjian Kerja Sama', 'Memorandum of Understanding (MoU) dan PKS', '2025-01-01 00:00:00'),
(5, 'PER', 'Permohonan', 'Surat permohonan izin, dana, tempat, dan sejenisnya', '2025-01-01 00:00:00'),
(6, 'LPJ', 'Laporan', 'Laporan pertanggungjawaban kegiatan dan keuangan', '2025-01-01 00:00:00'),
(7, 'EDR', 'Edaran', 'Surat edaran / pengumuman resmi', '2025-01-01 00:00:00'),
(8, 'REK', 'Rekomendasi', 'Surat rekomendasi dan keterangan', '2025-01-01 00:00:00'),
(9, 'SPT', 'Surat Pengantar', 'Surat pengantar dokumen atau barang', '2025-01-01 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `log_aktivitas`
--

CREATE TABLE `log_aktivitas` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `aksi` varchar(100) NOT NULL,
  `keterangan` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Audit trail seluruh aktivitas pengguna';

--
-- Dumping data for table `log_aktivitas`
--

INSERT INTO `log_aktivitas` (`id`, `user_id`, `aksi`, `keterangan`, `ip_address`, `created_at`) VALUES
(1, 1, 'logout', 'Logout', '::1', '2026-03-15 05:23:42'),
(2, 1, 'login', 'Login berhasil dari IP: ::1', '::1', '2026-03-15 05:36:16'),
(3, 1, 'logout', 'Logout', '::1', '2026-03-15 05:39:46'),
(4, 1, 'login', 'Login berhasil dari IP: ::1', '::1', '2026-03-15 05:41:13'),
(5, 1, 'logout', 'Logout', '::1', '2026-03-15 05:41:17'),
(6, 1, 'login', 'Login berhasil dari IP: ::1', '::1', '2026-03-15 05:43:30'),
(7, 1, 'logout', 'Logout', '::1', '2026-03-15 05:43:35'),
(8, NULL, 'login_gagal', 'Email tidak ditemukan: admin@gmail.com', '::1', '2026-03-15 05:43:46'),
(9, 1, 'login', 'Login berhasil dari IP: ::1', '::1', '2026-03-15 05:43:58'),
(10, 1, 'logout', 'Logout', '::1', '2026-03-15 05:46:31'),
(11, 1, 'login', 'Login berhasil dari IP: ::1', '::1', '2026-03-15 05:46:39'),
(12, 1, 'logout', 'Logout', '::1', '2026-03-15 05:48:28');

-- --------------------------------------------------------

--
-- Table structure for table `surat_keluar`
--

CREATE TABLE `surat_keluar` (
  `id` int(10) UNSIGNED NOT NULL,
  `nomor_surat` varchar(100) NOT NULL COMMENT 'Nomor surat keluar resmi',
  `tanggal_surat` date NOT NULL,
  `tujuan` varchar(200) NOT NULL COMMENT 'Instansi / pihak yang dituju',
  `perihal` varchar(255) NOT NULL,
  `isi_singkat` text DEFAULT NULL,
  `klasifikasi_id` int(10) UNSIGNED DEFAULT NULL,
  `file_surat` varchar(255) DEFAULT NULL COMMENT 'Path relatif file di storage/',
  `status` enum('draft','dikirim','diarsipkan') NOT NULL DEFAULT 'draft',
  `user_id` int(10) UNSIGNED NOT NULL COMMENT 'Petugas yang membuat surat',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Arsip surat keluar dari BEM';

-- --------------------------------------------------------

--
-- Table structure for table `surat_masuk`
--

CREATE TABLE `surat_masuk` (
  `id` int(10) UNSIGNED NOT NULL,
  `nomor_agenda` varchar(30) NOT NULL COMMENT 'Nomor urut internal pencatatan',
  `nomor_surat` varchar(100) NOT NULL COMMENT 'Nomor surat dari pengirim',
  `tanggal_surat` date NOT NULL COMMENT 'Tanggal tertulis pada surat',
  `tanggal_terima` date NOT NULL COMMENT 'Tanggal surat diterima BEM',
  `asal_surat` varchar(200) NOT NULL COMMENT 'Instansi / nama pengirim',
  `tujuan` varchar(200) NOT NULL COMMENT 'Ditujukan kepada',
  `perihal` varchar(255) NOT NULL,
  `isi_singkat` text DEFAULT NULL,
  `klasifikasi_id` int(10) UNSIGNED DEFAULT NULL,
  `file_surat` varchar(255) DEFAULT NULL COMMENT 'Path relatif file di storage/',
  `status` enum('baru','diproses','selesai','diarsipkan') NOT NULL DEFAULT 'baru',
  `user_id` int(10) UNSIGNED NOT NULL COMMENT 'Petugas yang menginput',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Arsip surat masuk ke BEM';

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `nama` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','operator','anggota') NOT NULL DEFAULT 'anggota',
  `foto` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Data pengguna sistem';

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `nama`, `email`, `password`, `role`, `foto`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Administrator BEM', 'admin@bem.ac.id', '$2y$10$fcgejci3x3LGYnMc4.vbPuaqYRLx1/LeVfU5tLypfZa3vH41ovQqy', 'admin', NULL, 1, '2025-01-01 00:00:00', '2026-03-15 03:31:11');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `disposisi`
--
ALTER TABLE `disposisi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_disp_surat` (`surat_masuk_id`),
  ADD KEY `idx_disp_dari_user` (`dari_user_id`),
  ADD KEY `idx_disp_kepada_user` (`kepada_user_id`),
  ADD KEY `idx_disp_status` (`status`),
  ADD KEY `idx_disp_tanggal` (`tanggal`);

--
-- Indexes for table `klasifikasi`
--
ALTER TABLE `klasifikasi`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_klasifikasi_kode` (`kode`);

--
-- Indexes for table `log_aktivitas`
--
ALTER TABLE `log_aktivitas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_log_user` (`user_id`),
  ADD KEY `idx_log_aksi` (`aksi`),
  ADD KEY `idx_log_created` (`created_at`);

--
-- Indexes for table `surat_keluar`
--
ALTER TABLE `surat_keluar`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_sk_nomor_surat` (`nomor_surat`),
  ADD KEY `idx_sk_tanggal_surat` (`tanggal_surat`),
  ADD KEY `idx_sk_status` (`status`),
  ADD KEY `idx_sk_tujuan` (`tujuan`),
  ADD KEY `idx_sk_klasifikasi` (`klasifikasi_id`),
  ADD KEY `idx_sk_user` (`user_id`);

--
-- Indexes for table `surat_masuk`
--
ALTER TABLE `surat_masuk`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_sm_nomor_agenda` (`nomor_agenda`),
  ADD KEY `idx_sm_nomor_surat` (`nomor_surat`),
  ADD KEY `idx_sm_tanggal_terima` (`tanggal_terima`),
  ADD KEY `idx_sm_status` (`status`),
  ADD KEY `idx_sm_asal_surat` (`asal_surat`),
  ADD KEY `idx_sm_klasifikasi` (`klasifikasi_id`),
  ADD KEY `idx_sm_user` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_users_email` (`email`),
  ADD KEY `idx_users_role` (`role`),
  ADD KEY `idx_users_active` (`is_active`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `disposisi`
--
ALTER TABLE `disposisi`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `klasifikasi`
--
ALTER TABLE `klasifikasi`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `log_aktivitas`
--
ALTER TABLE `log_aktivitas`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `surat_keluar`
--
ALTER TABLE `surat_keluar`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `surat_masuk`
--
ALTER TABLE `surat_masuk`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `disposisi`
--
ALTER TABLE `disposisi`
  ADD CONSTRAINT `fk_disp_dari` FOREIGN KEY (`dari_user_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_disp_kepada` FOREIGN KEY (`kepada_user_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_disp_surat` FOREIGN KEY (`surat_masuk_id`) REFERENCES `surat_masuk` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `log_aktivitas`
--
ALTER TABLE `log_aktivitas`
  ADD CONSTRAINT `fk_log_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `surat_keluar`
--
ALTER TABLE `surat_keluar`
  ADD CONSTRAINT `fk_sk_klasifikasi` FOREIGN KEY (`klasifikasi_id`) REFERENCES `klasifikasi` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_sk_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `surat_masuk`
--
ALTER TABLE `surat_masuk`
  ADD CONSTRAINT `fk_sm_klasifikasi` FOREIGN KEY (`klasifikasi_id`) REFERENCES `klasifikasi` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_sm_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
