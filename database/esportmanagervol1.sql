-- MySQL dump 10.13  Distrib 8.0.30, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: esportmanagervol1
-- ------------------------------------------------------
-- Server version	8.0.30

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Current Database: `esportmanagervol1`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `esportmanagervol1` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;

USE `esportmanagervol1`;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `esport_teams`
--

DROP TABLE IF EXISTS `esport_teams`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `esport_teams` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama_tim` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi_tim` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `logo_esport` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tempat_kota_gh` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_kekayaan` decimal(15,2) NOT NULL DEFAULT '0.00',
  `winrate` decimal(5,2) NOT NULL DEFAULT '50.00',
  `penghargaan` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `region_id` bigint unsigned DEFAULT NULL,
  `pemilik_id` bigint unsigned DEFAULT NULL,
  `ceo_id` bigint unsigned DEFAULT NULL,
  `pelatih_utama_id` bigint unsigned DEFAULT NULL,
  `pelatih_kedua_id` bigint unsigned DEFAULT NULL,
  `manager_id` bigint unsigned DEFAULT NULL,
  `analis_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `esport_teams_region_id_foreign` (`region_id`),
  KEY `esport_teams_pemilik_id_foreign` (`pemilik_id`),
  KEY `esport_teams_ceo_id_foreign` (`ceo_id`),
  KEY `esport_teams_pelatih_utama_id_foreign` (`pelatih_utama_id`),
  KEY `esport_teams_pelatih_kedua_id_foreign` (`pelatih_kedua_id`),
  KEY `esport_teams_manager_id_foreign` (`manager_id`),
  KEY `esport_teams_analis_id_foreign` (`analis_id`),
  CONSTRAINT `esport_teams_analis_id_foreign` FOREIGN KEY (`analis_id`) REFERENCES `players` (`id`) ON DELETE SET NULL,
  CONSTRAINT `esport_teams_ceo_id_foreign` FOREIGN KEY (`ceo_id`) REFERENCES `players` (`id`) ON DELETE SET NULL,
  CONSTRAINT `esport_teams_manager_id_foreign` FOREIGN KEY (`manager_id`) REFERENCES `players` (`id`) ON DELETE SET NULL,
  CONSTRAINT `esport_teams_pelatih_kedua_id_foreign` FOREIGN KEY (`pelatih_kedua_id`) REFERENCES `players` (`id`) ON DELETE SET NULL,
  CONSTRAINT `esport_teams_pelatih_utama_id_foreign` FOREIGN KEY (`pelatih_utama_id`) REFERENCES `players` (`id`) ON DELETE SET NULL,
  CONSTRAINT `esport_teams_pemilik_id_foreign` FOREIGN KEY (`pemilik_id`) REFERENCES `players` (`id`) ON DELETE SET NULL,
  CONSTRAINT `esport_teams_region_id_foreign` FOREIGN KEY (`region_id`) REFERENCES `regions` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `esport_teams`
--

LOCK TABLES `esport_teams` WRITE;
/*!40000 ALTER TABLE `esport_teams` DISABLE KEYS */;
/*!40000 ALTER TABLE `esport_teams` ENABLE KEYS */;
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
-- Table structure for table `heroes`
--

DROP TABLE IF EXISTS `heroes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `heroes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama_hero` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipe_hero` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deskripsi` text COLLATE utf8mb4_unicode_ci,
  `foto` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `targeting` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gaya_permainan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `roles` json DEFAULT NULL,
  `jenis_serangan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `area_serangan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pola_serangan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role_utama` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role_pendukung` json DEFAULT NULL,
  `role_tidak_direkomendasikan` json DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `heroes`
--

