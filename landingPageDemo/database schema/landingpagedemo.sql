-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 17, 2021 at 09:15 PM
-- Server version: 10.4.14-MariaDB
-- PHP Version: 7.2.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `landingpagedemo`
--
CREATE DATABASE IF NOT EXISTS `landingpagedemo` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `landingpagedemo`;

-- --------------------------------------------------------

--
-- Table structure for table `body_content`
--

DROP TABLE IF EXISTS `body_content`;
CREATE TABLE `body_content` (
  `Id` int(11) NOT NULL,
  `Header` text NOT NULL,
  `Content` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `body_content`
--

INSERT INTO `body_content` (`Id`, `Header`, `Content`) VALUES
(1, 'WAS ERWARTET SIE IM YTONG BAUSATZHAUS BERATUNGSZENTRUM?', 'Sie möchten entweder ein schlüsselfertiges Haus oder ein Haus mit Eigenleistungen errichten? Wir beraten und betreuen Sie auf dem Weg zu Ihrem passenden YTONG Bauunternehmen in Berlin und Brandenburg. Unsere Bauherren profitieren dabei von zahlreichen Vorteilen und fundierten Leistungen. Zum Beispiel werden Sie durch unsere kostenneutrale Beratung und unsere Hausplanung viel Geld sparen und Fehler vor der Bauphase vermeiden! Über 14.000 Bauherren haben schon mit YTONG Bausatzhaus gebaut. Gerne stellen wir Ihnen einige persönlich vor. Lernen Sie uns kennen: Vereinbaren Sie einen Beratungstermin!');

-- --------------------------------------------------------

--
-- Table structure for table `card_content`
--

DROP TABLE IF EXISTS `card_content`;
CREATE TABLE `card_content` (
  `Id` int(11) NOT NULL,
  `Header` text NOT NULL,
  `Content` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `card_content`
--

INSERT INTO `card_content` (`Id`, `Header`, `Content`) VALUES
(1, 'Beratung rund um die Wahl Ihres Hauses', 'Haustyp, Hausgröße, Ausbaustufe, Hausbudget und vieles mehr: Während unserer Erstberatung gehen wir Ihre Wünsche durch, stellen sie auf den Prüfstand und helfen Ihnen mit Informationen und Tipps bei zahlreichen Entscheidungen. Damit schaffen wir die Basis, um Ihr Haus zu planen.'),
(2, '	\r\nPassendes Bauunternehm', 'Wir repräsentieren sieben YTONG-Partner aus dem Osten Deutschlands. Entsprechend Ihrer Hausplanung, Ihrer Ausbaustufe, Ihres Zeitplans, Ihrer Bauregion und vieler weiterer Kriterien stellen wir ein passendes Angebot von Ihrem zukünftigen Baupartner zusa'),
(3, '	\r\nFinanzcheck und -beratung', 'Wenn Sie es wünschen, unterstützen wir Sie bei Ihrer Baufinanzierung. Unter anderem helfen wir auch bei der Beantragung von KfW Krediten wie dem KfW 124 Wohneigentumsprogramm oder den Krediten rund um den Bau eines KfW Effizienzhauses 55, 40 oder 40 Plus.');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `body_content`
--
ALTER TABLE `body_content`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `card_content`
--
ALTER TABLE `card_content`
  ADD PRIMARY KEY (`Id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `body_content`
--
ALTER TABLE `body_content`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `card_content`
--
ALTER TABLE `card_content`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
