INSERT INTO `modules_fields_types` (
    `id`,
    `name`,
    `key`,
    `sql_type`,
    `default_options`
)
VALUES (
  19,
  'Coleção',
  'collection',
  'JSON',
  '{"itemsType":"1"}'
);

ALTER TABLE `modules_fields_types`
  ADD COLUMN `foreign` INT NOT NULL DEFAULT '0' AFTER `active`;

UPDATE `modules_fields_types` SET `foreign`='1' WHERE  `id`=4;
UPDATE `modules_fields_types` SET `foreign`='1' WHERE  `id`=8;
UPDATE `modules_fields_types` SET `foreign`='1' WHERE  `id`=9;
UPDATE `modules_fields_types` SET `foreign`='1' WHERE  `id`=10;
UPDATE `modules_fields_types` SET `foreign`='1' WHERE  `id`=17;