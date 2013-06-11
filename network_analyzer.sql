-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 11, 2013 at 01:35 PM
-- Server version: 5.5.30-1.1
-- PHP Version: 5.4.4-14

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
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
-- Table structure for table `bandwidth_logs`
--

CREATE TABLE IF NOT EXISTS `bandwidth_logs` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=58365 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

-- --------------------------------------------------------

--
-- Table structure for table `device_types`
--

CREATE TABLE IF NOT EXISTS `device_types` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Table structure for table `interface_types`
--

CREATE TABLE IF NOT EXISTS `interface_types` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `traffic_logs`
--

CREATE TABLE IF NOT EXISTS `traffic_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `time` double NOT NULL,
  `type` varchar(5) NOT NULL,
  `src_ip` varchar(40) NOT NULL,
  `dst_ip` varchar(40) NOT NULL,
  `src_port` int(11) NOT NULL,
  `dst_port` int(11) NOT NULL,
  `iface_name` varchar(50) NOT NULL,
  `device_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=32625 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `user_roles`
--

CREATE TABLE IF NOT EXISTS `user_roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name_en_US` varchar(20) NOT NULL,
  `name_ro_RO` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bandwidth_logs`
--
ALTER TABLE `bandwidth_logs`
  ADD CONSTRAINT `bandwidth_logs_ibfk_1` FOREIGN KEY (`device_id`) REFERENCES `devices` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `bandwidth_logs_ibfk_2` FOREIGN KEY (`device_id`) REFERENCES `devices` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `devices`
--
ALTER TABLE `devices`
  ADD CONSTRAINT `devices_ibfk_1` FOREIGN KEY (`type_id`) REFERENCES `device_types` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `devices_ibfk_2` FOREIGN KEY (`interface_type_id`) REFERENCES `interface_types` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `devices_ibfk_3` FOREIGN KEY (`type_id`) REFERENCES `device_types` (`id`),
  ADD CONSTRAINT `devices_ibfk_4` FOREIGN KEY (`type_id`) REFERENCES `device_types` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `user_roles` (`id`) ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
