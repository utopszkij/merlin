-- MySQL dump 10.13  Distrib 8.0.36, for Linux (x86_64)
--
-- Host: localhost    Database: merlin
-- ------------------------------------------------------
-- Server version	8.0.36-0ubuntu0.22.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
SET NAMES utf8;
SET FOREIGN_KEY_CHECKS = 0;
--
-- Table structure for table `groups`
--

DROP TABLE IF EXISTS `groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `groups` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `name` varchar(128) COLLATE utf8mb3_hungarian_ci NOT NULL,
  `description` text COLLATE utf8mb3_hungarian_ci NOT NULL,
  `state` varchar(32) COLLATE utf8mb3_hungarian_ci NOT NULL DEFAULT 'member' COMMENT 'active | closed | paused | Proposal',
  `parent_id` bigint NOT NULL COMMENT 'Tree structure',
  `config` text COLLATE utf8mb3_hungarian_ci NOT NULL COMMENT 'JSON string',
  `ranks` varchar(255) COLLATE utf8mb3_hungarian_ci NOT NULL DEFAULT 'members,admin' COMMENT 'comma-separated list',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_UNIQUE` (`name`),
  KEY `parent_fk_idx` (`parent_id`),
  KEY `name_idx` (`name`),
  CONSTRAINT `parent_fk` FOREIGN KEY (`parent_id`) REFERENCES `groups` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_hungarian_ci COMMENT='groups ';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `groups`
--

