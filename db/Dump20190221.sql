-- MySQL dump 10.13  Distrib 5.7.25, for Linux (x86_64)
--
-- Host: 13.127.82.199    Database: adh_prod_130219
-- ------------------------------------------------------
-- Server version	5.6.16-1~exp1

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
-- Table structure for table `assessor_key_notes`
--

DROP TABLE IF EXISTS `assessor_key_notes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `assessor_key_notes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `text_data` text,
  `kpa_instance_id` int(11) DEFAULT NULL,
  `assessment_id` int(11) NOT NULL,
  `type` varchar(45) DEFAULT NULL,
  `key_question_instance_id` int(11) DEFAULT NULL,
  `core_question_instance_id` int(11) DEFAULT NULL,
  `judgement_statement_instance_id` int(11) DEFAULT NULL,
  `recommendation_id` int(11) DEFAULT NULL,
  `rec_type` int(11) NOT NULL DEFAULT '0',
  `assessor_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_assessor_key_notes_h_kpa_diagnostic1_idx` (`kpa_instance_id`),
  KEY `fk_assessor_key_notes_d_assessment1_idx` (`assessment_id`),
  KEY `fk_assessor_key_notes_h_kpa_kq_idx` (`key_question_instance_id`),
  KEY `fk_assessor_key_notes_h_kq_cq_idx` (`core_question_instance_id`),
  KEY `fk_assessor_key_notes_h_cq_js_instance_idx` (`judgement_statement_instance_id`),
  CONSTRAINT `fk_assessor_key_notes_d_assessment1` FOREIGN KEY (`assessment_id`) REFERENCES `d_assessment` (`assessment_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_assessor_key_notes_h_cq_js_instance` FOREIGN KEY (`judgement_statement_instance_id`) REFERENCES `h_cq_js_instance` (`judgement_statement_instance_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_assessor_key_notes_h_kpa_diagnostic1` FOREIGN KEY (`kpa_instance_id`) REFERENCES `h_kpa_diagnostic` (`kpa_instance_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_assessor_key_notes_h_kpa_kq` FOREIGN KEY (`key_question_instance_id`) REFERENCES `h_kpa_kq` (`key_question_instance_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_assessor_key_notes_h_kq_cq` FOREIGN KEY (`core_question_instance_id`) REFERENCES `h_kq_cq` (`core_question_instance_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `assessor_key_notes`
--

LOCK TABLES `assessor_key_notes` WRITE;
/*!40000 ALTER TABLE `assessor_key_notes` DISABLE KEYS */;
/*!40000 ALTER TABLE `assessor_key_notes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `d_AQS_data`
--

DROP TABLE IF EXISTS `d_AQS_data`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `d_AQS_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `school_name` varchar(255) DEFAULT NULL,
  `principal_name` varchar(255) DEFAULT NULL,
  `principal_phone_no` varchar(45) DEFAULT NULL,
  `principal_email` varchar(45) DEFAULT NULL,
  `coordinator_name` varchar(255) DEFAULT NULL,
  `coordinator_email` varchar(45) DEFAULT NULL,
  `coordinator_phone_number` varchar(45) DEFAULT NULL,
  `accountant_name` varchar(45) DEFAULT NULL,
  `accountant_phone_no` varchar(20) DEFAULT NULL,
  `accountant_email` varchar(45) DEFAULT NULL,
  `school_address` varchar(255) DEFAULT NULL,
  `school_website` varchar(255) DEFAULT NULL,
  `school_email` varchar(100) DEFAULT NULL,
  `billing_name` varchar(255) DEFAULT NULL,
  `billing_address` varchar(255) DEFAULT NULL,
  `gst_num` varchar(100) NOT NULL,
  `school_pancard_num` varchar(100) NOT NULL,
  `board_id` int(11) DEFAULT NULL,
  `school_type_id` int(11) DEFAULT NULL,
  `no_of_gates` int(11) DEFAULT NULL,
  `no_of_buildings` int(11) DEFAULT NULL,
  `distance_main_building` varchar(10) DEFAULT NULL,
  `no_of_students` varchar(12) DEFAULT NULL,
  `num_class_rooms` int(11) NOT NULL,
  `classes_to` int(10) DEFAULT NULL,
  `classes_from` int(10) DEFAULT NULL,
  `student_type_id` int(11) DEFAULT NULL,
  `annual_fee` varchar(45) DEFAULT NULL,
  `contract_file_name` varchar(100) DEFAULT NULL,
  `travel_arrangement_for_adhyayan` int(11) DEFAULT NULL,
  `accomodation_arrangement_for_adhyayan` int(11) DEFAULT NULL,
  `airport_name` varchar(45) DEFAULT NULL,
  `rail_station_name` varchar(45) DEFAULT NULL,
  `airport_distance` double NOT NULL,
  `rail_station_distance` double NOT NULL,
  `hotel_name` varchar(45) DEFAULT NULL,
  `hotel_school_distance` varchar(45) DEFAULT NULL,
  `hotel_station_distance` varchar(45) DEFAULT NULL,
  `hotel_airport_distance` varchar(45) DEFAULT NULL,
  `school_aqs_pref_start_date` varchar(45) DEFAULT NULL,
  `school_aqs_pref_end_date` varchar(45) DEFAULT NULL,
  `time_table_filename` varchar(100) DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL,
  `referrer_id` int(11) DEFAULT NULL,
  `referrer_text` varchar(250) DEFAULT NULL,
  `RTECompliance` tinyint(1) DEFAULT NULL,
  `h_AQS_workshop_id` int(11) DEFAULT NULL,
  `percComplete` decimal(5,2) DEFAULT NULL,
  `terms_agree` tinyint(1) DEFAULT NULL,
  `school_region_id` int(11) DEFAULT NULL,
  `aqs_school_recognised` int(11) NOT NULL,
  `medium_instruction` int(11) DEFAULT NULL,
  `is_uploaded` int(11) NOT NULL DEFAULT '0',
  `aqs_school_minority` int(11) NOT NULL,
  `aqs_school_registration_num` varchar(255) NOT NULL,
  `aqs_school_gst` int(11) NOT NULL,
  `aqs_school_gst_num` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_d_AQS_data_d_board1_idx` (`board_id`),
  KEY `fk_d_AQS_data_d_school_type1_idx` (`school_type_id`),
  KEY `fk_d_AQS_data_d_referrer1_idx` (`referrer_id`),
  KEY `fk_d_AQS_data_h_AQS_workshop1_idx` (`h_AQS_workshop_id`),
  KEY `student_type_id` (`student_type_id`),
  KEY `classes_from` (`classes_from`),
  KEY `classes_to` (`classes_to`),
  KEY `fk_d_AQS_data_ibfk_1` (`school_region_id`),
  KEY `fk_d_language_idx` (`medium_instruction`),
  CONSTRAINT `fkkx_d_language` FOREIGN KEY (`medium_instruction`) REFERENCES `d_language` (`language_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_aqsClassfrom_class1` FOREIGN KEY (`classes_from`) REFERENCES `d_school_class` (`class_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_aqsClassto_class1` FOREIGN KEY (`classes_to`) REFERENCES `d_school_class` (`class_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_aqs_studenttype1` FOREIGN KEY (`student_type_id`) REFERENCES `del_d_student_type` (`student_type_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_d_AQS_data_d_board1` FOREIGN KEY (`board_id`) REFERENCES `del_d_board` (`board_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_d_AQS_data_d_referrer1` FOREIGN KEY (`referrer_id`) REFERENCES `del_d_referrer` (`referrer_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_d_AQS_data_d_school_type1` FOREIGN KEY (`school_type_id`) REFERENCES `del_d_school_type` (`school_type_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_d_AQS_data_h_AQS_workshop1` FOREIGN KEY (`h_AQS_workshop_id`) REFERENCES `del_h_AQS_workshop` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_d_AQS_data_ibfk_1` FOREIGN KEY (`school_region_id`) REFERENCES `d_school_region` (`region_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `d_AQS_data`
--

LOCK TABLES `d_AQS_data` WRITE;
/*!40000 ALTER TABLE `d_AQS_data` DISABLE KEYS */;
INSERT INTO `d_AQS_data` VALUES (1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','',NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL,'21-02-2019','21-02-2019',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,0,0,'',0,'');
/*!40000 ALTER TABLE `d_AQS_data` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `d_AQS_team`
--

DROP TABLE IF EXISTS `d_AQS_team`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `d_AQS_team` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `AQS_data_id` int(11) NOT NULL,
  `designation` varchar(255) DEFAULT NULL,
  `designation_id` int(11) NOT NULL,
  `language` varchar(255) DEFAULT NULL,
  `lang_id` int(11) NOT NULL DEFAULT '0',
  `email` varchar(255) DEFAULT NULL,
  `mobile` varchar(45) DEFAULT NULL,
  `isInternal` tinyint(1) DEFAULT NULL,
  `user_added_flag` int(1) NOT NULL COMMENT '(0=not added,1=added) into d_user table',
  PRIMARY KEY (`id`),
  KEY `fk_d_AQS_team_d_AQS_data1_idx` (`AQS_data_id`),
  KEY `lang_id` (`lang_id`),
  CONSTRAINT `fk_aqsteam_preferedlan_1` FOREIGN KEY (`lang_id`) REFERENCES `d_language` (`language_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_d_AQS_team_d_AQS_data1` FOREIGN KEY (`AQS_data_id`) REFERENCES `d_AQS_data` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `d_AQS_team`
--

LOCK TABLES `d_AQS_team` WRITE;
/*!40000 ALTER TABLE `d_AQS_team` DISABLE KEYS */;
/*!40000 ALTER TABLE `d_AQS_team` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `d_action_activity_status`
--

DROP TABLE IF EXISTS `d_action_activity_status`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `d_action_activity_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `activity_status_name` varchar(255) NOT NULL,
  `activity_status` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `activity_id` (`activity_status`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `d_action_activity_status`
--

LOCK TABLES `d_action_activity_status` WRITE;
/*!40000 ALTER TABLE `d_action_activity_status` DISABLE KEYS */;
INSERT INTO `d_action_activity_status` VALUES (1,'Not Started',0),(2,'Started',1),(3,'Completed',2),(4,'Postponed',3);
/*!40000 ALTER TABLE `d_action_activity_status` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `d_activity`
--

DROP TABLE IF EXISTS `d_activity`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `d_activity` (
  `activity_id` int(11) NOT NULL AUTO_INCREMENT,
  `activity` varchar(255) NOT NULL,
  `active` int(11) NOT NULL DEFAULT '1',
  `symbol` varchar(200) NOT NULL,
  PRIMARY KEY (`activity_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `d_activity`
--

LOCK TABLES `d_activity` WRITE;
/*!40000 ALTER TABLE `d_activity` DISABLE KEYS */;
INSERT INTO `d_activity` VALUES (1,'Doing a course',1,'triangle'),(2,'Planning',1,'square'),(3,'Visiting school',1,'diamond'),(4,'Calling a meeting',1,'circle'),(5,'Discussion',1,'triangle-down'),(6,'Taking Action',1,'plus'),(7,'Assessing students',1,'triangle'),(8,'Documentation',1,'square'),(9,'Learning',1,'diamond'),(10,'Reviewing impact',1,'circle');
/*!40000 ALTER TABLE `d_activity` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `d_alerts`
--

DROP TABLE IF EXISTS `d_alerts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `d_alerts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `table_name` varchar(100) CHARACTER SET latin1 NOT NULL,
  `content_id` int(11) NOT NULL,
  `content_title` varchar(255) CHARACTER SET latin1 NOT NULL,
  `content_description` text CHARACTER SET latin1 NOT NULL,
  `type` varchar(100) CHARACTER SET latin1 NOT NULL COMMENT 'Create External Assessor, Create Review',
  `status` int(1) NOT NULL COMMENT '0=unread,1=read',
  `ref_key` varchar(500) NOT NULL,
  `creation_date` timestamp NULL DEFAULT NULL,
  `modification_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1455 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `d_alerts`
--

LOCK TABLES `d_alerts` WRITE;
/*!40000 ALTER TABLE `d_alerts` DISABLE KEYS */;
INSERT INTO `d_alerts` VALUES (1,'d_user',10,'deepak','deepak@tatras.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-06-26 17:42:43','2019-01-30 09:23:15'),(2,'d_user',11,'ashutosh','ashutosh@tatrasdata.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-06-26 17:43:20','2019-01-30 09:23:15'),(3,'d_assessment',1,'7','1','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-06-27 12:04:44','2019-02-06 08:37:22'),(4,'d_assessment',2,'7','2','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-06-27 19:49:04','2019-02-06 08:37:22'),(5,'d_assessment',3,'7','3','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-06-27 21:23:14','2019-02-06 08:37:22'),(6,'d_assessment',4,'1','4','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-06-28 12:32:25','2019-02-06 08:37:22'),(7,'d_assessment',5,'7','5','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-06-28 12:44:44','2019-02-06 08:37:22'),(8,'d_assessment',6,'7','6','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-06-29 11:13:16','2019-02-06 08:37:22'),(9,'d_assessment',7,'1','7','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-06-29 12:26:31','2019-02-06 08:37:22'),(10,'d_user',16,'Amit','amit@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-06-30 10:54:07','2019-01-30 09:23:15'),(11,'d_user',17,'Ankit','ankit@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-06-30 10:54:46','2019-01-30 09:23:15'),(12,'d_user',18,'Arpit','arpit@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-06-30 10:55:30','2019-01-30 09:23:15'),(13,'d_user',19,'Pawan','pawan@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-06-30 10:55:58','2019-01-30 09:23:15'),(14,'d_user',20,'Manu','manu@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-06-30 10:57:22','2019-01-30 09:23:15'),(15,'d_user',21,'Shivam','shivam@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-06-30 10:57:55','2019-01-30 09:23:15'),(16,'d_assessment',8,'8','8','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-06-30 11:27:43','2019-02-06 08:37:22'),(17,'d_user',23,'Pooja','pooja@tatrasdata.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-06-30 15:22:57','2019-01-30 09:23:15'),(18,'d_assessment',9,'1','9','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-06-30 15:26:07','2019-02-06 08:37:22'),(19,'d_assessment',10,'10','10','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-01 16:36:37','2019-02-06 08:37:22'),(20,'d_assessment',11,'9','11','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-01 16:44:47','2019-02-06 08:37:22'),(21,'d_assessment',12,'10','12','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-01 16:44:47','2019-02-06 08:37:22'),(22,'d_assessment',13,'1','13','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-01 16:50:16','2019-02-06 08:37:22'),(23,'d_user',30,'External-Tet1','externaltest1@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-01 17:46:46','2019-01-30 09:23:15'),(24,'d_user',31,'External-Tet2','externaltest2@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-01 17:47:27','2019-01-30 09:23:15'),(25,'d_user',32,'External-Tet3','externaltest3@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-01 17:47:59','2019-01-30 09:23:15'),(26,'d_assessment',14,'12','14','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-01 17:51:42','2019-02-06 08:37:22'),(27,'d_assessment',15,'13','15','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-01 17:52:44','2019-02-06 08:37:22'),(28,'d_assessment',16,'14','16','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-01 17:53:38','2019-02-06 08:37:22'),(29,'d_assessment',17,'9','17','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-01 19:27:52','2019-02-06 08:37:22'),(30,'d_assessment',18,'1','18','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-02 17:51:55','2019-02-06 08:37:22'),(31,'d_assessment',19,'8','19','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-02 19:30:04','2019-02-06 08:37:22'),(32,'d_user',41,'Kavita Anand','kavita.anand@adhyayan.asia','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-04 20:36:30','2019-01-30 09:23:15'),(33,'d_user',42,'Anushri Alva','anushri.alva@adhyayan.asia','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-04 20:36:58','2019-01-30 09:23:15'),(34,'d_user',43,'Kalpesh Dalvi','kalpesh.dalvi@adhyayan.asia','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-04 20:37:28','2019-01-30 09:23:15'),(35,'d_assessment',7,'35','7','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-04 20:51:14','2019-02-06 08:37:22'),(36,'d_assessment',8,'34','8','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-04 20:52:39','2019-02-06 08:37:22'),(37,'d_assessment',9,'36','9','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-04 20:54:56','2019-02-06 08:37:22'),(38,'d_assessment',10,'37','10','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-04 20:55:34','2019-02-06 08:37:22'),(39,'d_assessment',11,'12','11','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-04 20:56:08','2019-02-06 08:37:22'),(40,'d_assessment',12,'1','12','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-04 20:56:41','2019-02-06 08:37:22'),(41,'d_assessment',13,'38','13','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-04 20:57:11','2019-02-06 08:37:22'),(42,'d_assessment',14,'39','14','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-04 20:57:43','2019-02-06 08:37:22'),(43,'d_assessment',15,'26','15','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-04 20:58:17','2019-02-06 08:37:22'),(44,'d_user',45,'Supriya Sawant','supriyasawant@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-04 21:53:39','2019-01-30 09:23:15'),(45,'d_user',46,'Swarupa Kalangutkar','swarupak@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-04 21:54:35','2019-01-30 09:23:15'),(46,'d_user',47,'Sangeeta Joshi','sangeeta-brp@mail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-04 22:36:26','2019-01-30 09:23:15'),(47,'d_user',48,'Satyeja Kudaskar','satyeja-crp@mail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-04 22:37:26','2019-01-30 09:23:15'),(48,'d_user',49,'Aashika Gaonkar','aashika-brp@mail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-04 22:38:28','2019-01-30 09:23:15'),(49,'d_user',50,'Balkrishna gawas','balkrishna-brp@mail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-04 22:45:29','2019-01-30 09:23:15'),(50,'d_user',51,'Pramila Gawas','pramila-crp@mail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-04 22:46:15','2019-01-30 09:23:15'),(51,'d_user',52,'Falguni Sheth','falguni-brp@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-04 23:10:56','2019-01-30 09:23:15'),(52,'d_user',53,'Mubina Shaihk','mubina-crp@mail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-04 23:11:45','2019-01-30 09:23:15'),(53,'d_user',55,'Sneha Phalle','sneha.phalle@adhyayan.asia','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-05 09:08:21','2019-01-30 09:23:15'),(54,'d_user',56,'external','ext@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-05 09:49:00','2019-01-30 09:23:15'),(55,'d_assessment',16,'40','16','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-05 09:49:40','2019-02-06 08:37:22'),(56,'d_assessment',17,'44','17','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-18 09:31:25','2019-02-06 08:37:22'),(57,'d_assessment',18,'45','18','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-18 09:36:19','2019-02-06 08:37:22'),(58,'d_assessment',19,'46','19','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-18 09:37:29','2019-02-06 08:37:22'),(59,'d_user',310,'Mentor GHS Amona','mentor.ghsamona@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-25 14:29:28','2019-01-30 09:23:15'),(60,'d_user',312,'Mentor GHS Surla','mentor.ghssurla@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-25 14:30:43','2019-01-30 09:23:15'),(61,'d_user',315,'Mentor GPA Amona','mentor.gpsamona@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-25 14:35:26','2019-01-30 09:23:15'),(62,'d_assessment',20,'47','20','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-25 14:36:18','2019-02-06 08:37:22'),(63,'d_assessment',21,'48','21','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-25 14:37:44','2019-02-06 08:37:22'),(64,'d_assessment',22,'49','22','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-25 14:57:06','2019-02-06 08:37:22'),(65,'d_user',323,'GPS BHANDARWADA AMONA','mentor.gpsbhandarwada@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-25 14:58:42','2019-01-30 09:23:15'),(66,'d_user',325,'GPS GHADIWADA SURLA','mentor.gpsghadiwada@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-25 15:00:32','2019-01-30 09:23:15'),(67,'d_user',326,'GPS KADCHAL SURLA','mentor.gpskadchal@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-25 15:02:15','2019-01-30 09:23:15'),(68,'d_assessment',23,'50','23','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-25 15:02:30','2019-02-06 08:37:22'),(69,'d_user',327,'GPS KHARWADA AMONA','mentor.gpskharwada@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-25 15:03:31','2019-01-30 09:23:15'),(70,'d_user',328,'GPS KHODGINI SURLA','mentor.gpskhodgini@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-25 15:04:59','2019-01-30 09:23:15'),(71,'d_user',329,'GPS TARWADA SURLA','mentor.gpstarwada@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-25 15:06:15','2019-01-30 09:23:15'),(72,'d_user',330,'GHS SHIRODWADI MULGAO','mentor.ghsshirodwadi@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-25 15:07:25','2019-01-30 09:23:15'),(73,'d_assessment',24,'51','24','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-25 15:07:42','2019-02-06 08:37:22'),(74,'d_user',332,'GPS BICHOLIM','mentor.gpsbicholim@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-25 15:09:16','2019-01-30 09:23:15'),(75,'d_assessment',25,'52','25','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-25 15:09:24','2019-02-06 08:37:22'),(76,'d_user',333,'GPS BORDEM','mentor.gpsbordem@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-25 15:10:35','2019-01-30 09:23:15'),(77,'d_assessment',26,'53','26','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-25 15:11:05','2019-02-06 08:37:22'),(78,'d_user',334,'GPS GAONKARWADA MULGAO','mentor.gpsgaonkarwada@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-25 15:11:59','2019-01-30 09:23:15'),(79,'d_user',335,'GPS LAMGAO BICHOLIM WARD 12','mentor.gpslamgao@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-25 15:14:03','2019-01-30 09:23:15'),(80,'d_assessment',27,'54','27','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-25 15:14:45','2019-02-06 08:37:22'),(81,'d_user',336,'GPS MAULINGUEM','mentor.gpsmaulinguem@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-25 15:16:05','2019-01-30 09:23:15'),(82,'d_user',337,'GPS SHIRODWADI MULGAO','mentor.gpsmulgao@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-25 15:17:32','2019-01-30 09:23:15'),(83,'d_user',338,'GPS VAN  MAULINGUEM','mentor.gpsvanmaulinguem@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-25 15:19:14','2019-01-30 09:23:15'),(84,'d_assessment',28,'55','28','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-25 15:19:30','2019-02-06 08:37:22'),(85,'d_user',339,'GPS VATHADEO SARVAN','mentor.gpsvathadeo@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-25 15:20:38','2019-01-30 09:23:15'),(86,'d_assessment',29,'56','29','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-25 15:21:37','2019-02-06 08:37:22'),(87,'d_user',340,'GPS VHALSHI','mentor.gpsvhalshi@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-25 15:39:56','2019-01-30 09:23:15'),(88,'d_user',341,'GPS BHOLWADA KARAPUR','mentor.gpsbholwada@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-25 15:41:10','2019-01-30 09:23:15'),(89,'d_assessment',30,'57','30','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-25 15:42:19','2019-02-06 08:37:22'),(90,'d_user',342,'GPS DEULWADA KUDCHIRE','mentor.gpsdeulwada@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-25 15:42:25','2019-01-30 09:23:15'),(91,'d_assessment',31,'58','31','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-25 15:44:24','2019-02-06 08:37:22'),(92,'d_user',343,'GPS HSG BRD VASANT NAGAR HARVALEM','mentor.gpshsgbrd@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-25 15:44:28','2019-01-30 09:23:15'),(93,'d_user',344,'GPS KOTHI KARAPUR','mentor.gpskothi@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-25 15:46:06','2019-01-30 09:23:15'),(94,'d_user',345,'GPS KUDAP KARAPUR','mentor.gpskudap@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-25 15:47:26','2019-01-30 09:23:15'),(95,'d_assessment',32,'59','32','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-25 15:47:46','2019-02-06 08:37:22'),(96,'d_user',346,'GPS LOWER HARAVALE SANQUELIM','mentor.gpslower@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-25 15:48:56','2019-01-30 09:23:15'),(97,'d_user',347,'GPS NAIGINI','mentor.gpsnaigini@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-25 15:50:13','2019-01-30 09:23:15'),(98,'d_user',348,'GPS PALTADWADA KUDCHIRE','mentor.gpspaltadwada@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-25 15:52:14','2019-01-30 09:23:15'),(99,'d_assessment',33,'60','33','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-25 15:58:42','2019-02-06 08:37:22'),(100,'d_assessment',34,'61','34','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-25 16:14:08','2019-02-06 08:37:22'),(101,'d_user',349,'GPS PRATAPNAGAR HARAVLEM SANQU','mentor.gpspratapnagar@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-25 16:18:41','2019-01-30 09:23:15'),(102,'d_user',350,'GPS UPPER HARWALE','mentor.gpsupper@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-25 16:20:20','2019-01-30 09:23:15'),(103,'d_assessment',35,'62','35','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-25 16:20:57','2019-02-06 08:37:22'),(104,'d_user',351,'GPS VITHALAPUR KARAPUR','memtor.gpavithalapur@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-25 16:21:56','2019-01-30 09:23:15'),(105,'d_assessment',36,'63','36','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-25 16:22:41','2019-02-06 08:37:22'),(106,'d_user',352,'GPS BHAVKAI MAYEM','mentor.gpsbhavkai@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-25 16:23:43','2019-01-30 09:23:15'),(107,'d_user',353,'GPS DEUSBHATWADI MAYEM','mentor.gpsmayem@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-25 16:25:09','2019-01-30 09:23:15'),(108,'d_user',354,'GPS GAONKARWADA MAYEM','mentor.gaonkarwada@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-25 16:26:34','2019-01-30 09:23:15'),(109,'d_user',355,'GPS HALDANWADI MAYEM','mentor.haldanwadi@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-25 16:28:15','2019-01-30 09:23:15'),(110,'d_user',356,'GPS JAMBHULBHAT MAYEM','mentor.jambhulbhat@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-25 16:29:39','2019-01-30 09:23:15'),(111,'d_assessment',37,'64','37','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-25 16:30:05','2019-02-06 08:37:22'),(112,'d_user',357,'GPS KELBAIWADA MAYEM','mentor.gpskelbaiwada@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-25 16:31:09','2019-01-30 09:23:15'),(113,'d_assessment',38,'65','38','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-25 16:34:05','2019-02-06 08:37:22'),(114,'d_assessment',39,'66','39','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-25 16:39:46','2019-02-06 08:37:22'),(115,'d_assessment',40,'67','40','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-25 16:46:57','2019-02-06 08:37:22'),(116,'d_user',358,'GPS POIRA MAYEM','mentor.gpspoira@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-25 17:31:42','2019-01-30 09:23:15'),(117,'d_user',359,'GPS SIKERI MAYEM','mentor.gpssikeri@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-25 17:32:56','2019-01-30 09:23:15'),(118,'d_user',360,'GHS KASARPAL','mentor.ghskasarpal@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-25 17:34:05','2019-01-30 09:23:15'),(119,'d_user',361,'GHS MULGAO','mentor.ghsmulgao@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-25 17:35:17','2019-01-30 09:23:15'),(120,'d_user',362,'GHS SHIRGAO','mentor.ghsshirgao@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-25 17:36:35','2019-01-30 09:23:15'),(121,'d_user',363,'GPMS ADVALPAL','mentor.gpmsadvalpal@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-25 17:37:47','2019-01-30 09:23:15'),(122,'d_assessment',41,'68','41','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-25 17:48:50','2019-02-06 08:37:22'),(123,'d_assessment',42,'69','42','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-25 17:50:42','2019-02-06 08:37:22'),(124,'d_assessment',43,'70','43','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-25 17:53:01','2019-02-06 08:37:22'),(125,'d_assessment',44,'71','44','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-25 17:54:01','2019-02-06 08:37:22'),(126,'d_assessment',45,'72','45','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-25 17:55:03','2019-02-06 08:37:22'),(127,'d_assessment',46,'73','46','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-25 17:59:06','2019-02-06 08:37:22'),(128,'d_assessment',47,'74','47','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-25 18:01:51','2019-02-06 08:37:22'),(129,'d_assessment',48,'75','48','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-25 18:03:01','2019-02-06 08:37:22'),(130,'d_assessment',49,'76','49','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-25 18:04:16','2019-02-06 08:37:22'),(131,'d_assessment',50,'77','50','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-25 18:06:06','2019-02-06 08:37:22'),(132,'d_assessment',51,'78','51','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-25 18:07:32','2019-02-06 08:37:22'),(133,'d_assessment',52,'79','52','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-25 18:08:57','2019-02-06 08:37:22'),(134,'d_assessment',53,'80','53','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-25 18:10:01','2019-02-06 08:37:22'),(135,'d_assessment',54,'81','54','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-25 18:11:15','2019-02-06 08:37:22'),(136,'d_assessment',55,'82','55','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-25 18:12:27','2019-02-06 08:37:22'),(137,'d_assessment',56,'83','56','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-25 18:13:23','2019-02-06 08:37:22'),(138,'d_assessment',57,'84','57','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-25 18:14:37','2019-02-06 08:37:22'),(139,'d_assessment',58,'85','58','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-25 18:17:26','2019-02-06 08:37:22'),(140,'d_user',365,'Mentor GPS Malpe','mentor.gpsmalpe@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-25 19:03:39','2019-01-30 09:23:15'),(141,'d_assessment',59,'204','59','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-25 19:05:14','2019-02-06 08:37:22'),(142,'d_user',366,'Mentor SHEMECHE ADVAN','mentor.gpsshemeche@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-25 19:21:52','2019-01-30 09:23:15'),(143,'d_user',367,'Mentor G.P.S. MARDIWADA','mentor.gpsmardiwada@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-25 19:24:23','2019-01-30 09:23:15'),(144,'d_user',368,'Mentor GPS SAWANTWADA MANDREM','mentor.gpsmandrem@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-25 19:26:25','2019-01-30 09:23:15'),(145,'d_user',369,'Mentor GPS OZARI','mentor.gpsozari@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-25 19:27:16','2019-01-30 09:23:15'),(146,'d_assessment',60,'154','60','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-25 19:28:04','2019-02-06 08:37:22'),(147,'d_assessment',61,'180','61','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-25 19:48:43','2019-02-06 08:37:22'),(148,'d_assessment',62,'176','62','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-25 19:51:28','2019-02-06 08:37:22'),(149,'d_assessment',63,'161','63','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-25 19:57:14','2019-02-06 08:37:22'),(150,'d_user',370,'Mentor GHS Chandel','mentor.ghschandel@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 10:11:51','2019-01-30 09:23:15'),(151,'d_assessment',64,'136','64','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-26 10:12:59','2019-02-06 08:37:22'),(152,'d_user',371,'MENTOR.G.H.S. HANKHANE','mentor.ghshankhane@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 10:17:27','2019-01-30 09:23:15'),(153,'d_user',372,'Mentor.G.P.M.S. HASAPUR','mentor.gpmshasapur@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 10:20:43','2019-01-30 09:23:15'),(154,'d_user',373,'Mentor.G.P.S. CHANDEL','mentor.gpschandel@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 10:22:06','2019-01-30 09:23:15'),(155,'d_user',374,'Mentor.G.P.S. HANKHANE','mentor.gpshankhane@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 10:23:56','2019-01-30 09:23:15'),(156,'d_user',375,'Mentor.G.P.S. HEDUS IBRAMPUR','mentor.gpshedus@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 10:25:09','2019-01-30 09:23:15'),(157,'d_user',376,'Mentor.G.P.S. IBRAMPUR','mentor.gpsibrampur@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 10:26:37','2019-01-30 09:23:15'),(158,'d_user',377,'Mentor.G.H.S. DADACHIWADI','mentor.ghsdadachiwadi@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 10:28:03','2019-01-30 09:23:15'),(159,'d_user',378,'Mentor.G.P.S DHARESHWAR','mentor.gpsdhareshwar@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 10:30:17','2019-01-30 09:23:15'),(160,'d_user',379,'Mentor.G.P.S. AROBA','mentor.gpsaroba@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 10:31:38','2019-01-30 09:23:15'),(161,'d_assessment',65,'133','65','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-26 10:32:09','2019-02-06 08:37:22'),(162,'d_assessment',66,'134','66','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-26 10:34:15','2019-02-06 08:37:22'),(163,'d_user',380,'Mentor.GPMS LADFE','mentor.gpmsladfe@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 10:36:42','2019-01-30 09:23:15'),(164,'d_user',381,'Mentor.GPMS NANODA','mentor.nanoda@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 10:39:19','2019-01-30 09:23:15'),(165,'d_assessment',67,'138','67','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-26 10:43:51','2019-02-06 08:37:22'),(166,'d_user',382,'Mentor.G.P.S. CHICHOLA, DHARGAL','mentor.gpschichola@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 10:46:47','2019-01-30 09:23:15'),(167,'d_assessment',68,'139','68','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-26 10:48:28','2019-02-06 08:37:22'),(168,'d_assessment',69,'140','69','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-26 10:50:09','2019-02-06 08:37:22'),(169,'d_assessment',70,'142','70','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-26 10:51:15','2019-02-06 08:37:22'),(170,'d_assessment',71,'143','71','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-26 10:53:48','2019-02-06 08:37:22'),(171,'d_assessment',72,'144','72','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-26 10:55:04','2019-02-06 08:37:22'),(172,'d_assessment',73,'146','73','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-26 10:56:19','2019-02-06 08:37:22'),(173,'d_user',383,'Mentor.GPS DHANGARWADA  VADAVAL','mentor.dhangarwada@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 11:02:11','2019-01-30 09:23:15'),(174,'d_user',384,'Mentor.G.P.S. DADACHIWADI, DHARGAL','mentor.gpsdadachiwadi@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 11:07:57','2019-01-30 09:23:15'),(175,'d_user',385,'mentor.GPS KASARPAL','mentor.gpskasarpal@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 11:09:06','2019-01-30 09:23:15'),(176,'d_user',386,'Mentor.G.P.S. DEULWADA, DHARGAL','mentor.gpsdeulwadadh@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 11:12:27','2019-01-30 09:23:15'),(177,'d_user',387,'Mentor.G.P.S. OSHALBAG','mentor.gpsoshalbag@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 11:13:57','2019-01-30 09:23:15'),(178,'d_assessment',74,'147','74','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-26 11:16:06','2019-02-06 08:37:22'),(179,'d_user',388,'mentor.GPS MANASBAG MULGAO','mentor.gpsmanasbag@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 11:16:21','2019-01-30 09:23:15'),(180,'d_assessment',75,'149','75','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-26 11:17:20','2019-02-06 08:37:22'),(181,'d_user',389,'Mentor.G.P.S. TIWADE','mentor.gpstiwade@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 11:17:43','2019-01-30 09:23:15'),(182,'d_assessment',76,'150','76','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-26 11:18:19','2019-02-06 08:37:22'),(183,'d_user',390,'mentor.GPS MULGAO','mentor.gpsmulgao12@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 11:18:22','2019-01-30 09:23:15'),(184,'d_user',391,'Mentor.G.P.S. VIRNODA','mentor.gpsvirnoda@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 11:18:57','2019-01-30 09:23:15'),(185,'d_assessment',77,'152','77','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-26 11:19:15','2019-02-06 08:37:22'),(186,'d_user',392,'Mentor.G.H.S. OZARI, PERNEM','mentor.ghsozariper@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 11:20:12','2019-01-30 09:23:15'),(187,'d_assessment',78,'153','78','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-26 11:20:16','2019-02-06 08:37:22'),(188,'d_user',393,'Mentor.G.P.M.S. KASARWARNE','mentor.gpmskasarwarn@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 11:21:42','2019-01-30 09:23:15'),(189,'d_assessment',79,'156','79','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-26 11:25:26','2019-02-06 08:37:22'),(190,'d_user',394,'Mentor.GPS SHIRGAO','mentor.gpsshirgao@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 11:39:23','2019-01-30 09:23:15'),(191,'d_user',395,'Mentor.G.P.M.S. NAGZAR','mentor.gpmsnagzar@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 11:41:02','2019-01-30 09:23:15'),(192,'d_assessment',80,'157','80','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-26 11:42:24','2019-02-06 08:37:22'),(193,'d_user',396,'Mentor.GPS USSAP','mentor.gpsussap@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 11:42:24','2019-01-30 09:23:15'),(194,'d_user',397,'Mentor.G.P.S. GOTHANWADA, OZARI','mentor.gpsgothanwada@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 11:42:47','2019-01-30 09:23:15'),(195,'d_assessment',81,'159','81','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-26 11:43:29','2019-02-06 08:37:22'),(196,'d_user',398,'Mentor.G.P.S. MADKAI, OZARI','mentor.gpsmadkal@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 11:44:01','2019-01-30 09:23:15'),(197,'d_user',399,'Mentor.GPS AMBEGAL PALE','mentor.gpsambegalpale@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 11:45:01','2019-01-30 09:23:15'),(198,'d_assessment',82,'160','82','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-26 11:45:09','2019-02-06 08:37:22'),(199,'d_user',400,'Mentor.G.P.S. SHEMECHE ADVAN','mentor.gpsshemechead@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 11:45:10','2019-01-30 09:23:15'),(200,'d_user',401,'Mentor.GPS AMBESHI PALE','mentor.gpsambeshipale@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 11:46:11','2019-01-30 09:23:15'),(201,'d_user',403,'Mentor.G.P.S. BHALKHAJAN KORGAO','mentor.gpsbhalkhajan@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 11:47:33','2019-01-30 09:23:15'),(202,'d_user',404,'Mentor.GPS BAYEM SURLA','mentor.gpsbayemsurla@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 11:48:55','2019-01-30 09:23:15'),(203,'d_user',405,'Mentor.GPS BHAMAI PALE','mentor.gpsbhamaipale@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 11:50:44','2019-01-30 09:23:15'),(204,'d_user',406,'Mentor.G.P.S. BHATWADI KORGAO','mantor.gpsbhatwadiko@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 11:51:09','2019-01-30 09:23:15'),(205,'d_user',407,'Mentor.G.P.S. MANSHIWADA KORGAO','mantor.gpsmanshiwada@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 11:52:30','2019-01-30 09:23:15'),(206,'d_user',408,'Mentor.GPS CHINCHWADA PALE','mentor.gpschinchwada@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 11:52:30','2019-01-30 09:23:15'),(207,'d_user',409,'Mentor.G.P.S. PARASTE','mentor.gpsparaste@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 11:53:37','2019-01-30 09:23:15'),(208,'d_user',410,'Mentor.GPS DINGNE SURLA','mentor.gpsdingnesurla@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 11:54:01','2019-01-30 09:23:15'),(209,'d_user',411,'Mentor.G.P.S. DEULWADA, MANDREM','mantor.gpsdeulwada@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 11:54:49','2019-01-30 09:23:15'),(210,'d_user',412,'Mentor.G.P.S. DEULWADA, MORJIM','mentor.gpsdeulwadamor@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 11:56:06','2019-01-30 09:23:15'),(211,'d_user',413,'Mentor.G.P.S. KANNAIKWADA','mentor.gpskannaikwada@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 11:57:19','2019-01-30 09:23:15'),(212,'d_assessment',83,'164','83','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-26 11:57:58','2019-02-06 08:37:22'),(213,'d_user',414,'Mentor.G.P.S. KATTEWADA','mantor.gpskattewada@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 11:58:26','2019-01-30 09:23:15'),(214,'d_user',415,'Mentor.G.P.S. MARDIWADA','mantor.gpsmardiwada@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 11:59:56','2019-01-30 09:23:15'),(215,'d_user',416,'Mentor.GPS KHAJAN SURLA','mentor.gpskhajansurla@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 12:00:18','2019-01-30 09:23:15'),(216,'d_user',417,'Mentor.G.P.S. NAIKWADA MANDREM','mentor.gpsnaikwadaman@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 12:01:24','2019-01-30 09:23:15'),(217,'d_user',418,'Mentor.GPS NAWARWADA PALE','mentor.gpsnawarwada@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 12:01:46','2019-01-30 09:23:15'),(218,'d_user',419,'Mentor.G.P.S. POKEWADA, MORJI','mantor.gpspokewada@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 12:02:31','2019-01-30 09:23:15'),(219,'d_assessment',84,'166','84','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-26 12:02:54','2019-02-06 08:37:22'),(220,'d_assessment',85,'167','85','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-26 12:04:05','2019-02-06 08:37:22'),(221,'d_user',420,'Mentor.G.P.S. SAWANTWADA MANDREM','mantor.gpssawantwada@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 12:04:38','2019-01-30 09:23:15'),(222,'d_assessment',86,'169','86','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-26 12:05:12','2019-02-06 08:37:22'),(223,'d_user',421,'Mentor.G.P.S. BHANDARWADA PALYE','mantor.gpsbhandarwad@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 12:05:46','2019-01-30 09:23:15'),(224,'d_assessment',87,'170','87','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-26 12:06:16','2019-02-06 08:37:22'),(225,'d_user',422,'Mentor.GPS RUMOD SURLA','mentor.gpsrumodsurla@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 12:06:18','2019-01-30 09:23:15'),(226,'d_assessment',88,'171','88','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-26 12:07:12','2019-02-06 08:37:22'),(227,'d_user',423,'Mentor.G.P.S. DEULWADA HARMAL','mentor.gpsdeulwadaha@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 12:08:11','2019-01-30 09:23:15'),(228,'d_assessment',89,'173','89','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-26 12:08:48','2019-02-06 08:37:22'),(229,'d_user',424,'Mentor.G.P.S. KIRANPANI','mentor.gpskiranpani@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 12:09:27','2019-01-30 09:23:15'),(230,'d_assessment',90,'174','90','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-26 12:09:46','2019-02-06 08:37:22'),(231,'d_user',425,'Mentor.G.P.S. MADHALAWADA KERI','mentor.gpsmadhalawad@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 12:10:39','2019-01-30 09:23:15'),(232,'d_user',426,'Mentor.GPS BAGWADA PILGAO','mentor.bagwadapilgao@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 12:10:55','2019-01-30 09:23:15'),(233,'d_assessment',91,'177','91','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-26 12:11:29','2019-02-06 08:37:22'),(234,'d_user',427,'Mentor.G.P.S. MADHALAWADA PALYE','mentor.gpsmadhalawad1@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 12:12:05','2019-01-30 09:23:15'),(235,'d_assessment',92,'179','92','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-26 12:12:30','2019-02-06 08:37:22'),(236,'d_user',428,'Mentor.G.P.S. NAIKWADA PALYE','mentor.gpsnaikwadapal@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 12:13:12','2019-01-30 09:23:15'),(237,'d_assessment',93,'182','93','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-26 12:15:23','2019-02-06 08:37:22'),(238,'d_user',429,'Mentor.GPS HATURLIM MAYEM','mentor.gpshaturlim@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 12:16:46','2019-01-30 09:23:15'),(239,'d_assessment',94,'184','94','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-26 12:21:31','2019-02-06 08:37:22'),(240,'d_assessment',95,'104','95','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-26 12:25:01','2019-02-06 08:37:22'),(241,'d_user',430,'Mentor.G.P.S. PARSEKARWADA HARMAL','mentor.gpsparsekarwad@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 12:26:33','2019-01-30 09:23:15'),(242,'d_user',431,'Mentor.GPS KATAR DHABDHABA BICHOLIM','mentor.gpskatardhabdha@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 12:27:37','2019-01-30 09:23:15'),(243,'d_user',432,'Mentor.G.P.S. TALWADA KERI','mentor.gpstalwadakeri@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 12:28:15','2019-01-30 09:23:15'),(244,'d_user',433,'Mentor.GPS MATHWADA PILIGAO','mentor.gpsmathwada@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 12:28:58','2019-01-30 09:23:15'),(245,'d_assessment',96,'185','96','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-26 12:29:05','2019-02-06 08:37:22'),(246,'d_user',434,'Mentor.G.P.M.S. GAONKARWADA TUEM','mentor.gpmsgaonkarw@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 12:29:28','2019-01-30 09:23:15'),(247,'d_assessment',97,'186','97','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-26 12:30:05','2019-02-06 08:37:22'),(248,'d_user',435,'Mentor.G.P.S. AGARWADA','mentor.gpsagarwada@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 12:30:30','2019-01-30 09:23:15'),(249,'d_user',436,'Mentor.GPS PILIGAO','mentor.gpspiligao@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 12:30:53','2019-01-30 09:23:15'),(250,'d_assessment',98,'187','98','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-26 12:30:59','2019-02-06 08:37:22'),(251,'d_user',437,'mentor.G.P.S. B. D. L., PARSE','mentor.gpsbdlparse@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 12:31:38','2019-01-30 09:23:15'),(252,'d_assessment',99,'188','99','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-26 12:31:45','2019-02-06 08:37:22'),(253,'d_user',438,'Mentor.GPS SARVAN','mentor.gpssarvan@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 12:32:05','2019-01-30 09:23:15'),(254,'d_assessment',100,'88','100','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-26 12:32:11','2019-02-06 08:37:22'),(255,'d_assessment',101,'190','101','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-26 12:32:39','2019-02-06 08:37:22'),(256,'d_user',439,'Mentor.G.P.S. CHOPDE','mentor.gpschopde@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 12:33:13','2019-01-30 09:23:15'),(257,'d_assessment',102,'89','102','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-26 12:33:37','2019-02-06 08:37:22'),(258,'d_assessment',103,'191','103','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-26 12:33:52','2019-02-06 08:37:22'),(259,'d_user',440,'Mentor.G.P.S. HARMALKARWADA','mentor.gpsharmalkar@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 12:34:23','2019-01-30 09:23:15'),(260,'d_assessment',104,'192','104','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-26 12:34:48','2019-02-06 08:37:22'),(261,'d_user',441,'Mentor.G.P.S. MURMUSE TUEM','mentor.gpsmurmuse@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 12:35:37','2019-01-30 09:23:15'),(262,'d_assessment',105,'193','105','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-26 12:35:50','2019-02-06 08:37:22'),(263,'d_assessment',106,'194','106','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-26 12:36:49','2019-02-06 08:37:22'),(264,'d_user',442,'Mentor.G.P.S. VAIDONGOR PARSE','mentor.gpsvaidongor@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 12:36:53','2019-01-30 09:23:15'),(265,'d_user',443,'Mentor.G.P.S. BHIRONE','mentor.gpsbhirone@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 12:38:36','2019-01-30 09:23:15'),(266,'d_user',444,'Mentor.G.P.S. BHUTWADI','mentorgpsbhutwadi@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 12:39:48','2019-01-30 09:23:15'),(267,'d_user',445,'Mentor.GPS VAINGINI MAYEM','mentor.gpsvaingaini@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 12:40:25','2019-01-30 09:23:15'),(268,'d_user',446,'Mentor.G.P.S. KHAJANE','mentor.gpskhajane@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 12:40:52','2019-01-30 09:23:15'),(269,'d_assessment',107,'195','107','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-26 12:41:32','2019-02-06 08:37:22'),(270,'d_user',447,'Mentor.GHS MENKUREM','mentor.ghsmenkurem@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 12:41:40','2019-01-30 09:23:15'),(271,'d_user',448,'Mentor.G.P.S. MALPE','mentor.gpsmalpe1@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 12:42:12','2019-01-30 09:23:15'),(272,'d_assessment',108,'196','108','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-26 12:42:25','2019-02-06 08:37:22'),(273,'d_user',449,'Mentor.GHS SAL','mentor.ghssal@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 12:43:07','2019-01-30 09:23:15'),(274,'d_assessment',109,'90','109','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-26 12:43:08','2019-02-06 08:37:22'),(275,'d_user',450,'Mentor.G.P.S. VITHALADEVI','mentor.gpsvithaladevi@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 12:43:22','2019-01-30 09:23:15'),(276,'d_assessment',110,'197','110','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-26 12:43:44','2019-02-06 08:37:22'),(277,'d_user',451,'Mentor.GPS AMTHANE','mentor.gpsamthane@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 12:44:15','2019-01-30 09:23:15'),(278,'d_assessment',111,'91','111','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-26 12:44:35','2019-02-06 08:37:22'),(279,'d_user',452,'Mentor.H. M. P. G. P. M. S. PERNEM','mentor.hmpgpmspe@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 12:44:51','2019-01-30 09:23:15'),(280,'d_assessment',112,'92','112','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-26 12:47:04','2019-02-06 08:37:22'),(281,'d_assessment',113,'93','113','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-26 12:48:28','2019-02-06 08:37:22'),(282,'d_user',453,'Mentor GPS DODAMARG','mentor.gpsdodamarg@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 12:49:50','2019-01-30 09:23:15'),(283,'d_assessment',114,'94','114','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-26 12:50:28','2019-02-06 08:37:22'),(284,'d_assessment',115,'198','115','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-26 12:50:32','2019-02-06 08:37:22'),(285,'d_user',454,'Mentor GHS TORSE','mentor.ghstorse@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 12:54:02','2019-01-30 09:23:15'),(286,'d_user',455,'Mentor GPS KHARPAL','mentor.gpskharpal@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 12:54:03','2019-01-30 09:23:15'),(287,'d_user',456,'Mentor GPS KHOLPEWADI SAL','mentor.gpskholpewadi@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 12:55:29','2019-01-30 09:23:15'),(288,'d_assessment',116,'95','116','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-26 12:58:28','2019-02-06 08:37:22'),(289,'d_user',457,'Mentor GPS MENKUREM','mentor.gpsmenkurem@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 12:59:21','2019-01-30 09:23:15'),(290,'d_user',458,'Mentor.GPS PUNARVASAN SAL','mentor.gpspunarvasan@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 13:01:03','2019-01-30 09:23:15'),(291,'d_user',459,'Mentor GPS SAL','mentor.gpssal@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 13:02:12','2019-01-30 09:23:15'),(292,'d_assessment',117,'96','117','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-26 14:16:17','2019-02-06 08:37:22'),(293,'d_user',460,'mentor GPMS MOPA','mentor.gpmsmopa@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 14:16:56','2019-01-30 09:23:15'),(294,'d_assessment',118,'97','118','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-26 14:17:31','2019-02-06 08:37:22'),(295,'d_user',461,'Mentor GPMS PORASKADE','mentor.gpmsporask@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 14:18:34','2019-01-30 09:23:15'),(296,'d_assessment',119,'98','119','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-26 14:19:00','2019-02-06 08:37:22'),(297,'d_assessment',120,'99','120','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-26 14:20:16','2019-02-06 08:37:22'),(298,'d_user',462,'Mentor GPMS UGAVE DEULWADA','mentor.gpmsugave@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 14:20:33','2019-01-30 09:23:15'),(299,'d_user',463,'Mentor GPS FAKIRPATA','mentor.gpsfakirpata@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 14:23:59','2019-01-30 09:23:15'),(300,'d_assessment',121,'100','121','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-26 14:24:44','2019-02-06 08:37:22'),(301,'d_assessment',122,'201','122','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-26 14:25:39','2019-02-06 08:37:22'),(302,'d_assessment',123,'202','123','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-26 14:26:55','2019-02-06 08:37:22'),(303,'d_assessment',124,'101','124','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-26 14:27:21','2019-02-06 08:37:22'),(304,'d_assessment',125,'203','125','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-26 14:27:52','2019-02-06 08:37:22'),(305,'d_user',464,'Mentor GPS KADSHI MOPA','mentor.gpskadshimopa@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 14:29:14','2019-01-30 09:23:15'),(306,'d_user',465,'Mentor GPS VADAVAL DEULWADA','mentor.gpsvadaval@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 14:29:17','2019-01-30 09:23:15'),(307,'d_assessment',126,'102','126','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-26 14:29:56','2019-02-06 08:37:22'),(308,'d_user',466,'Mentor GPS SAKRAL TORSE','mentor.gpssakral@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 14:30:29','2019-01-30 09:23:15'),(309,'d_user',467,'Mentor GHS NAVELIM','mentor.ghsnavelim@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 14:31:25','2019-01-30 09:23:15'),(310,'d_assessment',127,'205','127','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-26 14:31:28','2019-02-06 08:37:22'),(311,'d_assessment',128,'103','128','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-26 14:31:52','2019-02-06 08:37:22'),(312,'d_user',468,'Mentor GPS TAMBOSE, PERNEM','mentor.gpstambose@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 14:32:19','2019-01-30 09:23:15'),(313,'d_user',469,'Mentor GPS TORSE','mentor.gpstorse@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 14:33:29','2019-01-30 09:23:15'),(314,'d_user',470,'Mentor GPS FALWADI KUDNE','mentor.gpsfalwadi@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 14:33:46','2019-01-30 09:23:15'),(315,'d_assessment',129,'207','129','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-26 14:34:38','2019-02-06 08:37:22'),(316,'d_user',471,'Mentor GPS VARKHAND','mentor.gpsvarkhand@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 14:34:50','2019-01-30 09:23:15'),(317,'d_assessment',130,'208','130','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-26 14:35:40','2019-02-06 08:37:22'),(318,'d_user',472,'Mentor GPS FANASWADI NAVELIM','mentor.gpsfanaswadi@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 14:35:57','2019-01-30 09:23:15'),(319,'d_user',473,'Mentor SHAHID K BANIPAL GPS PATRADEVI','mentor.shahidkbanipal@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 14:36:43','2019-01-30 09:23:15'),(320,'d_assessment',131,'105','131','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-26 14:36:55','2019-02-06 08:37:22'),(321,'d_assessment',132,'106','132','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-26 14:38:18','2019-02-06 08:37:22'),(322,'d_user',474,'Mentor GHS DABE','mentor.ghsdabe@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 14:38:52','2019-01-30 09:23:15'),(323,'d_user',475,'Mentor GPS KAREKHAJAN VIRDI','mentor.gpskarekhajan@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 14:39:27','2019-01-30 09:23:15'),(324,'d_assessment',133,'107','133','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-26 14:39:48','2019-02-06 08:37:22'),(325,'d_user',476,'Mentor GHS THANE','mentor.ghsthane@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 14:40:00','2019-01-30 09:23:15'),(326,'d_user',477,'Mentor GPS KUDNE','mentor.gpskudne@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 14:40:55','2019-01-30 09:23:15'),(327,'d_assessment',134,'108','134','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-26 14:41:18','2019-02-06 08:37:22'),(328,'d_user',478,'Mentor GPS CHARAVNE','mentor.gpscharavne@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 14:41:33','2019-01-30 09:23:15'),(329,'d_user',479,'Mentor GPS MAINI NAVELIM','mentor.gpsmaininavelim@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 14:42:19','2019-01-30 09:23:15'),(330,'d_user',480,'Mentor GPS DABE','mentor.gpsdabe@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 14:42:40','2019-01-30 09:23:15'),(331,'d_assessment',135,'209','135','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-26 14:43:11','2019-02-06 08:37:22'),(332,'d_assessment',136,'109','136','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-26 14:43:30','2019-02-06 08:37:22'),(333,'d_user',481,'Mentor GPS GOLALI','mentor.gpsgolali@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 14:44:01','2019-01-30 09:23:15'),(334,'d_assessment',137,'210','137','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-26 14:44:11','2019-02-06 08:37:22'),(335,'d_user',482,'Mentor GPS NAVELIM','mentor.gpsnavelim@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 14:44:19','2019-01-30 09:23:15'),(336,'d_assessment',138,'116','138','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-26 14:45:12','2019-02-06 08:37:22'),(337,'d_assessment',139,'211','139','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-26 14:45:31','2019-02-06 08:37:22'),(338,'d_user',483,'Mentor GPS HIVRE BADRUK','mentor.gpshivrebadruk@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 14:45:38','2019-01-30 09:23:15'),(339,'d_user',484,'Mentor GPS SANKHALI','mentor.gpssankhali@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 14:46:06','2019-01-30 09:23:15'),(340,'d_user',485,'Mentor GPS KOPARDE','mentor.gpskoparde@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 14:46:53','2019-01-30 09:23:15'),(341,'d_assessment',140,'212','140','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-26 14:47:04','2019-02-06 08:37:22'),(342,'d_assessment',141,'111','141','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-26 14:47:19','2019-02-06 08:37:22'),(343,'d_user',486,'Mentor GPS MHAUS','mentor.gpsmhaus@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 14:48:20','2019-01-30 09:23:15'),(344,'d_assessment',142,'112','142','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-26 14:48:35','2019-02-06 08:37:22'),(345,'d_assessment',143,'213','143','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-26 14:49:01','2019-02-06 08:37:22'),(346,'d_user',487,'Mentor GPS PAL','mentor.gpspal@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 14:49:39','2019-01-30 09:23:15'),(347,'d_assessment',144,'214','144','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-26 14:50:26','2019-02-06 08:37:22'),(348,'d_user',488,'Mentor GPS RIVE','mentor.gpsrive@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 14:50:40','2019-01-30 09:23:15'),(349,'d_assessment',145,'215','145','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-26 14:51:37','2019-02-06 08:37:22'),(350,'d_user',489,'Mentor GPS THANE','mentor.gpsthane@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 14:51:51','2019-01-30 09:23:15'),(351,'d_assessment',146,'113','146','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-26 14:53:33','2019-02-06 08:37:22'),(352,'d_user',490,'Mentor GHS SAVARDEM','mentor.ghssavardem@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 14:54:28','2019-01-30 09:23:15'),(353,'d_user',491,'Mentor GPMS KUDSE','mentor.gpmskudse@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 14:57:48','2019-01-30 09:23:15'),(354,'d_assessment',147,'115','147','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-26 14:58:15','2019-02-06 08:37:22'),(355,'d_user',492,'Mentor GPS VIRDI','mentor.gpsvirdi@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 14:59:20','2019-01-30 09:23:15'),(356,'d_user',493,'Mentor GPMS VELGUEM','mentor.gpmsvelguem@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 14:59:48','2019-01-30 09:23:15'),(357,'d_user',494,'Mentor GPS BANDIRWADA','mentor.gpsbandirwada@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 15:01:05','2019-01-30 09:23:15'),(358,'d_user',495,'Mentor G.P.M.S. HASAPUR','mentor.gpmshasapur11@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 15:02:11','2019-01-30 09:23:15'),(359,'d_user',496,'Mentor GPS DHARKHAND','mentor.gpsdharkhand@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 15:03:00','2019-01-30 09:23:15'),(360,'d_user',497,'Mentor GPS GAULMOL VELGUEM','mentor.gpsgaulmolvel@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 15:04:44','2019-01-30 09:23:15'),(361,'d_user',498,'Mentor G.P.S. CHANDEL','mentor.gpschandel12@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 15:05:12','2019-01-30 09:23:15'),(362,'d_assessment',148,'117','148','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-26 15:07:23','2019-02-06 08:37:22'),(363,'d_assessment',149,'118','149','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-26 15:10:02','2019-02-06 08:37:22'),(364,'d_user',499,'Mentor GPS KARANZOL','mentor.gpskaranzol1@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 15:28:46','2019-01-30 09:23:15'),(365,'d_assessment',150,'216','150','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-26 15:28:55','2019-02-06 08:37:22'),(366,'d_user',500,'Mentor G.P.S. DHADA','mentor.gpsdhada@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 15:29:17','2019-01-30 09:23:15'),(367,'d_user',501,'Mentor GPS KARMALI BADRUK','mentor.gpskarmalibad@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 15:30:05','2019-01-30 09:23:15'),(368,'d_user',502,'Mentor G.P.S. DHAMSHE','mentor.gpsdhamshe@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 15:30:40','2019-01-30 09:23:15'),(369,'d_user',503,'Mentor GPS KUMTHOL','mentor.gpskumthol@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 15:31:47','2019-01-30 09:23:15'),(370,'d_user',504,'Mentor G.P.S. GULELI','mentor.gpsguleli@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 15:31:59','2019-01-30 09:23:15'),(371,'d_user',505,'Mentor GPS SAWARDEM','mentor.gpssawardem@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 15:33:21','2019-01-30 09:23:15'),(372,'d_user',506,'Mentor G.P.S. KANKIRE','mentor.gpskankire@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 15:33:52','2019-01-30 09:23:15'),(373,'d_user',507,'Mentor GPS SHIR','mentor.gpsshir@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 15:34:36','2019-01-30 09:23:15'),(374,'d_user',508,'Mentor GPS SONAL','mentor.gpssonal@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 15:36:04','2019-01-30 09:23:15'),(375,'d_user',509,'Mentor G.P.S. MAINGINE','mentor.gpsmaingine@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 15:37:37','2019-01-30 09:23:15'),(376,'d_assessment',151,'119','151','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-26 15:37:48','2019-02-06 08:37:22'),(377,'d_user',510,'Mentor G.P.S. MALPAN','mentor.gpsmalpan@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 15:39:01','2019-01-30 09:23:15'),(378,'d_assessment',152,'126','152','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-26 15:39:13','2019-02-06 08:37:22'),(379,'d_user',511,'Mentor G.P.S. PAIKUL SATTARI','mentor.gpspaikulsattari@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 15:40:21','2019-01-30 09:23:15'),(380,'d_assessment',153,'121','153','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-26 15:40:57','2019-02-06 08:37:22'),(381,'d_user',512,'Mentor G.P.S. SHEL MELAULI','mentor.gpsshelmelauli@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 15:41:42','2019-01-30 09:23:15'),(382,'d_assessment',154,'122','154','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-26 15:42:21','2019-02-06 08:37:22'),(383,'d_user',513,'Mentor G.P.S. SHIRSODE','mentor.gpsshirsode@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 15:43:00','2019-01-30 09:23:15'),(384,'d_assessment',155,'217','155','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-26 15:43:31','2019-02-06 08:37:22'),(385,'d_user',514,'Mentor G.H.S. KERI','mentor.ghskeri@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 15:43:49','2019-01-30 09:23:15'),(386,'d_assessment',156,'123','156','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-26 15:43:51','2019-02-06 08:37:22'),(387,'d_user',515,'Mentor G.H.S. SURLA','mentor.ghssurla@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 15:45:01','2019-01-30 09:23:15'),(388,'d_assessment',157,'124','157','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-26 15:45:40','2019-02-06 08:37:22'),(389,'d_user',516,'Mentor G.P.S.  KERI','mentor.gpskeri@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 15:46:23','2019-01-30 09:23:15'),(390,'d_assessment',158,'125','158','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-26 15:54:21','2019-02-06 08:37:22'),(391,'d_assessment',159,'127','159','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-26 15:57:02','2019-02-06 08:37:22'),(392,'d_user',517,'Mentor GPS KHADKI','mentor.gpskhadki@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 15:58:57','2019-01-30 09:23:15'),(393,'d_user',518,'Mentor G.P.S.  RE COLONY MORLEM','mentor.gpsrecolonymorlem@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 15:59:06','2019-01-30 09:23:15'),(394,'d_assessment',160,'128','160','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-26 15:59:22','2019-02-06 08:37:22'),(395,'d_user',519,'Mentor G.P.S. BAHERILWADA','mentor.gpsbaherilwada@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 16:00:09','2019-01-30 09:23:15'),(396,'d_user',520,'Mentor GHS AMBEDEM NAGARGAO','mentor.ghsambedem@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 16:00:16','2019-01-30 09:23:15'),(397,'d_user',521,'Mentor GPMS NANODA BAMBAR','mentor.gpmsnanoda@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 16:02:11','2019-01-30 09:23:15'),(398,'d_user',522,'Mentor G.P.S. DEULWADA MORLEM','mentor.gpsdeulwadamorlem@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 16:03:07','2019-01-30 09:23:15'),(399,'d_user',523,'Mentor GPMS USTE','mentor.gpmsuste@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 16:03:52','2019-01-30 09:23:15'),(400,'d_user',524,'Mentor G.P.S. GHODEMAL MORLE','mentor.gpsghodemal@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 16:04:43','2019-01-30 09:23:15'),(401,'d_user',525,'Mentor GPS SATRE','mentor.gpssatre@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 16:05:22','2019-01-30 09:23:15'),(402,'d_user',526,'Mentor GPS TEMBKARWADA USTE','mentor.gpstembkarwada@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 16:10:00','2019-01-30 09:23:15'),(403,'d_user',527,'Mentor GPS BRAHMAKARMALI','mentor.gpsbrahmakarmal@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 16:12:26','2019-01-30 09:23:15'),(404,'d_user',528,'Mentor GPS DHAVE','mentor.gpsdhave@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 16:13:39','2019-01-30 09:23:15'),(405,'d_user',529,'Mentor G.P.S. GHOTELI NO. 1','mentor.gpsghoteli1@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 16:14:30','2019-01-30 09:23:15'),(406,'d_user',530,'Mentor GPS HEDODE','mentor.gpshedode@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 16:14:53','2019-01-30 09:23:15'),(407,'d_assessment',161,'220','161','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-26 16:15:23','2019-02-06 08:37:22'),(408,'d_user',531,'Mentor G.P.S. GHOTELI NO. 2','mentor.gpsghoteli2@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 16:15:57','2019-01-30 09:23:15'),(409,'d_user',532,'Mentor GPS KODAL','mentor.gpskodal@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 16:16:14','2019-01-30 09:23:15'),(410,'d_user',533,'Mentor GPS MALOLI','mentor.gpsmaloli@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 16:17:24','2019-01-30 09:23:15'),(411,'d_user',534,'Mentor GPS NAGARGAO','mentor.gpsnagargao@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 16:18:37','2019-01-30 09:23:15'),(412,'d_assessment',162,'223','162','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-26 16:18:43','2019-02-06 08:37:22'),(413,'d_user',535,'Mentor GPS NANODA SATTARI','mentor.gpsnanodasatt@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 16:20:05','2019-01-30 09:23:15'),(414,'d_user',536,'Mentor GPS SATODE','mentor.gpssatode@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 16:21:26','2019-01-30 09:23:15'),(415,'d_user',537,'Mentor G.P.S. SHIROLI','mentor.gpsshiroli@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 16:21:44','2019-01-30 09:23:15'),(416,'d_user',538,'Mentor GPS SHELOP THANE','mentor.gpsshelop@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 16:22:38','2019-01-30 09:23:15'),(417,'d_user',539,'Mentor G.P.S. SURLA','mentor.gpssurla@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 16:23:19','2019-01-30 09:23:15'),(418,'d_user',540,'Mentor GPS RAWAN','mentor.gpsrawan@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 16:23:42','2019-01-30 09:23:15'),(419,'d_user',541,'Mentor G.P.M.S. VALPOI','mentor.gpmsvalpoi@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 16:24:45','2019-01-30 09:23:15'),(420,'d_user',542,'Mentor GHS MORLEM','mentor.ghsmorlem@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 16:25:07','2019-01-30 09:23:15'),(421,'d_user',543,'Mentor GPS  BELWADA PARYE','mentor.gpsbelwada@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 16:26:17','2019-01-30 09:23:15'),(422,'d_user',544,'Mentor GPS BAGWADA','mentor.gpsbagwada@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 16:27:28','2019-01-30 09:23:15'),(423,'d_assessment',163,'218','163','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-26 16:27:46','2019-02-06 08:37:22'),(424,'d_user',545,'Mentor G.P.S. DABOS','mentor.gpsdabos@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 16:27:58','2019-01-30 09:23:15'),(425,'d_user',546,'Mentor GPS DHAT PADOSHE','mentor.gpsdhat@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 16:28:23','2019-01-30 09:23:15'),(426,'d_user',547,'Mentor GPS GHOLWADA','mentor.gpsgholwada@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 16:29:36','2019-01-30 09:23:15'),(427,'d_user',548,'Mentor GPS KELAWADA','mentor.gpskelawada@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 16:33:03','2019-01-30 09:23:15'),(428,'d_user',549,'Mentor GPS KESARKARWADA','mentor.gpskesarkar@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 16:34:10','2019-01-30 09:23:15'),(429,'d_assessment',164,'304','164','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-26 16:35:54','2019-02-06 08:37:22'),(430,'d_user',550,'Mentor GPS MHALSHEKARWADA PARYE','mentor.gpsmhalsheka@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 16:35:55','2019-01-30 09:23:15'),(431,'d_user',551,'Mentor GPS PARYE','mentor.gpsparye@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 16:37:01','2019-01-30 09:23:15'),(432,'d_assessment',165,'305','165','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-26 16:37:37','2019-02-06 08:37:22'),(433,'d_user',552,'Mentor GPS TAMITAGI PARYE','mentor.gpstamitagi@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 16:38:06','2019-01-30 09:23:15'),(434,'d_user',553,'Mentor GHS ADVOI SATTARI','mentor.ghsadvoi@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 16:40:26','2019-01-30 09:23:15'),(435,'d_user',554,'Mentor GHS PISSURLEM','mentor.ghspissurlem@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 16:41:33','2019-01-30 09:23:15'),(436,'d_user',555,'Mentor GPMS PADELI','mentor.gpmspadeli@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 16:42:37','2019-01-30 09:23:15'),(437,'d_user',556,'Mentor GPS ADVOI','mentor.gpsadvoi@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 16:43:51','2019-01-30 09:23:15'),(438,'d_user',557,'Mentor G.P.S. GUNDELWADA','mentor.gpsgundelwada@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 16:53:37','2019-01-30 09:23:15'),(439,'d_user',558,'Mentor G.P.S. MASSORDEM','mentor.gpsmassordem@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 16:54:45','2019-01-30 09:23:15'),(440,'d_user',559,'Mentor G.P.S. NAGVE','mentor.gpsnagve@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 16:55:42','2019-01-30 09:23:15'),(441,'d_user',560,'Mentor GPS BHIRONDA','mentor.gpsbhironda@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 16:56:29','2019-01-30 09:23:15'),(442,'d_user',561,'Mentor GPS GAONKARWADA VANTE','mentor.gpsgaonkarwada11@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 16:58:05','2019-01-30 09:23:15'),(443,'d_user',562,'Mentor G.P.S. NAYAWADA','mentor.gpsnayawada@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 16:58:58','2019-01-30 09:23:15'),(444,'d_user',563,'Mentor G.P.S. VELUS','mentor.gpsvelus@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 16:59:55','2019-01-30 09:23:15'),(445,'d_user',564,'Mentor GPS KHODIYE','mentor.gpskhodiye@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 17:00:23','2019-01-30 09:23:15'),(446,'d_user',565,'Mentor G.H.S. BHUIPAL SATTARI','mentor.ghsbhuilpal@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 17:01:14','2019-01-30 09:23:15'),(447,'d_user',566,'Mentor GPS KUMBHARKHAN','mentor.gpskumbhar@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 17:01:40','2019-01-30 09:23:15'),(448,'d_user',567,'Mentor G.H.S. HONDA SATTARI','mentor.ghshonda@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 17:02:09','2019-01-30 09:23:15'),(449,'d_user',568,'Mentor GPS MHADAI VANTE','mentor.gpsmhadai@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 17:02:56','2019-01-30 09:23:15'),(450,'d_user',569,'Mentor G.P.S.  DHANGARWADA','mentor.gpsdhangarwada@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 17:03:32','2019-01-30 09:23:15'),(451,'d_user',570,'Mentor GPS PANSHE','mentor.gpspanshe@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 17:04:34','2019-01-30 09:23:15'),(452,'d_assessment',166,'226','166','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-26 17:04:42','2019-02-06 08:37:22'),(453,'d_user',571,'Mentor G.P.S. BHUIPAL','mentor.gpsbhuilpal@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 17:05:14','2019-01-30 09:23:15'),(454,'d_user',572,'Mentor GPS SAVARSHE','mentor.gpssavarshe@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 17:05:56','2019-01-30 09:23:15'),(455,'d_user',573,'Mentor G.P.S. BODANWADA HONDA','mentor.gpsbodanwada@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 17:06:41','2019-01-30 09:23:15'),(456,'d_assessment',167,'228','167','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-26 17:06:56','2019-02-06 08:37:22'),(457,'d_user',574,'Mentor GPS VANTE','mentor.gpsvante@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 17:07:08','2019-01-30 09:23:15'),(458,'d_user',575,'Mentor G.P.S. DHATWADA PISURLEM','mentor.gpsdhatwadapisurlem@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 17:07:59','2019-01-30 09:23:15'),(459,'d_user',576,'Mentor GPS MURMUNE','mentor.gpsmurmune@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 17:08:19','2019-01-30 09:23:15'),(460,'d_assessment',168,'229','168','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-26 17:08:59','2019-02-06 08:37:22'),(461,'d_user',577,'Mentor G.P.S. DHONKALWADA','mentor.gpsdhonakalwada@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 17:09:07','2019-01-30 09:23:15'),(462,'d_user',578,'Mentor GHS GULELI SATTARI','mentor.ghsgulelisat@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 17:09:31','2019-01-30 09:23:15'),(463,'d_user',579,'Mentor G.P.S. GAONKARWADA HONDA','mentor.gpsgoankarwada@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 17:10:14','2019-01-30 09:23:15'),(464,'d_assessment',169,'230','169','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-26 17:10:21','2019-02-06 08:37:22'),(465,'d_user',580,'Mentor GHS SHELOP KHURD','mentor.ghsshelop@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 17:10:29','2019-01-30 09:23:15'),(466,'d_user',581,'Mentor G.P.S. HONDA SATTARI','mentor.gpshondasattari@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 17:11:17','2019-01-30 09:23:15'),(467,'d_assessment',170,'231','170','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-26 17:11:57','2019-02-06 08:37:22'),(468,'d_user',582,'Mentor G.P.S. KAJARYACHI VHALI','mentor.gpskajaryachi@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 17:12:55','2019-01-30 09:23:15'),(469,'d_assessment',171,'232','171','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-26 17:13:13','2019-02-06 08:37:22'),(470,'d_assessment',172,'233','172','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-26 17:14:44','2019-02-06 08:37:22'),(471,'d_user',583,'Mentor G.P.S. NARAYAN NAGAR','mentor.gpsnarayan@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 17:14:45','2019-01-30 09:23:15'),(472,'d_user',584,'Mentor GHS SHELOP KHURD','mentor.ghsshelop1@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 17:15:18','2019-01-30 09:23:15'),(473,'d_user',585,'Mentor G.P.S. SALELI','mentor.gpssaleli@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 17:15:40','2019-01-30 09:23:15'),(474,'d_user',586,'Mentor GPMS KHOTODE','mentor.gpmskhotode@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 17:16:32','2019-01-30 09:23:15'),(475,'d_user',587,'Mentor G.P.S. SOLIYE','mentor.gpssoliye@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 17:16:37','2019-01-30 09:23:15'),(476,'d_user',588,'MentorG.P.S. SONUS','mentor.gpssonus@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 17:17:35','2019-01-30 09:23:15'),(477,'d_user',589,'Mentor GPS SHELOP KHURD','mentor.gpsshelop2@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 17:17:40','2019-01-30 09:23:15'),(478,'d_user',590,'Mentor GPS BARAJAN','mentor.gpsbarajan@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-26 17:19:20','2019-01-30 09:23:15'),(479,'d_assessment',173,'234','173','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-26 17:30:13','2019-02-06 08:37:22'),(480,'d_assessment',174,'235','174','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-26 17:55:40','2019-02-06 08:37:22'),(481,'d_assessment',175,'236','175','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-26 17:56:39','2019-02-06 08:37:22'),(482,'d_assessment',176,'237','176','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-26 17:58:21','2019-02-06 08:37:22'),(483,'d_assessment',177,'238','177','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-26 18:01:18','2019-02-06 08:37:22'),(484,'d_assessment',178,'239','178','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-26 18:02:40','2019-02-06 08:37:22'),(485,'d_assessment',179,'240','179','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-26 18:03:50','2019-02-06 08:37:22'),(486,'d_assessment',180,'241','180','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-26 18:04:47','2019-02-06 08:37:22'),(487,'d_assessment',181,'242','181','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-26 18:07:01','2019-02-06 08:37:22'),(488,'d_assessment',182,'243','182','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-26 18:12:45','2019-02-06 08:37:22'),(489,'d_assessment',183,'244','183','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-26 18:24:30','2019-02-06 08:37:22'),(490,'d_assessment',184,'245','184','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-26 18:26:12','2019-02-06 08:37:22'),(491,'d_assessment',185,'247','185','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-26 18:33:50','2019-02-06 08:37:22'),(492,'d_assessment',186,'248','186','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-26 18:36:37','2019-02-06 08:37:22'),(493,'d_assessment',187,'249','187','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-26 18:37:39','2019-02-06 08:37:22'),(494,'d_assessment',188,'251','188','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-26 18:41:33','2019-02-06 08:37:22'),(495,'d_assessment',189,'253','189','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-26 18:51:19','2019-02-06 08:37:22'),(496,'d_assessment',190,'254','190','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-26 18:53:00','2019-02-06 08:37:22'),(497,'d_assessment',191,'255','191','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-26 18:54:15','2019-02-06 08:37:22'),(498,'d_assessment',192,'257','192','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-26 18:55:39','2019-02-06 08:37:22'),(499,'d_assessment',193,'258','193','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-26 18:56:49','2019-02-06 08:37:22'),(500,'d_assessment',194,'259','194','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-26 18:57:49','2019-02-06 08:37:22'),(501,'d_assessment',195,'260','195','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-26 18:59:01','2019-02-06 08:37:22'),(502,'d_assessment',196,'261','196','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-26 19:00:22','2019-02-06 08:37:22'),(503,'d_assessment',197,'262','197','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-26 19:09:17','2019-02-06 08:37:22'),(504,'d_assessment',198,'306','198','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-27 09:49:53','2019-02-06 08:37:22'),(505,'d_assessment',199,'307','199','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-27 09:51:17','2019-02-06 08:37:22'),(506,'d_assessment',200,'270','200','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-27 09:52:24','2019-02-06 08:37:22'),(507,'d_assessment',201,'272','201','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-27 10:29:22','2019-02-06 08:37:22'),(508,'d_assessment',202,'273','202','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-27 10:31:17','2019-02-06 08:37:22'),(509,'d_assessment',203,'263','203','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-27 10:55:14','2019-02-06 08:37:22'),(510,'d_assessment',204,'264','204','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-27 10:59:03','2019-02-06 08:37:22'),(511,'d_assessment',205,'267','205','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-27 11:05:31','2019-02-06 08:37:22'),(512,'d_assessment',206,'268','206','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-27 11:26:25','2019-02-06 08:37:22'),(513,'d_assessment',207,'219','207','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-27 11:28:29','2019-02-06 08:37:22'),(514,'d_assessment',208,'221','208','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-27 11:30:25','2019-02-06 08:37:22'),(515,'d_assessment',209,'222','209','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-27 11:31:56','2019-02-06 08:37:22'),(516,'d_assessment',210,'225','210','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-27 11:33:29','2019-02-06 08:37:22'),(517,'d_assessment',211,'227','211','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-27 11:38:54','2019-02-06 08:37:22'),(518,'d_assessment',212,'252','212','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-27 11:40:32','2019-02-06 08:37:22'),(519,'d_assessment',213,'256','213','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-27 11:41:45','2019-02-06 08:37:22'),(520,'d_assessment',214,'265','214','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-27 11:46:51','2019-02-06 08:37:22'),(521,'d_assessment',215,'269','215','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-27 11:48:17','2019-02-06 08:37:22'),(522,'d_assessment',216,'271','216','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-27 11:50:16','2019-02-06 08:37:22'),(523,'d_assessment',217,'280','217','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-27 11:51:31','2019-02-06 08:37:22'),(524,'d_assessment',218,'291','218','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-27 11:54:41','2019-02-06 08:37:22'),(525,'d_assessment',219,'292','219','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-27 12:00:05','2019-02-06 08:37:22'),(526,'d_assessment',220,'293','220','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-27 12:01:39','2019-02-06 08:37:22'),(527,'d_assessment',221,'294','221','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-27 12:02:47','2019-02-06 08:37:22'),(528,'d_assessment',222,'295','222','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-27 12:04:05','2019-02-06 08:37:22'),(529,'d_assessment',223,'296','223','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-27 12:08:18','2019-02-06 08:37:22'),(530,'d_assessment',224,'297','224','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-27 12:09:21','2019-02-06 08:37:22'),(531,'d_assessment',225,'298','225','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-27 12:10:49','2019-02-06 08:37:22'),(532,'d_assessment',226,'299','226','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-27 12:11:58','2019-02-06 08:37:22'),(533,'d_assessment',227,'301','227','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-27 12:15:36','2019-02-06 08:37:22'),(534,'d_assessment',228,'300','228','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-27 12:19:37','2019-02-06 08:37:22'),(535,'d_assessment',229,'303','229','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-27 12:20:53','2019-02-06 08:37:22'),(536,'d_assessment',230,'266','230','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-27 12:22:45','2019-02-06 08:37:22'),(537,'d_assessment',231,'274','231','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-27 12:33:30','2019-02-06 08:37:22'),(538,'d_assessment',232,'275','232','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-27 12:34:19','2019-02-06 08:37:22'),(539,'d_assessment',233,'276','233','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-27 12:35:02','2019-02-06 08:37:22'),(540,'d_assessment',234,'277','234','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-27 12:36:03','2019-02-06 08:37:22'),(541,'d_assessment',235,'278','235','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-27 12:37:15','2019-02-06 08:37:22'),(542,'d_assessment',236,'279','236','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-27 12:39:19','2019-02-06 08:37:22'),(543,'d_assessment',237,'281','237','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-27 12:41:16','2019-02-06 08:37:22'),(544,'d_assessment',238,'282','238','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-27 12:42:18','2019-02-06 08:37:22'),(545,'d_assessment',239,'284','239','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-27 12:46:54','2019-02-06 08:37:22'),(546,'d_assessment',240,'285','240','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-27 12:48:28','2019-02-06 08:37:22'),(547,'d_assessment',241,'286','241','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-27 12:49:35','2019-02-06 08:37:22'),(548,'d_assessment',242,'287','242','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-27 12:50:57','2019-02-06 08:37:22'),(549,'d_assessment',243,'288','243','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-27 12:56:54','2019-02-06 08:37:22'),(550,'d_assessment',244,'199','244','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-27 14:14:43','2019-02-06 08:37:22'),(551,'d_assessment',245,'290','245','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-27 14:15:39','2019-02-06 08:37:22'),(552,'d_assessment',246,'189','246','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-27 14:16:53','2019-02-06 08:37:22'),(553,'d_assessment',247,'183','247','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-27 14:17:58','2019-02-06 08:37:22'),(554,'d_assessment',248,'181','248','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-27 14:18:53','2019-02-06 08:37:22'),(555,'d_assessment',249,'178','249','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-27 14:20:14','2019-02-06 08:37:22'),(556,'d_assessment',250,'175','250','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-27 14:21:09','2019-02-06 08:37:22'),(557,'d_assessment',251,'168','251','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-27 14:22:59','2019-02-06 08:37:22'),(558,'d_assessment',252,'165','252','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-27 14:33:46','2019-02-06 08:37:22'),(559,'d_assessment',253,'155','253','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-27 14:38:26','2019-02-06 08:37:22'),(560,'d_assessment',254,'145','254','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-27 14:44:12','2019-02-06 08:37:22'),(561,'d_assessment',255,'141','255','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-27 14:45:11','2019-02-06 08:37:22'),(562,'d_assessment',256,'131','256','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-27 14:49:30','2019-02-06 08:37:22'),(563,'d_assessment',257,'130','257','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-27 14:50:47','2019-02-06 08:37:22'),(564,'d_assessment',258,'132','258','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-29 20:21:24','2019-02-06 08:37:22'),(565,'d_assessment',259,'162','259','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-30 10:10:52','2019-02-06 08:37:22'),(566,'d_assessment',260,'43','260','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-30 15:01:30','2019-02-06 08:37:22'),(567,'d_user',592,'Mentor G.P.S Valpe','mentor.gpsvalpe@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-30 18:45:35','2019-01-30 09:23:15'),(568,'d_assessment',261,'308','261','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-07-30 18:48:38','2019-02-06 08:37:22'),(569,'d_user',593,'Mentor GPS AMONA','mentor.gpsamona@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-31 14:40:06','2019-01-30 09:23:15'),(570,'d_user',594,'Mentor GPS BHANDARWADA AMONA','mentor.gpsbhandarwadaamona@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-07-31 15:35:16','2019-01-30 09:23:15'),(571,'d_user',596,'Mentor GPS Bhatpavni','mentor.gpsbhatpavni@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-08-01 23:01:42','2019-01-30 09:23:15'),(572,'d_assessment',262,'309','262','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-08-01 23:03:34','2019-02-06 08:37:22'),(573,'d_assessment',263,'206','263','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-08-04 15:41:55','2019-02-06 08:37:22'),(574,'d_assessment',264,'310','264','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-08-06 14:36:00','2019-02-06 08:37:22'),(575,'d_assessment',265,'43','265','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-08-09 12:29:24','2019-02-06 08:37:22'),(576,'d_assessment',266,'120','266','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-08-15 11:07:27','2019-02-06 08:37:22'),(577,'d_user',600,'Mentor GHS Bicholim','mentor.ghsbicholim@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-08-15 17:59:17','2019-01-30 09:23:15'),(578,'d_assessment',267,'311','267','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-08-15 18:00:29','2019-02-06 08:37:22'),(579,'d_assessment',268,'86','268','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-08-17 12:33:46','2019-02-06 08:37:22'),(580,'d_assessment',269,'312','269','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-08-19 12:57:25','2019-02-06 08:37:22'),(581,'d_assessment',270,'114','270','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-08-19 13:31:57','2019-02-06 08:37:22'),(582,'d_user',603,'Mentor GPMS Valpoi','mentor.gpmsvalpoi@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-08-22 12:51:30','2019-01-30 09:23:15'),(583,'d_user',604,'Mentor GPS Naneli','mentor.gpsnaneli@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-08-22 12:54:48','2019-01-30 09:23:15'),(584,'d_user',605,'Mentor G.P.S. SAWANTWADA PAL','mentor.gpssawantwadapal@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-08-22 12:56:39','2019-01-30 09:23:15'),(585,'d_user',606,'Mentor GPS Zarme','mentor.gpszarme@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-08-22 12:58:33','2019-01-30 09:23:15'),(586,'d_user',607,'Mentor GPMS Nanoda Bambar','mentor.gpmsnanodabambar@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-08-22 13:02:41','2019-01-30 09:23:15'),(587,'d_assessment',271,'87','271','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-08-22 13:03:22','2019-02-06 08:37:22'),(588,'d_assessment',272,'302','272','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-08-22 15:25:08','2019-02-06 08:37:22'),(589,'d_user',609,'Mentor GPS SALVADOR DU MUND','mentor.gpssalvador@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-08-22 16:04:04','2019-01-30 09:23:15'),(590,'d_user',610,'Mentor GPS GOLJUVEM KHORJUVEM','mentor.gpsgoljuvem@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-08-22 16:07:12','2019-01-30 09:23:15'),(591,'d_user',615,'Mentor GPS NACHINOLA','mentor.gpsnachinola@gmal.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-08-22 16:17:30','2019-01-30 09:23:15'),(592,'d_user',617,'Mentor GPS PALIEM UCCASSAIM','mentor.gpspaliem@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-08-22 16:19:05','2019-01-30 09:23:15'),(593,'d_user',619,'Mentor GPS BETIM','mentor.gpsbetim@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-08-22 16:20:04','2019-01-30 09:23:15'),(594,'d_user',621,'Mentor GPS ALTO BETIM','mentor.gpsalto@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-08-22 16:21:00','2019-01-30 09:23:15'),(595,'d_user',622,'Mentor GPS SERULA BRITONA','mentor.gpsserula@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-08-22 16:22:01','2019-01-30 09:23:15'),(596,'d_user',624,'Mentor GPS SERULA PAITHAN','mentor.gpspaithan@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-08-22 16:23:07','2019-01-30 09:23:15'),(597,'d_user',626,'Mentor GPS CHAPORA','mentor.chapora@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-08-22 16:24:03','2019-01-30 09:23:15'),(598,'d_user',627,'Mentor GPS HUDO CHAPORA','mentor.gpshudo@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-08-22 16:24:56','2019-01-30 09:23:15'),(599,'d_user',628,'Mentor GPS TEMBI ANJUNA','mentor.gpstembi@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-08-22 16:25:45','2019-01-30 09:23:15'),(600,'d_user',629,'Mentor GPS CHIVAR BAND ANJUNA','mentor.gpschivar@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-08-22 16:26:35','2019-01-30 09:23:15'),(601,'d_user',631,'Mentor GPS BANDIRWADA CHAPORA','mentor.gpsbndrwada@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-08-22 16:28:03','2019-01-30 09:23:15'),(602,'d_user',633,'Mentor GPS ARAISWADA NAGOA','mentor.gpsaraiswada@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-08-22 16:29:06','2019-01-30 09:23:15'),(603,'d_user',634,'Mentor GPS NAGOA ARPORA','mentor.gpsnagoa@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-08-22 16:29:54','2019-01-30 09:23:15'),(604,'d_user',636,'Mentor GPS SAKWADI BAG ARPORA','mentor.gpssakwadi@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-08-22 16:30:40','2019-01-30 09:23:15'),(605,'d_user',637,'Mentor GPS SIMWADA','mentor.gpssimwada@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-08-22 16:31:30','2019-01-30 09:23:15'),(606,'d_user',639,'Mentor GPS SALIGAO','mentor.gpssaligao@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-08-22 16:32:21','2019-01-30 09:23:15'),(607,'d_user',641,'Mentor GPMS SUCCOR','mentor.gpmssuccor@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-08-22 16:33:28','2019-01-30 09:23:15'),(608,'d_user',643,'Mentor GPS SANTA CRUZ BASTORA','mentor.gpssanta@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-08-22 16:34:25','2019-01-30 09:23:15'),(609,'d_user',644,'Mentor GPS BAMBURDA MOIRA','mentor.gpsbamburda@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-08-22 16:35:10','2019-01-30 09:23:15'),(610,'d_user',645,'Mentor GPS JOSHWADA SOCORRO','mentor.gpsjoshwada@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-08-22 16:36:00','2019-01-30 09:23:15'),(611,'d_user',647,'Mentor GPS NAIKAWADA','mentor.gpsnaikawada@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-08-22 16:36:54','2019-01-30 09:23:15'),(612,'d_user',648,'Mentor GPS SAWATAWADA','mentor.gpssawatawada@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-08-22 16:37:41','2019-01-30 09:23:15'),(613,'d_user',649,'Mentor GPS CANCA','mentor.gpscanca@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-08-22 16:38:27','2019-01-30 09:23:15'),(614,'d_user',651,'Mentor GPS VERLA','mentor.gpsverla@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-08-22 16:39:10','2019-01-30 09:23:15'),(615,'d_user',652,'Mentor GPS PARRA','mentor.gpsparra@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-08-22 16:39:54','2019-01-30 09:23:15'),(616,'d_user',654,'Mentor GPS MUNANG ASSAGAO','mentor.gpsmunang@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-08-22 16:40:35','2019-01-30 09:23:15'),(617,'d_user',656,'Mentor GPS ABADE FARIA','mentor.gpsabade@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-08-22 16:41:30','2019-01-30 09:23:15'),(618,'d_user',657,'Mentor GPS CONFRARIWADA','mentor.gpsconfrariwada@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-08-22 16:42:20','2019-01-30 09:23:15'),(619,'d_user',659,'Mentor GPS DANDO CANDOLIM','mentor.gpsdando@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-08-22 16:42:58','2019-01-30 09:23:15'),(620,'d_user',661,'Mentor GPMS REIS MAGOS','mentor.gpmsreis@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-08-22 16:44:48','2019-01-30 09:23:15'),(621,'d_user',663,'Mentor GPS SAIPEM CANDOLIM','mentor.gpssaipem@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-08-22 16:45:58','2019-01-30 09:23:15'),(622,'d_user',665,'Mentor GPS DEUL WADA REIS MAGOS','mentor.gpsdeul@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-08-22 16:46:39','2019-01-30 09:23:15'),(623,'d_user',666,'Mentor GPS GAUNSAWADA','mentor.gpsgaunsawada@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-08-22 16:47:34','2019-01-30 09:23:15'),(624,'d_user',668,'Mentor GPS KAMARKHAJAN','mentor.gpskamarkhajan@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-08-22 16:48:22','2019-01-30 09:23:15'),(625,'d_user',670,'Mentor GPS NAMOSHI GUIRIM','mentor.gpsnamoshi@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-08-22 16:49:03','2019-01-30 09:23:15'),(626,'d_user',671,'Mentor GPS MAPUSA','mentor.gpsmapusa@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-08-22 16:49:48','2019-01-30 09:23:15'),(627,'d_user',672,'Mentor GPS DHULER','mentor.gpsdhuler@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-08-22 16:50:30','2019-01-30 09:23:15'),(628,'d_user',673,'Mentor GPS KARASWADA','mentor.gpskaraswada@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-08-22 16:51:11','2019-01-30 09:23:15'),(629,'d_user',674,'Mentor GPMS MAPUSA','mentor.gpmsmapusa@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-08-22 16:51:59','2019-01-30 09:23:15'),(630,'d_user',676,'Mentor GPS MONTE DE GUIRIM','mentor.gpsmonte@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-08-22 16:52:40','2019-01-30 09:23:15'),(631,'d_user',678,'Mentor GPS NERUL','mentor.gpsnerul@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-08-22 16:53:25','2019-01-30 09:23:15'),(632,'d_assessment',273,'148','273','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-08-22 16:53:44','2019-02-06 08:37:22'),(633,'d_user',679,'Mentor GPS MOICAWADDO PILERNE','mentor.gpsmoicawaddo@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-08-22 16:54:13','2019-01-30 09:23:15'),(634,'d_user',682,'Mentor GPS MAINA PILERNE','mentor.gpsmaina@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-08-22 16:56:12','2019-01-30 09:23:15'),(635,'d_user',683,'Mentor GPS SAVLE PILERNE','mentor.gpssavle@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-08-22 16:56:55','2019-01-30 09:23:15'),(636,'d_user',685,'Mentor GPS SANGOLDA','mentor.gpssangolda@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-08-22 16:57:38','2019-01-30 09:23:15'),(637,'d_user',687,'Mentor GPS KEL PIRNA','mentor.gpskel@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-08-22 16:58:20','2019-01-30 09:23:15'),(638,'d_user',688,'Mentor GPS TALAP PIRNA','mentor.gpstalap@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-08-22 16:59:06','2019-01-30 09:23:15'),(639,'d_user',690,'Mentor GPS MAJIL WADA REVORA','mentor.gpsmajil@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-08-22 16:59:47','2019-01-30 09:23:15'),(640,'d_user',691,'Mentor GPS TANK REVORA','mentor.gpstank@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-08-22 17:00:29','2019-01-30 09:23:15'),(641,'d_user',692,'Mentor GPS BAMANWADA','mentor.gpsbamanwada@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-08-22 17:01:15','2019-01-30 09:23:15'),(642,'d_user',694,'Mentor GPS RAI SIOLIM','mentor.gpsrai@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-08-22 17:01:53','2019-01-30 09:23:15'),(643,'d_user',696,'Mentor GPS OXEL CHOPDEM','mentor.gpsoxel@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-08-22 17:02:42','2019-01-30 09:23:15'),(644,'d_user',697,'Mentor GPS CONIWADA COLVALE','mentor.gpsconiwada@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-08-22 17:03:21','2019-01-30 09:23:15'),(645,'d_assessment',274,'135','274','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-08-22 17:03:54','2019-02-06 08:37:22'),(646,'d_user',699,'Mentor GPMS THIVIM','mentor.gpmsthivim@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-08-22 17:04:00','2019-01-30 09:23:15'),(647,'d_user',700,'Mentor GPS TARWADA COLVALE','mentor.gpstrwada@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-08-22 17:04:53','2019-01-30 09:23:15'),(648,'d_user',702,'Mentor GPS MADEL TIVIM','mentor.gpsmadel@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-08-22 17:05:31','2019-01-30 09:23:15'),(649,'d_user',703,'Mentor GPS TIVIM','mentor.gpstivim@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-08-22 17:06:09','2019-01-30 09:23:15'),(650,'d_assessment',275,'129','275','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-08-22 17:06:17','2019-02-06 08:37:22'),(651,'d_user',705,'Mentor GPS SIRSAIM','mentor.gpssirsaim@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-08-22 17:06:47','2019-01-30 09:23:15'),(652,'d_user',706,'Mentor GPMS ASSONORA','mentor.gpmsassonora@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-08-22 17:07:27','2019-01-30 09:23:15'),(653,'d_user',708,'Mentor GPS ASSONORA','mentor.gpsassonora@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-08-22 17:08:09','2019-01-30 09:23:15'),(654,'d_user',709,'Mentor GHS ALTO BETIM','mentor.ghsalto@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-08-22 17:08:50','2019-01-30 09:23:15'),(655,'d_assessment',276,'137','276','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-08-22 17:09:25','2019-02-06 08:37:22'),(656,'d_user',711,'mentor GHS NAMOSHI GURIM','mentor.ghsnamoshi@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-08-22 17:10:02','2019-01-30 09:23:15'),(657,'d_user',712,'Mentor GHS REVORA','mentor.ghsrevora@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-08-22 17:10:45','2019-01-30 09:23:15'),(658,'d_assessment',277,'151','277','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-08-22 17:12:49','2019-02-06 08:37:22'),(659,'d_assessment',278,'158','278','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-08-22 17:19:06','2019-02-06 08:37:22'),(660,'d_assessment',279,'283','279','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-08-22 17:21:08','2019-02-06 08:37:22'),(661,'d_assessment',280,'224','280','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-08-22 17:26:55','2019-02-06 08:37:22'),(662,'d_assessment',281,'246','281','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-08-22 17:33:53','2019-02-06 08:37:22'),(663,'d_assessment',282,'250','282','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-08-22 17:37:37','2019-02-06 08:37:22'),(664,'d_assessment',283,'313','283','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-08-22 18:19:17','2019-02-06 08:37:22'),(665,'d_assessment',284,'356','284','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-08-22 18:24:51','2019-02-06 08:37:22'),(666,'d_assessment',285,'334','285','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-08-22 18:25:52','2019-02-06 08:37:22'),(667,'d_assessment',286,'314','286','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-08-22 18:26:41','2019-02-06 08:37:22'),(668,'d_assessment',287,'357','287','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-08-22 18:28:23','2019-02-06 08:37:22'),(669,'d_assessment',288,'315','288','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-08-22 18:28:24','2019-02-06 08:37:22'),(670,'d_assessment',289,'335','289','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-08-22 18:29:13','2019-02-06 08:37:22'),(671,'d_assessment',290,'316','290','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-08-22 18:29:21','2019-02-06 08:37:22'),(672,'d_assessment',291,'358','291','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-08-22 18:29:48','2019-02-06 08:37:22'),(673,'d_assessment',292,'336','292','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-08-22 18:30:21','2019-02-06 08:37:22'),(674,'d_assessment',293,'317','293','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-08-22 18:30:59','2019-02-06 08:37:22'),(675,'d_assessment',294,'359','294','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-08-22 18:31:13','2019-02-06 08:37:22'),(676,'d_assessment',295,'337','295','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-08-22 18:31:31','2019-02-06 08:37:22'),(677,'d_assessment',296,'318','296','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-08-22 18:32:01','2019-02-06 08:37:22'),(678,'d_assessment',297,'360','297','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-08-22 18:32:51','2019-02-06 08:37:22'),(679,'d_assessment',298,'338','298','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-08-22 18:32:54','2019-02-06 08:37:22'),(680,'d_assessment',299,'319','299','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-08-22 18:33:05','2019-02-06 08:37:22'),(681,'d_assessment',300,'339','300','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-08-22 18:34:19','2019-02-06 08:37:22'),(682,'d_assessment',301,'361','301','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-08-22 18:34:23','2019-02-06 08:37:22'),(683,'d_assessment',302,'340','302','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-08-22 18:35:46','2019-02-06 08:37:22'),(684,'d_assessment',303,'362','303','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-08-22 18:35:48','2019-02-06 08:37:22'),(685,'d_assessment',304,'341','304','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-08-22 18:36:49','2019-02-06 08:37:22'),(686,'d_assessment',305,'363','305','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-08-22 18:37:05','2019-02-06 08:37:22'),(687,'d_assessment',306,'342','306','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-08-22 18:37:48','2019-02-06 08:37:22'),(688,'d_assessment',307,'364','307','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-08-22 18:38:29','2019-02-06 08:37:22'),(689,'d_assessment',308,'343','308','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-08-22 18:39:32','2019-02-06 08:37:22'),(690,'d_assessment',309,'365','309','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-08-22 18:40:54','2019-02-06 08:37:22'),(691,'d_assessment',310,'344','310','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-08-22 18:41:17','2019-02-06 08:37:22'),(692,'d_assessment',311,'366','311','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-08-22 18:42:07','2019-02-06 08:37:22'),(693,'d_assessment',312,'345','312','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-08-22 18:42:14','2019-02-06 08:37:22'),(694,'d_assessment',313,'346','313','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-08-22 18:43:16','2019-02-06 08:37:22'),(695,'d_assessment',314,'367','314','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-08-22 18:43:22','2019-02-06 08:37:22'),(696,'d_assessment',315,'347','315','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-08-22 18:44:31','2019-02-06 08:37:22'),(697,'d_assessment',316,'368','316','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-08-22 18:44:39','2019-02-06 08:37:22'),(698,'d_assessment',317,'348','317','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-08-22 18:45:24','2019-02-06 08:37:22'),(699,'d_assessment',318,'369','318','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-08-22 18:45:47','2019-02-06 08:37:22'),(700,'d_assessment',319,'349','319','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-08-22 18:46:29','2019-02-06 08:37:22'),(701,'d_assessment',320,'370','320','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-08-22 18:46:51','2019-02-06 08:37:22'),(702,'d_assessment',321,'350','321','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-08-22 18:47:26','2019-02-06 08:37:22'),(703,'d_assessment',322,'371','322','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-08-22 18:48:15','2019-02-06 08:37:22'),(704,'d_assessment',323,'351','323','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-08-22 18:48:37','2019-02-06 08:37:22'),(705,'d_assessment',324,'320','324','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-08-22 18:49:17','2019-02-06 08:37:22'),(706,'d_assessment',325,'372','325','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-08-22 18:49:28','2019-02-06 08:37:22'),(707,'d_assessment',326,'321','326','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-08-22 18:50:09','2019-02-06 08:37:22'),(708,'d_assessment',327,'352','327','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-08-22 18:50:11','2019-02-06 08:37:22'),(709,'d_assessment',328,'373','328','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-08-22 18:50:37','2019-02-06 08:37:22'),(710,'d_assessment',329,'353','329','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-08-22 18:51:15','2019-02-06 08:37:22'),(711,'d_assessment',330,'354','330','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-08-22 18:52:12','2019-02-06 08:37:22'),(712,'d_assessment',331,'374','331','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-08-22 18:52:23','2019-02-06 08:37:22'),(713,'d_assessment',332,'355','332','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-08-22 18:53:07','2019-02-06 08:37:22'),(714,'d_assessment',333,'375','333','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-08-22 18:53:27','2019-02-06 08:37:22'),(715,'d_assessment',334,'377','334','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-08-22 18:53:28','2019-02-06 08:37:22'),(716,'d_assessment',335,'376','335','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-08-22 18:54:33','2019-02-06 08:37:22'),(717,'d_assessment',336,'322','336','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-08-22 18:55:50','2019-02-06 08:37:22'),(718,'d_assessment',337,'323','337','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-08-22 18:57:07','2019-02-06 08:37:22'),(719,'d_assessment',338,'333','338','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-08-22 18:57:39','2019-02-06 08:37:22'),(720,'d_assessment',339,'324','339','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-08-22 18:58:02','2019-02-06 08:37:22'),(721,'d_assessment',340,'325','340','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-08-22 18:59:34','2019-02-06 08:37:22'),(722,'d_assessment',341,'326','341','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-08-22 19:00:50','2019-02-06 08:37:22'),(723,'d_assessment',342,'327','342','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-08-22 19:01:56','2019-02-06 08:37:22'),(724,'d_assessment',343,'328','343','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-08-22 19:02:54','2019-02-06 08:37:22'),(725,'d_assessment',344,'329','344','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-08-22 19:03:53','2019-02-06 08:37:22'),(726,'d_assessment',345,'330','345','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-08-22 19:06:28','2019-02-06 08:37:22'),(727,'d_assessment',346,'331','346','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-08-22 19:07:18','2019-02-06 08:37:22'),(728,'d_assessment',347,'332','347','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-08-22 19:08:32','2019-02-06 08:37:22'),(729,'d_user',739,'Mentor GPS PISSURLEM','mentor.gpspissurlem@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-08-22 19:30:27','2019-01-30 09:23:15'),(730,'d_assessment',348,'378','348','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-08-22 19:31:09','2019-02-06 08:37:22'),(731,'d_user',741,'Mentor G.P.S. AMBEDEM NAGARGAO','mentor.gpsambedem@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-08-22 20:37:36','2019-01-30 09:23:15'),(732,'d_assessment',349,'379','349','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-08-22 20:38:30','2019-02-06 08:37:22'),(733,'d_user',743,'Mentor GPS Nanoda Bambar','mentor.gpsnanodabambar@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-08-22 21:16:05','2019-01-30 09:23:15'),(734,'d_assessment',350,'380','350','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-08-22 21:17:19','2019-02-06 08:37:22'),(735,'d_user',745,'Mentor GPS Padeli','mentor.gpspadeli@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-08-23 10:09:56','2019-01-30 09:23:15'),(736,'d_assessment',351,'381','351','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-08-23 10:10:47','2019-02-06 08:37:22'),(737,'d_assessment',352,'43','352','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-08-23 16:27:55','2019-02-06 08:37:22'),(738,'d_assessment',353,'43','353','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-08-24 14:53:56','2019-02-06 08:37:22'),(739,'d_user',747,'Mentor GPMS Khorlim','mentor.gpmskholim@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-08-27 11:39:04','2019-01-30 09:23:15'),(740,'d_assessment',354,'382','354','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-08-27 11:40:20','2019-02-06 08:37:22'),(741,'d_assessment',355,'383','355','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-08-28 10:23:32','2019-02-06 08:37:22'),(742,'d_user',753,'Mentor GHS DONA PAULA','mentor.ghsdonapaula@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-09-03 19:11:04','2019-01-30 09:23:15'),(743,'d_user',754,'Mentor GHS Merces','mentor.ghsmerces@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-09-03 19:12:24','2019-01-30 09:23:15'),(744,'d_user',755,'Mentor GHS KIRLWADA','mentor.ghskirlwada@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-09-03 19:13:04','2019-01-30 09:23:15'),(745,'d_user',756,'Mentor GHS PALEM SIRIDAO','mentor.ghspalemsiridao@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-09-03 19:13:48','2019-01-30 09:23:15'),(746,'d_assessment',356,'384','356','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-03 19:19:04','2019-02-06 08:37:22'),(747,'d_assessment',357,'385','357','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-03 19:20:24','2019-02-06 08:37:22'),(748,'d_assessment',358,'386','358','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-03 19:21:32','2019-02-06 08:37:22'),(749,'d_assessment',359,'387','359','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-03 19:23:05','2019-02-06 08:37:22'),(750,'d_user',791,'Mentor GPS Chincholem','mentor.gpschincholem@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-09-04 11:33:56','2019-01-30 09:23:15'),(751,'d_user',792,'Mentor GPS Vodlem Bhat','mentor.gpsvodlem@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-09-04 11:35:54','2019-01-30 09:23:15'),(752,'d_user',793,'Mentor GPS Goa Velha','mentor.gpsgoavelha@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-09-04 11:37:51','2019-01-30 09:23:15'),(753,'d_user',794,'Mentor GPS Sao Paulo','mentor.gpssaopaulo@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-09-04 11:38:50','2019-01-30 09:23:15'),(754,'d_user',795,'Mentor GPS 1st Bairo St. Cruz','mentor.gpsbairo@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-09-04 11:40:15','2019-01-30 09:23:15'),(755,'d_user',796,'Mentor GPS Central Panaji','mentor.gpscentralpanaji@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-09-04 11:41:04','2019-01-30 09:23:15'),(756,'d_user',797,'Mentor GPS Ramdas Santinez','mentor.gpsramdassantinez@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-09-04 11:42:01','2019-01-30 09:23:15'),(757,'d_user',798,'Mentor GPS Santinez Tonca','mentor.gpssantinez@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-09-04 11:42:43','2019-01-30 09:23:15'),(758,'d_user',799,'Mentor GPS Gawat Kumbharjua','mentor.gpsgawat@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-09-04 11:43:24','2019-01-30 09:23:15'),(759,'d_user',800,'Mentor GPS Sulabhat','mentor.gpssulabhat@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-09-04 11:44:13','2019-01-30 09:23:15'),(760,'d_user',801,'Mentor GPS Kumbharjua','mentor.gpskumbharjua@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-09-04 11:44:57','2019-01-30 09:23:15'),(761,'d_user',802,'Mentor GPS Taligao','mentor.gpstaligao@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-09-04 11:45:35','2019-01-30 09:23:15'),(762,'d_user',803,'Mentor GPS Mandur','mentor.gpsmandur@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-09-04 11:46:24','2019-01-30 09:23:15'),(763,'d_user',804,'Mentor GPS Merces','mentor.gpsmerces@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-09-04 11:47:10','2019-01-30 09:23:15'),(764,'d_user',805,'Mentor GPS Dona Paula','mentor.gpsdonapaula@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-09-04 11:47:53','2019-01-30 09:23:15'),(765,'d_user',806,'Mentor GPS Kirlawada','mentor.gpskirlawada@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-09-04 11:48:37','2019-01-30 09:23:15'),(766,'d_user',807,'Mentor GPS Dando Pilar','mentro.gpsdandopilar@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-09-04 11:49:23','2019-01-30 09:23:15'),(767,'d_user',808,'Mentor GPS Sant Barbara','mentor.gpssantbarbara@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-09-04 11:50:15','2019-01-30 09:23:15'),(768,'d_user',809,'Mentor GPS Carambolim','mentor.gpscarambolim@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-09-04 11:50:54','2019-01-30 09:23:15'),(769,'d_user',810,'Mentor GPS Bambolim','mentor.gpsbambolim@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-09-04 11:51:38','2019-01-30 09:23:15'),(770,'d_user',811,'Mentor GPS Neura-O-Grande','mentor.gpsneura@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-09-04 11:52:24','2019-01-30 09:23:15'),(771,'d_user',812,'Mentor GPS Ramdas Panaji','mentor.gpsramdaspanaji@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-09-04 11:53:08','2019-01-30 09:23:15'),(772,'d_user',813,'Mentor GPS Dhulapicorlim','mentor.gpsdhulapicorlim@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-09-04 11:53:59','2019-01-30 09:23:15'),(773,'d_user',814,'Mentor GPS Mangado Khorlim','mentor.gpsmangado@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-09-04 11:54:45','2019-01-30 09:23:15'),(774,'d_user',815,'Mentor GPS Malar Khorlim','mentor.gpsmalar@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-09-04 11:55:36','2019-01-30 09:23:15'),(775,'d_user',816,'Mentor GPS Sao Pedro','mentor.gpssaopedro@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-09-04 11:56:18','2019-01-30 09:23:15'),(776,'d_user',817,'Mentor GPS Ela Old Goa','mentor.gpselaold@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-09-04 11:57:01','2019-01-30 09:23:15'),(777,'d_user',818,'Mentor GPS Deugi Chodan','mentor.gpsdeugichodan@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-09-04 11:57:45','2019-01-30 09:23:15'),(778,'d_user',819,'Mentor GPS Palem Siridao','mentor.gpspalemsiridao@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-09-04 11:58:33','2019-01-30 09:23:15'),(779,'d_user',820,'Mentor GPS Gavali Moula','mentor.gpsgavali@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-09-04 11:59:24','2019-01-30 09:23:15'),(780,'d_user',821,'Mentor GPS Band St. Cruz','mentor.gpsband@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-09-04 12:00:03','2019-01-30 09:23:15'),(781,'d_user',822,'Mentor GPS Deulwada St. Cruz','mentor.gpsdeulwadast@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-09-04 12:01:00','2019-01-30 09:23:15'),(782,'d_user',823,'Mentor GPS Akhada','mentor.gpsakhada@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-09-04 12:01:49','2019-01-30 09:23:15'),(783,'d_user',824,'Mentor GPS Toncawada','mentor.gpstoncawada@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-09-04 12:02:31','2019-01-30 09:23:15'),(784,'d_user',826,'Mentor GPS Apna Ghar','mentor.gpaapnaghar@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-09-04 12:06:44','2019-01-30 09:23:15'),(785,'d_assessment',360,'388','360','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-04 12:09:15','2019-02-06 08:37:22'),(786,'d_assessment',361,'389','361','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-04 12:12:32','2019-02-06 08:37:22'),(787,'d_assessment',362,'390','362','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-04 12:16:27','2019-02-06 08:37:22'),(788,'d_assessment',363,'391','363','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-04 12:17:49','2019-02-06 08:37:22'),(789,'d_assessment',364,'392','364','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-04 12:19:55','2019-02-06 08:37:22'),(790,'d_assessment',365,'393','365','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-04 12:22:35','2019-02-06 08:37:22'),(791,'d_assessment',366,'394','366','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-04 12:23:44','2019-02-06 08:37:22'),(792,'d_assessment',367,'395','367','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-04 12:24:58','2019-02-06 08:37:22'),(793,'d_assessment',368,'396','368','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-04 12:26:41','2019-02-06 08:37:22'),(794,'d_assessment',369,'397','369','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-04 12:29:49','2019-02-06 08:37:22'),(795,'d_assessment',370,'398','370','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-04 12:32:21','2019-02-06 08:37:22'),(796,'d_assessment',371,'399','371','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-04 12:47:10','2019-02-06 08:37:22'),(797,'d_assessment',372,'400','372','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-04 12:48:55','2019-02-06 08:37:22'),(798,'d_assessment',373,'401','373','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-04 12:50:05','2019-02-06 08:37:22'),(799,'d_assessment',374,'402','374','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-04 12:51:05','2019-02-06 08:37:22'),(800,'d_assessment',375,'403','375','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-04 12:52:09','2019-02-06 08:37:22'),(801,'d_assessment',376,'404','376','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-04 12:53:09','2019-02-06 08:37:22'),(802,'d_assessment',377,'405','377','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-04 12:54:02','2019-02-06 08:37:22'),(803,'d_assessment',378,'406','378','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-04 12:55:40','2019-02-06 08:37:22'),(804,'d_assessment',379,'407','379','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-04 12:56:33','2019-02-06 08:37:22'),(805,'d_assessment',380,'408','380','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-04 12:57:55','2019-02-06 08:37:22'),(806,'d_assessment',381,'409','381','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-04 12:59:18','2019-02-06 08:37:22'),(807,'d_assessment',382,'410','382','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-04 13:00:21','2019-02-06 08:37:22'),(808,'d_assessment',383,'411','383','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-04 13:01:27','2019-02-06 08:37:22'),(809,'d_assessment',384,'412','384','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-04 13:02:27','2019-02-06 08:37:22'),(810,'d_assessment',385,'413','385','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-04 14:18:56','2019-02-06 08:37:22'),(811,'d_assessment',386,'414','386','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-04 14:20:14','2019-02-06 08:37:22'),(812,'d_assessment',387,'415','387','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-04 14:21:13','2019-02-06 08:37:22'),(813,'d_assessment',388,'416','388','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-04 14:22:21','2019-02-06 08:37:22'),(814,'d_assessment',389,'417','389','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-04 14:23:15','2019-02-06 08:37:22'),(815,'d_assessment',390,'418','390','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-04 14:24:22','2019-02-06 08:37:22'),(816,'d_assessment',391,'419','391','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-04 14:25:20','2019-02-06 08:37:22'),(817,'d_assessment',392,'420','392','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-04 14:26:23','2019-02-06 08:37:22'),(818,'d_assessment',393,'421','393','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-04 14:29:02','2019-02-06 08:37:22'),(819,'d_assessment',394,'422','394','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-04 14:30:28','2019-02-06 08:37:22'),(820,'d_assessment',395,'423','395','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-12 10:41:28','2019-02-06 08:37:22'),(821,'d_assessment',396,'424','396','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-12 10:51:33','2019-02-06 08:37:22'),(822,'d_assessment',397,'425','397','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-12 10:52:55','2019-02-06 08:37:22'),(823,'d_assessment',398,'427','398','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-12 11:00:43','2019-02-06 08:37:22'),(824,'d_assessment',399,'428','399','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-12 11:02:28','2019-02-06 08:37:22'),(825,'d_assessment',400,'429','400','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-12 11:04:52','2019-02-06 08:37:22'),(826,'d_assessment',401,'431','401','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-12 11:10:30','2019-02-06 08:37:22'),(827,'d_assessment',402,'432','402','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-12 11:11:44','2019-02-06 08:37:22'),(828,'d_assessment',403,'433','403','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-12 11:18:47','2019-02-06 08:37:22'),(829,'d_assessment',404,'434','404','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-12 11:22:49','2019-02-06 08:37:22'),(830,'d_assessment',405,'435','405','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-12 11:24:57','2019-02-06 08:37:22'),(831,'d_assessment',406,'436','406','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-12 11:34:06','2019-02-06 08:37:22'),(832,'d_assessment',407,'437','407','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-12 11:40:31','2019-02-06 08:37:22'),(833,'d_assessment',408,'430','408','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-12 11:44:26','2019-02-06 08:37:22'),(834,'d_assessment',409,'426','409','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-12 11:51:20','2019-02-06 08:37:22'),(835,'d_assessment',410,'438','410','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-12 11:52:28','2019-02-06 08:37:22'),(836,'d_assessment',411,'439','411','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-12 11:54:27','2019-02-06 08:37:22'),(837,'d_assessment',412,'440','412','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-12 11:59:37','2019-02-06 08:37:22'),(838,'d_assessment',413,'441','413','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-12 12:09:39','2019-02-06 08:37:22'),(839,'d_assessment',414,'442','414','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-12 12:12:39','2019-02-06 08:37:22'),(840,'d_assessment',415,'443','415','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-12 12:14:07','2019-02-06 08:37:22'),(841,'d_assessment',416,'444','416','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-12 12:16:20','2019-02-06 08:37:22'),(842,'d_assessment',417,'445','417','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-12 12:18:19','2019-02-06 08:37:22'),(843,'d_assessment',418,'446','418','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-12 12:19:30','2019-02-06 08:37:22'),(844,'d_assessment',419,'447','419','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-12 12:44:11','2019-02-06 08:37:22'),(845,'d_assessment',420,'448','420','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-12 12:48:24','2019-02-06 08:37:22'),(846,'d_assessment',421,'449','421','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-12 12:50:58','2019-02-06 08:37:22'),(847,'d_assessment',422,'451','422','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-12 14:19:08','2019-02-06 08:37:22'),(848,'d_assessment',423,'452','423','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-12 14:24:28','2019-02-06 08:37:22'),(849,'d_assessment',424,'453','424','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-12 14:25:22','2019-02-06 08:37:22'),(850,'d_assessment',425,'454','425','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-12 14:26:13','2019-02-06 08:37:22'),(851,'d_assessment',426,'455','426','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-12 14:27:11','2019-02-06 08:37:22'),(852,'d_assessment',427,'456','427','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-12 14:28:10','2019-02-06 08:37:22'),(853,'d_assessment',428,'457','428','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-12 14:29:42','2019-02-06 08:37:22'),(854,'d_assessment',429,'458','429','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-12 14:33:48','2019-02-06 08:37:22'),(855,'d_assessment',430,'459','430','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-12 14:35:30','2019-02-06 08:37:22'),(856,'d_assessment',431,'460','431','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-12 14:37:04','2019-02-06 08:37:22'),(857,'d_assessment',432,'461','432','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-12 14:39:22','2019-02-06 08:37:22'),(858,'d_assessment',433,'462','433','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-12 14:42:10','2019-02-06 08:37:22'),(859,'d_assessment',434,'463','434','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-12 14:43:12','2019-02-06 08:37:22'),(860,'d_assessment',435,'464','435','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-12 14:44:44','2019-02-06 08:37:22'),(861,'d_assessment',436,'465','436','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-12 14:45:55','2019-02-06 08:37:22'),(862,'d_assessment',437,'466','437','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-12 14:47:27','2019-02-06 08:37:22'),(863,'d_assessment',438,'467','438','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-12 14:48:55','2019-02-06 08:37:22'),(864,'d_assessment',439,'468','439','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-12 14:51:58','2019-02-06 08:37:22'),(865,'d_assessment',440,'469','440','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-12 14:54:45','2019-02-06 08:37:22'),(866,'d_assessment',441,'470','441','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-12 14:57:13','2019-02-06 08:37:22'),(867,'d_assessment',442,'471','442','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-12 14:59:08','2019-02-06 08:37:22'),(868,'d_assessment',443,'472','443','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-12 15:02:59','2019-02-06 08:37:22'),(869,'d_assessment',444,'473','444','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-12 15:45:10','2019-02-06 08:37:22'),(870,'d_assessment',445,'474','445','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-12 15:46:01','2019-02-06 08:37:22'),(871,'d_assessment',446,'475','446','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-12 15:46:48','2019-02-06 08:37:22'),(872,'d_assessment',447,'476','447','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-12 15:47:44','2019-02-06 08:37:22'),(873,'d_assessment',448,'477','448','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-12 15:48:35','2019-02-06 08:37:22'),(874,'d_assessment',449,'478','449','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-12 15:49:23','2019-02-06 08:37:22'),(875,'d_assessment',450,'479','450','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-12 15:50:17','2019-02-06 08:37:22'),(876,'d_assessment',451,'480','451','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-12 15:51:05','2019-02-06 08:37:22'),(877,'d_assessment',452,'481','452','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-12 15:51:49','2019-02-06 08:37:22'),(878,'d_assessment',453,'482','453','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-12 15:52:47','2019-02-06 08:37:22'),(879,'d_assessment',454,'483','454','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-12 15:53:36','2019-02-06 08:37:22'),(880,'d_assessment',455,'484','455','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-12 15:54:22','2019-02-06 08:37:22'),(881,'d_assessment',456,'485','456','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-12 15:55:16','2019-02-06 08:37:22'),(882,'d_assessment',457,'486','457','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-12 15:55:58','2019-02-06 08:37:22'),(883,'d_assessment',458,'487','458','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-12 15:57:01','2019-02-06 08:37:22'),(884,'d_assessment',459,'488','459','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-12 15:59:22','2019-02-06 08:37:22'),(885,'d_assessment',460,'489','460','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-12 16:01:39','2019-02-06 08:37:22'),(886,'d_assessment',461,'490','461','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-12 16:02:31','2019-02-06 08:37:22'),(887,'d_assessment',462,'491','462','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-12 16:03:34','2019-02-06 08:37:22'),(888,'d_assessment',463,'492','463','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-12 16:04:35','2019-02-06 08:37:22'),(889,'d_assessment',464,'493','464','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-12 16:06:14','2019-02-06 08:37:22'),(890,'d_assessment',465,'494','465','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-12 16:07:10','2019-02-06 08:37:22'),(891,'d_assessment',466,'496','466','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-12 16:11:10','2019-02-06 08:37:22'),(892,'d_assessment',467,'497','467','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-12 16:12:25','2019-02-06 08:37:22'),(893,'d_assessment',468,'498','468','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-12 16:13:10','2019-02-06 08:37:22'),(894,'d_assessment',469,'499','469','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-12 16:17:15','2019-02-06 08:37:22'),(895,'d_assessment',470,'500','470','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-12 16:18:10','2019-02-06 08:37:22'),(896,'d_assessment',471,'501','471','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-12 16:19:01','2019-02-06 08:37:22'),(897,'d_assessment',472,'502','472','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-12 16:20:00','2019-02-06 08:37:22'),(898,'d_assessment',473,'503','473','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-12 16:20:42','2019-02-06 08:37:22'),(899,'d_assessment',474,'504','474','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-12 16:21:33','2019-02-06 08:37:22'),(900,'d_assessment',475,'505','475','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-12 16:22:47','2019-02-06 08:37:22'),(901,'d_assessment',476,'506','476','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-12 16:23:54','2019-02-06 08:37:22'),(902,'d_assessment',477,'507','477','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-12 16:24:46','2019-02-06 08:37:22'),(903,'d_assessment',478,'508','478','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-12 16:25:36','2019-02-06 08:37:22'),(904,'d_assessment',479,'509','479','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-12 16:26:25','2019-02-06 08:37:22'),(905,'d_assessment',480,'510','480','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-12 16:27:13','2019-02-06 08:37:22'),(906,'d_assessment',481,'511','481','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-12 16:28:30','2019-02-06 08:37:22'),(907,'d_assessment',482,'512','482','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-12 16:29:16','2019-02-06 08:37:22'),(908,'d_assessment',483,'513','483','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-12 16:30:34','2019-02-06 08:37:22'),(909,'d_assessment',484,'514','484','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-12 16:31:30','2019-02-06 08:37:22'),(910,'d_assessment',485,'515','485','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-12 16:32:18','2019-02-06 08:37:22'),(911,'d_assessment',486,'516','486','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-12 16:33:13','2019-02-06 08:37:22'),(912,'d_assessment',487,'517','487','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-12 16:34:28','2019-02-06 08:37:22'),(913,'d_assessment',488,'518','488','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-12 16:35:28','2019-02-06 08:37:22'),(914,'d_assessment',489,'519','489','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-12 16:36:16','2019-02-06 08:37:22'),(915,'d_assessment',490,'521','490','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-12 16:38:05','2019-02-06 08:37:22'),(916,'d_assessment',491,'522','491','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-12 16:39:05','2019-02-06 08:37:22'),(917,'d_assessment',492,'523','492','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-12 16:39:48','2019-02-06 08:37:22'),(918,'d_assessment',493,'524','493','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-12 16:40:48','2019-02-06 08:37:22'),(919,'d_assessment',494,'525','494','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-12 16:41:47','2019-02-06 08:37:22'),(920,'d_assessment',495,'526','495','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-12 16:42:43','2019-02-06 08:37:22'),(921,'d_assessment',496,'527','496','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-12 16:43:24','2019-02-06 08:37:22'),(922,'d_assessment',497,'528','497','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-12 16:44:24','2019-02-06 08:37:22'),(923,'d_assessment',498,'529','498','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-12 16:45:15','2019-02-06 08:37:22'),(924,'d_assessment',499,'530','499','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-12 16:46:12','2019-02-06 08:37:22'),(925,'d_assessment',500,'531','500','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-12 16:47:17','2019-02-06 08:37:22'),(926,'d_assessment',501,'532','501','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-12 16:48:15','2019-02-06 08:37:22'),(927,'d_assessment',502,'533','502','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-12 16:49:31','2019-02-06 08:37:22'),(928,'d_assessment',503,'534','503','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-12 16:50:33','2019-02-06 08:37:22'),(929,'d_assessment',504,'535','504','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-12 16:51:59','2019-02-06 08:37:22'),(930,'d_assessment',505,'495','505','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-12 16:57:18','2019-02-06 08:37:22'),(931,'d_assessment',506,'536','506','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-12 17:05:53','2019-02-06 08:37:22'),(932,'d_user',1061,'Mentor G.P.S. SATODE Ponda','mentor.gpssatodeponda@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-09-12 17:13:18','2019-01-30 09:23:15'),(933,'d_assessment',507,'537','507','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-12 17:13:59','2019-02-06 08:37:22'),(934,'d_user',1064,'Mentor G.P.S. VELGUEM','mentor.gpsvelguem@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-09-17 19:59:47','2019-01-30 09:23:15'),(935,'d_assessment',508,'172','508','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-17 20:00:20','2019-02-06 08:37:22'),(936,'d_user',1065,'Mentor G.P.S. KUDSE','mentor.gpskudse@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-09-17 20:02:04','2019-01-30 09:23:15'),(937,'d_assessment',509,'539','509','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-17 20:02:39','2019-02-06 08:37:22'),(938,'d_user',1066,'Mentor G.P.S. VALPOI - marathi','mentor.gpsvalpoimarathi@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-09-17 20:04:40','2019-01-30 09:23:15'),(939,'d_assessment',510,'200','510','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-17 20:05:24','2019-02-06 08:37:22'),(940,'d_user',1067,'Mentor G.P.S. DHANGARWADA','mentor.gpsdhandarwada@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-09-17 20:08:22','2019-01-30 09:23:15'),(941,'d_assessment',511,'163','511','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-17 20:08:53','2019-02-06 08:37:22'),(942,'d_user',1068,'Mentor G.P.S. Uste','mentor.gpsustesattari@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-09-17 20:10:16','2019-01-30 09:23:15'),(943,'d_assessment',512,'538','512','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-17 20:10:45','2019-02-06 08:37:22'),(944,'d_user',1070,'Mentor GPS Kuchelim','mentor.gpskuchelim@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-09-17 20:15:41','2019-01-30 09:23:15'),(945,'d_assessment',513,'540','513','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-17 20:16:38','2019-02-06 08:37:22'),(946,'d_assessment',514,'43','514','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-09-18 14:58:05','2019-02-06 08:37:22'),(947,'d_assessment',515,'450','515','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-05 10:26:23','2019-02-06 08:37:22'),(948,'d_user',1119,'Mentor GPS GAUTHAN','mentor.gpsgauthanpiliye@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-10-07 22:09:52','2019-01-30 09:23:15'),(949,'d_user',1120,'Mentor GPS PILIYE','mentor.gpspiliye@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-10-07 22:10:35','2019-01-30 09:23:15'),(950,'d_user',1121,'Mentor GPS DHADEWADA','mentor.gpsdhadewada@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-10-07 22:11:01','2019-01-30 09:23:15'),(951,'d_user',1122,'Mentor GPS PRATAPNAGAR','mentor.gpspratapnagarpiliye@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-10-07 22:11:24','2019-01-30 09:23:15'),(952,'d_user',1123,'Mentor GPS DAYANANDNAGAR','mentor.gpsdayanandnagar@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-10-07 22:11:50','2019-01-30 09:23:15'),(953,'d_user',1124,'Mentor GPS DHARBANDORA','mentor.gpsdharbandora@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-10-07 22:12:21','2019-01-30 09:23:15'),(954,'d_user',1125,'Mentor GPS MARAD','mentor.gpsmarad@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-10-07 22:12:48','2019-01-30 09:23:15'),(955,'d_user',1126,'Mentor GPS GURKHE','mentor.gpsgurkhe@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-10-07 22:13:16','2019-01-30 09:23:15'),(956,'d_user',1127,'GPS SHIVDE','mentor.gpashivde@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-10-07 22:13:40','2019-01-30 09:23:15'),(957,'d_user',1128,'Mentor GPS TALSAI','mentor.gpstalsai@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-10-07 22:14:05','2019-01-30 09:23:15'),(958,'d_user',1129,'Mentor GPS DAUKOND','mentor.gpsdaukond@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-10-07 22:14:29','2019-01-30 09:23:15'),(959,'d_user',1130,'Mentor GPS DHULLAI','mentor.gpsdhullai@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-10-07 22:14:59','2019-01-30 09:23:15'),(960,'d_user',1131,'GPS PALASKATTA','mentor.gpspalaskatta@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-10-07 22:15:20','2019-01-30 09:23:15'),(961,'d_user',1132,'Mentor GHS DAYANANDNAGAR','mentor.ghsdayanandnagar@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-10-07 22:15:44','2019-01-30 09:23:15'),(962,'d_user',1133,'Mentor GPS KOLSAI','mentor.gpskolsai@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-10-07 22:16:07','2019-01-30 09:23:15'),(963,'d_user',1134,'Mentor GPS CODLI TISK','mentor.gpscodlitisk@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-10-07 22:17:15','2019-01-30 09:23:15'),(964,'d_user',1135,'Mentor GPS VAGHON','mentor.gpsvaghon@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-10-07 22:17:46','2019-01-30 09:23:15'),(965,'d_user',1136,'Mentor GPS DABAL','mentor.gpsdabal@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-10-07 22:18:14','2019-01-30 09:23:15'),(966,'d_user',1137,'Mentor GPS SADGAL','mentor.gpssadgal@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-10-07 22:18:38','2019-01-30 09:23:15'),(967,'d_user',1138,'GPS SATON','mentor.gpasaton@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-10-07 22:19:02','2019-01-30 09:23:15'),(968,'d_user',1139,'GPS KAMARKHAND','mentor.gpskamarkhand@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-10-07 22:19:22','2019-01-30 09:23:15'),(969,'d_user',1140,'GPS HALULIWADA','mentor.gpshaluliwada@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-10-07 22:19:43','2019-01-30 09:23:15'),(970,'d_user',1141,'GPS KARMANE','mentor.gpskarmane@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-10-07 22:20:03','2019-01-30 09:23:15'),(971,'d_user',1142,'GPS MORKANI','mentor.gpsmorkani@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-10-07 22:20:22','2019-01-30 09:23:15'),(972,'d_user',1143,'GPS KIRLAPAL','mentor.gpskirlapal@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-10-07 22:20:41','2019-01-30 09:23:15'),(973,'d_user',1144,'GPS MOLLEM','mentor.gpsmollem@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-10-07 22:21:02','2019-01-30 09:23:15'),(974,'d_user',1145,'GPS JAMBOLI','mentor.gpsjamboli@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-10-07 22:21:22','2019-01-30 09:23:15'),(975,'d_user',1146,'GPS CASAVLI','mentor.gpscasavli@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-10-07 22:21:47','2019-01-30 09:23:15'),(976,'d_user',1147,'GPS BONDUMOL','mentor.gpsbondumol@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-10-07 22:22:20','2019-01-30 09:23:15'),(977,'d_user',1148,'GPS NAVAWADA','mentor.gpsnavawada@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-10-07 22:22:38','2019-01-30 09:23:15'),(978,'d_user',1149,'GPS COLLEM','mentor.gpscollem@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-10-07 22:22:58','2019-01-30 09:23:15'),(979,'d_user',1150,'GPS SHIGAO','mentor.gpsshigao@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-10-07 22:23:20','2019-01-30 09:23:15'),(980,'d_assessment',516,'557','516','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-07 22:26:22','2019-02-06 08:37:22'),(981,'d_assessment',517,'558','517','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-07 22:27:14','2019-02-06 08:37:22'),(982,'d_assessment',518,'567','518','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-07 22:28:07','2019-02-06 08:37:22'),(983,'d_assessment',519,'559','519','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-07 22:28:56','2019-02-06 08:37:22'),(984,'d_assessment',520,'560','520','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-07 22:29:39','2019-02-06 08:37:22'),(985,'d_assessment',521,'561','521','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-07 22:30:19','2019-02-06 08:37:22'),(986,'d_assessment',522,'562','522','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-07 22:31:07','2019-02-06 08:37:22'),(987,'d_assessment',523,'563','523','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-07 22:31:57','2019-02-06 08:37:22'),(988,'d_assessment',524,'564','524','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-07 22:32:43','2019-02-06 08:37:22'),(989,'d_assessment',525,'565','525','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-07 22:33:38','2019-02-06 08:37:22'),(990,'d_assessment',526,'566','526','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-07 22:34:32','2019-02-06 08:37:22'),(991,'d_assessment',527,'568','527','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-07 22:35:26','2019-02-06 08:37:22'),(992,'d_assessment',528,'569','528','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-07 22:36:17','2019-02-06 08:37:22'),(993,'d_assessment',529,'570','529','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-07 22:37:00','2019-02-06 08:37:22'),(994,'d_assessment',530,'571','530','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-07 22:37:51','2019-02-06 08:37:22'),(995,'d_assessment',531,'572','531','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-07 22:38:32','2019-02-06 08:37:22'),(996,'d_assessment',532,'573','532','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-07 22:39:21','2019-02-06 08:37:22'),(997,'d_assessment',533,'574','533','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-07 22:40:11','2019-02-06 08:37:22'),(998,'d_assessment',534,'575','534','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-07 22:40:55','2019-02-06 08:37:22'),(999,'d_assessment',535,'576','535','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-07 22:41:42','2019-02-06 08:37:22'),(1000,'d_assessment',536,'577','536','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-07 22:42:25','2019-02-06 08:37:22'),(1001,'d_assessment',537,'578','537','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-07 22:43:11','2019-02-06 08:37:22'),(1002,'d_assessment',538,'579','538','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-07 22:43:58','2019-02-06 08:37:22'),(1003,'d_assessment',539,'580','539','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-07 22:44:49','2019-02-06 08:37:22'),(1004,'d_assessment',540,'581','540','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-07 22:45:34','2019-02-06 08:37:22'),(1005,'d_assessment',541,'582','541','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-07 22:46:20','2019-02-06 08:37:22'),(1006,'d_assessment',542,'583','542','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-07 22:47:02','2019-02-06 08:37:22'),(1007,'d_assessment',543,'584','543','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-07 22:47:48','2019-02-06 08:37:22'),(1008,'d_assessment',544,'585','544','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-07 22:48:30','2019-02-06 08:37:22'),(1009,'d_assessment',545,'586','545','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-07 22:49:25','2019-02-06 08:37:22'),(1010,'d_assessment',546,'587','546','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-07 22:50:17','2019-02-06 08:37:22'),(1011,'d_assessment',547,'588','547','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-07 22:50:59','2019-02-06 08:37:22'),(1012,'d_user',1151,'Mentor GPS Baina No 1 A','mentor.gpsbainano1a@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-10-08 11:55:41','2019-01-30 09:23:15'),(1013,'d_user',1152,'Mentor GPS Baina No 6 A','mentor.gpsbainano6a@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-10-08 11:56:13','2019-01-30 09:23:15'),(1014,'d_user',1153,'Mentor GPS Baina No 2','mentor.gpsbainano2@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-10-08 11:56:37','2019-01-30 09:23:15'),(1015,'d_user',1154,'Mentor GPS Mangor Hill kanada A','mentor.gpsmangorhilla@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-10-08 11:57:12','2019-01-30 09:23:15'),(1016,'d_user',1155,'GHS Baina','mentor.ghsbaina@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-10-08 11:57:32','2019-01-30 09:23:15'),(1017,'d_user',1156,'Mentor GHS Mangor hill','mentor.ghsmangorhill@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-10-08 11:58:01','2019-01-30 09:23:15'),(1018,'d_user',1158,'Mentor GPS Mangor Hill Hindi Urdu','mentor.gpsmangorhillhu@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-10-08 12:00:46','2019-01-30 09:23:15'),(1019,'d_user',1159,'Mentor GPS Chicalim','mentor.gpschicalim@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-10-08 12:01:09','2019-01-30 09:23:15'),(1020,'d_user',1160,'Mentor GPS Podxiro','mentor.gpspodxiro@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-10-08 12:01:31','2019-01-30 09:23:15'),(1021,'d_user',1162,'Mentor GPMS Dabolim','mentor.gpmsdabolim@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-10-08 12:01:57','2019-01-30 09:23:15'),(1022,'d_user',1163,'Mentor GPMS Santrant','mentor.gpmasantrant@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-10-08 12:02:22','2019-01-30 09:23:15'),(1023,'d_user',1164,'Mentor GPMS Curpawado','mentor.gpmscurpawado@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-10-08 12:02:45','2019-01-30 09:23:15'),(1024,'d_user',1174,'Mentor GPS New Vaddem','mentor.gpsnewvaddem@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-10-08 13:04:50','2019-01-30 09:23:15'),(1025,'d_user',1175,'Mentor GHS Vasco Main','mentor.ghsvascomain@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-10-08 13:05:56','2019-01-30 09:23:15'),(1026,'d_user',1176,'Mentor GPS Bogda','mentor.gpsbogda@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-10-08 13:07:18','2019-01-30 09:23:15'),(1027,'d_user',1177,'Mentor GPS Vasco Main A','mentor.gpsvascomaina@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-10-08 13:08:28','2019-01-30 09:23:15'),(1028,'d_user',1178,'Mentor GPS Sada','mentor.gpssada@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-10-08 13:09:17','2019-01-30 09:23:15'),(1029,'d_user',1179,'Mentor GHS Vademnagar','mentor.ghsvademnagar@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-10-08 13:10:12','2019-01-30 09:23:15'),(1030,'d_user',1180,'Mentor GHS New Vaddem','mentor.ghsnewvaddem@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-10-08 13:14:59','2019-01-30 09:23:15'),(1031,'d_user',1181,'Mentor GHS Sada','mentor.ghssada@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-10-08 13:15:49','2019-01-30 09:23:15'),(1032,'d_user',1182,'Mentor GHS Zuarinagar','mentor.ghszuarinagar@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-10-08 13:16:33','2019-01-30 09:23:15'),(1033,'d_user',1183,'Mentor GPMS Jetty','mentor.gpmsjetty@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-10-08 13:17:05','2019-01-30 09:23:15'),(1034,'d_user',1184,'Mentor GPS Zuarinagar A','mentor.gpszuarinagar@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-10-08 13:17:54','2019-01-30 09:23:15'),(1035,'d_user',1185,'Mentor GPS Zari Zuarinagar','mentor.gpszarizuarinagar@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-10-08 13:18:33','2019-01-30 09:23:15'),(1036,'d_user',1186,'Mentor GPS Cansaulim','mentor.gpscansaulim@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-10-08 13:19:06','2019-01-30 09:23:15'),(1037,'d_user',1187,'Mentor GPS Vademnagar A','mentor.gpsvademnagara@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-10-08 13:19:53','2019-01-30 09:23:15'),(1038,'d_user',1188,'GPS HBC Vademnagar A','mentor.gpshbcvademnagara@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-10-08 13:20:25','2019-01-30 09:23:15'),(1039,'d_assessment',548,'541','548','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-08 13:26:46','2019-02-06 08:37:22'),(1040,'d_assessment',549,'542','549','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-08 14:12:18','2019-02-06 08:37:22'),(1041,'d_assessment',550,'543','550','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-08 14:15:14','2019-02-06 08:37:22'),(1042,'d_assessment',551,'544','551','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-08 14:17:37','2019-02-06 08:37:22'),(1043,'d_assessment',552,'546','552','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-08 14:21:24','2019-02-06 08:37:22'),(1044,'d_assessment',553,'547','553','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-08 14:23:34','2019-02-06 08:37:22'),(1045,'d_assessment',554,'548','554','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-08 14:25:42','2019-02-06 08:37:22'),(1046,'d_assessment',555,'549','555','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-08 14:27:30','2019-02-06 08:37:22'),(1047,'d_assessment',556,'550','556','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-08 14:29:28','2019-02-06 08:37:22'),(1048,'d_assessment',557,'545','557','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-08 14:31:37','2019-02-06 08:37:22'),(1049,'d_assessment',558,'551','558','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-08 14:33:14','2019-02-06 08:37:22'),(1050,'d_assessment',559,'552','559','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-08 14:34:44','2019-02-06 08:37:22'),(1051,'d_assessment',560,'553','560','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-08 14:36:48','2019-02-06 08:37:22'),(1052,'d_assessment',561,'554','561','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-08 14:38:06','2019-02-06 08:37:22'),(1053,'d_assessment',562,'555','562','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-08 14:40:01','2019-02-06 08:37:22'),(1054,'d_assessment',563,'556','563','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-08 14:41:31','2019-02-06 08:37:22'),(1055,'d_assessment',564,'589','564','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-08 14:43:50','2019-02-06 08:37:22'),(1056,'d_assessment',565,'590','565','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-08 14:45:25','2019-02-06 08:37:22'),(1057,'d_assessment',566,'591','566','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-08 14:47:08','2019-02-06 08:37:22'),(1058,'d_assessment',567,'592','567','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-08 14:50:53','2019-02-06 08:37:22'),(1059,'d_assessment',568,'593','568','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-08 14:52:09','2019-02-06 08:37:22'),(1060,'d_assessment',569,'594','569','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-08 14:54:13','2019-02-06 08:37:22'),(1061,'d_assessment',570,'597','570','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-08 14:55:29','2019-02-06 08:37:22'),(1062,'d_assessment',571,'595','571','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-08 14:57:09','2019-02-06 08:37:22'),(1063,'d_assessment',572,'596','572','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-08 14:59:12','2019-02-06 08:37:22'),(1064,'d_assessment',573,'598','573','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-08 15:00:47','2019-02-06 08:37:22'),(1065,'d_assessment',574,'599','574','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-08 15:05:53','2019-02-06 08:37:22'),(1066,'d_user',1208,'Mentor GPS Bharipwada','mentor.gpsbharipwada@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-10-11 12:11:31','2019-01-30 09:23:15'),(1067,'d_user',1209,'Mentor GPS Bimbol','mentor.bimbol@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-10-11 12:12:16','2019-01-30 09:23:15'),(1068,'d_user',1210,'Mentor GPS Bolkarne','mentor.gpsbolkarne@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-10-11 12:13:00','2019-01-30 09:23:15'),(1069,'d_user',1211,'Mentor GPS Bottar','mentor.gpsbottar@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-10-11 12:13:37','2019-01-30 09:23:15'),(1070,'d_user',1212,'Mentor GPS Dharge','mentor.gpsdharge@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-10-11 12:14:16','2019-01-30 09:23:15'),(1071,'d_user',1213,'Mentor GPS Kasavali','mentor.gpskasavali@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-10-11 12:15:34','2019-01-30 09:23:15'),(1072,'d_user',1214,'Mentor GPS Kumbharwada','mentor.gpskumbharwada@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-10-11 12:16:06','2019-01-30 09:23:15'),(1073,'d_user',1215,'Mentor GPS Matoje','mentor.gpsmatoje@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-10-11 12:17:03','2019-01-30 09:23:15'),(1074,'d_user',1216,'Mentor GPS Nave','mentor.gpsnave@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-10-11 12:18:07','2019-01-30 09:23:15'),(1075,'d_user',1217,'Mentor GPS Panaswada','mentor.gpspanaswada@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-10-11 12:18:57','2019-01-30 09:23:15'),(1076,'d_user',1218,'Mentor GPS Sacorda','mentor.gpssacorda@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-10-11 12:20:35','2019-01-30 09:23:15'),(1077,'d_user',1219,'Mentor GPS Satpal','mentor.gpssatpal@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-10-11 12:21:44','2019-01-30 09:23:15'),(1078,'d_user',1220,'Mentor GPS Souzamol','mentor.gpssouzamol@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-10-11 12:22:26','2019-01-30 09:23:15'),(1079,'d_user',1221,'Mentor GPS Suktali','mentor.gpssuktali@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-10-11 12:23:26','2019-01-30 09:23:15'),(1080,'d_user',1222,'Mentor GPS Surla','mentor.gpssurladharbhandora@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-10-11 12:24:12','2019-01-30 09:23:15'),(1081,'d_user',1223,'Mentor GPS Thatod','mentor.gpsthatod@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-10-11 12:25:01','2019-01-30 09:23:15'),(1082,'d_user',1224,'Mentor GPS Udhalshe','mentor.gpsudhalshe@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-10-11 12:26:05','2019-01-30 09:23:15'),(1083,'d_user',1225,'Mentor GPS Vakikulan','mentor.gpsvakikulan@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-10-11 12:26:59','2019-01-30 09:23:15'),(1084,'d_user',1226,'Mentor GPS Velipwada','mentor.gpsvelipwada@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-10-11 12:28:25','2019-01-30 09:23:15'),(1085,'d_assessment',575,'600','575','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-11 12:34:07','2019-02-06 08:37:22'),(1086,'d_assessment',576,'601','576','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-11 12:35:25','2019-02-06 08:37:22'),(1087,'d_assessment',577,'602','577','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-11 12:36:49','2019-02-06 08:37:22'),(1088,'d_assessment',578,'603','578','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-11 12:37:56','2019-02-06 08:37:22'),(1089,'d_assessment',579,'604','579','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-11 12:39:18','2019-02-06 08:37:22'),(1090,'d_assessment',580,'605','580','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-11 12:48:20','2019-02-06 08:37:22'),(1091,'d_assessment',581,'606','581','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-11 12:51:23','2019-02-06 08:37:22'),(1092,'d_assessment',582,'607','582','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-11 12:52:51','2019-02-06 08:37:22'),(1093,'d_assessment',583,'608','583','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-11 12:54:03','2019-02-06 08:37:22'),(1094,'d_assessment',584,'609','584','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-11 12:55:32','2019-02-06 08:37:22'),(1095,'d_assessment',585,'610','585','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-11 12:57:14','2019-02-06 08:37:22'),(1096,'d_assessment',586,'611','586','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-11 12:59:56','2019-02-06 08:37:22'),(1097,'d_assessment',587,'612','587','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-11 13:01:42','2019-02-06 08:37:22'),(1098,'d_assessment',588,'613','588','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-11 14:18:04','2019-02-06 08:37:22'),(1099,'d_assessment',589,'614','589','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-11 14:19:11','2019-02-06 08:37:22'),(1100,'d_assessment',590,'615','590','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-11 14:22:26','2019-02-06 08:37:22'),(1101,'d_assessment',591,'616','591','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-11 14:23:35','2019-02-06 08:37:22'),(1102,'d_assessment',592,'617','592','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-11 14:24:40','2019-02-06 08:37:22'),(1103,'d_assessment',593,'618','593','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-11 14:25:38','2019-02-06 08:37:22'),(1104,'d_user',1227,'Mentor GPS Dhat','mentor.gpsdhat1@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-10-11 18:39:51','2019-01-30 09:23:15'),(1105,'d_assessment',594,'619','594','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-11 18:41:57','2019-02-06 08:37:22'),(1106,'d_user',1252,'Mentor GPS Koop Adnem','mentor.gpskoopadnem@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-10-14 12:47:39','2019-01-30 09:23:15'),(1107,'d_user',1253,'Mentor GPS Khaddem','mentor.gpskhaddem@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-10-14 12:48:14','2019-01-30 09:23:15'),(1108,'d_user',1254,'Mentor GPS Khamamol','mentor.gpskhamamol@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-10-14 12:48:51','2019-01-30 09:23:15'),(1109,'d_user',1255,'Mentor GPS Mangal','mentor.gpsmangal@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-10-14 12:49:21','2019-01-30 09:23:15'),(1110,'d_user',1256,'Mentor GPS Bhinde','mentor.gpsbhinde@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-10-14 12:49:49','2019-01-30 09:23:15'),(1111,'d_user',1257,'Mentor GPS Curchorem','mentor.gpscurchorem@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-10-14 12:50:13','2019-01-30 09:23:15'),(1112,'d_user',1258,'Mentor GPS Soliem','mentor.gpssoliem@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-10-14 12:50:37','2019-01-30 09:23:15'),(1113,'d_user',1259,'Mentor GPS Kamral','mentor.gpskamral@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-10-14 12:51:05','2019-01-30 09:23:15'),(1114,'d_user',1260,'Mentor GPS Gaonkarwada Malkarnem','mentor.gpsgaonkarwadamalkarnem@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-10-14 12:51:34','2019-01-30 09:23:15'),(1115,'d_user',1261,'Mentor GPS Shelvon','mentor.gpsshelvon@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-10-14 12:51:59','2019-01-30 09:23:15'),(1116,'d_user',1262,'Mentor GPS Malakpan','mentor.gpsmalakpan@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-10-14 12:52:26','2019-01-30 09:23:15'),(1117,'d_user',1263,'Mentor GPS Deulamol Malkarnem','mentor.gpsdeulamolmalkarnem@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-10-14 12:52:52','2019-01-30 09:23:15'),(1118,'d_user',1264,'Mentor GPS Borimol','mentor.gpsborimol@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-10-14 12:53:18','2019-01-30 09:23:15'),(1119,'d_user',1265,'Mentor GPS Maina','mentor.gpsmainaquepem@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-10-14 12:54:02','2019-01-30 09:23:15'),(1120,'d_user',1266,'Mentor GPS Deulmol Shirvoi','mentor.gpsdeulmolshirvoi@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-10-14 12:54:26','2019-01-30 09:23:15'),(1121,'d_user',1267,'Mentor GPS Katta Amona','mentor.gpskattaamona@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-10-14 12:54:53','2019-01-30 09:23:15'),(1122,'d_user',1268,'Mentor GPS Kumbharwada Shirvoi','mentor.gpskumbharwadashirvoi@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-10-14 12:55:15','2019-01-30 09:23:15'),(1123,'d_assessment',595,'620','595','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-14 12:56:31','2019-02-06 08:37:22'),(1124,'d_assessment',596,'642','596','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-14 12:57:21','2019-02-06 08:37:22'),(1125,'d_assessment',597,'623','597','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-14 12:58:47','2019-02-06 08:37:22'),(1126,'d_assessment',598,'625','598','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-14 12:59:41','2019-02-06 08:37:22'),(1127,'d_assessment',599,'629','599','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-14 13:00:37','2019-02-06 08:37:22'),(1128,'d_assessment',600,'630','600','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-14 13:01:18','2019-02-06 08:37:22'),(1129,'d_assessment',601,'631','601','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-14 13:02:10','2019-02-06 08:37:22'),(1130,'d_assessment',602,'632','602','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-14 13:03:10','2019-02-06 08:37:22'),(1131,'d_assessment',603,'633','603','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-14 13:04:09','2019-02-06 08:37:22'),(1132,'d_assessment',604,'634','604','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-14 13:04:56','2019-02-06 08:37:22'),(1133,'d_assessment',605,'635','605','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-14 13:05:43','2019-02-06 08:37:22'),(1134,'d_assessment',606,'636','606','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-14 13:06:42','2019-02-06 08:37:22'),(1135,'d_assessment',607,'637','607','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-14 13:07:23','2019-02-06 08:37:22'),(1136,'d_assessment',608,'638','608','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-14 13:08:08','2019-02-06 08:37:22'),(1137,'d_assessment',609,'639','609','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-14 13:09:13','2019-02-06 08:37:22'),(1138,'d_assessment',610,'640','610','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-14 13:10:07','2019-02-06 08:37:22'),(1139,'d_assessment',611,'641','611','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-14 13:10:56','2019-02-06 08:37:22'),(1140,'d_assessment',612,'621','612','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-14 15:23:47','2019-02-06 08:37:22'),(1141,'d_assessment',613,'622','613','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-14 15:24:30','2019-02-06 08:37:22'),(1142,'d_assessment',614,'624','614','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-14 15:25:21','2019-02-06 08:37:22'),(1143,'d_assessment',615,'626','615','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-14 15:26:10','2019-02-06 08:37:22'),(1144,'d_assessment',616,'627','616','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-14 15:27:23','2019-02-06 08:37:22'),(1145,'d_assessment',617,'628','617','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-14 15:28:22','2019-02-06 08:37:22'),(1146,'d_assessment',618,'643','618','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-14 15:34:09','2019-02-06 08:37:22'),(1147,'d_assessment',619,'644','619','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-14 15:35:10','2019-02-06 08:37:22'),(1148,'d_assessment',620,'645','620','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-14 15:36:15','2019-02-06 08:37:22'),(1149,'d_assessment',621,'646','621','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-14 15:37:23','2019-02-06 08:37:22'),(1150,'d_assessment',622,'647','622','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-14 15:38:29','2019-02-06 08:37:22'),(1151,'d_assessment',623,'649','623','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-14 15:39:10','2019-02-06 08:37:22'),(1152,'d_assessment',624,'650','624','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-14 15:39:51','2019-02-06 08:37:22'),(1153,'d_assessment',625,'651','625','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-14 15:41:03','2019-02-06 08:37:22'),(1154,'d_assessment',626,'657','626','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-14 15:41:47','2019-02-06 08:37:22'),(1155,'d_assessment',627,'653','627','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-14 15:42:45','2019-02-06 08:37:22'),(1156,'d_assessment',628,'654','628','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-14 15:43:33','2019-02-06 08:37:22'),(1157,'d_assessment',629,'655','629','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-14 15:44:29','2019-02-06 08:37:22'),(1158,'d_assessment',630,'656','630','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-14 15:45:26','2019-02-06 08:37:22'),(1159,'d_assessment',631,'652','631','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-14 15:46:27','2019-02-06 08:37:22'),(1160,'d_assessment',632,'658','632','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-14 15:47:14','2019-02-06 08:37:22'),(1161,'d_assessment',633,'659','633','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-14 15:47:53','2019-02-06 08:37:22'),(1162,'d_assessment',634,'660','634','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-14 15:48:59','2019-02-06 08:37:22'),(1163,'d_assessment',635,'663','635','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-14 15:49:36','2019-02-06 08:37:22'),(1164,'d_assessment',636,'664','636','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-14 15:50:19','2019-02-06 08:37:22'),(1165,'d_assessment',637,'665','637','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-14 15:51:04','2019-02-06 08:37:22'),(1166,'d_assessment',638,'666','638','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-14 15:52:03','2019-02-06 08:37:22'),(1167,'d_assessment',639,'667','639','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-14 15:52:47','2019-02-06 08:37:22'),(1168,'d_assessment',640,'668','640','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-14 15:53:37','2019-02-06 08:37:22'),(1169,'d_assessment',641,'669','641','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-14 15:54:34','2019-02-06 08:37:22'),(1170,'d_assessment',642,'670','642','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-14 15:55:22','2019-02-06 08:37:22'),(1171,'d_assessment',643,'671','643','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-14 15:56:19','2019-02-06 08:37:22'),(1172,'d_assessment',644,'672','644','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-14 15:57:12','2019-02-06 08:37:22'),(1173,'d_assessment',645,'673','645','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-14 15:58:17','2019-02-06 08:37:22'),(1174,'d_assessment',646,'674','646','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-14 15:58:55','2019-02-06 08:37:22'),(1175,'d_assessment',647,'675','647','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-14 15:59:36','2019-02-06 08:37:22'),(1176,'d_assessment',648,'676','648','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-14 16:00:20','2019-02-06 08:37:22'),(1177,'d_assessment',649,'677','649','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-14 16:01:15','2019-02-06 08:37:22'),(1178,'d_assessment',650,'678','650','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-14 16:02:00','2019-02-06 08:37:22'),(1179,'d_assessment',651,'679','651','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-14 16:02:48','2019-02-06 08:37:22'),(1180,'d_assessment',652,'680','652','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-14 16:03:36','2019-02-06 08:37:22'),(1181,'d_assessment',653,'681','653','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-14 16:04:18','2019-02-06 08:37:22'),(1182,'d_assessment',654,'682','654','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-14 16:05:03','2019-02-06 08:37:22'),(1183,'d_assessment',655,'683','655','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-14 16:07:11','2019-02-06 08:37:22'),(1184,'d_assessment',656,'684','656','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-14 16:07:54','2019-02-06 08:37:22'),(1185,'d_assessment',657,'685','657','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-14 16:09:05','2019-02-06 08:37:22'),(1186,'d_assessment',658,'686','658','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-14 16:09:49','2019-02-06 08:37:22'),(1187,'d_assessment',659,'697','659','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-15 14:58:38','2019-02-06 08:37:22'),(1188,'d_assessment',660,'699','660','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-15 15:00:48','2019-02-06 08:37:22'),(1189,'d_assessment',661,'700','661','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-15 15:01:49','2019-02-06 08:37:22'),(1190,'d_assessment',662,'701','662','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-15 15:02:42','2019-02-06 08:37:22'),(1191,'d_assessment',663,'702','663','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-15 15:03:33','2019-02-06 08:37:22'),(1192,'d_assessment',664,'703','664','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-15 15:04:21','2019-02-06 08:37:22'),(1193,'d_assessment',665,'704','665','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-15 15:05:16','2019-02-06 08:37:22'),(1194,'d_assessment',666,'705','666','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-15 15:06:25','2019-02-06 08:37:22'),(1195,'d_assessment',667,'707','667','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-15 15:07:29','2019-02-06 08:37:22'),(1196,'d_assessment',668,'709','668','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-15 15:08:29','2019-02-06 08:37:22'),(1197,'d_assessment',669,'710','669','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-15 15:09:30','2019-02-06 08:37:22'),(1198,'d_assessment',670,'711','670','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-15 15:10:32','2019-02-06 08:37:22'),(1199,'d_assessment',671,'712','671','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-15 15:11:26','2019-02-06 08:37:22'),(1200,'d_assessment',672,'765','672','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-15 15:12:42','2019-02-06 08:37:22'),(1201,'d_assessment',673,'714','673','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-15 15:13:49','2019-02-06 08:37:22'),(1202,'d_assessment',674,'715','674','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-15 15:14:53','2019-02-06 08:37:22'),(1203,'d_assessment',675,'717','675','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-15 15:16:49','2019-02-06 08:37:22'),(1204,'d_assessment',676,'718','676','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-15 15:17:50','2019-02-06 08:37:22'),(1205,'d_assessment',677,'719','677','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-15 15:18:57','2019-02-06 08:37:22'),(1206,'d_assessment',678,'720','678','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-15 15:19:52','2019-02-06 08:37:22'),(1207,'d_assessment',679,'721','679','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-15 15:20:51','2019-02-06 08:37:22'),(1208,'d_assessment',680,'722','680','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-15 15:21:51','2019-02-06 08:37:22'),(1209,'d_assessment',681,'723','681','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-15 15:22:53','2019-02-06 08:37:22'),(1210,'d_assessment',682,'724','682','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-15 15:24:07','2019-02-06 08:37:22'),(1211,'d_assessment',683,'725','683','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-15 15:25:09','2019-02-06 08:37:22'),(1212,'d_assessment',684,'727','684','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-15 15:26:09','2019-02-06 08:37:22'),(1213,'d_assessment',685,'728','685','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-15 15:27:22','2019-02-06 08:37:22'),(1214,'d_assessment',686,'729','686','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-15 15:28:31','2019-02-06 08:37:22'),(1215,'d_assessment',687,'768','687','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-15 15:29:39','2019-02-06 08:37:22'),(1216,'d_assessment',688,'731','688','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-15 15:31:11','2019-02-06 08:37:22'),(1217,'d_assessment',689,'733','689','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-15 15:32:07','2019-02-06 08:37:22'),(1218,'d_assessment',690,'735','690','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-15 15:33:06','2019-02-06 08:37:22'),(1219,'d_assessment',691,'736','691','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-15 15:33:58','2019-02-06 08:37:22'),(1220,'d_assessment',692,'737','692','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-15 15:34:45','2019-02-06 08:37:22'),(1221,'d_assessment',693,'738','693','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-15 15:35:58','2019-02-06 08:37:22'),(1222,'d_assessment',694,'739','694','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-15 15:37:09','2019-02-06 08:37:22'),(1223,'d_assessment',695,'740','695','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-15 15:38:08','2019-02-06 08:37:22'),(1224,'d_assessment',696,'741','696','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-15 15:39:21','2019-02-06 08:37:22'),(1225,'d_assessment',697,'742','697','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-15 15:40:40','2019-02-06 08:37:22'),(1226,'d_assessment',698,'743','698','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-15 15:41:38','2019-02-06 08:37:22'),(1227,'d_assessment',699,'744','699','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-15 15:42:43','2019-02-06 08:37:22'),(1228,'d_assessment',700,'745','700','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-15 15:43:30','2019-02-06 08:37:22'),(1229,'d_assessment',701,'746','701','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-15 15:44:23','2019-02-06 08:37:22'),(1230,'d_assessment',702,'747','702','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-15 15:45:17','2019-02-06 08:37:22'),(1231,'d_assessment',703,'748','703','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-15 15:46:14','2019-02-06 08:37:22'),(1232,'d_user',1573,'Mentor GPS Ballimoth','mentor.gpsballimoth@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-10-16 10:07:41','2019-01-30 09:23:15'),(1233,'d_assessment',704,'662','704','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-16 10:09:11','2019-02-06 08:37:22'),(1234,'d_assessment',705,'687','705','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-16 10:10:58','2019-02-06 08:37:22'),(1235,'d_user',1574,'Mentor GPS Kakoda','mentor.gpskakoda@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-10-16 10:12:30','2019-01-30 09:23:15'),(1236,'d_assessment',706,'661','706','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-16 10:13:13','2019-02-06 08:37:22'),(1237,'d_assessment',707,'791','707','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-16 10:25:17','2019-02-06 08:37:22'),(1238,'d_assessment',708,'688','708','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-16 10:31:45','2019-02-06 08:37:22'),(1239,'d_assessment',709,'689','709','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-16 10:39:28','2019-02-06 08:37:22'),(1240,'d_assessment',710,'690','710','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-16 10:44:44','2019-02-06 08:37:22'),(1241,'d_assessment',711,'691','711','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-16 10:49:46','2019-02-06 08:37:22'),(1242,'d_assessment',712,'692','712','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-16 10:56:29','2019-02-06 08:37:22'),(1243,'d_assessment',713,'693','713','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-16 11:01:02','2019-02-06 08:37:22'),(1244,'d_assessment',714,'694','714','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-16 11:25:00','2019-02-06 08:37:22'),(1245,'d_assessment',715,'695','715','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-16 11:28:03','2019-02-06 08:37:22'),(1246,'d_assessment',716,'696','716','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-16 11:33:03','2019-02-06 08:37:22'),(1247,'d_assessment',717,'698','717','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-16 11:36:38','2019-02-06 08:37:22'),(1248,'d_assessment',718,'706','718','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-16 11:39:25','2019-02-06 08:37:22'),(1249,'d_assessment',719,'708','719','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-16 11:42:42','2019-02-06 08:37:22'),(1250,'d_assessment',720,'713','720','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-16 11:46:10','2019-02-06 08:37:22'),(1251,'d_assessment',721,'716','721','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-16 11:53:19','2019-02-06 08:37:22'),(1252,'d_assessment',722,'726','722','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-16 11:54:33','2019-02-06 08:37:22'),(1253,'d_assessment',723,'730','723','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-16 12:03:38','2019-02-06 08:37:22'),(1254,'d_assessment',724,'732','724','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-16 12:06:46','2019-02-06 08:37:22'),(1255,'d_assessment',725,'734','725','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-16 12:08:06','2019-02-06 08:37:22'),(1256,'d_assessment',726,'749','726','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-16 12:10:35','2019-02-06 08:37:22'),(1257,'d_assessment',727,'750','727','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-16 12:12:34','2019-02-06 08:37:22'),(1258,'d_assessment',728,'751','728','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-16 12:14:45','2019-02-06 08:37:22'),(1259,'d_assessment',729,'752','729','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-16 12:15:42','2019-02-06 08:37:22'),(1260,'d_assessment',730,'753','730','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-16 12:16:52','2019-02-06 08:37:22'),(1261,'d_assessment',731,'754','731','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-16 12:18:20','2019-02-06 08:37:22'),(1262,'d_assessment',732,'755','732','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-16 12:19:17','2019-02-06 08:37:22'),(1263,'d_assessment',733,'756','733','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-16 12:20:31','2019-02-06 08:37:22'),(1264,'d_assessment',734,'757','734','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-16 12:22:41','2019-02-06 08:37:22'),(1265,'d_assessment',735,'758','735','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-16 12:23:59','2019-02-06 08:37:22'),(1266,'d_assessment',736,'759','736','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-16 12:25:25','2019-02-06 08:37:22'),(1267,'d_assessment',737,'760','737','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-16 12:27:59','2019-02-06 08:37:22'),(1268,'d_assessment',738,'761','738','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-16 12:29:14','2019-02-06 08:37:22'),(1269,'d_assessment',739,'762','739','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-16 12:30:13','2019-02-06 08:37:22'),(1270,'d_assessment',740,'763','740','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-16 12:33:05','2019-02-06 08:37:22'),(1271,'d_assessment',741,'764','741','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-16 12:42:53','2019-02-06 08:37:22'),(1272,'d_assessment',742,'766','742','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-16 12:44:02','2019-02-06 08:37:22'),(1273,'d_assessment',743,'767','743','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-16 12:45:36','2019-02-06 08:37:22'),(1274,'d_assessment',744,'769','744','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-16 12:47:22','2019-02-06 08:37:22'),(1275,'d_assessment',745,'770','745','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-16 12:48:57','2019-02-06 08:37:22'),(1276,'d_assessment',746,'771','746','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-16 12:50:40','2019-02-06 08:37:22'),(1277,'d_assessment',747,'772','747','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-16 12:52:25','2019-02-06 08:37:22'),(1278,'d_assessment',748,'773','748','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-16 12:53:43','2019-02-06 08:37:22'),(1279,'d_assessment',749,'774','749','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-16 12:56:44','2019-02-06 08:37:22'),(1280,'d_assessment',750,'775','750','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-16 12:58:21','2019-02-06 08:37:22'),(1281,'d_assessment',751,'776','751','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-16 13:00:19','2019-02-06 08:37:22'),(1282,'d_assessment',752,'790','752','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-16 14:17:30','2019-02-06 08:37:22'),(1283,'d_assessment',753,'777','753','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-16 14:19:43','2019-02-06 08:37:22'),(1284,'d_assessment',754,'778','754','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-16 14:23:02','2019-02-06 08:37:22'),(1285,'d_assessment',755,'779','755','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-16 14:24:55','2019-02-06 08:37:22'),(1286,'d_assessment',756,'780','756','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-16 14:27:03','2019-02-06 08:37:22'),(1287,'d_assessment',757,'781','757','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-16 14:29:43','2019-02-06 08:37:22'),(1288,'d_assessment',758,'782','758','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-16 14:32:35','2019-02-06 08:37:22'),(1289,'d_assessment',759,'783','759','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-16 14:40:18','2019-02-06 08:37:22'),(1290,'d_assessment',760,'784','760','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-16 14:42:49','2019-02-06 08:37:22'),(1291,'d_assessment',761,'785','761','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-16 14:46:32','2019-02-06 08:37:22'),(1292,'d_assessment',762,'786','762','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-16 14:47:40','2019-02-06 08:37:22'),(1293,'d_assessment',763,'787','763','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-16 14:50:38','2019-02-06 08:37:22'),(1294,'d_assessment',764,'788','764','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-16 14:53:45','2019-02-06 08:37:22'),(1295,'d_assessment',765,'789','765','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-16 14:56:30','2019-02-06 08:37:22'),(1296,'d_assessment',766,'792','766','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-16 15:07:02','2019-02-06 08:37:22'),(1297,'d_user',1577,'Mentor GPMS Kanibag','mentor.gpmskanibag@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-10-17 14:07:56','2019-01-30 09:23:15'),(1298,'d_assessment',767,'648','767','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-17 14:08:36','2019-02-06 08:37:22'),(1299,'d_assessment',768,'793','768','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-18 12:06:56','2019-02-06 08:37:22'),(1300,'d_assessment',769,'794','769','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-18 12:09:43','2019-02-06 08:37:22'),(1301,'d_assessment',770,'795','770','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-18 12:11:15','2019-02-06 08:37:22'),(1302,'d_assessment',771,'796','771','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-18 12:12:16','2019-02-06 08:37:22'),(1303,'d_assessment',772,'797','772','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-18 12:13:27','2019-02-06 08:37:22'),(1304,'d_assessment',773,'798','773','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-18 12:15:12','2019-02-06 08:37:22'),(1305,'d_assessment',774,'799','774','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-18 12:20:28','2019-02-06 08:37:22'),(1306,'d_assessment',775,'800','775','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-18 12:21:24','2019-02-06 08:37:22'),(1307,'d_assessment',776,'801','776','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-18 12:22:28','2019-02-06 08:37:22'),(1308,'d_assessment',777,'802','777','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-18 12:23:34','2019-02-06 08:37:22'),(1309,'d_assessment',778,'803','778','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-18 12:24:31','2019-02-06 08:37:22'),(1310,'d_assessment',779,'804','779','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-18 12:25:28','2019-02-06 08:37:22'),(1311,'d_assessment',780,'805','780','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-18 12:47:43','2019-02-06 08:37:22'),(1312,'d_assessment',781,'806','781','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-18 12:49:54','2019-02-06 08:37:22'),(1313,'d_assessment',782,'807','782','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-18 12:51:14','2019-02-06 08:37:22'),(1314,'d_assessment',783,'808','783','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-18 12:53:08','2019-02-06 08:37:22'),(1315,'d_assessment',784,'809','784','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-18 14:17:14','2019-02-06 08:37:22'),(1316,'d_assessment',785,'810','785','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-18 14:26:20','2019-02-06 08:37:22'),(1317,'d_assessment',786,'811','786','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-18 14:27:44','2019-02-06 08:37:22'),(1318,'d_assessment',787,'812','787','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-18 14:30:46','2019-02-06 08:37:22'),(1319,'d_assessment',788,'813','788','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-18 14:32:11','2019-02-06 08:37:22'),(1320,'d_assessment',789,'814','789','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-18 14:34:36','2019-02-06 08:37:22'),(1321,'d_assessment',790,'861','790','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-18 14:41:20','2019-02-06 08:37:22'),(1322,'d_assessment',791,'815','791','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-18 14:43:31','2019-02-06 08:37:22'),(1323,'d_assessment',792,'816','792','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-18 14:44:25','2019-02-06 08:37:22'),(1324,'d_assessment',793,'817','793','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-18 14:47:22','2019-02-06 08:37:22'),(1325,'d_assessment',794,'818','794','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-18 14:49:02','2019-02-06 08:37:22'),(1326,'d_assessment',795,'819','795','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-18 14:50:15','2019-02-06 08:37:22'),(1327,'d_assessment',796,'820','796','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-18 14:54:58','2019-02-06 08:37:22'),(1328,'d_assessment',797,'821','797','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-18 14:56:34','2019-02-06 08:37:22'),(1329,'d_assessment',798,'822','798','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-18 14:58:38','2019-02-06 08:37:22'),(1330,'d_assessment',799,'823','799','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-18 15:00:43','2019-02-06 08:37:22'),(1331,'d_assessment',800,'824','800','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-18 15:02:43','2019-02-06 08:37:22'),(1332,'d_assessment',801,'825','801','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-18 15:04:50','2019-02-06 08:37:22'),(1333,'d_assessment',802,'826','802','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-18 15:06:00','2019-02-06 08:37:22'),(1334,'d_assessment',803,'827','803','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-18 15:07:00','2019-02-06 08:37:22'),(1335,'d_assessment',804,'828','804','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-18 15:07:56','2019-02-06 08:37:22'),(1336,'d_assessment',805,'829','805','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-18 15:09:59','2019-02-06 08:37:22'),(1337,'d_assessment',806,'830','806','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-18 15:11:23','2019-02-06 08:37:22'),(1338,'d_assessment',807,'831','807','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-18 15:14:16','2019-02-06 08:37:22'),(1339,'d_assessment',808,'832','808','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-18 15:18:08','2019-02-06 08:37:22'),(1340,'d_assessment',809,'833','809','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-18 15:19:28','2019-02-06 08:37:22'),(1341,'d_assessment',810,'834','810','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-18 15:21:42','2019-02-06 08:37:22'),(1342,'d_assessment',811,'835','811','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-18 15:23:42','2019-02-06 08:37:22'),(1343,'d_assessment',812,'836','812','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-18 15:30:26','2019-02-06 08:37:22'),(1344,'d_assessment',813,'837','813','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-18 15:32:07','2019-02-06 08:37:22'),(1345,'d_assessment',814,'838','814','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-18 15:33:33','2019-02-06 08:37:22'),(1346,'d_assessment',815,'839','815','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-18 15:34:37','2019-02-06 08:37:22'),(1347,'d_assessment',816,'840','816','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-18 15:35:58','2019-02-06 08:37:22'),(1348,'d_assessment',817,'841','817','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-18 15:37:16','2019-02-06 08:37:22'),(1349,'d_assessment',818,'842','818','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-18 15:39:01','2019-02-06 08:37:22'),(1350,'d_assessment',819,'843','819','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-18 15:41:11','2019-02-06 08:37:22'),(1351,'d_assessment',820,'844','820','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-18 15:43:12','2019-02-06 08:37:22'),(1352,'d_assessment',821,'845','821','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-18 15:59:53','2019-02-06 08:37:22'),(1353,'d_assessment',822,'846','822','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-18 16:00:45','2019-02-06 08:37:22'),(1354,'d_assessment',823,'847','823','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-18 16:02:25','2019-02-06 08:37:22'),(1355,'d_assessment',824,'848','824','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-18 16:03:39','2019-02-06 08:37:22'),(1356,'d_assessment',825,'849','825','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-18 16:05:11','2019-02-06 08:37:22'),(1357,'d_assessment',826,'850','826','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-18 16:06:03','2019-02-06 08:37:22'),(1358,'d_assessment',827,'851','827','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-18 16:08:17','2019-02-06 08:37:22'),(1359,'d_assessment',828,'852','828','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-18 16:09:54','2019-02-06 08:37:22'),(1360,'d_assessment',829,'853','829','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-18 16:11:57','2019-02-06 08:37:22'),(1361,'d_assessment',830,'854','830','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-18 16:13:04','2019-02-06 08:37:22'),(1362,'d_assessment',831,'855','831','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-18 16:13:52','2019-02-06 08:37:22'),(1363,'d_assessment',832,'856','832','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-18 16:14:43','2019-02-06 08:37:22'),(1364,'d_assessment',833,'857','833','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-18 16:18:06','2019-02-06 08:37:22'),(1365,'d_assessment',834,'858','834','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-18 16:19:29','2019-02-06 08:37:22'),(1366,'d_assessment',835,'859','835','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-18 16:20:31','2019-02-06 08:37:22'),(1367,'d_assessment',836,'860','836','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-18 16:21:54','2019-02-06 08:37:22'),(1368,'d_assessment',837,'862','837','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-18 16:24:34','2019-02-06 08:37:22'),(1369,'d_user',1721,'Mentor GHS Shigao','mentor.ghsshigao@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-10-21 15:23:23','2019-01-30 09:23:15'),(1370,'d_assessment',838,'863','838','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-21 15:25:59','2019-02-06 08:37:22'),(1371,'d_user',1723,'GHS Sadar','mentor.ghssadar@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-10-24 12:11:25','2019-01-30 09:23:15'),(1372,'d_assessment',839,'864','839','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-24 12:17:13','2019-02-06 08:37:22'),(1373,'d_user',1725,'Reviewer','mentor.ghsssadar@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-10-24 12:36:17','2019-01-30 09:23:15'),(1374,'d_assessment',840,'864','840','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-24 12:36:26','2019-02-06 08:37:22'),(1375,'d_user',1727,'Mentor GHS Kumbharwada','mentor.ghskumbharwada@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-10-24 14:29:28','2019-01-30 09:23:15'),(1376,'d_assessment',841,'865','841','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-24 14:35:20','2019-02-06 08:37:22'),(1377,'d_user',1738,'Mentor GPS Sawantwada Canacona','mentor.gpssawantwadacanacona@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-10-29 10:22:35','2019-01-30 09:23:15'),(1378,'d_assessment',842,'866','842','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-29 10:23:30','2019-02-06 08:37:22'),(1379,'d_assessment',843,'233','843','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-29 16:40:25','2019-02-06 08:37:22'),(1380,'d_user',1743,'Mentor GHS Gaval Khol','mentor.ghsgavalkhol@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-10-31 12:09:20','2019-01-30 09:23:15'),(1381,'d_assessment',844,'867','844','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-10-31 12:10:33','2019-02-06 08:37:22'),(1382,'d_assessment',845,'876','845','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-11-30 12:51:23','2019-02-06 08:37:22'),(1383,'d_assessment',846,'876','846','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-12-12 11:23:01','2019-02-06 08:37:22'),(1384,'d_user',1756,'test school','arti@123.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2018-12-14 18:19:23','2019-01-30 09:23:15'),(1385,'d_assessment',847,'40','847','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-12-17 12:21:16','2019-02-06 08:37:22'),(1386,'d_assessment',848,'310','848','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-12-17 12:40:19','2019-02-06 08:37:22'),(1387,'d_assessment',849,'40','849','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-12-17 12:47:56','2019-02-06 08:37:22'),(1388,'d_assessment',850,'310','850','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-12-17 12:52:21','2019-02-06 08:37:22'),(1389,'d_assessment',851,'470','851','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-12-17 16:39:55','2019-02-06 08:37:22'),(1390,'d_assessment',852,'470','852','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-12-17 16:40:54','2019-02-06 08:37:22'),(1391,'d_assessment',853,'310','853','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-12-17 17:12:04','2019-02-06 08:37:22'),(1392,'d_assessment',854,'310','854','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-12-18 11:47:56','2019-02-06 08:37:22'),(1393,'d_assessment',855,'43','855','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-12-18 15:03:06','2019-02-06 08:37:22'),(1394,'d_assessment',856,'815','856','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2018-12-27 12:38:57','2019-02-06 08:37:22'),(1395,'d_assessment',858,'867','858','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2019-01-03 14:36:30','2019-02-06 08:37:22'),(1396,'d_assessment',859,'472','859','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2019-01-04 12:42:59','2019-02-06 08:37:22'),(1397,'d_assessment',860,'168','860','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2019-01-04 14:23:35','2019-02-06 08:37:22'),(1398,'d_assessment',862,'471','862','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2019-01-04 18:05:44','2019-02-06 08:37:22'),(1399,'d_assessment',863,'247','863','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2019-01-04 18:29:58','2019-02-06 08:37:22'),(1400,'d_assessment',864,'460','864','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2019-01-04 18:48:42','2019-02-06 08:37:22'),(1401,'d_assessment',865,'256','865','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2019-01-08 10:35:07','2019-02-06 08:37:22'),(1402,'d_user',1763,'school','gps@school.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2019-01-09 11:30:47','2019-01-30 09:23:15'),(1403,'d_assessment',866,'470','866','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2019-01-10 11:39:56','2019-02-06 08:37:22'),(1404,'d_user',1786,'arti-test-school','ghsshi@school.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2019-01-16 16:34:27','2019-01-30 09:23:15'),(1405,'d_assessment',867,'234','867','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2019-01-21 12:35:29','2019-02-06 08:37:22'),(1406,'d_user',1790,'RK Sharma','rksharma@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2019-01-21 14:42:32','2019-01-30 09:23:15'),(1407,'d_user',1795,'ambassyschoolexternalreviewer','ambextreviewer@adhyayan.asia','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2019-01-21 15:17:44','2019-01-30 09:23:15'),(1408,'d_assessment',868,'879','868','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2019-01-21 15:18:52','2019-02-06 08:37:22'),(1409,'d_user',1796,'new test','a@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2019-01-21 16:23:19','2019-01-30 09:23:15'),(1410,'d_assessment',869,'879','869','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2019-01-21 16:42:10','2019-02-06 08:37:22'),(1411,'d_user',1797,'Testnew','testnewext@adhyayan.asia','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2019-01-21 16:44:45','2019-01-30 09:23:15'),(1412,'d_assessment',870,'878','870','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2019-01-23 15:57:29','2019-02-06 08:37:22'),(1413,'d_assessment',871,'309','871','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2019-01-24 11:57:46','2019-02-06 08:37:22'),(1414,'d_assessment',872,'883','872','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2019-01-30 11:28:17','2019-02-06 08:37:22'),(1415,'d_user',1806,'Ayushi','ayushi@gmail.com','CREATE_EXTERNAL_ASSESSOR',1,'ASSESSOR104ca6c741603eb9df0427f76311f174','2019-01-30 11:57:10','2019-01-30 09:23:15'),(1416,'d_assessment',873,'877','873','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2019-01-30 12:38:51','2019-02-06 08:37:22'),(1417,'d_assessment',874,'276','874','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2019-01-30 16:07:16','2019-02-06 08:37:22'),(1418,'d_assessment',875,'882','875','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2019-01-30 18:07:54','2019-02-06 08:37:22'),(1419,'d_assessment',876,'877','876','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2019-01-31 09:53:38','2019-02-06 08:37:22'),(1420,'d_assessment',877,'142','877','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2019-02-04 11:54:08','2019-02-06 08:37:22'),(1421,'d_user',1810,'clusterschoolextreviewer','clusterschoolextreviewer@gmail.com','CREATE_EXTERNAL_ASSESSOR',0,'','2019-02-06 10:05:36','2019-02-06 04:35:36'),(1422,'d_assessment',878,'882','878','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2019-02-06 10:07:31','2019-02-06 08:37:22'),(1423,'d_user',1811,'newschoolexternalreviewer','newschoolexternalreviewer@gmail.com','CREATE_EXTERNAL_ASSESSOR',0,'','2019-02-06 10:50:11','2019-02-06 05:20:11'),(1424,'d_assessment',879,'886','879','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2019-02-06 11:07:45','2019-02-06 08:37:22'),(1425,'d_user',1812,'ravi kant','ravi@tatrasdata.com','CREATE_EXTERNAL_ASSESSOR',0,'','2019-02-06 11:10:22','2019-02-06 05:40:22'),(1426,'d_user',1813,'Newschooltestextreviewer','newschooltestextreviewer@gmail.com','CREATE_EXTERNAL_ASSESSOR',0,'','2019-02-06 12:25:32','2019-02-06 06:55:32'),(1427,'d_assessment',880,'883','880','CREATE_REVIEW',1,'REVIEW599228c9337e7b3611ae5585379ba162','2019-02-06 12:26:33','2019-02-06 08:37:22'),(1428,'d_user',1829,'Westernschoolexternalreviewer','westernschoolexternalreviewer@gmail.com','CREATE_EXTERNAL_ASSESSOR',0,'','2019-02-12 14:27:05','2019-02-12 08:57:05'),(1429,'d_assessment',881,'892','881','CREATE_REVIEW',0,'','2019-02-12 14:35:02','2019-02-12 09:05:02'),(1430,'d_user',1832,'ravi','r@gmail.com','CREATE_EXTERNAL_ASSESSOR',0,'','2019-02-12 16:47:39','2019-02-12 11:17:39'),(1431,'d_assessment',882,'892','882','CREATE_REVIEW',0,'','2019-02-12 17:29:42','2019-02-12 11:59:42'),(1432,'d_assessment',883,'132','883','CREATE_REVIEW',0,'','2019-02-12 18:10:11','2019-02-12 12:40:11'),(1433,'d_user',1838,'Stgeorgeschoolexternalreviewer','stgeorgeschoolexternalreviewer@gmail.com','CREATE_EXTERNAL_ASSESSOR',0,'','2019-02-13 09:51:35','2019-02-13 04:21:35'),(1434,'d_user',1839,'Stgeorgeschoolexternalreviewer2','stgeorgeschoolexternalreviewer2@gmail.com','CREATE_EXTERNAL_ASSESSOR',0,'','2019-02-13 09:52:09','2019-02-13 04:22:09'),(1435,'d_assessment',884,'894','884','CREATE_REVIEW',0,'','2019-02-13 09:54:34','2019-02-13 04:24:34'),(1436,'d_user',1846,'test','test123@test.com','CREATE_EXTERNAL_ASSESSOR',0,'','2019-02-13 10:09:55','2019-02-13 04:39:55'),(1437,'d_assessment',885,'894','885','CREATE_REVIEW',0,'','2019-02-13 12:53:55','2019-02-13 07:23:55'),(1442,'d_assessment',886,'895','886','CREATE_REVIEW',0,'','2019-02-13 14:34:16','2019-02-13 09:04:16'),(1443,'d_user',1862,'test','test@ewew.com','CREATE_EXTERNAL_ASSESSOR',0,'','2019-02-13 14:35:28','2019-02-13 09:05:28'),(1444,'d_user',1873,'fdgsfdg','djvhv@gmail.com','CREATE_EXTERNAL_ASSESSOR',0,'','2019-02-15 15:47:55','2019-02-15 10:17:55'),(1445,'d_user',1874,'dfgsdg','abcde@gmail.com','CREATE_EXTERNAL_ASSESSOR',0,'','2019-02-15 15:58:08','2019-02-15 10:28:08'),(1446,'d_assessment',900,'300','900','CREATE_REVIEW',0,'','2019-02-19 14:38:34','2019-02-19 09:08:34'),(1447,'d_assessment',901,'209','901','CREATE_REVIEW',0,'','2019-02-19 14:48:41','2019-02-19 09:18:41'),(1448,'d_assessment',902,'902','902','CREATE_REVIEW',0,'','2019-02-19 15:00:25','2019-02-19 09:30:25'),(1449,'d_assessment',903,'902','903','CREATE_REVIEW',0,'','2019-02-19 15:05:18','2019-02-19 09:35:18'),(1450,'d_assessment',904,'888','904','CREATE_REVIEW',0,'','2019-02-19 15:07:21','2019-02-19 09:37:21'),(1451,'d_user',1890,'fghfgdhf','hfgdh@gmail.com','CREATE_EXTERNAL_ASSESSOR',0,'','2019-02-19 18:08:55','2019-02-19 12:38:55'),(1452,'d_assessment',905,'535','905','CREATE_REVIEW',0,'','2019-02-20 12:32:00','2019-02-20 07:02:00'),(1453,'d_user',1896,'ext1','ext@gmail.com','CREATE_EXTERNAL_ASSESSOR',0,'','2019-02-21 15:48:24','2019-02-21 10:18:24'),(1454,'d_assessment',1,'916','1','CREATE_REVIEW',0,'','2019-02-21 15:48:43','2019-02-21 10:18:43');
/*!40000 ALTER TABLE `d_alerts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `d_aqs_rounds`
--

DROP TABLE IF EXISTS `d_aqs_rounds`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `d_aqs_rounds` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `aqs_round` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `d_aqs_rounds`
--

LOCK TABLES `d_aqs_rounds` WRITE;
/*!40000 ALTER TABLE `d_aqs_rounds` DISABLE KEYS */;
INSERT INTO `d_aqs_rounds` VALUES (1,1),(3,2);
/*!40000 ALTER TABLE `d_aqs_rounds` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `d_assessment`
--

DROP TABLE IF EXISTS `d_assessment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `d_assessment` (
  `assessment_id` int(11) NOT NULL AUTO_INCREMENT,
  `client_id` int(11) NOT NULL,
  `review_criteria` varchar(255) DEFAULT NULL,
  `facilitator_id` int(11) NOT NULL,
  `diagnostic_id` int(11) NOT NULL,
  `aqsdata_id` int(11) DEFAULT NULL,
  `tier_id` int(11) DEFAULT NULL,
  `award_scheme_id` int(11) DEFAULT NULL,
  `aqs_round` int(11) NOT NULL,
  `create_date` datetime DEFAULT NULL,
  `isAssessorKeyNotesApproved` tinyint(1) DEFAULT NULL,
  `d_sub_assessment_type_id` int(11) DEFAULT '0',
  `internal_award` int(11) DEFAULT NULL,
  `external_award` int(11) DEFAULT NULL,
  `is_approved` int(1) DEFAULT '1',
  `is_uploaded` int(11) NOT NULL DEFAULT '0',
  `rejection_reason` text,
  `is_replicated` int(11) NOT NULL,
  `replicated_date_time` datetime NOT NULL,
  `language_id` int(11) NOT NULL DEFAULT '9',
  `isAssessmentActive` int(11) NOT NULL DEFAULT '1',
  `iscollebrative` int(11) NOT NULL DEFAULT '0',
  `collaborativepercntg` float(10,2) NOT NULL,
  `is_sync` int(11) NOT NULL,
  `is_offline` int(1) DEFAULT '0' COMMENT '0- is not sync with offline, 1- is sync with offline',
  `is_offline_date` datetime DEFAULT NULL,
  PRIMARY KEY (`assessment_id`),
  KEY `fk_d_assessment_d_client1_idx` (`client_id`),
  KEY `fk_d_assessment_d_diagnostic1_idx` (`diagnostic_id`),
  KEY `fk_d_assessment_d_standard1_idx` (`tier_id`),
  KEY `fk_d_assessment_award_scheme1_idx` (`award_scheme_id`),
  KEY `aqsdata_id` (`aqsdata_id`),
  KEY `fk_d_assessment_h_award_schme_idx` (`internal_award`),
  KEY `fk_d_assessment_h_award_scheme_idx1` (`external_award`),
  CONSTRAINT `d_assessment_ibfk_1` FOREIGN KEY (`award_scheme_id`) REFERENCES `d_award_scheme` (`award_scheme_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_aqsdata_assessment_1` FOREIGN KEY (`aqsdata_id`) REFERENCES `d_AQS_data` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_d_assessment_d_client1` FOREIGN KEY (`client_id`) REFERENCES `d_client` (`client_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_d_assessment_d_diagnostic1` FOREIGN KEY (`diagnostic_id`) REFERENCES `d_diagnostic` (`diagnostic_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_d_assessment_d_standard1` FOREIGN KEY (`tier_id`) REFERENCES `d_tier` (`standard_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_d_assessment_h_award_scheme` FOREIGN KEY (`internal_award`) REFERENCES `h_award_scheme` (`order`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_d_assessment_h_award_scheme1` FOREIGN KEY (`external_award`) REFERENCES `h_award_scheme` (`order`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `d_assessment`
--

LOCK TABLES `d_assessment` WRITE;
/*!40000 ALTER TABLE `d_assessment` DISABLE KEYS */;
INSERT INTO `d_assessment` VALUES (1,916,'',0,43,1,3,1,1,'2019-02-21 15:48:43',NULL,2,NULL,NULL,1,0,NULL,0,'0000-00-00 00:00:00',9,1,1,0.00,0,0,NULL);
/*!40000 ALTER TABLE `d_assessment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `d_assessment_kpa`
--

DROP TABLE IF EXISTS `d_assessment_kpa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `d_assessment_kpa` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `assessment_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `kpa_instance_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `kpa_instance_id` (`kpa_instance_id`),
  KEY `idx_d_assessment_kpa_assessment_id` (`assessment_id`),
  CONSTRAINT `d_assessment_kpa_ibfk_1` FOREIGN KEY (`kpa_instance_id`) REFERENCES `h_kpa_diagnostic` (`kpa_instance_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `d_assessment_kpa`
--

LOCK TABLES `d_assessment_kpa` WRITE;
/*!40000 ALTER TABLE `d_assessment_kpa` DISABLE KEYS */;
INSERT INTO `d_assessment_kpa` VALUES (1,1,1896,210),(2,1,1896,211),(3,1,1896,212),(4,1,1896,213),(5,1,1896,214),(6,1,1896,215),(7,1,1896,216);
/*!40000 ALTER TABLE `d_assessment_kpa` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `d_assessment_labels`
--

DROP TABLE IF EXISTS `d_assessment_labels`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `d_assessment_labels` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `label_name` varchar(255) NOT NULL,
  `label_key` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=66 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `d_assessment_labels`
--

LOCK TABLES `d_assessment_labels` WRITE;
/*!40000 ALTER TABLE `d_assessment_labels` DISABLE KEYS */;
INSERT INTO `d_assessment_labels` VALUES (1,'KEY DOMAIN','KEY_DOMAIN'),(2,'Key Question','Key_Question'),(3,'Sub Question','Sub_Question'),(4,'Good','Good'),(5,'Always','Always'),(6,'Mostly','Mostly'),(7,'Sometimes','Sometimes'),(8,'Rarely','Rarely'),(11,'Variable','Variable'),(12,'Evidence','Evidence'),(13,'Your Rating','Your_Rating'),(21,'Evidence Text','Evidence_Txt'),(22,'Key Recommendations','Key_Recommendations'),(23,'View','View'),(24,'Save','Save'),(25,'Preview','Preview'),(26,'Submit','Submit'),(27,'Comparison of Reviews across 6 Key Performance Areas ','comparision_text'),(28,'Self-Review Rating(SSRE)','self_review_rating'),(29,'External Review Rating(SERE)','external_review_rating'),(30,'Judgement Statements','Judgement_Statements'),(31,'Judgement Distance','Judgement_Distance'),(32,'Self-Review Grade for S.Q','self_review_sq'),(33,'External Review Grade for S.Q','external_review_sq'),(34,'Self-Review Grade for K.Q','self_review_kq'),(35,'External Review Grade for K.Q','external_review_kq'),(36,'KPA2','KPA2'),(37,'assessment for','assessment_for'),(38,'Always','Always_value'),(39,'Mostly','Mostly_value'),(40,'Sometimes','Sometimes_value'),(41,'Rarely','Rarely_value'),(42,' Key for reading the report','report_reading_key'),(43,'Bar graph representation of above comparison','bar_graph_representation_title'),(44,'Comparison of Reviews across 6 Key Performance Areas ','kpa_performance_area_title'),(45,'INDEX','INDEX'),(46,'SR. NO','SR_No'),(47,'PARTICULARS','PARTICULARS'),(48,'PAGE NO','PAGE_NO'),(49,'Score card for','Score_Card_KPA_Title'),(50,'Name of the Principal','Principal_Name'),(51,'Adhyayan Quality Standard Awarded','Adhyayan_Award'),(52,'ADHYAYAN REPORT CARD','Adhyayan_Report_Card_Title'),(53,'School Self-Review & Evaluation (SSRE team - School Assessors)','School_Self_Review_Evaluation_title'),(54,'School External Review & Evaluation (SERE team - Adhyayan` Assessors)','School_External_Review_Evaluation_title'),(55,'A compilation of scores based on','Compilation_Scores'),(56,'And','And'),(57,'conducted on','Conducted_On'),(58,'Valid until','Valid_Until'),(59,'Outstanding','Outstanding'),(60,'Needs Attention','Needs_Attention'),(61,'NO','No'),(62,'Level1','Level1'),(63,'Level2','Level2'),(64,'Level3','Level3'),(65,'Name of the Incharge/HM','Incharge_Name');
/*!40000 ALTER TABLE `d_assessment_labels` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `d_assessment_type`
--

DROP TABLE IF EXISTS `d_assessment_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `d_assessment_type` (
  `assessment_type_id` int(11) NOT NULL AUTO_INCREMENT,
  `assessment_type_name` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`assessment_type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `d_assessment_type`
--

LOCK TABLES `d_assessment_type` WRITE;
/*!40000 ALTER TABLE `d_assessment_type` DISABLE KEYS */;
INSERT INTO `d_assessment_type` VALUES (1,'school'),(8,'cluster'),(9,'Block');
/*!40000 ALTER TABLE `d_assessment_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `d_award`
--

DROP TABLE IF EXISTS `d_award`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `d_award` (
  `award_id` int(11) NOT NULL AUTO_INCREMENT,
  `award_name1` varchar(45) DEFAULT NULL,
  `equivalence_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`award_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `d_award`
--

LOCK TABLES `d_award` WRITE;
/*!40000 ALTER TABLE `d_award` DISABLE KEYS */;
/*!40000 ALTER TABLE `d_award` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `d_award_scheme`
--

DROP TABLE IF EXISTS `d_award_scheme`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `d_award_scheme` (
  `award_scheme_id` int(11) NOT NULL AUTO_INCREMENT,
  `award_scheme_name` varchar(45) DEFAULT NULL,
  `award_name_template` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`award_scheme_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `d_award_scheme`
--

LOCK TABLES `d_award_scheme` WRITE;
/*!40000 ALTER TABLE `d_award_scheme` DISABLE KEYS */;
INSERT INTO `d_award_scheme` VALUES (1,'Adhyayan Standard','<Tier> <Award>'),(2,'Don Bosco','<Award>');
/*!40000 ALTER TABLE `d_award_scheme` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `d_block`
--

DROP TABLE IF EXISTS `d_block`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `d_block` (
  `block_id` int(11) NOT NULL AUTO_INCREMENT,
  `block_name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`block_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `d_block`
--

LOCK TABLES `d_block` WRITE;
/*!40000 ALTER TABLE `d_block` DISABLE KEYS */;
/*!40000 ALTER TABLE `d_block` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `d_cities`
--

DROP TABLE IF EXISTS `d_cities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `d_cities` (
  `city_id` int(11) NOT NULL AUTO_INCREMENT,
  `city_name` varchar(30) NOT NULL,
  `state_id` int(11) NOT NULL,
  PRIMARY KEY (`city_id`),
  KEY `fk_d_states_id` (`state_id`),
  CONSTRAINT `fk_d_states_id` FOREIGN KEY (`state_id`) REFERENCES `d_states` (`state_id`)
) ENGINE=InnoDB AUTO_INCREMENT=75 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `d_cities`
--

LOCK TABLES `d_cities` WRITE;
/*!40000 ALTER TABLE `d_cities` DISABLE KEYS */;
INSERT INTO `d_cities` VALUES (1,'Aldona',1),(2,'Altinho',1),(3,'Aquem',1),(4,'Arpora',1),(5,'Bambolim',1),(6,'Bandora',1),(7,'Bardez',1),(8,'Benaulim',1),(9,'Betora',1),(10,'Bicholim',1),(11,'Calapor',1),(12,'Candolim',1),(13,'Caranzalem',1),(14,'Carapur',1),(15,'Chicalim',1),(16,'Chimbel',1),(17,'Chinchinim',1),(18,'Colvale',1),(19,'Corlim',1),(20,'Cortalim',1),(21,'Cuncolim',1),(22,'Curchorem',1),(23,'Curti',1),(24,'Davorlim',1),(25,'Dona Paula',1),(26,'Goa',1),(27,'Guirim',1),(28,'Jua',1),(29,'Kalangat',1),(30,'Kankon',1),(31,'Kundaim',1),(32,'Loutulim',1),(33,'Madgaon',1),(34,'Mapusa',1),(35,'Margao',1),(36,'Margaon',1),(37,'Miramar',1),(38,'Morjim',1),(39,'Mormugao',1),(40,'Navelim',1),(41,'Pale',1),(42,'Panaji',1),(43,'Parcem',1),(44,'Parra',1),(45,'Penha de Franca',1),(46,'Pernem',1),(47,'Pilerne',1),(48,'Pissurlem',1),(49,'Ponda',1),(50,'Porvorim',1),(51,'Quepem',1),(52,'Queula',1),(53,'Raia',1),(54,'Reis Magos',1),(55,'Salcette',1),(56,'Saligao',1),(57,'Sancoale',1),(58,'Sanguem',1),(59,'Sanquelim',1),(60,'Sanvordem',1),(61,'Sao Jose-de-Areal',1),(62,'Sattari',1),(63,'Serula',1),(64,'Sinquerim',1),(65,'Siolim',1),(66,'Taleigao',1),(67,'Tivim',1),(68,'Valpoi',1),(69,'Varca',1),(70,'Vasco',1),(71,'Verna',1),(72,'Tuem',1),(73,'Sulcorna',1),(74,'Canacona',1);
/*!40000 ALTER TABLE `d_cities` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `d_client`
--

DROP TABLE IF EXISTS `d_client`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `d_client` (
  `client_id` int(11) NOT NULL AUTO_INCREMENT,
  `client_institution_id` int(11) NOT NULL,
  `client_name` varchar(255) NOT NULL,
  `street` varchar(255) DEFAULT NULL,
  `addressLine2` varchar(50) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `city_id` int(11) DEFAULT NULL,
  `province` varchar(100) DEFAULT NULL,
  `state` varchar(50) DEFAULT NULL,
  `state_id` int(11) DEFAULT NULL,
  `country_id` int(11) DEFAULT NULL,
  `principal_name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `create_date` datetime DEFAULT NULL,
  `principal_phone_no` varchar(40) DEFAULT NULL,
  `remarks` text,
  `is_web` char(1) DEFAULT '0',
  `is_guest` int(11) NOT NULL,
  `client_name_user` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`client_id`),
  KEY `fk_d_states_state_id` (`state_id`),
  KEY `client_institution_id` (`client_institution_id`),
  CONSTRAINT `d_client_ibfk_1` FOREIGN KEY (`client_institution_id`) REFERENCES `d_client_institution` (`client_institution_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_d_states_state_id` FOREIGN KEY (`state_id`) REFERENCES `d_states` (`state_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=917 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `d_client`
--

LOCK TABLES `d_client` WRITE;
/*!40000 ALTER TABLE `d_client` DISABLE KEYS */;
INSERT INTO `d_client` VALUES (43,1,'Test','Delhi','',NULL,1,NULL,NULL,1,1,NULL,NULL,'2018-07-10 19:24:11','(+91)25456316','','0',0,NULL),(916,1,'test1','test','',NULL,4,NULL,NULL,1,1,NULL,NULL,'2019-02-21 15:47:24','(+91)','','0',0,NULL);
/*!40000 ALTER TABLE `d_client` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `d_client_institution`
--

DROP TABLE IF EXISTS `d_client_institution`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `d_client_institution` (
  `client_institution_id` int(11) NOT NULL AUTO_INCREMENT,
  `institution` varchar(255) NOT NULL,
  `status` int(11) NOT NULL,
  PRIMARY KEY (`client_institution_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `d_client_institution`
--

LOCK TABLES `d_client_institution` WRITE;
/*!40000 ALTER TABLE `d_client_institution` DISABLE KEYS */;
INSERT INTO `d_client_institution` VALUES (1,'School',1);
/*!40000 ALTER TABLE `d_client_institution` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `d_cluster`
--

DROP TABLE IF EXISTS `d_cluster`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `d_cluster` (
  `cluster_id` int(11) NOT NULL AUTO_INCREMENT,
  `cluster_name` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`cluster_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `d_cluster`
--

LOCK TABLES `d_cluster` WRITE;
/*!40000 ALTER TABLE `d_cluster` DISABLE KEYS */;
/*!40000 ALTER TABLE `d_cluster` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `d_core_question`
--

DROP TABLE IF EXISTS `d_core_question`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `d_core_question` (
  `core_question_id` int(11) NOT NULL AUTO_INCREMENT,
  `core_question_text1` varchar(1000) CHARACTER SET latin1 DEFAULT NULL,
  `isActive` tinyint(1) DEFAULT NULL,
  `equivalence_id` int(11) DEFAULT NULL COMMENT 'equivalence id referring h_lang_translation for translation of statements',
  PRIMARY KEY (`core_question_id`),
  KEY `fk_d_core_question_h_lang_translation_idx` (`equivalence_id`),
  CONSTRAINT `fk_d_core_question_h_lang_translation` FOREIGN KEY (`equivalence_id`) REFERENCES `h_lang_translation` (`equivalence_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `d_core_question`
--

LOCK TABLES `d_core_question` WRITE;
/*!40000 ALTER TABLE `d_core_question` DISABLE KEYS */;
INSERT INTO `d_core_question` VALUES (0,NULL,1,645);
/*!40000 ALTER TABLE `d_core_question` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `d_countries`
--

DROP TABLE IF EXISTS `d_countries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `d_countries` (
  `country_id` int(11) NOT NULL AUTO_INCREMENT,
  `country_code` varchar(3) NOT NULL,
  `country_name` varchar(150) NOT NULL,
  `phonecode` int(11) NOT NULL,
  PRIMARY KEY (`country_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `d_countries`
--

LOCK TABLES `d_countries` WRITE;
/*!40000 ALTER TABLE `d_countries` DISABLE KEYS */;
INSERT INTO `d_countries` VALUES (1,'IN','INDIA',91);
/*!40000 ALTER TABLE `d_countries` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `d_designation`
--

DROP TABLE IF EXISTS `d_designation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `d_designation` (
  `designation_id` int(11) NOT NULL AUTO_INCREMENT,
  `designation` varchar(255) NOT NULL,
  `active` int(11) NOT NULL,
  `rank` int(11) NOT NULL,
  PRIMARY KEY (`designation_id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `d_designation`
--

LOCK TABLES `d_designation` WRITE;
/*!40000 ALTER TABLE `d_designation` DISABLE KEYS */;
INSERT INTO `d_designation` VALUES (1,'Principal/HM (Head Master/Mistress)',1,1),(2,'CRP',1,2),(3,'BRP',1,3),(4,'ADEI',1,4),(5,'SMC',1,5),(17,'BRCC',1,6),(18,'Co-ordinator',1,7);
/*!40000 ALTER TABLE `d_designation` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `d_diagnostic`
--

DROP TABLE IF EXISTS `d_diagnostic`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `d_diagnostic` (
  `diagnostic_id` int(11) NOT NULL AUTO_INCREMENT,
  `name1` varchar(45) CHARACTER SET latin1 DEFAULT NULL,
  `diagnostic_image_id` int(11) DEFAULT NULL,
  `isPublished` tinyint(1) DEFAULT NULL,
  `date_created` datetime DEFAULT NULL,
  `date_published` datetime DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `assessment_type_id` int(11) NOT NULL,
  `diagnostic_type_id` int(11) NOT NULL,
  `kpa_recommendations` int(1) DEFAULT '0',
  `kq_recommendations` int(1) DEFAULT '0',
  `cq_recommendations` int(1) DEFAULT '0',
  `js_recommendations` int(1) DEFAULT '0',
  `equivalence_id` int(11) DEFAULT NULL,
  `isdefaultselfreview` int(11) NOT NULL,
  `isfreeselfreview` int(11) NOT NULL,
  `diagnostic_type` int(11) NOT NULL,
  `iscreateNet` int(11) NOT NULL,
  PRIMARY KEY (`diagnostic_id`),
  KEY `fk_d_diagnostic_d_user_idx` (`user_id`),
  KEY `fk_d_diagnostic_d_assessment_type1` (`assessment_type_id`),
  KEY `fk_d_diagnostic_image_d_file_idx` (`diagnostic_image_id`),
  KEY `fk_h_lang_translation_idx` (`equivalence_id`),
  CONSTRAINT `fk_d_diagnostic_d_assessment_type1` FOREIGN KEY (`assessment_type_id`) REFERENCES `d_assessment_type` (`assessment_type_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_d_diagnostic_d_user` FOREIGN KEY (`user_id`) REFERENCES `d_user` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_d_diagnostic_image_d_file` FOREIGN KEY (`diagnostic_image_id`) REFERENCES `d_file` (`file_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_h_lang_translation` FOREIGN KEY (`equivalence_id`) REFERENCES `h_lang_translation` (`equivalence_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `d_diagnostic`
--

LOCK TABLES `d_diagnostic` WRITE;
/*!40000 ALTER TABLE `d_diagnostic` DISABLE KEYS */;
INSERT INTO `d_diagnostic` VALUES (43,NULL,NULL,1,'2018-06-18 17:12:56','2018-06-19 16:27:32',1,1,0,1,0,0,0,670,0,0,1,0),(44,NULL,53,0,'2018-12-05 12:01:40',NULL,1,1,0,1,0,0,0,672,0,0,1,0),(45,NULL,48,0,'2018-12-05 12:05:30',NULL,1,1,0,0,0,0,0,673,0,0,1,0),(46,NULL,50,1,'2018-12-06 17:12:54','2018-12-06 17:13:37',1,1,0,1,0,0,0,674,0,0,1,0),(47,NULL,51,0,'2018-12-07 11:36:51',NULL,1,1,0,0,0,0,0,675,0,0,1,0),(48,NULL,52,0,'2018-12-07 11:49:50',NULL,1,1,0,0,0,0,0,676,0,0,1,0),(49,NULL,54,0,'2018-12-07 12:00:48',NULL,1,1,0,0,0,0,0,677,0,0,1,0),(50,NULL,NULL,0,'2019-01-10 11:17:43',NULL,1,1,0,1,0,0,0,678,0,0,1,0),(51,NULL,NULL,1,'2019-01-10 11:36:59','2019-01-10 11:38:23',1,1,0,0,0,0,0,679,0,0,1,0),(52,NULL,NULL,0,'2019-01-24 11:13:42',NULL,1,1,0,1,0,0,0,680,0,0,1,0);
/*!40000 ALTER TABLE `d_diagnostic` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `d_diagnostic_type`
--

DROP TABLE IF EXISTS `d_diagnostic_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `d_diagnostic_type` (
  `type_id` int(11) NOT NULL AUTO_INCREMENT,
  `type_text` varchar(100) NOT NULL,
  PRIMARY KEY (`type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `d_diagnostic_type`
--

LOCK TABLES `d_diagnostic_type` WRITE;
/*!40000 ALTER TABLE `d_diagnostic_type` DISABLE KEYS */;
INSERT INTO `d_diagnostic_type` VALUES (1,'New Diagnostic');
/*!40000 ALTER TABLE `d_diagnostic_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `d_equivalence`
--

DROP TABLE IF EXISTS `d_equivalence`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `d_equivalence` (
  `equivalence_id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`equivalence_id`)
) ENGINE=InnoDB AUTO_INCREMENT=681 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `d_equivalence`
--

LOCK TABLES `d_equivalence` WRITE;
/*!40000 ALTER TABLE `d_equivalence` DISABLE KEYS */;
INSERT INTO `d_equivalence` VALUES (1),(2),(3),(4),(5),(6),(7),(8),(10),(11),(12),(13),(14),(15),(16),(17),(18),(19),(20),(21),(22),(23),(24),(25),(26),(27),(28),(29),(30),(31),(32),(33),(34),(35),(36),(37),(38),(39),(40),(41),(42),(43),(44),(45),(46),(47),(48),(49),(50),(51),(52),(53),(54),(55),(56),(57),(58),(59),(60),(61),(62),(63),(64),(65),(66),(67),(68),(69),(70),(71),(72),(73),(74),(75),(76),(77),(78),(79),(80),(81),(82),(83),(84),(85),(86),(87),(88),(89),(90),(91),(92),(93),(94),(95),(96),(97),(98),(99),(100),(101),(102),(103),(104),(105),(106),(107),(108),(109),(110),(111),(112),(113),(114),(115),(116),(117),(118),(119),(120),(121),(122),(123),(124),(125),(126),(127),(128),(129),(130),(131),(132),(133),(134),(135),(136),(137),(138),(139),(140),(141),(142),(143),(144),(145),(146),(147),(148),(149),(150),(151),(152),(153),(154),(155),(156),(157),(158),(159),(160),(161),(162),(163),(164),(165),(166),(167),(168),(169),(170),(171),(172),(173),(174),(175),(176),(177),(178),(179),(180),(181),(182),(183),(184),(185),(186),(187),(188),(189),(190),(191),(192),(193),(194),(195),(196),(197),(198),(199),(200),(201),(202),(203),(204),(205),(206),(207),(208),(209),(210),(211),(212),(213),(214),(215),(216),(217),(218),(219),(220),(221),(222),(223),(224),(225),(226),(227),(228),(229),(230),(231),(232),(233),(234),(235),(236),(237),(238),(239),(240),(241),(242),(243),(244),(245),(246),(247),(248),(249),(250),(251),(252),(253),(254),(256),(257),(258),(259),(260),(261),(262),(263),(264),(265),(266),(267),(268),(269),(270),(271),(272),(273),(274),(275),(276),(277),(278),(279),(280),(330),(331),(332),(333),(334),(335),(336),(337),(338),(339),(340),(341),(342),(343),(344),(345),(346),(347),(348),(349),(350),(351),(352),(353),(354),(355),(356),(357),(358),(359),(360),(361),(362),(363),(364),(365),(366),(367),(368),(369),(370),(371),(372),(373),(374),(375),(376),(377),(378),(379),(380),(381),(382),(383),(384),(385),(386),(387),(388),(389),(390),(391),(392),(393),(394),(395),(396),(397),(398),(399),(400),(401),(402),(403),(404),(405),(406),(407),(408),(409),(410),(411),(412),(413),(414),(415),(416),(417),(418),(419),(420),(421),(422),(423),(424),(425),(426),(427),(428),(429),(430),(431),(432),(433),(434),(435),(436),(437),(438),(439),(440),(441),(442),(443),(444),(445),(446),(447),(448),(449),(450),(451),(452),(453),(454),(455),(456),(457),(458),(459),(460),(461),(462),(463),(464),(465),(466),(467),(468),(469),(470),(471),(472),(473),(474),(475),(476),(477),(478),(479),(480),(481),(482),(483),(484),(485),(486),(487),(488),(489),(490),(491),(492),(493),(494),(495),(496),(497),(498),(499),(500),(501),(502),(503),(504),(505),(506),(507),(508),(509),(510),(511),(512),(513),(514),(515),(516),(517),(518),(519),(520),(521),(522),(523),(524),(525),(526),(527),(528),(529),(530),(531),(532),(533),(534),(535),(536),(537),(538),(539),(540),(541),(542),(543),(544),(545),(546),(547),(548),(549),(550),(551),(552),(553),(554),(555),(556),(557),(558),(559),(560),(561),(562),(563),(564),(565),(566),(567),(568),(569),(570),(571),(572),(573),(574),(575),(576),(577),(578),(579),(580),(581),(582),(583),(584),(585),(586),(587),(588),(589),(590),(591),(592),(593),(594),(595),(596),(597),(598),(599),(600),(601),(602),(603),(604),(605),(606),(607),(608),(609),(610),(611),(612),(613),(614),(615),(616),(617),(618),(619),(620),(621),(622),(623),(624),(625),(626),(627),(628),(629),(630),(631),(632),(633),(634),(635),(636),(642),(645),(646),(647),(650),(651),(652),(653),(654),(655),(656),(657),(658),(659),(662),(663),(664),(665),(666),(667),(668),(669),(670),(672),(673),(674),(675),(676),(677),(678),(679),(680);
/*!40000 ALTER TABLE `d_equivalence` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `d_feedback_question`
--

DROP TABLE IF EXISTS `d_feedback_question`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `d_feedback_question` (
  `q_id` int(10) NOT NULL AUTO_INCREMENT,
  `question_name` text NOT NULL,
  `field_type` varchar(255) NOT NULL,
  `field_name` varchar(255) NOT NULL,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `user_type` varchar(255) NOT NULL DEFAULT 'peer',
  `rank` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`q_id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `d_feedback_question`
--

LOCK TABLES `d_feedback_question` WRITE;
/*!40000 ALTER TABLE `d_feedback_question` DISABLE KEYS */;
INSERT INTO `d_feedback_question` VALUES (1,'Please provide any additional comments about the Assessor\'s strengths','area','assessor_strength',0,'peer',3),(2,'What are the key areas of development for the Assessor? ','area','key_area',0,'peer',4),(3,'What was the level of the Assessor\'s contribution in the following areas: ','','',0,'peer',0),(4,'Effective completion of their responsibilities','radio','completion_responsibilities',3,'peer',0),(5,'Understanding of the AQS Diagnostic statements','radio','diagnostic_statements',3,'peer',0),(6,'Objective evidence collection','radio','objective_evidence',3,'peer',0),(7,'Planning & facilitation of the Quality Dialogue and/or Action Planning sessions','radio','quality_dialogue',3,'peer',0),(8,'Team work','radio','team_work',3,'peer',0),(9,'Accuracy in \'Coming to Judgement\' using evidence','radio','judgement',3,'peer',0),(10,'How did you support your SERE team members in their growth & learning? Please provide examples','area','support_growth_learning',0,'lead',0),(11,'What do your interactions with the school tell you about the school & its leadership?','area','school_interaction_exp',0,'lead',0),(12,'Share your reflections on how you led elements of the External Review, Quality Dialogue & Action Planning days','area','reflections',0,'associate',0),(13,'What should the school focus on first in order to sustain their school improvement journey?','area','school_improvement_journey',0,'associate',0),(14,'What did you learn about evidence collection & what were your contributions to the SERE team in coming to judgement?','area','your_contributions',0,'intern',0),(15,'Describe the SSRE team\'s level of engagement during the Quality Dialogue & Action Planning sessions.','area','team_engagement_level',0,'intern',0),(16,'Share your reflections on how you collected evidence using the 4 methods of evidence collection.','area','apprentice_share_reflection',0,'apprentice',0),(17,'What was the workshop flow of the Quality Dialogue & Action Planning day(s)?','area','apprentice_workshop_flow',0,'apprentice',0),(18,'What have been your key learnings as a school leader and how will these impact you & your School/Organization?','area','all_key_learnings_school',0,'all_accessors',1),(19,'What have been your key learnings as an Assessor & how will these impact your growth in The Assessor Programme? ','area','all_key_learnings_assessor',0,'all_accessors',2);
/*!40000 ALTER TABLE `d_feedback_question` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `d_feedback_submit`
--

DROP TABLE IF EXISTS `d_feedback_submit`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `d_feedback_submit` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `assessment_id` int(11) NOT NULL,
  `is_submit` int(11) NOT NULL,
  `type` int(11) NOT NULL COMMENT '1=peer,2=self',
  `feedback_status` int(11) NOT NULL DEFAULT '0',
  `submit_date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `d_feedback_submit`
--

LOCK TABLES `d_feedback_submit` WRITE;
/*!40000 ALTER TABLE `d_feedback_submit` DISABLE KEYS */;
INSERT INTO `d_feedback_submit` VALUES (1,11,6,1,1,1,'0000-00-00 00:00:00'),(2,11,6,1,2,1,'0000-00-00 00:00:00'),(3,10,6,1,1,1,'0000-00-00 00:00:00'),(4,10,6,1,2,1,'0000-00-00 00:00:00'),(5,13,9,1,2,1,'0000-00-00 00:00:00'),(6,13,9,1,1,1,'0000-00-00 00:00:00'),(7,11,9,1,2,1,'0000-00-00 00:00:00'),(8,11,9,1,1,1,'0000-00-00 00:00:00'),(9,10,9,1,2,1,'0000-00-00 00:00:00'),(10,10,9,1,1,1,'0000-00-00 00:00:00'),(11,22,9,1,2,1,'0000-00-00 00:00:00'),(12,22,9,1,1,1,'0000-00-00 00:00:00'),(13,16,9,1,1,1,'0000-00-00 00:00:00'),(14,16,9,1,2,1,'0000-00-00 00:00:00'),(15,18,8,1,1,1,'0000-00-00 00:00:00'),(16,19,8,1,2,1,'0000-00-00 00:00:00'),(17,19,8,1,1,1,'0000-00-00 00:00:00'),(18,21,8,1,2,1,'0000-00-00 00:00:00'),(19,21,8,1,1,1,'0000-00-00 00:00:00'),(20,18,8,1,2,1,'0000-00-00 00:00:00');
/*!40000 ALTER TABLE `d_feedback_submit` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `d_file`
--

DROP TABLE IF EXISTS `d_file`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `d_file` (
  `file_id` int(11) NOT NULL AUTO_INCREMENT,
  `file_name` varchar(60) DEFAULT NULL,
  `file_size` text NOT NULL,
  `uploaded_by` int(11) NOT NULL,
  `upload_date` datetime NOT NULL,
  PRIMARY KEY (`file_id`),
  KEY `uploaded_by` (`uploaded_by`)
) ENGINE=InnoDB AUTO_INCREMENT=114 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `d_file`
--

LOCK TABLES `d_file` WRITE;
/*!40000 ALTER TABLE `d_file` DISABLE KEYS */;
INSERT INTO `d_file` VALUES (1,'green_2145_1529574602.jpeg','0',1,'2018-06-21 15:20:02'),(2,'download_(10)_8334_1529576834.jpg','0',1,'2018-06-21 15:56:15'),(3,'images_(4)_9118_1529577869.jpg','0',1,'2018-06-21 16:14:29'),(4,'green_9983_1530111931.jpeg','0',11,'2018-06-27 20:35:31'),(5,'green_6379_1530113189.jpeg','0',11,'2018-06-27 20:56:29'),(6,'green_8376_1530113861.jpeg','0',11,'2018-06-27 21:07:42'),(7,'logo_127_1530184430.png','0',1,'2018-06-28 11:59:21'),(8,'CtJ_mostly_new_3043_1530175107.xlsx','0',11,'2018-06-28 14:08:27'),(9,'blue_5307_1530179718.png','0',1,'2018-06-28 14:12:53'),(10,'action_7062_1530176628.png','0',13,'2018-06-28 14:33:48'),(11,'KeyDomain_8142_1530176636.xlsx','0',13,'2018-06-28 14:33:56'),(12,'image_1502_1530336675.jpg','0',1,'2018-06-30 11:01:15'),(13,'image_6069_1530336684.jpg','0',1,'2018-06-30 11:01:24'),(14,'image_9735_1530348263.jpg','0',1,'2018-06-30 14:14:23'),(15,'image_4750_1530352723.jpg','0',18,'2018-06-30 15:28:43'),(16,'assignment-name-in-brief_f9e7b986-7_5226_1530352734.jpg','0',18,'2018-06-30 15:28:54'),(17,'michael-schumacher-ferrari-comeback_4743_1530352738.jpg','0',18,'2018-06-30 15:28:58'),(18,'maxresdefault1_984_1530352741.jpg','0',18,'2018-06-30 15:29:01'),(19,'yogitrivendra_1038_1530352744.jpg','0',18,'2018-06-30 15:29:04'),(20,'azamcow_4768_1530352747.jpg','0',18,'2018-06-30 15:29:07'),(21,'images_6_2278_1530352750.jpg','0',18,'2018-06-30 15:29:10'),(22,'download_15_9677_1530352754.jpg','0',18,'2018-06-30 15:29:14'),(23,'maxresdefault_2771_1530352758.jpg','0',18,'2018-06-30 15:29:18'),(24,'dp_1655_1530764568.jpg','0',56,'2018-07-05 09:52:48'),(25,'Screen_Shot_2015-01-18_at_1_06_06_p_2496_1530765424.png','0',56,'2018-07-05 10:07:04'),(26,'wall_paper_5_6528_1530773722.jpg','0',56,'2018-07-05 12:25:22'),(27,'dp_266_1530775728.jpg','0',56,'2018-07-05 12:58:49'),(28,'dp_5446_1530777987.jpg','0',56,'2018-07-05 13:36:27'),(29,'agile_9371_1530778121.png','0',56,'2018-07-05 13:38:41'),(30,'Batch_report_6157_1530801225.docx','0',56,'2018-07-05 20:03:45'),(31,'Draft_data_for_Shaala_Siddhi_5608_1530850394.pdf','0',56,'2018-07-06 09:43:15'),(32,'IMG-20180627-WA0001_8098_1530850482.jpg','0',41,'2018-07-06 09:44:42'),(33,'error_message_2289_1531390510.png','0',54,'2018-07-12 15:45:11'),(34,'logo_4546_1531391828.png','0',1,'2018-07-12 16:07:08'),(35,'evidence_file_repeating_9549_1531391835.png','0',1,'2018-07-12 16:07:15'),(36,'Adhyayan_2406_1531392144.png','0',1,'2018-07-12 16:12:24'),(37,'Action_planning_changes_1_6013_1531392150.docx','0',1,'2018-07-12 16:12:30'),(38,'Action_planning_changes_1_648_1531392195.docx','0',1,'2018-07-12 16:13:15'),(39,'KeyDomain_9468_1531392200.xlsx','0',1,'2018-07-12 16:13:20'),(40,'Screenshot_from_2018-05-16_10-54-23_1995_1531399859.png','0',1,'2018-07-12 18:20:59'),(41,'Screenshot_from_2018-05-16_10-54-23_4076_1531399950.png','0',1,'2018-07-12 18:22:30'),(42,'Screenshot_from_2018-05-16_10-54-23_9563_1531400392.png','0',1,'2018-07-12 18:29:52'),(43,'logo_4494_1531408750.png','0',56,'2018-07-12 20:49:10'),(44,'2018-05-17_3669_1536302970.png','0',1,'2018-09-07 12:19:30'),(45,'2018-05-17_5718_1536303361.png','0',1,'2018-09-07 12:26:01'),(46,'Block_level_format_134_1536303416.xlsx','0',1,'2018-09-07 12:26:56'),(47,'43360694_2236246323084777_475067167_468_1541065489.jpg','0',1,'2018-11-01 15:14:49'),(48,'Screenshot_from_2018-11-27_14-17-48_1531_1546939529.png','0',1,'2018-12-05 12:05:51'),(49,'dropdown_4176_1543993292.png','0',1,'2018-12-05 12:31:32'),(50,'201a5889a4727fa5d8fb3c02592ef2cd_7169_1544096574.jpg','0',1,'2018-12-06 17:12:54'),(51,'status-down_812_1545642806.png','0',1,'2018-12-07 11:36:51'),(52,'Screenshot_from_2018-11-27_14-17-48_2662_1546932277.png','0',1,'2018-12-07 11:49:50'),(53,'Screenshot_from_2018-11-27_14-17-48_4573_1546932424.png','0',1,'2018-12-07 11:53:25'),(54,'Screenshot_from_2018-11-27_14-17-48_6042_1546939028.png','0',1,'2018-12-07 12:00:48'),(55,'2018-11-21_11_39_01-Window_2331_1545029147.png','0',56,'2018-12-17 12:15:47'),(56,'vtip_arrow_3165_1545388152.png','0',1,'2018-12-21 15:59:12'),(57,'tick3_4055_1545388194.png','0',1,'2018-12-21 15:59:54'),(58,'vtip_arrow_2619_1545389497.png','0',1,'2018-12-21 16:21:37'),(59,'tick3_289_1545389720.png','0',1,'2018-12-21 16:25:20'),(60,'tick1_9632_1545390052.png','0',1,'2018-12-21 16:30:57'),(61,'tick3_4555_1545390151.png','0',1,'2018-12-21 16:32:31'),(62,'vtip_arrow_9494_1545390541.png','0',1,'2018-12-21 16:39:01'),(63,'tab3left_7279_1545390567.png','0',1,'2018-12-21 16:39:28'),(64,'tick_9489_1545390665.png','0',1,'2018-12-21 16:41:05'),(65,'whitebg_9758_1545394553.jpg','0',1,'2018-12-21 17:45:53'),(66,'status-up_934_1545394669.png','0',1,'2018-12-21 17:47:50'),(67,'not-count_3701_1545394677.png','0',1,'2018-12-21 17:47:57'),(68,'tab1right_6029_1545397708.jpg','0',1,'2018-12-21 18:38:28'),(69,'arrow_4516_1545657240.png','0',1,'2018-12-24 18:44:00'),(70,'tick2_4432_1545975393.png','0',1,'2018-12-28 11:06:33'),(71,'tab2right_5997_1545997938.jpg','0',1,'2018-12-28 17:22:23'),(72,'Screenshot_from_2018-11-27_14-17-48_6647_1546867995.png','0',1,'2019-01-07 19:03:15'),(73,'stu_count_7697_1546868107.png','0',1,'2019-01-07 19:05:07'),(74,'Screenshot_from_2018-11-27_14-17-48_924_1546924573.png','0',1,'2019-01-08 10:46:14'),(75,'Screenshot_from_2018-11-27_14-17-48_5268_1546939448.png','0',1,'2019-01-08 14:54:13'),(76,'Screenshot_from_2018-11-27_14-17-48_4408_1546939486.png','0',1,'2019-01-08 14:54:57'),(77,'Screenshot_from_2018-11-27_14-17-48_6533_1546939655.png','0',1,'2019-01-08 14:57:36'),(78,'Screenshot_from_2018-11-27_14-17-48_8100_1546939769.png','0',1,'2019-01-08 14:59:30'),(79,'Screenshot_from_2018-11-27_14-17-48_4325_1546939839.png','0',1,'2019-01-08 15:00:40'),(80,'Screenshot_from_2018-11-27_14-17-48_8051_1546939898.png','0',1,'2019-01-08 15:01:39'),(81,'Screenshot_from_2018-11-27_14-17-48_8209_1546940501.png','0',1,'2019-01-08 15:11:41'),(82,'Screenshot_from_2018-11-27_14-17-48_9392_1546940854.png','0',1,'2019-01-08 15:17:35'),(83,'Screenshot_from_2018-11-27_14-17-48_3856_1546941125.png','0',1,'2019-01-08 15:22:05'),(84,'Career_Readiness_Key_definitions_7654_1546941775.png','0',1,'2019-01-08 15:32:56'),(85,'2018-05-17_604_1546948920.png','0',1,'2019-01-08 17:32:00'),(86,'Screenshot_from_2018-11-27_14-17-48_74_1546949145.png','0',1,'2019-01-08 17:35:45'),(87,'my_tasks_8476_1548074075.png','0',1795,'2019-01-21 18:04:35'),(88,'Untitled_3139_1548150864.png','0',1,'2019-01-22 15:24:24'),(89,'Untitled_5729_1548152586.png','0',1,'2019-01-22 15:53:07'),(90,'Untitled_4463_1548152716.png','0',1,'2019-01-22 15:55:16'),(91,'image-2018-10-08-19-33-56-250_3445_1548908881.png','0',594,'2019-01-31 09:58:01'),(92,'adhyayan-app-logo-2732_1868_1549956552.png','0',595,'2019-02-12 12:59:12'),(93,'image-2018-10-08-19-33-56-250_222_1549968288.png','0',1829,'2019-02-12 16:14:48'),(94,'List_Of_Test_Users_61_1549968305.txt','0',1829,'2019-02-12 16:15:05'),(95,'image-2018-10-08-19-33-56-250_3043_1549968313.png','0',1829,'2019-02-12 16:15:13'),(96,'image-2018-10-08-19-33-56-250_7218_1549968513.png','0',1829,'2019-02-12 16:18:33'),(97,'List_Of_Test_Users_2086_1549968520.txt','0',1829,'2019-02-12 16:18:40'),(98,'image-2018-10-08-19-33-56-250_6790_1549968633.png','0',1829,'2019-02-12 16:20:33'),(99,'image-2018-10-08-19-33-56-250_6553_1549969451.png','0',1829,'2019-02-12 16:34:11'),(100,'image-2018-10-08-19-33-56-250_7053_1549969741.png','0',1795,'2019-02-12 16:39:01'),(101,'List_Of_Test_Users_2713_1549969750.txt','0',1795,'2019-02-12 16:39:11'),(102,'image-2018-10-08-19-33-56-250_2225_1549973447.png','0',1829,'2019-02-12 17:40:47'),(103,'image-2018-10-08-19-33-56-250_5840_1549974194.png','0',1795,'2019-02-12 17:53:14'),(104,'arrow2_6349_1549979804.png','0',1785,'2019-02-12 19:26:44'),(105,'antarang_4131_1549979833.jpg','0',1785,'2019-02-12 19:27:13'),(106,'action-plan_screenshots_6623_1550032537.pdf','0',1785,'2019-02-13 10:05:37'),(107,'Screenshot_20181213-151046_1_9165_1550032578.jpg','0',1785,'2019-02-13 10:06:18'),(108,'Screenshot_20181213-151046_2_314_1550032601.jpg','0',1785,'2019-02-13 10:06:41'),(109,'favicon_5550_1550032708.png','0',595,'2019-02-13 10:08:28'),(110,'image-2018-10-08-19-33-56-250_4020_1550035063.png','0',1838,'2019-02-13 10:47:43'),(111,'2018-05-17_1951_1550646166.png','0',56,'2019-02-20 12:32:46'),(112,'2018-05-17_424_1550646420.png','0',1058,'2019-02-20 12:37:01'),(113,'2018-05-17_3579_1550647581.png','0',1785,'2019-02-20 12:56:21');
/*!40000 ALTER TABLE `d_file` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `d_filter`
--

DROP TABLE IF EXISTS `d_filter`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `d_filter` (
  `filter_id` int(11) NOT NULL AUTO_INCREMENT,
  `filter_name` varchar(45) NOT NULL,
  `added_by` int(11) NOT NULL,
  `create_date` datetime NOT NULL,
  PRIMARY KEY (`filter_id`),
  KEY `fk_d_user_id_idx` (`added_by`),
  CONSTRAINT `fk_d_user_id` FOREIGN KEY (`added_by`) REFERENCES `d_user` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `d_filter`
--

LOCK TABLES `d_filter` WRITE;
/*!40000 ALTER TABLE `d_filter` DISABLE KEYS */;
/*!40000 ALTER TABLE `d_filter` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `d_frequency`
--

DROP TABLE IF EXISTS `d_frequency`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `d_frequency` (
  `frequency_id` int(11) NOT NULL AUTO_INCREMENT,
  `frequecy_text` varchar(100) NOT NULL,
  `frequency_days` varchar(100) NOT NULL,
  PRIMARY KEY (`frequency_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `d_frequency`
--

LOCK TABLES `d_frequency` WRITE;
/*!40000 ALTER TABLE `d_frequency` DISABLE KEYS */;
INSERT INTO `d_frequency` VALUES (1,'Weekly','+1 week'),(2,'Fortnightly','+14 days'),(3,'Monthly','+1 month'),(4,'Quarterly','+3 month'),(5,'Half yearly','+6 month'),(6,'Annually','+1 year');
/*!40000 ALTER TABLE `d_frequency` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `d_good_look_like_statement`
--

DROP TABLE IF EXISTS `d_good_look_like_statement`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `d_good_look_like_statement` (
  `good_looks_like_statement_id` int(11) NOT NULL AUTO_INCREMENT,
  `isActive` tinyint(1) DEFAULT NULL,
  `equivalence_id` int(11) DEFAULT NULL COMMENT 'equivalence id referring h_lang_translation for translation of statements',
  PRIMARY KEY (`good_looks_like_statement_id`),
  KEY `fk_d_good_look_like_statement_h_lang_translation_idx` (`equivalence_id`),
  CONSTRAINT `fk_d_good_look_like_statement_h_lang_translation` FOREIGN KEY (`equivalence_id`) REFERENCES `h_lang_translation` (`equivalence_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=59 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `d_good_look_like_statement`
--

LOCK TABLES `d_good_look_like_statement` WRITE;
/*!40000 ALTER TABLE `d_good_look_like_statement` DISABLE KEYS */;
INSERT INTO `d_good_look_like_statement` VALUES (1,1,572),(2,1,573),(3,1,574),(4,1,575),(5,1,576),(6,1,577),(7,1,578),(8,1,579),(9,1,580),(10,1,581),(11,1,582),(12,1,583),(13,1,584),(14,1,585),(15,1,586),(16,1,587),(17,1,588),(18,1,589),(19,1,590),(20,1,591),(21,1,592),(22,1,593),(23,1,594),(24,1,595),(25,1,596),(26,1,597),(27,1,598),(28,1,599),(29,1,600),(30,1,601),(31,1,602),(32,1,603),(33,1,604),(34,1,605),(35,1,606),(36,1,607),(37,1,608),(38,1,609),(39,1,610),(40,1,611),(41,1,612),(42,1,613),(43,1,614),(44,1,615),(45,1,616),(46,1,617),(47,1,618),(48,1,619),(49,1,620),(50,1,621),(51,1,622),(52,1,623),(53,1,624),(54,1,625),(55,1,626),(56,1,627),(57,1,628),(58,1,629);
/*!40000 ALTER TABLE `d_good_look_like_statement` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `d_group_assessment`
--

DROP TABLE IF EXISTS `d_group_assessment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `d_group_assessment` (
  `group_assessment_id` int(11) NOT NULL AUTO_INCREMENT,
  `assessment_type_id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `admin_user_id` int(11) DEFAULT NULL,
  `group_assessment_name` varchar(45) DEFAULT NULL,
  `student_review_type_id` int(11) NOT NULL,
  `student_review_form_id` int(11) NOT NULL,
  `grp_aqsdata_id` int(11) DEFAULT NULL,
  `student_round` int(11) NOT NULL,
  `creation_date` datetime DEFAULT NULL,
  `isGroupAssessmentActive` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`group_assessment_id`),
  KEY `fk_d_group_assessment_d_assessment_type1_idx` (`assessment_type_id`),
  KEY `fk_d_group_assessment_d_user1_idx` (`admin_user_id`),
  KEY `client_id` (`client_id`),
  CONSTRAINT `d_group_assessment_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `d_client` (`client_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_d_group_assessment_d_assessment_type1` FOREIGN KEY (`assessment_type_id`) REFERENCES `d_assessment_type` (`assessment_type_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_d_group_assessment_d_user1` FOREIGN KEY (`admin_user_id`) REFERENCES `d_user` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `d_group_assessment`
--

LOCK TABLES `d_group_assessment` WRITE;
/*!40000 ALTER TABLE `d_group_assessment` DISABLE KEYS */;
/*!40000 ALTER TABLE `d_group_assessment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `d_impact_method`
--

DROP TABLE IF EXISTS `d_impact_method`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `d_impact_method` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `method` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `d_impact_method`
--

LOCK TABLES `d_impact_method` WRITE;
/*!40000 ALTER TABLE `d_impact_method` DISABLE KEYS */;
INSERT INTO `d_impact_method` VALUES (1,' Learning Walk (LW)'),(2,'Classroom Observation (CO)'),(3,'Book Look (BL)'),(4,'Interactions');
/*!40000 ALTER TABLE `d_impact_method` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `d_intro_assess_que_option`
--

DROP TABLE IF EXISTS `d_intro_assess_que_option`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `d_intro_assess_que_option` (
  `o_id` int(10) NOT NULL AUTO_INCREMENT,
  `question_option` text NOT NULL,
  PRIMARY KEY (`o_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `d_intro_assess_que_option`
--

LOCK TABLES `d_intro_assess_que_option` WRITE;
/*!40000 ALTER TABLE `d_intro_assess_que_option` DISABLE KEYS */;
/*!40000 ALTER TABLE `d_intro_assess_que_option` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `d_js_order`
--

DROP TABLE IF EXISTS `d_js_order`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `d_js_order` (
  `order_id` int(11) NOT NULL,
  `show_text` varchar(10) NOT NULL,
  PRIMARY KEY (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `d_js_order`
--

LOCK TABLES `d_js_order` WRITE;
/*!40000 ALTER TABLE `d_js_order` DISABLE KEYS */;
INSERT INTO `d_js_order` VALUES (1,'1a'),(2,'1b'),(3,'1c'),(4,'1d'),(5,'1e'),(6,'1f'),(7,'1g'),(8,'1h'),(9,'1i'),(10,'1j'),(11,'1k'),(12,'1l'),(13,'1m');
/*!40000 ALTER TABLE `d_js_order` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `d_judgement_statement`
--

DROP TABLE IF EXISTS `d_judgement_statement`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `d_judgement_statement` (
  `judgement_statement_id` int(11) NOT NULL AUTO_INCREMENT,
  `judgement_statement_text1` varchar(1000) CHARACTER SET latin1 DEFAULT NULL,
  `isActive` tinyint(1) DEFAULT NULL,
  `equivalence_id` int(11) DEFAULT NULL COMMENT 'equivalence id referring h_lang_translation for translation of statements',
  PRIMARY KEY (`judgement_statement_id`),
  KEY `fk_d_judgement_statement_h_lang_translation_idx` (`equivalence_id`),
  CONSTRAINT `fk_d_judgement_statement_h_lang_translation` FOREIGN KEY (`equivalence_id`) REFERENCES `h_lang_translation` (`equivalence_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `d_judgement_statement`
--

LOCK TABLES `d_judgement_statement` WRITE;
/*!40000 ALTER TABLE `d_judgement_statement` DISABLE KEYS */;
INSERT INTO `d_judgement_statement` VALUES (1,NULL,1,111),(2,NULL,1,112),(3,NULL,1,113),(4,NULL,1,114),(5,NULL,1,115),(6,NULL,1,116),(7,NULL,1,117),(8,NULL,1,118),(9,NULL,1,119),(10,NULL,1,120),(11,NULL,1,121),(12,NULL,1,122),(13,NULL,1,123),(14,NULL,1,124),(15,NULL,1,125),(16,NULL,1,126),(17,NULL,1,127),(18,NULL,1,128),(19,NULL,1,129),(20,NULL,1,130),(21,NULL,1,131),(22,NULL,1,132),(23,NULL,1,133),(24,NULL,1,134),(25,NULL,1,135),(26,NULL,1,136),(27,NULL,1,137),(28,NULL,1,138),(29,NULL,1,139),(30,NULL,1,140),(31,NULL,1,141),(32,NULL,1,142),(33,NULL,1,143),(34,NULL,1,144),(35,NULL,1,145),(36,NULL,1,146),(37,NULL,1,147),(38,NULL,1,148),(39,NULL,1,149),(40,NULL,1,150),(41,NULL,1,151),(42,NULL,1,152),(43,NULL,1,153),(44,NULL,1,154),(45,NULL,1,155),(46,NULL,1,156),(47,NULL,1,166),(48,NULL,1,663);
/*!40000 ALTER TABLE `d_judgement_statement` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `d_key_question`
--

DROP TABLE IF EXISTS `d_key_question`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `d_key_question` (
  `key_question_id` int(11) NOT NULL AUTO_INCREMENT,
  `key_question_text1` varchar(1000) CHARACTER SET latin1 DEFAULT NULL,
  `isActive` tinyint(1) DEFAULT NULL,
  `equivalence_id` int(11) DEFAULT NULL COMMENT 'equivalence id referring h_lang_translation for translation of statements',
  PRIMARY KEY (`key_question_id`),
  KEY `fk_d_key_question_h_lang_translation_idx` (`equivalence_id`),
  CONSTRAINT `fk_d_key_question_h_lang_translation` FOREIGN KEY (`equivalence_id`) REFERENCES `h_lang_translation` (`equivalence_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `d_key_question`
--

LOCK TABLES `d_key_question` WRITE;
/*!40000 ALTER TABLE `d_key_question` DISABLE KEYS */;
INSERT INTO `d_key_question` VALUES (0,'',1,642);
/*!40000 ALTER TABLE `d_key_question` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `d_key_question_heading`
--

DROP TABLE IF EXISTS `d_key_question_heading`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `d_key_question_heading` (
  `key_question_id` int(11) NOT NULL,
  `key_heading` varchar(100) NOT NULL,
  PRIMARY KEY (`key_question_id`),
  CONSTRAINT `d_key_question_heading_ibfk_1` FOREIGN KEY (`key_question_id`) REFERENCES `d_key_question` (`key_question_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='for center and organisation reports in student assessment';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `d_key_question_heading`
--

LOCK TABLES `d_key_question_heading` WRITE;
/*!40000 ALTER TABLE `d_key_question_heading` DISABLE KEYS */;
/*!40000 ALTER TABLE `d_key_question_heading` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `d_kpa`
--

DROP TABLE IF EXISTS `d_kpa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `d_kpa` (
  `kpa_id` int(11) NOT NULL AUTO_INCREMENT,
  `kpa_name1` varchar(45) CHARACTER SET latin1 DEFAULT NULL,
  `isActive` tinyint(1) DEFAULT NULL,
  `equivalence_id` int(11) DEFAULT NULL COMMENT 'equivalence id referring h_lang_translation for translation of statements',
  PRIMARY KEY (`kpa_id`),
  KEY `fk_d_kpa_h_lang_translation_idx` (`equivalence_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `d_kpa`
--

LOCK TABLES `d_kpa` WRITE;
/*!40000 ALTER TABLE `d_kpa` DISABLE KEYS */;
INSERT INTO `d_kpa` VALUES (1,NULL,1,1),(2,NULL,1,2),(3,NULL,1,3),(4,NULL,1,4),(5,NULL,1,5),(6,NULL,1,6),(7,NULL,1,7);
/*!40000 ALTER TABLE `d_kpa` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `d_language`
--

DROP TABLE IF EXISTS `d_language`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `d_language` (
  `language_id` int(11) NOT NULL AUTO_INCREMENT,
  `language_name` varchar(45) NOT NULL,
  `language_code` varchar(255) DEFAULT NULL,
  `language_words` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`language_id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `d_language`
--

LOCK TABLES `d_language` WRITE;
/*!40000 ALTER TABLE `d_language` DISABLE KEYS */;
INSERT INTO `d_language` VALUES (1,'Assamese','as',''),(2,'Bengali','bn','Bengali'),(3,'Bhili',NULL,''),(4,'Bodo',NULL,''),(5,'Dogri',NULL,''),(6,'Gondi',NULL,''),(7,'Gujarati',NULL,''),(8,'Hindi','hi','Hindi'),(9,'English','en','English'),(10,'Kannada','ka',''),(11,'Kashmiri',NULL,''),(12,'Khandeshi',NULL,''),(13,'Khasi',NULL,''),(14,'Konkani',NULL,''),(15,'Kurukh',NULL,''),(16,'Maithili',NULL,''),(17,'Malayalam',NULL,''),(18,'Marathi','ma','Marathi'),(19,'Manipuri',NULL,''),(20,'Mundari',NULL,''),(21,'Nepali',NULL,''),(22,'Odia',NULL,''),(23,'Punjabi','pb',''),(24,'Santali',NULL,''),(25,'Sindhi',NULL,''),(26,'Tamil',NULL,''),(27,'Telugu',NULL,''),(28,'Tulu',NULL,''),(29,'Urdu',NULL,''),(30,'Spanish',NULL,''),(32,'--Not Specified--',NULL,'');
/*!40000 ALTER TABLE `d_language` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `d_network`
--

DROP TABLE IF EXISTS `d_network`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `d_network` (
  `network_id` int(11) NOT NULL AUTO_INCREMENT,
  `network_name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`network_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `d_network`
--

LOCK TABLES `d_network` WRITE;
/*!40000 ALTER TABLE `d_network` DISABLE KEYS */;
INSERT INTO `d_network` VALUES (1,'Block1');
/*!40000 ALTER TABLE `d_network` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `d_notification_queue`
--

DROP TABLE IF EXISTS `d_notification_queue`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `d_notification_queue` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `notification_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `user_email` varchar(255) NOT NULL,
  `assessment_id` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `final_email` int(11) NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `d_notification_queue`
--

LOCK TABLES `d_notification_queue` WRITE;
/*!40000 ALTER TABLE `d_notification_queue` DISABLE KEYS */;
/*!40000 ALTER TABLE `d_notification_queue` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `d_notification_type`
--

DROP TABLE IF EXISTS `d_notification_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `d_notification_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(255) NOT NULL,
  `status` int(2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `d_notification_type`
--

LOCK TABLES `d_notification_type` WRITE;
/*!40000 ALTER TABLE `d_notification_type` DISABLE KEYS */;
/*!40000 ALTER TABLE `d_notification_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `d_notifications`
--

DROP TABLE IF EXISTS `d_notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `d_notifications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `notification_label` varchar(255) NOT NULL,
  `notification_name` varchar(255) NOT NULL,
  `status` int(11) NOT NULL,
  `notification_type` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `d_notifications`
--

LOCK TABLES `d_notifications` WRITE;
/*!40000 ALTER TABLE `d_notifications` DISABLE KEYS */;
/*!40000 ALTER TABLE `d_notifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `d_post_review`
--

DROP TABLE IF EXISTS `d_post_review`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `d_post_review` (
  `post_review_id` int(11) NOT NULL AUTO_INCREMENT,
  `assessment_id` int(11) NOT NULL,
  `decision_maker` int(11) DEFAULT NULL COMMENT 'Decision for review taken by',
  `decision_maker_other` varchar(45) DEFAULT NULL COMMENT 'Decision for review taken by - Text',
  `management_engagement` int(11) DEFAULT NULL COMMENT 'Engagement of management in school',
  `action_management_decision` int(11) DEFAULT NULL COMMENT 'Action by management on decisions for improvement reported by Principal',
  `principal_tenure` int(11) DEFAULT NULL COMMENT 'Principal Tenure (in years)',
  `principal_vision` int(11) DEFAULT NULL COMMENT 'Principal''s vision',
  `principal_involvement` int(11) DEFAULT NULL COMMENT 'Principal''s involvement in the process',
  `principal_openness` int(11) DEFAULT NULL COMMENT 'Principal''s openness to review',
  `middle_leaders` int(11) DEFAULT NULL COMMENT 'Middle leaders (Coordinator/Supervisor)',
  `parent_teacher_association` int(11) DEFAULT NULL COMMENT 'Parent Teacher Association',
  `student_body` int(11) DEFAULT NULL,
  `student_body_activity` int(11) DEFAULT NULL COMMENT 'Student leaders role',
  `student_body_school_level` int(11) DEFAULT NULL COMMENT 'Student body - Level',
  `average_staff_tenure` int(11) DEFAULT NULL COMMENT 'Average Staff Tenure (in years)',
  `medium_instruction` int(11) DEFAULT NULL,
  `instruction_lang` int(11) DEFAULT NULL,
  `number_playgrounds` varchar(45) DEFAULT NULL,
  `playgrounds_actively_used` varchar(45) DEFAULT NULL,
  `number_classrooms` int(11) DEFAULT NULL,
  `average_number_students_class` int(11) DEFAULT NULL COMMENT 'Avg. students and teachers in single class',
  `ratio_students_class_size` int(11) DEFAULT NULL COMMENT 'Ratio of students to class size',
  `number_teaching_staff` int(11) DEFAULT NULL COMMENT 'Average teacher student ratio',
  `number_non_teaching_staff_prep` int(11) DEFAULT NULL COMMENT 'Teaching - Non teaching staff ratio (Prep)',
  `number_non_teaching_staff_rest` int(11) DEFAULT NULL COMMENT 'Teaching - Non teaching staff ratio (Other)',
  `teaching_staff_comment` varchar(255) DEFAULT NULL,
  `alumni_association` int(11) DEFAULT NULL COMMENT 'Alumni Association',
  `non_teaching_staff_comment` varchar(255) DEFAULT NULL,
  `rte` tinyint(1) DEFAULT NULL COMMENT '25% RTE Reservations',
  `comments` text,
  `student_count` int(5) DEFAULT NULL COMMENT 'Student enrolment count for the whole school',
  `status` tinyint(1) DEFAULT NULL,
  `percComplete` varchar(45) DEFAULT NULL,
  `create_date` date DEFAULT NULL,
  PRIMARY KEY (`post_review_id`),
  KEY `fk_d_assessment_id_idx` (`assessment_id`),
  CONSTRAINT `fk_d_assessment_id` FOREIGN KEY (`assessment_id`) REFERENCES `d_assessment` (`assessment_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `d_post_review`
--

LOCK TABLES `d_post_review` WRITE;
/*!40000 ALTER TABLE `d_post_review` DISABLE KEYS */;
/*!40000 ALTER TABLE `d_post_review` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `d_product`
--

DROP TABLE IF EXISTS `d_product`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `d_product` (
  `product_id` int(11) NOT NULL AUTO_INCREMENT,
  `product_name` varchar(45) DEFAULT NULL,
  `validated_reviews` int(11) DEFAULT NULL,
  `self_reviews` int(11) DEFAULT NULL,
  `recommendation_reports` int(11) DEFAULT NULL,
  `courses` int(11) DEFAULT NULL,
  `validity` int(11) DEFAULT NULL,
  `amount` varchar(45) DEFAULT NULL,
  `assist` varchar(45) DEFAULT NULL,
  `create_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `active` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `d_product`
--

LOCK TABLES `d_product` WRITE;
/*!40000 ALTER TABLE `d_product` DISABLE KEYS */;
/*!40000 ALTER TABLE `d_product` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `d_province`
--

DROP TABLE IF EXISTS `d_province`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `d_province` (
  `province_id` int(11) NOT NULL AUTO_INCREMENT,
  `province_name` varchar(100) NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  PRIMARY KEY (`province_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `d_province`
--

LOCK TABLES `d_province` WRITE;
/*!40000 ALTER TABLE `d_province` DISABLE KEYS */;
INSERT INTO `d_province` VALUES (1,'hub1',1);
/*!40000 ALTER TABLE `d_province` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `d_rating`
--

DROP TABLE IF EXISTS `d_rating`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `d_rating` (
  `rating_id` int(11) NOT NULL AUTO_INCREMENT,
  `rating1` varchar(45) DEFAULT NULL,
  `equivalence_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`rating_id`),
  KEY `fk_d_rating_h_lang_translation_idx` (`equivalence_id`),
  CONSTRAINT `fk_d_rating_h_lang_translation` FOREIGN KEY (`equivalence_id`) REFERENCES `h_lang_translation` (`equivalence_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `d_rating`
--

LOCK TABLES `d_rating` WRITE;
/*!40000 ALTER TABLE `d_rating` DISABLE KEYS */;
INSERT INTO `d_rating` VALUES (0,NULL,671),(1,NULL,632),(2,NULL,631),(3,NULL,630),(8,NULL,666),(9,NULL,667),(10,NULL,668),(11,NULL,669);
/*!40000 ALTER TABLE `d_rating` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `d_rating_definition`
--

DROP TABLE IF EXISTS `d_rating_definition`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `d_rating_definition` (
  `definition_id` int(11) NOT NULL AUTO_INCREMENT,
  `equivalence_id` int(11) NOT NULL,
  `rating_id` int(11) NOT NULL,
  PRIMARY KEY (`definition_id`),
  KEY `ifx_d_rating_idx` (`rating_id`),
  CONSTRAINT `ifx_d_rating` FOREIGN KEY (`rating_id`) REFERENCES `d_rating` (`rating_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=175 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `d_rating_definition`
--

LOCK TABLES `d_rating_definition` WRITE;
/*!40000 ALTER TABLE `d_rating_definition` DISABLE KEYS */;
INSERT INTO `d_rating_definition` VALUES (1,456,1),(2,514,2),(3,572,3),(4,457,1),(5,515,2),(6,573,3),(7,458,1),(8,516,2),(9,574,3),(10,459,1),(11,517,2),(12,575,3),(13,460,1),(14,518,2),(15,576,3),(16,461,1),(17,519,2),(18,577,3),(19,462,1),(20,520,2),(21,578,3),(22,463,1),(23,521,2),(24,579,3),(25,464,1),(26,522,2),(27,580,3),(28,465,1),(29,523,2),(30,581,3),(31,466,1),(32,524,2),(33,582,3),(34,467,1),(35,525,2),(36,583,3),(37,468,1),(38,526,2),(39,584,3),(40,469,1),(41,527,2),(42,585,3),(43,470,1),(44,528,2),(45,586,3),(46,471,1),(47,529,2),(48,587,3),(49,472,1),(50,530,2),(51,588,3),(52,473,1),(53,531,2),(54,589,3),(55,474,1),(56,532,2),(57,590,3),(58,475,1),(59,533,2),(60,591,3),(61,476,1),(62,534,2),(63,592,3),(64,477,1),(65,535,2),(66,593,3),(67,478,1),(68,536,2),(69,594,3),(70,479,1),(71,537,2),(72,595,3),(73,480,1),(74,538,2),(75,596,3),(76,481,1),(77,539,2),(78,597,3),(79,482,1),(80,540,2),(81,598,3),(82,483,1),(83,541,2),(84,599,3),(85,484,1),(86,542,2),(87,600,3),(88,485,1),(89,543,2),(90,601,3),(91,486,1),(92,544,2),(93,602,3),(94,487,1),(95,545,2),(96,603,3),(97,488,1),(98,546,2),(99,604,3),(100,489,1),(101,547,2),(102,605,3),(103,490,1),(104,548,2),(105,606,3),(106,491,1),(107,549,2),(108,607,3),(109,492,1),(110,550,2),(111,608,3),(112,493,1),(113,551,2),(114,609,3),(115,494,1),(116,552,2),(117,610,3),(118,495,1),(119,553,2),(120,611,3),(121,496,1),(122,554,2),(123,612,3),(124,497,1),(125,555,2),(126,613,3),(127,498,1),(128,556,2),(129,614,3),(130,499,1),(131,557,2),(132,615,3),(133,500,1),(134,558,2),(135,616,3),(136,501,1),(137,559,2),(138,617,3),(139,502,1),(140,560,2),(141,618,3),(142,503,1),(143,561,2),(144,619,3),(145,504,1),(146,562,2),(147,620,3),(148,505,1),(149,563,2),(150,621,3),(151,506,1),(152,564,2),(153,622,3),(154,507,1),(155,565,2),(156,623,3),(157,508,1),(158,566,2),(159,624,3),(160,509,1),(161,567,2),(162,625,3),(163,510,1),(164,568,2),(165,626,3),(166,511,1),(167,569,2),(168,627,3),(169,512,1),(170,570,2),(171,628,3),(172,513,1),(173,571,2),(174,629,3);
/*!40000 ALTER TABLE `d_rating_definition` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `d_rating_definition_copy`
--

DROP TABLE IF EXISTS `d_rating_definition_copy`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `d_rating_definition_copy` (
  `definition_id` int(11) NOT NULL AUTO_INCREMENT,
  `equivalence_id` int(11) NOT NULL,
  `rating_id` int(11) NOT NULL,
  PRIMARY KEY (`definition_id`)
) ENGINE=InnoDB AUTO_INCREMENT=175 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `d_rating_definition_copy`
--

LOCK TABLES `d_rating_definition_copy` WRITE;
/*!40000 ALTER TABLE `d_rating_definition_copy` DISABLE KEYS */;
INSERT INTO `d_rating_definition_copy` VALUES (1,572,1),(2,514,2),(3,456,3),(4,573,1),(5,515,2),(6,457,3),(7,574,1),(8,516,2),(9,458,3),(10,575,1),(11,517,2),(12,459,3),(13,576,1),(14,518,2),(15,460,3),(16,577,1),(17,519,2),(18,461,3),(19,578,1),(20,520,2),(21,462,3),(22,579,1),(23,521,2),(24,463,3),(25,580,1),(26,522,2),(27,464,3),(28,581,1),(29,523,2),(30,465,3),(31,582,1),(32,524,2),(33,466,3),(34,583,1),(35,525,2),(36,467,3),(37,584,1),(38,526,2),(39,468,3),(40,585,1),(41,527,2),(42,469,3),(43,586,1),(44,528,2),(45,470,3),(46,587,1),(47,529,2),(48,471,3),(49,588,1),(50,530,2),(51,472,3),(52,589,1),(53,531,2),(54,473,3),(55,590,1),(56,532,2),(57,474,3),(58,591,1),(59,533,2),(60,475,3),(61,592,1),(62,534,2),(63,476,3),(64,593,1),(65,535,2),(66,477,3),(67,594,1),(68,536,2),(69,478,3),(70,595,1),(71,537,2),(72,479,3),(73,596,1),(74,538,2),(75,480,3),(76,597,1),(77,539,2),(78,481,3),(79,598,1),(80,540,2),(81,482,3),(82,599,1),(83,541,2),(84,483,3),(85,600,1),(86,542,2),(87,484,3),(88,601,1),(89,543,2),(90,485,3),(91,602,1),(92,544,2),(93,486,3),(94,603,1),(95,545,2),(96,487,3),(97,604,1),(98,546,2),(99,488,3),(100,605,1),(101,547,2),(102,489,3),(103,606,1),(104,548,2),(105,490,3),(106,607,1),(107,549,2),(108,491,3),(109,608,1),(110,550,2),(111,492,3),(112,609,1),(113,551,2),(114,493,3),(115,610,1),(116,552,2),(117,494,3),(118,611,1),(119,553,2),(120,495,3),(121,612,1),(122,554,2),(123,496,3),(124,613,1),(125,555,2),(126,497,3),(127,614,1),(128,556,2),(129,498,3),(130,615,1),(131,557,2),(132,499,3),(133,616,1),(134,558,2),(135,500,3),(136,617,1),(137,559,2),(138,501,3),(139,618,1),(140,560,2),(141,502,3),(142,619,1),(143,561,2),(144,503,3),(145,620,1),(146,562,2),(147,504,3),(148,621,1),(149,563,2),(150,505,3),(151,622,1),(152,564,2),(153,506,3),(154,623,1),(155,565,2),(156,507,3),(157,624,1),(158,566,2),(159,508,3),(160,625,1),(161,567,2),(162,509,3),(163,626,1),(164,568,2),(165,510,3),(166,627,1),(167,569,2),(168,511,3),(169,628,1),(170,570,2),(171,512,3),(172,629,1),(173,571,2),(174,513,3);
/*!40000 ALTER TABLE `d_rating_definition_copy` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `d_rating_level`
--

DROP TABLE IF EXISTS `d_rating_level`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `d_rating_level` (
  `rating_level_id` int(11) NOT NULL AUTO_INCREMENT,
  `rating_level_text` varchar(45) NOT NULL,
  `rating_order` int(11) NOT NULL,
  PRIMARY KEY (`rating_level_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `d_rating_level`
--

LOCK TABLES `d_rating_level` WRITE;
/*!40000 ALTER TABLE `d_rating_level` DISABLE KEYS */;
INSERT INTO `d_rating_level` VALUES (1,'kpa',1),(2,'kq',2),(3,'sq',3),(4,'js',4);
/*!40000 ALTER TABLE `d_rating_level` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `d_recommendation`
--

DROP TABLE IF EXISTS `d_recommendation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `d_recommendation` (
  `recommendation_id` int(11) NOT NULL AUTO_INCREMENT,
  `recommendation_text1` longtext,
  `publish_time` datetime DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL,
  `equivalence_id` int(11) DEFAULT NULL COMMENT 'equivalence id referring h_lang_translation for translation of statements',
  PRIMARY KEY (`recommendation_id`),
  KEY `fk_d_recommendation_h_lang_translation_idx` (`equivalence_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `d_recommendation`
--

LOCK TABLES `d_recommendation` WRITE;
/*!40000 ALTER TABLE `d_recommendation` DISABLE KEYS */;
/*!40000 ALTER TABLE `d_recommendation` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `d_reports`
--

DROP TABLE IF EXISTS `d_reports`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `d_reports` (
  `report_id` int(11) NOT NULL AUTO_INCREMENT,
  `report_name` varchar(45) DEFAULT NULL,
  `assessment_type_id` int(11) NOT NULL,
  `isIndividualAssessmentReport` tinyint(1) NOT NULL,
  PRIMARY KEY (`report_id`),
  KEY `assessment_type_id` (`assessment_type_id`),
  CONSTRAINT `fk_d_assessment_type_d_report_1` FOREIGN KEY (`assessment_type_id`) REFERENCES `d_assessment_type` (`assessment_type_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `d_reports`
--

LOCK TABLES `d_reports` WRITE;
/*!40000 ALTER TABLE `d_reports` DISABLE KEYS */;
INSERT INTO `d_reports` VALUES (1,'Evaluation Report Card',1,1),(2,'School Demographic Report',1,1),(3,'Hub Report',1,0),(4,'Block Report',1,0),(6,'Evidence Report',1,1);
/*!40000 ALTER TABLE `d_reports` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `d_review_action`
--

DROP TABLE IF EXISTS `d_review_action`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `d_review_action` (
  `action_id` int(11) NOT NULL AUTO_INCREMENT,
  `action_type` varchar(500) NOT NULL,
  PRIMARY KEY (`action_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `d_review_action`
--

LOCK TABLES `d_review_action` WRITE;
/*!40000 ALTER TABLE `d_review_action` DISABLE KEYS */;
/*!40000 ALTER TABLE `d_review_action` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `d_review_association`
--

DROP TABLE IF EXISTS `d_review_association`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `d_review_association` (
  `association_id` int(11) NOT NULL AUTO_INCREMENT,
  `status` varchar(500) NOT NULL,
  `parent_association_text` varchar(255) DEFAULT NULL,
  `alumni_association_text` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`association_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `d_review_association`
--

LOCK TABLES `d_review_association` WRITE;
/*!40000 ALTER TABLE `d_review_association` DISABLE KEYS */;
/*!40000 ALTER TABLE `d_review_association` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `d_review_notification_template`
--

DROP TABLE IF EXISTS `d_review_notification_template`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `d_review_notification_template` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `template_text` longtext NOT NULL,
  `template_type` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `template_type` (`template_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `d_review_notification_template`
--

LOCK TABLES `d_review_notification_template` WRITE;
/*!40000 ALTER TABLE `d_review_notification_template` DISABLE KEYS */;
/*!40000 ALTER TABLE `d_review_notification_template` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `d_school_class`
--

DROP TABLE IF EXISTS `d_school_class`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `d_school_class` (
  `class_id` int(11) NOT NULL AUTO_INCREMENT,
  `class_name` varchar(20) NOT NULL,
  PRIMARY KEY (`class_id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `d_school_class`
--

LOCK TABLES `d_school_class` WRITE;
/*!40000 ALTER TABLE `d_school_class` DISABLE KEYS */;
INSERT INTO `d_school_class` VALUES (0,'- Not Specified -'),(1,'Nursery'),(2,'Jr. KG'),(3,'Sr. KG'),(4,'I'),(5,'II'),(6,'III'),(7,'IV'),(8,'V'),(9,'VI'),(10,'VII'),(11,'VIII'),(12,'IX'),(13,'X'),(14,'XI'),(15,'XII');
/*!40000 ALTER TABLE `d_school_class` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `d_school_profile`
--

DROP TABLE IF EXISTS `d_school_profile`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `d_school_profile` (
  `school_profile_id` int(11) NOT NULL AUTO_INCREMENT,
  `equivalence_id` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `html_type_id` int(11) NOT NULL,
  `kpa_id` int(11) NOT NULL,
  `display_order` int(11) NOT NULL,
  `childtobeactive_ifparentselected` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`school_profile_id`),
  KEY `ifx_equivalence_id_idx` (`equivalence_id`),
  KEY `ifx_html_type_id_idx` (`html_type_id`),
  CONSTRAINT `ifx_equivalence_id` FOREIGN KEY (`equivalence_id`) REFERENCES `h_lang_translation` (`equivalence_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `ifx_html_type_id` FOREIGN KEY (`html_type_id`) REFERENCES `d_sp_html_type` (`html_type_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=354 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `d_school_profile`
--

LOCK TABLES `d_school_profile` WRITE;
/*!40000 ALTER TABLE `d_school_profile` DISABLE KEYS */;
INSERT INTO `d_school_profile` VALUES (1,173,0,4,1,1,NULL),(2,174,0,4,1,2,NULL),(3,175,0,4,1,3,NULL),(4,176,0,1,1,4,NULL),(5,254,4,4,1,1,NULL),(6,255,4,4,1,2,NULL),(7,177,0,1,1,5,NULL),(8,256,7,6,1,1,NULL),(9,633,8,4,1,1,NULL),(10,634,8,4,1,2,NULL),(11,257,7,6,1,2,NULL),(12,633,11,4,1,1,NULL),(13,634,11,4,1,2,NULL),(14,258,7,6,1,3,NULL),(15,633,14,4,1,1,NULL),(16,634,14,4,1,2,NULL),(17,178,0,1,1,6,NULL),(18,259,17,2,1,1,NULL),(19,260,17,2,1,2,NULL),(20,261,17,2,1,3,NULL),(21,262,17,2,1,4,NULL),(22,179,0,5,1,7,NULL),(23,635,0,1,1,8,NULL),(24,636,23,4,1,1,NULL),(25,263,23,4,1,2,NULL),(26,181,0,4,1,9,NULL),(27,182,0,4,1,10,NULL),(28,183,0,4,1,11,NULL),(29,184,0,1,1,12,NULL),(30,264,29,3,1,1,NULL),(31,265,29,3,1,2,NULL),(32,266,29,3,1,3,NULL),(33,267,29,3,1,4,1),(34,185,0,1,1,13,NULL),(35,648,34,2,1,1,1),(36,649,34,2,1,2,NULL),(37,268,35,4,1,1,NULL),(38,186,0,4,1,14,NULL),(39,187,0,1,1,15,NULL),(40,269,39,2,1,1,NULL),(41,270,39,2,1,2,NULL),(42,271,39,2,1,3,NULL),(43,272,39,2,1,4,1),(44,188,0,1,1,16,NULL),(45,273,44,3,1,1,NULL),(46,274,44,3,1,2,NULL),(47,275,44,3,1,3,NULL),(48,276,44,3,1,4,NULL),(49,189,0,4,1,17,NULL),(50,190,0,1,1,18,NULL),(51,277,50,2,1,1,NULL),(52,278,50,2,1,2,NULL),(53,279,50,2,1,3,NULL),(54,280,50,2,1,4,NULL),(55,191,0,1,1,19,NULL),(56,281,55,3,1,1,NULL),(57,282,55,3,1,2,NULL),(58,283,55,3,1,3,NULL),(59,284,55,3,1,4,NULL),(60,192,0,1,1,20,NULL),(61,285,60,3,1,1,NULL),(62,286,60,3,1,2,NULL),(63,287,60,3,1,3,NULL),(64,288,60,3,1,4,NULL),(65,193,0,1,1,21,NULL),(66,289,65,3,1,1,NULL),(67,290,65,3,1,2,NULL),(68,291,65,3,1,3,NULL),(69,292,65,3,1,4,NULL),(70,293,65,3,1,5,NULL),(71,294,65,3,1,6,1),(72,194,0,1,1,22,NULL),(73,295,72,3,1,1,NULL),(74,296,72,3,1,2,NULL),(75,297,72,3,1,3,NULL),(76,298,72,3,1,4,NULL),(77,299,72,3,1,5,NULL),(78,300,72,3,1,6,NULL),(79,301,72,3,1,7,NULL),(80,195,0,1,1,23,NULL),(81,302,80,4,1,1,NULL),(82,303,80,4,1,2,NULL),(83,196,0,1,1,24,NULL),(84,304,83,3,1,1,NULL),(85,305,83,3,1,2,NULL),(86,306,83,3,1,3,1),(87,197,0,1,1,25,NULL),(88,307,87,2,1,1,NULL),(89,308,87,2,1,2,NULL),(90,309,87,2,1,3,NULL),(91,310,87,2,1,4,NULL),(92,311,87,2,1,5,1),(93,198,0,1,1,26,NULL),(94,312,93,2,1,1,NULL),(95,313,93,2,1,2,NULL),(96,314,93,2,1,3,NULL),(97,315,93,2,1,4,1),(98,635,0,1,1,27,NULL),(99,199,98,1,1,1,NULL),(100,648,99,2,1,1,1),(101,649,99,2,1,2,NULL),(102,316,98,4,1,2,NULL),(103,200,0,1,1,28,NULL),(104,317,103,2,1,1,NULL),(105,318,103,2,1,2,NULL),(106,319,103,2,1,3,NULL),(107,320,103,2,1,4,NULL),(108,635,0,1,1,29,NULL),(109,201,108,1,1,1,NULL),(110,321,109,2,1,1,1),(111,322,109,2,1,2,NULL),(112,323,108,1,1,2,NULL),(113,648,112,2,1,1,0),(114,649,112,2,1,2,NULL),(115,202,0,5,1,30,NULL),(116,203,0,1,1,31,NULL),(117,648,116,2,1,1,1),(118,649,116,2,1,2,NULL),(119,324,117,4,1,1,NULL),(120,325,117,4,1,2,NULL),(121,204,0,5,1,32,NULL),(122,205,0,1,1,33,NULL),(123,326,122,4,1,1,NULL),(124,327,122,4,1,2,NULL),(125,328,122,4,1,3,NULL),(126,329,122,4,1,4,NULL),(127,206,0,1,2,1,NULL),(128,330,127,3,2,1,NULL),(129,331,127,3,2,2,NULL),(130,332,127,3,2,3,NULL),(131,333,127,3,2,4,1),(132,334,133,2,2,5,NULL),(133,207,0,1,2,2,NULL),(134,335,133,2,2,1,NULL),(135,336,133,2,2,2,NULL),(136,337,137,3,2,1,NULL),(137,208,0,1,2,3,NULL),(138,338,137,3,2,2,NULL),(139,339,137,3,2,3,NULL),(140,340,137,3,2,4,NULL),(141,341,137,3,2,5,NULL),(142,209,0,4,3,1,NULL),(143,210,0,5,3,2,NULL),(144,211,0,1,3,3,0),(145,342,144,3,3,1,NULL),(146,343,144,3,3,2,NULL),(147,344,144,3,3,3,1),(148,345,144,3,3,4,NULL),(149,212,0,1,3,4,NULL),(150,648,149,2,3,1,0),(151,649,149,2,3,2,NULL),(152,635,0,1,3,5,NULL),(153,213,152,1,3,1,NULL),(154,648,153,2,3,1,0),(155,649,153,2,3,2,NULL),(156,346,152,1,3,2,NULL),(157,648,156,2,3,1,0),(158,649,156,2,3,2,NULL),(159,214,0,1,3,6,NULL),(160,347,159,2,3,1,NULL),(161,348,159,2,3,2,NULL),(162,349,159,2,3,3,NULL),(163,350,159,2,3,4,1),(164,635,0,1,3,7,NULL),(165,215,164,1,3,1,NULL),(166,648,165,2,3,1,1),(167,649,165,2,3,2,NULL),(168,351,166,1,3,2,NULL),(169,352,168,2,3,1,NULL),(170,353,168,2,3,2,NULL),(171,354,168,2,3,3,NULL),(172,355,168,2,3,4,1),(173,216,0,5,3,8,NULL),(174,217,0,1,3,9,NULL),(175,356,174,3,3,1,NULL),(176,357,174,3,3,2,NULL),(177,358,174,3,3,3,NULL),(178,359,174,3,3,4,1),(179,218,0,1,3,10,NULL),(180,360,179,2,3,1,NULL),(181,361,179,2,3,2,NULL),(182,362,179,2,3,3,NULL),(183,363,179,2,3,4,NULL),(184,219,0,1,4,1,NULL),(185,364,184,4,4,1,NULL),(186,365,184,4,4,2,NULL),(187,220,0,1,4,2,NULL),(188,366,187,4,4,1,NULL),(189,367,187,4,4,2,NULL),(190,368,187,4,4,3,NULL),(191,369,187,4,4,4,NULL),(192,370,187,4,4,5,0),(193,222,0,1,4,3,NULL),(194,371,193,4,4,1,NULL),(195,372,193,4,4,2,NULL),(196,223,0,1,4,4,NULL),(197,373,196,2,4,1,NULL),(198,374,196,2,4,2,NULL),(199,375,196,2,4,3,NULL),(200,376,196,2,4,4,1),(201,224,0,1,4,5,NULL),(202,648,201,2,4,1,1),(203,649,201,2,4,2,NULL),(204,377,202,5,4,1,NULL),(205,378,202,5,4,2,NULL),(206,379,202,5,4,3,NULL),(207,225,0,1,4,6,NULL),(208,380,207,3,4,1,NULL),(209,381,207,3,4,2,NULL),(210,382,207,3,4,3,NULL),(211,383,207,3,4,4,NULL),(212,226,0,1,4,7,NULL),(213,384,212,3,4,1,NULL),(214,385,212,3,4,2,NULL),(215,386,212,3,4,3,NULL),(216,387,212,3,4,4,NULL),(217,388,212,3,4,5,NULL),(218,389,212,3,4,6,1),(219,227,0,7,4,8,NULL),(220,228,0,7,4,9,NULL),(221,229,0,1,5,1,NULL),(222,648,221,2,5,1,1),(223,649,221,2,5,2,NULL),(224,390,222,5,5,1,NULL),(225,230,0,1,5,2,NULL),(226,648,225,2,5,1,1),(227,649,225,2,5,2,NULL),(228,391,226,5,5,1,NULL),(229,231,0,1,5,3,NULL),(230,648,229,2,5,1,1),(231,649,229,2,5,2,NULL),(232,392,230,5,5,4,NULL),(233,635,0,1,5,4,NULL),(234,232,233,1,5,1,NULL),(235,393,234,3,5,1,NULL),(236,394,234,3,5,2,NULL),(237,395,234,3,5,3,NULL),(238,396,234,3,5,4,1),(239,397,233,5,5,2,NULL),(240,233,0,1,5,5,NULL),(241,398,240,2,5,1,NULL),(242,399,240,2,5,2,NULL),(243,400,240,2,5,3,NULL),(244,401,240,2,5,4,NULL),(245,234,0,1,5,6,NULL),(246,402,245,2,5,1,NULL),(247,403,245,2,5,2,NULL),(248,404,245,2,5,3,NULL),(249,405,245,2,5,4,NULL),(250,235,0,1,5,7,NULL),(251,406,250,2,5,1,NULL),(252,407,250,2,5,2,NULL),(253,408,250,2,5,3,NULL),(254,409,250,2,5,4,NULL),(255,236,0,1,5,8,NULL),(256,648,255,2,5,1,1),(257,649,255,2,5,2,NULL),(258,410,256,5,5,1,NULL),(259,237,0,1,5,9,NULL),(260,411,259,3,5,1,NULL),(261,412,259,3,5,2,NULL),(262,413,259,3,5,3,NULL),(263,414,259,3,5,4,1),(264,238,0,1,5,10,NULL),(265,415,264,3,5,1,NULL),(266,416,264,3,5,2,NULL),(267,417,264,3,5,3,NULL),(268,418,264,3,5,4,NULL),(269,419,264,3,5,5,NULL),(270,239,0,1,6,1,NULL),(271,420,270,4,6,1,NULL),(272,421,270,4,6,2,NULL),(273,422,270,4,6,3,NULL),(274,240,0,1,6,2,NULL),(275,423,274,4,6,1,NULL),(276,424,274,4,6,2,NULL),(277,425,274,4,6,3,NULL),(278,426,274,4,6,4,NULL),(279,427,274,4,6,5,NULL),(280,428,274,4,6,6,NULL),(281,635,0,1,6,3,NULL),(282,241,281,5,6,1,NULL),(283,429,281,5,6,2,NULL),(284,242,0,1,6,4,NULL),(285,430,284,4,6,1,NULL),(286,431,284,4,6,2,NULL),(287,432,284,4,6,3,NULL),(288,433,284,4,6,4,NULL),(289,434,284,4,6,5,NULL),(290,435,284,4,6,6,NULL),(291,635,0,1,6,5,NULL),(292,243,291,1,6,1,NULL),(293,648,292,2,6,1,0),(294,649,292,2,6,2,NULL),(295,436,291,1,6,2,NULL),(296,437,295,3,6,1,NULL),(297,438,295,3,6,2,NULL),(298,439,295,3,6,3,NULL),(299,440,295,3,6,4,NULL),(300,441,295,3,6,5,NULL),(301,244,0,1,6,6,NULL),(302,648,301,2,6,1,0),(303,649,301,2,6,2,NULL),(304,245,0,4,6,7,NULL),(305,246,0,1,6,8,NULL),(306,648,305,2,6,1,0),(307,649,305,2,6,2,NULL),(308,635,0,1,6,9,NULL),(309,247,308,4,6,1,NULL),(310,442,308,4,6,2,NULL),(311,443,308,1,6,3,NULL),(312,444,311,4,6,1,NULL),(313,445,311,4,6,2,NULL),(314,446,311,4,6,3,NULL),(315,447,311,4,6,4,NULL),(316,635,308,1,6,4,NULL),(317,448,316,4,6,1,NULL),(318,449,316,4,6,2,NULL),(319,248,0,4,7,1,NULL),(320,249,0,1,7,2,NULL),(321,450,320,4,7,1,NULL),(322,451,320,4,7,2,NULL),(323,452,320,4,7,3,NULL),(324,453,320,4,7,4,NULL),(325,454,320,4,7,5,NULL),(326,455,320,4,7,6,NULL),(327,250,0,4,7,3,NULL),(328,251,0,4,7,4,NULL),(329,252,0,4,7,5,NULL),(330,253,0,5,7,6,NULL),(331,655,219,5,4,1,NULL),(332,656,219,5,4,2,NULL),(333,657,219,5,4,3,NULL),(334,658,220,5,4,1,NULL),(335,659,220,5,4,2,NULL),(338,660,33,4,1,1,0),(339,661,43,4,1,1,NULL),(340,660,71,4,1,1,NULL),(341,660,86,4,1,1,NULL),(342,660,92,4,1,1,NULL),(343,660,97,4,1,1,NULL),(344,660,163,4,3,1,NULL),(345,660,172,4,3,1,NULL),(346,660,178,4,3,1,NULL),(347,660,700,4,4,1,NULL),(348,660,200,4,4,1,NULL),(349,660,218,4,4,1,NULL),(350,660,238,4,5,1,NULL),(351,660,263,4,5,1,NULL),(352,660,147,4,3,1,NULL),(353,660,131,4,2,1,NULL);
/*!40000 ALTER TABLE `d_school_profile` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `d_school_region`
--

DROP TABLE IF EXISTS `d_school_region`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `d_school_region` (
  `region_id` int(11) NOT NULL,
  `region_name` varchar(45) NOT NULL,
  `info` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`region_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `d_school_region`
--

LOCK TABLES `d_school_region` WRITE;
/*!40000 ALTER TABLE `d_school_region` DISABLE KEYS */;
/*!40000 ALTER TABLE `d_school_region` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `d_sp_html_type`
--

DROP TABLE IF EXISTS `d_sp_html_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `d_sp_html_type` (
  `html_type_id` int(11) NOT NULL AUTO_INCREMENT,
  `html_type` varchar(45) NOT NULL,
  UNIQUE KEY `html_type_id_UNIQUE` (`html_type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `d_sp_html_type`
--

LOCK TABLES `d_sp_html_type` WRITE;
/*!40000 ALTER TABLE `d_sp_html_type` DISABLE KEYS */;
INSERT INTO `d_sp_html_type` VALUES (1,'static text'),(2,'radio button'),(3,'check box'),(4,'text box'),(5,'text area'),(6,'static text with inline childs'),(7,'table');
/*!40000 ALTER TABLE `d_sp_html_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `d_state`
--

DROP TABLE IF EXISTS `d_state`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `d_state` (
  `state_id` int(11) NOT NULL AUTO_INCREMENT,
  `state_name` varchar(255) NOT NULL,
  PRIMARY KEY (`state_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `d_state`
--

LOCK TABLES `d_state` WRITE;
/*!40000 ALTER TABLE `d_state` DISABLE KEYS */;
INSERT INTO `d_state` VALUES (1,'Goa');
/*!40000 ALTER TABLE `d_state` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `d_states`
--

DROP TABLE IF EXISTS `d_states`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `d_states` (
  `state_id` int(11) NOT NULL AUTO_INCREMENT,
  `state_name` varchar(30) NOT NULL,
  `country_id` int(11) NOT NULL,
  PRIMARY KEY (`state_id`),
  KEY `country_id` (`country_id`),
  CONSTRAINT `d_states_ibfk_1` FOREIGN KEY (`country_id`) REFERENCES `d_countries` (`country_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `d_states`
--

LOCK TABLES `d_states` WRITE;
/*!40000 ALTER TABLE `d_states` DISABLE KEYS */;
INSERT INTO `d_states` VALUES (1,'Goa',1);
/*!40000 ALTER TABLE `d_states` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `d_status`
--

DROP TABLE IF EXISTS `d_status`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `d_status` (
  `status_id` int(11) NOT NULL AUTO_INCREMENT,
  `status` varchar(20) NOT NULL,
  PRIMARY KEY (`status_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `d_status`
--

LOCK TABLES `d_status` WRITE;
/*!40000 ALTER TABLE `d_status` DISABLE KEYS */;
INSERT INTO `d_status` VALUES (0,'Not Approved'),(1,'Not Approved');
/*!40000 ALTER TABLE `d_status` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `d_student_data`
--

DROP TABLE IF EXISTS `d_student_data`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `d_student_data` (
  `student_data_id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) NOT NULL,
  `attr_id` int(11) NOT NULL,
  `assessment_id` int(11) NOT NULL,
  `value` varchar(255) NOT NULL,
  PRIMARY KEY (`student_data_id`),
  KEY `teacher_id` (`student_id`,`attr_id`,`assessment_id`),
  KEY `attr_id` (`attr_id`),
  KEY `assessment_id` (`assessment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `d_student_data`
--

LOCK TABLES `d_student_data` WRITE;
/*!40000 ALTER TABLE `d_student_data` DISABLE KEYS */;
/*!40000 ALTER TABLE `d_student_data` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `d_sub_assessment_type`
--

DROP TABLE IF EXISTS `d_sub_assessment_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `d_sub_assessment_type` (
  `sub_assessment_type_id` int(11) NOT NULL AUTO_INCREMENT,
  `assessment_type_id` int(11) DEFAULT NULL,
  `sub_assessment_type_name` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`sub_assessment_type_id`),
  KEY `fk_d_assessment_type_id_idx` (`assessment_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `d_sub_assessment_type`
--

LOCK TABLES `d_sub_assessment_type` WRITE;
/*!40000 ALTER TABLE `d_sub_assessment_type` DISABLE KEYS */;
/*!40000 ALTER TABLE `d_sub_assessment_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `d_teacher_data`
--

DROP TABLE IF EXISTS `d_teacher_data`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `d_teacher_data` (
  `teacher_data_id` int(11) NOT NULL AUTO_INCREMENT,
  `teacher_id` int(11) NOT NULL,
  `attr_id` int(11) NOT NULL,
  `assessment_id` int(11) NOT NULL,
  `value` varchar(255) NOT NULL,
  PRIMARY KEY (`teacher_data_id`),
  KEY `teacher_id` (`teacher_id`,`attr_id`,`assessment_id`),
  KEY `attr_id` (`attr_id`),
  KEY `assessment_id` (`assessment_id`),
  CONSTRAINT `fk_teacherdata_assessment_1` FOREIGN KEY (`assessment_id`) REFERENCES `d_assessment` (`assessment_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_teacherdata_attr_1` FOREIGN KEY (`attr_id`) REFERENCES `d_teacher_attribute` (`attr_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_teacherdata_user_1` FOREIGN KEY (`teacher_id`) REFERENCES `d_user` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `d_teacher_data`
--

LOCK TABLES `d_teacher_data` WRITE;
/*!40000 ALTER TABLE `d_teacher_data` DISABLE KEYS */;
/*!40000 ALTER TABLE `d_teacher_data` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `d_tier`
--

DROP TABLE IF EXISTS `d_tier`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `d_tier` (
  `standard_id` int(11) NOT NULL AUTO_INCREMENT,
  `standard_name` varchar(45) DEFAULT NULL,
  `isActive` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`standard_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `d_tier`
--

LOCK TABLES `d_tier` WRITE;
/*!40000 ALTER TABLE `d_tier` DISABLE KEYS */;
INSERT INTO `d_tier` VALUES (1,'International','1'),(2,'National','1'),(3,'State','1');
/*!40000 ALTER TABLE `d_tier` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `d_user`
--

DROP TABLE IF EXISTS `d_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `d_user` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(45) DEFAULT NULL,
  `password` varchar(45) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `client_id` int(11) NOT NULL,
  `aqs_status_id` int(11) NOT NULL DEFAULT '0',
  `has_view_video` char(1) DEFAULT '0',
  `create_date` datetime DEFAULT NULL,
  `createdby` int(11) DEFAULT NULL,
  `modify_date` datetime DEFAULT NULL,
  `modifyby` int(11) DEFAULT NULL,
  `add_moodle` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `email_UNIQUE` (`email`),
  KEY `fk_d_user_d_client1_idx` (`client_id`),
  KEY `aqs_status_id` (`aqs_status_id`),
  CONSTRAINT `fk_d_user_d_client1` FOREIGN KEY (`client_id`) REFERENCES `d_client` (`client_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_d_user_d_status` FOREIGN KEY (`aqs_status_id`) REFERENCES `d_status` (`status_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=1897 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `d_user`
--

LOCK TABLES `d_user` WRITE;
/*!40000 ALTER TABLE `d_user` DISABLE KEYS */;
INSERT INTO `d_user` VALUES (1,'admin','96e79218965eb72c92a549dd5a330112','Super Admin','supadmin@adhyayan.asia',43,1,'0','2018-10-14 14:54:05',NULL,'2019-01-22 12:38:38',1,0),(1895,NULL,'96e79218965eb72c92a549dd5a330112','test','test@test.com',916,0,'0','2019-02-21 15:47:24',1,NULL,NULL,0),(1896,NULL,'96e79218965eb72c92a549dd5a330112','ext1','ext@gmail.com',43,0,'0','2019-02-21 15:48:24',1,NULL,NULL,0);
/*!40000 ALTER TABLE `d_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `d_user_capability`
--

DROP TABLE IF EXISTS `d_user_capability`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `d_user_capability` (
  `capability_id` int(11) NOT NULL AUTO_INCREMENT,
  `slug` varchar(40) NOT NULL,
  `description` varchar(255) NOT NULL,
  `order` int(11) NOT NULL,
  PRIMARY KEY (`capability_id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `d_user_capability`
--

LOCK TABLES `d_user_capability` WRITE;
/*!40000 ALTER TABLE `d_user_capability` DISABLE KEYS */;
INSERT INTO `d_user_capability` VALUES (1,'manage_own_users','user can add, update users of his own client_id',0),(2,'manage_all_users','user can add, update all users',0),(3,'create_client','user can create a new client',0),(4,'create_assessment','user can create a new assessment',0),(5,'upload_file','user can upload whiles while taking assessment',1),(7,'manage_diagnostic','user can create, update diagnostic,kpa,key question,core question, judgemental statement',0),(8,'view_own_institute_assessment','user can view all assessments that are assigned to internal assessors of his school',0),(9,'view_all_assessments','user can view all assessments in our record',0),(12,'create_network','user can create a new network',0),(13,'manage_app_settings','user can edit the application settings.\r\nNote:- Only super admin should have this capability.',0),(14,'manage_own_network_users','user can manage all the users of his own network',0),(16,'manage_own_network_clients','user can view client list of his own network, but user can\'t add/update the list',0),(17,'take_internal_assessment','user can take internal assessment',0),(18,'take_external_assessment','user can take external assessment',0),(19,'view_own_network_assessment','user can view assessments of his own network',0),(21,'edit_all_submitted_assessments','user can edit all the submitted assessments',0),(23,'create_self_review','user can create self review only',0),(24,'generate_submitted_asmt_reports','user can generate reports for submitted assessments',0),(25,'generate_unsubmitted_asmt_reports','user can generate reports for unsubmitted assessments',0),(26,'assign_external_review_team','user can assign external review team for school review',0),(29,'view_published_own_school_reports','user can view published reports for his school',0),(32,'my_dashboard','user can view graphical data',0);
/*!40000 ALTER TABLE `d_user_capability` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `d_user_enc`
--

DROP TABLE IF EXISTS `d_user_enc`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `d_user_enc` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `key` text,
  `create_date` date DEFAULT NULL,
  `expiration_date` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_d_user_user_id_idx` (`user_id`),
  CONSTRAINT `fk_d_user_idx` FOREIGN KEY (`user_id`) REFERENCES `d_user` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `d_user_enc`
--

LOCK TABLES `d_user_enc` WRITE;
/*!40000 ALTER TABLE `d_user_enc` DISABLE KEYS */;
INSERT INTO `d_user_enc` VALUES (2,13,'eb85d9aef5db02698f2e126e1025f5752ae2223b5b9931fc0116adf2d7dcaab1e4ef9357b9d14f6f6b24801332cde9b00988d3dfd06f0b84bfca61e06f793f26','2018-06-30','2018-07-01'),(3,NULL,'98f29fc6a570f46027c7e8bb45a9fcdb189c3651351a20923fa637126e6b25fb0c499d814122fcfceb6860a55f7849fc50ef34f0a6b45898fe7df2bec7bc5f59','2018-07-28','2019-07-28'),(4,NULL,'11c68c1e689a11acf337b58bebb46e7913c80006488cd6d2464cddf685e67d9c67305913e19f98a10e0eca3709c4bbc328153bff938fe15a624106ddc3add73f','2018-11-28','2019-11-28'),(5,NULL,'11c68c1e689a11acf337b58bebb46e7913c80006488cd6d2464cddf685e67d9c67305913e19f98a10e0eca3709c4bbc328153bff938fe15a624106ddc3add73f','2018-11-28','2019-11-28'),(9,1,'8a9c45dc5bd777ad57d10bb96158975a6d8a5d00065e246c7727eaabb2efb30054494177ef7d87d7e9677012d4dcb3298ec3442831d41b09b7932f72fc43db89','2019-01-21','2020-01-21'),(10,1735,'afc5bf38cdd5ef134fceccca73e7d6245f3d6471f991ee816f53a652176b8752c2458c6ef1dd8a4ce98590ace227d431b7d7132bf7253d91d0cc0848ab58cb4f','2019-02-01','2020-02-01'),(11,NULL,'0bbfdf537704ab4a0216a8a6efa29319b3cc0040397f1641d5e2356fcf58b81bee7ff96ba4da7e0ce1d1809dac517a3b7bd83d1fc087b6a77a8e6dd781f5c535','2019-02-01','2020-02-01'),(12,NULL,'647e32a45f381180a820ed73a3625f0f64afa65f004ed954a0ace0012477c15e23af03f6e9e8cd885a371f84e762cd500f3988be8d3f086031785f65ac67ed39','2019-02-06','2020-02-06');
/*!40000 ALTER TABLE `d_user_enc` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `d_user_role`
--

DROP TABLE IF EXISTS `d_user_role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `d_user_role` (
  `role_id` int(11) NOT NULL AUTO_INCREMENT,
  `role_name` varchar(45) DEFAULT NULL,
  `order` int(11) NOT NULL,
  PRIMARY KEY (`role_id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `d_user_role`
--

LOCK TABLES `d_user_role` WRITE;
/*!40000 ALTER TABLE `d_user_role` DISABLE KEYS */;
INSERT INTO `d_user_role` VALUES (1,'Super Admin',1),(2,'Adhyayan Admin',2),(3,'Internal Reviewer',7),(4,'External Reviewer',6),(5,'Admin',5),(6,'Principal',4),(7,'Block Admin',3),(8,'TAP Admin',99),(9,'Facilitator',9),(10,'State Admin',10),(11,'Zone Admin',11),(12,'Hub Leader',12);
/*!40000 ALTER TABLE `d_user_role` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `d_user_sub_role`
--

DROP TABLE IF EXISTS `d_user_sub_role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `d_user_sub_role` (
  `sub_role_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_role_id` int(11) DEFAULT NULL,
  `sub_role_name` varchar(45) DEFAULT NULL,
  `sub_role_order` int(11) DEFAULT NULL,
  PRIMARY KEY (`sub_role_id`),
  KEY `fk_d_user_role_role_id` (`user_role_id`),
  CONSTRAINT `fk_d_user_role_role_id` FOREIGN KEY (`user_role_id`) REFERENCES `d_user_role` (`role_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `d_user_sub_role`
--

LOCK TABLES `d_user_sub_role` WRITE;
/*!40000 ALTER TABLE `d_user_sub_role` DISABLE KEYS */;
INSERT INTO `d_user_sub_role` VALUES (1,4,'Mentor',1),(2,4,'Apprentice',4),(3,4,'Associate',2),(4,4,'Intern',3),(5,9,'LED',1),(6,9,'Co-Facilitator',2),(7,9,'Attended',3);
/*!40000 ALTER TABLE `d_user_sub_role` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `d_user_type`
--

DROP TABLE IF EXISTS `d_user_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `d_user_type` (
  `user_type_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_type_name` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`user_type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `d_user_type`
--

LOCK TABLES `d_user_type` WRITE;
/*!40000 ALTER TABLE `d_user_type` DISABLE KEYS */;
INSERT INTO `d_user_type` VALUES (1,'State'),(2,'Zone'),(3,'Block'),(4,'Hub'),(5,'School');
/*!40000 ALTER TABLE `d_user_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `d_zone`
--

DROP TABLE IF EXISTS `d_zone`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `d_zone` (
  `zone_id` int(11) NOT NULL AUTO_INCREMENT,
  `zone_name` varchar(255) NOT NULL,
  PRIMARY KEY (`zone_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `d_zone`
--

LOCK TABLES `d_zone` WRITE;
/*!40000 ALTER TABLE `d_zone` DISABLE KEYS */;
INSERT INTO `d_zone` VALUES (1,'Zone1');
/*!40000 ALTER TABLE `d_zone` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `del_h_network_report_student`
--

DROP TABLE IF EXISTS `del_h_network_report_student`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `del_h_network_report_student` (
  `h_network_report_student_id` int(11) NOT NULL AUTO_INCREMENT,
  `network_report_id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `province_id` int(11) NOT NULL,
  `network_id` int(11) NOT NULL,
  `round_id` int(11) NOT NULL,
  PRIMARY KEY (`h_network_report_student_id`),
  KEY `network_report_id` (`network_report_id`),
  KEY `round_id` (`round_id`),
  CONSTRAINT `del_h_network_report_student_ibfk_1` FOREIGN KEY (`network_report_id`) REFERENCES `h_network_report` (`network_report_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `del_h_network_report_student_ibfk_3` FOREIGN KEY (`round_id`) REFERENCES `d_aqs_rounds` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `del_h_network_report_student`
--

LOCK TABLES `del_h_network_report_student` WRITE;
/*!40000 ALTER TABLE `del_h_network_report_student` DISABLE KEYS */;
/*!40000 ALTER TABLE `del_h_network_report_student` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `f_school_profile_data`
--

DROP TABLE IF EXISTS `f_school_profile_data`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `f_school_profile_data` (
  `sp_data_id` int(11) NOT NULL AUTO_INCREMENT,
  `school_profile_id` int(11) NOT NULL,
  `assessment_id` int(11) NOT NULL,
  `answer` text NOT NULL,
  `group_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`sp_data_id`),
  KEY `ifx_school_profile_id_idx` (`school_profile_id`),
  KEY `ifx_assessment_id_idx` (`assessment_id`),
  CONSTRAINT `ifx_assessment_id` FOREIGN KEY (`assessment_id`) REFERENCES `d_assessment` (`assessment_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `ifx_school_profile_id` FOREIGN KEY (`school_profile_id`) REFERENCES `d_school_profile` (`school_profile_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `f_school_profile_data`
--

LOCK TABLES `f_school_profile_data` WRITE;
/*!40000 ALTER TABLE `f_school_profile_data` DISABLE KEYS */;
/*!40000 ALTER TABLE `f_school_profile_data` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `f_score`
--

DROP TABLE IF EXISTS `f_score`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `f_score` (
  `score_id` int(11) NOT NULL AUTO_INCREMENT,
  `judgement_statement_instance_id` int(11) NOT NULL,
  `assessment_id` int(11) NOT NULL,
  `rating_id` int(11) NOT NULL,
  `isFinal` tinyint(1) DEFAULT NULL,
  `assessor_id` int(11) NOT NULL,
  `added_by` int(11) NOT NULL,
  `date_added` datetime NOT NULL,
  `evidence_text` text CHARACTER SET latin1,
  `level2rating` int(11) NOT NULL,
  `evidence_text_lw` text COLLATE utf8_unicode_ci NOT NULL,
  `evidence_text_co` text COLLATE utf8_unicode_ci NOT NULL,
  `evidence_text_in` text COLLATE utf8_unicode_ci NOT NULL,
  `evidence_text_bl` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`score_id`),
  KEY `fk_score_d_assessment1_idx` (`assessment_id`),
  KEY `fk_score_d_user1_idx` (`assessor_id`),
  KEY `fk_score_d_rating1_idx` (`rating_id`),
  KEY `fk_score_h_cq_js_instance1` (`judgement_statement_instance_id`),
  KEY `added_by` (`added_by`),
  CONSTRAINT `fk_d_user_f_score1` FOREIGN KEY (`added_by`) REFERENCES `d_user` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_score_d_assessment1` FOREIGN KEY (`assessment_id`) REFERENCES `d_assessment` (`assessment_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_score_d_rating1` FOREIGN KEY (`rating_id`) REFERENCES `d_rating` (`rating_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_score_d_user1` FOREIGN KEY (`assessor_id`) REFERENCES `d_user` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_score_h_cq_js_instance1` FOREIGN KEY (`judgement_statement_instance_id`) REFERENCES `h_cq_js_instance` (`judgement_statement_instance_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `f_score`
--

LOCK TABLES `f_score` WRITE;
/*!40000 ALTER TABLE `f_score` DISABLE KEYS */;
/*!40000 ALTER TABLE `f_score` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `h_alert_relation`
--

DROP TABLE IF EXISTS `h_alert_relation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `h_alert_relation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login_user_role` int(11) NOT NULL,
  `alert_ids` text CHARACTER SET utf8 NOT NULL,
  `ref_key` varchar(500) CHARACTER SET utf8 NOT NULL,
  `type` varchar(50) NOT NULL,
  `flag` int(1) NOT NULL,
  `creation_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `h_alert_relation`
--

LOCK TABLES `h_alert_relation` WRITE;
/*!40000 ALTER TABLE `h_alert_relation` DISABLE KEYS */;
/*!40000 ALTER TABLE `h_alert_relation` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `h_assessment_ass_group`
--

DROP TABLE IF EXISTS `h_assessment_ass_group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `h_assessment_ass_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_assessment_id` int(11) NOT NULL,
  `assessment_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_h_assessment_ass_group_d_group_assessment1_idx` (`group_assessment_id`),
  KEY `fk_h_assessment_ass_group_d_assessment1_idx` (`assessment_id`),
  CONSTRAINT `fk_h_assessment_ass_group_d_assessment1` FOREIGN KEY (`assessment_id`) REFERENCES `d_assessment` (`assessment_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_h_assessment_ass_group_d_group_assessment1` FOREIGN KEY (`group_assessment_id`) REFERENCES `d_group_assessment` (`group_assessment_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `h_assessment_ass_group`
--

LOCK TABLES `h_assessment_ass_group` WRITE;
/*!40000 ALTER TABLE `h_assessment_ass_group` DISABLE KEYS */;
/*!40000 ALTER TABLE `h_assessment_ass_group` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `h_assessment_external_team`
--

DROP TABLE IF EXISTS `h_assessment_external_team`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `h_assessment_external_team` (
  `assessment_row_id` int(11) NOT NULL AUTO_INCREMENT,
  `assessment_id` int(11) DEFAULT NULL,
  `user_role` int(11) DEFAULT NULL,
  `user_sub_role` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `external_client_id` int(11) DEFAULT NULL,
  `ratingInputDate` datetime NOT NULL,
  `isFilled` int(2) NOT NULL,
  `percComplete` decimal(5,2) NOT NULL,
  `is_offline` int(1) DEFAULT '0' COMMENT '0- is not sync with offline, 1- is sync with offline',
  `is_offline_date` datetime DEFAULT NULL,
  PRIMARY KEY (`assessment_row_id`),
  KEY `fkx_assessment_id_idx` (`assessment_id`),
  KEY `fkx_user_id_idx` (`user_id`),
  KEY `fkx_client_id_idx` (`external_client_id`),
  KEY `fkx_d_assessment_assessment_id` (`assessment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `h_assessment_external_team`
--

LOCK TABLES `h_assessment_external_team` WRITE;
/*!40000 ALTER TABLE `h_assessment_external_team` DISABLE KEYS */;
/*!40000 ALTER TABLE `h_assessment_external_team` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `h_assessment_labels`
--

DROP TABLE IF EXISTS `h_assessment_labels`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `h_assessment_labels` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `language_id` int(11) NOT NULL,
  `label_id` int(11) NOT NULL,
  `label_text` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=109 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `h_assessment_labels`
--

LOCK TABLES `h_assessment_labels` WRITE;
/*!40000 ALTER TABLE `h_assessment_labels` DISABLE KEYS */;
INSERT INTO `h_assessment_labels` VALUES (1,9,1,'KEY DOMAIN'),(2,9,2,'Key Question'),(3,9,3,'Sub Question'),(4,9,4,'Good'),(5,9,5,'Always'),(6,9,6,'Mostly'),(7,9,7,'Sometimes'),(8,9,8,'Rarely'),(9,9,11,'Variable'),(10,9,12,'Evidence'),(11,9,13,'Your Rating'),(12,8,1,'प्र.नि.क्षे'),(13,8,2,'प्रमुख प्रश्न'),(14,8,3,'उप प्रश्न'),(15,8,4,'उत्तम'),(16,8,5,'सदैव'),(17,8,6,'अधिकतर'),(18,8,7,'कभी-कभी'),(19,8,8,'कदाचित'),(20,8,13,'आत्म समीक्षा निर्धारण'),(21,8,12,'साक्ष्य'),(22,8,21,'साक्ष्य व्याख्यान'),(23,9,21,'Evidence Text'),(24,9,22,'Key Recommendations'),(25,8,22,'मुख्य सिफ़ारिश'),(26,8,23,'देखना'),(27,8,24,'जमा करना'),(28,8,25,'समीक्षा'),(29,9,23,'View'),(30,9,24,'Save'),(31,9,25,'Preview'),(32,9,26,'Submit'),(33,8,26,'उपस्थित करना'),(34,8,27,'6 प्रमुख निष्पादन क्षेत्रों (KPAs) में समीक्षा की तुलना'),(35,9,27,'Comparison of Reviews across 6 Key Performance Areas'),(36,8,28,'आत्म समीक्षा निर्धारण'),(37,8,29,'बाह्य समीक्षा निर्धारण'),(38,9,28,'Self-Review Rating'),(39,9,29,'External Review Rating'),(40,9,30,'Judgement_Statements'),(41,8,30,'निर्णय वाक्य'),(42,9,31,'Judgement Distance'),(43,8,31,'निर्णय अन्तर'),(44,9,32,'Self-Review Grade for S.Q'),(45,8,32,'(S. Q.) के लिए आत्म समीक्षा ग्रेड'),(46,9,33,'External Review Grade for S.Q'),(47,8,33,'S. Q.) के लिए बाह्य समीक्षा ग्रेड'),(48,9,34,'Self-Review Grade for K.Q'),(49,8,34,'(K. Q.) के लिए आत्म समीक्षा ग्रेड'),(50,9,35,'External Review Grade for K.Q'),(51,8,35,'(K. Q.) के लिए बाह्य समीक्षा ग्रेड'),(52,9,36,'KPA'),(53,8,36,'प्रमुख निष्पादन क्षेत्र'),(54,8,37,'के लिए आकलन'),(55,9,37,'Assessment for'),(56,9,38,'There is evidence of robust systems of good practice throughout the school, across all sections from the beginning of the academic year to the end. These are documented and known to all stakeholders.'),(57,9,39,'There is evidence of systemic good practice in most part of the school, in most of the sections for most of the year. Everyone in the school is aware of these systems and processes.'),(58,9,40,'There is evidence of good practice in some parts of the school, in some of the sections for some time of the year. It is known to few stakeholders in the school.'),(59,9,41,'There is little or no evidence of good practise in the school.'),(60,8,38,'विद्यालय के सभी विभागों में, सभी वर्गों में शैक्षणिक वर्ष की शुरुआत से लेकर अंत तक प्रणालीगत अच्छे आचरण की ठोस व्यवस्था का प्रमाण है। यह दस्तावेज किया जाता है और सभी हितधारकों के लिए ज्ञात हैं।'),(61,8,39,'विद्यालय के ज्यादातर विभागों में, अधिकांश वर्गों में अधिकांश वर्ष के लिए प्रणालीगत अच्छे आचरण का प्रमाण है। विद्यालय में हर कोई इन प्रणाली और प्रक्रियाओं के बारे में जानता है।'),(62,8,40,'विद्यालय के कुछ विभागों में, कुछ वर्गों में वर्ष के कुछ समय के लिए प्रणालीगत अच्छे आचरण का प्रमाण है। यह विद्यालय में कुछ हितधारकों के लिए ज्ञात हैं।'),(63,8,41,'विद्यालय  में प्रणालीगत अच्छे आचरण के बहुत कम या कोई प्रमाण नहीं है\r\n'),(64,8,42,'रिपोर्ट पढ़ने की कुंजी'),(65,9,42,'Key for reading the report'),(66,9,43,'Bar graph representation of above comparison'),(67,8,43,'उपरोक्त तुलना की बार ग्राफ प्रस्तुतिकरण'),(68,8,44,'& प्रमुख निष्पादन क्षेत्रों (KPAs) में समीक्षा की तुलना'),(69,9,44,'Comparison of Reviews across & Key Performance Areas'),(70,9,45,'INDEX'),(71,8,45,'अनुक्रमणिका'),(72,8,46,'चरण'),(73,9,46,'SR. NO.'),(74,8,47,'विभाग'),(75,8,48,'वपृष्ठ क्रमांक'),(76,9,48,'PAGE NO.'),(77,9,47,'PARTICULARS'),(78,8,49,'KPA& के लिए प्राप्तांक-कार्ड'),(79,9,49,'Score card for KPA&'),(80,9,50,'Name of the Principal'),(81,8,50,'प्राध्यापक का नाम'),(82,8,51,'अध्ययन गुणवत्ता मानक (AQS) से सम्मानित'),(83,9,51,'Adhyayan Quality Standard Awarded'),(84,9,52,'ADHYAYAN REPORT CARD'),(85,8,52,'अध्ययन रिपोर्ट कार्ड'),(86,9,53,'School Self-Review & Evaluation (SSRE team - School Assessors)'),(87,8,53,'विद्यालय आत्म समीक्षा तथा मूल्यांकन (SSRE टीम – विद्यालय आंकलनकर्ता)'),(88,9,54,'School External Review & Evaluation (SERE team - Adhyayan` Assessors)'),(89,8,54,'विद्यालय बाह्य समीक्षा तथा मूल्यांकन (SERE टीम – अध्ययन आंकलनकर्ता)'),(90,8,55,'पर आधारित गणना का संकलन'),(91,9,55,'A compilation of scores based on'),(92,8,56,'एवं'),(93,9,56,'And'),(94,8,57,'& में आयोजित'),(95,9,57,'conducted on: &'),(96,8,58,'& तक वैध'),(97,9,58,'Valid until: &'),(98,8,60,'असंतोषजनक'),(99,9,60,'Needs Attention'),(100,9,59,'Outstanding'),(101,8,59,'सर्वोत्तम'),(102,8,11,'असंगत'),(103,9,61,'NO.'),(104,8,61,'क्र.'),(105,9,62,'Level1'),(106,9,63,'Level2'),(107,9,64,'Level3'),(108,9,65,'Name of the Incharge/HM');
/*!40000 ALTER TABLE `h_assessment_labels` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `h_assessment_report`
--

DROP TABLE IF EXISTS `h_assessment_report`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `h_assessment_report` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `assessment_id` int(11) NOT NULL,
  `report_id` int(11) NOT NULL,
  `isPublished` tinyint(1) DEFAULT NULL,
  `publishDate` datetime DEFAULT NULL,
  `filename` varchar(250) DEFAULT NULL,
  `valid_until` datetime DEFAULT NULL,
  `fileformat` varchar(45) DEFAULT NULL,
  `report_name` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_h_assessment_report_d_assessment1_idx` (`assessment_id`),
  KEY `fk_h_assessment_report_d_reports1_idx` (`report_id`),
  CONSTRAINT `fk_h_assessment_report_d_assessment1` FOREIGN KEY (`assessment_id`) REFERENCES `d_assessment` (`assessment_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_h_assessment_report_d_reports1` FOREIGN KEY (`report_id`) REFERENCES `d_reports` (`report_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `h_assessment_report`
--

LOCK TABLES `h_assessment_report` WRITE;
/*!40000 ALTER TABLE `h_assessment_report` DISABLE KEYS */;
/*!40000 ALTER TABLE `h_assessment_report` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `h_assessment_user`
--

DROP TABLE IF EXISTS `h_assessment_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `h_assessment_user` (
  `assessment_user_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `role` int(11) NOT NULL,
  `assessment_id` int(11) NOT NULL,
  `ratingInputDate` datetime DEFAULT NULL,
  `isFilled` tinyint(1) NOT NULL DEFAULT '0',
  `percComplete` decimal(5,2) NOT NULL,
  `action_planning_status` int(11) NOT NULL DEFAULT '0',
  `isLeadSave` int(11) NOT NULL DEFAULT '0',
  `is_offline` int(1) DEFAULT '0' COMMENT '0- is not sync with offline, 1- is sync with offline',
  `is_offline_date` datetime DEFAULT NULL,
  PRIMARY KEY (`assessment_user_id`),
  KEY `fk_h_assessment_user_d_user1_idx` (`user_id`),
  KEY `fk_h_assessment_user_d_assessment1_idx` (`assessment_id`),
  KEY `fk_h_assessment_user_d_user_role_idx` (`role`),
  CONSTRAINT `fk_h_assessment_user_d_assessment1` FOREIGN KEY (`assessment_id`) REFERENCES `d_assessment` (`assessment_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_h_assessment_user_d_user1` FOREIGN KEY (`user_id`) REFERENCES `d_user` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_h_assessment_user_d_user_role` FOREIGN KEY (`role`) REFERENCES `d_user_role` (`role_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `h_assessment_user`
--

LOCK TABLES `h_assessment_user` WRITE;
/*!40000 ALTER TABLE `h_assessment_user` DISABLE KEYS */;
INSERT INTO `h_assessment_user` VALUES (1,1896,4,1,NULL,0,0.00,0,0,0,NULL),(2,1895,3,1,NULL,0,0.00,0,0,0,NULL);
/*!40000 ALTER TABLE `h_assessment_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `h_assessor_action1`
--

DROP TABLE IF EXISTS `h_assessor_action1`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `h_assessor_action1` (
  `h_assessor_action1_id` int(11) NOT NULL AUTO_INCREMENT,
  `assessor_key_notes_id` int(11) NOT NULL,
  `from_date` date NOT NULL,
  `to_date` date NOT NULL,
  `leader` int(11) DEFAULT NULL,
  `frequency_report` int(11) DEFAULT NULL,
  `reporting_authority` text NOT NULL,
  `action_status` int(11) NOT NULL,
  `createDate` datetime NOT NULL,
  `modifyDate` datetime NOT NULL,
  `createdBy` int(11) NOT NULL,
  `modifiedBy` int(11) NOT NULL,
  `mail_status` int(11) NOT NULL,
  PRIMARY KEY (`h_assessor_action1_id`),
  KEY `assessor_key_notes_id` (`assessor_key_notes_id`),
  KEY `leader` (`leader`),
  KEY `frequency_report` (`frequency_report`),
  CONSTRAINT `h_assessor_action1_ibfk_1` FOREIGN KEY (`assessor_key_notes_id`) REFERENCES `assessor_key_notes` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `h_assessor_action1_ibfk_2` FOREIGN KEY (`leader`) REFERENCES `d_user` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `h_assessor_action1_ibfk_3` FOREIGN KEY (`frequency_report`) REFERENCES `d_frequency` (`frequency_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `h_assessor_action1`
--

LOCK TABLES `h_assessor_action1` WRITE;
/*!40000 ALTER TABLE `h_assessor_action1` DISABLE KEYS */;
/*!40000 ALTER TABLE `h_assessor_action1` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `h_assessor_action1_impact`
--

DROP TABLE IF EXISTS `h_assessor_action1_impact`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `h_assessor_action1_impact` (
  `assessor_action1_impact_id` int(11) NOT NULL AUTO_INCREMENT,
  `assessor_action1_id` int(11) NOT NULL,
  `designation_id` int(11) NOT NULL,
  `impact_statement` text NOT NULL,
  PRIMARY KEY (`assessor_action1_impact_id`),
  KEY `designation_id` (`designation_id`),
  KEY `h_assessor_action1_impact_ibfk_1` (`assessor_action1_id`),
  CONSTRAINT `h_assessor_action1_impact_ibfk_1` FOREIGN KEY (`assessor_action1_id`) REFERENCES `h_assessor_action1` (`h_assessor_action1_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `h_assessor_action1_impact_ibfk_2` FOREIGN KEY (`designation_id`) REFERENCES `d_designation` (`designation_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `h_assessor_action1_impact`
--

LOCK TABLES `h_assessor_action1_impact` WRITE;
/*!40000 ALTER TABLE `h_assessor_action1_impact` DISABLE KEYS */;
INSERT INTO `h_assessor_action1_impact` VALUES (1,95,2,'hfghfgh');
/*!40000 ALTER TABLE `h_assessor_action1_impact` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `h_assessor_key_notes_js`
--

DROP TABLE IF EXISTS `h_assessor_key_notes_js`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `h_assessor_key_notes_js` (
  `h_assessor_key_notes_js_id` int(11) NOT NULL,
  `assessor_key_notes_id` int(11) NOT NULL,
  `rec_judgement_instance_id` int(11) DEFAULT NULL,
  KEY `h_assessor_key_notes_js_ibfk_1` (`assessor_key_notes_id`),
  KEY `h_assessor_key_notes_js_ibfk_2` (`rec_judgement_instance_id`),
  CONSTRAINT `h_assessor_key_notes_js_ibfk_1` FOREIGN KEY (`assessor_key_notes_id`) REFERENCES `assessor_key_notes` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `h_assessor_key_notes_js_ibfk_2` FOREIGN KEY (`rec_judgement_instance_id`) REFERENCES `h_cq_js_instance` (`judgement_statement_instance_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `h_assessor_key_notes_js`
--

LOCK TABLES `h_assessor_key_notes_js` WRITE;
/*!40000 ALTER TABLE `h_assessor_key_notes_js` DISABLE KEYS */;
/*!40000 ALTER TABLE `h_assessor_key_notes_js` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `h_assessor_question_answer`
--

DROP TABLE IF EXISTS `h_assessor_question_answer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `h_assessor_question_answer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `question_id` int(10) NOT NULL,
  `option_id` int(10) NOT NULL,
  `user_id` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `question_id` (`question_id`,`option_id`,`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `h_assessor_question_answer`
--

LOCK TABLES `h_assessor_question_answer` WRITE;
/*!40000 ALTER TABLE `h_assessor_question_answer` DISABLE KEYS */;
/*!40000 ALTER TABLE `h_assessor_question_answer` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `h_award_scheme`
--

DROP TABLE IF EXISTS `h_award_scheme`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `h_award_scheme` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `award_scheme_id` int(11) NOT NULL,
  `award_id` int(11) NOT NULL,
  `order` int(11) DEFAULT NULL,
  `tier_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_award_scheme_d_award1_idx` (`award_id`),
  KEY `award_scheme_id` (`award_scheme_id`),
  KEY `idx_order` (`order`),
  CONSTRAINT `fk_award_scheme_d_award1` FOREIGN KEY (`award_id`) REFERENCES `d_award` (`award_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `h_award_scheme_ibfk_1` FOREIGN KEY (`award_scheme_id`) REFERENCES `d_award_scheme` (`award_scheme_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `h_award_scheme`
--

LOCK TABLES `h_award_scheme` WRITE;
/*!40000 ALTER TABLE `h_award_scheme` DISABLE KEYS */;
/*!40000 ALTER TABLE `h_award_scheme` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `h_block_zone_state`
--

DROP TABLE IF EXISTS `h_block_zone_state`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `h_block_zone_state` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `block_id` int(11) NOT NULL,
  `network_id` int(11) NOT NULL,
  `state_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `h_block_zone_state`
--

LOCK TABLES `h_block_zone_state` WRITE;
/*!40000 ALTER TABLE `h_block_zone_state` DISABLE KEYS */;
/*!40000 ALTER TABLE `h_block_zone_state` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `h_client_block`
--

DROP TABLE IF EXISTS `h_client_block`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `h_client_block` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `client_id` int(11) NOT NULL,
  `block_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `h_client_block`
--

LOCK TABLES `h_client_block` WRITE;
/*!40000 ALTER TABLE `h_client_block` DISABLE KEYS */;
/*!40000 ALTER TABLE `h_client_block` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `h_client_cluster`
--

DROP TABLE IF EXISTS `h_client_cluster`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `h_client_cluster` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `client_id` int(11) NOT NULL,
  `cluster_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_h_client_cluster_d_client1_idx` (`client_id`),
  KEY `fk_h_client_cluster_d_cluster1_idx` (`cluster_id`),
  CONSTRAINT `fk_h_client_cluster_d_client1` FOREIGN KEY (`client_id`) REFERENCES `d_client` (`client_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_h_client_cluster_d_cluster1` FOREIGN KEY (`cluster_id`) REFERENCES `d_cluster` (`cluster_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `h_client_cluster`
--

LOCK TABLES `h_client_cluster` WRITE;
/*!40000 ALTER TABLE `h_client_cluster` DISABLE KEYS */;
/*!40000 ALTER TABLE `h_client_cluster` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `h_client_network`
--

DROP TABLE IF EXISTS `h_client_network`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `h_client_network` (
  `client_id` int(11) NOT NULL,
  `network_id` int(11) NOT NULL,
  PRIMARY KEY (`client_id`,`network_id`),
  UNIQUE KEY `client_id` (`client_id`),
  KEY `fk_h_client_network_d_network1_idx` (`network_id`),
  CONSTRAINT `fk_h_client_network_d_client1` FOREIGN KEY (`client_id`) REFERENCES `d_client` (`client_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_h_client_network_d_network1` FOREIGN KEY (`network_id`) REFERENCES `d_network` (`network_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `h_client_network`
--

LOCK TABLES `h_client_network` WRITE;
/*!40000 ALTER TABLE `h_client_network` DISABLE KEYS */;
INSERT INTO `h_client_network` VALUES (897,73),(898,83),(899,82),(900,79),(901,84),(902,86),(903,87),(904,73),(905,90),(906,73),(907,91),(908,79),(909,94),(910,51),(911,96),(912,97),(913,98),(914,87),(915,73),(916,1);
/*!40000 ALTER TABLE `h_client_network` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `h_client_product`
--

DROP TABLE IF EXISTS `h_client_product`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `h_client_product` (
  `transaction_row_id` int(11) NOT NULL AUTO_INCREMENT,
  `client_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `transaction_id` int(11) NOT NULL,
  `transaction_status` varchar(45) DEFAULT NULL,
  `purchase_date` date DEFAULT NULL,
  `payment_mode` int(11) NOT NULL DEFAULT '1',
  `is_approved` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`transaction_row_id`),
  KEY `fk_d_client_id` (`client_id`),
  KEY `fk_d_product_id` (`product_id`),
  KEY `fk_d_payment_mode_id_idx` (`payment_mode`),
  KEY `idx_transactionId` (`transaction_id`),
  CONSTRAINT `fk_d_client_id` FOREIGN KEY (`client_id`) REFERENCES `d_client` (`client_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_d_product_id` FOREIGN KEY (`product_id`) REFERENCES `d_product` (`product_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `h_client_product`
--

LOCK TABLES `h_client_product` WRITE;
/*!40000 ALTER TABLE `h_client_product` DISABLE KEYS */;
/*!40000 ALTER TABLE `h_client_product` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `h_client_province`
--

DROP TABLE IF EXISTS `h_client_province`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `h_client_province` (
  `client_province_id` int(11) NOT NULL AUTO_INCREMENT,
  `province_id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  PRIMARY KEY (`client_province_id`),
  KEY `fk_d_province_h_client_province_idx` (`province_id`),
  KEY `fk_d_client_h_client_province_idx` (`client_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `h_client_province`
--

LOCK TABLES `h_client_province` WRITE;
/*!40000 ALTER TABLE `h_client_province` DISABLE KEYS */;
INSERT INTO `h_client_province` VALUES (1,1,916);
/*!40000 ALTER TABLE `h_client_province` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `h_client_state`
--

DROP TABLE IF EXISTS `h_client_state`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `h_client_state` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `client_id` int(11) NOT NULL,
  `state_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_h_client_state_1_idx` (`client_id`),
  KEY `fk_h_client_state_2_idx` (`state_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `h_client_state`
--

LOCK TABLES `h_client_state` WRITE;
/*!40000 ALTER TABLE `h_client_state` DISABLE KEYS */;
INSERT INTO `h_client_state` VALUES (1,916,1);
/*!40000 ALTER TABLE `h_client_state` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `h_client_zone`
--

DROP TABLE IF EXISTS `h_client_zone`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `h_client_zone` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `client_id` int(11) NOT NULL,
  `zone_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_h_client_zone_1_idx` (`client_id`),
  KEY `fk_h_client_zone_2_idx` (`zone_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `h_client_zone`
--

LOCK TABLES `h_client_zone` WRITE;
/*!40000 ALTER TABLE `h_client_zone` DISABLE KEYS */;
INSERT INTO `h_client_zone` VALUES (1,916,1);
/*!40000 ALTER TABLE `h_client_zone` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `h_cluster_block_zone_state`
--

DROP TABLE IF EXISTS `h_cluster_block_zone_state`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `h_cluster_block_zone_state` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cluster_id` varchar(45) NOT NULL,
  `block_id` varchar(45) NOT NULL,
  `zone_id` varchar(45) NOT NULL,
  `state_id` varchar(45) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_h_cluster_block_zone_state_4_idx` (`state_id`),
  KEY `fk_h_cluster_block_zone_state_3_idx` (`zone_id`),
  KEY `fk_h_cluster_block_zone_state_2_idx` (`block_id`),
  KEY `fk_h_cluster_block_zone_state_1_idx` (`cluster_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `h_cluster_block_zone_state`
--

LOCK TABLES `h_cluster_block_zone_state` WRITE;
/*!40000 ALTER TABLE `h_cluster_block_zone_state` DISABLE KEYS */;
INSERT INTO `h_cluster_block_zone_state` VALUES (1,'1','1','1','1');
/*!40000 ALTER TABLE `h_cluster_block_zone_state` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `h_cq_js_instance`
--

DROP TABLE IF EXISTS `h_cq_js_instance`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `h_cq_js_instance` (
  `judgement_statement_instance_id` int(11) NOT NULL AUTO_INCREMENT,
  `judgement_statement_id` int(11) NOT NULL,
  `core_question_instance_id` int(11) NOT NULL,
  `js_order` int(3) DEFAULT NULL,
  PRIMARY KEY (`judgement_statement_instance_id`),
  KEY `fk_h_cq_js_instance_d_judgement_statement1_idx` (`judgement_statement_id`),
  KEY `fk_h_cq_js_instance_h_kq_cq1_idx` (`core_question_instance_id`),
  CONSTRAINT `fk_h_cq_js_instance_d_judgement_statement1` FOREIGN KEY (`judgement_statement_id`) REFERENCES `d_judgement_statement` (`judgement_statement_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_h_cq_js_instance_h_kq_cq1` FOREIGN KEY (`core_question_instance_id`) REFERENCES `h_kq_cq` (`core_question_instance_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=295 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `h_cq_js_instance`
--

LOCK TABLES `h_cq_js_instance` WRITE;
/*!40000 ALTER TABLE `h_cq_js_instance` DISABLE KEYS */;
INSERT INTO `h_cq_js_instance` VALUES (2,1,4,1),(3,2,4,2),(4,3,4,3),(5,4,5,1),(6,5,5,2),(7,6,5,3),(8,7,6,1),(9,8,6,2),(10,9,6,3),(11,10,7,1),(12,11,7,2),(13,12,7,3),(14,13,8,1),(15,14,8,2),(16,15,8,3),(17,16,9,1),(18,17,9,2),(19,18,9,3),(20,4,10,1),(21,2,10,2),(22,1,10,3),(23,3,10,4),(24,7,11,1),(25,9,11,2),(26,8,11,3),(27,6,11,4),(28,5,11,5),(29,12,12,1),(30,11,12,2),(31,15,12,3),(32,10,12,4),(33,14,12,5),(34,13,12,6),(35,16,13,1),(36,17,13,2),(37,19,13,3),(38,18,13,4),(39,20,13,5),(40,21,14,1),(41,22,14,2),(42,23,14,3),(43,24,14,4),(44,25,14,5),(45,26,15,1),(46,27,15,2),(47,30,15,3),(48,28,15,4),(49,29,15,5),(50,31,16,1),(51,32,16,2),(52,33,16,3),(53,34,16,4),(54,35,16,5),(55,1,18,1),(56,2,18,2),(57,3,18,3),(58,4,18,4),(59,5,25,1),(60,4,25,2),(61,3,25,3),(62,2,25,4),(63,1,25,5),(64,10,26,1),(65,9,26,2),(66,8,26,3),(67,7,26,4),(68,6,26,5),(69,12,27,1),(70,13,27,2),(71,14,27,3),(72,11,27,4),(73,17,28,1),(74,16,28,2),(75,18,28,3),(76,15,28,4),(77,22,29,1),(78,23,29,2),(79,21,29,3),(80,20,29,4),(81,19,29,5),(82,27,30,1),(83,26,30,2),(84,25,30,3),(85,24,30,4),(86,28,31,1),(87,29,31,2),(88,31,31,3),(89,30,31,4),(90,1,17,1),(91,2,17,2),(92,3,17,3),(93,4,17,4),(94,5,17,5),(95,6,17,6),(96,7,17,7),(97,8,17,8),(98,9,17,9),(99,10,17,10),(100,11,17,11),(101,12,17,12),(102,42,38,1),(103,43,38,2),(104,44,38,3),(105,45,38,4),(106,46,38,5),(107,37,37,1),(108,38,37,2),(109,39,37,3),(110,40,37,4),(111,41,37,5),(112,33,36,1),(113,34,36,2),(114,35,36,3),(115,36,36,4),(116,27,35,1),(117,28,35,2),(118,29,35,3),(119,30,35,4),(120,31,35,5),(121,32,35,6),(122,13,34,1),(123,14,34,2),(124,15,34,3),(125,16,34,4),(126,17,34,5),(127,18,34,6),(128,19,34,7),(129,20,34,8),(130,21,34,9),(131,22,33,1),(132,23,33,2),(133,24,33,3),(134,25,33,4),(135,26,33,5),(136,4,39,1),(137,1,39,2),(138,1,41,1),(139,2,41,2),(140,3,41,3),(141,4,41,4),(142,5,41,5),(143,6,41,6),(144,7,41,7),(145,8,41,8),(147,10,41,10),(148,11,41,11),(149,12,41,12),(150,48,41,9),(151,13,42,1),(152,14,42,2),(153,15,42,3),(154,16,42,4),(155,17,42,5),(156,18,42,6),(157,19,42,7),(158,20,42,8),(159,21,42,9),(160,22,43,1),(161,23,43,2),(162,24,43,3),(163,25,43,4),(164,26,43,5),(165,27,44,1),(166,28,44,2),(167,29,44,3),(168,30,44,4),(169,31,44,5),(170,32,44,6),(171,33,45,1),(172,34,45,2),(173,35,45,3),(174,36,45,4),(175,37,46,1),(176,38,46,2),(177,39,46,3),(178,40,46,4),(179,41,46,5),(180,42,47,1),(181,43,47,2),(182,44,47,3),(183,45,47,4),(184,46,47,5),(185,1,48,1),(186,48,48,2),(187,46,48,3),(188,43,48,4),(189,40,48,5),(190,3,49,1),(191,4,49,2),(192,1,50,1),(193,2,50,2),(194,3,50,3),(195,4,50,4),(196,5,50,5),(197,6,50,6),(198,7,50,7),(199,8,50,8),(200,9,50,9),(201,10,50,10),(202,11,50,11),(203,12,50,12),(204,13,51,1),(205,14,51,2),(206,15,51,3),(207,16,51,4),(208,17,51,5),(209,18,51,6),(210,19,51,7),(211,20,51,8),(212,21,51,9),(213,22,52,1),(214,23,52,2),(215,24,52,3),(216,25,52,4),(217,26,52,5),(218,27,53,1),(219,28,53,2),(220,29,53,3),(221,30,53,4),(222,31,53,5),(223,32,53,6),(224,33,54,1),(225,34,54,2),(226,35,54,3),(227,36,54,4),(228,37,55,1),(229,38,55,2),(230,39,55,3),(231,40,55,4),(232,41,55,5),(233,42,56,1),(234,43,56,2),(235,44,56,3),(236,45,56,4),(237,46,56,5),(238,1,57,1),(239,2,57,2),(240,3,57,3),(241,4,57,4),(242,5,57,5),(243,6,57,6),(244,7,57,7),(245,8,57,8),(246,9,57,9),(247,10,57,10),(248,11,57,11),(249,12,57,12),(250,13,58,1),(251,14,58,2),(252,15,58,3),(253,16,58,4),(254,17,58,5),(255,18,58,6),(256,19,58,7),(257,20,58,8),(258,21,58,9),(259,22,59,1),(260,23,59,2),(261,24,59,3),(262,25,59,4),(263,26,59,5),(264,27,60,1),(265,28,60,2),(266,29,60,3),(267,30,60,4),(268,31,60,5),(269,32,60,6),(270,33,61,1),(271,34,61,2),(272,35,61,3),(273,36,61,4),(274,37,62,1),(275,38,62,2),(276,39,62,3),(277,40,62,4),(278,41,62,5),(279,42,63,1),(280,43,63,2),(281,44,63,3),(282,45,63,4),(283,46,63,5),(284,1,64,1),(285,1,66,1),(286,2,66,2),(287,2,67,1),(288,4,67,2),(289,1,76,1),(290,1,78,1),(291,2,78,2),(292,3,78,3),(293,3,64,2),(294,1,79,1);
/*!40000 ALTER TABLE `h_cq_js_instance` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `h_cq_score`
--

DROP TABLE IF EXISTS `h_cq_score`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `h_cq_score` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `d_rating_rating_id` int(11) NOT NULL,
  `core_question_instance_id` int(11) NOT NULL,
  `assessment_id` int(11) DEFAULT NULL,
  `assessor_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_h_kq_score_d_rating1_idx` (`d_rating_rating_id`),
  KEY `fk_h_kq_score_h_kq_cq1_idx` (`core_question_instance_id`),
  KEY `fk_h_cq_score_d_user_idx` (`assessor_id`),
  CONSTRAINT `fk_h_cq_score_d_user` FOREIGN KEY (`assessor_id`) REFERENCES `d_user` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_h_kq_score_d_rating1` FOREIGN KEY (`d_rating_rating_id`) REFERENCES `d_rating` (`rating_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_h_kq_score_h_kq_cq1` FOREIGN KEY (`core_question_instance_id`) REFERENCES `h_kq_cq` (`core_question_instance_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `h_cq_score`
--

LOCK TABLES `h_cq_score` WRITE;
/*!40000 ALTER TABLE `h_cq_score` DISABLE KEYS */;
/*!40000 ALTER TABLE `h_cq_score` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `h_diagnostic_rating_level_scheme`
--

DROP TABLE IF EXISTS `h_diagnostic_rating_level_scheme`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `h_diagnostic_rating_level_scheme` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `diagnostic_id` int(11) NOT NULL,
  `rating_level_scheme_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fkx_diagnostic_id_idx` (`diagnostic_id`),
  KEY `fkx_h_rating_level_scheme_id_idx` (`rating_level_scheme_id`)
) ENGINE=InnoDB AUTO_INCREMENT=57 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `h_diagnostic_rating_level_scheme`
--

LOCK TABLES `h_diagnostic_rating_level_scheme` WRITE;
/*!40000 ALTER TABLE `h_diagnostic_rating_level_scheme` DISABLE KEYS */;
INSERT INTO `h_diagnostic_rating_level_scheme` VALUES (1,1,1),(2,2,1),(3,3,1),(4,4,1),(5,5,1),(6,6,1),(7,7,1),(8,8,1),(9,9,1),(10,10,1),(11,11,1),(12,12,1),(13,13,1),(14,14,1),(15,15,1),(16,16,1),(17,17,1),(18,18,1),(19,19,1),(20,20,1),(21,21,1),(22,22,1),(23,23,1),(24,24,1),(32,32,1),(33,33,1),(36,34,1),(37,35,1),(38,36,1),(39,37,1),(40,38,1),(43,39,1),(44,40,1),(45,41,1),(46,43,1),(48,44,1),(49,45,1),(50,46,1),(51,47,1),(52,48,1),(53,49,1),(54,50,1),(55,51,1),(56,52,1);
/*!40000 ALTER TABLE `h_diagnostic_rating_level_scheme` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `h_diagnostic_rating_scheme`
--

DROP TABLE IF EXISTS `h_diagnostic_rating_scheme`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `h_diagnostic_rating_scheme` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `diagnostic_id` int(11) NOT NULL,
  `rating_id` int(11) NOT NULL,
  `order` int(11) NOT NULL,
  `is_judgestmt_rating` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `ifbk_diag1_idx` (`diagnostic_id`),
  KEY `ifbk_rating_idx` (`rating_id`),
  CONSTRAINT `ifbk_d_diagnostic` FOREIGN KEY (`diagnostic_id`) REFERENCES `d_diagnostic` (`diagnostic_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `ifbk_d_rating` FOREIGN KEY (`rating_id`) REFERENCES `d_rating` (`rating_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `h_diagnostic_rating_scheme`
--

LOCK TABLES `h_diagnostic_rating_scheme` WRITE;
/*!40000 ALTER TABLE `h_diagnostic_rating_scheme` DISABLE KEYS */;
/*!40000 ALTER TABLE `h_diagnostic_rating_scheme` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `h_facilitator_user`
--

DROP TABLE IF EXISTS `h_facilitator_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `h_facilitator_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `assessment_id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `sub_role_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `h_facilitator_user`
--

LOCK TABLES `h_facilitator_user` WRITE;
/*!40000 ALTER TABLE `h_facilitator_user` DISABLE KEYS */;
INSERT INTO `h_facilitator_user` VALUES (1,904,888,1,1893);
/*!40000 ALTER TABLE `h_facilitator_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `h_impact_statement`
--

DROP TABLE IF EXISTS `h_impact_statement`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `h_impact_statement` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `activity_method_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `comments` text NOT NULL,
  `assessment_id` int(11) NOT NULL,
  `action_plan_id` int(11) NOT NULL,
  `statement_id` int(11) NOT NULL,
  `row_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `h_impact_statement`
--

LOCK TABLES `h_impact_statement` WRITE;
/*!40000 ALTER TABLE `h_impact_statement` DISABLE KEYS */;
INSERT INTO `h_impact_statement` VALUES (2,1,'2019-02-20','fdgdfg',905,12024,1,1);
/*!40000 ALTER TABLE `h_impact_statement` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `h_impact_statement_classes`
--

DROP TABLE IF EXISTS `h_impact_statement_classes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `h_impact_statement_classes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `impact_statement_id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `comments` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `h_impact_statement_classes`
--

LOCK TABLES `h_impact_statement_classes` WRITE;
/*!40000 ALTER TABLE `h_impact_statement_classes` DISABLE KEYS */;
/*!40000 ALTER TABLE `h_impact_statement_classes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `h_impact_statement_files`
--

DROP TABLE IF EXISTS `h_impact_statement_files`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `h_impact_statement_files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `file_id` int(11) NOT NULL,
  `impact_statement_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `h_impact_statement_files`
--

LOCK TABLES `h_impact_statement_files` WRITE;
/*!40000 ALTER TABLE `h_impact_statement_files` DISABLE KEYS */;
/*!40000 ALTER TABLE `h_impact_statement_files` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `h_impact_statement_stakeholders`
--

DROP TABLE IF EXISTS `h_impact_statement_stakeholders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `h_impact_statement_stakeholders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `impact_statement_id` int(11) NOT NULL,
  `designation_id` int(11) NOT NULL,
  `comments` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `h_impact_statement_stakeholders`
--

LOCK TABLES `h_impact_statement_stakeholders` WRITE;
/*!40000 ALTER TABLE `h_impact_statement_stakeholders` DISABLE KEYS */;
/*!40000 ALTER TABLE `h_impact_statement_stakeholders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `h_intro_assess_que_option`
--

DROP TABLE IF EXISTS `h_intro_assess_que_option`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `h_intro_assess_que_option` (
  `q_o_id` int(10) NOT NULL AUTO_INCREMENT,
  `o_id` int(10) NOT NULL,
  `q_id` int(10) NOT NULL,
  PRIMARY KEY (`q_o_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `h_intro_assess_que_option`
--

LOCK TABLES `h_intro_assess_que_option` WRITE;
/*!40000 ALTER TABLE `h_intro_assess_que_option` DISABLE KEYS */;
/*!40000 ALTER TABLE `h_intro_assess_que_option` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `h_js_mostly_statements`
--

DROP TABLE IF EXISTS `h_js_mostly_statements`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `h_js_mostly_statements` (
  `mostly_statement_instance_id` int(11) NOT NULL AUTO_INCREMENT,
  `mostly_statements_id` int(11) NOT NULL,
  `judgement_statement_id` int(11) NOT NULL,
  `display_js_txt` int(11) NOT NULL,
  PRIMARY KEY (`mostly_statement_instance_id`),
  KEY `fx_d_mostly_statements_idx` (`mostly_statements_id`),
  KEY `fx_d_judgement_statement_idx` (`judgement_statement_id`),
  CONSTRAINT `fx_d_judgement_statement` FOREIGN KEY (`judgement_statement_id`) REFERENCES `d_judgement_statement` (`judgement_statement_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fx_good_look_like_statement` FOREIGN KEY (`mostly_statements_id`) REFERENCES `d_good_look_like_statement` (`good_looks_like_statement_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=221 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `h_js_mostly_statements`
--

LOCK TABLES `h_js_mostly_statements` WRITE;
/*!40000 ALTER TABLE `h_js_mostly_statements` DISABLE KEYS */;
INSERT INTO `h_js_mostly_statements` VALUES (163,1,1,1),(164,2,1,0),(165,3,2,1),(166,4,2,0),(167,5,3,1),(168,6,3,0),(169,7,4,1),(170,8,4,0),(171,9,5,1),(172,10,5,0),(173,11,6,1),(174,12,6,0),(175,13,7,1),(176,14,7,0),(177,15,8,1),(178,16,8,0),(179,17,9,1),(180,18,9,0),(181,19,10,1),(182,20,10,0),(183,21,11,1),(184,22,11,0),(185,23,12,1),(186,24,12,0),(187,25,13,1),(188,26,14,1),(189,27,15,1),(190,28,16,1),(191,29,17,1),(192,30,18,1),(193,31,19,1),(194,32,20,1),(195,33,21,1),(196,34,22,1),(197,35,23,1),(198,36,24,1),(199,37,25,1),(200,38,26,1),(201,39,27,1),(202,40,28,1),(203,41,29,1),(204,42,30,1),(205,43,31,1),(206,44,32,1),(207,45,33,1),(208,46,34,1),(209,47,35,1),(210,48,36,1),(211,49,37,1),(212,50,38,1),(213,51,39,1),(214,52,40,1),(215,53,41,1),(216,54,42,1),(217,55,43,1),(218,56,44,1),(219,57,45,1),(220,58,46,1);
/*!40000 ALTER TABLE `h_js_mostly_statements` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `h_js_rating_definiton`
--

DROP TABLE IF EXISTS `h_js_rating_definiton`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `h_js_rating_definiton` (
  `js_rating_definiton_id` int(11) NOT NULL AUTO_INCREMENT,
  `definition_id` int(11) NOT NULL,
  `judgement_statement_id` int(11) NOT NULL,
  `level_type` int(11) NOT NULL,
  PRIMARY KEY (`js_rating_definiton_id`),
  KEY `ifx_d_rating_definition_idx` (`definition_id`),
  KEY `ifx_judgement_statement_id_idx` (`judgement_statement_id`),
  CONSTRAINT `ifx_d_rating_definition` FOREIGN KEY (`definition_id`) REFERENCES `d_rating_definition` (`definition_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `ifx_judgement_statement_id` FOREIGN KEY (`judgement_statement_id`) REFERENCES `d_judgement_statement` (`judgement_statement_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=175 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `h_js_rating_definiton`
--

LOCK TABLES `h_js_rating_definiton` WRITE;
/*!40000 ALTER TABLE `h_js_rating_definiton` DISABLE KEYS */;
INSERT INTO `h_js_rating_definiton` VALUES (1,1,1,0),(2,2,1,0),(3,3,1,0),(4,4,1,2),(5,5,1,2),(6,6,1,2),(7,7,2,0),(8,8,2,0),(9,9,2,0),(10,10,2,2),(11,11,2,2),(12,12,2,2),(13,13,3,0),(14,14,3,0),(15,15,3,0),(16,16,3,2),(17,17,3,2),(18,18,3,2),(19,19,4,0),(20,20,4,0),(21,21,4,0),(22,22,4,2),(23,23,4,2),(24,24,4,2),(25,25,5,0),(26,26,5,0),(27,27,5,0),(28,28,5,2),(29,29,5,2),(30,30,5,2),(31,31,6,0),(32,32,6,0),(33,33,6,0),(34,34,6,2),(35,35,6,2),(36,36,6,2),(37,37,7,0),(38,38,7,0),(39,39,7,0),(40,40,7,2),(41,41,7,2),(42,42,7,2),(43,43,8,0),(44,44,8,0),(45,45,8,0),(46,46,8,2),(47,47,8,2),(48,48,8,2),(49,49,9,0),(50,50,9,0),(51,51,9,0),(52,52,9,2),(53,53,9,2),(54,54,9,2),(55,55,10,0),(56,56,10,0),(57,57,10,0),(58,58,10,2),(59,59,10,2),(60,60,10,2),(61,61,11,0),(62,62,11,0),(63,63,11,0),(64,64,11,2),(65,65,11,2),(66,66,11,2),(67,67,12,0),(68,68,12,0),(69,69,12,0),(70,70,12,2),(71,71,12,2),(72,72,12,2),(73,73,13,0),(74,74,13,0),(75,75,13,0),(76,76,14,0),(77,77,14,0),(78,78,14,0),(79,79,15,0),(80,80,15,0),(81,81,15,0),(82,82,16,0),(83,83,16,0),(84,84,16,0),(85,85,17,0),(86,86,17,0),(87,87,17,0),(88,88,18,0),(89,89,18,0),(90,90,18,0),(91,91,19,0),(92,92,19,0),(93,93,19,0),(94,94,20,0),(95,95,20,0),(96,96,20,0),(97,97,21,0),(98,98,21,0),(99,99,21,0),(100,100,22,0),(101,101,22,0),(102,102,22,0),(103,103,23,0),(104,104,23,0),(105,105,23,0),(106,106,24,0),(107,107,24,0),(108,108,24,0),(109,109,25,0),(110,110,25,0),(111,111,25,0),(112,112,26,0),(113,113,26,0),(114,114,26,0),(115,115,27,0),(116,116,27,0),(117,117,27,0),(118,118,28,0),(119,119,28,0),(120,120,28,0),(121,121,29,0),(122,122,29,0),(123,123,29,0),(124,124,30,0),(125,125,30,0),(126,126,30,0),(127,127,31,0),(128,128,31,0),(129,129,31,0),(130,130,32,0),(131,131,32,0),(132,132,32,0),(133,133,33,0),(134,134,33,0),(135,135,33,0),(136,136,34,0),(137,137,34,0),(138,138,34,0),(139,139,35,0),(140,140,35,0),(141,141,35,0),(142,142,36,0),(143,143,36,0),(144,144,36,0),(145,145,37,0),(146,146,37,0),(147,147,37,0),(148,148,38,0),(149,149,38,0),(150,150,38,0),(151,151,39,0),(152,152,39,0),(153,153,39,0),(154,154,40,0),(155,155,40,0),(156,156,40,0),(157,157,41,0),(158,158,41,0),(159,159,41,0),(160,160,42,0),(161,161,42,0),(162,162,42,0),(163,163,43,0),(164,164,43,0),(165,165,43,0),(166,166,44,0),(167,167,44,0),(168,168,44,0),(169,169,45,0),(170,170,45,0),(171,171,45,0),(172,172,46,0),(173,173,46,0),(174,174,46,0);
/*!40000 ALTER TABLE `h_js_rating_definiton` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `h_jstatement_recommendation`
--

DROP TABLE IF EXISTS `h_jstatement_recommendation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `h_jstatement_recommendation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `judgement_statement_id` int(11) NOT NULL,
  `recommendation_id` int(11) NOT NULL,
  `thought_id` int(11) NOT NULL,
  `isActive` tinyint(1) DEFAULT NULL,
  `rating_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_h_jstatement_recommendation_d_judgement_statement1_idx` (`judgement_statement_id`),
  KEY `fk_h_jstatement_recommendation_d_recommendation1_idx` (`recommendation_id`),
  KEY `fk_h_jstatement_recommendation_d_thought1_idx` (`thought_id`),
  KEY `fk_h_jstatement_recommendation_d_rating1_idx` (`rating_id`),
  CONSTRAINT `fk_h_jstatement_recommendation_d_judgement_statement1` FOREIGN KEY (`judgement_statement_id`) REFERENCES `d_judgement_statement` (`judgement_statement_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_h_jstatement_recommendation_d_rating1` FOREIGN KEY (`rating_id`) REFERENCES `d_rating` (`rating_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_h_jstatement_recommendation_d_recommendation1` FOREIGN KEY (`recommendation_id`) REFERENCES `d_recommendation` (`recommendation_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_h_jstatement_recommendation_d_thought1` FOREIGN KEY (`thought_id`) REFERENCES `del_d_thought` (`thought_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `h_jstatement_recommendation`
--

LOCK TABLES `h_jstatement_recommendation` WRITE;
/*!40000 ALTER TABLE `h_jstatement_recommendation` DISABLE KEYS */;
/*!40000 ALTER TABLE `h_jstatement_recommendation` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `h_kd_kq_sq`
--

DROP TABLE IF EXISTS `h_kd_kq_sq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `h_kd_kq_sq` (
  `kd_kq_sq_id` int(11) NOT NULL AUTO_INCREMENT,
  `key_domain_question_id` int(11) NOT NULL,
  `sub_question_id` varchar(20) NOT NULL,
  `kpa_id` int(11) NOT NULL,
  PRIMARY KEY (`kd_kq_sq_id`),
  KEY `ifx_key_domain_id_idx` (`key_domain_question_id`),
  KEY `ifx_sub_question_id_idx` (`sub_question_id`),
  KEY `ifx_kpa_id_idx` (`kpa_id`),
  CONSTRAINT `ifx_key_domain_id` FOREIGN KEY (`key_domain_question_id`) REFERENCES `del_d_key_domain_questions` (`key_domain_question_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `ifx_kpa_id` FOREIGN KEY (`kpa_id`) REFERENCES `d_kpa` (`kpa_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=214 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `h_kd_kq_sq`
--

LOCK TABLES `h_kd_kq_sq` WRITE;
/*!40000 ALTER TABLE `h_kd_kq_sq` DISABLE KEYS */;
INSERT INTO `h_kd_kq_sq` VALUES (1,1,'NA',1),(2,2,'NA',1),(3,3,'NA',1),(4,4,'1',1),(5,4,'2',1),(6,5,'3',1),(7,5,'4',1),(8,5,'5',1),(9,6,'6',1),(10,6,'7',1),(11,6,'8',1),(12,6,'9',1),(13,8,'10',1),(14,12,'11',1),(15,12,'12',1),(16,12,'13',1),(17,12,'14',1),(18,13,'15',1),(19,15,'16',1),(20,15,'17',1),(21,15,'18',1),(22,15,'19',1),(23,16,'20',1),(24,16,'21',1),(25,16,'22',1),(26,16,'23',1),(27,18,'24',1),(28,18,'25',1),(29,18,'26',1),(30,18,'27',1),(31,19,'28',1),(32,19,'29',1),(33,19,'30',1),(34,19,'31',1),(35,20,'32',1),(36,20,'33',1),(37,20,'34',1),(38,20,'35',1),(39,21,'36',1),(40,21,'37',1),(41,21,'38',1),(42,21,'39',1),(43,21,'40',1),(44,21,'41',1),(45,22,'42',1),(46,22,'43',1),(47,22,'44',1),(48,22,'45',1),(49,22,'46',1),(50,22,'47',1),(51,22,'48',1),(52,23,'49',1),(53,23,'50',1),(54,24,'51',1),(55,24,'52',1),(56,24,'53',1),(57,25,'54',1),(58,25,'55',1),(59,25,'56',1),(60,25,'57',1),(61,25,'58',1),(62,26,'59',1),(63,26,'60',1),(64,26,'61',1),(65,26,'62',1),(66,27,'63',1),(67,28,'64',1),(68,28,'65',1),(69,28,'66',1),(70,28,'67',1),(71,29,'68',1),(72,29,'69',1),(73,29,'70',1),(74,31,'71',1),(75,31,'72',1),(76,33,'73',1),(77,33,'74',1),(78,33,'75',1),(79,33,'76',1),(80,34,'77',2),(81,34,'78',2),(82,34,'79',2),(83,34,'80',2),(84,35,'81',2),(85,35,'82',2),(86,35,'83',2),(87,36,'84',2),(88,36,'85',2),(89,36,'86',2),(90,36,'87',2),(91,36,'88',2),(92,39,'89',3),(93,39,'90',3),(94,39,'91',3),(95,39,'92',3),(96,41,'93',3),(97,42,'94',3),(98,42,'95',3),(99,42,'96',3),(100,42,'97',3),(101,43,'98',3),(102,43,'99',3),(103,43,'100',3),(104,43,'101',3),(105,43,'102',3),(106,45,'103',3),(107,45,'104',3),(108,45,'105',3),(109,45,'106',3),(110,46,'107',3),(111,46,'108',3),(112,46,'109',3),(113,46,'110',3),(114,47,'111',4),(115,47,'112',4),(116,48,'113',4),(117,48,'114',4),(118,48,'115',4),(119,48,'116',4),(120,48,'117',4),(121,49,'118',4),(122,49,'119',4),(123,50,'120',4),(124,50,'121',4),(125,50,'122',4),(126,50,'123',4),(127,51,'124',4),(128,51,'125',4),(129,51,'126',4),(130,52,'127',4),(131,52,'128',4),(132,52,'129',4),(133,52,'130',4),(134,53,'131',4),(135,53,'132',4),(136,53,'133',4),(137,53,'134',4),(138,53,'135',4),(139,53,'136',4),(140,56,'137',5),(141,57,'138',5),(142,58,'139',5),(143,59,'140',5),(144,59,'141',5),(145,59,'142',5),(146,59,'143',5),(147,59,'144',5),(148,60,'145',5),(149,60,'146',5),(150,60,'147',5),(151,60,'148',5),(152,61,'149',5),(153,61,'150',5),(154,61,'151',5),(155,61,'152',5),(156,62,'153',5),(157,62,'154',5),(158,62,'155',5),(159,62,'156',5),(160,63,'157',5),(161,64,'158',5),(162,64,'159',5),(163,64,'160',5),(164,64,'161',5),(165,65,'162',5),(166,65,'163',5),(167,65,'164',5),(168,65,'165',5),(169,65,'166',5),(170,66,'167',6),(171,66,'168',6),(172,66,'169',6),(173,67,'170',6),(174,67,'171',6),(175,67,'172',6),(176,67,'173',6),(177,67,'174',6),(178,67,'175',6),(179,68,'176',6),(180,69,'177',6),(181,69,'178',6),(182,69,'179',6),(183,69,'180',6),(184,69,'181',6),(185,69,'182',6),(186,70,'183',6),(187,70,'184',6),(188,70,'185',6),(189,70,'186',6),(190,70,'187',6),(191,70,'188',6),(192,74,'189',6),(193,74,'190',6),(194,74,'191',6),(195,74,'192',6),(196,74,'193',6),(197,74,'194',6),(198,74,'195',6),(199,74,'196',6),(200,76,'197',7),(201,76,'198',7),(202,76,'199',7),(203,76,'200',7),(204,76,'201',7),(205,76,'202',7),(206,7,'NA',1),(207,9,'NA',1),(208,10,'NA',1),(209,11,'NA',1),(210,14,'NA',1),(211,17,'NA',1),(212,30,'NA',1),(213,32,'NA',1);
/*!40000 ALTER TABLE `h_kd_kq_sq` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `h_kpa_diagnostic`
--

DROP TABLE IF EXISTS `h_kpa_diagnostic`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `h_kpa_diagnostic` (
  `kpa_instance_id` int(11) NOT NULL AUTO_INCREMENT,
  `kpa_id` int(11) NOT NULL,
  `diagnostic_id` int(11) NOT NULL,
  `kpa_order` int(3) DEFAULT NULL,
  PRIMARY KEY (`kpa_instance_id`),
  KEY `fk_h_kpa_diagnostic_d_kpa1_idx` (`kpa_id`),
  KEY `fk_h_kpa_diagnostic_d_diagnostic1_idx` (`diagnostic_id`),
  CONSTRAINT `fk_h_kpa_diagnostic_d_diagnostic1` FOREIGN KEY (`diagnostic_id`) REFERENCES `d_diagnostic` (`diagnostic_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_h_kpa_diagnostic_d_kpa1` FOREIGN KEY (`kpa_id`) REFERENCES `d_kpa` (`kpa_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=234 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `h_kpa_diagnostic`
--

LOCK TABLES `h_kpa_diagnostic` WRITE;
/*!40000 ALTER TABLE `h_kpa_diagnostic` DISABLE KEYS */;
INSERT INTO `h_kpa_diagnostic` VALUES (210,1,43,1),(211,2,43,2),(212,3,43,3),(213,4,43,4),(214,5,43,5),(215,6,43,6),(216,7,43,7),(218,1,44,1),(219,2,44,2),(220,1,45,1),(221,1,46,1),(222,3,45,2),(223,5,45,3),(224,7,45,4),(225,6,45,5),(226,1,47,1),(227,3,47,2),(228,5,48,1),(229,1,49,1),(230,1,50,2),(231,2,50,1),(232,1,51,1),(233,1,52,1);
/*!40000 ALTER TABLE `h_kpa_diagnostic` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `h_kpa_instance_score`
--

DROP TABLE IF EXISTS `h_kpa_instance_score`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `h_kpa_instance_score` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `d_rating_rating_id` int(11) NOT NULL,
  `kpa_instance_id` int(11) NOT NULL,
  `assessment_id` int(11) DEFAULT NULL,
  `assessor_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_h_kpa_instance_score_d_rating1_idx` (`d_rating_rating_id`),
  KEY `fk_h_kpa_instance_score_h_kpa_diagnostic1_idx` (`kpa_instance_id`),
  KEY `fk_h_kpa_instance_score_d_user_idx` (`assessor_id`),
  CONSTRAINT `fk_h_kpa_instance_score_d_rating1` FOREIGN KEY (`d_rating_rating_id`) REFERENCES `d_rating` (`rating_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_h_kpa_instance_score_d_user` FOREIGN KEY (`assessor_id`) REFERENCES `d_user` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_h_kpa_instance_score_h_kpa_diagnostic1` FOREIGN KEY (`kpa_instance_id`) REFERENCES `h_kpa_diagnostic` (`kpa_instance_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `h_kpa_instance_score`
--

LOCK TABLES `h_kpa_instance_score` WRITE;
/*!40000 ALTER TABLE `h_kpa_instance_score` DISABLE KEYS */;
/*!40000 ALTER TABLE `h_kpa_instance_score` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `h_kpa_js_instance`
--

DROP TABLE IF EXISTS `h_kpa_js_instance`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `h_kpa_js_instance` (
  `judgement_statement_instance_id` int(11) NOT NULL AUTO_INCREMENT,
  `judgement_statement_id` int(11) NOT NULL,
  `kpa_instance_id` int(11) NOT NULL,
  `js_order` int(3) DEFAULT NULL,
  PRIMARY KEY (`judgement_statement_instance_id`),
  KEY `fk_h_cq_js_instance_d_judgement_statement1_idx` (`judgement_statement_id`)
) ENGINE=InnoDB AUTO_INCREMENT=219 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `h_kpa_js_instance`
--

LOCK TABLES `h_kpa_js_instance` WRITE;
/*!40000 ALTER TABLE `h_kpa_js_instance` DISABLE KEYS */;
INSERT INTO `h_kpa_js_instance` VALUES (11,44,26,1),(12,46,26,2),(13,43,26,3),(14,41,27,2),(15,42,27,3),(17,38,28,1),(18,39,28,2),(19,40,28,3),(20,35,29,1),(21,36,29,2),(22,37,29,3),(23,32,30,1),(24,33,30,2),(25,34,30,3),(26,29,31,1),(27,30,31,2),(28,31,31,3),(38,3,25,3),(39,21,25,4),(40,16,25,5),(43,4,25,2),(44,6,25,1),(45,2,27,1),(46,4,61,2),(47,3,61,3),(48,2,61,4),(49,1,61,5),(50,6,61,1),(51,16,57,1),(52,14,57,2),(53,13,57,3),(54,42,58,1),(55,46,58,2),(56,45,58,3),(57,11,59,1),(58,10,59,2),(59,9,59,3),(60,19,60,1),(61,17,60,2),(62,16,60,3),(63,3,62,1),(64,2,62,2),(65,1,62,3),(66,6,52,1),(67,5,52,2),(68,4,52,3),(69,11,53,1),(70,10,53,2),(71,9,53,3),(72,12,54,1),(73,11,54,2),(74,10,54,3),(75,18,55,1),(76,16,55,2),(77,14,55,3),(78,3,63,1),(79,2,63,2),(80,1,63,3),(81,3,67,1),(82,2,67,2),(83,1,67,3),(84,47,68,2),(85,46,68,3),(86,6,68,1),(87,8,69,1),(88,6,69,2),(89,4,69,3),(90,16,70,1),(91,13,70,2),(92,14,70,3),(93,17,71,1),(94,15,71,2),(95,14,71,3),(96,3,64,1),(97,1,64,2),(98,2,64,3),(99,3,65,1),(100,2,65,2),(101,1,65,3),(102,4,66,1),(103,3,66,2),(104,2,66,3),(105,1,66,4),(106,3,72,1),(107,2,72,2),(108,1,72,3),(109,3,73,1),(110,2,73,2),(111,1,73,3),(112,1,77,1),(113,3,77,2),(114,2,77,3),(115,5,78,1),(116,7,78,2),(117,8,78,3),(118,4,78,4),(119,18,79,1),(120,19,79,2),(121,15,79,3),(122,16,79,4),(123,9,80,1),(124,10,80,2),(125,8,80,3),(126,12,80,4),(127,1,81,1),(128,2,81,2),(129,3,81,3),(130,4,81,4),(131,5,81,5),(132,6,81,6),(133,7,81,7),(134,8,81,8),(135,9,81,9),(136,10,81,10),(137,11,81,11),(138,12,81,12),(139,13,82,1),(140,14,82,2),(141,15,82,3),(142,16,82,4),(143,17,82,5),(144,18,82,6),(145,19,82,7),(146,20,82,8),(147,21,82,9),(148,22,83,1),(149,23,83,2),(150,24,83,3),(151,25,83,4),(152,26,83,5),(153,27,84,1),(154,22,84,2),(155,29,84,3),(156,30,84,4),(157,31,84,5),(158,32,84,6),(159,33,85,1),(160,34,85,2),(161,35,85,3),(162,36,85,4),(163,37,86,1),(164,38,86,2),(165,39,86,3),(166,40,86,4),(167,41,86,5),(168,42,87,1),(169,43,87,2),(170,44,87,3),(171,45,87,4),(172,46,87,5),(173,3,92,1),(174,4,92,2),(175,2,92,3),(176,1,92,4),(177,1,93,1),(178,7,93,2),(179,19,93,3),(180,21,93,4),(181,22,93,5),(182,40,94,1),(183,41,94,2),(184,39,94,3),(185,3,95,1),(186,2,95,2),(187,1,95,3),(188,9,96,1),(189,10,96,2),(190,8,96,3),(191,23,97,1),(192,22,97,2),(193,25,97,3),(194,24,97,4),(195,1,98,1),(196,2,98,2),(197,3,98,3),(198,4,98,4),(199,5,98,5),(200,6,98,6),(201,7,98,7),(202,8,98,8),(203,9,98,9),(204,10,98,10),(205,11,98,11),(206,12,98,12),(207,13,99,1),(208,14,99,2),(209,15,99,3),(210,17,99,5),(211,18,99,6),(212,19,99,7),(213,16,99,4),(214,20,99,8),(215,21,99,9),(216,1,100,1),(217,2,100,2),(218,3,100,3);
/*!40000 ALTER TABLE `h_kpa_js_instance` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `h_kpa_kq`
--

DROP TABLE IF EXISTS `h_kpa_kq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `h_kpa_kq` (
  `key_question_instance_id` int(11) NOT NULL AUTO_INCREMENT,
  `key_question_id` int(11) NOT NULL,
  `kpa_instance_id` int(11) NOT NULL,
  `kq_order` int(3) DEFAULT NULL,
  PRIMARY KEY (`key_question_instance_id`),
  KEY `fk_h_diag_instance_kq_d_key_question1_idx` (`key_question_id`),
  KEY `fk_h_diag_instance_kq_h_kpa_diagnostic1_idx` (`kpa_instance_id`),
  CONSTRAINT `fk_h_diag_instance_kq_d_key_question1` FOREIGN KEY (`key_question_id`) REFERENCES `d_key_question` (`key_question_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_h_diag_instance_kq_h_kpa_diagnostic1` FOREIGN KEY (`kpa_instance_id`) REFERENCES `h_kpa_diagnostic` (`kpa_instance_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=87 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `h_kpa_kq`
--

LOCK TABLES `h_kpa_kq` WRITE;
/*!40000 ALTER TABLE `h_kpa_kq` DISABLE KEYS */;
INSERT INTO `h_kpa_kq` VALUES (11,0,153,1),(12,0,154,1),(13,0,155,1),(14,0,156,1),(15,0,157,1),(16,0,158,1),(17,0,159,1),(18,0,160,1),(19,0,161,1),(20,0,162,1),(21,0,163,1),(22,0,164,1),(23,0,165,1),(24,0,168,1),(25,0,169,1),(26,0,170,1),(27,0,171,1),(28,0,172,1),(29,0,173,1),(30,0,174,1),(31,0,175,1),(32,0,176,1),(33,0,177,1),(34,0,178,1),(35,0,179,1),(36,0,180,1),(37,0,181,1),(38,0,182,1),(39,0,183,1),(40,0,184,1),(41,0,185,1),(42,0,186,1),(43,0,187,1),(44,0,188,1),(45,0,189,1),(46,0,190,1),(47,0,191,1),(48,0,194,1),(49,0,195,1),(50,0,196,1),(51,0,197,1),(52,0,198,1),(53,0,199,1),(54,0,200,1),(55,0,201,1),(56,0,202,1),(57,0,203,1),(58,0,204,1),(59,0,205,1),(60,0,206,1),(61,0,207,1),(62,0,208,1),(63,0,209,1),(64,0,210,1),(65,0,211,1),(66,0,212,1),(67,0,213,1),(68,0,214,1),(69,0,215,1),(70,0,216,1),(71,0,218,1),(72,0,219,1),(73,0,220,1),(74,0,221,1),(75,0,222,1),(76,0,223,1),(77,0,224,1),(78,0,225,1),(79,0,226,1),(80,0,227,1),(81,0,228,1),(82,0,229,1),(83,0,230,1),(84,0,231,1),(85,0,232,1),(86,0,233,1);
/*!40000 ALTER TABLE `h_kpa_kq` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `h_kq_cq`
--

DROP TABLE IF EXISTS `h_kq_cq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `h_kq_cq` (
  `core_question_instance_id` int(11) NOT NULL AUTO_INCREMENT,
  `core_question_id` int(11) DEFAULT NULL,
  `key_question_instance_id` int(11) NOT NULL,
  `cq_order` int(3) DEFAULT NULL,
  PRIMARY KEY (`core_question_instance_id`),
  KEY `fk_h_kq_cq_h_diag_instance_kq1_idx` (`key_question_instance_id`),
  KEY `fk_d_core_question_idx` (`core_question_id`),
  CONSTRAINT `fk_d_core_question` FOREIGN KEY (`core_question_id`) REFERENCES `d_core_question` (`core_question_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_h_kq_cq_h_diag_instance_kq1` FOREIGN KEY (`key_question_instance_id`) REFERENCES `h_kpa_kq` (`key_question_instance_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=80 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `h_kq_cq`
--

LOCK TABLES `h_kq_cq` WRITE;
/*!40000 ALTER TABLE `h_kq_cq` DISABLE KEYS */;
INSERT INTO `h_kq_cq` VALUES (4,0,11,1),(5,0,12,1),(6,0,13,1),(7,0,14,1),(8,0,15,1),(9,0,16,1),(10,0,17,1),(11,0,18,1),(12,0,19,1),(13,0,20,1),(14,0,21,1),(15,0,22,1),(16,0,23,1),(17,0,24,1),(18,0,25,1),(19,0,26,1),(20,0,27,1),(21,0,28,1),(22,0,29,1),(23,0,30,1),(24,0,31,1),(25,0,32,1),(26,0,33,1),(27,0,34,1),(28,0,35,1),(29,0,36,1),(30,0,37,1),(31,0,38,1),(32,0,39,1),(33,0,40,1),(34,0,41,1),(35,0,42,1),(36,0,43,1),(37,0,44,1),(38,0,45,1),(39,0,46,1),(40,0,47,1),(41,0,48,1),(42,0,49,1),(43,0,50,1),(44,0,51,1),(45,0,52,1),(46,0,53,1),(47,0,54,1),(48,0,55,1),(49,0,56,1),(50,0,57,1),(51,0,58,1),(52,0,59,1),(53,0,60,1),(54,0,61,1),(55,0,62,1),(56,0,63,1),(57,0,64,1),(58,0,65,1),(59,0,66,1),(60,0,67,1),(61,0,68,1),(62,0,69,1),(63,0,70,1),(64,0,71,1),(65,0,72,1),(66,0,73,1),(67,0,74,1),(68,0,75,1),(69,0,76,1),(70,0,77,1),(71,0,78,1),(72,0,79,1),(73,0,80,1),(74,0,81,1),(75,0,82,1),(76,0,83,1),(77,0,84,1),(78,0,85,1),(79,0,86,1);
/*!40000 ALTER TABLE `h_kq_cq` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `h_kq_instance_score`
--

DROP TABLE IF EXISTS `h_kq_instance_score`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `h_kq_instance_score` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `d_rating_rating_id` int(11) NOT NULL,
  `key_question_instance_id` int(11) NOT NULL,
  `assessment_id` int(11) DEFAULT NULL,
  `assessor_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_h_cq_instance_score_d_rating1_idx` (`d_rating_rating_id`),
  KEY `fk_h_kq_instance_score_h_diag_instance_kq1_idx` (`key_question_instance_id`),
  KEY `fk_h_kq_instance_score_d_user_idx` (`assessor_id`),
  CONSTRAINT `fk_h_cq_instance_score_d_rating1` FOREIGN KEY (`d_rating_rating_id`) REFERENCES `d_rating` (`rating_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_h_kq_instance_score_d_user` FOREIGN KEY (`assessor_id`) REFERENCES `d_user` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_h_kq_instance_score_h_diag_instance_kq1` FOREIGN KEY (`key_question_instance_id`) REFERENCES `h_kpa_kq` (`key_question_instance_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `h_kq_instance_score`
--

LOCK TABLES `h_kq_instance_score` WRITE;
/*!40000 ALTER TABLE `h_kq_instance_score` DISABLE KEYS */;
/*!40000 ALTER TABLE `h_kq_instance_score` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `h_lang_trans_diagnostics_details`
--

DROP TABLE IF EXISTS `h_lang_trans_diagnostics_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `h_lang_trans_diagnostics_details` (
  `trans_diagnostics_details_id` int(11) NOT NULL AUTO_INCREMENT,
  `lang_translation_id` int(11) NOT NULL,
  `date_created` datetime DEFAULT NULL,
  `isPublished` int(11) NOT NULL,
  `date_published` datetime DEFAULT NULL,
  `create_user_id` int(11) NOT NULL,
  `publish_user_id` int(11) NOT NULL,
  PRIMARY KEY (`trans_diagnostics_details_id`),
  UNIQUE KEY `lang_translation_id` (`lang_translation_id`)
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `h_lang_trans_diagnostics_details`
--

LOCK TABLES `h_lang_trans_diagnostics_details` WRITE;
/*!40000 ALTER TABLE `h_lang_trans_diagnostics_details` DISABLE KEYS */;
INSERT INTO `h_lang_trans_diagnostics_details` VALUES (1,10,'2018-06-14 14:56:16',0,NULL,1,0),(2,11,'2018-06-14 17:49:14',0,NULL,1,0),(3,12,'2018-06-14 19:13:06',0,NULL,1,0),(4,13,'2018-06-18 11:59:59',0,NULL,1,0),(5,14,'2018-06-18 12:00:48',0,NULL,1,0),(6,15,'2018-06-18 12:06:19',0,NULL,1,0),(7,16,'2018-06-18 12:45:21',0,NULL,1,0),(8,20,'2018-06-18 17:12:56',1,'2018-06-19 16:27:32',1,1),(9,67,'2018-06-19 16:31:22',0,NULL,1,0),(10,68,'2018-06-19 16:46:19',0,NULL,1,0),(11,69,'2018-06-19 16:56:48',0,NULL,1,0),(12,70,'2018-06-19 18:24:40',1,'2018-06-21 15:59:31',1,1),(13,71,'2018-06-19 18:29:31',0,NULL,1,0),(14,72,'2018-06-19 18:58:44',1,'2018-06-19 19:34:51',1,1),(15,73,'2018-06-19 19:02:07',1,'2018-06-19 19:12:25',1,1),(16,74,'2018-06-19 19:36:46',1,'2018-06-20 12:53:43',1,1),(17,75,'2018-06-19 19:42:18',1,'2018-06-19 19:43:59',1,1),(18,77,'2018-06-20 12:51:45',0,NULL,1,0),(19,78,'2018-06-20 13:04:44',1,'2018-06-20 13:05:42',1,1),(20,79,'2018-06-20 19:29:10',1,'2018-06-20 20:04:18',1,1),(21,80,'2018-06-21 15:18:38',1,'2018-06-21 15:20:53',1,1),(22,81,'2018-06-21 15:25:34',0,NULL,1,0),(23,82,'2018-06-21 16:14:29',0,NULL,1,0),(24,83,'2018-06-22 12:45:14',0,NULL,1,0),(32,562,'2018-06-27 11:20:24',1,'2018-06-27 11:50:24',1,1),(33,563,'2018-06-27 21:50:38',1,'2018-06-27 21:52:44',1,1),(34,568,'2018-06-28 11:45:48',1,'2018-06-28 16:16:55',1,1),(35,569,'2018-06-28 11:48:01',0,NULL,1,0),(36,570,'2018-06-28 11:49:25',1,'2018-06-28 11:51:13',1,1),(37,571,'2018-06-28 11:54:26',0,NULL,1,0),(38,572,'2018-06-28 14:12:54',0,NULL,1,0),(39,582,'2018-06-30 11:01:24',1,'2018-06-30 11:22:14',1,1),(40,584,'2018-06-30 14:03:38',1,'2018-06-30 14:04:15',1,1),(41,585,'2018-06-30 14:13:56',1,'2018-06-30 14:23:10',1,1),(42,590,'2018-07-02 17:43:54',1,'2018-07-02 17:50:47',1,1),(43,648,'2018-12-05 12:01:40',0,NULL,1,0),(44,649,'2018-12-05 12:05:30',0,NULL,1,0),(45,650,'2018-12-06 17:12:54',1,'2018-12-06 17:13:37',1,1),(46,651,'2018-12-07 11:36:51',0,NULL,1,0),(47,652,'2018-12-07 11:49:50',0,NULL,1,0),(48,653,'2018-12-07 12:00:48',0,NULL,1,0),(49,654,'2019-01-10 11:17:43',0,NULL,1,0),(50,655,'2019-01-10 11:36:59',1,'2019-01-10 11:38:23',1,1),(51,656,'2019-01-24 11:13:42',0,NULL,1,0);
/*!40000 ALTER TABLE `h_lang_trans_diagnostics_details` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `h_lang_trans_diagnostics_details_back`
--

DROP TABLE IF EXISTS `h_lang_trans_diagnostics_details_back`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `h_lang_trans_diagnostics_details_back` (
  `trans_diagnostics_details_id` int(11) NOT NULL AUTO_INCREMENT,
  `lang_translation_id` int(11) NOT NULL,
  `date_created` datetime DEFAULT NULL,
  `isPublished` int(11) NOT NULL,
  `date_published` datetime DEFAULT NULL,
  `create_user_id` int(11) NOT NULL,
  `publish_user_id` int(11) NOT NULL,
  PRIMARY KEY (`trans_diagnostics_details_id`),
  UNIQUE KEY `lang_translation_id` (`lang_translation_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `h_lang_trans_diagnostics_details_back`
--

LOCK TABLES `h_lang_trans_diagnostics_details_back` WRITE;
/*!40000 ALTER TABLE `h_lang_trans_diagnostics_details_back` DISABLE KEYS */;
/*!40000 ALTER TABLE `h_lang_trans_diagnostics_details_back` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `h_lang_translation`
--

DROP TABLE IF EXISTS `h_lang_translation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `h_lang_translation` (
  `lang_translation_id` int(11) NOT NULL AUTO_INCREMENT,
  `equivalence_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `translation_type_id` int(11) NOT NULL,
  `translation_text` text COLLATE utf8_unicode_ci NOT NULL,
  `isActive` int(11) NOT NULL,
  `parent_lang_translation_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`lang_translation_id`),
  UNIQUE KEY `equivalence_id` (`equivalence_id`,`language_id`) USING BTREE,
  KEY `fk_h_lang_translation_d_translation_type_idxx` (`translation_type_id`),
  KEY `fk_h_lang_translation_d_languagee` (`language_id`),
  CONSTRAINT `fk_h_lang_translation_d_languagee` FOREIGN KEY (`language_id`) REFERENCES `d_language` (`language_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_h_lang_translation_d_translation_typee` FOREIGN KEY (`translation_type_id`) REFERENCES `del_d_translation_type` (`translation_type_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=657 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `h_lang_translation`
--

LOCK TABLES `h_lang_translation` WRITE;
/*!40000 ALTER TABLE `h_lang_translation` DISABLE KEYS */;
INSERT INTO `h_lang_translation` VALUES (1,1,9,1,'Enabling Resources of\r\nSchool: Availability,\r\nAdequacy and Usability',1,NULL),(2,2,9,1,'Teaching-learning and\r\nAssessment',1,NULL),(3,3,9,1,'Learners’ Progress,\r\nAttainment and Development',1,NULL),(4,4,9,1,'Managing Teacher\r\nPerformance and Professional Development',1,NULL),(5,5,9,1,'School Leadership and\r\nManagement',1,NULL),(6,6,9,1,'Inclusion, Health and\r\nSafety',1,NULL),(7,7,9,1,'Productive Community\r\nParticipation',1,NULL),(19,17,9,1,'Enabling Resources of\r\nSchool: Availability,\r\nAdequacy and Usability',1,NULL),(21,111,9,4,'School Premises',1,NULL),(22,112,9,4,'Playground with Sports Equipment and Materials',1,NULL),(23,113,9,4,'Classrooms and Other Rooms',1,NULL),(24,114,9,4,'Electricity and Gadgets',1,NULL),(25,115,9,4,'Library',1,NULL),(26,116,9,4,'Laboratory',1,NULL),(27,117,9,4,'Computer (where provisioning exist)',1,NULL),(28,118,9,4,'Ramp',1,NULL),(29,119,9,4,'Mid Day Meal, Kitchen and Utensils',1,NULL),(30,120,9,4,'Drinking Water',1,NULL),(31,121,9,4,'Hand Wash Facilities',1,NULL),(32,122,9,4,'Toilets',1,NULL),(33,123,9,4,'Teachers’ Understanding of Learners',1,NULL),(34,124,9,4,'Subject and Pedagogical Knowledge of Teachers',1,NULL),(35,125,9,4,'Planning for Teaching',1,NULL),(36,126,9,4,'Enabling Learning Environment',1,NULL),(37,127,9,4,'Teaching-learning Process',1,NULL),(38,128,9,4,'Class Management',1,NULL),(39,129,9,4,'Learners’ Assessment',1,NULL),(40,130,9,4,'Utilization of Teaching-learning Resources',1,NULL),(41,131,9,4,'Teachers’ Reflection on their own Teaching-learning Practices',1,NULL),(42,132,9,4,'Learners’ Attendance',1,NULL),(43,133,9,4,'Learners’ Participation & Engagement',1,NULL),(44,134,9,4,'Learners’ Progress',1,NULL),(45,135,9,4,'Learners’ Personal and Social Development',1,NULL),(46,136,9,4,'Learners’ Attainment',1,NULL),(47,137,9,4,'Orientation of New Teachers',1,NULL),(48,138,9,4,'Teachers’ Attendance',1,NULL),(49,139,9,4,'Assigning Responsibilities and Defining Performance Goal',1,NULL),(50,140,9,4,'Teachers’ Preparedness for Curricular Expectations',1,NULL),(51,141,9,4,'Monitoring of Teachers Performance',1,NULL),(52,142,9,4,'Teachers’ Professional Development',1,NULL),(53,143,9,4,'Building Vision and Setting Direction',1,NULL),(54,144,9,4,'Leading Change and Improvement',1,NULL),(55,145,9,4,'Leading Teaching-learning',1,NULL),(56,146,9,4,'Leading Management of School',1,NULL),(57,147,9,4,'Inclusive Culture',1,NULL),(58,148,9,4,'Inclusion of Children With Special Needs (CWSN)',1,NULL),(59,149,9,4,'Physical Safety',1,NULL),(60,150,9,4,'Psychological Safety',1,NULL),(61,151,9,4,'Health and Hygiene',1,NULL),(62,152,9,4,'Organisation and Management of SMC/ SDMC',1,NULL),(63,153,9,4,'Role in School Improvement',1,NULL),(64,154,9,4,'School – Community Linkages',1,NULL),(65,155,9,4,'Community as Learning Resource',1,NULL),(66,156,9,4,'Empowering Community',1,NULL),(76,166,9,4,'deepak',1,NULL),(84,173,9,9,'Total area of school premises with covered area (square metre)',1,NULL),(85,174,9,9,'Area of playground, if available (in square metre)',1,NULL),(86,175,9,9,'Area of open space in the school, if there is no playground (in square metre)',1,NULL),(87,176,9,9,'Classes taught in school:',1,NULL),(88,177,9,9,'Total enrolment in the school_(as on 30th September)',1,NULL),(89,178,9,9,'The condition of school building:',1,NULL),(90,179,9,9,'List of games/ sports, art education, work experience and other co-scholastic activities and list equipment/ material available for different activities:',1,NULL),(91,636,9,9,'a) Total number of classrooms in the school',1,NULL),(92,181,9,9,'Number of classrooms where learners sit on mats/ tatputtis:',1,NULL),(93,182,9,9,'Number of classrooms in which learners sit on benches/chairs and have desks:',1,NULL),(94,183,9,9,'Number of learners for whom additional benches/ chairs are required/ mat/ tatputtis are needed, if existing provisions are insufficient:',1,NULL),(95,184,9,9,'Availability of other rooms:',1,NULL),(96,185,9,9,'Is there a separate room for library?',1,NULL),(97,186,9,9,'Number of learners who can sit and read at a time in library',1,NULL),(98,187,9,9,'The library manages by:',1,NULL),(99,188,9,9,'Number of periodicals the school has subscribed to:',1,NULL),(100,189,9,9,'The numbers of books other than dictionaries & encyclopedia per 100 learners in the library',1,NULL),(101,190,9,9,'Laboratory available in the school:',1,NULL),(102,191,9,9,'Number of computers available in the school for:',1,NULL),(103,192,9,9,'Internet facility available in school is used by:',1,NULL),(104,193,9,9,'Availability of other equipment:',1,NULL),(105,194,9,9,'Number of functional toilets available:',1,NULL),(106,195,9,9,'Ratio of number of learners to number of taps/ outlets for:',1,NULL),(107,196,9,9,'Source of drinking water:',1,NULL),(108,197,9,9,'Process of purification of water in school:',1,NULL),(109,198,9,9,'Type of hand-washing facility available:',1,NULL),(110,199,9,9,'a. Whether water is stored in an overhead tank:',1,NULL),(111,200,9,9,'School assembly held in:',1,NULL),(112,201,9,9,'i. Mid Day Meal in the school is:',1,NULL),(113,202,9,9,'Precautions taken to ensure that the food is safe to eat with no chance for insects/reptiles to contaminate the food:',1,NULL),(114,203,9,9,'Is there electricity in school?',1,NULL),(115,204,9,9,'List other rooms (other than classrooms) available in school for different purposes along with the use being made of each room:',1,NULL),(116,205,9,9,'Incentives (free textbooks, free uniform, scholarships, etc.) available for learners:',1,NULL),(117,206,9,9,'How do teachers acquire information about socio-cultural and home background of learners?',1,NULL),(118,207,9,9,'Teachers access to different types of teaching- learning resources:',1,NULL),(119,208,9,9,'On what basis do teachers assess learners\' attitudes, motivation and interest in learning?',1,NULL),(120,209,9,9,'Average school attendance for the current academic year',1,NULL),(121,210,9,9,'Types of rewards (if any) given to learners for punctuality and regular attendance:',1,NULL),(122,211,9,9,'Record of actions to promote regularity and punctuality in attendance',1,NULL),(123,212,9,9,'Are the learners\' attendance registers kept up-to-date?',1,NULL),(124,213,9,9,'a. Is average attendance calculated monthly for every learner?',1,NULL),(125,214,9,9,'Alternative arrangements made for classes which the teachers could not take:',1,NULL),(126,215,9,9,'Is personal hygiene of learners checked and assured by the school?',1,NULL),(127,216,9,9,'List the activities undertaken in the school that help in personal and social development of learners:',1,NULL),(128,217,9,9,'How is learners\' personal development monitored?',1,NULL),(129,218,9,9,'How is learners\' attainment measured and how is the progress ascertained over time?',1,NULL),(130,219,9,9,'Number of teachers in school:',1,NULL),(131,220,9,9,'Number of teachers in position:',1,NULL),(136,222,9,9,'Number of:',1,NULL),(137,223,9,9,'Orientation of new teachers in the school is done by:',1,NULL),(138,224,9,9,'Does the school maintain a record of teachers\' attendance along with reasons for absence?',1,NULL),(139,225,9,9,'School makes alternative arrangements for the classes of absent teachers by:',1,NULL),(140,226,9,9,'Teacher performance is monitored through/ by:',1,NULL),(141,227,9,9,'Mechanisms for teachers\' continuous performance improvement:',1,NULL),(142,228,9,9,'List the duties/ responsibilities assigned to teachers beyond classroom teaching:',1,NULL),(143,229,9,9,'Does the school have a vision/ mission statement?',1,NULL),(144,230,9,9,'Is the School Development Plan (SDP) of previous year available?',1,NULL),(145,231,9,9,'Was the SDP for the previous year implemented?',1,NULL),(146,232,9,9,'(i) What are the areas in which the School Head has received training?',1,NULL),(147,233,9,9,'How does the School Head usually take routine management decisions?',1,NULL),(148,234,9,9,'The directions/ decisions communicated to teachers are clearly understood by:',1,NULL),(149,235,9,9,'How often does the School Head review implementation of the plan and assess the progress made, particularly in the prioritized areas?',1,NULL),(150,236,9,9,'Has the School Head constituted teams for different tasks and made them accountable?',1,NULL),(151,237,9,9,'How does the School Head monitor teachers\' performance?',1,NULL),(152,238,9,9,'How does the School Head monitor learners\' progress in learning?',1,NULL),(153,239,9,9,'Number of learners:',1,NULL),(154,240,9,9,'Number of learners of different categories enrolled in the school:',1,NULL),(155,241,9,9,'a) Number of CWSN in different categories enrolled in the school:',1,NULL),(156,242,9,9,'Number of learners given scholarships in the following categories:',1,NULL),(157,243,9,9,'i. Are resource persons available for CWSN?',1,NULL),(158,244,9,9,'Do you have evacuation plans in the event of fire, earthquake, flood, landscaping, etc.?',1,NULL),(159,245,9,9,'List the committees, if any, dealing with sexual harassment or abuse:',1,NULL),(160,246,9,9,'Has the school arranged for any counselling session for students?',1,NULL),(161,247,9,9,'a. Number of learners who have undergone medical/ health check-up last year',1,NULL),(162,248,9,9,'Number of members of SMC/ SDMC:',1,NULL),(163,249,9,9,'Composition of SMC/ SDMC: <br> (Provide number of representatives for each category in given box)',1,NULL),(164,250,9,9,'Number of meetings organized during the last academic year:',1,NULL),(165,251,9,9,'Average attendance in the meetings organized during the last academic year:',1,NULL),(166,252,9,9,'Number of SMC/ SDMC members who have received training:',1,NULL),(167,253,9,9,'Activities/ areas in which SMC/ SDMC provided support to school last year:',1,NULL),(168,254,9,10,'from',1,NULL),(169,255,9,10,'to',1,NULL),(170,256,9,10,'i. In Primary classes:',1,NULL),(171,257,9,10,'ii. In Upper primary classes:',1,NULL),(172,258,9,10,'iii. In Secondary classes:	',1,NULL),(173,259,9,10,'a. good	',1,NULL),(174,260,9,10,'b. needs minor repair',1,NULL),(175,261,9,10,'c. needs major repair',1,NULL),(176,262,9,10,'d. no building',1,NULL),(177,263,9,10,'b) Number of classrooms with adequate space for learner (SSA/ RMSA norms)',1,NULL),(178,264,9,10,'a. school head',1,NULL),(179,265,9,10,'b. staff',1,NULL),(180,266,9,10,'c. girls',1,NULL),(181,267,9,10,'any other',1,NULL),(182,268,9,10,'if yes, area (sq. metre)',1,NULL),(183,269,9,10,'a. full-time librarian',1,NULL),(184,270,9,10,'b. teacher',1,NULL),(185,271,9,10,'c. School Head',1,NULL),(186,272,9,10,'d. any other arrangement',1,NULL),(187,273,9,10,'a. dictionaries and Encyclopaedias',1,NULL),(188,274,9,10,'b. newspapers',1,NULL),(189,275,9,10,'c. magazines',1,NULL),(190,276,9,10,'d. other Reference Books',1,NULL),(191,277,9,10,'a. integrated science laboratory',1,NULL),(192,278,9,10,'b. separate laboratories for different purposes (demonstrating experiments)',1,NULL),(193,279,9,10,'c. only a corner or almirah for keeping apparatus and equipment',1,NULL),(194,280,9,10,'d. no equipment for conducting experiments',1,NULL),(195,281,9,10,'a. teaching-learning',1,NULL),(196,282,9,10,'b. administration',1,NULL),(197,283,9,10,'c. library',1,NULL),(198,284,9,10,'d. no computer',1,NULL),(199,285,9,10,'a. school head only',1,NULL),(200,286,9,10,'b. teachers only',1,NULL),(201,287,9,10,'c. learners',1,NULL),(202,288,9,10,'d. not available',1,NULL),(203,289,9,10,'a. radio',1,NULL),(204,290,9,10,'b. television',1,NULL),(205,291,9,10,'c. CD/DVD player',1,NULL),(206,292,9,10,'d. LCD projector',1,NULL),(207,293,9,10,'e. generator',1,NULL),(208,294,9,10,'f. any other ',1,NULL),(209,295,9,10,'a. toilet seats for boys',1,NULL),(210,296,9,10,'b. toilet seats for girls',1,NULL),(211,297,9,10,'c. toilet units for CWSN',1,NULL),(212,298,9,10,'d. urinals for boys',1,NULL),(213,299,9,10,'e. separate toilets for staff',1,NULL),(214,300,9,10,'f. no toilets',1,NULL),(215,301,9,10,'g. only one toilet unit',1,NULL),(216,302,9,10,'a. hand-washing',1,NULL),(217,303,9,10,'b. drinking water (if different)',1,NULL),(218,304,9,10,'a. tube-well/ hand pump',1,NULL),(219,305,9,10,'b. supplied through taps from a common source',1,NULL),(220,306,9,10,'c. any other',1,NULL),(221,307,9,10,'a. boiling',1,NULL),(222,308,9,10,'b. chlorination',1,NULL),(223,309,9,10,'c. filtration',1,NULL),(224,310,9,10,'d. no arrangement',1,NULL),(225,311,9,10,'e. any other',1,NULL),(226,312,9,10,'a. taps',1,NULL),(227,313,9,10,'b. buckets and mugs',1,NULL),(228,314,9,10,'c. no facility',1,NULL),(229,315,9,10,'d. any other',1,NULL),(230,316,9,10,'b. Number of times cleaned in previous years',1,NULL),(231,317,9,10,'a. verandah/ corridor',1,NULL),(232,318,9,10,'b. assembly hall',1,NULL),(233,319,9,10,'c. open space',1,NULL),(234,320,9,10,'d. designated place exists',1,NULL),(235,321,9,10,'a. prepared in the school',1,NULL),(236,322,9,10,'b. supplied from outside (by some agency)',1,NULL),(237,323,9,10,'ii. If it is prepared in the school, is there a kitchen shed or a room for cooking\nMid-day meal for learners in school?',1,NULL),(238,324,9,10,'If yes, a. Number of rooms having fans',1,NULL),(239,325,9,10,'b. Number of rooms having light facility (in the form of bulbs, CFLs, tubes)',1,NULL),(240,326,9,10,'a. number of learners given free textbooks',1,NULL),(241,327,9,10,'b. number of learners given uniforms',1,NULL),(242,328,9,10,'c. number of learners given scholarships',1,NULL),(243,329,9,10,'d. other incentives and number of eligible learners (please mention)',1,NULL),(244,330,9,10,'a. School records',1,NULL),(245,331,9,10,'b. Interaction with parents',1,NULL),(246,332,9,10,'c. Asking learners themselves',1,NULL),(247,333,9,10,'d. Other sources',1,NULL),(248,334,9,10,'a. unaware of resources',1,NULL),(249,335,9,10,'b. aware of resources but unable to access them',1,NULL),(250,336,9,10,'c. resources they have access to and use',1,NULL),(251,337,9,10,'a. Attainment in scholastic and co-scholastic areas',1,NULL),(252,338,9,10,'b. Evidence from interaction with learners in class',1,NULL),(253,339,9,10,'c. Discussion with other teachers',1,NULL),(254,340,9,10,'d. Observation of learner behaviour both in and outside class',1,NULL),(255,341,9,10,'e. Unable to assess',1,NULL),(256,342,9,10,'a. meetings with parents/ guardians in contact register',1,NULL),(257,343,9,10,'b. reminder/ letter sent to the parents/ guardians of learners irregular with attendance',1,NULL),(258,344,9,10,'c. other',1,NULL),(259,345,9,10,'d. no record',1,NULL),(260,346,9,10,'b. Is average attendance calculated monthly for every class?',1,NULL),(261,347,9,10,'a. classes are combined with that of other teachers',1,NULL),(262,348,9,10,'b. another free teacher takes the class',1,NULL),(263,349,9,10,'c. no arrangement made',1,NULL),(264,350,9,10,'d. any other',1,NULL),(265,351,9,10,'If, yes then',1,NULL),(266,352,9,10,'i. personal hygiene is stressed upon occasionally during school assembly',1,NULL),(267,353,9,10,'ii. checking and questioning individual learners in class or during school assembly almost daily',1,NULL),(268,354,9,10,'iii. stressing importance of personal hygiene during school assembly',1,NULL),(269,355,9,10,'iv. any other ',1,NULL),(270,356,9,10,'a. By observing learners in class and during their participation in games/ sports and other co-scholastic activities ',1,NULL),(271,357,9,10,'b. By keeping a record of learners’ participation and attainment',1,NULL),(272,358,9,10,'c. No efforts made to monitor personal-social development',1,NULL),(273,359,9,10,'d. Any other ',1,NULL),(274,360,9,10,'a. By counting periodic tests',1,NULL),(275,361,9,10,'b. Half yearly',1,NULL),(276,362,9,10,'c. Annual exams',1,NULL),(277,363,9,10,'d. By awarding grades based on marks',1,NULL),(278,364,9,10,'a. sanctioned strength',1,NULL),(279,365,9,10,'b. in position',1,NULL),(280,366,9,10,'a. regular',1,NULL),(281,367,9,10,'b. contractual/ ad-hoc',1,NULL),(282,368,9,10,'c. part- time',1,NULL),(283,369,9,10,'d. guest teacher',1,NULL),(284,370,9,10,'e. any other',1,NULL),(285,371,9,10,'a. trained teachers',1,NULL),(286,372,9,10,'b. untrained teachers',1,NULL),(287,373,9,10,'a. organizing special orientation program',1,NULL),(288,374,9,10,'b. head/ senior teachers in face- to- face meeting',1,NULL),(289,375,9,10,'c. no special orientation is done and new teachers get oriented gradually on their own',1,NULL),(290,376,9,10,'d. any other mechanisms ',1,NULL),(291,377,9,10,'(i) If yes, list the reasons for absence (e.g. being on leave, being deputed for training or other details):',1,NULL),(292,378,9,10,'(ii) How is this information compiled to compute average attendance of teachers afterwards?',1,NULL),(293,379,9,10,'(iii) How does the school deal with unreported absence of teachers and other staff members?',1,NULL),(294,380,9,10,'a. assigning substitute teachers',1,NULL),(295,381,9,10,'b. combining classes',1,NULL),(296,382,9,10,'c. assigning a class to the senior',1,NULL),(297,383,9,10,'d. no alternative arrangements student',1,NULL),(298,384,9,10,'a. annual Confidential Report',1,NULL),(299,385,9,10,'b. school head’s observations',1,NULL),(300,386,9,10,'c. learners’ achievement',1,NULL),(301,387,9,10,'d. feedback from Parents’/ SMC',1,NULL),(302,388,9,10,'e. peer/learners’ feedback',1,NULL),(303,389,9,10,'f. any other ',1,NULL),(304,390,9,10,'If yes, what are the main points in it for future development of school?',1,NULL),(305,391,9,10,'If yes, what are the main recommendations for the current year in the plan?',1,NULL),(306,392,9,10,'If yes, to what extent were its goals for that year achieved and what were the reasons for shortfall, if any?',1,NULL),(307,393,9,10,'a. Financial Management',1,NULL),(308,394,9,10,'b. ICT',1,NULL),(309,395,9,10,'c. School Leadership',1,NULL),(310,396,9,10,'d. Any other',1,NULL),(311,397,9,10,'(ii) When and where did she/ he receive training?',1,NULL),(312,398,9,10,'a. On his/ her own',1,NULL),(313,399,9,10,'b. In consultation with a few teachers',1,NULL),(314,400,9,10,'c. With involvement of all teachers',1,NULL),(315,401,9,10,'d. With involvement of teachers, parents and SMC',1,NULL),(316,402,9,10,'a. all teachers',1,NULL),(317,403,9,10,'b. most teachers',1,NULL),(318,404,9,10,'c. a few teachers only',1,NULL),(319,405,9,10,'d. no teacher',1,NULL),(320,406,9,10,'a. Regularly',1,NULL),(321,407,9,10,'b. Occasionally',1,NULL),(322,408,9,10,'c. Rarely',1,NULL),(323,409,9,10,'d. Does not review',1,NULL),(324,410,9,10,'If yes, which are these teams/ committees and what tasks are assigned to them?',1,NULL),(325,411,9,10,'a. By discussing progress individually with teachers',1,NULL),(326,412,9,10,'b. By reviewing the performance of their learners in tests from time to time',1,NULL),(327,413,9,10,'c. By observing the classrooms occasionally to check how teaching is done',1,NULL),(328,414,9,10,'d. Any other',1,NULL),(329,415,9,10,'a. By reviewing record of CCE of learners of every class',1,NULL),(330,416,9,10,'b. By discussing the performance of learners with teachers',1,NULL),(331,417,9,10,'c. By checking the results of all learners in tests and taking note of the change/ improvement in results over a time',1,NULL),(332,418,9,10,'d. Progress is not reviewed by the School Head',1,NULL),(333,419,9,10,'e. Teachers monitor their progress at their level',1,NULL),(335,420,9,10,'a. boys',1,NULL),(336,421,9,10,'b. girls',1,NULL),(337,422,9,10,'c. other',1,NULL),(338,423,9,10,'a. SC',1,NULL),(339,424,9,10,'b. ST',1,NULL),(340,425,9,10,'c. OBC',1,NULL),(341,426,9,10,'d. BPL/ EWS',1,NULL),(342,427,9,10,'e. GEN',1,NULL),(343,428,9,10,'f. CWSN',1,NULL),(344,429,9,10,'b) Number of CWSN in different categories getting aids and appliances:',1,NULL),(345,430,9,10,'a. CWSN',1,NULL),(346,431,9,10,'b. BPL/ EWS',1,NULL),(347,432,9,10,'c. SC',1,NULL),(348,433,9,10,'d. OBC',1,NULL),(349,434,9,10,'e. Girls',1,NULL),(350,435,9,10,'f. ST',1,NULL),(351,436,9,10,'ii. Areas in which programs have been organized for learners:',1,NULL),(352,437,9,10,'a. first-aid',1,NULL),(353,438,9,10,'b. adolescent/ sex education',1,NULL),(354,439,9,10,'c. substance abuse',1,NULL),(355,440,9,10,'d. safety mock drills',1,NULL),(356,441,9,10,'e. road safety/ traffic regulation programme',1,NULL),(357,442,9,10,'b. List the items covered in health checkups:',1,NULL),(358,443,9,10,'c. Number of camps arranged by the school last year:',1,NULL),(359,444,9,10,'i. medical/ health',1,NULL),(360,445,9,10,'ii. HB check-up camp',1,NULL),(361,446,9,10,'iii. road safety awareness programme',1,NULL),(362,447,9,10,'iv. health, hygiene & sanitation awareness camp',1,NULL),(363,448,9,10,'d. i. Number of times health practitioner invited for medical/ health camp',1,NULL),(364,449,9,10,'ii. Give the details of the practitioner(s)',1,NULL),(367,450,9,10,'a. parents',1,NULL),(368,451,9,10,'b. teachers',1,NULL),(369,452,9,10,'c. women',1,NULL),(370,453,9,10,'d. minorities',1,NULL),(371,454,9,10,'e. local authorities',1,NULL),(372,455,9,10,'f. SC/ ST',1,NULL),(373,456,9,11,'Open area is insufficient with limited space for assembly; kuchcha/ semi-pucca/ tent type building is available; boundary wall/ fence doesn’t exist or is discontinuous with big gaps; no garden/ trees in the compound',1,NULL),(374,457,9,11,'Open space is used only for assembly; ground is uneven; premises appear to be unclean and lacking maintenance; major repairs are needed in floor/ walls/ roof/ doors/ windows, etc.',1,NULL),(375,458,9,11,'Playground is unavailable; school occasionally uses the playground of a neighbourhood school or a community Space; no or limited equipment/ material is available',1,NULL),(376,459,9,11,'Learners sometimes play only those games for which no or minimum equipment is needed; no guidance and supervision is available',1,NULL),(377,460,9,11,'Classrooms are crowded; no other rooms are available except for School Head; furniture (mats in the case of primary classrooms) is available but not sufficient',1,NULL),(378,461,9,11,'Classrooms including other rooms are poorly ventilated with inadequate natural/ electric light; some classrooms have poor quality of blackboards with few displays like charts and maps; furniture is of poor quality and requires repairs or replacement',1,NULL),(379,462,9,11,'No provision for electricity; battery operated gadgets like radio etc. are Available',1,NULL),(380,463,9,11,'School borrows/ hires generator/ battery and other electrical equipments for special occasions',1,NULL),(381,464,9,11,'Books are insufficient in number; library room and/ or reading space is not available',1,NULL),(382,465,9,11,'Books are not properly catalogued; no specific library period in the time table; books are generally not issued for reading at home',1,NULL),(383,466,9,11,'No separate laboratory; some space is earmarked for keeping equipment and laboratory materials',1,NULL),(384,467,9,11,'Teachers demonstrate some of the experiments in the class; learners seldom get an opportunity to perform experiments',1,NULL),(385,468,9,11,'School has no computer used for teaching learning purposes; digital learning materials not available',1,NULL),(386,469,9,11,'Absence of opportunity for teachers and learners to use computers',1,NULL),(387,470,9,11,'No ramp',1,NULL),(388,471,9,11,'No ramp facility for learners who are physically challenged',1,NULL),(389,472,9,11,'No proper kitchen shed or designated room for cooking food; there is only a make shift arrangement as a kitchen; cooking utensils are not adequate; no specified place for learners to have their midday meal',1,NULL),(390,473,9,11,'No effort made to keep utensils covered while cooking and storing food; the eating place for learners is unhygienic',1,NULL),(391,474,9,11,'Drinking water facility is available but supply is insufficient',1,NULL),(392,475,9,11,'Drinking water is used as supplied from the source/s without any quality check',1,NULL),(393,476,9,11,'Insufficient supply of water and inadequate number of hand-wash outlets/ stations; no provision for soap',1,NULL),(394,477,9,11,'Hand-wash stations/ water containers are seldom cleaned and maintained; teachers rarely communicate to learners the importance of hand-washing; learners seldom wash hands or wash them without soap',1,NULL),(395,478,9,11,'None or insufficient number of toilets are available; no separate toilets for boys, girls and CWSN',1,NULL),(396,479,9,11,'Toilets are in poor condition and cleaned irregularly; sufficient water is not available for flushing and cleaning toilets',1,NULL),(397,480,9,11,'Teachers are aware of the sociocultural and economic background of the community from where learners come; have a general idea of the home background and learning levels of the learners',1,NULL),(398,481,9,11,'Teachers often experience difficulty in teaching certain concepts due to lack of understanding of the same; make limited efforts to improve their content knowledge and pedagogical skills',1,NULL),(399,482,9,11,'Teachers teach the lesson as per the textbook, with a focus on completion of syllabus; are aware of the topic to be taught and teaching-learning material to be used in their teaching',1,NULL),(400,483,9,11,'Teachers address learners by name; make basic resources available for teaching-learning',1,NULL),(401,484,9,11,'Teachers use only the textbooks and blackboard to teach in class; sometimes make learners copy from the blackboard; class work and home work is given to learners occasionally',1,NULL),(402,485,9,11,'Teachers manage the class, making learners sit in rows facing the blackboard; instruct the class from a fixed position and learners listen passively; ensure discipline by maintaining silence in the class',1,NULL),(403,486,9,11,'Teachers assess learners as per applicable policy; generally tests that are given to assess rote learning and factual knowledge obtained from the content and exercises in the textbooks; learners’ performance is communicated to the parents only through report cards',1,NULL),(404,487,9,11,'Teachers mainly use textbooks for teaching in the class; use other TLM, which may be sporadic and not planned for',1,NULL),(405,488,9,11,'Teachers occasionally reflect on their teaching-learning practice and learners’ progress',1,NULL),(406,489,9,11,'Teachers take and record attendance of learners regularly; identify learners who are frequently absent or not punctual; display class-wise attendance of the learners on the school notice board; sometimes inform parents about frequently absent learners',1,NULL),(407,490,9,11,'Learners listen quietly to teachers in the classroom without much interaction; organizes mandated school functions and co-scholastic activities; the same students usually participate in these activities',1,NULL),(408,491,9,11,'School documents and maintains records of learner’s progress data as per mandate such as in the form of report cards, CCE register, etc.',1,NULL),(409,492,9,11,'School is aware of the indicators of personal and social development of learners e.g. spirit of nationalism, tolerance, secular behavior, good interpersonal relations, etc.; organizes activities like morning assembly, celebration of national days and festivals as per mandate',1,NULL),(410,493,9,11,'Very few learners attain curricular expectations (knowledge and skills) in every grade as measured; school conducts assessment at the end of each academic year to ascertain grade exit levels of learners in all curricular areas',1,NULL),(411,494,9,11,'School leaves it to the new teacher to acquire information about available facilities and observes the ongoing practices of the school',1,NULL),(412,495,9,11,'School maintains record of attendance along with the reasons for absence; generally no alternate arrangements are made to engage the class',1,NULL),(413,496,9,11,'School provides a pre-designed time-table and expects the teacher to complete the syllabus and perform other duties as assigned from time to time',1,NULL),(414,497,9,11,'Teachers are aware of the changes, if any, in the school curriculum and textbooks resulting from changes in policy',1,NULL),(415,498,9,11,'School Head takes note of teachers’ performance as reflected in mandatory inspection reports; checks the presence of teachers in their classrooms and observes their teaching occasionally',1,NULL),(416,499,9,11,'School Head ensures the participation of all teachers in the mandatory in-service training programmes',1,NULL),(417,500,9,11,'School Head develops a School Development Plan (SDP) as per the given mandate; the other stakeholders do not find an opportunity to participate in the planning process',1,NULL),(418,501,9,11,'School Head is broadly aware of areas that need attention; acts on issues in response to official mandate and immediate needs; the required change is rarely discussed and reflected upon',1,NULL),(419,502,9,11,'School Head ensures that all classes are taken regularly, makes alternative arrangements for classes when teachers are absent; ensures effective classroom teaching by taking rounds; is aware of learners’ performance in different classes and subjects',1,NULL),(420,503,9,11,'School Head manages routine activities and school resources (staff, material and financial); allocates responsibilities to a few; takes decisions; acts on the orders and instructions received from the authorities for compliance; communicates decisions; shares the orders and instructions received',1,NULL),(421,504,9,11,'School ensures that no child is denied admission on the basis of caste, gender, language, economic status, disability, etc.; convinces parents of diverse backgrounds to send their children regularly to the school',1,NULL),(422,505,9,11,'Teachers are aware of children with visible disabilities; school maintains records of the same; extends support for activities for which funds and resources are provisioned and documents the same',1,NULL),(423,506,9,11,'School checks its status of compliance against existing laws on school safety, including road safety norms and safety status of school building; takes measures to ensure safety in the existing school building and additional construction, if any; ensures that the building and its surroundings have necessary safety provisions e.g. displays providing information on safety equipments, emergency exits, emergency contact numbers, first-aid kits, fire-extinguishers, etc.',1,NULL),(424,507,9,11,'School is aware of the policy on child abuse and exploitation; does not allow corporal punishment or verbal abuse',1,NULL),(425,508,9,11,'School occasionally checks cleanliness and sanitation of its premises and the personal hygiene of children; provides dustbin for waste; records height and weight measurements of all children',1,NULL),(426,509,9,11,'Meetings are organized without a pre-determined agenda; only a few members attend the meetings; SMC/ SDMC takes decisions largely in the areas of finance and infrastructure',1,NULL),(427,510,9,11,'SMC/ SDMC is aware of the provisions of the RTE Act-2009 as well as SSA/ RMSA provisions relating to school; School Development Plan(SDP) is shared at the SMC/ SDMC meetings',1,NULL),(428,511,9,11,'Parents and community members are invited to school functions; school informs parents about the facilities available in the school and challenges currently faced',1,NULL),(429,512,9,11,'School uses available help from the community to organise visits to institutions/ places of interest in the vicinity of the school',1,NULL),(430,513,9,11,'SMC identifies additional resources required for the implementation of SDP and potential sources for procuring the same',1,NULL),(431,514,9,11,'Open and built area is just sufficient with available assembly hall/ space but inadequate to accommodate all learners comfortably; pucca building exists with boundary wall/ fence without gate; few garden/trees in the compound',1,NULL),(432,515,9,11,'Assembly space/ hall is used for organizing other activities such as physical exercises, organizing functions, etc.; ground is even; minor repairs are needed in floor/ walls/ roof/ doors; occasional maintenance is undertaken',1,NULL),(433,516,9,11,'Playground of inadequate size is available; playground of other school used occasionally for certain games; adequate material and equipment available only for a few games',1,NULL),(434,517,9,11,'Learners utilize the playground well, though for limited number of games; specific time is allocated for sports/games; playground activity is always supervised; equipment is maintained and made available as and when required; sports events are conducted in the school playground or outside',1,NULL),(435,518,9,11,'A few classrooms are crowded; rooms for School Head and common room for teachers are available; furniture is adequate as per requirements of the School',1,NULL),(436,519,9,11,'Majority of classrooms have good ventilation , natural light and fans(where needed); most classrooms have charts and maps displayed on the walls; furniture is comfortable and caters to the needs of the learners',1,NULL),(437,520,9,11,'Electric supply is irregular; no alternative arrangements for power failure/ cuts; all rooms have electric lights and fans; electronic equipment(T.V, radio, etc.) are available',1,NULL),(438,521,9,11,'Wiring and switch boards are in good condition; electrical equipment (fans, etc.) is serviced from time to time',1,NULL),(439,522,9,11,'Sufficient number of books, magazines and newspapers are available and updated regularly; reading space/ library room is available; no e-books or digitized material',1,NULL),(440,523,9,11,'Books are well-kept, catalogued and issued regularly; library period(s) is allotted in the time table; new books are added as and when resources are available',1,NULL),(441,524,9,11,'Basic equipment for demonstration is available; composite laboratory for science and mathematics exists (applicable to upper primary and secondary School)',1,NULL),(442,525,9,11,'Teachers give exposure to learners by demonstrating prescribed experiments as per the syllabus; learners sometimes get an opportunity to conduct experiments in the laboratory/ies; safety measures are in place',1,NULL),(443,526,9,11,'School has a few computers accessible to both teachers and learners; some software and digital teaching learning materials available; no internet facility exists',1,NULL),(444,527,9,11,'Teachers use computers and digital material appropriately for different classes and subjects; learners are occasionally given the opportunity to use computers',1,NULL),(445,528,9,11,'Ramp present but not as per specifications',1,NULL),(446,529,9,11,'Physically challenged learners need assistance while using the ramp',1,NULL),(447,530,9,11,'Kitchen shed or separate room for cooking is available through space is insufficient; utensils are adequate in size and number; sitting space for learners to eat is specified but insufficient',1,NULL),(448,531,9,11,'Cooking utensils are clean for use and kept covered while cooking/ storing food; eating place for learners is hygienic',1,NULL),(449,532,9,11,'Sufficient and regular supply of drinking water; if underground water, then facility for purification is available',1,NULL),(450,533,9,11,'Drinking water is purified, if required; water storage facility is cleaned regularly',1,NULL),(451,534,9,11,'Sufficient supply of water but inadequate hand-wash outlets/ stations; inadequate supply for soap is made',1,NULL),(452,535,9,11,'Hand-wash stations/ water containers are cleaned and maintained on a regular basis; teachers communicate the importance of hand-washing during school assembly; the monitoring of hand-washing is undertaken occasionally',1,NULL),(453,536,9,11,'Separate toilets for boys and girls are available; number of seats and urinals not sufficient (as per Norms)',1,NULL),(454,537,9,11,'Toilets are functional and cleaned at least once a day; water is available for flushing and cleaning for a limited time; maintenance of toilets is undertaken occasionally',1,NULL),(455,538,9,11,'Teachers understand the sociocultural and economic background of the community and the learning needs of the learner; develop an understanding of the learning needs of learners through classroom experiences and personal interaction with other teachers, parents/ guardians and community',1,NULL),(456,539,9,11,'Teachers sometimes face difficulty in explaining difficult concepts in their subjects; lack appropriate pedagogical skills; make efforts to upgrade their content knowledge and pedagogical skills with the available support and resources e.g. subject forums, training programmes',1,NULL),(457,540,9,11,'Teachers prepare and maintain a diary with detailed plan including teaching and assessment strategies and TLM to be used; prepare additional teaching-learning material using local resources',1,NULL),(458,541,9,11,'Teachers make all learners comfortable and involve them in class activities; plan and organize group work/activities and display learners’ work and charts, etc. on the wall; TLMs are accessible to all',1,NULL),(459,542,9,11,'Teachers use a variety of support materials to involve learners in discussions; conduct experiments in the classroom to explain concepts; make special efforts to explain concepts to learners who need additional help; teachers check home work and provide appropriate feedback',1,NULL),(460,543,9,11,'Teachers manage space for organizing different activities in the classroom and outside giving attention to CWSN; encourage punctuality and regularity among learners; learners follow class management rules set by teachers',1,NULL),(461,544,9,11,'Teachers use a variety of activities/ tasks to assess all the curricular areas including art, health and physical education on set criteria; provide descriptive feedback highlighting areas of improvement in the progress report card; regularly interact with parents to share learners’ progress',1,NULL),(462,545,9,11,'Teachers use other resources in addition to textbooks such as reference materials, charts, maps, models, digital learning kits, local resources; use science, mathematics and language kits/ laboratories, as and when appropriate; school maintains a catalogue of resources and makes it available to the teachers as and when required',1,NULL),(463,546,9,11,'Teachers regularly reflect on their teaching-learning practice and record the same; revisit their plans, teaching-learning practice and make efforts for necessary improvement',1,NULL),(464,547,9,11,'School provides regular information about learner attendance to parents; identifies the reasons for prolonged and frequent absence; discusses with learners and parents about the implication of low attendance on learning, making home visits as and when appropriate',1,NULL),(465,548,9,11,'A few learners actively participate in classroom discussion and interactions; school organizes a variety of co-scholastic activities and cultural programmes; teachers motivate learners to actively participate in the same; a large number of students participate in these activities',1,NULL),(466,549,9,11,'School continuously gauges individual learner’s progress against curricular expectations (scholastic and co-scholastic); creates a cumulative database across classes and for different groups of learners that is updated annually',1,NULL),(467,550,9,11,'Teachers organize group activities in the class with a view to develop social and interpersonal skills; organize meetings with parents/ community for discussing social and personal development needs of learners',1,NULL),(468,551,9,11,'Some learners attain most of the curricular expectations (knowledge and skills) in every grade while most remain marginally below grade level expectations; school arranges for remedial measures for improving attainment levels and thereby preparing learners for next grade',1,NULL),(469,552,9,11,'School head orients the new teacher about his/ her responsibilities and the facilities available within the school; usually involves other teachers in orienting the new teacher',1,NULL),(470,553,9,11,'School systematically maintains attendance records, monitors unreported absence and takes action, if necessary; makes arrangements to ensure the class is not left unattended',1,NULL),(471,554,9,11,'School Head briefs the teachers about their responsibilities and performance goals, informally or at staff meetings; reviews and monitors the completion of the syllabus, assigned responsibilities and other tasks as expected',1,NULL),(472,555,9,11,'Teachers make efforts to understand the changing curricular expectations; adapt their teaching learning practice to suit the same',1,NULL),(473,556,9,11,'School Head reviews the teachers’ performance and provides them feedback; teachers review their own performance based on the classroom experiences and identify areas of improvement',1,NULL),(474,557,9,11,'School motivates and creates regular opportunities for teachers to participate in different programmes relevant to their professional needs; seeks support from other academic institutions/ experts to address difficulties faced by teachers',1,NULL),(475,558,9,11,'School Head develops a vision/ mission statement; teachers are involved in the creation of the SDP, prioritization of tasks; School Head allocates responsibilities to majority of teachers for SDP implementation; provides direction for its implementation',1,NULL),(476,559,9,11,'School Head, in consultation with teachers, identifies the strengths of the school, and areas that need improvement; reflects upon the required changes; identifies action points and appropriately acts upon them; logically assesses the progress and refines actions, where required; takes note of the changes that are being reflected in the teaching-learning and other school practices',1,NULL),(477,560,9,11,'School Head regularly observes the teaching-learning process in different classes and provides written/ verbal feedback to teachers individually; analyses and reviews the learners’ performance in different classes and subjects and discusses the same with concerned teachers/ subject teachers',1,NULL),(478,561,9,11,'School Head and staff plans and manage routine activities and school resources (staff, material and financial); involve staff in decision-making; School Head communicates details of the SDP and provides clear directions to staff members and takes the lead for its effective implementation',1,NULL),(479,562,9,11,'Teachers maintain equity among children, parents, peers and other staff on the basis of caste, gender, socio-economic background, etc. during classroom tasks, activities, seating arrangement, etc.; give special attention to girls and disadvantaged groups to promote equity',1,NULL),(480,563,9,11,'School is aware of Persons with Disabilities Act; leverages the support of available resource person to identify and support CWSN; teachers attempt to attend to their needs with special aids and curricular material; follow curriculum for CWSN with minor adaptations like making small changes in learning content, using appropriate learning approach and assessment methods; ensure CWSN are learning as per the targets mutually agreed upon with the parents',1,NULL),(481,564,9,11,'School ensures safe storage and usage of potentially hazardous materials with special attention to kitchen & laboratories; monitors entry and exit of visitors; undertakes safety drills as mandated; ties up with local agencies for handling emergency situations; makes arrangements for keeping the building safe from rodents, reptiles, stray dogs etc.; allocates responsibility for all safety related activities; identifies accident prone areas and ensures signboards are placed by relevant agencies in the vicinity of the school to prevent accidents; ensures the presence of personnel to regulate traffic during peak hours and in accident prone areas as and where needed',1,NULL),(482,565,9,11,'School staff is trained to recognize signs of sexual/ physical/ substance abuse; school creates awareness among children to differentiate between ‘good touch’ and ‘bad touch’; screens all digital/ non-digital learning material for objectionable content; ensures no child is left alone in isolated/ dark places; also ensures that there is no adverse psychological impact on children due to work overload by spacing out assignments, assessments, etc.; has a mechanism to address complaints and grievances of children and parents; undertakes background checks of all adults working in the school',1,NULL),(483,566,9,11,'School has a policy on health, hygiene and sanitation; continuously ensures cleanliness and sanitation of all its facilities, the quality of the Mid Day Meal and the personal hygiene of children through regular checks and drives; undertakes appropriate measures for waste disposal; initiates awareness building programs; creates growth charts of children to check status of their health; facilitates regular primary health checks (including dental and eye checkup)',1,NULL),(484,567,9,11,'Meetings are organized as per the mandate with prior notice and fixed agenda; most members attend the meetings and participate in the discussions; SMC/ SDMC also take decisions on issues other than finance and infrastructure',1,NULL),(485,568,9,11,'SMC/ SDMC facilitates implementation and ensures compliance with RTE Act-2009 as well as SSA/ RMSA provisions; suggests activities that require immediate attention in the SDP; shares information related to RTE Act-2009 as well as SSA/ RMSA with the community',1,NULL),(486,569,9,11,'School interacts with the community and discusses the issues relating to the socioeconomic background, enrolment, attendance, etc. of learners; SMC mobilizes resources for maintenance of the school and for improving its facilities; school and community jointly organize functions within the school and in the community',1,NULL),(487,570,9,11,'School takes initiatives to develop understanding among learners about the culture, oral history and traditional knowledge (folk songs, art and craft, agricultural practices, etc.) of the community; displays the photographs and pictures of renowned people and important places and features of the community; invites local artisans and craftsmen to interact with learners',1,NULL),(488,571,9,11,'SMC mobilizes resources for maintenance of the school and for improving its facilities; school and community jointly organize functions within the school and in the community',1,NULL),(489,572,9,11,'Ample open and built spaces available for free movement of learners with designated space for assembly; boundary wall/ fencing with plantation and gate exists; well maintained garden and lawn.',1,NULL),(490,573,9,11,'Open space and building are clean and well-maintained; repairs are undertaken in a timely manner',1,NULL),(491,574,9,11,'Playground of adequate size is available within school premises; adequate sports equipment and material also available for a variety of games',1,NULL),(492,575,9,11,'Learners participate in a variety of games/ sports in a planned manner; facility for training/ coaching for sports is available; school maintains inventories of all equipment and replenishes material as and when necessary; organizes inter-school sports meet every year',1,NULL),(493,576,9,11,'All the classrooms have adequate space for learners and for group work and other activities; additional rooms to be used as office, store, craft, etc. are available; each classroom has sufficient number of benches and chairs; teachers have lockers/ cupboards',1,NULL),(494,577,9,11,'Every classroom has good ventilation, light; other rooms are appropriately furnished; display of pictures are used to create an attractive environment; furniture is well-arranged and aesthetically pleasing; is age-appropriate and friendly for differently-abled Learners',1,NULL),(495,578,9,11,'School has its own power back-up facility, such as generator or inverter to deal with power failures; all rooms have adequate electric lights and fans; public address system is in place',1,NULL),(496,579,9,11,'Miniature Circuit Breaker switches (MCB), are in place to prevent fire due to short circuit; all electrical and electronic equipment are regularly checked, maintained and kept in working Order',1,NULL),(497,580,9,11,'A large collection of books is available; periodicals, magazines, newspapers are regularly subscribed to; a separate room for library with adequate reading space is available; e-books and digitized materials are available',1,NULL),(498,581,9,11,'Books are properly catalogued, arranged systematically in shelves and regularly used by learners and teachers; library provides access to ebooks and digitized materials; library resources support curricular transaction; regular addition of new books is done through an appropriate selection procedure keeping in mind the age, linguistic background , academic needs of learners and teachers',1,NULL),(499,582,9,11,'All required laboratories are available and are wellequipped with materials, instruments as per state norms and specifications; running water and electric supply is ensured',1,NULL),(500,583,9,11,'Every learner is given an opportunity to conduct all prescribed experiments in the laboratory; teacher utilizes/ uses the laboratory to conducts experiments simultaneously while transacting the relevant topic in the class',1,NULL),(501,584,9,11,'School has computer room with sufficient numbers of computers with internet access; up-todate adequate digital teaching learning material and software available',1,NULL),(502,585,9,11,'Teachers integrate the use of technology (computers and related digital material) in their teaching learning plan and implementation; also use computers in the assessment of learners; every learner gets an opportunity to use the computer',1,NULL),(503,586,9,11,'Ramp with hand rails, non-slippery walking surface, slope and height as per specifications',1,NULL),(504,587,9,11,'Ramp provides effortless access to physicallychallenged learners and can be used independently by them',1,NULL),(505,588,9,11,'Ample space in the kitchen shed or room for storage of utensils and cooking activity; adequate space for learners to have their midday meal',1,NULL),(506,589,9,11,'Kitchen and utensils are cleaned after every use; person responsible for cooking makes special efforts to maintain personal hygiene; proper seating arrangement is made for the learners to have their meals; designated teachers regularly supervise the mid-day meal programme',1,NULL),(507,590,9,11,'There is continuous supply of safe drinking water; retrofitting of drinking water facilities is done, if required, for maintenance and purification',1,NULL),(508,591,9,11,'School ensures regular supply of purified drinking water; cleanliness is maintained around drinking water facilities',1,NULL),(509,592,9,11,'Regular and sufficient supply of water; adequate number of hand-wash outlets/ stations available; adequate and regular supply of soap',1,NULL),(510,593,9,11,'Hand-wash stations are cleaned daily; school organizes hand-washing and hygiene drives through posters, slogans, songs, skits, etc.; regular sessions at various forums are held to develop a habit and stress the importance of hand-washing; School Head monitor learners’ personal hygiene regularly',1,NULL),(511,594,9,11,'Separate toilet seats and urinals for boys and girls are available in sufficient number; CWSN friendly toilet available',1,NULL),(512,595,9,11,'All the toilets are functional and maintained at all times; cleaning of toilets is undertaken regularly; continuous supply of water is available for flushing and cleaning; school maintains sanitation and cleanliness of toilets',1,NULL),(513,596,9,11,'Teachers seek feedback from learners and parents regarding learners’ performance in a systematic manner; address individual needs, learning style and strengths of learners',1,NULL),(514,597,9,11,'Teachers have mastery over content and pedagogical skills and hence rarely face difficulty in classroom transaction; take their own initiative and the support of their fellow teachers if needed for updating their knowledge and pedagogical skills; school also extends support in updating the same',1,NULL),(515,598,9,11,'School has a culture where every teacher designs lessons as per the varying learning needs of learners and makes the teaching learnercentric; uses TLMs appropriately; connects teaching-learning with immediate context and environment; plans appropriate strategies such as observation, exploration, discovery, analysis, critical reflection, problem-solving and drawing inferences to make learning effective',1,NULL),(516,599,9,11,'Teachers create a conducive and interactive environment in the classroom; encourage peer learning/interaction; provide opportunity for expression; appreciate the views of all learners; encourage questioning/sharing of ideas',1,NULL),(517,600,9,11,'Teachers provide opportunity to learners for self-learning through inquiry, exploration, discovery, experimentation and collaborative learning; ensure participation of each learner in the classroom discussion; get teaching-learning materials prepared by learners as required',1,NULL),(518,601,9,11,'Teachers and learners collectively decide on classroom management rules; seating arrangement is flexible and learners sit as per the needs of the activity they are engaged in; learners observe self –discipline and adhere to the rules developed collectively',1,NULL),(519,602,9,11,'Teachers consider assessment as an integral part of the teachinglearning process; analyze the learners’ past assessment records and link it with the current achievement levels; make continuous assessment and provide feedback on progress and attainment; assess other curricular areas, including personal and social qualities systematically with followup measures for improvement; use feedback from assessment to improve teaching-learning',1,NULL),(520,603,9,11,'Teachers integrate the use of TLM, local community resources, ICT support material, laboratories, library, etc. with the lessons appropriately; school facilitates networking with other schools for sharing resources',1,NULL),(521,604,9,11,'Teachers reflect individually and collectively on the planned and actual teaching-learning process in the light of its outcomes; identify the gaps between the two and plan for improvement; design alternative learning experiences based on the reflection',1,NULL),(522,605,9,11,'School analyzes attendance data of all learners; ascertains whether the high absence rates can be associated with any particular category of learners or at any period of the year; addresses the problem with the help of the SMC and parents; evolves measures to motivate learners and parents to ensure punctuality and regular attendance; acknowledges and appreciates punctuality and regularity of learners',1,NULL),(523,606,9,11,'All learners participate actively in classroom discussions and interact with teachers and peers; school identifies the talent of learners in different co-scholastic areas; provides them training and opportunities to excel in the same; all learners take interest and participate enthusiastically in various school functions and coscholastic activities',1,NULL),(524,607,9,11,'School tracks and monitors each learner’s progress across subjects and co-scholastic areas; tracks individual learner progress from the beginning and over time, keeping in mind the differential pace of learning of learners; analyzes the cumulative database to identify progress patterns and trends for classes and groups of learners; uses the findings of such analyses and incorporates the feedback in classroom practice; aspires to achieve/ exceed state/ national learner attainment levels',1,NULL),(525,608,9,11,'School integrates life skills development with day-to-day classroom transactions to promote creative and critical thinking, problem solving and decision making, communication and interpersonal skills; teachers create and use resources like stories, audio-video clips, etc. to create a conducive value ethos; teachers exemplify behavior as expected from learners; discuss with parents the role of both school and home in the personal and social development of the learner',1,NULL),(526,609,9,11,'Most learners’ attainment is at par/ above expected grade level across the school; school continuously improvises its mechanism to ascertain grade exit levels of learners',1,NULL),(527,610,9,11,'Special orientation programmes are organized systematically to apprise new teacher/s about roles and responsibilities, the school context, profile of the learners, curricular expectations, role of SMC/ SDMC and various schemes/ programmes being implemented in the school',1,NULL),(528,611,9,11,'School has an appropriate system to address short, long and unreported absence of teachers; makes timely and suitable arrangements for substitutes from within or outside the school and orients them to undertake the responsibility; creates a culture of punctuality and accountability among teachers',1,NULL),(529,612,9,11,'School allocates responsibilities of teachers through mutual consultation; encourages teachers to set their own performance goals and provides opportunities to innovate and experiment with new ideas; teachers themselves monitor their own progress',1,NULL),(530,613,9,11,'School creates opportunities for teachers to discuss and reflect upon the changing curricular expectations and its implications on their current classroom practice; provides follow-up support for teachers to adopt context-specific changes',1,NULL),(531,614,9,11,'School Head reviews the performance of teachers on the basis of learners’ progress and attainment and discussions with teachers; discusses teacher performance with parents, learners and SMC/ SDMC; teachers collectively reflect on their own performance and develop strategies for improvement',1,NULL),(532,615,9,11,'School makes provision for continuous academic mentoring of teachers; supports teachers in trying out innovative ideas and practices; teachers discuss collectively on inputs received during training; reflect on the possibility of integration of the acquired knowledge and skills in classroom practice',1,NULL),(533,616,9,11,'School Head engages all stakeholders in developing vision/ mission taking into account current practices, policies and programs which are subsequently documented; SDP is co-created by all the stakeholders and is aligned to the vision/ mission statement; appropriate prioritization is done for necessary action; all teachers understand their defined roles and responsibilities and act accordingly to make desired progress; periodic review of vision and SDP is undertaken regularly',1,NULL),(534,617,9,11,'School Head communicates clearly the need for change to all the stakeholders and enhances their understanding of the same; identifies clear targets and formulates predictable improvement strategies on the basis of analysis of evidence and other sources collectively with stakeholders; leads change and monitors incremental improvement regularly; distributes leadership roles and individual responsibilities for implementing change; encourages teachers to engage in evidence- based improvement and change in school practices',1,NULL),(535,618,9,11,'School Head and teachers collectively reflect on current teaching-learning practices and learners’ progress and attainment; discuss required improvement in the light of learning indicators, learner-centred pedagogy, innovative approaches to teaching, etc.; discuss performance of learners with parents',1,NULL),(536,619,9,11,'School Head and staff members collectively develop a shared vision and a strategic plan in consultation with parents and learners; distribute the responsibilities among the staff members on the basis of mutual consensus and areas of expertise; take action with mutual support, monitor and evaluate the progress collectively',1,NULL),(537,620,9,11,'School responds to the needs of all children with varying abilities and backgrounds; values and ensures participation of all children, irrespective of their different physical, emotional and learning abilities; encourages parents from diverse backgrounds to actively participate in SMC/ SDMC meetings and other school activities',1,NULL),(538,621,9,11,'School involves the community and local NGOs in the identification and subsequent support needed for CWSN; monitors and documents the progress of CWSN regularly; includes CWSN in general classrooms with the rest of the class; builds teacher capacity for the same through training; teachers share inspirational stories of accomplishments of people with special needs',1,NULL),(539,622,9,11,'School undertakes awarenessbuilding exercises on disaster management for all stakeholders; has a structured emergency response plan, including communication modes and mechanisms like maintenance contract (for keeping building free from rodents, animals, etc.); reviews such plans and mechanisms regularly; conducts training/seminar/ workshops periodically to sensitize learners on safety measures and precautions ; integrates awareness programmes and safety drills with teachinglearning; checks that transport arrangement is safe for learners; participates in traffic regulation awareness programmes',1,NULL),(540,623,9,11,'School adopts a structured approach to ensure emotional safety of all children which includes awareness building through dialogue and discussion, programs on child abuse, sex and adolescent education, regular one-on-one counseling sessions, dialogue to resolve complaints and grievances; checks the implementation of the policy on emotional safety and reviews the same on a regular basis; conducts counseling sessions for children and parents to ease out child anxieties related to curricular overload and pressure of performance, thereby helping children develop coping mechanisms; regular career counseling sessions are also held for appropriate age groups',1,NULL),(541,624,9,11,'School and SMC together monitor cleanliness, sanitation in the school and the personal hygiene of children; conduct orientation programs/ workshops on health, hygiene and sanitation for parents/ guardians; invite health practitioners for such events; advise parents/ guardians about health related problems noticed in the school; arrange for professional medical advice for children engaged in substance abuse',1,NULL),(542,625,9,11,'The SMC/ SDMC meetings are organized regularly and additionally when the need arises; identified issues and plans to resolve the same are discussed; the SMC/ SDMC also facilitates, monitors and reviews the implementation of the decisions',1,NULL),(543,626,9,11,'SMC/ SDMC participates in the school evaluation process; helps identify and prioritize development needs; jointly prepares the SDP with the teachers and monitors its implementation for holistic development',1,NULL),(544,627,9,11,'School and community jointly assess the needs of the school; identify available resources, plan and optimally use them for the development of the school; school and community reach out to other sources such as NGOs, corporate bodies, alumni, etc. to mobilize resources',1,NULL),(545,628,9,11,'School integrates local community knowledge and skills in the teaching-learning of different subjects and classes in a planned and organised manner; uses community/ village as a learning environment for learner to develop specific vocational skills',1,NULL),(546,629,9,11,'School and community reach out to other sources such as NGOs, corporate bodies, alumni, etc. to mobilise resources; school organizes/ undertakes activities for the benefit of the community like cleanliness drive, literacy campaigns, awareness against gender and social discriminations, etc.; actively initiates online platforms for sharing of good practices relating to community participation in schools',1,NULL),(547,630,9,8,'Level-1',1,NULL),(548,631,9,8,'Level-2',1,NULL),(549,632,9,8,'Level-3',1,NULL),(550,633,9,10,'a. boys',1,NULL),(551,634,9,10,'b. girls',1,NULL),(552,635,9,9,'',1,NULL),(558,642,9,2,'',1,NULL),(561,645,9,3,'',1,NULL),(564,648,9,10,'Yes',1,NULL),(565,649,9,10,'No',1,NULL),(573,655,9,10,'Name of programme',1,NULL),(574,656,9,10,'Duration',1,NULL),(575,657,9,10,'Names of teachers who attended',1,NULL),(576,658,9,10,'Duties/ Responsibilities',1,NULL),(577,659,9,10,'Names of teachers assigned',1,NULL),(578,660,9,10,'(please mention)',1,NULL),(579,661,9,10,'(specify)',1,NULL),(583,663,9,4,'Mid Day Meal, Kitchen and Utensils (where cooking is done within school premises)',1,NULL),(586,666,9,8,'Rarely',1,NULL),(587,667,9,8,'Sometimes',1,NULL),(588,668,9,8,'Mostly',1,NULL),(589,669,9,8,'Always',1,NULL),(590,670,9,7,'Shaala Siddhi_Test',1,NULL),(591,671,9,8,'',1,NULL),(592,111,18,4,'शालेय आवार',1,NULL),(593,112,18,4,'क्रीडा उपकरणे व साहित्यासह मैदान',1,NULL),(594,1,18,1,'शाळेचे सामर्थ्य स्रोत: उपलब्धता, पर्याप्तता व उपयुक्तता',1,NULL),(597,113,18,4,'वर्गखोल्या व इतर खोल्या ',1,NULL),(598,114,18,4,'वीज व विदयुत उपकरणे',1,NULL),(599,115,18,4,'ग्रंथालय',1,NULL),(600,116,18,4,'प्रयोगशाळा',1,NULL),(601,117,18,4,'संगणक (जेथे तरतूद असेल तेथे)',1,NULL),(602,118,18,4,'उतरता रस्ता',1,NULL),(603,119,18,4,'माध्यान्ह भोजन, स्वयंपाक खोली व भांडी',1,NULL),(604,120,18,4,'पेय जल',1,NULL),(605,121,18,4,'हात धुण्याची सुविधा',1,NULL),(606,122,18,4,'स्वच्छतागृह',1,NULL),(607,2,18,1,'अध्ययन-अध्यापन व मूल्यमापन',1,NULL),(608,123,18,4,'शिक्षकांना अध्ययनार्थीची जाण',1,NULL),(609,124,18,4,'शिक्षकांचे विषय व अध्यापनशास्त्रीय ज्ञान',1,NULL),(610,125,18,4,'अध्यापनाचे नियोजन',1,NULL),(611,126,18,4,'अध्ययनपूरक वातावरण निर्मिती',1,NULL),(612,127,18,4,'अध्ययन-अध्यापन प्रक्रिया',1,NULL),(613,128,18,4,'वर्ग व्यवस्थापन',1,NULL),(614,129,18,4,'अध्ययनार्थीचे मूल्यमापन',1,NULL),(615,130,18,4,'अध्ययन-अध्यापन साहित्याचा वापर',1,NULL),(616,131,18,4,'शिक्षकांचे स्वत:च्या अध्यापन-अध्ययन कृतींवरील चिंतन',1,NULL),(617,3,18,1,'अध्ययनार्थींची प्रगती, संपादणूक व विकास',1,NULL),(618,132,18,4,'अध्ययनार्थींची उपस्थिती',1,NULL),(619,133,18,4,'अध्ययनार्थींचा सहभाग व व्यग्रता',1,NULL),(620,134,18,4,'अध्ययनार्थींची प्रगती',1,NULL),(621,135,18,4,'अध्ययनार्थींचा वैयक्तिक व सामाजिक विकास',1,NULL),(622,136,18,4,'अध्ययनार्थींची संपादणूक',1,NULL),(623,4,18,1,'शिक्षक कामगिरी व व्यावसायिक विकासाचे व्यवस्थापन',1,NULL),(624,137,18,4,'नवीन शिक्षकांचे उद्बोधन',1,NULL),(625,138,18,4,'शिक्षकांची उपस्थिती',1,NULL),(626,139,18,4,'जबाबदारी निश्चिती व कामगिरी लक्ष्यांची निश्चिती',1,NULL),(627,140,18,4,'अभ्यासक्रम अपेक्षांप्रति शिक्षकांची तयारी',1,NULL),(628,141,18,4,'शिक्षक कामगिरीचे पर्यवेक्षण',1,NULL),(629,142,18,4,'शिक्षकांचा व्यावसायिक विकास',1,NULL),(630,5,18,1,'शालेय नेतृत्व व व्यस्थापन',1,NULL),(631,6,18,1,'समावेशन, आरोग्य व सुरक्षा',1,NULL),(632,7,18,1,'उत्पादक समाज-सहभाग',1,NULL),(633,143,18,4,'दूरदृष्टीचे विकसन व दिशा निश्चति',1,NULL),(634,144,18,4,'बदल व सुधारणेचे नेतृत्व',1,NULL),(635,145,18,4,'अध्ययन-अध्यापनाचे नेतृत्व',1,NULL),(636,146,18,4,'शालेय व्यस्थापनाचे नेतृत्व',1,NULL),(637,147,18,4,'समावेशन संस्कृती',1,NULL),(638,148,18,4,'विशेष गरजा असणान्या विद्यार्थ्यांचे समावेशन',1,NULL),(639,149,18,4,'शारीरिक सुरक्षितता',1,NULL),(640,150,18,4,'मानसिक सुरक्षितता',1,NULL),(641,151,18,4,'आरोग्य व स्वछता',1,NULL),(642,152,18,4,'शाळा व्यवस्थापन / शाळा विकास व्यवस्थापन समितीचे संघटन व व्यवस्थापन',1,NULL),(643,153,18,4,'शाळा सुधारणेत भूमिका',1,NULL),(644,154,18,4,'शाळा-समाज अनुबंध',1,NULL),(645,155,18,4,'अध्ययन स्रोत म्हणून समाज',1,NULL),(646,156,18,4,'समाज सक्षमीकरण',1,NULL),(648,672,9,7,'Test',1,NULL),(649,673,9,7,'test_shaala',1,NULL),(650,674,9,7,'Test_Dec_2018',1,NULL),(651,675,9,7,'test',1,NULL),(652,676,9,7,'d',1,NULL),(653,677,9,7,'Test 1',1,NULL),(654,678,9,7,'d',1,NULL),(655,679,9,7,'Test 10',1,NULL),(656,680,9,7,'Zzxzzx',1,NULL);
/*!40000 ALTER TABLE `h_lang_translation` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `h_lang_translation_back`
--

DROP TABLE IF EXISTS `h_lang_translation_back`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `h_lang_translation_back` (
  `lang_translation_id` int(11) NOT NULL AUTO_INCREMENT,
  `equivalence_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `translation_type_id` int(11) NOT NULL,
  `translation_text` text COLLATE utf8_unicode_ci NOT NULL,
  `isActive` int(11) NOT NULL,
  `parent_lang_translation_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`lang_translation_id`),
  UNIQUE KEY `equivalence_id` (`equivalence_id`,`language_id`) USING BTREE,
  KEY `fk_h_lang_translation_d_translation_type_idxx` (`translation_type_id`),
  KEY `fk_h_lang_translation_d_languagee` (`language_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `h_lang_translation_back`
--

LOCK TABLES `h_lang_translation_back` WRITE;
/*!40000 ALTER TABLE `h_lang_translation_back` DISABLE KEYS */;
/*!40000 ALTER TABLE `h_lang_translation_back` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `h_lang_translation_marathi`
--

DROP TABLE IF EXISTS `h_lang_translation_marathi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `h_lang_translation_marathi` (
  `lang_translation_id` int(11) NOT NULL DEFAULT '0',
  `equivalence_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `translation_type_id` int(11) NOT NULL,
  `translation_text` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `isActive` int(11) NOT NULL,
  `parent_lang_translation_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `h_lang_translation_marathi`
--

LOCK TABLES `h_lang_translation_marathi` WRITE;
/*!40000 ALTER TABLE `h_lang_translation_marathi` DISABLE KEYS */;
INSERT INTO `h_lang_translation_marathi` VALUES (1,1,9,1,'Enabling Resources of\r\nSchool: Availability,\r\nAdequacy and Usability',1,NULL),(2,2,9,1,'Teaching-learning and\r\nAssessment',1,NULL),(3,3,9,1,'Learners’ Progress,\r\nAttainment and Development',1,NULL),(4,4,9,1,'Managing Teacher\r\nPerformance and Professional Development',1,NULL),(5,5,9,1,'School Leadership and\r\nManagement',1,NULL),(6,6,9,1,'Inclusion, Health and\r\nSafety',1,NULL),(7,7,9,1,'Productive Community\r\nParticipation',1,NULL),(19,17,9,1,'Enabling Resources of\r\nSchool: Availability,\r\nAdequacy and Usability',1,NULL),(21,111,9,4,'School Premises',1,NULL),(22,112,9,4,'Playground with Sports Equipment and Materials',1,NULL),(23,113,9,4,'Classrooms and Other Rooms',1,NULL),(24,114,9,4,'Electricity and Gadgets',1,NULL),(25,115,9,4,'Library',1,NULL),(26,116,9,4,'Laboratory',1,NULL),(27,117,9,4,'Computer (where provisioning exist)',1,NULL),(28,118,9,4,'Ramp',1,NULL),(29,119,9,4,'Mid Day Meal, Kitchen and Utensils',1,NULL),(30,120,9,4,'Drinking Water',1,NULL),(31,121,9,4,'Hand Wash Facilities',1,NULL),(32,122,9,4,'Toilets',1,NULL),(33,123,9,4,'Teachers’ Understanding of Learners',1,NULL),(34,124,9,4,'Subject and Pedagogical Knowledge of Teachers',1,NULL),(35,125,9,4,'Planning for Teaching',1,NULL),(36,126,9,4,'Enabling Learning Environment',1,NULL),(37,127,9,4,'Teaching-learning Process',1,NULL),(38,128,9,4,'Class Management',1,NULL),(39,129,9,4,'Learners’ Assessment',1,NULL),(40,130,9,4,'Utilization of Teaching-learning Resources',1,NULL),(41,131,9,4,'Teachers’ Reflection on their own Teaching-learning Practices',1,NULL),(42,132,9,4,'Learners’ Attendance',1,NULL),(43,133,9,4,'Learners’ Participation & Engagement',1,NULL),(44,134,9,4,'Learners’ Progress',1,NULL),(45,135,9,4,'Learners’ Personal and Social Development',1,NULL),(46,136,9,4,'Learners’ Attainment',1,NULL),(47,137,9,4,'Orientation of New Teachers',1,NULL),(48,138,9,4,'Teachers’ Attendance',1,NULL),(49,139,9,4,'Assigning Responsibilities and Defining Performance Goal',1,NULL),(50,140,9,4,'Teachers’ Preparedness for Curricular Expectations',1,NULL),(51,141,9,4,'Monitoring of Teachers Performance',1,NULL),(52,142,9,4,'Teachers’ Professional Development',1,NULL),(53,143,9,4,'Building Vision and Setting Direction',1,NULL),(54,144,9,4,'Leading Change and Improvement',1,NULL),(55,145,9,4,'Leading Teaching-learning',1,NULL),(56,146,9,4,'Leading Management of School',1,NULL),(57,147,9,4,'Inclusive Culture',1,NULL),(58,148,9,4,'Inclusion of Children With Special Needs (CWSN)',1,NULL),(59,149,9,4,'Physical Safety',1,NULL),(60,150,9,4,'Psychological Safety',1,NULL),(61,151,9,4,'Health and Hygiene',1,NULL),(62,152,9,4,'Organisation and Management of SMC/ SDMC',1,NULL),(63,153,9,4,'Role in School Improvement',1,NULL),(64,154,9,4,'School – Community Linkages',1,NULL),(65,155,9,4,'Community as Learning Resource',1,NULL),(66,156,9,4,'Empowering Community',1,NULL),(76,166,9,4,'deepak',1,NULL),(84,173,9,9,'Total area of school premises with covered area (square metre)',1,NULL),(85,174,9,9,'Area of playground, if available (in square metre)',1,NULL),(86,175,9,9,'Area of open space in the school, if there is no playground (in square metre)',1,NULL),(87,176,9,9,'Classes taught in school:',1,NULL),(88,177,9,9,'Total enrolment in the school_(as on 30th September)',1,NULL),(89,178,9,9,'The condition of school building:',1,NULL),(90,179,9,9,'List of games/ sports, art education, work experience and other co-scholastic activities and list equipment/ material available for different activities:',1,NULL),(91,636,9,9,'a) Total number of classrooms in the school',1,NULL),(92,181,9,9,'Number of classrooms where learners sit on mats/ tatputtis:',1,NULL),(93,182,9,9,'Number of classrooms in which learners sit on benches/chairs and have desks:',1,NULL),(94,183,9,9,'Number of learners for whom additional benches/ chairs are required/ mat/ tatputtis are needed, if existing provisions are insufficient:',1,NULL),(95,184,9,9,'Availability of other rooms:',1,NULL),(96,185,9,9,'Is there a separate room for library?',1,NULL),(97,186,9,9,'Number of learners who can sit and read at a time in library',1,NULL),(98,187,9,9,'The library manages by:',1,NULL),(99,188,9,9,'Number of periodicals the school has subscribed to:',1,NULL),(100,189,9,9,'The numbers of books other than dictionaries & encyclopedia per 100 learners in the library',1,NULL),(101,190,9,9,'Laboratory available in the school:',1,NULL),(102,191,9,9,'Number of computers available in the school for:',1,NULL),(103,192,9,9,'Internet facility available in school is used by:',1,NULL),(104,193,9,9,'Availability of other equipment:',1,NULL),(105,194,9,9,'Number of functional toilets available:',1,NULL),(106,195,9,9,'Ratio of number of learners to number of taps/ outlets for:',1,NULL),(107,196,9,9,'Source of drinking water:',1,NULL),(108,197,9,9,'Process of purification of water in school:',1,NULL),(109,198,9,9,'Type of hand-washing facility available:',1,NULL),(110,199,9,9,'a. Whether water is stored in an overhead tank:',1,NULL),(111,200,9,9,'School assembly held in:',1,NULL),(112,201,9,9,'i. Mid Day Meal in the school is:',1,NULL),(113,202,9,9,'Precautions taken to ensure that the food is safe to eat with no chance for insects/reptiles to contaminate the food:',1,NULL),(114,203,9,9,'Is there electricity in school?',1,NULL),(115,204,9,9,'List other rooms (other than classrooms) available in school for different purposes along with the use being made of each room:',1,NULL),(116,205,9,9,'Incentives (free textbooks, free uniform, scholarships, etc.) available for learners:',1,NULL),(117,206,9,9,'How do teachers acquire information about socio-cultural and home background of learners?',1,NULL),(118,207,9,9,'Teachers access to different types of teaching- learning resources:',1,NULL),(119,208,9,9,'On what basis do teachers assess learners\' attitudes, motivation and interest in learning?',1,NULL),(120,209,9,9,'Average school attendance for the current academic year',1,NULL),(121,210,9,9,'Types of rewards (if any) given to learners for punctuality and regular attendance:',1,NULL),(122,211,9,9,'Record of actions to promote regularity and punctuality in attendance',1,NULL),(123,212,9,9,'Are the learners\' attendance registers kept up-to-date?',1,NULL),(124,213,9,9,'a. Is average attendance calculated monthly for every learner?',1,NULL),(125,214,9,9,'Alternative arrangements made for classes which the teachers could not take:',1,NULL),(126,215,9,9,'Is personal hygiene of learners checked and assured by the school?',1,NULL),(127,216,9,9,'List the activities undertaken in the school that help in personal and social development of learners:',1,NULL),(128,217,9,9,'How is learners\' personal development monitored?',1,NULL),(129,218,9,9,'How is learners\' attainment measured and how is the progress ascertained over time?',1,NULL),(130,219,9,9,'Number of teachers in school:',1,NULL),(131,220,9,9,'Number of teachers in position:',1,NULL),(136,222,9,9,'Number of:',1,NULL),(137,223,9,9,'Orientation of new teachers in the school is done by:',1,NULL),(138,224,9,9,'Does the school maintain a record of teachers\' attendance along with reasons for absence?',1,NULL),(139,225,9,9,'School makes alternative arrangements for the classes of absent teachers by:',1,NULL),(140,226,9,9,'Teacher performance is monitored through/ by:',1,NULL),(141,227,9,9,'Mechanisms for teachers\' continuous performance improvement:',1,NULL),(142,228,9,9,'List the duties/ responsibilities assigned to teachers beyond classroom teaching:',1,NULL),(143,229,9,9,'Does the school have a vision/ mission statement?',1,NULL),(144,230,9,9,'Is the School Development Plan (SDP) of previous year available?',1,NULL),(145,231,9,9,'Was the SDP for the previous year implemented?',1,NULL),(146,232,9,9,'(i) What are the areas in which the School Head has received training?',1,NULL),(147,233,9,9,'How does the School Head usually take routine management decisions?',1,NULL),(148,234,9,9,'The directions/ decisions communicated to teachers are clearly understood by:',1,NULL),(149,235,9,9,'How often does the School Head review implementation of the plan and assess the progress made, particularly in the prioritized areas?',1,NULL),(150,236,9,9,'Has the School Head constituted teams for different tasks and made them accountable?',1,NULL),(151,237,9,9,'How does the School Head monitor teachers\' performance?',1,NULL),(152,238,9,9,'How does the School Head monitor learners\' progress in learning?',1,NULL),(153,239,9,9,'Number of learners:',1,NULL),(154,240,9,9,'Number of learners of different categories enrolled in the school:',1,NULL),(155,241,9,9,'a) Number of CWSN in different categories enrolled in the school:',1,NULL),(156,242,9,9,'Number of learners given scholarships in the following categories:',1,NULL),(157,243,9,9,'i. Are resource persons available for CWSN?',1,NULL),(158,244,9,9,'Do you have evacuation plans in the event of fire, earthquake, flood, landscaping, etc.?',1,NULL),(159,245,9,9,'List the committees, if any, dealing with sexual harassment or abuse:',1,NULL),(160,246,9,9,'Has the school arranged for any counselling session for students?',1,NULL),(161,247,9,9,'a. Number of learners who have undergone medical/ health check-up last year',1,NULL),(162,248,9,9,'Number of members of SMC/ SDMC:',1,NULL),(163,249,9,9,'Composition of SMC/ SDMC: <br> (Provide number of representatives for each category in given box)',1,NULL),(164,250,9,9,'Number of meetings organized during the last academic year:',1,NULL),(165,251,9,9,'Average attendance in the meetings organized during the last academic year:',1,NULL),(166,252,9,9,'Number of SMC/ SDMC members who have received training:',1,NULL),(167,253,9,9,'Activities/ areas in which SMC/ SDMC provided support to school last year:',1,NULL),(168,254,9,10,'from',1,NULL),(169,255,9,10,'to',1,NULL),(170,256,9,10,'i. In Primary classes:',1,NULL),(171,257,9,10,'ii. In Upper primary classes:',1,NULL),(172,258,9,10,'iii. In Secondary classes:	',1,NULL),(173,259,9,10,'a. good	',1,NULL),(174,260,9,10,'b. needs minor repair',1,NULL),(175,261,9,10,'c. needs major repair',1,NULL),(176,262,9,10,'d. no building',1,NULL),(177,263,9,10,'b) Number of classrooms with adequate space for learner (SSA/ RMSA norms)',1,NULL),(178,264,9,10,'a. school head',1,NULL),(179,265,9,10,'b. staff',1,NULL),(180,266,9,10,'c. girls',1,NULL),(181,267,9,10,'any other',1,NULL),(182,268,9,10,'if yes, area (sq. metre)',1,NULL),(183,269,9,10,'a. full-time librarian',1,NULL),(184,270,9,10,'b. teacher',1,NULL),(185,271,9,10,'c. School Head',1,NULL),(186,272,9,10,'d. any other arrangement',1,NULL),(187,273,9,10,'a. dictionaries and Encyclopaedias',1,NULL),(188,274,9,10,'b. newspapers',1,NULL),(189,275,9,10,'c. magazines',1,NULL),(190,276,9,10,'d. other Reference Books',1,NULL),(191,277,9,10,'a. integrated science laboratory',1,NULL),(192,278,9,10,'b. separate laboratories for different purposes (demonstrating experiments)',1,NULL),(193,279,9,10,'c. only a corner or almirah for keeping apparatus and equipment',1,NULL),(194,280,9,10,'d. no equipment for conducting experiments',1,NULL),(195,281,9,10,'a. teaching-learning',1,NULL),(196,282,9,10,'b. administration',1,NULL),(197,283,9,10,'c. library',1,NULL),(198,284,9,10,'d. no computer',1,NULL),(199,285,9,10,'a. school head only',1,NULL),(200,286,9,10,'b. teachers only',1,NULL),(201,287,9,10,'c. learners',1,NULL),(202,288,9,10,'d. not available',1,NULL),(203,289,9,10,'a. radio',1,NULL),(204,290,9,10,'b. television',1,NULL),(205,291,9,10,'c. CD/DVD player',1,NULL),(206,292,9,10,'d. LCD projector',1,NULL),(207,293,9,10,'e. generator',1,NULL),(208,294,9,10,'f. any other ',1,NULL),(209,295,9,10,'a. toilet seats for boys',1,NULL),(210,296,9,10,'b. toilet seats for girls',1,NULL),(211,297,9,10,'c. toilet units for CWSN',1,NULL),(212,298,9,10,'d. urinals for boys',1,NULL),(213,299,9,10,'e. separate toilets for staff',1,NULL),(214,300,9,10,'f. no toilets',1,NULL),(215,301,9,10,'g. only one toilet unit',1,NULL),(216,302,9,10,'a. hand-washing',1,NULL),(217,303,9,10,'b. drinking water (if different)',1,NULL),(218,304,9,10,'a. tube-well/ hand pump',1,NULL),(219,305,9,10,'b. supplied through taps from a common source',1,NULL),(220,306,9,10,'c. any other',1,NULL),(221,307,9,10,'a. boiling',1,NULL),(222,308,9,10,'b. chlorination',1,NULL),(223,309,9,10,'c. filtration',1,NULL),(224,310,9,10,'d. no arrangement',1,NULL),(225,311,9,10,'e. any other',1,NULL),(226,312,9,10,'a. taps',1,NULL),(227,313,9,10,'b. buckets and mugs',1,NULL),(228,314,9,10,'c. no facility',1,NULL),(229,315,9,10,'d. any other',1,NULL),(230,316,9,10,'b. Number of times cleaned in previous years',1,NULL),(231,317,9,10,'a. verandah/ corridor',1,NULL),(232,318,9,10,'b. assembly hall',1,NULL),(233,319,9,10,'c. open space',1,NULL),(234,320,9,10,'d. designated place exists',1,NULL),(235,321,9,10,'a. prepared in the school',1,NULL),(236,322,9,10,'b. supplied from outside (by some agency)',1,NULL),(237,323,9,10,'ii. If it is prepared in the school, is there a kitchen shed or a room for cooking\nMid-day meal for learners in school?',1,NULL),(238,324,9,10,'If yes, a. Number of rooms having fans',1,NULL),(239,325,9,10,'b. Number of rooms having light facility (in the form of bulbs, CFLs, tubes)',1,NULL),(240,326,9,10,'a. number of learners given free textbooks',1,NULL),(241,327,9,10,'b. number of learners given uniforms',1,NULL),(242,328,9,10,'c. number of learners given scholarships',1,NULL),(243,329,9,10,'d. other incentives and number of eligible learners (please mention)',1,NULL),(244,330,9,10,'a. School records',1,NULL),(245,331,9,10,'b. Interaction with parents',1,NULL),(246,332,9,10,'c. Asking learners themselves',1,NULL),(247,333,9,10,'d. Other sources',1,NULL),(248,334,9,10,'a. unaware of resources',1,NULL),(249,335,9,10,'b. aware of resources but unable to access them',1,NULL),(250,336,9,10,'c. resources they have access to and use',1,NULL),(251,337,9,10,'a. Attainment in scholastic and co-scholastic areas',1,NULL),(252,338,9,10,'b. Evidence from interaction with learners in class',1,NULL),(253,339,9,10,'c. Discussion with other teachers',1,NULL),(254,340,9,10,'d. Observation of learner behaviour both in and outside class',1,NULL),(255,341,9,10,'e. Unable to assess',1,NULL),(256,342,9,10,'a. meetings with parents/ guardians in contact register',1,NULL),(257,343,9,10,'b. reminder/ letter sent to the parents/ guardians of learners irregular with attendance',1,NULL),(258,344,9,10,'c. other',1,NULL),(259,345,9,10,'d. no record',1,NULL),(260,346,9,10,'b. Is average attendance calculated monthly for every class?',1,NULL),(261,347,9,10,'a. classes are combined with that of other teachers',1,NULL),(262,348,9,10,'b. another free teacher takes the class',1,NULL),(263,349,9,10,'c. no arrangement made',1,NULL),(264,350,9,10,'d. any other',1,NULL),(265,351,9,10,'If, yes then',1,NULL),(266,352,9,10,'i. personal hygiene is stressed upon occasionally during school assembly',1,NULL),(267,353,9,10,'ii. checking and questioning individual learners in class or during school assembly almost daily',1,NULL),(268,354,9,10,'iii. stressing importance of personal hygiene during school assembly',1,NULL),(269,355,9,10,'iv. any other ',1,NULL),(270,356,9,10,'a. By observing learners in class and during their participation in games/ sports and other co-scholastic activities ',1,NULL),(271,357,9,10,'b. By keeping a record of learners’ participation and attainment',1,NULL),(272,358,9,10,'c. No efforts made to monitor personal-social development',1,NULL),(273,359,9,10,'d. Any other ',1,NULL),(274,360,9,10,'a. By counting periodic tests',1,NULL),(275,361,9,10,'b. Half yearly',1,NULL),(276,362,9,10,'c. Annual exams',1,NULL),(277,363,9,10,'d. By awarding grades based on marks',1,NULL),(278,364,9,10,'a. sanctioned strength',1,NULL),(279,365,9,10,'b. in position',1,NULL),(280,366,9,10,'a. regular',1,NULL),(281,367,9,10,'b. contractual/ ad-hoc',1,NULL),(282,368,9,10,'c. part- time',1,NULL),(283,369,9,10,'d. guest teacher',1,NULL),(284,370,9,10,'e. any other',1,NULL),(285,371,9,10,'a. trained teachers',1,NULL),(286,372,9,10,'b. untrained teachers',1,NULL),(287,373,9,10,'a. organizing special orientation program',1,NULL),(288,374,9,10,'b. head/ senior teachers in face- to- face meeting',1,NULL),(289,375,9,10,'c. no special orientation is done and new teachers get oriented gradually on their own',1,NULL),(290,376,9,10,'d. any other mechanisms ',1,NULL),(291,377,9,10,'(i) If yes, list the reasons for absence (e.g. being on leave, being deputed for training or other details):',1,NULL),(292,378,9,10,'(ii) How is this information compiled to compute average attendance of teachers afterwards?',1,NULL),(293,379,9,10,'(iii) How does the school deal with unreported absence of teachers and other staff members?',1,NULL),(294,380,9,10,'a. assigning substitute teachers',1,NULL),(295,381,9,10,'b. combining classes',1,NULL),(296,382,9,10,'c. assigning a class to the senior',1,NULL),(297,383,9,10,'d. no alternative arrangements student',1,NULL),(298,384,9,10,'a. annual Confidential Report',1,NULL),(299,385,9,10,'b. school head’s observations',1,NULL),(300,386,9,10,'c. learners’ achievement',1,NULL),(301,387,9,10,'d. feedback from Parents’/ SMC',1,NULL),(302,388,9,10,'e. peer/learners’ feedback',1,NULL),(303,389,9,10,'f. any other ',1,NULL),(304,390,9,10,'If yes, what are the main points in it for future development of school?',1,NULL),(305,391,9,10,'If yes, what are the main recommendations for the current year in the plan?',1,NULL),(306,392,9,10,'If yes, to what extent were its goals for that year achieved and what were the reasons for shortfall, if any?',1,NULL),(307,393,9,10,'a. Financial Management',1,NULL),(308,394,9,10,'b. ICT',1,NULL),(309,395,9,10,'c. School Leadership',1,NULL),(310,396,9,10,'d. Any other',1,NULL),(311,397,9,10,'(ii) When and where did she/ he receive training?',1,NULL),(312,398,9,10,'a. On his/ her own',1,NULL),(313,399,9,10,'b. In consultation with a few teachers',1,NULL),(314,400,9,10,'c. With involvement of all teachers',1,NULL),(315,401,9,10,'d. With involvement of teachers, parents and SMC',1,NULL),(316,402,9,10,'a. all teachers',1,NULL),(317,403,9,10,'b. most teachers',1,NULL),(318,404,9,10,'c. a few teachers only',1,NULL),(319,405,9,10,'d. no teacher',1,NULL),(320,406,9,10,'a. Regularly',1,NULL),(321,407,9,10,'b. Occasionally',1,NULL),(322,408,9,10,'c. Rarely',1,NULL),(323,409,9,10,'d. Does not review',1,NULL),(324,410,9,10,'If yes, which are these teams/ committees and what tasks are assigned to them?',1,NULL),(325,411,9,10,'a. By discussing progress individually with teachers',1,NULL),(326,412,9,10,'b. By reviewing the performance of their learners in tests from time to time',1,NULL),(327,413,9,10,'c. By observing the classrooms occasionally to check how teaching is done',1,NULL),(328,414,9,10,'d. Any other',1,NULL),(329,415,9,10,'a. By reviewing record of CCE of learners of every class',1,NULL),(330,416,9,10,'b. By discussing the performance of learners with teachers',1,NULL),(331,417,9,10,'c. By checking the results of all learners in tests and taking note of the change/ improvement in results over a time',1,NULL),(332,418,9,10,'d. Progress is not reviewed by the School Head',1,NULL),(333,419,9,10,'e. Teachers monitor their progress at their level',1,NULL),(335,420,9,10,'a. boys',1,NULL),(336,421,9,10,'b. girls',1,NULL),(337,422,9,10,'c. other',1,NULL),(338,423,9,10,'a. SC',1,NULL),(339,424,9,10,'b. ST',1,NULL),(340,425,9,10,'c. OBC',1,NULL),(341,426,9,10,'d. BPL/ EWS',1,NULL),(342,427,9,10,'e. GEN',1,NULL),(343,428,9,10,'f. CWSN',1,NULL),(344,429,9,10,'b) Number of CWSN in different categories getting aids and appliances:',1,NULL),(345,430,9,10,'a. CWSN',1,NULL),(346,431,9,10,'b. BPL/ EWS',1,NULL),(347,432,9,10,'c. SC',1,NULL),(348,433,9,10,'d. OBC',1,NULL),(349,434,9,10,'e. Girls',1,NULL),(350,435,9,10,'f. ST',1,NULL),(351,436,9,10,'ii. Areas in which programs have been organized for learners:',1,NULL),(352,437,9,10,'a. first-aid',1,NULL),(353,438,9,10,'b. adolescent/ sex education',1,NULL),(354,439,9,10,'c. substance abuse',1,NULL),(355,440,9,10,'d. safety mock drills',1,NULL),(356,441,9,10,'e. road safety/ traffic regulation programme',1,NULL),(357,442,9,10,'b. List the items covered in health checkups:',1,NULL),(358,443,9,10,'c. Number of camps arranged by the school last year:',1,NULL),(359,444,9,10,'i. medical/ health',1,NULL),(360,445,9,10,'ii. HB check-up camp',1,NULL),(361,446,9,10,'iii. road safety awareness programme',1,NULL),(362,447,9,10,'iv. health, hygiene & sanitation awareness camp',1,NULL),(363,448,9,10,'d. i. Number of times health practitioner invited for medical/ health camp',1,NULL),(364,449,9,10,'ii. Give the details of the practitioner(s)',1,NULL),(367,450,9,10,'a. parents',1,NULL),(368,451,9,10,'b. teachers',1,NULL),(369,452,9,10,'c. women',1,NULL),(370,453,9,10,'d. minorities',1,NULL),(371,454,9,10,'e. local authorities',1,NULL),(372,455,9,10,'f. SC/ ST',1,NULL),(373,456,9,11,'Open area is insufficient with limited space for assembly; kuchcha/ semi-pucca/ tent type building is available; boundary wall/ fence doesn’t exist or is discontinuous with big gaps; no garden/ trees in the compound',1,NULL),(374,457,9,11,'Open space is used only for assembly; ground is uneven; premises appear to be unclean and lacking maintenance; major repairs are needed in floor/ walls/ roof/ doors/ windows, etc.',1,NULL),(375,458,9,11,'Playground is unavailable; school occasionally uses the playground of a neighbourhood school or a community Space; no or limited equipment/ material is available',1,NULL),(376,459,9,11,'Learners sometimes play only those games for which no or minimum equipment is needed; no guidance and supervision is available',1,NULL),(377,460,9,11,'Classrooms are crowded; no other rooms are available except for School Head; furniture (mats in the case of primary classrooms) is available but not sufficient',1,NULL),(378,461,9,11,'Classrooms including other rooms are poorly ventilated with inadequate natural/ electric light; some classrooms have poor quality of blackboards with few displays like charts and maps; furniture is of poor quality and requires repairs or replacement',1,NULL),(379,462,9,11,'No provision for electricity; battery operated gadgets like radio etc. are Available',1,NULL),(380,463,9,11,'School borrows/ hires generator/ battery and other electrical equipments for special occasions',1,NULL),(381,464,9,11,'Books are insufficient in number; library room and/ or reading space is not available',1,NULL),(382,465,9,11,'Books are not properly catalogued; no specific library period in the time table; books are generally not issued for reading at home',1,NULL),(383,466,9,11,'No separate laboratory; some space is earmarked for keeping equipment and laboratory materials',1,NULL),(384,467,9,11,'Teachers demonstrate some of the experiments in the class; learners seldom get an opportunity to perform experiments',1,NULL),(385,468,9,11,'School has no computer used for teaching learning purposes; digital learning materials not available',1,NULL),(386,469,9,11,'Absence of opportunity for teachers and learners to use computers',1,NULL),(387,470,9,11,'No ramp',1,NULL),(388,471,9,11,'No ramp facility for learners who are physically challenged',1,NULL),(389,472,9,11,'No proper kitchen shed or designated room for cooking food; there is only a make shift arrangement as a kitchen; cooking utensils are not adequate; no specified place for learners to have their midday meal',1,NULL),(390,473,9,11,'No effort made to keep utensils covered while cooking and storing food; the eating place for learners is unhygienic',1,NULL),(391,474,9,11,'Drinking water facility is available but supply is insufficient',1,NULL),(392,475,9,11,'Drinking water is used as supplied from the source/s without any quality check',1,NULL),(393,476,9,11,'Insufficient supply of water and inadequate number of hand-wash outlets/ stations; no provision for soap',1,NULL),(394,477,9,11,'Hand-wash stations/ water containers are seldom cleaned and maintained; teachers rarely communicate to learners the importance of hand-washing; learners seldom wash hands or wash them without soap',1,NULL),(395,478,9,11,'None or insufficient number of toilets are available; no separate toilets for boys, girls and CWSN',1,NULL),(396,479,9,11,'Toilets are in poor condition and cleaned irregularly; sufficient water is not available for flushing and cleaning toilets',1,NULL),(397,480,9,11,'Teachers are aware of the sociocultural and economic background of the community from where learners come; have a general idea of the home background and learning levels of the learners',1,NULL),(398,481,9,11,'Teachers often experience difficulty in teaching certain concepts due to lack of understanding of the same; make limited efforts to improve their content knowledge and pedagogical skills',1,NULL),(399,482,9,11,'Teachers teach the lesson as per the textbook, with a focus on completion of syllabus; are aware of the topic to be taught and teaching-learning material to be used in their teaching',1,NULL),(400,483,9,11,'Teachers address learners by name; make basic resources available for teaching-learning',1,NULL),(401,484,9,11,'Teachers use only the textbooks and blackboard to teach in class; sometimes make learners copy from the blackboard; class work and home work is given to learners occasionally',1,NULL),(402,485,9,11,'Teachers manage the class, making learners sit in rows facing the blackboard; instruct the class from a fixed position and learners listen passively; ensure discipline by maintaining silence in the class',1,NULL),(403,486,9,11,'Teachers assess learners as per applicable policy; generally tests that are given to assess rote learning and factual knowledge obtained from the content and exercises in the textbooks; learners’ performance is communicated to the parents only through report cards',1,NULL),(404,487,9,11,'Teachers mainly use textbooks for teaching in the class; use other TLM, which may be sporadic and not planned for',1,NULL),(405,488,9,11,'Teachers occasionally reflect on their teaching-learning practice and learners’ progress',1,NULL),(406,489,9,11,'Teachers take and record attendance of learners regularly; identify learners who are frequently absent or not punctual; display class-wise attendance of the learners on the school notice board; sometimes inform parents about frequently absent learners',1,NULL),(407,490,9,11,'Learners listen quietly to teachers in the classroom without much interaction; organizes mandated school functions and co-scholastic activities; the same students usually participate in these activities',1,NULL),(408,491,9,11,'School documents and maintains records of learner’s progress data as per mandate such as in the form of report cards, CCE register, etc.',1,NULL),(409,492,9,11,'School is aware of the indicators of personal and social development of learners e.g. spirit of nationalism, tolerance, secular behavior, good interpersonal relations, etc.; organizes activities like morning assembly, celebration of national days and festivals as per mandate',1,NULL),(410,493,9,11,'Very few learners attain curricular expectations (knowledge and skills) in every grade as measured; school conducts assessment at the end of each academic year to ascertain grade exit levels of learners in all curricular areas',1,NULL),(411,494,9,11,'School leaves it to the new teacher to acquire information about available facilities and observes the ongoing practices of the school',1,NULL),(412,495,9,11,'School maintains record of attendance along with the reasons for absence; generally no alternate arrangements are made to engage the class',1,NULL),(413,496,9,11,'School provides a pre-designed time-table and expects the teacher to complete the syllabus and perform other duties as assigned from time to time',1,NULL),(414,497,9,11,'Teachers are aware of the changes, if any, in the school curriculum and textbooks resulting from changes in policy',1,NULL),(415,498,9,11,'School Head takes note of teachers’ performance as reflected in mandatory inspection reports; checks the presence of teachers in their classrooms and observes their teaching occasionally',1,NULL),(416,499,9,11,'School Head ensures the participation of all teachers in the mandatory in-service training programmes',1,NULL),(417,500,9,11,'School Head develops a School Development Plan (SDP) as per the given mandate; the other stakeholders do not find an opportunity to participate in the planning process',1,NULL),(418,501,9,11,'School Head is broadly aware of areas that need attention; acts on issues in response to official mandate and immediate needs; the required change is rarely discussed and reflected upon',1,NULL),(419,502,9,11,'School Head ensures that all classes are taken regularly, makes alternative arrangements for classes when teachers are absent; ensures effective classroom teaching by taking rounds; is aware of learners’ performance in different classes and subjects',1,NULL),(420,503,9,11,'School Head manages routine activities and school resources (staff, material and financial); allocates responsibilities to a few; takes decisions; acts on the orders and instructions received from the authorities for compliance; communicates decisions; shares the orders and instructions received',1,NULL),(421,504,9,11,'School ensures that no child is denied admission on the basis of caste, gender, language, economic status, disability, etc.; convinces parents of diverse backgrounds to send their children regularly to the school',1,NULL),(422,505,9,11,'Teachers are aware of children with visible disabilities; school maintains records of the same; extends support for activities for which funds and resources are provisioned and documents the same',1,NULL),(423,506,9,11,'School checks its status of compliance against existing laws on school safety, including road safety norms and safety status of school building; takes measures to ensure safety in the existing school building and additional construction, if any; ensures that the building and its surroundings have necessary safety provisions e.g. displays providing information on safety equipments, emergency exits, emergency contact numbers, first-aid kits, fire-extinguishers, etc.',1,NULL),(424,507,9,11,'School is aware of the policy on child abuse and exploitation; does not allow corporal punishment or verbal abuse',1,NULL),(425,508,9,11,'School occasionally checks cleanliness and sanitation of its premises and the personal hygiene of children; provides dustbin for waste; records height and weight measurements of all children',1,NULL),(426,509,9,11,'Meetings are organized without a pre-determined agenda; only a few members attend the meetings; SMC/ SDMC takes decisions largely in the areas of finance and infrastructure',1,NULL),(427,510,9,11,'SMC/ SDMC is aware of the provisions of the RTE Act-2009 as well as SSA/ RMSA provisions relating to school; School Development Plan(SDP) is shared at the SMC/ SDMC meetings',1,NULL),(428,511,9,11,'Parents and community members are invited to school functions; school informs parents about the facilities available in the school and challenges currently faced',1,NULL),(429,512,9,11,'School uses available help from the community to organise visits to institutions/ places of interest in the vicinity of the school',1,NULL),(430,513,9,11,'SMC identifies additional resources required for the implementation of SDP and potential sources for procuring the same',1,NULL),(431,514,9,11,'Open and built area is just sufficient with available assembly hall/ space but inadequate to accommodate all learners comfortably; pucca building exists with boundary wall/ fence without gate; few garden/trees in the compound',1,NULL),(432,515,9,11,'Assembly space/ hall is used for organizing other activities such as physical exercises, organizing functions, etc.; ground is even; minor repairs are needed in floor/ walls/ roof/ doors; occasional maintenance is undertaken',1,NULL),(433,516,9,11,'Playground of inadequate size is available; playground of other school used occasionally for certain games; adequate material and equipment available only for a few games',1,NULL),(434,517,9,11,'Learners utilize the playground well, though for limited number of games; specific time is allocated for sports/games; playground activity is always supervised; equipment is maintained and made available as and when required; sports events are conducted in the school playground or outside',1,NULL),(435,518,9,11,'A few classrooms are crowded; rooms for School Head and common room for teachers are available; furniture is adequate as per requirements of the School',1,NULL),(436,519,9,11,'Majority of classrooms have good ventilation , natural light and fans(where needed); most classrooms have charts and maps displayed on the walls; furniture is comfortable and caters to the needs of the learners',1,NULL),(437,520,9,11,'Electric supply is irregular; no alternative arrangements for power failure/ cuts; all rooms have electric lights and fans; electronic equipment(T.V, radio, etc.) are available',1,NULL),(438,521,9,11,'Wiring and switch boards are in good condition; electrical equipment (fans, etc.) is serviced from time to time',1,NULL),(439,522,9,11,'Sufficient number of books, magazines and newspapers are available and updated regularly; reading space/ library room is available; no e-books or digitized material',1,NULL),(440,523,9,11,'Books are well-kept, catalogued and issued regularly; library period(s) is allotted in the time table; new books are added as and when resources are available',1,NULL),(441,524,9,11,'Basic equipment for demonstration is available; composite laboratory for science and mathematics exists (applicable to upper primary and secondary School)',1,NULL),(442,525,9,11,'Teachers give exposure to learners by demonstrating prescribed experiments as per the syllabus; learners sometimes get an opportunity to conduct experiments in the laboratory/ies; safety measures are in place',1,NULL),(443,526,9,11,'School has a few computers accessible to both teachers and learners; some software and digital teaching learning materials available; no internet facility exists',1,NULL),(444,527,9,11,'Teachers use computers and digital material appropriately for different classes and subjects; learners are occasionally given the opportunity to use computers',1,NULL),(445,528,9,11,'Ramp present but not as per specifications',1,NULL),(446,529,9,11,'Physically challenged learners need assistance while using the ramp',1,NULL),(447,530,9,11,'Kitchen shed or separate room for cooking is available through space is insufficient; utensils are adequate in size and number; sitting space for learners to eat is specified but insufficient',1,NULL),(448,531,9,11,'Cooking utensils are clean for use and kept covered while cooking/ storing food; eating place for learners is hygienic',1,NULL),(449,532,9,11,'Sufficient and regular supply of drinking water; if underground water, then facility for purification is available',1,NULL),(450,533,9,11,'Drinking water is purified, if required; water storage facility is cleaned regularly',1,NULL),(451,534,9,11,'Sufficient supply of water but inadequate hand-wash outlets/ stations; inadequate supply for soap is made',1,NULL),(452,535,9,11,'Hand-wash stations/ water containers are cleaned and maintained on a regular basis; teachers communicate the importance of hand-washing during school assembly; the monitoring of hand-washing is undertaken occasionally',1,NULL),(453,536,9,11,'Separate toilets for boys and girls are available; number of seats and urinals not sufficient (as per Norms)',1,NULL),(454,537,9,11,'Toilets are functional and cleaned at least once a day; water is available for flushing and cleaning for a limited time; maintenance of toilets is undertaken occasionally',1,NULL),(455,538,9,11,'Teachers understand the sociocultural and economic background of the community and the learning needs of the learner; develop an understanding of the learning needs of learners through classroom experiences and personal interaction with other teachers, parents/ guardians and community',1,NULL),(456,539,9,11,'Teachers sometimes face difficulty in explaining difficult concepts in their subjects; lack appropriate pedagogical skills; make efforts to upgrade their content knowledge and pedagogical skills with the available support and resources e.g. subject forums, training programmes',1,NULL),(457,540,9,11,'Teachers prepare and maintain a diary with detailed plan including teaching and assessment strategies and TLM to be used; prepare additional teaching-learning material using local resources',1,NULL),(458,541,9,11,'Teachers make all learners comfortable and involve them in class activities; plan and organize group work/activities and display learners’ work and charts, etc. on the wall; TLMs are accessible to all',1,NULL),(459,542,9,11,'Teachers use a variety of support materials to involve learners in discussions; conduct experiments in the classroom to explain concepts; make special efforts to explain concepts to learners who need additional help; teachers check home work and provide appropriate feedback',1,NULL),(460,543,9,11,'Teachers manage space for organizing different activities in the classroom and outside giving attention to CWSN; encourage punctuality and regularity among learners; learners follow class management rules set by teachers',1,NULL),(461,544,9,11,'Teachers use a variety of activities/ tasks to assess all the curricular areas including art, health and physical education on set criteria; provide descriptive feedback highlighting areas of improvement in the progress report card; regularly interact with parents to share learners’ progress',1,NULL),(462,545,9,11,'Teachers use other resources in addition to textbooks such as reference materials, charts, maps, models, digital learning kits, local resources; use science, mathematics and language kits/ laboratories, as and when appropriate; school maintains a catalogue of resources and makes it available to the teachers as and when required',1,NULL),(463,546,9,11,'Teachers regularly reflect on their teaching-learning practice and record the same; revisit their plans, teaching-learning practice and make efforts for necessary improvement',1,NULL),(464,547,9,11,'School provides regular information about learner attendance to parents; identifies the reasons for prolonged and frequent absence; discusses with learners and parents about the implication of low attendance on learning, making home visits as and when appropriate',1,NULL),(465,548,9,11,'A few learners actively participate in classroom discussion and interactions; school organizes a variety of co-scholastic activities and cultural programmes; teachers motivate learners to actively participate in the same; a large number of students participate in these activities',1,NULL),(466,549,9,11,'School continuously gauges individual learner’s progress against curricular expectations (scholastic and co-scholastic); creates a cumulative database across classes and for different groups of learners that is updated annually',1,NULL),(467,550,9,11,'Teachers organize group activities in the class with a view to develop social and interpersonal skills; organize meetings with parents/ community for discussing social and personal development needs of learners',1,NULL),(468,551,9,11,'Some learners attain most of the curricular expectations (knowledge and skills) in every grade while most remain marginally below grade level expectations; school arranges for remedial measures for improving attainment levels and thereby preparing learners for next grade',1,NULL),(469,552,9,11,'School head orients the new teacher about his/ her responsibilities and the facilities available within the school; usually involves other teachers in orienting the new teacher',1,NULL),(470,553,9,11,'School systematically maintains attendance records, monitors unreported absence and takes action, if necessary; makes arrangements to ensure the class is not left unattended',1,NULL),(471,554,9,11,'School Head briefs the teachers about their responsibilities and performance goals, informally or at staff meetings; reviews and monitors the completion of the syllabus, assigned responsibilities and other tasks as expected',1,NULL),(472,555,9,11,'Teachers make efforts to understand the changing curricular expectations; adapt their teaching learning practice to suit the same',1,NULL),(473,556,9,11,'School Head reviews the teachers’ performance and provides them feedback; teachers review their own performance based on the classroom experiences and identify areas of improvement',1,NULL),(474,557,9,11,'School motivates and creates regular opportunities for teachers to participate in different programmes relevant to their professional needs; seeks support from other academic institutions/ experts to address difficulties faced by teachers',1,NULL),(475,558,9,11,'School Head develops a vision/ mission statement; teachers are involved in the creation of the SDP, prioritization of tasks; School Head allocates responsibilities to majority of teachers for SDP implementation; provides direction for its implementation',1,NULL),(476,559,9,11,'School Head, in consultation with teachers, identifies the strengths of the school, and areas that need improvement; reflects upon the required changes; identifies action points and appropriately acts upon them; logically assesses the progress and refines actions, where required; takes note of the changes that are being reflected in the teaching-learning and other school practices',1,NULL),(477,560,9,11,'School Head regularly observes the teaching-learning process in different classes and provides written/ verbal feedback to teachers individually; analyses and reviews the learners’ performance in different classes and subjects and discusses the same with concerned teachers/ subject teachers',1,NULL),(478,561,9,11,'School Head and staff plans and manage routine activities and school resources (staff, material and financial); involve staff in decision-making; School Head communicates details of the SDP and provides clear directions to staff members and takes the lead for its effective implementation',1,NULL),(479,562,9,11,'Teachers maintain equity among children, parents, peers and other staff on the basis of caste, gender, socio-economic background, etc. during classroom tasks, activities, seating arrangement, etc.; give special attention to girls and disadvantaged groups to promote equity',1,NULL),(480,563,9,11,'School is aware of Persons with Disabilities Act; leverages the support of available resource person to identify and support CWSN; teachers attempt to attend to their needs with special aids and curricular material; follow curriculum for CWSN with minor adaptations like making small changes in learning content, using appropriate learning approach and assessment methods; ensure CWSN are learning as per the targets mutually agreed upon with the parents',1,NULL),(481,564,9,11,'School ensures safe storage and usage of potentially hazardous materials with special attention to kitchen & laboratories; monitors entry and exit of visitors; undertakes safety drills as mandated; ties up with local agencies for handling emergency situations; makes arrangements for keeping the building safe from rodents, reptiles, stray dogs etc.; allocates responsibility for all safety related activities; identifies accident prone areas and ensures signboards are placed by relevant agencies in the vicinity of the school to prevent accidents; ensures the presence of personnel to regulate traffic during peak hours and in accident prone areas as and where needed',1,NULL),(482,565,9,11,'School staff is trained to recognize signs of sexual/ physical/ substance abuse; school creates awareness among children to differentiate between ‘good touch’ and ‘bad touch’; screens all digital/ non-digital learning material for objectionable content; ensures no child is left alone in isolated/ dark places; also ensures that there is no adverse psychological impact on children due to work overload by spacing out assignments, assessments, etc.; has a mechanism to address complaints and grievances of children and parents; undertakes background checks of all adults working in the school',1,NULL),(483,566,9,11,'School has a policy on health, hygiene and sanitation; continuously ensures cleanliness and sanitation of all its facilities, the quality of the Mid Day Meal and the personal hygiene of children through regular checks and drives; undertakes appropriate measures for waste disposal; initiates awareness building programs; creates growth charts of children to check status of their health; facilitates regular primary health checks (including dental and eye checkup)',1,NULL),(484,567,9,11,'Meetings are organized as per the mandate with prior notice and fixed agenda; most members attend the meetings and participate in the discussions; SMC/ SDMC also take decisions on issues other than finance and infrastructure',1,NULL),(485,568,9,11,'SMC/ SDMC facilitates implementation and ensures compliance with RTE Act-2009 as well as SSA/ RMSA provisions; suggests activities that require immediate attention in the SDP; shares information related to RTE Act-2009 as well as SSA/ RMSA with the community',1,NULL),(486,569,9,11,'School interacts with the community and discusses the issues relating to the socioeconomic background, enrolment, attendance, etc. of learners; SMC mobilizes resources for maintenance of the school and for improving its facilities; school and community jointly organize functions within the school and in the community',1,NULL),(487,570,9,11,'School takes initiatives to develop understanding among learners about the culture, oral history and traditional knowledge (folk songs, art and craft, agricultural practices, etc.) of the community; displays the photographs and pictures of renowned people and important places and features of the community; invites local artisans and craftsmen to interact with learners',1,NULL),(488,571,9,11,'SMC mobilizes resources for maintenance of the school and for improving its facilities; school and community jointly organize functions within the school and in the community',1,NULL),(489,572,9,11,'Ample open and built spaces available for free movement of learners with designated space for assembly; boundary wall/ fencing with plantation and gate exists; well maintained garden and lawn.',1,NULL),(490,573,9,11,'Open space and building are clean and well-maintained; repairs are undertaken in a timely manner',1,NULL),(491,574,9,11,'Playground of adequate size is available within school premises; adequate sports equipment and material also available for a variety of games',1,NULL),(492,575,9,11,'Learners participate in a variety of games/ sports in a planned manner; facility for training/ coaching for sports is available; school maintains inventories of all equipment and replenishes material as and when necessary; organizes inter-school sports meet every year',1,NULL),(493,576,9,11,'All the classrooms have adequate space for learners and for group work and other activities; additional rooms to be used as office, store, craft, etc. are available; each classroom has sufficient number of benches and chairs; teachers have lockers/ cupboards',1,NULL),(494,577,9,11,'Every classroom has good ventilation, light; other rooms are appropriately furnished; display of pictures are used to create an attractive environment; furniture is well-arranged and aesthetically pleasing; is age-appropriate and friendly for differently-abled Learners',1,NULL),(495,578,9,11,'School has its own power back-up facility, such as generator or inverter to deal with power failures; all rooms have adequate electric lights and fans; public address system is in place',1,NULL),(496,579,9,11,'Miniature Circuit Breaker switches (MCB), are in place to prevent fire due to short circuit; all electrical and electronic equipment are regularly checked, maintained and kept in working Order',1,NULL),(497,580,9,11,'A large collection of books is available; periodicals, magazines, newspapers are regularly subscribed to; a separate room for library with adequate reading space is available; e-books and digitized materials are available',1,NULL),(498,581,9,11,'Books are properly catalogued, arranged systematically in shelves and regularly used by learners and teachers; library provides access to ebooks and digitized materials; library resources support curricular transaction; regular addition of new books is done through an appropriate selection procedure keeping in mind the age, linguistic background , academic needs of learners and teachers',1,NULL),(499,582,9,11,'All required laboratories are available and are wellequipped with materials, instruments as per state norms and specifications; running water and electric supply is ensured',1,NULL),(500,583,9,11,'Every learner is given an opportunity to conduct all prescribed experiments in the laboratory; teacher utilizes/ uses the laboratory to conducts experiments simultaneously while transacting the relevant topic in the class',1,NULL),(501,584,9,11,'School has computer room with sufficient numbers of computers with internet access; up-todate adequate digital teaching learning material and software available',1,NULL),(502,585,9,11,'Teachers integrate the use of technology (computers and related digital material) in their teaching learning plan and implementation; also use computers in the assessment of learners; every learner gets an opportunity to use the computer',1,NULL),(503,586,9,11,'Ramp with hand rails, non-slippery walking surface, slope and height as per specifications',1,NULL),(504,587,9,11,'Ramp provides effortless access to physicallychallenged learners and can be used independently by them',1,NULL),(505,588,9,11,'Ample space in the kitchen shed or room for storage of utensils and cooking activity; adequate space for learners to have their midday meal',1,NULL),(506,589,9,11,'Kitchen and utensils are cleaned after every use; person responsible for cooking makes special efforts to maintain personal hygiene; proper seating arrangement is made for the learners to have their meals; designated teachers regularly supervise the mid-day meal programme',1,NULL),(507,590,9,11,'There is continuous supply of safe drinking water; retrofitting of drinking water facilities is done, if required, for maintenance and purification',1,NULL),(508,591,9,11,'School ensures regular supply of purified drinking water; cleanliness is maintained around drinking water facilities',1,NULL),(509,592,9,11,'Regular and sufficient supply of water; adequate number of hand-wash outlets/ stations available; adequate and regular supply of soap',1,NULL),(510,593,9,11,'Hand-wash stations are cleaned daily; school organizes hand-washing and hygiene drives through posters, slogans, songs, skits, etc.; regular sessions at various forums are held to develop a habit and stress the importance of hand-washing; School Head monitor learners’ personal hygiene regularly',1,NULL),(511,594,9,11,'Separate toilet seats and urinals for boys and girls are available in sufficient number; CWSN friendly toilet available',1,NULL),(512,595,9,11,'All the toilets are functional and maintained at all times; cleaning of toilets is undertaken regularly; continuous supply of water is available for flushing and cleaning; school maintains sanitation and cleanliness of toilets',1,NULL),(513,596,9,11,'Teachers seek feedback from learners and parents regarding learners’ performance in a systematic manner; address individual needs, learning style and strengths of learners',1,NULL),(514,597,9,11,'Teachers have mastery over content and pedagogical skills and hence rarely face difficulty in classroom transaction; take their own initiative and the support of their fellow teachers if needed for updating their knowledge and pedagogical skills; school also extends support in updating the same',1,NULL),(515,598,9,11,'School has a culture where every teacher designs lessons as per the varying learning needs of learners and makes the teaching learnercentric; uses TLMs appropriately; connects teaching-learning with immediate context and environment; plans appropriate strategies such as observation, exploration, discovery, analysis, critical reflection, problem-solving and drawing inferences to make learning effective',1,NULL),(516,599,9,11,'Teachers create a conducive and interactive environment in the classroom; encourage peer learning/interaction; provide opportunity for expression; appreciate the views of all learners; encourage questioning/sharing of ideas',1,NULL),(517,600,9,11,'Teachers provide opportunity to learners for self-learning through inquiry, exploration, discovery, experimentation and collaborative learning; ensure participation of each learner in the classroom discussion; get teaching-learning materials prepared by learners as required',1,NULL),(518,601,9,11,'Teachers and learners collectively decide on classroom management rules; seating arrangement is flexible and learners sit as per the needs of the activity they are engaged in; learners observe self –discipline and adhere to the rules developed collectively',1,NULL),(519,602,9,11,'Teachers consider assessment as an integral part of the teachinglearning process; analyze the learners’ past assessment records and link it with the current achievement levels; make continuous assessment and provide feedback on progress and attainment; assess other curricular areas, including personal and social qualities systematically with followup measures for improvement; use feedback from assessment to improve teaching-learning',1,NULL),(520,603,9,11,'Teachers integrate the use of TLM, local community resources, ICT support material, laboratories, library, etc. with the lessons appropriately; school facilitates networking with other schools for sharing resources',1,NULL),(521,604,9,11,'Teachers reflect individually and collectively on the planned and actual teaching-learning process in the light of its outcomes; identify the gaps between the two and plan for improvement; design alternative learning experiences based on the reflection',1,NULL),(522,605,9,11,'School analyzes attendance data of all learners; ascertains whether the high absence rates can be associated with any particular category of learners or at any period of the year; addresses the problem with the help of the SMC and parents; evolves measures to motivate learners and parents to ensure punctuality and regular attendance; acknowledges and appreciates punctuality and regularity of learners',1,NULL),(523,606,9,11,'All learners participate actively in classroom discussions and interact with teachers and peers; school identifies the talent of learners in different co-scholastic areas; provides them training and opportunities to excel in the same; all learners take interest and participate enthusiastically in various school functions and coscholastic activities',1,NULL),(524,607,9,11,'School tracks and monitors each learner’s progress across subjects and co-scholastic areas; tracks individual learner progress from the beginning and over time, keeping in mind the differential pace of learning of learners; analyzes the cumulative database to identify progress patterns and trends for classes and groups of learners; uses the findings of such analyses and incorporates the feedback in classroom practice; aspires to achieve/ exceed state/ national learner attainment levels',1,NULL),(525,608,9,11,'School integrates life skills development with day-to-day classroom transactions to promote creative and critical thinking, problem solving and decision making, communication and interpersonal skills; teachers create and use resources like stories, audio-video clips, etc. to create a conducive value ethos; teachers exemplify behavior as expected from learners; discuss with parents the role of both school and home in the personal and social development of the learner',1,NULL),(526,609,9,11,'Most learners’ attainment is at par/ above expected grade level across the school; school continuously improvises its mechanism to ascertain grade exit levels of learners',1,NULL),(527,610,9,11,'Special orientation programmes are organized systematically to apprise new teacher/s about roles and responsibilities, the school context, profile of the learners, curricular expectations, role of SMC/ SDMC and various schemes/ programmes being implemented in the school',1,NULL),(528,611,9,11,'School has an appropriate system to address short, long and unreported absence of teachers; makes timely and suitable arrangements for substitutes from within or outside the school and orients them to undertake the responsibility; creates a culture of punctuality and accountability among teachers',1,NULL),(529,612,9,11,'School allocates responsibilities of teachers through mutual consultation; encourages teachers to set their own performance goals and provides opportunities to innovate and experiment with new ideas; teachers themselves monitor their own progress',1,NULL),(530,613,9,11,'School creates opportunities for teachers to discuss and reflect upon the changing curricular expectations and its implications on their current classroom practice; provides follow-up support for teachers to adopt context-specific changes',1,NULL),(531,614,9,11,'School Head reviews the performance of teachers on the basis of learners’ progress and attainment and discussions with teachers; discusses teacher performance with parents, learners and SMC/ SDMC; teachers collectively reflect on their own performance and develop strategies for improvement',1,NULL),(532,615,9,11,'School makes provision for continuous academic mentoring of teachers; supports teachers in trying out innovative ideas and practices; teachers discuss collectively on inputs received during training; reflect on the possibility of integration of the acquired knowledge and skills in classroom practice',1,NULL),(533,616,9,11,'School Head engages all stakeholders in developing vision/ mission taking into account current practices, policies and programs which are subsequently documented; SDP is co-created by all the stakeholders and is aligned to the vision/ mission statement; appropriate prioritization is done for necessary action; all teachers understand their defined roles and responsibilities and act accordingly to make desired progress; periodic review of vision and SDP is undertaken regularly',1,NULL),(534,617,9,11,'School Head communicates clearly the need for change to all the stakeholders and enhances their understanding of the same; identifies clear targets and formulates predictable improvement strategies on the basis of analysis of evidence and other sources collectively with stakeholders; leads change and monitors incremental improvement regularly; distributes leadership roles and individual responsibilities for implementing change; encourages teachers to engage in evidence- based improvement and change in school practices',1,NULL),(535,618,9,11,'School Head and teachers collectively reflect on current teaching-learning practices and learners’ progress and attainment; discuss required improvement in the light of learning indicators, learner-centred pedagogy, innovative approaches to teaching, etc.; discuss performance of learners with parents',1,NULL),(536,619,9,11,'School Head and staff members collectively develop a shared vision and a strategic plan in consultation with parents and learners; distribute the responsibilities among the staff members on the basis of mutual consensus and areas of expertise; take action with mutual support, monitor and evaluate the progress collectively',1,NULL),(537,620,9,11,'School responds to the needs of all children with varying abilities and backgrounds; values and ensures participation of all children, irrespective of their different physical, emotional and learning abilities; encourages parents from diverse backgrounds to actively participate in SMC/ SDMC meetings and other school activities',1,NULL),(538,621,9,11,'School involves the community and local NGOs in the identification and subsequent support needed for CWSN; monitors and documents the progress of CWSN regularly; includes CWSN in general classrooms with the rest of the class; builds teacher capacity for the same through training; teachers share inspirational stories of accomplishments of people with special needs',1,NULL),(539,622,9,11,'School undertakes awarenessbuilding exercises on disaster management for all stakeholders; has a structured emergency response plan, including communication modes and mechanisms like maintenance contract (for keeping building free from rodents, animals, etc.); reviews such plans and mechanisms regularly; conducts training/seminar/ workshops periodically to sensitize learners on safety measures and precautions ; integrates awareness programmes and safety drills with teachinglearning; checks that transport arrangement is safe for learners; participates in traffic regulation awareness programmes',1,NULL),(540,623,9,11,'School adopts a structured approach to ensure emotional safety of all children which includes awareness building through dialogue and discussion, programs on child abuse, sex and adolescent education, regular one-on-one counseling sessions, dialogue to resolve complaints and grievances; checks the implementation of the policy on emotional safety and reviews the same on a regular basis; conducts counseling sessions for children and parents to ease out child anxieties related to curricular overload and pressure of performance, thereby helping children develop coping mechanisms; regular career counseling sessions are also held for appropriate age groups',1,NULL),(541,624,9,11,'School and SMC together monitor cleanliness, sanitation in the school and the personal hygiene of children; conduct orientation programs/ workshops on health, hygiene and sanitation for parents/ guardians; invite health practitioners for such events; advise parents/ guardians about health related problems noticed in the school; arrange for professional medical advice for children engaged in substance abuse',1,NULL),(542,625,9,11,'The SMC/ SDMC meetings are organized regularly and additionally when the need arises; identified issues and plans to resolve the same are discussed; the SMC/ SDMC also facilitates, monitors and reviews the implementation of the decisions',1,NULL),(543,626,9,11,'SMC/ SDMC participates in the school evaluation process; helps identify and prioritize development needs; jointly prepares the SDP with the teachers and monitors its implementation for holistic development',1,NULL),(544,627,9,11,'School and community jointly assess the needs of the school; identify available resources, plan and optimally use them for the development of the school; school and community reach out to other sources such as NGOs, corporate bodies, alumni, etc. to mobilize resources',1,NULL),(545,628,9,11,'School integrates local community knowledge and skills in the teaching-learning of different subjects and classes in a planned and organised manner; uses community/ village as a learning environment for learner to develop specific vocational skills',1,NULL),(546,629,9,11,'School and community reach out to other sources such as NGOs, corporate bodies, alumni, etc. to mobilise resources; school organizes/ undertakes activities for the benefit of the community like cleanliness drive, literacy campaigns, awareness against gender and social discriminations, etc.; actively initiates online platforms for sharing of good practices relating to community participation in schools',1,NULL),(547,630,9,8,'Level-1',1,NULL),(548,631,9,8,'Level-2',1,NULL),(549,632,9,8,'Level-3',1,NULL),(550,633,9,10,'a. boys',1,NULL),(551,634,9,10,'b. girls',1,NULL),(552,635,9,9,'',1,NULL),(558,642,9,2,'',1,NULL),(561,645,9,3,'',1,NULL),(564,648,9,10,'Yes',1,NULL),(565,649,9,10,'No',1,NULL),(573,655,9,10,'Name of programme',1,NULL),(574,656,9,10,'Duration',1,NULL),(575,657,9,10,'Names of teachers who attended',1,NULL),(576,658,9,10,'Duties/ Responsibilities',1,NULL),(577,659,9,10,'Names of teachers assigned',1,NULL),(578,660,9,10,'(please mention)',1,NULL),(579,661,9,10,'(specify)',1,NULL),(583,663,9,4,'Mid Day Meal, Kitchen and Utensils (where cooking is done within school premises)',1,NULL),(586,666,9,8,'Rarely',1,NULL),(587,667,9,8,'Sometimes',1,NULL),(588,668,9,8,'Mostly',1,NULL),(589,669,9,8,'Always',1,NULL),(590,670,9,7,'Shaala Siddhi_Test',1,NULL),(591,671,9,8,'',1,NULL),(592,111,18,4,'शालेय परिसर',1,NULL),(593,112,18,4,'क्रीडा उपकरणे व साहित्यासह मैदान',1,NULL),(594,1,18,1,'शाळेचे सामर्थ्ये स्रोत: उपलब्धता, पर्याप्तता व उपयुक्तता',1,NULL),(597,113,18,4,'वर्गखोल्या व इतर खोल्या ',1,NULL),(598,114,18,4,'वीज व विद्युत उपकरणे',1,NULL),(599,115,18,4,'ग्रंथालय',1,NULL),(600,116,18,4,'प्रयोगशाळा',1,NULL),(601,117,18,4,'संगणक (जेथे तरतूद असेल तेथे)',1,NULL),(602,118,18,4,'उतरता रस्ता',1,NULL),(603,119,18,4,'मध्यान भोजन, स्वयंपाक खोली व भांडी',1,NULL),(604,120,18,4,'पेय जल',1,NULL),(605,121,18,4,'हाथ धुण्याची सुविधा',1,NULL),(606,122,18,4,'स्वच्छतागृह',1,NULL),(607,2,18,1,'अध्ययन-अध्यापन व मूल्यमापन',1,NULL),(608,123,18,4,'शिक्षकांना अध्ययनार्थीची जाण',1,NULL),(609,124,18,4,'शिक्षकांचे विषय व अध्यापनशास्त्रीय ज्ञान',1,NULL),(610,125,18,4,'अध्यापनाचे नियोजन',1,NULL),(611,126,18,4,'अध्ययनपूरक वातावरण निर्मित',1,NULL),(612,127,18,4,'अध्ययन-अध्यापन प्रक्रिया',1,NULL),(613,128,18,4,'वर्ग व्यवस्थापन',1,NULL),(614,129,18,4,'अध्ययनार्थीचे मूल्यमापन',1,NULL),(615,130,18,4,'अध्ययन-अध्यापन साहित्याचा वापर',1,NULL),(616,131,18,4,'शिक्षकांचे स्वत:च्या अध्यापन-अध्ययन कृतींवरील चिंतन',1,NULL),(617,3,18,1,'अध्ययनार्थींची प्रगती, संपादणूक व विकास',1,NULL),(618,132,18,4,'अध्ययनार्थींची उपस्थिती',1,NULL),(619,133,18,4,'अध्ययनार्थींचा सहभाग व व्यग्रता',1,NULL),(620,134,18,4,'अध्ययनार्थींची प्रगती',1,NULL),(621,135,18,4,'अध्ययनार्थींचा वैयक्तिक व सामाजिक विकास',1,NULL),(622,136,18,4,'अध्ययनार्थींची संपादणूक',1,NULL),(623,4,18,1,'शिक्षक कामगिरी व व्यावसायिक विकासाचे व्यवस्थापन',1,NULL),(624,137,18,4,'नवीन शिक्षकांचे उद्बोधन',1,NULL),(625,138,18,4,'शिक्षकांची उपस्थिती',1,NULL),(626,139,18,4,'जबाबदारी निश्चिती व कामगिरी लक्ष्यांची निश्चिती',1,NULL),(627,140,18,4,'अभ्यासक्रम अपेक्षांप्रति शिक्षकांची तयारी',1,NULL),(628,141,18,4,'शिक्षक कामगिरीचे पर्यवेक्षण',1,NULL),(629,142,18,4,'शिक्षकांचा व्यावसायिक विकास',1,NULL),(630,5,18,1,'शाळा नेतृत्व व व्यस्थापन',1,NULL),(631,6,18,1,'समावेशन, आरोग्य व सुरक्षा',1,NULL),(632,7,18,1,'उत्पादक समाज-सहभाग',1,NULL),(633,143,18,4,'दूरदृष्टीचे विकसन व दिशा निश्चति',1,NULL),(634,144,18,4,'बदल व सुधारणेचे नेतृत्व',1,NULL),(635,145,18,4,'अध्ययन-अध्यापनाचे नेतृत्व',1,NULL),(636,146,18,4,'शाळा व्यस्थापनाचे नेतृत्व',1,NULL),(637,147,18,4,'समावेशन संस्कृती',1,NULL),(638,148,18,4,'विशेष गरजा असणाया समावेशन',1,NULL),(639,149,18,4,'शारिरीरिक सुरक्षता',1,NULL),(640,150,18,4,'मानसिक सुरक्षता',1,NULL),(641,151,18,4,'आरोग्य व स्वछता',1,NULL),(642,152,18,4,'शाळा व्यस्थापन / शाळा विकास व्यस्थापन समितीचे संघटन व व्यस्थापन',1,NULL),(643,153,18,4,'शाळा सुधारणेत भूमिका',1,NULL),(644,154,18,4,'शाळा-समाज अनुबंध',1,NULL),(645,155,18,4,'अध्ययन स्रोत म्हणून समाज',1,NULL),(646,156,18,4,'समाज सक्षमीकरण',1,NULL);
/*!40000 ALTER TABLE `h_lang_translation_marathi` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `h_network_report`
--

DROP TABLE IF EXISTS `h_network_report`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `h_network_report` (
  `network_report_id` int(11) NOT NULL AUTO_INCREMENT,
  `report_id` int(11) NOT NULL,
  `report_name` varchar(255) NOT NULL,
  `filter_id` int(11) NOT NULL,
  `review_experience` text NOT NULL,
  `include_self_review` tinyint(1) NOT NULL,
  `num_days_process` tinyint(1) NOT NULL,
  `is_validated` tinyint(1) NOT NULL,
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `province_id` varchar(45) DEFAULT NULL,
  `network` varchar(45) DEFAULT NULL,
  `round` varchar(45) DEFAULT NULL,
  `state` varchar(45) DEFAULT NULL,
  `zone` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`network_report_id`),
  KEY `fkk_d_filter_id_idx` (`filter_id`),
  KEY `fkk_d_report_id_idx` (`report_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `h_network_report`
--

LOCK TABLES `h_network_report` WRITE;
/*!40000 ALTER TABLE `h_network_report` DISABLE KEYS */;
INSERT INTO `h_network_report` VALUES (1,1,'pratibha',0,'',0,0,0,'2018-10-18 11:22:39','13,14,15,16,17,18,19,20,21','28','1','6','6');
/*!40000 ALTER TABLE `h_network_report` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `h_network_report_student`
--

DROP TABLE IF EXISTS `h_network_report_student`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `h_network_report_student` (
  `h_network_report_student_id` int(11) NOT NULL AUTO_INCREMENT,
  `network_report_id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `province_id` int(11) NOT NULL,
  `network_id` int(11) NOT NULL,
  `round_id` int(11) NOT NULL,
  PRIMARY KEY (`h_network_report_student_id`),
  KEY `network_report_id` (`network_report_id`),
  KEY `round_id` (`round_id`),
  CONSTRAINT `h_network_report_student_ibfk_1` FOREIGN KEY (`network_report_id`) REFERENCES `h_network_report` (`network_report_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `h_network_report_student_ibfk_3` FOREIGN KEY (`round_id`) REFERENCES `d_aqs_rounds` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `h_network_report_student`
--

LOCK TABLES `h_network_report_student` WRITE;
/*!40000 ALTER TABLE `h_network_report_student` DISABLE KEYS */;
/*!40000 ALTER TABLE `h_network_report_student` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `h_network_report_student_client`
--

DROP TABLE IF EXISTS `h_network_report_student_client`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `h_network_report_student_client` (
  `report_client_id` int(11) NOT NULL AUTO_INCREMENT,
  `h_network_report_student_id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  PRIMARY KEY (`report_client_id`),
  KEY `h_network_report_student_id` (`h_network_report_student_id`),
  KEY `client_id` (`client_id`),
  CONSTRAINT `h_network_report_student_client_ibfk_1` FOREIGN KEY (`h_network_report_student_id`) REFERENCES `h_network_report_student` (`h_network_report_student_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `h_network_report_student_client_ibfk_2` FOREIGN KEY (`client_id`) REFERENCES `d_client` (`client_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `h_network_report_student_client`
--

LOCK TABLES `h_network_report_student_client` WRITE;
/*!40000 ALTER TABLE `h_network_report_student_client` DISABLE KEYS */;
/*!40000 ALTER TABLE `h_network_report_student_client` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `h_network_report_student_province`
--

DROP TABLE IF EXISTS `h_network_report_student_province`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `h_network_report_student_province` (
  `report_province_id` int(11) NOT NULL AUTO_INCREMENT,
  `h_network_report_student_id` int(11) NOT NULL,
  `province_id` int(11) NOT NULL,
  PRIMARY KEY (`report_province_id`),
  KEY `province_id` (`province_id`),
  KEY `h_network_report_student_id` (`h_network_report_student_id`),
  CONSTRAINT `h_network_report_student_province_ibfk_1` FOREIGN KEY (`province_id`) REFERENCES `d_province` (`province_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `h_network_report_student_province_ibfk_2` FOREIGN KEY (`h_network_report_student_id`) REFERENCES `h_network_report_student` (`h_network_report_student_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `h_network_report_student_province`
--

LOCK TABLES `h_network_report_student_province` WRITE;
/*!40000 ALTER TABLE `h_network_report_student_province` DISABLE KEYS */;
/*!40000 ALTER TABLE `h_network_report_student_province` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `h_network_state`
--

DROP TABLE IF EXISTS `h_network_state`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `h_network_state` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `network_id` int(11) NOT NULL,
  `state_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `h_network_state`
--

LOCK TABLES `h_network_state` WRITE;
/*!40000 ALTER TABLE `h_network_state` DISABLE KEYS */;
/*!40000 ALTER TABLE `h_network_state` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `h_network_zone_state`
--

DROP TABLE IF EXISTS `h_network_zone_state`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `h_network_zone_state` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `network_id` int(11) NOT NULL,
  `zone_id` int(11) NOT NULL,
  `state_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_h_network_zone_state_1_idx` (`network_id`),
  KEY `fk_h_network_zone_state_2_idx` (`zone_id`),
  KEY `fk_h_network_zone_state_3_idx` (`state_id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `h_network_zone_state`
--

LOCK TABLES `h_network_zone_state` WRITE;
/*!40000 ALTER TABLE `h_network_zone_state` DISABLE KEYS */;
INSERT INTO `h_network_zone_state` VALUES (1,73,6,6),(2,74,57,47),(3,75,58,6),(4,76,59,6),(5,77,60,6),(6,78,61,6),(7,79,62,48),(8,80,63,49),(9,81,66,50),(10,51,67,6),(11,82,68,6),(12,83,78,55),(13,84,85,6),(14,85,86,56),(15,86,87,57),(16,87,89,59),(17,88,90,60),(18,89,97,6),(19,90,100,6),(20,91,101,63),(21,92,102,64),(22,93,104,65),(23,94,106,48),(24,51,107,6),(25,95,110,67),(26,96,111,67),(27,97,112,48),(28,98,116,71),(29,99,117,72),(30,100,118,6),(31,33,8,6),(32,1,1,1);
/*!40000 ALTER TABLE `h_network_zone_state` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `h_nonteaching_staff_school_level`
--

DROP TABLE IF EXISTS `h_nonteaching_staff_school_level`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `h_nonteaching_staff_school_level` (
  `staff_level_id` int(11) NOT NULL AUTO_INCREMENT,
  `staff_id` int(11) NOT NULL,
  `school_level_id` int(11) NOT NULL,
  `post_review_id` int(11) NOT NULL,
  PRIMARY KEY (`staff_level_id`),
  KEY `fk_post_review_id_idx` (`post_review_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `h_nonteaching_staff_school_level`
--

LOCK TABLES `h_nonteaching_staff_school_level` WRITE;
/*!40000 ALTER TABLE `h_nonteaching_staff_school_level` DISABLE KEYS */;
/*!40000 ALTER TABLE `h_nonteaching_staff_school_level` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `h_notification_type`
--

DROP TABLE IF EXISTS `h_notification_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `h_notification_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `notification_id` int(11) NOT NULL,
  `notification_type_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `notification_type_id` (`notification_type_id`),
  KEY `notification_id` (`notification_id`),
  CONSTRAINT `h_notification_type_ibfk_1` FOREIGN KEY (`notification_type_id`) REFERENCES `d_notification_type` (`id`),
  CONSTRAINT `h_notification_type_ibfk_2` FOREIGN KEY (`notification_id`) REFERENCES `d_notifications` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `h_notification_type`
--

LOCK TABLES `h_notification_type` WRITE;
/*!40000 ALTER TABLE `h_notification_type` DISABLE KEYS */;
/*!40000 ALTER TABLE `h_notification_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `h_province_network`
--

DROP TABLE IF EXISTS `h_province_network`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `h_province_network` (
  `province_network_id` int(11) NOT NULL AUTO_INCREMENT,
  `network_id` int(11) DEFAULT NULL,
  `province_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`province_network_id`),
  KEY `fk_d_network_d_province_idx` (`network_id`),
  KEY `fk_d_province_h_province_network_idx` (`province_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `h_province_network`
--

LOCK TABLES `h_province_network` WRITE;
/*!40000 ALTER TABLE `h_province_network` DISABLE KEYS */;
/*!40000 ALTER TABLE `h_province_network` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `h_province_report_clients`
--

DROP TABLE IF EXISTS `h_province_report_clients`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `h_province_report_clients` (
  `province_report_client_id` int(11) NOT NULL AUTO_INCREMENT,
  `province_report_id` int(11) NOT NULL DEFAULT '0',
  `client_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`province_report_client_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `h_province_report_clients`
--

LOCK TABLES `h_province_report_clients` WRITE;
/*!40000 ALTER TABLE `h_province_report_clients` DISABLE KEYS */;
/*!40000 ALTER TABLE `h_province_report_clients` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `h_rating_level_scheme`
--

DROP TABLE IF EXISTS `h_rating_level_scheme`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `h_rating_level_scheme` (
  `rating_scheme_id` int(4) NOT NULL,
  `rating_level_id` int(4) NOT NULL,
  `rating_id` int(4) NOT NULL,
  `rating_level_order` int(11) DEFAULT NULL,
  `rating_level_scheme_id_id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`rating_level_scheme_id_id`),
  KEY `fkx_rating_level_id_idx` (`rating_level_id`),
  KEY `fkx_rating_id_idx` (`rating_id`),
  KEY `idx_rating_scheme_id_idx` (`rating_scheme_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `h_rating_level_scheme`
--

LOCK TABLES `h_rating_level_scheme` WRITE;
/*!40000 ALTER TABLE `h_rating_level_scheme` DISABLE KEYS */;
INSERT INTO `h_rating_level_scheme` VALUES (1,1,1,1,1),(1,1,2,2,2),(1,1,3,3,3),(1,4,1,1,4),(1,4,2,2,5),(1,4,3,3,6),(1,3,3,1,7),(1,2,3,1,8),(1,3,3,3,9),(1,3,2,2,10);
/*!40000 ALTER TABLE `h_rating_level_scheme` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `h_reminder_final_mail_settings`
--

DROP TABLE IF EXISTS `h_reminder_final_mail_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `h_reminder_final_mail_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `assessment_id` int(11) NOT NULL,
  `final_mail` int(11) NOT NULL,
  `num_days` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `mail_status` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `h_reminder_final_mail_settings`
--

LOCK TABLES `h_reminder_final_mail_settings` WRITE;
/*!40000 ALTER TABLE `h_reminder_final_mail_settings` DISABLE KEYS */;
/*!40000 ALTER TABLE `h_reminder_final_mail_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `h_review_action2_activity`
--

DROP TABLE IF EXISTS `h_review_action2_activity`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `h_review_action2_activity` (
  `h_review_action2_activity_id` int(11) NOT NULL AUTO_INCREMENT,
  `h_assessor_action1_id` int(11) NOT NULL,
  `activity_stackholder` int(11) NOT NULL,
  `activity` int(11) NOT NULL,
  `activity_details` text NOT NULL,
  `activity_status` int(11) NOT NULL DEFAULT '0',
  `activity_date` date NOT NULL,
  `activity_actual_date` date NOT NULL,
  `activity_comments` text NOT NULL,
  `createDate` datetime NOT NULL,
  `createdBy` int(11) NOT NULL,
  `modifyDate` datetime NOT NULL,
  `modifiedBy` int(11) NOT NULL,
  PRIMARY KEY (`h_review_action2_activity_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `h_review_action2_activity`
--

LOCK TABLES `h_review_action2_activity` WRITE;
/*!40000 ALTER TABLE `h_review_action2_activity` DISABLE KEYS */;
INSERT INTO `h_review_action2_activity` VALUES (1,95,0,5,'dsfsdf',0,'2019-02-20','0000-00-00','dsfsdf','2019-02-20 12:37:40',1058,'2019-02-20 12:56:30',1785);
/*!40000 ALTER TABLE `h_review_action2_activity` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `h_review_action2_activity_postponed`
--

DROP TABLE IF EXISTS `h_review_action2_activity_postponed`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `h_review_action2_activity_postponed` (
  `h_review_action2_activity_postponed_id` int(11) NOT NULL AUTO_INCREMENT,
  `h_review_action2_activity_id` int(11) NOT NULL,
  `h_assessor_action1_id` int(11) NOT NULL,
  `activity` int(11) NOT NULL,
  `activity_details` text NOT NULL,
  `activity_status` int(11) NOT NULL DEFAULT '0',
  `activity_date` date NOT NULL,
  `activity_actual_date` date NOT NULL,
  `activity_comments` text NOT NULL,
  `createDate` datetime NOT NULL,
  `createdBy` int(11) NOT NULL,
  `modifyDate` datetime NOT NULL,
  `modifiedBy` int(11) NOT NULL,
  PRIMARY KEY (`h_review_action2_activity_postponed_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `h_review_action2_activity_postponed`
--

LOCK TABLES `h_review_action2_activity_postponed` WRITE;
/*!40000 ALTER TABLE `h_review_action2_activity_postponed` DISABLE KEYS */;
/*!40000 ALTER TABLE `h_review_action2_activity_postponed` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `h_review_action2_activity_stackholder`
--

DROP TABLE IF EXISTS `h_review_action2_activity_stackholder`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `h_review_action2_activity_stackholder` (
  `h_review_action2_activity_stackholder_id` int(11) NOT NULL AUTO_INCREMENT,
  `h_review_action2_activity_id` int(11) NOT NULL,
  `activity_stackholder` int(11) NOT NULL,
  PRIMARY KEY (`h_review_action2_activity_stackholder_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `h_review_action2_activity_stackholder`
--

LOCK TABLES `h_review_action2_activity_stackholder` WRITE;
/*!40000 ALTER TABLE `h_review_action2_activity_stackholder` DISABLE KEYS */;
INSERT INTO `h_review_action2_activity_stackholder` VALUES (2,1,1);
/*!40000 ALTER TABLE `h_review_action2_activity_stackholder` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `h_review_action2_activity_stackholder_postponed`
--

DROP TABLE IF EXISTS `h_review_action2_activity_stackholder_postponed`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `h_review_action2_activity_stackholder_postponed` (
  `h_review_action2_activity_stackholder_postponed_id` int(11) NOT NULL AUTO_INCREMENT,
  `h_review_action2_activity_postponed_id` int(11) NOT NULL,
  `activity_stackholder` int(11) NOT NULL,
  PRIMARY KEY (`h_review_action2_activity_stackholder_postponed_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `h_review_action2_activity_stackholder_postponed`
--

LOCK TABLES `h_review_action2_activity_stackholder_postponed` WRITE;
/*!40000 ALTER TABLE `h_review_action2_activity_stackholder_postponed` DISABLE KEYS */;
/*!40000 ALTER TABLE `h_review_action2_activity_stackholder_postponed` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `h_review_action2_team`
--

DROP TABLE IF EXISTS `h_review_action2_team`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `h_review_action2_team` (
  `h_review_action2_team_id` int(11) NOT NULL,
  `h_assessor_action1_id` int(11) NOT NULL,
  `team_designation` int(11) NOT NULL,
  `team_member_name` varchar(255) NOT NULL,
  `createDate` datetime NOT NULL,
  `createdBy` int(11) NOT NULL,
  `modifyDate` datetime NOT NULL,
  `modifiedBy` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `h_review_action2_team`
--

LOCK TABLES `h_review_action2_team` WRITE;
/*!40000 ALTER TABLE `h_review_action2_team` DISABLE KEYS */;
/*!40000 ALTER TABLE `h_review_action2_team` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `h_review_notification_log`
--

DROP TABLE IF EXISTS `h_review_notification_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `h_review_notification_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `notification_id` int(11) NOT NULL,
  `assessment_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `mail_content` text NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `h_review_notification_log`
--

LOCK TABLES `h_review_notification_log` WRITE;
/*!40000 ALTER TABLE `h_review_notification_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `h_review_notification_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `h_review_notification_mail_users`
--

DROP TABLE IF EXISTS `h_review_notification_mail_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `h_review_notification_mail_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `notification_id` int(11) NOT NULL,
  `subject` text NOT NULL,
  `sender` varchar(255) NOT NULL,
  `sender_name` varchar(255) NOT NULL,
  `cc` varchar(255) NOT NULL,
  `status` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_notification_id` (`notification_id`),
  CONSTRAINT `fk_notification_id` FOREIGN KEY (`notification_id`) REFERENCES `d_review_notification_template` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `h_review_notification_mail_users`
--

LOCK TABLES `h_review_notification_mail_users` WRITE;
/*!40000 ALTER TABLE `h_review_notification_mail_users` DISABLE KEYS */;
/*!40000 ALTER TABLE `h_review_notification_mail_users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `h_school_feedback_question`
--

DROP TABLE IF EXISTS `h_school_feedback_question`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `h_school_feedback_question` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sub_role_id` int(11) NOT NULL,
  `q_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `h_school_feedback_question`
--

LOCK TABLES `h_school_feedback_question` WRITE;
/*!40000 ALTER TABLE `h_school_feedback_question` DISABLE KEYS */;
INSERT INTO `h_school_feedback_question` VALUES (1,2,16),(2,2,17),(3,3,12),(4,3,13),(5,4,14),(6,4,15),(7,1,10),(8,1,11),(9,2,18),(10,3,18),(11,1,18),(12,4,18),(13,1,19),(14,2,19),(15,3,19),(16,4,19);
/*!40000 ALTER TABLE `h_school_feedback_question` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `h_school_profile_status`
--

DROP TABLE IF EXISTS `h_school_profile_status`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `h_school_profile_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `assessment_id` int(11) NOT NULL,
  `perComplete` varchar(255) NOT NULL,
  `submit_date` datetime NOT NULL,
  `status` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_h_school_profile_status_assessment_id` (`assessment_id`),
  CONSTRAINT `fk_h_school_profile_status_1` FOREIGN KEY (`assessment_id`) REFERENCES `d_assessment` (`assessment_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `h_school_profile_status`
--

LOCK TABLES `h_school_profile_status` WRITE;
/*!40000 ALTER TABLE `h_school_profile_status` DISABLE KEYS */;
/*!40000 ALTER TABLE `h_school_profile_status` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `h_school_review_mail_role`
--

DROP TABLE IF EXISTS `h_school_review_mail_role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `h_school_review_mail_role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mail_content_id` int(11) NOT NULL,
  `role` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `h_school_review_mail_role`
--

LOCK TABLES `h_school_review_mail_role` WRITE;
/*!40000 ALTER TABLE `h_school_review_mail_role` DISABLE KEYS */;
/*!40000 ALTER TABLE `h_school_review_mail_role` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `h_school_review_mail_sub_role`
--

DROP TABLE IF EXISTS `h_school_review_mail_sub_role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `h_school_review_mail_sub_role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mail_content_id` int(11) NOT NULL,
  `sub_role` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `h_school_review_mail_sub_role`
--

LOCK TABLES `h_school_review_mail_sub_role` WRITE;
/*!40000 ALTER TABLE `h_school_review_mail_sub_role` DISABLE KEYS */;
/*!40000 ALTER TABLE `h_school_review_mail_sub_role` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `h_score_file`
--

DROP TABLE IF EXISTS `h_score_file`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `h_score_file` (
  `score_file_id` int(11) NOT NULL AUTO_INCREMENT,
  `score_id` int(11) DEFAULT NULL,
  `file_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`score_file_id`),
  KEY `score_id` (`score_id`),
  KEY `file_id` (`file_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `h_score_file`
--

LOCK TABLES `h_score_file` WRITE;
/*!40000 ALTER TABLE `h_score_file` DISABLE KEYS */;
/*!40000 ALTER TABLE `h_score_file` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `h_studbody_school_level`
--

DROP TABLE IF EXISTS `h_studbody_school_level`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `h_studbody_school_level` (
  `sbody_level_id` int(11) NOT NULL AUTO_INCREMENT,
  `student_body_id` int(11) DEFAULT NULL,
  `school_level_id` int(11) DEFAULT NULL,
  `post_review_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`sbody_level_id`),
  KEY `fk_school_level_id_idx` (`school_level_id`),
  KEY `fk_student_body_id_idx` (`sbody_level_id`),
  KEY `fk_post_review_id_idx` (`post_review_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `h_studbody_school_level`
--

LOCK TABLES `h_studbody_school_level` WRITE;
/*!40000 ALTER TABLE `h_studbody_school_level` DISABLE KEYS */;
/*!40000 ALTER TABLE `h_studbody_school_level` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `h_tap_user_assessment`
--

DROP TABLE IF EXISTS `h_tap_user_assessment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `h_tap_user_assessment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `tap_program_status` int(2) NOT NULL COMMENT '1=user is in tap program, 0=user is not in tap program',
  `creation_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=533 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `h_tap_user_assessment`
--

LOCK TABLES `h_tap_user_assessment` WRITE;
/*!40000 ALTER TABLE `h_tap_user_assessment` DISABLE KEYS */;
INSERT INTO `h_tap_user_assessment` VALUES (1,10,1,'2018-06-26 12:12:43'),(2,11,1,'2018-06-26 12:13:20'),(3,16,1,'2018-06-30 05:24:07'),(4,17,1,'2018-06-30 05:24:46'),(5,18,1,'2018-06-30 05:25:30'),(6,19,1,'2018-06-30 05:25:58'),(7,20,1,'2018-06-30 05:27:22'),(8,21,1,'2018-06-30 05:27:55'),(9,23,1,'2018-06-30 09:52:57'),(10,30,1,'2018-07-01 12:16:46'),(11,31,1,'2018-07-01 12:17:27'),(12,32,1,'2018-07-01 12:17:59'),(13,41,1,'2018-07-04 15:06:30'),(14,42,1,'2018-07-04 15:06:58'),(15,43,1,'2018-07-04 15:07:28'),(16,45,1,'2018-07-04 16:23:39'),(17,46,1,'2018-07-04 16:24:35'),(18,47,1,'2018-07-04 17:06:26'),(19,48,1,'2018-07-04 17:07:26'),(20,49,1,'2018-07-04 17:08:28'),(21,50,1,'2018-07-04 17:15:29'),(22,51,1,'2018-07-04 17:16:15'),(23,52,1,'2018-07-04 17:40:56'),(24,53,1,'2018-07-04 17:41:45'),(25,55,1,'2018-07-05 03:38:21'),(26,56,1,'2018-07-05 04:19:00'),(27,310,1,'2018-07-25 08:59:28'),(28,312,1,'2018-07-25 09:00:43'),(29,315,1,'2018-07-25 09:05:26'),(30,323,1,'2018-07-25 09:28:42'),(31,325,1,'2018-07-25 09:30:32'),(32,326,1,'2018-07-25 09:32:15'),(33,327,1,'2018-07-25 09:33:31'),(34,328,1,'2018-07-25 09:34:59'),(35,329,1,'2018-07-25 09:36:15'),(36,330,1,'2018-07-25 09:37:25'),(37,332,1,'2018-07-25 09:39:16'),(38,333,1,'2018-07-25 09:40:35'),(39,334,1,'2018-07-25 09:41:59'),(40,335,1,'2018-07-25 09:44:03'),(41,336,1,'2018-07-25 09:46:05'),(42,337,1,'2018-07-25 09:47:32'),(43,338,1,'2018-07-25 09:49:14'),(44,339,1,'2018-07-25 09:50:38'),(45,340,1,'2018-07-25 10:09:56'),(46,341,1,'2018-07-25 10:11:10'),(47,342,1,'2018-07-25 10:12:25'),(48,343,1,'2018-07-25 10:14:28'),(49,344,1,'2018-07-25 10:16:06'),(50,345,1,'2018-07-25 10:17:26'),(51,346,1,'2018-07-25 10:18:56'),(52,347,1,'2018-07-25 10:20:13'),(53,348,1,'2018-07-25 10:22:14'),(54,349,1,'2018-07-25 10:48:41'),(55,350,1,'2018-07-25 10:50:20'),(56,351,1,'2018-07-25 10:51:56'),(57,352,1,'2018-07-25 10:53:43'),(58,353,1,'2018-07-25 10:55:09'),(59,354,1,'2018-07-25 10:56:34'),(60,355,1,'2018-07-25 10:58:15'),(61,356,1,'2018-07-25 10:59:39'),(62,357,1,'2018-07-25 11:01:09'),(63,358,1,'2018-07-25 12:01:42'),(64,359,1,'2018-07-25 12:02:56'),(65,360,1,'2018-07-25 12:04:05'),(66,361,1,'2018-07-25 12:05:17'),(67,362,1,'2018-07-25 12:06:35'),(68,363,1,'2018-07-25 12:07:47'),(69,365,1,'2018-07-25 13:33:39'),(70,366,1,'2018-07-25 13:51:52'),(71,367,1,'2018-07-25 13:54:23'),(72,368,1,'2018-07-25 13:56:25'),(73,369,1,'2018-07-25 13:57:16'),(74,370,1,'2018-07-26 04:41:51'),(75,371,1,'2018-07-26 04:47:27'),(76,372,1,'2018-07-26 04:50:43'),(77,373,1,'2018-07-26 04:52:06'),(78,374,1,'2018-07-26 04:53:56'),(79,375,1,'2018-07-26 04:55:09'),(80,376,1,'2018-07-26 04:56:37'),(81,377,1,'2018-07-26 04:58:04'),(82,378,1,'2018-07-26 05:00:17'),(83,379,1,'2018-07-26 05:01:38'),(84,380,1,'2018-07-26 05:06:42'),(85,381,1,'2018-07-26 05:09:19'),(86,382,1,'2018-07-26 05:16:47'),(87,383,1,'2018-07-26 05:32:11'),(88,384,1,'2018-07-26 05:37:57'),(89,385,1,'2018-07-26 05:39:06'),(90,386,1,'2018-07-26 05:42:27'),(91,387,1,'2018-07-26 05:43:57'),(92,388,1,'2018-07-26 05:46:21'),(93,389,1,'2018-07-26 05:47:43'),(94,390,1,'2018-07-26 05:48:22'),(95,391,1,'2018-07-26 05:48:57'),(96,392,1,'2018-07-26 05:50:12'),(97,393,1,'2018-07-26 05:51:42'),(98,394,1,'2018-07-26 06:09:23'),(99,395,1,'2018-07-26 06:11:02'),(100,396,1,'2018-07-26 06:12:24'),(101,397,1,'2018-07-26 06:12:47'),(102,398,1,'2018-07-26 06:14:01'),(103,399,1,'2018-07-26 06:15:01'),(104,400,1,'2018-07-26 06:15:10'),(105,401,1,'2018-07-26 06:16:11'),(106,403,1,'2018-07-26 06:17:33'),(107,404,1,'2018-07-26 06:18:55'),(108,405,1,'2018-07-26 06:20:44'),(109,406,1,'2018-07-26 06:21:09'),(110,407,1,'2018-07-26 06:22:30'),(111,408,1,'2018-07-26 06:22:30'),(112,409,1,'2018-07-26 06:23:37'),(113,410,1,'2018-07-26 06:24:01'),(114,411,1,'2018-07-26 06:24:49'),(115,412,1,'2018-07-26 06:26:06'),(116,413,1,'2018-07-26 06:27:19'),(117,414,1,'2018-07-26 06:28:26'),(118,415,1,'2018-07-26 06:29:56'),(119,416,1,'2018-07-26 06:30:18'),(120,417,1,'2018-07-26 06:31:24'),(121,418,1,'2018-07-26 06:31:46'),(122,419,1,'2018-07-26 06:32:31'),(123,420,1,'2018-07-26 06:34:38'),(124,421,1,'2018-07-26 06:35:46'),(125,422,1,'2018-07-26 06:36:18'),(126,423,1,'2018-07-26 06:38:11'),(127,424,1,'2018-07-26 06:39:27'),(128,425,1,'2018-07-26 06:40:39'),(129,426,1,'2018-07-26 06:40:55'),(130,427,1,'2018-07-26 06:42:05'),(131,428,1,'2018-07-26 06:43:12'),(132,429,1,'2018-07-26 06:46:46'),(133,430,1,'2018-07-26 06:56:33'),(134,431,1,'2018-07-26 06:57:37'),(135,432,1,'2018-07-26 06:58:15'),(136,433,1,'2018-07-26 06:58:58'),(137,434,1,'2018-07-26 06:59:28'),(138,435,1,'2018-07-26 07:00:30'),(139,436,1,'2018-07-26 07:00:53'),(140,437,1,'2018-07-26 07:01:38'),(141,438,1,'2018-07-26 07:02:05'),(142,439,1,'2018-07-26 07:03:13'),(143,440,1,'2018-07-26 07:04:23'),(144,441,1,'2018-07-26 07:05:37'),(145,442,1,'2018-07-26 07:06:53'),(146,443,1,'2018-07-26 07:08:36'),(147,444,1,'2018-07-26 07:09:48'),(148,445,1,'2018-07-26 07:10:25'),(149,446,1,'2018-07-26 07:10:52'),(150,447,1,'2018-07-26 07:11:40'),(151,448,1,'2018-07-26 07:12:12'),(152,449,1,'2018-07-26 07:13:07'),(153,450,1,'2018-07-26 07:13:22'),(154,451,1,'2018-07-26 07:14:15'),(155,452,1,'2018-07-26 07:14:51'),(156,453,1,'2018-07-26 07:19:50'),(157,454,1,'2018-07-26 07:24:02'),(158,455,1,'2018-07-26 07:24:03'),(159,456,1,'2018-07-26 07:25:29'),(160,457,1,'2018-07-26 07:29:21'),(161,458,1,'2018-07-26 07:31:03'),(162,459,1,'2018-07-26 07:32:12'),(163,460,1,'2018-07-26 08:46:56'),(164,461,1,'2018-07-26 08:48:34'),(165,462,1,'2018-07-26 08:50:33'),(166,463,1,'2018-07-26 08:53:59'),(167,464,1,'2018-07-26 08:59:14'),(168,465,1,'2018-07-26 08:59:17'),(169,466,1,'2018-07-26 09:00:29'),(170,467,1,'2018-07-26 09:01:25'),(171,468,1,'2018-07-26 09:02:19'),(172,469,1,'2018-07-26 09:03:29'),(173,470,1,'2018-07-26 09:03:46'),(174,471,1,'2018-07-26 09:04:50'),(175,472,1,'2018-07-26 09:05:57'),(176,473,1,'2018-07-26 09:06:43'),(177,474,1,'2018-07-26 09:08:52'),(178,475,1,'2018-07-26 09:09:27'),(179,476,1,'2018-07-26 09:10:00'),(180,477,1,'2018-07-26 09:10:55'),(181,478,1,'2018-07-26 09:11:33'),(182,479,1,'2018-07-26 09:12:19'),(183,480,1,'2018-07-26 09:12:40'),(184,481,1,'2018-07-26 09:14:01'),(185,482,1,'2018-07-26 09:14:19'),(186,483,1,'2018-07-26 09:15:38'),(187,484,1,'2018-07-26 09:16:06'),(188,485,1,'2018-07-26 09:16:53'),(189,486,1,'2018-07-26 09:18:20'),(190,487,1,'2018-07-26 09:19:39'),(191,488,1,'2018-07-26 09:20:40'),(192,489,1,'2018-07-26 09:21:51'),(193,490,1,'2018-07-26 09:24:28'),(194,491,1,'2018-07-26 09:27:48'),(195,492,1,'2018-07-26 09:29:20'),(196,493,1,'2018-07-26 09:29:48'),(197,494,1,'2018-07-26 09:31:05'),(198,495,1,'2018-07-26 09:32:11'),(199,496,1,'2018-07-26 09:33:00'),(200,497,1,'2018-07-26 09:34:44'),(201,498,1,'2018-07-26 09:35:12'),(202,499,1,'2018-07-26 09:58:46'),(203,500,1,'2018-07-26 09:59:17'),(204,501,1,'2018-07-26 10:00:05'),(205,502,1,'2018-07-26 10:00:40'),(206,503,1,'2018-07-26 10:01:47'),(207,504,1,'2018-07-26 10:01:59'),(208,505,1,'2018-07-26 10:03:21'),(209,506,1,'2018-07-26 10:03:52'),(210,507,1,'2018-07-26 10:04:36'),(211,508,1,'2018-07-26 10:06:04'),(212,509,1,'2018-07-26 10:07:37'),(213,510,1,'2018-07-26 10:09:01'),(214,511,1,'2018-07-26 10:10:21'),(215,512,1,'2018-07-26 10:11:42'),(216,513,1,'2018-07-26 10:13:00'),(217,514,1,'2018-07-26 10:13:49'),(218,515,1,'2018-07-26 10:15:01'),(219,516,1,'2018-07-26 10:16:23'),(220,517,1,'2018-07-26 10:28:57'),(221,518,1,'2018-07-26 10:29:06'),(222,519,1,'2018-07-26 10:30:09'),(223,520,1,'2018-07-26 10:30:16'),(224,521,1,'2018-07-26 10:32:11'),(225,522,1,'2018-07-26 10:33:07'),(226,523,1,'2018-07-26 10:33:52'),(227,524,1,'2018-07-26 10:34:44'),(228,525,1,'2018-07-26 10:35:22'),(229,526,1,'2018-07-26 10:40:00'),(230,527,1,'2018-07-26 10:42:26'),(231,528,1,'2018-07-26 10:43:39'),(232,529,1,'2018-07-26 10:44:30'),(233,530,1,'2018-07-26 10:44:53'),(234,531,1,'2018-07-26 10:45:57'),(235,532,1,'2018-07-26 10:46:14'),(236,533,1,'2018-07-26 10:47:24'),(237,534,1,'2018-07-26 10:48:37'),(238,535,1,'2018-07-26 10:50:05'),(239,536,1,'2018-07-26 10:51:26'),(240,537,1,'2018-07-26 10:51:44'),(241,538,1,'2018-07-26 10:52:38'),(242,539,1,'2018-07-26 10:53:19'),(243,540,1,'2018-07-26 10:53:42'),(244,541,1,'2018-07-26 10:54:45'),(245,542,1,'2018-07-26 10:55:07'),(246,543,1,'2018-07-26 10:56:17'),(247,544,1,'2018-07-26 10:57:28'),(248,545,1,'2018-07-26 10:57:58'),(249,546,1,'2018-07-26 10:58:23'),(250,547,1,'2018-07-26 10:59:36'),(251,548,1,'2018-07-26 11:03:03'),(252,549,1,'2018-07-26 11:04:10'),(253,550,1,'2018-07-26 11:05:55'),(254,551,1,'2018-07-26 11:07:01'),(255,552,1,'2018-07-26 11:08:06'),(256,553,1,'2018-07-26 11:10:26'),(257,554,1,'2018-07-26 11:11:33'),(258,555,1,'2018-07-26 11:12:37'),(259,556,1,'2018-07-26 11:13:52'),(260,557,1,'2018-07-26 11:23:37'),(261,558,1,'2018-07-26 11:24:45'),(262,559,1,'2018-07-26 11:25:42'),(263,560,1,'2018-07-26 11:26:29'),(264,561,1,'2018-07-26 11:28:05'),(265,562,1,'2018-07-26 11:28:58'),(266,563,1,'2018-07-26 11:29:55'),(267,564,1,'2018-07-26 11:30:23'),(268,565,1,'2018-07-26 11:31:14'),(269,566,1,'2018-07-26 11:31:40'),(270,567,1,'2018-07-26 11:32:09'),(271,568,1,'2018-07-26 11:32:56'),(272,569,1,'2018-07-26 11:33:32'),(273,570,1,'2018-07-26 11:34:34'),(274,571,1,'2018-07-26 11:35:14'),(275,572,1,'2018-07-26 11:35:56'),(276,573,1,'2018-07-26 11:36:41'),(277,574,1,'2018-07-26 11:37:08'),(278,575,1,'2018-07-26 11:37:59'),(279,576,1,'2018-07-26 11:38:19'),(280,577,1,'2018-07-26 11:39:07'),(281,578,1,'2018-07-26 11:39:31'),(282,579,1,'2018-07-26 11:40:14'),(283,580,1,'2018-07-26 11:40:29'),(284,581,1,'2018-07-26 11:41:17'),(285,582,1,'2018-07-26 11:42:55'),(286,583,1,'2018-07-26 11:44:45'),(287,584,1,'2018-07-26 11:45:18'),(288,585,1,'2018-07-26 11:45:40'),(289,586,1,'2018-07-26 11:46:32'),(290,587,1,'2018-07-26 11:46:37'),(291,588,1,'2018-07-26 11:47:35'),(292,589,1,'2018-07-26 11:47:40'),(293,590,1,'2018-07-26 11:49:20'),(294,592,1,'2018-07-30 13:15:35'),(295,593,1,'2018-07-31 09:10:06'),(296,594,1,'2018-07-31 10:05:16'),(297,596,1,'2018-08-01 17:31:42'),(298,600,1,'2018-08-15 12:29:17'),(299,603,1,'2018-08-22 07:21:30'),(300,604,1,'2018-08-22 07:24:48'),(301,605,1,'2018-08-22 07:26:39'),(302,606,1,'2018-08-22 07:28:33'),(303,607,1,'2018-08-22 07:32:41'),(304,609,1,'2018-08-22 10:34:04'),(305,610,1,'2018-08-22 10:37:12'),(306,615,1,'2018-08-22 10:47:30'),(307,617,1,'2018-08-22 10:49:05'),(308,619,1,'2018-08-22 10:50:04'),(309,621,1,'2018-08-22 10:51:00'),(310,622,1,'2018-08-22 10:52:01'),(311,624,1,'2018-08-22 10:53:07'),(312,626,1,'2018-08-22 10:54:03'),(313,627,1,'2018-08-22 10:54:56'),(314,628,1,'2018-08-22 10:55:45'),(315,629,1,'2018-08-22 10:56:35'),(316,631,1,'2018-08-22 10:58:03'),(317,633,1,'2018-08-22 10:59:06'),(318,634,1,'2018-08-22 10:59:54'),(319,636,1,'2018-08-22 11:00:40'),(320,637,1,'2018-08-22 11:01:30'),(321,639,1,'2018-08-22 11:02:21'),(322,641,1,'2018-08-22 11:03:28'),(323,643,1,'2018-08-22 11:04:25'),(324,644,1,'2018-08-22 11:05:10'),(325,645,1,'2018-08-22 11:06:00'),(326,647,1,'2018-08-22 11:06:54'),(327,648,1,'2018-08-22 11:07:41'),(328,649,1,'2018-08-22 11:08:27'),(329,651,1,'2018-08-22 11:09:10'),(330,652,1,'2018-08-22 11:09:54'),(331,654,1,'2018-08-22 11:10:35'),(332,656,1,'2018-08-22 11:11:30'),(333,657,1,'2018-08-22 11:12:20'),(334,659,1,'2018-08-22 11:12:58'),(335,661,1,'2018-08-22 11:14:48'),(336,663,1,'2018-08-22 11:15:58'),(337,665,1,'2018-08-22 11:16:39'),(338,666,1,'2018-08-22 11:17:34'),(339,668,1,'2018-08-22 11:18:22'),(340,670,1,'2018-08-22 11:19:03'),(341,671,1,'2018-08-22 11:19:48'),(342,672,1,'2018-08-22 11:20:30'),(343,673,1,'2018-08-22 11:21:11'),(344,674,1,'2018-08-22 11:21:59'),(345,676,1,'2018-08-22 11:22:40'),(346,678,1,'2018-08-22 11:23:25'),(347,679,1,'2018-08-22 11:24:13'),(348,682,1,'2018-08-22 11:26:12'),(349,683,1,'2018-08-22 11:26:55'),(350,685,1,'2018-08-22 11:27:38'),(351,687,1,'2018-08-22 11:28:20'),(352,688,1,'2018-08-22 11:29:06'),(353,690,1,'2018-08-22 11:29:47'),(354,691,1,'2018-08-22 11:30:29'),(355,692,1,'2018-08-22 11:31:15'),(356,694,1,'2018-08-22 11:31:53'),(357,696,1,'2018-08-22 11:32:42'),(358,697,1,'2018-08-22 11:33:21'),(359,699,1,'2018-08-22 11:34:00'),(360,700,1,'2018-08-22 11:34:53'),(361,702,1,'2018-08-22 11:35:31'),(362,703,1,'2018-08-22 11:36:09'),(363,705,1,'2018-08-22 11:36:47'),(364,706,1,'2018-08-22 11:37:27'),(365,708,1,'2018-08-22 11:38:09'),(366,709,1,'2018-08-22 11:38:50'),(367,711,1,'2018-08-22 11:40:02'),(368,712,1,'2018-08-22 11:40:45'),(369,739,1,'2018-08-22 14:00:27'),(370,741,1,'2018-08-22 15:07:36'),(371,743,1,'2018-08-22 15:46:05'),(372,745,1,'2018-08-23 04:39:56'),(373,747,1,'2018-08-27 06:09:04'),(374,753,1,'2018-09-03 13:41:04'),(375,754,1,'2018-09-03 13:42:24'),(376,755,1,'2018-09-03 13:43:04'),(377,756,1,'2018-09-03 13:43:48'),(378,791,1,'2018-09-04 06:03:56'),(379,792,1,'2018-09-04 06:05:54'),(380,793,1,'2018-09-04 06:07:51'),(381,794,1,'2018-09-04 06:08:50'),(382,795,1,'2018-09-04 06:10:15'),(383,796,1,'2018-09-04 06:11:04'),(384,797,1,'2018-09-04 06:12:01'),(385,798,1,'2018-09-04 06:12:43'),(386,799,1,'2018-09-04 06:13:24'),(387,800,1,'2018-09-04 06:14:13'),(388,801,1,'2018-09-04 06:14:57'),(389,802,1,'2018-09-04 06:15:35'),(390,803,1,'2018-09-04 06:16:24'),(391,804,1,'2018-09-04 06:17:10'),(392,805,1,'2018-09-04 06:17:53'),(393,806,1,'2018-09-04 06:18:37'),(394,807,1,'2018-09-04 06:19:23'),(395,808,1,'2018-09-04 06:20:15'),(396,809,1,'2018-09-04 06:20:54'),(397,810,1,'2018-09-04 06:21:38'),(398,811,1,'2018-09-04 06:22:24'),(399,812,1,'2018-09-04 06:23:08'),(400,813,1,'2018-09-04 06:23:59'),(401,814,1,'2018-09-04 06:24:45'),(402,815,1,'2018-09-04 06:25:36'),(403,816,1,'2018-09-04 06:26:18'),(404,817,1,'2018-09-04 06:27:01'),(405,818,1,'2018-09-04 06:27:45'),(406,819,1,'2018-09-04 06:28:33'),(407,820,1,'2018-09-04 06:29:24'),(408,821,1,'2018-09-04 06:30:03'),(409,822,1,'2018-09-04 06:31:00'),(410,823,1,'2018-09-04 06:31:49'),(411,824,1,'2018-09-04 06:32:31'),(412,826,1,'2018-09-04 06:36:44'),(413,1061,1,'2018-09-12 11:43:18'),(414,1064,1,'2018-09-17 14:29:47'),(415,1065,1,'2018-09-17 14:32:04'),(416,1066,1,'2018-09-17 14:34:40'),(417,1067,1,'2018-09-17 14:38:22'),(418,1068,1,'2018-09-17 14:40:16'),(419,1070,1,'2018-09-17 14:45:41'),(420,1119,1,'2018-10-07 16:39:52'),(421,1120,1,'2018-10-07 16:40:35'),(422,1121,1,'2018-10-07 16:41:01'),(423,1122,1,'2018-10-07 16:41:24'),(424,1123,1,'2018-10-07 16:41:50'),(425,1124,1,'2018-10-07 16:42:21'),(426,1125,1,'2018-10-07 16:42:48'),(427,1126,1,'2018-10-07 16:43:16'),(428,1127,1,'2018-10-07 16:43:40'),(429,1128,1,'2018-10-07 16:44:05'),(430,1129,1,'2018-10-07 16:44:29'),(431,1130,1,'2018-10-07 16:44:59'),(432,1131,1,'2018-10-07 16:45:20'),(433,1132,1,'2018-10-07 16:45:44'),(434,1133,1,'2018-10-07 16:46:07'),(435,1134,1,'2018-10-07 16:47:15'),(436,1135,1,'2018-10-07 16:47:46'),(437,1136,1,'2018-10-07 16:48:14'),(438,1137,1,'2018-10-07 16:48:38'),(439,1138,1,'2018-10-07 16:49:02'),(440,1139,1,'2018-10-07 16:49:22'),(441,1140,1,'2018-10-07 16:49:43'),(442,1141,1,'2018-10-07 16:50:03'),(443,1142,1,'2018-10-07 16:50:22'),(444,1143,1,'2018-10-07 16:50:41'),(445,1144,1,'2018-10-07 16:51:02'),(446,1145,1,'2018-10-07 16:51:22'),(447,1146,1,'2018-10-07 16:51:47'),(448,1147,1,'2018-10-07 16:52:20'),(449,1148,1,'2018-10-07 16:52:38'),(450,1149,1,'2018-10-07 16:52:58'),(451,1150,1,'2018-10-07 16:53:20'),(452,1151,1,'2018-10-08 06:25:41'),(453,1152,1,'2018-10-08 06:26:13'),(454,1153,1,'2018-10-08 06:26:37'),(455,1154,1,'2018-10-08 06:27:12'),(456,1155,1,'2018-10-08 06:27:32'),(457,1156,1,'2018-10-08 06:28:01'),(458,1158,1,'2018-10-08 06:30:46'),(459,1159,1,'2018-10-08 06:31:09'),(460,1160,1,'2018-10-08 06:31:31'),(461,1162,1,'2018-10-08 06:31:57'),(462,1163,1,'2018-10-08 06:32:22'),(463,1164,1,'2018-10-08 06:32:45'),(464,1174,1,'2018-10-08 07:34:50'),(465,1175,1,'2018-10-08 07:35:56'),(466,1176,1,'2018-10-08 07:37:18'),(467,1177,1,'2018-10-08 07:38:28'),(468,1178,1,'2018-10-08 07:39:17'),(469,1179,1,'2018-10-08 07:40:12'),(470,1180,1,'2018-10-08 07:44:59'),(471,1181,1,'2018-10-08 07:45:49'),(472,1182,1,'2018-10-08 07:46:33'),(473,1183,1,'2018-10-08 07:47:05'),(474,1184,1,'2018-10-08 07:47:54'),(475,1185,1,'2018-10-08 07:48:33'),(476,1186,1,'2018-10-08 07:49:06'),(477,1187,1,'2018-10-08 07:49:53'),(478,1188,1,'2018-10-08 07:50:25'),(479,1208,1,'2018-10-11 06:41:31'),(480,1209,1,'2018-10-11 06:42:16'),(481,1210,1,'2018-10-11 06:43:00'),(482,1211,1,'2018-10-11 06:43:37'),(483,1212,1,'2018-10-11 06:44:16'),(484,1213,1,'2018-10-11 06:45:34'),(485,1214,1,'2018-10-11 06:46:06'),(486,1215,1,'2018-10-11 06:47:03'),(487,1216,1,'2018-10-11 06:48:07'),(488,1217,1,'2018-10-11 06:48:57'),(489,1218,1,'2018-10-11 06:50:35'),(490,1219,1,'2018-10-11 06:51:44'),(491,1220,1,'2018-10-11 06:52:26'),(492,1221,1,'2018-10-11 06:53:26'),(493,1222,1,'2018-10-11 06:54:12'),(494,1223,1,'2018-10-11 06:55:01'),(495,1224,1,'2018-10-11 06:56:05'),(496,1225,1,'2018-10-11 06:56:59'),(497,1226,1,'2018-10-11 06:58:25'),(498,1227,1,'2018-10-11 13:09:51'),(499,1252,1,'2018-10-14 07:17:39'),(500,1253,1,'2018-10-14 07:18:14'),(501,1254,1,'2018-10-14 07:18:51'),(502,1255,1,'2018-10-14 07:19:21'),(503,1256,1,'2018-10-14 07:19:49'),(504,1257,1,'2018-10-14 07:20:13'),(505,1258,1,'2018-10-14 07:20:37'),(506,1259,1,'2018-10-14 07:21:05'),(507,1260,1,'2018-10-14 07:21:34'),(508,1261,1,'2018-10-14 07:21:59'),(509,1262,1,'2018-10-14 07:22:26'),(510,1263,1,'2018-10-14 07:22:52'),(511,1264,1,'2018-10-14 07:23:18'),(512,1265,1,'2018-10-14 07:24:02'),(513,1266,1,'2018-10-14 07:24:26'),(514,1267,1,'2018-10-14 07:24:53'),(515,1268,1,'2018-10-14 07:25:15'),(516,1573,1,'2018-10-16 04:37:41'),(517,1574,1,'2018-10-16 04:42:30'),(518,1577,1,'2018-10-17 08:37:56'),(519,1721,1,'2018-10-21 09:53:23'),(520,1723,1,'2018-10-24 06:41:25'),(521,1725,1,'2018-10-24 07:06:17'),(522,1727,1,'2018-10-24 08:59:28'),(523,1738,1,'2018-10-29 04:52:35'),(524,1743,1,'2018-10-31 06:39:20'),(525,1756,1,'2018-12-14 12:49:24'),(526,1763,1,'2019-01-09 06:00:48'),(527,1786,1,'2019-01-16 11:04:27'),(528,1862,1,'2019-02-13 09:05:28'),(529,1873,1,'2019-02-15 10:17:55'),(530,1874,1,'2019-02-15 10:28:08'),(531,1890,1,'2019-02-19 12:38:55'),(532,1896,1,'2019-02-21 10:18:24');
/*!40000 ALTER TABLE `h_tap_user_assessment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `h_transaction_assessment`
--

DROP TABLE IF EXISTS `h_transaction_assessment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `h_transaction_assessment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `transaction_id` int(11) NOT NULL,
  `assessment_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_h_client_product_transaction_id_idx` (`transaction_id`),
  KEY `fk_d_assessment_id_idx` (`assessment_id`),
  CONSTRAINT `fk_d_assessment_id1` FOREIGN KEY (`assessment_id`) REFERENCES `d_assessment` (`assessment_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_h_client_product_transaction_id` FOREIGN KEY (`transaction_id`) REFERENCES `h_client_product` (`transaction_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `h_transaction_assessment`
--

LOCK TABLES `h_transaction_assessment` WRITE;
/*!40000 ALTER TABLE `h_transaction_assessment` DISABLE KEYS */;
/*!40000 ALTER TABLE `h_transaction_assessment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `h_user_admin_levels`
--

DROP TABLE IF EXISTS `h_user_admin_levels`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `h_user_admin_levels` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `user_type_id` int(11) NOT NULL,
  `state_id` int(11) DEFAULT NULL,
  `zone_id` int(11) DEFAULT NULL,
  `block_id` int(11) DEFAULT NULL,
  `cluster_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_h_user_admin_levels_1_idx` (`user_id`),
  KEY `fk_h_user_admin_levels_2_idx` (`user_type_id`),
  CONSTRAINT `fk_h_user_admin_levels_1` FOREIGN KEY (`user_id`) REFERENCES `d_user` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_h_user_admin_levels_2` FOREIGN KEY (`user_type_id`) REFERENCES `d_user_type` (`user_type_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `h_user_admin_levels`
--

LOCK TABLES `h_user_admin_levels` WRITE;
/*!40000 ALTER TABLE `h_user_admin_levels` DISABLE KEYS */;
/*!40000 ALTER TABLE `h_user_admin_levels` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `h_user_language`
--

DROP TABLE IF EXISTS `h_user_language`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `h_user_language` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `language_speak` varchar(50) NOT NULL,
  `language_read` varchar(50) NOT NULL,
  `language_write` varchar(50) NOT NULL,
  `creation_date` timestamp NULL DEFAULT NULL,
  `modification_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `h_user_language`
--

LOCK TABLES `h_user_language` WRITE;
/*!40000 ALTER TABLE `h_user_language` DISABLE KEYS */;
/*!40000 ALTER TABLE `h_user_language` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `h_user_profile`
--

DROP TABLE IF EXISTS `h_user_profile`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `h_user_profile` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `gender` varchar(20) NOT NULL,
  `date_of_birth` date NOT NULL,
  `address` text NOT NULL,
  `town` varchar(100) NOT NULL,
  `state_id` int(4) NOT NULL,
  `pincode` varchar(10) NOT NULL,
  `school_contact_number` varchar(40) NOT NULL,
  `cell_number` varchar(40) NOT NULL,
  `emergency_firstname` varchar(255) NOT NULL,
  `emergency_lastname` varchar(255) NOT NULL,
  `emergency_email` varchar(255) NOT NULL,
  `emergency_relationship` varchar(255) NOT NULL,
  `emergency_address` text NOT NULL,
  `emergency_town` varchar(255) NOT NULL,
  `emergency_state_id` int(4) NOT NULL,
  `emergency_pincode` varchar(10) NOT NULL,
  `emergency_home_contact_no` varchar(40) NOT NULL,
  `emergency_cell_no` varchar(40) NOT NULL,
  `meal_preferences` enum('Vegetarian','Non-Vegetarian') NOT NULL,
  `medical_conditions` varchar(255) NOT NULL,
  `travel_outstation` varchar(10) NOT NULL,
  `other_medical_text` varchar(255) NOT NULL,
  `travel_sickness` varchar(10) NOT NULL,
  `travel_sickness_text` varchar(255) NOT NULL,
  `accomod_pref` varchar(100) NOT NULL,
  `education` varchar(255) NOT NULL,
  `assessor_experience` varchar(500) NOT NULL,
  `hobbies` varchar(500) NOT NULL,
  `other_hobbies_text` varchar(255) NOT NULL,
  `bank_name` varchar(255) NOT NULL,
  `ifsc_code` varchar(50) NOT NULL,
  `account_name` varchar(100) NOT NULL,
  `account_number` varchar(100) NOT NULL,
  `pancard_name` varchar(255) NOT NULL,
  `pancard_number` varchar(255) NOT NULL,
  `branch_address` text NOT NULL,
  `upload_document` varchar(255) NOT NULL,
  `profile_resume` int(10) NOT NULL,
  `term_condition` int(2) NOT NULL,
  `contract_value` varchar(20) NOT NULL,
  `workshop` varchar(255) NOT NULL,
  `is_submit` int(1) NOT NULL COMMENT '1=submit,0=not submit',
  `designation` varchar(255) NOT NULL,
  `work_experience` varchar(100) NOT NULL,
  `personal_pan_number` varchar(100) NOT NULL,
  `experience_description` text,
  `creation_date` timestamp NULL DEFAULT NULL,
  `modification_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `whatsapp_num` varchar(100) NOT NULL,
  `aadhar_number` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `h_user_profile`
--

LOCK TABLES `h_user_profile` WRITE;
/*!40000 ALTER TABLE `h_user_profile` DISABLE KEYS */;
/*!40000 ALTER TABLE `h_user_profile` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `h_user_review_notification`
--

DROP TABLE IF EXISTS `h_user_review_notification`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `h_user_review_notification` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `notification_id` int(11) NOT NULL,
  `assessment_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `type` (`type`),
  CONSTRAINT `h_user_review_notification_ibfk_1` FOREIGN KEY (`type`) REFERENCES `d_notification_type` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `h_user_review_notification`
--

LOCK TABLES `h_user_review_notification` WRITE;
/*!40000 ALTER TABLE `h_user_review_notification` DISABLE KEYS */;
/*!40000 ALTER TABLE `h_user_review_notification` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `h_user_review_reim_sheet_status`
--

DROP TABLE IF EXISTS `h_user_review_reim_sheet_status`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `h_user_review_reim_sheet_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `assessment_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `sheet_status` int(11) NOT NULL,
  `first_mail_status` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `mail_sent_date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `h_user_review_reim_sheet_status`
--

LOCK TABLES `h_user_review_reim_sheet_status` WRITE;
/*!40000 ALTER TABLE `h_user_review_reim_sheet_status` DISABLE KEYS */;
/*!40000 ALTER TABLE `h_user_review_reim_sheet_status` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `h_user_role_user_capability`
--

DROP TABLE IF EXISTS `h_user_role_user_capability`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `h_user_role_user_capability` (
  `role_capability_id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) NOT NULL,
  `capability_id` int(11) NOT NULL,
  PRIMARY KEY (`role_capability_id`),
  UNIQUE KEY `role_id` (`role_id`,`capability_id`),
  KEY `user_role_id` (`role_id`,`capability_id`),
  KEY `capability_id` (`capability_id`)
) ENGINE=InnoDB AUTO_INCREMENT=125 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `h_user_role_user_capability`
--

LOCK TABLES `h_user_role_user_capability` WRITE;
/*!40000 ALTER TABLE `h_user_role_user_capability` DISABLE KEYS */;
INSERT INTO `h_user_role_user_capability` VALUES (1,1,1),(3,1,2),(75,1,3),(120,1,4),(11,1,5),(20,1,7),(21,1,8),(25,1,9),(31,1,12),(29,1,13),(30,1,14),(33,1,16),(52,1,21),(113,1,23),(59,1,24),(79,1,26),(71,1,28),(119,1,32),(2,2,1),(4,2,2),(8,2,3),(10,2,4),(12,2,5),(19,2,7),(22,2,8),(26,2,9),(35,2,12),(34,2,14),(37,2,16),(53,2,21),(114,2,23),(60,2,24),(80,2,26),(72,2,28),(13,3,5),(49,3,17),(121,4,4),(106,4,5),(48,4,18),(63,4,25),(5,5,1),(15,5,5),(23,5,8),(55,5,22),(62,5,25),(83,5,29),(6,6,1),(104,6,5),(24,6,8),(54,6,22),(61,6,25),(82,6,29),(97,7,25),(111,7,32),(64,8,1),(66,8,2),(67,8,4),(70,8,5),(68,8,9),(81,8,26),(69,8,27),(73,8,28),(108,10,32),(109,11,32),(123,12,5),(110,12,32);
/*!40000 ALTER TABLE `h_user_role_user_capability` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `h_user_user_role`
--

DROP TABLE IF EXISTS `h_user_user_role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `h_user_user_role` (
  `user_user_role_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  PRIMARY KEY (`user_user_role_id`),
  KEY `user_id` (`user_id`,`role_id`),
  KEY `role_id` (`role_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `h_user_user_role`
--

LOCK TABLES `h_user_user_role` WRITE;
/*!40000 ALTER TABLE `h_user_user_role` DISABLE KEYS */;
INSERT INTO `h_user_user_role` VALUES (1,1,1),(3,1895,3),(2,1895,6),(4,1896,4);
/*!40000 ALTER TABLE `h_user_user_role` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `h_zone_state`
--

DROP TABLE IF EXISTS `h_zone_state`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `h_zone_state` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `zone_id` int(11) NOT NULL,
  `state_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_h_zone_state_1_idx` (`zone_id`),
  KEY `fk_h_zone_state_2_idx` (`state_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `h_zone_state`
--

LOCK TABLES `h_zone_state` WRITE;
/*!40000 ALTER TABLE `h_zone_state` DISABLE KEYS */;
INSERT INTO `h_zone_state` VALUES (1,1,1);
/*!40000 ALTER TABLE `h_zone_state` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `preferred_language`
--

DROP TABLE IF EXISTS `preferred_language`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `preferred_language` (
  `lang_id` int(11) NOT NULL AUTO_INCREMENT,
  `lang_name` varchar(45) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`lang_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `preferred_language`
--

LOCK TABLES `preferred_language` WRITE;
/*!40000 ALTER TABLE `preferred_language` DISABLE KEYS */;
/*!40000 ALTER TABLE `preferred_language` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `session_token`
--

DROP TABLE IF EXISTS `session_token`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `session_token` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(50) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_date` datetime NOT NULL,
  `updated_date` datetime NOT NULL,
  `server_details` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6374 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `session_token`
--

LOCK TABLES `session_token` WRITE;
/*!40000 ALTER TABLE `session_token` DISABLE KEYS */;
INSERT INTO `session_token` VALUES (6361,'f8f4cfe35fad230d00492e8080334fbe',1,'2019-02-21 15:50:17','2019-02-21 16:06:56','a:34:{s:9:\"HTTP_HOST\";s:25:\"stage.adhfd.adhyayan.asia\";s:15:\"HTTP_USER_AGENT\";s:78:\"Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:65.0) Gecko/20100101 Firefox/65.0\";s:11:\"HTTP_ACCEPT\";s:74:\"text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8\";s:20:\"HTTP_ACCEPT_LANGUAGE\";s:14:\"en-US,en;q=0.5\";s:20:\"HTTP_ACCEPT_ENCODING\";s:13:\"gzip, deflate\";s:12:\"HTTP_REFERER\";s:72:\"http://stage.adhfd.adhyayan.asia/index.php?controller=login&action=login\";s:12:\"CONTENT_TYPE\";s:33:\"application/x-www-form-urlencoded\";s:14:\"CONTENT_LENGTH\";s:2:\"60\";s:15:\"HTTP_CONNECTION\";s:10:\"keep-alive\";s:11:\"HTTP_COOKIE\";s:12:\"ADH_LANG=all\";s:30:\"HTTP_UPGRADE_INSECURE_REQUESTS\";s:1:\"1\";s:4:\"PATH\";s:60:\"/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin\";s:16:\"SERVER_SIGNATURE\";s:86:\"<address>Apache/2.4.18 (Ubuntu) Server at stage.adhfd.adhyayan.asia Port 80</address>\n\";s:15:\"SERVER_SOFTWARE\";s:22:\"Apache/2.4.18 (Ubuntu)\";s:11:\"SERVER_NAME\";s:25:\"stage.adhfd.adhyayan.asia\";s:11:\"SERVER_ADDR\";s:12:\"172.31.31.99\";s:11:\"SERVER_PORT\";s:2:\"80\";s:11:\"REMOTE_ADDR\";s:14:\"180.151.85.178\";s:13:\"DOCUMENT_ROOT\";s:36:\"/var/www/Adhyayan-app/Adhyayan-seli/\";s:14:\"REQUEST_SCHEME\";s:4:\"http\";s:14:\"CONTEXT_PREFIX\";s:0:\"\";s:21:\"CONTEXT_DOCUMENT_ROOT\";s:36:\"/var/www/Adhyayan-app/Adhyayan-seli/\";s:12:\"SERVER_ADMIN\";s:20:\"ankur@tatrasdata.com\";s:15:\"SCRIPT_FILENAME\";s:45:\"/var/www/Adhyayan-app/Adhyayan-seli/index.php\";s:11:\"REMOTE_PORT\";s:5:\"51377\";s:17:\"GATEWAY_INTERFACE\";s:7:\"CGI/1.1\";s:15:\"SERVER_PROTOCOL\";s:8:\"HTTP/1.1\";s:14:\"REQUEST_METHOD\";s:4:\"POST\";s:12:\"QUERY_STRING\";s:29:\"controller=login&action=login\";s:11:\"REQUEST_URI\";s:40:\"/index.php?controller=login&action=login\";s:11:\"SCRIPT_NAME\";s:10:\"/index.php\";s:8:\"PHP_SELF\";s:10:\"/index.php\";s:18:\"REQUEST_TIME_FLOAT\";d:1550745416.7260001;s:12:\"REQUEST_TIME\";i:1550745416;}'),(6363,'b8a50ccd85c80439c641f9671068071d',1,'2019-02-21 15:51:21','2019-02-21 16:06:56','a:34:{s:9:\"HTTP_HOST\";s:25:\"stage.adhfd.adhyayan.asia\";s:15:\"HTTP_USER_AGENT\";s:78:\"Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:65.0) Gecko/20100101 Firefox/65.0\";s:11:\"HTTP_ACCEPT\";s:74:\"text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8\";s:20:\"HTTP_ACCEPT_LANGUAGE\";s:14:\"en-US,en;q=0.5\";s:20:\"HTTP_ACCEPT_ENCODING\";s:13:\"gzip, deflate\";s:12:\"HTTP_REFERER\";s:72:\"http://stage.adhfd.adhyayan.asia/index.php?controller=login&action=login\";s:12:\"CONTENT_TYPE\";s:33:\"application/x-www-form-urlencoded\";s:14:\"CONTENT_LENGTH\";s:2:\"60\";s:15:\"HTTP_CONNECTION\";s:10:\"keep-alive\";s:11:\"HTTP_COOKIE\";s:12:\"ADH_LANG=all\";s:30:\"HTTP_UPGRADE_INSECURE_REQUESTS\";s:1:\"1\";s:4:\"PATH\";s:60:\"/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin\";s:16:\"SERVER_SIGNATURE\";s:86:\"<address>Apache/2.4.18 (Ubuntu) Server at stage.adhfd.adhyayan.asia Port 80</address>\n\";s:15:\"SERVER_SOFTWARE\";s:22:\"Apache/2.4.18 (Ubuntu)\";s:11:\"SERVER_NAME\";s:25:\"stage.adhfd.adhyayan.asia\";s:11:\"SERVER_ADDR\";s:12:\"172.31.31.99\";s:11:\"SERVER_PORT\";s:2:\"80\";s:11:\"REMOTE_ADDR\";s:14:\"180.151.85.178\";s:13:\"DOCUMENT_ROOT\";s:36:\"/var/www/Adhyayan-app/Adhyayan-seli/\";s:14:\"REQUEST_SCHEME\";s:4:\"http\";s:14:\"CONTEXT_PREFIX\";s:0:\"\";s:21:\"CONTEXT_DOCUMENT_ROOT\";s:36:\"/var/www/Adhyayan-app/Adhyayan-seli/\";s:12:\"SERVER_ADMIN\";s:20:\"ankur@tatrasdata.com\";s:15:\"SCRIPT_FILENAME\";s:45:\"/var/www/Adhyayan-app/Adhyayan-seli/index.php\";s:11:\"REMOTE_PORT\";s:5:\"51377\";s:17:\"GATEWAY_INTERFACE\";s:7:\"CGI/1.1\";s:15:\"SERVER_PROTOCOL\";s:8:\"HTTP/1.1\";s:14:\"REQUEST_METHOD\";s:4:\"POST\";s:12:\"QUERY_STRING\";s:29:\"controller=login&action=login\";s:11:\"REQUEST_URI\";s:40:\"/index.php?controller=login&action=login\";s:11:\"SCRIPT_NAME\";s:10:\"/index.php\";s:8:\"PHP_SELF\";s:10:\"/index.php\";s:18:\"REQUEST_TIME_FLOAT\";d:1550745416.7260001;s:12:\"REQUEST_TIME\";i:1550745416;}'),(6367,'a8e44b3d6189625be6b0fe3d0a0952bd',1,'2019-02-21 15:58:36','2019-02-21 16:06:56','a:34:{s:9:\"HTTP_HOST\";s:25:\"stage.adhfd.adhyayan.asia\";s:15:\"HTTP_USER_AGENT\";s:78:\"Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:65.0) Gecko/20100101 Firefox/65.0\";s:11:\"HTTP_ACCEPT\";s:74:\"text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8\";s:20:\"HTTP_ACCEPT_LANGUAGE\";s:14:\"en-US,en;q=0.5\";s:20:\"HTTP_ACCEPT_ENCODING\";s:13:\"gzip, deflate\";s:12:\"HTTP_REFERER\";s:72:\"http://stage.adhfd.adhyayan.asia/index.php?controller=login&action=login\";s:12:\"CONTENT_TYPE\";s:33:\"application/x-www-form-urlencoded\";s:14:\"CONTENT_LENGTH\";s:2:\"60\";s:15:\"HTTP_CONNECTION\";s:10:\"keep-alive\";s:11:\"HTTP_COOKIE\";s:12:\"ADH_LANG=all\";s:30:\"HTTP_UPGRADE_INSECURE_REQUESTS\";s:1:\"1\";s:4:\"PATH\";s:60:\"/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin\";s:16:\"SERVER_SIGNATURE\";s:86:\"<address>Apache/2.4.18 (Ubuntu) Server at stage.adhfd.adhyayan.asia Port 80</address>\n\";s:15:\"SERVER_SOFTWARE\";s:22:\"Apache/2.4.18 (Ubuntu)\";s:11:\"SERVER_NAME\";s:25:\"stage.adhfd.adhyayan.asia\";s:11:\"SERVER_ADDR\";s:12:\"172.31.31.99\";s:11:\"SERVER_PORT\";s:2:\"80\";s:11:\"REMOTE_ADDR\";s:14:\"180.151.85.178\";s:13:\"DOCUMENT_ROOT\";s:36:\"/var/www/Adhyayan-app/Adhyayan-seli/\";s:14:\"REQUEST_SCHEME\";s:4:\"http\";s:14:\"CONTEXT_PREFIX\";s:0:\"\";s:21:\"CONTEXT_DOCUMENT_ROOT\";s:36:\"/var/www/Adhyayan-app/Adhyayan-seli/\";s:12:\"SERVER_ADMIN\";s:20:\"ankur@tatrasdata.com\";s:15:\"SCRIPT_FILENAME\";s:45:\"/var/www/Adhyayan-app/Adhyayan-seli/index.php\";s:11:\"REMOTE_PORT\";s:5:\"51377\";s:17:\"GATEWAY_INTERFACE\";s:7:\"CGI/1.1\";s:15:\"SERVER_PROTOCOL\";s:8:\"HTTP/1.1\";s:14:\"REQUEST_METHOD\";s:4:\"POST\";s:12:\"QUERY_STRING\";s:29:\"controller=login&action=login\";s:11:\"REQUEST_URI\";s:40:\"/index.php?controller=login&action=login\";s:11:\"SCRIPT_NAME\";s:10:\"/index.php\";s:8:\"PHP_SELF\";s:10:\"/index.php\";s:18:\"REQUEST_TIME_FLOAT\";d:1550745416.7260001;s:12:\"REQUEST_TIME\";i:1550745416;}'),(6368,'dfc63119f679eeedb608c7020d557944',1,'2019-02-21 16:03:56','2019-02-21 16:06:56','a:34:{s:9:\"HTTP_HOST\";s:25:\"stage.adhfd.adhyayan.asia\";s:15:\"HTTP_USER_AGENT\";s:78:\"Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:65.0) Gecko/20100101 Firefox/65.0\";s:11:\"HTTP_ACCEPT\";s:74:\"text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8\";s:20:\"HTTP_ACCEPT_LANGUAGE\";s:14:\"en-US,en;q=0.5\";s:20:\"HTTP_ACCEPT_ENCODING\";s:13:\"gzip, deflate\";s:12:\"HTTP_REFERER\";s:72:\"http://stage.adhfd.adhyayan.asia/index.php?controller=login&action=login\";s:12:\"CONTENT_TYPE\";s:33:\"application/x-www-form-urlencoded\";s:14:\"CONTENT_LENGTH\";s:2:\"60\";s:15:\"HTTP_CONNECTION\";s:10:\"keep-alive\";s:11:\"HTTP_COOKIE\";s:12:\"ADH_LANG=all\";s:30:\"HTTP_UPGRADE_INSECURE_REQUESTS\";s:1:\"1\";s:4:\"PATH\";s:60:\"/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin\";s:16:\"SERVER_SIGNATURE\";s:86:\"<address>Apache/2.4.18 (Ubuntu) Server at stage.adhfd.adhyayan.asia Port 80</address>\n\";s:15:\"SERVER_SOFTWARE\";s:22:\"Apache/2.4.18 (Ubuntu)\";s:11:\"SERVER_NAME\";s:25:\"stage.adhfd.adhyayan.asia\";s:11:\"SERVER_ADDR\";s:12:\"172.31.31.99\";s:11:\"SERVER_PORT\";s:2:\"80\";s:11:\"REMOTE_ADDR\";s:14:\"180.151.85.178\";s:13:\"DOCUMENT_ROOT\";s:36:\"/var/www/Adhyayan-app/Adhyayan-seli/\";s:14:\"REQUEST_SCHEME\";s:4:\"http\";s:14:\"CONTEXT_PREFIX\";s:0:\"\";s:21:\"CONTEXT_DOCUMENT_ROOT\";s:36:\"/var/www/Adhyayan-app/Adhyayan-seli/\";s:12:\"SERVER_ADMIN\";s:20:\"ankur@tatrasdata.com\";s:15:\"SCRIPT_FILENAME\";s:45:\"/var/www/Adhyayan-app/Adhyayan-seli/index.php\";s:11:\"REMOTE_PORT\";s:5:\"51377\";s:17:\"GATEWAY_INTERFACE\";s:7:\"CGI/1.1\";s:15:\"SERVER_PROTOCOL\";s:8:\"HTTP/1.1\";s:14:\"REQUEST_METHOD\";s:4:\"POST\";s:12:\"QUERY_STRING\";s:29:\"controller=login&action=login\";s:11:\"REQUEST_URI\";s:40:\"/index.php?controller=login&action=login\";s:11:\"SCRIPT_NAME\";s:10:\"/index.php\";s:8:\"PHP_SELF\";s:10:\"/index.php\";s:18:\"REQUEST_TIME_FLOAT\";d:1550745416.7260001;s:12:\"REQUEST_TIME\";i:1550745416;}'),(6369,'7df897c190c769cc3406acdb091b41d7',1,'2019-02-21 16:04:42','2019-02-21 16:06:56','a:34:{s:9:\"HTTP_HOST\";s:25:\"stage.adhfd.adhyayan.asia\";s:15:\"HTTP_USER_AGENT\";s:78:\"Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:65.0) Gecko/20100101 Firefox/65.0\";s:11:\"HTTP_ACCEPT\";s:74:\"text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8\";s:20:\"HTTP_ACCEPT_LANGUAGE\";s:14:\"en-US,en;q=0.5\";s:20:\"HTTP_ACCEPT_ENCODING\";s:13:\"gzip, deflate\";s:12:\"HTTP_REFERER\";s:72:\"http://stage.adhfd.adhyayan.asia/index.php?controller=login&action=login\";s:12:\"CONTENT_TYPE\";s:33:\"application/x-www-form-urlencoded\";s:14:\"CONTENT_LENGTH\";s:2:\"60\";s:15:\"HTTP_CONNECTION\";s:10:\"keep-alive\";s:11:\"HTTP_COOKIE\";s:12:\"ADH_LANG=all\";s:30:\"HTTP_UPGRADE_INSECURE_REQUESTS\";s:1:\"1\";s:4:\"PATH\";s:60:\"/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin\";s:16:\"SERVER_SIGNATURE\";s:86:\"<address>Apache/2.4.18 (Ubuntu) Server at stage.adhfd.adhyayan.asia Port 80</address>\n\";s:15:\"SERVER_SOFTWARE\";s:22:\"Apache/2.4.18 (Ubuntu)\";s:11:\"SERVER_NAME\";s:25:\"stage.adhfd.adhyayan.asia\";s:11:\"SERVER_ADDR\";s:12:\"172.31.31.99\";s:11:\"SERVER_PORT\";s:2:\"80\";s:11:\"REMOTE_ADDR\";s:14:\"180.151.85.178\";s:13:\"DOCUMENT_ROOT\";s:36:\"/var/www/Adhyayan-app/Adhyayan-seli/\";s:14:\"REQUEST_SCHEME\";s:4:\"http\";s:14:\"CONTEXT_PREFIX\";s:0:\"\";s:21:\"CONTEXT_DOCUMENT_ROOT\";s:36:\"/var/www/Adhyayan-app/Adhyayan-seli/\";s:12:\"SERVER_ADMIN\";s:20:\"ankur@tatrasdata.com\";s:15:\"SCRIPT_FILENAME\";s:45:\"/var/www/Adhyayan-app/Adhyayan-seli/index.php\";s:11:\"REMOTE_PORT\";s:5:\"51377\";s:17:\"GATEWAY_INTERFACE\";s:7:\"CGI/1.1\";s:15:\"SERVER_PROTOCOL\";s:8:\"HTTP/1.1\";s:14:\"REQUEST_METHOD\";s:4:\"POST\";s:12:\"QUERY_STRING\";s:29:\"controller=login&action=login\";s:11:\"REQUEST_URI\";s:40:\"/index.php?controller=login&action=login\";s:11:\"SCRIPT_NAME\";s:10:\"/index.php\";s:8:\"PHP_SELF\";s:10:\"/index.php\";s:18:\"REQUEST_TIME_FLOAT\";d:1550745416.7260001;s:12:\"REQUEST_TIME\";i:1550745416;}'),(6370,'66eabb02bc9428c347820d1adb71c627',1,'2019-02-21 16:05:15','2019-02-21 16:06:56','a:34:{s:9:\"HTTP_HOST\";s:25:\"stage.adhfd.adhyayan.asia\";s:15:\"HTTP_USER_AGENT\";s:78:\"Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:65.0) Gecko/20100101 Firefox/65.0\";s:11:\"HTTP_ACCEPT\";s:74:\"text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8\";s:20:\"HTTP_ACCEPT_LANGUAGE\";s:14:\"en-US,en;q=0.5\";s:20:\"HTTP_ACCEPT_ENCODING\";s:13:\"gzip, deflate\";s:12:\"HTTP_REFERER\";s:72:\"http://stage.adhfd.adhyayan.asia/index.php?controller=login&action=login\";s:12:\"CONTENT_TYPE\";s:33:\"application/x-www-form-urlencoded\";s:14:\"CONTENT_LENGTH\";s:2:\"60\";s:15:\"HTTP_CONNECTION\";s:10:\"keep-alive\";s:11:\"HTTP_COOKIE\";s:12:\"ADH_LANG=all\";s:30:\"HTTP_UPGRADE_INSECURE_REQUESTS\";s:1:\"1\";s:4:\"PATH\";s:60:\"/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin\";s:16:\"SERVER_SIGNATURE\";s:86:\"<address>Apache/2.4.18 (Ubuntu) Server at stage.adhfd.adhyayan.asia Port 80</address>\n\";s:15:\"SERVER_SOFTWARE\";s:22:\"Apache/2.4.18 (Ubuntu)\";s:11:\"SERVER_NAME\";s:25:\"stage.adhfd.adhyayan.asia\";s:11:\"SERVER_ADDR\";s:12:\"172.31.31.99\";s:11:\"SERVER_PORT\";s:2:\"80\";s:11:\"REMOTE_ADDR\";s:14:\"180.151.85.178\";s:13:\"DOCUMENT_ROOT\";s:36:\"/var/www/Adhyayan-app/Adhyayan-seli/\";s:14:\"REQUEST_SCHEME\";s:4:\"http\";s:14:\"CONTEXT_PREFIX\";s:0:\"\";s:21:\"CONTEXT_DOCUMENT_ROOT\";s:36:\"/var/www/Adhyayan-app/Adhyayan-seli/\";s:12:\"SERVER_ADMIN\";s:20:\"ankur@tatrasdata.com\";s:15:\"SCRIPT_FILENAME\";s:45:\"/var/www/Adhyayan-app/Adhyayan-seli/index.php\";s:11:\"REMOTE_PORT\";s:5:\"51377\";s:17:\"GATEWAY_INTERFACE\";s:7:\"CGI/1.1\";s:15:\"SERVER_PROTOCOL\";s:8:\"HTTP/1.1\";s:14:\"REQUEST_METHOD\";s:4:\"POST\";s:12:\"QUERY_STRING\";s:29:\"controller=login&action=login\";s:11:\"REQUEST_URI\";s:40:\"/index.php?controller=login&action=login\";s:11:\"SCRIPT_NAME\";s:10:\"/index.php\";s:8:\"PHP_SELF\";s:10:\"/index.php\";s:18:\"REQUEST_TIME_FLOAT\";d:1550745416.7260001;s:12:\"REQUEST_TIME\";i:1550745416;}'),(6371,'d47a3817828030bc756cc635de26f80b',1,'2019-02-21 16:05:46','2019-02-21 16:06:56','a:34:{s:9:\"HTTP_HOST\";s:25:\"stage.adhfd.adhyayan.asia\";s:15:\"HTTP_USER_AGENT\";s:78:\"Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:65.0) Gecko/20100101 Firefox/65.0\";s:11:\"HTTP_ACCEPT\";s:74:\"text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8\";s:20:\"HTTP_ACCEPT_LANGUAGE\";s:14:\"en-US,en;q=0.5\";s:20:\"HTTP_ACCEPT_ENCODING\";s:13:\"gzip, deflate\";s:12:\"HTTP_REFERER\";s:72:\"http://stage.adhfd.adhyayan.asia/index.php?controller=login&action=login\";s:12:\"CONTENT_TYPE\";s:33:\"application/x-www-form-urlencoded\";s:14:\"CONTENT_LENGTH\";s:2:\"60\";s:15:\"HTTP_CONNECTION\";s:10:\"keep-alive\";s:11:\"HTTP_COOKIE\";s:12:\"ADH_LANG=all\";s:30:\"HTTP_UPGRADE_INSECURE_REQUESTS\";s:1:\"1\";s:4:\"PATH\";s:60:\"/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin\";s:16:\"SERVER_SIGNATURE\";s:86:\"<address>Apache/2.4.18 (Ubuntu) Server at stage.adhfd.adhyayan.asia Port 80</address>\n\";s:15:\"SERVER_SOFTWARE\";s:22:\"Apache/2.4.18 (Ubuntu)\";s:11:\"SERVER_NAME\";s:25:\"stage.adhfd.adhyayan.asia\";s:11:\"SERVER_ADDR\";s:12:\"172.31.31.99\";s:11:\"SERVER_PORT\";s:2:\"80\";s:11:\"REMOTE_ADDR\";s:14:\"180.151.85.178\";s:13:\"DOCUMENT_ROOT\";s:36:\"/var/www/Adhyayan-app/Adhyayan-seli/\";s:14:\"REQUEST_SCHEME\";s:4:\"http\";s:14:\"CONTEXT_PREFIX\";s:0:\"\";s:21:\"CONTEXT_DOCUMENT_ROOT\";s:36:\"/var/www/Adhyayan-app/Adhyayan-seli/\";s:12:\"SERVER_ADMIN\";s:20:\"ankur@tatrasdata.com\";s:15:\"SCRIPT_FILENAME\";s:45:\"/var/www/Adhyayan-app/Adhyayan-seli/index.php\";s:11:\"REMOTE_PORT\";s:5:\"51377\";s:17:\"GATEWAY_INTERFACE\";s:7:\"CGI/1.1\";s:15:\"SERVER_PROTOCOL\";s:8:\"HTTP/1.1\";s:14:\"REQUEST_METHOD\";s:4:\"POST\";s:12:\"QUERY_STRING\";s:29:\"controller=login&action=login\";s:11:\"REQUEST_URI\";s:40:\"/index.php?controller=login&action=login\";s:11:\"SCRIPT_NAME\";s:10:\"/index.php\";s:8:\"PHP_SELF\";s:10:\"/index.php\";s:18:\"REQUEST_TIME_FLOAT\";d:1550745416.7260001;s:12:\"REQUEST_TIME\";i:1550745416;}'),(6373,'fe44a5519c2c73f30b53f1f1c36df27a',1,'2019-02-21 16:06:56','2019-02-21 16:06:56','a:34:{s:9:\"HTTP_HOST\";s:25:\"stage.adhfd.adhyayan.asia\";s:15:\"HTTP_USER_AGENT\";s:78:\"Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:65.0) Gecko/20100101 Firefox/65.0\";s:11:\"HTTP_ACCEPT\";s:74:\"text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8\";s:20:\"HTTP_ACCEPT_LANGUAGE\";s:14:\"en-US,en;q=0.5\";s:20:\"HTTP_ACCEPT_ENCODING\";s:13:\"gzip, deflate\";s:12:\"HTTP_REFERER\";s:72:\"http://stage.adhfd.adhyayan.asia/index.php?controller=login&action=login\";s:12:\"CONTENT_TYPE\";s:33:\"application/x-www-form-urlencoded\";s:14:\"CONTENT_LENGTH\";s:2:\"60\";s:15:\"HTTP_CONNECTION\";s:10:\"keep-alive\";s:11:\"HTTP_COOKIE\";s:12:\"ADH_LANG=all\";s:30:\"HTTP_UPGRADE_INSECURE_REQUESTS\";s:1:\"1\";s:4:\"PATH\";s:60:\"/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin\";s:16:\"SERVER_SIGNATURE\";s:86:\"<address>Apache/2.4.18 (Ubuntu) Server at stage.adhfd.adhyayan.asia Port 80</address>\n\";s:15:\"SERVER_SOFTWARE\";s:22:\"Apache/2.4.18 (Ubuntu)\";s:11:\"SERVER_NAME\";s:25:\"stage.adhfd.adhyayan.asia\";s:11:\"SERVER_ADDR\";s:12:\"172.31.31.99\";s:11:\"SERVER_PORT\";s:2:\"80\";s:11:\"REMOTE_ADDR\";s:14:\"180.151.85.178\";s:13:\"DOCUMENT_ROOT\";s:36:\"/var/www/Adhyayan-app/Adhyayan-seli/\";s:14:\"REQUEST_SCHEME\";s:4:\"http\";s:14:\"CONTEXT_PREFIX\";s:0:\"\";s:21:\"CONTEXT_DOCUMENT_ROOT\";s:36:\"/var/www/Adhyayan-app/Adhyayan-seli/\";s:12:\"SERVER_ADMIN\";s:20:\"ankur@tatrasdata.com\";s:15:\"SCRIPT_FILENAME\";s:45:\"/var/www/Adhyayan-app/Adhyayan-seli/index.php\";s:11:\"REMOTE_PORT\";s:5:\"51377\";s:17:\"GATEWAY_INTERFACE\";s:7:\"CGI/1.1\";s:15:\"SERVER_PROTOCOL\";s:8:\"HTTP/1.1\";s:14:\"REQUEST_METHOD\";s:4:\"POST\";s:12:\"QUERY_STRING\";s:29:\"controller=login&action=login\";s:11:\"REQUEST_URI\";s:40:\"/index.php?controller=login&action=login\";s:11:\"SCRIPT_NAME\";s:10:\"/index.php\";s:8:\"PHP_SELF\";s:10:\"/index.php\";s:18:\"REQUEST_TIME_FLOAT\";d:1550745416.7260001;s:12:\"REQUEST_TIME\";i:1550745416;}');
/*!40000 ALTER TABLE `session_token` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `z_history_users`
--

DROP TABLE IF EXISTS `z_history_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `z_history_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `client_id_from` int(11) NOT NULL,
  `client_id_to` int(11) NOT NULL,
  `users_roles_from` text NOT NULL,
  `users_roles_to` text NOT NULL,
  `user_action` varchar(255) NOT NULL,
  `created_by` int(11) NOT NULL,
  `action_date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `z_history_users`
--

LOCK TABLES `z_history_users` WRITE;
/*!40000 ALTER TABLE `z_history_users` DISABLE KEYS */;
INSERT INTO `z_history_users` VALUES (1,1785,875,875,'12','12','Updated',1,'2019-02-20 12:53:32'),(2,1844,888,888,'3','3,9','Updated',1,'2019-02-20 14:16:39');
/*!40000 ALTER TABLE `z_history_users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-02-21 16:12:40
