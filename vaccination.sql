-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 03, 2026 at 02:44 PM
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
-- Database: `vaccination`
--

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `appointment_id` int(11) NOT NULL,
  `child_id` int(11) NOT NULL,
  `hospital_id` int(11) NOT NULL,
  `vaccine_id` int(11) NOT NULL,
  `appointment_date` date NOT NULL,
  `appointment_time` time NOT NULL,
  `appointment_status` varchar(30) NOT NULL DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`appointment_id`, `child_id`, `hospital_id`, `vaccine_id`, `appointment_date`, `appointment_time`, `appointment_status`) VALUES
(1, 7, 1, 1, '0000-00-00', '16:04:00', 'Pending'),
(2, 7, 1, 1, '0000-00-00', '16:04:00', 'Pending'),
(3, 7, 1, 1, '3333-03-03', '14:02:00', 'Pending'),
(4, 8, 4, 5, '0000-00-00', '20:08:00', 'Pending'),
(5, 8, 4, 3, '2026-03-03', '11:30:00', 'Pending'),
(6, 3, 1, 1, '3333-03-03', '15:33:00', 'Pending'),
(7, 7, 5, 1, '2222-02-02', '14:22:00', 'Pending'),
(8, 7, 5, 1, '2222-02-02', '14:22:00', 'Pending'),
(9, 7, 5, 1, '2222-02-02', '14:22:00', 'Pending'),
(10, 7, 5, 1, '2222-02-02', '14:22:00', 'Pending'),
(11, 7, 5, 1, '2222-02-02', '14:22:00', 'Pending'),
(12, 7, 5, 1, '2222-02-02', '14:22:00', 'Pending'),
(13, 7, 5, 1, '2222-02-02', '14:22:00', 'Pending'),
(14, 7, 2, 1, '2222-02-02', '14:02:00', 'Pending'),
(15, 7, 1, 1, '2222-02-02', '14:22:00', 'Pending'),
(16, 8, 1, 1, '2222-02-02', '14:22:00', 'Pending'),
(17, 7, 1, 1, '2222-02-22', '14:22:00', 'Pending'),
(18, 7, 1, 1, '2222-02-22', '14:22:00', 'Pending'),
(19, 7, 1, 1, '2222-02-22', '11:11:00', 'Pending'),
(20, 8, 1, 1, '5555-05-05', '05:55:00', 'Pending'),
(23, 7, 1, 1, '3333-03-23', '03:33:00', 'Pending'),
(24, 7, 6, 1, '0000-00-00', '11:11:00', 'Completed'),
(25, 8, 6, 3, '3212-02-23', '12:12:00', 'Confirmed');

-- --------------------------------------------------------

--
-- Table structure for table `child`
--

