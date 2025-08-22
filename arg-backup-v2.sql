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
INSERT INTO `departments` VALUES ('4bef5383-6bd6-4975-9d87-471e6d36ea72','f8f5ed94-f0db-475f-90e7-f40c3383872f','Department 1','Department 1','2025-03-13 16:05:36','2025-03-13 16:05:36');
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
INSERT INTO `expenditures` VALUES ('211bcda6-d97f-4ac9-8401-09050405dd2f',1,'Initial Cost','Facilities',1,1000.00,1000.00,'2025-03-13 16:54:57','2025-03-13 16:54:57');
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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
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
INSERT INTO `globalsettings` VALUES (1,'current_open_grant','1',NULL,NULL,NULL),(2,'current_fin_year','1',NULL,NULL,NULL);
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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `grants`
--

LOCK TABLES `grants` WRITE;
/*!40000 ALTER TABLE `grants` DISABLE KEYS */;
INSERT INTO `grants` VALUES (1,'2024-25 Grant',1,'Open','2025-03-13 16:12:33','2025-03-13 16:12:33');
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
) ENGINE=InnoDB AUTO_INCREMENT=82 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (55,'2012_03_08_224517_create_userroles_table',1),(56,'2014_10_12_000000_create_users_table',1),(57,'2014_10_12_100000_create_password_resets_table',1),(58,'2019_08_19_000000_create_failed_jobs_table',1),(59,'2019_12_14_000001_create_personal_access_tokens_table',1),(60,'2024_06_25_190842_create_permission_table',1),(61,'2024_06_25_212454_create_userpermissions_table',1),(62,'2024_06_26_084843_create_researchtheme_table',1),(63,'2024_06_27_102838_create_finyears_table',1),(64,'2024_06_27_114040_create_grants_table',1),(65,'2024_06_27_114135_create_schools_table',1),(66,'2024_06_27_114635_create_departments_table',1),(67,'2024_06_27_114636_create_proposals_table',1),(68,'2024_07_01_084303_create_collaborators_table',1),(69,'2024_07_01_084629_create_publications_table',1),(70,'2024_07_01_084658_create_workplan_table',1),(71,'2024_07_01_084731_create_researchdesign_table',1),(72,'2024_07_01_084759_create_expenditures_table',1),(73,'2024_07_01_084759_create_proposalchanges_table',1),(74,'2024_07_16_112641_create_jobs_table',1),(75,'2024_08_07_165952_create_researchproject_table',1),(76,'2024_08_07_170727_create_researchprogress_table',1),(77,'2024_08_28_170727_create_researchfunding_table',1),(78,'2024_08_28_170727_create_supervisionprogress_table',1),(79,'2024_08_29_192838_create_globalsettings_table',1),(80,'2024_09_01_184856_create_notificationtypes_table',1),(81,'2024_09_01_185500_create_notifiableusers_table',1);
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `proposalchanges`
--

LOCK TABLES `proposalchanges` WRITE;
/*!40000 ALTER TABLE `proposalchanges` DISABLE KEYS */;
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
  `submittedstatus` tinyint(1) NOT NULL DEFAULT '0',
  `receivedstatus` tinyint(1) NOT NULL DEFAULT '0',
  `caneditstatus` tinyint(1) NOT NULL DEFAULT '1',
  `approvalstatus` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Pending',
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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `proposals`
--

LOCK TABLES `proposals` WRITE;
/*!40000 ALTER TABLE `proposals` DISABLE KEYS */;
INSERT INTO `proposals` VALUES (1,'UOK/ARG/P/2025/2025/1',1,'4bef5383-6bd6-4975-9d87-471e6d36ea72','867dfb09-8b4d-4e25-bec3-8f07d0e63a37','1234',1,1,1,0,'Pending','PhD','0712345678','0712345678','0712345678','Test Title','2025-03-13','2025-04-05','Objectives','Hypothesis','Significance','Ethics','outputs','socio economic','findings','',NULL,'2025-03-13 16:30:23','2025-03-13 17:35:50');
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
INSERT INTO `researchdesigns` VALUES ('d29d276a-2813-48b7-ac79-649d93e6c49d',1,'Summary','indicators','verification','assumptions','goal','purpose','2025-03-13 16:55:24','2025-03-13 16:55:24');
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `researchfundings`
--

