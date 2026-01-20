-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3304
-- Generation Time: Jan 20, 2026 at 12:33 PM
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
-- Database: `pos_admin`
--

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
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone_country_code` varchar(5) DEFAULT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `name`, `email`, `phone_country_code`, `phone_number`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Prof. Jewell Bins Sr.', 'aaliyah15@example.net', '+855', '367627658', 'active', '2026-01-18 19:58:31', '2026-01-18 19:58:31'),
(2, 'Rafaela Nienow', 'darryl07@example.org', '+855', '116849365', 'active', '2026-01-18 19:58:31', '2026-01-18 19:58:31'),
(3, 'Sylvan Brekke DDS', 'pokeefe@example.org', '+855', '717846252', 'active', '2026-01-18 19:58:31', '2026-01-18 19:58:31'),
(4, 'Pauline Ortiz', 'hkub@example.com', '+855', '988628495', 'active', '2026-01-18 19:58:31', '2026-01-18 19:58:31'),
(5, 'Baby Schuppe', 'heidenreich.leopold@example.org', '+855', '038448142', 'active', '2026-01-18 19:58:31', '2026-01-18 19:58:31'),
(6, 'Dr. Joey Spencer I', 'marilie.veum@example.net', '+855', '863992197', 'active', '2026-01-18 19:58:31', '2026-01-18 19:58:31'),
(7, 'Rachael Von', 'mollie.morissette@example.org', '+855', '331680430', 'active', '2026-01-18 19:58:31', '2026-01-18 19:58:31'),
(8, 'Elenora Feil', 'barton65@example.org', '+855', '095297868', 'active', '2026-01-18 19:58:31', '2026-01-18 19:58:31'),
(9, 'Mr. Crawford Mohr MD', 'darryl02@example.org', '+855', '527439710', 'active', '2026-01-18 19:58:31', '2026-01-18 19:58:31'),
(10, 'Ms. Vella Weimann II', 'sbeatty@example.org', '+855', '912625712', 'active', '2026-01-18 19:58:31', '2026-01-18 19:58:31'),
(11, 'Pat Mayer MD', 'gpouros@example.net', '+855', '686309913', 'active', '2026-01-18 19:58:31', '2026-01-18 19:58:31'),
(12, 'Dorris Franecki II', 'stokes.lonny@example.com', '+855', '364624229', 'active', '2026-01-18 19:58:31', '2026-01-18 19:58:31'),
(13, 'Orlo Howell', 'dena.prohaska@example.org', '+855', '464391998', 'active', '2026-01-18 19:58:31', '2026-01-18 19:58:31'),
(14, 'Stephany Jast', 'hmccullough@example.org', '+855', '531151558', 'active', '2026-01-18 19:58:31', '2026-01-18 19:58:31'),
(15, 'Prof. Sylvester Prohaska Sr.', 'soledad34@example.com', '+855', '264891955', 'active', '2026-01-18 19:58:31', '2026-01-18 19:58:31'),
(16, 'Bertrand Streich PhD', 'kris.eli@example.net', '+855', '863493326', 'active', '2026-01-18 19:58:31', '2026-01-18 19:58:31'),
(17, 'Earnestine Mohr', 'lon.heller@example.net', '+855', '768979806', 'active', '2026-01-18 19:58:31', '2026-01-18 19:58:31'),
(18, 'Citlalli Bayer', 'oconner.desiree@example.net', '+855', '135188616', 'active', '2026-01-18 19:58:31', '2026-01-18 19:58:31'),
(19, 'Garrett Klein', 'flatley.rudolph@example.org', '+855', '323510238', 'active', '2026-01-18 19:58:31', '2026-01-18 19:58:31'),
(20, 'Lempi Veum III', 'prenner@example.net', '+855', '247321837', 'active', '2026-01-18 19:58:31', '2026-01-18 19:58:31');

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
-- Table structure for table `inventories`
--

CREATE TABLE `inventories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `item_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 0,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `inventories`
--

INSERT INTO `inventories` (`id`, `item_id`, `quantity`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 26, 76, NULL, '2026-01-18 19:58:31', '2026-01-18 20:17:51'),
(2, 27, 10, NULL, '2026-01-18 19:58:31', '2026-01-18 19:58:31'),
(3, 28, 12, NULL, '2026-01-18 19:58:31', '2026-01-18 19:58:31'),
(4, 29, 40, NULL, '2026-01-18 19:58:31', '2026-01-18 20:17:51'),
(5, 30, 74, NULL, '2026-01-18 19:58:31', '2026-01-18 19:58:31'),
(6, 31, 25, NULL, '2026-01-18 19:58:31', '2026-01-19 06:17:15'),
(7, 32, 16, NULL, '2026-01-18 19:58:31', '2026-01-18 19:58:31'),
(8, 33, 86, NULL, '2026-01-18 19:58:31', '2026-01-18 20:18:44'),
(9, 34, 68, NULL, '2026-01-18 19:58:31', '2026-01-19 06:17:15'),
(10, 35, 38, NULL, '2026-01-18 19:58:31', '2026-01-18 20:18:44'),
(11, 36, 19, NULL, '2026-01-18 19:58:31', '2026-01-18 19:58:31'),
(12, 37, 59, NULL, '2026-01-18 19:58:31', '2026-01-18 19:58:31'),
(13, 38, 85, NULL, '2026-01-18 19:58:31', '2026-01-18 19:58:31'),
(14, 39, 54, NULL, '2026-01-18 19:58:31', '2026-01-18 19:58:31'),
(15, 40, 28, NULL, '2026-01-18 19:58:31', '2026-01-18 19:58:31'),
(16, 41, 42, NULL, '2026-01-18 19:58:31', '2026-01-18 19:58:31'),
(17, 42, 18, NULL, '2026-01-18 19:58:31', '2026-01-18 19:58:31'),
(18, 43, 42, NULL, '2026-01-18 19:58:31', '2026-01-18 19:58:31'),
(19, 44, 34, NULL, '2026-01-18 19:58:31', '2026-01-18 19:58:31'),
(20, 45, 75, NULL, '2026-01-18 19:58:31', '2026-01-18 19:58:31'),
(21, 46, 16, NULL, '2026-01-18 19:58:31', '2026-01-18 19:58:31'),
(22, 47, 64, NULL, '2026-01-18 19:58:31', '2026-01-18 20:20:30'),
(23, 48, 45, NULL, '2026-01-18 19:58:31', '2026-01-18 20:20:30'),
(24, 49, 62, NULL, '2026-01-18 19:58:31', '2026-01-18 19:58:31'),
(25, 50, 45, NULL, '2026-01-18 19:58:31', '2026-01-18 19:58:31');

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `sku` varchar(255) NOT NULL,
  `original_price` decimal(8,2) NOT NULL,
  `selling_price` decimal(8,2) NOT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`id`, `image`, `name`, `sku`, `original_price`, `selling_price`, `status`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, NULL, 'nam minus', 'NKCNG9OOVB', 8.93, 29.62, 'active', '2026-01-18 20:15:58', '2026-01-18 19:58:31', '2026-01-18 20:15:58'),
(2, NULL, 'sunt sed', '9L5UMW9446', 13.20, 13.18, 'active', NULL, '2026-01-18 19:58:31', '2026-01-18 19:58:31'),
(3, NULL, 'id dolores', 'LCDXLTWA7K', 13.65, 49.40, 'active', NULL, '2026-01-18 19:58:31', '2026-01-18 19:58:31'),
(4, NULL, 'sed ducimus', 'IKEHZUC0RO', 13.69, 36.55, 'active', NULL, '2026-01-18 19:58:31', '2026-01-18 19:58:31'),
(5, NULL, 'tempora fugiat', '9OG3LTNBGM', 23.68, 43.44, 'active', NULL, '2026-01-18 19:58:31', '2026-01-18 19:58:31'),
(6, NULL, 'quis sint', 'YTHS3PE38G', 28.61, 33.59, 'active', '2026-01-18 20:11:11', '2026-01-18 19:58:31', '2026-01-18 20:11:11'),
(7, NULL, 'eius maxime', 'OLKCAA2N3E', 28.25, 28.01, 'active', NULL, '2026-01-18 19:58:31', '2026-01-18 19:58:31'),
(8, NULL, 'tempore temporibus', 'WRYQMUHZIS', 10.01, 28.07, 'active', NULL, '2026-01-18 19:58:31', '2026-01-18 19:58:31'),
(9, NULL, 'quo commodi', 'CSSRPUWKII', 16.86, 44.26, 'active', NULL, '2026-01-18 19:58:31', '2026-01-18 19:58:31'),
(10, NULL, 'nesciunt dolor', 'SHVJ8Q0F10', 12.01, 27.30, 'active', NULL, '2026-01-18 19:58:31', '2026-01-18 19:58:31'),
(11, NULL, 'amet quia', 'S4BYF96RDL', 22.88, 22.22, 'active', NULL, '2026-01-18 19:58:31', '2026-01-18 19:58:31'),
(12, NULL, 'porro illo', '9IQ1K5INLE', 18.51, 27.81, 'active', NULL, '2026-01-18 19:58:31', '2026-01-18 19:58:31'),
(13, NULL, 'nesciunt maiores', 'ZAKYOADTIH', 26.29, 49.98, 'active', NULL, '2026-01-18 19:58:31', '2026-01-18 19:58:31'),
(14, NULL, 'autem voluptates', 'GSPWDAPYWX', 6.69, 18.56, 'active', NULL, '2026-01-18 19:58:31', '2026-01-18 19:58:31'),
(15, NULL, 'incidunt et', 'UMH37B8MBE', 10.95, 44.15, 'active', NULL, '2026-01-18 19:58:31', '2026-01-18 19:58:31'),
(16, NULL, 'incidunt natus', 'VZX9BBV3TF', 12.65, 25.61, 'active', NULL, '2026-01-18 19:58:31', '2026-01-18 19:58:31'),
(17, NULL, 'id quas', '5U40VK1XDC', 12.72, 46.83, 'active', NULL, '2026-01-18 19:58:31', '2026-01-18 19:58:31'),
(18, NULL, 'illo autem', '4FBKH2H3VP', 23.78, 28.05, 'active', NULL, '2026-01-18 19:58:31', '2026-01-18 19:58:31'),
(19, NULL, 'et quia', 'GBGSSLGSIO', 21.53, 12.62, 'active', NULL, '2026-01-18 19:58:31', '2026-01-18 19:58:31'),
(20, NULL, 'quisquam ut', 'NQHFESZV6M', 17.32, 33.68, 'active', NULL, '2026-01-18 19:58:31', '2026-01-18 19:58:31'),
(21, NULL, 'enim aut', 'JLATGLXIME', 21.48, 39.92, 'active', NULL, '2026-01-18 19:58:31', '2026-01-18 19:58:31'),
(22, NULL, 'numquam aut', '3NHNVTM41G', 5.15, 34.36, 'active', NULL, '2026-01-18 19:58:31', '2026-01-18 19:58:31'),
(23, NULL, 'rerum nam', '7MIAN18OAN', 11.44, 37.91, 'active', NULL, '2026-01-18 19:58:31', '2026-01-18 19:58:31'),
(24, NULL, 'recusandae itaque', '9MWQEZINGF', 9.10, 23.81, 'active', NULL, '2026-01-18 19:58:31', '2026-01-18 19:58:31'),
(25, NULL, 'quas qui', 'IU8RUVYLUS', 20.77, 27.19, 'active', NULL, '2026-01-18 19:58:31', '2026-01-18 19:58:31'),
(26, NULL, 'nostrum atque', 'IMUZGMVWBA', 22.83, 30.61, 'active', NULL, '2026-01-18 19:58:31', '2026-01-18 19:58:31'),
(27, NULL, 'debitis et', 'SIRK8WPGYA', 17.96, 13.16, 'active', NULL, '2026-01-18 19:58:31', '2026-01-18 19:58:31'),
(28, NULL, 'asperiores iusto', 'A6EZU150Z6', 28.49, 33.98, 'active', NULL, '2026-01-18 19:58:31', '2026-01-18 19:58:31'),
(29, NULL, 'dicta eius', 'AQ0D8HWNQJ', 16.51, 23.55, 'active', NULL, '2026-01-18 19:58:31', '2026-01-18 19:58:31'),
(30, NULL, 'maiores ea', 'GASXFENEDT', 6.12, 21.16, 'active', NULL, '2026-01-18 19:58:31', '2026-01-18 19:58:31'),
(31, NULL, 'ut vel', '5CY6OT5ZVA', 10.98, 19.71, 'active', NULL, '2026-01-18 19:58:31', '2026-01-18 19:58:31'),
(32, NULL, 'repellendus atque', 'E07LHE4RKB', 15.31, 15.15, 'active', NULL, '2026-01-18 19:58:31', '2026-01-18 19:58:31'),
(33, NULL, 'et qui', '92K0YOGVAL', 22.55, 41.12, 'active', NULL, '2026-01-18 19:58:31', '2026-01-18 19:58:31'),
(34, NULL, 'consequatur aut', '5YNLSL8TLE', 13.09, 47.46, 'active', NULL, '2026-01-18 19:58:31', '2026-01-18 19:58:31'),
(35, NULL, 'est fuga', 'WZSF3Y0KSO', 16.53, 26.18, 'active', NULL, '2026-01-18 19:58:31', '2026-01-18 19:58:31'),
(36, NULL, 'et possimus', 'LSNLKUZQFE', 26.03, 12.07, 'active', NULL, '2026-01-18 19:58:31', '2026-01-18 19:58:31'),
(37, NULL, 'fugit ullam', '4M76N7JZSU', 17.95, 10.62, 'active', NULL, '2026-01-18 19:58:31', '2026-01-18 19:58:31'),
(38, NULL, 'sed eos', 'LXOLBADTNI', 13.54, 42.34, 'active', NULL, '2026-01-18 19:58:31', '2026-01-18 19:58:31'),
(39, NULL, 'accusantium et', 'OEAOXSOEJR', 16.97, 23.36, 'active', NULL, '2026-01-18 19:58:31', '2026-01-18 19:58:31'),
(40, NULL, 'ut odit', 'F43M2VP9N5', 5.65, 19.49, 'active', NULL, '2026-01-18 19:58:31', '2026-01-18 19:58:31'),
(41, NULL, 'at molestiae', 'Z8IQZRK2OT', 26.78, 10.06, 'active', NULL, '2026-01-18 19:58:31', '2026-01-18 19:58:31'),
(42, NULL, 'et ut', 'IG63RRNCLB', 9.24, 27.54, 'active', NULL, '2026-01-18 19:58:31', '2026-01-18 19:58:31'),
(43, NULL, 'ut et', '69KQ5BUUPB', 5.95, 19.21, 'active', NULL, '2026-01-18 19:58:31', '2026-01-18 19:58:31'),
(44, NULL, 'temporibus nihil', 'OCFTQHFPWZ', 21.35, 12.52, 'active', NULL, '2026-01-18 19:58:31', '2026-01-18 19:58:31'),
(45, NULL, 'omnis aliquid', 'OBQHAI6O28', 16.36, 19.93, 'active', NULL, '2026-01-18 19:58:31', '2026-01-18 19:58:31'),
(46, NULL, 'quisquam similique', 'NMDD8E7YRY', 26.52, 18.02, 'active', NULL, '2026-01-18 19:58:31', '2026-01-18 19:58:31'),
(47, NULL, 'esse dolor', 'VYTHUNDSPR', 6.49, 42.91, 'active', NULL, '2026-01-18 19:58:31', '2026-01-18 19:58:31'),
(48, NULL, 'enim quasi', '5PYV5W9Z3A', 16.13, 45.34, 'active', NULL, '2026-01-18 19:58:31', '2026-01-18 19:58:31'),
(49, NULL, 'id quo', 'U1QXQ0M4LN', 8.86, 14.57, 'active', NULL, '2026-01-18 19:58:31', '2026-01-18 19:58:31'),
(50, NULL, 'vero vel', 'INKYZEZIJO', 24.81, 29.10, 'active', NULL, '2026-01-18 19:58:31', '2026-01-18 19:58:31');

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
(4, '2025_09_22_145432_add_two_factor_columns_to_users_table', 1),
(5, '2026_01_10_074842_create_items_table', 1),
(6, '2026_01_10_074853_create_inventories_table', 1),
(7, '2026_01_10_074902_create_customers_table', 1),
(8, '2026_01_10_074916_create_payment_methods_table', 1),
(9, '2026_01_10_074925_create_sales_table', 1),
(10, '2026_01_10_074935_create_sales_items_table', 1),
(11, '2026_01_19_113844_create_permission_tables', 2);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_methods`
--

