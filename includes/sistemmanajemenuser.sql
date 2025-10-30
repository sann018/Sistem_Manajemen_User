-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 30, 2025 at 04:24 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sistemmanajemenuser`
--

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `kode_produk` varchar(50) NOT NULL,
  `nama_produk` varchar(255) NOT NULL,
  `kategori` varchar(100) DEFAULT NULL,
  `harga` decimal(15,2) NOT NULL,
  `stok` int(11) DEFAULT 0,
  `deskripsi` text DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `kode_produk`, `nama_produk`, `kategori`, `harga`, `stok`, `deskripsi`, `created_by`, `created_at`, `updated_at`) VALUES
(7, '02', 'Silikon Realmi', 'Handphone', 13000.00, 15, 'adadada', NULL, '2025-10-30 15:09:33', '2025-10-30 15:09:33');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `gender` varchar(20) DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `fullname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama_lengkap` varchar(255) NOT NULL,
  `role` enum('Admin Gudang') DEFAULT 'Admin Gudang',
  `status` enum('INACTIVE','ACTIVE') DEFAULT 'INACTIVE',
  `activation_token` varchar(255) DEFAULT NULL,
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_token_expiry` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `activation_code` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `gender`, `phone`, `fullname`, `email`, `password`, `nama_lengkap`, `role`, `status`, `activation_token`, `reset_token`, `reset_token_expiry`, `created_at`, `updated_at`, `activation_code`) VALUES
(3, 'sann', NULL, NULL, 'Ihsan Alhusna', 'ihsanalhusna4@gmail.com', '$2y$10$ccGjHJPtj.YR28.fpLoQreaObf2AH2I8dT30wVrUJLSUcJEtHstkq', '', 'Admin Gudang', 'ACTIVE', '079f67d58a90653d5ee7acfc0c908ee80b9e851a991cfc0d94bde678c6a98d7d', NULL, NULL, '2025-10-30 08:38:17', '2025-10-30 08:38:24', NULL),
(7, 'pooo', 'Laki-laki', '085750777394', 'zoror', 'ihsanalhusnaa@gmail.com', '$2y$10$BqTAbSbTj/KJ3EGVGW9louY4d2yNXgPXShlnzKeMenZcKxyJC5giK', '', '', 'ACTIVE', 'd342eda9999b8460d4c5c2b0cfeee2efdd667d2885e9291635545072949196d4', NULL, NULL, '2025-10-30 14:36:26', '2025-10-30 15:10:04', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_produk` (`kode_produk`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
