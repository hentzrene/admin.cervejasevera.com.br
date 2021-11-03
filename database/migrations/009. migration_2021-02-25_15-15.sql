INSERT INTO `modules_fields_types` (`id`, `name`, `key`, `sql_type`, `active`) VALUES
  (15, 'Número', 'number', 'INT', 1),
  (16, 'Preço', 'price', 'DECIMAL(10,2)', 1);

UPDATE `modules_fields_types` SET `active`='1' WHERE `id`=11;