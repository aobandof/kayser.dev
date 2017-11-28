-- MySQL dump 10.16  Distrib 10.1.26-MariaDB, for Win32 (AMD64)
--
-- Host: localhost    Database: kayser_articulos
-- ------------------------------------------------------
-- Server version	10.1.26-MariaDB

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
-- Table structure for table `articulo`
--

DROP TABLE IF EXISTS `articulo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `articulo` (
  `codigo` varchar(45) NOT NULL,
  `lista_id` int(10) unsigned NOT NULL,
  `itemname` varchar(45) NOT NULL,
  `marca_code` int(11) NOT NULL,
  `marca_name` varchar(45) NOT NULL,
  `dpto_code` int(11) NOT NULL,
  `dpto_name` varchar(45) NOT NULL,
  `subdpto_code` int(11) NOT NULL,
  `subdpto_name` varchar(45) NOT NULL,
  `prenda_code` varchar(5) DEFAULT NULL,
  `prenda_name` varchar(45) DEFAULT NULL,
  `categoria_code` varchar(5) DEFAULT NULL,
  `categoria_name` varchar(45) DEFAULT NULL,
  `presentacion_code` int(11) DEFAULT NULL,
  `presentacion_name` varchar(45) DEFAULT NULL,
  `material_code` int(11) DEFAULT NULL,
  `material_name` varchar(45) DEFAULT NULL,
  `tprenda_code` int(11) DEFAULT NULL,
  `tprenda_name` varchar(15) DEFAULT NULL,
  `tcatalogo_code` int(11) DEFAULT NULL,
  `tcatalogo_name` varchar(20) DEFAULT NULL,
  `grupouso_code` int(11) DEFAULT NULL,
  `grupouso_name` varchar(45) DEFAULT NULL,
  `caracteristica_code` int(11) DEFAULT NULL,
  `caracteristica_name` varchar(45) DEFAULT NULL,
  `composicion_code` int(11) DEFAULT NULL,
  `composicion_name` varchar(100) DEFAULT NULL,
  `talla_familia` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`codigo`),
  UNIQUE KEY `codigo_UNIQUE` (`codigo`),
  KEY `fk_articulo_lista1_idx` (`lista_id`),
  CONSTRAINT `fk_articulo_lista1` FOREIGN KEY (`lista_id`) REFERENCES `lista` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `articulo`
--

LOCK TABLES `articulo` WRITE;
/*!40000 ALTER TABLE `articulo` DISABLE KEYS */;
/*!40000 ALTER TABLE `articulo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `caracteristica`
--

