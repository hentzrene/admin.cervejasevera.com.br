UPDATE `modules_fields_types` SET `default_options`=NULL WHERE `id`=19;
UPDATE `modules_fields` SET `options`='{}' WHERE `modules_fields_types_id`=19;

INSERT INTO `modules_fields_types` (
  `id`,
  `name`,
  `key`,
  `sql_type`
)
VALUES (
  20,
  'Coleção com chave',
  'collectionWithKey',
  'JSON'
);
