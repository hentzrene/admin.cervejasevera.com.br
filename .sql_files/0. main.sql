-- --------------------------------------------------------
-- Versão do servidor:           5.7.32 - MySQL Community Server (GPL)
-- OS do Servidor:               Linux
-- HeidiSQL Versão:              11.0.0.5919
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

CREATE TABLE IF NOT EXISTS `accounts_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `alteredAt` timestamp NULL DEFAULT NULL,
  `createdAt` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `accounts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `accounts_types_id` int(11) NOT NULL DEFAULT '2',
  `alteredAt` timestamp NULL DEFAULT NULL,
  `createdAt` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `accounts_accounts_types_id` (`accounts_types_id`),
  CONSTRAINT `accounts_accounts_types_id` FOREIGN KEY (`accounts_types_id`) REFERENCES `accounts_types` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `modules_views` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `key` varchar(255) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `name` (`name`),
  UNIQUE KEY `key` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `modules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `key` varchar(255) NOT NULL,
  `icon` varchar(255) NOT NULL,
  `modules_views_id` int(11) DEFAULT NULL,
  `view_options` json DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `removable` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `key` (`key`),
  KEY `modules_modules_views_id` (`modules_views_id`),
  CONSTRAINT `modules_modules_views_id` FOREIGN KEY (`modules_views_id`) REFERENCES `modules_views` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `accounts_permissions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `accounts_id` int(11) NOT NULL,
  `modules_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `accounts_id_modules_id` (`accounts_id`,`modules_id`),
  KEY `accounts_permissions_modules_id` (`modules_id`),
  CONSTRAINT `accounts_permissions_accounts_id` FOREIGN KEY (`accounts_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `accounts_permissions_modules_id` FOREIGN KEY (`modules_id`) REFERENCES `modules` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `modules_fields_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `key` varchar(255) NOT NULL,
  `sql_type` varchar(255) NOT NULL,
  `active` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `name` (`name`) USING BTREE,
  UNIQUE KEY `key` (`key`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `modules_fields` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `modules_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `key` varchar(255) NOT NULL,
  `unique` tinyint(1) NOT NULL DEFAULT '0',
  `private` tinyint(1) NOT NULL DEFAULT '0',
  `modules_fields_types_id` int(11) NOT NULL,
  `options` json NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `modules_id_key` (`modules_id`,`key`),
  KEY `modules_fields_modules_fields_types_id` (`modules_fields_types_id`),
  CONSTRAINT `modules_fields_modules_fields_types_id` FOREIGN KEY (`modules_fields_types_id`) REFERENCES `modules_fields_types` (`id`),
  CONSTRAINT `modules_modules_id` FOREIGN KEY (`modules_id`) REFERENCES `modules` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `modules_fields_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `categories_modules_fields_id` (`modules_fields_id`),
  CONSTRAINT `categories_modules_fields_id` FOREIGN KEY (`modules_fields_id`) REFERENCES `modules_fields` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `modules_id` int(11) NOT NULL,
  `modules_fields_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `path` varchar(255) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `files_modules_id` (`modules_id`),
  KEY `files_modules_fields_id` (`modules_fields_id`),
  CONSTRAINT `files_modules_fields_id` FOREIGN KEY (`modules_fields_id`) REFERENCES `modules_fields` (`id`)  ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `files_modules_id` FOREIGN KEY (`modules_id`) REFERENCES `modules` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `modules_id` int(11) NOT NULL,
  `modules_fields_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `path` varchar(255) NOT NULL,
  `title` VARCHAR(255) NULL DEFAULT NULL COLLATE 'utf8_general_ci',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `image_modules_id` (`modules_id`),
  KEY `image_modules_fields_id` (`modules_fields_id`),
  CONSTRAINT `image_modules_fields_id` FOREIGN KEY (`modules_fields_id`) REFERENCES `modules_fields` (`id`)  ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `image_modules_id` FOREIGN KEY (`modules_id`) REFERENCES `modules` (`id`)  ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `sessions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(255) NOT NULL,
  `accounts_id` int(11) NOT NULL,
  `expireAt` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `token` (`token`),
  KEY `accounts_id` (`accounts_id`),
  CONSTRAINT `sessions_accounts_id` FOREIGN KEY (`accounts_id`) REFERENCES `accounts` (`id`) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `subcategories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `modules_fields_id` int(11) NOT NULL,
  `categories_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `subcategories_modules_fields_id` (`modules_fields_id`),
  KEY `categories_id` (`categories_id`),
  CONSTRAINT `subcategories_modules_fields_id` FOREIGN KEY (`modules_fields_id`) REFERENCES `modules_fields` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `subcategories_categories_id` FOREIGN KEY (`categories_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `configurations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `key` varchar(255) NOT NULL,
  `data` json NOT NULL,
  `public` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `key` (`key`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `mod_informations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `createdAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `alteredAt` timestamp NULL DEFAULT NULL,
  `img` varchar(255) DEFAULT NULL,
  `share` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `keywords` varchar(300) DEFAULT NULL,
  `tel` varchar(255) DEFAULT NULL,
  `whatsapp` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `facebook` varchar(255) DEFAULT NULL,
  `instagram` varchar(255) DEFAULT NULL,
  `youtube` varchar(255) DEFAULT NULL,
  `local` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Criando tabela temporária para evitar erros de dependência de VIEW
CREATE TABLE `vw_modules` (
	`id` INT(11) NOT NULL,
	`name` VARCHAR(255) NOT NULL COLLATE 'utf8_general_ci',
	`key` VARCHAR(255) NOT NULL COLLATE 'utf8_general_ci',
	`icon` VARCHAR(255) NOT NULL COLLATE 'utf8_general_ci',
	`view_options` JSON NULL,
	`view_name` VARCHAR(255) NOT NULL COLLATE 'utf8_general_ci',
	`view_key` VARCHAR(255) NOT NULL COLLATE 'utf8_general_ci',
	`removable` TINYINT(1) NOT NULL
) ENGINE=InnoDB;

-- Criando tabela temporária para evitar erros de dependência de VIEW
CREATE TABLE `vw_modules_fields` (
	`id` INT(11) NOT NULL,
	`modules_id` INT(11) NOT NULL,
	`modules_key` VARCHAR(255) NOT NULL COLLATE 'utf8_general_ci',
	`name` VARCHAR(255) NOT NULL COLLATE 'utf8_general_ci',
	`key` VARCHAR(255) NOT NULL COLLATE 'utf8_general_ci',
	`unique` TINYINT(1) NOT NULL,
	`private` TINYINT(1) NOT NULL,
	`type_id` INT(11) NOT NULL,
	`options` JSON NOT NULL,
	`type_key` VARCHAR(255) NOT NULL COLLATE 'utf8_general_ci',
	`sql_type` VARCHAR(255) NOT NULL COLLATE 'utf8_general_ci'
) ENGINE=MyISAM;

-- Criando tabela temporária para evitar erros de dependência de VIEW
CREATE TABLE `vw_categories` (
	`id` INT(11) NOT NULL,
	`modules_fields_id` INT(11) NOT NULL,
	`title` VARCHAR(255) NOT NULL COLLATE 'utf8_general_ci',
	`modules_id` INT(11) NOT NULL
) ENGINE=MyISAM;

-- Removendo tabela temporária e criando a estrutura VIEW final
DROP TABLE IF EXISTS `vw_modules`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `vw_modules` AS select `modules`.`id` AS `id`,`modules`.`name` AS `name`,`modules`.`key` AS `key`,`modules`.`icon` AS `icon`,`modules`.`view_options` AS `view_options`,`modules_views`.`name` AS `view_name`,`modules_views`.`key` AS `view_key`,`modules`.`removable` AS `removable` from (`modules` join `modules_views` on((`modules_views`.`id` = `modules`.`modules_views_id`))) where (`modules`.`active` = 1);

-- Removendo tabela temporária e criando a estrutura VIEW final
DROP TABLE IF EXISTS `vw_modules_fields`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `vw_modules_fields` AS select `modules_fields`.`id` AS `id`,`modules_fields`.`modules_id` AS `modules_id`,`modules`.`key` AS `modules_key`,`modules_fields`.`name` AS `name`,`modules_fields`.`key` AS `key`,`modules_fields`.`unique` AS `unique`,`modules_fields`.`private` AS `private`,`modules_fields`.`modules_fields_types_id` AS `type_id`,`modules_fields`.`options` AS `options`,`modules_fields_types`.`key` AS `type_key`,`modules_fields_types`.`sql_type` AS `sql_type` from ((`modules_fields` join `modules` on((`modules`.`id` = `modules_fields`.`modules_id`))) join `modules_fields_types` on((`modules_fields`.`modules_fields_types_id` = `modules_fields_types`.`id`))) order by `modules_fields`.`id`;

-- Removendo tabela temporária e criando a estrutura VIEW final
DROP TABLE IF EXISTS `vw_categories`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `vw_categories` AS select `categories`.`id` AS `id`,`categories`.`modules_fields_id` AS `modules_fields_id`,`categories`.`title` AS `title`,`modules_fields`.`modules_id` AS `modules_id` from (`categories` join `modules_fields` on((`categories`.`modules_fields_id` = `modules_fields`.`id`)));

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
