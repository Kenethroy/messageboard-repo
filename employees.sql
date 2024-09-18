-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: db
-- Generation Time: Sep 18, 2024 at 05:49 AM
-- Server version: 8.0.39
-- PHP Version: 8.1.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `employees`
--

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE `contacts` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(50) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `contacts`
--

INSERT INTO `contacts` (`id`, `name`, `email`, `phone`, `created`, `modified`) VALUES
(1, 'ken', 'roy@gmail.com', '0986666', '2024-09-12 02:34:21', '2024-09-12 02:36:18'),
(8, 'gf', 'roy@gmail.com', '0986666', '2024-09-12 03:05:57', '2024-09-12 03:05:57'),
(12, 'keneth', 'roy@gmail.com', '0986666', '2024-09-12 03:41:17', '2024-09-12 03:41:17'),
(14, 'zz', 'roy@gmail.com', '0986666', '2024-09-12 03:41:28', '2024-09-12 08:04:09'),
(15, 'ee', 'roy@gmail.com', '0986666', '2024-09-12 03:41:43', '2024-09-12 03:41:43'),
(16, 'keneth', 'roy@gmail.com', '0986666', '2024-09-12 03:41:56', '2024-09-12 03:41:56');

-- --------------------------------------------------------

--
-- Table structure for table `conversations`
--

