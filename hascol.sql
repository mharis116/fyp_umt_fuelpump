-- --------------------------------------------------------
-- Host:                         localhost
-- Server version:               5.7.24 - MySQL Community Server (GPL)
-- Server OS:                    Win64
-- HeidiSQL Version:             10.2.0.5599
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


-- Dumping database structure for hascol
CREATE DATABASE IF NOT EXISTS `hascol` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `hascol`;

-- Dumping structure for table hascol.customers
CREATE TABLE IF NOT EXISTS `customers` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `date` timestamp NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone1` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone2` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `credit_limit` int(11) DEFAULT NULL,
  `opening_bal` int(11) DEFAULT NULL,
  `op_bal_id` bigint(20) unsigned DEFAULT NULL,
  `isdeleted` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `phone1` (`phone1`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `name` (`name`),
  KEY `customers_op_bal_id_foreign` (`op_bal_id`),
  CONSTRAINT `customers_op_bal_id_foreign` FOREIGN KEY (`op_bal_id`) REFERENCES `cust_ledgers` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table hascol.customers: ~6 rows (approximately)
/*!40000 ALTER TABLE `customers` DISABLE KEYS */;
INSERT INTO `customers` (`id`, `date`, `name`, `email`, `phone1`, `phone2`, `city`, `address`, `credit_limit`, `opening_bal`, `op_bal_id`, `isdeleted`, `created_at`, `updated_at`) VALUES
	(4, '2021-04-13 13:19:14', 'Ali', 'Ahmad@gmail.com', '03254789552', '03214785679', 'Karachi', 'kahna', NULL, 400, 1, 0, '2021-04-13 13:19:14', '2021-04-17 16:29:21'),
	(5, '2021-04-13 13:32:29', 'Haris', 'abccd@gmail.com', '03157896547', '30214574867', 'lahore', 'kahna', 50000, 8000, 3, 0, '2021-04-13 13:32:29', '2021-04-13 13:32:29'),
	(6, '2021-04-15 13:18:11', 'Amjad Ahsaan', 'abcd@gmail.com', '03334256897', '03254789552', 'Lahore', 'Null', 1000, 500, 5, 1, '2021-04-15 13:18:11', '2021-05-03 19:20:56'),
	(7, '2021-04-17 16:25:53', 'Ali akbar', '032348@45426', '03211086027', '30214578868', 'Lahore', 'kahna', 5000, 500, 13, 1, '2021-04-17 16:25:53', '2021-05-03 19:22:13'),
	(8, '2021-05-03 18:51:38', 'Walk In Customer', 'no@gmail.com', '03222222222', '03211111111', 'Lahore', 'Hascol', NULL, 0, 26, 0, '2021-05-03 18:51:38', '2021-05-03 18:51:38'),
	(9, '2021-05-05 14:15:46', 'Suny', 'suny@gmail.com', '03256845553', '03527825522', 'Lahore', 'Kahrek Multan Rd', NULL, -500, 49, 0, '2021-05-05 14:15:46', '2021-05-05 14:15:46');
/*!40000 ALTER TABLE `customers` ENABLE KEYS */;

-- Dumping structure for table hascol.cust_ledgers
CREATE TABLE IF NOT EXISTS `cust_ledgers` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `date` timestamp NOT NULL,
  `dr` double(255,2) DEFAULT NULL,
  `cr` double(255,2) DEFAULT NULL,
  `desc` text COLLATE utf8mb4_unicode_ci,
  `adjustment` double(255,2) DEFAULT NULL,
  `sale_id` bigint(20) unsigned DEFAULT NULL,
  `customer_id` bigint(20) unsigned NOT NULL,
  `type` enum('sale','payment','opbl') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'sale',
  `isdeleted` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cust_ledgers_sale_id_foreign` (`sale_id`),
  KEY `cust_ledgers_customer_id_foreign` (`customer_id`),
  CONSTRAINT `cust_ledgers_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`),
  CONSTRAINT `cust_ledgers_sale_id_foreign` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table hascol.cust_ledgers: ~39 rows (approximately)
/*!40000 ALTER TABLE `cust_ledgers` DISABLE KEYS */;
INSERT INTO `cust_ledgers` (`id`, `date`, `dr`, `cr`, `desc`, `adjustment`, `sale_id`, `customer_id`, `type`, `isdeleted`, `created_at`, `updated_at`) VALUES
	(1, '2021-04-13 13:35:48', 400.00, NULL, 'opening balance', NULL, NULL, 4, 'opbl', 0, '2021-04-13 13:19:14', '2021-04-13 13:35:48'),
	(3, '2021-04-13 13:32:29', 8000.00, NULL, 'opening balance', NULL, NULL, 5, 'opbl', 0, '2021-04-13 13:32:29', '2021-04-13 13:32:29'),
	(5, '2021-04-15 13:18:11', 500.00, NULL, 'opening balance', NULL, NULL, 6, 'opbl', 1, '2021-04-15 13:18:11', '2021-05-03 19:20:56'),
	(13, '2021-04-17 16:25:53', 500.00, NULL, 'opening balance', NULL, NULL, 7, 'opbl', 1, '2021-04-17 16:25:53', '2021-05-03 19:22:13'),
	(17, '2021-05-03 15:25:43', 60.00, 1700.00, NULL, -60.00, 183, 5, 'sale', 0, '2021-05-03 15:25:43', '2021-05-03 15:25:43'),
	(18, '2021-05-03 15:26:32', 0.00, 57500.00, NULL, NULL, 184, 4, 'sale', 0, '2021-05-03 15:26:32', '2021-05-03 15:26:32'),
	(19, '2021-05-03 15:55:07', -10000.00, 10000.00, NULL, NULL, NULL, 5, 'payment', 0, '2021-05-03 15:55:07', '2021-05-03 15:55:07'),
	(20, '2021-05-03 16:17:01', 0.00, 50270.00, NULL, NULL, 185, 4, 'sale', 0, '2021-05-03 16:17:01', '2021-05-03 16:17:01'),
	(21, '2021-05-03 16:27:51', 33000.00, NULL, NULL, NULL, 186, 5, 'sale', 0, '2021-05-03 16:27:51', '2021-05-03 16:27:51'),
	(22, '2021-05-03 16:31:54', -5000.00, 5000.00, NULL, NULL, NULL, 5, 'payment', 0, '2021-05-03 16:31:54', '2021-05-03 16:31:54'),
	(23, '2021-05-03 16:32:05', -5000.00, 5000.00, NULL, NULL, NULL, 5, 'payment', 0, '2021-05-03 16:32:05', '2021-05-03 16:32:05'),
	(24, '2021-05-03 16:32:17', -5000.00, 5000.00, NULL, NULL, NULL, 5, 'payment', 1, '2021-05-03 16:32:17', '2021-05-03 21:28:13'),
	(25, '2021-05-03 17:15:50', 0.00, 3630.00, NULL, NULL, 187, 5, 'sale', 0, '2021-05-03 17:15:50', '2021-05-03 17:15:50'),
	(26, '2021-05-03 18:51:38', 0.00, NULL, 'opening balance', NULL, NULL, 8, 'opbl', 0, '2021-05-03 18:51:38', '2021-05-03 18:51:38'),
	(27, '2021-05-03 21:03:36', 78500.00, 2000.00, NULL, NULL, 188, 5, 'sale', 0, '2021-05-03 21:03:36', '2021-05-03 21:03:36'),
	(28, '2021-05-03 21:19:26', 0.00, 13525.00, NULL, NULL, 189, 8, 'sale', 0, '2021-05-03 21:19:26', '2021-05-03 21:19:26'),
	(29, '2021-05-03 21:22:16', 0.00, 1150.00, NULL, NULL, 190, 8, 'sale', 0, '2021-05-03 21:22:16', '2021-05-03 21:22:16'),
	(30, '2021-05-03 21:22:37', 110.00, NULL, NULL, NULL, 191, 5, 'sale', 0, '2021-05-03 21:22:37', '2021-05-03 21:22:37'),
	(31, '2021-05-03 21:22:54', 110.00, NULL, NULL, NULL, 192, 4, 'sale', 0, '2021-05-03 21:22:54', '2021-05-03 21:22:54'),
	(32, '2021-05-03 21:30:31', -560.00, 560.00, NULL, NULL, NULL, 4, 'payment', 0, '2021-05-03 21:30:31', '2021-05-03 21:30:31'),
	(33, '2021-05-04 13:17:16', 0.00, 5500.00, NULL, NULL, 193, 5, 'sale', 0, '2021-05-04 13:17:16', '2021-05-04 13:17:16'),
	(34, '2021-05-04 13:24:13', 0.00, 55.00, NULL, NULL, 194, 5, 'sale', 0, '2021-05-04 13:24:13', '2021-05-04 13:24:13'),
	(35, '2021-05-04 13:26:03', 0.00, 115.00, NULL, NULL, 195, 5, 'sale', 0, '2021-05-04 13:26:03', '2021-05-04 13:26:03'),
	(36, '2021-05-04 13:26:27', 0.00, 57.50, NULL, NULL, 196, 5, 'sale', 0, '2021-05-04 13:26:27', '2021-05-04 13:26:27'),
	(37, '2021-05-04 13:27:37', 0.00, 110.00, NULL, NULL, 197, 5, 'sale', 0, '2021-05-04 13:27:37', '2021-05-04 13:27:37'),
	(38, '2021-05-04 13:28:09', 0.00, 115.00, NULL, NULL, 198, 5, 'sale', 0, '2021-05-04 13:28:09', '2021-05-04 13:28:09'),
	(39, '2021-05-04 13:31:53', 0.00, 57.50, NULL, NULL, 199, 5, 'sale', 0, '2021-05-04 13:31:53', '2021-05-04 13:31:53'),
	(40, '2021-05-04 13:32:56', 0.00, 330.00, NULL, NULL, 200, 5, 'sale', 0, '2021-05-04 13:32:56', '2021-05-04 13:32:56'),
	(41, '2021-05-04 13:33:47', 0.00, 115.00, NULL, NULL, 201, 5, 'sale', 0, '2021-05-04 13:33:47', '2021-05-04 13:33:47'),
	(42, '2021-05-04 13:34:15', 0.00, 110.00, NULL, NULL, 202, 5, 'sale', 0, '2021-05-04 13:34:15', '2021-05-04 13:34:15'),
	(43, '2021-05-04 13:34:47', 0.00, 110.00, NULL, NULL, 203, 5, 'sale', 0, '2021-05-04 13:34:47', '2021-05-04 13:34:47'),
	(44, '2021-05-04 13:38:08', 50.00, 5.00, NULL, NULL, 204, 4, 'sale', 0, '2021-05-04 13:38:08', '2021-05-04 13:38:08'),
	(45, '2021-05-04 13:57:17', -90000.00, 90000.00, NULL, NULL, NULL, 5, 'payment', 0, '2021-05-04 13:57:17', '2021-05-04 13:57:17'),
	(46, '2021-05-04 13:58:18', -10.00, 10.00, NULL, NULL, NULL, 5, 'payment', 0, '2021-05-04 13:58:18', '2021-05-04 13:58:18'),
	(47, '2021-05-04 13:58:53', 57500.00, NULL, NULL, NULL, 205, 5, 'sale', 0, '2021-05-04 13:58:53', '2021-05-04 13:58:53'),
	(48, '2021-05-05 13:47:48', 0.00, 57.50, NULL, NULL, 206, 8, 'sale', 0, '2021-05-05 13:47:48', '2021-05-05 13:47:48'),
	(49, '2021-05-05 14:15:46', -500.00, NULL, 'opening balance', NULL, NULL, 9, 'opbl', 0, '2021-05-05 14:15:46', '2021-05-05 14:15:46'),
	(50, '2021-05-05 14:16:12', 440.00, NULL, NULL, NULL, 207, 9, 'sale', 0, '2021-05-05 14:16:12', '2021-05-05 14:16:12'),
	(51, '2021-05-05 14:16:40', 60.00, 6.00, NULL, NULL, 208, 9, 'sale', 0, '2021-05-05 14:16:40', '2021-05-05 14:16:40'),
	(52, '2021-05-05 14:48:03', -100.00, 100.00, NULL, NULL, NULL, 5, 'payment', 0, '2021-05-05 14:48:03', '2021-05-05 14:48:03');
/*!40000 ALTER TABLE `cust_ledgers` ENABLE KEYS */;

-- Dumping structure for table hascol.dips
CREATE TABLE IF NOT EXISTS `dips` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `pro_id` bigint(20) unsigned NOT NULL,
  `qty` double(255,2) NOT NULL DEFAULT '0.00',
  `sighn` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `desc` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `change_in_qty` double(255,2) DEFAULT NULL,
  `isdeleted` tinyint(1) DEFAULT '0',
  `date` timestamp NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `dips_pro_id_foreign` (`pro_id`),
  CONSTRAINT `dips_pro_id_foreign` FOREIGN KEY (`pro_id`) REFERENCES `products` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=63 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table hascol.dips: ~13 rows (approximately)
/*!40000 ALTER TABLE `dips` DISABLE KEYS */;
INSERT INTO `dips` (`id`, `pro_id`, `qty`, `sighn`, `desc`, `change_in_qty`, `isdeleted`, `date`, `created_at`, `updated_at`) VALUES
	(50, 5, 9900.00, '+', NULL, 9900.00, 0, '2021-04-13 11:37:06', '2021-04-13 11:37:06', '2021-04-13 11:37:06'),
	(51, 4, 9970.00, '+', NULL, 535.00, 1, '2021-04-13 16:02:09', '2021-04-13 16:02:09', '2021-04-14 13:36:09'),
	(52, 4, 9970.00, '-', NULL, 30.00, 1, '2021-04-14 13:20:12', '2021-04-14 13:20:12', '2021-04-14 13:23:11'),
	(53, 4, 9999.00, '-', NULL, 31.00, 1, '2021-04-14 13:23:26', '2021-04-14 13:23:26', '2021-04-14 13:35:58'),
	(54, 4, 10000.00, '+', NULL, 1.00, 1, '2021-04-14 13:33:46', '2021-04-14 13:33:46', '2021-04-14 13:35:54'),
	(55, 4, 9000.00, '-', NULL, 495.00, 0, '2021-04-14 13:36:49', '2021-04-14 13:36:49', '2021-04-14 13:36:49'),
	(56, 4, 9999.00, '+', NULL, 999.00, 0, '2021-04-16 14:16:39', '2021-04-16 14:16:39', '2021-04-16 14:16:39'),
	(57, 4, 500.00, '+', 'krk', 500.00, 0, '2021-04-17 16:05:58', '2021-04-17 16:05:58', '2021-04-17 16:05:58'),
	(58, 4, 710.00, '+', 'iojio', 10.00, 1, '2021-04-20 12:18:09', '2021-04-20 12:18:09', '2021-05-03 15:48:35'),
	(59, 4, 9995.00, '+', NULL, 9852.00, 0, '2021-05-03 21:24:54', '2021-05-03 21:24:54', '2021-05-03 21:24:54'),
	(60, 4, 99.00, '-', NULL, 9896.00, 0, '2021-05-03 21:26:30', '2021-05-03 21:26:30', '2021-05-03 21:26:30'),
	(61, 4, 99.50, '+', NULL, 0.50, 0, '2021-05-03 21:27:11', '2021-05-03 21:27:11', '2021-05-03 21:27:11'),
	(62, 4, 4042.00, '-', NULL, 0.50, 0, '2021-05-05 14:11:09', '2021-05-05 14:11:09', '2021-05-05 14:11:09');
/*!40000 ALTER TABLE `dips` ENABLE KEYS */;

-- Dumping structure for table hascol.expenses
CREATE TABLE IF NOT EXISTS `expenses` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `date` timestamp NOT NULL,
  `desc` text COLLATE utf8mb4_unicode_ci,
  `amount` int(11) NOT NULL,
  `exp_type_id` bigint(20) unsigned NOT NULL,
  `isdeleted` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `expenses_exp_type_id_foreign` (`exp_type_id`),
  CONSTRAINT `expenses_exp_type_id_foreign` FOREIGN KEY (`exp_type_id`) REFERENCES `exp_types` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table hascol.expenses: ~8 rows (approximately)
/*!40000 ALTER TABLE `expenses` DISABLE KEYS */;
INSERT INTO `expenses` (`id`, `date`, `desc`, `amount`, `exp_type_id`, `isdeleted`, `created_at`, `updated_at`) VALUES
	(1, '2021-04-12 00:00:00', NULL, 500, 4, 0, '2021-04-12 13:16:19', '2021-04-12 13:16:19'),
	(6, '2021-05-03 00:00:00', NULL, 500, 4, 0, '2021-05-03 16:19:34', '2021-05-03 16:19:34'),
	(7, '2021-05-03 00:00:00', NULL, 100, 4, 0, '2021-05-03 19:44:21', '2021-05-03 19:44:21'),
	(8, '2021-05-03 00:00:00', 'nehari', 50, 4, 0, '2021-05-03 19:52:24', '2021-05-03 19:52:24'),
	(9, '2021-05-03 00:00:00', NULL, 700, 4, 0, '2021-05-03 21:27:43', '2021-05-03 21:27:43'),
	(10, '2021-05-04 00:00:00', NULL, 200, 4, 0, '2021-05-04 14:00:52', '2021-05-04 14:00:52'),
	(11, '2021-05-04 00:00:00', NULL, 25, 4, 0, '2021-05-04 14:01:34', '2021-05-04 14:01:34'),
	(12, '2021-05-04 00:00:00', NULL, 700, 5, 0, '2021-05-04 14:02:23', '2021-05-04 14:02:23');
/*!40000 ALTER TABLE `expenses` ENABLE KEYS */;

-- Dumping structure for table hascol.exp_types
CREATE TABLE IF NOT EXISTS `exp_types` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `desc` text COLLATE utf8mb4_unicode_ci,
  `isdeleted` binary(50) NOT NULL DEFAULT '0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table hascol.exp_types: ~3 rows (approximately)
/*!40000 ALTER TABLE `exp_types` DISABLE KEYS */;
INSERT INTO `exp_types` (`id`, `name`, `type`, `desc`, `isdeleted`, `created_at`, `updated_at`) VALUES
	(4, 'Staff Food', 'Daily', 'Staff food Expense', _binary 0x3000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000, '2021-04-03 17:50:50', '2021-04-03 17:50:50'),
	(5, 'Electric Bill', 'Monthly', 'Bijli ka bill', _binary 0x3000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000, '2021-04-05 14:51:35', '2021-04-05 14:51:35'),
	(6, 'Salaries', 'Monthly', 'staff saleries', _binary 0x3000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000, '2021-05-03 16:18:19', '2021-05-03 16:18:19');
/*!40000 ALTER TABLE `exp_types` ENABLE KEYS */;

-- Dumping structure for table hascol.fuel_backups
CREATE TABLE IF NOT EXISTS `fuel_backups` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `pro_id` bigint(20) unsigned NOT NULL,
  `pur_id` bigint(20) unsigned NOT NULL,
  `sku` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `qty` double(255,2) NOT NULL DEFAULT '0.00',
  `fqty` double(255,2) DEFAULT '0.00',
  `stock_capacity` double(255,2) NOT NULL,
  `desc` text COLLATE utf8mb4_unicode_ci,
  `isdeleted` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fuel_backups_pro_id_foreign` (`pro_id`),
  KEY `fuel_backups_pur_id_foreign` (`pur_id`),
  CONSTRAINT `fuel_backups_pro_id_foreign` FOREIGN KEY (`pro_id`) REFERENCES `products` (`id`),
  CONSTRAINT `fuel_backups_pur_id_foreign` FOREIGN KEY (`pur_id`) REFERENCES `purchases` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table hascol.fuel_backups: ~2 rows (approximately)
/*!40000 ALTER TABLE `fuel_backups` DISABLE KEYS */;
INSERT INTO `fuel_backups` (`id`, `pro_id`, `pur_id`, `sku`, `qty`, `fqty`, `stock_capacity`, `desc`, `isdeleted`, `created_at`, `updated_at`) VALUES
	(1, 5, 51, 'D-0001', 0.00, 400.00, 1000.00, NULL, 0, '2021-05-03 15:44:17', '2021-05-05 14:10:38'),
	(2, 5, 54, 'D-0001', 0.00, 100.00, 500.00, NULL, 0, '2021-05-03 18:45:22', '2021-05-03 18:48:49');
/*!40000 ALTER TABLE `fuel_backups` ENABLE KEYS */;

-- Dumping structure for table hascol.migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table hascol.migrations: ~16 rows (approximately)
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '2021_03_19_124154_create_products_table', 1),
	(2, '2021_03_19_124244_create_prices_table', 2),
	(7, '2021_03_23_133311_create_pur_types_table', 5),
	(9, '2021_03_23_131052_create_exp_types_table', 7),
	(10, '2021_03_19_124953_create_expenses_table', 8),
	(18, '2021_03_23_171330_create_sales_items_table', 11),
	(20, '2021_03_19_124335_create_sales_table', 12),
	(30, '2021_03_19_124307_create_purchases_table', 15),
	(32, '2021_04_02_124924_create_purchase_items_table', 15),
	(33, '2021_04_05_152316_create_dips_table', 16),
	(35, '2021_03_19_124357_create_stocks_table', 17),
	(38, '2021_03_19_124825_create_sup_ledgers_table', 19),
	(39, '2021_03_19_124442_create_suppliers_table', 20),
	(41, '2021_03_23_131146_create_cust_ledgers_table', 21),
	(42, '2021_03_19_124425_create_customers_table', 22),
	(43, '2021_04_12_142652_create_fuel_backups_table', 23);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;

