-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Aug 25, 2025 at 07:30 PM
-- Server version: 8.0.30
-- PHP Version: 8.2.24

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `upay`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('role_permission_cache_admin@yopamil.com|127.0.0.1', 'i:1;', 1755963789),
('role_permission_cache_admin@yopamil.com|127.0.0.1:timer', 'i:1755963789;', 1755963789),
('role_permission_cache_admin@yopmail.com|::1', 'i:2;', 1756135939),
('role_permission_cache_admin@yopmail.com|::1:timer', 'i:1756135939;', 1756135939),
('role_permission_cache_admin@yopmail.com|127.0.0.1', 'i:1;', 1755953479),
('role_permission_cache_admin@yopmail.com|127.0.0.1:timer', 'i:1755953479;', 1755953479),
('role_permission_cache_spatie.permission.cache', 'a:3:{s:5:\"alias\";a:4:{s:1:\"a\";s:2:\"id\";s:1:\"b\";s:4:\"name\";s:1:\"c\";s:10:\"guard_name\";s:1:\"r\";s:5:\"roles\";}s:11:\"permissions\";a:19:{i:0;a:4:{s:1:\"a\";i:1;s:1:\"b\";s:15:\"Role-Management\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:1;a:4:{s:1:\"a\";i:2;s:1:\"b\";s:21:\"Permission-Management\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:2;a:4:{s:1:\"a\";i:3;s:1:\"b\";s:7:\"Uploads\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:3;a:4:{s:1:\"a\";i:4;s:1:\"b\";s:13:\"Single-Upload\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:4;a:4:{s:1:\"a\";i:5;s:1:\"b\";s:11:\"Bulk-Upload\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:5;a:4:{s:1:\"a\";i:6;s:1:\"b\";s:16:\"Users-Management\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:6;a:4:{s:1:\"a\";i:7;s:1:\"b\";s:7:\"API-Doc\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:7;a:4:{s:1:\"a\";i:8;s:1:\"b\";s:11:\"Transations\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:8;a:4:{s:1:\"a\";i:9;s:1:\"b\";s:5:\"PayIn\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:9;a:4:{s:1:\"a\";i:10;s:1:\"b\";s:6:\"PayOut\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:10;a:4:{s:1:\"a\";i:11;s:1:\"b\";s:6:\"Ledger\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:11;a:4:{s:1:\"a\";i:12;s:1:\"b\";s:12:\"wallet-topup\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:12;a:4:{s:1:\"a\";i:13;s:1:\"b\";s:20:\"wallet-topup-request\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:13;a:4:{s:1:\"a\";i:14;s:1:\"b\";s:11:\"wallet-hold\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:14;a:4:{s:1:\"a\";i:15;s:1:\"b\";s:19:\"wallet-hold-request\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:15;a:4:{s:1:\"a\";i:16;s:1:\"b\";s:13:\"wallet-refund\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:16;a:4:{s:1:\"a\";i:17;s:1:\"b\";s:15:\"service-charges\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:17;a:4:{s:1:\"a\";i:18;s:1:\"b\";s:10:\"commission\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:18;a:4:{s:1:\"a\";i:19;s:1:\"b\";s:3:\"log\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}}s:5:\"roles\";a:2:{i:0;a:3:{s:1:\"a\";i:1;s:1:\"b\";s:5:\"ADMIN\";s:1:\"c\";s:3:\"web\";}i:1;a:3:{s:1:\"a\";i:2;s:1:\"b\";s:5:\"USERS\";s:1:\"c\";s:3:\"web\";}}}', 1756222349);

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint UNSIGNED NOT NULL,
  `main_category_id` bigint UNSIGNED NOT NULL,
  `name` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `status` tinyint NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `comissions_old`
--

CREATE TABLE `comissions_old` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `type` enum('PAYOUT','PAYIN') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `comission_amount` decimal(10,2) NOT NULL,
  `comission_percentage` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `comissions_old`
--

INSERT INTO `comissions_old` (`id`, `user_id`, `type`, `comission_amount`, `comission_percentage`, `created_at`, `updated_at`) VALUES
(1, 4, 'PAYIN', '100000.00', '10.00', '2025-08-17 10:58:36', '2025-08-17 10:58:36'),
(2, 4, 'PAYIN', '10000.00', '20.00', '2025-08-19 12:52:15', '2025-08-19 12:52:15');

-- --------------------------------------------------------

--
-- Table structure for table `commissions`
--

