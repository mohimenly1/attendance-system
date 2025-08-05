-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Aug 02, 2025 at 11:23 PM
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
-- Database: `attendance_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `attendances`
--

CREATE TABLE `attendances` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `schedule_id` bigint(20) UNSIGNED NOT NULL,
  `attendance_date` date NOT NULL,
  `attended_at` timestamp NULL DEFAULT NULL,
  `is_present` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `teacher_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`id`, `name`, `code`, `description`, `teacher_id`, `created_at`, `updated_at`) VALUES
(1, 'Laboriosam provident nesciunt.', 'CS32gr', 'Amet voluptatem eaque voluptas est. Architecto nihil qui provident animi maxime ipsam. Quis quam porro quo et dignissimos.', 6, '2025-08-01 03:17:43', '2025-08-01 03:17:43'),
(2, 'Cum numquam aperiam tempora.', 'CS65yp', 'Modi molestiae unde eos fugit error. Omnis eum rem veniam delectus sint. Molestiae explicabo nulla perferendis ab quam. Quibusdam qui consectetur ut tenetur.', 4, '2025-08-01 03:17:43', '2025-08-01 03:17:43'),
(3, 'Et rerum qui voluptatem.', 'CS05at', 'Rem aut temporibus pariatur blanditiis eveniet eius. Dolorum nihil est et quis dolore. Itaque et facere incidunt rerum ea tenetur. Rerum ullam qui quas ut.', 2, '2025-08-01 03:17:43', '2025-08-01 03:17:43'),
(4, 'Asperiores id ea quia.', 'CS93wm', 'Consequatur temporibus neque quis sapiente id. Reprehenderit est quam temporibus provident labore ea alias. Harum soluta voluptatem inventore aut quidem quo officia.', 6, '2025-08-01 03:17:43', '2025-08-01 03:17:43'),
(5, 'Non et omnis.', 'CS03hf', 'Tempore consequatur omnis at eveniet labore est aspernatur. Itaque ea quos ut. Necessitatibus est totam in earum fugit aut. Ad est commodi et nesciunt.', 4, '2025-08-01 03:17:43', '2025-08-01 03:17:43'),
(6, 'Et nemo aut qui.', 'CS60pu', 'Aut id officiis dolore explicabo. Pariatur velit ratione commodi pariatur.', 4, '2025-08-01 03:17:43', '2025-08-01 03:17:43'),
(7, 'Veniam architecto et placeat.', 'CS60ya', 'Commodi odit perspiciatis beatae sint nihil dolores. Aliquid reprehenderit sunt quibusdam qui non qui sapiente culpa. Error consequatur dignissimos ut dolorem eius odio error. Eligendi ut consectetur provident maxime possimus vitae. Aliquid reiciendis sint et praesentium vero enim.', 4, '2025-08-01 03:17:43', '2025-08-01 03:17:43'),
(8, 'Nesciunt aspernatur voluptatem.', 'CS52hn', 'Cumque possimus maxime est itaque et. Voluptatem magni nam ad et velit. Unde voluptatem cupiditate quia nulla adipisci et eos. Dolorem sequi iusto est consequatur animi et.', 3, '2025-08-01 03:17:43', '2025-08-01 03:17:43'),
(9, 'Omnis ut.', 'CS45hz', 'Nam ut autem repellendus debitis consectetur. Accusantium eligendi exercitationem dolores consequatur ea. Suscipit dolore aut reprehenderit.', 3, '2025-08-01 03:17:43', '2025-08-01 03:17:43'),
(10, 'Culpa omnis non.', 'CS46ln', 'Quia in voluptas voluptatum sit quo sit corrupti nesciunt. Est eius minus et inventore voluptates asperiores aspernatur. Dolor ea neque nam enim suscipit.', 6, '2025-08-01 03:17:43', '2025-08-01 03:17:43'),
(11, 'maaath', '322e', 'dwedewd', 5, '2025-08-02 16:58:46', '2025-08-02 16:58:46'),
(12, 'madaaaa', '332', 'adasdsadsa', 2, '2025-08-02 18:22:01', '2025-08-02 18:22:01');

-- --------------------------------------------------------

--
-- Table structure for table `course_student`
--

CREATE TABLE `course_student` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `course_id` bigint(20) UNSIGNED NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `course_student`
--

