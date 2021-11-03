INSERT INTO `modules_fields_types` (`id`, `name`, `key`, `sql_type`, `active`) VALUES (17, 'Tags', 'tags', 'JSON', 1);

CREATE TABLE IF NOT EXISTS `tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `modules_id` int(11) NOT NULL,
  `modules_fields_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `tags_modules_fields_id` (`modules_fields_id`),
  KEY `tags_modules_id` (`modules_id`),
  CONSTRAINT `tags_modules_fields_id` FOREIGN KEY (`modules_fields_id`) REFERENCES `modules_fields` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tags_modules_id` FOREIGN KEY (`modules_id`) REFERENCES `modules` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