-- Dumping structure for table hascol.prices
CREATE TABLE IF NOT EXISTS `prices` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `date` timestamp NOT NULL,
  `cost_price` double(8,2) NOT NULL,
  `retail_price` double(8,2) NOT NULL,
  `comments` text COLLATE utf8mb4_unicode_ci,
  `pro_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `prices_pro_id_foreign` (`pro_id`),
  CONSTRAINT `prices_pro_id_foreign` FOREIGN KEY (`pro_id`) REFERENCES `products` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table hascol.prices: ~4 rows (approximately)
/*!40000 ALTER TABLE `prices` DISABLE KEYS */;
INSERT INTO `prices` (`id`, `date`, `cost_price`, `retail_price`, `comments`, `pro_id`, `created_at`, `updated_at`) VALUES
	(5, '2021-04-05 15:57:17', 100.00, 110.00, NULL, 4, '2021-04-05 15:57:17', '2021-04-05 15:57:17'),
	(6, '2021-04-05 15:59:08', 100.00, 115.00, NULL, 5, '2021-04-05 15:59:08', '2021-04-05 15:59:08'),
	(7, '2021-05-03 20:35:02', 115.00, 130.00, NULL, 6, '2021-05-03 20:35:02', '2021-05-03 20:35:02'),
	(8, '2021-05-05 14:13:01', 90.00, 110.00, NULL, 4, '2021-05-05 14:13:01', '2021-05-05 14:13:01');
/*!40000 ALTER TABLE `prices` ENABLE KEYS */;

-- Dumping structure for table hascol.products
CREATE TABLE IF NOT EXISTS `products` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sku` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alert_qty` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cost_Price` double(8,2) NOT NULL,
  `retail_price` double(8,2) NOT NULL,
  `desc` text COLLATE utf8mb4_unicode_ci,
  `isdeleted` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `products_sku_unique` (`sku`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table hascol.products: ~3 rows (approximately)
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` (`id`, `name`, `sku`, `alert_qty`, `cost_Price`, `retail_price`, `desc`, `isdeleted`, `created_at`, `updated_at`) VALUES
	(4, 'Petrol', 'P-0001', '500', 90.00, 110.00, NULL, 0, '2021-04-05 15:57:17', '2021-05-05 14:13:01'),
	(5, 'Disel', 'D-0001', '500', 100.00, 115.00, NULL, 0, '2021-04-05 15:59:08', '2021-04-05 15:59:08'),
	(6, 'Heighe Octane', 'hp-0001', '500', 115.00, 130.00, NULL, 1, '2021-05-03 20:35:02', '2021-05-03 20:51:56');
/*!40000 ALTER TABLE `products` ENABLE KEYS */;

-- Dumping structure for table hascol.purchases
CREATE TABLE IF NOT EXISTS `purchases` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `date` timestamp NOT NULL,
  `inv_no` bigint(20) NOT NULL DEFAULT '0',
  `desc` text COLLATE utf8mb4_unicode_ci,
  `sup_bill_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sup_id` bigint(20) unsigned NOT NULL,
  `total_qty` bigint(20) NOT NULL,
  `retail_amount` bigint(20) DEFAULT NULL,
  `cost_amount` bigint(20) NOT NULL,
  `pur_type` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `adjustment` bigint(20) DEFAULT NULL,
  `isdeleted` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `purchases_sup_id_foreign` (`sup_id`),
  CONSTRAINT `purchases_sup_id_foreign` FOREIGN KEY (`sup_id`) REFERENCES `suppliers` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=61 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table hascol.purchases: ~3 rows (approximately)
/*!40000 ALTER TABLE `purchases` DISABLE KEYS */;
INSERT INTO `purchases` (`id`, `date`, `inv_no`, `desc`, `sup_bill_no`, `sup_id`, `total_qty`, `retail_amount`, `cost_amount`, `pur_type`, `adjustment`, `isdeleted`, `created_at`, `updated_at`) VALUES
	(51, '2021-05-03 15:44:17', 1, NULL, 'jghf', 2, 500, 57500, 50000, 'backup', NULL, 0, '2021-05-03 15:44:17', '2021-05-03 15:44:17'),
	(52, '2021-05-03 16:15:13', 2, NULL, 'kk', 2, 200, 22000, 20000, 'stock', NULL, 0, '2021-05-03 16:15:13', '2021-05-03 16:15:13'),
	(53, '2021-05-03 16:24:48', 3, NULL, 'ikyuujy', 1, 100, 11000, 10000, 'stock', NULL, 0, '2021-05-03 16:24:48', '2021-05-03 16:24:48'),
	(54, '2021-05-03 18:45:22', 4, NULL, 'uhui', 1, 100, 11500, 10000, 'backup', NULL, 0, '2021-05-03 18:45:22', '2021-05-03 18:45:22'),
	(55, '2021-05-05 14:06:24', 5, NULL, 'ggt', 2, 4000, 440000, 400000, 'stock', NULL, 0, '2021-05-05 14:06:24', '2021-05-05 14:06:24'),
	(56, '2021-05-05 14:14:44', 6, NULL, 'jknjn', 3, 1058, 116380, 95220, 'stock', NULL, 0, '2021-05-05 14:14:44', '2021-05-05 14:14:44'),
	(57, '2021-05-05 14:29:46', 7, NULL, '8', 3, 1, 58, 50, 'stock', NULL, 0, '2021-05-05 14:29:46', '2021-05-05 14:29:46'),
	(58, '2021-05-05 14:32:28', 8, NULL, 'jj', 3, 9, 1035, 900, 'stock', NULL, 0, '2021-05-05 14:32:28', '2021-05-05 14:32:28'),
	(59, '2021-05-05 14:32:53', 9, NULL, 'ikk', 2, 1, 66, 54, 'stock', NULL, 0, '2021-05-05 14:32:53', '2021-05-05 14:32:53'),
	(60, '2021-05-05 14:33:23', 10, NULL, 'jj', 1, 4, 440, 360, 'stock', NULL, 0, '2021-05-05 14:33:23', '2021-05-05 14:33:23');
/*!40000 ALTER TABLE `purchases` ENABLE KEYS */;

-- Dumping structure for table hascol.purchase_items
CREATE TABLE IF NOT EXISTS `purchase_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `date` timestamp NOT NULL,
  `pro_id` bigint(20) unsigned NOT NULL,
  `pur_id` bigint(20) unsigned NOT NULL,
  `sku` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `qty` bigint(20) NOT NULL,
  `cost_price` bigint(20) NOT NULL,
  `retail_price` bigint(20) NOT NULL,
  `sub_total` bigint(20) NOT NULL,
  `isdeleted` tinyint(4) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `purchase_items_pro_id_foreign` (`pro_id`),
  KEY `purchase_items_pur_id_foreign` (`pur_id`),
  CONSTRAINT `purchase_items_pro_id_foreign` FOREIGN KEY (`pro_id`) REFERENCES `products` (`id`),
  CONSTRAINT `purchase_items_pur_id_foreign` FOREIGN KEY (`pur_id`) REFERENCES `purchases` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=63 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table hascol.purchase_items: ~10 rows (approximately)
/*!40000 ALTER TABLE `purchase_items` DISABLE KEYS */;
INSERT INTO `purchase_items` (`id`, `date`, `pro_id`, `pur_id`, `sku`, `qty`, `cost_price`, `retail_price`, `sub_total`, `isdeleted`, `created_at`, `updated_at`) VALUES
	(53, '2021-05-03 15:44:17', 5, 51, 'D-0001', 500, 100, 115, 50000, 0, '2021-05-03 15:44:17', '2021-05-03 15:44:17'),
	(54, '2021-05-03 16:15:13', 4, 52, 'P-0001', 200, 100, 110, 20000, 0, '2021-05-03 16:15:13', '2021-05-03 16:15:13'),
	(55, '2021-05-03 16:24:48', 4, 53, 'P-0001', 100, 100, 110, 10000, 0, '2021-05-03 16:24:48', '2021-05-03 16:24:48'),
	(56, '2021-05-03 18:45:22', 5, 54, 'D-0001', 100, 100, 115, 10000, 0, '2021-05-03 18:45:22', '2021-05-03 18:45:22'),
	(57, '2021-05-05 14:06:24', 4, 55, 'P-0001', 4000, 100, 110, 400000, 0, '2021-05-05 14:06:24', '2021-05-05 14:06:24'),
	(58, '2021-05-05 14:14:44', 4, 56, 'P-0001', 1058, 90, 110, 95220, 0, '2021-05-05 14:14:44', '2021-05-05 14:14:44'),
	(59, '2021-05-05 14:29:46', 5, 57, 'D-0001', 1, 100, 115, 50, 0, '2021-05-05 14:29:46', '2021-05-05 14:29:46'),
	(60, '2021-05-05 14:32:28', 5, 58, 'D-0001', 9, 100, 115, 900, 0, '2021-05-05 14:32:28', '2021-05-05 14:32:28'),
	(61, '2021-05-05 14:32:53', 4, 59, 'P-0001', 1, 90, 110, 54, 0, '2021-05-05 14:32:53', '2021-05-05 14:32:53'),
	(62, '2021-05-05 14:33:23', 4, 60, 'P-0001', 4, 90, 110, 360, 0, '2021-05-05 14:33:23', '2021-05-05 14:33:23');
/*!40000 ALTER TABLE `purchase_items` ENABLE KEYS */;

-- Dumping structure for table hascol.sales
CREATE TABLE IF NOT EXISTS `sales` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `invoice_no` bigint(20) NOT NULL DEFAULT '0',
  `cost_amount` double(255,2) NOT NULL,
  `retail_amount` double(255,2) NOT NULL,
  `desc` text COLLATE utf8mb4_unicode_ci,
  `total_qty` double(255,2) NOT NULL,
  `adjustment` double(255,2) DEFAULT '0.00',
  `customer_id` bigint(20) unsigned NOT NULL,
  `isdeleted` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sales_customer_id_foreign` (`customer_id`),
  CONSTRAINT `sales_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=209 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table hascol.sales: ~26 rows (approximately)
/*!40000 ALTER TABLE `sales` DISABLE KEYS */;
INSERT INTO `sales` (`id`, `date`, `invoice_no`, `cost_amount`, `retail_amount`, `desc`, `total_qty`, `adjustment`, `customer_id`, `isdeleted`, `created_at`, `updated_at`) VALUES
	(183, '2021-04-03', 1, 1600.00, 1760.00, NULL, 16.00, -60.00, 5, 0, '2021-05-03 15:25:43', '2021-05-03 15:25:43'),
	(184, '2021-05-03', 2, 50000.00, 57500.00, NULL, 500.00, 0.00, 4, 0, '2021-05-03 15:26:32', '2021-05-03 15:26:32'),
	(185, '2021-05-03', 3, 45700.00, 50270.00, NULL, 457.00, 0.00, 4, 0, '2021-05-03 16:17:01', '2021-05-03 16:17:01'),
	(186, '2021-05-03', 4, 30000.00, 33000.00, NULL, 300.00, 0.00, 5, 0, '2021-05-03 16:27:51', '2021-05-03 16:27:51'),
	(187, '2021-05-03', 5, 3300.00, 3630.00, NULL, 33.00, 0.00, 5, 0, '2021-05-03 17:15:50', '2021-05-03 17:15:50'),
	(188, '2021-05-03', 6, 70000.00, 80500.00, NULL, 700.00, 0.00, 5, 0, '2021-05-03 21:03:36', '2021-05-03 21:03:36'),
	(189, '2021-05-03', 7, 12000.00, 13525.00, NULL, 120.00, 0.00, 8, 0, '2021-05-03 21:19:26', '2021-05-03 21:19:26'),
	(190, '2021-05-03', 8, 1000.00, 1150.00, NULL, 10.00, 0.00, 8, 0, '2021-05-03 21:22:16', '2021-05-03 21:22:16'),
	(191, '2021-05-03', 9, 100.00, 110.00, NULL, 1.00, 0.00, 5, 0, '2021-05-03 21:22:37', '2021-05-03 21:22:37'),
	(192, '2021-05-03', 10, 100.00, 110.00, NULL, 1.00, 0.00, 4, 0, '2021-05-03 21:22:54', '2021-05-03 21:22:54'),
	(193, '2021-05-04', 11, 5000.00, 5500.00, NULL, 50.00, 0.00, 5, 0, '2021-05-04 13:17:16', '2021-05-04 13:17:16'),
	(194, '2021-05-04', 12, 50.00, 55.00, NULL, 0.50, 0.00, 5, 0, '2021-05-04 13:24:13', '2021-05-04 13:24:13'),
	(195, '2021-05-04', 13, 100.00, 115.00, NULL, 1.00, 0.00, 5, 0, '2021-05-04 13:26:03', '2021-05-04 13:26:03'),
	(196, '2021-05-04', 14, 50.00, 57.50, NULL, 0.50, 0.00, 5, 0, '2021-05-04 13:26:27', '2021-05-04 13:26:27'),
	(197, '2021-05-04', 15, 100.00, 110.00, NULL, 1.00, 0.00, 5, 0, '2021-05-04 13:27:37', '2021-05-04 13:27:37'),
	(198, '2021-05-04', 16, 100.00, 115.00, NULL, 1.00, 0.00, 5, 0, '2021-05-04 13:28:09', '2021-05-04 13:28:09'),
	(199, '2021-05-04', 17, 50.00, 57.50, NULL, 0.50, 0.00, 5, 0, '2021-05-04 13:31:53', '2021-05-04 13:31:53'),
	(200, '2021-05-04', 18, 300.00, 330.00, NULL, 3.00, 0.00, 5, 0, '2021-05-04 13:32:56', '2021-05-04 13:32:56'),
	(201, '2021-05-04', 19, 100.00, 115.00, NULL, 1.00, 0.00, 5, 0, '2021-05-04 13:33:47', '2021-05-04 13:33:47'),
	(202, '2021-05-04', 20, 100.00, 110.00, NULL, 1.00, 0.00, 5, 0, '2021-05-04 13:34:15', '2021-05-04 13:34:15'),
	(203, '2021-05-04', 21, 100.00, 110.00, NULL, 1.00, 0.00, 5, 0, '2021-05-04 13:34:47', '2021-05-04 13:34:47'),
	(204, '2021-05-04', 22, 50.00, 55.00, NULL, 0.50, 0.00, 4, 0, '2021-05-04 13:38:08', '2021-05-04 13:38:08'),
	(205, '2021-05-04', 23, 50000.00, 57500.00, NULL, 500.00, 0.00, 5, 0, '2021-05-04 13:58:53', '2021-05-04 13:58:53'),
	(206, '2021-05-05', 24, 50.00, 57.50, NULL, 0.50, NULL, 8, 0, '2021-05-05 13:47:48', '2021-05-05 13:47:48'),
	(207, '2021-05-05', 25, 360.00, 440.00, NULL, 4.00, NULL, 9, 0, '2021-05-05 14:16:12', '2021-05-05 14:16:12'),
	(208, '2021-05-05', 26, 54.00, 66.00, NULL, 0.60, NULL, 9, 0, '2021-05-05 14:16:40', '2021-05-05 14:16:40');
/*!40000 ALTER TABLE `sales` ENABLE KEYS */;

-- Dumping structure for table hascol.sales_items
CREATE TABLE IF NOT EXISTS `sales_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `date` timestamp NOT NULL,
  `sale_id` bigint(20) unsigned NOT NULL,
  `pro_id` bigint(20) unsigned NOT NULL,
  `sku` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `qty` double(8,2) NOT NULL,
  `cost_price` double(8,2) DEFAULT NULL,
  `subtotal` bigint(20) NOT NULL DEFAULT '0',
  `retail_price` double(8,2) NOT NULL,
  `desc` text COLLATE utf8mb4_unicode_ci,
  `isdeleted` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sales_items_sale_id_foreign` (`sale_id`),
  KEY `sales_items_pro_id_foreign` (`pro_id`),
  CONSTRAINT `sales_items_pro_id_foreign` FOREIGN KEY (`pro_id`) REFERENCES `products` (`id`),
  CONSTRAINT `sales_items_sale_id_foreign` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=106 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table hascol.sales_items: ~27 rows (approximately)
/*!40000 ALTER TABLE `sales_items` DISABLE KEYS */;
INSERT INTO `sales_items` (`id`, `date`, `sale_id`, `pro_id`, `sku`, `qty`, `cost_price`, `subtotal`, `retail_price`, `desc`, `isdeleted`, `created_at`, `updated_at`) VALUES
	(79, '2021-04-03 15:25:43', 183, 4, 'P-0001', 16.00, 100.00, 1760, 110.00, NULL, 0, '2021-05-03 15:25:43', '2021-05-03 15:25:43'),
	(80, '2021-05-03 15:26:32', 184, 5, 'D-0001', 500.00, 100.00, 57500, 115.00, NULL, 0, '2021-05-03 15:26:32', '2021-05-03 15:26:32'),
	(81, '2021-05-03 16:17:01', 185, 4, 'P-0001', 457.00, 100.00, 50270, 110.00, NULL, 0, '2021-05-03 16:17:01', '2021-05-03 16:17:01'),
	(82, '2021-05-03 16:27:51', 186, 4, 'P-0001', 300.00, 100.00, 33000, 110.00, NULL, 0, '2021-05-03 16:27:51', '2021-05-03 16:27:51'),
	(83, '2021-05-03 17:15:50', 187, 4, 'P-0001', 33.00, 100.00, 3630, 110.00, NULL, 0, '2021-05-03 17:15:50', '2021-05-03 17:15:50'),
	(84, '2021-05-03 21:03:36', 188, 5, 'D-0001', 700.00, 100.00, 80500, 115.00, NULL, 0, '2021-05-03 21:03:36', '2021-05-03 21:03:36'),
	(85, '2021-05-03 21:19:26', 189, 4, 'P-0001', 55.00, 100.00, 6050, 110.00, NULL, 0, '2021-05-03 21:19:26', '2021-05-03 21:19:26'),
	(86, '2021-05-03 21:19:26', 189, 5, 'D-0001', 65.00, 100.00, 7475, 115.00, NULL, 0, '2021-05-03 21:19:26', '2021-05-03 21:19:26'),
	(87, '2021-05-03 21:22:16', 190, 5, 'D-0001', 10.00, 100.00, 1150, 115.00, NULL, 0, '2021-05-03 21:22:16', '2021-05-03 21:22:16'),
	(88, '2021-05-03 21:22:37', 191, 4, 'P-0001', 1.00, 100.00, 110, 110.00, NULL, 0, '2021-05-03 21:22:37', '2021-05-03 21:22:37'),
	(89, '2021-05-03 21:22:54', 192, 4, 'P-0001', 1.00, 100.00, 110, 110.00, NULL, 0, '2021-05-03 21:22:54', '2021-05-03 21:22:54'),
	(90, '2021-05-04 13:17:16', 193, 4, 'P-0001', 50.00, 100.00, 5500, 110.00, NULL, 0, '2021-05-04 13:17:16', '2021-05-04 13:17:16'),
	(91, '2021-05-04 13:24:13', 194, 4, 'P-0001', 0.50, 100.00, 55, 110.00, NULL, 0, '2021-05-04 13:24:13', '2021-05-04 13:24:13'),
	(92, '2021-05-04 13:26:03', 195, 5, 'D-0001', 1.00, 100.00, 115, 115.00, NULL, 0, '2021-05-04 13:26:03', '2021-05-04 13:26:03'),
	(93, '2021-05-04 13:26:27', 196, 5, 'D-0001', 0.50, 100.00, 58, 115.00, NULL, 0, '2021-05-04 13:26:27', '2021-05-04 13:26:27'),
	(94, '2021-05-04 13:27:37', 197, 4, 'P-0001', 1.00, 100.00, 110, 110.00, NULL, 0, '2021-05-04 13:27:37', '2021-05-04 13:27:37'),
	(95, '2021-05-04 13:28:09', 198, 5, 'D-0001', 1.00, 100.00, 115, 115.00, NULL, 0, '2021-05-04 13:28:09', '2021-05-04 13:28:09'),
	(96, '2021-05-04 13:31:53', 199, 5, 'D-0001', 0.50, 100.00, 58, 115.00, NULL, 0, '2021-05-04 13:31:53', '2021-05-04 13:31:53'),
	(97, '2021-05-04 13:32:56', 200, 4, 'P-0001', 3.00, 100.00, 330, 110.00, NULL, 0, '2021-05-04 13:32:56', '2021-05-04 13:32:56'),
	(98, '2021-05-04 13:33:47', 201, 5, 'D-0001', 1.00, 100.00, 115, 115.00, NULL, 0, '2021-05-04 13:33:47', '2021-05-04 13:33:47'),
	(99, '2021-05-04 13:34:15', 202, 4, 'P-0001', 1.00, 100.00, 110, 110.00, NULL, 0, '2021-05-04 13:34:15', '2021-05-04 13:34:15'),
	(100, '2021-05-04 13:34:47', 203, 4, 'P-0001', 1.00, 100.00, 110, 110.00, NULL, 0, '2021-05-04 13:34:47', '2021-05-04 13:34:47'),
	(101, '2021-05-04 13:38:08', 204, 4, 'P-0001', 0.50, 100.00, 55, 110.00, NULL, 0, '2021-05-04 13:38:08', '2021-05-04 13:38:08'),
	(102, '2021-05-04 13:58:53', 205, 5, 'D-0001', 500.00, 100.00, 57500, 115.00, NULL, 0, '2021-05-04 13:58:53', '2021-05-04 13:58:53'),
	(103, '2021-05-05 13:47:48', 206, 5, 'D-0001', 0.50, 100.00, 58, 115.00, NULL, 0, '2021-05-05 13:47:48', '2021-05-05 13:47:48'),
	(104, '2021-05-05 14:16:12', 207, 4, 'P-0001', 4.00, 90.00, 440, 110.00, NULL, 0, '2021-05-05 14:16:12', '2021-05-05 14:16:12'),
	(105, '2021-05-05 14:16:40', 208, 4, 'P-0001', 0.60, 90.00, 66, 110.00, NULL, 0, '2021-05-05 14:16:40', '2021-05-05 14:16:40');
/*!40000 ALTER TABLE `sales_items` ENABLE KEYS */;

-- Dumping structure for table hascol.stocks
CREATE TABLE IF NOT EXISTS `stocks` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `pro_id` bigint(20) unsigned NOT NULL,
  `dip_id` bigint(20) unsigned DEFAULT NULL,
  `sku` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `desc` text COLLATE utf8mb4_unicode_ci,
  `qty` double(255,2) NOT NULL DEFAULT '0.00',
  `stock_capacity` double(255,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `stocks_pro_id_foreign` (`pro_id`),
  KEY `stocks_dip_id_foreign` (`dip_id`),
  CONSTRAINT `stocks_dip_id_foreign` FOREIGN KEY (`dip_id`) REFERENCES `dips` (`id`),
  CONSTRAINT `stocks_pro_id_foreign` FOREIGN KEY (`pro_id`) REFERENCES `products` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table hascol.stocks: ~3 rows (approximately)
/*!40000 ALTER TABLE `stocks` DISABLE KEYS */;
INSERT INTO `stocks` (`id`, `pro_id`, `dip_id`, `sku`, `desc`, `qty`, `stock_capacity`, `created_at`, `updated_at`) VALUES
	(1, 4, 62, 'P-0001', NULL, 5100.00, 10000.00, '2021-04-05 15:57:17', '2021-05-05 14:33:23'),
	(2, 5, 50, 'D-0001', NULL, 7830.00, 10000.00, '2021-04-05 15:59:08', '2021-05-05 14:32:28'),
	(3, 6, NULL, 'hp-0001', NULL, 0.00, 5000.00, '2021-05-03 20:35:03', '2021-05-03 20:35:03');
/*!40000 ALTER TABLE `stocks` ENABLE KEYS */;

-- Dumping structure for table hascol.suppliers
CREATE TABLE IF NOT EXISTS `suppliers` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `date` timestamp NOT NULL,
  `company` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '',
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone1` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone2` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `opening_bal` int(11) DEFAULT NULL,
  `op_bal_id` bigint(20) unsigned DEFAULT NULL,
  `isdeleted` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `phone1` (`phone1`),
  KEY `suppliers_op_bal_id_foreign` (`op_bal_id`),
  CONSTRAINT `suppliers_op_bal_id_foreign` FOREIGN KEY (`op_bal_id`) REFERENCES `sup_ledgers` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table hascol.suppliers: ~3 rows (approximately)
/*!40000 ALTER TABLE `suppliers` DISABLE KEYS */;
INSERT INTO `suppliers` (`id`, `date`, `company`, `name`, `email`, `phone1`, `phone2`, `city`, `address`, `opening_bal`, `op_bal_id`, `isdeleted`, `created_at`, `updated_at`) VALUES
	(1, '2021-04-13 12:53:37', '', 'babar', 'abdc@gmail.com', '03288086021', '03275458874', 'Lahore', 'kahna', 5000, 4, 0, '2021-04-13 12:53:37', '2021-04-15 13:24:27'),
	(2, '2021-04-15 13:25:31', '', 'Hassan', 'abcd@gmail.com', '03254789552', '03334286051', 'Lahore', 'kahna', 400, 19, 0, '2021-04-15 13:25:31', '2021-04-15 13:25:31'),
	(3, '2021-05-03 18:19:34', 'Hascol', 'Amar', 'amar.tanveer@gmail.com', '03241825255', '03284112845', 'Lahore', 'jsknjdnjnjwsndjnjdnjcnjncdjdncjnjnc', 500, 46, 0, '2021-05-03 18:19:34', '2021-05-03 19:31:39');
/*!40000 ALTER TABLE `suppliers` ENABLE KEYS */;

-- Dumping structure for table hascol.sup_ledgers
CREATE TABLE IF NOT EXISTS `sup_ledgers` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `date` timestamp NOT NULL,
  `dr` double(255,2) DEFAULT NULL,
  `cr` double(255,2) DEFAULT NULL,
  `adjustment` double(255,2) DEFAULT NULL,
  `desc` text COLLATE utf8mb4_unicode_ci,
  `pur_id` bigint(20) unsigned DEFAULT NULL,
  `sup_id` bigint(20) unsigned NOT NULL,
  `type` enum('purchase','payment','opbl') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'purchase',
  `isdeleted` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sup_ledgers_pur_id_foreign` (`pur_id`),
  KEY `sup_ledgers_sup_id_foreign` (`sup_id`),
  CONSTRAINT `sup_ledgers_pur_id_foreign` FOREIGN KEY (`pur_id`) REFERENCES `purchases` (`id`),
  CONSTRAINT `sup_ledgers_sup_id_foreign` FOREIGN KEY (`sup_id`) REFERENCES `suppliers` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=59 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table hascol.sup_ledgers: ~18 rows (approximately)
/*!40000 ALTER TABLE `sup_ledgers` DISABLE KEYS */;
INSERT INTO `sup_ledgers` (`id`, `date`, `dr`, `cr`, `adjustment`, `desc`, `pur_id`, `sup_id`, `type`, `isdeleted`, `created_at`, `updated_at`) VALUES
	(4, '2021-04-13 12:54:20', 5000.00, NULL, NULL, 'opening balance', NULL, 1, 'opbl', 0, '2021-04-13 12:53:37', '2021-04-13 12:54:20'),
	(19, '2021-04-15 13:25:31', 400.00, NULL, NULL, 'opening balance', NULL, 2, 'opbl', 0, '2021-04-15 13:25:31', '2021-04-15 13:25:31'),
	(39, '2021-05-03 15:44:17', 50000.00, NULL, NULL, NULL, 51, 2, 'purchase', 0, '2021-05-03 15:44:17', '2021-05-03 15:44:17'),
	(40, '2021-05-03 16:15:13', 20000.00, NULL, NULL, NULL, 52, 2, 'purchase', 0, '2021-05-03 16:15:13', '2021-05-03 16:15:13'),
	(41, '2021-05-03 16:23:46', -700.00, 700.00, NULL, NULL, NULL, 2, 'payment', 0, '2021-05-03 16:23:46', '2021-05-03 16:23:46'),
	(42, '2021-05-03 16:24:48', 10000.00, NULL, NULL, NULL, 53, 1, 'purchase', 0, '2021-05-03 16:24:48', '2021-05-03 16:24:48'),
	(43, '2021-05-03 16:34:59', -700.00, 700.00, NULL, NULL, NULL, 2, 'payment', 0, '2021-05-03 16:34:59', '2021-05-03 16:34:59'),
	(44, '2021-05-03 16:35:17', -8531.00, 8531.00, NULL, NULL, NULL, 2, 'payment', 0, '2021-05-03 16:35:17', '2021-05-03 16:35:17'),
	(45, '2021-05-03 16:35:35', -469.00, 469.00, NULL, NULL, NULL, 2, 'payment', 0, '2021-05-03 16:35:35', '2021-05-03 16:35:35'),
	(46, '2021-05-03 18:19:34', 500.00, NULL, NULL, 'opening balance', NULL, 3, 'opbl', 0, '2021-05-03 18:19:34', '2021-05-03 19:31:39'),
	(47, '2021-05-03 18:45:22', 0.00, 10000.00, NULL, NULL, 54, 1, 'purchase', 0, '2021-05-03 18:45:22', '2021-05-03 18:45:22'),
	(48, '2021-05-03 19:46:35', -5000.00, 5000.00, NULL, NULL, NULL, 1, 'payment', 0, '2021-05-03 19:46:35', '2021-05-03 19:46:35'),
	(49, '2021-05-05 14:06:24', 100000.00, 300000.00, NULL, NULL, 55, 2, 'purchase', 0, '2021-05-05 14:06:24', '2021-05-05 14:06:24'),
	(50, '2021-05-05 14:14:44', 0.00, 95220.00, NULL, NULL, 56, 3, 'purchase', 0, '2021-05-05 14:14:44', '2021-05-05 14:14:44'),
	(51, '2021-05-05 14:29:46', 0.00, 50.00, NULL, NULL, 57, 3, 'purchase', 0, '2021-05-05 14:29:46', '2021-05-05 14:29:46'),
	(52, '2021-05-05 14:32:28', 200.00, 700.00, NULL, NULL, 58, 3, 'purchase', 0, '2021-05-05 14:32:28', '2021-05-05 14:32:28'),
	(53, '2021-05-05 14:32:53', 0.00, 54.00, NULL, NULL, 59, 2, 'purchase', 0, '2021-05-05 14:32:53', '2021-05-05 14:32:53'),
	(54, '2021-05-05 14:33:23', 0.00, 360.00, NULL, NULL, 60, 1, 'purchase', 0, '2021-05-05 14:33:23', '2021-05-05 14:33:23'),
	(55, '2021-05-05 14:50:18', -60000.00, 60000.00, NULL, NULL, NULL, 2, 'payment', 0, '2021-05-05 14:50:18', '2021-05-05 14:50:18'),
	(56, '2021-05-05 14:50:43', -110000.00, 110000.00, NULL, NULL, NULL, 2, 'payment', 0, '2021-05-05 14:50:43', '2021-05-05 14:50:43'),
	(57, '2021-05-05 14:51:35', -10000.00, 10000.00, NULL, NULL, NULL, 2, 'payment', 0, '2021-05-05 14:51:35', '2021-05-05 14:51:35'),
	(58, '2021-05-05 14:54:29', 20000.00, -20000.00, NULL, NULL, NULL, 2, 'payment', 0, '2021-05-05 14:54:29', '2021-05-05 14:54:29');
/*!40000 ALTER TABLE `sup_ledgers` ENABLE KEYS */;

-- Dumping structure for table hascol.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `logo` text COLLATE utf8mb4_unicode_ci,
  `account_type` enum('admin','manager','staff','customer','supplier') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'staff',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `isactive` tinyint(1) NOT NULL,
  `isdeleted` tinyint(4) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`,`contact`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table hascol.users: ~1 rows (approximately)
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` (`id`, `name`, `email`, `contact`, `logo`, `account_type`, `email_verified_at`, `password`, `remember_token`, `isactive`, `isdeleted`, `created_at`, `updated_at`) VALUES
	(1, 'babar', 'abc@gmail.com', '0333428602', '/place/1.png', 'admin', NULL, '$2y$10$Nzz.j0VNsguuexYfBeCGGOk9OlbT5tYvstfGTxiKY7t0kPqFRiOcm', 'W0apyL6Hkh25DIvyoyRGn4W7jEONc4t20NpU7Vfy3RC9xF3gCmy8nzpXrcgZ', 1, 0, '2021-03-20 12:36:26', '2021-03-20 12:36:26'),
	(2, 'Amar', 'abcd@gmail.com', '03334586021', '/user/2.png', 'manager', NULL, '$2y$10$VzrsgRu9Zr3.ds2SRYzEe.uH1mZ.WRTE.kDhWWt1/q/oaILu7x1Ni', NULL, 1, 0, '2021-04-10 16:17:59', '2021-05-03 19:32:18');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;

-- Dumping structure for trigger hascol.products_after_insert
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION';
DELIMITER //
CREATE TRIGGER `products_after_insert` AFTER INSERT ON `products` FOR EACH ROW BEGIN
insert into prices (date,cost_price,retail_price,pro_id,created_at,updated_at)
   values( NEW.created_at,NEW.cost_price,NEW.retail_price,NEW.id,now(),now());
END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

-- Dumping structure for trigger hascol.products_after_update
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION';
DELIMITER //
CREATE TRIGGER `products_after_update` AFTER UPDATE ON `products` FOR EACH ROW BEGIN
IF (NEW.cost_Price != OLD.cost_Price or NEW.retail_price != OLD.retail_price ) THEN 
		insert into prices (date,cost_price,retail_price,pro_id,created_at,updated_at)
	   values( NEW.updated_at,NEW.cost_price,NEW.retail_price,NEW.id,now(),now());
   END IF;
END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
