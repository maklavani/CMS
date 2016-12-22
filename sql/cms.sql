-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Dec 22, 2016 at 09:07 AM
-- Server version: 5.6.21
-- PHP Version: 5.6.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `cms`
--

-- --------------------------------------------------------

--
-- Table structure for table `dig_article`
--

CREATE TABLE IF NOT EXISTS `dig_article` (
`id` int(11) NOT NULL,
  `title` varchar(500) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `special` tinyint(4) NOT NULL DEFAULT '1',
  `category` int(11) NOT NULL,
  `permission` int(11) NOT NULL DEFAULT '5',
  `content` text,
  `code` varchar(10) NOT NULL,
  `likes` int(11) NOT NULL,
  `likes_id` text,
  `dislikes` int(11) NOT NULL,
  `dislikes_id` text,
  `comments` int(11) NOT NULL DEFAULT '0',
  `comments_verify` int(11) NOT NULL DEFAULT '0',
  `comments_deleted` int(11) NOT NULL DEFAULT '0',
  `tags` text,
  `image` text,
  `meta_tag` varchar(500) NOT NULL,
  `meta_desc` varchar(500) NOT NULL,
  `heading` text,
  `views` int(11) NOT NULL DEFAULT '0',
  `setting` text,
  `edit_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `publish_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `user` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `dig_bank`
--

CREATE TABLE IF NOT EXISTS `dig_bank` (
`id` int(11) NOT NULL,
  `order_number` text,
  `status` int(11) NOT NULL DEFAULT '1',
  `code` varchar(20) NOT NULL,
  `price` int(11) NOT NULL DEFAULT '0',
  `component` varchar(255) NOT NULL,
  `code_product` varchar(40) NOT NULL,
  `category` text,
  `details` text,
  `description` text,
  `orderdate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `accept_orderdate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `update_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `user` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `dig_banned`
--

CREATE TABLE IF NOT EXISTS `dig_banned` (
`id` int(11) NOT NULL,
  `ip` varchar(40) NOT NULL,
  `count` int(11) NOT NULL DEFAULT '1',
  `message` text,
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `expire` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `dig_category`
--

CREATE TABLE IF NOT EXISTS `dig_category` (
`id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `parent` int(11) NOT NULL DEFAULT '0',
  `code` varchar(5) CHARACTER SET latin1 NOT NULL,
  `permission` int(11) NOT NULL DEFAULT '5',
  `setting` text,
  `edit_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `user` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `dig_comments`
--

CREATE TABLE IF NOT EXISTS `dig_comments` (
`id` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `code` varchar(15) NOT NULL,
  `article` int(11) NOT NULL,
  `parent` int(11) NOT NULL DEFAULT '0',
  `likes` int(11) NOT NULL DEFAULT '0',
  `likes_id` text,
  `dislikes` int(11) NOT NULL DEFAULT '0',
  `dislikes_id` text,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `comment` varchar(1000) DEFAULT NULL,
  `publish_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `user` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `dig_components`
--

CREATE TABLE IF NOT EXISTS `dig_components` (
`id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `type` varchar(250) CHARACTER SET latin1 NOT NULL,
  `permission` int(11) NOT NULL DEFAULT '4',
  `setting` text,
  `location` varchar(15) NOT NULL DEFAULT 'site'
) ENGINE=InnoDB AUTO_INCREMENT=108 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `dig_components`
--

INSERT INTO `dig_components` (`id`, `name`, `status`, `type`, `permission`, `setting`, `location`) VALUES
(1, 'سیستم', 0, 'system', 3, NULL, 'administrator'),
(2, 'ورود کاربران', 0, 'signin', 3, NULL, 'administrator'),
(3, 'تنظیمات', 0, 'setting', 1, NULL, 'administrator'),
(4, 'کاربران', 0, 'users', 2, NULL, 'administrator'),
(5, 'منو ها', 0, 'menus', 2, NULL, 'administrator'),
(6, 'محتوا', 0, 'content', 3, '{&quot;article&quot;:{&quot;setting_title&quot;:&quot;0&quot;,&quot;setting_heading&quot;:&quot;1&quot;,&quot;setting_author&quot;:&quot;1&quot;,&quot;setting_publish_date&quot;:&quot;1&quot;,&quot;setting_views&quot;:&quot;1&quot;,&quot;setting_tags&quot;:&quot;1&quot;,&quot;setting_likes&quot;:&quot;1&quot;,&quot;setting_likes_permission&quot;:&quot;0&quot;,&quot;setting_comments&quot;:&quot;1&quot;,&quot;setting_comments_permission&quot;:&quot;1&quot;,&quot;setting_comments_confirmation&quot;:&quot;1&quot;,&quot;setting_code&quot;:&quot;1&quot;,&quot;setting_article_info&quot;:&quot;1&quot;},&quot;category&quot;:{&quot;setting_special&quot;:&quot;1&quot;,&quot;setting_special_related&quot;:&quot;1&quot;,&quot;setting_special_limit&quot;:&quot;3&quot;,&quot;setting_limit&quot;:&quot;10&quot;,&quot;setting_countdesc&quot;:&quot;150&quot;,&quot;setting_pagination&quot;:&quot;0&quot;,&quot;setting_article_heading&quot;:&quot;0&quot;,&quot;setting_newsfeed&quot;:&quot;0&quot;,&quot;setting_sort&quot;:&quot;0&quot;}}', 'administrator'),
(7, 'افزونه ها', 0, 'extension', 2, NULL, 'administrator'),
(8, 'خوراک اخبار', 0, 'newsfeed', 3, NULL, 'administrator'),
(9, 'ارجاع', 0, 'redirects', 3, '{&quot;redirects&quot;:{&quot;setting_status&quot;:&quot;1&quot;}}', 'administrator'),
(101, 'کاربران', 0, 'users', 5, NULL, 'site'),
(102, 'محتوا', 0, 'content', 5, '{&quot;article&quot;:{&quot;setting_title&quot;:&quot;0&quot;,&quot;setting_heading&quot;:&quot;1&quot;,&quot;setting_author&quot;:&quot;1&quot;,&quot;setting_publish_date&quot;:&quot;1&quot;,&quot;setting_views&quot;:&quot;1&quot;,&quot;setting_tags&quot;:&quot;1&quot;,&quot;setting_likes&quot;:&quot;1&quot;,&quot;setting_likes_permission&quot;:&quot;0&quot;,&quot;setting_comments&quot;:&quot;1&quot;,&quot;setting_comments_permission&quot;:&quot;1&quot;,&quot;setting_comments_confirmation&quot;:&quot;1&quot;,&quot;setting_code&quot;:&quot;1&quot;,&quot;setting_article_info&quot;:&quot;1&quot;},&quot;category&quot;:{&quot;setting_special&quot;:&quot;1&quot;,&quot;setting_special_related&quot;:&quot;1&quot;,&quot;setting_special_limit&quot;:&quot;3&quot;,&quot;setting_limit&quot;:&quot;10&quot;,&quot;setting_countdesc&quot;:&quot;150&quot;,&quot;setting_pagination&quot;:&quot;0&quot;,&quot;setting_article_heading&quot;:&quot;0&quot;,&quot;setting_newsfeed&quot;:&quot;0&quot;,&quot;setting_sort&quot;:&quot;0&quot;}}', 'site'),
(103, 'پلاگین ها', 0, 'plugins', 5, NULL, 'site'),
(104, 'خوراک اخبار', 0, 'newsfeed', 5, NULL, 'site'),
(105, 'مشخصات', 0, 'profile', 5, NULL, 'site'),
(106, 'بانک', 0, 'bank', 4, NULL, 'site'),
(107, 'ارجاع', 0, 'redirects', 5, '{&quot;redirects&quot;:{&quot;setting_status&quot;:&quot;1&quot;}}', 'site');

-- --------------------------------------------------------

--
-- Table structure for table `dig_extension`
--

CREATE TABLE IF NOT EXISTS `dig_extension` (
`id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` varchar(40) NOT NULL,
  `lock_key` tinyint(4) NOT NULL DEFAULT '0',
  `update_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `location` varchar(20) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4104 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `dig_extension`
--

INSERT INTO `dig_extension` (`id`, `name`, `type`, `lock_key`, `update_date`, `create_date`, `location`) VALUES
(1, 'system', 'component', 1, '2016-01-05 14:50:51', '2016-01-05 14:50:51', 'administrator'),
(2, 'signin', 'component', 1, '2016-01-05 14:45:38', '2016-01-05 14:45:38', 'administrator'),
(3, 'setting', 'component', 1, '2016-01-05 14:44:12', '2016-01-05 14:44:12', 'administrator'),
(4, 'users', 'component', 1, '2016-01-31 13:10:57', '2016-01-05 14:48:54', 'administrator'),
(5, 'menus', 'component', 1, '2016-01-05 14:24:07', '2016-01-05 14:24:07', 'administrator'),
(6, 'content', 'component', 1, '2016-02-27 10:17:06', '2016-01-05 13:36:28', 'administrator'),
(7, 'extension', 'component', 1, '2016-01-24 11:18:04', '2016-01-05 14:22:10', 'administrator'),
(8, 'newsfeed', 'component', 1, '2016-01-16 18:47:40', '2016-01-05 14:39:26', 'administrator'),
(9, 'redirects', 'component', 1, '2016-10-03 17:57:41', '2016-10-03 17:14:31', 'administrator'),
(101, 'users', 'component', 1, '2016-01-05 12:32:58', '2016-01-05 12:32:58', 'site'),
(102, 'content', 'component', 1, '2016-02-27 10:17:01', '2016-01-05 13:14:25', 'site'),
(103, 'plugins', 'component', 1, '2016-01-05 12:32:47', '2016-01-05 12:32:47', 'site'),
(104, 'newsfeed', 'component', 1, '2016-01-19 15:38:37', '2015-08-13 00:00:00', 'site'),
(105, 'profile', 'component', 1, '2016-01-31 13:02:45', '2016-01-05 12:32:53', 'site'),
(106, 'bank', 'component', 1, '2016-01-05 13:22:01', '2016-01-05 13:22:01', 'site'),
(107, 'redirects', 'component', 1, '2016-10-03 17:03:24', '2016-10-03 17:03:24', 'site'),
(1001, 'url_friendly', 'plugin', 1, '2016-01-05 15:33:30', '2016-01-05 15:33:30', 'site'),
(1002, 'wave', 'plugin', 0, '2016-01-20 16:28:12', '2016-01-05 15:27:00', 'all'),
(1003, 'math', 'plugin', 0, '2016-01-20 16:28:07', '2016-01-05 15:29:49', 'all'),
(2001, 'persian', 'language', 1, '2016-01-05 16:10:39', '2016-01-05 16:10:39', 'all'),
(2002, 'english', 'language', 1, '2016-01-05 16:11:23', '2016-01-05 16:11:23', 'all'),
(3001, 'digarsoo', 'template', 0, '2016-01-04 16:17:57', '2016-01-04 16:17:57', 'administrator'),
(3101, 'digarsoo', 'template', 0, '2016-01-04 16:12:27', '2016-01-04 16:07:11', 'site'),
(4001, 'menu', 'widget', 1, '2016-01-04 14:25:32', '2016-01-04 14:25:32', 'administrator'),
(4002, 'search', 'widget', 1, '2016-01-04 14:33:53', '2016-01-04 14:33:53', 'administrator'),
(4003, 'users', 'widget', 1, '2016-01-04 14:43:31', '2016-01-04 14:43:31', 'administrator'),
(4004, 'visit', 'widget', 1, '2016-01-04 14:44:48', '2016-01-04 14:44:48', 'administrator'),
(4005, 'system', 'widget', 1, '2016-01-04 14:41:40', '2016-01-04 14:41:40', 'administrator'),
(4101, 'menu', 'widget', 1, '2016-01-04 14:21:54', '2016-01-04 14:21:54', 'site'),
(4102, 'login', 'widget', 1, '2016-01-04 14:45:45', '2016-01-04 14:45:45', 'site'),
(4103, 'articles', 'widget', 1, '2016-01-30 15:18:10', '2016-01-04 16:08:27', 'site');

-- --------------------------------------------------------

--
-- Table structure for table `dig_group`
--

CREATE TABLE IF NOT EXISTS `dig_group` (
`id` int(11) NOT NULL,
  `name` varchar(250) NOT NULL,
  `parent` int(11) NOT NULL DEFAULT '1',
  `lock_key` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `dig_group`
--

INSERT INTO `dig_group` (`id`, `name`, `parent`, `lock_key`) VALUES
(1, 'ROOT', 0, 1),
(2, 'PROGRAMMER', 1, 1),
(3, 'MANAGER', 1, 1),
(4, 'EMPLOYEE', 3, 1),
(5, 'REGISTERED', 1, 1),
(6, 'PUBLIC', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `dig_identification`
--

CREATE TABLE IF NOT EXISTS `dig_identification` (
`id` int(11) NOT NULL,
  `code` varchar(100) NOT NULL,
  `section` text NOT NULL,
  `val` varchar(255) NOT NULL,
  `expire` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `dig_jobs`
--

CREATE TABLE IF NOT EXISTS `dig_jobs` (
`id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `event_number` bigint(40) NOT NULL DEFAULT '-1',
  `execute_number` varchar(40) NOT NULL DEFAULT '0',
  `last_execute_status` tinyint(4) NOT NULL DEFAULT '0',
  `next_execute` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `data_store` text,
  `source` varchar(1000) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `dig_jobs`
--

INSERT INTO `dig_jobs` (`id`, `title`, `event_number`, `execute_number`, `last_execute_status`, `next_execute`, `data_store`, `source`) VALUES
(1, 'Root', -1, '28', 0, '2016-11-18 00:00:00', NULL, 'includes/jobs/root.php'),
(2, 'AllVisitUsers', -1, '27', 0, '2016-11-18 00:01:01', NULL, '_digarsoo/components/users/jobs/all_visit.php'),
(3, 'DeleteIPUsers', -1, '22', 0, '2016-11-20 00:11:01', NULL, '_digarsoo/components/users/jobs/delete_ip.php'),
(4, 'IPVisitUsers', -1, '27', 0, '2016-11-18 00:21:01', NULL, '_digarsoo/components/users/jobs/ip_visit.php');

-- --------------------------------------------------------

--
-- Table structure for table `dig_languages`
--

CREATE TABLE IF NOT EXISTS `dig_languages` (
`id` int(11) NOT NULL,
  `name` varchar(250) NOT NULL,
  `type` varchar(255) NOT NULL,
  `label` varchar(250) NOT NULL,
  `abbreviation` varchar(250) NOT NULL,
  `default_administrator` tinyint(4) NOT NULL DEFAULT '0',
  `default_site` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `dig_languages`
--

INSERT INTO `dig_languages` (`id`, `name`, `type`, `label`, `abbreviation`, `default_administrator`, `default_site`) VALUES
(1, 'فارسی', 'persian', 'fa-ir', 'fa', 1, 1),
(2, 'English', 'english', 'en-gb', 'en', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `dig_menu`
--

CREATE TABLE IF NOT EXISTS `dig_menu` (
`id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `alias` varchar(250) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `type` varchar(255) DEFAULT NULL,
  `link` varchar(1024) DEFAULT NULL,
  `languages` varchar(10) NOT NULL DEFAULT 'all',
  `parent` int(11) NOT NULL DEFAULT '0',
  `group_number` int(11) NOT NULL,
  `permission` int(11) NOT NULL DEFAULT '5',
  `index_number` int(11) NOT NULL,
  `icon` varchar(200) DEFAULT NULL,
  `homepage` tinyint(1) DEFAULT '0',
  `robots_index` varchar(10) NOT NULL DEFAULT 'off',
  `robots_follow` varchar(10) NOT NULL DEFAULT 'off',
  `meta_tag` text,
  `meta_description` text,
  `setting` text,
  `location` varchar(20) NOT NULL DEFAULT 'site'
) ENGINE=InnoDB AUTO_INCREMENT=102 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `dig_menu`
--

INSERT INTO `dig_menu` (`id`, `name`, `alias`, `status`, `type`, `link`, `languages`, `parent`, `group_number`, `permission`, `index_number`, `icon`, `homepage`, `robots_index`, `robots_follow`, `meta_tag`, `meta_description`, `setting`, `location`) VALUES
(1, 'CONTROL_PANEL', NULL, 0, 'link', 'index.php', 'all', 0, 1, 5, 1, 'icon-home', 1, 'off', 'off', NULL, NULL, NULL, 'administrator'),
(2, 'SETTING', NULL, 0, 'link', 'index.php?component=setting', 'all', 0, 1, 1, 2, 'icon-setting', 0, 'off', 'off', NULL, NULL, NULL, 'administrator'),
(3, 'USERS', NULL, 0, 'link', '#', 'all', 0, 1, 2, 3, 'icon-users', 0, 'off', 'off', NULL, NULL, NULL, 'administrator'),
(4, 'USERS', NULL, 0, 'link', 'index.php?component=users&amp;view=users', 'all', 3, 1, 2, 1, NULL, 0, 'off', 'off', NULL, NULL, NULL, 'administrator'),
(5, 'USERS_CATEGORY', NULL, 0, 'link', 'index.php?component=users&amp;view=group', 'all', 3, 1, 2, 2, NULL, 0, 'off', 'off', NULL, NULL, NULL, 'administrator'),
(6, 'PERMISSIONS', NULL, 0, 'link', 'index.php?component=users&amp;view=permissions', 'all', 3, 1, 2, 3, NULL, 0, 'off', 'off', NULL, NULL, NULL, 'administrator'),
(7, 'BANNED', NULL, 0, 'link', 'index.php?component=users&amp;view=banned', 'all', 3, 1, 2, 4, NULL, 0, 'off', 'off', NULL, NULL, NULL, 'administrator'),
(8, 'MENUS', NULL, 0, 'link', '#', 'all', 0, 1, 2, 4, 'icon-menu', 0, 'off', 'off', NULL, NULL, NULL, 'administrator'),
(9, 'MENU_GROUPS', NULL, 0, 'link', 'index.php?component=menus&amp;view=groups', 'all', 8, 1, 2, 1, NULL, 0, 'off', 'off', NULL, NULL, NULL, 'administrator'),
(10, 'CONTENT', NULL, 0, 'link', '#', 'all', 0, 1, 3, 5, 'icon-content', 0, 'off', 'off', NULL, NULL, NULL, 'administrator'),
(11, 'ARTICLE', NULL, 0, 'link', 'index.php?component=content&amp;view=article', 'all', 10, 1, 3, 1, NULL, 0, 'off', 'off', NULL, NULL, NULL, 'administrator'),
(12, 'CATEGORY', NULL, 0, 'link', 'index.php?component=content&amp;view=category', 'all', 10, 1, 3, 2, NULL, 0, 'off', 'off', NULL, NULL, NULL, 'administrator'),
(13, 'UPLOAD', NULL, 0, 'link', 'index.php?component=content&amp;view=upload', 'all', 10, 1, 3, 3, NULL, 0, 'off', 'off', NULL, NULL, NULL, 'administrator'),
(14, 'TAGS', NULL, 0, 'link', 'index.php?component=content&amp;view=tags', 'all', 10, 1, 3, 4, NULL, 0, 'off', 'off', NULL, NULL, NULL, 'administrator'),
(15, 'COMMENTS', NULL, 0, 'link', 'index.php?component=content&amp;view=comments', 'all', 10, 1, 3, 5, NULL, 0, 'off', 'off', NULL, NULL, NULL, 'administrator'),
(16, 'EXTENSIONS', NULL, 0, 'link', '#', 'all', 0, 1, 2, 6, 'icon-extension', 0, 'off', 'off', NULL, NULL, NULL, 'administrator'),
(17, 'COMPONENTS', NULL, 0, 'link', 'index.php?component=extension&amp;view=components', 'all', 16, 1, 2, 1, NULL, 0, 'off', 'off', NULL, NULL, NULL, 'administrator'),
(18, 'WIDGETS', NULL, 0, 'link', 'index.php?component=extension&amp;view=widgets', 'all', 16, 1, 2, 2, NULL, 0, 'off', 'off', NULL, NULL, NULL, 'administrator'),
(19, 'TEMPLATES', NULL, 0, 'link', 'index.php?component=extension&amp;view=templates', 'all', 16, 1, 2, 3, NULL, 0, 'off', 'off', NULL, NULL, NULL, 'administrator'),
(20, 'LANGUAGES', NULL, 0, 'link', 'index.php?component=extension&amp;view=languages', 'all', 16, 1, 2, 4, NULL, 0, 'off', 'off', NULL, NULL, NULL, 'administrator'),
(21, 'PLUGINS', NULL, 0, 'link', 'index.php?component=extension&amp;view=plugins', 'all', 16, 1, 2, 5, NULL, 0, 'off', 'off', NULL, NULL, NULL, 'administrator'),
(22, 'SETTING', NULL, 0, 'link', 'index.php?component=extension&amp;view=setting', 'all', 16, 1, 2, 6, NULL, 0, 'off', 'off', NULL, NULL, NULL, 'administrator'),
(23, 'ADDITIONAL_FEATURES', NULL, 0, 'link', '#', 'all', 0, 1, 3, 7, 'icon-cogs', 0, 'off', 'off', NULL, NULL, '{&quot;title&quot;:&quot;&quot;,&quot;show_status&quot;:&quot;1&quot;,&quot;class&quot;:&quot;&quot;}', 'administrator'),
(24, 'NEWS_FEED', NULL, 0, 'link', 'index.php?component=newsfeed&amp;view=newsfeed', 'all', 23, 1, 3, 1, NULL, 0, 'off', 'off', NULL, NULL, '{&quot;title&quot;:&quot;&quot;,&quot;show_status&quot;:&quot;1&quot;,&quot;class&quot;:&quot;&quot;}', 'administrator'),
(25, 'REDIRECTS', NULL, 0, 'link', 'index.php?component=redirects&amp;view=redirects', 'all', 23, 1, 3, 2, NULL, 0, 'off', 'off', NULL, NULL, '{&quot;title&quot;:&quot;&quot;,&quot;show_status&quot;:&quot;1&quot;,&quot;class&quot;:&quot;&quot;}', 'administrator'),
(101, 'صفحه اصلی', 'صفحه-اصلی', 0, 'link', '', 'all', 0, 2, 5, 1, '', 1, 'off', 'off', NULL, NULL, '{&quot;title&quot;:&quot;&quot;,&quot;show_status&quot;:&quot;1&quot;,&quot;class&quot;:&quot;&quot;}', 'site');

-- --------------------------------------------------------

--
-- Table structure for table `dig_menu_group`
--

CREATE TABLE IF NOT EXISTS `dig_menu_group` (
`id` int(11) NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_estonian_ci NOT NULL,
  `location` varchar(100) NOT NULL DEFAULT 'site'
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `dig_menu_group`
--

INSERT INTO `dig_menu_group` (`id`, `name`, `location`) VALUES
(1, 'MAIN_MANU', 'administrator'),
(2, 'MAIN_MANU', 'site');

-- --------------------------------------------------------

--
-- Table structure for table `dig_newsfeed`
--

CREATE TABLE IF NOT EXISTS `dig_newsfeed` (
`id` int(11) NOT NULL,
  `name` varchar(250) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `category` text NOT NULL,
  `count` int(11) DEFAULT '10',
  `image` text NOT NULL,
  `countdesc` int(11) NOT NULL DEFAULT '150',
  `sort` int(11) NOT NULL DEFAULT '0',
  `description` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `dig_permissions`
--

CREATE TABLE IF NOT EXISTS `dig_permissions` (
`id` int(11) NOT NULL,
  `name` varchar(250) NOT NULL,
  `groups` text,
  `lock_key` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `dig_permissions`
--

INSERT INTO `dig_permissions` (`id`, `name`, `groups`, `lock_key`) VALUES
(1, 'PROGRAMMER', '1,2', 1),
(2, 'MANAGER', '1,2,3', 1),
(3, 'EMPLOYEE', '1,2,3,4', 1),
(4, 'REGISTERED', '1,2,3,4,5', 1),
(5, 'PUBLIC', '1,2,3,4,5,6', 1);

-- --------------------------------------------------------

--
-- Table structure for table `dig_plugins`
--

CREATE TABLE IF NOT EXISTS `dig_plugins` (
`id` int(11) NOT NULL,
  `name` varchar(250) NOT NULL,
  `type` varchar(250) NOT NULL,
  `category` varchar(250) NOT NULL,
  `setting` text,
  `location` varchar(20) NOT NULL DEFAULT 'all'
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `dig_plugins`
--

INSERT INTO `dig_plugins` (`id`, `name`, `type`, `category`, `setting`, `location`) VALUES
(1, 'URL کاربر پسند', 'url_friendly', 'system', '[]', 'site'),
(2, 'Captcha موجی', 'wave', 'captcha', '[]', 'all'),
(3, 'Captcha ریاضی', 'math', 'captcha', '[]', 'all');

-- --------------------------------------------------------

--
-- Table structure for table `dig_redirects`
--

CREATE TABLE IF NOT EXISTS `dig_redirects` (
`id` int(11) unsigned NOT NULL COMMENT 'Primary Key',
  `url` varchar(500) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `redirect_to` varchar(500) DEFAULT NULL,
  `views` bigint(40) NOT NULL DEFAULT '0',
  `update_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `dig_tags`
--

CREATE TABLE IF NOT EXISTS `dig_tags` (
`id` int(11) NOT NULL,
  `name` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `dig_templates`
--

CREATE TABLE IF NOT EXISTS `dig_templates` (
`id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `setting` text,
  `location` varchar(40) NOT NULL DEFAULT 'site',
  `showing` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=102 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `dig_templates`
--

INSERT INTO `dig_templates` (`id`, `name`, `type`, `setting`, `location`, `showing`) VALUES
(1, 'دیگرسو', 'digarsoo', '[]', 'administrator', 1),
(101, 'دیگرسو', 'digarsoo', '[]', 'site', 1);

-- --------------------------------------------------------

--
-- Table structure for table `dig_users`
--

CREATE TABLE IF NOT EXISTS `dig_users` (
`id` int(10) NOT NULL,
  `name` varchar(100) NOT NULL,
  `family` varchar(100) NOT NULL,
  `username` varchar(100) NOT NULL,
  `code` varchar(20) NOT NULL,
  `password` varchar(300) DEFAULT NULL,
  `group_number` int(10) NOT NULL DEFAULT '6',
  `email` varchar(100) NOT NULL,
  `mobile` varchar(30) DEFAULT NULL,
  `image` text,
  `ip` varchar(16) DEFAULT NULL,
  `visit` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `register` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `status` tinyint(4) unsigned NOT NULL DEFAULT '1',
  `lock_key` tinyint(4) NOT NULL DEFAULT '0',
  `logged` varchar(100) DEFAULT NULL,
  `logged_admin` varchar(100) DEFAULT NULL,
  `password_edit` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `change_logged` tinyint(4) NOT NULL DEFAULT '0',
  `profile` text
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `dig_users`
--

INSERT INTO `dig_users` (`id`, `name`, `family`, `username`, `code`, `password`, `group_number`, `email`, `mobile`, `image`, `ip`, `visit`, `register`, `status`, `lock_key`, `logged`, `logged_admin`, `password_edit`, `change_logged`, `profile`) VALUES
(1, 'دیگرسو', 'طراحی سایت', 'digarsoo', 'yyBOMBW1EgAn3TKTpPie', 'yR0f2+p9w1VrJ+KYrSjbkiSzQGDosVQdRKa4dhlHZBk=:K8e4gZg4zVxdtNMdDuOOktKZ7RC0+VdC+Y2/G9gtkYA2TFNAyihcbsdBSav8zz9zBDJf0yXQG3NTjKSNbTcFLf/REmK22j/KzrxGRzZ3AeGKRNMLKbxMoL5tQQe+my8hsbtLyb7ht/pCP5vVU3hw178P9+nZxf0arfYfxX04EAM=', 2, 'ho.mohamadi@yahoo.com', '09364911666', '[&quot;&quot;]', '::1', '2016-10-03 17:57:51', '2015-08-01 09:00:00', 0, 1, 'TohZTdSBeUHgXTLv6BPGpifVgfq277Ag7fKn9PaCAwdFfLgnbEUAQgED8IdIBAwEXtqOgTaaZlrBfau1xeRRu1fITJJv8bbAqUEM', 'GhLyKfgZBHU23OkzeKtVDwuBRaG7T5wtHnOcpTatui0mm7bu76rL9Wkgcii2iVk0AGIeCpMaWXOuMELCRGiTxTSZeD2LjJQTv753', '2015-08-24 13:33:13', 0, '{&quot;tel&quot;:&quot;&quot;,&quot;address&quot;:&quot;&quot;,&quot;favorites&quot;:&quot;&quot;}');

-- --------------------------------------------------------

--
-- Table structure for table `dig_users_all_visit`
--

CREATE TABLE IF NOT EXISTS `dig_users_all_visit` (
`id` int(11) NOT NULL,
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `count` int(11) DEFAULT '0',
  `count_all` int(11) DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `dig_users_all_visit`
--

INSERT INTO `dig_users_all_visit` (`id`, `date`, `count`, `count_all`) VALUES
(1, '2015-12-08 00:00:00', 1, 3),
(2, '2015-12-12 00:00:00', 2, 2),
(3, '2015-12-29 00:00:00', 1, 1),
(4, '2015-12-30 00:00:00', 2, 3),
(5, '2016-01-04 00:00:00', 1, 5),
(6, '2016-01-05 00:00:00', 3, 17),
(7, '2016-04-09 00:00:00', 1, 1),
(8, '2016-05-04 00:00:00', 1, 1),
(9, '2016-08-01 00:00:00', 1, 12),
(10, '2016-08-07 00:00:00', 1, 1),
(11, '2016-08-16 00:00:00', 1, 1),
(12, '2016-08-27 00:00:00', 1, 1),
(13, '2016-09-19 00:00:00', 2, 2),
(14, '2016-10-03 00:00:00', 2, 60);

-- --------------------------------------------------------

--
-- Table structure for table `dig_users_ip_visit`
--

CREATE TABLE IF NOT EXISTS `dig_users_ip_visit` (
`id` int(11) NOT NULL,
  `ip` varchar(20) NOT NULL,
  `count` int(11) NOT NULL DEFAULT '1',
  `last_seen` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `dig_users_today_visit`
--

CREATE TABLE IF NOT EXISTS `dig_users_today_visit` (
`id` int(11) NOT NULL,
  `ip` varchar(20) NOT NULL,
  `count` int(11) NOT NULL DEFAULT '1',
  `last_seen` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `dig_users_today_visit`
--

INSERT INTO `dig_users_today_visit` (`id`, `ip`, `count`, `last_seen`) VALUES
(1, '::1', 1, '2016-11-17 12:26:37');

-- --------------------------------------------------------

--
-- Table structure for table `dig_widgets`
--

CREATE TABLE IF NOT EXISTS `dig_widgets` (
`id` int(11) NOT NULL,
  `name` varchar(250) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `show_name` tinyint(4) NOT NULL DEFAULT '1',
  `type` varchar(255) NOT NULL,
  `permission` int(11) NOT NULL DEFAULT '5',
  `position` varchar(250) NOT NULL,
  `setting` text,
  `menus` text,
  `menu_type` tinyint(4) NOT NULL DEFAULT '1',
  `location` varchar(250) NOT NULL DEFAULT 'site',
  `languages` varchar(250) NOT NULL DEFAULT 'all'
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `dig_widgets`
--

INSERT INTO `dig_widgets` (`id`, `name`, `status`, `show_name`, `type`, `permission`, `position`, `setting`, `menus`, `menu_type`, `location`, `languages`) VALUES
(1, 'menu', 0, 1, 'menu', 3, 'menu', '{"menu":"1","title":"0"}', NULL, 1, 'administrator', 'all'),
(2, 'search', 0, 1, 'search', 3, 'search', NULL, NULL, 1, 'administrator', 'all'),
(3, 'users', 0, 1, 'users', 3, 'users', NULL, NULL, 1, 'administrator', 'all'),
(4, 'visit', 0, 1, 'visit', 3, 'visit', '{"number":"30"}', '[&quot;1&quot;]', 2, 'administrator', 'all'),
(5, 'system', 0, 1, 'system', 5, 'system', NULL, '[&quot;1&quot;]', 2, 'administrator', 'all');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `dig_article`
--
ALTER TABLE `dig_article`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dig_bank`
--
ALTER TABLE `dig_bank`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dig_banned`
--
ALTER TABLE `dig_banned`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dig_category`
--
ALTER TABLE `dig_category`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dig_comments`
--
ALTER TABLE `dig_comments`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dig_components`
--
ALTER TABLE `dig_components`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dig_extension`
--
ALTER TABLE `dig_extension`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dig_group`
--
ALTER TABLE `dig_group`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dig_identification`
--
ALTER TABLE `dig_identification`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dig_jobs`
--
ALTER TABLE `dig_jobs`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dig_languages`
--
ALTER TABLE `dig_languages`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dig_menu`
--
ALTER TABLE `dig_menu`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dig_menu_group`
--
ALTER TABLE `dig_menu_group`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dig_newsfeed`
--
ALTER TABLE `dig_newsfeed`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dig_permissions`
--
ALTER TABLE `dig_permissions`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dig_plugins`
--
ALTER TABLE `dig_plugins`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dig_redirects`
--
ALTER TABLE `dig_redirects`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dig_tags`
--
ALTER TABLE `dig_tags`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dig_templates`
--
ALTER TABLE `dig_templates`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dig_users`
--
ALTER TABLE `dig_users`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dig_users_all_visit`
--
ALTER TABLE `dig_users_all_visit`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dig_users_ip_visit`
--
ALTER TABLE `dig_users_ip_visit`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dig_users_today_visit`
--
ALTER TABLE `dig_users_today_visit`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dig_widgets`
--
ALTER TABLE `dig_widgets`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `dig_article`
--
ALTER TABLE `dig_article`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `dig_bank`
--
ALTER TABLE `dig_bank`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `dig_banned`
--
ALTER TABLE `dig_banned`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `dig_category`
--
ALTER TABLE `dig_category`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `dig_comments`
--
ALTER TABLE `dig_comments`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `dig_components`
--
ALTER TABLE `dig_components`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=108;
--
-- AUTO_INCREMENT for table `dig_extension`
--
ALTER TABLE `dig_extension`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4104;
--
-- AUTO_INCREMENT for table `dig_group`
--
ALTER TABLE `dig_group`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `dig_identification`
--
ALTER TABLE `dig_identification`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `dig_jobs`
--
ALTER TABLE `dig_jobs`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `dig_languages`
--
ALTER TABLE `dig_languages`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `dig_menu`
--
ALTER TABLE `dig_menu`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=102;
--
-- AUTO_INCREMENT for table `dig_menu_group`
--
ALTER TABLE `dig_menu_group`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `dig_newsfeed`
--
ALTER TABLE `dig_newsfeed`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `dig_permissions`
--
ALTER TABLE `dig_permissions`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `dig_plugins`
--
ALTER TABLE `dig_plugins`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `dig_redirects`
--
ALTER TABLE `dig_redirects`
MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Primary Key';
--
-- AUTO_INCREMENT for table `dig_tags`
--
ALTER TABLE `dig_tags`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `dig_templates`
--
ALTER TABLE `dig_templates`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=102;
--
-- AUTO_INCREMENT for table `dig_users`
--
ALTER TABLE `dig_users`
MODIFY `id` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `dig_users_all_visit`
--
ALTER TABLE `dig_users_all_visit`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT for table `dig_users_ip_visit`
--
ALTER TABLE `dig_users_ip_visit`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `dig_users_today_visit`
--
ALTER TABLE `dig_users_today_visit`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `dig_widgets`
--
ALTER TABLE `dig_widgets`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
