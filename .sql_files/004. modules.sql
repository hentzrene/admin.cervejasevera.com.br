/*!40000 ALTER TABLE `modules` DISABLE KEYS */;
INSERT INTO `modules` (`id`, `name`, `key`, `icon`, `modules_views_id`, `view_options`, `active`, `removable`) VALUES
	(1, 'Informações', 'informations', 'fas fa-info-circle', 3, NULL, 1, 0);

INSERT INTO `mod_informations` (`createdAt`) VALUES (CURRENT_TIMESTAMP());
/*!40000 ALTER TABLE `modules` ENABLE KEYS */;