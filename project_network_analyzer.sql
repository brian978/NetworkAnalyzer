-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 30, 2013 at 08:34 PM
-- Server version: 5.5.30-1.1
-- PHP Version: 5.4.4-14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `project_network_analyzer`
--

-- --------------------------------------------------------

--
-- Table structure for table `devices`
--

CREATE TABLE IF NOT EXISTS `devices` (
  `id`                INT(11)     NOT NULL AUTO_INCREMENT,
  `date`              TIMESTAMP   NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `name`              VARCHAR(50) NOT NULL,
  `snmp_version`      INT(11)     NOT NULL,
  `snmp_community`    VARCHAR(20) NOT NULL,
  `type_id`           INT(11)     NOT NULL,
  `ip`                VARCHAR(40) NOT NULL,
  `interface_type_id` INT(11)     NOT NULL,
  PRIMARY KEY (`id`),
  KEY `type_id` (`type_id`)
)
  ENGINE =InnoDB
  DEFAULT CHARSET =latin1
  AUTO_INCREMENT =3;

-- --------------------------------------------------------

--
-- Table structure for table `device_types`
--

CREATE TABLE IF NOT EXISTS `device_types` (
  `id`   INT(5)      NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(20) NOT NULL,
  PRIMARY KEY (`id`)
)
  ENGINE =InnoDB
  DEFAULT CHARSET =latin1
  AUTO_INCREMENT =5;

-- --------------------------------------------------------

--
-- Table structure for table `interface_types`
--

CREATE TABLE IF NOT EXISTS `interface_types` (
  `id`   INT(5)      NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(30) NOT NULL,
  PRIMARY KEY (`id`)
)
  ENGINE =InnoDB
  DEFAULT CHARSET =latin1
  AUTO_INCREMENT =4;

-- --------------------------------------------------------

--
-- Table structure for table `traffic`
--

CREATE TABLE IF NOT EXISTS `traffic` (
  `id`                  INT(20) NOT NULL AUTO_INCREMENT,
  `source_interface_id` INT(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `source_interface_id` (`source_interface_id`)
)
  ENGINE =InnoDB
  DEFAULT CHARSET =latin1
  AUTO_INCREMENT =1;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id`      INT(11)      NOT NULL AUTO_INCREMENT,
  `name`    VARCHAR(255) NOT NULL,
  `email`   VARCHAR(100) NOT NULL,
  `role_id` INT(11)      NOT NULL,
  PRIMARY KEY (`id`),
  KEY `role_id` (`role_id`)
)
  ENGINE =InnoDB
  DEFAULT CHARSET =latin1
  AUTO_INCREMENT =2;

-- --------------------------------------------------------

--
-- Table structure for table `user_roles`
--

CREATE TABLE IF NOT EXISTS `user_roles` (
  `id`         INT(11)     NOT NULL AUTO_INCREMENT,
  `name_en_US` VARCHAR(20) NOT NULL,
  `name_ro_RO` VARCHAR(20) NOT NULL,
  PRIMARY KEY (`id`)
)
  ENGINE =InnoDB
  DEFAULT CHARSET =latin1
  AUTO_INCREMENT =5;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
