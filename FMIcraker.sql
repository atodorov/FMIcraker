-- MySQL dump 10.13  Distrib 5.1.68, for unknown-linux-gnu (x86_64)
--
-- Host: localhost    Database: tttcloud_FMIcraker
-- ------------------------------------------------------
-- Server version	5.1.68-cll

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
-- Table structure for table `admin`
--

DROP TABLE IF EXISTS `admin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admin` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT 'unique index',
  `facNo` tinytext COLLATE cp1251_bulgarian_ci NOT NULL COMMENT 'only if a student of SU',
  `fName` tinytext COLLATE cp1251_bulgarian_ci NOT NULL,
  `sName` tinytext COLLATE cp1251_bulgarian_ci NOT NULL,
  `oName` tinytext COLLATE cp1251_bulgarian_ci NOT NULL,
  `lName` tinytext COLLATE cp1251_bulgarian_ci NOT NULL,
  `bDate` date NOT NULL,
  `tel1` tinytext COLLATE cp1251_bulgarian_ci NOT NULL,
  `tel2` tinytext COLLATE cp1251_bulgarian_ci NOT NULL,
  `tel3` tinytext COLLATE cp1251_bulgarian_ci NOT NULL,
  `mail1` tinytext COLLATE cp1251_bulgarian_ci NOT NULL,
  `mail2` tinytext COLLATE cp1251_bulgarian_ci NOT NULL,
  `start` datetime NOT NULL,
  `end` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251 COLLATE=cp1251_bulgarian_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin`
--

LOCK TABLES `admin` WRITE;
/*!40000 ALTER TABLE `admin` DISABLE KEYS */;
/*!40000 ALTER TABLE `admin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rent`
--

DROP TABLE IF EXISTS `rent`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rent` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `sNo` mediumtext COLLATE cp1251_bulgarian_ci NOT NULL,
  `uNo` mediumtext COLLATE cp1251_bulgarian_ci NOT NULL,
  `brack` tinyint(1) NOT NULL,
  `start` datetime NOT NULL,
  `end` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251 COLLATE=cp1251_bulgarian_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rent`
--

LOCK TABLES `rent` WRITE;
/*!40000 ALTER TABLE `rent` DISABLE KEYS */;
/*!40000 ALTER TABLE `rent` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `stock`
--

DROP TABLE IF EXISTS `stock`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `stock` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` tinytext COLLATE cp1251_bulgarian_ci NOT NULL,
  `number` tinyint(4) NOT NULL,
  `describe` text COLLATE cp1251_bulgarian_ci NOT NULL,
  `idNo` tinytext COLLATE cp1251_bulgarian_ci NOT NULL,
  `type` tinytext COLLATE cp1251_bulgarian_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251 COLLATE=cp1251_bulgarian_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stock`
--

LOCK TABLES `stock` WRITE;
/*!40000 ALTER TABLE `stock` DISABLE KEYS */;
/*!40000 ALTER TABLE `stock` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `susi`
--

DROP TABLE IF EXISTS `susi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `susi` (
  `id` mediumint(8) unsigned NOT NULL COMMENT 'unique index',
  `facNo` tinytext COLLATE cp1251_bulgarian_ci NOT NULL COMMENT 'facultyNumber',
  `fName` tinytext COLLATE cp1251_bulgarian_ci NOT NULL COMMENT 'firstName',
  `sName` tinytext COLLATE cp1251_bulgarian_ci NOT NULL COMMENT 'surName',
  `oName` tinytext COLLATE cp1251_bulgarian_ci NOT NULL COMMENT 'otherName',
  `lName` tinytext COLLATE cp1251_bulgarian_ci NOT NULL COMMENT 'lastName',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251 COLLATE=cp1251_bulgarian_ci COMMENT='subversion of SU SUSI';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `susi`
--

LOCK TABLES `susi` WRITE;
/*!40000 ALTER TABLE `susi` DISABLE KEYS */;
/*!40000 ALTER TABLE `susi` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `id` mediumint(9) NOT NULL AUTO_INCREMENT,
  `facNo` tinytext COLLATE cp1251_bulgarian_ci NOT NULL COMMENT 'only if the user has been a SU student',
  `vNo` tinytext COLLATE cp1251_bulgarian_ci NOT NULL COMMENT 'if never SU student',
  `fName` tinytext COLLATE cp1251_bulgarian_ci NOT NULL,
  `sName` tinytext COLLATE cp1251_bulgarian_ci NOT NULL,
  `oName` tinytext COLLATE cp1251_bulgarian_ci NOT NULL,
  `lName` tinytext COLLATE cp1251_bulgarian_ci NOT NULL,
  `idNo` tinytext COLLATE cp1251_bulgarian_ci NOT NULL,
  `tel` tinytext COLLATE cp1251_bulgarian_ci NOT NULL,
  `mail` tinytext COLLATE cp1251_bulgarian_ci NOT NULL,
  `address` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251 COLLATE=cp1251_bulgarian_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `visit`
--

DROP TABLE IF EXISTS `visit`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `visit` (
  `id` mediumint(9) NOT NULL AUTO_INCREMENT,
  `visit` tinytext COLLATE cp1251_bulgarian_ci NOT NULL,
  `su` tinyint(1) NOT NULL,
  `comp` tinyint(4) NOT NULL,
  `start` datetime NOT NULL,
  `end` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251 COLLATE=cp1251_bulgarian_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `visit`
--

LOCK TABLES `visit` WRITE;
/*!40000 ALTER TABLE `visit` DISABLE KEYS */;
/*!40000 ALTER TABLE `visit` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2013-04-13 15:46:47
