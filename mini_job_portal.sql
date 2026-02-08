-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 19, 2025 at 08:39 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mini_job_portal`
--

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `company` varchar(255) NOT NULL,
  `location` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `posted_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jobs`
--

INSERT INTO `jobs` (`id`, `title`, `company`, `location`, `description`, `posted_date`) VALUES
(2, 'Barber', 'Brian  Barbershop', 'jln. Pangkas No.1', 'bisa cukur model apa aja', '2025-04-23'),
(3, 'Tukang Listrik', 'Tukang Listrik', 'Tenjolaya, Tapos 1', 'Bisa benerin Listrik? bisa gerakin Listrik? Kerja disini aja', '2025-04-23'),
(4, 'Receptionist', 'Fahru Shop of Power', 'Washington Dc.', 'ini toko buat para-para Bolang jadi perlu orang bilingual buat ngmong sama kustomer', '2025-04-23'),
(5, 'Grave Digger', 'Mystery Shack', 'Gravity Falls', 'whatever cause if you could dig you are accepted!', '2025-04-23'),
(6, 'Waiter', 'OFC (Obama Fried Chicken)', 'Jln. kaji No.10', 'kita jual ayam, ayam ter enak se Asia tenggara', '2025-04-23'),
(7, 'Teacher', 'Sekolah Impian', 'Tenjolaya,', 'Perlu Guru yang Pintar Teknologi dan Al-Quran', '2025-04-24'),
(8, 'looker', 'CIA Secret Service', 'Washington Dc.', 'ehm....', '2025-04-24'),
(9, 'Spy', 'KGB sekretnaya sluzhba', 'Moscow, Russia', 'ehm....', '2025-04-24');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
