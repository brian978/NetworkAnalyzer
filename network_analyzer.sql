-- phpMyAdmin SQL Dump
-- version 4.0.3
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 16, 2013 at 11:44 PM
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
CREATE DATABASE IF NOT EXISTS `network_analyzer` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `network_analyzer`;

-- --------------------------------------------------------

--
-- Table structure for table `bandwidth_logs`
--

CREATE TABLE IF NOT EXISTS `bandwidth_logs` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `time` double NOT NULL,
  `uptime` varchar(50) NOT NULL,
  `oid_index` int(10) NOT NULL,
  `interface_name` varchar(50) NOT NULL,
  `ip` varchar(40) NOT NULL,
  `netmask` varchar(40) NOT NULL,
  `octets_in` double NOT NULL,
  `octets_out` double NOT NULL,
  `bandwidth_in` double NOT NULL COMMENT 'Value is in bytes per second',
  `bandwidth_out` double NOT NULL COMMENT 'Value is in bytes per second',
  `mac` varchar(50) NOT NULL,
  `discontinuity_counter` varchar(20) NOT NULL,
  `device_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `device_id` (`device_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `devices`
--

CREATE TABLE IF NOT EXISTS `devices` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `name` varchar(50) NOT NULL,
  `snmp_version` int(11) NOT NULL,
  `snmp_community` varchar(20) NOT NULL,
  `type_id` int(11) NOT NULL,
  `ip` varchar(40) NOT NULL,
  `interface_type_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `type_id` (`type_id`),
  KEY `interface_type_id` (`interface_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `device_types`
--

CREATE TABLE IF NOT EXISTS `device_types` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `interface_types`
--

CREATE TABLE IF NOT EXISTS `interface_types` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `traffic_logs`
--

CREATE TABLE IF NOT EXISTS `traffic_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `time` double NOT NULL,
  `type` varchar(5) NOT NULL,
  `src_ip` varchar(40) NOT NULL,
  `dst_ip` varchar(40) NOT NULL,
  `src_port` int(11) NOT NULL,
  `dst_port` int(11) NOT NULL,
  `iface_name` varchar(50) NOT NULL,
  `device_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `device_id` (`device_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `role_id` (`role_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role_id`) VALUES
(1, 'admin', 'admin@admin.com', 'a6c91681919e96e4db8adbfdbcbd1a40cbdf057f245fd718db64758f2d9a29474288c291ad39de076c2f6de08d32104941728c49fdf6b6463fef48cad868ecb2df39de7139bb5a8852519182', 1);

-- --------------------------------------------------------

--
-- Table structure for table `user_roles`
--

CREATE TABLE IF NOT EXISTS `user_roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_name` varchar(50) NOT NULL,
  `name_en_US` varchar(20) NOT NULL,
  `name_ro_RO` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `user_roles`
--

INSERT INTO `user_roles` (`id`, `role_name`, `name_en_US`, `name_ro_RO`) VALUES
(1, 'admin', 'Administrator', 'Administrator'),
(2, 'guest', 'Guest', 'Musafir'),
(3, 'user', 'User', 'Utilizator'),
(4, 'technical', 'Technical', 'Technician');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bandwidth_logs`
--
ALTER TABLE `bandwidth_logs`
  ADD CONSTRAINT `bandwidth_logs_ibfk_1` FOREIGN KEY (`device_id`) REFERENCES `devices` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `bandwidth_logs_ibfk_2` FOREIGN KEY (`device_id`) REFERENCES `devices` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `bandwidth_logs_ibfk_3` FOREIGN KEY (`device_id`) REFERENCES `devices` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `devices`
--
ALTER TABLE `devices`
  ADD CONSTRAINT `devices_ibfk_1` FOREIGN KEY (`type_id`) REFERENCES `device_types` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `devices_ibfk_2` FOREIGN KEY (`interface_type_id`) REFERENCES `interface_types` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `devices_ibfk_3` FOREIGN KEY (`type_id`) REFERENCES `device_types` (`id`),
  ADD CONSTRAINT `devices_ibfk_4` FOREIGN KEY (`type_id`) REFERENCES `device_types` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `traffic_logs`
--
ALTER TABLE `traffic_logs`
  ADD CONSTRAINT `traffic_logs_ibfk_1` FOREIGN KEY (`device_id`) REFERENCES `devices` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `user_roles` (`id`) ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
