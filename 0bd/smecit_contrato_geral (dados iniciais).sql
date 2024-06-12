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

/*Data for the table `acl_privileges` */

/*Data for the table `acl_resources` */

/*Data for the table `acl_roles` */

insert  into `acl_roles`(`id`,`parent_id`,`nome`,`is_admin`,`created_at`,`updated_at`) values 
(1,NULL,'Super',1,'2017-03-14 22:26:43','2017-03-14 22:26:43'),
(2,NULL,'Administrador',1,'2017-03-14 22:26:43','2017-03-14 22:26:43'),
(3,NULL,'Membro',0,'2017-03-14 22:26:12','2017-03-14 22:26:12');

/*Data for the table `address_searchs` */

/*Data for the table `cities` */

/*Data for the table `districts` */

/*Data for the table `logsystem` */

insert  into `logsystem`(`id`,`usuario`,`acao`,`status`,`datahora`,`ipa`,`ipb`,`ipc`,`ipd`) values 
(1,'000001','Usuario - adicionado','Novo usuário cadastrado - ID: 000001','2022-11-11 00:00:00',1,1,1,1)

/*Data for the table `states` */

/*Data for the table `usuario` */

insert  into `usuario`(`id`,`nome`,`snome`,`nomeExibicao`,`email`,`senha`,`salt`,`activate`,`activationKey`,`updatedAt`,`createdAt`,`exibir`,`cpf`) values 
('000001','SME','CIT','Sistemas SME - CIT','josefelipe@rioeduca.net','DJS9PJS+5YGN6qHCczHj17fsJn4=','YlSYeHzAZAM=',1,'f055296442897f620d244e88711bf20a','2022-11-11 00:00:00','2022-11-11 00:00:00',0,1);

/*Data for the table `usuario_role` */

insert  into `usuario_role`(`usuario_id`,`role_id`) values 
('000001',1);

/*Data for the table `usuariodados` */

/*Data for the table `usuarioendereco` */

/*Data for the table `usuariosocial` */

/*Data for the table `usuariotelefone` */

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