CREATE TABLE `child` (
  `child_id` int(11) NOT NULL,
  `child_name` varchar(50) NOT NULL,
  `date_of_birth` date NOT NULL,
  `parent_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `child`
--

INSERT INTO `child` (`child_id`, `child_name`, `date_of_birth`, `parent_id`) VALUES
(3, 'abc', '4444-04-02', 6),
(4, 'ali', '3333-03-02', 6),
(5, 'ahmed', '2222-02-02', 7),
(7, 'ismail hussain', '0001-01-02', 1),
(8, 'ismail44', '2026-09-03', 1);

-- --------------------------------------------------------

--
-- Table structure for table `hospital`
--

CREATE TABLE `hospital` (
  `hospital_id` int(11) NOT NULL,
  `hospital_name` varchar(100) NOT NULL,
  `hospital_address` text NOT NULL,
  `hospital_email` varchar(100) NOT NULL,
  `hospital_phone` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hospital`
--

INSERT INTO `hospital` (`hospital_id`, `hospital_name`, `hospital_address`, `hospital_email`, `hospital_phone`, `password`) VALUES
(1, 'abc', 'ismailasdfasfasfsdf', 'abc@gmail.com', '2222222', '$2y$10$OLajlrJ6rDnAplK4Q3CHKu3W./SODckyZu5pUVd82ujkSvEkUmhqC'),
(2, 'ismail', 'my address', 'ismail@gmail.com', '22222233333', '$2y$10$xNozJUyzHPRR2b/XiNj4kOGBDcNVSjtrBPYJagoAMydGEiGaBhOIe'),
(3, 'hospital', 'hospital address', 'hospital@gmail.com', '744474747', '$2y$10$JhDxudDmzjvDttyTDWraCOfxaI3MLDmZ5Nb5JoszIzb38w/Ag3u/e'),
(4, 'inilino', 'asdifaksd;ljkaf', 'root@gmai.com', '1293493s', '$2y$10$5HmvugYzGlftMDiAvQevZuyXvjs9PeL.h0kaCxO.EkJZim3/Wb95S'),
(5, 'user', 'usernameabc', 'user@gmail.com', '333333', '$2y$10$FyFqbzxT/h2TgHwiANaXxeXqWwPAVgf0SFhZz/Na4N/.261fMWY2q'),
(6, 'haspatal', 'haspatal123', 'haspatal@gmail.com', '2244338899', '$2y$10$XMos9JjFfniCZqIDBSJ/2eK..lUk5WPCu.EheqN5VpO6HJ0V6.Rwq'),
(7, 'ismail', 'dfasfasf', 'ifi@gmail.com', '34324as', '$2y$10$7DBRz.RaStGmXjN/u1OAweXZ3AqhGZJvup6M9vPNci0soK7gTG.ve'),
(8, 'umer', 'abcdefghijklmnopqrstuvwxyz', 'umer@gmail.com', '82920202', '$2y$10$XgJk8DnveIC0C8pCt04S9O8BmofJ.0W2J0WVqlwdDPfzWTboGdICK');

-- --------------------------------------------------------

--
-- Table structure for table `parents`
--

CREATE TABLE `parents` (
  `parent_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `parents`
--

INSERT INTO `parents` (`parent_id`, `name`, `email`, `password`, `phone`) VALUES
(1, 'ismail', 'ismail@gmai.com', '$2y$10$CyN1mHF0Y8ypGN4UZxeHA.prGU85ysLz1aaKgr1Ym8bZBCNR8/D0u', NULL),
(2, 'ayan@gmail.com', 'ayan@gmail.com', '$2y$10$2AwRN65fvD6Gi2l7aw8kNOZMEiMSIbXZpndCWo5ZaGRzkZ9YtJHmC', '333333'),
(3, 'Ismail Hussain', 'root@gmail.com', '$2y$10$KJb2enKL/Sv1vsC8OuyJ4e5lyui60i2aqK4tMu2kfwL29I/4kR0kG', NULL),
(4, 'ismail', 'ifi@gmail.com', '$2y$10$NYlyhZtfRsosSFdF5w5JgONL4jnD8oU3FN55LAIhFAhsbTdgwe4E.', '234'),
(5, 'xyz', 'xyz@gmail.com', '$2y$10$j7LcF7C1ykA6GR9rIf6h2Ouo6olM1V8vXa.0VmSII0McjfJHh5yhq', NULL),
(6, 'example', 'example@gmail.com', '$2y$10$HdHGgMqMtMtIPlJlLokNaOJFH5499CqReofZgUN58dKjtcj.4DkmC', NULL),
(7, 'the', 'the@gmail.com', '$2y$10$D3Y/XA5jdQhcVX56ZbLNAuDk0jBuy2uJtrYH9Dd6edMHGq6TgD4Fi', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `vaccination_record`
--

CREATE TABLE `vaccination_record` (
  `record_id` int(11) NOT NULL,
  `appointment_id` int(11) NOT NULL,
  `vaccination_status` varchar(50) NOT NULL,
  `vaccination_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vaccination_record`
--

INSERT INTO `vaccination_record` (`record_id`, `appointment_id`, `vaccination_status`, `vaccination_date`) VALUES
(1, 24, 'Vaccinated', '2026-11-03');

-- --------------------------------------------------------

--
-- Table structure for table `vaccines`
--

CREATE TABLE `vaccines` (
  `vaccine_id` int(11) NOT NULL,
  `vaccine_name` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vaccines`
--

INSERT INTO `vaccines` (`vaccine_id`, `vaccine_name`) VALUES
(1, 'BCG'),
(5, 'DTP'),
(3, 'Hepatitis B'),
(4, 'Measles'),
(2, 'Polio');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`appointment_id`),
  ADD KEY `child_id` (`child_id`),
  ADD KEY `hospital_id` (`hospital_id`),
  ADD KEY `vaccine_id` (`vaccine_id`);

--
-- Indexes for table `child`
--
ALTER TABLE `child`
  ADD PRIMARY KEY (`child_id`),
  ADD KEY `parent_id` (`parent_id`);

--
-- Indexes for table `hospital`
--
ALTER TABLE `hospital`
  ADD PRIMARY KEY (`hospital_id`),
  ADD UNIQUE KEY `hospital_email` (`hospital_email`);

--
-- Indexes for table `parents`
--
ALTER TABLE `parents`
  ADD PRIMARY KEY (`parent_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `vaccination_record`
--
ALTER TABLE `vaccination_record`
  ADD PRIMARY KEY (`record_id`),
  ADD UNIQUE KEY `appointment_id` (`appointment_id`);

--
-- Indexes for table `vaccines`
--
ALTER TABLE `vaccines`
  ADD PRIMARY KEY (`vaccine_id`),
  ADD UNIQUE KEY `vaccine_name` (`vaccine_name`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `appointment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `child`
--
ALTER TABLE `child`
  MODIFY `child_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `hospital`
--
ALTER TABLE `hospital`
  MODIFY `hospital_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `parents`
--
ALTER TABLE `parents`
  MODIFY `parent_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `vaccination_record`
--
ALTER TABLE `vaccination_record`
  MODIFY `record_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `vaccines`
--
ALTER TABLE `vaccines`
  MODIFY `vaccine_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appointments`
--
ALTER TABLE `appointments`
  ADD CONSTRAINT `appointments_ibfk_1` FOREIGN KEY (`child_id`) REFERENCES `child` (`child_id`),
  ADD CONSTRAINT `appointments_ibfk_2` FOREIGN KEY (`hospital_id`) REFERENCES `hospital` (`hospital_id`),
  ADD CONSTRAINT `appointments_ibfk_3` FOREIGN KEY (`vaccine_id`) REFERENCES `vaccines` (`vaccine_id`);

--
-- Constraints for table `child`
--
ALTER TABLE `child`
  ADD CONSTRAINT `child_ibfk_1` FOREIGN KEY (`parent_id`) REFERENCES `parents` (`parent_id`);

--
-- Constraints for table `vaccination_record`
--
ALTER TABLE `vaccination_record`
  ADD CONSTRAINT `vaccination_record_ibfk_1` FOREIGN KEY (`appointment_id`) REFERENCES `appointments` (`appointment_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
