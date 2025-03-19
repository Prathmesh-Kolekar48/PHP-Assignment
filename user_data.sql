-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 19, 2025 at 09:04 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `user_data`
--

-- --------------------------------------------------------

--
-- Table structure for table `history`
--

CREATE TABLE `history` (
  `username` varchar(20) NOT NULL,
  `hashtag` varchar(20) NOT NULL,
  `search_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `history`
--

INSERT INTO `history` (`username`, `hashtag`, `search_date`) VALUES
('pk', 'hello', '2025-03-12'),
('pk', 'nature', '2025-03-12'),
('pk', 'nature', '2025-03-12'),
('pk', 'selfie', '2025-03-12'),
('pk', 'selfie', '2025-03-12'),
('pk', 'selfie', '2025-03-12'),
('pk', 'selfie', '2025-03-12'),
('pk', 'selfie', '2025-03-12'),
('pk', 'selfie', '2025-03-12'),
('pk', 'nature', '2025-03-12'),
('pk', 'nature', '2025-03-12'),
('pk', 'nature', '2025-03-12'),
('pk', 'nature', '2025-03-12'),
('pk', 'nature', '2025-03-12'),
('pk', 'nature', '2025-03-12'),
('pk', 'selfie', '2025-03-12'),
('pk', 'nature', '2025-03-12'),
('pk', 'nature', '2025-03-12'),
('pk', 'nature', '2025-03-12'),
('pk', 'nature', '2025-03-12'),
('pk', 'nature', '2025-03-12'),
('pk', 'nature', '2025-03-12'),
('pk', 'nature', '2025-03-12'),
('pk', 'nature', '2025-03-12'),
('pk', 'nature', '2025-03-12'),
('pk', 'nature', '2025-03-12'),
('pk', 'nature', '2025-03-12'),
('pk', 'nature', '2025-03-12'),
('pk', 'selfie', '2025-03-16'),
('pk', 'selfie', '2025-03-16'),
('pk', 'selfie', '2025-03-16'),
('pk', 'aj_stark', '2025-03-16'),
('pk', 'UnforgettableJourney', '2025-03-16'),
('pk', 'nature', '2025-03-17'),
('pk', 'nature', '2025-03-17'),
('pk', 'sunset', '2025-03-17'),
('pk', 'sunrise', '2025-03-17'),
('pk', 'sunrise', '2025-03-17'),
('pk', 'sunrise', '2025-03-17'),
('pk', 'sunrise', '2025-03-17'),
('pk', 'hello', '2025-03-18'),
('pk', 'selfie', '2025-03-18'),
('pk', 'selfie', '2025-03-18'),
('pk', 'selfie', '2025-03-18'),
('pk', 'selfie', '2025-03-18'),
('pk', 'nature', '2025-03-18'),
('pk', 'nature', '2025-03-18'),
('pk', 'hello', '2025-03-19'),
('qwerty', 'ironman', '2025-03-19'),
('qwerty', 'nature', '2025-03-19'),
('qwerty', 'nature', '2025-03-19');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `reset_code` varchar(6) NOT NULL,
  `expiry_time` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `password_resets`
--

INSERT INTO `password_resets` (`id`, `email`, `reset_code`, `expiry_time`, `created_at`) VALUES
(1, 'kolekaraniket70@gmail.com', 'df3483', '2025-03-19 08:50:58', '2025-03-19 07:35:59'),
(2, 'kolekarp04082003@gmail.com', '110e13', '2025-03-19 08:52:21', '2025-03-19 07:37:21'),
(3, 'kolekarp04082003@gmail.com', '1ea6f7', '2025-03-19 08:58:35', '2025-03-19 07:43:35'),
(4, 'kolekarp04082003@gmail.com', '2425dc', '2025-03-19 13:36:02', '2025-03-19 07:51:02');

-- --------------------------------------------------------

--
-- Table structure for table `user_details`
--

CREATE TABLE `user_details` (
  `username` varchar(20) NOT NULL,
  `email` varchar(40) NOT NULL,
  `password` varchar(255) NOT NULL,
  `verified` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_details`
--

INSERT INTO `user_details` (`username`, `email`, `password`, `verified`) VALUES
('laflj', 'kolekarp04082003@gmail.com', '$2y$10$Kv44pRN/3sTXNIZPvdrfruAabbRCjSTs9G70838234JZBZRKiwGnK', 0),
('pk', 'kolekarp04082003@gmail.com', '$2y$10$Kv44pRN/3sTXNIZPvdrfruAabbRCjSTs9G70838234JZBZRKiwGnK', 1),
('qwerty', 'kolekaraniket70@gmail.com', '$2y$10$pjxLmCpNovcthzLefXnX4uDlbXASexAI4EcTncLfQMF3uhTqMCzN2', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_details`
--
ALTER TABLE `user_details`
  ADD PRIMARY KEY (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
