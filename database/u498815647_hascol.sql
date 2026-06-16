-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Oct 30, 2023 at 06:42 PM
-- Server version: 10.5.19-MariaDB-cll-lve
-- PHP Version: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `u498815647_hascol`
--

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone1` varchar(255) NOT NULL,
  `phone2` varchar(255) DEFAULT NULL,
  `city` varchar(255) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `credit_limit` int(11) DEFAULT NULL,
  `opening_bal` int(11) DEFAULT NULL,
  `op_bal_id` bigint(20) UNSIGNED DEFAULT NULL,
  `isdeleted` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `date`, `name`, `email`, `phone1`, `phone2`, `city`, `address`, `credit_limit`, `opening_bal`, `op_bal_id`, `isdeleted`, `created_at`, `updated_at`) VALUES
(12, '2021-05-27 08:15:00', 'Walk In Customer', 'abc@example.com', '03000000000', NULL, 'lahore', NULL, NULL, NULL, NULL, 0, NULL, NULL),
(13, '2021-05-27 08:23:47', 'agility cargo', 'test@example.com', '03324962084', '03174533811', 'shekhpura', 'test', NULL, 239000, 69, 0, '2021-05-27 13:23:47', '2021-05-27 13:23:47');

-- --------------------------------------------------------

--
-- Table structure for table `cust_ledgers`
--

CREATE TABLE `cust_ledgers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `dr` double(255,2) DEFAULT NULL,
  `cr` double(255,2) DEFAULT NULL,
  `desc` text DEFAULT NULL,
  `adjustment` double(255,2) DEFAULT NULL,
  `sale_id` bigint(20) UNSIGNED DEFAULT NULL,
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `type` enum('sale','payment','opbl') NOT NULL DEFAULT 'sale',
  `isdeleted` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cust_ledgers`
--

INSERT INTO `cust_ledgers` (`id`, `date`, `dr`, `cr`, `desc`, `adjustment`, `sale_id`, `customer_id`, `type`, `isdeleted`, `created_at`, `updated_at`) VALUES
(69, '2021-05-27 13:23:47', 239000.00, NULL, 'opening balance', NULL, NULL, 13, 'opbl', 0, '2021-05-27 13:23:47', '2021-05-27 13:23:47'),
(70, '2021-05-27 14:10:57', 0.00, 2074069.64, NULL, NULL, 217, 12, 'sale', 0, '2021-05-27 14:10:57', '2021-05-27 14:10:57'),
(71, '2021-05-29 13:15:27', -130000.00, 130000.00, 'online cash deposit in ar cng', NULL, NULL, 13, 'payment', 0, '2021-05-29 13:12:42', '2021-05-29 13:15:27'),
(72, '2022-02-05 08:27:22', 0.00, 1092.00, NULL, NULL, 220, 12, 'sale', 1, '2022-02-05 13:27:11', '2022-02-05 13:27:22');

-- --------------------------------------------------------

--
-- Table structure for table `dips`
--

CREATE TABLE `dips` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `pro_id` bigint(20) UNSIGNED NOT NULL,
  `qty` double(255,2) NOT NULL DEFAULT 0.00,
  `sighn` varchar(50) DEFAULT NULL,
  `desc` varchar(50) DEFAULT NULL,
  `change_in_qty` double(255,2) DEFAULT NULL,
  `isdeleted` tinyint(1) DEFAULT 0,
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `dips`
--

INSERT INTO `dips` (`id`, `pro_id`, `qty`, `sighn`, `desc`, `change_in_qty`, `isdeleted`, `date`, `created_at`, `updated_at`) VALUES
(67, 12, 17237.00, '+', 'test', 17237.00, 0, '2021-05-27 13:10:03', '2021-05-27 13:10:03', '2021-05-27 13:10:03'),
(68, 13, 21108.00, '+', NULL, 21108.00, 0, '2021-05-27 13:43:43', '2021-05-27 13:43:43', '2021-05-27 13:43:43');

-- --------------------------------------------------------

--
-- Table structure for table `expenses`
--

CREATE TABLE `expenses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `desc` text DEFAULT NULL,
  `amount` int(11) NOT NULL,
  `exp_type_id` bigint(20) UNSIGNED NOT NULL,
  `isdeleted` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `expenses`