LOCK TABLES `heroes` WRITE;
/*!40000 ALTER TABLE `heroes` DISABLE KEYS */;
/*!40000 ALTER TABLE `heroes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
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
-- Table structure for table `match_participants`
--

DROP TABLE IF EXISTS `match_participants`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `match_participants` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `match_id` bigint unsigned NOT NULL,
  `esport_team_id` bigint unsigned NOT NULL,
  `rounds_won` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `previous_match_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `match_participants_match_id_index` (`match_id`),
  KEY `match_participants_esport_team_id_index` (`esport_team_id`),
  KEY `match_participants_previous_match_id_foreign` (`previous_match_id`),
  CONSTRAINT `match_participants_esport_team_id_foreign` FOREIGN KEY (`esport_team_id`) REFERENCES `esport_teams` (`id`) ON DELETE CASCADE,
  CONSTRAINT `match_participants_match_id_foreign` FOREIGN KEY (`match_id`) REFERENCES `matches` (`id`) ON DELETE CASCADE,
  CONSTRAINT `match_participants_previous_match_id_foreign` FOREIGN KEY (`previous_match_id`) REFERENCES `matches` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `match_participants`
--

LOCK TABLES `match_participants` WRITE;
/*!40000 ALTER TABLE `match_participants` DISABLE KEYS */;
/*!40000 ALTER TABLE `match_participants` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `matches`
--

DROP TABLE IF EXISTS `matches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `matches` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tournament_id` bigint unsigned NOT NULL,
  `match_number` int NOT NULL,
  `round_index` int unsigned NOT NULL DEFAULT '1',
  `bo_format` enum('BO3','BO5','BO7') COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('pending','in_progress','completed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `is_grand_final` tinyint(1) NOT NULL DEFAULT '0',
  `winner_team_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `matches_winner_team_id_foreign` (`winner_team_id`),
  KEY `matches_tournament_id_index` (`tournament_id`),
  KEY `matches_status_index` (`status`),
  CONSTRAINT `matches_tournament_id_foreign` FOREIGN KEY (`tournament_id`) REFERENCES `tournaments` (`id`) ON DELETE CASCADE,
  CONSTRAINT `matches_winner_team_id_foreign` FOREIGN KEY (`winner_team_id`) REFERENCES `esport_teams` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `matches`
--

LOCK TABLES `matches` WRITE;
/*!40000 ALTER TABLE `matches` DISABLE KEYS */;
/*!40000 ALTER TABLE `matches` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1);
INSERT INTO `migrations` VALUES (2,'0001_01_01_000001_create_cache_table',1);
INSERT INTO `migrations` VALUES (3,'0001_01_01_000002_create_jobs_table',1);
INSERT INTO `migrations` VALUES (4,'2026_02_11_094822_create_tournaments_table',1);
INSERT INTO `migrations` VALUES (5,'2026_02_11_094825_create_esport_teams_table',1);
INSERT INTO `migrations` VALUES (6,'2026_02_11_094834_create_players_table',1);
INSERT INTO `migrations` VALUES (7,'2026_02_11_094837_create_tournament_slots_table',1);
INSERT INTO `migrations` VALUES (8,'2026_02_11_094841_create_player_roles_table',1);
INSERT INTO `migrations` VALUES (9,'2026_02_11_112235_create_tournament_awards_table',1);
INSERT INTO `migrations` VALUES (10,'2026_02_11_112237_create_matches_table',1);
INSERT INTO `migrations` VALUES (11,'2026_02_11_112238_create_match_participants_table',1);
INSERT INTO `migrations` VALUES (12,'2026_02_11_112240_create_rounds_table',1);
INSERT INTO `migrations` VALUES (13,'2026_02_11_112241_create_team_statistics_table',1);
INSERT INTO `migrations` VALUES (14,'2026_02_11_112243_modify_tournaments_remove_awards',1);
INSERT INTO `migrations` VALUES (15,'2026_02_11_112244_modify_tournament_slots_remove_prize',1);
INSERT INTO `migrations` VALUES (16,'2026_02_11_120823_add_photo_to_players_table',1);
INSERT INTO `migrations` VALUES (17,'2026_02_12_072827_create_tournament_prizes_table_and_add_status_to_tournaments',1);
INSERT INTO `migrations` VALUES (18,'2026_02_19_041351_add_nationality_to_players_table',1);
INSERT INTO `migrations` VALUES (19,'2026_02_19_044810_add_nationality_column_to_players_table',1);
INSERT INTO `migrations` VALUES (20,'2026_02_19_053217_add_country_to_esport_teams_table',1);
INSERT INTO `migrations` VALUES (21,'2026_02_19_140000_add_winrate_to_esport_teams_table',1);
INSERT INTO `migrations` VALUES (22,'2026_02_20_075528_add_is_grand_final_to_matches_table',1);
INSERT INTO `migrations` VALUES (23,'2026_09_13_102623_create_regions_table',1);
INSERT INTO `migrations` VALUES (24,'2026_09_13_102743_modify_players_table',1);
INSERT INTO `migrations` VALUES (25,'2026_09_13_102924_modify_esport_teams_table',1);
INSERT INTO `migrations` VALUES (26,'2026_09_13_110000_create_heroes_table',1);
INSERT INTO `migrations` VALUES (27,'2026_09_13_110001_add_format_and_bo_to_tournaments_table',1);
INSERT INTO `migrations` VALUES (28,'2026_09_13_110002_add_winrate_to_players_table',1);
INSERT INTO `migrations` VALUES (29,'2026_09_13_110003_add_round_index_to_matches_table',1);
INSERT INTO `migrations` VALUES (30,'2026_09_13_110004_add_mvp_to_rounds_table',1);
INSERT INTO `migrations` VALUES (31,'2026_09_13_110005_add_previous_match_to_match_participants_table',1);
INSERT INTO `migrations` VALUES (32,'2026_09_13_110006_create_round_lineups_table',1);
INSERT INTO `migrations` VALUES (33,'2026_09_27_100000_add_none_to_players_jabatan_table',1);
INSERT INTO `migrations` VALUES (34,'2026_11_08_000000_update_hero_identity_fields',1);
INSERT INTO `migrations` VALUES (35,'2026_11_19_000000_add_hero_attack_and_role_categories',1);
INSERT INTO `migrations` VALUES (36,'2026_11_19_000001_add_personnel_category_and_athlete_profile',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `players`
--

DROP TABLE IF EXISTS `players`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `players` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama_asli` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nickname_game` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi_pemain` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `esport_team_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `region_id` bigint unsigned DEFAULT NULL,
  `jabatan` enum('None','Player','Owner','CEO','Head Coach','Assistant Coach','Manager','Analyst','Ketua Persatuan Game','Pelatih Timnas') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'None',
  `winrate` decimal(5,2) NOT NULL DEFAULT '50.00',
  `kategori_personel` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Common',
  `role_utama` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role_cadangan` json DEFAULT NULL,
  `role_kurang_kompeten` json DEFAULT NULL,
  `gaya_permainan` json DEFAULT NULL,
  `targeting_urut` json DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `players_esport_team_id_foreign` (`esport_team_id`),
  KEY `players_region_id_foreign` (`region_id`),
  CONSTRAINT `players_esport_team_id_foreign` FOREIGN KEY (`esport_team_id`) REFERENCES `esport_teams` (`id`) ON DELETE SET NULL,
  CONSTRAINT `players_region_id_foreign` FOREIGN KEY (`region_id`) REFERENCES `regions` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `players`
--

LOCK TABLES `players` WRITE;
/*!40000 ALTER TABLE `players` DISABLE KEYS */;
/*!40000 ALTER TABLE `players` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `regions`
--

DROP TABLE IF EXISTS `regions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `regions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama_negara` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ketua_persatuan_game_id` bigint unsigned DEFAULT NULL,
  `pelatih_timnas_id` bigint unsigned DEFAULT NULL,
  `foto_negara` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `regions_ketua_persatuan_game_id_foreign` (`ketua_persatuan_game_id`),
  KEY `regions_pelatih_timnas_id_foreign` (`pelatih_timnas_id`),
  CONSTRAINT `regions_ketua_persatuan_game_id_foreign` FOREIGN KEY (`ketua_persatuan_game_id`) REFERENCES `players` (`id`) ON DELETE SET NULL,
  CONSTRAINT `regions_pelatih_timnas_id_foreign` FOREIGN KEY (`pelatih_timnas_id`) REFERENCES `players` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `regions`
--

LOCK TABLES `regions` WRITE;
/*!40000 ALTER TABLE `regions` DISABLE KEYS */;
INSERT INTO `regions` VALUES (1,'Brunei Darussalam',NULL,NULL,'Bendera/Flag_of_Brunei-256x128.png','2026-11-19 03:27:36','2026-11-19 03:27:36');
INSERT INTO `regions` VALUES (2,'Mesir',NULL,NULL,'Bendera/Flag_of_Egypt-256x171.png','2026-11-19 03:27:36','2026-11-19 03:27:36');
INSERT INTO `regions` VALUES (3,'Inggris',NULL,NULL,'Bendera/Flag_of_England-256x154.png','2026-11-19 03:27:36','2026-11-19 03:27:36');
INSERT INTO `regions` VALUES (4,'Indonesia',NULL,NULL,'Bendera/Flag_of_Indonesia-256x171.png','2026-11-19 03:27:36','2026-11-19 03:27:36');
INSERT INTO `regions` VALUES (5,'Irlandia',NULL,NULL,'Bendera/Flag_of_Ireland-256x128.png','2026-11-19 03:27:36','2026-11-19 03:27:36');
INSERT INTO `regions` VALUES (6,'Jepang',NULL,NULL,'Bendera/Flag_of_Japan-256x171.png','2026-11-19 03:27:36','2026-11-19 03:27:36');
INSERT INTO `regions` VALUES (7,'Malaysia',NULL,NULL,'Bendera/Flag_of_Malaysia-256x128.png','2026-11-19 03:27:36','2026-11-19 03:27:36');
INSERT INTO `regions` VALUES (8,'Meksiko',NULL,NULL,'Bendera/Flag_of_Mexico-256x146.png','2026-11-19 03:27:36','2026-11-19 03:27:36');
INSERT INTO `regions` VALUES (9,'Tiongkok',NULL,NULL,'Bendera/Flag_of_Peoples_Republic_of_China-256x171.png','2026-11-19 03:27:36','2026-11-19 03:27:36');
INSERT INTO `regions` VALUES (10,'Filipina',NULL,NULL,'Bendera/Flag_of_Philippines-256x128.png','2026-11-19 03:27:36','2026-11-19 03:27:36');
INSERT INTO `regions` VALUES (11,'Qatar',NULL,NULL,'Bendera/Flag_of_Qatar-256x100.png','2026-11-19 03:27:36','2026-11-19 03:27:36');
INSERT INTO `regions` VALUES (12,'Rusia',NULL,NULL,'Bendera/Flag_of_Russia-256x171.png','2026-11-19 03:27:36','2026-11-19 03:27:36');
INSERT INTO `regions` VALUES (13,'Arab Saudi',NULL,NULL,'Bendera/Flag_of_Saudi_Arabia-256x171.png','2026-11-19 03:27:36','2026-11-19 03:27:36');
INSERT INTO `regions` VALUES (14,'Singapura',NULL,NULL,'Bendera/Flag_of_Singapore-256x171.png','2026-11-19 03:27:36','2026-11-19 03:27:36');
INSERT INTO `regions` VALUES (15,'Thailand',NULL,NULL,'Bendera/Flag_of_Thailand-256x171.png','2026-11-19 03:27:36','2026-11-19 03:27:36');
INSERT INTO `regions` VALUES (16,'Turki',NULL,NULL,'Bendera/Flag_of_Turkey-256x171.png','2026-11-19 03:27:36','2026-11-19 03:27:36');
INSERT INTO `regions` VALUES (17,'Uni Emirat Arab',NULL,NULL,'Bendera/Flag_of_United_Arab_Emirates-256x128.png','2026-11-19 03:27:36','2026-11-19 03:27:36');
INSERT INTO `regions` VALUES (18,'Amerika Serikat',NULL,NULL,'Bendera/Flag_of_United_States-256x135.png','2026-11-19 03:27:36','2026-11-19 03:27:36');
/*!40000 ALTER TABLE `regions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `round_lineups`
--

DROP TABLE IF EXISTS `round_lineups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `round_lineups` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `round_id` bigint unsigned NOT NULL,
  `player_id` bigint unsigned NOT NULL,
  `hero_id` bigint unsigned NOT NULL,
  `esport_team_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `round_lineups_round_id_player_id_unique` (`round_id`,`player_id`),
  UNIQUE KEY `round_lineups_round_id_hero_id_unique` (`round_id`,`hero_id`),
  KEY `round_lineups_player_id_foreign` (`player_id`),
  KEY `round_lineups_hero_id_foreign` (`hero_id`),
  KEY `round_lineups_esport_team_id_foreign` (`esport_team_id`),
  CONSTRAINT `round_lineups_esport_team_id_foreign` FOREIGN KEY (`esport_team_id`) REFERENCES `esport_teams` (`id`) ON DELETE SET NULL,
  CONSTRAINT `round_lineups_hero_id_foreign` FOREIGN KEY (`hero_id`) REFERENCES `heroes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `round_lineups_player_id_foreign` FOREIGN KEY (`player_id`) REFERENCES `players` (`id`) ON DELETE CASCADE,
  CONSTRAINT `round_lineups_round_id_foreign` FOREIGN KEY (`round_id`) REFERENCES `rounds` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `round_lineups`
--

LOCK TABLES `round_lineups` WRITE;
/*!40000 ALTER TABLE `round_lineups` DISABLE KEYS */;
/*!40000 ALTER TABLE `round_lineups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rounds`
--

DROP TABLE IF EXISTS `rounds`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rounds` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `match_id` bigint unsigned NOT NULL,
  `round_number` int NOT NULL,
  `winner_team_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `mvp_player_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `rounds_winner_team_id_foreign` (`winner_team_id`),
  KEY `rounds_match_id_index` (`match_id`),
  KEY `rounds_mvp_player_id_foreign` (`mvp_player_id`),
  CONSTRAINT `rounds_match_id_foreign` FOREIGN KEY (`match_id`) REFERENCES `matches` (`id`) ON DELETE CASCADE,
  CONSTRAINT `rounds_mvp_player_id_foreign` FOREIGN KEY (`mvp_player_id`) REFERENCES `players` (`id`) ON DELETE SET NULL,
  CONSTRAINT `rounds_winner_team_id_foreign` FOREIGN KEY (`winner_team_id`) REFERENCES `esport_teams` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rounds`
--

LOCK TABLES `rounds` WRITE;
/*!40000 ALTER TABLE `rounds` DISABLE KEYS */;
/*!40000 ALTER TABLE `rounds` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `team_statistics`
--

DROP TABLE IF EXISTS `team_statistics`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `team_statistics` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tournament_id` bigint unsigned NOT NULL,
  `esport_team_id` bigint unsigned NOT NULL,
  `matches_played` int NOT NULL DEFAULT '0',
  `matches_won` int NOT NULL DEFAULT '0',
  `rounds_played` int NOT NULL DEFAULT '0',
  `rounds_won` int NOT NULL DEFAULT '0',
  `points` int NOT NULL DEFAULT '0',
  `win_rate` decimal(5,2) NOT NULL DEFAULT '50.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `team_statistics_tournament_id_esport_team_id_unique` (`tournament_id`,`esport_team_id`),
  KEY `team_statistics_tournament_id_index` (`tournament_id`),
  KEY `team_statistics_esport_team_id_index` (`esport_team_id`),
  CONSTRAINT `team_statistics_esport_team_id_foreign` FOREIGN KEY (`esport_team_id`) REFERENCES `esport_teams` (`id`) ON DELETE CASCADE,
  CONSTRAINT `team_statistics_tournament_id_foreign` FOREIGN KEY (`tournament_id`) REFERENCES `tournaments` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `team_statistics`
--

LOCK TABLES `team_statistics` WRITE;
/*!40000 ALTER TABLE `team_statistics` DISABLE KEYS */;
/*!40000 ALTER TABLE `team_statistics` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tournament_awards`
--

DROP TABLE IF EXISTS `tournament_awards`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tournament_awards` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tournament_id` bigint unsigned NOT NULL,
  `award_name` enum('MVP Finals','Top Player','Most Killed Player') COLLATE utf8mb4_unicode_ci NOT NULL,
  `prize_amount` decimal(15,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tournament_awards_tournament_id_index` (`tournament_id`),
  CONSTRAINT `tournament_awards_tournament_id_foreign` FOREIGN KEY (`tournament_id`) REFERENCES `tournaments` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tournament_awards`
--

LOCK TABLES `tournament_awards` WRITE;
/*!40000 ALTER TABLE `tournament_awards` DISABLE KEYS */;
/*!40000 ALTER TABLE `tournament_awards` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tournament_prizes`
--

DROP TABLE IF EXISTS `tournament_prizes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tournament_prizes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tournament_id` bigint unsigned NOT NULL,
  `rank` int NOT NULL,
  `prize_amount` decimal(15,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tournament_prizes_tournament_id_foreign` (`tournament_id`),
  CONSTRAINT `tournament_prizes_tournament_id_foreign` FOREIGN KEY (`tournament_id`) REFERENCES `tournaments` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tournament_prizes`
--

LOCK TABLES `tournament_prizes` WRITE;
/*!40000 ALTER TABLE `tournament_prizes` DISABLE KEYS */;
/*!40000 ALTER TABLE `tournament_prizes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tournament_slots`
--

DROP TABLE IF EXISTS `tournament_slots`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tournament_slots` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tournament_id` bigint unsigned NOT NULL,
  `slot_number` int NOT NULL,
  `esport_team_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tournament_slots_tournament_id_foreign` (`tournament_id`),
  KEY `tournament_slots_esport_team_id_foreign` (`esport_team_id`),
  CONSTRAINT `tournament_slots_esport_team_id_foreign` FOREIGN KEY (`esport_team_id`) REFERENCES `esport_teams` (`id`) ON DELETE SET NULL,
  CONSTRAINT `tournament_slots_tournament_id_foreign` FOREIGN KEY (`tournament_id`) REFERENCES `tournaments` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tournament_slots`
--

LOCK TABLES `tournament_slots` WRITE;
/*!40000 ALTER TABLE `tournament_slots` DISABLE KEYS */;
/*!40000 ALTER TABLE `tournament_slots` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tournaments`
--

DROP TABLE IF EXISTS `tournaments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tournaments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama_tournament` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `penyelenggara` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_hadiah` decimal(15,2) NOT NULL,
  `deskripsi_tournament` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `logo_tournament` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jumlah_slot` int NOT NULL,
  `negara_penyelenggara` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tempat_final` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tier` enum('S+ Tier','S Tier','A Tier','B Tier','C Tier','National Invitation','International Invitation') COLLATE utf8mb4_unicode_ci NOT NULL,
  `format` enum('standard','knockout') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'standard',
  `bo_format` enum('BO3','BO5','BO7') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'BO3',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status` enum('upcoming','ongoing','completed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'upcoming',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tournaments`
--

LOCK TABLES `tournaments` WRITE;
/*!40000 ALTER TABLE `tournaments` DISABLE KEYS */;
/*!40000 ALTER TABLE `tournaments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-11-19 17:28:41
