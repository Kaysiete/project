-- MySQL dump 10.13  Distrib 8.0.30, for Win64 (x86_64)
--
-- Host: Localhost    Database: stageproject
-- ------------------------------------------------------
-- Server version	8.0.30

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
-- Table structure for table `menu_items`
--

DROP TABLE IF EXISTS `menu_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `menu_items` (
  `id` int NOT NULL AUTO_INCREMENT,
  `naam` varchar(255) NOT NULL,
  `beschrijving` varchar(255) NOT NULL,
  `prijs` decimal(10,2) NOT NULL,
  `menu_id` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `bijgewerkt_op` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `menu_id` (`menu_id`),
  CONSTRAINT `menu_items_ibfk_1` FOREIGN KEY (`menu_id`) REFERENCES `menus` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `menu_items`
--

LOCK TABLES `menu_items` WRITE;
/*!40000 ALTER TABLE `menu_items` DISABLE KEYS */;
INSERT INTO `menu_items` VALUES (1,'Pannenkoeken','Luchtige pannenkoeken met stroop',5.50,1,'2026-03-04 10:36:00','2026-03-04 10:36:00'),(2,'Croissant','Vers gebakken croissant met boter',2.75,1,'2026-03-04 10:36:00','2026-03-04 10:36:00'),(3,'Omelet','Omelet met kaas en groenten',6.25,1,'2026-03-04 10:36:00','2026-03-04 10:36:00'),(4,'Spaghetti Bolognese','Pasta met gehakt en tomatensaus',12.50,2,'2026-03-04 10:36:11','2026-03-04 10:36:11'),(5,'Gegrilde Zalm','Zalmfilet met citroenboter',15.00,2,'2026-03-04 10:36:11','2026-03-04 10:36:11'),(6,'Caesar Salad','Klassieke salade met kip en Parmezaanse kaas',9.50,2,'2026-03-04 10:36:11','2026-03-04 10:36:11');
/*!40000 ALTER TABLE `menu_items` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-03-04 11:38:41
