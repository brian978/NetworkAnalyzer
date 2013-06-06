-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 06, 2013 at 08:13 AM
-- Server version: 5.5.30-1.1
-- PHP Version: 5.4.4-14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `network_analyzer`
--

-- --------------------------------------------------------

--
-- Table structure for table `bandwidth_logs`
--

DROP TABLE IF EXISTS `bandwidth_logs`;
CREATE TABLE IF NOT EXISTS `bandwidth_logs` (
  `id`                    INT(20)     NOT NULL AUTO_INCREMENT,
  `time`                  INT(50)     NOT NULL,
  `uptime`                VARCHAR(50) NOT NULL,
  `oid_index`             INT(10)     NOT NULL,
  `interface_name`        VARCHAR(50) NOT NULL,
  `ip`                    VARCHAR(40) NOT NULL,
  `netmask`               VARCHAR(40) NOT NULL,
  `octets_in`             INT(100)    NOT NULL,
  `octets_out`            INT(100)    NOT NULL,
  `mac`                   VARCHAR(50) NOT NULL,
  `discontinuity_counter` VARCHAR(20) NOT NULL,
  `device_id`             INT(11)     NOT NULL,
  PRIMARY KEY (`id`),
  KEY `device_id` (`device_id`)
)
  ENGINE =InnoDB
  DEFAULT CHARSET =latin1
  AUTO_INCREMENT =534;

-- --------------------------------------------------------

--
-- Table structure for table `devices`
--

DROP TABLE IF EXISTS `devices`;
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
  KEY `type_id` (`type_id`),
  KEY `interface_type_id` (`interface_type_id`)
)
  ENGINE =InnoDB
  DEFAULT CHARSET =latin1
  AUTO_INCREMENT =8;

-- --------------------------------------------------------

--
-- Table structure for table `device_types`
--

DROP TABLE IF EXISTS `device_types`;
CREATE TABLE IF NOT EXISTS `device_types` (
  `id`   INT(5)      NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(20) NOT NULL,
  PRIMARY KEY (`id`)
)
  ENGINE =InnoDB
  DEFAULT CHARSET =latin1
  AUTO_INCREMENT =7;

-- --------------------------------------------------------

--
-- Table structure for table `interface_types`
--

DROP TABLE IF EXISTS `interface_types`;
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
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id`       INT(11)      NOT NULL AUTO_INCREMENT,
  `name`     VARCHAR(255) NOT NULL,
  `email`    VARCHAR(100) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `role_id`  INT(11)      NOT NULL,
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

DROP TABLE IF EXISTS `user_roles`;
CREATE TABLE IF NOT EXISTS `user_roles` (
  `id`         INT(11)     NOT NULL AUTO_INCREMENT,
  `name_en_US` VARCHAR(20) NOT NULL,
  `name_ro_RO` VARCHAR(20) NOT NULL,
  PRIMARY KEY (`id`)
)
  ENGINE =InnoDB
  DEFAULT CHARSET =latin1
  AUTO_INCREMENT =5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bandwidth_logs`
--
ALTER TABLE `bandwidth_logs`
ADD CONSTRAINT `bandwidth_logs_ibfk_1` FOREIGN KEY (`device_id`) REFERENCES `devices` (`id`)
  ON UPDATE CASCADE,
ADD CONSTRAINT `bandwidth_logs_ibfk_2` FOREIGN KEY (`device_id`) REFERENCES `devices` (`id`)
  ON DELETE CASCADE
  ON UPDATE CASCADE;

--
-- Constraints for table `devices`
--
ALTER TABLE `devices`
ADD CONSTRAINT `devices_ibfk_4` FOREIGN KEY (`type_id`) REFERENCES `device_types` (`id`)
  ON UPDATE CASCADE,
ADD CONSTRAINT `devices_ibfk_1` FOREIGN KEY (`type_id`) REFERENCES `device_types` (`id`)
  ON UPDATE CASCADE,
ADD CONSTRAINT `devices_ibfk_2` FOREIGN KEY (`interface_type_id`) REFERENCES `interface_types` (`id`)
  ON UPDATE CASCADE,
ADD CONSTRAINT `devices_ibfk_3` FOREIGN KEY (`type_id`) REFERENCES `device_types` (`id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `user_roles` (`id`)
  ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
