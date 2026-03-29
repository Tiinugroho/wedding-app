-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 29, 2026 at 01:47 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.4.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `wedding_app`
--

-- --------------------------------------------------------

--
-- Table structure for table `banks`
--

CREATE TABLE `banks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `banks`
--

INSERT INTO `banks` (`id`, `name`, `logo`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'BCA', 'https://upload.wikimedia.org/wikipedia/commons/5/5c/Bank_Central_Asia.svg', 1, '2026-03-20 23:42:37', '2026-03-20 23:42:37'),
(2, 'Mandiri', 'https://upload.wikimedia.org/wikipedia/commons/a/ad/Bank_Mandiri_logo_2016.svg', 1, '2026-03-20 23:42:37', '2026-03-20 23:42:37'),
(3, 'BNI', 'https://upload.wikimedia.org/wikipedia/id/5/55/BNI_logo.svg', 1, '2026-03-20 23:42:37', '2026-03-20 23:42:37'),
(4, 'BRI', 'https://upload.wikimedia.org/wikipedia/commons/9/9e/BRI_2020.svg', 1, '2026-03-20 23:42:37', '2026-03-20 23:42:37'),
(5, 'DANA', 'https://upload.wikimedia.org/wikipedia/commons/7/72/Logo_dana_blue.svg', 1, '2026-03-20 23:42:37', '2026-03-20 23:42:37'),
(6, 'OVO', 'https://upload.wikimedia.org/wikipedia/commons/e/e1/Logo_OVO.svg', 1, '2026-03-20 23:42:37', '2026-03-20 23:42:37'),
(7, 'GoPay', 'https://upload.wikimedia.org/wikipedia/commons/8/86/Gopay_logo.svg', 1, '2026-03-20 23:42:37', '2026-03-20 23:42:37'),
(8, 'ShopeePay', 'https://upload.wikimedia.org/wikipedia/commons/f/fe/ShopeePay_Logo.png', 1, '2026-03-20 23:42:37', '2026-03-20 23:42:37');

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
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `created_at`, `updated_at`) VALUES
(1, 'Modern Elegant', 'modern-elegant', '2026-03-20 23:42:37', '2026-03-20 23:42:37'),
(2, 'Minimalist', 'minimalist', '2026-03-20 23:42:37', '2026-03-20 23:42:37');

-- --------------------------------------------------------

--
-- Table structure for table `digital_gifts`
--

CREATE TABLE `digital_gifts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `invitation_id` bigint(20) UNSIGNED NOT NULL,
  `provider_name` varchar(255) NOT NULL,
  `account_number` varchar(255) NOT NULL,
  `account_name` varchar(255) NOT NULL,
  `qr_code_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
-- Table structure for table `galleries`
--

CREATE TABLE `galleries` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `invitation_id` bigint(20) UNSIGNED NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `type` enum('photo','video') NOT NULL DEFAULT 'photo',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `guests`
--

CREATE TABLE `guests` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `invitation_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `phone_number` varchar(255) DEFAULT NULL,
  `slug_name` varchar(255) NOT NULL,
  `qr_code` varchar(255) DEFAULT NULL,
  `is_present` tinyint(1) NOT NULL DEFAULT 0,
  `is_blasted` tinyint(1) NOT NULL DEFAULT 0,
  `blasted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invitations`
--

CREATE TABLE `invitations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `template_id` bigint(20) UNSIGNED NOT NULL,
  `music_id` bigint(20) UNSIGNED DEFAULT NULL,
  `slug` varchar(255) NOT NULL,
  `referral_code` varchar(255) DEFAULT NULL,
  `status` enum('draft','unpaid','active','expired') NOT NULL DEFAULT 'draft',
  `visits_count` int(11) NOT NULL DEFAULT 0,
  `expires_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `invitations`
--

