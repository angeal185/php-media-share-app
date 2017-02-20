-- phpMyAdmin SQL Dump
-- version 4.5.2
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Feb 20, 2017 at 11:26 PM
-- Server version: 5.7.9
-- PHP Version: 5.6.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mediashare`
--

-- --------------------------------------------------------

--
-- Table structure for table `ms_administrators`
--

DROP TABLE IF EXISTS `ms_administrators`;
CREATE TABLE IF NOT EXISTS `ms_administrators` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ms_administrators`
--

INSERT INTO `ms_administrators` (`id`, `user_id`) VALUES
(1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `ms_categories`
--

DROP TABLE IF EXISTS `ms_categories`;
CREATE TABLE IF NOT EXISTS `ms_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category` text CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `ms_categories`
--

INSERT INTO `ms_categories` (`id`, `category`) VALUES
(1, 'Cars & Vehicles'),
(2, 'Comedy'),
(3, 'Education'),
(4, 'Entertainment'),
(5, 'Films & Animation'),
(6, 'Gaming'),
(7, 'How To & Style'),
(8, 'Music'),
(9, 'News & Politics'),
(10, 'People & Blogs'),
(11, 'Pets & Animals'),
(12, 'Sports'),
(13, 'Short Movies'),
(14, 'Movie Trailers'),
(15, 'Science & Technology'),
(16, 'Travel & Events');

-- --------------------------------------------------------

--
-- Table structure for table `ms_comments`
--

DROP TABLE IF EXISTS `ms_comments`;
CREATE TABLE IF NOT EXISTS `ms_comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `video_id` varchar(20) NOT NULL,
  `user_id` text NOT NULL,
  `comment` text NOT NULL,
  `likes` int(11) NOT NULL DEFAULT '0',
  `date` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ms_follows`
--

DROP TABLE IF EXISTS `ms_follows`;
CREATE TABLE IF NOT EXISTS `ms_follows` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `follower_id` int(11) NOT NULL,
  `followers_id` int(11) NOT NULL,
  `date` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `ms_likes`
--

DROP TABLE IF EXISTS `ms_likes`;
CREATE TABLE IF NOT EXISTS `ms_likes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `video_id` varchar(20) NOT NULL,
  `user_id` int(11) NOT NULL,
  `ip` varchar(30) NOT NULL,
  `date` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ms_log`
--

DROP TABLE IF EXISTS `ms_log`;
CREATE TABLE IF NOT EXISTS `ms_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `log` text NOT NULL,
  `ip` varchar(20) NOT NULL,
  `date` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ms_log`
--

INSERT INTO `ms_log` (`id`, `user_id`, `log`, `ip`, `date`) VALUES
(44, 1, 'Login to account.', '::1', 1487536591),
(45, 1, 'Login to account.', '::1', 1487537968),
(46, 1, 'Login to account.', '::1', 1487538296),
(47, 1, 'Logout from account.', '::1', 1487538492),
(48, 1, 'Login to account.', '::1', 1487538811),
(49, 1, 'Login to account.', '::1', 1487600010),
(50, 1, 'Logout from account.', '::1', 1487604279),
(51, 1, 'Login to account.', '::1', 1487604364);

-- --------------------------------------------------------

--
-- Table structure for table `ms_pm`
--

DROP TABLE IF EXISTS `ms_pm`;
CREATE TABLE IF NOT EXISTS `ms_pm` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `from_id` int(11) NOT NULL,
  `to_id` int(11) NOT NULL,
  `title` text NOT NULL,
  `message` text NOT NULL,
  `view` int(11) NOT NULL DEFAULT '0',
  `type` int(11) NOT NULL DEFAULT '0',
  `date` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ms_users`
--

DROP TABLE IF EXISTS `ms_users`;
CREATE TABLE IF NOT EXISTS `ms_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` text NOT NULL,
  `password` text NOT NULL,
  `profile` text NOT NULL,
  `cover` text NOT NULL,
  `email` text NOT NULL,
  `FirstName` text NOT NULL,
  `LastName` text NOT NULL,
  `hash` text,
  `date` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ms_users`
--

INSERT INTO `ms_users` (`id`, `username`, `password`, `profile`, `cover`, `email`, `FirstName`, `LastName`, `hash`, `date`) VALUES
(1, 'admin', 'dce709a8e67f8556abc6999c44de0bd7', 'default.png', 'default.png', 'administrator@videoshare.com', 'Administrator', 'Administrator', NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `ms_videos`
--

DROP TABLE IF EXISTS `ms_videos`;
CREATE TABLE IF NOT EXISTS `ms_videos` (
  `id` varchar(20) NOT NULL,
  `title` text NOT NULL,
  `video_file` text,
  `image_file` text,
  `user_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `description` text NOT NULL,
  `enable_comments` int(11) NOT NULL DEFAULT '1',
  `date` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ms_views`
--

DROP TABLE IF EXISTS `ms_views`;
CREATE TABLE IF NOT EXISTS `ms_views` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `video_id` varchar(20) NOT NULL,
  `ip` varchar(30) NOT NULL,
  `date` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
