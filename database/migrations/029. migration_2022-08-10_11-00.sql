CREATE TABLE `modules_sections_fields` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`modules_id` INT(11) NOT NULL,
	`title` VARCHAR(255) NOT NULL,
	PRIMARY KEY (`id`),
	UNIQUE (`modules_id`, `title`),
	FOREIGN KEY (`modules_id`) REFERENCES `modules` (`id`) ON UPDATE CASCADE ON DELETE CASCADE
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB;

ALTER TABLE `modules_fields`
	ADD COLUMN `modules_sections_fields_id` INT NULL DEFAULT NULL AFTER `options`,
	ADD CONSTRAINT `modules_fields_modules_sections_fields_id` FOREIGN KEY (`modules_sections_fields_id`) REFERENCES `modules_sections_fields` (`id`) ON UPDATE SET NULL ON DELETE SET NULL;

DROP VIEW IF EXISTS `vw_modules_fields`;

CREATE VIEW `vw_modules_fields` AS
SELECT
  `modules_fields`.`id` AS `id`,
  `modules_fields`.`modules_id` AS `modules_id`,
  `modules`.`key` AS `modules_key`,
  `modules_fields`.`name` AS `name`,
  `modules_fields`.`key` AS `key`,
  `modules_fields`.`unique` AS `unique`,
  `modules_fields`.`modules_fields_types_id` AS `type_id`,
  `modules_fields`.`options` AS `options`,
  `modules_sections_fields`.`id` AS `modules_sections_fields_id`,
  `modules_sections_fields`.`title` AS `modules_sections_fields_title`,
  `modules_fields_types`.`key` AS `type_key`,
  `modules_fields_types`.`sql_type` AS `sql_type`
FROM `modules_fields`
  INNER JOIN `modules` ON `modules`.`id` = `modules_fields`.`modules_id`
  INNER JOIN `modules_fields_types` ON `modules_fields`.`modules_fields_types_id` = `modules_fields_types`.`id`
  LEFT JOIN `modules_sections_fields` ON `modules_fields`.`modules_sections_fields_id` = `modules_sections_fields`.`id`
ORDER BY
  `modules_fields`.`id`;