LOCK TABLES `groups` WRITE;
/*!40000 ALTER TABLE `groups` DISABLE KEYS */;
INSERT INTO `groups` VALUES (1,'Root	','Root','active',1,'{}','member'),
(2,'Registered','Registered users','active',1,'{}','member'),
(3,'System admins	','System admins','active',1,'{}','member');
/*!40000 ALTER TABLE `groups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `groups_like`
--

DROP TABLE IF EXISTS `groups_like`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `groups_like` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `created_at` datetime NOT NULL,
  `type` varchar(32) COLLATE utf8mb3_hungarian_ci NOT NULL DEFAULT 'like' COMMENT 'like|dislike',
  `created_by` bigint NOT NULL,
  `parent_id` bigint NOT NULL COMMENT 'groups_id',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  KEY `fk_user_like_users1_idx` (`created_by`),
  KEY `fk_user_like_users21_idx` (`parent_id`),
  CONSTRAINT `fk_user_like_users11` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  CONSTRAINT `fk_user_like_users21` FOREIGN KEY (`parent_id`) REFERENCES `groups` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_hungarian_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `groups_like`
--

LOCK TABLES `groups_like` WRITE;
/*!40000 ALTER TABLE `groups_like` DISABLE KEYS */;
/*!40000 ALTER TABLE `groups_like` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `groups_log`
--

DROP TABLE IF EXISTS `groups_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `groups_log` (
  `id` bigint NOT NULL,
  `parent_id` bigint NOT NULL,
  `time` datetime NOT NULL,
  `event` text COLLATE utf8mb3_hungarian_ci NOT NULL COMMENT 'eventType  infos',
  `users_id` bigint DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `time` (`time`),
  KEY `fk_groups_log_idx` (`parent_id`),
  CONSTRAINT `fk_groups_log` FOREIGN KEY (`parent_id`) REFERENCES `groups` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_hungarian_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `groups_log`
--

LOCK TABLES `groups_log` WRITE;
/*!40000 ALTER TABLE `groups_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `groups_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `groups_vote`
--

DROP TABLE IF EXISTS `groups_vote`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `groups_vote` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `created_at` datetime NOT NULL,
  `value` int NOT NULL COMMENT '1|2|3|4|5',
  `created_by` bigint NOT NULL,
  `parent_id` bigint NOT NULL COMMENT 'groups_id',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  KEY `fk_group_vote_idx` (`parent_id`),
  CONSTRAINT `fk_user_like_users121` FOREIGN KEY (`parent_id`) REFERENCES `users` (`id`),
  CONSTRAINT `fk_user_like_users221` FOREIGN KEY (`parent_id`) REFERENCES `groups` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=armscii8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `groups_vote`
--

LOCK TABLES `groups_vote` WRITE;
/*!40000 ALTER TABLE `groups_vote` DISABLE KEYS */;
/*!40000 ALTER TABLE `groups_vote` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `iplocks`
--

DROP TABLE IF EXISTS `iplocks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `iplocks` (
  `id` bigint NOT NULL,
  `ip` varchar(64) COLLATE utf8mb3_hungarian_ci NOT NULL,
  `error_count` int NOT NULL DEFAULT '0',
  `lock_time` int DEFAULT '0' COMMENT '0 - not locked | lock time start',
  PRIMARY KEY (`id`),
  KEY `ip_index` (`ip`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_hungarian_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `iplocks`
--

LOCK TABLES `iplocks` WRITE;
/*!40000 ALTER TABLE `iplocks` DISABLE KEYS */;
/*!40000 ALTER TABLE `iplocks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `members`
--

DROP TABLE IF EXISTS `members`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `members` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `rank` varchar(128) COLLATE utf8mb3_hungarian_ci NOT NULL DEFAULT 'member' COMMENT 'example: mermber | admin .  -see  rankas of group.',
  `state` varchar(32) COLLATE utf8mb3_hungarian_ci NOT NULL DEFAULT 'draft' COMMENT 'active | aspirant | closed | disabled | paused | draft',
  `users_id` bigint NOT NULL,
  `groups_id` bigint NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_members_users_idx` (`users_id`),
  KEY `fk_member_groups` (`groups_id`),
  CONSTRAINT `fk_member_groups` FOREIGN KEY (`groups_id`) REFERENCES `groups` (`id`),
  CONSTRAINT `fk_members_users` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_hungarian_ci COMMENT='User group memberships. A user can hold multiple positions in the same group';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `members`
--

LOCK TABLES `members` WRITE;
/*!40000 ALTER TABLE `members` DISABLE KEYS */;
/*!40000 ALTER TABLE `members` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `members_like`
--

DROP TABLE IF EXISTS `members_like`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `members_like` (
  `id` bigint NOT NULL AUTO_INCREMENT COMMENT 'time of lock start',
  `created_at` datetime NOT NULL,
  `type` varchar(32) COLLATE utf8mb3_hungarian_ci NOT NULL DEFAULT 'like' COMMENT 'like|dislike',
  `created_by` bigint NOT NULL,
  `parent_id` bigint NOT NULL COMMENT 'members_id',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  KEY `fk_user_like_users1_idx` (`created_by`),
  KEY `fk_user_like_users20_idx` (`parent_id`),
  CONSTRAINT `fk_user_like_users10` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  CONSTRAINT `fk_user_like_users20` FOREIGN KEY (`parent_id`) REFERENCES `members` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_hungarian_ci COMMENT='Bad login attempt counter (if username is wrong)\n';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `members_like`
--

LOCK TABLES `members_like` WRITE;
/*!40000 ALTER TABLE `members_like` DISABLE KEYS */;
/*!40000 ALTER TABLE `members_like` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `members_log`
--

DROP TABLE IF EXISTS `members_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `members_log` (
  `id` bigint NOT NULL,
  `parent_id` bigint NOT NULL COMMENT 'members_id',
  `time` datetime NOT NULL,
  `event` text COLLATE utf8mb3_hungarian_ci NOT NULL COMMENT 'eventType  infos',
  `users_id` bigint DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `time` (`time`),
  KEY `fk_members_log_idx` (`parent_id`),
  CONSTRAINT `fk_members_log` FOREIGN KEY (`parent_id`) REFERENCES `members` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_hungarian_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `members_log`
--

LOCK TABLES `members_log` WRITE;
/*!40000 ALTER TABLE `members_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `members_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `members_vote`
--

DROP TABLE IF EXISTS `members_vote`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `members_vote` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `created_at` datetime NOT NULL,
  `value` int NOT NULL COMMENT '1|2|3|4|5',
  `created_by` bigint NOT NULL,
  `parent_id` bigint NOT NULL COMMENT 'members_id',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  KEY `fk_user_like_users1_idx` (`created_by`),
  KEY `fk_user_like_users2_idx` (`parent_id`),
  CONSTRAINT `fk_user_like_users120` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  CONSTRAINT `fk_user_like_users220` FOREIGN KEY (`parent_id`) REFERENCES `members` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=armscii8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `members_vote`
--

LOCK TABLES `members_vote` WRITE;
/*!40000 ALTER TABLE `members_vote` DISABLE KEYS */;
/*!40000 ALTER TABLE `members_vote` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `username` varchar(16) COLLATE utf8mb3_hungarian_ci NOT NULL,
  `email` varchar(128) COLLATE utf8mb3_hungarian_ci NOT NULL,
  `password` varchar(128) COLLATE utf8mb3_hungarian_ci NOT NULL COMMENT 'hash',
  `realname` varchar(64) COLLATE utf8mb3_hungarian_ci NOT NULL,
  `avatar` varchar(128) COLLATE utf8mb3_hungarian_ci NOT NULL COMMENT 'absolute url',
  `email_verifyed` tinyint NOT NULL DEFAULT '0' COMMENT '0-no|1-yes',
  `status` varchar(16) COLLATE utf8mb3_hungarian_ci NOT NULL DEFAULT 'notActivated' COMMENT 'enabled | disabled | deleted | locked | notActivated',
  `two_factor` tinyint NOT NULL DEFAULT '0' COMMENT '0 -no | 1 -yes',
  `two_factor_secret` varchar(128) COLLATE utf8mb3_hungarian_ci NOT NULL,
  `error_count` int NOT NULL COMMENT 'Bad login attempt counter (username correct)',
  `lock_time` int DEFAULT '0' COMMENT 'time of lock start|0-notlocked',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email_UNIQUE` (`email`),
  UNIQUE KEY `username_UNIQUE` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_hungarian_ci COMMENT='registered users';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'algorithm','none','none','algorithm','none',1,'disbled',0,'none',0,0);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users_like`
--

DROP TABLE IF EXISTS `users_like`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users_like` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `created_at` datetime NOT NULL,
  `type` varchar(32) COLLATE utf8mb3_hungarian_ci NOT NULL DEFAULT 'like' COMMENT 'like|dislike',
  `created_by` bigint NOT NULL,
  `parent_id` bigint NOT NULL COMMENT 'users_id',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  KEY `fk_user_like_users1_idx` (`created_by`),
  KEY `fk_user_like_users2_idx` (`parent_id`),
  CONSTRAINT `fk_user_like_users1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  CONSTRAINT `fk_user_like_users2` FOREIGN KEY (`parent_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_hungarian_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users_like`
--

LOCK TABLES `users_like` WRITE;
/*!40000 ALTER TABLE `users_like` DISABLE KEYS */;
/*!40000 ALTER TABLE `users_like` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users_log`
--

DROP TABLE IF EXISTS `users_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users_log` (
  `id` bigint NOT NULL,
  `parent_id` bigint NOT NULL,
  `time` datetime NOT NULL,
  `event` text COLLATE utf8mb3_hungarian_ci NOT NULL COMMENT 'eventType  infos',
  `users_id` bigint DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `time` (`time`),
  KEY `fk_users_log` (`parent_id`),
  CONSTRAINT `fk_users_log` FOREIGN KEY (`parent_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_hungarian_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users_log`
--

LOCK TABLES `users_log` WRITE;
/*!40000 ALTER TABLE `users_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `users_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users_vote`
--

DROP TABLE IF EXISTS `users_vote`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users_vote` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `created_at` datetime NOT NULL,
  `value` int NOT NULL COMMENT '1|2|3|4|5',
  `created_by` bigint NOT NULL,
  `parent_id` bigint NOT NULL COMMENT 'users_id',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  KEY `fk_user_like_users1_idx` (`created_by`),
  KEY `fk_user_like_users2_idx` (`parent_id`),
  CONSTRAINT `fk_user_like_users12` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  CONSTRAINT `fk_user_like_users22` FOREIGN KEY (`parent_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=armscii8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users_vote`
--

LOCK TABLES `users_vote` WRITE;
/*!40000 ALTER TABLE `users_vote` DISABLE KEYS */;
/*!40000 ALTER TABLE `users_vote` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
SET FOREIGN_KEY_CHECKS = 1;

-- Dump completed on 2024-04-17  9:53:35
