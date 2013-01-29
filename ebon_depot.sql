-- MySQL dump 10.13  Distrib 5.5.27, for Linux (x86_64)
--
-- Host: localhost    Database: ebon_depot
-- ------------------------------------------------------
-- Server version	5.5.27-log

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
-- Table structure for table `forums`
--

DROP TABLE IF EXISTS `forums`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `forums` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text,
  `last_topic` text,
  `last_topic_id` int(11) DEFAULT NULL,
  `last_poster` text,
  `last_poster_id` text,
  `last_poster_pid` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `forums`
--

LOCK TABLES `forums` WRITE;
/*!40000 ALTER TABLE `forums` DISABLE KEYS */;
INSERT INTO `forums` VALUES (4,'SC2 Replays','Some sort of 1v1',103,'Cortstar','20',0),(5,'General Talk','General Talk Created!',99,'bonywonix','17',0),(6,'SC2 Strategy','Mass Reaper',100,'bonywonix','17',0);
/*!40000 ALTER TABLE `forums` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `maps`
--

DROP TABLE IF EXISTS `maps`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `maps` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` char(128) DEFAULT NULL,
  `path` char(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `maps`
--

LOCK TABLES `maps` WRITE;
/*!40000 ALTER TABLE `maps` DISABLE KEYS */;
INSERT INTO `maps` VALUES (1,'Akilon Flats','assets/maps/akilonflats.jpg'),(2,'Antiga Shipyard','assets/maps/antigashipyard.jpg'),(3,'Cloud Kingdom LE','assets/maps/cloudkingdom.jpg'),(4,'Condemned Ridge','assets/maps/condemnedridge.jpg'),(5,'Daybreak LE','assets/maps/daybreak.jpg'),(6,'Entombed Valley','assets/maps/entombedvalley.jpg'),(7,'Metalopolis','assets/maps/metalopolis.jpg'),(8,'Newkirk District','assets/maps/newkirkdistrict.jpg'),(9,'Ohana LE','assets/maps/ohana.jpg'),(10,'Shakuras Plateau','assets/maps/shakurasplateau.jpg'),(11,'The Shattered Temple','assets/maps/shattered.jpg'),(12,'Tal\'Darim Altar','assets/maps/taldarimaltar.jpg'),(13,'Typhon Peaks','assets/maps/typhonpeaks.jpg'),(14,'Xel\'Naga Caverns','assets/maps/xelnagacaverns.jpg');
/*!40000 ALTER TABLE `maps` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `matches`
--

DROP TABLE IF EXISTS `matches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `matches` (
  `id` int(11) NOT NULL,
  `in_tournament` int(11) DEFAULT NULL,
  `player_1` int(11) DEFAULT NULL,
  `player_2` int(11) DEFAULT NULL,
  `player_1_score` int(11) DEFAULT NULL,
  `player_2_score` int(11) DEFAULT NULL,
  `ro` int(11) DEFAULT NULL,
  `bracket_num` int(11) DEFAULT NULL,
  `replay` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `matches`
--

LOCK TABLES `matches` WRITE;
/*!40000 ALTER TABLE `matches` DISABLE KEYS */;
/*!40000 ALTER TABLE `matches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `posts`
--

DROP TABLE IF EXISTS `posts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `subject` text,
  `message` text,
  `in_reply_to` int(11) DEFAULT NULL,
  `author` text,
  `author_uid` int(11) DEFAULT NULL,
  `date` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=99 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `posts`
--

LOCK TABLES `posts` WRITE;
/*!40000 ALTER TABLE `posts` DISABLE KEYS */;
/*!40000 ALTER TABLE `posts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `topics`
--

DROP TABLE IF EXISTS `topics`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `topics` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `subject` text,
  `author` text,
  `date` text,
  `last_reply` text,
  `last_poster` text,
  `author_uid` int(11) DEFAULT NULL,
  `message` text,
  `in_forum` int(11) DEFAULT NULL,
  `last_reply_uid` int(11) DEFAULT NULL,
  `last_reply_pid` int(11) DEFAULT NULL,
  `replay` char(255) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=104 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `topics`
--

LOCK TABLES `topics` WRITE;
/*!40000 ALTER TABLE `topics` DISABLE KEYS */;
INSERT INTO `topics` VALUES (84,'Fairly long, map-encompassing TvT','bonywonix','17-01-2013 14:24:35','17-01-2013 14:24:35','bonywonix',17,'In which there is a lot of mech vs biomech. Pretty good platinum-level play.',4,17,0,'assets/replays/17_20130117142435.SC2Replay'),(99,'General Talk Created!','bonywonix','17-01-2013 16:50:25','17-01-2013 16:50:25','bonywonix',17,'This forum exists for topics irrelevant to any other existing forum.',5,17,0,'0'),(100,'Mass Reaper','bonywonix','18-01-2013 22:20:45','18-01-2013 22:20:45','bonywonix',17,'Mass reaper is an example of a strategy you should not post because it\'s not a very good strategy all things considered.<br />\r\n<br />\r\nAdding replays enhances strategical posts.',6,17,0,'0'),(103,'Some sort of 1v1','Cortstar','19-01-2013 09:50:52','19-01-2013 09:50:52','Cortstar',20,'I don\'t remember this game at all.',4,20,0,'assets/replays/20_20130119095052.SC2Replay');
/*!40000 ALTER TABLE `topics` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tournaments`
--

DROP TABLE IF EXISTS `tournaments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tournaments` (
  `id` int(11) NOT NULL,
  `name` text,
  `participants_num` int(11) DEFAULT '0',
  `current_round` int(11) DEFAULT '0',
  `winner` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tournaments`
--

LOCK TABLES `tournaments` WRITE;
/*!40000 ALTER TABLE `tournaments` DISABLE KEYS */;
INSERT INTO `tournaments` VALUES (0,'test Tourney',0,0,0);
/*!40000 ALTER TABLE `tournaments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` text,
  `email` text,
  `postcount` int(11) DEFAULT '0',
  `join_date` text,
  `rank` text,
  `profile_pic` text,
  `bnet_id` varchar(255) DEFAULT '0',
  `char_code` int(11) DEFAULT '0',
  `bnet_name` varchar(255) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (17,'bonywonix','ebonwumon@gmail.com',3,'22-12-2012 21:00:13','admin','assets/profile/uid_17','2275201',412,'FGTlllllllll'),(18,'Ebonwumon','ebonwumon@depotwarehouse.net',0,'22-12-2012 21:29:05','user','assets/profile/uid_0.gif','0',0,'0'),(19,'hezzyy','heather@depotwarehouse.net',0,'22-12-2012 21:32:13','user','assets/profile/uid_0.gif','0',0,'0'),(20,'Cortstar','cortland@ualberta.ca',1,'22-12-2012 22:04:17','user','assets/profile/uid_0.gif','3014719',610,'FGTcortstar'),(21,'','ken.mundell@gmail.com',0,'31-12-2012 21:47:05','user','assets/profile/uid_0.gif','0',0,'0'),(22,NULL,NULL,0,'08-01-2013 16:57:34','user','assets/profile/uid_0.gif','0',0,'0'),(23,'parish@ualberta.ca','parish@ualberta.ca',0,'12-01-2013 13:11:47','user','assets/profile/uid_0.gif','1662338',0,'FGTShuffle'),(24,'hjklz','andyyaosoccer@gmail.com',0,'13-01-2013 12:46:40','user','assets/profile/uid_0.gif','0',0,'0');
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

-- Dump completed on 2013-01-22 18:57:31
