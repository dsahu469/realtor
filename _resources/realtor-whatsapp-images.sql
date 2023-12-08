/*
SQLyog Ultimate v12.09 (64 bit)
MySQL - 10.4.19-MariaDB : Database - realtor
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_type` enum('REALTOR','USER') NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone_no` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL,
  `token` varchar(255) DEFAULT NULL,
  `interest` varchar(255) NOT NULL,
  `brokerage` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `addr_lat` varchar(20) NOT NULL DEFAULT '0',
  `addr_lon` varchar(20) NOT NULL DEFAULT '0',
  `cur_address` text NOT NULL,
  `cur_lat` varchar(20) NOT NULL DEFAULT '0',
  `cur_lon` varchar(20) NOT NULL DEFAULT '0',
  `is_verified` enum('Y','N') NOT NULL DEFAULT 'N',
  `is_active` enum('Y','N') NOT NULL DEFAULT 'N',
  `is_delete` enum('Y','N') NOT NULL DEFAULT 'N',
  `cr_date` bigint(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `users` */

insert  into `users`(`id`,`user_type`,`name`,`email`,`phone_no`,`password`,`token`,`interest`,`brokerage`,`address`,`addr_lat`,`addr_lon`,`cur_address`,`cur_lat`,`cur_lon`,`is_verified`,`is_active`,`is_delete`,`cr_date`) values (1,'REALTOR','Dinesh','dinesh1@yopmail.com','7063873913','$2y$10$9QqJcN2jJUD0ljv7TA46s.Gy2Dhsn.mDSvBb3dZZtAf6vYueO4y0q',NULL,'1234567890','ABCDEF','Contai, West Bengal, India - 721401','21.916700','87.514862','','0','0','N','Y','N',1701885890);

/*Table structure for table `whatsapp` */

DROP TABLE IF EXISTS `whatsapp`;

CREATE TABLE `whatsapp` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) DEFAULT 0,
  `date` date DEFAULT NULL,
  `message` text DEFAULT NULL,
  `is_active` enum('Y','N') DEFAULT 'N',
  `is_delete` enum('Y','N') DEFAULT 'N',
  `cr_date` bigint(11) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `whatsapp` */

/*Table structure for table `whatsapp_images` */

DROP TABLE IF EXISTS `whatsapp_images`;

CREATE TABLE `whatsapp_images` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `whatsapp_id` bigint(20) DEFAULT 0,
  `file_name` varchar(255) DEFAULT NULL,
  `is_delete` enum('Y','N') DEFAULT 'N',
  `cr_date` bigint(11) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `whatsapp_images` */

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
