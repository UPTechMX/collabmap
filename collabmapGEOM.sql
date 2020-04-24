-- MariaDB dump 10.17  Distrib 10.4.11-MariaDB, for osx10.10 (x86_64)
--
-- Host: localhost    Database: collabmap
-- ------------------------------------------------------
-- Server version	10.4.11-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `Areas`
--

DROP TABLE IF EXISTS `Areas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Areas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bloquesId` int(11) DEFAULT NULL,
  `nombre` varchar(45) DEFAULT NULL,
  `orden` tinyint(4) DEFAULT NULL,
  `elim` tinyint(4) DEFAULT NULL,
  `identificador` varchar(45) DEFAULT NULL,
  `valMax` double DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_Areas_Bloques1_idx` (`bloquesId`),
  KEY `aIdentificadorIndex` (`identificador`),
  CONSTRAINT `fk_Areas_Bloques1` FOREIGN KEY (`bloquesId`) REFERENCES `Bloques` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Areas`
--

LOCK TABLES `Areas` WRITE;
/*!40000 ALTER TABLE `Areas` DISABLE KEYS */;
INSERT INTO `Areas` VALUES (5,5,'One time',1,NULL,'a_5_5_5',100),(6,6,'WEEKLY RW MONITORING',1,NULL,'a_6_6_6',100),(7,7,'MONTHLY RW MONITORING',1,NULL,'a_7_7_7',100),(16,16,'T2',1,NULL,'a_16_16_16',100),(18,18,'Status',1,NULL,'a_18_18_18',100),(19,19,'Food',1,NULL,'a_18_19_19',100),(20,20,'Personnel transportation',1,NULL,'a_18_20_20',100),(22,22,'Supply monitoring',1,NULL,'a_20_22_22',100),(23,23,'Location',1,NULL,'a_21_23_23',100),(24,24,'Family members',1,NULL,'a_21_24_24',100),(25,25,'Symptom',1,NULL,'a_21_25_25',100),(26,27,'Daily monitoring',1,NULL,'a_22_27_26',NULL),(27,27,'Disinfectant spraying',2,NULL,'a_22_27_27',NULL),(28,28,'Migration data',1,NULL,'a_23_28_28',NULL),(29,29,'Employment Monitoring',1,NULL,'a_24_29_29',NULL),(30,30,'Local businesses',1,NULL,'a_25_30_30',NULL),(31,31,'Water',1,NULL,'a_26_31_31',NULL),(32,31,'Waste',3,NULL,'a_26_31_32',NULL),(33,31,'Electricity',2,NULL,'a_26_31_33',NULL),(34,32,'Hospital',1,NULL,'a_27_32_34',NULL),(35,33,'National Social Security Programs ',1,NULL,'a_28_33_35',NULL),(36,33,'Local Government Programs',2,NULL,'a_28_33_36',NULL),(37,34,'Migration',1,NULL,'a_29_34_37',NULL),(38,35,'Food security',1,NULL,'a_29_35_38',NULL),(39,36,'Arriving',1,NULL,'a_30_36_39',NULL),(40,36,'Leaving',2,NULL,'a_30_36_40',NULL),(41,37,'Food security',1,NULL,'a_30_37_41',NULL),(42,38,'Community Self-Organisation',1,NULL,'a_31_38_42',NULL),(43,39,'Community Self-Organisation',1,NULL,'a_32_39_43',NULL);
/*!40000 ALTER TABLE `Areas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Bloques`
--

DROP TABLE IF EXISTS `Bloques`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Bloques` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `checklistId` int(11) DEFAULT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `orden` tinyint(4) DEFAULT NULL,
  `elim` tinyint(4) DEFAULT NULL,
  `identificador` varchar(45) DEFAULT NULL,
  `encabezado` tinyint(4) DEFAULT NULL,
  `tipoProm` tinyint(4) DEFAULT NULL,
  `valMax` double DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_Bloques_Checklist1_idx` (`checklistId`),
  KEY `bIdentificadorIndex` (`identificador`),
  CONSTRAINT `fk_Bloques_Checklist1` FOREIGN KEY (`checklistId`) REFERENCES `Checklist` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Bloques`
--

LOCK TABLES `Bloques` WRITE;
/*!40000 ALTER TABLE `Bloques` DISABLE KEYS */;
INSERT INTO `Bloques` VALUES (5,5,'One time',1,NULL,'b_5_5',0,1,100),(6,6,'WEEKLY RW MONITORING',1,NULL,'b_6_6',0,1,100),(7,7,'MONTHLY RW MONITORING',1,NULL,'b_7_7',0,1,100),(16,16,'T1',1,NULL,'b_16_16',0,1,100),(18,18,'Capacity',1,NULL,'b_18_18',0,1,100),(19,18,'Food',2,NULL,'b_18_19',0,1,100),(20,18,'Personnel transportation',3,NULL,'b_18_20',0,1,100),(22,20,'Markets supply',1,NULL,'b_20_22',0,1,100),(23,21,'Location',1,NULL,'b_21_23',0,1,100),(24,21,'Family members',2,NULL,'b_21_24',0,1,100),(25,21,'Symptom',3,NULL,'b_21_25',0,1,100),(26,16,'Health',2,NULL,'b_16_26',0,1,100),(27,22,'Daily monitoring ',1,NULL,'b_22_27',0,1,NULL),(28,23,'Migration data',1,NULL,'b_23_28',0,1,NULL),(29,24,'Employment monitoring',1,NULL,'b_24_29',0,1,NULL),(30,25,'Local businesses',1,NULL,'b_25_30',0,1,NULL),(31,26,'Shortage in public services',1,NULL,'b_26_31',0,1,NULL),(32,27,'Hospital',1,NULL,'b_27_32',0,1,NULL),(33,28,'Social Security Assistance',1,NULL,'b_28_33',0,1,NULL),(34,29,'Migration',1,1,'b_29_34',0,1,NULL),(35,29,'Food security',2,1,'b_29_35',0,1,NULL),(36,30,'Migration (in-out)',1,NULL,'b_30_36',0,1,NULL),(37,30,'Food security',2,NULL,'b_30_37',0,1,NULL),(38,31,'Community Self-Organisation',1,NULL,'b_31_38',0,1,NULL),(39,32,'Community Self-Organisation',1,NULL,'b_32_39',0,1,NULL);
/*!40000 ALTER TABLE `Bloques` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `CalculosVisita`
--

DROP TABLE IF EXISTS `CalculosVisita`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `CalculosVisita` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `visitasId` int(11) DEFAULT NULL,
  `total` varchar(20) DEFAULT NULL,
  `bloque` varchar(255) DEFAULT NULL,
  `bloqueCalif` varchar(20) DEFAULT NULL,
  `area` varchar(255) DEFAULT NULL,
  `areaCalif` varchar(20) DEFAULT NULL,
  `bloqueNom` varchar(255) DEFAULT NULL,
  `areaNom` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_CalculosVisita_Visitas1_idx` (`visitasId`),
  CONSTRAINT `fk_CalculosVisita_Visitas1` FOREIGN KEY (`visitasId`) REFERENCES `Visitas` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=107 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `CalculosVisita`
--

LOCK TABLES `CalculosVisita` WRITE;
/*!40000 ALTER TABLE `CalculosVisita` DISABLE KEYS */;
INSERT INTO `CalculosVisita` VALUES (17,58,'-','b_5_5','-','a_5_5_5','-','One time','One time'),(18,59,'16','b_6_6','16','a_6_6_6','16','WEEKLY DUSUN MONITORING','WEEKLY DUSUN MONITORING'),(19,60,'-','b_7_7','-','a_7_7_7','-','MONTHLY DUSUN MONITORING','MONTHLY DUSUN MONITORING'),(20,69,'3','b_7_7','3','a_7_7_7','3','MONTHLY RW MONITORING','MONTHLY RW MONITORING'),(21,70,'-','b_5_5','-','a_5_5_5','-','One time','One time'),(22,71,'-','b_5_5','-','a_5_5_5','-','One time','One time'),(23,72,'1000','b_6_6','1000','a_6_6_6','1000','WEEKLY RW MONITORING','WEEKLY RW MONITORING'),(24,76,'-','b_5_5','-','a_5_5_5','-','One time','One time'),(28,84,'24.5','b_18_18','48','a_18_18_18','48','Capacity','Status'),(29,84,'24.5','b_18_19','1','a_18_19_19','1','Food','Food'),(30,84,'24.5','b_18_20','1','a_18_20_20','1','Personnel transportation','Personnel transportation'),(31,87,'30.75','b_18_18','60.5','a_18_18_18','60.5','Capacity','Status'),(32,87,'30.75','b_18_19','1','a_18_19_19','1','Food','Food'),(33,87,'30.75','b_18_20','1','a_18_20_20','1','Personnel transportation','Personnel transportation'),(34,89,'20.75','b_18_18','40.5','a_18_18_18','40.5','Capacity','Status'),(35,89,'20.75','b_18_19','1','a_18_19_19','1','Food','Food'),(36,89,'20.75','b_18_20','1','a_18_20_20','1','Personnel transportation','Personnel transportation'),(37,90,'0.6','b_21_23','-','a_21_23_23','-','Location','Location'),(38,90,'0.6','b_21_24','2.3333333333333','a_21_24_24','2.3333333333333','Family members','Family members'),(39,90,'0.6','b_21_25','0.29411764705882','a_21_25_25','0.29411764705882','Symptom','Symptom'),(40,95,'0.4','b_21_23','-','a_21_23_23','-','Location','Location'),(41,95,'0.4','b_21_24','2.6666666666667','a_21_24_24','2.6666666666667','Family members','Family members'),(42,95,'0.4','b_21_25','0','a_21_25_25','0','Symptom','Symptom'),(72,128,'590.5','b_27_32','590.5','a_27_32_34','590.5','Hospital','Hospital'),(73,129,'157.5','b_27_32','157.5','a_27_32_34','157.5','Hospital','Hospital'),(74,130,'149','b_27_32','149','a_27_32_34','149','Hospital','Hospital'),(75,131,'111','b_27_32','111','a_27_32_34','111','Hospital','Hospital'),(76,132,'90','b_27_32','90','a_27_32_34','90','Hospital','Hospital'),(77,133,'123','b_27_32','123','a_27_32_34','123','Hospital','Hospital'),(78,134,'400','b_27_32','400','a_27_32_34','400','Hospital','Hospital'),(79,135,'77','b_27_32','77','a_27_32_34','77','Hospital','Hospital'),(80,136,'210','b_27_32','210','a_27_32_34','210','Hospital','Hospital'),(81,137,'3','b_27_32','3','a_27_32_34','3','Hospital','Hospital'),(82,138,'207','b_27_32','207','a_27_32_34','207','Hospital','Hospital'),(83,139,'-','b_27_32','-','a_27_32_34','-','Hospital','Hospital'),(84,140,'33','b_27_32','33','a_27_32_34','33','Hospital','Hospital'),(85,141,'55.5','b_27_32','55.5','a_27_32_34','55.5','Hospital','Hospital'),(86,142,'148','b_27_32','148','a_27_32_34','148','Hospital','Hospital'),(87,143,'80','b_27_32','80','a_27_32_34','80','Hospital','Hospital'),(88,144,'70','b_27_32','70','a_27_32_34','70','Hospital','Hospital'),(89,145,'60.5','b_27_32','60.5','a_27_32_34','60.5','Hospital','Hospital'),(90,146,'60','b_27_32','60','a_27_32_34','60','Hospital','Hospital'),(91,147,'17','b_27_32','17','a_27_32_34','17','Hospital','Hospital'),(92,148,'-','b_27_32','-','a_27_32_34','-','Hospital','Hospital'),(93,149,'25','b_27_32','25','a_27_32_34','25','Hospital','Hospital'),(94,150,'25','b_27_32','25','a_27_32_34','25','Hospital','Hospital'),(95,151,'26','b_27_32','26','a_27_32_34','26','Hospital','Hospital'),(96,152,'43','b_27_32','43','a_27_32_34','43','Hospital','Hospital'),(97,153,'25','b_27_32','25','a_27_32_34','25','Hospital','Hospital'),(98,154,'25','b_27_32','25','a_27_32_34','25','Hospital','Hospital'),(99,155,'7','b_27_32','7','a_27_32_34','7','Hospital','Hospital'),(100,156,'4','b_27_32','4','a_27_32_34','4','Hospital','Hospital'),(101,161,'-','b_30_36','-','a_30_36_39','-','Migration (in-out)','Arriving'),(102,161,'-','b_30_36','-','a_30_36_40','-','Migration (in-out)','Leaving'),(103,161,'-','b_30_37','-','a_30_37_41','-','Food security','Food security'),(104,163,'-','b_30_36','-','a_30_36_39','-','Migration (in-out)','Arriving'),(105,163,'-','b_30_36','-','a_30_36_40','-','Migration (in-out)','Leaving'),(106,163,'-','b_30_37','-','a_30_37_41','-','Food security','Food security');
/*!40000 ALTER TABLE `CalculosVisita` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Categories`
--

DROP TABLE IF EXISTS `Categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `preguntasId` int(11) DEFAULT NULL,
  `name` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_Categorias_Preguntas1_idx` (`preguntasId`),
  CONSTRAINT `fk_Categorias_Preguntas1` FOREIGN KEY (`preguntasId`) REFERENCES `Preguntas` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Categories`
--

LOCK TABLES `Categories` WRITE;
/*!40000 ALTER TABLE `Categories` DISABLE KEYS */;
INSERT INTO `Categories` VALUES (1,169,'BPNT/Rastra'),(11,171,'PKH (Conditional Cash Transfer)'),(13,173,'PKT (Cash for Work)'),(14,175,'BLT (Direct Cash Transfer)'),(15,177,'Direct food assistance '),(16,179,'Sanitizer and face masks'),(17,188,'Store'),(18,188,'Market');
/*!40000 ALTER TABLE `Categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Checklist`
--

DROP TABLE IF EXISTS `Checklist`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Checklist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(200) DEFAULT NULL,
  `siglas` varchar(20) DEFAULT NULL,
  `elim` tinyint(4) DEFAULT NULL,
  `tipo` varchar(30) DEFAULT NULL,
  `resumen` text DEFAULT NULL,
  `tipoProm` tinyint(4) DEFAULT NULL,
  `tipoAnalisis` tinyint(4) DEFAULT NULL,
  `listaFotos` text DEFAULT NULL,
  `photos` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Checklist`
--

LOCK TABLES `Checklist` WRITE;
/*!40000 ALTER TABLE `Checklist` DISABLE KEYS */;
INSERT INTO `Checklist` VALUES (5,'RW OT','ROT',NULL,NULL,NULL,NULL,NULL,NULL,NULL),(6,'WEEKLY RW MONITORING','WRM',NULL,NULL,NULL,NULL,NULL,NULL,NULL),(7,'MONTHLY RW MONITORING','MDM',NULL,NULL,NULL,NULL,NULL,NULL,NULL),(16,'Test','TST',NULL,NULL,NULL,NULL,NULL,NULL,NULL),(18,'Hospital capacity','Hospital_capacity',NULL,NULL,NULL,NULL,NULL,NULL,NULL),(19,'s','s',NULL,NULL,NULL,NULL,NULL,NULL,NULL),(20,'Markets','markets',NULL,NULL,NULL,NULL,NULL,NULL,NULL),(21,'COVID19 Symptom monitoring','symptom_monitoring',NULL,NULL,NULL,NULL,NULL,NULL,NULL),(22,'COVID-19 Monitoring for Puskesmas','COVID19',NULL,NULL,NULL,NULL,NULL,NULL,0),(23,'Migration Monitoring','MIG',NULL,NULL,NULL,NULL,NULL,NULL,0),(24,'Employment Monitoring','EMP',NULL,NULL,NULL,NULL,NULL,NULL,0),(25,'Local businesses','LOCAL',NULL,NULL,NULL,NULL,NULL,NULL,0),(26,'Shortage in public services','PS01',NULL,NULL,NULL,NULL,NULL,NULL,0),(27,'Hospitals Import','HI',NULL,NULL,NULL,NULL,NULL,NULL,0),(28,'Social Security Assistance Monitoring','SSA',NULL,NULL,NULL,NULL,NULL,NULL,0),(29,'RW Weekly Monitoring','asd',NULL,NULL,NULL,NULL,NULL,NULL,0),(30,'RT Weekly Monitoring','RT-W',NULL,NULL,NULL,NULL,NULL,NULL,0),(31,'Monthly RT Questionnare','RT-M',NULL,NULL,NULL,NULL,NULL,NULL,0),(32,'RT Monthly Questionnaire','RT-M',NULL,NULL,NULL,NULL,NULL,NULL,0);
/*!40000 ALTER TABLE `Checklist` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ChecklistEst`
--

DROP TABLE IF EXISTS `ChecklistEst`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ChecklistEst` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `checklistId` int(11) DEFAULT NULL,
  `estructura` mediumtext DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `chkEstUniq` (`checklistId`),
  CONSTRAINT `fk_ChecklistEst_Checklist1` FOREIGN KEY (`checklistId`) REFERENCES `Checklist` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ChecklistEst`
--

LOCK TABLES `ChecklistEst` WRITE;
/*!40000 ALTER TABLE `ChecklistEst` DISABLE KEYS */;
INSERT INTO `ChecklistEst` VALUES (24,18,'{\"tipoProm\":1,\"tipoAnalisis\":1,\"conds\":[],\"bloques\":{\"b_18_18\":{\"max\":0,\"valor\":0,\"muestra\":1,\"nombre\":\"Capacity\",\"tipoProm\":1,\"valMax\":100,\"encabezado\":0,\"areas\":{\"a_18_18_18\":{\"max\":0,\"valor\":0,\"muestra\":1,\"nombre\":\"Status\",\"valMax\":100,\"preguntas\":{\"p_18_18_18_94\":{\"puntos\":1,\"orden\":1,\"muestra\":1,\"identificador\":\"p_18_18_18_94\",\"influyeValor\":1,\"tipo\":\"mult\",\"id\":94,\"pregunta\":\"Select the facility\",\"respuestas\":{\"1\":[{\"id\":40,\"preguntasId\":94,\"respuesta\":\"Hospital A\",\"valor\":\"1\",\"identificador\":\"r_18_18_18_94_140\",\"orden\":1,\"elim\":null,\"justif\":0}],\"2\":[{\"id\":41,\"preguntasId\":94,\"respuesta\":\"Hospital B\",\"valor\":\"2\",\"identificador\":\"r_18_18_18_94_241\",\"orden\":2,\"elim\":null,\"justif\":0}]},\"justif\":0,\"comShopper\":\"\",\"comVerif\":null,\"conds\":[]},\"p_18_18_18_95\":{\"puntos\":1,\"orden\":2,\"muestra\":1,\"identificador\":\"p_18_18_18_95\",\"influyeValor\":1,\"tipo\":\"num\",\"id\":95,\"pregunta\":\"Capacity\",\"respuestas\":[],\"justif\":0,\"comShopper\":\"Please select the percentage of capacity\",\"comVerif\":null,\"conds\":[]},\"p_18_18_18_96\":{\"puntos\":1,\"orden\":3,\"muestra\":1,\"identificador\":\"p_18_18_18_96\",\"influyeValor\":1,\"tipo\":\"ab\",\"id\":96,\"pregunta\":\"Please add any notes\",\"respuestas\":[],\"justif\":0,\"comShopper\":\"\",\"comVerif\":null,\"conds\":[]}},\"conds\":[]}},\"conds\":[]},\"b_18_19\":{\"max\":0,\"valor\":0,\"muestra\":1,\"nombre\":\"Food\",\"tipoProm\":1,\"valMax\":100,\"encabezado\":0,\"areas\":{\"a_18_19_19\":{\"max\":0,\"valor\":0,\"muestra\":1,\"nombre\":\"Food\",\"valMax\":100,\"preguntas\":{\"p_18_19_19_97\":{\"puntos\":1,\"orden\":1,\"muestra\":1,\"identificador\":\"p_18_19_19_97\",\"influyeValor\":1,\"tipo\":\"mult\",\"id\":97,\"pregunta\":\"Are food supplies sufficient?\",\"respuestas\":{\"1\":[{\"id\":42,\"preguntasId\":97,\"respuesta\":\"Yes\",\"valor\":\"1\",\"identificador\":null,\"orden\":0,\"elim\":1,\"justif\":null},{\"id\":45,\"preguntasId\":97,\"respuesta\":\"Yes\",\"valor\":\"1\",\"identificador\":null,\"orden\":0,\"elim\":1,\"justif\":null},{\"id\":48,\"preguntasId\":97,\"respuesta\":\"Yes\",\"valor\":\"1\",\"identificador\":null,\"orden\":0,\"elim\":1,\"justif\":null},{\"id\":51,\"preguntasId\":97,\"respuesta\":\"Yes\",\"valor\":\"1\",\"identificador\":null,\"orden\":0,\"elim\":1,\"justif\":null},{\"id\":55,\"preguntasId\":97,\"respuesta\":\"1\",\"valor\":\"1\",\"identificador\":null,\"orden\":2,\"elim\":1,\"justif\":null},{\"id\":66,\"preguntasId\":97,\"respuesta\":\"Yes\",\"valor\":\"1\",\"identificador\":null,\"orden\":0,\"elim\":null,\"justif\":null}],\"0\":[{\"id\":43,\"preguntasId\":97,\"respuesta\":\"No\",\"valor\":\"0\",\"identificador\":null,\"orden\":1,\"elim\":1,\"justif\":null},{\"id\":46,\"preguntasId\":97,\"respuesta\":\"No\",\"valor\":\"0\",\"identificador\":null,\"orden\":1,\"elim\":1,\"justif\":null},{\"id\":49,\"preguntasId\":97,\"respuesta\":\"No\",\"valor\":\"0\",\"identificador\":null,\"orden\":1,\"elim\":1,\"justif\":null},{\"id\":52,\"preguntasId\":97,\"respuesta\":\"No\",\"valor\":\"0\",\"identificador\":null,\"orden\":1,\"elim\":1,\"justif\":null},{\"id\":54,\"preguntasId\":97,\"respuesta\":\"0\",\"valor\":\"0\",\"identificador\":null,\"orden\":1,\"elim\":1,\"justif\":null},{\"id\":67,\"preguntasId\":97,\"respuesta\":\"No\",\"valor\":\"0\",\"identificador\":null,\"orden\":1,\"elim\":null,\"justif\":null}],\"2\":[{\"id\":44,\"preguntasId\":97,\"respuesta\":\"I dont know\",\"valor\":\"2\",\"identificador\":null,\"orden\":2,\"elim\":1,\"justif\":null},{\"id\":47,\"preguntasId\":97,\"respuesta\":\"I dont know\",\"valor\":\"2\",\"identificador\":null,\"orden\":2,\"elim\":1,\"justif\":null},{\"id\":50,\"preguntasId\":97,\"respuesta\":\"I dont know\",\"valor\":\"2\",\"identificador\":null,\"orden\":2,\"elim\":1,\"justif\":null},{\"id\":53,\"preguntasId\":97,\"respuesta\":\"I dont know\",\"valor\":\"2\",\"identificador\":null,\"orden\":2,\"elim\":1,\"justif\":null},{\"id\":56,\"preguntasId\":97,\"respuesta\":\"2\",\"valor\":\"2\",\"identificador\":null,\"orden\":3,\"elim\":1,\"justif\":null},{\"id\":68,\"preguntasId\":97,\"respuesta\":\"I dont know\",\"valor\":\"2\",\"identificador\":null,\"orden\":2,\"elim\":null,\"justif\":null}],\"3\":[{\"id\":57,\"preguntasId\":97,\"respuesta\":\"3\",\"valor\":\"3\",\"identificador\":null,\"orden\":4,\"elim\":1,\"justif\":null}],\"4\":[{\"id\":58,\"preguntasId\":97,\"respuesta\":\"4\",\"valor\":\"4\",\"identificador\":null,\"orden\":5,\"elim\":1,\"justif\":null}],\"5\":[{\"id\":59,\"preguntasId\":97,\"respuesta\":\"5\",\"valor\":\"5\",\"identificador\":null,\"orden\":6,\"elim\":1,\"justif\":null}],\"6\":[{\"id\":60,\"preguntasId\":97,\"respuesta\":\"6\",\"valor\":\"6\",\"identificador\":null,\"orden\":7,\"elim\":1,\"justif\":null}],\"7\":[{\"id\":61,\"preguntasId\":97,\"respuesta\":\"7\",\"valor\":\"7\",\"identificador\":null,\"orden\":8,\"elim\":1,\"justif\":null}],\"8\":[{\"id\":62,\"preguntasId\":97,\"respuesta\":\"8\",\"valor\":\"8\",\"identificador\":null,\"orden\":9,\"elim\":1,\"justif\":null}],\"9\":[{\"id\":63,\"preguntasId\":97,\"respuesta\":\"9\",\"valor\":\"9\",\"identificador\":null,\"orden\":10,\"elim\":1,\"justif\":null}],\"10\":[{\"id\":64,\"preguntasId\":97,\"respuesta\":\"10\",\"valor\":\"10\",\"identificador\":null,\"orden\":11,\"elim\":1,\"justif\":null}],\"-\":[{\"id\":65,\"preguntasId\":97,\"respuesta\":\"NA\",\"valor\":\"-\",\"identificador\":null,\"orden\":13,\"elim\":1,\"justif\":null}]},\"justif\":0,\"comShopper\":\"\",\"comVerif\":null,\"conds\":[]},\"p_18_19_19_98\":{\"puntos\":1,\"orden\":2,\"muestra\":1,\"identificador\":\"p_18_19_19_98\",\"influyeValor\":1,\"tipo\":\"ab\",\"id\":98,\"pregunta\":\"Notes\",\"respuestas\":[],\"justif\":0,\"comShopper\":\"Please provide comments based on current conditions\",\"comVerif\":null,\"conds\":[]}},\"conds\":[]}},\"conds\":[]},\"b_18_20\":{\"max\":0,\"valor\":0,\"muestra\":1,\"nombre\":\"Personnel transportation\",\"tipoProm\":1,\"valMax\":100,\"encabezado\":0,\"areas\":{\"a_18_20_20\":{\"max\":0,\"valor\":0,\"muestra\":1,\"nombre\":\"Personnel transportation\",\"valMax\":100,\"preguntas\":{\"p_18_20_20_99\":{\"puntos\":1,\"orden\":1,\"muestra\":1,\"identificador\":\"p_18_20_20_99\",\"influyeValor\":1,\"tipo\":\"mult\",\"id\":99,\"pregunta\":\"Is personnel transportation working?\",\"respuestas\":{\"1\":[{\"id\":69,\"preguntasId\":99,\"respuesta\":\"Yes\",\"valor\":\"1\",\"identificador\":null,\"orden\":0,\"elim\":null,\"justif\":null}],\"0\":[{\"id\":70,\"preguntasId\":99,\"respuesta\":\"No\",\"valor\":\"0\",\"identificador\":null,\"orden\":1,\"elim\":null,\"justif\":null}],\"2\":[{\"id\":71,\"preguntasId\":99,\"respuesta\":\"I dont know\",\"valor\":\"2\",\"identificador\":null,\"orden\":2,\"elim\":null,\"justif\":null}]},\"justif\":0,\"comShopper\":\"\",\"comVerif\":null,\"conds\":[]}},\"conds\":[]}},\"conds\":[]}}}'),(26,6,'{\"tipoProm\":1,\"tipoAnalisis\":1,\"conds\":[],\"bloques\":{\"b_6_6\":{\"max\":0,\"valor\":0,\"muestra\":1,\"nombre\":\"WEEKLY RW MONITORING\",\"tipoProm\":1,\"valMax\":100,\"encabezado\":0,\"areas\":{\"a_6_6_6\":{\"max\":0,\"valor\":0,\"muestra\":1,\"nombre\":\"WEEKLY RW MONITORING\",\"valMax\":100,\"preguntas\":{\"p_6_6_6_13\":{\"puntos\":1,\"orden\":1,\"muestra\":1,\"identificador\":\"p_6_6_6_13\",\"influyeValor\":0,\"tipo\":\"ab\",\"id\":13,\"pregunta\":\"What is the name of your RW?\",\"respuestas\":[],\"justif\":0,\"comShopper\":\"\",\"comVerif\":null,\"conds\":[]},\"p_6_6_6_14\":{\"puntos\":1,\"orden\":2,\"muestra\":1,\"identificador\":\"p_6_6_6_14\",\"influyeValor\":0,\"tipo\":\"num\",\"id\":14,\"pregunta\":\"Number of people showing Corona Virus symptoms in your RW?\",\"respuestas\":[],\"justif\":0,\"comShopper\":\"\",\"comVerif\":null,\"conds\":[]},\"p_6_6_6_15\":{\"puntos\":1,\"orden\":3,\"muestra\":1,\"identificador\":\"p_6_6_6_15\",\"influyeValor\":0,\"tipo\":\"num\",\"id\":15,\"pregunta\":\"Number of people showing symptoms of severe illness to Corona Virus.\",\"respuestas\":[],\"justif\":0,\"comShopper\":\"\",\"comVerif\":null,\"conds\":[]},\"p_6_6_6_16\":{\"puntos\":1,\"orden\":4,\"muestra\":1,\"identificador\":\"p_6_6_6_16\",\"influyeValor\":0,\"tipo\":\"mult\",\"id\":16,\"pregunta\":\"Has anyone in your RW been tested for Corona Virus?&nbsp;\",\"respuestas\":{\"1\":[{\"id\":22,\"preguntasId\":16,\"respuesta\":\"Yes\",\"valor\":\"1\",\"identificador\":null,\"orden\":0,\"elim\":null,\"justif\":null}],\"0\":[{\"id\":23,\"preguntasId\":16,\"respuesta\":\"No\",\"valor\":\"0\",\"identificador\":null,\"orden\":1,\"elim\":null,\"justif\":null}],\"2\":[{\"id\":24,\"preguntasId\":16,\"respuesta\":\"I dont know\",\"valor\":\"2\",\"identificador\":null,\"orden\":2,\"elim\":null,\"justif\":null}]},\"justif\":0,\"comShopper\":\"\",\"comVerif\":null,\"conds\":[]},\"p_6_6_6_17\":{\"puntos\":1,\"orden\":5,\"muestra\":1,\"identificador\":\"p_6_6_6_17\",\"influyeValor\":0,\"tipo\":\"num\",\"id\":17,\"pregunta\":\"If anyone in your RW has been tested, how many have been confirmed positive for Corona Virus?&nbsp;\",\"respuestas\":[],\"justif\":0,\"comShopper\":\"\",\"comVerif\":null,\"conds\":[{\"id\":4,\"aplicacion\":\"preg\",\"eleId\":17,\"condicion\":\"val(p_6_6_6_16) = 0\",\"accion\":2,\"valor\":\"-\",\"orden\":0}]},\"p_6_6_6_18\":{\"puntos\":1,\"orden\":6,\"muestra\":1,\"identificador\":\"p_6_6_6_18\",\"influyeValor\":0,\"tipo\":\"num\",\"id\":18,\"pregunta\":\"How many people with symptoms in your RW have recovered?&nbsp;\",\"respuestas\":[],\"justif\":0,\"comShopper\":\"\",\"comVerif\":null,\"conds\":[]},\"p_6_6_6_19\":{\"puntos\":1,\"orden\":7,\"muestra\":1,\"identificador\":\"p_6_6_6_19\",\"influyeValor\":0,\"tipo\":\"num\",\"id\":19,\"pregunta\":\"Number of deaths in the RW believed to be due to covid19?&nbsp;\",\"respuestas\":[],\"justif\":0,\"comShopper\":\"\",\"comVerif\":null,\"conds\":[]},\"p_6_6_6_20\":{\"puntos\":1,\"orden\":8,\"muestra\":1,\"identificador\":\"p_6_6_6_20\",\"influyeValor\":0,\"tipo\":\"sub\",\"id\":20,\"pregunta\":\"Number of people in isolation and where are they isolated?&nbsp;\",\"respuestas\":[],\"justif\":0,\"comShopper\":\"\",\"comVerif\":null,\"conds\":[],\"subpregs\":{\"p_6_6_6_21\":{\"puntos\":1,\"muestra\":1,\"identificador\":\"p_6_6_6_21\",\"influyeValor\":0,\"tipo\":\"num\",\"id\":21,\"pregunta\":\"In their house:&nbsp;&nbsp;\",\"respuestas\":[],\"justif\":0,\"stipo\":\"subPregs\",\"conds\":[]},\"p_6_6_6_22\":{\"puntos\":1,\"muestra\":1,\"identificador\":\"p_6_6_6_22\",\"influyeValor\":0,\"tipo\":\"num\",\"id\":22,\"pregunta\":\"In a formal health center\",\"respuestas\":[],\"justif\":0,\"stipo\":\"subPregs\",\"conds\":[]},\"p_6_6_6_23\":{\"puntos\":1,\"muestra\":1,\"identificador\":\"p_6_6_6_23\",\"influyeValor\":0,\"tipo\":\"num\",\"id\":23,\"pregunta\":\"In another facility\",\"respuestas\":[],\"justif\":0,\"stipo\":\"subPregs\",\"conds\":[]}}},\"p_6_6_6_24\":{\"puntos\":1,\"orden\":12,\"muestra\":1,\"identificador\":\"p_6_6_6_24\",\"influyeValor\":0,\"tipo\":\"sub\",\"id\":24,\"pregunta\":\"Number of people who entered the village from other areas\",\"respuestas\":[],\"justif\":0,\"comShopper\":\"\",\"comVerif\":null,\"conds\":[],\"subpregs\":{\"p_6_6_6_25\":{\"puntos\":1,\"muestra\":1,\"identificador\":\"p_6_6_6_25\",\"influyeValor\":0,\"tipo\":\"num\",\"id\":25,\"pregunta\":\"From another country&nbsp;\",\"respuestas\":[],\"justif\":0,\"stipo\":\"subPregs\",\"conds\":[]},\"p_6_6_6_26\":{\"puntos\":1,\"muestra\":1,\"identificador\":\"p_6_6_6_26\",\"influyeValor\":0,\"tipo\":\"num\",\"id\":26,\"pregunta\":\"From a major city&nbsp;\",\"respuestas\":[],\"justif\":0,\"stipo\":\"subPregs\",\"conds\":[]},\"p_6_6_6_27\":{\"puntos\":1,\"muestra\":1,\"identificador\":\"p_6_6_6_27\",\"influyeValor\":0,\"tipo\":\"num\",\"id\":27,\"pregunta\":\"From outside the district\",\"respuestas\":[],\"justif\":0,\"stipo\":\"subPregs\",\"conds\":[]},\"p_6_6_6_28\":{\"puntos\":1,\"muestra\":1,\"identificador\":\"p_6_6_6_28\",\"influyeValor\":1,\"tipo\":\"num\",\"id\":28,\"pregunta\":\"From another village inside the district\",\"respuestas\":[],\"justif\":0,\"stipo\":\"subPregs\",\"conds\":[]}}},\"p_6_6_6_29\":{\"puntos\":1,\"orden\":17,\"muestra\":1,\"identificador\":\"p_6_6_6_29\",\"influyeValor\":0,\"tipo\":\"num\",\"id\":29,\"pregunta\":\"Number of people coming from outside the village that are self-quarantined?\",\"respuestas\":[],\"justif\":0,\"comShopper\":\"\",\"comVerif\":null,\"conds\":[]},\"p_6_6_6_30\":{\"puntos\":1,\"orden\":18,\"muestra\":1,\"identificador\":\"p_6_6_6_30\",\"influyeValor\":0,\"tipo\":\"sub\",\"id\":30,\"pregunta\":\"Number of villagers receiving PKT\",\"respuestas\":[],\"justif\":0,\"comShopper\":\"\",\"comVerif\":null,\"conds\":[],\"subpregs\":{\"p_6_6_6_31\":{\"puntos\":1,\"muestra\":1,\"identificador\":\"p_6_6_6_31\",\"influyeValor\":0,\"tipo\":\"num\",\"id\":31,\"pregunta\":\"Number who are working&nbsp;\",\"respuestas\":[],\"justif\":0,\"stipo\":\"subPregs\",\"conds\":[]},\"p_6_6_6_32\":{\"puntos\":1,\"muestra\":1,\"identificador\":\"p_6_6_6_32\",\"influyeValor\":0,\"tipo\":\"num\",\"id\":32,\"pregunta\":\"Number of people who were participating in PKT but were identified to have symptoms in the last week&nbsp;\",\"respuestas\":[],\"justif\":0,\"stipo\":\"subPregs\",\"conds\":[]},\"p_6_6_6_33\":{\"puntos\":1,\"muestra\":1,\"identificador\":\"p_6_6_6_33\",\"influyeValor\":0,\"tipo\":\"num\",\"id\":33,\"pregunta\":\"Number receiving transfers but unable to work&nbsp;\",\"respuestas\":[],\"justif\":0,\"stipo\":\"subPregs\",\"conds\":[]}}}},\"conds\":[]}},\"conds\":[]}}}'),(27,16,'{\"tipoProm\":1,\"tipoAnalisis\":1,\"conds\":[],\"bloques\":{\"b_16_16\":{\"max\":0,\"valor\":0,\"muestra\":1,\"nombre\":\"T1\",\"tipoProm\":1,\"valMax\":100,\"encabezado\":0,\"areas\":{\"a_16_16_16\":{\"max\":0,\"valor\":0,\"muestra\":1,\"nombre\":\"T2\",\"valMax\":100,\"preguntas\":{\"p_16_16_16_91\":{\"puntos\":1,\"orden\":1,\"muestra\":1,\"identificador\":\"p_16_16_16_91\",\"influyeValor\":1,\"tipo\":\"spatial\",\"id\":91,\"pregunta\":\"Spatial\",\"respuestas\":[],\"justif\":0,\"comShopper\":\"\",\"comVerif\":null,\"conds\":[]}},\"conds\":[]}},\"conds\":[]},\"b_16_26\":{\"max\":0,\"valor\":0,\"muestra\":1,\"nombre\":\"Health\",\"tipoProm\":1,\"valMax\":100,\"encabezado\":0,\"areas\":[],\"conds\":[]}}}'),(28,21,'{\"tipoProm\":1,\"tipoAnalisis\":1,\"conds\":[],\"bloques\":{\"b_21_23\":{\"max\":0,\"valor\":0,\"muestra\":1,\"nombre\":\"Location\",\"tipoProm\":1,\"valMax\":100,\"encabezado\":0,\"areas\":{\"a_21_23_23\":{\"max\":0,\"valor\":0,\"muestra\":1,\"nombre\":\"Location\",\"valMax\":100,\"preguntas\":{\"p_21_23_23_109\":{\"puntos\":1,\"orden\":1,\"muestra\":1,\"identificador\":\"p_21_23_23_109\",\"influyeValor\":1,\"tipo\":\"spatial\",\"id\":109,\"pregunta\":\"Please point the location of your house in the map. This will be highly useful to identify your location if you need help.\",\"respuestas\":[],\"justif\":0,\"comShopper\":\"All answers will remain confidential.&nbsp;\",\"comVerif\":null,\"conds\":[]},\"p_21_23_23_108\":{\"puntos\":1,\"orden\":2,\"muestra\":1,\"identificador\":\"p_21_23_23_108\",\"influyeValor\":1,\"tipo\":\"ab\",\"id\":108,\"pregunta\":\"Please write your address and add instructions to get to your location.&nbsp;\",\"respuestas\":[],\"justif\":0,\"comShopper\":\"All answers will remain confidential.\\u00a0\",\"comVerif\":null,\"conds\":[]}},\"conds\":[]}},\"conds\":[]},\"b_21_24\":{\"max\":0,\"valor\":0,\"muestra\":1,\"nombre\":\"Family members\",\"tipoProm\":1,\"valMax\":100,\"encabezado\":0,\"areas\":{\"a_21_24_24\":{\"max\":0,\"valor\":0,\"muestra\":1,\"nombre\":\"Family members\",\"valMax\":100,\"preguntas\":{\"p_21_24_24_110\":{\"puntos\":1,\"orden\":1,\"muestra\":1,\"identificador\":\"p_21_24_24_110\",\"influyeValor\":1,\"tipo\":\"num\",\"id\":110,\"pregunta\":\"How many persons live with you?\",\"respuestas\":[],\"justif\":0,\"comShopper\":\"All answers will remain confidential.&nbsp;\",\"comVerif\":null,\"conds\":[]},\"p_21_24_24_111\":{\"puntos\":1,\"orden\":2,\"muestra\":1,\"identificador\":\"p_21_24_24_111\",\"influyeValor\":1,\"tipo\":\"num\",\"id\":111,\"pregunta\":\"How many rooms are there in your house?\",\"respuestas\":[],\"justif\":0,\"comShopper\":\"All answers will remain confidential.&nbsp;\",\"comVerif\":null,\"conds\":[]},\"p_21_24_24_129\":{\"puntos\":1,\"orden\":3,\"muestra\":1,\"identificador\":\"p_21_24_24_129\",\"influyeValor\":1,\"tipo\":\"num\",\"id\":129,\"pregunta\":\"How many members of your family, including yourself are older than 65 or younger than 12?\",\"respuestas\":[],\"justif\":0,\"comShopper\":\"\",\"comVerif\":null,\"conds\":[]}},\"conds\":[]}},\"conds\":[]},\"b_21_25\":{\"max\":0,\"valor\":0,\"muestra\":1,\"nombre\":\"Symptom\",\"tipoProm\":1,\"valMax\":100,\"encabezado\":0,\"areas\":{\"a_21_25_25\":{\"max\":0,\"valor\":0,\"muestra\":1,\"nombre\":\"Symptom\",\"valMax\":100,\"preguntas\":{\"p_21_25_25_112\":{\"puntos\":1,\"orden\":1,\"muestra\":1,\"identificador\":\"p_21_25_25_112\",\"influyeValor\":1,\"tipo\":\"mult\",\"id\":112,\"pregunta\":\"Has anyone from your family recently experienced&nbsp;fever greater than 38 degrees?\",\"respuestas\":{\"1\":[{\"id\":95,\"preguntasId\":112,\"respuesta\":\"Yes\",\"valor\":\"1\",\"identificador\":null,\"orden\":0,\"elim\":null,\"justif\":null}],\"0\":[{\"id\":96,\"preguntasId\":112,\"respuesta\":\"No\",\"valor\":\"0\",\"identificador\":null,\"orden\":1,\"elim\":null,\"justif\":null}],\"2\":[{\"id\":97,\"preguntasId\":112,\"respuesta\":\"I dont know\",\"valor\":\"2\",\"identificador\":null,\"orden\":2,\"elim\":null,\"justif\":null}]},\"justif\":0,\"comShopper\":\"\",\"comVerif\":null,\"conds\":[]},\"p_21_25_25_113\":{\"puntos\":1,\"orden\":2,\"muestra\":1,\"identificador\":\"p_21_25_25_113\",\"influyeValor\":1,\"tipo\":\"mult\",\"id\":113,\"pregunta\":\"Has anyone in your family experienced EYE PAIN in the last week?\",\"respuestas\":{\"1\":[{\"id\":98,\"preguntasId\":113,\"respuesta\":\"Yes\",\"valor\":\"1\",\"identificador\":null,\"orden\":0,\"elim\":null,\"justif\":null}],\"0\":[{\"id\":99,\"preguntasId\":113,\"respuesta\":\"No\",\"valor\":\"0\",\"identificador\":null,\"orden\":1,\"elim\":null,\"justif\":null}],\"2\":[{\"id\":100,\"preguntasId\":113,\"respuesta\":\"I dont know\",\"valor\":\"2\",\"identificador\":null,\"orden\":2,\"elim\":null,\"justif\":null}]},\"justif\":0,\"comShopper\":\"\",\"comVerif\":null,\"conds\":[]},\"p_21_25_25_114\":{\"puntos\":1,\"orden\":3,\"muestra\":1,\"identificador\":\"p_21_25_25_114\",\"influyeValor\":1,\"tipo\":\"mult\",\"id\":114,\"pregunta\":\"Has anyone in your family experienced CHEST PAIN in the last week?\",\"respuestas\":{\"1\":[{\"id\":101,\"preguntasId\":114,\"respuesta\":\"Yes\",\"valor\":\"1\",\"identificador\":null,\"orden\":0,\"elim\":null,\"justif\":null}],\"0\":[{\"id\":102,\"preguntasId\":114,\"respuesta\":\"No\",\"valor\":\"0\",\"identificador\":null,\"orden\":1,\"elim\":null,\"justif\":null}],\"2\":[{\"id\":103,\"preguntasId\":114,\"respuesta\":\"I dont know\",\"valor\":\"2\",\"identificador\":null,\"orden\":2,\"elim\":null,\"justif\":null}]},\"justif\":0,\"comShopper\":\"\",\"comVerif\":null,\"conds\":[]},\"p_21_25_25_115\":{\"puntos\":1,\"orden\":4,\"muestra\":1,\"identificador\":\"p_21_25_25_115\",\"influyeValor\":1,\"tipo\":\"mult\",\"id\":115,\"pregunta\":\"Has anyone in your family experienced MUSCLE PAIN in the last week?\",\"respuestas\":{\"1\":[{\"id\":104,\"preguntasId\":115,\"respuesta\":\"Yes\",\"valor\":\"1\",\"identificador\":null,\"orden\":0,\"elim\":null,\"justif\":null}],\"0\":[{\"id\":105,\"preguntasId\":115,\"respuesta\":\"No\",\"valor\":\"0\",\"identificador\":null,\"orden\":1,\"elim\":null,\"justif\":null}],\"2\":[{\"id\":106,\"preguntasId\":115,\"respuesta\":\"I dont know\",\"valor\":\"2\",\"identificador\":null,\"orden\":2,\"elim\":null,\"justif\":null}]},\"justif\":0,\"comShopper\":\"\",\"comVerif\":null,\"conds\":[]},\"p_21_25_25_116\":{\"puntos\":1,\"orden\":5,\"muestra\":1,\"identificador\":\"p_21_25_25_116\",\"influyeValor\":1,\"tipo\":\"mult\",\"id\":116,\"pregunta\":\"Has anyone in your family experienced HEADACHE in the last week?\",\"respuestas\":{\"1\":[{\"id\":107,\"preguntasId\":116,\"respuesta\":\"Yes\",\"valor\":\"1\",\"identificador\":null,\"orden\":0,\"elim\":null,\"justif\":null}],\"0\":[{\"id\":108,\"preguntasId\":116,\"respuesta\":\"No\",\"valor\":\"0\",\"identificador\":null,\"orden\":1,\"elim\":null,\"justif\":null}],\"2\":[{\"id\":109,\"preguntasId\":116,\"respuesta\":\"I dont know\",\"valor\":\"2\",\"identificador\":null,\"orden\":2,\"elim\":null,\"justif\":null}]},\"justif\":0,\"comShopper\":\"\",\"comVerif\":null,\"conds\":[]},\"p_21_25_25_117\":{\"puntos\":1,\"orden\":6,\"muestra\":1,\"identificador\":\"p_21_25_25_117\",\"influyeValor\":1,\"tipo\":\"mult\",\"id\":117,\"pregunta\":\"Has anyone in your family experienced SORE THROAT in the last week?\",\"respuestas\":{\"1\":[{\"id\":110,\"preguntasId\":117,\"respuesta\":\"Yes\",\"valor\":\"1\",\"identificador\":null,\"orden\":0,\"elim\":null,\"justif\":null}],\"0\":[{\"id\":111,\"preguntasId\":117,\"respuesta\":\"No\",\"valor\":\"0\",\"identificador\":null,\"orden\":1,\"elim\":null,\"justif\":null}],\"2\":[{\"id\":112,\"preguntasId\":117,\"respuesta\":\"I dont know\",\"valor\":\"2\",\"identificador\":null,\"orden\":2,\"elim\":null,\"justif\":null}]},\"justif\":0,\"comShopper\":\"\",\"comVerif\":null,\"conds\":[]},\"p_21_25_25_118\":{\"puntos\":1,\"orden\":7,\"muestra\":1,\"identificador\":\"p_21_25_25_118\",\"influyeValor\":1,\"tipo\":\"mult\",\"id\":118,\"pregunta\":\"Has anyone in your family experienced KNEE PAIN in the last week?\",\"respuestas\":{\"1\":[{\"id\":113,\"preguntasId\":118,\"respuesta\":\"Yes\",\"valor\":\"1\",\"identificador\":null,\"orden\":0,\"elim\":null,\"justif\":null}],\"0\":[{\"id\":114,\"preguntasId\":118,\"respuesta\":\"No\",\"valor\":\"0\",\"identificador\":null,\"orden\":1,\"elim\":null,\"justif\":null}],\"2\":[{\"id\":115,\"preguntasId\":118,\"respuesta\":\"I dont know\",\"valor\":\"2\",\"identificador\":null,\"orden\":2,\"elim\":null,\"justif\":null}]},\"justif\":0,\"comShopper\":\"\",\"comVerif\":null,\"conds\":[]},\"p_21_25_25_119\":{\"puntos\":1,\"orden\":8,\"muestra\":1,\"identificador\":\"p_21_25_25_119\",\"influyeValor\":1,\"tipo\":\"mult\",\"id\":119,\"pregunta\":\"Has anyone in your family experienced PAIN IN THE EARS in the last week?\",\"respuestas\":{\"1\":[{\"id\":116,\"preguntasId\":119,\"respuesta\":\"Yes\",\"valor\":\"1\",\"identificador\":null,\"orden\":0,\"elim\":null,\"justif\":null}],\"0\":[{\"id\":117,\"preguntasId\":119,\"respuesta\":\"No\",\"valor\":\"0\",\"identificador\":null,\"orden\":1,\"elim\":null,\"justif\":null}],\"2\":[{\"id\":118,\"preguntasId\":119,\"respuesta\":\"I dont know\",\"valor\":\"2\",\"identificador\":null,\"orden\":2,\"elim\":null,\"justif\":null}]},\"justif\":0,\"comShopper\":\"\",\"comVerif\":null,\"conds\":[]},\"p_21_25_25_120\":{\"puntos\":1,\"orden\":9,\"muestra\":1,\"identificador\":\"p_21_25_25_120\",\"influyeValor\":1,\"tipo\":\"mult\",\"id\":120,\"pregunta\":\"Has anyone in your family experienced JOINT PAIN in the last week?\",\"respuestas\":{\"1\":[{\"id\":119,\"preguntasId\":120,\"respuesta\":\"Yes\",\"valor\":\"1\",\"identificador\":null,\"orden\":0,\"elim\":null,\"justif\":null}],\"0\":[{\"id\":120,\"preguntasId\":120,\"respuesta\":\"No\",\"valor\":\"0\",\"identificador\":null,\"orden\":1,\"elim\":null,\"justif\":null}],\"2\":[{\"id\":121,\"preguntasId\":120,\"respuesta\":\"I dont know\",\"valor\":\"2\",\"identificador\":null,\"orden\":2,\"elim\":null,\"justif\":null}]},\"justif\":0,\"comShopper\":\"\",\"comVerif\":null,\"conds\":[]},\"p_21_25_25_121\":{\"puntos\":1,\"orden\":10,\"muestra\":1,\"identificador\":\"p_21_25_25_121\",\"influyeValor\":1,\"tipo\":\"mult\",\"id\":121,\"pregunta\":\"Has anyone in your family experienced COUGH in the last week?\",\"respuestas\":{\"1\":[{\"id\":122,\"preguntasId\":121,\"respuesta\":\"Yes\",\"valor\":\"1\",\"identificador\":null,\"orden\":0,\"elim\":null,\"justif\":null}],\"0\":[{\"id\":123,\"preguntasId\":121,\"respuesta\":\"No\",\"valor\":\"0\",\"identificador\":null,\"orden\":1,\"elim\":null,\"justif\":null}],\"2\":[{\"id\":124,\"preguntasId\":121,\"respuesta\":\"I dont know\",\"valor\":\"2\",\"identificador\":null,\"orden\":2,\"elim\":null,\"justif\":null}]},\"justif\":0,\"comShopper\":\"\",\"comVerif\":null,\"conds\":[]},\"p_21_25_25_122\":{\"puntos\":1,\"orden\":11,\"muestra\":1,\"identificador\":\"p_21_25_25_122\",\"influyeValor\":1,\"tipo\":\"mult\",\"id\":122,\"pregunta\":\"Has anyone in your family experienced DIFFICULTY BREATHING in the last week?\",\"respuestas\":{\"1\":[{\"id\":125,\"preguntasId\":122,\"respuesta\":\"Yes\",\"valor\":\"1\",\"identificador\":null,\"orden\":0,\"elim\":null,\"justif\":null}],\"0\":[{\"id\":126,\"preguntasId\":122,\"respuesta\":\"No\",\"valor\":\"0\",\"identificador\":null,\"orden\":1,\"elim\":null,\"justif\":null}],\"2\":[{\"id\":127,\"preguntasId\":122,\"respuesta\":\"I dont know\",\"valor\":\"2\",\"identificador\":null,\"orden\":2,\"elim\":null,\"justif\":null}]},\"justif\":0,\"comShopper\":\"\",\"comVerif\":null,\"conds\":[]},\"p_21_25_25_123\":{\"puntos\":1,\"orden\":12,\"muestra\":1,\"identificador\":\"p_21_25_25_123\",\"influyeValor\":1,\"tipo\":\"mult\",\"id\":123,\"pregunta\":\"Has anyone in your family experienced SWEATING in the last week?\",\"respuestas\":{\"1\":[{\"id\":128,\"preguntasId\":123,\"respuesta\":\"Yes\",\"valor\":\"1\",\"identificador\":null,\"orden\":0,\"elim\":null,\"justif\":null}],\"0\":[{\"id\":129,\"preguntasId\":123,\"respuesta\":\"No\",\"valor\":\"0\",\"identificador\":null,\"orden\":1,\"elim\":null,\"justif\":null}],\"2\":[{\"id\":130,\"preguntasId\":123,\"respuesta\":\"I dont know\",\"valor\":\"2\",\"identificador\":null,\"orden\":2,\"elim\":null,\"justif\":null}]},\"justif\":0,\"comShopper\":\"\",\"comVerif\":null,\"conds\":[]},\"p_21_25_25_124\":{\"puntos\":1,\"orden\":13,\"muestra\":1,\"identificador\":\"p_21_25_25_124\",\"influyeValor\":1,\"tipo\":\"mult\",\"id\":124,\"pregunta\":\"Has anyone in your family experienced RUNNY NOSE in the last week?\",\"respuestas\":{\"1\":[{\"id\":131,\"preguntasId\":124,\"respuesta\":\"Yes\",\"valor\":\"1\",\"identificador\":null,\"orden\":0,\"elim\":null,\"justif\":null}],\"0\":[{\"id\":132,\"preguntasId\":124,\"respuesta\":\"No\",\"valor\":\"0\",\"identificador\":null,\"orden\":1,\"elim\":null,\"justif\":null}],\"2\":[{\"id\":133,\"preguntasId\":124,\"respuesta\":\"I dont know\",\"valor\":\"2\",\"identificador\":null,\"orden\":2,\"elim\":null,\"justif\":null}]},\"justif\":0,\"comShopper\":\"\",\"comVerif\":null,\"conds\":[]},\"p_21_25_25_125\":{\"puntos\":1,\"orden\":14,\"muestra\":1,\"identificador\":\"p_21_25_25_125\",\"influyeValor\":1,\"tipo\":\"mult\",\"id\":125,\"pregunta\":\"Has anyone in your family experienced ITCH in the last week?\",\"respuestas\":{\"1\":[{\"id\":134,\"preguntasId\":125,\"respuesta\":\"Yes\",\"valor\":\"1\",\"identificador\":null,\"orden\":0,\"elim\":null,\"justif\":null}],\"0\":[{\"id\":135,\"preguntasId\":125,\"respuesta\":\"No\",\"valor\":\"0\",\"identificador\":null,\"orden\":1,\"elim\":null,\"justif\":null}],\"2\":[{\"id\":136,\"preguntasId\":125,\"respuesta\":\"I dont know\",\"valor\":\"2\",\"identificador\":null,\"orden\":2,\"elim\":null,\"justif\":null}]},\"justif\":0,\"comShopper\":\"\",\"comVerif\":null,\"conds\":[]},\"p_21_25_25_126\":{\"puntos\":1,\"orden\":15,\"muestra\":1,\"identificador\":\"p_21_25_25_126\",\"influyeValor\":1,\"tipo\":\"mult\",\"id\":126,\"pregunta\":\"<p>Has anyone in your family experienced CONJUNCTIVITIS in the last week?<\\/p>\",\"respuestas\":{\"1\":[{\"id\":137,\"preguntasId\":126,\"respuesta\":\"Yes\",\"valor\":\"1\",\"identificador\":null,\"orden\":0,\"elim\":null,\"justif\":null}],\"0\":[{\"id\":138,\"preguntasId\":126,\"respuesta\":\"No\",\"valor\":\"0\",\"identificador\":null,\"orden\":1,\"elim\":null,\"justif\":null}],\"2\":[{\"id\":139,\"preguntasId\":126,\"respuesta\":\"I dont know\",\"valor\":\"2\",\"identificador\":null,\"orden\":2,\"elim\":null,\"justif\":null}]},\"justif\":0,\"comShopper\":\"\",\"comVerif\":null,\"conds\":[]},\"p_21_25_25_127\":{\"puntos\":1,\"orden\":16,\"muestra\":1,\"identificador\":\"p_21_25_25_127\",\"influyeValor\":1,\"tipo\":\"mult\",\"id\":127,\"pregunta\":\"<p>Has anyone in your family experienced SICKNESS, NAUSEA or VOMIT in the last week?<\\/p>\",\"respuestas\":{\"1\":[{\"id\":140,\"preguntasId\":127,\"respuesta\":\"Yes\",\"valor\":\"1\",\"identificador\":null,\"orden\":0,\"elim\":null,\"justif\":null}],\"0\":[{\"id\":141,\"preguntasId\":127,\"respuesta\":\"No\",\"valor\":\"0\",\"identificador\":null,\"orden\":1,\"elim\":null,\"justif\":null}],\"2\":[{\"id\":142,\"preguntasId\":127,\"respuesta\":\"I dont know\",\"valor\":\"2\",\"identificador\":null,\"orden\":2,\"elim\":null,\"justif\":null}]},\"justif\":0,\"comShopper\":\"\",\"comVerif\":null,\"conds\":[]},\"p_21_25_25_128\":{\"puntos\":1,\"orden\":17,\"muestra\":1,\"identificador\":\"p_21_25_25_128\",\"influyeValor\":1,\"tipo\":\"mult\",\"id\":128,\"pregunta\":\"Has anyone in your family experienced DIARRHEA in the last week?\",\"respuestas\":{\"1\":[{\"id\":143,\"preguntasId\":128,\"respuesta\":\"Yes\",\"valor\":\"1\",\"identificador\":null,\"orden\":0,\"elim\":null,\"justif\":null}],\"0\":[{\"id\":144,\"preguntasId\":128,\"respuesta\":\"No\",\"valor\":\"0\",\"identificador\":null,\"orden\":1,\"elim\":null,\"justif\":null}],\"2\":[{\"id\":145,\"preguntasId\":128,\"respuesta\":\"I dont know\",\"valor\":\"2\",\"identificador\":null,\"orden\":2,\"elim\":null,\"justif\":null}]},\"justif\":0,\"comShopper\":\"\",\"comVerif\":null,\"conds\":[]}},\"conds\":[]}},\"conds\":[]}}}'),(29,7,'{\"tipoProm\":1,\"tipoAnalisis\":1,\"conds\":[],\"bloques\":{\"b_7_7\":{\"max\":0,\"valor\":0,\"muestra\":1,\"nombre\":\"MONTHLY RW MONITORING\",\"tipoProm\":1,\"valMax\":100,\"encabezado\":0,\"areas\":{\"a_7_7_7\":{\"max\":0,\"valor\":0,\"muestra\":1,\"nombre\":\"MONTHLY RW MONITORING\",\"valMax\":100,\"preguntas\":{\"p_7_7_7_34\":{\"puntos\":1,\"orden\":1,\"muestra\":1,\"identificador\":\"p_7_7_7_34\",\"influyeValor\":0,\"tipo\":\"ab\",\"id\":34,\"pregunta\":\"What is the name of your RW?\",\"respuestas\":[],\"justif\":0,\"comShopper\":\"\",\"comVerif\":null,\"conds\":[]},\"p_7_7_7_35\":{\"puntos\":1,\"orden\":2,\"muestra\":1,\"identificador\":\"p_7_7_7_35\",\"influyeValor\":0,\"tipo\":\"num\",\"id\":35,\"pregunta\":\"Number of households where the main income earner(s) are unemployed\",\"respuestas\":[],\"justif\":0,\"comShopper\":\"\",\"comVerif\":null,\"conds\":[]},\"p_7_7_7_36\":{\"puntos\":1,\"orden\":3,\"muestra\":1,\"identificador\":\"p_7_7_7_36\",\"influyeValor\":0,\"tipo\":\"num\",\"id\":36,\"pregunta\":\"Number of households that have lost formal employment\",\"respuestas\":[],\"justif\":0,\"comShopper\":\"\",\"comVerif\":null,\"conds\":[]},\"p_7_7_7_37\":{\"puntos\":1,\"orden\":4,\"muestra\":1,\"identificador\":\"p_7_7_7_37\",\"influyeValor\":0,\"tipo\":\"num\",\"id\":37,\"pregunta\":\"Number of households that have lost informal employment\",\"respuestas\":[],\"justif\":0,\"comShopper\":\"\",\"comVerif\":null,\"conds\":[]},\"p_7_7_7_38\":{\"puntos\":1,\"orden\":5,\"muestra\":1,\"identificador\":\"p_7_7_7_38\",\"influyeValor\":0,\"tipo\":\"num\",\"id\":38,\"pregunta\":\"Number of households where the main income earner(s) are under employed or experienced a significant loss of income in the past month\",\"respuestas\":[],\"justif\":0,\"comShopper\":\"\",\"comVerif\":null,\"conds\":[]},\"p_7_7_7_39\":{\"puntos\":1,\"orden\":6,\"muestra\":1,\"identificador\":\"p_7_7_7_39\",\"influyeValor\":0,\"tipo\":\"num\",\"id\":39,\"pregunta\":\"Number of households with toddlers who are undernourished&nbsp;\",\"respuestas\":[],\"justif\":0,\"comShopper\":\"\",\"comVerif\":null,\"conds\":[]},\"p_7_7_7_40\":{\"puntos\":1,\"orden\":7,\"muestra\":1,\"identificador\":\"p_7_7_7_40\",\"influyeValor\":0,\"tipo\":\"num\",\"id\":40,\"pregunta\":\"Number of households who do not \\/ will not have enough food for the next month&nbsp;\",\"respuestas\":[],\"justif\":0,\"comShopper\":\"\",\"comVerif\":null,\"conds\":[]},\"p_7_7_7_41\":{\"puntos\":1,\"orden\":8,\"muestra\":1,\"identificador\":\"p_7_7_7_41\",\"influyeValor\":0,\"tipo\":\"num\",\"id\":41,\"pregunta\":\"Total number of households who responded \\u2018yes\\u2019 to any one of the above questions.\",\"respuestas\":[],\"justif\":0,\"comShopper\":\"&nbsp;Instructions: do not count a household more than once&nbsp;\",\"comVerif\":null,\"conds\":[]},\"p_7_7_7_42\":{\"puntos\":1,\"orden\":9,\"muestra\":1,\"identificador\":\"p_7_7_7_42\",\"influyeValor\":0,\"tipo\":\"num\",\"id\":42,\"pregunta\":\"Total number of households receiving PKT\",\"respuestas\":[],\"justif\":0,\"comShopper\":\"\",\"comVerif\":null,\"conds\":[]},\"p_7_7_7_43\":{\"puntos\":1,\"orden\":10,\"muestra\":1,\"identificador\":\"p_7_7_7_43\",\"influyeValor\":0,\"tipo\":\"num\",\"id\":43,\"pregunta\":\"Number of households who own agricultural land, but are not cultivating because of the COVID crisis?\",\"respuestas\":[],\"justif\":0,\"comShopper\":\"\",\"comVerif\":null,\"conds\":[]},\"p_7_7_7_44\":{\"puntos\":1,\"orden\":11,\"muestra\":1,\"identificador\":\"p_7_7_7_44\",\"influyeValor\":0,\"tipo\":\"mult\",\"id\":44,\"pregunta\":\"Have there been any instances of local conflicts in your RW over the last month?&nbsp;\",\"respuestas\":{\"1\":[{\"id\":25,\"preguntasId\":44,\"respuesta\":\"Yes\",\"valor\":\"1\",\"identificador\":null,\"orden\":0,\"elim\":null,\"justif\":null}],\"0\":[{\"id\":26,\"preguntasId\":44,\"respuesta\":\"No\",\"valor\":\"0\",\"identificador\":null,\"orden\":1,\"elim\":null,\"justif\":null}],\"2\":[{\"id\":27,\"preguntasId\":44,\"respuesta\":\"I dont know\",\"valor\":\"2\",\"identificador\":null,\"orden\":2,\"elim\":1,\"justif\":null}]},\"justif\":0,\"comShopper\":\"\",\"comVerif\":null,\"conds\":[]},\"p_7_7_7_45\":{\"puntos\":1,\"orden\":12,\"muestra\":1,\"identificador\":\"p_7_7_7_45\",\"influyeValor\":1,\"tipo\":\"mult\",\"id\":45,\"pregunta\":\"If yes, is the conflict between families, within families or both?&nbsp;\",\"respuestas\":{\"1\":[{\"id\":28,\"preguntasId\":45,\"respuesta\":\"Between families\",\"valor\":\"1\",\"identificador\":\"r_7_7_7_45_128\",\"orden\":1,\"elim\":null,\"justif\":0}],\"2\":[{\"id\":29,\"preguntasId\":45,\"respuesta\":\"Within families\",\"valor\":\"2\",\"identificador\":\"r_7_7_7_45_229\",\"orden\":2,\"elim\":null,\"justif\":0}],\"3\":[{\"id\":30,\"preguntasId\":45,\"respuesta\":\"Both?\",\"valor\":\"3\",\"identificador\":\"r_7_7_7_45_330\",\"orden\":3,\"elim\":null,\"justif\":0}]},\"justif\":0,\"comShopper\":\"\",\"comVerif\":null,\"conds\":[{\"id\":5,\"aplicacion\":\"preg\",\"eleId\":45,\"condicion\":\"val(p_7_7_7_44) = 0\",\"accion\":2,\"valor\":\"-\",\"orden\":0}]},\"p_7_7_7_46\":{\"puntos\":1,\"orden\":13,\"muestra\":1,\"identificador\":\"p_7_7_7_46\",\"influyeValor\":0,\"tipo\":\"num\",\"id\":46,\"pregunta\":\"Numbers of cases of domestic violence (reported\\/unreported?)\",\"respuestas\":[],\"justif\":0,\"comShopper\":\"\",\"comVerif\":null,\"conds\":[]},\"p_7_7_7_47\":{\"puntos\":1,\"orden\":14,\"muestra\":1,\"identificador\":\"p_7_7_7_47\",\"influyeValor\":0,\"tipo\":\"mult\",\"id\":47,\"pregunta\":\"Has conflict changed in the last month compared to previous months?&nbsp;\",\"respuestas\":{\"1\":[{\"id\":31,\"preguntasId\":47,\"respuesta\":\"No change\",\"valor\":\"1\",\"identificador\":\"r_7_7_7_47_131\",\"orden\":1,\"elim\":null,\"justif\":0}],\"2\":[{\"id\":32,\"preguntasId\":47,\"respuesta\":\"Increased\",\"valor\":\"2\",\"identificador\":\"r_7_7_7_47_232\",\"orden\":2,\"elim\":null,\"justif\":0},{\"id\":33,\"preguntasId\":47,\"respuesta\":\"Decreased\",\"valor\":\"2\",\"identificador\":\"r_7_7_7_47_333\",\"orden\":3,\"elim\":null,\"justif\":0}]},\"justif\":0,\"comShopper\":\"\",\"comVerif\":null,\"conds\":[]},\"p_7_7_7_48\":{\"puntos\":1,\"orden\":15,\"muestra\":1,\"identificador\":\"p_7_7_7_48\",\"influyeValor\":0,\"tipo\":\"ab\",\"id\":48,\"pregunta\":\"What kind of support would be helpful for your village during this period?\",\"respuestas\":[],\"justif\":0,\"comShopper\":\"\",\"comVerif\":null,\"conds\":[]},\"p_7_7_7_49\":{\"puntos\":1,\"orden\":16,\"muestra\":1,\"identificador\":\"p_7_7_7_49\",\"influyeValor\":0,\"tipo\":\"num\",\"id\":49,\"pregunta\":\"<b style=\\\"color: rgb(0, 0, 0); font-size: medium;\\\"><span lang=\\\"EN-US\\\" style=\\\"font-size: 11pt; line-height: 15.6933px; font-family: Calibri, sans-serif;\\\">Number of monthly deaths<\\/span><\\/b>\",\"respuestas\":[],\"justif\":0,\"comShopper\":\"\",\"comVerif\":null,\"conds\":[]}},\"conds\":[]}},\"conds\":[]}}}'),(30,5,'{\"tipoProm\":1,\"tipoAnalisis\":1,\"conds\":[],\"bloques\":{\"b_5_5\":{\"max\":0,\"valor\":0,\"muestra\":1,\"nombre\":\"One time\",\"tipoProm\":1,\"valMax\":100,\"encabezado\":0,\"areas\":{\"a_5_5_5\":{\"max\":0,\"valor\":0,\"muestra\":1,\"nombre\":\"One time\",\"valMax\":100,\"preguntas\":{\"p_5_5_5_8\":{\"puntos\":1,\"orden\":1,\"muestra\":1,\"identificador\":\"p_5_5_5_8\",\"influyeValor\":0,\"tipo\":\"ab\",\"id\":8,\"pregunta\":\"What is the name of your RW?\",\"respuestas\":[],\"justif\":0,\"comShopper\":\"\",\"comVerif\":null,\"conds\":[]},\"p_5_5_5_9\":{\"puntos\":1,\"orden\":2,\"muestra\":1,\"identificador\":\"p_5_5_5_9\",\"influyeValor\":0,\"tipo\":\"num\",\"id\":9,\"pregunta\":\"How many people live in your RW?\",\"respuestas\":[],\"justif\":0,\"comShopper\":\"\",\"comVerif\":null,\"conds\":[]},\"p_5_5_5_10\":{\"puntos\":1,\"orden\":3,\"muestra\":1,\"identificador\":\"p_5_5_5_10\",\"influyeValor\":0,\"tipo\":\"num\",\"id\":10,\"pregunta\":\"How many people aged 65 or older live in your RW?&nbsp;\",\"respuestas\":[],\"justif\":0,\"comShopper\":\"\",\"comVerif\":null,\"conds\":[]},\"p_5_5_5_130\":{\"puntos\":1,\"orden\":4,\"muestra\":1,\"identificador\":\"p_5_5_5_130\",\"influyeValor\":0,\"tipo\":\"num\",\"id\":130,\"pregunta\":\"How many single mothers live in your RW?\",\"respuestas\":[],\"justif\":0,\"comShopper\":\"\",\"comVerif\":null,\"conds\":[]},\"p_5_5_5_131\":{\"puntos\":1,\"orden\":5,\"muestra\":1,\"identificador\":\"p_5_5_5_131\",\"influyeValor\":0,\"tipo\":\"num\",\"id\":131,\"pregunta\":\"How many people have underlying medical conditions in your RW?\",\"respuestas\":[],\"justif\":0,\"comShopper\":\"\",\"comVerif\":null,\"conds\":[]}},\"conds\":[]}},\"conds\":[]}}}'),(34,27,'{\"tipoProm\":1,\"tipoAnalisis\":1,\"conds\":[],\"bloques\":{\"b_27_32\":{\"max\":0,\"valor\":0,\"muestra\":1,\"nombre\":\"Hospital\",\"tipoProm\":1,\"valMax\":100,\"encabezado\":0,\"areas\":{\"a_27_32_34\":{\"max\":0,\"valor\":0,\"muestra\":1,\"nombre\":\"Hospital\",\"valMax\":100,\"preguntas\":{\"p_27_32_34_165\":{\"puntos\":1,\"orden\":1,\"muestra\":1,\"identificador\":\"p_27_32_34_165\",\"influyeValor\":1,\"tipo\":\"num\",\"id\":165,\"pregunta\":\"<p>Jumlah tempat tidur<\\/p>\",\"respuestas\":[],\"justif\":0,\"comShopper\":\"\",\"comVerif\":null,\"conds\":[]},\"p_27_32_34_166\":{\"puntos\":1,\"orden\":2,\"muestra\":1,\"identificador\":\"p_27_32_34_166\",\"influyeValor\":1,\"tipo\":\"num\",\"id\":166,\"pregunta\":\"<p>Jumlah bed r isolasi<\\/p>\",\"respuestas\":[],\"justif\":0,\"comShopper\":\"\",\"comVerif\":null,\"conds\":[]},\"p_27_32_34_167\":{\"puntos\":1,\"orden\":3,\"muestra\":1,\"identificador\":\"p_27_32_34_167\",\"influyeValor\":1,\"tipo\":\"op\",\"id\":167,\"pregunta\":\"<p>location<\\/p>\",\"respuestas\":[],\"justif\":0,\"comShopper\":\"\",\"comVerif\":null,\"conds\":[]}},\"conds\":[]}},\"conds\":[]}}}'),(35,28,'{\"tipoProm\":1,\"tipoAnalisis\":1,\"conds\":[],\"bloques\":{\"b_28_33\":{\"max\":0,\"valor\":0,\"muestra\":1,\"nombre\":\"Social Security Assistance\",\"tipoProm\":1,\"valMax\":100,\"encabezado\":0,\"areas\":{\"a_28_33_35\":{\"max\":0,\"valor\":0,\"muestra\":1,\"nombre\":\"National Social Security Programs \",\"valMax\":100,\"preguntas\":{\"p_28_33_35_168\":{\"puntos\":1,\"orden\":1,\"muestra\":1,\"identificador\":\"p_28_33_35_168\",\"influyeValor\":0,\"tipo\":\"num\",\"id\":168,\"pregunta\":\"How many households are receiving BPNT\\/Rastra?&nbsp;&nbsp;\",\"respuestas\":[],\"justif\":0,\"comShopper\":\"\",\"comVerif\":null,\"conds\":[]},\"p_28_33_35_169\":{\"puntos\":1,\"orden\":2,\"muestra\":1,\"identificador\":\"p_28_33_35_169\",\"influyeValor\":1,\"tipo\":\"cm\",\"id\":169,\"pregunta\":\"Where are these households located?&nbsp;\",\"respuestas\":[],\"justif\":0,\"comShopper\":\"\",\"comVerif\":null,\"conds\":[{\"id\":7,\"aplicacion\":\"preg\",\"eleId\":169,\"condicion\":\"val (p_28_33_35_168) = 0\",\"accion\":2,\"valor\":\"1\",\"orden\":0}]},\"p_28_33_35_170\":{\"puntos\":1,\"orden\":3,\"muestra\":1,\"identificador\":\"p_28_33_35_170\",\"influyeValor\":0,\"tipo\":\"num\",\"id\":170,\"pregunta\":\"How many households are receiving PKH (Conditional Cash Transfers)?&nbsp;\",\"respuestas\":[],\"justif\":0,\"comShopper\":\"\",\"comVerif\":null,\"conds\":[{\"id\":8,\"aplicacion\":\"preg\",\"eleId\":170,\"condicion\":\"val (p_28_33_35_170) = 0\",\"accion\":2,\"valor\":\"1\",\"orden\":0}]},\"p_28_33_35_171\":{\"puntos\":1,\"orden\":4,\"muestra\":1,\"identificador\":\"p_28_33_35_171\",\"influyeValor\":1,\"tipo\":\"cm\",\"id\":171,\"pregunta\":\"Where are these households located?\",\"respuestas\":[],\"justif\":0,\"comShopper\":\"\",\"comVerif\":null,\"conds\":[]},\"p_28_33_35_172\":{\"puntos\":1,\"orden\":5,\"muestra\":1,\"identificador\":\"p_28_33_35_172\",\"influyeValor\":1,\"tipo\":\"num\",\"id\":172,\"pregunta\":\"How many households are receiving PKT (Cash for Work)\",\"respuestas\":[],\"justif\":0,\"comShopper\":\"\",\"comVerif\":null,\"conds\":[]},\"p_28_33_35_173\":{\"puntos\":1,\"orden\":6,\"muestra\":1,\"identificador\":\"p_28_33_35_173\",\"influyeValor\":1,\"tipo\":\"cm\",\"id\":173,\"pregunta\":\"Where are these households located?\",\"respuestas\":[],\"justif\":0,\"comShopper\":\"\",\"comVerif\":null,\"conds\":[{\"id\":9,\"aplicacion\":\"preg\",\"eleId\":173,\"condicion\":\"val (p_28_33_35_172) = 0\",\"accion\":2,\"valor\":\"1\",\"orden\":0}]}},\"conds\":[]},\"a_28_33_36\":{\"max\":0,\"valor\":0,\"muestra\":1,\"nombre\":\"Local Government Programs\",\"valMax\":100,\"preguntas\":{\"p_28_33_36_174\":{\"puntos\":1,\"orden\":1,\"muestra\":1,\"identificador\":\"p_28_33_36_174\",\"influyeValor\":0,\"tipo\":\"num\",\"id\":174,\"pregunta\":\"How many households are receiving BLT (Direct Cash Transfer)?&nbsp;\",\"respuestas\":[],\"justif\":0,\"comShopper\":\"\",\"comVerif\":null,\"conds\":[]},\"p_28_33_36_175\":{\"puntos\":1,\"orden\":2,\"muestra\":1,\"identificador\":\"p_28_33_36_175\",\"influyeValor\":1,\"tipo\":\"cm\",\"id\":175,\"pregunta\":\"Where are these households located?&nbsp;\",\"respuestas\":[],\"justif\":0,\"comShopper\":\"\",\"comVerif\":null,\"conds\":[{\"id\":10,\"aplicacion\":\"preg\",\"eleId\":175,\"condicion\":\"val (p_28_33_36_174) = 0\",\"accion\":2,\"valor\":\"1\",\"orden\":0}]},\"p_28_33_36_176\":{\"puntos\":1,\"orden\":3,\"muestra\":1,\"identificador\":\"p_28_33_36_176\",\"influyeValor\":0,\"tipo\":\"num\",\"id\":176,\"pregunta\":\"How many households are receiving direct food assistance?&nbsp;\",\"respuestas\":[],\"justif\":0,\"comShopper\":\"\",\"comVerif\":null,\"conds\":[]},\"p_28_33_36_177\":{\"puntos\":1,\"orden\":4,\"muestra\":1,\"identificador\":\"p_28_33_36_177\",\"influyeValor\":0,\"tipo\":\"cm\",\"id\":177,\"pregunta\":\"Where are these households located?&nbsp;\",\"respuestas\":[],\"justif\":0,\"comShopper\":\"\",\"comVerif\":null,\"conds\":[{\"id\":11,\"aplicacion\":\"preg\",\"eleId\":177,\"condicion\":\"val (p_28_33_36_177) = 0\",\"accion\":2,\"valor\":\"1\",\"orden\":0}]},\"p_28_33_36_178\":{\"puntos\":1,\"orden\":5,\"muestra\":1,\"identificador\":\"p_28_33_36_178\",\"influyeValor\":0,\"tipo\":\"num\",\"id\":178,\"pregunta\":\"How many households are receiving sanitizer and face masks?\\u00a0\",\"respuestas\":[],\"justif\":0,\"comShopper\":\"\",\"comVerif\":null,\"conds\":[]},\"p_28_33_36_179\":{\"puntos\":1,\"orden\":6,\"muestra\":1,\"identificador\":\"p_28_33_36_179\",\"influyeValor\":0,\"tipo\":\"cm\",\"id\":179,\"pregunta\":\"Where are these households located?&nbsp;\",\"respuestas\":[],\"justif\":0,\"comShopper\":\"\",\"comVerif\":null,\"conds\":[{\"id\":12,\"aplicacion\":\"preg\",\"eleId\":179,\"condicion\":\"val (p_28_33_36_178) = 0\",\"accion\":2,\"valor\":\"1\",\"orden\":0}]}},\"conds\":[]}},\"conds\":[]}}}'),(37,30,'{\"tipoProm\":1,\"tipoAnalisis\":1,\"conds\":[],\"bloques\":{\"b_30_36\":{\"max\":0,\"valor\":0,\"muestra\":1,\"nombre\":\"Migration (in-out)\",\"tipoProm\":1,\"valMax\":100,\"encabezado\":0,\"areas\":{\"a_30_36_39\":{\"max\":0,\"valor\":0,\"muestra\":1,\"nombre\":\"Arriving\",\"valMax\":100,\"preguntas\":{\"p_30_36_39_180\":{\"puntos\":1,\"orden\":1,\"muestra\":1,\"identificador\":\"p_30_36_39_180\",\"influyeValor\":0,\"tipo\":\"num\",\"id\":180,\"pregunta\":\"How many people arrived in your area this week?&nbsp;\",\"respuestas\":[],\"justif\":0,\"comShopper\":\"\",\"comVerif\":null,\"conds\":[]},\"p_30_36_39_194\":{\"puntos\":1,\"orden\":2,\"muestra\":1,\"identificador\":\"p_30_36_39_194\",\"influyeValor\":0,\"tipo\":\"sub\",\"id\":194,\"pregunta\":\"How many of these travelers came from a ...\",\"respuestas\":[],\"justif\":0,\"comShopper\":\"\",\"comVerif\":null,\"conds\":[],\"subpregs\":{\"p_30_36_39_195\":{\"puntos\":1,\"muestra\":1,\"identificador\":\"p_30_36_39_195\",\"influyeValor\":0,\"tipo\":\"num\",\"id\":195,\"pregunta\":\"Kelurahan?\",\"respuestas\":[],\"justif\":0,\"stipo\":\"subPregs\",\"conds\":[]},\"p_30_36_39_196\":{\"puntos\":1,\"muestra\":1,\"identificador\":\"p_30_36_39_196\",\"influyeValor\":0,\"tipo\":\"num\",\"id\":196,\"pregunta\":\"Kecamatan?\",\"respuestas\":[],\"justif\":0,\"stipo\":\"subPregs\",\"conds\":[]},\"p_30_36_39_197\":{\"puntos\":1,\"muestra\":1,\"identificador\":\"p_30_36_39_197\",\"influyeValor\":0,\"tipo\":\"num\",\"id\":197,\"pregunta\":\"City\\/Regency?\",\"respuestas\":[],\"justif\":0,\"stipo\":\"subPregs\",\"conds\":[]},\"p_30_36_39_198\":{\"puntos\":1,\"muestra\":1,\"identificador\":\"p_30_36_39_198\",\"influyeValor\":0,\"tipo\":\"num\",\"id\":198,\"pregunta\":\"Country?\",\"respuestas\":[],\"justif\":0,\"stipo\":\"subPregs\",\"conds\":[]}}},\"p_30_36_39_182\":{\"puntos\":1,\"orden\":7,\"muestra\":1,\"identificador\":\"p_30_36_39_182\",\"influyeValor\":0,\"tipo\":\"num\",\"id\":182,\"pregunta\":\"How many incoming travelers are presenting COVID-19 symptoms?&nbsp;\",\"respuestas\":[],\"justif\":0,\"comShopper\":\"\",\"comVerif\":null,\"conds\":[]}},\"conds\":[]},\"a_30_36_40\":{\"max\":0,\"valor\":0,\"muestra\":1,\"nombre\":\"Leaving\",\"valMax\":100,\"preguntas\":{\"p_30_36_40_183\":{\"puntos\":1,\"orden\":1,\"muestra\":1,\"identificador\":\"p_30_36_40_183\",\"influyeValor\":0,\"tipo\":\"num\",\"id\":183,\"pregunta\":\"How many people left your area this week?\",\"respuestas\":[],\"justif\":0,\"comShopper\":\"\",\"comVerif\":null,\"conds\":[]},\"p_30_36_40_199\":{\"puntos\":1,\"orden\":3,\"muestra\":1,\"identificador\":\"p_30_36_40_199\",\"influyeValor\":0,\"tipo\":\"sub\",\"id\":199,\"pregunta\":\"How many travelers were heading to ...\",\"respuestas\":[],\"justif\":0,\"comShopper\":\"\",\"comVerif\":null,\"conds\":[],\"subpregs\":{\"p_30_36_40_200\":{\"puntos\":1,\"muestra\":1,\"identificador\":\"p_30_36_40_200\",\"influyeValor\":0,\"tipo\":\"num\",\"id\":200,\"pregunta\":\"Kelurahan?\",\"respuestas\":[],\"justif\":0,\"stipo\":\"subPregs\",\"conds\":[]},\"p_30_36_40_201\":{\"puntos\":1,\"muestra\":1,\"identificador\":\"p_30_36_40_201\",\"influyeValor\":0,\"tipo\":\"num\",\"id\":201,\"pregunta\":\"Kecamatan?\",\"respuestas\":[],\"justif\":0,\"stipo\":\"subPregs\",\"conds\":[]},\"p_30_36_40_202\":{\"puntos\":1,\"muestra\":1,\"identificador\":\"p_30_36_40_202\",\"influyeValor\":0,\"tipo\":\"num\",\"id\":202,\"pregunta\":\"City\\/Regency?\",\"respuestas\":[],\"justif\":0,\"stipo\":\"subPregs\",\"conds\":[]},\"p_30_36_40_203\":{\"puntos\":1,\"muestra\":1,\"identificador\":\"p_30_36_40_203\",\"influyeValor\":0,\"tipo\":\"num\",\"id\":203,\"pregunta\":\"Country?\",\"respuestas\":[],\"justif\":0,\"stipo\":\"subPregs\",\"conds\":[]}}}},\"conds\":[]}},\"conds\":[]},\"b_30_37\":{\"max\":0,\"valor\":0,\"muestra\":1,\"nombre\":\"Food security\",\"tipoProm\":1,\"valMax\":100,\"encabezado\":0,\"areas\":{\"a_30_37_41\":{\"max\":0,\"valor\":0,\"muestra\":1,\"nombre\":\"Food security\",\"valMax\":100,\"preguntas\":{\"p_30_37_41_185\":{\"puntos\":1,\"orden\":1,\"muestra\":1,\"identificador\":\"p_30_37_41_185\",\"influyeValor\":0,\"tipo\":\"num\",\"id\":185,\"pregunta\":\"In the last week, how many households have been unable to buy rice or other basic foods?&nbsp;\",\"respuestas\":[],\"justif\":0,\"comShopper\":\"\",\"comVerif\":null,\"conds\":[]},\"p_30_37_41_186\":{\"puntos\":1,\"orden\":2,\"muestra\":1,\"identificador\":\"p_30_37_41_186\",\"influyeValor\":0,\"tipo\":\"num\",\"id\":186,\"pregunta\":\"How many grocery stores and traditional markets are in your neighborhood?&nbsp;\",\"respuestas\":[],\"justif\":0,\"comShopper\":\"\",\"comVerif\":null,\"conds\":[]},\"p_30_37_41_187\":{\"puntos\":1,\"orden\":3,\"muestra\":1,\"identificador\":\"p_30_37_41_187\",\"influyeValor\":0,\"tipo\":\"num\",\"id\":187,\"pregunta\":\"How many grocery stores and traditional markets are still open?\",\"respuestas\":[],\"justif\":0,\"comShopper\":\"\",\"comVerif\":null,\"conds\":[{\"id\":13,\"aplicacion\":\"preg\",\"eleId\":187,\"condicion\":\"val(p_30_37_41_186) = 0\",\"accion\":2,\"valor\":\"1\",\"orden\":0}]},\"p_30_37_41_188\":{\"puntos\":1,\"orden\":4,\"muestra\":1,\"identificador\":\"p_30_37_41_188\",\"influyeValor\":0,\"tipo\":\"cm\",\"id\":188,\"pregunta\":\"Locate the stores and markets open.\",\"respuestas\":[],\"justif\":0,\"comShopper\":\"\",\"comVerif\":null,\"conds\":[{\"id\":14,\"aplicacion\":\"preg\",\"eleId\":188,\"condicion\":\"val (p_30_37_41_186) = 0\",\"accion\":2,\"valor\":\"1\",\"orden\":0}]},\"p_30_37_41_189\":{\"puntos\":1,\"orden\":5,\"muestra\":1,\"identificador\":\"p_30_37_41_189\",\"influyeValor\":0,\"tipo\":\"num\",\"id\":189,\"pregunta\":\"In the last week, how many households in your neighborhood were hungry but did not eat due to limited money or other resources of food?&nbsp;\",\"respuestas\":[],\"justif\":0,\"comShopper\":\"\",\"comVerif\":null,\"conds\":[]},\"p_30_37_41_190\":{\"puntos\":1,\"orden\":6,\"muestra\":1,\"identificador\":\"p_30_37_41_190\",\"influyeValor\":0,\"tipo\":\"num\",\"id\":190,\"pregunta\":\"In the last week, were there any children (0-5 years old) in your neighborhood hungry but did not eat due to limited money or other resources of food?\",\"respuestas\":[],\"justif\":0,\"comShopper\":\"\",\"comVerif\":null,\"conds\":[]},\"p_30_37_41_191\":{\"puntos\":1,\"orden\":7,\"muestra\":1,\"identificador\":\"p_30_37_41_191\",\"influyeValor\":0,\"tipo\":\"num\",\"id\":191,\"pregunta\":\"How many children?&nbsp;\",\"respuestas\":[],\"justif\":0,\"comShopper\":\"\",\"comVerif\":null,\"conds\":[{\"id\":15,\"aplicacion\":\"preg\",\"eleId\":191,\"condicion\":\"val (p_30_37_41_190) = 0 \",\"accion\":2,\"valor\":\"1\",\"orden\":0}]},\"p_30_37_41_192\":{\"puntos\":1,\"orden\":8,\"muestra\":1,\"identificador\":\"p_30_37_41_192\",\"influyeValor\":0,\"tipo\":\"mult\",\"id\":192,\"pregunta\":\"What is the main reason for not being able to buy rice\\/other basic food?\",\"respuestas\":{\"1\":[{\"id\":171,\"preguntasId\":192,\"respuesta\":\"Groceries\\/shops have run out of stock\",\"valor\":\"1\",\"identificador\":\"r_30_37_41_192_1171\",\"orden\":1,\"elim\":null,\"justif\":0},{\"id\":172,\"preguntasId\":192,\"respuesta\":\"Traditional markets not operating\\/closed\",\"valor\":\"1\",\"identificador\":\"r_30_37_41_192_2172\",\"orden\":2,\"elim\":null,\"justif\":0},{\"id\":173,\"preguntasId\":192,\"respuesta\":\"Limited\\/no transportation\",\"valor\":\"1\",\"identificador\":\"r_30_37_41_192_3173\",\"orden\":3,\"elim\":null,\"justif\":0},{\"id\":174,\"preguntasId\":192,\"respuesta\":\"Restriction to go outside\",\"valor\":\"1\",\"identificador\":\"r_30_37_41_192_4174\",\"orden\":4,\"elim\":null,\"justif\":0},{\"id\":175,\"preguntasId\":192,\"respuesta\":\"Increase in price\",\"valor\":\"1\",\"identificador\":\"r_30_37_41_192_5175\",\"orden\":5,\"elim\":null,\"justif\":0},{\"id\":176,\"preguntasId\":192,\"respuesta\":\"No access to cash and cannot pay with credit card\",\"valor\":\"1\",\"identificador\":\"r_30_37_41_192_6176\",\"orden\":6,\"elim\":null,\"justif\":0},{\"id\":177,\"preguntasId\":192,\"respuesta\":\"Other\",\"valor\":\"1\",\"identificador\":\"r_30_37_41_192_7177\",\"orden\":7,\"elim\":null,\"justif\":1}]},\"justif\":0,\"comShopper\":\"\",\"comVerif\":null,\"conds\":[]}},\"conds\":[]}},\"conds\":[]}}}');
/*!40000 ALTER TABLE `ChecklistEst` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ChecklistImagenes`
--

DROP TABLE IF EXISTS `ChecklistImagenes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ChecklistImagenes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `checklistId` int(11) DEFAULT NULL,
  `archivo` varchar(100) DEFAULT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_CheklistsImagenes_Checklist1_idx` (`checklistId`),
  CONSTRAINT `fk_CheklistsImagenes_Checklist1` FOREIGN KEY (`checklistId`) REFERENCES `Checklist` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ChecklistImagenes`
--

LOCK TABLES `ChecklistImagenes` WRITE;
/*!40000 ALTER TABLE `ChecklistImagenes` DISABLE KEYS */;
/*!40000 ALTER TABLE `ChecklistImagenes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Condicionales`
--

DROP TABLE IF EXISTS `Condicionales`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Condicionales` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `aplicacion` varchar(10) DEFAULT NULL,
  `eleId` int(11) DEFAULT NULL,
  `condicion` text DEFAULT NULL,
  `accion` int(11) DEFAULT NULL,
  `valor` text DEFAULT NULL,
  `orden` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Condicionales`
--

LOCK TABLES `Condicionales` WRITE;
/*!40000 ALTER TABLE `Condicionales` DISABLE KEYS */;
INSERT INTO `Condicionales` VALUES (2,'preg',3,'val(p_2_1_1_1) = 0',1,'5',0),(4,'preg',17,'val(p_6_6_6_16) = 0',2,'-',0),(5,'preg',45,'val(p_7_7_7_44) = 0',2,'-',0),(6,'preg',143,'val(p_23_28_28_142) = 0',2,'-',0),(7,'preg',169,'val (p_28_33_35_168) = 0',2,'1',0),(8,'preg',170,'val (p_28_33_35_170) = 0',2,'1',0),(9,'preg',173,'val (p_28_33_35_172) = 0',2,'1',0),(10,'preg',175,'val (p_28_33_36_174) = 0',2,'1',0),(11,'preg',177,'val (p_28_33_36_177) = 0',2,'1',0),(12,'preg',179,'val (p_28_33_36_178) = 0',2,'1',0),(13,'preg',187,'val(p_30_37_41_186) = 0',2,'1',0),(14,'preg',188,'val (p_30_37_41_186) = 0',2,'1',0),(15,'preg',191,'val (p_30_37_41_190) = 0 ',2,'1',0),(16,'preg',207,'val(p_32_39_43_206) = 0',2,'3',0),(17,'preg',209,'val(p_32_39_43_208) = 0',2,'4',0);
/*!40000 ALTER TABLE `Condicionales` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Dimensiones`
--

DROP TABLE IF EXISTS `Dimensiones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Dimensiones` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) DEFAULT NULL,
  `nivel` tinyint(4) DEFAULT NULL,
  `elemId` int(11) DEFAULT NULL,
  `type` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Dimensiones`
--

LOCK TABLES `Dimensiones` WRITE;
/*!40000 ALTER TABLE `Dimensiones` DISABLE KEYS */;
INSERT INTO `Dimensiones` VALUES (2,'nama_rumah_sakit',1,11,'structure'),(3,'Kelurahan',1,12,'structure'),(4,'RT',1,13,'structure');
/*!40000 ALTER TABLE `Dimensiones` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `DimensionesElem`
--

DROP TABLE IF EXISTS `DimensionesElem`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `DimensionesElem` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `padre` int(11) DEFAULT NULL,
  `dimensionesId` int(11) DEFAULT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_DimensionesElem_DimensionesElem1_idx` (`padre`),
  KEY `fk_DimensionesElem_Dimensiones1_idx` (`dimensionesId`),
  CONSTRAINT `fk_DimensionesElem_Dimensiones1` FOREIGN KEY (`dimensionesId`) REFERENCES `Dimensiones` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `fk_DimensionesElem_DimensionesElem1` FOREIGN KEY (`padre`) REFERENCES `DimensionesElem` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=72 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `DimensionesElem`
--

LOCK TABLES `DimensionesElem` WRITE;
/*!40000 ALTER TABLE `DimensionesElem` DISABLE KEYS */;
INSERT INTO `DimensionesElem` VALUES (0,NULL,NULL,NULL),(32,0,2,'RSUP Kariadi'),(33,0,2,'RSU Telogorejo'),(34,0,2,'RSU St. Elizabeth'),(35,0,2,'RSU Panti Wilasa Citarum'),(36,0,2,'RSU Panti Wilasa dr.Cipto'),(37,0,2,'RSU Roemani'),(38,0,2,'RSU Sultan Agung'),(39,0,2,'RSU Bhakti Wiratamtama'),(40,0,2,'RSUD Kota Semarang'),(41,0,2,'RSU William Both'),(42,0,2,'RSU Tugurejo'),(43,0,2,'RSU Banyumanik'),(44,0,2,'RS Bhayangkara Akpol'),(45,0,2,'RS Bhayangkara POLDA'),(46,0,2,'RSU Permata Medika'),(47,0,2,'RS Hermina Pandanaran'),(48,0,2,'RS Hermina Banyumanik'),(49,0,2,'RS Columbia Asia'),(50,0,2,'RS Nasional Diponegoro'),(51,0,2,'RS Siloam Hospitals'),(52,0,2,'RSJ Dr. Amino Gondohutomo'),(53,0,2,'RSIA Anugerah'),(54,0,2,'RSIA Gunung Sawo'),(55,0,2,'RSIA Bunda'),(56,0,2,'RSIA Kusuma Pradja'),(57,0,2,'RSIA Plamongan Indah'),(58,0,2,'RSIA Ananda Pasar Ace'),(59,0,2,'RSI Gigi dan Mulut Sultan Agung'),(60,0,2,'RSGM Unimus'),(61,0,3,'Kelurahan 1'),(62,0,3,'Kelurahan 2'),(63,0,3,'Benito Juarez'),(64,0,3,'Kelurahan 3'),(65,0,4,'RT 1'),(66,0,4,'RT 1 '),(67,0,4,'RT 2'),(68,0,4,'RT 3'),(69,0,4,'RT 4'),(70,0,4,'RT 5'),(71,0,4,'RT 6');
/*!40000 ALTER TABLE `DimensionesElem` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Estatus`
--

DROP TABLE IF EXISTS `Estatus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Estatus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(45) DEFAULT NULL,
  `color` varchar(20) DEFAULT NULL,
  `icono` varchar(63) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Estatus`
--

LOCK TABLES `Estatus` WRITE;
/*!40000 ALTER TABLE `Estatus` DISABLE KEYS */;
/*!40000 ALTER TABLE `Estatus` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Frequencies`
--

DROP TABLE IF EXISTS `Frequencies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Frequencies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(15) DEFAULT NULL,
  `orden` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Frequencies`
--

LOCK TABLES `Frequencies` WRITE;
/*!40000 ALTER TABLE `Frequencies` DISABLE KEYS */;
INSERT INTO `Frequencies` VALUES (1,'oneTime',1),(2,'daily',2),(3,'weekly',3),(4,'2weeks',4),(5,'3weeks',5),(6,'monthly',6),(7,'2months',7),(8,'3months',8),(9,'4months',9),(10,'6months',10),(11,'yearly',11);
/*!40000 ALTER TABLE `Frequencies` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Multimedia`
--

DROP TABLE IF EXISTS `Multimedia`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Multimedia` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `visitasId` int(11) DEFAULT NULL,
  `tipo` varchar(31) DEFAULT NULL,
  `nombre` varchar(255) DEFAULT NULL,
  `archivo` varchar(255) DEFAULT NULL,
  `descripcion` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_table1_Visitas1_idx` (`visitasId`),
  CONSTRAINT `fk_table1_Visitas1` FOREIGN KEY (`visitasId`) REFERENCES `Visitas` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Multimedia`
--

LOCK TABLES `Multimedia` WRITE;
/*!40000 ALTER TABLE `Multimedia` DISABLE KEYS */;
/*!40000 ALTER TABLE `Multimedia` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Points`
--

DROP TABLE IF EXISTS `Points`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Points` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `problemsId` int(11) DEFAULT NULL,
  `lat` double DEFAULT NULL,
  `lng` double DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_Puntos_Problems1_idx` (`problemsId`),
  CONSTRAINT `fk_Puntos_Problems1` FOREIGN KEY (`problemsId`) REFERENCES `Problems` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Points`
--

LOCK TABLES `Points` WRITE;
/*!40000 ALTER TABLE `Points` DISABLE KEYS */;
INSERT INTO `Points` VALUES (1,1,-5.02618066604941,32.806287815445856),(2,2,-6.800350366927789,39.12875175476075),(3,3,-6.9942118,110.4074897),(4,4,-6.988027,110.426464),(5,5,-7.0081259,110.4193678),(6,6,-6.9702528,110.4395854),(7,7,-6.9732769,110.4344201),(8,8,-7.0010931,110.4279077),(9,9,-6.9555019,110.4613344),(10,10,-6.9866701,110.4081965),(11,11,-7.033956,110.466789),(12,12,-6.9968428,110.4050627),(13,13,-6.9845657,110.3559326),(14,14,-7.0637815,110.4164815),(15,15,-7.0165856,110.4097546),(16,16,-7.0002655,110.4462453),(17,17,-7.0001923,110.3421959),(18,18,-6.985675,110.4126031),(19,19,-7.0727976,110.411677),(20,20,-6.984308,110.382903),(21,21,-7.0479579,110.4439775),(22,22,-6.9990602,110.4339636),(23,23,-7.0087348,110.4641215),(24,24,-6.9926054,110.4048426),(25,25,-6.9961927,110.4067333),(26,26,-6.9944813,110.4332992),(27,27,-6.9760041,110.4357457),(28,28,-7.0236841,110.4979331),(29,29,-7.0687745,110.3163248),(30,30,-6.955472,110.461952),(31,31,-7.0248387,110.4665603),(32,32,-7.0088973515151185,110.42496745613477),(33,33,-7.01366792756663,470.3961113768998),(34,34,-7.009578865370235,470.39358679504903);
/*!40000 ALTER TABLE `Points` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Preguntas`
--

DROP TABLE IF EXISTS `Preguntas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Preguntas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `areasId` int(11) DEFAULT NULL,
  `pregunta` text DEFAULT NULL,
  `comShopper` text DEFAULT NULL,
  `comVerif` text DEFAULT NULL,
  `tiposId` int(11) DEFAULT NULL,
  `puntos` double DEFAULT NULL,
  `orden` int(11) DEFAULT NULL,
  `subareasId` int(11) DEFAULT NULL,
  `identificador` varchar(45) DEFAULT NULL,
  `influyeValor` tinyint(4) DEFAULT NULL,
  `elim` tinyint(4) DEFAULT NULL,
  `justif` tinyint(4) DEFAULT NULL,
  `grafica` tinyint(4) DEFAULT NULL,
  `fichaTec` tinyint(4) DEFAULT NULL,
  `datTec` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_Preguntas_Areas1_idx` (`areasId`),
  KEY `fk_Preguntas_Tipos1_idx` (`tiposId`),
  KEY `identificador` (`identificador`),
  KEY `graficar` (`grafica`),
  CONSTRAINT `fk_Preguntas_Areas1` FOREIGN KEY (`areasId`) REFERENCES `Areas` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `fk_Preguntas_Tipos1` FOREIGN KEY (`tiposId`) REFERENCES `Tipos` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=210 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Preguntas`
--

LOCK TABLES `Preguntas` WRITE;
/*!40000 ALTER TABLE `Preguntas` DISABLE KEYS */;
INSERT INTO `Preguntas` VALUES (8,5,'What is the name of your RW?','',NULL,1,1,1,NULL,'p_5_5_5_8',0,NULL,0,NULL,0,NULL),(9,5,'How many people live in your RW?','',NULL,4,1,2,NULL,'p_5_5_5_9',0,NULL,0,NULL,0,NULL),(10,5,'How many people aged 65 or older live in your RW?&nbsp;','',NULL,4,1,3,NULL,'p_5_5_5_10',0,NULL,0,NULL,0,NULL),(11,5,'How many people have underlying medical conditions in your RW?','<i style=\"color: rgb(0, 0, 0); font-size: medium;\"><span lang=\"EN-US\" style=\"font-size: 10pt; line-height: 14.2667px; font-family: Calibri, sans-serif;\">Medical conditions include people with: (i) chronic lung disease or moderate to severe asthma, (ii) serious heart conditions, (iii) immunocompromised- this could include cancer treatment, smoking, bone marrow or organ transplantation, immune deficiencies, poorly controlled HIV or AIDS, and prolonged use of corticosteroids and other immune weakening medication, (iv) severe obesity (body mass index [BMI] of 40 or higher, (v) diabetes, (vi) chronic kidney disease undergoing dialysis, and (vii) liver disease</span></i>',NULL,4,1,4,NULL,'p_5_5_5_11',0,1,0,NULL,0,NULL),(12,5,'How many people are receiving cash grants/social assistance grants in your community?','',NULL,4,1,5,NULL,'p_5_5_5_12',0,1,0,NULL,0,NULL),(13,6,'What is the name of your RW?','',NULL,1,1,1,NULL,'p_6_6_6_13',0,NULL,0,NULL,0,NULL),(14,6,'Number of people showing Corona Virus symptoms in your RW?','',NULL,4,1,2,NULL,'p_6_6_6_14',0,NULL,0,NULL,0,NULL),(15,6,'Number of people showing symptoms of severe illness to Corona Virus.','',NULL,4,1,3,NULL,'p_6_6_6_15',0,NULL,0,NULL,0,NULL),(16,6,'Has anyone in your RW been tested for Corona Virus?&nbsp;','',NULL,2,1,4,NULL,'p_6_6_6_16',0,NULL,0,NULL,0,NULL),(17,6,'If anyone in your RW has been tested, how many have been confirmed positive for Corona Virus?&nbsp;','',NULL,4,1,5,NULL,'p_6_6_6_17',0,NULL,0,NULL,0,NULL),(18,6,'How many people with symptoms in your RW have recovered?&nbsp;','',NULL,4,1,6,NULL,'p_6_6_6_18',0,NULL,0,NULL,0,NULL),(19,6,'Number of deaths in the RW believed to be due to covid19?&nbsp;','',NULL,4,1,7,NULL,'p_6_6_6_19',0,NULL,0,NULL,0,NULL),(20,6,'Number of people in isolation and where are they isolated?&nbsp;','',NULL,3,1,8,NULL,'p_6_6_6_20',0,NULL,0,NULL,0,NULL),(21,6,'In their house:&nbsp;&nbsp;','',NULL,4,1,9,20,'p_6_6_6_21',0,NULL,0,NULL,NULL,NULL),(22,6,'In a formal health center','',NULL,4,1,10,20,'p_6_6_6_22',0,NULL,0,NULL,NULL,NULL),(23,6,'In another facility','',NULL,4,1,11,20,'p_6_6_6_23',0,NULL,0,NULL,NULL,NULL),(24,6,'Number of people who entered the village from other areas','',NULL,3,1,12,NULL,'p_6_6_6_24',0,NULL,0,NULL,0,NULL),(25,6,'From another country&nbsp;','',NULL,4,1,13,24,'p_6_6_6_25',0,NULL,0,NULL,NULL,NULL),(26,6,'From a major city&nbsp;','',NULL,4,1,14,24,'p_6_6_6_26',0,NULL,0,NULL,NULL,NULL),(27,6,'From outside the district','',NULL,4,1,15,24,'p_6_6_6_27',0,NULL,0,NULL,NULL,NULL),(28,6,'From another village inside the district','',NULL,4,1,16,24,'p_6_6_6_28',1,NULL,0,NULL,NULL,NULL),(29,6,'Number of people coming from outside the village that are self-quarantined?','',NULL,4,1,17,NULL,'p_6_6_6_29',0,NULL,0,NULL,0,NULL),(30,6,'Number of villagers receiving PKT','',NULL,3,1,18,NULL,'p_6_6_6_30',0,NULL,0,NULL,0,NULL),(31,6,'Number who are working&nbsp;','',NULL,4,1,19,30,'p_6_6_6_31',0,NULL,0,NULL,NULL,NULL),(32,6,'Number of people who were participating in PKT but were identified to have symptoms in the last week&nbsp;','',NULL,4,1,20,30,'p_6_6_6_32',0,NULL,0,NULL,NULL,NULL),(33,6,'Number receiving transfers but unable to work&nbsp;','',NULL,4,1,21,30,'p_6_6_6_33',0,NULL,0,NULL,NULL,NULL),(34,7,'What is the name of your RW?','',NULL,1,1,1,NULL,'p_7_7_7_34',0,NULL,0,NULL,0,NULL),(35,7,'Number of households where the main income earner(s) are unemployed','',NULL,4,1,2,NULL,'p_7_7_7_35',0,NULL,0,NULL,0,NULL),(36,7,'Number of households that have lost formal employment','',NULL,4,1,3,NULL,'p_7_7_7_36',0,NULL,0,NULL,0,NULL),(37,7,'Number of households that have lost informal employment','',NULL,4,1,4,NULL,'p_7_7_7_37',0,NULL,0,NULL,0,NULL),(38,7,'Number of households where the main income earner(s) are under employed or experienced a significant loss of income in the past month','',NULL,4,1,5,NULL,'p_7_7_7_38',0,NULL,0,NULL,0,NULL),(39,7,'Number of households with toddlers who are undernourished&nbsp;','',NULL,4,1,6,NULL,'p_7_7_7_39',0,NULL,0,NULL,0,NULL),(40,7,'Number of households who do not / will not have enough food for the next month&nbsp;','',NULL,4,1,7,NULL,'p_7_7_7_40',0,NULL,0,NULL,0,NULL),(41,7,'Total number of households who responded ‚Äòyes‚Äô to any one of the above questions.','&nbsp;Instructions: do not count a household more than once&nbsp;',NULL,4,1,8,NULL,'p_7_7_7_41',0,NULL,0,NULL,0,NULL),(42,7,'Total number of households receiving PKT','',NULL,4,1,9,NULL,'p_7_7_7_42',0,NULL,0,NULL,0,NULL),(43,7,'Number of households who own agricultural land, but are not cultivating because of the COVID crisis?','',NULL,4,1,10,NULL,'p_7_7_7_43',0,NULL,0,NULL,0,NULL),(44,7,'Have there been any instances of local conflicts in your RW over the last month?&nbsp;','',NULL,2,1,11,NULL,'p_7_7_7_44',0,NULL,0,NULL,0,NULL),(45,7,'If yes, is the conflict between families, within families or both?&nbsp;','',NULL,2,1,12,NULL,'p_7_7_7_45',1,NULL,0,NULL,0,NULL),(46,7,'Numbers of cases of domestic violence (reported/unreported?)','',NULL,4,1,13,NULL,'p_7_7_7_46',0,NULL,0,NULL,0,NULL),(47,7,'Has conflict changed in the last month compared to previous months?&nbsp;','',NULL,2,1,14,NULL,'p_7_7_7_47',0,NULL,0,NULL,0,NULL),(48,7,'What kind of support would be helpful for your village during this period?','',NULL,1,1,15,NULL,'p_7_7_7_48',0,NULL,0,NULL,0,NULL),(49,7,'<b style=\"color: rgb(0, 0, 0); font-size: medium;\"><span lang=\"EN-US\" style=\"font-size: 11pt; line-height: 15.6933px; font-family: Calibri, sans-serif;\">Number of monthly deaths</span></b>','',NULL,4,1,16,NULL,'p_7_7_7_49',0,NULL,0,NULL,0,NULL),(50,6,'asas','dsd',NULL,2,1,22,20,'p_6_6_6_50',1,1,0,NULL,NULL,NULL),(91,16,'Spatial','',NULL,6,1,1,NULL,'p_16_16_16_91',1,NULL,0,NULL,0,NULL),(94,18,'Select the facility','',NULL,2,1,1,NULL,'p_18_18_18_94',1,NULL,0,NULL,0,NULL),(95,18,'Capacity','Please select the percentage of capacity',NULL,4,1,2,NULL,'p_18_18_18_95',1,NULL,0,NULL,0,NULL),(96,18,'Please add any notes','',NULL,1,1,3,NULL,'p_18_18_18_96',1,NULL,0,NULL,0,NULL),(97,19,'Are food supplies sufficient?','',NULL,2,1,1,NULL,'p_18_19_19_97',1,NULL,0,NULL,0,NULL),(98,19,'Notes','Please provide comments based on current conditions',NULL,1,1,2,NULL,'p_18_19_19_98',1,NULL,0,NULL,0,NULL),(99,20,'Is personnel transportation working?','',NULL,2,1,1,NULL,'p_18_20_20_99',1,NULL,0,NULL,0,NULL),(101,22,'Name of market','',NULL,2,1,1,NULL,'p_20_22_22_101',1,NULL,0,NULL,0,NULL),(102,22,'<font color=\"#222222\" face=\"arial, sans-serif\"><span style=\"font-size: 16px;\">Is&nbsp;</span></font><span style=\"color: rgb(34, 34, 34); font-family: arial, sans-serif; font-size: 16px;\">Maize available</span>','',NULL,2,1,2,NULL,'p_20_22_22_102',1,NULL,0,NULL,0,NULL),(103,22,'Is&nbsp;<span style=\"color: rgb(34, 34, 34); font-family: arial, sans-serif; font-size: 16px;\">Rice available</span>','',NULL,2,1,3,NULL,'p_20_22_22_103',1,NULL,0,NULL,0,NULL),(104,22,'Are&nbsp;<span style=\"color: rgb(34, 34, 34); font-family: arial, sans-serif; font-size: 16px;\">Potatoes available</span>','',NULL,2,1,4,NULL,'p_20_22_22_104',1,NULL,0,NULL,0,NULL),(105,22,'Is&nbsp;<span style=\"color: rgb(34, 34, 34); font-family: arial, sans-serif; font-size: 16px;\">Millet available</span>','',NULL,2,1,5,NULL,'p_20_22_22_105',1,NULL,0,NULL,0,NULL),(106,22,'<span style=\"color: rgb(34, 34, 34); font-family: arial, sans-serif; font-size: 16px;\">Is Sorghum available</span>','',NULL,2,1,6,NULL,'p_20_22_22_106',1,NULL,0,NULL,0,NULL),(107,22,'<span style=\"color: rgb(34, 34, 34); font-family: arial, sans-serif; font-size: 16px;\">Are Dairy products available</span>','',NULL,2,1,7,NULL,'p_20_22_22_107',1,NULL,0,NULL,0,NULL),(108,23,'Please write your address and add instructions to get to your location.&nbsp;','All answers will remain confidential.¬†',NULL,1,1,2,NULL,'p_21_23_23_108',1,NULL,0,NULL,0,NULL),(109,23,'Please point the location of your house in the map. This will be highly useful to identify your location if you need help.','All answers will remain confidential.&nbsp;',NULL,6,1,1,NULL,'p_21_23_23_109',1,NULL,0,NULL,0,NULL),(110,24,'How many persons live with you?','All answers will remain confidential.&nbsp;',NULL,4,1,1,NULL,'p_21_24_24_110',1,NULL,0,NULL,0,NULL),(111,24,'How many rooms are there in your house?','All answers will remain confidential.&nbsp;',NULL,4,1,2,NULL,'p_21_24_24_111',1,NULL,0,NULL,0,NULL),(112,25,'Has anyone from your family recently experienced&nbsp;fever greater than 38 degrees?','',NULL,2,1,1,NULL,'p_21_25_25_112',1,NULL,0,NULL,0,NULL),(113,25,'Has anyone in your family experienced EYE PAIN in the last week?','',NULL,2,1,2,NULL,'p_21_25_25_113',1,NULL,0,NULL,0,NULL),(114,25,'Has anyone in your family experienced CHEST PAIN in the last week?','',NULL,2,1,3,NULL,'p_21_25_25_114',1,NULL,0,NULL,0,NULL),(115,25,'Has anyone in your family experienced MUSCLE PAIN in the last week?','',NULL,2,1,4,NULL,'p_21_25_25_115',1,NULL,0,NULL,0,NULL),(116,25,'Has anyone in your family experienced HEADACHE in the last week?','',NULL,2,1,5,NULL,'p_21_25_25_116',1,NULL,0,NULL,0,NULL),(117,25,'Has anyone in your family experienced SORE THROAT in the last week?','',NULL,2,1,6,NULL,'p_21_25_25_117',1,NULL,0,NULL,0,NULL),(118,25,'Has anyone in your family experienced KNEE PAIN in the last week?','',NULL,2,1,7,NULL,'p_21_25_25_118',1,NULL,0,NULL,0,NULL),(119,25,'Has anyone in your family experienced PAIN IN THE EARS in the last week?','',NULL,2,1,8,NULL,'p_21_25_25_119',1,NULL,0,NULL,0,NULL),(120,25,'Has anyone in your family experienced JOINT PAIN in the last week?','',NULL,2,1,9,NULL,'p_21_25_25_120',1,NULL,0,NULL,0,NULL),(121,25,'Has anyone in your family experienced COUGH in the last week?','',NULL,2,1,10,NULL,'p_21_25_25_121',1,NULL,0,NULL,0,NULL),(122,25,'Has anyone in your family experienced DIFFICULTY BREATHING in the last week?','',NULL,2,1,11,NULL,'p_21_25_25_122',1,NULL,0,NULL,0,NULL),(123,25,'Has anyone in your family experienced SWEATING in the last week?','',NULL,2,1,12,NULL,'p_21_25_25_123',1,NULL,0,NULL,0,NULL),(124,25,'Has anyone in your family experienced RUNNY NOSE in the last week?','',NULL,2,1,13,NULL,'p_21_25_25_124',1,NULL,0,NULL,0,NULL),(125,25,'Has anyone in your family experienced ITCH in the last week?','',NULL,2,1,14,NULL,'p_21_25_25_125',1,NULL,0,NULL,0,NULL),(126,25,'<p>Has anyone in your family experienced CONJUNCTIVITIS in the last week?</p>','',NULL,2,1,15,NULL,'p_21_25_25_126',1,NULL,0,NULL,0,NULL),(127,25,'<p>Has anyone in your family experienced SICKNESS, NAUSEA or VOMIT in the last week?</p>','',NULL,2,1,16,NULL,'p_21_25_25_127',1,NULL,0,NULL,0,NULL),(128,25,'Has anyone in your family experienced DIARRHEA in the last week?','',NULL,2,1,17,NULL,'p_21_25_25_128',1,NULL,0,NULL,0,NULL),(129,24,'How many members of your family, including yourself are older than 65 or younger than 12?','',NULL,4,1,3,NULL,'p_21_24_24_129',1,NULL,0,NULL,0,NULL),(130,5,'How many single mothers live in your RW?','',NULL,4,1,4,NULL,'p_5_5_5_130',0,NULL,0,NULL,0,NULL),(131,5,'How many people have underlying medical conditions in your RW?','',NULL,4,1,5,NULL,'p_5_5_5_131',0,NULL,0,NULL,0,NULL),(132,26,'<span style=\"font-size: 16px;\">How many people have reported COVID-19 symptoms?</span>','',NULL,4,1,1,NULL,'p_22_27_26_132',0,NULL,0,NULL,0,NULL),(133,26,'How many COVID-19 tests have been performed?&nbsp;','',NULL,4,1,2,NULL,'p_22_27_26_133',0,NULL,0,NULL,0,NULL),(134,26,'How many new confirmed cases?&nbsp;','',NULL,4,1,3,NULL,'p_22_27_26_134',0,NULL,0,NULL,0,NULL),(135,26,'How many new fatalities due to COVID-19?','',NULL,4,1,4,NULL,'p_22_27_26_135',0,NULL,0,NULL,0,NULL),(136,26,'How many people have recovered?','',NULL,4,1,5,NULL,'p_22_27_26_136',0,NULL,0,NULL,0,NULL),(137,26,'How many are instructed to be isolated because they are presenting symptoms?&nbsp;','',NULL,4,1,6,NULL,'p_22_27_26_137',0,NULL,0,NULL,0,NULL),(138,26,'How many areas were sprayed today with disinfectant?&nbsp;','',NULL,4,1,7,NULL,'p_22_27_26_138',0,1,0,NULL,0,NULL),(139,27,'How many areas were spread today with disinfectant?&nbsp;','',NULL,4,1,1,NULL,'p_22_27_27_139',0,NULL,0,NULL,0,NULL),(140,27,'Which areas were sprayed with disinfectant?&nbsp;','',NULL,5,1,2,NULL,'p_22_27_27_140',0,NULL,0,NULL,0,NULL),(141,28,'Where are you coming from?&nbsp;','',NULL,2,1,1,NULL,'p_23_28_28_141',0,NULL,0,NULL,0,NULL),(142,28,'Are you traveling alone?¬†','',NULL,2,1,2,NULL,'p_23_28_28_142',1,NULL,0,NULL,0,NULL),(143,28,'How many people are you traveling with?&nbsp;','',NULL,4,1,3,NULL,'p_23_28_28_143',0,NULL,0,NULL,0,NULL),(144,28,'Purpose of travel','',NULL,2,1,4,NULL,'p_23_28_28_144',0,NULL,0,NULL,0,NULL),(145,28,'What is your destination','',NULL,2,1,5,NULL,'p_23_28_28_145',0,NULL,0,NULL,0,NULL),(146,28,'Identify your destination in the following map.','',NULL,5,1,6,NULL,'p_23_28_28_146',0,NULL,0,NULL,0,NULL),(147,28,'Are you showing any COVID-19 related symptoms?&nbsp;','',NULL,2,1,7,NULL,'p_23_28_28_147',0,NULL,0,NULL,0,NULL),(148,29,'How many people have become temporarily unemployed?&nbsp;','',NULL,4,1,1,NULL,'p_24_29_29_148',0,NULL,0,NULL,0,NULL),(149,29,'Have many people have become permanently unemployed?&nbsp;','',NULL,4,1,2,NULL,'p_24_29_29_149',0,NULL,0,NULL,0,NULL),(150,29,'How many people suffered a salary cut?&nbsp;','',NULL,4,1,3,NULL,'p_24_29_29_150',0,NULL,0,NULL,0,NULL),(151,29,'How many people are employed and working from home?&nbsp;','',NULL,4,1,4,NULL,'p_24_29_29_151',0,NULL,0,NULL,0,NULL),(152,29,'How many people are employed and working as usual?&nbsp;','',NULL,4,1,5,NULL,'p_24_29_29_152',0,NULL,0,NULL,0,NULL),(153,29,'Number of household where the main income earner(d) are unemployed.','',NULL,4,1,6,NULL,'p_24_29_29_153',0,NULL,0,NULL,0,NULL),(154,29,'Number of households where the main income earner(s) have a significant loss of income in the past month.','',NULL,4,1,7,NULL,'p_24_29_29_154',1,NULL,0,NULL,0,NULL),(155,29,'Number of households receiving PKT.','',NULL,4,1,8,NULL,'p_24_29_29_155',0,NULL,0,NULL,0,NULL),(156,30,'How many local businesses and stores are closed?&nbsp;','',NULL,4,1,1,NULL,'p_25_30_30_156',0,NULL,0,NULL,0,NULL),(157,30,'How many local businesses and stores are open?&nbsp;','',NULL,4,1,2,NULL,'p_25_30_30_157',0,NULL,0,NULL,0,NULL),(158,30,'Where are open businesses located?','',NULL,5,1,3,NULL,'p_25_30_30_158',0,NULL,0,NULL,0,NULL),(159,30,'How many local businesses are paying normal wages?','',NULL,4,1,4,NULL,'p_25_30_30_159',0,NULL,0,NULL,0,NULL),(160,30,'How many local businesses decreased wages?&nbsp;','',NULL,4,1,5,NULL,'p_25_30_30_160',1,NULL,0,NULL,0,NULL),(161,31,'How many households are experiencing water shortage?&nbsp;','',NULL,4,1,1,NULL,'p_26_31_31_161',0,NULL,0,NULL,0,NULL),(162,31,'Where are these households located?&nbsp;','',NULL,5,1,2,NULL,'p_26_31_31_162',0,NULL,0,NULL,0,NULL),(163,33,'How many households are experiencing energy shortage?&nbsp;','',NULL,4,1,1,NULL,'p_26_31_33_163',0,NULL,0,NULL,0,NULL),(164,33,'Where are these households located?&nbsp;','',NULL,5,1,2,NULL,'p_26_31_33_164',0,NULL,0,NULL,0,NULL),(165,34,'<p>Jumlah tempat tidur</p>','',NULL,4,1,1,NULL,'p_27_32_34_165',1,NULL,0,NULL,0,NULL),(166,34,'<p>Jumlah bed r isolasi</p>','',NULL,4,1,2,NULL,'p_27_32_34_166',1,NULL,0,NULL,0,NULL),(167,34,'<p>location</p>','',NULL,7,1,3,NULL,'p_27_32_34_167',1,NULL,0,NULL,0,NULL),(168,35,'How many households are receiving BPNT/Rastra?&nbsp;&nbsp;','',NULL,4,1,1,NULL,'p_28_33_35_168',0,NULL,0,NULL,0,NULL),(169,35,'Where are these households located?&nbsp;','',NULL,5,1,2,NULL,'p_28_33_35_169',1,NULL,0,NULL,0,NULL),(170,35,'How many households are receiving PKH (Conditional Cash Transfers)?&nbsp;','',NULL,4,1,3,NULL,'p_28_33_35_170',0,NULL,0,NULL,0,NULL),(171,35,'Where are these households located?','',NULL,5,1,4,NULL,'p_28_33_35_171',1,NULL,0,NULL,0,NULL),(172,35,'How many households are receiving PKT (Cash for Work)','',NULL,4,1,5,NULL,'p_28_33_35_172',1,NULL,0,NULL,0,NULL),(173,35,'Where are these households located?','',NULL,5,1,6,NULL,'p_28_33_35_173',1,NULL,0,NULL,0,NULL),(174,36,'How many households are receiving BLT (Direct Cash Transfer)?&nbsp;','',NULL,4,1,1,NULL,'p_28_33_36_174',0,NULL,0,NULL,0,NULL),(175,36,'Where are these households located?&nbsp;','',NULL,5,1,2,NULL,'p_28_33_36_175',1,NULL,0,NULL,0,NULL),(176,36,'How many households are receiving direct food assistance?&nbsp;','',NULL,4,1,3,NULL,'p_28_33_36_176',0,NULL,0,NULL,0,NULL),(177,36,'Where are these households located?&nbsp;','',NULL,5,1,4,NULL,'p_28_33_36_177',0,NULL,0,NULL,0,NULL),(178,36,'How many households are receiving sanitizer and face masks?¬†','',NULL,4,1,5,NULL,'p_28_33_36_178',0,NULL,0,NULL,0,NULL),(179,36,'Where are these households located?&nbsp;','',NULL,5,1,6,NULL,'p_28_33_36_179',0,NULL,0,NULL,0,NULL),(180,39,'How many people arrived in your area this week?&nbsp;','',NULL,4,1,1,NULL,'p_30_36_39_180',0,NULL,0,NULL,0,NULL),(181,39,'Where did these travelers come from?&nbsp;','',NULL,2,1,2,NULL,'p_30_36_39_181',0,1,0,NULL,0,NULL),(182,39,'How many incoming travelers are presenting COVID-19 symptoms?&nbsp;','',NULL,4,1,7,NULL,'p_30_36_39_182',0,NULL,0,NULL,0,NULL),(183,40,'How many people left your area this week?','',NULL,4,1,1,NULL,'p_30_36_40_183',0,NULL,0,NULL,0,NULL),(184,40,'Which is the destination of these travelers?&nbsp;','',NULL,2,1,2,NULL,'p_30_36_40_184',0,1,0,NULL,0,NULL),(185,41,'In the last week, how many households have been unable to buy rice or other basic foods?&nbsp;','',NULL,4,1,1,NULL,'p_30_37_41_185',0,NULL,0,NULL,0,NULL),(186,41,'How many grocery stores and traditional markets are in your neighborhood?&nbsp;','',NULL,4,1,2,NULL,'p_30_37_41_186',0,NULL,0,NULL,0,NULL),(187,41,'How many grocery stores and traditional markets are still open?','',NULL,4,1,3,NULL,'p_30_37_41_187',0,NULL,0,NULL,0,NULL),(188,41,'Locate the stores and markets open.','',NULL,5,1,4,NULL,'p_30_37_41_188',0,NULL,0,NULL,0,NULL),(189,41,'In the last week, how many households in your neighborhood were hungry but did not eat due to limited money or other resources of food?&nbsp;','',NULL,4,1,5,NULL,'p_30_37_41_189',0,NULL,0,NULL,0,NULL),(190,41,'In the last week, were there any children (0-5 years old) in your neighborhood hungry but did not eat due to limited money or other resources of food?','',NULL,4,1,6,NULL,'p_30_37_41_190',0,NULL,0,NULL,0,NULL),(191,41,'How many children?&nbsp;','',NULL,4,1,7,NULL,'p_30_37_41_191',0,NULL,0,NULL,0,NULL),(192,41,'What is the main reason for not being able to buy rice/other basic food?','',NULL,2,1,8,NULL,'p_30_37_41_192',0,NULL,0,NULL,0,NULL),(193,39,'Tes','',NULL,3,1,4,NULL,'p_30_36_39_193',0,1,0,NULL,0,NULL),(194,39,'How many of these travelers came from a ...','',NULL,3,1,2,NULL,'p_30_36_39_194',0,NULL,0,NULL,0,NULL),(195,39,'Kelurahan?','',NULL,4,1,3,194,'p_30_36_39_195',0,NULL,0,NULL,NULL,NULL),(196,39,'Kecamatan?','',NULL,4,1,4,194,'p_30_36_39_196',0,NULL,0,NULL,NULL,NULL),(197,39,'City/Regency?','',NULL,4,1,5,194,'p_30_36_39_197',0,NULL,0,NULL,NULL,NULL),(198,39,'Country?','',NULL,4,1,6,194,'p_30_36_39_198',0,NULL,0,NULL,NULL,NULL),(199,40,'How many travelers were heading to ...','',NULL,3,1,3,NULL,'p_30_36_40_199',0,NULL,0,NULL,0,NULL),(200,40,'Kelurahan?','',NULL,4,1,4,199,'p_30_36_40_200',0,NULL,0,NULL,NULL,NULL),(201,40,'Kecamatan?','',NULL,4,1,5,199,'p_30_36_40_201',0,NULL,0,NULL,NULL,NULL),(202,40,'City/Regency?','',NULL,4,1,6,199,'p_30_36_40_202',0,NULL,0,NULL,NULL,NULL),(203,40,'Country?','',NULL,4,1,7,199,'p_30_36_40_203',0,NULL,0,NULL,NULL,NULL),(204,42,'Are there currently community checkpoints set up in your area?','',NULL,2,1,1,NULL,'p_31_38_42_204',0,NULL,0,NULL,0,NULL),(205,42,'How many are there?','',NULL,4,1,2,NULL,'p_31_38_42_205',0,NULL,0,NULL,0,NULL),(206,43,'Are there currently checkpoints set up in your area?','',NULL,2,1,1,NULL,'p_32_39_43_206',0,NULL,0,NULL,0,NULL),(207,43,'If yes, how many are there?','',NULL,4,1,2,NULL,'p_32_39_43_207',0,NULL,0,NULL,0,NULL),(208,43,'Is there a self-organised distribution of food in your area?','',NULL,2,1,3,NULL,'p_32_39_43_208',0,NULL,0,NULL,0,NULL),(209,43,'If yes, how many households receive food from this mechanism every week?','',NULL,4,1,4,NULL,'p_32_39_43_209',0,NULL,0,NULL,0,NULL);
/*!40000 ALTER TABLE `Preguntas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Problems`
--

DROP TABLE IF EXISTS `Problems`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Problems` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(30) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `categoriesId` int(11) DEFAULT NULL,
  `respuestasVisitaId` int(11) DEFAULT NULL,
  `photo` varchar(100) DEFAULT NULL,
  `geometry` geometry DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_Problems_RespuestasVisita1_idx` (`respuestasVisitaId`),
  KEY `fk_Problems_Categorias1_idx` (`categoriesId`),
  CONSTRAINT `fk_Problems_Categorias1` FOREIGN KEY (`categoriesId`) REFERENCES `Categories` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `fk_Problems_RespuestasVisita1` FOREIGN KEY (`respuestasVisitaId`) REFERENCES `RespuestasVisita` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Problems`
--

LOCK TABLES `Problems` WRITE;
/*!40000 ALTER TABLE `Problems` DISABLE KEYS */;
INSERT INTO `Problems` VALUES (1,'marker',NULL,NULL,NULL,310,NULL,'\0\0\0\0\0\0\0~@kp4g@@Ò‡¡\Zœ\Z¿'),(2,'marker',NULL,NULL,NULL,334,NULL,'\0\0\0\0\0\0\0$\0\0zêC@û/Ìè3¿'),(3,'marker',NULL,NULL,NULL,453,NULL,'\0\0\0\0\0\0\0=Ω≠Oö[@n0y®˙¿'),(4,'marker',NULL,NULL,NULL,456,NULL,'\0\0\0\0\0\0\0˜:©/Kõ[@çBíYΩÛ¿'),(5,'marker',NULL,NULL,NULL,459,NULL,'\0\0\0\0\0\0\0∂\nÏ÷ö[@î\0Î\'R¿'),(6,'marker',NULL,NULL,NULL,462,NULL,'\0\0\0\0\0\0\0$3Õ*\"ú[@„h3Ûâ·¿'),(7,'marker',NULL,NULL,NULL,465,NULL,'\0\0\0\0\0\0\0géˆâÕõ[@œ≥¢‰¿'),(8,'marker',NULL,NULL,NULL,468,NULL,'\0\0\0\0\0\0\09M˙÷bõ[@J≥å¿'),(9,'marker',NULL,NULL,NULL,471,NULL,'\0\0\0\0\0\0\0D!∏ÄÜù[@>o“¿'),(10,'marker',NULL,NULL,NULL,474,NULL,'\0\0\0\0\0\0\0ﬁu6‰ö[@£√ç•YÚ¿'),(11,'marker',NULL,NULL,NULL,477,NULL,'\0\0\0\0\0\0\0|H¯ﬁﬂù[@ñ\\≈\"¿'),(12,'marker',NULL,NULL,NULL,480,NULL,'\0\0\0\0\0\0\0U\ZåÏô[@$Â[ƒ¸¿'),(13,'marker',NULL,NULL,NULL,483,NULL,'\0\0\0\0\0\0\0#%áô«ñ[@´\r©˝1¿'),(14,'marker',NULL,NULL,NULL,486,NULL,'\0\0\0\0\0\0\0Êx¢ßö[@˚[OA¿'),(15,'marker',NULL,NULL,NULL,489,NULL,'\0\0\0\0\0\0\0≠ò[k9ö[@ëV∆–˚¿'),(16,'marker',NULL,NULL,NULL,492,NULL,'\0\0\0\0\0\0\0ô_rHèú[@≥DgôE\0¿'),(17,'marker',NULL,NULL,NULL,495,NULL,'\0\0\0\0\0\0\0—‘°âÊï[@ÿi2\0¿'),(18,'marker',NULL,NULL,NULL,498,NULL,'\0\0\0\0\0\0\0õ.’hö[@oÖ…TÒ¿'),(19,'marker',NULL,NULL,NULL,501,NULL,'\0\0\0\0\0\0\0¸‡|ÍXö[@øË<tãJ¿'),(20,'marker',NULL,NULL,NULL,504,NULL,'\0\0\0\0\0\0\0î¢ï{Åò[@¬√¥oÓÔ¿'),(21,'marker',NULL,NULL,NULL,507,NULL,'\0\0\0\0\0\0\0;™ö jú[@‡V0‡1¿'),(22,'marker',NULL,NULL,NULL,510,NULL,'\0\0\0\0\0\0\0‚iC∆õ[@›£	ˇ¿'),(23,'marker',NULL,NULL,NULL,513,NULL,'\0\0\0\0\0\0\0∂˜©*¥ù[@1YÅ∆Ò¿'),(24,'marker',NULL,NULL,NULL,516,NULL,'\0\0\0\0\0\0\0ƒ¡ÔËô[@˛TÀåm¯¿'),(25,'marker',NULL,NULL,NULL,519,NULL,'\0\0\0\0\0\0\0mlÎö[@Fl¸¿'),(26,'marker',NULL,NULL,NULL,522,NULL,'\0\0\0\0\0\0\0ÇXë,ªõ[@,ÔONY˙¿'),(27,'marker',NULL,NULL,NULL,525,NULL,'\0\0\0\0\0\0\0Ÿ∑ÓA„õ[@3iûmÁ¿'),(28,'marker',NULL,NULL,NULL,528,NULL,'\0\0\0\0\0\0\0#À\"ﬁü[@£Ω•@¿'),(29,'marker',NULL,NULL,NULL,531,NULL,'\0\0\0\0\0\0\0{∫_™>î[@Ï1ë“lF¿'),(30,'marker',NULL,NULL,NULL,534,NULL,'\0\0\0\0\0\0\0òüêù[@ê˘Ä@g“¿'),(31,'marker',NULL,NULL,NULL,537,NULL,'\0\0\0\0\0\0\0*áª‹ù[@\\≥Po¿'),(32,'marker','Report_1','Test',11,540,'','\0\0\0\0\0\0\0U|≥™2õ[@\r\'c	¿'),(33,'marker','Market','This market is open',18,562,'','\0\0\0\0\0\0\0ø‚xVf}@Oú˜˛\r¿'),(34,'marker','Market 1','This market is still open.',18,581,'','\0\0\0\0\0\0\0	Œ™!Lf}@æ˝≈\nœ	¿');
/*!40000 ALTER TABLE `Problems` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Projects`
--

DROP TABLE IF EXISTS `Projects`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Projects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `inactive` tinyint(4) DEFAULT NULL,
  `code` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Projects`
--

LOCK TABLES `Projects` WRITE;
/*!40000 ALTER TABLE `Projects` DISABLE KEYS */;
INSERT INTO `Projects` VALUES (1,'Monitoring COVID-19','Project ONE',0,'COVID19'),(3,'Tanzania','Task team for COVID-19',0,'COVID-19'),(4,'Semarang ','Monitoring for Semarang',0,'COVID-19');
/*!40000 ALTER TABLE `Projects` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Respuestas`
--

DROP TABLE IF EXISTS `Respuestas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Respuestas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `preguntasId` int(11) DEFAULT NULL,
  `respuesta` varchar(255) DEFAULT NULL,
  `valor` varchar(6) DEFAULT NULL,
  `identificador` varchar(45) DEFAULT NULL,
  `orden` tinyint(4) DEFAULT NULL,
  `elim` tinyint(4) DEFAULT NULL,
  `justif` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_Respuestas_Preguntas1_idx` (`preguntasId`),
  KEY `indexRespuesta_Valor` (`valor`),
  KEY `indexRespuesta_Respuesta` (`respuesta`),
  CONSTRAINT `fk_Respuestas_Preguntas1` FOREIGN KEY (`preguntasId`) REFERENCES `Preguntas` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=187 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Respuestas`
--

LOCK TABLES `Respuestas` WRITE;
/*!40000 ALTER TABLE `Respuestas` DISABLE KEYS */;
INSERT INTO `Respuestas` VALUES (22,16,'Yes','1',NULL,0,NULL,NULL),(23,16,'No','0',NULL,1,NULL,NULL),(24,16,'I dont know','2',NULL,2,NULL,NULL),(25,44,'Yes','1',NULL,0,NULL,NULL),(26,44,'No','0',NULL,1,NULL,NULL),(27,44,'I dont know','2',NULL,2,1,NULL),(28,45,'Between families','1','r_7_7_7_45_128',1,NULL,0),(29,45,'Within families','2','r_7_7_7_45_229',2,NULL,0),(30,45,'Both?','3','r_7_7_7_45_330',3,NULL,0),(31,47,'No change','1','r_7_7_7_47_131',1,NULL,0),(32,47,'Increased','2','r_7_7_7_47_232',2,NULL,0),(33,47,'Decreased','2','r_7_7_7_47_333',3,NULL,0),(34,50,'Yes','1',NULL,0,NULL,NULL),(35,50,'No','0',NULL,1,NULL,NULL),(36,50,'I dont know','2',NULL,2,NULL,NULL),(40,94,'Hospital A','1','r_18_18_18_94_140',1,NULL,0),(41,94,'Hospital B','2','r_18_18_18_94_241',2,NULL,0),(42,97,'Yes','1',NULL,0,1,NULL),(43,97,'No','0',NULL,1,1,NULL),(44,97,'I dont know','2',NULL,2,1,NULL),(45,97,'Yes','1',NULL,0,1,NULL),(46,97,'No','0',NULL,1,1,NULL),(47,97,'I dont know','2',NULL,2,1,NULL),(48,97,'Yes','1',NULL,0,1,NULL),(49,97,'No','0',NULL,1,1,NULL),(50,97,'I dont know','2',NULL,2,1,NULL),(51,97,'Yes','1',NULL,0,1,NULL),(52,97,'No','0',NULL,1,1,NULL),(53,97,'I dont know','2',NULL,2,1,NULL),(54,97,'0','0',NULL,1,1,NULL),(55,97,'1','1',NULL,2,1,NULL),(56,97,'2','2',NULL,3,1,NULL),(57,97,'3','3',NULL,4,1,NULL),(58,97,'4','4',NULL,5,1,NULL),(59,97,'5','5',NULL,6,1,NULL),(60,97,'6','6',NULL,7,1,NULL),(61,97,'7','7',NULL,8,1,NULL),(62,97,'8','8',NULL,9,1,NULL),(63,97,'9','9',NULL,10,1,NULL),(64,97,'10','10',NULL,11,1,NULL),(65,97,'NA','-',NULL,13,1,NULL),(66,97,'Yes','1',NULL,0,NULL,NULL),(67,97,'No','0',NULL,1,NULL,NULL),(68,97,'I dont know','2',NULL,2,NULL,NULL),(69,99,'Yes','1',NULL,0,NULL,NULL),(70,99,'No','0',NULL,1,NULL,NULL),(71,99,'I dont know','2',NULL,2,NULL,NULL),(74,101,'Market 1','1','r_20_22_22_101_174',1,NULL,0),(75,101,'Market 2','2','r_20_22_22_101_275',2,NULL,0),(76,102,'Yes','1',NULL,0,NULL,NULL),(77,102,'No','0',NULL,1,NULL,NULL),(78,102,'I dont know','2',NULL,2,NULL,NULL),(79,103,'Yes','1',NULL,0,NULL,NULL),(80,103,'No','0',NULL,1,NULL,NULL),(81,103,'I dont know','2',NULL,2,NULL,NULL),(82,104,'Yes','1',NULL,0,NULL,NULL),(83,104,'No','0',NULL,1,NULL,NULL),(84,104,'I dont know','2',NULL,2,NULL,NULL),(85,105,'Yes','1',NULL,0,NULL,NULL),(86,105,'No','0',NULL,1,NULL,NULL),(87,105,'I dont know','2',NULL,2,NULL,NULL),(88,106,'Yes','1',NULL,0,NULL,NULL),(89,106,'No','0',NULL,1,NULL,NULL),(90,106,'I dont know','2',NULL,2,NULL,NULL),(91,107,'Yes','1',NULL,0,NULL,NULL),(92,107,'No','0',NULL,1,NULL,NULL),(93,107,'I dont know','2',NULL,2,NULL,NULL),(94,101,'Market 3','3','r_20_22_22_101_394',3,NULL,0),(95,112,'Yes','1',NULL,0,NULL,NULL),(96,112,'No','0',NULL,1,NULL,NULL),(97,112,'I dont know','2',NULL,2,NULL,NULL),(98,113,'Yes','1',NULL,0,NULL,NULL),(99,113,'No','0',NULL,1,NULL,NULL),(100,113,'I dont know','2',NULL,2,NULL,NULL),(101,114,'Yes','1',NULL,0,NULL,NULL),(102,114,'No','0',NULL,1,NULL,NULL),(103,114,'I dont know','2',NULL,2,NULL,NULL),(104,115,'Yes','1',NULL,0,NULL,NULL),(105,115,'No','0',NULL,1,NULL,NULL),(106,115,'I dont know','2',NULL,2,NULL,NULL),(107,116,'Yes','1',NULL,0,NULL,NULL),(108,116,'No','0',NULL,1,NULL,NULL),(109,116,'I dont know','2',NULL,2,NULL,NULL),(110,117,'Yes','1',NULL,0,NULL,NULL),(111,117,'No','0',NULL,1,NULL,NULL),(112,117,'I dont know','2',NULL,2,NULL,NULL),(113,118,'Yes','1',NULL,0,NULL,NULL),(114,118,'No','0',NULL,1,NULL,NULL),(115,118,'I dont know','2',NULL,2,NULL,NULL),(116,119,'Yes','1',NULL,0,NULL,NULL),(117,119,'No','0',NULL,1,NULL,NULL),(118,119,'I dont know','2',NULL,2,NULL,NULL),(119,120,'Yes','1',NULL,0,NULL,NULL),(120,120,'No','0',NULL,1,NULL,NULL),(121,120,'I dont know','2',NULL,2,NULL,NULL),(122,121,'Yes','1',NULL,0,NULL,NULL),(123,121,'No','0',NULL,1,NULL,NULL),(124,121,'I dont know','2',NULL,2,NULL,NULL),(125,122,'Yes','1',NULL,0,NULL,NULL),(126,122,'No','0',NULL,1,NULL,NULL),(127,122,'I dont know','2',NULL,2,NULL,NULL),(128,123,'Yes','1',NULL,0,NULL,NULL),(129,123,'No','0',NULL,1,NULL,NULL),(130,123,'I dont know','2',NULL,2,NULL,NULL),(131,124,'Yes','1',NULL,0,NULL,NULL),(132,124,'No','0',NULL,1,NULL,NULL),(133,124,'I dont know','2',NULL,2,NULL,NULL),(134,125,'Yes','1',NULL,0,NULL,NULL),(135,125,'No','0',NULL,1,NULL,NULL),(136,125,'I dont know','2',NULL,2,NULL,NULL),(137,126,'Yes','1',NULL,0,NULL,NULL),(138,126,'No','0',NULL,1,NULL,NULL),(139,126,'I dont know','2',NULL,2,NULL,NULL),(140,127,'Yes','1',NULL,0,NULL,NULL),(141,127,'No','0',NULL,1,NULL,NULL),(142,127,'I dont know','2',NULL,2,NULL,NULL),(143,128,'Yes','1',NULL,0,NULL,NULL),(144,128,'No','0',NULL,1,NULL,NULL),(145,128,'I dont know','2',NULL,2,NULL,NULL),(146,141,'Another Kelurahan','1','r_23_28_28_141_1146',1,NULL,1),(147,141,'Another city','1','r_23_28_28_141_2147',2,NULL,0),(148,141,'Another country','1','r_23_28_28_141_3148',3,NULL,0),(149,142,'Yes','1',NULL,0,NULL,NULL),(150,142,'No','0',NULL,1,NULL,NULL),(151,142,'I dont know','2',NULL,2,1,NULL),(152,144,'Forced migration','1','r_23_28_28_144_1152',1,NULL,0),(153,144,'For isolation','1','r_23_28_28_144_2153',2,NULL,0),(154,144,'Tourism','1','r_23_28_28_144_3154',3,NULL,0),(155,144,'Work','1','r_23_28_28_144_4155',4,NULL,0),(156,145,'Hotel','1','r_23_28_28_145_1156',1,NULL,0),(157,145,'Hospital','1','r_23_28_28_145_2157',2,NULL,0),(158,145,'Home','1','r_23_28_28_145_3158',3,NULL,0),(159,147,'Yes','1',NULL,0,NULL,NULL),(160,147,'No','0',NULL,1,NULL,NULL),(161,147,'I dont know','2',NULL,2,1,NULL),(162,181,'Kelurahan','1','r_30_36_39_181_1162',1,NULL,0),(163,181,'Kecamatan&nbsp;','1','r_30_36_39_181_2163',2,NULL,0),(164,181,'City/Regency&nbsp;','1','r_30_36_39_181_3164',3,NULL,0),(165,181,'Country','1','r_30_36_39_181_4165',4,NULL,0),(166,184,'Kelurahan','1','r_30_36_40_184_1166',1,NULL,0),(167,184,'<sub>Kecamatan</sub>','1','r_30_36_40_184_2167',2,1,0),(168,184,'Kecamatan','1','r_30_36_40_184_2168',2,NULL,0),(169,184,'City/Regency','1','r_30_36_40_184_3169',3,NULL,0),(170,184,'Country','1','r_30_36_40_184_4170',4,NULL,0),(171,192,'Groceries/shops have run out of stock','1','r_30_37_41_192_1171',1,NULL,0),(172,192,'Traditional markets not operating/closed','1','r_30_37_41_192_2172',2,NULL,0),(173,192,'Limited/no transportation','1','r_30_37_41_192_3173',3,NULL,0),(174,192,'Restriction to go outside','1','r_30_37_41_192_4174',4,NULL,0),(175,192,'Increase in price','1','r_30_37_41_192_5175',5,NULL,0),(176,192,'No access to cash and cannot pay with credit card','1','r_30_37_41_192_6176',6,NULL,0),(177,192,'Other','1','r_30_37_41_192_7177',7,NULL,1),(178,204,'Yes','1',NULL,0,NULL,NULL),(179,204,'No','0',NULL,1,NULL,NULL),(180,204,'I dont know','2',NULL,2,1,NULL),(181,206,'Yes','1',NULL,0,NULL,NULL),(182,206,'No','0',NULL,1,NULL,NULL),(183,206,'I dont know','2',NULL,2,1,NULL),(184,208,'Yes','1',NULL,0,NULL,NULL),(185,208,'No','0',NULL,1,NULL,NULL),(186,208,'I dont know','2',NULL,2,1,NULL);
/*!40000 ALTER TABLE `Respuestas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `RespuestasVisita`
--

DROP TABLE IF EXISTS `RespuestasVisita`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `RespuestasVisita` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `visitasId` int(11) DEFAULT NULL,
  `preguntasId` int(11) DEFAULT NULL,
  `respuesta` text DEFAULT NULL,
  `justificacion` text DEFAULT NULL,
  `identificador` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `visitaPregunta` (`visitasId`,`preguntasId`),
  KEY `fk_RespuestasVisita_Preguntas1_idx` (`preguntasId`),
  KEY `fk_RespuestasVisita_Visitas1_idx` (`visitasId`),
  KEY `RespIdentif` (`identificador`),
  CONSTRAINT `fk_RespuestasVisita_Preguntas1` FOREIGN KEY (`preguntasId`) REFERENCES `Preguntas` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `fk_RespuestasVisita_Visitas1` FOREIGN KEY (`visitasId`) REFERENCES `Visitas` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=586 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `RespuestasVisita`
--

LOCK TABLES `RespuestasVisita` WRITE;
/*!40000 ALTER TABLE `RespuestasVisita` DISABLE KEYS */;
INSERT INTO `RespuestasVisita` VALUES (78,58,8,'Narvarte','','p_5_5_5_8'),(79,58,9,'5000','','p_5_5_5_9'),(80,58,10,'5000','','p_5_5_5_10'),(81,58,11,'200','','p_5_5_5_11'),(82,58,12,'200','','p_5_5_5_12'),(83,59,13,'Narvarte','','p_6_6_6_13'),(84,59,14,'500','','p_6_6_6_14'),(86,59,15,'300','','p_6_6_6_15'),(90,59,16,'23','','p_6_6_6_16'),(91,59,18,'100','','p_6_6_6_18'),(92,59,19,'111','','p_6_6_6_19'),(93,59,21,'11','','p_6_6_6_21'),(94,59,22,'12','','p_6_6_6_22'),(97,59,23,'13','','p_6_6_6_23'),(98,59,25,'13','','p_6_6_6_25'),(99,59,26,'14','','p_6_6_6_26'),(100,59,27,'15','','p_6_6_6_27'),(101,59,28,'16','','p_6_6_6_28'),(102,59,29,'17','','p_6_6_6_29'),(103,59,31,'66','','p_6_6_6_31'),(104,59,32,'77','','p_6_6_6_32'),(105,59,33,'88','','p_6_6_6_33'),(106,60,34,'Narvarte','','p_7_7_7_34'),(107,60,35,'23','','p_7_7_7_35'),(108,60,36,'23','','p_7_7_7_36'),(109,60,37,'24','','p_7_7_7_37'),(110,60,38,'25','','p_7_7_7_38'),(111,60,39,'26','','p_7_7_7_39'),(112,60,40,'27','','p_7_7_7_40'),(113,60,41,'45','','p_7_7_7_41'),(114,60,42,'34','','p_7_7_7_42'),(115,60,43,'34','','p_7_7_7_43'),(122,60,44,'26','','p_7_7_7_44'),(123,60,46,'67','','p_7_7_7_46'),(124,60,47,'32','','p_7_7_7_47'),(125,60,48,'43','','p_7_7_7_48'),(127,60,49,'34','','p_7_7_7_49'),(129,61,8,'cdsfd','','p_5_5_5_8'),(130,61,9,'11','','p_5_5_5_9'),(131,61,10,'11','','p_5_5_5_10'),(132,61,11,'11','','p_5_5_5_11'),(136,61,12,'11','','p_5_5_5_12'),(137,66,34,'RW1','','p_7_7_7_34'),(138,66,35,'45','','p_7_7_7_35'),(139,66,36,'20','','p_7_7_7_36'),(140,66,37,'1','','p_7_7_7_37'),(141,66,38,'1','','p_7_7_7_38'),(142,66,39,'1','','p_7_7_7_39'),(143,66,40,'1','','p_7_7_7_40'),(144,66,41,'2','','p_7_7_7_41'),(145,66,42,'4','','p_7_7_7_42'),(146,66,43,'4','','p_7_7_7_43'),(148,66,44,'25','','p_7_7_7_44'),(155,66,49,'32','','p_7_7_7_49'),(156,66,48,'2','','p_7_7_7_48'),(157,66,47,'32','','p_7_7_7_47'),(158,66,46,'3','','p_7_7_7_46'),(160,66,45,'28','','p_7_7_7_45'),(161,65,13,'RW1','','p_6_6_6_13'),(162,65,14,'2','','p_6_6_6_14'),(164,64,8,'RW1','','p_5_5_5_8'),(165,64,9,'3','','p_5_5_5_9'),(166,64,10,'3','','p_5_5_5_10'),(167,64,11,'5','','p_5_5_5_11'),(168,64,12,'2','','p_5_5_5_12'),(174,65,15,'3','','p_6_6_6_15'),(175,68,13,'RW1','','p_6_6_6_13'),(196,68,31,'2','','p_6_6_6_31'),(197,68,29,'10','','p_6_6_6_29'),(198,68,28,'9','','p_6_6_6_28'),(199,68,27,'10','','p_6_6_6_27'),(201,68,26,'15','','p_6_6_6_26'),(203,68,25,'10','','p_6_6_6_25'),(209,68,23,'20','','p_6_6_6_23'),(214,68,22,'20','','p_6_6_6_22'),(215,68,21,'10','','p_6_6_6_21'),(216,68,19,'0','','p_6_6_6_19'),(218,68,18,'0','','p_6_6_6_18'),(224,68,17,'3','','p_6_6_6_17'),(225,68,16,'22','','p_6_6_6_16'),(227,68,15,'2','','p_6_6_6_15'),(228,68,14,'4','','p_6_6_6_14'),(229,68,33,'1','','p_6_6_6_33'),(231,68,32,'1','','p_6_6_6_32'),(232,69,34,'RW1','','p_7_7_7_34'),(233,69,35,'15','','p_7_7_7_35'),(234,69,36,'10','','p_7_7_7_36'),(235,69,37,'9','','p_7_7_7_37'),(236,69,38,'8','','p_7_7_7_38'),(237,69,39,'5','','p_7_7_7_39'),(238,69,40,'20','','p_7_7_7_40'),(239,69,41,'10','','p_7_7_7_41'),(240,69,42,'25','','p_7_7_7_42'),(241,69,43,'8','','p_7_7_7_43'),(242,69,44,'25','','p_7_7_7_44'),(243,69,45,'30','','p_7_7_7_45'),(244,69,46,'10','','p_7_7_7_46'),(245,69,47,'32','','p_7_7_7_47'),(246,69,48,'General.','','p_7_7_7_48'),(247,69,49,'2','','p_7_7_7_49'),(248,70,8,'RW1','','p_5_5_5_8'),(249,70,9,'50','','p_5_5_5_9'),(250,70,10,'15','','p_5_5_5_10'),(251,70,11,'8','','p_5_5_5_11'),(253,70,12,'10','','p_5_5_5_12'),(254,71,8,'RW1','','p_5_5_5_8'),(255,71,9,'50','','p_5_5_5_9'),(256,71,10,'10','','p_5_5_5_10'),(257,71,11,'10','','p_5_5_5_11'),(258,71,12,'10','','p_5_5_5_12'),(259,72,13,'RW1','','p_6_6_6_13'),(260,72,14,'0','','p_6_6_6_14'),(261,72,15,'0','','p_6_6_6_15'),(262,72,16,'23','','p_6_6_6_16'),(263,72,18,'0','','p_6_6_6_18'),(265,72,19,'0','','p_6_6_6_19'),(266,72,21,'90','','p_6_6_6_21'),(267,72,22,'0','','p_6_6_6_22'),(268,72,23,'0','','p_6_6_6_23'),(269,72,25,'4','','p_6_6_6_25'),(270,72,26,'4','','p_6_6_6_26'),(271,72,27,'10','','p_6_6_6_27'),(272,72,28,'1000','','p_6_6_6_28'),(273,72,29,'0','','p_6_6_6_29'),(274,72,31,'10','','p_6_6_6_31'),(275,72,32,'0','','p_6_6_6_32'),(276,72,33,'1','','p_6_6_6_33'),(277,73,8,'RW1','','p_5_5_5_8'),(280,76,8,'Hospital √°ngeles del pedregal','','p_5_5_5_8'),(281,76,9,'13','','p_5_5_5_9'),(282,76,10,'34','','p_5_5_5_10'),(283,76,11,'45','','p_5_5_5_11'),(284,76,12,'45','','p_5_5_5_12'),(288,84,94,'40','','p_18_18_18_94'),(289,84,95,'95','','p_18_18_18_95'),(291,84,96,'s','','p_18_18_18_96'),(292,84,97,'66','','p_18_19_19_97'),(293,84,98,'dasd','','p_18_19_19_98'),(294,84,99,'69','','p_18_20_20_99'),(295,87,94,'40','','p_18_18_18_94'),(296,87,95,'120','','p_18_18_18_95'),(297,87,96,'More band-its are needed','','p_18_18_18_96'),(298,87,97,'66','','p_18_19_19_97'),(299,87,98,'No action needed at this point','','p_18_19_19_98'),(300,87,99,'69','','p_18_20_20_99'),(301,89,94,'40','','p_18_18_18_94'),(302,89,95,'80','','p_18_18_18_95'),(303,89,96,'No comments','','p_18_18_18_96'),(304,89,97,'66','','p_18_19_19_97'),(305,89,98,'No comments','','p_18_19_19_98'),(306,89,99,'69','','p_18_20_20_99'),(310,90,109,'spatial','','p_21_23_23_109'),(311,90,108,'Temeke St, Dar es Salaam, Tanzania. Right in front of Chang\'ombe Police Station. ','','p_21_23_23_108'),(312,90,110,'4','','p_21_24_24_110'),(314,90,111,'2','','p_21_24_24_111'),(315,90,129,'1','','p_21_24_24_129'),(316,90,112,'96','','p_21_25_25_112'),(318,90,113,'99','','p_21_25_25_113'),(319,90,114,'101','','p_21_25_25_114'),(320,90,115,'104','','p_21_25_25_115'),(321,90,116,'108','','p_21_25_25_116'),(322,90,117,'111','','p_21_25_25_117'),(323,90,118,'113','','p_21_25_25_118'),(324,90,119,'117','','p_21_25_25_119'),(325,90,120,'120','','p_21_25_25_120'),(326,90,121,'123','','p_21_25_25_121'),(327,90,122,'126','','p_21_25_25_122'),(328,90,123,'129','','p_21_25_25_123'),(329,90,124,'132','','p_21_25_25_124'),(330,90,125,'135','','p_21_25_25_125'),(331,90,126,'137','','p_21_25_25_126'),(332,90,127,'141','','p_21_25_25_127'),(333,90,128,'143','','p_21_25_25_128'),(334,95,109,'spatial','','p_21_23_23_109'),(335,95,108,'Location description. ','','p_21_23_23_108'),(336,95,110,'4','','p_21_24_24_110'),(337,95,111,'2','','p_21_24_24_111'),(338,95,129,'2','','p_21_24_24_129'),(339,95,112,'96','','p_21_25_25_112'),(340,95,113,'99','','p_21_25_25_113'),(341,95,114,'102','','p_21_25_25_114'),(342,95,115,'105','','p_21_25_25_115'),(343,95,116,'108','','p_21_25_25_116'),(344,95,117,'111','','p_21_25_25_117'),(345,95,118,'114','','p_21_25_25_118'),(346,95,119,'117','','p_21_25_25_119'),(347,95,120,'120','','p_21_25_25_120'),(348,95,121,'123','','p_21_25_25_121'),(349,95,122,'126','','p_21_25_25_122'),(350,95,123,'129','','p_21_25_25_123'),(351,95,124,'132','','p_21_25_25_124'),(352,95,125,'135','','p_21_25_25_125'),(353,95,126,'138','','p_21_25_25_126'),(354,95,127,'141','','p_21_25_25_127'),(355,95,128,'144','','p_21_25_25_128'),(356,82,94,'41','','p_18_18_18_94'),(357,82,95,'56','','p_18_18_18_95'),(358,82,96,'No notes','','p_18_18_18_96'),(360,82,97,'67','','p_18_19_19_97'),(361,82,98,'No comments','','p_18_19_19_98'),(363,82,99,'69','','p_18_20_20_99'),(451,128,165,'1157',NULL,NULL),(452,128,166,'24',NULL,NULL),(453,128,167,'spatial',NULL,NULL),(454,129,165,'312',NULL,NULL),(455,129,166,'3',NULL,NULL),(456,129,167,'spatial',NULL,NULL),(457,130,165,'296',NULL,NULL),(458,130,166,'2',NULL,NULL),(459,130,167,'spatial',NULL,NULL),(460,131,165,'205',NULL,NULL),(461,131,166,'17',NULL,NULL),(462,131,167,'spatial',NULL,NULL),(463,132,165,'165',NULL,NULL),(464,132,166,'15',NULL,NULL),(465,132,167,'spatial',NULL,NULL),(466,133,165,'238',NULL,NULL),(467,133,166,'8',NULL,NULL),(468,133,167,'spatial',NULL,NULL),(469,134,165,'400',NULL,NULL),(470,134,166,'-',NULL,NULL),(471,134,167,'spatial',NULL,NULL),(472,135,165,'144',NULL,NULL),(473,135,166,'10',NULL,NULL),(474,135,167,'spatial',NULL,NULL),(475,136,165,'396',NULL,NULL),(476,136,166,'24',NULL,NULL),(477,136,167,'spatial',NULL,NULL),(478,137,165,'-',NULL,NULL),(479,137,166,'3',NULL,NULL),(480,137,167,'spatial',NULL,NULL),(481,138,165,'410',NULL,NULL),(482,138,166,'4',NULL,NULL),(483,138,167,'spatial',NULL,NULL),(484,139,165,'-',NULL,NULL),(485,139,166,'-',NULL,NULL),(486,139,167,'spatial',NULL,NULL),(487,140,165,'33',NULL,NULL),(488,140,166,'-',NULL,NULL),(489,140,167,'spatial',NULL,NULL),(490,141,165,'100',NULL,NULL),(491,141,166,'11',NULL,NULL),(492,141,167,'spatial',NULL,NULL),(493,142,165,'148',NULL,NULL),(494,142,166,'-',NULL,NULL),(495,142,167,'spatial',NULL,NULL),(496,143,165,'80',NULL,NULL),(497,143,166,'-',NULL,NULL),(498,143,167,'spatial',NULL,NULL),(499,144,165,'70',NULL,NULL),(500,144,166,'-',NULL,NULL),(501,144,167,'spatial',NULL,NULL),(502,145,165,'119',NULL,NULL),(503,145,166,'2',NULL,NULL),(504,145,167,'spatial',NULL,NULL),(505,146,165,'110',NULL,NULL),(506,146,166,'10',NULL,NULL),(507,146,167,'spatial',NULL,NULL),(508,147,165,'17',NULL,NULL),(509,147,166,'-',NULL,NULL),(510,147,167,'spatial',NULL,NULL),(511,148,165,'-',NULL,NULL),(512,148,166,'-',NULL,NULL),(513,148,167,'spatial',NULL,NULL),(514,149,165,'25',NULL,NULL),(515,149,166,'-',NULL,NULL),(516,149,167,'spatial',NULL,NULL),(517,150,165,'25',NULL,NULL),(518,150,166,'-',NULL,NULL),(519,150,167,'spatial',NULL,NULL),(520,151,165,'26',NULL,NULL),(521,151,166,'-',NULL,NULL),(522,151,167,'spatial',NULL,NULL),(523,152,165,'43',NULL,NULL),(524,152,166,'-',NULL,NULL),(525,152,167,'spatial',NULL,NULL),(526,153,165,'25',NULL,NULL),(527,153,166,'-',NULL,NULL),(528,153,167,'spatial',NULL,NULL),(529,154,165,'25',NULL,NULL),(530,154,166,'-',NULL,NULL),(531,154,167,'spatial',NULL,NULL),(532,155,165,'-',NULL,NULL),(533,155,166,'7',NULL,NULL),(534,155,167,'spatial',NULL,NULL),(535,156,165,'4',NULL,NULL),(536,156,166,'-',NULL,NULL),(537,156,167,'spatial',NULL,NULL),(538,157,168,'0','','p_28_33_35_168'),(539,157,170,'1','','p_28_33_35_170'),(540,157,171,'spatial','','p_28_33_35_171'),(542,157,172,'34','','p_28_33_35_172'),(545,160,180,'54','','p_30_36_39_180'),(546,161,180,'20','','p_30_36_39_180'),(547,161,195,'10','','p_30_36_39_195'),(548,161,196,'5','','p_30_36_39_196'),(549,161,197,'5','','p_30_36_39_197'),(550,161,198,'0','','p_30_36_39_198'),(551,161,182,'2','','p_30_36_39_182'),(552,161,183,'10','','p_30_36_40_183'),(553,161,200,'2','','p_30_36_40_200'),(554,161,201,'6','','p_30_36_40_201'),(555,161,202,'2','','p_30_36_40_202'),(556,161,203,'0','','p_30_36_40_203'),(557,161,185,'15','','p_30_37_41_185'),(558,161,186,'5','','p_30_37_41_186'),(561,161,187,'0','','p_30_37_41_187'),(562,161,188,'spatial','','p_30_37_41_188'),(563,161,189,'2','','p_30_37_41_189'),(564,161,190,'3','','p_30_37_41_190'),(565,161,191,'3','','p_30_37_41_191'),(566,161,192,'171','','p_30_37_41_192'),(567,163,180,'20','','p_30_36_39_180'),(568,163,195,'10','','p_30_36_39_195'),(569,163,196,'5','','p_30_36_39_196'),(570,163,197,'5','','p_30_36_39_197'),(571,163,198,'0','','p_30_36_39_198'),(572,163,182,'5','','p_30_36_39_182'),(573,163,183,'10','','p_30_36_40_183'),(574,163,200,'5','','p_30_36_40_200'),(575,163,201,'5','','p_30_36_40_201'),(576,163,202,'0','','p_30_36_40_202'),(577,163,203,'0','','p_30_36_40_203'),(578,163,185,'15','','p_30_37_41_185'),(579,163,186,'3','','p_30_37_41_186'),(580,163,187,'1','','p_30_37_41_187'),(581,163,188,'spatial','','p_30_37_41_188'),(582,163,189,'5','','p_30_37_41_189'),(583,163,190,'3','','p_30_37_41_190'),(584,163,191,'1','','p_30_37_41_191'),(585,163,192,'175','','p_30_37_41_192');
/*!40000 ALTER TABLE `RespuestasVisita` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Studyarea`
--

DROP TABLE IF EXISTS `Studyarea`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Studyarea` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `preguntasId` int(11) DEFAULT NULL,
  `type` varchar(30) DEFAULT NULL,
  `geometry` geometry DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_Studyarea_Preguntas1_idx` (`preguntasId`),
  CONSTRAINT `fk_Studyarea_Preguntas1` FOREIGN KEY (`preguntasId`) REFERENCES `Preguntas` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Studyarea`
--

LOCK TABLES `Studyarea` WRITE;
/*!40000 ALTER TABLE `Studyarea` DISABLE KEYS */;
INSERT INTO `Studyarea` VALUES (4,109,'polygon','\0\0\0\0\0\0\0\0\0\0B\0\0\0£¨˛Ió°C@˛3Ì≥ø¿ØV35\ZÂB@æÑŸ¿î_J;	◊B@‘õ¶◊˝¿bóÔá|9A@ΩóÀnt+ÛøÒK∫≥Ω@@ƒùC8Øˆø∂Hº) @@À5^Uƒ˛ø˜÷±ºÅ?@¥zƒDúã¿È„bbíx>@>»=C`¿⁄<IüËÎ=@mÚ±¿Ôó°Ü=@\r±√ıN¿hóÆSÆ=@TÄOñ|?¿s)úEπ=@3~æ\0–õ¿+ì\n>@1ÀΩ3bq\Z¿„\Zl˛Òr>@‘í‹¿V¿J÷∞>@ôÊ÷i}¿HzN^!?@b˙†¿ ¿àFClâƒ?@€H\0|£E!¿õQ”@@XµaÎ∂©!¿÷Æ6´æq@@ayß¨cP\"¿C£ÍƒÀ@@≤º•–Î\"¿xG“A@È|\0cÈˆ\"¿{+˛ÈA@k|SÚ#¿òÖ¡{ûUA@n\r‡WzC$¿0ØCﬂ`A@Œ#…Ö∞%¿Íj|i—A@πRøWç&¿Õ.ª§ØA@¡œ¿&°˚&¿“àøj)ÛA@ê¯ÑpÆ&¿$nzX\\B@Nw¨wπ&¿D‘ÙÚnB@Ÿ@«A≠\'¿∆ﬁä¶ØB@pF?¸≤\'¿1X•òŒB@Nw¨wπ&¿‡{≈õ˚B@âîo4a&¿gOõ¶AC@âîo4a&¿Ô\"(àEàC@FJ∞¡\r5&¿%˙{g§C@FJ∞¡\r5&¿q¢îÓC@dõˆß™%¿wWD@Æ+’j–b%¿@»#2Ò(D@+;qV%¿:ˇ,Œ`1D@“-u*1r$¿W‡ÔÆºD@«eE´H$¿hÜ±ÊˇÈC@≥18ì¯‰#¿¨_®¥ÍC@•eÏÿë#¿@DO÷C@õ®VŒ\"¿MûpE’C@>BIªã\"¿å™πD6‘C@IÖ∞ç*\"¿ÌﬂÔÆÀC@∏@çÏ!¿ıTsâÓ¡C@VOÑstÇ!¿{tÉ⁄É≥C@ƒ\'¨ÕQ!¿≤eŒcπ™C@ñ¶÷°Ô ¿ŒCØ)¶C@#ëe“(Å ¿Ãr›v∏C@ZDµ!A ¿˚Z˙∏C@û˘ÛQò¿˜Ë˘¯®C@˜&å\'r.¿≥–JC®C@8˙1‹~¿ ¯é]’¨C@|\'üÉƒ¿=∫AÌ}πC@œÒS¥^¿OwWH¬C@´±uOﬂ¿uíÌ«B»C@Ù~FwëV¿¿ä©Û4«C@fö0¢Ú¿\'¯ª+,¿C@√ÔÏqt¿º&ÅÛ_™C@\ZË∑\n¿ ¯é]UñC@G‚på(O\Z¿>°¢>ÂÄC@ï!Œ˘‰¿ì]äèŸlC@;ÅØ7¿Q«C\\dwC@)¢qw≤X¿£¨˛Ió°C@˛3Ì≥ø¿'),(7,167,'polygon','\0\0\0\0\0\0\0\0\0\0\0\0\0Rπ£Pñ[@Åë˙Ïœ¿‚	\0Kî[@k=ùı¿M√EÌ<ì[@ò∫údá¿[WÁ‚í[@b°£≈C¿ÈS‰¸î[@*\0æÆ_¿`M±ùˆï[@pΩ´msi¿lç<Q›ò[@õ\nëÊ=l¿âÜ¯|Sõ[@«[‡ômp¿ıu¢où[@°\0—0h¿^vÅI¢ü[@ÇS>∫Q¿˜·Ø⁄ô†[@.£ıF\"¿Ìr__›†[@ﬁ»\"¿vp‘?†[@ùÖb|rÍ¿^vÅI¢ü[@<%˝≠∑“¿à2√6îû[@eä	í¿¿9ˆïªú[@êáwÃ∂¿WØõZdô[@ÜYK√\\√¿Rπ£Pñ[@Åë˙Ïœ¿'),(10,169,'polygon','\0\0\0\0\0\0\0\0\0\0\0\0\0`vdáô[@≥YvÕ¿B\n∫8ó[@â¶é[¿Uî%È ô[@—Z…£ì¿¿+ñ˘Qû[@HL\'u#’¿`vdáô[@≥YvÕ¿'),(11,171,'polygon','\0\0\0\0\0\0\0\0\0\0\0\0\0ëX~‹oô[@tpÈ`€Œ¿Gﬂ9vHó[@(¥ÌﬂÿÛ¿nÊÏPö[@@ù¶œ\ZÄ¿Åpàú\nù[@à>•:~‰¿à∫hô›ú[@Î<5ª@–¿ëX~‹oô[@tpÈ`€Œ¿'),(12,173,'polygon','\0\0\0\0\0\0\0\0\0\0\0\0\0j—#!≥ô[@tpÈ`€Œ¿oÈü¥1ó[@Ç‹ÿ¯¿å‚[‡ßô[@@ù¶œ\ZÄ¿i}Ó⁄Ûú[@p9œ;ñÊ¿j—#!≥ô[@tpÈ`€Œ¿'),(13,175,'polygon','\0\0\0\0\0\0\0\0\0\0\0\0\0~AÖ‡ô[@tpÈ`€Œ¿u&j‹‚ñ[@7W<∆Í¿ï„lUö[@ÆœÂá¿|ÌOÀ ù[@=4‰„¿~AÖ‡ô[@tpÈ`€Œ¿'),(14,177,'polygon','\0\0\0\0\0\0\0\0\0\0\0\0\0ÕGïUíô[@•qnP´ ¿ä1Ï=ó[@X¥£8Ò¿Öüã\'ïö[@U6WÓëä¿Åáîù[@º≈~ç≥·¿7À-Ôö[@ﬂnäÎ-«¿ÕGïUíô[@•qnP´ ¿'),(15,179,'polygon','\0\0\0\0\0\0\0\0\0\0\0\0\05åvÉPö[@—/…¢¯…¿7‡´…ó[@r›Çª¯¿e1o<Îô[@∞\ZŒ_Ÿê¿qÈzoù[@^dœ8¸¿nLäqËú[@tpÈ`€Œ¿5åvÉPö[@—/…¢¯…¿'),(17,188,'polygon','\0\0\0\0\0\0\0\0\0\0\0\0\0gÑ†ÅPf}@LÜ´Ã¿ Ü±ˆ≤e}@ Û±‹ı¿€Q∆sof}@\r–£2Ç¿‹ñSSg}@xÙ\0o”¿gÑ†ÅPf}@LÜ´Ã¿');
/*!40000 ALTER TABLE `Studyarea` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `StudyareaPoints`
--

DROP TABLE IF EXISTS `StudyareaPoints`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `StudyareaPoints` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `studyareaId` int(11) DEFAULT NULL,
  `lat` double DEFAULT NULL,
  `lng` double DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_StudiareaPoints_Studyarea1_idx` (`studyareaId`),
  CONSTRAINT `fk_StudiareaPoints_Studyarea1` FOREIGN KEY (`studyareaId`) REFERENCES `Studyarea` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=213 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `StudyareaPoints`
--

LOCK TABLES `StudyareaPoints` WRITE;
/*!40000 ALTER TABLE `StudyareaPoints` DISABLE KEYS */;
INSERT INTO `StudyareaPoints` VALUES (65,4,-4.68661385665204,39.262429475167174),(66,4,-3.481208809323797,37.7898622990301),(67,4,-2.9985844466896214,37.67996922618408),(68,4,-1.1981090858403265,34.44911288451021),(69,4,-1.4177782648419572,33.482053843464975),(70,4,-1.9229329750699877,32.25125142758921),(71,4,-2.318169152494475,31.50397853223607),(72,4,-3.547002831409447,30.470983647483205),(73,4,-4.423775078152496,29.921518283252972),(74,4,-4.577109391866131,29.525903221007173),(75,4,-5.561998699762688,29.67975352299164),(76,4,-6.152160655619393,29.723710752130067),(77,4,-6.610726173838691,30.031411356099003),(78,4,-7.0252534087530725,30.449005032914005),(79,4,-7.7661034142890255,30.690769793175303),(80,4,-8.3752413087505,31.130342084559505),(81,4,-8.636012911829676,31.76772190706661),(82,4,-8.83147368972516,32.141358354743176),(83,4,-9.157010455554612,32.8886312500963),(84,4,-9.46057622833301,33.591946916311),(85,4,-9.482249349412488,34.031519207695204),(86,4,-9.503921101275568,34.163390895110474),(87,4,-10.131792780018268,34.668899030202304),(88,4,-10.844770703828438,34.75681348847914),(89,4,-11.276060084197633,34.998578248740436),(90,4,-11.491463862454392,35.372214696417004),(91,4,-11.340698317272167,35.899701446078076),(92,4,-11.362241149585227,36.22938066461621),(93,4,-11.534524970601408,36.86676048712331),(94,4,-11.55605304977784,37.3722686222151),(95,4,-11.362241149585227,37.61403338247644),(96,4,-11.189853165016535,37.965691215583774),(97,4,-11.189853165016535,38.51515657981404),(98,4,-11.103620579499834,39.06462194404427),(99,4,-11.103620579499834,39.284408089736374),(100,4,-10.833305983642491,39.86391096609992),(101,4,-10.692996347925073,40.09461491924612),(102,4,-10.504015637704898,40.319860713455185),(103,4,-10.223031355670871,40.38576676556674),(104,4,-10.141931686131018,40.13857065880474),(105,4,-9.947208977327021,39.82812198322474),(106,4,-9.784851250750592,39.8336382357742),(107,4,-9.403000374334917,39.67429402493732),(108,4,-9.272911497538322,39.66618163791666),(109,4,-9.083112255354074,39.65790614190768),(110,4,-8.961045222037118,39.58650767052349),(111,4,-8.754794702435618,39.51509206897156),(112,4,-8.65977227887551,39.402461351573095),(113,4,-8.466939027264113,39.33378264978371),(114,4,-8.252264571092859,39.298147093628174),(115,4,-8.12721029716719,39.43821607411282),(116,4,-7.898750125960867,39.43823171470076),(117,4,-7.795357339791491,39.32009815097104),(118,4,-7.623886853120049,39.31455359640536),(119,4,-7.441453987683334,39.35026139718134),(120,4,-7.256220643638186,39.44915548047406),(121,4,-7.2180748352370445,39.51783269268362),(122,4,-7.0845392834450545,39.56453799343309),(123,4,-6.986406835762532,39.55630346086867),(124,4,-6.863712339483681,39.501347986966834),(125,4,-6.776443641161725,39.33105319792019),(126,4,-6.577303118123875,39.17448014718134),(127,4,-6.47360918121307,39.006995992097465),(128,4,-6.304378764325811,38.85038942581304),(129,4,-6.086618295965818,38.93275025662904),(153,7,-6.953052432367613,110.34867189892356),(154,7,-6.989857155679365,110.31707764048033),(155,7,-7.017118045869832,110.30059367955343),(156,7,-7.06618362115097,110.2950990259111),(157,7,-7.09344002605436,110.31433031365918),(158,7,-7.102979386908703,110.34317724528128),(159,7,-7.105704882242792,110.38850813783027),(160,7,-7.109793094938984,110.42697071332638),(161,7,-7.101616633182963,110.45993863518021),(162,7,-7.079812024901783,110.49428022044458),(163,7,-7.033473814091443,110.50939051796094),(164,7,-7.0157550392247545,110.51351150819264),(165,7,-6.978952353967024,110.50389586431862),(166,7,-6.955778807235538,110.49428022044458),(167,7,-6.938057087950774,110.47779625951767),(168,7,-6.928514347326078,110.44894932789559),(169,7,-6.940783549760778,110.3967501182937),(179,10,-6.9506455414054615,110.3988886978599),(180,10,-6.9847242631097455,110.36042612236378),(181,10,-7.144179483529257,110.40300968809163),(182,10,-6.958143072627633,110.4737533537363),(183,11,-6.952008737800599,110.39745247209761),(184,11,-6.988131998904455,110.3637977185385),(185,11,-7.125102276367478,110.41118910620337),(186,11,-6.973137775741319,110.45377267193123),(187,11,-6.953371930240971,110.45102534511008),(188,12,-6.952008737800599,110.40155819413636),(189,12,-6.9922212490642,110.36240878693498),(190,12,-7.125102276367478,110.4008713624311),(191,12,-6.975182470820416,110.45238374032765),(192,13,-6.952008737800599,110.4043010522181),(193,13,-6.979271834194219,110.35759649625855),(194,13,-7.131915655899779,110.40773521074458),(195,13,-6.971774640730276,110.4551265984094),(196,14,-6.9479191367539945,110.3995565374227),(197,14,-6.98540581225556,110.36315445704244),(198,14,-7.135322307656253,110.41535366664431),(199,14,-6.97041150175345,110.45450307384574),(200,14,-6.944511108707721,110.42084832028664),(201,15,-6.947237533120598,110.41116415574166),(202,15,-6.992902787278015,110.36033860955038),(203,15,-7.141454216913804,110.40498267039409),(204,15,-6.996310463429386,110.45992920681714),(205,15,-6.952008737800599,110.4516872263537),(209,17,-6.949282341056348,470.3946548719582),(210,17,-6.990176628457358,470.35619229646204),(211,17,-7.127146300865895,470.4022100207163),(212,17,-6.9560983032544295,470.45784338884465);
/*!40000 ALTER TABLE `StudyareaPoints` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Targets`
--

DROP TABLE IF EXISTS `Targets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Targets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) DEFAULT NULL,
  `description` varchar(45) DEFAULT NULL,
  `projectsId` int(11) DEFAULT NULL,
  `addStructure` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_Targets_Projects1_idx` (`projectsId`),
  CONSTRAINT `fk_Targets_Projects1` FOREIGN KEY (`projectsId`) REFERENCES `Projects` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Targets`
--

LOCK TABLES `Targets` WRITE;
/*!40000 ALTER TABLE `Targets` DISABLE KEYS */;
INSERT INTO `Targets` VALUES (1,'RW','RW',1,1),(3,'Kelurahan','KELURAHAN',1,NULL),(6,'DUSUN','Monitoring COVID-19 at RW level.',1,NULL),(7,'Hospitals','HSP',3,NULL),(8,'Markets','Tanzania Markets and supplies task team ',3,NULL),(9,'Civil society','Civil society',3,NULL),(10,'RW','RW0\'s monitoring COVID-19',4,0),(11,'Hospitals','HS',4,0),(12,'Social Security Assistance Monitoring ','Social Security Assistance',4,1),(13,'RT','Target community: RT',4,1);
/*!40000 ALTER TABLE `Targets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `TargetsChecklist`
--

DROP TABLE IF EXISTS `TargetsChecklist`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `TargetsChecklist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `checklistId` int(11) DEFAULT NULL,
  `targetsId` int(11) DEFAULT NULL,
  `frequency` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_CommunitiesChecklist_Checklist1_idx` (`checklistId`),
  KEY `fk_CommunitiesChecklist_Tergets1_idx` (`targetsId`),
  CONSTRAINT `fk_CommunitiesChecklist_Checklist1` FOREIGN KEY (`checklistId`) REFERENCES `Checklist` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_CommunitiesChecklist_Tergets1` FOREIGN KEY (`targetsId`) REFERENCES `Targets` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `TargetsChecklist`
--

LOCK TABLES `TargetsChecklist` WRITE;
/*!40000 ALTER TABLE `TargetsChecklist` DISABLE KEYS */;
INSERT INTO `TargetsChecklist` VALUES (18,7,1,6),(19,6,1,3),(22,5,3,4),(27,18,7,3),(28,18,8,3),(29,21,9,2),(31,21,3,2),(32,5,1,1),(33,27,11,3),(34,28,12,3),(35,30,10,3),(36,30,13,3);
/*!40000 ALTER TABLE `TargetsChecklist` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `TargetsElems`
--

DROP TABLE IF EXISTS `TargetsElems`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `TargetsElems` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `targetsId` int(11) DEFAULT NULL,
  `usersTargetsId` int(11) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `usersId` int(11) DEFAULT NULL,
  `dimensionesElemId` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_TargetsElems_Tergets1_idx` (`targetsId`),
  KEY `fk_TargetsElems_UsersTargets1_idx` (`usersTargetsId`),
  KEY `fk_TargetsElems_Users1_idx` (`usersId`),
  KEY `fk_TargetsElems_DimensionesElem1_idx` (`dimensionesElemId`),
  CONSTRAINT `fk_TargetsElems_DimensionesElem1` FOREIGN KEY (`dimensionesElemId`) REFERENCES `DimensionesElem` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `fk_TargetsElems_Tergets1` FOREIGN KEY (`targetsId`) REFERENCES `Targets` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `fk_TargetsElems_Users1` FOREIGN KEY (`usersId`) REFERENCES `Users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `fk_TargetsElems_UsersTargets1` FOREIGN KEY (`usersTargetsId`) REFERENCES `UsersTargets` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=116 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `TargetsElems`
--

LOCK TABLES `TargetsElems` WRITE;
/*!40000 ALTER TABLE `TargetsElems` DISABLE KEYS */;
INSERT INTO `TargetsElems` VALUES (45,9,27,'Please fill the symptoms questionnaire',16,NULL),(46,9,26,'Request of pickup',15,NULL),(76,11,NULL,NULL,NULL,32),(77,11,NULL,NULL,NULL,33),(78,11,NULL,NULL,NULL,34),(79,11,NULL,NULL,NULL,35),(80,11,NULL,NULL,NULL,36),(81,11,NULL,NULL,NULL,37),(82,11,NULL,NULL,NULL,38),(83,11,NULL,NULL,NULL,39),(84,11,NULL,NULL,NULL,40),(85,11,NULL,NULL,NULL,41),(86,11,NULL,NULL,NULL,42),(87,11,NULL,NULL,NULL,43),(88,11,NULL,NULL,NULL,44),(89,11,NULL,NULL,NULL,45),(90,11,NULL,NULL,NULL,46),(91,11,NULL,NULL,NULL,47),(92,11,NULL,NULL,NULL,48),(93,11,NULL,NULL,NULL,49),(94,11,NULL,NULL,NULL,50),(95,11,NULL,NULL,NULL,51),(96,11,NULL,NULL,NULL,52),(97,11,NULL,NULL,NULL,53),(98,11,NULL,NULL,NULL,54),(99,11,NULL,NULL,NULL,55),(100,11,NULL,NULL,NULL,56),(101,11,NULL,NULL,NULL,57),(102,11,NULL,NULL,NULL,58),(103,11,NULL,NULL,NULL,59),(104,11,NULL,NULL,NULL,60),(105,12,29,NULL,4,61),(106,12,29,NULL,4,62),(107,12,29,NULL,4,63),(108,12,29,NULL,4,64),(109,13,31,NULL,4,65),(110,13,32,NULL,17,66),(111,13,32,NULL,17,67),(112,13,32,NULL,17,68),(113,13,32,NULL,17,69),(114,13,32,NULL,17,70),(115,13,32,NULL,17,71);
/*!40000 ALTER TABLE `TargetsElems` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Tipos`
--

DROP TABLE IF EXISTS `Tipos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Tipos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(63) DEFAULT NULL,
  `siglas` varchar(10) DEFAULT NULL,
  `tabla` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Tipos`
--

LOCK TABLES `Tipos` WRITE;
/*!40000 ALTER TABLE `Tipos` DISABLE KEYS */;
INSERT INTO `Tipos` VALUES (1,'Abierta','ab','Preguntas'),(2,'M√∫ltiple','mult','Preguntas'),(3,'Sub√°rea','sub','Preguntas'),(4,'Num√©rica','num','Preguntas'),(5,'Collabmap','cm','Preguntas'),(6,'spatial','spatial','Preguntas'),(7,'One point','op','Preguntas');
/*!40000 ALTER TABLE `Tipos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Users`
--

DROP TABLE IF EXISTS `Users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(45) DEFAULT NULL,
  `name` varchar(45) DEFAULT NULL,
  `lastname` varchar(45) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `pwd` varchar(255) DEFAULT NULL,
  `gender` varchar(1) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `hashConf` varchar(255) DEFAULT NULL,
  `confirmed` tinyint(4) DEFAULT NULL,
  `validated` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Users`
--

LOCK TABLES `Users` WRITE;
/*!40000 ALTER TABLE `Users` DISABLE KEYS */;
INSERT INTO `Users` VALUES (1,'juanma','juan manuel','gomez','xpektro@hotmail.com','$2y$10$rNQNf3PqPZ8yX5ByqHIMB.pt0TAvFpYIRNRXgnkjFxzPZzgcofybu','M',38,NULL,1,1),(2,'juanma2','juan manuel','gomez Perez figueroa','xpektro3@hotmail.com','$2y$10$FJ.DUpNxZR4x2GB6H7v.D.abIuVS1re/8y5dJT1KsiAlY735JLrGW','M',38,NULL,NULL,NULL),(3,'Daniela','Daniela','Evia ','daniela.evia@capsus.mx','$2y$10$xxB9X37wIWffFpk2xy1WcetW.c9Jz6W25DDsKRZV7Hm4K4Xlk6d8q','F',41,NULL,1,1),(4,'dante','Dante','Zayas','dante.zayas@capsus.mx','$2y$10$5qS.s3mLETYLfS8.8/aj8u6FHmPK.tbw38ozDl/UxjPkVnAR/bwmG','M',22,NULL,1,1),(9,'juanma3','juanma','123','xpektro@hotmail.com','$2y$10$r6C9jUJGgsYvrwC2yVju7.CtQg/XPfHA32zYIgU1fx1/BquGjsTvG','M',38,'YL9pM5Bb6lSUyiP157VCeeB5Ey15fjiMQjZazEYN0tbazRrQERZNW',NULL,NULL),(14,'facilitador1','facilitador','uno','facilitador@capsus.mx','$2y$10$YXnCr6ot2sKkXWzda4dVPODpVnNGqkJZV4UGOLzB8Xi7l2O7XV/GG','M',23,NULL,1,1),(15,'John','John','Doe','john.doe@capsus.mx','$2y$10$SX.f2aLwwoNXCFWsFX8ZN.czb6bscH9AjOe8FUvtQJfwzB21MCuEC','M',41,NULL,1,1),(16,'marygrace','MaryGrace','Weber','marygraceweber@gmail.com','$2y$10$6XZkAryJcSqc.UzFjrCg3OnldTn2Y/pUCqm2FdaLEqJHlM/MVzoGK','F',30,NULL,1,1),(17,'demo','Demo','User','demo@capsus.mx','$2y$10$ZYcm7UUkaDOTgDWF0sKFxuNKtcHk3V9kV4xFUfSTzEmX2aFnvO6l.','M',22,NULL,1,1);
/*!40000 ALTER TABLE `Users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `UsersTargets`
--

DROP TABLE IF EXISTS `UsersTargets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `UsersTargets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usersId` int(11) DEFAULT NULL,
  `targetsId` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_UsersComunities_Users1_idx` (`usersId`),
  KEY `fk_UsersTargets_Tergets1_idx` (`targetsId`),
  CONSTRAINT `fk_UsersComunities_Users1` FOREIGN KEY (`usersId`) REFERENCES `Users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_UsersTargets_Tergets1` FOREIGN KEY (`targetsId`) REFERENCES `Targets` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `UsersTargets`
--

LOCK TABLES `UsersTargets` WRITE;
/*!40000 ALTER TABLE `UsersTargets` DISABLE KEYS */;
INSERT INTO `UsersTargets` VALUES (10,1,1),(12,3,1),(18,14,7),(20,1,3),(21,1,7),(22,15,7),(23,3,7),(24,15,8),(25,16,9),(26,15,9),(27,3,9),(29,4,12),(30,3,12),(31,4,13),(32,17,13);
/*!40000 ALTER TABLE `UsersTargets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Visitas`
--

DROP TABLE IF EXISTS `Visitas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Visitas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NULL DEFAULT NULL,
  `estatus` varchar(45) DEFAULT NULL,
  `resumen` text DEFAULT NULL,
  `finishDate` datetime DEFAULT NULL,
  `finalizada` tinyint(4) DEFAULT NULL,
  `checklistId` int(11) DEFAULT NULL,
  `type` varchar(5) DEFAULT NULL,
  `elemId` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_Visitas_Checklist1_idx` (`checklistId`),
  KEY `visElemId` (`elemId`),
  KEY `visType` (`type`),
  CONSTRAINT `fk_Visitas_Checklist1` FOREIGN KEY (`checklistId`) REFERENCES `Checklist` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=164 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Visitas`
--

LOCK TABLES `Visitas` WRITE;
/*!40000 ALTER TABLE `Visitas` DISABLE KEYS */;
INSERT INTO `Visitas` VALUES (58,'2020-04-14 01:32:05',NULL,'this is a test','2020-04-13 21:52:41',1,5,'trgt',20),(59,'2020-04-14 01:32:45',NULL,'','2020-04-13 21:58:48',1,6,'trgt',20),(60,'2020-04-14 02:58:54',NULL,'','2020-04-13 22:02:10',1,7,'trgt',20),(61,'2020-04-14 03:39:05',NULL,'',NULL,NULL,5,'trgt',21),(62,'2020-04-14 03:39:13',NULL,'',NULL,NULL,6,'trgt',21),(63,'2020-04-14 03:41:23',NULL,'',NULL,NULL,7,'trgt',21),(64,'2020-04-15 13:56:16',NULL,'',NULL,NULL,5,'trgt',22),(65,'2020-04-15 14:06:53',NULL,'Test',NULL,NULL,6,'trgt',22),(66,'2020-04-15 14:10:23',NULL,'Comments',NULL,NULL,7,'trgt',22),(67,'2020-04-15 15:25:35',NULL,NULL,NULL,NULL,5,'trgt',23),(68,'2020-04-15 16:13:57',NULL,'Comments.',NULL,NULL,6,'trgt',25),(69,'2020-04-15 16:25:31',NULL,'General comments go here.','2020-04-15 11:28:04',1,7,'trgt',25),(70,'2020-04-15 17:15:39',NULL,'General Comments','2020-04-15 12:16:29',1,5,'trgt',25),(71,'2020-04-15 17:18:57',NULL,'General comments go here.','2020-04-15 12:20:09',1,5,'trgt',26),(72,'2020-04-15 22:37:02',NULL,'Everything fine\r\n','2020-04-15 17:40:19',1,6,'trgt',27),(73,'2020-04-15 22:40:28',NULL,'Hello world!',NULL,NULL,5,'trgt',27),(74,'2020-04-15 22:41:07',NULL,NULL,NULL,NULL,7,'trgt',27),(76,'2020-04-16 17:22:36',NULL,'','2020-04-16 12:23:19',1,5,'trgt',30),(77,'2020-04-16 17:23:34',NULL,NULL,NULL,NULL,6,'trgt',30),(78,'2020-04-16 17:23:37',NULL,NULL,NULL,NULL,7,'trgt',30),(80,'2020-04-16 21:47:26',NULL,NULL,NULL,NULL,5,'trgt',33),(82,'2020-04-16 22:02:06',NULL,'n',NULL,NULL,18,'trgt',34),(84,'2020-04-16 22:09:45',NULL,'a','2020-04-16 17:11:14',1,18,'trgt',32),(86,'2020-04-16 22:31:12',NULL,'knoviane',NULL,NULL,18,'trgt',37),(87,'2020-04-16 22:32:48',NULL,'s','2020-04-16 20:32:54',1,18,'trgt',36),(88,'2020-04-17 08:33:02',NULL,'Every supply is ok',NULL,NULL,18,'trgt',39),(89,'2020-04-17 08:36:30',NULL,'Ok','2020-04-17 03:38:04',1,18,'trgt',38),(90,'2020-04-17 15:58:34',NULL,'Hola. No entiendo de d√≥nde sali√≥ esta pregunta si yo nunca la program√©. Parece que est√° en todos los cuestionarios por default, pero no entiendo para qu√© sirve. ','2020-04-19 15:29:54',1,21,'trgt',43),(91,'2020-04-17 16:29:28',NULL,NULL,NULL,NULL,16,'trgt',22),(92,'2020-04-17 17:49:15',NULL,NULL,NULL,NULL,16,'trgt',44),(93,'2020-04-17 17:49:38',NULL,NULL,NULL,NULL,6,'trgt',44),(94,'2020-04-17 21:10:43',NULL,NULL,NULL,NULL,16,'trgt',20),(95,'2020-04-19 21:30:23',NULL,NULL,'2020-04-19 16:32:43',1,21,'trgt',46),(96,'2020-04-19 23:13:26',NULL,NULL,NULL,NULL,7,'trgt',28),(97,'2020-04-19 23:14:16',NULL,NULL,NULL,NULL,16,'trgt',33),(98,'2020-04-19 23:14:37',NULL,NULL,NULL,NULL,16,'trgt',27),(128,'2020-04-22 00:15:34',NULL,NULL,'2020-04-21 19:04:34',1,27,'trgt',76),(129,'2020-04-22 00:15:34',NULL,NULL,'2020-04-21 19:04:34',1,27,'trgt',77),(130,'2020-04-22 00:15:34',NULL,NULL,'2020-04-21 19:04:34',1,27,'trgt',78),(131,'2020-04-22 00:15:34',NULL,NULL,'2020-04-21 19:04:34',1,27,'trgt',79),(132,'2020-04-22 00:15:34',NULL,NULL,'2020-04-21 19:04:34',1,27,'trgt',80),(133,'2020-04-22 00:15:34',NULL,NULL,'2020-04-21 19:04:34',1,27,'trgt',81),(134,'2020-04-22 00:15:34',NULL,NULL,'2020-04-21 19:04:34',1,27,'trgt',82),(135,'2020-04-22 00:15:34',NULL,NULL,'2020-04-21 19:04:34',1,27,'trgt',83),(136,'2020-04-22 00:15:34',NULL,NULL,'2020-04-21 19:04:34',1,27,'trgt',84),(137,'2020-04-22 00:15:34',NULL,NULL,'2020-04-21 19:04:34',1,27,'trgt',85),(138,'2020-04-22 00:15:34',NULL,NULL,'2020-04-21 19:04:34',1,27,'trgt',86),(139,'2020-04-22 00:15:34',NULL,NULL,'2020-04-21 19:04:34',1,27,'trgt',87),(140,'2020-04-22 00:15:34',NULL,NULL,'2020-04-21 19:04:34',1,27,'trgt',88),(141,'2020-04-22 00:15:34',NULL,NULL,'2020-04-21 19:04:34',1,27,'trgt',89),(142,'2020-04-22 00:15:34',NULL,NULL,'2020-04-21 19:04:34',1,27,'trgt',90),(143,'2020-04-22 00:15:34',NULL,NULL,'2020-04-21 19:04:34',1,27,'trgt',91),(144,'2020-04-22 00:15:34',NULL,NULL,'2020-04-21 19:04:34',1,27,'trgt',92),(145,'2020-04-22 00:15:34',NULL,NULL,'2020-04-21 19:04:34',1,27,'trgt',93),(146,'2020-04-22 00:15:34',NULL,NULL,'2020-04-21 19:04:34',1,27,'trgt',94),(147,'2020-04-22 00:15:34',NULL,NULL,'2020-04-21 19:04:34',1,27,'trgt',95),(148,'2020-04-22 00:15:34',NULL,NULL,'2020-04-21 19:04:34',1,27,'trgt',96),(149,'2020-04-22 00:15:34',NULL,NULL,'2020-04-21 19:04:34',1,27,'trgt',97),(150,'2020-04-22 00:15:34',NULL,NULL,'2020-04-21 19:04:34',1,27,'trgt',98),(151,'2020-04-22 00:15:34',NULL,NULL,'2020-04-21 19:04:34',1,27,'trgt',99),(152,'2020-04-22 00:15:34',NULL,NULL,'2020-04-21 19:04:34',1,27,'trgt',100),(153,'2020-04-22 00:15:34',NULL,NULL,'2020-04-21 19:04:34',1,27,'trgt',101),(154,'2020-04-22 00:15:34',NULL,NULL,'2020-04-21 19:04:34',1,27,'trgt',102),(155,'2020-04-22 00:15:34',NULL,NULL,'2020-04-21 19:04:34',1,27,'trgt',103),(156,'2020-04-22 00:15:34',NULL,NULL,'2020-04-21 19:04:34',1,27,'trgt',104),(157,'2020-04-23 00:45:35',NULL,NULL,NULL,NULL,28,'trgt',105),(158,'2020-04-23 18:06:53',NULL,NULL,NULL,NULL,28,'trgt',106),(159,'2020-04-23 18:07:41',NULL,NULL,NULL,NULL,28,'trgt',107),(160,'2020-04-23 20:54:39',NULL,NULL,NULL,NULL,30,'trgt',109),(161,'2020-04-23 22:14:47',NULL,NULL,'2020-04-23 22:21:17',1,30,'trgt',113),(162,'2020-04-23 22:27:42',NULL,NULL,NULL,NULL,30,'trgt',114),(163,'2020-04-23 22:29:37',NULL,NULL,'2020-04-23 22:33:39',1,30,'trgt',115);
/*!40000 ALTER TABLE `Visitas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cambiarPwd`
--

DROP TABLE IF EXISTS `cambiarPwd`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cambiarPwd` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hash` varchar(255) DEFAULT NULL,
  `clientesId` int(11) DEFAULT NULL,
  `expira` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cambiarPwd`
--

LOCK TABLES `cambiarPwd` WRITE;
/*!40000 ALTER TABLE `cambiarPwd` DISABLE KEYS */;
/*!40000 ALTER TABLE `cambiarPwd` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `estatusHist`
--

DROP TABLE IF EXISTS `estatusHist`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `estatusHist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `clientesId` int(11) DEFAULT NULL,
  `estatus` int(11) DEFAULT NULL,
  `timestamp` timestamp NULL DEFAULT NULL,
  `usuarioId` int(11) DEFAULT NULL,
  `visitasId` int(11) DEFAULT NULL,
  `comentario` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_estatusHist_usrAdmin1_idx` (`usuarioId`),
  KEY `fk_estatusHist_Estatus1_idx` (`estatus`),
  CONSTRAINT `fk_estatusHist_Estatus1` FOREIGN KEY (`estatus`) REFERENCES `Estatus` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_estatusHist_usrAdmin1` FOREIGN KEY (`usuarioId`) REFERENCES `usrAdmin` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `estatusHist`
--

LOCK TABLES `estatusHist` WRITE;
/*!40000 ALTER TABLE `estatusHist` DISABLE KEYS */;
/*!40000 ALTER TABLE `estatusHist` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usrAdmin`
--

DROP TABLE IF EXISTS `usrAdmin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usrAdmin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `lastname` varchar(100) DEFAULT NULL,
  `mail` varchar(255) DEFAULT NULL,
  `pwd` varchar(255) DEFAULT NULL,
  `username` varchar(45) DEFAULT NULL,
  `ext` varchar(20) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `nivel` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usrAdmin`
--

LOCK TABLES `usrAdmin` WRITE;
/*!40000 ALTER TABLE `usrAdmin` DISABLE KEYS */;
INSERT INTO `usrAdmin` VALUES (2,'juanma','gomez','juanma','$2y$10$wpFAGW0sOuEHvBZ8Zu2Kp.XefmPNminGytNf5w7hSynN96JSLsdrK','juanma',NULL,NULL,60),(24,'Daniela','Evia','daniela.evia@capsus.mx','$2y$10$opoNa4iitZIiXU/SMU.rXunKtBfweV4XEyKj8em0o6Ba5ifoshjrO','dani',NULL,NULL,60),(25,'Dante','Zayas','dante.zayas@capsus.mx','$2y$10$L3C0IKg7wKK1vmO7W8yG5.p49Jzb8ZWxrl8jU.9w4mlFCNt34ZCgC','dante',NULL,NULL,60),(26,'Demo','Demo','demo@capsus.mx','$2y$10$eTE.B4DOHpshzqvwEGhh0eYJl.HWZaTYwvXeX4EEgws8pYvbdJwJC','demo_admin',NULL,NULL,60),(27,'Ricardo','Ochoa Sosa','ricardo.ochoa@capsus.mx','$2y$10$LnBYLbW.J.aFwnFDAOElyeYvINFvqucox7uHSzsy9xw6HslwvSBqy','ricardo',NULL,NULL,60),(28,'Tommaso','Bassetti','tommaso.bassetti@capsus.mx','$2y$10$fuDa6FFarvk1cOzuSaOnOOfSCw21iQqSP0XAs35abnLcJXee.mcFO','tommaso',NULL,NULL,60);
/*!40000 ALTER TABLE `usrAdmin` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2020-04-24  2:01:22
