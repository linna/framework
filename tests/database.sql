CREATE DATABASE  IF NOT EXISTS `test` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `test`;
-- MySQL dump 10.13  Distrib 5.7.12, for Win64 (x86_64)
--
-- Host: 192.168.0.8    Database: test
-- ------------------------------------------------------
-- Server version	5.7.15-0ubuntu0.16.04.1

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
-- Table structure for table `session`
--

DROP TABLE IF EXISTS `session`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `session` (
  `session_id` char(128) NOT NULL,
  `session_data` varchar(3096) NOT NULL,
  `last_update` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`session_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `session`
--

LOCK TABLES `session` WRITE;
/*!40000 ALTER TABLE `session` DISABLE KEYS */;
/*!40000 ALTER TABLE `session` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tree`
--

DROP TABLE IF EXISTS `tree`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tree` (
  `tree_id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `tree_parent_id` smallint(6) unsigned NOT NULL,
  `node_name` char(32) NOT NULL,
  `node_order` smallint(6) unsigned NOT NULL DEFAULT '0',
  `tree_root` tinyint(1) NOT NULL DEFAULT '0',
  `tree_left` smallint(6) unsigned NOT NULL DEFAULT '0',
  `tree_right` smallint(6) unsigned NOT NULL DEFAULT '0',
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`tree_id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tree`
--

LOCK TABLES `tree` WRITE;
/*!40000 ALTER TABLE `tree` DISABLE KEYS */;
INSERT INTO `tree` VALUES (1,0,'Root',1,1,1,46,'2016-08-07 17:22:57'),(2,1,'Child 1',1,0,2,17,'2016-08-07 17:22:57'),(3,1,'Child 2',2,0,6,9,'2016-08-07 17:22:57'),(4,1,'Child 3',3,0,18,39,'2016-08-07 17:22:57'),(5,1,'Child 4',4,0,40,41,'2016-08-07 17:22:57'),(6,1,'Child 5',5,0,42,43,'2016-08-07 17:22:57'),(7,2,'Child 11',1,0,3,10,'2016-08-07 17:22:57'),(8,2,'Child 12',2,0,4,5,'2016-08-04 20:26:21'),(9,2,'Child 13',3,0,11,12,'2016-08-07 17:22:57'),(10,2,'Child 14',4,0,13,14,'2016-08-07 17:22:57'),(11,2,'Child 15',5,0,15,16,'2016-08-07 17:22:57'),(12,4,'Child 31',1,0,19,20,'2016-08-07 17:22:57'),(13,4,'Child 32',2,0,21,34,'2016-08-07 17:22:57'),(14,4,'Child 33',3,0,35,36,'2016-08-07 17:22:57'),(15,4,'Child 34',4,0,37,38,'2016-08-07 17:22:57'),(16,13,'Child 321',1,0,22,23,'2016-08-07 17:22:57'),(17,13,'Child 322',2,0,24,25,'2016-08-07 17:22:57'),(18,13,'Child 323',3,0,26,27,'2016-08-07 17:22:57'),(19,13,'Child 324',4,0,28,29,'2016-08-07 17:22:57'),(20,13,'Child 325',5,0,30,31,'2016-08-07 17:22:57'),(21,13,'Child 326',6,0,32,33,'2016-08-07 17:22:57'),(22,1,'Child 6',6,0,44,45,'2016-08-07 17:22:57'),(23,3,'Child 21',1,0,7,8,'2016-08-07 17:22:57');
/*!40000 ALTER TABLE `tree` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `user_id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL,
  `description` tinytext,
  `password` char(255) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '0',
  `created` datetime NOT NULL,
  `last_update` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1,'root','System User','$2y$11$4IAn6SRaB0osPz8afZC5D.CmTrBGxnb5FQEygPjDirK9SWE/u8YuO',1,'2015-02-14 10:39:00','2016-08-30 16:46:56'),(2,'User_0','Descrizione User_0','$2y$11$i7/54cEL3wVTbIqH6hyuUe4w/tijNLZSFRlllDM44fqUa6yBJF9wi',1,'2015-08-07 18:50:44','2016-09-15 18:36:39'),(3,'User_1','Descrizione User_1','$2y$11$cq3ZWO18l68X7pGs9Y1fveTGcNJ/iyehrDZ10BAvbY8LaBXNvnyk6',0,'2015-08-07 18:50:44','2016-09-14 21:59:13'),(4,'User_2','Descrizione User_2','$2y$11$pJalB4tJwvD3ZHSAvhIFE.bZFC7M3QdXRJ/SOBPU7ylqQ2aW9spY.',0,'2015-08-07 18:50:44','2015-08-09 22:17:17'),(5,'User_3','Descrizione User_3','$2y$11$J/9GjpefJUvqO1clihPkEuxZrWVwDIXk1zR9XAjOEzK5HI90cr5FO',0,'2015-08-07 18:50:45','2016-09-15 06:39:57'),(6,'User_4','Descrizione User_4','$2y$11$oC64K4.qGruuVqEgJV.Nxe7/f84jR2cSEywf3BmfmWnlM0wDO/ApG',0,'2015-08-07 18:50:45','2015-08-07 16:50:45'),(7,'User_5','Descrizione User_5','$2y$11$aLl8Rdz3duayXTOpNqFCUOw4aIyPQOwIEsZCMfoVJ8ZjJajt4SpPe',0,'2015-08-07 18:50:45','2015-08-12 15:56:01');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-09-15 22:09:09
