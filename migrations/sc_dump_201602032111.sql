-- MySQL dump 10.13  Distrib 5.5.47, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: sc
-- ------------------------------------------------------
-- Server version	5.5.47-0ubuntu0.14.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `area`
--

DROP TABLE IF EXISTS `area`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `area` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary key area table',
  `state_id` int(11) NOT NULL COMMENT 'state id',
  `district_id` int(11) NOT NULL COMMENT 'district id',
  `area_name` varchar(255) DEFAULT NULL,
  `row_status` tinyint(1) DEFAULT '1' COMMENT 'Record status 1-Active 0-Deactive',
  `user_session_id` int(11) NOT NULL COMMENT 'Primary key of user_sessions table',
  `created_time` datetime NOT NULL COMMENT 'Record created time',
  `modified_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Record updated time',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `area`
--

LOCK TABLES `area` WRITE;
/*!40000 ALTER TABLE `area` DISABLE KEYS */;
/*!40000 ALTER TABLE `area` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dates`
--

DROP TABLE IF EXISTS `dates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dates` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary key dates table',
  `area_id` int(11) NOT NULL COMMENT 'state id',
  `pickup_date` datetime NOT NULL COMMENT 'stores the pick up date',
  `row_status` tinyint(1) DEFAULT '1' COMMENT 'Record status 1-Active 0-Deactive',
  `user_session_id` int(11) NOT NULL COMMENT 'Primary key of user_sessions table',
  `created_time` datetime NOT NULL COMMENT 'Record created time',
  `modified_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Record updated time',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dates`
--