DROP TABLE IF EXISTS `caracteristica`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `caracteristica` (
  `id` tinyint(4) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(105) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `caracteristica`
--

LOCK TABLES `caracteristica` WRITE;
/*!40000 ALTER TABLE `caracteristica` DISABLE KEYS */;
INSERT INTO `caracteristica` VALUES (1,'FULL PRINT'),(2,'OTRA CARACTERISTICA');
/*!40000 ALTER TABLE `caracteristica` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `color`
--

DROP TABLE IF EXISTS `color`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `color` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(40) COLLATE utf8_spanish_ci NOT NULL,
  `abreviatura` varchar(15) COLLATE utf8_spanish_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre_UNIQUE` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=171 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `color`
--

LOCK TABLES `color` WRITE;
/*!40000 ALTER TABLE `color` DISABLE KEYS */;
INSERT INTO `color` VALUES (1,'ACERO','ACE'),(2,'ALMENDRA','ALM'),(3,'AMARILLO','AMA'),(4,'AMATISTA (LILA)','AMT'),(5,'AMBAR (ROJO OSCURO)','AMB'),(6,'ANIMAL PRINT','ANI'),(7,'AQUA','AQU'),(8,'ARANDANO','ARA'),(9,'ARUBA (TURQUEZA)','ARU'),(10,'AZALEA (ROSADO)','AZA'),(11,'AZUL','AZU'),(12,'AZUL REY','AZR'),(13,'AZULINO','AZL'),(14,'BANANA','BAN'),(15,'BARQUILLO','BAR'),(16,'BEIGE','BEI'),(17,'BERENJENA','BER'),(18,'BERRY','BRY'),(19,'BLANCO','BLA'),(20,'BLUE','BLU'),(21,'BURDEO','BUR'),(22,'CACAO','CAC'),(23,'CAFÉ','CAF'),(24,'CALABAZA','CLB'),(25,'CALIPSO','CAL'),(26,'CAMEL','CAM'),(27,'CANDY (ROSADO-ROJO)','CAD'),(28,'CANELA','CAN'),(29,'CAQUI','CAQ'),(30,'CARBON','CAR'),(31,'CARMIN','CAM'),(32,'CELESTE','CEL'),(33,'CEREZA','CER'),(34,'CHERRY','CHE'),(35,'CHICLE','CHI'),(36,'CHOCOLATE','CHO'),(37,'COBRE','COB'),(38,'COGÑAC','COG'),(39,'COLOR 1','CO1'),(40,'COLOR 2','CO2'),(41,'COLOR 3','CO3'),(42,'COLOR 4','CO4'),(43,'COM1','COM1'),(44,'COM2','COM2'),(45,'COM3','COM3'),(46,'COM4','COM4'),(47,'COM5','COM5'),(48,'CORAL','COR'),(49,'CREMA','CRE'),(50,'DAMASCO','DAM'),(51,'DEG','DEG'),(52,'DISEÑO','DIS'),(53,'DORADO','DOR'),(54,'ESMERALDA','ESM'),(55,'ESTAMPADO','EST'),(56,'ETNICO','ETN'),(57,'FLUOR','FLU'),(58,'FRAMBUESA','FRA'),(59,'FRESA','FRE'),(60,'FRUTILLA','FRU'),(61,'FUCSIA','FUC'),(62,'GOLD','GOL'),(63,'GRAFITO','GRA'),(64,'GRANADA','GRN'),(65,'GREEN','GRE'),(66,'GREY','GRY'),(67,'GRIS','GRI'),(68,'GUINDA','GUI'),(69,'INDIGO (AZUL)','IND'),(70,'JACARANDA (VIOLETA)','JAC'),(71,'JADE (VERDE ESMERALDA)','JAD'),(72,'JEANS','JEA'),(73,'KIWI','KIW'),(74,'LAPIZLAZULI (AZUL)','LAP'),(75,'LAVANDA','LAV'),(76,'LILA','LIL'),(77,'LIMA','LIM'),(78,'LIMON','LMO'),(79,'LISO','LIS'),(80,'LUCUMA','LUC'),(81,'LUNARES','LUN'),(82,'MAGENTA','MAG'),(83,'MALVA','MAL'),(84,'MANGO','MAN'),(85,'MANTEQUILLA','MAT'),(86,'MAQUI','MAQ'),(87,'MARENGO','MAR'),(88,'MARFIL','MRF'),(89,'MARRON','MRN'),(90,'MARSHMELLOW','MSH'),(91,'MELON','MEL'),(92,'MENTA','MEN'),(93,'METAL','MET'),(94,'MOCA','MOC'),(95,'MORA','MRA'),(96,'MORADO','MOR'),(97,'MOSTAZA','MOS'),(98,'NARANJA','NAR'),(99,'NATURAL','NAT'),(100,'NAVY','NAV'),(101,'NEGRO','NEG'),(102,'NEON','NEO'),(103,'NUDE','NUD'),(104,'OBISPO','OBI'),(105,'OLIVA','OLI'),(106,'ONIX (NEGRO)','ONI'),(107,'ORANGE','ORA'),(108,'PACK1','PACK1'),(109,'PACK2','PACK2'),(110,'PACK3','PACK3'),(111,'PALO ROSA','PRO'),(112,'PASTEL','PAS'),(113,'PEACH','PEA'),(114,'PERA','PER'),(115,'PETROLEO','PET'),(116,'PIEL','PIE'),(117,'PINK','PIN'),(118,'PISTACHO','PIS'),(119,'PLATA','PLA'),(120,'PLOMO','PLO'),(121,'PRINT','PRI'),(122,'PURPLE','PRP'),(123,'PURPURA','PUR'),(124,'RED','RED'),(125,'ROJO','ROJ'),(126,'ROSA','RSA'),(127,'ROSADO','ROS'),(128,'ROYAL','ROY'),(129,'RUBI','RUB'),(130,'S/C','S/C'),(131,'SAFIRO','SAF'),(132,'SALMON','SAL'),(133,'SANDIA','SAN'),(134,'SANGRIA','SNG'),(135,'SEA (AZULES)','SEA'),(136,'SILVER','SIL'),(137,'SMOKE','SMO'),(138,'SUN','SUN'),(139,'SURTIDO','SUR'),(140,'SURTIDO 1','SUR1'),(141,'SURTIDO 10','SUR10'),(142,'SURTIDO 11','SUR11'),(143,'SURTIDO 12','SUR12'),(144,'SURTIDO 13','SUR13'),(145,'SURTIDO 14','SUR14'),(146,'SURTIDO 15','SUR15'),(147,'SURTIDO 2','SUR2'),(148,'SURTIDO 3','SUR3'),(149,'SURTIDO 4','SUR4'),(150,'SURTIDO 5','SUR5'),(151,'SURTIDO 6','SUR6'),(152,'SURTIDO 7','SUR7'),(153,'SURTIDO 8','SUR8'),(154,'SURTIDO 9','SUR9'),(155,'TAUPE','TAU'),(156,'TERRACOTA','TER'),(157,'TOFFEE','TOF'),(158,'TOPACIO (CAFE)','TOP'),(159,'TOSTADO','TOS'),(160,'TURQUESA','TUR'),(161,'UVA','UVA'),(162,'VAINILLA','VAI'),(163,'VERDE','VER'),(164,'VINO','VIN'),(165,'VIOLETA','VIO'),(166,'VISON','VIS'),(167,'WARMRED','WAR'),(168,'WHITE','WHI'),(169,'ZAFIRO','ZAF'),(170,'ZEBRA','ZEB');
/*!40000 ALTER TABLE `color` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `composicion`
--

DROP TABLE IF EXISTS `composicion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `composicion` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(85) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=94 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `composicion`
--

LOCK TABLES `composicion` WRITE;
/*!40000 ALTER TABLE `composicion` DISABLE KEYS */;
INSERT INTO `composicion` VALUES (1,'100% ACRILICO'),(2,'100% ALGODON'),(3,'100% POLIAMIDA'),(4,'100% POLIESTER'),(5,'100% SILICONA'),(6,'40% ALGODON 55% POLIESTER 5% ELASTANO'),(7,'40% POLIAMIDA 40% POLIESTER 20%ELASTANO'),(8,'50% ALGODON 50% POLIESTER'),(9,'50% POLIESTER 50% POLYAMIDA'),(10,'51% POLIAMIDA 44% POLIESTER 5% ELASTANO'),(11,'60% ALGODON 40% ELASTANO '),(12,'60% ALGODON 40% POLIESTER ( CVC)'),(13,'60% ALGODON 40% POLYAMIDA'),(14,'60% POLIESTER 35% ALGODON 5% ELASTANO'),(15,'60% POLIESTER 40% ALGODON'),(16,'62% FIBRA BAMBU 23% POLIESTER 10% COBRE 3% LUREX 2% ELASTANO'),(17,'62% FIBRA BAMBU 26% POLIESTER 10% COBRE 2% ELASTANO'),(18,'63% POLIESTER 32% ALGODON 5% ELASTANO'),(19,'65% ALGODON 23% POLIESTER 10% COBRE 2% ELASTANO'),(20,'65% ALGODON 35% POLIESTER '),(21,'65% POLIESTER 35% ALGODÓN ( T/C)'),(22,'65% VISCOSA 25% POLIESTER 10% ELASTANO'),(23,'66% ALGODON 27% POLIESTER 7% ELASTANO'),(24,'67% VISCOSA 23% POLIESTER 10% ELASTANO'),(25,'70% ACRILICO 25% POLIETER 5%ELASTANO'),(26,'70% ALGODON 20% POLIESTER 10% ESLASTANO'),(27,'70% POLYAMIDA 20% POLIESTER 10% ELASTANO'),(28,'75% ALGODÓN 21% POLIESTER 2% LUREX 2% ELASTANO'),(29,'75% ALGODON 22% POLIAMIDA 3% ELASTANO'),(30,'75% ALGODON 22% POLIESTER 3% ELASTANO'),(31,'75% ALGODON 23% POLIAMIDA 2% ELASTANO'),(32,'75% ALGODON 23% POLIESTER 2% ELASTANO'),(33,'75% ALGODON 24% POLIESTER 1% ELASTANO'),(34,'75% ALGODON 25% POLIESTER'),(35,'75% BAMBU 25 %POLIAMIDA'),(36,'75% BAMBU 25% POLIESTER'),(37,'75% FIBRA BAMBU 21% POLIESTER 2% LUREX 2% ELASTANO'),(38,'75% FIBRA BAMBU 23% POLIESTER 2% ELASTANO'),(39,'75% POLiAMIDA 25% ELASTANO'),(40,'77% CACHEMIRA 21% POLIESTER 2% ELASTANO'),(41,'78% POLiAMIDA 12% POLIE STER 10% ELASTANO'),(42,'78% POLIAMIDA 12% POLIESTER 10% ELASTANO'),(43,'80% ALGODON 10% POLYAMIDA 10% ELASTANO'),(44,'80% ALGODON 15% POLIESTER 5% ELASTANO'),(45,'80% ALGODON 15% POLYAMIDA 5% ELASTANO'),(46,'80% ALGODON 18% POLIESTER 2% ELASTANO'),(47,'80% ALGODON 20% POLIESTER'),(48,'80% NiLON 20% ELASTANO'),(49,'80% POLIESTER 10% ALGODON 10% ELASTANO'),(50,'80% POLIESTER 15% ALGODON 5% ELASTANO'),(51,'80% POLIESTER 20% ELASTANO'),(52,'80% POLYAMIDA 10% ALGODON 10% ELASTANO'),(53,'80% POLYAMIDA 10% POLIESTR 10% ELASTANO'),(54,'80% POLYAMIDA 20% ELASTANO'),(55,'82% POLIESTER 18% ELASTANO'),(56,'82% POLYAMIDA 18% ELASTANO'),(57,'83% POLiAMIDA 17% ELASTANO'),(58,'83% POLIESTER 17% ALGODON'),(59,'84% POLIAMIDA 16% ELASTANO'),(60,'85% POLiAMIDA 10% ELASTANO 5% ALGODON'),(61,'85% POLiAMIDA 15% ELASTANO'),(62,'85% POLIESTER 10% ELASTANO 5% ALGODON'),(63,'85% POLIESTER 15% ELASTANO'),(64,'86% NiLON 14% ELASTANO'),(65,'86% POLiAMIDA 14% ELASTANO'),(66,'87% POLiAMIDA 13% ELASTANO'),(67,'88% POLiAMIDA 12% ELASTANO'),(68,'88% POLIESTER 12% ELASTANO'),(69,'90% ALGODON 10% ELASTANO'),(70,'90% ALGODON 9% POLIESTER 1% ELASTANO'),(71,'90% POLiAMIDA 10% ELASTANO'),(72,'90% POLIESTER 10% ELASTANO'),(73,'91% ALPACA 8% POLIESTER 1% ELASTANO'),(74,'91% POLIESTER 9% ELASTANO'),(75,'92% ALGODON 8% ELASTANO'),(76,'92% POLiAMIDA 8% ELASTANO'),(77,'92% POLIESTER 8% ELASTANO'),(78,'93% ALGODON 7% ELASTANO'),(79,'93% POLiAMIDA 7% ELASTANO'),(80,'93% POLIESTER 7% ELASTANO'),(81,'94% POLiAMIDA 6% ELASTANO'),(82,'94% POLIESTER 6% ELASTANO'),(83,'95% ALGODON 5% ELASTANO'),(84,'95% ALGODON 5% POLYAMIDA'),(85,'95% BAMBOO 5% ELASTANO'),(86,'95% COTTON 5% ELASTANO'),(87,'95% POLiAMIDA 5% ELASTANO'),(88,'95% POLIESTER 5% ELASTANO'),(89,'96% POLIESTER 2% LUREX 2% ELASTANO'),(90,'96% POLIESTER 4% ELASTANO'),(91,'97% POLIESTER 3% ELASTANO'),(92,'98% ALGODON 5% ELASTANO'),(93,'98% POLIESTER 2% ELASTANO');
/*!40000 ALTER TABLE `composicion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `copa`
--

DROP TABLE IF EXISTS `copa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `copa` (
  `id` tinyint(4) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(10) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre_UNIQUE` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `copa`
--

LOCK TABLES `copa` WRITE;
/*!40000 ALTER TABLE `copa` DISABLE KEYS */;
INSERT INTO `copa` VALUES (2,'A'),(4,'B'),(3,'C'),(5,'D'),(6,'DD'),(1,'S/C');
/*!40000 ALTER TABLE `copa` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `detalletalla`
--

DROP TABLE IF EXISTS `detalletalla`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `detalletalla` (
  `Talla_codigo` varchar(5) COLLATE utf8_spanish_ci NOT NULL,
  `nombre` varchar(10) COLLATE utf8_spanish_ci NOT NULL,
  `orden` smallint(2) NOT NULL,
  PRIMARY KEY (`Talla_codigo`,`nombre`),
  KEY `fk_DetalleTalla_Talla1_idx` (`Talla_codigo`),
  CONSTRAINT `fk_DetalleTalla_Talla1` FOREIGN KEY (`Talla_codigo`) REFERENCES `talla` (`codigo`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `detalletalla`
--

LOCK TABLES `detalletalla` WRITE;
/*!40000 ALTER TABLE `detalletalla` DISABLE KEYS */;
INSERT INTO `detalletalla` VALUES ('T01','00',1),('T01','01',2),('T01','02',3),('T01','03',4),('T01','04',5),('T01','05',6),('T02','0/3',1),('T02','10/12',6),('T02','12/18',7),('T02','14/16',8),('T02','18/24',9),('T02','2/4',10),('T02','3/6',2),('T02','6/8',3),('T02','6/9',4),('T02','9/12',5),('T03','L',3),('T03','M',2),('T03','S',1),('T03','XL',4),('T03','XS',7),('T03','XXL',5),('T03','XXXL',6),('T04','2',1),('T04','3',2),('T04','5',10),('T05','32',1),('T05','34',2),('T05','36',3),('T05','38',4),('T05','40',5),('T05','42',6),('T05','44',7),('T06','52',1),('T06','54',2),('T06','56',3),('T06','L',4),('T06','M',5),('T06','S',6),('T06','XL',7),('T06','XXL',8),('T07','UNI',1),('T17','27/28',1),('T17','29/30',2),('T17','31/32',3),('T17','33/34',4),('T17','35/36',5),('T17','37/38',6),('T17','39/40',7),('T17','41/42',8),('T17','43/44',9),('T17','45/46',10),('T24','28A',1),('T24','30A',2),('T24','32A',3),('T24','34A',4),('T30','10',6),('T30','12',7),('T30','14',8),('T30','16',9),('T30','4',3),('T30','6',4),('T30','8',5),('T32','39/42',1),('T32','43/46',2),('T33','30/34',1),('T33','35/37',2),('T34','30/33',1),('T34','34/36',2),('T34','37/39',3),('T34','40/43',4);
/*!40000 ALTER TABLE `detalletalla` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dpto_prenda`
--

DROP TABLE IF EXISTS `dpto_prenda`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dpto_prenda` (
  `Dpto_codigo` smallint(4) NOT NULL,
  `Prenda_codigo` varchar(5) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`Dpto_codigo`,`Prenda_codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dpto_prenda`
--

LOCK TABLES `dpto_prenda` WRITE;
/*!40000 ALTER TABLE `dpto_prenda` DISABLE KEYS */;
INSERT INTO `dpto_prenda` VALUES (103,'22'),(105,'31'),(106,'01'),(106,'02'),(106,'03'),(106,'06'),(106,'08'),(106,'09'),(106,'12'),(106,'13'),(106,'14'),(106,'16'),(106,'17'),(106,'18'),(106,'21'),(106,'22'),(106,'25'),(106,'34'),(106,'35'),(106,'36'),(106,'37'),(106,'38'),(106,'40'),(106,'41'),(106,'42'),(106,'43'),(106,'46'),(106,'48'),(106,'50'),(108,'03'),(108,'07'),(108,'08'),(108,'10'),(108,'11'),(108,'12'),(108,'22'),(108,'26'),(127,'03'),(127,'08'),(127,'09'),(127,'12'),(127,'22'),(127,'25'),(127,'42'),(128,'03'),(128,'07'),(128,'08'),(128,'10'),(128,'12'),(128,'22'),(129,'08'),(129,'09'),(129,'12'),(129,'22'),(129,'42'),(130,'07'),(130,'08'),(130,'10'),(130,'12'),(130,'22');
/*!40000 ALTER TABLE `dpto_prenda` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dpto_subdpto`
--

DROP TABLE IF EXISTS `dpto_subdpto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dpto_subdpto` (
  `Dpto_codigo` smallint(4) NOT NULL,
  `Subdpto_id` smallint(5) NOT NULL,
  PRIMARY KEY (`Dpto_codigo`,`Subdpto_id`),
  KEY `fk_Dpto_SubDpto_Subdpto1_idx` (`Subdpto_id`),
  CONSTRAINT `fk_Dpto_SubDpto_Subdpto1` FOREIGN KEY (`Subdpto_id`) REFERENCES `subdpto` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dpto_subdpto`
--

LOCK TABLES `dpto_subdpto` WRITE;
/*!40000 ALTER TABLE `dpto_subdpto` DISABLE KEYS */;
INSERT INTO `dpto_subdpto` VALUES (103,8),(105,4),(106,1),(106,2),(106,3),(106,5),(106,6),(106,8),(106,9),(106,10),(106,11),(106,13),(106,14),(106,15),(106,16),(106,18),(108,3),(108,8),(108,13),(108,17),(127,3),(127,5),(127,8),(128,3),(128,8),(128,17),(129,3),(129,5),(129,8),(130,3),(130,8),(130,17),(145,3),(147,12);
/*!40000 ALTER TABLE `dpto_subdpto` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `formacopa`
--

DROP TABLE IF EXISTS `formacopa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `formacopa` (
  `id` tinyint(4) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre_UNIQUE` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `formacopa`
--

LOCK TABLES `formacopa` WRITE;
/*!40000 ALTER TABLE `formacopa` DISABLE KEYS */;
INSERT INTO `formacopa` VALUES (3,'BALCONET'),(4,'BICASCO'),(5,'COBERTURA COMPLETA'),(2,'COPA S'),(7,'MAXI COPA'),(9,'SIN ARCO'),(1,'SIN COPA'),(8,'STRAPLESS'),(6,'TRICASCO');
/*!40000 ALTER TABLE `formacopa` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `grupouso`
--

DROP TABLE IF EXISTS `grupouso`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `grupouso` (
  `id` tinyint(4) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(30) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `grupouso`
--

LOCK TABLES `grupouso` WRITE;
/*!40000 ALTER TABLE `grupouso` DISABLE KEYS */;
INSERT INTO `grupouso` VALUES (1,'NIÑOS'),(2,'SEÑORA'),(3,'MUJER'),(4,'JUVENIL'),(5,'ADULTO'),(6,'HOGAR'),(7,'PROMOTORA'),(8,'INSUMOS'),(9,'ESCOLAR'),(10,'OUTLET'),(11,'INFANTIL'),(12,'EXHIBIDOR');
/*!40000 ALTER TABLE `grupouso` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lista`
--

DROP TABLE IF EXISTS `lista`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lista` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(45) DEFAULT NULL,
  `estado` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lista`
--

LOCK TABLES `lista` WRITE;
/*!40000 ALTER TABLE `lista` DISABLE KEYS */;
/*!40000 ALTER TABLE `lista` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lista_has_usuario`
--

DROP TABLE IF EXISTS `lista_has_usuario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lista_has_usuario` (
  `lista_id` int(10) unsigned NOT NULL,
  `usuario_user` varchar(30) NOT NULL,
  `operacion` varchar(45) DEFAULT NULL,
  `fecha` datetime DEFAULT NULL,
  PRIMARY KEY (`lista_id`,`usuario_user`),
  KEY `fk_lista_has_usuario_usuario1_idx` (`usuario_user`),
  KEY `fk_lista_has_usuario_lista1_idx` (`lista_id`),
  CONSTRAINT `fk_lista_has_usuario_lista1` FOREIGN KEY (`lista_id`) REFERENCES `lista` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_lista_has_usuario_usuario1` FOREIGN KEY (`usuario_user`) REFERENCES `usuario` (`user`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lista_has_usuario`
--

LOCK TABLES `lista_has_usuario` WRITE;
/*!40000 ALTER TABLE `lista_has_usuario` DISABLE KEYS */;
/*!40000 ALTER TABLE `lista_has_usuario` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `marca`
--

DROP TABLE IF EXISTS `marca`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `marca` (
  `id` tinyint(4) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `simbolo` varchar(10) COLLATE utf8_spanish_ci DEFAULT NULL,
  `posicion` varchar(10) COLLATE utf8_spanish_ci DEFAULT NULL,
  `tipo` varchar(45) COLLATE utf8_spanish_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre_UNIQUE` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `marca`
--

LOCK TABLES `marca` WRITE;
/*!40000 ALTER TABLE `marca` DISABLE KEYS */;
INSERT INTO `marca` VALUES (1,'KAYSER',NULL,'','MARCA'),(2,'SIMPSONS','S','INICIO','LICENCIA'),(3,'SENS','S','FIN','MARCA'),(4,'DISNEY','D','INICIO','LICENCIA'),(5,'WALMART','W','FIN','CLIENTE');
/*!40000 ALTER TABLE `marca` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `material`
--

DROP TABLE IF EXISTS `material`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `material` (
  `id` tinyint(4) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(40) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre_UNIQUE` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `material`
--

LOCK TABLES `material` WRITE;
/*!40000 ALTER TABLE `material` DISABLE KEYS */;
INSERT INTO `material` VALUES (1,'ACRILICO'),(2,'ALGODON'),(3,'BAMBOO'),(4,'CARTON'),(5,'CATALOGO'),(6,'CORAL FLEECE'),(7,'CREMA'),(8,'ENCAJE'),(9,'FRAGANCIA'),(10,'FRANELA'),(11,'JACQUARD'),(12,'LIQUIDACION'),(13,'MICROFIBRA'),(14,'MODAL'),(15,'PLASTICO'),(16,'PLUSH'),(17,'POLAR'),(18,'POLIAMIDA'),(19,'POLIESTER'),(20,'PUÑO'),(21,'PVC'),(22,'SATIN'),(23,'SIN COSTURA'),(24,'STOCK LOT'),(25,'TOALLA'),(26,'TREVIRA'),(27,'TULL'),(28,'VISCOSA');
/*!40000 ALTER TABLE `material` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `prenda_categoria`
--

DROP TABLE IF EXISTS `prenda_categoria`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `prenda_categoria` (
  `Prenda_codigo` varchar(5) COLLATE utf8_spanish_ci NOT NULL,
  `Categoria_codigo` varchar(5) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`Prenda_codigo`,`Categoria_codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `prenda_categoria`
--

LOCK TABLES `prenda_categoria` WRITE;
/*!40000 ALTER TABLE `prenda_categoria` DISABLE KEYS */;
INSERT INTO `prenda_categoria` VALUES ('01','74'),('02','26'),('02','29'),('02','67'),('03','05'),('03','06'),('06','31'),('06','70'),('07','05'),('07','06'),('08','05'),('08','06'),('08','07'),('09','03'),('09','08'),('09','19'),('09','24'),('09','37'),('09','45'),('09','54'),('09','55'),('09','62'),('10','55'),('10','56'),('10','79'),('11','06'),('12','51'),('12','52'),('12','68'),('13','28'),('13','30'),('14','34'),('15','44'),('16','42'),('17','22'),('17','40'),('18','66'),('21','39'),('21','49'),('22','01'),('22','04'),('22','20'),('22','41'),('25','02'),('25','03'),('25','07'),('25','14'),('25','33'),('25','38'),('25','54'),('25','57'),('25','60'),('25','63'),('25','65'),('25','69'),('25','72'),('25','73'),('26','78'),('28','77'),('31','09'),('31','21'),('33','19'),('33','32'),('33','71'),('33','75'),('34','18'),('34','46'),('35','74'),('36','47'),('37','39'),('37','76'),('38','43'),('39','06'),('40','15'),('41','51'),('41','52'),('42','26'),('42','51'),('42','52'),('43','27'),('45','35'),('46','36'),('47','09'),('47','10'),('47','11'),('47','12'),('47','13'),('47','16'),('47','17'),('47','25'),('47','50'),('47','53'),('47','58'),('47','59'),('48','48'),('49','61'),('50','64');
/*!40000 ALTER TABLE `prenda_categoria` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `prenda_copa`
--

DROP TABLE IF EXISTS `prenda_copa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `prenda_copa` (
  `Prenda_codigo` varchar(5) COLLATE utf8_spanish_ci NOT NULL,
  `Copa_id` tinyint(4) unsigned NOT NULL,
  PRIMARY KEY (`Prenda_codigo`,`Copa_id`),
  KEY `fk_Prenda_Copa_Copa1_idx` (`Copa_id`),
  CONSTRAINT `fk_Prenda_Copa_Copa1` FOREIGN KEY (`Copa_id`) REFERENCES `copa` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `prenda_copa`
--

LOCK TABLES `prenda_copa` WRITE;
/*!40000 ALTER TABLE `prenda_copa` DISABLE KEYS */;
INSERT INTO `prenda_copa` VALUES ('13',3),('25',1),('25',2),('25',3),('25',4),('25',5),('25',6);
/*!40000 ALTER TABLE `prenda_copa` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `prenda_formacopa`
--

DROP TABLE IF EXISTS `prenda_formacopa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `prenda_formacopa` (
  `Prenda_codigo` varchar(5) COLLATE utf8_spanish_ci NOT NULL,
  `FormaCopa_id` tinyint(4) unsigned NOT NULL,
  PRIMARY KEY (`Prenda_codigo`,`FormaCopa_id`),
  KEY `fk_Prenda_FormaCopa_FormaCopa1_idx` (`FormaCopa_id`),
  CONSTRAINT `fk_Prenda_FormaCopa_FormaCopa1` FOREIGN KEY (`FormaCopa_id`) REFERENCES `formacopa` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `prenda_formacopa`
--

LOCK TABLES `prenda_formacopa` WRITE;
/*!40000 ALTER TABLE `prenda_formacopa` DISABLE KEYS */;
INSERT INTO `prenda_formacopa` VALUES ('25',1),('25',2),('25',3),('25',4),('25',5),('25',6),('25',7),('25',8),('25',9);
/*!40000 ALTER TABLE `prenda_formacopa` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `prenda_talla`
--

DROP TABLE IF EXISTS `prenda_talla`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `prenda_talla` (
  `Prenda_codigo` varchar(5) COLLATE utf8_spanish_ci NOT NULL,
  `Talla_codigo` varchar(5) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`Prenda_codigo`,`Talla_codigo`),
  KEY `fk_Prenda_Talla_Talla1_idx` (`Talla_codigo`),
  CONSTRAINT `fk_Prenda_Talla_Talla1` FOREIGN KEY (`Talla_codigo`) REFERENCES `talla` (`codigo`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `prenda_talla`
--

LOCK TABLES `prenda_talla` WRITE;
/*!40000 ALTER TABLE `prenda_talla` DISABLE KEYS */;
INSERT INTO `prenda_talla` VALUES ('01','T07'),('02','T03'),('02','T07'),('03','T02'),('03','T03'),('06','T03'),('07','T03'),('07','T07'),('07','T30'),('08','T04'),('08','T07'),('08','T17'),('08','T32'),('08','T33'),('08','T34'),('09','T03'),('09','T06'),('09','T07'),('09','T30'),('10','T03'),('10','T30'),('11','T03'),('11','T30'),('12','T03'),('12','T07'),('12','T30'),('13','T03'),('13','T05'),('14','T03'),('14','T05'),('15','T07'),('16','T07'),('17','T03'),('17','T06'),('18','T07'),('20','T07'),('21','T03'),('21','T04'),('21','T07'),('22','T02'),('22','T03'),('22','T07'),('22','T30'),('25','T03'),('25','T05'),('25','T07'),('25','T24'),('25','T30'),('26','T17'),('28','T07'),('31','T07'),('32','T07'),('32','T17'),('32','T24'),('32','T30'),('33','T03'),('34','T03'),('34','T07'),('35','T07'),('36','T07'),('37','T07'),('38','T07'),('39','T33'),('39','T34'),('40','T03'),('41','T03'),('41','T06'),('41','T07'),('42','T03'),('42','T06'),('42','T30'),('43','T03'),('45','T07'),('46','T07'),('47','T03'),('47','T05'),('47','T07'),('48','T07'),('49','T03'),('49','T07'),('50','T03');
/*!40000 ALTER TABLE `prenda_talla` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `presentacion`
--

DROP TABLE IF EXISTS `presentacion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `presentacion` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(10) COLLATE utf8_spanish_ci NOT NULL,
  `abreviatura` varchar(15) COLLATE utf8_spanish_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `presentacion`
--

LOCK TABLES `presentacion` WRITE;
/*!40000 ALTER TABLE `presentacion` DISABLE KEYS */;
INSERT INTO `presentacion` VALUES (1,'UNITARIO',''),(2,'BIPACK','P2'),(3,'TRIPACK','P3'),(4,'CATALOGO','CATA'),(5,'PROMOCION','PROM'),(6,'PACK 5','P5'),(7,'PACK','');
/*!40000 ALTER TABLE `presentacion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `relacionprefijo`
--

DROP TABLE IF EXISTS `relacionprefijo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `relacionprefijo` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Dpto_codigo` smallint(4) unsigned NOT NULL,
  `SubDpto_id` smallint(5) unsigned DEFAULT NULL,
  `Prenda_codigo` varchar(5) COLLATE utf8_spanish_ci DEFAULT NULL,
  `Categoria_codigo` varchar(5) COLLATE utf8_spanish_ci DEFAULT NULL,
  `prefijo` varchar(25) COLLATE utf8_spanish_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=78 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `relacionprefijo`
--

LOCK TABLES `relacionprefijo` WRITE;
/*!40000 ALTER TABLE `relacionprefijo` DISABLE KEYS */;
INSERT INTO `relacionprefijo` VALUES (1,103,NULL,'22','','0'),(2,105,NULL,'31','','CATA'),(3,106,NULL,'01','','AC'),(4,106,NULL,'02','','73'),(5,106,NULL,'03','','78'),(6,106,NULL,'06','','150'),(7,106,NULL,'08','','99M'),(8,106,NULL,'09','55','10'),(9,106,NULL,'09','45','11'),(10,106,NULL,'09','24','12'),(11,106,NULL,'09','8','13'),(12,106,NULL,'09','62','14'),(13,106,11,'09','','118'),(14,106,NULL,'12','52','40'),(15,106,NULL,'12','68','41'),(16,106,9,'12','','141'),(17,106,NULL,'13','','180'),(18,106,NULL,'14','','32'),(19,106,NULL,'16','','AC'),(20,106,NULL,'17','','19'),(21,106,NULL,'18','','AC'),(22,106,NULL,'21','','101'),(23,106,NULL,'22','04','70'),(24,106,NULL,'22','01','60'),(25,106,NULL,'25','','50'),(26,106,9,'25','','150'),(27,106,14,'34','','101'),(28,106,NULL,'35','','AC'),(29,106,NULL,'36','','AC'),(30,106,NULL,'37','','AC'),(31,106,NULL,'38','','AC'),(32,106,NULL,'40','','56'),(33,106,NULL,'41','52','61'),(34,106,NULL,'41','51','71'),(35,106,NULL,'42','52','61'),(36,106,NULL,'42','51','71'),(37,106,NULL,'43','','160'),(38,106,NULL,'46','','AC'),(39,106,NULL,'48','','AC'),(40,106,NULL,'50','','52'),(41,108,NULL,'03','','79'),(42,108,NULL,'07','','93'),(43,108,NULL,'08','','99H'),(44,108,NULL,'10','','91'),(45,108,NULL,'11','','98'),(46,108,NULL,'12','','40'),(47,108,NULL,'22','','67'),(48,108,NULL,'26','','ZH'),(49,127,NULL,'03','','69'),(50,127,NULL,'08','','99NP'),(51,127,NULL,'09','08','16'),(52,127,NULL,'09','62','15'),(53,127,NULL,'12','','45'),(54,127,NULL,'22','01','65'),(55,127,NULL,'22','04','75'),(56,127,NULL,'25','','51'),(57,127,NULL,'25','63','25'),(58,127,NULL,'42','','75'),(59,128,NULL,'03','','69'),(60,128,NULL,'07','','97'),(61,128,NULL,'08','','99NP'),(62,128,NULL,'10','','95'),(63,128,NULL,'12','','45'),(64,128,NULL,'22','01','66'),(65,128,NULL,'22','04','76'),(66,129,NULL,'08','','99NP'),(67,129,NULL,'09','','17'),(68,129,NULL,'12','','47'),(69,129,NULL,'22','04','73'),(70,129,NULL,'22','01','63'),(71,129,NULL,'42','','73'),(72,130,NULL,'07','','97'),(73,130,NULL,'08','','99NP'),(74,130,NULL,'10','','97'),(75,130,NULL,'12','','47'),(76,130,NULL,'22','04','74'),(77,130,NULL,'22','01','64');
/*!40000 ALTER TABLE `relacionprefijo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sku`
--

DROP TABLE IF EXISTS `sku`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sku` (
  `codigo` varchar(45) NOT NULL,
  `articulo_codigo` varchar(45) NOT NULL,
  `barcode` varchar(15) NOT NULL,
  `color_code` int(11) DEFAULT NULL,
  `color_name` varchar(45) DEFAULT NULL,
  `talla_name` varchar(10) DEFAULT NULL,
  `talla_orden` int(11) DEFAULT NULL,
  `copa` varchar(5) DEFAULT NULL,
  `fcopa` varchar(5) DEFAULT NULL,
  PRIMARY KEY (`codigo`),
  UNIQUE KEY `barcode_UNIQUE` (`barcode`),
  KEY `articulo_codigo_idx` (`articulo_codigo`),
  CONSTRAINT `articulo_codigo` FOREIGN KEY (`articulo_codigo`) REFERENCES `articulo` (`codigo`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sku`
--

LOCK TABLES `sku` WRITE;
/*!40000 ALTER TABLE `sku` DISABLE KEYS */;
/*!40000 ALTER TABLE `sku` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `skucreated`
--

DROP TABLE IF EXISTS `skucreated`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `skucreated` (
  `sku` varchar(30) COLLATE utf8_spanish_ci NOT NULL,
  `fecha` date DEFAULT NULL,
  `user_creacion` varchar(25) COLLATE utf8_spanish_ci DEFAULT NULL,
  `user_revision` varchar(45) COLLATE utf8_spanish_ci DEFAULT NULL,
  `user_carga` varchar(45) COLLATE utf8_spanish_ci DEFAULT NULL,
  PRIMARY KEY (`sku`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `skucreated`
--

LOCK TABLES `skucreated` WRITE;
/*!40000 ALTER TABLE `skucreated` DISABLE KEYS */;
/*!40000 ALTER TABLE `skucreated` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `skuupdated`
--

DROP TABLE IF EXISTS `skuupdated`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `skuupdated` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `fecha` date DEFAULT NULL,
  `campo` varchar(45) COLLATE utf8_spanish_ci DEFAULT NULL,
  `valor_inicial` varchar(45) COLLATE utf8_spanish_ci DEFAULT NULL,
  `valor_final` varchar(45) COLLATE utf8_spanish_ci DEFAULT NULL,
  `usuario_edicion` varchar(25) COLLATE utf8_spanish_ci DEFAULT NULL,
  `usuario_revision` varchar(45) COLLATE utf8_spanish_ci DEFAULT NULL,
  `usuario_carga` varchar(45) COLLATE utf8_spanish_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `skuupdated`
--

LOCK TABLES `skuupdated` WRITE;
/*!40000 ALTER TABLE `skuupdated` DISABLE KEYS */;
/*!40000 ALTER TABLE `skuupdated` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `subdpto`
--

DROP TABLE IF EXISTS `subdpto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `subdpto` (
  `id` smallint(5) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `subdpto`
--

LOCK TABLES `subdpto` WRITE;
/*!40000 ALTER TABLE `subdpto` DISABLE KEYS */;
INSERT INTO `subdpto` VALUES (1,'ACCESORIOS'),(2,'BABY DOLL'),(3,'CALCETINES'),(4,'CATALOGO'),(5,'CORSETERIA'),(6,'ENAGUAS'),(7,'INSUMOS'),(8,'LENCERIA'),(9,'LINEA CONTROL'),(10,'LINEA MODELADORA'),(11,'MATERNAL'),(12,'OUTLET'),(13,'PANTUFLAS'),(14,'PANTYS'),(15,'PERFUMERIA'),(16,'ROPA DEPORTIVA'),(17,'ROPA INTERIOR'),(18,'TRAJE DE BAÑO');
/*!40000 ALTER TABLE `subdpto` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `subdpto_prenda`
--

DROP TABLE IF EXISTS `subdpto_prenda`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `subdpto_prenda` (
  `Subdpto_id` smallint(5) NOT NULL,
  `Prenda_codigo` varchar(5) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`Subdpto_id`,`Prenda_codigo`),
  KEY `fk_SubDpto_Prenda_Subdpto1_idx` (`Subdpto_id`),
  CONSTRAINT `fk_SubDpto_Prenda_Subdpto1` FOREIGN KEY (`Subdpto_id`) REFERENCES `subdpto` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `subdpto_prenda`
--

LOCK TABLES `subdpto_prenda` WRITE;
/*!40000 ALTER TABLE `subdpto_prenda` DISABLE KEYS */;
INSERT INTO `subdpto_prenda` VALUES (1,'01'),(1,'18'),(1,'25'),(1,'28'),(1,'35'),(1,'36'),(1,'37'),(1,'38'),(1,'46'),(2,'02'),(3,'08'),(3,'39'),(4,'31'),(5,'06'),(5,'09'),(5,'14'),(5,'25'),(6,'17'),(7,'47'),(8,'03'),(8,'22'),(8,'41'),(8,'42'),(9,'06'),(9,'13'),(9,'25'),(9,'43'),(10,'09'),(11,'09'),(11,'25'),(12,'49'),(13,'26'),(14,'21'),(14,'34'),(15,'15'),(15,'16'),(15,'45'),(15,'48'),(16,'34'),(16,'40'),(16,'50'),(17,'07'),(17,'10'),(17,'11'),(18,'33');
/*!40000 ALTER TABLE `subdpto_prenda` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `talla`
--

DROP TABLE IF EXISTS `talla`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `talla` (
  `codigo` varchar(5) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `talla`
--

LOCK TABLES `talla` WRITE;
/*!40000 ALTER TABLE `talla` DISABLE KEYS */;
INSERT INTO `talla` VALUES ('T01'),('T02'),('T03'),('T04'),('T05'),('T06'),('T07'),('T17'),('T24'),('T30'),('T32'),('T33'),('T34');
/*!40000 ALTER TABLE `talla` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tcatalogo`
--

DROP TABLE IF EXISTS `tcatalogo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tcatalogo` (
  `id` tinyint(4) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre_UNIQUE` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tcatalogo`
--

LOCK TABLES `tcatalogo` WRITE;
/*!40000 ALTER TABLE `tcatalogo` DISABLE KEYS */;
INSERT INTO `tcatalogo` VALUES (1,'DESCONTINUADO'),(2,'EVD'),(3,'EVD/COL'),(4,'INV17'),(5,'INV18'),(6,'LIMAS'),(7,'OUTLET'),(8,'STOCK LOT'),(9,'VER17'),(10,'VER18');
/*!40000 ALTER TABLE `tcatalogo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tprenda`
--

DROP TABLE IF EXISTS `tprenda`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tprenda` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(15) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre_UNIQUE` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tprenda`
--

LOCK TABLES `tprenda` WRITE;
/*!40000 ALTER TABLE `tprenda` DISABLE KEYS */;
INSERT INTO `tprenda` VALUES (1,'INVIERNO'),(3,'TODA TEMPORADA'),(2,'VERANO');
/*!40000 ALTER TABLE `tprenda` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuario`
--

DROP TABLE IF EXISTS `usuario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usuario` (
  `user` varchar(30) NOT NULL,
  `password` varchar(45) NOT NULL,
  `perfil` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario`
--

LOCK TABLES `usuario` WRITE;
/*!40000 ALTER TABLE `usuario` DISABLE KEYS */;
INSERT INTO `usuario` VALUES ('admin','12345','admin'),('aobando','aobando','admin'),('cmarino','cmarino','reviser'),('comex','comex','editor'),('editor','12345','editor'),('emonsalves','emonsalves','admin'),('fmunoz','fmunoz','reviser'),('gpassi','gpassi','reviser'),('informatica','12345','admin'),('janais','janais','editor'),('jbisquertt','jbisquertt','editor'),('ldelteil','ldelteil','editor'),('mbustos             ','mbustos             ','editor'),('mgiraldo','mgiraldo','admin'),('mmora','mmora','admin'),('mpasten','mpasten','editor'),('mvera','mvera','admin'),('reviser','12345','reviser'),('rriquelme','rriquelme','editor'),('smolina','smolina','editor'),('ssalas','ssalas','reviser');
/*!40000 ALTER TABLE `usuario` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2017-11-28 18:17:01
