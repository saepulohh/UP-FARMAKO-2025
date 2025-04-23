-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 21, 2025 at 09:08 PM
-- Server version: 8.0.30
-- PHP Version: 8.3.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tiket`
--

-- --------------------------------------------------------

--
-- Table structure for table `kereta`
--

CREATE TABLE `kereta` (
  `id` int NOT NULL,
  `nama_kereta` varchar(255) NOT NULL,
  `kelas` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `kereta`
--

INSERT INTO `kereta` (`id`, `nama_kereta`, `kelas`) VALUES
(1, 'Taksaka', 'eksekutif');

-- --------------------------------------------------------

--
-- Table structure for table `pemesanan`
--

CREATE TABLE `pemesanan` (
  `id` int NOT NULL,
  `nama_penumpang` varchar(100) NOT NULL,
  `kereta` varchar(100) NOT NULL,
  `asal` varchar(100) NOT NULL,
  `tujuan` varchar(100) NOT NULL,
  `tanggal_berangkat` date NOT NULL,
  `kelas` enum('Ekonomi','Bisnis','Eksekutif') NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `user_id` int DEFAULT NULL,
  `status` varchar(20) DEFAULT 'belum bayar',
  `metode_pembayaran` varchar(50) DEFAULT NULL,
  `harga` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `pemesanan`
--

INSERT INTO `pemesanan` (`id`, `nama_penumpang`, `kereta`, `asal`, `tujuan`, `tanggal_berangkat`, `kelas`, `created_at`, `user_id`, `status`, `metode_pembayaran`, `harga`) VALUES
(2, 'epul', 'jayakarta', 'bogor', 'semarang', '2025-04-21', 'Eksekutif', '2025-04-21 14:49:24', 4, 'sudah bayar', NULL, 400000),
(3, 'dalvin', 'Taksaka', 'Bogor', 'Surabaya', '2025-04-21', 'Eksekutif', '2025-04-21 16:03:12', 7, 'sudah bayar', 'QRIS', 0),
(4, 'dalvin', 'Taksaka', 'Bogor', 'Surabaya', '2025-04-21', 'Eksekutif', '2025-04-21 16:10:37', 7, 'sudah bayar', NULL, 400),
(5, 'epul', 'Taksaka', 'Bogor', 'Surabaya', '2025-04-05', 'Eksekutif', '2025-04-21 16:29:00', 4, 'sudah bayar', 'Transfer Bank', 400000);

-- --------------------------------------------------------

--
-- Table structure for table `rute`
--

CREATE TABLE `rute` (
  `id` int NOT NULL,
  `asal` varchar(255) NOT NULL,
  `tujuan` varchar(255) NOT NULL,
  `harga` decimal(10,2) NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `rute`
--

INSERT INTO `rute` (`id`, `asal`, `tujuan`, `harga`) VALUES
(1, 'Bogor', 'Surabaya', '500000.00');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `role` enum('admin','petugas','penumpang') NOT NULL DEFAULT 'penumpang'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `role`) VALUES
(1, 'penumpang', 'penupang123', '', 'penumpang'),
(2, 'admin', 'admin123', '', 'admin'),
(3, 'petugas', '$2y$10$Nn68.Jfi7ZXzy.bedb3t5.d4y..IsrE0dkzx6eAhuZDtXjufZpYGy', '', 'penumpang'),
(4, 'epul', '$2y$10$iEHyMipZkLxWd.2z/zFqVu6Q72AAOfIu79wG2zo0ZDcSOwkkG7wmW', '', 'penumpang'),
(5, 'admin1', '$2y$10$KSKBrNmb20Wa6LHPd/RFW.hZGLKij/lL2xLc6/0oXj/65UFImeGhi', '', 'admin'),
(6, 'rasya', '$2y$10$r5DMuxKWmwLMY639n6idHefvMMFtpu/PjRu1vLs8pCS/RscVgxdaS', 'epul@gmail.com', 'penumpang'),
(7, 'dalvin', '$2y$10$3c5fUEi3R78yQFkupONDbe8U8FC6n8y1Sj8E2dq8OidX5utc/M1tu', 'dalvin@gmail.com', 'penumpang'),
(8, 'tibi', '$2y$10$3d1cZrx/HLlo7MslqgGzzuRWt5wg5AzXTqW0sAmOsj1h2TMLAT02u', '', 'penumpang');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `kereta`
--
ALTER TABLE `kereta`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pemesanan`
--
ALTER TABLE `pemesanan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rute`
--
ALTER TABLE `rute`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `kereta`
--
ALTER TABLE `kereta`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `pemesanan`
--
ALTER TABLE `pemesanan`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `rute`
--
ALTER TABLE `rute`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `pemesanan`
--
ALTER TABLE `pemesanan`
  ADD CONSTRAINT `fk_user` FOREIGN KEY (`id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
