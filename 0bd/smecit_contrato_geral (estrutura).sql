/*
SQLyog Community v13.1.9 (64 bit)
MySQL - 10.4.20-MariaDB : Database - smecit_contrato_geral
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`smecit_contrato_geral` /*!40100 DEFAULT CHARACTER SET latin1 COLLATE latin1_general_ci */;

USE `smecit_contrato_geral`;

/*Table structure for table `acl_privileges` */

DROP TABLE IF EXISTS `acl_privileges`;

CREATE TABLE `acl_privileges` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) NOT NULL,
  `resource_id` int(11) NOT NULL,
  `nome` varchar(45) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `fk_acl_privileges_acl_roles1` (`role_id`) USING BTREE,
  KEY `fk_acl_privileges_acl_resources1` (`resource_id`) USING BTREE,
  CONSTRAINT `fk_acl_privileges_acl_resources1` FOREIGN KEY (`resource_id`) REFERENCES `acl_resources` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `fk_acl_privileges_acl_roles1` FOREIGN KEY (`role_id`) REFERENCES `acl_roles` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

/*Table structure for table `acl_resources` */

DROP TABLE IF EXISTS `acl_resources`;

CREATE TABLE `acl_resources` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(45) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

/*Table structure for table `acl_roles` */

DROP TABLE IF EXISTS `acl_roles`;

CREATE TABLE `acl_roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) DEFAULT NULL,
  `nome` varchar(45) NOT NULL,
  `is_admin` tinyint(1) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `fk_acl_roles_acl_roles` (`parent_id`) USING BTREE,
  CONSTRAINT `fk_acl_roles_acl_roles` FOREIGN KEY (`parent_id`) REFERENCES `acl_roles` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

/*Table structure for table `address_searchs` */

DROP TABLE IF EXISTS `address_searchs`;

CREATE TABLE `address_searchs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `city_id` int(10) unsigned NOT NULL,
  `district_id` int(10) unsigned DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `postal_code` varchar(15) DEFAULT NULL,
  `latitude` varchar(20) DEFAULT NULL,
  `longitude` varchar(20) DEFAULT NULL,
  `ddd` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `fk_adrress_searchs_cities1_idx` (`city_id`) USING BTREE,
  KEY `fk_adress_searchs_district` (`district_id`) USING BTREE,
  CONSTRAINT `fk_adress_searchs_district` FOREIGN KEY (`district_id`) REFERENCES `districts` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_adrress_searchs_cities1` FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

/*Table structure for table `cities` */

DROP TABLE IF EXISTS `cities`;

CREATE TABLE `cities` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `state_id` int(10) unsigned NOT NULL,
  `name` varchar(95) NOT NULL,
  `slug` varchar(95) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `fk_cities_states_idx` (`state_id`) USING BTREE,
  CONSTRAINT `fk_cities_states` FOREIGN KEY (`state_id`) REFERENCES `states` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

/*Table structure for table `districts` */

DROP TABLE IF EXISTS `districts`;

CREATE TABLE `districts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `city_id` int(10) unsigned NOT NULL,
  `name` varchar(95) NOT NULL,
  `slug` varchar(95) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `fk_districts_city_idx` (`city_id`) USING BTREE,
  CONSTRAINT `fk_districts_city` FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

/*Table structure for table `logsystem` */

DROP TABLE IF EXISTS `logsystem`;

CREATE TABLE `logsystem` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `usuario` varbinary(6) NOT NULL,
  `acao` varchar(20) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `datahora` datetime NOT NULL DEFAULT current_timestamp(),
  `ipa` tinyint(3) unsigned DEFAULT NULL,
  `ipb` tinyint(3) unsigned DEFAULT NULL,
  `ipc` tinyint(3) unsigned DEFAULT NULL,
  `ipd` tinyint(3) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `usuario_registro` (`usuario`) USING BTREE,
  CONSTRAINT `usuario_registro` FOREIGN KEY (`usuario`) REFERENCES `usuario` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

/*Table structure for table `states` */

DROP TABLE IF EXISTS `states`;

CREATE TABLE `states` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(95) NOT NULL,
  `initials` varchar(10) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

/*Table structure for table `usuario` */

DROP TABLE IF EXISTS `usuario`;

