-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 28, 2022 at 08:22 AM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 7.4.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ballast`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_users`
--

CREATE TABLE `admin_users` (
  `user_id` int(11) NOT NULL,
  `user_guid` varchar(40) NOT NULL,
  `user_type` enum('USER','ADMIN') NOT NULL DEFAULT 'USER',
  `first_name` varchar(80) DEFAULT NULL,
  `last_name` varchar(80) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `bio` text NOT NULL,
  `gender` enum('Male','Female','Others') DEFAULT NULL,
  `media_id` int(11) DEFAULT NULL,
  `status` enum('ACTIVE','DELETED','PENDING','BLOCKED') NOT NULL DEFAULT 'PENDING',
  `last_login_at` datetime DEFAULT NULL,
  `login_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `admin_users`
--

INSERT INTO `admin_users` (`user_id`, `user_guid`, `user_type`, `first_name`, `last_name`, `email`, `password`, `dob`, `bio`, `gender`, `media_id`, `status`, `last_login_at`, `login_at`, `created_at`, `updated_at`) VALUES
(1, '0b5398b0-6ea0-240a-bfb9-8bec3e7cbfaf', 'ADMIN', 'Admin', 'Second', 'admin@second.com', '25d55ad283aa400af464c76d713c07ad', NULL, '', 'Male', NULL, 'ACTIVE', '2022-08-28 02:12:54', '2022-08-28 02:15:08', '2018-01-08 11:39:07', '2018-01-08 11:39:07'),
(2, '0b5398b0-6ea0-240g-bfb9-8bec3e7dbfaf', 'ADMIN', 'Admin', 'Second', 'ballastcc@gmail.com', '25d55ad283aa400af464c76d713c07ad', NULL, '', 'Male', NULL, 'ACTIVE', '2020-02-13 10:55:34', '2020-02-13 15:54:55', '2018-01-08 11:39:07', '2018-01-08 11:39:07');

-- --------------------------------------------------------

--
-- Table structure for table `batches`
--

CREATE TABLE `batches` (
  `batch_id` int(11) NOT NULL,
  `batch_guid` varchar(40) NOT NULL,
  `name` varchar(100) NOT NULL,
  `medium` enum('HINDI','ENGLISH') NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `start` time NOT NULL,
  `end` time NOT NULL,
  `status` enum('ACTIVE','DELETED','PENDING') DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `batches`
--

INSERT INTO `batches` (`batch_id`, `batch_guid`, `name`, `medium`, `start_date`, `end_date`, `start`, `end`, `status`, `created_at`, `updated_at`) VALUES
(1, 'b974312a-d647-9b49-af9b-b3fa30e204f2', '2020-2021', 'ENGLISH', '2021-08-15 04:00:00', '2021-08-15 04:00:00', '04:00:00', '06:00:00', 'ACTIVE', '2021-09-21 23:17:09', '2021-09-21 23:17:09');

-- --------------------------------------------------------

--
-- Table structure for table `boards`
--

CREATE TABLE `boards` (
  `board_id` int(11) NOT NULL,
  `board_guid` varchar(40) NOT NULL,
  `name` varchar(80) NOT NULL,
  `status` enum('ACTIVE','PENDING','DELETED') NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `boards`
--

INSERT INTO `boards` (`board_id`, `board_guid`, `name`, `status`, `created_at`, `updated_at`) VALUES
(1, '144f5bf8-b817-bc3f-29b0-93ab8d12f4ed', 'M.P.', 'ACTIVE', '2021-09-21 23:20:06', '2021-09-21 23:20:06'),
(2, '011763ea-64b0-7a16-16ae-c204c7f87c68', 'U.P.', 'ACTIVE', '2021-09-21 23:20:18', '2021-09-21 23:20:18'),
(3, '88a4e162-0d8d-48a2-caab-69672b869f49', 'C.B.S.C.', 'ACTIVE', '2021-09-21 23:20:31', '2021-09-21 23:20:31'),
(4, 'ca1993d9-7fdb-9789-1148-666ad59397f8', 'I.C.S.C.', 'ACTIVE', '2021-09-21 23:20:51', '2021-09-21 23:20:51');

-- --------------------------------------------------------

--
-- Table structure for table `chapters`
--

CREATE TABLE `chapters` (
  `chapter_id` int(11) NOT NULL,
  `chapter_guid` varchar(40) NOT NULL,
  `subject_id` int(11) DEFAULT NULL,
  `chapter_name` varchar(150) NOT NULL,
  `added_by` int(11) NOT NULL,
  `status` enum('ACTIVE','DEACTIVE','DELETED','PENDING','BLOCKED') NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `chapters`
--

INSERT INTO `chapters` (`chapter_id`, `chapter_guid`, `subject_id`, `chapter_name`, `added_by`, `status`, `created_at`, `updated_at`) VALUES
(1, '19f120dd-288b-0700-0740-660e8959dba5', 6, 'electric charges update', 1, 'ACTIVE', '2022-08-26 23:10:31', '2022-08-26 23:36:15'),
(2, '410568cf-f62f-486f-5ef5-7e216078c93f', 12, 'alternating current', 1, 'ACTIVE', '2022-08-26 23:11:28', '2022-08-27 02:28:14'),
(3, '389b092a-4356-9599-b666-4d541da7a6d5', 8, 'moving charges and magnetism', 1, 'ACTIVE', '2022-08-26 23:11:51', '2022-08-26 23:11:51'),
(4, 'b1a7c583-c0ed-63da-e719-a76e2e8af303', 8, 'electric charges update', 1, 'ACTIVE', '2022-08-26 23:15:17', '2022-08-26 23:15:17');

-- --------------------------------------------------------

--
-- Table structure for table `classes`
--

CREATE TABLE `classes` (
  `class_id` int(11) NOT NULL,
  `class_guid` varchar(40) NOT NULL,
  `name` varchar(80) NOT NULL,
  `status` enum('ACTIVE','PENDING','DELETED','') NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `classes`
--

INSERT INTO `classes` (`class_id`, `class_guid`, `name`, `status`, `created_at`, `updated_at`) VALUES
(1, 'a90e4265-9735-d8f7-dbcd-9e2469a6fa01', '8th', 'ACTIVE', '2021-09-21 23:21:40', '2021-09-21 23:21:40'),
(2, '2683ae84-703b-2b2f-bcb9-85de8c1220c8', '9th', 'ACTIVE', '2021-09-21 23:21:46', '2021-09-21 23:21:46'),
(3, '29486fb7-5fbf-a809-98de-22046c43d683', '10th', 'ACTIVE', '2021-09-21 23:21:52', '2021-09-21 23:21:52'),
(4, '229ac668-fcb6-e24c-737d-0c0ecd30e5db', '11th', 'ACTIVE', '2021-09-21 23:21:57', '2021-09-21 23:21:57'),
(5, '48d2e0a7-5120-c956-3f6d-552ee7d348da', '12th', 'ACTIVE', '2021-09-21 23:22:02', '2021-09-21 23:22:02');

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

CREATE TABLE `countries` (
  `id` int(11) NOT NULL,
  `sortname` varchar(3) NOT NULL,
  `name` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `countries`
--

INSERT INTO `countries` (`id`, `sortname`, `name`) VALUES
(1, 'AF', 'Afghanistan'),
(2, 'AL', 'Albania'),
(3, 'DZ', 'Algeria'),
(4, 'AS', 'American Samoa'),
(5, 'AD', 'Andorra'),
(6, 'AO', 'Angola'),
(7, 'AI', 'Anguilla'),
(8, 'AQ', 'Antarctica'),
(9, 'AG', 'Antigua And Barbuda'),
(10, 'AR', 'Argentina'),
(11, 'AM', 'Armenia'),
(12, 'AW', 'Aruba'),
(13, 'AU', 'Australia'),
(14, 'AT', 'Austria'),
(15, 'AZ', 'Azerbaijan'),
(16, 'BS', 'Bahamas The'),
(17, 'BH', 'Bahrain'),
(18, 'BD', 'Bangladesh'),
(19, 'BB', 'Barbados'),
(20, 'BY', 'Belarus'),
(21, 'BE', 'Belgium'),
(22, 'BZ', 'Belize'),
(23, 'BJ', 'Benin'),
(24, 'BM', 'Bermuda'),
(25, 'BT', 'Bhutan'),
(26, 'BO', 'Bolivia'),
(27, 'BA', 'Bosnia and Herzegovina'),
(28, 'BW', 'Botswana'),
(29, 'BV', 'Bouvet Island'),
(30, 'BR', 'Brazil'),
(31, 'IO', 'British Indian Ocean Territory'),
(32, 'BN', 'Brunei'),
(33, 'BG', 'Bulgaria'),
(34, 'BF', 'Burkina Faso'),
(35, 'BI', 'Burundi'),
(36, 'KH', 'Cambodia'),
(37, 'CM', 'Cameroon'),
(38, 'CA', 'Canada'),
(39, 'CV', 'Cape Verde'),
(40, 'KY', 'Cayman Islands'),
(41, 'CF', 'Central African Republic'),
(42, 'TD', 'Chad'),
(43, 'CL', 'Chile'),
(44, 'CN', 'China'),
(45, 'CX', 'Christmas Island'),
(46, 'CC', 'Cocos (Keeling) Islands'),
(47, 'CO', 'Colombia'),
(48, 'KM', 'Comoros'),
(49, 'CG', 'Congo'),
(50, 'CD', 'Congo The Democratic Republic Of The'),
(51, 'CK', 'Cook Islands'),
(52, 'CR', 'Costa Rica'),
(53, 'CI', 'Cote D\'Ivoire (Ivory Coast)'),
(54, 'HR', 'Croatia (Hrvatska)'),
(55, 'CU', 'Cuba'),
(56, 'CY', 'Cyprus'),
(57, 'CZ', 'Czech Republic'),
(58, 'DK', 'Denmark'),
(59, 'DJ', 'Djibouti'),
(60, 'DM', 'Dominica'),
(61, 'DO', 'Dominican Republic'),
(62, 'TP', 'East Timor'),
(63, 'EC', 'Ecuador'),
(64, 'EG', 'Egypt'),
(65, 'SV', 'El Salvador'),
(66, 'GQ', 'Equatorial Guinea'),
(67, 'ER', 'Eritrea'),
(68, 'EE', 'Estonia'),
(69, 'ET', 'Ethiopia'),
(70, 'XA', 'External Territories of Australia'),
(71, 'FK', 'Falkland Islands'),
(72, 'FO', 'Faroe Islands'),
(73, 'FJ', 'Fiji Islands'),
(74, 'FI', 'Finland'),
(75, 'FR', 'France'),
(76, 'GF', 'French Guiana'),
(77, 'PF', 'French Polynesia'),
(78, 'TF', 'French Southern Territories'),
(79, 'GA', 'Gabon'),
(80, 'GM', 'Gambia The'),
(81, 'GE', 'Georgia'),
(82, 'DE', 'Germany'),
(83, 'GH', 'Ghana'),
(84, 'GI', 'Gibraltar'),
(85, 'GR', 'Greece'),
(86, 'GL', 'Greenland'),
(87, 'GD', 'Grenada'),
(88, 'GP', 'Guadeloupe'),
(89, 'GU', 'Guam'),
(90, 'GT', 'Guatemala'),
(91, 'XU', 'Guernsey and Alderney'),
(92, 'GN', 'Guinea'),
(93, 'GW', 'Guinea-Bissau'),
(94, 'GY', 'Guyana'),
(95, 'HT', 'Haiti'),
(96, 'HM', 'Heard and McDonald Islands'),
(97, 'HN', 'Honduras'),
(98, 'HK', 'Hong Kong S.A.R.'),
(99, 'HU', 'Hungary'),
(100, 'IS', 'Iceland'),
(101, 'IN', 'India'),
(102, 'ID', 'Indonesia'),
(103, 'IR', 'Iran'),
(104, 'IQ', 'Iraq'),
(105, 'IE', 'Ireland'),
(106, 'IL', 'Israel'),
(107, 'IT', 'Italy'),
(108, 'JM', 'Jamaica'),
(109, 'JP', 'Japan'),
(110, 'XJ', 'Jersey'),
(111, 'JO', 'Jordan'),
(112, 'KZ', 'Kazakhstan'),
(113, 'KE', 'Kenya'),
(114, 'KI', 'Kiribati'),
(115, 'KP', 'Korea North'),
(116, 'KR', 'Korea South'),
(117, 'KW', 'Kuwait'),
(118, 'KG', 'Kyrgyzstan'),
(119, 'LA', 'Laos'),
(120, 'LV', 'Latvia'),
(121, 'LB', 'Lebanon'),
(122, 'LS', 'Lesotho'),
(123, 'LR', 'Liberia'),
(124, 'LY', 'Libya'),
(125, 'LI', 'Liechtenstein'),
(126, 'LT', 'Lithuania'),
(127, 'LU', 'Luxembourg'),
(128, 'MO', 'Macau S.A.R.'),
(129, 'MK', 'Macedonia'),
(130, 'MG', 'Madagascar'),
(131, 'MW', 'Malawi'),
(132, 'MY', 'Malaysia'),
(133, 'MV', 'Maldives'),
(134, 'ML', 'Mali'),
(135, 'MT', 'Malta'),
(136, 'XM', 'Man (Isle of)'),
(137, 'MH', 'Marshall Islands'),
(138, 'MQ', 'Martinique'),
(139, 'MR', 'Mauritania'),
(140, 'MU', 'Mauritius'),
(141, 'YT', 'Mayotte'),
(142, 'MX', 'Mexico'),
(143, 'FM', 'Micronesia'),
(144, 'MD', 'Moldova'),
(145, 'MC', 'Monaco'),
(146, 'MN', 'Mongolia'),
(147, 'MS', 'Montserrat'),
(148, 'MA', 'Morocco'),
(149, 'MZ', 'Mozambique'),
(150, 'MM', 'Myanmar'),
(151, 'NA', 'Namibia'),
(152, 'NR', 'Nauru'),
(153, 'NP', 'Nepal'),
(154, 'AN', 'Netherlands Antilles'),
(155, 'NL', 'Netherlands The'),
(156, 'NC', 'New Caledonia'),
(157, 'NZ', 'New Zealand'),
(158, 'NI', 'Nicaragua'),
(159, 'NE', 'Niger'),
(160, 'NG', 'Nigeria'),
(161, 'NU', 'Niue'),
(162, 'NF', 'Norfolk Island'),
(163, 'MP', 'Northern Mariana Islands'),
(164, 'NO', 'Norway'),
(165, 'OM', 'Oman'),
(166, 'PK', 'Pakistan'),
(167, 'PW', 'Palau'),
(168, 'PS', 'Palestinian Territory Occupied'),
(169, 'PA', 'Panama'),
(170, 'PG', 'Papua new Guinea'),
(171, 'PY', 'Paraguay'),
(172, 'PE', 'Peru'),
(173, 'PH', 'Philippines'),
(174, 'PN', 'Pitcairn Island'),
(175, 'PL', 'Poland'),
(176, 'PT', 'Portugal'),
(177, 'PR', 'Puerto Rico'),
(178, 'QA', 'Qatar'),
(179, 'RE', 'Reunion'),
(180, 'RO', 'Romania'),
(181, 'RU', 'Russia'),
(182, 'RW', 'Rwanda'),
(183, 'SH', 'Saint Helena'),
(184, 'KN', 'Saint Kitts And Nevis'),
(185, 'LC', 'Saint Lucia'),
(186, 'PM', 'Saint Pierre and Miquelon'),
(187, 'VC', 'Saint Vincent And The Grenadines'),
(188, 'WS', 'Samoa'),
(189, 'SM', 'San Marino'),
(190, 'ST', 'Sao Tome and Principe'),
(191, 'SA', 'Saudi Arabia'),
(192, 'SN', 'Senegal'),
(193, 'RS', 'Serbia'),
(194, 'SC', 'Seychelles'),
(195, 'SL', 'Sierra Leone'),
(196, 'SG', 'Singapore'),
(197, 'SK', 'Slovakia'),
(198, 'SI', 'Slovenia'),
(199, 'XG', 'Smaller Territories of the UK'),
(200, 'SB', 'Solomon Islands'),
(201, 'SO', 'Somalia'),
(202, 'ZA', 'South Africa'),
(203, 'GS', 'South Georgia'),
(204, 'SS', 'South Sudan'),
(205, 'ES', 'Spain'),
(206, 'LK', 'Sri Lanka'),
(207, 'SD', 'Sudan'),
(208, 'SR', 'Suriname'),
(209, 'SJ', 'Svalbard And Jan Mayen Islands'),
(210, 'SZ', 'Swaziland'),
(211, 'SE', 'Sweden'),
(212, 'CH', 'Switzerland'),
(213, 'SY', 'Syria'),
(214, 'TW', 'Taiwan'),
(215, 'TJ', 'Tajikistan'),
(216, 'TZ', 'Tanzania'),
(217, 'TH', 'Thailand'),
(218, 'TG', 'Togo'),
(219, 'TK', 'Tokelau'),
(220, 'TO', 'Tonga'),
(221, 'TT', 'Trinidad And Tobago'),
(222, 'TN', 'Tunisia'),
(223, 'TR', 'Turkey'),
(224, 'TM', 'Turkmenistan'),
(225, 'TC', 'Turks And Caicos Islands'),
(226, 'TV', 'Tuvalu'),
(227, 'UG', 'Uganda'),
(228, 'UA', 'Ukraine'),
(229, 'AE', 'United Arab Emirates'),
(230, 'GB', 'United Kingdom'),
(231, 'US', 'United States'),
(232, 'UM', 'United States Minor Outlying Islands'),
(233, 'UY', 'Uruguay'),
(234, 'UZ', 'Uzbekistan'),
(235, 'VU', 'Vanuatu'),
(236, 'VA', 'Vatican City State (Holy See)'),
(237, 'VE', 'Venezuela'),
(238, 'VN', 'Vietnam'),
(239, 'VG', 'Virgin Islands (British)'),
(240, 'VI', 'Virgin Islands (US)'),
(241, 'WF', 'Wallis And Futuna Islands'),
(242, 'EH', 'Western Sahara'),
(243, 'YE', 'Yemen'),
(244, 'YU', 'Yugoslavia'),
(245, 'ZM', 'Zambia'),
(246, 'ZW', 'Zimbabwe');

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `course_id` int(11) NOT NULL,
  `course_guid` varchar(40) NOT NULL,
  `course_name` varchar(200) NOT NULL,
  `added_by` int(11) NOT NULL,
  `media` int(11) DEFAULT 1,
  `status` enum('ACTIVE','DELETED','PENDING','BLOCKED') NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`course_id`, `course_guid`, `course_name`, `added_by`, `media`, `status`, `created_at`, `updated_at`) VALUES
(1, 'eec61545-4be8-f3a3-69e6-7eb1b0bcf3d1', 'test', 1, 1, 'PENDING', '2022-08-26 18:13:29', '2022-08-26 18:15:26'),
(2, 'e9113a61-06e8-e992-b440-eb267fb0ae48', '12th', 1, 1, 'ACTIVE', '2022-08-26 19:19:54', '2022-08-26 19:19:54'),
(3, '340f8362-4417-9653-c471-20eebe503d4a', '9th', 1, 1, 'ACTIVE', '2022-08-26 19:19:59', '2022-08-26 19:19:59'),
(4, 'd39844ff-61e1-09a9-a2aa-148d96a2b589', '10th', 1, 1, 'ACTIVE', '2022-08-26 19:20:04', '2022-08-26 19:20:04'),
(5, 'ac85ab5c-f4fa-5778-1440-d4ee3cf52433', '', 1, 1, 'ACTIVE', '2022-08-26 19:20:09', '2022-08-26 21:00:35'),
(6, '799359d0-c432-41e4-3d04-b19d541945ef', '', 1, 1, 'ACTIVE', '2022-08-26 20:09:53', '2022-08-26 20:09:53');

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

CREATE TABLE `logs` (
  `id` int(11) NOT NULL,
  `uri` varchar(255) NOT NULL,
  `method` varchar(6) NOT NULL,
  `params` text DEFAULT NULL,
  `api_key` varchar(40) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `time` int(11) NOT NULL,
  `rtime` float DEFAULT NULL,
  `authorized` varchar(1) NOT NULL,
  `response_code` smallint(3) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `media`
--

CREATE TABLE `media` (
  `media_id` int(11) NOT NULL,
  `media_guid` varchar(40) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `original_name` varchar(255) NOT NULL,
  `media_category` enum('BANNER','LOGO','OTHER','') NOT NULL DEFAULT 'OTHER',
  `size` varchar(50) NOT NULL COMMENT 'in KB',
  `status` enum('PENDING','ACTIVE','DELETED') NOT NULL,
  `extension` varchar(10) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `options`
--

CREATE TABLE `options` (
  `option_id` int(11) NOT NULL,
  `option_guid` varchar(40) NOT NULL,
  `option_name` varchar(150) NOT NULL,
  `question_id` int(11) NOT NULL,
  `status` enum('ACTIVE','DELETE') NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `pay_id` int(11) NOT NULL,
  `pay_guid` varchar(48) NOT NULL,
  `pay_by` int(11) NOT NULL,
  `amount` float NOT NULL,
  `type` enum('CASH','ONLINE','OTHER','') NOT NULL,
  `paid_date` date NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`pay_id`, `pay_guid`, `pay_by`, `amount`, `type`, `paid_date`, `created_at`, `updated_at`) VALUES
(1, '19ac6f79-ee76-2aaf-3542-d629bd790be8', 2, 2000, 'CASH', '2021-09-12', '2021-09-14 21:21:38', '2021-09-14 21:21:38'),
(2, '47181c4a-a4b1-4fcd-ea30-df95a21ee4e4', 1, 6000, 'CASH', '2021-09-12', '2021-09-14 21:50:28', '2021-09-14 21:50:28'),
(3, 'e3e42c29-237e-675c-6a14-e837ccf02781', 1, 12000, 'CASH', '2021-09-12', '2021-09-14 21:50:45', '2021-09-14 21:50:45'),
(4, '3aaa60d5-06a5-8304-72bd-6bfa5852cad8', 1, 12000, 'CASH', '2021-09-12', '2021-09-14 21:53:55', '2021-09-14 21:53:55'),
(5, '19ac6f79-ee76-2aaf-3542-d629bd790be8', 2, 2000, 'CASH', '2021-09-12', '2021-09-14 21:21:38', '2021-09-14 21:21:38'),
(6, '47181c4a-a4b1-4fcd-ea30-df95a21ee4e4', 1, 6000, 'CASH', '2021-09-12', '2021-09-14 21:50:28', '2021-09-14 21:50:28'),
(7, 'e3e42c29-237e-675c-6a14-e837ccf02781', 1, 12000, 'CASH', '2021-09-12', '2021-09-14 21:50:45', '2021-09-14 21:50:45'),
(8, '3aaa60d5-06a5-8304-72bd-6bfa5852cad8', 1, 12000, 'CASH', '2021-09-12', '2021-09-14 21:53:55', '2021-09-14 21:53:55'),
(9, '19ac6f79-ee76-2aaf-3542-d629bd790be8', 2, 2000, 'CASH', '2021-09-12', '2021-09-14 21:21:38', '2021-09-14 21:21:38'),
(10, '47181c4a-a4b1-4fcd-ea30-df95a21ee4e4', 1, 6000, 'CASH', '2021-09-12', '2021-09-14 21:50:28', '2021-09-14 21:50:28'),
(11, 'e3e42c29-237e-675c-6a14-e837ccf02781', 1, 12000, 'CASH', '2021-09-12', '2021-09-14 21:50:45', '2021-09-14 21:50:45'),
(12, '3aaa60d5-06a5-8304-72bd-6bfa5852cad8', 1, 12000, 'CASH', '2021-09-12', '2021-09-14 21:53:55', '2021-09-14 21:53:55'),
(13, '19ac6f79-ee76-2aaf-3542-d629bd790be8', 2, 2000, 'CASH', '2021-09-12', '2021-09-14 21:21:38', '2021-09-14 21:21:38'),
(14, '47181c4a-a4b1-4fcd-ea30-df95a21ee4e4', 1, 6000, 'CASH', '2021-09-12', '2021-09-14 21:50:28', '2021-09-14 21:50:28'),
(15, 'e3e42c29-237e-675c-6a14-e837ccf02781', 1, 12000, 'CASH', '2021-09-12', '2021-09-14 21:50:45', '2021-09-14 21:50:45'),
(16, '3aaa60d5-06a5-8304-72bd-6bfa5852cad8', 1, 12000, 'CASH', '2021-09-12', '2021-09-14 21:53:55', '2021-09-14 21:53:55');

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
  `question_id` int(11) NOT NULL,
  `question_guid` varchar(40) NOT NULL,
  `type` enum('MCQ','BOOLEAN','CORRECT_MATCH','FILL_IN_BLANKS','ONE_WORD') NOT NULL,
  `title` varchar(150) NOT NULL,
  `media_id` int(11) DEFAULT NULL,
  `description` varchar(300) NOT NULL,
  `status` enum('ACTIVE','DELETE','PENDING','') NOT NULL,
  `answer` varchar(1000) DEFAULT NULL,
  `answer_id` int(11) NOT NULL COMMENT 'this is actual answer option id',
  `solution_text` text DEFAULT NULL,
  `solution_media` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `site_logs`
--

CREATE TABLE `site_logs` (
  `site_log_id` int(11) NOT NULL,
  `site_log_guid` varchar(40) NOT NULL,
  `uri` varchar(255) NOT NULL,
  `input` text NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `site_logs`
--

INSERT INTO `site_logs` (`site_log_id`, `site_log_guid`, `uri`, `input`, `ip_address`, `created_at`) VALUES
(1, 'b63a952b-05ca-a64f-55cc-e88fc415a25b', 'api/users/login', '{\"email\":\"romildoe@mailinator.cpm\",\"device_type\":\"web_browser\",\"password\":\"********\",\"key\":\"value\"}', '::1', '2020-10-08 08:01:37'),
(2, '9a448537-f8f5-13b6-ec2e-d0333aaeb81c', 'api/users/login', '{\"email\":\"demo@demo.com\",\"device_type\":\"web_browser\",\"password\":\"********\",\"key\":\"value\"}', '::1', '2020-10-16 11:36:04'),
(3, '9a8367fc-567f-e9e1-3df9-e72956fd546b', 'api/users/login', '{\"email\":\"demo@demo.com\",\"device_type\":\"web_browser\",\"password\":\"***********\",\"key\":\"value\"}', '::1', '2020-10-16 11:36:35'),
(4, '9d1ddf67-fe5a-8b09-aec4-78b339c7d02e', 'api/users/login', '{\"email\":\"test@gmail.com\",\"device_type\":\"web_browser\",\"password\":\"********\",\"key\":\"value\"}', '::1', '2020-10-22 10:20:51'),
(5, 'b455e765-9ffe-1540-1f68-a23f46c6aece', 'api/users/login', '{\"email\":\"admin@gamil.com\",\"device_type\":\"web_browser\",\"password\":\"********\",\"key\":\"value\"}', '::1', '2020-10-22 10:42:40'),
(6, '6c4510a1-e961-3ed8-20ef-de04135b51b1', 'api/users/login', '{\"email\":\"admi@admin.com\",\"device_type\":\"web_browser\",\"password\":\"********\",\"key\":\"value\"}', '::1', '2020-10-23 16:01:03'),
(7, '1bc5eb4a-62dc-7731-b6bf-777dc5a3fc53', 'api/admin/auth/login', '{\"email\":\"testsecond@gmail.com\",\"device_type\":\"web_browser\",\"password\":\"********\",\"key\":\"value\"}', '::1', '2021-09-10 22:05:49'),
(8, 'c60bb026-56b1-80e1-2c11-e828511de59e', 'api/admin/auth/login', '{\"email\":\"test@gmail.com\",\"device_type\":\"web_browser\",\"password\":\"********\",\"key\":\"value\"}', '::1', '2021-09-10 22:30:21'),
(9, 'c24f795b-d29f-1c94-87f5-dab01c4dbea5', 'api/admin/auth/login', '{\"email\":\"ballast@gmail.com\",\"device_type\":\"web_browser\",\"password\":\"********\",\"key\":\"value\"}', '::1', '2021-09-15 23:03:44'),
(10, '1caff2f5-f85d-874e-b914-7da65429bec6', 'api/admin/auth/login', '{\"email\":\"admin@gmail.com\",\"device_type\":\"web_browser\",\"password\":\"********\",\"key\":\"value\"}', '::1', '2021-09-15 23:04:54'),
(11, '20cb5fdf-cd39-b0a0-5f92-9141f5f2537c', 'api/admin/auth/login', '{\"email\":\"admin@gmail.com\",\"device_type\":\"web_browser\",\"password\":\"********\",\"key\":\"value\"}', '::1', '2021-09-15 23:05:35'),
(12, 'f907f0e4-5fa1-7133-613d-fc90cff4fd83', 'api/admin/auth/login', '{\"email\":\"admin@gmail.com\",\"device_type\":\"web_browser\",\"password\":\"********\",\"key\":\"value\"}', '::1', '2021-09-15 23:05:37'),
(13, '40e4d99a-a49b-87e9-5b5c-59489d30a660', 'api/admin/auth/login', '{\"email\":\"admin@gmail.com\",\"device_type\":\"web_browser\",\"password\":\"********\",\"key\":\"value\"}', '::1', '2021-09-15 23:05:38'),
(14, '75a90c8b-e3fd-60da-85ac-310ad18e44fe', 'api/admin/auth/login', '{\"email\":\"admin@gmail.com\",\"device_type\":\"web_browser\",\"password\":\"********\",\"key\":\"value\"}', '::1', '2021-09-15 23:05:39'),
(15, '450085f3-69fd-eb46-c833-26b8b5cd44ac', 'api/admin/auth/login', '{\"email\":\"admin@gmail.com\",\"device_type\":\"web_browser\",\"password\":\"********\",\"key\":\"value\"}', '::1', '2021-09-15 23:05:40'),
(16, '0d00765a-c8af-7099-f501-67d326846c89', 'api/admin/auth/login', '{\"email\":\"admin@gmail.com\",\"device_type\":\"web_browser\",\"password\":\"********\",\"key\":\"value\"}', '::1', '2021-09-15 23:06:16'),
(17, '992c3473-a129-ab01-cf49-3ebf7ad1220d', 'api/admin/auth/login', '{\"email\":\"adminf@gmail.com\",\"device_type\":\"web_browser\",\"password\":\"********\",\"key\":\"value\"}', '::1', '2021-09-17 19:37:00'),
(18, 'b7e684da-b549-7db8-e324-04a700e06558', 'api/admin/auth/login', '{\"email\":\"ballast@gmail.com\",\"device_type\":\"web_browser\",\"password\":\"*******\",\"key\":\"value\"}', '::1', '2022-08-26 17:54:30'),
(19, '2e2f3fa6-8bbe-d0bf-ccdf-67b2a6ab4ef8', 'api/admin/auth/login', '{\"email\":\"ballast@gmail.com\",\"device_type\":\"web_browser\",\"password\":\"*******\",\"key\":\"value\"}', '::1', '2022-08-26 17:55:08'),
(20, '0035b1ab-97ce-a83a-4b3e-69cdd5146fdf', 'api/admin/auth/login', '{\"email\":\"ballast@gmail.com\",\"device_type\":\"web_browser\",\"password\":\"*******\",\"key\":\"value\"}', '::1', '2022-08-26 17:56:27'),
(21, '250ccad8-5b44-f162-e4a8-149da0720def', 'api/admin/auth/login', '{\"email\":\"ballast@gmail.com\",\"device_type\":\"web_browser\",\"password\":\"*******\",\"key\":\"value\"}', '::1', '2022-08-26 17:56:28'),
(22, '5d7637a2-78eb-1389-7607-4c8d801d41cc', 'api/admin/auth/login', '{\"email\":\"ballast@gmail.com\",\"device_type\":\"web_browser\",\"password\":\"********\",\"key\":\"value\"}', '::1', '2022-08-26 17:56:38'),
(23, '95c7a24a-a593-de4b-0f7b-7152516c9b0c', 'api/admin/auth/login', '{\"email\":\"ballast@gmail.com\",\"device_type\":\"web_browser\",\"password\":\"********\",\"key\":\"value\"}', '::1', '2022-08-26 17:56:39'),
(24, '674408ee-6d62-e87d-d73a-8f83b0383288', 'api/admin/auth/login', '{\"email\":\"ballast@gmail.com\",\"device_type\":\"web_browser\",\"password\":\"********\",\"key\":\"value\"}', '::1', '2022-08-26 17:56:47'),
(25, 'ffd7a1da-4691-686a-bea9-cd6032dfde6b', 'api/admin/auth/login', '{\"email\":\"admin@admin.com\",\"device_type\":\"web_browser\",\"password\":\"********\",\"key\":\"value\"}', '::1', '2022-08-26 17:57:22'),
(26, '4b59738c-9025-162a-51bb-1f61f9e5077a', 'api/admin/auth/login', '{\"email\":\"admin@admin.com\",\"device_type\":\"web_browser\",\"password\":\"********\",\"key\":\"value\"}', '::1', '2022-08-26 17:57:52'),
(27, '2d2ec441-7326-a437-a497-5c6af5da15f3', 'api/admin/auth/login', '{\"email\":\"admin@admin.com\",\"device_type\":\"web_browser\",\"password\":\"********\",\"key\":\"value\"}', '::1', '2022-08-26 17:57:53'),
(28, '54dce7eb-7d0a-1fa1-0639-d467b6d4d0a7', 'api/admin/auth/login', '{\"email\":\"admin@admin.com\",\"device_type\":\"web_browser\",\"password\":\"*******\",\"key\":\"value\"}', '::1', '2022-08-26 17:58:04'),
(29, '10089632-3d0f-7439-2d43-76d465e8aced', 'api/admin/auth/login', '{\"email\":\"admin@admin.com\",\"device_type\":\"web_browser\",\"password\":\"********\",\"key\":\"value\"}', '::1', '2022-08-26 17:58:09'),
(30, '7f2caccc-64f1-2a8b-484c-1777d428b8d3', 'api/admin/auth/login', '{\"email\":\"admin@admin.com\",\"device_type\":\"web_browser\",\"password\":\"********\",\"key\":\"value\"}', '::1', '2022-08-26 17:58:52');

-- --------------------------------------------------------

--
-- Table structure for table `states`
--

CREATE TABLE `states` (
  `state_id` int(11) NOT NULL,
  `state` varchar(22) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `states`
--

INSERT INTO `states` (`state_id`, `state`) VALUES
(1, 'Alaska'),
(2, 'Alabama'),
(3, 'Arkansas'),
(4, 'Arizona'),
(5, 'California'),
(6, 'Colorado'),
(7, 'Connecticut'),
(8, 'District of Columbia'),
(9, 'Delaware'),
(10, 'Florida'),
(11, 'Georgia'),
(12, 'Hawaii'),
(13, 'Iowa'),
(14, 'Idaho'),
(15, 'Illinois'),
(16, 'Indiana'),
(17, 'Kansas'),
(18, 'Kentucky'),
(19, 'Louisiana'),
(20, 'Massachusetts'),
(21, 'Maryland'),
(22, 'Maine'),
(23, 'Michigan'),
(24, 'Minnesota'),
(25, 'Missouri'),
(26, 'Mississippi'),
(27, 'Montana'),
(28, 'North Carolina'),
(29, 'North Dakota'),
(30, 'Nebraska'),
(31, 'New Hampshire'),
(32, 'New Jersey'),
(33, 'New Mexico'),
(34, 'Nevada'),
(35, 'New York'),
(36, 'Ohio'),
(37, 'Oklahoma'),
(38, 'Oregon'),
(39, 'Pennsylvania'),
(40, 'Rhode Island'),
(41, 'South Carolina'),
(42, 'South Dakota'),
(43, 'Tennessee'),
(44, 'Texas'),
(45, 'Utah'),
(46, 'Virginia'),
(47, 'Vermont'),
(48, 'Washington'),
(49, 'Wisconsin'),
(50, 'West Virginia'),
(51, 'Wyoming');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `student_id` int(11) NOT NULL,
  `student_guid` varchar(40) NOT NULL,
  `reg_number` varchar(20) NOT NULL,
  `first_name` varchar(80) NOT NULL,
  `last_name` varchar(80) NOT NULL,
  `father_name` varchar(100) NOT NULL,
  `mother_name` varchar(100) NOT NULL,
  `dob` date DEFAULT NULL,
  `class` int(11) NOT NULL,
  `subjects` text NOT NULL,
  `board` int(11) NOT NULL,
  `medium` enum('HINDI','ENGLISH') NOT NULL,
  `batch` int(11) NOT NULL,
  `total_fee` float NOT NULL,
  `remain_fee` float DEFAULT NULL,
  `reg_date` date NOT NULL,
  `profile_id` int(11) DEFAULT NULL,
  `school` varchar(250) NOT NULL,
  `address` varchar(200) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `mobile` varchar(15) NOT NULL,
  `alt_mobile` varchar(15) NOT NULL,
  `status` enum('ACTIVE','PENDING','DELETED','') NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`student_id`, `student_guid`, `reg_number`, `first_name`, `last_name`, `father_name`, `mother_name`, `dob`, `class`, `subjects`, `board`, `medium`, `batch`, `total_fee`, `remain_fee`, `reg_date`, `profile_id`, `school`, `address`, `email`, `mobile`, `alt_mobile`, `status`, `created_at`, `updated_at`) VALUES
(1, 'f7b1a753-686a-0a4e-dcc2-3945f46c8b49', '22E003', 'Vaishali ', 'rajput', 'harishankar rajput', 'manisha rajput', '2006-05-28', 3, '{subject_guid: \"a4ad4786-3055-962c-6dd8-3f89481effe9\", name: \"Physics\", status: \"ACTIVE\"}', 1, 'ENGLISH', 1, 6300, 6300, '2021-07-01', 0, 'career acadmy nandbagh', '305/11 C nandbagh coloney', '', '9926811259', '6260073955', 'ACTIVE', '2021-09-22 00:59:01', '2021-09-22 00:59:01'),
(2, '2cc1237f-9f4d-c437-c81d-2d16a6e93fda', '22E006', 'Anshuma', 'nagle', 'narayan nagle', 'sangeeta nagle', '2006-08-01', 3, '', 1, 'ENGLISH', 1, 6300, 6300, '2021-07-01', 0, 'Rai academy H.S. school', '191/5 D nandbag colony', '', '9630800469', '7828794184', 'ACTIVE', '2021-09-22 01:43:52', '2021-09-22 01:43:52'),
(3, '34696433-ba26-75c8-dc94-3906cdc6fc96', '22A009', 'sneha', 'goswami', 'raju goswami', 'sobha goswami', '2003-11-08', 5, 'YTozOntpOjA7YTozOntzOjEyOiJzdWJqZWN0X2d1aWQiO3M6MzY6ImE0YWQ0Nzg2LTMwNTUtOTYyYy02ZGQ4LTNmODk0ODFlZmZlOSI7czo0OiJuYW1lIjtzOjc6IlBoeXNpY3MiO3M6Njoic3RhdHVzIjtzOjY6IkFDVElWRSI7fWk6MTthOjM6e3M6MTI6InN1YmplY3RfZ3VpZCI7czozNjoiMWNjMTg0NzgtMTI1Mi1jYzc2LWNjYmQtNjFlNGU4ZTA1NTBkIjtzOjQ6Im5hbWUiO3M6OToiQ2hlbWlzdHJ5IjtzOjY6InN0YXR1cyI7czo2OiJBQ1RJVkUiO31pOjI7YTozOntzOjEyOiJzdWJqZWN0X2d1aWQiO3M6MzY6ImI1NjRjNzE3LTM4MGQtODJjYS1mZDU1LTViN2M3NDcxZWU0YiI7czo0OiJuYW1lIjtzOjEwOiJNYXRobWF0aWNzIjtzOjY6InN0YXR1cyI7czo2OiJBQ1RJVkUiO319', 1, 'HINDI', 1, 9500, 9500, '2021-07-01', 0, 'private form', '1/B nandbagh coloney indore m.p.', '', '8817795990', '', 'ACTIVE', '2021-09-22 02:32:15', '2021-09-22 02:32:15'),
(4, 'b2f2262d-3318-34ed-10d3-fd0e45ae3313', '22E004', 'Jiya', 'deshmukh', 'Ruprav deshmukh', 'sarita deshmukh', '2007-01-19', 3, 'YTozOntpOjA7YTozOntzOjEyOiJzdWJqZWN0X2d1aWQiO3M6MzY6IjczNzNiZjc2LTRmMTgtMmExZS0zNGQwLWQ0ZWViOGQyODE3MSI7czo0OiJuYW1lIjtzOjc6IlNjaWVuY2UiO3M6Njoic3RhdHVzIjtzOjY6IkFDVElWRSI7fWk6MTthOjM6e3M6MTI6InN1YmplY3RfZ3VpZCI7czozNjoiYjU2NGM3MTctMzgwZC04MmNhLWZkNTUtNWI3Yzc0NzFlZTRiIjtzOjQ6Im5hbWUiO3M6MTA6Ik1hdGhtYXRpY3MiO3M6Njoic3RhdHVzIjtzOjY6IkFDVElWRSI7fWk6MjthOjM6e3M6MTI6InN1YmplY3RfZ3VpZCI7czozNjoiMTk3YmE4OGEtNTllYy03YmE5LWVjZjMtYTFiMzEzZDY2ODMwIjtzOjQ6Im5hbWUiO3M6MTQ6IlNvY2lhbCBTY2llbmNlIjtzOjY6InN0YXR1cyI7czo2OiJBQ1RJVkUiO319', 1, 'ENGLISH', 1, 6300, 6300, '2021-07-01', 0, 'career academy nandbag indore', '177/5 nandbag colony indore (m.p.)', '', '9981263238', '', 'ACTIVE', '2021-09-22 02:53:47', '2021-09-22 02:53:47');

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE `subjects` (
  `subject_id` int(11) NOT NULL,
  `subject_guid` varchar(40) NOT NULL,
  `course_id` int(11) DEFAULT NULL,
  `subject_name` varchar(150) NOT NULL,
  `added_by` int(11) NOT NULL,
  `status` enum('ACTIVE','DEACTIVE','DELETED','PENDING','BLOCKED') NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `subjects`
--

INSERT INTO `subjects` (`subject_id`, `subject_guid`, `course_id`, `subject_name`, `added_by`, `status`, `created_at`, `updated_at`) VALUES
(1, '81fbc9a0-82bf-7dcc-bd48-8a169399b97d', 2, 'mathmatics', 1, 'ACTIVE', '2022-08-26 20:17:23', '2022-08-26 20:17:23'),
(2, 'b65bb717-2c59-d8df-a3d9-e21bfa13caa1', 2, 'mathmatics2', 1, 'ACTIVE', '2022-08-26 20:18:45', '2022-08-26 20:18:45'),
(3, 'e080504b-c624-a43c-d9c1-ca7eaf836697', 2, 'mathmatics2', 1, 'ACTIVE', '2022-08-26 20:20:00', '2022-08-26 20:20:00'),
(4, '13dd968f-e8b3-c1ac-9b19-3df8988554e6', 2, 'mathmatics2', 1, 'ACTIVE', '2022-08-26 20:31:54', '2022-08-26 20:31:54'),
(5, '2e3df991-3d3a-5fca-568f-40cd506b335a', 2, 'physics', 1, 'ACTIVE', '2022-08-26 20:33:33', '2022-08-26 20:33:33'),
(6, '291166ae-8342-87b7-24b1-43d7486e42fd', NULL, '', 1, 'ACTIVE', '2022-08-26 20:36:15', '2022-08-26 23:25:32'),
(7, 'bad507c3-4683-5bb2-b67f-0d8d3d5d5f92', 2, 'chemistry', 1, 'ACTIVE', '2022-08-26 20:36:34', '2022-08-26 20:36:34'),
(8, '7f7aa5f9-dd67-963b-823c-2b8777646a93', 2, 'bio', 1, 'ACTIVE', '2022-08-26 20:38:42', '2022-08-26 20:38:42'),
(9, '5aa1937f-1230-7a46-caac-ac1245069cbb', 2, 'bio', 1, 'ACTIVE', '2022-08-26 20:44:54', '2022-08-26 20:44:54'),
(10, '12a83581-9f3b-f9a5-a897-ba9337bfcd4b', 2, 'bio', 1, 'ACTIVE', '2022-08-26 20:44:59', '2022-08-26 20:44:59'),
(11, '1cabb7ab-e228-9ef4-6655-510179488d49', 2, 'bio', 1, 'ACTIVE', '2022-08-26 20:45:01', '2022-08-26 20:45:01'),
(12, '079c65b5-87a0-394c-ea08-8bd27d5e517c', 2, 'bio', 1, 'ACTIVE', '2022-08-26 20:45:20', '2022-08-26 20:45:20'),
(13, '48f9aaa0-8878-a18d-974c-0fa6752faddb', 2, 'bio', 1, 'ACTIVE', '2022-08-26 20:45:22', '2022-08-26 20:45:22'),
(14, '59978ff1-cbff-8eb5-0486-099f8aeda4aa', 0, 'bio', 1, 'ACTIVE', '2022-08-26 20:47:48', '2022-08-26 20:47:48'),
(15, '5cba95b7-ec4e-660d-c04c-30a2ab1a6f5f', 2, 'hindi', 1, 'ACTIVE', '2022-08-26 20:48:07', '2022-08-26 20:48:07'),
(16, '79ee7c5f-f636-3839-4a21-f6d2c1fd2b01', 0, 'hindi', 1, 'ACTIVE', '2022-08-26 20:48:14', '2022-08-26 20:48:14'),
(17, '8342f2a0-c3a5-3421-5158-9fcc5f0bb8a7', 0, 'science', 1, 'ACTIVE', '2022-08-26 20:48:30', '2022-08-26 20:48:30'),
(18, '312e150f-ab05-1d68-72f4-83f1774553e5', 2, 'science', 1, 'ACTIVE', '2022-08-26 20:48:39', '2022-08-26 20:48:39'),
(19, '0705140a-a430-59c1-de07-cef1d4bc5edc', 0, '', 1, 'ACTIVE', '2022-08-26 23:04:59', '2022-08-26 23:04:59');

-- --------------------------------------------------------

--
-- Table structure for table `tests`
--

CREATE TABLE `tests` (
  `test_id` int(11) NOT NULL,
  `test_guid` varchar(40) NOT NULL,
  `test_name` varchar(150) NOT NULL,
  `status` enum('ACTIVE','PENDING','DELETED') NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `user_guid` varchar(40) NOT NULL,
  `do_not_delete` enum('YES','NO') NOT NULL DEFAULT 'NO',
  `user_type` enum('OWNER','ADMIN','SITE_MANAGER','USER') NOT NULL DEFAULT 'USER',
  `first_name` varchar(80) DEFAULT NULL,
  `last_name` varchar(80) NOT NULL,
  `business_name` varchar(80) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `mobile` varchar(15) NOT NULL,
  `gender` enum('MALE','FEMALE','OTHERS') DEFAULT NULL,
  `profile_picture_id` int(11) DEFAULT NULL,
  `cover_picture_id` int(11) NOT NULL DEFAULT 0,
  `present_address` text CHARACTER SET utf8 DEFAULT NULL,
  `permanent_address` text CHARACTER SET utf8 DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `state` varchar(100) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `device_type_id` int(11) NOT NULL,
  `status` enum('ACTIVE','DELETED','PENDING','BLOCKED') NOT NULL DEFAULT 'PENDING',
  `last_login_at` datetime DEFAULT NULL,
  `customer_stripe_id` varchar(100) DEFAULT NULL,
  `login_at` datetime DEFAULT NULL,
  `send_push_notifications` enum('YES','NO') NOT NULL DEFAULT 'YES',
  `send_email_notifications` enum('YES','NO') NOT NULL DEFAULT 'YES',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `user_guid`, `do_not_delete`, `user_type`, `first_name`, `last_name`, `business_name`, `email`, `password`, `dob`, `bio`, `mobile`, `gender`, `profile_picture_id`, `cover_picture_id`, `present_address`, `permanent_address`, `country`, `state`, `city`, `device_type_id`, `status`, `last_login_at`, `customer_stripe_id`, `login_at`, `send_push_notifications`, `send_email_notifications`, `created_at`, `updated_at`) VALUES
(1, 'cb326240-0fb7-4957-8997-ccd2d546b733', 'NO', 'ADMIN', 'Admin', '', 'Admin Business', 'admin@admin.com', '25d55ad283aa400af464c76d713c07ad', NULL, NULL, '', 'MALE', NULL, 0, NULL, NULL, NULL, NULL, NULL, 0, 'ACTIVE', '2020-11-09 05:39:36', NULL, '2020-11-09 11:29:38', 'YES', 'YES', '2020-09-15 00:00:00', '0000-00-00 00:00:00'),
(2, 'bfcb3631-0511-e9c9-7aca-640307c91f72', 'NO', 'USER', 'Marketing Tiki', '', 'Individual business Marketing', 'mtsupport@mailinator.com', '25d55ad283aa400af464c76d713c07ad', NULL, NULL, '', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 1, 'ACTIVE', '2020-10-08 14:25:52', NULL, '2020-10-09 15:26:31', 'YES', 'YES', '2020-10-08 06:26:43', '2020-10-08 06:26:43'),
(3, '06ab9888-2b05-4b75-87ec-b34c40943f0d', 'NO', 'USER', 'User name', '', 'Business name', 'dummy@mailinator.com', '25d55ad283aa400af464c76d713c07ad', NULL, NULL, '', 'MALE', NULL, 0, NULL, NULL, NULL, NULL, NULL, 0, 'ACTIVE', NULL, NULL, '2020-10-08 08:02:13', 'YES', 'YES', '2020-09-10 10:00:00', '0000-00-00 00:00:00'),
(4, 'd8a95f49-d985-32c9-e4c3-1362189a7432', 'NO', 'USER', 'Brehm Strategic Marketing Group', '', 'Brehm Strategic Marketing Group', 'ryans@mailinator.com', '25d55ad283aa400af464c76d713c07ad', NULL, NULL, '', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 1, 'ACTIVE', NULL, NULL, '2020-10-23 16:01:44', 'YES', 'YES', '2020-10-08 07:13:33', '2020-10-08 07:13:33'),
(5, '549cf9bd-0373-8b81-183b-fa544a17650d', 'NO', 'USER', 'demo', '', 'demo business', 'demo@mailinator.com', '25d55ad283aa400af464c76d713c07ad', NULL, NULL, '', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 1, 'ACTIVE', '2020-10-23 16:00:20', NULL, '2020-11-09 07:38:36', 'YES', 'YES', '2020-10-12 12:35:48', '2020-10-12 12:35:48'),
(6, '95a57874-2e95-42a0-c966-e7c83c850842', 'NO', 'USER', 'fjkhgbdrjhg', '', 'fhfghfh', 'test@gmail.com', '25f9e794323b453885f5181f1b624d0b', NULL, NULL, '', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 1, 'ACTIVE', NULL, NULL, NULL, 'YES', 'YES', '2020-10-12 13:40:28', '2020-10-12 13:40:28');

-- --------------------------------------------------------

--
-- Table structure for table `user_login_sessions`
--

CREATE TABLE `user_login_sessions` (
  `user_login_session_id` int(11) NOT NULL,
  `session_key` varchar(40) NOT NULL,
  `user_id` int(11) NOT NULL,
  `device_type_id` int(11) NOT NULL,
  `device_token` varchar(255) DEFAULT NULL,
  `ip_address` varchar(50) DEFAULT NULL,
  `status` enum('ACTIVE','LOGGED_OUT','INACTIVE_BY_ADMIN') NOT NULL DEFAULT 'ACTIVE',
  `created_at` datetime DEFAULT NULL,
  `last_used_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_login_sessions`
--

INSERT INTO `user_login_sessions` (`user_login_session_id`, `session_key`, `user_id`, `device_type_id`, `device_token`, `ip_address`, `status`, `created_at`, `last_used_at`) VALUES
(1, '2017f794-9c0f-5782-4abd-50287578efad', 2, 1, NULL, '::1', 'ACTIVE', '2021-09-22 02:54:58', '2021-09-22 02:59:18'),
(2, '008641c4-b29f-24ea-106c-d9391286770f', 2, 1, NULL, '::1', 'ACTIVE', '2021-09-22 03:00:41', '2021-09-22 03:26:40'),
(3, '8ea649cb-4de1-c1f8-cc76-64525cdf2aa3', 2, 1, NULL, '::1', 'ACTIVE', '2021-09-22 13:31:29', '2021-10-28 08:39:26'),
(4, '8ec822ab-dda1-a1bb-2148-8780f087f833', 2, 1, NULL, '::1', 'ACTIVE', '2021-09-25 09:23:33', '2021-09-26 02:13:14'),
(5, 'b6292035-de22-2ea7-89fd-5ca8399074df', 1, 1, NULL, '::1', 'ACTIVE', '2021-09-25 19:48:03', '2021-09-25 19:49:08'),
(6, 'dc948bb5-fa8d-8956-6a2f-4a5dd1aa09ae', 1, 1, NULL, '::1', 'ACTIVE', '2021-10-28 08:39:44', '2021-10-28 11:32:51'),
(7, '4d786d8e-46e0-b547-c364-2cc7a5cf0971', 1, 1, NULL, '::1', 'ACTIVE', '2021-10-28 11:33:33', '2021-10-28 11:34:02'),
(8, 'b5ebbfa2-970d-71ab-84f6-a4fae2c12826', 1, 1, NULL, '::1', 'ACTIVE', '2022-08-26 17:59:46', '2022-08-27 19:52:20'),
(9, '52c32ff7-0a5c-0a8e-f065-6d382d46fa3b', 1, 1, NULL, '127.0.0.1', 'ACTIVE', '2022-08-28 02:12:54', '2022-08-28 02:12:54'),
(10, 'd2d1acf6-da35-5ac8-a5ae-3b8183812dce', 1, 1, NULL, '::1', 'ACTIVE', '2022-08-28 02:15:08', '2022-08-28 02:15:08');

-- --------------------------------------------------------

--
-- Table structure for table `user_token`
--

CREATE TABLE `user_token` (
  `user_token_id` int(11) NOT NULL,
  `access_token` varchar(255) NOT NULL,
  `device_type` enum('web','ios','android') NOT NULL DEFAULT 'web',
  `unique_device_id` varchar(255) NOT NULL,
  `device_id` varchar(255) NOT NULL,
  `is_logged_in` enum('TRUE','FALSE') NOT NULL DEFAULT 'TRUE',
  `user_id` int(11) NOT NULL,
  `app_version` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_users`
--
ALTER TABLE `admin_users`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `batches`
--
ALTER TABLE `batches`
  ADD PRIMARY KEY (`batch_id`);

--
-- Indexes for table `boards`
--
ALTER TABLE `boards`
  ADD PRIMARY KEY (`board_id`);

--
-- Indexes for table `chapters`
--
ALTER TABLE `chapters`
  ADD PRIMARY KEY (`chapter_id`);

--
-- Indexes for table `classes`
--
ALTER TABLE `classes`
  ADD PRIMARY KEY (`class_id`);

--
-- Indexes for table `countries`
--
ALTER TABLE `countries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`course_id`);

--
-- Indexes for table `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `media`
--
ALTER TABLE `media`
  ADD PRIMARY KEY (`media_id`);

--
-- Indexes for table `options`
--
ALTER TABLE `options`
  ADD PRIMARY KEY (`option_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`pay_id`);

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`question_id`);

--
-- Indexes for table `site_logs`
--
ALTER TABLE `site_logs`
  ADD PRIMARY KEY (`site_log_id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`student_id`);

--
-- Indexes for table `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`subject_id`);

--
-- Indexes for table `tests`
--
ALTER TABLE `tests`
  ADD PRIMARY KEY (`test_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `user_login_sessions`
--
ALTER TABLE `user_login_sessions`
  ADD PRIMARY KEY (`user_login_session_id`);

--
-- Indexes for table `user_token`
--
ALTER TABLE `user_token`
  ADD PRIMARY KEY (`user_token_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_users`
--
ALTER TABLE `admin_users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `batches`
--
ALTER TABLE `batches`
  MODIFY `batch_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `boards`
--
ALTER TABLE `boards`
  MODIFY `board_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `chapters`
--
ALTER TABLE `chapters`
  MODIFY `chapter_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `classes`
--
ALTER TABLE `classes`
  MODIFY `class_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `countries`
--
ALTER TABLE `countries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=247;

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `course_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `logs`
--
ALTER TABLE `logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `media`
--
ALTER TABLE `media`
  MODIFY `media_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `options`
--
ALTER TABLE `options`
  MODIFY `option_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `pay_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
  MODIFY `question_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `site_logs`
--
ALTER TABLE `site_logs`
  MODIFY `site_log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `student_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `subject_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `tests`
--
ALTER TABLE `tests`
  MODIFY `test_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `user_login_sessions`
--
ALTER TABLE `user_login_sessions`
  MODIFY `user_login_session_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `user_token`
--
ALTER TABLE `user_token`
  MODIFY `user_token_id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
