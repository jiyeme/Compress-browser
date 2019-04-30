-- phpMyAdmin SQL Dump
-- version 4.8.4
-- https://www.phpmyadmin.net/
--
-- 主机： 127.0.0.1:3306
-- 生成日期： 2019-04-30 21:59:57
-- 服务器版本： 5.7.24
-- PHP 版本： 7.2.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 数据库： `browser`
--

-- --------------------------------------------------------

--
-- 表的结构 `browser_books`
--

DROP TABLE IF EXISTS `browser_books`;
CREATE TABLE IF NOT EXISTS `browser_books` (
  `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT,
  `uid` int(5) UNSIGNED NOT NULL DEFAULT '0',
  `title` varchar(120) NOT NULL,
  `url` varchar(250) NOT NULL,
  `nums` int(5) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `browser_caches`
--

DROP TABLE IF EXISTS `browser_caches`;
CREATE TABLE IF NOT EXISTS `browser_caches` (
  `keyid` varchar(20) NOT NULL,
  `content` text NOT NULL,
  `type` enum('1','0') NOT NULL DEFAULT '0',
  `uid` int(5) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`keyid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `browser_cookies`
--

DROP TABLE IF EXISTS `browser_cookies`;
CREATE TABLE IF NOT EXISTS `browser_cookies` (
  `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` mediumint(8) UNSIGNED NOT NULL DEFAULT '0',
  `value` text NOT NULL,
  `domain` varchar(255) NOT NULL,
  `path` varchar(255) NOT NULL,
  `expires` int(11) NOT NULL DEFAULT '0',
  `key` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `host` (`domain`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `browser_copys`
--

DROP TABLE IF EXISTS `browser_copys`;
CREATE TABLE IF NOT EXISTS `browser_copys` (
  `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT,
  `uid` int(5) UNSIGNED NOT NULL,
  `content` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `browser_dns`
--

DROP TABLE IF EXISTS `browser_dns`;
CREATE TABLE IF NOT EXISTS `browser_dns` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `domain` varchar(255) NOT NULL,
  `target` varchar(255) NOT NULL,
  `uid` mediumint(8) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `browser_temps`
--

DROP TABLE IF EXISTS `browser_temps`;
CREATE TABLE IF NOT EXISTS `browser_temps` (
  `uid` mediumint(8) UNSIGNED NOT NULL,
  `key` varchar(200) NOT NULL,
  `value` text NOT NULL,
  `time` int(10) UNSIGNED NOT NULL,
  `type` varchar(20) NOT NULL,
  KEY `k` (`key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `browser_temps_file`
--

DROP TABLE IF EXISTS `browser_temps_file`;
CREATE TABLE IF NOT EXISTS `browser_temps_file` (
  `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `uid` mediumint(8) UNSIGNED NOT NULL,
  `file` varchar(255) NOT NULL,
  `time` int(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `browser_users`
--

DROP TABLE IF EXISTS `browser_users`;
CREATE TABLE IF NOT EXISTS `browser_users` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `pass` varchar(30) NOT NULL,
  `num_time` int(10) UNSIGNED NOT NULL,
  `num_look` int(10) UNSIGNED NOT NULL,
  `num_size_html` int(10) UNSIGNED NOT NULL,
  `num_size_pic` int(10) UNSIGNED NOT NULL,
  `config_pic` enum('0','1','2','3','4','5','6','7','8') NOT NULL,
  `config_wap2wml` enum('0','1','2','3','4') NOT NULL,
  `config_useragent` enum('0','1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18') NOT NULL,
  `config_ipagent` varchar(30) NOT NULL,
  `config_cutpage` int(3) NOT NULL,
  `config_pic_wap` enum('0','1','2','3','4','5','6','7','8') NOT NULL DEFAULT '0',
  `config_ipagent_open` enum('0','1') NOT NULL DEFAULT '0',
  `template_foot` varchar(90) NOT NULL DEFAULT '[book]|[menu][br][size]',
  `space_all` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `space_use` int(10) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`,`name`),
  KEY `name` (`name`),
  KEY `pass` (`pass`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `browser_users_quicklogin`
--

DROP TABLE IF EXISTS `browser_users_quicklogin`;
CREATE TABLE IF NOT EXISTS `browser_users_quicklogin` (
  `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `type` enum('qq','sina') NOT NULL,
  `key` varchar(255) NOT NULL,
  `uid` mediumint(8) UNSIGNED NOT NULL,
  `time` int(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `disk_dir`
--

DROP TABLE IF EXISTS `disk_dir`;
CREATE TABLE IF NOT EXISTS `disk_dir` (
  `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT,
  `uid` int(5) UNSIGNED NOT NULL DEFAULT '0',
  `title` varchar(50) NOT NULL,
  `oid` int(5) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `disk_file`
--

DROP TABLE IF EXISTS `disk_file`;
CREATE TABLE IF NOT EXISTS `disk_file` (
  `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT,
  `uid` int(5) UNSIGNED NOT NULL,
  `title` varchar(50) NOT NULL,
  `mime` varchar(10) DEFAULT '',
  `file` varchar(50) NOT NULL,
  `oid` int(5) UNSIGNED NOT NULL DEFAULT '0',
  `size` int(10) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `oid` (`oid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
