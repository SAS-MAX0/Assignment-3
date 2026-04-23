CREATE DATABASE IF NOT EXISTS software_projects;
USE software_projects;

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Table structure for table `projects`
--
CREATE TABLE `projects` (
  `pid` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `short_description` text DEFAULT NULL,
  `phase` enum('design','development','testing','deployment','complete') NOT NULL,
  `uid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `projects`
--
INSERT INTO `projects` (`pid`, `title`, `start_date`, `end_date`, `short_description`, `phase`, `uid`) VALUES
(1, 'Online Shopping Website', '2024-01-10', '2024-03-15', 'Building a website where customers can buy clothes and shoes online.', 'complete', 1),
(2, 'Mobile Banking App', '2024-02-01', '2024-06-30', 'Creating an app so customers can check their balance and transfer money.', 'development', 2),
(3, 'Restaurant Booking System', '2024-03-05', NULL, 'Making a system where people can book tables at restaurants.', 'design', 3),
(4, 'School Attendance Tracker', '2023-09-01', '2024-01-20', 'Building software for teachers to track which students are present each day.', 'complete', 1),
(5, 'Fitness Workout App', '2024-04-12', NULL, 'Creating an app that shows exercise videos and tracks workout progress.', 'development', 4),
(6, 'Company Email Newsletter', '2024-05-01', NULL, 'Setting up automated emails to send weekly updates to customers.', 'testing', 2),
(7, 'Parking Lot Management', '2024-01-15', '2024-04-10', 'Building a system to manage parking spaces and payments at the city mall.', 'complete', 5),
(8, 'Online Quiz Platform', '2024-06-01', NULL, 'Making a website where teachers can create quizzes for students.', 'design', 3),
(9, 'Inventory Tracking System', '2024-03-20', NULL, 'Creating software to track products in the warehouse.', 'development', 6),
(10, 'Customer Support Chatbot', '2024-05-15', NULL, 'Building a chat system that answers common customer questions automatically.', 'testing', 1),
(11, 'Photo Gallery Website', '2023-11-10', '2024-02-05', 'Making a website where users can upload and share their photos.', 'complete', 4),
(12, 'Delivery Tracking App', '2024-04-01', NULL, 'Creating an app so customers can see where their package is in real-time.', 'development', 5),
(13, 'Employee Time Clock', '2024-02-20', '2024-05-30', 'Building a system where workers can clock in and out each day.', 'deployment', 2),
(14, 'Recipe Sharing Website', '2024-06-10', NULL, 'Making a site where people can post and find cooking recipes.', 'design', 6),
(15, 'Appointment Scheduler', '2024-01-05', '2024-03-25', 'Creating a booking system for doctors and patients.', 'complete', 3),
(16, 'Weather Forecast App', '2024-05-20', NULL, 'Building an app that shows the weather for the next 7 days.', 'development', 1),
(17, 'Library Book System', '2024-03-15', NULL, 'Making software to check out books and manage library inventory.', 'testing', 4),
(18, 'Expense Tracker', '2024-04-25', NULL, 'Creating an app where users can record their daily spending.', 'development', 6),
(19, 'Video Streaming Platform', '2023-08-01', '2024-01-30', 'Building a website where users can watch and upload videos.', 'complete', 5),
(20, 'Job Board Website', '2024-06-05', NULL, 'Making a site where companies can post jobs and people can apply.', 'design', 2);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--
CREATE TABLE `users` (
  `uid` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--
INSERT INTO `users` (`uid`, `username`, `password`, `email`) VALUES
(1, 'Samad', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'samad@company.com'),
(2, 'Mike_Chen', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'mike.chen@company.com'),
(3, 'Emily_Brown', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'emily.brown@company.com'),
(4, 'David_Lee', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'david.lee@company.com'),
(5, 'Lisa_Martinez', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'lisa.martinez@company.com'),
(6, 'Tom_Anderson', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'tom.anderson@company.com'),
(7, 'Rachel_Taylor', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'rachel.taylor@company.com'),
(8, 'John_Wilson', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'john.wilson@company.com'),
(9, 'Amy_Garcia', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'amy.garcia@company.com'),
(10, 'Kevin_White', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'kevin.white@company.com'),
(11, 'X', '$2y$10$KmmR2sa7nK1RfGyZQ4c.Ouo3sdiUmceA/2nHPixNzqoJBTxaQxctO', 'xax@g.com');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`pid`),
  ADD KEY `uid` (`uid`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`uid`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `pid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `uid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `projects`
--
ALTER TABLE `projects`
  ADD CONSTRAINT `projects_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `users` (`uid`) ON DELETE CASCADE;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
