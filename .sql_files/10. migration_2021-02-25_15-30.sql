ALTER TABLE `categories`
	ADD COLUMN `modules_id` INT NOT NULL AFTER `id`,
	ADD CONSTRAINT `categories_modules_id` FOREIGN KEY (`modules_id`) REFERENCES `modules` (`id`) ON UPDATE CASCADE ON DELETE CASCADE;