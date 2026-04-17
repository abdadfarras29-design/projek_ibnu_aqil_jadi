-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 14, 2026 at 04:29 AM
-- Server version: 8.0.30
-- PHP Version: 8.3.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ibnu_aqil`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `Id` int NOT NULL,
  `Username` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `Password` varchar(100) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`Id`, `Username`, `Password`) VALUES
(1, 'Admin', 'Admin12345#');

-- --------------------------------------------------------

--
-- Table structure for table `berita`
--

CREATE TABLE `berita` (
  `id` int NOT NULL,
  `judul` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `foto` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `kategori` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tanggal` date NOT NULL,
  `deskripsi` text COLLATE utf8mb4_general_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `esktrakulikuler`
--

CREATE TABLE `esktrakulikuler` (
  `id` int NOT NULL,
  `foto` varchar(200) NOT NULL,
  `nama` varchar(200) NOT NULL,
  `deskripsi` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `esktrakulikuler`
--

INSERT INTO `esktrakulikuler` (`id`, `foto`, `nama`, `deskripsi`) VALUES
(8, 'uploads/ekskul/ekskul_1776139788_728.png', 'FUTSAL', 'Ekstrakurikuler futsal di Sekolah Ibnu Aqil merupakan salah satu kegiatan yang paling diminati oleh siswa. Kegiatan ini bertujuan untuk mengembangkan bakat dan minat siswa dalam bidang olahraga.'),
(9, 'uploads/ekskul/ekskul_1776139908_385.png', 'BASKET', 'Ekstrakurikuler basket di Sekolah Ibnu Aqil merupakan wadah bagi siswa yang memiliki minat dan bakat dalam olahraga bola basket.');

-- --------------------------------------------------------

--
-- Table structure for table `fasilitas`
--

CREATE TABLE `fasilitas` (
  `id` int NOT NULL,
  `foto` varchar(200) NOT NULL,
  `nama fasilitas` varchar(200) NOT NULL,
  `deskripsi` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `galery`
--

CREATE TABLE `galery` (
  `id` int NOT NULL,
  `foto` varchar(200) NOT NULL,
  `kategori` varchar(200) DEFAULT NULL,
  `judul` varchar(200) NOT NULL,
  `deskripsi` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `galery`
--

INSERT INTO `galery` (`id`, `foto`, `kategori`, `judul`, `deskripsi`) VALUES
(9, 'uploads/galery/galery_1774928286_641.png', '', 'Minecraft', ''),
(10, 'uploads/galery/galery_1774928312_560.png', '', 'Fc25', ''),
(11, 'uploads/galery/galery_1774928332_308.png', '', 'Free Fire', ''),
(12, 'uploads/galery/galery_1774928352_929.png', 'Game', 'Roblox', 'roblox adalah game yang sangat seru dimainkan');

-- --------------------------------------------------------

--
-- Table structure for table `jumlah siswa dll`
--

CREATE TABLE `jumlah siswa dll` (
  `id` int NOT NULL,
  `siswa` varchar(200) NOT NULL,
  `guru` varchar(200) NOT NULL,
  `prestasi` varchar(200) NOT NULL,
  `rombongan belajar` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `jumlah siswa dll`
--

INSERT INTO `jumlah siswa dll` (`id`, `siswa`, `guru`, `prestasi`, `rombongan belajar`) VALUES
(1, '67', '67', '67', '67');

-- --------------------------------------------------------

--
-- Table structure for table `nama guru & staff`
--

CREATE TABLE `nama guru & staff` (
  `id` int NOT NULL,
  `foto` varchar(200) NOT NULL,
  `nama guru` varchar(200) NOT NULL,
  `mapel guru` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `nama guru & staff`
--

INSERT INTO `nama guru & staff` (`id`, `foto`, `nama guru`, `mapel guru`) VALUES
(1, 'uploads/guru/guru_1776138096_713.png', 'ADE', 'GURU MATEMATIKA');

-- --------------------------------------------------------

--
-- Table structure for table `pesan`
--

CREATE TABLE `pesan` (
  `id` int NOT NULL,
  `username` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `judul` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `deskripsi` longtext COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `berita`
--
ALTER TABLE `berita`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `esktrakulikuler`
--
ALTER TABLE `esktrakulikuler`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fasilitas`
--
ALTER TABLE `fasilitas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `galery`
--
ALTER TABLE `galery`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jumlah siswa dll`
--
ALTER TABLE `jumlah siswa dll`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `nama guru & staff`
--
ALTER TABLE `nama guru & staff`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pesan`
--
ALTER TABLE `pesan`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `Id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `berita`
--
ALTER TABLE `berita`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `esktrakulikuler`
--
ALTER TABLE `esktrakulikuler`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `fasilitas`
--
ALTER TABLE `fasilitas`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `galery`
--
ALTER TABLE `galery`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `jumlah siswa dll`
--
ALTER TABLE `jumlah siswa dll`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `nama guru & staff`
--
ALTER TABLE `nama guru & staff`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `pesan`
--
ALTER TABLE `pesan`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
