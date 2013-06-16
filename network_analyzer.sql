-- phpMyAdmin SQL Dump
-- version 4.0.3
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 16, 2013 at 06:24 PM
-- Server version: 5.5.30-1.1
-- PHP Version: 5.4.4-14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT = @@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS = @@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION = @@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `network_analyzer`
--
CREATE DATABASE IF NOT EXISTS `network_analyzer`
  DEFAULT CHARACTER SET latin1
  COLLATE latin1_swedish_ci;
USE `network_analyzer`;

-- --------------------------------------------------------

--
-- Table structure for table `bandwidth_logs`
--

CREATE TABLE IF NOT EXISTS `bandwidth_logs` (
  `id`                    INT(20)     NOT NULL AUTO_INCREMENT,
  `date`                  DATE        NOT NULL,
  `time`                  DOUBLE      NOT NULL,
  `uptime`                VARCHAR(50) NOT NULL,
  `oid_index`             INT(10)     NOT NULL,
  `interface_name`        VARCHAR(50) NOT NULL,
  `ip`                    VARCHAR(40) NOT NULL,
  `netmask`               VARCHAR(40) NOT NULL,
  `octets_in`             DOUBLE      NOT NULL,
  `octets_out`            DOUBLE      NOT NULL,
  `bandwidth_in`          DOUBLE      NOT NULL
  COMMENT 'Value is in bytes per second',
  `bandwidth_out`         DOUBLE      NOT NULL
  COMMENT 'Value is in bytes per second',
  `mac`                   VARCHAR(50) NOT NULL,
  `discontinuity_counter` VARCHAR(20) NOT NULL,
  `device_id`             INT(11)     NOT NULL,
  PRIMARY KEY (`id`),
  KEY `device_id` (`device_id`)
)
  ENGINE =InnoDB
  DEFAULT CHARSET =latin1
  AUTO_INCREMENT =189924;

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

CREATE TABLE IF NOT EXISTS `device_types` (
  `id`   INT(5)      NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(20) NOT NULL,
  PRIMARY KEY (`id`)
)
  ENGINE =InnoDB
  DEFAULT CHARSET =latin1
  AUTO_INCREMENT =8;

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
  AUTO_INCREMENT =6;

-- --------------------------------------------------------

--
-- Table structure for table `traffic_logs`
--

CREATE TABLE IF NOT EXISTS `traffic_logs` (
  `id`         INT(11)     NOT NULL AUTO_INCREMENT,
  `date`       DATE        NOT NULL,
  `time`       DOUBLE      NOT NULL,
  `type`       VARCHAR(5)  NOT NULL,
  `src_ip`     VARCHAR(40) NOT NULL,
  `dst_ip`     VARCHAR(40) NOT NULL,
  `src_port`   INT(11)     NOT NULL,
  `dst_port`   INT(11)     NOT NULL,
  `iface_name` VARCHAR(50) NOT NULL,
  `device_id`  INT(11)     NOT NULL,
  PRIMARY KEY (`id`),
  KEY `device_id` (`device_id`)
)
  ENGINE =InnoDB
  DEFAULT CHARSET =latin1
  AUTO_INCREMENT =123110;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

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
  AUTO_INCREMENT =3;

-- --------------------------------------------------------

--
-- Table structure for table `user_roles`
--

CREATE TABLE IF NOT EXISTS `user_roles` (
  `id`         INT(11)     NOT NULL AUTO_INCREMENT,
  `role_name`  VARCHAR(50) NOT NULL,
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
  ON UPDATE CASCADE,
ADD CONSTRAINT `bandwidth_logs_ibfk_3` FOREIGN KEY (`device_id`) REFERENCES `devices` (`id`)
  ON DELETE CASCADE
  ON UPDATE CASCADE;

--
-- Constraints for table `devices`
--
ALTER TABLE `devices`
ADD CONSTRAINT `devices_ibfk_1` FOREIGN KEY (`type_id`) REFERENCES `device_types` (`id`)
  ON UPDATE CASCADE,
ADD CONSTRAINT `devices_ibfk_2` FOREIGN KEY (`interface_type_id`) REFERENCES `interface_types` (`id`)
  ON UPDATE CASCADE,
ADD CONSTRAINT `devices_ibfk_3` FOREIGN KEY (`type_id`) REFERENCES `device_types` (`id`),
ADD CONSTRAINT `devices_ibfk_4` FOREIGN KEY (`type_id`) REFERENCES `device_types` (`id`)
  ON UPDATE CASCADE;

--
-- Constraints for table `traffic_logs`
--
ALTER TABLE `traffic_logs`
ADD CONSTRAINT `traffic_logs_ibfk_1` FOREIGN KEY (`device_id`) REFERENCES `devices` (`id`)
  ON DELETE CASCADE
  ON UPDATE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `user_roles` (`id`)
  ON UPDATE CASCADE;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role_id`) VALUES
(1, 'admin', 'admin@admin.com', 'a6c91681919e96e4db8adbfdbcbd1a40cbdf057f245fd718db64758f2d9a29474288c291ad39de076c2f6de08d32104941728c49fdf6b6463fef48cad868ecb2df39de7139bb5a8852519182', 1);

--
-- Dumping data for table `user_roles`
--

INSERT INTO `user_roles` (`id`, `role_name`, `name_en_US`, `name_ro_RO`) VALUES
(1, 'admin', 'Administrator', 'Administrator'),
(2, 'guest', 'Guest', 'Musafir'),
(3, 'user', 'User', 'Utilizator'),
(4, 'technical', 'Technical', 'Technician');

/*!40101 SET CHARACTER_SET_CLIENT = @OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS = @OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION = @OLD_COLLATION_CONNECTION */;