INSERT INTO `course_student` (`id`, `course_id`, `student_id`, `created_at`, `updated_at`) VALUES
(1, 1, 7, NULL, NULL),
(2, 3, 7, NULL, NULL),
(3, 10, 7, NULL, NULL),
(4, 3, 8, NULL, NULL),
(5, 6, 8, NULL, NULL),
(6, 9, 8, NULL, NULL),
(7, 2, 9, NULL, NULL),
(8, 5, 9, NULL, NULL),
(9, 7, 9, NULL, NULL),
(10, 1, 10, NULL, NULL),
(11, 2, 10, NULL, NULL),
(12, 3, 10, NULL, NULL),
(13, 4, 11, NULL, NULL),
(14, 5, 11, NULL, NULL),
(15, 7, 11, NULL, NULL),
(16, 4, 12, NULL, NULL),
(17, 9, 12, NULL, NULL),
(18, 10, 12, NULL, NULL),
(19, 2, 13, NULL, NULL),
(20, 7, 13, NULL, NULL),
(21, 8, 13, NULL, NULL),
(22, 3, 14, NULL, NULL),
(23, 9, 14, NULL, NULL),
(24, 10, 14, NULL, NULL),
(25, 4, 15, NULL, NULL),
(26, 8, 15, NULL, NULL),
(27, 10, 15, NULL, NULL),
(28, 1, 16, NULL, NULL),
(29, 7, 16, NULL, NULL),
(30, 8, 16, NULL, NULL),
(31, 4, 17, NULL, NULL),
(32, 5, 17, NULL, NULL),
(33, 8, 17, NULL, NULL),
(34, 2, 18, NULL, NULL),
(35, 3, 18, NULL, NULL),
(36, 8, 18, NULL, NULL),
(37, 1, 19, NULL, NULL),
(38, 4, 19, NULL, NULL),
(39, 6, 19, NULL, NULL),
(40, 1, 20, NULL, NULL),
(41, 6, 20, NULL, NULL),
(42, 8, 20, NULL, NULL),
(43, 1, 21, NULL, NULL),
(44, 5, 21, NULL, NULL),
(45, 8, 21, NULL, NULL),
(46, 2, 22, NULL, NULL),
(47, 8, 22, NULL, NULL),
(48, 10, 22, NULL, NULL),
(49, 2, 23, NULL, NULL),
(50, 4, 23, NULL, NULL),
(51, 8, 23, NULL, NULL),
(52, 6, 24, NULL, NULL),
(53, 9, 24, NULL, NULL),
(54, 10, 24, NULL, NULL),
(55, 3, 25, NULL, NULL),
(56, 7, 25, NULL, NULL),
(57, 9, 25, NULL, NULL),
(58, 2, 26, NULL, NULL),
(59, 3, 26, NULL, NULL),
(60, 6, 26, NULL, NULL),
(61, 4, 27, NULL, NULL),
(62, 6, 27, NULL, NULL),
(63, 7, 27, NULL, NULL),
(64, 1, 28, NULL, NULL),
(65, 4, 28, NULL, NULL),
(66, 6, 28, NULL, NULL),
(67, 3, 29, NULL, NULL),
(68, 8, 29, NULL, NULL),
(69, 9, 29, NULL, NULL),
(70, 5, 30, NULL, NULL),
(71, 6, 30, NULL, NULL),
(72, 9, 30, NULL, NULL),
(73, 1, 31, NULL, NULL),
(74, 5, 31, NULL, NULL),
(75, 10, 31, NULL, NULL),
(76, 2, 32, NULL, NULL),
(77, 5, 32, NULL, NULL),
(78, 10, 32, NULL, NULL),
(79, 4, 33, NULL, NULL),
(80, 5, 33, NULL, NULL),
(81, 10, 33, NULL, NULL),
(82, 3, 34, NULL, NULL),
(83, 5, 34, NULL, NULL),
(84, 7, 34, NULL, NULL),
(85, 1, 35, NULL, NULL),
(86, 2, 35, NULL, NULL),
(87, 6, 35, NULL, NULL),
(88, 1, 36, NULL, NULL),
(89, 4, 36, NULL, NULL),
(90, 8, 36, NULL, NULL),
(91, 5, 37, NULL, NULL),
(92, 7, 37, NULL, NULL),
(93, 8, 37, NULL, NULL),
(94, 7, 38, NULL, NULL),
(95, 8, 38, NULL, NULL),
(96, 10, 38, NULL, NULL),
(97, 2, 39, NULL, NULL),
(98, 3, 39, NULL, NULL),
(99, 6, 39, NULL, NULL),
(100, 4, 40, NULL, NULL),
(101, 8, 40, NULL, NULL),
(102, 9, 40, NULL, NULL),
(103, 1, 41, NULL, NULL),
(104, 8, 41, NULL, NULL),
(105, 9, 41, NULL, NULL),
(106, 5, 42, NULL, NULL),
(107, 6, 42, NULL, NULL),
(108, 9, 42, NULL, NULL),
(109, 4, 43, NULL, NULL),
(110, 5, 43, NULL, NULL),
(111, 7, 43, NULL, NULL),
(112, 4, 44, NULL, NULL),
(113, 8, 44, NULL, NULL),
(114, 10, 44, NULL, NULL),
(115, 5, 45, NULL, NULL),
(116, 6, 45, NULL, NULL),
(117, 10, 45, NULL, NULL),
(118, 2, 46, NULL, NULL),
(119, 7, 46, NULL, NULL),
(120, 8, 46, NULL, NULL),
(121, 1, 47, NULL, NULL),
(122, 6, 47, NULL, NULL),
(123, 9, 47, NULL, NULL),
(124, 3, 48, NULL, NULL),
(125, 4, 48, NULL, NULL),
(126, 9, 48, NULL, NULL),
(127, 2, 49, NULL, NULL),
(128, 5, 49, NULL, NULL),
(129, 9, 49, NULL, NULL),
(130, 1, 50, NULL, NULL),
(131, 5, 50, NULL, NULL),
(132, 10, 50, NULL, NULL),
(133, 4, 51, NULL, NULL),
(134, 6, 51, NULL, NULL),
(135, 9, 51, NULL, NULL),
(136, 1, 52, NULL, NULL),
(137, 2, 52, NULL, NULL),
(138, 6, 52, NULL, NULL),
(139, 4, 53, NULL, NULL),
(140, 7, 53, NULL, NULL),
(141, 9, 53, NULL, NULL),
(142, 1, 54, NULL, NULL),
(143, 9, 54, NULL, NULL),
(144, 10, 54, NULL, NULL),
(145, 1, 55, NULL, NULL),
(146, 5, 55, NULL, NULL),
(147, 6, 55, NULL, NULL),
(148, 3, 56, NULL, NULL),
(149, 6, 56, NULL, NULL),
(150, 7, 56, NULL, NULL),
(151, 3, 23, NULL, NULL),
(152, 12, 52, NULL, NULL),
(153, 12, 57, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_07_29_234831_create_courses_table', 1),
(5, '2025_07_30_000944_create_course_student_table', 1),
(6, '2025_07_30_000944_create_schedules_table', 1),
(7, '2025_07_30_025136_create_attendances_table', 1),
(8, '2025_07_30_030011_create_student_photos_table', 1),
(9, '2025_08_01_055544_sessions', 2);

-- --------------------------------------------------------

--
-- Table structure for table `schedules`
--

CREATE TABLE `schedules` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `course_id` bigint(20) UNSIGNED NOT NULL,
  `day_of_week` enum('Sunday','Monday','Tuesday','Wednesday','Thursday') NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `schedules`
--

INSERT INTO `schedules` (`id`, `course_id`, `day_of_week`, `start_time`, `end_time`, `created_at`, `updated_at`) VALUES
(1, 3, 'Sunday', '08:00:00', '09:52:00', '2025-08-02 17:50:51', '2025-08-02 17:50:51');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('yVF3Ey7k5X9nj9wQ2j51xE0ps98Ud5WFVMngcS3g', NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiNVpHRFVTcTI0RVZSa2MzanF1djNPdlRhbjBpUlZKNk9BdUdZQjZROSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1754167775);

-- --------------------------------------------------------

--
-- Table structure for table `student_photos`
--

CREATE TABLE `student_photos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `photo_path` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','teacher','student') NOT NULL DEFAULT 'student',
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `role`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin User', 'admin@example.com', NULL, '$2y$12$JAAsjdGoR1BugyIWeVMfJ.CosR7w3vB3.ZWCBx6X4YGrB6IYCYLO2', 'admin', NULL, '2025-08-01 03:17:43', '2025-08-01 03:17:43'),
(2, 'Ms. Meagan Stanton', 'vilma.boyle@example.net', '2025-08-01 03:17:43', '$2y$12$JAAsjdGoR1BugyIWeVMfJ.CosR7w3vB3.ZWCBx6X4YGrB6IYCYLO2', 'teacher', 'VweYaI6mIz6GL8QScRjb4nMYB0bswXUUWahvT8XgOhTh4aFge17IBvLmIqM9', '2025-08-01 03:17:43', '2025-08-01 03:17:43'),
(3, 'Abigayle Braun', 'stuart42@example.com', '2025-08-01 03:17:43', '$2y$12$5NpA9A680BtP5pl/lFZsHuL/5Iww8wFGAjWRmmSsnPuloT0Fz4dy2', 'teacher', 'klkBLOnL13', '2025-08-01 03:17:43', '2025-08-01 03:17:43'),
(4, 'Sandra Kemmer', 'ukoelpin@example.org', '2025-08-01 03:17:43', '$2y$12$5NpA9A680BtP5pl/lFZsHuL/5Iww8wFGAjWRmmSsnPuloT0Fz4dy2', 'teacher', 'M8qxmjXeAu', '2025-08-01 03:17:43', '2025-08-01 03:17:43'),
(5, 'Bennie Kerluke', 'nathaniel50@example.net', '2025-08-01 03:17:43', '$2y$12$5NpA9A680BtP5pl/lFZsHuL/5Iww8wFGAjWRmmSsnPuloT0Fz4dy2', 'teacher', '3hHqUu3t6y', '2025-08-01 03:17:43', '2025-08-01 03:17:43'),
(6, 'Ms. Marian McDermott MD', 'konopelski.florine@example.com', '2025-08-01 03:17:43', '$2y$12$5NpA9A680BtP5pl/lFZsHuL/5Iww8wFGAjWRmmSsnPuloT0Fz4dy2', 'teacher', 'MaN4UFwQ6U', '2025-08-01 03:17:43', '2025-08-01 03:17:43'),
(7, 'Dr. Norwood Harber I', 'bauch.marilou@example.org', '2025-08-01 03:17:43', '$2y$12$5NpA9A680BtP5pl/lFZsHuL/5Iww8wFGAjWRmmSsnPuloT0Fz4dy2', 'student', '2nbZmo2pFI', '2025-08-01 03:17:43', '2025-08-01 03:17:43'),
(8, 'Emilie Ullrich', 'jessica58@example.com', '2025-08-01 03:17:43', '$2y$12$5NpA9A680BtP5pl/lFZsHuL/5Iww8wFGAjWRmmSsnPuloT0Fz4dy2', 'student', 'pxyIO9iuIy', '2025-08-01 03:17:43', '2025-08-01 03:17:43'),
(9, 'Thurman Berge', 'frida79@example.org', '2025-08-01 03:17:43', '$2y$12$5NpA9A680BtP5pl/lFZsHuL/5Iww8wFGAjWRmmSsnPuloT0Fz4dy2', 'student', '6yEhwtWzjC', '2025-08-01 03:17:43', '2025-08-01 03:17:43'),
(10, 'Vella McClure', 'nader.myrna@example.org', '2025-08-01 03:17:43', '$2y$12$5NpA9A680BtP5pl/lFZsHuL/5Iww8wFGAjWRmmSsnPuloT0Fz4dy2', 'student', '8RHWyrZOd9', '2025-08-01 03:17:43', '2025-08-01 03:17:43'),
(11, 'Jaron Von', 'fjast@example.net', '2025-08-01 03:17:43', '$2y$12$5NpA9A680BtP5pl/lFZsHuL/5Iww8wFGAjWRmmSsnPuloT0Fz4dy2', 'student', 'BXzGvi4m0X', '2025-08-01 03:17:43', '2025-08-01 03:17:43'),
(12, 'Savion Hills', 'kerluke.josh@example.org', '2025-08-01 03:17:43', '$2y$12$5NpA9A680BtP5pl/lFZsHuL/5Iww8wFGAjWRmmSsnPuloT0Fz4dy2', 'student', 'pCWMh04T69', '2025-08-01 03:17:43', '2025-08-01 03:17:43'),
(13, 'Ms. Mazie Stark', 'efren.mosciski@example.net', '2025-08-01 03:17:43', '$2y$12$5NpA9A680BtP5pl/lFZsHuL/5Iww8wFGAjWRmmSsnPuloT0Fz4dy2', 'student', 'HQBRb50Dob', '2025-08-01 03:17:43', '2025-08-01 03:17:43'),
(14, 'Mekhi West', 'misael.smitham@example.net', '2025-08-01 03:17:43', '$2y$12$5NpA9A680BtP5pl/lFZsHuL/5Iww8wFGAjWRmmSsnPuloT0Fz4dy2', 'student', 'PCrjAZJgl8', '2025-08-01 03:17:43', '2025-08-01 03:17:43'),
(15, 'Prof. Wendy Lemke', 'herminio17@example.org', '2025-08-01 03:17:43', '$2y$12$5NpA9A680BtP5pl/lFZsHuL/5Iww8wFGAjWRmmSsnPuloT0Fz4dy2', 'student', 'MNK1MAJba6', '2025-08-01 03:17:43', '2025-08-01 03:17:43'),
(16, 'Aracely Goldner', 'vicente.homenick@example.com', '2025-08-01 03:17:43', '$2y$12$5NpA9A680BtP5pl/lFZsHuL/5Iww8wFGAjWRmmSsnPuloT0Fz4dy2', 'student', 'UeYLay9e7A', '2025-08-01 03:17:43', '2025-08-01 03:17:43'),
(17, 'Cheyenne Schmitt', 'brown.pagac@example.com', '2025-08-01 03:17:43', '$2y$12$5NpA9A680BtP5pl/lFZsHuL/5Iww8wFGAjWRmmSsnPuloT0Fz4dy2', 'student', 'nNWuA0W2cs', '2025-08-01 03:17:43', '2025-08-01 03:17:43'),
(18, 'Gerry Hermiston', 'stroman.payton@example.net', '2025-08-01 03:17:43', '$2y$12$5NpA9A680BtP5pl/lFZsHuL/5Iww8wFGAjWRmmSsnPuloT0Fz4dy2', 'student', 'HjNoHKpYZV', '2025-08-01 03:17:43', '2025-08-01 03:17:43'),
(19, 'Garnet Dooley', 'frederik.kautzer@example.com', '2025-08-01 03:17:43', '$2y$12$5NpA9A680BtP5pl/lFZsHuL/5Iww8wFGAjWRmmSsnPuloT0Fz4dy2', 'student', 'K0bGiXLmLZ', '2025-08-01 03:17:43', '2025-08-01 03:17:43'),
(20, 'Myrtie Wintheiser', 'domenica05@example.com', '2025-08-01 03:17:43', '$2y$12$5NpA9A680BtP5pl/lFZsHuL/5Iww8wFGAjWRmmSsnPuloT0Fz4dy2', 'student', '001BCpySnL', '2025-08-01 03:17:43', '2025-08-01 03:17:43'),
(21, 'Mrs. Susana O\'Connell', 'kenton54@example.net', '2025-08-01 03:17:43', '$2y$12$5NpA9A680BtP5pl/lFZsHuL/5Iww8wFGAjWRmmSsnPuloT0Fz4dy2', 'student', 'YIqE3l3Ur3', '2025-08-01 03:17:43', '2025-08-01 03:17:43'),
(22, 'Ms. Amelie Ernser', 'edgar.vandervort@example.org', '2025-08-01 03:17:43', '$2y$12$5NpA9A680BtP5pl/lFZsHuL/5Iww8wFGAjWRmmSsnPuloT0Fz4dy2', 'student', '2E02LwpStc', '2025-08-01 03:17:43', '2025-08-01 03:17:43'),
(23, 'Enos Hermiston', 'irma.koelpin@example.com', '2025-08-01 03:17:43', '$2y$12$5NpA9A680BtP5pl/lFZsHuL/5Iww8wFGAjWRmmSsnPuloT0Fz4dy2', 'student', 'xy8EGU5T93', '2025-08-01 03:17:43', '2025-08-01 03:17:43'),
(24, 'Stuart Stehr', 'michaela88@example.net', '2025-08-01 03:17:43', '$2y$12$5NpA9A680BtP5pl/lFZsHuL/5Iww8wFGAjWRmmSsnPuloT0Fz4dy2', 'student', 'zWIliiyATI', '2025-08-01 03:17:43', '2025-08-01 03:17:43'),
(25, 'Maxime Haley', 'wgrady@example.net', '2025-08-01 03:17:43', '$2y$12$5NpA9A680BtP5pl/lFZsHuL/5Iww8wFGAjWRmmSsnPuloT0Fz4dy2', 'student', 'TGOoWOcEyC', '2025-08-01 03:17:43', '2025-08-01 03:17:43'),
(26, 'Marlee Weber', 'ruecker.harmony@example.net', '2025-08-01 03:17:43', '$2y$12$5NpA9A680BtP5pl/lFZsHuL/5Iww8wFGAjWRmmSsnPuloT0Fz4dy2', 'student', '31K3HbNfRy', '2025-08-01 03:17:43', '2025-08-01 03:17:43'),
(27, 'Petra Rosenbaum', 'pokeefe@example.org', '2025-08-01 03:17:43', '$2y$12$5NpA9A680BtP5pl/lFZsHuL/5Iww8wFGAjWRmmSsnPuloT0Fz4dy2', 'student', 'SGSSETqJ79', '2025-08-01 03:17:43', '2025-08-01 03:17:43'),
(28, 'Melody Muller', 'noble58@example.org', '2025-08-01 03:17:43', '$2y$12$5NpA9A680BtP5pl/lFZsHuL/5Iww8wFGAjWRmmSsnPuloT0Fz4dy2', 'student', 'bGb3eqh1Xn', '2025-08-01 03:17:43', '2025-08-01 03:17:43'),
(29, 'Ezekiel Senger', 'ohudson@example.net', '2025-08-01 03:17:43', '$2y$12$5NpA9A680BtP5pl/lFZsHuL/5Iww8wFGAjWRmmSsnPuloT0Fz4dy2', 'student', '9vQlwjjtsO', '2025-08-01 03:17:43', '2025-08-01 03:17:43'),
(30, 'Cullen Herman DDS', 'camryn83@example.net', '2025-08-01 03:17:43', '$2y$12$5NpA9A680BtP5pl/lFZsHuL/5Iww8wFGAjWRmmSsnPuloT0Fz4dy2', 'student', 'ZFmnw1Vu9M', '2025-08-01 03:17:43', '2025-08-01 03:17:43'),
(31, 'Golda Cummings', 'gleason.nikolas@example.com', '2025-08-01 03:17:43', '$2y$12$5NpA9A680BtP5pl/lFZsHuL/5Iww8wFGAjWRmmSsnPuloT0Fz4dy2', 'student', 'mXpVZInhUd', '2025-08-01 03:17:43', '2025-08-01 03:17:43'),
(32, 'Prof. Ezequiel Schmitt', 'miles45@example.org', '2025-08-01 03:17:43', '$2y$12$5NpA9A680BtP5pl/lFZsHuL/5Iww8wFGAjWRmmSsnPuloT0Fz4dy2', 'student', 'b082SjtNcU', '2025-08-01 03:17:43', '2025-08-01 03:17:43'),
(33, 'Grover Bode', 'aturcotte@example.com', '2025-08-01 03:17:43', '$2y$12$5NpA9A680BtP5pl/lFZsHuL/5Iww8wFGAjWRmmSsnPuloT0Fz4dy2', 'student', 'vQE6ig79To', '2025-08-01 03:17:43', '2025-08-01 03:17:43'),
(34, 'Lilly Johns', 'usanford@example.net', '2025-08-01 03:17:43', '$2y$12$5NpA9A680BtP5pl/lFZsHuL/5Iww8wFGAjWRmmSsnPuloT0Fz4dy2', 'student', 'ptbb3zTEBr', '2025-08-01 03:17:43', '2025-08-01 03:17:43'),
(35, 'Genesis McKenzie MD', 'okuneva.daisha@example.org', '2025-08-01 03:17:43', '$2y$12$5NpA9A680BtP5pl/lFZsHuL/5Iww8wFGAjWRmmSsnPuloT0Fz4dy2', 'student', 'Q1CtkwOFSD', '2025-08-01 03:17:43', '2025-08-01 03:17:43'),
(36, 'Mr. Royal Nolan', 'kayla78@example.net', '2025-08-01 03:17:43', '$2y$12$5NpA9A680BtP5pl/lFZsHuL/5Iww8wFGAjWRmmSsnPuloT0Fz4dy2', 'student', 'ITyuapxp4Q', '2025-08-01 03:17:43', '2025-08-01 03:17:43'),
(37, 'Bo Hermann Sr.', 'bogan.heidi@example.com', '2025-08-01 03:17:43', '$2y$12$5NpA9A680BtP5pl/lFZsHuL/5Iww8wFGAjWRmmSsnPuloT0Fz4dy2', 'student', '6Pbem3goui', '2025-08-01 03:17:43', '2025-08-01 03:17:43'),
(38, 'Alford Kozey', 'koss.kallie@example.org', '2025-08-01 03:17:43', '$2y$12$5NpA9A680BtP5pl/lFZsHuL/5Iww8wFGAjWRmmSsnPuloT0Fz4dy2', 'student', 'OuOBpQVBAz', '2025-08-01 03:17:43', '2025-08-01 03:17:43'),
(39, 'Lauryn Prohaska', 'bernhard.eden@example.com', '2025-08-01 03:17:43', '$2y$12$5NpA9A680BtP5pl/lFZsHuL/5Iww8wFGAjWRmmSsnPuloT0Fz4dy2', 'student', 'k67u9ZMxqa', '2025-08-01 03:17:43', '2025-08-01 03:17:43'),
(40, 'Dr. Myriam Botsford II', 'kian37@example.org', '2025-08-01 03:17:43', '$2y$12$5NpA9A680BtP5pl/lFZsHuL/5Iww8wFGAjWRmmSsnPuloT0Fz4dy2', 'student', 'lNjjP5oTCR', '2025-08-01 03:17:43', '2025-08-01 03:17:43'),
(41, 'Leanna Will', 'brakus.jason@example.org', '2025-08-01 03:17:43', '$2y$12$5NpA9A680BtP5pl/lFZsHuL/5Iww8wFGAjWRmmSsnPuloT0Fz4dy2', 'student', 'xFt8BM6tHO', '2025-08-01 03:17:43', '2025-08-01 03:17:43'),
(42, 'Mr. Toy Wilderman', 'cordie.davis@example.net', '2025-08-01 03:17:43', '$2y$12$5NpA9A680BtP5pl/lFZsHuL/5Iww8wFGAjWRmmSsnPuloT0Fz4dy2', 'student', 'j8SuM17y1G', '2025-08-01 03:17:43', '2025-08-01 03:17:43'),
(43, 'Mrs. Cathy Olson', 'doreilly@example.net', '2025-08-01 03:17:43', '$2y$12$5NpA9A680BtP5pl/lFZsHuL/5Iww8wFGAjWRmmSsnPuloT0Fz4dy2', 'student', 'OV0SAcwsnY', '2025-08-01 03:17:43', '2025-08-01 03:17:43'),
(44, 'Dr. Creola Mueller', 'kreinger@example.com', '2025-08-01 03:17:43', '$2y$12$5NpA9A680BtP5pl/lFZsHuL/5Iww8wFGAjWRmmSsnPuloT0Fz4dy2', 'student', '5ZWO13ai99', '2025-08-01 03:17:43', '2025-08-01 03:17:43'),
(45, 'Prof. Maxime Koch', 'cummings.mac@example.org', '2025-08-01 03:17:43', '$2y$12$5NpA9A680BtP5pl/lFZsHuL/5Iww8wFGAjWRmmSsnPuloT0Fz4dy2', 'student', 'SyLJrCRLkt', '2025-08-01 03:17:43', '2025-08-01 03:17:43'),
(46, 'Piper Bashirian', 'doyle.luella@example.net', '2025-08-01 03:17:43', '$2y$12$5NpA9A680BtP5pl/lFZsHuL/5Iww8wFGAjWRmmSsnPuloT0Fz4dy2', 'student', 'FyoFduh7tz', '2025-08-01 03:17:43', '2025-08-01 03:17:43'),
(47, 'Christa Larson', 'shields.jesse@example.org', '2025-08-01 03:17:43', '$2y$12$5NpA9A680BtP5pl/lFZsHuL/5Iww8wFGAjWRmmSsnPuloT0Fz4dy2', 'student', 'k33V1R3ZM0', '2025-08-01 03:17:43', '2025-08-01 03:17:43'),
(48, 'Dorris Stanton', 'hermiston.lela@example.net', '2025-08-01 03:17:43', '$2y$12$5NpA9A680BtP5pl/lFZsHuL/5Iww8wFGAjWRmmSsnPuloT0Fz4dy2', 'student', 'JOdkZGlb68', '2025-08-01 03:17:43', '2025-08-01 03:17:43'),
(49, 'Prof. Karl Kertzmann', 'bosco.graciela@example.com', '2025-08-01 03:17:43', '$2y$12$5NpA9A680BtP5pl/lFZsHuL/5Iww8wFGAjWRmmSsnPuloT0Fz4dy2', 'student', 'R5Pn2IZ3Ms', '2025-08-01 03:17:43', '2025-08-01 03:17:43'),
(50, 'Elliot Grimes', 'jimmy49@example.org', '2025-08-01 03:17:43', '$2y$12$5NpA9A680BtP5pl/lFZsHuL/5Iww8wFGAjWRmmSsnPuloT0Fz4dy2', 'student', 'xmGCOtC0vy', '2025-08-01 03:17:43', '2025-08-01 03:17:43'),
(51, 'Dr. Karelle Kulas', 'ariel01@example.com', '2025-08-01 03:17:43', '$2y$12$5NpA9A680BtP5pl/lFZsHuL/5Iww8wFGAjWRmmSsnPuloT0Fz4dy2', 'student', 'u9D4J9jhcK', '2025-08-01 03:17:43', '2025-08-01 03:17:43'),
(52, 'Angelina Denesik Sr.', 'julius.purdy@example.com', '2025-08-01 03:17:43', '$2y$12$5NpA9A680BtP5pl/lFZsHuL/5Iww8wFGAjWRmmSsnPuloT0Fz4dy2', 'student', 'x4YDAlg9Sw', '2025-08-01 03:17:43', '2025-08-01 03:17:43'),
(53, 'Mrs. Nikki Swift', 'gulgowski.idell@example.org', '2025-08-01 03:17:43', '$2y$12$5NpA9A680BtP5pl/lFZsHuL/5Iww8wFGAjWRmmSsnPuloT0Fz4dy2', 'student', 'rwXKyXtjjW', '2025-08-01 03:17:43', '2025-08-01 03:17:43'),
(54, 'Bart Weissnat', 'keeling.uriah@example.com', '2025-08-01 03:17:43', '$2y$12$5NpA9A680BtP5pl/lFZsHuL/5Iww8wFGAjWRmmSsnPuloT0Fz4dy2', 'student', 'XA7K34hYiw', '2025-08-01 03:17:43', '2025-08-01 03:17:43'),
(55, 'Devin Deckow', 'ken19@example.net', '2025-08-01 03:17:43', '$2y$12$5NpA9A680BtP5pl/lFZsHuL/5Iww8wFGAjWRmmSsnPuloT0Fz4dy2', 'student', '3TiP5fs9Q0', '2025-08-01 03:17:43', '2025-08-01 03:17:43'),
(56, 'Marcos Nader', 'lucinda02@example.org', '2025-08-01 03:17:43', '$2y$12$5NpA9A680BtP5pl/lFZsHuL/5Iww8wFGAjWRmmSsnPuloT0Fz4dy2', 'student', 'dXy8IDKRXM', '2025-08-01 03:17:43', '2025-08-01 03:17:43'),
(57, 'مهيمن طالب', 'test@test.com', NULL, '$2y$12$K30RGr4mJKbXqJPNdkgBDeukruh1DAOD99PJ.6fp6lpFT7yUyedFq', 'student', NULL, '2025-08-02 18:42:51', '2025-08-02 18:42:51');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attendances`
--
ALTER TABLE `attendances`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `attendances_student_id_schedule_id_attendance_date_unique` (`student_id`,`schedule_id`,`attendance_date`),
  ADD KEY `attendances_schedule_id_foreign` (`schedule_id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `courses_code_unique` (`code`),
  ADD KEY `courses_teacher_id_foreign` (`teacher_id`);

--
-- Indexes for table `course_student`
--
ALTER TABLE `course_student`
  ADD PRIMARY KEY (`id`),
  ADD KEY `course_student_course_id_foreign` (`course_id`),
  ADD KEY `course_student_student_id_foreign` (`student_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `schedules`
--
ALTER TABLE `schedules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `schedules_course_id_foreign` (`course_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `student_photos`
--
ALTER TABLE `student_photos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `student_photos_student_id_unique` (`student_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `attendances`
--
ALTER TABLE `attendances`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `course_student`
--
ALTER TABLE `course_student`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=154;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `schedules`
--
ALTER TABLE `schedules`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `student_photos`
--
ALTER TABLE `student_photos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `attendances`
--
ALTER TABLE `attendances`
  ADD CONSTRAINT `attendances_schedule_id_foreign` FOREIGN KEY (`schedule_id`) REFERENCES `schedules` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `attendances_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `courses`
--
ALTER TABLE `courses`
  ADD CONSTRAINT `courses_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `course_student`
--
ALTER TABLE `course_student`
  ADD CONSTRAINT `course_student_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `course_student_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `schedules`
--
ALTER TABLE `schedules`
  ADD CONSTRAINT `schedules_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `student_photos`
--
ALTER TABLE `student_photos`
  ADD CONSTRAINT `student_photos_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
