-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Apr 16, 2026 at 08:49 AM
-- Server version: 8.4.7
-- PHP Version: 8.2.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `lms`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
CREATE TABLE IF NOT EXISTS `cache` (
  `key` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
CREATE TABLE IF NOT EXISTS `jobs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `queue` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
CREATE TABLE IF NOT EXISTS `job_batches` (
  `id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=77 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_03_28_115317_create_personal_access_tokens_table', 1),
(5, '2026_03_30_054122_add_handshake_fields_to_users_table', 1),
(6, '2026_03_30_054502_create_tbl_register', 1),
(7, '2026_03_31_075535_create_tbl_employee_table', 1),
(8, '2026_03_31_075651_create_roles_table', 1),
(9, '2026_03_31_080727_add_role_to_users_table', 1),
(10, '2026_04_02_070919_add_phone_and_image_to_users_table', 1),
(11, '2026_04_02_113345_create_tbl_session_table', 1),
(12, '2026_04_02_114921_create_tbl_programme_table', 1),
(13, '2026_04_02_121959_add_created_by_updated_by_to_tbl_programme_table', 1),
(14, '2026_04_02_124208_create_tbl_technology_table', 1),
(15, '2026_04_02_130249_create_tbl_batch_table', 1),
(16, '2026_04_02_132510_create_tbl_subject_table', 1),
(17, '2026_04_03_053245_create_tbl_centers_table', 1),
(18, '2026_04_03_072559_create_tbl_branches_table', 1),
(19, '2026_04_03_114920_create_tbl_courses_table', 2),
(20, '2026_04_03_125152_add_approval_status_to_tbl_register_table', 3),
(21, '2026_04_03_130528_create_tbl_student_table', 4),
(22, '2026_04_04_052821_create_tbl_student_session_table', 5),
(23, '2026_04_04_052929_create_tbl_student_programme_table', 6),
(24, '2026_04_04_053002_create_tbl_student_technology_table', 7),
(25, '2026_04_04_053116_create_tbl_student_batch_table', 8),
(26, '2026_04_04_074253_add_center_id_to_tbl_student_table', 9),
(27, '2026_04_04_122107_add_is_block_to_users_table', 10),
(28, '2026_04_06_093245_alter_tbl_student_batch_add_shift_fields', 11),
(29, '2026_04_06_102046_create_tbl_employee', 12),
(30, '2026_04_06_000001_create_tbl_employee_table', 13),
(31, '2026_04_06_000002_create_tbl_employee_session_table', 13),
(32, '2026_04_06_000003_create_tbl_employee_programme_table', 13),
(33, '2026_04_06_000004_create_tbl_employee_technology_table', 13),
(34, '2026_04_06_000005_create_tbl_employee_batch_table', 13),
(35, '2026_04_06_112436_create_tbl_employee_table', 14),
(36, '2026_04_06_112604_create_tbl_employee_session_table', 14),
(37, '2026_04_06_112711_create_tbl_employee_programme_table', 15),
(38, '2026_04_06_112754_create_tbl_employee_technology_table', 15),
(39, '2026_04_06_112821_create_tbl_employee_batch_table', 15),
(40, '2026_04_06_113956_add_status_to_tbl_employee_table', 16),
(41, '2026_04_07_060239_create_tbl_programme_centers_table', 17),
(42, '2026_04_07_071638_create_tbl_session_programmes_table', 17),
(43, '2026_04_07_091752_add_soft_delete_columns_to_tbl_programme_table', 18),
(44, '2026_04_08_054450_create_tbl_attendance_table', 19),
(45, '2026_04_08_070004_add_session_center_programme_to_tbl_attendance_table', 20),
(46, '2026_04_08_132538_create_tbl_qr_attendance_device_logs_table', 21),
(47, '2026_04_09_045711_create_tbl_topic_table', 22),
(48, '2026_04_09_050313_create_tbl_upload_content_table', 23),
(49, '2026_04_09_071118_create_tbl_assignments_table', 24),
(50, '2026_04_09_071827_drop_assignment_columns_from_tbl_upload_content_table', 25),
(51, '2026_04_09_074306_create_tbl_recordings_table', 26),
(52, '2026_04_09_075300_drop_video_recording_from_tbl_upload_content_table', 27),
(53, '2026_04_09_090102_create_tbl_mock_tests_table', 28),
(54, '2026_04_09_091438_create_tbl_online_tests_table', 29),
(55, '2026_04_09_091607_create_tbl_online_test_questions_table', 30),
(56, '2026_04_09_105905_add_unique_topic_title_to_tbl_upload_content_table', 31),
(57, '2026_04_10_110512_create_tbl_subject_mock_tests_table', 32),
(58, '2026_04_10_133356_create_notification_heads_table', 33),
(59, '2026_04_11_051219_create_tbl_notification_table', 34),
(60, '2026_04_11_052355_create_tbl_notification_heads_table', 35),
(61, '2026_04_11_052424_create_tbl_notification_table', 36),
(62, '2026_04_11_090534_create_tbl_popup_notification_table', 37),
(63, '2026_04_11_101022_add_popup_schedule_columns_to_tbl_popup_notification_table', 38),
(64, '2026_04_11_112323_create_tbl_feedbacks_table', 39),
(65, '2026_04_11_121738_create_tbl_feedbacks_table', 40),
(66, '2026_04_11_122728_create_tbl_feedback_responses_table', 40),
(67, '2026_04_11_133905_create_tbl_feedback_forms_table', 41),
(68, '2026_04_11_133944_create_tbl_feedback_responses_table', 41),
(69, '2026_04_13_063156_add_advanced_fields_to_tbl_feedback_forms', 42),
(70, '2026_04_13_063324_add_advanced_fields_to_tbl_feedback_responses', 42),
(71, '2026_04_14_052721_create_user_login_logs_table', 43),
(72, '2026_04_14_053954_create_student_login_logs_table', 44),
(73, '2026_04_14_120000_add_user_and_student_to_feedback_responses_table', 45),
(74, '2026_04_14_122429_create_tbl_feedback_responses_table', 46),
(75, '2026_04_14_130024_create_tbl_feedback_responses_table', 47),
(76, '2026_04_15_092758_create_tbl_feedback_responses_table', 48);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
CREATE TABLE IF NOT EXISTS `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  KEY `personal_access_tokens_expires_at_index` (`expires_at`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
CREATE TABLE IF NOT EXISTS `roles` (
  `role_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `role_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`role_id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`role_id`, `role_name`, `created_at`, `updated_at`) VALUES
