-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 09, 2025 at 01:55 AM
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
-- Database: `parks`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `first` varchar(50) NOT NULL,
  `last` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `is_admin` enum('Yes','No') DEFAULT 'No',
  `dateAdded` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first`, `last`, `email`, `password`, `is_admin`, `dateAdded`) VALUES
(1, 'Alice', 'Smith', 'alice.smith@example.com', '098f6bcd4621d373cade4e832627b4f6', 'No', '2025-03-09 00:54:37'),
(2, 'Bob', 'Johnson', 'bob.johnson@example.com', '098f6bcd4621d373cade4e832627b4f6', 'No', '2025-03-09 00:54:37'),
(3, 'Carol', 'Brown', 'carol.brown@example.com', '098f6bcd4621d373cade4e832627b4f6', 'No', '2025-03-09 00:54:37'),
(4, 'Dave', 'Wilson', 'dave.wilson@example.com', '098f6bcd4621d373cade4e832627b4f6', 'No', '2025-03-09 00:54:37'),
(5, 'Eve', 'Taylor', 'eve.taylor@example.com', '098f6bcd4621d373cade4e832627b4f6', 'No', '2025-03-09 00:54:37'),
(6, 'Frank', 'Lee', 'frank.lee@example.com', '098f6bcd4621d373cade4e832627b4f6', 'No', '2025-03-09 00:54:37'),
(7, 'Grace', 'Harris', 'grace.harris@example.com', '098f6bcd4621d373cade4e832627b4f6', 'No', '2025-03-09 00:54:37'),
(8, 'Henry', 'Clark', 'henry.clark@example.com', '098f6bcd4621d373cade4e832627b4f6', 'No', '2025-03-09 00:54:37'),
(9, 'Admin', '', 'admin@example.com', '098f6bcd4621d373cade4e832627b4f6', 'Yes', '2025-03-09 00:54:37');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
