-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jan 14, 2026 at 10:54 PM
-- Server version: 8.4.3
-- PHP Version: 8.3.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `shopdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `PrdID` int NOT NULL,
  `PrdName` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `PrdPicture` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `PrdCategory` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `PrdDescription` varchar(250) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `PrdPrice` int DEFAULT NULL,
  `PrdQtyStock` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`PrdID`, `PrdName`, `PrdPicture`, `PrdCategory`, `PrdDescription`, `PrdPrice`, `PrdQtyStock`) VALUES
(1, 'LIFX', 'https://www.lifx.com.au/cdn/shop/products/00_LIFX-Color-Image-Hero-AU-E27.jpg?v=1620792659', 'Lighting', 'The brightest, most efficient\r\nWi-Fi LED light bulb.', 150, 48),
(2, 'Amazon Echo', 'https://groov.store/cdn/shop/products/SF2.jpg?v=1558520584&width=800', 'Voice', 'Use your voice to control\r\nyour Muzzley devices.', 151, 102),
(3, 'Nest Learning Thermostat®', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQG9cy-EtKX2zTJz_kVwHPNrfrl9V4CvBskCg&s', 'Thermostats', 'Set the perfect temperature\r\nand save money while you\'re\r\naway.', 152, 77),
(4, 'Nest Learning Thermostat®', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQG9cy-EtKX2zTJz_kVwHPNrfrl9V4CvBskCg&s', 'Thermostats', 'Set the perfect temperature\r\nand save money while you\'re\r\naway.', 152, 77),
(5, 'Nest Protect: Smoke + Carbon Monoxident', 'https://www.wink.com/img/product/nest-protect-smoke-and-co-alarm/variants/854448003396/hero_01.png', 'Detectors & Sensors', 'It speaks up to tell you if\r\nthere\'s smoke or CO and tells\r\nyou where the problem is so\r\nyou know what to do.', 168, 145),
(8, 'ecobee3', 'https://m.media-amazon.com/images/I/71wlZSurPPL._AC_UF894,1000_QL80_.jpg', 'Thermostats', 'ecobee3 is an Apple HomeKit\r\nenabled smart thermostat\r\nwith wireless remote sensors.', 169, 80);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`PrdID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `PrdID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
