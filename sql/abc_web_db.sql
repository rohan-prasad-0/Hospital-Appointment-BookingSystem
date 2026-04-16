-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Apr 16, 2026 at 08:29 PM
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
-- Database: `abc_web_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `admin_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL DEFAULT '',
  `phone` varchar(50) NOT NULL DEFAULT '',
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`admin_id`, `name`, `phone`, `user_id`) VALUES
(1, 'admin', '0785544784', 5);

-- --------------------------------------------------------

--
-- Table structure for table `appointment`
--

CREATE TABLE `appointment` (
  `appointment_id` int(11) NOT NULL,
  `appointment_number` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `doctor_id` int(11) NOT NULL,
  `sch_id` int(11) DEFAULT NULL,
  `status` varchar(10) NOT NULL DEFAULT '',
  `note` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `appointment`
--

INSERT INTO `appointment` (`appointment_id`, `appointment_number`, `patient_id`, `doctor_id`, `sch_id`, `status`, `note`, `created_at`) VALUES
(3, 1, 2, 2, 3, 'Canceled', '', '2026-03-12 11:14:07'),
(5, 2, 2, 2, 3, 'Canceled', '', '2026-03-12 11:09:28'),
(12, 1, 2, 2, 10, 'Cancelled', 'having bad headache', '2026-03-17 09:15:06'),
(16, 1, 2, 2, 10, 'Booked', 'Having bad headache', '2026-03-17 20:50:26'),
(17, 1, 2, 2, 9, 'Booked', '', '2026-03-17 20:51:03'),
(18, 2, 5, 2, 9, 'Cancelled', '', '2026-04-16 14:33:54'),
(19, 1, 5, 8, 11, 'Booked', '', '2026-04-16 14:31:40'),
(20, 1, 5, 8, 16, 'Booked', '', '2026-04-16 14:38:42');

-- --------------------------------------------------------

--
-- Table structure for table `doctor`
--

CREATE TABLE `doctor` (
  `doctor_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL DEFAULT '',
  `sp_id` int(11) NOT NULL DEFAULT 0,
  `phone` varchar(15) NOT NULL DEFAULT '',
  `gender` varchar(6) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `doctor`
--

INSERT INTO `doctor` (`doctor_id`, `user_id`, `name`, `sp_id`, `phone`, `gender`) VALUES
(2, 4, 'Jenny Fernando', 2, '0745511541', 'Female'),
(8, 21, 'Bathiya Jayakodi', 3, '0712345198', 'Male'),
(9, 22, 'Uvindu Harshana', 4, '0761254896', 'Male'),
(10, 23, 'Supun Harshana', 2, '0742361265', 'Male');

-- --------------------------------------------------------

--
-- Table structure for table `doctor_schedule`
--

CREATE TABLE `doctor_schedule` (
  `sch_id` int(11) NOT NULL,
  `doctor_id` int(11) NOT NULL,
  `available_date` date NOT NULL,
  `time_slot` time NOT NULL,
  `max_patient` int(2) NOT NULL,
  `status` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `doctor_schedule`
--

INSERT INTO `doctor_schedule` (`sch_id`, `doctor_id`, `available_date`, `time_slot`, `max_patient`, `status`) VALUES
(3, 2, '2026-08-04', '10:00:00', 3, 'Available'),
(9, 2, '2026-07-01', '17:00:00', 10, 'Available'),
(10, 2, '2026-05-01', '18:30:00', 10, 'Available'),
(11, 8, '2026-04-30', '08:00:00', 10, 'Available'),
(13, 9, '2026-04-29', '08:00:00', 10, 'Available'),
(14, 9, '2026-04-28', '18:00:00', 10, 'Available'),
(15, 10, '2026-04-27', '09:00:00', 10, 'Available'),
(16, 8, '2026-04-28', '08:00:00', 1, 'Available'),
(17, 8, '2026-04-23', '08:00:00', 5, 'Available');

-- --------------------------------------------------------

--
-- Table structure for table `patient`
--

CREATE TABLE `patient` (
  `patient_id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL DEFAULT '',
  `dob` date NOT NULL,
  `gender` varchar(6) NOT NULL DEFAULT '',
  `phone` varchar(15) NOT NULL DEFAULT '',
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `patient`
--

INSERT INTO `patient` (`patient_id`, `name`, `dob`, `gender`, `phone`, `user_id`) VALUES
(2, 'John Doe', '2000-06-14', 'male', '078445121', 2),
(4, 'Jimmy', '1999-01-01', 'Male', '0724455478', 19),
(5, 'alex', '1999-12-10', 'Female', '07455441125', 20),
(6, 'Fathima Aathif', '2003-03-19', 'Male', '0741523649', 24);

-- --------------------------------------------------------

--
-- Table structure for table `receptionist`
--

CREATE TABLE `receptionist` (
  `recep_id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL DEFAULT '',
  `gender` varchar(50) DEFAULT NULL,
  `phone` varchar(15) NOT NULL DEFAULT '',
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `receptionist`
--

INSERT INTO `receptionist` (`recep_id`, `name`, `gender`, `phone`, `user_id`) VALUES
(4, 'Jack', 'Male', '0785544514', 17),
(6, 'Theekshana Bandara', 'Male', '0741236547', 26);

-- --------------------------------------------------------

--
-- Table structure for table `specialization`
--

CREATE TABLE `specialization` (
  `sp_id` int(11) NOT NULL,
  `sp_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `specialization`
--

INSERT INTO `specialization` (`sp_id`, `sp_name`) VALUES
(1, 'Cardiology'),
(2, 'Dermatology'),
(3, 'Neurology'),
(4, 'Orthopedics');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_id` int(11) NOT NULL,
  `email` varchar(200) NOT NULL DEFAULT '',
  `access_code` varchar(255) NOT NULL DEFAULT '',
  `role` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `email`, `access_code`, `role`) VALUES
(2, 'john@gmail.com', '$2y$10$mHWz6EG48vy62T7pOAN/lO2alWagDu4n7MFyEt/OKRhLFcsYaG9te', 'Patient'),
(4, 'jenny@gmail.com', '$2y$10$OeuzYfmTlv4SPQo2XIVIoOTi2xaVpOoA1WQK772fukTYUxfzrAwTi', 'Doctor'),
(5, 'admin@gmail.com', '$2y$10$mHWz6EG48vy62T7pOAN/lO2alWagDu4n7MFyEt/OKRhLFcsYaG9te', 'Admin'),
(17, 'jack@gmail.com', '$2y$10$xfxV6dmkS.EXk0g7MPqj9u0ldNP/G2wS8ks0mUOpDIwDTTe62HCP2', 'Receptionist'),
(19, 'jimmy@gmail.com', '$2y$10$YF2MoBBJwdpmfZYHPJgrv.jGRE3j66PtVAGvhIvo7ncSivvbnpOE6', 'Patient'),
(20, 'alex@gmail.com', '$2y$10$OeuzYfmTlv4SPQo2XIVIoOTi2xaVpOoA1WQK772fukTYUxfzrAwTi', 'Patient'),
(21, 'bathiya@gmail.com', '$2y$10$e0S.S9s29JaoqZFP.M/XFuXimWAkJjHv41EblsYv042O9corcBlwC', 'Doctor'),
(22, 'uvindu@gmail.com', '$2y$10$zQ7TIaw.B33awc6cKrtUY.z0pka42x.fwtd0oBeE585CwZA1RiiLu', 'Doctor'),
(23, 'supun@gmail.com', '$2y$10$tnD5iTQ/6ap2kkgrs0sOgOYF5XpjBAoBvY4hQF701qJXVPlctgUVm', 'Doctor'),
(24, 'aathif@gmail.com', '$2y$10$gmaMrLnk22VkzvJ5Q2fXeeS3y/IRMSOGiGnq9YR/TQGQBWkLDmsh6', 'Patient'),
(26, 'theekshana@gmail.com', '$2y$10$whSix3Mjn37BIighmYjjkOE0I61qBA3l44meh20OmanxhtWP9x5vm', 'Receptionist');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`),
  ADD KEY `FK_admin_user` (`user_id`);

--
-- Indexes for table `appointment`
--
ALTER TABLE `appointment`
  ADD PRIMARY KEY (`appointment_id`),
  ADD KEY `FK_appointment_patient` (`patient_id`),
  ADD KEY `FK_appointment_doctor` (`doctor_id`),
  ADD KEY `FK_appointment_doctor_schedule` (`sch_id`);

--
-- Indexes for table `doctor`
--
ALTER TABLE `doctor`
  ADD PRIMARY KEY (`doctor_id`),
  ADD KEY `FK_doctor_user` (`user_id`),
  ADD KEY `FK_doctor_specialization` (`sp_id`);

--
-- Indexes for table `doctor_schedule`
--
ALTER TABLE `doctor_schedule`
  ADD PRIMARY KEY (`sch_id`),
  ADD KEY `FK_doctor_schedule_doctor` (`doctor_id`);

--
-- Indexes for table `patient`
--
ALTER TABLE `patient`
  ADD PRIMARY KEY (`patient_id`),
  ADD KEY `FK_patient_user` (`user_id`);

--
-- Indexes for table `receptionist`
--
ALTER TABLE `receptionist`
  ADD PRIMARY KEY (`recep_id`),
  ADD KEY `FK_receptionist_user` (`user_id`);

--
-- Indexes for table `specialization`
--
ALTER TABLE `specialization`
  ADD PRIMARY KEY (`sp_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `appointment`
--
ALTER TABLE `appointment`
  MODIFY `appointment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `doctor`
--
ALTER TABLE `doctor`
  MODIFY `doctor_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `doctor_schedule`
--
ALTER TABLE `doctor_schedule`
  MODIFY `sch_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `patient`
--
ALTER TABLE `patient`
  MODIFY `patient_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `receptionist`
--
ALTER TABLE `receptionist`
  MODIFY `recep_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `specialization`
--
ALTER TABLE `specialization`
  MODIFY `sp_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin`
--
ALTER TABLE `admin`
  ADD CONSTRAINT `FK_admin_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `appointment`
--
ALTER TABLE `appointment`
  ADD CONSTRAINT `FK_appointment_doctor` FOREIGN KEY (`doctor_id`) REFERENCES `doctor` (`doctor_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_appointment_doctor_schedule` FOREIGN KEY (`sch_id`) REFERENCES `doctor_schedule` (`sch_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_appointment_patient` FOREIGN KEY (`patient_id`) REFERENCES `patient` (`patient_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `doctor`
--
ALTER TABLE `doctor`
  ADD CONSTRAINT `FK_doctor_specialization` FOREIGN KEY (`sp_id`) REFERENCES `specialization` (`sp_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_doctor_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `doctor_schedule`
--
ALTER TABLE `doctor_schedule`
  ADD CONSTRAINT `FK_doctor_schedule_doctor` FOREIGN KEY (`doctor_id`) REFERENCES `doctor` (`doctor_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `patient`
--
ALTER TABLE `patient`
  ADD CONSTRAINT `FK_patient_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
