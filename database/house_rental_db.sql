-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 13, 2024 at 01:35 PM
-- Server version: 10.11.10-MariaDB
-- PHP Version: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `u297599468_ohrmslpa_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(30) NOT NULL,
  `name` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(1, 'Apartment Solo'),
(4, 'Family Home');

-- --------------------------------------------------------

--
-- Table structure for table `houses`
--

CREATE TABLE `houses` (
  `id` int(30) NOT NULL,
  `house_no` varchar(50) NOT NULL,
  `category_id` int(30) NOT NULL,
  `description` text NOT NULL,
  `price` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `houses`
--

INSERT INTO `houses` (`id`, `house_no`, `category_id`, `description`, `price`) VALUES
(1, '0001', 4, '- Two Bedrooms with Kitchen, and One Bathroom (Own Electric and Water Bill)', 4000),
(2, '0002', 4, '- Two Bedrooms with Kitchen, and One Bathroom (Own Electric and Water Bill)', 4000),
(3, '0003', 4, '- Two Bedrooms with Kitchen, and One Bathroom (Own Electric and Water Bill)', 4000),
(4, '0004', 4, '- Two Bedrooms with Kitchen, and One Bathroom (Own Electric and Water Bill)', 4000),
(5, '0005', 4, '- Two Bedrooms with Kitchen, and One Bathroom (Own Electric and Water Bill)', 4000),
(6, '0006', 1, '- Studio Type with Kitchen and Bathroom (Own Electric and Water Bill)', 3500),
(7, '0007', 1, '- Studio Type with Kitchen and Bathroom (Own Electric and Water Bill)', 3500),
(8, '0008', 1, '- Studio Type with Kitchen and Bathroom (Own Electric and Water Bill)', 3500),
(9, '0009', 1, '- Studio Type with Kitchen and Bathroom (Own Electric and Water Bill)', 3500),
(10, '0010', 1, '- Studio Type with Kitchen and Bathroom (Own Electric and Water Bill)', 3500),
(11, '0011', 4, ' - Two Bedrooms with Kitchen, and One Bathroom (Own Electric and Water Bill)', 4000),
(12, '0012', 4, ' - Two Bedrooms with Kitchen, and One Bathroom (Own Electric and Water Bill)', 4000),
(13, '0013', 1, '- Studio Type with Kitchen and Bathroom (Own Electric and Water Bill)', 3500),
(14, '0014', 1, '- Studio Type with Kitchen and Bathroom (Own Electric and Water Bill)', 3500),
(15, '0015', 1, '- Studio Type with Kitchen and Bathroom (Own Electric and Water Bill)', 3500),
(16, '0016', 1, '- Studio Type with Kitchen and Bathroom (Own Electric and Water Bill)', 3500),
(17, '0017', 1, '- Studio Type with Kitchen and Bathroom (Own Electric and Water Bill)', 3500),
(18, '0018', 1, '- Studio Type with Kitchen and Bathroom (Own Electric and Water Bill)', 3500),
(19, '0019', 1, '- Studio Type with Kitchen and Bathroom (Own Electric and Water Bill)', 3500),
(20, '0020', 1, '- Studio Type with Kitchen and Bathroom (Own Electric and Water Bill)', 3500);

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(30) NOT NULL,
  `tenant_id` int(30) NOT NULL,
  `amount` float NOT NULL,
  `invoice` varchar(50) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `tenant_id`, `amount`, `invoice`, `date_created`) VALUES
(20, 14, 4000, 'June 1', '2024-06-01 09:34:30'),
(21, 14, 4000, 'July 1', '2024-07-01 09:34:45'),
(22, 14, 4000, 'Aug 1 ', '2024-08-01 09:35:02'),
(23, 14, 4000, 'Sept 1', '2024-10-01 09:35:56'),
(24, 14, 4000, 'Oct 1', '2024-10-01 09:36:11'),
(26, 14, 4000, 'Nov 1', '2024-11-13 10:00:43'),
(27, 12, 3500, 'June1', '2024-06-01 10:05:15'),
(28, 12, 3500, 'July 1', '2024-07-01 10:05:32'),
(29, 12, 3500, 'Aug 1', '2024-08-01 10:05:48'),
(30, 12, 3500, 'Sept 1', '2024-09-01 10:06:24'),
(31, 12, 3500, 'Oct 1', '2024-10-01 10:07:21'),
(32, 12, 3500, 'Nov 1', '2024-11-13 10:07:32'),
(33, 11, 3500, 'June 1', '2024-06-01 10:08:12'),
(34, 11, 3500, 'July 1', '2024-07-01 10:08:25'),
(35, 11, 3500, 'Aug 1', '2024-08-01 10:09:11'),
(36, 11, 3500, 'Sept', '2024-09-01 10:09:32'),
(37, 11, 3500, 'Oct', '2024-10-01 10:09:44'),
(39, 11, 3500, 'Nov 1', '2024-11-13 10:14:30'),
(40, 13, 4000, 'June', '2024-06-01 10:14:54'),
(41, 13, 4000, 'July', '2024-07-01 10:15:06'),
(42, 13, 4000, 'Sept', '2024-08-01 10:15:22'),
(43, 13, 4000, 'Aug', '2024-09-01 10:15:35'),
(44, 13, 4000, 'Oct', '2024-09-01 10:16:49'),
(45, 13, 4000, 'Nov', '2024-10-01 10:17:05'),
(46, 8, 3500, 'June', '2024-06-01 10:18:41'),
(47, 8, 3500, 'July', '2024-07-01 10:18:52'),
(48, 8, 3500, 'Aug', '2024-08-01 10:19:06'),
(49, 8, 3500, 'Sept', '2024-09-01 10:19:50'),
(50, 8, 3500, 'Oct', '2024-10-01 10:20:40'),
(51, 8, 3500, 'Nov1', '2024-11-13 10:20:56'),
(52, 5, 4000, 'June', '2024-06-01 10:39:43'),
(53, 5, 4000, 'July 1', '2024-07-01 10:40:10'),
(54, 5, 4000, 'Aug', '2024-08-01 10:40:25'),
(55, 5, 4000, 'Sept', '2024-09-01 10:40:39'),
(56, 5, 4000, 'Oct', '2024-10-01 10:40:55'),
(57, 5, 4000, 'Nov', '2024-11-13 10:41:10'),
(58, 21, 3500, 'June', '2024-06-01 10:46:47'),
(59, 21, 3500, 'July', '2024-07-01 10:47:01'),
(60, 21, 3500, 'Aug', '2024-08-01 10:47:48'),
(61, 21, 3500, 'Sept', '2024-09-02 10:48:20'),
(62, 21, 3500, 'Oct', '2024-10-01 10:48:32'),
(63, 21, 3500, 'Nov', '2024-11-13 10:48:56'),
(64, 17, 3500, 'June', '2024-06-01 10:51:09'),
(65, 17, 3500, 'July', '2024-07-01 10:51:22'),
(66, 17, 3500, 'Aug', '2024-08-01 10:51:34'),
(67, 17, 3500, 'Sept', '2024-09-01 10:51:47'),
(68, 17, 3500, 'Oct', '2024-10-01 10:51:59'),
(69, 17, 3500, 'Nov', '2024-11-13 10:52:10'),
(70, 22, 3500, 'June', '2024-06-05 10:53:18'),
(71, 22, 3500, 'July', '2024-07-01 10:53:38'),
(72, 22, 3500, 'Aug', '2024-08-01 10:53:54'),
(73, 22, 3500, 'Sept', '2024-09-03 10:54:16'),
(74, 22, 3500, 'Oct', '2024-10-01 10:54:39'),
(75, 22, 3500, 'Nov', '2024-11-13 10:54:59'),
(76, 23, 3500, 'June', '2024-06-01 10:56:14'),
(77, 23, 3500, 'July', '2024-07-01 10:56:29'),
(78, 23, 3500, 'Aug', '2024-08-01 10:56:43'),
(79, 23, 3500, 'Sept', '2024-09-04 10:57:16'),
(80, 23, 3500, 'Oct', '2024-09-05 10:58:28'),
(81, 23, 3500, 'Nov', '2024-10-01 10:58:43'),
(82, 15, 3500, 'June', '2024-11-13 10:59:44'),
(83, 15, 3500, 'July', '2024-11-13 10:59:57'),
(84, 15, 3500, 'Aug', '2024-08-01 11:00:09'),
(85, 15, 3500, 'Sept', '2024-09-01 11:00:28'),
(86, 15, 3500, 'Oct', '2024-10-01 11:00:46'),
(87, 15, 3500, 'Nov', '2024-11-13 11:01:04'),
(88, 20, 3500, 'June', '2024-06-02 11:03:48'),
(89, 20, 3500, 'July', '2024-07-01 11:04:36'),
(90, 20, 3500, 'Aug', '2024-08-01 11:05:42'),
(91, 20, 3500, 'Sept', '2024-09-03 11:06:08'),
(92, 20, 3500, 'Oct', '2024-10-02 11:06:35'),
(93, 20, 3500, 'u', '2024-11-13 11:06:50'),
(94, 18, 3500, 'June', '2024-06-03 11:07:58'),
(95, 18, 3500, 'July', '2024-07-01 11:08:16'),
(96, 18, 3500, 'Aug', '2024-11-13 11:08:34'),
(97, 18, 3500, 'Sept', '2024-09-01 11:08:50'),
(98, 18, 3500, 'Oct', '2024-10-02 11:09:07'),
(99, 18, 3500, 'Nov', '2024-11-13 11:09:22'),
(100, 16, 3500, 'June', '2024-06-04 11:11:09'),
(101, 16, 3500, 'July', '2024-07-01 11:11:26'),
(102, 16, 3500, 'Aug', '2024-08-01 11:11:41'),
(103, 16, 3500, 'Sept', '2024-09-01 11:12:01'),
(104, 16, 3500, 'Oct', '2024-10-01 11:12:21'),
(105, 16, 3500, 'Nov', '2024-11-13 11:12:38'),
(106, 3, 4000, 'June', '2024-06-04 11:14:03'),
(107, 3, 4000, 'July', '2024-07-01 11:14:26'),
(108, 3, 4000, 'Aug', '2024-08-01 11:15:07'),
(109, 3, 4000, 'Sept', '2024-09-01 11:15:29'),
(110, 3, 4000, 'Oct', '2024-10-01 11:15:49'),
(111, 3, 4000, 'Nov', '2024-11-13 11:16:38'),
(112, 10, 3500, 'June', '2024-06-02 11:43:39'),
(113, 10, 3500, 'July', '2024-07-01 11:44:05'),
(114, 10, 3500, 'Aug', '2024-08-05 11:44:48'),
(115, 10, 3500, 'Sept', '2024-09-03 11:46:48'),
(116, 10, 3500, 'Oct', '2024-10-01 11:47:07'),
(117, 10, 3500, 'Nov', '2024-11-13 11:47:19'),
(118, 9, 3500, 'June', '2024-06-01 11:49:01'),
(119, 9, 3500, 'July', '2024-07-01 11:49:23'),
(120, 9, 3500, 'Aug', '2024-08-01 11:49:43'),
(121, 9, 3500, 'Sept', '2024-09-02 11:51:20'),
(122, 9, 3500, 'Oct', '2024-10-01 11:51:36'),
(123, 9, 3500, 'Nov', '2024-11-13 11:51:51'),
(124, 7, 4000, 'June', '2024-06-04 11:53:01'),
(125, 7, 4000, 'July', '2024-07-01 11:53:18'),
(126, 7, 4000, 'Aug', '2024-08-01 11:53:34'),
(127, 7, 4000, 'Sept', '2024-09-01 11:53:49'),
(128, 7, 4000, 'Oct', '2024-10-01 11:54:03'),
(129, 7, 4000, 'Nov', '2024-11-13 11:54:25'),
(130, 6, 4000, 'June', '2024-06-02 11:55:50'),
(131, 6, 4000, 'July', '2024-07-01 11:56:06'),
(132, 6, 4000, 'Aug', '2024-08-01 11:57:23'),
(133, 6, 4000, 'Sept', '2024-09-02 11:57:57'),
(134, 6, 4000, 'June', '2024-06-01 11:58:53'),
(135, 4, 4000, 'June', '2024-06-01 11:59:30'),
(136, 4, 4000, 'July', '2024-07-03 11:59:53'),
(137, 4, 4000, 'Aug', '2024-11-13 12:00:11'),
(138, 4, 4000, 'Sept', '2024-09-01 12:00:26'),
(139, 4, 4000, 'Oct', '2024-10-01 12:00:43');

-- --------------------------------------------------------

--
-- Table structure for table `system_settings`
--

CREATE TABLE `system_settings` (
  `id` int(30) NOT NULL,
  `name` text NOT NULL,
  `email` varchar(200) NOT NULL,
  `contact` varchar(20) NOT NULL,
  `cover_img` text NOT NULL,
  `about_content` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `system_settings`
--

INSERT INTO `system_settings` (`id`, `name`, `email`, `contact`, `cover_img`, `about_content`) VALUES
(1, 'House Rental Management System', 'info@sample.comm', '+6948 8542 623', '1603344720_1602738120_pngtree-purple-hd-business-banner-image_5493.jpg', '&lt;p style=&quot;text-align: center; background: transparent; position: relative;&quot;&gt;&lt;span style=&quot;color: rgb(0, 0, 0); font-family: &amp;quot;Open Sans&amp;quot;, Arial, sans-serif; font-weight: 400; text-align: justify;&quot;&gt;&amp;nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry&rsquo;s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.&lt;/span&gt;&lt;br&gt;&lt;/p&gt;&lt;p style=&quot;text-align: center; background: transparent; position: relative;&quot;&gt;&lt;br&gt;&lt;/p&gt;&lt;p style=&quot;text-align: center; background: transparent; position: relative;&quot;&gt;&lt;br&gt;&lt;/p&gt;&lt;p&gt;&lt;/p&gt;');

-- --------------------------------------------------------

--
-- Table structure for table `tenants`
--

CREATE TABLE `tenants` (
  `id` int(30) NOT NULL,
  `firstname` varchar(100) NOT NULL,
  `middlename` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `contact` varchar(50) NOT NULL,
  `house_id` int(30) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1 = active, 0= inactive',
  `date_in` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tenants`
