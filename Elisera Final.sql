-- phpMyAdmin SQL Dump
-- version 4.0.10.12
-- http://www.phpmyadmin.net
--
-- Host: 127.5.179.130:3306
-- Generation Time: Jun 09, 2016 at 04:24 PM
-- Server version: 5.5.45
-- PHP Version: 5.3.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `elisera`
--
CREATE DATABASE IF NOT EXISTS `elisera` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `elisera`;

-- --------------------------------------------------------

--
-- Table structure for table `Customer`
--

CREATE TABLE IF NOT EXISTS `Customer` (
  `customer_id` int(11) NOT NULL AUTO_INCREMENT,
  `wallet_addr` varchar(30) NOT NULL,
  `customer_name` varchar(35) NOT NULL,
  `email` varchar(50) NOT NULL,
  `mobile_num` int(8) NOT NULL,
  `picture_url` mediumtext NOT NULL,
  PRIMARY KEY (`customer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `Exchange`
--

CREATE TABLE IF NOT EXISTS `Exchange` (
  `exchange_id` int(11) NOT NULL AUTO_INCREMENT,
  `cost` int(11) NOT NULL,
  `point_id` int(11) NOT NULL,
  PRIMARY KEY (`exchange_id`),
  KEY `point_id` (`point_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `Point`
--

CREATE TABLE IF NOT EXISTS `Point` (
  `point_id` int(11) NOT NULL AUTO_INCREMENT,
  `points` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  PRIMARY KEY (`point_id`),
  KEY `customer_id` (`customer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `Purchase`
--

CREATE TABLE IF NOT EXISTS `Purchase` (
  `purchase_id` int(11) NOT NULL AUTO_INCREMENT,
  `cost` decimal(11,2) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `room_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  PRIMARY KEY (`purchase_id`),
  KEY `room_id` (`room_id`),
  KEY `customer_id` (`customer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `Reservation`
--

CREATE TABLE IF NOT EXISTS `Reservation` (
  `reservation_id` int(11) NOT NULL AUTO_INCREMENT,
  `start_datetime` datetime NOT NULL,
  `end_datetime` datetime NOT NULL,
  `customer_id` int(11) NOT NULL,
  `room_id` int(11) NOT NULL,
  PRIMARY KEY (`reservation_id`),
  KEY `customer_id` (`customer_id`),
  KEY `room_id` (`room_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `Room`
--

CREATE TABLE IF NOT EXISTS `Room` (
  `room_id` int(11) NOT NULL AUTO_INCREMENT,
  `cost` decimal(11,2) NOT NULL,
  `size` int(2) NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `reservation_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`room_id`),
  KEY `customer_id` (`customer_id`),
  KEY `reservation_id` (`reservation_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1505 ;

--
-- Dumping data for table `Room`
--

INSERT INTO `Room` (`room_id`, `cost`, `size`, `customer_id`, `reservation_id`) VALUES
(1500, '325.00', 3, NULL, NULL),
(1501, '250.00', 2, NULL, NULL),
(1502, '500.00', 4, NULL, NULL),
(1503, '600.00', 4, NULL, NULL),
(1504, '75.00', 1, NULL, NULL);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `Exchange`
--
ALTER TABLE `Exchange`
  ADD CONSTRAINT `Exchange_ibfk_1` FOREIGN KEY (`point_id`) REFERENCES `Point` (`point_id`);

--
-- Constraints for table `Point`
--
ALTER TABLE `Point`
  ADD CONSTRAINT `Point_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `Customer` (`customer_id`);

--
-- Constraints for table `Purchase`
--
ALTER TABLE `Purchase`
  ADD CONSTRAINT `Purchase_ibfk_1` FOREIGN KEY (`room_id`) REFERENCES `Room` (`room_id`),
  ADD CONSTRAINT `Purchase_ibfk_2` FOREIGN KEY (`customer_id`) REFERENCES `Customer` (`customer_id`);

--
-- Constraints for table `Reservation`
--
ALTER TABLE `Reservation`
  ADD CONSTRAINT `Reservation_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `Customer` (`customer_id`),
  ADD CONSTRAINT `Reservation_ibfk_2` FOREIGN KEY (`room_id`) REFERENCES `Room` (`room_id`);

--
-- Constraints for table `Room`
--
ALTER TABLE `Room`
  ADD CONSTRAINT `Room_ibfk_2` FOREIGN KEY (`reservation_id`) REFERENCES `Reservation` (`reservation_id`),
  ADD CONSTRAINT `Room_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `Customer` (`customer_id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
