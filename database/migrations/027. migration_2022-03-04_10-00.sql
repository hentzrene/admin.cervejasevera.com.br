ALTER TABLE
  `modules_fields` DROP COLUMN `private`;

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
  `modules_fields_types`.`key` AS `type_key`,
  `modules_fields_types`.`sql_type` AS `sql_type`
FROM
  (
    (
      `modules_fields`
      JOIN `modules` ON((`modules`.`id` = `modules_fields`.`modules_id`))
    )
    JOIN `modules_fields_types` ON(
      (
        `modules_fields`.`modules_fields_types_id` = `modules_fields_types`.`id`
      )
    )
  )
ORDER BY
  `modules_fields`.`id`;