--

INSERT INTO `expenses` (`id`, `date`, `desc`, `amount`, `exp_type_id`, `isdeleted`, `created_at`, `updated_at`) VALUES
(19, '2021-05-29 13:24:02', NULL, 650, 10, 0, '2021-05-29 13:24:02', '2021-05-29 13:24:02');

-- --------------------------------------------------------

--
-- Table structure for table `exp_types`
--

CREATE TABLE `exp_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `desc` text DEFAULT NULL,
  `isdeleted` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `exp_types`
--

INSERT INTO `exp_types` (`id`, `name`, `type`, `desc`, `isdeleted`, `created_at`, `updated_at`) VALUES
(10, 'staff food', 'Daily', NULL, 0, '2021-05-29 13:21:49', '2021-05-29 13:21:49');

-- --------------------------------------------------------

--
-- Table structure for table `fuel_backups`
--

CREATE TABLE `fuel_backups` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `pro_id` bigint(20) UNSIGNED NOT NULL,
  `pur_id` bigint(20) UNSIGNED NOT NULL,
  `sku` varchar(255) NOT NULL,
  `qty` double(255,2) NOT NULL DEFAULT 0.00,
  `fqty` double(255,2) DEFAULT 0.00,
  `stock_capacity` double(255,2) NOT NULL,
  `desc` text DEFAULT NULL,
  `isdeleted` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `fuel_backups`
--

INSERT INTO `fuel_backups` (`id`, `pro_id`, `pur_id`, `sku`, `qty`, `fqty`, `stock_capacity`, `desc`, `isdeleted`, `created_at`, `updated_at`) VALUES
(10, 12, 76, 'p-001', 500.00, 500.00, 1000.00, NULL, 1, '2021-05-29 13:03:01', '2021-05-29 13:27:35'),
(11, 13, 76, 'd-01', 500.00, 500.00, 1000.00, NULL, 1, '2021-05-29 13:03:01', '2021-05-29 13:27:35');

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

-- --------------------------------------------------------

--
-- Table structure for table `prices`
--

CREATE TABLE `prices` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `cost_price` double(8,2) NOT NULL,
  `retail_price` double(8,2) NOT NULL,
  `comments` text DEFAULT NULL,
  `pro_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `prices`
--

INSERT INTO `prices` (`id`, `date`, `cost_price`, `retail_price`, `comments`, `pro_id`, `created_at`, `updated_at`) VALUES
(13, '2021-05-27 12:59:12', 106.00, 109.00, NULL, 12, '2021-05-27 07:59:12', '2021-05-27 07:59:12'),
(14, '2021-05-27 13:04:12', 106.45, 109.20, NULL, 12, '2021-05-27 08:04:12', '2021-05-27 08:04:12'),
(15, '2021-05-27 13:06:43', 109.00, 111.30, NULL, 13, '2021-05-27 08:06:43', '2021-05-27 08:06:43');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `sku` varchar(255) NOT NULL,
  `alert_qty` varchar(255) NOT NULL,
  `cost_Price` double(8,2) NOT NULL,
  `retail_price` double(8,2) NOT NULL,
  `desc` text DEFAULT NULL,
  `isdeleted` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `sku`, `alert_qty`, `cost_Price`, `retail_price`, `desc`, `isdeleted`, `created_at`, `updated_at`) VALUES
(12, 'pmg', 'p-001', '50', 106.45, 109.20, NULL, 0, '2021-05-27 12:59:12', '2021-05-27 13:04:12'),
(13, 'hsd', 'd-01', '50', 109.00, 111.30, NULL, 0, '2021-05-27 13:06:43', '2021-05-27 13:06:43');

--
-- Triggers `products`
--
DELIMITER $$
CREATE TRIGGER `products_after_insert` AFTER INSERT ON `products` FOR EACH ROW BEGIN
insert into prices (date,cost_price,retail_price,pro_id,created_at,updated_at)
   values( NEW.created_at,NEW.cost_price,NEW.retail_price,NEW.id,now(),now());
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `products_after_update` AFTER UPDATE ON `products` FOR EACH ROW BEGIN
IF (NEW.cost_Price != OLD.cost_Price or NEW.retail_price != OLD.retail_price ) THEN 
		insert into prices (date,cost_price,retail_price,pro_id,created_at,updated_at)
	   values( NEW.updated_at,NEW.cost_price,NEW.retail_price,NEW.id,now(),now());
   END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `purchases`
