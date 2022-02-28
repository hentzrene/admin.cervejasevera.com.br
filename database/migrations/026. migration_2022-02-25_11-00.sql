CREATE TABLE `modules_submenus` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `modules_menu_id` INT NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`modules_menu_id`) REFERENCES `modules_menu` (`id`) ON UPDATE CASCADE ON DELETE CASCADE
) COLLATE = 'utf8_general_ci' ENGINE = InnoDB;

ALTER TABLE `modules`
	ADD COLUMN `modules_submenus_id` INT(11) NULL DEFAULT NULL AFTER `modules_menu_id`,
	ADD CONSTRAINT `modules_modules_submenus_id` FOREIGN KEY (`modules_submenus_id`) REFERENCES `modules_submenus` (`id`) ON UPDATE SET NULL ON DELETE SET NULL;

DROP VIEW IF EXISTS `vw_modules`;

CREATE VIEW `vw_modules` AS
SELECT
	`modules`.`id` AS `id`,
	`modules`.`name` AS `name`,
	`modules`.`key` AS `key`,
	`modules`.`icon` AS `icon`,
	`modules`.`view_options` AS `view_options`,
	`modules_views`.`id` AS `view_id`,
	`modules_views`.`name` AS `view_name`,
	`modules_views`.`key` AS `view_key`,
	`modules_menu`.`id` AS `menu_id`,
	`modules_menu`.`title` AS `menu_title`,
	`modules_submenus`.`id` AS `submenu_id`,
	`modules_submenus`.`title` AS `submenu_title`,
	`modules`.`removable` AS `removable`
FROM
	`modules`
	JOIN `modules_views` ON `modules_views`.`id` = `modules`.`modules_views_id`
	LEFT JOIN `modules_menu` ON `modules_menu`.`id` = `modules`.`modules_menu_id`
	LEFT JOIN `modules_submenus` ON `modules_submenus`.`id` = `modules`.`modules_submenus_id`
WHERE
	`modules`.`active` = 1;
