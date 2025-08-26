-- MySQL dump 10.13  Distrib 8.0.36, for Linux (x86_64)
--
-- Host: 127.0.0.1    Database: argtest
-- ------------------------------------------------------
-- Server version	8.0.42-0ubuntu0.20.04.1

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

--
-- Table structure for table `collaborators`
--

DROP TABLE IF EXISTS `collaborators`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `collaborators` (
  `collaboratorid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `proposalidfk` bigint unsigned NOT NULL,
  `collaboratorname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `position` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `institution` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `researcharea` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `experience` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`collaboratorid`),
  KEY `collaborators_proposalidfk_foreign` (`proposalidfk`),
  CONSTRAINT `collaborators_proposalidfk_foreign` FOREIGN KEY (`proposalidfk`) REFERENCES `proposals` (`proposalid`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `collaborators`
--

LOCK TABLES `collaborators` WRITE;
/*!40000 ALTER TABLE `collaborators` DISABLE KEYS */;
INSERT INTO `collaborators` VALUES ('374f0a7f-f89f-4b3e-a42a-a126efed5cd1',2,'knoph','knoph','knoph','knoph','knoph','2025-08-23 17:02:22','2025-08-23 17:02:22'),('4ee1ea38-26d2-4da6-b4ef-3aabd1395731',2,'proposalId','Research Assistant','proposalId','fkiprotich845@gmail.com','proposalId','2025-08-23 16:49:46','2025-08-23 16:49:46'),('a218017e-1c56-48d7-bc84-0847fc720adf',2,'string','string','string','string@mail.com','string','2025-08-23 16:48:05','2025-08-23 16:48:05');
/*!40000 ALTER TABLE `collaborators` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `departments`
--

DROP TABLE IF EXISTS `departments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `departments` (
  `depid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `schoolfk` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `shortname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`depid`),
  KEY `departments_schoolfk_foreign` (`schoolfk`),
  CONSTRAINT `departments_schoolfk_foreign` FOREIGN KEY (`schoolfk`) REFERENCES `schools` (`schoolid`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `departments`
--

LOCK TABLES `departments` WRITE;
/*!40000 ALTER TABLE `departments` DISABLE KEYS */;
INSERT INTO `departments` VALUES ('22002eac-1935-4217-83ca-332dabcbf4fe','f8f5ed94-f0db-475f-90e7-f40c3383872f','depa 1','test','2025-08-23 08:34:27','2025-08-23 08:34:27'),('4bef5383-6bd6-4975-9d87-471e6d36ea72','f8f5ed94-f0db-475f-90e7-f40c3383872f','Department 1','Department 1','2025-03-13 16:05:36','2025-03-13 16:05:36');
/*!40000 ALTER TABLE `departments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `expenditures`
--

DROP TABLE IF EXISTS `expenditures`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `expenditures` (
  `expenditureid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `proposalidfk` bigint unsigned NOT NULL,
  `item` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `itemtype` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` int NOT NULL,
  `unitprice` decimal(8,2) NOT NULL,
  `total` decimal(15,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`expenditureid`),
  KEY `expenditures_proposalidfk_foreign` (`proposalidfk`),
  CONSTRAINT `expenditures_proposalidfk_foreign` FOREIGN KEY (`proposalidfk`) REFERENCES `proposals` (`proposalid`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `expenditures`
--

LOCK TABLES `expenditures` WRITE;
/*!40000 ALTER TABLE `expenditures` DISABLE KEYS */;
INSERT INTO `expenditures` VALUES ('211bcda6-d97f-4ac9-8401-09050405dd2f',1,'Initial Cost','Facilities/Equipment',1,1000.00,1000.00,'2025-03-13 16:54:57','2025-03-13 16:54:57'),('31aea39b-67d4-4e6a-bd7f-b1934756f37b',2,'1000','Facilities/Equipment',1,1000.00,1000.00,'2025-08-23 17:43:14','2025-08-23 17:43:14'),('53b0fbc0-909c-45f4-beaa-b5e8005652ab',2,'laptop','Facilities/Equipment',2,50000.00,100000.00,'2025-08-23 20:42:08','2025-08-23 20:42:08'),('5e6fda35-2223-42d0-9dfe-d8c486885514',2,'laptop','Personnel/Subsistence',1,555.00,555.00,'2025-08-23 17:04:40','2025-08-23 17:04:40'),('62874fe9-bde0-46db-b41f-7de572c48312',2,'laptop','Facilities/Equipment',1,1.00,1.00,'2025-08-24 07:18:47','2025-08-24 07:18:47');
/*!40000 ALTER TABLE `expenditures` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `finyears`
--

DROP TABLE IF EXISTS `finyears`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `finyears` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `finyear` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `startdate` date DEFAULT NULL,
  `enddate` date DEFAULT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `finyears_finyear_unique` (`finyear`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `finyears`
--

LOCK TABLES `finyears` WRITE;
/*!40000 ALTER TABLE `finyears` DISABLE KEYS */;
INSERT INTO `finyears` VALUES (1,'2024/2025','2024-07-01','2025-06-30',NULL,'2025-03-13 16:07:10','2025-03-13 16:07:10');
/*!40000 ALTER TABLE `finyears` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `globalsettings`
--

DROP TABLE IF EXISTS `globalsettings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `globalsettings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `item` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value1` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value2` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `globalsettings`
--

LOCK TABLES `globalsettings` WRITE;
/*!40000 ALTER TABLE `globalsettings` DISABLE KEYS */;
INSERT INTO `globalsettings` VALUES (1,'current_open_grant','2',NULL,NULL,'2025-08-26 09:01:07'),(2,'current_fin_year','1',NULL,NULL,NULL);
/*!40000 ALTER TABLE `globalsettings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `grants`
--

DROP TABLE IF EXISTS `grants`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `grants` (
  `grantid` int unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `finyearfk` bigint unsigned NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`grantid`),
  KEY `grants_finyearfk_foreign` (`finyearfk`),
  CONSTRAINT `grants_finyearfk_foreign` FOREIGN KEY (`finyearfk`) REFERENCES `finyears` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `grants`
--

LOCK TABLES `grants` WRITE;
/*!40000 ALTER TABLE `grants` DISABLE KEYS */;
INSERT INTO `grants` VALUES (1,'2024-25 Grant',1,'Open','2025-03-13 16:12:33','2025-03-13 16:12:33'),(2,'Research Grant 1',1,'ACTIVE','2025-08-26 07:47:14','2025-08-26 07:47:14'),(3,'Research Grant 2',1,'ACTIVE','2025-08-26 07:47:14','2025-08-26 07:47:14'),(4,'Research Grant 3',1,'ACTIVE','2025-08-26 07:47:14','2025-08-26 07:47:14'),(5,'Research Grant 1',1,'ACTIVE','2025-08-26 07:48:04','2025-08-26 07:48:04'),(6,'Research Grant 2',1,'ACTIVE','2025-08-26 07:48:04','2025-08-26 07:48:04'),(7,'Research Grant 3',1,'ACTIVE','2025-08-26 07:48:04','2025-08-26 07:48:04');
/*!40000 ALTER TABLE `grants` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=91 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (55,'2012_03_08_224517_create_userroles_table',1),(56,'2014_10_12_000000_create_users_table',1),(57,'2014_10_12_100000_create_password_resets_table',1),(58,'2019_08_19_000000_create_failed_jobs_table',1),(59,'2019_12_14_000001_create_personal_access_tokens_table',1),(60,'2024_06_25_190842_create_permission_table',1),(61,'2024_06_25_212454_create_userpermissions_table',1),(62,'2024_06_26_084843_create_researchtheme_table',1),(63,'2024_06_27_102838_create_finyears_table',1),(64,'2024_06_27_114040_create_grants_table',1),(65,'2024_06_27_114135_create_schools_table',1),(66,'2024_06_27_114635_create_departments_table',1),(67,'2024_06_27_114636_create_proposals_table',1),(68,'2024_07_01_084303_create_collaborators_table',1),(69,'2024_07_01_084629_create_publications_table',1),(70,'2024_07_01_084658_create_workplan_table',1),(71,'2024_07_01_084731_create_researchdesign_table',1),(72,'2024_07_01_084759_create_expenditures_table',1),(73,'2024_07_01_084759_create_proposalchanges_table',1),(74,'2024_07_16_112641_create_jobs_table',1),(75,'2024_08_07_165952_create_researchproject_table',1),(76,'2024_08_07_170727_create_researchprogress_table',1),(77,'2024_08_28_170727_create_researchfunding_table',1),(78,'2024_08_28_170727_create_supervisionprogress_table',1),(79,'2024_08_29_192838_create_globalsettings_table',1),(80,'2024_09_01_184856_create_notificationtypes_table',1),(81,'2024_09_01_185500_create_notifiableusers_table',1),(82,'2024_12_19_000000_create_user_roles_table',2),(84,'2025_08_24_091203_update_proposal_status_fields_to_enums',3),(85,'2025_08_24_122256_add_draft_status_to_proposals_table',4),(86,'2025_01_27_000000_rename_caneditstatus_to_allowediting',5),(87,'2025_08_24_132952_update_projectstatus_to_enum_in_researchprojects_table',5),(88,'2025_01_28_000000_create_notifications_table',6),(89,'2025_01_28_000001_add_notification_preferences_to_users',7),(90,'2025_01_29_000000_remove_role_from_users_table',8);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notifiableusers`
--

DROP TABLE IF EXISTS `notifiableusers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notifiableusers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `notificationfk` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `useridfk` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifiableusers_notificationfk_foreign` (`notificationfk`),
  KEY `notifiableusers_useridfk_foreign` (`useridfk`),
  CONSTRAINT `notifiableusers_notificationfk_foreign` FOREIGN KEY (`notificationfk`) REFERENCES `notificationtypes` (`typeuuid`) ON DELETE RESTRICT,
  CONSTRAINT `notifiableusers_useridfk_foreign` FOREIGN KEY (`useridfk`) REFERENCES `users` (`userid`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notifiableusers`
--

LOCK TABLES `notifiableusers` WRITE;
/*!40000 ALTER TABLE `notifiableusers` DISABLE KEYS */;
/*!40000 ALTER TABLE `notifiableusers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notifications` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `data` json DEFAULT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifications_user_id_read_at_index` (`user_id`,`read_at`),
  CONSTRAINT `notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`userid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notifications`
--

LOCK TABLES `notifications` WRITE;
/*!40000 ALTER TABLE `notifications` DISABLE KEYS */;
INSERT INTO `notifications` VALUES ('0a813906-24a5-40ae-9cea-5b8ac81448c6','8e793faa-e928-4eca-90ae-e12bbf98ba1b','permissions_changed','Your Permissions Have Been Updated','Your account permissions have been modified. Please review your new access levels.',NULL,NULL,'2025-08-26 07:04:46','2025-08-26 07:04:46'),('0e5d87b1-c888-4753-a089-ac6d341f5b15','7e793faa-e928-4eca-90ae-e12bbf98ba1c','proposal_submitted','New Proposal Submitted','A new research proposal has been submitted for review.',NULL,NULL,'2025-08-24 12:02:33','2025-08-24 12:02:33'),('1c4dae27-7e72-4e28-b64e-557f329ce992','7e793faa-e928-4eca-90ae-e12bbf98ba1c','grant_available','New Grant Available','A new research grant is now available for applications.',NULL,NULL,'2025-08-24 12:02:33','2025-08-24 12:02:33'),('2d15e7fc-c290-4a6d-856c-868dd904a03d','8e793faa-e928-4eca-90ae-e12bbf98ba1b','permissions_changed','Your Permissions Have Been Updated','Your account permissions have been modified. Please review your new access levels.',NULL,NULL,'2025-08-26 07:03:46','2025-08-26 07:03:46'),('4deb5424-2685-4c77-a62e-5e7105cec837','8e793faa-e928-4eca-90ae-e12bbf98ba1b','permissions_changed','Your Permissions Have Been Updated','Your account permissions have been modified. Please review your new access levels.',NULL,NULL,'2025-08-26 07:05:16','2025-08-26 07:05:16'),('5182c9d4-dcd3-4584-91d0-f909454992d7','8e793faa-e928-4eca-90ae-e12bbf98ba1b','project_status_changed','Project Status Updated','Your research project has been activated and is now in progress.',NULL,NULL,'2025-08-24 14:02:36','2025-08-24 14:02:36'),('54294bbc-a518-4026-9775-038aa0fa345a','8e793faa-e928-4eca-90ae-e12bbf98ba1b','permissions_changed','Your Permissions Have Been Updated','Your account permissions have been modified. Please review your new access levels.',NULL,NULL,'2025-08-26 07:08:14','2025-08-26 07:08:14'),('6f8eff3e-4d0c-4bf6-afe6-33b5affa0efd','8e793faa-e928-4eca-90ae-e12bbf98ba1b','permissions_changed','Your Permissions Have Been Updated','Your account permissions have been modified. Please review your new access levels.',NULL,NULL,'2025-08-26 07:08:59','2025-08-26 07:08:59'),('79128b01-b555-4e7c-a6d1-6072b6ea6cc5','7e793faa-e928-4eca-90ae-e12bbf98ba1c','system_announcement','System Maintenance','The system will undergo maintenance on Sunday from 2:00 AM to 4:00 AM.',NULL,NULL,'2025-08-24 12:02:33','2025-08-24 12:02:33'),('8f8ea710-c975-4223-98f0-e88f7677262b','07e1bac9-e8cb-428d-bf33-c52b297a9864','role_changed','Your Role Has Been Updated','Your role has been changed to User. This may affect your access permissions.',NULL,NULL,'2025-08-26 08:38:57','2025-08-26 08:38:57'),('996613f2-11a4-4292-b1a3-4f9677efe3d5','8e793faa-e928-4eca-90ae-e12bbf98ba1b','project_status_changed','Project Status Updated','Your research project has been paused. Please contact the administrator for details.',NULL,NULL,'2025-08-24 13:50:32','2025-08-24 13:50:32'),('9c379b5d-3d7e-4821-ac0a-616d8ffb2788','8e793faa-e928-4eca-90ae-e12bbf98ba1b','permissions_changed','Your Permissions Have Been Updated','Your account permissions have been modified. Please review your new access levels.',NULL,NULL,'2025-08-26 07:11:05','2025-08-26 07:11:05'),('a3a0315f-17e4-4ef2-aca8-b29ea31fadaa','8e793faa-e928-4eca-90ae-e12bbf98ba1b','permissions_changed','Your Permissions Have Been Updated','Your account permissions have been modified. Please review your new access levels.',NULL,NULL,'2025-08-26 07:02:09','2025-08-26 07:02:09'),('abb8ad54-cb6f-49d2-aa44-17aeb9861540','8e793faa-e928-4eca-90ae-e12bbf98ba1b','funding_added','Funding Added to Your Project','Funding of KES 50,000.00 has been added to your research project \'testttttt\'.',NULL,NULL,'2025-08-24 13:27:23','2025-08-24 13:27:23'),('acc063bd-12f2-4002-8d26-be689ced0642','8e793faa-e928-4eca-90ae-e12bbf98ba1b','permissions_changed','Your Permissions Have Been Updated','Your account permissions have been modified. Please review your new access levels.',NULL,NULL,'2025-08-26 07:10:59','2025-08-26 07:10:59'),('bbbe540c-0bbb-40e2-942d-1b59442f3a9c','8e793faa-e928-4eca-90ae-e12bbf98ba1b','funding_added','Funding Added to Your Project','Funding of KES 7.00 has been added to your research project \'testttttt\'.',NULL,NULL,'2025-08-24 13:42:18','2025-08-24 13:42:18'),('c5ff2fa2-e95a-4f3e-9fcb-ca9009985e1f','8e793faa-e928-4eca-90ae-e12bbf98ba1b','permissions_changed','Your Permissions Have Been Updated','Your account permissions have been modified. Please review your new access levels.',NULL,NULL,'2025-08-26 07:03:23','2025-08-26 07:03:23');
/*!40000 ALTER TABLE `notifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notificationtypes`
--

DROP TABLE IF EXISTS `notificationtypes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notificationtypes` (
  `typeuuid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `typename` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifyowner` tinyint(1) NOT NULL DEFAULT '0',
  `description` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`typeuuid`),
  UNIQUE KEY `notificationtypes_typename_unique` (`typename`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notificationtypes`
--

LOCK TABLES `notificationtypes` WRITE;
/*!40000 ALTER TABLE `notificationtypes` DISABLE KEYS */;
/*!40000 ALTER TABLE `notificationtypes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_resets`
--

LOCK TABLES `password_resets` WRITE;
/*!40000 ALTER TABLE `password_resets` DISABLE KEYS */;
INSERT INTO `password_resets` VALUES ('fkiprotich845@gmail.com','$2y$10$ijmBU0Dob.kgwvxJIvXHIuhGIWYBSX3iIOXQJItpdVeKUhiyIVxti','2025-08-24 13:16:57'),('portxyz100@gmail.com','$2y$10$JTbM3to7D6wORkyLqw8KG.SW7.YSmIjAGBBsQPePwtyQQ38pLG.bW','2025-08-24 13:12:03');
/*!40000 ALTER TABLE `password_resets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `permissions` (
  `pid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `menuname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `shortname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `priorityno` int NOT NULL,
  `permissionlevel` int NOT NULL,
  `targetrole` int NOT NULL,
  `issuperadminright` tinyint(1) NOT NULL DEFAULT '0',
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`pid`),
  UNIQUE KEY `permissions_shortname_unique` (`shortname`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permissions`
--

LOCK TABLES `permissions` WRITE;
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
INSERT INTO `permissions` VALUES ('0565496a-0753-46ba-9463-4c17cce8588b','Can Update Current Grant and Financial Year','canupdatecurrentgrantandyear','route.permission',0,2,1,0,NULL,NULL,NULL),('0782080f-2e30-4df7-bdea-7f7fcff70bdf','Can Read Proposal Details','canreadproposaldetails','route.permission',0,2,1,0,NULL,NULL,NULL),('09f0b68d-d401-4c7f-9ef8-10f962399fa5','My Projects','canviewmyprojects','pages.projects.myprojects',12,1,2,0,NULL,NULL,NULL),('1308ce3a-fb1f-41dd-aa52-01cba9a3df41','Can Receive a Proposal','canreceiveproposal','route.permission',0,2,1,0,NULL,NULL,NULL),('174a16d1-bdec-44c7-934a-07598e2c0bbf','Can Change User Role & Rights','canchangeuserroleorrights','route.permission',0,2,1,1,NULL,NULL,NULL),('1b667bca-caf8-4c21-a2a7-c4deab0e93b6','Can Add/Edit Department','canaddoreditdepartment','route.permission',0,2,1,0,NULL,NULL,NULL),('24533248-1e2b-4c9b-935b-234b912c727e','Departments','canviewdepartmentsandschools','pages.departments.home',5,1,1,0,NULL,NULL,NULL),('367dd0ff-c3c7-4864-9457-7f97c52f855b','M & E Links','canviewmonitoringpage','pages.supervision.home',4,1,1,0,NULL,NULL,NULL),('36bdc1a8-4216-4845-8007-52e6e26a917d','Can View NotificationTypes Tab','canviewnotificationtypestab','route.permission',0,2,1,0,NULL,NULL,NULL),('39f38bbe-9f9b-4018-98ec-4170224f33c5','Can View Office Use Tab','canviewofficeuse','route.permission',0,2,1,0,NULL,NULL,NULL),('3d05f398-d4aa-46fa-bee8-72d226a86738','Can Pause Research Project','canpauseresearchproject','route.permission',0,2,1,0,NULL,NULL,NULL),('436c7651-44f8-4c14-959d-d8ab35cb2d54','Can Add Project Funding','canaddprojectfunding','route.permission',0,2,1,0,NULL,NULL,NULL),('46b16b76-4cc8-4e68-96f2-8792087d7a51','Approve Proposal','canapproveproposal','route.permission',0,2,1,0,'test',NULL,NULL),('4e7d80e0-bbfc-457f-b81f-9f0c571c3d6e','Can Add or Edit FinancialYear','canaddoreditfinyear','route.permission',0,2,1,0,NULL,NULL,NULL),('535a7f0e-77f3-443c-a6cf-8bb2cf03f246','Mailing','mailingmodule','pages.mailing.home',9,1,1,1,NULL,NULL,NULL),('5b691787-d267-4f5b-a0fb-d4f1bee30a97','Can Read Any Project','canreadanyproject','route.permission',0,2,1,0,NULL,NULL,NULL),('5f648fb5-66de-464b-8a94-1085ec8ab468','My Applications','canviewmyapplications','pages.proposals.myapplications',11,1,2,0,'test',NULL,NULL),('6fe0ca94-35cb-429e-9d8f-4789b90699af','Can Complete Research Project','cancompleteresearchproject','route.permission',0,2,1,0,NULL,NULL,NULL),('76038eff-cd87-4540-b9c3-835e51ef6e20','Can Cancel Research Project','cancancelresearchproject','route.permission',0,2,1,0,NULL,NULL,NULL),('80cfd5d9-c5a6-45a1-8a07-0828f7961e26','Reject Proposal','canrejectproposal','route.permission',0,2,1,0,'test',NULL,NULL),('8738e24a-4df3-4d42-b20f-0867ead669b4','Can Add/Edit School','canaddoreditschool','route.permission',0,2,1,0,NULL,NULL,NULL),('894daace-4717-47fd-b50c-4bdab931f198','Can View Project Fundings','canviewprojectfunding','route.permission',0,2,1,0,NULL,NULL,NULL),('89d26b77-daad-4da3-9b41-549e9b46e3a0','Research Projects','canviewallprojects','pages.projects.allprojects',2,1,1,0,NULL,NULL,NULL),('8df711ad-9697-43ef-95fe-397b510bb27d','Reports','canviewreports','pages.reports.home',8,1,1,0,'test',NULL,NULL),('a377396f-1d3c-4375-ab5b-fed4adfc912f','All Applications','canviewallapplications','pages.proposals.allproposals',1,1,1,0,'test',NULL,NULL),('a6d51f1e-cf63-4671-8f11-2ef36e2d8882','Can Add or Remove Notifiable User','canaddorremovenotifiableuser','route.permission',0,2,1,0,NULL,NULL,NULL),('a9944b38-039c-44af-8e48-47c2ac4b1374','Can Add/edit Grant','canaddoreditgrant','route.permission',0,2,1,0,NULL,NULL,NULL),('b0734086-3341-11ef-b05b-c8d9d27c3c7e','New Proposal','canmakenewproposal','pages.proposals.viewnewproposal',10,1,2,0,'test',NULL,NULL),('b9a4edbe-7fbb-4050-bbae-20f125bd2234','Can Reset User Password','canresetuserpasswordordisablelogin','route.permission',0,2,1,1,NULL,NULL,NULL),('bf02fa16-9aff-41d1-9c33-cd40c636160f','Dashboard','canviewadmindashboard','pages.dashboard',0,1,1,0,NULL,NULL,NULL),('d0326ee8-209c-45cb-98d9-2c190d3b8fea','Can Assign Monitoring Person','canassignmonitoringperson','route.permission',0,2,1,0,NULL,NULL,NULL),('d20f3fbd-fb04-43ba-a320-6a6a124a0d0b','Users','canviewallusers','pages.users.manage',7,1,1,1,'test',NULL,NULL),('d6e1a65b-0533-415c-992d-cd03637aed4e','Grants & Years','managegrantsandyears','pages.grants.home',6,1,1,0,'test',NULL,NULL),('d980ecd9-ee91-485a-b286-31a76c0bed2a','Can Read My Project','canreadmyproject','route.permission',0,2,2,0,NULL,NULL,NULL),('de2d34fe-0799-42d8-a796-4cb58baad518','Can Propose Changes','canproposechanges','route.permission',0,2,1,0,'test',NULL,NULL),('e96c123d-80a0-4ac4-9433-6ac6f9e7cc91','Can Resume Research Project','canresumeresearchproject','route.permission',0,2,1,0,'',NULL,NULL),('e9faf986-d6af-4a14-a00d-53b423164559','Can Edit User Profile','canedituserprofile','route.permission',0,2,1,1,NULL,NULL,NULL),('eae62e07-3ca4-4293-a12e-494b5f1a4621','Can Enable Proposal Editing','canenabledisableproposaledit','route.permission',0,2,1,0,NULL,NULL,NULL);
/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal_access_tokens`
--

LOCK TABLES `personal_access_tokens` WRITE;
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `proposalchanges`
--

DROP TABLE IF EXISTS `proposalchanges`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `proposalchanges` (
  `changeid` bigint unsigned NOT NULL AUTO_INCREMENT,
  `proposalidfk` bigint unsigned NOT NULL,
  `triggerissue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `suggestedchange` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `suggestedbyfk` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`changeid`),
  KEY `proposalchanges_proposalidfk_foreign` (`proposalidfk`),
  CONSTRAINT `proposalchanges_proposalidfk_foreign` FOREIGN KEY (`proposalidfk`) REFERENCES `proposals` (`proposalid`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `proposalchanges`
--

LOCK TABLES `proposalchanges` WRITE;
/*!40000 ALTER TABLE `proposalchanges` DISABLE KEYS */;
INSERT INTO `proposalchanges` VALUES (1,2,'Review required','test','8e793faa-e928-4eca-90ae-e12bbf98ba1b','Pending','2025-08-24 09:49:31','2025-08-24 09:49:31'),(2,2,'t','t','8e793faa-e928-4eca-90ae-e12bbf98ba1b','Pending','2025-08-24 09:51:58','2025-08-24 09:51:58');
/*!40000 ALTER TABLE `proposalchanges` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `proposals`
--

DROP TABLE IF EXISTS `proposals`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `proposals` (
  `proposalid` bigint unsigned NOT NULL AUTO_INCREMENT,
  `proposalcode` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `grantnofk` int unsigned NOT NULL,
  `departmentidfk` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `useridfk` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pfnofk` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `themefk` int NOT NULL,
  `submittedstatus` enum('PENDING','SUBMITTED') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'PENDING',
  `receivedstatus` enum('PENDING','RECEIVED') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'PENDING',
  `allowediting` tinyint(1) NOT NULL DEFAULT '1',
  `approvalstatus` enum('DRAFT','PENDING','APPROVED','REJECTED') COLLATE utf8mb4_unicode_ci DEFAULT 'PENDING',
  `highqualification` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `officephone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cellphone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `faxnumber` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `researchtitle` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `commencingdate` date DEFAULT NULL,
  `terminationdate` date DEFAULT NULL,
  `objectives` text COLLATE utf8mb4_unicode_ci,
  `hypothesis` text COLLATE utf8mb4_unicode_ci,
  `significance` text COLLATE utf8mb4_unicode_ci,
  `ethicals` text COLLATE utf8mb4_unicode_ci,
  `expoutput` text COLLATE utf8mb4_unicode_ci,
  `socio_impact` text COLLATE utf8mb4_unicode_ci,
  `res_findings` text COLLATE utf8mb4_unicode_ci,
  `comment` text COLLATE utf8mb4_unicode_ci,
  `approvedrejectedbywhofk` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`proposalid`),
  UNIQUE KEY `proposals_proposalcode_unique` (`proposalcode`),
  KEY `proposals_grantnofk_foreign` (`grantnofk`),
  KEY `proposals_useridfk_foreign` (`useridfk`),
  KEY `proposals_pfnofk_foreign` (`pfnofk`),
  KEY `proposals_departmentidfk_foreign` (`departmentidfk`),
  KEY `proposals_themefk_foreign` (`themefk`),
  KEY `proposals_approvedrejectedbywhofk_foreign` (`approvedrejectedbywhofk`),
  CONSTRAINT `proposals_approvedrejectedbywhofk_foreign` FOREIGN KEY (`approvedrejectedbywhofk`) REFERENCES `users` (`userid`) ON DELETE RESTRICT,
  CONSTRAINT `proposals_departmentidfk_foreign` FOREIGN KEY (`departmentidfk`) REFERENCES `departments` (`depid`) ON DELETE RESTRICT,
  CONSTRAINT `proposals_grantnofk_foreign` FOREIGN KEY (`grantnofk`) REFERENCES `grants` (`grantid`) ON DELETE RESTRICT,
  CONSTRAINT `proposals_pfnofk_foreign` FOREIGN KEY (`pfnofk`) REFERENCES `users` (`pfno`) ON DELETE RESTRICT,
  CONSTRAINT `proposals_themefk_foreign` FOREIGN KEY (`themefk`) REFERENCES `researchthemes` (`themeid`) ON DELETE RESTRICT,
  CONSTRAINT `proposals_useridfk_foreign` FOREIGN KEY (`useridfk`) REFERENCES `users` (`userid`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `proposals`
--

LOCK TABLES `proposals` WRITE;
/*!40000 ALTER TABLE `proposals` DISABLE KEYS */;
INSERT INTO `proposals` VALUES (1,'UOK/ARG/P/2025/2025/1',1,'4bef5383-6bd6-4975-9d87-471e6d36ea72','867dfb09-8b4d-4e25-bec3-8f07d0e63a37','1234',1,'PENDING','PENDING',0,'PENDING','PhD','0712345678','0712345678','0712345678','Test Title','2025-03-13','2025-04-05','Objectives','Hypothesis','Significance','Ethics','outputs','socio economic','findings','',NULL,'2025-03-13 16:30:23','2025-03-13 17:35:50'),(2,'UOK/ARG/P/2025/2025/2',1,'22002eac-1935-4217-83ca-332dabcbf4fe','8e793faa-e928-4eca-90ae-e12bbf98ba1b','12345',8,'PENDING','PENDING',0,'APPROVED','Masters','0712345678','0712345678','0712345678','testttttt','2025-08-23','2025-09-06','test','test','test','test','test','test','test','t',NULL,'2025-08-23 08:56:11','2025-08-24 09:54:33'),(3,'PROP0001',1,'22002eac-1935-4217-83ca-332dabcbf4fe','07e1bac9-e8cb-428d-bf33-c52b297a9864','PF0001',9,'PENDING','RECEIVED',1,'DRAFT','PhD','0202000001','0712000001','0202000100','Machine Learning Applications in Agriculture','2025-09-25','2026-08-26','Research objectives for Machine Learning Applications in Agriculture','Research hypothesis for Machine Learning Applications in Agriculture','Research significance for Machine Learning Applications in Agriculture',NULL,NULL,NULL,NULL,NULL,NULL,'2025-08-11 08:15:51','2025-08-26 08:15:51'),(4,'PROP0002',2,'22002eac-1935-4217-83ca-332dabcbf4fe','3bcd7cb5-30ac-476d-bb68-38aafe924316','PF0003',3,'SUBMITTED','RECEIVED',1,'PENDING','PhD','0202000002','0712000003','0202000101','Sustainable Energy Solutions for Rural Communities','2025-09-25','2026-08-26','Research objectives for Sustainable Energy Solutions for Rural Communities','Research hypothesis for Sustainable Energy Solutions for Rural Communities','Research significance for Sustainable Energy Solutions for Rural Communities',NULL,NULL,NULL,NULL,NULL,NULL,'2025-07-30 08:15:51','2025-08-26 08:15:51'),(5,'PROP0003',3,'22002eac-1935-4217-83ca-332dabcbf4fe','4dd3befb-cd8c-4811-9ad0-6f6074a5cdea','PF0010',2,'PENDING','RECEIVED',1,'APPROVED','PhD','0202000003','0712000010','0202000102','Digital Learning Platforms for Primary Education','2025-09-25','2026-08-26','Research objectives for Digital Learning Platforms for Primary Education','Research hypothesis for Digital Learning Platforms for Primary Education','Research significance for Digital Learning Platforms for Primary Education',NULL,NULL,NULL,NULL,NULL,NULL,'2025-08-06 08:15:51','2025-08-26 08:15:51'),(6,'PROP0004',4,'22002eac-1935-4217-83ca-332dabcbf4fe','50716434-974f-4bdd-9768-77c048dd1a4d','PF0004',5,'SUBMITTED','PENDING',1,'DRAFT','PhD','0202000004','0712000004','0202000103','Microfinance Impact on Small Business Growth','2025-09-25','2026-08-26','Research objectives for Microfinance Impact on Small Business Growth','Research hypothesis for Microfinance Impact on Small Business Growth','Research significance for Microfinance Impact on Small Business Growth',NULL,NULL,NULL,NULL,NULL,NULL,'2025-07-31 08:15:51','2025-08-26 08:15:51'),(7,'PROP0005',5,'22002eac-1935-4217-83ca-332dabcbf4fe','7520c81d-ba86-419c-b1de-7e1eae3cb67b','PF0006',2,'PENDING','PENDING',1,'PENDING','PhD','0202000005','0712000006','0202000104','Climate Change Adaptation Strategies','2025-09-25','2026-08-26','Research objectives for Climate Change Adaptation Strategies','Research hypothesis for Climate Change Adaptation Strategies','Research significance for Climate Change Adaptation Strategies',NULL,NULL,NULL,NULL,NULL,NULL,'2025-08-22 08:15:51','2025-08-26 08:15:51'),(9,'PROP0008',1,'22002eac-1935-4217-83ca-332dabcbf4fe','07e1bac9-e8cb-428d-bf33-c52b297a9864','PF0001',4,'PENDING','RECEIVED',1,'DRAFT','PhD','0202000001','0712000001','0202000100','Machine Learning Applications in Agriculture','2025-09-25','2026-08-26','Research objectives for Machine Learning Applications in Agriculture','Research hypothesis for Machine Learning Applications in Agriculture','Research significance for Machine Learning Applications in Agriculture',NULL,NULL,NULL,NULL,NULL,NULL,'2025-08-10 08:17:58','2025-08-26 08:17:58'),(10,'PROP0009',2,'22002eac-1935-4217-83ca-332dabcbf4fe','3bcd7cb5-30ac-476d-bb68-38aafe924316','PF0003',5,'SUBMITTED','RECEIVED',1,'PENDING','PhD','0202000002','0712000003','0202000101','Sustainable Energy Solutions for Rural Communities','2025-09-25','2026-08-26','Research objectives for Sustainable Energy Solutions for Rural Communities','Research hypothesis for Sustainable Energy Solutions for Rural Communities','Research significance for Sustainable Energy Solutions for Rural Communities',NULL,NULL,NULL,NULL,NULL,NULL,'2025-08-03 08:17:58','2025-08-26 08:17:58'),(11,'PROP0010',3,'22002eac-1935-4217-83ca-332dabcbf4fe','4dd3befb-cd8c-4811-9ad0-6f6074a5cdea','PF0010',7,'PENDING','RECEIVED',1,'APPROVED','PhD','0202000003','0712000010','0202000102','Digital Learning Platforms for Primary Education','2025-09-25','2026-08-26','Research objectives for Digital Learning Platforms for Primary Education','Research hypothesis for Digital Learning Platforms for Primary Education','Research significance for Digital Learning Platforms for Primary Education',NULL,NULL,NULL,NULL,NULL,NULL,'2025-08-16 08:17:58','2025-08-26 08:17:58'),(12,'PROP0011',4,'22002eac-1935-4217-83ca-332dabcbf4fe','50716434-974f-4bdd-9768-77c048dd1a4d','PF0004',8,'SUBMITTED','PENDING',1,'DRAFT','PhD','0202000004','0712000004','0202000103','Microfinance Impact on Small Business Growth','2025-09-25','2026-08-26','Research objectives for Microfinance Impact on Small Business Growth','Research hypothesis for Microfinance Impact on Small Business Growth','Research significance for Microfinance Impact on Small Business Growth',NULL,NULL,NULL,NULL,NULL,NULL,'2025-08-23 08:17:58','2025-08-26 08:17:58'),(13,'PROP0012',5,'22002eac-1935-4217-83ca-332dabcbf4fe','7520c81d-ba86-419c-b1de-7e1eae3cb67b','PF0006',3,'PENDING','PENDING',1,'PENDING','PhD','0202000005','0712000006','0202000104','Climate Change Adaptation Strategies','2025-09-25','2026-08-26','Research objectives for Climate Change Adaptation Strategies','Research hypothesis for Climate Change Adaptation Strategies','Research significance for Climate Change Adaptation Strategies',NULL,NULL,NULL,NULL,NULL,NULL,'2025-08-02 08:17:58','2025-08-26 08:17:58'),(14,'PROP0013',6,'22002eac-1935-4217-83ca-332dabcbf4fe','7b70fe23-30c0-4db6-a34e-d4dc44aa6769','PF0002',3,'SUBMITTED','PENDING',1,'APPROVED','PhD','0202000006','0712000002','0202000105','Mobile Health Applications for Maternal Care','2025-09-25','2026-08-26','Research objectives for Mobile Health Applications for Maternal Care','Research hypothesis for Mobile Health Applications for Maternal Care','Research significance for Mobile Health Applications for Maternal Care',NULL,NULL,NULL,NULL,NULL,NULL,'2025-08-01 08:17:58','2025-08-26 08:17:58'),(15,'PROP0014',7,'22002eac-1935-4217-83ca-332dabcbf4fe','7e793faa-e928-4eca-90ae-e12bbf98ba1c','123',4,'PENDING','PENDING',1,'DRAFT','PhD','0202000007','07123','0202000106','Blockchain Technology in Supply Chain Management','2025-09-25','2026-08-26','Research objectives for Blockchain Technology in Supply Chain Management','Research hypothesis for Blockchain Technology in Supply Chain Management','Research significance for Blockchain Technology in Supply Chain Management',NULL,NULL,NULL,NULL,NULL,NULL,'2025-08-11 08:17:58','2025-08-26 08:17:58'),(16,'PROP0015',1,'22002eac-1935-4217-83ca-332dabcbf4fe','7ea82430-47e7-4ead-9865-ec95fcfe9184','PF0007',10,'SUBMITTED','PENDING',1,'PENDING','PhD','0202000008','0712000007','0202000107','Educational Assessment Using AI','2025-09-25','2026-08-26','Research objectives for Educational Assessment Using AI','Research hypothesis for Educational Assessment Using AI','Research significance for Educational Assessment Using AI',NULL,NULL,NULL,NULL,NULL,NULL,'2025-08-01 08:17:58','2025-08-26 08:17:58'),(17,'PROP0016',2,'22002eac-1935-4217-83ca-332dabcbf4fe','80fb2d65-9d4e-41a2-8dc6-d6cd60335f85','PF0005',12,'PENDING','PENDING',1,'APPROVED','PhD','0202000009','0712000005','0202000108','Renewable Energy Storage Systems','2025-09-25','2026-08-26','Research objectives for Renewable Energy Storage Systems','Research hypothesis for Renewable Energy Storage Systems','Research significance for Renewable Energy Storage Systems',NULL,NULL,NULL,NULL,NULL,NULL,'2025-08-10 08:17:58','2025-08-26 08:17:58'),(18,'PROP0017',3,'22002eac-1935-4217-83ca-332dabcbf4fe','867dfb09-8b4d-4e25-bec3-8f07d0e63a37','1234',13,'SUBMITTED','PENDING',1,'DRAFT','PhD','0202000010','07888','0202000109','Community-Based Tourism Development','2025-09-25','2026-08-26','Research objectives for Community-Based Tourism Development','Research hypothesis for Community-Based Tourism Development','Research significance for Community-Based Tourism Development',NULL,NULL,NULL,NULL,NULL,NULL,'2025-08-14 08:17:58','2025-08-26 08:17:58');
/*!40000 ALTER TABLE `proposals` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `publications`
--

DROP TABLE IF EXISTS `publications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `publications` (
  `publicationid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `proposalidfk` bigint unsigned NOT NULL,
  `authors` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `year` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `researcharea` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `publisher` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `volume` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pages` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`publicationid`),
  KEY `publications_proposalidfk_foreign` (`proposalidfk`),
  CONSTRAINT `publications_proposalidfk_foreign` FOREIGN KEY (`proposalidfk`) REFERENCES `proposals` (`proposalid`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `publications`
--

LOCK TABLES `publications` WRITE;
/*!40000 ALTER TABLE `publications` DISABLE KEYS */;
INSERT INTO `publications` VALUES ('247ccd32-bc5d-4f2d-ae2a-bef707bdec59',2,'knoph','2026','knoph','knoph','knoph','t',33,'2025-08-23 17:04:24','2025-08-23 17:04:24'),('4a02e324-a0d8-4d58-9def-bbea9d620113',2,'publicatrion','2026','publicatrion','Journal Article','publicatrion','N/A',0,'2025-08-23 16:51:25','2025-08-23 16:51:25');
/*!40000 ALTER TABLE `publications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `researchdesigns`
--

DROP TABLE IF EXISTS `researchdesigns`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `researchdesigns` (
  `designid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `proposalidfk` bigint unsigned NOT NULL,
  `summary` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `indicators` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `verification` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `assumptions` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `goal` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `purpose` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`designid`),
  KEY `researchdesigns_proposalidfk_foreign` (`proposalidfk`),
  CONSTRAINT `researchdesigns_proposalidfk_foreign` FOREIGN KEY (`proposalidfk`) REFERENCES `proposals` (`proposalid`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `researchdesigns`
--

LOCK TABLES `researchdesigns` WRITE;
/*!40000 ALTER TABLE `researchdesigns` DISABLE KEYS */;
INSERT INTO `researchdesigns` VALUES ('d29d276a-2813-48b7-ac79-649d93e6c49d',1,'Summary','indicators','verification','assumptions','goal','purpose','2025-03-13 16:55:24','2025-03-13 16:55:24'),('d9659d40-b150-4904-a575-7f4b2e81decc',2,'activity','activity','activity','activity','activity','activity','2025-08-23 17:08:30','2025-08-23 17:08:30');
/*!40000 ALTER TABLE `researchdesigns` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `researchfundings`
--

DROP TABLE IF EXISTS `researchfundings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `researchfundings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `researchidfk` bigint unsigned NOT NULL,
  `createdby` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` double NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `researchfundings_researchidfk_foreign` (`researchidfk`),
  KEY `researchfundings_createdby_foreign` (`createdby`),
  CONSTRAINT `researchfundings_createdby_foreign` FOREIGN KEY (`createdby`) REFERENCES `users` (`userid`) ON DELETE RESTRICT,
  CONSTRAINT `researchfundings_researchidfk_foreign` FOREIGN KEY (`researchidfk`) REFERENCES `researchprojects` (`researchid`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `researchfundings`
--

LOCK TABLES `researchfundings` WRITE;
/*!40000 ALTER TABLE `researchfundings` DISABLE KEYS */;
INSERT INTO `researchfundings` VALUES (12,2,'867dfb09-8b4d-4e25-bec3-8f07d0e63a37',7,'2025-08-24 13:42:18','2025-08-24 13:42:18');
/*!40000 ALTER TABLE `researchfundings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `researchprogress`
--

DROP TABLE IF EXISTS `researchprogress`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `researchprogress` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `researchidfk` bigint unsigned NOT NULL,
  `reportedbyfk` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `report` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `researchprogress_researchidfk_foreign` (`researchidfk`),
  KEY `researchprogress_reportedbyfk_foreign` (`reportedbyfk`),
  CONSTRAINT `researchprogress_reportedbyfk_foreign` FOREIGN KEY (`reportedbyfk`) REFERENCES `users` (`userid`) ON DELETE RESTRICT,
  CONSTRAINT `researchprogress_researchidfk_foreign` FOREIGN KEY (`researchidfk`) REFERENCES `researchprojects` (`researchid`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `researchprogress`
--

LOCK TABLES `researchprogress` WRITE;
/*!40000 ALTER TABLE `researchprogress` DISABLE KEYS */;
INSERT INTO `researchprogress` VALUES (1,2,'8e793faa-e928-4eca-90ae-e12bbf98ba1b','progress 1','2025-08-24 10:27:11','2025-08-24 10:27:11'),(2,2,'8e793faa-e928-4eca-90ae-e12bbf98ba1b','progress 2','2025-08-24 10:27:22','2025-08-24 10:27:22');
/*!40000 ALTER TABLE `researchprogress` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `researchprojects`
--

DROP TABLE IF EXISTS `researchprojects`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `researchprojects` (
  `researchid` bigint unsigned NOT NULL AUTO_INCREMENT,
  `researchnumber` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `proposalidfk` bigint unsigned NOT NULL,
  `projectstatus` enum('ACTIVE','PAUSED','CANCELLED','COMPLETED') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'ACTIVE',
  `ispaused` tinyint(1) NOT NULL DEFAULT '0',
  `supervisorfk` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fundingfinyearfk` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`researchid`),
  UNIQUE KEY `researchprojects_researchnumber_unique` (`researchnumber`),
  UNIQUE KEY `researchprojects_proposalidfk_unique` (`proposalidfk`),
  KEY `researchprojects_fundingfinyearfk_foreign` (`fundingfinyearfk`),
  KEY `researchprojects_supervisorfk_foreign` (`supervisorfk`),
  CONSTRAINT `researchprojects_fundingfinyearfk_foreign` FOREIGN KEY (`fundingfinyearfk`) REFERENCES `finyears` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `researchprojects_proposalidfk_foreign` FOREIGN KEY (`proposalidfk`) REFERENCES `proposals` (`proposalid`) ON DELETE RESTRICT,
  CONSTRAINT `researchprojects_supervisorfk_foreign` FOREIGN KEY (`supervisorfk`) REFERENCES `users` (`userid`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `researchprojects`
--

LOCK TABLES `researchprojects` WRITE;
/*!40000 ALTER TABLE `researchprojects` DISABLE KEYS */;
INSERT INTO `researchprojects` VALUES (2,'UOK/ARG/2024/2025/1',2,'ACTIVE',0,NULL,1,'2025-08-24 09:54:33','2025-08-24 14:02:36'),(3,'RP-2025-0005',5,'ACTIVE',0,'4dd3befb-cd8c-4811-9ad0-6f6074a5cdea',1,'2025-08-26 08:23:08','2025-08-26 08:23:08'),(4,'RP-2025-0011',11,'ACTIVE',0,'4dd3befb-cd8c-4811-9ad0-6f6074a5cdea',1,'2025-08-26 08:23:08','2025-08-26 08:23:08'),(5,'RP-2025-0014',14,'ACTIVE',0,'7b70fe23-30c0-4db6-a34e-d4dc44aa6769',1,'2025-08-26 08:23:08','2025-08-26 08:23:08'),(6,'RP-2025-0017',17,'ACTIVE',0,'80fb2d65-9d4e-41a2-8dc6-d6cd60335f85',1,'2025-08-26 08:23:08','2025-08-26 08:23:08');
/*!40000 ALTER TABLE `researchprojects` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `researchthemes`
--

DROP TABLE IF EXISTS `researchthemes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `researchthemes` (
  `themeid` int NOT NULL,
  `themename` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `themedescription` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `applicablestatus` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`themeid`),
  UNIQUE KEY `researchthemes_themename_unique` (`themename`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `researchthemes`
--

LOCK TABLES `researchthemes` WRITE;
/*!40000 ALTER TABLE `researchthemes` DISABLE KEYS */;
INSERT INTO `researchthemes` VALUES (1,'Food Security','Food Security','open',NULL,NULL),(2,'Natural Resources','Natural Resources','open',NULL,NULL),(3,'Health & Nutrition','Health & Nutrition','open',NULL,NULL),(4,'Environmental Conservation','Environmental Conservation','open',NULL,NULL),(5,'Community Development','Community Development','open',NULL,NULL),(6,'Gender','Gender','open',NULL,NULL),(7,'Education','Education','open',NULL,NULL),(8,'Human Resource Development','Human Resource Development','open',NULL,NULL),(9,'Socio-Cultural Issues','Socio-Cultural Issues','open',NULL,NULL),(10,'Entrepreneurship','Entrepreneurship','open',NULL,NULL),(11,'Legal Issues','Legal Issues','open',NULL,NULL),(12,'Natural Sciences','Natural Sciences','open',NULL,NULL),(13,'Others (Specify)','Others (Specify)','open',NULL,NULL);
/*!40000 ALTER TABLE `researchthemes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `schools`
--

DROP TABLE IF EXISTS `schools`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `schools` (
  `schoolid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `schoolname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`schoolid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `schools`
--

LOCK TABLES `schools` WRITE;
/*!40000 ALTER TABLE `schools` DISABLE KEYS */;
INSERT INTO `schools` VALUES ('3b1f1976-a870-48fd-bcac-785f14b2c223','School of Education','Education and Arts','2025-08-26 07:43:56','2025-08-26 07:43:56'),('7c505a7d-846d-4c07-af40-88994bf81a3b','School of Business','Business and Economics','2025-08-26 07:43:56','2025-08-26 07:43:56'),('89abb553-0375-450d-8505-f00c80b09117','school 2','test','2025-08-23 08:34:05','2025-08-23 08:34:05'),('e801c73e-7bc8-4026-bc2c-e54f34c8fc0c','School of Science','Science and Technology','2025-08-26 07:43:56','2025-08-26 07:43:56'),('f8f5ed94-f0db-475f-90e7-f40c3383872f','school 1','school 1','2025-03-13 16:05:03','2025-03-13 16:05:03');
/*!40000 ALTER TABLE `schools` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `supervisionprogress`
--

DROP TABLE IF EXISTS `supervisionprogress`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `supervisionprogress` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `researchidfk` bigint unsigned NOT NULL,
  `supervisorfk` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `report` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `remark` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `supervisionprogress_researchidfk_foreign` (`researchidfk`),
  KEY `supervisionprogress_supervisorfk_foreign` (`supervisorfk`),
  CONSTRAINT `supervisionprogress_researchidfk_foreign` FOREIGN KEY (`researchidfk`) REFERENCES `researchprojects` (`researchid`) ON DELETE RESTRICT,
  CONSTRAINT `supervisionprogress_supervisorfk_foreign` FOREIGN KEY (`supervisorfk`) REFERENCES `users` (`userid`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `supervisionprogress`
--

LOCK TABLES `supervisionprogress` WRITE;
/*!40000 ALTER TABLE `supervisionprogress` DISABLE KEYS */;
/*!40000 ALTER TABLE `supervisionprogress` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_roles`
--

DROP TABLE IF EXISTS `user_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_roles` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_roles_user_id_is_active_index` (`user_id`,`is_active`),
  CONSTRAINT `user_roles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_roles`
--

LOCK TABLES `user_roles` WRITE;
/*!40000 ALTER TABLE `user_roles` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `userpermissions`
--

DROP TABLE IF EXISTS `userpermissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `userpermissions` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `useridfk` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `permissionidfk` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `userpermissions_useridfk_foreign` (`useridfk`),
  KEY `userpermissions_permissionidfk_foreign` (`permissionidfk`),
  CONSTRAINT `userpermissions_permissionidfk_foreign` FOREIGN KEY (`permissionidfk`) REFERENCES `permissions` (`pid`) ON DELETE RESTRICT,
  CONSTRAINT `userpermissions_useridfk_foreign` FOREIGN KEY (`useridfk`) REFERENCES `users` (`userid`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `userpermissions`
--

LOCK TABLES `userpermissions` WRITE;
/*!40000 ALTER TABLE `userpermissions` DISABLE KEYS */;
INSERT INTO `userpermissions` VALUES ('43e08424-1aac-4a14-85a9-f2bc87b1c67d','8e793faa-e928-4eca-90ae-e12bbf98ba1b','de2d34fe-0799-42d8-a796-4cb58baad518','2025-08-26 07:41:25','2025-08-26 07:41:25'),('6bd2ce25-8939-497c-9530-87d70ecfcb0a','867dfb09-8b4d-4e25-bec3-8f07d0e63a37','09f0b68d-d401-4c7f-9ef8-10f962399fa5',NULL,NULL),('798bfd27-d987-43f5-add3-027bfd2f63de','8e793faa-e928-4eca-90ae-e12bbf98ba1b','b0734086-3341-11ef-b05b-c8d9d27c3c7e','2025-08-26 07:41:25','2025-08-26 07:41:25'),('a21b7bc8-8f7a-4e30-830b-df9dc0a6e654','867dfb09-8b4d-4e25-bec3-8f07d0e63a37','5f648fb5-66de-464b-8a94-1085ec8ab468',NULL,NULL),('b7b27f1d-ccf4-4b7c-b63f-7e6292f6088e','867dfb09-8b4d-4e25-bec3-8f07d0e63a37','d980ecd9-ee91-485a-b286-31a76c0bed2a',NULL,NULL),('d0cf2c5b-0436-4a40-aa6f-a83ccc39ee96','867dfb09-8b4d-4e25-bec3-8f07d0e63a37','b0734086-3341-11ef-b05b-c8d9d27c3c7e',NULL,NULL);
/*!40000 ALTER TABLE `userpermissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `userroles`
--

DROP TABLE IF EXISTS `userroles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `userroles` (
  `roleid` bigint unsigned NOT NULL AUTO_INCREMENT,
  `codename` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `shortname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`roleid`),
  UNIQUE KEY `userroles_codename_unique` (`codename`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `userroles`
--

LOCK TABLES `userroles` WRITE;
/*!40000 ALTER TABLE `userroles` DISABLE KEYS */;
INSERT INTO `userroles` VALUES (1,'committe','Committe','Committe',NULL,NULL),(2,'researcher','Researcher','Researcher',NULL,NULL),(3,'guest','Guest','Guest',NULL,NULL);
/*!40000 ALTER TABLE `userroles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `userid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pfno` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phonenumber` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `gender` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `isadmin` tinyint(1) NOT NULL DEFAULT '0',
  `isactive` tinyint(1) NOT NULL DEFAULT '1',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `email_notifications` tinyint(1) NOT NULL DEFAULT '1',
  `inapp_notifications` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`userid`),
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `users_pfno_unique` (`pfno`),
  UNIQUE KEY `users_phonenumber_unique` (`phonenumber`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES ('07e1bac9-e8cb-428d-bf33-c52b297a9864','Dr. John Kamau','user1@kabianga.ac.ke','PF0001','0712000001',NULL,0,1,NULL,'$2y$10$J1gXcKKvm86zP0F9g5zsCO7XkCzSt9Ns9JrEjCVDO1IokycOCX9J2',NULL,'2025-08-26 07:44:26','2025-08-26 08:38:57',1,1),('3bcd7cb5-30ac-476d-bb68-38aafe924316','Dr. Peter Ochieng','user3@kabianga.ac.ke','PF0003','0712000003',NULL,0,1,NULL,'$2y$10$Su0M3CgGoQYp42BfJsGyi.8GK5LT0R2wILMPtiHsH.hjMWlSfbmiK',NULL,'2025-08-26 07:44:26','2025-08-26 07:44:26',1,1),('4dd3befb-cd8c-4811-9ad0-6f6074a5cdea','Dr. Lucy Wanjiru','user10@kabianga.ac.ke','PF0010','0712000010',NULL,0,1,NULL,'$2y$10$pArDfKDe2zTWrFemGidg6.FhJJWb6ohHC7O0P7zgG0IxO4ctElOOm',NULL,'2025-08-26 07:44:27','2025-08-26 07:44:27',1,1),('50716434-974f-4bdd-9768-77c048dd1a4d','Dr. Grace Muthoni','user4@kabianga.ac.ke','PF0004','0712000004',NULL,0,1,NULL,'$2y$10$X3l53QOFKwZC53jq.UDxRO4aOTd.XUJggamElXsjEFn8b78rV2UJa',NULL,'2025-08-26 07:44:26','2025-08-26 07:44:26',1,1),('7520c81d-ba86-419c-b1de-7e1eae3cb67b','Dr. Sarah Akinyi','user6@kabianga.ac.ke','PF0006','0712000006',NULL,0,1,NULL,'$2y$10$F6yOnR2SMrhzrfF/4pI3g.CeHdHSVabSjgvXg4qbxd3nKq9blJy/G',NULL,'2025-08-26 07:44:27','2025-08-26 07:44:27',1,1),('7b70fe23-30c0-4db6-a34e-d4dc44aa6769','Prof. Mary Wanjiku','user2@kabianga.ac.ke','PF0002','0712000002',NULL,0,1,NULL,'$2y$10$vg4P.pPXvjm3AjqLzCl/4OQeCTlGcpxWgkxWJae9a3mvl/qzFZm2K',NULL,'2025-08-26 07:44:26','2025-08-26 07:44:26',1,1),('7e793faa-e928-4eca-90ae-e12bbf98ba1c','Test and Test','felix@laxco.co.ke','123','07123',NULL,1,1,'2025-03-08 20:20:46','$2y$10$bXy49/Zix7sWPO/TbRaHaOVjCoDYIdjxKR0uofWjCs6rgQecZM84S',NULL,'2025-03-08 20:08:10','2025-08-23 05:43:17',1,1),('7ea82430-47e7-4ead-9865-ec95fcfe9184','Dr. Michael Wekesa','user7@kabianga.ac.ke','PF0007','0712000007',NULL,0,1,NULL,'$2y$10$T6U2Js8JDC3Z5udvjOTBMOOYmNRKd1C1GaV0rCRYrF1olgvEnJ.BC',NULL,'2025-08-26 07:44:27','2025-08-26 07:44:27',1,1),('80fb2d65-9d4e-41a2-8dc6-d6cd60335f85','Prof. David Kiprop','user5@kabianga.ac.ke','PF0005','0712000005',NULL,0,1,NULL,'$2y$10$S/STo.KSUQ6fb8l9WtEazupSN5T3MzUwP2OTkMdJZBuv8VsgtfMGu',NULL,'2025-08-26 07:44:27','2025-08-26 07:44:27',1,1),('867dfb09-8b4d-4e25-bec3-8f07d0e63a37','john dev','portxyz100@gmail.com','1234','07888',NULL,1,1,'2025-03-08 21:15:36','$2y$10$l0xgrHsI2SaYk.UcsqKI5.Pce1nRwdgG0Mpb2xq57wvG.BhQzror2',NULL,'2025-03-08 21:11:26','2025-08-23 06:13:15',1,1),('8e793faa-e928-4eca-90ae-e12bbf98ba1b','Felix Kiprotich','fkiprotich845@gmail.com','12345','071234',NULL,0,0,'2025-03-08 20:20:46','$2y$10$bXy49/Zix7sWPO/TbRaHaOVjCoDYIdjxKR0uofWjCs6rgQecZM84S',NULL,'2025-03-08 20:08:10','2025-03-08 21:08:01',1,1),('c7055f97-6da1-4214-98cf-466e37c16085','Dr. Jane Nyambura','user8@kabianga.ac.ke','PF0008','0712000008',NULL,0,1,NULL,'$2y$10$3whTu2As56InvCHyIXovT.12bbNAWyHA5fKcXpkc59EJcFGpfIPye',NULL,'2025-08-26 07:44:27','2025-08-26 07:44:27',1,1),('d1cb851d-e7f6-41cb-9093-96ed48931e7f','Prof. Samuel Kiprotich','user9@kabianga.ac.ke','PF0009','0712000009',NULL,0,1,NULL,'$2y$10$4qNHjATUDwMUOXL0Q76K7uET842Zwv6gVj1HayT5EH1pLUqMEKe4m',NULL,'2025-08-26 07:44:27','2025-08-26 07:44:27',1,1);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `workplans`
--

DROP TABLE IF EXISTS `workplans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `workplans` (
  `workplanid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `proposalidfk` bigint unsigned NOT NULL,
  `activity` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `time` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `input` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `facilities` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `bywhom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `outcome` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`workplanid`),
  KEY `workplans_proposalidfk_foreign` (`proposalidfk`),
  CONSTRAINT `workplans_proposalidfk_foreign` FOREIGN KEY (`proposalidfk`) REFERENCES `proposals` (`proposalid`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `workplans`
--

LOCK TABLES `workplans` WRITE;
/*!40000 ALTER TABLE `workplans` DISABLE KEYS */;
INSERT INTO `workplans` VALUES ('18dd0ea1-29fa-4927-bd4c-1515dbe710c3',1,'activity','time','input','facilities','by whom','outcome','2025-03-13 16:55:52','2025-03-13 16:55:52'),('e6dde9a8-9997-441a-b8a5-d6a7c98884eb',2,'activity','activity','activity','activity','activity','activity','2025-08-23 17:08:14','2025-08-23 17:08:14');
/*!40000 ALTER TABLE `workplans` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-08-26 15:05:10