--

CREATE TABLE `purchases` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `inv_no` bigint(20) NOT NULL DEFAULT 0,
  `desc` text DEFAULT NULL,
  `sup_bill_no` varchar(255) DEFAULT NULL,
  `sup_id` bigint(20) UNSIGNED NOT NULL,
  `total_qty` bigint(20) NOT NULL,
  `retail_amount` bigint(20) DEFAULT NULL,
  `cost_amount` bigint(20) NOT NULL,
  `pur_type` varchar(50) DEFAULT NULL,
  `adjustment` bigint(20) DEFAULT NULL,
  `isdeleted` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `purchases`
--

INSERT INTO `purchases` (`id`, `date`, `inv_no`, `desc`, `sup_bill_no`, `sup_id`, `total_qty`, `retail_amount`, `cost_amount`, `pur_type`, `adjustment`, `isdeleted`, `created_at`, `updated_at`) VALUES
(76, '2021-05-29 08:27:35', 1, NULL, '25', 7, 1000, 110250, 107725, 'backup', NULL, 1, '2021-05-29 13:03:01', '2021-05-29 13:27:35');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_items`
--

CREATE TABLE `purchase_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `pro_id` bigint(20) UNSIGNED NOT NULL,
  `pur_id` bigint(20) UNSIGNED NOT NULL,
  `sku` varchar(255) NOT NULL,
  `qty` bigint(20) NOT NULL,
  `cost_price` bigint(20) NOT NULL,
  `retail_price` bigint(20) NOT NULL,
  `sub_total` bigint(20) NOT NULL,
  `isdeleted` tinyint(4) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `purchase_items`
--

INSERT INTO `purchase_items` (`id`, `date`, `pro_id`, `pur_id`, `sku`, `qty`, `cost_price`, `retail_price`, `sub_total`, `isdeleted`, `created_at`, `updated_at`) VALUES
(77, '2021-05-29 08:27:35', 12, 76, 'p-001', 500, 106, 109, 53225, 1, '2021-05-29 13:03:01', '2021-05-29 13:27:35'),
(78, '2021-05-29 08:27:35', 13, 76, 'd-01', 500, 109, 111, 54500, 1, '2021-05-29 13:03:01', '2021-05-29 13:27:35');

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `invoice_no` bigint(20) NOT NULL DEFAULT 0,
  `cost_amount` double(255,2) NOT NULL,
  `retail_amount` double(255,2) NOT NULL,
  `desc` text DEFAULT NULL,
  `total_qty` double(255,2) NOT NULL,
  `adjustment` double(255,2) DEFAULT 0.00,
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `isdeleted` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`id`, `date`, `invoice_no`, `cost_amount`, `retail_amount`, `desc`, `total_qty`, `adjustment`, `customer_id`, `isdeleted`, `created_at`, `updated_at`) VALUES
(217, '2021-05-27', 1, 2027888.93, 2074069.64, NULL, 18761.92, NULL, 12, 0, '2021-05-27 14:10:57', '2021-05-27 14:10:57'),
(220, '2022-02-05', 2, 1064.50, 1092.00, NULL, 10.00, NULL, 12, 1, '2022-02-05 13:27:11', '2022-02-05 13:27:22');

-- --------------------------------------------------------

--
-- Table structure for table `sales_items`
--

CREATE TABLE `sales_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `sale_id` bigint(20) UNSIGNED NOT NULL,
  `pro_id` bigint(20) UNSIGNED NOT NULL,
  `sku` varchar(255) NOT NULL,
  `qty` double(8,2) NOT NULL,
  `cost_price` double(8,2) DEFAULT NULL,
  `subtotal` bigint(20) NOT NULL DEFAULT 0,
  `retail_price` double(8,2) NOT NULL,
  `desc` text DEFAULT NULL,
  `isdeleted` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sales_items`