LOCK TABLES `researchfundings` WRITE;
/*!40000 ALTER TABLE `researchfundings` DISABLE KEYS */;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `researchprogress`
--

LOCK TABLES `researchprogress` WRITE;
/*!40000 ALTER TABLE `researchprogress` DISABLE KEYS */;
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
  `projectstatus` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `researchprojects`
--

LOCK TABLES `researchprojects` WRITE;
/*!40000 ALTER TABLE `researchprojects` DISABLE KEYS */;
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
INSERT INTO `schools` VALUES ('f8f5ed94-f0db-475f-90e7-f40c3383872f','school 1','school 1','2025-03-13 16:05:03','2025-03-13 16:05:03');
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
INSERT INTO `userpermissions` VALUES ('01fa95b8-1948-48fb-a816-8397d0b7e35c','8e793faa-e928-4eca-90ae-e12bbf98ba1b','a377396f-1d3c-4375-ab5b-fed4adfc912f',NULL,NULL),('0d2a1efc-06dd-4797-8335-c6fc1946bba1','8e793faa-e928-4eca-90ae-e12bbf98ba1b','5b691787-d267-4f5b-a0fb-d4f1bee30a97',NULL,NULL),('187b6068-d2f1-443d-8679-046a8c988db0','8e793faa-e928-4eca-90ae-e12bbf98ba1b','8df711ad-9697-43ef-95fe-397b510bb27d',NULL,NULL),('2508afc2-5306-4954-bb47-5e54c14a7202','8e793faa-e928-4eca-90ae-e12bbf98ba1b','a6d51f1e-cf63-4671-8f11-2ef36e2d8882',NULL,NULL),('37744bfe-1b91-4943-9294-35932c00e3ab','8e793faa-e928-4eca-90ae-e12bbf98ba1b','1308ce3a-fb1f-41dd-aa52-01cba9a3df41',NULL,NULL),('40ddc485-76d0-4c53-a307-3a334c2a4b0b','8e793faa-e928-4eca-90ae-e12bbf98ba1b','eae62e07-3ca4-4293-a12e-494b5f1a4621',NULL,NULL),('444d2db8-9d8e-470c-ad11-3e5daa247e1a','8e793faa-e928-4eca-90ae-e12bbf98ba1b','e9faf986-d6af-4a14-a00d-53b423164559',NULL,NULL),('46d0837a-de59-4d55-b41d-680efcb53784','8e793faa-e928-4eca-90ae-e12bbf98ba1b','24533248-1e2b-4c9b-935b-234b912c727e',NULL,NULL),('4ae5e534-8343-48db-9a5c-1c2292694553','8e793faa-e928-4eca-90ae-e12bbf98ba1b','535a7f0e-77f3-443c-a6cf-8bb2cf03f246',NULL,NULL),('578fa28b-1c5b-4dfb-a06b-a8dabd82e2da','8e793faa-e928-4eca-90ae-e12bbf98ba1b','894daace-4717-47fd-b50c-4bdab931f198',NULL,NULL),('5c0094f2-3b3c-4098-b9d4-107b75886f47','8e793faa-e928-4eca-90ae-e12bbf98ba1b','76038eff-cd87-4540-b9c3-835e51ef6e20',NULL,NULL),('5e311254-09a9-4f9a-b18b-c8c7499d2596','8e793faa-e928-4eca-90ae-e12bbf98ba1b','d0326ee8-209c-45cb-98d9-2c190d3b8fea',NULL,NULL),('5eea40f1-6be8-4266-92dd-9f1bb05c3a97','8e793faa-e928-4eca-90ae-e12bbf98ba1b','8738e24a-4df3-4d42-b20f-0867ead669b4',NULL,NULL),('60fb7417-8540-4072-84d6-6532174b5e28','8e793faa-e928-4eca-90ae-e12bbf98ba1b','d20f3fbd-fb04-43ba-a320-6a6a124a0d0b',NULL,NULL),('6bd2ce25-8939-497c-9530-87d70ecfcb0a','867dfb09-8b4d-4e25-bec3-8f07d0e63a37','09f0b68d-d401-4c7f-9ef8-10f962399fa5',NULL,NULL),('6d1048f3-643c-4a28-957a-cd33a804e879','8e793faa-e928-4eca-90ae-e12bbf98ba1b','b9a4edbe-7fbb-4050-bbae-20f125bd2234',NULL,NULL),('6f0b17af-ac54-4df3-a7c3-fb6e2931676d','8e793faa-e928-4eca-90ae-e12bbf98ba1b','4e7d80e0-bbfc-457f-b81f-9f0c571c3d6e',NULL,NULL),('7e552593-c3fb-48ef-b65d-8f7a9d8c3201','8e793faa-e928-4eca-90ae-e12bbf98ba1b','436c7651-44f8-4c14-959d-d8ab35cb2d54',NULL,NULL),('81290b6c-aa0d-4a7b-a892-b3b22037d354','8e793faa-e928-4eca-90ae-e12bbf98ba1b','bf02fa16-9aff-41d1-9c33-cd40c636160f',NULL,NULL),('831da785-98ec-41b5-a4d7-cd5f1bb4813d','8e793faa-e928-4eca-90ae-e12bbf98ba1b','80cfd5d9-c5a6-45a1-8a07-0828f7961e26',NULL,NULL),('858abd62-7de8-4815-b415-7c8044196936','8e793faa-e928-4eca-90ae-e12bbf98ba1b','174a16d1-bdec-44c7-934a-07598e2c0bbf',NULL,NULL),('9ffdef03-0aee-49ff-a045-67ad30bd608a','8e793faa-e928-4eca-90ae-e12bbf98ba1b','6fe0ca94-35cb-429e-9d8f-4789b90699af',NULL,NULL),('a21b7bc8-8f7a-4e30-830b-df9dc0a6e654','867dfb09-8b4d-4e25-bec3-8f07d0e63a37','5f648fb5-66de-464b-8a94-1085ec8ab468',NULL,NULL),('a2fa6413-095f-4e4a-a268-2b151fd99fcf','8e793faa-e928-4eca-90ae-e12bbf98ba1b','39f38bbe-9f9b-4018-98ec-4170224f33c5',NULL,NULL),('b5142a36-c9c1-43b8-9cb7-ad813a4b91e6','8e793faa-e928-4eca-90ae-e12bbf98ba1b','0565496a-0753-46ba-9463-4c17cce8588b',NULL,NULL),('b7b27f1d-ccf4-4b7c-b63f-7e6292f6088e','867dfb09-8b4d-4e25-bec3-8f07d0e63a37','d980ecd9-ee91-485a-b286-31a76c0bed2a',NULL,NULL),('bbc200d4-883c-4c16-842d-7c55a9b27946','8e793faa-e928-4eca-90ae-e12bbf98ba1b','0782080f-2e30-4df7-bdea-7f7fcff70bdf',NULL,NULL),('bbfc6c3c-8166-48ae-b256-5cfc98eb0c0c','8e793faa-e928-4eca-90ae-e12bbf98ba1b','e96c123d-80a0-4ac4-9433-6ac6f9e7cc91',NULL,NULL),('c8444c6b-b68b-4610-a041-58b67ce9942d','8e793faa-e928-4eca-90ae-e12bbf98ba1b','36bdc1a8-4216-4845-8007-52e6e26a917d',NULL,NULL),('c9b97be6-2473-45ed-a6db-0ef1097a37c5','8e793faa-e928-4eca-90ae-e12bbf98ba1b','46b16b76-4cc8-4e68-96f2-8792087d7a51',NULL,NULL),('cd317dd0-6407-4df3-8008-bdb9861ae860','8e793faa-e928-4eca-90ae-e12bbf98ba1b','3d05f398-d4aa-46fa-bee8-72d226a86738',NULL,NULL),('d0cf2c5b-0436-4a40-aa6f-a83ccc39ee96','867dfb09-8b4d-4e25-bec3-8f07d0e63a37','b0734086-3341-11ef-b05b-c8d9d27c3c7e',NULL,NULL),('d13d3cd5-8d14-4864-acb6-a3fe153f79fb','8e793faa-e928-4eca-90ae-e12bbf98ba1b','d6e1a65b-0533-415c-992d-cd03637aed4e',NULL,NULL),('d4dd6a9e-dc5e-4343-9ada-adf19cce8f94','8e793faa-e928-4eca-90ae-e12bbf98ba1b','367dd0ff-c3c7-4864-9457-7f97c52f855b',NULL,NULL),('f19ff7e8-47ea-462c-8690-144c40eac962','8e793faa-e928-4eca-90ae-e12bbf98ba1b','a9944b38-039c-44af-8e48-47c2ac4b1374',NULL,NULL),('f700402a-1fac-4129-a8eb-38e1e320e6c6','8e793faa-e928-4eca-90ae-e12bbf98ba1b','de2d34fe-0799-42d8-a796-4cb58baad518',NULL,NULL),('fd476136-3976-4352-9c28-322b671cc0b4','8e793faa-e928-4eca-90ae-e12bbf98ba1b','89d26b77-daad-4da3-9b41-549e9b46e3a0',NULL,NULL),('ff3ab5b3-8a1c-4ccc-a981-0425fe3f74b6','8e793faa-e928-4eca-90ae-e12bbf98ba1b','1b667bca-caf8-4c21-a2a7-c4deab0e93b6',NULL,NULL);
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
  `role` bigint unsigned NOT NULL,
  `isadmin` tinyint(1) NOT NULL DEFAULT '0',
  `isactive` tinyint(1) NOT NULL DEFAULT '1',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`userid`),
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `users_pfno_unique` (`pfno`),
  UNIQUE KEY `users_phonenumber_unique` (`phonenumber`),
  KEY `users_role_foreign` (`role`),
  CONSTRAINT `users_role_foreign` FOREIGN KEY (`role`) REFERENCES `userroles` (`roleid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES ('7e793faa-e928-4eca-90ae-e12bbf98ba1c','Test and Test','test@gmail.com','123','07123',NULL,3,0,0,'2025-03-08 20:20:46','$2y$10$bXy49/Zix7sWPO/TbRaHaOVjCoDYIdjxKR0uofWjCs6rgQecZM84S',NULL,'2025-03-08 20:08:10','2025-03-08 21:08:01'),('867dfb09-8b4d-4e25-bec3-8f07d0e63a37','john dev','portxyz100@gmail.com','1234','07888',NULL,2,1,0,'2025-03-08 21:15:36','$2y$10$l0xgrHsI2SaYk.UcsqKI5.Pce1nRwdgG0Mpb2xq57wvG.BhQzror2',NULL,'2025-03-08 21:11:26','2025-03-13 16:18:42'),('8e793faa-e928-4eca-90ae-e12bbf98ba1b','Felix Kiprotich','fkiprotich845@gmail.com','12345','071234',NULL,1,0,0,'2025-03-08 20:20:46','$2y$10$bXy49/Zix7sWPO/TbRaHaOVjCoDYIdjxKR0uofWjCs6rgQecZM84S',NULL,'2025-03-08 20:08:10','2025-03-08 21:08:01');
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
INSERT INTO `workplans` VALUES ('18dd0ea1-29fa-4927-bd4c-1515dbe710c3',1,'activity','time','input','facilities','by whom','outcome','2025-03-13 16:55:52','2025-03-13 16:55:52');
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

-- Dump completed on 2025-07-14 15:02:34
