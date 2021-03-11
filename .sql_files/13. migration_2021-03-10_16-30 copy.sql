ALTER TABLE `modules_fields_types`
	ADD COLUMN `default_options` JSON NULL DEFAULT NULL AFTER `sql_type`;