--

INSERT INTO `sales_items` (`id`, `date`, `sale_id`, `pro_id`, `sku`, `qty`, `cost_price`, `subtotal`, `retail_price`, `desc`, `isdeleted`, `created_at`, `updated_at`) VALUES
(115, '2021-05-27 14:10:57', 217, 12, 'p-001', 6729.55, 106.45, 734867, 109.20, NULL, 0, '2021-05-27 14:10:57', '2021-05-27 14:10:57'),
(116, '2021-05-27 14:10:57', 217, 13, 'd-01', 12032.37, 109.00, 1339203, 111.30, NULL, 0, '2021-05-27 14:10:57', '2021-05-27 14:10:57'),
(117, '2022-02-05 08:27:25', 220, 12, 'p-001', 10.00, 106.45, 1092, 109.20, NULL, 1, '2022-02-05 13:27:11', '2022-02-05 13:27:25');

-- --------------------------------------------------------

--
-- Table structure for table `stocks`
--

CREATE TABLE `stocks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `pro_id` bigint(20) UNSIGNED NOT NULL,
  `dip_id` bigint(20) UNSIGNED DEFAULT NULL,
  `sku` varchar(255) NOT NULL,
  `desc` text DEFAULT NULL,
  `qty` double(255,2) NOT NULL DEFAULT 0.00,
  `stock_capacity` double(255,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stocks`
--

INSERT INTO `stocks` (`id`, `pro_id`, `dip_id`, `sku`, `desc`, `qty`, `stock_capacity`, `created_at`, `updated_at`) VALUES
(8, 12, 67, 'p-001', NULL, 10517.45, 23000.00, '2021-05-27 12:59:12', '2022-02-05 13:27:25'),
(9, 13, 68, 'd-01', NULL, 9075.63, 23000.00, '2021-05-27 13:06:43', '2021-05-27 14:10:57');

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `company` varchar(50) DEFAULT '',
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone1` varchar(255) NOT NULL,
  `phone2` varchar(255) DEFAULT NULL,
  `city` varchar(255) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `opening_bal` int(11) DEFAULT NULL,
  `op_bal_id` bigint(20) UNSIGNED DEFAULT NULL,
  `isdeleted` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`id`, `date`, `company`, `name`, `email`, `phone1`, `phone2`, `city`, `address`, `opening_bal`, `op_bal_id`, `isdeleted`, `created_at`, `updated_at`) VALUES
(7, '2021-05-27 09:18:14', '', 'azib mukhtar', 'example@exampl.com', '03324466484', NULL, 'gujranwala', 'gujranwala', 289239, 79, 0, '2021-05-27 14:18:14', '2021-05-27 14:18:14'),
(8, '2021-05-27 12:46:18', 'hascol', 'hascol', 'info@hascol.com', '04235718033', NULL, 'lahore', 'lahore', 1192686, 80, 0, '2021-05-27 17:46:18', '2021-05-27 17:46:18');

-- --------------------------------------------------------

--
-- Table structure for table `sup_ledgers`
--

CREATE TABLE `sup_ledgers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `dr` double(255,2) DEFAULT NULL,
  `cr` double(255,2) DEFAULT NULL,
  `adjustment` double(255,2) DEFAULT NULL,
  `desc` text DEFAULT NULL,
  `pur_id` bigint(20) UNSIGNED DEFAULT NULL,
  `sup_id` bigint(20) UNSIGNED NOT NULL,
  `type` enum('purchase','payment','opbl') NOT NULL DEFAULT 'purchase',
  `isdeleted` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sup_ledgers`
--

INSERT INTO `sup_ledgers` (`id`, `date`, `dr`, `cr`, `adjustment`, `desc`, `pur_id`, `sup_id`, `type`, `isdeleted`, `created_at`, `updated_at`) VALUES
(79, '2021-05-27 14:18:14', 289239.00, NULL, NULL, 'opening balance', NULL, 7, 'opbl', 0, '2021-05-27 14:18:14', '2021-05-27 14:18:14'),
(80, '2021-05-27 17:46:18', 1192686.00, NULL, NULL, 'opening balance', NULL, 8, 'opbl', 0, '2021-05-27 17:46:18', '2021-05-27 17:46:18'),
(81, '2021-05-29 08:27:35', 57725.00, 50000.00, NULL, NULL, 76, 7, 'purchase', 1, '2021-05-29 13:03:01', '2021-05-29 13:27:35'),
(82, '2021-05-29 13:16:55', -500000.00, 500000.00, NULL, NULL, NULL, 7, 'payment', 0, '2021-05-29 13:16:55', '2021-05-29 13:16:55');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `contact` varchar(50) NOT NULL,
  `logo` text DEFAULT NULL,
  `account_type` enum('admin','manager','staff','customer','supplier') NOT NULL DEFAULT 'staff',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `isactive` tinyint(1) NOT NULL,
  `isdeleted` tinyint(4) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `contact`, `logo`, `account_type`, `email_verified_at`, `password`, `remember_token`, `isactive`, `isdeleted`, `created_at`, `updated_at`) VALUES
(1, 'hashim', 'ar_cng@yahoo.com', '03008419638', '/user/1.jpg', 'admin', NULL, '$2y$10$eyRcRUMxqLYMHVAl.Au/iuACQDRL4rQjFC6VTsJ4MgONJaplwGLb.', 'sUQSWlXMVFcrpI9NqRIEhKUzEB7I0XRuZOEuA1TT8n6n3HxGUmlpJxBITWDQ', 1, 0, '2021-03-20 12:36:26', '2021-05-12 14:42:43'),
(12, 'faizurrehman', 'faizurrehmanf@60gmail.com', '03334049839', '/place/1.png', 'manager', NULL, '$2y$10$PObx5JBWGpp3JoA3uJP5FO5UXl.9E5pUjqcSAClwTGoYg0eYHRmOy', NULL, 0, 1, '2021-05-26 19:11:10', '2021-05-26 19:18:11');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `phone1` (`phone1`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `name` (`name`),
  ADD KEY `customers_op_bal_id_foreign` (`op_bal_id`);

--
-- Indexes for table `cust_ledgers`
--
ALTER TABLE `cust_ledgers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cust_ledgers_sale_id_foreign` (`sale_id`),
  ADD KEY `cust_ledgers_customer_id_foreign` (`customer_id`);

--
-- Indexes for table `dips`
--
ALTER TABLE `dips`
  ADD PRIMARY KEY (`id`),
  ADD KEY `dips_pro_id_foreign` (`pro_id`);

--
-- Indexes for table `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `expenses_exp_type_id_foreign` (`exp_type_id`);

