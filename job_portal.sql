-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Dec 26, 2025 at 06:26 AM
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
-- Database: `job_portal`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `name`, `email`, `password`, `created_at`) VALUES
(1, 'Admin', 'admin@test.com', '$2y$10$7yQKIQDfBYCO.k5nIMVBRO1Z40cEXddirLmqembFVdJu1wDoUjJj2', '2025-12-24 17:57:52');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `location` varchar(150) NOT NULL,
  `company_name` varchar(255) NOT NULL,
  `company_logo` varchar(500) DEFAULT NULL,
  `salary_range` varchar(100) DEFAULT NULL,
  `experience` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jobs`
--

INSERT INTO `jobs` (`id`, `title`, `location`, `company_name`, `company_logo`, `salary_range`, `experience`, `created_at`) VALUES
(1, 'Flutter Developer', 'Bhubaneswar', 'ABC Pvt Ltd', NULL, '₹6–10 LPA', '2–4 Years', '2025-12-24 20:13:31'),
(3, 'NodeJs Developer Intern', 'Bhubaneswar', 'ABC Pvt Ltd', NULL, '₹1–2 LPA', 'Fresher', '2025-12-24 20:18:01'),
(4, 'ReactJs Developer Intern', 'Bhubaneswar', 'ABC Pvt Ltd', NULL, '₹1–2 LPA', 'Fresher', '2025-12-24 20:29:47'),
(5, 'Python Developer Intern', 'Bhubaneswar', 'ABC Pvt Ltd', NULL, '₹1–2 LPA', 'Fresher', '2025-12-24 20:48:07'),
(6, 'Java Developer Intern', 'Bhubaneswar', 'ABC Pvt Ltd', NULL, '₹1–2 LPA', 'Fresher', '2025-12-24 20:51:02');

-- --------------------------------------------------------

--
-- Table structure for table `job_descriptions`
--

CREATE TABLE `job_descriptions` (
  `id` int(11) NOT NULL,
  `job_id` int(11) NOT NULL,
  `point` text NOT NULL,
  `sort_order` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `job_descriptions`
--

INSERT INTO `job_descriptions` (`id`, `job_id`, `point`, `sort_order`) VALUES
(1, 1, 'Build mobile apps using Flutter', 1),
(2, 1, 'Work with REST APIs', 2),
(7, 3, 'Build mobile apps using Flutter', 1),
(8, 3, 'Work with REST APIs', 2),
(9, 4, 'Build mobile apps using Flutter', 1),
(10, 4, 'Work with REST APIs', 2),
(11, 5, 'Build mobile apps using Flutter', 1),
(12, 5, 'Work with REST APIs', 2),
(13, 6, 'Build mobile apps using Flutter', 1),
(14, 6, 'Work with REST APIs', 2);

-- --------------------------------------------------------

--
-- Table structure for table `job_qualifications`
--

CREATE TABLE `job_qualifications` (
  `id` int(11) NOT NULL,
  `job_id` int(11) NOT NULL,
  `point` text NOT NULL,
  `sort_order` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `job_qualifications`
--

INSERT INTO `job_qualifications` (`id`, `job_id`, `point`, `sort_order`) VALUES
(1, 1, 'B.Tech / MCA', 1),
(5, 4, 'B.Tech / MCA', 1),
(6, 5, 'B.Tech / MCA', 1),
(7, 6, 'B.Tech / MCA', 1),
(8, 3, 'B.Tech', 1),
(9, 3, 'MCA', 2);

-- --------------------------------------------------------

--
-- Table structure for table `job_responsibilities`
--

CREATE TABLE `job_responsibilities` (
  `id` int(11) NOT NULL,
  `job_id` int(11) NOT NULL,
  `point` text NOT NULL,
  `sort_order` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `job_responsibilities`
--

INSERT INTO `job_responsibilities` (`id`, `job_id`, `point`, `sort_order`) VALUES
(1, 1, 'Write clean code', 1),
(2, 1, 'Fix bugs', 2),
(7, 3, 'Write clean code', 1),
(8, 3, 'Fix bugs', 2),
(9, 4, 'Write clean code', 1),
(10, 4, 'Fix bugs', 2),
(11, 5, 'Write clean code', 1),
(12, 5, 'Fix bugs', 2),
(13, 6, 'Write clean code', 1),
(14, 6, 'Fix bugs', 2);

-- --------------------------------------------------------

--
-- Table structure for table `job_skills`
--

CREATE TABLE `job_skills` (
  `id` int(11) NOT NULL,
  `job_id` int(11) NOT NULL,
  `point` text NOT NULL,
  `sort_order` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `job_skills`
--

INSERT INTO `job_skills` (`id`, `job_id`, `point`, `sort_order`) VALUES
(1, 1, 'Flutter', 1),
(2, 1, 'Dart', 2),
(3, 1, 'Git', 3),
(19, 3, 'Flutter', 1),
(20, 3, 'Dart', 2),
(21, 3, 'Git', 3),
(22, 4, 'Flutter', 1),
(23, 4, 'Dart', 2),
(24, 4, 'Git', 3),
(25, 5, 'Flutter', 1),
(26, 5, 'Dart', 2),
(27, 5, 'Git', 3),
(28, 6, 'Flutter', 1),
(29, 6, 'Dart', 2),
(30, 6, 'Git', 3);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `job_descriptions`
--
ALTER TABLE `job_descriptions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `job_id` (`job_id`);

--
-- Indexes for table `job_qualifications`
--
ALTER TABLE `job_qualifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `job_id` (`job_id`);

--
-- Indexes for table `job_responsibilities`
--
ALTER TABLE `job_responsibilities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `job_id` (`job_id`);

--
-- Indexes for table `job_skills`
--
ALTER TABLE `job_skills`
  ADD PRIMARY KEY (`id`),
  ADD KEY `job_id` (`job_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `job_descriptions`
--
ALTER TABLE `job_descriptions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `job_qualifications`
--
ALTER TABLE `job_qualifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `job_responsibilities`
--
ALTER TABLE `job_responsibilities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `job_skills`
--
ALTER TABLE `job_skills`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `job_descriptions`
--
ALTER TABLE `job_descriptions`
  ADD CONSTRAINT `job_descriptions_ibfk_1` FOREIGN KEY (`job_id`) REFERENCES `jobs` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `job_qualifications`
--
ALTER TABLE `job_qualifications`
  ADD CONSTRAINT `job_qualifications_ibfk_1` FOREIGN KEY (`job_id`) REFERENCES `jobs` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `job_responsibilities`
--
ALTER TABLE `job_responsibilities`
  ADD CONSTRAINT `job_responsibilities_ibfk_1` FOREIGN KEY (`job_id`) REFERENCES `jobs` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `job_skills`
--
ALTER TABLE `job_skills`
  ADD CONSTRAINT `job_skills_ibfk_1` FOREIGN KEY (`job_id`) REFERENCES `jobs` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
