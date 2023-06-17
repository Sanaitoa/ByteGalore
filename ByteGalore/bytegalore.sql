-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 30, 2023 at 10:27 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.0.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bytegalore`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`) VALUES
(1, 'admin1', 'password1'),
(2, 'admin2', 'password2'),
(3, 'admin3', 'password3');

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `num_people` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `food_order` text DEFAULT NULL,
  `special_request` text DEFAULT NULL,
  `total_order` decimal(10,2) DEFAULT NULL,
  `reservation_fee` decimal(10,2) DEFAULT NULL,
  `proof_of_payment` varchar(255) DEFAULT NULL,
  `reference_number` varchar(255) DEFAULT NULL,
  `appointment_time` time DEFAULT NULL,
  `appointment_date` date DEFAULT NULL,
  `booking_datetime` datetime DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`id`, `user_id`, `num_people`, `quantity`, `food_order`, `special_request`, `total_order`, `reservation_fee`, `proof_of_payment`, `reference_number`, `appointment_time`, `appointment_date`, `booking_datetime`, `status`) VALUES
(6, 4, 23, NULL, 'a:3:{i:0;s:1:\"1\";i:1;s:1:\"3\";i:2;s:1:\"4\";}', 'sheshh', '121.00', '24.20', NULL, NULL, '20:59:00', '2023-05-15', '2023-05-13 14:56:08', 'Approve'),
(13, 9, 5, 8, 'a:2:{i:0;s:1:\"1\";i:1;s:1:\"2\";}', 'mima', '1250.00', '250.00', NULL, NULL, '11:05:00', '2023-05-22', '2023-05-19 11:01:42', 'Approve'),
(15, 5, 5, 2, 'a:3:{i:0;s:1:\"2\";i:1;s:1:\"3\";i:2;s:1:\"5\";}', 'Lemon', '300.00', '60.00', NULL, NULL, '19:48:00', '2023-05-29', '2023-05-19 13:48:25', 'Approve'),
(35, 4, 1, 3, 'a:2:{i:0;s:1:\"1\";i:1;s:1:\"2\";}', 'dvgfh', '450.00', '90.00', NULL, NULL, '23:06:00', '2023-05-18', '2023-05-29 23:01:49', 'Approve'),
(36, 4, 7, 17, 'a:7:{i:0;s:1:\"1\";i:1;s:1:\"2\";i:2;s:1:\"3\";i:3;s:1:\"4\";i:4;s:1:\"6\";i:5;s:2:\"10\";i:6;s:2:\"12\";}', 'Please lang gumana ka na', '3400.00', '680.00', NULL, NULL, '18:40:00', '2023-05-31', '2023-05-30 01:37:04', 'Approve'),
(37, 4, 1, 3, 'a:2:{i:0;s:1:\"1\";i:1;s:1:\"2\";}', 'sdfgh', '850.00', '170.00', NULL, NULL, '17:29:00', '2023-06-07', '2023-05-30 12:23:16', 'Pending');

-- --------------------------------------------------------

--
-- Table structure for table `contact_submissions`
--

CREATE TABLE `contact_submissions` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `food_menu`
--

CREATE TABLE `food_menu` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `food_menu`
--

INSERT INTO `food_menu` (`id`, `name`, `price`) VALUES
(1, 'Lechon (Roast Pork)', '350.00'),
(2, 'Lumpia (Spring Rolls)', '150.00'),
(3, 'Pancit (Filipino Stir-Fried Noodles)', '150.00'),
(4, 'Kare-Kare (Oxtail Stew in Peanut Sauce)', '250.00'),
(5, 'Chicken Adobo', '200.00'),
(6, 'Garlic Rice', '150.00'),
(7, 'Sinigang (Tamarind Soup with Shrimp)', '200.00'),
(8, 'Halo-Halo (Mixed Dessert with Shaved Ice)', '150.00'),
(9, 'Beef Caldereta (Spicy Beef Stew)', '250.00'),
(10, 'Bicol Express (Spicy Pork Stew in Coconut Milk)', '200.00'),
(11, 'Chicken Inasal (Grilled Chicken)', '250.00'),
(12, 'Sisig (Sizzling Pork)', '250.00');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(225) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `profile_picture` varchar(1) NOT NULL,
  `contact_number` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `profile_picture`, `contact_number`) VALUES
(4, 'Yuri Alfrance', 'yurialfrance05@gmail.com', '$2y$10$dTMeuQDaIcfDw3brdSjQ5OZNLGFsLyEFSQOGwrzCTblTS.lQ8lD1y', 'g', '2345'),
(5, 'Khel Ileto', 'khel@gmail.com', '$2y$10$L/FdMUlRZgZK8ePHS8W8YO.88tgi6P/6LiaWdVWB8SvHHDhlPe1X6', 'P', '0912242'),
(9, 'Jamilynn Modelo', 'jam@gmail.com', '$2y$10$6uJhN/O7ZQBXozDcJpPzMeXyZQe.pPhxxJ21VVeZoadQJqUXMSeg6', 'u', '0943455');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `contact_submissions`
--
ALTER TABLE `contact_submissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `food_menu`
--
ALTER TABLE `food_menu`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `contact_submissions`
--
ALTER TABLE `contact_submissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `food_menu`
--
ALTER TABLE `food_menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(225) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appointments`
--
ALTER TABLE `appointments`
  ADD CONSTRAINT `appointments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
