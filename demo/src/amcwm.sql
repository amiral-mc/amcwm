-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 13, 2014 at 01:56 PM
-- Server version: 5.5.34-0ubuntu0.12.04.1
-- PHP Version: 5.3.10-1ubuntu3.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `amcwm`
--

-- --------------------------------------------------------

--
-- Table structure for table `access_rights`
--

CREATE TABLE IF NOT EXISTS `access_rights` (
  `role_id` smallint(5) unsigned NOT NULL,
  `controller_id` mediumint(9) NOT NULL,
  `access` mediumint(8) unsigned DEFAULT '0',
  PRIMARY KEY (`role_id`,`controller_id`),
  KEY `fk_roles_has_pages_roles1` (`role_id`),
  KEY `fk_access_rights_controllers1` (`controller_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `access_rights`
--

INSERT INTO `access_rights` (`role_id`, `controller_id`, `access`) VALUES
(1, 1, 1),
(1, 39, 993),
(1, 41, 97),
(1, 46, 97),
(1, 47, 193),
(1, 48, 7),
(1, 49, 7),
(1, 50, 97),
(1, 51, 7),
(1, 52, 7),
(1, 53, 15),
(1, 55, 1),
(1, 56, 97),
(1, 57, 1),
(1, 59, 33),
(1, 79, 449),
(1, 86, 3),
(1, 90, 97),
(1, 1282, 33),
(1, 1284, 67),
(1, 1285, 3),
(1, 1286, 33),
(1, 1288, 64),
(1, 1290, 33),
(1, 1292, 66),
(1, 1294, 33),
(1, 1296, 64),
(1, 1298, 33),
(1, 1300, 66),
(1, 1302, 33),
(1, 1304, 66),
(2, 1, 33),
(2, 2, 255),
(2, 3, 63),
(2, 4, 63),
(2, 5, 255),
(2, 6, 127),
(2, 7, 127),
(2, 8, 127),
(2, 9, 63),
(2, 10, 127),
(2, 11, 127),
(2, 13, 63),
(2, 14, 31),
(2, 15, 225),
(2, 17, 15),
(2, 18, 31),
(2, 21, 63),
(2, 37, 31),
(2, 60, 7),
(2, 68, 127),
(2, 69, 15),
(2, 70, 15),
(2, 71, 15),
(2, 72, 15),
(2, 73, 15),
(2, 74, 15),
(2, 75, 107),
(2, 83, 29),
(2, 84, 31),
(2, 85, 29),
(2, 87, 15),
(2, 96, 1),
(2, 97, 31),
(2, 98, 31),
(2, 99, 95),
(2, 100, 31),
(2, 1200, 31),
(2, 1201, 15),
(3, 12, 63),
(3, 16, 15),
(3, 37, 63),
(3, 58, 5),
(3, 1199, 1),
(3, 1254, 255),
(3, 1255, 13),
(3, 1256, 125),
(3, 1257, 61),
(3, 1258, 255),
(3, 1259, 13),
(3, 1260, 125),
(3, 1261, 61),
(3, 1262, 255),
(3, 1263, 13),
(3, 1264, 125),
(3, 1265, 61),
(3, 1266, 255),
(3, 1267, 13),
(3, 1268, 125),
(3, 1269, 61),
(3, 1270, 255),
(3, 1271, 13),
(3, 1272, 125),
(3, 1273, 61),
(3, 1274, 255),
(3, 1275, 13),
(3, 1276, 125),
(3, 1277, 61),
(3, 1278, 255),
(3, 1279, 13),
(3, 1280, 125),
(3, 1281, 61),
(4, 39, 993),
(4, 41, 481),
(4, 59, 33),
(4, 91, 15),
(4, 92, 101),
(4, 1283, 63),
(4, 1287, 63),
(4, 1288, 3),
(4, 1289, 3),
(4, 1291, 63),
(4, 1292, 1),
(4, 1293, 3),
(4, 1295, 63),
(4, 1296, 3),
(4, 1297, 3),
(4, 1299, 63),
(4, 1300, 1),
(4, 1301, 3),
(4, 1303, 63),
(4, 1304, 1),
(4, 1305, 3);

-- --------------------------------------------------------

--
-- Table structure for table `actions`
--

CREATE TABLE IF NOT EXISTS `actions` (
  `action_id` mediumint(9) NOT NULL AUTO_INCREMENT,
  `controller_id` mediumint(9) NOT NULL,
  `action` varchar(30) DEFAULT NULL,
  `permissions` smallint(5) unsigned DEFAULT '1',
  `is_system` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`action_id`),
  KEY `fk_actions_controllers1` (`controller_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7222 ;

--
-- Dumping data for table `actions`
--

INSERT INTO `actions` (`action_id`, `controller_id`, `action`, `permissions`, `is_system`) VALUES
(3, 1, 'index', 32, 0),
(4, 2, 'index', 1, 0),
(5, 2, 'view', 1, 0),
(6, 2, 'create', 2, 0),
(7, 2, 'update', 4, 0),
(8, 2, 'delete', 8, 0),
(9, 2, 'publish', 16, 0),
(10, 2, 'backgrounds', 32, 0),
(11, 4, 'index', 1, 0),
(12, 4, 'view', 1, 0),
(13, 4, 'create', 2, 0),
(14, 4, 'update', 4, 0),
(15, 4, 'delete', 8, 0),
(16, 4, 'publish', 16, 0),
(17, 4, 'comments', 32, 0),
(18, 7, 'index', 1, 0),
(19, 7, 'view', 1, 0),
(20, 7, 'create', 2, 0),
(21, 7, 'update', 4, 0),
(22, 7, 'delete', 8, 0),
(23, 7, 'publish', 16, 0),
(24, 7, 'replies', 64, 0),
(25, 10, 'index', 1, 0),
(26, 10, 'view', 1, 0),
(27, 10, 'create', 2, 0),
(28, 10, 'update', 4, 0),
(29, 10, 'delete', 8, 0),
(30, 10, 'publish', 16, 0),
(31, 2, 'images', 64, 0),
(32, 3, 'index', 1, 0),
(33, 3, 'view', 1, 0),
(34, 3, 'create', 2, 0),
(35, 3, 'update', 4, 0),
(36, 3, 'delete', 8, 0),
(37, 3, 'publish', 16, 0),
(38, 3, 'comments', 32, 0),
(39, 6, 'index', 1, 0),
(40, 6, 'view', 1, 0),
(41, 6, 'create', 2, 0),
(42, 6, 'update', 4, 0),
(43, 6, 'delete', 8, 0),
(44, 6, 'publish', 16, 0),
(45, 6, 'replies', 64, 0),
(46, 9, 'index', 1, 0),
(47, 9, 'view', 1, 0),
(48, 9, 'create', 2, 0),
(49, 9, 'update', 4, 0),
(50, 9, 'delete', 8, 0),
(51, 9, 'publish', 16, 0),
(52, 2, 'videos', 128, 0),
(53, 5, 'index', 1, 0),
(54, 5, 'view', 1, 0),
(55, 5, 'create', 2, 0),
(56, 5, 'update', 4, 0),
(57, 5, 'delete', 8, 0),
(58, 5, 'publish', 16, 0),
(59, 5, 'comments', 32, 0),
(60, 8, 'index', 1, 0),
(61, 8, 'view', 1, 0),
(62, 8, 'create', 2, 0),
(63, 8, 'update', 4, 0),
(64, 8, 'delete', 8, 0),
(65, 8, 'publish', 16, 0),
(66, 8, 'replies', 64, 0),
(67, 11, 'index', 1, 0),
(68, 11, 'view', 1, 0),
(69, 11, 'create', 2, 0),
(70, 11, 'update', 4, 0),
(71, 11, 'delete', 8, 0),
(72, 11, 'publish', 16, 0),
(73, 12, 'index', 1, 0),
(74, 12, 'view', 1, 0),
(75, 12, 'create', 2, 0),
(76, 12, 'update', 4, 0),
(77, 12, 'delete', 8, 0),
(78, 12, 'publish', 16, 0),
(79, 12, 'permissions', 32, 0),
(80, 13, 'index', 1, 0),
(81, 13, 'view', 1, 0),
(82, 13, 'create', 2, 0),
(83, 13, 'update', 4, 0),
(84, 13, 'delete', 8, 0),
(85, 13, 'supervisors', 32, 0),
(86, 14, 'index', 1, 0),
(87, 14, 'view', 1, 0),
(88, 14, 'create', 2, 0),
(89, 14, 'update', 4, 0),
(90, 14, 'delete', 8, 0),
(92, 5, 'sort', 1, 0),
(93, 15, 'index', 1, 0),
(94, 8, 'hide', 32, 0),
(95, 11, 'hide', 32, 0),
(96, 6, 'hide', 32, 0),
(97, 9, 'hide', 32, 0),
(98, 3, 'sort', 1, 0),
(99, 4, 'sort', 1, 0),
(100, 7, 'hide', 32, 0),
(101, 10, 'hide', 32, 0),
(102, 16, 'index', 1, 0),
(103, 16, 'view', 1, 0),
(104, 16, 'create', 2, 0),
(105, 16, 'update', 4, 0),
(106, 16, 'delete', 8, 0),
(107, 17, 'index', 1, 0),
(108, 17, 'view', 1, 0),
(109, 17, 'create', 2, 0),
(110, 17, 'update', 4, 0),
(111, 17, 'delete', 8, 0),
(112, 18, 'index', 1, 0),
(113, 18, 'view', 1, 0),
(114, 18, 'create', 2, 0),
(115, 18, 'update', 4, 0),
(116, 18, 'delete', 8, 0),
(117, 18, 'publish', 16, 0),
(120, 21, 'index', 1, 0),
(121, 21, 'view', 1, 0),
(122, 21, 'create', 2, 0),
(123, 21, 'update', 4, 0),
(124, 21, 'delete', 8, 0),
(125, 21, 'publish', 16, 0),
(126, 21, 'pollResults', 32, 0),
(242, 37, 'index', 1, 0),
(243, 37, 'view', 1, 0),
(244, 37, 'create', 2, 0),
(245, 37, 'update', 4, 0),
(246, 37, 'delete', 8, 0),
(247, 37, 'publish', 16, 0),
(250, 13, 'publish', 16, 0),
(256, 39, 'contact', 64, 0),
(257, 39, 'index', 128, 0),
(265, 41, 'register', 32, 0),
(266, 41, 'reset', 64, 0),
(267, 41, 'profile', 128, 0),
(269, 41, 'index', 256, 0),
(281, 39, 'vote', 256, 0),
(284, 13, 'sort', 1, 0),
(293, 46, 'index', 1, 0),
(294, 46, 'videos', 32, 0),
(295, 47, 'index', 1, 0),
(296, 47, 'view', 1, 0),
(297, 47, 'dopeSheet', 32, 0),
(298, 47, 'comments', 64, 0),
(299, 48, 'index', 1, 0),
(300, 48, 'view', 1, 0),
(301, 48, 'create', 2, 0),
(302, 47, 'replies', 128, 0),
(303, 49, 'index', 1, 0),
(304, 49, 'view', 1, 0),
(305, 49, 'create', 2, 0),
(306, 46, 'images', 64, 0),
(307, 50, 'index', 1, 0),
(308, 50, 'view', 1, 0),
(309, 50, 'comments', 32, 0),
(310, 51, 'index', 1, 0),
(311, 51, 'view', 1, 0),
(312, 51, 'create', 2, 0),
(313, 50, 'replies', 64, 0),
(314, 52, 'index', 1, 0),
(315, 52, 'view', 1, 0),
(316, 52, 'create', 2, 0),
(317, 50, 'topList', 1, 0),
(318, 47, 'topList', 1, 0),
(319, 5, 'dopeSheet', 64, 0),
(326, 53, 'index', 2, 0),
(327, 53, 'subscribe', 2, 0),
(328, 53, 'activate', 4, 0),
(329, 53, 'unsubscribe', 8, 0),
(332, 48, 'like', 4, 0),
(333, 49, 'like', 4, 0),
(334, 51, 'like', 4, 0),
(335, 52, 'like', 4, 0),
(338, 55, 'index', 1, 0),
(339, 55, 'breaking', 1, 0),
(344, 56, 'contact', 64, 0),
(345, 56, 'index', 1, 0),
(346, 56, 'articles', 1, 0),
(347, 56, 'details', 1, 0),
(348, 56, 'mostRead', 1, 0),
(349, 57, 'index', 1, 0),
(352, 57, 'hotNewsData', 1, 0),
(353, 58, 'index', 1, 0),
(354, 58, 'update', 4, 0),
(355, 37, 'services', 32, 0),
(356, 59, 'index', 1, 0),
(357, 59, 'view', 1, 0),
(358, 59, 'request', 32, 0),
(359, 57, 'xml', 1, 0),
(360, 60, 'index', 1, 0),
(361, 60, 'view', 1, 0),
(362, 60, 'create', 2, 0),
(363, 60, 'update', 4, 0),
(364, 60, 'delete', 8, 0),
(365, 53, 'view', 1, 0),
(366, 53, 'log', 1, 0),
(367, 53, 'article', 1, 0),
(401, 68, 'create', 2, 0),
(402, 68, 'update', 4, 0),
(403, 68, 'index', 1, 0),
(405, 68, 'view', 1, 0),
(406, 68, 'delete', 8, 0),
(407, 69, 'create', 2, 0),
(408, 69, 'update', 4, 0),
(409, 69, 'index', 1, 0),
(411, 69, 'view', 1, 0),
(412, 69, 'delete', 8, 0),
(413, 70, 'create', 2, 0),
(414, 70, 'update', 4, 0),
(415, 70, 'index', 1, 0),
(417, 70, 'view', 1, 0),
(418, 70, 'delete', 8, 0),
(419, 71, 'create', 2, 0),
(420, 71, 'update', 4, 0),
(421, 71, 'index', 1, 0),
(423, 71, 'view', 1, 0),
(424, 71, 'delete', 8, 0),
(425, 72, 'create', 2, 0),
(426, 72, 'update', 4, 0),
(427, 72, 'index', 1, 0),
(429, 72, 'view', 1, 0),
(430, 72, 'delete', 8, 0),
(431, 73, 'create', 2, 0),
(432, 73, 'update', 4, 0),
(433, 73, 'index', 1, 0),
(435, 73, 'view', 1, 0),
(436, 73, 'delete', 8, 0),
(437, 73, 'items', 1, 0),
(438, 74, 'index', 1, 0),
(439, 74, 'view', 1, 0),
(440, 74, 'create', 2, 0),
(441, 74, 'update', 4, 0),
(442, 74, 'delete', 8, 0),
(443, 5, 'dopeSheetTranslate', 128, 0),
(445, 15, 'manageFiles', 32, 0),
(446, 15, 'manageFolders', 64, 0),
(447, 15, 'ajax', 128, 0),
(448, 75, 'index', 1, 0),
(449, 75, 'save', 2, 0),
(450, 75, 'delete', 8, 0),
(451, 75, 'sort', 32, 0),
(452, 75, 'ajax', 64, 0),
(477, 79, 'index', 1, 0),
(478, 79, 'view', 64, 0),
(479, 79, 'countryList', 128, 0),
(480, 79, 'viewArticle', 256, 0),
(493, 83, 'index', 1, 0),
(494, 83, 'view', 1, 0),
(495, 83, 'update', 4, 0),
(496, 83, 'delete', 8, 0),
(497, 83, 'accept', 16, 0),
(498, 79, 'request', 1, 0),
(499, 84, 'index', 1, 0),
(500, 84, 'view', 1, 0),
(501, 84, 'create', 2, 0),
(502, 84, 'update', 4, 0),
(503, 84, 'delete', 8, 0),
(504, 84, 'publish', 16, 0),
(505, 85, 'index', 1, 0),
(506, 85, 'view', 1, 0),
(507, 85, 'update', 4, 0),
(508, 85, 'delete', 8, 0),
(509, 85, 'accept', 16, 0),
(510, 86, 'index', 1, 0),
(511, 86, 'request', 2, 0),
(512, 87, 'index', 1, 0),
(513, 87, 'view', 1, 0),
(514, 87, 'create', 2, 0),
(515, 87, 'update', 4, 0),
(516, 87, 'delete', 8, 0),
(517, 90, 'index', 1, 0),
(518, 90, 'list', 32, 0),
(519, 90, 'viewArticle', 64, 0),
(520, 91, 'index', 1, 0),
(521, 91, 'view', 1, 0),
(522, 91, 'create', 2, 0),
(523, 91, 'update', 4, 0),
(524, 91, 'delete', 8, 0),
(525, 68, 'publish', 16, 0),
(526, 68, 'companyArticles', 32, 0),
(527, 92, 'view', 1, 0),
(528, 92, 'update', 4, 0),
(529, 92, 'articles', 32, 0),
(530, 92, 'translate', 64, 0),
(555, 68, 'generateUser', 64, 0),
(556, 96, 'index', 1, 0),
(557, 96, 'view', 1, 0),
(558, 96, 'update', 1, 0),
(559, 96, 'create', 1, 0),
(560, 96, 'delete', 1, 0),
(561, 96, 'publish', 1, 0),
(562, 96, 'comments', 1, 0),
(563, 96, 'translate', 1, 0),
(564, 97, 'index', 1, 0),
(565, 97, 'view', 1, 0),
(566, 97, 'update', 4, 0),
(567, 97, 'create', 2, 0),
(568, 97, 'delete', 8, 0),
(569, 97, 'publish', 16, 0),
(571, 98, 'index', 1, 0),
(572, 98, 'view', 1, 0),
(573, 98, 'update', 4, 0),
(574, 98, 'create', 2, 0),
(575, 98, 'delete', 8, 0),
(576, 98, 'publish', 16, 0),
(578, 99, 'index', 1, 0),
(579, 99, 'view', 1, 0),
(580, 99, 'update', 4, 0),
(581, 99, 'create', 2, 0),
(582, 99, 'delete', 8, 0),
(583, 99, 'publish', 16, 0),
(584, 99, 'replies', 64, 0),
(585, 100, 'index', 1, 0),
(586, 100, 'view', 1, 0),
(587, 100, 'update', 4, 0),
(588, 100, 'create', 2, 0),
(589, 100, 'delete', 8, 0),
(590, 100, 'publish', 16, 0),
(591, 1, 'login', 1, 0),
(592, 39, 'login', 1, 0),
(593, 1, 'logout', 1, 0),
(594, 39, 'logout', 1, 0),
(6629, 1199, 'index', 1, 0),
(6630, 1199, 'view', 1, 0),
(6631, 1200, 'index', 1, 0),
(6632, 1200, 'view', 1, 0),
(6633, 1200, 'create', 2, 0),
(6634, 1200, 'update', 4, 0),
(6635, 1200, 'delete', 8, 0),
(6636, 1200, 'servers', 16, 0),
(6637, 1201, 'index', 1, 0),
(6638, 1201, 'view', 1, 0),
(6639, 1201, 'create', 2, 0),
(6640, 1201, 'update', 4, 0),
(6641, 1201, 'delete', 8, 0),
(6932, 1254, 'index', 1, 0),
(6933, 1254, 'view', 1, 0),
(6934, 1254, 'create', 2, 0),
(6935, 1254, 'update', 4, 0),
(6936, 1254, 'delete', 8, 0),
(6937, 1254, 'publish', 16, 0),
(6938, 1254, 'sort', 32, 0),
(6939, 1254, 'comments', 64, 0),
(6940, 1254, 'sources', 128, 0),
(6941, 1255, 'index', 1, 0),
(6942, 1255, 'view', 1, 0),
(6943, 1255, 'update', 4, 0),
(6944, 1255, 'delete', 8, 0),
(6945, 1256, 'index', 1, 0),
(6946, 1256, 'view', 1, 0),
(6947, 1256, 'update', 4, 0),
(6948, 1256, 'delete', 8, 0),
(6949, 1256, 'publish', 16, 0),
(6950, 1256, 'hide', 32, 0),
(6951, 1256, 'replies', 64, 0),
(6952, 1257, 'index', 1, 0),
(6953, 1257, 'view', 1, 0),
(6954, 1257, 'update', 4, 0),
(6955, 1257, 'delete', 8, 0),
(6956, 1257, 'publish', 16, 0),
(6957, 1257, 'hide', 32, 0),
(6958, 1258, 'index', 1, 0),
(6959, 1258, 'view', 1, 0),
(6960, 1258, 'create', 2, 0),
(6961, 1258, 'update', 4, 0),
(6962, 1258, 'delete', 8, 0),
(6963, 1258, 'publish', 16, 0),
(6964, 1258, 'sort', 32, 0),
(6965, 1258, 'comments', 64, 0),
(6966, 1258, 'sources', 128, 0),
(6967, 1259, 'index', 1, 0),
(6968, 1259, 'view', 1, 0),
(6969, 1259, 'update', 4, 0),
(6970, 1259, 'delete', 8, 0),
(6971, 1260, 'index', 1, 0),
(6972, 1260, 'view', 1, 0),
(6973, 1260, 'update', 4, 0),
(6974, 1260, 'delete', 8, 0),
(6975, 1260, 'publish', 16, 0),
(6976, 1260, 'hide', 32, 0),
(6977, 1260, 'replies', 64, 0),
(6978, 1261, 'index', 1, 0),
(6979, 1261, 'view', 1, 0),
(6980, 1261, 'update', 4, 0),
(6981, 1261, 'delete', 8, 0),
(6982, 1261, 'publish', 16, 0),
(6983, 1261, 'hide', 32, 0),
(6984, 1262, 'index', 1, 0),
(6985, 1262, 'view', 1, 0),
(6986, 1262, 'create', 2, 0),
(6987, 1262, 'update', 4, 0),
(6988, 1262, 'delete', 8, 0),
(6989, 1262, 'publish', 16, 0),
(6990, 1262, 'sort', 32, 0),
(6991, 1262, 'comments', 64, 0),
(6992, 1262, 'sources', 128, 0),
(6993, 1263, 'index', 1, 0),
(6994, 1263, 'view', 1, 0),
(6995, 1263, 'update', 4, 0),
(6996, 1263, 'delete', 8, 0),
(6997, 1264, 'index', 1, 0),
(6998, 1264, 'view', 1, 0),
(6999, 1264, 'update', 4, 0),
(7000, 1264, 'delete', 8, 0),
(7001, 1264, 'publish', 16, 0),
(7002, 1264, 'hide', 32, 0),
(7003, 1264, 'replies', 64, 0),
(7004, 1265, 'index', 1, 0),
(7005, 1265, 'view', 1, 0),
(7006, 1265, 'update', 4, 0),
(7007, 1265, 'delete', 8, 0),
(7008, 1265, 'publish', 16, 0),
(7009, 1265, 'hide', 32, 0),
(7010, 1266, 'index', 1, 0),
(7011, 1266, 'view', 1, 0),
(7012, 1266, 'create', 2, 0),
(7013, 1266, 'update', 4, 0),
(7014, 1266, 'delete', 8, 0),
(7015, 1266, 'publish', 16, 0),
(7016, 1266, 'sort', 32, 0),
(7017, 1266, 'comments', 64, 0),
(7018, 1266, 'sources', 128, 0),
(7019, 1267, 'index', 1, 0),
(7020, 1267, 'view', 1, 0),
(7021, 1267, 'update', 4, 0),
(7022, 1267, 'delete', 8, 0),
(7023, 1268, 'index', 1, 0),
(7024, 1268, 'view', 1, 0),
(7025, 1268, 'update', 4, 0),
(7026, 1268, 'delete', 8, 0),
(7027, 1268, 'publish', 16, 0),
(7028, 1268, 'hide', 32, 0),
(7029, 1268, 'replies', 64, 0),
(7030, 1269, 'index', 1, 0),
(7031, 1269, 'view', 1, 0),
(7032, 1269, 'update', 4, 0),
(7033, 1269, 'delete', 8, 0),
(7034, 1269, 'publish', 16, 0),
(7035, 1269, 'hide', 32, 0),
(7036, 1270, 'index', 1, 0),
(7037, 1270, 'view', 1, 0),
(7038, 1270, 'create', 2, 0),
(7039, 1270, 'update', 4, 0),
(7040, 1270, 'delete', 8, 0),
(7041, 1270, 'publish', 16, 0),
(7042, 1270, 'sort', 32, 0),
(7043, 1270, 'comments', 64, 0),
(7044, 1270, 'sources', 128, 0),
(7045, 1271, 'index', 1, 0),
(7046, 1271, 'view', 1, 0),
(7047, 1271, 'update', 4, 0),
(7048, 1271, 'delete', 8, 0),
(7049, 1272, 'index', 1, 0),
(7050, 1272, 'view', 1, 0),
(7051, 1272, 'update', 4, 0),
(7052, 1272, 'delete', 8, 0),
(7053, 1272, 'publish', 16, 0),
(7054, 1272, 'hide', 32, 0),
(7055, 1272, 'replies', 64, 0),
(7056, 1273, 'index', 1, 0),
(7057, 1273, 'view', 1, 0),
(7058, 1273, 'update', 4, 0),
(7059, 1273, 'delete', 8, 0),
(7060, 1273, 'publish', 16, 0),
(7061, 1273, 'hide', 32, 0),
(7062, 1274, 'index', 1, 0),
(7063, 1274, 'view', 1, 0),
(7064, 1274, 'create', 2, 0),
(7065, 1274, 'update', 4, 0),
(7066, 1274, 'delete', 8, 0),
(7067, 1274, 'publish', 16, 0),
(7068, 1274, 'sort', 32, 0),
(7069, 1274, 'comments', 64, 0),
(7070, 1274, 'sources', 128, 0),
(7071, 1275, 'index', 1, 0),
(7072, 1275, 'view', 1, 0),
(7073, 1275, 'update', 4, 0),
(7074, 1275, 'delete', 8, 0),
(7075, 1276, 'index', 1, 0),
(7076, 1276, 'view', 1, 0),
(7077, 1276, 'update', 4, 0),
(7078, 1276, 'delete', 8, 0),
(7079, 1276, 'publish', 16, 0),
(7080, 1276, 'hide', 32, 0),
(7081, 1276, 'replies', 64, 0),
(7082, 1277, 'index', 1, 0),
(7083, 1277, 'view', 1, 0),
(7084, 1277, 'update', 4, 0),
(7085, 1277, 'delete', 8, 0),
(7086, 1277, 'publish', 16, 0),
(7087, 1277, 'hide', 32, 0),
(7088, 1278, 'index', 1, 0),
(7089, 1278, 'view', 1, 0),
(7090, 1278, 'create', 2, 0),
(7091, 1278, 'update', 4, 0),
(7092, 1278, 'delete', 8, 0),
(7093, 1278, 'publish', 16, 0),
(7094, 1278, 'sort', 32, 0),
(7095, 1278, 'comments', 64, 0),
(7096, 1278, 'sources', 128, 0),
(7097, 1279, 'index', 1, 0),
(7098, 1279, 'view', 1, 0),
(7099, 1279, 'update', 4, 0),
(7100, 1279, 'delete', 8, 0),
(7101, 1280, 'index', 1, 0),
(7102, 1280, 'view', 1, 0),
(7103, 1280, 'update', 4, 0),
(7104, 1280, 'delete', 8, 0),
(7105, 1280, 'publish', 16, 0),
(7106, 1280, 'hide', 32, 0),
(7107, 1280, 'replies', 64, 0),
(7108, 1281, 'index', 1, 0),
(7109, 1281, 'view', 1, 0),
(7110, 1281, 'update', 4, 0),
(7111, 1281, 'delete', 8, 0),
(7112, 1281, 'publish', 16, 0),
(7113, 1281, 'hide', 32, 0),
(7114, 1282, 'index', 1, 0),
(7115, 1282, 'sections', 1, 0),
(7116, 1282, 'view', 1, 0),
(7117, 1282, 'comments', 32, 0),
(7118, 1283, 'index', 1, 0),
(7119, 1283, 'view', 1, 0),
(7120, 1283, 'create', 2, 0),
(7121, 1283, 'update', 4, 0),
(7122, 1283, 'delete', 8, 0),
(7123, 1283, 'publish', 16, 0),
(7124, 1283, 'sort', 32, 0),
(7125, 1284, 'index', 1, 0),
(7126, 1284, 'view', 1, 0),
(7127, 1284, 'create', 2, 0),
(7128, 1284, 'replies', 64, 0),
(7129, 1285, 'index', 1, 0),
(7130, 1285, 'view', 1, 0),
(7131, 1285, 'create', 2, 0),
(7132, 1286, 'index', 1, 0),
(7133, 1286, 'sections', 1, 0),
(7134, 1286, 'view', 1, 0),
(7135, 1286, 'comments', 32, 0),
(7136, 1287, 'index', 1, 0),
(7137, 1287, 'view', 1, 0),
(7138, 1287, 'create', 2, 0),
(7139, 1287, 'update', 4, 0),
(7140, 1287, 'delete', 8, 0),
(7141, 1287, 'publish', 16, 0),
(7142, 1287, 'sort', 32, 0),
(7143, 1288, 'index', 1, 0),
(7144, 1288, 'view', 1, 0),
(7145, 1288, 'create', 2, 0),
(7146, 1288, 'replies', 64, 0),
(7147, 1289, 'index', 1, 0),
(7148, 1289, 'view', 1, 0),
(7149, 1289, 'create', 2, 0),
(7150, 1290, 'index', 1, 0),
(7151, 1290, 'sections', 1, 0),
(7152, 1290, 'view', 1, 0),
(7153, 1290, 'comments', 32, 0),
(7154, 1291, 'index', 1, 0),
(7155, 1291, 'view', 1, 0),
(7156, 1291, 'create', 2, 0),
(7157, 1291, 'update', 4, 0),
(7158, 1291, 'delete', 8, 0),
(7159, 1291, 'publish', 16, 0),
(7160, 1291, 'sort', 32, 0),
(7161, 1292, 'index', 1, 0),
(7162, 1292, 'view', 1, 0),
(7163, 1292, 'create', 2, 0),
(7164, 1292, 'replies', 64, 0),
(7165, 1293, 'index', 1, 0),
(7166, 1293, 'view', 1, 0),
(7167, 1293, 'create', 2, 0),
(7168, 1294, 'index', 1, 0),
(7169, 1294, 'sections', 1, 0),
(7170, 1294, 'view', 1, 0),
(7171, 1294, 'comments', 32, 0),
(7172, 1295, 'index', 1, 0),
(7173, 1295, 'view', 1, 0),
(7174, 1295, 'create', 2, 0),
(7175, 1295, 'update', 4, 0),
(7176, 1295, 'delete', 8, 0),
(7177, 1295, 'publish', 16, 0),
(7178, 1295, 'sort', 32, 0),
(7179, 1296, 'index', 1, 0),
(7180, 1296, 'view', 1, 0),
(7181, 1296, 'create', 2, 0),
(7182, 1296, 'replies', 64, 0),
(7183, 1297, 'index', 1, 0),
(7184, 1297, 'view', 1, 0),
(7185, 1297, 'create', 2, 0),
(7186, 1298, 'index', 1, 0),
(7187, 1298, 'sections', 1, 0),
(7188, 1298, 'view', 1, 0),
(7189, 1298, 'comments', 32, 0),
(7190, 1299, 'index', 1, 0),
(7191, 1299, 'view', 1, 0),
(7192, 1299, 'create', 2, 0),
(7193, 1299, 'update', 4, 0),
(7194, 1299, 'delete', 8, 0),
(7195, 1299, 'publish', 16, 0),
(7196, 1299, 'sort', 32, 0),
(7197, 1300, 'index', 1, 0),
(7198, 1300, 'view', 1, 0),
(7199, 1300, 'create', 2, 0),
(7200, 1300, 'replies', 64, 0),
(7201, 1301, 'index', 1, 0),
(7202, 1301, 'view', 1, 0),
(7203, 1301, 'create', 2, 0),
(7204, 1302, 'index', 1, 0),
(7205, 1302, 'sections', 1, 0),
(7206, 1302, 'view', 1, 0),
(7207, 1302, 'comments', 32, 0),
(7208, 1303, 'index', 1, 0),
(7209, 1303, 'view', 1, 0),
(7210, 1303, 'create', 2, 0),
(7211, 1303, 'update', 4, 0),
(7212, 1303, 'delete', 8, 0),
(7213, 1303, 'publish', 16, 0),
(7214, 1303, 'sort', 32, 0),
(7215, 1304, 'index', 1, 0),
(7216, 1304, 'view', 1, 0),
(7217, 1304, 'create', 2, 0),
(7218, 1304, 'replies', 64, 0),
(7219, 1305, 'index', 1, 0),
(7220, 1305, 'view', 1, 0),
(7221, 1305, 'create', 2, 0);

-- --------------------------------------------------------

--
-- Table structure for table `ads_servers_config`
--

CREATE TABLE IF NOT EXISTS `ads_servers_config` (
  `server_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `header_code` text NOT NULL,
  `server_name` varchar(35) NOT NULL,
  PRIMARY KEY (`server_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `ads_zones`
--

CREATE TABLE IF NOT EXISTS `ads_zones` (
  `ad_id` smallint(6) NOT NULL AUTO_INCREMENT,
  `server_id` smallint(5) unsigned NOT NULL,
  `zone_id` tinyint(3) unsigned NOT NULL,
  `invocation_code` text NOT NULL,
  `published` tinyint(1) NOT NULL,
  PRIMARY KEY (`ad_id`),
  KEY `fk_ads_zones_ads_servers_config1_idx` (`server_id`),
  KEY `fk_ads_zones_default_ads_zones1_idx` (`zone_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `ads_zones_has_sections`
--

CREATE TABLE IF NOT EXISTS `ads_zones_has_sections` (
  `ad_id` smallint(6) NOT NULL,
  `section_id` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`ad_id`,`section_id`),
  KEY `fk_ads_zones_has_sections_sections1_idx` (`section_id`),
  KEY `fk_ads_zones_has_sections_ads_zones1_idx` (`ad_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `articles`
--

CREATE TABLE IF NOT EXISTS `articles` (
  `article_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `section_id` smallint(5) unsigned DEFAULT NULL,
  `votes` int(10) unsigned DEFAULT '0',
  `votes_rate` double DEFAULT '1',
  `hits` int(10) unsigned DEFAULT '0',
  `published` tinyint(4) DEFAULT '1',
  `archive` tinyint(1) DEFAULT '0',
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `writer_id` int(10) unsigned DEFAULT NULL,
  `publish_date` datetime NOT NULL,
  `expire_date` datetime DEFAULT NULL,
  `published_mobile` tinyint(1) DEFAULT '1',
  `thumb` varchar(3) DEFAULT '0',
  `page_img` varchar(3) DEFAULT NULL,
  `country_code` char(2) DEFAULT NULL,
  `update_date` datetime DEFAULT NULL,
  `comments` int(10) unsigned DEFAULT '0',
  `article_sort` int(10) unsigned DEFAULT '0',
  `in_ticker` tinyint(1) DEFAULT '0',
  `in_slider` varchar(3) DEFAULT NULL,
  `in_spot` tinyint(1) DEFAULT '0',
  `in_list` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `parent_article` int(10) unsigned DEFAULT NULL,
  `is_system` tinyint(1) NOT NULL DEFAULT '0',
  `shared` int(10) unsigned DEFAULT '0',
  PRIMARY KEY (`article_id`),
  KEY `fk_articles_writers1` (`writer_id`),
  KEY `articles_create_date_idx` (`create_date`),
  KEY `articles_hits_idx` (`hits`),
  KEY `fk_articles_countries1` (`country_code`),
  KEY `fk_articles_sections1` (`section_id`),
  KEY `fk_articles_articles1` (`parent_article`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `articles_comments`
--

CREATE TABLE IF NOT EXISTS `articles_comments` (
  `article_comment_id` int(10) unsigned NOT NULL,
  `article_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`article_comment_id`),
  KEY `fk_articles_comments_articles1` (`article_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `articles_titles`
--

CREATE TABLE IF NOT EXISTS `articles_titles` (
  `title_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `article_id` int(10) unsigned NOT NULL,
  `content_lang` char(2) NOT NULL,
  `title` varchar(500) NOT NULL,
  PRIMARY KEY (`title_id`),
  KEY `fk_articles_titles_articles1` (`article_id`,`content_lang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `articles_translation`
--

CREATE TABLE IF NOT EXISTS `articles_translation` (
  `article_id` int(10) unsigned NOT NULL,
  `content_lang` char(2) NOT NULL,
  `article_header` varchar(500) NOT NULL,
  `article_pri_header` varchar(500) DEFAULT NULL,
  `article_detail` text,
  `tags` varchar(1024) DEFAULT NULL,
  `image_description` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`article_id`,`content_lang`),
  KEY `fk_articles_translation_1` (`article_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `attachment`
--

CREATE TABLE IF NOT EXISTS `attachment` (
  `attach_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ref_id` int(10) unsigned NOT NULL,
  `module_id` mediumint(9) NOT NULL,
  `table_id` tinyint(3) unsigned NOT NULL,
  `content_type` enum('IMAGE','INTERNAL_VIDEO','EXTERNAL_VIDEO','LINK') DEFAULT NULL,
  `attach_url` varchar(100) DEFAULT NULL,
  `create_date` datetime NOT NULL,
  `attach_sort` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`attach_id`),
  KEY `fk_attachment_modules1` (`module_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `attachment_translation`
--

CREATE TABLE IF NOT EXISTS `attachment_translation` (
  `attach_id` int(10) unsigned NOT NULL,
  `content_lang` char(2) NOT NULL,
  `title` varchar(100) DEFAULT NULL,
  `description` tinytext,
  PRIMARY KEY (`attach_id`,`content_lang`),
  KEY `fk_attachment_translation` (`attach_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE IF NOT EXISTS `comments` (
  `comment_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `comment_review` int(10) unsigned DEFAULT NULL,
  `comment_header` varchar(100) NOT NULL,
  `comment` text NOT NULL,
  `published` tinyint(1) DEFAULT '0',
  `comment_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `ip` varchar(15) NOT NULL,
  `hide` tinyint(1) NOT NULL DEFAULT '0',
  `user_id` int(10) unsigned DEFAULT NULL,
  `bad_imp` int(10) unsigned DEFAULT '0',
  `good_imp` int(10) unsigned DEFAULT '0',
  `force_display` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`comment_id`),
  KEY `fk_comments_users1` (`user_id`),
  KEY `fk_comments_comments1` (`comment_review`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `comments_owners`
--

CREATE TABLE IF NOT EXISTS `comments_owners` (
  `comment_id` int(10) unsigned NOT NULL,
  `name` varchar(40) NOT NULL,
  `email` varchar(100) NOT NULL,
  PRIMARY KEY (`comment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `configuration`
--

CREATE TABLE IF NOT EXISTS `configuration` (
  `content_lang` char(2) NOT NULL,
  `config` text,
  PRIMARY KEY (`content_lang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `configuration`
--

INSERT INTO `configuration` (`content_lang`, `config`) VALUES
('ar', 'YToxOntzOjY6ImN1c3RvbSI7YToxOntzOjU6ImZyb250IjthOjE6e3M6NDoic2l0ZSI7YTo1OntzOjU6InRpdGxlIjtzOjc6IldlYlNpdGUiO3M6ODoia2V5d29yZHMiO3M6NzoiV2ViU2l0ZSI7czoxMToiZGVzY3JpcHRpb24iO3M6NzoiV2ViU2l0ZSI7czoxMDoibmV3c190aXRsZSI7czo0OiJOZXdzIjtzOjE1OiJuZXdzX3RpdGxlX2luZm8iO3M6NDoiTmV3cyI7fX19fQ=='),
('en', 'YToxOntzOjY6ImN1c3RvbSI7YToxOntzOjU6ImZyb250IjthOjE6e3M6NDoic2l0ZSI7YTo1OntzOjU6InRpdGxlIjtzOjc6IldlYlNpdGUiO3M6ODoia2V5d29yZHMiO3M6NzoiV2ViU2l0ZSI7czoxMToiZGVzY3JpcHRpb24iO3M6NzoiV2ViU2l0ZSI7czoxMDoibmV3c190aXRsZSI7czo0OiJOZXdzIjtzOjE1OiJuZXdzX3RpdGxlX2luZm8iO3M6NDoiTmV3cyI7fX19fQ==');

-- --------------------------------------------------------

--
-- Table structure for table `controllers`
--

CREATE TABLE IF NOT EXISTS `controllers` (
  `controller_id` mediumint(9) NOT NULL AUTO_INCREMENT,
  `module_id` mediumint(9) NOT NULL,
  `controller` varchar(30) DEFAULT NULL,
  `hidden` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`controller_id`),
  KEY `fk_controllers_modules1` (`module_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1306 ;

--
-- Dumping data for table `controllers`
--

INSERT INTO `controllers` (`controller_id`, `module_id`, `controller`, `hidden`) VALUES
(1, 1, 'default', 1),
(2, 2, 'default', 0),
(3, 2, 'images', 0),
(4, 2, 'backgrounds', 0),
(5, 2, 'videos', 0),
(6, 2, 'imagesComments', 0),
(7, 2, 'backgroundsComments', 0),
(8, 2, 'videosComments', 0),
(9, 2, 'repliesImages', 0),
(10, 2, 'repliesBackgrounds', 0),
(11, 2, 'repliesVideos', 0),
(12, 3, 'default', 0),
(13, 4, 'default', 0),
(14, 4, 'supervisors', 0),
(15, 5, 'default', 1),
(16, 6, 'default', 0),
(17, 7, 'default', 1),
(18, 8, 'default', 0),
(21, 10, 'default', 0),
(37, 16, 'default', 0),
(39, 18, 'site', 1),
(41, 20, 'default', 0),
(46, 23, 'default', 0),
(47, 23, 'videos', 0),
(48, 23, 'videoComments', 0),
(49, 23, 'videoReplies', 0),
(50, 23, 'images', 0),
(51, 23, 'imageComments', 0),
(52, 23, 'imageReplies', 0),
(53, 24, 'default', 0),
(55, 26, 'default', 0),
(56, 27, 'mobile', 1),
(57, 28, 'api', 1),
(58, 29, 'default', 0),
(59, 30, 'default', 0),
(60, 31, 'default', 0),
(68, 34, 'default', 0),
(69, 34, 'branches', 0),
(70, 34, 'categories', 0),
(71, 35, 'default', 0),
(72, 35, 'categories', 0),
(73, 36, 'default', 0),
(74, 37, 'default', 0),
(75, 38, 'attachment', 0),
(79, 40, 'default', 0),
(83, 34, 'requests', 0),
(84, 42, 'default', 0),
(85, 42, 'jobs', 0),
(86, 43, 'default', 0),
(87, 29, 'attributes', 0),
(88, 42, 'categories', 0),
(89, 42, 'usersCvs', 0),
(90, 44, 'default', 0),
(91, 40, 'branches', 0),
(92, 40, 'members', 0),
(96, 45, 'default', 0),
(97, 45, 'departments', 0),
(98, 45, 'activities', 0),
(99, 45, 'questions', 0),
(100, 45, 'repliesTenders', 0),
(1199, 82, 'default', 0),
(1200, 83, 'default', 0),
(1201, 83, 'servers', 0),
(1254, 15, 'default', 0),
(1255, 15, 'sources', 0),
(1256, 15, 'comments', 0),
(1257, 15, 'replies', 0),
(1258, 11, 'default', 0),
(1259, 11, 'sources', 0),
(1260, 11, 'comments', 0),
(1261, 11, 'replies', 0),
(1262, 76, 'default', 0),
(1263, 76, 'sources', 0),
(1264, 76, 'comments', 0),
(1265, 76, 'replies', 0),
(1266, 77, 'default', 0),
(1267, 77, 'sources', 0),
(1268, 77, 'comments', 0),
(1269, 77, 'replies', 0),
(1270, 14, 'default', 0),
(1271, 14, 'sources', 0),
(1272, 14, 'comments', 0),
(1273, 14, 'replies', 0),
(1274, 39, 'default', 0),
(1275, 39, 'sources', 0),
(1276, 39, 'comments', 0),
(1277, 39, 'replies', 0),
(1278, 78, 'default', 0),
(1279, 78, 'sources', 0),
(1280, 78, 'comments', 0),
(1281, 78, 'replies', 0),
(1282, 32, 'default', 0),
(1283, 32, 'manage', 0),
(1284, 32, 'comments', 0),
(1285, 32, 'replies', 0),
(1286, 22, 'default', 0),
(1287, 22, 'manage', 0),
(1288, 22, 'comments', 0),
(1289, 22, 'replies', 0),
(1290, 79, 'default', 0),
(1291, 79, 'manage', 0),
(1292, 79, 'comments', 0),
(1293, 79, 'replies', 0),
(1294, 80, 'default', 0),
(1295, 80, 'manage', 0),
(1296, 80, 'comments', 0),
(1297, 80, 'replies', 0),
(1298, 41, 'default', 0),
(1299, 41, 'manage', 0),
(1300, 41, 'comments', 0),
(1301, 41, 'replies', 0),
(1302, 81, 'default', 0),
(1303, 81, 'manage', 0),
(1304, 81, 'comments', 0),
(1305, 81, 'replies', 0);

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

CREATE TABLE IF NOT EXISTS `countries` (
  `code` char(2) NOT NULL,
  `currency_code` varchar(3) DEFAULT NULL,
  `latitude` float DEFAULT NULL,
  `longitude` float DEFAULT NULL,
  `published` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`code`),
  KEY `fk_countries_currency1` (`currency_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `countries`
--

INSERT INTO `countries` (`code`, `currency_code`, `latitude`, `longitude`, `published`) VALUES
('AD', NULL, 42.5, 1.5, 1),
('AE', 'AED', 24, 54, 1),
('AF', NULL, 33, 65, 1),
('AG', NULL, 17.05, -61.8, 1),
('AI', NULL, 18.25, -63.1667, 1),
('AL', NULL, 41, 20, 1),
('AM', NULL, 40, 45, 1),
('AN', NULL, 12.25, -68.75, 1),
('AO', NULL, -12.5, 18.5, 1),
('AQ', NULL, -90, 0, 1),
('AR', NULL, -34, -64, 1),
('AS', NULL, -14.3333, -170, 1),
('AT', NULL, 47.3333, 13.3333, 1),
('AU', NULL, -27, 133, 1),
('AW', NULL, 12.5, -69.9667, 1),
('AZ', NULL, 40.5, 47.5, 1),
('BA', NULL, 44, 18, 1),
('BB', NULL, 13.1667, -59.5333, 1),
('BD', NULL, 24, 90, 1),
('BE', NULL, 50.8333, 4, 1),
('BF', NULL, 13, -2, 1),
('BG', NULL, 43, 25, 1),
('BH', 'BHD', 26, 50.55, 1),
('BI', NULL, -3.5, 30, 1),
('BJ', NULL, 9.5, 2.25, 1),
('BM', NULL, 32.3333, -64.75, 1),
('BN', NULL, 4.5, 114.667, 1),
('BO', NULL, -17, -65, 1),
('BR', NULL, -10, -55, 1),
('BS', NULL, 24.25, -76, 1),
('BT', NULL, 27.5, 90.5, 1),
('BV', NULL, -54.4333, 3.4, 1),
('BW', NULL, -22, 24, 1),
('BY', NULL, 53, 28, 1),
('BZ', NULL, 17.25, -88.75, 1),
('CA', 'CAD', 60, -95, 1),
('CC', NULL, -12.5, 96.8333, 1),
('CD', NULL, 0, 25, 1),
('CF', NULL, 7, 21, 1),
('CG', NULL, -1, 15, 1),
('CH', NULL, 47, 8, 1),
('CI', NULL, 8, -5, 1),
('CK', NULL, -21.2333, -159.767, 1),
('CL', NULL, -30, -71, 1),
('CM', NULL, 6, 12, 1),
('CN', 'CNY', 35, 105, 1),
('CO', NULL, 4, -72, 1),
('CR', NULL, 10, -84, 1),
('CU', NULL, 21.5, -80, 1),
('CV', NULL, 16, -24, 1),
('CX', NULL, -10.5, 105.667, 1),
('CY', NULL, 35, 33, 1),
('CZ', NULL, 49.75, 15.5, 1),
('DE', 'EUR', 51, 9, 1),
('DJ', NULL, 11.5, 43, 1),
('DK', NULL, 56, 10, 1),
('DM', NULL, 15.4167, -61.3333, 1),
('DO', NULL, 19, -70.6667, 1),
('DZ', NULL, 28, 3, 1),
('EC', NULL, -2, -77.5, 1),
('EE', NULL, 59, 26, 1),
('EG', 'EGP', 27, 30, 1),
('EH', NULL, 24.5, -13, 1),
('ER', NULL, 15, 39, 1),
('ES', NULL, 40, -4, 1),
('ET', NULL, 8, 38, 1),
('FI', NULL, 64, 26, 1),
('FJ', NULL, -18, 175, 1),
('FK', NULL, -51.75, -59, 1),
('FM', NULL, 6.9167, 158.25, 1),
('FO', NULL, 62, -7, 1),
('FR', 'EUR', 46, 2, 1),
('GA', NULL, -1, 11.75, 1),
('GB', NULL, 54, -2, 1),
('GD', NULL, 12.1167, -61.6667, 1),
('GE', NULL, 42, 43.5, 1),
('GF', NULL, 4, -53, 1),
('GH', NULL, 8, -2, 1),
('GI', NULL, 36.1833, -5.3667, 1),
('GL', NULL, 72, -40, 1),
('GM', NULL, 13.4667, -16.5667, 1),
('GN', NULL, 11, -10, 1),
('GP', NULL, 16.25, -61.5833, 1),
('GQ', NULL, 2, 10, 1),
('GR', NULL, 39, 22, 1),
('GS', NULL, -54.5, -37, 1),
('GT', NULL, 15.5, -90.25, 1),
('GU', NULL, 13.4667, 144.783, 1),
('GW', NULL, 12, -15, 1),
('GY', NULL, 5, -59, 1),
('HK', NULL, 22.25, 114.167, 1),
('HM', NULL, -53.1, 72.5167, 1),
('HN', NULL, 15, -86.5, 1),
('HR', NULL, 45.1667, 15.5, 1),
('HT', NULL, 19, -72.4167, 1),
('HU', NULL, 47, 20, 1),
('ID', NULL, -5, 120, 1),
('IE', NULL, 53, -8, 1),
('IL', NULL, 31.5, 34.75, 1),
('IN', NULL, 20, 77, 1),
('IO', NULL, -6, 71.5, 1),
('IQ', 'IQD', 33, 44, 1),
('IR', 'IRR', 32, 53, 1),
('IS', NULL, 65, -18, 1),
('IT', NULL, 42.8333, 12.8333, 1),
('JM', NULL, 18.25, -77.5, 1),
('JO', 'JOD', 31, 36, 1),
('JP', 'JPY', 36, 138, 1),
('KE', NULL, 1, 38, 1),
('KG', NULL, 41, 75, 1),
('KH', NULL, 13, 105, 1),
('KI', NULL, 1.4167, 173, 1),
('KM', NULL, -12.1667, 44.25, 1),
('KN', NULL, 17.3333, -62.75, 1),
('KP', NULL, 40, 127, 1),
('KR', NULL, 37, 127.5, 1),
('KW', 'KWD', 29.3375, 47.6581, 1),
('KY', NULL, 19.5, -80.5, 1),
('KZ', NULL, 48, 68, 1),
('LA', NULL, 18, 105, 1),
('LB', 'LBP', 33.8333, 35.8333, 1),
('LC', NULL, 13.8833, -61.1333, 1),
('LI', NULL, 47.1667, 9.5333, 1),
('LK', NULL, 7, 81, 1),
('LR', NULL, 6.5, -9.5, 1),
('LS', NULL, -29.5, 28.5, 1),
('LT', NULL, 56, 24, 1),
('LU', NULL, 49.75, 6.1667, 1),
('LV', NULL, 57, 25, 1),
('LY', 'LYD', 25, 17, 1),
('MA', 'MAD', 32, -5, 1),
('MC', NULL, 43.7333, 7.4, 1),
('MD', NULL, 47, 29, 1),
('ME', NULL, 42, 19, 1),
('MF', NULL, 0, 0, 1),
('MG', NULL, -20, 47, 1),
('MH', NULL, 9, 168, 1),
('MK', NULL, 41.8333, 22, 1),
('ML', NULL, 17, -4, 1),
('MM', NULL, 22, 98, 1),
('MN', NULL, 46, 105, 1),
('MO', NULL, 22.1667, 113.55, 1),
('MP', NULL, 15.2, 145.75, 1),
('MQ', NULL, 14.6667, -61, 1),
('MR', NULL, 20, -12, 1),
('MS', NULL, 16.75, -62.2, 1),
('MT', NULL, 35.8333, 14.5833, 1),
('MU', NULL, -20.2833, 57.55, 1),
('MV', NULL, 3.25, 73, 1),
('MW', NULL, -13.5, 34, 1),
('MX', NULL, 23, -102, 1),
('MY', NULL, 2.5, 112.5, 1),
('MZ', NULL, -18.25, 35, 1),
('NA', NULL, -22, 17, 1),
('NC', NULL, -21.5, 165.5, 1),
('NE', NULL, 16, 8, 1),
('NF', NULL, -29.0333, 167.95, 1),
('NG', NULL, 10, 8, 1),
('NI', NULL, 13, -85, 1),
('NL', NULL, 52.5, 5.75, 1),
('NO', NULL, 62, 10, 1),
('NP', NULL, 28, 84, 1),
('NR', NULL, -0.5333, 166.917, 1),
('NU', NULL, -19.0333, -169.867, 1),
('NZ', NULL, -41, 174, 1),
('OM', 'OMR', 21, 57, 1),
('PA', NULL, 9, -80, 1),
('PE', NULL, -10, -76, 1),
('PF', NULL, -15, -140, 1),
('PG', NULL, -6, 147, 1),
('PH', NULL, 13, 122, 1),
('PK', NULL, 30, 70, 1),
('PL', NULL, 52, 20, 1),
('PM', NULL, 46.8333, -56.3333, 1),
('PN', NULL, 0, 0, 1),
('PR', NULL, 18.25, -66.5, 1),
('PS', 'ILS', 32, 35.25, 1),
('PT', NULL, 39.5, -8, 1),
('PW', NULL, 7.5, 134.5, 1),
('PY', NULL, -23, -58, 1),
('QA', 'QAR', 25.5, 51.25, 1),
('RE', NULL, -21.1, 55.6, 1),
('RO', NULL, 46, 25, 1),
('RS', NULL, 44, 21, 1),
('RU', NULL, 60, 100, 1),
('RW', NULL, -2, 30, 1),
('SA', 'SAR', 25, 45, 1),
('SB', NULL, -8, 159, 1),
('SC', NULL, -4.5833, 55.6667, 1),
('SD', 'SDG', 15, 30, 1),
('SE', NULL, 62, 15, 1),
('SG', NULL, 1.3667, 103.8, 1),
('SH', NULL, -15.9333, -5.7, 1),
('SI', NULL, 46, 15, 1),
('SJ', NULL, 78, 20, 1),
('SK', NULL, 48.6667, 19.5, 1),
('SL', NULL, 8.5, -11.5, 1),
('SM', NULL, 43.7667, 12.4167, 1),
('SN', NULL, 14, -14, 1),
('SO', 'SOS', 10, 49, 1),
('SR', NULL, 4, -56, 1),
('ST', NULL, 1, 7, 1),
('SV', NULL, 13.8333, -88.9167, 1),
('SY', 'SYP', 35, 38, 1),
('SZ', NULL, -26.5, 31.5, 1),
('TC', NULL, 21.75, -71.5833, 1),
('TD', NULL, 15, 19, 1),
('TF', NULL, -43, 67, 1),
('TG', NULL, 8, 1.1667, 1),
('TH', NULL, 15, 100, 1),
('TJ', NULL, 39, 71, 1),
('TK', NULL, -9, -172, 1),
('TL', NULL, 0, 0, 1),
('TM', NULL, 40, 60, 1),
('TN', 'TND', 34, 9, 1),
('TO', NULL, -20, -175, 1),
('TR', 'TRY', 39, 35, 1),
('TT', NULL, 11, -61, 1),
('TV', NULL, -8, 178, 1),
('TW', NULL, 23.5, 121, 1),
('TZ', NULL, -6, 35, 1),
('UA', NULL, 49, 32, 1),
('UG', NULL, 1, 32, 1),
('UM', NULL, 19.2833, 166.6, 1),
('US', NULL, 38, -97, 1),
('UY', NULL, -33, -56, 1),
('UZ', NULL, 41, 64, 1),
('VA', NULL, 41.9, 12.45, 1),
('VC', NULL, 13.25, -61.2, 1),
('VE', NULL, 8, -66, 1),
('VG', NULL, 18.5, -64.5, 1),
('VI', NULL, 18.3333, -64.8333, 1),
('VN', NULL, 16, 106, 1),
('VU', NULL, -16, 167, 1),
('WF', NULL, -13.3, -176.2, 1),
('WS', NULL, -13.5833, -172.333, 1),
('YE', 'YER', 15, 48, 1),
('YT', NULL, -12.8333, 45.1667, 1),
('ZA', NULL, -29, 24, 1),
('ZM', NULL, -15, 30, 1),
('ZW', NULL, -20, 30, 1);

-- --------------------------------------------------------

--
-- Table structure for table `countries_translation`
--

CREATE TABLE IF NOT EXISTS `countries_translation` (
  `code` char(2) NOT NULL,
  `content_lang` char(2) NOT NULL,
  `country` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`code`,`content_lang`),
  KEY `fk_countries_translation` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `countries_translation`
--

INSERT INTO `countries_translation` (`code`, `content_lang`, `country`) VALUES
('AD', 'ar', 'أندورا'),
('AD', 'en', 'Andorra'),
('AE', 'ar', 'الإمارات العربية المتحدة'),
('AE', 'en', 'United Arab Emirates'),
('AF', 'ar', 'أفغانستان'),
('AF', 'en', 'Afghanistan'),
('AG', 'ar', 'أنتيغوا وبربودا'),
('AG', 'en', 'Antigua and Barbuda'),
('AI', 'ar', 'أنجويلا'),
('AI', 'en', 'Anguilla'),
('AL', 'ar', 'ألبانيا'),
('AL', 'en', 'Albania'),
('AM', 'ar', 'أرمينيا'),
('AM', 'en', 'Armenia'),
('AN', 'ar', 'جزر الأنتيل الهولندية'),
('AN', 'en', 'Netherlands Antilles'),
('AO', 'ar', 'أنغولا'),
('AO', 'en', 'Angola'),
('AQ', 'ar', 'أنتاركتيكا'),
('AQ', 'en', 'Antarctica'),
('AR', 'ar', 'الأرجنتين'),
('AR', 'en', 'Argentina'),
('AS', 'ar', 'ساموا الأمريكية'),
('AS', 'en', 'American Samoa'),
('AT', 'ar', 'النمسا'),
('AT', 'en', 'Austria'),
('AU', 'ar', 'أستراليا'),
('AU', 'en', 'Australia'),
('AW', 'ar', 'أروبا'),
('AW', 'en', 'Aruba'),
('AZ', 'ar', 'أذربيجان'),
('AZ', 'en', 'Azerbaijan'),
('BA', 'ar', 'البوسنة والهرسك'),
('BA', 'en', 'Bosnia and Herzegovina'),
('BB', 'ar', 'بربادوس'),
('BB', 'en', 'Barbados'),
('BD', 'ar', 'بنغلاديش'),
('BD', 'en', 'Bangladesh'),
('BE', 'ar', 'بلجيكا'),
('BE', 'en', 'Belgium'),
('BF', 'ar', 'بوركينا فاسو'),
('BF', 'en', 'Burkina Faso'),
('BG', 'ar', 'بلغاريا'),
('BG', 'en', 'Bulgaria'),
('BH', 'ar', 'البحرين'),
('BH', 'en', 'Bahrain'),
('BI', 'ar', 'بوروندي'),
('BI', 'en', 'Burundi'),
('BJ', 'ar', 'بينين'),
('BJ', 'en', 'Benin'),
('BM', 'ar', 'برمودا'),
('BM', 'en', 'Bermuda'),
('BN', 'ar', 'بروناي'),
('BN', 'en', 'Brunei Darussalam'),
('BO', 'ar', 'بوليفيا'),
('BO', 'en', 'Bolivia'),
('BR', 'ar', 'البرازيل'),
('BR', 'en', 'Brazil'),
('BS', 'ar', 'جزر البهاما'),
('BS', 'en', 'Bahamas'),
('BT', 'ar', 'بوتان'),
('BT', 'en', 'Bhutan'),
('BV', 'ar', 'جزيرة بوفيه'),
('BV', 'en', 'Bouvet Island'),
('BW', 'ar', 'بتسوانا'),
('BW', 'en', 'Botswana'),
('BY', 'ar', 'روسيا البيضاء'),
('BY', 'en', 'Belarus'),
('BZ', 'ar', 'بليز'),
('BZ', 'en', 'Belize'),
('CA', 'ar', 'كندا'),
('CA', 'en', 'Canada'),
('CC', 'ar', 'جزر كوكوس'),
('CC', 'en', 'Cocos (Keeling) Islands'),
('CD', 'ar', 'جمهورية الكونغو الديمقراطية'),
('CD', 'en', 'Congo, The Democratic Republic of the'),
('CF', 'ar', 'جمهورية أفريقيا الوسطى'),
('CF', 'en', 'Central African Republic'),
('CG', 'ar', 'الكونغو'),
('CG', 'en', 'Congo'),
('CH', 'ar', 'سويسرا'),
('CH', 'en', 'Switzerland'),
('CI', 'ar', 'ساحل العاج'),
('CI', 'en', 'Ivory Coast'),
('CK', 'ar', 'جزر كوك'),
('CK', 'en', 'Cook Islands'),
('CL', 'ar', 'تشيلي'),
('CL', 'en', 'Chile'),
('CM', 'ar', 'الكاميرون'),
('CM', 'en', 'Cameroon'),
('CN', 'ar', 'الصين'),
('CN', 'en', 'China'),
('CO', 'ar', 'كولومبيا'),
('CO', 'en', 'Colombia'),
('CR', 'ar', 'كوستاريكا'),
('CR', 'en', 'Costa Rica'),
('CU', 'ar', 'كوبا'),
('CU', 'en', 'Cuba'),
('CV', 'ar', 'جزيرة الرأس الأخضر'),
('CV', 'en', 'Cape Verde'),
('CX', 'ar', 'جزيرة الكريسماس'),
('CX', 'en', 'Christmas Island'),
('CY', 'ar', 'قبرص'),
('CY', 'en', 'Cyprus'),
('CZ', 'ar', 'جمهورية التشيك'),
('CZ', 'en', 'Czech Republic'),
('DE', 'ar', 'ألمانيا'),
('DE', 'en', 'Germany'),
('DJ', 'ar', 'جيبوتي'),
('DJ', 'en', 'Djibouti'),
('DK', 'ar', 'الدنمارك'),
('DK', 'en', 'Denmark'),
('DM', 'ar', 'دومينيكا'),
('DM', 'en', 'Dominica'),
('DO', 'ar', 'جمهورية الدومنيكان'),
('DO', 'en', 'Dominican Republic'),
('DZ', 'ar', 'الجزائر'),
('DZ', 'en', 'Algeria'),
('EC', 'ar', 'الإكوادور'),
('EC', 'en', 'Ecuador'),
('EE', 'ar', 'إستونيا'),
('EE', 'en', 'Estonia'),
('EG', 'ar', 'مصر'),
('EG', 'en', 'Egypt'),
('EH', 'ar', 'الصحراء الغربية'),
('EH', 'en', 'Western Sahara'),
('ER', 'ar', 'أرتيريا'),
('ER', 'en', 'Eritrea'),
('ES', 'ar', 'إسبانيا'),
('ES', 'en', 'Spain'),
('ET', 'ar', 'أثيوبيا'),
('ET', 'en', 'Ethiopia'),
('FI', 'ar', 'فنلندا'),
('FI', 'en', 'Finland'),
('FJ', 'ar', 'فيجي'),
('FJ', 'en', 'Fiji'),
('FK', 'ar', 'جزر فوكلاند'),
('FK', 'en', 'Falkland Islands (Malvinas)'),
('FM', 'ar', 'ولايات ميكرونيسيا المتحدة'),
('FM', 'en', 'Micronesia, Federated States of'),
('FO', 'ar', 'جزر فارو'),
('FO', 'en', 'Faroe Islands'),
('FR', 'ar', 'فرنسا'),
('FR', 'en', 'France'),
('GA', 'ar', 'الغابون'),
('GA', 'en', 'Gabon'),
('GB', 'ar', 'المملكة المتحدة'),
('GB', 'en', 'United Kingdom'),
('GD', 'ar', 'غرينادا'),
('GD', 'en', 'Grenada'),
('GE', 'ar', 'جورجيا'),
('GE', 'en', 'Georgia'),
('GF', 'ar', 'غويانا الفرنسية'),
('GF', 'en', 'French Guiana'),
('GH', 'ar', 'غانا'),
('GH', 'en', 'Ghana'),
('GI', 'ar', 'جبل طارق'),
('GI', 'en', 'Gibraltar'),
('GL', 'ar', 'جرينلاند'),
('GL', 'en', 'Greenland'),
('GM', 'ar', 'غامبيا'),
('GM', 'en', 'Gambia'),
('GN', 'ar', 'غينيا'),
('GN', 'en', 'Guinea'),
('GP', 'ar', 'غوادلوب'),
('GP', 'en', 'Guadeloupe'),
('GQ', 'ar', 'غينيا الاستوائية'),
('GQ', 'en', 'Equatorial Guinea'),
('GR', 'ar', 'اليونان'),
('GR', 'en', 'Greece'),
('GS', 'ar', 'جورجيا الجنوبية وجزر ساندويتش الجنوبية'),
('GS', 'en', 'South Georgia and the South Sandwich Islands'),
('GT', 'ar', 'غواتيمالا'),
('GT', 'en', 'Guatemala'),
('GU', 'ar', 'غوام'),
('GU', 'en', 'Guam'),
('GW', 'ar', 'غينيا-بيساو'),
('GW', 'en', 'Guinea-Bissau'),
('GY', 'ar', 'غويانا'),
('GY', 'en', 'Guyana'),
('HK', 'ar', 'هونغ كونغ'),
('HK', 'en', 'Hong Kong'),
('HM', 'ar', 'جزيرة هيرد وجزر ماكدونالد'),
('HM', 'en', 'Heard Island and McDonald Islands'),
('HN', 'ar', 'هندوراس'),
('HN', 'en', 'Honduras'),
('HR', 'ar', 'كرواتيا'),
('HR', 'en', 'Croatia'),
('HT', 'ar', 'هايتي'),
('HT', 'en', 'Haiti'),
('HU', 'ar', 'المجر'),
('HU', 'en', 'Hungary'),
('ID', 'ar', 'إندونيسيا'),
('ID', 'en', 'Indonesia'),
('IE', 'ar', 'أيرلندا'),
('IE', 'en', 'Ireland'),
('IL', 'ar', 'إسرائيل'),
('IL', 'en', 'Israel'),
('IN', 'ar', 'الهند'),
('IN', 'en', 'India'),
('IO', 'ar', 'إقليم المحيط الهندي البريطاني'),
('IO', 'en', 'British Indian Ocean Territory'),
('IQ', 'ar', 'العراق'),
('IQ', 'en', 'Iraq'),
('IR', 'ar', 'إيران'),
('IR', 'en', 'Iran, Islamic Republic of'),
('IS', 'ar', 'أيسلندا'),
('IS', 'en', 'Iceland'),
('IT', 'ar', 'إيطاليا'),
('IT', 'en', 'Italy'),
('JM', 'ar', 'جامايكا'),
('JM', 'en', 'Jamaica'),
('JO', 'ar', 'الأردن'),
('JO', 'en', 'Jordan'),
('JP', 'ar', 'اليابان'),
('JP', 'en', 'Japan'),
('KE', 'ar', 'كينيا'),
('KE', 'en', 'Kenya'),
('KG', 'ar', 'قيرغيزستان'),
('KG', 'en', 'Kyrgyzstan'),
('KH', 'ar', 'كامبوديا'),
('KH', 'en', 'Cambodia'),
('KI', 'ar', 'جزر الكيريباتي'),
('KI', 'en', 'Kiribati'),
('KM', 'ar', 'جزر القمر'),
('KM', 'en', 'Comoros'),
('KN', 'ar', 'سانت كيتس ونيفس'),
('KN', 'en', 'Saint Kitts and Nevis'),
('KP', 'ar', 'كوريا الشمالية'),
('KP', 'en', 'Korea, Democratic People''s Republic of'),
('KR', 'ar', 'كوريا الجنوبية'),
('KR', 'en', 'Korea, Republic of'),
('KW', 'ar', 'الكويت'),
('KW', 'en', 'Kuwait'),
('KY', 'ar', 'جزر كايمان'),
('KY', 'en', 'Cayman Islands'),
('KZ', 'ar', 'كازاخستان'),
('KZ', 'en', 'Kazakhstan'),
('LA', 'ar', 'لاوس'),
('LA', 'en', 'Lao People''s Democratic Republic'),
('LB', 'ar', 'لبنان'),
('LB', 'en', 'Lebanon'),
('LC', 'ar', 'سانت لوسيا'),
('LC', 'en', 'Saint Lucia'),
('LI', 'ar', 'ليختنشتاين'),
('LI', 'en', 'Liechtenstein'),
('LK', 'ar', 'سريلانكا'),
('LK', 'en', 'Sri Lanka'),
('LR', 'ar', 'ليبريا'),
('LR', 'en', 'Liberia'),
('LS', 'ar', 'ليسوتو'),
('LS', 'en', 'Lesotho'),
('LT', 'ar', 'ليتوانيا'),
('LT', 'en', 'Lithuania'),
('LU', 'ar', 'لوكسمبورغ'),
('LU', 'en', 'Luxembourg'),
('LV', 'ar', 'لاتفيا'),
('LV', 'en', 'Latvia'),
('LY', 'ar', 'ليبيا'),
('LY', 'en', 'Libyan Arab Jamahiriya'),
('MA', 'ar', 'المغرب'),
('MA', 'en', 'Morocco'),
('MC', 'ar', 'موناكو'),
('MC', 'en', 'Monaco'),
('MD', 'ar', 'مولدوفا'),
('MD', 'en', 'Moldova, Republic of'),
('ME', 'ar', 'الجبل الأسود'),
('ME', 'en', 'Montenegro'),
('MF', 'ar', 'سانت مارتين'),
('MF', 'en', 'Saint Martin'),
('MG', 'ar', 'مدغشقر'),
('MG', 'en', 'Madagascar'),
('MH', 'ar', 'جزر مارشال'),
('MH', 'en', 'Marshall Islands'),
('MK', 'ar', 'مقدونيا'),
('MK', 'en', 'Macedonia, The Former Yugoslav Republic of'),
('ML', 'ar', 'مالي'),
('ML', 'en', 'Mali'),
('MM', 'ar', 'ميانمار'),
('MM', 'en', 'Myanmar'),
('MN', 'ar', 'منغوليا'),
('MN', 'en', 'Mongolia'),
('MO', 'ar', 'ماكاو'),
('MO', 'en', 'Macao'),
('MP', 'ar', 'جزر ماريانا الشمالية'),
('MP', 'en', 'Northern Mariana Islands'),
('MQ', 'ar', 'مارتينيك'),
('MQ', 'en', 'Martinique'),
('MR', 'ar', 'موريتانيا'),
('MR', 'en', 'Mauritania'),
('MS', 'ar', 'مونتسرات'),
('MS', 'en', 'Montserrat'),
('MT', 'ar', 'مالطا'),
('MT', 'en', 'Malta'),
('MU', 'ar', 'موريشيوس'),
('MU', 'en', 'Mauritius'),
('MV', 'ar', 'المالديف'),
('MV', 'en', 'Maldives'),
('MW', 'ar', 'ملاوي '),
('MW', 'en', 'Malawi'),
('MX', 'ar', 'المكسيك'),
('MX', 'en', 'Mexico'),
('MY', 'ar', 'ماليزيا'),
('MY', 'en', 'Malaysia'),
('MZ', 'ar', 'موزمبيق'),
('MZ', 'en', 'Mozambique'),
('NA', 'ar', 'ناميبيا '),
('NA', 'en', 'Namibia'),
('NC', 'ar', 'كاليدونيا الجديدة'),
('NC', 'en', 'New Caledonia'),
('NE', 'ar', 'النيجر'),
('NE', 'en', 'Niger'),
('NF', 'ar', 'جزيرة نورفولك'),
('NF', 'en', 'Norfolk Island'),
('NG', 'ar', 'نيجيريا'),
('NG', 'en', 'Nigeria'),
('NI', 'ar', 'نيكاراغوا '),
('NI', 'en', 'Nicaragua'),
('NL', 'ar', 'هولندا'),
('NL', 'en', 'Netherlands'),
('NO', 'ar', 'النرويج'),
('NO', 'en', 'Norway'),
('NP', 'ar', 'نيبال'),
('NP', 'en', 'Nepal'),
('NR', 'ar', 'ناورو'),
('NR', 'en', 'Nauru'),
('NU', 'ar', 'نيوي '),
('NU', 'en', 'Niue'),
('NZ', 'ar', 'نيوزيلندا'),
('NZ', 'en', 'New Zealand'),
('OM', 'ar', 'عمان'),
('OM', 'en', 'Oman'),
('PA', 'ar', 'بنما'),
('PA', 'en', 'Panama'),
('PE', 'ar', 'بيرو'),
('PE', 'en', 'Peru'),
('PF', 'ar', 'بولينيزيا الفرنسية'),
('PF', 'en', 'French Polynesia'),
('PG', 'ar', 'بابوا غينيا الجديدة'),
('PG', 'en', 'Papua New Guinea'),
('PH', 'ar', 'الفلبين'),
('PH', 'en', 'Philippines'),
('PK', 'ar', 'باكستان'),
('PK', 'en', 'Pakistan'),
('PL', 'ar', 'بولندا'),
('PL', 'en', 'Poland'),
('PM', 'ar', 'سانت بيير وميكويلون'),
('PM', 'en', 'Saint Pierre and Miquelon'),
('PN', 'ar', 'جزر بيتكيرن'),
('PN', 'en', 'Pitcairn'),
('PR', 'ar', 'بورتوريكو'),
('PR', 'en', 'Puerto Rico'),
('PS', 'ar', 'فلسطين'),
('PS', 'en', 'Palestinian'),
('PT', 'ar', 'البرتغال'),
('PT', 'en', 'Portugal'),
('PW', 'ar', 'بالاو'),
('PW', 'en', 'Palau'),
('PY', 'ar', 'باراغواي'),
('PY', 'en', 'Paraguay'),
('QA', 'ar', 'قطر'),
('QA', 'en', 'Qatar'),
('RE', 'ar', 'ريونيون'),
('RE', 'en', 'Reunion'),
('RO', 'ar', 'رومانيا'),
('RO', 'en', 'Romania'),
('RS', 'ar', 'صربيا'),
('RS', 'en', 'Serbia'),
('RU', 'ar', 'روسيا'),
('RU', 'en', 'Russian Federation'),
('RW', 'ar', 'رواندا'),
('RW', 'en', 'Rwanda'),
('SA', 'ar', 'المملكة العربية السعودية'),
('SA', 'en', 'Saudi Arabia'),
('SB', 'ar', 'جزر سليمان'),
('SB', 'en', 'Solomon Islands'),
('SC', 'ar', 'سيشيل'),
('SC', 'en', 'Seychelles'),
('SD', 'ar', 'السودان'),
('SD', 'en', 'Sudan'),
('SE', 'ar', 'السويد'),
('SE', 'en', 'Sweden'),
('SG', 'ar', 'سنغافورة'),
('SG', 'en', 'Singapore'),
('SH', 'ar', 'سانت هيلينا'),
('SH', 'en', 'Saint Helena'),
('SI', 'ar', 'سلوفينيا'),
('SI', 'en', 'Slovenia'),
('SJ', 'ar', 'سفالبارد وجان ماين'),
('SJ', 'en', 'Svalbard and Jan Mayen'),
('SK', 'ar', 'سلوفاكيا'),
('SK', 'en', 'Slovakia'),
('SL', 'ar', 'سيراليون'),
('SL', 'en', 'Sierra Leone'),
('SM', 'ar', 'سان مارينو'),
('SM', 'en', 'San Marino'),
('SN', 'ar', 'السنغال'),
('SN', 'en', 'Senegal'),
('SO', 'ar', 'الصومال'),
('SO', 'en', 'Somalia'),
('SR', 'ar', 'سورينام'),
('SR', 'en', 'Suriname'),
('ST', 'ar', 'ساو تومي وبرنسيب'),
('ST', 'en', 'Sao Tome and Principe'),
('SV', 'ar', 'السلفادور'),
('SV', 'en', 'El Salvador'),
('SY', 'ar', 'سوريا'),
('SY', 'en', 'Syrian Arab Republic'),
('SZ', 'ar', 'سوازيلند'),
('SZ', 'en', 'Swaziland'),
('TC', 'ar', 'جزر تركس وكايكوس'),
('TC', 'en', 'Turks and Caicos Islands'),
('TD', 'ar', 'تشاد'),
('TD', 'en', 'Chad'),
('TF', 'ar', 'المقاطعات الجنوبية الفرنسية'),
('TF', 'en', 'French Southern Territories'),
('TG', 'ar', 'توغو'),
('TG', 'en', 'Togo'),
('TH', 'ar', 'تايلاند'),
('TH', 'en', 'Thailand'),
('TJ', 'ar', 'طاجكستان'),
('TJ', 'en', 'Tajikistan'),
('TK', 'ar', 'توكيلو'),
('TK', 'en', 'Tokelau'),
('TL', 'ar', 'تيمور الشرقية'),
('TL', 'en', 'Timor-Leste'),
('TM', 'ar', 'تركمانستان'),
('TM', 'en', 'Turkmenistan'),
('TN', 'ar', 'تونس'),
('TN', 'en', 'Tunisia'),
('TO', 'ar', 'تونغا'),
('TO', 'en', 'Tonga'),
('TR', 'ar', 'تركيا'),
('TR', 'en', 'Turkey'),
('TT', 'ar', 'ترينيداد وتوباغو'),
('TT', 'en', 'Trinidad and Tobago'),
('TV', 'ar', 'توفالو'),
('TV', 'en', 'Tuvalu'),
('TW', 'ar', 'تايوان'),
('TW', 'en', 'Taiwan, Province Of China'),
('TZ', 'ar', 'تنزانيا'),
('TZ', 'en', 'Tanzania, United Republic of'),
('UA', 'ar', 'أوكرانيا'),
('UA', 'en', 'Ukraine'),
('UG', 'ar', 'أوغندا'),
('UG', 'en', 'Uganda'),
('UM', 'ar', 'الجزر النائية الصغرى للولايات المتحدة'),
('UM', 'en', 'United States Minor Outlying Islands'),
('US', 'ar', 'الولايات المتحدة'),
('US', 'en', 'United States'),
('UY', 'ar', 'أوروغواي'),
('UY', 'en', 'Uruguay'),
('UZ', 'ar', 'أوزباكستان'),
('UZ', 'en', 'Uzbekistan'),
('VA', 'ar', 'مدينة الفاتيكان'),
('VA', 'en', 'Holy See (Vatican City State)'),
('VC', 'ar', 'سانت فينسنت وجزر غرينادين'),
('VC', 'en', 'Saint Vincent and the Grenadines'),
('VE', 'ar', 'فنزويلا'),
('VE', 'en', 'Venezuela'),
('VG', 'ar', 'جزر فيرجين البريطانية'),
('VG', 'en', 'Virgin Islands, British'),
('VI', 'ar', 'جزر فيرجين الأمريكية'),
('VI', 'en', 'Virgin Islands, U.S.'),
('VN', 'ar', 'فيتنام'),
('VN', 'en', 'Viet Nam'),
('VU', 'ar', 'فانواتو'),
('VU', 'en', 'Vanuatu'),
('WF', 'ar', 'والس وفوتونا'),
('WF', 'en', 'Wallis And Futuna'),
('WS', 'ar', 'ساموا'),
('WS', 'en', 'Samoa'),
('YE', 'ar', 'اليمن'),
('YE', 'en', 'Yemen'),
('YT', 'ar', 'مايوت'),
('YT', 'en', 'Mayotte'),
('ZA', 'ar', 'جنوب أفريقيا'),
('ZA', 'en', 'South Africa'),
('ZM', 'ar', 'زامبيا'),
('ZM', 'en', 'Zambia'),
('ZW', 'ar', 'زيمبابوي'),
('ZW', 'en', 'Zimbabwe');

-- --------------------------------------------------------

--
-- Table structure for table `currency`
--

CREATE TABLE IF NOT EXISTS `currency` (
  `currency_code` varchar(3) NOT NULL,
  PRIMARY KEY (`currency_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `currency`
--

INSERT INTO `currency` (`currency_code`) VALUES
('AED'),
('BHD'),
('CAD'),
('CNY'),
('EGP'),
('EUR'),
('GBP'),
('ILS'),
('IQD'),
('IRR'),
('JOD'),
('JPY'),
('KWD'),
('LBP'),
('LYD'),
('MAD'),
('OMR'),
('QAR'),
('SAR'),
('SDG'),
('SEK'),
('SOS'),
('SYP'),
('TND'),
('TRY'),
('USD'),
('YER');

-- --------------------------------------------------------

--
-- Table structure for table `currency_compare`
--

CREATE TABLE IF NOT EXISTS `currency_compare` (
  `rate` decimal(15,3) DEFAULT NULL,
  `compare_from` varchar(3) NOT NULL,
  `compare_to` varchar(3) NOT NULL,
  PRIMARY KEY (`compare_from`,`compare_to`),
  KEY `fk_currency_compare_currency2` (`compare_to`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `currency_compare`
--

INSERT INTO `currency_compare` (`rate`, `compare_from`, `compare_to`) VALUES
(1.000, 'AED', 'AED'),
(9.770, 'AED', 'BHD'),
(3.460, 'AED', 'CAD'),
(0.600, 'AED', 'CNY'),
(0.530, 'AED', 'EGP'),
(4.990, 'AED', 'EUR'),
(6.010, 'AED', 'GBP'),
(1.040, 'AED', 'ILS'),
(5.180, 'AED', 'JOD'),
(0.040, 'AED', 'JPY'),
(12.990, 'AED', 'KWD'),
(0.000, 'AED', 'LBP'),
(0.440, 'AED', 'MAD'),
(9.550, 'AED', 'OMR'),
(1.010, 'AED', 'QAR'),
(0.980, 'AED', 'SAR'),
(0.560, 'AED', 'SEK'),
(0.030, 'AED', 'SYP'),
(2.200, 'AED', 'TND'),
(1.820, 'AED', 'TRY'),
(3.670, 'AED', 'USD'),
(0.100, 'BHD', 'AED'),
(1.000, 'BHD', 'BHD'),
(0.350, 'BHD', 'CAD'),
(0.060, 'BHD', 'CNY'),
(0.050, 'BHD', 'EGP'),
(0.510, 'BHD', 'EUR'),
(0.620, 'BHD', 'GBP'),
(0.110, 'BHD', 'ILS'),
(0.530, 'BHD', 'JOD'),
(0.000, 'BHD', 'JPY'),
(1.330, 'BHD', 'KWD'),
(0.250, 'BHD', 'LBP'),
(0.050, 'BHD', 'MAD'),
(0.980, 'BHD', 'OMR'),
(0.100, 'BHD', 'QAR'),
(0.100, 'BHD', 'SAR'),
(0.060, 'BHD', 'SEK'),
(0.000, 'BHD', 'SYP'),
(0.230, 'BHD', 'TND'),
(0.190, 'BHD', 'TRY'),
(0.380, 'BHD', 'USD'),
(0.290, 'CAD', 'AED'),
(2.820, 'CAD', 'BHD'),
(1.000, 'CAD', 'CAD'),
(0.170, 'CAD', 'CNY'),
(0.150, 'CAD', 'EGP'),
(1.440, 'CAD', 'EUR'),
(1.740, 'CAD', 'GBP'),
(0.300, 'CAD', 'ILS'),
(1.500, 'CAD', 'JOD'),
(0.010, 'CAD', 'JPY'),
(3.750, 'CAD', 'KWD'),
(1.000, 'CAD', 'LBP'),
(0.130, 'CAD', 'MAD'),
(2.760, 'CAD', 'OMR'),
(0.290, 'CAD', 'QAR'),
(0.280, 'CAD', 'SAR'),
(0.160, 'CAD', 'SEK'),
(0.010, 'CAD', 'SYP'),
(0.640, 'CAD', 'TND'),
(0.530, 'CAD', 'TRY'),
(1.060, 'CAD', 'USD'),
(1.660, 'CNY', 'AED'),
(16.210, 'CNY', 'BHD'),
(5.740, 'CNY', 'CAD'),
(1.000, 'CNY', 'CNY'),
(0.880, 'CNY', 'EGP'),
(8.280, 'CNY', 'EUR'),
(9.980, 'CNY', 'GBP'),
(1.730, 'CNY', 'ILS'),
(8.600, 'CNY', 'JOD'),
(0.060, 'CNY', 'JPY'),
(21.560, 'CNY', 'KWD'),
(0.000, 'CNY', 'LBP'),
(0.740, 'CNY', 'MAD'),
(15.850, 'CNY', 'OMR'),
(1.670, 'CNY', 'QAR'),
(1.630, 'CNY', 'SAR'),
(0.930, 'CNY', 'SEK'),
(0.040, 'CNY', 'SYP'),
(3.650, 'CNY', 'TND'),
(3.020, 'CNY', 'TRY'),
(6.090, 'CNY', 'USD'),
(1.880, 'EGP', 'AED'),
(18.340, 'EGP', 'BHD'),
(6.490, 'EGP', 'CAD'),
(1.130, 'EGP', 'CNY'),
(1.000, 'EGP', 'EGP'),
(9.370, 'EGP', 'EUR'),
(11.280, 'EGP', 'GBP'),
(1.960, 'EGP', 'ILS'),
(9.720, 'EGP', 'JOD'),
(0.070, 'EGP', 'JPY'),
(24.380, 'EGP', 'KWD'),
(0.000, 'EGP', 'LBP'),
(0.830, 'EGP', 'MAD'),
(17.930, 'EGP', 'OMR'),
(1.890, 'EGP', 'QAR'),
(1.840, 'EGP', 'SAR'),
(1.050, 'EGP', 'SEK'),
(0.050, 'EGP', 'SYP'),
(4.130, 'EGP', 'TND'),
(3.410, 'EGP', 'TRY'),
(6.890, 'EGP', 'USD'),
(0.200, 'EUR', 'AED'),
(1.960, 'EUR', 'BHD'),
(0.690, 'EUR', 'CAD'),
(0.120, 'EUR', 'CNY'),
(0.110, 'EUR', 'EGP'),
(1.000, 'EUR', 'EUR'),
(1.200, 'EUR', 'GBP'),
(0.210, 'EUR', 'ILS'),
(1.040, 'EUR', 'JOD'),
(0.010, 'EUR', 'JPY'),
(2.600, 'EUR', 'KWD'),
(0.500, 'EUR', 'LBP'),
(0.090, 'EUR', 'MAD'),
(1.910, 'EUR', 'OMR'),
(0.200, 'EUR', 'QAR'),
(0.200, 'EUR', 'SAR'),
(0.110, 'EUR', 'SEK'),
(0.010, 'EUR', 'SYP'),
(0.440, 'EUR', 'TND'),
(0.360, 'EUR', 'TRY'),
(0.740, 'EUR', 'USD'),
(0.170, 'GBP', 'AED'),
(1.620, 'GBP', 'BHD'),
(0.580, 'GBP', 'CAD'),
(0.100, 'GBP', 'CNY'),
(0.090, 'GBP', 'EGP'),
(0.830, 'GBP', 'EUR'),
(1.000, 'GBP', 'GBP'),
(0.170, 'GBP', 'ILS'),
(0.860, 'GBP', 'JOD'),
(0.010, 'GBP', 'JPY'),
(2.160, 'GBP', 'KWD'),
(0.500, 'GBP', 'LBP'),
(0.070, 'GBP', 'MAD'),
(1.590, 'GBP', 'OMR'),
(0.170, 'GBP', 'QAR'),
(0.160, 'GBP', 'SAR'),
(0.090, 'GBP', 'SEK'),
(0.000, 'GBP', 'SYP'),
(0.370, 'GBP', 'TND'),
(0.300, 'GBP', 'TRY'),
(0.610, 'GBP', 'USD'),
(0.960, 'ILS', 'AED'),
(9.370, 'ILS', 'BHD'),
(3.320, 'ILS', 'CAD'),
(0.580, 'ILS', 'CNY'),
(0.510, 'ILS', 'EGP'),
(4.790, 'ILS', 'EUR'),
(5.770, 'ILS', 'GBP'),
(1.000, 'ILS', 'ILS'),
(4.970, 'ILS', 'JOD'),
(0.030, 'ILS', 'JPY'),
(12.460, 'ILS', 'KWD'),
(0.000, 'ILS', 'LBP'),
(0.430, 'ILS', 'MAD'),
(9.160, 'ILS', 'OMR'),
(0.970, 'ILS', 'QAR'),
(0.940, 'ILS', 'SAR'),
(0.540, 'ILS', 'SEK'),
(0.030, 'ILS', 'SYP'),
(2.110, 'ILS', 'TND'),
(1.740, 'ILS', 'TRY'),
(3.520, 'ILS', 'USD'),
(0.190, 'JOD', 'AED'),
(1.890, 'JOD', 'BHD'),
(0.670, 'JOD', 'CAD'),
(0.120, 'JOD', 'CNY'),
(0.100, 'JOD', 'EGP'),
(0.960, 'JOD', 'EUR'),
(1.160, 'JOD', 'GBP'),
(0.200, 'JOD', 'ILS'),
(1.000, 'JOD', 'JOD'),
(0.010, 'JOD', 'JPY'),
(2.510, 'JOD', 'KWD'),
(0.500, 'JOD', 'LBP'),
(0.090, 'JOD', 'MAD'),
(1.840, 'JOD', 'OMR'),
(0.190, 'JOD', 'QAR'),
(0.190, 'JOD', 'SAR'),
(0.110, 'JOD', 'SEK'),
(0.010, 'JOD', 'SYP'),
(0.420, 'JOD', 'TND'),
(0.350, 'JOD', 'TRY'),
(0.710, 'JOD', 'USD'),
(27.890, 'JPY', 'AED'),
(272.480, 'JPY', 'BHD'),
(96.530, 'JPY', 'CAD'),
(16.810, 'JPY', 'CNY'),
(14.860, 'JPY', 'EGP'),
(139.280, 'JPY', 'EUR'),
(167.790, 'JPY', 'GBP'),
(29.080, 'JPY', 'ILS'),
(144.510, 'JPY', 'JOD'),
(1.000, 'JPY', 'JPY'),
(362.320, 'JPY', 'KWD'),
(0.070, 'JPY', 'LBP'),
(12.400, 'JPY', 'MAD'),
(266.670, 'JPY', 'OMR'),
(28.140, 'JPY', 'QAR'),
(27.310, 'JPY', 'SAR'),
(15.620, 'JPY', 'SEK'),
(0.730, 'JPY', 'SYP'),
(61.310, 'JPY', 'TND'),
(50.710, 'JPY', 'TRY'),
(102.460, 'JPY', 'USD'),
(0.080, 'KWD', 'AED'),
(0.750, 'KWD', 'BHD'),
(0.270, 'KWD', 'CAD'),
(0.050, 'KWD', 'CNY'),
(0.040, 'KWD', 'EGP'),
(0.380, 'KWD', 'EUR'),
(0.460, 'KWD', 'GBP'),
(0.080, 'KWD', 'ILS'),
(0.400, 'KWD', 'JOD'),
(0.000, 'KWD', 'JPY'),
(1.000, 'KWD', 'KWD'),
(0.200, 'KWD', 'LBP'),
(0.030, 'KWD', 'MAD'),
(0.740, 'KWD', 'OMR'),
(0.080, 'KWD', 'QAR'),
(0.080, 'KWD', 'SAR'),
(0.040, 'KWD', 'SEK'),
(0.000, 'KWD', 'SYP'),
(0.170, 'KWD', 'TND'),
(0.140, 'KWD', 'TRY'),
(0.280, 'KWD', 'USD'),
(409.840, 'LBP', 'AED'),
(4000.000, 'LBP', 'BHD'),
(1428.570, 'LBP', 'CAD'),
(247.520, 'LBP', 'CNY'),
(218.820, 'LBP', 'EGP'),
(2040.820, 'LBP', 'EUR'),
(2439.020, 'LBP', 'GBP'),
(427.350, 'LBP', 'ILS'),
(2127.660, 'LBP', 'JOD'),
(14.720, 'LBP', 'JPY'),
(5263.160, 'LBP', 'KWD'),
(1.000, 'LBP', 'LBP'),
(182.480, 'LBP', 'MAD'),
(3846.150, 'LBP', 'OMR'),
(414.940, 'LBP', 'QAR'),
(401.610, 'LBP', 'SAR'),
(229.890, 'LBP', 'SEK'),
(10.780, 'LBP', 'SYP'),
(900.900, 'LBP', 'TND'),
(746.270, 'LBP', 'TRY'),
(1515.150, 'LBP', 'USD'),
(2.250, 'MAD', 'AED'),
(21.970, 'MAD', 'BHD'),
(7.780, 'MAD', 'CAD'),
(1.360, 'MAD', 'CNY'),
(1.200, 'MAD', 'EGP'),
(11.230, 'MAD', 'EUR'),
(13.520, 'MAD', 'GBP'),
(2.350, 'MAD', 'ILS'),
(11.650, 'MAD', 'JOD'),
(0.080, 'MAD', 'JPY'),
(29.220, 'MAD', 'KWD'),
(0.010, 'MAD', 'LBP'),
(1.000, 'MAD', 'MAD'),
(21.490, 'MAD', 'OMR'),
(2.270, 'MAD', 'QAR'),
(2.200, 'MAD', 'SAR'),
(1.260, 'MAD', 'SEK'),
(0.060, 'MAD', 'SYP'),
(4.940, 'MAD', 'TND'),
(4.090, 'MAD', 'TRY'),
(8.260, 'MAD', 'USD'),
(0.100, 'OMR', 'AED'),
(1.020, 'OMR', 'BHD'),
(0.360, 'OMR', 'CAD'),
(0.060, 'OMR', 'CNY'),
(0.060, 'OMR', 'EGP'),
(0.520, 'OMR', 'EUR'),
(0.630, 'OMR', 'GBP'),
(0.110, 'OMR', 'ILS'),
(0.540, 'OMR', 'JOD'),
(0.000, 'OMR', 'JPY'),
(1.360, 'OMR', 'KWD'),
(0.330, 'OMR', 'LBP'),
(0.050, 'OMR', 'MAD'),
(1.000, 'OMR', 'OMR'),
(0.110, 'OMR', 'QAR'),
(0.100, 'OMR', 'SAR'),
(0.060, 'OMR', 'SEK'),
(0.000, 'OMR', 'SYP'),
(0.230, 'OMR', 'TND'),
(0.190, 'OMR', 'TRY'),
(0.380, 'OMR', 'USD'),
(0.990, 'QAR', 'AED'),
(9.680, 'QAR', 'BHD'),
(3.430, 'QAR', 'CAD'),
(0.600, 'QAR', 'CNY'),
(0.530, 'QAR', 'EGP'),
(4.950, 'QAR', 'EUR'),
(5.960, 'QAR', 'GBP'),
(1.030, 'QAR', 'ILS'),
(5.130, 'QAR', 'JOD'),
(0.040, 'QAR', 'JPY'),
(12.870, 'QAR', 'KWD'),
(0.000, 'QAR', 'LBP'),
(0.440, 'QAR', 'MAD'),
(9.470, 'QAR', 'OMR'),
(1.000, 'QAR', 'QAR'),
(0.970, 'QAR', 'SAR'),
(0.560, 'QAR', 'SEK'),
(0.030, 'QAR', 'SYP'),
(2.180, 'QAR', 'TND'),
(1.800, 'QAR', 'TRY'),
(3.640, 'QAR', 'USD'),
(1.020, 'SAR', 'AED'),
(9.970, 'SAR', 'BHD'),
(3.530, 'SAR', 'CAD'),
(0.620, 'SAR', 'CNY'),
(0.540, 'SAR', 'EGP'),
(5.100, 'SAR', 'EUR'),
(6.140, 'SAR', 'GBP'),
(1.060, 'SAR', 'ILS'),
(5.290, 'SAR', 'JOD'),
(0.040, 'SAR', 'JPY'),
(13.260, 'SAR', 'KWD'),
(0.000, 'SAR', 'LBP'),
(0.450, 'SAR', 'MAD'),
(9.750, 'SAR', 'OMR'),
(1.030, 'SAR', 'QAR'),
(1.000, 'SAR', 'SAR'),
(0.570, 'SAR', 'SEK'),
(0.030, 'SAR', 'SYP'),
(2.240, 'SAR', 'TND'),
(1.860, 'SAR', 'TRY'),
(3.750, 'SAR', 'USD'),
(1.790, 'SEK', 'AED'),
(17.440, 'SEK', 'BHD'),
(6.180, 'SEK', 'CAD'),
(1.080, 'SEK', 'CNY'),
(0.950, 'SEK', 'EGP'),
(8.910, 'SEK', 'EUR'),
(10.730, 'SEK', 'GBP'),
(1.860, 'SEK', 'ILS'),
(9.250, 'SEK', 'JOD'),
(0.060, 'SEK', 'JPY'),
(23.190, 'SEK', 'KWD'),
(0.000, 'SEK', 'LBP'),
(0.790, 'SEK', 'MAD'),
(17.050, 'SEK', 'OMR'),
(1.800, 'SEK', 'QAR'),
(1.750, 'SEK', 'SAR'),
(1.000, 'SEK', 'SEK'),
(0.050, 'SEK', 'SYP'),
(3.920, 'SEK', 'TND'),
(3.250, 'SEK', 'TRY'),
(6.560, 'SEK', 'USD'),
(38.100, 'SYP', 'AED'),
(371.750, 'SYP', 'BHD'),
(131.750, 'SYP', 'CAD'),
(22.960, 'SYP', 'CNY'),
(20.290, 'SYP', 'EGP'),
(190.110, 'SYP', 'EUR'),
(228.830, 'SYP', 'GBP'),
(39.710, 'SYP', 'ILS'),
(197.240, 'SYP', 'JOD'),
(1.370, 'SYP', 'JPY'),
(495.050, 'SYP', 'KWD'),
(0.090, 'SYP', 'LBP'),
(16.930, 'SYP', 'MAD'),
(363.640, 'SYP', 'OMR'),
(38.430, 'SYP', 'QAR'),
(37.300, 'SYP', 'SAR'),
(21.340, 'SYP', 'SEK'),
(1.000, 'SYP', 'SYP'),
(83.750, 'SYP', 'TND'),
(69.250, 'SYP', 'TRY'),
(139.860, 'SYP', 'USD'),
(0.450, 'TND', 'AED'),
(4.440, 'TND', 'BHD'),
(1.570, 'TND', 'CAD'),
(0.270, 'TND', 'CNY'),
(0.240, 'TND', 'EGP'),
(2.270, 'TND', 'EUR'),
(2.730, 'TND', 'GBP'),
(0.470, 'TND', 'ILS'),
(2.360, 'TND', 'JOD'),
(0.020, 'TND', 'JPY'),
(5.910, 'TND', 'KWD'),
(0.000, 'TND', 'LBP'),
(0.200, 'TND', 'MAD'),
(4.350, 'TND', 'OMR'),
(0.460, 'TND', 'QAR'),
(0.450, 'TND', 'SAR'),
(0.250, 'TND', 'SEK'),
(0.010, 'TND', 'SYP'),
(1.000, 'TND', 'TND'),
(0.830, 'TND', 'TRY'),
(1.670, 'TND', 'USD'),
(0.550, 'TRY', 'AED'),
(5.370, 'TRY', 'BHD'),
(1.900, 'TRY', 'CAD'),
(0.330, 'TRY', 'CNY'),
(0.290, 'TRY', 'EGP'),
(2.740, 'TRY', 'EUR'),
(3.310, 'TRY', 'GBP'),
(0.570, 'TRY', 'ILS'),
(2.850, 'TRY', 'JOD'),
(0.020, 'TRY', 'JPY'),
(7.140, 'TRY', 'KWD'),
(0.000, 'TRY', 'LBP'),
(0.240, 'TRY', 'MAD'),
(5.250, 'TRY', 'OMR'),
(0.550, 'TRY', 'QAR'),
(0.540, 'TRY', 'SAR'),
(0.310, 'TRY', 'SEK'),
(0.010, 'TRY', 'SYP'),
(1.210, 'TRY', 'TND'),
(1.000, 'TRY', 'TRY'),
(2.020, 'TRY', 'USD'),
(0.270, 'USD', 'AED'),
(2.660, 'USD', 'BHD'),
(0.940, 'USD', 'CAD'),
(0.160, 'USD', 'CNY'),
(0.150, 'USD', 'EGP'),
(1.360, 'USD', 'EUR'),
(1.640, 'USD', 'GBP'),
(0.280, 'USD', 'ILS'),
(1.410, 'USD', 'JOD'),
(0.010, 'USD', 'JPY'),
(3.540, 'USD', 'KWD'),
(1.000, 'USD', 'LBP'),
(0.120, 'USD', 'MAD'),
(2.600, 'USD', 'OMR'),
(0.270, 'USD', 'QAR'),
(0.270, 'USD', 'SAR'),
(0.150, 'USD', 'SEK'),
(0.010, 'USD', 'SYP'),
(0.600, 'USD', 'TND'),
(0.500, 'USD', 'TRY'),
(1.000, 'USD', 'USD');

-- --------------------------------------------------------

--
-- Table structure for table `currency_translation`
--

CREATE TABLE IF NOT EXISTS `currency_translation` (
  `currency_code` varchar(3) NOT NULL,
  `content_lang` char(2) NOT NULL,
  `currency_name` varchar(30) NOT NULL,
  PRIMARY KEY (`currency_code`,`content_lang`),
  KEY `fk_currency_translation` (`currency_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `currency_translation`
--

INSERT INTO `currency_translation` (`currency_code`, `content_lang`, `currency_name`) VALUES
('AED', 'ar', 'الدرهم الإمارتي'),
('AED', 'en', 'United Arab Emirates Dirham'),
('BHD', 'ar', 'الدينار بحريني'),
('BHD', 'en', 'Bahraini Dinar'),
('CAD', 'ar', 'الدولار كندي'),
('CAD', 'en', 'Canadian Dollar'),
('CNY', 'ar', 'اليوان الصيني'),
('CNY', 'en', 'Chinese Yuan'),
('EGP', 'ar', 'الجنية المصري'),
('EGP', 'en', 'The Egyptian Pound'),
('EUR', 'ar', 'اليورو'),
('EUR', 'en', 'Euro'),
('GBP', 'ar', 'الجنية اﻻسترليني'),
('GBP', 'en', 'British Pound Sterling'),
('ILS', 'ar', 'الشيكل'),
('ILS', 'en', 'Shekel'),
('IQD', 'ar', 'الدينار العراقي'),
('IQD', 'en', 'Iraqi Dinar'),
('IRR', 'ar', 'الريال الإيراني'),
('IRR', 'en', 'Iranian Rial'),
('JOD', 'ar', 'الدينار الأردني'),
('JOD', 'en', 'Jordanian Dinar'),
('JPY', 'ar', 'الين الياباني'),
('JPY', 'en', 'Japanese Yen'),
('KWD', 'ar', 'الدينار الكويتي'),
('KWD', 'en', 'Kuwaiti Dinar'),
('LBP', 'ar', 'الجنية اللبناني'),
('LBP', 'en', 'Lebanese Pound'),
('LYD', 'ar', 'الدينار الليبي'),
('LYD', 'en', 'Libyan Dinar'),
('MAD', 'ar', 'الدرهم المغربي'),
('MAD', 'en', 'Moroccan Dirham'),
('OMR', 'ar', 'الريال العماني'),
('OMR', 'en', 'Omani Rial'),
('QAR', 'ar', 'الريال القطري'),
('QAR', 'en', 'Qatari Riyal'),
('SAR', 'ar', 'الريال السعودي'),
('SAR', 'en', 'Saudi Riyal'),
('SDG', 'ar', 'الجنية السوداني'),
('SDG', 'en', 'Sudanese Pound'),
('SEK', 'ar', 'كرونا سوسرية'),
('SEK', 'en', 'Swedish Krona'),
('SOS', 'ar', 'الشلن الصومالي'),
('SOS', 'en', 'Somali Shilling'),
('SYP', 'ar', 'الجنية السوري'),
('SYP', 'en', 'Syrian Pound'),
('TND', 'ar', 'الدينار التونسي'),
('TND', 'en', 'Tunisian Dinar'),
('TRY', 'ar', 'الليرة التركية'),
('TRY', 'en', 'Turkish Lira'),
('USD', 'ar', 'الدولار الامريكي'),
('USD', 'en', 'US Dollar'),
('YER', 'ar', 'الريال اليمني'),
('YER', 'en', 'Yemeni Rial');

-- --------------------------------------------------------

--
-- Table structure for table `default_ads_zones`
--

CREATE TABLE IF NOT EXISTS `default_ads_zones` (
  `zone_id` tinyint(3) unsigned NOT NULL,
  `zone_name` varchar(100) NOT NULL,
  `width` smallint(5) unsigned NOT NULL,
  `height` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`zone_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `deleted_users`
--

CREATE TABLE IF NOT EXISTS `deleted_users` (
  `deleted_id` int(10) unsigned NOT NULL,
  `email` varchar(65) DEFAULT NULL,
  `name` varchar(65) DEFAULT NULL,
  `username` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`deleted_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `deleted_users_log`
--

CREATE TABLE IF NOT EXISTS `deleted_users_log` (
  `deleted_id` int(10) unsigned NOT NULL,
  `log_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`deleted_id`,`log_id`),
  KEY `fk_deleted_users_has_users_log_users_log1` (`log_id`),
  KEY `fk_deleted_users_has_users_log_deleted_users1` (`deleted_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `dir_categories`
--

CREATE TABLE IF NOT EXISTS `dir_categories` (
  `category_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `parent_category` smallint(5) unsigned DEFAULT NULL,
  `published` tinyint(1) NOT NULL DEFAULT '1',
  `hits` int(10) unsigned NOT NULL DEFAULT '0',
  `is_system` tinyint(1) DEFAULT '0',
  `settings` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`category_id`),
  KEY `fk_dir_categories_dir_categories1` (`parent_category`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `dir_categories_translation`
--

CREATE TABLE IF NOT EXISTS `dir_categories_translation` (
  `category_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `content_lang` char(2) NOT NULL,
  `category_name` varchar(100) NOT NULL,
  `category_description` text,
  PRIMARY KEY (`category_id`,`content_lang`),
  KEY `fk_dir_categories_translation_1` (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `dir_companies`
--

CREATE TABLE IF NOT EXISTS `dir_companies` (
  `company_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `category_id` smallint(5) unsigned DEFAULT NULL,
  `user_id` int(10) unsigned DEFAULT NULL,
  `nationality` char(2) NOT NULL,
  `published` tinyint(1) NOT NULL DEFAULT '1',
  `hits` int(10) unsigned NOT NULL DEFAULT '0',
  `votes` int(11) DEFAULT '0',
  `votes_rate` double DEFAULT '1',
  `email` varchar(65) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `mobile` varchar(20) DEFAULT NULL,
  `fax` varchar(20) DEFAULT NULL,
  `image_ext` varchar(3) DEFAULT NULL,
  `maps` varchar(200) DEFAULT NULL,
  `attach_ext` varchar(4) DEFAULT NULL,
  `create_date` datetime NOT NULL,
  `file_ext` varchar(4) DEFAULT NULL,
  `in_ticker` tinyint(1) DEFAULT '1',
  `accepted` tinyint(3) unsigned DEFAULT '1',
  `url` varchar(100) NOT NULL,
  `registered` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`company_id`),
  KEY `fk_dir_companies_dir_categories1` (`category_id`),
  KEY `fk_dir_companies_countries1` (`nationality`),
  KEY `fk_dir_companies_users1` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `dir_companies_articles`
--

CREATE TABLE IF NOT EXISTS `dir_companies_articles` (
  `article_id` int(10) unsigned NOT NULL,
  `company_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`article_id`),
  KEY `fk_dir_companies_has_articles_articles1` (`article_id`),
  KEY `fk_dir_companies_has_articles_dir_companies1` (`company_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `dir_companies_attributes`
--

CREATE TABLE IF NOT EXISTS `dir_companies_attributes` (
  `company_attribute_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `company_id` int(10) unsigned NOT NULL,
  `attribute_id` smallint(5) unsigned NOT NULL,
  `attribute_sort` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`company_attribute_id`),
  KEY `fk_dir_companies_attributes_system_attributes1` (`attribute_id`),
  KEY `fk_dir_companies_attributes0` (`company_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `dir_companies_attributes_value`
--

CREATE TABLE IF NOT EXISTS `dir_companies_attributes_value` (
  `company_attribute_id` int(10) unsigned NOT NULL,
  `value` varchar(255) NOT NULL,
  PRIMARY KEY (`company_attribute_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `dir_companies_attributes_value_translation`
--

CREATE TABLE IF NOT EXISTS `dir_companies_attributes_value_translation` (
  `company_attribute_id` int(10) unsigned NOT NULL,
  `content_lang` char(2) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`company_attribute_id`,`content_lang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `dir_companies_branches`
--

CREATE TABLE IF NOT EXISTS `dir_companies_branches` (
  `branch_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `company_id` int(10) unsigned NOT NULL,
  `country` char(2) DEFAULT NULL,
  `email` varchar(65) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `mobile` varchar(20) DEFAULT NULL,
  `fax` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`branch_id`),
  KEY `fk_dir_companies_branches_1` (`company_id`),
  KEY `fk_dir_companies_branches_countries1` (`country`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `dir_companies_branches_translation`
--

CREATE TABLE IF NOT EXISTS `dir_companies_branches_translation` (
  `branch_id` int(10) unsigned NOT NULL,
  `content_lang` char(2) NOT NULL,
  `branch_name` varchar(100) NOT NULL,
  `branch_address` varchar(150) NOT NULL,
  `city` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`branch_id`,`content_lang`),
  KEY `fk_dir_companies_branches_translation_dir_companies_branches1` (`branch_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `dir_companies_translation`
--

CREATE TABLE IF NOT EXISTS `dir_companies_translation` (
  `company_id` int(10) unsigned NOT NULL,
  `content_lang` char(2) NOT NULL,
  `company_name` varchar(100) NOT NULL,
  `company_address` varchar(150) NOT NULL,
  `city` varchar(100) DEFAULT NULL,
  `activity` varchar(250) DEFAULT NULL,
  `description` text,
  PRIMARY KEY (`company_id`,`content_lang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `docs`
--

CREATE TABLE IF NOT EXISTS `docs` (
  `doc_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `category_id` smallint(5) unsigned DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  `start_date` datetime NOT NULL,
  `file_lang` char(2) DEFAULT NULL,
  `published` tinyint(1) NOT NULL DEFAULT '1',
  `hits` int(10) unsigned NOT NULL DEFAULT '0',
  `votes` int(11) DEFAULT '0',
  `file_ext` varchar(4) DEFAULT NULL,
  `votes_rate` double DEFAULT '1',
  `create_date` datetime NOT NULL,
  PRIMARY KEY (`doc_id`),
  KEY `fk_docs_docs_categories1` (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `docs_categories`
--

CREATE TABLE IF NOT EXISTS `docs_categories` (
  `category_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `parent_category` smallint(5) unsigned DEFAULT NULL,
  `published` tinyint(1) NOT NULL DEFAULT '1',
  `hits` int(10) unsigned NOT NULL DEFAULT '0',
  `image_ext` varchar(3) DEFAULT NULL,
  `is_system` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`category_id`),
  KEY `fk_dir_categories_dir_categories1` (`parent_category`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `docs_categories_translation`
--

CREATE TABLE IF NOT EXISTS `docs_categories_translation` (
  `category_id` smallint(5) unsigned NOT NULL,
  `content_lang` char(2) NOT NULL,
  `category_name` varchar(100) NOT NULL,
  `category_description` text,
  PRIMARY KEY (`category_id`,`content_lang`),
  KEY `fk_docs_categories_translation_docs_categories1` (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `docs_translation`
--

CREATE TABLE IF NOT EXISTS `docs_translation` (
  `doc_id` int(10) unsigned NOT NULL,
  `content_lang` char(2) NOT NULL,
  `title` varchar(150) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`doc_id`,`content_lang`),
  KEY `fk_docs_translation_docs1` (`doc_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `dope_sheet`
--

CREATE TABLE IF NOT EXISTS `dope_sheet` (
  `video_id` int(10) unsigned NOT NULL,
  `event_date` datetime NOT NULL,
  `length_hours` tinyint(3) unsigned NOT NULL,
  `length_minutes` tinyint(3) unsigned NOT NULL,
  `length_seconds` tinyint(3) unsigned NOT NULL,
  `published` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`video_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `dope_sheet_shots`
--

CREATE TABLE IF NOT EXISTS `dope_sheet_shots` (
  `shot_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `video_id` int(10) unsigned NOT NULL,
  `type_id` tinyint(3) unsigned NOT NULL,
  `length_minutes` tinyint(3) unsigned NOT NULL,
  `length_seconds` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`shot_id`),
  KEY `fk_dope_sheet_shots1` (`video_id`),
  KEY `fk_dope_sheet_shots_types1` (`type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `dope_sheet_shots_translation`
--

CREATE TABLE IF NOT EXISTS `dope_sheet_shots_translation` (
  `shot_id` mediumint(8) unsigned NOT NULL,
  `content_lang` char(2) NOT NULL,
  `description` varchar(150) NOT NULL,
  `sound` varchar(45) NOT NULL,
  PRIMARY KEY (`shot_id`,`content_lang`),
  KEY `fk_dope_sheet_shots_dope_sheet1` (`shot_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `dope_sheet_shots_types`
--

CREATE TABLE IF NOT EXISTS `dope_sheet_shots_types` (
  `type_id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(4) NOT NULL,
  PRIMARY KEY (`type_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `dope_sheet_shots_types`
--

INSERT INTO `dope_sheet_shots_types` (`type_id`, `type`) VALUES
(1, 'EX'),
(2, 'CS'),
(3, 'MS'),
(4, 'SB');

-- --------------------------------------------------------

--
-- Table structure for table `dope_sheet_translation`
--

CREATE TABLE IF NOT EXISTS `dope_sheet_translation` (
  `video_id` int(10) unsigned NOT NULL,
  `content_lang` char(2) NOT NULL,
  `reporter` varchar(45) NOT NULL,
  `source` varchar(45) NOT NULL,
  `location` varchar(45) NOT NULL,
  `sound` varchar(45) NOT NULL,
  `story` text,
  PRIMARY KEY (`video_id`,`content_lang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `essays`
--

CREATE TABLE IF NOT EXISTS `essays` (
  `article_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`article_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE IF NOT EXISTS `events` (
  `event_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `section_id` smallint(5) unsigned DEFAULT NULL,
  `country_code` char(2) DEFAULT NULL,
  `votes` int(10) unsigned DEFAULT '0',
  `votes_rate` double DEFAULT '1',
  `hits` int(10) unsigned DEFAULT '0',
  `published` tinyint(1) DEFAULT '1',
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `event_date` datetime NOT NULL,
  `update_date` datetime DEFAULT NULL,
  PRIMARY KEY (`event_id`),
  KEY `fk_events_sections1` (`section_id`),
  KEY `fk_events_countries1` (`country_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `events_translation`
--

CREATE TABLE IF NOT EXISTS `events_translation` (
  `event_id` int(10) unsigned NOT NULL,
  `content_lang` char(2) NOT NULL,
  `event_header` varchar(500) NOT NULL,
  `event_detail` text NOT NULL,
  `location` varchar(45) NOT NULL,
  PRIMARY KEY (`event_id`,`content_lang`),
  KEY `fk_events_master` (`event_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `external_videos`
--

CREATE TABLE IF NOT EXISTS `external_videos` (
  `video_id` int(10) unsigned NOT NULL,
  `video` varchar(255) DEFAULT NULL,
  `uploaded_via_api` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`video_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `files`
--

CREATE TABLE IF NOT EXISTS `files` (
  `file_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ext` varchar(5) DEFAULT NULL,
  `create_date` datetime NOT NULL,
  `folder_id` int(10) unsigned DEFAULT NULL,
  `file` varchar(255) NOT NULL,
  `content_type` enum('IMAGE','INTERNAL_VIDEO','LINK') DEFAULT 'LINK',
  `rte` tinyint(1) NOT NULL DEFAULT '1',
  `manager_exclude` tinyint(1) NOT NULL DEFAULT '0',
  `user_id` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`file_id`),
  KEY `fk_files_folders1` (`folder_id`),
  KEY `fk_files_users1` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `folders`
--

CREATE TABLE IF NOT EXISTS `folders` (
  `folder_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `folder` varchar(50) DEFAULT NULL,
  `create_date` datetime NOT NULL,
  PRIMARY KEY (`folder_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `foreign_currencies`
--

CREATE TABLE IF NOT EXISTS `foreign_currencies` (
  `currency_code` varchar(3) NOT NULL,
  PRIMARY KEY (`currency_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `foreign_currencies`
--

INSERT INTO `foreign_currencies` (`currency_code`) VALUES
('AED'),
('BHD'),
('CAD'),
('CNY'),
('EUR'),
('GBP'),
('JOD'),
('JPY'),
('KWD'),
('OMR'),
('QAR'),
('SAR'),
('SEK'),
('USD');

-- --------------------------------------------------------

--
-- Table structure for table `forward_actions`
--

CREATE TABLE IF NOT EXISTS `forward_actions` (
  `forward_to` mediumint(9) NOT NULL,
  `forward_from` mediumint(9) NOT NULL,
  PRIMARY KEY (`forward_from`),
  KEY `fk_forward_from_actions` (`forward_from`),
  KEY `fk_forward_to_actions` (`forward_to`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `forward_actions`
--

INSERT INTO `forward_actions` (`forward_to`, `forward_from`) VALUES
(11, 10),
(18, 17),
(25, 24),
(32, 31),
(39, 38),
(46, 45),
(53, 52),
(60, 59),
(67, 66),
(86, 85),
(299, 298),
(303, 302),
(307, 306),
(310, 309),
(314, 313),
(6637, 6636),
(6941, 6940),
(6945, 6939),
(6952, 6951),
(6967, 6966),
(6971, 6965),
(6978, 6977),
(6993, 6992),
(6997, 6991),
(7004, 7003),
(7019, 7018),
(7023, 7017),
(7030, 7029),
(7045, 7044),
(7049, 7043),
(7056, 7055),
(7071, 7070),
(7075, 7069),
(7082, 7081),
(7097, 7096),
(7101, 7095),
(7108, 7107),
(7125, 7117),
(7129, 7128),
(7143, 7135),
(7147, 7146),
(7161, 7153),
(7165, 7164),
(7179, 7171),
(7183, 7182),
(7197, 7189),
(7201, 7200),
(7215, 7207),
(7219, 7218);

-- --------------------------------------------------------

--
-- Table structure for table `forward_modules`
--

CREATE TABLE IF NOT EXISTS `forward_modules` (
  `forward_from` mediumint(9) NOT NULL,
  `forward_to` mediumint(9) NOT NULL,
  PRIMARY KEY (`forward_from`),
  KEY `fk_modules_forward_from` (`forward_from`),
  KEY `fk_modules_forward_to` (`forward_to`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `forward_modules`
--

INSERT INTO `forward_modules` (`forward_from`, `forward_to`) VALUES
(11, 15),
(14, 15),
(39, 15),
(76, 15),
(77, 15),
(78, 15),
(22, 32),
(41, 32),
(79, 32),
(80, 32),
(81, 32);

-- --------------------------------------------------------

--
-- Table structure for table `galleries`
--

CREATE TABLE IF NOT EXISTS `galleries` (
  `gallery_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `published` tinyint(1) NOT NULL DEFAULT '0',
  `section_id` smallint(5) unsigned DEFAULT NULL,
  `show_gallery` tinyint(1) DEFAULT '1',
  `country_code` char(2) DEFAULT NULL,
  `is_system` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`gallery_id`),
  KEY `fk_galleries_sections` (`section_id`),
  KEY `fk_galleries_countries1` (`country_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `galleries_translation`
--

CREATE TABLE IF NOT EXISTS `galleries_translation` (
  `gallery_id` int(10) unsigned NOT NULL,
  `content_lang` char(2) NOT NULL,
  `gallery_header` varchar(500) NOT NULL,
  `tags` varchar(1024) DEFAULT NULL,
  PRIMARY KEY (`gallery_id`,`content_lang`),
  KEY `fk_galleries_translation_1` (`gallery_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `glossary`
--

CREATE TABLE IF NOT EXISTS `glossary` (
  `expression_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `category_id` smallint(5) unsigned NOT NULL,
  `expression` varchar(45) NOT NULL,
  PRIMARY KEY (`expression_id`),
  KEY `fk_glossary_glossary_categories1` (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `glossary_categories`
--

CREATE TABLE IF NOT EXISTS `glossary_categories` (
  `category_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `published` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `glossary_categories_translation`
--

CREATE TABLE IF NOT EXISTS `glossary_categories_translation` (
  `category_id` smallint(5) unsigned NOT NULL,
  `content_lang` char(2) NOT NULL,
  `category_name` varchar(100) NOT NULL,
  `category_description` text,
  PRIMARY KEY (`category_id`,`content_lang`),
  KEY `fk_glossary_categories_translation_glossary_categories1` (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `glossary_translation`
--

CREATE TABLE IF NOT EXISTS `glossary_translation` (
  `expression_id` int(10) unsigned NOT NULL,
  `content_lang` char(2) NOT NULL,
  `meaning` varchar(100) NOT NULL,
  `description` varchar(1024) DEFAULT NULL,
  PRIMARY KEY (`expression_id`,`content_lang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `images`
--

CREATE TABLE IF NOT EXISTS `images` (
  `image_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `creation_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ext` char(4) NOT NULL,
  `hits` int(10) unsigned DEFAULT '0',
  `user_id` int(10) unsigned DEFAULT NULL,
  `is_background` tinyint(1) NOT NULL DEFAULT '0',
  `gallery_id` int(10) unsigned DEFAULT NULL,
  `published` tinyint(1) NOT NULL DEFAULT '0',
  `publish_date` datetime NOT NULL,
  `expire_date` datetime DEFAULT NULL,
  `image_sort` int(10) unsigned DEFAULT NULL,
  `in_slider` tinyint(1) DEFAULT '0',
  `votes` int(10) unsigned DEFAULT '0',
  `votes_rate` double DEFAULT '1',
  `show_media` tinyint(1) DEFAULT '1',
  `update_date` datetime DEFAULT NULL,
  `comments` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`image_id`),
  KEY `fk_images_users1` (`user_id`),
  KEY `fk_images_galleries1` (`gallery_id`),
  KEY `image_sort_idx` (`image_sort`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `images_comments`
--

CREATE TABLE IF NOT EXISTS `images_comments` (
  `image_comment_id` int(10) unsigned NOT NULL,
  `image_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`image_comment_id`),
  KEY `fk_image_comment_images1` (`image_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `images_translation`
--

CREATE TABLE IF NOT EXISTS `images_translation` (
  `image_id` int(10) unsigned NOT NULL,
  `content_lang` char(2) NOT NULL,
  `image_header` varchar(255) NOT NULL,
  `tags` varchar(1024) DEFAULT NULL,
  `description` text,
  PRIMARY KEY (`image_id`,`content_lang`),
  KEY `fk_images_translation_1` (`image_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `infocus`
--

CREATE TABLE IF NOT EXISTS `infocus` (
  `infocus_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `published` tinyint(1) DEFAULT '0',
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `section_id` smallint(5) unsigned DEFAULT NULL,
  `country_code` char(2) DEFAULT NULL,
  `expire_date` datetime DEFAULT NULL,
  `thumb` varchar(3) DEFAULT NULL,
  `background` varchar(3) DEFAULT NULL,
  `banner` varchar(3) DEFAULT NULL,
  `publish_date` datetime NOT NULL,
  `archive` tinyint(1) DEFAULT '0',
  `dont_show` tinyint(1) DEFAULT '0',
  `bgcolor` char(6) NOT NULL DEFAULT 'FFFFFF',
  PRIMARY KEY (`infocus_id`),
  KEY `fk_infocus_sections1` (`section_id`),
  KEY `fk_infocus_countries1` (`country_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `infocus_has_articles`
--

CREATE TABLE IF NOT EXISTS `infocus_has_articles` (
  `infocus_id` int(10) unsigned NOT NULL,
  `article_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`infocus_id`,`article_id`),
  KEY `fk_infocus_has_articles_infocus1` (`infocus_id`),
  KEY `fk_infocus_has_articles_articles` (`article_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `infocus_has_images`
--

CREATE TABLE IF NOT EXISTS `infocus_has_images` (
  `infocus_id` int(10) unsigned NOT NULL,
  `image_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`infocus_id`,`image_id`),
  KEY `fk_infocus_has_images_infocus1` (`infocus_id`),
  KEY `fk_infocus_has_images_images` (`image_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `infocus_has_videos`
--

CREATE TABLE IF NOT EXISTS `infocus_has_videos` (
  `infocus_id` int(10) unsigned NOT NULL,
  `video_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`infocus_id`,`video_id`),
  KEY `fk_infocus_has_videos_infocus1` (`infocus_id`),
  KEY `fk_infocus_has_videos_videos` (`video_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `infocus_translation`
--

CREATE TABLE IF NOT EXISTS `infocus_translation` (
  `infocus_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `content_lang` char(2) NOT NULL,
  `header` varchar(500) NOT NULL,
  `brief` text NOT NULL,
  `tags` varchar(1024) DEFAULT NULL,
  PRIMARY KEY (`infocus_id`,`content_lang`),
  KEY `fk_infocus_translation_1` (`infocus_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `internal_videos`
--

CREATE TABLE IF NOT EXISTS `internal_videos` (
  `video_id` int(10) unsigned NOT NULL,
  `video_ext` varchar(4) NOT NULL,
  `img_ext` varchar(4) DEFAULT NULL,
  PRIMARY KEY (`video_id`),
  KEY `fk_internal_videos_videos1` (`video_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `issues`
--

CREATE TABLE IF NOT EXISTS `issues` (
  `issue_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `issue_date` date NOT NULL,
  `published` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`issue_id`),
  UNIQUE KEY `issue_date_UNIQUE` (`issue_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `issues_articles`
--

CREATE TABLE IF NOT EXISTS `issues_articles` (
  `article_id` int(10) unsigned NOT NULL,
  `issue_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`article_id`,`issue_id`),
  KEY `fk_issues_articles_issues1` (`issue_id`),
  KEY `fk_issues_articles_1` (`article_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE IF NOT EXISTS `jobs` (
  `job_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `category_id` smallint(5) unsigned NOT NULL,
  `published` tinyint(1) DEFAULT '1',
  `expire_date` datetime DEFAULT NULL,
  `publish_date` datetime NOT NULL,
  PRIMARY KEY (`job_id`),
  KEY `fk_jobs_jobs_categories1` (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `jobs_categories`
--

CREATE TABLE IF NOT EXISTS `jobs_categories` (
  `category_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `published` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `jobs_categories_translation`
--

CREATE TABLE IF NOT EXISTS `jobs_categories_translation` (
  `category_id` smallint(5) unsigned NOT NULL,
  `content_lang` char(2) NOT NULL,
  `category_name` varchar(45) NOT NULL,
  `category_description` text,
  PRIMARY KEY (`category_id`,`content_lang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `jobs_requests`
--

CREATE TABLE IF NOT EXISTS `jobs_requests` (
  `request_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `job_id` smallint(5) unsigned DEFAULT NULL,
  `content_lang` char(2) NOT NULL,
  `nationality` char(2) NOT NULL,
  `accepted` tinyint(1) DEFAULT '0',
  `date_of_birth` date NOT NULL,
  `sex` enum('F','M') NOT NULL,
  `military` tinyint(3) unsigned NOT NULL,
  `marital` tinyint(3) unsigned NOT NULL,
  `have_children` tinyint(1) DEFAULT NULL,
  `driving_license` tinyint(1) DEFAULT NULL,
  `car_owner` tinyint(1) DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `mobile` varchar(15) DEFAULT NULL,
  `fax` varchar(15) DEFAULT NULL,
  `email` varchar(65) NOT NULL,
  `name` varchar(45) NOT NULL,
  `city` varchar(100) DEFAULT NULL,
  `educations` text,
  `work_experiences` text,
  `computer_skills` text,
  `professional_certifications` text,
  `career_objective` text,
  `attach_ext` varchar(4) DEFAULT NULL,
  `address` text,
  `short_list` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`request_id`),
  KEY `fk_jobs_requests_jobs1` (`job_id`),
  KEY `fk_jobs_requests_countries1` (`nationality`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `jobs_translation`
--

CREATE TABLE IF NOT EXISTS `jobs_translation` (
  `job_id` smallint(5) unsigned NOT NULL,
  `content_lang` char(2) NOT NULL,
  `job` varchar(100) NOT NULL,
  `job_description` text,
  PRIMARY KEY (`job_id`,`content_lang`),
  KEY `fk_jobs_translation_jobs1` (`job_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `log_data`
--

CREATE TABLE IF NOT EXISTS `log_data` (
  `log_id` bigint(20) unsigned NOT NULL,
  `data` blob NOT NULL,
  `title` varchar(1024) NOT NULL,
  PRIMARY KEY (`log_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `maillist`
--

CREATE TABLE IF NOT EXISTS `maillist` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ip` varchar(15) NOT NULL,
  `status` tinyint(1) DEFAULT '0',
  `person_id` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `person_id` (`person_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `maillist_articles_log`
--

CREATE TABLE IF NOT EXISTS `maillist_articles_log` (
  `log_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `article_id` int(10) unsigned NOT NULL,
  `subscriber_id` int(10) unsigned NOT NULL,
  `message_id` int(10) unsigned NOT NULL,
  `ip` varchar(15) NOT NULL,
  `log_date` datetime NOT NULL,
  PRIMARY KEY (`log_id`),
  KEY `fk_maillist_articles_log_articles1` (`article_id`),
  KEY `fk_maillist_articles_log_maillist_channels_subscribe1` (`subscriber_id`),
  KEY `fk_maillist_articles_log_maillist_message1` (`message_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `maillist_channels`
--

CREATE TABLE IF NOT EXISTS `maillist_channels` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `channel` varchar(45) NOT NULL,
  `content_lang` char(2) DEFAULT NULL,
  `channel_command` varchar(15) DEFAULT NULL,
  `is_system` tinyint(1) DEFAULT '0',
  `auto_generate` tinyint(1) DEFAULT '0',
  `published` tinyint(3) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `maillist_channels`
--

INSERT INTO `maillist_channels` (`id`, `channel`, `content_lang`, `channel_command`, `is_system`, `auto_generate`, `published`) VALUES
(1, 'الاخبار', 'ar', 'News', 1, 1, 1),
(2, 'News', 'en', 'News', 1, 1, 1),
(3, 'الاخبار العاجلة', 'ar', 'Breaking', 1, 1, 1),
(4, 'Breaking News', 'en', 'Breaking', 1, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `maillist_channels_subscribe`
--

CREATE TABLE IF NOT EXISTS `maillist_channels_subscribe` (
  `channel_id` smallint(5) unsigned NOT NULL,
  `subscriber_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`channel_id`,`subscriber_id`),
  KEY `fk_mailist_channels_has_maillist_maillist1` (`subscriber_id`),
  KEY `fk_mailist_channels_has_maillist_mailist_channels1` (`channel_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `maillist_channels_templates`
--

CREATE TABLE IF NOT EXISTS `maillist_channels_templates` (
  `template_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `template` smallint(5) unsigned DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `body` text,
  `channel_id` smallint(5) unsigned DEFAULT NULL,
  PRIMARY KEY (`template_id`),
  KEY `fk_maillist_channels_templates_maillist_channels1` (`channel_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `maillist_channels_templates`
--

INSERT INTO `maillist_channels_templates` (`template_id`, `template`, `subject`, `body`, `channel_id`) VALUES
(1, 1, 'الاخبار', NULL, 1),
(2, 1, 'News', NULL, 2),
(3, 2, 'الاخبار العاجلة', NULL, 3),
(4, 2, 'Breaking News', NULL, 4);

-- --------------------------------------------------------

--
-- Table structure for table `maillist_log`
--

CREATE TABLE IF NOT EXISTS `maillist_log` (
  `log_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `subscriber_id` int(10) unsigned NOT NULL,
  `message_id` int(10) unsigned NOT NULL,
  `ip` varchar(15) NOT NULL,
  `log_date` datetime NOT NULL,
  PRIMARY KEY (`log_id`),
  KEY `fk_maillist_log_maillist_subscribe1_idx` (`subscriber_id`),
  KEY `fk_maillist_log_maillist_message1_idx` (`message_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `maillist_message`
--

CREATE TABLE IF NOT EXISTS `maillist_message` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `channel_id` smallint(5) unsigned DEFAULT NULL,
  `template_id` smallint(5) unsigned DEFAULT NULL,
  `subject` varchar(255) NOT NULL,
  `body` text,
  `cron_condition` enum('hour','day','week','month','year') DEFAULT NULL,
  `cron_time` int(10) unsigned DEFAULT NULL,
  `cron_step` smallint(5) unsigned DEFAULT NULL,
  `cron_start` datetime NOT NULL,
  `cron_end` datetime DEFAULT NULL,
  `published` tinyint(3) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `fk_mailist_message_mailist_channels1_idx` (`channel_id`),
  KEY `fk_mailist_message_maillist_channels_templates1_idx` (`template_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `maillist_message`
--

INSERT INTO `maillist_message` (`id`, `channel_id`, `template_id`, `subject`, `body`, `cron_condition`, `cron_time`, `cron_step`, `cron_start`, `cron_end`, `published`) VALUES
(1, 1, 1, 'الاخبار', NULL, 'hour', NULL, 1, '2013-11-11 15:47:35', NULL, 1),
(2, 2, 2, 'News', NULL, 'hour', NULL, 1, '2013-11-11 15:47:35', NULL, 1),
(3, 3, 3, 'الاخبار العاجلة', NULL, 'hour', NULL, 1, '2013-11-11 15:47:35', NULL, 1),
(4, 4, 4, 'Breaking News', NULL, 'hour', NULL, 1, '2013-11-11 15:47:35', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `maillist_messages_setions`
--

CREATE TABLE IF NOT EXISTS `maillist_messages_setions` (
  `section_id` smallint(5) unsigned NOT NULL,
  `message_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`section_id`,`message_id`),
  KEY `fk_maillist_messages_setions_maillist_message1_idx` (`message_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `maillist_message_queue`
--

CREATE TABLE IF NOT EXISTS `maillist_message_queue` (
  `message_id` int(10) unsigned NOT NULL,
  `maillist_id` int(10) unsigned NOT NULL,
  `sent` int(10) unsigned DEFAULT '0',
  PRIMARY KEY (`message_id`,`maillist_id`),
  KEY `fk_maillist_message_has_maillist_maillist1_idx` (`maillist_id`),
  KEY `fk_maillist_message_has_maillist_maillist_message1_idx` (`message_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `maillist_users`
--

CREATE TABLE IF NOT EXISTS `maillist_users` (
  `user_id` int(10) unsigned NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `menus`
--

CREATE TABLE IF NOT EXISTS `menus` (
  `menu_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `menu_name` varchar(45) DEFAULT NULL,
  `levels` tinyint(3) unsigned DEFAULT '3',
  PRIMARY KEY (`menu_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `menus`
--

INSERT INTO `menus` (`menu_id`, `menu_name`, `levels`) VALUES
(1, 'MainMenu', 3),
(2, 'TopMenu', 0),
(3, 'BottomMenu', 2);

-- --------------------------------------------------------

--
-- Table structure for table `menus_params`
--

CREATE TABLE IF NOT EXISTS `menus_params` (
  `param_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `param` varchar(45) NOT NULL,
  `param_type` enum('MENU_CLASS','CODE','ROUTE','HTML') NOT NULL DEFAULT 'ROUTE',
  PRIMARY KEY (`param_id`),
  UNIQUE KEY `idx_module_components_params` (`param`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `menus_params`
--

INSERT INTO `menus_params` (`param_id`, `param`, `param_type`) VALUES
(1, 'id', 'ROUTE'),
(2, 'sectionSectionsList', 'MENU_CLASS'),
(3, 'sectionArticlesList', 'MENU_CLASS'),
(4, 'view', 'CODE'),
(5, 'task', 'CODE'),
(6, 'module', 'ROUTE'),
(7, 'showPrimaryHeader', 'CODE'),
(8, 'showDate', 'CODE');

-- --------------------------------------------------------

--
-- Table structure for table `menu_items`
--

CREATE TABLE IF NOT EXISTS `menu_items` (
  `item_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `parent_item` smallint(5) unsigned DEFAULT NULL,
  `menu_id` smallint(5) unsigned NOT NULL,
  `sort_item` smallint(5) unsigned NOT NULL DEFAULT '0',
  `link` varchar(100) DEFAULT NULL,
  `icon` varchar(3) DEFAULT NULL,
  `page_img` varchar(3) DEFAULT NULL,
  `published` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`item_id`),
  KEY `fk_menus` (`menu_id`),
  KEY `fk_menus_items` (`parent_item`),
  KEY `sort_item` (`sort_item`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=58 ;

--
-- Dumping data for table `menu_items`
--

INSERT INTO `menu_items` (`item_id`, `parent_item`, `menu_id`, `sort_item`, `link`, `icon`, `page_img`, `published`) VALUES
(56, NULL, 1, 1, 'site/index', NULL, NULL, 1),
(57, NULL, 2, 2, 'site/contact', NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `menu_items_params`
--

CREATE TABLE IF NOT EXISTS `menu_items_params` (
  `item_id` smallint(5) unsigned NOT NULL,
  `component_id` smallint(5) unsigned NOT NULL,
  `param_id` smallint(5) unsigned NOT NULL,
  `value` varchar(45) NOT NULL,
  PRIMARY KEY (`item_id`,`component_id`,`param_id`),
  KEY `fk_menu_items_has_menus_params_menu_items1` (`item_id`),
  KEY `fk_menu_items_params_modules_components_params1` (`component_id`,`param_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `menu_item_translation`
--

CREATE TABLE IF NOT EXISTS `menu_item_translation` (
  `item_id` smallint(5) unsigned NOT NULL,
  `content_lang` char(2) NOT NULL,
  `label` varchar(100) DEFAULT NULL,
  `page_title` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`item_id`,`content_lang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `menu_item_translation`
--

INSERT INTO `menu_item_translation` (`item_id`, `content_lang`, `label`, `page_title`) VALUES
(56, 'en', 'Home', NULL),
(57, 'en', 'Contact us', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `modules`
--

CREATE TABLE IF NOT EXISTS `modules` (
  `module_id` mediumint(9) NOT NULL AUTO_INCREMENT,
  `parent_module` mediumint(9) DEFAULT NULL,
  `module` varchar(30) DEFAULT NULL,
  `virtual` tinyint(1) DEFAULT '0',
  `enabled` tinyint(1) DEFAULT '1',
  `system` tinyint(1) DEFAULT '0',
  `workflow_enabled` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`module_id`),
  KEY `fk_modules_modules1` (`parent_module`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=84 ;

--
-- Dumping data for table `modules`
--

INSERT INTO `modules` (`module_id`, `parent_module`, `module`, `virtual`, `enabled`, `system`, `workflow_enabled`) VALUES
(1, NULL, 'backend', 0, 1, 0, 0),
(2, 1, 'multimedia', 0, 1, 0, 0),
(3, 1, 'users', 0, 1, 0, 0),
(4, 1, 'sections', 0, 1, 0, 0),
(5, 1, 'uploads', 0, 1, 1, 0),
(6, 1, 'writers', 0, 1, 0, 0),
(7, 1, 'persons', 0, 1, 0, 0),
(8, 1, 'maillist', 0, 0, 0, 0),
(10, 1, 'votes', 0, 1, 0, 0),
(11, 1, 'news', 0, 1, 0, 0),
(14, 1, 'usersArticles', 0, 0, 0, 0),
(15, 1, 'articles', 0, 1, 0, 0),
(16, 1, 'events', 0, 1, 0, 0),
(18, NULL, 'site', 1, 1, 1, 0),
(20, NULL, 'users', 0, 1, 0, 0),
(22, NULL, 'news', 0, 1, 0, 0),
(23, NULL, 'multimedia', 0, 1, 0, 0),
(24, NULL, 'maillist', 0, 1, 1, 0),
(26, NULL, 'rss', 0, 1, 1, 0),
(27, NULL, 'mobile', 1, 1, 1, 0),
(28, NULL, 'api', 1, 1, 1, 0),
(29, 1, 'settings', 0, 1, 0, 0),
(30, NULL, 'events', 0, 1, 0, 0),
(31, 1, 'sms', 0, 0, 0, 0),
(32, NULL, 'articles', 0, 1, 0, 0),
(34, 1, 'directory', 0, 0, 0, 0),
(35, 1, 'glossary', 0, 0, 0, 0),
(36, 1, 'menus', 0, 1, 0, 0),
(37, 1, 'documents', 0, 1, 0, 0),
(38, NULL, 'attachment', 1, 1, 1, 0),
(39, 1, 'companyArticles', 0, 0, 0, 0),
(40, NULL, 'directory', 0, 0, 0, 0),
(41, NULL, 'companyArticles', 0, 0, 0, 0),
(42, 1, 'jobs', 0, 1, 0, 0),
(43, NULL, 'jobs', 0, 1, 0, 0),
(44, NULL, 'agencies', 0, 0, 0, 0),
(45, 1, 'tenders', 0, 0, 0, 0),
(46, NULL, 'tenders', 0, 0, 0, 0),
(76, 1, 'breaking', 0, 1, 0, 0),
(77, 1, 'essays', 0, 1, 0, 0),
(78, 1, 'issueArticles', 0, 0, 0, 0),
(79, NULL, 'usersArticles', 0, 1, 0, 0),
(80, NULL, 'essays', 0, 1, 0, 0),
(81, NULL, 'issueArticles', 0, 0, 0, 0),
(82, 1, 'infocus', 0, 1, 0, 0),
(83, 1, 'ads', 0, 1, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `modules_components`
--

CREATE TABLE IF NOT EXISTS `modules_components` (
  `component_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `module_id` mediumint(9) NOT NULL,
  `route` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`component_id`),
  KEY `fk_modules_components` (`module_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=18 ;

--
-- Dumping data for table `modules_components`
--

INSERT INTO `modules_components` (`component_id`, `module_id`, `route`) VALUES
(1, 18, 'site/index'),
(2, 32, 'articles/default/view'),
(3, 22, 'articles/default/view'),
(4, 32, 'articles/default/sections'),
(5, 22, 'articles/default/sections'),
(6, 30, 'events/default/index'),
(7, 18, 'site/contact'),
(8, 18, 'site/siteMap'),
(9, 37, 'documents/default/index'),
(10, 35, 'glossary/default/index'),
(11, 34, 'directory/default/index'),
(12, 44, 'agencies/default/index'),
(13, 43, 'jobs/default/index'),
(14, 46, 'tenders/default/index'),
(15, 23, 'multimedia/default/index'),
(16, 80, 'articles/default/view'),
(17, 80, 'articles/default/sections');

-- --------------------------------------------------------

--
-- Table structure for table `modules_components_params`
--

CREATE TABLE IF NOT EXISTS `modules_components_params` (
  `component_id` smallint(5) unsigned NOT NULL,
  `param_id` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`component_id`,`param_id`),
  KEY `fk_modules_components_has_modules_components_params_modules_c2` (`param_id`),
  KEY `fk_modules_components_has_modules_components_params_modules_c1` (`component_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `modules_components_params`
--

INSERT INTO `modules_components_params` (`component_id`, `param_id`) VALUES
(2, 1),
(3, 1),
(4, 1),
(5, 1),
(6, 1),
(9, 1),
(11, 1),
(15, 1),
(16, 1),
(17, 1),
(4, 2),
(5, 2),
(17, 2),
(4, 3),
(4, 4),
(4, 5),
(5, 6),
(17, 6),
(2, 7),
(4, 7),
(2, 8),
(4, 8);

-- --------------------------------------------------------

--
-- Table structure for table `modules_components_translation`
--

CREATE TABLE IF NOT EXISTS `modules_components_translation` (
  `component_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `content_lang` char(2) NOT NULL,
  `component_name` varchar(45) NOT NULL,
  PRIMARY KEY (`component_id`,`content_lang`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=18 ;

--
-- Dumping data for table `modules_components_translation`
--

INSERT INTO `modules_components_translation` (`component_id`, `content_lang`, `component_name`) VALUES
(1, 'ar', 'الرئيسية'),
(1, 'en', 'Home page'),
(2, 'ar', 'عرض صفحة'),
(2, 'en', 'View Page'),
(3, 'ar', 'عرض خبر'),
(3, 'en', 'View News Item'),
(4, 'ar', 'أقسام صفحات الموقع'),
(4, 'en', 'Site pages Sections'),
(5, 'ar', 'اقسام الاخبار'),
(5, 'en', 'News Sections'),
(6, 'ar', 'أنشطة وفعاليات'),
(6, 'en', 'Agenda'),
(7, 'ar', 'اتصل بنا'),
(7, 'en', 'Contact us'),
(8, 'ar', 'خريطة الموقع'),
(8, 'en', 'Sitemap'),
(9, 'ar', 'الوثائق'),
(9, 'en', 'Documents'),
(10, 'ar', 'المصطلحات'),
(10, 'en', 'Glossary'),
(11, 'ar', 'الدليل'),
(11, 'en', 'Directory'),
(12, 'ar', 'تعريفة الخدمات للوكالة الملاحية'),
(12, 'en', 'Agencies List'),
(13, 'ar', 'الوظائف'),
(13, 'en', 'Jobs'),
(14, 'ar', 'المناقصات والعطاءات'),
(14, 'en', 'Tenders'),
(15, 'ar', 'وسائط منعددة'),
(15, 'en', 'multimedia'),
(16, 'ar', 'عرض المقال'),
(16, 'en', 'View Article'),
(17, 'ar', 'أقسام المقالات'),
(17, 'en', 'Articles Sections');

-- --------------------------------------------------------

--
-- Table structure for table `module_social_config`
--

CREATE TABLE IF NOT EXISTS `module_social_config` (
  `config_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `module_id` mediumint(9) NOT NULL,
  `social_id` tinyint(3) unsigned DEFAULT NULL,
  `ref_id` int(10) unsigned DEFAULT NULL,
  `table_id` tinyint(3) unsigned DEFAULT NULL,
  `post_date` datetime DEFAULT NULL,
  PRIMARY KEY (`config_id`),
  KEY `fk_module_attachment_modules1` (`module_id`),
  KEY `fk_module_social_config_social_networks1_idx` (`social_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `module_social_config_langs`
--

CREATE TABLE IF NOT EXISTS `module_social_config_langs` (
  `config_id` int(10) unsigned NOT NULL,
  `content_lang` char(2) NOT NULL,
  PRIMARY KEY (`config_id`,`content_lang`),
  KEY `fk_module_social_config_langs_module_social_config1_idx` (`config_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `moduls_components_params_translation`
--

CREATE TABLE IF NOT EXISTS `moduls_components_params_translation` (
  `content_lang` char(2) NOT NULL,
  `component_id` smallint(5) unsigned NOT NULL,
  `param_id` smallint(5) unsigned NOT NULL,
  `label` varchar(45) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`content_lang`,`component_id`,`param_id`),
  KEY `fk_moduls_components_params_translation1` (`component_id`,`param_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `moduls_components_params_translation`
--

INSERT INTO `moduls_components_params_translation` (`content_lang`, `component_id`, `param_id`, `label`, `description`) VALUES
('ar', 2, 1, 'عنوان الصفحة', NULL),
('ar', 2, 7, 'عرض /اخفاء العنوان التمهيدي', NULL),
('ar', 2, 8, 'عرض/اخفاء التاريخ', NULL),
('ar', 3, 1, 'عنوان الخبر', NULL),
('ar', 4, 1, 'عنوان القسم', NULL),
('ar', 4, 2, 'اضافة الاقسام الفرعيه للقائمة', NULL),
('ar', 4, 3, 'اضافة صفحات القسم للقائمة', NULL),
('ar', 4, 4, 'نموذج عرض المحتوي', NULL),
('ar', 4, 5, 'طريقة عرض المحتوي', NULL),
('ar', 4, 7, 'عرض /اخفاء العنوان التمهيدي', NULL),
('ar', 4, 8, 'عرض/اخفاء التاريخ', NULL),
('ar', 5, 1, 'عنوان القسم', NULL),
('ar', 5, 2, 'اضافة الاقسام الفرعيه للقائمة', NULL),
('ar', 5, 6, 'module', NULL),
('ar', 6, 1, 'عنوان القسم', NULL),
('ar', 9, 1, 'اسم التصنيف', NULL),
('ar', 11, 1, 'اسم التصنيف', NULL),
('ar', 16, 1, 'عنوان المقال', NULL),
('ar', 17, 1, 'عنوان القسم', NULL),
('ar', 17, 2, 'اضافة الاقسام الفرعيه للقائمة', NULL),
('ar', 17, 6, 'module', NULL),
('en', 2, 1, 'Site page title', NULL),
('en', 2, 7, 'Show/Hide Primary Header', NULL),
('en', 2, 8, 'Show/Hide date', NULL),
('en', 3, 1, 'News title', NULL),
('en', 4, 1, 'Section title', NULL),
('en', 4, 2, 'Append sub-sections to the menu list', NULL),
('en', 4, 3, 'Append site pages to the menu list', NULL),
('en', 4, 4, 'View Type', NULL),
('en', 4, 5, 'Others', NULL),
('en', 4, 7, 'Show/Hide Primary Header', NULL),
('en', 4, 8, 'Show/Hide date', NULL),
('en', 5, 1, 'Section title', NULL),
('en', 5, 2, 'Append sub-sections to the menu list', NULL),
('en', 5, 6, 'module', NULL),
('en', 6, 1, 'Section ID', NULL),
('en', 11, 1, 'Category Name', NULL),
('en', 16, 1, 'News title', NULL),
('en', 17, 1, 'Section title', NULL),
('en', 17, 2, 'Append sub-sections to the menu list', NULL),
('en', 17, 6, 'module', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `news`
--

CREATE TABLE IF NOT EXISTS `news` (
  `article_id` int(10) unsigned NOT NULL,
  `is_breaking` tinyint(4) DEFAULT '0',
  `source_id` smallint(5) unsigned DEFAULT NULL,
  PRIMARY KEY (`article_id`),
  KEY `fk_news_news_source1_idx` (`source_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `news_editors`
--

CREATE TABLE IF NOT EXISTS `news_editors` (
  `article_id` int(10) unsigned NOT NULL,
  `editor_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`article_id`,`editor_id`),
  KEY `fk_news_has_editors_editors1_idx` (`editor_id`),
  KEY `fk_news_has_editors_news1_idx` (`article_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `news_sources`
--

CREATE TABLE IF NOT EXISTS `news_sources` (
  `source_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`source_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `news_sources`
--

INSERT INTO `news_sources` (`source_id`, `url`) VALUES
(1, NULL),
(2, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `news_sources_translation`
--

CREATE TABLE IF NOT EXISTS `news_sources_translation` (
  `source_id` smallint(5) unsigned NOT NULL,
  `content_lang` char(2) NOT NULL,
  `source` varchar(100) NOT NULL,
  PRIMARY KEY (`source_id`,`content_lang`),
  KEY `fk_news_source_translation_news_source1_idx` (`source_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `news_sources_translation`
--

INSERT INTO `news_sources_translation` (`source_id`, `content_lang`, `source`) VALUES
(1, 'en', 'Daily News Egypt'),
(2, 'en', 'Arabian Supply Chain');

-- --------------------------------------------------------

--
-- Table structure for table `persons`
--

CREATE TABLE IF NOT EXISTS `persons` (
  `person_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(65) NOT NULL DEFAULT '',
  `inserted_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `country_code` char(2) NOT NULL,
  `sex` enum('m','f') NOT NULL,
  `thumb` varchar(4) DEFAULT NULL,
  `phone` varchar(45) DEFAULT NULL,
  `mobile` varchar(45) DEFAULT NULL,
  `fax` varchar(45) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  PRIMARY KEY (`person_id`),
  KEY `fk_persons_countries1` (`country_code`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `persons`
--

INSERT INTO `persons` (`person_id`, `email`, `inserted_date`, `country_code`, `sex`, `thumb`, `phone`, `mobile`, `fax`, `date_of_birth`) VALUES
(1, 'info@amiral.com', '2011-07-20 23:59:13', 'EG', 'm', NULL, '', '', '', '1966-12-20'),
(2, 'ashraf.akl@amiral.com', '2013-01-22 03:33:44', 'EG', 'm', NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `persons_translation`
--

CREATE TABLE IF NOT EXISTS `persons_translation` (
  `person_id` int(10) unsigned NOT NULL,
  `content_lang` char(2) NOT NULL,
  `name` varchar(65) NOT NULL,
  PRIMARY KEY (`person_id`,`content_lang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `persons_translation`
--

INSERT INTO `persons_translation` (`person_id`, `content_lang`, `name`) VALUES
(1, 'ar', 'مدير الموقع'),
(1, 'en', 'Administrator'),
(2, 'ar', 'محرر'),
(2, 'en', 'Editor');

-- --------------------------------------------------------

--
-- Table structure for table `prayer_times`
--

CREATE TABLE IF NOT EXISTS `prayer_times` (
  `city_id` mediumint(8) unsigned NOT NULL,
  `fajr` int(11) NOT NULL,
  `sunrise` int(11) NOT NULL,
  `dhuhr` int(11) NOT NULL,
  `asr` int(11) NOT NULL,
  `maghrib` int(11) NOT NULL,
  `isha` int(11) NOT NULL,
  PRIMARY KEY (`city_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `regions`
--

CREATE TABLE IF NOT EXISTS `regions` (
  `region_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`region_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `regions`
--

INSERT INTO `regions` (`region_id`) VALUES
(1),
(2);

-- --------------------------------------------------------

--
-- Table structure for table `regions_has_countries`
--

CREATE TABLE IF NOT EXISTS `regions_has_countries` (
  `region_id` smallint(5) unsigned NOT NULL,
  `country_code` char(2) NOT NULL,
  `region_sort` smallint(5) unsigned DEFAULT '0',
  PRIMARY KEY (`region_id`,`country_code`),
  KEY `fk_regions_has_countries_countries` (`country_code`),
  KEY `fk_regions_has_countries_regions` (`region_id`),
  KEY `index_region_sort` (`region_sort`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `regions_has_countries`
--

INSERT INTO `regions_has_countries` (`region_id`, `country_code`, `region_sort`) VALUES
(1, 'EG', 1),
(2, 'EG', 1),
(1, 'DZ', 2),
(2, 'DZ', 2),
(1, 'BH', 3),
(2, 'BH', 3),
(1, 'IR', 4),
(1, 'IQ', 5),
(2, 'IQ', 5),
(1, 'JO', 6),
(2, 'JO', 6),
(1, 'KW', 7),
(2, 'KW', 7),
(1, 'LB', 8),
(2, 'LB', 8),
(1, 'LY', 9),
(2, 'LY', 9),
(1, 'MA', 10),
(2, 'MA', 10),
(1, 'OM', 11),
(2, 'OM', 11),
(1, 'PS', 12),
(2, 'PS', 12),
(1, 'QA', 13),
(2, 'QA', 13),
(1, 'SA', 14),
(2, 'SA', 14),
(1, 'SD', 15),
(2, 'SD', 15),
(1, 'SY', 16),
(2, 'SY', 16),
(1, 'TN', 17),
(2, 'TN', 17),
(1, 'TR', 18),
(1, 'AE', 19),
(2, 'AE', 19),
(1, 'YE', 20),
(2, 'YE', 20),
(1, 'IL', 21);

-- --------------------------------------------------------

--
-- Table structure for table `regions_translation`
--

CREATE TABLE IF NOT EXISTS `regions_translation` (
  `region_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `content_lang` char(2) NOT NULL,
  `region` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`region_id`,`content_lang`),
  KEY `fk_regions_names_1` (`region_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `regions_translation`
--

INSERT INTO `regions_translation` (`region_id`, `content_lang`, `region`) VALUES
(1, 'ar', 'الشرق الاوسط'),
(1, 'en', 'Middle East'),
(2, 'ar', 'الوطن العربي'),
(2, 'en', 'Arab World');

-- --------------------------------------------------------

--
-- Table structure for table `related_sections`
--

CREATE TABLE IF NOT EXISTS `related_sections` (
  `section` smallint(5) unsigned NOT NULL,
  `related_section` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`section`,`related_section`),
  KEY `fk_sections_ids_has_sections_ids_sections_ids2` (`related_section`),
  KEY `fk_sections_ids_has_sections_ids_sections_ids1` (`section`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `related_websites`
--

CREATE TABLE IF NOT EXISTS `related_websites` (
  `website_id` smallint(6) NOT NULL AUTO_INCREMENT,
  `published` tinyint(1) NOT NULL DEFAULT '1',
  `url` varchar(150) NOT NULL,
  `hits` int(10) unsigned NOT NULL DEFAULT '0',
  `image_ext` varchar(3) DEFAULT NULL,
  PRIMARY KEY (`website_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `related_websites_translation`
--

CREATE TABLE IF NOT EXISTS `related_websites_translation` (
  `website_id` smallint(6) NOT NULL,
  `content_lang` char(2) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text,
  PRIMARY KEY (`website_id`,`content_lang`),
  KEY `fk_related_website_translation_1` (`website_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `reset_passwods`
--

CREATE TABLE IF NOT EXISTS `reset_passwods` (
  `reset_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `reset_key` char(8) NOT NULL,
  `reset_date` date NOT NULL,
  PRIMARY KEY (`reset_id`),
  KEY `fk_reset_passwods_users1_idx` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE IF NOT EXISTS `roles` (
  `role_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `role` varchar(20) NOT NULL,
  `parent_role_id` smallint(5) unsigned DEFAULT NULL,
  `role_desc` varchar(45) DEFAULT NULL,
  `is_system` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`role_id`),
  KEY `fk_roles_roles1` (`parent_role_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`role_id`, `role`, `parent_role_id`, `role_desc`, `is_system`) VALUES
(1, 'guest', NULL, 'Guest', 1),
(2, 'editor', 4, 'Editor', 1),
(3, 'admin', 2, 'Admin', 1),
(4, 'registered', 1, 'User', 1);

-- --------------------------------------------------------

--
-- Table structure for table `sections`
--

CREATE TABLE IF NOT EXISTS `sections` (
  `section_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `parent_section` smallint(5) unsigned DEFAULT NULL,
  `published` tinyint(1) DEFAULT '1',
  `section_sort` mediumint(8) unsigned DEFAULT '0',
  `image_ext` varchar(3) DEFAULT NULL,
  `is_system` tinyint(1) DEFAULT NULL,
  `settings` varchar(1024) DEFAULT NULL,
  PRIMARY KEY (`section_id`),
  KEY `fk_sections_sections` (`parent_section`),
  KEY `idx_section_sort` (`section_sort`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `sections`
--

INSERT INTO `sections` (`section_id`, `parent_section`, `published`, `section_sort`, `image_ext`, `is_system`, `settings`) VALUES
(1, NULL, 1, 1, NULL, NULL, 'null');

-- --------------------------------------------------------

--
-- Table structure for table `sections_issues`
--

CREATE TABLE IF NOT EXISTS `sections_issues` (
  `section_id` smallint(5) unsigned NOT NULL,
  `issue_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`section_id`,`issue_id`),
  KEY `fk_sections_has_issues_issues1` (`issue_id`),
  KEY `fk_sections_has_issues_sections1` (`section_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `sections_translation`
--

CREATE TABLE IF NOT EXISTS `sections_translation` (
  `section_id` smallint(5) unsigned NOT NULL,
  `content_lang` char(2) NOT NULL,
  `section_name` varchar(150) NOT NULL,
  `tags` varchar(1024) DEFAULT NULL,
  `supervisor` int(10) unsigned DEFAULT NULL,
  `description` text,
  PRIMARY KEY (`section_id`,`content_lang`),
  KEY `fk_sections_master` (`section_id`),
  KEY `fk_sections_translation_persons1` (`supervisor`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `sections_translation`
--

INSERT INTO `sections_translation` (`section_id`, `content_lang`, `section_name`, `tags`, `supervisor`, `description`) VALUES
(1, 'en', 'New', NULL, NULL, '');

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE IF NOT EXISTS `services` (
  `service_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `service_name` varchar(45) DEFAULT NULL,
  `class_name` varchar(15) DEFAULT NULL,
  `enabled` tinyint(1) DEFAULT '1',
  `cron_condition` enum('day','min') DEFAULT 'min',
  `cron_time` int(11) DEFAULT '0',
  `cron_step` mediumint(9) DEFAULT '10800',
  PRIMARY KEY (`service_id`),
  UNIQUE KEY `class_name_UNIQUE` (`class_name`),
  UNIQUE KEY `service_name_UNIQUE` (`service_name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`service_id`, `service_name`, `class_name`, `enabled`, `cron_condition`, `cron_time`, `cron_step`) VALUES
(1, 'prayer', 'Prayer', 1, 'day', 0, 1),
(2, 'weather', 'Weather', 1, 'day', 1382565615, 1),
(9, 'currency', 'Currency', 1, 'day', 1385848820, 1),
(10, 'stock', 'Stock', 1, 'day', 0, 1),
(11, 'events', 'Events', 1, 'day', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `services_cities`
--

CREATE TABLE IF NOT EXISTS `services_cities` (
  `city_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `country_code` char(2) NOT NULL,
  `latitude` float NOT NULL,
  `longitude` float NOT NULL,
  `timezone` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`city_id`),
  KEY `fk_services_cities_countries1` (`country_code`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=28 ;

--
-- Dumping data for table `services_cities`
--

INSERT INTO `services_cities` (`city_id`, `country_code`, `latitude`, `longitude`, `timezone`) VALUES
(1, 'EG', 30.05, 31.25, 2),
(2, 'EG', 30.05, 31.25, 2),
(3, 'EG', 30.05, 31.25, 2),
(4, 'EG', 30.05, 31.25, 2),
(5, 'EG', 30.05, 31.25, 2),
(6, 'EG', 30.05, 31.25, 2),
(7, 'EG', 30.05, 31.25, 2),
(8, 'EG', 30.05, 31.25, 2),
(9, 'EG', 30.05, 31.25, 2),
(10, 'EG', 30.05, 31.25, 2),
(11, 'EG', 30.05, 31.25, 2),
(12, 'EG', 30.05, 31.25, 2),
(13, 'EG', 30.05, 31.25, 2),
(14, 'EG', 30.05, 31.25, 2),
(15, 'EG', 30.05, 31.25, 2),
(16, 'EG', 30.05, 31.25, 2),
(17, 'EG', 30.05, 31.25, 2),
(18, 'EG', 30.05, 31.25, 2),
(19, 'EG', 30.05, 31.25, 2),
(20, 'EG', 30.05, 31.25, 2),
(21, 'EG', 30.05, 31.25, 2),
(22, 'EG', 30.05, 31.25, 2),
(23, 'EG', 30.05, 31.25, 2),
(24, 'EG', 30.05, 31.25, 2),
(25, 'EG', 30.05, 31.25, 2),
(26, 'EG', 30.05, 31.25, 2),
(27, 'EG', 30.05, 31.25, 2);

-- --------------------------------------------------------

--
-- Table structure for table `services_cities_translation`
--

CREATE TABLE IF NOT EXISTS `services_cities_translation` (
  `city_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `content_lang` char(2) NOT NULL,
  `city` varchar(20) NOT NULL,
  PRIMARY KEY (`city_id`,`content_lang`),
  KEY `fk_services_cities_names_1` (`city_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=28 ;

--
-- Dumping data for table `services_cities_translation`
--

INSERT INTO `services_cities_translation` (`city_id`, `content_lang`, `city`) VALUES
(1, 'ar', 'القاهرة'),
(1, 'en', 'Cairo'),
(2, 'ar', 'اﻻسكندرية'),
(2, 'en', 'Alexandria'),
(3, 'ar', 'اسوان'),
(3, 'en', 'Aswan'),
(4, 'ar', 'الفيوم'),
(4, 'en', 'El Fayoum'),
(5, 'ar', 'الجيزة'),
(5, 'en', 'El-Giza'),
(6, 'ar', 'الغردقة'),
(6, 'en', 'Hurghada'),
(7, 'ar', 'اﻻسماعيلية'),
(7, 'en', 'Ismailia'),
(8, 'ar', 'اﻻقصر'),
(8, 'en', 'Luxor'),
(9, 'ar', 'مرسي مطروح'),
(9, 'en', 'Mersa Matruh'),
(10, 'ar', 'بور توفيق'),
(10, 'en', 'Port Taufiq'),
(11, 'ar', 'قنا'),
(11, 'en', 'Qena'),
(12, 'ar', 'القصير'),
(12, 'en', 'Quseir'),
(13, 'ar', 'سفاجا'),
(13, 'en', 'Safaga'),
(14, 'ar', 'شرم الشيخ'),
(14, 'en', 'Sharm el Sheikh'),
(15, 'ar', 'السويس'),
(15, 'en', 'Suez'),
(16, 'ar', 'الزقازيق'),
(16, 'en', 'Zagazig'),
(17, 'ar', 'السلوم'),
(17, 'en', 'Sallum Plateau'),
(18, 'ar', 'بور سعيد'),
(18, 'en', 'Port Said'),
(19, 'ar', 'العريش'),
(19, 'en', 'Arish'),
(20, 'ar', 'اسيوط'),
(20, 'en', 'Asyut'),
(21, 'ar', 'سيوه'),
(21, 'en', 'Siwa'),
(22, 'ar', 'الخارجه'),
(22, 'en', 'Kharga'),
(23, 'ar', 'دمياط'),
(23, 'en', 'Damietta'),
(24, 'ar', 'رفح'),
(24, 'en', 'Rafah'),
(25, 'ar', 'رأس غارب'),
(25, 'en', 'Ras Gharib'),
(26, 'ar', 'الطور'),
(26, 'en', 'El Tor'),
(27, 'ar', 'سوهاج'),
(27, 'en', 'Sohag');

-- --------------------------------------------------------

--
-- Table structure for table `services_sections`
--

CREATE TABLE IF NOT EXISTS `services_sections` (
  `service_id` smallint(5) unsigned NOT NULL,
  `section_id` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`service_id`,`section_id`),
  KEY `fk_services_has_sections_sections1` (`section_id`),
  KEY `fk_services_has_sections_services1` (`service_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `sms_videos`
--

CREATE TABLE IF NOT EXISTS `sms_videos` (
  `video_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `published` tinyint(1) NOT NULL DEFAULT '1',
  `creation_date` date NOT NULL,
  `update_date` datetime DEFAULT NULL,
  `ext` varchar(4) NOT NULL,
  PRIMARY KEY (`video_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `sms_videos_translation`
--

CREATE TABLE IF NOT EXISTS `sms_videos_translation` (
  `video_id` int(10) unsigned NOT NULL,
  `content_lang` char(2) NOT NULL,
  `video_header` varchar(500) NOT NULL,
  `description` text,
  PRIMARY KEY (`video_id`,`content_lang`),
  KEY `fk_sms_videos_translation_1` (`video_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `social_networks`
--

CREATE TABLE IF NOT EXISTS `social_networks` (
  `social_id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `network_name` varchar(45) NOT NULL,
  `class_name` char(15) NOT NULL,
  `has_media` tinyint(1) DEFAULT '1',
  `cron_time` int(11) DEFAULT '0',
  `cron_step` smallint(5) unsigned NOT NULL DEFAULT '3600',
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  `delay_time` mediumint(8) unsigned DEFAULT '0',
  `send_all` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`social_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `social_networks`
--

INSERT INTO `social_networks` (`social_id`, `network_name`, `class_name`, `has_media`, `cron_time`, `cron_step`, `enabled`, `delay_time`, `send_all`) VALUES
(1, 'Facebook', 'Facebook', NULL, 0, 3600, 1, 0, 0),
(2, 'Twitter', 'Twitter', NULL, 0, 3600, 1, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `system_attributes`
--

CREATE TABLE IF NOT EXISTS `system_attributes` (
  `attribute_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `attribute_type` tinyint(3) unsigned NOT NULL,
  `module_id` mediumint(9) NOT NULL,
  `is_system` tinyint(4) NOT NULL DEFAULT '0',
  `is_new_type` varchar(30) DEFAULT '',
  PRIMARY KEY (`attribute_id`),
  KEY `fk_system_attributes_modules1` (`module_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `system_attributes_translation`
--

CREATE TABLE IF NOT EXISTS `system_attributes_translation` (
  `attribute_id` smallint(5) unsigned NOT NULL,
  `content_lang` char(2) NOT NULL,
  `label` varchar(100) NOT NULL,
  PRIMARY KEY (`attribute_id`,`content_lang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tenders`
--

CREATE TABLE IF NOT EXISTS `tenders` (
  `tender_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `department_id` smallint(5) unsigned NOT NULL,
  `tender_type` tinyint(4) NOT NULL,
  `tender_status` tinyint(4) NOT NULL,
  `rfp_start_date` datetime NOT NULL,
  `rfp_end_date` datetime NOT NULL,
  `submission_start_date` datetime NOT NULL,
  `submission_end_date` datetime NOT NULL,
  `technical_date` datetime DEFAULT NULL,
  `financial_date` datetime DEFAULT NULL,
  `rfp_price1` decimal(10,2) DEFAULT NULL,
  `rfp_price2` decimal(10,2) DEFAULT NULL,
  `primary_insurance` decimal(10,2) DEFAULT NULL,
  `published` tinyint(1) NOT NULL DEFAULT '1',
  `hits` int(10) unsigned NOT NULL DEFAULT '0',
  `comments` int(11) NOT NULL,
  `file_ext` varchar(4) DEFAULT NULL,
  `create_date` datetime NOT NULL,
  `rfp_price1_currency` char(3) NOT NULL DEFAULT 'EGP',
  `rfp_price2_currency` char(3) NOT NULL DEFAULT 'EGP',
  `primary_insurance_currency` char(3) NOT NULL DEFAULT 'EGP',
  PRIMARY KEY (`tender_id`),
  KEY `fk_docs_docs_department1` (`department_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tenders_activities`
--

CREATE TABLE IF NOT EXISTS `tenders_activities` (
  `activity_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `published` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`activity_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tenders_activities_translation`
--

CREATE TABLE IF NOT EXISTS `tenders_activities_translation` (
  `activity_id` smallint(5) unsigned NOT NULL,
  `content_lang` char(2) NOT NULL,
  `activity_name` varchar(100) NOT NULL,
  PRIMARY KEY (`activity_id`,`content_lang`),
  KEY `fk_tenders_activities_translation_1` (`activity_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tenders_comments`
--

CREATE TABLE IF NOT EXISTS `tenders_comments` (
  `tender_id` int(10) unsigned NOT NULL,
  `comment_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`tender_id`,`comment_id`),
  KEY `fk_tenders_has_comments_comments1` (`comment_id`),
  KEY `fk_tenders_has_comments_tenders1` (`tender_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tenders_department`
--

CREATE TABLE IF NOT EXISTS `tenders_department` (
  `department_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `parent_department` smallint(5) unsigned DEFAULT NULL,
  `published` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`department_id`),
  KEY `fk_tender_departments` (`parent_department`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tenders_department_translation`
--

CREATE TABLE IF NOT EXISTS `tenders_department_translation` (
  `department_id` smallint(5) unsigned NOT NULL,
  `content_lang` char(2) NOT NULL,
  `department_name` varchar(100) NOT NULL,
  PRIMARY KEY (`department_id`,`content_lang`),
  KEY `fk_tenders_department_translation` (`department_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tenders_has_activities`
--

CREATE TABLE IF NOT EXISTS `tenders_has_activities` (
  `activity_id` smallint(5) unsigned NOT NULL,
  `tender_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`activity_id`,`tender_id`),
  KEY `fk_activities_has_tenders` (`tender_id`),
  KEY `fk_tenders_has_activities` (`activity_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tenders_translation`
--

CREATE TABLE IF NOT EXISTS `tenders_translation` (
  `tender_id` int(10) unsigned NOT NULL,
  `content_lang` char(2) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `conditions` text,
  `notes` text,
  `technical_results` text,
  `financial_results` text,
  PRIMARY KEY (`tender_id`,`content_lang`),
  KEY `fk_tender_translation_tender1` (`tender_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int(10) unsigned NOT NULL,
  `username` varchar(65) NOT NULL,
  `role_id` smallint(5) unsigned DEFAULT NULL,
  `passwd` char(32) NOT NULL,
  `published` tinyint(1) DEFAULT '0',
  `is_system` tinyint(1) NOT NULL,
  PRIMARY KEY (`user_id`),
  KEY `fk_users_persons` (`user_id`),
  KEY `fk_users_roles1` (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `role_id`, `passwd`, `published`, `is_system`) VALUES
(1, 'admin', 3, '21232f297a57a5a743894a0e4a801fc3', 1, 1),
(2, 'editor', 2, '5aee9dbd2a188839105073571bee1b1f', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `users_access_rights`
--

CREATE TABLE IF NOT EXISTS `users_access_rights` (
  `user_id` int(10) unsigned NOT NULL,
  `role_id` smallint(5) unsigned NOT NULL,
  `controller_id` mediumint(9) NOT NULL,
  `access` mediumint(8) unsigned DEFAULT '0',
  PRIMARY KEY (`user_id`,`role_id`,`controller_id`),
  KEY `fk_users_has_access_rights_users1` (`user_id`),
  KEY `fk_users_access_rights_access_rights1` (`role_id`,`controller_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `users_articles`
--

CREATE TABLE IF NOT EXISTS `users_articles` (
  `article_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`article_id`),
  KEY `fk_users_articles_users1` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `users_cv`
--

CREATE TABLE IF NOT EXISTS `users_cv` (
  `cv_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `military` tinyint(3) unsigned NOT NULL,
  `marital` tinyint(3) unsigned NOT NULL,
  `have_children` tinyint(1) DEFAULT NULL,
  `driving_license` tinyint(1) DEFAULT NULL,
  `car_owner` tinyint(1) DEFAULT NULL,
  `attach_ext` varchar(4) DEFAULT NULL,
  PRIMARY KEY (`cv_id`),
  KEY `fk_jobs_requests_users1` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `users_cv_has_jobs`
--

CREATE TABLE IF NOT EXISTS `users_cv_has_jobs` (
  `cv_id` int(10) unsigned NOT NULL,
  `job_id` smallint(5) unsigned NOT NULL,
  `short_list` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`cv_id`,`job_id`),
  KEY `fk_users_cv_has_jobs_jobs1` (`job_id`),
  KEY `fk_users_cv_has_jobs_users_cv1` (`cv_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `users_cv_translation`
--

CREATE TABLE IF NOT EXISTS `users_cv_translation` (
  `cv_id` int(10) unsigned NOT NULL,
  `content_lang` char(2) NOT NULL,
  `city` varchar(100) DEFAULT NULL,
  `educations` text,
  `work_experiences` text,
  `computer_skills` text,
  `professional_certifications` text,
  `career_objective` text,
  `address` text,
  PRIMARY KEY (`cv_id`,`content_lang`),
  KEY `fk_users_cv_copy1_users_cv1` (`cv_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `users_log`
--

CREATE TABLE IF NOT EXISTS `users_log` (
  `log_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `ip` char(15) NOT NULL,
  `action_id` mediumint(9) NOT NULL,
  `user_id` int(10) unsigned DEFAULT NULL,
  `action_date` datetime NOT NULL,
  PRIMARY KEY (`log_id`),
  KEY `fk_users_log_user_actions` (`action_id`),
  KEY `fk_users_log_users1` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

--
-- Dumping data for table `users_log`
--

INSERT INTO `users_log` (`log_id`, `ip`, `action_id`, `user_id`, `action_date`) VALUES
(10, '101.101.1.239', 591, 1, '2014-07-07 11:18:12'),
(11, '127.0.0.1', 591, 1, '2014-07-07 11:51:55'),
(12, '127.0.0.1', 591, 1, '2014-07-08 10:45:15');

-- --------------------------------------------------------

--
-- Table structure for table `users_workflow_log`
--

CREATE TABLE IF NOT EXISTS `users_workflow_log` (
  `user_id` int(10) unsigned NOT NULL,
  `tasks_id` int(11) NOT NULL,
  `log_date` datetime NOT NULL,
  `status` tinyint(4) NOT NULL,
  PRIMARY KEY (`user_id`,`tasks_id`),
  KEY `fk_users_has_workflow_tasks_workflow_tasks1` (`tasks_id`),
  KEY `fk_users_has_workflow_tasks_users1` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `videos`
--

CREATE TABLE IF NOT EXISTS `videos` (
  `video_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `votes` int(10) unsigned DEFAULT '0',
  `votes_rate` double DEFAULT '1',
  `hits` int(10) unsigned DEFAULT '0',
  `published` tinyint(1) NOT NULL DEFAULT '0',
  `user_id` int(10) unsigned DEFAULT NULL,
  `gallery_id` int(10) unsigned DEFAULT NULL,
  `tags` varchar(1024) DEFAULT NULL,
  `creation_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `publish_date` datetime DEFAULT NULL,
  `expire_date` datetime DEFAULT NULL,
  `video_sort` int(10) unsigned DEFAULT NULL,
  `in_slider` tinyint(1) DEFAULT NULL,
  `show_media` tinyint(1) DEFAULT '1',
  `update_date` datetime DEFAULT NULL,
  `comments` int(10) unsigned DEFAULT '0',
  PRIMARY KEY (`video_id`),
  KEY `fk_videos_users1` (`user_id`),
  KEY `fk_videos_galleries1` (`gallery_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `videos_comments`
--

CREATE TABLE IF NOT EXISTS `videos_comments` (
  `video_id` int(10) unsigned NOT NULL,
  `video_comment_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`video_comment_id`),
  KEY `fk_videos_comments_comments1` (`video_comment_id`),
  KEY `fk_videos_videos_comments` (`video_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `videos_translation`
--

CREATE TABLE IF NOT EXISTS `videos_translation` (
  `video_id` int(10) unsigned NOT NULL,
  `content_lang` char(2) NOT NULL,
  `video_header` varchar(500) NOT NULL,
  `tags` varchar(1024) DEFAULT NULL,
  `description` text,
  `inserted_date` datetime NOT NULL,
  PRIMARY KEY (`video_id`,`content_lang`),
  KEY `fk_videos_translation_1` (`video_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `voters`
--

CREATE TABLE IF NOT EXISTS `voters` (
  `answer_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `option_id` mediumint(8) unsigned NOT NULL,
  `content_lang` char(2) NOT NULL,
  `voted_on` datetime NOT NULL,
  `ip` varchar(16) DEFAULT NULL,
  `user_id` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`answer_id`),
  KEY `fk_votes_options1` (`option_id`,`content_lang`),
  KEY `fk_voters_users1` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `votes_options`
--

CREATE TABLE IF NOT EXISTS `votes_options` (
  `option_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `ques_id` mediumint(8) unsigned NOT NULL,
  `content_lang` char(2) NOT NULL,
  `value` varchar(100) NOT NULL,
  PRIMARY KEY (`option_id`,`content_lang`),
  KEY `fk_poll_options` (`ques_id`,`content_lang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `votes_questions`
--

CREATE TABLE IF NOT EXISTS `votes_questions` (
  `ques_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `creation_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `publish_date` datetime NOT NULL,
  `published` tinyint(1) NOT NULL DEFAULT '0',
  `expire_date` datetime DEFAULT NULL,
  `suspend` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ques_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `votes_questions_translation`
--

CREATE TABLE IF NOT EXISTS `votes_questions_translation` (
  `ques_id` mediumint(8) unsigned NOT NULL,
  `content_lang` char(2) NOT NULL,
  `ques` varchar(100) NOT NULL,
  PRIMARY KEY (`ques_id`,`content_lang`),
  KEY `fk_votes_questions_translation_1` (`ques_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `weather_cities`
--

CREATE TABLE IF NOT EXISTS `weather_cities` (
  `city_id` mediumint(8) unsigned NOT NULL,
  `weather_city` char(8) NOT NULL,
  `temp` tinyint(4) NOT NULL,
  `icon` tinyint(4) NOT NULL,
  `status` varchar(100) NOT NULL,
  `temperature` enum('C','F') NOT NULL DEFAULT 'C',
  `sunr` varchar(10) NOT NULL,
  `suns` varchar(10) NOT NULL,
  `feelslik` smallint(3) NOT NULL,
  `wind` varchar(255) NOT NULL,
  `pressure` varchar(255) NOT NULL,
  `humidity` tinyint(3) NOT NULL,
  `visibility` smallint(6) NOT NULL,
  `uv_index` varchar(255) NOT NULL,
  `moon` varchar(255) NOT NULL,
  `forecast` text,
  PRIMARY KEY (`city_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `weather_cities`
--

INSERT INTO `weather_cities` (`city_id`, `weather_city`, `temp`, `icon`, `status`, `temperature`, `sunr`, `suns`, `feelslik`, `wind`, `pressure`, `humidity`, `visibility`, `uv_index`, `moon`, `forecast`) VALUES
(1, 'EGXX0004', 20, 33, 'Fair', 'F', '6:03 AM', '5:16 PM', 20, '{"speed":"9","gust":"N\\/A","d":"30","from":"NNE"}', '{"r":"30.03","d":"steady"}', 68, 6, '{"i":"0","t":"Low"}', '{"icon":"19","t":"Waning Gibbous"}', '[{"dt":"Oct 23","t":"Wednesday","hi":-18,"low":16,"sunr":"6:02 AM","suns":"5:16 PM","part":{"d":{"icon":"44","t":"N\\/A","wind":{"s":"N\\/A","gust":"N\\/A","d":"N\\/A","t":"N\\/A"},"bt":"N\\/A","ppcp":"10","hmid":"N\\/A"},"n":{"icon":"33","t":"Mostly Clear","wind":{"s":"12","gust":"N\\/A","d":"24","t":"NNE"},"bt":"M Clear","ppcp":"10","hmid":"76"}}},{"dt":"Oct 24","t":"Thursday","hi":29,"low":16,"sunr":"6:03 AM","suns":"5:15 PM","part":{"d":{"icon":"30","t":"Partly Cloudy","wind":{"s":"10","gust":"N\\/A","d":"17","t":"NNE"},"bt":"P Cloudy","ppcp":"10","hmid":"49"},"n":{"icon":"29","t":"Partly Cloudy","wind":{"s":"11","gust":"N\\/A","d":"38","t":"NE"},"bt":"P Cloudy","ppcp":"10","hmid":"77"}}},{"dt":"Oct 25","t":"Friday","hi":29,"low":16,"sunr":"6:04 AM","suns":"5:14 PM","part":{"d":{"icon":"32","t":"Sunny","wind":{"s":"13","gust":"N\\/A","d":"31","t":"NNE"},"bt":"Sunny","ppcp":"0","hmid":"53"},"n":{"icon":"31","t":"Clear","wind":{"s":"9","gust":"N\\/A","d":"34","t":"NE"},"bt":"Clear","ppcp":"0","hmid":"64"}}},{"dt":"Oct 26","t":"Saturday","hi":29,"low":15,"sunr":"6:04 AM","suns":"5:13 PM","part":{"d":{"icon":"32","t":"Sunny","wind":{"s":"12","gust":"N\\/A","d":"26","t":"NNE"},"bt":"Sunny","ppcp":"0","hmid":"46"},"n":{"icon":"31","t":"Clear","wind":{"s":"7","gust":"N\\/A","d":"11","t":"N"},"bt":"Clear","ppcp":"0","hmid":"68"}}},{"dt":"Oct 27","t":"Sunday","hi":28,"low":16,"sunr":"6:05 AM","suns":"5:12 PM","part":{"d":{"icon":"32","t":"Sunny","wind":{"s":"11","gust":"N\\/A","d":"355","t":"N"},"bt":"Sunny","ppcp":"0","hmid":"56"},"n":{"icon":"33","t":"Mostly Clear","wind":{"s":"7","gust":"N\\/A","d":"359","t":"N"},"bt":"M Clear","ppcp":"0","hmid":"69"}}}]'),
(2, 'EGXX0001', 20, 29, 'Partly Cloudy', 'F', '6:09 AM', '5:20 PM', 20, '{"speed":"7","gust":"N\\/A","d":"360","from":"N"}', '{"r":"30.03","d":"steady"}', 78, 6, '{"i":"0","t":"Low"}', '{"icon":"19","t":"Waning Gibbous"}', '[{"dt":"Oct 23","t":"Wednesday","hi":-18,"low":17,"sunr":"6:09 AM","suns":"5:20 PM","part":{"d":{"icon":"44","t":"N\\/A","wind":{"s":"N\\/A","gust":"N\\/A","d":"N\\/A","t":"N\\/A"},"bt":"N\\/A","ppcp":"10","hmid":"N\\/A"},"n":{"icon":"27","t":"Mostly Cloudy","wind":{"s":"6","gust":"N\\/A","d":"357","t":"N"},"bt":"M Cloudy","ppcp":"10","hmid":"83"}}},{"dt":"Oct 24","t":"Thursday","hi":26,"low":17,"sunr":"6:09 AM","suns":"5:19 PM","part":{"d":{"icon":"30","t":"Partly Cloudy","wind":{"s":"10","gust":"N\\/A","d":"351","t":"N"},"bt":"P Cloudy","ppcp":"10","hmid":"70"},"n":{"icon":"29","t":"Partly Cloudy","wind":{"s":"7","gust":"N\\/A","d":"37","t":"NE"},"bt":"P Cloudy","ppcp":"10","hmid":"86"}}},{"dt":"Oct 25","t":"Friday","hi":27,"low":17,"sunr":"6:10 AM","suns":"5:18 PM","part":{"d":{"icon":"32","t":"Sunny","wind":{"s":"12","gust":"N\\/A","d":"35","t":"NE"},"bt":"Sunny","ppcp":"10","hmid":"63"},"n":{"icon":"31","t":"Clear","wind":{"s":"7","gust":"N\\/A","d":"52","t":"NE"},"bt":"Clear","ppcp":"0","hmid":"72"}}},{"dt":"Oct 26","t":"Saturday","hi":26,"low":17,"sunr":"6:11 AM","suns":"5:17 PM","part":{"d":{"icon":"32","t":"Sunny","wind":{"s":"12","gust":"N\\/A","d":"16","t":"NNE"},"bt":"Sunny","ppcp":"0","hmid":"67"},"n":{"icon":"31","t":"Clear","wind":{"s":"6","gust":"N\\/A","d":"351","t":"N"},"bt":"Clear","ppcp":"0","hmid":"77"}}},{"dt":"Oct 27","t":"Sunday","hi":24,"low":17,"sunr":"6:12 AM","suns":"5:16 PM","part":{"d":{"icon":"34","t":"Mostly Sunny","wind":{"s":"12","gust":"N\\/A","d":"331","t":"NNW"},"bt":"M Sunny","ppcp":"0","hmid":"68"},"n":{"icon":"33","t":"Mostly Clear","wind":{"s":"5","gust":"N\\/A","d":"334","t":"NNW"},"bt":"M Clear","ppcp":"0","hmid":"72"}}}]'),
(3, 'EGXX0003', 28, 33, 'Fair', 'F', '5:50 AM', '5:15 PM', 27, '{"speed":"10","gust":"N\\/A","d":"360","from":"N"}', '{"r":"29.88","d":"steady"}', 25, 6, '{"i":"0","t":"Low"}', '{"icon":"19","t":"Waning Gibbous"}', '[{"dt":"Oct 23","t":"Wednesday","hi":-18,"low":22,"sunr":"5:50 AM","suns":"5:15 PM","part":{"d":{"icon":"44","t":"N\\/A","wind":{"s":"N\\/A","gust":"N\\/A","d":"N\\/A","t":"N\\/A"},"bt":"N\\/A","ppcp":"0","hmid":"N\\/A"},"n":{"icon":"31","t":"Clear","wind":{"s":"10","gust":"N\\/A","d":"1","t":"N"},"bt":"Clear","ppcp":"0","hmid":"32"}}},{"dt":"Oct 24","t":"Thursday","hi":37,"low":21,"sunr":"5:50 AM","suns":"5:15 PM","part":{"d":{"icon":"32","t":"Sunny","wind":{"s":"10","gust":"N\\/A","d":"8","t":"N"},"bt":"Sunny","ppcp":"0","hmid":"28"},"n":{"icon":"31","t":"Clear","wind":{"s":"11","gust":"N\\/A","d":"360","t":"N"},"bt":"Clear","ppcp":"0","hmid":"32"}}},{"dt":"Oct 25","t":"Friday","hi":36,"low":21,"sunr":"5:51 AM","suns":"5:14 PM","part":{"d":{"icon":"32","t":"Sunny","wind":{"s":"9","gust":"N\\/A","d":"5","t":"N"},"bt":"Sunny","ppcp":"0","hmid":"28"},"n":{"icon":"31","t":"Clear","wind":{"s":"8","gust":"N\\/A","d":"3","t":"N"},"bt":"Clear","ppcp":"0","hmid":"24"}}},{"dt":"Oct 26","t":"Saturday","hi":35,"low":18,"sunr":"5:51 AM","suns":"5:13 PM","part":{"d":{"icon":"32","t":"Sunny","wind":{"s":"10","gust":"N\\/A","d":"357","t":"N"},"bt":"Sunny","ppcp":"0","hmid":"18"},"n":{"icon":"31","t":"Clear","wind":{"s":"9","gust":"N\\/A","d":"357","t":"N"},"bt":"Clear","ppcp":"0","hmid":"20"}}},{"dt":"Oct 27","t":"Sunday","hi":33,"low":16,"sunr":"5:52 AM","suns":"5:12 PM","part":{"d":{"icon":"32","t":"Sunny","wind":{"s":"10","gust":"N\\/A","d":"351","t":"N"},"bt":"Sunny","ppcp":"0","hmid":"20"},"n":{"icon":"31","t":"Clear","wind":{"s":"8","gust":"N\\/A","d":"343","t":"NNW"},"bt":"Clear","ppcp":"0","hmid":"32"}}}]'),
(4, 'EGXX0005', 20, 33, 'Fair', 'F', '6:04 AM', '5:19 PM', 20, '{"speed":"9","gust":"N\\/A","d":"30","from":"NNE"}', '{"r":"30.03","d":"steady"}', 68, 6, '{"i":"0","t":"Low"}', '{"icon":"19","t":"Waning Gibbous"}', '[{"dt":"Oct 23","t":"Wednesday","hi":-18,"low":16,"sunr":"6:03 AM","suns":"5:19 PM","part":{"d":{"icon":"44","t":"N\\/A","wind":{"s":"N\\/A","gust":"N\\/A","d":"N\\/A","t":"N\\/A"},"bt":"N\\/A","ppcp":"10","hmid":"N\\/A"},"n":{"icon":"29","t":"Partly Cloudy","wind":{"s":"8","gust":"N\\/A","d":"24","t":"NNE"},"bt":"P Cloudy","ppcp":"10","hmid":"80"}}},{"dt":"Oct 24","t":"Thursday","hi":29,"low":16,"sunr":"6:04 AM","suns":"5:18 PM","part":{"d":{"icon":"30","t":"Partly Cloudy","wind":{"s":"11","gust":"N\\/A","d":"17","t":"NNE"},"bt":"P Cloudy","ppcp":"10","hmid":"50"},"n":{"icon":"29","t":"Partly Cloudy","wind":{"s":"10","gust":"N\\/A","d":"34","t":"NE"},"bt":"P Cloudy","ppcp":"10","hmid":"76"}}},{"dt":"Oct 25","t":"Friday","hi":28,"low":16,"sunr":"6:05 AM","suns":"5:17 PM","part":{"d":{"icon":"32","t":"Sunny","wind":{"s":"12","gust":"N\\/A","d":"29","t":"NNE"},"bt":"Sunny","ppcp":"0","hmid":"53"},"n":{"icon":"31","t":"Clear","wind":{"s":"9","gust":"N\\/A","d":"33","t":"NNE"},"bt":"Clear","ppcp":"0","hmid":"64"}}},{"dt":"Oct 26","t":"Saturday","hi":29,"low":15,"sunr":"6:05 AM","suns":"5:16 PM","part":{"d":{"icon":"32","t":"Sunny","wind":{"s":"13","gust":"N\\/A","d":"25","t":"NNE"},"bt":"Sunny","ppcp":"0","hmid":"47"},"n":{"icon":"31","t":"Clear","wind":{"s":"7","gust":"N\\/A","d":"11","t":"N"},"bt":"Clear","ppcp":"0","hmid":"68"}}},{"dt":"Oct 27","t":"Sunday","hi":27,"low":15,"sunr":"6:06 AM","suns":"5:15 PM","part":{"d":{"icon":"32","t":"Sunny","wind":{"s":"12","gust":"N\\/A","d":"353","t":"N"},"bt":"Sunny","ppcp":"0","hmid":"56"},"n":{"icon":"33","t":"Mostly Clear","wind":{"s":"7","gust":"N\\/A","d":"3","t":"N"},"bt":"M Clear","ppcp":"0","hmid":"69"}}}]'),
(5, 'EGXX0006', 20, 33, 'Fair', 'F', '6:03 AM', '5:16 PM', 20, '{"speed":"9","gust":"N\\/A","d":"30","from":"NNE"}', '{"r":"30.03","d":"steady"}', 68, 6, '{"i":"0","t":"Low"}', '{"icon":"19","t":"Waning Gibbous"}', '[{"dt":"Oct 23","t":"Wednesday","hi":-18,"low":16,"sunr":"6:02 AM","suns":"5:16 PM","part":{"d":{"icon":"44","t":"N\\/A","wind":{"s":"N\\/A","gust":"N\\/A","d":"N\\/A","t":"N\\/A"},"bt":"N\\/A","ppcp":"10","hmid":"N\\/A"},"n":{"icon":"33","t":"Mostly Clear","wind":{"s":"12","gust":"N\\/A","d":"24","t":"NNE"},"bt":"M Clear","ppcp":"10","hmid":"77"}}},{"dt":"Oct 24","t":"Thursday","hi":29,"low":16,"sunr":"6:03 AM","suns":"5:15 PM","part":{"d":{"icon":"30","t":"Partly Cloudy","wind":{"s":"11","gust":"N\\/A","d":"17","t":"NNE"},"bt":"P Cloudy","ppcp":"10","hmid":"50"},"n":{"icon":"29","t":"Partly Cloudy","wind":{"s":"10","gust":"N\\/A","d":"34","t":"NE"},"bt":"P Cloudy","ppcp":"10","hmid":"76"}}},{"dt":"Oct 25","t":"Friday","hi":28,"low":16,"sunr":"6:04 AM","suns":"5:14 PM","part":{"d":{"icon":"32","t":"Sunny","wind":{"s":"12","gust":"N\\/A","d":"29","t":"NNE"},"bt":"Sunny","ppcp":"0","hmid":"53"},"n":{"icon":"31","t":"Clear","wind":{"s":"9","gust":"N\\/A","d":"33","t":"NNE"},"bt":"Clear","ppcp":"0","hmid":"64"}}},{"dt":"Oct 26","t":"Saturday","hi":29,"low":15,"sunr":"6:05 AM","suns":"5:13 PM","part":{"d":{"icon":"32","t":"Sunny","wind":{"s":"13","gust":"N\\/A","d":"25","t":"NNE"},"bt":"Sunny","ppcp":"0","hmid":"47"},"n":{"icon":"31","t":"Clear","wind":{"s":"7","gust":"N\\/A","d":"11","t":"N"},"bt":"Clear","ppcp":"0","hmid":"68"}}},{"dt":"Oct 27","t":"Sunday","hi":27,"low":15,"sunr":"6:05 AM","suns":"5:12 PM","part":{"d":{"icon":"32","t":"Sunny","wind":{"s":"12","gust":"N\\/A","d":"353","t":"N"},"bt":"Sunny","ppcp":"0","hmid":"56"},"n":{"icon":"33","t":"Mostly Clear","wind":{"s":"7","gust":"N\\/A","d":"3","t":"N"},"bt":"M Clear","ppcp":"0","hmid":"69"}}}]'),
(6, 'EGXX0008', 24, 33, 'Fair', 'F', '5:50 AM', '5:09 PM', 24, '{"speed":"13","gust":"N\\/A","d":"300","from":"WNW"}', '{"r":"29.91","d":"steady"}', 47, 6, '{"i":"0","t":"Low"}', '{"icon":"19","t":"Waning Gibbous"}', '[{"dt":"Oct 23","t":"Wednesday","hi":-18,"low":21,"sunr":"5:49 AM","suns":"5:09 PM","part":{"d":{"icon":"44","t":"N\\/A","wind":{"s":"N\\/A","gust":"N\\/A","d":"N\\/A","t":"N\\/A"},"bt":"N\\/A","ppcp":"0","hmid":"N\\/A"},"n":{"icon":"29","t":"Partly Cloudy","wind":{"s":"11","gust":"N\\/A","d":"308","t":"NW"},"bt":"P Cloudy","ppcp":"0","hmid":"49"}}},{"dt":"Oct 24","t":"Thursday","hi":32,"low":20,"sunr":"5:50 AM","suns":"5:08 PM","part":{"d":{"icon":"28","t":"Mostly Cloudy","wind":{"s":"16","gust":"N\\/A","d":"349","t":"N"},"bt":"M Cloudy","ppcp":"0","hmid":"42"},"n":{"icon":"29","t":"Partly Cloudy","wind":{"s":"11","gust":"N\\/A","d":"317","t":"NW"},"bt":"P Cloudy","ppcp":"0","hmid":"47"}}},{"dt":"Oct 25","t":"Friday","hi":32,"low":19,"sunr":"5:51 AM","suns":"5:07 PM","part":{"d":{"icon":"32","t":"Sunny","wind":{"s":"16","gust":"N\\/A","d":"346","t":"NNW"},"bt":"Sunny","ppcp":"0","hmid":"37"},"n":{"icon":"31","t":"Clear","wind":{"s":"11","gust":"N\\/A","d":"321","t":"NW"},"bt":"Clear","ppcp":"0","hmid":"40"}}},{"dt":"Oct 26","t":"Saturday","hi":31,"low":18,"sunr":"5:51 AM","suns":"5:06 PM","part":{"d":{"icon":"32","t":"Sunny","wind":{"s":"17","gust":"N\\/A","d":"334","t":"NNW"},"bt":"Sunny","ppcp":"0","hmid":"37"},"n":{"icon":"31","t":"Clear","wind":{"s":"12","gust":"N\\/A","d":"314","t":"NW"},"bt":"Clear","ppcp":"0","hmid":"48"}}},{"dt":"Oct 27","t":"Sunday","hi":31,"low":18,"sunr":"5:52 AM","suns":"5:05 PM","part":{"d":{"icon":"32","t":"Sunny","wind":{"s":"15","gust":"N\\/A","d":"335","t":"NNW"},"bt":"Sunny","ppcp":"0","hmid":"43"},"n":{"icon":"31","t":"Clear","wind":{"s":"10","gust":"N\\/A","d":"317","t":"NW"},"bt":"Clear","ppcp":"0","hmid":"51"}}}]'),
(7, 'EGXX0009', 22, 33, 'Fair', 'F', '6:00 AM', '5:11 PM', 22, '{"speed":"9","gust":"N\\/A","d":"360","from":"N"}', '{"r":"30.00","d":"steady"}', 60, 6, '{"i":"0","t":"Low"}', '{"icon":"19","t":"Waning Gibbous"}', '[{"dt":"Oct 23","t":"Wednesday","hi":-18,"low":16,"sunr":"5:59 AM","suns":"5:11 PM","part":{"d":{"icon":"44","t":"N\\/A","wind":{"s":"N\\/A","gust":"N\\/A","d":"N\\/A","t":"N\\/A"},"bt":"N\\/A","ppcp":"0","hmid":"N\\/A"},"n":{"icon":"29","t":"Partly Cloudy","wind":{"s":"5","gust":"N\\/A","d":"11","t":"N"},"bt":"P Cloudy","ppcp":"0","hmid":"81"}}},{"dt":"Oct 24","t":"Thursday","hi":29,"low":15,"sunr":"6:00 AM","suns":"5:10 PM","part":{"d":{"icon":"34","t":"Mostly Sunny","wind":{"s":"12","gust":"N\\/A","d":"21","t":"NNE"},"bt":"M Sunny","ppcp":"0","hmid":"51"},"n":{"icon":"33","t":"Mostly Clear","wind":{"s":"9","gust":"N\\/A","d":"49","t":"NE"},"bt":"M Clear","ppcp":"10","hmid":"87"}}},{"dt":"Oct 25","t":"Friday","hi":28,"low":16,"sunr":"6:00 AM","suns":"5:10 PM","part":{"d":{"icon":"32","t":"Sunny","wind":{"s":"13","gust":"N\\/A","d":"35","t":"NE"},"bt":"Sunny","ppcp":"10","hmid":"52"},"n":{"icon":"31","t":"Clear","wind":{"s":"7","gust":"N\\/A","d":"29","t":"NNE"},"bt":"Clear","ppcp":"0","hmid":"66"}}},{"dt":"Oct 26","t":"Saturday","hi":28,"low":15,"sunr":"6:01 AM","suns":"5:09 PM","part":{"d":{"icon":"32","t":"Sunny","wind":{"s":"14","gust":"N\\/A","d":"20","t":"NNE"},"bt":"Sunny","ppcp":"0","hmid":"52"},"n":{"icon":"31","t":"Clear","wind":{"s":"6","gust":"N\\/A","d":"359","t":"N"},"bt":"Clear","ppcp":"0","hmid":"72"}}},{"dt":"Oct 27","t":"Sunday","hi":28,"low":15,"sunr":"6:02 AM","suns":"5:08 PM","part":{"d":{"icon":"32","t":"Sunny","wind":{"s":"9","gust":"N\\/A","d":"355","t":"N"},"bt":"Sunny","ppcp":"0","hmid":"58"},"n":{"icon":"33","t":"Mostly Clear","wind":{"s":"6","gust":"N\\/A","d":"321","t":"NW"},"bt":"M Clear","ppcp":"0","hmid":"76"}}}]'),
(8, 'EGXX0011', 25, 33, 'Fair', 'F', '5:53 AM', '5:15 PM', 25, '{"speed":"7","gust":"N\\/A","d":"130","from":"SE"}', '{"r":"29.91","d":"rising"}', 50, 6, '{"i":"0","t":"Low"}', '{"icon":"19","t":"Waning Gibbous"}', '[{"dt":"Oct 23","t":"Wednesday","hi":-18,"low":18,"sunr":"5:52 AM","suns":"5:15 PM","part":{"d":{"icon":"44","t":"N\\/A","wind":{"s":"N\\/A","gust":"N\\/A","d":"N\\/A","t":"N\\/A"},"bt":"N\\/A","ppcp":"0","hmid":"N\\/A"},"n":{"icon":"29","t":"Partly Cloudy","wind":{"s":"3","gust":"N\\/A","d":"227","t":"SW"},"bt":"P Cloudy","ppcp":"0","hmid":"51"}}},{"dt":"Oct 24","t":"Thursday","hi":35,"low":18,"sunr":"5:53 AM","suns":"5:14 PM","part":{"d":{"icon":"34","t":"Mostly Sunny","wind":{"s":"5","gust":"N\\/A","d":"321","t":"NW"},"bt":"M Sunny","ppcp":"0","hmid":"37"},"n":{"icon":"31","t":"Clear","wind":{"s":"4","gust":"N\\/A","d":"285","t":"WNW"},"bt":"Clear","ppcp":"0","hmid":"48"}}},{"dt":"Oct 25","t":"Friday","hi":34,"low":18,"sunr":"5:54 AM","suns":"5:13 PM","part":{"d":{"icon":"32","t":"Sunny","wind":{"s":"4","gust":"N\\/A","d":"334","t":"NNW"},"bt":"Sunny","ppcp":"0","hmid":"34"},"n":{"icon":"31","t":"Clear","wind":{"s":"2","gust":"N\\/A","d":"9","t":"N"},"bt":"Clear","ppcp":"0","hmid":"35"}}},{"dt":"Oct 26","t":"Saturday","hi":33,"low":16,"sunr":"5:54 AM","suns":"5:13 PM","part":{"d":{"icon":"32","t":"Sunny","wind":{"s":"5","gust":"N\\/A","d":"338","t":"NNW"},"bt":"Sunny","ppcp":"0","hmid":"25"},"n":{"icon":"31","t":"Clear","wind":{"s":"3","gust":"N\\/A","d":"316","t":"NW"},"bt":"Clear","ppcp":"0","hmid":"36"}}},{"dt":"Oct 27","t":"Sunday","hi":31,"low":15,"sunr":"5:55 AM","suns":"5:12 PM","part":{"d":{"icon":"32","t":"Sunny","wind":{"s":"7","gust":"N\\/A","d":"305","t":"NW"},"bt":"Sunny","ppcp":"0","hmid":"34"},"n":{"icon":"31","t":"Clear","wind":{"s":"4","gust":"N\\/A","d":"284","t":"WNW"},"bt":"Clear","ppcp":"0","hmid":"45"}}}]'),
(9, 'EGXX0012', 21, 29, 'Partly Cloudy', 'F', '6:20 AM', '5:31 PM', 21, '{"speed":"6","gust":"N\\/A","d":"350","from":"N"}', '{"r":"30.06","d":"falling"}', 73, 6, '{"i":"0","t":"Low"}', '{"icon":"19","t":"Waning Gibbous"}', '[{"dt":"Oct 23","t":"Wednesday","hi":-18,"low":18,"sunr":"6:20 AM","suns":"5:31 PM","part":{"d":{"icon":"44","t":"N\\/A","wind":{"s":"N\\/A","gust":"N\\/A","d":"N\\/A","t":"N\\/A"},"bt":"N\\/A","ppcp":"0","hmid":"N\\/A"},"n":{"icon":"27","t":"Mostly Cloudy","wind":{"s":"10","gust":"N\\/A","d":"355","t":"N"},"bt":"M Cloudy","ppcp":"0","hmid":"73"}}},{"dt":"Oct 24","t":"Thursday","hi":25,"low":19,"sunr":"6:20 AM","suns":"5:30 PM","part":{"d":{"icon":"30","t":"Partly Cloudy","wind":{"s":"13","gust":"N\\/A","d":"351","t":"N"},"bt":"P Cloudy","ppcp":"0","hmid":"72"},"n":{"icon":"29","t":"Partly Cloudy","wind":{"s":"13","gust":"N\\/A","d":"7","t":"N"},"bt":"P Cloudy","ppcp":"10","hmid":"81"}}},{"dt":"Oct 25","t":"Friday","hi":24,"low":19,"sunr":"6:21 AM","suns":"5:29 PM","part":{"d":{"icon":"30","t":"Partly Cloudy","wind":{"s":"12","gust":"N\\/A","d":"21","t":"NNE"},"bt":"P Cloudy","ppcp":"10","hmid":"72"},"n":{"icon":"29","t":"Partly Cloudy","wind":{"s":"9","gust":"N\\/A","d":"33","t":"NNE"},"bt":"P Cloudy","ppcp":"0","hmid":"78"}}},{"dt":"Oct 26","t":"Saturday","hi":23,"low":18,"sunr":"6:22 AM","suns":"5:28 PM","part":{"d":{"icon":"34","t":"Mostly Sunny","wind":{"s":"12","gust":"N\\/A","d":"21","t":"NNE"},"bt":"M Sunny","ppcp":"0","hmid":"69"},"n":{"icon":"33","t":"Mostly Clear","wind":{"s":"9","gust":"N\\/A","d":"354","t":"N"},"bt":"M Clear","ppcp":"0","hmid":"73"}}},{"dt":"Oct 27","t":"Sunday","hi":23,"low":18,"sunr":"6:23 AM","suns":"5:27 PM","part":{"d":{"icon":"34","t":"Mostly Sunny","wind":{"s":"13","gust":"N\\/A","d":"330","t":"NNW"},"bt":"M Sunny","ppcp":"0","hmid":"63"},"n":{"icon":"33","t":"Mostly Clear","wind":{"s":"8","gust":"N\\/A","d":"313","t":"NW"},"bt":"M Clear","ppcp":"0","hmid":"67"}}}]'),
(10, 'EGXX0014', 22, 31, 'Clear', 'F', '5:58 AM', '5:11 PM', 22, '{"speed":"9","gust":"N\\/A","d":"330","from":"NNW"}', '{"r":"N\\/A","d":"N\\/A"}', 69, 6, '{"i":"0","t":"Low"}', '{"icon":"19","t":"Waning Gibbous"}', '[{"dt":"Oct 23","t":"Wednesday","hi":-18,"low":14,"sunr":"5:57 AM","suns":"5:11 PM","part":{"d":{"icon":"44","t":"N\\/A","wind":{"s":"N\\/A","gust":"N\\/A","d":"N\\/A","t":"N\\/A"},"bt":"N\\/A","ppcp":"0","hmid":"N\\/A"},"n":{"icon":"29","t":"Partly Cloudy","wind":{"s":"8","gust":"N\\/A","d":"16","t":"NNE"},"bt":"P Cloudy","ppcp":"0","hmid":"80"}}},{"dt":"Oct 24","t":"Thursday","hi":28,"low":13,"sunr":"5:58 AM","suns":"5:10 PM","part":{"d":{"icon":"34","t":"Mostly Sunny","wind":{"s":"12","gust":"N\\/A","d":"9","t":"N"},"bt":"M Sunny","ppcp":"0","hmid":"52"},"n":{"icon":"31","t":"Clear","wind":{"s":"11","gust":"N\\/A","d":"14","t":"NNE"},"bt":"Clear","ppcp":"0","hmid":"80"}}},{"dt":"Oct 25","t":"Friday","hi":28,"low":13,"sunr":"5:58 AM","suns":"5:09 PM","part":{"d":{"icon":"32","t":"Sunny","wind":{"s":"14","gust":"N\\/A","d":"15","t":"NNE"},"bt":"Sunny","ppcp":"0","hmid":"50"},"n":{"icon":"31","t":"Clear","wind":{"s":"8","gust":"N\\/A","d":"20","t":"NNE"},"bt":"Clear","ppcp":"0","hmid":"70"}}},{"dt":"Oct 26","t":"Saturday","hi":27,"low":13,"sunr":"5:59 AM","suns":"5:08 PM","part":{"d":{"icon":"32","t":"Sunny","wind":{"s":"15","gust":"N\\/A","d":"14","t":"NNE"},"bt":"Sunny","ppcp":"0","hmid":"56"},"n":{"icon":"31","t":"Clear","wind":{"s":"8","gust":"N\\/A","d":"11","t":"N"},"bt":"Clear","ppcp":"0","hmid":"74"}}},{"dt":"Oct 27","t":"Sunday","hi":27,"low":14,"sunr":"6:00 AM","suns":"5:07 PM","part":{"d":{"icon":"32","t":"Sunny","wind":{"s":"12","gust":"N\\/A","d":"10","t":"N"},"bt":"Sunny","ppcp":"0","hmid":"58"},"n":{"icon":"33","t":"Mostly Clear","wind":{"s":"8","gust":"N\\/A","d":"16","t":"NNE"},"bt":"M Clear","ppcp":"0","hmid":"78"}}}]'),
(11, 'EGXX0015', 24, 31, 'Clear', 'F', '5:53 AM', '5:14 PM', 24, '{"speed":"8","gust":"N\\/A","d":"250","from":"WSW"}', '{"r":"N\\/A","d":"N\\/A"}', 45, 6, '{"i":"0","t":"Low"}', '{"icon":"19","t":"Waning Gibbous"}', '[{"dt":"Oct 23","t":"Wednesday","hi":-18,"low":18,"sunr":"5:53 AM","suns":"5:14 PM","part":{"d":{"icon":"44","t":"N\\/A","wind":{"s":"N\\/A","gust":"N\\/A","d":"N\\/A","t":"N\\/A"},"bt":"N\\/A","ppcp":"0","hmid":"N\\/A"},"n":{"icon":"29","t":"Partly Cloudy","wind":{"s":"6","gust":"N\\/A","d":"244","t":"WSW"},"bt":"P Cloudy","ppcp":"0","hmid":"48"}}},{"dt":"Oct 24","t":"Thursday","hi":34,"low":17,"sunr":"5:53 AM","suns":"5:13 PM","part":{"d":{"icon":"30","t":"Partly Cloudy","wind":{"s":"9","gust":"N\\/A","d":"305","t":"NW"},"bt":"P Cloudy","ppcp":"0","hmid":"31"},"n":{"icon":"33","t":"Mostly Clear","wind":{"s":"8","gust":"N\\/A","d":"316","t":"NW"},"bt":"M Clear","ppcp":"0","hmid":"40"}}},{"dt":"Oct 25","t":"Friday","hi":33,"low":16,"sunr":"5:54 AM","suns":"5:13 PM","part":{"d":{"icon":"32","t":"Sunny","wind":{"s":"8","gust":"N\\/A","d":"315","t":"NW"},"bt":"Sunny","ppcp":"0","hmid":"28"},"n":{"icon":"31","t":"Clear","wind":{"s":"6","gust":"N\\/A","d":"341","t":"NNW"},"bt":"Clear","ppcp":"0","hmid":"33"}}},{"dt":"Oct 26","t":"Saturday","hi":32,"low":15,"sunr":"5:54 AM","suns":"5:12 PM","part":{"d":{"icon":"32","t":"Sunny","wind":{"s":"7","gust":"N\\/A","d":"330","t":"NNW"},"bt":"Sunny","ppcp":"0","hmid":"23"},"n":{"icon":"31","t":"Clear","wind":{"s":"5","gust":"N\\/A","d":"332","t":"NNW"},"bt":"Clear","ppcp":"0","hmid":"35"}}},{"dt":"Oct 27","t":"Sunday","hi":31,"low":14,"sunr":"5:55 AM","suns":"5:11 PM","part":{"d":{"icon":"32","t":"Sunny","wind":{"s":"9","gust":"N\\/A","d":"310","t":"NW"},"bt":"Sunny","ppcp":"0","hmid":"28"},"n":{"icon":"31","t":"Clear","wind":{"s":"6","gust":"N\\/A","d":"310","t":"NW"},"bt":"Clear","ppcp":"0","hmid":"38"}}}]'),
(12, 'EGXX0016', 24, 31, 'Clear', 'F', '5:47 AM', '5:08 PM', 26, '{"speed":"calm","gust":"N\\/A","d":"0","from":"CALM"}', '{"r":"N\\/A","d":"N\\/A"}', 59, 6, '{"i":"0","t":"Low"}', '{"icon":"19","t":"Waning Gibbous"}', '[{"dt":"Oct 23","t":"Wednesday","hi":-18,"low":22,"sunr":"5:46 AM","suns":"5:08 PM","part":{"d":{"icon":"44","t":"N\\/A","wind":{"s":"N\\/A","gust":"N\\/A","d":"N\\/A","t":"N\\/A"},"bt":"N\\/A","ppcp":"0","hmid":"N\\/A"},"n":{"icon":"29","t":"Partly Cloudy","wind":{"s":"11","gust":"N\\/A","d":"322","t":"NW"},"bt":"P Cloudy","ppcp":"0","hmid":"50"}}},{"dt":"Oct 24","t":"Thursday","hi":30,"low":21,"sunr":"5:47 AM","suns":"5:07 PM","part":{"d":{"icon":"30","t":"Partly Cloudy","wind":{"s":"12","gust":"N\\/A","d":"2","t":"N"},"bt":"P Cloudy","ppcp":"0","hmid":"48"},"n":{"icon":"29","t":"Partly Cloudy","wind":{"s":"9","gust":"N\\/A","d":"323","t":"NW"},"bt":"P Cloudy","ppcp":"0","hmid":"53"}}},{"dt":"Oct 25","t":"Friday","hi":29,"low":21,"sunr":"5:48 AM","suns":"5:06 PM","part":{"d":{"icon":"32","t":"Sunny","wind":{"s":"13","gust":"N\\/A","d":"360","t":"N"},"bt":"Sunny","ppcp":"0","hmid":"42"},"n":{"icon":"31","t":"Clear","wind":{"s":"10","gust":"N\\/A","d":"329","t":"NNW"},"bt":"Clear","ppcp":"0","hmid":"47"}}},{"dt":"Oct 26","t":"Saturday","hi":29,"low":20,"sunr":"5:48 AM","suns":"5:05 PM","part":{"d":{"icon":"32","t":"Sunny","wind":{"s":"15","gust":"N\\/A","d":"347","t":"NNW"},"bt":"Sunny","ppcp":"0","hmid":"43"},"n":{"icon":"31","t":"Clear","wind":{"s":"10","gust":"N\\/A","d":"328","t":"NNW"},"bt":"Clear","ppcp":"0","hmid":"45"}}},{"dt":"Oct 27","t":"Sunday","hi":29,"low":19,"sunr":"5:49 AM","suns":"5:05 PM","part":{"d":{"icon":"32","t":"Sunny","wind":{"s":"12","gust":"N\\/A","d":"343","t":"NNW"},"bt":"Sunny","ppcp":"0","hmid":"45"},"n":{"icon":"31","t":"Clear","wind":{"s":"8","gust":"N\\/A","d":"319","t":"NW"},"bt":"Clear","ppcp":"0","hmid":"49"}}}]'),
(13, 'EGXX0017', 24, 33, 'Fair', 'F', '5:49 AM', '5:09 PM', 24, '{"speed":"13","gust":"N\\/A","d":"300","from":"WNW"}', '{"r":"29.91","d":"steady"}', 47, 6, '{"i":"0","t":"Low"}', '{"icon":"19","t":"Waning Gibbous"}', '[{"dt":"Oct 23","t":"Wednesday","hi":-18,"low":22,"sunr":"5:48 AM","suns":"5:09 PM","part":{"d":{"icon":"44","t":"N\\/A","wind":{"s":"N\\/A","gust":"N\\/A","d":"N\\/A","t":"N\\/A"},"bt":"N\\/A","ppcp":"0","hmid":"N\\/A"},"n":{"icon":"29","t":"Partly Cloudy","wind":{"s":"12","gust":"N\\/A","d":"319","t":"NW"},"bt":"P Cloudy","ppcp":"0","hmid":"45"}}},{"dt":"Oct 24","t":"Thursday","hi":29,"low":21,"sunr":"5:49 AM","suns":"5:08 PM","part":{"d":{"icon":"30","t":"Partly Cloudy","wind":{"s":"15","gust":"N\\/A","d":"358","t":"N"},"bt":"P Cloudy","ppcp":"0","hmid":"43"},"n":{"icon":"29","t":"Partly Cloudy","wind":{"s":"12","gust":"N\\/A","d":"331","t":"NNW"},"bt":"P Cloudy","ppcp":"0","hmid":"36"}}},{"dt":"Oct 25","t":"Friday","hi":29,"low":21,"sunr":"5:50 AM","suns":"5:07 PM","part":{"d":{"icon":"32","t":"Sunny","wind":{"s":"15","gust":"N\\/A","d":"360","t":"N"},"bt":"Sunny","ppcp":"0","hmid":"38"},"n":{"icon":"31","t":"Clear","wind":{"s":"14","gust":"N\\/A","d":"335","t":"NNW"},"bt":"Clear","ppcp":"0","hmid":"32"}}},{"dt":"Oct 26","t":"Saturday","hi":29,"low":20,"sunr":"5:50 AM","suns":"5:06 PM","part":{"d":{"icon":"32","t":"Sunny","wind":{"s":"18","gust":"N\\/A","d":"347","t":"NNW"},"bt":"Sunny","ppcp":"0","hmid":"32"},"n":{"icon":"31","t":"Clear","wind":{"s":"13","gust":"N\\/A","d":"331","t":"NNW"},"bt":"Clear","ppcp":"0","hmid":"36"}}},{"dt":"Oct 27","t":"Sunday","hi":28,"low":19,"sunr":"5:51 AM","suns":"5:05 PM","part":{"d":{"icon":"32","t":"Sunny","wind":{"s":"14","gust":"N\\/A","d":"348","t":"NNW"},"bt":"Sunny","ppcp":"0","hmid":"38"},"n":{"icon":"31","t":"Clear","wind":{"s":"12","gust":"N\\/A","d":"327","t":"NNW"},"bt":"Clear","ppcp":"0","hmid":"40"}}}]'),
(14, 'EGXX0018', 27, 33, 'Fair', 'F', '5:48 AM', '5:06 PM', 27, '{"speed":"8","gust":"N\\/A","d":"350","from":"N"}', '{"r":"29.88","d":"steady"}', 37, 6, '{"i":"0","t":"Low"}', '{"icon":"19","t":"Waning Gibbous"}', '[{"dt":"Oct 23","t":"Wednesday","hi":-18,"low":23,"sunr":"5:48 AM","suns":"5:06 PM","part":{"d":{"icon":"44","t":"N\\/A","wind":{"s":"N\\/A","gust":"N\\/A","d":"N\\/A","t":"N\\/A"},"bt":"N\\/A","ppcp":"0","hmid":"N\\/A"},"n":{"icon":"29","t":"Partly Cloudy","wind":{"s":"9","gust":"N\\/A","d":"353","t":"N"},"bt":"P Cloudy","ppcp":"0","hmid":"44"}}},{"dt":"Oct 24","t":"Thursday","hi":34,"low":22,"sunr":"5:48 AM","suns":"5:05 PM","part":{"d":{"icon":"28","t":"Mostly Cloudy","wind":{"s":"8","gust":"N\\/A","d":"13","t":"NNE"},"bt":"M Cloudy","ppcp":"0","hmid":"38"},"n":{"icon":"29","t":"Partly Cloudy","wind":{"s":"11","gust":"N\\/A","d":"357","t":"N"},"bt":"P Cloudy","ppcp":"0","hmid":"40"}}},{"dt":"Oct 25","t":"Friday","hi":33,"low":21,"sunr":"5:49 AM","suns":"5:04 PM","part":{"d":{"icon":"32","t":"Sunny","wind":{"s":"12","gust":"N\\/A","d":"13","t":"NNE"},"bt":"Sunny","ppcp":"0","hmid":"34"},"n":{"icon":"31","t":"Clear","wind":{"s":"12","gust":"N\\/A","d":"359","t":"N"},"bt":"Clear","ppcp":"0","hmid":"38"}}},{"dt":"Oct 26","t":"Saturday","hi":33,"low":21,"sunr":"5:50 AM","suns":"5:03 PM","part":{"d":{"icon":"32","t":"Sunny","wind":{"s":"12","gust":"N\\/A","d":"8","t":"N"},"bt":"Sunny","ppcp":"0","hmid":"32"},"n":{"icon":"31","t":"Clear","wind":{"s":"12","gust":"N\\/A","d":"352","t":"N"},"bt":"Clear","ppcp":"0","hmid":"34"}}},{"dt":"Oct 27","t":"Sunday","hi":31,"low":20,"sunr":"5:50 AM","suns":"5:02 PM","part":{"d":{"icon":"32","t":"Sunny","wind":{"s":"11","gust":"N\\/A","d":"5","t":"N"},"bt":"Sunny","ppcp":"0","hmid":"31"},"n":{"icon":"31","t":"Clear","wind":{"s":"9","gust":"N\\/A","d":"348","t":"NNW"},"bt":"Clear","ppcp":"0","hmid":"37"}}}]'),
(15, 'EGXX0022', 22, 31, 'Clear', 'F', '5:58 AM', '5:12 PM', 22, '{"speed":"9","gust":"N\\/A","d":"330","from":"NNW"}', '{"r":"N\\/A","d":"N\\/A"}', 69, 6, '{"i":"0","t":"Low"}', '{"icon":"19","t":"Waning Gibbous"}', '[{"dt":"Oct 23","t":"Wednesday","hi":-18,"low":14,"sunr":"5:58 AM","suns":"5:12 PM","part":{"d":{"icon":"44","t":"N\\/A","wind":{"s":"N\\/A","gust":"N\\/A","d":"N\\/A","t":"N\\/A"},"bt":"N\\/A","ppcp":"0","hmid":"N\\/A"},"n":{"icon":"29","t":"Partly Cloudy","wind":{"s":"6","gust":"N\\/A","d":"356","t":"N"},"bt":"P Cloudy","ppcp":"0","hmid":"67"}}},{"dt":"Oct 24","t":"Thursday","hi":26,"low":14,"sunr":"5:58 AM","suns":"5:11 PM","part":{"d":{"icon":"30","t":"Partly Cloudy","wind":{"s":"9","gust":"N\\/A","d":"357","t":"N"},"bt":"P Cloudy","ppcp":"0","hmid":"47"},"n":{"icon":"29","t":"Partly Cloudy","wind":{"s":"9","gust":"N\\/A","d":"352","t":"N"},"bt":"P Cloudy","ppcp":"0","hmid":"63"}}},{"dt":"Oct 25","t":"Friday","hi":25,"low":13,"sunr":"5:59 AM","suns":"5:10 PM","part":{"d":{"icon":"32","t":"Sunny","wind":{"s":"11","gust":"N\\/A","d":"6","t":"N"},"bt":"Sunny","ppcp":"0","hmid":"43"},"n":{"icon":"31","t":"Clear","wind":{"s":"7","gust":"N\\/A","d":"1","t":"N"},"bt":"Clear","ppcp":"0","hmid":"63"}}},{"dt":"Oct 26","t":"Saturday","hi":24,"low":13,"sunr":"6:00 AM","suns":"5:09 PM","part":{"d":{"icon":"32","t":"Sunny","wind":{"s":"11","gust":"N\\/A","d":"5","t":"N"},"bt":"Sunny","ppcp":"0","hmid":"54"},"n":{"icon":"31","t":"Clear","wind":{"s":"7","gust":"N\\/A","d":"354","t":"N"},"bt":"Clear","ppcp":"0","hmid":"69"}}},{"dt":"Oct 27","t":"Sunday","hi":23,"low":13,"sunr":"6:00 AM","suns":"5:09 PM","part":{"d":{"icon":"32","t":"Sunny","wind":{"s":"8","gust":"N\\/A","d":"3","t":"N"},"bt":"Sunny","ppcp":"0","hmid":"54"},"n":{"icon":"31","t":"Clear","wind":{"s":"6","gust":"N\\/A","d":"340","t":"NNW"},"bt":"Clear","ppcp":"0","hmid":"72"}}}]'),
(16, 'EGXX0024', 20, 33, 'Fair', 'F', '6:03 AM', '5:15 PM', 20, '{"speed":"9","gust":"N\\/A","d":"30","from":"NNE"}', '{"r":"30.03","d":"steady"}', 68, 6, '{"i":"0","t":"Low"}', '{"icon":"19","t":"Waning Gibbous"}', '[{"dt":"Oct 23","t":"Wednesday","hi":-18,"low":16,"sunr":"6:02 AM","suns":"5:15 PM","part":{"d":{"icon":"44","t":"N\\/A","wind":{"s":"N\\/A","gust":"N\\/A","d":"N\\/A","t":"N\\/A"},"bt":"N\\/A","ppcp":"0","hmid":"N\\/A"},"n":{"icon":"33","t":"Mostly Clear","wind":{"s":"10","gust":"N\\/A","d":"15","t":"NNE"},"bt":"M Clear","ppcp":"0","hmid":"78"}}},{"dt":"Oct 24","t":"Thursday","hi":30,"low":16,"sunr":"6:03 AM","suns":"5:14 PM","part":{"d":{"icon":"34","t":"Mostly Sunny","wind":{"s":"10","gust":"N\\/A","d":"16","t":"NNE"},"bt":"M Sunny","ppcp":"0","hmid":"48"},"n":{"icon":"33","t":"Mostly Clear","wind":{"s":"9","gust":"N\\/A","d":"35","t":"NE"},"bt":"M Clear","ppcp":"10","hmid":"85"}}},{"dt":"Oct 25","t":"Friday","hi":29,"low":16,"sunr":"6:03 AM","suns":"5:13 PM","part":{"d":{"icon":"32","t":"Sunny","wind":{"s":"12","gust":"N\\/A","d":"34","t":"NE"},"bt":"Sunny","ppcp":"0","hmid":"50"},"n":{"icon":"31","t":"Clear","wind":{"s":"7","gust":"N\\/A","d":"25","t":"NNE"},"bt":"Clear","ppcp":"0","hmid":"65"}}},{"dt":"Oct 26","t":"Saturday","hi":29,"low":16,"sunr":"6:04 AM","suns":"5:12 PM","part":{"d":{"icon":"32","t":"Sunny","wind":{"s":"11","gust":"N\\/A","d":"21","t":"NNE"},"bt":"Sunny","ppcp":"0","hmid":"47"},"n":{"icon":"31","t":"Clear","wind":{"s":"5","gust":"N\\/A","d":"357","t":"N"},"bt":"Clear","ppcp":"0","hmid":"70"}}},{"dt":"Oct 27","t":"Sunday","hi":28,"low":16,"sunr":"6:05 AM","suns":"5:11 PM","part":{"d":{"icon":"32","t":"Sunny","wind":{"s":"9","gust":"N\\/A","d":"342","t":"NNW"},"bt":"Sunny","ppcp":"0","hmid":"56"},"n":{"icon":"33","t":"Mostly Clear","wind":{"s":"5","gust":"N\\/A","d":"328","t":"NNW"},"bt":"M Clear","ppcp":"0","hmid":"73"}}}]'),
(17, 'EGXX0026', 22, 29, 'Partly Cloudy', 'F', '6:29 AM', '5:39 PM', 22, '{"speed":"12","gust":"N\\/A","d":"330","from":"NNW"}', '{"r":"N\\/A","d":"N\\/A"}', 78, 6, '{"i":"0","t":"Low"}', '{"icon":"19","t":"Waning Gibbous"}', '[{"dt":"Oct 23","t":"Wednesday","hi":-18,"low":14,"sunr":"6:28 AM","suns":"5:39 PM","part":{"d":{"icon":"44","t":"N\\/A","wind":{"s":"N\\/A","gust":"N\\/A","d":"N\\/A","t":"N\\/A"},"bt":"N\\/A","ppcp":"0","hmid":"N\\/A"},"n":{"icon":"29","t":"Partly Cloudy","wind":{"s":"7","gust":"N\\/A","d":"326","t":"NW"},"bt":"P Cloudy","ppcp":"0","hmid":"85"}}},{"dt":"Oct 24","t":"Thursday","hi":26,"low":16,"sunr":"6:29 AM","suns":"5:38 PM","part":{"d":{"icon":"28","t":"Mostly Cloudy","wind":{"s":"13","gust":"N\\/A","d":"1","t":"N"},"bt":"M Cloudy","ppcp":"0","hmid":"62"},"n":{"icon":"29","t":"Partly Cloudy","wind":{"s":"10","gust":"N\\/A","d":"16","t":"NNE"},"bt":"P Cloudy","ppcp":"10","hmid":"84"}}},{"dt":"Oct 25","t":"Friday","hi":23,"low":17,"sunr":"6:30 AM","suns":"5:37 PM","part":{"d":{"icon":"34","t":"Mostly Sunny","wind":{"s":"10","gust":"N\\/A","d":"31","t":"NNE"},"bt":"M Sunny","ppcp":"10","hmid":"69"},"n":{"icon":"33","t":"Mostly Clear","wind":{"s":"7","gust":"N\\/A","d":"45","t":"NE"},"bt":"M Clear","ppcp":"0","hmid":"80"}}},{"dt":"Oct 26","t":"Saturday","hi":24,"low":15,"sunr":"6:30 AM","suns":"5:36 PM","part":{"d":{"icon":"30","t":"Partly Cloudy","wind":{"s":"10","gust":"N\\/A","d":"46","t":"NE"},"bt":"P Cloudy","ppcp":"0","hmid":"68"},"n":{"icon":"33","t":"Mostly Clear","wind":{"s":"6","gust":"N\\/A","d":"328","t":"NNW"},"bt":"M Clear","ppcp":"0","hmid":"79"}}},{"dt":"Oct 27","t":"Sunday","hi":24,"low":14,"sunr":"6:31 AM","suns":"5:35 PM","part":{"d":{"icon":"32","t":"Sunny","wind":{"s":"9","gust":"N\\/A","d":"313","t":"NW"},"bt":"Sunny","ppcp":"0","hmid":"62"},"n":{"icon":"31","t":"Clear","wind":{"s":"7","gust":"N\\/A","d":"297","t":"WNW"},"bt":"Clear","ppcp":"0","hmid":"75"}}}]'),
(18, 'EGXX0028', 22, 33, 'Fair', 'F', '6:00 AM', '5:11 PM', 22, '{"speed":"9","gust":"N\\/A","d":"360","from":"N"}', '{"r":"30.00","d":"steady"}', 60, 6, '{"i":"0","t":"Low"}', '{"icon":"19","t":"Waning Gibbous"}', '[{"dt":"Oct 23","t":"Wednesday","hi":-18,"low":21,"sunr":"5:59 AM","suns":"5:11 PM","part":{"d":{"icon":"44","t":"N\\/A","wind":{"s":"N\\/A","gust":"N\\/A","d":"N\\/A","t":"N\\/A"},"bt":"N\\/A","ppcp":"0","hmid":"N\\/A"},"n":{"icon":"29","t":"Partly Cloudy","wind":{"s":"10","gust":"N\\/A","d":"13","t":"NNE"},"bt":"P Cloudy","ppcp":"0","hmid":"72"}}},{"dt":"Oct 24","t":"Thursday","hi":24,"low":22,"sunr":"6:00 AM","suns":"5:10 PM","part":{"d":{"icon":"32","t":"Sunny","wind":{"s":"11","gust":"N\\/A","d":"8","t":"N"},"bt":"Sunny","ppcp":"0","hmid":"68"},"n":{"icon":"29","t":"Partly Cloudy","wind":{"s":"11","gust":"N\\/A","d":"36","t":"NE"},"bt":"P Cloudy","ppcp":"0","hmid":"74"}}},{"dt":"Oct 25","t":"Friday","hi":25,"low":22,"sunr":"6:01 AM","suns":"5:09 PM","part":{"d":{"icon":"32","t":"Sunny","wind":{"s":"13","gust":"N\\/A","d":"32","t":"NNE"},"bt":"Sunny","ppcp":"0","hmid":"60"},"n":{"icon":"31","t":"Clear","wind":{"s":"12","gust":"N\\/A","d":"27","t":"NNE"},"bt":"Clear","ppcp":"0","hmid":"63"}}},{"dt":"Oct 26","t":"Saturday","hi":25,"low":21,"sunr":"6:02 AM","suns":"5:08 PM","part":{"d":{"icon":"32","t":"Sunny","wind":{"s":"12","gust":"N\\/A","d":"11","t":"N"},"bt":"Sunny","ppcp":"0","hmid":"59"},"n":{"icon":"31","t":"Clear","wind":{"s":"9","gust":"N\\/A","d":"11","t":"N"},"bt":"Clear","ppcp":"0","hmid":"65"}}},{"dt":"Oct 27","t":"Sunday","hi":24,"low":19,"sunr":"6:02 AM","suns":"5:07 PM","part":{"d":{"icon":"32","t":"Sunny","wind":{"s":"12","gust":"N\\/A","d":"346","t":"NNW"},"bt":"Sunny","ppcp":"0","hmid":"70"},"n":{"icon":"29","t":"Partly Cloudy","wind":{"s":"7","gust":"N\\/A","d":"317","t":"NW"},"bt":"P Cloudy","ppcp":"10","hmid":"76"}}}]'),
(19, 'EGXX0029', -18, 0, '', 'F', '5:54 AM', '5:05 PM', -18, '{"speed":"","gust":"","d":"","from":""}', '{"r":"","d":""}', 0, 0, '{"i":"","t":""}', '{"icon":"","t":""}', '[{"dt":"Oct 23","t":"Wednesday","hi":-18,"low":17,"sunr":"5:53 AM","suns":"5:05 PM","part":{"d":{"icon":"44","t":"N\\/A","wind":{"s":"N\\/A","gust":"N\\/A","d":"N\\/A","t":"N\\/A"},"bt":"N\\/A","ppcp":"0","hmid":"N\\/A"},"n":{"icon":"33","t":"Mostly Clear","wind":{"s":"2","gust":"N\\/A","d":"132","t":"SE"},"bt":"M Clear","ppcp":"0","hmid":"80"}}},{"dt":"Oct 24","t":"Thursday","hi":27,"low":16,"sunr":"5:54 AM","suns":"5:04 PM","part":{"d":{"icon":"30","t":"Partly Cloudy","wind":{"s":"11","gust":"N\\/A","d":"17","t":"NNE"},"bt":"P Cloudy","ppcp":"0","hmid":"56"},"n":{"icon":"29","t":"Partly Cloudy","wind":{"s":"7","gust":"N\\/A","d":"70","t":"ENE"},"bt":"P Cloudy","ppcp":"0","hmid":"79"}}},{"dt":"Oct 25","t":"Friday","hi":26,"low":17,"sunr":"5:55 AM","suns":"5:03 PM","part":{"d":{"icon":"32","t":"Sunny","wind":{"s":"12","gust":"N\\/A","d":"20","t":"NNE"},"bt":"Sunny","ppcp":"0","hmid":"54"},"n":{"icon":"31","t":"Clear","wind":{"s":"5","gust":"N\\/A","d":"37","t":"NE"},"bt":"Clear","ppcp":"0","hmid":"64"}}},{"dt":"Oct 26","t":"Saturday","hi":27,"low":16,"sunr":"5:56 AM","suns":"5:02 PM","part":{"d":{"icon":"32","t":"Sunny","wind":{"s":"13","gust":"N\\/A","d":"25","t":"NNE"},"bt":"Sunny","ppcp":"0","hmid":"56"},"n":{"icon":"31","t":"Clear","wind":{"s":"6","gust":"N\\/A","d":"64","t":"ENE"},"bt":"Clear","ppcp":"0","hmid":"67"}}},{"dt":"Oct 27","t":"Sunday","hi":26,"low":16,"sunr":"5:56 AM","suns":"5:01 PM","part":{"d":{"icon":"32","t":"Sunny","wind":{"s":"11","gust":"N\\/A","d":"1","t":"N"},"bt":"Sunny","ppcp":"0","hmid":"61"},"n":{"icon":"29","t":"Partly Cloudy","wind":{"s":"4","gust":"N\\/A","d":"292","t":"WNW"},"bt":"P Cloudy","ppcp":"0","hmid":"74"}}}]'),
(20, 'EGXX0030', 20, 33, 'Fair', 'F', '6:00 AM', '5:19 PM', 20, '{"speed":"5","gust":"N\\/A","d":"300","from":"WNW"}', '{"r":"29.97","d":"steady"}', 68, 6, '{"i":"0","t":"Low"}', '{"icon":"19","t":"Waning Gibbous"}', '[{"dt":"Oct 23","t":"Wednesday","hi":-18,"low":15,"sunr":"6:00 AM","suns":"5:19 PM","part":{"d":{"icon":"44","t":"N\\/A","wind":{"s":"N\\/A","gust":"N\\/A","d":"N\\/A","t":"N\\/A"},"bt":"N\\/A","ppcp":"0","hmid":"N\\/A"},"n":{"icon":"27","t":"Mostly Cloudy","wind":{"s":"6","gust":"N\\/A","d":"303","t":"WNW"},"bt":"M Cloudy","ppcp":"0","hmid":"65"}}},{"dt":"Oct 24","t":"Thursday","hi":29,"low":14,"sunr":"6:00 AM","suns":"5:19 PM","part":{"d":{"icon":"30","t":"AM Clouds \\/ PM Sun","wind":{"s":"9","gust":"N\\/A","d":"322","t":"NW"},"bt":"AM Clouds","ppcp":"0","hmid":"48"},"n":{"icon":"29","t":"Partly Cloudy","wind":{"s":"8","gust":"N\\/A","d":"318","t":"NW"},"bt":"P Cloudy","ppcp":"0","hmid":"51"}}},{"dt":"Oct 25","t":"Friday","hi":29,"low":14,"sunr":"6:01 AM","suns":"5:18 PM","part":{"d":{"icon":"32","t":"Sunny","wind":{"s":"10","gust":"N\\/A","d":"322","t":"NW"},"bt":"Sunny","ppcp":"0","hmid":"40"},"n":{"icon":"31","t":"Clear","wind":{"s":"7","gust":"N\\/A","d":"334","t":"NNW"},"bt":"Clear","ppcp":"0","hmid":"44"}}},{"dt":"Oct 26","t":"Saturday","hi":28,"low":14,"sunr":"6:02 AM","suns":"5:17 PM","part":{"d":{"icon":"32","t":"Sunny","wind":{"s":"11","gust":"N\\/A","d":"333","t":"NNW"},"bt":"Sunny","ppcp":"0","hmid":"38"},"n":{"icon":"31","t":"Clear","wind":{"s":"7","gust":"N\\/A","d":"334","t":"NNW"},"bt":"Clear","ppcp":"0","hmid":"53"}}},{"dt":"Oct 27","t":"Sunday","hi":27,"low":13,"sunr":"6:02 AM","suns":"5:16 PM","part":{"d":{"icon":"32","t":"Sunny","wind":{"s":"11","gust":"N\\/A","d":"328","t":"NNW"},"bt":"Sunny","ppcp":"0","hmid":"48"},"n":{"icon":"31","t":"Clear","wind":{"s":"7","gust":"N\\/A","d":"323","t":"NW"},"bt":"Clear","ppcp":"0","hmid":"59"}}}]'),
(21, 'EGXX0031', 21, 31, 'Clear', 'F', '6:25 AM', '5:40 PM', 21, '{"speed":"6","gust":"N\\/A","d":"40","from":"NE"}', '{"r":"N\\/A","d":"N\\/A"}', 52, 6, '{"i":"0","t":"Low"}', '{"icon":"19","t":"Waning Gibbous"}', '[{"dt":"Oct 23","t":"Wednesday","hi":-18,"low":13,"sunr":"6:24 AM","suns":"5:40 PM","part":{"d":{"icon":"44","t":"N\\/A","wind":{"s":"N\\/A","gust":"N\\/A","d":"N\\/A","t":"N\\/A"},"bt":"N\\/A","ppcp":"10","hmid":"N\\/A"},"n":{"icon":"29","t":"Partly Cloudy","wind":{"s":"8","gust":"N\\/A","d":"51","t":"NE"},"bt":"P Cloudy","ppcp":"10","hmid":"74"}}},{"dt":"Oct 24","t":"Thursday","hi":26,"low":14,"sunr":"6:25 AM","suns":"5:39 PM","part":{"d":{"icon":"34","t":"Mostly Sunny","wind":{"s":"8","gust":"N\\/A","d":"14","t":"NNE"},"bt":"M Sunny","ppcp":"10","hmid":"59"},"n":{"icon":"31","t":"Clear","wind":{"s":"9","gust":"N\\/A","d":"20","t":"NNE"},"bt":"Clear","ppcp":"0","hmid":"72"}}},{"dt":"Oct 25","t":"Friday","hi":27,"low":14,"sunr":"6:25 AM","suns":"5:38 PM","part":{"d":{"icon":"32","t":"Sunny","wind":{"s":"7","gust":"N\\/A","d":"14","t":"NNE"},"bt":"Sunny","ppcp":"0","hmid":"62"},"n":{"icon":"31","t":"Clear","wind":{"s":"7","gust":"N\\/A","d":"28","t":"NNE"},"bt":"Clear","ppcp":"0","hmid":"74"}}},{"dt":"Oct 26","t":"Saturday","hi":27,"low":13,"sunr":"6:26 AM","suns":"5:37 PM","part":{"d":{"icon":"32","t":"Sunny","wind":{"s":"6","gust":"N\\/A","d":"2","t":"N"},"bt":"Sunny","ppcp":"0","hmid":"59"},"n":{"icon":"31","t":"Clear","wind":{"s":"6","gust":"N\\/A","d":"31","t":"NNE"},"bt":"Clear","ppcp":"0","hmid":"72"}}},{"dt":"Oct 27","t":"Sunday","hi":26,"low":12,"sunr":"6:27 AM","suns":"5:36 PM","part":{"d":{"icon":"32","t":"Sunny","wind":{"s":"7","gust":"N\\/A","d":"339","t":"NNW"},"bt":"Sunny","ppcp":"0","hmid":"60"},"n":{"icon":"31","t":"Clear","wind":{"s":"5","gust":"N\\/A","d":"314","t":"NW"},"bt":"Clear","ppcp":"0","hmid":"65"}}}]'),
(22, 'EGXX0032', 22, 31, 'Clear', 'F', '6:01 AM', '5:23 PM', 22, '{"speed":"3","gust":"N\\/A","d":"350","from":"N"}', '{"r":"N\\/A","d":"N\\/A"}', 46, 6, '{"i":"0","t":"Low"}', '{"icon":"19","t":"Waning Gibbous"}', '[{"dt":"Oct 23","t":"Wednesday","hi":-18,"low":16,"sunr":"6:00 AM","suns":"5:23 PM","part":{"d":{"icon":"44","t":"N\\/A","wind":{"s":"N\\/A","gust":"N\\/A","d":"N\\/A","t":"N\\/A"},"bt":"N\\/A","ppcp":"0","hmid":"N\\/A"},"n":{"icon":"29","t":"Partly Cloudy","wind":{"s":"10","gust":"N\\/A","d":"350","t":"N"},"bt":"P Cloudy","ppcp":"0","hmid":"49"}}},{"dt":"Oct 24","t":"Thursday","hi":30,"low":14,"sunr":"6:01 AM","suns":"5:23 PM","part":{"d":{"icon":"34","t":"Mostly Sunny","wind":{"s":"13","gust":"N\\/A","d":"355","t":"N"},"bt":"M Sunny","ppcp":"0","hmid":"37"},"n":{"icon":"31","t":"Clear","wind":{"s":"12","gust":"N\\/A","d":"350","t":"N"},"bt":"Clear","ppcp":"0","hmid":"46"}}},{"dt":"Oct 25","t":"Friday","hi":31,"low":15,"sunr":"6:01 AM","suns":"5:22 PM","part":{"d":{"icon":"32","t":"Sunny","wind":{"s":"13","gust":"N\\/A","d":"353","t":"N"},"bt":"Sunny","ppcp":"0","hmid":"34"},"n":{"icon":"31","t":"Clear","wind":{"s":"9","gust":"N\\/A","d":"353","t":"N"},"bt":"Clear","ppcp":"0","hmid":"41"}}},{"dt":"Oct 26","t":"Saturday","hi":29,"low":15,"sunr":"6:02 AM","suns":"5:21 PM","part":{"d":{"icon":"32","t":"Sunny","wind":{"s":"16","gust":"N\\/A","d":"355","t":"N"},"bt":"Sunny","ppcp":"0","hmid":"28"},"n":{"icon":"31","t":"Clear","wind":{"s":"11","gust":"N\\/A","d":"353","t":"N"},"bt":"Clear","ppcp":"0","hmid":"41"}}},{"dt":"Oct 27","t":"Sunday","hi":28,"low":13,"sunr":"6:03 AM","suns":"5:20 PM","part":{"d":{"icon":"32","t":"Sunny","wind":{"s":"15","gust":"N\\/A","d":"351","t":"N"},"bt":"Sunny","ppcp":"0","hmid":"34"},"n":{"icon":"31","t":"Clear","wind":{"s":"10","gust":"N\\/A","d":"346","t":"NNW"},"bt":"Clear","ppcp":"0","hmid":"44"}}}]'),
(23, 'EGXX5799', 22, 31, 'Clear', 'F', '6:02 AM', '5:12 PM', 22, '{"speed":"9","gust":"N\\/A","d":"360","from":"N"}', '{"r":"N\\/A","d":"N\\/A"}', 60, 6, '{"i":"0","t":"Low"}', '{"icon":"19","t":"Waning Gibbous"}', '[{"dt":"Oct 23","t":"Wednesday","hi":-18,"low":17,"sunr":"6:01 AM","suns":"5:12 PM","part":{"d":{"icon":"44","t":"N\\/A","wind":{"s":"N\\/A","gust":"N\\/A","d":"N\\/A","t":"N\\/A"},"bt":"N\\/A","ppcp":"0","hmid":"N\\/A"},"n":{"icon":"29","t":"Partly Cloudy","wind":{"s":"9","gust":"N\\/A","d":"357","t":"N"},"bt":"P Cloudy","ppcp":"0","hmid":"78"}}},{"dt":"Oct 24","t":"Thursday","hi":27,"low":19,"sunr":"6:02 AM","suns":"5:11 PM","part":{"d":{"icon":"30","t":"Partly Cloudy","wind":{"s":"9","gust":"N\\/A","d":"8","t":"N"},"bt":"P Cloudy","ppcp":"0","hmid":"56"},"n":{"icon":"29","t":"Partly Cloudy","wind":{"s":"8","gust":"N\\/A","d":"35","t":"NE"},"bt":"P Cloudy","ppcp":"0","hmid":"80"}}},{"dt":"Oct 25","t":"Friday","hi":27,"low":19,"sunr":"6:03 AM","suns":"5:10 PM","part":{"d":{"icon":"32","t":"Sunny","wind":{"s":"11","gust":"N\\/A","d":"39","t":"NE"},"bt":"Sunny","ppcp":"0","hmid":"48"},"n":{"icon":"31","t":"Clear","wind":{"s":"9","gust":"N\\/A","d":"26","t":"NNE"},"bt":"Clear","ppcp":"0","hmid":"61"}}},{"dt":"Oct 26","t":"Saturday","hi":27,"low":17,"sunr":"6:04 AM","suns":"5:09 PM","part":{"d":{"icon":"32","t":"Sunny","wind":{"s":"10","gust":"N\\/A","d":"10","t":"N"},"bt":"Sunny","ppcp":"0","hmid":"54"},"n":{"icon":"31","t":"Clear","wind":{"s":"6","gust":"N\\/A","d":"342","t":"NNW"},"bt":"Clear","ppcp":"0","hmid":"70"}}},{"dt":"Oct 27","t":"Sunday","hi":26,"low":17,"sunr":"6:04 AM","suns":"5:08 PM","part":{"d":{"icon":"32","t":"Sunny","wind":{"s":"12","gust":"N\\/A","d":"323","t":"NW"},"bt":"Sunny","ppcp":"0","hmid":"63"},"n":{"icon":"33","t":"Mostly Clear","wind":{"s":"5","gust":"N\\/A","d":"302","t":"WNW"},"bt":"M Clear","ppcp":"0","hmid":"73"}}}]'),
(24, 'EGXX9447', 19, 33, 'Fair', 'F', '5:53 AM', '5:03 PM', 19, '{"speed":"3","gust":"N\\/A","d":"0","from":"VAR"}', '{"r":"29.97","d":"steady"}', 73, 6, '{"i":"0","t":"Low"}', '{"icon":"19","t":"Waning Gibbous"}', '[{"dt":"Oct 23","t":"Wednesday","hi":-18,"low":16,"sunr":"5:52 AM","suns":"5:03 PM","part":{"d":{"icon":"44","t":"N\\/A","wind":{"s":"N\\/A","gust":"N\\/A","d":"N\\/A","t":"N\\/A"},"bt":"N\\/A","ppcp":"10","hmid":"N\\/A"},"n":{"icon":"29","t":"Partly Cloudy","wind":{"s":"4","gust":"N\\/A","d":"137","t":"SE"},"bt":"P Cloudy","ppcp":"10","hmid":"83"}}},{"dt":"Oct 24","t":"Thursday","hi":27,"low":16,"sunr":"5:53 AM","suns":"5:02 PM","part":{"d":{"icon":"30","t":"Partly Cloudy","wind":{"s":"11","gust":"N\\/A","d":"16","t":"NNE"},"bt":"P Cloudy","ppcp":"10","hmid":"54"},"n":{"icon":"29","t":"Partly Cloudy","wind":{"s":"8","gust":"N\\/A","d":"65","t":"ENE"},"bt":"P Cloudy","ppcp":"10","hmid":"80"}}},{"dt":"Oct 25","t":"Friday","hi":26,"low":15,"sunr":"5:53 AM","suns":"5:01 PM","part":{"d":{"icon":"32","t":"Sunny","wind":{"s":"11","gust":"N\\/A","d":"355","t":"N"},"bt":"Sunny","ppcp":"0","hmid":"51"},"n":{"icon":"31","t":"Clear","wind":{"s":"6","gust":"N\\/A","d":"60","t":"ENE"},"bt":"Clear","ppcp":"0","hmid":"63"}}},{"dt":"Oct 26","t":"Saturday","hi":27,"low":15,"sunr":"5:54 AM","suns":"5:00 PM","part":{"d":{"icon":"32","t":"Sunny","wind":{"s":"12","gust":"N\\/A","d":"32","t":"NNE"},"bt":"Sunny","ppcp":"0","hmid":"51"},"n":{"icon":"31","t":"Clear","wind":{"s":"6","gust":"N\\/A","d":"58","t":"ENE"},"bt":"Clear","ppcp":"0","hmid":"65"}}},{"dt":"Oct 27","t":"Sunday","hi":27,"low":16,"sunr":"5:55 AM","suns":"4:59 PM","part":{"d":{"icon":"32","t":"Sunny","wind":{"s":"10","gust":"N\\/A","d":"5","t":"N"},"bt":"Sunny","ppcp":"0","hmid":"56"},"n":{"icon":"33","t":"Mostly Clear","wind":{"s":"4","gust":"N\\/A","d":"321","t":"NW"},"bt":"M Clear","ppcp":"0","hmid":"71"}}}]'),
(25, 'EGXX0205', 24, 27, 'Mostly Cloudy', 'F', '5:54 AM', '5:11 PM', 26, '{"speed":"8","gust":"N\\/A","d":"320","from":"NW"}', '{"r":"N\\/A","d":"N\\/A"}', 59, 6, '{"i":"0","t":"Low"}', '{"icon":"19","t":"Waning Gibbous"}', '[{"dt":"Oct 23","t":"Wednesday","hi":-18,"low":21,"sunr":"5:53 AM","suns":"5:11 PM","part":{"d":{"icon":"44","t":"N\\/A","wind":{"s":"N\\/A","gust":"N\\/A","d":"N\\/A","t":"N\\/A"},"bt":"N\\/A","ppcp":"0","hmid":"N\\/A"},"n":{"icon":"29","t":"Partly Cloudy","wind":{"s":"16","gust":"N\\/A","d":"331","t":"NNW"},"bt":"P Cloudy","ppcp":"0","hmid":"61"}}},{"dt":"Oct 24","t":"Thursday","hi":28,"low":20,"sunr":"5:54 AM","suns":"5:10 PM","part":{"d":{"icon":"30","t":"Partly Cloudy","wind":{"s":"16","gust":"N\\/A","d":"311","t":"NW"},"bt":"P Cloudy","ppcp":"0","hmid":"53"},"n":{"icon":"29","t":"Partly Cloudy","wind":{"s":"14","gust":"N\\/A","d":"311","t":"NW"},"bt":"P Cloudy","ppcp":"0","hmid":"56"}}},{"dt":"Oct 25","t":"Friday","hi":29,"low":19,"sunr":"5:55 AM","suns":"5:09 PM","part":{"d":{"icon":"32","t":"Sunny","wind":{"s":"16","gust":"N\\/A","d":"308","t":"NW"},"bt":"Sunny","ppcp":"0","hmid":"42"},"n":{"icon":"31","t":"Clear","wind":{"s":"12","gust":"N\\/A","d":"314","t":"NW"},"bt":"Clear","ppcp":"0","hmid":"48"}}},{"dt":"Oct 26","t":"Saturday","hi":28,"low":19,"sunr":"5:55 AM","suns":"5:08 PM","part":{"d":{"icon":"32","t":"Sunny","wind":{"s":"16","gust":"N\\/A","d":"315","t":"NW"},"bt":"Sunny","ppcp":"0","hmid":"41"},"n":{"icon":"31","t":"Clear","wind":{"s":"9","gust":"N\\/A","d":"318","t":"NW"},"bt":"Clear","ppcp":"0","hmid":"45"}}},{"dt":"Oct 27","t":"Sunday","hi":28,"low":18,"sunr":"5:56 AM","suns":"5:07 PM","part":{"d":{"icon":"32","t":"Sunny","wind":{"s":"14","gust":"N\\/A","d":"310","t":"NW"},"bt":"Sunny","ppcp":"0","hmid":"44"},"n":{"icon":"31","t":"Clear","wind":{"s":"12","gust":"N\\/A","d":"316","t":"NW"},"bt":"Clear","ppcp":"0","hmid":"50"}}}]'),
(26, 'EGXX0034', 17, 33, 'Fair', 'F', '5:52 AM', '5:08 PM', 17, '{"speed":"5","gust":"N\\/A","d":"210","from":"SSW"}', '{"r":"30.21","d":"steady"}', 30, 6, '{"i":"0","t":"Low"}', '{"icon":"19","t":"Waning Gibbous"}', '[{"dt":"Oct 23","t":"Wednesday","hi":-18,"low":21,"sunr":"5:51 AM","suns":"5:08 PM","part":{"d":{"icon":"44","t":"N\\/A","wind":{"s":"N\\/A","gust":"N\\/A","d":"N\\/A","t":"N\\/A"},"bt":"N\\/A","ppcp":"0","hmid":"N\\/A"},"n":{"icon":"29","t":"Partly Cloudy","wind":{"s":"16","gust":"N\\/A","d":"331","t":"NNW"},"bt":"P Cloudy","ppcp":"0","hmid":"61"}}},{"dt":"Oct 24","t":"Thursday","hi":28,"low":20,"sunr":"5:52 AM","suns":"5:08 PM","part":{"d":{"icon":"30","t":"Partly Cloudy","wind":{"s":"16","gust":"N\\/A","d":"311","t":"NW"},"bt":"P Cloudy","ppcp":"0","hmid":"53"},"n":{"icon":"29","t":"Partly Cloudy","wind":{"s":"14","gust":"N\\/A","d":"311","t":"NW"},"bt":"P Cloudy","ppcp":"0","hmid":"56"}}},{"dt":"Oct 25","t":"Friday","hi":29,"low":19,"sunr":"5:52 AM","suns":"5:07 PM","part":{"d":{"icon":"32","t":"Sunny","wind":{"s":"16","gust":"N\\/A","d":"308","t":"NW"},"bt":"Sunny","ppcp":"0","hmid":"42"},"n":{"icon":"31","t":"Clear","wind":{"s":"12","gust":"N\\/A","d":"314","t":"NW"},"bt":"Clear","ppcp":"0","hmid":"48"}}},{"dt":"Oct 26","t":"Saturday","hi":28,"low":19,"sunr":"5:53 AM","suns":"5:06 PM","part":{"d":{"icon":"32","t":"Sunny","wind":{"s":"16","gust":"N\\/A","d":"315","t":"NW"},"bt":"Sunny","ppcp":"0","hmid":"41"},"n":{"icon":"31","t":"Clear","wind":{"s":"9","gust":"N\\/A","d":"318","t":"NW"},"bt":"Clear","ppcp":"0","hmid":"45"}}},{"dt":"Oct 27","t":"Sunday","hi":28,"low":18,"sunr":"5:54 AM","suns":"5:05 PM","part":{"d":{"icon":"32","t":"Sunny","wind":{"s":"14","gust":"N\\/A","d":"310","t":"NW"},"bt":"Sunny","ppcp":"0","hmid":"44"},"n":{"icon":"31","t":"Clear","wind":{"s":"12","gust":"N\\/A","d":"316","t":"NW"},"bt":"Clear","ppcp":"0","hmid":"50"}}}]');
INSERT INTO `weather_cities` (`city_id`, `weather_city`, `temp`, `icon`, `status`, `temperature`, `sunr`, `suns`, `feelslik`, `wind`, `pressure`, `humidity`, `visibility`, `uv_index`, `moon`, `forecast`) VALUES
(27, 'EGXX9184', 20, 33, 'Fair', 'F', '5:58 AM', '5:18 PM', 20, '{"speed":"5","gust":"N\\/A","d":"300","from":"WNW"}', '{"r":"29.97","d":"steady"}', 68, 6, '{"i":"0","t":"Low"}', '{"icon":"19","t":"Waning Gibbous"}', '[{"dt":"Oct 23","t":"Wednesday","hi":-18,"low":16,"sunr":"5:58 AM","suns":"5:18 PM","part":{"d":{"icon":"44","t":"N\\/A","wind":{"s":"N\\/A","gust":"N\\/A","d":"N\\/A","t":"N\\/A"},"bt":"N\\/A","ppcp":"0","hmid":"N\\/A"},"n":{"icon":"26","t":"Cloudy","wind":{"s":"8","gust":"N\\/A","d":"327","t":"NNW"},"bt":"Cloudy","ppcp":"0","hmid":"59"}}},{"dt":"Oct 24","t":"Thursday","hi":30,"low":14,"sunr":"5:58 AM","suns":"5:17 PM","part":{"d":{"icon":"30","t":"Partly Cloudy","wind":{"s":"12","gust":"N\\/A","d":"327","t":"NNW"},"bt":"P Cloudy","ppcp":"0","hmid":"38"},"n":{"icon":"29","t":"Partly Cloudy","wind":{"s":"9","gust":"N\\/A","d":"346","t":"NNW"},"bt":"P Cloudy","ppcp":"0","hmid":"48"}}},{"dt":"Oct 25","t":"Friday","hi":31,"low":14,"sunr":"5:59 AM","suns":"5:17 PM","part":{"d":{"icon":"32","t":"Sunny","wind":{"s":"12","gust":"N\\/A","d":"330","t":"NNW"},"bt":"Sunny","ppcp":"0","hmid":"32"},"n":{"icon":"31","t":"Clear","wind":{"s":"7","gust":"N\\/A","d":"347","t":"NNW"},"bt":"Clear","ppcp":"0","hmid":"36"}}},{"dt":"Oct 26","t":"Saturday","hi":29,"low":13,"sunr":"5:59 AM","suns":"5:16 PM","part":{"d":{"icon":"32","t":"Sunny","wind":{"s":"13","gust":"N\\/A","d":"336","t":"NNW"},"bt":"Sunny","ppcp":"0","hmid":"27"},"n":{"icon":"31","t":"Clear","wind":{"s":"9","gust":"N\\/A","d":"342","t":"NNW"},"bt":"Clear","ppcp":"0","hmid":"41"}}},{"dt":"Oct 27","t":"Sunday","hi":28,"low":13,"sunr":"6:00 AM","suns":"5:15 PM","part":{"d":{"icon":"32","t":"Sunny","wind":{"s":"13","gust":"N\\/A","d":"333","t":"NNW"},"bt":"Sunny","ppcp":"0","hmid":"37"},"n":{"icon":"31","t":"Clear","wind":{"s":"9","gust":"N\\/A","d":"337","t":"NNW"},"bt":"Clear","ppcp":"0","hmid":"48"}}}]');

-- --------------------------------------------------------

--
-- Table structure for table `workflow`
--

CREATE TABLE IF NOT EXISTS `workflow` (
  `flow_id` mediumint(9) NOT NULL AUTO_INCREMENT,
  `flow_title` varchar(30) DEFAULT NULL,
  `enabled` tinyint(1) DEFAULT '1',
  `system` tinyint(1) DEFAULT '0',
  `module_id` mediumint(9) NOT NULL,
  PRIMARY KEY (`flow_id`),
  KEY `fk_workflow_modules1` (`module_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `workflow_actions`
--

CREATE TABLE IF NOT EXISTS `workflow_actions` (
  `action_id` mediumint(9) NOT NULL,
  `step_id` mediumint(9) NOT NULL,
  `is_major` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`action_id`,`step_id`),
  KEY `fk_workflow_has_actions_actions1` (`action_id`),
  KEY `sort_workflow_action` (`is_major`),
  KEY `fk_workflow_actions_workflow_steps1` (`step_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `workflow_comments`
--

CREATE TABLE IF NOT EXISTS `workflow_comments` (
  `comment_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `to_task` int(11) NOT NULL,
  `from_task` int(11) NOT NULL,
  `comment_review` int(10) unsigned DEFAULT NULL,
  `comment_header` varchar(150) NOT NULL,
  `comment` text NOT NULL,
  `comment_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `ip` varchar(15) NOT NULL,
  PRIMARY KEY (`comment_id`),
  KEY `fk_comments_comments1` (`comment_review`),
  KEY `fk_workflow_comments_workflow_tasks1` (`from_task`),
  KEY `fk_workflow_comments_workflow_tasks2` (`to_task`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `workflow_roles`
--

CREATE TABLE IF NOT EXISTS `workflow_roles` (
  `role_id` smallint(5) unsigned NOT NULL,
  `step_id` mediumint(9) NOT NULL,
  PRIMARY KEY (`role_id`,`step_id`),
  KEY `fk_workflow_has_roles_roles1` (`role_id`),
  KEY `fk_workflow_roles_workflow_steps1` (`step_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `workflow_steps`
--

CREATE TABLE IF NOT EXISTS `workflow_steps` (
  `step_id` mediumint(9) NOT NULL AUTO_INCREMENT,
  `flow_id` mediumint(9) NOT NULL,
  `step_sort` mediumint(9) NOT NULL,
  `step_title` varchar(30) DEFAULT NULL,
  `enabled` tinyint(1) DEFAULT '1',
  `system` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`step_id`),
  KEY `fk_workflow_steps_workflow1` (`flow_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `workflow_tasks`
--

CREATE TABLE IF NOT EXISTS `workflow_tasks` (
  `task_id` int(11) NOT NULL AUTO_INCREMENT,
  `step_id` mediumint(9) NOT NULL,
  `return_from` int(11) DEFAULT NULL,
  `item_id` int(10) unsigned NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`task_id`),
  UNIQUE KEY `flow_item` (`item_id`),
  KEY `fk_workflow_tasks_workflow_tasks1` (`return_from`),
  KEY `fk_workflow_tasks_workflow_steps1` (`step_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `workflow_users`
--

CREATE TABLE IF NOT EXISTS `workflow_users` (
  `user_id` int(10) unsigned NOT NULL,
  `step_id` mediumint(9) NOT NULL,
  PRIMARY KEY (`user_id`,`step_id`),
  KEY `fk_workflow_has_users_users1` (`user_id`),
  KEY `fk_workflow_users_workflow_steps1` (`step_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `writers`
--

CREATE TABLE IF NOT EXISTS `writers` (
  `writer_id` int(10) unsigned NOT NULL,
  `writer_type` tinyint(3) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`writer_id`),
  KEY `fk_writers_persons` (`writer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `exchange` (
  `exchange_id` INT NOT NULL AUTO_INCREMENT,
  `exchange_name` VARCHAR(45) NULL,
  `currency` VARCHAR(45) NULL,
  PRIMARY KEY (`exchange_id`))
ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `exchange_companies` (
  `exchange_companies_id` INT NOT NULL AUTO_INCREMENT,
  `exchange_id` INT NOT NULL,
  `code` VARCHAR(45) NULL,
  `published` TINYINT(1) NOT NULL,
  PRIMARY KEY (`exchange_companies_id`),
  INDEX `fk_exchange_companies_exchange1_idx` (`exchange_id` ASC),
  CONSTRAINT `fk_exchange_companies_exchange1`
    FOREIGN KEY (`exchange_id`)
    REFERENCES `exchange` (`exchange_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `exchange_trading` (
  `exchange_id` INT NOT NULL,
  `exchange_date` DATE NOT NULL,
  `trading_value` DECIMAL(16,2) NOT NULL,
  `shares_of_stock` DECIMAL(16,2) NOT NULL,
  `closing_value` DECIMAL(12,2) NOT NULL,
  `difference_value` DECIMAL(8,3) NOT NULL,
  `difference_percentage` DECIMAL(8,5) NOT NULL,
  PRIMARY KEY (`exchange_id`, `exchange_date`),
  INDEX `fk_exchange_trading_exchange1_idx` (`exchange_id` ASC),
  CONSTRAINT `fk_exchange_trading_exchange1`
    FOREIGN KEY (`exchange_id`)
    REFERENCES `exchange` (`exchange_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `exchange_trading_companies` (
  `exchange_trading_exchange_id` INT NOT NULL,
  `exchange_trading_exchange_date` DATE NOT NULL,
  `exchange_companies_exchange_companies_id` INT NOT NULL,
  `opening_value` DECIMAL(12,2) NULL,
  `closing_value` DECIMAL(12,2) NULL,
  `difference_percentage` DECIMAL(8,5) NULL,
  PRIMARY KEY (`exchange_trading_exchange_id`, `exchange_trading_exchange_date`, `exchange_companies_exchange_companies_id`),
  INDEX `fk_exchange_trading_has_exchange_companies_exchange_compani_idx` (`exchange_companies_exchange_companies_id` ASC),
  INDEX `fk_exchange_trading_has_exchange_companies_exchange_trading_idx` (`exchange_trading_exchange_id` ASC, `exchange_trading_exchange_date` ASC),
  CONSTRAINT `fk_exchange_trading_has_exchange_companies_exchange_trading1`
    FOREIGN KEY (`exchange_trading_exchange_id` , `exchange_trading_exchange_date`)
    REFERENCES `exchange_trading` (`exchange_id` , `exchange_date`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_exchange_trading_has_exchange_companies_exchange_companies1`
    FOREIGN KEY (`exchange_companies_exchange_companies_id`)
    REFERENCES `exchange_companies` (`exchange_companies_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `exchange_companies_translation` (
  `exchange_companies_id` INT NOT NULL,
  `company_name` VARCHAR(100) NOT NULL,
  `content_lang` CHAR(2) NOT NULL,
  PRIMARY KEY (`exchange_companies_id`),
  CONSTRAINT `fk_exchange_companies_translation_exchange_companies1`
    FOREIGN KEY (`exchange_companies_id`)
    REFERENCES `exchange_companies` (`exchange_companies_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
--
-- Constraints for dumped tables
--

--
-- Constraints for table `access_rights`
--
ALTER TABLE `access_rights`
  ADD CONSTRAINT `fk_access_rights_controllers1` FOREIGN KEY (`controller_id`) REFERENCES `controllers` (`controller_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_roles_has_pages_roles1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`role_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `actions`
--
ALTER TABLE `actions`
  ADD CONSTRAINT `fk_actions_controllers1` FOREIGN KEY (`controller_id`) REFERENCES `controllers` (`controller_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `ads_zones`
--
ALTER TABLE `ads_zones`
  ADD CONSTRAINT `fk_ads_zones_ads_servers_config1` FOREIGN KEY (`server_id`) REFERENCES `ads_servers_config` (`server_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_ads_zones_default_ads_zones1` FOREIGN KEY (`zone_id`) REFERENCES `default_ads_zones` (`zone_id`) ON UPDATE CASCADE;

--
-- Constraints for table `ads_zones_has_sections`
--
ALTER TABLE `ads_zones_has_sections`
  ADD CONSTRAINT `fk_ads_zones_sections_ads_zones1` FOREIGN KEY (`ad_id`) REFERENCES `ads_zones` (`ad_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_ads_zones_sections_sections1` FOREIGN KEY (`section_id`) REFERENCES `sections` (`section_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `articles`
--
ALTER TABLE `articles`
  ADD CONSTRAINT `fk_articles_articles1` FOREIGN KEY (`parent_article`) REFERENCES `articles` (`article_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_articles_countries1` FOREIGN KEY (`country_code`) REFERENCES `countries` (`code`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_articles_sections1` FOREIGN KEY (`section_id`) REFERENCES `sections` (`section_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_articles_writers1` FOREIGN KEY (`writer_id`) REFERENCES `writers` (`writer_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `articles_comments`
--
ALTER TABLE `articles_comments`
  ADD CONSTRAINT `articles_comments_ibfk_1` FOREIGN KEY (`article_comment_id`) REFERENCES `comments` (`comment_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_articles_comments_articles1` FOREIGN KEY (`article_id`) REFERENCES `articles` (`article_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `articles_titles`
--
ALTER TABLE `articles_titles`
  ADD CONSTRAINT `fk_articles_titles_articles1` FOREIGN KEY (`article_id`, `content_lang`) REFERENCES `articles_translation` (`article_id`, `content_lang`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `articles_translation`
--
ALTER TABLE `articles_translation`
  ADD CONSTRAINT `fk_articles_translation_1` FOREIGN KEY (`article_id`) REFERENCES `articles` (`article_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `attachment`
--
ALTER TABLE `attachment`
  ADD CONSTRAINT `fk_attachment_modules1` FOREIGN KEY (`module_id`) REFERENCES `modules` (`module_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `attachment_translation`
--
ALTER TABLE `attachment_translation`
  ADD CONSTRAINT `fk_attachment_translation` FOREIGN KEY (`attach_id`) REFERENCES `attachment` (`attach_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `fk_comments_comments1` FOREIGN KEY (`comment_review`) REFERENCES `comments` (`comment_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_comments_users1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `comments_owners`
--
ALTER TABLE `comments_owners`
  ADD CONSTRAINT `fk_comments_owners_comments1` FOREIGN KEY (`comment_id`) REFERENCES `comments` (`comment_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `controllers`
--
ALTER TABLE `controllers`
  ADD CONSTRAINT `fk_controllers_modules1` FOREIGN KEY (`module_id`) REFERENCES `modules` (`module_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `countries`
--
ALTER TABLE `countries`
  ADD CONSTRAINT `fk_countries_currency1` FOREIGN KEY (`currency_code`) REFERENCES `currency` (`currency_code`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `countries_translation`
--
ALTER TABLE `countries_translation`
  ADD CONSTRAINT `fk_countries_translation` FOREIGN KEY (`code`) REFERENCES `countries` (`code`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `currency_compare`
--
ALTER TABLE `currency_compare`
  ADD CONSTRAINT `fk_currency_compare_currency1` FOREIGN KEY (`compare_from`) REFERENCES `currency` (`currency_code`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_currency_compare_currency2` FOREIGN KEY (`compare_to`) REFERENCES `currency` (`currency_code`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `currency_translation`
--
ALTER TABLE `currency_translation`
  ADD CONSTRAINT `fk_currency_translation` FOREIGN KEY (`currency_code`) REFERENCES `currency` (`currency_code`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `deleted_users_log`
--
ALTER TABLE `deleted_users_log`
  ADD CONSTRAINT `fk_deleted_users_has_users_log_deleted_users1` FOREIGN KEY (`deleted_id`) REFERENCES `deleted_users` (`deleted_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_deleted_users_has_users_log_users_log1` FOREIGN KEY (`log_id`) REFERENCES `users_log` (`log_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `dir_categories`
--
ALTER TABLE `dir_categories`
  ADD CONSTRAINT `fk_dir_categories_dir_categories1` FOREIGN KEY (`parent_category`) REFERENCES `dir_categories` (`category_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `dir_categories_translation`
--
ALTER TABLE `dir_categories_translation`
  ADD CONSTRAINT `fk_dir_categories_translation_1` FOREIGN KEY (`category_id`) REFERENCES `dir_categories` (`category_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `dir_companies`
--
ALTER TABLE `dir_companies`
  ADD CONSTRAINT `fk_dir_companies_countries1` FOREIGN KEY (`nationality`) REFERENCES `countries` (`code`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_dir_companies_dir_categories1` FOREIGN KEY (`category_id`) REFERENCES `dir_categories` (`category_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_dir_companies_users1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON UPDATE CASCADE;

--
-- Constraints for table `dir_companies_articles`
--
ALTER TABLE `dir_companies_articles`
  ADD CONSTRAINT `fk_dir_companies_has_articles_articles1` FOREIGN KEY (`article_id`) REFERENCES `articles` (`article_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_dir_companies_has_articles_dir_companies1` FOREIGN KEY (`company_id`) REFERENCES `dir_companies` (`company_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `dir_companies_attributes`
--
ALTER TABLE `dir_companies_attributes`
  ADD CONSTRAINT `fk_dir_companies_attributes0` FOREIGN KEY (`company_id`) REFERENCES `dir_companies` (`company_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_dir_companies_attributes_system_attributes1` FOREIGN KEY (`attribute_id`) REFERENCES `system_attributes` (`attribute_id`) ON UPDATE CASCADE;

--
-- Constraints for table `dir_companies_attributes_value`
--
ALTER TABLE `dir_companies_attributes_value`
  ADD CONSTRAINT `fk_dir_companies_attributes_values_dir_companies_attributes1` FOREIGN KEY (`company_attribute_id`) REFERENCES `dir_companies_attributes` (`company_attribute_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `dir_companies_attributes_value_translation`
--
ALTER TABLE `dir_companies_attributes_value_translation`
  ADD CONSTRAINT `fk_dir_companies_attributes_values_dir_companies_attributes10` FOREIGN KEY (`company_attribute_id`) REFERENCES `dir_companies_attributes` (`company_attribute_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `dir_companies_branches`
--
ALTER TABLE `dir_companies_branches`
  ADD CONSTRAINT `fk_dir_companies_branches_1` FOREIGN KEY (`company_id`) REFERENCES `dir_companies` (`company_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_dir_companies_branches_countries1` FOREIGN KEY (`country`) REFERENCES `countries` (`code`) ON UPDATE CASCADE;

--
-- Constraints for table `dir_companies_branches_translation`
--
ALTER TABLE `dir_companies_branches_translation`
  ADD CONSTRAINT `fk_dir_companies_branches_translation_dir_companies_branches1` FOREIGN KEY (`branch_id`) REFERENCES `dir_companies_branches` (`branch_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `dir_companies_translation`
--
ALTER TABLE `dir_companies_translation`
  ADD CONSTRAINT `fk_dir_companies_translation_dir_companies1` FOREIGN KEY (`company_id`) REFERENCES `dir_companies` (`company_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `docs`
--
ALTER TABLE `docs`
  ADD CONSTRAINT `fk_docs_docs_categories1` FOREIGN KEY (`category_id`) REFERENCES `docs_categories` (`category_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `docs_categories`
--
ALTER TABLE `docs_categories`
  ADD CONSTRAINT `fk_dir_categories_dir_categories10` FOREIGN KEY (`parent_category`) REFERENCES `docs_categories` (`category_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `docs_categories_translation`
--
ALTER TABLE `docs_categories_translation`
  ADD CONSTRAINT `fk_docs_categories_translation_docs_categories1` FOREIGN KEY (`category_id`) REFERENCES `docs_categories` (`category_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `docs_translation`
--
ALTER TABLE `docs_translation`
  ADD CONSTRAINT `fk_docs_translation_docs1` FOREIGN KEY (`doc_id`) REFERENCES `docs` (`doc_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `dope_sheet`
--
ALTER TABLE `dope_sheet`
  ADD CONSTRAINT `fk_dope_sheet_videos1` FOREIGN KEY (`video_id`) REFERENCES `videos` (`video_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `dope_sheet_shots`
--
ALTER TABLE `dope_sheet_shots`
  ADD CONSTRAINT `fk_dope_sheet_shots1` FOREIGN KEY (`video_id`) REFERENCES `dope_sheet` (`video_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_dope_sheet_shots_types1` FOREIGN KEY (`type_id`) REFERENCES `dope_sheet_shots_types` (`type_id`) ON UPDATE CASCADE;

--
-- Constraints for table `dope_sheet_shots_translation`
--
ALTER TABLE `dope_sheet_shots_translation`
  ADD CONSTRAINT `fk_dope_sheet_shots_translation1` FOREIGN KEY (`shot_id`) REFERENCES `dope_sheet_shots` (`shot_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `dope_sheet_translation`
--
ALTER TABLE `dope_sheet_translation`
  ADD CONSTRAINT `fk_dope_sheet_videos_translation10` FOREIGN KEY (`video_id`) REFERENCES `dope_sheet` (`video_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `essays`
--
ALTER TABLE `essays`
  ADD CONSTRAINT `fk_news_articles0` FOREIGN KEY (`article_id`) REFERENCES `articles` (`article_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `events`
--
ALTER TABLE `events`
  ADD CONSTRAINT `fk_events_countries1` FOREIGN KEY (`country_code`) REFERENCES `countries` (`code`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_events_sections` FOREIGN KEY (`section_id`) REFERENCES `sections` (`section_id`) ON DELETE CASCADE ON UPDATE SET NULL;

--
-- Constraints for table `events_translation`
--
ALTER TABLE `events_translation`
  ADD CONSTRAINT `fk_events_master` FOREIGN KEY (`event_id`) REFERENCES `events` (`event_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `external_videos`
--
ALTER TABLE `external_videos`
  ADD CONSTRAINT `fk_external_videos_videos1` FOREIGN KEY (`video_id`) REFERENCES `videos` (`video_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `files`
--
ALTER TABLE `files`
  ADD CONSTRAINT `fk_files_folders1` FOREIGN KEY (`folder_id`) REFERENCES `folders` (`folder_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_files_users1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `foreign_currencies`
--
ALTER TABLE `foreign_currencies`
  ADD CONSTRAINT `fk_foreign_currencies_currency1` FOREIGN KEY (`currency_code`) REFERENCES `currency` (`currency_code`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `forward_actions`
--
ALTER TABLE `forward_actions`
  ADD CONSTRAINT `fk_forward_from_actions` FOREIGN KEY (`forward_from`) REFERENCES `actions` (`action_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_forward_to_actions` FOREIGN KEY (`forward_to`) REFERENCES `actions` (`action_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `forward_modules`
--
ALTER TABLE `forward_modules`
  ADD CONSTRAINT `fk_modules_forward_from` FOREIGN KEY (`forward_from`) REFERENCES `modules` (`module_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_modules_forward_to` FOREIGN KEY (`forward_to`) REFERENCES `modules` (`module_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `galleries`
--
ALTER TABLE `galleries`
  ADD CONSTRAINT `fk_galleries_countries1` FOREIGN KEY (`country_code`) REFERENCES `countries` (`code`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_galleries_sections` FOREIGN KEY (`section_id`) REFERENCES `sections` (`section_id`) ON UPDATE CASCADE;

--
-- Constraints for table `galleries_translation`
--
ALTER TABLE `galleries_translation`
  ADD CONSTRAINT `fk_galleries_translation_1` FOREIGN KEY (`gallery_id`) REFERENCES `galleries` (`gallery_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `glossary`
--
ALTER TABLE `glossary`
  ADD CONSTRAINT `fk_glossary_glossary_categories1` FOREIGN KEY (`category_id`) REFERENCES `glossary_categories` (`category_id`) ON UPDATE CASCADE;

--
-- Constraints for table `glossary_categories_translation`
--
ALTER TABLE `glossary_categories_translation`
  ADD CONSTRAINT `fk_glossary_categories_translation_glossary_categories1` FOREIGN KEY (`category_id`) REFERENCES `glossary_categories` (`category_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `glossary_translation`
--
ALTER TABLE `glossary_translation`
  ADD CONSTRAINT `fk_glossary_translation_glossary1` FOREIGN KEY (`expression_id`) REFERENCES `glossary` (`expression_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `images`
--
ALTER TABLE `images`
  ADD CONSTRAINT `fk_images_galleries1` FOREIGN KEY (`gallery_id`) REFERENCES `galleries` (`gallery_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_images_users1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON UPDATE CASCADE;

--
-- Constraints for table `images_comments`
--
ALTER TABLE `images_comments`
  ADD CONSTRAINT `fk_images_images_comments` FOREIGN KEY (`image_id`) REFERENCES `images` (`image_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_image_comment_comments1` FOREIGN KEY (`image_comment_id`) REFERENCES `comments` (`comment_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `images_translation`
--
ALTER TABLE `images_translation`
  ADD CONSTRAINT `fk_images_translation_1` FOREIGN KEY (`image_id`) REFERENCES `images` (`image_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `infocus`
--
ALTER TABLE `infocus`
  ADD CONSTRAINT `fk_infocus_countries` FOREIGN KEY (`country_code`) REFERENCES `countries` (`code`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_infocus_sections` FOREIGN KEY (`section_id`) REFERENCES `sections` (`section_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `infocus_has_articles`
--
ALTER TABLE `infocus_has_articles`
  ADD CONSTRAINT `fk_infocus_has_articles_articles` FOREIGN KEY (`article_id`) REFERENCES `articles` (`article_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_infocus_has_articles_infocus1` FOREIGN KEY (`infocus_id`) REFERENCES `infocus` (`infocus_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `infocus_has_images`
--
ALTER TABLE `infocus_has_images`
  ADD CONSTRAINT `fk_infocus_has_images_images` FOREIGN KEY (`image_id`) REFERENCES `images` (`image_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_infocus_has_images_infocus1` FOREIGN KEY (`infocus_id`) REFERENCES `infocus` (`infocus_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `infocus_has_videos`
--
ALTER TABLE `infocus_has_videos`
  ADD CONSTRAINT `fk_infocus_has_videos_infocus1` FOREIGN KEY (`infocus_id`) REFERENCES `infocus` (`infocus_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_infocus_has_videos_videos` FOREIGN KEY (`video_id`) REFERENCES `videos` (`video_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `infocus_translation`
--
ALTER TABLE `infocus_translation`
  ADD CONSTRAINT `fk_infocus_translation_1` FOREIGN KEY (`infocus_id`) REFERENCES `infocus` (`infocus_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `internal_videos`
--
ALTER TABLE `internal_videos`
  ADD CONSTRAINT `fk_internal_videos_videos1` FOREIGN KEY (`video_id`) REFERENCES `videos` (`video_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `issues_articles`
--
ALTER TABLE `issues_articles`
  ADD CONSTRAINT `fk_issues_articles_1` FOREIGN KEY (`article_id`) REFERENCES `articles` (`article_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_issues_articles_issues1` FOREIGN KEY (`issue_id`) REFERENCES `issues` (`issue_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `jobs`
--
ALTER TABLE `jobs`
  ADD CONSTRAINT `fk_jobs_jobs_categories1` FOREIGN KEY (`category_id`) REFERENCES `jobs_categories` (`category_id`) ON UPDATE CASCADE;

--
-- Constraints for table `jobs_categories_translation`
--
ALTER TABLE `jobs_categories_translation`
  ADD CONSTRAINT `fk_jobs_categories_translation_jobs_categories1` FOREIGN KEY (`category_id`) REFERENCES `jobs_categories` (`category_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `jobs_requests`
--
ALTER TABLE `jobs_requests`
  ADD CONSTRAINT `fk_jobs_requests_countries1` FOREIGN KEY (`nationality`) REFERENCES `countries` (`code`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_jobs_requests_jobs1` FOREIGN KEY (`job_id`) REFERENCES `jobs` (`job_id`) ON UPDATE CASCADE;

--
-- Constraints for table `jobs_translation`
--
ALTER TABLE `jobs_translation`
  ADD CONSTRAINT `fk_jobs_translation_jobs1` FOREIGN KEY (`job_id`) REFERENCES `jobs` (`job_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `log_data`
--
ALTER TABLE `log_data`
  ADD CONSTRAINT `fk_log_data_users_log1` FOREIGN KEY (`log_id`) REFERENCES `users_log` (`log_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `maillist`
--
ALTER TABLE `maillist`
  ADD CONSTRAINT `maillist_ibfk_1` FOREIGN KEY (`person_id`) REFERENCES `persons` (`person_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `maillist_articles_log`
--
ALTER TABLE `maillist_articles_log`
  ADD CONSTRAINT `fk_maillist_articles_log_articles1` FOREIGN KEY (`article_id`) REFERENCES `articles` (`article_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_maillist_articles_log_maillist_channels_subscribe1` FOREIGN KEY (`subscriber_id`) REFERENCES `maillist` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_maillist_articles_log_maillist_message1` FOREIGN KEY (`message_id`) REFERENCES `maillist_message` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `maillist_channels_subscribe`
--
ALTER TABLE `maillist_channels_subscribe`
  ADD CONSTRAINT `fk_mailist_channels_has_maillist_mailist_channels1` FOREIGN KEY (`channel_id`) REFERENCES `maillist_channels` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_mailist_channels_has_maillist_maillist1` FOREIGN KEY (`subscriber_id`) REFERENCES `maillist` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `maillist_channels_templates`
--
ALTER TABLE `maillist_channels_templates`
  ADD CONSTRAINT `fk_maillist_channels_templates_maillist_channels1` FOREIGN KEY (`channel_id`) REFERENCES `maillist_channels` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `maillist_log`
--
ALTER TABLE `maillist_log`
  ADD CONSTRAINT `fk_maillist_log_maillist_message1` FOREIGN KEY (`message_id`) REFERENCES `maillist_message` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_maillist_log_maillist_subscribe1` FOREIGN KEY (`subscriber_id`) REFERENCES `maillist` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `maillist_message`
--
ALTER TABLE `maillist_message`
  ADD CONSTRAINT `fk_mailist_message_mailist_channels1` FOREIGN KEY (`channel_id`) REFERENCES `maillist_channels` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_mailist_message_maillist_channels_templates1` FOREIGN KEY (`template_id`) REFERENCES `maillist_channels_templates` (`template_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `maillist_messages_setions`
--
ALTER TABLE `maillist_messages_setions`
  ADD CONSTRAINT `fk_maillist_messages_setions_maillist_message1` FOREIGN KEY (`message_id`) REFERENCES `maillist_message` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_maillist_messages_setions_sections1` FOREIGN KEY (`section_id`) REFERENCES `sections` (`section_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `maillist_message_queue`
--
ALTER TABLE `maillist_message_queue`
  ADD CONSTRAINT `fk_maillist_message_has_maillist_maillist1` FOREIGN KEY (`maillist_id`) REFERENCES `maillist` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_maillist_message_has_maillist_maillist_message1` FOREIGN KEY (`message_id`) REFERENCES `maillist_message` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `maillist_users`
--
ALTER TABLE `maillist_users`
  ADD CONSTRAINT `fk_maillist_users_maillist1` FOREIGN KEY (`user_id`) REFERENCES `maillist` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `menu_items`
--
ALTER TABLE `menu_items`
  ADD CONSTRAINT `fk_menus_main_menus1` FOREIGN KEY (`menu_id`) REFERENCES `menus` (`menu_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_menus_menus1` FOREIGN KEY (`parent_item`) REFERENCES `menu_items` (`item_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `menu_items_params`
--
ALTER TABLE `menu_items_params`
  ADD CONSTRAINT `fk_menu_items_has_menus_params_menu_items1` FOREIGN KEY (`item_id`) REFERENCES `menu_items` (`item_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_menu_items_params_modules_components_params1` FOREIGN KEY (`component_id`, `param_id`) REFERENCES `modules_components_params` (`component_id`, `param_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `menu_item_translation`
--
ALTER TABLE `menu_item_translation`
  ADD CONSTRAINT `fk_menu_item_labels_menu_items1` FOREIGN KEY (`item_id`) REFERENCES `menu_items` (`item_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `modules`
--
ALTER TABLE `modules`
  ADD CONSTRAINT `fk_modules_modules1` FOREIGN KEY (`parent_module`) REFERENCES `modules` (`module_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `modules_components`
--
ALTER TABLE `modules_components`
  ADD CONSTRAINT `fk_modules_components` FOREIGN KEY (`module_id`) REFERENCES `modules` (`module_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `modules_components_params`
--
ALTER TABLE `modules_components_params`
  ADD CONSTRAINT `fk_modules_components_has_modules_components_params_modules_c1` FOREIGN KEY (`component_id`) REFERENCES `modules_components` (`component_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_modules_components_has_modules_components_params_modules_c2` FOREIGN KEY (`param_id`) REFERENCES `menus_params` (`param_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `modules_components_translation`
--
ALTER TABLE `modules_components_translation`
  ADD CONSTRAINT `fk_modules_components_labels` FOREIGN KEY (`component_id`) REFERENCES `modules_components` (`component_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `module_social_config`
--
ALTER TABLE `module_social_config`
  ADD CONSTRAINT `fk_module_social` FOREIGN KEY (`module_id`) REFERENCES `modules` (`module_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_module_social_config_social_networks1` FOREIGN KEY (`social_id`) REFERENCES `social_networks` (`social_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `module_social_config_langs`
--
ALTER TABLE `module_social_config_langs`
  ADD CONSTRAINT `fk_module_social_config_langs_module_social_config1` FOREIGN KEY (`config_id`) REFERENCES `module_social_config` (`config_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `moduls_components_params_translation`
--
ALTER TABLE `moduls_components_params_translation`
  ADD CONSTRAINT `fk_moduls_components_params_translation1` FOREIGN KEY (`component_id`, `param_id`) REFERENCES `modules_components_params` (`component_id`, `param_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `news`
--
ALTER TABLE `news`
  ADD CONSTRAINT `fk_news_articles` FOREIGN KEY (`article_id`) REFERENCES `articles` (`article_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_news_news_source1` FOREIGN KEY (`source_id`) REFERENCES `news_sources` (`source_id`) ON UPDATE CASCADE;

--
-- Constraints for table `news_editors`
--
ALTER TABLE `news_editors`
  ADD CONSTRAINT `fk_news_editors_editors1` FOREIGN KEY (`editor_id`) REFERENCES `writers` (`writer_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_news_editors_news1` FOREIGN KEY (`article_id`) REFERENCES `news` (`article_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `news_sources_translation`
--
ALTER TABLE `news_sources_translation`
  ADD CONSTRAINT `fk_news_source_translation_news_source1` FOREIGN KEY (`source_id`) REFERENCES `news_sources` (`source_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `persons`
--
ALTER TABLE `persons`
  ADD CONSTRAINT `fk_persons_countries1` FOREIGN KEY (`country_code`) REFERENCES `countries` (`code`) ON UPDATE CASCADE;

--
-- Constraints for table `persons_translation`
--
ALTER TABLE `persons_translation`
  ADD CONSTRAINT `fk_persons_translated_1` FOREIGN KEY (`person_id`) REFERENCES `persons` (`person_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `prayer_times`
--
ALTER TABLE `prayer_times`
  ADD CONSTRAINT `fk_prayer_times_services_cities1` FOREIGN KEY (`city_id`) REFERENCES `services_cities` (`city_id`) ON UPDATE CASCADE;

--
-- Constraints for table `regions_has_countries`
--
ALTER TABLE `regions_has_countries`
  ADD CONSTRAINT `fk_regions_has_countries_countries` FOREIGN KEY (`country_code`) REFERENCES `countries` (`code`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_regions_has_countries_regions` FOREIGN KEY (`region_id`) REFERENCES `regions` (`region_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `regions_translation`
--
ALTER TABLE `regions_translation`
  ADD CONSTRAINT `fk_regions_names_1` FOREIGN KEY (`region_id`) REFERENCES `regions` (`region_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `related_sections`
--
ALTER TABLE `related_sections`
  ADD CONSTRAINT `fk_sections_ids_has_sections_ids_sections_ids1` FOREIGN KEY (`section`) REFERENCES `sections` (`section_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_sections_ids_has_sections_ids_sections_ids2` FOREIGN KEY (`related_section`) REFERENCES `sections` (`section_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `related_websites_translation`
--
ALTER TABLE `related_websites_translation`
  ADD CONSTRAINT `fk_related_website_translation_1` FOREIGN KEY (`website_id`) REFERENCES `related_websites` (`website_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `reset_passwods`
--
ALTER TABLE `reset_passwods`
  ADD CONSTRAINT `fk_reset_passwods_users1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `roles`
--
ALTER TABLE `roles`
  ADD CONSTRAINT `fk_roles_roles1` FOREIGN KEY (`parent_role_id`) REFERENCES `roles` (`role_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `sections`
--
ALTER TABLE `sections`
  ADD CONSTRAINT `fk_sections_sections` FOREIGN KEY (`parent_section`) REFERENCES `sections` (`section_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `sections_issues`
--
ALTER TABLE `sections_issues`
  ADD CONSTRAINT `fk_sections_has_issues_issues1` FOREIGN KEY (`issue_id`) REFERENCES `issues` (`issue_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_sections_has_issues_sections1` FOREIGN KEY (`section_id`) REFERENCES `sections` (`section_id`) ON UPDATE CASCADE;

--
-- Constraints for table `sections_translation`
--
ALTER TABLE `sections_translation`
  ADD CONSTRAINT `fk_sections_master` FOREIGN KEY (`section_id`) REFERENCES `sections` (`section_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_sections_translation_persons1` FOREIGN KEY (`supervisor`) REFERENCES `persons` (`person_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `services_cities`
--
ALTER TABLE `services_cities`
  ADD CONSTRAINT `fk_services_cities_countries1` FOREIGN KEY (`country_code`) REFERENCES `countries` (`code`) ON UPDATE CASCADE;

--
-- Constraints for table `services_cities_translation`
--
ALTER TABLE `services_cities_translation`
  ADD CONSTRAINT `fk_services_cities_names_1` FOREIGN KEY (`city_id`) REFERENCES `services_cities` (`city_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `services_sections`
--
ALTER TABLE `services_sections`
  ADD CONSTRAINT `fk_services_has_sections_sections1` FOREIGN KEY (`section_id`) REFERENCES `sections` (`section_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_services_has_sections_services1` FOREIGN KEY (`service_id`) REFERENCES `services` (`service_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `sms_videos_translation`
--
ALTER TABLE `sms_videos_translation`
  ADD CONSTRAINT `fk_sms_videos_translation_1` FOREIGN KEY (`video_id`) REFERENCES `sms_videos` (`video_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `system_attributes`
--
ALTER TABLE `system_attributes`
  ADD CONSTRAINT `fk_system_attributes_modules1` FOREIGN KEY (`module_id`) REFERENCES `modules` (`module_id`) ON UPDATE CASCADE;

--
-- Constraints for table `system_attributes_translation`
--
ALTER TABLE `system_attributes_translation`
  ADD CONSTRAINT `fk_system_attributes_copy1_system_attributes1` FOREIGN KEY (`attribute_id`) REFERENCES `system_attributes` (`attribute_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tenders`
--
ALTER TABLE `tenders`
  ADD CONSTRAINT `fk_tenders_departments` FOREIGN KEY (`department_id`) REFERENCES `tenders_department` (`department_id`) ON UPDATE CASCADE;

--
-- Constraints for table `tenders_activities_translation`
--
ALTER TABLE `tenders_activities_translation`
  ADD CONSTRAINT `fk_tenders_activities_translation_1` FOREIGN KEY (`activity_id`) REFERENCES `tenders_activities` (`activity_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tenders_comments`
--
ALTER TABLE `tenders_comments`
  ADD CONSTRAINT `fk_tenders_has_comments_comments1` FOREIGN KEY (`comment_id`) REFERENCES `comments` (`comment_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_tenders_has_comments_tenders1` FOREIGN KEY (`tender_id`) REFERENCES `tenders` (`tender_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tenders_department`
--
ALTER TABLE `tenders_department`
  ADD CONSTRAINT `fk_tenDep_dir_departments` FOREIGN KEY (`parent_department`) REFERENCES `tenders_department` (`department_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `tenders_department_translation`
--
ALTER TABLE `tenders_department_translation`
  ADD CONSTRAINT `fk_ten_department_translation` FOREIGN KEY (`department_id`) REFERENCES `tenders_department` (`department_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tenders_has_activities`
--
ALTER TABLE `tenders_has_activities`
  ADD CONSTRAINT `fk_activities_has_tenders` FOREIGN KEY (`tender_id`) REFERENCES `tenders` (`tender_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_tenders_has_activities` FOREIGN KEY (`activity_id`) REFERENCES `tenders_activities` (`activity_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tenders_translation`
--
ALTER TABLE `tenders_translation`
  ADD CONSTRAINT `fk_tender_translation_tender10` FOREIGN KEY (`tender_id`) REFERENCES `tenders` (`tender_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_users_persons` FOREIGN KEY (`user_id`) REFERENCES `persons` (`person_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_users_roles1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`role_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `users_access_rights`
--
ALTER TABLE `users_access_rights`
  ADD CONSTRAINT `fk_users_access_rights_access_rights1` FOREIGN KEY (`role_id`, `controller_id`) REFERENCES `access_rights` (`role_id`, `controller_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_users_has_access_rights_users1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `users_articles`
--
ALTER TABLE `users_articles`
  ADD CONSTRAINT `fk_users_articles_articles` FOREIGN KEY (`article_id`) REFERENCES `articles` (`article_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_users_articles_users1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `users_cv`
--
ALTER TABLE `users_cv`
  ADD CONSTRAINT `fk_jobs_requests_users10` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON UPDATE CASCADE;

--
-- Constraints for table `users_cv_has_jobs`
--
ALTER TABLE `users_cv_has_jobs`
  ADD CONSTRAINT `fk_users_cv_has_jobs_jobs1` FOREIGN KEY (`job_id`) REFERENCES `jobs` (`job_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_users_cv_has_jobs_users_cv1` FOREIGN KEY (`cv_id`) REFERENCES `users_cv` (`cv_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `users_cv_translation`
--
ALTER TABLE `users_cv_translation`
  ADD CONSTRAINT `fk_users_cv_copy1_users_cv1` FOREIGN KEY (`cv_id`) REFERENCES `users_cv` (`cv_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `users_log`
--
ALTER TABLE `users_log`
  ADD CONSTRAINT `fk_users_log_users1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_users_log_user_actions` FOREIGN KEY (`action_id`) REFERENCES `actions` (`action_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `users_workflow_log`
--
ALTER TABLE `users_workflow_log`
  ADD CONSTRAINT `fk_users_has_workflow_tasks_users1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_users_has_workflow_tasks_workflow_tasks1` FOREIGN KEY (`tasks_id`) REFERENCES `workflow_tasks` (`task_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `videos`
--
ALTER TABLE `videos`
  ADD CONSTRAINT `fk_videos_galleries1` FOREIGN KEY (`gallery_id`) REFERENCES `galleries` (`gallery_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_videos_users1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON UPDATE CASCADE;

--
-- Constraints for table `videos_comments`
--
ALTER TABLE `videos_comments`
  ADD CONSTRAINT `fk_videos_comments_comments1` FOREIGN KEY (`video_comment_id`) REFERENCES `comments` (`comment_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_videos_videos_comments` FOREIGN KEY (`video_id`) REFERENCES `videos` (`video_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `videos_translation`
--
ALTER TABLE `videos_translation`
  ADD CONSTRAINT `fk_videos_translation_1` FOREIGN KEY (`video_id`) REFERENCES `videos` (`video_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `voters`
--
ALTER TABLE `voters`
  ADD CONSTRAINT `fk_voters_users1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_votes_options1` FOREIGN KEY (`option_id`, `content_lang`) REFERENCES `votes_options` (`option_id`, `content_lang`) ON UPDATE CASCADE;

--
-- Constraints for table `votes_options`
--
ALTER TABLE `votes_options`
  ADD CONSTRAINT `fk_poll_options_const` FOREIGN KEY (`ques_id`, `content_lang`) REFERENCES `votes_questions_translation` (`ques_id`, `content_lang`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `votes_questions_translation`
--
ALTER TABLE `votes_questions_translation`
  ADD CONSTRAINT `fk_votes_questions_translation_1` FOREIGN KEY (`ques_id`) REFERENCES `votes_questions` (`ques_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `weather_cities`
--
ALTER TABLE `weather_cities`
  ADD CONSTRAINT `fk_weather_cities_services_cities1` FOREIGN KEY (`city_id`) REFERENCES `services_cities` (`city_id`) ON UPDATE CASCADE;

--
-- Constraints for table `workflow`
--
ALTER TABLE `workflow`
  ADD CONSTRAINT `fk_workflow_modules1` FOREIGN KEY (`module_id`) REFERENCES `modules` (`module_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `workflow_actions`
--
ALTER TABLE `workflow_actions`
  ADD CONSTRAINT `fk_workflow_actions_workflow_steps1` FOREIGN KEY (`step_id`) REFERENCES `workflow_steps` (`step_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_workflow_has_actions_actions1` FOREIGN KEY (`action_id`) REFERENCES `actions` (`action_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `workflow_comments`
--
ALTER TABLE `workflow_comments`
  ADD CONSTRAINT `fk_comments_comments10` FOREIGN KEY (`comment_review`) REFERENCES `workflow_comments` (`comment_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_workflow_comments_workflow_tasks1` FOREIGN KEY (`from_task`) REFERENCES `workflow_tasks` (`task_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_workflow_comments_workflow_tasks2` FOREIGN KEY (`to_task`) REFERENCES `workflow_tasks` (`task_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `workflow_roles`
--
ALTER TABLE `workflow_roles`
  ADD CONSTRAINT `fk_workflow_has_roles_roles1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`role_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_workflow_roles_workflow_steps1` FOREIGN KEY (`step_id`) REFERENCES `workflow_steps` (`step_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `workflow_steps`
--
ALTER TABLE `workflow_steps`
  ADD CONSTRAINT `fk_workflow_steps_workflow1` FOREIGN KEY (`flow_id`) REFERENCES `workflow` (`flow_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `workflow_tasks`
--
ALTER TABLE `workflow_tasks`
  ADD CONSTRAINT `fk_workflow_tasks_workflow_steps1` FOREIGN KEY (`step_id`) REFERENCES `workflow_steps` (`step_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_workflow_tasks_workflow_tasks1` FOREIGN KEY (`return_from`) REFERENCES `workflow_tasks` (`task_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `workflow_users`
--
ALTER TABLE `workflow_users`
  ADD CONSTRAINT `fk_workflow_has_users_users1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_workflow_users_workflow_steps1` FOREIGN KEY (`step_id`) REFERENCES `workflow_steps` (`step_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `writers`
--
ALTER TABLE `writers`
  ADD CONSTRAINT `fk_writers_persons` FOREIGN KEY (`writer_id`) REFERENCES `persons` (`person_id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;