-- MySQL dump 10.13  Distrib 5.1.46, for suse-linux-gnu (i686)
--
-- Host: localhost    Database: cnxcc
-- ------------------------------------------------------
-- Server version	5.1.46-log

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
-- Table structure for table `call`
--

DROP TABLE IF EXISTS `call`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `call` (
  `call_id` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `confirmed` varchar(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'N',
  `max_amount` decimal(10,0) DEFAULT NULL,
  `consumed_amount` decimal(10,0) DEFAULT NULL,
  `start_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `client_id` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`call_id`),
  KEY `FK_call_credit_data` (`client_id`),
  CONSTRAINT `FK_call_credit_data` FOREIGN KEY (`client_id`) REFERENCES `credit_data` (`client_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `call`
--

LOCK TABLES `call` WRITE;
/*!40000 ALTER TABLE `call` DISABLE KEYS */;
/*!40000 ALTER TABLE `call` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `credit_data`
--

DROP TABLE IF EXISTS `credit_data`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `credit_data` (
  `client_data_id` int(11) NOT NULL AUTO_INCREMENT,
  `max_amount` decimal(11,0) DEFAULT '0',
  `consumed_amount` decimal(11,0) DEFAULT '0',
  `number_of_calls` int(11) DEFAULT '0',
  `concurrent_calls` int(11) DEFAULT '0',
  `credit_type_id` int(11) DEFAULT NULL,
  `client_id` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`client_data_id`),
  UNIQUE KEY `client_id_unique` (`client_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `credit_data`
--

LOCK TABLES `credit_data` WRITE;
/*!40000 ALTER TABLE `credit_data` DISABLE KEYS */;
/*!40000 ALTER TABLE `credit_data` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `money_based_call`
--

DROP TABLE IF EXISTS `money_based_call`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `money_based_call` (
  `call_id` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `cost_per_second` decimal(10,0) NOT NULL,
  `initial_pulse` int(11) NOT NULL,
  `final_pulse` int(11) NOT NULL,
  PRIMARY KEY (`call_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `money_based_call`
--

LOCK TABLES `money_based_call` WRITE;
/*!40000 ALTER TABLE `money_based_call` DISABLE KEYS */;
/*!40000 ALTER TABLE `money_based_call` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2012-12-27 12:14:53