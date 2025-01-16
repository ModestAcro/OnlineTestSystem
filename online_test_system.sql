-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Jan 16, 2025 at 09:48 AM
-- Server version: 8.0.35
-- PHP Version: 8.2.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `online_test_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `tAdministratorzy`
--

CREATE TABLE `tAdministratorzy` (
  `ID` int NOT NULL,
  `imie` varchar(100) NOT NULL,
  `nazwisko` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `haslo` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tAdministratorzy`
--

INSERT INTO `tAdministratorzy` (`ID`, `imie`, `nazwisko`, `email`, `haslo`) VALUES
(8, 'Jan', 'Kowalski', 'jan.kowalski@gmail.com', '$2y$10$vDEZgC8JHU6SNFxux2nsWu4aSPbLnwAFhTt5Xog4s.2B1y5rkXzpe'),
(9, 'Karol', 'Kowalski', 'karol.kowalski@gmail.com', '$2y$10$ki2Hj7Vnl2H9lZ85rT9XReNvhzMnao7hKiKvy6cn0zYUiCBmwIls2');

-- --------------------------------------------------------

--
-- Table structure for table `tGrupy`
--

CREATE TABLE `tGrupy` (
  `ID` int NOT NULL,
  `rok` year NOT NULL,
  `nazwa` varchar(255) NOT NULL,
  `id_wykladowcy` int NOT NULL,
  `id_przedmiotu` int NOT NULL,
  `id_uczelni` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tGrupy`
--

INSERT INTO `tGrupy` (`ID`, `rok`, `nazwa`, `id_wykladowcy`, `id_przedmiotu`, `id_uczelni`) VALUES
(60, '2023', 'Grupa 4', 19, 26, 2),
(61, '2024', 'Grupa 1', 22, 26, 2),
(62, '2024', 'Grupa 2', 22, 26, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tGrupyStudenci`
--

CREATE TABLE `tGrupyStudenci` (
  `id_grupy` int NOT NULL,
  `id_studenta` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tGrupyStudenci`
--

INSERT INTO `tGrupyStudenci` (`id_grupy`, `id_studenta`) VALUES
(60, 33),
(61, 33),
(62, 33),
(60, 34),
(61, 34),
(62, 34),
(60, 35),
(61, 35),
(62, 35),
(61, 37),
(62, 37);

-- --------------------------------------------------------

--
-- Table structure for table `tOdpowiedzi`
--

CREATE TABLE `tOdpowiedzi` (
  `ID` int NOT NULL,
  `id_pytania` int NOT NULL,
  `tresc` text NOT NULL,
  `data_stworzenia` datetime DEFAULT CURRENT_TIMESTAMP,
  `data_aktualizacji` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `correct` tinyint(1) NOT NULL,
  `punkty` decimal(5,2) NOT NULL DEFAULT '0.00'
) ;

--
-- Dumping data for table `tOdpowiedzi`
--

INSERT INTO `tOdpowiedzi` (`ID`, `id_pytania`, `tresc`, `data_stworzenia`, `data_aktualizacji`, `correct`, `punkty`) VALUES
(359, 100, '4', '2025-01-11 15:37:46', '2025-01-11 15:45:44', 1, 10.00),
(360, 100, '2', '2025-01-11 15:37:46', '2025-01-11 15:45:44', 0, 0.00),
(361, 101, '4', '2025-01-16 11:42:19', '2025-01-16 11:42:19', 1, 10.00),
(362, 101, '2', '2025-01-16 11:42:19', '2025-01-16 11:42:19', 0, 0.00),
(363, 101, '5', '2025-01-16 11:42:19', '2025-01-16 11:42:19', 0, 0.00),
(364, 101, '6', '2025-01-16 11:42:19', '2025-01-16 11:42:19', 0, 0.00);

-- --------------------------------------------------------

--
-- Table structure for table `tPrzedmioty`
--

CREATE TABLE `tPrzedmioty` (
  `ID` int NOT NULL,
  `nazwa` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `uwagi` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tPrzedmioty`
--

INSERT INTO `tPrzedmioty` (`ID`, `nazwa`, `uwagi`) VALUES
(25, 'Bezpieczeństwo systemów informatycznych', 'Prowadzący: Jan Suchodolski'),
(26, 'Pracownia dyplomowa I', 'Prowadzący: Mariusz Żynel'),
(28, 'Usługi w chmurze obliczeniowej	', 'Prowadzący: Roman Avlasevič');

-- --------------------------------------------------------

--
-- Table structure for table `tPytania`
--

CREATE TABLE `tPytania` (
  `ID` int NOT NULL,
  `id_przedmiotu` int NOT NULL,
  `id_wykladowcy` int NOT NULL,
  `typ` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `tresc` text NOT NULL,
  `data_stworzenia` datetime DEFAULT CURRENT_TIMESTAMP,
  `data_aktualizacji` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tPytania`
--

INSERT INTO `tPytania` (`ID`, `id_przedmiotu`, `id_wykladowcy`, `typ`, `tresc`, `data_stworzenia`, `data_aktualizacji`) VALUES
(100, 26, 19, 'Wielokrotnego wyboru', '2 + 2 = ?', '2025-01-11 15:37:46', '2025-01-11 15:37:46'),
(101, 26, 22, 'Wielokrotnego wyboru', '2 + 2 = ?', '2025-01-16 11:42:19', '2025-01-16 11:42:19');

-- --------------------------------------------------------

--
-- Table structure for table `tStudenci`
--

CREATE TABLE `tStudenci` (
  `ID` int NOT NULL,
  `nr_albumu` int NOT NULL,
  `imie` varchar(100) NOT NULL,
  `nazwisko` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `haslo` varchar(255) NOT NULL,
  `aktywny` enum('T','N') NOT NULL DEFAULT 'T',
  `uwagi` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tStudenci`
--

INSERT INTO `tStudenci` (`ID`, `nr_albumu`, `imie`, `nazwisko`, `email`, `haslo`, `aktywny`, `uwagi`) VALUES
(33, 87188, 'Modest', 'Siemionow', 'modest.sem@gmail.com', '$2y$10$777sZGU5YDkZ578Cjo/q1.CEIM5LkpmvaUjIylLIarMWTeD8mur.u', 'N', 'Informatyka 3 rok'),
(34, 83240, 'Edgar', 'Makowski', 'edgar.makowski@example.com', '$2y$10$NmWKMOWC5VOC15e.nFRosOdTlq0q8t253jawBdlcolOiZKXKey5t2', 'N', 'Informatyka 3 rok'),
(35, 86476, 'Bogusław', 'Pacyno', 'boguslaw.pacyno@example.com', '$2y$10$rLI0wuXepebzKI0QsUb.ZOiItvSa9lOstxbA5ijsgHrCwk2McSkru', 'N', 'Informatyka 3 rok'),
(37, 85746, 'Justyna', 'Širvinskaja', 'justyna.sirvinskaja@example.com', '$2y$10$i4OrZTX2obEy/RHJXieQ/uTQjCKWBgZXSY91zPGpDRn34LoRKmRym', 'N', 'Informatyka 3 rok');

-- --------------------------------------------------------

--
-- Table structure for table `tTesty`
--

CREATE TABLE `tTesty` (
  `ID` int NOT NULL,
  `nazwa` varchar(255) NOT NULL,
  `data_utworzenia` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `data_rozpoczecia` datetime NOT NULL,
  `data_zakonczenia` datetime NOT NULL,
  `czas_trwania` int NOT NULL,
  `ilosc_prob` int NOT NULL,
  `id_grupy` int DEFAULT NULL,
  `id_wykladowcy` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tUczelnie`
--

CREATE TABLE `tUczelnie` (
  `ID` int NOT NULL,
  `nazwa` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `miasto` varchar(255) NOT NULL,
  `kraj` varchar(255) NOT NULL,
  `kontynent` varchar(255) NOT NULL,
  `adres` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `uwagi` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tUczelnie`
--

INSERT INTO `tUczelnie` (`ID`, `nazwa`, `miasto`, `kraj`, `kontynent`, `adres`, `uwagi`) VALUES
(1, 'Uniwersytet w Białymstoku ', 'Białystok', 'Polska', 'Europa', ' Świerkowa 20B', 'Najważniejsza uczelnia w północno-wschodniej Polsce'),
(2, 'Filiia Uniwersytetu w Białymstoku w Wilnie', 'Wilno', 'Litwa', 'Europa', 'Aguonų g. 22', 'Pierwsza filia polskiego uniwersytetu poza granicami kraju');

-- --------------------------------------------------------

--
-- Table structure for table `tWykladowcy`
--

CREATE TABLE `tWykladowcy` (
  `ID` int NOT NULL,
  `imie` varchar(100) NOT NULL,
  `nazwisko` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `haslo` varchar(255) NOT NULL,
  `aktywny` enum('T','N') NOT NULL DEFAULT 'N',
  `uwagi` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tWykladowcy`
--

INSERT INTO `tWykladowcy` (`ID`, `imie`, `nazwisko`, `email`, `haslo`, `aktywny`, `uwagi`) VALUES
(19, 'Mieczysław', 'Muraszkiewicz', 'mieczyslaw.muraszkiewicz@example.com', '$2y$10$ZoU/.xoGRJD.2ks6kWaxfOyQFECUUp3w8a.S0DWIyQL/1bZomctKK', 'N', 'prof. dr. hab.'),
(22, 'Mariusz', 'Żynel', 'mariusz.zynel@example.com', '$2y$10$zh4Cuo/oFmNZr4UKRUOqV.COCVaWL6h2BHN8FkNbZWwAcva0FIjeu', 'N', 'dr.'),
(23, 'Jan', 'Suchodolski', 'jan.suchodolski@example.com', '$2y$10$ObdDfKfbOCdFEzEu36XHluuqBOAYXORNsXbU1hZT9q2RZqNDBgtxu', 'N', 'dr.');

-- --------------------------------------------------------

--
-- Table structure for table `tZalaczniki`
--

CREATE TABLE `tZalaczniki` (
  `ID` int NOT NULL,
  `id_pytania` int DEFAULT NULL,
  `file_path` varchar(255) NOT NULL,
  `data_stworzenia` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `data_aktualizacji` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tZalaczniki`
--

INSERT INTO `tZalaczniki` (`ID`, `id_pytania`, `file_path`, `data_stworzenia`, `data_aktualizacji`) VALUES
(44, 100, '../../uploads/19/security2.png', '2025-01-11 20:30:04', '2025-01-11 20:30:04'),
(45, 101, '../../uploads/22/security.png', '2025-01-16 09:42:19', '2025-01-16 09:42:19');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tAdministratorzy`
--
ALTER TABLE `tAdministratorzy`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `tGrupy`
--
ALTER TABLE `tGrupy`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `fk_wykladowca_grupy` (`id_wykladowcy`),
  ADD KEY `FK_id_przedmiotu` (`id_przedmiotu`),
  ADD KEY `FK_id_uczelni` (`id_uczelni`);

--
-- Indexes for table `tGrupyStudenci`
--
ALTER TABLE `tGrupyStudenci`
  ADD PRIMARY KEY (`id_grupy`,`id_studenta`),
  ADD KEY `fk_grupy_studenci_studenci` (`id_studenta`);

--
-- Indexes for table `tOdpowiedzi`
--
ALTER TABLE `tOdpowiedzi`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `id_pytania` (`id_pytania`);

--
-- Indexes for table `tPrzedmioty`
--
ALTER TABLE `tPrzedmioty`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tPytania`
--
ALTER TABLE `tPytania`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `id_wykladowcy` (`id_wykladowcy`),
  ADD KEY `fk_przedmiotu` (`id_przedmiotu`);

--
-- Indexes for table `tStudenci`
--
ALTER TABLE `tStudenci`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `tTesty`
--
ALTER TABLE `tTesty`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `id_grupy` (`id_grupy`),
  ADD KEY `fk_wykladowca` (`id_wykladowcy`);

--
-- Indexes for table `tUczelnie`
--
ALTER TABLE `tUczelnie`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tWykladowcy`
--
ALTER TABLE `tWykladowcy`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tZalaczniki`
--
ALTER TABLE `tZalaczniki`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `id_pytania` (`id_pytania`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tAdministratorzy`
--
ALTER TABLE `tAdministratorzy`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `tGrupy`
--
ALTER TABLE `tGrupy`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT for table `tOdpowiedzi`
--
ALTER TABLE `tOdpowiedzi`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tPrzedmioty`
--
ALTER TABLE `tPrzedmioty`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `tPytania`
--
ALTER TABLE `tPytania`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=102;

--
-- AUTO_INCREMENT for table `tStudenci`
--
ALTER TABLE `tStudenci`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `tTesty`
--
ALTER TABLE `tTesty`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tUczelnie`
--
ALTER TABLE `tUczelnie`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `tWykladowcy`
--
ALTER TABLE `tWykladowcy`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `tZalaczniki`
--
ALTER TABLE `tZalaczniki`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tGrupy`
--
ALTER TABLE `tGrupy`
  ADD CONSTRAINT `FK_id_przedmiotu` FOREIGN KEY (`id_przedmiotu`) REFERENCES `tPrzedmioty` (`ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_id_uczelni` FOREIGN KEY (`id_uczelni`) REFERENCES `tUczelnie` (`ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_wykladowca_grupy` FOREIGN KEY (`id_wykladowcy`) REFERENCES `tWykladowcy` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tGrupyStudenci`
--
ALTER TABLE `tGrupyStudenci`
  ADD CONSTRAINT `fk_grupy_studenci_grupy` FOREIGN KEY (`id_grupy`) REFERENCES `tGrupy` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_grupy_studenci_studenci` FOREIGN KEY (`id_studenta`) REFERENCES `tStudenci` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tOdpowiedzi`
--
ALTER TABLE `tOdpowiedzi`
  ADD CONSTRAINT `todpowiedzi_ibfk_1` FOREIGN KEY (`id_pytania`) REFERENCES `tPytania` (`ID`);

--
-- Constraints for table `tPytania`
--
ALTER TABLE `tPytania`
  ADD CONSTRAINT `fk_przedmiotu` FOREIGN KEY (`id_przedmiotu`) REFERENCES `tPrzedmioty` (`ID`),
  ADD CONSTRAINT `tpytania_ibfk_1` FOREIGN KEY (`id_wykladowcy`) REFERENCES `tWykladowcy` (`ID`);

--
-- Constraints for table `tTesty`
--
ALTER TABLE `tTesty`
  ADD CONSTRAINT `fk_wykladowca` FOREIGN KEY (`id_wykladowcy`) REFERENCES `tWykladowcy` (`ID`),
  ADD CONSTRAINT `ttesty_ibfk_1` FOREIGN KEY (`id_grupy`) REFERENCES `tGrupy` (`ID`) ON DELETE CASCADE;

--
-- Constraints for table `tZalaczniki`
--
ALTER TABLE `tZalaczniki`
  ADD CONSTRAINT `tzalaczniki_ibfk_1` FOREIGN KEY (`id_pytania`) REFERENCES `tPytania` (`ID`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
