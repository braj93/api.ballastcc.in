-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 20, 2022 at 06:22 AM
-- Server version: 10.4.25-MariaDB
-- PHP Version: 7.4.30

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
(1, '0b5398b0-6ea0-240a-bfb9-8bec3e7cbfaf', 'ADMIN', 'Braj', 'yadav', 'admin@admin.com', '25d55ad283aa400af464c76d713c07ad', NULL, '', 'Male', NULL, 'ACTIVE', '2022-11-18 19:01:00', '2022-11-19 17:13:46', '2018-01-08 11:39:07', '2018-01-08 11:39:07'),
(2, '0b5398b0-6ea0-240g-bfb9-8bec3e7dbfaf', 'ADMIN', 'Admin', 'Second', 'ballastcc@gmail.com', '25d55ad283aa400af464c76d713c07ad', NULL, '', 'Male', NULL, 'ACTIVE', '2022-09-10 09:04:03', '2022-09-10 09:05:56', '2018-01-08 11:39:07', '2018-01-08 11:39:07');

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
  `course_id` int(11) DEFAULT NULL,
  `chapter_name` varchar(150) NOT NULL,
  `chapter_summary` varchar(500) DEFAULT NULL,
  `status` enum('ACTIVE','DEACTIVE','DELETED','PENDING','BLOCKED') NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `chapters`
--

INSERT INTO `chapters` (`chapter_id`, `chapter_guid`, `course_id`, `chapter_name`, `chapter_summary`, `status`, `created_at`, `updated_at`) VALUES
(1, 'c4411473-2745-f27b-9020-50a2920d3ff4', 1, 'chemical reactions and equations', '(According to blueprint, 6 marks questions will be asked from this lesson.)', 'ACTIVE', '2022-11-19 18:06:25', '2022-11-19 18:06:25'),
(2, 'fe4c3f75-69fd-617c-8e74-5c54451e4cab', 1, 'acid, bases and salts', 'no discription', 'ACTIVE', '2022-11-19 18:07:34', '2022-11-19 18:07:34'),
(3, '882c5908-c4b8-b1fe-72cb-e8d9ffdb67b4', 1, 'metal and non-metals', 'this is updated description', 'ACTIVE', '2022-11-19 18:08:11', '2022-11-19 18:19:28');

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
-- Table structure for table `correct_answers`
--

