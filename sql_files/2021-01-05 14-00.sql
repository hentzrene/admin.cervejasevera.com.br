-- --------------------------------------------------------
-- Servidor:                     www.colegiosanpetrus.com.br
-- Versão do servidor:           5.7.32 - MySQL Community Server (GPL)
-- OS do Servidor:               Linux
-- HeidiSQL Versão:              11.0.0.5919
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Copiando estrutura para tabela colegios_2020.accounts
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
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

-- Copiando dados para a tabela colegios_2020.accounts: ~1 rows (aproximadamente)
/*!40000 ALTER TABLE `accounts` DISABLE KEYS */;
INSERT INTO `accounts` (`id`, `name`, `email`, `password`, `accounts_types_id`, `alteredAt`, `createdAt`) VALUES
	(1, 'Admin', 'suporte@mrxweb.com.br', '$2y$10$aUwMl3sIxKxnnyuTlwqz7O.ilOpW4wUZRPYBlI2GaNpbpa6dnHaTm', 1, '2020-12-29 16:24:21', '2020-12-07 20:23:31');
/*!40000 ALTER TABLE `accounts` ENABLE KEYS */;

-- Copiando estrutura para tabela colegios_2020.accounts_permissions
CREATE TABLE IF NOT EXISTS `accounts_permissions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `accounts_id` int(11) NOT NULL,
  `modules_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `accounts_id_modules_id` (`accounts_id`,`modules_id`),
  KEY `accounts_permissions_modules_id` (`modules_id`),
  CONSTRAINT `accounts_permissions_accounts_id` FOREIGN KEY (`accounts_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `accounts_permissions_modules_id` FOREIGN KEY (`modules_id`) REFERENCES `modules` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- Copiando dados para a tabela colegios_2020.accounts_permissions: ~2 rows (aproximadamente)
/*!40000 ALTER TABLE `accounts_permissions` DISABLE KEYS */;
INSERT INTO `accounts_permissions` (`id`, `accounts_id`, `modules_id`) VALUES
	(1, 1, 1),
	(2, 1, 2);
/*!40000 ALTER TABLE `accounts_permissions` ENABLE KEYS */;

-- Copiando estrutura para tabela colegios_2020.accounts_types
CREATE TABLE IF NOT EXISTS `accounts_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `alteredAt` timestamp NULL DEFAULT NULL,
  `createdAt` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- Copiando dados para a tabela colegios_2020.accounts_types: ~2 rows (aproximadamente)
/*!40000 ALTER TABLE `accounts_types` DISABLE KEYS */;
INSERT INTO `accounts_types` (`id`, `name`, `alteredAt`, `createdAt`) VALUES
	(1, 'Administrador', NULL, '2020-11-15 16:24:26'),
	(2, 'Usuário', NULL, '2020-11-15 16:25:02');
/*!40000 ALTER TABLE `accounts_types` ENABLE KEYS */;

-- Copiando estrutura para tabela colegios_2020.categories
CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `modules_id` int(11) NOT NULL,
  `modules_fields_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `categories_modules_id` (`modules_id`),
  KEY `categories_modules_fields_id` (`modules_fields_id`),
  CONSTRAINT `categories_modules_fields_id` FOREIGN KEY (`modules_fields_id`) REFERENCES `modules_fields` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `categories_modules_id` FOREIGN KEY (`modules_id`) REFERENCES `modules` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Copiando dados para a tabela colegios_2020.categories: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;

-- Copiando estrutura para tabela colegios_2020.files
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
  CONSTRAINT `files_modules_fields_id` FOREIGN KEY (`modules_fields_id`) REFERENCES `modules_fields` (`id`),
  CONSTRAINT `files_modules_id` FOREIGN KEY (`modules_id`) REFERENCES `modules` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Copiando dados para a tabela colegios_2020.files: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `files` DISABLE KEYS */;
/*!40000 ALTER TABLE `files` ENABLE KEYS */;

-- Copiando estrutura para tabela colegios_2020.images
CREATE TABLE IF NOT EXISTS `images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `modules_id` int(11) NOT NULL,
  `modules_fields_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `path` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `image_modules_id` (`modules_id`),
  KEY `image_modules_fields_id` (`modules_fields_id`),
  CONSTRAINT `image_modules_fields_id` FOREIGN KEY (`modules_fields_id`) REFERENCES `modules_fields` (`id`),
  CONSTRAINT `image_modules_id` FOREIGN KEY (`modules_id`) REFERENCES `modules` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Copiando dados para a tabela colegios_2020.images: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `images` DISABLE KEYS */;
/*!40000 ALTER TABLE `images` ENABLE KEYS */;

-- Copiando estrutura para tabela colegios_2020.modules
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
  KEY `FK_modules_modules_views` (`modules_views_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- Copiando dados para a tabela colegios_2020.modules: ~2 rows (aproximadamente)
/*!40000 ALTER TABLE `modules` DISABLE KEYS */;
INSERT INTO `modules` (`id`, `name`, `key`, `icon`, `modules_views_id`, `view_options`, `active`, `removable`) VALUES
	(1, 'Contas', 'accounts', 'fas fa-users', 1, NULL, 1, 0),
	(2, 'Módulos', 'modules', 'fas fa-cogs', 1, NULL, 1, 0);
/*!40000 ALTER TABLE `modules` ENABLE KEYS */;

-- Copiando estrutura para tabela colegios_2020.modules_fields
CREATE TABLE IF NOT EXISTS `modules_fields` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `modules_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `key` varchar(255) NOT NULL,
  `unique` tinyint(1) NOT NULL DEFAULT '0',
  `private` tinyint(1) NOT NULL DEFAULT '0',
  `modules_fields_types_id` int(11) NOT NULL,
  `options` json DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `modules_id_key` (`modules_id`,`key`),
  KEY `modules_fields_modules_fields_types_id` (`modules_fields_types_id`),
  CONSTRAINT `modules_fields_modules_fields_types_id` FOREIGN KEY (`modules_fields_types_id`) REFERENCES `modules_fields_types` (`id`),
  CONSTRAINT `modules_modules_id` FOREIGN KEY (`modules_id`) REFERENCES `modules` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Copiando dados para a tabela colegios_2020.modules_fields: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `modules_fields` DISABLE KEYS */;
/*!40000 ALTER TABLE `modules_fields` ENABLE KEYS */;

-- Copiando estrutura para tabela colegios_2020.modules_fields_types
CREATE TABLE IF NOT EXISTS `modules_fields_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `key` varchar(255) NOT NULL,
  `sql_type` varchar(255) NOT NULL,
  `active` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `name` (`name`) USING BTREE,
  UNIQUE KEY `key` (`key`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

-- Copiando dados para a tabela colegios_2020.modules_fields_types: ~13 rows (aproximadamente)
/*!40000 ALTER TABLE `modules_fields_types` DISABLE KEYS */;
INSERT INTO `modules_fields_types` (`id`, `name`, `key`, `sql_type`, `active`) VALUES
	(1, 'Texto Pequeno', 'tinytext', 'VARCHAR(255)', 1),
	(2, 'E-mail', 'email', 'VARCHAR(255)', 0),
	(3, 'Senha', 'password', 'VARCHAR(255)', 0),
	(4, 'Categoria', 'category', 'INT', 1),
	(5, 'Data', 'date', 'DATETIME', 1),
	(6, 'Texto Médio', 'mediumtext', 'VARCHAR(300)', 1),
	(7, 'Texto Grande', 'bigtext', 'TEXT', 1),
	(8, 'Image', 'imagefile', 'VARCHAR(255)', 1),
	(9, 'Arquivo', 'file', 'VARCHAR(255)', 1),
	(10, 'Subcategoria', 'subcategory', 'INT', 0),
	(11, 'URL', 'url', 'VARCHAR(255)', 0),
	(12, 'Secretarias', 'secretariats', 'INT', 1),
	(13, 'Sim/Não', 'switch', 'INT(1)', 1);
/*!40000 ALTER TABLE `modules_fields_types` ENABLE KEYS */;

-- Copiando estrutura para tabela colegios_2020.modules_views
CREATE TABLE IF NOT EXISTS `modules_views` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `key` varchar(255) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `name` (`name`),
  UNIQUE KEY `key` (`key`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- Copiando dados para a tabela colegios_2020.modules_views: ~2 rows (aproximadamente)
/*!40000 ALTER TABLE `modules_views` DISABLE KEYS */;
INSERT INTO `modules_views` (`id`, `name`, `key`) VALUES
	(1, 'Customizado', 'custom'),
	(2, 'Tabela', 'table');
/*!40000 ALTER TABLE `modules_views` ENABLE KEYS */;

-- Copiando estrutura para tabela colegios_2020.sessions
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(255) NOT NULL,
  `accounts_id` int(11) NOT NULL,
  `expireAt` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `token` (`token`),
  KEY `accounts_id` (`accounts_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Copiando dados para a tabela colegios_2020.sessions: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;

-- Copiando estrutura para tabela colegios_2020.subcategories
CREATE TABLE IF NOT EXISTS `subcategories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `categories_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `categories_id` (`categories_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Copiando dados para a tabela colegios_2020.subcategories: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `subcategories` DISABLE KEYS */;
/*!40000 ALTER TABLE `subcategories` ENABLE KEYS */;

-- Copiando estrutura para view colegios_2020.vw_modules
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
) ENGINE=MyISAM;

-- Copiando estrutura para view colegios_2020.vw_modules_fields
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
	`type_key` VARCHAR(255) NOT NULL COLLATE 'utf8_general_ci',
	`sql_type` VARCHAR(255) NOT NULL COLLATE 'utf8_general_ci'
) ENGINE=MyISAM;

-- Copiando estrutura para view colegios_2020.vw_modules
-- Removendo tabela temporária e criando a estrutura VIEW final
DROP TABLE IF EXISTS `vw_modules`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `vw_modules` AS select `modules`.`id` AS `id`,`modules`.`name` AS `name`,`modules`.`key` AS `key`,`modules`.`icon` AS `icon`,`modules`.`view_options` AS `view_options`,`modules_views`.`name` AS `view_name`,`modules_views`.`key` AS `view_key`,`modules`.`removable` AS `removable` from (`modules` join `modules_views` on((`modules_views`.`id` = `modules`.`modules_views_id`))) where (`modules`.`active` = 1);

-- Copiando estrutura para view colegios_2020.vw_modules_fields
-- Removendo tabela temporária e criando a estrutura VIEW final
DROP TABLE IF EXISTS `vw_modules_fields`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `vw_modules_fields` AS select `modules_fields`.`id` AS `id`,`modules_fields`.`modules_id` AS `modules_id`,`modules`.`key` AS `modules_key`,`modules_fields`.`name` AS `name`,`modules_fields`.`key` AS `key`,`modules_fields`.`unique` AS `unique`,`modules_fields`.`private` AS `private`,`modules_fields`.`modules_fields_types_id` AS `type_id`,`modules_fields_types`.`key` AS `type_key`,`modules_fields_types`.`sql_type` AS `sql_type` from ((`modules_fields` join `modules` on((`modules`.`id` = `modules_fields`.`modules_id`))) join `modules_fields_types` on((`modules_fields`.`modules_fields_types_id` = `modules_fields_types`.`id`))) order by `modules_fields`.`id`;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
