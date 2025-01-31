-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Jan 23, 2025 at 10:59 AM
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
  `id_kierunku` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tGrupy`
--

INSERT INTO `tGrupy` (`ID`, `rok`, `nazwa`, `id_wykladowcy`, `id_przedmiotu`, `id_kierunku`) VALUES
(71, '2024', 'Grupa I', 31, 37, 42),
(73, '2025', 'Brak', 29, 47, 42),
(74, '2025', 'Brak', 29, 49, 42),
(75, '2025', 'Brak', 35, 39, 43);

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
(73, 54),
(74, 54),
(73, 55),
(73, 56),
(74, 56),
(73, 57),
(74, 57),
(73, 58),
(73, 59),
(74, 59),
(73, 60),
(73, 61),
(73, 62),
(74, 62),
(75, 62),
(75, 63),
(75, 64),
(75, 65),
(75, 66),
(75, 67);

-- --------------------------------------------------------

--
-- Table structure for table `tKierunki`
--

CREATE TABLE `tKierunki` (
  `ID` int NOT NULL,
  `nazwa` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `uwagi` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tKierunki`
--

INSERT INTO `tKierunki` (`ID`, `nazwa`, `uwagi`) VALUES
(42, 'Informatyka', 'Niema'),
(43, 'Ekonomia', 'Niema\r\n');

-- --------------------------------------------------------

--
-- Table structure for table `tKierunkiPrzedmioty`
--