INSERT INTO `invitations` (`id`, `user_id`, `template_id`, `music_id`, `slug`, `referral_code`, `status`, `visits_count`, `expires_at`, `created_at`, `updated_at`) VALUES
(1, 3, 2, 6, 'jati-nabila', NULL, 'draft', 0, NULL, '2026-03-20 23:44:38', '2026-03-20 23:48:15');

-- --------------------------------------------------------

--
-- Table structure for table `invitation_details`
--

CREATE TABLE `invitation_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `invitation_id` bigint(20) UNSIGNED NOT NULL,
  `content` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`content`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `invitation_details`
--

INSERT INTO `invitation_details` (`id`, `invitation_id`, `content`, `created_at`, `updated_at`) VALUES
(1, 1, '{\"groom_photo\":\"profiles\\/1\\/c878Mze5kT0vm5aQ14gAloh3lYReGG9nWSudtpGV.jpg\",\"bride_photo\":\"profiles\\/1\\/URUXYDaybppkWwsJlCgCbYc3rW85ERv4YHTRiOnT.jpg\",\"groom_name\":\"Muhammad Jati Nugroho\",\"groom_nickname\":\"Jati\",\"groom_parents\":\"Putra dari Bapak Sunaryo (Alm) & Ibu Lusiani\",\"groom_ig\":null,\"bride_name\":\"Nabila Ramadhina\",\"bride_nickname\":\"Nabila\",\"bride_parents\":\"Putri dari Bapak Budianto dan Ibu Fatiyeni Yensi\",\"bride_ig\":null,\"turut_mengundang_groom\":\"Keluarga Besar Bpk. Kakek\\r\\nKeluarga Besar Ibu. Nenek\\r\\nPaman & Bibi\",\"turut_mengundang_bride\":\"Keluarga Besar Bpk. Kakek\\r\\nKeluarga Besar Ibu. Nenek\\r\\nPaman & Bibi\",\"akad_date\":\"2027-04-06\",\"akad_time\":\"08:00 WIB - Selesai\",\"akad_location\":\"Aula Gedung Wiratama Korem Wira Bima\",\"akad_address\":\"Jl. Sisingamangaraja\",\"akad_map\":\"https:\\/\\/www.google.com\\/maps\\/place\\/Aula+Gedung+Wiratama+Korem+Wira+Bima\\/@0.5285336,101.4523553,17z\\/data=!3m1!4b1!4m6!3m5!1s0x31d5ac1698619d75:0x33448cd4da2002bf!8m2!3d0.5285336!4d101.4523553!16s%2Fg%2F11g8w92fss?entry=ttu&g_ep=EgoyMDI2MDMxMC4wIKXMDSoASAFQAw%3D%3D\",\"resepsi_date\":\"2027-04-06\",\"resepsi_time\":\"11:00 WIB - 16:00 WIB\",\"resepsi_location\":\"Aula Gedung Wiratama Korem Wira Bima\",\"resepsi_address\":\"Jl. Sisingamangaraja\",\"resepsi_map\":\"https:\\/\\/www.google.com\\/maps\\/place\\/Aula+Gedung+Wiratama+Korem+Wira+Bima\\/@0.5285336,101.4523553,17z\\/data=!3m1!4b1!4m6!3m5!1s0x31d5ac1698619d75:0x33448cd4da2002bf!8m2!3d0.5285336!4d101.4523553!16s%2Fg%2F11g8w92fss?entry=ttu&g_ep=EgoyMDI2MDMxMC4wIKXMDSoASAFQAw%3D%3D\",\"enable_dresscode\":true,\"dresscode\":\"Formal\\/Biru\",\"enable_health_protocol\":true,\"love_stories\":[{\"year\":\"April 2018\",\"title\":\"Testing 1\",\"description\":\"Lorem ipsum dolor sit amet consectetur adipiscing elit quisque faucibus ex sapien vitae pellentesque sem placerat in id cursus mi.\",\"image\":\"lovestories\\/1\\/SkBEzWgm2cRdx8n1WoAskBbSsL2y5bBTVMzmAUIh.jpg\"},{\"year\":\"Mei 2022\",\"title\":\"Testing 2\",\"description\":\"Lorem ipsum dolor sit amet consectetur adipiscing elit quisque faucibus ex sapien vitae pellentesque sem placerat in id cursus mi.\",\"image\":\"lovestories\\/1\\/MmrBUb3gxEujmFp10nbXH0P55Kid717G0mldqtYQ.jpg\"}],\"banks\":[{\"name\":\"DANA\",\"account_name\":\"Muhammad Jati Nugroho\",\"account_number\":\"12213123123\"},{\"name\":\"BNI\",\"account_name\":\"Nabila Ramadhina\",\"account_number\":\"1231232133\"}]}', '2026-03-20 23:44:38', '2026-03-21 01:03:22');

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
(4, '2026_03_11_170432_create_categories_table', 1),
(5, '2026_03_11_170501_create_templates_table', 1),
(6, '2026_03_11_170519_create_musics_table', 1),
(7, '2026_03_11_170539_create_invitations_table', 1),
(8, '2026_03_11_170557_create_invitation_details_table', 1),
(9, '2026_03_11_170612_create_galleries_table', 1),
(10, '2026_03_11_170630_create_digital_gifts_table', 1),
(11, '2026_03_11_170642_create_guests_table', 1),
(12, '2026_03_11_170700_create_wishes_rsvps_table', 1),
(13, '2026_03_11_170709_create_packages_table', 1),
(14, '2026_03_11_170717_create_orders_table', 1),
(15, '2026_03_11_170739_create_payments_table', 1),
(16, '2026_03_20_153843_create_banks_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `musics`
--

CREATE TABLE `musics` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `category` varchar(255) DEFAULT 'Umum',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `musics`
--

INSERT INTO `musics` (`id`, `title`, `file_path`, `category`, `created_at`, `updated_at`) VALUES
(1, 'Janji Suci - Yovie & Nuno', NULL, 'Musik Indonesia', '2026-03-20 23:42:37', '2026-03-20 23:42:37'),
(2, 'Gamelan Jawa Wedding (Panggih)', NULL, 'Musik Traditional', '2026-03-20 23:42:37', '2026-03-20 23:42:37'),
(3, 'Kiroro - Mirai e', NULL, 'Musik Jepang', '2026-03-20 23:42:37', '2026-03-20 23:42:37'),
(4, 'Canon in D - Pachelbel (Piano)', NULL, 'Musik Instrumental', '2026-03-20 23:42:37', '2026-03-20 23:42:37'),
(5, 'Barakallah - Maher Zain', NULL, 'Musik Islami', '2026-03-20 23:42:37', '2026-03-20 23:42:37'),
(6, 'A Thousand Years - Christina Perri', NULL, 'Musik Barat', '2026-03-20 23:42:37', '2026-03-20 23:42:37'),
(7, 'Beautiful In White - Westlife', NULL, 'Musik Barat', '2026-03-20 23:42:37', '2026-03-20 23:42:37'),
(8, 'Marry You - Bruno Mars', NULL, 'Musik Celebration', '2026-03-20 23:42:37', '2026-03-20 23:42:37');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_number` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `invitation_id` bigint(20) UNSIGNED NOT NULL,
  `amount` bigint(20) NOT NULL,
  `snap_token` varchar(255) DEFAULT NULL,
  `status` enum('pending','success','failed','expired') NOT NULL DEFAULT 'pending',
  `package_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `order_number`, `user_id`, `invitation_id`, `amount`, `snap_token`, `status`, `package_id`, `created_at`, `updated_at`) VALUES
(1, 'INV-1774075478DTYK3', 3, 1, 149000, NULL, 'pending', 3, '2026-03-20 23:44:38', '2026-03-20 23:44:38');

-- --------------------------------------------------------

--
-- Table structure for table `packages`
--

CREATE TABLE `packages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` bigint(20) NOT NULL,
  `description` text DEFAULT NULL,
  `original_price` bigint(20) DEFAULT NULL,
  `features` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`features`)),
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `packages`
--

INSERT INTO `packages` (`id`, `name`, `price`, `description`, `original_price`, `features`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'BASIC', 49000, 'Paket hemat untuk kebutuhan undangan digital yang simpel.', NULL, '{\"display\":{\"included\":[\"Masa Aktif 1 Bulan\",\"Galeri 5 Foto\",\"Bisa Input 2 Acara\"],\"excluded\":[\"Fitur Love Story\",\"Kado Digital \\/ Amplop\",\"Video Galeri\"]},\"logic\":{\"event_limit\":2,\"gallery_limit\":5,\"has_love_story\":false,\"has_digital_gift\":false,\"has_video\":false}}', 1, '2026-03-20 23:42:37', '2026-03-20 23:42:37'),
(2, 'PLATINUM', 76000, 'Paket terpopuler dengan fitur interaktif lengkap untuk tamu.', 149999, '{\"display\":{\"included\":[\"Masa Aktif 3 Bulan\",\"Galeri 9 Foto\",\"Fitur Love Story\",\"Kado Digital \\/ Amplop\"],\"excluded\":[\"Video Galeri\"]},\"logic\":{\"event_limit\":3,\"gallery_limit\":9,\"has_love_story\":true,\"has_digital_gift\":true,\"has_video\":false}}', 1, '2026-03-20 23:42:37', '2026-03-20 23:42:37'),
(3, 'PRIORITY', 149000, 'Masa aktif tanpa batas dengan prioritas layanan khusus.', NULL, '{\"display\":{\"included\":[\"Masa Aktif Selamanya\",\"Galeri 20 Foto & 2 Video\",\"Semua Fitur Terbuka\"],\"excluded\":[]},\"logic\":{\"event_limit\":5,\"gallery_limit\":20,\"has_love_story\":true,\"has_digital_gift\":true,\"has_video\":true}}', 1, '2026-03-20 23:42:37', '2026-03-20 23:42:37');

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
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `transaction_id` varchar(255) NOT NULL,
  `payment_type` varchar(255) NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`payload`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
('3paqHWu3P4GpK7wb3g66wwiBf6y2lBLxkajjei4O', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoic24yR21jZlVJTlJTNjRCU3NsQlNrOFAwYUVGVkRPM2RSQ2RUeVl6MyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDI6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9hdXRoL2dvb2dsZS9yZWRpcmVjdCI7czo1OiJyb3V0ZSI7czoxMjoiZ29vZ2xlLmxvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1OiJzdGF0ZSI7czo0MDoiSW1mWUZtcDRBYUxWcHhmZHllWE1lWW13cWZaNnhGTzB2dGhaZkl0VSI7fQ==', 1774790989),
('OTDcZGoKQaIta4E7Ay9g7s15QQ83ka1YPIRe0Lae', 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiaHF6WUxYTEg1bXJMWERjb0FuYTUxQ3Z3Q1NXdmpFTlptY1hpZHJpdyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDU6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9qYXRpLW5hYmlsYT90aHVtYm5haWw9MSI7czo1OiJyb3V0ZSI7czoxNToiaW52aXRhdGlvbi5zaG93Ijt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6Mzt9', 1774791098),
('P4SokPzkCfBJdrFY7Fk5oSiUdLU7CI6PCfi3t5NL', 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiNllyMFAxaVptaklyTm5BUHFsSnM0Mkc4RlB4d0VjSk9XUnFFQ05xNSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDU6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9qYXRpLW5hYmlsYT90aHVtYm5haWw9MSI7czo1OiJyb3V0ZSI7czoxNToiaW52aXRhdGlvbi5zaG93Ijt9czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czo0MDoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2N1c3RvbWVyL2Rhc2hib2FyZCI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjM7fQ==', 1774085291);

-- --------------------------------------------------------

--
-- Table structure for table `templates`
--

CREATE TABLE `templates` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` bigint(20) NOT NULL,
  `view_path` varchar(255) NOT NULL,
  `thumbnail` varchar(255) DEFAULT NULL,
  `required_fields` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`required_fields`)),
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `templates`
--

