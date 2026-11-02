-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 02, 2025 at 10:28 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

CREATE DATABASE IF NOT EXISTS health_db;
USE health_db;


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `health_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `bmi_records`
--

CREATE TABLE `bmi_records` (
  `username` varchar(50) NOT NULL,
  `bmi_result` decimal(5,2) NOT NULL,
  `category` varchar(50) NOT NULL,
  `recorded_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bmi_records`
--

INSERT INTO `bmi_records` (`username`, `bmi_result`, `category`, `recorded_at`) VALUES
('cccc', 16.60, 'Underweight', '2025-07-02 18:58:21'),
('cccc', 27.40, 'Overweight', '2025-07-02 19:06:12'),
('cccc', 23.90, 'Normal weight', '2025-07-02 19:08:06');

-- --------------------------------------------------------

--
-- Table structure for table `meal_plan`
--

CREATE TABLE `meal_plan` (
  `username` varchar(50) NOT NULL,
  `gender` varchar(50) NOT NULL,
  `age` int(11) NOT NULL,
  `weight` float NOT NULL,
  `height` float NOT NULL,
  `preference` varchar(50) NOT NULL,
  `activity` varchar(50) NOT NULL,
  `goal` varchar(50) NOT NULL,
  `calories` float NOT NULL,
  `feedback` text DEFAULT NULL,
  `suggestion` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `meal_plan`
--

INSERT INTO `meal_plan` (`username`, `gender`, `age`, `weight`, `height`, `preference`, `activity`, `goal`, `calories`, `feedback`, `suggestion`, `created_at`) VALUES
('cccc', 'female', 44, 65, 163, 'non-vegetarian', 'light', 'lose', 1271, 'Grilled chicken salad, Boiled eggs with spinach, Tuna lettuce wraps', NULL, '2025-07-01 18:11:12'),
('cccc', 'male', 23, 55, 176, 'non-vegetarian', 'light', 'gain', 2618, 'You’re in a deficit today — great for fat loss.', 'Beef stew with potatoes, Chicken biryani, Omelette with cheese and toast', '2025-07-02 06:24:11'),
('cccc', 'male', 34, 90, 170, 'non-vegetarian', 'sedentary', 'gain', 2657, 'You’re in a deficit today — great for fat loss.', 'Beef stew with potatoes, Chicken biryani, Omelette with cheese and toast', '2025-07-02 06:25:05'),
('cccc', 'female', 23, 44, 155, 'non-vegetarian', 'sedentary', 'lose', 859, 'You’re in a deficit today — great for fat loss.', 'Grilled chicken salad, Boiled eggs with spinach, Tuna lettuce wraps', '2025-07-02 09:33:01'),
('aaaa', 'male', 23, 66, 177, 'vegetarian', 'sedentary', 'lose', 1488, 'You’re in a deficit today — great for fat loss.', 'Zucchini noodles with pesto, Chickpea salad, Steamed broccoli with tofu', '2025-07-02 18:50:52');

-- --------------------------------------------------------

--
-- Table structure for table `meal_tracker`
--

CREATE TABLE `meal_tracker` (
  `meal_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `meal_name` varchar(100) NOT NULL,
  `calories` int(11) NOT NULL,
  `protein` int(11) NOT NULL,
  `carbs` int(11) NOT NULL,
  `fat` int(11) NOT NULL,
  `vitaminC` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `meal_tracker`
--

INSERT INTO `meal_tracker` (`meal_id`, `username`, `meal_name`, `calories`, `protein`, `carbs`, `fat`, `vitaminC`, `created_at`) VALUES
(1, 'aaaa', 'burger', 11, 1, 1, 1, 1, '2025-07-02 20:19:31'),
(2, 'aaaa', 'burger', 11, 1, 1, 1, 1, '2025-07-02 20:19:44'),
(3, 'aaaa', 'nasi lemak', 150, 1, 1, 1, 1, '2025-07-02 20:22:53');

-- --------------------------------------------------------

--
-- Table structure for table `sleep_data`
--

