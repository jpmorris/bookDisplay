-- phpMyAdmin SQL Dump
-- version 3.3.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Aug 24, 2011 at 07:47 PM
-- Server version: 5.1.54
-- PHP Version: 5.3.5-1ubuntu7.2

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `bookDisplay`
--

-- --------------------------------------------------------

--
-- Table structure for table `bd_book`
--

CREATE TABLE IF NOT EXISTS `bd_book` (
  `id` int(6) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(150) NOT NULL,
  `author1` varchar(50) DEFAULT NULL,
  `author2` varchar(50) DEFAULT NULL,
  `author3` varchar(50) DEFAULT NULL,
  `author4` varchar(50) DEFAULT NULL,
  `author5` varchar(50) DEFAULT NULL,
  `isbn` varchar(20) NOT NULL,
  `category` int(11) NOT NULL,
  `subcategory` int(11) NOT NULL,
  `filelocation` varchar(150) NOT NULL,
  `filename` varchar(150) NOT NULL,
  `coverimagelocation` varchar(150) NOT NULL,
  `coverimagename` varchar(150) NOT NULL,
  `isocred` tinyint(1) NOT NULL DEFAULT '0',
  KEY `id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

-- --------------------------------------------------------

--
-- Table structure for table `bd_category`
--

CREATE TABLE IF NOT EXISTS `bd_category` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `category` varchar(50) NOT NULL,
  KEY `ID` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

-- --------------------------------------------------------

--
-- Table structure for table `bd_subcategory`
--

CREATE TABLE IF NOT EXISTS `bd_subcategory` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `subcategory` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

-- --------------------------------------------------------

--
-- Table structure for table `bd_tag`
--

CREATE TABLE IF NOT EXISTS `bd_tag` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `tagname` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=152 ;

-- --------------------------------------------------------

--
-- Table structure for table `bd_tagmap`
--

CREATE TABLE IF NOT EXISTS `bd_tagmap` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `book_id` int(11) unsigned NOT NULL,
  `tag_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=106 ;