CREATE TABLE `correct_answers` (
  `correct_ans_id` int(11) NOT NULL,
  `correct_ans_guid` varchar(40) NOT NULL,
  `question_id` int(11) NOT NULL,
  `answer_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
  `description` text DEFAULT NULL,
  `added_by` int(11) NOT NULL,
  `media` int(11) DEFAULT 1,
  `status` enum('ACTIVE','DELETED','PENDING','BLOCKED') NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`course_id`, `course_guid`, `course_name`, `description`, `added_by`, `media`, `status`, `created_at`, `updated_at`) VALUES
(1, '500fa8da-08a1-9f62-4f8a-c66c612868f7', '10th science (m.p. board)', 'this course blongs to student of class 10th (mm.p. board) for science subject ', 1, 1, 'ACTIVE', '2022-11-19 17:19:47', '2022-11-19 17:28:32');

-- --------------------------------------------------------

--
-- Table structure for table `enrolled_courses`
--

CREATE TABLE `enrolled_courses` (
  `enrolled_id` int(11) NOT NULL,
  `enrolled_guid` varchar(40) NOT NULL,
  `course_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `status` enum('ACTIVE','DEACTIVE','PENDING','DELETE') NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `imp_notices`
--

CREATE TABLE `imp_notices` (
  `notice_id` int(11) NOT NULL,
  `notice_guid` varchar(40) NOT NULL,
  `subject` varchar(300) NOT NULL,
  `notice` text NOT NULL,
  `color` varchar(10) NOT NULL,
  `type` enum('STUDENT','USER','BOTH') NOT NULL,
  `status` enum('ACTIVE','DEACTIVE','DELETED') NOT NULL,
  `exp_date` date NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `imp_notices`
--

INSERT INTO `imp_notices` (`notice_id`, `notice_guid`, `subject`, `notice`, `color`, `type`, `status`, `exp_date`, `created_at`, `updated_at`) VALUES
(2, '73633187-fb2a-5ef2-b6fa-1358f9b5a326', 'this is notice subject', '<p>we are trying to check importent notice</p>', '#FF0000', 'BOTH', 'ACTIVE', '2020-10-19', '2022-10-09 16:09:58', '2022-10-09 16:35:10'),
(3, 'fa889831-8c42-f86d-4644-60d7bfbaad3d', 'this is second  notice subject', 'hello users this is notice for you', '#FF0000', 'USER', 'ACTIVE', '2020-10-19', '2022-10-09 16:26:05', '2022-10-09 16:26:05'),
(4, 'b10a8763-18e8-6c95-9083-eddad67eb085', 'notice for only student', 'hello users this is notice for you', '#FF0000', 'STUDENT', 'ACTIVE', '2020-10-19', '2022-10-09 16:26:32', '2022-10-09 16:26:32'),
(5, '21a48fbf-defd-378f-20ca-948c6f9d3894', 'this is first notice subject from ui', '<h1>here h1 title</h1>\n\n<p>my name is lakhan&nbsp;</p>\n\n<ul>\n	<li>sjdkfdsfsdf</li>\n	<li>dsfsdf</li>\n	<li>dsfdsf</li>\n	<li>sfsdfd</li>\n	<li>fsdfds</li>\n	<li>fsdfsdf</li>\n	<li>\n	<table border=\"1\" cellpadding=\"1\" cellspacing=\"1\" style=\"width:500px\">\n		<tbody>\n			<tr>\n				<td>&nbsp;</td>\n				<td>&nbsp;</td>\n			</tr>\n			<tr>\n				<td>&nbsp;</td>\n				<td>&nbsp;</td>\n			</tr>\n			<tr>\n				<td>&nbsp;</td>\n				<td>&nbsp;</td>\n			</tr>\n		</tbody>\n	</table>\n\n	<p>&nbsp;</p>\n	</li>\n</ul>', '#68e302', 'BOTH', 'ACTIVE', '2022-10-22', '2022-10-09 20:22:10', '2022-10-09 20:22:10'),
(6, '0a39c114-8a2e-78a0-5352-902db0be93e3', 'kfgjhjkdhgf sdjkfskjdfhg', '<p>Notice Detail dfgdfsdfsd</p>', '#1d2ad7', 'STUDENT', 'ACTIVE', '2022-10-21', '2022-10-09 20:26:44', '2022-10-09 20:26:44'),
(7, '227f69ce-74b9-f4df-02be-17e634ce962e', 'fzgrdgdfg', '<p>Notice Detailfdgdfgdfgdfg</p>', '#1d2ad7', 'USER', 'ACTIVE', '2022-10-20', '2022-10-09 20:28:05', '2022-10-09 20:28:05'),
(8, 'f857ee82-62d7-7c05-b0b8-c3dc497f146f', 'dfsdfsdfsfd', '<p>Notice Detailfdgdfgdfgdfg fgvfg</p>', '#1dd4d7', 'USER', 'ACTIVE', '2022-10-14', '2022-10-09 20:28:36', '2022-10-09 20:28:36'),
(9, 'cae9250c-21ac-6541-f903-24894f371b3b', 'dfsdfsdfsfd up 2', '<p>Notice <a href=\"http://ballastcc.com\">Detail </a>dfgdfgdgdg description updated fast</p>\n\n<h2>hello world<span class=\"marker\"> new happy life</span></h2>\n\n<p>dsxjhsejkdfh sfdjhgsdkjfhg dfmgsjfdg sdjfgsdjfhgsd sdgdsjhfgsd sdfjfgsdjfhgsjfdg sfddmsfngsdhjfg dsfgsdfjg</p>\n\n<p>jfhfdhgasdfjh</p>\n\n<p>sdfjhsdfh</p>\n\n<p>dfdkjdsfhj<img alt=\"\" src=\"https://png.pngtree.com/png-vector/20200613/ourmid/pngtree-yellow-cartoon-hand-drawn-megaphone-illustration-png-image_2255152.jpg\" style=\"height:200px; margin-left:200px; margin-right:200px; width:60%\" /></p>', '#1dd4d7', 'USER', 'DEACTIVE', '2022-10-14', '2022-10-09 20:36:24', '2022-10-14 18:00:26');

-- --------------------------------------------------------

--
-- Table structure for table `lessons`
--

CREATE TABLE `lessons` (
  `lesson_id` int(11) NOT NULL,
  `lesson_guid` varchar(40) NOT NULL,
  `lesson_name` varchar(200) NOT NULL,
  `lesson_summary` varchar(500) NOT NULL,
  `chapter_id` int(11) NOT NULL,
  `status` enum('ACTIVE','DEACTIVE','DELETE','PENDING') NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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

--
-- Dumping data for table `logs`
--

INSERT INTO `logs` (`id`, `uri`, `method`, `params`, `api_key`, `ip_address`, `time`, `rtime`, `authorized`, `response_code`) VALUES
(1, 'api/admin/course/courses_list', 'post', '{\"session_key\":\"3ae705ed-3ae9-1d04-f101-d398469e16eb\",\"Accept\":\"application\\/json\",\"Content-Type\":\"application\\/json\",\"User-Agent\":\"PostmanRuntime\\/7.29.2\",\"Postman-Token\":\"f6c35c72-4346-4162-a5ee-5702680abb67\",\"Host\":\"localhost\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Connection\":\"keep-alive\",\"Content-Length\":\"71\",\"pagination\":{\"offset\":0,\"limit\":10}}', '', '::1', 1668798016, 0.0904989, '0', 401),
(2, 'api/admin/auth/login', 'post', '{\"Accept\":\"application\\/json\",\"Content-Type\":\"application\\/json\",\"User-Agent\":\"PostmanRuntime\\/7.29.2\",\"Postman-Token\":\"8d0ca7fc-1b2f-48dd-9f89-c0914fbdecda\",\"Host\":\"localhost\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Connection\":\"keep-alive\",\"Content-Length\":\"101\",\"email\":\"admin@second.com\",\"device_type\":\"web_browser\",\"password\":\"12345678\"}', '', '::1', 1668798026, 0.027416, '1', 403),
(3, 'api/admin/auth/login', 'post', '{\"Accept\":\"application\\/json\",\"Content-Type\":\"application\\/json\",\"User-Agent\":\"PostmanRuntime\\/7.29.2\",\"Postman-Token\":\"3cdbf3d9-136c-45d8-831e-5feb92d1e346\",\"Host\":\"localhost\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Connection\":\"keep-alive\",\"Content-Length\":\"100\",\"email\":\"admin@admin.com\",\"device_type\":\"web_browser\",\"password\":\"12345678\"}', '', '::1', 1668798060, 0.0339069, '1', 200),
(4, 'api/admin/course/courses_list', 'post', '{\"session_key\":\"c36f48a0-9816-b5a8-3f33-ef300055f78a\",\"Accept\":\"application\\/json\",\"Content-Type\":\"application\\/json\",\"User-Agent\":\"PostmanRuntime\\/7.29.2\",\"Postman-Token\":\"36e28a54-c88e-4e2b-af8a-4076845e75fd\",\"Host\":\"localhost\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Connection\":\"keep-alive\",\"Content-Length\":\"71\",\"pagination\":{\"offset\":0,\"limit\":10}}', 'c36f48a0-9816-b5a8-3f33-ef300055f78a', '::1', 1668798075, 0.0142422, '1', 200),
(5, 'api/admin/tests/test_list', 'post', '{\"Accept\":\"application\\/json\",\"Content-Type\":\"application\\/json\",\"session_key\":\"b5ebbfa2-970d-71ab-84f6-a4fae2c12826\",\"User-Agent\":\"PostmanRuntime\\/7.29.2\",\"Postman-Token\":\"f42a6bb6-f4a0-44f3-b530-db5ecd050af6\",\"Host\":\"localhost\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Connection\":\"keep-alive\",\"Content-Length\":\"177\",\"subject_id\":\"291166ae-8342-87b7-24b1-43d7486e42fd\",\"subject_name\":\"science2\",\"course_id\":\"ac85ab5c-f4fa-5778-1440-d4ee3cf52433\",\"status\":\"ACTIVE\"}', '', '::1', 1668798252, 0.03951, '0', 401),
(6, 'api/admin/tests/test_list', 'post', '{\"Accept\":\"application\\/json\",\"Content-Type\":\"application\\/json\",\"session_key\":\"c36f48a0-9816-b5a8-3f33-ef300055f78a\",\"User-Agent\":\"PostmanRuntime\\/7.29.2\",\"Postman-Token\":\"afd52c3c-0b61-4642-b831-6d1c06c8e08a\",\"Host\":\"localhost\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Connection\":\"keep-alive\",\"Content-Length\":\"177\",\"subject_id\":\"291166ae-8342-87b7-24b1-43d7486e42fd\",\"subject_name\":\"science2\",\"course_id\":\"ac85ab5c-f4fa-5778-1440-d4ee3cf52433\",\"status\":\"ACTIVE\"}', 'c36f48a0-9816-b5a8-3f33-ef300055f78a', '::1', 1668798262, 0.01247, '1', 200),
(7, 'api/admin/tests/question_list', 'post', '{\"Accept\":\"application\\/json\",\"Content-Type\":\"application\\/json\",\"session_key\":\"c36f48a0-9816-b5a8-3f33-ef300055f78a\",\"User-Agent\":\"PostmanRuntime\\/7.29.2\",\"Postman-Token\":\"6aad3d36-1e4a-4f20-81a9-7b91b3dbef3a\",\"Host\":\"localhost\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Connection\":\"keep-alive\",\"Content-Length\":\"177\",\"subject_id\":\"291166ae-8342-87b7-24b1-43d7486e42fd\",\"subject_name\":\"science2\",\"course_id\":\"ac85ab5c-f4fa-5778-1440-d4ee3cf52433\",\"status\":\"ACTIVE\"}', 'c36f48a0-9816-b5a8-3f33-ef300055f78a', '::1', 1668798301, 0.012517, '1', 200),
(8, 'api/users/login', 'post', '{\"Content-Type\":\"application\\/json\",\"Accept\":\"application\\/json\",\"Content-Length\":\"49\",\"User-Agent\":\"node-fetch\\/1.0 (+https:\\/\\/github.com\\/bitinn\\/node-fetch)\",\"Accept-Encoding\":\"gzip,deflate\",\"Host\":\"localhost\",\"Connection\":\"keep-alive\",\"email\":\"admin@admin.com\",\"password\":\"12345678\"}', '', '127.0.0.1', 1668798441, 0.0533819, '1', 200),
(9, 'api/admin/course/courses_list', 'post', '{\"Host\":\"localhost\",\"Connection\":\"keep-alive\",\"Content-Length\":\"106\",\"sec-ch-ua\":\"\\\"Google Chrome\\\";v=\\\"107\\\", \\\"Chromium\\\";v=\\\"107\\\", \\\"Not=A?Brand\\\";v=\\\"24\\\"\",\"content-Type\":\"application\\/json\",\"session_key\":\"276def3e-6d22-ca4e-72bc-ed63abd1f2f9\",\"sec-ch-ua-mobile\":\"?0\",\"User-Agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/107.0.0.0 Safari\\/537.36\",\"sec-ch-ua-platform\":\"\\\"Windows\\\"\",\"Accept\":\"*\\/*\",\"Origin\":\"http:\\/\\/localhost:3000\",\"Sec-Fetch-Site\":\"same-site\",\"Sec-Fetch-Mode\":\"cors\",\"Sec-Fetch-Dest\":\"empty\",\"Referer\":\"http:\\/\\/localhost:3000\\/\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Accept-Language\":\"en-GB,en-US;q=0.9,en;q=0.8,hi;q=0.7\",\"keyword\":\"\",\"pagination\":{\"offset\":0,\"limit\":10},\"sort_by\":{\"column_name\":\"\",\"order_by\":\"\"},\"filter\":\"\"}', '276def3e-6d22-ca4e-72bc-ed63abd1f2f9', '::1', 1668798454, 0.0189312, '1', 200),
(10, 'api/common/courses_list', 'post', '{\"Host\":\"localhost\",\"Connection\":\"keep-alive\",\"Content-Length\":\"106\",\"sec-ch-ua\":\"\\\"Google Chrome\\\";v=\\\"107\\\", \\\"Chromium\\\";v=\\\"107\\\", \\\"Not=A?Brand\\\";v=\\\"24\\\"\",\"content-Type\":\"application\\/json\",\"session_key\":\"undefined\",\"sec-ch-ua-mobile\":\"?0\",\"User-Agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/107.0.0.0 Safari\\/537.36\",\"sec-ch-ua-platform\":\"\\\"Windows\\\"\",\"Accept\":\"*\\/*\",\"Origin\":\"http:\\/\\/localhost:3000\",\"Sec-Fetch-Site\":\"same-site\",\"Sec-Fetch-Mode\":\"cors\",\"Sec-Fetch-Dest\":\"empty\",\"Referer\":\"http:\\/\\/localhost:3000\\/\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Accept-Language\":\"en-US,en;q=0.9,hi;q=0.8\",\"keyword\":\"\",\"pagination\":{\"offset\":0,\"limit\":10},\"sort_by\":{\"column_name\":\"\",\"order_by\":\"\"},\"filter\":\"\"}', '', '::1', 1668798494, 0.0247428, '1', 200),
(11, 'api/common/courses_list', 'post', '{\"Host\":\"localhost\",\"Connection\":\"keep-alive\",\"Content-Length\":\"106\",\"sec-ch-ua\":\"\\\"Google Chrome\\\";v=\\\"107\\\", \\\"Chromium\\\";v=\\\"107\\\", \\\"Not=A?Brand\\\";v=\\\"24\\\"\",\"content-Type\":\"application\\/json\",\"session_key\":\"undefined\",\"sec-ch-ua-mobile\":\"?0\",\"User-Agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/107.0.0.0 Safari\\/537.36\",\"sec-ch-ua-platform\":\"\\\"Windows\\\"\",\"Accept\":\"*\\/*\",\"Origin\":\"http:\\/\\/localhost:3000\",\"Sec-Fetch-Site\":\"same-site\",\"Sec-Fetch-Mode\":\"cors\",\"Sec-Fetch-Dest\":\"empty\",\"Referer\":\"http:\\/\\/localhost:3000\\/\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Accept-Language\":\"en-US,en;q=0.9,hi;q=0.8\",\"keyword\":\"\",\"pagination\":{\"offset\":0,\"limit\":10},\"sort_by\":{\"column_name\":\"\",\"order_by\":\"\"},\"filter\":\"\"}', '', '::1', 1668798494, 0.00960112, '1', 200),
(12, 'api/common/courses_list', 'post', '{\"Host\":\"localhost\",\"Connection\":\"keep-alive\",\"Content-Length\":\"157\",\"sec-ch-ua\":\"\\\"Google Chrome\\\";v=\\\"107\\\", \\\"Chromium\\\";v=\\\"107\\\", \\\"Not=A?Brand\\\";v=\\\"24\\\"\",\"content-Type\":\"application\\/json\",\"session_key\":\"undefined\",\"sec-ch-ua-mobile\":\"?0\",\"User-Agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/107.0.0.0 Safari\\/537.36\",\"sec-ch-ua-platform\":\"\\\"Windows\\\"\",\"Accept\":\"*\\/*\",\"Origin\":\"http:\\/\\/localhost:3000\",\"Sec-Fetch-Site\":\"same-site\",\"Sec-Fetch-Mode\":\"cors\",\"Sec-Fetch-Dest\":\"empty\",\"Referer\":\"http:\\/\\/localhost:3000\\/\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Accept-Language\":\"en-US,en;q=0.9,hi;q=0.8\",\"keyword\":\"\",\"pagination\":{\"offset\":0,\"limit\":10},\"sort_by\":{\"column_name\":\"\",\"order_by\":\"\"},\"filter\":\"\",\"course_id\":\"abc3241a-113e-3b07-e382-c4ef978c357d\"}', '', '::1', 1668798501, 0.00746489, '1', 200),
(13, 'api/common/courses_list', 'post', '{\"Host\":\"localhost\",\"Connection\":\"keep-alive\",\"Content-Length\":\"157\",\"sec-ch-ua\":\"\\\"Google Chrome\\\";v=\\\"107\\\", \\\"Chromium\\\";v=\\\"107\\\", \\\"Not=A?Brand\\\";v=\\\"24\\\"\",\"content-Type\":\"application\\/json\",\"session_key\":\"undefined\",\"sec-ch-ua-mobile\":\"?0\",\"User-Agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/107.0.0.0 Safari\\/537.36\",\"sec-ch-ua-platform\":\"\\\"Windows\\\"\",\"Accept\":\"*\\/*\",\"Origin\":\"http:\\/\\/localhost:3000\",\"Sec-Fetch-Site\":\"same-site\",\"Sec-Fetch-Mode\":\"cors\",\"Sec-Fetch-Dest\":\"empty\",\"Referer\":\"http:\\/\\/localhost:3000\\/\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Accept-Language\":\"en-US,en;q=0.9,hi;q=0.8\",\"keyword\":\"\",\"pagination\":{\"offset\":0,\"limit\":10},\"sort_by\":{\"column_name\":\"\",\"order_by\":\"\"},\"filter\":\"\",\"course_id\":\"abc3241a-113e-3b07-e382-c4ef978c357d\"}', '', '::1', 1668798501, 0.0277832, '1', 200),
(14, 'api/common/courses_list', 'post', '{\"Host\":\"localhost\",\"Connection\":\"keep-alive\",\"Content-Length\":\"106\",\"sec-ch-ua\":\"\\\"Google Chrome\\\";v=\\\"107\\\", \\\"Chromium\\\";v=\\\"107\\\", \\\"Not=A?Brand\\\";v=\\\"24\\\"\",\"content-Type\":\"application\\/json\",\"session_key\":\"undefined\",\"sec-ch-ua-mobile\":\"?0\",\"User-Agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/107.0.0.0 Safari\\/537.36\",\"sec-ch-ua-platform\":\"\\\"Windows\\\"\",\"Accept\":\"*\\/*\",\"Origin\":\"http:\\/\\/localhost:3000\",\"Sec-Fetch-Site\":\"same-site\",\"Sec-Fetch-Mode\":\"cors\",\"Sec-Fetch-Dest\":\"empty\",\"Referer\":\"http:\\/\\/localhost:3000\\/\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Accept-Language\":\"en-US,en;q=0.9,hi;q=0.8\",\"keyword\":\"\",\"pagination\":{\"offset\":0,\"limit\":10},\"sort_by\":{\"column_name\":\"\",\"order_by\":\"\"},\"filter\":\"\"}', '', '::1', 1668798542, 0.0101521, '1', 200),
(15, 'api/common/courses_list', 'post', '{\"Host\":\"localhost\",\"Connection\":\"keep-alive\",\"Content-Length\":\"106\",\"sec-ch-ua\":\"\\\"Google Chrome\\\";v=\\\"107\\\", \\\"Chromium\\\";v=\\\"107\\\", \\\"Not=A?Brand\\\";v=\\\"24\\\"\",\"content-Type\":\"application\\/json\",\"session_key\":\"undefined\",\"sec-ch-ua-mobile\":\"?0\",\"User-Agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/107.0.0.0 Safari\\/537.36\",\"sec-ch-ua-platform\":\"\\\"Windows\\\"\",\"Accept\":\"*\\/*\",\"Origin\":\"http:\\/\\/localhost:3000\",\"Sec-Fetch-Site\":\"same-site\",\"Sec-Fetch-Mode\":\"cors\",\"Sec-Fetch-Dest\":\"empty\",\"Referer\":\"http:\\/\\/localhost:3000\\/\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Accept-Language\":\"en-US,en;q=0.9,hi;q=0.8\",\"keyword\":\"\",\"pagination\":{\"offset\":0,\"limit\":10},\"sort_by\":{\"column_name\":\"\",\"order_by\":\"\"},\"filter\":\"\"}', '', '::1', 1668798542, 0.0259891, '1', 200),
(16, 'api/admin/course/courses_list', 'post', '{\"Host\":\"localhost\",\"Connection\":\"keep-alive\",\"Content-Length\":\"106\",\"sec-ch-ua\":\"\\\"Google Chrome\\\";v=\\\"107\\\", \\\"Chromium\\\";v=\\\"107\\\", \\\"Not=A?Brand\\\";v=\\\"24\\\"\",\"content-Type\":\"application\\/json\",\"session_key\":\"276def3e-6d22-ca4e-72bc-ed63abd1f2f9\",\"sec-ch-ua-mobile\":\"?0\",\"User-Agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/107.0.0.0 Safari\\/537.36\",\"sec-ch-ua-platform\":\"\\\"Windows\\\"\",\"Accept\":\"*\\/*\",\"Origin\":\"http:\\/\\/localhost:3000\",\"Sec-Fetch-Site\":\"same-site\",\"Sec-Fetch-Mode\":\"cors\",\"Sec-Fetch-Dest\":\"empty\",\"Referer\":\"http:\\/\\/localhost:3000\\/\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Accept-Language\":\"en-GB,en-US;q=0.9,en;q=0.8,hi;q=0.7\",\"keyword\":\"\",\"pagination\":{\"offset\":0,\"limit\":10},\"sort_by\":{\"column_name\":\"\",\"order_by\":\"\"},\"filter\":\"\"}', '276def3e-6d22-ca4e-72bc-ed63abd1f2f9', '::1', 1668798549, 0.014276, '1', 200),
(17, 'api/admin/course/courses_list', 'post', '{\"Host\":\"localhost\",\"Connection\":\"keep-alive\",\"Content-Length\":\"106\",\"sec-ch-ua\":\"\\\"Google Chrome\\\";v=\\\"107\\\", \\\"Chromium\\\";v=\\\"107\\\", \\\"Not=A?Brand\\\";v=\\\"24\\\"\",\"content-Type\":\"application\\/json\",\"session_key\":\"276def3e-6d22-ca4e-72bc-ed63abd1f2f9\",\"sec-ch-ua-mobile\":\"?0\",\"User-Agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/107.0.0.0 Safari\\/537.36\",\"sec-ch-ua-platform\":\"\\\"Windows\\\"\",\"Accept\":\"*\\/*\",\"Origin\":\"http:\\/\\/localhost:3000\",\"Sec-Fetch-Site\":\"same-site\",\"Sec-Fetch-Mode\":\"cors\",\"Sec-Fetch-Dest\":\"empty\",\"Referer\":\"http:\\/\\/localhost:3000\\/\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Accept-Language\":\"en-GB,en-US;q=0.9,en;q=0.8,hi;q=0.7\",\"keyword\":\"\",\"pagination\":{\"offset\":0,\"limit\":10},\"sort_by\":{\"column_name\":\"\",\"order_by\":\"\"},\"filter\":\"\"}', '276def3e-6d22-ca4e-72bc-ed63abd1f2f9', '::1', 1668798596, 0.015023, '1', 200),
(18, 'api/admin/course/courses_list', 'post', '{\"Host\":\"localhost\",\"Connection\":\"keep-alive\",\"Content-Length\":\"106\",\"sec-ch-ua\":\"\\\"Google Chrome\\\";v=\\\"107\\\", \\\"Chromium\\\";v=\\\"107\\\", \\\"Not=A?Brand\\\";v=\\\"24\\\"\",\"content-Type\":\"application\\/json\",\"session_key\":\"276def3e-6d22-ca4e-72bc-ed63abd1f2f9\",\"sec-ch-ua-mobile\":\"?0\",\"User-Agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/107.0.0.0 Safari\\/537.36\",\"sec-ch-ua-platform\":\"\\\"Windows\\\"\",\"Accept\":\"*\\/*\",\"Origin\":\"http:\\/\\/localhost:3000\",\"Sec-Fetch-Site\":\"same-site\",\"Sec-Fetch-Mode\":\"cors\",\"Sec-Fetch-Dest\":\"empty\",\"Referer\":\"http:\\/\\/localhost:3000\\/\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Accept-Language\":\"en-GB,en-US;q=0.9,en;q=0.8,hi;q=0.7\",\"keyword\":\"\",\"pagination\":{\"offset\":0,\"limit\":10},\"sort_by\":{\"column_name\":\"\",\"order_by\":\"\"},\"filter\":\"\"}', '276def3e-6d22-ca4e-72bc-ed63abd1f2f9', '::1', 1668798622, 0.0134811, '1', 200),
(19, 'api/admin/course/get_details_by_id', 'post', '{\"session_key\":\"276def3e-6d22-ca4e-72bc-ed63abd1f2f9\",\"Content-Type\":\"application\\/json;charset=utf-8\",\"Accept\":\"*\\/*\",\"Content-Length\":\"52\",\"User-Agent\":\"node-fetch\\/1.0 (+https:\\/\\/github.com\\/bitinn\\/node-fetch)\",\"Accept-Encoding\":\"gzip,deflate\",\"Host\":\"localhost\",\"Connection\":\"keep-alive\",\"course_id\":\"abc3241a-113e-3b07-e382-c4ef978c357d\"}', '276def3e-6d22-ca4e-72bc-ed63abd1f2f9', '127.0.0.1', 1668798635, 0.0167179, '1', 200),
(20, 'api/admin/course/courses_list', 'post', '{\"Host\":\"localhost\",\"Connection\":\"keep-alive\",\"Content-Length\":\"106\",\"sec-ch-ua\":\"\\\"Google Chrome\\\";v=\\\"107\\\", \\\"Chromium\\\";v=\\\"107\\\", \\\"Not=A?Brand\\\";v=\\\"24\\\"\",\"content-Type\":\"application\\/json\",\"session_key\":\"276def3e-6d22-ca4e-72bc-ed63abd1f2f9\",\"sec-ch-ua-mobile\":\"?0\",\"User-Agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/107.0.0.0 Safari\\/537.36\",\"sec-ch-ua-platform\":\"\\\"Windows\\\"\",\"Accept\":\"*\\/*\",\"Origin\":\"http:\\/\\/localhost:3000\",\"Sec-Fetch-Site\":\"same-site\",\"Sec-Fetch-Mode\":\"cors\",\"Sec-Fetch-Dest\":\"empty\",\"Referer\":\"http:\\/\\/localhost:3000\\/\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Accept-Language\":\"en-GB,en-US;q=0.9,en;q=0.8,hi;q=0.7\",\"keyword\":\"\",\"pagination\":{\"offset\":0,\"limit\":10},\"sort_by\":{\"column_name\":\"\",\"order_by\":\"\"},\"filter\":\"\"}', '276def3e-6d22-ca4e-72bc-ed63abd1f2f9', '::1', 1668798635, 0.0105641, '1', 200),
(21, 'api/admin/course/courses_list', 'post', '{\"Host\":\"localhost\",\"Connection\":\"keep-alive\",\"Content-Length\":\"106\",\"sec-ch-ua\":\"\\\"Google Chrome\\\";v=\\\"107\\\", \\\"Chromium\\\";v=\\\"107\\\", \\\"Not=A?Brand\\\";v=\\\"24\\\"\",\"content-Type\":\"application\\/json\",\"session_key\":\"276def3e-6d22-ca4e-72bc-ed63abd1f2f9\",\"sec-ch-ua-mobile\":\"?0\",\"User-Agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/107.0.0.0 Safari\\/537.36\",\"sec-ch-ua-platform\":\"\\\"Windows\\\"\",\"Accept\":\"*\\/*\",\"Origin\":\"http:\\/\\/localhost:3000\",\"Sec-Fetch-Site\":\"same-site\",\"Sec-Fetch-Mode\":\"cors\",\"Sec-Fetch-Dest\":\"empty\",\"Referer\":\"http:\\/\\/localhost:3000\\/\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Accept-Language\":\"en-GB,en-US;q=0.9,en;q=0.8,hi;q=0.7\",\"keyword\":\"\",\"pagination\":{\"offset\":0,\"limit\":10},\"sort_by\":{\"column_name\":\"\",\"order_by\":\"\"},\"filter\":\"\"}', '276def3e-6d22-ca4e-72bc-ed63abd1f2f9', '::1', 1668798635, 0.0268121, '1', 200),
(22, 'api/admin/subject/subject_list_by_course_id', 'post', '{\"Host\":\"localhost\",\"Connection\":\"keep-alive\",\"Content-Length\":\"157\",\"sec-ch-ua\":\"\\\"Google Chrome\\\";v=\\\"107\\\", \\\"Chromium\\\";v=\\\"107\\\", \\\"Not=A?Brand\\\";v=\\\"24\\\"\",\"content-Type\":\"application\\/json\",\"session_key\":\"276def3e-6d22-ca4e-72bc-ed63abd1f2f9\",\"sec-ch-ua-mobile\":\"?0\",\"User-Agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/107.0.0.0 Safari\\/537.36\",\"sec-ch-ua-platform\":\"\\\"Windows\\\"\",\"Accept\":\"*\\/*\",\"Origin\":\"http:\\/\\/localhost:3000\",\"Sec-Fetch-Site\":\"same-site\",\"Sec-Fetch-Mode\":\"cors\",\"Sec-Fetch-Dest\":\"empty\",\"Referer\":\"http:\\/\\/localhost:3000\\/\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Accept-Language\":\"en-GB,en-US;q=0.9,en;q=0.8,hi;q=0.7\",\"keyword\":\"\",\"pagination\":{\"offset\":0,\"limit\":10},\"sort_by\":{\"column_name\":\"\",\"order_by\":\"\"},\"filter\":\"\",\"course_id\":\"abc3241a-113e-3b07-e382-c4ef978c357d\"}', '276def3e-6d22-ca4e-72bc-ed63abd1f2f9', '::1', 1668798635, 0.0210469, '1', 200),
(23, 'api/admin/course/courses_list', 'post', '{\"Host\":\"localhost\",\"Connection\":\"keep-alive\",\"Content-Length\":\"106\",\"sec-ch-ua\":\"\\\"Google Chrome\\\";v=\\\"107\\\", \\\"Chromium\\\";v=\\\"107\\\", \\\"Not=A?Brand\\\";v=\\\"24\\\"\",\"content-Type\":\"application\\/json\",\"session_key\":\"276def3e-6d22-ca4e-72bc-ed63abd1f2f9\",\"sec-ch-ua-mobile\":\"?0\",\"User-Agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/107.0.0.0 Safari\\/537.36\",\"sec-ch-ua-platform\":\"\\\"Windows\\\"\",\"Accept\":\"*\\/*\",\"Origin\":\"http:\\/\\/localhost:3000\",\"Sec-Fetch-Site\":\"same-site\",\"Sec-Fetch-Mode\":\"cors\",\"Sec-Fetch-Dest\":\"empty\",\"Referer\":\"http:\\/\\/localhost:3000\\/\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Accept-Language\":\"en-GB,en-US;q=0.9,en;q=0.8,hi;q=0.7\",\"keyword\":\"\",\"pagination\":{\"offset\":0,\"limit\":10},\"sort_by\":{\"column_name\":\"\",\"order_by\":\"\"},\"filter\":\"\"}', '276def3e-6d22-ca4e-72bc-ed63abd1f2f9', '::1', 1668798668, 0.0251281, '1', 200),
(24, 'api/admin/course/courses_list', 'post', '{\"Host\":\"localhost\",\"Connection\":\"keep-alive\",\"Content-Length\":\"106\",\"sec-ch-ua\":\"\\\"Google Chrome\\\";v=\\\"107\\\", \\\"Chromium\\\";v=\\\"107\\\", \\\"Not=A?Brand\\\";v=\\\"24\\\"\",\"content-Type\":\"application\\/json\",\"session_key\":\"276def3e-6d22-ca4e-72bc-ed63abd1f2f9\",\"sec-ch-ua-mobile\":\"?0\",\"User-Agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/107.0.0.0 Safari\\/537.36\",\"sec-ch-ua-platform\":\"\\\"Windows\\\"\",\"Accept\":\"*\\/*\",\"Origin\":\"http:\\/\\/localhost:3000\",\"Sec-Fetch-Site\":\"same-site\",\"Sec-Fetch-Mode\":\"cors\",\"Sec-Fetch-Dest\":\"empty\",\"Referer\":\"http:\\/\\/localhost:3000\\/\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Accept-Language\":\"en-GB,en-US;q=0.9,en;q=0.8,hi;q=0.7\",\"keyword\":\"\",\"pagination\":{\"offset\":0,\"limit\":10},\"sort_by\":{\"column_name\":\"\",\"order_by\":\"\"},\"filter\":\"\"}', '276def3e-6d22-ca4e-72bc-ed63abd1f2f9', '::1', 1668798668, 0.0123429, '1', 200),
(25, 'api/admin/subject/subject_list_by_course_id', 'post', '{\"Host\":\"localhost\",\"Connection\":\"keep-alive\",\"Content-Length\":\"157\",\"sec-ch-ua\":\"\\\"Google Chrome\\\";v=\\\"107\\\", \\\"Chromium\\\";v=\\\"107\\\", \\\"Not=A?Brand\\\";v=\\\"24\\\"\",\"content-Type\":\"application\\/json\",\"session_key\":\"276def3e-6d22-ca4e-72bc-ed63abd1f2f9\",\"sec-ch-ua-mobile\":\"?0\",\"User-Agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/107.0.0.0 Safari\\/537.36\",\"sec-ch-ua-platform\":\"\\\"Windows\\\"\",\"Accept\":\"*\\/*\",\"Origin\":\"http:\\/\\/localhost:3000\",\"Sec-Fetch-Site\":\"same-site\",\"Sec-Fetch-Mode\":\"cors\",\"Sec-Fetch-Dest\":\"empty\",\"Referer\":\"http:\\/\\/localhost:3000\\/\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Accept-Language\":\"en-GB,en-US;q=0.9,en;q=0.8,hi;q=0.7\",\"keyword\":\"\",\"pagination\":{\"offset\":0,\"limit\":10},\"sort_by\":{\"column_name\":\"\",\"order_by\":\"\"},\"filter\":\"\",\"course_id\":\"abc3241a-113e-3b07-e382-c4ef978c357d\"}', '276def3e-6d22-ca4e-72bc-ed63abd1f2f9', '::1', 1668798668, 0.0112009, '1', 200),
(26, 'api/admin/course/courses_list', 'post', '{\"Host\":\"localhost\",\"Connection\":\"keep-alive\",\"Content-Length\":\"106\",\"sec-ch-ua\":\"\\\"Google Chrome\\\";v=\\\"107\\\", \\\"Chromium\\\";v=\\\"107\\\", \\\"Not=A?Brand\\\";v=\\\"24\\\"\",\"content-Type\":\"application\\/json\",\"session_key\":\"276def3e-6d22-ca4e-72bc-ed63abd1f2f9\",\"sec-ch-ua-mobile\":\"?0\",\"User-Agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/107.0.0.0 Safari\\/537.36\",\"sec-ch-ua-platform\":\"\\\"Windows\\\"\",\"Accept\":\"*\\/*\",\"Origin\":\"http:\\/\\/localhost:3000\",\"Sec-Fetch-Site\":\"same-site\",\"Sec-Fetch-Mode\":\"cors\",\"Sec-Fetch-Dest\":\"empty\",\"Referer\":\"http:\\/\\/localhost:3000\\/\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Accept-Language\":\"en-GB,en-US;q=0.9,en;q=0.8,hi;q=0.7\",\"keyword\":\"\",\"pagination\":{\"offset\":0,\"limit\":10},\"sort_by\":{\"column_name\":\"\",\"order_by\":\"\"},\"filter\":\"\"}', '276def3e-6d22-ca4e-72bc-ed63abd1f2f9', '::1', 1668798727, 0.0171151, '1', 200),
(27, 'api/admin/course/courses_list', 'post', '{\"Host\":\"localhost\",\"Connection\":\"keep-alive\",\"Content-Length\":\"106\",\"sec-ch-ua\":\"\\\"Google Chrome\\\";v=\\\"107\\\", \\\"Chromium\\\";v=\\\"107\\\", \\\"Not=A?Brand\\\";v=\\\"24\\\"\",\"content-Type\":\"application\\/json\",\"session_key\":\"276def3e-6d22-ca4e-72bc-ed63abd1f2f9\",\"sec-ch-ua-mobile\":\"?0\",\"User-Agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/107.0.0.0 Safari\\/537.36\",\"sec-ch-ua-platform\":\"\\\"Windows\\\"\",\"Accept\":\"*\\/*\",\"Origin\":\"http:\\/\\/localhost:3000\",\"Sec-Fetch-Site\":\"same-site\",\"Sec-Fetch-Mode\":\"cors\",\"Sec-Fetch-Dest\":\"empty\",\"Referer\":\"http:\\/\\/localhost:3000\\/\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Accept-Language\":\"en-GB,en-US;q=0.9,en;q=0.8,hi;q=0.7\",\"keyword\":\"\",\"pagination\":{\"offset\":0,\"limit\":10},\"sort_by\":{\"column_name\":\"\",\"order_by\":\"\"},\"filter\":\"\"}', '276def3e-6d22-ca4e-72bc-ed63abd1f2f9', '::1', 1668798727, 0.020128, '1', 200),
(28, 'api/admin/subject/subject_list_by_course_id', 'post', '{\"Host\":\"localhost\",\"Connection\":\"keep-alive\",\"Content-Length\":\"157\",\"sec-ch-ua\":\"\\\"Google Chrome\\\";v=\\\"107\\\", \\\"Chromium\\\";v=\\\"107\\\", \\\"Not=A?Brand\\\";v=\\\"24\\\"\",\"content-Type\":\"application\\/json\",\"session_key\":\"276def3e-6d22-ca4e-72bc-ed63abd1f2f9\",\"sec-ch-ua-mobile\":\"?0\",\"User-Agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/107.0.0.0 Safari\\/537.36\",\"sec-ch-ua-platform\":\"\\\"Windows\\\"\",\"Accept\":\"*\\/*\",\"Origin\":\"http:\\/\\/localhost:3000\",\"Sec-Fetch-Site\":\"same-site\",\"Sec-Fetch-Mode\":\"cors\",\"Sec-Fetch-Dest\":\"empty\",\"Referer\":\"http:\\/\\/localhost:3000\\/\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Accept-Language\":\"en-GB,en-US;q=0.9,en;q=0.8,hi;q=0.7\",\"keyword\":\"\",\"pagination\":{\"offset\":0,\"limit\":10},\"sort_by\":{\"column_name\":\"\",\"order_by\":\"\"},\"filter\":\"\",\"course_id\":\"abc3241a-113e-3b07-e382-c4ef978c357d\"}', '276def3e-6d22-ca4e-72bc-ed63abd1f2f9', '::1', 1668798727, 0.016865, '1', 200),
(29, 'api/admin/course/courses_list', 'post', '{\"Host\":\"localhost\",\"Connection\":\"keep-alive\",\"Content-Length\":\"106\",\"sec-ch-ua\":\"\\\"Google Chrome\\\";v=\\\"107\\\", \\\"Chromium\\\";v=\\\"107\\\", \\\"Not=A?Brand\\\";v=\\\"24\\\"\",\"content-Type\":\"application\\/json\",\"session_key\":\"276def3e-6d22-ca4e-72bc-ed63abd1f2f9\",\"sec-ch-ua-mobile\":\"?0\",\"User-Agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/107.0.0.0 Safari\\/537.36\",\"sec-ch-ua-platform\":\"\\\"Windows\\\"\",\"Accept\":\"*\\/*\",\"Origin\":\"http:\\/\\/localhost:3000\",\"Sec-Fetch-Site\":\"same-site\",\"Sec-Fetch-Mode\":\"cors\",\"Sec-Fetch-Dest\":\"empty\",\"Referer\":\"http:\\/\\/localhost:3000\\/\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Accept-Language\":\"en-GB,en-US;q=0.9,en;q=0.8,hi;q=0.7\",\"keyword\":\"\",\"pagination\":{\"offset\":0,\"limit\":10},\"sort_by\":{\"column_name\":\"\",\"order_by\":\"\"},\"filter\":\"\"}', '276def3e-6d22-ca4e-72bc-ed63abd1f2f9', '::1', 1668798757, 0.0139279, '1', 200),
(30, 'api/admin/subject/subject_list_by_course_id', 'post', '{\"Host\":\"localhost\",\"Connection\":\"keep-alive\",\"Content-Length\":\"157\",\"sec-ch-ua\":\"\\\"Google Chrome\\\";v=\\\"107\\\", \\\"Chromium\\\";v=\\\"107\\\", \\\"Not=A?Brand\\\";v=\\\"24\\\"\",\"content-Type\":\"application\\/json\",\"session_key\":\"276def3e-6d22-ca4e-72bc-ed63abd1f2f9\",\"sec-ch-ua-mobile\":\"?0\",\"User-Agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/107.0.0.0 Safari\\/537.36\",\"sec-ch-ua-platform\":\"\\\"Windows\\\"\",\"Accept\":\"*\\/*\",\"Origin\":\"http:\\/\\/localhost:3000\",\"Sec-Fetch-Site\":\"same-site\",\"Sec-Fetch-Mode\":\"cors\",\"Sec-Fetch-Dest\":\"empty\",\"Referer\":\"http:\\/\\/localhost:3000\\/\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Accept-Language\":\"en-GB,en-US;q=0.9,en;q=0.8,hi;q=0.7\",\"keyword\":\"\",\"pagination\":{\"offset\":0,\"limit\":10},\"sort_by\":{\"column_name\":\"\",\"order_by\":\"\"},\"filter\":\"\",\"course_id\":\"abc3241a-113e-3b07-e382-c4ef978c357d\"}', '276def3e-6d22-ca4e-72bc-ed63abd1f2f9', '::1', 1668798757, 0.0115988, '1', 200),
(31, 'api/admin/course/courses_list', 'post', '{\"Host\":\"localhost\",\"Connection\":\"keep-alive\",\"Content-Length\":\"106\",\"sec-ch-ua\":\"\\\"Google Chrome\\\";v=\\\"107\\\", \\\"Chromium\\\";v=\\\"107\\\", \\\"Not=A?Brand\\\";v=\\\"24\\\"\",\"content-Type\":\"application\\/json\",\"session_key\":\"276def3e-6d22-ca4e-72bc-ed63abd1f2f9\",\"sec-ch-ua-mobile\":\"?0\",\"User-Agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/107.0.0.0 Safari\\/537.36\",\"sec-ch-ua-platform\":\"\\\"Windows\\\"\",\"Accept\":\"*\\/*\",\"Origin\":\"http:\\/\\/localhost:3000\",\"Sec-Fetch-Site\":\"same-site\",\"Sec-Fetch-Mode\":\"cors\",\"Sec-Fetch-Dest\":\"empty\",\"Referer\":\"http:\\/\\/localhost:3000\\/\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Accept-Language\":\"en-GB,en-US;q=0.9,en;q=0.8,hi;q=0.7\",\"keyword\":\"\",\"pagination\":{\"offset\":0,\"limit\":10},\"sort_by\":{\"column_name\":\"\",\"order_by\":\"\"},\"filter\":\"\"}', '276def3e-6d22-ca4e-72bc-ed63abd1f2f9', '::1', 1668798757, 0.012758, '1', 200),
(32, 'api/admin/course/courses_list', 'post', '{\"Host\":\"localhost\",\"Connection\":\"keep-alive\",\"Content-Length\":\"106\",\"sec-ch-ua\":\"\\\"Google Chrome\\\";v=\\\"107\\\", \\\"Chromium\\\";v=\\\"107\\\", \\\"Not=A?Brand\\\";v=\\\"24\\\"\",\"content-Type\":\"application\\/json\",\"session_key\":\"276def3e-6d22-ca4e-72bc-ed63abd1f2f9\",\"sec-ch-ua-mobile\":\"?0\",\"User-Agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/107.0.0.0 Safari\\/537.36\",\"sec-ch-ua-platform\":\"\\\"Windows\\\"\",\"Accept\":\"*\\/*\",\"Origin\":\"http:\\/\\/localhost:3000\",\"Sec-Fetch-Site\":\"same-site\",\"Sec-Fetch-Mode\":\"cors\",\"Sec-Fetch-Dest\":\"empty\",\"Referer\":\"http:\\/\\/localhost:3000\\/\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Accept-Language\":\"en-GB,en-US;q=0.9,en;q=0.8,hi;q=0.7\",\"keyword\":\"\",\"pagination\":{\"offset\":0,\"limit\":10},\"sort_by\":{\"column_name\":\"\",\"order_by\":\"\"},\"filter\":\"\"}', '276def3e-6d22-ca4e-72bc-ed63abd1f2f9', '::1', 1668798789, 0.0180199, '1', 200),
(33, 'api/admin/course/courses_list', 'post', '{\"Host\":\"localhost\",\"Connection\":\"keep-alive\",\"Content-Length\":\"106\",\"sec-ch-ua\":\"\\\"Google Chrome\\\";v=\\\"107\\\", \\\"Chromium\\\";v=\\\"107\\\", \\\"Not=A?Brand\\\";v=\\\"24\\\"\",\"content-Type\":\"application\\/json\",\"session_key\":\"276def3e-6d22-ca4e-72bc-ed63abd1f2f9\",\"sec-ch-ua-mobile\":\"?0\",\"User-Agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/107.0.0.0 Safari\\/537.36\",\"sec-ch-ua-platform\":\"\\\"Windows\\\"\",\"Accept\":\"*\\/*\",\"Origin\":\"http:\\/\\/localhost:3000\",\"Sec-Fetch-Site\":\"same-site\",\"Sec-Fetch-Mode\":\"cors\",\"Sec-Fetch-Dest\":\"empty\",\"Referer\":\"http:\\/\\/localhost:3000\\/\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Accept-Language\":\"en-GB,en-US;q=0.9,en;q=0.8,hi;q=0.7\",\"keyword\":\"\",\"pagination\":{\"offset\":0,\"limit\":10},\"sort_by\":{\"column_name\":\"\",\"order_by\":\"\"},\"filter\":\"\"}', '276def3e-6d22-ca4e-72bc-ed63abd1f2f9', '::1', 1668798789, 0.0122941, '1', 200),
(34, 'api/admin/subject/subject_list_by_course_id', 'post', '{\"Host\":\"localhost\",\"Connection\":\"keep-alive\",\"Content-Length\":\"157\",\"sec-ch-ua\":\"\\\"Google Chrome\\\";v=\\\"107\\\", \\\"Chromium\\\";v=\\\"107\\\", \\\"Not=A?Brand\\\";v=\\\"24\\\"\",\"content-Type\":\"application\\/json\",\"session_key\":\"276def3e-6d22-ca4e-72bc-ed63abd1f2f9\",\"sec-ch-ua-mobile\":\"?0\",\"User-Agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/107.0.0.0 Safari\\/537.36\",\"sec-ch-ua-platform\":\"\\\"Windows\\\"\",\"Accept\":\"*\\/*\",\"Origin\":\"http:\\/\\/localhost:3000\",\"Sec-Fetch-Site\":\"same-site\",\"Sec-Fetch-Mode\":\"cors\",\"Sec-Fetch-Dest\":\"empty\",\"Referer\":\"http:\\/\\/localhost:3000\\/\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Accept-Language\":\"en-GB,en-US;q=0.9,en;q=0.8,hi;q=0.7\",\"keyword\":\"\",\"pagination\":{\"offset\":0,\"limit\":10},\"sort_by\":{\"column_name\":\"\",\"order_by\":\"\"},\"filter\":\"\",\"course_id\":\"abc3241a-113e-3b07-e382-c4ef978c357d\"}', '276def3e-6d22-ca4e-72bc-ed63abd1f2f9', '::1', 1668798789, 0.0116971, '1', 200),
(35, 'api/admin/subject/subject_list_by_course_id', 'post', '{\"Host\":\"localhost\",\"Connection\":\"keep-alive\",\"Content-Length\":\"157\",\"sec-ch-ua\":\"\\\"Google Chrome\\\";v=\\\"107\\\", \\\"Chromium\\\";v=\\\"107\\\", \\\"Not=A?Brand\\\";v=\\\"24\\\"\",\"content-Type\":\"application\\/json\",\"session_key\":\"276def3e-6d22-ca4e-72bc-ed63abd1f2f9\",\"sec-ch-ua-mobile\":\"?0\",\"User-Agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/107.0.0.0 Safari\\/537.36\",\"sec-ch-ua-platform\":\"\\\"Windows\\\"\",\"Accept\":\"*\\/*\",\"Origin\":\"http:\\/\\/localhost:3000\",\"Sec-Fetch-Site\":\"same-site\",\"Sec-Fetch-Mode\":\"cors\",\"Sec-Fetch-Dest\":\"empty\",\"Referer\":\"http:\\/\\/localhost:3000\\/\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Accept-Language\":\"en-GB,en-US;q=0.9,en;q=0.8,hi;q=0.7\",\"keyword\":\"\",\"pagination\":{\"offset\":0,\"limit\":10},\"sort_by\":{\"column_name\":\"\",\"order_by\":\"\"},\"filter\":\"\",\"course_id\":\"abc3241a-113e-3b07-e382-c4ef978c357d\"}', '276def3e-6d22-ca4e-72bc-ed63abd1f2f9', '::1', 1668798805, 0.011097, '1', 200),
(36, 'api/admin/course/courses_list', 'post', '{\"Host\":\"localhost\",\"Connection\":\"keep-alive\",\"Content-Length\":\"106\",\"sec-ch-ua\":\"\\\"Google Chrome\\\";v=\\\"107\\\", \\\"Chromium\\\";v=\\\"107\\\", \\\"Not=A?Brand\\\";v=\\\"24\\\"\",\"content-Type\":\"application\\/json\",\"session_key\":\"276def3e-6d22-ca4e-72bc-ed63abd1f2f9\",\"sec-ch-ua-mobile\":\"?0\",\"User-Agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/107.0.0.0 Safari\\/537.36\",\"sec-ch-ua-platform\":\"\\\"Windows\\\"\",\"Accept\":\"*\\/*\",\"Origin\":\"http:\\/\\/localhost:3000\",\"Sec-Fetch-Site\":\"same-site\",\"Sec-Fetch-Mode\":\"cors\",\"Sec-Fetch-Dest\":\"empty\",\"Referer\":\"http:\\/\\/localhost:3000\\/\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Accept-Language\":\"en-GB,en-US;q=0.9,en;q=0.8,hi;q=0.7\",\"keyword\":\"\",\"pagination\":{\"offset\":0,\"limit\":10},\"sort_by\":{\"column_name\":\"\",\"order_by\":\"\"},\"filter\":\"\"}', '276def3e-6d22-ca4e-72bc-ed63abd1f2f9', '::1', 1668798805, 0.0138948, '1', 200),
(37, 'api/admin/course/courses_list', 'post', '{\"Host\":\"localhost\",\"Connection\":\"keep-alive\",\"Content-Length\":\"106\",\"sec-ch-ua\":\"\\\"Google Chrome\\\";v=\\\"107\\\", \\\"Chromium\\\";v=\\\"107\\\", \\\"Not=A?Brand\\\";v=\\\"24\\\"\",\"content-Type\":\"application\\/json\",\"session_key\":\"276def3e-6d22-ca4e-72bc-ed63abd1f2f9\",\"sec-ch-ua-mobile\":\"?0\",\"User-Agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/107.0.0.0 Safari\\/537.36\",\"sec-ch-ua-platform\":\"\\\"Windows\\\"\",\"Accept\":\"*\\/*\",\"Origin\":\"http:\\/\\/localhost:3000\",\"Sec-Fetch-Site\":\"same-site\",\"Sec-Fetch-Mode\":\"cors\",\"Sec-Fetch-Dest\":\"empty\",\"Referer\":\"http:\\/\\/localhost:3000\\/\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Accept-Language\":\"en-GB,en-US;q=0.9,en;q=0.8,hi;q=0.7\",\"keyword\":\"\",\"pagination\":{\"offset\":0,\"limit\":10},\"sort_by\":{\"column_name\":\"\",\"order_by\":\"\"},\"filter\":\"\"}', '276def3e-6d22-ca4e-72bc-ed63abd1f2f9', '::1', 1668798805, 0.010962, '1', 200),
(38, 'api/admin/course/courses_list', 'post', '{\"Host\":\"localhost\",\"Connection\":\"keep-alive\",\"Content-Length\":\"106\",\"sec-ch-ua\":\"\\\"Google Chrome\\\";v=\\\"107\\\", \\\"Chromium\\\";v=\\\"107\\\", \\\"Not=A?Brand\\\";v=\\\"24\\\"\",\"content-Type\":\"application\\/json\",\"session_key\":\"276def3e-6d22-ca4e-72bc-ed63abd1f2f9\",\"sec-ch-ua-mobile\":\"?0\",\"User-Agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/107.0.0.0 Safari\\/537.36\",\"sec-ch-ua-platform\":\"\\\"Windows\\\"\",\"Accept\":\"*\\/*\",\"Origin\":\"http:\\/\\/localhost:3000\",\"Sec-Fetch-Site\":\"same-site\",\"Sec-Fetch-Mode\":\"cors\",\"Sec-Fetch-Dest\":\"empty\",\"Referer\":\"http:\\/\\/localhost:3000\\/\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Accept-Language\":\"en-GB,en-US;q=0.9,en;q=0.8,hi;q=0.7\",\"keyword\":\"\",\"pagination\":{\"offset\":0,\"limit\":10},\"sort_by\":{\"column_name\":\"\",\"order_by\":\"\"},\"filter\":\"\"}', '276def3e-6d22-ca4e-72bc-ed63abd1f2f9', '::1', 1668798836, 0.0150051, '1', 200),
(39, 'api/admin/subject/subject_list_by_course_id', 'post', '{\"Host\":\"localhost\",\"Connection\":\"keep-alive\",\"Content-Length\":\"157\",\"sec-ch-ua\":\"\\\"Google Chrome\\\";v=\\\"107\\\", \\\"Chromium\\\";v=\\\"107\\\", \\\"Not=A?Brand\\\";v=\\\"24\\\"\",\"content-Type\":\"application\\/json\",\"session_key\":\"276def3e-6d22-ca4e-72bc-ed63abd1f2f9\",\"sec-ch-ua-mobile\":\"?0\",\"User-Agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/107.0.0.0 Safari\\/537.36\",\"sec-ch-ua-platform\":\"\\\"Windows\\\"\",\"Accept\":\"*\\/*\",\"Origin\":\"http:\\/\\/localhost:3000\",\"Sec-Fetch-Site\":\"same-site\",\"Sec-Fetch-Mode\":\"cors\",\"Sec-Fetch-Dest\":\"empty\",\"Referer\":\"http:\\/\\/localhost:3000\\/\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Accept-Language\":\"en-GB,en-US;q=0.9,en;q=0.8,hi;q=0.7\",\"keyword\":\"\",\"pagination\":{\"offset\":0,\"limit\":10},\"sort_by\":{\"column_name\":\"\",\"order_by\":\"\"},\"filter\":\"\",\"course_id\":\"abc3241a-113e-3b07-e382-c4ef978c357d\"}', '276def3e-6d22-ca4e-72bc-ed63abd1f2f9', '::1', 1668798836, 0.013696, '1', 200),
(40, 'api/admin/course/courses_list', 'post', '{\"Host\":\"localhost\",\"Connection\":\"keep-alive\",\"Content-Length\":\"106\",\"sec-ch-ua\":\"\\\"Google Chrome\\\";v=\\\"107\\\", \\\"Chromium\\\";v=\\\"107\\\", \\\"Not=A?Brand\\\";v=\\\"24\\\"\",\"content-Type\":\"application\\/json\",\"session_key\":\"276def3e-6d22-ca4e-72bc-ed63abd1f2f9\",\"sec-ch-ua-mobile\":\"?0\",\"User-Agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/107.0.0.0 Safari\\/537.36\",\"sec-ch-ua-platform\":\"\\\"Windows\\\"\",\"Accept\":\"*\\/*\",\"Origin\":\"http:\\/\\/localhost:3000\",\"Sec-Fetch-Site\":\"same-site\",\"Sec-Fetch-Mode\":\"cors\",\"Sec-Fetch-Dest\":\"empty\",\"Referer\":\"http:\\/\\/localhost:3000\\/\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Accept-Language\":\"en-GB,en-US;q=0.9,en;q=0.8,hi;q=0.7\",\"keyword\":\"\",\"pagination\":{\"offset\":0,\"limit\":10},\"sort_by\":{\"column_name\":\"\",\"order_by\":\"\"},\"filter\":\"\"}', '276def3e-6d22-ca4e-72bc-ed63abd1f2f9', '::1', 1668798836, 0.015007, '1', 200),
(41, 'api/admin/course/courses_list', 'post', '{\"Host\":\"localhost\",\"Connection\":\"keep-alive\",\"Content-Length\":\"106\",\"sec-ch-ua\":\"\\\"Google Chrome\\\";v=\\\"107\\\", \\\"Chromium\\\";v=\\\"107\\\", \\\"Not=A?Brand\\\";v=\\\"24\\\"\",\"content-Type\":\"application\\/json\",\"session_key\":\"276def3e-6d22-ca4e-72bc-ed63abd1f2f9\",\"sec-ch-ua-mobile\":\"?0\",\"User-Agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/107.0.0.0 Safari\\/537.36\",\"sec-ch-ua-platform\":\"\\\"Windows\\\"\",\"Accept\":\"*\\/*\",\"Origin\":\"http:\\/\\/localhost:3000\",\"Sec-Fetch-Site\":\"same-site\",\"Sec-Fetch-Mode\":\"cors\",\"Sec-Fetch-Dest\":\"empty\",\"Referer\":\"http:\\/\\/localhost:3000\\/\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Accept-Language\":\"en-GB,en-US;q=0.9,en;q=0.8,hi;q=0.7\",\"keyword\":\"\",\"pagination\":{\"offset\":0,\"limit\":10},\"sort_by\":{\"column_name\":\"\",\"order_by\":\"\"},\"filter\":\"\"}', '276def3e-6d22-ca4e-72bc-ed63abd1f2f9', '::1', 1668798873, 0.016253, '1', 200),
(42, 'api/admin/course/courses_list', 'post', '{\"Host\":\"localhost\",\"Connection\":\"keep-alive\",\"Content-Length\":\"106\",\"sec-ch-ua\":\"\\\"Google Chrome\\\";v=\\\"107\\\", \\\"Chromium\\\";v=\\\"107\\\", \\\"Not=A?Brand\\\";v=\\\"24\\\"\",\"content-Type\":\"application\\/json\",\"session_key\":\"276def3e-6d22-ca4e-72bc-ed63abd1f2f9\",\"sec-ch-ua-mobile\":\"?0\",\"User-Agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/107.0.0.0 Safari\\/537.36\",\"sec-ch-ua-platform\":\"\\\"Windows\\\"\",\"Accept\":\"*\\/*\",\"Origin\":\"http:\\/\\/localhost:3000\",\"Sec-Fetch-Site\":\"same-site\",\"Sec-Fetch-Mode\":\"cors\",\"Sec-Fetch-Dest\":\"empty\",\"Referer\":\"http:\\/\\/localhost:3000\\/\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Accept-Language\":\"en-GB,en-US;q=0.9,en;q=0.8,hi;q=0.7\",\"keyword\":\"\",\"pagination\":{\"offset\":0,\"limit\":10},\"sort_by\":{\"column_name\":\"\",\"order_by\":\"\"},\"filter\":\"\"}', '276def3e-6d22-ca4e-72bc-ed63abd1f2f9', '::1', 1668798873, 0.013906, '1', 200),
(43, 'api/admin/subject/subject_list_by_course_id', 'post', '{\"Host\":\"localhost\",\"Connection\":\"keep-alive\",\"Content-Length\":\"157\",\"sec-ch-ua\":\"\\\"Google Chrome\\\";v=\\\"107\\\", \\\"Chromium\\\";v=\\\"107\\\", \\\"Not=A?Brand\\\";v=\\\"24\\\"\",\"content-Type\":\"application\\/json\",\"session_key\":\"276def3e-6d22-ca4e-72bc-ed63abd1f2f9\",\"sec-ch-ua-mobile\":\"?0\",\"User-Agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/107.0.0.0 Safari\\/537.36\",\"sec-ch-ua-platform\":\"\\\"Windows\\\"\",\"Accept\":\"*\\/*\",\"Origin\":\"http:\\/\\/localhost:3000\",\"Sec-Fetch-Site\":\"same-site\",\"Sec-Fetch-Mode\":\"cors\",\"Sec-Fetch-Dest\":\"empty\",\"Referer\":\"http:\\/\\/localhost:3000\\/\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Accept-Language\":\"en-GB,en-US;q=0.9,en;q=0.8,hi;q=0.7\",\"keyword\":\"\",\"pagination\":{\"offset\":0,\"limit\":10},\"sort_by\":{\"column_name\":\"\",\"order_by\":\"\"},\"filter\":\"\",\"course_id\":\"abc3241a-113e-3b07-e382-c4ef978c357d\"}', '276def3e-6d22-ca4e-72bc-ed63abd1f2f9', '::1', 1668798873, 0.0122931, '1', 200),
(44, 'api/admin/subject/subject_list_by_course_id', 'post', '{\"Host\":\"localhost\",\"Connection\":\"keep-alive\",\"Content-Length\":\"157\",\"sec-ch-ua\":\"\\\"Google Chrome\\\";v=\\\"107\\\", \\\"Chromium\\\";v=\\\"107\\\", \\\"Not=A?Brand\\\";v=\\\"24\\\"\",\"content-Type\":\"application\\/json\",\"session_key\":\"276def3e-6d22-ca4e-72bc-ed63abd1f2f9\",\"sec-ch-ua-mobile\":\"?0\",\"User-Agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/107.0.0.0 Safari\\/537.36\",\"sec-ch-ua-platform\":\"\\\"Windows\\\"\",\"Accept\":\"*\\/*\",\"Origin\":\"http:\\/\\/localhost:3000\",\"Sec-Fetch-Site\":\"same-site\",\"Sec-Fetch-Mode\":\"cors\",\"Sec-Fetch-Dest\":\"empty\",\"Referer\":\"http:\\/\\/localhost:3000\\/\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Accept-Language\":\"en-GB,en-US;q=0.9,en;q=0.8,hi;q=0.7\",\"keyword\":\"\",\"pagination\":{\"offset\":0,\"limit\":10},\"sort_by\":{\"column_name\":\"\",\"order_by\":\"\"},\"filter\":\"\",\"course_id\":\"abc3241a-113e-3b07-e382-c4ef978c357d\"}', '276def3e-6d22-ca4e-72bc-ed63abd1f2f9', '::1', 1668798889, 0.0119679, '1', 200),
(45, 'api/admin/course/courses_list', 'post', '{\"Host\":\"localhost\",\"Connection\":\"keep-alive\",\"Content-Length\":\"106\",\"sec-ch-ua\":\"\\\"Google Chrome\\\";v=\\\"107\\\", \\\"Chromium\\\";v=\\\"107\\\", \\\"Not=A?Brand\\\";v=\\\"24\\\"\",\"content-Type\":\"application\\/json\",\"session_key\":\"276def3e-6d22-ca4e-72bc-ed63abd1f2f9\",\"sec-ch-ua-mobile\":\"?0\",\"User-Agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/107.0.0.0 Safari\\/537.36\",\"sec-ch-ua-platform\":\"\\\"Windows\\\"\",\"Accept\":\"*\\/*\",\"Origin\":\"http:\\/\\/localhost:3000\",\"Sec-Fetch-Site\":\"same-site\",\"Sec-Fetch-Mode\":\"cors\",\"Sec-Fetch-Dest\":\"empty\",\"Referer\":\"http:\\/\\/localhost:3000\\/\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Accept-Language\":\"en-GB,en-US;q=0.9,en;q=0.8,hi;q=0.7\",\"keyword\":\"\",\"pagination\":{\"offset\":0,\"limit\":10},\"sort_by\":{\"column_name\":\"\",\"order_by\":\"\"},\"filter\":\"\"}', '276def3e-6d22-ca4e-72bc-ed63abd1f2f9', '::1', 1668798889, 0.0147219, '1', 200),
(46, 'api/admin/course/courses_list', 'post', '{\"Host\":\"localhost\",\"Connection\":\"keep-alive\",\"Content-Length\":\"106\",\"sec-ch-ua\":\"\\\"Google Chrome\\\";v=\\\"107\\\", \\\"Chromium\\\";v=\\\"107\\\", \\\"Not=A?Brand\\\";v=\\\"24\\\"\",\"content-Type\":\"application\\/json\",\"session_key\":\"276def3e-6d22-ca4e-72bc-ed63abd1f2f9\",\"sec-ch-ua-mobile\":\"?0\",\"User-Agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/107.0.0.0 Safari\\/537.36\",\"sec-ch-ua-platform\":\"\\\"Windows\\\"\",\"Accept\":\"*\\/*\",\"Origin\":\"http:\\/\\/localhost:3000\",\"Sec-Fetch-Site\":\"same-site\",\"Sec-Fetch-Mode\":\"cors\",\"Sec-Fetch-Dest\":\"empty\",\"Referer\":\"http:\\/\\/localhost:3000\\/\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Accept-Language\":\"en-GB,en-US;q=0.9,en;q=0.8,hi;q=0.7\",\"keyword\":\"\",\"pagination\":{\"offset\":0,\"limit\":10},\"sort_by\":{\"column_name\":\"\",\"order_by\":\"\"},\"filter\":\"\"}', '276def3e-6d22-ca4e-72bc-ed63abd1f2f9', '::1', 1668798889, 0.01215, '1', 200),
(47, 'api/admin/course/courses_list', 'post', '{\"Host\":\"localhost\",\"Connection\":\"keep-alive\",\"Content-Length\":\"106\",\"sec-ch-ua\":\"\\\"Google Chrome\\\";v=\\\"107\\\", \\\"Chromium\\\";v=\\\"107\\\", \\\"Not=A?Brand\\\";v=\\\"24\\\"\",\"content-Type\":\"application\\/json\",\"session_key\":\"276def3e-6d22-ca4e-72bc-ed63abd1f2f9\",\"sec-ch-ua-mobile\":\"?0\",\"User-Agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/107.0.0.0 Safari\\/537.36\",\"sec-ch-ua-platform\":\"\\\"Windows\\\"\",\"Accept\":\"*\\/*\",\"Origin\":\"http:\\/\\/localhost:3000\",\"Sec-Fetch-Site\":\"same-site\",\"Sec-Fetch-Mode\":\"cors\",\"Sec-Fetch-Dest\":\"empty\",\"Referer\":\"http:\\/\\/localhost:3000\\/\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Accept-Language\":\"en-GB,en-US;q=0.9,en;q=0.8,hi;q=0.7\",\"keyword\":\"\",\"pagination\":{\"offset\":0,\"limit\":10},\"sort_by\":{\"column_name\":\"\",\"order_by\":\"\"},\"filter\":\"\"}', '276def3e-6d22-ca4e-72bc-ed63abd1f2f9', '::1', 1668798900, 0.0119522, '1', 200),
(48, 'api/admin/course/courses_list', 'post', '{\"Host\":\"localhost\",\"Connection\":\"keep-alive\",\"Content-Length\":\"106\",\"sec-ch-ua\":\"\\\"Google Chrome\\\";v=\\\"107\\\", \\\"Chromium\\\";v=\\\"107\\\", \\\"Not=A?Brand\\\";v=\\\"24\\\"\",\"content-Type\":\"application\\/json\",\"session_key\":\"276def3e-6d22-ca4e-72bc-ed63abd1f2f9\",\"sec-ch-ua-mobile\":\"?0\",\"User-Agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/107.0.0.0 Safari\\/537.36\",\"sec-ch-ua-platform\":\"\\\"Windows\\\"\",\"Accept\":\"*\\/*\",\"Origin\":\"http:\\/\\/localhost:3000\",\"Sec-Fetch-Site\":\"same-site\",\"Sec-Fetch-Mode\":\"cors\",\"Sec-Fetch-Dest\":\"empty\",\"Referer\":\"http:\\/\\/localhost:3000\\/\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Accept-Language\":\"en-GB,en-US;q=0.9,en;q=0.8,hi;q=0.7\",\"keyword\":\"\",\"pagination\":{\"offset\":0,\"limit\":10},\"sort_by\":{\"column_name\":\"\",\"order_by\":\"\"},\"filter\":\"\"}', '276def3e-6d22-ca4e-72bc-ed63abd1f2f9', '::1', 1668798900, 0.013566, '1', 200),
(49, 'api/admin/subject/subject_list_by_course_id', 'post', '{\"Host\":\"localhost\",\"Connection\":\"keep-alive\",\"Content-Length\":\"157\",\"sec-ch-ua\":\"\\\"Google Chrome\\\";v=\\\"107\\\", \\\"Chromium\\\";v=\\\"107\\\", \\\"Not=A?Brand\\\";v=\\\"24\\\"\",\"content-Type\":\"application\\/json\",\"session_key\":\"276def3e-6d22-ca4e-72bc-ed63abd1f2f9\",\"sec-ch-ua-mobile\":\"?0\",\"User-Agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/107.0.0.0 Safari\\/537.36\",\"sec-ch-ua-platform\":\"\\\"Windows\\\"\",\"Accept\":\"*\\/*\",\"Origin\":\"http:\\/\\/localhost:3000\",\"Sec-Fetch-Site\":\"same-site\",\"Sec-Fetch-Mode\":\"cors\",\"Sec-Fetch-Dest\":\"empty\",\"Referer\":\"http:\\/\\/localhost:3000\\/\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Accept-Language\":\"en-GB,en-US;q=0.9,en;q=0.8,hi;q=0.7\",\"keyword\":\"\",\"pagination\":{\"offset\":0,\"limit\":10},\"sort_by\":{\"column_name\":\"\",\"order_by\":\"\"},\"filter\":\"\",\"course_id\":\"abc3241a-113e-3b07-e382-c4ef978c357d\"}', '276def3e-6d22-ca4e-72bc-ed63abd1f2f9', '::1', 1668798900, 0.0281138, '1', 200),
(50, 'api/admin/subject/subject_list_by_course_id', 'post', '{\"Host\":\"localhost\",\"Connection\":\"keep-alive\",\"Content-Length\":\"157\",\"sec-ch-ua\":\"\\\"Google Chrome\\\";v=\\\"107\\\", \\\"Chromium\\\";v=\\\"107\\\", \\\"Not=A?Brand\\\";v=\\\"24\\\"\",\"content-Type\":\"application\\/json\",\"session_key\":\"276def3e-6d22-ca4e-72bc-ed63abd1f2f9\",\"sec-ch-ua-mobile\":\"?0\",\"User-Agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/107.0.0.0 Safari\\/537.36\",\"sec-ch-ua-platform\":\"\\\"Windows\\\"\",\"Accept\":\"*\\/*\",\"Origin\":\"http:\\/\\/localhost:3000\",\"Sec-Fetch-Site\":\"same-site\",\"Sec-Fetch-Mode\":\"cors\",\"Sec-Fetch-Dest\":\"empty\",\"Referer\":\"http:\\/\\/localhost:3000\\/\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Accept-Language\":\"en-GB,en-US;q=0.9,en;q=0.8,hi;q=0.7\",\"keyword\":\"\",\"pagination\":{\"offset\":0,\"limit\":10},\"sort_by\":{\"column_name\":\"\",\"order_by\":\"\"},\"filter\":\"\",\"course_id\":\"abc3241a-113e-3b07-e382-c4ef978c357d\"}', '276def3e-6d22-ca4e-72bc-ed63abd1f2f9', '::1', 1668798903, 0.0156438, '1', 200);
INSERT INTO `logs` (`id`, `uri`, `method`, `params`, `api_key`, `ip_address`, `time`, `rtime`, `authorized`, `response_code`) VALUES
(51, 'api/admin/course/courses_list', 'post', '{\"Host\":\"localhost\",\"Connection\":\"keep-alive\",\"Content-Length\":\"106\",\"sec-ch-ua\":\"\\\"Google Chrome\\\";v=\\\"107\\\", \\\"Chromium\\\";v=\\\"107\\\", \\\"Not=A?Brand\\\";v=\\\"24\\\"\",\"content-Type\":\"application\\/json\",\"session_key\":\"276def3e-6d22-ca4e-72bc-ed63abd1f2f9\",\"sec-ch-ua-mobile\":\"?0\",\"User-Agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/107.0.0.0 Safari\\/537.36\",\"sec-ch-ua-platform\":\"\\\"Windows\\\"\",\"Accept\":\"*\\/*\",\"Origin\":\"http:\\/\\/localhost:3000\",\"Sec-Fetch-Site\":\"same-site\",\"Sec-Fetch-Mode\":\"cors\",\"Sec-Fetch-Dest\":\"empty\",\"Referer\":\"http:\\/\\/localhost:3000\\/\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Accept-Language\":\"en-GB,en-US;q=0.9,en;q=0.8,hi;q=0.7\",\"keyword\":\"\",\"pagination\":{\"offset\":0,\"limit\":10},\"sort_by\":{\"column_name\":\"\",\"order_by\":\"\"},\"filter\":\"\"}', '276def3e-6d22-ca4e-72bc-ed63abd1f2f9', '::1', 1668798903, 0.0203531, '1', 200),
(52, 'api/admin/course/courses_list', 'post', '{\"Host\":\"localhost\",\"Connection\":\"keep-alive\",\"Content-Length\":\"106\",\"sec-ch-ua\":\"\\\"Google Chrome\\\";v=\\\"107\\\", \\\"Chromium\\\";v=\\\"107\\\", \\\"Not=A?Brand\\\";v=\\\"24\\\"\",\"content-Type\":\"application\\/json\",\"session_key\":\"276def3e-6d22-ca4e-72bc-ed63abd1f2f9\",\"sec-ch-ua-mobile\":\"?0\",\"User-Agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/107.0.0.0 Safari\\/537.36\",\"sec-ch-ua-platform\":\"\\\"Windows\\\"\",\"Accept\":\"*\\/*\",\"Origin\":\"http:\\/\\/localhost:3000\",\"Sec-Fetch-Site\":\"same-site\",\"Sec-Fetch-Mode\":\"cors\",\"Sec-Fetch-Dest\":\"empty\",\"Referer\":\"http:\\/\\/localhost:3000\\/\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Accept-Language\":\"en-GB,en-US;q=0.9,en;q=0.8,hi;q=0.7\",\"keyword\":\"\",\"pagination\":{\"offset\":0,\"limit\":10},\"sort_by\":{\"column_name\":\"\",\"order_by\":\"\"},\"filter\":\"\"}', '276def3e-6d22-ca4e-72bc-ed63abd1f2f9', '::1', 1668798903, 0.028389, '1', 200),
(53, 'api/admin/course/courses_list', 'post', '{\"Host\":\"localhost\",\"Connection\":\"keep-alive\",\"Content-Length\":\"106\",\"sec-ch-ua\":\"\\\"Google Chrome\\\";v=\\\"107\\\", \\\"Chromium\\\";v=\\\"107\\\", \\\"Not=A?Brand\\\";v=\\\"24\\\"\",\"content-Type\":\"application\\/json\",\"session_key\":\"276def3e-6d22-ca4e-72bc-ed63abd1f2f9\",\"sec-ch-ua-mobile\":\"?0\",\"User-Agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/107.0.0.0 Safari\\/537.36\",\"sec-ch-ua-platform\":\"\\\"Windows\\\"\",\"Accept\":\"*\\/*\",\"Origin\":\"http:\\/\\/localhost:3000\",\"Sec-Fetch-Site\":\"same-site\",\"Sec-Fetch-Mode\":\"cors\",\"Sec-Fetch-Dest\":\"empty\",\"Referer\":\"http:\\/\\/localhost:3000\\/\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Accept-Language\":\"en-GB,en-US;q=0.9,en;q=0.8,hi;q=0.7\",\"keyword\":\"\",\"pagination\":{\"offset\":0,\"limit\":10},\"sort_by\":{\"column_name\":\"\",\"order_by\":\"\"},\"filter\":\"\"}', '276def3e-6d22-ca4e-72bc-ed63abd1f2f9', '::1', 1668798908, 0.0122311, '1', 200),
(54, 'api/admin/course/courses_list', 'post', '{\"Host\":\"localhost\",\"Connection\":\"keep-alive\",\"Content-Length\":\"106\",\"sec-ch-ua\":\"\\\"Google Chrome\\\";v=\\\"107\\\", \\\"Chromium\\\";v=\\\"107\\\", \\\"Not=A?Brand\\\";v=\\\"24\\\"\",\"content-Type\":\"application\\/json\",\"session_key\":\"276def3e-6d22-ca4e-72bc-ed63abd1f2f9\",\"sec-ch-ua-mobile\":\"?0\",\"User-Agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/107.0.0.0 Safari\\/537.36\",\"sec-ch-ua-platform\":\"\\\"Windows\\\"\",\"Accept\":\"*\\/*\",\"Origin\":\"http:\\/\\/localhost:3000\",\"Sec-Fetch-Site\":\"same-site\",\"Sec-Fetch-Mode\":\"cors\",\"Sec-Fetch-Dest\":\"empty\",\"Referer\":\"http:\\/\\/localhost:3000\\/\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Accept-Language\":\"en-GB,en-US;q=0.9,en;q=0.8,hi;q=0.7\",\"keyword\":\"\",\"pagination\":{\"offset\":0,\"limit\":10},\"sort_by\":{\"column_name\":\"\",\"order_by\":\"\"},\"filter\":\"\"}', '276def3e-6d22-ca4e-72bc-ed63abd1f2f9', '::1', 1668798908, 0.013545, '1', 200),
(55, 'api/admin/subject/subject_list_by_course_id', 'post', '{\"Host\":\"localhost\",\"Connection\":\"keep-alive\",\"Content-Length\":\"157\",\"sec-ch-ua\":\"\\\"Google Chrome\\\";v=\\\"107\\\", \\\"Chromium\\\";v=\\\"107\\\", \\\"Not=A?Brand\\\";v=\\\"24\\\"\",\"content-Type\":\"application\\/json\",\"session_key\":\"276def3e-6d22-ca4e-72bc-ed63abd1f2f9\",\"sec-ch-ua-mobile\":\"?0\",\"User-Agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/107.0.0.0 Safari\\/537.36\",\"sec-ch-ua-platform\":\"\\\"Windows\\\"\",\"Accept\":\"*\\/*\",\"Origin\":\"http:\\/\\/localhost:3000\",\"Sec-Fetch-Site\":\"same-site\",\"Sec-Fetch-Mode\":\"cors\",\"Sec-Fetch-Dest\":\"empty\",\"Referer\":\"http:\\/\\/localhost:3000\\/\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Accept-Language\":\"en-GB,en-US;q=0.9,en;q=0.8,hi;q=0.7\",\"keyword\":\"\",\"pagination\":{\"offset\":0,\"limit\":10},\"sort_by\":{\"column_name\":\"\",\"order_by\":\"\"},\"filter\":\"\",\"course_id\":\"abc3241a-113e-3b07-e382-c4ef978c357d\"}', '276def3e-6d22-ca4e-72bc-ed63abd1f2f9', '::1', 1668798908, 0.033987, '1', 200),
(56, 'api/admin/course/courses_list', 'post', '{\"Host\":\"localhost\",\"Connection\":\"keep-alive\",\"Content-Length\":\"106\",\"sec-ch-ua\":\"\\\"Google Chrome\\\";v=\\\"107\\\", \\\"Chromium\\\";v=\\\"107\\\", \\\"Not=A?Brand\\\";v=\\\"24\\\"\",\"content-Type\":\"application\\/json\",\"session_key\":\"276def3e-6d22-ca4e-72bc-ed63abd1f2f9\",\"sec-ch-ua-mobile\":\"?0\",\"User-Agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/107.0.0.0 Safari\\/537.36\",\"sec-ch-ua-platform\":\"\\\"Windows\\\"\",\"Accept\":\"*\\/*\",\"Origin\":\"http:\\/\\/localhost:3000\",\"Sec-Fetch-Site\":\"same-site\",\"Sec-Fetch-Mode\":\"cors\",\"Sec-Fetch-Dest\":\"empty\",\"Referer\":\"http:\\/\\/localhost:3000\\/\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Accept-Language\":\"en-GB,en-US;q=0.9,en;q=0.8,hi;q=0.7\",\"keyword\":\"\",\"pagination\":{\"offset\":0,\"limit\":10},\"sort_by\":{\"column_name\":\"\",\"order_by\":\"\"},\"filter\":\"\"}', '276def3e-6d22-ca4e-72bc-ed63abd1f2f9', '::1', 1668798969, 0.0149598, '1', 200),
(57, 'api/admin/course/courses_list', 'post', '{\"Host\":\"localhost\",\"Connection\":\"keep-alive\",\"Content-Length\":\"106\",\"sec-ch-ua\":\"\\\"Google Chrome\\\";v=\\\"107\\\", \\\"Chromium\\\";v=\\\"107\\\", \\\"Not=A?Brand\\\";v=\\\"24\\\"\",\"content-Type\":\"application\\/json\",\"session_key\":\"276def3e-6d22-ca4e-72bc-ed63abd1f2f9\",\"sec-ch-ua-mobile\":\"?0\",\"User-Agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/107.0.0.0 Safari\\/537.36\",\"sec-ch-ua-platform\":\"\\\"Windows\\\"\",\"Accept\":\"*\\/*\",\"Origin\":\"http:\\/\\/localhost:3000\",\"Sec-Fetch-Site\":\"same-site\",\"Sec-Fetch-Mode\":\"cors\",\"Sec-Fetch-Dest\":\"empty\",\"Referer\":\"http:\\/\\/localhost:3000\\/\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Accept-Language\":\"en-GB,en-US;q=0.9,en;q=0.8,hi;q=0.7\",\"keyword\":\"\",\"pagination\":{\"offset\":0,\"limit\":10},\"sort_by\":{\"column_name\":\"\",\"order_by\":\"\"},\"filter\":\"\"}', '276def3e-6d22-ca4e-72bc-ed63abd1f2f9', '::1', 1668798969, 0.0139332, '1', 200),
(58, 'api/admin/subject/subject_list_by_course_id', 'post', '{\"Host\":\"localhost\",\"Connection\":\"keep-alive\",\"Content-Length\":\"157\",\"sec-ch-ua\":\"\\\"Google Chrome\\\";v=\\\"107\\\", \\\"Chromium\\\";v=\\\"107\\\", \\\"Not=A?Brand\\\";v=\\\"24\\\"\",\"content-Type\":\"application\\/json\",\"session_key\":\"276def3e-6d22-ca4e-72bc-ed63abd1f2f9\",\"sec-ch-ua-mobile\":\"?0\",\"User-Agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/107.0.0.0 Safari\\/537.36\",\"sec-ch-ua-platform\":\"\\\"Windows\\\"\",\"Accept\":\"*\\/*\",\"Origin\":\"http:\\/\\/localhost:3000\",\"Sec-Fetch-Site\":\"same-site\",\"Sec-Fetch-Mode\":\"cors\",\"Sec-Fetch-Dest\":\"empty\",\"Referer\":\"http:\\/\\/localhost:3000\\/\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Accept-Language\":\"en-GB,en-US;q=0.9,en;q=0.8,hi;q=0.7\",\"keyword\":\"\",\"pagination\":{\"offset\":0,\"limit\":10},\"sort_by\":{\"column_name\":\"\",\"order_by\":\"\"},\"filter\":\"\",\"course_id\":\"abc3241a-113e-3b07-e382-c4ef978c357d\"}', '276def3e-6d22-ca4e-72bc-ed63abd1f2f9', '::1', 1668798969, 0.0363162, '1', 200),
(59, 'api/admin/course/get_details_by_id', 'post', '{\"session_key\":\"276def3e-6d22-ca4e-72bc-ed63abd1f2f9\",\"Content-Type\":\"application\\/json;charset=utf-8\",\"Accept\":\"*\\/*\",\"Content-Length\":\"52\",\"User-Agent\":\"node-fetch\\/1.0 (+https:\\/\\/github.com\\/bitinn\\/node-fetch)\",\"Accept-Encoding\":\"gzip,deflate\",\"Host\":\"localhost\",\"Connection\":\"keep-alive\",\"course_id\":\"abc3241a-113e-3b07-e382-c4ef978c357d\"}', '276def3e-6d22-ca4e-72bc-ed63abd1f2f9', '127.0.0.1', 1668798978, 0.0325751, '1', 200),
(60, 'api/admin/course/courses_list', 'post', '{\"Host\":\"localhost\",\"Connection\":\"keep-alive\",\"Content-Length\":\"106\",\"sec-ch-ua\":\"\\\"Google Chrome\\\";v=\\\"107\\\", \\\"Chromium\\\";v=\\\"107\\\", \\\"Not=A?Brand\\\";v=\\\"24\\\"\",\"content-Type\":\"application\\/json\",\"session_key\":\"276def3e-6d22-ca4e-72bc-ed63abd1f2f9\",\"sec-ch-ua-mobile\":\"?0\",\"User-Agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/107.0.0.0 Safari\\/537.36\",\"sec-ch-ua-platform\":\"\\\"Windows\\\"\",\"Accept\":\"*\\/*\",\"Origin\":\"http:\\/\\/localhost:3000\",\"Sec-Fetch-Site\":\"same-site\",\"Sec-Fetch-Mode\":\"cors\",\"Sec-Fetch-Dest\":\"empty\",\"Referer\":\"http:\\/\\/localhost:3000\\/\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Accept-Language\":\"en-GB,en-US;q=0.9,en;q=0.8,hi;q=0.7\",\"keyword\":\"\",\"pagination\":{\"offset\":0,\"limit\":10},\"sort_by\":{\"column_name\":\"\",\"order_by\":\"\"},\"filter\":\"\"}', '276def3e-6d22-ca4e-72bc-ed63abd1f2f9', '::1', 1668798982, 0.016963, '1', 200),
(61, 'api/admin/course/courses_list', 'post', '{\"Host\":\"localhost\",\"Connection\":\"keep-alive\",\"Content-Length\":\"106\",\"sec-ch-ua\":\"\\\"Google Chrome\\\";v=\\\"107\\\", \\\"Chromium\\\";v=\\\"107\\\", \\\"Not=A?Brand\\\";v=\\\"24\\\"\",\"content-Type\":\"application\\/json\",\"session_key\":\"276def3e-6d22-ca4e-72bc-ed63abd1f2f9\",\"sec-ch-ua-mobile\":\"?0\",\"User-Agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/107.0.0.0 Safari\\/537.36\",\"sec-ch-ua-platform\":\"\\\"Windows\\\"\",\"Accept\":\"*\\/*\",\"Origin\":\"http:\\/\\/localhost:3000\",\"Sec-Fetch-Site\":\"same-site\",\"Sec-Fetch-Mode\":\"cors\",\"Sec-Fetch-Dest\":\"empty\",\"Referer\":\"http:\\/\\/localhost:3000\\/\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Accept-Language\":\"en-GB,en-US;q=0.9,en;q=0.8,hi;q=0.7\",\"keyword\":\"\",\"pagination\":{\"offset\":0,\"limit\":10},\"sort_by\":{\"column_name\":\"\",\"order_by\":\"\"},\"filter\":\"\"}', '276def3e-6d22-ca4e-72bc-ed63abd1f2f9', '::1', 1668798982, 0.018151, '1', 200),
(62, 'api/admin/subject/subject_list_by_course_id', 'post', '{\"Host\":\"localhost\",\"Connection\":\"keep-alive\",\"Content-Length\":\"157\",\"sec-ch-ua\":\"\\\"Google Chrome\\\";v=\\\"107\\\", \\\"Chromium\\\";v=\\\"107\\\", \\\"Not=A?Brand\\\";v=\\\"24\\\"\",\"content-Type\":\"application\\/json\",\"session_key\":\"276def3e-6d22-ca4e-72bc-ed63abd1f2f9\",\"sec-ch-ua-mobile\":\"?0\",\"User-Agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/107.0.0.0 Safari\\/537.36\",\"sec-ch-ua-platform\":\"\\\"Windows\\\"\",\"Accept\":\"*\\/*\",\"Origin\":\"http:\\/\\/localhost:3000\",\"Sec-Fetch-Site\":\"same-site\",\"Sec-Fetch-Mode\":\"cors\",\"Sec-Fetch-Dest\":\"empty\",\"Referer\":\"http:\\/\\/localhost:3000\\/\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Accept-Language\":\"en-GB,en-US;q=0.9,en;q=0.8,hi;q=0.7\",\"keyword\":\"\",\"pagination\":{\"offset\":0,\"limit\":10},\"sort_by\":{\"column_name\":\"\",\"order_by\":\"\"},\"filter\":\"\",\"course_id\":\"abc3241a-113e-3b07-e382-c4ef978c357d\"}', '276def3e-6d22-ca4e-72bc-ed63abd1f2f9', '::1', 1668798982, 0.014811, '1', 200),
(63, 'api/admin/course/courses_list', 'post', '{\"Host\":\"localhost\",\"Connection\":\"keep-alive\",\"Content-Length\":\"106\",\"sec-ch-ua\":\"\\\"Google Chrome\\\";v=\\\"107\\\", \\\"Chromium\\\";v=\\\"107\\\", \\\"Not=A?Brand\\\";v=\\\"24\\\"\",\"content-Type\":\"application\\/json\",\"session_key\":\"276def3e-6d22-ca4e-72bc-ed63abd1f2f9\",\"sec-ch-ua-mobile\":\"?0\",\"User-Agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/107.0.0.0 Safari\\/537.36\",\"sec-ch-ua-platform\":\"\\\"Windows\\\"\",\"Accept\":\"*\\/*\",\"Origin\":\"http:\\/\\/localhost:3000\",\"Sec-Fetch-Site\":\"same-site\",\"Sec-Fetch-Mode\":\"cors\",\"Sec-Fetch-Dest\":\"empty\",\"Referer\":\"http:\\/\\/localhost:3000\\/\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Accept-Language\":\"en-GB,en-US;q=0.9,en;q=0.8,hi;q=0.7\",\"keyword\":\"\",\"pagination\":{\"offset\":0,\"limit\":10},\"sort_by\":{\"column_name\":\"\",\"order_by\":\"\"},\"filter\":\"\"}', '276def3e-6d22-ca4e-72bc-ed63abd1f2f9', '::1', 1668798996, 0.014627, '1', 200),
(64, 'api/admin/subject/subject_list_by_course_id', 'post', '{\"Host\":\"localhost\",\"Connection\":\"keep-alive\",\"Content-Length\":\"157\",\"sec-ch-ua\":\"\\\"Google Chrome\\\";v=\\\"107\\\", \\\"Chromium\\\";v=\\\"107\\\", \\\"Not=A?Brand\\\";v=\\\"24\\\"\",\"content-Type\":\"application\\/json\",\"session_key\":\"276def3e-6d22-ca4e-72bc-ed63abd1f2f9\",\"sec-ch-ua-mobile\":\"?0\",\"User-Agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/107.0.0.0 Safari\\/537.36\",\"sec-ch-ua-platform\":\"\\\"Windows\\\"\",\"Accept\":\"*\\/*\",\"Origin\":\"http:\\/\\/localhost:3000\",\"Sec-Fetch-Site\":\"same-site\",\"Sec-Fetch-Mode\":\"cors\",\"Sec-Fetch-Dest\":\"empty\",\"Referer\":\"http:\\/\\/localhost:3000\\/\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Accept-Language\":\"en-GB,en-US;q=0.9,en;q=0.8,hi;q=0.7\",\"keyword\":\"\",\"pagination\":{\"offset\":0,\"limit\":10},\"sort_by\":{\"column_name\":\"\",\"order_by\":\"\"},\"filter\":\"\",\"course_id\":\"abc3241a-113e-3b07-e382-c4ef978c357d\"}', '276def3e-6d22-ca4e-72bc-ed63abd1f2f9', '::1', 1668798996, 0.0134821, '1', 200),
(65, 'api/admin/course/courses_list', 'post', '{\"Host\":\"localhost\",\"Connection\":\"keep-alive\",\"Content-Length\":\"106\",\"sec-ch-ua\":\"\\\"Google Chrome\\\";v=\\\"107\\\", \\\"Chromium\\\";v=\\\"107\\\", \\\"Not=A?Brand\\\";v=\\\"24\\\"\",\"content-Type\":\"application\\/json\",\"session_key\":\"276def3e-6d22-ca4e-72bc-ed63abd1f2f9\",\"sec-ch-ua-mobile\":\"?0\",\"User-Agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/107.0.0.0 Safari\\/537.36\",\"sec-ch-ua-platform\":\"\\\"Windows\\\"\",\"Accept\":\"*\\/*\",\"Origin\":\"http:\\/\\/localhost:3000\",\"Sec-Fetch-Site\":\"same-site\",\"Sec-Fetch-Mode\":\"cors\",\"Sec-Fetch-Dest\":\"empty\",\"Referer\":\"http:\\/\\/localhost:3000\\/\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Accept-Language\":\"en-GB,en-US;q=0.9,en;q=0.8,hi;q=0.7\",\"keyword\":\"\",\"pagination\":{\"offset\":0,\"limit\":10},\"sort_by\":{\"column_name\":\"\",\"order_by\":\"\"},\"filter\":\"\"}', '276def3e-6d22-ca4e-72bc-ed63abd1f2f9', '::1', 1668798996, 0.014694, '1', 200),
(66, 'api/admin/course/courses_list', 'post', '{\"Host\":\"localhost\",\"Connection\":\"keep-alive\",\"Content-Length\":\"106\",\"sec-ch-ua\":\"\\\"Google Chrome\\\";v=\\\"107\\\", \\\"Chromium\\\";v=\\\"107\\\", \\\"Not=A?Brand\\\";v=\\\"24\\\"\",\"content-Type\":\"application\\/json\",\"session_key\":\"276def3e-6d22-ca4e-72bc-ed63abd1f2f9\",\"sec-ch-ua-mobile\":\"?0\",\"User-Agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/107.0.0.0 Safari\\/537.36\",\"sec-ch-ua-platform\":\"\\\"Windows\\\"\",\"Accept\":\"*\\/*\",\"Origin\":\"http:\\/\\/localhost:3000\",\"Sec-Fetch-Site\":\"same-site\",\"Sec-Fetch-Mode\":\"cors\",\"Sec-Fetch-Dest\":\"empty\",\"Referer\":\"http:\\/\\/localhost:3000\\/\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Accept-Language\":\"en-GB,en-US;q=0.9,en;q=0.8,hi;q=0.7\",\"keyword\":\"\",\"pagination\":{\"offset\":0,\"limit\":10},\"sort_by\":{\"column_name\":\"\",\"order_by\":\"\"},\"filter\":\"\"}', '276def3e-6d22-ca4e-72bc-ed63abd1f2f9', '::1', 1668799008, 0.0153821, '1', 200),
(67, 'api/admin/subject/subject_list_by_course_id', 'post', '{\"Host\":\"localhost\",\"Connection\":\"keep-alive\",\"Content-Length\":\"157\",\"sec-ch-ua\":\"\\\"Google Chrome\\\";v=\\\"107\\\", \\\"Chromium\\\";v=\\\"107\\\", \\\"Not=A?Brand\\\";v=\\\"24\\\"\",\"content-Type\":\"application\\/json\",\"session_key\":\"276def3e-6d22-ca4e-72bc-ed63abd1f2f9\",\"sec-ch-ua-mobile\":\"?0\",\"User-Agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/107.0.0.0 Safari\\/537.36\",\"sec-ch-ua-platform\":\"\\\"Windows\\\"\",\"Accept\":\"*\\/*\",\"Origin\":\"http:\\/\\/localhost:3000\",\"Sec-Fetch-Site\":\"same-site\",\"Sec-Fetch-Mode\":\"cors\",\"Sec-Fetch-Dest\":\"empty\",\"Referer\":\"http:\\/\\/localhost:3000\\/\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Accept-Language\":\"en-GB,en-US;q=0.9,en;q=0.8,hi;q=0.7\",\"keyword\":\"\",\"pagination\":{\"offset\":0,\"limit\":10},\"sort_by\":{\"column_name\":\"\",\"order_by\":\"\"},\"filter\":\"\",\"course_id\":\"abc3241a-113e-3b07-e382-c4ef978c357d\"}', '276def3e-6d22-ca4e-72bc-ed63abd1f2f9', '::1', 1668799008, 0.0163839, '1', 200),
(68, 'api/admin/course/courses_list', 'post', '{\"Host\":\"localhost\",\"Connection\":\"keep-alive\",\"Content-Length\":\"106\",\"sec-ch-ua\":\"\\\"Google Chrome\\\";v=\\\"107\\\", \\\"Chromium\\\";v=\\\"107\\\", \\\"Not=A?Brand\\\";v=\\\"24\\\"\",\"content-Type\":\"application\\/json\",\"session_key\":\"276def3e-6d22-ca4e-72bc-ed63abd1f2f9\",\"sec-ch-ua-mobile\":\"?0\",\"User-Agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/107.0.0.0 Safari\\/537.36\",\"sec-ch-ua-platform\":\"\\\"Windows\\\"\",\"Accept\":\"*\\/*\",\"Origin\":\"http:\\/\\/localhost:3000\",\"Sec-Fetch-Site\":\"same-site\",\"Sec-Fetch-Mode\":\"cors\",\"Sec-Fetch-Dest\":\"empty\",\"Referer\":\"http:\\/\\/localhost:3000\\/\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Accept-Language\":\"en-GB,en-US;q=0.9,en;q=0.8,hi;q=0.7\",\"keyword\":\"\",\"pagination\":{\"offset\":0,\"limit\":10},\"sort_by\":{\"column_name\":\"\",\"order_by\":\"\"},\"filter\":\"\"}', '276def3e-6d22-ca4e-72bc-ed63abd1f2f9', '::1', 1668799008, 0.01775, '1', 200),
(69, 'api/admin/course/courses_list', 'post', '{\"Host\":\"localhost\",\"Connection\":\"keep-alive\",\"Content-Length\":\"106\",\"sec-ch-ua\":\"\\\"Google Chrome\\\";v=\\\"107\\\", \\\"Chromium\\\";v=\\\"107\\\", \\\"Not=A?Brand\\\";v=\\\"24\\\"\",\"content-Type\":\"application\\/json\",\"session_key\":\"276def3e-6d22-ca4e-72bc-ed63abd1f2f9\",\"sec-ch-ua-mobile\":\"?0\",\"User-Agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/107.0.0.0 Safari\\/537.36\",\"sec-ch-ua-platform\":\"\\\"Windows\\\"\",\"Accept\":\"*\\/*\",\"Origin\":\"http:\\/\\/localhost:3000\",\"Sec-Fetch-Site\":\"same-site\",\"Sec-Fetch-Mode\":\"cors\",\"Sec-Fetch-Dest\":\"empty\",\"Referer\":\"http:\\/\\/localhost:3000\\/\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Accept-Language\":\"en-GB,en-US;q=0.9,en;q=0.8,hi;q=0.7\",\"keyword\":\"\",\"pagination\":{\"offset\":0,\"limit\":10},\"sort_by\":{\"column_name\":\"\",\"order_by\":\"\"},\"filter\":\"\"}', '276def3e-6d22-ca4e-72bc-ed63abd1f2f9', '::1', 1668799024, 0.019186, '1', 200),
(70, 'api/admin/subject/subject_list_by_course_id', 'post', '{\"Host\":\"localhost\",\"Connection\":\"keep-alive\",\"Content-Length\":\"157\",\"sec-ch-ua\":\"\\\"Google Chrome\\\";v=\\\"107\\\", \\\"Chromium\\\";v=\\\"107\\\", \\\"Not=A?Brand\\\";v=\\\"24\\\"\",\"content-Type\":\"application\\/json\",\"session_key\":\"276def3e-6d22-ca4e-72bc-ed63abd1f2f9\",\"sec-ch-ua-mobile\":\"?0\",\"User-Agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/107.0.0.0 Safari\\/537.36\",\"sec-ch-ua-platform\":\"\\\"Windows\\\"\",\"Accept\":\"*\\/*\",\"Origin\":\"http:\\/\\/localhost:3000\",\"Sec-Fetch-Site\":\"same-site\",\"Sec-Fetch-Mode\":\"cors\",\"Sec-Fetch-Dest\":\"empty\",\"Referer\":\"http:\\/\\/localhost:3000\\/\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Accept-Language\":\"en-GB,en-US;q=0.9,en;q=0.8,hi;q=0.7\",\"keyword\":\"\",\"pagination\":{\"offset\":0,\"limit\":10},\"sort_by\":{\"column_name\":\"\",\"order_by\":\"\"},\"filter\":\"\",\"course_id\":\"abc3241a-113e-3b07-e382-c4ef978c357d\"}', '276def3e-6d22-ca4e-72bc-ed63abd1f2f9', '::1', 1668799024, 0.017338, '1', 200),
(71, 'api/admin/course/courses_list', 'post', '{\"Host\":\"localhost\",\"Connection\":\"keep-alive\",\"Content-Length\":\"106\",\"sec-ch-ua\":\"\\\"Google Chrome\\\";v=\\\"107\\\", \\\"Chromium\\\";v=\\\"107\\\", \\\"Not=A?Brand\\\";v=\\\"24\\\"\",\"content-Type\":\"application\\/json\",\"session_key\":\"276def3e-6d22-ca4e-72bc-ed63abd1f2f9\",\"sec-ch-ua-mobile\":\"?0\",\"User-Agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/107.0.0.0 Safari\\/537.36\",\"sec-ch-ua-platform\":\"\\\"Windows\\\"\",\"Accept\":\"*\\/*\",\"Origin\":\"http:\\/\\/localhost:3000\",\"Sec-Fetch-Site\":\"same-site\",\"Sec-Fetch-Mode\":\"cors\",\"Sec-Fetch-Dest\":\"empty\",\"Referer\":\"http:\\/\\/localhost:3000\\/\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Accept-Language\":\"en-GB,en-US;q=0.9,en;q=0.8,hi;q=0.7\",\"keyword\":\"\",\"pagination\":{\"offset\":0,\"limit\":10},\"sort_by\":{\"column_name\":\"\",\"order_by\":\"\"},\"filter\":\"\"}', '276def3e-6d22-ca4e-72bc-ed63abd1f2f9', '::1', 1668799024, 0.0282621, '1', 200),
(72, 'api/admin/course/get_details_by_id', 'post', '{\"session_key\":\"276def3e-6d22-ca4e-72bc-ed63abd1f2f9\",\"Content-Type\":\"application\\/json;charset=utf-8\",\"Accept\":\"*\\/*\",\"Content-Length\":\"52\",\"User-Agent\":\"node-fetch\\/1.0 (+https:\\/\\/github.com\\/bitinn\\/node-fetch)\",\"Accept-Encoding\":\"gzip,deflate\",\"Host\":\"localhost\",\"Connection\":\"keep-alive\",\"course_id\":\"abc3241a-113e-3b07-e382-c4ef978c357d\"}', '276def3e-6d22-ca4e-72bc-ed63abd1f2f9', '127.0.0.1', 1668799029, 0.020746, '1', 200),
(73, 'api/admin/course/courses_list', 'post', '{\"Host\":\"localhost\",\"Connection\":\"keep-alive\",\"Content-Length\":\"106\",\"sec-ch-ua\":\"\\\"Google Chrome\\\";v=\\\"107\\\", \\\"Chromium\\\";v=\\\"107\\\", \\\"Not=A?Brand\\\";v=\\\"24\\\"\",\"content-Type\":\"application\\/json\",\"session_key\":\"276def3e-6d22-ca4e-72bc-ed63abd1f2f9\",\"sec-ch-ua-mobile\":\"?0\",\"User-Agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/107.0.0.0 Safari\\/537.36\",\"sec-ch-ua-platform\":\"\\\"Windows\\\"\",\"Accept\":\"*\\/*\",\"Origin\":\"http:\\/\\/localhost:3000\",\"Sec-Fetch-Site\":\"same-site\",\"Sec-Fetch-Mode\":\"cors\",\"Sec-Fetch-Dest\":\"empty\",\"Referer\":\"http:\\/\\/localhost:3000\\/\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Accept-Language\":\"en-GB,en-US;q=0.9,en;q=0.8,hi;q=0.7\",\"keyword\":\"\",\"pagination\":{\"offset\":0,\"limit\":10},\"sort_by\":{\"column_name\":\"\",\"order_by\":\"\"},\"filter\":\"\"}', '276def3e-6d22-ca4e-72bc-ed63abd1f2f9', '::1', 1668799033, 0.0195551, '1', 200),
(74, 'api/admin/subject/subject_list_by_course_id', 'post', '{\"Host\":\"localhost\",\"Connection\":\"keep-alive\",\"Content-Length\":\"157\",\"sec-ch-ua\":\"\\\"Google Chrome\\\";v=\\\"107\\\", \\\"Chromium\\\";v=\\\"107\\\", \\\"Not=A?Brand\\\";v=\\\"24\\\"\",\"content-Type\":\"application\\/json\",\"session_key\":\"276def3e-6d22-ca4e-72bc-ed63abd1f2f9\",\"sec-ch-ua-mobile\":\"?0\",\"User-Agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/107.0.0.0 Safari\\/537.36\",\"sec-ch-ua-platform\":\"\\\"Windows\\\"\",\"Accept\":\"*\\/*\",\"Origin\":\"http:\\/\\/localhost:3000\",\"Sec-Fetch-Site\":\"same-site\",\"Sec-Fetch-Mode\":\"cors\",\"Sec-Fetch-Dest\":\"empty\",\"Referer\":\"http:\\/\\/localhost:3000\\/\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Accept-Language\":\"en-GB,en-US;q=0.9,en;q=0.8,hi;q=0.7\",\"keyword\":\"\",\"pagination\":{\"offset\":0,\"limit\":10},\"sort_by\":{\"column_name\":\"\",\"order_by\":\"\"},\"filter\":\"\",\"course_id\":\"abc3241a-113e-3b07-e382-c4ef978c357d\"}', '276def3e-6d22-ca4e-72bc-ed63abd1f2f9', '::1', 1668799033, 0.0175219, '1', 200),
(75, 'api/admin/course/courses_list', 'post', '{\"Host\":\"localhost\",\"Connection\":\"keep-alive\",\"Content-Length\":\"106\",\"sec-ch-ua\":\"\\\"Google Chrome\\\";v=\\\"107\\\", \\\"Chromium\\\";v=\\\"107\\\", \\\"Not=A?Brand\\\";v=\\\"24\\\"\",\"content-Type\":\"application\\/json\",\"session_key\":\"276def3e-6d22-ca4e-72bc-ed63abd1f2f9\",\"sec-ch-ua-mobile\":\"?0\",\"User-Agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/107.0.0.0 Safari\\/537.36\",\"sec-ch-ua-platform\":\"\\\"Windows\\\"\",\"Accept\":\"*\\/*\",\"Origin\":\"http:\\/\\/localhost:3000\",\"Sec-Fetch-Site\":\"same-site\",\"Sec-Fetch-Mode\":\"cors\",\"Sec-Fetch-Dest\":\"empty\",\"Referer\":\"http:\\/\\/localhost:3000\\/\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Accept-Language\":\"en-GB,en-US;q=0.9,en;q=0.8,hi;q=0.7\",\"keyword\":\"\",\"pagination\":{\"offset\":0,\"limit\":10},\"sort_by\":{\"column_name\":\"\",\"order_by\":\"\"},\"filter\":\"\"}', '276def3e-6d22-ca4e-72bc-ed63abd1f2f9', '::1', 1668799033, 0.0173421, '1', 200),
(76, 'api/admin/course/courses_list', 'post', '{\"Host\":\"localhost\",\"Connection\":\"keep-alive\",\"Content-Length\":\"106\",\"sec-ch-ua\":\"\\\"Google Chrome\\\";v=\\\"107\\\", \\\"Chromium\\\";v=\\\"107\\\", \\\"Not=A?Brand\\\";v=\\\"24\\\"\",\"content-Type\":\"application\\/json\",\"session_key\":\"276def3e-6d22-ca4e-72bc-ed63abd1f2f9\",\"sec-ch-ua-mobile\":\"?0\",\"User-Agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/107.0.0.0 Safari\\/537.36\",\"sec-ch-ua-platform\":\"\\\"Windows\\\"\",\"Accept\":\"*\\/*\",\"Origin\":\"http:\\/\\/localhost:3000\",\"Sec-Fetch-Site\":\"same-site\",\"Sec-Fetch-Mode\":\"cors\",\"Sec-Fetch-Dest\":\"empty\",\"Referer\":\"http:\\/\\/localhost:3000\\/\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Accept-Language\":\"en-GB,en-US;q=0.9,en;q=0.8,hi;q=0.7\",\"keyword\":\"\",\"pagination\":{\"offset\":0,\"limit\":10},\"sort_by\":{\"column_name\":\"\",\"order_by\":\"\"},\"filter\":\"\"}', '276def3e-6d22-ca4e-72bc-ed63abd1f2f9', '::1', 1668799066, 0.044538, '1', 200),
(77, 'api/admin/subject/subject_list_by_course_id', 'post', '{\"Host\":\"localhost\",\"Connection\":\"keep-alive\",\"Content-Length\":\"157\",\"sec-ch-ua\":\"\\\"Google Chrome\\\";v=\\\"107\\\", \\\"Chromium\\\";v=\\\"107\\\", \\\"Not=A?Brand\\\";v=\\\"24\\\"\",\"content-Type\":\"application\\/json\",\"session_key\":\"276def3e-6d22-ca4e-72bc-ed63abd1f2f9\",\"sec-ch-ua-mobile\":\"?0\",\"User-Agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/107.0.0.0 Safari\\/537.36\",\"sec-ch-ua-platform\":\"\\\"Windows\\\"\",\"Accept\":\"*\\/*\",\"Origin\":\"http:\\/\\/localhost:3000\",\"Sec-Fetch-Site\":\"same-site\",\"Sec-Fetch-Mode\":\"cors\",\"Sec-Fetch-Dest\":\"empty\",\"Referer\":\"http:\\/\\/localhost:3000\\/\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Accept-Language\":\"en-GB,en-US;q=0.9,en;q=0.8,hi;q=0.7\",\"keyword\":\"\",\"pagination\":{\"offset\":0,\"limit\":10},\"sort_by\":{\"column_name\":\"\",\"order_by\":\"\"},\"filter\":\"\",\"course_id\":\"abc3241a-113e-3b07-e382-c4ef978c357d\"}', '276def3e-6d22-ca4e-72bc-ed63abd1f2f9', '::1', 1668799066, 0.0434391, '1', 200),
(78, 'api/admin/course/courses_list', 'post', '{\"Host\":\"localhost\",\"Connection\":\"keep-alive\",\"Content-Length\":\"106\",\"sec-ch-ua\":\"\\\"Google Chrome\\\";v=\\\"107\\\", \\\"Chromium\\\";v=\\\"107\\\", \\\"Not=A?Brand\\\";v=\\\"24\\\"\",\"content-Type\":\"application\\/json\",\"session_key\":\"276def3e-6d22-ca4e-72bc-ed63abd1f2f9\",\"sec-ch-ua-mobile\":\"?0\",\"User-Agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/107.0.0.0 Safari\\/537.36\",\"sec-ch-ua-platform\":\"\\\"Windows\\\"\",\"Accept\":\"*\\/*\",\"Origin\":\"http:\\/\\/localhost:3000\",\"Sec-Fetch-Site\":\"same-site\",\"Sec-Fetch-Mode\":\"cors\",\"Sec-Fetch-Dest\":\"empty\",\"Referer\":\"http:\\/\\/localhost:3000\\/\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Accept-Language\":\"en-GB,en-US;q=0.9,en;q=0.8,hi;q=0.7\",\"keyword\":\"\",\"pagination\":{\"offset\":0,\"limit\":10},\"sort_by\":{\"column_name\":\"\",\"order_by\":\"\"},\"filter\":\"\"}', '276def3e-6d22-ca4e-72bc-ed63abd1f2f9', '::1', 1668799066, 0.0237601, '1', 200),
(79, 'api/admin/course/courses_list', 'post', '{\"Host\":\"localhost\",\"Connection\":\"keep-alive\",\"Content-Length\":\"106\",\"sec-ch-ua\":\"\\\"Google Chrome\\\";v=\\\"107\\\", \\\"Chromium\\\";v=\\\"107\\\", \\\"Not=A?Brand\\\";v=\\\"24\\\"\",\"content-Type\":\"application\\/json\",\"session_key\":\"276def3e-6d22-ca4e-72bc-ed63abd1f2f9\",\"sec-ch-ua-mobile\":\"?0\",\"User-Agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/107.0.0.0 Safari\\/537.36\",\"sec-ch-ua-platform\":\"\\\"Windows\\\"\",\"Accept\":\"*\\/*\",\"Origin\":\"http:\\/\\/localhost:3000\",\"Sec-Fetch-Site\":\"same-site\",\"Sec-Fetch-Mode\":\"cors\",\"Sec-Fetch-Dest\":\"empty\",\"Referer\":\"http:\\/\\/localhost:3000\\/\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Accept-Language\":\"en-GB,en-US;q=0.9,en;q=0.8,hi;q=0.7\",\"keyword\":\"\",\"pagination\":{\"offset\":0,\"limit\":10},\"sort_by\":{\"column_name\":\"\",\"order_by\":\"\"},\"filter\":\"\"}', '276def3e-6d22-ca4e-72bc-ed63abd1f2f9', '::1', 1668799095, 0.0153351, '1', 200),
(80, 'api/admin/subject/subject_list_by_course_id', 'post', '{\"Host\":\"localhost\",\"Connection\":\"keep-alive\",\"Content-Length\":\"157\",\"sec-ch-ua\":\"\\\"Google Chrome\\\";v=\\\"107\\\", \\\"Chromium\\\";v=\\\"107\\\", \\\"Not=A?Brand\\\";v=\\\"24\\\"\",\"content-Type\":\"application\\/json\",\"session_key\":\"276def3e-6d22-ca4e-72bc-ed63abd1f2f9\",\"sec-ch-ua-mobile\":\"?0\",\"User-Agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/107.0.0.0 Safari\\/537.36\",\"sec-ch-ua-platform\":\"\\\"Windows\\\"\",\"Accept\":\"*\\/*\",\"Origin\":\"http:\\/\\/localhost:3000\",\"Sec-Fetch-Site\":\"same-site\",\"Sec-Fetch-Mode\":\"cors\",\"Sec-Fetch-Dest\":\"empty\",\"Referer\":\"http:\\/\\/localhost:3000\\/\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Accept-Language\":\"en-GB,en-US;q=0.9,en;q=0.8,hi;q=0.7\",\"keyword\":\"\",\"pagination\":{\"offset\":0,\"limit\":10},\"sort_by\":{\"column_name\":\"\",\"order_by\":\"\"},\"filter\":\"\",\"course_id\":\"abc3241a-113e-3b07-e382-c4ef978c357d\"}', '276def3e-6d22-ca4e-72bc-ed63abd1f2f9', '::1', 1668799095, 0.014179, '1', 200),
(81, 'api/admin/course/courses_list', 'post', '{\"Host\":\"localhost\",\"Connection\":\"keep-alive\",\"Content-Length\":\"106\",\"sec-ch-ua\":\"\\\"Google Chrome\\\";v=\\\"107\\\", \\\"Chromium\\\";v=\\\"107\\\", \\\"Not=A?Brand\\\";v=\\\"24\\\"\",\"content-Type\":\"application\\/json\",\"session_key\":\"276def3e-6d22-ca4e-72bc-ed63abd1f2f9\",\"sec-ch-ua-mobile\":\"?0\",\"User-Agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/107.0.0.0 Safari\\/537.36\",\"sec-ch-ua-platform\":\"\\\"Windows\\\"\",\"Accept\":\"*\\/*\",\"Origin\":\"http:\\/\\/localhost:3000\",\"Sec-Fetch-Site\":\"same-site\",\"Sec-Fetch-Mode\":\"cors\",\"Sec-Fetch-Dest\":\"empty\",\"Referer\":\"http:\\/\\/localhost:3000\\/\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Accept-Language\":\"en-GB,en-US;q=0.9,en;q=0.8,hi;q=0.7\",\"keyword\":\"\",\"pagination\":{\"offset\":0,\"limit\":10},\"sort_by\":{\"column_name\":\"\",\"order_by\":\"\"},\"filter\":\"\"}', '276def3e-6d22-ca4e-72bc-ed63abd1f2f9', '::1', 1668799095, 0.0142279, '1', 200),
(82, 'api/admin/course/courses_list', 'post', '{\"Host\":\"localhost\",\"Connection\":\"keep-alive\",\"Content-Length\":\"106\",\"sec-ch-ua\":\"\\\"Google Chrome\\\";v=\\\"107\\\", \\\"Chromium\\\";v=\\\"107\\\", \\\"Not=A?Brand\\\";v=\\\"24\\\"\",\"content-Type\":\"application\\/json\",\"session_key\":\"276def3e-6d22-ca4e-72bc-ed63abd1f2f9\",\"sec-ch-ua-mobile\":\"?0\",\"User-Agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/107.0.0.0 Safari\\/537.36\",\"sec-ch-ua-platform\":\"\\\"Windows\\\"\",\"Accept\":\"*\\/*\",\"Origin\":\"http:\\/\\/localhost:3000\",\"Sec-Fetch-Site\":\"same-site\",\"Sec-Fetch-Mode\":\"cors\",\"Sec-Fetch-Dest\":\"empty\",\"Referer\":\"http:\\/\\/localhost:3000\\/\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Accept-Language\":\"en-GB,en-US;q=0.9,en;q=0.8,hi;q=0.7\",\"keyword\":\"\",\"pagination\":{\"offset\":0,\"limit\":10},\"sort_by\":{\"column_name\":\"\",\"order_by\":\"\"},\"filter\":\"\"}', '276def3e-6d22-ca4e-72bc-ed63abd1f2f9', '::1', 1668799099, 0.011338, '1', 200),
(83, 'api/admin/subject/subject_list_by_course_id', 'post', '{\"Host\":\"localhost\",\"Connection\":\"keep-alive\",\"Content-Length\":\"157\",\"sec-ch-ua\":\"\\\"Google Chrome\\\";v=\\\"107\\\", \\\"Chromium\\\";v=\\\"107\\\", \\\"Not=A?Brand\\\";v=\\\"24\\\"\",\"content-Type\":\"application\\/json\",\"session_key\":\"276def3e-6d22-ca4e-72bc-ed63abd1f2f9\",\"sec-ch-ua-mobile\":\"?0\",\"User-Agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/107.0.0.0 Safari\\/537.36\",\"sec-ch-ua-platform\":\"\\\"Windows\\\"\",\"Accept\":\"*\\/*\",\"Origin\":\"http:\\/\\/localhost:3000\",\"Sec-Fetch-Site\":\"same-site\",\"Sec-Fetch-Mode\":\"cors\",\"Sec-Fetch-Dest\":\"empty\",\"Referer\":\"http:\\/\\/localhost:3000\\/\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Accept-Language\":\"en-GB,en-US;q=0.9,en;q=0.8,hi;q=0.7\",\"keyword\":\"\",\"pagination\":{\"offset\":0,\"limit\":10},\"sort_by\":{\"column_name\":\"\",\"order_by\":\"\"},\"filter\":\"\",\"course_id\":\"abc3241a-113e-3b07-e382-c4ef978c357d\"}', '276def3e-6d22-ca4e-72bc-ed63abd1f2f9', '::1', 1668799099, 0.012413, '1', 200),
(84, 'api/admin/course/courses_list', 'post', '{\"Host\":\"localhost\",\"Connection\":\"keep-alive\",\"Content-Length\":\"106\",\"sec-ch-ua\":\"\\\"Google Chrome\\\";v=\\\"107\\\", \\\"Chromium\\\";v=\\\"107\\\", \\\"Not=A?Brand\\\";v=\\\"24\\\"\",\"content-Type\":\"application\\/json\",\"session_key\":\"276def3e-6d22-ca4e-72bc-ed63abd1f2f9\",\"sec-ch-ua-mobile\":\"?0\",\"User-Agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/107.0.0.0 Safari\\/537.36\",\"sec-ch-ua-platform\":\"\\\"Windows\\\"\",\"Accept\":\"*\\/*\",\"Origin\":\"http:\\/\\/localhost:3000\",\"Sec-Fetch-Site\":\"same-site\",\"Sec-Fetch-Mode\":\"cors\",\"Sec-Fetch-Dest\":\"empty\",\"Referer\":\"http:\\/\\/localhost:3000\\/\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Accept-Language\":\"en-GB,en-US;q=0.9,en;q=0.8,hi;q=0.7\",\"keyword\":\"\",\"pagination\":{\"offset\":0,\"limit\":10},\"sort_by\":{\"column_name\":\"\",\"order_by\":\"\"},\"filter\":\"\"}', '276def3e-6d22-ca4e-72bc-ed63abd1f2f9', '::1', 1668799099, 0.0322342, '1', 200),
(85, 'api/admin/course/courses_list', 'post', '{\"Host\":\"localhost\",\"Connection\":\"keep-alive\",\"Content-Length\":\"106\",\"sec-ch-ua\":\"\\\"Google Chrome\\\";v=\\\"107\\\", \\\"Chromium\\\";v=\\\"107\\\", \\\"Not=A?Brand\\\";v=\\\"24\\\"\",\"content-Type\":\"application\\/json\",\"session_key\":\"276def3e-6d22-ca4e-72bc-ed63abd1f2f9\",\"sec-ch-ua-mobile\":\"?0\",\"User-Agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/107.0.0.0 Safari\\/537.36\",\"sec-ch-ua-platform\":\"\\\"Windows\\\"\",\"Accept\":\"*\\/*\",\"Origin\":\"http:\\/\\/localhost:3000\",\"Sec-Fetch-Site\":\"same-site\",\"Sec-Fetch-Mode\":\"cors\",\"Sec-Fetch-Dest\":\"empty\",\"Referer\":\"http:\\/\\/localhost:3000\\/\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Accept-Language\":\"en-GB,en-US;q=0.9,en;q=0.8,hi;q=0.7\",\"keyword\":\"\",\"pagination\":{\"offset\":0,\"limit\":10},\"sort_by\":{\"column_name\":\"\",\"order_by\":\"\"},\"filter\":\"\"}', '276def3e-6d22-ca4e-72bc-ed63abd1f2f9', '::1', 1668799134, 0.0125921, '1', 200),
(86, 'api/admin/subject/subject_list_by_course_id', 'post', '{\"Host\":\"localhost\",\"Connection\":\"keep-alive\",\"Content-Length\":\"157\",\"sec-ch-ua\":\"\\\"Google Chrome\\\";v=\\\"107\\\", \\\"Chromium\\\";v=\\\"107\\\", \\\"Not=A?Brand\\\";v=\\\"24\\\"\",\"content-Type\":\"application\\/json\",\"session_key\":\"276def3e-6d22-ca4e-72bc-ed63abd1f2f9\",\"sec-ch-ua-mobile\":\"?0\",\"User-Agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/107.0.0.0 Safari\\/537.36\",\"sec-ch-ua-platform\":\"\\\"Windows\\\"\",\"Accept\":\"*\\/*\",\"Origin\":\"http:\\/\\/localhost:3000\",\"Sec-Fetch-Site\":\"same-site\",\"Sec-Fetch-Mode\":\"cors\",\"Sec-Fetch-Dest\":\"empty\",\"Referer\":\"http:\\/\\/localhost:3000\\/\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Accept-Language\":\"en-GB,en-US;q=0.9,en;q=0.8,hi;q=0.7\",\"keyword\":\"\",\"pagination\":{\"offset\":0,\"limit\":10},\"sort_by\":{\"column_name\":\"\",\"order_by\":\"\"},\"filter\":\"\",\"course_id\":\"abc3241a-113e-3b07-e382-c4ef978c357d\"}', '276def3e-6d22-ca4e-72bc-ed63abd1f2f9', '::1', 1668799134, 0.0110168, '1', 200),
(87, 'api/admin/course/courses_list', 'post', '{\"Host\":\"localhost\",\"Connection\":\"keep-alive\",\"Content-Length\":\"106\",\"sec-ch-ua\":\"\\\"Google Chrome\\\";v=\\\"107\\\", \\\"Chromium\\\";v=\\\"107\\\", \\\"Not=A?Brand\\\";v=\\\"24\\\"\",\"content-Type\":\"application\\/json\",\"session_key\":\"276def3e-6d22-ca4e-72bc-ed63abd1f2f9\",\"sec-ch-ua-mobile\":\"?0\",\"User-Agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/107.0.0.0 Safari\\/537.36\",\"sec-ch-ua-platform\":\"\\\"Windows\\\"\",\"Accept\":\"*\\/*\",\"Origin\":\"http:\\/\\/localhost:3000\",\"Sec-Fetch-Site\":\"same-site\",\"Sec-Fetch-Mode\":\"cors\",\"Sec-Fetch-Dest\":\"empty\",\"Referer\":\"http:\\/\\/localhost:3000\\/\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Accept-Language\":\"en-GB,en-US;q=0.9,en;q=0.8,hi;q=0.7\",\"keyword\":\"\",\"pagination\":{\"offset\":0,\"limit\":10},\"sort_by\":{\"column_name\":\"\",\"order_by\":\"\"},\"filter\":\"\"}', '276def3e-6d22-ca4e-72bc-ed63abd1f2f9', '::1', 1668799134, 0.0119209, '1', 200),
(88, 'api/admin/course/courses_list', 'post', '{\"Host\":\"localhost\",\"Connection\":\"keep-alive\",\"Content-Length\":\"106\",\"sec-ch-ua\":\"\\\"Google Chrome\\\";v=\\\"107\\\", \\\"Chromium\\\";v=\\\"107\\\", \\\"Not=A?Brand\\\";v=\\\"24\\\"\",\"content-Type\":\"application\\/json\",\"session_key\":\"276def3e-6d22-ca4e-72bc-ed63abd1f2f9\",\"sec-ch-ua-mobile\":\"?0\",\"User-Agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/107.0.0.0 Safari\\/537.36\",\"sec-ch-ua-platform\":\"\\\"Windows\\\"\",\"Accept\":\"*\\/*\",\"Origin\":\"http:\\/\\/localhost:3000\",\"Sec-Fetch-Site\":\"same-site\",\"Sec-Fetch-Mode\":\"cors\",\"Sec-Fetch-Dest\":\"empty\",\"Referer\":\"http:\\/\\/localhost:3000\\/\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Accept-Language\":\"en-GB,en-US;q=0.9,en;q=0.8,hi;q=0.7\",\"keyword\":\"\",\"pagination\":{\"offset\":0,\"limit\":10},\"sort_by\":{\"column_name\":\"\",\"order_by\":\"\"},\"filter\":\"\"}', '276def3e-6d22-ca4e-72bc-ed63abd1f2f9', '::1', 1668799153, 0.0147822, '1', 200),
(89, 'api/admin/course/courses_list', 'post', '{\"Host\":\"localhost\",\"Connection\":\"keep-alive\",\"Content-Length\":\"106\",\"sec-ch-ua\":\"\\\"Google Chrome\\\";v=\\\"107\\\", \\\"Chromium\\\";v=\\\"107\\\", \\\"Not=A?Brand\\\";v=\\\"24\\\"\",\"content-Type\":\"application\\/json\",\"session_key\":\"276def3e-6d22-ca4e-72bc-ed63abd1f2f9\",\"sec-ch-ua-mobile\":\"?0\",\"User-Agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/107.0.0.0 Safari\\/537.36\",\"sec-ch-ua-platform\":\"\\\"Windows\\\"\",\"Accept\":\"*\\/*\",\"Origin\":\"http:\\/\\/localhost:3000\",\"Sec-Fetch-Site\":\"same-site\",\"Sec-Fetch-Mode\":\"cors\",\"Sec-Fetch-Dest\":\"empty\",\"Referer\":\"http:\\/\\/localhost:3000\\/\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Accept-Language\":\"en-GB,en-US;q=0.9,en;q=0.8,hi;q=0.7\",\"keyword\":\"\",\"pagination\":{\"offset\":0,\"limit\":10},\"sort_by\":{\"column_name\":\"\",\"order_by\":\"\"},\"filter\":\"\"}', '276def3e-6d22-ca4e-72bc-ed63abd1f2f9', '::1', 1668799153, 0.0255771, '1', 200),
(90, 'api/admin/subject/subject_list_by_course_id', 'post', '{\"Host\":\"localhost\",\"Connection\":\"keep-alive\",\"Content-Length\":\"157\",\"sec-ch-ua\":\"\\\"Google Chrome\\\";v=\\\"107\\\", \\\"Chromium\\\";v=\\\"107\\\", \\\"Not=A?Brand\\\";v=\\\"24\\\"\",\"content-Type\":\"application\\/json\",\"session_key\":\"276def3e-6d22-ca4e-72bc-ed63abd1f2f9\",\"sec-ch-ua-mobile\":\"?0\",\"User-Agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/107.0.0.0 Safari\\/537.36\",\"sec-ch-ua-platform\":\"\\\"Windows\\\"\",\"Accept\":\"*\\/*\",\"Origin\":\"http:\\/\\/localhost:3000\",\"Sec-Fetch-Site\":\"same-site\",\"Sec-Fetch-Mode\":\"cors\",\"Sec-Fetch-Dest\":\"empty\",\"Referer\":\"http:\\/\\/localhost:3000\\/\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Accept-Language\":\"en-GB,en-US;q=0.9,en;q=0.8,hi;q=0.7\",\"keyword\":\"\",\"pagination\":{\"offset\":0,\"limit\":10},\"sort_by\":{\"column_name\":\"\",\"order_by\":\"\"},\"filter\":\"\",\"course_id\":\"abc3241a-113e-3b07-e382-c4ef978c357d\"}', '276def3e-6d22-ca4e-72bc-ed63abd1f2f9', '::1', 1668799153, 0.03493, '1', 200),
(91, 'api/admin/course/courses_list', 'post', '{\"Host\":\"localhost\",\"Connection\":\"keep-alive\",\"Content-Length\":\"106\",\"sec-ch-ua\":\"\\\"Google Chrome\\\";v=\\\"107\\\", \\\"Chromium\\\";v=\\\"107\\\", \\\"Not=A?Brand\\\";v=\\\"24\\\"\",\"content-Type\":\"application\\/json\",\"session_key\":\"276def3e-6d22-ca4e-72bc-ed63abd1f2f9\",\"sec-ch-ua-mobile\":\"?0\",\"User-Agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/107.0.0.0 Safari\\/537.36\",\"sec-ch-ua-platform\":\"\\\"Windows\\\"\",\"Accept\":\"*\\/*\",\"Origin\":\"http:\\/\\/localhost:3000\",\"Sec-Fetch-Site\":\"same-site\",\"Sec-Fetch-Mode\":\"cors\",\"Sec-Fetch-Dest\":\"empty\",\"Referer\":\"http:\\/\\/localhost:3000\\/\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Accept-Language\":\"en-GB,en-US;q=0.9,en;q=0.8,hi;q=0.7\",\"keyword\":\"\",\"pagination\":{\"offset\":0,\"limit\":10},\"sort_by\":{\"column_name\":\"\",\"order_by\":\"\"},\"filter\":\"\"}', '276def3e-6d22-ca4e-72bc-ed63abd1f2f9', '::1', 1668799197, 0.0157259, '1', 200),
(92, 'api/admin/course/courses_list', 'post', '{\"Host\":\"localhost\",\"Connection\":\"keep-alive\",\"Content-Length\":\"106\",\"sec-ch-ua\":\"\\\"Google Chrome\\\";v=\\\"107\\\", \\\"Chromium\\\";v=\\\"107\\\", \\\"Not=A?Brand\\\";v=\\\"24\\\"\",\"content-Type\":\"application\\/json\",\"session_key\":\"276def3e-6d22-ca4e-72bc-ed63abd1f2f9\",\"sec-ch-ua-mobile\":\"?0\",\"User-Agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/107.0.0.0 Safari\\/537.36\",\"sec-ch-ua-platform\":\"\\\"Windows\\\"\",\"Accept\":\"*\\/*\",\"Origin\":\"http:\\/\\/localhost:3000\",\"Sec-Fetch-Site\":\"same-site\",\"Sec-Fetch-Mode\":\"cors\",\"Sec-Fetch-Dest\":\"empty\",\"Referer\":\"http:\\/\\/localhost:3000\\/\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Accept-Language\":\"en-GB,en-US;q=0.9,en;q=0.8,hi;q=0.7\",\"keyword\":\"\",\"pagination\":{\"offset\":0,\"limit\":10},\"sort_by\":{\"column_name\":\"\",\"order_by\":\"\"},\"filter\":\"\"}', '276def3e-6d22-ca4e-72bc-ed63abd1f2f9', '::1', 1668799197, 0.0123088, '1', 200),
(93, 'api/admin/subject/subject_list_by_course_id', 'post', '{\"Host\":\"localhost\",\"Connection\":\"keep-alive\",\"Content-Length\":\"157\",\"sec-ch-ua\":\"\\\"Google Chrome\\\";v=\\\"107\\\", \\\"Chromium\\\";v=\\\"107\\\", \\\"Not=A?Brand\\\";v=\\\"24\\\"\",\"content-Type\":\"application\\/json\",\"session_key\":\"276def3e-6d22-ca4e-72bc-ed63abd1f2f9\",\"sec-ch-ua-mobile\":\"?0\",\"User-Agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/107.0.0.0 Safari\\/537.36\",\"sec-ch-ua-platform\":\"\\\"Windows\\\"\",\"Accept\":\"*\\/*\",\"Origin\":\"http:\\/\\/localhost:3000\",\"Sec-Fetch-Site\":\"same-site\",\"Sec-Fetch-Mode\":\"cors\",\"Sec-Fetch-Dest\":\"empty\",\"Referer\":\"http:\\/\\/localhost:3000\\/\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Accept-Language\":\"en-GB,en-US;q=0.9,en;q=0.8,hi;q=0.7\",\"keyword\":\"\",\"pagination\":{\"offset\":0,\"limit\":10},\"sort_by\":{\"column_name\":\"\",\"order_by\":\"\"},\"filter\":\"\",\"course_id\":\"abc3241a-113e-3b07-e382-c4ef978c357d\"}', '276def3e-6d22-ca4e-72bc-ed63abd1f2f9', '::1', 1668799197, 0.0205309, '1', 200),
(94, 'api/admin/subject/subject_list_by_course_id', 'post', '{\"Host\":\"localhost\",\"Connection\":\"keep-alive\",\"Content-Length\":\"157\",\"sec-ch-ua\":\"\\\"Google Chrome\\\";v=\\\"107\\\", \\\"Chromium\\\";v=\\\"107\\\", \\\"Not=A?Brand\\\";v=\\\"24\\\"\",\"content-Type\":\"application\\/json\",\"session_key\":\"276def3e-6d22-ca4e-72bc-ed63abd1f2f9\",\"sec-ch-ua-mobile\":\"?0\",\"User-Agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/107.0.0.0 Safari\\/537.36\",\"sec-ch-ua-platform\":\"\\\"Windows\\\"\",\"Accept\":\"*\\/*\",\"Origin\":\"http:\\/\\/localhost:3000\",\"Sec-Fetch-Site\":\"same-site\",\"Sec-Fetch-Mode\":\"cors\",\"Sec-Fetch-Dest\":\"empty\",\"Referer\":\"http:\\/\\/localhost:3000\\/\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Accept-Language\":\"en-GB,en-US;q=0.9,en;q=0.8,hi;q=0.7\",\"keyword\":\"\",\"pagination\":{\"offset\":0,\"limit\":10},\"sort_by\":{\"column_name\":\"\",\"order_by\":\"\"},\"filter\":\"\",\"course_id\":\"abc3241a-113e-3b07-e382-c4ef978c357d\"}', '276def3e-6d22-ca4e-72bc-ed63abd1f2f9', '::1', 1668799262, 0.0166669, '1', 200),
(95, 'api/admin/course/courses_list', 'post', '{\"Host\":\"localhost\",\"Connection\":\"keep-alive\",\"Content-Length\":\"106\",\"sec-ch-ua\":\"\\\"Google Chrome\\\";v=\\\"107\\\", \\\"Chromium\\\";v=\\\"107\\\", \\\"Not=A?Brand\\\";v=\\\"24\\\"\",\"content-Type\":\"application\\/json\",\"session_key\":\"276def3e-6d22-ca4e-72bc-ed63abd1f2f9\",\"sec-ch-ua-mobile\":\"?0\",\"User-Agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/107.0.0.0 Safari\\/537.36\",\"sec-ch-ua-platform\":\"\\\"Windows\\\"\",\"Accept\":\"*\\/*\",\"Origin\":\"http:\\/\\/localhost:3000\",\"Sec-Fetch-Site\":\"same-site\",\"Sec-Fetch-Mode\":\"cors\",\"Sec-Fetch-Dest\":\"empty\",\"Referer\":\"http:\\/\\/localhost:3000\\/\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Accept-Language\":\"en-GB,en-US;q=0.9,en;q=0.8,hi;q=0.7\",\"keyword\":\"\",\"pagination\":{\"offset\":0,\"limit\":10},\"sort_by\":{\"column_name\":\"\",\"order_by\":\"\"},\"filter\":\"\"}', '276def3e-6d22-ca4e-72bc-ed63abd1f2f9', '::1', 1668799262, 0.00999999, '1', 200),
(96, 'api/admin/course/courses_list', 'post', '{\"Host\":\"localhost\",\"Connection\":\"keep-alive\",\"Content-Length\":\"106\",\"sec-ch-ua\":\"\\\"Google Chrome\\\";v=\\\"107\\\", \\\"Chromium\\\";v=\\\"107\\\", \\\"Not=A?Brand\\\";v=\\\"24\\\"\",\"content-Type\":\"application\\/json\",\"session_key\":\"276def3e-6d22-ca4e-72bc-ed63abd1f2f9\",\"sec-ch-ua-mobile\":\"?0\",\"User-Agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/107.0.0.0 Safari\\/537.36\",\"sec-ch-ua-platform\":\"\\\"Windows\\\"\",\"Accept\":\"*\\/*\",\"Origin\":\"http:\\/\\/localhost:3000\",\"Sec-Fetch-Site\":\"same-site\",\"Sec-Fetch-Mode\":\"cors\",\"Sec-Fetch-Dest\":\"empty\",\"Referer\":\"http:\\/\\/localhost:3000\\/\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Accept-Language\":\"en-GB,en-US;q=0.9,en;q=0.8,hi;q=0.7\",\"keyword\":\"\",\"pagination\":{\"offset\":0,\"limit\":10},\"sort_by\":{\"column_name\":\"\",\"order_by\":\"\"},\"filter\":\"\"}', '276def3e-6d22-ca4e-72bc-ed63abd1f2f9', '::1', 1668799262, 0.0315981, '1', 200);
INSERT INTO `logs` (`id`, `uri`, `method`, `params`, `api_key`, `ip_address`, `time`, `rtime`, `authorized`, `response_code`) VALUES
(97, 'api/admin/course/courses_list', 'post', '{\"Host\":\"localhost\",\"Connection\":\"keep-alive\",\"Content-Length\":\"106\",\"sec-ch-ua\":\"\\\"Google Chrome\\\";v=\\\"107\\\", \\\"Chromium\\\";v=\\\"107\\\", \\\"Not=A?Brand\\\";v=\\\"24\\\"\",\"content-Type\":\"application\\/json\",\"session_key\":\"276def3e-6d22-ca4e-72bc-ed63abd1f2f9\",\"sec-ch-ua-mobile\":\"?0\",\"User-Agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/107.0.0.0 Safari\\/537.36\",\"sec-ch-ua-platform\":\"\\\"Windows\\\"\",\"Accept\":\"*\\/*\",\"Origin\":\"http:\\/\\/localhost:3000\",\"Sec-Fetch-Site\":\"same-site\",\"Sec-Fetch-Mode\":\"cors\",\"Sec-Fetch-Dest\":\"empty\",\"Referer\":\"http:\\/\\/localhost:3000\\/\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Accept-Language\":\"en-GB,en-US;q=0.9,en;q=0.8,hi;q=0.7\",\"keyword\":\"\",\"pagination\":{\"offset\":0,\"limit\":10},\"sort_by\":{\"column_name\":\"\",\"order_by\":\"\"},\"filter\":\"\"}', '276def3e-6d22-ca4e-72bc-ed63abd1f2f9', '::1', 1668799330, 0.01559, '1', 200),
(98, 'api/admin/subject/subject_list_by_course_id', 'post', '{\"Host\":\"localhost\",\"Connection\":\"keep-alive\",\"Content-Length\":\"157\",\"sec-ch-ua\":\"\\\"Google Chrome\\\";v=\\\"107\\\", \\\"Chromium\\\";v=\\\"107\\\", \\\"Not=A?Brand\\\";v=\\\"24\\\"\",\"content-Type\":\"application\\/json\",\"session_key\":\"276def3e-6d22-ca4e-72bc-ed63abd1f2f9\",\"sec-ch-ua-mobile\":\"?0\",\"User-Agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/107.0.0.0 Safari\\/537.36\",\"sec-ch-ua-platform\":\"\\\"Windows\\\"\",\"Accept\":\"*\\/*\",\"Origin\":\"http:\\/\\/localhost:3000\",\"Sec-Fetch-Site\":\"same-site\",\"Sec-Fetch-Mode\":\"cors\",\"Sec-Fetch-Dest\":\"empty\",\"Referer\":\"http:\\/\\/localhost:3000\\/\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Accept-Language\":\"en-GB,en-US;q=0.9,en;q=0.8,hi;q=0.7\",\"keyword\":\"\",\"pagination\":{\"offset\":0,\"limit\":10},\"sort_by\":{\"column_name\":\"\",\"order_by\":\"\"},\"filter\":\"\",\"course_id\":\"abc3241a-113e-3b07-e382-c4ef978c357d\"}', '276def3e-6d22-ca4e-72bc-ed63abd1f2f9', '::1', 1668799330, 0.0143721, '1', 200),
(99, 'api/admin/subject/subject_list_by_course_id', 'post', '{\"Host\":\"localhost\",\"Connection\":\"keep-alive\",\"Content-Length\":\"157\",\"sec-ch-ua\":\"\\\"Google Chrome\\\";v=\\\"107\\\", \\\"Chromium\\\";v=\\\"107\\\", \\\"Not=A?Brand\\\";v=\\\"24\\\"\",\"content-Type\":\"application\\/json\",\"session_key\":\"276def3e-6d22-ca4e-72bc-ed63abd1f2f9\",\"sec-ch-ua-mobile\":\"?0\",\"User-Agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/107.0.0.0 Safari\\/537.36\",\"sec-ch-ua-platform\":\"\\\"Windows\\\"\",\"Accept\":\"*\\/*\",\"Origin\":\"http:\\/\\/localhost:3000\",\"Sec-Fetch-Site\":\"same-site\",\"Sec-Fetch-Mode\":\"cors\",\"Sec-Fetch-Dest\":\"empty\",\"Referer\":\"http:\\/\\/localhost:3000\\/\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Accept-Language\":\"en-GB,en-US;q=0.9,en;q=0.8,hi;q=0.7\",\"keyword\":\"\",\"pagination\":{\"offset\":0,\"limit\":10},\"sort_by\":{\"column_name\":\"\",\"order_by\":\"\"},\"filter\":\"\",\"course_id\":\"abc3241a-113e-3b07-e382-c4ef978c357d\"}', '276def3e-6d22-ca4e-72bc-ed63abd1f2f9', '::1', 1668799330, 0.0155499, '1', 200),
(100, 'api/admin/course/courses_list', 'post', '{\"Host\":\"localhost\",\"Connection\":\"keep-alive\",\"Content-Length\":\"106\",\"sec-ch-ua\":\"\\\"Google Chrome\\\";v=\\\"107\\\", \\\"Chromium\\\";v=\\\"107\\\", \\\"Not=A?Brand\\\";v=\\\"24\\\"\",\"content-Type\":\"application\\/json\",\"session_key\":\"276def3e-6d22-ca4e-72bc-ed63abd1f2f9\",\"sec-ch-ua-mobile\":\"?0\",\"User-Agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/107.0.0.0 Safari\\/537.36\",\"sec-ch-ua-platform\":\"\\\"Windows\\\"\",\"Accept\":\"*\\/*\",\"Origin\":\"http:\\/\\/localhost:3000\",\"Sec-Fetch-Site\":\"same-site\",\"Sec-Fetch-Mode\":\"cors\",\"Sec-Fetch-Dest\":\"empty\",\"Referer\":\"http:\\/\\/localhost:3000\\/\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Accept-Language\":\"en-GB,en-US;q=0.9,en;q=0.8,hi;q=0.7\",\"keyword\":\"\",\"pagination\":{\"offset\":0,\"limit\":10},\"sort_by\":{\"column_name\":\"\",\"order_by\":\"\"},\"filter\":\"\"}', '276def3e-6d22-ca4e-72bc-ed63abd1f2f9', '::1', 1668799331, 0.030863, '1', 200),
(101, 'api/admin/course/courses_list', 'post', '{\"Host\":\"localhost\",\"Connection\":\"keep-alive\",\"Content-Length\":\"106\",\"sec-ch-ua\":\"\\\"Google Chrome\\\";v=\\\"107\\\", \\\"Chromium\\\";v=\\\"107\\\", \\\"Not=A?Brand\\\";v=\\\"24\\\"\",\"content-Type\":\"application\\/json\",\"session_key\":\"276def3e-6d22-ca4e-72bc-ed63abd1f2f9\",\"sec-ch-ua-mobile\":\"?0\",\"User-Agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/107.0.0.0 Safari\\/537.36\",\"sec-ch-ua-platform\":\"\\\"Windows\\\"\",\"Accept\":\"*\\/*\",\"Origin\":\"http:\\/\\/localhost:3000\",\"Sec-Fetch-Site\":\"same-site\",\"Sec-Fetch-Mode\":\"cors\",\"Sec-Fetch-Dest\":\"empty\",\"Referer\":\"http:\\/\\/localhost:3000\\/\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Accept-Language\":\"en-GB,en-US;q=0.9,en;q=0.8,hi;q=0.7\",\"keyword\":\"\",\"pagination\":{\"offset\":0,\"limit\":10},\"sort_by\":{\"column_name\":\"\",\"order_by\":\"\"},\"filter\":\"\"}', '276def3e-6d22-ca4e-72bc-ed63abd1f2f9', '::1', 1668799350, 0.0170519, '1', 200),
(102, 'api/admin/course/courses_list', 'post', '{\"Host\":\"localhost\",\"Connection\":\"keep-alive\",\"Content-Length\":\"106\",\"sec-ch-ua\":\"\\\"Google Chrome\\\";v=\\\"107\\\", \\\"Chromium\\\";v=\\\"107\\\", \\\"Not=A?Brand\\\";v=\\\"24\\\"\",\"content-Type\":\"application\\/json\",\"session_key\":\"276def3e-6d22-ca4e-72bc-ed63abd1f2f9\",\"sec-ch-ua-mobile\":\"?0\",\"User-Agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/107.0.0.0 Safari\\/537.36\",\"sec-ch-ua-platform\":\"\\\"Windows\\\"\",\"Accept\":\"*\\/*\",\"Origin\":\"http:\\/\\/localhost:3000\",\"Sec-Fetch-Site\":\"same-site\",\"Sec-Fetch-Mode\":\"cors\",\"Sec-Fetch-Dest\":\"empty\",\"Referer\":\"http:\\/\\/localhost:3000\\/\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Accept-Language\":\"en-GB,en-US;q=0.9,en;q=0.8,hi;q=0.7\",\"keyword\":\"\",\"pagination\":{\"offset\":0,\"limit\":10},\"sort_by\":{\"column_name\":\"\",\"order_by\":\"\"},\"filter\":\"\"}', '276def3e-6d22-ca4e-72bc-ed63abd1f2f9', '::1', 1668799350, 0.0115972, '1', 200),
(103, 'api/admin/subject/subject_list_by_course_id', 'post', '{\"Host\":\"localhost\",\"Connection\":\"keep-alive\",\"Content-Length\":\"157\",\"sec-ch-ua\":\"\\\"Google Chrome\\\";v=\\\"107\\\", \\\"Chromium\\\";v=\\\"107\\\", \\\"Not=A?Brand\\\";v=\\\"24\\\"\",\"content-Type\":\"application\\/json\",\"session_key\":\"276def3e-6d22-ca4e-72bc-ed63abd1f2f9\",\"sec-ch-ua-mobile\":\"?0\",\"User-Agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/107.0.0.0 Safari\\/537.36\",\"sec-ch-ua-platform\":\"\\\"Windows\\\"\",\"Accept\":\"*\\/*\",\"Origin\":\"http:\\/\\/localhost:3000\",\"Sec-Fetch-Site\":\"same-site\",\"Sec-Fetch-Mode\":\"cors\",\"Sec-Fetch-Dest\":\"empty\",\"Referer\":\"http:\\/\\/localhost:3000\\/\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Accept-Language\":\"en-GB,en-US;q=0.9,en;q=0.8,hi;q=0.7\",\"keyword\":\"\",\"pagination\":{\"offset\":0,\"limit\":10},\"sort_by\":{\"column_name\":\"\",\"order_by\":\"\"},\"filter\":\"\",\"course_id\":\"abc3241a-113e-3b07-e382-c4ef978c357d\"}', '276def3e-6d22-ca4e-72bc-ed63abd1f2f9', '::1', 1668799350, 0.0324531, '1', 200),
(104, 'api/admin/subject/subject_list_by_course_id', 'post', '{\"Host\":\"localhost\",\"Connection\":\"keep-alive\",\"Content-Length\":\"157\",\"sec-ch-ua\":\"\\\"Google Chrome\\\";v=\\\"107\\\", \\\"Chromium\\\";v=\\\"107\\\", \\\"Not=A?Brand\\\";v=\\\"24\\\"\",\"content-Type\":\"application\\/json\",\"session_key\":\"276def3e-6d22-ca4e-72bc-ed63abd1f2f9\",\"sec-ch-ua-mobile\":\"?0\",\"User-Agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/107.0.0.0 Safari\\/537.36\",\"sec-ch-ua-platform\":\"\\\"Windows\\\"\",\"Accept\":\"*\\/*\",\"Origin\":\"http:\\/\\/localhost:3000\",\"Sec-Fetch-Site\":\"same-site\",\"Sec-Fetch-Mode\":\"cors\",\"Sec-Fetch-Dest\":\"empty\",\"Referer\":\"http:\\/\\/localhost:3000\\/\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Accept-Language\":\"en-GB,en-US;q=0.9,en;q=0.8,hi;q=0.7\",\"keyword\":\"\",\"pagination\":{\"offset\":0,\"limit\":10},\"sort_by\":{\"column_name\":\"\",\"order_by\":\"\"},\"filter\":\"\",\"course_id\":\"abc3241a-113e-3b07-e382-c4ef978c357d\"}', '276def3e-6d22-ca4e-72bc-ed63abd1f2f9', '::1', 1668799351, 0.013047, '1', 200),
(105, 'api/admin/course/courses_list', 'post', '{\"Host\":\"localhost\",\"Connection\":\"keep-alive\",\"Content-Length\":\"106\",\"sec-ch-ua\":\"\\\"Google Chrome\\\";v=\\\"107\\\", \\\"Chromium\\\";v=\\\"107\\\", \\\"Not=A?Brand\\\";v=\\\"24\\\"\",\"content-Type\":\"application\\/json\",\"session_key\":\"276def3e-6d22-ca4e-72bc-ed63abd1f2f9\",\"sec-ch-ua-mobile\":\"?0\",\"User-Agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/107.0.0.0 Safari\\/537.36\",\"sec-ch-ua-platform\":\"\\\"Windows\\\"\",\"Accept\":\"*\\/*\",\"Origin\":\"http:\\/\\/localhost:3000\",\"Sec-Fetch-Site\":\"same-site\",\"Sec-Fetch-Mode\":\"cors\",\"Sec-Fetch-Dest\":\"empty\",\"Referer\":\"http:\\/\\/localhost:3000\\/\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Accept-Language\":\"en-GB,en-US;q=0.9,en;q=0.8,hi;q=0.7\",\"keyword\":\"\",\"pagination\":{\"offset\":0,\"limit\":10},\"sort_by\":{\"column_name\":\"\",\"order_by\":\"\"},\"filter\":\"\"}', '276def3e-6d22-ca4e-72bc-ed63abd1f2f9', '::1', 1668799382, 0.0145991, '1', 200),
(106, 'api/admin/subject/subject_list_by_course_id', 'post', '{\"Host\":\"localhost\",\"Connection\":\"keep-alive\",\"Content-Length\":\"157\",\"sec-ch-ua\":\"\\\"Google Chrome\\\";v=\\\"107\\\", \\\"Chromium\\\";v=\\\"107\\\", \\\"Not=A?Brand\\\";v=\\\"24\\\"\",\"content-Type\":\"application\\/json\",\"session_key\":\"276def3e-6d22-ca4e-72bc-ed63abd1f2f9\",\"sec-ch-ua-mobile\":\"?0\",\"User-Agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/107.0.0.0 Safari\\/537.36\",\"sec-ch-ua-platform\":\"\\\"Windows\\\"\",\"Accept\":\"*\\/*\",\"Origin\":\"http:\\/\\/localhost:3000\",\"Sec-Fetch-Site\":\"same-site\",\"Sec-Fetch-Mode\":\"cors\",\"Sec-Fetch-Dest\":\"empty\",\"Referer\":\"http:\\/\\/localhost:3000\\/\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Accept-Language\":\"en-GB,en-US;q=0.9,en;q=0.8,hi;q=0.7\",\"keyword\":\"\",\"pagination\":{\"offset\":0,\"limit\":10},\"sort_by\":{\"column_name\":\"\",\"order_by\":\"\"},\"filter\":\"\",\"course_id\":\"abc3241a-113e-3b07-e382-c4ef978c357d\"}', '276def3e-6d22-ca4e-72bc-ed63abd1f2f9', '::1', 1668799382, 0.0106769, '1', 200),
(107, 'api/admin/course/courses_list', 'post', '{\"Host\":\"localhost\",\"Connection\":\"keep-alive\",\"Content-Length\":\"106\",\"sec-ch-ua\":\"\\\"Google Chrome\\\";v=\\\"107\\\", \\\"Chromium\\\";v=\\\"107\\\", \\\"Not=A?Brand\\\";v=\\\"24\\\"\",\"content-Type\":\"application\\/json\",\"session_key\":\"276def3e-6d22-ca4e-72bc-ed63abd1f2f9\",\"sec-ch-ua-mobile\":\"?0\",\"User-Agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/107.0.0.0 Safari\\/537.36\",\"sec-ch-ua-platform\":\"\\\"Windows\\\"\",\"Accept\":\"*\\/*\",\"Origin\":\"http:\\/\\/localhost:3000\",\"Sec-Fetch-Site\":\"same-site\",\"Sec-Fetch-Mode\":\"cors\",\"Sec-Fetch-Dest\":\"empty\",\"Referer\":\"http:\\/\\/localhost:3000\\/\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Accept-Language\":\"en-GB,en-US;q=0.9,en;q=0.8,hi;q=0.7\",\"keyword\":\"\",\"pagination\":{\"offset\":0,\"limit\":10},\"sort_by\":{\"column_name\":\"\",\"order_by\":\"\"},\"filter\":\"\"}', '276def3e-6d22-ca4e-72bc-ed63abd1f2f9', '::1', 1668799382, 0.0388141, '1', 200),
(108, 'api/admin/subject/subject_list_by_course_id', 'post', '{\"Host\":\"localhost\",\"Connection\":\"keep-alive\",\"Content-Length\":\"157\",\"sec-ch-ua\":\"\\\"Google Chrome\\\";v=\\\"107\\\", \\\"Chromium\\\";v=\\\"107\\\", \\\"Not=A?Brand\\\";v=\\\"24\\\"\",\"content-Type\":\"application\\/json\",\"session_key\":\"276def3e-6d22-ca4e-72bc-ed63abd1f2f9\",\"sec-ch-ua-mobile\":\"?0\",\"User-Agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/107.0.0.0 Safari\\/537.36\",\"sec-ch-ua-platform\":\"\\\"Windows\\\"\",\"Accept\":\"*\\/*\",\"Origin\":\"http:\\/\\/localhost:3000\",\"Sec-Fetch-Site\":\"same-site\",\"Sec-Fetch-Mode\":\"cors\",\"Sec-Fetch-Dest\":\"empty\",\"Referer\":\"http:\\/\\/localhost:3000\\/\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Accept-Language\":\"en-GB,en-US;q=0.9,en;q=0.8,hi;q=0.7\",\"keyword\":\"\",\"pagination\":{\"offset\":0,\"limit\":10},\"sort_by\":{\"column_name\":\"\",\"order_by\":\"\"},\"filter\":\"\",\"course_id\":\"abc3241a-113e-3b07-e382-c4ef978c357d\"}', '276def3e-6d22-ca4e-72bc-ed63abd1f2f9', '::1', 1668799428, 0.0150621, '1', 200),
(109, 'api/admin/course/courses_list', 'post', '{\"Host\":\"localhost\",\"Connection\":\"keep-alive\",\"Content-Length\":\"106\",\"sec-ch-ua\":\"\\\"Google Chrome\\\";v=\\\"107\\\", \\\"Chromium\\\";v=\\\"107\\\", \\\"Not=A?Brand\\\";v=\\\"24\\\"\",\"content-Type\":\"application\\/json\",\"session_key\":\"276def3e-6d22-ca4e-72bc-ed63abd1f2f9\",\"sec-ch-ua-mobile\":\"?0\",\"User-Agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/107.0.0.0 Safari\\/537.36\",\"sec-ch-ua-platform\":\"\\\"Windows\\\"\",\"Accept\":\"*\\/*\",\"Origin\":\"http:\\/\\/localhost:3000\",\"Sec-Fetch-Site\":\"same-site\",\"Sec-Fetch-Mode\":\"cors\",\"Sec-Fetch-Dest\":\"empty\",\"Referer\":\"http:\\/\\/localhost:3000\\/\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Accept-Language\":\"en-GB,en-US;q=0.9,en;q=0.8,hi;q=0.7\",\"keyword\":\"\",\"pagination\":{\"offset\":0,\"limit\":10},\"sort_by\":{\"column_name\":\"\",\"order_by\":\"\"},\"filter\":\"\"}', '276def3e-6d22-ca4e-72bc-ed63abd1f2f9', '::1', 1668799434, 0.0162861, '1', 200),
(110, 'api/admin/subject/subject_list_by_course_id', 'post', '{\"Host\":\"localhost\",\"Connection\":\"keep-alive\",\"Content-Length\":\"157\",\"sec-ch-ua\":\"\\\"Google Chrome\\\";v=\\\"107\\\", \\\"Chromium\\\";v=\\\"107\\\", \\\"Not=A?Brand\\\";v=\\\"24\\\"\",\"content-Type\":\"application\\/json\",\"session_key\":\"276def3e-6d22-ca4e-72bc-ed63abd1f2f9\",\"sec-ch-ua-mobile\":\"?0\",\"User-Agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/107.0.0.0 Safari\\/537.36\",\"sec-ch-ua-platform\":\"\\\"Windows\\\"\",\"Accept\":\"*\\/*\",\"Origin\":\"http:\\/\\/localhost:3000\",\"Sec-Fetch-Site\":\"same-site\",\"Sec-Fetch-Mode\":\"cors\",\"Sec-Fetch-Dest\":\"empty\",\"Referer\":\"http:\\/\\/localhost:3000\\/\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Accept-Language\":\"en-GB,en-US;q=0.9,en;q=0.8,hi;q=0.7\",\"keyword\":\"\",\"pagination\":{\"offset\":0,\"limit\":10},\"sort_by\":{\"column_name\":\"\",\"order_by\":\"\"},\"filter\":\"\",\"course_id\":\"abc3241a-113e-3b07-e382-c4ef978c357d\"}', '276def3e-6d22-ca4e-72bc-ed63abd1f2f9', '::1', 1668799434, 0.0114369, '1', 200),
(111, 'api/admin/course/courses_list', 'post', '{\"Host\":\"localhost\",\"Connection\":\"keep-alive\",\"Content-Length\":\"106\",\"sec-ch-ua\":\"\\\"Google Chrome\\\";v=\\\"107\\\", \\\"Chromium\\\";v=\\\"107\\\", \\\"Not=A?Brand\\\";v=\\\"24\\\"\",\"content-Type\":\"application\\/json\",\"session_key\":\"276def3e-6d22-ca4e-72bc-ed63abd1f2f9\",\"sec-ch-ua-mobile\":\"?0\",\"User-Agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/107.0.0.0 Safari\\/537.36\",\"sec-ch-ua-platform\":\"\\\"Windows\\\"\",\"Accept\":\"*\\/*\",\"Origin\":\"http:\\/\\/localhost:3000\",\"Sec-Fetch-Site\":\"same-site\",\"Sec-Fetch-Mode\":\"cors\",\"Sec-Fetch-Dest\":\"empty\",\"Referer\":\"http:\\/\\/localhost:3000\\/\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Accept-Language\":\"en-GB,en-US;q=0.9,en;q=0.8,hi;q=0.7\",\"keyword\":\"\",\"pagination\":{\"offset\":0,\"limit\":10},\"sort_by\":{\"column_name\":\"\",\"order_by\":\"\"},\"filter\":\"\"}', '276def3e-6d22-ca4e-72bc-ed63abd1f2f9', '::1', 1668799434, 0.025048, '1', 200),
(112, 'api/admin/subject/subject_list_by_course_id', 'post', '{\"Host\":\"localhost\",\"Connection\":\"keep-alive\",\"Content-Length\":\"157\",\"sec-ch-ua\":\"\\\"Google Chrome\\\";v=\\\"107\\\", \\\"Chromium\\\";v=\\\"107\\\", \\\"Not=A?Brand\\\";v=\\\"24\\\"\",\"content-Type\":\"application\\/json\",\"session_key\":\"276def3e-6d22-ca4e-72bc-ed63abd1f2f9\",\"sec-ch-ua-mobile\":\"?0\",\"User-Agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/107.0.0.0 Safari\\/537.36\",\"sec-ch-ua-platform\":\"\\\"Windows\\\"\",\"Accept\":\"*\\/*\",\"Origin\":\"http:\\/\\/localhost:3000\",\"Sec-Fetch-Site\":\"same-site\",\"Sec-Fetch-Mode\":\"cors\",\"Sec-Fetch-Dest\":\"empty\",\"Referer\":\"http:\\/\\/localhost:3000\\/\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Accept-Language\":\"en-GB,en-US;q=0.9,en;q=0.8,hi;q=0.7\",\"keyword\":\"\",\"pagination\":{\"offset\":0,\"limit\":10},\"sort_by\":{\"column_name\":\"\",\"order_by\":\"\"},\"filter\":\"\",\"course_id\":\"abc3241a-113e-3b07-e382-c4ef978c357d\"}', '276def3e-6d22-ca4e-72bc-ed63abd1f2f9', '::1', 1668799478, 0.0367589, '1', 200),
(113, 'api/admin/subject/subject_list_by_course_id', 'post', '{\"Host\":\"localhost\",\"Connection\":\"keep-alive\",\"Content-Length\":\"157\",\"sec-ch-ua\":\"\\\"Google Chrome\\\";v=\\\"107\\\", \\\"Chromium\\\";v=\\\"107\\\", \\\"Not=A?Brand\\\";v=\\\"24\\\"\",\"content-Type\":\"application\\/json\",\"session_key\":\"276def3e-6d22-ca4e-72bc-ed63abd1f2f9\",\"sec-ch-ua-mobile\":\"?0\",\"User-Agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/107.0.0.0 Safari\\/537.36\",\"sec-ch-ua-platform\":\"\\\"Windows\\\"\",\"Accept\":\"*\\/*\",\"Origin\":\"http:\\/\\/localhost:3000\",\"Sec-Fetch-Site\":\"same-site\",\"Sec-Fetch-Mode\":\"cors\",\"Sec-Fetch-Dest\":\"empty\",\"Referer\":\"http:\\/\\/localhost:3000\\/\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Accept-Language\":\"en-GB,en-US;q=0.9,en;q=0.8,hi;q=0.7\",\"keyword\":\"\",\"pagination\":{\"offset\":0,\"limit\":10},\"sort_by\":{\"column_name\":\"\",\"order_by\":\"\"},\"filter\":\"\",\"course_id\":\"abc3241a-113e-3b07-e382-c4ef978c357d\"}', '276def3e-6d22-ca4e-72bc-ed63abd1f2f9', '::1', 1668799490, 0.0172279, '1', 200),
(114, 'api/admin/course/courses_list', 'post', '{\"Host\":\"localhost\",\"Connection\":\"keep-alive\",\"Content-Length\":\"106\",\"sec-ch-ua\":\"\\\"Google Chrome\\\";v=\\\"107\\\", \\\"Chromium\\\";v=\\\"107\\\", \\\"Not=A?Brand\\\";v=\\\"24\\\"\",\"content-Type\":\"application\\/json\",\"session_key\":\"276def3e-6d22-ca4e-72bc-ed63abd1f2f9\",\"sec-ch-ua-mobile\":\"?0\",\"User-Agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/107.0.0.0 Safari\\/537.36\",\"sec-ch-ua-platform\":\"\\\"Windows\\\"\",\"Accept\":\"*\\/*\",\"Origin\":\"http:\\/\\/localhost:3000\",\"Sec-Fetch-Site\":\"same-site\",\"Sec-Fetch-Mode\":\"cors\",\"Sec-Fetch-Dest\":\"empty\",\"Referer\":\"http:\\/\\/localhost:3000\\/\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Accept-Language\":\"en-GB,en-US;q=0.9,en;q=0.8,hi;q=0.7\",\"keyword\":\"\",\"pagination\":{\"offset\":0,\"limit\":10},\"sort_by\":{\"column_name\":\"\",\"order_by\":\"\"},\"filter\":\"\"}', '276def3e-6d22-ca4e-72bc-ed63abd1f2f9', '::1', 1668799490, 0.0368159, '1', 200),
(115, 'api/admin/course/courses_list', 'post', '{\"Host\":\"localhost\",\"Connection\":\"keep-alive\",\"Content-Length\":\"106\",\"sec-ch-ua\":\"\\\"Google Chrome\\\";v=\\\"107\\\", \\\"Chromium\\\";v=\\\"107\\\", \\\"Not=A?Brand\\\";v=\\\"24\\\"\",\"content-Type\":\"application\\/json\",\"session_key\":\"276def3e-6d22-ca4e-72bc-ed63abd1f2f9\",\"sec-ch-ua-mobile\":\"?0\",\"User-Agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/107.0.0.0 Safari\\/537.36\",\"sec-ch-ua-platform\":\"\\\"Windows\\\"\",\"Accept\":\"*\\/*\",\"Origin\":\"http:\\/\\/localhost:3000\",\"Sec-Fetch-Site\":\"same-site\",\"Sec-Fetch-Mode\":\"cors\",\"Sec-Fetch-Dest\":\"empty\",\"Referer\":\"http:\\/\\/localhost:3000\\/\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Accept-Language\":\"en-GB,en-US;q=0.9,en;q=0.8,hi;q=0.7\",\"keyword\":\"\",\"pagination\":{\"offset\":0,\"limit\":10},\"sort_by\":{\"column_name\":\"\",\"order_by\":\"\"},\"filter\":\"\"}', '276def3e-6d22-ca4e-72bc-ed63abd1f2f9', '::1', 1668799490, 0.02283, '1', 200),
(116, 'api/admin/course/get_details_by_id', 'post', '{\"session_key\":\"276def3e-6d22-ca4e-72bc-ed63abd1f2f9\",\"Content-Type\":\"application\\/json;charset=utf-8\",\"Accept\":\"*\\/*\",\"Content-Length\":\"52\",\"User-Agent\":\"node-fetch\\/1.0 (+https:\\/\\/github.com\\/bitinn\\/node-fetch)\",\"Accept-Encoding\":\"gzip,deflate\",\"Host\":\"localhost\",\"Connection\":\"keep-alive\",\"course_id\":\"abc3241a-113e-3b07-e382-c4ef978c357d\"}', '276def3e-6d22-ca4e-72bc-ed63abd1f2f9', '127.0.0.1', 1668799504, 0.0227289, '1', 200),
(117, 'api/admin/subject/subject_list_by_course_id', 'post', '{\"Host\":\"localhost\",\"Connection\":\"keep-alive\",\"Content-Length\":\"157\",\"sec-ch-ua\":\"\\\"Google Chrome\\\";v=\\\"107\\\", \\\"Chromium\\\";v=\\\"107\\\", \\\"Not=A?Brand\\\";v=\\\"24\\\"\",\"content-Type\":\"application\\/json\",\"session_key\":\"276def3e-6d22-ca4e-72bc-ed63abd1f2f9\",\"sec-ch-ua-mobile\":\"?0\",\"User-Agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/107.0.0.0 Safari\\/537.36\",\"sec-ch-ua-platform\":\"\\\"Windows\\\"\",\"Accept\":\"*\\/*\",\"Origin\":\"http:\\/\\/localhost:3000\",\"Sec-Fetch-Site\":\"same-site\",\"Sec-Fetch-Mode\":\"cors\",\"Sec-Fetch-Dest\":\"empty\",\"Referer\":\"http:\\/\\/localhost:3000\\/\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Accept-Language\":\"en-GB,en-US;q=0.9,en;q=0.8,hi;q=0.7\",\"keyword\":\"\",\"pagination\":{\"offset\":0,\"limit\":10},\"sort_by\":{\"column_name\":\"\",\"order_by\":\"\"},\"filter\":\"\",\"course_id\":\"abc3241a-113e-3b07-e382-c4ef978c357d\"}', '276def3e-6d22-ca4e-72bc-ed63abd1f2f9', '::1', 1668799514, 0.0144329, '1', 200),
(118, 'api/admin/course/courses_list', 'post', '{\"Host\":\"localhost\",\"Connection\":\"keep-alive\",\"Content-Length\":\"106\",\"sec-ch-ua\":\"\\\"Google Chrome\\\";v=\\\"107\\\", \\\"Chromium\\\";v=\\\"107\\\", \\\"Not=A?Brand\\\";v=\\\"24\\\"\",\"content-Type\":\"application\\/json\",\"session_key\":\"276def3e-6d22-ca4e-72bc-ed63abd1f2f9\",\"sec-ch-ua-mobile\":\"?0\",\"User-Agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/107.0.0.0 Safari\\/537.36\",\"sec-ch-ua-platform\":\"\\\"Windows\\\"\",\"Accept\":\"*\\/*\",\"Origin\":\"http:\\/\\/localhost:3000\",\"Sec-Fetch-Site\":\"same-site\",\"Sec-Fetch-Mode\":\"cors\",\"Sec-Fetch-Dest\":\"empty\",\"Referer\":\"http:\\/\\/localhost:3000\\/\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Accept-Language\":\"en-GB,en-US;q=0.9,en;q=0.8,hi;q=0.7\",\"keyword\":\"\",\"pagination\":{\"offset\":0,\"limit\":10},\"sort_by\":{\"column_name\":\"\",\"order_by\":\"\"},\"filter\":\"\"}', '276def3e-6d22-ca4e-72bc-ed63abd1f2f9', '::1', 1668799514, 0.013767, '1', 200),
(119, 'api/admin/course/courses_list', 'post', '{\"Host\":\"localhost\",\"Connection\":\"keep-alive\",\"Content-Length\":\"106\",\"sec-ch-ua\":\"\\\"Google Chrome\\\";v=\\\"107\\\", \\\"Chromium\\\";v=\\\"107\\\", \\\"Not=A?Brand\\\";v=\\\"24\\\"\",\"content-Type\":\"application\\/json\",\"session_key\":\"276def3e-6d22-ca4e-72bc-ed63abd1f2f9\",\"sec-ch-ua-mobile\":\"?0\",\"User-Agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/107.0.0.0 Safari\\/537.36\",\"sec-ch-ua-platform\":\"\\\"Windows\\\"\",\"Accept\":\"*\\/*\",\"Origin\":\"http:\\/\\/localhost:3000\",\"Sec-Fetch-Site\":\"same-site\",\"Sec-Fetch-Mode\":\"cors\",\"Sec-Fetch-Dest\":\"empty\",\"Referer\":\"http:\\/\\/localhost:3000\\/\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Accept-Language\":\"en-GB,en-US;q=0.9,en;q=0.8,hi;q=0.7\",\"keyword\":\"\",\"pagination\":{\"offset\":0,\"limit\":10},\"sort_by\":{\"column_name\":\"\",\"order_by\":\"\"},\"filter\":\"\"}', '276def3e-6d22-ca4e-72bc-ed63abd1f2f9', '::1', 1668799514, 0.0161719, '1', 200),
(120, 'api/admin/chapters/chapter_list_by_subject_id', 'post', '{\"Host\":\"localhost\",\"Connection\":\"keep-alive\",\"Content-Length\":\"158\",\"sec-ch-ua\":\"\\\"Google Chrome\\\";v=\\\"107\\\", \\\"Chromium\\\";v=\\\"107\\\", \\\"Not=A?Brand\\\";v=\\\"24\\\"\",\"content-Type\":\"application\\/json\",\"session_key\":\"276def3e-6d22-ca4e-72bc-ed63abd1f2f9\",\"sec-ch-ua-mobile\":\"?0\",\"User-Agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/107.0.0.0 Safari\\/537.36\",\"sec-ch-ua-platform\":\"\\\"Windows\\\"\",\"Accept\":\"*\\/*\",\"Origin\":\"http:\\/\\/localhost:3000\",\"Sec-Fetch-Site\":\"same-site\",\"Sec-Fetch-Mode\":\"cors\",\"Sec-Fetch-Dest\":\"empty\",\"Referer\":\"http:\\/\\/localhost:3000\\/\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Accept-Language\":\"en-GB,en-US;q=0.9,en;q=0.8,hi;q=0.7\",\"keyword\":\"\",\"pagination\":{\"offset\":0,\"limit\":10},\"sort_by\":{\"column_name\":\"\",\"order_by\":\"\"},\"filter\":\"\",\"subject_id\":\"79d183fd-2ac5-42d5-f590-896f54267332\"}', '276def3e-6d22-ca4e-72bc-ed63abd1f2f9', '::1', 1668799521, 0.0237761, '1', 200),
(121, 'api/admin/chapters/chapter_list_by_subject_id', 'post', '{\"Host\":\"localhost\",\"Connection\":\"keep-alive\",\"Content-Length\":\"158\",\"sec-ch-ua\":\"\\\"Google Chrome\\\";v=\\\"107\\\", \\\"Chromium\\\";v=\\\"107\\\", \\\"Not=A?Brand\\\";v=\\\"24\\\"\",\"content-Type\":\"application\\/json\",\"session_key\":\"276def3e-6d22-ca4e-72bc-ed63abd1f2f9\",\"sec-ch-ua-mobile\":\"?0\",\"User-Agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/107.0.0.0 Safari\\/537.36\",\"sec-ch-ua-platform\":\"\\\"Windows\\\"\",\"Accept\":\"*\\/*\",\"Origin\":\"http:\\/\\/localhost:3000\",\"Sec-Fetch-Site\":\"same-site\",\"Sec-Fetch-Mode\":\"cors\",\"Sec-Fetch-Dest\":\"empty\",\"Referer\":\"http:\\/\\/localhost:3000\\/\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Accept-Language\":\"en-GB,en-US;q=0.9,en;q=0.8,hi;q=0.7\",\"keyword\":\"\",\"pagination\":{\"offset\":0,\"limit\":10},\"sort_by\":{\"column_name\":\"\",\"order_by\":\"\"},\"filter\":\"\",\"subject_id\":\"79d183fd-2ac5-42d5-f590-896f54267332\"}', '276def3e-6d22-ca4e-72bc-ed63abd1f2f9', '::1', 1668800008, 0.033551, '1', 200),
(122, 'api/admin/chapters/add_chapter', 'post', '{\"Host\":\"localhost\",\"Connection\":\"keep-alive\",\"Content-Length\":\"110\",\"sec-ch-ua\":\"\\\"Google Chrome\\\";v=\\\"107\\\", \\\"Chromium\\\";v=\\\"107\\\", \\\"Not=A?Brand\\\";v=\\\"24\\\"\",\"content-Type\":\"application\\/json\",\"session_key\":\"276def3e-6d22-ca4e-72bc-ed63abd1f2f9\",\"sec-ch-ua-mobile\":\"?0\",\"User-Agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/107.0.0.0 Safari\\/537.36\",\"sec-ch-ua-platform\":\"\\\"Windows\\\"\",\"Accept\":\"*\\/*\",\"Origin\":\"http:\\/\\/localhost:3000\",\"Sec-Fetch-Site\":\"same-site\",\"Sec-Fetch-Mode\":\"cors\",\"Sec-Fetch-Dest\":\"empty\",\"Referer\":\"http:\\/\\/localhost:3000\\/\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Accept-Language\":\"en-GB,en-US;q=0.9,en;q=0.8,hi;q=0.7\",\"subject_id\":\"79d183fd-2ac5-42d5-f590-896f54267332\",\"chapter_name\":\"Acid bases and salts\",\"status\":\"PENDING\"}', '276def3e-6d22-ca4e-72bc-ed63abd1f2f9', '::1', 1668800029, 0.014456, '1', 200),
(123, 'api/admin/chapters/chapter_list_by_subject_id', 'post', '{\"Host\":\"localhost\",\"Connection\":\"keep-alive\",\"Content-Length\":\"158\",\"sec-ch-ua\":\"\\\"Google Chrome\\\";v=\\\"107\\\", \\\"Chromium\\\";v=\\\"107\\\", \\\"Not=A?Brand\\\";v=\\\"24\\\"\",\"content-Type\":\"application\\/json\",\"session_key\":\"276def3e-6d22-ca4e-72bc-ed63abd1f2f9\",\"sec-ch-ua-mobile\":\"?0\",\"User-Agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/107.0.0.0 Safari\\/537.36\",\"sec-ch-ua-platform\":\"\\\"Windows\\\"\",\"Accept\":\"*\\/*\",\"Origin\":\"http:\\/\\/localhost:3000\",\"Sec-Fetch-Site\":\"same-site\",\"Sec-Fetch-Mode\":\"cors\",\"Sec-Fetch-Dest\":\"empty\",\"Referer\":\"http:\\/\\/localhost:3000\\/\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Accept-Language\":\"en-GB,en-US;q=0.9,en;q=0.8,hi;q=0.7\",\"keyword\":\"\",\"pagination\":{\"offset\":0,\"limit\":10},\"sort_by\":{\"column_name\":\"\",\"order_by\":\"\"},\"filter\":\"\",\"subject_id\":\"79d183fd-2ac5-42d5-f590-896f54267332\"}', '276def3e-6d22-ca4e-72bc-ed63abd1f2f9', '::1', 1668800029, 0.0305772, '1', 200),
(124, 'api/admin/auth/login', 'post', '{\"Accept\":\"application\\/json\",\"Content-Type\":\"application\\/json\",\"User-Agent\":\"PostmanRuntime\\/7.29.2\",\"Postman-Token\":\"600334fa-6dbb-4ccc-90e1-41fcaabfa43d\",\"Host\":\"localhost\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Connection\":\"keep-alive\",\"Content-Length\":\"100\",\"email\":\"admin@admin.com\",\"device_type\":\"web_browser\",\"password\":\"12345678\"}', '', '::1', 1668878027, 0.084682, '1', 200),
(125, 'api/admin/course/add_course', 'post', '{\"session_key\":\"b5ebbfa2-970d-71ab-84f6-a4fae2c12826\",\"Accept\":\"application\\/json\",\"Content-Type\":\"application\\/json\",\"User-Agent\":\"PostmanRuntime\\/7.29.2\",\"Postman-Token\":\"2ee9548e-76ed-4bfe-a4fa-34da832c1c0a\",\"Host\":\"localhost\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Connection\":\"keep-alive\",\"Content-Length\":\"159\",\"course_name\":\"12th I\",\"description\":\"this course blongs to student of class 10th (mm.p. board) for science subject \",\"status\":\"ACTIVE\"}', '', '::1', 1668878371, 0.033988, '0', 401),
(126, 'api/admin/course/add_course', 'post', '{\"session_key\":\"e127172b-b783-3d10-2b6f-a7954bba4431\",\"Accept\":\"application\\/json\",\"Content-Type\":\"application\\/json\",\"User-Agent\":\"PostmanRuntime\\/7.29.2\",\"Postman-Token\":\"e8f315c9-beee-40cb-b960-c181e9b3e6f5\",\"Host\":\"localhost\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Connection\":\"keep-alive\",\"Content-Length\":\"159\",\"course_name\":\"12th I\",\"description\":\"this course blongs to student of class 10th (mm.p. board) for science subject \",\"status\":\"ACTIVE\"}', 'e127172b-b783-3d10-2b6f-a7954bba4431', '::1', 1668878387, 0.0104671, '1', 200),
(127, 'api/admin/course/edit_course', 'post', '{\"session_key\":\"e127172b-b783-3d10-2b6f-a7954bba4431\",\"Accept\":\"application\\/json\",\"Content-Type\":\"application\\/json\",\"User-Agent\":\"PostmanRuntime\\/7.29.2\",\"Postman-Token\":\"d4fa8e2f-0673-4067-a6e7-df1bed4bf37d\",\"Host\":\"localhost\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Connection\":\"keep-alive\",\"Content-Length\":\"222\",\"course_id\":\"500fa8da-08a1-9f62-4f8a-c66c612868f7\",\"course_name\":\"12th\",\"description\":\"update  this course blongs to student of class 10th (mm.p. board) for science subject \",\"status\":\"ACTIVE\"}', 'e127172b-b783-3d10-2b6f-a7954bba4431', '::1', 1668878720, 0.012229, '1', 200),
(128, 'api/admin/course/edit_course', 'post', '{\"session_key\":\"e127172b-b783-3d10-2b6f-a7954bba4431\",\"Accept\":\"application\\/json\",\"Content-Type\":\"application\\/json\",\"User-Agent\":\"PostmanRuntime\\/7.29.2\",\"Postman-Token\":\"8d298f9b-71ec-4f3d-94ab-27f833cbcd0c\",\"Host\":\"localhost\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Connection\":\"keep-alive\",\"Content-Length\":\"243\",\"course_id\":\"500fa8da-08a1-9f62-4f8a-c66c612868f7\",\"course_name\":\"10th science (m.p. board)\",\"description\":\"update  this course blongs to student of class 10th (mm.p. board) for science subject \",\"status\":\"ACTIVE\"}', 'e127172b-b783-3d10-2b6f-a7954bba4431', '::1', 1668878758, 0.0106251, '1', 200),
(129, 'api/admin/course/edit_course', 'post', '{\"session_key\":\"e127172b-b783-3d10-2b6f-a7954bba4431\",\"Accept\":\"application\\/json\",\"Content-Type\":\"application\\/json\",\"User-Agent\":\"PostmanRuntime\\/7.29.2\",\"Postman-Token\":\"2e3cdcb2-528f-4222-986e-1af1e2f4ac33\",\"Host\":\"localhost\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Connection\":\"keep-alive\",\"Content-Length\":\"235\",\"course_id\":\"500fa8da-08a1-9f62-4f8a-c66c612868f7\",\"course_name\":\"10th science (m.p. board)\",\"description\":\"this course blongs to student of class 10th (mm.p. board) for science subject \",\"status\":\"ACTIVE\"}', 'e127172b-b783-3d10-2b6f-a7954bba4431', '::1', 1668878774, 0.0247591, '1', 200),
(130, 'api/admin/course/courses_list', 'post', '{\"session_key\":\"e127172b-b783-3d10-2b6f-a7954bba4431\",\"Accept\":\"application\\/json\",\"Content-Type\":\"application\\/json\",\"User-Agent\":\"PostmanRuntime\\/7.29.2\",\"Postman-Token\":\"1b060373-6d92-4088-bbdc-7490f735f8ea\",\"Host\":\"localhost\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Connection\":\"keep-alive\",\"Content-Length\":\"71\",\"pagination\":{\"offset\":0,\"limit\":10}}', 'e127172b-b783-3d10-2b6f-a7954bba4431', '::1', 1668878811, 0.084259, '1', 0),
(131, 'api/admin/course/courses_list', 'post', '{\"session_key\":\"e127172b-b783-3d10-2b6f-a7954bba4431\",\"Accept\":\"application\\/json\",\"Content-Type\":\"application\\/json\",\"User-Agent\":\"PostmanRuntime\\/7.29.2\",\"Postman-Token\":\"7e2f30fe-89c8-450c-a7bd-8efb3f32b15a\",\"Host\":\"localhost\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Connection\":\"keep-alive\",\"Content-Length\":\"71\",\"pagination\":{\"offset\":0,\"limit\":10}}', 'e127172b-b783-3d10-2b6f-a7954bba4431', '::1', 1668878874, 0.0090251, '1', 200),
(132, 'api/admin/course/edit_course', 'post', '{\"session_key\":\"e127172b-b783-3d10-2b6f-a7954bba4431\",\"Accept\":\"application\\/json\",\"Content-Type\":\"application\\/json\",\"User-Agent\":\"PostmanRuntime\\/7.29.2\",\"Postman-Token\":\"6a7d6884-254e-4a8a-bcb6-69f5c01c4509\",\"Host\":\"localhost\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Connection\":\"keep-alive\",\"Content-Length\":\"235\",\"course_id\":\"500fa8da-08a1-9f62-4f8a-c66c612868f7\",\"course_name\":\"10th science (m.p. board)\",\"description\":\"this course blongs to student of class 10th (mm.p. board) for science subject \",\"status\":\"ACTIVE\"}', 'e127172b-b783-3d10-2b6f-a7954bba4431', '::1', 1668878912, 0.0105591, '1', 200),
(133, 'api/admin/course/courses_list', 'post', '{\"session_key\":\"e127172b-b783-3d10-2b6f-a7954bba4431\",\"Accept\":\"application\\/json\",\"Content-Type\":\"application\\/json\",\"User-Agent\":\"PostmanRuntime\\/7.29.2\",\"Postman-Token\":\"9ee64838-dd3b-41cf-9037-3c153126fd64\",\"Host\":\"localhost\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Connection\":\"keep-alive\",\"Content-Length\":\"71\",\"pagination\":{\"offset\":0,\"limit\":10}}', 'e127172b-b783-3d10-2b6f-a7954bba4431', '::1', 1668878916, 0.020112, '1', 200),
(134, 'api/admin/course/get_details_by_id', 'post', '{\"session_key\":\"b5ebbfa2-970d-71ab-84f6-a4fae2c12826\",\"Accept\":\"application\\/json\",\"Content-Type\":\"application\\/json\",\"User-Agent\":\"PostmanRuntime\\/7.29.2\",\"Postman-Token\":\"1aadbd2c-49e2-4711-89c3-f78226bf9761\",\"Host\":\"localhost\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Connection\":\"keep-alive\",\"Content-Length\":\"60\",\"course_id\":\"901df363-7e72-00aa-497d-48a3a6d47def\"}', '', '::1', 1668879080, 0.0073061, '0', 401),
(135, 'api/admin/course/get_details_by_id', 'post', '{\"session_key\":\"e127172b-b783-3d10-2b6f-a7954bba4431\",\"Accept\":\"application\\/json\",\"Content-Type\":\"application\\/json\",\"User-Agent\":\"PostmanRuntime\\/7.29.2\",\"Postman-Token\":\"98c745e1-d3fb-4c81-9e7a-4249a24fa157\",\"Host\":\"localhost\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Connection\":\"keep-alive\",\"Content-Length\":\"60\",\"course_id\":\"901df363-7e72-00aa-497d-48a3a6d47def\"}', 'e127172b-b783-3d10-2b6f-a7954bba4431', '::1', 1668879093, 0.0109899, '1', 403),
(136, 'api/admin/course/get_details_by_id', 'post', '{\"session_key\":\"e127172b-b783-3d10-2b6f-a7954bba4431\",\"Accept\":\"application\\/json\",\"Content-Type\":\"application\\/json\",\"User-Agent\":\"PostmanRuntime\\/7.29.2\",\"Postman-Token\":\"ce3837ba-b6b5-49ce-9fbd-6a69b430471d\",\"Host\":\"localhost\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Connection\":\"keep-alive\",\"Content-Length\":\"60\",\"course_id\":\"500fa8da-08a1-9f62-4f8a-c66c612868f7\"}', 'e127172b-b783-3d10-2b6f-a7954bba4431', '::1', 1668879111, 0.021888, '1', 0),
(137, 'api/admin/course/get_details_by_id', 'post', '{\"session_key\":\"e127172b-b783-3d10-2b6f-a7954bba4431\",\"Accept\":\"application\\/json\",\"Content-Type\":\"application\\/json\",\"User-Agent\":\"PostmanRuntime\\/7.29.2\",\"Postman-Token\":\"50b295c5-c793-4a83-93e0-77a9f9a28f69\",\"Host\":\"localhost\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Connection\":\"keep-alive\",\"Content-Length\":\"60\",\"course_id\":\"500fa8da-08a1-9f62-4f8a-c66c612868f7\"}', 'e127172b-b783-3d10-2b6f-a7954bba4431', '::1', 1668879168, 0.010246, '1', 200),
(138, 'api/admin/course/get_details_by_id', 'post', '{\"session_key\":\"e127172b-b783-3d10-2b6f-a7954bba4431\",\"Accept\":\"application\\/json\",\"Content-Type\":\"application\\/json\",\"User-Agent\":\"PostmanRuntime\\/7.29.2\",\"Postman-Token\":\"9dd9e55c-d46f-4b1d-8173-25b7e07861df\",\"Host\":\"localhost\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Connection\":\"keep-alive\",\"Content-Length\":\"60\",\"course_id\":\"500fa8da-08a1-9f62-4f8a-c66c612868f7\"}', 'e127172b-b783-3d10-2b6f-a7954bba4431', '::1', 1668879192, 0.0101359, '1', 200),
(139, 'api/admin/course/get_details_by_id', 'post', '{\"session_key\":\"e127172b-b783-3d10-2b6f-a7954bba4431\",\"Accept\":\"application\\/json\",\"Content-Type\":\"application\\/json\",\"User-Agent\":\"PostmanRuntime\\/7.29.2\",\"Postman-Token\":\"36d0fcf6-ba5e-47da-92f2-59707bdca80f\",\"Host\":\"localhost\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Connection\":\"keep-alive\",\"Content-Length\":\"60\",\"course_id\":\"500fa8da-08a1-9f62-4f8a-c66c612868f7\"}', 'e127172b-b783-3d10-2b6f-a7954bba4431', '::1', 1668879207, 0.00910997, '1', 200),
(140, 'api/admin/chapters/add_chapter', 'post', '{\"Accept\":\"application\\/json\",\"Content-Type\":\"application\\/json\",\"session_key\":\"e127172b-b783-3d10-2b6f-a7954bba4431\",\"User-Agent\":\"PostmanRuntime\\/7.29.2\",\"Postman-Token\":\"22a31374-139d-4559-af14-70e4bc009932\",\"Host\":\"localhost\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Connection\":\"keep-alive\",\"Content-Length\":\"212\",\"chapter_name\":\"Moving Charges and Magnetism\",\"chapter_summary\":\"this is a chapter summary\",\"subject_id\":\"7f7aa5f9-dd67-963b-823c-2b8777646a93\",\"status\":\"ACTIVE\"}', 'e127172b-b783-3d10-2b6f-a7954bba4431', '::1', 1668880329, 0.0161979, '1', 0),
(141, 'api/admin/chapters/add_chapter', 'post', '{\"Accept\":\"application\\/json\",\"Content-Type\":\"application\\/json\",\"session_key\":\"e127172b-b783-3d10-2b6f-a7954bba4431\",\"User-Agent\":\"PostmanRuntime\\/7.29.2\",\"Postman-Token\":\"bc428db2-3ea0-40e9-98b1-dd5211125a9f\",\"Host\":\"localhost\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Connection\":\"keep-alive\",\"Content-Length\":\"212\",\"chapter_name\":\"Moving Charges and Magnetism\",\"chapter_summary\":\"this is a chapter summary\",\"subject_id\":\"7f7aa5f9-dd67-963b-823c-2b8777646a93\",\"status\":\"ACTIVE\"}', 'e127172b-b783-3d10-2b6f-a7954bba4431', '::1', 1668880377, 0.0083518, '1', 0),
(142, 'api/admin/chapters/add_chapter', 'post', '{\"Accept\":\"application\\/json\",\"Content-Type\":\"application\\/json\",\"session_key\":\"e127172b-b783-3d10-2b6f-a7954bba4431\",\"User-Agent\":\"PostmanRuntime\\/7.29.2\",\"Postman-Token\":\"863097bb-d007-437c-b444-d70116d740a1\",\"Host\":\"localhost\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Connection\":\"keep-alive\",\"Content-Length\":\"212\",\"chapter_name\":\"Moving Charges and Magnetism\",\"chapter_summary\":\"this is a chapter summary\",\"subject_id\":\"7f7aa5f9-dd67-963b-823c-2b8777646a93\",\"status\":\"ACTIVE\"}', 'e127172b-b783-3d10-2b6f-a7954bba4431', '::1', 1668880462, 0.00888419, '1', 0),
(143, 'api/admin/chapters/add_chapter', 'post', '{\"Accept\":\"application\\/json\",\"Content-Type\":\"application\\/json\",\"session_key\":\"e127172b-b783-3d10-2b6f-a7954bba4431\",\"User-Agent\":\"PostmanRuntime\\/7.29.2\",\"Postman-Token\":\"11b4d168-5dd5-4ca9-b490-f46621465947\",\"Host\":\"localhost\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Connection\":\"keep-alive\",\"Content-Length\":\"212\",\"chapter_name\":\"Moving Charges and Magnetism\",\"chapter_summary\":\"this is a chapter summary\",\"subject_id\":\"7f7aa5f9-dd67-963b-823c-2b8777646a93\",\"status\":\"ACTIVE\"}', 'e127172b-b783-3d10-2b6f-a7954bba4431', '::1', 1668880655, 0.00994086, '1', 0),
(144, 'api/admin/chapters/add_chapter', 'post', '{\"Accept\":\"application\\/json\",\"Content-Type\":\"application\\/json\",\"session_key\":\"e127172b-b783-3d10-2b6f-a7954bba4431\",\"User-Agent\":\"PostmanRuntime\\/7.29.2\",\"Postman-Token\":\"ffd15a9d-482c-4f9f-b9c7-3093446a7570\",\"Host\":\"localhost\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Connection\":\"keep-alive\",\"Content-Length\":\"212\",\"chapter_name\":\"Moving Charges and Magnetism\",\"chapter_summary\":\"this is a chapter summary\",\"subject_id\":\"7f7aa5f9-dd67-963b-823c-2b8777646a93\",\"status\":\"ACTIVE\"}', 'e127172b-b783-3d10-2b6f-a7954bba4431', '::1', 1668880716, 0.010267, '1', 0),
(145, 'api/admin/chapters/add_chapter', 'post', '{\"Accept\":\"application\\/json\",\"Content-Type\":\"application\\/json\",\"session_key\":\"e127172b-b783-3d10-2b6f-a7954bba4431\",\"User-Agent\":\"PostmanRuntime\\/7.29.2\",\"Postman-Token\":\"4e8f7e89-5365-4825-a6b1-e02ffedca659\",\"Host\":\"localhost\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Connection\":\"keep-alive\",\"Content-Length\":\"212\",\"chapter_name\":\"Moving Charges and Magnetism\",\"chapter_summary\":\"this is a chapter summary\",\"subject_id\":\"7f7aa5f9-dd67-963b-823c-2b8777646a93\",\"status\":\"ACTIVE\"}', 'e127172b-b783-3d10-2b6f-a7954bba4431', '::1', 1668880731, 0.0119619, '1', 200),
(146, 'api/admin/chapters/add_chapter', 'post', '{\"Accept\":\"application\\/json\",\"Content-Type\":\"application\\/json\",\"session_key\":\"e127172b-b783-3d10-2b6f-a7954bba4431\",\"User-Agent\":\"PostmanRuntime\\/7.29.2\",\"Postman-Token\":\"bc94a73e-7e4d-4c13-9c38-a87b51779613\",\"Host\":\"localhost\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Connection\":\"keep-alive\",\"Content-Length\":\"212\",\"chapter_name\":\"Moving Charges and Magnetism\",\"chapter_summary\":\"this is a chapter summary\",\"subject_id\":\"7f7aa5f9-dd67-963b-823c-2b8777646a93\",\"status\":\"ACTIVE\"}', 'e127172b-b783-3d10-2b6f-a7954bba4431', '::1', 1668880811, 0.0266271, '1', 200),
(147, 'api/admin/chapters/add_chapter', 'post', '{\"Accept\":\"application\\/json\",\"Content-Type\":\"application\\/json\",\"session_key\":\"e127172b-b783-3d10-2b6f-a7954bba4431\",\"User-Agent\":\"PostmanRuntime\\/7.29.2\",\"Postman-Token\":\"5c96f333-8906-44b7-a6a9-d29d0c7aeb57\",\"Host\":\"localhost\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Connection\":\"keep-alive\",\"Content-Length\":\"212\",\"chapter_name\":\"Moving Charges and Magnetism\",\"chapter_summary\":\"this is a chapter summary\",\"subject_id\":\"7f7aa5f9-dd67-963b-823c-2b8777646a93\",\"status\":\"ACTIVE\"}', 'e127172b-b783-3d10-2b6f-a7954bba4431', '::1', 1668880971, 0.00964189, '1', 403),
(148, 'api/admin/chapters/add_chapter', 'post', '{\"Accept\":\"application\\/json\",\"Content-Type\":\"application\\/json\",\"session_key\":\"e127172b-b783-3d10-2b6f-a7954bba4431\",\"User-Agent\":\"PostmanRuntime\\/7.29.2\",\"Postman-Token\":\"21fee556-2852-4c17-9b57-312d30c2610b\",\"Host\":\"localhost\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Connection\":\"keep-alive\",\"Content-Length\":\"212\",\"chapter_name\":\"Moving Charges and Magnetism\",\"chapter_summary\":\"this is a chapter summary\",\"subject_id\":\"7f7aa5f9-dd67-963b-823c-2b8777646a93\",\"status\":\"ACTIVE\"}', 'e127172b-b783-3d10-2b6f-a7954bba4431', '::1', 1668881006, 0.0114551, '1', 403),
(149, 'api/admin/chapters/add_chapter', 'post', '{\"Accept\":\"application\\/json\",\"Content-Type\":\"application\\/json\",\"session_key\":\"e127172b-b783-3d10-2b6f-a7954bba4431\",\"User-Agent\":\"PostmanRuntime\\/7.29.2\",\"Postman-Token\":\"7b92aa3c-2034-4f01-827c-9a402b50c3ff\",\"Host\":\"localhost\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Connection\":\"keep-alive\",\"Content-Length\":\"211\",\"chapter_name\":\"Moving Charges and Magnetism\",\"chapter_summary\":\"this is a chapter summary\",\"course_id\":\"7f7aa5f9-dd67-963b-823c-2b8777646a93\",\"status\":\"ACTIVE\"}', 'e127172b-b783-3d10-2b6f-a7954bba4431', '::1', 1668881024, 0.00950003, '1', 403),
(150, 'api/admin/chapters/add_chapter', 'post', '{\"Accept\":\"application\\/json\",\"Content-Type\":\"application\\/json\",\"session_key\":\"e127172b-b783-3d10-2b6f-a7954bba4431\",\"User-Agent\":\"PostmanRuntime\\/7.29.2\",\"Postman-Token\":\"480fc136-50a2-4e56-a888-9062e3a64db6\",\"Host\":\"localhost\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Connection\":\"keep-alive\",\"Content-Length\":\"211\",\"chapter_name\":\"Moving Charges and Magnetism\",\"chapter_summary\":\"this is a chapter summary\",\"course_id\":\"7f7aa5f9-dd67-963b-823c-2b8777646a93\",\"status\":\"ACTIVE\"}', 'e127172b-b783-3d10-2b6f-a7954bba4431', '::1', 1668881069, 0.00931191, '1', 403),
(151, 'api/admin/chapters/add_chapter', 'post', '{\"Accept\":\"application\\/json\",\"Content-Type\":\"application\\/json\",\"session_key\":\"e127172b-b783-3d10-2b6f-a7954bba4431\",\"User-Agent\":\"PostmanRuntime\\/7.29.2\",\"Postman-Token\":\"857c7066-4d4e-4f78-9ec4-28919b0f031c\",\"Host\":\"localhost\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Connection\":\"keep-alive\",\"Content-Length\":\"265\",\"chapter_name\":\"chemical reactions and equations\",\"chapter_summary\":\"(According to blueprint, 6 marks questions will be asked from this lesson.)\",\"course_id\":\"500fa8da-08a1-9f62-4f8a-c66c612868f7\",\"status\":\"ACTIVE\"}', 'e127172b-b783-3d10-2b6f-a7954bba4431', '::1', 1668881185, 0.011198, '1', 200),
(152, 'api/admin/chapters/add_chapter', 'post', '{\"Accept\":\"application\\/json\",\"Content-Type\":\"application\\/json\",\"session_key\":\"e127172b-b783-3d10-2b6f-a7954bba4431\",\"User-Agent\":\"PostmanRuntime\\/7.29.2\",\"Postman-Token\":\"cb4a6a9f-243f-447e-b8d3-460c70c0a0d8\",\"Host\":\"localhost\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Connection\":\"keep-alive\",\"Content-Length\":\"265\",\"chapter_name\":\"chemical reactions and equations\",\"chapter_summary\":\"(According to blueprint, 6 marks questions will be asked from this lesson.)\",\"course_id\":\"500fa8da-08a1-9f62-4f8a-c66c612868f7\",\"status\":\"ACTIVE\"}', 'e127172b-b783-3d10-2b6f-a7954bba4431', '::1', 1668881206, 0.0104411, '1', 403),
(153, 'api/admin/chapters/add_chapter', 'post', '{\"Accept\":\"application\\/json\",\"Content-Type\":\"application\\/json\",\"session_key\":\"e127172b-b783-3d10-2b6f-a7954bba4431\",\"User-Agent\":\"PostmanRuntime\\/7.29.2\",\"Postman-Token\":\"a64d7dc1-a8a3-4727-b71f-1ec52ef609ea\",\"Host\":\"localhost\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Connection\":\"keep-alive\",\"Content-Length\":\"193\",\"chapter_name\":\"Acid, bases and salts\",\"chapter_summary\":\"no discription\",\"course_id\":\"500fa8da-08a1-9f62-4f8a-c66c612868f7\",\"status\":\"ACTIVE\"}', 'e127172b-b783-3d10-2b6f-a7954bba4431', '::1', 1668881254, 0.0132558, '1', 200),
(154, 'api/admin/chapters/add_chapter', 'post', '{\"Accept\":\"application\\/json\",\"Content-Type\":\"application\\/json\",\"session_key\":\"e127172b-b783-3d10-2b6f-a7954bba4431\",\"User-Agent\":\"PostmanRuntime\\/7.29.2\",\"Postman-Token\":\"935a7045-41a5-481d-8746-76bc66cf37eb\",\"Host\":\"localhost\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Connection\":\"keep-alive\",\"Content-Length\":\"253\",\"chapter_name\":\"Metal and non-metals\",\"chapter_summary\":\"(According to blueprint, 6 marks questions will be asked from this lesson.)\",\"course_id\":\"500fa8da-08a1-9f62-4f8a-c66c612868f7\",\"status\":\"ACTIVE\"}', 'e127172b-b783-3d10-2b6f-a7954bba4431', '::1', 1668881291, 0.0302, '1', 200),
(155, 'api/admin/chapters/edit_chapter', 'post', '{\"Accept\":\"application\\/json\",\"Content-Type\":\"application\\/json\",\"session_key\":\"e127172b-b783-3d10-2b6f-a7954bba4431\",\"User-Agent\":\"PostmanRuntime\\/7.29.2\",\"Postman-Token\":\"c91a5f07-8850-4ee0-bb26-cbbfc4b772c9\",\"Host\":\"localhost\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Connection\":\"keep-alive\",\"Content-Length\":\"189\",\"subject_id\":\"079c65b5-87a0-394c-ea08-8bd27d5e517c\",\"chapter_id\":\"410568cf-f62f-486f-5ef5-7e216078c93f\",\"chapter_name\":\"alternating current\",\"status\":\"ACTIVE\"}', 'e127172b-b783-3d10-2b6f-a7954bba4431', '::1', 1668881331, 0.0283489, '1', 403);
INSERT INTO `logs` (`id`, `uri`, `method`, `params`, `api_key`, `ip_address`, `time`, `rtime`, `authorized`, `response_code`) VALUES
(156, 'api/admin/chapters/edit_chapter', 'post', '{\"Accept\":\"application\\/json\",\"Content-Type\":\"application\\/json\",\"session_key\":\"e127172b-b783-3d10-2b6f-a7954bba4431\",\"User-Agent\":\"PostmanRuntime\\/7.29.2\",\"Postman-Token\":\"b7821df0-f422-410b-ab1f-125bc4dd0a17\",\"Host\":\"localhost\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Connection\":\"keep-alive\",\"Content-Length\":\"189\",\"subject_id\":\"079c65b5-87a0-394c-ea08-8bd27d5e517c\",\"chapter_id\":\"882c5908-c4b8-b1fe-72cb-e8d9ffdb67b4\",\"chapter_name\":\"alternating current\",\"status\":\"ACTIVE\"}', 'e127172b-b783-3d10-2b6f-a7954bba4431', '::1', 1668881364, 0.00943303, '1', 403),
(157, 'api/admin/chapters/edit_chapter', 'post', '{\"Accept\":\"application\\/json\",\"Content-Type\":\"application\\/json\",\"session_key\":\"e127172b-b783-3d10-2b6f-a7954bba4431\",\"User-Agent\":\"PostmanRuntime\\/7.29.2\",\"Postman-Token\":\"1e079362-ccdb-493a-b16d-7f7e663b6709\",\"Host\":\"localhost\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Connection\":\"keep-alive\",\"Content-Length\":\"189\",\"subject_id\":\"079c65b5-87a0-394c-ea08-8bd27d5e517c\",\"chapter_id\":\"882c5908-c4b8-b1fe-72cb-e8d9ffdb67b4\",\"chapter_name\":\"alternating current\",\"status\":\"ACTIVE\"}', 'e127172b-b783-3d10-2b6f-a7954bba4431', '::1', 1668881407, 0.0328941, '1', 403),
(158, 'api/admin/chapters/edit_chapter', 'post', '{\"Accept\":\"application\\/json\",\"Content-Type\":\"application\\/json\",\"session_key\":\"e127172b-b783-3d10-2b6f-a7954bba4431\",\"User-Agent\":\"PostmanRuntime\\/7.29.2\",\"Postman-Token\":\"6ec385f3-0c0f-4c11-b6c2-9aca3187cc77\",\"Host\":\"localhost\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Connection\":\"keep-alive\",\"Content-Length\":\"188\",\"course_id\":\"079c65b5-87a0-394c-ea08-8bd27d5e517c\",\"chapter_id\":\"882c5908-c4b8-b1fe-72cb-e8d9ffdb67b4\",\"chapter_name\":\"alternating current\",\"status\":\"ACTIVE\"}', 'e127172b-b783-3d10-2b6f-a7954bba4431', '::1', 1668881418, 0.0286732, '1', 403),
(159, 'api/admin/chapters/edit_chapter', 'post', '{\"Accept\":\"application\\/json\",\"Content-Type\":\"application\\/json\",\"session_key\":\"e127172b-b783-3d10-2b6f-a7954bba4431\",\"User-Agent\":\"PostmanRuntime\\/7.29.2\",\"Postman-Token\":\"574adcbc-d8e3-4e53-a249-58a64fff1f68\",\"Host\":\"localhost\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Connection\":\"keep-alive\",\"Content-Length\":\"245\",\"course_id\":\"500fa8da-08a1-9f62-4f8a-c66c612868f7\",\"chapter_id\":\"882c5908-c4b8-b1fe-72cb-e8d9ffdb67b4\",\"chapter_name\":\"metal and non-metals2\",\"chapter_summary\":\"this is updated description\",\"status\":\"ACTIVE\"}', 'e127172b-b783-3d10-2b6f-a7954bba4431', '::1', 1668881517, 0.0258532, '1', 0),
(160, 'api/admin/chapters/edit_chapter', 'post', '{\"Accept\":\"application\\/json\",\"Content-Type\":\"application\\/json\",\"session_key\":\"e127172b-b783-3d10-2b6f-a7954bba4431\",\"User-Agent\":\"PostmanRuntime\\/7.29.2\",\"Postman-Token\":\"8713c4bf-cca9-4a24-8c2e-b55f35011500\",\"Host\":\"localhost\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Connection\":\"keep-alive\",\"Content-Length\":\"245\",\"course_id\":\"500fa8da-08a1-9f62-4f8a-c66c612868f7\",\"chapter_id\":\"882c5908-c4b8-b1fe-72cb-e8d9ffdb67b4\",\"chapter_name\":\"metal and non-metals2\",\"chapter_summary\":\"this is updated description\",\"status\":\"ACTIVE\"}', 'e127172b-b783-3d10-2b6f-a7954bba4431', '::1', 1668881720, 0.00965786, '1', 0),
(161, 'api/admin/chapters/edit_chapter', 'post', '{\"Accept\":\"application\\/json\",\"Content-Type\":\"application\\/json\",\"session_key\":\"e127172b-b783-3d10-2b6f-a7954bba4431\",\"User-Agent\":\"PostmanRuntime\\/7.29.2\",\"Postman-Token\":\"0c82621e-8503-4473-a4f9-8efce27ed641\",\"Host\":\"localhost\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Connection\":\"keep-alive\",\"Content-Length\":\"245\",\"course_id\":\"500fa8da-08a1-9f62-4f8a-c66c612868f7\",\"chapter_id\":\"882c5908-c4b8-b1fe-72cb-e8d9ffdb67b4\",\"chapter_name\":\"metal and non-metals2\",\"chapter_summary\":\"this is updated description\",\"status\":\"ACTIVE\"}', 'e127172b-b783-3d10-2b6f-a7954bba4431', '::1', 1668881725, 0.012639, '1', 0),
(162, 'api/admin/chapters/edit_chapter', 'post', '{\"Accept\":\"application\\/json\",\"Content-Type\":\"application\\/json\",\"session_key\":\"e127172b-b783-3d10-2b6f-a7954bba4431\",\"User-Agent\":\"PostmanRuntime\\/7.29.2\",\"Postman-Token\":\"58995832-2f49-4f0b-88fc-3a839ad20474\",\"Host\":\"localhost\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Connection\":\"keep-alive\",\"Content-Length\":\"245\",\"course_id\":\"500fa8da-08a1-9f62-4f8a-c66c612868f7\",\"chapter_id\":\"882c5908-c4b8-b1fe-72cb-e8d9ffdb67b4\",\"chapter_name\":\"metal and non-metals2\",\"chapter_summary\":\"this is updated description\",\"status\":\"ACTIVE\"}', 'e127172b-b783-3d10-2b6f-a7954bba4431', '::1', 1668881902, 0.01033, '1', 0),
(163, 'api/admin/chapters/edit_chapter', 'post', '{\"Accept\":\"application\\/json\",\"Content-Type\":\"application\\/json\",\"session_key\":\"e127172b-b783-3d10-2b6f-a7954bba4431\",\"User-Agent\":\"PostmanRuntime\\/7.29.2\",\"Postman-Token\":\"53fa0806-2bdc-4027-8963-b64ebf34a091\",\"Host\":\"localhost\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Connection\":\"keep-alive\",\"Content-Length\":\"245\",\"course_id\":\"500fa8da-08a1-9f62-4f8a-c66c612868f7\",\"chapter_id\":\"882c5908-c4b8-b1fe-72cb-e8d9ffdb67b4\",\"chapter_name\":\"metal and non-metals2\",\"chapter_summary\":\"this is updated description\",\"status\":\"ACTIVE\"}', 'e127172b-b783-3d10-2b6f-a7954bba4431', '::1', 1668881950, 0.034709, '1', 200),
(164, 'api/admin/chapters/edit_chapter', 'post', '{\"Accept\":\"application\\/json\",\"Content-Type\":\"application\\/json\",\"session_key\":\"e127172b-b783-3d10-2b6f-a7954bba4431\",\"User-Agent\":\"PostmanRuntime\\/7.29.2\",\"Postman-Token\":\"5eedee34-72ca-48ec-8aa2-ab4815481edd\",\"Host\":\"localhost\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Connection\":\"keep-alive\",\"Content-Length\":\"244\",\"course_id\":\"500fa8da-08a1-9f62-4f8a-c66c612868f7\",\"chapter_id\":\"882c5908-c4b8-b1fe-72cb-e8d9ffdb67b4\",\"chapter_name\":\"metal and non-metals\",\"chapter_summary\":\"this is updated description\",\"status\":\"ACTIVE\"}', 'e127172b-b783-3d10-2b6f-a7954bba4431', '::1', 1668881969, 0.024472, '1', 200),
(165, 'api/admin/subject/subject_list', 'post', '{\"Accept\":\"application\\/json\",\"Content-Type\":\"application\\/json\",\"session_key\":\"b5ebbfa2-970d-71ab-84f6-a4fae2c12826\",\"User-Agent\":\"PostmanRuntime\\/7.29.2\",\"Postman-Token\":\"4272360c-06b4-4d3f-b281-49b50fcbf94f\",\"Host\":\"localhost\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Connection\":\"keep-alive\",\"Content-Length\":\"177\",\"subject_id\":\"291166ae-8342-87b7-24b1-43d7486e42fd\",\"subject_name\":\"science2\",\"course_id\":\"ac85ab5c-f4fa-5778-1440-d4ee3cf52433\",\"status\":\"ACTIVE\"}', '', '::1', 1668884024, 0.0931549, '0', 401),
(166, 'api/admin/subject/subject_list', 'post', '{\"Accept\":\"application\\/json\",\"Content-Type\":\"application\\/json\",\"session_key\":\"e127172b-b783-3d10-2b6f-a7954bba4431\",\"User-Agent\":\"PostmanRuntime\\/7.29.2\",\"Postman-Token\":\"13705770-d6ab-4e5b-9200-0535788e9a16\",\"Host\":\"localhost\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Connection\":\"keep-alive\",\"Content-Length\":\"177\",\"subject_id\":\"291166ae-8342-87b7-24b1-43d7486e42fd\",\"subject_name\":\"science2\",\"course_id\":\"ac85ab5c-f4fa-5778-1440-d4ee3cf52433\",\"status\":\"ACTIVE\"}', 'e127172b-b783-3d10-2b6f-a7954bba4431', '::1', 1668884034, 0.050319, '1', 0),
(167, 'api/admin/subject/subject_list', 'post', '{\"Accept\":\"application\\/json\",\"Content-Type\":\"application\\/json\",\"session_key\":\"e127172b-b783-3d10-2b6f-a7954bba4431\",\"User-Agent\":\"PostmanRuntime\\/7.29.2\",\"Postman-Token\":\"398d9be6-49aa-4f13-8d5f-388a86185a45\",\"Host\":\"localhost\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Connection\":\"keep-alive\",\"Content-Length\":\"177\",\"subject_id\":\"291166ae-8342-87b7-24b1-43d7486e42fd\",\"subject_name\":\"science2\",\"course_id\":\"ac85ab5c-f4fa-5778-1440-d4ee3cf52433\",\"status\":\"ACTIVE\"}', 'e127172b-b783-3d10-2b6f-a7954bba4431', '::1', 1668884086, 0.0093019, '1', 0),
(168, 'api/admin/chapters/chapter_list', 'post', '{\"Accept\":\"application\\/json\",\"Content-Type\":\"application\\/json\",\"session_key\":\"e127172b-b783-3d10-2b6f-a7954bba4431\",\"User-Agent\":\"PostmanRuntime\\/7.29.2\",\"Postman-Token\":\"18a23c6f-ef5e-4622-a9ac-7f8699a2c099\",\"Host\":\"localhost\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Connection\":\"keep-alive\",\"Content-Length\":\"177\",\"subject_id\":\"291166ae-8342-87b7-24b1-43d7486e42fd\",\"subject_name\":\"science2\",\"course_id\":\"ac85ab5c-f4fa-5778-1440-d4ee3cf52433\",\"status\":\"ACTIVE\"}', 'e127172b-b783-3d10-2b6f-a7954bba4431', '::1', 1668884142, 0.033627, '1', 0),
(169, 'api/admin/chapters/chapter_list', 'post', '{\"Accept\":\"application\\/json\",\"Content-Type\":\"application\\/json\",\"session_key\":\"e127172b-b783-3d10-2b6f-a7954bba4431\",\"User-Agent\":\"PostmanRuntime\\/7.29.2\",\"Postman-Token\":\"59f36215-db96-4b89-ac7a-74feaf7d3d5e\",\"Host\":\"localhost\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Connection\":\"keep-alive\",\"Content-Length\":\"177\",\"subject_id\":\"291166ae-8342-87b7-24b1-43d7486e42fd\",\"subject_name\":\"science2\",\"course_id\":\"ac85ab5c-f4fa-5778-1440-d4ee3cf52433\",\"status\":\"ACTIVE\"}', 'e127172b-b783-3d10-2b6f-a7954bba4431', '::1', 1668884205, 0.0271881, '1', 0),
(170, 'api/admin/chapters/chapter_list', 'post', '{\"Accept\":\"application\\/json\",\"Content-Type\":\"application\\/json\",\"session_key\":\"e127172b-b783-3d10-2b6f-a7954bba4431\",\"User-Agent\":\"PostmanRuntime\\/7.29.2\",\"Postman-Token\":\"6d16c0da-0860-4df2-91f9-ed8064c182c6\",\"Host\":\"localhost\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Connection\":\"keep-alive\",\"Content-Length\":\"177\",\"subject_id\":\"291166ae-8342-87b7-24b1-43d7486e42fd\",\"subject_name\":\"science2\",\"course_id\":\"ac85ab5c-f4fa-5778-1440-d4ee3cf52433\",\"status\":\"ACTIVE\"}', 'e127172b-b783-3d10-2b6f-a7954bba4431', '::1', 1668884238, 0.0336001, '1', 200),
(171, 'api/admin/chapters/chapter_list', 'post', '{\"Accept\":\"application\\/json\",\"Content-Type\":\"application\\/json\",\"session_key\":\"e127172b-b783-3d10-2b6f-a7954bba4431\",\"User-Agent\":\"PostmanRuntime\\/7.29.2\",\"Postman-Token\":\"1020ace1-a071-45cd-b915-c5ec4cb1b221\",\"Host\":\"localhost\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Connection\":\"keep-alive\",\"Content-Length\":\"177\",\"subject_id\":\"291166ae-8342-87b7-24b1-43d7486e42fd\",\"subject_name\":\"science2\",\"course_id\":\"ac85ab5c-f4fa-5778-1440-d4ee3cf52433\",\"status\":\"ACTIVE\"}', 'e127172b-b783-3d10-2b6f-a7954bba4431', '::1', 1668884259, 0.027395, '1', 200),
(172, 'api/admin/chapters/chapter_list', 'post', '{\"Accept\":\"application\\/json\",\"Content-Type\":\"application\\/json\",\"session_key\":\"e127172b-b783-3d10-2b6f-a7954bba4431\",\"User-Agent\":\"PostmanRuntime\\/7.29.2\",\"Postman-Token\":\"9cfe935e-3667-4f6d-a94b-f8a3676e57de\",\"Host\":\"localhost\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Connection\":\"keep-alive\",\"Content-Length\":\"177\",\"subject_id\":\"291166ae-8342-87b7-24b1-43d7486e42fd\",\"subject_name\":\"science2\",\"course_id\":\"ac85ab5c-f4fa-5778-1440-d4ee3cf52433\",\"status\":\"ACTIVE\"}', 'e127172b-b783-3d10-2b6f-a7954bba4431', '::1', 1668884368, 0.0230219, '1', 200),
(173, 'api/admin/chapters/chapter_list', 'post', '{\"Accept\":\"application\\/json\",\"Content-Type\":\"application\\/json\",\"session_key\":\"e127172b-b783-3d10-2b6f-a7954bba4431\",\"User-Agent\":\"PostmanRuntime\\/7.29.2\",\"Postman-Token\":\"6f661dc1-8736-4f94-8d5d-2b1c62f20273\",\"Host\":\"localhost\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Connection\":\"keep-alive\",\"Content-Length\":\"177\",\"subject_id\":\"291166ae-8342-87b7-24b1-43d7486e42fd\",\"subject_name\":\"science2\",\"course_id\":\"ac85ab5c-f4fa-5778-1440-d4ee3cf52433\",\"status\":\"ACTIVE\"}', 'e127172b-b783-3d10-2b6f-a7954bba4431', '::1', 1668884418, 0.019269, '1', 200),
(174, 'api/admin/subject/subject_list', 'post', '{\"Accept\":\"application\\/json\",\"Content-Type\":\"application\\/json\",\"session_key\":\"b5ebbfa2-970d-71ab-84f6-a4fae2c12826\",\"User-Agent\":\"PostmanRuntime\\/7.29.2\",\"Postman-Token\":\"50a002c4-d74b-4e74-8ef8-81c16c78ead4\",\"Host\":\"localhost\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Connection\":\"keep-alive\",\"Content-Length\":\"65\",\"course_id\":\"fe4c3f75-69fd-617c-8e74-5c54451e4cab\"}', '', '::1', 1668884573, 0.0197358, '0', 401),
(175, 'api/admin/subject/subject_list', 'post', '{\"Accept\":\"application\\/json\",\"Content-Type\":\"application\\/json\",\"session_key\":\"e127172b-b783-3d10-2b6f-a7954bba4431\",\"User-Agent\":\"PostmanRuntime\\/7.29.2\",\"Postman-Token\":\"66ab15db-c4a4-4eba-9e53-a48d5a2ee837\",\"Host\":\"localhost\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Connection\":\"keep-alive\",\"Content-Length\":\"65\",\"course_id\":\"fe4c3f75-69fd-617c-8e74-5c54451e4cab\"}', 'e127172b-b783-3d10-2b6f-a7954bba4431', '::1', 1668884584, 0.0210299, '1', 0),
(176, 'api/admin/subject/subject_list', 'post', '{\"Accept\":\"application\\/json\",\"Content-Type\":\"application\\/json\",\"session_key\":\"e127172b-b783-3d10-2b6f-a7954bba4431\",\"User-Agent\":\"PostmanRuntime\\/7.29.2\",\"Postman-Token\":\"5612d224-3351-46ed-b067-1fa3adf77547\",\"Host\":\"localhost\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Connection\":\"keep-alive\",\"Content-Length\":\"65\",\"course_id\":\"fe4c3f75-69fd-617c-8e74-5c54451e4cab\"}', 'e127172b-b783-3d10-2b6f-a7954bba4431', '::1', 1668884683, 0.0230758, '1', 0),
(177, 'api/admin/chapters/get_details_by_id', 'post', '{\"Accept\":\"application\\/json\",\"Content-Type\":\"application\\/json\",\"session_key\":\"e127172b-b783-3d10-2b6f-a7954bba4431\",\"User-Agent\":\"PostmanRuntime\\/7.29.2\",\"Postman-Token\":\"08130acc-b8ff-4ce8-8bcb-48e1cda46331\",\"Host\":\"localhost\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Connection\":\"keep-alive\",\"Content-Length\":\"65\",\"course_id\":\"fe4c3f75-69fd-617c-8e74-5c54451e4cab\"}', 'e127172b-b783-3d10-2b6f-a7954bba4431', '::1', 1668884778, 0.00884104, '1', 403),
(178, 'api/admin/chapters/chapter_list', 'post', '{\"Accept\":\"application\\/json\",\"Content-Type\":\"application\\/json\",\"session_key\":\"e127172b-b783-3d10-2b6f-a7954bba4431\",\"User-Agent\":\"PostmanRuntime\\/7.29.2\",\"Postman-Token\":\"e750bafe-59b0-4df4-b2dd-912bedc21180\",\"Host\":\"localhost\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Connection\":\"keep-alive\",\"Content-Length\":\"177\",\"subject_id\":\"291166ae-8342-87b7-24b1-43d7486e42fd\",\"subject_name\":\"science2\",\"course_id\":\"ac85ab5c-f4fa-5778-1440-d4ee3cf52433\",\"status\":\"ACTIVE\"}', 'e127172b-b783-3d10-2b6f-a7954bba4431', '::1', 1668884861, 0.0347841, '1', 200),
(179, 'api/admin/chapters/get_details_by_id', 'post', '{\"Accept\":\"application\\/json\",\"Content-Type\":\"application\\/json\",\"session_key\":\"e127172b-b783-3d10-2b6f-a7954bba4431\",\"User-Agent\":\"PostmanRuntime\\/7.29.2\",\"Postman-Token\":\"18157663-97c0-407d-873d-d210847e7706\",\"Host\":\"localhost\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Connection\":\"keep-alive\",\"Content-Length\":\"65\",\"course_id\":\"fe4c3f75-69fd-617c-8e74-5c54451e4cab\"}', 'e127172b-b783-3d10-2b6f-a7954bba4431', '::1', 1668884865, 0.0200989, '1', 403),
(180, 'api/admin/chapters/chapter_list_by_course_id', 'post', '{\"Accept\":\"application\\/json\",\"Content-Type\":\"application\\/json\",\"session_key\":\"e127172b-b783-3d10-2b6f-a7954bba4431\",\"User-Agent\":\"PostmanRuntime\\/7.29.2\",\"Postman-Token\":\"10bc6a79-58e0-44a3-8247-66dbf681775e\",\"Host\":\"localhost\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Connection\":\"keep-alive\",\"Content-Length\":\"65\",\"course_id\":\"fe4c3f75-69fd-617c-8e74-5c54451e4cab\"}', 'e127172b-b783-3d10-2b6f-a7954bba4431', '::1', 1668884893, 0.0100222, '1', 403),
(181, 'api/admin/chapters/chapter_list_by_course_id', 'post', '{\"Accept\":\"application\\/json\",\"Content-Type\":\"application\\/json\",\"session_key\":\"e127172b-b783-3d10-2b6f-a7954bba4431\",\"User-Agent\":\"PostmanRuntime\\/7.29.2\",\"Postman-Token\":\"38c6c540-8a37-4602-a741-96221fd4b57e\",\"Host\":\"localhost\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Connection\":\"keep-alive\",\"Content-Length\":\"65\",\"course_id\":\"fe4c3f75-69fd-617c-8e74-5c54451e4cab\"}', 'e127172b-b783-3d10-2b6f-a7954bba4431', '::1', 1668884926, 0.00921106, '1', 403),
(182, 'api/admin/chapters/chapter_list_by_course_id', 'post', '{\"Accept\":\"application\\/json\",\"Content-Type\":\"application\\/json\",\"session_key\":\"e127172b-b783-3d10-2b6f-a7954bba4431\",\"User-Agent\":\"PostmanRuntime\\/7.29.2\",\"Postman-Token\":\"9870593a-c4a4-4304-878d-42ced14fbd50\",\"Host\":\"localhost\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Connection\":\"keep-alive\",\"Content-Length\":\"65\",\"course_id\":\"fe4c3f75-69fd-617c-8e74-5c54451e4cab\"}', 'e127172b-b783-3d10-2b6f-a7954bba4431', '::1', 1668884931, 0.00955915, '1', 403),
(183, 'api/admin/chapters/chapter_list_by_course_id', 'post', '{\"Accept\":\"application\\/json\",\"Content-Type\":\"application\\/json\",\"session_key\":\"e127172b-b783-3d10-2b6f-a7954bba4431\",\"User-Agent\":\"PostmanRuntime\\/7.29.2\",\"Postman-Token\":\"47c53a5d-d368-47b4-bf8c-5b67780287fe\",\"Host\":\"localhost\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Connection\":\"keep-alive\",\"Content-Length\":\"65\",\"course_id\":\"500fa8da-08a1-9f62-4f8a-c66c612868f7\"}', 'e127172b-b783-3d10-2b6f-a7954bba4431', '::1', 1668884963, 0.0108781, '1', 0),
(184, 'api/admin/chapters/chapter_list_by_course_id', 'post', '{\"Accept\":\"application\\/json\",\"Content-Type\":\"application\\/json\",\"session_key\":\"e127172b-b783-3d10-2b6f-a7954bba4431\",\"User-Agent\":\"PostmanRuntime\\/7.29.2\",\"Postman-Token\":\"275c2779-47bb-480a-beec-8825fb94335e\",\"Host\":\"localhost\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Connection\":\"keep-alive\",\"Content-Length\":\"65\",\"course_id\":\"500fa8da-08a1-9f62-4f8a-c66c612868f7\"}', 'e127172b-b783-3d10-2b6f-a7954bba4431', '::1', 1668884993, 0.00848413, '1', 0),
(185, 'api/admin/chapters/chapter_list_by_course_id', 'post', '{\"Accept\":\"application\\/json\",\"Content-Type\":\"application\\/json\",\"session_key\":\"e127172b-b783-3d10-2b6f-a7954bba4431\",\"User-Agent\":\"PostmanRuntime\\/7.29.2\",\"Postman-Token\":\"0b1d9a4c-55ff-4d3c-9c61-0ea0c403700c\",\"Host\":\"localhost\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Connection\":\"keep-alive\",\"Content-Length\":\"65\",\"course_id\":\"500fa8da-08a1-9f62-4f8a-c66c612868f7\"}', 'e127172b-b783-3d10-2b6f-a7954bba4431', '::1', 1668885051, 0.019892, '1', 0),
(186, 'api/admin/chapters/chapter_list_by_course_id', 'post', '{\"Accept\":\"application\\/json\",\"Content-Type\":\"application\\/json\",\"session_key\":\"e127172b-b783-3d10-2b6f-a7954bba4431\",\"User-Agent\":\"PostmanRuntime\\/7.29.2\",\"Postman-Token\":\"87b28917-860e-42c0-9b33-bd1ae2e2a034\",\"Host\":\"localhost\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Connection\":\"keep-alive\",\"Content-Length\":\"65\",\"course_id\":\"500fa8da-08a1-9f62-4f8a-c66c612868f7\"}', 'e127172b-b783-3d10-2b6f-a7954bba4431', '::1', 1668885091, 0.021883, '1', 0),
(187, 'api/admin/chapters/chapter_list_by_course_id', 'post', '{\"Accept\":\"application\\/json\",\"Content-Type\":\"application\\/json\",\"session_key\":\"e127172b-b783-3d10-2b6f-a7954bba4431\",\"User-Agent\":\"PostmanRuntime\\/7.29.2\",\"Postman-Token\":\"b67db9d9-7a4a-4a86-918e-653dfabb293b\",\"Host\":\"localhost\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Connection\":\"keep-alive\",\"Content-Length\":\"65\",\"course_id\":\"500fa8da-08a1-9f62-4f8a-c66c612868f7\"}', 'e127172b-b783-3d10-2b6f-a7954bba4431', '::1', 1668885123, 0.0100179, '1', 200),
(188, 'api/admin/chapters/chapter_list_by_course_id', 'post', '{\"Accept\":\"application\\/json\",\"Content-Type\":\"application\\/json\",\"session_key\":\"e127172b-b783-3d10-2b6f-a7954bba4431\",\"User-Agent\":\"PostmanRuntime\\/7.29.2\",\"Postman-Token\":\"e4d9fca8-a883-4dd0-b2c1-7493bea5bc95\",\"Host\":\"localhost\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Connection\":\"keep-alive\",\"Content-Length\":\"65\",\"course_id\":\"500fa8da-08a1-9f62-4f8a-c66c612868f7\"}', 'e127172b-b783-3d10-2b6f-a7954bba4431', '::1', 1668885153, 0.0100482, '1', 200),
(189, 'api/admin/chapters/chapter_list_by_course_id', 'post', '{\"Accept\":\"application\\/json\",\"Content-Type\":\"application\\/json\",\"session_key\":\"e127172b-b783-3d10-2b6f-a7954bba4431\",\"User-Agent\":\"PostmanRuntime\\/7.29.2\",\"Postman-Token\":\"c9cd7502-0eb4-4f22-8d7c-db05ee759c8b\",\"Host\":\"localhost\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Connection\":\"keep-alive\",\"Content-Length\":\"65\",\"course_id\":\"500fa8da-08a1-9f62-4f8a-c66c612868f7\"}', 'e127172b-b783-3d10-2b6f-a7954bba4431', '::1', 1668885309, 0.010006, '1', 200),
(190, 'api/admin/subject/get_details_by_id', 'post', '{\"Accept\":\"application\\/json\",\"Content-Type\":\"application\\/json\",\"session_key\":\"b5ebbfa2-970d-71ab-84f6-a4fae2c12826\",\"User-Agent\":\"PostmanRuntime\\/7.29.2\",\"Postman-Token\":\"403c8c2f-7768-4b4a-ba33-2e2cdef0d7f3\",\"Host\":\"localhost\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Connection\":\"keep-alive\",\"Content-Length\":\"62\",\"chapter_id\":\"fe4c3f75-69fd-617c-8e74-5c54451e4cab\"}', '', '::1', 1668885360, 0.031842, '0', 401),
(191, 'api/admin/subject/get_details_by_id', 'post', '{\"Accept\":\"application\\/json\",\"Content-Type\":\"application\\/json\",\"session_key\":\"e127172b-b783-3d10-2b6f-a7954bba4431\",\"User-Agent\":\"PostmanRuntime\\/7.29.2\",\"Postman-Token\":\"9d8a1b30-a638-4f95-8300-7bebc632908b\",\"Host\":\"localhost\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Connection\":\"keep-alive\",\"Content-Length\":\"62\",\"chapter_id\":\"fe4c3f75-69fd-617c-8e74-5c54451e4cab\"}', 'e127172b-b783-3d10-2b6f-a7954bba4431', '::1', 1668885370, 0.0206079, '1', 403),
(192, 'api/admin/chapters/get_details_by_id', 'post', '{\"Accept\":\"application\\/json\",\"Content-Type\":\"application\\/json\",\"session_key\":\"e127172b-b783-3d10-2b6f-a7954bba4431\",\"User-Agent\":\"PostmanRuntime\\/7.29.2\",\"Postman-Token\":\"0d96e4e1-cadf-4408-8cff-34bc6238bf2f\",\"Host\":\"localhost\",\"Accept-Encoding\":\"gzip, deflate, br\",\"Connection\":\"keep-alive\",\"Content-Length\":\"62\",\"chapter_id\":\"fe4c3f75-69fd-617c-8e74-5c54451e4cab\"}', 'e127172b-b783-3d10-2b6f-a7954bba4431', '::1', 1668885423, 0.0234919, '1', 200);

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

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
  `question_id` int(11) NOT NULL,
  `question_guid` varchar(40) NOT NULL,
  `quiz_id` int(11) NOT NULL,
  `question_type` enum('MCQ','TF','CM','FIB') NOT NULL COMMENT '{\r\nMCQ = (Multiple choice questions),\r\nTF = (True false based questions),\r\nCM = (correct match),\r\nFIB= (Fill in the blanks)\r\n}',
  `question_title` varchar(500) NOT NULL,
  `question_summary` text NOT NULL,
  `marks` smallint(6) NOT NULL,
  `media` int(11) DEFAULT NULL,
  `correct_answer` text DEFAULT NULL,
  `correct_answer_id` int(11) DEFAULT NULL,
  `s_media` int(11) DEFAULT NULL,
  `status` enum('ACTIVE','DELETE','PENDING','') NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `questions_answers`
--

CREATE TABLE `questions_answers` (
  `qa_id` int(11) NOT NULL,
  `qa_guid` varchar(40) NOT NULL,
  `lesson_id` int(11) NOT NULL,
  `question_title` varchar(500) NOT NULL,
  `question_summary` text NOT NULL,
  `media` int(11) NOT NULL,
  `answer` text NOT NULL,
  `ans_media` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `question_options`
--

CREATE TABLE `question_options` (
  `option_id` int(11) NOT NULL,
  `option_guid` varchar(40) NOT NULL,
  `question_id` int(11) NOT NULL,
  `option_title` varchar(150) NOT NULL,
  `option_summary` varchar(500) NOT NULL,
  `media` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `quizs`
--

CREATE TABLE `quizs` (
  `quiz_id` int(11) NOT NULL,
  `quiz_guid` varchar(40) NOT NULL,
  `quiz_name` int(200) NOT NULL,
  `quiz_summary` varchar(500) NOT NULL,
  `chapter_id` int(11) NOT NULL,
  `quiz_time` time NOT NULL,
  `status` enum('ACTIVE','PENDING','DEACTIVE','DELETE') NOT NULL,
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
(1, '9f4fd1de-f6c3-bb6f-48cd-b51e0b738c4f', 'api/users/login', '{\"email\":\"#202142\",\"password\":\"**********\",\"key\":\"value\"}', '127.0.0.1', '2022-10-09 05:58:56'),
(2, '2e96c4f5-d09b-a853-b2b5-4a05b59be4b1', 'api/users/login', '{\"email\":\"user@user.com\",\"password\":\"********\",\"key\":\"value\"}', '127.0.0.1', '2022-10-09 06:03:16'),
(3, '3e662220-6220-8aef-df01-1208b8417d1b', 'api/users/login', '{\"email\":\"user@user.com\",\"password\":\"********\",\"key\":\"value\"}', '127.0.0.1', '2022-10-09 06:03:42'),
(4, '69d15f39-b9f4-00f6-334f-d5f565e316f5', 'api/users/login', '{\"email\":\"admin@admin.com\",\"password\":\"********\",\"key\":\"value\"}', '127.0.0.1', '2022-10-09 06:28:50'),
(5, '20e78f7e-ea64-8ab7-410a-0e211a4bd6a2', 'api/admin/auth/login', '{\"email\":\"admin@second.com\",\"device_type\":\"web_browser\",\"password\":\"********\",\"key\":\"value\"}', '::1', '2022-11-13 05:55:44'),
(6, '6f808fa9-a129-99f3-df64-049e097df6c8', 'api/admin/auth/login', '{\"email\":\"admin@second.com\",\"device_type\":\"web_browser\",\"password\":\"********\",\"key\":\"value\"}', '::1', '2022-11-18 19:00:26');

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
(1, 'f78710b9-8dcf-2eba-87b9-ab6bf440c674', '#202134', 'test', 'doe', 'mr. john khatwa', 'mis. seema', '2003-04-20', 0, 'czoyNDoibWF0aCwgcGh5c2ljcywgY2hlbWlzdHJ5Ijs=', 0, 'HINDI', 0, 8000, 8000, '0000-00-00', 0, 'shiv shakti academy', 'guru anand nagar indore ', 'john@gmail.com', '8871678840', '', 'ACTIVE', '2022-10-05 18:59:40', '2022-10-05 18:59:40'),
(2, 'e9b3c53b-1229-13b0-6e35-9711d00ba6a0', '#2021389', 'test', 'doe', 'mr. john agarwal', 'mis. seema agrawal', '2003-04-20', 0, 'YTowOnt9', 0, 'HINDI', 0, 8000, 8000, '0000-00-00', 0, 'shiv shakti academy', 'guru anand nagar indore ', 'john@gmail.com', '8871678840', '', 'ACTIVE', '2022-10-09 05:32:50', '2022-10-09 05:32:50'),
(3, 'a9b82b48-9584-c318-8b6a-da782897bb1c', '#202145', 'test', 'doe', 'mr. john agarwal', 'mis. seema agrawal', '2003-04-20', 0, 'YTowOnt9', 0, 'HINDI', 0, 8000, 8000, '0000-00-00', 0, 'shiv shakti academy', 'guru anand nagar indore ', 'john@gmail.com', '8871678840', '', 'ACTIVE', '2022-10-09 05:36:04', '2022-10-09 05:36:04'),
(4, '1feae7a6-b56c-90eb-1426-afdcf630ef2a', '#202142', 'test', 'doe', 'mr. john agarwal', 'mis. seema agrawal', '2003-04-20', 0, 'YTowOnt9', 0, 'HINDI', 0, 8000, 8000, '0000-00-00', 0, 'shiv shakti academy', 'guru anand nagar indore ', 'john@gmail.com', '8871678840', '', 'ACTIVE', '2022-10-09 05:36:40', '2022-10-09 05:36:40'),
(5, '080eb20e-dab5-8d11-cdbe-61ae68bbc200', '#202143', 'jhon', 'smith', 'mr. john agarwal', 'mis. seema agrawal', '0000-00-00', 0, 'YTowOnt9', 0, 'HINDI', 0, 8000, 8000, '0000-00-00', 0, 'shiv shakti academy', 'guru anand nagar indore ', 'john@gmail.com', '8871678840', '', 'ACTIVE', '2022-10-09 05:57:57', '2022-10-09 05:57:57');

-- --------------------------------------------------------

--
-- Table structure for table `submitted_questions`
--

CREATE TABLE `submitted_questions` (
  `sub_question_id` int(11) NOT NULL,
  `sub_question_guid` varchar(40) NOT NULL,
  `squiz_id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `opt_marks` smallint(6) NOT NULL,
  `submitted_answer` varchar(500) NOT NULL,
  `media` int(11) DEFAULT NULL,
  `submitted_answer_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `submitted_quiz`
--

CREATE TABLE `submitted_quiz` (
  `squiz_id` int(11) NOT NULL,
  `squiz_guid` varchar(40) NOT NULL,
  `quiz_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `status` enum('ACTIVE','DEACTIVE','PENDING','DELETE') NOT NULL,
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
  `user_type` enum('OWNER','ADMIN','SITE_MANAGER','USER','STUDENT') NOT NULL DEFAULT 'USER',
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
(1, 'cb326240-0fb7-4957-8997-ccd2d546b733', 'NO', 'ADMIN', 'Admin', '', 'Admin Business', 'admin@admin.com', '25d55ad283aa400af464c76d713c07ad', NULL, NULL, '', 'MALE', NULL, 0, NULL, NULL, NULL, NULL, NULL, 0, 'ACTIVE', '2022-11-01 09:34:51', NULL, '2022-11-18 19:07:21', 'YES', 'YES', '2020-09-15 00:00:00', '0000-00-00 00:00:00'),
(2, 'bfcb3631-0511-e9c9-7aca-640307c91f72', 'NO', 'USER', 'Marketing Tiki', '', 'Individual business Marketing', 'mtsupport@mailinator.com', '25d55ad283aa400af464c76d713c07ad', NULL, NULL, '', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 1, 'ACTIVE', '2020-10-08 14:25:52', NULL, '2020-10-09 15:26:31', 'YES', 'YES', '2020-10-08 06:26:43', '2020-10-08 06:26:43'),
(3, '06ab9888-2b05-4b75-87ec-b34c40943f0d', 'NO', 'USER', 'User name', '', 'Business name', 'user@user.com', '25d55ad283aa400af464c76d713c07ad', NULL, NULL, '', 'MALE', NULL, 0, NULL, NULL, NULL, NULL, NULL, 0, 'ACTIVE', '2022-10-10 21:04:25', NULL, '2022-10-14 11:31:25', 'YES', 'YES', '2020-09-10 10:00:00', '0000-00-00 00:00:00'),
(4, 'd8a95f49-d985-32c9-e4c3-1362189a7432', 'NO', 'USER', 'Brehm Strategic Marketing Group', '', 'Brehm Strategic Marketing Group', 'ryans@mailinator.com', '25d55ad283aa400af464c76d713c07ad', NULL, NULL, '', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 1, 'ACTIVE', NULL, NULL, '2020-10-23 16:01:44', 'YES', 'YES', '2020-10-08 07:13:33', '2020-10-08 07:13:33'),
(5, '549cf9bd-0373-8b81-183b-fa544a17650d', 'NO', 'STUDENT', 'demo', '', 'demo business', 'student@student.com', '25d55ad283aa400af464c76d713c07ad', NULL, NULL, '', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 1, 'ACTIVE', '2022-10-07 07:07:32', NULL, '2022-10-10 20:37:30', 'YES', 'YES', '2020-10-12 12:35:48', '2020-10-12 12:35:48'),
(6, '95a57874-2e95-42a0-c966-e7c83c850842', 'NO', 'USER', 'fjkhgbdrjhg', '', 'fhfghfh', 'braj@datacabinet.systems', '25f9e794323b453885f5181f1b624d0b', NULL, NULL, '', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 1, 'ACTIVE', NULL, NULL, NULL, 'YES', 'YES', '2020-10-12 13:40:28', '2020-10-12 13:40:28'),
(7, '3e8f306e-0e2d-2478-8b5d-62412ff81261', 'NO', 'STUDENT', 'test', 'doe', NULL, '#202142', '8187a85a978e714f58272522a97daa5e', '2003-04-20', NULL, '8871678840', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 0, 'ACTIVE', '2022-10-09 05:43:34', NULL, '2022-10-09 05:54:33', 'YES', 'YES', '2022-10-09 05:36:40', '2022-10-09 05:36:40'),
(8, 'd3d66722-2247-0374-f44c-51a4a1216420', 'NO', 'STUDENT', 'jhon', 'smith', NULL, '#202143', '5e8788b77fca817c9c65368ffce6e96c', '0000-00-00', NULL, '8871678840', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 0, 'ACTIVE', '2022-10-09 05:59:17', NULL, '2022-10-09 05:59:31', 'YES', 'YES', '2022-10-09 05:57:57', '2022-10-09 05:57:57'),
(9, '904cbcab-3162-f297-8d6d-c0e7495484c4', 'NO', 'USER', 'ballast', 'class', NULL, 'mail@gmail.com', '25d55ad283aa400af464c76d713c07ad', NULL, NULL, '9399123182', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 0, 'ACTIVE', NULL, NULL, NULL, 'YES', 'YES', '2022-10-13 18:09:21', '2022-10-13 18:09:21'),
(10, '55e4d586-c1a6-432b-49d6-f00bd2ffb38d', 'NO', 'USER', 'ballast', 'class', NULL, 'mail123@gmail.com', '25d55ad283aa400af464c76d713c07ad', NULL, NULL, '9399123182', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 0, 'ACTIVE', NULL, NULL, NULL, 'YES', 'YES', '2022-10-14 09:50:23', '2022-10-14 09:50:23'),
(11, '2acd5ff4-e157-2f2b-ca10-2f49401fc4be', 'NO', 'USER', 'steave', 'smith', NULL, 'smith@gmail.com', '53db1c7c5f21dc88f253abca0b2ace17', NULL, NULL, '', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 0, 'ACTIVE', NULL, NULL, NULL, 'YES', 'YES', '2022-10-14 11:23:50', '2022-10-14 11:23:50'),
(12, 'b494051f-7acb-5f3a-ff88-6ba75a606f95', 'NO', 'USER', 'jhon', 'doe', NULL, 'brajmohany13@gmail.com', 'ce0596ff204fe1baa6fabd7e507c5415', NULL, NULL, '', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 0, 'ACTIVE', NULL, NULL, '2022-10-14 11:28:20', 'YES', 'YES', '2022-10-14 11:25:03', '2022-10-14 11:25:03'),
(13, 'f62b5d79-f568-e1db-9ecf-e66f50717643', 'NO', 'USER', 'amit', 'pal', NULL, 'ballast@gmail.com', 'ce0596ff204fe1baa6fabd7e507c5415', NULL, NULL, '1234567890', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 0, 'ACTIVE', NULL, NULL, NULL, 'YES', 'YES', '2022-10-14 11:26:13', '2022-10-14 11:26:13');

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
(1, 'ae84ba36-825b-55f8-5a93-9ae73da117d7', 1, 1, NULL, '::1', 'ACTIVE', '2022-10-02 10:45:00', '2022-10-09 17:01:36'),
(2, '759a21c4-1af7-b4c2-7e05-c9198a37eee6', 1, 1, NULL, '127.0.0.1', 'ACTIVE', '2022-10-05 09:05:28', '2022-10-05 09:05:45'),
(3, '3cc5bb09-dec3-1c52-ac6f-b8db8b3c54fc', 1, 1, NULL, '127.0.0.1', 'ACTIVE', '2022-10-05 09:07:13', '2022-10-05 09:07:13'),
(4, '408ba67b-cedd-0e06-7e90-e8476add562b', 3, 1, NULL, '::1', 'ACTIVE', '2022-10-05 10:30:45', '2022-10-05 10:30:45'),
(5, 'b1fe9c33-0e8a-ad66-1ba7-7b4d80d2f02d', 3, 1, NULL, '127.0.0.1', 'ACTIVE', '2022-10-05 10:31:18', '2022-10-05 10:31:18'),
(6, '883ba7fa-5c27-8f75-f36b-5129a65b03f7', 1, 1, NULL, '::1', 'ACTIVE', '2022-10-05 10:33:06', '2022-10-05 10:33:06'),
(7, '6f079374-9c26-2524-39a5-bcfafe049624', 3, 1, NULL, '::1', 'ACTIVE', '2022-10-05 10:36:40', '2022-10-05 10:36:40'),
(8, '4421a856-12f1-6dfa-7c04-8f7faf9f3497', 3, 1, NULL, '127.0.0.1', 'ACTIVE', '2022-10-05 10:39:42', '2022-10-09 17:19:04'),
(9, '9f292835-79d2-39ac-b0ed-cfca24260675', 3, 1, NULL, '127.0.0.1', 'ACTIVE', '2022-10-05 12:27:24', '2022-10-05 13:10:57'),
(10, '60982aed-cf64-636b-829a-42d8007ef015', 3, 1, NULL, '127.0.0.1', 'ACTIVE', '2022-10-05 15:07:35', '2022-10-05 15:07:35'),
(11, 'bfe99a31-7cc3-ba76-accc-2e3af49c1b1c', 1, 1, NULL, '127.0.0.1', 'ACTIVE', '2022-10-05 16:23:51', '2022-10-05 18:34:18'),
(12, 'd6576b67-f36d-d84c-97ce-2e01e5e582ac', 1, 1, NULL, '127.0.0.1', 'ACTIVE', '2022-10-05 18:36:32', '2022-10-06 14:24:29'),
(13, 'ccb873dd-4b62-cd7f-34a1-568cfdc679cc', 1, 1, NULL, '127.0.0.1', 'ACTIVE', '2022-10-07 07:06:18', '2022-10-07 07:06:31'),
(14, '18e1d1ac-c43f-cdf0-af80-7f6802574896', 3, 1, NULL, '127.0.0.1', 'ACTIVE', '2022-10-07 07:06:56', '2022-10-07 07:06:56'),
(15, '5c0ea255-75ca-bef9-09cb-326394247bfb', 5, 1, NULL, '127.0.0.1', 'ACTIVE', '2022-10-07 07:07:32', '2022-10-09 17:19:12'),
(16, '98601e86-5ec6-18be-e0d5-00965e17709a', 1, 1, NULL, '127.0.0.1', 'ACTIVE', '2022-10-07 09:32:37', '2022-10-08 20:24:23'),
(17, '05e79d58-3d47-9471-3c0f-d0ee4e164bc1', 7, 1, NULL, '::1', 'ACTIVE', '2022-10-09 05:43:29', '2022-10-09 05:43:29'),
(18, '81ee85f9-c1a7-0014-79c2-d75b4a0a744d', 7, 1, NULL, '127.0.0.1', 'ACTIVE', '2022-10-09 05:43:34', '2022-10-09 05:43:34'),
(19, 'ec6a2dff-5bea-deae-9884-03db3acda055', 7, 1, NULL, '127.0.0.1', 'ACTIVE', '2022-10-09 05:54:33', '2022-10-09 05:54:33'),
(20, '3bd0943f-baba-7007-c4a6-808cb71088c0', 8, 1, NULL, '::1', 'ACTIVE', '2022-10-09 05:59:17', '2022-10-09 05:59:17'),
(21, 'a6537505-2d39-41d8-7741-53849a1cea5f', 8, 1, NULL, '127.0.0.1', 'ACTIVE', '2022-10-09 05:59:31', '2022-10-09 05:59:31'),
(22, '19e25860-340e-8d5c-78ab-151e90257402', 3, 1, NULL, '127.0.0.1', 'ACTIVE', '2022-10-09 06:02:57', '2022-10-09 06:02:57'),
(23, 'a7f6afcf-2968-03dc-c662-ae0a0cc9aafe', 1, 1, NULL, '127.0.0.1', 'ACTIVE', '2022-10-09 06:28:57', '2022-10-09 09:03:09'),
(24, 'c15887b6-24e9-7e0b-aa32-10821d0bbac6', 1, 1, NULL, '127.0.0.1', 'ACTIVE', '2022-10-09 09:06:23', '2022-10-10 20:35:04'),
(25, '0d532781-b20f-3cf3-5655-8e2d647ef752', 5, 1, NULL, '127.0.0.1', 'ACTIVE', '2022-10-10 20:37:30', '2022-10-10 21:02:36'),
(26, 'e4721736-6153-a51c-60fa-ffdec79e8cda', 3, 1, NULL, '127.0.0.1', 'ACTIVE', '2022-10-10 21:04:25', '2022-10-13 05:10:28'),
(27, '644d122a-3134-2ad6-78f5-f034a6bf3769', 12, 1, NULL, '127.0.0.1', 'ACTIVE', '2022-10-14 11:28:20', '2022-10-14 11:28:25'),
(28, '48c2aeea-26f2-901b-7a18-d5e5fcd74039', 3, 1, NULL, '127.0.0.1', 'ACTIVE', '2022-10-14 11:31:25', '2022-10-14 11:39:44'),
(29, '6a05bf64-4fd5-f271-a2bb-0af5def18fd0', 1, 1, NULL, '127.0.0.1', 'ACTIVE', '2022-10-14 17:57:22', '2022-10-14 18:55:11'),
(30, '518da877-896b-f2ac-5535-db2085626923', 1, 1, NULL, '127.0.0.1', 'ACTIVE', '2022-11-01 09:34:51', '2022-11-01 09:34:51'),
(31, 'c36f48a0-9816-b5a8-3f33-ef300055f78a', 1, 1, NULL, '::1', 'ACTIVE', '2022-11-18 19:01:00', '2022-11-18 19:05:01'),
(32, '276def3e-6d22-ca4e-72bc-ed63abd1f2f9', 1, 1, NULL, '127.0.0.1', 'ACTIVE', '2022-11-18 19:07:21', '2022-11-18 19:33:49'),
(33, 'e127172b-b783-3d10-2b6f-a7954bba4431', 1, 1, NULL, '::1', 'ACTIVE', '2022-11-19 17:13:46', '2022-11-19 19:17:03');

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

-- --------------------------------------------------------

--
-- Table structure for table `verifications`
--

CREATE TABLE `verifications` (
  `verification_id` int(11) NOT NULL,
  `verification_guid` varchar(40) NOT NULL,
  `verification_type` enum('ACCOUNT_VERIFICATION_LINK','ACCOUNT_VERIFICATION_CODE','RESET_PASSWORD_LINK','RESET_PASSWORD_CODE','EMAIL_VERIFICATION_CODE') DEFAULT NULL,
  `verification_target` varchar(100) DEFAULT NULL COMMENT 'verification_target is either email verification or mobile',
  `user_id` int(11) NOT NULL,
  `code` varchar(10) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `used_at` datetime DEFAULT NULL,
  `status` enum('ACTIVE','EXPIRED','USED') NOT NULL DEFAULT 'ACTIVE'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `verifications`
--

INSERT INTO `verifications` (`verification_id`, `verification_guid`, `verification_type`, `verification_target`, `user_id`, `code`, `created_at`, `used_at`, `status`) VALUES
(1, 'd7979ce1-75c2-d2cd-7c13-9267fc243cfd', 'RESET_PASSWORD_LINK', NULL, 6, NULL, '2022-10-13 18:30:51', NULL, 'EXPIRED'),
(2, '55a4b15b-74f6-18cd-ad5e-ad276efe0764', 'RESET_PASSWORD_LINK', NULL, 6, NULL, '2022-10-13 18:32:54', NULL, 'EXPIRED'),
(3, '40322873-39cd-ba83-144b-7233e70b71f7', 'RESET_PASSWORD_LINK', NULL, 6, NULL, '2022-10-13 18:38:03', NULL, 'EXPIRED'),
(4, 'f6e9fb19-ac9b-c069-65bb-2c57f461c816', 'RESET_PASSWORD_LINK', NULL, 6, NULL, '2022-10-13 18:39:48', NULL, 'ACTIVE');

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
-- Indexes for table `correct_answers`
--
ALTER TABLE `correct_answers`
  ADD PRIMARY KEY (`correct_ans_id`);

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
-- Indexes for table `enrolled_courses`
--
ALTER TABLE `enrolled_courses`
  ADD PRIMARY KEY (`enrolled_id`);

--
-- Indexes for table `imp_notices`
--
ALTER TABLE `imp_notices`
  ADD PRIMARY KEY (`notice_id`);

--
-- Indexes for table `lessons`
--
ALTER TABLE `lessons`
  ADD PRIMARY KEY (`lesson_id`);

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
-- Indexes for table `questions_answers`
--
ALTER TABLE `questions_answers`
  ADD PRIMARY KEY (`qa_id`);

--
-- Indexes for table `question_options`
--
ALTER TABLE `question_options`
  ADD PRIMARY KEY (`option_id`);

--
-- Indexes for table `quizs`
--
ALTER TABLE `quizs`
  ADD PRIMARY KEY (`quiz_id`);

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
-- Indexes for table `submitted_questions`
--
ALTER TABLE `submitted_questions`
  ADD PRIMARY KEY (`sub_question_id`);

--
-- Indexes for table `submitted_quiz`
--
ALTER TABLE `submitted_quiz`
  ADD PRIMARY KEY (`squiz_id`);

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
-- Indexes for table `verifications`
--
ALTER TABLE `verifications`
  ADD PRIMARY KEY (`verification_id`);

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
  MODIFY `chapter_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `classes`
--
ALTER TABLE `classes`
  MODIFY `class_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `correct_answers`
--
ALTER TABLE `correct_answers`
  MODIFY `correct_ans_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `countries`
--
ALTER TABLE `countries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=247;

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `course_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `enrolled_courses`
--
ALTER TABLE `enrolled_courses`
  MODIFY `enrolled_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `imp_notices`
--
ALTER TABLE `imp_notices`
  MODIFY `notice_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `lessons`
--
ALTER TABLE `lessons`
  MODIFY `lesson_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `logs`
--
ALTER TABLE `logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=193;

--
-- AUTO_INCREMENT for table `media`
--
ALTER TABLE `media`
  MODIFY `media_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `pay_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
  MODIFY `question_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `questions_answers`
--
ALTER TABLE `questions_answers`
  MODIFY `qa_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `question_options`
--
ALTER TABLE `question_options`
  MODIFY `option_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `quizs`
--
ALTER TABLE `quizs`
  MODIFY `quiz_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `site_logs`
--
ALTER TABLE `site_logs`
  MODIFY `site_log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `student_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `submitted_questions`
--
ALTER TABLE `submitted_questions`
  MODIFY `sub_question_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `submitted_quiz`
--
ALTER TABLE `submitted_quiz`
  MODIFY `squiz_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `user_login_sessions`
--
ALTER TABLE `user_login_sessions`
  MODIFY `user_login_session_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `user_token`
--
ALTER TABLE `user_token`
  MODIFY `user_token_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `verifications`
--
ALTER TABLE `verifications`
  MODIFY `verification_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
