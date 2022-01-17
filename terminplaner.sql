-- phpMyAdmin SQL Dump
-- version 5.0.4deb2ubuntu5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Erstellungszeit: 17. Jan 2022 um 13:46
-- Server-Version: 8.0.27-0ubuntu0.21.10.1
-- PHP-Version: 8.0.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `terminplaner`
--
CREATE DATABASE IF NOT EXISTS `terminplaner` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `terminplaner`;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `tblSprechstunden`
--

CREATE TABLE `tblSprechstunden` (
  `id` int NOT NULL,
  `anfang` time NOT NULL,
  `ende` time NOT NULL,
  `datum` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `tblSprechstunden`
--
ALTER TABLE `tblSprechstunden`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `tblSprechstunden`
--
ALTER TABLE `tblSprechstunden`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
