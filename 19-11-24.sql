-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 19, 2024 at 05:25 PM
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
-- Database: `campus`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `admin_id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`admin_id`, `email`, `password`) VALUES
(1, 'admin@gmail.com', '$2y$10$RGfkTJUHs5P0Xe/m3RWXdO/s.ONmVt8RJNDJn..Atm6Fvqn7.dY5G');

-- --------------------------------------------------------

--
-- Table structure for table `applications`
--

CREATE TABLE `applications` (
  `id` int(11) NOT NULL,
  `vacancies_id` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `resume` varchar(255) NOT NULL,
  `cover_page` text NOT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `applications`
--

INSERT INTO `applications` (`id`, `vacancies_id`, `company_id`, `student_id`, `name`, `email`, `resume`, `cover_page`, `status`) VALUES
(13, 1, 1, NULL, 'Student Name', 'student@example.com', 'path/to/resume.pdf', 'Cover page content', 'approved'),
(24, 1, 116, NULL, 'ARPIT', 'arpitkukadiya10@gmail.com', 'uploads/DOC-20240802-WA0002..pdf', 'arpit', 'pending'),
(26, 1, 116, NULL, 'nik', 'arpitkukadiya10@gmail.com', 'uploads/Screenshot_20230125_213227.png', 'hii this arpit kukadiya \r\n', 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `company`
--

CREATE TABLE `company` (
  `company_id` int(11) NOT NULL,
  `company_name` varchar(100) NOT NULL,
  `company_address` varchar(200) NOT NULL,
  `company_website` varchar(200) NOT NULL,
  `contact_person` varchar(200) NOT NULL,
  `email` varchar(50) NOT NULL,
  `role` varchar(50) NOT NULL DEFAULT 'company',
  `password` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `company`
--

INSERT INTO `company` (`company_id`, `company_name`, `company_address`, `company_website`, `contact_person`, `email`, `role`, `password`) VALUES
(1, 'Tech Solutions Pvt. Ltd.', '', '', '', '', 'company', ''),
(112, 'ak', 'as', 'aaaaaaaaaaaaaa', '9999999999', 'arpit1@gmail.com', 'company ', '123'),
(113, 'INFO', 'krishna public school,amreli,Gujarat-365601', 'http://localhost/project1/register.php', 'arpit ', 'demo@gmail.com', 'company', '$2y$10$N.hsedNgSyP25nCyimAQc.QMaRJt17.I76oRezF0R4KG56LcucNJS'),
(114, 'bapu', 'bapu', 'http:/bapu', 'bapu', 'bapu1@gmail.com', 'company', '$2y$10$3tzKTf7P0MMz7cyIjwW0AuVnB4HqzOdILw.bs5kAtQOVe2g9PRH1S'),
(115, 'vvv', 'vvv', 'http://localhost/project1/register.php', 'vvv', 'vv@gmail.com', 'company', '$2y$10$hWLD31pAWbCgg7XUYReXROD6FEyHTaej/VnUmeqvvfWV3JdHsR.vW'),
(116, 'Example Company', '123 Example St', 'http://example.com', 'John Doe', 'contact@example.com', 'company', 'securepassword'),
(117, 'nov', 'nov', 'nov', 'nov', 'nov@gmail.com', 'company', '$2y$10$Rrnwl4T2nMB6XVR9xKQpC.nCpelv9jAy2VTbVzAs8gjhzhjYja04S');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `title` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `date` date NOT NULL,
  `location` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `title`, `description`, `date`, `location`) VALUES
(1, 'Web Development Bootcamp', 'Learn the basics of full-stack web development in this intensive bootcamp.', '2024-10-25', 'Tech Auditorium, Ahmedabad'),
(2, 'AI and Machine Learning Workshop', 'An advanced workshop focusing on AI and ML algorithms and their applications.', '2024-11-02', 'Innovation Hall, Mumbai'),
(3, 'Data Science with Python', 'This workshop will cover data manipulation, analysis, and visualization using Python.', '2024-11-15', 'Data Lab, Bangalore'),
(4, 'Cloud Computing Fundamentals', 'Get introduced to cloud platforms, infrastructure, and services.', '2024-12-05', 'Skyline Tower, Delhi'),
(5, 'Cybersecurity Basics', 'Learn the essentials of protecting data and networks from cyber threats.', '2024-12-12', 'Secure Center, Pune'),
(6, 'sss', 'sssss', '2024-10-31', 'aj'),
(7, 'ds', 'ddddd', '2024-10-31', 'ajddd'),
(8, 'sss', 'ssssssssssssssssssssss', '2024-10-31', 'dddddd');

-- --------------------------------------------------------

--
-- Table structure for table `event_registered`
--

CREATE TABLE `event_registered` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `mobile` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `event_registered`
--

INSERT INTO `event_registered` (`id`, `student_id`, `event_id`, `name`, `email`, `mobile`) VALUES
(19, 0, 7, 'varshil', 'kukadiyavarshil@gmail.com', '56465464'),
(20, 0, 3, 'bapu', 'arpit@gmail.com', '1231212121'),
(21, 0, 1, 'varshil', 'kukadiyavarshil@gmail.com', '56465464');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `student_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(50) NOT NULL,
  `role` varchar(50) NOT NULL DEFAULT 'student',
  `password` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`student_id`, `name`, `email`, `role`, `password`, `created_at`) VALUES
