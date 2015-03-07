-- MySQL dump 10.13  Distrib 5.1.73, for redhat-linux-gnu (x86_64)
--
-- Host: localhost    Database: emsnotice_db
-- ------------------------------------------------------
-- Server version	5.1.73

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
-- Table structure for table `mm_users_groups`
--

DROP TABLE IF EXISTS `mm_users_groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mm_users_groups` (
  `id_user` int(11) NOT NULL,
  `id_Group` varchar(45) NOT NULL,
  PRIMARY KEY (`id_user`),
  CONSTRAINT `mm_users_groups_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `tblUsers` (`id_user`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='many-to-many Users to Groups';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mm_users_groups`
--

LOCK TABLES `mm_users_groups` WRITE;
/*!40000 ALTER TABLE `mm_users_groups` DISABLE KEYS */;
/*!40000 ALTER TABLE `mm_users_groups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `readywisc_counties`
--

DROP TABLE IF EXISTS `readywisc_counties`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `readywisc_counties` (
  `county_code` varchar(5) NOT NULL,
  `county_name` varchar(20) NOT NULL,
  PRIMARY KEY (`county_code`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `readywisc_counties`
--

LOCK TABLES `readywisc_counties` WRITE;
/*!40000 ALTER TABLE `readywisc_counties` DISABLE KEYS */;
INSERT INTO `readywisc_counties` VALUES ('KEN','Kenosha');
/*!40000 ALTER TABLE `readywisc_counties` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `readywisc_damage_assesment`
--

DROP TABLE IF EXISTS `readywisc_damage_assesment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `readywisc_damage_assesment` (
  `report_id` int(11) NOT NULL AUTO_INCREMENT,
  `date_of_occurrence` date NOT NULL,
  `type_of_occurrence` varchar(10) NOT NULL,
  `name_of_reporter` varchar(30) DEFAULT NULL,
  `affected_address` varchar(50) DEFAULT NULL,
  `city` varchar(20) DEFAULT NULL,
  `zip_code` int(11) DEFAULT NULL,
  `contact_number` int(11) DEFAULT NULL,
  `own_or_rent` varchar(10) DEFAULT NULL,
  `damage_description` varchar(200) DEFAULT NULL,
  `estimation_of_loss` int(11) DEFAULT NULL,
  `insurance_coverage` int(11) DEFAULT NULL,
  `habitable` varchar(5) DEFAULT NULL,
  `water_in_basement` varchar(5) DEFAULT NULL,
  `basement_occupant` varchar(5) DEFAULT NULL,
  `latitude` int(11) DEFAULT NULL,
  `longitude` int(11) DEFAULT NULL,
  `picture` longblob,
  PRIMARY KEY (`report_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `readywisc_damage_assesment`
--

LOCK TABLES `readywisc_damage_assesment` WRITE;
/*!40000 ALTER TABLE `readywisc_damage_assesment` DISABLE KEYS */;
INSERT INTO `readywisc_damage_assesment` VALUES (1,'0000-00-00','Flood','John','123 Main Street','Floodville',55412,2147483647,'Own','Bad',10,0,'Yes','Yes','Yes',321,21,NULL);
/*!40000 ALTER TABLE `readywisc_damage_assesment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `readywisc_dept`
--

DROP TABLE IF EXISTS `readywisc_dept`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `readywisc_dept` (
  `dept_id` varchar(10) NOT NULL,
  `dept_type` varchar(10) NOT NULL,
  `dept_name` varchar(50) NOT NULL,
  `street_add` varchar(100) DEFAULT NULL,
  `zip_code` int(11) DEFAULT NULL,
  `phone_number` int(11) DEFAULT NULL,
  PRIMARY KEY (`dept_id`),
  UNIQUE KEY `phone_number` (`phone_number`),
  KEY `zip_code` (`zip_code`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `readywisc_dept`
--

LOCK TABLES `readywisc_dept` WRITE;
/*!40000 ALTER TABLE `readywisc_dept` DISABLE KEYS */;
INSERT INTO `readywisc_dept` VALUES ('123','Police','WIPD','123 Police Road',53144,2147483647);
/*!40000 ALTER TABLE `readywisc_dept` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `readywisc_userDetails`
--

DROP TABLE IF EXISTS `readywisc_userDetails`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `readywisc_userDetails` (
  `user_id` int(11) DEFAULT NULL,
  `last_name` varchar(20) NOT NULL,
  `first_name` varchar(20) NOT NULL,
  `email` varchar(50) DEFAULT NULL,
  UNIQUE KEY `email` (`email`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `readywisc_userDetails`
--

LOCK TABLES `readywisc_userDetails` WRITE;
/*!40000 ALTER TABLE `readywisc_userDetails` DISABLE KEYS */;
INSERT INTO `readywisc_userDetails` VALUES (123456,'Smith','John','john.smith@email.com');
/*!40000 ALTER TABLE `readywisc_userDetails` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `readywisc_users`
--

DROP TABLE IF EXISTS `readywisc_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `readywisc_users` (
  `user_id` int(11) NOT NULL,
  `password` varchar(24) NOT NULL,
  `county_code` varchar(5) NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `readywisc_users`
--

LOCK TABLES `readywisc_users` WRITE;
/*!40000 ALTER TABLE `readywisc_users` DISABLE KEYS */;
INSERT INTO `readywisc_users` VALUES (12345,'asdf','53144');
/*!40000 ALTER TABLE `readywisc_users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `readywisc_zipchecklist`
--

DROP TABLE IF EXISTS `readywisc_zipchecklist`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `readywisc_zipchecklist` (
  `checklist_id` int(11) NOT NULL AUTO_INCREMENT,
  `google_id` varchar(50) DEFAULT NULL,
  `title` varchar(20) NOT NULL,
  PRIMARY KEY (`checklist_id`),
  UNIQUE KEY `google_id` (`google_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `readywisc_zipchecklist`
--

LOCK TABLES `readywisc_zipchecklist` WRITE;
/*!40000 ALTER TABLE `readywisc_zipchecklist` DISABLE KEYS */;
/*!40000 ALTER TABLE `readywisc_zipchecklist` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `readywisc_zipcodes`
--

DROP TABLE IF EXISTS `readywisc_zipcodes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `readywisc_zipcodes` (
  `zip_code` int(11) NOT NULL,
  `county_code` varchar(5) DEFAULT NULL,
  `state` varchar(20) DEFAULT NULL,
  `city` varchar(20) DEFAULT NULL,
  `zip_class` varchar(20) DEFAULT NULL,
  `latitude` double DEFAULT NULL,
  `longitude` double DEFAULT NULL,
  PRIMARY KEY (`zip_code`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `readywisc_zipcodes`
--

LOCK TABLES `readywisc_zipcodes` WRITE;
/*!40000 ALTER TABLE `readywisc_zipcodes` DISABLE KEYS */;
INSERT INTO `readywisc_zipcodes` VALUES (53101,'','Wisconsin','BASSETT','PO BOX ONLY',42.58098,-87.662878),(53102,'','Wisconsin','BENET LAKE','STANDARD',42.500141,-88.079983),(53104,'','Wisconsin','BRISTOL','STANDARD',42.553518,-88.028986),(53109,'','Wisconsin','CAMP LAKE','PO BOX ONLY',42.535968,-88.144386),(53140,'','Wisconsin','KENOSHA','STANDARD',42.622449,-87.830375),(53141,'','Wisconsin','KENOSHA','STANDARD',42.58098,-87.662878),(53142,'','Wisconsin','KENOSHA','STANDARD',42.558221,-87.925876),(53143,'','Wisconsin','KENOSHA','STANDARD',42.53607,-87.824828),(53144,'','Wisconsin','KENOSHA','STANDARD',42.618427,-87.948079),(53152,'','Wisconsin','NEW MUNSTER','PO BOX ONLY',42.574616,-88.232632),(53158,'','Wisconsin','PLEASANT PRAIRIE','STANDARD',42.52926,-87.885546),(53159,'','Wisconsin','POWERS LAKE','PO BOX ONLY',42.555695,-88.296914),(53168,'','Wisconsin','SALEM','STANDARD',42.574598,-88.137535),(53170,'','Wisconsin','SILVER LAKE','STANDARD',42.552018,-88.160836),(53171,'','Wisconsin','SOMERS','PO BOX ONLY',42.642298,-87.903161),(53179,'','Wisconsin','TREVOR','STANDARD',42.517168,-88.138001),(53181,'','Wisconsin','TWIN LAKES','STANDARD',42.523887,-88.235389),(53192,'','Wisconsin','WILMOT','PO BOX ONLY',42.507165,-88.191337),(53194,'','Wisconsin','WOODWORTH','PO BOX ONLY',42.58098,-87.662878);
/*!40000 ALTER TABLE `readywisc_zipcodes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblCheckLists`
--

DROP TABLE IF EXISTS `tblCheckLists`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblCheckLists` (
  `id_checklist` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `checklist_name` varchar(45) DEFAULT NULL COMMENT 'Short name',
  `checklist_desc` text COMMENT 'Description',
  PRIMARY KEY (`id_checklist`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblCheckLists`
--

LOCK TABLES `tblCheckLists` WRITE;
/*!40000 ALTER TABLE `tblCheckLists` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblCheckLists` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblCheckLists_Items`
--

DROP TABLE IF EXISTS `tblCheckLists_Items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblCheckLists_Items` (
  `id_checklist` int(11) NOT NULL COMMENT 'ID (FK)',
  `id_checklist_item` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID (item)',
  `checklist_item` varchar(100) DEFAULT NULL COMMENT 'Item for this checklist',
  PRIMARY KEY (`id_checklist_item`,`id_checklist`),
  KEY `id_checklist_idx` (`id_checklist`),
  CONSTRAINT `id_checklist` FOREIGN KEY (`id_checklist`) REFERENCES `tblCheckLists` (`id_checklist`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Individual line items for populating checklists on local db ';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblCheckLists_Items`
--

LOCK TABLES `tblCheckLists_Items` WRITE;
/*!40000 ALTER TABLE `tblCheckLists_Items` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblCheckLists_Items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblEvents`
--

DROP TABLE IF EXISTS `tblEvents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblEvents` (
  `id_Event` int(11) NOT NULL AUTO_INCREMENT,
  `event_name` varchar(50) DEFAULT NULL COMMENT 'Short name such as "15Feb15 Snowstorm"',
  `event_desc` text COMMENT 'Text description of event',
  `event_active` tinyint(1) DEFAULT '1' COMMENT 'Is this event still active? (Default is true)',
  PRIMARY KEY (`id_Event`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Data for weather or emergeny events';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblEvents`
--

LOCK TABLES `tblEvents` WRITE;
/*!40000 ALTER TABLE `tblEvents` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblEvents` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblGroup`
--

DROP TABLE IF EXISTS `tblGroup`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblGroup` (
  `id_Group` int(11) NOT NULL,
  `groupName` varchar(50) DEFAULT NULL,
  `groupDescription` varchar(100) DEFAULT NULL,
  `groupLongDesc` mediumtext,
  PRIMARY KEY (`id_Group`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Groups to which users many belong. This needs to be fleshed ';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblGroup`
--

LOCK TABLES `tblGroup` WRITE;
/*!40000 ALTER TABLE `tblGroup` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblGroup` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblNotifications`
--

DROP TABLE IF EXISTS `tblNotifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblNotifications` (
  `id_notifications` int(11) NOT NULL COMMENT 'ID',
  `notification_timestamp` timestamp NULL DEFAULT NULL COMMENT 'Date/Time index for notification.',
  `notification_text` text COMMENT 'Text string to send to end users',
  PRIMARY KEY (`id_notifications`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Holds information for push notifications';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblNotifications`
--

LOCK TABLES `tblNotifications` WRITE;
/*!40000 ALTER TABLE `tblNotifications` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblNotifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblPublicReporting`
--

DROP TABLE IF EXISTS `tblPublicReporting`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblPublicReporting` (
  `id_PublicReporting` int(11) NOT NULL AUTO_INCREMENT,
  `id_Event` int(11) NOT NULL COMMENT 'Event for which this is a report. (Assumption: required.)',
  `publicReport_text` mediumtext,
  `publicReport_image` blob COMMENT 'Possibly move this to a table -- allow for multiple images more easily.',
  PRIMARY KEY (`id_PublicReporting`,`id_Event`),
  KEY `id_event_idx` (`id_Event`),
  CONSTRAINT `id_event` FOREIGN KEY (`id_Event`) REFERENCES `tblEvents` (`id_Event`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Damage reports and information from public. Assumptions: no ';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblPublicReporting`
--

LOCK TABLES `tblPublicReporting` WRITE;
/*!40000 ALTER TABLE `tblPublicReporting` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblPublicReporting` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblTest`
--

DROP TABLE IF EXISTS `tblTest`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblTest` (
  `id` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblTest`
--

LOCK TABLES `tblTest` WRITE;
/*!40000 ALTER TABLE `tblTest` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblTest` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblUsers`
--

DROP TABLE IF EXISTS `tblUsers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblUsers` (
  `id_user` int(11) NOT NULL AUTO_INCREMENT COMMENT 'User ID (int)',
  `userLastName` varchar(45) DEFAULT NULL COMMENT 'User''s Last Name',
  `userFirstName` varchar(45) DEFAULT NULL COMMENT 'User''s First Name',
  `userEmail` varchar(100) DEFAULT NULL COMMENT 'User email',
  PRIMARY KEY (`id_user`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 COMMENT='Data for backend users';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblUsers`
--

LOCK TABLES `tblUsers` WRITE;
/*!40000 ALTER TABLE `tblUsers` DISABLE KEYS */;
INSERT INTO `tblUsers` VALUES (1,'Smith','Bob','bob@email.com');
/*!40000 ALTER TABLE `tblUsers` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2015-03-07 16:58:07
