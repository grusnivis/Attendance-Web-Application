-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 19, 2023 at 08:19 AM
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
-- Database: `antoniette cañete`
--

-- --------------------------------------------------------

--
-- Table structure for table `g1-cpe 3101l (t - 0100 pm - 0400 pm)`
--

CREATE TABLE `g1-cpe 3101l (t - 0100 pm - 0400 pm)` (
  `RFID` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ID` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Surname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Date` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Time` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `g1-cpe 3101l (t - 0100 pm - 0400 pm)`
--

INSERT INTO `g1-cpe 3101l (t - 0100 pm - 0400 pm)` (`RFID`, `ID`, `Surname`, `Name`, `Date`, `Status`, `Time`) VALUES
('', '16100551', 'GUERRERO', 'CLYDE GONZAGA', '2022-10-18', 'ABSENT', ''),
('', '18101371', 'ABELLA', 'VINCENT DOMINGUITO', '2022-11-15', 'PRESENT', ''),
('', '18104198', 'PLAZO', 'KAYLA MILLICENT MALOLOY-ON', '2022-11-15', 'PRESENT', ''),
('1.05494E+12', '19103114', 'BAJAN', 'ZVI CINCO', '2022-10-18', 'PRESENT', '13:16:00'),
('1.77072E+11', '18104697', 'LLIDO', 'JOSHUA NICO TAN', '2022-10-18', 'PRESENT', '13:18:02'),
('1042306517593', '20103449', 'SOLON', 'VICTORIGEN SUQUIB', '2022-12-6', 'PRESENT', '13:16:10'),
('116286824468', '18103689', 'PAQUIBOT', 'JUN NIEL TAGHOY', '2022-12-6', 'PRESENT', '13:12:5'),
('177072204073', '18104697', 'LLIDO', 'JOSHUA NICO TAN', '2022-11-15', 'PRESENT', '13:24:38'),
('178817493432', '20100229', 'DUBLIN', 'HANNAH FLORENZ QUIACHON', '2022-12-6', 'PRESENT', '13:14:2'),
('335327839110', '20101430', 'TARUC', 'CHED VARQUEZ', '2022-12-6', 'PRESENT', '13:11:40'),
('5.8199E+11', '20400008', 'DEDURO', 'MAYNARD CARANO-O', '2022-10-18', 'PRESENT', '13:16:37'),
('581990101094', '20400008', 'DEDURO', 'MAYNARD CARANO-O', '2022-11-15', 'PRESENT', '13:24:48'),
('6.69466E+11', '18104198', 'PLAZO', 'KAYLA MILLICENT MALOLOY-ON', '2022-10-18', 'PRESENT', '13:14:49'),
('7.89844E+11', '18104118', 'COMENDADOR', 'ZAIR LEORICH JUGALBOT', '2022-10-18', 'PRESENT', '13:17:23'),
('757822400597', '19102023', 'ENERO', 'LYKA MARIE PARAISO', '2022-11-15', 'PRESENT', '13:25:50'),
('757822400597', '19102023', 'ENERO', 'LYKA MARIE PARAISO', '2022-12-6', 'PRESENT', '13:11:15'),
('789844013118', '18104118', 'COMENDADOR', 'ZAIR LEORICH JUGALBOT', '2022-11-15', 'PRESENT', '13:27:19'),
('789844013118', '18104118', 'COMENDADOR', 'ZAIR LEORICH JUGALBOT', '2022-12-6', 'PRESENT', '13:11:34'),
('796749814686', '19100869', 'LAO', 'ODHREY ALLIYANAH LANUTAN', '2022-11-15', 'PRESENT', '13:26:26'),
('8.25808E+11', '19101816', 'IGOT', 'KENNETH NEAL CABABAN', '2022-10-18', 'PRESENT', '13:13:43'),
('8.27783E+11', '18101371', 'ABELLA', 'VINCENT DOMINGUITO', '2022-10-18', 'PRESENT', '13:14:15'),
('825808159493', '19101816', 'IGOT', 'KENNETH NEAL CABABAN', '2022-11-15', 'PRESENT', '13:27:1'),
('827782536649', '18101371', 'ABELLA', 'VINCENT DOMINGUITO', '2022-12-6', 'PRESENT', '13:11:56'),
('84305543985', '18106371', 'ANIZOBA', 'CALLISTUS OBINNA', '2022-10-18', 'PRESENT', '13:18:37'),
('90300098426', '19100870', 'ANGELES', 'BEA MONICA AMANDORON', '2022-10-18', 'PRESENT', '13:15:21'),
('90300098426', '19100870', 'ANGELES', 'BEA MONICA AMANDORON', '2022-12-6', 'PRESENT', '13:11:8'),
('969798392651', '20102663', 'INITAN', 'JOHNFIL LAYOS', '2022-11-15', 'PRESENT', '13:25:16'),
('987041965658', '19103302', 'DELA CERNA', 'BELLE CLARICE PLEÑOS', '2022-11-15', 'PRESENT', '13:26:43'),
('987041965658', '19103302', 'DELA CERNA', 'BELLE CLARICE PLEÑOS', '2022-12-6', 'PRESENT', '13:10:58'),
('990166271656', '18102026', 'DECIERDO', 'CHRISTIAN CLYDE GARCIANO', '2022-12-6', 'PRESENT', '13:11:48');

-- --------------------------------------------------------

--
-- Table structure for table `g2-cpe 3101l (t - 0900 am - 1200 pm)`
--

CREATE TABLE `g2-cpe 3101l (t - 0900 am - 1200 pm)` (
  `RFID` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ID` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Surname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Date` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Time` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `g2-cpe 3101l (t - 0900 am - 1200 pm)`
--

INSERT INTO `g2-cpe 3101l (t - 0900 am - 1200 pm)` (`RFID`, `ID`, `Surname`, `Name`, `Date`, `Status`, `Time`) VALUES
('', '20101087', 'FACTOR', 'DOVYLE ARRIESGADO', '2022-11-15', 'ABSENT', ''),
('', '20102458', 'GO', 'ZACH DOMINIC CORDEÑO', '2022-11-15', 'PRESENT', ''),
('1001204273480', '18103866', 'TOME', 'HENTJI CZAR GELASQUE', '2022-12-6', 'PRESENT', '9:35:21'),
('1045581157295', '19102491', 'ADLAON', 'VINCENT JOSHUA CABATINGAN', '2022-10-18', 'PRESENT', '10:20:46'),
('1045581157295', '19102491', 'ADLAON', 'VINCENT JOSHUA CABATINGAN', '2022-11-15', 'PRESENT', '0:41:41'),
('1045581157295', '19102491', 'ADLAON', 'VINCENT JOSHUA CABATINGAN', '2022-12-6', 'PRESENT', '9:15:23'),
('111084096809', '18103903', 'VALLES', 'ZIAN RINZLER VERGAS', '2022-11-15', 'PRESENT', '0:40:42'),
('143953440448', '19101503', 'VILLAMOR', 'SEBASTIAN MARI MIGUEL PEPITO', '2022-11-15', 'PRESENT', '0:41:33'),
('212050189917', '20400020', 'SOLON', 'IXIDRO GABRIEL RELATADO', '2022-10-18', 'PRESENT', '10:28:22'),
('212050189917', '20400020', 'SOLON', 'IXIDRO GABRIEL RELATADO', '2022-11-15', 'PRESENT', '0:41:22'),
('212050189917', '20400020', 'SOLON', 'IXIDRO GABRIEL RELATADO', '2022-12-6', 'PRESENT', '9:17:8'),
('246496389472', '20101481', 'POGOY', 'EARLL ALLEN CYMER DELA CRUZ', '2022-10-18', 'PRESENT', '10:30:46'),
('246496389472', '20101481', 'POGOY', 'EARLL ALLEN CYMER DELA CRUZ', '2022-12-6', 'PRESENT', '9:16:28'),
('253590483880', '20101654', 'CHU', 'JHAY ALPHONSUS BANTASAN', '2022-11-15', 'PRESENT', '0:40:30'),
('253590483880', '20101654', 'CHU', 'JHAY ALPHONSUS BANTASAN', '2022-12-6', 'PRESENT', '9:16:15'),
('298984955491', '20103214', 'CERNAL', 'MARK TEOFEL SINADJAN', '2022-10-18', 'PRESENT', '10:24:38'),
('308669266620', '20103043', 'MOSQUEDA', 'JOHN VIANNEY CARDENAS', '2022-10-18', 'PRESENT', '10:27:39'),
('308669266620', '20103043', 'MOSQUEDA', 'JOHN VIANNEY CARDENAS', '2022-12-6', 'PRESENT', '9:17:22'),
('318794697183', '18104362', 'LIM', 'ARTHUR ELLY VILLARIN', '2022-10-18', 'PRESENT', '10:23:1'),
('365061349585', '19100472', 'DEL CASTILLO', 'JEREMY ENRIQUEZ', '2022-12-6', 'PRESENT', '9:15:36'),
('425366138504', '15105689', 'BRANZUELA', 'ALFONSO MIGUELLE DOSADO', '2022-10-18', 'PRESENT', '10:21:38'),
('425366138504', '15105689', 'BRANZUELA', 'ALFONSO MIGUELLE DOSADO', '2022-12-6', 'PRESENT', '9:16:5'),
('485343823560', '20103214', 'CERNAL', 'MARK TEOFEL SINADJAN', '2022-12-6', 'PRESENT', '9:17:42'),
('513704213246', '20700001', 'PACANAN', 'JESSIE ANGELO ALBARILLO', '2022-10-18', 'PRESENT', '10:26:41'),
('513704213246', '20700001', 'PACANAN', 'JESSIE ANGELO ALBARILLO', '2022-11-15', 'PRESENT', '0:41:5'),
('628678335945', '20101087', 'FACTOR', 'DOVYLE ARRIESGADO', '2022-12-6', 'PRESENT', '9:16:44'),
('857185007967', '20102551', 'CABERTE', 'ADAMS JOSHUA PRIA', '2022-10-18', 'PRESENT', '10:30:4'),
('857185007967', '20102551', 'CABERTE', 'ADAMS JOSHUA PRIA', '2022-11-15', 'PRESENT', '0:40:52'),
('938590223416', '18105420', 'GREGORIO', 'JERALD PATRICK CAPAYAS', '2022-10-18', 'PRESENT', '10:25:57'),
('938590223416', '18105420', 'GREGORIO', 'JERALD PATRICK CAPAYAS', '2022-11-15', 'PRESENT', '0:40:58');

-- --------------------------------------------------------

--
-- Table structure for table `g6-cpe 3101l (w - 0900 am - 1200 pm)`
--

CREATE TABLE `g6-cpe 3101l (w - 0900 am - 1200 pm)` (
  `RFID` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ID` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Surname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Date` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Time` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `g6-cpe 3101l (w - 0900 am - 1200 pm)`
--

INSERT INTO `g6-cpe 3101l (w - 0900 am - 1200 pm)` (`RFID`, `ID`, `Surname`, `Name`, `Date`, `Status`, `Time`) VALUES
('', '18103842', 'VESTIL', 'MARC RIO MALPAS', '2022-10-19', 'ABSENT', ''),
('', '18103890', 'LISTON', 'ELIJAH-MCCAULEY CASTOR', '2022-12-7', 'ABSENT', ''),
('', '18104001', 'PEREZ', 'JAKE MARK MOLIT', '2022-10-19', 'ABSENT', ''),
('', '19100943', 'FUENTES', 'JAMES LORENZ RAMO', '2022-10-19', 'ABSENT', ''),
('', '19101793', 'BALILI', 'MARK HUEY CAHAYAGAN', '2022-10-19', 'ABSENT', ''),
('', '20103315', 'MEDALLO', 'DENZIEL OLINARES', '2022-10-19', 'ABSENT', ''),
('1036645631566', '18103718', 'DEGAMO', 'JAY EMMANUEL CASINILLO', '2022-12-7', 'PRESENT', '9:19:14'),
('1061030146206', '20101292', 'TORAL', 'PAUL JOHN RAMIREZ', '2022-10-19', 'PRESENT', '9:11:49'),
('1061030146206', '20101292', 'TORAL', 'PAUL JOHN RAMIREZ', '2022-12-7', 'PRESENT', '9:23:23'),
('1073987455321', '20101013', 'ACAIN', 'JHAYCEE ANTHONY PITOGO', '2022-12-7', 'PRESENT', '9:19:48'),
('176350718237', '18103494', 'LASTRE', 'JHURY KEVIN PUEBLA', '2022-10-19', 'PRESENT', '9:16:12'),
('214460336640', '18104001', 'PEREZ', 'JAKE MARK MOLIT', '2022-12-7', 'PRESENT', '9:19:30'),
('245255465239', '18103439', 'LIMOSNERO', 'GRANT IAN ASIDERA', '2022-10-19', 'PRESENT', '9:12:16'),
('427583155761', '19100943', 'FUENTES', 'JAMES LORENZ RAMO', '2022-12-7', 'PRESENT', '9:18:30'),
('444682418935', '18104324', 'HUSAIN', 'JOHN CLEMENT OMBING', '2022-10-19', 'PRESENT', '9:14:47'),
('539523334968', '20103460', 'SUMALINOG', 'REGGIE ANN MALINGIN', '2022-12-7', 'PRESENT', '9:20:34'),
('589859547624', '18100033', 'ARRANGUEZ', 'FELIX MIGUEL DE DIOS', '2022-10-19', 'PRESENT', '9:12:44'),
('589859547624', '18100033', 'ARRANGUEZ', 'FELIX MIGUEL DE DIOS', '2022-12-7', 'PRESENT', '9:21:42'),
('6249646845', '18103083', 'VILLAHERMOSA', 'URES MORANDARTE', '2022-12-7', 'PRESENT', '9:17:59'),
('83542876618', '18101836', 'MENDOZA', 'MARC LAWRENCE LUMAPAS', '2022-12-7', 'PRESENT', '9:20:16'),
('840235814298', '18103276', 'DUMALAGAN', 'DANICA MARIE ANDAL', '2022-10-19', 'PRESENT', '9:15:36'),
('871745141510', '19900030', 'BERNALES', 'JEFF ANTHONY DATAHAN', '2022-10-19', 'PRESENT', '9:13:10'),
('871745141510', '19900030', 'BERNALES', 'JEFF ANTHONY DATAHAN', '2022-12-7', 'PRESENT', '9:18:58'),
('900544474610', '18103842', 'VESTIL', 'MARC RIO MALPAS', '2022-12-7', 'PRESENT', '9:17:43');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `g1-cpe 3101l (t - 0100 pm - 0400 pm)`
--
ALTER TABLE `g1-cpe 3101l (t - 0100 pm - 0400 pm)`
  ADD PRIMARY KEY (`RFID`,`ID`,`Date`);

--
-- Indexes for table `g2-cpe 3101l (t - 0900 am - 1200 pm)`
--
ALTER TABLE `g2-cpe 3101l (t - 0900 am - 1200 pm)`
  ADD PRIMARY KEY (`RFID`,`ID`,`Date`);

--
-- Indexes for table `g6-cpe 3101l (w - 0900 am - 1200 pm)`
--
ALTER TABLE `g6-cpe 3101l (w - 0900 am - 1200 pm)`
  ADD PRIMARY KEY (`RFID`,`ID`,`Date`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
