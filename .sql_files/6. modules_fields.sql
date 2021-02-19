/*!40000 ALTER TABLE `modules_fields` DISABLE KEYS */;
INSERT INTO `modules_fields` (`id`, `modules_id`, `name`, `key`, `unique`, `private`, `modules_fields_types_id`, `options`) VALUES
	(1, 1, 'Logo', 'img', 0, 0, 8, '{}'),
	(2, 1, 'Compartilhamento', 'share', 0, 0, 8, '{}'),
	(3, 1, 'Name', 'name', 0, 0, 1, '{}'),
	(4, 1, 'Descrição', 'description', 0, 0, 6, '{}'),
	(5, 1, 'Palavras Chave', 'keywords', 0, 0, 6, '{}');
/*!40000 ALTER TABLE `modules_fields` ENABLE KEYS */;