CREATE TABLE `conversations` (
  `id` int NOT NULL,
  `sender_id` int NOT NULL,
  `receiver_id` int NOT NULL,
  `created` datetime DEFAULT CURRENT_TIMESTAMP,
  `modified` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_ip` varchar(15) DEFAULT '0.0.0.0',
  `modified_ip` varchar(15) DEFAULT '0.0.0.0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `conversations`
--

INSERT INTO `conversations` (`id`, `sender_id`, `receiver_id`, `created`, `modified`, `created_ip`, `modified_ip`) VALUES
(4, 5, 1, '2024-09-16 02:39:52', '2024-09-16 02:39:52', '0.0.0.0', '0.0.0.0'),
(14, 3, 5, '2024-09-17 09:25:18', '2024-09-17 09:25:18', '0.0.0.0', '0.0.0.0'),
(15, 3, 1, '2024-09-17 09:49:14', '2024-09-17 09:49:14', '0.0.0.0', '0.0.0.0'),
(17, 2, 4, '2024-09-18 01:12:22', '2024-09-18 01:12:22', '0.0.0.0', '0.0.0.0'),
(18, 2, 5, '2024-09-18 03:07:00', '2024-09-18 03:07:00', '0.0.0.0', '0.0.0.0'),
(19, 3, 2, '2024-09-18 05:03:43', '2024-09-18 05:03:43', '0.0.0.0', '0.0.0.0'),
(21, 3, 4, '2024-09-18 05:38:46', '2024-09-18 05:38:46', '0.0.0.0', '0.0.0.0');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int NOT NULL,
  `conversation_id` int NOT NULL,
  `user_id` int NOT NULL,
  `message` text NOT NULL,
  `created` datetime DEFAULT CURRENT_TIMESTAMP,
  `modified` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_ip` varchar(15) DEFAULT '0.0.0.0',
  `modified_ip` varchar(15) DEFAULT '0.0.0.0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `conversation_id`, `user_id`, `message`, `created`, `modified`, `created_ip`, `modified_ip`) VALUES
(9, 4, 5, 'hi 1', '2024-09-16 02:40:08', '2024-09-16 02:40:08', '0.0.0.0', '0.0.0.0'),
(10, 4, 1, 'hi 5', '2024-09-16 02:40:22', '2024-09-16 02:40:22', '0.0.0.0', '0.0.0.0'),
(93, 15, 3, 'hhhh', '2024-09-17 09:49:14', '2024-09-17 09:49:14', '0.0.0.0', '0.0.0.0'),
(95, 17, 2, 'hhhhhhhh', '2024-09-18 01:12:22', '2024-09-18 01:12:22', '0.0.0.0', '0.0.0.0'),
(96, 17, 4, 'hello', '2024-09-18 01:15:59', '2024-09-18 01:15:59', '0.0.0.0', '0.0.0.0'),
(100, 17, 2, 'This stops any ongoing animations (like fadeOut()) on the #error-message element. If there is an animation currently in progress, this ensures that it stops immediately, allowing the new error message to be displayed.', '2024-09-18 01:24:46', '2024-09-18 01:24:46', '0.0.0.0', '0.0.0.0'),
(137, 17, 2, 'hh', '2024-09-18 01:52:35', '2024-09-18 01:52:35', '0.0.0.0', '0.0.0.0'),
(138, 17, 2, 'hhh', '2024-09-18 01:52:37', '2024-09-18 01:52:37', '0.0.0.0', '0.0.0.0'),
(139, 17, 2, 'hhh', '2024-09-18 01:52:38', '2024-09-18 01:52:38', '0.0.0.0', '0.0.0.0'),
(156, 17, 2, 'hhh', '2024-09-18 02:15:50', '2024-09-18 02:15:50', '0.0.0.0', '0.0.0.0'),
(176, 17, 2, 'hii', '2024-09-18 02:34:26', '2024-09-18 02:34:26', '0.0.0.0', '0.0.0.0'),
(212, 18, 2, 'hhh', '2024-09-18 03:07:00', '2024-09-18 03:07:00', '0.0.0.0', '0.0.0.0'),
(213, 19, 3, 'hii', '2024-09-18 05:03:43', '2024-09-18 05:03:43', '0.0.0.0', '0.0.0.0'),
(215, 21, 3, 'hiii', '2024-09-18 05:38:46', '2024-09-18 05:38:46', '0.0.0.0', '0.0.0.0');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `name` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `gender` enum('male','female','') DEFAULT '',
  `hubby` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `last_login_time` datetime DEFAULT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_ip` varchar(45) DEFAULT '0.0.0.0',
  `modified_ip` varchar(45) DEFAULT '0.0.0.0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `profile_picture`, `birthdate`, `gender`, `hubby`, `last_login_time`, `created`, `modified`, `created_ip`, `modified_ip`) VALUES
(1, 'keneth', 'fdc.kkkennethroy@gmail.com', '71154fb0a1682c491312cb5d93791a732234ce6f', NULL, NULL, '', NULL, NULL, '2024-09-13 05:44:44', '2024-09-13 05:44:44', '0.0.0.0', '0.0.0.0'),
(2, 'kenethroy', 'fdc.kkennethroy@gmail.com', '71154fb0a1682c491312cb5d93791a732234ce6f', 'kenethroy_20240918_005907.jpeg', '2000-11-30', 'male', 'love love love me', '2024-09-18 00:57:42', '2024-09-13 05:46:11', '2024-09-18 00:59:07', '0.0.0.0', '0.0.0.0'),
(3, 'johnnysonny', 'fdc.kennethroy@gmail.com', '71154fb0a1682c491312cb5d93791a732234ce6f', 'johnnysonny_20240917_094737.jpeg', '2001-09-17', 'male', 'love love love me', '2024-09-18 05:39:39', '2024-09-13 06:22:24', '2024-09-18 05:39:39', '0.0.0.0', '0.0.0.0'),
(4, 'keneth', 'fdc.kennnethroy@gmail.com', '71154fb0a1682c491312cb5d93791a732234ce6f', 'keneth.jpeg', '2001-09-19', 'male', 'love love love me me', '2024-09-17 01:07:47', '2024-09-16 01:23:58', '2024-09-17 03:15:37', '0.0.0.0', '0.0.0.0'),
(5, 'jean', 'fdc.kennnnethroy@gmail.com', '24a13045d005b520461694f07098702b97fd737b', NULL, NULL, '', NULL, '2024-09-16 01:30:24', '2024-09-16 01:30:24', '2024-09-16 01:30:24', '0.0.0.0', '0.0.0.0');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `conversations`
--
ALTER TABLE `conversations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sender_id` (`sender_id`),
  ADD KEY `receiver_id` (`receiver_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `conversation_id` (`conversation_id`),
  ADD KEY `user_id` (`user_id`);

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
-- AUTO_INCREMENT for table `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `conversations`
--
ALTER TABLE `conversations`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=216;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `conversations`
--
ALTER TABLE `conversations`
  ADD CONSTRAINT `conversations_ibfk_1` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `conversations_ibfk_2` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`conversation_id`) REFERENCES `conversations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
