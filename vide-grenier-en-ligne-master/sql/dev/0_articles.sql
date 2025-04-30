-- MySQL dump 10.13  Distrib 5.7.24, for Linux (x86_64)
--
-- Host: b41jkc9qwfquwgwxzxw6-mysql.services.clever-cloud.com    Database: b41jkc9qwfquwgwxzxw6
-- ------------------------------------------------------
-- Server version	8.0.41-32

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
-- Table structure for table `articles`
--

DROP TABLE IF EXISTS `articles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `articles` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `description` text NOT NULL,
  `published_date` date DEFAULT NULL,
  `user_id` int NOT NULL,
  `views` int unsigned NOT NULL DEFAULT '0',
  `picture` varchar(200) DEFAULT NULL,
  `ville_id` mediumint unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `picture` (`picture`),
  KEY `user_id` (`user_id`),
  KEY `articles_ibfk_2` (`ville_id`),
  CONSTRAINT `articles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `articles_ibfk_2` FOREIGN KEY (`ville_id`) REFERENCES `villes_france` (`ville_id`)
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `articles`
--

LOCK TABLES `articles` WRITE;
/*!40000 ALTER TABLE `articles` DISABLE KEYS */;
INSERT INTO `articles` VALUES (1,'Mappemonde à gratter','Carte du monde à gratter. Neuve dans son emballage d\'origine','2018-05-28',1,4,'1.jpeg',30438);
INSERT INTO `articles` VALUES (2,'Guide Berlin','Guide de voyage Lonely Planet. Petit format\npas de plan détachable','2018-05-28',1,29,'2.jpeg',12754);
INSERT INTO `articles` VALUES (3,'Jeu Harry Potter','Harry Potter et la coupe de feu. Jeu Nintendo Gamecube. Complet,\ntrès bon état','2018-05-28',1,65,'3.jpeg',3689);
INSERT INTO `articles` VALUES (4,'Peinture Cheval','Tableau numéro d’art cheval. Fait main','2018-05-28',1,7,'4.jpeg',28456);
INSERT INTO `articles` VALUES (5,'Cluedo Games of Thrones','Jeu de société Cluedo Games of Thrones en très bon état. nous n’y avons joué que quelques fois, et j’ai entre temps eu le coup de cœur pour le Cluedo Harry Potter.\nje me sépare donc de celui ci pour acheter l’autre version :)','2018-05-28',1,8,'5.jpeg',7823);
INSERT INTO `articles` VALUES (6,'Jeu de boules','Mini boules de pétanque. Boîte contenant 6 boules de pétanque un cochonnet et une ficelle pour mesurer.\nEn bon état.','2018-05-28',1,8,'6.jpeg',19342);
INSERT INTO `articles` VALUES (7,'Livre','La déclaration de Gemma Malley. Lu une fois. Très bon état','2018-05-28',3,9,'7.jpeg',1057);
INSERT INTO `articles` VALUES (8,'Puzzle Harry Potter','Donne puzzle 1000 pièces Fantastic Beast - Neuf, dans son emballage d’origine','2018-05-28',1,14,'8.jpeg',31265);
INSERT INTO `articles` VALUES (9,'Cadre New York','Donne cadre en toile plastifiée, Taxis New York 100 × 50. J\'en veux plus ','2018-05-28',1,9,'9.jpeg',5491);
INSERT INTO `articles` VALUES (10,'Calculatrice','Calculatrice Casio, je donne car erreur de modèle.','2018-05-28',1,11,'10.jpeg',24378);
INSERT INTO `articles` VALUES (11,'Djembé','Djembé en bois peu servi.\n','2018-05-28',3,15,'11.jpeg',16743);
INSERT INTO `articles` VALUES (12,'Pull de noel','pull de noel Coca Cola thème du ski, taille xs chez les hommes donc je dirais un s chez les femmes. Jamais porté car trop petit !','2018-05-28',1,13,'12.jpeg',9126);
INSERT INTO `articles` VALUES (13,'Taie d\'oreiller','Je donne cette belle taie d’oreiller, de chez h&m. Très bon état. Dimensions : 47x47cm','2018-05-28',1,13,'13.jpeg',22587);
INSERT INTO `articles` VALUES (14,'Beau Livre','The Grand Tour des éditions taschen. Dans sa boîte cartonnée ! Attention grand format : 41×30×7 - 8 kilos.','2018-05-28',1,17,'14.jpeg',4219);
INSERT INTO `articles` VALUES (15,'Mules Minelli','jamais portées. elles me sont trop petites. elles sont étroites','2018-05-28',1,15,'15.jpeg',35921);
INSERT INTO `articles` VALUES (16,'Bougie','Bougie Bath&Body Works. 3 Mèches Pure White Cotton. Sent la lessive','2018-05-28',1,16,'16.jpeg',8764);
INSERT INTO `articles` VALUES (17,'Figurines Harry Potter','minifigures harry potter de dumbledore avec le sachet et le collecteur au niveau des baguettes il y a le lien pour les attacher','2018-05-28',1,17,'17.jpeg',27135);
INSERT INTO `articles` VALUES (18,'Peluche R2D2','Presque jamais utilisé.','2018-05-28',1,18,'18.jpeg',14682);
INSERT INTO `articles` VALUES (19,'Carte pokemon','état passable. Carte Lugia','2018-05-28',1,23,'19.jpeg',2947);
INSERT INTO `articles` VALUES (20,'Moules Muffin','9 moules à muffins','2018-05-28',3,21,'20.jpeg',33518);
INSERT INTO `articles` VALUES (21,'Meuble','beau meuble en fer forgé','2018-05-28',1,22,'21.jpeg',6275);
INSERT INTO `articles` VALUES (22,'Montre femme','Montre pour femme avec un bracelet doré réglable selon votre poignet. Elle possède un très jolie cadrant en marbre noir et blanc. Cette dernière est toute fois assez grosse. Jamais portée.','2018-05-28',1,22,'22.jpeg',18934);
INSERT INTO `articles` VALUES (23,'Produit beauté','Eau micellaire démaquillante Yves Rocher. Neuf jamais ouvert','2018-05-28',1,24,'23.jpeg',11467);
INSERT INTO `articles` VALUES (24,'Sac noir','Petite sacoche noire simple neuve. Jamais portée','2018-05-28',1,24,'24.jpeg',29823);
INSERT INTO `articles` VALUES (25,'Boite airpods','J\'ai cassé un ecouteur il me reste juste la boite alors je la donne','2018-05-28',1,26,'25.jpeg',15376);
INSERT INTO `articles` VALUES (26,'VHS Destination Finale','Cassette vidéo horreur bon état VHS. Marche toujours','2018-05-28',1,27,'26.jpeg',3142);
INSERT INTO `articles` VALUES (27,'Porte-clés','Porte-clés fétiche arumbaya vu dans une BD Tintin','2018-05-28',1,27,'27.jpeg',25917);
INSERT INTO `articles` VALUES (28,'Manteau Desigual','Neuf. Taille 42 mais taille un peu petit. Correspond à un S. Parfait pour une prof d\'espagnol','2018-05-28',1,28,'28.jpeg',10483);
INSERT INTO `articles` VALUES (29,'Cintres','Cintres en aluminium, parfaits pour les pantalons et les jupes. J\'en ai plus de 80 si vous en voulez plus.','2018-05-28',1,30,'29.jpeg',32761);
INSERT INTO `articles` VALUES (30,'Chaise de bureau','chaise de bureau rose. pour enfant','2018-05-28',1,30,'30.jpeg',7295);
INSERT INTO `articles` VALUES (31,'Tapis enfant','Tapis d\'épaisseur moquette. Cadre cousu. Dessous caoutchou. Longueur : 1,20m. Largeur : 80cm','2018-05-28',1,33,'31.jpeg',30438);
/*!40000 ALTER TABLE `articles` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-04-30 11:23:32