CREATE TABLE `usuario` (
  `id` varbinary(6) NOT NULL,
  `nome` varchar(50) DEFAULT NULL,
  `snome` varchar(50) DEFAULT NULL,
  `nomeExibicao` varchar(30) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `senha` varbinary(255) DEFAULT NULL,
  `salt` varchar(255) NOT NULL,
  `activate` tinyint(1) DEFAULT NULL,
  `activationKey` varchar(255) NOT NULL,
  `updatedAt` datetime NOT NULL,
  `createdAt` datetime NOT NULL,
  `exibir` tinyint(1) DEFAULT 0,
  `cpf` bigint(20) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

/*Table structure for table `usuario_role` */

DROP TABLE IF EXISTS `usuario_role`;

CREATE TABLE `usuario_role` (
  `usuario_id` varbinary(6) NOT NULL,
  `role_id` int(11) NOT NULL,
  PRIMARY KEY (`usuario_id`,`role_id`) USING BTREE,
  KEY `roles_usuario` (`usuario_id`) USING BTREE,
  KEY `usuario_roles` (`role_id`) USING BTREE,
  CONSTRAINT `role_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `usuario_role` FOREIGN KEY (`role_id`) REFERENCES `acl_roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

/*Table structure for table `usuariodados` */

DROP TABLE IF EXISTS `usuariodados`;

CREATE TABLE `usuariodados` (
  `usuario` varbinary(6) NOT NULL,
  `datanasci` date DEFAULT NULL,
  `sexo` tinyint(3) unsigned DEFAULT NULL,
  `exibir` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`usuario`) USING BTREE,
  CONSTRAINT `usuario_dados` FOREIGN KEY (`usuario`) REFERENCES `usuario` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

/*Table structure for table `usuarioendereco` */

DROP TABLE IF EXISTS `usuarioendereco`;

CREATE TABLE `usuarioendereco` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `usuario` varbinary(6) NOT NULL,
  `endereco` int(10) unsigned NOT NULL,
  `numero` decimal(10,0) DEFAULT NULL,
  `complemento` varchar(100) DEFAULT NULL,
  `exibir` tinyint(1) DEFAULT 0,
  `referencia` varchar(50) DEFAULT NULL,
  `nome` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `endereco` (`endereco`) USING BTREE,
  KEY `endereco_usuario` (`usuario`) USING BTREE,
  CONSTRAINT `endereco` FOREIGN KEY (`endereco`) REFERENCES `address_searchs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `endereco_usuario` FOREIGN KEY (`usuario`) REFERENCES `usuario` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

/*Table structure for table `usuariosocial` */

DROP TABLE IF EXISTS `usuariosocial`;

CREATE TABLE `usuariosocial` (
  `usuario` varbinary(6) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `oauth_uid` varchar(200) NOT NULL,
  `oauth_provider` varchar(6) NOT NULL,
  `oauth_profile` varchar(200) DEFAULT NULL,
  `exibir` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`usuario`,`oauth_uid`) USING BTREE,
  KEY `usuario_social` (`usuario`) USING BTREE,
  CONSTRAINT `usuario_social` FOREIGN KEY (`usuario`) REFERENCES `usuario` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

/*Table structure for table `usuariotelefone` */

DROP TABLE IF EXISTS `usuariotelefone`;

CREATE TABLE `usuariotelefone` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `usuario` varbinary(6) NOT NULL,
  `movel` tinyint(1) NOT NULL DEFAULT 0,
  `telefone` varchar(30) NOT NULL,
  `exibir` tinyint(1) DEFAULT 0,
  `ewhatsapp` tinyint(1) DEFAULT 0,
  `etelegram` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `telefone` (`id`) USING BTREE,
  KEY `usuario` (`usuario`) USING BTREE,
  CONSTRAINT `usuario` FOREIGN KEY (`usuario`) REFERENCES `usuario` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

/* Function  structure for function  `idGera` */

/*!50003 DROP FUNCTION IF EXISTS `idGera` */;
DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` FUNCTION `idGera`(in_strlen INT) RETURNS varbinary(500)
    DETERMINISTIC
BEGIN
SET @var:='';
WHILE(in_strlen>0) DO
SET @var:=CONCAT(@var,ELT(1+FLOOR(RAND() * 64), 'q','w','e','r','t','y','u','i','o','p','a','s','d','f','g','h','j','k','l','z','x','c','v','b','n','m','_','1','4','7','5','9','6','3','0','8','2','-','M','N','B','V','C','X','Z','L','K','J','H','G','F','D','S','A','P','O','I','U','Y','T','R','E','W','Q'));
SET in_strlen:=in_strlen-1;
END WHILE;
RETURN @var;
END */$$
DELIMITER ;

/* Function  structure for function  `usuarioID` */

/*!50003 DROP FUNCTION IF EXISTS `usuarioID` */;
DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` FUNCTION `usuarioID`() RETURNS varbinary(6)
    DETERMINISTIC
BEGIN
		SET @valor:=1;
		WHILE(@valor>0) DO
			SET @id:=idGera(6);
			SET @valor:= (SELECT COUNT(id) FROM usuario WHERE id=@id);
		END WHILE;
		RETURN @id;
	END */$$
DELIMITER ;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
