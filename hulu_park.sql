-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: May 24, 2025 at 02:26 PM
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
-- Database: `hulu_park`
--

-- --------------------------------------------------------

--
-- Table structure for table `durations`
--

CREATE TABLE `durations` (
  `dur_id` int(11) NOT NULL,
  `dur` time NOT NULL,
  `text` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `durations`
--

INSERT INTO `durations` (`dur_id`, `dur`, `text`) VALUES
(1, '00:30:00', '1/2 hour'),
(2, '01:00:00', '1 hour'),
(3, '01:30:00', '1 1/2 hour'),
(4, '02:00:00', '2 hours');

-- --------------------------------------------------------

--
-- Table structure for table `sectors`
--

CREATE TABLE `sectors` (
  `sector_id` int(11) NOT NULL,
  `sector_code` char(10) NOT NULL,
  `half_hour_rate` decimal(5,2) NOT NULL,
  `location_point_a_lat` decimal(12,6) NOT NULL,
  `location_point_a_lon` decimal(12,6) NOT NULL,
  `location_point_b_lat` decimal(12,6) NOT NULL,
  `location_point_b_lon` decimal(12,6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sectors`
--

INSERT INTO `sectors` (`sector_id`, `sector_code`, `half_hour_rate`, `location_point_a_lat`, `location_point_a_lon`, `location_point_b_lat`, `location_point_b_lon`) VALUES
(1, '12345', 0.85, 21.302179, -157.862785, 21.303405, -157.861255);

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `session_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `sector_id` int(11) NOT NULL,
  `start_time` datetime NOT NULL,
  `end_time` datetime NOT NULL,
  `duration` time NOT NULL,
  `car_plate` char(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`session_id`, `user_id`, `sector_id`, `start_time`, `end_time`, `duration`, `car_plate`) VALUES
(18, 19, 1, '2025-05-15 13:39:08', '2025-05-15 19:39:08', '06:00:00', 's'),
(19, 19, 1, '2025-05-15 15:16:21', '2025-05-15 15:46:21', '00:30:00', 'ass'),
(20, 19, 1, '2025-05-15 15:19:51', '2025-05-15 15:49:51', '00:30:00', 'as'),
(21, 19, 1, '2025-05-15 20:57:52', '2025-05-15 21:27:52', '00:30:00', 'A112-123'),
(22, 2, 1, '2025-05-15 21:04:01', '2025-05-15 21:34:01', '00:30:00', 'asasd'),
(23, 19, 1, '2025-05-15 21:29:50', '2025-05-15 22:29:50', '01:00:00', 'AOK1-12K'),
(24, 19, 1, '2025-05-15 21:31:55', '2025-05-15 23:01:55', '01:30:00', 'aaaa'),
(25, 19, 1, '2025-05-15 21:43:10', '2025-05-15 23:43:10', '02:00:00', 'ASKJ-1239'),
(26, 2, 1, '2025-05-15 21:55:26', '2025-05-16 00:55:26', '03:00:00', 'ASDJ-1288'),
(27, 19, 1, '2025-05-19 15:47:32', '2025-05-19 16:17:32', '00:30:00', 'qsdsad'),
(28, 19, 1, '2025-05-19 16:25:33', '2025-05-19 18:25:33', '02:00:00', 'asdsdsd'),
(29, 19, 1, '2025-05-19 20:45:40', '2025-05-19 22:45:40', '02:00:00', 'ADFEA'),
(30, 19, 1, '2025-05-22 13:32:32', '2025-05-22 14:02:32', '00:30:00', 'ASDEES'),
(31, 19, 1, '2025-05-24 12:11:23', '2025-05-24 13:11:23', '01:00:00', 'awesdf');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `admin` tinyint(1) DEFAULT NULL,
  `wallet` decimal(10,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `email`, `admin`, `wallet`) VALUES
(1, 'asdas', 0, 0.00),
(2, 'nouk', 0, 24.05),
(3, 'asd', 0, 0.00),
(4, 'asd22222222', 0, 0.00),
(5, 'asd1111111', 0, 0.00),
(6, 'asdasd23333333333333333', 0, 0.00),
(7, 'nouk1212121212', 0, 0.00),
(8, 'tes', 0, 0.00),
(9, 'asdqqqq', 0, 0.00),
(10, 'asd111', 0, 0.00),
(11, 'asdasddas', 0, 0.00),
(12, 'assss', 0, 0.00),
(13, 'nouk3', 0, 0.00),
(14, 'asdddddddddddd', 0, 0.00),
(15, 'loge', 0, 0.00),
(16, 'ok', 0, 0.00),
(17, 'oooo', NULL, 13.00),
(18, 'qqqqq', NULL, 14.23),
(19, 'q', 0, 1099.20),
(20, 'qqqq', 0, 0.00);

-- --------------------------------------------------------

--
-- Table structure for table `weekly_hours`
--

CREATE TABLE `weekly_hours` (
  `week_day` int(11) NOT NULL,
  `label` text NOT NULL,
  `start_hour` time NOT NULL,
  `end_hour` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `weekly_hours`
--

INSERT INTO `weekly_hours` (`week_day`, `label`, `start_hour`, `end_hour`) VALUES
(1, 'Monday', '08:00:00', '21:00:00'),
(2, 'Tuesday', '08:00:00', '21:00:00'),
(3, 'Wednesday', '08:00:00', '21:00:00'),
(4, 'Thursday', '08:00:00', '21:00:00'),
(5, 'Friday', '08:00:00', '21:00:00'),
(6, 'Saturday', '08:00:00', '16:00:00'),
(7, 'Sunday', '08:00:00', '16:00:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `durations`
--
ALTER TABLE `durations`
  ADD PRIMARY KEY (`dur_id`);

--
-- Indexes for table `sectors`
--
ALTER TABLE `sectors`
  ADD PRIMARY KEY (`sector_id`),
  ADD UNIQUE KEY `unique` (`sector_code`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`session_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `sector_id` (`sector_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `weekly_hours`
--
ALTER TABLE `weekly_hours`
  ADD UNIQUE KEY `week_day` (`week_day`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `durations`
--
ALTER TABLE `durations`
  MODIFY `dur_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `sectors`
--
ALTER TABLE `sectors`
  MODIFY `sector_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `sessions`
--
ALTER TABLE `sessions`
  MODIFY `session_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `sessions`
--
ALTER TABLE `sessions`
  ADD CONSTRAINT `sector_id` FOREIGN KEY (`sector_id`) REFERENCES `sectors` (`sector_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
