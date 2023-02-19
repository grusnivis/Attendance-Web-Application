-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 19, 2023 at 08:20 AM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `teamteach`
--

-- --------------------------------------------------------

--
-- Table structure for table `teamteach`
--

CREATE TABLE `teamteach` (
  `Teacher` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Partner` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Course` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `teamteach`
--

INSERT INTO `teamteach` (`Teacher`, `Partner`, `Course`) VALUES
('ANTONIETTE CAÑETE', 'ELLINE FABIAN', 'CPE 3101L-G1'),
('ANTONIETTE CAÑETE', 'CHRISTOPHER JAMES LABRADOR', 'CPE 3105-G5');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
