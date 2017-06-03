-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Nov 09, 2016 at 03:36 PM
-- Server version: 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `arvdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_admin`
--

CREATE TABLE IF NOT EXISTS `tbl_admin` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `FName` varchar(100) NOT NULL,
  `LName` varchar(100) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Phone` varchar(100) NOT NULL,
  `UserName` varchar(500) NOT NULL,
  `Password` varchar(1000) NOT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_category`
--

CREATE TABLE IF NOT EXISTS `tbl_category` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Name` int(11) NOT NULL,
  `Description` int(11) NOT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_group`
--

CREATE TABLE IF NOT EXISTS `tbl_group` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(100) NOT NULL,
  `Description` text NOT NULL,
  `Judge_Id` int(11) NOT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_image`
--

CREATE TABLE IF NOT EXISTS `tbl_image` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Image_Path` varchar(500) NOT NULL,
  `Caption` varchar(100) NOT NULL,
  `Description` text NOT NULL,
  `Category` int(11) NOT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=27 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_image_judge_sketch`
--

CREATE TABLE IF NOT EXISTS `tbl_image_judge_sketch` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Judge_Id` int(11) NOT NULL,
  `Sketch_Id` int(11) NOT NULL,
  `Image_Id_Up` int(11) NOT NULL,
  `Image_Id_Down` int(11) NOT NULL,
  `Rate_Up` double NOT NULL,
  `Confidence_Value_Up` double NOT NULL,
  `Rate_Down` double NOT NULL,
  `Confidence_Value_Down` int(11) NOT NULL,
  `Date_Time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Direction` int(11) NOT NULL,
  `Project_Id` int(11) NOT NULL,
  PRIMARY KEY (`Id`,`Judge_Id`,`Sketch_Id`,`Image_Id_Up`,`Image_Id_Down`,`Date_Time`),
  UNIQUE KEY `Id` (`Id`),
  KEY `Judge_Id` (`Judge_Id`),
  KEY `Image_Id_Up` (`Image_Id_Up`),
  KEY `Image_Id_Down` (`Image_Id_Down`),
  KEY `Sketch_Id` (`Sketch_Id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_judge`
--

CREATE TABLE IF NOT EXISTS `tbl_judge` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `FName` varchar(100) NOT NULL,
  `LName` varchar(100) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Phone` varchar(100) NOT NULL,
  `Group_Id` int(11) NOT NULL,
  `UserName` varchar(500) NOT NULL,
  `Password` varchar(1000) NOT NULL,
  PRIMARY KEY (`Id`),
  UNIQUE KEY `Group_Id` (`Group_Id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_judge_group`
--

CREATE TABLE IF NOT EXISTS `tbl_judge_group` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Judge_Id` int(11) NOT NULL,
  `Project_Id` int(11) NOT NULL,
  `Date` timestamp NOT NULL,
  PRIMARY KEY (`Id`,`Judge_Id`,`Project_Id`),
  KEY `Judge_Id` (`Judge_Id`),
  KEY `Project_Id` (`Project_Id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=39 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_msg`
--

CREATE TABLE IF NOT EXISTS `tbl_msg` (
  `idMsg` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '',
  `body` text NOT NULL,
  `sender` int(11) NOT NULL DEFAULT '0',
  `receiver` int(11) NOT NULL DEFAULT '0',
  `role` varchar(55) NOT NULL DEFAULT '0',
  `readed` int(11) NOT NULL DEFAULT '0',
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`idMsg`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_project`
--

CREATE TABLE IF NOT EXISTS `tbl_project` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(500) NOT NULL,
  `Question` varchar(1000) NOT NULL,
  `Date_Time` datetime NOT NULL,
  `Online_Trade_Direction` varchar(50) NOT NULL,
  `Revenue` varchar(55) NOT NULL,
  `Judge_Direction` varchar(50) NOT NULL DEFAULT 'not determined yet',
  `Investment` varchar(55) NOT NULL,
  `Upload_Deadline` varchar(55) NOT NULL,
  `Notification_Time` varchar(55) NOT NULL,
  `Exit_Date_Time` varchar(55) NOT NULL,
  `Place_Date_Time` varchar(55) NOT NULL,
  `Judge_Deadline` varchar(55) NOT NULL,
  `Status` int(11) NOT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=29 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_project_judge`
--

CREATE TABLE IF NOT EXISTS `tbl_project_judge` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Judge_Id` int(11) NOT NULL,
  `Project_Id` int(11) NOT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_project_viewer`
--

CREATE TABLE IF NOT EXISTS `tbl_project_viewer` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Viewer_Id` int(11) NOT NULL,
  `Project_Id` int(11) NOT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_setting`
--

CREATE TABLE IF NOT EXISTS `tbl_setting` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Judge_Deadline` varchar(55) DEFAULT NULL,
  `Upload_Deadline` varchar(55) DEFAULT NULL,
  `Notification_Time` varchar(55) DEFAULT NULL,
  `Exit_Date_Time` varchar(55) DEFAULT NULL,
  `Place_Date_Time` varchar(55) DEFAULT NULL,
  `Investment` varchar(55) DEFAULT NULL,
  `Project_Id` int(11) NOT NULL,
  PRIMARY KEY (`Id`),
  UNIQUE KEY `Project_Id` (`Project_Id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=14 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_sketch`
--

CREATE TABLE IF NOT EXISTS `tbl_sketch` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Image_Path` varchar(500) NOT NULL,
  `Caption` varchar(100) NOT NULL,
  `Description` text NOT NULL,
  `Viewer_Id` int(11) NOT NULL,
  `Date_Time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`Id`,`Viewer_Id`),
  UNIQUE KEY `Viewer_Id` (`Viewer_Id`),
  UNIQUE KEY `Id_2` (`Id`),
  KEY `Id` (`Id`,`Viewer_Id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_trade`
--

CREATE TABLE IF NOT EXISTS `tbl_trade` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Item` varchar(500) NOT NULL,
  `Place_Date_Time` datetime NOT NULL,
  `Exit_Date_Time` datetime NOT NULL,
  `Direction_Id` int(11) NOT NULL,
  `Investment` double NOT NULL,
  `Result` int(11) NOT NULL,
  `Revenue` double NOT NULL,
  PRIMARY KEY (`Id`),
  UNIQUE KEY `Direction_Id` (`Direction_Id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_users`
--

CREATE TABLE IF NOT EXISTS `tbl_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(25) NOT NULL,
  `last_name` varchar(25) NOT NULL,
  `email_id` varchar(50) NOT NULL,
  `contact_no` bigint(10) NOT NULL,
  `user_role` varchar(50) NOT NULL,
  `user_name` varchar(20) NOT NULL,
  `password` varchar(1000) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user_account`
--

CREATE TABLE IF NOT EXISTS `tbl_user_account` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `User_Id` int(11) NOT NULL,
  `User_Name` varchar(500) NOT NULL,
  `Password` varchar(1000) NOT NULL,
  `User_Type` varchar(100) NOT NULL,
  PRIMARY KEY (`Id`,`User_Id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_viewer`
--

CREATE TABLE IF NOT EXISTS `tbl_viewer` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `FName` varchar(100) NOT NULL,
  `LName` varchar(100) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Phone` varchar(100) NOT NULL,
  `Group_Id` int(11) NOT NULL,
  `UserName` varchar(500) NOT NULL,
  `Password` varchar(1000) NOT NULL,
  PRIMARY KEY (`Id`),
  UNIQUE KEY `Group_Id` (`Group_Id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_viewer_group`
--

CREATE TABLE IF NOT EXISTS `tbl_viewer_group` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Viewer_Id` int(11) NOT NULL,
  `Project_Id` int(11) NOT NULL,
  `Date` timestamp NOT NULL,
  PRIMARY KEY (`Id`),
  KEY `Viewer_Id` (`Viewer_Id`,`Project_Id`),
  KEY `Group_Id` (`Project_Id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=34 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_viewer_sketch`
--

CREATE TABLE IF NOT EXISTS `tbl_viewer_sketch` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Image_Path` varchar(500) NOT NULL,
  `Caption` varchar(100) NOT NULL,
  `Description` text NOT NULL,
  `Viewer_ID` int(11) NOT NULL,
  `Project_Id` int(11) NOT NULL,
  `Date_Time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Table structure for table `testtable`
--

CREATE TABLE IF NOT EXISTS `testtable` (
  `int` int(11) NOT NULL,
  `judid` int(11) NOT NULL,
  `sketchid` int(11) NOT NULL,
  `direction` int(11) NOT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tbl_judge`
--
ALTER TABLE `tbl_judge`
  ADD CONSTRAINT `tbl_judge_ibfk_1` FOREIGN KEY (`Group_Id`) REFERENCES `tbl_group` (`Id`);

--
-- Constraints for table `tbl_setting`
--
ALTER TABLE `tbl_setting`
  ADD CONSTRAINT `tbl_setting_ibfk_1` FOREIGN KEY (`Project_Id`) REFERENCES `tbl_project` (`Id`);

--
-- Constraints for table `tbl_sketch`
--
ALTER TABLE `tbl_sketch`
  ADD CONSTRAINT `tbl_sketch_ibfk_1` FOREIGN KEY (`Viewer_Id`) REFERENCES `tbl_viewer` (`Id`);

--
-- Constraints for table `tbl_trade`
--
ALTER TABLE `tbl_trade`
  ADD CONSTRAINT `tbl_trade_ibfk_1` FOREIGN KEY (`Direction_Id`) REFERENCES `tbl_image_judge_sketch` (`Id`);

--
-- Constraints for table `tbl_viewer`
--
ALTER TABLE `tbl_viewer`
  ADD CONSTRAINT `tbl_viewer_ibfk_1` FOREIGN KEY (`Group_Id`) REFERENCES `tbl_group` (`Id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