CREATE TABLE `sleep_data` (
  `username` varchar(50) NOT NULL,
  `hours` decimal(3,1) NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sleep_data`
--

INSERT INTO `sleep_data` (`username`, `hours`, `date`) VALUES
('cccc', 3.0, '2025-06-28'),
('cccc', 7.0, '2025-06-28'),
('cccc', 8.0, '2025-06-28'),
('cccc', 5.0, '2025-06-30'),
('cccc', 6.0, '2025-06-30'),
('cccc', 6.0, '2025-07-01'),
('cccc', 7.0, '2025-07-01'),
('aaaa', 2.0, '2025-07-03'),
('aaaa', 5.0, '2025-07-03');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `username` varchar(50) NOT NULL,
  `FirstName` varchar(50) DEFAULT NULL,
  `LastName` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `DoB` date DEFAULT NULL,
  `height` decimal(5,2) DEFAULT NULL,
  `weight` decimal(5,2) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`username`, `FirstName`, `LastName`, `email`, `phone`, `DoB`, `height`, `weight`, `password`) VALUES
('aaaa', 'haha', 'ahaha', 'ahaha@haha.com', '01234567890', '2002-12-12', 155.00, 45.00, '$2y$10$HJq6NG1G8DYq2xP.ps1Weu37dLLbSWLow.UVRQRsx.OGAsF6WEV3K'),
('cccc', 'aaa', 'bbb', 'abc@abc.def', '0111111111', '2001-12-12', 123.00, 23.00, '$2y$10$3UuxJTb8/U8uElS61ZeMX.YH0FY1ELH6im2XiDDmDhOp68PZ31mGS');

-- --------------------------------------------------------

--
-- Table structure for table `water_intake`
--

CREATE TABLE `water_intake` (
  `username` varchar(50) NOT NULL,
  `amount` int(11) NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `water_intake`
--

INSERT INTO `water_intake` (`username`, `amount`, `date`) VALUES
('cccc', 200, '2025-06-28'),
('cccc', 300, '2025-06-28'),
('cccc', 350, '2025-06-28'),
('cccc', 300, '2025-06-30'),
('cccc', 500, '2025-07-01'),
('aaaa', 200, '2025-07-03'),
('aaaa', 150, '2025-07-03');

-- --------------------------------------------------------

--
-- Table structure for table `workouts`
--

CREATE TABLE `workouts` (
  `username` varchar(50) NOT NULL,
  `goal` varchar(50) NOT NULL,
  `plan` text NOT NULL,
  `date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `workouts`
--

INSERT INTO `workouts` (`username`, `goal`, `plan`, `date`) VALUES
('cccc', 'weight_loss', '30 minutes of brisk walking or jogging, 20 minutes of HIIT (High Intensity Interval Training), Bodyweight exercises: 3 sets of 15 squats, 10 push-ups, 20 lunges', '2025-06-28 17:28:31'),
('cccc', 'flexibility', '30 minutes of yoga or pilates, Dynamic stretching before workout, static stretching after, Focus on major muscle groups: hamstrings, shoulders, hips', '2025-06-30 22:22:11'),
('cccc', 'muscle_gain', 'Weight training: 4 sets of 8-12 reps for major muscle groups, Focus on compound movements: bench press, squats, deadlifts, Rest 60-90 seconds between sets', '2025-07-01 07:16:55'),
('cccc', 'weight_loss', '30 minutes of brisk walking or jogging, 20 minutes of HIIT (High Intensity Interval Training), Bodyweight exercises: 3 sets of 15 squats, 10 push-ups, 20 lunges', '2025-07-01 11:44:43'),
('aaaa', 'endurance', '45-60 minutes of moderate cardio (running, cycling, swimming), Circuit training with minimal rest between exercises, Try interval training: 1 minute sprint, 2 minutes walk, repeat 10 times', '2025-07-03 02:33:45');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bmi_records`
--
ALTER TABLE `bmi_records`
  ADD KEY `username` (`username`);

--
-- Indexes for table `meal_plan`
--
ALTER TABLE `meal_plan`
  ADD KEY `username` (`username`);

--
-- Indexes for table `meal_tracker`
--
ALTER TABLE `meal_tracker`
  ADD PRIMARY KEY (`meal_id`);

--
-- Indexes for table `sleep_data`
--
ALTER TABLE `sleep_data`
  ADD KEY `idx_workouts_user_date` (`username`,`date`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `water_intake`
--
ALTER TABLE `water_intake`
  ADD KEY `idx_workouts_user_date` (`username`,`date`);

--
-- Indexes for table `workouts`
--
ALTER TABLE `workouts`
  ADD KEY `idx_workouts_user_date` (`username`,`date`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `meal_tracker`
--
ALTER TABLE `meal_tracker`
  MODIFY `meal_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bmi_records`
--
ALTER TABLE `bmi_records`
  ADD CONSTRAINT `bmi_records_ibfk_1` FOREIGN KEY (`username`) REFERENCES `users` (`username`);

--
-- Constraints for table `meal_plan`
--
ALTER TABLE `meal_plan`
  ADD CONSTRAINT `meal_plan_ibfk_1` FOREIGN KEY (`username`) REFERENCES `users` (`username`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `sleep_data`
--
ALTER TABLE `sleep_data`
  ADD CONSTRAINT `sleep_data_ibfk_1` FOREIGN KEY (`username`) REFERENCES `users` (`username`) ON DELETE CASCADE;

--
-- Constraints for table `water_intake`
--
ALTER TABLE `water_intake`
  ADD CONSTRAINT `water_intake_ibfk_1` FOREIGN KEY (`username`) REFERENCES `users` (`username`) ON DELETE CASCADE;

--
-- Constraints for table `workouts`
--
ALTER TABLE `workouts`
  ADD CONSTRAINT `workouts_ibfk_1` FOREIGN KEY (`username`) REFERENCES `users` (`username`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
