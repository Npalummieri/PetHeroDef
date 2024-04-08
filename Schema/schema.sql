-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 04, 2024 at 01:04 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pethero`
--

-- --------------------------------------------------------

--
-- Table structure for table `booking`
--

CREATE TABLE `booking` (
  `id` int(11) NOT NULL,
  `bookCode` varchar(255) NOT NULL,
  `ownerCode` varchar(255) NOT NULL,
  `keeperCode` varchar(255) NOT NULL,
  `petCode` varchar(255) NOT NULL,
  `initDate` date NOT NULL,
  `endDate` date NOT NULL,
  `status` enum('confirmed','cancelled','rejected','pending','finished','paidup') NOT NULL,
  `totalPrice` float NOT NULL,
  `totalDays` int(11) DEFAULT NULL,
  `visitPerDay` int(11) NOT NULL,
  `timeStamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `conversation`
--

CREATE TABLE `conversation` (
  `idCon` int(10) UNSIGNED NOT NULL,
  `codeConv` int(12) UNSIGNED NOT NULL,
  `keeperCode` varchar(255) NOT NULL,
  `ownerCode` varchar(255) NOT NULL,
  `timestamp` timestamp(6) NOT NULL DEFAULT current_timestamp(6),
  `status` enum('active','inactive','suspended','') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `coupon`
--

CREATE TABLE `coupon` (
  `id` int(11) NOT NULL,
  `couponCode` varchar(255) NOT NULL,
  `bookCode` varchar(255) NOT NULL,
  `price` float NOT NULL,
  `status` enum('paidup','pending','cancelled','finished') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `keeper`
--

CREATE TABLE `keeper` (
  `id` int(11) NOT NULL,
  `keeperCode` varchar(255) NOT NULL,
  `email` varchar(50) CHARACTER SET armscii8 COLLATE armscii8_bin NOT NULL,
  `username` varchar(12) NOT NULL,
  `password` varchar(255) NOT NULL,
  `status` enum('active','inactive','suspended','') NOT NULL,
  `name` varchar(20) NOT NULL,
  `lastname` varchar(20) NOT NULL,
  `dni` int(16) UNSIGNED NOT NULL,
  `pfp` varchar(255) DEFAULT NULL,
  `typeCare` enum('big','medium','small','') NOT NULL,
  `price` int(10) UNSIGNED NOT NULL,
  `typePet` varchar(255) DEFAULT NULL,
  `score` float UNSIGNED DEFAULT NULL,
  `bio` varchar(180) DEFAULT NULL,
  `initDate` date DEFAULT NULL,
  `endDate` date DEFAULT NULL,
  `visitPerDay` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `message`
--

CREATE TABLE `message` (
  `idMsg` int(11) NOT NULL,
  `codeSender` varchar(255) NOT NULL,
  `codeReceiver` varchar(255) NOT NULL,
  `msgText` varchar(255) NOT NULL,
  `chatCode` int(12) UNSIGNED NOT NULL,
  `timeStamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `seen` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `owner`
--

CREATE TABLE `owner` (
  `id` int(3) NOT NULL,
  `ownerCode` varchar(255) NOT NULL,
  `email` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `username` varchar(12) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `password` varchar(255) NOT NULL,
  `status` enum('active','inactive','suspended','') NOT NULL,
  `name` varchar(20) NOT NULL,
  `lastname` varchar(20) NOT NULL,
  `dni` int(16) NOT NULL,
  `pfp` varchar(255) DEFAULT NULL,
  `bio` varchar(180) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pet`
--

CREATE TABLE `pet` (
  `id` int(11) NOT NULL,
  `petCode` varchar(255) NOT NULL,
  `name` varchar(30) CHARACTER SET armscii8 COLLATE armscii8_bin NOT NULL,
  `pfp` varchar(255) DEFAULT NULL,
  `ownerCode` varchar(255) NOT NULL,
  `size` enum('small','medium','big') NOT NULL,
  `breed` varchar(255) CHARACTER SET armscii8 COLLATE armscii8_bin DEFAULT NULL,
  `vaccPlan` varchar(255) DEFAULT NULL,
  `video` varchar(255) DEFAULT NULL,
  `typePet` enum('cat','dog') NOT NULL,
  `age` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `review`
--

CREATE TABLE `review` (
  `keeperCode` varchar(255) NOT NULL,
  `ownerCode` varchar(255) NOT NULL,
  `comment` varchar(150) NOT NULL,
  `score` int(11) NOT NULL,
  `timeStamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `reviewCode` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `booking`
--
ALTER TABLE `booking`
  ADD PRIMARY KEY (`bookCode`),
  ADD UNIQUE KEY `id` (`id`) USING BTREE,
  ADD KEY `keeperCode` (`keeperCode`),
  ADD KEY `petCode` (`petCode`),
  ADD KEY `ownerCode` (`ownerCode`) USING BTREE;

--
-- Indexes for table `conversation`
--
ALTER TABLE `conversation`
  ADD PRIMARY KEY (`codeConv`) USING BTREE,
  ADD UNIQUE KEY `idCon` (`idCon`) USING BTREE,
  ADD KEY `FK_conv_keeperCode` (`keeperCode`),
  ADD KEY `FK_conv_ownerCode` (`ownerCode`);

--
-- Indexes for table `coupon`
--
ALTER TABLE `coupon`
  ADD PRIMARY KEY (`couponCode`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `FK_coupon_bookCode` (`bookCode`);

--
-- Indexes for table `keeper`
--
ALTER TABLE `keeper`
  ADD PRIMARY KEY (`keeperCode`),
  ADD UNIQUE KEY `id` (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`idMsg`) USING BTREE,
  ADD KEY `chatCode` (`chatCode`);

--
-- Indexes for table `owner`
--
ALTER TABLE `owner`
  ADD PRIMARY KEY (`ownerCode`),
  ADD UNIQUE KEY `id` (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `dni` (`dni`);

--
-- Indexes for table `pet`
--
ALTER TABLE `pet`
  ADD PRIMARY KEY (`petCode`),
  ADD UNIQUE KEY `id` (`id`),
  ADD UNIQUE KEY `video` (`video`),
  ADD UNIQUE KEY `vaccPlan` (`vaccPlan`),
  ADD UNIQUE KEY `pfp` (`pfp`),
  ADD KEY `FK ownerCode` (`ownerCode`);

--
-- Indexes for table `review`
--
ALTER TABLE `review`
  ADD PRIMARY KEY (`reviewCode`) USING BTREE,
  ADD KEY `codeKeeper` (`keeperCode`),
  ADD KEY `codeOwner` (`ownerCode`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `booking`
--
ALTER TABLE `booking`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `conversation`
--
ALTER TABLE `conversation`
  MODIFY `idCon` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `coupon`
--
ALTER TABLE `coupon`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `keeper`
--
ALTER TABLE `keeper`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `message`
--
ALTER TABLE `message`
  MODIFY `idMsg` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `owner`
--
ALTER TABLE `owner`
  MODIFY `id` int(3) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pet`
--
ALTER TABLE `pet`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `booking`
--
ALTER TABLE `booking`
  ADD CONSTRAINT `FK_booking_keeperCode` FOREIGN KEY (`keeperCode`) REFERENCES `keeper` (`keeperCode`),
  ADD CONSTRAINT `FK_booking_ownerCode` FOREIGN KEY (`ownerCode`) REFERENCES `owner` (`ownerCode`),
  ADD CONSTRAINT `FK_booking_petCode` FOREIGN KEY (`petCode`) REFERENCES `pet` (`petCode`);

--
-- Constraints for table `conversation`
--
ALTER TABLE `conversation`
  ADD CONSTRAINT `FK_conv_keeperCode` FOREIGN KEY (`keeperCode`) REFERENCES `keeper` (`keeperCode`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_conv_ownerCode` FOREIGN KEY (`ownerCode`) REFERENCES `owner` (`ownerCode`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `coupon`
--
ALTER TABLE `coupon`
  ADD CONSTRAINT `FK_coupon_bookCode` FOREIGN KEY (`bookCode`) REFERENCES `booking` (`bookCode`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `message`
--
ALTER TABLE `message`
  ADD CONSTRAINT `FK_msg_chatCode` FOREIGN KEY (`chatCode`) REFERENCES `conversation` (`codeConv`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `pet`
--
ALTER TABLE `pet`
  ADD CONSTRAINT `FK ownerCode` FOREIGN KEY (`ownerCode`) REFERENCES `owner` (`ownerCode`);

--
-- Constraints for table `review`
--
ALTER TABLE `review`
  ADD CONSTRAINT `FK_review_keeperCode` FOREIGN KEY (`keeperCode`) REFERENCES `keeper` (`keeperCode`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_review_ownerCode` FOREIGN KEY (`ownerCode`) REFERENCES `owner` (`ownerCode`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