(4, 'student', '2026-04-03 07:23:51', '2026-04-03 07:23:51'),
(2, 'admin', '2026-04-03 07:23:26', '2026-04-14 05:41:47'),
(3, 'employee', '2026-04-03 07:23:31', '2026-04-03 07:23:31');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('vB7wMXRsjMXGqXCMKExNPToynOb0CXN4f6uqPUTE', 71, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTo5OntzOjY6Il90b2tlbiI7czo0MDoiVHNyckpHU1BOdFNZVlRRTHJobkJlN2NzMkZ3NFpDMjJIc3FWZU9uNyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzg6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9zdHVkZW50L2ZlZWRiYWNrIjtzOjU6InJvdXRlIjtzOjIyOiJzdHVkZW50LmZlZWRiYWNrLmluZGV4Ijt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6NzE7czoxNzoibG9nZ2VkX2luX3VzZXJfaWQiO2k6NTE7czoxOToibG9nZ2VkX2luX3VzZXJfbmFtZSI7czoxMzoiU2Frc2hpIFBhbmRleSI7czoyMDoibG9nZ2VkX2luX3VzZXJfZW1haWwiO3M6MjE6InNwYW5kZXk1NDQ5QGdtYWlsLmNvbSI7czoxOToibG9nZ2VkX2luX3VzZXJfcm9sZSI7czo3OiJzdHVkZW50IjtzOjE0OiJzdHVkZW50X3NvdXJjZSI7czo3OiJzdHVkZW50Ijt9', 1776329299);

-- --------------------------------------------------------

--
-- Table structure for table `student_login_logs`
--

DROP TABLE IF EXISTS `student_login_logs`;
CREATE TABLE IF NOT EXISTS `student_login_logs` (
  `log_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `stu_id` bigint UNSIGNED DEFAULT NULL,
  `student_email` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `student_mobile` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `login_at` datetime DEFAULT NULL,
  `logout_at` datetime DEFAULT NULL,
  `last_seen_at` datetime DEFAULT NULL,
  `status` enum('online','offline') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'offline',
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`log_id`),
  KEY `student_login_logs_user_id_index` (`user_id`),
  KEY `student_login_logs_stu_id_index` (`stu_id`),
  KEY `student_login_logs_student_email_index` (`student_email`),
  KEY `student_login_logs_login_at_index` (`login_at`),
  KEY `student_login_logs_status_index` (`status`)
) ENGINE=MyISAM AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `student_login_logs`
--

INSERT INTO `student_login_logs` (`log_id`, `user_id`, `stu_id`, `student_email`, `student_mobile`, `login_at`, `logout_at`, `last_seen_at`, `status`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES
(1, 68, 58, 'abhi@gmail.com', '7482482388', '2026-04-14 06:47:28', '2026-04-14 06:51:06', '2026-04-14 06:51:06', 'offline', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-14 01:17:28', '2026-04-14 01:21:06'),
(2, 70, 59, 'saurabh@gmail.com', '6573836438', '2026-04-14 06:53:23', '2026-04-14 06:56:17', '2026-04-14 06:56:17', 'offline', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-14 01:23:23', '2026-04-14 01:26:17'),
(3, 70, 59, 'saurabh@gmail.com', '6573836438', '2026-04-14 07:15:10', '2026-04-14 07:17:37', '2026-04-14 07:17:37', 'offline', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-14 01:45:10', '2026-04-14 01:47:37'),
(4, 55, 51, 'sakshi45@example.com', '8800000045', '2026-04-14 07:20:52', '2026-04-14 07:22:34', '2026-04-14 07:22:34', 'offline', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-14 01:50:52', '2026-04-14 01:52:34'),
(5, 55, 51, 'spandey5449@gmail.com', '9506923396', '2026-04-14 07:24:10', '2026-04-14 07:49:49', '2026-04-14 07:49:49', 'offline', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-14 01:54:10', '2026-04-14 02:19:49'),
(6, 55, 51, 'spandey5449@gmail.com', '9506923396', '2026-04-14 07:50:19', '2026-04-14 07:51:50', '2026-04-14 07:51:50', 'offline', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-14 02:20:19', '2026-04-14 02:21:50'),
(7, 55, 51, 'spandey5449@gmail.com', '9506923396', '2026-04-14 07:52:23', '2026-04-14 08:08:51', '2026-04-14 08:08:51', 'offline', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-14 02:22:23', '2026-04-14 02:38:51'),
(8, 55, 51, 'spandey5449@gmail.com', '9506923396', '2026-04-14 09:39:15', NULL, '2026-04-14 09:39:15', 'online', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-14 04:09:15', '2026-04-14 04:09:15'),
(9, 55, 51, 'spandey5449@gmail.com', '9506923396', '2026-04-14 09:39:23', '2026-04-14 09:39:37', '2026-04-14 09:39:37', 'offline', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-14 04:09:23', '2026-04-14 04:09:37'),
(10, 55, 51, 'spandey5449@gmail.com', '9506923396', '2026-04-14 09:39:54', '2026-04-14 09:39:58', '2026-04-14 09:39:58', 'offline', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-14 04:09:54', '2026-04-14 04:09:58'),
(11, 55, 51, 'spandey5449@gmail.com', '9506923396', '2026-04-14 10:11:37', NULL, '2026-04-14 10:11:37', 'online', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-14 04:41:37', '2026-04-14 04:41:37'),
(12, 55, 51, 'spandey5449@gmail.com', '9506923396', '2026-04-14 10:12:11', '2026-04-14 10:13:03', '2026-04-14 10:13:03', 'offline', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-14 04:42:11', '2026-04-14 04:43:03'),
(13, 55, 51, 'spandey5449@gmail.com', '9506923396', '2026-04-14 10:16:11', '2026-04-14 10:18:02', '2026-04-14 10:18:02', 'offline', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-14 04:46:11', '2026-04-14 04:48:02'),
(14, 71, 51, 'spandey5449@gmail.com', '9506923396', '2026-04-14 10:32:00', '2026-04-14 10:32:24', '2026-04-14 10:32:24', 'offline', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-14 05:02:00', '2026-04-14 05:02:24'),
(15, 71, 51, 'spandey5449@gmail.com', '9506923396', '2026-04-14 10:33:20', '2026-04-14 10:33:24', '2026-04-14 10:33:24', 'offline', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-14 05:03:20', '2026-04-14 05:03:24'),
(16, 71, 51, 'spandey5449@gmail.com', '9506923396', '2026-04-14 10:35:54', NULL, '2026-04-14 10:35:54', 'online', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-14 05:05:54', '2026-04-14 05:05:54'),
(17, 71, 51, 'spandey5449@gmail.com', '9506923396', '2026-04-14 10:40:24', '2026-04-14 10:48:18', '2026-04-14 10:48:18', 'offline', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-14 05:10:24', '2026-04-14 05:18:18'),
(18, 66, NULL, 'student1@gmail.com', '7890916220', '2026-04-14 13:49:06', '2026-04-14 13:56:18', '2026-04-14 13:56:18', 'offline', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-14 08:19:06', '2026-04-14 08:26:18'),
(19, 70, 59, 'saurabh@gmail.com', '6573836438', '2026-04-15 13:51:11', '2026-04-15 13:51:40', '2026-04-15 13:51:40', 'offline', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-15 08:21:11', '2026-04-15 08:21:40'),
(20, 58, 54, 'ujjwal48@example.com', '8800000048', '2026-04-15 13:51:51', '2026-04-15 13:52:05', '2026-04-15 13:52:05', 'offline', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-15 08:21:51', '2026-04-15 08:22:05'),
(21, 71, 51, 'spandey5449@gmail.com', '9506923396', '2026-04-15 13:52:24', NULL, '2026-04-15 13:52:24', 'online', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-15 08:22:24', '2026-04-15 08:22:24'),
(22, 69, NULL, 'abhishek@gmail.com', '8790936220', '2026-04-16 04:51:40', '2026-04-16 05:03:54', '2026-04-16 05:03:54', 'offline', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-15 23:21:40', '2026-04-15 23:33:54'),
(23, 71, 51, 'spandey5449@gmail.com', '9506923396', '2026-04-16 05:04:02', '2026-04-16 05:04:14', '2026-04-16 05:04:14', 'offline', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-15 23:34:02', '2026-04-15 23:34:14'),
(24, 70, 59, 'saurabh@gmail.com', '6573836438', '2026-04-16 05:52:48', '2026-04-16 05:54:02', '2026-04-16 05:54:02', 'offline', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-16 00:22:48', '2026-04-16 00:24:02'),
(25, 72, 60, 'shubham@gmail.com', '7397865344', '2026-04-16 05:55:48', '2026-04-16 05:57:02', '2026-04-16 05:57:02', 'offline', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-16 00:25:48', '2026-04-16 00:27:02'),
(26, 72, 60, 'shubham@gmail.com', '7397865344', '2026-04-16 05:57:07', '2026-04-16 05:59:47', '2026-04-16 05:59:47', 'offline', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-16 00:27:07', '2026-04-16 00:29:47'),
(27, 71, 51, 'spandey5449@gmail.com', '9506923396', '2026-04-16 08:20:23', '2026-04-16 08:22:51', '2026-04-16 08:22:51', 'offline', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-16 02:50:23', '2026-04-16 02:52:51'),
(28, 71, 51, 'spandey5449@gmail.com', '9506923396', '2026-04-16 08:24:46', '2026-04-16 08:26:55', '2026-04-16 08:26:55', 'offline', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-16 02:54:46', '2026-04-16 02:56:55'),
(29, 64, NULL, 'student@gmail.com', '8390916220', '2026-04-16 08:27:02', '2026-04-16 08:30:42', '2026-04-16 08:30:42', 'offline', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-16 02:57:02', '2026-04-16 03:00:42'),
(30, 71, 51, 'spandey5449@gmail.com', '9506923396', '2026-04-16 08:30:51', '2026-04-16 08:31:13', '2026-04-16 08:31:13', 'offline', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-16 03:00:51', '2026-04-16 03:01:13'),
(31, 71, 51, 'spandey5449@gmail.com', '9506923396', '2026-04-16 08:31:27', NULL, '2026-04-16 08:31:27', 'online', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-16 03:01:27', '2026-04-16 03:01:27');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_assignments`
--

DROP TABLE IF EXISTS `tbl_assignments`;
CREATE TABLE IF NOT EXISTS `tbl_assignments` (
  `assignment_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `topic_id` bigint UNSIGNED NOT NULL,
  `subject_id` bigint UNSIGNED NOT NULL,
  `Assignment_Ques` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `Assignment_file` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `max_marks` int UNSIGNED DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`assignment_id`),
  KEY `tbl_assignments_topic_id_index` (`topic_id`),
  KEY `tbl_assignments_subject_id_index` (`subject_id`)
) ENGINE=MyISAM AUTO_INCREMENT=44 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tbl_assignments`
--

INSERT INTO `tbl_assignments` (`assignment_id`, `topic_id`, `subject_id`, `Assignment_Ques`, `Assignment_file`, `max_marks`, `due_date`, `created_at`, `updated_at`) VALUES
(39, 13, 1, 'Left panel mein brand features + decorative layout', '1775825852_xy8BnPcy_project.png', 5, '2020-10-10', '2026-04-10 07:27:32', '2026-04-10 07:27:32'),
(40, 13, 1, 'Smooth tab switching between Sign In / Create Account', '1775825852_xy8BnPcy_project.png', 5, '2020-10-10', '2026-04-10 07:27:32', '2026-04-10 07:27:32'),
(41, 13, 1, 'Toast notification on successful login/register', '1775825852_xy8BnPcy_project.png', 5, '2020-10-10', '2026-04-10 07:27:32', '2026-04-10 07:27:32'),
(42, 13, 1, 'Fully responsive — mobile pe left panel upar aata hai', '1775825852_xy8BnPcy_project.png', 5, '2020-10-10', '2026-04-10 07:27:32', '2026-04-10 07:27:32'),
(43, 13, 1, 'Poppins font throughout', '1775825852_xy8BnPcy_project.png', 5, '2020-10-10', '2026-04-10 07:27:32', '2026-04-10 07:27:32'),
(34, 12, 3, '<strong>Why is C called a \"mid-level\" language?</strong>', '1775815715_2itdQJIK_project.png', 5, '2020-10-10', '2026-04-10 05:16:01', '2026-04-10 05:16:01'),
(35, 12, 3, '<strong>What is a \"token\" in C?</strong>', '1775815715_2itdQJIK_project.png', 5, '2020-10-10', '2026-04-10 05:16:01', '2026-04-10 05:16:01'),
(36, 12, 3, '<strong>What is the difference between #include &lt;...&gt; and #include \"...\"?</strong>', '1775815715_2itdQJIK_project.png', 5, '2020-10-10', '2026-04-10 05:16:01', '2026-04-10 05:16:01'),
(37, 12, 3, '<strong>What does the static keyword do?</strong>', '1775815715_2itdQJIK_project.png', 5, '2020-10-10', '2026-04-10 05:16:01', '2026-04-10 05:16:01'),
(38, 12, 3, '<strong>Dangling Pointers vs. Memory Leaks</strong>', '1775815715_2itdQJIK_project.png', 5, '2020-10-10', '2026-04-10 05:16:01', '2026-04-10 05:16:01');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_attendance`
--

DROP TABLE IF EXISTS `tbl_attendance`;
CREATE TABLE IF NOT EXISTS `tbl_attendance` (
  `attend_id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `attend_date` varchar(30) NOT NULL,
  `attend_session` varchar(30) NOT NULL DEFAULT '',
  `attend_center` varchar(30) NOT NULL DEFAULT '',
  `attend_programme` varchar(30) NOT NULL DEFAULT '',
  `attend_technology` varchar(30) NOT NULL,
  `attend_batch` varchar(30) NOT NULL,
  `attend_student` varchar(30) NOT NULL,
  `attendance` varchar(10) NOT NULL,
  `attend_by` varchar(30) NOT NULL DEFAULT '48',
  `added_date` varchar(30) NOT NULL,
  `added_time` varchar(30) NOT NULL,
  PRIMARY KEY (`attend_id`),
  KEY `idx_attend_student` (`attend_student`),
  KEY `idx_attendance_flag` (`attend_student`,`attendance`),
  KEY `idx_tech_batch_student` (`attend_technology`,`attend_batch`,`attend_student`),
  KEY `idx_tech_batch_date` (`attend_technology`,`attend_batch`,`attend_date`),
  KEY `idx_student` (`attend_student`),
  KEY `idx_date` (`attend_date`),
  KEY `idx_attend_session` (`attend_session`),
  KEY `idx_attend_center` (`attend_center`),
  KEY `idx_attend_programme` (`attend_programme`),
  KEY `idx_session_center_programme` (`attend_session`,`attend_center`,`attend_programme`)
) ENGINE=MyISAM AUTO_INCREMENT=37 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_attendance`
--

INSERT INTO `tbl_attendance` (`attend_id`, `attend_date`, `attend_session`, `attend_center`, `attend_programme`, `attend_technology`, `attend_batch`, `attend_student`, `attendance`, `attend_by`, `added_date`, `added_time`) VALUES
(1, '2026-04-08', '1', '1', '1', '1', '1', '56', 'P', '48', '2026-04-08', '12:12:39'),
(4, '2026-04-08', '1', '3', '1', '1', '1', '55', 'P', '48', '2026-04-08', '20:12:41'),
(5, '2026-04-08', '1', '1', '1', '1', '1', '51', 'A', '48', '2026-04-08', '11:05:25'),
(11, '2026-05-07', '1', '1', '1', '3', '2', '50', 'A', '48', '2026-04-08', '12:16:53'),
(10, '2026-05-07', '1', '1', '1', '1', '1', '53', 'P', '48', '2026-04-08', '10:12:23'),
(6, '2026-04-08', '1', '1', '1', '1', '1', '53', 'P', '48', '2026-04-08', '11:10:26'),
(7, '2026-04-08', '1', '1', '1', '3', '2', '50', 'A', '48', '2026-04-08', '11:51:31'),
(8, '2026-04-08', '1', '3', '1', '1', '1', '54', 'A', '48', '2026-04-08', '12:50:38'),
(9, '2026-04-08', '1', '1', '1', '1', '1', '52', 'P', '48', '2026-04-08', '10:56:53'),
(12, '2026-05-07', '1', '3', '1', '1', '1', '55', 'P', '48', '2026-04-08', '12:16:49'),
(13, '2026-05-07', '1', '1', '1', '1', '1', '51', 'A', '48', '2026-04-08', '10:12:23'),
(14, '2026-05-07', '1', '3', '1', '1', '1', '54', 'P', '48', '2026-04-08', '10:12:23'),
(15, '2026-05-07', '1', '1', '1', '1', '1', '52', 'A', '48', '2026-04-08', '10:12:23'),
(16, '2026-04-09', '1', '1', '1', '1', '1', '53', 'P', '48', '2026-04-09', '18:53:01'),
(17, '2026-04-09', '1', '1', '1', '3', '2', '50', 'P', '48', '2026-04-09', '18:52:53'),
(18, '2026-04-09', '1', '3', '1', '1', '1', '55', 'A', '48', '2026-04-08', '12:50:38'),
(19, '2026-04-09', '1', '1', '1', '1', '1', '51', 'P', '48', '2026-04-08', '11:36:20'),
(20, '2026-04-09', '1', '3', '1', '1', '1', '54', 'A', '48', '2026-04-08', '12:50:38'),
(21, '2026-04-09', '1', '1', '1', '1', '1', '52', 'P', '48', '2026-04-14', '09:13:28'),
(22, '2026-04-10', '1', '1', '1', '1', '1', '53', 'A', '48', '2026-04-14', '09:13:18'),
(23, '2026-04-10', '1', '1', '1', '3', '2', '50', 'P', '48', '2026-04-08', '12:17:00'),
(24, '2026-04-10', '1', '3', '1', '1', '1', '55', 'A', '48', '2026-04-14', '09:13:13'),
(25, '2026-04-10', '1', '1', '1', '1', '1', '51', 'P', '48', '2026-04-10', '12:04:12'),
(26, '2026-04-10', '1', '3', '1', '1', '1', '54', 'P', '48', '2026-04-08', '12:12:07'),
(27, '2026-04-10', '1', '1', '1', '1', '1', '52', 'P', '48', '2026-04-08', '20:12:36'),
(28, '2026-04-14', '1', '3', '1', '1', '1', '58', 'P', '48', '2026-04-14', '10:03:26'),
(29, '2026-04-14', '1', '3', '1', '3', '2', '58', 'P', '48', '2026-04-14', '10:03:26'),
(30, '2026-04-14', '1', '3', '1', '1', '1', '57', 'P', '48', '2026-04-14', '10:03:26'),
(31, '2026-04-14', '1', '1', '1', '1', '1', '53', 'P', '48', '2026-04-14', '10:03:26'),
(32, '2026-04-14', '1', '1', '1', '3', '2', '50', 'P', '48', '2026-04-14', '10:03:26'),
(33, '2026-04-14', '1', '3', '1', '1', '1', '55', 'A', '48', '2026-04-14', '10:03:26'),
(34, '2026-04-14', '1', '1', '1', '1', '1', '51', 'A', '48', '2026-04-14', '10:03:26'),
(35, '2026-04-14', '1', '3', '1', '1', '1', '54', 'P', '48', '2026-04-14', '10:03:26'),
(36, '2026-04-14', '1', '1', '1', '1', '1', '52', 'P', '48', '2026-04-14', '10:03:26');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_batch`
--

DROP TABLE IF EXISTS `tbl_batch`;
CREATE TABLE IF NOT EXISTS `tbl_batch` (
  `batch_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `session_id` bigint UNSIGNED NOT NULL,
  `programme_id` bigint UNSIGNED NOT NULL,
  `technology_id` bigint UNSIGNED NOT NULL,
  `batch_name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_by` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_by` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`batch_id`),
  UNIQUE KEY `uq_technology_batch_name` (`technology_id`,`batch_name`),
  KEY `tbl_batch_session_id_foreign` (`session_id`),
  KEY `tbl_batch_programme_id_foreign` (`programme_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tbl_batch`
--

INSERT INTO `tbl_batch` (`batch_id`, `session_id`, `programme_id`, `technology_id`, `batch_name`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, 'Full-Stack 11', 'admin', 'admin', '2026-04-03 07:25:50', '2026-04-03 07:25:50'),
(2, 1, 1, 3, 'Full-Stack 5', 'admin', 'admin', '2026-04-03 07:26:07', '2026-04-03 07:26:07'),
(3, 1, 1, 2, 'Full-Stack 1', 'admin', 'admin', '2026-04-03 07:26:23', '2026-04-03 07:26:23');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_branches`
--

DROP TABLE IF EXISTS `tbl_branches`;
CREATE TABLE IF NOT EXISTS `tbl_branches` (
  `branch_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `course_id` bigint UNSIGNED NOT NULL,
  `branch_name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_by` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_by` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`branch_id`),
  KEY `tbl_branches_course_id_foreign` (`course_id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tbl_branches`
--

INSERT INTO `tbl_branches` (`branch_id`, `course_id`, `branch_name`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 2, 'CSE', 'Admin', 'Admin', '2026-04-03 07:30:12', '2026-04-03 07:30:12'),
(3, 1, 'CSE', 'Admin', 'Admin', '2026-04-03 07:30:29', '2026-04-03 07:30:29'),
(4, 1, 'IT', 'Admin', 'Admin', '2026-04-03 07:30:39', '2026-04-03 07:30:39'),
(5, 2, 'ME', 'Admin', 'Admin', '2026-04-03 07:30:48', '2026-04-03 07:30:48'),
(6, 2, 'ELx', 'Admin', 'Admin', '2026-04-03 07:30:56', '2026-04-03 07:30:56'),
(7, 2, 'Civil Engineering', 'Admin', 'Admin', '2026-04-03 07:31:15', '2026-04-03 07:31:15'),
(8, 2, 'IT', 'Admin', 'Admin', '2026-04-08 02:41:57', '2026-04-08 02:41:57');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_centers`
--

DROP TABLE IF EXISTS `tbl_centers`;
CREATE TABLE IF NOT EXISTS `tbl_centers` (
  `center_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `center_name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `latitude` decimal(10,7) NOT NULL,
  `longitude` decimal(10,7) NOT NULL,
  `radius` decimal(10,2) NOT NULL COMMENT 'Radius in meter',
  `created_by` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_by` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`center_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tbl_centers`
--

INSERT INTO `tbl_centers` (`center_id`, `center_name`, `latitude`, `longitude`, `radius`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 'Softpro House', 26.9236100, 80.9647054, 100.00, 'Admin', 'Admin', '2026-04-03 07:29:21', '2026-04-03 07:29:21'),
(3, 'Full Stack Academy', 26.9236100, 80.9647054, 20.00, 'Admin', 'Admin', '2026-04-07 03:35:21', '2026-04-07 03:35:21');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_courses`
--

DROP TABLE IF EXISTS `tbl_courses`;
CREATE TABLE IF NOT EXISTS `tbl_courses` (
  `course_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `course_name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `duration` int NOT NULL COMMENT 'Duration in years',
  `created_by` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_by` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`course_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tbl_courses`
--

INSERT INTO `tbl_courses` (`course_id`, `course_name`, `duration`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 'Diploma', 3, 'Admin', 'Admin', '2026-04-03 07:29:48', '2026-04-08 02:41:29'),
(2, 'B. Tech.', 4, 'Admin', 'Admin', '2026-04-03 07:29:59', '2026-04-03 07:29:59');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_employee`
--

DROP TABLE IF EXISTS `tbl_employee`;
CREATE TABLE IF NOT EXISTS `tbl_employee` (
  `emp_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `emp_role` int NOT NULL DEFAULT '5',
  `emp_name` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `emp_regid` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `center_id` bigint UNSIGNED NOT NULL,
  `emp_mobile` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `emp_email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `emp_password` text COLLATE utf8mb4_unicode_ci,
  `emp_gender` enum('Male','Female','Other') COLLATE utf8mb4_unicode_ci NOT NULL,
  `emp_type` enum('Internal','External') COLLATE utf8mb4_unicode_ci NOT NULL,
  `emp_pic` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '1',
  `del_status` enum('N','Y') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'N',
  `is_block` enum('N','Y') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'N',
  `created_by` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_by` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`emp_id`),
  UNIQUE KEY `tbl_employee_emp_regid_unique` (`emp_regid`),
  UNIQUE KEY `tbl_employee_emp_mobile_unique` (`emp_mobile`),
  UNIQUE KEY `tbl_employee_emp_email_unique` (`emp_email`),
  KEY `tbl_employee_center_id_index` (`center_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tbl_employee`
--

INSERT INTO `tbl_employee` (`emp_id`, `emp_role`, `emp_name`, `emp_regid`, `center_id`, `emp_mobile`, `emp_email`, `emp_password`, `emp_gender`, `emp_type`, `emp_pic`, `status`, `del_status`, `is_block`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 5, 'Aditya Pratap', 'Emp-101', 1, '8009423208', 'employee05@gmail.com', '$2y$12$XtEZdjlCIkMw2Gm2riEY4.lD/seHMRyZBkkIjnP918H8P1P69fi1u', 'Male', 'Internal', '1775475741_Screenshot_2025-12-11_164959.png', 1, 'N', 'N', 'Admin', 'Admin', '2026-04-06 06:11:56', '2026-04-06 07:51:33'),
(2, 5, 'Aditya Pratap', 'Emp-102', 1, '8009434652', 'adityapratap0567@gmail.com', '$2y$12$1YxSyPPHzcvi/ixyvOTBBOR1TWXJ6JYIWyaI5XX2zt3ZUKu8aMCcm', 'Male', 'External', '1775483286_WhatsApp_Image_2026-01-13_at_10.44.53_PM.jpeg', 1, 'N', 'N', 'Admin', 'Admin', '2026-04-06 08:18:07', '2026-04-06 08:18:07');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_employee_batch`
--

DROP TABLE IF EXISTS `tbl_employee_batch`;
CREATE TABLE IF NOT EXISTS `tbl_employee_batch` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `emp_id` bigint UNSIGNED NOT NULL,
  `batch_id` bigint UNSIGNED NOT NULL,
  `is_active` enum('Y','N') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Y',
  `created_by` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_by` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tbl_employee_batch_emp_id_batch_id_unique` (`emp_id`,`batch_id`)
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tbl_employee_batch`
--

INSERT INTO `tbl_employee_batch` (`id`, `emp_id`, `batch_id`, `is_active`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(15, 1, 3, 'Y', 'Admin', 'Admin', '2026-04-08 02:43:22', '2026-04-08 02:43:22'),
(13, 1, 1, 'Y', 'Admin', 'Admin', '2026-04-08 02:43:22', '2026-04-08 02:43:22'),
(14, 1, 2, 'Y', 'Admin', 'Admin', '2026-04-08 02:43:22', '2026-04-08 02:43:22'),
(16, 2, 1, 'Y', 'Admin', 'Admin', '2026-04-08 02:44:13', '2026-04-08 02:44:13'),
(17, 2, 2, 'Y', 'Admin', 'Admin', '2026-04-08 02:44:13', '2026-04-08 02:44:13'),
(18, 2, 3, 'Y', 'Admin', 'Admin', '2026-04-08 02:44:13', '2026-04-08 02:44:13');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_employee_programme`
--

DROP TABLE IF EXISTS `tbl_employee_programme`;
CREATE TABLE IF NOT EXISTS `tbl_employee_programme` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `emp_id` bigint UNSIGNED NOT NULL,
  `programme_id` bigint UNSIGNED NOT NULL,
  `is_active` enum('Y','N') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Y',
  `created_by` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_by` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tbl_employee_programme_emp_id_programme_id_unique` (`emp_id`,`programme_id`)
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tbl_employee_programme`
--

INSERT INTO `tbl_employee_programme` (`id`, `emp_id`, `programme_id`, `is_active`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(13, 1, 6, 'Y', 'Admin', 'Admin', '2026-04-08 02:43:22', '2026-04-08 02:43:22'),
(12, 1, 5, 'Y', 'Admin', 'Admin', '2026-04-08 02:43:22', '2026-04-08 02:43:22'),
(11, 1, 4, 'Y', 'Admin', 'Admin', '2026-04-08 02:43:22', '2026-04-08 02:43:22'),
(10, 1, 1, 'Y', 'Admin', 'Admin', '2026-04-08 02:43:22', '2026-04-08 02:43:22'),
(14, 2, 1, 'Y', 'Admin', 'Admin', '2026-04-08 02:44:13', '2026-04-08 02:44:13'),
(15, 2, 4, 'Y', 'Admin', 'Admin', '2026-04-08 02:44:13', '2026-04-08 02:44:13'),
(16, 2, 5, 'Y', 'Admin', 'Admin', '2026-04-08 02:44:13', '2026-04-08 02:44:13'),
(17, 2, 6, 'Y', 'Admin', 'Admin', '2026-04-08 02:44:13', '2026-04-08 02:44:13');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_employee_session`
--

DROP TABLE IF EXISTS `tbl_employee_session`;
CREATE TABLE IF NOT EXISTS `tbl_employee_session` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `emp_id` bigint UNSIGNED NOT NULL,
  `session_id` bigint UNSIGNED NOT NULL,
  `is_active` enum('Y','N') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Y',
  `created_by` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_by` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tbl_employee_session_emp_id_session_id_unique` (`emp_id`,`session_id`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tbl_employee_session`
--

INSERT INTO `tbl_employee_session` (`id`, `emp_id`, `session_id`, `is_active`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(10, 1, 1, 'Y', 'Admin', 'Admin', '2026-04-08 02:43:22', '2026-04-08 02:43:22'),
(11, 1, 3, 'Y', 'Admin', 'Admin', '2026-04-08 02:43:22', '2026-04-08 02:43:22'),
(12, 1, 2, 'Y', 'Admin', 'Admin', '2026-04-08 02:43:22', '2026-04-08 02:43:22'),
(13, 2, 1, 'Y', 'Admin', 'Admin', '2026-04-08 02:44:13', '2026-04-08 02:44:13'),
(14, 2, 3, 'Y', 'Admin', 'Admin', '2026-04-08 02:44:13', '2026-04-08 02:44:13'),
(15, 2, 2, 'Y', 'Admin', 'Admin', '2026-04-08 02:44:13', '2026-04-08 02:44:13');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_employee_technology`
--

DROP TABLE IF EXISTS `tbl_employee_technology`;
CREATE TABLE IF NOT EXISTS `tbl_employee_technology` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `emp_id` bigint UNSIGNED NOT NULL,
  `technology_id` bigint UNSIGNED NOT NULL,
  `is_active` enum('Y','N') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Y',
  `created_by` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_by` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tbl_employee_technology_emp_id_technology_id_unique` (`emp_id`,`technology_id`)
) ENGINE=MyISAM AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tbl_employee_technology`
--

INSERT INTO `tbl_employee_technology` (`id`, `emp_id`, `technology_id`, `is_active`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(15, 1, 2, 'Y', 'Admin', 'Admin', '2026-04-08 02:43:22', '2026-04-08 02:43:22'),
(16, 1, 3, 'Y', 'Admin', 'Admin', '2026-04-08 02:43:22', '2026-04-08 02:43:22'),
(17, 1, 4, 'Y', 'Admin', 'Admin', '2026-04-08 02:43:22', '2026-04-08 02:43:22'),
(14, 1, 1, 'Y', 'Admin', 'Admin', '2026-04-08 02:43:22', '2026-04-08 02:43:22'),
(18, 2, 1, 'Y', 'Admin', 'Admin', '2026-04-08 02:44:13', '2026-04-08 02:44:13'),
(19, 2, 2, 'Y', 'Admin', 'Admin', '2026-04-08 02:44:13', '2026-04-08 02:44:13'),
(20, 2, 3, 'Y', 'Admin', 'Admin', '2026-04-08 02:44:13', '2026-04-08 02:44:13'),
(21, 2, 4, 'Y', 'Admin', 'Admin', '2026-04-08 02:44:13', '2026-04-08 02:44:13');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_feedback_forms`
--

DROP TABLE IF EXISTS `tbl_feedback_forms`;
CREATE TABLE IF NOT EXISTS `tbl_feedback_forms` (
  `form_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `public_key` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `session_ids` json DEFAULT NULL,
  `center_ids` json DEFAULT NULL,
  `programme_ids` json DEFAULT NULL,
  `technology_ids` json DEFAULT NULL,
  `batch_ids` json DEFAULT NULL,
  `form_status` enum('open','close') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'open',
  `questions_json` json NOT NULL,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`form_id`),
  UNIQUE KEY `tbl_feedback_forms_public_key_unique` (`public_key`)
) ENGINE=MyISAM AUTO_INCREMENT=44 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tbl_feedback_forms`
--

INSERT INTO `tbl_feedback_forms` (`form_id`, `public_key`, `title`, `description`, `session_ids`, `center_ids`, `programme_ids`, `technology_ids`, `batch_ids`, `form_status`, `questions_json`, `created_by`, `created_at`, `updated_at`) VALUES
(39, '54b4a75c-ba7b-403a-be45-3ab13fcb3a4c', 'PHP Feedback Form', 'PHP Feedback Form', '[1]', '[3]', '[1]', '[3]', '[2]', 'open', '[{\"uid\": \"q_1776258837179_1\", \"text\": \"PHP Feedback Form\", \"type\": \"rating\", \"order\": 1, \"required\": true}]', 6, '2026-04-15 07:45:11', '2026-04-16 02:54:30'),
(40, 'df9cad3c-d5e1-40e2-87fa-33311ad8ce2b', 'Form For Edit', 'feedback Form', '[1]', '[3]', '[1]', '[1, 3]', '[1, 2]', 'open', '[{\"uid\": \"q_vyga6fqbokk1776316584531\", \"text\": \"html\", \"type\": \"short_answer\", \"order\": 1, \"required\": true}]', 6, '2026-04-15 23:47:28', '2026-04-16 02:48:50'),
(29, 'a741b5ec-2d0f-4455-a709-ed44ff17af14', 'Java Full Stack development', 'Java Full Stack Development', '[\"1\"]', '[\"3\"]', '[\"1\"]', '[\"1\"]', '[\"1\"]', 'open', '[{\"uid\": \"q_ldwid5mzmr1776236319329\", \"text\": \"html\", \"type\": \"short_answer\", \"options\": [\"Option 1\"], \"required\": true, \"rating_scale\": 5}]', 6, '2026-04-15 01:29:32', '2026-04-15 01:29:32'),
(34, 'fe3389df-9323-431e-b9d4-3ca9a66687cb', 'Feedback Form 2026', 'feedback form 2026', '[1]', '[3]', '[1]', '[1]', '[1]', 'open', '[{\"uid\": \"q_1776252798803_1\", \"text\": \"html\", \"type\": \"short_answer\", \"order\": 1, \"required\": false}]', 6, '2026-04-15 06:04:53', '2026-04-15 06:04:53'),
(36, '8e12868b-c07c-4702-91b2-b5b93cb7c95b', 'Java Full-Stack Development', 'java Full Stack Development', '[1]', '[3]', '[1]', '[1]', '[1]', 'open', '[{\"uid\": \"q_1776253665695_1\", \"text\": \"html\", \"type\": \"short_answer\", \"order\": 1, \"required\": false}, {\"uid\": \"q_1776253867532_2\", \"text\": \"css\", \"type\": \"short_answer\", \"order\": 2, \"required\": false}, {\"uid\": \"q_1776253871095_3\", \"text\": \"js\", \"type\": \"short_answer\", \"order\": 3, \"required\": false}]', 6, '2026-04-15 06:21:15', '2026-04-15 06:21:15');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_feedback_responses`
--

DROP TABLE IF EXISTS `tbl_feedback_responses`;
CREATE TABLE IF NOT EXISTS `tbl_feedback_responses` (
  `response_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `form_id` bigint UNSIGNED NOT NULL,
  `student_id` bigint UNSIGNED DEFAULT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `session_id` bigint UNSIGNED DEFAULT NULL,
  `center_id` bigint UNSIGNED DEFAULT NULL,
  `programme_id` bigint UNSIGNED DEFAULT NULL,
  `technology_id` bigint UNSIGNED DEFAULT NULL,
  `batch_id` bigint UNSIGNED DEFAULT NULL,
  `student_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mobile` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `answers_json` json NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`response_id`),
  KEY `tbl_feedback_responses_form_id_index` (`form_id`),
  KEY `tbl_feedback_responses_student_id_index` (`student_id`),
  KEY `tbl_feedback_responses_session_id_index` (`session_id`),
  KEY `tbl_feedback_responses_center_id_index` (`center_id`),
  KEY `tbl_feedback_responses_programme_id_index` (`programme_id`),
  KEY `tbl_feedback_responses_technology_id_index` (`technology_id`),
  KEY `tbl_feedback_responses_batch_id_index` (`batch_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_mock_tests`
--

DROP TABLE IF EXISTS `tbl_mock_tests`;
CREATE TABLE IF NOT EXISTS `tbl_mock_tests` (
  `mock_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `subject_id` bigint UNSIGNED NOT NULL,
  `topic_id` bigint UNSIGNED NOT NULL,
  `mock_test` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `marks` int UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`mock_id`),
  KEY `tbl_mock_tests_subject_id_index` (`subject_id`),
  KEY `tbl_mock_tests_topic_id_index` (`topic_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tbl_mock_tests`
--

INSERT INTO `tbl_mock_tests` (`mock_id`, `subject_id`, `topic_id`, `mock_test`, `marks`, `created_at`, `updated_at`) VALUES
(2, 3, 12, '<ol><li><strong>Why is C called a \"mid-level\" language?</strong></li><li><strong>What is a \"token\" in C?</strong></li></ol>', 25, '2026-04-10 04:38:35', '2026-04-10 05:16:01'),
(3, 1, 13, '<ul><li>Left panel mein brand features + decorative layout</li><li>Smooth tab switching between Sign In / Create Account</li><li>Toast notification on successful login/register</li><li>Fully responsive — mobile pe left panel upar aata hai</li><li>Poppins font throughout</li></ul><p>&nbsp;</p><p><br>&nbsp;</p>', 5, '2026-04-10 07:27:32', '2026-04-10 07:27:32');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_notification`
--

DROP TABLE IF EXISTS `tbl_notification`;
CREATE TABLE IF NOT EXISTS `tbl_notification` (
  `notification_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `session_id` bigint UNSIGNED NOT NULL,
  `center_id` bigint UNSIGNED NOT NULL,
  `programme_id` bigint UNSIGNED NOT NULL,
  `technology_id` bigint UNSIGNED NOT NULL,
  `batch_id` bigint UNSIGNED NOT NULL,
  `notification_head_id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notification_file` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci,
  `created_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`notification_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tbl_notification`
--

INSERT INTO `tbl_notification` (`notification_id`, `session_id`, `center_id`, `programme_id`, `technology_id`, `batch_id`, `notification_head_id`, `title`, `notification_file`, `description`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 1, 3, 1, 1, 1, 1, 'First Notification', '1775888058_69d9e6baa2f7d_Resume.pdf', '<p>&nbsp; Route::get(\'/notifications/centers/{sessionId}\', [NotificationController::class, \'getCentersBySession\'])-&gt;name(\'admin.notifications.centers\');</p><p>&nbsp; Route::get(\'/notifications/programmes\', [NotificationController::class, \'getProgrammes\'])-&gt;name(\'admin.notifications.programmes\');</p><p>&nbsp; Route::get(\'/notifications/technologies/{programmeId}\', [NotificationController::class, \'getTechnologiesByProgramme\'])-&gt;name(\'admin.notifications.technologies\');</p><p>&nbsp; Route::get(\'/notifications/batches/{technologyId}\', [NotificationController::class, \'getBatchesByTechnology\'])-&gt;name(\'admin.notifications.batches\');</p>', 'Admin', 'Admin', '2026-04-11 00:44:18', '2026-04-11 00:44:18'),
(2, 1, 3, 1, 3, 2, 2, 'PHP Notification', '1775888300_69d9e7ac7081d_Java.pdf', '<p>&nbsp; Route::get(\'/notifications/centers/{sessionId}\', [NotificationController::class, \'getCentersBySession\'])-&gt;name(\'admin.notifications.centers\');</p><p>&nbsp; Route::get(\'/notifications/programmes\', [NotificationController::class, \'getProgrammes\'])-&gt;name(\'admin.notifications.programmes\');</p>', 'Admin', 'Admin', '2026-04-11 00:48:20', '2026-04-11 00:48:20'),
(3, 1, 3, 1, 1, 1, 2, 'Second Notification', '1775890736_69d9f1307075e_Java.pdf', '<p>$notification-&gt;update([<br>&nbsp; &nbsp;\'session_id\' &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; =&gt; $validated[\'session_id\'],<br>&nbsp; &nbsp;\'center_id\' &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;=&gt; $validated[\'center_id\'],<br>&nbsp; &nbsp;\'programme_id\' &nbsp; &nbsp; &nbsp; &nbsp; =&gt; $validated[\'programme_id\'],<br>&nbsp; &nbsp;\'technology_id\' &nbsp; &nbsp; &nbsp; &nbsp;=&gt; $validated[\'technology_id\'],<br>&nbsp; &nbsp;\'batch_id\' &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; =&gt; $validated[\'batch_id\'],<br>&nbsp; &nbsp;\'notification_head_id\' =&gt; $validated[\'notification_head_id\'],<br>&nbsp; &nbsp;\'title\' &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;=&gt; trim($validated[\'title\']),<br>&nbsp; &nbsp;\'notification_file\' &nbsp; &nbsp;=&gt; $fileName,<br>&nbsp; &nbsp;\'description\' &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;=&gt; $validated[\'description\'] ?? null,<br>&nbsp; &nbsp;\'updated_by\' &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; =&gt; $user-&gt;name ?? $user-&gt;email ?? \'Admin\',<br>&nbsp; &nbsp;\'updated_at\' &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; =&gt; now(),<br>]);</p>', 'Admin', 'Admin', '2026-04-11 01:28:56', '2026-04-11 01:28:56');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_notification_heads`
--

DROP TABLE IF EXISTS `tbl_notification_heads`;
CREATE TABLE IF NOT EXISTS `tbl_notification_heads` (
  `notification_head_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `notification_head` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_by` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`notification_head_id`),
  UNIQUE KEY `tbl_notification_heads_notification_head_unique` (`notification_head`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tbl_notification_heads`
--

INSERT INTO `tbl_notification_heads` (`notification_head_id`, `notification_head`, `created_by`, `created_at`, `updated_at`) VALUES
(1, '1st Notification', 'Admin', '2026-04-11 00:01:05', '2026-04-11 00:01:05'),
(2, '2nd Notification', 'Admin', '2026-04-11 00:01:16', '2026-04-11 00:01:16'),
(3, '3rd Notification', 'Admin', '2026-04-11 00:01:27', '2026-04-11 00:01:27');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_online_tests`
--

DROP TABLE IF EXISTS `tbl_online_tests`;
CREATE TABLE IF NOT EXISTS `tbl_online_tests` (
  `online_test_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `subject_id` bigint UNSIGNED NOT NULL,
  `topic_id` bigint UNSIGNED NOT NULL,
  `total_questions` int UNSIGNED NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`online_test_id`),
  KEY `tbl_online_tests_subject_id_index` (`subject_id`),
  KEY `tbl_online_tests_topic_id_index` (`topic_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tbl_online_tests`
--

INSERT INTO `tbl_online_tests` (`online_test_id`, `subject_id`, `topic_id`, `total_questions`, `created_at`, `updated_at`) VALUES
(2, 3, 12, 1, '2026-04-10 05:16:01', '2026-04-10 05:16:01');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_online_test_questions`
--

DROP TABLE IF EXISTS `tbl_online_test_questions`;
CREATE TABLE IF NOT EXISTS `tbl_online_test_questions` (
  `otq_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `online_test_id` bigint UNSIGNED NOT NULL,
  `question_no` int UNSIGNED NOT NULL,
  `marks` int UNSIGNED NOT NULL DEFAULT '0',
  `question` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `option_a` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `option_b` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `option_c` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `option_d` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `correct_option` enum('A','B','C','D') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`otq_id`),
  KEY `tbl_online_test_questions_online_test_id_index` (`online_test_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tbl_online_test_questions`
--

INSERT INTO `tbl_online_test_questions` (`otq_id`, `online_test_id`, `question_no`, `marks`, `question`, `option_a`, `option_b`, `option_c`, `option_d`, `correct_option`, `created_at`, `updated_at`) VALUES
(3, 2, 1, 5, '<ol><li><strong>Why is C called a \"mid-level\" language?</strong></li></ol>', 'A', 'B', 'C', 'D', 'A', '2026-04-10 05:16:01', '2026-04-10 05:16:01');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_popup_notification`
--

DROP TABLE IF EXISTS `tbl_popup_notification`;
CREATE TABLE IF NOT EXISTS `tbl_popup_notification` (
  `popup_notification_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `session_ids` json DEFAULT NULL,
  `center_ids` json DEFAULT NULL,
  `programme_ids` json DEFAULT NULL,
  `technology_ids` json DEFAULT NULL,
  `batch_ids` json DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `popup_from` datetime DEFAULT NULL,
  `popup_to` datetime DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci,
  `created_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`popup_notification_id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tbl_popup_notification`
--

INSERT INTO `tbl_popup_notification` (`popup_notification_id`, `session_ids`, `center_ids`, `programme_ids`, `technology_ids`, `batch_ids`, `title`, `popup_from`, `popup_to`, `image`, `description`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(5, '[1]', '[3, 1]', '[1, 4]', '[3]', '[2]', 'POP up NOTI', '2020-10-10 00:10:00', '2020-10-10 23:40:00', '1775901532_69da1b5ca137e_WhatsApp_Image_2026-04-10_at_3.06.29_PM.jpeg', 'jfdjgfhsdgfsdfs dfsdjf', 'Admin', 'Admin', '2026-04-11 04:28:52', '2026-04-11 04:59:42'),
(6, '[1]', '[3]', '[1]', '[4, 1, 3, 2]', '[3, 1, 2]', 'This is Notice', '2026-10-10 14:00:00', '2027-10-11 23:22:00', '1775903855_69da246f71808_WhatsApp_Image_2026-04-08_at_10.40.02_AM.jpeg', 'kasdjvkj   sd  ksdjfhjsdhfjs', 'Admin', 'Admin', '2026-04-11 04:43:14', '2026-04-11 05:07:35');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_programme`
--

DROP TABLE IF EXISTS `tbl_programme`;
CREATE TABLE IF NOT EXISTS `tbl_programme` (
  `programme_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `session_id` bigint UNSIGNED NOT NULL,
  `programme_name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `duration` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_by` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_by` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `restored_at` timestamp NULL DEFAULT NULL,
  `restored_by` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`programme_id`),
  UNIQUE KEY `uq_programme_session_name` (`session_id`,`programme_name`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tbl_programme`
--

INSERT INTO `tbl_programme` (`programme_id`, `session_id`, `programme_name`, `duration`, `created_by`, `updated_by`, `deleted_at`, `deleted_by`, `restored_at`, `restored_by`, `created_at`, `updated_at`) VALUES
(1, 1, 'Full-Stack Development', '6 Month', 'admin', 'admin', NULL, NULL, '2026-04-08 14:39:53', 'admin', '2026-04-03 07:24:40', '2026-04-08 14:39:53'),
(4, 1, 'Industrial Training', '1.5 Month', 'admin', 'admin', NULL, NULL, NULL, NULL, '2026-04-07 03:34:26', '2026-04-07 03:34:26'),
(5, 1, 'Web Technology', '6 Month', 'admin', 'admin', NULL, NULL, NULL, NULL, '2026-04-07 05:26:03', '2026-04-07 05:26:03'),
(6, 1, 'Summar Training', '1.5 Month', 'admin', 'admin', NULL, NULL, NULL, NULL, '2026-04-07 05:48:47', '2026-04-07 05:48:47');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_programme_centers`
--

DROP TABLE IF EXISTS `tbl_programme_centers`;
CREATE TABLE IF NOT EXISTS `tbl_programme_centers` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `session_id` bigint UNSIGNED NOT NULL,
  `programme_id` bigint UNSIGNED NOT NULL,
  `center_id` bigint UNSIGNED NOT NULL,
  `created_by` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_by` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_programme_center_session` (`session_id`,`programme_id`,`center_id`)
) ENGINE=MyISAM AUTO_INCREMENT=52 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tbl_programme_centers`
--

INSERT INTO `tbl_programme_centers` (`id`, `session_id`, `programme_id`, `center_id`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(41, 1, 4, 1, 'Admin', 'Admin', '2026-04-07 05:47:01', '2026-04-07 05:47:01'),
(34, 1, 5, 3, 'Admin', 'Admin', '2026-04-07 05:26:18', '2026-04-07 05:26:18'),
(40, 1, 4, 3, 'Admin', 'Admin', '2026-04-07 05:47:01', '2026-04-07 05:47:01'),
(39, 1, 4, 2, 'Admin', 'Admin', '2026-04-07 05:46:28', '2026-04-07 05:46:28'),
(51, 1, 1, 3, 'Admin', 'Admin', '2026-04-08 14:00:29', '2026-04-08 14:00:29'),
(35, 1, 5, 1, 'Admin', 'Admin', '2026-04-07 05:26:18', '2026-04-07 05:26:18'),
(36, 1, 5, 2, 'Admin', 'Admin', '2026-04-07 05:26:18', '2026-04-07 05:26:18');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_qr_attendance_device_logs`
--

DROP TABLE IF EXISTS `tbl_qr_attendance_device_logs`;
CREATE TABLE IF NOT EXISTS `tbl_qr_attendance_device_logs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `stu_id` bigint UNSIGNED DEFAULT NULL,
  `mobile` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `attendance_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_ip_date_once` (`ip_address`,`attendance_date`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_recordings`
--

DROP TABLE IF EXISTS `tbl_recordings`;
CREATE TABLE IF NOT EXISTS `tbl_recordings` (
  `rec_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `subject_id` bigint UNSIGNED NOT NULL,
  `topic_id` bigint UNSIGNED NOT NULL,
  `video_Recording` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `max_marks` int UNSIGNED DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`rec_id`),
  KEY `tbl_recordings_subject_id_index` (`subject_id`),
  KEY `tbl_recordings_topic_id_index` (`topic_id`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tbl_recordings`
--

INSERT INTO `tbl_recordings` (`rec_id`, `subject_id`, `topic_id`, `video_Recording`, `max_marks`, `due_date`, `created_at`, `updated_at`) VALUES
(15, 3, 12, '<strong>What is a \"token\" in C?</strong>', 5, '2020-10-10', '2026-04-10 05:16:01', '2026-04-10 05:16:01'),
(16, 1, 13, 'Left panel mein brand features + decorative layout', 5, '2020-10-10', '2026-04-10 07:27:32', '2026-04-10 07:27:32'),
(17, 1, 13, 'Smooth tab switching between Sign In / Create Account', 5, '2020-10-10', '2026-04-10 07:27:32', '2026-04-10 07:27:32'),
(18, 1, 13, 'Toast notification on successful login/register', 5, '2020-10-10', '2026-04-10 07:27:32', '2026-04-10 07:27:32'),
(19, 1, 13, 'Fully responsive — mobile pe left panel upar aata hai', 5, '2020-10-10', '2026-04-10 07:27:32', '2026-04-10 07:27:32'),
(14, 3, 12, '<strong>Why is C called a \"mid-level\" language?</strong>', 5, '2020-10-10', '2026-04-10 05:16:01', '2026-04-10 05:16:01'),
(20, 1, 13, 'Poppins font throughout', 5, '2020-10-10', '2026-04-10 07:27:32', '2026-04-10 07:27:32');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_register`
--

DROP TABLE IF EXISTS `tbl_register`;
CREATE TABLE IF NOT EXISTS `tbl_register` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `college` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `regno` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `course` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `branch` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `year` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mobile` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gender` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `program` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `photo` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `approval_status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tbl_register_regno_unique` (`regno`),
  UNIQUE KEY `tbl_register_mobile_unique` (`mobile`),
  UNIQUE KEY `tbl_register_email_unique` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tbl_register`
--

INSERT INTO `tbl_register` (`id`, `name`, `college`, `regno`, `course`, `branch`, `year`, `mobile`, `email`, `password`, `gender`, `program`, `location`, `photo`, `approval_status`, `created_at`, `updated_at`) VALUES
(1, 'Aditya pratap Vishwakarma', 'CSJM GOVERNMENT POLYTECHNIC AMBEDKARNAGAR', '07390916220', '3', 'Computer Application', 'First Year', '7390916220', 'aditya@gmail.com', '$2y$12$tiPzlwd00E2kB5KqHqSOfeQ8J9sTLhqbmcdLeufEw4103VLDskgpe', 'male', '16', 'House', 'students/H1mTlx32c7YxYE9cdABFSNQwr9mjmXBjYaR0sqgj.jpg', 'Approved', '2026-04-04 05:57:24', '2026-04-04 05:57:42'),
(2, 'Aditya pratap Vishwakarma', 'CSJM GOVERNMENT POLYTECHNIC AMBEDKARNAGAR', '9390916220', '2', 'Computer Science', 'First Year', '8390916220', 'student@gmail.com', '$2y$12$BC62j5HvFSYGkC9CziWI2ObiHX.GuisDD8Xotx/risUiCkxhILOTa', 'male', '12', 'House', 'students/usp2Yy7M2TGX0dMV0QoCAtiptvebQ1JhlfNEqvM1.png', 'Approved', '2026-04-08 12:57:03', '2026-04-08 12:58:04'),
(3, 'Sanjay Kumar', 'CSJM GOVERNMENT POLYTECHNIC AMBEDKARNAGAR', '8604678897', '2', 'Computer Science', 'Second Year', '7890916220', 'student1@gmail.com', '$2y$12$sDiigz4qbIDR5e1fmr/5UOuEsdcuG4wPkdEmCvvfoxud5ZJnYykfa', 'male', '15', 'Academy', 'students/K3ixdGtWrYciXF5WZljzlsinyxNwpl8NVsSE004L.png', 'Approved', '2026-04-08 14:30:36', '2026-04-10 06:30:50'),
(4, 'Abhishek Maurya', 'CSJM GOVERNMENT POLYTECHNIC AMBEDKARNAGAR', '6790916220', '2', 'Computer Science', 'Second Year', '8790936220', 'abhishek@gmail.com', '$2y$12$c1SoXY.tN27MMhPB2wInd.A4TL9zDt4R5t2pkPKX5ULbGuJU2Hjbi', 'male', '15', 'House', NULL, 'Approved', '2026-04-13 03:50:47', '2026-04-13 03:51:35');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_session`
--

DROP TABLE IF EXISTS `tbl_session`;
CREATE TABLE IF NOT EXISTS `tbl_session` (
  `session_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `session_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('old','current','upcomming') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'upcomming',
  `created_by` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_by` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`session_id`),
  UNIQUE KEY `tbl_session_session_name_unique` (`session_name`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tbl_session`
--

INSERT INTO `tbl_session` (`session_id`, `session_name`, `status`, `created_by`, `updated_by`) VALUES
(1, '2026-27', 'current', 'admin', 'admin'),
(2, '2025-26', 'old', 'admin', 'admin'),
(3, '2027-28', 'upcomming', 'admin', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_student`
--

DROP TABLE IF EXISTS `tbl_student`;
CREATE TABLE IF NOT EXISTS `tbl_student` (
  `stu_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `stu_role` bigint UNSIGNED NOT NULL DEFAULT '2',
  `stu_name` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `stu_regid` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `stu_college` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `center_id` bigint UNSIGNED DEFAULT NULL,
  `stu_course` bigint UNSIGNED NOT NULL,
  `stu_course_year` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `stu_branch` bigint UNSIGNED NOT NULL,
  `stu_mobile` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `stu_email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `stu_password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `stu_gender` enum('Male','Female','Other') COLLATE utf8mb4_unicode_ci NOT NULL,
  `stu_pic` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `stu_pro` bigint UNSIGNED DEFAULT NULL,
  `stu_session` bigint UNSIGNED DEFAULT NULL,
  `del_status` enum('Y','N') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'N',
  `is_block` enum('Y','N') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'N',
  `created_by` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_by` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`stu_id`),
  UNIQUE KEY `tbl_student_stu_regid_unique` (`stu_regid`),
  UNIQUE KEY `tbl_student_stu_mobile_unique` (`stu_mobile`),
  UNIQUE KEY `tbl_student_stu_email_unique` (`stu_email`),
  KEY `tbl_student_stu_course_index` (`stu_course`),
  KEY `tbl_student_stu_branch_index` (`stu_branch`),
  KEY `tbl_student_stu_pro_index` (`stu_pro`),
  KEY `tbl_student_stu_session_index` (`stu_session`),
  KEY `tbl_student_del_status_index` (`del_status`),
  KEY `tbl_student_is_block_index` (`is_block`)
) ENGINE=MyISAM AUTO_INCREMENT=61 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tbl_student`
--

INSERT INTO `tbl_student` (`stu_id`, `stu_role`, `stu_name`, `stu_regid`, `stu_college`, `center_id`, `stu_course`, `stu_course_year`, `stu_branch`, `stu_mobile`, `stu_email`, `stu_password`, `stu_gender`, `stu_pic`, `stu_pro`, `stu_session`, `del_status`, `is_block`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(9, 4, 'Aditya pratap Vishwakarma', 'aditya-7390', 'CSJM Government Polytechnic Ambedkarnagar', 1, 1, '2nd Year', 3, '7390916222', 'adityapratap05072005@gmail.com', '$2y$12$q19SHtpBLq2G4o3UhML9s.hiJ2tLV0o7KjbjSJbn7ghfp/nTMBa52', 'Male', '1775458106_Screenshot_2025-12-01_000955.png', 1, 1, 'N', 'N', 'Admin', 'Admin', '2026-04-06 01:18:26', '2026-04-08 01:18:40'),
(10, 4, 'Priya Sharma', 'aditya-1004', 'Government Polytechnic Ayodhya', 1, 2, '2nd Year', 1, '8800000004', 'priya4@example.com', '$2y$12$2AQlMigt6hUEJpPrtI/ouOg6QxiiK/cNM8B6gL0q0N9lC.S8pDfkW', 'Female', NULL, NULL, NULL, 'N', 'N', 'Admin', 'Admin', '2026-04-06 01:26:52', '2026-04-08 02:13:17'),
(11, 4, 'Sneha Verma', 'aditya-1005', 'Government Polytechnic Prayagraj', 1, 2, '2nd Year', 1, '8800000005', 'sneha5@example.com', '$2y$12$2AQlMigt6hUEJpPrtI/ouOg6QxiiK/cNM8B6gL0q0N9lC.S8pDfkW', 'Female', NULL, NULL, NULL, 'N', 'N', 'Admin', 'Admin', '2026-04-06 01:26:52', '2026-04-07 23:32:19'),
(12, 4, 'Ankit Mishra', 'aditya-1006', 'CSJM Government Polytechnic Ambedkarnagar', 1, 2, '2nd Year', 1, '8800000006', 'ankit6@example.com', '$2y$12$2AQlMigt6hUEJpPrtI/ouOg6QxiiK/cNM8B6gL0q0N9lC.S8pDfkW', 'Male', NULL, NULL, NULL, 'N', 'N', 'Admin', 'Admin', '2026-04-06 01:26:52', '2026-04-08 01:18:38'),
(13, 4, 'Neha Gupta', 'aditya-1007', 'Government Polytechnic Lucknow', 1, 2, '2nd Year', 1, '8800000007', 'neha7@example.com', '$2y$12$2AQlMigt6hUEJpPrtI/ouOg6QxiiK/cNM8B6gL0q0N9lC.S8pDfkW', 'Female', NULL, NULL, NULL, 'N', 'N', 'Admin', 'Admin', '2026-04-06 01:26:52', '2026-04-08 01:18:35'),
(14, 4, 'Vikas Patel', 'aditya-1008', 'Government Polytechnic Kanpur', 1, 2, '2nd Year', 1, '8800000008', 'vikas8@example.com', '$2y$12$2AQlMigt6hUEJpPrtI/ouOg6QxiiK/cNM8B6gL0q0N9lC.S8pDfkW', 'Male', NULL, NULL, NULL, 'N', 'N', 'Admin', 'Admin', '2026-04-06 01:26:52', '2026-04-06 02:27:07'),
(15, 4, 'Pooja Kumari', 'aditya-1009', 'Government Polytechnic Ayodhya', 1, 2, '2nd Year', 1, '8800000009', 'pooja9@example.com', '$2y$12$2AQlMigt6hUEJpPrtI/ouOg6QxiiK/cNM8B6gL0q0N9lC.S8pDfkW', 'Female', NULL, NULL, NULL, 'N', 'N', 'Admin', 'Admin', '2026-04-06 01:26:52', '2026-04-06 02:27:07'),
(16, 4, 'Shivam Tiwari', 'aditya-1010', 'Government Polytechnic Prayagraj', 1, 2, '2nd Year', 1, '8800000010', 'shivam10@example.com', '$2y$12$2AQlMigt6hUEJpPrtI/ouOg6QxiiK/cNM8B6gL0q0N9lC.S8pDfkW', 'Male', NULL, NULL, NULL, 'N', 'N', 'Admin', 'Admin', '2026-04-06 01:26:52', '2026-04-06 02:27:07'),
(17, 4, 'Ritika Singh', 'aditya-1011', 'CSJM Government Polytechnic Ambedkarnagar', 1, 2, '2nd Year', 1, '8800000011', 'ritika11@example.com', '$2y$12$2AQlMigt6hUEJpPrtI/ouOg6QxiiK/cNM8B6gL0q0N9lC.S8pDfkW', 'Female', NULL, NULL, NULL, 'N', 'N', 'Admin', 'Admin', '2026-04-06 01:26:52', '2026-04-06 02:27:07'),
(18, 4, 'Ayush Srivastava', 'aditya-1012', 'Government Polytechnic Lucknow', 1, 2, '2nd Year', 1, '8800000012', 'ayush12@example.com', '$2y$12$2AQlMigt6hUEJpPrtI/ouOg6QxiiK/cNM8B6gL0q0N9lC.S8pDfkW', 'Male', NULL, NULL, NULL, 'N', 'N', 'Admin', 'Admin', '2026-04-06 01:26:52', '2026-04-06 02:27:07'),
(19, 4, 'Kajal Maurya', 'aditya-1013', 'Government Polytechnic Kanpur', 1, 2, '2nd Year', 1, '8800000013', 'kajal13@example.com', '$2y$12$2AQlMigt6hUEJpPrtI/ouOg6QxiiK/cNM8B6gL0q0N9lC.S8pDfkW', 'Female', NULL, NULL, NULL, 'N', 'N', 'Admin', 'Admin', '2026-04-06 01:26:52', '2026-04-06 02:27:07'),
(20, 4, 'Saurabh Pandey', 'aditya-1014', 'Government Polytechnic Ayodhya', 1, 2, '2nd Year', 1, '8800000014', 'saurabh14@example.com', '$2y$12$2AQlMigt6hUEJpPrtI/ouOg6QxiiK/cNM8B6gL0q0N9lC.S8pDfkW', 'Male', NULL, NULL, NULL, 'N', 'N', 'Admin', 'Admin', '2026-04-06 01:26:52', '2026-04-06 02:27:07'),
(21, 4, 'Nisha Yadav', 'aditya-1015', 'Government Polytechnic Prayagraj', 1, 2, '2nd Year', 1, '8800000015', 'nisha15@example.com', '$2y$12$2AQlMigt6hUEJpPrtI/ouOg6QxiiK/cNM8B6gL0q0N9lC.S8pDfkW', 'Female', NULL, NULL, NULL, 'N', 'N', 'Admin', 'Admin', '2026-04-06 01:26:52', '2026-04-06 02:27:07'),
(22, 4, 'Deepak Kumar', 'aditya-1016', 'CSJM Government Polytechnic Ambedkarnagar', 1, 2, '2nd Year', 1, '8800000016', 'deepak16@example.com', '$2y$12$2AQlMigt6hUEJpPrtI/ouOg6QxiiK/cNM8B6gL0q0N9lC.S8pDfkW', 'Male', NULL, NULL, NULL, 'N', 'N', 'Admin', 'Admin', '2026-04-06 01:26:52', '2026-04-06 02:27:07'),
(23, 4, 'Anjali Gupta', 'aditya-1017', 'Government Polytechnic Lucknow', 1, 2, '2nd Year', 1, '8800000017', 'anjali17@example.com', '$2y$12$2AQlMigt6hUEJpPrtI/ouOg6QxiiK/cNM8B6gL0q0N9lC.S8pDfkW', 'Female', NULL, NULL, NULL, 'N', 'N', 'Admin', 'Admin', '2026-04-06 01:26:52', '2026-04-06 02:27:07'),
(24, 4, 'Abhishek Singh', 'aditya-1018', 'Government Polytechnic Kanpur', 1, 2, '2nd Year', 1, '8800000018', 'abhishek18@example.com', '$2y$12$2AQlMigt6hUEJpPrtI/ouOg6QxiiK/cNM8B6gL0q0N9lC.S8pDfkW', 'Male', NULL, NULL, NULL, 'N', 'N', 'Admin', 'Admin', '2026-04-06 01:26:52', '2026-04-06 02:27:07'),
(25, 4, 'Komal Verma', 'aditya-1019', 'Government Polytechnic Ayodhya', 1, 2, '2nd Year', 1, '8800000019', 'komal19@example.com', '$2y$12$2AQlMigt6hUEJpPrtI/ouOg6QxiiK/cNM8B6gL0q0N9lC.S8pDfkW', 'Female', NULL, NULL, NULL, 'N', 'N', 'Admin', 'Admin', '2026-04-06 01:26:52', '2026-04-06 02:27:07'),
(26, 4, 'Harshita Mishra', 'aditya-1020', 'Government Polytechnic Prayagraj', 1, 2, '2nd Year', 1, '8800000020', 'harshita20@example.com', '$2y$12$2AQlMigt6hUEJpPrtI/ouOg6QxiiK/cNM8B6gL0q0N9lC.S8pDfkW', 'Female', NULL, NULL, NULL, 'N', 'N', 'Admin', 'Admin', '2026-04-06 01:26:52', '2026-04-06 02:27:07'),
(27, 4, 'Rahul Patel', 'aditya-1021', 'CSJM Government Polytechnic Ambedkarnagar', 1, 2, '2nd Year', 1, '8800000021', 'rahul21@example.com', '$2y$12$2AQlMigt6hUEJpPrtI/ouOg6QxiiK/cNM8B6gL0q0N9lC.S8pDfkW', 'Male', NULL, NULL, NULL, 'N', 'N', 'Admin', 'Admin', '2026-04-06 01:26:52', '2026-04-06 02:27:07'),
(28, 4, 'Swati Sharma', 'aditya-1022', 'Government Polytechnic Lucknow', 1, 2, '2nd Year', 1, '8800000022', 'swati22@example.com', '$2y$12$2AQlMigt6hUEJpPrtI/ouOg6QxiiK/cNM8B6gL0q0N9lC.S8pDfkW', 'Female', NULL, NULL, NULL, 'N', 'N', 'Admin', 'Admin', '2026-04-06 01:26:52', '2026-04-06 02:27:07'),
(29, 4, 'Manish Yadav', 'aditya-1023', 'Government Polytechnic Kanpur', 1, 2, '2nd Year', 1, '8800000023', 'manish23@example.com', '$2y$12$2AQlMigt6hUEJpPrtI/ouOg6QxiiK/cNM8B6gL0q0N9lC.S8pDfkW', 'Male', NULL, NULL, NULL, 'N', 'N', 'Admin', 'Admin', '2026-04-06 01:26:52', '2026-04-06 02:27:07'),
(30, 4, 'Aarti Singh', 'aditya-1024', 'Government Polytechnic Ayodhya', 1, 2, '2nd Year', 1, '8800000024', 'aarti24@example.com', '$2y$12$2AQlMigt6hUEJpPrtI/ouOg6QxiiK/cNM8B6gL0q0N9lC.S8pDfkW', 'Female', NULL, NULL, NULL, 'N', 'N', 'Admin', 'Admin', '2026-04-06 01:26:52', '2026-04-06 02:27:07'),
(31, 4, 'Gaurav Tiwari', 'aditya-1025', 'Government Polytechnic Prayagraj', 1, 2, '2nd Year', 1, '8800000025', 'gaurav25@example.com', '$2y$12$2AQlMigt6hUEJpPrtI/ouOg6QxiiK/cNM8B6gL0q0N9lC.S8pDfkW', 'Male', NULL, NULL, NULL, 'N', 'N', 'Admin', 'Admin', '2026-04-06 01:26:52', '2026-04-06 02:27:07'),
(32, 4, 'Muskan Gupta', 'aditya-1026', 'CSJM Government Polytechnic Ambedkarnagar', 1, 2, '2nd Year', 1, '8800000026', 'muskan26@example.com', '$2y$12$2AQlMigt6hUEJpPrtI/ouOg6QxiiK/cNM8B6gL0q0N9lC.S8pDfkW', 'Female', NULL, NULL, NULL, 'N', 'N', 'Admin', 'Admin', '2026-04-06 01:26:52', '2026-04-06 02:27:07'),
(33, 4, 'Vivek Kumar', 'aditya-1027', 'Government Polytechnic Lucknow', 1, 2, '2nd Year', 1, '8800000027', 'vivek27@example.com', '$2y$12$2AQlMigt6hUEJpPrtI/ouOg6QxiiK/cNM8B6gL0q0N9lC.S8pDfkW', 'Male', NULL, NULL, NULL, 'N', 'N', 'Admin', 'Admin', '2026-04-06 01:26:52', '2026-04-06 02:27:07'),
(34, 4, 'Payal Verma', 'aditya-1028', 'Government Polytechnic Kanpur', 1, 2, '2nd Year', 1, '8800000028', 'payal28@example.com', '$2y$12$2AQlMigt6hUEJpPrtI/ouOg6QxiiK/cNM8B6gL0q0N9lC.S8pDfkW', 'Female', NULL, NULL, NULL, 'N', 'N', 'Admin', 'Admin', '2026-04-06 01:26:52', '2026-04-06 02:27:07'),
(35, 4, 'Arjun Pandey', 'aditya-1029', 'Government Polytechnic Ayodhya', 1, 2, '2nd Year', 1, '8800000029', 'arjun29@example.com', '$2y$12$2AQlMigt6hUEJpPrtI/ouOg6QxiiK/cNM8B6gL0q0N9lC.S8pDfkW', 'Male', NULL, NULL, NULL, 'N', 'N', 'Admin', 'Admin', '2026-04-06 01:26:52', '2026-04-06 02:27:07'),
(36, 4, 'Simran Patel', 'aditya-1030', 'Government Polytechnic Prayagraj', 1, 2, '2nd Year', 1, '8800000030', 'simran30@example.com', '$2y$12$2AQlMigt6hUEJpPrtI/ouOg6QxiiK/cNM8B6gL0q0N9lC.S8pDfkW', 'Female', NULL, NULL, NULL, 'N', 'N', 'Admin', 'Admin', '2026-04-06 01:26:52', '2026-04-06 02:27:07'),
(37, 4, 'Sachin Maurya', 'aditya-1031', 'CSJM Government Polytechnic Ambedkarnagar', 1, 2, '2nd Year', 1, '8800000031', 'sachin31@example.com', '$2y$12$2AQlMigt6hUEJpPrtI/ouOg6QxiiK/cNM8B6gL0q0N9lC.S8pDfkW', 'Male', NULL, NULL, NULL, 'N', 'N', 'Admin', 'Admin', '2026-04-06 01:26:52', '2026-04-06 02:27:07'),
(38, 4, 'Monika Singh', 'aditya-1032', 'Government Polytechnic Lucknow', 1, 2, '2nd Year', 1, '8800000032', 'monika32@example.com', '$2y$12$2AQlMigt6hUEJpPrtI/ouOg6QxiiK/cNM8B6gL0q0N9lC.S8pDfkW', 'Female', NULL, NULL, NULL, 'N', 'N', 'Admin', 'Admin', '2026-04-06 01:26:52', '2026-04-06 02:27:07'),
(39, 4, 'Nitin Sharma', 'aditya-1033', 'Government Polytechnic Kanpur', 1, 2, '2nd Year', 1, '8800000033', 'nitin33@example.com', '$2y$12$2AQlMigt6hUEJpPrtI/ouOg6QxiiK/cNM8B6gL0q0N9lC.S8pDfkW', 'Male', NULL, NULL, NULL, 'N', 'N', 'Admin', 'Admin', '2026-04-06 01:26:52', '2026-04-06 02:27:07'),
(40, 4, 'Riya Yadav', 'aditya-1034', 'Government Polytechnic Ayodhya', 1, 2, '2nd Year', 1, '8800000034', 'riya34@example.com', '$2y$12$2AQlMigt6hUEJpPrtI/ouOg6QxiiK/cNM8B6gL0q0N9lC.S8pDfkW', 'Female', NULL, NULL, NULL, 'N', 'N', 'Admin', 'Admin', '2026-04-06 01:26:52', '2026-04-06 02:27:07'),
(41, 4, 'Prashant Mishra', 'aditya-1035', 'Government Polytechnic Prayagraj', 1, 2, '2nd Year', 1, '8800000035', 'prashant35@example.com', '$2y$12$2AQlMigt6hUEJpPrtI/ouOg6QxiiK/cNM8B6gL0q0N9lC.S8pDfkW', 'Male', NULL, NULL, NULL, 'N', 'N', 'Admin', 'Admin', '2026-04-06 01:26:52', '2026-04-06 02:27:07'),
(42, 4, 'Akash Gupta', 'aditya-1036', 'CSJM Government Polytechnic Ambedkarnagar', 1, 2, '2nd Year', 1, '8800000036', 'akash36@example.com', '$2y$12$2AQlMigt6hUEJpPrtI/ouOg6QxiiK/cNM8B6gL0q0N9lC.S8pDfkW', 'Male', NULL, NULL, NULL, 'N', 'N', 'Admin', 'Admin', '2026-04-06 01:26:52', '2026-04-06 02:27:07'),
(43, 4, 'Shreya Singh', 'aditya-1037', 'Government Polytechnic Lucknow', 1, 2, '2nd Year', 1, '8800000037', 'shreya37@example.com', '$2y$12$2AQlMigt6hUEJpPrtI/ouOg6QxiiK/cNM8B6gL0q0N9lC.S8pDfkW', 'Female', NULL, NULL, NULL, 'N', 'N', 'Admin', 'Admin', '2026-04-06 01:26:52', '2026-04-06 02:27:07'),
(44, 4, 'Mohit Verma', 'aditya-1038', 'Government Polytechnic Kanpur', 1, 2, '2nd Year', 1, '8800000038', 'mohit38@example.com', '$2y$12$2AQlMigt6hUEJpPrtI/ouOg6QxiiK/cNM8B6gL0q0N9lC.S8pDfkW', 'Male', NULL, NULL, NULL, 'N', 'N', 'Admin', 'Admin', '2026-04-06 01:26:52', '2026-04-06 02:27:07'),
(45, 4, 'Tanya Patel', 'aditya-1039', 'Government Polytechnic Ayodhya', 1, 2, '2nd Year', 1, '8800000039', 'tanya39@example.com', '$2y$12$2AQlMigt6hUEJpPrtI/ouOg6QxiiK/cNM8B6gL0q0N9lC.S8pDfkW', 'Female', NULL, NULL, NULL, 'N', 'N', 'Admin', 'Admin', '2026-04-06 01:26:52', '2026-04-06 02:27:07'),
(46, 4, 'Varun Kumar', 'aditya-1040', 'Government Polytechnic Prayagraj', 1, 2, '2nd Year', 1, '8800000040', 'varun40@example.com', '$2y$12$2AQlMigt6hUEJpPrtI/ouOg6QxiiK/cNM8B6gL0q0N9lC.S8pDfkW', 'Male', NULL, NULL, NULL, 'N', 'N', 'Admin', 'Admin', '2026-04-06 01:26:52', '2026-04-06 02:27:07'),
(47, 4, 'Kirti Sharma', 'aditya-1041', 'CSJM Government Polytechnic Ambedkarnagar', 1, 2, '2nd Year', 1, '8800000041', 'kirti41@example.com', '$2y$12$2AQlMigt6hUEJpPrtI/ouOg6QxiiK/cNM8B6gL0q0N9lC.S8pDfkW', 'Female', NULL, NULL, NULL, 'N', 'N', 'Admin', 'Admin', '2026-04-06 01:26:52', '2026-04-06 02:49:38'),
(48, 4, 'Naveen Yadav', 'aditya-1042', 'Government Polytechnic Lucknow', 1, 2, '2nd Year', 1, '8800000042', 'naveen42@example.com', '$2y$12$2AQlMigt6hUEJpPrtI/ouOg6QxiiK/cNM8B6gL0q0N9lC.S8pDfkW', 'Male', NULL, NULL, NULL, 'N', 'N', 'Admin', 'Admin', '2026-04-06 01:26:52', '2026-04-06 02:27:07'),
(49, 4, 'Ishita Singh', 'aditya-1043', 'Government Polytechnic Kanpur', 1, 2, '2nd Year', 1, '8800000043', 'ishita43@example.com', '$2y$12$2AQlMigt6hUEJpPrtI/ouOg6QxiiK/cNM8B6gL0q0N9lC.S8pDfkW', 'Female', NULL, NULL, NULL, 'N', 'N', 'Admin', 'Admin', '2026-04-06 01:26:52', '2026-04-08 02:13:16'),
(50, 4, 'Karan Gupta user', 'aditya-1044', 'Government Polytechnic Ayodhya', 1, 2, '2nd Year', 1, '8800000044', 'karan44@example.com', '$2y$12$2AQlMigt6hUEJpPrtI/ouOg6QxiiK/cNM8B6gL0q0N9lC.S8pDfkW', 'Male', NULL, 1, 1, 'N', 'N', 'Admin', 'Admin', '2026-04-06 01:26:52', '2026-04-08 01:20:52'),
(51, 4, 'Sakshi Pandey', 'aditya-1045', 'Government Polytechnic Prayagraj', 1, 2, '2nd Year', 1, '9506923396', 'spandey5449@gmail.com', '$2y$12$2AQlMigt6hUEJpPrtI/ouOg6QxiiK/cNM8B6gL0q0N9lC.S8pDfkW', 'Female', NULL, 1, 1, 'N', 'N', 'Admin', 'Admin', '2026-04-06 01:26:52', '2026-04-14 01:53:37'),
(52, 4, 'Yash Patel', 'aditya-1046', 'CSJM Government Polytechnic Ambedkarnagar', 1, 2, '2nd Year', 1, '8800000046', 'yash46@example.com', '$2y$12$2AQlMigt6hUEJpPrtI/ouOg6QxiiK/cNM8B6gL0q0N9lC.S8pDfkW', 'Male', NULL, 1, 1, 'N', 'N', 'Admin', 'Admin', '2026-04-06 01:26:52', '2026-04-08 02:21:32'),
(53, 4, 'Divya Mishra', 'aditya-1047', 'Government Polytechnic Lucknow', 1, 2, '2nd Year', 1, '8800000047', 'divya47@example.com', '$2y$12$2AQlMigt6hUEJpPrtI/ouOg6QxiiK/cNM8B6gL0q0N9lC.S8pDfkW', 'Female', NULL, 1, 1, 'N', 'N', 'Admin', 'Admin', '2026-04-06 01:26:52', '2026-04-08 02:13:13'),
(54, 4, 'Ujjwal Kumar', 'aditya-1048', 'Government Polytechnic Kanpur', 3, 2, '2nd Year', 1, '8800000048', 'ujjwal48@example.com', '$2y$12$2AQlMigt6hUEJpPrtI/ouOg6QxiiK/cNM8B6gL0q0N9lC.S8pDfkW', 'Male', NULL, 1, 1, 'N', 'N', 'Admin', 'Admin', '2026-04-06 01:26:52', '2026-04-08 02:32:07'),
(55, 4, 'Megha Singh', 'aditya-1049', 'Government Polytechnic Ayodhya', 3, 2, '2nd Year', 1, '8800000049', 'megha49@example.com', '$2y$12$2AQlMigt6hUEJpPrtI/ouOg6QxiiK/cNM8B6gL0q0N9lC.S8pDfkW', 'Female', '1775466929_Screenshot_2026-01-05_000522.png', 1, 1, 'N', 'N', 'Admin', 'Admin', '2026-04-06 01:26:52', '2026-04-08 02:32:01'),
(56, 4, 'Anurag Tiwari User', 'aditya-1050', 'Government Polytechnic Prayagraj', 3, 2, '2nd Year', 1, '8800000050', 'anurag50@example.com', '$2y$12$2AQlMigt6hUEJpPrtI/ouOg6QxiiK/cNM8B6gL0q0N9lC.S8pDfkW', 'Male', '1775466694_project.png', 1, 1, 'N', 'Y', 'Admin', 'Admin', '2026-04-06 01:26:52', '2026-04-10 06:25:26'),
(57, 4, 'Aditya Kumar', 'aditya-6389', 'CSJM Government Polytechnic Ambedkarnagar', 3, 2, '2nd Year', 1, '6389450032', 'adityakumar@gmail.com', '$2y$12$13vFSJsrlQFsyIxqB1en8Oyq4NzyhhHmsGh5pi3cEcSMEH0253Kja', 'Male', '1775822239_Screenshot_2026-03-25_162618.png', 1, 1, 'N', 'N', 'Admin', 'Admin', '2026-04-10 06:27:19', '2026-04-10 06:27:19'),
(58, 4, 'Abhi Maurya', 'aditya-7420', 'CSJM Government Polytechnic Ambedkarnagar', 3, 2, '3rd Year', 1, '7482482388', 'abhi@gmail.com', '$2y$12$XhO5qqIskA.UCromCKXYNOH2//s9lRqqAu3r0CC2mS.4fHXBanHGq', 'Male', '1776071934_project.png', 1, 1, 'N', 'N', 'Admin', 'Admin', '2026-04-13 03:48:54', '2026-04-14 01:15:53'),
(59, 4, 'Saurabh Chakraverty', 'sau-101', 'CSJM Government Polytechnic Ambedkarnagar', 3, 2, '2nd Year', 1, '6573836438', 'saurabh@gmail.com', '$2y$12$Ya/2vaqAecbc1h20mO6Cu.gBfejZ54eLbxRYXgq7N6EWoDRFvx0DO', 'Male', '1776149558_wallpaper.png', 1, 1, 'N', 'N', 'Admin', 'Admin', '2026-04-14 01:22:38', '2026-04-15 01:34:32'),
(60, 4, 'shubham', 'aditya-7346', 'CSJM Government Polytechnic Ambedkarnagar', 3, 2, '2nd Year', 1, '7397865344', 'shubham@gmail.com', '$2y$12$YbPOgu.ga2V6iK9eexQ./uSq2JcxCYqCu5DLpxoVgM7HJ/C.kPjwy', 'Male', '1776318916_screencapture-handshake-softproindia-in-student-feedback-php-2026-04-15-14_29_19.png', 1, 1, 'N', 'N', 'Admin', 'Admin', '2026-04-16 00:25:16', '2026-04-16 00:25:16');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_student_batch`
--

DROP TABLE IF EXISTS `tbl_student_batch`;
CREATE TABLE IF NOT EXISTS `tbl_student_batch` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `stu_id` bigint UNSIGNED NOT NULL,
  `batch_id` bigint UNSIGNED NOT NULL,
  `is_active` enum('Y','N') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Y',
  `assigned_at` datetime DEFAULT NULL,
  `removed_at` datetime DEFAULT NULL,
  `shift_reason` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_by` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_student_batch` (`stu_id`,`batch_id`)
) ENGINE=MyISAM AUTO_INCREMENT=68 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tbl_student_batch`
--

INSERT INTO `tbl_student_batch` (`id`, `stu_id`, `batch_id`, `is_active`, `assigned_at`, `removed_at`, `shift_reason`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(20, 3, 1, 'Y', NULL, NULL, NULL, 'Admin', 'Admin', '2026-04-04 00:28:01', '2026-04-04 00:28:01'),
(9, 1, 2, 'Y', NULL, NULL, NULL, 'Admin', 'Admin', '2026-04-04 00:12:37', '2026-04-04 00:12:37'),
(19, 3, 3, 'Y', NULL, NULL, NULL, 'Admin', 'Admin', '2026-04-04 00:28:01', '2026-04-04 00:28:01'),
(21, 3, 2, 'Y', NULL, NULL, NULL, 'Admin', 'Admin', '2026-04-04 00:28:01', '2026-04-04 00:28:01'),
(31, 5, 1, 'Y', NULL, NULL, NULL, 'Admin', 'Admin', '2026-04-04 05:29:31', '2026-04-04 05:29:31'),
(32, 5, 2, 'Y', NULL, NULL, NULL, 'Admin', 'Admin', '2026-04-04 05:29:31', '2026-04-04 05:29:31'),
(33, 5, 3, 'Y', NULL, NULL, NULL, 'Admin', 'Admin', '2026-04-04 05:29:31', '2026-04-04 05:29:31'),
(62, 57, 1, 'Y', NULL, NULL, NULL, 'Admin', 'Admin', '2026-04-13 03:33:14', '2026-04-13 03:33:14'),
(38, 6, 3, 'Y', NULL, NULL, NULL, 'Admin', 'Admin', '2026-04-04 06:12:08', '2026-04-04 06:12:08'),
(54, 55, 1, 'Y', NULL, NULL, NULL, 'Admin', 'Admin', '2026-04-08 02:20:47', '2026-04-08 02:20:47'),
(56, 53, 1, 'Y', NULL, NULL, NULL, 'Admin', 'Admin', '2026-04-08 02:21:15', '2026-04-08 02:21:15'),
(60, 50, 2, 'Y', NULL, NULL, NULL, 'Admin', 'Admin', '2026-04-08 02:24:21', '2026-04-08 02:24:21'),
(67, 51, 1, 'Y', NULL, NULL, NULL, 'Admin', 'Admin', '2026-04-16 02:51:30', '2026-04-16 02:51:30'),
(55, 54, 1, 'Y', NULL, NULL, NULL, 'Admin', 'Admin', '2026-04-08 02:20:59', '2026-04-08 02:20:59'),
(57, 52, 1, 'Y', NULL, NULL, NULL, 'Admin', 'Admin', '2026-04-08 02:21:32', '2026-04-08 02:21:32'),
(63, 58, 1, 'Y', NULL, NULL, NULL, 'Admin', 'Admin', '2026-04-14 01:15:53', '2026-04-14 01:15:53'),
(64, 58, 2, 'Y', NULL, NULL, NULL, 'Admin', 'Admin', '2026-04-14 01:15:53', '2026-04-14 01:15:53'),
(65, 59, 1, 'Y', NULL, NULL, NULL, 'Admin', 'Admin', '2026-04-14 05:58:51', '2026-04-14 05:58:51'),
(66, 59, 3, 'Y', NULL, NULL, NULL, 'Admin', 'Admin', '2026-04-14 05:58:51', '2026-04-14 05:58:51');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_student_programme`
--

DROP TABLE IF EXISTS `tbl_student_programme`;
CREATE TABLE IF NOT EXISTS `tbl_student_programme` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `stu_id` bigint UNSIGNED NOT NULL,
  `programme_id` bigint UNSIGNED NOT NULL,
  `created_by` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_by` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_student_programme` (`stu_id`,`programme_id`)
) ENGINE=MyISAM AUTO_INCREMENT=76 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tbl_student_programme`
--

INSERT INTO `tbl_student_programme` (`id`, `stu_id`, `programme_id`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(5, 1, 1, 'Admin', 'Admin', '2026-04-04 00:12:37', '2026-04-04 00:12:37'),
(6, 3, 1, 'Admin', 'Admin', '2026-04-04 00:28:01', '2026-04-04 00:28:01'),
(14, 5, 1, 'Admin', 'Admin', '2026-04-04 05:29:31', '2026-04-04 05:29:31'),
(19, 6, 1, 'Admin', 'Admin', '2026-04-04 06:12:08', '2026-04-04 06:12:08'),
(59, 56, 1, 'Admin', 'Admin', '2026-04-08 02:30:27', '2026-04-08 02:30:27'),
(60, 55, 1, 'Admin', 'Admin', '2026-04-08 02:32:01', '2026-04-08 02:32:01'),
(52, 53, 1, 'Admin', 'Admin', '2026-04-08 02:21:15', '2026-04-08 02:21:15'),
(58, 50, 1, 'Admin', 'Admin', '2026-04-08 02:24:21', '2026-04-08 02:24:21'),
(75, 51, 1, 'Admin', 'Admin', '2026-04-16 02:51:30', '2026-04-16 02:51:30'),
(73, 54, 1, 'Admin', 'Admin', '2026-04-15 08:01:19', '2026-04-15 08:01:19'),
(53, 52, 1, 'Admin', 'Admin', '2026-04-08 02:21:32', '2026-04-08 02:21:32'),
(64, 57, 1, 'Admin', 'Admin', '2026-04-13 03:33:14', '2026-04-13 03:33:14'),
(66, 58, 1, 'Admin', 'Admin', '2026-04-14 01:15:53', '2026-04-14 01:15:53'),
(67, 58, 6, 'Admin', 'Admin', '2026-04-14 01:15:53', '2026-04-14 01:15:53'),
(72, 59, 1, 'Admin', 'Admin', '2026-04-14 05:58:51', '2026-04-14 05:58:51'),
(74, 60, 1, 'Admin', 'Admin', '2026-04-16 00:25:16', '2026-04-16 00:25:16');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_student_session`
--

DROP TABLE IF EXISTS `tbl_student_session`;
CREATE TABLE IF NOT EXISTS `tbl_student_session` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `stu_id` bigint UNSIGNED NOT NULL,
  `session_id` bigint UNSIGNED NOT NULL,
  `created_by` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_by` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_student_session` (`stu_id`,`session_id`)
) ENGINE=MyISAM AUTO_INCREMENT=79 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tbl_student_session`
--

INSERT INTO `tbl_student_session` (`id`, `stu_id`, `session_id`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(14, 1, 2, 'Admin', 'Admin', '2026-04-04 00:12:37', '2026-04-04 00:12:37'),
(13, 1, 3, 'Admin', 'Admin', '2026-04-04 00:12:37', '2026-04-04 00:12:37'),
(12, 1, 1, 'Admin', 'Admin', '2026-04-04 00:12:37', '2026-04-04 00:12:37'),
(21, 3, 1, 'Admin', 'Admin', '2026-04-04 00:28:01', '2026-04-04 00:28:01'),
(22, 3, 3, 'Admin', 'Admin', '2026-04-04 00:28:01', '2026-04-04 00:28:01'),
(23, 3, 2, 'Admin', 'Admin', '2026-04-04 00:28:01', '2026-04-04 00:28:01'),
(39, 5, 1, 'Admin', 'Admin', '2026-04-04 05:29:31', '2026-04-04 05:29:31'),
(44, 6, 1, 'Admin', 'Admin', '2026-04-04 06:12:08', '2026-04-04 06:12:08'),
(68, 56, 1, 'Admin', 'Admin', '2026-04-08 02:23:24', '2026-04-08 02:23:24'),
(61, 55, 1, 'Admin', 'Admin', '2026-04-08 02:20:47', '2026-04-08 02:20:47'),
(63, 53, 1, 'Admin', 'Admin', '2026-04-08 02:21:15', '2026-04-08 02:21:15'),
(69, 50, 1, 'Admin', 'Admin', '2026-04-08 02:24:21', '2026-04-08 02:24:21'),
(78, 51, 1, 'Admin', 'Admin', '2026-04-16 02:51:30', '2026-04-16 02:51:30'),
(62, 54, 1, 'Admin', 'Admin', '2026-04-08 02:20:59', '2026-04-08 02:20:59'),
(64, 52, 1, 'Admin', 'Admin', '2026-04-08 02:21:32', '2026-04-08 02:21:32'),
(72, 57, 1, 'Admin', 'Admin', '2026-04-13 03:33:14', '2026-04-13 03:33:14'),
(74, 58, 1, 'Admin', 'Admin', '2026-04-14 01:15:53', '2026-04-14 01:15:53'),
(76, 59, 1, 'Admin', 'Admin', '2026-04-14 05:58:51', '2026-04-14 05:58:51'),
(77, 60, 1, 'Admin', 'Admin', '2026-04-16 00:25:16', '2026-04-16 00:25:16');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_student_technology`
--

DROP TABLE IF EXISTS `tbl_student_technology`;
CREATE TABLE IF NOT EXISTS `tbl_student_technology` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `stu_id` bigint UNSIGNED NOT NULL,
  `technology_id` bigint UNSIGNED NOT NULL,
  `created_by` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_by` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_student_technology` (`stu_id`,`technology_id`)
) ENGINE=MyISAM AUTO_INCREMENT=71 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tbl_student_technology`
--

INSERT INTO `tbl_student_technology` (`id`, `stu_id`, `technology_id`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(9, 1, 1, 'Admin', 'Admin', '2026-04-04 00:12:37', '2026-04-04 00:12:37'),
(10, 1, 3, 'Admin', 'Admin', '2026-04-04 00:12:37', '2026-04-04 00:12:37'),
(17, 3, 1, 'Admin', 'Admin', '2026-04-04 00:28:01', '2026-04-04 00:28:01'),
(18, 3, 3, 'Admin', 'Admin', '2026-04-04 00:28:01', '2026-04-04 00:28:01'),
(19, 3, 2, 'Admin', 'Admin', '2026-04-04 00:28:01', '2026-04-04 00:28:01'),
(29, 5, 1, 'Admin', 'Admin', '2026-04-04 05:29:31', '2026-04-04 05:29:31'),
(30, 5, 3, 'Admin', 'Admin', '2026-04-04 05:29:31', '2026-04-04 05:29:31'),
(31, 5, 2, 'Admin', 'Admin', '2026-04-04 05:29:31', '2026-04-04 05:29:31'),
(60, 56, 1, 'Admin', 'Admin', '2026-04-08 02:23:24', '2026-04-08 02:23:24'),
(36, 6, 2, 'Admin', 'Admin', '2026-04-04 06:12:08', '2026-04-04 06:12:08'),
(53, 55, 1, 'Admin', 'Admin', '2026-04-08 02:20:47', '2026-04-08 02:20:47'),
(55, 53, 1, 'Admin', 'Admin', '2026-04-08 02:21:15', '2026-04-08 02:21:15'),
(61, 50, 3, 'Admin', 'Admin', '2026-04-08 02:24:21', '2026-04-08 02:24:21'),
(70, 51, 1, 'Admin', 'Admin', '2026-04-16 02:51:30', '2026-04-16 02:51:30'),
(54, 54, 1, 'Admin', 'Admin', '2026-04-08 02:20:59', '2026-04-08 02:20:59'),
(56, 52, 1, 'Admin', 'Admin', '2026-04-08 02:21:32', '2026-04-08 02:21:32'),
(64, 57, 4, 'Admin', 'Admin', '2026-04-13 03:33:14', '2026-04-13 03:33:14'),
(65, 57, 1, 'Admin', 'Admin', '2026-04-13 03:33:14', '2026-04-13 03:33:14'),
(66, 58, 1, 'Admin', 'Admin', '2026-04-14 01:15:53', '2026-04-14 01:15:53'),
(67, 58, 3, 'Admin', 'Admin', '2026-04-14 01:15:53', '2026-04-14 01:15:53'),
(68, 59, 1, 'Admin', 'Admin', '2026-04-14 05:58:51', '2026-04-14 05:58:51'),
(69, 59, 2, 'Admin', 'Admin', '2026-04-14 05:58:51', '2026-04-14 05:58:51');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_subject`
--

DROP TABLE IF EXISTS `tbl_subject`;
CREATE TABLE IF NOT EXISTS `tbl_subject` (
  `subject_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `subject_name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `syllabus` longtext COLLATE utf8mb4_unicode_ci,
  `subject_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_by` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`subject_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tbl_subject`
--

INSERT INTO `tbl_subject` (`subject_id`, `subject_name`, `syllabus`, `subject_image`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 'PHP', '<p>ith the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p><h2>Why do we use it?</h2><p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using \'Content here, content here\', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for \'lorem ipsum\' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).</p><h2>Where does it come from?</h2><p>Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of \"de Finibus Bonorum et Malorum\" (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, \"Lorem ipsum dolor sit amet..\", comes from a line in section 1.10.32.</p><p>The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from \"de Finibus Bonorum et Malorum\" by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.</p><h2>Where can I get some?</h2><p>There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don\'t look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn\'t anything embarrassing hidden in the middle of text. All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator on the Internet. It uses a dictionary of over 200 Latin words, combined with a handful of model sentence structures, to generate Lorem Ipsum which loo</p>', '1775221139_Screenshot_2026-03-19_233655.png', 'admin', 'admin', '2026-04-03 07:28:59', '2026-04-09 23:20:08'),
(2, 'DSA Using C++', '<h2>Introduction</h2><p>The <a href=\"https://www.w3schools.com/dsa/index.php\">W3Schools Data Structures and Algorithms Tutorial</a> is comprehensive and beginner-friendly.</p><p>It will give you a fundamental knowledge of data structures and algorithms.</p><p>This tutorial is designed for beginners and requires only basic programming knowledge.</p><p>The content has been carefully made to be bite-sized, simple, and easy to understand.</p><p>The content has been proven by millions of users over the years. It is updated and improved frequently.</p><p>The syllabus outline and its sequence are structured so you can learn DSA step by step, from basic data structures to advanced algorithms.</p><p>&nbsp;</p><h2>Learning Outcomes</h2><ul><li>Understand basic data structures like arrays, linked lists, trees, and graphs.</li><li>Learn how to sort and search data efficiently.</li><li>Analyze the performance of algorithms using time complexity.</li><li>Improve program efficiency by selecting appropriate data structures.</li><li>Solve real-world problems with effective algorithms.</li></ul><p><strong>Note:</strong> Are you a teacher teaching DSA? <a href=\"https://www.w3schools.com/academy/index.php\">W3Schools Academy</a> is a toolbox of features that can help you teach. It offers classroom features such as pre-built study plans, classroom administration and much more. Read more about <a href=\"https://www.w3schools.com/academy/index.php\">Academy here</a>.</p><h2>Which Subjects Are DSA Relevant For?</h2><ul><li><strong>Software Development:</strong><br>DSA is essential for efficient programming.</li><li><strong>Problem Solving:</strong><br>DSA provides tools for solving complex problems.</li><li><strong>System Design:</strong><br>DSA helps in designing scalable systems.</li><li><strong>Competitive Programming:</strong><br>DSA is crucial for coding competitions.</li><li><strong>Technical Interviews:</strong><br>DSA is a key topic in job interviews.</li><li><strong>Performance Optimization:</strong><br>DSA enables writing efficient code.</li><li><strong>Data Science:</strong><br>DSA helps in handling large datasets efficiently.</li></ul><p>&nbsp;</p><p><br>&nbsp;</p>', '1775712665_project.png', 'admin', 'admin', '2026-04-09 00:01:05', '2026-04-09 00:01:05'),
(3, 'DBMS', '<p>Route::put(\'/upload-content/{id}/topic-title\', [SubjectController::class, \'updateTopicTitle\'])<br>-&gt;name(\'admin.upload-content.topic-title.update\');<br><br>Route::delete(\'/upload-content/{id}\', [SubjectController::class, \'deleteTopicTitle\'])<br>-&gt;name(\'admin.upload-content.delete\');</p>', '1775735287_Screenshot_2025-12-01_000955.png', 'admin', 'admin', '2026-04-09 06:18:07', '2026-04-09 06:18:07');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_subject_mock_tests`
--

DROP TABLE IF EXISTS `tbl_subject_mock_tests`;
CREATE TABLE IF NOT EXISTS `tbl_subject_mock_tests` (
  `subject_mock_test_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `subject_id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `upload_file` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `max_marks` int DEFAULT NULL,
  `mock_test` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint NOT NULL DEFAULT '1',
  `created_by` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_by` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`subject_mock_test_id`),
  KEY `tbl_subject_mock_tests_subject_id_index` (`subject_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tbl_subject_mock_tests`
--

INSERT INTO `tbl_subject_mock_tests` (`subject_mock_test_id`, `subject_id`, `title`, `upload_file`, `max_marks`, `mock_test`, `status`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 1, 'PHP Mock Test', '1775821210_migration.png', 5, '<ol><li>Pure light theme — white + orange (#ff6b35) — koi bhi dark color nahi</li><li>Left panel mein brand features + decorative layout</li><li>Smooth tab switching between Sign In / Create Account</li><li>Toast notification on successful login/registe</li></ol>', 1, 'Admin', 'Admin', '2026-04-10 06:10:10', '2026-04-10 06:17:18'),
(3, 3, 'DBMS Mock Test', '1775821744_migration.png', 5, '<ol><li>Pure light theme — white + orange (#ff6b35) — koi bhi dark color nahi</li><li>Left panel mein brand features + decorative layout</li><li>Smooth tab switching between Sign In / Create Account</li><li>Toast notification on successful login/register</li><li>Fully responsive — mobile pe left panel upar aata hai</li></ol>', 1, 'Admin', 'Admin', '2026-04-10 06:19:04', '2026-04-10 06:19:04');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_technology`
--

DROP TABLE IF EXISTS `tbl_technology`;
CREATE TABLE IF NOT EXISTS `tbl_technology` (
  `technology_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `session_id` bigint UNSIGNED NOT NULL,
  `programme_id` bigint UNSIGNED NOT NULL,
  `technology_name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `technology_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_by` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`technology_id`),
  UNIQUE KEY `uq_programme_technology_name` (`programme_id`,`technology_name`),
  KEY `tbl_technology_session_id_foreign` (`session_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tbl_technology`
--

INSERT INTO `tbl_technology` (`technology_id`, `session_id`, `programme_id`, `technology_name`, `technology_image`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'Java Technology', '1775220897_project.png', 'admin', 'admin', '2026-04-03 07:24:57', '2026-04-03 07:24:57'),
(2, 1, 1, 'Pyhton Technology', '1775220918_Screenshot_2025-12-01_000955.png', 'admin', 'admin', '2026-04-03 07:25:18', '2026-04-03 07:25:18'),
(3, 1, 1, 'PHP Technology', '1775220936_migration.png', 'admin', 'admin', '2026-04-03 07:25:36', '2026-04-03 07:25:36'),
(4, 1, 1, 'ASP.netTechnology', '1775635832_migration.png', 'admin', 'admin', '2026-04-08 02:40:32', '2026-04-08 02:40:32');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_topic`
--

DROP TABLE IF EXISTS `tbl_topic`;
CREATE TABLE IF NOT EXISTS `tbl_topic` (
  `topic_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `subject_id` bigint UNSIGNED NOT NULL,
  `topic_name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint NOT NULL DEFAULT '1',
  `created_by` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_by` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`topic_id`),
  UNIQUE KEY `uq_subject_topic` (`subject_id`,`topic_name`),
  KEY `idx_topic_subject_id` (`subject_id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tbl_topic`
--

INSERT INTO `tbl_topic` (`topic_id`, `subject_id`, `topic_name`, `status`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(13, 1, 'Day 1', 1, 'Admin', 'Admin', '2026-04-10 07:26:07', '2026-04-10 07:26:07');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_upload_content`
--

DROP TABLE IF EXISTS `tbl_upload_content`;
CREATE TABLE IF NOT EXISTS `tbl_upload_content` (
  `content_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `subject_id` bigint UNSIGNED NOT NULL,
  `topic_id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `learning_objective` longtext COLLATE utf8mb4_unicode_ci,
  `content_body` longtext COLLATE utf8mb4_unicode_ci,
  `resource_file` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `video_link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `trainer_task` longtext COLLATE utf8mb4_unicode_ci,
  `learning_outcomes` longtext COLLATE utf8mb4_unicode_ci,
  `created_by` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_by` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`content_id`),
  UNIQUE KEY `uq_upload_content_topic_title_key` (`topic_id`),
  KEY `idx_upload_content_subject_id` (`subject_id`),
  KEY `idx_upload_content_topic_id` (`topic_id`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tbl_upload_content`
--

INSERT INTO `tbl_upload_content` (`content_id`, `subject_id`, `topic_id`, `title`, `learning_objective`, `content_body`, `resource_file`, `video_link`, `trainer_task`, `learning_outcomes`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(20, 1, 13, 'PHP Day 1', '<ul><li>Left panel mein brand features + decorative layout</li><li>Smooth tab switching between Sign In / Create Account</li><li>Toast notification on successful login/register</li><li>Fully responsive — mobile pe left panel upar aata hai</li><li>Poppins font throughout</li></ul><p>&nbsp;</p><p><br>&nbsp;</p>', '<ul><li>Left panel mein brand features + decorative layout</li><li>Smooth tab switching between Sign In / Create Account</li><li>Toast notification on successful login/register</li><li>Fully responsive — mobile pe left panel upar aata hai</li><li>Poppins font throughout</li></ul><p>&nbsp;</p><p><br>&nbsp;</p>', '1775825852_d7XGwRyG_project.png', 'https://www.github.com/', '<ul><li>Left panel mein brand features + decorative layout</li><li>Smooth tab switching between Sign In / Create Account</li><li>Toast notification on successful login/register</li><li>Fully responsive — mobile pe left panel upar aata hai</li><li>Poppins font throughout</li></ul><p>&nbsp;</p><p><br>&nbsp;</p>', '<ul><li>Left panel mein brand features + decorative layout</li><li>Smooth tab switching between Sign In / Create Account</li><li>Toast notification on successful login/register</li><li>Fully responsive — mobile pe left panel upar aata hai</li><li>Poppins font throughout</li></ul><p>&nbsp;</p><p><br>&nbsp;</p>', 'Admin', 'Admin', '2026-04-10 07:27:32', '2026-04-10 07:27:32');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'student',
  `approval_status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Approved',
  `is_block` enum('Y','N') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'N',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `college` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `regno` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `course` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `branch` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `year` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gender` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `program` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `users_regno_unique` (`regno`)
) ENGINE=MyISAM AUTO_INCREMENT=73 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `phone`, `role`, `is_block`, `email_verified_at`, `password`, `image`, `remember_token`, `created_at`, `updated_at`) VALUES
(12, 'Aman Kumar Yadav', 'aman2@example.com', '8800000002', 'student', 'N', '2026-04-06 01:23:07', '$2y$12$hPl34PPymrlOurBaF1EmCeBqIFmHUUOQpCsIA5Lo9bEU0yXgBjFKi', NULL, NULL, '2026-04-06 01:23:07', '2026-04-06 01:23:07'),
(10, 'Aditya pratap Vishwakarma', 'adityapratap05072005@gmail.com', '7390916222', 'student', 'N', '2026-04-06 01:18:26', '$2y$12$q19SHtpBLq2G4o3UhML9s.hiJ2tLV0o7KjbjSJbn7ghfp/nTMBa52', '1775458106_Screenshot_2025-12-01_000955.png', NULL, '2026-04-06 01:18:26', '2026-04-06 08:39:39'),
(11, 'Aditya Pratap Vishwakarma', 'aditya1@example.com', '8800000001', 'student', 'N', '2026-04-06 01:21:54', '$2y$12$tQCqqkSvJXCFsFgT5JqNL.2VY37Jechq.pUonG1FdhAoOSgf7I3NG', NULL, NULL, '2026-04-06 01:21:54', '2026-04-06 01:21:54'),
(6, 'admin', 'admin@gmail.com', '7390916220', 'admin', 'N', '0000-00-00 00:00:00', '$2y$12$zv8ICBQX.LUvHF3k2sWolO9PklBV83ZolkhXXoI8TGqHDo7x9iYm.', '1776161720_WhatsApp_Image_2026-01-21_at_8.52.55_AM.jpeg', 'AFtpJU0zGtPIvdoKHKah4Uq4OgV0lli0dlX2bIJeMFLPMDY2SnzyKerTqI4f', '0000-00-00 00:00:00', '2026-04-14 04:45:36'),
(13, 'Rohit Singh', 'rohit3@example.com', '8800000003', 'student', 'N', '2026-04-06 01:23:56', '$2y$12$5AX10oZD/n2rgHLu2dSMVOSELs1lVx3/LrLAStN33x22x189i8e1y', NULL, NULL, '2026-04-06 01:23:56', '2026-04-06 01:23:56'),
(14, 'Priya Sharma', 'priya4@example.com', '8800000004', 'student', 'Y', '2026-04-06 01:26:52', '$2y$12$2AQlMigt6hUEJpPrtI/ouOg6QxiiK/cNM8B6gL0q0N9lC.S8pDfkW', NULL, NULL, '2026-04-06 01:26:52', '2026-04-07 16:15:45'),
(15, 'Sneha Verma', 'sneha5@example.com', '8800000005', 'student', 'Y', '2026-04-06 01:26:52', '$2y$12$2AQlMigt6hUEJpPrtI/ouOg6QxiiK/cNM8B6gL0q0N9lC.S8pDfkW', NULL, NULL, '2026-04-06 01:26:52', '2026-04-07 16:15:48'),
(16, 'Ankit Mishra', 'ankit6@example.com', '8800000006', 'student', 'N', '2026-04-06 01:26:52', '$2y$12$2AQlMigt6hUEJpPrtI/ouOg6QxiiK/cNM8B6gL0q0N9lC.S8pDfkW', NULL, NULL, '2026-04-06 01:26:52', '2026-04-06 01:26:52'),
(17, 'Neha Gupta', 'neha7@example.com', '8800000007', 'student', 'N', '2026-04-06 01:26:52', '$2y$12$2AQlMigt6hUEJpPrtI/ouOg6QxiiK/cNM8B6gL0q0N9lC.S8pDfkW', NULL, NULL, '2026-04-06 01:26:52', '2026-04-06 01:26:52'),
(18, 'Vikas Patel', 'vikas8@example.com', '8800000008', 'student', 'N', '2026-04-06 01:26:52', '$2y$12$2AQlMigt6hUEJpPrtI/ouOg6QxiiK/cNM8B6gL0q0N9lC.S8pDfkW', NULL, NULL, '2026-04-06 01:26:52', '2026-04-06 01:26:52'),
(19, 'Pooja Kumari', 'pooja9@example.com', '8800000009', 'student', 'N', '2026-04-06 01:26:52', '$2y$12$2AQlMigt6hUEJpPrtI/ouOg6QxiiK/cNM8B6gL0q0N9lC.S8pDfkW', NULL, NULL, '2026-04-06 01:26:52', '2026-04-06 01:26:52'),
(20, 'Shivam Tiwari', 'shivam10@example.com', '8800000010', 'student', 'N', '2026-04-06 01:26:52', '$2y$12$2AQlMigt6hUEJpPrtI/ouOg6QxiiK/cNM8B6gL0q0N9lC.S8pDfkW', NULL, NULL, '2026-04-06 01:26:52', '2026-04-06 01:26:52'),
(21, 'Ritika Singh', 'ritika11@example.com', '8800000011', 'student', 'N', '2026-04-06 01:26:52', '$2y$12$2AQlMigt6hUEJpPrtI/ouOg6QxiiK/cNM8B6gL0q0N9lC.S8pDfkW', NULL, NULL, '2026-04-06 01:26:52', '2026-04-06 01:26:52'),
(22, 'Ayush Srivastava', 'ayush12@example.com', '8800000012', 'student', 'N', '2026-04-06 01:26:52', '$2y$12$2AQlMigt6hUEJpPrtI/ouOg6QxiiK/cNM8B6gL0q0N9lC.S8pDfkW', NULL, NULL, '2026-04-06 01:26:52', '2026-04-06 01:26:52'),
(23, 'Kajal Maurya', 'kajal13@example.com', '8800000013', 'student', 'N', '2026-04-06 01:26:52', '$2y$12$2AQlMigt6hUEJpPrtI/ouOg6QxiiK/cNM8B6gL0q0N9lC.S8pDfkW', NULL, NULL, '2026-04-06 01:26:52', '2026-04-06 01:26:52'),
(24, 'Saurabh Pandey', 'saurabh14@example.com', '8800000014', 'student', 'N', '2026-04-06 01:26:52', '$2y$12$2AQlMigt6hUEJpPrtI/ouOg6QxiiK/cNM8B6gL0q0N9lC.S8pDfkW', NULL, NULL, '2026-04-06 01:26:52', '2026-04-06 01:26:52'),
(25, 'Nisha Yadav', 'nisha15@example.com', '8800000015', 'student', 'N', '2026-04-06 01:26:52', '$2y$12$2AQlMigt6hUEJpPrtI/ouOg6QxiiK/cNM8B6gL0q0N9lC.S8pDfkW', NULL, NULL, '2026-04-06 01:26:52', '2026-04-06 01:26:52'),
(26, 'Deepak Kumar', 'deepak16@example.com', '8800000016', 'student', 'N', '2026-04-06 01:26:52', '$2y$12$2AQlMigt6hUEJpPrtI/ouOg6QxiiK/cNM8B6gL0q0N9lC.S8pDfkW', NULL, NULL, '2026-04-06 01:26:52', '2026-04-06 01:26:52'),
(27, 'Anjali Gupta', 'anjali17@example.com', '8800000017', 'student', 'N', '2026-04-06 01:26:52', '$2y$12$2AQlMigt6hUEJpPrtI/ouOg6QxiiK/cNM8B6gL0q0N9lC.S8pDfkW', NULL, NULL, '2026-04-06 01:26:52', '2026-04-06 01:26:52'),
(28, 'Abhishek Singh', 'abhishek18@example.com', '8800000018', 'student', 'N', '2026-04-06 01:26:52', '$2y$12$2AQlMigt6hUEJpPrtI/ouOg6QxiiK/cNM8B6gL0q0N9lC.S8pDfkW', NULL, NULL, '2026-04-06 01:26:52', '2026-04-06 01:26:52'),
(29, 'Komal Verma', 'komal19@example.com', '8800000019', 'student', 'N', '2026-04-06 01:26:52', '$2y$12$2AQlMigt6hUEJpPrtI/ouOg6QxiiK/cNM8B6gL0q0N9lC.S8pDfkW', NULL, NULL, '2026-04-06 01:26:52', '2026-04-06 01:26:52'),
(30, 'Harshita Mishra', 'harshita20@example.com', '8800000020', 'student', 'N', '2026-04-06 01:26:52', '$2y$12$2AQlMigt6hUEJpPrtI/ouOg6QxiiK/cNM8B6gL0q0N9lC.S8pDfkW', NULL, NULL, '2026-04-06 01:26:52', '2026-04-06 01:26:52'),
(31, 'Rahul Patel', 'rahul21@example.com', '8800000021', 'student', 'N', '2026-04-06 01:26:52', '$2y$12$2AQlMigt6hUEJpPrtI/ouOg6QxiiK/cNM8B6gL0q0N9lC.S8pDfkW', NULL, NULL, '2026-04-06 01:26:52', '2026-04-06 01:26:52'),
(32, 'Swati Sharma', 'swati22@example.com', '8800000022', 'student', 'N', '2026-04-06 01:26:52', '$2y$12$2AQlMigt6hUEJpPrtI/ouOg6QxiiK/cNM8B6gL0q0N9lC.S8pDfkW', NULL, NULL, '2026-04-06 01:26:52', '2026-04-06 01:26:52'),
(33, 'Manish Yadav', 'manish23@example.com', '8800000023', 'student', 'N', '2026-04-06 01:26:52', '$2y$12$2AQlMigt6hUEJpPrtI/ouOg6QxiiK/cNM8B6gL0q0N9lC.S8pDfkW', NULL, NULL, '2026-04-06 01:26:52', '2026-04-06 01:26:52'),
(34, 'Aarti Singh', 'aarti24@example.com', '8800000024', 'student', 'N', '2026-04-06 01:26:52', '$2y$12$2AQlMigt6hUEJpPrtI/ouOg6QxiiK/cNM8B6gL0q0N9lC.S8pDfkW', NULL, NULL, '2026-04-06 01:26:52', '2026-04-06 01:26:52'),
(35, 'Gaurav Tiwari', 'gaurav25@example.com', '8800000025', 'student', 'N', '2026-04-06 01:26:52', '$2y$12$2AQlMigt6hUEJpPrtI/ouOg6QxiiK/cNM8B6gL0q0N9lC.S8pDfkW', NULL, NULL, '2026-04-06 01:26:52', '2026-04-06 01:26:52'),
(36, 'Muskan Gupta', 'muskan26@example.com', '8800000026', 'student', 'N', '2026-04-06 01:26:52', '$2y$12$2AQlMigt6hUEJpPrtI/ouOg6QxiiK/cNM8B6gL0q0N9lC.S8pDfkW', NULL, NULL, '2026-04-06 01:26:52', '2026-04-06 01:26:52'),
(37, 'Vivek Kumar', 'vivek27@example.com', '8800000027', 'student', 'N', '2026-04-06 01:26:52', '$2y$12$2AQlMigt6hUEJpPrtI/ouOg6QxiiK/cNM8B6gL0q0N9lC.S8pDfkW', NULL, NULL, '2026-04-06 01:26:52', '2026-04-06 01:26:52'),
(38, 'Payal Verma', 'payal28@example.com', '8800000028', 'student', 'N', '2026-04-06 01:26:52', '$2y$12$2AQlMigt6hUEJpPrtI/ouOg6QxiiK/cNM8B6gL0q0N9lC.S8pDfkW', NULL, NULL, '2026-04-06 01:26:52', '2026-04-06 01:26:52'),
(39, 'Arjun Pandey', 'arjun29@example.com', '8800000029', 'student', 'N', '2026-04-06 01:26:52', '$2y$12$2AQlMigt6hUEJpPrtI/ouOg6QxiiK/cNM8B6gL0q0N9lC.S8pDfkW', NULL, NULL, '2026-04-06 01:26:52', '2026-04-06 01:26:52'),
(40, 'Simran Patel', 'simran30@example.com', '8800000030', 'student', 'N', '2026-04-06 01:26:52', '$2y$12$2AQlMigt6hUEJpPrtI/ouOg6QxiiK/cNM8B6gL0q0N9lC.S8pDfkW', NULL, NULL, '2026-04-06 01:26:52', '2026-04-06 01:26:52'),
(41, 'Sachin Maurya', 'sachin31@example.com', '8800000031', 'student', 'N', '2026-04-06 01:26:52', '$2y$12$2AQlMigt6hUEJpPrtI/ouOg6QxiiK/cNM8B6gL0q0N9lC.S8pDfkW', NULL, NULL, '2026-04-06 01:26:52', '2026-04-06 01:26:52'),
(42, 'Monika Singh', 'monika32@example.com', '8800000032', 'student', 'N', '2026-04-06 01:26:52', '$2y$12$2AQlMigt6hUEJpPrtI/ouOg6QxiiK/cNM8B6gL0q0N9lC.S8pDfkW', NULL, NULL, '2026-04-06 01:26:52', '2026-04-06 01:26:52'),
(43, 'Nitin Sharma', 'nitin33@example.com', '8800000033', 'student', 'N', '2026-04-06 01:26:52', '$2y$12$2AQlMigt6hUEJpPrtI/ouOg6QxiiK/cNM8B6gL0q0N9lC.S8pDfkW', NULL, NULL, '2026-04-06 01:26:52', '2026-04-06 01:26:52'),
(44, 'Riya Yadav', 'riya34@example.com', '8800000034', 'student', 'N', '2026-04-06 01:26:52', '$2y$12$2AQlMigt6hUEJpPrtI/ouOg6QxiiK/cNM8B6gL0q0N9lC.S8pDfkW', NULL, NULL, '2026-04-06 01:26:52', '2026-04-06 01:26:52'),
(45, 'Prashant Mishra', 'prashant35@example.com', '8800000035', 'student', 'N', '2026-04-06 01:26:52', '$2y$12$2AQlMigt6hUEJpPrtI/ouOg6QxiiK/cNM8B6gL0q0N9lC.S8pDfkW', NULL, NULL, '2026-04-06 01:26:52', '2026-04-06 01:26:52'),
(46, 'Akash Gupta', 'akash36@example.com', '8800000036', 'student', 'N', '2026-04-06 01:26:52', '$2y$12$2AQlMigt6hUEJpPrtI/ouOg6QxiiK/cNM8B6gL0q0N9lC.S8pDfkW', NULL, NULL, '2026-04-06 01:26:52', '2026-04-06 01:26:52'),
(47, 'Shreya Singh', 'shreya37@example.com', '8800000037', 'student', 'N', '2026-04-06 01:26:52', '$2y$12$2AQlMigt6hUEJpPrtI/ouOg6QxiiK/cNM8B6gL0q0N9lC.S8pDfkW', NULL, NULL, '2026-04-06 01:26:52', '2026-04-06 01:26:52'),
(48, 'Mohit Verma', 'mohit38@example.com', '8800000038', 'student', 'Y', '2026-04-06 01:26:52', '$2y$12$2AQlMigt6hUEJpPrtI/ouOg6QxiiK/cNM8B6gL0q0N9lC.S8pDfkW', NULL, NULL, '2026-04-06 01:26:52', '2026-04-06 01:26:52'),
(49, 'Tanya Patel', 'tanya39@example.com', '8800000039', 'student', 'N', '2026-04-06 01:26:52', '$2y$12$2AQlMigt6hUEJpPrtI/ouOg6QxiiK/cNM8B6gL0q0N9lC.S8pDfkW', NULL, NULL, '2026-04-06 01:26:52', '2026-04-06 01:26:52'),
(50, 'Varun Kumar', 'varun40@example.com', '8800000040', 'student', 'N', '2026-04-06 01:26:52', '$2y$12$2AQlMigt6hUEJpPrtI/ouOg6QxiiK/cNM8B6gL0q0N9lC.S8pDfkW', NULL, NULL, '2026-04-06 01:26:52', '2026-04-06 01:26:52'),
(51, 'Kirti Sharma', 'kirti41@example.com', '8800000041', 'student', 'N', '2026-04-06 01:26:52', '$2y$12$2AQlMigt6hUEJpPrtI/ouOg6QxiiK/cNM8B6gL0q0N9lC.S8pDfkW', NULL, NULL, '2026-04-06 01:26:52', '2026-04-06 02:49:38'),
(52, 'Naveen Yadav', 'naveen42@example.com', '8800000042', 'student', 'N', '2026-04-06 01:26:52', '$2y$12$2AQlMigt6hUEJpPrtI/ouOg6QxiiK/cNM8B6gL0q0N9lC.S8pDfkW', NULL, NULL, '2026-04-06 01:26:52', '2026-04-06 01:26:52'),
(53, 'Ishita Singh', 'ishita43@example.com', '8800000043', 'student', 'Y', '2026-04-06 01:26:52', '$2y$12$2AQlMigt6hUEJpPrtI/ouOg6QxiiK/cNM8B6gL0q0N9lC.S8pDfkW', NULL, NULL, '2026-04-06 01:26:52', '2026-04-08 02:06:39'),
(54, 'Karan Gupta user', 'karan44@example.com', '8800000044', 'student', 'N', '2026-04-06 01:26:52', '$2y$12$2AQlMigt6hUEJpPrtI/ouOg6QxiiK/cNM8B6gL0q0N9lC.S8pDfkW', NULL, NULL, '2026-04-06 01:26:52', '2026-04-08 01:20:52'),
(55, 'Aditya pratap Vishwakarma', 'adityapratap05005@gmail.com', '7390916220', 'student', 'N', '2026-04-06 01:26:52', '$2y$12$2AQlMigt6hUEJpPrtI/ouOg6QxiiK/cNM8B6gL0q0N9lC.S8pDfkW', '1776161869_WhatsApp_Image_2026-01-21_at_8.52.55_AM.jpeg', NULL, '2026-04-06 01:26:52', '2026-04-14 04:47:49'),
(56, 'Yash Patel', 'yash46@example.com', '8800000046', 'student', 'N', '2026-04-06 01:26:52', '$2y$12$2AQlMigt6hUEJpPrtI/ouOg6QxiiK/cNM8B6gL0q0N9lC.S8pDfkW', NULL, NULL, '2026-04-06 01:26:52', '2026-04-06 01:26:52'),
(57, 'Divya Mishra', 'divya47@example.com', '8800000047', 'student', 'Y', '2026-04-06 01:26:52', '$2y$12$2AQlMigt6hUEJpPrtI/ouOg6QxiiK/cNM8B6gL0q0N9lC.S8pDfkW', NULL, NULL, '2026-04-06 01:26:52', '2026-04-08 02:06:34'),
(58, 'Ujjwal Kumar', 'ujjwal48@example.com', '8800000048', 'student', 'N', '2026-04-06 01:26:52', '$2y$12$2AQlMigt6hUEJpPrtI/ouOg6QxiiK/cNM8B6gL0q0N9lC.S8pDfkW', NULL, NULL, '2026-04-06 01:26:52', '2026-04-15 08:01:19'),
(59, 'Megha Singh', 'megha49@example.com', '8800000049', 'student', 'Y', '2026-04-06 01:26:52', '$2y$12$2AQlMigt6hUEJpPrtI/ouOg6QxiiK/cNM8B6gL0q0N9lC.S8pDfkW', '1775466929_Screenshot_2026-01-05_000522.png', NULL, '2026-04-06 01:26:52', '2026-04-08 02:32:01'),
(60, 'Anurag Tiwari User', 'anurag50@example.com', '8800000050', 'student', 'Y', '2026-04-06 01:26:52', '$2y$12$2AQlMigt6hUEJpPrtI/ouOg6QxiiK/cNM8B6gL0q0N9lC.S8pDfkW', '1775466694_project.png', NULL, '2026-04-06 01:26:52', '2026-04-10 06:25:26'),
(61, 'Aditya Pratap', 'employee@gmail.com', '8009423207', 'employee', 'N', '2026-04-06 05:33:01', '$2y$12$JRWBbrcEt5FmIZzPcg92guLlcUEyIkK5sbEBe3QmIHd.1yJzA6TTG', '1775473381_WhatsApp_Image_2025-12-20_at_3.13.03_PM.jpeg', NULL, '2026-04-06 05:33:01', '2026-04-06 05:39:09'),
(62, 'Aditya Pratap', 'employee05@gmail.com', '8009423208', 'employee', 'N', '2026-04-06 06:11:56', '$2y$12$XtEZdjlCIkMw2Gm2riEY4.lD/seHMRyZBkkIjnP918H8P1P69fi1u', '1775475741_Screenshot_2025-12-11_164959.png', NULL, '2026-04-06 06:11:56', '2026-04-06 07:51:33'),
(63, 'Aditya Pratap', 'adityapratap0567@gmail.com', '8009434652', 'employee', 'N', '2026-04-06 08:18:06', '$2y$12$1YxSyPPHzcvi/ixyvOTBBOR1TWXJ6JYIWyaI5XX2zt3ZUKu8aMCcm', '1775483286_WhatsApp_Image_2026-01-13_at_10.44.53_PM.jpeg', NULL, '2026-04-06 08:18:06', '2026-04-06 08:18:06'),
(64, 'Aditya pratap Vishwakarma', 'student@gmail.com', '8390916220', 'student', 'N', '2026-04-08 12:57:03', '$2y$12$BC62j5HvFSYGkC9CziWI2ObiHX.GuisDD8Xotx/risUiCkxhILOTa', NULL, NULL, '2026-04-08 12:57:03', '2026-04-08 12:57:03'),
(65, 'Aditya pratap Vishwakarma', 'aditya@gmail.com', '7390916220', 'student', 'N', '2026-04-08 14:01:17', '$2y$12$tiPzlwd00E2kB5KqHqSOfeQ8J9sTLhqbmcdLeufEw4103VLDskgpe', 'students/H1mTlx32c7YxYE9cdABFSNQwr9mjmXBjYaR0sqgj.jpg', NULL, '2026-04-08 14:01:17', '2026-04-08 14:01:17'),
(66, 'Sanjay Kumar', 'student1@gmail.com', '7890916220', 'student', 'N', '2026-04-08 14:30:36', '$2y$12$sDiigz4qbIDR5e1fmr/5UOuEsdcuG4wPkdEmCvvfoxud5ZJnYykfa', 'students/K3ixdGtWrYciXF5WZljzlsinyxNwpl8NVsSE004L.png', NULL, '2026-04-08 14:30:36', '2026-04-08 14:30:36'),
(67, 'Aditya Kumar', 'adityakumar@gmail.com', '6389450032', 'student', 'N', '2026-04-10 06:27:19', '$2y$12$sDiigz4qbIDR5e1fmr/5UOuEsdcuG4wPkdEmCvvfoxud5ZJnYykfa', '1775822239_Screenshot_2026-03-25_162618.png', NULL, '2026-04-10 06:27:19', '2026-04-10 06:27:19'),
(68, 'Abhi Maurya', 'abhi@gmail.com', '7482482388', 'student', 'N', '2026-04-13 03:48:54', '$2y$12$XhO5qqIskA.UCromCKXYNOH2//s9lRqqAu3r0CC2mS.4fHXBanHGq', '1776071934_project.png', NULL, '2026-04-13 03:48:54', '2026-04-13 03:48:54'),
(69, 'Abhishek Maurya', 'abhishek@gmail.com', '8790936220', 'student', 'N', '2026-04-13 03:50:47', '$2y$12$c1SoXY.tN27MMhPB2wInd.A4TL9zDt4R5t2pkPKX5ULbGuJU2Hjbi', NULL, NULL, '2026-04-13 03:50:47', '2026-04-13 03:50:47'),
(70, 'Aditya pratap Vishwakarma', 'saurabh@gmail.com', '8374548888', 'student', 'N', '2026-04-14 01:22:38', '$2y$12$Ya/2vaqAecbc1h20mO6Cu.gBfejZ54eLbxRYXgq7N6EWoDRFvx0DO', '1776318830_screencapture-handshake-softproindia-in-student-feedback-php-2026-04-15-14_29_19.png', NULL, '2026-04-14 01:22:38', '2026-04-16 00:23:50'),
(71, 'Sakshi Pandey', 'spandey5449@gmail.com', '9506923396', 'student', 'N', '2026-04-14 05:01:58', '$2y$12$2AQlMigt6hUEJpPrtI/ouOg6QxiiK/cNM8B6gL0q0N9lC.S8pDfkW', NULL, NULL, '2026-04-14 05:01:58', '2026-04-14 05:01:58'),
(72, 'shubham', 'shubham@gmail.com', '7397865344', 'student', 'N', '2026-04-16 00:25:16', '$2y$12$YbPOgu.ga2V6iK9eexQ./uSq2JcxCYqCu5DLpxoVgM7HJ/C.kPjwy', '1776318916_screencapture-handshake-softproindia-in-student-feedback-php-2026-04-15-14_29_19.png', NULL, '2026-04-16 00:25:16', '2026-04-16 00:25:16');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;


-- --------------------------------------------------------
-- Login/profile sync patch
ALTER TABLE `users`
  ADD COLUMN IF NOT EXISTS `approval_status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Approved' AFTER `role`,
  ADD COLUMN IF NOT EXISTS `college` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL AFTER `image`,
  ADD COLUMN IF NOT EXISTS `regno` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL AFTER `college`,
  ADD COLUMN IF NOT EXISTS `course` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL AFTER `regno`,
  ADD COLUMN IF NOT EXISTS `branch` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL AFTER `course`,
  ADD COLUMN IF NOT EXISTS `year` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL AFTER `branch`,
  ADD COLUMN IF NOT EXISTS `gender` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL AFTER `year`,
  ADD COLUMN IF NOT EXISTS `program` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL AFTER `gender`,
  ADD COLUMN IF NOT EXISTS `location` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL AFTER `program`;

UPDATE `users` u
LEFT JOIN `tbl_register` r ON r.`email` = u.`email`
SET
  u.`approval_status` = CASE WHEN LOWER(COALESCE(u.`role`, '')) = 'student' THEN COALESCE(r.`approval_status`, u.`approval_status`, 'Pending') ELSE 'Approved' END,
  u.`college` = COALESCE(u.`college`, r.`college`),
  u.`regno` = COALESCE(u.`regno`, r.`regno`),
  u.`course` = COALESCE(u.`course`, r.`course`),
  u.`branch` = COALESCE(u.`branch`, r.`branch`),
  u.`year` = COALESCE(u.`year`, r.`year`),
  u.`gender` = COALESCE(u.`gender`, CONCAT(UCASE(LEFT(r.`gender`,1)), LCASE(SUBSTRING(r.`gender`,2)))),
  u.`program` = COALESCE(u.`program`, r.`program`),
  u.`location` = COALESCE(u.`location`, r.`location`);

UPDATE `users`
SET `approval_status` = 'Approved'
WHERE LOWER(COALESCE(`role`, '')) = 'admin';