INSERT INTO `templates` (`id`, `category_id`, `name`, `price`, `view_path`, `thumbnail`, `required_fields`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 1, 'Luxury Gold', 0, 'template1', 'thumbnails/luxury-gold.jpg', '{\"has_video\":true,\"has_love_story\":true,\"gallery_limit\":10}', 1, '2026-03-20 23:42:37', '2026-03-20 23:42:37'),
(2, 2, 'Clean White', 0, 'template1', 'thumbnails/clean-white.jpg', '{\"has_video\":false,\"has_love_story\":false,\"gallery_limit\":4}', 1, '2026-03-20 23:42:37', '2026-03-20 23:42:37');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `phone_number` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `google_id` varchar(255) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `role` enum('admin','client') NOT NULL DEFAULT 'client',
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `phone_number`, `email`, `email_verified_at`, `password`, `google_id`, `avatar`, `role`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Super Admin', NULL, 'admin@ruangrestu.com', NULL, '$2y$12$haGlCn3PAIpwCCxp/bNXL.8KdUW34qCahtDzg/rBBwF36Oa1R4SLy', NULL, NULL, 'admin', NULL, '2026-03-20 23:42:37', '2026-03-20 23:42:37'),
(2, 'Klien Test', NULL, 'client@gmail.com', NULL, '$2y$12$coNeVu6.Sxeu6NFp7P5LeetYJ5V6HT3JcDM7zcwPM2enEKEI0TslS', NULL, NULL, 'client', NULL, '2026-03-20 23:42:37', '2026-03-20 23:42:37'),
(3, 'hah gaming', NULL, 'abynnanony5@gmail.com', NULL, '$2y$12$UjYWRy5Gk3hR9WhHVE1Pf.c0GIywRIL7FaDPeyh6dQlMj.87JENd.', '108947557729018496465', 'https://lh3.googleusercontent.com/a/ACg8ocJhw0Bi2St6t13Wtpg5OVr0-n69ue8WdlPNr90cpirl5jQYYA=s96-c', 'client', NULL, '2026-03-20 23:43:37', '2026-03-20 23:43:37');

