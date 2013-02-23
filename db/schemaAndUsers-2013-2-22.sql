-- MySQL dump 10.13  Distrib 5.5.28, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: ccmk
-- ------------------------------------------------------
-- Server version	5.5.28-0ubuntu0.12.04.3

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
-- Table structure for table `Group`
--

DROP TABLE IF EXISTS `Group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Group` (
  `id` int(11) NOT NULL,
  `creator` int(11) NOT NULL,
  `startDate` datetime NOT NULL,
  `endDate` datetime DEFAULT NULL,
  `name` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Group`
--

LOCK TABLES `Group` WRITE;
/*!40000 ALTER TABLE `Group` DISABLE KEYS */;
/*!40000 ALTER TABLE `Group` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Payment`
--

DROP TABLE IF EXISTS `Payment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Payment` (
  `id` int(11) NOT NULL,
  `senderId` int(11) NOT NULL,
  `receiverId` int(11) NOT NULL,
  `startDate` datetime NOT NULL,
  `endDate` datetime DEFAULT NULL,
  `amountDue` int(11) NOT NULL,
  `receiptId` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_payment_receipt` (`receiptId`),
  CONSTRAINT `fk_payment_receipt` FOREIGN KEY (`receiptId`) REFERENCES `Receipt` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Payment`
--

LOCK TABLES `Payment` WRITE;
/*!40000 ALTER TABLE `Payment` DISABLE KEYS */;
/*!40000 ALTER TABLE `Payment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Receipt`
--

DROP TABLE IF EXISTS `Receipt`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Receipt` (
  `id` int(11) NOT NULL,
  `amountDue` float DEFAULT NULL,
  `userId` int(11) DEFAULT NULL,
  `groupId` int(11) DEFAULT NULL,
  `picture` varchar(255) DEFAULT NULL,
  `name` varchar(45) DEFAULT NULL,
  `status` enum('UNPAID','VOID','PAID') DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_receipt_group` (`groupId`),
  KEY `fk_receipt_user` (`userId`),
  CONSTRAINT `fk_receipt_group` FOREIGN KEY (`groupId`) REFERENCES `Group` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_receipt_user` FOREIGN KEY (`userId`) REFERENCES `User` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Receipt`
--

LOCK TABLES `Receipt` WRITE;
/*!40000 ALTER TABLE `Receipt` DISABLE KEYS */;
/*!40000 ALTER TABLE `Receipt` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `User`
--

DROP TABLE IF EXISTS `User`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `User` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `firstName` varchar(45) DEFAULT NULL,
  `lastName` varchar(45) DEFAULT NULL,
  `email` varchar(45) NOT NULL,
  `startDate` datetime NOT NULL,
  `endDate` datetime DEFAULT NULL,
  `lastLogin` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `User`
--

LOCK TABLES `User` WRITE;
/*!40000 ALTER TABLE `User` DISABLE KEYS */;
INSERT INTO `User` VALUES (1,'Marin','Zaimov','marin@gatech.edu','2013-02-22 00:00:00',NULL,NULL),(3,'Chudy','Chudy','chudy@gatech.edu','2013-02-22 00:00:00',NULL,NULL),(4,'Cliffton','Thomas','cliftot64@gmail.com','2013-02-22 00:00:00',NULL,NULL);
/*!40000 ALTER TABLE `User` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `User_Group`
--

DROP TABLE IF EXISTS `User_Group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `User_Group` (
  `userId` int(11) NOT NULL,
  `groupId` int(11) NOT NULL,
  `startDate` datetime NOT NULL,
  `endDate` datetime DEFAULT NULL,
  `invitedBy` int(11) DEFAULT NULL,
  PRIMARY KEY (`userId`,`groupId`),
  KEY `fk_user_group_user` (`userId`),
  KEY `fk_user_group_group` (`groupId`),
  CONSTRAINT `fk_user_group_group` FOREIGN KEY (`groupId`) REFERENCES `Group` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_user_group_user` FOREIGN KEY (`userId`) REFERENCES `User` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `User_Group`
--

LOCK TABLES `User_Group` WRITE;
/*!40000 ALTER TABLE `User_Group` DISABLE KEYS */;
/*!40000 ALTER TABLE `User_Group` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2013-02-22 23:48:21