CREATE TABLE `tKierunkiPrzedmioty` (
  `id_kierunku` int NOT NULL,
  `id_przedmiotu` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tKierunkiPrzedmioty`
--

INSERT INTO `tKierunkiPrzedmioty` (`id_kierunku`, `id_przedmiotu`) VALUES
(42, 34),
(42, 35),
(42, 36),
(42, 37),
(43, 38),
(43, 39),
(43, 40),
(43, 41),
(42, 42),
(42, 46),
(42, 47),
(42, 48);

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
(377, 106, '9', '2025-01-23 12:50:58', '2025-01-23 12:50:58', 0, 0.00),
(378, 106, '8', '2025-01-23 12:50:58', '2025-01-23 12:50:58', 1, 10.00),
(379, 106, '3', '2025-01-23 12:50:58', '2025-01-23 12:50:58', 0, 0.00),
(380, 106, '6', '2025-01-23 12:50:58', '2025-01-23 12:55:01', 0, 0.00),
(381, 107, '10', '2025-01-23 12:51:28', '2025-01-23 12:51:41', 1, 10.00),
(382, 107, '5', '2025-01-23 12:51:28', '2025-01-23 12:51:41', 0, 0.00),
(383, 108, '23', '2025-01-23 12:52:20', '2025-01-23 12:52:20', 0, 0.00),
(384, 108, '10', '2025-01-23 12:52:20', '2025-01-23 12:52:20', 0, 0.00),
(385, 108, '12', '2025-01-23 12:52:20', '2025-01-23 12:52:20', 0, 0.00),
(386, 108, '24', '2025-01-23 12:52:20', '2025-01-23 12:52:20', 1, 10.00),
(387, 108, '45', '2025-01-23 12:52:20', '2025-01-23 12:52:20', 0, 0.00),
(388, 108, '64', '2025-01-23 12:52:20', '2025-01-23 12:52:20', 0, 0.00),
(389, 109, '11', '2025-01-23 12:53:39', '2025-01-23 12:53:39', 1, 10.00),
(390, 109, '10', '2025-01-23 12:53:39', '2025-01-23 12:53:39', 0, 0.00),
(391, 109, '17', '2025-01-23 12:53:39', '2025-01-23 12:53:39', 1, 10.00),
(392, 109, '8', '2025-01-23 12:53:39', '2025-01-23 12:53:39', 0, 0.00),
(393, 110, 'Graf o 5 wierzchołkach i 4 krawędziach bez cykli', '2025-01-23 12:54:31', '2025-01-23 12:54:31', 1, 10.00),
(394, 110, 'Graf o 6 wierzchołkach i 5 krawędziach, zawierający cykl', '2025-01-23 12:54:31', '2025-01-23 12:54:31', 0, 0.00),
(395, 110, 'Graf o 3 wierzchołkach i 2 krawędziach bez cykli', '2025-01-23 12:54:31', '2025-01-23 12:54:31', 1, 10.00),
(396, 110, 'Graf o 4 wierzchołkach i 6 krawędziach', '2025-01-23 12:54:31', '2025-01-23 12:54:31', 0, 0.00),
(397, 111, 'Indeks cen konsumpcyjnych (CPI)', '2025-01-23 12:57:40', '2025-01-23 12:57:40', 1, 5.00),
(398, 111, 'Produkt Krajowy Brutto (PKB)', '2025-01-23 12:57:40', '2025-01-23 12:57:40', 0, 0.00),
(399, 111, 'Deflator PKB', '2025-01-23 12:57:40', '2025-01-23 12:57:40', 1, 5.00),
(400, 111, 'Stopa bezrobocia', '2025-01-23 12:57:40', '2025-01-23 12:57:40', 0, 0.00),
(401, 112, 'Podatki', '2025-01-23 12:58:36', '2025-01-23 12:58:36', 1, 5.00),
(402, 112, 'Wydatki rządowe', '2025-01-23 12:58:36', '2025-01-23 12:58:36', 1, 5.00),
(403, 112, ' Stopy procentowe', '2025-01-23 12:58:36', '2025-01-23 12:58:36', 0, 0.00),
(404, 112, ' Interwencje walutowe', '2025-01-23 12:58:36', '2025-01-23 12:58:36', 0, 0.00);

-- --------------------------------------------------------

--
-- Table structure for table `tPrzedmioty`
--

CREATE TABLE `tPrzedmioty` (
  `ID` int NOT NULL,
  `nazwa` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `uwagi` text,
  `id_kierunku` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tPrzedmioty`
--

INSERT INTO `tPrzedmioty` (`ID`, `nazwa`, `uwagi`, `id_kierunku`) VALUES
(34, 'Algorytmy i struktury danych I', 'Niema', NULL),
(35, 'Bazy danych', 'Niema', NULL),
(36, 'Systemy operacyjne', 'Niema', NULL),
(37, 'Sieci komputerowe', 'Niema', NULL),
(38, 'Mikroekonomia', 'Niema', NULL),
(39, 'Makroekonomia', 'Niema', NULL),
(40, 'Zarządzanie finansami', 'Niema', NULL),
(41, 'Prawo gospodarcze', 'Niema', NULL),
(42, 'Statystyka', 'Niema', NULL),
(46, 'Programowanie w języku C', 'Niema', NULL),
(47, 'Matematyka dyskretna', 'Niema', NULL),
(48, 'Algorytmy i struktury danych II', 'Niema', NULL),
(49, 'Pracownia dyplomowa I', 'Niema', NULL),
(50, 'Pracownia dyplomowa II', 'Niema', NULL);

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
(106, 47, 29, 'Wielokrotnego wyboru', 'Ile elementów ma zbiór potęgowy zbioru zawierającego 3 elementy?', '2025-01-23 12:50:58', '2025-01-23 12:50:58'),
(107, 47, 29, 'Wielokrotnego wyboru', 'Jaka jest liczba krawędzi w grafie pełnym o 5 wierzchołkach?', '2025-01-23 12:51:28', '2025-01-23 12:51:28'),
(108, 47, 29, 'Wielokrotnego wyboru', 'Jaka jest liczba permutacji zbioru 4-elementowego?', '2025-01-23 12:52:20', '2025-01-23 12:52:20'),
(109, 47, 29, 'Wielokrotnego wyboru', 'Które liczby są liczbami pierwszymi?', '2025-01-23 12:53:39', '2025-01-23 12:53:39'),
(110, 47, 29, 'Wielokrotnego wyboru', ' Które z poniższych grafów są drzewami?', '2025-01-23 12:54:31', '2025-01-23 12:54:31'),
(111, 39, 35, 'Wielokrotnego wyboru', 'Które z poniższych wskaźników mierzą inflację?', '2025-01-23 12:57:40', '2025-01-23 12:57:40'),
(112, 39, 35, 'Wielokrotnego wyboru', 'Co zalicza się do instrumentów polityki fiskalnej?', '2025-01-23 12:58:36', '2025-01-23 12:58:36');

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
  `uwagi` text,
  `id_kierunku` int DEFAULT NULL,
  `rok` int DEFAULT NULL,
  `rocznik` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tStudenci`
--

INSERT INTO `tStudenci` (`ID`, `nr_albumu`, `imie`, `nazwisko`, `email`, `haslo`, `aktywny`, `uwagi`, `id_kierunku`, `rok`, `rocznik`) VALUES
(54, 87188, 'Modest', 'Semionow', 'modest.semionow@example.com', '$2y$10$7GZJaoCpeWa/41PcnYtXyevoIpSf9tbt802M3NMaoZpoiJ/.WmkQ.', 'N', 'Brak', 42, 2022, 3),
(55, 87270, 'Norbert', 'Gajewski', 'norbert.gajewski@example.com', '$2y$10$XRp2YXJMGVX7fYlval/pq.3UtCQ1yS61dk1cH9cOYRcmRNnmeYnOe', 'N', 'Brak', 42, 2022, 3),
(56, 83240, 'Edgar', 'Makowski', 'edgar.makowski@example.com', '$2y$10$je1yYYTsgTmUPVqugVD5mOf78rBKFRotvE06ew2CAitDzj/e/mFr.', 'N', 'Brak', 42, 2022, 3),
(57, 85109, 'Rafał', 'Miłosz', 'rafal.milosz@example.com', '$2y$10$6MiFz4xVOFehgZB5F8xSk.DOOnf8upaJyAmxBtkAfrp3U1YRVsko6', 'N', 'Brak', 42, 2022, 3),
(58, 86476, 'Bogusław', 'Pacyno', 'boguslaw.pacyno@example.com', '$2y$10$1QOWpbmeeWAH8R6e9HQnJugvw0mB6PasSmlpy2Ce9DsaOi0rz2ixy', 'N', 'Brak', 42, 2022, 3),
(59, 86899, 'Kamil', 'Pecela', 'kamil.pecela@example.com', '$2y$10$t1S2T5aBAP5PM/9EC8z.NOZaDQKdPVWGvoszTz9pdbVzGz0bxLyZy', 'N', 'Brak', 42, 2022, 3),
(60, 86562, 'Oskar', 'Slawinski', 'oskar.slawinski@example.com', '$2y$10$ehUCH60Sg.UiSCJugAsvquaYxZDXSTthsDoUPGkPiLk.Le/ICwSvq', 'N', 'Brak', 42, 2022, 3),
(61, 83244, 'Edwin', 'Zawadckij', 'edwin.zawadckij@example.com', '$2y$10$Gb.8UzJB8Fb5y1BJt70ocestkdjXKnq3VdW4H1MPRFLw5pk.G/Vpq', 'N', 'Brak', 42, 2022, 3),
(62, 85746, 'Justyna', 'Sirwinskaja', 'justyna.sirwinskaja@example.com', '$2y$10$CvPw15l2Mwdh..agsZYxROTl60wQ5a4tWNarWhsVUdh1EEKUVBopq', 'N', 'Brak', 42, 2022, 3),
(63, 87278, 'Gabriela', 'Kuckiewicz', 'gabriela.kuckiewicz@example.com', '$2y$10$ngQQbjqygFA1F.s5aUVYde80enhGp75NMMhUbp1j1NB5hWdR1zLNW', 'N', 'Brak', 43, 2022, 3),
(64, 86631, 'Diana', 'Lukoic', 'diana.lukoic@example.com', '$2y$10$VHcQmQpqlzrgKacVB8CqXeGmGv0lW6TyiTKUYN/71g5kLbmAwZjr6', 'N', 'Brak', 43, 2022, 3),
(65, 87280, 'Milena', 'Okunewicz', 'milena.okunewicz@example.com', '$2y$10$LhzXQrLdNPrMgmsR73wWr.Ix3baePiGLqLJM4Q3GwlED6PaI8BsBu', 'N', 'Brak', 43, 2022, 3),
(66, 85145, 'Agata', 'Stankiewicz', 'agata.stankiewicz@example.com', '$2y$10$C82g2HnwZrAbSXUyhu4FcuO8RiAs8CGXsV/YDubgtponk2DW9wqem', 'N', 'Brak', 43, 2022, 3),
(67, 87279, 'Elena', 'Szostak', 'elena.szostak@example.com', '$2y$10$KjLrlvOpP2jHOdFP0MppPePE0xMm4B/Rls3Sz3u/sx/Weyd/2X/ym', 'N', 'Brak', 43, 2022, 3);

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
-- Table structure for table `tWykladowcy`
--

CREATE TABLE `tWykladowcy` (
  `ID` int NOT NULL,
  `imie` varchar(100) NOT NULL,
  `nazwisko` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `haslo` varchar(255) NOT NULL,
  `aktywny` enum('T','N') NOT NULL DEFAULT 'N',
  `uwagi` text,
  `stopien` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tWykladowcy`
--

INSERT INTO `tWykladowcy` (`ID`, `imie`, `nazwisko`, `email`, `haslo`, `aktywny`, `uwagi`, `stopien`) VALUES
(29, 'Mariusz', 'Żynel', 'mariusz.zynel@example.com', '$2y$10$yE8fvR9dEZoIO0n8XmGM3uPB0aMn5MgjvfNvWqygOz3/0ySkT0SAO', 'N', 'Niema', 'dr.'),
(30, 'Regina', 'Laszakiewicz', 'regina.laszakiewicz@example.com', '$2y$10$tlGw.kMExpkaWE9kplR6BeAJj.danwbzMPd0detzPPK9B.gUWIFYa', 'N', 'Niema', 'dr.'),
(31, 'Mieczysław', 'Muraszkiewicz', 'mieczyslaw.muraszkiewicz@example.com', '$2y$10$Fw6PioNrsUgslP/n3TGA4eedxmFemV8ju/Fy1/efPAHtEhr10jJ1a', 'N', 'Niema', 'prof. dr. hab.'),
(33, 'Stanislaw', 'Pilzys', 'stanislaw.pilzys@example.com', '$2y$10$X2tHJ97DBijnPKcVAJwJs.chJoNPvMQ8lL5fVJL1sf/QBTSokqKLm', 'N', 'Niema', 'mgr.'),
(34, 'Anna', 'Grześ', 'anna.grzes@example.com', '$2y$10$a9vTBw7QDed9.6LYETV47OzzRwtHkVIJ.tIKvkhOzlpVCZi6p8rNa', 'N', 'Niema', 'dr. hab.'),
(35, 'Elżbieta', 'Majewska', 'elzbieta.majewska@example.com', '$2y$10$7c8VCMPMmJTVb6hlkSXlKuMsy73UfqOLvAxUqSJXkac0pIH4hSB2.', 'N', 'Niema', 'dr.');

-- --------------------------------------------------------

--
-- Table structure for table `twykladowcykierunki`
--

CREATE TABLE `twykladowcykierunki` (
  `ID` int NOT NULL,
  `id_wykladowcy` int DEFAULT NULL,
  `id_kierunku` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `twykladowcykierunki`
--

INSERT INTO `twykladowcykierunki` (`ID`, `id_wykladowcy`, `id_kierunku`) VALUES
(40, 29, 42),
(43, 30, 42),
(44, 30, 43),
(46, 31, 42),
(47, 33, 43),
(48, 34, 43),
(49, 35, 43);

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
  ADD KEY `FK_id_uczelni` (`id_kierunku`);

--
-- Indexes for table `tGrupyStudenci`
--
ALTER TABLE `tGrupyStudenci`
  ADD PRIMARY KEY (`id_grupy`,`id_studenta`),
  ADD KEY `fk_grupy_studenci_studenci` (`id_studenta`);

--
-- Indexes for table `tKierunki`
--
ALTER TABLE `tKierunki`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tKierunkiPrzedmioty`
--
ALTER TABLE `tKierunkiPrzedmioty`
  ADD PRIMARY KEY (`id_kierunku`,`id_przedmiotu`),
  ADD KEY `id_przedmiotu` (`id_przedmiotu`);

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
  ADD PRIMARY KEY (`ID`),
  ADD KEY `FK_Kierunek_Przedmiot` (`id_kierunku`);

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
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `fk_tStudenci_tKierunki` (`id_kierunku`);

--
-- Indexes for table `tTesty`
--
ALTER TABLE `tTesty`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `id_grupy` (`id_grupy`),
  ADD KEY `fk_wykladowca` (`id_wykladowcy`);

--
-- Indexes for table `tWykladowcy`
--
ALTER TABLE `tWykladowcy`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `twykladowcykierunki`
--
ALTER TABLE `twykladowcykierunki`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `fk_id_wykladowcy` (`id_wykladowcy`),
  ADD KEY `twykladowcykierunki_ibfk_2` (`id_kierunku`);

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
  MODIFY `ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=76;

--
-- AUTO_INCREMENT for table `tKierunki`
--
ALTER TABLE `tKierunki`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `tOdpowiedzi`
--
ALTER TABLE `tOdpowiedzi`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tPrzedmioty`
--
ALTER TABLE `tPrzedmioty`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `tPytania`
--
ALTER TABLE `tPytania`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=113;

--
-- AUTO_INCREMENT for table `tStudenci`
--
ALTER TABLE `tStudenci`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT for table `tTesty`
--
ALTER TABLE `tTesty`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tWykladowcy`
--
ALTER TABLE `tWykladowcy`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `twykladowcykierunki`
--
ALTER TABLE `twykladowcykierunki`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `tZalaczniki`
--
ALTER TABLE `tZalaczniki`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tGrupy`
--
ALTER TABLE `tGrupy`
  ADD CONSTRAINT `FK_id_przedmiotu` FOREIGN KEY (`id_przedmiotu`) REFERENCES `tPrzedmioty` (`ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_id_uczelni` FOREIGN KEY (`id_kierunku`) REFERENCES `tKierunki` (`ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_wykladowca_grupy` FOREIGN KEY (`id_wykladowcy`) REFERENCES `tWykladowcy` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tGrupyStudenci`
--
ALTER TABLE `tGrupyStudenci`
  ADD CONSTRAINT `fk_grupy_studenci_grupy` FOREIGN KEY (`id_grupy`) REFERENCES `tGrupy` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_grupy_studenci_studenci` FOREIGN KEY (`id_studenta`) REFERENCES `tStudenci` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tKierunkiPrzedmioty`
--
ALTER TABLE `tKierunkiPrzedmioty`
  ADD CONSTRAINT `tkierunkiprzedmioty_ibfk_1` FOREIGN KEY (`id_kierunku`) REFERENCES `tKierunki` (`ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `tkierunkiprzedmioty_ibfk_2` FOREIGN KEY (`id_przedmiotu`) REFERENCES `tPrzedmioty` (`ID`) ON DELETE CASCADE;

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
-- Constraints for table `tStudenci`
--
ALTER TABLE `tStudenci`
  ADD CONSTRAINT `FK_Studenci_Kierunki` FOREIGN KEY (`id_kierunku`) REFERENCES `tKierunki` (`ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_tStudenci_tKierunki` FOREIGN KEY (`id_kierunku`) REFERENCES `tKierunki` (`ID`) ON DELETE SET NULL;

--
-- Constraints for table `tTesty`
--
ALTER TABLE `tTesty`
  ADD CONSTRAINT `fk_wykladowca` FOREIGN KEY (`id_wykladowcy`) REFERENCES `tWykladowcy` (`ID`),
  ADD CONSTRAINT `ttesty_ibfk_1` FOREIGN KEY (`id_grupy`) REFERENCES `tGrupy` (`ID`) ON DELETE CASCADE;

--
-- Constraints for table `twykladowcykierunki`
--
ALTER TABLE `twykladowcykierunki`
  ADD CONSTRAINT `fk_id_wykladowcy` FOREIGN KEY (`id_wykladowcy`) REFERENCES `tWykladowcy` (`ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `twykladowcykierunki_ibfk_1` FOREIGN KEY (`id_wykladowcy`) REFERENCES `tWykladowcy` (`ID`),
  ADD CONSTRAINT `twykladowcykierunki_ibfk_2` FOREIGN KEY (`id_kierunku`) REFERENCES `tkierunki` (`ID`) ON DELETE CASCADE;

--
-- Constraints for table `tZalaczniki`
--
ALTER TABLE `tZalaczniki`
  ADD CONSTRAINT `tzalaczniki_ibfk_1` FOREIGN KEY (`id_pytania`) REFERENCES `tPytania` (`ID`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
