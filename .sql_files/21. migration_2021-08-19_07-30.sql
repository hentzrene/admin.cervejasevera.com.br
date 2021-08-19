UPDATE
  `modules_fields_types`
SET
  `name` = 'Data e tempo',
  `key` = 'datetime'
WHERE
  `id` = 5;

INSERT INTO
  `modules_fields_types` (`name`, `key`, `sql_type`)
VALUES
  ('Data', 'date', 'DATE');
