CREATE TABLE `modules_menu` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(255) NOT NULL UNIQUE,
  PRIMARY KEY(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `modules`
	ADD COLUMN `modules_menu_id` INT NULL DEFAULT NULL AFTER `icon`,
	ADD CONSTRAINT `modules_modules_menu_id` FOREIGN KEY (`modules_menu_id`) REFERENCES `modules_menu` (`id`) ON UPDATE SET NULL ON DELETE SET NULL;
