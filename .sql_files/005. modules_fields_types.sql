/*!40000 ALTER TABLE `modules_fields_types` DISABLE KEYS */;
INSERT INTO `modules_fields_types` (`id`, `name`, `key`, `sql_type`, `active`) VALUES
	(1, 'Texto Pequeno', 'tinyText', 'VARCHAR(255)', 1),
	(2, 'E-mail', 'email', 'VARCHAR(255)', 0),
	(3, 'Senha', 'password', 'VARCHAR(255)', 0),
	(4, 'Categoria', 'category', 'INT', 1),
	(5, 'Data', 'date', 'DATETIME', 1),
	(6, 'Texto Médio', 'mediumText', 'VARCHAR(300)', 1),
	(7, 'Texto Grande', 'bigText', 'TEXT', 1),
	(8, 'Imagem', 'imageFile', 'VARCHAR(255)', 1),
	(9, 'Arquivo', 'file', 'VARCHAR(255)', 1),
	(10, 'Subcategoria', 'subcategory', 'INT', 1),
	(11, 'URL', 'url', 'VARCHAR(255)', 0),
	(12, 'Sim/Não', 'switchBoolean', 'INT(1)', 1),
	(13, 'Apenas Leitura', 'readOnly', 'VARCHAR(255)', 1);
/*!40000 ALTER TABLE `modules_fields_types` ENABLE KEYS */;