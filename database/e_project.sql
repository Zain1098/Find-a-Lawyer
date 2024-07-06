-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 04, 2024 at 08:37 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `e_project`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `name`, `email`, `password`) VALUES
(1, 'zain', 'za496694@gmail.com', '1122');

-- --------------------------------------------------------

--
-- Table structure for table `appointment`
--

CREATE TABLE `appointment` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` int(11) NOT NULL,
  `lawyer` int(11) NOT NULL,
  `available` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categorie`
--

CREATE TABLE `categorie` (
  `cat_id` int(11) NOT NULL,
  `cat_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categorie`
--

INSERT INTO `categorie` (`cat_id`, `cat_name`) VALUES
(1, 'Divorce (Family Lawyer)'),
(2, 'Criminal Defense lawyer'),
(3, 'Corporate Lawyer (Business Lawyer)'),
(4, 'Immigration Lawyer'),
(5, 'Real State Lawyer'),
(6, 'Tax Lawyer');

-- --------------------------------------------------------

--
-- Table structure for table `lawyer`
--

CREATE TABLE `lawyer` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `last name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `number` int(11) NOT NULL,
  `address` varchar(255) NOT NULL,
  `password` text NOT NULL,
  `dob` varchar(255) NOT NULL,
  `bar council` varchar(255) NOT NULL,
  `since` int(11) NOT NULL,
  `specialist` int(11) NOT NULL,
  `description` varchar(255) NOT NULL,
  `degree` varchar(255) NOT NULL,
  `university` varchar(255) NOT NULL,
  `language` varchar(255) NOT NULL,
  `day` varchar(255) NOT NULL,
  `Time` varchar(255) NOT NULL,
  `fee` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `cover image` varchar(255) NOT NULL,
  `about me` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lawyer`
--

INSERT INTO `lawyer` (`id`, `name`, `last name`, `email`, `number`, `address`, `password`, `dob`, `bar council`, `since`, `specialist`, `description`, `degree`, `university`, `language`, `day`, `Time`, `fee`, `image`, `cover image`, `about me`) VALUES
(1, 'Ammar', 'Motan', 'ammar@gmail.com', 2147483647, 'Orangi Town Karachi', 'lawyer123456', '', 'HXC85462', 5, 1, 'sjakjdjdjdjijdjdjdj', 'LLM', 'Harvard Law', 'English', '[{\"day\":\"Monday\",\"start\":\"9:00 AM\",\"end\":\"3:00 PM\"},{\"day\":\"Tuesday\",\"start\":\"9:00 AM\",\"end\":\"3:00 PM\"},{\"day\":\"Wednesday\",\"start\":\"9:00 AM\",\"end\":\"3:00 PM\"},{\"day\":\"Thursday\",\"start\":\"9:00 AM\",\"end\":\"3:00 PM\"},{\"day\":\"Friday\",\"start\":\"9:00 AM\",\"end\":\"3:0', '', 0, 'ammar.jpeg', '', 'mfcdkhvhjhdhciuhsdhfidhfhsdifhdshfhhshhchodshfohohhshdhcodcoishcoioifhihihoihvohsovoshvosvoihvoihsivhsdddddd'),
(2, 'Ammar', 'taha', 'muhammadahmed8507@gmail.com', 2147483647, 'Orangi Town Karachi', '1122', '', 'HXC85462', 2020, 4, 'Hi', 'LLM', 'Harvard Law', 'English', '[{\"day\":\"Monday\",\"start\":\"8:00 AM\",\"end\":\"8:00 PM\"}]', '', 50000, 'SC-Project-Icon.png', '', 'hiII'),
(3, 'Zain', 'Ansari', 'za496694@gmail.com', 2147483647, 'Orangi Town Karachi', '1122', '', 'HGZ214689', 2020, 3, 'Hi i am a Lawyer', 'LLB', 'harvard', 'French', '[{\"day\":\"Monday\",\"start\":\"8:00 AM\",\"end\":\"4:00 PM\"},{\"day\":\"Tuesday\",\"start\":\"8:00 AM\",\"end\":\"4:00 PM\"},{\"day\":\"Saturday\",\"start\":\"8:00 AM\",\"end\":\"4:00 PM\"}]', '', 15000, '1712905422037.jpg', 'Screenshot 2023-11-19 110243.png', 'I am a best Lawyer in the universe'),
(5, 'Taha', 'Ansari', 'Taha@gmail.com', 2147483647, 'Orangi Town sector 10 hanifabad Karachi', '2233', '', 'HGZ214689', 2017, 6, 'Hi', 'LLB', 'harvard', 'Spanish', '[{\"day\":\"Monday\",\"start\":\"8:00 AM\",\"end\":\"8:00 PM\"}]', '', 150000, '1712905422037.jpg', 'Z A logo.png', 'Hi');

-- --------------------------------------------------------

--
-- Table structure for table `role`
--

CREATE TABLE `role` (
  `role_id` int(11) NOT NULL,
  `role_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `role`
--

INSERT INTO `role` (`role_id`, `role_name`) VALUES
(1, 'User'),
(2, 'lawyer');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `name`, `email`, `password`) VALUES
(1, 'Ammar', 'ammar@gmail.com', 'ammar123456'),
(2, 'Zain', 'zain@gmail.com', 'zain123');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `appointment`
--
ALTER TABLE `appointment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categorie`
--
ALTER TABLE `categorie`
  ADD PRIMARY KEY (`cat_id`);

--
-- Indexes for table `lawyer`
--
ALTER TABLE `lawyer`
  ADD PRIMARY KEY (`id`),
  ADD KEY `specialist` (`specialist`);

--
-- Indexes for table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`role_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `appointment`
--
ALTER TABLE `appointment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categorie`
--
ALTER TABLE `categorie`
  MODIFY `cat_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `lawyer`
--
ALTER TABLE `lawyer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `role`
--
ALTER TABLE `role`
  MODIFY `role_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `lawyer`
--
ALTER TABLE `lawyer`
  ADD CONSTRAINT `lawyer_ibfk_1` FOREIGN KEY (`specialist`) REFERENCES `categorie` (`cat_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
