/*!40000 ALTER TABLE `modules_fields` DISABLE KEYS */;
INSERT INTO `modules_fields` (`id`, `modules_id`, `name`, `key`, `unique`, `private`, `modules_fields_types_id`, `options`) VALUES
	(1, 1, 'Logo', 'img', 0, 0, 8, '{}'),
	(2, 1, 'Compartilhamento', 'share', 0, 0, 8, '{}'),
	(3, 1, 'Ícone', 'icon', 0, 0, 8, '{}'),
	(4, 1, 'Ícone Mascarável', 'mask_icon', 0, 0, 8, '{}'),
	(5, 1, 'Nome', 'name', 0, 0, 1, '{}'),
	(6, 1, 'Nome Curto', 'short_name', 0, 0, 1, '{}'),
	(7, 1, 'Descrição', 'description', 0, 0, 6, '{}'),
	(8, 1, 'Palavras Chave', 'keywords', 0, 0, 6, '{}');
/*!40000 ALTER TABLE `modules_fields` ENABLE KEYS */;