CREATE TABLE `commissions` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `admin_id` bigint UNSIGNED DEFAULT NULL,
  `type` enum('payin','payout') COLLATE utf8mb4_unicode_ci NOT NULL,
  `commission1` decimal(12,2) DEFAULT NULL,
  `percentage1` decimal(5,2) DEFAULT NULL,
  `commission2` decimal(12,2) DEFAULT NULL,
  `percentage2` decimal(5,2) DEFAULT NULL,
  `commission3` decimal(12,2) DEFAULT NULL,
  `percentage3` decimal(5,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `commissions`
--

INSERT INTO `commissions` (`id`, `user_id`, `admin_id`, `type`, `commission1`, `percentage1`, `commission2`, `percentage2`, `commission3`, `percentage3`, `created_at`, `updated_at`) VALUES
(1, 8, 1, 'payin', '1.00', '2.00', '3.00', '4.00', '5.00', '6.00', '2025-08-23 04:24:42', '2025-08-23 04:24:42'),
(2, 8, 1, 'payout', '7.00', '5.00', '6.00', '9.00', '6.00', '6.00', '2025-08-23 04:24:42', '2025-08-23 04:24:42'),
(3, 9, 1, 'payin', '1.00', '1.00', '2.00', '3.00', '3.00', '3.00', '2025-08-23 04:28:00', '2025-08-23 04:28:00'),
(4, 9, 1, 'payout', '2.00', '3.00', '5.00', '7.00', '4.00', '4.00', '2025-08-23 04:28:00', '2025-08-23 04:28:00'),
(5, 11, 1, 'payin', '1.00', '1.00', '1.00', '1.00', '1.00', '1.00', '2025-08-23 05:43:49', '2025-08-23 05:43:49'),
(6, 11, 1, 'payout', '1.00', '1.00', '1.00', '1.00', '1.00', '1.00', '2025-08-23 05:43:49', '2025-08-23 05:43:49'),
(7, 12, 1, 'payin', '1.00', '2.00', '2.00', '3.00', '3.00', '3.00', '2025-08-23 05:46:59', '2025-08-23 05:46:59'),
(8, 12, 1, 'payout', '4.00', '4.00', '4.00', '4.00', '44.00', '5.00', '2025-08-23 05:46:59', '2025-08-23 05:46:59'),
(9, 13, 1, 'payin', '11.00', '12.00', '13.00', '11.00', '12.00', '13.00', '2025-08-23 05:51:27', '2025-08-23 06:19:31'),
(10, 13, 1, 'payout', '14.00', '15.00', '16.00', '14.00', '15.00', '16.00', '2025-08-23 05:51:27', '2025-08-23 06:19:31'),
(11, 4, 1, 'payin', '1000.00', '1.50', '25000.00', '3.50', '50000.00', '4.50', '2025-08-23 07:26:30', '2025-08-23 07:26:30'),
(12, 4, 1, 'payout', '1000.00', '1.40', '25000.00', '3.40', '50000.00', '4.40', '2025-08-23 07:26:30', '2025-08-23 07:26:30');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `main_categories`
--

CREATE TABLE `main_categories` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2024_08_17_140604_create_permission_tables', 1),
(5, '2024_09_01_102145_create_main_categories_table', 1),
(6, '2024_09_01_154313_add_deleted_at_to_your_table_name', 1),
(7, '2024_09_02_183432_create_categories_table', 1),
(8, '2024_09_04_174852_create_sub_categories_table', 1),
(9, '2025_08_15_103342_create_wallets_table', 2),
(10, '2025_08_15_103444_create_transactions_table', 2),
(11, '2025_08_15_104240_create_payouts_table', 3),
(12, '2025_08_15_104953_create_request_logs_table', 3),
(13, '2025_08_23_060026_create_commissions_table', 4),
(14, '2025_08_23_060346_update_users_table_add_dmt_and_status_fields', 4),
(15, '2025_08_23_062236_create_table_user_banks', 5);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 1),
(2, 'App\\Models\\User', 4),
(2, 'App\\Models\\User', 5),
(2, 'App\\Models\\User', 6),
(2, 'App\\Models\\User', 7),
(2, 'App\\Models\\User', 8),
(2, 'App\\Models\\User', 9),
(2, 'App\\Models\\User', 11),
(2, 'App\\Models\\User', 12),
(2, 'App\\Models\\User', 13);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payouts`
--

CREATE TABLE `payouts` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `transfer_by` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `account_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `account_holder_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ifsc` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `bank_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `transfer_amount` decimal(15,2) NOT NULL,
  `payment_mode` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remark` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `upload_type` int NOT NULL DEFAULT '1' COMMENT '1 single 2 bulk',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payouts`
--

INSERT INTO `payouts` (`id`, `user_id`, `transfer_by`, `account_number`, `account_holder_name`, `ifsc`, `bank_name`, `transfer_amount`, `payment_mode`, `remark`, `upload_type`, `created_at`, `updated_at`) VALUES
(1, 4, 'bank', '96969696969696', 'test', 'SBIN0001234', 'test', '10.00', 'NEFT', 'single', 1, '2025-08-20 12:10:15', '2025-08-20 12:10:15'),
(2, 4, 'bank', '3.02E+12', 'Demo3', 'CNRB0003020', 'Test', '10.00', 'NEFT', 'Test3', 2, '2025-08-20 12:13:14', '2025-08-20 12:13:14'),
(3, 4, 'bank', '3.02E+12', 'Demo4', 'CNRB0003022', 'Test', '12.00', 'NEFT', 'Test3', 2, '2025-08-20 12:13:14', '2025-08-20 12:13:14'),
(5, 4, 'bank', '12121212212', 'dasdasd', 'SBIN0001234', 'Test', '100.00', 'IMPS', 'erwer', 1, '2025-08-25 13:35:45', '2025-08-25 13:35:45'),
(7, 4, 'bank', '12121212212', 'dasdasd', 'SBIN0001234', 'Test', '100.00', 'IMPS', 'erwer', 1, '2025-08-25 13:42:59', '2025-08-25 13:42:59'),
(9, 4, 'bank', '12121212212', 'dasdasd', 'SBIN0001234', 'Test', '100.00', 'IMPS', 'erwer', 1, '2025-08-25 13:46:55', '2025-08-25 13:46:55'),
(11, 4, 'bank', '12121212212', 'dasdasd', 'SBIN0001234', 'Test', '100.00', 'IMPS', 'erwer', 1, '2025-08-25 13:50:25', '2025-08-25 13:50:25'),
(12, 4, 'bank', '3.02E+12', 'Demo3', 'CNRB0003020', 'Test', '10.00', 'NEFT', 'Test3', 2, '2025-08-25 13:52:59', '2025-08-25 13:52:59'),
(13, 4, 'bank', '3.02E+12', 'Demo4', 'CNRB0003022', 'Test', '12.00', 'NEFT', 'Test3', 2, '2025-08-25 13:53:00', '2025-08-25 13:53:00');

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'Role-Management', 'web', '2025-08-15 02:47:28', '2025-08-15 02:47:28'),
(2, 'Permission-Management', 'web', '2025-08-15 02:47:28', '2025-08-15 02:47:28'),
(3, 'Uploads', 'web', '2025-08-15 02:47:28', '2025-08-15 02:47:28'),
(4, 'Single-Upload', 'web', '2025-08-15 02:47:28', '2025-08-15 02:47:28'),
(5, 'Bulk-Upload', 'web', '2025-08-15 02:47:28', '2025-08-15 02:47:28'),
(6, 'Users-Management', 'web', '2025-08-15 02:47:28', '2025-08-15 02:47:28'),
(7, 'API-Doc', 'web', '2025-08-15 02:47:28', '2025-08-15 02:47:28'),
(8, 'Transations', 'web', '2025-08-15 02:47:28', '2025-08-15 02:47:28'),
(9, 'PayIn', 'web', '2025-08-15 02:47:28', '2025-08-15 02:47:28'),
(10, 'PayOut', 'web', '2025-08-15 02:47:28', '2025-08-15 02:47:28'),
(11, 'Ledger', 'web', '2025-08-15 02:47:28', '2025-08-15 02:47:28'),
(12, 'wallet-topup', 'web', '2025-08-15 02:47:28', '2025-08-15 02:47:28'),
(13, 'wallet-topup-request', 'web', '2025-08-15 02:47:28', '2025-08-15 02:47:28'),
(14, 'wallet-hold', 'web', '2025-08-15 02:47:28', '2025-08-15 02:47:28'),
(15, 'wallet-hold-request', 'web', '2025-08-15 02:47:28', '2025-08-15 02:47:28'),
(16, 'wallet-refund', 'web', '2025-08-15 02:47:28', '2025-08-15 02:47:28'),
(17, 'service-charges', 'web', '2025-08-15 02:47:28', '2025-08-15 02:47:28'),
(18, 'commission', 'web', '2025-08-15 02:47:28', '2025-08-15 02:47:28'),
(19, 'log', 'web', '2025-08-15 02:47:28', '2025-08-15 02:47:28');

-- --------------------------------------------------------

--
-- Table structure for table `request_logs`
--

CREATE TABLE `request_logs` (
  `id` bigint UNSIGNED NOT NULL,
  `ip` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `end_point` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `data` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `request_logs`
--

INSERT INTO `request_logs` (`id`, `ip`, `type`, `user_agent`, `end_point`, `data`, `created_at`, `updated_at`) VALUES
(1, '::1', 'payout', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', 'admin/singleupload', '{\"ifsc\": \"SBIN0001234\", \"_token\": \"2xLDxpf2KnXW0LvFdBP4aYKZ8hzXawQ8HCqmEJoP\", \"remark\": \"single\", \"bank_name\": \"test\", \"transfer_by\": \"bank\", \"payment_mode\": \"NEFT\", \"account_number\": \"96969696969696\", \"transfer_amount\": \"10\", \"account_holder_name\": \"test\"}', '2025-08-20 12:10:15', '2025-08-20 12:10:15'),
(2, '::1', 'transaction', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', 'admin/singleupload', '{\"ifsc\": \"SBIN0001234\", \"_token\": \"2xLDxpf2KnXW0LvFdBP4aYKZ8hzXawQ8HCqmEJoP\", \"remark\": \"single\", \"bank_name\": \"test\", \"transfer_by\": \"bank\", \"payment_mode\": \"NEFT\", \"account_number\": \"96969696969696\", \"transfer_amount\": \"10\", \"account_holder_name\": \"test\"}', '2025-08-20 12:10:15', '2025-08-20 12:10:15'),
(3, '::1', 'payout', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', 'admin/bulkUpload', '{\"ifsc\": \"CNRB0003020\", \"remark\": \"Test3\", \"bank_name\": \"Test\", \"transfer_by\": \"bank\", \"payment_mode\": \"NEFT\", \"account_number\": \"3.02E+12\", \"transfer_amount\": \"10\", \"account_holder_name\": \"Demo3\"}', '2025-08-20 12:13:14', '2025-08-20 12:13:14'),
(4, '::1', 'transaction', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', 'admin/bulkUpload', '{\"ifsc\": \"CNRB0003020\", \"remark\": \"Test3\", \"bank_name\": \"Test\", \"transfer_by\": \"bank\", \"payment_mode\": \"NEFT\", \"account_number\": \"3.02E+12\", \"transfer_amount\": \"10\", \"account_holder_name\": \"Demo3\"}', '2025-08-20 12:13:14', '2025-08-20 12:13:14'),
(5, '::1', 'payout', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', 'admin/bulkUpload', '{\"ifsc\": \"CNRB0003022\", \"remark\": \"Test3\", \"bank_name\": \"Test\", \"transfer_by\": \"bank\", \"payment_mode\": \"NEFT\", \"account_number\": \"3.02E+12\", \"transfer_amount\": \"12\", \"account_holder_name\": \"Demo4\"}', '2025-08-20 12:13:14', '2025-08-20 12:13:14'),
(6, '::1', 'transaction', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', 'admin/bulkUpload', '{\"ifsc\": \"CNRB0003022\", \"remark\": \"Test3\", \"bank_name\": \"Test\", \"transfer_by\": \"bank\", \"payment_mode\": \"NEFT\", \"account_number\": \"3.02E+12\", \"transfer_amount\": \"12\", \"account_holder_name\": \"Demo4\"}', '2025-08-20 12:13:14', '2025-08-20 12:13:14'),
(7, '127.0.0.1', 'wallet_load_request', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:142.0) Gecko/20100101 Firefox/142.0', 'admin/wallet-topup', '{\"_token\": \"8MpaiBQSsNqKMhJ4gjYwmDIy7NgCFUFBxnu6ltzW\", \"amount\": \"1001\", \"remark\": \"WALLET LOAD\", \"user_id\": \"4\"}', '2025-08-23 07:36:21', '2025-08-23 07:36:21'),
(8, '127.0.0.1', 'wallet_load_request', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:142.0) Gecko/20100101 Firefox/142.0', 'admin/wallet-topup', '{\"_token\": \"I3MuHZB93FiYELCCUMATCXO9R2NnYnkX4vuaXLIl\", \"amount\": \"10000\", \"remark\": \"WALLET LOAD\", \"user_id\": \"4\"}', '2025-08-23 10:59:50', '2025-08-23 10:59:50'),
(9, '127.0.0.1', 'wallet_load_request', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:142.0) Gecko/20100101 Firefox/142.0', 'admin/wallet-topup', '{\"_token\": \"I3MuHZB93FiYELCCUMATCXO9R2NnYnkX4vuaXLIl\", \"amount\": \"10000\", \"remark\": \"WALLET LOAD\", \"user_id\": \"4\"}', '2025-08-23 11:17:25', '2025-08-23 11:17:25'),
(10, '127.0.0.1', 'wallet_load_request', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:142.0) Gecko/20100101 Firefox/142.0', 'admin/wallet-topup', '{\"_token\": \"I3MuHZB93FiYELCCUMATCXO9R2NnYnkX4vuaXLIl\", \"amount\": \"10000\", \"remark\": \"WALLET LOAD\", \"user_id\": \"4\"}', '2025-08-23 11:29:37', '2025-08-23 11:29:37'),
(11, '127.0.0.1', 'wallet_load_request', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:142.0) Gecko/20100101 Firefox/142.0', 'admin/wallet-topup', '{\"_token\": \"I3MuHZB93FiYELCCUMATCXO9R2NnYnkX4vuaXLIl\", \"amount\": \"10000\", \"remark\": \"WALLET LOAD\", \"user_id\": \"4\"}', '2025-08-23 11:33:04', '2025-08-23 11:33:04'),
(12, '127.0.0.1', 'wallet_load_request', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:142.0) Gecko/20100101 Firefox/142.0', 'admin/wallet-topup', '{\"_token\": \"I3MuHZB93FiYELCCUMATCXO9R2NnYnkX4vuaXLIl\", \"amount\": \"10000\", \"remark\": \"WALLET LOAD\", \"user_id\": \"4\"}', '2025-08-23 11:38:13', '2025-08-23 11:38:13'),
(13, '127.0.0.1', 'wallet_load_request', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:142.0) Gecko/20100101 Firefox/142.0', 'admin/wallet-topup', '{\"_token\": \"I3MuHZB93FiYELCCUMATCXO9R2NnYnkX4vuaXLIl\", \"amount\": \"10000\", \"remark\": \"WALLET LOAD\", \"user_id\": \"4\"}', '2025-08-23 11:42:46', '2025-08-23 11:42:46'),
(14, '127.0.0.1', 'wallet_load_request', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:142.0) Gecko/20100101 Firefox/142.0', 'admin/wallet-topup', '{\"_token\": \"I3MuHZB93FiYELCCUMATCXO9R2NnYnkX4vuaXLIl\", \"amount\": \"10000\", \"remark\": \"WALLET LOAD\", \"user_id\": \"4\"}', '2025-08-23 11:44:00', '2025-08-23 11:44:00'),
(15, '127.0.0.1', 'wallet_load_request', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:142.0) Gecko/20100101 Firefox/142.0', 'admin/wallet-topup', '{\"_token\": \"I3MuHZB93FiYELCCUMATCXO9R2NnYnkX4vuaXLIl\", \"amount\": \"10000\", \"remark\": \"WALLET LOAD\", \"user_id\": \"4\"}', '2025-08-23 12:13:02', '2025-08-23 12:13:02'),
(16, '127.0.0.1', 'wallet_load_request', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:142.0) Gecko/20100101 Firefox/142.0', 'admin/wallet-topup', '{\"_token\": \"I3MuHZB93FiYELCCUMATCXO9R2NnYnkX4vuaXLIl\", \"amount\": \"10000\", \"remark\": \"WALLET LOAD\", \"user_id\": \"4\"}', '2025-08-23 12:18:10', '2025-08-23 12:18:10'),
(17, '127.0.0.1', 'wallet_load_request', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:142.0) Gecko/20100101 Firefox/142.0', 'admin/wallet-topup', '{\"_token\": \"I3MuHZB93FiYELCCUMATCXO9R2NnYnkX4vuaXLIl\", \"amount\": \"10000\", \"remark\": \"WALLET LOAD\", \"user_id\": \"4\"}', '2025-08-23 12:20:37', '2025-08-23 12:20:37'),
(18, '127.0.0.1', 'wallet_load_request', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:142.0) Gecko/20100101 Firefox/142.0', 'admin/wallet-topup', '{\"_token\": \"I3MuHZB93FiYELCCUMATCXO9R2NnYnkX4vuaXLIl\", \"amount\": \"10000\", \"remark\": \"WALLET LOAD\", \"user_id\": \"4\"}', '2025-08-23 12:25:43', '2025-08-23 12:25:43'),
(19, '127.0.0.1', 'wallet_load_request', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:142.0) Gecko/20100101 Firefox/142.0', 'admin/wallet-topup', '{\"_token\": \"I3MuHZB93FiYELCCUMATCXO9R2NnYnkX4vuaXLIl\", \"amount\": \"10000\", \"remark\": \"WALLET LOAD\", \"user_id\": \"4\"}', '2025-08-23 12:27:33', '2025-08-23 12:27:33'),
(20, '127.0.0.1', 'wallet_load_request', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:142.0) Gecko/20100101 Firefox/142.0', 'admin/wallet-topup', '{\"_token\": \"I3MuHZB93FiYELCCUMATCXO9R2NnYnkX4vuaXLIl\", \"amount\": \"10000\", \"remark\": \"WALLET LOAD\", \"user_id\": \"4\"}', '2025-08-23 12:34:23', '2025-08-23 12:34:23'),
(21, '127.0.0.1', 'wallet_load_request', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:142.0) Gecko/20100101 Firefox/142.0', 'admin/wallet-topup', '{\"_token\": \"I3MuHZB93FiYELCCUMATCXO9R2NnYnkX4vuaXLIl\", \"amount\": \"10000\", \"remark\": \"WALLET LOAD\", \"user_id\": \"4\"}', '2025-08-23 12:48:52', '2025-08-23 12:48:52'),
(22, '127.0.0.1', 'wallet_load_request', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:142.0) Gecko/20100101 Firefox/142.0', 'admin/wallet-topup', '{\"_token\": \"I3MuHZB93FiYELCCUMATCXO9R2NnYnkX4vuaXLIl\", \"amount\": \"10000\", \"remark\": \"WALLET LOAD\", \"user_id\": \"4\"}', '2025-08-23 12:58:46', '2025-08-23 12:58:46'),
(23, '127.0.0.1', 'wallet_load_request', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:142.0) Gecko/20100101 Firefox/142.0', 'admin/wallet-topup', '{\"_token\": \"I3MuHZB93FiYELCCUMATCXO9R2NnYnkX4vuaXLIl\", \"amount\": \"10000\", \"remark\": \"WALLET LOAD\", \"user_id\": \"4\"}', '2025-08-23 13:10:13', '2025-08-23 13:10:13'),
(24, '127.0.0.1', 'wallet_load_request', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:142.0) Gecko/20100101 Firefox/142.0', 'admin/wallet-topup', '{\"_token\": \"I3MuHZB93FiYELCCUMATCXO9R2NnYnkX4vuaXLIl\", \"amount\": \"10000\", \"remark\": \"WALLET LOAD\", \"user_id\": \"4\"}', '2025-08-23 13:11:42', '2025-08-23 13:11:42'),
(25, '127.0.0.1', 'wallet_load_request', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:142.0) Gecko/20100101 Firefox/142.0', 'admin/wallet-topup', '{\"_token\": \"I3MuHZB93FiYELCCUMATCXO9R2NnYnkX4vuaXLIl\", \"amount\": \"10000\", \"remark\": \"WALLET LOAD\", \"user_id\": \"4\"}', '2025-08-23 13:15:57', '2025-08-23 13:15:57'),
(26, '127.0.0.1', 'wallet_load_request', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:142.0) Gecko/20100101 Firefox/142.0', 'admin/wallet-topup', '{\"_token\": \"I3MuHZB93FiYELCCUMATCXO9R2NnYnkX4vuaXLIl\", \"amount\": \"10000\", \"remark\": \"WALLET LOAD\", \"user_id\": \"4\"}', '2025-08-23 13:17:13', '2025-08-23 13:17:13'),
(27, '127.0.0.1', 'wallet_load_request', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:142.0) Gecko/20100101 Firefox/142.0', 'admin/wallet-topup', '{\"_token\": \"I3MuHZB93FiYELCCUMATCXO9R2NnYnkX4vuaXLIl\", \"amount\": \"10000\", \"remark\": \"WALLET LOAD\", \"user_id\": \"4\"}', '2025-08-23 13:19:18', '2025-08-23 13:19:18'),
(28, '::1', 'payout', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', 'admin/singleupload', '{\"data\": {\"data\": {\"udf1\": \"optional Data1\", \"udf2\": \"optional Data2\", \"udf3\": \"optional Data3\", \"status\": \"Completed\", \"orderId\": \"134895854\", \"transactionId\": \"\", \"creationDateTime\": \"2025-08-15T17:55:40.000000+05:30\"}, \"errors\": null, \"message\": \"Payment initiated successfully...!!!\", \"success\": true, \"exception\": null}, \"status\": true}', '2025-08-25 13:35:45', '2025-08-25 13:35:45'),
(29, '::1', 'payout', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', 'admin/singleupload', '{\"data\": {\"data\": {\"udf1\": \"optional Data1\", \"udf2\": \"optional Data2\", \"udf3\": \"optional Data3\", \"status\": \"Completed\", \"orderId\": \"134895854\", \"transactionId\": \"\", \"creationDateTime\": \"2025-08-15T17:55:40.000000+05:30\"}, \"errors\": null, \"message\": \"Payment initiated successfully...!!!\", \"success\": true, \"exception\": null}, \"status\": true}', '2025-08-25 13:42:59', '2025-08-25 13:42:59'),
(30, '::1', 'payout', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', 'admin/singleupload', '{\"data\": {\"data\": {\"udf1\": \"optional Data1\", \"udf2\": \"optional Data2\", \"udf3\": \"optional Data3\", \"status\": \"Completed\", \"orderId\": \"134895854\", \"transactionId\": \"\", \"creationDateTime\": \"2025-08-15T17:55:40.000000+05:30\"}, \"errors\": null, \"message\": \"Payment initiated successfully...!!!\", \"success\": true, \"exception\": null}, \"status\": true}', '2025-08-25 13:46:55', '2025-08-25 13:46:55'),
(32, '::1', 'payout', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', 'admin/singleupload', '{\"data\": {\"data\": {\"udf1\": \"optional Data1\", \"udf2\": \"optional Data2\", \"udf3\": \"optional Data3\", \"status\": \"Completed\", \"orderId\": \"134895854\", \"transactionId\": \"\", \"creationDateTime\": \"2025-08-15T17:55:40.000000+05:30\"}, \"errors\": null, \"message\": \"Payment initiated successfully...!!!\", \"success\": true, \"exception\": null}, \"status\": true}', '2025-08-25 13:50:25', '2025-08-25 13:50:25'),
(33, '::1', 'payout', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', 'admin/bulkUpload', '{\"data\": {\"data\": {\"status\": \"Completed\", \"orderId\": \"134895854\"}, \"message\": \"Payment initiated successfully...!!!\", \"success\": true}, \"status\": true}', '2025-08-25 13:52:59', '2025-08-25 13:52:59'),
(34, '::1', 'payout', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', 'admin/bulkUpload', '{\"data\": {\"data\": {\"status\": \"Completed\", \"orderId\": \"134895854\"}, \"message\": \"Payment initiated successfully...!!!\", \"success\": true}, \"status\": true}', '2025-08-25 13:53:00', '2025-08-25 13:53:00');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'ADMIN', 'web', '2025-08-15 02:47:29', '2025-08-16 23:37:26'),
(2, 'USERS', 'web', '2025-08-15 02:48:47', '2025-08-16 01:42:06');

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `role_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_has_permissions`
--

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(1, 1),
(2, 1),
(3, 1),
(4, 1),
(5, 1),
(6, 1),
(7, 1),
(8, 1),
(9, 1),
(10, 1),
(11, 1),
(12, 1),
(13, 1),
(14, 1),
(15, 1),
(16, 1),
(17, 1),
(18, 1),
(19, 1),
(3, 2),
(4, 2),
(5, 2),
(7, 2),
(8, 2),
(9, 2),
(10, 2),
(11, 2);

-- --------------------------------------------------------

--
-- Table structure for table `service_charges`
--

CREATE TABLE `service_charges` (
  `id` bigint UNSIGNED NOT NULL,
  `ref_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'wallet request id',
  `ref_type` enum('TRANSACTION','WALLET_REQUEST','WALLET_HOLD') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `gst` decimal(10,2) DEFAULT NULL,
  `charge` decimal(10,2) DEFAULT NULL,
  `total_charge` decimal(10,2) DEFAULT NULL,
  `type` enum('PAYOUT','PAYIN','HOLD') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_charged` tinyint(1) NOT NULL DEFAULT '0',
  `api_provider` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `service_charges`
--

INSERT INTO `service_charges` (`id`, `ref_id`, `ref_type`, `amount`, `gst`, `charge`, `total_charge`, `type`, `is_charged`, `api_provider`, `created_at`, `updated_at`) VALUES
(2, '2', 'WALLET_REQUEST', '10000.00', '90.00', '150.00', '240.00', 'PAYIN', 1, NULL, '2025-08-24 13:17:26', '2025-08-23 13:17:26'),
(3, '4', 'TRANSACTION', '100.00', '0.90', '1.40', '2.30', 'PAYOUT', 1, NULL, '2025-08-25 13:35:45', '2025-08-25 13:35:45'),
(4, '5', 'TRANSACTION', '100.00', '0.90', '1.40', '2.30', 'PAYOUT', 1, NULL, '2025-08-25 13:42:59', '2025-08-25 13:42:59'),
(5, '6', 'TRANSACTION', '100.00', '0.90', '1.40', '2.30', 'PAYOUT', 1, NULL, '2025-08-25 13:46:55', '2025-08-25 13:46:55'),
(6, '7', 'TRANSACTION', '100.00', '0.90', '1.40', '2.30', 'PAYOUT', 1, NULL, '2025-08-25 13:50:25', '2025-08-25 13:50:25'),
(7, '8', 'TRANSACTION', '10.00', '0.90', '1.40', '2.30', 'PAYOUT', 1, NULL, '2025-08-25 13:52:59', '2025-08-25 13:52:59'),
(8, '9', 'TRANSACTION', '12.00', '0.90', '1.40', '2.30', 'PAYOUT', 1, NULL, '2025-08-25 13:53:00', '2025-08-25 13:53:00');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sub_categories`
--

