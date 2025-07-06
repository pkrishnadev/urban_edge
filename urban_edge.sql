-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jan 06, 2025 at 03:39 AM
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
-- Database: `urban_edge`
--

-- --------------------------------------------------------

--
-- Table structure for table `addresses`
--

DROP TABLE IF EXISTS `addresses`;
CREATE TABLE IF NOT EXISTS `addresses` (
  `address_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `address_name` varchar(255) NOT NULL,
  `address_line1` varchar(255) NOT NULL,
  `address_line2` varchar(255) DEFAULT NULL,
  `city` varchar(100) NOT NULL,
  `pincode` varchar(20) NOT NULL,
  `phone_number` varchar(15) NOT NULL,
  `address_type` enum('home','work','other') NOT NULL,
  `street` varchar(150) NOT NULL,
  PRIMARY KEY (`address_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `addresses`
--

INSERT INTO `addresses` (`address_id`, `user_id`, `address_name`, `address_line1`, `address_line2`, `city`, `pincode`, `phone_number`, `address_type`, `street`) VALUES
(23, 2038, 'dsdsdsd', 'sddds', 'sddds', 'ydsgysg', '123456', '1234567890', 'home', 'dsdhsbhjdsj'),
(21, 2037, 'Neswin', 'pala', 'kottyam', 'pala', '565656', '5665656565', 'home', 'kottyam'),
(22, 2038, 'yuyu', 'uhfhjdfhjf', 'huhkjhkjr', 'hhkhk', '444444', '4444444', 'home', 'ghgjjkjk'),
(18, 2037, 'Krishna Dev', 'st palus hostel ramapuram', 'Ramapuram', 'Ramapuram', '686576', '9497546334', 'home', 'kottayam'),
(24, 2039, 'fff', 'fffffffffff', 'ffffffff', 'ffffffff', '123456', '1234567899', 'work', 'rrrrrrr'),
(25, 2040, 'joyal', 'pala', 'pala town', 'pala', '686565', '1234567899', 'home', 'kottyam'),
(26, 2042, 'joyal', 'pala', 'kottyam pala', 'pala', '685565', '1234567899', 'home', 'kottyam'),
(27, 2041, 'krishna dev', 'st palus hostel', 'ramapuram,pala', 'ramapuram', '686576', '9497546334', 'home', 'kottyam'),
(28, 2041, 'krishnadev', 'pala', 'pala town', 'pala', '685565', '9497546334', 'home', 'kottyam'),
(29, 2045, 'joyal', 'ramapuram', 'ramapuram town', 'ramapuram', '685565', '9497586426', 'home', 'Kottayam '),
(30, 2046, 'neswin george', 'pala', 'pala town', 'pala', '686576', '9497563459', 'home', 'Kottayam ');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE IF NOT EXISTS `categories` (
  `category_id` int NOT NULL AUTO_INCREMENT,
  `category_name` varchar(50) NOT NULL,
  PRIMARY KEY (`category_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3001 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `category_name`) VALUES
(2000, 'plain'),
(3000, 'printed');

-- --------------------------------------------------------

--
-- Table structure for table `order_details`
--

DROP TABLE IF EXISTS `order_details`;
CREATE TABLE IF NOT EXISTS `order_details` (
  `order_detail_id` int NOT NULL AUTO_INCREMENT,
  `order_id` int NOT NULL,
  `user_id` int NOT NULL,
  `address_id` int NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `order_status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'placed',
  `order_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`order_detail_id`),
  KEY `order_id` (`order_id`),
  KEY `user_id` (`user_id`),
  KEY `address_id` (`address_id`)
) ENGINE=MyISAM AUTO_INCREMENT=45 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `order_details`
--

INSERT INTO `order_details` (`order_detail_id`, `order_id`, `user_id`, `address_id`, `total_amount`, `order_status`, `order_date`) VALUES
(42, 1736100923, 2041, 28, 2196.00, 'placed', '2025-01-05 12:45:16'),
(41, 1736097189, 2041, 28, 3095.00, 'placed', '2025-01-05 11:43:05'),
(40, 1736096259, 2042, 26, 499.00, 'placed', '2025-01-05 11:27:35'),
(39, 1736095141, 2040, 25, 3493.00, 'placed', '2025-01-05 11:08:41'),
(38, 1736094364, 2040, 25, 1398.00, 'Canceled', '2025-01-05 10:55:47'),
(37, 1736083423, 2039, 24, 998.00, 'shipped', '2025-01-05 07:53:37'),
(36, 1736059800, 2039, 24, 998.00, 'shipped', '2025-01-05 01:19:54'),
(35, 1736058187, 2037, 18, 1598.00, 'canceled', '2025-01-05 00:52:58'),
(34, 1735746472, 2037, 21, 998.00, 'delivered', '2025-01-01 10:17:47'),
(43, 1736102666, 2045, 29, 2994.00, 'placed', '2025-01-05 13:14:19'),
(44, 1736133846, 2046, 30, 2397.00, 'placed', '2025-01-05 21:54:02');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

DROP TABLE IF EXISTS `order_items`;
CREATE TABLE IF NOT EXISTS `order_items` (
  `order_item_id` int NOT NULL AUTO_INCREMENT,
  `order_detail_id` int NOT NULL,
  `product_id` int NOT NULL,
  `quantity` int NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `size` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`order_item_id`),
  KEY `order_detail_id` (`order_detail_id`),
  KEY `product_id` (`product_id`)
) ENGINE=MyISAM AUTO_INCREMENT=66 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`order_item_id`, `order_detail_id`, `product_id`, `quantity`, `price`, `size`) VALUES
(57, 41, 24, 1, 499.00, 'L'),
(56, 40, 22, 1, 499.00, 'XXL'),
(55, 39, 26, 3, 1497.00, 'XL'),
(54, 39, 22, 4, 1996.00, 'L'),
(53, 38, 28, 1, 599.00, 'XL'),
(52, 38, 98, 1, 799.00, 'M'),
(51, 37, 24, 1, 499.00, 'M'),
(50, 37, 22, 1, 499.00, 'XXL'),
(49, 36, 22, 2, 998.00, 'L'),
(48, 35, 107, 2, 1598.00, 'M'),
(47, 34, 23, 2, 998.00, 'L'),
(58, 41, 32, 2, 998.00, 'XXL'),
(59, 41, 105, 2, 1598.00, 'XXL'),
(60, 42, 28, 2, 1198.00, 'XL'),
(61, 42, 29, 2, 998.00, 'L'),
(62, 43, 26, 2, 998.00, 'L'),
(63, 43, 30, 4, 1996.00, 'M'),
(64, 44, 106, 1, 799.00, 'M'),
(65, 44, 101, 2, 1598.00, 'L');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

DROP TABLE IF EXISTS `payments`;
CREATE TABLE IF NOT EXISTS `payments` (
  `payment_id` int NOT NULL AUTO_INCREMENT,
  `order_id` int DEFAULT NULL,
  `payment_date` datetime DEFAULT CURRENT_TIMESTAMP,
  `amount` decimal(10,2) NOT NULL,
  `payment_method` varchar(50) NOT NULL,
  PRIMARY KEY (`payment_id`),
  KEY `order_id` (`order_id`)
) ENGINE=MyISAM AUTO_INCREMENT=9778 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`payment_id`, `order_id`, `payment_date`, `amount`, `payment_method`) VALUES
(4454, 1736102666, '2025-01-05 18:44:19', 2994.00, 'cod'),
(7241, 1736100923, '2025-01-05 18:15:16', 2196.00, 'cod'),
(4260, 1736097189, '2025-01-05 17:13:05', 3095.00, 'cod'),
(6535, 1736095141, '2025-01-05 16:38:41', 3493.00, 'cod'),
(2797, 1736096259, '2025-01-05 16:57:35', 499.00, 'cod'),
(3875, 1736094364, '2025-01-05 16:25:47', 1398.00, 'cod'),
(1572, 1736083423, '2025-01-05 13:23:37', 998.00, 'cod'),
(3110, 1736059800, '2025-01-05 06:49:54', 998.00, 'cod'),
(3403, 1736058187, '2025-01-05 06:22:58', 1598.00, 'cod'),
(9038, 1735746472, '2025-01-01 15:47:47', 998.00, 'cod'),
(5735, 1736133846, '2025-01-06 03:24:02', 2397.00, 'cod');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
CREATE TABLE IF NOT EXISTS `products` (
  `product_id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `description` text NOT NULL,
  `category_id` int DEFAULT NULL,
  `stock_s` int DEFAULT '0',
  `stock_m` int DEFAULT '0',
  `stock_l` int DEFAULT '0',
  `stock_xl` int DEFAULT '0',
  `stock_xxl` int DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`product_id`),
  KEY `category_id` (`category_id`)
) ENGINE=MyISAM AUTO_INCREMENT=109 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `title`, `price`, `description`, `category_id`, `stock_s`, `stock_m`, `stock_l`, `stock_xl`, `stock_xxl`, `created_at`) VALUES
(23, 'Solid Bottle Green Soft Jersey Oversized T-shirt', 499.00, 'Composition: 100% cotton\r\nGSM: 210\r\nCountry of production: India\r\nWash care: Machine wash cold with similar colours.\r\nOnly non-chlorine.\r\nTumble dry low.\r\nWarm Iron if needed.\r\nSleeve length: Long sleeve\r\nNeckline: Round neck\r\nFit: Oversized drop shoulder tee', 2000, 8, 9, 0, 4, 0, '2024-10-18 09:52:31'),
(22, 'Solid Off White Soft Jersey Oversized T-shirt ', 499.00, 'Composition: 100% cotton\r\nGSM: 210\r\nColour: Pastel Green\r\nCountry of production: India\r\nWash care: Machine wash cold with similar colours.\r\nOnly non-chlorine.\r\nTumble dry low.\r\nWarm Iron if needed.\r\nSleeve length: Long sleeve\r\nNeckline: Round neck\r\nFit: Oversized drop shoulder tee', 2000, 0, 4, -3, 0, 5, '2024-10-18 09:51:42'),
(24, 'Solid Brown Soft Jersey Oversized T-shirt', 499.00, 'Composition: 100% cotton\r\nGSM: 210\r\nColour: Pastel Green\r\nCountry of production: India\r\nWash care: Machine wash cold with similar colours.\r\nOnly non-chlorine.\r\nTumble dry low.\r\nWarm Iron if needed.\r\nSleeve length: Long sleeve\r\nNeckline: Round neck\r\nFit: Oversized drop shoulder tee', 2000, 5, 4, 39, 5, 5, '2024-10-18 09:53:56'),
(25, 'Solid Green Soft Jersey Oversized T-shirt ', 499.00, 'Composition: 100% cotton\r\nGSM: 210\r\nColour: Pastel Green\r\nCountry of production: India\r\nWash care: Machine wash cold with similar colours.\r\nOnly non-chlorine.\r\nTumble dry low.\r\nWarm Iron if needed.\r\nSleeve length: Long sleeve\r\nNeckline: Round neck\r\nFit: Oversized drop shoulder tee', 2000, 5, 4, 4, 6, 5, '2024-10-18 09:54:52'),
(26, 'Solid Grey Melange Soft Jersey Oversized T-shirt', 499.00, 'Composition: 100% cotton\r\nGSM: 210\r\nColour: Pastel Green\r\nCountry of production: India\r\nWash care: Machine wash cold with similar colours.\r\nOnly non-chlorine.\r\nTumble dry low.\r\nWarm Iron if needed.\r\nSleeve length: Long sleeve\r\nNeckline: Round neck\r\nFit: Oversized drop shoulder tee', 2000, 4, 1, 17, 76, 6, '2024-10-18 09:56:58'),
(27, 'Solid dark olive Jersey Oversized T-shirt ', 599.00, 'Composition: 100% cotton\r\nGSM: 210\r\nColour: Pastel Green\r\nCountry of production: India\r\nWash care: Machine wash cold with similar colours.\r\nOnly non-chlorine.\r\nTumble dry low.\r\nWarm Iron if needed.\r\nSleeve length: Long sleeve\r\nNeckline: Round neck\r\nFit: Oversized drop shoulder tee', 2000, 5, 6, 48, 50, 50, '2024-10-18 10:02:56'),
(28, 'Pastel Blue Heavyweight Faded T-shirt ', 599.00, 'Composition: 100% cotton\r\nGSM: 210\r\nColour: Pastel Green\r\nCountry of production: India\r\nWash care: Machine wash cold with similar colours.\r\nOnly non-chlorine.\r\nTumble dry low.\r\nWarm Iron if needed.\r\nSleeve length: Long sleeve\r\nNeckline: Round neck\r\nFit: Oversized drop shoulder tee', 2000, 50, 47, 36, 1, 5, '2024-10-18 10:05:04'),
(29, 'Peach Heavyweight Oversized T-shirt', 499.00, 'Composition: 100% cotton\r\nGSM: 210\r\nColour: Pastel Green\r\nCountry of production: India\r\nWash care: Machine wash cold with similar colours.\r\nOnly non-chlorine.\r\nTumble dry low.\r\nWarm Iron if needed.\r\nSleeve length: Long sleeve\r\nNeckline: Round neck\r\nFit: Oversized drop shoulder tee', 2000, 0, 53, 7, 6, 40, '2024-10-18 10:11:31'),
(30, 'Solid Stone Grey Soft Jersey Oversized T-shirt ', 499.00, 'Composition: 100% cotton\r\nGSM: 210\r\nColour: Pastel Green\r\nCountry of production: India\r\nWash care: Machine wash cold with similar colours.\r\nOnly non-chlorine.\r\nTumble dry low.\r\nWarm Iron if needed.\r\nSleeve length: Long sleeve\r\nNeckline: Round neck\r\nFit: Oversized drop shoulder tee', 2000, 88, 93, 54, 10, 5, '2024-10-18 10:13:04'),
(32, 'White Heavyweight Oversized T-shirt ', 499.00, 'Composition: 100% cotton\r\nGSM: 210\r\nColour: Pastel Green\r\nCountry of production: India\r\nWash care: Machine wash cold with similar colours.\r\nOnly non-chlorine.\r\nTumble dry low.\r\nWarm Iron if needed.\r\nSleeve length: Long sleeve\r\nNeckline: Round neck\r\nFit: Oversized drop shoulder tee', 2000, 55, 54, 86, 7, 4, '2024-10-18 10:18:53'),
(73, '', 0.00, '', 0, 0, 0, 0, 0, 0, '2024-12-28 13:07:05'),
(84, '', 0.00, '', 0, 0, 0, 0, 0, 0, '2025-01-03 16:47:05'),
(99, 'Bugs On Call Oversized T-shirt-brown', 799.00, 'Composition: 100% cotton\r\nGSM: 210\r\nCountry of production: India\r\nWash care: Machine wash cold with similar colours.\r\nOnly non-chlorine.\r\nTumble dry low.\r\nWarm Iron if needed.\r\nSleeve length: Long sleeve\r\nNeckline: Round neck\r\nFit: Oversized drop shoulder tee', 3000, 33, 33, 77, 77, 77, '2025-01-03 17:34:59'),
(83, '', 0.00, '', 0, 0, 0, 0, 0, 0, '2025-01-03 16:39:20'),
(85, '', 0.00, '', 0, 0, 0, 0, 0, 0, '2025-01-03 16:56:54'),
(86, '', 0.00, '', 0, 0, 0, 0, 0, 0, '2025-01-03 16:57:33'),
(87, '', 0.00, '', 0, 0, 0, 0, 0, 0, '2025-01-03 16:59:16'),
(88, '', 0.00, '', 0, 0, 0, 0, 0, 0, '2025-01-03 17:02:32'),
(89, '', 0.00, '', 0, 0, 0, 0, 0, 0, '2025-01-03 17:02:52'),
(90, '', 0.00, '', 0, 0, 0, 0, 0, 0, '2025-01-03 17:03:16'),
(91, '', 0.00, '', 0, 0, 0, 0, 0, 0, '2025-01-03 17:03:28'),
(94, '', 0.00, '', 0, 0, 0, 0, 0, 0, '2025-01-03 17:13:15'),
(96, '', 0.00, '', 0, 0, 0, 0, 0, 0, '2025-01-03 17:15:32'),
(98, 'Appetite For Destruction Faded Oversized T-shirt-black', 799.00, 'Composition: 100% cotton\r\nGSM: 210\r\nCountry of production: India\r\nWash care: Machine wash cold with similar colours.\r\nOnly non-chlorine.\r\nTumble dry low.\r\nWarm Iron if needed.\r\nSleeve length: Long sleeve\r\nNeckline: Round neck\r\nFit: Oversized drop shoulder tee', 3000, 33, 2, 0, 0, 0, '2025-01-03 17:34:27'),
(100, 'Football Bunny Oversized T-shirt-blue', 799.00, 'Composition: 100% cotton\r\nGSM: 210\r\nCountry of production: India\r\nWash care: Machine wash cold with similar colours.\r\nOnly non-chlorine.\r\nTumble dry low.\r\nWarm Iron if needed.\r\nSleeve length: Long sleeve\r\nNeckline: Round neck\r\nFit: Oversized drop shoulder tee', 3000, 33, 33, 33, 33, 33, '2025-01-03 17:36:23'),
(101, 'Guns N Roses Use Your Illusion Oversized T-shirt-grey', 799.00, 'Composition: 100% cotton\r\nGSM: 210\r\nCountry of production: India\r\nWash care: Machine wash cold with similar colours.\r\nOnly non-chlorine.\r\nTumble dry low.\r\nWarm Iron if needed.\r\nSleeve length: Long sleeve\r\nNeckline: Round neck\r\nFit: Oversized drop shoulder tee', 3000, 22, 22, 20, 0, 22, '2025-01-03 17:37:36'),
(102, 'No Rain No Flower Oversized T-shirt-cream', 799.00, 'Composition: 100% cotton\r\nGSM: 210\r\nCountry of production: India\r\nWash care: Machine wash cold with similar colours.\r\nOnly non-chlorine.\r\nTumble dry low.\r\nWarm Iron if needed.\r\nSleeve length: Long sleeve\r\nNeckline: Round neck\r\nFit: Oversized drop shoulder tee', 3000, 33, 13, 33, 0, 33, '2025-01-03 17:38:31'),
(103, 'The Empire Strike Back Oversized T-shirt-white', 799.00, 'Composition: 100% cotton\r\nGSM: 210\r\nCountry of production: India\r\nWash care: Machine wash cold with similar colours.\r\nOnly non-chlorine.\r\nTumble dry low.\r\nWarm Iron if needed.\r\nSleeve length: Long sleeve\r\nNeckline: Round neck\r\nFit: Oversized drop shoulder tee', 3000, 33, 0, 33, 33, 33, '2025-01-03 17:39:38'),
(104, 'Therapy Sessions Oversized T-shirt-blue', 799.00, 'Composition: 100% cotton\r\nGSM: 210\r\nCountry of production: India\r\nWash care: Machine wash cold with similar colours.\r\nOnly non-chlorine.\r\nTumble dry low.\r\nWarm Iron if needed.\r\nSleeve length: Long sleeve\r\nNeckline: Round neck\r\nFit: Oversized drop shoulder tee', 3000, 0, 55, 55, 55, 0, '2025-01-03 17:40:23'),
(105, 'Coyote The Genius Oversized T-shirt-brown', 799.00, 'Composition: 100% cotton\r\nGSM: 210\r\nCountry of production: India\r\nWash care: Machine wash cold with similar colours.\r\nOnly non-chlorine.\r\nTumble dry low.\r\nWarm Iron if needed.\r\nSleeve length: Long sleeve\r\nNeckline: Round neck\r\nFit: Oversized drop shoulder tee', 3000, 0, 44, 44, 0, 42, '2025-01-03 17:41:30'),
(106, 'Cupid Love And Peace Premium Oversized T-shirt-grey', 799.00, 'Composition: 100% cotton\r\nGSM: 210\r\nCountry of production: India\r\nWash care: Machine wash cold with similar colours.\r\nOnly non-chlorine.\r\nTumble dry low.\r\nWarm Iron if needed.\r\nSleeve length: Long sleeve\r\nNeckline: Round neck\r\nFit: Oversized drop shoulder tee', 3000, 9, 54, 55, 0, 0, '2025-01-03 17:42:32'),
(107, 'SpongeBob Squad Oversized T-shirt-black', 799.00, 'Composition: 100% cotton\r\nGSM: 210\r\nCountry of production: India\r\nWash care: Machine wash cold with similar colours.\r\nOnly non-chlorine.\r\nTumble dry low.\r\nWarm Iron if needed.\r\nSleeve length: Long sleeve\r\nNeckline: Round neck\r\nFit: Oversized drop shoulder tee', 3000, 14, 20, 5, 1, 0, '2025-01-03 17:43:18');

