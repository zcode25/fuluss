-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 14 Feb 2022 pada 13.30
-- Versi server: 10.4.18-MariaDB
-- Versi PHP: 8.0.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `budget_tracker`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `budget`
--

CREATE TABLE `budget` (
  `code_budget` char(5) NOT NULL,
  `id_user` char(5) NOT NULL,
  `money` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktur dari tabel `category_expense`
--

CREATE TABLE `category_expense` (
  `code_category_expense` char(5) NOT NULL,
  `name_category_expense` varchar(50) NOT NULL,
  `icon_category_expense` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `category_expense`
--

INSERT INTO `category_expense` (`code_category_expense`, `name_category_expense`, `icon_category_expense`) VALUES
('D0001', 'Food & drink', 'utensils'),
('D0002', 'Shop', 'shopping-cart'),
('D0003', 'Home supplies', 'home'),
('D0004', 'Transportation', 'bus'),
('D0005', 'Vehicle', 'car'),
('D0006', 'Entertainment', 'ticket-alt'),
('D0007', 'Computer', 'desktop'),
('D0008', 'Insurance', 'car-crash'),
('D0009', 'Tax', 'receipt'),
('D0010', 'Loan', 'money-check-alt'),
('D0011', 'Fine', 'exclamation-circle'),
('D0012', 'Bill', 'money-bill'),
('D0013', 'Child allowance', 'child'),
('D0014', 'Investment', 'chart-line'),
('D0015', 'Savings', 'coins'),
('D0016', 'Alms', 'donate'),
('D0017', 'Internet', 'wifi'),
('D0018', 'Travel', 'plane-departure'),
('D0019', 'Business', 'dollar-sign'),
('D0020', 'Lost money', 'search-dollar'),
('D0021', 'Goal', 'bullseye');

-- --------------------------------------------------------

--
-- Struktur dari tabel `category_income`
--

CREATE TABLE `category_income` (
  `code_category_income` char(5) NOT NULL,
  `name_category_income` varchar(50) NOT NULL,
  `icon_category_income` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `category_income`
--

INSERT INTO `category_income` (`code_category_income`, `name_category_income`, `icon_category_income`) VALUES
('C0001', 'Salary', 'briefcase'),
('C0002', 'Sale', 'box'),
('C0003', 'Investment', 'chart-line'),
('C0004', 'Loan', 'money-check-alt'),
('C0005', 'Savings', 'coins'),
('C0006', 'Business', 'dollar-sign');

-- --------------------------------------------------------

--
-- Struktur dari tabel `expense`
--

CREATE TABLE `expense` (
  `code_expense` char(5) NOT NULL,
  `date_expense` date NOT NULL,
  `code_category_expense` char(5) NOT NULL,
  `note_expense` varchar(100) NOT NULL,
  `budget_expense` int(11) NOT NULL,
  `id_user` char(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktur dari tabel `goal`
--

CREATE TABLE `goal` (
  `code_goal` char(5) NOT NULL,
  `name_goal` varchar(50) NOT NULL,
  `date_goal` date NOT NULL,
  `note_goal` varchar(100) NOT NULL,
  `amount_goal` int(11) NOT NULL,
  `amount2_goal` int(11) NOT NULL,
  `status_goal` enum('Achieved','Not Achieved') NOT NULL,
  `id_user` char(5) NOT NULL,
  `code_expense` char(5) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktur dari tabel `income`
--

CREATE TABLE `income` (
  `code_income` char(5) NOT NULL,
  `date_income` date NOT NULL,
  `code_category_income` char(5) NOT NULL,
  `note_income` varchar(100) NOT NULL,
  `budget_income` int(11) NOT NULL,
  `id_user` char(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktur dari tabel `loan`
--

CREATE TABLE `loan` (
  `code_loan` char(5) NOT NULL,
  `note_loan` varchar(100) NOT NULL,
  `amount_loan` int(11) NOT NULL,
  `date_loan` date NOT NULL,
  `due_date_loan` date NOT NULL,
  `name_loan` varchar(50) NOT NULL,
  `tel_loan` char(15) NOT NULL,
  `category_loan` enum('Give','Accept') NOT NULL,
  `status_loan` enum('Paid Off','Not Yet Paid Off') NOT NULL,
  `id_user` char(5) NOT NULL,
  `code_income` char(5) DEFAULT NULL,
  `code_expense` char(5) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktur dari tabel `user`
--

CREATE TABLE `user` (
  `id_user` char(5) NOT NULL,
  `name_user` varchar(50) NOT NULL,
  `email_user` varchar(50) NOT NULL,
  `tel_user` char(15) NOT NULL,
  `pass_user` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `budget`
--
ALTER TABLE `budget`
  ADD PRIMARY KEY (`code_budget`),
  ADD KEY `id_user` (`id_user`);

--
-- Indeks untuk tabel `category_expense`
--
ALTER TABLE `category_expense`
  ADD PRIMARY KEY (`code_category_expense`);

--
-- Indeks untuk tabel `category_income`
--
ALTER TABLE `category_income`
  ADD PRIMARY KEY (`code_category_income`);

--
-- Indeks untuk tabel `expense`
--
ALTER TABLE `expense`
  ADD PRIMARY KEY (`code_expense`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `code_category_expense` (`code_category_expense`);

--
-- Indeks untuk tabel `goal`
--
ALTER TABLE `goal`
  ADD PRIMARY KEY (`code_goal`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `code_expense` (`code_expense`);

--
-- Indeks untuk tabel `income`
--
ALTER TABLE `income`
  ADD PRIMARY KEY (`code_income`),
  ADD KEY `category_income` (`code_category_income`),
  ADD KEY `id_user` (`id_user`);

--
-- Indeks untuk tabel `loan`
--
ALTER TABLE `loan`
  ADD PRIMARY KEY (`code_loan`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `code_income` (`code_income`),
  ADD KEY `code_expense` (`code_expense`);

--
-- Indeks untuk tabel `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `email_user` (`email_user`);

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `budget`
--
ALTER TABLE `budget`
  ADD CONSTRAINT `budget_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `expense`
--
ALTER TABLE `expense`
  ADD CONSTRAINT `expense_ibfk_1` FOREIGN KEY (`code_category_expense`) REFERENCES `category_expense` (`code_category_expense`) ON UPDATE CASCADE,
  ADD CONSTRAINT `expense_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `goal`
--
ALTER TABLE `goal`
  ADD CONSTRAINT `goal_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON UPDATE CASCADE,
  ADD CONSTRAINT `goal_ibfk_2` FOREIGN KEY (`code_expense`) REFERENCES `expense` (`code_expense`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `income`
--
ALTER TABLE `income`
  ADD CONSTRAINT `income_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON UPDATE CASCADE,
  ADD CONSTRAINT `income_ibfk_2` FOREIGN KEY (`code_category_income`) REFERENCES `category_income` (`code_category_income`) ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `loan`
--
ALTER TABLE `loan`
  ADD CONSTRAINT `loan_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON UPDATE CASCADE,
  ADD CONSTRAINT `loan_ibfk_2` FOREIGN KEY (`code_income`) REFERENCES `income` (`code_income`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `loan_ibfk_3` FOREIGN KEY (`code_expense`) REFERENCES `expense` (`code_expense`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