--
-- Indexes for table `exp_types`
--
ALTER TABLE `exp_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fuel_backups`
--
ALTER TABLE `fuel_backups`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fuel_backups_pro_id_foreign` (`pro_id`),
  ADD KEY `fuel_backups_pur_id_foreign` (`pur_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `prices`
--
ALTER TABLE `prices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `prices_pro_id_foreign` (`pro_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `products_sku_unique` (`sku`);

--
-- Indexes for table `purchases`
--
ALTER TABLE `purchases`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchases_sup_id_foreign` (`sup_id`);

--
-- Indexes for table `purchase_items`
--
ALTER TABLE `purchase_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchase_items_pro_id_foreign` (`pro_id`),
  ADD KEY `purchase_items_pur_id_foreign` (`pur_id`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sales_customer_id_foreign` (`customer_id`);

--
-- Indexes for table `sales_items`
--
ALTER TABLE `sales_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sales_items_sale_id_foreign` (`sale_id`),
  ADD KEY `sales_items_pro_id_foreign` (`pro_id`);

--
-- Indexes for table `stocks`
--
ALTER TABLE `stocks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stocks_pro_id_foreign` (`pro_id`),
  ADD KEY `stocks_dip_id_foreign` (`dip_id`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `phone1` (`phone1`),
  ADD KEY `suppliers_op_bal_id_foreign` (`op_bal_id`);

--
-- Indexes for table `sup_ledgers`
--
ALTER TABLE `sup_ledgers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sup_ledgers_pur_id_foreign` (`pur_id`),
  ADD KEY `sup_ledgers_sup_id_foreign` (`sup_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`,`contact`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `cust_ledgers`
--
ALTER TABLE `cust_ledgers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

--
-- AUTO_INCREMENT for table `dips`
--
ALTER TABLE `dips`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- AUTO_INCREMENT for table `expenses`
--
ALTER TABLE `expenses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `exp_types`
--
ALTER TABLE `exp_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `fuel_backups`
--
ALTER TABLE `fuel_backups`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `prices`
--
ALTER TABLE `prices`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `purchases`
--
ALTER TABLE `purchases`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;

--
-- AUTO_INCREMENT for table `purchase_items`
--
ALTER TABLE `purchase_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=79;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=221;

--
-- AUTO_INCREMENT for table `sales_items`
--
ALTER TABLE `sales_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=118;

--
-- AUTO_INCREMENT for table `stocks`
--
ALTER TABLE `stocks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `sup_ledgers`
--
ALTER TABLE `sup_ledgers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=83;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `customers`
--
ALTER TABLE `customers`
  ADD CONSTRAINT `customers_op_bal_id_foreign` FOREIGN KEY (`op_bal_id`) REFERENCES `cust_ledgers` (`id`);

--
-- Constraints for table `cust_ledgers`
--
ALTER TABLE `cust_ledgers`
  ADD CONSTRAINT `cust_ledgers_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`),
  ADD CONSTRAINT `cust_ledgers_sale_id_foreign` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`id`);

--
-- Constraints for table `dips`
--
ALTER TABLE `dips`
  ADD CONSTRAINT `dips_pro_id_foreign` FOREIGN KEY (`pro_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `expenses`
--
ALTER TABLE `expenses`
  ADD CONSTRAINT `expenses_exp_type_id_foreign` FOREIGN KEY (`exp_type_id`) REFERENCES `exp_types` (`id`);

--
-- Constraints for table `fuel_backups`
--
ALTER TABLE `fuel_backups`
  ADD CONSTRAINT `fuel_backups_pro_id_foreign` FOREIGN KEY (`pro_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `fuel_backups_pur_id_foreign` FOREIGN KEY (`pur_id`) REFERENCES `purchases` (`id`);

--
-- Constraints for table `prices`
--
ALTER TABLE `prices`
  ADD CONSTRAINT `prices_pro_id_foreign` FOREIGN KEY (`pro_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `purchases`
--
ALTER TABLE `purchases`
  ADD CONSTRAINT `purchases_sup_id_foreign` FOREIGN KEY (`sup_id`) REFERENCES `suppliers` (`id`);

--
-- Constraints for table `purchase_items`
--
ALTER TABLE `purchase_items`
  ADD CONSTRAINT `purchase_items_pro_id_foreign` FOREIGN KEY (`pro_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `purchase_items_pur_id_foreign` FOREIGN KEY (`pur_id`) REFERENCES `purchases` (`id`);

--
-- Constraints for table `sales`
--
ALTER TABLE `sales`
  ADD CONSTRAINT `sales_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`);

--
-- Constraints for table `sales_items`
--
ALTER TABLE `sales_items`
  ADD CONSTRAINT `sales_items_pro_id_foreign` FOREIGN KEY (`pro_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `sales_items_sale_id_foreign` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`id`);

--
-- Constraints for table `stocks`
--
ALTER TABLE `stocks`
  ADD CONSTRAINT `stocks_dip_id_foreign` FOREIGN KEY (`dip_id`) REFERENCES `dips` (`id`),
  ADD CONSTRAINT `stocks_pro_id_foreign` FOREIGN KEY (`pro_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD CONSTRAINT `suppliers_op_bal_id_foreign` FOREIGN KEY (`op_bal_id`) REFERENCES `sup_ledgers` (`id`);

--
-- Constraints for table `sup_ledgers`
--
ALTER TABLE `sup_ledgers`
  ADD CONSTRAINT `sup_ledgers_pur_id_foreign` FOREIGN KEY (`pur_id`) REFERENCES `purchases` (`id`),
  ADD CONSTRAINT `sup_ledgers_sup_id_foreign` FOREIGN KEY (`sup_id`) REFERENCES `suppliers` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