LOCK TABLES `dates` WRITE;
/*!40000 ALTER TABLE `dates` DISABLE KEYS */;
/*!40000 ALTER TABLE `dates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `districts`
--

DROP TABLE IF EXISTS `districts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `districts` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary key districts table',
  `state_id` int(11) NOT NULL COMMENT 'state id',
  `district_name` varchar(255) DEFAULT NULL,
  `row_status` tinyint(1) DEFAULT '1' COMMENT 'Record status 1-Active 0-Deactive',
  `user_session_id` int(11) NOT NULL COMMENT 'Primary key of user_sessions table',
  `created_time` datetime NOT NULL COMMENT 'Record created time',
  `modified_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Record updated time',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `districts`
--

LOCK TABLES `districts` WRITE;
/*!40000 ALTER TABLE `districts` DISABLE KEYS */;
/*!40000 ALTER TABLE `districts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `materials_master`
--

DROP TABLE IF EXISTS `materials_master`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `materials_master` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary key materials table',
  `name` varchar(255) NOT NULL COMMENT 'name of the materials',
  `cost` int(11) NOT NULL COMMENT 'Cost of the material per unit',
  `unit` varchar(255) NOT NULL COMMENT 'Unit Name',
  `row_status` tinyint(1) DEFAULT '1' COMMENT 'Record status 1-Active 0-Deactive',
  `user_session_id` int(11) NOT NULL COMMENT 'Primary key of user_sessions table',
  `created_time` datetime NOT NULL COMMENT 'Record created time',
  `modified_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Record updated time',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `materials_master`
--

LOCK TABLES `materials_master` WRITE;
/*!40000 ALTER TABLE `materials_master` DISABLE KEYS */;
/*!40000 ALTER TABLE `materials_master` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_materials`
--

DROP TABLE IF EXISTS `order_materials`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `order_materials` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary key user_materials table',
  `order_id` int(11) DEFAULT NULL,
  `material_id` int(11) NOT NULL COMMENT 'material id',
  `material_qty` varchar(255) NOT NULL COMMENT 'material quantity',
  `price` varchar(255) NOT NULL COMMENT 'price given to user',
  `row_status` tinyint(1) DEFAULT '1' COMMENT 'Record status 1-Active 0-Deactive',
  `user_session_id` int(11) NOT NULL COMMENT 'Primary key of user_sessions table',
  `created_time` datetime NOT NULL COMMENT 'Record created time',
  `modified_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Record updated time',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_materials`
--

LOCK TABLES `order_materials` WRITE;
/*!40000 ALTER TABLE `order_materials` DISABLE KEYS */;
/*!40000 ALTER TABLE `order_materials` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary key orders table',
  `user_id` int(11) NOT NULL COMMENT 'Foreign key to users',
  `state_id` int(11) NOT NULL COMMENT 'state id',
  `district_id` int(11) NOT NULL COMMENT 'district id',
  `area_id` int(11) NOT NULL COMMENT 'state id',
  `address` text NOT NULL COMMENT 'full address of the user',
  `deal_status` int(2) NOT NULL COMMENT 'stores the deal',
  `row_status` tinyint(1) DEFAULT '1' COMMENT 'Record status 1-Active 0-Deactive',
  `user_session_id` int(11) NOT NULL COMMENT 'Primary key of user_sessions table',
  `created_time` datetime NOT NULL COMMENT 'Record created time',
  `modified_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Record updated time',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `states`
--

DROP TABLE IF EXISTS `states`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `states` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary key states table',
  `state_name` varchar(255) DEFAULT NULL,
  `row_status` tinyint(1) DEFAULT '1' COMMENT 'Record status 1-Active 0-Deactive',
  `user_session_id` int(11) NOT NULL COMMENT 'Primary key of user_sessions table',
  `created_time` datetime NOT NULL COMMENT 'Record created time',
  `modified_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Record updated time',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `states`
--

LOCK TABLES `states` WRITE;
/*!40000 ALTER TABLE `states` DISABLE KEYS */;
/*!40000 ALTER TABLE `states` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `type_values`
--

DROP TABLE IF EXISTS `type_values`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `type_values` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type_number` int(11) DEFAULT NULL,
  `type_name` varchar(255) NOT NULL,
  `row_status` tinyint(1) DEFAULT NULL,
  `created_time` datetime DEFAULT NULL,
  `modified_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `type_values`
--

LOCK TABLES `type_values` WRITE;
/*!40000 ALTER TABLE `type_values` DISABLE KEYS */;
INSERT INTO `type_values` VALUES (1,1,'admin',1,'2016-02-03 21:08:11','2016-02-03 15:39:26');
/*!40000 ALTER TABLE `type_values` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_sessions`
--

DROP TABLE IF EXISTS `user_sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_sessions` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary key users table',
  `user_id` int(11) NOT NULL COMMENT 'Foreign key to users',
  `login_time` datetime DEFAULT NULL COMMENT 'user login time',
  `logout_time` datetime DEFAULT NULL COMMENT 'user logout time',
  `row_status` tinyint(1) DEFAULT '1' COMMENT 'Record status 1-Active 0-Deactive',
  `created_time` datetime NOT NULL COMMENT 'Record created time',
  `modified_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Record updated time',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_sessions`
--

LOCK TABLES `user_sessions` WRITE;
/*!40000 ALTER TABLE `user_sessions` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary key users table',
  `email` varchar(255) DEFAULT NULL COMMENT 'Email of user',
  `password` varchar(255) DEFAULT NULL COMMENT 'Password of user',
  `user_type` int(11) DEFAULT NULL COMMENT 'User type - Group values-',
  `phone_number` varchar(20) DEFAULT NULL COMMENT 'Mobile number of user',
  `referral_id` varchar(20) DEFAULT NULL COMMENT 'referral id of user',
  `status` int(11) DEFAULT NULL COMMENT 'User status - Group values-',
  `login_attempts` int(11) DEFAULT NULL COMMENT 'Number of failed login attempts',
  `last_login` datetime DEFAULT NULL COMMENT 'User last login time',
  `row_status` tinyint(1) DEFAULT '1' COMMENT 'Record status 1-Active 0-Deactive',
  `user_session_id` int(11) NOT NULL COMMENT 'Primary key of user_sessions table',
  `created_time` datetime NOT NULL COMMENT 'Record created time',
  `modified_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Record updated time',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'chsatyaraj93@gmail.com',NULL,1,NULL,NULL,NULL,NULL,NULL,1,0,'0000-00-00 00:00:00','2016-02-03 15:40:10');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-02-03 21:11:20
