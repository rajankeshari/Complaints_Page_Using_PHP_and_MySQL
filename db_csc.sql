-- phpMyAdmin SQL Dump
-- version 4.2.7.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Nov 01, 2021 at 03:19 PM
-- Server version: 5.5.39
-- PHP Version: 5.4.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `db_csc`
--

-- --------------------------------------------------------

--
-- Table structure for table `location_name`
--

DROP TABLE IF EXISTS `location_name`;
CREATE TABLE IF NOT EXISTS `location_name` (
  `id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `typeID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `location_name`
--

INSERT INTO `location_name` (`id`, `name`, `typeID`) VALUES
(1, 'Amber Hostel', 1),
(2, 'Diamond Hostel', 1),
(3, 'Emerald Hostel', 1),
(4, 'International Hostel', 1),
(5, 'Jasper Hostel', 1),
(6, 'JRF Hostel', 1),
(7, 'Opal Hostel', 1),
(8, 'Rosaline Hostel', 1),
(9, 'Ruby', 1),
(10, 'Rubby Annex', 1),
(11, 'Sapphire Hostel', 1),
(12, 'Topaz Hostel', 1),
(13, 'NA for Student Contingency', 2),
(14, 'Department', 2),
(15, 'Office', 2),
(16, 'Residence', 2),
(17, 'Shanti Bhawan', 2),
(18, 'SAH', 2),
(19, 'EDC', 2),
(20, 'Others', 2);

-- --------------------------------------------------------

--
-- Table structure for table `location_type`
--

DROP TABLE IF EXISTS `location_type`;
CREATE TABLE IF NOT EXISTS `location_type` (
  `id` int(11) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `location_type`
--

INSERT INTO `location_type` (`id`, `type`) VALUES
(1, 'Hostel'),
(2, 'Non Hostel');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
