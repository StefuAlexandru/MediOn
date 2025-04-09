-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: mysql_db
-- Generation Time: May 23, 2024 at 07:33 AM
-- Server version: 8.3.0
-- PHP Version: 8.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `Clients`
--

-- --------------------------------------------------------

--
-- Table structure for table `clients`
--

CREATE TABLE `clients` (
  `id` int NOT NULL,
  `firstName` varchar(80) NOT NULL,
  `lastName` varchar(80) NOT NULL,
  `userName` varchar(80) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(150) NOT NULL,
  `phoneNumber` varchar(20) NOT NULL,
  `admin` tinyint(1) NOT NULL,
  `gaveLike` tinyint(1) NOT NULL,
  `subscription` datetime(6) DEFAULT NULL,
  `profilePicture` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `clients`
--

INSERT INTO `clients` (`id`, `firstName`, `lastName`, `userName`, `email`, `password`, `phoneNumber`, `admin`, `gaveLike`, `subscription`, `profilePicture`) VALUES
(1, 'alex', 'stefu', 'alexstefu11', 'alexstefu112@yahoo.com', 'UGFyb2xhMTIzIQ==', '0770196154', 0, 1, NULL, '664499746afd1.jpg'),
(2, 'admin', 'admin', 'admin', 'admin@yahoo.com', 'cm9vdA==', '0000000000', 1, 0, NULL, 'None'),
(3, 'billy', 'bober', 'theonlybober', 'theonlybober@yahoo.ro', 'MTIzNA==', '0765675289', 0, 0, NULL, 'None'),
(4, 'gheo', 'zama', 'gheorghe', 'zama@mamaliga.com', 'Z2hlbw==', '0743578778', 0, 0, NULL, 'None'),
(5, 'flori', 'panaintescu', 'foi4foi', 'flori645@gmail.ro', 'NGZvaQ==', '0786756273', 0, 0, NULL, 'None');

-- --------------------------------------------------------

--
-- Table structure for table `like_share`
--

CREATE TABLE `like_share` (
  `id` int NOT NULL,
  `likes` int NOT NULL,
  `shares` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `like_share`
--

INSERT INTO `like_share` (`id`, `likes`, `shares`) VALUES
(1, 7563, 1234);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `like_share`
--
ALTER TABLE `like_share`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `clients`
--
ALTER TABLE `clients`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `like_share`
--
ALTER TABLE `like_share`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
