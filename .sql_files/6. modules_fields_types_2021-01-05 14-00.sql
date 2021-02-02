/*!40000 ALTER TABLE `modules_fields_types` DISABLE KEYS */;
INSERT INTO `modules_fields_types` (`id`, `name`, `key`, `sql_type`, `active`) VALUES
	(1, 'Texto Pequeno', 'tinytext', 'VARCHAR(255)', 1),
	(2, 'E-mail', 'email', 'VARCHAR(255)', 0),
	(3, 'Senha', 'password', 'VARCHAR(255)', 0),
	(4, 'Categoria', 'category', 'INT', 1),
	(5, 'Data', 'date', 'DATETIME', 1),
	(6, 'Texto Médio', 'mediumtext', 'VARCHAR(300)', 1),
	(7, 'Texto Grande', 'bigtext', 'TEXT', 1),
	(8, 'Image', 'imagefile', 'VARCHAR(255)', 1),
	(9, 'Arquivo', 'file', 'VARCHAR(255)', 1),
	(10, 'Subcategoria', 'subcategory', 'INT', 0),
	(11, 'URL', 'url', 'VARCHAR(255)', 0),
	(12, 'Sim/Não', 'switch', 'INT(1)', 1);
/*!40000 ALTER TABLE `modules_fields_types` ENABLE KEYS */;