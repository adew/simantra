-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 28, 2024 at 07:11 AM
-- Server version: 10.4.18-MariaDB
-- PHP Version: 8.0.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_simantra`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_config`
--

CREATE TABLE `tbl_config` (
  `no` int(11) NOT NULL,
  `thn_dokumen` int(11) NOT NULL,
  `nm_group` varchar(25) NOT NULL,
  `status` int(11) NOT NULL,
  `CreateDate` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_config`
--

INSERT INTO `tbl_config` (`no`, `thn_dokumen`, `nm_group`, `status`, `CreateDate`) VALUES
(1, 2024, 'PTA', 1, '2024-10-26 16:16:41');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_dok_keluar`
--

CREATE TABLE `tbl_dok_keluar` (
  `id_dokumen` int(11) NOT NULL,
  `no_dokumen` varchar(50) NOT NULL,
  `no_dokumen2` varchar(50) NOT NULL,
  `jns_dokumen` int(11) NOT NULL,
  `dari` varchar(25) NOT NULL,
  `unit_tujuan` text NOT NULL,
  `perihal` text NOT NULL,
  `pembuat` varchar(10) NOT NULL,
  `lampiran` int(11) NOT NULL,
  `kategori` char(25) NOT NULL,
  `sts_dokumen` varchar(50) NOT NULL,
  `catatan` text DEFAULT NULL,
  `path_folder` varchar(50) DEFAULT NULL,
  `file_dokumen` text DEFAULT NULL,
  `createDate` datetime NOT NULL,
  `kd_unit` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_dok_masuk`
--

CREATE TABLE `tbl_dok_masuk` (
  `id_dokumen` int(11) NOT NULL,
  `no_dokumen` varchar(50) NOT NULL,
  `jns_dokumen` int(11) NOT NULL,
  `dari` varchar(50) NOT NULL,
  `perihal` text NOT NULL,
  `lampiran` int(11) NOT NULL,
  `kategori` int(11) NOT NULL,
  `tgl_dokumen` date NOT NULL,
  `tgl_disposisi` date DEFAULT NULL,
  `disposisi` text DEFAULT NULL,
  `catatan` text DEFAULT NULL,
  `path_folder` varchar(50) DEFAULT NULL,
  `file_dokumen` text DEFAULT NULL,
  `tgl_diterima` date NOT NULL,
  `createDate` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_jabatan`
--

CREATE TABLE `tbl_jabatan` (
  `id_jabatan` int(11) NOT NULL,
  `nm_jabatan` varchar(100) NOT NULL,
  `createDate` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_jabatan`
--

INSERT INTO `tbl_jabatan` (`id_jabatan`, `nm_jabatan`, `createDate`) VALUES
(1, 'KEPANITERAAN', '2021-01-28 18:30:08'),
(2, 'KESEKRETARIATAN', '2021-01-28 18:31:04');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_jns_dokumen`
--

CREATE TABLE `tbl_jns_dokumen` (
  `id_jns_dokumen` int(11) NOT NULL,
  `jns_dokumen` varchar(100) NOT NULL,
  `keterangan` varchar(100) NOT NULL,
  `counter_dokumen` int(11) NOT NULL,
  `createDate` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_jns_dokumen`
--

INSERT INTO `tbl_jns_dokumen` (`id_jns_dokumen`, `jns_dokumen`, `keterangan`, `counter_dokumen`, `createDate`) VALUES
(1, 'Penting', 'Bersifat Penting', 6, '2021-01-27 00:00:00'),
(2, 'Biasa', 'Bersifat Biasa', 4, '2021-01-27 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_kategori`
--

CREATE TABLE `tbl_kategori` (
  `id_kategori` int(11) NOT NULL,
  `jns_kategori` varchar(100) NOT NULL,
  `keterangan` varchar(100) NOT NULL,
  `createDate` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_kategori`
--

INSERT INTO `tbl_kategori` (`id_kategori`, `jns_kategori`, `keterangan`, `createDate`) VALUES
(1, 'Undangan', 'Undangan', '2024-10-19 08:09:26'),
(2, 'SK', 'Surat Keputusan', '2024-10-19 08:09:39'),
(3, 'Surat Tugas', 'Surat Tugas', '2024-10-19 08:09:59');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_pegawai`
--

CREATE TABLE `tbl_pegawai` (
  `id_pegawai` int(11) NOT NULL,
  `nm_pegawai` varchar(100) NOT NULL,
  `id_jabatan` int(11) NOT NULL,
  `createDate` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_pegawai`
--

INSERT INTO `tbl_pegawai` (`id_pegawai`, `nm_pegawai`, `id_jabatan`, `createDate`) VALUES
(1, 'Andra', 1, '2021-01-29 09:25:39'),
(2, 'Dicky', 1, '2021-01-29 09:25:52'),
(3, 'Yayan', 2, '2021-01-29 09:26:06');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_unit`
--

CREATE TABLE `tbl_unit` (
  `no` int(11) NOT NULL,
  `kd_unit` varchar(10) NOT NULL,
  `nm_unit` varchar(100) NOT NULL,
  `bagian` varchar(100) NOT NULL,
  `createDate` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_unit`
--

INSERT INTO `tbl_unit` (`no`, `kd_unit`, `nm_unit`, `bagian`, `createDate`) VALUES
(1, 'PH', 'Panmud Hukum', 'kepaniteraan', '2024-10-29 02:58:34'),
(2, 'PB', 'Panmud Banding', 'kepaniteraan', '2024-10-29 02:58:34'),
(3, 'UM', 'Umum', 'kesekretariatan', '2024-10-29 03:18:04'),
(4, 'KPG', 'Kepegawaian dan TI', 'kesekretariatan', '2024-10-29 05:18:55'),
(5, 'KEU', 'Keuangan dan Pelaporan', 'kesekretariatan', '2024-10-29 05:18:55'),
(6, 'PPG', 'Perencanaan dan Program', 'kesekretariatan', '2024-10-29 05:21:03');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user`
--

CREATE TABLE `tbl_user` (
  `id` int(5) NOT NULL,
  `username` varchar(25) NOT NULL,
  `nm_user` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `lv_user` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_user`
--

INSERT INTO `tbl_user` (`id`, `username`, `nm_user`, `password`, `lv_user`) VALUES
(1, 'admin', 'Administrator', '21232f297a57a5a743894a0e4a801fc3', 'admin'),
(2, 'kepaniteraan', 'Panitera Muda Hukum', 'cbebffc96a46a3e1da96033eff31ea03', 'user'),
(3, 'kepaniteraan', 'Panitera Muda Banding', 'e7ed0c3114ac0358b78b9c7699f2ff4e', 'user'),
(4, 'kesekretariatan', 'Rencana Program dan Anggaran', '4146c5b4cb5450f582601fe5a65e2732', 'user'),
(5, 'kesekretariatan', 'Kepegawaian dan Teknologi Informasi', 'd45de20a488481327b5c7f2600b861cf', 'user'),
(6, 'kesekretariatan', 'Tata Usaha dan Rumah Tangga', '82849c85acf1f4e6e4eec748f0aeddf4', 'user'),
(7, 'kesekretariatan', 'Keuangan dan Pelaporan', 'a4151d4b2856ec63368a7c784b1f0a6e', 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_config`
--
ALTER TABLE `tbl_config`
  ADD PRIMARY KEY (`no`);

--
-- Indexes for table `tbl_dok_keluar`
--
ALTER TABLE `tbl_dok_keluar`
  ADD PRIMARY KEY (`id_dokumen`);

--
-- Indexes for table `tbl_dok_masuk`
--
ALTER TABLE `tbl_dok_masuk`
  ADD PRIMARY KEY (`id_dokumen`);

--
-- Indexes for table `tbl_jabatan`
--
ALTER TABLE `tbl_jabatan`
  ADD PRIMARY KEY (`id_jabatan`);

--
-- Indexes for table `tbl_jns_dokumen`
--
ALTER TABLE `tbl_jns_dokumen`
  ADD PRIMARY KEY (`id_jns_dokumen`);

--
-- Indexes for table `tbl_kategori`
--
ALTER TABLE `tbl_kategori`
  ADD PRIMARY KEY (`id_kategori`);

--
-- Indexes for table `tbl_pegawai`
--
ALTER TABLE `tbl_pegawai`
  ADD PRIMARY KEY (`id_pegawai`);

--
-- Indexes for table `tbl_unit`
--
ALTER TABLE `tbl_unit`
  ADD PRIMARY KEY (`no`);

--
-- Indexes for table `tbl_user`
--
ALTER TABLE `tbl_user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_config`
--
ALTER TABLE `tbl_config`
  MODIFY `no` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_dok_keluar`
--
ALTER TABLE `tbl_dok_keluar`
  MODIFY `id_dokumen` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `tbl_dok_masuk`
--
ALTER TABLE `tbl_dok_masuk`
  MODIFY `id_dokumen` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_jabatan`
--
ALTER TABLE `tbl_jabatan`
  MODIFY `id_jabatan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tbl_jns_dokumen`
--
ALTER TABLE `tbl_jns_dokumen`
  MODIFY `id_jns_dokumen` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbl_kategori`
--
ALTER TABLE `tbl_kategori`
  MODIFY `id_kategori` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `tbl_pegawai`
--
ALTER TABLE `tbl_pegawai`
  MODIFY `id_pegawai` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `tbl_unit`
--
ALTER TABLE `tbl_unit`
  MODIFY `no` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tbl_user`
--
ALTER TABLE `tbl_user`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