(111, 'varshil', 'v12@gmail.com', 'student', '123', '2024-10-26 01:47:02'),
(116, 'arpit', 'arpitkukadiya10@gmail.com', 'student', '$2y$10$dwzkeistX5fTe52qCSgpuuzHYKItpjFiJaSjK3iOeFTT0Jcj8W6Gy', '2024-10-24 16:57:36');

-- --------------------------------------------------------

--
-- Table structure for table `vacancies`
--

CREATE TABLE `vacancies` (
  `id` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` varchar(500) NOT NULL,
  `company` varchar(50) NOT NULL,
  `posted_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vacancies`
--

INSERT INTO `vacancies` (`id`, `company_id`, `title`, `description`, `company`, `posted_date`) VALUES
(1, 116, 'ds', 'dsff', 'akwew', '2024-09-30'),
(19, 116, 'ds', 'dddd', 'akgg', '2024-10-25'),
(22, 116, 'rk', 'hii', 'abc', '2024-10-25'),
(31, 45, 'hg', 'hiiii', 'ak', '0000-00-00'),
(33, 12222, 'qwq', 'wqw', 'ak', '2024-10-26'),
(34, 55, 'aass', 'asasas', 'ak122', '2024-10-26'),
(41, 1, 'sasas', 'bapu', 'ak', '2024-11-17');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `applications`
--
ALTER TABLE `applications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vacancies_id` (`vacancies_id`),
  ADD KEY `company_id` (`company_id`);

--
-- Indexes for table `company`
--
ALTER TABLE `company`
  ADD PRIMARY KEY (`company_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `event_registered`
--
ALTER TABLE `event_registered`
  ADD PRIMARY KEY (`id`),
  ADD KEY `event_id` (`event_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`student_id`);

--
-- Indexes for table `vacancies`
--
ALTER TABLE `vacancies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `company_id` (`company_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `applications`
--
ALTER TABLE `applications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `company`
--
ALTER TABLE `company`
  MODIFY `company_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=118;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `event_registered`
--
ALTER TABLE `event_registered`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `student_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=117;

--
-- AUTO_INCREMENT for table `vacancies`
--
ALTER TABLE `vacancies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `applications`
--
ALTER TABLE `applications`
  ADD CONSTRAINT `applications_ibfk_1` FOREIGN KEY (`vacancies_id`) REFERENCES `vacancies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `applications_ibfk_2` FOREIGN KEY (`company_id`) REFERENCES `company` (`company_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `event_registered`
--
ALTER TABLE `event_registered`
  ADD CONSTRAINT `event_registered_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