-- --------------------------------------------------------

--
-- Table structure for table `product_images`
--

DROP TABLE IF EXISTS `product_images`;
CREATE TABLE IF NOT EXISTS `product_images` (
  `image_id` int NOT NULL AUTO_INCREMENT,
  `product_id` int DEFAULT NULL,
  `image_path` varchar(255) NOT NULL,
  PRIMARY KEY (`image_id`),
  KEY `product_id` (`product_id`)
) ENGINE=MyISAM AUTO_INCREMENT=298 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `product_images`
--

INSERT INTO `product_images` (`image_id`, `product_id`, `image_path`) VALUES
(119, 25, 'http://localhost/urban_edge/admin/uploads/Solid Green Soft Jersey Oversized T-shirt  (2).jpg'),
(118, 25, 'http://localhost/urban_edge/admin/uploads/Solid Green Soft Jersey Oversized T-shirt  (1).jpg'),
(117, 24, 'http://localhost/urban_edge/admin/uploads/Solid Brown Soft Jersey Oversized T-shirt (5).jpg'),
(116, 24, 'http://localhost/urban_edge/admin/uploads/Solid Brown Soft Jersey Oversized T-shirt (4).jpg'),
(115, 24, 'http://localhost/urban_edge/admin/uploads/Solid Brown Soft Jersey Oversized T-shirt (3).jpg'),
(114, 24, 'http://localhost/urban_edge/admin/uploads/Solid Brown Soft Jersey Oversized T-shirt (2).jpg'),
(113, 24, 'http://localhost/urban_edge/admin/uploads/Solid Brown Soft Jersey Oversized T-shirt (1).jpg'),
(112, 23, 'http://localhost/urban_edge/admin/uploads/Solid Bottle Green Soft Jersey Oversized T-shirt (5).jpg'),
(111, 23, 'http://localhost/urban_edge/admin/uploads/Solid Bottle Green Soft Jersey Oversized T-shirt (4).jpg'),
(110, 23, 'http://localhost/urban_edge/admin/uploads/Solid Bottle Green Soft Jersey Oversized T-shirt (3).jpg'),
(109, 23, 'http://localhost/urban_edge/admin/uploads/Solid Bottle Green Soft Jersey Oversized T-shirt (2).jpg'),
(107, 22, 'http://localhost/urban_edge/admin/uploads/Solid Off White  (5).jpg'),
(106, 22, 'http://localhost/urban_edge/admin/uploads/Solid Off White  (4).jpg'),
(105, 22, 'http://localhost/urban_edge/admin/uploads/Solid Off White  (3).jpg'),
(104, 22, 'http://localhost/urban_edge/admin/uploads/Solid Off White  (2).jpg'),
(252, 99, 'http://localhost/urban_edge/admin/uploads/Bonkerscorner_bugs_on_call_ost_8.png'),
(253, 100, 'http://localhost/urban_edge/admin/uploads/Bonkerscorner_football_bunny_ost_1-min.png'),
(254, 100, 'http://localhost/urban_edge/admin/uploads/Bonkerscorner_football_bunny_ost_2-min - Copy.png'),
(103, 22, 'http://localhost/urban_edge/admin/uploads/Solid Off White  (1).jpg'),
(258, 101, 'http://localhost/urban_edge/admin/uploads/Bonkerscorner_Guns_N_Roses_Use_Your_IllusionOversized_T-shirt_2.png'),
(108, 23, 'http://localhost/urban_edge/admin/uploads/Solid Bottle Green Soft Jersey Oversized T-shirt (1).jpg'),
(120, 25, 'http://localhost/urban_edge/admin/uploads/Solid Green Soft Jersey Oversized T-shirt  (3).jpg'),
(121, 25, 'http://localhost/urban_edge/admin/uploads/Solid Green Soft Jersey Oversized T-shirt  (4).jpg'),
(122, 25, 'http://localhost/urban_edge/admin/uploads/Solid Green Soft Jersey Oversized T-shirt  (5).jpg'),
(123, 26, 'http://localhost/urban_edge/admin/uploads/Solid Grey Melange Soft Jersey Oversized T-shirt (1).jpg'),
(124, 26, 'http://localhost/urban_edge/admin/uploads/Solid Grey Melange Soft Jersey Oversized T-shirt (2).jpg'),
(125, 26, 'http://localhost/urban_edge/admin/uploads/Solid Grey Melange Soft Jersey Oversized T-shirt (3).jpg'),
(126, 26, 'http://localhost/urban_edge/admin/uploads/Solid Grey Melange Soft Jersey Oversized T-shirt (4).jpg'),
(127, 26, 'http://localhost/urban_edge/admin/uploads/Solid Grey Melange Soft Jersey Oversized T-shirt (5).jpg'),
(128, 27, 'http://localhost/urban_edge/admin/uploads/Solid dark olive Jersey Oversized T-shirt (1).jpg'),
(129, 27, 'http://localhost/urban_edge/admin/uploads/Solid dark olive Jersey Oversized T-shirt (2).jpg'),
(130, 27, 'http://localhost/urban_edge/admin/uploads/Solid dark olive Jersey Oversized T-shirt (3).jpg'),
(131, 27, 'http://localhost/urban_edge/admin/uploads/Solid dark olive Jersey Oversized T-shirt (4).jpg'),
(132, 27, 'http://localhost/urban_edge/admin/uploads/Solid dark olive Jersey Oversized T-shirt (5).jpg'),
(133, 28, 'http://localhost/urban_edge/admin/uploads/Pastel Blue Heavyweight Faded T-shirt  (1).jpg'),
(134, 28, 'http://localhost/urban_edge/admin/uploads/Pastel Blue Heavyweight Faded T-shirt  (2).jpg'),
(135, 28, 'http://localhost/urban_edge/admin/uploads/Pastel Blue Heavyweight Faded T-shirt  (3).jpg'),
(136, 28, 'http://localhost/urban_edge/admin/uploads/Pastel Blue Heavyweight Faded T-shirt  (4).jpg'),
(137, 28, 'http://localhost/urban_edge/admin/uploads/Pastel Blue Heavyweight Faded T-shirt  (5).jpg'),
(138, 29, 'http://localhost/urban_edge/admin/uploads/Peach Heavyweight Oversized T-shirt (1).jpg'),
(139, 29, 'http://localhost/urban_edge/admin/uploads/Peach Heavyweight Oversized T-shirt (2).jpg'),
(140, 29, 'http://localhost/urban_edge/admin/uploads/Peach Heavyweight Oversized T-shirt (3).jpg'),
(141, 29, 'http://localhost/urban_edge/admin/uploads/Peach Heavyweight Oversized T-shirt (4).jpg'),
(142, 29, 'http://localhost/urban_edge/admin/uploads/Peach Heavyweight Oversized T-shirt (5).jpg'),
(143, 30, 'http://localhost/urban_edge/admin/uploads/Solid Stone Grey Soft Jersey Oversized T-shirt  (1).jpg'),
(144, 30, 'http://localhost/urban_edge/admin/uploads/Solid Stone Grey Soft Jersey Oversized T-shirt  (2).jpg'),
(145, 30, 'http://localhost/urban_edge/admin/uploads/Solid Stone Grey Soft Jersey Oversized T-shirt  (3).jpg'),
(146, 30, 'http://localhost/urban_edge/admin/uploads/Solid Stone Grey Soft Jersey Oversized T-shirt  (4).jpg'),
(147, 30, 'http://localhost/urban_edge/admin/uploads/Solid Stone Grey Soft Jersey Oversized T-shirt  (5).jpg'),
(157, 32, 'http://localhost/urban_edge/admin/uploads/White Heavyweight Oversized T-shirt  (5).jpg'),
(156, 32, 'http://localhost/urban_edge/admin/uploads/White Heavyweight Oversized T-shirt  (4).jpg'),
(155, 32, 'http://localhost/urban_edge/admin/uploads/White Heavyweight Oversized T-shirt  (3).jpg'),
(154, 32, 'http://localhost/urban_edge/admin/uploads/White Heavyweight Oversized T-shirt  (2).jpg'),
(153, 32, 'http://localhost/urban_edge/admin/uploads/White Heavyweight Oversized T-shirt  (1).jpg'),
(257, 100, 'http://localhost/urban_edge/admin/uploads/Bonkerscorner_football_bunny_ost_10_960x_crop_center.png'),
(256, 100, 'http://localhost/urban_edge/admin/uploads/Bonkerscorner_football_bunny_ost_8-min.png'),
(255, 100, 'http://localhost/urban_edge/admin/uploads/Bonkerscorner_football_bunny_ost_6-min - Copy.png'),
(251, 99, 'http://localhost/urban_edge/admin/uploads/Bonkerscorner_bugs_on_call_ost_6.png'),
(250, 99, 'http://localhost/urban_edge/admin/uploads/Bonkerscorner_bugs_on_call_ost_4_960x_crop_center.png'),
(247, 98, 'http://localhost/urban_edge/admin/uploads/Bonkerscorner_AppetiteForDestructionFadedOversizedT-shirt08.png'),
(248, 99, 'http://localhost/urban_edge/admin/uploads/Bonkerscorner_bugs_on_call_ost_1_960x_crop_center.png'),
(249, 99, 'http://localhost/urban_edge/admin/uploads/Bonkerscorner_bugs_on_call_ost_3 - Copy.png'),
(246, 98, 'http://localhost/urban_edge/admin/uploads/Bonkerscorner_AppetiteForDestructionFadedOversizedT-shirt5_960x_crop_center.png'),
(245, 98, 'http://localhost/urban_edge/admin/uploads/Bonkerscorner_AppetiteForDestructionFadedOversizedT-shirt4_960x_crop_center.png'),
(244, 98, 'http://localhost/urban_edge/admin/uploads/Bonkerscorner_AppetiteForDestructionFadedOversizedT-shirt3_960x_crop_center.png'),
(243, 98, 'http://localhost/urban_edge/admin/uploads/Bonkerscorner_AppetiteForDestructionFadedOversizedT-shirt0_960x_crop_center.png'),
(259, 101, 'http://localhost/urban_edge/admin/uploads/Bonkerscorner_Guns_N_Roses_Use_Your_IllusionOversized_T-shirt_4 - Copy.png'),
(260, 101, 'http://localhost/urban_edge/admin/uploads/Bonkerscorner_Guns_N_Roses_Use_Your_IllusionOversized_T-shirt_5_960x_crop_center - Copy.png'),
(261, 101, 'http://localhost/urban_edge/admin/uploads/Bonkerscorner_Guns_N_Roses_Use_Your_IllusionOversized_T-shirt_6 - Copy.png'),
(262, 101, 'http://localhost/urban_edge/admin/uploads/Bonkerscorner_Guns_N_Roses_Use_Your_IllusionOversized_T-shirt_8 - Copy.png'),
(263, 102, 'http://localhost/urban_edge/admin/uploads/Bonkerscorner_No_Rain_No_Flower_Ost01_7aaef4dc-3139-4ead-b7db-6a508a7ba2b0.png'),
(264, 102, 'http://localhost/urban_edge/admin/uploads/Bonkerscorner_No_Rain_No_Flower_Ost03_7c63161f-1de9-44fe-882d-8d9de495fe11_960x_crop_center.png'),
(265, 102, 'http://localhost/urban_edge/admin/uploads/Bonkerscorner_No_Rain_No_Flower_Ost04_50f4efd0-d9e4-4808-b6cd-a4e0567a62b4.png'),
(266, 102, 'http://localhost/urban_edge/admin/uploads/Bonkerscorner_No_Rain_No_Flower_Ost07_e0330585-deaf-4b64-8cc1-ddb8e2afd552.png'),
(267, 102, 'http://localhost/urban_edge/admin/uploads/Bonkerscorner_No_Rain_No_Flower_Oversized_T-shirt006.png'),
(268, 103, 'http://localhost/urban_edge/admin/uploads/Bonkerscorner_the_empire_strike_back_oversized_tshirt_1.png'),
(269, 103, 'http://localhost/urban_edge/admin/uploads/Bonkerscorner_the_empire_strike_back_oversized_tshirt_3.png'),
(270, 103, 'http://localhost/urban_edge/admin/uploads/Bonkerscorner_the_empire_strike_back_oversized_tshirt_7.png'),
(271, 103, 'http://localhost/urban_edge/admin/uploads/Bonkerscorner_the_empire_strike_back_oversized_tshirt_8_960x_crop_center - Copy.png'),
(272, 103, 'http://localhost/urban_edge/admin/uploads/Bonkerscorner_the_empire_strike_back_oversized_tshirt_10_960x_crop_center - Copy.png'),
(273, 104, 'http://localhost/urban_edge/admin/uploads/Bonkerscorner_Therapy_Sessions_Oversized_T-shirt3_960x_crop_center - Copy.png'),
(274, 104, 'http://localhost/urban_edge/admin/uploads/Bonkerscorner_Therapy_Sessions_Oversized_T-shirt7_960x_crop_center.png'),
(275, 104, 'http://localhost/urban_edge/admin/uploads/Bonkerscorner_Therapy_Sessions_Oversized_T-shirt12_960x_crop_center.png'),
(276, 104, 'http://localhost/urban_edge/admin/uploads/Bonkerscorner_Therapy_Sessions_Oversized_T-shirt14.png'),
(277, 104, 'http://localhost/urban_edge/admin/uploads/Bonkerscorner_Therapy_Sessions_Oversized_T-shirt14_960x_crop_center.png'),
(278, 105, 'http://localhost/urban_edge/admin/uploads/Coyote brown (1)-min.png'),
(279, 105, 'http://localhost/urban_edge/admin/uploads/Coyote brown (2) - Copy.png'),
(280, 105, 'http://localhost/urban_edge/admin/uploads/Coyote brown (3)-min - Copy.png'),
(281, 105, 'http://localhost/urban_edge/admin/uploads/Coyote brown (4) - Copy.png'),
(282, 105, 'http://localhost/urban_edge/admin/uploads/Coyote brown (5) - Copy.png'),
(283, 106, 'http://localhost/urban_edge/admin/uploads/cupid-love-and-peace-premium-oversized-t-shirt-xs-bonkerscorner-store-34410182377572 - Copy.png'),
(284, 106, 'http://localhost/urban_edge/admin/uploads/cupid-love-and-peace-premium-oversized-t-shirt-xs-bonkerscorner-store-34410182410340 - Copy.png'),
(285, 106, 'http://localhost/urban_edge/admin/uploads/cupid-love-and-peace-premium-oversized-t-shirt-xs-bonkerscorner-store-34410182443108_960x_crop_center - Copy.png'),
(286, 106, 'http://localhost/urban_edge/admin/uploads/cupid-love-and-peace-premium-oversized-t-shirt-xs-bonkerscorner-store-34410182475876 - Copy.png'),
(287, 106, 'http://localhost/urban_edge/admin/uploads/cupid-love-and-peace-premium-oversized-t-shirt-xs-bonkerscorner-store-34410182508644 - Copy.png'),
(288, 107, 'http://localhost/urban_edge/admin/uploads/spongebob-squad-oversized-t-shirt-3xl-bonkerscorner-store-33703205699684.png'),
(289, 107, 'http://localhost/urban_edge/admin/uploads/spongebob-squad-oversized-t-shirt-3xl-bonkerscorner-store-33703207665764.png'),
(290, 107, 'http://localhost/urban_edge/admin/uploads/spongebob-squad-oversized-t-shirt-3xl-bonkerscorner-store-33703208222820_960x_crop_center - Copy.png'),
(291, 107, 'http://localhost/urban_edge/admin/uploads/spongebob-squad-oversized-t-shirt-3xl-bonkerscorner-store-33703210713188.png'),
(292, 107, 'http://localhost/urban_edge/admin/uploads/spongebob-squad-oversized-t-shirt-3xl-bonkerscorner-store-33703212613732.png');