CREATE TABLE `sub_categories` (
  `id` bigint UNSIGNED NOT NULL,
  `category_id` bigint UNSIGNED NOT NULL,
  `name` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `status` tinyint NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` bigint UNSIGNED NOT NULL,
  `transaction_id` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `user_id` bigint UNSIGNED NOT NULL,
  `order_id` text COLLATE utf8mb4_unicode_ci,
  `wallet_id` bigint UNSIGNED NOT NULL,
  `type` enum('payout','payin','WALLETLOAD','REVERTWALLETLOAD') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_balance` float NOT NULL DEFAULT '0',
  `amount` decimal(15,2) NOT NULL,
  `balance` decimal(15,2) NOT NULL,
  `reference` int DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `status` enum('pending','success','failed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `is_active` tinyint NOT NULL DEFAULT '1',
  `upload_type` int NOT NULL DEFAULT '1' COMMENT '1 single 2 bulk',
  `remark` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `response_data` json DEFAULT NULL,
  `initiator_id` text COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `transaction_id`, `user_id`, `order_id`, `wallet_id`, `type`, `last_balance`, `amount`, `balance`, `reference`, `description`, `status`, `is_active`, `upload_type`, `remark`, `created_at`, `updated_at`, `response_data`, `initiator_id`) VALUES
(1, NULL, 4, NULL, 1, 'WALLETLOAD', 0, '10000.00', '9760.00', NULL, 'sdad', 'success', 1, 1, 'Wallet Load', '2025-08-23 13:16:18', '2025-08-23 13:16:18', NULL, 'SBIN1234567891'),
(2, NULL, 4, NULL, 1, 'WALLETLOAD', 9760, '10000.00', '19520.00', NULL, 'dasd', 'success', 1, 1, 'Wallet Load', '2025-08-23 13:17:26', '2025-08-23 13:17:26', NULL, 'SBIN1234567892'),
(3, NULL, 4, NULL, 1, 'REVERTWALLETLOAD', 19520, '10000.00', '9760.00', NULL, 'cdsf', 'success', 1, 1, 'Reverted Wallet Load', '2025-08-23 13:18:18', '2025-08-23 13:18:18', NULL, 'SBIN1234567891'),
(4, '134895854', 4, '134895854', 1, 'payout', 0, '100.00', '9660.00', 5, 'Payout request created', 'success', 1, 1, NULL, '2025-08-25 13:35:45', '2025-08-25 13:35:45', '{\"data\": {\"data\": {\"udf1\": \"optional Data1\", \"udf2\": \"optional Data2\", \"udf3\": \"optional Data3\", \"status\": \"Completed\", \"orderId\": \"134895854\", \"transactionId\": \"\", \"creationDateTime\": \"2025-08-15T17:55:40.000000+05:30\"}, \"errors\": null, \"message\": \"Payment initiated successfully...!!!\", \"success\": true, \"exception\": null}, \"status\": true}', 'SBIN0001234'),
(5, '134895854', 4, '134895854', 1, 'payout', 0, '100.00', '9560.00', 7, 'Payout request created', 'success', 1, 1, NULL, '2025-08-25 13:42:59', '2025-08-25 13:42:59', '{\"data\": {\"data\": {\"udf1\": \"optional Data1\", \"udf2\": \"optional Data2\", \"udf3\": \"optional Data3\", \"status\": \"Completed\", \"orderId\": \"134895854\", \"transactionId\": \"\", \"creationDateTime\": \"2025-08-15T17:55:40.000000+05:30\"}, \"errors\": null, \"message\": \"Payment initiated successfully...!!!\", \"success\": true, \"exception\": null}, \"status\": true}', 'SBIN0001234'),
(6, '134895854', 4, '134895854', 1, 'payout', 0, '100.00', '9460.00', 9, 'Payout request created', 'success', 1, 1, NULL, '2025-08-25 13:46:55', '2025-08-25 13:46:55', '{\"data\": {\"data\": {\"udf1\": \"optional Data1\", \"udf2\": \"optional Data2\", \"udf3\": \"optional Data3\", \"status\": \"Completed\", \"orderId\": \"134895854\", \"transactionId\": \"\", \"creationDateTime\": \"2025-08-15T17:55:40.000000+05:30\"}, \"errors\": null, \"message\": \"Payment initiated successfully...!!!\", \"success\": true, \"exception\": null}, \"status\": true}', 'SBIN0001234'),
(7, '134895854', 4, '134895854', 1, 'payout', 9460, '100.00', '9360.00', 11, 'Payout request created', 'success', 1, 1, 'erwer', '2025-08-25 13:50:25', '2025-08-25 13:50:25', '{\"data\": {\"data\": {\"udf1\": \"optional Data1\", \"udf2\": \"optional Data2\", \"udf3\": \"optional Data3\", \"status\": \"Completed\", \"orderId\": \"134895854\", \"transactionId\": \"\", \"creationDateTime\": \"2025-08-15T17:55:40.000000+05:30\"}, \"errors\": null, \"message\": \"Payment initiated successfully...!!!\", \"success\": true, \"exception\": null}, \"status\": true}', 'SBIN0001234'),
(8, '134895854', 4, '134895854', 1, 'payout', 9360, '10.00', '9350.00', 12, 'Payout request created', 'success', 1, 2, 'Test3', '2025-08-25 13:52:59', '2025-08-25 13:52:59', '{\"data\": {\"data\": {\"status\": \"Completed\", \"orderId\": \"134895854\"}, \"message\": \"Payment initiated successfully...!!!\", \"success\": true}, \"status\": true}', 'CNRB0003020'),
(9, '134895854', 4, '134895854', 1, 'payout', 9350, '12.00', '9338.00', 13, 'Payout request created', 'success', 1, 2, 'Test3', '2025-08-25 13:53:00', '2025-08-25 13:53:00', '{\"data\": {\"data\": {\"status\": \"Completed\", \"orderId\": \"134895854\"}, \"message\": \"Payment initiated successfully...!!!\", \"success\": true}, \"status\": true}', 'CNRB0003022');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `dob` date DEFAULT NULL,
  `phone_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `gender` enum('male','female') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `gst` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `admin_id` bigint UNSIGNED DEFAULT NULL,
  `api_status` tinyint(1) NOT NULL DEFAULT '0',
  `payout_commission_in_percent` tinyint(1) NOT NULL DEFAULT '0',
  `node_bypass` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `first_name`, `last_name`, `email`, `email_verified_at`, `password`, `dob`, `phone_no`, `address`, `gender`, `status`, `remember_token`, `created_at`, `updated_at`, `gst`, `admin_id`, `api_status`, `payout_commission_in_percent`, `node_bypass`) VALUES
(1, 'admin', 'admin', 'admin', 'admin1@yopmail.com', NULL, '$2y$12$ujmdJBmG9dhzH/rGjtmeCeY/wnCkx7uCYuRaAbz7ByGDF5Gf/PDRi', NULL, '9876543210', NULL, 'male', 1, NULL, '2025-08-15 02:47:29', '2025-08-15 02:47:29', NULL, NULL, 0, 0, 0),
(4, 'usersA', 'Aman', 'Sharma', 'users@yopmail.com', NULL, '$2y$12$4xyhIU2UV81ZoWI4wf0L8eagd1BlRz5T4r4MZIoOknUsy69yKOC6W', NULL, '1212121212', NULL, NULL, 1, NULL, '2025-08-15 03:27:36', '2025-08-23 07:26:43', '0.9', NULL, 1, 0, 0),
(8, 'vishal', 'vishal', 'f', 'vishal@yopmail.com', NULL, '$2y$12$vz/f8zu.tRfiY/K2Xv9qEO4BDp6uikD3RxLxVDgvU/RbJx76XreMO', NULL, '9696969696', NULL, NULL, 1, NULL, '2025-08-23 04:24:41', '2025-08-23 04:24:41', NULL, NULL, 0, 0, 0),
(9, 'kishaan', 'kishan', 'sharma', 'kishan@yopmail.com', NULL, '$2y$12$.Q61861T0yedeyd2v7OVqeN4y1htyzn9RCJE31biAEBu7f7G/PDsS', NULL, '1234657890', NULL, NULL, 1, NULL, '2025-08-23 04:28:00', '2025-08-23 04:28:00', '123456', NULL, 1, 1, 1),
(11, 's', 'd', 'dd', 'ddd@mailintor.com', NULL, '$2y$12$EmO2AugvKrsSUMFVMO5cuu9TsryKhemTPK4i0j0Rc3x1U8MwoKhwy', NULL, '8829842472', NULL, NULL, 1, NULL, '2025-08-23 05:43:48', '2025-08-23 05:43:48', NULL, NULL, 0, 0, 0),
(12, 'new', 'new', 'new', 'new@yopmail.com', NULL, '$2y$12$Gn1FaSwZs8zo32z8KzcCs.iXE/GHcYVyame5KVuAqId0U.SSthsA6', NULL, '8829842472', NULL, NULL, 1, NULL, '2025-08-23 05:46:58', '2025-08-23 05:46:58', '9696969696', NULL, 1, 1, 0),
(13, 'c', 'f', 'f', 'ff@yopmail.com', NULL, '$2y$12$tL6uPJY9z4xcMxFEb9wPZOtKSWFWMyEis4iggqU6ad.K5.lBWanNe', NULL, '1234567899', NULL, NULL, 1, NULL, '2025-08-23 05:51:26', '2025-08-23 06:19:56', '9696969690', NULL, 1, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `user_banks`
--

CREATE TABLE `user_banks` (
  `id` bigint UNSIGNED NOT NULL,
  `admin_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `ip_address` text COLLATE utf8mb4_unicode_ci,
  `api_provider` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `max_transfer_amount` decimal(10,0) NOT NULL DEFAULT '0',
  `bank_mobile` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `max_tps` int DEFAULT '0',
  `password` text COLLATE utf8mb4_unicode_ci,
  `email` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_banks`
--

INSERT INTO `user_banks` (`id`, `admin_id`, `user_id`, `ip_address`, `api_provider`, `bank_name`, `max_transfer_amount`, `bank_mobile`, `max_tps`, `password`, `email`, `created_at`, `updated_at`) VALUES
(3, 1, 8, NULL, 'Upay', 'dd', '0', '9696969696', 0, '$2y$12$PEHEDLvPf24gXVLitJDFzeywRvNzCtjluDFoyQPA82PVsGuGdiLjW', 'dd@yopmail.com', '2025-08-23 04:24:42', '2025-08-23 04:24:42'),
(4, 1, 9, '123.12.12', 'Upay', 'Test', '10', '8585858585', 0, '$2y$12$PZBKDnIEkuLDU0jBXpqiwuavx4gXjnVa4OLz7w8tPkXfAQAvdl/T.', 'test@gmail.com', '2025-08-23 04:28:00', '2025-08-23 04:28:00'),
(5, 1, 11, NULL, 'Upay', 'dd', '100', '9696969696', 3, '$2y$12$44yUdZS0Ob/LQTlXXyJXPudYPkgFB5JnY5dJ4YhdgBaeNFtKto5xy', 'test@gmail.com', '2025-08-23 05:43:49', '2025-08-23 05:43:49'),
(6, 1, 12, '12344', 'Upay', 'ss', '10', '8585858585', 5, '$2y$12$GUX7X6WnKYG1JLQRZvZau.JQykhz6gRSZkbh7egcojKoongNn42v.', 'ss@yopmail.com', '2025-08-23 05:46:59', '2025-08-23 05:46:59'),
(7, 1, 13, '123.130.13', 'Upay', 'dd', '100', '1212122222', 10, '$2y$12$o6DJ73HeAvctrK839k8xmeAHIy/Bt4CLe1sBE24kN8ccAZY2F/tLe', 'test@gmail.com', '2025-08-23 05:51:27', '2025-08-23 06:44:03'),
(8, 1, 4, '123.12.12', 'Upay', 'HDFC', '500000', '9696969696', 50, '$2y$12$QKRN/RigSVj9J4rSqf4pwOQIkFZaJc1M3djhTtPKsXWahmp/HRp0q', 'aman@yopmail.com', '2025-08-23 07:26:30', '2025-08-23 07:26:43');

-- --------------------------------------------------------

--
-- Table structure for table `wallets`
--

CREATE TABLE `wallets` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `is_approved` int NOT NULL DEFAULT '0' COMMENT '1 for approved 0 for un approved',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `wallets`
--

INSERT INTO `wallets` (`id`, `user_id`, `amount`, `is_approved`, `created_at`, `updated_at`) VALUES
(1, 4, '9338.00', 1, '2025-08-23 13:15:57', '2025-08-25 13:53:00');

-- --------------------------------------------------------

--
-- Table structure for table `wallet_requests`
--

CREATE TABLE `wallet_requests` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `requested_user_id` bigint UNSIGNED NOT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `platform_charge` decimal(10,2) DEFAULT NULL,
  `gst` decimal(10,2) DEFAULT NULL,
  `payin_amount` decimal(10,2) DEFAULT NULL,
  `remark` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `utr_no` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('APPROVED','PENDING','DECLINED','CANCELLED','REVERTED') CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `source` enum('PPAY') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_updated` tinyint(1) NOT NULL DEFAULT '0',
  `payload` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `wallet_requests`
--

INSERT INTO `wallet_requests` (`id`, `user_id`, `requested_user_id`, `amount`, `platform_charge`, `gst`, `payin_amount`, `remark`, `utr_no`, `status`, `created_at`, `updated_at`, `source`, `is_updated`, `payload`) VALUES
(1, 4, 1, '10000.00', '150.00', '90.00', '9760.00', 'REVERTED', 'SBIN1234567891', 'REVERTED', '2025-08-23 13:15:57', '2025-08-23 13:18:18', 'PPAY', 1, NULL),
(2, 4, 1, '10000.00', '150.00', '90.00', '9760.00', 'WALLET LOAD', 'SBIN1234567892', 'APPROVED', '2025-08-23 13:17:13', '2025-08-23 13:17:26', 'PPAY', 1, NULL),
(3, 4, 1, '10000.00', NULL, NULL, NULL, 'WALLET LOAD', NULL, 'DECLINED', '2025-08-23 13:19:18', '2025-08-23 13:19:35', 'PPAY', 0, NULL);

--
-- Indexes for dumped tables
--

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
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `categories_slug_unique` (`slug`),
  ADD KEY `categories_main_category_id_foreign` (`main_category_id`);

--
-- Indexes for table `comissions_old`
--
ALTER TABLE `comissions_old`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `commissions`
--
ALTER TABLE `commissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `commissions_user_id_foreign` (`user_id`);

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
-- Indexes for table `main_categories`
--
ALTER TABLE `main_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `payouts`
--
ALTER TABLE `payouts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payouts_user_id_foreign` (`user_id`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `request_logs`
--
ALTER TABLE `request_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indexes for table `service_charges`
--
ALTER TABLE `service_charges`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `sub_categories`
--
ALTER TABLE `sub_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `sub_categories_slug_unique` (`slug`),
  ADD KEY `sub_categories_category_id_foreign` (`category_id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transactions_user_id_foreign` (`user_id`),
  ADD KEY `transactions_wallet_id_foreign` (`wallet_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `user_banks`
--
ALTER TABLE `user_banks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_banks_admin_id_foreign` (`admin_id`),
  ADD KEY `user_banks_user_id_foreign` (`user_id`);

--
-- Indexes for table `wallets`
--
ALTER TABLE `wallets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `wallets_user_id_foreign` (`user_id`);

--
-- Indexes for table `wallet_requests`
--
ALTER TABLE `wallet_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `wallet_requests_user_id_foreign` (`user_id`),
  ADD KEY `wallet_requests_requested_user_id_foreign` (`requested_user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `comissions_old`
--
ALTER TABLE `comissions_old`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `commissions`
--
ALTER TABLE `commissions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `main_categories`
--
ALTER TABLE `main_categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `payouts`
--
ALTER TABLE `payouts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `request_logs`
--
ALTER TABLE `request_logs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `service_charges`
--
ALTER TABLE `service_charges`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `sub_categories`
--
ALTER TABLE `sub_categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `user_banks`
--
ALTER TABLE `user_banks`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `wallets`
--
ALTER TABLE `wallets`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `wallet_requests`
--
ALTER TABLE `wallet_requests`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `categories_main_category_id_foreign` FOREIGN KEY (`main_category_id`) REFERENCES `main_categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `commissions`
--
ALTER TABLE `commissions`
  ADD CONSTRAINT `commissions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payouts`
--
ALTER TABLE `payouts`
  ADD CONSTRAINT `payouts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sub_categories`
--
ALTER TABLE `sub_categories`
  ADD CONSTRAINT `sub_categories_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transactions_wallet_id_foreign` FOREIGN KEY (`wallet_id`) REFERENCES `wallets` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_banks`
--
ALTER TABLE `user_banks`
  ADD CONSTRAINT `user_banks_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_banks_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `wallets`
--
ALTER TABLE `wallets`
  ADD CONSTRAINT `wallets_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