CREATE TABLE `payment_methods` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payment_methods`
--

INSERT INTO `payment_methods` (`id`, `name`, `description`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'Cash', 'Non alias est ut quod. Corrupti ratione in non et natus et minus nulla. Iure sint quis iste repellendus.', NULL, '2026-01-18 19:58:31', '2026-01-18 20:05:07'),
(2, 'Mobile Payment', 'Inventore iure quo eaque dolorem suscipit magni. Ut eveniet dolores consectetur esse consectetur officiis.', NULL, '2026-01-18 19:58:31', '2026-01-18 19:58:31'),
(3, 'Card', 'Et quo aut qui atque alias molestias quis amet. Cum nemo modi nobis sed. Sit mollitia veniam minima perferendis et sed distinctio. Voluptatem placeat velit sit officiis quo.', NULL, '2026-01-18 19:58:31', '2026-01-18 19:58:31');

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'web', '2026-01-19 04:41:36', '2026-01-19 04:41:36'),
(2, 'manager', 'web', '2026-01-19 04:41:36', '2026-01-19 04:41:36'),
(3, 'cashier', 'web', '2026-01-19 04:41:36', '2026-01-19 04:41:36');

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `customer_id` bigint(20) UNSIGNED DEFAULT NULL,
  `payment_method_id` bigint(20) UNSIGNED DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `subtotal` decimal(10,2) NOT NULL DEFAULT 0.00,
  `tax` decimal(10,2) DEFAULT NULL,
  `change_amount` decimal(10,2) DEFAULT NULL,
  `total` decimal(8,2) NOT NULL,
  `paid_amount` decimal(8,2) NOT NULL,
  `discount` decimal(8,2) NOT NULL DEFAULT 0.00,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`id`, `customer_id`, `payment_method_id`, `user_id`, `subtotal`, `tax`, `change_amount`, `total`, `paid_amount`, `discount`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 18, 3, 12, 125.89, 18.88, 5.23, 144.77, 150.00, 0.00, NULL, '2026-01-18 20:17:51', '2026-01-19 00:31:45'),
(2, NULL, 3, 12, 67.30, 10.10, 2.61, 77.40, 80.00, 0.00, NULL, '2026-01-18 20:18:44', '2026-01-18 20:18:44'),
(3, NULL, 2, 12, 88.25, 13.24, 0.51, 101.49, 102.00, 0.00, NULL, '2026-01-18 20:20:30', '2026-01-18 20:20:30'),
(4, NULL, 2, 11, 67.17, 10.08, 0.75, 77.25, 78.00, 0.00, NULL, '2026-01-19 06:17:15', '2026-01-19 06:17:15');

-- --------------------------------------------------------

--
-- Table structure for table `sales_items`
--

CREATE TABLE `sales_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sale_id` bigint(20) UNSIGNED NOT NULL,
  `item_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL,
  `selling_price` decimal(8,2) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sales_items`
--

INSERT INTO `sales_items` (`id`, `sale_id`, `item_id`, `quantity`, `selling_price`, `total`, `created_at`, `updated_at`) VALUES
(1, 1, 26, 2, 30.61, 61.22, '2026-01-18 20:17:51', '2026-01-18 20:17:51'),
(2, 1, 29, 1, 23.55, 23.55, '2026-01-18 20:17:51', '2026-01-18 20:17:51'),
(3, 1, 33, 1, 41.12, 41.12, '2026-01-18 20:17:51', '2026-01-18 20:17:51'),
(4, 2, 33, 1, 41.12, 41.12, '2026-01-18 20:18:44', '2026-01-18 20:18:44'),
(5, 2, 35, 1, 26.18, 26.18, '2026-01-18 20:18:44', '2026-01-18 20:18:44'),
(6, 3, 48, 1, 45.34, 45.34, '2026-01-18 20:20:30', '2026-01-18 20:20:30'),
(7, 3, 47, 1, 42.91, 42.91, '2026-01-18 20:20:30', '2026-01-18 20:20:30'),
(8, 4, 31, 1, 19.71, 19.71, '2026-01-19 06:17:15', '2026-01-19 06:17:15'),
(9, 4, 34, 1, 47.46, 47.46, '2026-01-19 06:17:15', '2026-01-19 06:17:15');

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

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `two_factor_secret` text DEFAULT NULL,
  `two_factor_recovery_codes` text DEFAULT NULL,
  `two_factor_confirmed_at` timestamp NULL DEFAULT NULL,
  `role` enum('admin','manager','cashier') NOT NULL DEFAULT 'cashier',
  `status` enum('active','suspended') NOT NULL DEFAULT 'active',
  `remember_token` varchar(100) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `phone`, `email_verified_at`, `password`, `two_factor_secret`, `two_factor_recovery_codes`, `two_factor_confirmed_at`, `role`, `status`, `remember_token`, `deleted_at`, `created_at`, `updated_at`) VALUES
(11, 'Cashier', 'cashier@gmail.com', NULL, NULL, '$2y$12$tKNkiZ2JKZ7VNxB5QeHxIOTQrS2m2OTxaj6B2tGbqhGZea8JQWfce', NULL, NULL, NULL, 'cashier', 'active', NULL, NULL, '2026-01-18 19:59:57', '2026-01-18 19:59:57'),
(12, 'Admin', 'admin@gmail.com', NULL, NULL, '$2y$12$sDQyP8n/gOksnjpj2r/uzuoz1ZGX3bKboyDtcAhcYxD2NBTcjGfHa', NULL, NULL, NULL, 'admin', 'active', NULL, NULL, '2026-01-18 20:00:33', '2026-01-18 20:00:33'),
(13, 'Manager', 'manager@gmail.com', NULL, NULL, '$2y$12$YRr8UiMVVMQnKzryth.gcOp2WEGIaBcwQD/liG4/sjCFYZ5wFXd.m', NULL, NULL, NULL, 'manager', 'active', NULL, NULL, '2026-01-19 00:44:28', '2026-01-19 00:44:28');

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
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `customers_email_unique` (`email`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `inventories`
--
ALTER TABLE `inventories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `inventories_item_id_foreign` (`item_id`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `items_sku_unique` (`sku`);

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
-- Indexes for table `payment_methods`
--
ALTER TABLE `payment_methods`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

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
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sales_customer_id_foreign` (`customer_id`),
  ADD KEY `sales_payment_method_id_foreign` (`payment_method_id`),
  ADD KEY `sales_user_id_foreign` (`user_id`);

--
-- Indexes for table `sales_items`
--
ALTER TABLE `sales_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sales_items_sale_id_foreign` (`sale_id`),
  ADD KEY `sales_items_item_id_foreign` (`item_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

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
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inventories`
--
ALTER TABLE `inventories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `payment_methods`
--
ALTER TABLE `payment_methods`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `sales_items`
--
ALTER TABLE `sales_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `inventories`
--
ALTER TABLE `inventories`
  ADD CONSTRAINT `inventories_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE;

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
-- Constraints for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sales`
--
ALTER TABLE `sales`
  ADD CONSTRAINT `sales_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `sales_payment_method_id_foreign` FOREIGN KEY (`payment_method_id`) REFERENCES `payment_methods` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `sales_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `sales_items`
--
ALTER TABLE `sales_items`
  ADD CONSTRAINT `sales_items_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sales_items_sale_id_foreign` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
