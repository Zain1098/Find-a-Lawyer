-- phpMyAdmin SQL Dump
-- Database: `e_project`
-- Lawyer Appointment Booking System Schema
-- Updated for Portfolio Production

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- --------------------------------------------------------
-- Table structure for table `admin`
-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table `admin`
-- Default password: '1122' (hashed with bcrypt, legacy fallback supported)
INSERT INTO `admin` (`id`, `name`, `email`, `password`) VALUES
(1, 'Admin Zain', 'za496694@gmail.com', '$2y$10$wK1k6aCsnqX6B7eB5l1q2OsuD.KjVn24fomZk0R1vX7m6n8p9eL.K')
ON DUPLICATE KEY UPDATE `name`=VALUES(`name`);

-- --------------------------------------------------------
-- Table structure for table `categorie`
-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `categorie` (
  `cat_id` int(11) NOT NULL AUTO_INCREMENT,
  `cat_name` varchar(255) NOT NULL,
  `cat_icon` varchar(100) DEFAULT 'flaticon-courthouse',
  `cat_desc` text DEFAULT NULL,
  PRIMARY KEY (`cat_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table `categorie`
INSERT INTO `categorie` (`cat_id`, `cat_name`, `cat_icon`, `cat_desc`) VALUES
(1, 'Family & Divorce Law', 'flaticon-lawyer-1', 'Legal guidance for family disputes, marriage dissolution, child custody, and domestic settlements.'),
(2, 'Criminal Defense', 'flaticon-police-badge', 'Strong constitutional defense for criminal allegations, trials, and legal representation in court.'),
(3, 'Corporate & Business Law', 'flaticon-courthouse', 'Comprehensive legal counsel for company registration, commercial contracts, mergers, and business disputes.'),
(4, 'Immigration & Visa Law', 'flaticon-libra', 'Expert advice on citizenship, work permits, permanent residency, and international immigration procedures.'),
(5, 'Real Estate & Property Law', 'flaticon-courthouse', 'Legal assistance with property disputes, title verification, tenant agreements, and real estate transactions.'),
(6, 'Taxation & Financial Law', 'flaticon-libra', 'Corporate and individual tax planning, audits, financial litigation, and revenue tribunal appeals.')
ON DUPLICATE KEY UPDATE `cat_name`=VALUES(`cat_name`);

-- --------------------------------------------------------
-- Table structure for table `role`
-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `role` (
  `role_id` int(11) NOT NULL AUTO_INCREMENT,
  `role_name` varchar(255) NOT NULL,
  PRIMARY KEY (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `role` (`role_id`, `role_name`) VALUES
(1, 'User'),
(2, 'Lawyer'),
(3, 'Admin')
ON DUPLICATE KEY UPDATE `role_name`=VALUES(`role_name`);

-- --------------------------------------------------------
-- Table structure for table `lawyer`
-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `lawyer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `last name` varchar(255) DEFAULT '',
  `email` varchar(255) NOT NULL,
  `number` varchar(30) NOT NULL,
  `address` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `dob` varchar(50) DEFAULT '',
  `bar council` varchar(100) DEFAULT '',
  `since` int(11) NOT NULL DEFAULT 0,
  `specialist` int(11) NOT NULL,
  `description` text DEFAULT NULL,
  `degree` varchar(255) DEFAULT '',
  `university` varchar(255) DEFAULT '',
  `language` varchar(255) DEFAULT 'English',
  `day` text DEFAULT NULL,
  `Time` varchar(255) DEFAULT '',
  `fee` int(11) NOT NULL DEFAULT 0,
  `image` varchar(255) DEFAULT 'default_lawyer.png',
  `cover image` varchar(255) DEFAULT 'default_cover.jpg',
  `about me` text DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `specialist` (`specialist`),
  CONSTRAINT `lawyer_ibfk_1` FOREIGN KEY (`specialist`) REFERENCES `categorie` (`cat_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table `lawyer`
-- Demo password for lawyers: '1122' or 'lawyer123'
INSERT INTO `lawyer` (`id`, `name`, `last name`, `email`, `number`, `address`, `password`, `dob`, `bar council`, `since`, `specialist`, `description`, `degree`, `university`, `language`, `day`, `Time`, `fee`, `image`, `cover image`, `about me`, `status`) VALUES
(1, 'Barrister Ammar', 'Motan', 'ammar@gmail.com', '+92 300 1234567', 'Clifton Block 5, Karachi', '$2y$10$wK1k6aCsnqX6B7eB5l1q2OsuD.KjVn24fomZk0R1vX7m6n8p9eL.K', '1985-06-15', 'BC-KHI-85462', 2012, 1, 'Senior Family & Child Custody Specialist with over a decade of litigation experience in family courts.', 'LL.M (Family Law)', 'Harvard Law School', 'English, Urdu', 'Monday, Tuesday, Wednesday, Thursday, Friday', '09:00 AM to 03:00 PM', 5000, 'ammar.jpeg', 'cover_1.jpg', 'Experienced advocate dedicated to protecting families and ensuring amicable resolutions in challenging divorce and custody disputes.', 'active'),
(2, 'Advocate Muhammad', 'Ahmed', 'muhammadahmed8507@gmail.com', '+92 314 9876543', 'Gulshan-e-Iqbal, Karachi', '$2y$10$wK1k6aCsnqX6B7eB5l1q2OsuD.KjVn24fomZk0R1vX7m6n8p9eL.K', '1988-09-21', 'BC-KHI-98541', 2015, 2, 'Expert Criminal Defense Attorney defending rights in trial courts, bails, and constitutional petitions.', 'LL.B (Hons), Bar-at-Law', 'Lincoln\'s Inn, London', 'English, Urdu', 'Monday, Tuesday, Wednesday, Friday', '10:00 AM to 05:00 PM', 8000, 'SC-Project-Icon.png', 'cover_2.jpg', 'Aggressive legal defense with a proven track record of securing justice for clients across complex criminal trials.', 'active'),
(3, 'Advocate Zain', 'Ansari', 'za496694@gmail.com', '+92 333 4567890', 'Defence Phase 6, Karachi', '$2y$10$wK1k6aCsnqX6B7eB5l1q2OsuD.KjVn24fomZk0R1vX7m6n8p9eL.K', '1992-04-12', 'HGZ-214689', 2018, 3, 'Corporate and Commercial Law Consultant advising startups, multinational corporations, and fintechs.', 'LL.M (Corporate Law)', 'University of London', 'English, Urdu', 'Monday, Tuesday, Wednesday, Thursday, Saturday', '11:00 AM to 06:00 PM', 10000, '1712905422037.jpg', 'cover_3.jpg', 'Providing strategic corporate advisory, regulatory compliance, contract drafting, and M&A solutions for growing businesses.', 'active'),
(4, 'Advocate Taha', 'Ansari', 'taha@gmail.com', '+92 321 7890123', 'North Nazimabad, Karachi', '$2y$10$wK1k6aCsnqX6B7eB5l1q2OsuD.KjVn24fomZk0R1vX7m6n8p9eL.K', '1990-11-03', 'BC-KHI-33491', 2016, 6, 'Taxation & Financial Consultant helping corporations navigate complex tax codes and revenue disputes.', 'LL.B, FCA', 'Karachi University', 'English, Urdu', 'Monday, Wednesday, Friday, Saturday', '02:00 PM to 08:00 PM', 6000, '1712905422037.jpg', 'cover_4.jpg', 'Dedicated to minimizing tax liabilities and resolving high-stakes financial and sales tax litigation before appellate tribunals.', 'active')
ON DUPLICATE KEY UPDATE `name`=VALUES(`name`);

-- --------------------------------------------------------
-- Table structure for table `user`
-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(30) DEFAULT '',
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table `user`
-- Passwords: '1122' or 'user123'
INSERT INTO `user` (`id`, `name`, `email`, `phone`, `password`) VALUES
(1, 'Client Ammar', 'ammar.client@gmail.com', '+92 300 1112233', '$2y$10$wK1k6aCsnqX6B7eB5l1q2OsuD.KjVn24fomZk0R1vX7m6n8p9eL.K'),
(2, 'Client Zain', 'zain.client@gmail.com', '+92 300 4445566', '$2y$10$wK1k6aCsnqX6B7eB5l1q2OsuD.KjVn24fomZk0R1vX7m6n8p9eL.K')
ON DUPLICATE KEY UPDATE `name`=VALUES(`name`);

-- --------------------------------------------------------
-- Table structure for table `appointment`
-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `appointment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(30) NOT NULL,
  `lawyer` int(11) NOT NULL,
  `available` varchar(255) NOT NULL,
  `appointment_date` date DEFAULT NULL,
  `appointment_time` varchar(100) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `status` enum('pending','confirmed','cancelled','completed') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `lawyer` (`lawyer`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `appointment_ibfk_1` FOREIGN KEY (`lawyer`) REFERENCES `lawyer` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `appointment_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping sample appointment
INSERT INTO `appointment` (`id`, `user_id`, `name`, `email`, `phone`, `lawyer`, `available`, `appointment_date`, `appointment_time`, `message`, `status`) VALUES
(1, 1, 'Client Ammar', 'ammar.client@gmail.com', '+92 300 1112233', 3, 'Monday (11:00 AM to 06:00 PM)', CURDATE() + INTERVAL 2 DAY, '11:30 AM', 'Initial consultation regarding commercial lease agreement dispute.', 'confirmed'),
(2, 2, 'Client Zain', 'zain.client@gmail.com', '+92 300 4445566', 1, 'Wednesday (09:00 AM to 03:00 PM)', CURDATE() + INTERVAL 4 DAY, '10:00 AM', 'Consultation regarding family estate distribution.', 'pending')
ON DUPLICATE KEY UPDATE `name`=VALUES(`name`);

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
