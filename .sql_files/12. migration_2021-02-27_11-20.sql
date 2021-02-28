DROP VIEW vw_categories;
CREATE VIEW vw_categories AS SELECT
  `categories`.`id` AS `id`,
  `categories`.`title` AS `title`,
  `categories`.`modules_fields_id` AS `modules_fields_id`,
  `modules_fields`.`key` AS `modules_fields_key`,
  `modules_fields`.`modules_id` AS `modules_id`
FROM (
  `categories` JOIN `modules_fields` ON(
    (`categories`.`modules_fields_id` = `modules_fields`.`id`)
  )
);

ALTER TABLE `categories`
	DROP COLUMN `modules_id`,
	DROP FOREIGN KEY `categories_modules_id`;

ALTER TABLE `tags`
	DROP COLUMN `modules_id`,
	DROP FOREIGN KEY `tags_modules_id`;

CREATE VIEW vw_tags AS SELECT
  `tags`.`id` AS `id`,
  `tags`.`title` AS `title`,
  `tags`.`modules_fields_id` AS `modules_fields_id`,
  `modules_fields`.`key` AS `modules_fields_key`,
  `modules_fields`.`modules_id` AS `modules_id`
FROM (
  `tags` JOIN `modules_fields` ON(
    (`tags`.`modules_fields_id` = `modules_fields`.`id`)
  )
);


