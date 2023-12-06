-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Dec 06, 2023 at 09:47 PM
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
-- Database: `realtor`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) NOT NULL,
  `user_type` enum('REALTOR','USER') NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone_no` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL,
  `token` varchar(255) DEFAULT NULL,
  `interest` varchar(255) NOT NULL,
  `brokerage` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `addr_lat` varchar(20) NOT NULL DEFAULT '0',
  `addr_lon` varchar(20) NOT NULL DEFAULT '0',
  `cur_address` text NOT NULL,
  `cur_lat` varchar(20) NOT NULL DEFAULT '0',
  `cur_lon` varchar(20) NOT NULL DEFAULT '0',
  `is_verified` enum('Y','N') NOT NULL DEFAULT 'N',
  `is_active` enum('Y','N') NOT NULL DEFAULT 'N',
  `is_delete` enum('Y','N') NOT NULL DEFAULT 'N',
  `cr_date` bigint(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `user_type`, `name`, `email`, `phone_no`, `password`, `token`, `interest`, `brokerage`, `address`, `addr_lat`, `addr_lon`, `cur_address`, `cur_lat`, `cur_lon`, `is_verified`, `is_active`, `is_delete`, `cr_date`) VALUES
(1, 'REALTOR', 'Dinesh', 'dinesh1@yopmail.com', '7063873913', '$2y$10$9QqJcN2jJUD0ljv7TA46s.Gy2Dhsn.mDSvBb3dZZtAf6vYueO4y0q', NULL, '1234567890', 'ABCDEF', 'Contai, West Bengal, India - 721401', '21.916700', '87.514862', '', '0', '0', 'N', 'N', 'N', 1701885890);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
