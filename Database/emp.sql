-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 22, 2024 at 09:53 PM
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
-- Database: `emp`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `admin_id` int(11) NOT NULL,
  `person_id` int(11) NOT NULL,
  `email` varchar(225) NOT NULL,
  `password` varchar(225) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`admin_id`, `person_id`, `email`, `password`) VALUES
(1, 1, 'admin.ali@company.com', '$2y$10$2ai8z2VMFT2wnptHbIq7meiDLWLREhV6K8CGfBzGajLrqoOD0qaVa'),
(2, 2, 'root2@gmail.com', '$2y$10$2ai8z2VMFT2wnptHbIq7meiDLWLREhV6K8CGfBzGajLrqoOD0qaVa');

-- --------------------------------------------------------

--
-- Table structure for table `department`
--

CREATE TABLE `department` (
  `department_id` int(11) NOT NULL,
  `manager_id` int(11) DEFAULT NULL,
  `name` varchar(225) NOT NULL,
  `location` varchar(225) NOT NULL,
  `emps_number` int(12) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `department`
--

INSERT INTO `department` (`department_id`, `manager_id`, `name`, `location`, `emps_number`) VALUES
(1, 2, 'IT', 'Cairo, Egypt', 1),
(103, 3, 'Sales', 'Aswan', 1),
(104, 4, 'Marketing', 'Cairo, Egypt', 1);

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

CREATE TABLE `employee` (
  `employee_id` int(11) NOT NULL,
  `person_id` int(11) NOT NULL,
  `department_id` int(11) DEFAULT NULL,
  `logi_id` int(14) NOT NULL,
  `password` varchar(225) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `employee`
--

INSERT INTO `employee` (`employee_id`, `person_id`, `department_id`, `logi_id`, `password`) VALUES
(1, 4, 1, 123, '123'),
(27, 116, 103, 2000, '$2y$10$2ai8z2VMFT2wnptHbIq7meiDLWLREhV6K8CGfBzGajLrqoOD0qaVa'),
(29, 119, 104, 123, '$2y$10$0jdd9AWIX2pF3HoaOBHbDOxNiIDwmbmnQ9PGXyjk9Slo2SSOKRUTa');

-- --------------------------------------------------------

--
-- Table structure for table `manager`
--

CREATE TABLE `manager` (
  `manager_id` int(11) NOT NULL,
  `person_id` int(11) NOT NULL,
  `email` varchar(225) NOT NULL,
  `password` varchar(225) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `manager`
--

INSERT INTO `manager` (`manager_id`, `person_id`, `email`, `password`) VALUES
(2, 3, 'manager@gmail.com', '123'),
(3, 115, 'example@gmail.com', '$2y$10$A49Vrq64rA/E71EYK5B4LeoF9FkkF6chNorfbv/mjqZXz9zQ3EYAu'),
(4, 117, 'Samir_manager@gmail.com', '$2y$10$R7vDo4HePv.xnWU8QE4h8ezVG/kVJN3CWtWyqtuk8aM5aWhoxD4ga');

-- --------------------------------------------------------

--
-- Table structure for table `person`
--

CREATE TABLE `person` (
  `person_id` int(11) NOT NULL,
  `first_name` varchar(225) NOT NULL,
  `last_name` varchar(225) NOT NULL,
  `age` int(10) NOT NULL,
  `address` varchar(225) NOT NULL,
  `phone_number` varchar(11) NOT NULL,
  `date_of_birth` date NOT NULL,
  `absent_times` int(10) DEFAULT NULL,
  `salary` decimal(20,0) NOT NULL,
  `job_title` varchar(225) NOT NULL,
  `gender` enum('male','female') NOT NULL,
  `role` enum('admin','employee','manager') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `person`
--

INSERT INTO `person` (`person_id`, `first_name`, `last_name`, `age`, `address`, `phone_number`, `date_of_birth`, `absent_times`, `salary`, `job_title`, `gender`, `role`) VALUES
(1, 'Ali', 'Alaa', 40, 'Cairo', '5997403940', '1986-01-01', 3, 35000, 'root', 'male', 'admin'),
(2, 'Ahmed', 'Mohamed', 30, 'Cairo, Egypt', '+2010123486', '1994-01-20', 0, 15000, 'IT Admin', 'male', 'admin'),
(3, 'Nour', 'Ragab', 30, 'Cairo', '', '1994-12-10', 0, 15000, 'IT Manager', 'female', 'manager'),
(4, 'Sara', 'Tamer', 20, 'Cairo', '+2010123486', '2004-12-16', 1, 19000, 'IT Employee', 'female', 'employee'),
(115, 'Roaa', 'Mohamed', 20, 'Menofia', '0106025602', '2004-06-20', NULL, 15300, 'PO', 'female', 'manager'),
(116, 'Ahmed', 'Mohamed', 20, 'Menofia', '01203545330', '2004-06-20', NULL, 15300, 'Backend Developer', 'male', 'employee'),
(117, 'Samir', 'Yousif', 25, 'Menofia, Egypt', '+2011657284', '1999-07-02', NULL, 16700, 'Marketing Manager', 'male', 'manager'),
(119, 'Reham', 'Hassan', 20, 'Cairo, Egypt', '+2012255213', '2004-06-16', NULL, 15300, 'Marketing', 'female', 'employee');

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `report_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `report_content` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `task_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `description` varchar(50) DEFAULT NULL,
  `status` enum('To Do','In Progress','Done') DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`task_id`, `employee_id`, `description`, `status`, `start_date`, `end_date`) VALUES
(4, 1, 'sequence diagram', 'To Do', '2024-12-20', '2024-12-20');

-- --------------------------------------------------------

--
-- Table structure for table `vacations`
--

CREATE TABLE `vacations` (
  `vacation_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `causes` varchar(50) DEFAULT NULL,
  `status` enum('Pending','Approved','Rejected') NOT NULL DEFAULT 'Pending',
  `department_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `vacations`
--

INSERT INTO `vacations` (`vacation_id`, `employee_id`, `start_date`, `end_date`, `causes`, `status`, `department_id`) VALUES
(16, 1, '2024-12-20', '2024-12-21', 'Sick', 'Pending', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`),
  ADD KEY `person_id` (`person_id`);

--
-- Indexes for table `department`
--
ALTER TABLE `department`
  ADD PRIMARY KEY (`department_id`),
  ADD KEY `department_ibfk_1` (`manager_id`);

--
-- Indexes for table `employee`
--
ALTER TABLE `employee`
  ADD PRIMARY KEY (`employee_id`),
  ADD KEY `person_id` (`person_id`),
  ADD KEY `department_id` (`department_id`);

--
-- Indexes for table `manager`
--
ALTER TABLE `manager`
  ADD PRIMARY KEY (`manager_id`),
  ADD KEY `person_id` (`person_id`);

--
-- Indexes for table `person`
--
ALTER TABLE `person`
  ADD PRIMARY KEY (`person_id`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`report_id`),
  ADD KEY `employee_id` (`employee_id`);

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`task_id`),
  ADD KEY `employee_id` (`employee_id`);

--
-- Indexes for table `vacations`
--
ALTER TABLE `vacations`
  ADD PRIMARY KEY (`vacation_id`),
  ADD KEY `employee_id` (`employee_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `department`
--
ALTER TABLE `department`
  MODIFY `department_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=105;

--
-- AUTO_INCREMENT for table `employee`
--
ALTER TABLE `employee`
  MODIFY `employee_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `manager`
--
ALTER TABLE `manager`
  MODIFY `manager_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `person`
--
ALTER TABLE `person`
  MODIFY `person_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=120;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `report_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `task_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `vacations`
--
ALTER TABLE `vacations`
  MODIFY `vacation_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin`
--
ALTER TABLE `admin`
  ADD CONSTRAINT `admin_ibfk_1` FOREIGN KEY (`person_id`) REFERENCES `person` (`person_id`);

--
-- Constraints for table `department`
--
ALTER TABLE `department`
  ADD CONSTRAINT `department_ibfk_1` FOREIGN KEY (`manager_id`) REFERENCES `manager` (`manager_id`) ON DELETE SET NULL;

--
-- Constraints for table `employee`
--
ALTER TABLE `employee`
  ADD CONSTRAINT `employee_ibfk_1` FOREIGN KEY (`department_id`) REFERENCES `department` (`department_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `employee_ibfk_2` FOREIGN KEY (`person_id`) REFERENCES `person` (`person_id`);

--
-- Constraints for table `manager`
--
ALTER TABLE `manager`
  ADD CONSTRAINT `manager_ibfk_1` FOREIGN KEY (`person_id`) REFERENCES `person` (`person_id`);

--
-- Constraints for table `reports`
--
ALTER TABLE `reports`
  ADD CONSTRAINT `reports_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employee` (`employee_id`) ON DELETE CASCADE;

--
-- Constraints for table `tasks`
--
ALTER TABLE `tasks`
  ADD CONSTRAINT `tasks_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employee` (`employee_id`) ON DELETE CASCADE;

--
-- Constraints for table `vacations`
--
ALTER TABLE `vacations`
  ADD CONSTRAINT `vacations_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employee` (`employee_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
