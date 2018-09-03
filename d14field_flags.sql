-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 29, 2016 at 03:49 PM
-- Server version: 5.7.14
-- PHP Version: 5.6.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `d14field_flags`
--

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
(1, 2, 0, 1),
(2, 2, 0, 1),
(3, 2, 0, 1),
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
(14, 1, 0, 1),
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
(1, 'Flag 1', 'Located at end of firing range.', 0, 0, 0, 0, 0, 0, 0, 0, 0),
(2, 'Flag 2', 'Located at center of town, next to truck on dirt hill.', 2, 0, 84, 0, 1, 0, 0, 0, 0),
(3, 'Flag 3', 'Located between Comms and creek .', 2, 0, 95, 0, 1, 0, 0, 0, 0),
(4, 'Flag 4', 'Located in hayfield south west.', 0, 0, 0, 0, 0, 0, 0, 0, 0),
(5, 'Flag 5', 'Located west of mansion.', 0, 0, 0, 0, 0, 0, 0, 0, 0),
(6, 'Flag 6', 'Located north of town at green plane.', 0, 0, 0, 0, 0, 0, 0, 0, 0),
(7, 'Flag 7', 'Located between tents and cedar building.', 0, 0, 0, 0, 0, 0, 0, 0, 0),
(8, 'Flag 8', 'Located in woods west of and near the creek.', 0, 0, 0, 0, 0, 0, 0, 0, 0),
(9, 'Flag 9', 'TBD.', 0, 0, 0, 0, 0, 0, 0, 0, 0),
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
