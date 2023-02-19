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
-- Database: `teacher attendance`
--

-- --------------------------------------------------------

--
-- Table structure for table `teacher_attendance`
--

CREATE TABLE `teacher_attendance` (
  `Course` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `RFID` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ID` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Surname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Date` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Time` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `teacher_attendance`
--

INSERT INTO `teacher_attendance` (`Course`, `RFID`, `ID`, `Surname`, `Name`, `Date`, `Status`, `Time`) VALUES
('G5-CPE 3105 (MW - 0130 PM - 0430 PM)', '149412304535', '-', 'LABRADOR', 'CHRISTOPHER JAMES M', '2022-10-17', 'PRESENT', '13:35:6\n'),
('G1-CPE 3101L (T - 0100 PM - 0400 PM)', '4.5601E+11', '', 'CANETE', 'ANTONIETTE M', '2022-10-18', 'PRESENT', '13:13:13\r\n'),
('G2-CPE 3101L (T - 0900 AM - 1200 PM)', '456010377272', '-', 'CAÑETE', 'ANTONIETTE M', '2022-10-18', 'PRESENT', '10:19:27\n'),
('G1-CPE 3105 (TTh - 0900 AM - 1200 PM)', '1.49E+11', '', 'LABRADOR', 'CHRISTOPHER JAMES M', '2022-10-18', 'PRESENT', '09:11:40\r\n'),
('G2-CPE 3105 (TTh - 0100 PM - 0400 PM)', '149412304535', '-', 'LABRADOR', 'CHRISTOPHER JAMES M', '2022-10-18', 'PRESENT', '13:4:49\n'),
('G6-CPE 3101L (W - 0900 AM - 1200 PM)', '456010377272', '-', 'CAÑETE', 'ANTONIETTE M', '2022-10-19', 'PRESENT', '9:11:29\n'),
('G1-CPE 3101L (T - 0100 PM - 0400 PM)', '578301207153', '19101182', 'LIM', 'AMBER BRINETTE U', '2022-11-15', 'PRESENT', '13:24:24\n'),
('G2-CPE 3101L (T - 0900 AM - 1200 PM)', '578301207153', '19101182', 'LIM', 'AMBER BRINETTE U', '2022-11-15', 'PRESENT', '0:40:4\n'),
('G1-CPE 3105 (TTh - 0900 AM - 1200 PM)', '578301207153', '-', 'LABRADOR', 'CHRISTOPHER JAMES M', '2022-11-15', 'PRESENT', '10:6:50\n'),
('G2-CPE 3105 (TTh - 0100 PM - 0400 PM)', '149412304535', '-', 'LABRADOR', 'CHRISTOPHER JAMES M', '2022-11-15', 'PRESENT', '13:19:36\n'),
('G5-CPE 3105 (MW - 0130 PM - 0430 PM)', '149412304535', '-', 'LABRADOR', 'CHRISTOPHER JAMES M', '2022-11-16', 'PRESENT', '15:44:20\n'),
('G1-CPE 3105 (TTh - 0900 AM - 1200 PM)', '149412304535', '-', 'LABRADOR', 'CHRISTOPHER JAMES M', '2022-11-17', 'PRESENT', '10:25:16\n'),
('G2-CPE 3105 (TTh - 0100 PM - 0400 PM)', '149412304535', '-', 'LABRADOR', 'CHRISTOPHER JAMES M', '2022-11-17', 'PRESENT', '13:35:35\n'),
('G5-CPE 3105 (MW - 0130 PM - 0430 PM)', '149412304535', '-', 'LABRADOR', 'CHRISTOPHER JAMES M', '2022-11-21', 'PRESENT', '13:56:53\n'),
('G1-CPE 3105 (TTh - 0900 AM - 1200 PM)', '149412304535', '-', 'LABRADOR', 'CHRISTOPHER JAMES M', '2022-11-22', 'PRESENT', '9:24:3\n'),
('G2-CPE 3105 (TTh - 0100 PM - 0400 PM)', '594128616246', '17100948', 'HIPOLITO', 'JUSTIN STUART R', '2022-11-22', 'PRESENT', '13:10:7\n'),
('G1-CPE 3108 (MW - 0830 AM - 1000 AM)', '594128616246', '17100948', 'HIPOLITO', 'JUSTIN STUART R', '2022-11-26', 'PRESENT', '12:39:59\n'),
('G1-CPE 3108 (MW - 0830 AM - 1000 AM)', '296290754774', '091F908', 'MACAPAGAL', 'ALVIN JOSEPH', '2022-12-10', 'PRESENT', '12:50:43\n'),
('G1-CPE 2101L (TTh - 0730 AM - 1030 AM)', '747347722229', '19102579', 'SIGAYA', 'KATHRYN MARIE P', '2022-12-12', 'PRESENT', '13:45:41\n'),
('G1-CPE 3105 (TTh - 0900 AM - 1200 PM)', '578301207153', '19101182', 'LIM', 'AMBER BRINETTE U', '2022-12-13', 'PRESENT', '11:15:13\n'),
('G3-CPE 2101L (MW - 1030 AM - 0130 PM)', '578301207153', '19101182', 'LIM', 'AMBER BRINETTE U', '2022-12-13', 'PRESENT', '11:12:41\n'),
('G1-CPE 3101L (T - 0100 PM - 0400 PM)', '578301207153', '19101182', 'LIM', 'AMBER BRINETTE U', '2022-12-6', 'PRESENT', '13:10:40\n'),
('G2-CPE 3101L (T - 0900 AM - 1200 PM)', '747347722229', '19102579', 'SIGAYA', 'KATHRYN MARIE P', '2022-12-6', 'PRESENT', '9:14:59\n'),
('G1-CPE 2101L (TTh - 0730 AM - 1030 AM)', '594128616246', '17100948', 'HIPOLITO', 'JUSTIN STUART R', '2022-12-6', 'PRESENT', '7:35:23\n'),
('G6-CPE 3101L (W - 0900 AM - 1200 PM)', '747347722229', '19102579', 'SIGAYA', 'KATHRYN MARIE P', '2022-12-7', 'PRESENT', '9:17:6\n');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
