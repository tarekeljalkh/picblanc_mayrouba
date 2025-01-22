-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jan 22, 2025 at 10:12 AM
-- Server version: 8.0.30
-- PHP Version: 8.3.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `picblanc_mayrouba`
--

-- --------------------------------------------------------

--
-- Table structure for table `additional_items`
--

CREATE TABLE `additional_items` (
  `id` bigint UNSIGNED NOT NULL,
  `invoice_id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `quantity` int NOT NULL,
  `returned_quantity` int NOT NULL DEFAULT '0',
  `price` decimal(10,2) NOT NULL,
  `days` int DEFAULT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `rental_start_date` datetime DEFAULT NULL,
  `rental_end_date` datetime DEFAULT NULL,
  `status` enum('draft','active','returned','overdue') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `additional_items`
--

INSERT INTO `additional_items` (`id`, `invoice_id`, `product_id`, `quantity`, `returned_quantity`, `price`, `days`, `total_price`, `rental_start_date`, `rental_end_date`, `status`, `created_at`, `updated_at`) VALUES
(6, 146, 24, 1, 1, '5.00', 1, '5.00', NULL, NULL, 'returned', '2025-01-11 06:15:26', '2025-01-11 14:20:29'),
(7, 146, 23, 1, 1, '5.00', 1, '5.00', NULL, NULL, 'returned', '2025-01-11 06:15:26', '2025-01-11 14:20:29'),
(8, 167, 23, 1, 1, '5.00', 1, '5.00', NULL, NULL, 'returned', '2025-01-12 06:05:49', '2025-01-12 11:36:50');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'daily', '2025-01-02 10:10:33', '2025-01-02 10:10:33'),
(2, 'season', '2025-01-02 10:10:33', '2025-01-02 10:10:33');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone2` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deposit_card` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `name`, `phone`, `phone2`, `address`, `deposit_card`, `created_at`, `updated_at`) VALUES
(3, 'Jennifer Sayegh', '03027259', NULL, NULL, NULL, '2025-01-09 14:23:22', '2025-01-09 14:23:22'),
(4, 'Michel Moutran (Saria moutran)', '03348112', '03253968', NULL, NULL, '2025-01-09 14:43:08', '2025-01-09 14:43:08'),
(5, 'Alban arthur', '70228862', NULL, NULL, NULL, '2025-01-09 14:47:47', '2025-01-09 14:47:47'),
(6, 'Nadine baydoun', '78893793', NULL, NULL, NULL, '2025-01-09 14:48:31', '2025-01-09 14:48:31'),
(7, 'Rawane korbani', '03788458', NULL, NULL, NULL, '2025-01-09 14:50:12', '2025-01-09 14:50:12'),
(8, 'Ronald zirka', '03242487', NULL, NULL, NULL, '2025-01-09 14:53:12', '2025-01-09 14:53:12'),
(9, 'Gaby Charbachi', '03860089', NULL, NULL, NULL, '2025-01-09 14:54:10', '2025-01-09 14:54:10'),
(10, 'Adel karam', '71500490', NULL, NULL, NULL, '2025-01-09 14:56:15', '2025-01-09 14:56:15'),
(11, 'Paul nakhoul', '70544661', NULL, NULL, NULL, '2025-01-09 14:58:27', '2025-01-09 14:58:27'),
(12, 'Elie Azrieh (Elio abdo)', '70630647', NULL, NULL, NULL, '2025-01-09 15:00:09', '2025-01-09 15:00:09'),
(13, 'Karim Fallaha', '03741606', NULL, NULL, NULL, '2025-01-09 15:02:29', '2025-01-09 15:02:29'),
(14, 'Samer Atoumani', '03441265', NULL, NULL, NULL, '2025-01-09 15:03:47', '2025-01-09 15:03:47'),
(15, 'Karl jallad', '03900770', NULL, NULL, NULL, '2025-01-09 15:04:19', '2025-01-09 15:04:19'),
(16, 'Marwan Nasr', '03545557', NULL, NULL, NULL, '2025-01-09 15:05:53', '2025-01-09 15:05:53'),
(17, 'Ralph kai', '03641628', NULL, NULL, NULL, '2025-01-09 15:08:35', '2025-01-09 15:08:35'),
(18, 'Elie chaccour', '03714454', NULL, NULL, NULL, '2025-01-09 15:10:39', '2025-01-09 15:10:39'),
(19, 'Moussa khalil', '81081051', NULL, NULL, NULL, '2025-01-09 15:11:58', '2025-01-09 15:11:58'),
(20, 'Philippe nahhas (Sima)', '03346481', NULL, NULL, NULL, '2025-01-09 15:13:14', '2025-01-09 15:13:14'),
(21, 'Karim james', '03236665', NULL, NULL, NULL, '2025-01-09 15:15:56', '2025-01-09 15:15:56'),
(22, 'Anne marie romanos', '03240998', NULL, NULL, NULL, '2025-01-09 15:32:04', '2025-01-09 15:32:04'),
(23, 'Naji ziadeh', '70080702', NULL, NULL, NULL, '2025-01-09 15:40:29', '2025-01-09 15:40:29'),
(24, 'Anthony Aoun (Anna Sylvana)', '03688658', NULL, NULL, NULL, '2025-01-09 15:41:29', '2025-01-09 15:41:29'),
(25, 'Ziad maatouk', '03959499', NULL, NULL, NULL, '2025-01-09 15:43:12', '2025-01-09 15:43:12'),
(26, 'Serge Naimeh', '71758282', NULL, NULL, NULL, '2025-01-09 15:44:08', '2025-01-09 15:44:08'),
(27, 'Michel mfarrej', '03919212', NULL, NULL, NULL, '2025-01-09 15:45:28', '2025-01-09 15:45:28'),
(28, 'Charbel nabil nakhoul', '03624482', NULL, NULL, NULL, '2025-01-09 15:46:11', '2025-01-09 15:46:11'),
(29, 'Rayan Berbari', '81020760', NULL, NULL, NULL, '2025-01-09 15:55:13', '2025-01-09 15:55:13'),
(30, 'Jouna mnassa', '70709060', NULL, NULL, NULL, '2025-01-09 15:56:27', '2025-01-09 15:56:27'),
(31, 'Wakim Herro', '70252525', NULL, NULL, NULL, '2025-01-09 15:57:35', '2025-01-09 15:57:35'),
(32, 'Teddy El rami', '03960096', NULL, NULL, NULL, '2025-01-09 15:58:58', '2025-01-09 15:58:58'),
(33, 'Fadi nasr', '03398883', NULL, NULL, NULL, '2025-01-09 16:00:15', '2025-01-09 16:00:15'),
(34, 'Marwan zgheib', '03317170', NULL, NULL, NULL, '2025-01-09 16:03:26', '2025-01-09 16:03:26'),
(35, 'Mikael dahdah', '03770199', NULL, NULL, NULL, '2025-01-09 16:07:07', '2025-01-09 16:07:07'),
(36, 'Marwan senno', '03555544', NULL, NULL, NULL, '2025-01-09 16:09:01', '2025-01-09 16:09:01'),
(37, 'Stephanie (Meera)', '76404020', NULL, NULL, NULL, '2025-01-10 08:41:32', '2025-01-10 08:41:32'),
(38, 'Patrick Mhanna', '03941281', NULL, NULL, NULL, '2025-01-10 08:42:34', '2025-01-10 08:42:34'),
(39, 'Walid Khoury', '71739187', NULL, NULL, NULL, '2025-01-10 08:44:40', '2025-01-10 08:44:40'),
(40, 'Valerie Berbarie', '03984534', NULL, NULL, NULL, '2025-01-10 08:47:30', '2025-01-10 08:47:30'),
(41, 'Marcel Choukri', '03683001', NULL, NULL, NULL, '2025-01-10 09:25:31', '2025-01-10 09:25:31'),
(42, 'Karim Minassian', '03411821', NULL, NULL, NULL, '2025-01-10 09:27:45', '2025-01-10 09:27:45'),
(43, 'Roy Habib', '03678971', NULL, NULL, NULL, '2025-01-10 09:30:42', '2025-01-10 09:30:42'),
(44, 'Richard Abi Habib (alain khairalah)', '03566312', NULL, NULL, NULL, '2025-01-10 09:33:29', '2025-01-10 09:33:29'),
(45, 'Nathalie Zahar', '03625154', NULL, NULL, NULL, '2025-01-10 09:34:07', '2025-01-10 09:34:07'),
(46, 'Sara Dandan ( Joe Jibrine )', '03604848', NULL, NULL, NULL, '2025-01-10 09:35:43', '2025-01-10 09:35:43'),
(47, 'Ghina Sedani', '03876519', NULL, NULL, NULL, '2025-01-10 09:37:02', '2025-01-10 09:37:02'),
(48, 'Ghina ( Joe Jibrine )', '03515745', NULL, NULL, NULL, '2025-01-10 09:38:38', '2025-01-10 09:38:38'),
(49, 'Charbel Sfeir ( SGA )', '71554421', NULL, NULL, NULL, '2025-01-10 09:40:16', '2025-01-10 09:40:16'),
(50, 'Emil Salhab', '03675789', NULL, NULL, NULL, '2025-01-10 09:41:11', '2025-01-10 09:41:11'),
(51, 'Ralf Yazji', '70939427', NULL, NULL, NULL, '2025-01-10 09:43:49', '2025-01-10 09:43:49'),
(52, 'Kevin Mdawar ( SGA )', '79174441', NULL, NULL, NULL, '2025-01-10 09:46:22', '2025-01-10 09:46:22'),
(53, 'Alain Khayrallah', '03678618', NULL, NULL, NULL, '2025-01-10 09:47:43', '2025-01-10 09:47:43'),
(54, 'Wissam Tabet', '03892892', NULL, NULL, NULL, '2025-01-10 09:50:18', '2025-01-10 09:50:18'),
(55, 'Hany Saadeh', '03450630', NULL, NULL, NULL, '2025-01-10 09:51:01', '2025-01-10 09:51:01'),
(56, 'Mamdouh Abou Khatir (Marwan Zgheib)', '79112372', NULL, NULL, NULL, '2025-01-10 09:52:39', '2025-01-10 09:52:39'),
(57, 'Wadih Haddad', '03786390', NULL, NULL, NULL, '2025-01-10 09:53:13', '2025-01-10 09:53:13'),
(58, 'Antoine Habchy', '03796555', NULL, NULL, NULL, '2025-01-10 09:54:01', '2025-01-10 09:54:01'),
(59, 'Joe Abou Jawde', '81614809', NULL, NULL, NULL, '2025-01-10 09:54:54', '2025-01-10 09:54:54'),
(60, 'Riyad Khalil', '70421122', NULL, NULL, NULL, '2025-01-10 09:55:24', '2025-01-10 09:55:24'),
(61, 'Carl Najjar', '03406406', NULL, NULL, NULL, '2025-01-10 09:56:17', '2025-01-10 09:56:17'),
(62, 'Mila Riachy ( Rhea)', '71606033', NULL, NULL, NULL, '2025-01-10 09:57:26', '2025-01-10 09:57:26'),
(63, 'Paul Skayem (Naji Ziadeh)', '03019718', NULL, NULL, NULL, '2025-01-10 09:58:25', '2025-01-10 09:58:25'),
(64, 'charbel Jreij ( SGA )', '76988959', NULL, NULL, NULL, '2025-01-10 09:59:28', '2025-01-10 09:59:28'),
(65, 'Pauline Malik', '03757858', NULL, NULL, NULL, '2025-01-10 10:47:42', '2025-01-10 10:47:42'),
(66, 'Gilbert Sammour', '70010008', NULL, NULL, NULL, '2025-01-10 10:48:21', '2025-01-10 10:48:21'),
(67, 'Romy Saade', '70802995', NULL, NULL, NULL, '2025-01-10 10:49:12', '2025-01-10 10:49:12'),
(68, 'Wissam Boueiry', '03782683', NULL, NULL, NULL, '2025-01-10 10:50:06', '2025-01-10 10:50:06'),
(69, 'Maria Sfeir ( SGA )', '70281290', NULL, NULL, NULL, '2025-01-10 10:53:29', '2025-01-10 10:53:29'),
(70, 'Charbel saade', '03664901', NULL, NULL, NULL, '2025-01-10 10:54:17', '2025-01-10 10:54:17'),
(71, 'Talal Salem', '70654630', NULL, NULL, NULL, '2025-01-10 10:55:48', '2025-01-10 10:55:48'),
(72, 'Talal Kadoura', '78923796', NULL, NULL, NULL, '2025-01-10 10:57:08', '2025-01-10 10:57:08'),
(73, '3erzel Restaurant', '76100545', NULL, NULL, NULL, '2025-01-10 10:59:21', '2025-01-10 10:59:21'),
(74, 'Kyra Abi Nasr', '76936444', NULL, NULL, NULL, '2025-01-10 11:01:12', '2025-01-10 11:01:12'),
(75, 'Raya Maamarbachi', '03127069', NULL, NULL, NULL, '2025-01-10 11:01:49', '2025-01-10 11:01:49'),
(76, 'Jeffrey ( SGA )', '71825069', NULL, NULL, NULL, '2025-01-10 11:03:21', '2025-01-10 11:03:21'),
(77, 'Yamen Mattah', '03477295', NULL, NULL, NULL, '2025-01-10 11:10:44', '2025-01-10 11:10:44'),
(78, 'Hania Boustany', '03200132', NULL, NULL, NULL, '2025-01-10 11:12:28', '2025-01-10 11:12:28'),
(79, 'Gabriel Rizk', '81220379', NULL, NULL, NULL, '2025-01-10 11:13:07', '2025-01-10 11:13:07'),
(80, 'nabil makary', '03303006', NULL, NULL, NULL, '2025-01-10 11:15:34', '2025-01-10 11:15:34'),
(81, 'dany abou jamra', '03547266', NULL, NULL, NULL, '2025-01-10 11:18:44', '2025-01-10 11:18:44'),
(82, 'jean noel mansour', '79195447', NULL, NULL, NULL, '2025-01-10 11:19:45', '2025-01-10 11:19:45'),
(83, 'rima khoury', '03277310', NULL, NULL, NULL, '2025-01-10 11:20:52', '2025-01-10 11:20:52'),
(84, 'jean charbel jabbour', '78861116', NULL, NULL, NULL, '2025-01-10 11:21:45', '2025-01-10 11:21:45'),
(85, 'joelle habib', '70211100', NULL, NULL, NULL, '2025-01-10 11:22:40', '2025-01-10 11:22:40'),
(86, 'mila khoury', '03929977', NULL, NULL, NULL, '2025-01-10 11:23:45', '2025-01-10 11:23:45'),
(87, 'zeina nassif', '71760045', NULL, NULL, NULL, '2025-01-10 11:24:27', '2025-01-10 11:24:27'),
(88, 'nassim el ramy', '03969969', NULL, NULL, NULL, '2025-01-10 11:25:05', '2025-01-10 11:25:05'),
(89, 'karim wahab', '03606642', NULL, NULL, NULL, '2025-01-10 11:26:59', '2025-01-10 11:26:59'),
(90, 'gillene elias', '03333980', NULL, NULL, NULL, '2025-01-10 11:27:59', '2025-01-10 11:27:59'),
(91, 'cynthia hkayem', '03351227', NULL, NULL, NULL, '2025-01-10 11:28:32', '2025-01-10 11:28:32'),
(92, 'myriam rahme', '70882200', NULL, NULL, NULL, '2025-01-10 11:29:13', '2025-01-10 11:29:13'),
(93, 'peter mrakadi', '70800323', NULL, NULL, NULL, '2025-01-10 11:30:47', '2025-01-10 11:30:47'),
(94, 'carine daccache', '03214727', NULL, NULL, NULL, '2025-01-10 11:31:39', '2025-01-10 11:31:39'),
(95, 'ziad naccouzy', '03225575', NULL, NULL, NULL, '2025-01-10 11:35:40', '2025-01-10 11:35:40'),
(96, 'ghinwa chbeir', '71766444', NULL, NULL, NULL, '2025-01-10 11:36:23', '2025-01-10 11:36:23'),
(97, 'sarah nassar', '03699728', NULL, NULL, NULL, '2025-01-10 11:38:55', '2025-01-10 11:38:55'),
(98, 'woody zailah', '03666168', NULL, NULL, NULL, '2025-01-10 11:39:26', '2025-01-10 11:39:26'),
(99, 'karim korbani', '03764648', NULL, NULL, NULL, '2025-01-10 11:40:26', '2025-01-10 11:40:26'),
(100, 'livan dekermenjian', '03003372', NULL, NULL, NULL, '2025-01-10 11:41:29', '2025-01-10 11:41:29'),
(101, 'eliano kalabian ( jennifer sayegh)', '79171767', NULL, NULL, NULL, '2025-01-10 11:42:29', '2025-01-10 11:42:29'),
(102, 'elie alfred saade', '03446667', NULL, NULL, NULL, '2025-01-10 11:43:11', '2025-01-10 11:43:11'),
(103, 'roland abdel jalil', '035466551', NULL, NULL, NULL, '2025-01-10 11:43:47', '2025-01-10 11:43:47'),
(104, 'raymond njeim', '70695519', NULL, NULL, NULL, '2025-01-10 11:44:41', '2025-01-10 11:44:41'),
(105, 'maroun saker', '03669313', NULL, NULL, NULL, '2025-01-10 11:45:29', '2025-01-10 11:45:29'),
(106, 'alex kassis', '71810228', NULL, NULL, NULL, '2025-01-10 11:46:17', '2025-01-10 11:46:17'),
(107, 'Mandy Bassil ( Wakim Herro )', '03460268', NULL, NULL, NULL, '2025-01-10 11:47:29', '2025-01-10 11:47:29'),
(108, 'Elie Mdawar (SGA)', '71387738', NULL, NULL, NULL, '2025-01-10 11:49:29', '2025-01-10 11:49:29'),
(109, 'Elias Zgheib', '03824145', NULL, NULL, NULL, '2025-01-10 11:50:18', '2025-01-10 11:50:18'),
(110, 'Remond Metri', '03526888', NULL, NULL, NULL, '2025-01-10 11:54:12', '2025-01-10 11:54:12'),
(111, 'Nicole Zeidan', '03358653', NULL, NULL, NULL, '2025-01-10 11:54:49', '2025-01-10 11:54:49'),
(112, 'Georgio Daou', '70990033', NULL, NULL, NULL, '2025-01-10 11:56:15', '2025-01-10 11:56:15'),
(113, 'Joe Akiki', '71747999', NULL, NULL, NULL, '2025-01-10 11:56:58', '2025-01-10 11:56:58'),
(114, 'Jimmy mehanna', '03858658', NULL, NULL, NULL, '2025-01-10 15:07:11', '2025-01-10 15:07:11'),
(115, 'Charles Karazi', '76966337', NULL, NULL, NULL, '2025-01-10 15:31:24', '2025-01-10 15:31:24'),
(116, 'Stephanie Khadij', '03696562', NULL, NULL, NULL, '2025-01-10 15:41:54', '2025-01-10 15:41:54'),
(117, 'Marwan Chaker', '0371593', NULL, NULL, NULL, '2025-01-10 15:43:06', '2025-01-10 15:43:06'),
(118, 'Yvonne makhlouf', '03751918', NULL, NULL, NULL, '2025-01-10 15:44:33', '2025-01-10 15:44:33'),
(119, 'Jino chehade', '03186633', NULL, NULL, NULL, '2025-01-10 16:01:29', '2025-01-10 16:01:29'),
(120, 'Nayla Najjar', '03621501', NULL, NULL, NULL, '2025-01-10 16:04:09', '2025-01-10 16:04:09'),
(121, 'Salwa Chalhoub', '03441054', NULL, NULL, NULL, '2025-01-10 16:09:15', '2025-01-10 16:09:15'),
(122, 'Karim Eid', '03628635', NULL, NULL, NULL, '2025-01-10 16:19:21', '2025-01-10 16:19:21'),
(123, 'Andrea Jallad', '03519669', NULL, NULL, NULL, '2025-01-10 16:20:59', '2025-01-10 16:20:59'),
(124, 'Georgio bou khalil', '70016868', NULL, NULL, NULL, '2025-01-10 16:24:17', '2025-01-10 16:24:17'),
(125, 'Krikor Khatchikian', '+97455763965', NULL, NULL, NULL, '2025-01-10 16:25:38', '2025-01-10 16:25:38'),
(126, 'Marc Mouawad', '03826372', NULL, NULL, NULL, '2025-01-10 16:26:39', '2025-01-10 16:26:39'),
(127, 'Rafa chabtini', '03392029', NULL, NULL, NULL, '2025-01-10 16:28:16', '2025-01-10 16:28:16'),
(128, 'Atef zgheib', '03775778', NULL, NULL, NULL, '2025-01-10 16:28:47', '2025-01-10 16:28:47'),
(129, 'Nathalie Romanos', '03740221', NULL, NULL, NULL, '2025-01-10 16:29:45', '2025-01-10 16:29:45'),
(130, 'Michel haddad', '03600166', NULL, NULL, NULL, '2025-01-10 16:30:34', '2025-01-10 16:30:34'),
(131, 'Marc hado', '03964406', NULL, NULL, NULL, '2025-01-10 16:32:59', '2025-01-10 16:32:59'),
(132, 'Christopher Charles', '71848126', NULL, NULL, NULL, '2025-01-11 05:11:21', '2025-01-11 05:11:21'),
(133, 'Joseph atallah', '03849974', NULL, NULL, NULL, '2025-01-11 05:14:01', '2025-01-11 05:14:01'),
(134, 'Vatche Saegherian', '70838686', NULL, NULL, NULL, '2025-01-11 05:42:41', '2025-01-11 05:42:41'),
(135, 'Emile abou samra', '03800768', NULL, NULL, NULL, '2025-01-11 05:43:35', '2025-01-11 05:43:35'),
(136, 'Jad sarkis', '76446311', NULL, NULL, NULL, '2025-01-11 06:06:04', '2025-01-11 06:06:04'),
(137, 'Alaa Chazbeck', '03212230', NULL, NULL, NULL, '2025-01-11 06:18:07', '2025-01-11 06:18:07'),
(138, 'Walid Akkawi', '03602602', NULL, NULL, NULL, '2025-01-11 06:24:46', '2025-01-11 06:24:46'),
(139, 'Imane Abdallah', '03701020', NULL, NULL, NULL, '2025-01-11 06:30:32', '2025-01-11 06:30:32'),
(140, 'Martin Mounsef', '76518409', NULL, NULL, NULL, '2025-01-11 06:38:33', '2025-01-11 06:38:33'),
(141, 'Maya zeineldine', '03572654', NULL, NULL, NULL, '2025-01-11 06:43:05', '2025-01-11 06:43:05'),
(142, 'ziad chahwan', '76179274', NULL, NULL, NULL, '2025-01-11 07:13:39', '2025-01-11 07:13:39'),
(143, 'Tina semaan', '03712581', NULL, NULL, NULL, '2025-01-11 07:14:31', '2025-01-11 07:14:31'),
(144, 'fouad aramoune', '03944644', NULL, NULL, NULL, '2025-01-11 09:22:06', '2025-01-11 09:22:06'),
(145, 'Leonel khalil', '03171899', NULL, NULL, NULL, '2025-01-11 13:23:36', '2025-01-11 13:23:36'),
(146, 'Zouzou sayah', '70521812', NULL, NULL, NULL, '2025-01-11 13:44:55', '2025-01-11 13:44:55'),
(147, 'Elie hamouche', '03194941', NULL, NULL, NULL, '2025-01-11 14:45:19', '2025-01-11 14:45:19'),
(148, 'Joe wehbe', '76449882', NULL, NULL, NULL, '2025-01-11 15:21:27', '2025-01-11 15:21:27'),
(149, 'Gilbert takla', '03055075', NULL, NULL, NULL, '2025-01-12 05:37:07', '2025-01-12 05:37:07'),
(150, 'Mrad mhawej', '03066097', NULL, NULL, NULL, '2025-01-12 05:38:02', '2025-01-12 05:38:02'),
(151, 'Nayri sarafian', '70102990', NULL, NULL, NULL, '2025-01-12 05:39:21', '2025-01-12 05:39:21'),
(152, 'Eddy boustany', '03804805', NULL, NULL, NULL, '2025-01-12 05:44:27', '2025-01-12 05:44:27'),
(153, 'Nareg panosian', '78915653', NULL, NULL, NULL, '2025-01-12 05:48:50', '2025-01-12 05:48:50'),
(154, 'Dima abdallah', '71010070', NULL, NULL, NULL, '2025-01-12 05:51:21', '2025-01-12 05:51:21'),
(155, 'Ghassan allam', '03707470', NULL, NULL, NULL, '2025-01-12 05:58:05', '2025-01-12 05:58:05'),
(156, 'Joya noppe', '70313920', NULL, NULL, NULL, '2025-01-12 06:01:48', '2025-01-12 06:01:48'),
(157, 'Ghassan jamal', '70720611', NULL, NULL, NULL, '2025-01-12 06:03:04', '2025-01-12 06:03:04'),
(158, 'Dora nakhoul', '71522280', NULL, NULL, NULL, '2025-01-12 06:03:56', '2025-01-12 06:03:56'),
(159, 'Michelle aoun', '71851309', NULL, NULL, NULL, '2025-01-12 06:14:59', '2025-01-12 06:14:59'),
(160, 'Chris rahbani', '71358000', NULL, NULL, NULL, '2025-01-12 06:38:39', '2025-01-12 06:38:39'),
(161, 'Abboud el khatib', '71715046', NULL, NULL, NULL, '2025-01-12 07:39:56', '2025-01-12 07:39:56'),
(162, 'Nadira bassil', '76505546', NULL, NULL, NULL, '2025-01-12 08:00:14', '2025-01-12 08:00:14'),
(163, 'Josiane farah', '70401084', NULL, NULL, NULL, '2025-01-12 08:42:32', '2025-01-12 08:42:32'),
(164, 'Pio chihane', '71445463', NULL, NULL, NULL, '2025-01-12 09:49:23', '2025-01-12 09:49:23'),
(165, 'Jessica menassa', '70852962', NULL, NULL, NULL, '2025-01-12 14:56:58', '2025-01-12 14:56:58'),
(166, 'Miguel khanjan', '79160501', NULL, NULL, NULL, '2025-01-13 05:27:38', '2025-01-13 05:27:38'),
(167, 'Alex chbib', '78952083', NULL, NULL, NULL, '2025-01-13 05:29:49', '2025-01-13 05:29:49'),
(168, 'Paul awad', '71741774', NULL, NULL, NULL, '2025-01-13 05:36:37', '2025-01-13 05:36:37'),
(169, 'Roger sejaan', '76818288', NULL, NULL, NULL, '2025-01-13 06:05:52', '2025-01-13 06:05:52'),
(170, 'Gabriel chalhoub', '03431637', NULL, NULL, NULL, '2025-01-13 06:32:35', '2025-01-13 06:32:35'),
(171, 'Joe raphael', '70255841', NULL, NULL, NULL, '2025-01-13 06:45:24', '2025-01-13 06:45:24'),
(172, 'faroue hosary', '03681785', NULL, NULL, NULL, '2025-01-13 09:04:00', '2025-01-13 09:04:00'),
(173, 'henry hankach', '70718124', NULL, NULL, NULL, '2025-01-14 05:18:03', '2025-01-14 05:18:03'),
(174, 'sam morgin', '+18602147042', NULL, NULL, NULL, '2025-01-14 05:23:48', '2025-01-14 05:23:48'),
(175, 'joy aoun', '70134681', NULL, NULL, NULL, '2025-01-14 05:27:30', '2025-01-14 05:27:30'),
(176, 'joseph estephan', '70660900', NULL, NULL, NULL, '2025-01-14 05:33:29', '2025-01-14 05:33:29'),
(177, 'lucas  asaad', '70081568', NULL, NULL, NULL, '2025-01-14 05:41:19', '2025-01-14 05:41:19'),
(178, 'marc helo', '71150694', NULL, NULL, NULL, '2025-01-14 05:42:46', '2025-01-14 05:42:46'),
(179, 'redrick bejenni', '81364640', NULL, NULL, NULL, '2025-01-14 05:44:02', '2025-01-14 05:44:02'),
(180, 'rayen helo', '71858838', NULL, NULL, NULL, '2025-01-14 05:46:50', '2025-01-14 05:46:50'),
(181, 'ragheb darwich', '7032249', NULL, NULL, NULL, '2025-01-14 06:49:39', '2025-01-14 06:49:39'),
(182, 'nael hammoud', '78812103', NULL, NULL, NULL, '2025-01-14 06:59:39', '2025-01-14 06:59:39'),
(183, 'bahae hamed', '78892811', NULL, NULL, NULL, '2025-01-14 07:01:13', '2025-01-14 07:01:13'),
(184, 'fatima basma', '71268066', NULL, NULL, NULL, '2025-01-14 09:29:25', '2025-01-14 09:29:25'),
(185, 'ghassan mkahal', '76833001', NULL, NULL, NULL, '2025-01-15 05:45:01', '2025-01-15 05:45:01'),
(186, 'julien alam', '76341226', NULL, NULL, NULL, '2025-01-15 06:02:13', '2025-01-15 06:02:13'),
(187, 'meera zgheib', '71928973', NULL, NULL, NULL, '2025-01-15 06:38:48', '2025-01-15 06:38:48'),
(188, 'karl bou chaaya', '76452714', NULL, NULL, NULL, '2025-01-15 06:43:46', '2025-01-15 06:43:46'),
(189, 'victore fadel', '03114636', NULL, NULL, NULL, '2025-01-15 06:56:17', '2025-01-15 06:56:17'),
(190, 'louis de royere', '81955809', NULL, NULL, NULL, '2025-01-15 07:35:21', '2025-01-15 07:35:21'),
(191, 'sebastien fustier', '79159006', NULL, NULL, NULL, '2025-01-15 07:47:51', '2025-01-15 07:47:51'),
(192, 'youssef mdawar', '71851701', NULL, NULL, NULL, '2025-01-15 08:29:13', '2025-01-15 08:29:13'),
(193, 'Elie Rizk', '71301100', NULL, NULL, NULL, '2025-01-15 08:40:26', '2025-01-15 08:40:26'),
(194, 'youssef khalil', '81500400', NULL, NULL, NULL, '2025-01-16 05:39:14', '2025-01-16 05:39:14'),
(195, 'raphael oibeid', '76172735', NULL, NULL, NULL, '2025-01-16 05:52:02', '2025-01-16 05:52:02'),
(196, 'ziad jrej', '78836786', NULL, NULL, NULL, '2025-01-16 06:34:37', '2025-01-16 06:34:37'),
(197, 'nancy fardissy', '70999931', NULL, NULL, NULL, '2025-01-16 10:07:07', '2025-01-16 10:07:07'),
(198, 'boudy sabagh', '70935267', NULL, NULL, NULL, '2025-01-18 05:14:31', '2025-01-18 05:14:31'),
(199, 'chris rahbani', '71398000', NULL, NULL, NULL, '2025-01-18 05:28:35', '2025-01-18 05:28:35'),
(200, 'ziad khoury', '03283188', NULL, NULL, NULL, '2025-01-18 05:30:37', '2025-01-18 05:30:37'),
(201, 'roula l hajj', '03982275', NULL, NULL, NULL, '2025-01-18 05:32:36', '2025-01-18 05:32:36'),
(202, 'jana nolke', '71565365', NULL, NULL, NULL, '2025-01-18 07:42:35', '2025-01-18 07:42:35'),
(203, 'Ali Youssef', '70874961', NULL, NULL, NULL, '2025-01-18 09:07:06', '2025-01-18 09:07:06'),
(204, 'talal adoura', '81243603', NULL, NULL, NULL, '2025-01-19 06:57:32', '2025-01-19 06:57:32'),
(205, 'toni kahhal', '70985236', NULL, NULL, NULL, '2025-01-19 07:35:25', '2025-01-19 07:35:25'),
(206, 'mazen arabi', '76808534', NULL, NULL, NULL, '2025-01-19 08:46:56', '2025-01-19 08:46:56'),
(207, 'mehde hattoum', '71223515', NULL, NULL, NULL, '2025-01-20 05:14:08', '2025-01-20 05:14:08'),
(208, 'wiliam mdawar', '76172020', NULL, NULL, NULL, '2025-01-20 05:15:59', '2025-01-20 05:15:59'),
(209, 'toni morkos', '03267664', NULL, NULL, NULL, '2025-01-20 06:48:03', '2025-01-20 06:48:03'),
(210, 'gui abi najem', '76534138', NULL, NULL, NULL, '2025-01-21 06:04:44', '2025-01-21 06:04:44'),
(211, 'Wissam Saliba', '70385450', NULL, NULL, NULL, '2025-01-21 06:35:17', '2025-01-21 06:35:17'),
(212, 'ibrahim hananiya', '03852774', NULL, NULL, NULL, '2025-01-21 07:53:11', '2025-01-21 07:53:11');

-- --------------------------------------------------------

--
-- Table structure for table `custom_items`
--

CREATE TABLE `custom_items` (
  `id` bigint UNSIGNED NOT NULL,
  `invoice_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity` int NOT NULL,
  `returned_quantity` int NOT NULL DEFAULT '0',
  `status` enum('draft','active','returned','overdue') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `custom_items`
--

INSERT INTO `custom_items` (`id`, `invoice_id`, `name`, `description`, `price`, `quantity`, `returned_quantity`, `status`, `created_at`, `updated_at`) VALUES
(1, 8, 'Ski set new', '', '325.00', 1, 0, 'active', '2025-01-09 14:25:31', '2025-01-09 14:25:31'),
(2, 14, '1 ski SL + 1 ski GS+ 1 boots used', '', '450.00', 1, 0, 'active', '2025-01-09 14:52:07', '2025-01-09 14:52:07'),
(3, 15, '1 ski SL used + 1 boots used', '', '250.00', 1, 0, 'active', '2025-01-09 14:53:39', '2025-01-09 14:53:39'),
(4, 16, 'Ski set Firebird RC new', '', '275.00', 1, 0, 'active', '2025-01-09 14:55:58', '2025-01-09 14:55:58'),
(5, 17, '1 boots kids', '', '100.00', 1, 0, 'active', '2025-01-09 14:58:08', '2025-01-09 14:58:08'),
(6, 17, '1 boots race', '', '200.00', 1, 0, 'active', '2025-01-09 14:58:08', '2025-01-09 14:58:08'),
(7, 18, 'Ski set Volkl SL', '', '250.00', 1, 0, 'active', '2025-01-09 14:59:05', '2025-01-09 14:59:05'),
(8, 19, 'Ski xcr blizzard used', '', '200.00', 1, 0, 'active', '2025-01-09 15:01:56', '2025-01-09 15:01:56'),
(9, 19, 'Boots new tecnica', '', '125.00', 1, 0, 'active', '2025-01-09 15:01:56', '2025-01-09 15:01:56'),
(10, 19, 'Poles', '', '0.00', 1, 0, 'active', '2025-01-09 15:01:56', '2025-01-09 15:01:56'),
(11, 20, '2 GS new + 2 SL new + 2 boots new', '', '2000.00', 1, 0, 'active', '2025-01-09 15:03:27', '2025-01-09 15:03:27'),
(12, 24, 'Ski set used', '', '250.00', 1, 0, 'active', '2025-01-09 15:10:23', '2025-01-09 15:10:23'),
(13, 25, '2 ski sets', '', '550.00', 1, 0, 'active', '2025-01-09 15:11:19', '2025-01-09 15:11:19'),
(14, 27, '2 skis (Blizzard)  & 1 boots & 1 poles', '', '600.00', 1, 0, 'active', '2025-01-09 15:15:02', '2025-01-09 15:15:02'),
(15, 28, '2 Sl & 3 SG & 1 kids', '', '900.00', 1, 0, 'active', '2025-01-09 15:20:05', '2025-01-09 15:20:05'),
(16, 28, '3 race boots & 1 kids boots', '', '1250.00', 1, 0, 'active', '2025-01-09 15:20:05', '2025-01-09 15:20:05'),
(17, 28, '2 poles', '', '200.00', 1, 0, 'active', '2025-01-09 15:20:05', '2025-01-09 15:20:05'),
(18, 29, 'Ski', '', '125.00', 2, 0, 'active', '2025-01-09 15:33:47', '2025-01-09 15:33:47'),
(19, 29, 'Boots', '', '200.00', 2, 0, 'active', '2025-01-09 15:33:47', '2025-01-09 15:33:47'),
(20, 31, 'shoes new', '', '200.00', 1, 0, 'active', '2025-01-09 15:42:41', '2025-01-09 15:42:41'),
(21, 33, 'Ski set RC junior', '', '200.00', 1, 0, 'active', '2025-01-09 15:45:02', '2025-01-09 15:45:02'),
(22, 36, 'Ski set (Volkl RTM 80)', '', '350.00', 1, 0, 'active', '2025-01-09 15:55:52', '2025-01-09 15:55:52'),
(23, 37, 'Ski set freestyle', '', '150.00', 1, 0, 'active', '2025-01-09 15:57:12', '2025-01-09 15:57:12'),
(24, 38, '2 sets new', '', '400.00', 1, 0, 'active', '2025-01-09 15:58:04', '2025-01-09 15:58:04'),
(25, 39, '2 ski sets', '', '350.00', 1, 0, 'active', '2025-01-09 15:59:49', '2025-01-09 15:59:49'),
(26, 40, 'ski sets', '', '116.70', 3, 0, 'active', '2025-01-09 16:02:05', '2025-01-09 16:02:05'),
(27, 41, 'ski sets', '', '216.67', 3, 0, 'active', '2025-01-09 16:04:45', '2025-01-09 16:04:45'),
(28, 42, '1 ski GS & 1 boots', '', '300.00', 1, 0, 'active', '2025-01-09 16:08:41', '2025-01-09 16:08:41'),
(29, 43, '1 race tecnica & 2 tecnica jr', '', '0.00', 3, 0, 'active', '2025-01-09 16:19:09', '2025-01-09 16:19:09'),
(30, 43, 'poles', '', '0.00', 2, 0, 'active', '2025-01-09 16:19:09', '2025-01-09 16:19:09'),
(31, 43, '1 ski GS 150 & 2 ski blizzard', '', '175.00', 3, 0, 'active', '2025-01-09 16:19:09', '2025-01-09 16:19:09'),
(32, 49, '2 Ski Set Junior + 1 Ski Set brahma 88', '', '1000.00', 1, 0, 'active', '2025-01-10 09:27:10', '2025-01-10 09:27:10'),
(33, 50, 'Ski SL + 1 boots tecnica', '', '250.00', 1, 0, 'active', '2025-01-10 09:28:47', '2025-01-10 09:28:47'),
(34, 51, '3 Ski Set Blizzard New + 1 Ski Set Volkl', '', '950.00', 1, 0, 'active', '2025-01-10 09:32:07', '2025-01-10 09:32:07'),
(35, 53, 'Ski Set Blizzard / Tecnica / Vola', '', '225.00', 1, 0, 'active', '2025-01-10 09:34:45', '2025-01-10 09:34:45'),
(36, 56, 'Ski Set blizzard New', '', '200.00', 1, 0, 'active', '2025-01-10 09:39:48', '2025-01-10 09:39:48'),
(37, 56, 'Ski Set SL Used', '', '250.00', 1, 0, 'active', '2025-01-10 09:39:48', '2025-01-10 09:39:48'),
(38, 57, 'Ski Set Volkl', '', '100.00', 1, 0, 'active', '2025-01-10 09:40:48', '2025-01-10 09:40:48'),
(39, 58, 'Ski Set Volkl + Dalbello', '', '200.00', 1, 0, 'active', '2025-01-10 09:43:10', '2025-01-10 09:43:10'),
(40, 58, 'Ski Set blizzard + Tecnica', '', '225.00', 2, 0, 'active', '2025-01-10 09:43:10', '2025-01-10 09:43:10'),
(41, 59, 'Ski boots  + Batton Fizan', '', '50.00', 1, 0, 'active', '2025-01-10 09:44:10', '2025-01-10 09:44:10'),
(42, 61, 'ski volkl + Batton', '', '0.00', 1, 0, 'active', '2025-01-10 09:48:32', '2025-01-10 09:48:32'),
(43, 63, '3 Ski Set Volkl', '', '400.00', 1, 0, 'active', '2025-01-10 09:52:04', '2025-01-10 09:52:04'),
(44, 68, 'Ski Set Blizzard Race (143)', '', '100.00', 1, 0, 'active', '2025-01-10 09:55:54', '2025-01-10 09:55:54'),
(45, 69, 'Ski Blizzard New  + Boots', '', '200.00', 1, 0, 'active', '2025-01-10 09:56:55', '2025-01-10 09:56:55'),
(46, 70, '2 ski Used + 1 Boots', '', '450.00', 1, 0, 'active', '2025-01-10 09:57:53', '2025-01-10 09:57:53'),
(47, 76, '5 Ski +4 boots + 5 Poles', '', '700.00', 1, 0, 'active', '2025-01-10 10:52:56', '2025-01-10 10:52:56'),
(48, 78, 'Ski Volkl + 1 Boots Tecnica', '', '100.00', 1, 0, 'active', '2025-01-10 10:55:06', '2025-01-10 10:55:06'),
(49, 80, 'Ski Set FreeStyle', '', '400.00', 1, 0, 'active', '2025-01-10 10:57:28', '2025-01-10 10:57:28'),
(50, 81, '5 Ski Set Volkl', '', '0.00', 1, 0, 'active', '2025-01-10 11:00:03', '2025-01-10 11:00:03'),
(51, 83, '1 Ski Geant New + 1 Ski SL + 1 Boots', '', '700.00', 1, 0, 'active', '2025-01-10 11:02:30', '2025-01-10 11:02:30'),
(52, 87, 'Ski Set Black Crows New Camox', '', '695.00', 1, 0, 'active', '2025-01-10 11:14:42', '2025-01-10 11:14:42'),
(53, 88, 'ski set racing', '', '250.00', 2, 0, 'active', '2025-01-10 11:17:16', '2025-01-10 11:17:16'),
(54, 88, 'ski set blizzard', '', '200.00', 1, 0, 'active', '2025-01-10 11:17:16', '2025-01-10 11:17:16'),
(55, 89, '2 ski sets', '', '0.00', 1, 0, 'active', '2025-01-10 11:19:16', '2025-01-10 11:19:16'),
(56, 90, 'ski set head', '', '200.00', 1, 0, 'active', '2025-01-10 11:20:33', '2025-01-10 11:20:33'),
(57, 92, '1 ski + 1 pole', '', '250.00', 1, 0, 'active', '2025-01-10 11:22:20', '2025-01-10 11:22:20'),
(58, 93, '1 ski blizzard xcr + 1 pole', '', '200.00', 1, 0, 'active', '2025-01-10 11:23:25', '2025-01-10 11:23:25'),
(59, 96, 'ski set rtm 80', '', '300.00', 1, 0, 'active', '2025-01-10 11:26:40', '2025-01-10 11:26:40'),
(60, 96, 'Ski Set Blizzard', '', '200.00', 1, 0, 'active', '2025-01-10 11:26:40', '2025-01-10 11:26:40'),
(61, 97, 'ski set rtm 80', '', '300.00', 1, 0, 'active', '2025-01-10 11:27:33', '2025-01-10 11:27:33'),
(62, 100, '3 ski gs + 1 ski sl new', '', '1465.00', 1, 0, 'active', '2025-01-10 11:30:15', '2025-01-10 11:30:15'),
(63, 100, '3 ski boots technica race new', '', '0.00', 1, 0, 'active', '2025-01-10 11:30:15', '2025-01-10 11:30:15'),
(64, 102, 'ski set blizzard', '', '300.00', 1, 0, 'active', '2025-01-10 11:33:43', '2025-01-10 11:33:43'),
(65, 102, 'ski set blizzard', '', '275.00', 1, 0, 'active', '2025-01-10 11:33:43', '2025-01-10 11:33:43'),
(66, 102, 'ski set volkl', '', '420.00', 1, 0, 'active', '2025-01-10 11:33:43', '2025-01-10 11:33:43'),
(67, 102, 'broken poles', '', '35.00', 1, 0, 'active', '2025-01-10 11:33:43', '2025-01-10 11:33:43'),
(68, 104, '1 ski + pole', '', '100.00', 1, 0, 'active', '2025-01-10 11:38:38', '2025-01-10 11:38:38'),
(69, 106, 'Ski Set blizzard New', '', '200.00', 1, 0, 'active', '2025-01-10 11:40:03', '2025-01-10 11:40:03'),
(70, 107, '2 ski boots', '', '315.00', 1, 0, 'active', '2025-01-10 11:41:03', '2025-01-10 11:41:03'),
(71, 114, '1 ski dynastar + 1 pole', '', '150.00', 1, 0, 'active', '2025-01-10 11:46:44', '2025-01-10 11:46:44'),
(72, 115, 'Snowboard Boots', '', '150.00', 1, 0, 'active', '2025-01-10 11:48:47', '2025-01-10 11:48:47'),
(73, 116, 'Ski Set Race', '', '150.00', 1, 0, 'active', '2025-01-10 11:49:48', '2025-01-10 11:49:48'),
(74, 117, 'Ski Set', '', '0.00', 1, 0, 'active', '2025-01-10 11:50:49', '2025-01-10 11:50:49'),
(75, 120, 'Ski boots Tecnica Race', '', '100.00', 1, 0, 'active', '2025-01-10 11:56:42', '2025-01-10 11:56:42'),
(76, 121, '1 Ski blizzard + 1 Poles', '', '200.00', 1, 0, 'active', '2025-01-10 11:57:30', '2025-01-10 11:57:30'),
(77, 122, 'ski sets (1blizzard used & 2 volkl used)', '', '0.00', 3, 0, 'active', '2025-01-10 15:07:57', '2025-01-10 15:07:57'),
(78, 123, '1 blizzard used & 2 volkl used', '', '0.00', 3, 0, 'active', '2025-01-10 15:30:27', '2025-01-10 15:30:27'),
(79, 123, '1 blizzard used & 2 volkl used', '', '0.00', 3, 0, 'active', '2025-01-10 15:30:27', '2025-01-10 15:30:27'),
(80, 123, '3 poles', '', '0.00', 3, 0, 'active', '2025-01-10 15:30:27', '2025-01-10 15:30:27'),
(81, 127, 'Boots SL used & 1 new', '', '0.00', 2, 0, 'active', '2025-01-10 15:48:09', '2025-01-10 15:48:09'),
(82, 127, 'ski GS new', '', '395.00', 2, 0, 'active', '2025-01-10 15:48:09', '2025-01-10 15:48:09'),
(83, 129, 'Skis race', '', '90.00', 5, 0, 'active', '2025-01-10 16:08:02', '2025-01-10 16:08:02'),
(84, 129, 'Ski boots', '', '112.50', 4, 0, 'active', '2025-01-10 16:08:02', '2025-01-10 16:08:02'),
(85, 129, 'Poles', '', '150.00', 1, 0, 'active', '2025-01-10 16:08:02', '2025-01-10 16:08:02'),
(86, 130, 'Ski + Boots + poles', '', '485.00', 1, 0, 'active', '2025-01-10 16:18:37', '2025-01-10 16:18:37'),
(87, 131, 'Ski + boots', '', '0.00', 1, 0, 'active', '2025-01-10 16:20:38', '2025-01-10 16:20:38'),
(88, 132, '2 Skis GS/ SL used', '', '250.00', 2, 0, 'active', '2025-01-10 16:21:41', '2025-01-10 16:21:41'),
(89, 132, 'Ski boots', '', '160.00', 1, 0, 'active', '2025-01-10 16:21:41', '2025-01-10 16:21:41'),
(90, 133, 'Ski + Boots new tecnica', '', '125.00', 1, 0, 'active', '2025-01-10 16:24:57', '2025-01-10 16:24:57'),
(91, 135, '3 ski + 3 new boots + 2 poles', '', '450.00', 1, 0, 'active', '2025-01-10 16:27:57', '2025-01-10 16:27:57'),
(92, 137, 'Ski + Boots + poles', '', '0.00', 1, 0, 'active', '2025-01-10 16:29:04', '2025-01-10 16:29:04'),
(93, 138, '5 ski new & 3 boots', '', '1540.00', 1, 0, 'active', '2025-01-10 16:30:07', '2025-01-10 16:30:07'),
(94, 139, '2 GS/ 2 SL (3 new & 1 used) & 1 boots tecnica', '', '1585.00', 1, 0, 'active', '2025-01-10 16:32:35', '2025-01-10 16:32:35'),
(95, 140, 'Ski boots & poles', '', '50.00', 1, 0, 'active', '2025-01-10 16:33:20', '2025-01-10 16:33:20'),
(96, 148, 'Ski used SL 149 + Boots flex 90 new', '', '350.00', 1, 1, 'returned', '2025-01-11 06:25:54', '2025-01-11 11:47:30'),
(97, 155, '1 Ski used SL 149 + New boots flex 90', '', '350.00', 1, 0, 'active', '2025-01-11 11:51:47', '2025-01-11 11:51:47'),
(98, 155, '1 Blizzard 130 race', '', '250.00', 1, 0, 'active', '2025-01-11 11:51:47', '2025-01-11 11:51:47'),
(99, 155, '1 blizzard 110', '', '200.00', 1, 0, 'active', '2025-01-11 11:51:47', '2025-01-11 11:51:47'),
(100, 155, '2 boots tecnica & 2 poles (vola & dynastar)', '', '0.00', 1, 0, 'active', '2025-01-11 11:51:47', '2025-01-11 11:51:47'),
(101, 162, 'Ski set', '', '180.00', 1, 0, 'active', '2025-01-12 05:37:36', '2025-01-12 05:37:36'),
(102, 175, 'R110 tecnica', '', '200.00', 1, 0, 'active', '2025-01-12 06:42:46', '2025-01-12 06:42:46'),
(103, 212, '1 ski GS & 1 ski SL', '', '0.00', 1, 0, 'active', '2025-01-16 10:11:18', '2025-01-16 10:11:18'),
(104, 213, '1 ski GS new', '', '550.00', 1, 0, 'active', '2025-01-16 14:26:43', '2025-01-16 14:26:43');

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
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `id` bigint UNSIGNED NOT NULL,
  `customer_id` bigint UNSIGNED NOT NULL,
  `category_id` bigint UNSIGNED DEFAULT NULL,
  `total_vat` decimal(5,2) NOT NULL DEFAULT '0.00',
  `total_discount` decimal(5,2) NOT NULL DEFAULT '0.00',
  `deposit` decimal(10,2) NOT NULL DEFAULT '0.00',
  `total_amount` decimal(10,2) NOT NULL,
  `paid_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `payment_method` enum('cash','credit_card') COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('draft','active','returned','overdue') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `rental_start_date` datetime DEFAULT NULL,
  `rental_end_date` datetime DEFAULT NULL,
  `days` int DEFAULT NULL,
  `note` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `invoices`
--

INSERT INTO `invoices` (`id`, `customer_id`, `category_id`, `total_vat`, `total_discount`, `deposit`, `total_amount`, `paid_amount`, `payment_method`, `status`, `rental_start_date`, `rental_end_date`, `days`, `note`, `user_id`, `created_at`, `updated_at`) VALUES
(8, 3, 2, '0.00', '0.00', '325.00', '625.00', '300.00', 'credit_card', 'active', NULL, NULL, NULL, '+2 boots free', 3, '2025-01-09 14:25:31', '2025-01-09 14:26:34'),
(10, 4, 2, '0.00', '0.00', '0.00', '400.00', '400.00', 'cash', 'active', NULL, NULL, NULL, '1 poles not 2', 3, '2025-01-09 14:45:01', '2025-01-09 14:45:01'),
(11, 5, 2, '0.00', '0.00', '0.00', '100.00', '100.00', 'credit_card', 'active', NULL, NULL, NULL, NULL, 3, '2025-01-09 14:48:08', '2025-01-09 14:48:08'),
(12, 6, 2, '0.00', '0.00', '0.00', '150.00', '150.00', 'cash', 'active', NULL, NULL, NULL, NULL, 3, '2025-01-09 14:49:52', '2025-01-09 14:49:52'),
(13, 6, 2, '0.00', '0.00', '0.00', '150.00', '150.00', 'cash', 'active', NULL, NULL, NULL, NULL, 3, '2025-01-09 14:49:55', '2025-01-09 14:49:55'),
(14, 7, 2, '0.00', '0.00', '0.00', '450.00', '450.00', 'credit_card', 'active', NULL, NULL, NULL, NULL, 3, '2025-01-09 14:52:07', '2025-01-09 14:52:07'),
(15, 8, 2, '0.00', '0.00', '0.00', '250.00', '250.00', 'cash', 'active', NULL, NULL, NULL, NULL, 3, '2025-01-09 14:53:39', '2025-01-09 14:53:39'),
(16, 9, 2, '0.00', '0.00', '0.00', '275.00', '275.00', 'credit_card', 'active', NULL, NULL, NULL, NULL, 3, '2025-01-09 14:55:58', '2025-01-09 14:55:58'),
(17, 10, 2, '0.00', '0.00', '0.00', '300.00', '300.00', 'cash', 'active', NULL, NULL, NULL, NULL, 3, '2025-01-09 14:58:08', '2025-01-09 16:27:55'),
(18, 11, 2, '0.00', '0.00', '0.00', '250.00', '250.00', 'cash', 'active', NULL, NULL, NULL, NULL, 3, '2025-01-09 14:59:05', '2025-01-09 14:59:05'),
(19, 12, 2, '0.00', '0.00', '0.00', '325.00', '325.00', 'cash', 'active', NULL, NULL, NULL, NULL, 3, '2025-01-09 15:01:56', '2025-01-09 16:27:32'),
(20, 13, 2, '0.00', '0.00', '0.00', '2000.00', '2000.00', 'credit_card', 'active', NULL, NULL, NULL, NULL, 3, '2025-01-09 15:03:27', '2025-01-09 15:03:27'),
(21, 14, 2, '0.00', '0.00', '0.00', '100.00', '100.00', 'cash', 'active', NULL, NULL, NULL, NULL, 3, '2025-01-09 15:04:00', '2025-01-09 15:04:00'),
(22, 15, 2, '0.00', '0.00', '0.00', '400.00', '400.00', 'cash', 'active', NULL, NULL, NULL, NULL, 3, '2025-01-09 15:05:22', '2025-01-09 15:05:22'),
(23, 16, 2, '0.00', '0.00', '0.00', '500.00', '500.00', 'cash', 'active', NULL, NULL, NULL, NULL, 3, '2025-01-09 15:08:18', '2025-01-09 15:08:18'),
(24, 17, 2, '0.00', '0.00', '0.00', '250.00', '250.00', 'credit_card', 'active', NULL, NULL, NULL, NULL, 3, '2025-01-09 15:10:23', '2025-01-09 15:10:23'),
(25, 18, 2, '0.00', '0.00', '0.00', '550.00', '550.00', 'credit_card', 'active', NULL, NULL, NULL, NULL, 3, '2025-01-09 15:11:19', '2025-01-09 15:11:19'),
(26, 19, 2, '0.00', '0.00', '0.00', '200.00', '200.00', 'credit_card', 'active', NULL, NULL, NULL, NULL, 3, '2025-01-09 15:12:14', '2025-01-09 15:12:14'),
(27, 20, 2, '0.00', '0.00', '0.00', '600.00', '600.00', 'cash', 'active', NULL, NULL, NULL, NULL, 3, '2025-01-09 15:15:02', '2025-01-09 15:15:02'),
(28, 21, 2, '0.00', '0.00', '0.00', '2350.00', '2350.00', 'cash', 'active', NULL, NULL, NULL, NULL, 3, '2025-01-09 15:20:05', '2025-01-09 15:20:05'),
(29, 22, 2, '0.00', '0.00', '0.00', '650.00', '0.00', 'cash', 'active', NULL, NULL, NULL, NULL, 3, '2025-01-09 15:33:47', '2025-01-09 15:33:47'),
(30, 23, 2, '0.00', '0.00', '0.00', '300.00', '0.00', 'cash', 'active', NULL, NULL, NULL, NULL, 3, '2025-01-09 15:40:53', '2025-01-09 15:40:53'),
(31, 24, 2, '0.00', '0.00', '0.00', '600.00', '600.00', 'cash', 'active', NULL, NULL, NULL, NULL, 3, '2025-01-09 15:42:41', '2025-01-09 15:42:41'),
(32, 25, 2, '0.00', '0.00', '0.00', '400.00', '400.00', 'cash', 'active', NULL, NULL, NULL, NULL, 3, '2025-01-09 15:43:34', '2025-01-09 15:43:34'),
(33, 26, 2, '0.00', '0.00', '0.00', '200.00', '200.00', 'cash', 'active', NULL, NULL, NULL, '+ 1 SL 156 for free', 3, '2025-01-09 15:45:02', '2025-01-09 15:45:02'),
(34, 27, 2, '0.00', '0.00', '0.00', '200.00', '200.00', 'cash', 'active', NULL, NULL, NULL, NULL, 3, '2025-01-09 15:45:42', '2025-01-09 15:45:42'),
(35, 28, 2, '0.00', '0.00', '0.00', '250.00', '250.00', 'cash', 'active', NULL, NULL, NULL, NULL, 3, '2025-01-09 15:46:29', '2025-01-09 15:46:29'),
(36, 29, 2, '0.00', '0.00', '0.00', '350.00', '350.00', 'cash', 'active', NULL, NULL, NULL, NULL, 3, '2025-01-09 15:55:52', '2025-01-09 15:55:52'),
(37, 30, 2, '0.00', '0.00', '0.00', '150.00', '150.00', 'cash', 'active', NULL, NULL, NULL, NULL, 3, '2025-01-09 15:57:12', '2025-01-09 15:57:12'),
(38, 31, 2, '0.00', '0.00', '0.00', '400.00', '400.00', 'cash', 'active', NULL, NULL, NULL, NULL, 3, '2025-01-09 15:58:04', '2025-01-09 15:58:04'),
(39, 32, 2, '0.00', '0.00', '0.00', '350.00', '0.00', 'cash', 'active', NULL, NULL, NULL, NULL, 3, '2025-01-09 15:59:49', '2025-01-09 15:59:49'),
(40, 33, 2, '0.00', '0.00', '0.00', '350.10', '350.10', 'cash', 'active', NULL, NULL, NULL, NULL, 3, '2025-01-09 16:02:05', '2025-01-09 16:02:05'),
(41, 34, 2, '0.00', '0.00', '0.00', '650.01', '650.01', 'cash', 'active', NULL, NULL, NULL, NULL, 3, '2025-01-09 16:04:45', '2025-01-09 16:04:45'),
(42, 35, 2, '0.00', '0.00', '0.00', '300.00', '300.00', 'cash', 'active', NULL, NULL, NULL, NULL, 3, '2025-01-09 16:08:41', '2025-01-09 16:08:41'),
(43, 36, 2, '0.00', '0.00', '0.00', '525.00', '525.00', 'cash', 'active', NULL, NULL, NULL, NULL, 3, '2025-01-09 16:19:09', '2025-01-09 16:19:09'),
(44, 37, 2, '0.00', '0.00', '0.00', '300.00', '0.00', 'cash', 'active', NULL, NULL, NULL, NULL, 7, '2025-01-10 08:41:57', '2025-01-10 08:41:57'),
(45, 38, 2, '0.00', '0.00', '0.00', '150.00', '150.00', 'cash', 'active', NULL, NULL, NULL, NULL, 7, '2025-01-10 08:42:49', '2025-01-10 08:42:49'),
(46, 39, 2, '0.00', '0.00', '0.00', '150.00', '150.00', 'cash', 'active', NULL, NULL, NULL, NULL, 7, '2025-01-10 08:45:49', '2025-01-10 08:45:49'),
(47, 40, 2, '0.00', '0.00', '0.00', '400.00', '400.00', 'credit_card', 'active', NULL, NULL, NULL, NULL, 7, '2025-01-10 08:48:34', '2025-01-10 08:48:34'),
(49, 41, 2, '0.00', '0.00', '0.00', '1000.00', '1000.00', 'cash', 'active', NULL, NULL, NULL, NULL, 7, '2025-01-10 09:27:10', '2025-01-10 09:27:10'),
(50, 42, 2, '0.00', '0.00', '0.00', '250.00', '250.00', 'cash', 'active', NULL, NULL, NULL, NULL, 7, '2025-01-10 09:28:47', '2025-01-10 09:28:47'),
(51, 43, 2, '0.00', '0.00', '0.00', '950.00', '950.00', 'credit_card', 'active', NULL, NULL, NULL, NULL, 7, '2025-01-10 09:32:07', '2025-01-10 09:32:07'),
(52, 44, 2, '0.00', '0.00', '0.00', '150.00', '150.00', 'cash', 'active', NULL, NULL, NULL, NULL, 7, '2025-01-10 09:33:42', '2025-01-10 09:33:42'),
(53, 45, 2, '0.00', '0.00', '0.00', '225.00', '225.00', 'cash', 'active', NULL, NULL, NULL, NULL, 7, '2025-01-10 09:34:45', '2025-01-10 09:34:45'),
(54, 46, 2, '0.00', '0.00', '0.00', '700.00', '700.00', 'cash', 'active', NULL, NULL, NULL, NULL, 7, '2025-01-10 09:36:38', '2025-01-10 09:36:38'),
(55, 47, 2, '0.00', '0.00', '0.00', '200.00', '200.00', 'cash', 'active', NULL, NULL, NULL, NULL, 7, '2025-01-10 09:37:23', '2025-01-10 09:37:23'),
(56, 48, 2, '0.00', '0.00', '0.00', '450.00', '450.00', 'cash', 'active', NULL, NULL, NULL, NULL, 7, '2025-01-10 09:39:48', '2025-01-10 09:39:48'),
(57, 49, 2, '0.00', '0.00', '0.00', '100.00', '100.00', 'cash', 'active', NULL, NULL, NULL, NULL, 7, '2025-01-10 09:40:48', '2025-01-10 09:40:48'),
(58, 50, 2, '0.00', '0.00', '0.00', '650.00', '650.00', 'credit_card', 'active', NULL, NULL, NULL, NULL, 7, '2025-01-10 09:43:10', '2025-01-10 09:43:10'),
(59, 51, 2, '0.00', '0.00', '0.00', '50.00', '50.00', 'cash', 'active', NULL, NULL, NULL, NULL, 7, '2025-01-10 09:44:10', '2025-01-10 09:44:10'),
(60, 52, 2, '0.00', '0.00', '0.00', '150.00', '150.00', 'cash', 'active', NULL, NULL, NULL, NULL, 7, '2025-01-10 09:47:03', '2025-01-10 16:00:31'),
(61, 53, 2, '0.00', '0.00', '0.00', '0.00', '0.00', 'cash', 'active', NULL, NULL, NULL, 'For Free', 7, '2025-01-10 09:48:32', '2025-01-10 09:48:32'),
(62, 54, 2, '0.00', '0.00', '0.00', '250.00', '250.00', 'cash', 'active', NULL, NULL, NULL, NULL, 7, '2025-01-10 09:50:32', '2025-01-10 09:50:32'),
(63, 55, 2, '0.00', '0.00', '0.00', '400.00', '400.00', 'credit_card', 'active', NULL, NULL, NULL, NULL, 7, '2025-01-10 09:52:04', '2025-01-10 09:52:04'),
(64, 56, 2, '0.00', '0.00', '0.00', '250.00', '250.00', 'cash', 'active', NULL, NULL, NULL, NULL, 7, '2025-01-10 09:52:51', '2025-01-10 09:52:51'),
(65, 57, 2, '0.00', '0.00', '0.00', '250.00', '250.00', 'credit_card', 'active', NULL, NULL, NULL, NULL, 7, '2025-01-10 09:53:32', '2025-01-10 09:53:32'),
(66, 58, 2, '0.00', '0.00', '0.00', '450.00', '450.00', 'cash', 'active', NULL, NULL, NULL, NULL, 7, '2025-01-10 09:54:16', '2025-01-10 09:54:16'),
(67, 59, 2, '0.00', '0.00', '0.00', '100.00', '100.00', 'cash', 'active', NULL, NULL, NULL, NULL, 7, '2025-01-10 09:55:04', '2025-01-10 09:55:04'),
(68, 60, 2, '0.00', '0.00', '0.00', '100.00', '100.00', 'cash', 'active', NULL, NULL, NULL, NULL, 7, '2025-01-10 09:55:54', '2025-01-10 09:55:54'),
(69, 61, 2, '0.00', '0.00', '0.00', '200.00', '200.00', 'cash', 'active', NULL, NULL, NULL, NULL, 7, '2025-01-10 09:56:55', '2025-01-10 09:56:55'),
(70, 62, 2, '0.00', '0.00', '0.00', '450.00', '450.00', 'credit_card', 'active', NULL, NULL, NULL, NULL, 7, '2025-01-10 09:57:53', '2025-01-10 09:57:53'),
(71, 63, 2, '0.00', '0.00', '0.00', '300.00', '300.00', 'cash', 'active', NULL, NULL, NULL, NULL, 7, '2025-01-10 09:58:50', '2025-01-10 09:58:50'),
(72, 64, 2, '0.00', '0.00', '0.00', '150.00', '150.00', 'cash', 'active', NULL, NULL, NULL, NULL, 7, '2025-01-10 09:59:40', '2025-01-10 09:59:40'),
(73, 65, 2, '0.00', '0.00', '0.00', '600.00', '600.00', 'credit_card', 'active', NULL, NULL, NULL, NULL, 7, '2025-01-10 10:48:01', '2025-01-10 10:48:01'),
(74, 66, 2, '0.00', '0.00', '0.00', '300.00', '300.00', 'credit_card', 'active', NULL, NULL, NULL, NULL, 7, '2025-01-10 10:48:37', '2025-01-10 10:48:37'),
(75, 67, 2, '0.00', '0.00', '0.00', '150.00', '150.00', 'cash', 'active', NULL, NULL, NULL, NULL, 7, '2025-01-10 10:49:23', '2025-01-10 10:49:23'),
(76, 68, 2, '0.00', '0.00', '0.00', '700.00', '700.00', 'cash', 'active', NULL, NULL, NULL, NULL, 7, '2025-01-10 10:52:56', '2025-01-10 10:52:56'),
(77, 69, 2, '0.00', '0.00', '0.00', '350.00', '350.00', 'cash', 'active', NULL, NULL, NULL, NULL, 7, '2025-01-10 10:53:53', '2025-01-10 10:53:53'),
(78, 70, 2, '0.00', '0.00', '0.00', '100.00', '100.00', 'cash', 'active', NULL, NULL, NULL, NULL, 7, '2025-01-10 10:55:06', '2025-01-10 10:55:06'),
(79, 71, 2, '0.00', '0.00', '0.00', '450.00', '450.00', 'cash', 'active', NULL, NULL, NULL, NULL, 7, '2025-01-10 10:56:10', '2025-01-10 10:56:10'),
(80, 72, 2, '0.00', '0.00', '0.00', '400.00', '400.00', 'cash', 'active', NULL, NULL, NULL, NULL, 7, '2025-01-10 10:57:28', '2025-01-10 10:57:28'),
(81, 73, 2, '0.00', '0.00', '0.00', '0.00', '0.00', 'cash', 'active', NULL, NULL, NULL, 'For Free', 7, '2025-01-10 11:00:03', '2025-01-10 11:00:03'),
(82, 74, 2, '0.00', '0.00', '0.00', '300.00', '300.00', 'cash', 'active', NULL, NULL, NULL, NULL, 7, '2025-01-10 11:01:22', '2025-01-10 11:01:22'),
(83, 75, 2, '0.00', '0.00', '0.00', '700.00', '700.00', 'cash', 'active', NULL, NULL, NULL, NULL, 7, '2025-01-10 11:02:30', '2025-01-10 11:02:30'),
(84, 76, 2, '0.00', '0.00', '0.00', '150.00', '150.00', 'cash', 'active', NULL, NULL, NULL, NULL, 7, '2025-01-10 11:03:29', '2025-01-10 11:03:29'),
(85, 77, 2, '0.00', '0.00', '0.00', '200.00', '200.00', 'credit_card', 'active', NULL, NULL, NULL, NULL, 7, '2025-01-10 11:12:02', '2025-01-10 11:12:02'),
(86, 78, 2, '0.00', '0.00', '0.00', '250.00', '250.00', 'cash', 'active', NULL, NULL, NULL, NULL, 7, '2025-01-10 11:12:46', '2025-01-10 11:12:46'),
(87, 79, 2, '0.00', '0.00', '0.00', '695.00', '695.00', 'cash', 'active', NULL, NULL, NULL, NULL, 7, '2025-01-10 11:14:42', '2025-01-10 11:14:42'),
(88, 80, 2, '0.00', '0.00', '0.00', '700.00', '0.00', 'cash', 'active', NULL, NULL, NULL, NULL, 7, '2025-01-10 11:17:16', '2025-01-10 11:17:16'),
(89, 81, 2, '0.00', '0.00', '0.00', '0.00', '0.00', 'cash', 'active', NULL, NULL, NULL, 'free', 7, '2025-01-10 11:19:16', '2025-01-10 11:19:16'),
(90, 82, 2, '0.00', '0.00', '0.00', '200.00', '200.00', 'cash', 'active', NULL, NULL, NULL, NULL, 7, '2025-01-10 11:20:33', '2025-01-10 11:20:33'),
(91, 83, 2, '0.00', '0.00', '0.00', '250.00', '250.00', 'credit_card', 'active', NULL, NULL, NULL, NULL, 7, '2025-01-10 11:21:21', '2025-01-10 11:21:21'),
(92, 84, 2, '0.00', '0.00', '0.00', '250.00', '250.00', 'cash', 'active', NULL, NULL, NULL, NULL, 7, '2025-01-10 11:22:20', '2025-01-10 11:22:20'),
(93, 85, 2, '0.00', '0.00', '0.00', '200.00', '200.00', 'cash', 'active', NULL, NULL, NULL, NULL, 7, '2025-01-10 11:23:25', '2025-01-10 11:23:25'),
(94, 86, 2, '0.00', '0.00', '0.00', '100.00', '100.00', 'cash', 'active', NULL, NULL, NULL, NULL, 7, '2025-01-10 11:24:07', '2025-01-10 11:24:07'),
(95, 87, 2, '0.00', '0.00', '0.00', '250.00', '250.00', 'cash', 'active', NULL, NULL, NULL, NULL, 7, '2025-01-10 11:24:41', '2025-01-10 11:24:41'),
(96, 88, 2, '0.00', '0.00', '0.00', '500.00', '0.00', 'cash', 'active', NULL, NULL, NULL, NULL, 7, '2025-01-10 11:26:40', '2025-01-10 11:26:40'),
(97, 89, 2, '0.00', '0.00', '0.00', '300.00', '300.00', 'cash', 'active', NULL, NULL, NULL, NULL, 7, '2025-01-10 11:27:33', '2025-01-10 11:27:33'),
(98, 90, 2, '0.00', '0.00', '0.00', '250.00', '250.00', 'credit_card', 'active', NULL, NULL, NULL, NULL, 7, '2025-01-10 11:28:10', '2025-01-10 11:28:10'),
(99, 91, 2, '0.00', '0.00', '0.00', '200.00', '200.00', 'cash', 'active', NULL, NULL, NULL, NULL, 7, '2025-01-10 11:28:43', '2025-01-10 11:28:43'),
(100, 92, 2, '0.00', '0.00', '0.00', '1465.00', '0.00', 'cash', 'active', NULL, NULL, NULL, NULL, 7, '2025-01-10 11:30:15', '2025-01-10 11:30:15'),
(101, 93, 2, '0.00', '0.00', '0.00', '200.00', '200.00', 'credit_card', 'active', NULL, NULL, NULL, '1 pole not 2', 7, '2025-01-10 11:31:19', '2025-01-10 11:31:19'),
(102, 94, 2, '0.00', '0.00', '730.00', '1030.00', '0.00', 'credit_card', 'active', NULL, NULL, NULL, NULL, 7, '2025-01-10 11:33:43', '2025-01-10 11:33:43'),
(103, 95, 2, '0.00', '0.00', '0.00', '200.00', '200.00', 'cash', 'active', NULL, NULL, NULL, '1 pole not 2', 7, '2025-01-10 11:36:00', '2025-01-10 11:36:00'),
(104, 96, 2, '0.00', '0.00', '0.00', '100.00', '100.00', 'credit_card', 'active', NULL, NULL, NULL, NULL, 7, '2025-01-10 11:38:38', '2025-01-10 11:38:38'),
(105, 97, 2, '0.00', '0.00', '0.00', '200.00', '200.00', 'cash', 'active', NULL, NULL, NULL, NULL, 7, '2025-01-10 11:39:05', '2025-01-10 11:39:05'),
(106, 98, 2, '0.00', '0.00', '0.00', '200.00', '0.00', 'cash', 'active', NULL, NULL, NULL, NULL, 7, '2025-01-10 11:40:03', '2025-01-10 11:40:03'),
(107, 99, 2, '0.00', '0.00', '0.00', '315.00', '315.00', 'cash', 'active', NULL, NULL, NULL, NULL, 7, '2025-01-10 11:41:03', '2025-01-10 11:41:03'),
(108, 100, 2, '0.00', '0.00', '0.00', '150.00', '150.00', 'cash', 'active', NULL, NULL, NULL, NULL, 7, '2025-01-10 11:41:39', '2025-01-10 11:41:39'),
(109, 101, 2, '0.00', '0.00', '0.00', '400.00', '400.00', 'credit_card', 'active', NULL, NULL, NULL, NULL, 7, '2025-01-10 11:42:46', '2025-01-10 11:42:46'),
(110, 102, 2, '0.00', '0.00', '0.00', '150.00', '150.00', 'cash', 'active', NULL, NULL, NULL, NULL, 7, '2025-01-10 11:43:24', '2025-01-10 11:43:24'),
(111, 103, 2, '0.00', '0.00', '0.00', '300.00', '300.00', 'cash', 'active', NULL, NULL, NULL, NULL, 7, '2025-01-10 11:44:01', '2025-01-10 11:44:01'),
(112, 104, 2, '0.00', '0.00', '0.00', '450.00', '450.00', 'credit_card', 'active', NULL, NULL, NULL, NULL, 7, '2025-01-10 11:45:08', '2025-01-10 11:45:08'),
(113, 105, 2, '0.00', '0.00', '0.00', '300.00', '300.00', 'cash', 'active', NULL, NULL, NULL, '1 pole not 3', 7, '2025-01-10 11:45:48', '2025-01-10 11:45:48'),
(114, 106, 2, '0.00', '0.00', '0.00', '150.00', '150.00', 'cash', 'active', NULL, NULL, NULL, NULL, 7, '2025-01-10 11:46:44', '2025-01-10 11:46:44'),
(115, 107, 2, '0.00', '0.00', '0.00', '150.00', '150.00', 'cash', 'active', NULL, NULL, NULL, NULL, 7, '2025-01-10 11:48:47', '2025-01-10 11:48:47'),
(116, 108, 2, '0.00', '0.00', '0.00', '150.00', '150.00', 'cash', 'active', NULL, NULL, NULL, NULL, 7, '2025-01-10 11:49:48', '2025-01-10 11:49:48'),
(117, 109, 2, '0.00', '0.00', '0.00', '0.00', '0.00', 'cash', 'active', NULL, NULL, NULL, 'For Free', 7, '2025-01-10 11:50:49', '2025-01-10 11:50:49'),
(118, 110, 2, '0.00', '0.00', '0.00', '200.00', '200.00', 'cash', 'active', NULL, NULL, NULL, NULL, 7, '2025-01-10 11:54:29', '2025-01-10 11:54:29'),
(119, 111, 2, '0.00', '0.00', '0.00', '100.00', '100.00', 'cash', 'active', NULL, NULL, NULL, NULL, 7, '2025-01-10 11:54:55', '2025-01-10 11:54:55'),
(120, 112, 2, '0.00', '0.00', '0.00', '100.00', '100.00', 'cash', 'active', NULL, NULL, NULL, NULL, 7, '2025-01-10 11:56:42', '2025-01-10 11:56:42'),
(121, 113, 2, '0.00', '0.00', '0.00', '200.00', '200.00', 'cash', 'active', NULL, NULL, NULL, NULL, 7, '2025-01-10 11:57:30', '2025-01-10 11:57:30'),
(122, 114, 2, '0.00', '0.00', '0.00', '0.00', '0.00', 'cash', 'returned', NULL, NULL, NULL, NULL, 3, '2025-01-10 15:07:57', '2025-01-10 15:18:02'),
(123, 114, 2, '0.00', '0.00', '0.00', '0.00', '0.00', 'cash', 'active', NULL, NULL, NULL, NULL, 3, '2025-01-10 15:30:27', '2025-01-10 15:30:27'),
(124, 115, 2, '0.00', '0.00', '0.00', '300.00', '300.00', 'cash', 'active', NULL, NULL, NULL, NULL, 3, '2025-01-10 15:35:37', '2025-01-10 15:35:37'),
(125, 116, 2, '0.00', '0.00', '0.00', '500.00', '500.00', 'cash', 'active', NULL, NULL, NULL, NULL, 3, '2025-01-10 15:42:35', '2025-01-10 15:42:35'),
(126, 117, 2, '0.00', '0.00', '0.00', '200.00', '200.00', 'cash', 'active', NULL, NULL, NULL, NULL, 3, '2025-01-10 15:43:24', '2025-01-10 15:43:24'),
(127, 118, 2, '0.00', '0.00', '0.00', '790.00', '690.00', 'credit_card', 'active', NULL, NULL, NULL, NULL, 3, '2025-01-10 15:48:09', '2025-01-10 15:48:09'),
(128, 120, 2, '0.00', '0.00', '0.00', '250.00', '250.00', 'cash', 'active', NULL, NULL, NULL, NULL, 3, '2025-01-10 16:04:23', '2025-01-10 16:04:23'),
(129, 119, 2, '0.00', '0.00', '0.00', '1050.00', '0.00', 'cash', 'active', NULL, NULL, NULL, NULL, 3, '2025-01-10 16:08:02', '2025-01-10 16:08:02'),
(130, 121, 2, '0.00', '0.00', '0.00', '485.00', '485.00', 'cash', 'active', NULL, NULL, NULL, NULL, 3, '2025-01-10 16:18:37', '2025-01-10 16:18:37'),
(131, 122, 2, '0.00', '0.00', '0.00', '0.00', '0.00', 'cash', 'active', NULL, NULL, NULL, NULL, 3, '2025-01-10 16:20:38', '2025-01-10 16:20:38'),
(132, 123, 2, '0.00', '0.00', '0.00', '660.00', '0.00', 'cash', 'active', NULL, NULL, NULL, NULL, 3, '2025-01-10 16:21:41', '2025-01-10 16:21:41'),
(133, 124, 2, '0.00', '0.00', '0.00', '125.00', '125.00', 'cash', 'active', NULL, NULL, NULL, NULL, 3, '2025-01-10 16:24:57', '2025-01-10 16:24:57'),
(134, 125, 2, '0.00', '0.00', '0.00', '250.00', '250.00', 'credit_card', 'active', NULL, NULL, NULL, NULL, 3, '2025-01-10 16:26:04', '2025-01-10 16:26:04'),
(135, 126, 2, '0.00', '0.00', '0.00', '450.00', '450.00', 'cash', 'active', NULL, NULL, NULL, NULL, 3, '2025-01-10 16:27:57', '2025-01-10 16:27:57'),
(136, 127, 2, '0.00', '0.00', '0.00', '300.00', '300.00', 'credit_card', 'active', NULL, NULL, NULL, NULL, 3, '2025-01-10 16:28:29', '2025-01-10 16:28:29'),
(137, 128, 2, '0.00', '0.00', '0.00', '0.00', '0.00', 'cash', 'active', NULL, NULL, NULL, NULL, 3, '2025-01-10 16:29:04', '2025-01-10 16:29:04'),
(138, 129, 2, '0.00', '0.00', '0.00', '1540.00', '0.00', 'cash', 'active', NULL, NULL, NULL, NULL, 3, '2025-01-10 16:30:07', '2025-01-10 16:30:07'),
(139, 130, 2, '0.00', '0.00', '0.00', '1585.00', '0.00', 'cash', 'active', NULL, NULL, NULL, NULL, 3, '2025-01-10 16:32:35', '2025-01-10 16:32:35'),
(140, 131, 2, '0.00', '0.00', '0.00', '50.00', '50.00', 'cash', 'active', NULL, NULL, NULL, NULL, 3, '2025-01-10 16:33:20', '2025-01-14 13:51:00'),
(141, 132, 1, '0.00', '0.00', '0.00', '35.00', '35.00', 'cash', 'returned', '2025-01-11 12:00:00', '2025-01-11 12:00:00', 1, 'ID', 3, '2025-01-11 05:13:06', '2025-01-11 13:21:11'),
(142, 133, 1, '0.00', '30.00', '0.00', '14.00', '14.00', 'cash', 'returned', '2025-01-11 12:00:00', '2025-01-11 12:00:00', 1, 'No ID', 3, '2025-01-11 05:16:12', '2025-01-11 11:53:06'),
(143, 134, 2, '0.00', '0.00', '0.00', '200.00', '200.00', 'cash', 'active', NULL, NULL, NULL, NULL, 3, '2025-01-11 05:43:04', '2025-01-11 05:43:04'),
(144, 135, 2, '0.00', '0.00', '0.00', '200.00', '200.00', 'credit_card', 'active', NULL, NULL, NULL, NULL, 3, '2025-01-11 05:43:47', '2025-01-11 05:43:47'),
(145, 135, 1, '0.00', '0.00', '0.00', '5.00', '5.00', 'cash', 'returned', '2025-01-11 12:00:00', '2025-01-11 14:00:00', 1, NULL, 3, '2025-01-11 05:46:39', '2025-01-11 15:11:10'),
(146, 136, 1, '0.00', '0.00', '0.00', '178.00', '188.00', 'cash', 'returned', '2025-01-11 12:00:00', '2025-01-11 12:00:00', 1, NULL, 3, '2025-01-11 06:13:36', '2025-01-11 14:20:29'),
(147, 137, 1, '0.00', '0.00', '0.00', '15.00', '15.00', 'cash', 'returned', '2025-01-11 12:00:00', '2025-01-11 12:00:00', 1, 'ID', 3, '2025-01-11 06:18:45', '2025-01-11 11:26:31'),
(148, 138, 2, '0.00', '0.00', '0.00', '350.00', '350.00', 'cash', 'returned', NULL, NULL, NULL, NULL, 3, '2025-01-11 06:25:54', '2025-01-16 14:47:36'),
(149, 139, 2, '0.00', '0.00', '0.00', '250.00', '0.00', 'cash', 'active', NULL, NULL, NULL, NULL, 3, '2025-01-11 06:30:39', '2025-01-11 06:30:39'),
(150, 140, 1, '0.00', '21.00', '0.00', '67.15', '67.15', 'cash', 'returned', '2025-01-11 12:00:00', '2025-01-11 12:00:00', 1, NULL, 3, '2025-01-11 06:42:46', '2025-01-11 14:04:24'),
(151, 141, 1, '0.00', '0.00', '0.00', '20.00', '20.00', 'cash', 'returned', '2025-01-11 12:00:00', '2025-01-11 12:00:00', 1, 'Only 1 poles', 3, '2025-01-11 06:43:49', '2025-01-11 12:05:45'),
(152, 142, 1, '0.00', '0.00', '5.00', '5.00', '0.00', 'cash', 'returned', '2025-01-11 12:00:00', '2025-01-11 12:00:00', 1, NULL, 3, '2025-01-11 07:14:07', '2025-01-11 16:10:15'),
(153, 143, 1, '0.00', '0.00', '0.00', '20.00', '20.00', 'cash', 'returned', '2025-01-11 12:00:00', '2025-01-11 12:00:00', 1, 'No poles- insurance card', 3, '2025-01-11 07:15:16', '2025-01-11 12:47:19'),
(154, 144, 1, '0.00', '0.00', '0.00', '30.00', '30.00', 'cash', 'returned', '2025-01-11 12:00:00', '2025-01-11 12:00:00', 1, NULL, 3, '2025-01-11 09:22:59', '2025-01-11 14:35:55'),
(155, 138, 2, '0.00', '0.00', '0.00', '800.00', '800.00', 'cash', 'active', NULL, NULL, NULL, NULL, 3, '2025-01-11 11:51:47', '2025-01-11 11:51:47'),
(156, 145, 2, '0.00', '0.00', '0.00', '100.00', '100.00', 'cash', 'active', NULL, NULL, NULL, 'with no poles', 3, '2025-01-11 13:23:59', '2025-01-11 13:23:59'),
(157, 146, 2, '0.00', '0.00', '0.00', '100.00', '100.00', 'cash', 'active', NULL, NULL, NULL, NULL, 3, '2025-01-11 13:47:21', '2025-01-11 13:47:21'),
(158, 147, 1, '0.00', '0.00', '0.00', '20.00', '20.00', 'cash', 'returned', '2025-01-11 12:00:00', '2025-01-11 12:00:00', 1, 'No poles', 3, '2025-01-11 14:46:31', '2025-01-11 14:56:36'),
(159, 147, 1, '0.00', '0.00', '0.00', '20.00', '20.00', 'cash', 'returned', '2025-01-12 12:00:00', '2025-01-12 12:00:00', 1, NULL, 3, '2025-01-11 14:57:41', '2025-01-12 16:14:57'),
(160, 148, 1, '0.00', '0.00', '0.00', '15.00', '15.00', 'cash', 'returned', '2025-01-12 12:00:00', '2025-01-12 12:00:00', 1, NULL, 3, '2025-01-11 15:22:41', '2025-01-12 13:10:54'),
(161, 142, 1, '0.00', '100.00', '0.00', '0.00', '0.00', 'cash', 'returned', '2025-01-12 12:00:00', '2025-01-12 12:00:00', 1, NULL, 3, '2025-01-11 16:11:14', '2025-01-12 15:00:02'),
(162, 149, 2, '0.00', '0.00', '0.00', '180.00', '180.00', 'cash', 'active', NULL, NULL, NULL, NULL, 3, '2025-01-12 05:37:36', '2025-01-12 05:37:36'),
(163, 150, 1, '0.00', '0.00', '0.00', '25.00', '25.00', 'cash', 'returned', '2025-01-12 12:00:00', '2025-01-12 12:00:00', 1, 'with no poles', 3, '2025-01-12 05:38:42', '2025-01-12 12:33:50'),
(164, 151, 1, '0.00', '0.00', '0.00', '10.00', '10.00', 'cash', 'returned', '2025-01-12 12:00:00', '2025-01-12 12:00:00', 1, 'no poles', 3, '2025-01-12 05:40:14', '2025-01-12 11:25:30'),
(165, 152, 1, '0.00', '0.00', '0.00', '15.00', '15.00', 'cash', 'returned', '2025-01-12 12:00:00', '2025-01-12 12:00:00', 1, 'card assurance', 3, '2025-01-12 05:44:51', '2025-01-12 13:06:52'),
(167, 154, 1, '0.00', '0.00', '0.00', '27.00', '32.00', 'cash', 'returned', '2025-01-12 12:00:00', '2025-01-12 12:00:00', 1, 'ID', 3, '2025-01-12 05:55:45', '2025-01-12 11:36:50'),
(168, 155, 1, '0.00', '0.00', '0.00', '30.00', '30.00', 'cash', 'returned', '2025-01-12 12:00:00', '2025-01-12 12:00:00', 1, 'Id', 3, '2025-01-12 05:58:59', '2025-01-12 11:54:52'),
(169, 156, 1, '0.00', '0.00', '0.00', '15.00', '15.00', 'cash', 'returned', '2025-01-12 12:00:00', '2025-01-12 12:00:00', 1, NULL, 3, '2025-01-12 06:02:39', '2025-01-12 16:39:53'),
(170, 157, 1, '0.00', '0.00', '0.00', '25.00', '25.00', 'cash', 'returned', '2025-01-12 12:00:00', '2025-01-12 12:00:00', 1, 'ID', 3, '2025-01-12 06:03:28', '2025-01-12 16:39:26'),
(171, 158, 1, '0.00', '0.00', '0.00', '10.00', '10.00', 'cash', 'returned', '2025-01-12 12:00:00', '2025-01-12 12:00:00', 1, 'passport', 3, '2025-01-12 06:04:44', '2025-01-12 11:37:10'),
(172, 159, 1, '0.00', '0.00', '0.00', '44.00', '44.00', 'cash', 'returned', '2025-01-12 12:00:00', '2025-01-12 12:00:00', 1, 'no poles', 3, '2025-01-12 06:16:08', '2025-01-12 12:45:03'),
(174, 153, 1, '0.00', '65.00', '0.00', '19.95', '19.95', 'cash', 'returned', '2025-01-12 12:00:00', '2025-01-12 12:00:00', 1, NULL, 3, '2025-01-12 06:21:10', '2025-01-12 07:13:00'),
(175, 160, 2, '0.00', '0.00', '0.00', '200.00', '200.00', 'cash', 'active', NULL, NULL, NULL, NULL, 3, '2025-01-12 06:42:46', '2025-01-12 06:42:46'),
(176, 161, 1, '0.00', '0.00', '0.00', '90.00', '90.00', 'cash', 'returned', '2025-01-12 12:00:00', '2025-01-12 12:00:00', 1, 'car card', 3, '2025-01-12 07:40:50', '2025-01-12 14:30:08'),
(177, 162, 1, '0.00', '0.00', '0.00', '54.00', '54.00', 'cash', 'returned', '2025-01-12 12:00:00', '2025-01-12 12:00:00', 1, 'ID', 3, '2025-01-12 08:15:36', '2025-01-12 12:39:34'),
(178, 163, 1, '0.00', '0.00', '0.00', '17.00', '17.00', 'cash', 'returned', '2025-01-12 12:00:00', '2025-01-12 12:00:00', 1, 'ID-- no poles', 3, '2025-01-12 08:43:00', '2025-01-12 12:58:28'),
(179, 164, 1, '0.00', '0.00', '0.00', '30.00', '30.00', 'cash', 'returned', '2025-01-12 12:00:00', '2025-01-12 12:00:00', 1, NULL, 3, '2025-01-12 09:50:18', '2025-01-12 14:32:38'),
(180, 165, 2, '0.00', '0.00', '0.00', '100.00', '100.00', 'cash', 'active', NULL, NULL, NULL, NULL, 3, '2025-01-12 14:57:07', '2025-01-12 14:57:07'),
(181, 166, 1, '0.00', '0.00', '0.00', '60.00', '60.00', 'cash', 'returned', '2025-01-13 12:00:00', '2025-01-13 12:00:00', 1, 'ID', 3, '2025-01-13 05:29:23', '2025-01-13 12:28:59'),
(182, 167, 1, '0.00', '0.00', '0.00', '20.00', '20.00', 'cash', 'returned', '2025-01-13 12:00:00', '2025-01-13 12:00:00', 1, 'ID US', 3, '2025-01-13 05:30:07', '2025-01-13 10:42:54'),
(183, 168, 1, '0.00', '0.00', '0.00', '52.00', '52.00', 'cash', 'returned', '2025-01-13 12:00:00', '2025-01-13 12:00:00', 1, 'car card', 3, '2025-01-13 05:40:07', '2025-01-13 13:18:13'),
(184, 169, 1, '0.00', '0.00', '0.00', '20.00', '20.00', 'cash', 'returned', '2025-01-13 12:00:00', '2025-01-13 12:00:00', 1, NULL, 3, '2025-01-13 06:06:21', '2025-01-13 13:51:18'),
(185, 170, 1, '0.00', '0.00', '0.00', '22.00', '22.00', 'cash', 'returned', '2025-01-13 12:00:00', '2025-01-13 12:00:00', 1, 'no poles', 3, '2025-01-13 06:32:59', '2025-01-13 12:01:19'),
(186, 171, 1, '0.00', '100.00', '0.00', '0.00', '0.00', 'cash', 'returned', '2025-01-13 12:00:00', '2025-01-13 12:00:00', 1, NULL, 3, '2025-01-13 06:46:08', '2025-01-13 12:39:34'),
(187, 172, 1, '0.00', '0.00', '0.00', '135.00', '135.00', 'cash', 'returned', '2025-01-13 12:00:00', '2025-01-13 12:00:00', 1, 'id', 7, '2025-01-13 09:20:24', '2025-01-13 14:09:34'),
(188, 173, 1, '0.00', '19.00', '0.00', '38.07', '38.07', 'cash', 'returned', '2025-01-14 12:00:00', '2025-01-14 12:00:00', 1, 'id uni lau', 7, '2025-01-14 05:21:29', '2025-01-14 14:38:58'),
(189, 174, 1, '0.00', '0.00', '0.00', '35.00', '35.00', 'cash', 'returned', '2025-01-14 12:00:00', '2025-01-14 12:00:00', 1, NULL, 7, '2025-01-14 05:25:51', '2025-01-14 13:52:27'),
(190, 175, 1, '0.00', '19.00', '0.00', '89.91', '89.91', 'cash', 'returned', '2025-01-14 12:00:00', '2025-01-14 12:00:00', 1, NULL, 7, '2025-01-14 05:31:28', '2025-01-14 14:13:38'),
(191, 176, 1, '0.00', '0.00', '0.00', '30.00', '30.00', 'cash', 'returned', '2025-01-14 12:00:00', '2025-01-14 12:00:00', 1, 'id uni ndu', 7, '2025-01-14 05:34:46', '2025-01-22 10:02:39'),
(192, 177, 1, '0.00', '0.00', '0.00', '20.00', '20.00', 'cash', 'returned', '2025-01-14 12:00:00', '2025-01-14 12:00:00', 1, 'id', 7, '2025-01-14 05:42:18', '2025-01-14 12:43:20'),
(193, 178, 1, '0.00', '0.00', '0.00', '15.00', '15.00', 'cash', 'returned', '2025-01-14 12:00:00', '2025-01-14 12:00:00', 1, 'id', 7, '2025-01-14 05:43:35', '2025-01-14 12:17:55'),
(194, 179, 1, '0.00', '0.00', '0.00', '44.00', '44.00', 'cash', 'returned', '2025-01-14 12:00:00', '2025-01-14 12:00:00', 1, NULL, 7, '2025-01-14 05:45:34', '2025-01-14 12:21:52'),
(195, 180, 1, '0.00', '0.00', '0.00', '30.00', '30.00', 'cash', 'returned', '2025-01-14 12:00:00', '2025-01-14 12:00:00', 1, NULL, 7, '2025-01-14 05:50:46', '2025-01-14 12:23:10'),
(196, 181, 1, '0.00', '0.00', '0.00', '30.00', '30.00', 'cash', 'returned', '2025-01-14 12:00:00', '2025-01-14 12:00:00', 1, NULL, 7, '2025-01-14 06:50:13', '2025-01-14 14:18:20'),
(197, 182, 1, '0.00', '0.00', '0.00', '27.00', '27.00', 'cash', 'returned', '2025-01-14 12:00:00', '2025-01-14 12:00:00', 1, 'id', 7, '2025-01-14 07:00:50', '2025-01-14 11:52:57'),
(198, 183, 1, '0.00', '0.00', '0.00', '40.00', '40.00', 'cash', 'returned', '2025-01-14 12:00:00', '2025-01-14 12:00:00', 1, NULL, 7, '2025-01-14 07:03:30', '2025-01-14 11:52:23'),
(199, 184, 1, '0.00', '0.00', '0.00', '15.00', '15.00', 'cash', 'returned', '2025-01-14 12:00:00', '2025-01-14 12:00:00', 1, 'id', 7, '2025-01-14 09:31:16', '2025-01-14 15:06:41'),
(200, 185, 1, '0.00', '15.79', '0.00', '48.00', '48.00', 'cash', 'returned', '2025-01-15 12:00:00', '2025-01-15 12:00:00', 1, NULL, 7, '2025-01-15 05:52:57', '2025-01-15 16:03:15'),
(201, 186, 1, '0.00', '0.00', '0.00', '20.00', '20.00', 'cash', 'returned', '2025-01-15 12:00:00', '2025-01-15 12:00:00', 1, 'driving license', 7, '2025-01-15 06:03:08', '2025-01-15 12:51:43'),
(202, 148, 1, '0.00', '0.00', '0.00', '30.00', '30.00', 'cash', 'returned', '2025-01-15 12:00:00', '2025-01-15 12:00:00', 1, NULL, 7, '2025-01-15 06:42:33', '2025-01-15 11:57:29'),
(203, 188, 1, '0.00', '0.00', '0.00', '15.00', '15.00', 'cash', 'returned', '2025-01-15 12:00:00', '2025-01-15 12:00:00', 1, 'id', 7, '2025-01-15 06:44:12', '2025-01-15 12:24:21'),
(204, 189, 1, '0.00', '0.00', '0.00', '10.00', '10.00', 'cash', 'returned', '2025-01-15 09:00:00', '2025-01-15 15:00:00', 1, '+ poles', 7, '2025-01-15 06:57:14', '2025-01-15 12:12:41'),
(205, 190, 1, '0.00', '0.00', '0.00', '47.00', '47.00', 'cash', 'returned', '2025-01-15 12:00:00', '2025-01-15 12:00:00', 1, '2 poles only', 7, '2025-01-15 07:37:55', '2025-01-15 13:51:21'),
(206, 191, 1, '0.00', '0.00', '0.00', '15.00', '15.00', 'cash', 'returned', '2025-01-15 12:00:00', '2025-01-15 12:00:00', 1, 'no boots', 7, '2025-01-15 07:49:36', '2025-01-15 14:08:44'),
(207, 192, 1, '0.00', '0.00', '0.00', '40.00', '40.00', 'cash', 'returned', '2025-01-15 12:00:00', '2025-01-15 12:00:00', 1, 'id', 7, '2025-01-15 08:30:39', '2025-01-15 15:04:12'),
(208, 193, 1, '0.00', '0.00', '0.00', '20.00', '20.00', 'cash', 'returned', '2025-01-15 12:00:00', '2025-01-15 12:00:00', 1, NULL, 7, '2025-01-15 08:41:19', '2025-01-15 13:49:02'),
(209, 194, 1, '0.00', '0.00', '0.00', '50.00', '50.00', 'cash', 'returned', '2025-01-16 12:00:00', '2025-01-16 12:00:00', 1, 'id', 7, '2025-01-16 05:40:50', '2025-01-16 10:29:22'),
(210, 195, 1, '0.00', '0.00', '0.00', '20.00', '20.00', 'cash', 'returned', '2025-01-16 08:00:00', '2025-01-16 12:00:00', 1, 'id', 7, '2025-01-16 05:52:43', '2025-01-16 10:35:57'),
(211, 196, 1, '0.00', '0.00', '0.00', '27.00', '27.00', 'cash', 'returned', '2025-01-16 12:00:00', '2025-01-16 12:00:00', 1, 'driving license', 7, '2025-01-16 06:36:17', '2025-01-16 12:33:58'),
(212, 197, 2, '0.00', '0.00', '0.00', '0.00', '0.00', 'cash', 'active', NULL, NULL, NULL, 'free', 7, '2025-01-16 10:11:18', '2025-01-16 10:11:18'),
(213, 138, 2, '0.00', '0.00', '0.00', '550.00', '550.00', 'cash', 'active', NULL, NULL, NULL, NULL, 7, '2025-01-16 14:26:43', '2025-01-16 14:48:54'),
(214, 198, 1, '0.00', '14.00', '0.00', '90.30', '90.00', 'cash', 'returned', '2025-01-18 07:00:00', '2025-01-18 15:00:00', 1, NULL, 7, '2025-01-18 05:18:56', '2025-01-18 13:09:08'),
(215, 199, 1, '0.00', '100.00', '0.00', '0.00', '0.00', 'cash', 'returned', '2025-01-18 07:40:00', '2025-01-19 16:10:00', 2, '1 gs 188cm  - free', 7, '2025-01-18 05:29:45', '2025-01-22 10:02:21'),
(216, 201, 1, '0.00', '0.00', '0.00', '44.00', '44.00', 'cash', 'returned', '2025-01-18 07:55:00', '2025-01-18 16:00:00', 1, NULL, 7, '2025-01-18 05:34:31', '2025-01-18 12:03:12'),
(217, 153, 1, '0.00', '0.00', '0.00', '40.00', '40.00', 'cash', 'returned', '2025-01-18 08:00:00', '2025-01-18 16:00:00', 1, '2 poles', 7, '2025-01-18 06:12:30', '2025-01-18 13:07:15'),
(218, 202, 1, '0.00', '0.00', '0.00', '27.00', '27.00', 'cash', 'returned', '2025-01-18 12:00:00', '2025-01-18 12:00:00', 1, 'german embassy', 7, '2025-01-18 07:44:14', '2025-01-18 14:26:58'),
(219, 203, 1, '0.00', '15.00', '0.00', '119.00', '119.00', 'cash', 'returned', '2025-01-18 11:00:00', '2025-01-18 16:10:00', 1, NULL, 7, '2025-01-18 09:10:30', '2025-01-22 10:01:59'),
(220, 194, 1, '0.00', '0.00', '0.00', '15.00', '15.00', 'cash', 'returned', '2025-01-19 12:00:00', '2025-01-19 12:00:00', 1, 'k2 botte', 7, '2025-01-19 06:02:22', '2025-01-19 13:02:10'),
(221, 159, 1, '0.00', '0.00', '0.00', '44.00', '44.00', 'cash', 'returned', '2025-01-19 12:00:00', '2025-01-19 12:00:00', 1, 'no poles', 7, '2025-01-19 06:39:30', '2025-01-19 10:40:43'),
(222, 204, 1, '0.00', '0.00', '0.00', '15.00', '15.00', 'cash', 'returned', '2025-01-19 12:00:00', '2025-01-19 12:00:00', 1, NULL, 7, '2025-01-19 06:58:54', '2025-01-19 13:07:08'),
(223, 205, 1, '0.00', '0.00', '0.00', '30.00', '30.00', 'cash', 'returned', '2025-01-19 12:00:00', '2025-01-19 12:00:00', 1, '1 casque', 7, '2025-01-19 07:37:04', '2025-01-19 13:38:53'),
(224, 206, 1, '0.00', '0.00', '0.00', '135.00', '135.00', 'cash', 'returned', '2025-01-19 12:00:00', '2025-01-19 12:00:00', 1, 'id + helmet', 7, '2025-01-19 08:52:23', '2025-01-19 14:45:25'),
(225, 207, 1, '0.00', '0.00', '0.00', '40.00', '40.00', 'cash', 'returned', '2025-01-20 12:00:00', '2025-01-20 12:00:00', 1, 'id', 7, '2025-01-20 05:15:32', '2025-01-20 13:07:09'),
(226, 208, 1, '0.00', '16.00', '0.00', '25.20', '25.00', 'cash', 'returned', '2025-01-20 12:00:00', '2025-01-20 12:00:00', 1, NULL, 7, '2025-01-20 05:17:27', '2025-01-20 12:02:13'),
(227, 209, 1, '0.00', '0.00', '0.00', '70.00', '70.00', 'cash', 'returned', '2025-01-20 12:00:00', '2025-01-20 12:00:00', 1, 'id', 7, '2025-01-20 06:50:46', '2025-01-20 10:57:57'),
(228, 210, 1, '0.00', '0.00', '0.00', '15.00', '15.00', 'cash', 'returned', '2025-01-21 08:00:00', '2025-01-21 17:00:00', 1, NULL, 7, '2025-01-21 06:06:11', '2025-01-21 12:20:01'),
(229, 211, 1, '0.00', '33.30', '0.00', '20.01', '20.01', 'cash', 'returned', '2025-01-21 08:00:00', '2025-01-21 15:00:00', 1, NULL, 7, '2025-01-21 06:37:04', '2025-01-21 13:46:47'),
(230, 212, 1, '0.00', '0.00', '0.00', '17.00', '17.00', 'cash', 'returned', '2025-01-21 12:00:00', '2025-01-21 12:00:00', 1, 'driving license', 7, '2025-01-21 07:54:30', '2025-01-21 10:49:56');

-- --------------------------------------------------------

--
-- Table structure for table `invoice_items`
--

CREATE TABLE `invoice_items` (
  `id` bigint UNSIGNED NOT NULL,
  `invoice_id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `quantity` int NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `rental_start_date` datetime DEFAULT NULL,
  `rental_end_date` datetime DEFAULT NULL,
  `days` int DEFAULT NULL,
  `returned_quantity` int NOT NULL DEFAULT '0',
  `added_quantity` int NOT NULL DEFAULT '0',
  `status` enum('draft','active','returned','overdue') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `is_additional` tinyint(1) NOT NULL DEFAULT '0',
  `details` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `invoice_items`
--

INSERT INTO `invoice_items` (`id`, `invoice_id`, `product_id`, `quantity`, `price`, `total_price`, `rental_start_date`, `rental_end_date`, `days`, `returned_quantity`, `added_quantity`, `status`, `is_additional`, `details`, `created_at`, `updated_at`, `deleted_at`) VALUES
(20, 8, 32, 3, '100.00', '300.00', NULL, NULL, NULL, 0, 0, 'active', 0, NULL, '2025-01-09 14:25:31', '2025-01-09 14:25:31', NULL),
(23, 10, 31, 2, '200.00', '400.00', NULL, NULL, NULL, 0, 0, 'active', 0, NULL, '2025-01-09 14:45:01', '2025-01-09 14:45:01', NULL),
(24, 11, 32, 1, '100.00', '100.00', NULL, NULL, NULL, 0, 0, 'active', 0, NULL, '2025-01-09 14:48:08', '2025-01-09 14:48:08', NULL),
(25, 12, 39, 1, '50.00', '50.00', NULL, NULL, NULL, 0, 0, 'active', 0, NULL, '2025-01-09 14:49:52', '2025-01-09 14:49:52', NULL),
(26, 12, 38, 1, '100.00', '100.00', NULL, NULL, NULL, 0, 0, 'active', 0, NULL, '2025-01-09 14:49:52', '2025-01-09 14:49:52', NULL),
(27, 13, 39, 1, '50.00', '50.00', NULL, NULL, NULL, 0, 0, 'active', 0, NULL, '2025-01-09 14:49:55', '2025-01-09 14:49:55', NULL),
(28, 13, 38, 1, '100.00', '100.00', NULL, NULL, NULL, 0, 0, 'active', 0, NULL, '2025-01-09 14:49:55', '2025-01-09 14:49:55', NULL),
(29, 21, 32, 1, '100.00', '100.00', NULL, NULL, NULL, 0, 0, 'active', 0, NULL, '2025-01-09 15:04:00', '2025-01-09 15:04:00', NULL),
(30, 22, 31, 2, '200.00', '400.00', NULL, NULL, NULL, 0, 0, 'active', 0, NULL, '2025-01-09 15:05:22', '2025-01-09 15:05:22', NULL),
(31, 23, 35, 2, '150.00', '300.00', NULL, NULL, NULL, 0, 0, 'active', 0, NULL, '2025-01-09 15:08:18', '2025-01-09 15:08:18', NULL),
(32, 23, 32, 2, '100.00', '200.00', NULL, NULL, NULL, 0, 0, 'active', 0, NULL, '2025-01-09 15:08:18', '2025-01-09 15:08:18', NULL),
(33, 26, 32, 2, '100.00', '200.00', NULL, NULL, NULL, 0, 0, 'active', 0, NULL, '2025-01-09 15:12:14', '2025-01-09 15:12:14', NULL),
(34, 30, 35, 2, '150.00', '300.00', NULL, NULL, NULL, 0, 0, 'active', 0, NULL, '2025-01-09 15:40:53', '2025-01-09 15:40:53', NULL),
(35, 31, 43, 2, '200.00', '400.00', NULL, NULL, NULL, 0, 0, 'active', 0, NULL, '2025-01-09 15:42:41', '2025-01-09 15:42:41', NULL),
(36, 32, 31, 2, '200.00', '400.00', NULL, NULL, NULL, 0, 0, 'active', 0, NULL, '2025-01-09 15:43:34', '2025-01-09 15:43:34', NULL),
(37, 34, 32, 2, '100.00', '200.00', NULL, NULL, NULL, 0, 0, 'active', 0, NULL, '2025-01-09 15:45:42', '2025-01-09 15:45:42', NULL),
(38, 35, 33, 1, '250.00', '250.00', NULL, NULL, NULL, 0, 0, 'active', 0, NULL, '2025-01-09 15:46:29', '2025-01-09 15:46:29', NULL),
(39, 44, 34, 1, '300.00', '300.00', NULL, NULL, NULL, 0, 0, 'active', 0, NULL, '2025-01-10 08:41:57', '2025-01-10 08:41:57', NULL),
(40, 45, 35, 1, '150.00', '150.00', NULL, NULL, NULL, 0, 0, 'active', 0, NULL, '2025-01-10 08:42:49', '2025-01-10 08:42:49', NULL),
(41, 46, 35, 1, '150.00', '150.00', NULL, NULL, NULL, 0, 0, 'active', 0, NULL, '2025-01-10 08:45:49', '2025-01-10 08:45:49', NULL),
(42, 47, 31, 2, '200.00', '400.00', NULL, NULL, NULL, 0, 0, 'active', 0, NULL, '2025-01-10 08:48:34', '2025-01-10 08:48:34', NULL),
(48, 52, 35, 1, '150.00', '150.00', NULL, NULL, NULL, 0, 0, 'active', 0, NULL, '2025-01-10 09:33:42', '2025-01-10 09:33:42', NULL),
(49, 54, 33, 2, '250.00', '500.00', NULL, NULL, NULL, 0, 0, 'active', 0, NULL, '2025-01-10 09:36:38', '2025-01-10 09:36:38', NULL),
(50, 54, 31, 1, '200.00', '200.00', NULL, NULL, NULL, 0, 0, 'active', 0, NULL, '2025-01-10 09:36:38', '2025-01-10 09:36:38', NULL),
(51, 55, 32, 2, '100.00', '200.00', NULL, NULL, NULL, 0, 0, 'active', 0, NULL, '2025-01-10 09:37:23', '2025-01-10 09:37:23', NULL),
(52, 60, 35, 1, '150.00', '150.00', NULL, NULL, NULL, 0, 0, 'active', 0, NULL, '2025-01-10 09:47:03', '2025-01-10 09:47:03', NULL),
(53, 62, 33, 1, '250.00', '250.00', NULL, NULL, NULL, 0, 0, 'active', 0, NULL, '2025-01-10 09:50:32', '2025-01-10 09:50:32', NULL),
(54, 64, 33, 1, '250.00', '250.00', NULL, NULL, NULL, 0, 0, 'active', 0, NULL, '2025-01-10 09:52:51', '2025-01-10 09:52:51', NULL),
(55, 65, 33, 1, '250.00', '250.00', NULL, NULL, NULL, 0, 0, 'active', 0, NULL, '2025-01-10 09:53:32', '2025-01-10 09:53:32', NULL),
(56, 66, 35, 3, '150.00', '450.00', NULL, NULL, NULL, 0, 0, 'active', 0, NULL, '2025-01-10 09:54:16', '2025-01-10 09:54:16', NULL),
(57, 67, 32, 1, '100.00', '100.00', NULL, NULL, NULL, 0, 0, 'active', 0, NULL, '2025-01-10 09:55:04', '2025-01-10 09:55:04', NULL),
(58, 71, 35, 2, '150.00', '300.00', NULL, NULL, NULL, 0, 0, 'active', 0, NULL, '2025-01-10 09:58:50', '2025-01-10 09:58:50', NULL),
(59, 72, 35, 1, '150.00', '150.00', NULL, NULL, NULL, 0, 0, 'active', 0, NULL, '2025-01-10 09:59:40', '2025-01-10 09:59:40', NULL),
(60, 73, 34, 2, '300.00', '600.00', NULL, NULL, NULL, 0, 0, 'active', 0, NULL, '2025-01-10 10:48:01', '2025-01-10 10:48:01', NULL),
(61, 74, 35, 2, '150.00', '300.00', NULL, NULL, NULL, 0, 0, 'active', 0, NULL, '2025-01-10 10:48:37', '2025-01-10 10:48:37', NULL),
(62, 75, 35, 1, '150.00', '150.00', NULL, NULL, NULL, 0, 0, 'active', 0, NULL, '2025-01-10 10:49:23', '2025-01-10 10:49:23', NULL),
(63, 77, 31, 1, '200.00', '200.00', NULL, NULL, NULL, 0, 0, 'active', 0, NULL, '2025-01-10 10:53:53', '2025-01-10 10:53:53', NULL),
(64, 77, 35, 1, '150.00', '150.00', NULL, NULL, NULL, 0, 0, 'active', 0, NULL, '2025-01-10 10:53:53', '2025-01-10 10:53:53', NULL),
(65, 79, 32, 2, '100.00', '200.00', NULL, NULL, NULL, 0, 0, 'active', 0, NULL, '2025-01-10 10:56:10', '2025-01-10 10:56:10', NULL),
(66, 79, 33, 1, '250.00', '250.00', NULL, NULL, NULL, 0, 0, 'active', 0, NULL, '2025-01-10 10:56:10', '2025-01-10 10:56:10', NULL),
(67, 82, 34, 1, '300.00', '300.00', NULL, NULL, NULL, 0, 0, 'active', 0, NULL, '2025-01-10 11:01:22', '2025-01-10 11:01:22', NULL),
(68, 84, 35, 1, '150.00', '150.00', NULL, NULL, NULL, 0, 0, 'active', 0, NULL, '2025-01-10 11:03:29', '2025-01-10 11:03:29', NULL),
(69, 85, 32, 2, '100.00', '200.00', NULL, NULL, NULL, 0, 0, 'active', 0, NULL, '2025-01-10 11:12:02', '2025-01-10 11:12:02', NULL),
(70, 86, 32, 1, '100.00', '100.00', NULL, NULL, NULL, 0, 0, 'active', 0, NULL, '2025-01-10 11:12:46', '2025-01-10 11:12:46', NULL),
(71, 86, 35, 1, '150.00', '150.00', NULL, NULL, NULL, 0, 0, 'active', 0, NULL, '2025-01-10 11:12:46', '2025-01-10 11:12:46', NULL),
(72, 91, 32, 1, '100.00', '100.00', NULL, NULL, NULL, 0, 0, 'active', 0, NULL, '2025-01-10 11:21:21', '2025-01-10 11:21:21', NULL),
(73, 91, 35, 1, '150.00', '150.00', NULL, NULL, NULL, 0, 0, 'active', 0, NULL, '2025-01-10 11:21:21', '2025-01-10 11:21:21', NULL),
(74, 94, 32, 1, '100.00', '100.00', NULL, NULL, NULL, 0, 0, 'active', 0, NULL, '2025-01-10 11:24:07', '2025-01-10 11:24:07', NULL),
(75, 95, 32, 1, '100.00', '100.00', NULL, NULL, NULL, 0, 0, 'active', 0, NULL, '2025-01-10 11:24:41', '2025-01-10 11:24:41', NULL),
(76, 95, 35, 1, '150.00', '150.00', NULL, NULL, NULL, 0, 0, 'active', 0, NULL, '2025-01-10 11:24:41', '2025-01-10 11:24:41', NULL),
(77, 98, 33, 1, '250.00', '250.00', NULL, NULL, NULL, 0, 0, 'active', 0, NULL, '2025-01-10 11:28:10', '2025-01-10 11:28:10', NULL),
(78, 99, 32, 2, '100.00', '200.00', NULL, NULL, NULL, 0, 0, 'active', 0, NULL, '2025-01-10 11:28:43', '2025-01-10 11:28:43', NULL),
(79, 101, 32, 2, '100.00', '200.00', NULL, NULL, NULL, 0, 0, 'active', 0, NULL, '2025-01-10 11:31:19', '2025-01-10 11:31:19', NULL),
(80, 103, 32, 2, '100.00', '200.00', NULL, NULL, NULL, 0, 0, 'active', 0, NULL, '2025-01-10 11:36:00', '2025-01-10 11:36:00', NULL),
(81, 105, 32, 2, '100.00', '200.00', NULL, NULL, NULL, 0, 0, 'active', 0, NULL, '2025-01-10 11:39:05', '2025-01-10 11:39:05', NULL),
(82, 108, 35, 1, '150.00', '150.00', NULL, NULL, NULL, 0, 0, 'active', 0, NULL, '2025-01-10 11:41:39', '2025-01-10 11:41:39', NULL),
(83, 109, 31, 2, '200.00', '400.00', NULL, NULL, NULL, 0, 0, 'active', 0, NULL, '2025-01-10 11:42:46', '2025-01-10 11:42:46', NULL),
(84, 110, 35, 1, '150.00', '150.00', NULL, NULL, NULL, 0, 0, 'active', 0, NULL, '2025-01-10 11:43:24', '2025-01-10 11:43:24', NULL),
(85, 111, 35, 2, '150.00', '300.00', NULL, NULL, NULL, 0, 0, 'active', 0, NULL, '2025-01-10 11:44:01', '2025-01-10 11:44:01', NULL),
(86, 112, 35, 3, '150.00', '450.00', NULL, NULL, NULL, 0, 0, 'active', 0, NULL, '2025-01-10 11:45:08', '2025-01-10 11:45:08', NULL),
(87, 113, 32, 3, '100.00', '300.00', NULL, NULL, NULL, 0, 0, 'active', 0, NULL, '2025-01-10 11:45:48', '2025-01-10 11:45:48', NULL),
(88, 118, 31, 1, '200.00', '200.00', NULL, NULL, NULL, 0, 0, 'active', 0, NULL, '2025-01-10 11:54:29', '2025-01-10 11:54:29', NULL),
(89, 119, 32, 1, '100.00', '100.00', NULL, NULL, NULL, 0, 0, 'active', 0, NULL, '2025-01-10 11:54:55', '2025-01-10 11:54:55', NULL),
(90, 124, 31, 1, '200.00', '200.00', NULL, NULL, NULL, 0, 0, 'active', 0, NULL, '2025-01-10 15:35:37', '2025-01-10 15:35:37', NULL),
(91, 124, 32, 1, '100.00', '100.00', NULL, NULL, NULL, 0, 0, 'active', 0, NULL, '2025-01-10 15:35:37', '2025-01-10 15:35:37', NULL),
(92, 125, 33, 2, '250.00', '500.00', NULL, NULL, NULL, 0, 0, 'active', 0, NULL, '2025-01-10 15:42:35', '2025-01-10 15:42:35', NULL),
(93, 126, 32, 2, '100.00', '200.00', NULL, NULL, NULL, 0, 0, 'active', 0, NULL, '2025-01-10 15:43:24', '2025-01-10 15:43:24', NULL),
(94, 128, 33, 1, '250.00', '250.00', NULL, NULL, NULL, 0, 0, 'active', 0, NULL, '2025-01-10 16:04:23', '2025-01-10 16:04:23', NULL),
(95, 134, 32, 1, '100.00', '100.00', NULL, NULL, NULL, 0, 0, 'active', 0, NULL, '2025-01-10 16:26:04', '2025-01-10 16:26:04', NULL),
(96, 134, 35, 1, '150.00', '150.00', NULL, NULL, NULL, 0, 0, 'active', 0, NULL, '2025-01-10 16:26:04', '2025-01-10 16:26:04', NULL),
(97, 136, 34, 1, '300.00', '300.00', NULL, NULL, NULL, 0, 0, 'active', 0, NULL, '2025-01-10 16:28:29', '2025-01-10 16:28:29', NULL),
(98, 141, 2, 1, '15.00', '15.00', '2025-01-11 12:00:00', '2025-01-11 12:00:00', 1, 1, 0, 'returned', 0, NULL, '2025-01-11 05:13:06', '2025-01-11 13:21:11', NULL),
(99, 141, 4, 1, '20.00', '20.00', '2025-01-11 12:00:00', '2025-01-11 12:00:00', 1, 1, 0, 'returned', 0, NULL, '2025-01-11 05:13:06', '2025-01-11 13:21:11', NULL),
(100, 142, 1, 2, '10.00', '20.00', '2025-01-11 12:00:00', '2025-01-11 12:00:00', 1, 2, 0, 'returned', 0, NULL, '2025-01-11 05:16:12', '2025-01-11 11:53:06', NULL),
(101, 143, 31, 1, '200.00', '200.00', NULL, NULL, NULL, 0, 0, 'active', 0, NULL, '2025-01-11 05:43:04', '2025-01-11 05:43:04', NULL),
(102, 144, 32, 2, '100.00', '200.00', NULL, NULL, NULL, 0, 0, 'active', 0, NULL, '2025-01-11 05:43:47', '2025-01-11 05:43:47', NULL),
(103, 145, 20, 1, '5.00', '5.00', '2025-01-11 12:00:00', '2025-01-11 14:00:00', 1, 1, 0, 'returned', 0, NULL, '2025-01-11 05:46:39', '2025-01-11 15:11:10', NULL),
(104, 146, 4, 5, '20.00', '100.00', '2025-01-11 12:00:00', '2025-01-11 12:00:00', 1, 5, 0, 'returned', 0, NULL, '2025-01-11 06:13:36', '2025-01-11 14:20:29', NULL),
(105, 146, 3, 1, '15.00', '15.00', '2025-01-11 12:00:00', '2025-01-11 12:00:00', 1, 1, 0, 'returned', 0, NULL, '2025-01-11 06:13:36', '2025-01-11 14:20:29', NULL),
(106, 146, 22, 4, '7.00', '28.00', '2025-01-11 12:00:00', '2025-01-11 12:00:00', 1, 4, 0, 'returned', 0, NULL, '2025-01-11 06:13:36', '2025-01-11 14:20:29', NULL),
(107, 146, 25, 1, '10.00', '10.00', '2025-01-11 12:00:00', '2025-01-11 12:00:00', 1, 1, 0, 'returned', 0, NULL, '2025-01-11 06:13:36', '2025-01-11 14:20:29', NULL),
(108, 146, 26, 1, '10.00', '10.00', '2025-01-11 12:00:00', '2025-01-11 12:00:00', 1, 1, 0, 'returned', 0, NULL, '2025-01-11 06:13:36', '2025-01-11 14:20:29', NULL),
(109, 146, 23, 2, '5.00', '10.00', '2025-01-11 12:00:00', '2025-01-11 12:00:00', 1, 2, 0, 'returned', 0, NULL, '2025-01-11 06:13:36', '2025-01-11 14:20:29', NULL),
(110, 146, 24, 1, '5.00', '5.00', '2025-01-11 12:00:00', '2025-01-11 12:00:00', 1, 1, 0, 'returned', 0, NULL, '2025-01-11 06:13:36', '2025-01-11 14:20:29', NULL),
(111, 147, 23, 3, '5.00', '15.00', '2025-01-11 12:00:00', '2025-01-11 12:00:00', 1, 3, 0, 'returned', 0, NULL, '2025-01-11 06:18:45', '2025-01-11 11:26:31', NULL),
(112, 149, 33, 1, '250.00', '250.00', NULL, NULL, NULL, 0, 0, 'active', 0, NULL, '2025-01-11 06:30:39', '2025-01-11 06:30:39', NULL),
(113, 150, 19, 6, '10.00', '60.00', '2025-01-11 12:00:00', '2025-01-11 12:00:00', 1, 6, 0, 'returned', 0, NULL, '2025-01-11 06:42:46', '2025-01-11 14:04:24', NULL),
(114, 150, 24, 5, '5.00', '25.00', '2025-01-11 12:00:00', '2025-01-11 12:00:00', 1, 5, 0, 'returned', 0, NULL, '2025-01-11 06:42:46', '2025-01-11 14:04:24', NULL),
(115, 151, 1, 2, '10.00', '20.00', '2025-01-11 12:00:00', '2025-01-11 12:00:00', 1, 2, 0, 'returned', 0, NULL, '2025-01-11 06:43:49', '2025-01-11 12:05:45', NULL),
(116, 152, 8, 1, '5.00', '5.00', '2025-01-11 12:00:00', '2025-01-11 12:00:00', 1, 1, 0, 'returned', 0, NULL, '2025-01-11 07:14:07', '2025-01-11 16:10:15', NULL),
(117, 153, 1, 2, '10.00', '20.00', '2025-01-11 12:00:00', '2025-01-11 12:00:00', 1, 2, 0, 'returned', 0, NULL, '2025-01-11 07:15:16', '2025-01-11 12:47:19', NULL),
(118, 154, 5, 1, '30.00', '30.00', '2025-01-11 12:00:00', '2025-01-11 12:00:00', 1, 1, 0, 'returned', 0, NULL, '2025-01-11 09:22:59', '2025-01-11 14:35:55', NULL),
(119, 156, 32, 1, '100.00', '100.00', NULL, NULL, NULL, 0, 0, 'active', 0, NULL, '2025-01-11 13:23:59', '2025-01-11 13:23:59', NULL),
(120, 157, 32, 1, '100.00', '100.00', NULL, NULL, NULL, 0, 0, 'active', 0, NULL, '2025-01-11 13:47:21', '2025-01-11 13:47:21', NULL),
(121, 158, 1, 2, '10.00', '20.00', '2025-01-11 12:00:00', '2025-01-11 12:00:00', 1, 2, 0, 'returned', 0, NULL, '2025-01-11 14:46:31', '2025-01-11 14:56:36', NULL),
(122, 159, 1, 2, '10.00', '20.00', '2025-01-12 12:00:00', '2025-01-12 12:00:00', 1, 2, 0, 'returned', 0, NULL, '2025-01-11 14:57:41', '2025-01-12 16:14:57', NULL),
(123, 160, 7, 1, '10.00', '10.00', '2025-01-12 12:00:00', '2025-01-12 12:00:00', 1, 1, 0, 'returned', 0, NULL, '2025-01-11 15:22:41', '2025-01-12 13:10:54', NULL),
(124, 160, 8, 1, '5.00', '5.00', '2025-01-12 12:00:00', '2025-01-12 12:00:00', 1, 1, 0, 'returned', 0, NULL, '2025-01-11 15:22:41', '2025-01-12 13:10:54', NULL),
(125, 161, 8, 1, '5.00', '5.00', '2025-01-12 12:00:00', '2025-01-12 12:00:00', 1, 1, 0, 'returned', 0, NULL, '2025-01-11 16:11:14', '2025-01-12 15:00:02', NULL),
(126, 163, 3, 1, '15.00', '15.00', '2025-01-12 12:00:00', '2025-01-12 12:00:00', 1, 1, 0, 'returned', 0, NULL, '2025-01-12 05:38:42', '2025-01-12 12:33:50', NULL),
(127, 163, 1, 1, '10.00', '10.00', '2025-01-12 12:00:00', '2025-01-12 12:00:00', 1, 1, 0, 'returned', 0, NULL, '2025-01-12 05:38:42', '2025-01-12 12:33:50', NULL),
(128, 164, 1, 1, '10.00', '10.00', '2025-01-12 12:00:00', '2025-01-12 12:00:00', 1, 1, 0, 'returned', 0, NULL, '2025-01-12 05:40:14', '2025-01-12 11:25:30', NULL),
(129, 165, 3, 1, '15.00', '15.00', '2025-01-12 12:00:00', '2025-01-12 12:00:00', 1, 1, 0, 'returned', 0, NULL, '2025-01-12 05:44:51', '2025-01-12 13:06:52', NULL),
(132, 167, 1, 2, '10.00', '20.00', '2025-01-12 12:00:00', '2025-01-12 12:00:00', 1, 2, 0, 'returned', 0, NULL, '2025-01-12 05:55:45', '2025-01-12 11:36:50', NULL),
(133, 167, 22, 1, '7.00', '7.00', '2025-01-12 12:00:00', '2025-01-12 12:00:00', 1, 1, 0, 'returned', 0, NULL, '2025-01-12 05:55:45', '2025-01-12 11:36:50', NULL),
(134, 168, 4, 1, '20.00', '20.00', '2025-01-12 12:00:00', '2025-01-12 12:00:00', 1, 1, 0, 'returned', 0, NULL, '2025-01-12 05:58:59', '2025-01-12 11:54:52', NULL),
(135, 168, 7, 1, '10.00', '10.00', '2025-01-12 12:00:00', '2025-01-12 12:00:00', 1, 1, 0, 'returned', 0, NULL, '2025-01-12 05:58:59', '2025-01-12 11:54:52', NULL),
(136, 169, 3, 1, '15.00', '15.00', '2025-01-12 12:00:00', '2025-01-12 12:00:00', 1, 1, 0, 'returned', 0, NULL, '2025-01-12 06:02:39', '2025-01-12 16:39:53', NULL),
(137, 170, 2, 1, '15.00', '15.00', '2025-01-12 12:00:00', '2025-01-12 12:00:00', 1, 1, 0, 'returned', 0, NULL, '2025-01-12 06:03:28', '2025-01-12 16:39:26', NULL),
(138, 170, 1, 1, '10.00', '10.00', '2025-01-12 12:00:00', '2025-01-12 12:00:00', 1, 1, 0, 'returned', 0, NULL, '2025-01-12 06:03:28', '2025-01-12 16:39:26', NULL),
(139, 171, 1, 1, '10.00', '10.00', '2025-01-12 12:00:00', '2025-01-12 12:00:00', 1, 1, 0, 'returned', 0, NULL, '2025-01-12 06:04:44', '2025-01-12 11:37:09', NULL),
(140, 172, 1, 2, '10.00', '20.00', '2025-01-12 12:00:00', '2025-01-12 12:00:00', 1, 2, 0, 'returned', 0, NULL, '2025-01-12 06:16:08', '2025-01-12 12:45:03', NULL),
(141, 172, 22, 2, '7.00', '14.00', '2025-01-12 12:00:00', '2025-01-12 12:00:00', 1, 2, 0, 'returned', 0, NULL, '2025-01-12 06:16:08', '2025-01-12 12:45:03', NULL),
(142, 172, 23, 2, '5.00', '10.00', '2025-01-12 12:00:00', '2025-01-12 12:00:00', 1, 2, 0, 'returned', 0, NULL, '2025-01-12 06:16:08', '2025-01-12 12:45:03', NULL),
(145, 174, 22, 1, '7.00', '7.00', '2025-01-12 12:00:00', '2025-01-12 12:00:00', 1, 1, 0, 'returned', 0, NULL, '2025-01-12 06:21:10', '2025-01-12 07:13:00', NULL),
(146, 174, 14, 1, '50.00', '50.00', '2025-01-12 12:00:00', '2025-01-12 12:00:00', 1, 1, 0, 'returned', 0, NULL, '2025-01-12 06:21:10', '2025-01-12 07:13:00', NULL),
(147, 176, 1, 9, '10.00', '90.00', '2025-01-12 12:00:00', '2025-01-12 12:00:00', 1, 9, 0, 'returned', 0, NULL, '2025-01-12 07:40:50', '2025-01-12 14:30:08', NULL),
(148, 177, 3, 2, '15.00', '30.00', '2025-01-12 12:00:00', '2025-01-12 12:00:00', 1, 2, 0, 'returned', 0, NULL, '2025-01-12 08:15:36', '2025-01-12 12:39:34', NULL),
(149, 177, 22, 2, '7.00', '14.00', '2025-01-12 12:00:00', '2025-01-12 12:00:00', 1, 2, 0, 'returned', 0, NULL, '2025-01-12 08:15:36', '2025-01-12 12:39:34', NULL),
(150, 177, 23, 2, '5.00', '10.00', '2025-01-12 12:00:00', '2025-01-12 12:00:00', 1, 2, 0, 'returned', 0, NULL, '2025-01-12 08:15:36', '2025-01-12 12:39:34', NULL),
(151, 178, 1, 1, '10.00', '10.00', '2025-01-12 12:00:00', '2025-01-12 12:00:00', 1, 1, 0, 'returned', 0, NULL, '2025-01-12 08:43:00', '2025-01-12 12:58:28', NULL),
(152, 178, 22, 1, '7.00', '7.00', '2025-01-12 12:00:00', '2025-01-12 12:00:00', 1, 1, 0, 'returned', 0, NULL, '2025-01-12 08:43:00', '2025-01-12 12:58:28', NULL),
(153, 179, 19, 3, '10.00', '30.00', '2025-01-12 12:00:00', '2025-01-12 12:00:00', 1, 3, 0, 'returned', 0, NULL, '2025-01-12 09:50:18', '2025-01-12 14:32:38', NULL),
(154, 180, 32, 1, '100.00', '100.00', NULL, NULL, NULL, 0, 0, 'active', 0, NULL, '2025-01-12 14:57:07', '2025-01-12 14:57:07', NULL),
(155, 181, 17, 1, '20.00', '20.00', '2025-01-13 12:00:00', '2025-01-13 12:00:00', 1, 1, 0, 'returned', 0, NULL, '2025-01-13 05:29:23', '2025-01-13 12:28:59', NULL),
(156, 181, 16, 2, '15.00', '30.00', '2025-01-13 12:00:00', '2025-01-13 12:00:00', 1, 2, 0, 'returned', 0, NULL, '2025-01-13 05:29:23', '2025-01-13 12:28:59', NULL),
(157, 181, 23, 1, '5.00', '5.00', '2025-01-13 12:00:00', '2025-01-13 12:00:00', 1, 1, 0, 'returned', 0, NULL, '2025-01-13 05:29:23', '2025-01-13 12:28:59', NULL),
(158, 181, 24, 1, '5.00', '5.00', '2025-01-13 12:00:00', '2025-01-13 12:00:00', 1, 1, 0, 'returned', 0, NULL, '2025-01-13 05:29:23', '2025-01-13 12:28:59', NULL),
(159, 182, 4, 1, '20.00', '20.00', '2025-01-13 12:00:00', '2025-01-13 12:00:00', 1, 1, 0, 'returned', 0, NULL, '2025-01-13 05:30:07', '2025-01-13 10:42:54', NULL),
(160, 183, 3, 3, '15.00', '45.00', '2025-01-13 12:00:00', '2025-01-13 12:00:00', 1, 3, 0, 'returned', 0, NULL, '2025-01-13 05:40:07', '2025-01-13 13:18:13', NULL),
(161, 183, 22, 1, '7.00', '7.00', '2025-01-13 12:00:00', '2025-01-13 12:00:00', 1, 1, 0, 'returned', 0, NULL, '2025-01-13 05:40:07', '2025-01-13 13:18:13', NULL),
(162, 184, 4, 1, '20.00', '20.00', '2025-01-13 12:00:00', '2025-01-13 12:00:00', 1, 1, 0, 'returned', 0, NULL, '2025-01-13 06:06:21', '2025-01-13 13:51:18', NULL),
(163, 185, 3, 1, '15.00', '15.00', '2025-01-13 12:00:00', '2025-01-13 12:00:00', 1, 1, 0, 'returned', 0, NULL, '2025-01-13 06:32:59', '2025-01-13 12:01:18', NULL),
(164, 185, 22, 1, '7.00', '7.00', '2025-01-13 12:00:00', '2025-01-13 12:00:00', 1, 1, 0, 'returned', 0, NULL, '2025-01-13 06:32:59', '2025-01-13 12:01:18', NULL),
(165, 186, 3, 4, '15.00', '60.00', '2025-01-13 12:00:00', '2025-01-13 12:00:00', 1, 4, 0, 'returned', 0, NULL, '2025-01-13 06:46:08', '2025-01-13 12:39:34', NULL),
(166, 187, 2, 5, '15.00', '75.00', '2025-01-13 12:00:00', '2025-01-13 12:00:00', 1, 5, 0, 'returned', 0, NULL, '2025-01-13 09:20:24', '2025-01-13 14:09:34', NULL),
(167, 187, 23, 4, '5.00', '20.00', '2025-01-13 12:00:00', '2025-01-13 12:00:00', 1, 4, 0, 'returned', 0, NULL, '2025-01-13 09:20:24', '2025-01-13 14:09:34', NULL),
(168, 187, 25, 4, '10.00', '40.00', '2025-01-13 12:00:00', '2025-01-13 12:00:00', 1, 4, 0, 'returned', 0, NULL, '2025-01-13 09:20:24', '2025-01-13 14:09:34', NULL),
(169, 188, 4, 2, '20.00', '40.00', '2025-01-14 12:00:00', '2025-01-14 12:00:00', 1, 2, 0, 'returned', 0, NULL, '2025-01-14 05:21:29', '2025-01-14 14:38:58', NULL),
(170, 188, 22, 1, '7.00', '7.00', '2025-01-14 12:00:00', '2025-01-14 12:00:00', 1, 1, 0, 'returned', 0, NULL, '2025-01-14 05:21:29', '2025-01-14 14:38:58', NULL),
(171, 189, 5, 1, '30.00', '30.00', '2025-01-14 12:00:00', '2025-01-14 12:00:00', 1, 1, 0, 'returned', 0, NULL, '2025-01-14 05:25:51', '2025-01-14 13:52:27', NULL),
(172, 189, 23, 1, '5.00', '5.00', '2025-01-14 12:00:00', '2025-01-14 12:00:00', 1, 1, 0, 'returned', 0, NULL, '2025-01-14 05:25:51', '2025-01-14 13:52:27', NULL),
(173, 190, 22, 3, '7.00', '21.00', '2025-01-14 12:00:00', '2025-01-14 12:00:00', 1, 3, 0, 'returned', 0, NULL, '2025-01-14 05:31:28', '2025-01-14 14:13:38', NULL),
(174, 190, 23, 2, '5.00', '10.00', '2025-01-14 12:00:00', '2025-01-14 12:00:00', 1, 2, 0, 'returned', 0, NULL, '2025-01-14 05:31:28', '2025-01-14 14:13:38', NULL),
(175, 190, 2, 2, '15.00', '30.00', '2025-01-14 12:00:00', '2025-01-14 12:00:00', 1, 2, 0, 'returned', 0, NULL, '2025-01-14 05:31:28', '2025-01-14 14:13:38', NULL),
(176, 190, 4, 1, '20.00', '20.00', '2025-01-14 12:00:00', '2025-01-14 12:00:00', 1, 1, 0, 'returned', 0, NULL, '2025-01-14 05:31:28', '2025-01-14 14:13:38', NULL),
(177, 190, 17, 1, '20.00', '20.00', '2025-01-14 12:00:00', '2025-01-14 12:00:00', 1, 1, 0, 'returned', 0, NULL, '2025-01-14 05:31:28', '2025-01-14 14:13:38', NULL),
(178, 190, 7, 1, '10.00', '10.00', '2025-01-14 12:00:00', '2025-01-14 12:00:00', 1, 1, 0, 'returned', 0, NULL, '2025-01-14 05:31:28', '2025-01-14 14:13:38', NULL),
(179, 191, 3, 2, '15.00', '30.00', '2025-01-14 12:00:00', '2025-01-14 12:00:00', 1, 2, 0, 'returned', 0, NULL, '2025-01-14 05:34:46', '2025-01-22 10:02:39', NULL),
(180, 192, 4, 1, '20.00', '20.00', '2025-01-14 12:00:00', '2025-01-14 12:00:00', 1, 1, 0, 'returned', 0, NULL, '2025-01-14 05:42:18', '2025-01-14 12:43:20', NULL),
(181, 193, 3, 1, '15.00', '15.00', '2025-01-14 12:00:00', '2025-01-14 12:00:00', 1, 1, 0, 'returned', 0, NULL, '2025-01-14 05:43:35', '2025-01-14 12:17:55', NULL),
(182, 194, 22, 2, '7.00', '14.00', '2025-01-14 12:00:00', '2025-01-14 12:00:00', 1, 2, 0, 'returned', 0, NULL, '2025-01-14 05:45:34', '2025-01-14 12:21:52', NULL),
(183, 194, 3, 2, '15.00', '30.00', '2025-01-14 12:00:00', '2025-01-14 12:00:00', 1, 2, 0, 'returned', 0, NULL, '2025-01-14 05:45:34', '2025-01-14 12:21:52', NULL),
(184, 195, 17, 1, '20.00', '20.00', '2025-01-14 12:00:00', '2025-01-14 12:00:00', 1, 1, 0, 'returned', 0, NULL, '2025-01-14 05:50:46', '2025-01-14 12:23:10', NULL),
(185, 195, 23, 1, '5.00', '5.00', '2025-01-14 12:00:00', '2025-01-14 12:00:00', 1, 1, 0, 'returned', 0, NULL, '2025-01-14 05:50:46', '2025-01-14 12:23:10', NULL),
(186, 195, 24, 1, '5.00', '5.00', '2025-01-14 12:00:00', '2025-01-14 12:00:00', 1, 1, 0, 'returned', 0, NULL, '2025-01-14 05:50:46', '2025-01-14 12:23:10', NULL),
(187, 196, 5, 1, '30.00', '30.00', '2025-01-14 12:00:00', '2025-01-14 12:00:00', 1, 1, 0, 'returned', 0, NULL, '2025-01-14 06:50:13', '2025-01-14 14:18:20', NULL),
(188, 197, 4, 1, '20.00', '20.00', '2025-01-14 12:00:00', '2025-01-14 12:00:00', 1, 1, 0, 'returned', 0, NULL, '2025-01-14 07:00:50', '2025-01-14 11:52:57', NULL),
(189, 197, 22, 1, '7.00', '7.00', '2025-01-14 12:00:00', '2025-01-14 12:00:00', 1, 1, 0, 'returned', 0, NULL, '2025-01-14 07:00:50', '2025-01-14 11:52:57', NULL),
(190, 198, 3, 1, '15.00', '15.00', '2025-01-14 12:00:00', '2025-01-14 12:00:00', 1, 1, 0, 'returned', 0, NULL, '2025-01-14 07:03:30', '2025-01-14 11:52:23', NULL),
(191, 198, 4, 1, '20.00', '20.00', '2025-01-14 12:00:00', '2025-01-14 12:00:00', 1, 1, 0, 'returned', 0, NULL, '2025-01-14 07:03:30', '2025-01-14 11:52:23', NULL),
(192, 198, 23, 1, '5.00', '5.00', '2025-01-14 12:00:00', '2025-01-14 12:00:00', 1, 1, 0, 'returned', 0, NULL, '2025-01-14 07:03:30', '2025-01-14 11:52:23', NULL),
(193, 199, 24, 1, '5.00', '5.00', '2025-01-14 12:00:00', '2025-01-14 12:00:00', 1, 1, 0, 'returned', 0, NULL, '2025-01-14 09:31:16', '2025-01-14 15:06:41', NULL),
(194, 199, 25, 1, '10.00', '10.00', '2025-01-14 12:00:00', '2025-01-14 12:00:00', 1, 1, 0, 'returned', 0, NULL, '2025-01-14 09:31:16', '2025-01-14 15:06:41', NULL),
(195, 200, 4, 2, '20.00', '40.00', '2025-01-15 12:00:00', '2025-01-15 12:00:00', 1, 2, 0, 'returned', 0, NULL, '2025-01-15 05:52:57', '2025-01-15 16:03:14', NULL),
(196, 200, 22, 1, '7.00', '7.00', '2025-01-15 12:00:00', '2025-01-15 12:00:00', 1, 1, 0, 'returned', 0, NULL, '2025-01-15 05:52:57', '2025-01-15 16:03:14', NULL),
(197, 200, 25, 1, '10.00', '10.00', '2025-01-15 12:00:00', '2025-01-15 12:00:00', 1, 1, 0, 'returned', 0, NULL, '2025-01-15 05:52:57', '2025-01-15 16:03:15', NULL),
(198, 201, 4, 1, '20.00', '20.00', '2025-01-15 12:00:00', '2025-01-15 12:00:00', 1, 1, 0, 'returned', 0, NULL, '2025-01-15 06:03:08', '2025-01-15 12:51:43', NULL),
(199, 202, 4, 1, '20.00', '20.00', '2025-01-15 12:00:00', '2025-01-15 12:00:00', 1, 1, 0, 'returned', 0, NULL, '2025-01-15 06:42:33', '2025-01-15 11:57:29', NULL),
(200, 202, 7, 1, '10.00', '10.00', '2025-01-15 12:00:00', '2025-01-15 12:00:00', 1, 1, 0, 'returned', 0, NULL, '2025-01-15 06:42:33', '2025-01-15 11:57:29', NULL),
(201, 203, 3, 1, '15.00', '15.00', '2025-01-15 12:00:00', '2025-01-15 12:00:00', 1, 1, 0, 'returned', 0, NULL, '2025-01-15 06:44:12', '2025-01-15 12:24:21', NULL),
(202, 204, 9, 1, '10.00', '10.00', '2025-01-15 09:00:00', '2025-01-15 15:00:00', 1, 1, 0, 'returned', 0, NULL, '2025-01-15 06:57:14', '2025-01-15 12:12:41', NULL),
(203, 205, 1, 1, '10.00', '10.00', '2025-01-15 12:00:00', '2025-01-15 12:00:00', 1, 1, 0, 'returned', 0, NULL, '2025-01-15 07:37:55', '2025-01-15 13:51:21', NULL),
(204, 205, 2, 2, '15.00', '30.00', '2025-01-15 12:00:00', '2025-01-15 12:00:00', 1, 2, 0, 'returned', 0, NULL, '2025-01-15 07:37:55', '2025-01-15 13:51:21', NULL),
(205, 205, 22, 1, '7.00', '7.00', '2025-01-15 12:00:00', '2025-01-15 12:00:00', 1, 1, 0, 'returned', 0, NULL, '2025-01-15 07:37:55', '2025-01-15 13:51:21', NULL),
(206, 206, 16, 1, '15.00', '15.00', '2025-01-15 12:00:00', '2025-01-15 12:00:00', 1, 1, 0, 'returned', 0, NULL, '2025-01-15 07:49:36', '2025-01-15 14:08:44', NULL),
(207, 207, 19, 4, '10.00', '40.00', '2025-01-15 12:00:00', '2025-01-15 12:00:00', 1, 4, 0, 'returned', 0, NULL, '2025-01-15 08:30:39', '2025-01-15 15:04:12', NULL),
(208, 208, 4, 1, '20.00', '20.00', '2025-01-15 12:00:00', '2025-01-15 12:00:00', 1, 1, 0, 'returned', 0, NULL, '2025-01-15 08:41:19', '2025-01-15 13:49:02', NULL),
(209, 209, 14, 1, '50.00', '50.00', '2025-01-16 12:00:00', '2025-01-16 12:00:00', 1, 1, 0, 'returned', 0, NULL, '2025-01-16 05:40:50', '2025-01-16 10:29:22', NULL),
(210, 210, 17, 1, '20.00', '20.00', '2025-01-16 08:00:00', '2025-01-16 12:00:00', 1, 1, 0, 'returned', 0, NULL, '2025-01-16 05:52:43', '2025-01-16 10:35:57', NULL),
(211, 211, 22, 1, '7.00', '7.00', '2025-01-16 12:00:00', '2025-01-16 12:00:00', 1, 1, 0, 'returned', 0, NULL, '2025-01-16 06:36:17', '2025-01-16 12:33:58', NULL),
(212, 211, 17, 1, '20.00', '20.00', '2025-01-16 12:00:00', '2025-01-16 12:00:00', 1, 1, 0, 'returned', 0, NULL, '2025-01-16 06:36:17', '2025-01-16 12:33:58', NULL),
(213, 214, 24, 4, '5.00', '20.00', '2025-01-18 07:00:00', '2025-01-18 15:00:00', 1, 4, 0, 'returned', 0, NULL, '2025-01-18 05:18:56', '2025-01-18 11:30:55', NULL),
(214, 214, 4, 3, '20.00', '60.00', '2025-01-18 07:00:00', '2025-01-18 15:00:00', 1, 3, 0, 'returned', 0, NULL, '2025-01-18 05:18:56', '2025-01-18 11:30:55', NULL),
(215, 214, 7, 1, '10.00', '10.00', '2025-01-18 07:00:00', '2025-01-18 15:00:00', 1, 1, 0, 'returned', 0, NULL, '2025-01-18 05:18:56', '2025-01-18 11:30:55', NULL),
(216, 214, 25, 1, '10.00', '10.00', '2025-01-18 07:00:00', '2025-01-18 15:00:00', 1, 1, 0, 'returned', 0, NULL, '2025-01-18 05:18:56', '2025-01-18 11:30:55', NULL),
(217, 214, 23, 1, '5.00', '5.00', '2025-01-18 07:00:00', '2025-01-18 15:00:00', 1, 1, 0, 'returned', 0, NULL, '2025-01-18 05:18:56', '2025-01-18 11:30:55', NULL),
(218, 215, 12, 1, '35.00', '70.00', '2025-01-18 07:40:00', '2025-01-19 16:10:00', 2, 1, 0, 'returned', 0, NULL, '2025-01-18 05:29:45', '2025-01-22 10:02:21', NULL),
(219, 216, 22, 2, '7.00', '14.00', '2025-01-18 07:55:00', '2025-01-18 16:00:00', 1, 2, 0, 'returned', 0, NULL, '2025-01-18 05:34:31', '2025-01-18 12:03:12', NULL),
(220, 216, 3, 2, '15.00', '30.00', '2025-01-18 07:55:00', '2025-01-18 16:00:00', 1, 2, 0, 'returned', 0, NULL, '2025-01-18 05:34:31', '2025-01-18 12:03:12', NULL),
(221, 217, 19, 3, '10.00', '30.00', '2025-01-18 08:00:00', '2025-01-18 16:00:00', 1, 3, 0, 'returned', 0, NULL, '2025-01-18 06:12:30', '2025-01-18 13:07:15', NULL),
(222, 217, 24, 1, '5.00', '5.00', '2025-01-18 08:00:00', '2025-01-18 16:00:00', 1, 1, 0, 'returned', 0, NULL, '2025-01-18 06:12:30', '2025-01-18 13:07:15', NULL),
(223, 217, 23, 1, '5.00', '5.00', '2025-01-18 08:00:00', '2025-01-18 16:00:00', 1, 1, 0, 'returned', 0, NULL, '2025-01-18 06:12:30', '2025-01-18 13:07:15', NULL),
(224, 218, 3, 1, '15.00', '15.00', '2025-01-18 12:00:00', '2025-01-18 12:00:00', 1, 1, 0, 'returned', 0, NULL, '2025-01-18 07:44:14', '2025-01-18 14:26:58', NULL),
(225, 218, 23, 1, '5.00', '5.00', '2025-01-18 12:00:00', '2025-01-18 12:00:00', 1, 1, 0, 'returned', 0, NULL, '2025-01-18 07:44:14', '2025-01-18 14:26:58', NULL),
(226, 218, 22, 1, '7.00', '7.00', '2025-01-18 12:00:00', '2025-01-18 12:00:00', 1, 1, 0, 'returned', 0, NULL, '2025-01-18 07:44:14', '2025-01-18 14:26:58', NULL),
(227, 219, 3, 4, '15.00', '60.00', '2025-01-18 11:00:00', '2025-01-18 16:10:00', 1, 4, 0, 'returned', 0, NULL, '2025-01-18 09:10:30', '2025-01-22 10:01:59', NULL),
(228, 219, 25, 3, '10.00', '30.00', '2025-01-18 11:00:00', '2025-01-18 16:10:00', 1, 3, 0, 'returned', 0, NULL, '2025-01-18 09:10:30', '2025-01-22 10:01:59', NULL),
(229, 219, 24, 2, '5.00', '10.00', '2025-01-18 11:00:00', '2025-01-18 16:10:00', 1, 2, 0, 'returned', 0, NULL, '2025-01-18 09:10:30', '2025-01-22 10:01:59', NULL),
(230, 219, 23, 4, '5.00', '20.00', '2025-01-18 11:00:00', '2025-01-18 16:10:00', 1, 4, 0, 'returned', 0, NULL, '2025-01-18 09:10:30', '2025-01-22 10:01:59', NULL),
(231, 219, 26, 2, '10.00', '20.00', '2025-01-18 11:00:00', '2025-01-18 16:10:00', 1, 2, 0, 'returned', 0, NULL, '2025-01-18 09:10:30', '2025-01-22 10:01:59', NULL),
(232, 220, 10, 1, '15.00', '15.00', '2025-01-19 12:00:00', '2025-01-19 12:00:00', 1, 1, 0, 'returned', 0, NULL, '2025-01-19 06:02:22', '2025-01-19 13:02:10', NULL),
(233, 221, 1, 2, '10.00', '20.00', '2025-01-19 12:00:00', '2025-01-19 12:00:00', 1, 2, 0, 'returned', 0, NULL, '2025-01-19 06:39:30', '2025-01-19 10:40:43', NULL),
(234, 221, 23, 2, '5.00', '10.00', '2025-01-19 12:00:00', '2025-01-19 12:00:00', 1, 2, 0, 'returned', 0, NULL, '2025-01-19 06:39:30', '2025-01-19 10:40:43', NULL),
(235, 221, 22, 2, '7.00', '14.00', '2025-01-19 12:00:00', '2025-01-19 12:00:00', 1, 2, 0, 'returned', 0, NULL, '2025-01-19 06:39:30', '2025-01-19 10:40:43', NULL),
(236, 222, 3, 1, '15.00', '15.00', '2025-01-19 12:00:00', '2025-01-19 12:00:00', 1, 1, 0, 'returned', 0, NULL, '2025-01-19 06:58:54', '2025-01-19 13:07:08', NULL),
(237, 223, 1, 1, '10.00', '10.00', '2025-01-19 12:00:00', '2025-01-19 12:00:00', 1, 1, 0, 'returned', 0, NULL, '2025-01-19 07:37:04', '2025-01-19 13:38:53', NULL),
(238, 223, 3, 1, '15.00', '15.00', '2025-01-19 12:00:00', '2025-01-19 12:00:00', 1, 1, 0, 'returned', 0, NULL, '2025-01-19 07:37:04', '2025-01-19 13:38:53', NULL),
(239, 223, 23, 1, '5.00', '5.00', '2025-01-19 12:00:00', '2025-01-19 12:00:00', 1, 1, 0, 'returned', 0, NULL, '2025-01-19 07:37:04', '2025-01-19 13:38:53', NULL),
(240, 224, 2, 1, '15.00', '15.00', '2025-01-19 12:00:00', '2025-01-19 12:00:00', 1, 1, 0, 'returned', 0, NULL, '2025-01-19 08:52:23', '2025-01-19 14:45:24', NULL),
(241, 224, 3, 1, '15.00', '15.00', '2025-01-19 12:00:00', '2025-01-19 12:00:00', 1, 1, 0, 'returned', 0, NULL, '2025-01-19 08:52:23', '2025-01-19 14:45:25', NULL),
(242, 224, 4, 1, '20.00', '20.00', '2025-01-19 12:00:00', '2025-01-19 12:00:00', 1, 1, 0, 'returned', 0, NULL, '2025-01-19 08:52:23', '2025-01-19 14:45:25', NULL),
(243, 224, 16, 1, '15.00', '15.00', '2025-01-19 12:00:00', '2025-01-19 12:00:00', 1, 1, 0, 'returned', 0, NULL, '2025-01-19 08:52:23', '2025-01-19 14:45:25', NULL),
(244, 224, 25, 3, '10.00', '30.00', '2025-01-19 12:00:00', '2025-01-19 12:00:00', 1, 3, 0, 'returned', 0, NULL, '2025-01-19 08:52:23', '2025-01-19 14:45:25', NULL),
(245, 224, 26, 3, '10.00', '30.00', '2025-01-19 12:00:00', '2025-01-19 12:00:00', 1, 3, 0, 'returned', 0, NULL, '2025-01-19 08:52:23', '2025-01-19 14:45:25', NULL),
(246, 224, 24, 2, '5.00', '10.00', '2025-01-19 12:00:00', '2025-01-19 12:00:00', 1, 2, 0, 'returned', 0, NULL, '2025-01-19 08:52:23', '2025-01-19 14:45:25', NULL),
(247, 225, 17, 2, '20.00', '40.00', '2025-01-20 12:00:00', '2025-01-20 12:00:00', 1, 2, 0, 'returned', 0, NULL, '2025-01-20 05:15:32', '2025-01-20 13:07:09', NULL),
(248, 226, 5, 1, '30.00', '30.00', '2025-01-20 12:00:00', '2025-01-20 12:00:00', 1, 1, 0, 'returned', 0, NULL, '2025-01-20 05:17:27', '2025-01-20 12:02:13', NULL),
(249, 227, 1, 1, '10.00', '10.00', '2025-01-20 12:00:00', '2025-01-20 12:00:00', 1, 1, 0, 'returned', 0, NULL, '2025-01-20 06:50:46', '2025-01-20 10:57:57', NULL),
(250, 227, 2, 1, '15.00', '15.00', '2025-01-20 12:00:00', '2025-01-20 12:00:00', 1, 1, 0, 'returned', 0, NULL, '2025-01-20 06:50:46', '2025-01-20 10:57:57', NULL),
(251, 227, 3, 1, '15.00', '15.00', '2025-01-20 12:00:00', '2025-01-20 12:00:00', 1, 1, 0, 'returned', 0, NULL, '2025-01-20 06:50:46', '2025-01-20 10:57:57', NULL),
(252, 227, 4, 1, '20.00', '20.00', '2025-01-20 12:00:00', '2025-01-20 12:00:00', 1, 1, 0, 'returned', 0, NULL, '2025-01-20 06:50:46', '2025-01-20 10:57:57', NULL),
(253, 227, 23, 2, '5.00', '10.00', '2025-01-20 12:00:00', '2025-01-20 12:00:00', 1, 2, 0, 'returned', 0, NULL, '2025-01-20 06:50:46', '2025-01-20 10:57:57', NULL),
(254, 228, 3, 1, '15.00', '15.00', '2025-01-21 08:00:00', '2025-01-21 17:00:00', 1, 1, 0, 'returned', 0, NULL, '2025-01-21 06:06:11', '2025-01-21 12:20:01', NULL),
(255, 229, 3, 2, '15.00', '30.00', '2025-01-21 08:00:00', '2025-01-21 15:00:00', 1, 2, 0, 'returned', 0, NULL, '2025-01-21 06:37:04', '2025-01-21 13:46:47', NULL),
(256, 230, 1, 1, '10.00', '10.00', '2025-01-21 12:00:00', '2025-01-21 12:00:00', 1, 1, 0, 'returned', 0, NULL, '2025-01-21 07:54:30', '2025-01-21 10:49:56', NULL),
(257, 230, 22, 1, '7.00', '7.00', '2025-01-21 12:00:00', '2025-01-21 12:00:00', 1, 1, 0, 'returned', 0, NULL, '2025-01-21 07:54:30', '2025-01-21 10:49:56', NULL);

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
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2024_09_07_102225_create_customers_table', 1),
(6, '2024_09_07_102228_create_categories_table', 1),
(7, '2024_09_07_102229_create_products_table', 1),
(8, '2024_09_07_102233_create_invoices_table', 1),
(9, '2024_09_07_102237_create_invoice_items_table', 1),
(10, '2024_11_26_214818_create_additional_items_table', 1),
(11, '2024_11_26_214819_create_custom_items_table', 1),
(12, '2024_11_26_214820_create_return_details_table', 1);

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
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price` decimal(8,2) NOT NULL,
  `type` enum('standard','fixed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'standard',
  `category_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `type`, `category_id`, `created_at`, `updated_at`) VALUES
(1, 'Ski Set Kids', NULL, '10.00', 'standard', 1, '2025-01-02 12:54:09', '2025-01-02 12:54:09'),
(2, 'Ski Set Junior', NULL, '15.00', 'standard', 1, '2025-01-02 12:54:23', '2025-01-02 12:54:23'),
(3, 'Ski Set Adults Reg', NULL, '15.00', 'standard', 1, '2025-01-02 12:54:37', '2025-01-02 12:54:37'),
(4, 'Ski Set Adults Adv', NULL, '20.00', 'standard', 1, '2025-01-02 12:54:51', '2025-01-02 12:54:51'),
(5, 'Ski Set Adults Prem', NULL, '30.00', 'standard', 1, '2025-01-02 12:55:04', '2025-01-02 12:55:04'),
(6, 'Ski Set Adults Expert', NULL, '40.00', 'standard', 1, '2025-01-02 12:55:18', '2025-01-02 12:55:18'),
(7, 'ski Boots', NULL, '10.00', 'standard', 1, '2025-01-02 12:55:36', '2025-01-02 12:55:36'),
(8, 'Batton', NULL, '5.00', 'standard', 1, '2025-01-02 12:55:52', '2025-01-02 12:55:52'),
(9, 'Ski Regular', NULL, '10.00', 'standard', 1, '2025-01-02 12:56:01', '2025-01-02 12:56:01'),
(10, 'Ski Advanced', NULL, '15.00', 'standard', 1, '2025-01-02 12:56:17', '2025-01-02 12:56:17'),
(11, 'Ski Premium', NULL, '25.00', 'standard', 1, '2025-01-02 12:56:27', '2025-01-02 12:56:27'),
(12, 'Ski Expert', NULL, '35.00', 'standard', 1, '2025-01-02 12:56:37', '2025-01-02 12:56:37'),
(13, 'Ski Touring', NULL, '40.00', 'standard', 1, '2025-01-02 12:56:48', '2025-01-02 12:56:48'),
(14, 'Ski Touring + Boots', NULL, '50.00', 'standard', 1, '2025-01-02 12:57:03', '2025-01-02 12:57:03'),
(15, 'Snowboard Boots', NULL, '10.00', 'standard', 1, '2025-01-02 12:57:18', '2025-01-02 12:57:18'),
(16, 'Snowboard Set Reg', NULL, '15.00', 'standard', 1, '2025-01-02 12:57:40', '2025-01-02 12:57:40'),
(17, 'Snowboard Set Prem', NULL, '20.00', 'standard', 1, '2025-01-02 12:57:54', '2025-01-02 12:57:54'),
(18, 'Snowboard Set Prem New', NULL, '30.00', 'standard', 1, '2025-01-02 12:58:17', '2025-01-02 12:58:17'),
(19, 'Hiking Racket', NULL, '10.00', 'standard', 1, '2025-01-02 12:58:32', '2025-01-02 12:58:32'),
(20, 'Sled Small', NULL, '5.00', 'standard', 1, '2025-01-02 12:58:40', '2025-01-02 12:58:40'),
(21, 'Sled Big', NULL, '10.00', 'standard', 1, '2025-01-02 12:58:48', '2025-01-02 12:58:48'),
(22, 'Casque', NULL, '7.00', 'standard', 1, '2025-01-02 12:58:56', '2025-01-02 12:58:56'),
(23, 'Masque', NULL, '5.00', 'standard', 1, '2025-01-02 12:59:07', '2025-01-02 12:59:07'),
(24, 'Gloves', NULL, '5.00', 'standard', 1, '2025-01-02 12:59:18', '2025-01-02 12:59:18'),
(25, 'Pants', NULL, '10.00', 'standard', 1, '2025-01-02 12:59:25', '2025-01-02 12:59:25'),
(26, 'Jacket', NULL, '10.00', 'standard', 1, '2025-01-02 12:59:32', '2025-01-02 12:59:32'),
(27, 'OverHall', NULL, '15.00', 'standard', 1, '2025-01-02 12:59:41', '2025-01-02 12:59:41'),
(28, 'Apres Ski', NULL, '7.00', 'standard', 1, '2025-01-02 12:59:50', '2025-01-02 12:59:50'),
(29, 'Ski De Fond', NULL, '10.00', 'standard', 1, '2025-01-02 13:00:00', '2025-01-02 13:00:00'),
(30, 'Ski De Fond + Care', NULL, '15.00', 'standard', 1, '2025-01-02 13:00:16', '2025-01-02 13:00:16'),
(31, 'Ski Set Kids New', '80 --> 120', '200.00', 'standard', 2, '2025-01-02 13:16:20', '2025-01-02 13:16:20'),
(32, 'Ski Set Kids Used', NULL, '100.00', 'standard', 2, '2025-01-02 13:16:38', '2025-01-02 13:16:38'),
(33, 'Ski Set Junior New', '130 +', '250.00', 'standard', 2, '2025-01-02 13:16:54', '2025-01-02 13:16:54'),
(34, 'Ski Set Adult New', NULL, '300.00', 'standard', 2, '2025-01-02 13:17:07', '2025-01-02 13:17:07'),
(35, 'Ski Set Adult Used', NULL, '150.00', 'standard', 2, '2025-01-02 13:17:25', '2025-01-02 13:17:25'),
(36, 'Ski Boots Junior/Adult New', NULL, '125.00', 'standard', 2, '2025-01-02 13:17:49', '2025-01-02 13:18:02'),
(37, 'Ski Boots Junior/Adults Used', NULL, '75.00', 'standard', 2, '2025-01-02 13:18:18', '2025-01-02 13:18:18'),
(38, 'Ski Boots Kids New', NULL, '100.00', 'standard', 2, '2025-01-02 13:18:29', '2025-01-02 13:18:29'),
(39, 'Ski Boots Kids Used', NULL, '50.00', 'standard', 2, '2025-01-02 13:18:41', '2025-01-02 13:18:41'),
(40, 'Ski Junior New', NULL, '150.00', 'standard', 2, '2025-01-02 13:18:50', '2025-01-02 13:18:50'),
(41, 'Ski Kids New', NULL, '125.00', 'standard', 2, '2025-01-02 13:19:00', '2025-01-02 13:19:00'),
(42, 'Ski Kids Used', NULL, '75.00', 'standard', 2, '2025-01-02 13:19:16', '2025-01-02 13:19:16'),
(43, 'Ski Adults New', NULL, '200.00', 'standard', 2, '2025-01-02 13:19:25', '2025-01-02 13:19:25'),
(44, 'Ski Adults Used', NULL, '100.00', 'standard', 2, '2025-01-02 13:19:41', '2025-01-02 13:19:41'),
(45, 'Snowboard Set Kids New', NULL, '300.00', 'standard', 2, '2025-01-02 13:20:04', '2025-01-02 13:20:04'),
(46, 'Snowboard Set Kids Used', NULL, '200.00', 'standard', 2, '2025-01-02 13:20:30', '2025-01-02 13:20:30'),
(47, 'Snowboard Set Adults New', NULL, '400.00', 'standard', 2, '2025-01-02 13:20:45', '2025-01-02 13:20:45'),
(48, 'Snowboard Set Adults Used', NULL, '250.00', 'standard', 2, '2025-01-02 13:21:07', '2025-01-02 13:21:07'),
(49, 'Snowboard Boots Kids New', NULL, '100.00', 'standard', 2, '2025-01-02 13:21:25', '2025-01-02 13:21:25'),
(50, 'Snowboard Boots Kids Used', NULL, '50.00', 'standard', 2, '2025-01-02 13:21:47', '2025-01-02 13:21:47'),
(51, 'Snowboard Boots Adults New', NULL, '150.00', 'standard', 2, '2025-01-02 13:22:07', '2025-01-02 13:22:07'),
(52, 'Snowboard Boots Adults Used', NULL, '75.00', 'standard', 2, '2025-01-02 13:22:15', '2025-01-02 13:22:15');

-- --------------------------------------------------------

--
-- Table structure for table `return_details`
--

CREATE TABLE `return_details` (
  `id` bigint UNSIGNED NOT NULL,
  `invoice_id` bigint UNSIGNED NOT NULL,
  `invoice_item_id` bigint UNSIGNED DEFAULT NULL,
  `product_id` bigint UNSIGNED DEFAULT NULL,
  `additional_item_id` bigint UNSIGNED DEFAULT NULL,
  `custom_item_id` bigint UNSIGNED DEFAULT NULL,
  `returned_quantity` int NOT NULL,
  `days_used` int NOT NULL,
  `cost` decimal(10,2) NOT NULL,
  `refund` decimal(10,2) NOT NULL DEFAULT '0.00',
  `return_date` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `return_details`
--

INSERT INTO `return_details` (`id`, `invoice_id`, `invoice_item_id`, `product_id`, `additional_item_id`, `custom_item_id`, `returned_quantity`, `days_used`, `cost`, `refund`, `return_date`, `created_at`, `updated_at`) VALUES
(18, 147, 111, 23, NULL, NULL, 3, 1, '0.00', '0.00', '2025-01-11 12:00:00', '2025-01-11 11:26:31', '2025-01-11 11:26:31'),
(19, 148, NULL, NULL, NULL, 96, 1, 1, '0.00', '0.00', '2025-01-11 13:47:30', '2025-01-11 11:47:30', '2025-01-11 11:47:30'),
(20, 142, 100, 1, NULL, NULL, 2, 1, '0.00', '0.00', '2025-01-11 12:00:00', '2025-01-11 11:53:06', '2025-01-11 11:53:06'),
(21, 151, 115, 1, NULL, NULL, 2, 1, '0.00', '0.00', '2025-01-11 12:00:00', '2025-01-11 12:05:45', '2025-01-11 12:05:45'),
(22, 153, 117, 1, NULL, NULL, 2, 1, '0.00', '0.00', '2025-01-11 12:00:00', '2025-01-11 12:47:19', '2025-01-11 12:47:19'),
(23, 141, 98, 2, NULL, NULL, 1, 1, '0.00', '0.00', '2025-01-11 12:00:00', '2025-01-11 13:21:11', '2025-01-11 13:21:11'),
(24, 141, 99, 4, NULL, NULL, 1, 1, '0.00', '0.00', '2025-01-11 12:00:00', '2025-01-11 13:21:11', '2025-01-11 13:21:11'),
(25, 150, 113, 19, NULL, NULL, 6, 1, '0.00', '0.00', '2025-01-11 12:00:00', '2025-01-11 14:04:24', '2025-01-11 14:04:24'),
(26, 150, 114, 24, NULL, NULL, 5, 1, '0.00', '0.00', '2025-01-11 12:00:00', '2025-01-11 14:04:24', '2025-01-11 14:04:24'),
(27, 146, 104, 4, NULL, NULL, 5, 1, '0.00', '0.00', '2025-01-11 12:00:00', '2025-01-11 14:20:29', '2025-01-11 14:20:29'),
(28, 146, 105, 3, NULL, NULL, 1, 1, '0.00', '0.00', '2025-01-11 12:00:00', '2025-01-11 14:20:29', '2025-01-11 14:20:29'),
(29, 146, 106, 22, NULL, NULL, 4, 1, '0.00', '0.00', '2025-01-11 12:00:00', '2025-01-11 14:20:29', '2025-01-11 14:20:29'),
(30, 146, 107, 25, NULL, NULL, 1, 1, '0.00', '0.00', '2025-01-11 12:00:00', '2025-01-11 14:20:29', '2025-01-11 14:20:29'),
(31, 146, 108, 26, NULL, NULL, 1, 1, '0.00', '0.00', '2025-01-11 12:00:00', '2025-01-11 14:20:29', '2025-01-11 14:20:29'),
(32, 146, 109, 23, NULL, NULL, 2, 1, '0.00', '0.00', '2025-01-11 12:00:00', '2025-01-11 14:20:29', '2025-01-11 14:20:29'),
(33, 146, 110, 24, NULL, NULL, 1, 1, '0.00', '0.00', '2025-01-11 12:00:00', '2025-01-11 14:20:29', '2025-01-11 14:20:29'),
(34, 146, NULL, 24, 6, NULL, 1, 1, '0.00', '0.00', '2025-01-11 12:00:00', '2025-01-11 14:20:29', '2025-01-11 14:20:29'),
(35, 146, NULL, 23, 7, NULL, 1, 1, '0.00', '0.00', '2025-01-11 12:00:00', '2025-01-11 14:20:29', '2025-01-11 14:20:29'),
(36, 154, 118, 5, NULL, NULL, 1, 1, '0.00', '0.00', '2025-01-11 12:00:00', '2025-01-11 14:35:55', '2025-01-11 14:35:55'),
(37, 158, 121, 1, NULL, NULL, 2, 1, '0.00', '0.00', '2025-01-11 12:00:00', '2025-01-11 14:56:36', '2025-01-11 14:56:36'),
(38, 145, 103, 20, NULL, NULL, 1, 1, '0.00', '0.00', '2025-01-11 12:00:00', '2025-01-11 15:11:10', '2025-01-11 15:11:10'),
(39, 152, 116, 8, NULL, NULL, 1, 1, '0.00', '0.00', '2025-01-11 12:00:00', '2025-01-11 16:10:15', '2025-01-11 16:10:15'),
(42, 174, 145, 22, NULL, NULL, 1, 1, '0.00', '0.00', '2025-01-12 12:00:00', '2025-01-12 07:13:00', '2025-01-12 07:13:00'),
(43, 174, 146, 14, NULL, NULL, 1, 1, '0.00', '0.00', '2025-01-12 12:00:00', '2025-01-12 07:13:00', '2025-01-12 07:13:00'),
(44, 164, 128, 1, NULL, NULL, 1, 1, '0.00', '0.00', '2025-01-12 12:00:00', '2025-01-12 11:25:30', '2025-01-12 11:25:30'),
(45, 167, 132, 1, NULL, NULL, 2, 1, '0.00', '0.00', '2025-01-12 12:00:00', '2025-01-12 11:36:50', '2025-01-12 11:36:50'),
(46, 167, 133, 22, NULL, NULL, 1, 1, '0.00', '0.00', '2025-01-12 12:00:00', '2025-01-12 11:36:50', '2025-01-12 11:36:50'),
(47, 167, NULL, 23, 8, NULL, 1, 1, '0.00', '0.00', '2025-01-12 12:00:00', '2025-01-12 11:36:50', '2025-01-12 11:36:50'),
(48, 171, 139, 1, NULL, NULL, 1, 1, '0.00', '0.00', '2025-01-12 12:00:00', '2025-01-12 11:37:09', '2025-01-12 11:37:09'),
(49, 168, 134, 4, NULL, NULL, 1, 1, '0.00', '0.00', '2025-01-12 12:00:00', '2025-01-12 11:54:52', '2025-01-12 11:54:52'),
(50, 168, 135, 7, NULL, NULL, 1, 1, '0.00', '0.00', '2025-01-12 12:00:00', '2025-01-12 11:54:52', '2025-01-12 11:54:52'),
(51, 163, 126, 3, NULL, NULL, 1, 1, '0.00', '0.00', '2025-01-12 12:00:00', '2025-01-12 12:33:50', '2025-01-12 12:33:50'),
(52, 163, 127, 1, NULL, NULL, 1, 1, '0.00', '0.00', '2025-01-12 12:00:00', '2025-01-12 12:33:50', '2025-01-12 12:33:50'),
(53, 177, 148, 3, NULL, NULL, 2, 1, '0.00', '0.00', '2025-01-12 12:00:00', '2025-01-12 12:39:34', '2025-01-12 12:39:34'),
(54, 177, 149, 22, NULL, NULL, 2, 1, '0.00', '0.00', '2025-01-12 12:00:00', '2025-01-12 12:39:34', '2025-01-12 12:39:34'),
(55, 177, 150, 23, NULL, NULL, 2, 1, '0.00', '0.00', '2025-01-12 12:00:00', '2025-01-12 12:39:34', '2025-01-12 12:39:34'),
(56, 172, 140, 1, NULL, NULL, 2, 1, '0.00', '0.00', '2025-01-12 12:00:00', '2025-01-12 12:45:03', '2025-01-12 12:45:03'),
(57, 172, 141, 22, NULL, NULL, 2, 1, '0.00', '0.00', '2025-01-12 12:00:00', '2025-01-12 12:45:03', '2025-01-12 12:45:03'),
(58, 172, 142, 23, NULL, NULL, 2, 1, '0.00', '0.00', '2025-01-12 12:00:00', '2025-01-12 12:45:03', '2025-01-12 12:45:03'),
(59, 178, 151, 1, NULL, NULL, 1, 1, '0.00', '0.00', '2025-01-12 15:00:00', '2025-01-12 12:58:28', '2025-01-12 12:58:28'),
(60, 178, 152, 22, NULL, NULL, 1, 1, '0.00', '0.00', '2025-01-12 15:00:00', '2025-01-12 12:58:28', '2025-01-12 12:58:28'),
(61, 165, 129, 3, NULL, NULL, 1, 1, '0.00', '0.00', '2025-01-12 15:00:00', '2025-01-12 13:06:52', '2025-01-12 13:06:52'),
(62, 160, 123, 7, NULL, NULL, 1, 1, '0.00', '0.00', '2025-01-12 15:00:00', '2025-01-12 13:10:54', '2025-01-12 13:10:54'),
(63, 160, 124, 8, NULL, NULL, 1, 1, '0.00', '0.00', '2025-01-12 15:00:00', '2025-01-12 13:10:54', '2025-01-12 13:10:54'),
(64, 176, 147, 1, NULL, NULL, 9, 1, '0.00', '0.00', '2025-01-12 12:00:00', '2025-01-12 14:30:08', '2025-01-12 14:30:08'),
(65, 179, 153, 19, NULL, NULL, 3, 1, '0.00', '0.00', '2025-01-12 12:00:00', '2025-01-12 14:32:38', '2025-01-12 14:32:38'),
(66, 161, 125, 8, NULL, NULL, 1, 1, '0.00', '0.00', '2025-01-12 12:00:00', '2025-01-12 15:00:02', '2025-01-12 15:00:02'),
(67, 159, 122, 1, NULL, NULL, 2, 1, '0.00', '0.00', '2025-01-12 12:00:00', '2025-01-12 16:14:57', '2025-01-12 16:14:57'),
(68, 170, 137, 2, NULL, NULL, 1, 1, '0.00', '0.00', '2025-01-12 12:00:00', '2025-01-12 16:39:26', '2025-01-12 16:39:26'),
(69, 170, 138, 1, NULL, NULL, 1, 1, '0.00', '0.00', '2025-01-12 12:00:00', '2025-01-12 16:39:26', '2025-01-12 16:39:26'),
(70, 169, 136, 3, NULL, NULL, 1, 1, '0.00', '0.00', '2025-01-12 12:00:00', '2025-01-12 16:39:53', '2025-01-12 16:39:53'),
(71, 182, 159, 4, NULL, NULL, 1, 1, '0.00', '0.00', '2025-01-13 12:00:00', '2025-01-13 10:42:54', '2025-01-13 10:42:54'),
(72, 185, 163, 3, NULL, NULL, 1, 1, '0.00', '0.00', '2025-01-13 14:00:00', '2025-01-13 12:01:18', '2025-01-13 12:01:18'),
(73, 185, 164, 22, NULL, NULL, 1, 1, '0.00', '0.00', '2025-01-13 14:00:00', '2025-01-13 12:01:18', '2025-01-13 12:01:18'),
(74, 181, 155, 17, NULL, NULL, 1, 1, '0.00', '0.00', '2025-01-13 12:00:00', '2025-01-13 12:28:59', '2025-01-13 12:28:59'),
(75, 181, 156, 16, NULL, NULL, 2, 1, '0.00', '0.00', '2025-01-13 12:00:00', '2025-01-13 12:28:59', '2025-01-13 12:28:59'),
(76, 181, 157, 23, NULL, NULL, 1, 1, '0.00', '0.00', '2025-01-13 12:00:00', '2025-01-13 12:28:59', '2025-01-13 12:28:59'),
(77, 181, 158, 24, NULL, NULL, 1, 1, '0.00', '0.00', '2025-01-13 12:00:00', '2025-01-13 12:28:59', '2025-01-13 12:28:59'),
(78, 186, 165, 3, NULL, NULL, 4, 1, '0.00', '0.00', '2025-01-13 12:00:00', '2025-01-13 12:39:34', '2025-01-13 12:39:34'),
(79, 183, 160, 3, NULL, NULL, 3, 1, '0.00', '0.00', '2025-01-13 12:00:00', '2025-01-13 13:18:13', '2025-01-13 13:18:13'),
(80, 183, 161, 22, NULL, NULL, 1, 1, '0.00', '0.00', '2025-01-13 12:00:00', '2025-01-13 13:18:13', '2025-01-13 13:18:13'),
(81, 184, 162, 4, NULL, NULL, 1, 1, '0.00', '0.00', '2025-01-13 12:00:00', '2025-01-13 13:51:18', '2025-01-13 13:51:18'),
(82, 187, 166, 2, NULL, NULL, 5, 1, '0.00', '0.00', '2025-01-13 12:00:00', '2025-01-13 14:09:34', '2025-01-13 14:09:34'),
(83, 187, 167, 23, NULL, NULL, 4, 1, '0.00', '0.00', '2025-01-13 12:00:00', '2025-01-13 14:09:34', '2025-01-13 14:09:34'),
(84, 187, 168, 25, NULL, NULL, 4, 1, '0.00', '0.00', '2025-01-13 12:00:00', '2025-01-13 14:09:34', '2025-01-13 14:09:34'),
(85, 198, 190, 3, NULL, NULL, 1, 1, '0.00', '0.00', '2025-01-14 12:00:00', '2025-01-14 11:52:23', '2025-01-14 11:52:23'),
(86, 198, 191, 4, NULL, NULL, 1, 1, '0.00', '0.00', '2025-01-14 12:00:00', '2025-01-14 11:52:23', '2025-01-14 11:52:23'),
(87, 198, 192, 23, NULL, NULL, 1, 1, '0.00', '0.00', '2025-01-14 12:00:00', '2025-01-14 11:52:23', '2025-01-14 11:52:23'),
(88, 197, 188, 4, NULL, NULL, 1, 1, '0.00', '0.00', '2025-01-14 12:00:00', '2025-01-14 11:52:57', '2025-01-14 11:52:57'),
(89, 197, 189, 22, NULL, NULL, 1, 1, '0.00', '0.00', '2025-01-14 12:00:00', '2025-01-14 11:52:57', '2025-01-14 11:52:57'),
(90, 193, 181, 3, NULL, NULL, 1, 1, '0.00', '0.00', '2025-01-14 12:00:00', '2025-01-14 12:17:55', '2025-01-14 12:17:55'),
(91, 194, 182, 22, NULL, NULL, 2, 1, '0.00', '0.00', '2025-01-14 12:00:00', '2025-01-14 12:21:52', '2025-01-14 12:21:52'),
(92, 194, 183, 3, NULL, NULL, 2, 1, '0.00', '0.00', '2025-01-14 12:00:00', '2025-01-14 12:21:52', '2025-01-14 12:21:52'),
(93, 195, 184, 17, NULL, NULL, 1, 1, '0.00', '0.00', '2025-01-14 12:00:00', '2025-01-14 12:23:10', '2025-01-14 12:23:10'),
(94, 195, 185, 23, NULL, NULL, 1, 1, '0.00', '0.00', '2025-01-14 12:00:00', '2025-01-14 12:23:10', '2025-01-14 12:23:10'),
(95, 195, 186, 24, NULL, NULL, 1, 1, '0.00', '0.00', '2025-01-14 12:00:00', '2025-01-14 12:23:10', '2025-01-14 12:23:10'),
(96, 192, 180, 4, NULL, NULL, 1, 1, '0.00', '0.00', '2025-01-14 12:00:00', '2025-01-14 12:43:20', '2025-01-14 12:43:20'),
(97, 189, 171, 5, NULL, NULL, 1, 1, '0.00', '0.00', '2025-01-14 12:00:00', '2025-01-14 13:52:27', '2025-01-14 13:52:27'),
(98, 189, 172, 23, NULL, NULL, 1, 1, '0.00', '0.00', '2025-01-14 12:00:00', '2025-01-14 13:52:27', '2025-01-14 13:52:27'),
(99, 190, 173, 22, NULL, NULL, 3, 1, '0.00', '0.00', '2025-01-14 12:00:00', '2025-01-14 14:13:38', '2025-01-14 14:13:38'),
(100, 190, 174, 23, NULL, NULL, 2, 1, '0.00', '0.00', '2025-01-14 12:00:00', '2025-01-14 14:13:38', '2025-01-14 14:13:38'),
(101, 190, 175, 2, NULL, NULL, 2, 1, '0.00', '0.00', '2025-01-14 12:00:00', '2025-01-14 14:13:38', '2025-01-14 14:13:38'),
(102, 190, 176, 4, NULL, NULL, 1, 1, '0.00', '0.00', '2025-01-14 12:00:00', '2025-01-14 14:13:38', '2025-01-14 14:13:38'),
(103, 190, 177, 17, NULL, NULL, 1, 1, '0.00', '0.00', '2025-01-14 12:00:00', '2025-01-14 14:13:38', '2025-01-14 14:13:38'),
(104, 190, 178, 7, NULL, NULL, 1, 1, '0.00', '0.00', '2025-01-14 12:00:00', '2025-01-14 14:13:38', '2025-01-14 14:13:38'),
(105, 196, 187, 5, NULL, NULL, 1, 1, '0.00', '0.00', '2025-01-14 12:00:00', '2025-01-14 14:18:20', '2025-01-14 14:18:20'),
(106, 188, 169, 4, NULL, NULL, 2, 1, '0.00', '0.00', '2025-01-14 12:00:00', '2025-01-14 14:38:58', '2025-01-14 14:38:58'),
(107, 188, 170, 22, NULL, NULL, 1, 1, '0.00', '0.00', '2025-01-14 12:00:00', '2025-01-14 14:38:58', '2025-01-14 14:38:58'),
(108, 199, 193, 24, NULL, NULL, 1, 1, '0.00', '0.00', '2025-01-14 12:00:00', '2025-01-14 15:06:41', '2025-01-14 15:06:41'),
(109, 199, 194, 25, NULL, NULL, 1, 1, '0.00', '0.00', '2025-01-14 12:00:00', '2025-01-14 15:06:41', '2025-01-14 15:06:41'),
(110, 202, 199, 4, NULL, NULL, 1, 1, '0.00', '0.00', '2025-01-15 14:00:00', '2025-01-15 11:57:29', '2025-01-15 11:57:29'),
(111, 202, 200, 7, NULL, NULL, 1, 1, '0.00', '0.00', '2025-01-15 14:00:00', '2025-01-15 11:57:29', '2025-01-15 11:57:29'),
(112, 204, 202, 9, NULL, NULL, 1, 1, '0.00', '0.00', '2025-01-15 12:00:00', '2025-01-15 12:12:41', '2025-01-15 12:12:41'),
(113, 203, 201, 3, NULL, NULL, 1, 1, '0.00', '0.00', '2025-01-15 15:00:00', '2025-01-15 12:24:21', '2025-01-15 12:24:21'),
(114, 201, 198, 4, NULL, NULL, 1, 1, '0.00', '0.00', '2025-01-15 16:00:00', '2025-01-15 12:51:43', '2025-01-15 12:51:43'),
(115, 208, 208, 4, NULL, NULL, 1, 1, '0.00', '0.00', '2025-01-15 12:00:00', '2025-01-15 13:49:02', '2025-01-15 13:49:02'),
(116, 205, 203, 1, NULL, NULL, 1, 1, '0.00', '0.00', '2025-01-15 12:00:00', '2025-01-15 13:51:21', '2025-01-15 13:51:21'),
(117, 205, 204, 2, NULL, NULL, 2, 1, '0.00', '0.00', '2025-01-15 12:00:00', '2025-01-15 13:51:21', '2025-01-15 13:51:21'),
(118, 205, 205, 22, NULL, NULL, 1, 1, '0.00', '0.00', '2025-01-15 12:00:00', '2025-01-15 13:51:21', '2025-01-15 13:51:21'),
(119, 206, 206, 16, NULL, NULL, 1, 1, '0.00', '0.00', '2025-01-15 16:00:00', '2025-01-15 14:08:44', '2025-01-15 14:08:44'),
(120, 207, 207, 19, NULL, NULL, 4, 1, '0.00', '0.00', '2025-01-15 12:00:00', '2025-01-15 15:04:12', '2025-01-15 15:04:12'),
(121, 200, 195, 4, NULL, NULL, 2, 1, '0.00', '0.00', '2025-01-15 12:00:00', '2025-01-15 16:03:14', '2025-01-15 16:03:14'),
(122, 200, 196, 22, NULL, NULL, 1, 1, '0.00', '0.00', '2025-01-15 12:00:00', '2025-01-15 16:03:14', '2025-01-15 16:03:14'),
(123, 200, 197, 25, NULL, NULL, 1, 1, '0.00', '0.00', '2025-01-15 12:00:00', '2025-01-15 16:03:15', '2025-01-15 16:03:15'),
(124, 209, 209, 14, NULL, NULL, 1, 1, '0.00', '0.00', '2025-01-16 12:00:00', '2025-01-16 10:29:22', '2025-01-16 10:29:22'),
(125, 210, 210, 17, NULL, NULL, 1, 1, '0.00', '0.00', '2025-01-16 12:00:00', '2025-01-16 10:35:57', '2025-01-16 10:35:57'),
(126, 211, 211, 22, NULL, NULL, 1, 1, '0.00', '0.00', '2025-01-16 12:00:00', '2025-01-16 12:33:58', '2025-01-16 12:33:58'),
(127, 211, 212, 17, NULL, NULL, 1, 1, '0.00', '0.00', '2025-01-16 12:00:00', '2025-01-16 12:33:58', '2025-01-16 12:33:58'),
(128, 214, 213, 24, NULL, NULL, 4, 1, '0.00', '0.00', '2025-01-18 12:00:00', '2025-01-18 11:30:55', '2025-01-18 11:30:55'),
(129, 214, 214, 4, NULL, NULL, 3, 1, '0.00', '0.00', '2025-01-18 12:00:00', '2025-01-18 11:30:55', '2025-01-18 11:30:55'),
(130, 214, 215, 7, NULL, NULL, 1, 1, '0.00', '0.00', '2025-01-18 12:00:00', '2025-01-18 11:30:55', '2025-01-18 11:30:55'),
(131, 214, 216, 25, NULL, NULL, 1, 1, '0.00', '0.00', '2025-01-18 12:00:00', '2025-01-18 11:30:55', '2025-01-18 11:30:55'),
(132, 214, 217, 23, NULL, NULL, 1, 1, '0.00', '0.00', '2025-01-18 12:00:00', '2025-01-18 11:30:55', '2025-01-18 11:30:55'),
(133, 216, 219, 22, NULL, NULL, 2, 1, '0.00', '0.00', '2025-01-18 12:00:00', '2025-01-18 12:03:12', '2025-01-18 12:03:12'),
(134, 216, 220, 3, NULL, NULL, 2, 1, '0.00', '0.00', '2025-01-18 12:00:00', '2025-01-18 12:03:12', '2025-01-18 12:03:12'),
(135, 217, 221, 19, NULL, NULL, 3, 1, '0.00', '0.00', '2025-01-18 15:00:00', '2025-01-18 13:07:15', '2025-01-18 13:07:15'),
(136, 217, 222, 24, NULL, NULL, 1, 1, '0.00', '0.00', '2025-01-18 15:00:00', '2025-01-18 13:07:15', '2025-01-18 13:07:15'),
(137, 217, 223, 23, NULL, NULL, 1, 1, '0.00', '0.00', '2025-01-18 15:00:00', '2025-01-18 13:07:15', '2025-01-18 13:07:15'),
(138, 218, 224, 3, NULL, NULL, 1, 1, '0.00', '0.00', '2025-01-18 12:00:00', '2025-01-18 14:26:58', '2025-01-18 14:26:58'),
(139, 218, 225, 23, NULL, NULL, 1, 1, '0.00', '0.00', '2025-01-18 12:00:00', '2025-01-18 14:26:58', '2025-01-18 14:26:58'),
(140, 218, 226, 22, NULL, NULL, 1, 1, '0.00', '0.00', '2025-01-18 12:00:00', '2025-01-18 14:26:58', '2025-01-18 14:26:58'),
(141, 219, 227, 3, NULL, NULL, 1, 1, '0.00', '0.00', '2025-01-18 12:00:00', '2025-01-18 14:28:46', '2025-01-18 14:28:46'),
(142, 219, 228, 25, NULL, NULL, 1, 1, '0.00', '0.00', '2025-01-18 12:00:00', '2025-01-18 14:28:46', '2025-01-18 14:28:46'),
(143, 219, 229, 24, NULL, NULL, 1, 1, '0.00', '0.00', '2025-01-18 12:00:00', '2025-01-18 14:28:46', '2025-01-18 14:28:46'),
(144, 219, 230, 23, NULL, NULL, 1, 1, '0.00', '0.00', '2025-01-18 12:00:00', '2025-01-18 14:28:46', '2025-01-18 14:28:46'),
(145, 219, 231, 26, NULL, NULL, 1, 1, '0.00', '0.00', '2025-01-18 12:00:00', '2025-01-18 14:28:46', '2025-01-18 14:28:46'),
(146, 221, 233, 1, NULL, NULL, 2, 1, '0.00', '0.00', '2025-01-19 12:00:00', '2025-01-19 10:40:43', '2025-01-19 10:40:43'),
(147, 221, 234, 23, NULL, NULL, 2, 1, '0.00', '0.00', '2025-01-19 12:00:00', '2025-01-19 10:40:43', '2025-01-19 10:40:43'),
(148, 221, 235, 22, NULL, NULL, 2, 1, '0.00', '0.00', '2025-01-19 12:00:00', '2025-01-19 10:40:43', '2025-01-19 10:40:43'),
(149, 220, 232, 10, NULL, NULL, 1, 1, '0.00', '0.00', '2025-01-19 12:00:00', '2025-01-19 13:02:10', '2025-01-19 13:02:10'),
(150, 222, 236, 3, NULL, NULL, 1, 1, '0.00', '0.00', '2025-01-19 12:00:00', '2025-01-19 13:07:08', '2025-01-19 13:07:08'),
(151, 223, 237, 1, NULL, NULL, 1, 1, '0.00', '0.00', '2025-01-19 12:00:00', '2025-01-19 13:38:53', '2025-01-19 13:38:53'),
(152, 223, 238, 3, NULL, NULL, 1, 1, '0.00', '0.00', '2025-01-19 12:00:00', '2025-01-19 13:38:53', '2025-01-19 13:38:53'),
(153, 223, 239, 23, NULL, NULL, 1, 1, '0.00', '0.00', '2025-01-19 12:00:00', '2025-01-19 13:38:53', '2025-01-19 13:38:53'),
(154, 224, 240, 2, NULL, NULL, 1, 1, '0.00', '0.00', '2025-01-19 12:00:00', '2025-01-19 14:45:24', '2025-01-19 14:45:24'),
(155, 224, 241, 3, NULL, NULL, 1, 1, '0.00', '0.00', '2025-01-19 12:00:00', '2025-01-19 14:45:25', '2025-01-19 14:45:25'),
(156, 224, 242, 4, NULL, NULL, 1, 1, '0.00', '0.00', '2025-01-19 12:00:00', '2025-01-19 14:45:25', '2025-01-19 14:45:25'),
(157, 224, 243, 16, NULL, NULL, 1, 1, '0.00', '0.00', '2025-01-19 12:00:00', '2025-01-19 14:45:25', '2025-01-19 14:45:25'),
(158, 224, 244, 25, NULL, NULL, 3, 1, '0.00', '0.00', '2025-01-19 12:00:00', '2025-01-19 14:45:25', '2025-01-19 14:45:25'),
(159, 224, 245, 26, NULL, NULL, 3, 1, '0.00', '0.00', '2025-01-19 12:00:00', '2025-01-19 14:45:25', '2025-01-19 14:45:25'),
(160, 224, 246, 24, NULL, NULL, 2, 1, '0.00', '0.00', '2025-01-19 12:00:00', '2025-01-19 14:45:25', '2025-01-19 14:45:25'),
(161, 227, 249, 1, NULL, NULL, 1, 1, '0.00', '0.00', '2025-01-20 12:00:00', '2025-01-20 10:57:57', '2025-01-20 10:57:57'),
(162, 227, 250, 2, NULL, NULL, 1, 1, '0.00', '0.00', '2025-01-20 12:00:00', '2025-01-20 10:57:57', '2025-01-20 10:57:57'),
(163, 227, 251, 3, NULL, NULL, 1, 1, '0.00', '0.00', '2025-01-20 12:00:00', '2025-01-20 10:57:57', '2025-01-20 10:57:57'),
(164, 227, 252, 4, NULL, NULL, 1, 1, '0.00', '0.00', '2025-01-20 12:00:00', '2025-01-20 10:57:57', '2025-01-20 10:57:57'),
(165, 227, 253, 23, NULL, NULL, 2, 1, '0.00', '0.00', '2025-01-20 12:00:00', '2025-01-20 10:57:57', '2025-01-20 10:57:57'),
(166, 226, 248, 5, NULL, NULL, 1, 1, '0.00', '0.00', '2025-01-20 12:00:00', '2025-01-20 12:02:13', '2025-01-20 12:02:13'),
(167, 225, 247, 17, NULL, NULL, 2, 1, '0.00', '0.00', '2025-01-20 15:06:00', '2025-01-20 13:07:09', '2025-01-20 13:07:09'),
(168, 230, 256, 1, NULL, NULL, 1, 1, '0.00', '0.00', '2025-01-21 12:00:00', '2025-01-21 10:49:56', '2025-01-21 10:49:56'),
(169, 230, 257, 22, NULL, NULL, 1, 1, '0.00', '0.00', '2025-01-21 12:00:00', '2025-01-21 10:49:56', '2025-01-21 10:49:56'),
(170, 228, 254, 3, NULL, NULL, 1, 1, '0.00', '0.00', '2025-01-21 12:00:00', '2025-01-21 12:20:01', '2025-01-21 12:20:01'),
(171, 229, 255, 3, NULL, NULL, 2, 1, '0.00', '0.00', '2025-01-21 12:00:00', '2025-01-21 13:46:47', '2025-01-21 13:46:47'),
(175, 219, 227, 3, NULL, NULL, 3, 1, '0.00', '0.00', '2025-01-18 12:00:00', '2025-01-22 10:01:59', '2025-01-22 10:01:59'),
(176, 219, 228, 25, NULL, NULL, 2, 1, '0.00', '0.00', '2025-01-18 12:00:00', '2025-01-22 10:01:59', '2025-01-22 10:01:59'),
(177, 219, 229, 24, NULL, NULL, 1, 1, '0.00', '0.00', '2025-01-18 12:00:00', '2025-01-22 10:01:59', '2025-01-22 10:01:59'),
(178, 219, 230, 23, NULL, NULL, 3, 1, '0.00', '0.00', '2025-01-18 12:00:00', '2025-01-22 10:01:59', '2025-01-22 10:01:59'),
(179, 219, 231, 26, NULL, NULL, 1, 1, '0.00', '0.00', '2025-01-18 12:00:00', '2025-01-22 10:01:59', '2025-01-22 10:01:59'),
(180, 215, 218, 12, NULL, NULL, 1, 1, '35.00', '0.00', '2025-01-18 12:00:00', '2025-01-22 10:02:21', '2025-01-22 10:02:21'),
(181, 191, 179, 3, NULL, NULL, 2, 1, '0.00', '0.00', '2025-01-14 12:00:00', '2025-01-22 10:02:39', '2025-01-22 10:02:39');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('admin','user') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `role`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin User', 'admin@admin.com', 'admin', NULL, '$2y$12$zV425Agt4YHJESZyJ1cJ9O9Ifj83bxoJkO48QocPatJVzDjALl1j6', NULL, '2025-01-02 10:10:32', '2025-01-02 10:10:32'),
(3, 'Meera', 'meera@picblancsports.com', 'user', NULL, '$2y$12$bkzx/NcK6G5xLkO6NwgGCedo4xebvD2xihw0UjiHxIrKpmBnxTlbO', NULL, '2025-01-02 13:00:49', '2025-01-02 13:00:49'),
(4, 'Anthony', 'anthony@picblancsports.com', 'user', NULL, '$2y$12$VvtA4sscF47H6YWbjEORzuR4beR512EYi0GuBT2YwRAlx3RZrFFl6', NULL, '2025-01-02 13:01:14', '2025-01-02 13:01:14'),
(7, 'Anthony Akiki', 'anthonyakiki@picblancsports.com', 'user', NULL, '$2y$12$diWqrN6OLEaGKjOKv03xSeBztVqSoIOdorOHpjTz3tIjruQcjQtbe', NULL, '2025-01-09 14:36:33', '2025-01-10 11:59:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `additional_items`
--
ALTER TABLE `additional_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `additional_items_invoice_id_foreign` (`invoice_id`),
  ADD KEY `additional_items_product_id_foreign` (`product_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `categories_name_unique` (`name`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `customers_phone_phone2_unique` (`phone`,`phone2`),
  ADD UNIQUE KEY `customers_phone_unique` (`phone`),
  ADD UNIQUE KEY `customers_phone2_unique` (`phone2`);

--
-- Indexes for table `custom_items`
--
ALTER TABLE `custom_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `custom_items_invoice_id_foreign` (`invoice_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `invoices_customer_id_foreign` (`customer_id`),
  ADD KEY `invoices_category_id_foreign` (`category_id`),
  ADD KEY `invoices_user_id_foreign` (`user_id`);

--
-- Indexes for table `invoice_items`
--
ALTER TABLE `invoice_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `invoice_items_invoice_id_foreign` (`invoice_id`),
  ADD KEY `invoice_items_product_id_foreign` (`product_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `products_category_id_foreign` (`category_id`);

--
-- Indexes for table `return_details`
--
ALTER TABLE `return_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `return_details_product_id_foreign` (`product_id`),
  ADD KEY `return_details_additional_item_id_foreign` (`additional_item_id`),
  ADD KEY `return_details_invoice_id_index` (`invoice_id`),
  ADD KEY `return_details_invoice_item_id_index` (`invoice_item_id`),
  ADD KEY `return_details_custom_item_id_index` (`custom_item_id`);

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
-- AUTO_INCREMENT for table `additional_items`
--
ALTER TABLE `additional_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=213;

--
-- AUTO_INCREMENT for table `custom_items`
--
ALTER TABLE `custom_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=105;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=233;

--
-- AUTO_INCREMENT for table `invoice_items`
--
ALTER TABLE `invoice_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=263;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `return_details`
--
ALTER TABLE `return_details`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=184;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `additional_items`
--
ALTER TABLE `additional_items`
  ADD CONSTRAINT `additional_items_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `additional_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `custom_items`
--
ALTER TABLE `custom_items`
  ADD CONSTRAINT `custom_items_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `invoices`
--
ALTER TABLE `invoices`
  ADD CONSTRAINT `invoices_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`),
  ADD CONSTRAINT `invoices_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `invoices_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `invoice_items`
--
ALTER TABLE `invoice_items`
  ADD CONSTRAINT `invoice_items_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `invoice_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `return_details`
--
ALTER TABLE `return_details`
  ADD CONSTRAINT `return_details_additional_item_id_foreign` FOREIGN KEY (`additional_item_id`) REFERENCES `additional_items` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `return_details_custom_item_id_foreign` FOREIGN KEY (`custom_item_id`) REFERENCES `custom_items` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `return_details_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `return_details_invoice_item_id_foreign` FOREIGN KEY (`invoice_item_id`) REFERENCES `invoice_items` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `return_details_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
