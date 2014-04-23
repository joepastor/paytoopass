CREATE DATABASE  IF NOT EXISTS `sarita` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `sarita`;
-- MySQL dump 10.13  Distrib 5.6.13, for osx10.6 (i386)
--
-- Host: 127.0.0.1    Database: sarita
-- ------------------------------------------------------
-- Server version	5.6.17

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
-- Table structure for table `choferes`
--

DROP TABLE IF EXISTS `choferes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `choferes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `apellido` varchar(45) NOT NULL,
  `nombre` varchar(45) NOT NULL,
  `dni` varchar(45) NOT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `usuario` int(10) unsigned NOT NULL,
  `clave` int(10) unsigned NOT NULL,
  `mandatarias_id` int(11) NOT NULL,
  PRIMARY KEY (`id`,`mandatarias_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `choferes`
--

LOCK TABLES `choferes` WRITE;
/*!40000 ALTER TABLE `choferes` DISABLE KEYS */;
/*!40000 ALTER TABLE `choferes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cocido`
--

DROP TABLE IF EXISTS `cocido`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cocido` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_virloc` int(11) NOT NULL,
  `latitud` varchar(45) DEFAULT NULL,
  `longitud` varchar(45) DEFAULT NULL,
  `velocidad` int(11) DEFAULT NULL,
  `rumbo` int(11) DEFAULT NULL,
  `evento` varchar(45) DEFAULT NULL,
  `fecha` datetime DEFAULT NULL,
  `numero_evento` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cocido`
--

LOCK TABLES `cocido` WRITE;
/*!40000 ALTER TABLE `cocido` DISABLE KEYS */;
/*!40000 ALTER TABLE `cocido` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `crudo`
--

DROP TABLE IF EXISTS `crudo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `crudo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `levantado` tinyint(1) NOT NULL DEFAULT '0',
  `info` varchar(200) NOT NULL,
  `host` varchar(50) DEFAULT NULL,
  `port` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `crudo`
--

LOCK TABLES `crudo` WRITE;
/*!40000 ALTER TABLE `crudo` DISABLE KEYS */;
/*!40000 ALTER TABLE `crudo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `equipos`
--

DROP TABLE IF EXISTS `equipos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `equipos` (
  `id` int(11) NOT NULL,
  `ip` varchar(15) DEFAULT NULL COMMENT 'IP desde donde se recibio el ultimo paquete',
  `puerto` int(10) unsigned DEFAULT NULL COMMENT 'Puerto desde donde se recibio el ultimo paquete',
  `latitud` varchar(45) DEFAULT NULL,
  `longitud` varchar(45) DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `velocidad` int(11) DEFAULT NULL,
  `rumbo` int(11) DEFAULT NULL,
  `chofer` varchar(45) DEFAULT NULL COMMENT 'Chofer a bordo',
  `estado` varchar(100) DEFAULT NULL COMMENT 'Estado del vehiculo',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `equipos`
--

LOCK TABLES `equipos` WRITE;
/*!40000 ALTER TABLE `equipos` DISABLE KEYS */;
/*!40000 ALTER TABLE `equipos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mandatarias`
--

DROP TABLE IF EXISTS `mandatarias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mandatarias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(45) NOT NULL,
  `usuario` varchar(45) NOT NULL,
  `password` varchar(45) NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mandatarias`
--

LOCK TABLES `mandatarias` WRITE;
/*!40000 ALTER TABLE `mandatarias` DISABLE KEYS */;
/*!40000 ALTER TABLE `mandatarias` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mensajes`
--

DROP TABLE IF EXISTS `mensajes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mensajes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_mensaje` int(11) NOT NULL,
  `id_mensaje_hex` varchar(4) NOT NULL,
  `mensaje` varchar(95) DEFAULT NULL,
  `entregado` tinyint(1) NOT NULL DEFAULT '0',
  `leido` tinyint(1) NOT NULL DEFAULT '0',
  `id_virloc` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_mensajes_equipos1` (`id_virloc`),
  CONSTRAINT `fk_mensajes_equipos1` FOREIGN KEY (`id_virloc`) REFERENCES `equipos` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mensajes`
--

LOCK TABLES `mensajes` WRITE;
/*!40000 ALTER TABLE `mensajes` DISABLE KEYS */;
/*!40000 ALTER TABLE `mensajes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pagos`
--

DROP TABLE IF EXISTS `pagos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pagos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `cuenta` varchar(45) DEFAULT NULL COMMENT 'Nro de tarjeta o numero de wallet',
  `id_chofer` varchar(45) NOT NULL,
  `monto` decimal(8,2) NOT NULL,
  `id_virloc` int(11) DEFAULT NULL,
  `tipo_cobro` enum('WALLET','TARJETA') DEFAULT NULL,
  `tiempo` varchar(45) DEFAULT NULL,
  `distancia` float DEFAULT NULL,
  `estado` varchar(255) NOT NULL DEFAULT 'PENDIENTE',
  `mensaje` varchar(255) DEFAULT NULL,
  `password` varchar(45) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_pagos_equipos` (`id_virloc`),
  CONSTRAINT `fk_pagos_equipos` FOREIGN KEY (`id_virloc`) REFERENCES `equipos` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pagos`
--

LOCK TABLES `pagos` WRITE;
/*!40000 ALTER TABLE `pagos` DISABLE KEYS */;
/*!40000 ALTER TABLE `pagos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vehiculos`
--

DROP TABLE IF EXISTS `vehiculos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vehiculos` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_virloc` int(10) unsigned NOT NULL,
  `mandataria` varchar(45) DEFAULT NULL,
  `patente` varchar(45) NOT NULL,
  `marca` varchar(45) NOT NULL,
  `modelo` varchar(45) NOT NULL,
  `color` varchar(45) NOT NULL,
  `nrolicencia` varchar(45) DEFAULT NULL,
  `choferes_id` int(10) unsigned NOT NULL,
  `mandatarias_id` int(11) NOT NULL,
  `equipos_id` int(11) NOT NULL,
  PRIMARY KEY (`id`,`choferes_id`,`mandatarias_id`,`equipos_id`),
  KEY `fk_vehiculos_choferes1_idx` (`choferes_id`),
  KEY `fk_vehiculos_mandatarias1_idx` (`mandatarias_id`),
  KEY `fk_vehiculos_equipos1_idx` (`equipos_id`),
  CONSTRAINT `fk_vehiculos_choferes1` FOREIGN KEY (`choferes_id`) REFERENCES `choferes` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_vehiculos_mandatarias1` FOREIGN KEY (`mandatarias_id`) REFERENCES `mandatarias` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_vehiculos_equipos1` FOREIGN KEY (`equipos_id`) REFERENCES `equipos` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vehiculos`
--

LOCK TABLES `vehiculos` WRITE;
/*!40000 ALTER TABLE `vehiculos` DISABLE KEYS */;
/*!40000 ALTER TABLE `vehiculos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `viajes`
--

DROP TABLE IF EXISTS `viajes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `viajes` (
  `id` int(11) NOT NULL,
  `cuentas_id` int(11) NOT NULL,
  `choferes_id` int(11) NOT NULL,
  `vehiculos_id` int(11) NOT NULL,
  `estado` enum('PENDIENTE','ACEPTADO','INICIADO','FINALIZADO','COBRADO') DEFAULT NULL,
  `lugar_inicio` varchar(45) DEFAULT NULL COMMENT 'Informacion de origen, latitud y longitud',
  `destino` varchar(400) NOT NULL COMMENT 'Linea completa de indicacion de destino que le llegara al chofer',
  `importe` decimal(8,2) DEFAULT NULL COMMENT 'Si el importe esta seteado al principio del viaje, es con costo fijo.',
  PRIMARY KEY (`id`,`choferes_id`,`vehiculos_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `viajes`
--

LOCK TABLES `viajes` WRITE;
/*!40000 ALTER TABLE `viajes` DISABLE KEYS */;
/*!40000 ALTER TABLE `viajes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'sarita'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2014-04-22 22:38:33
