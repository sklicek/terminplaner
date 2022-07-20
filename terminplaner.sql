-- phpMyAdmin SQL Dump
-- version 5.1.1deb5ubuntu1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Erstellungszeit: 20. Jul 2022 um 20:31
-- Server-Version: 8.0.29-0ubuntu0.22.04.2
-- PHP-Version: 8.1.2

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

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `tblSprechstundenDetails`
--

CREATE TABLE `tblSprechstundenDetails` (
  `id_details` int NOT NULL,
  `id_termin` int DEFAULT NULL
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
-- Indizes für die Tabelle `tblSprechstundenDetails`
--
ALTER TABLE `tblSprechstundenDetails`
  ADD PRIMARY KEY (`id_details`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `tblSprechstunden`
--
ALTER TABLE `tblSprechstunden`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `tblSprechstundenDetails`
--
ALTER TABLE `tblSprechstundenDetails`
  MODIFY `id_details` int NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
