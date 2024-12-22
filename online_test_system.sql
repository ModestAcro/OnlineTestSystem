-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Dec 22, 2024 at 07:01 PM
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
(1, 'Jan', 'Kowalski', 'jan.kowalski@gmail.com', 'Jan'),
(2, 'Karol', 'Kowalski', 'karol.kowalski@gmail.com', 'Karol');

-- --------------------------------------------------------

--
-- Table structure for table `tGrupy`
--

CREATE TABLE `tGrupy` (
  `ID` int NOT NULL,
  `rok` year NOT NULL,
  `uczelnia` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `przedmiot` varchar(255) NOT NULL,
  `nazwa` varchar(255) NOT NULL,
  `id_wykladowcy` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tGrupyStudenci`
--

CREATE TABLE `tGrupyStudenci` (
  `id_grupy` int NOT NULL,
  `id_studenta` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
(11, 'Matematyka', 'Przedmiot podstawowy, wymaga dużego nakładu pracy.'),
(12, 'Fizyka', 'Zajęcia wymagające praktycznego podejścia, eksperymenty.'),
(13, 'Chemia', 'Przedmiot teoretyczny z elementami laboratoryjnymi.'),
(14, 'Biologia', 'Część materiału obejmuje wyjścia terenowe i obserwacje.'),
(15, 'Informatyka', 'Zajęcia obejmują programowanie i tworzenie aplikacji.'),
(16, 'Baza danych', 'Przedmiot prowadzi dr. German Budnik '),
(17, 'Chmury obliczeniowe', 'Przedmiot prowadzi Roman Awlasewicz');

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
(1, 10001, 'Marcin', 'Jankowski', 'marcin.jankowski@student.edu', 'haslo123', 'T', 'Student aktywnie uczestniczy w zajęciach'),
(2, 10002, 'Katarzyna', 'Mazurek', 'katarzyna.mazurek@student.edu', 'haslo123', 'T', 'Brak uwag'),
(3, 10003, 'Tomasz', 'Nowak', 'tomasz.nowak@student.edu', 'haslo123', 'T', 'Brak uwag'),
(4, 10004, 'Monika', 'Kowalska', 'monika.kowalska@student.edu', 'haslo123', 'T', 'Zawsze punktualna'),
(5, 10005, 'Piotr', 'Wójcik', 'piotr.wojcik@student.edu', 'haslo123', 'T', 'Brak uwag'),
(6, 10006, 'Agnieszka', 'Duda', 'agnieszka.duda@student.edu', 'haslo123', 'T', 'Student wykazuje dużą motywację'),
(7, 10007, 'Marek', 'Zieliński', 'marek.zielinski@student.edu', 'haslo123', 'T', 'Brak uwag'),
(8, 10008, 'Klaudia', 'Szymańska', 'klaudia.szymanska@student.edu', 'haslo123', 'T', 'Brak uwag'),
(9, 10009, 'Paweł', 'Wiśniewski', 'pawel.wisniewski@student.edu', 'haslo123', 'T', 'Brak uwag'),
(10, 10010, 'Elżbieta', 'Mazur', 'elzbieta.mazur@student.edu', 'haslo123', 'T', 'Niezbyt aktywna na zajęciach'),
(11, 10011, 'Aleksandra', 'Król', 'aleksandra.krol@student.edu', 'haslo123', 'T', 'Brak uwag'),
(12, 10012, 'Sebastian', 'Bąk', 'sebastian.bak@student.edu', 'haslo123', 'T', 'Brak uwag'),
(13, 10013, 'Michał', 'Sikora', 'michal.sikora@student.edu', 'haslo123', 'T', 'Brak uwag'),
(14, 10014, 'Janina', 'Kwiatkowska', 'janina.kwiatkowska@student.edu', 'haslo123', 'T', 'Brak uwag'),
(15, 10015, 'Wojciech', 'Piotrowski', 'wojciech.piotrowski@student.edu', 'haslo123', 'T', 'Brak uwag'),
(16, 10016, 'Aneta', 'Zawisza', 'aneta.zawisza@student.edu', 'haslo123', 'T', 'Brak uwag'),
(17, 10017, 'Kamil', 'Bąk', 'kamil.bak@student.edu', 'haslo123', 'T', 'Brak uwag'),
(18, 10018, 'Magdalena', 'Pawlak', 'magdalena.pawlak@student.edu', 'haslo123', 'T', 'Brak uwag'),
(19, 10019, 'Andrzej', 'Jankowski', 'andrzej.jankowski@student.edu', 'haslo123', 'T', 'Brak uwag'),
(20, 10020, 'Zofia', 'Sikora', 'zofia.sikora@student.edu', 'haslo123', 'T', 'Brak uwag'),
(21, 10021, 'Piotr', 'Dąbrowski', 'piotr.dabrowski@student.edu', 'haslo123', 'T', 'Brak uwag'),
(22, 10022, 'Karolina', 'Kozłowska', 'karolina.kozlowska@student.edu', 'haslo123', 'T', 'Brak uwag'),
(23, 10023, 'Grzegorz', 'Majchrzak', 'grzegorz.majchrzak@student.edu', 'haslo123', 'T', 'Brak uwag'),
(24, 10024, 'Alicja', 'Brodowska', 'alicja.brodowska@student.edu', 'haslo123', 'T', 'Brak uwag'),
(25, 10025, 'Natalia', 'Borkowska', 'natalia.borkowska@student.edu', 'haslo123', 'T', 'Brak uwag'),
(26, 10026, 'Paweł', 'Kozak', 'pawel.kozak@student.edu', 'haslo123', 'T', 'Brak uwag'),
(27, 10027, 'Monika', 'Majewska', 'monika.majewska@student.edu', 'haslo123', 'T', 'Brak uwag'),
(28, 10028, 'Tomasz', 'Kaczmarek', 'tomasz.kaczmarek@student.edu', 'haslo123', 'T', 'Brak uwag'),
(29, 10029, 'Dorota', 'Kowal', 'dorota.kowal@student.edu', 'haslo123', 'T', 'Brak uwag'),
(30, 10030, 'Marek', 'Sikora', 'marek.sikora@student.edu', 'haslo123', 'T', 'Brak uwag');

-- --------------------------------------------------------

--
-- Table structure for table `tUczelnie`
--

CREATE TABLE `tUczelnie` (
  `ID` int NOT NULL,
  `nazwa_uczelni` varchar(255) NOT NULL,
  `miasto` varchar(255) NOT NULL,
  `kraj` varchar(255) NOT NULL,
  `kontynent` varchar(255) NOT NULL,
  `adres_uczelni` varchar(255) DEFAULT NULL,
  `uwagi` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tUczelnie`
--

INSERT INTO `tUczelnie` (`ID`, `nazwa_uczelni`, `miasto`, `kraj`, `kontynent`, `adres_uczelni`, `uwagi`) VALUES
(1, 'Uniwersytet w Białymstoku', 'Białystok', 'Polska', 'Europa', 'ul. Marii Skłodowskiej-Curie 14', 'Najważniejsza uczelnia w północno-wschodniej Polsce'),
(2, 'Filiia Uniwersytetu w Białymstoku w Wilnie', 'Wilno', 'Litwa', 'Europa', 'Aguonų g. 22', 'Pierwsza filia polskiego uniwersytetu poza granicami kraju'),
(3, 'Uniwersytet Jagielloński', 'Kraków', 'Polska', 'Europa', 'ul. Gołębia 24', 'Najstarszy uniwersytet w Polsce'),
(4, 'Stanford University', 'Stanford', 'USA', 'Ameryka Północna', '450 Serra Mall, Stanford, CA', 'Znany z badań w dziedzinie IT i przedsiębiorczości'),
(8, 'Uniwersytet wilieński', 'Wilno', 'Litwa', 'Europa', 'Gedimino pr.', 'Najstarszy uniwersytet');

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
(1, 'Jan', 'Kowalski', 'jan.kowalski@example.com', 'haslo123', 'T', 'Wykładowca matematyki'),
(2, 'Anna', 'Nowak', 'anna.nowak@example.com', 'haslo456', 'T', 'Wykładowca fizyki'),
(3, 'Piotr', 'Wiśniewski', 'piotr.wisniewski@example.com', 'haslo789', 'T', 'Wykładowca informatyki'),
(4, 'Maria', 'Wójcik', 'maria.wojcik@example.com', 'haslo101', 'T', 'Wykładowca języka polskiego'),
(5, 'Tomasz', 'Kaczmarek', 'tomasz.kaczmarek@example.com', 'haslo202', 'T', 'Wykładowca historii'),
(6, 'Katarzyna', 'Szymańska', 'katarzyna.szymanska@example.com', 'haslo303', 'T', 'Wykładowca biologii'),
(7, 'Jakub', 'Zieliński', 'jakub.zielinski@example.com', 'haslo404', 'T', 'Wykładowca chemii'),
(8, 'Elżbieta', 'Lewandowska', 'elzbieta.lewandowska@example.com', 'haslo505', 'T', 'Wykładowca ekonomii'),
(9, 'Marek', 'Dąbrowski', 'marek.dabrowski@example.com', 'haslo606', 'T', 'Wykładowca geografii'),
(10, 'Zofia', 'Krawczyk', 'zofia.krawczyk@example.com', 'haslo707', 'T', 'Wykładowca sztuki');

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
  ADD KEY `fk_wykladowca_grupy` (`id_wykladowcy`);

--
-- Indexes for table `tGrupyStudenci`
--
ALTER TABLE `tGrupyStudenci`
  ADD PRIMARY KEY (`id_grupy`,`id_studenta`),
  ADD KEY `fk_grupy_studenci_studenci` (`id_studenta`);

--
-- Indexes for table `tPrzedmioty`
--
ALTER TABLE `tPrzedmioty`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tStudenci`
--
ALTER TABLE `tStudenci`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `email` (`email`);

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
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tAdministratorzy`
--
ALTER TABLE `tAdministratorzy`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tGrupy`
--
ALTER TABLE `tGrupy`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `tPrzedmioty`
--
ALTER TABLE `tPrzedmioty`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `tStudenci`
--
ALTER TABLE `tStudenci`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `tUczelnie`
--
ALTER TABLE `tUczelnie`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `tWykladowcy`
--
ALTER TABLE `tWykladowcy`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tGrupy`
--
ALTER TABLE `tGrupy`
  ADD CONSTRAINT `fk_wykladowca_grupy` FOREIGN KEY (`id_wykladowcy`) REFERENCES `tWykladowcy` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tGrupyStudenci`
--
ALTER TABLE `tGrupyStudenci`
  ADD CONSTRAINT `fk_grupy_studenci_grupy` FOREIGN KEY (`id_grupy`) REFERENCES `tGrupy` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_grupy_studenci_studenci` FOREIGN KEY (`id_studenta`) REFERENCES `tStudenci` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
