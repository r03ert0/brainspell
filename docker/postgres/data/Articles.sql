-- MySQL dump 10.13  Distrib 5.7.11, for Linux (x86_64)
--
-- Host: localhost    Database: brainspell
-- ------------------------------------------------------
-- Server version	5.7.11
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE=',POSTGRESQL' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table "Articles"
--

DROP TABLE IF EXISTS "Articles";
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE "Articles" (
  "UniqueID" int(25) NOT NULL,
  "TIMESTAMP" timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  "Title" text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  "Authors" text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  "Abstract" text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  "Reference" text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  "PMID" varchar(64) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  "DOI" varchar(128) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  "NeuroSynthID" varchar(64) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  "Experiments" text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  "Metadata" text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  PRIMARY KEY ("UniqueID")
);
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-04-05 17:55:04
