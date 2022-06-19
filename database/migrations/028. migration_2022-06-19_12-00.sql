CREATE TABLE modules_views_permissions (
	`id` INT NOT NULL AUTO_INCREMENT,
	`modules_views_id` INT NOT NULL,
	`title` VARCHAR(255) NOT NULL,
	PRIMARY KEY(`id`),
	FOREIGN KEY(`modules_views_id`) REFERENCES modules_views(`id`) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `modules_views_permissions` (`modules_views_id`, `title`) VALUES (1, 'Gerenciar');
INSERT INTO `modules_views_permissions` (`modules_views_id`, `title`) VALUES (2, 'Gerenciar');
INSERT INTO `modules_views_permissions` (`modules_views_id`, `title`) VALUES (3, 'Gerenciar');
INSERT INTO `modules_views_permissions` (`modules_views_id`, `title`) VALUES (4, 'Gerenciar');

ALTER TABLE `accounts_permissions`
	ADD COLUMN `modules_views_permissions_id` INT NOT NULL AFTER `modules_id`,
	DROP INDEX `accounts_id_modules_id`,
	ADD UNIQUE INDEX `accounts_id_modules_id` (`accounts_id`, `modules_id`, `modules_views_permissions_id`) USING BTREE;

UPDATE accounts_permissions
SET `modules_views_permissions_id` = (
	SELECT `id` FROM modules_views_permissions WHERE `modules_views_id` = (
		SELECT `modules_views_id` FROM modules WHERE `id` = accounts_permissions.`modules_id`
	)
);

ALTER TABLE `accounts_permissions`
	ADD CONSTRAINT `accounts_permissions_modules_views_permissions_id` FOREIGN KEY (`modules_views_permissions_id`) REFERENCES `modules_views_permissions` (`id`) ON UPDATE CASCADE ON DELETE CASCADE;