-- --------------------------------------------------------

--
-- Table structure for table `wishes_rsvps`
--

CREATE TABLE `wishes_rsvps` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `invitation_id` bigint(20) UNSIGNED NOT NULL,
  `guest_id` bigint(20) UNSIGNED DEFAULT NULL,
  `guest_name` varchar(255) NOT NULL,
  `status_rsvp` enum('hadir','tidak_hadir','ragu') NOT NULL,
  `pax` int(11) NOT NULL DEFAULT 1,
  `message` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `wishes_rsvps`
--

INSERT INTO `wishes_rsvps` (`id`, `invitation_id`, `guest_id`, `guest_name`, `status_rsvp`, `pax`, `message`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, 'Fauzan', 'hadir', 2, 'Lorem Ipsum', '2026-03-21 01:46:57', '2026-03-21 01:46:57');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `banks`
--
ALTER TABLE `banks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `categories_slug_unique` (`slug`);

--
-- Indexes for table `digital_gifts`
--
ALTER TABLE `digital_gifts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `digital_gifts_invitation_id_foreign` (`invitation_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `galleries`
--
ALTER TABLE `galleries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `galleries_invitation_id_foreign` (`invitation_id`);

--
-- Indexes for table `guests`
--
ALTER TABLE `guests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `guests_invitation_id_foreign` (`invitation_id`);

--
-- Indexes for table `invitations`
--
ALTER TABLE `invitations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `invitations_slug_unique` (`slug`),
  ADD KEY `invitations_user_id_foreign` (`user_id`),
  ADD KEY `invitations_template_id_foreign` (`template_id`),
  ADD KEY `invitations_music_id_foreign` (`music_id`);

--
-- Indexes for table `invitation_details`
--
ALTER TABLE `invitation_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `invitation_details_invitation_id_foreign` (`invitation_id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_reserved_at_available_at_index` (`queue`,`reserved_at`,`available_at`);

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
-- Indexes for table `musics`
--
ALTER TABLE `musics`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `orders_order_number_unique` (`order_number`),
  ADD KEY `orders_user_id_foreign` (`user_id`),
  ADD KEY `orders_invitation_id_foreign` (`invitation_id`),
  ADD KEY `orders_package_id_foreign` (`package_id`);

--
-- Indexes for table `packages`
--
ALTER TABLE `packages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `payments_transaction_id_unique` (`transaction_id`),
  ADD KEY `payments_order_id_foreign` (`order_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `templates`
--
ALTER TABLE `templates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `templates_category_id_foreign` (`category_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_phone_number_unique` (`phone_number`);

--
-- Indexes for table `wishes_rsvps`
--
ALTER TABLE `wishes_rsvps`
  ADD PRIMARY KEY (`id`),
  ADD KEY `wishes_rsvps_invitation_id_foreign` (`invitation_id`),
  ADD KEY `wishes_rsvps_guest_id_foreign` (`guest_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `banks`
--
ALTER TABLE `banks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `digital_gifts`
--
ALTER TABLE `digital_gifts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `galleries`
--
ALTER TABLE `galleries`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `guests`
--
ALTER TABLE `guests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invitations`
--
ALTER TABLE `invitations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `invitation_details`
--
ALTER TABLE `invitation_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `musics`
--
ALTER TABLE `musics`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `packages`
--
ALTER TABLE `packages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `templates`
--
ALTER TABLE `templates`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `wishes_rsvps`
--
ALTER TABLE `wishes_rsvps`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `digital_gifts`
--
ALTER TABLE `digital_gifts`
  ADD CONSTRAINT `digital_gifts_invitation_id_foreign` FOREIGN KEY (`invitation_id`) REFERENCES `invitations` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `galleries`
--
ALTER TABLE `galleries`
  ADD CONSTRAINT `galleries_invitation_id_foreign` FOREIGN KEY (`invitation_id`) REFERENCES `invitations` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `guests`
--
ALTER TABLE `guests`
  ADD CONSTRAINT `guests_invitation_id_foreign` FOREIGN KEY (`invitation_id`) REFERENCES `invitations` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `invitations`
--
ALTER TABLE `invitations`
  ADD CONSTRAINT `invitations_music_id_foreign` FOREIGN KEY (`music_id`) REFERENCES `musics` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `invitations_template_id_foreign` FOREIGN KEY (`template_id`) REFERENCES `templates` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `invitations_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `invitation_details`
--
ALTER TABLE `invitation_details`
  ADD CONSTRAINT `invitation_details_invitation_id_foreign` FOREIGN KEY (`invitation_id`) REFERENCES `invitations` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_invitation_id_foreign` FOREIGN KEY (`invitation_id`) REFERENCES `invitations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `orders_package_id_foreign` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `templates`
--
ALTER TABLE `templates`
  ADD CONSTRAINT `templates_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `wishes_rsvps`
--
ALTER TABLE `wishes_rsvps`
  ADD CONSTRAINT `wishes_rsvps_guest_id_foreign` FOREIGN KEY (`guest_id`) REFERENCES `guests` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `wishes_rsvps_invitation_id_foreign` FOREIGN KEY (`invitation_id`) REFERENCES `invitations` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
