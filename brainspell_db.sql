-- phpMyAdmin SQL Dump
-- version 4.0.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 19, 2015 at 10:20 AM
-- Server version: 5.6.11
-- PHP Version: 5.5.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `brainspell`
--

-- --------------------------------------------------------

--
-- Table structure for table `Articles`
--

CREATE TABLE IF NOT EXISTS `Articles` (
  `UniqueID` int(25) NOT NULL AUTO_INCREMENT,
  `TIMESTAMP` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Title` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `Authors` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `Abstract` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `Reference` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `PMID` varchar(64) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `DOI` varchar(128) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `Experiments` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `Metadata` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`UniqueID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12498 ;

-- --------------------------------------------------------

--
-- Table structure for table `Concepts`
--

CREATE TABLE IF NOT EXISTS `Concepts` (
  `UniqueID` int(25) NOT NULL AUTO_INCREMENT,
  `Name` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `Ontology` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `Definition` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `Metadata` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`UniqueID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10290 ;

-- --------------------------------------------------------

--
-- Table structure for table `Log`
--

CREATE TABLE IF NOT EXISTS `Log` (
  `UniqueID` int(25) NOT NULL AUTO_INCREMENT,
  `TIMESTAMP` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `UserName` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `Experiment` int(11) NOT NULL,
  `PMID` varchar(64) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `Type` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `Data` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`UniqueID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1636 ;

-- --------------------------------------------------------

--
-- Table structure for table `Users`
--

CREATE TABLE IF NOT EXISTS `Users` (
  `UserID` int(25) NOT NULL AUTO_INCREMENT,
  `Username` varchar(65) NOT NULL,
  `Password` varchar(32) NOT NULL,
  `EmailAddress` varchar(255) NOT NULL,
  PRIMARY KEY (`UserID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