-- --------------------------------------------------------

--
-- Table structure for table `site_statistics`
--

DROP TABLE IF EXISTS `site_statistics`;
CREATE TABLE IF NOT EXISTS `site_statistics` (
  `id` int NOT NULL AUTO_INCREMENT,
  `total_orders_placed` int DEFAULT '0',
  `total_customers` int DEFAULT '0',
  `total_products` int DEFAULT '0',
  `total_revenue` decimal(10,2) DEFAULT '0.00',
  `last_updated` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `site_statistics`
--

INSERT INTO `site_statistics` (`id`, `total_orders_placed`, `total_customers`, `total_products`, `total_revenue`, `last_updated`) VALUES
(1, 9, 2, 20, 11181.00, '2025-01-06 03:24:06');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'user',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2047 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `email`, `password`, `role`) VALUES
(2027, 'admin@gmail.com', '$2y$10$EwziKMafJj.gKgCoVIO6Gezri6ez5hCyfzF0s8u5BU0OOSlAou.I2', 'admin'),
(2041, 'krish@gmail.com', '$2y$10$j57hep3YgDP.IZafMDEPzeyViJBO5jzjhQPaQ3SfbwjwW72ZpM3w.', 'user'),
(2045, 'joyal@gmail.com', '$2y$10$.IXj6TXLB7eJAy6vpOGbp.r0eczUTjepPXtjn4eB.DPUUYb9vtkKC', 'user'),
(2046, 'neswin@gmail.com', '$2y$10$CZCIFqPxWL8Dq8A74a2H6OfH4ZHAFC/FBazkW6f9leAnGrg.VPaKW', 'user');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
