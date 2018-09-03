-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 30, 2016 at 12:23 AM
-- Server version: 5.7.14
-- PHP Version: 5.6.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `d14_field_flags`
--
CREATE DATABASE IF NOT EXISTS `field_flags` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;
USE `field_flags`;

-- --------------------------------------------------------

--
-- Table structure for table `desiredstatus`
--

CREATE TABLE `desiredstatus` (
  `flagid` int(11) NOT NULL,
  `mode` tinyint(4) NOT NULL DEFAULT '0',
  `owner` tinyint(4) NOT NULL DEFAULT '0',
  `enabled` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Stores Flag Data';

--
-- Dumping data for table `desiredstatus`
--

INSERT INTO `desiredstatus` (`flagid`, `mode`, `owner`, `enabled`) VALUES
(1, 2, 5, 1),
(2, 0, 0, 0),
(3, 0, 5, 0),
(4, 0, 0, 0),
(5, 0, 0, 0),
(6, 0, 0, 0),
(7, 0, 0, 0),
(8, 0, 0, 0),
(9, 0, 0, 0),
(10, 0, 0, 0),
(11, 0, 0, 0),
(12, 0, 0, 0),
(13, 0, 0, 0),
(14, 0, 0, 0),
(15, 0, 0, 0),
(16, 0, 0, 0),
(17, 0, 0, 0),
(18, 0, 0, 0),
(19, 0, 0, 0),
(20, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `flagstatus`
--

CREATE TABLE `flagstatus` (
  `flagid` int(11) NOT NULL,
  `name` varchar(128) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Flag',
  `flagdesc` varchar(1024) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Located at ',
  `mode` tinyint(4) NOT NULL DEFAULT '0',
  `owner` tinyint(4) NOT NULL DEFAULT '0',
  `battery` tinyint(4) NOT NULL DEFAULT '0',
  `lastseen` int(11) NOT NULL DEFAULT '0',
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  `greentime` bigint(20) NOT NULL DEFAULT '0',
  `tantime` bigint(20) NOT NULL DEFAULT '0',
  `bluetime` bigint(20) NOT NULL DEFAULT '0',
  `button` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Stores Flag Data';

--
-- Dumping data for table `flagstatus`
--

INSERT INTO `flagstatus` (`flagid`, `name`, `flagdesc`, `mode`, `owner`, `battery`, `lastseen`, `enabled`, `greentime`, `tantime`, `bluetime`, `button`) VALUES
(1, 'Flag 1', 'Located at end of firing range.', 2, 1, 45, 0, 1, 0, 0, 0, 0),
(2, 'Flag 2', 'Located at center of town, next to truck on dirt hill.', 0, 0, 0, 0, 0, 0, 0, 0, 0),
(3, 'Flag 3', 'Located between Comms and creek .', 0, 0, 0, 0, 0, 0, 0, 0, 0),
(4, 'Flag 4', 'Located in hayfield south west.', 0, 0, 0, 0, 0, 0, 0, 0, 0),
(5, 'Flag 5', 'Located west of mansion.', 0, 0, 0, 0, 0, 0, 0, 0, 0),
(6, 'Flag 6', 'Located north of town at green plane.', 0, 0, 0, 0, 0, 0, 0, 0, 0),
(7, 'Flag 7', 'Located between tents and cedar building.', 0, 0, 0, 0, 0, 0, 0, 0, 0),
(8, 'Flag 8', 'Located in woods west of and near the creek.', 0, 0, 0, 0, 0, 0, 0, 0, 0),
(9, 'Flag 9', 'Extreme north-east end of creek.', 0, 0, 0, 0, 0, 0, 0, 0, 0),
(10, 'Flag 10', 'Located between pond and prison near low water creek crossing.', 0, 0, 0, 0, 0, 0, 0, 0, 0),
(11, 'Flag 11', 'Located in woods, top of creek bank, near top of hayfield.', 0, 0, 0, 0, 0, 0, 0, 0, 0),
(12, 'Flag 12', 'Located in woods north of green tents.', 0, 0, 0, 0, 0, 0, 0, 0, 0),
(13, 'Flag 13', 'Located in woods between tents and north fence trail.', 0, 0, 0, 0, 0, 0, 0, 0, 0),
(14, 'Flag 14', 'Located in woods behind mosque.', 0, 0, 0, 0, 0, 0, 0, 0, 0),
(15, 'Flag 15', 'Located in woods behind chop-shop.', 0, 0, 0, 0, 0, 0, 0, 0, 0),
(16, 'Flag 16', 'TBD.', 0, 0, 0, 0, 0, 0, 0, 0, 0),
(17, 'Flag 17', 'TBD.', 0, 0, 0, 0, 0, 0, 0, 0, 0),
(18, 'Flag 18', 'TBD.', 0, 0, 0, 0, 0, 0, 0, 0, 0),
(19, 'Flag 19', 'TBD.', 0, 0, 0, 0, 0, 0, 0, 0, 0),
(20, 'Flag 20', 'TBD.', 0, 0, 0, 0, 0, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `gamemode`
--

CREATE TABLE `gamemode` (
  `gameid` int(1) NOT NULL DEFAULT '0',
  `flagid` int(1) NOT NULL DEFAULT '1',
  `flagmode` int(1) NOT NULL DEFAULT '0',
  `flagowner` int(1) NOT NULL DEFAULT '0',
  `flagenabled` tinyint(1) NOT NULL DEFAULT '0',
  `gamename` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Default Custom Game.'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Stores game mode scenarios.';

--
-- Dumping data for table `gamemode`
--

INSERT INTO `gamemode` (`gameid`, `flagid`, `flagmode`, `flagowner`, `flagenabled`, `gamename`) VALUES
(1, 1, 1, 1, 1, 'Green owns north, tan owns south, standby'),
(1, 2, 1, 2, 1, 'Green owns north, tan owns south, standby'),
(1, 3, 1, 1, 1, 'Green owns north, tan owns south, standby'),
(1, 4, 1, 1, 1, 'Green owns north, tan owns south, standby'),
(1, 5, 1, 2, 1, 'Green owns north, tan owns south, standby'),
(1, 6, 1, 2, 1, 'Green owns north, tan owns south, standby'),
(1, 7, 1, 2, 1, 'Green owns north, tan owns south, standby'),
(1, 8, 1, 1, 1, 'Green owns north, tan owns south, standby'),
(1, 9, 1, 0, 1, 'Green owns north, tan owns south, standby'),
(1, 10, 1, 1, 1, 'Green owns north, tan owns south, standby'),
(1, 11, 1, 1, 1, 'Green owns north, tan owns south, standby'),
(1, 12, 1, 2, 1, 'Green owns north, tan owns south, standby'),
(1, 13, 1, 1, 1, 'Green owns north, tan owns south, standby'),
(1, 14, 1, 2, 1, 'Green owns north, tan owns south, standby'),
(1, 15, 1, 2, 1, 'Green owns north, tan owns south, standby'),
(1, 16, 1, 0, 1, 'Green owns north, tan owns south, standby'),
(1, 17, 1, 0, 1, 'Green owns north, tan owns south, standby'),
(1, 18, 1, 0, 1, 'Green owns north, tan owns south, standby'),
(1, 19, 1, 0, 1, 'Green owns north, tan owns south, standby'),
(1, 20, 1, 0, 1, 'Green owns north, tan owns south, standby'),
(2, 1, 1, 1, 1, 'Green owns south, tan north, standby'),
(2, 2, 1, 1, 1, 'Green owns south, tan north, standby'),
(2, 3, 1, 1, 1, 'Green owns south, tan north, standby'),
(2, 4, 1, 2, 1, 'Green owns south, tan north, standby'),
(2, 5, 1, 1, 1, 'Green owns south, tan north, standby'),
(2, 6, 1, 2, 1, 'Green owns south, tan north, standby'),
(2, 7, 1, 2, 1, 'Green owns south, tan north, standby'),
(2, 8, 1, 2, 1, 'Green owns south, tan north, standby'),
(2, 9, 1, 0, 1, 'Green owns south, tan north, standby'),
(2, 10, 1, 1, 1, 'Green owns south, tan north, standby'),
(2, 11, 1, 2, 1, 'Green owns south, tan north, standby'),
(2, 12, 1, 2, 1, 'Green owns south, tan north, standby'),
(2, 13, 1, 2, 1, 'Green owns south, tan north, standby'),
(2, 14, 1, 1, 1, 'Green owns south, tan north, standby'),
(2, 15, 1, 1, 1, 'Green owns south, tan north, standby'),
(2, 16, 1, 0, 1, 'Green owns south, tan north, standby'),
(2, 17, 1, 0, 1, 'Green owns south, tan north, standby'),
(2, 18, 1, 0, 1, 'Green owns south, tan north, standby'),
(2, 19, 1, 0, 1, 'Green owns south, tan north, standby'),
(2, 20, 1, 0, 1, 'Green owns south, tan north, standby'),
(3, 1, 1, 1, 1, 'Green owns creek, tan owns west'),
(3, 2, 1, 2, 1, 'Green owns creek, tan owns west'),
(3, 3, 1, 1, 1, 'Green owns creek, tan owns west'),
(3, 4, 1, 1, 1, 'Green owns creek, tan owns west'),
(3, 5, 1, 2, 1, 'Green owns creek, tan owns west'),
(3, 6, 1, 2, 1, 'Green owns creek, tan owns west'),
(3, 7, 1, 2, 1, 'Green owns creek, tan owns west'),
(3, 8, 1, 1, 1, 'Green owns creek, tan owns west'),
(3, 9, 1, 0, 1, 'Green owns creek, tan owns west'),
(3, 10, 1, 1, 1, 'Green owns creek, tan owns west'),
(3, 11, 1, 1, 1, 'Green owns creek, tan owns west'),
(3, 12, 1, 2, 1, 'Green owns creek, tan owns west'),
(3, 13, 1, 1, 1, 'Green owns creek, tan owns west'),
(3, 14, 1, 2, 1, 'Green owns creek, tan owns west'),
(3, 15, 1, 2, 1, 'Green owns creek, tan owns west'),
(3, 16, 1, 0, 1, 'Green owns creek, tan owns west'),
(3, 17, 1, 0, 1, 'Green owns creek, tan owns west'),
(3, 18, 1, 0, 1, 'Green owns creek, tan owns west'),
(3, 19, 1, 0, 1, 'Green owns creek, tan owns west'),
(3, 20, 1, 0, 1, 'Green owns creek, tan owns west'),
(4, 1, 1, 0, 1, 'Game 4'),
(4, 2, 1, 0, 1, 'Game 4'),
(4, 3, 1, 0, 1, 'Game 4'),
(4, 4, 1, 0, 1, 'Game 4'),
(4, 5, 1, 0, 1, 'Game 4'),
(4, 6, 1, 0, 1, 'Game 4'),
(4, 7, 1, 0, 1, 'Game 4'),
(4, 8, 1, 0, 1, 'Game 4'),
(4, 9, 1, 0, 1, 'Game 4'),
(4, 10, 1, 0, 1, 'Game 4'),
(4, 11, 1, 0, 1, 'Game 4'),
(4, 12, 1, 0, 1, 'Game 4'),
(4, 13, 1, 0, 1, 'Game 4'),
(4, 14, 1, 0, 1, 'Game 4'),
(4, 15, 1, 0, 1, 'Game 4'),
(4, 16, 1, 0, 1, 'Game 4'),
(4, 17, 1, 0, 1, 'Game 4'),
(4, 18, 1, 0, 1, 'Game 4'),
(4, 19, 1, 0, 1, 'Game 4'),
(4, 20, 1, 0, 1, 'Game 4'),
(5, 1, 1, 0, 1, 'Game 5'),
(5, 2, 1, 0, 1, 'Game 5'),
(5, 3, 1, 0, 1, 'Game 5'),
(5, 4, 1, 0, 1, 'Game 5'),
(5, 5, 1, 0, 1, 'Game 5'),
(5, 6, 1, 0, 1, 'Game 5'),
(5, 7, 1, 0, 1, 'Game 5'),
(5, 8, 1, 0, 1, 'Game 5'),
(5, 9, 1, 0, 1, 'Game 5'),
(5, 10, 1, 0, 1, 'Game 5'),
(5, 11, 1, 0, 1, 'Game 5'),
(5, 12, 1, 0, 1, 'Game 5'),
(5, 13, 1, 0, 1, 'Game 5'),
(5, 14, 1, 0, 1, 'Game 5'),
(5, 15, 1, 0, 1, 'Game 5'),
(5, 16, 1, 0, 1, 'Game 5'),
(5, 17, 1, 0, 1, 'Game 5'),
(5, 18, 1, 0, 1, 'Game 5'),
(5, 19, 1, 0, 1, 'Game 5'),
(5, 20, 1, 0, 1, 'Game 5'),
(6, 1, 1, 0, 1, 'Game 6'),
(6, 2, 1, 0, 1, 'Game 6'),
(6, 3, 1, 0, 1, 'Game 6'),
(6, 4, 1, 0, 1, 'Game 6'),
(6, 5, 1, 0, 1, 'Game 6'),
(6, 6, 1, 0, 1, 'Game 6'),
(6, 7, 1, 0, 1, 'Game 6'),
(6, 8, 1, 0, 1, 'Game 6'),
(6, 9, 1, 0, 1, 'Game 6'),
(6, 10, 1, 0, 1, 'Game 6'),
(6, 11, 1, 0, 1, 'Game 6'),
(6, 12, 1, 0, 1, 'Game 6'),
(6, 13, 1, 0, 1, 'Game 6'),
(6, 14, 1, 0, 1, 'Game 6'),
(6, 15, 1, 0, 1, 'Game 6'),
(6, 16, 1, 0, 1, 'Game 6'),
(6, 17, 1, 0, 1, 'Game 6'),
(6, 18, 1, 0, 1, 'Game 6'),
(6, 19, 1, 0, 1, 'Game 6'),
(6, 20, 1, 0, 1, 'Game 6'),
(7, 1, 1, 0, 1, 'Game 7'),
(7, 2, 1, 0, 1, 'Game 7'),
(7, 3, 1, 0, 1, 'Game 7'),
(7, 4, 1, 0, 1, 'Game 7'),
(7, 5, 1, 0, 1, 'Game 7'),
(7, 6, 1, 0, 1, 'Game 7'),
(7, 7, 1, 0, 1, 'Game 7'),
(7, 8, 1, 0, 1, 'Game 7'),
(7, 9, 1, 0, 1, 'Game 7'),
(7, 10, 1, 0, 1, 'Game 7'),
(7, 11, 1, 0, 1, 'Game 7'),
(7, 12, 1, 0, 1, 'Game 7'),
(7, 13, 1, 0, 1, 'Game 7'),
(7, 14, 1, 0, 1, 'Game 7'),
(7, 15, 1, 0, 1, 'Game 7'),
(7, 16, 1, 0, 1, 'Game 7'),
(7, 17, 1, 0, 1, 'Game 7'),
(7, 18, 1, 0, 1, 'Game 7'),
(7, 19, 1, 0, 1, 'Game 7'),
(7, 20, 1, 0, 1, 'Game 7'),
(8, 1, 1, 0, 1, 'Game 8'),
(8, 2, 1, 0, 1, 'Game 8'),
(8, 3, 1, 0, 1, 'Game 8'),
(8, 4, 1, 0, 1, 'Game 8'),
(8, 5, 1, 0, 1, 'Game 8'),
(8, 6, 1, 0, 1, 'Game 8'),
(8, 7, 1, 0, 1, 'Game 8'),
(8, 8, 1, 0, 1, 'Game 8'),
(8, 9, 1, 0, 1, 'Game 8'),
(8, 10, 1, 0, 1, 'Game 8'),
(8, 11, 1, 0, 1, 'Game 8'),
(8, 12, 1, 0, 1, 'Game 8'),
(8, 13, 1, 0, 1, 'Game 8'),
(8, 14, 1, 0, 1, 'Game 8'),
(8, 15, 1, 0, 1, 'Game 8'),
(8, 16, 1, 0, 1, 'Game 8'),
(8, 17, 1, 0, 1, 'Game 8'),
(8, 18, 1, 0, 1, 'Game 8'),
(8, 19, 1, 0, 1, 'Game 8'),
(8, 20, 1, 0, 1, 'Game 8'),
(9, 1, 1, 0, 1, 'Game 9'),
(9, 2, 1, 0, 1, 'Game 9'),
(9, 3, 1, 0, 1, 'Game 9'),
(9, 4, 1, 0, 1, 'Game 9'),
(9, 5, 1, 0, 1, 'Game 9'),
(9, 6, 1, 0, 1, 'Game 9'),
(9, 7, 1, 0, 1, 'Game 9'),
(9, 8, 1, 0, 1, 'Game 9'),
(9, 9, 1, 0, 1, 'Game 9'),
(9, 10, 1, 0, 1, 'Game 9'),
(9, 11, 1, 0, 1, 'Game 9'),
(9, 12, 1, 0, 1, 'Game 9'),
(9, 13, 1, 0, 1, 'Game 9'),
(9, 14, 1, 0, 1, 'Game 9'),
(9, 15, 1, 0, 1, 'Game 9'),
(9, 16, 1, 0, 1, 'Game 9'),
(9, 17, 1, 0, 1, 'Game 9'),
(9, 18, 1, 0, 1, 'Game 9'),
(9, 19, 1, 0, 1, 'Game 9'),
(9, 20, 1, 0, 1, 'Game 9'),
(10, 1, 1, 0, 1, 'Game 10'),
(10, 2, 1, 0, 1, 'Game 10'),
(10, 3, 1, 0, 1, 'Game 10'),
(10, 4, 1, 0, 1, 'Game 10'),
(10, 5, 1, 0, 1, 'Game 10'),
(10, 6, 1, 0, 1, 'Game 10'),
(10, 7, 1, 0, 1, 'Game 10'),
(10, 8, 1, 0, 1, 'Game 10'),
(10, 9, 1, 0, 1, 'Game 10'),
(10, 10, 1, 0, 1, 'Game 10'),
(10, 11, 1, 0, 1, 'Game 10'),
(10, 12, 1, 0, 1, 'Game 10'),
(10, 13, 1, 0, 1, 'Game 10'),
(10, 14, 1, 0, 1, 'Game 10'),
(10, 15, 1, 0, 1, 'Game 10'),
(10, 16, 1, 0, 1, 'Game 10'),
(10, 17, 1, 0, 1, 'Game 10'),
(10, 18, 1, 0, 1, 'Game 10'),
(10, 19, 1, 0, 1, 'Game 10'),
(10, 20, 1, 0, 1, 'Game 10');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `desiredstatus`
--
ALTER TABLE `desiredstatus`
  ADD PRIMARY KEY (`flagid`);

--
-- Indexes for table `flagstatus`
--
ALTER TABLE `flagstatus`
  ADD PRIMARY KEY (`flagid`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
