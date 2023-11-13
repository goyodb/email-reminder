-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Nov 13, 2023 at 11:33 PM
-- Server version: 8.0.35
-- PHP Version: 8.0.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `email_reminder`
--

-- --------------------------------------------------------

--
-- Table structure for table `emails`
--

DROP TABLE IF EXISTS `emails`;
CREATE TABLE IF NOT EXISTS `emails` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `frequency` int NOT NULL,
  `template` text NOT NULL,
  `next_send_time` datetime NOT NULL,
  `status` enum('pending','completed') NOT NULL DEFAULT 'pending',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `emails`
--

INSERT INTO `emails` (`id`, `email`, `frequency`, `template`, `next_send_time`, `status`) VALUES
(1, 'email@email.com', 4, 'Hello, I am writing to you because I need to report a client of yours.\n\nThe website is \n\nI need information on how to proceed against the hosting and domain service so that I can ask you to suspend that information.\n\nThank you so much', '2023-11-11 10:20:00', 'pending'),
(2, 'email@email.com', 2, 'Hello, I am writing to you because I need to report a client of yours.\r\n\r\nThe website is \r\n\r\nI need information on how to proceed against the hosting and domain service so that I can ask you to suspend that information.\r\n\r\nThank you so much', '2023-11-11 10:20:00', 'completed');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
