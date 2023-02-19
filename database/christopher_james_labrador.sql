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
-- Database: `christopher james labrador`
--

-- --------------------------------------------------------

--
-- Table structure for table `g1-cpe 3105 (tth - 0900 am - 1200 pm)`
--

CREATE TABLE `g1-cpe 3105 (tth - 0900 am - 1200 pm)` (
  `RFID` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ID` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Surname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Date` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Time` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `g1-cpe 3105 (tth - 0900 am - 1200 pm)`
--

INSERT INTO `g1-cpe 3105 (tth - 0900 am - 1200 pm)` (`RFID`, `ID`, `Surname`, `Name`, `Date`, `Status`, `Time`) VALUES
('', '16100551', 'GUERRERO', 'CLYDE GONZAGA', '2022-10-18', 'LATE', '09:26:40'),
('', '16100551', 'GUERRERO', 'CLYDE GONZAGA', '2022-11-15', 'PRESENT', '10:13:19'),
('', '18102026', 'DECIERDO', 'CHRISTIAN CLYDE GARCIANO', '2022-12-13', 'ABSENT', ''),
('', '18103689', 'PAQUIBOT', 'JUN NIEL TAGHOY', '2022-11-17', 'ABSENT', ''),
('', '18104198', 'PLAZO', 'KAYLA MILLICENT MALOLOY-ON', '2022-11-22', 'PRESENT', ''),
('', '18104198', 'PLAZO', 'KAYLA MILLICENT MALOLOY-ON', '2022-12-13', 'ABSENT', ''),
('', '18104697', 'LLIDO', 'JOSHUA NICO TAN', '2022-11-22', 'ABSENT', ''),
('', '18105033', 'MACALALAD', 'JADE NERZA', '2022-12-13', 'ABSENT', ''),
('', '19100869', 'LAO', 'ODHREY ALLIYANAH LANUTAN', '2022-12-13', 'ABSENT', ''),
('', '19100870', 'ANGELES', 'BEA MONICA AMANDORON', '2022-12-13', 'ABSENT', ''),
('', '19101816', 'IGOT', 'KENNETH NEAL CABABAN', '2022-12-13', 'ABSENT', ''),
('', '19102023', 'ENERO', 'LYKA MARIE PARAISO', '2022-12-13', 'ABSENT', ''),
('', '19103302', 'DELA CERNA', 'BELLE CLARICE PLEÑOS', '2022-10-18', 'ABSENT', ''),
('', '19103302', 'DELA CERNA', 'BELLE CLARICE PLEÑOS', '2022-12-13', 'ABSENT', ''),
('', '19105903', 'CARANO-O', 'CYRUS NOEL MONTEMAYOR', '2022-11-22', 'ABSENT', ''),
('', '19105903', 'CARANO-O', 'CYRUS NOEL MONTEMAYOR', '2022-12-13', 'ABSENT', ''),
('', '20101430', 'TARUC', 'CHED VARQUEZ', '2022-12-13', 'ABSENT', ''),
('', '20400008', 'DEDURO', 'MAYNARD CARANO-O', '2022-11-22', 'ABSENT', ''),
('1.04E+12', '18105033', 'MACALALAD', 'JADE NERZA', '2022-10-18', 'PRESENT', '09:14:48'),
('1.04E+12', '20103449', 'SOLON', 'VICTORIGEN SUQUIB', '2022-10-18', 'PRESENT', '09:14:07'),
('1.16E+11', '18103689', 'PAQUIBOT', 'JUN NIEL TAGHOY', '2022-10-18', 'PRESENT', '09:12:15'),
('1025425686323', '16100551', 'GUERRERO', 'CLYDE GONZAGA', '2022-11-22', 'PRESENT', '9:24:25'),
('1040717456385', '18105033', 'MACALALAD', 'JADE NERZA', '2022-11-15', 'PRESENT', '10:12:47'),
('1054942963404', '19103114', 'BAJAN', 'ZVI CINCO', '2022-11-15', 'PRESENT', '10:13:2'),
('1054942963404', '19103114', 'BAJAN', 'ZVI CINCO', '2022-11-17', 'PRESENT', '10:27:33'),
('1054942963404', '19103114', 'BAJAN', 'ZVI CINCO', '2022-11-22', 'PRESENT', '9:26:35'),
('154190923389', '', '', '', '2022-12-13', 'PRESENT', '11:16:13'),
('177072204073', '18104697', 'LLIDO', 'JOSHUA NICO TAN', '2022-11-15', 'PRESENT', '10:13:43'),
('177072204073', '18104697', 'LLIDO', 'JOSHUA NICO TAN', '2022-11-17', 'PRESENT', '10:30:4'),
('178817493432', '20100229', 'DUBLIN', 'HANNAH FLORENZ QUIACHON', '2022-11-15', 'PRESENT', '10:12:3'),
('178817493432', '20100229', 'DUBLIN', 'HANNAH FLORENZ QUIACHON', '2022-11-22', 'PRESENT', '9:26:10'),
('298984955491', '', '', '', '2022-12-13', 'PRESENT', '11:15:32'),
('335327839110', '20101430', 'TARUC', 'CHED VARQUEZ', '2022-11-22', 'PRESENT', '9:25:40'),
('5.82E+11', '20400008', 'DEDURO', 'MAYNARD CARANO-O', '2022-10-18', 'PRESENT', '09:15:35'),
('581990101094', '20400008', 'DEDURO', 'MAYNARD CARANO-O', '2022-11-17', 'PRESENT', '10:27:12'),
('6.69E+11', '18104198', 'PLAZO', 'KAYLA MILLICENT MALOLOY-ON', '2022-10-18', 'PRESENT', '09:12:53'),
('631298989353', '18104198', 'PLAZO', 'KAYLA MILLICENT MALOLOY-ON', '2022-11-17', 'PRESENT', '10:25:34'),
('669465583654', '18104198', 'PLAZO', 'KAYLA MILLICENT MALOLOY-ON', '2022-11-15', 'PRESENT', '10:14:9'),
('7.97E+11', '19100869', 'LAO', 'ODHREY ALLIYANAH LANUTAN', '2022-10-18', 'PRESENT', '09:18:28'),
('757822400597', '19102023', 'ENERO', 'LYKA MARIE PARAISO', '2022-11-17', 'PRESENT', '10:25:58'),
('789844013118', '18104118', 'COMENDADOR', 'ZAIR LEORICH JUGALBOT', '2022-11-15', 'PRESENT', '10:11:38'),
('789844013118', '18104118', 'COMENDADOR', 'ZAIR LEORICH JUGALBOT', '2022-11-17', 'PRESENT', '10:27:2'),
('794970462167', '', '', '', '2022-12-13', 'PRESENT', '11:15:44'),
('796749814686', '19100869', 'LAO', 'ODHREY ALLIYANAH LANUTAN', '2022-11-22', 'PRESENT', '9:24:49'),
('8.26E+11', '19101816', 'IGOT', 'KENNETH NEAL CABABAN', '2022-10-18', 'PRESENT', '09:13:27'),
('825808159493', '19101816', 'IGOT', 'KENNETH NEAL CABABAN', '2022-11-15', 'PRESENT', '10:8:48'),
('825808159493', '19101816', 'IGOT', 'KENNETH NEAL CABABAN', '2022-11-17', 'PRESENT', '10:26:43'),
('827782536649', '18101371', 'ABELLA', 'VINCENT DOMINGUITO', '2022-10-18', 'LATE', ''),
('827782536649', '18101371', 'ABELLA', 'VINCENT DOMINGUITO', '2022-11-15', 'PRESENT', ''),
('827782536649', '18101371', 'ABELLA', 'VINCENT DOMINGUITO', '2022-11-17', 'PRESENT', '10:29:1'),
('864424597674', '', '', '', '2022-12-13', 'PRESENT', '11:16:1'),
('9.90E+11', '18102026', 'DECIERDO', 'CHRISTIAN CLYDE GARCIANO', '2022-10-18', 'PRESENT', '09:16:02'),
('90300098426', '19100870', 'ANGELES', 'BEA MONICA AMANDORON', '2022-11-22', 'PRESENT', '9:25:18'),
('987041965658', '19103302', 'DELA CERNA', 'BELLE CLARICE PLEÑOS', '2022-11-15', 'PRESENT', '10:10:42'),
('987041965658', '19103302', 'DELA CERNA', 'BELLE CLARICE PLEÑOS', '2022-11-17', 'PRESENT', '10:26:27'),
('990166271656', '18102026', 'DECIERDO', 'CHRISTIAN CLYDE GARCIANO', '2022-11-15', 'PRESENT', '10:8:6');

-- --------------------------------------------------------

--
-- Table structure for table `g2-cpe 3105 (tth - 0100 pm - 0400 pm)`
--

CREATE TABLE `g2-cpe 3105 (tth - 0100 pm - 0400 pm)` (
  `RFID` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ID` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Surname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Date` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Time` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `g2-cpe 3105 (tth - 0100 pm - 0400 pm)`
--

INSERT INTO `g2-cpe 3105 (tth - 0100 pm - 0400 pm)` (`RFID`, `ID`, `Surname`, `Name`, `Date`, `Status`, `Time`) VALUES
('', '18103866', 'TOME', 'HENTJI CZAR GELASQUE', '2022-11-22', 'ABSENT', ''),
('', '18104362', 'LIM', 'ARTHUR ELLY VILLARIN', '2022-11-22', 'ABSENT', ''),
('', '19100472', 'DEL CASTILLO', 'JEREMY ENRIQUEZ', '2022-11-22', 'ABSENT', ''),
('', '19101503', 'VILLAMOR', 'SEBASTIAN MARI MIGUEL PEPITO', '2022-10-18', 'ABSENT', ''),
('', '19101503', 'VILLAMOR', 'SEBASTIAN MARI MIGUEL PEPITO', '2022-11-15', 'ABSENT', ''),
('', '19101503', 'VILLAMOR', 'SEBASTIAN MARI MIGUEL PEPITO', '2022-11-17', 'ABSENT', ''),
('', '19101503', 'VILLAMOR', 'SEBASTIAN MARI MIGUEL PEPITO', '2022-11-22', 'ABSENT', ''),
('', '19102491', 'ADLAON', 'VINCENT JOSHUA CABATINGAN', '2022-11-22', 'ABSENT', ''),
('', '20101087', 'FACTOR', 'DOVYLE ARRIESGADO', '2022-10-18', 'ABSENT', ''),
('', '20101481', 'POGOY', 'EARLL ALLEN CYMER DELA CRUZ', '2022-11-22', 'ABSENT', ''),
('', '20102458', 'GO', 'ZACH DOMINIC CORDEÑO', '2022-10-18', 'ABSENT', ''),
('', '20102551', 'CABERTE', 'ADAMS JOSHUA PRIA', '2022-11-22', 'ABSENT', ''),
('', '20102597', 'GIGANTO', 'KRISTOFFER JOHN JIPOS', '2022-11-22', 'ABSENT', ''),
('', '20103043', 'MOSQUEDA', 'JOHN VIANNEY CARDENAS', '2022-10-18', 'ABSENT', ''),
('', '20103214', 'CERNAL', 'MARK TEOFEL SINADJAN', '2022-11-15', 'PRESENT', ''),
('', '20103214', 'CERNAL', 'MARK TEOFEL SINADJAN', '2022-11-17', 'PRESENT', ''),
('1001204273480', '18103866', 'TOME', 'HENTJI CZAR GELASQUE', '2022-11-15', 'PRESENT', '13:20:33'),
('1001204273480', '18103866', 'TOME', 'HENTJI CZAR GELASQUE', '2022-11-17', 'PRESENT', '13:36:20'),
('1045581157295', '19102491', 'ADLAON', 'VINCENT JOSHUA CABATINGAN', '2022-11-17', 'PRESENT', '13:36:7'),
('111084096809', '18103903', 'VALLES', 'ZIAN RINZLER VERGAS', '2022-10-18', 'PRESENT', '13:6:55'),
('111084096809', '18103903', 'VALLES', 'ZIAN RINZLER VERGAS', '2022-11-15', 'PRESENT', '13:21:30'),
('111084096809', '18103903', 'VALLES', 'ZIAN RINZLER VERGAS', '2022-11-17', 'PRESENT', '13:37:17'),
('212050189917', '20400020', 'SOLON', 'IXIDRO GABRIEL RELATADO', '2022-10-18', 'PRESENT', '13:7:51'),
('212050189917', '20400020', 'SOLON', 'IXIDRO GABRIEL RELATADO', '2022-11-17', 'PRESENT', '13:37:5'),
('212050189917', '20400020', 'SOLON', 'IXIDRO GABRIEL RELATADO', '2022-11-22', 'PRESENT', '13:13:11'),
('246496389472', '20101481', 'POGOY', 'EARLL ALLEN CYMER DELA CRUZ', '2022-11-15', 'PRESENT', '13:19:57'),
('246496389472', '20101481', 'POGOY', 'EARLL ALLEN CYMER DELA CRUZ', '2022-11-17', 'PRESENT', '13:36:39'),
('249305566207', '20102597', 'GIGANTO', 'KRISTOFFER JOHN JIPOS', '2022-11-15', 'PRESENT', '13:21:43'),
('249305566207', '20102597', 'GIGANTO', 'KRISTOFFER JOHN JIPOS', '2022-11-17', 'PRESENT', '13:37:26'),
('298984955491', '20103214', 'CERNAL', 'MARK TEOFEL SINADJAN', '2022-10-18', 'PRESENT', '13:9:21'),
('318794697183', '18104362', 'LIM', 'ARTHUR ELLY VILLARIN', '2022-10-18', 'PRESENT', '13:5:35'),
('365061349585', '19100472', 'DEL CASTILLO', 'JEREMY ENRIQUEZ', '2022-10-18', 'PRESENT', '13:6:6'),
('365061349585', '19100472', 'DEL CASTILLO', 'JEREMY ENRIQUEZ', '2022-11-15', 'PRESENT', '13:20:47'),
('365061349585', '19100472', 'DEL CASTILLO', 'JEREMY ENRIQUEZ', '2022-11-17', 'PRESENT', '13:35:56'),
('485343823560', '20103214', 'CERNAL', 'MARK TEOFEL SINADJAN', '2022-11-22', 'PRESENT', '13:14:17'),
('857185007967', '20102551', 'CABERTE', 'ADAMS JOSHUA PRIA', '2022-10-18', 'PRESENT', '13:7:18'),
('857185007967', '20102551', 'CABERTE', 'ADAMS JOSHUA PRIA', '2022-11-15', 'PRESENT', '13:21:21'),
('894672117022', '20102240', 'ROSALES', 'RONAN ALO', '2022-11-15', 'PRESENT', '13:21:2'),
('938590223416', '18105420', 'GREGORIO', 'JERALD PATRICK CAPAYAS', '2022-11-15', 'PRESENT', '13:20:13'),
('938590223416', '18105420', 'GREGORIO', 'JERALD PATRICK CAPAYAS', '2022-11-17', 'PRESENT', '13:36:31');

-- --------------------------------------------------------

--
-- Table structure for table `g5-cpe 3105 (mw - 0130 pm - 0430 pm)`
--

CREATE TABLE `g5-cpe 3105 (mw - 0130 pm - 0430 pm)` (
  `RFID` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ID` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Surname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Date` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Time` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `g5-cpe 3105 (mw - 0130 pm - 0430 pm)`
--

INSERT INTO `g5-cpe 3105 (mw - 0130 pm - 0430 pm)` (`RFID`, `ID`, `Surname`, `Name`, `Date`, `Status`, `Time`) VALUES
('', '16100844', 'POTICAR', 'JAHNA PATRICIA SEVILLE', '2022-11-16', 'ABSENT', ''),
('', '16100844', 'POTICAR', 'JAHNA PATRICIA SEVILLE', '2022-11-21', 'ABSENT', ''),
('', '18103083', 'VILLAHERMOSA', 'URES MORANDARTE', '2022-10-17', 'PRESENT', '13:41:18'),
('', '18103083', 'VILLAHERMOSA', 'URES MORANDARTE', '2022-11-16', 'PRESENT', ''),
('', '18103083', 'VILLAHERMOSA', 'URES MORANDARTE', '2022-11-21', 'ABSENT', ''),
('', '18103718', 'DEGAMO', 'JAY EMMANUEL CASINILLO', '2022-10-17', 'ABSENT', ''),
('', '18103718', 'DEGAMO', 'JAY EMMANUEL CASINILLO', '2022-11-21', 'ABSENT', ''),
('', '18103842', 'VESTIL', 'MARC RIO MALPAS', '2022-10-17', 'ABSENT', ''),
('', '18103890', 'LISTON', 'ELIJAH-MCCAULEY CASTOR', '2022-10-17', 'ABSENT', ''),
('', '18103890', 'LISTON', 'ELIJAH-MCCAULEY CASTOR', '2022-11-16', 'PRESENT', ''),
('', '18103890', 'LISTON', 'ELIJAH-MCCAULEY CASTOR', '2022-11-21', 'ABSENT', ''),
('', '18104001', 'PEREZ', 'JAKE MARK MOLIT', '2022-10-17', 'PRESENT', '13:39:55'),
('', '18106371', 'ANIZOBA', 'CALLISTUS OBINNA', '2022-11-21', 'ABSENT', ''),
('', '19100943', 'FUENTES', 'JAMES LORENZ RAMO', '2022-11-16', 'PRESENT', ''),
('', '20101292', 'TORAL', 'PAUL JOHN RAMIREZ', '2022-11-21', 'PRESENT', ''),
('', '20101951', 'MOZAR', 'CARLO DOMINGUEZ', '2022-11-16', 'PRESENT', ''),
('', '20101951', 'MOZAR', 'CARLO DOMINGUEZ', '2022-11-21', 'PRESENT', ''),
('', '20103315', 'MEDALLO', 'DENZIEL OLINARES', '2022-10-17', 'PRESENT', '13:43:37'),
('', '20400056', 'GIGANTE', 'MATEO SESALDO', '2022-10-17', 'ABSENT', ''),
('', '20400056', 'GIGANTE', 'MATEO SESALDO', '2022-11-21', 'ABSENT', ''),
('1073987455321', '20101013', 'ACAIN', 'JHAYCEE ANTHONY PITOGO', '2022-10-17', 'PRESENT', '13:35:37'),
('1073987455321', '20101013', 'ACAIN', 'JHAYCEE ANTHONY PITOGO', '2022-11-21', 'PRESENT', '13:58:56'),
('176350718237', '18103494', 'LASTRE', 'JHURY KEVIN PUEBLA', '2022-10-17', 'PRESENT', '13:36:25'),
('176350718237', '18103494', 'LASTRE', 'JHURY KEVIN PUEBLA', '2022-11-16', 'PRESENT', '15:47:6'),
('176350718237', '18103494', 'LASTRE', 'JHURY KEVIN PUEBLA', '2022-11-21', 'PRESENT', '13:57:49'),
('245255465239', '18103439', 'LIMOSNERO', 'GRANT IAN ASIDERA', '2022-10-17', 'PRESENT', '13:39:10'),
('245255465239', '18103439', 'LIMOSNERO', 'GRANT IAN ASIDERA', '2022-11-16', 'PRESENT', '15:47:42'),
('321432849010', '18102577', 'TIMTIM', 'KRYSTELLE UNGRIA', '2022-10-17', 'PRESENT', '13:37:5'),
('321432849010', '18102577', 'TIMTIM', 'KRYSTELLE UNGRIA', '2022-11-16', 'PRESENT', '15:46:25'),
('539523334968', '20103460', 'SUMALINOG', 'REGGIE ANN MALINGIN', '2022-11-16', 'PRESENT', '15:47:30'),
('589859547624', '18100033', 'ARRANGUEZ', 'FELIX MIGUEL DE DIOS', '2022-10-17', 'PRESENT', '13:37:36'),
('589859547624', '18100033', 'ARRANGUEZ', 'FELIX MIGUEL DE DIOS', '2022-11-16', 'PRESENT', '15:46:33'),
('802860186461', '17100212', 'LUCHAVEZ', 'KLYLE ALEXANDRE TOLO', '2022-11-16', 'PRESENT', '15:45:36'),
('840235814298', '18103276', 'DUMALAGAN', 'DANICA MARIE ANDAL', '2022-11-21', 'PRESENT', '13:57:4'),
('871745141510', '19900030', 'BERNALES', 'JEFF ANTHONY DATAHAN', '2022-11-16', 'PRESENT', '15:46:54'),
('871745141510', '19900030', 'BERNALES', 'JEFF ANTHONY DATAHAN', '2022-11-21', 'PRESENT', '13:57:35'),
('938146663003', '20102308', 'CASTRO', 'THOMAS LEE ARQUIZA', '2022-11-21', 'PRESENT', '13:58:21'),
('940054219157', '20100642', 'LUMAYNO', 'FRANCIS ANGELO LORENZO', '2022-10-17', 'PRESENT', '13:38:11'),
('940054219157', '20100642', 'LUMAYNO', 'FRANCIS ANGELO LORENZO', '2022-11-16', 'PRESENT', '15:48:16');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `g1-cpe 3105 (tth - 0900 am - 1200 pm)`
--
ALTER TABLE `g1-cpe 3105 (tth - 0900 am - 1200 pm)`
  ADD PRIMARY KEY (`RFID`,`ID`,`Date`);

--
-- Indexes for table `g2-cpe 3105 (tth - 0100 pm - 0400 pm)`
--
ALTER TABLE `g2-cpe 3105 (tth - 0100 pm - 0400 pm)`
  ADD PRIMARY KEY (`RFID`,`ID`,`Date`);

--
-- Indexes for table `g5-cpe 3105 (mw - 0130 pm - 0430 pm)`
--
ALTER TABLE `g5-cpe 3105 (mw - 0130 pm - 0430 pm)`
  ADD PRIMARY KEY (`RFID`,`ID`,`Date`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;