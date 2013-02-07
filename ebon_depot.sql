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
-- Table structure for table `bo_tournament`
--

DROP TABLE IF EXISTS `bo_tournament`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bo_tournament` (
  `tourn_id` int(11) DEFAULT NULL,
  `ro` int(11) DEFAULT NULL,
  `bo` int(11) DEFAULT NULL,
  KEY `tourn_id` (`tourn_id`),
  CONSTRAINT `bo_tournament_ibfk_2` FOREIGN KEY (`tourn_id`) REFERENCES `tournaments` (`id`) ON DELETE CASCADE,
  CONSTRAINT `bo_tournament_ibfk_1` FOREIGN KEY (`tourn_id`) REFERENCES `tournaments` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bo_tournament`
--

LOCK TABLES `bo_tournament` WRITE;
/*!40000 ALTER TABLE `bo_tournament` DISABLE KEYS */;
INSERT INTO `bo_tournament` VALUES (3,1,5),(3,2,3),(3,3,1),(3,4,1),(3,5,1);
/*!40000 ALTER TABLE `bo_tournament` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bracket`
--

DROP TABLE IF EXISTS `bracket`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bracket` (
  `match_id` int(11) DEFAULT NULL,
  `position` int(11) DEFAULT NULL,
  `bo` int(11) DEFAULT '1',
  `game_num` int(11) DEFAULT '1',
  `ro` int(11) DEFAULT NULL,
  `map` int(11) DEFAULT '5',
  `in_tournament` int(11) DEFAULT NULL,
  KEY `in_tournament` (`in_tournament`),
  KEY `match_id` (`match_id`),
  KEY `map` (`map`),
  CONSTRAINT `bracket_ibfk_3` FOREIGN KEY (`map`) REFERENCES `maps` (`id`) ON DELETE CASCADE,
  CONSTRAINT `bracket_ibfk_1` FOREIGN KEY (`in_tournament`) REFERENCES `tournaments` (`id`) ON DELETE CASCADE,
  CONSTRAINT `bracket_ibfk_2` FOREIGN KEY (`match_id`) REFERENCES `matches` (`match_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bracket`
--

LOCK TABLES `bracket` WRITE;
/*!40000 ALTER TABLE `bracket` DISABLE KEYS */;
INSERT INTO `bracket` VALUES (339,0,1,1,5,9,3),(340,1,1,1,5,9,3),(341,2,1,1,5,9,3),(342,3,1,1,5,9,3),(343,4,1,1,5,9,3),(344,5,1,1,5,9,3),(345,6,1,1,5,9,3),(346,7,1,1,5,9,3),(347,8,1,1,5,9,3),(348,9,1,1,5,9,3),(349,10,1,1,5,9,3),(350,11,1,1,5,9,3),(351,12,1,1,5,9,3),(352,13,1,1,5,9,3),(353,14,1,1,5,9,3),(354,15,1,1,5,9,3),(355,0,1,1,4,5,3),(356,1,1,1,4,5,3),(357,2,1,1,4,5,3),(358,3,1,1,4,5,3),(359,4,1,1,4,5,3),(360,5,1,1,4,5,3),(361,6,1,1,4,5,3),(362,7,1,1,4,5,3),(363,0,1,1,3,3,3),(364,1,1,1,3,3,3),(365,2,1,1,3,3,3),(366,3,1,1,3,3,3),(367,0,3,1,2,6,3),(368,1,3,1,2,6,3),(369,0,3,2,2,6,3),(370,1,3,2,2,6,3),(371,0,3,3,2,6,3),(372,1,3,3,2,6,3),(373,0,5,1,1,8,3),(374,0,5,2,1,8,3),(375,0,5,3,1,8,3),(376,0,5,4,1,8,3),(377,0,5,5,1,8,3);
/*!40000 ALTER TABLE `bracket` ENABLE KEYS */;
UNLOCK TABLES;

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
  `in_tournament` int(11) DEFAULT NULL,
  `player_1` int(11) DEFAULT '0',
  `player_2` int(11) DEFAULT '0',
  `replay` text,
  `winner` int(11) DEFAULT '0',
  `match_id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`match_id`),
  KEY `in_tournament` (`in_tournament`),
  CONSTRAINT `matches_ibfk_1` FOREIGN KEY (`in_tournament`) REFERENCES `tournaments` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=378 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `matches`
--

LOCK TABLES `matches` WRITE;
/*!40000 ALTER TABLE `matches` DISABLE KEYS */;
INSERT INTO `matches` VALUES (3,31,54,NULL,31,339),(3,32,58,NULL,58,340),(3,36,59,NULL,59,341),(3,37,57,NULL,57,342),(3,39,24,NULL,24,343),(3,40,55,NULL,55,344),(3,43,50,NULL,50,345),(3,46,62,NULL,62,346),(3,45,65,NULL,65,347),(3,47,60,NULL,47,348),(3,48,66,NULL,66,349),(3,49,-1,NULL,49,350),(3,51,-1,NULL,51,351),(3,53,-1,NULL,53,352),(3,56,-1,NULL,56,353),(3,52,-1,NULL,52,354),(3,31,58,NULL,58,355),(3,59,57,NULL,59,356),(3,24,55,NULL,55,357),(3,50,62,NULL,50,358),(3,65,47,NULL,47,359),(3,66,49,NULL,66,360),(3,51,53,NULL,53,361),(3,56,52,NULL,52,362),(3,58,59,NULL,58,363),(3,55,50,NULL,55,364),(3,47,66,NULL,47,365),(3,53,52,NULL,52,366),(3,58,55,NULL,58,367),(3,47,52,NULL,47,368),(3,58,55,NULL,55,369),(3,47,52,NULL,47,370),(3,58,55,NULL,58,371),(3,47,52,NULL,0,372),(3,58,47,NULL,0,373),(3,58,47,NULL,0,374),(3,58,47,NULL,0,375),(3,58,47,NULL,0,376),(3,58,47,NULL,0,377);
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
) ENGINE=InnoDB AUTO_INCREMENT=104 DEFAULT CHARSET=utf8;
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
) ENGINE=InnoDB AUTO_INCREMENT=112 DEFAULT CHARSET=utf8;
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
-- Table structure for table `tournament_registered`
--

DROP TABLE IF EXISTS `tournament_registered`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tournament_registered` (
  `tourn_id` int(11) DEFAULT NULL,
  `uid` int(11) DEFAULT NULL,
  KEY `tourn_id` (`tourn_id`),
  KEY `uid` (`uid`),
  CONSTRAINT `tournament_registered_ibfk_2` FOREIGN KEY (`uid`) REFERENCES `user` (`id`) ON DELETE CASCADE,
  CONSTRAINT `tournament_registered_ibfk_1` FOREIGN KEY (`tourn_id`) REFERENCES `tournaments` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tournament_registered`
--

LOCK TABLES `tournament_registered` WRITE;
/*!40000 ALTER TABLE `tournament_registered` DISABLE KEYS */;
INSERT INTO `tournament_registered` VALUES (3,31),(3,32),(3,36),(3,37),(3,39),(3,40),(3,43),(3,46),(3,45),(3,47),(3,48),(3,49),(3,51),(3,53),(3,56),(3,52),(3,54),(3,58),(3,59),(3,57),(3,24),(3,55),(3,50),(3,62),(3,65),(3,60),(3,66);
/*!40000 ALTER TABLE `tournament_registered` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tournaments`
--

DROP TABLE IF EXISTS `tournaments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tournaments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text,
  `participants_num` int(11) DEFAULT '0',
  `current_round` int(11) DEFAULT '0',
  `winner` int(11) DEFAULT '0',
  `started` int(11) DEFAULT '0',
  `num_rounds` int(11) DEFAULT '1',
  `info` text,
  `start_date` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tournaments`
--

LOCK TABLES `tournaments` WRITE;
/*!40000 ALTER TABLE `tournaments` DISABLE KEYS */;
INSERT INTO `tournaments` VALUES (3,'[FGT] Open BETA Test Tournament',0,1,0,1,5,'The first tournament utilizing [FGT]\'s tournament software. There are bound to be bugs, so please be patient while they\'re ironed out. <br>If you see any bugs or discover any desired features while doing the tournament, please send an email to ebonwumon@depotwarehouse.net.<br><br>This tournament is a single-elimination tournament with b01 rounds until the semifinals which are b03 and the finals are b05. <br><br>The prize structure will be $5 winner-take-all. Second and third place will both recieve a custom crayon drawing depicting whatever they choose (nonpornographic). Top 3 will also recieve, if they want, first round byes in the next tournament held here. <br><br>Start time is 6:30pm MST. Please be in ingame channel FGT. <br><br>Thanks for helping out!',NULL);
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
  `bnet_league` char(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=68 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (17,'bonywonix','ebonwumon@gmail.com',3,'22-12-2012 21:00:13','admin','assets/profile/uid_17','2275201',412,'FGTlllllllll','Platinum'),(19,'hezzyy','heather@depotwarehouse.net',0,'22-12-2012 21:32:13','user','assets/profile/uid_0.gif','0',0,'0',NULL),(20,'Cortstar','cortland@ualberta.ca',1,'22-12-2012 22:04:17','user','assets/profile/uid_0.gif','3014719',610,'FGTcortstar',NULL),(23,'FGTShuffle','parish@ualberta.ca',0,'12-01-2013 13:11:47','user','assets/profile/uid_0.gif','1662338',0,'FGTShuffle',NULL),(24,'hjklz','andyyaosoccer@gmail.com',0,'13-01-2013 12:46:40','user','assets/profile/uid_0.gif','506160',773,'FGThjklzEX',NULL),(25,'zturchan','zak.turchansky@gmail.com',0,'24-01-2013 17:48:20','user','assets/profile/uid_0.gif','2472052',259,'zturchan',NULL),(26,'AlphaLioness','chadwyck@ualberta.ca',0,'24-01-2013 17:50:17','user','assets/profile/uid_0.gif','1020846',776,'OyGuvna',NULL),(27,'KingofOrcs','yellowknife911@gmail.com',0,'24-01-2013 19:47:22','user','assets/profile/uid_0.gif','3520137',375,'KingofOrcs',NULL),(28,'yogybear','devyn@ualberta.ca',0,'24-01-2013 20:30:07','user','assets/profile/uid_0.gif','0',0,'0',NULL),(29,'Ebonwumon','ebonwumon@depotwarehouse.net',0,'27-01-2013 11:28:53','user','assets/profile/uid_0.gif','0',0,'0',NULL),(30,'Grackhorse','esieben@ualberta.ca',0,'27-01-2013 12:38:31','user','assets/profile/uid_0.gif','2907616',655,'seVen',NULL),(31,'Baconology','bscudera@gmail.com',0,'06-02-2013 14:56:04','user','assets/profile/uid_0.gif','3269031',796,'Baconology','Masters'),(32,'','ohsomeant@gmail.com',0,'06-02-2013 15:34:43','user','assets/profile/uid_0.gif','3578574',979,'Starry','Diamond'),(33,'TigerKarl','goethemeetscicero@yahoo.de',0,'06-02-2013 15:45:20','user','assets/profile/uid_0.gif','0',0,'0',NULL),(34,'BatesC','batescsc@yahoo.com',0,'06-02-2013 15:53:56','user','assets/profile/uid_0.gif','0',0,'0',NULL),(35,'bigplayasaurusrex@gmail.com','bigplayasaurusrex@gmail.com',0,'06-02-2013 16:12:09','user','assets/profile/uid_0.gif','1809074',666,'Grief','Masters'),(36,'ericbojo','ericbojo@gmail.com',0,'06-02-2013 16:18:08','user','assets/profile/uid_0.gif','1705704',897,'DeNaDa','Diamond'),(37,'Caesar','changedcaesar@gmail.com',0,'06-02-2013 16:22:05','user','assets/profile/uid_0.gif','4169125',605,'Caesar','Gold'),(38,'mbm.sting','mbm.sting@gmail.com',0,'06-02-2013 16:26:18','user','assets/profile/uid_0.gif','0',0,'0',NULL),(39,'Donten','connor.hunszinger@gmail.com',0,'06-02-2013 16:29:52','user','assets/profile/uid_0.gif','1053976',466,'CtrlAltElite','Platinum'),(40,'ProfessorFrink','professorfrinkenator@gmail.com',0,'06-02-2013 16:41:19','user','assets/profile/uid_40','1302556',0,'ProfFrink','Diamond'),(41,'darthcaesar','chessdragon5000@gmail.com',0,'06-02-2013 16:46:05','user','assets/profile/uid_0.gif','0',0,'0',NULL),(42,'Krelush','cush33312@yahoo.com',0,'06-02-2013 17:03:02','user','assets/profile/uid_0.gif','0',0,'0',NULL),(43,'fish','jodagear@gmail.com',0,'06-02-2013 17:03:22','user','assets/profile/uid_0.gif','3612101',618,'Fish','Silver'),(44,'Sheepit','undeadsheep@gmail.com',0,'06-02-2013 17:06:50','user','assets/profile/uid_44','0',0,'0',NULL),(45,'drakeball90','drakeball90@gmail.com',0,'06-02-2013 17:07:10','user','assets/profile/uid_0.gif','2031483',236,'White','Diamond'),(46,'elysaforever','elyssaforever@gmail.com',0,'06-02-2013 17:09:16','user','assets/profile/uid_0.gif','4018451',809,'Pinkman','Bronze'),(47,'rgscythe','aukistis@gmail.com',0,'06-02-2013 17:14:24','user','assets/profile/uid_0.gif','730044',837,'rGScythe','Masters'),(48,'luvs2play','arronlorenz@gmail.com',0,'06-02-2013 17:31:38','user','assets/profile/uid_0.gif','2974365',101,'Fuzzy','Silver'),(49,'za','zander.eldredge67@gmail.com',0,'06-02-2013 17:33:14','user','assets/profile/uid_0.gif','3576232',726,'SPooNiTe','Silver'),(50,'TheSambasti','merzbasti95@gmail.com',0,'06-02-2013 17:40:54','user','assets/profile/uid_0.gif','3607128',972,'TheSambasti','Gold'),(51,'MuffinTopper','bstpierr@gmail.com',0,'06-02-2013 17:41:46','user','assets/profile/uid_0.gif','3425480',846,'MuffinTopper','Gold'),(52,'mechanicus','davidvsd734@gmail.com',0,'06-02-2013 17:44:19','user','assets/profile/uid_0.gif','3212664',218,'mEcHaNiCuS','Masters'),(53,'mheroin','federico@mheroin.com',0,'06-02-2013 17:46:34','user','assets/profile/uid_0.gif','465715',809,'mheroin','Gold'),(54,'kaizen','fabiansoderlund@gmail.com',0,'06-02-2013 17:48:20','user','assets/profile/uid_0.gif','2061037',924,'Kaizen','Silver'),(55,'FXOSirRobin','robbieswitts@gmail.com',0,'06-02-2013 17:50:58','user','assets/profile/uid_0.gif','687150',629,'FXOSirRobin','Masters'),(56,'ogg25','michael.daw0@gmail.com',0,'06-02-2013 17:53:25','user','assets/profile/uid_0.gif','344238',885,'kanada','Diamond'),(57,'Mudkipnick','mudkipnick1994@gmail.com',0,'06-02-2013 17:55:42','user','assets/profile/uid_0.gif','2053112',518,'Mudkipnick','Diamond'),(58,'zoku','sc2zoku@gmail.com',0,'06-02-2013 17:59:10','user','assets/profile/uid_0.gif','1830362',342,'Zoku','Grandmaster'),(59,'dball90','dball90@gmail.com',0,'06-02-2013 17:59:22','user','assets/profile/uid_0.gif','1538345',951,'Goodman','Platinum'),(60,'Sheep','tnt4693@gmail.com',0,'06-02-2013 18:00:24','user','assets/profile/uid_0.gif','1091217',534,'Sheepit','Gold'),(61,'Sambasti','thesambasti@gmail.com',0,'06-02-2013 18:15:11','user','assets/profile/uid_0.gif','0',0,'0',NULL),(62,'noxiousnick','nkerns92@gmail.com',0,'06-02-2013 18:23:09','user','assets/profile/uid_0.gif','1708766',354,'NoxiousNick','Gold'),(63,'ynoj','lee.jony@hotmail.com',0,'06-02-2013 18:24:29','user','assets/profile/uid_0.gif','0',0,'0',NULL),(64,'swagasaurus','jaedongfighting@gmail.com',0,'06-02-2013 18:26:22','user','assets/profile/uid_0.gif','0',0,'0',NULL),(65,'leakymilkstraw','thedavidhinojosa@gmail.com',0,'06-02-2013 18:39:05','user','assets/profile/uid_0.gif','362905',987,'istartfires','Diamond'),(66,'k33h30n','nam.keeheon@gmail.com',0,'06-02-2013 18:47:16','user','assets/profile/uid_0.gif','2848336',696,'lIllIIlIlII','Platinum'),(67,'jjbennett25@gmail.com','jjbennett25@gmail.com',0,'06-02-2013 22:26:48','user','assets/profile/uid_0.gif','809466',647,'Zodaris','Silver');
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

-- Dump completed on 2013-02-07 16:36:38