--

INSERT INTO `tenants` (`id`, `firstname`, `middlename`, `lastname`, `email`, `contact`, `house_id`, `status`, `date_in`) VALUES
(2, 'John', 'C', 'Smith', 'jsmith@sample.com', '+18456-5455-55', 1, 0, '2020-07-02'),
(3, 'Lance', 'P.', 'Perez', 'Lance Perez', '092333332554', 1, 1, '2024-05-01'),
(4, 'Princess', 'L', 'Reyes', 'Princess Reyes', '09255568766', 2, 1, '2024-05-01'),
(5, 'Jenny', 'G', 'Gonzales', 'Jenny Gonzales', '09876564533', 3, 1, '2024-05-01'),
(6, 'Kieffer', 'O', 'Velasco', 'Kieffer Velasco', '09871112356', 4, 1, '2024-05-01'),
(7, 'Rein', 'W', 'Mangulabnan', 'Rein Magulabnan', '09564382311', 5, 1, '2024-05-01'),
(8, 'Shirley', 'D', 'Go', 'shirley go', '09124543433', 6, 1, '2024-05-01'),
(9, 'Albert', 'S', 'Pascual', 'albert pascual', '09051237898', 7, 1, '2024-05-01'),
(10, 'Railey', 'K', 'Mana', 'railey mana', '09567893421', 8, 1, '2024-05-01'),
(11, 'Sarah', 'Q', 'Cruz', 'sarah cruz', '09875643466', 9, 1, '2024-05-01'),
(12, 'Girlie', 'S', 'Cruz', 'girlie cruz', '09021345555', 10, 1, '2024-05-01'),
(13, 'Maine', 'S', 'Fern', 'maine fern', '09065489766', 11, 1, '2024-05-01'),
(14, 'Kristel', 'A', 'Alice', 'kristel alice', '09086563244', 12, 1, '2024-05-01'),
(15, 'Joshua', 'O', 'Peters', 'joshua peters', '09654342311', 13, 1, '2024-05-01'),
(16, 'Ralph', 'R', 'Rimando', 'ralph rimando', '09892341233', 14, 1, '2024-05-01'),
(17, 'Tony', 'D', 'Japson', 'tony japson', '09785467823', 15, 1, '2024-05-01'),
(18, 'Kate', 'H', 'Oliveria', 'kate oliveria', '09873452345', 16, 1, '2024-05-01'),
(19, 'raf', 'm', 'lim', 'raf lim', '09875643433', 17, 0, '2024-11-13'),
(20, 'Ali', 'R', 'Pascual', 'ali pascual', '09457832344', 17, 1, '2024-05-01'),
(21, 'Uly', 'H', 'Guevarra', 'uly guevarra', '09065495077', 18, 1, '2024-05-01'),
(22, 'John', 'O', 'Magana', 'john magana', '09786766677', 19, 1, '2024-05-01'),
(23, 'Teresa', 'Z', 'Villaspir', 'teresa villaspir', '09065442233', 20, 1, '2024-05-01');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(30) NOT NULL,
  `name` text NOT NULL,
  `username` varchar(200) NOT NULL,
  `password` text NOT NULL,
  `type` tinyint(1) NOT NULL DEFAULT 2 COMMENT '1=Admin,2=Staff',
  `establishment_id` varchar(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `otp` varchar(11) DEFAULT NULL,
  `token_expiry` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `password`, `type`, `establishment_id`, `email`, `otp`, `token_expiry`) VALUES
(1, 'admin', 'admin', '0192023a7bbd73250516f069df18b500', 1, '0', 'princeraisenguevara@gmail.com', 'PrLDG', 2024),
(12, 'Prince', 'princeadmin', '0192023a7bbd73250516f069df18b500', 2, '0', '', '0', 0),
(15, 'Adminrafael', 'Adminrafael', '0192023a7bbd73250516f069df18b500', 1, '0', '', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `houses`
--
ALTER TABLE `houses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `system_settings`
--
ALTER TABLE `system_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tenants`
--
ALTER TABLE `tenants`
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
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `houses`
--
ALTER TABLE `houses`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=140;

--
-- AUTO_INCREMENT for table `system_settings`
--
ALTER TABLE `system_settings`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tenants`
--
ALTER TABLE `tenants`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
