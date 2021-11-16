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
	`modules`.`removable` AS `removable`
FROM
	`modules`
	JOIN `modules_views` ON `modules_views`.`id` = `modules`.`modules_views_id`
	LEFT JOIN `modules_menu` ON `modules_menu`.`id` = `modules`.`modules_menu_id`
WHERE
	`modules`.`active` = 1;