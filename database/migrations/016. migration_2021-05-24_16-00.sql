CREATE TABLE `modules_menu` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(255) NOT NULL UNIQUE,
  PRIMARY KEY(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `modules`
	ADD COLUMN `modules_menu_id` INT NULL DEFAULT NULL AFTER `icon`,
	ADD CONSTRAINT `modules_modules_menu_id` FOREIGN KEY (`modules_menu_id`) REFERENCES `modules_menu` (`id`) ON UPDATE SET NULL ON DELETE SET NULL;

-- Criando tabela temporária para evitar erros de dependência de VIEW
CREATE TABLE `vw_modules` (
	`id` INT(11) NOT NULL,
	`name` VARCHAR(255) NOT NULL COLLATE 'utf8_general_ci',
	`key` VARCHAR(255) NOT NULL COLLATE 'utf8_general_ci',
	`icon` VARCHAR(255) NOT NULL COLLATE 'utf8_general_ci',
	`view_options` JSON NULL,
	`view_name` VARCHAR(255) NOT NULL COLLATE 'utf8_general_ci',
	`view_key` VARCHAR(255) NOT NULL COLLATE 'utf8_general_ci',
	`menu_id` INT(11) NULL,
	`menu_title` VARCHAR(255) NULL COLLATE 'utf8_general_ci',
	`removable` TINYINT(1) NOT NULL
) ENGINE=MyISAM;

-- Removendo tabela temporária e criando a estrutura VIEW final
DROP TABLE IF EXISTS `vw_modules`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `vw_modules` AS select `modules`.`id` AS `id`,`modules`.`name` AS `name`,`modules`.`key` AS `key`,`modules`.`icon` AS `icon`,`modules`.`view_options` AS `view_options`,`modules_views`.`name` AS `view_name`,`modules_views`.`key` AS `view_key`,`modules_menu`.`id` AS `menu_id`,`modules_menu`.`title` AS `menu_title`,`modules`.`removable` AS `removable` from ((`modules` join `modules_views` on((`modules_views`.`id` = `modules`.`modules_views_id`))) left join `modules_menu` on((`modules_menu`.`id` = `modules`.`modules_menu_id`))) where (`modules`.`active` = 1);