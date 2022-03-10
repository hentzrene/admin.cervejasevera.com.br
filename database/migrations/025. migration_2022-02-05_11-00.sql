-- DROP PROCEDURE IF EXISTS EXEC_QUERY;
-- DROP PROCEDURE IF EXISTS UPDATE_IMAGES;
-- DROP PROCEDURE IF EXISTS SAFE_UPDATE_IMAGES;

-- DELIMITER ;;

-- CREATE PROCEDURE EXEC_QUERY(s varchar(255))
-- BEGIN
--   SET @q = s;
--   PREPARE stmt FROM @q;
--   EXECUTE stmt;
--   DEALLOCATE PREPARE stmt;
-- END;
-- ;;

-- CREATE PROCEDURE UPDATE_IMAGES()
-- BEGIN
--   DECLARE n INT DEFAULT 0;
--   DECLARE i INT DEFAULT 0;

--   SELECT COUNT(*)
-- 		FROM modules_fields
-- 		INNER JOIN modules
-- 		ON modules_fields.modules_id = modules.id
-- 		WHERE modules_fields.modules_fields_types_id = 8
--     INTO n;

--   SET i=0;

--   WHILE i<n DO
-- 		SELECT @field_key:=modules_fields.key, @module_key:=modules.key
-- 		FROM modules_fields
-- 		INNER JOIN modules
-- 		ON modules_fields.modules_id = modules.id
-- 		WHERE modules_fields.modules_fields_types_id = 8
-- 		LIMIT i,1;

-- 		SET @table_name = CONCAT('mod_', @module_key);

--     CALL EXEC_QUERY(
--       CONCAT(
--         'ALTER TABLE `mod_', @module_key, '`
--         ADD COLUMN `', @field_key, '_tmp` VARCHAR(255) NULL DEFAULT NULL AFTER `', @field_key, '`'
--       )
--     );

--     CALL EXEC_QUERY(
--       CONCAT(
--         'UPDATE `mod_', @module_key, '` SET `', @field_key, '_tmp` = `', @field_key, '`'
--       )
--     );

--     CALL EXEC_QUERY(
--       CONCAT(
--         'ALTER TABLE `mod_', @module_key, '`
--         CHANGE COLUMN `', @field_key, '` `', @field_key, '` INT NULL DEFAULT NULL COLLATE "utf8_general_ci"'
--       )
--     );

--     CALL EXEC_QUERY(
--       CONCAT(
--         'UPDATE `mod_', @module_key, '` SET `', @field_key, '` = NULL'
--       )
--     );

--     CALL EXEC_QUERY(
--       CONCAT(
--         'ALTER TABLE `mod_', @module_key, '`
--         ADD CONSTRAINT `mod_', @module_key, '_', @field_key, '`
--         FOREIGN KEY (`', @field_key, '`)
--         REFERENCES `images` (`id`)
--         ON DELETE SET NULL
--         ON UPDATE SET NULL'
--       )
--     );

--     CALL EXEC_QUERY(
--       CONCAT(
--         'UPDATE `mod_', @module_key, '`
--         SET `', @field_key, '` = (
--           SELECT `images`.`id`
--           FROM `images`
--           WHERE `images`.`path` = `mod_', @module_key, '`.`', @field_key, '_tmp`
--           LIMIT 1
--         )'
--       )
--     );

--     CALL EXEC_QUERY(
--       CONCAT(
--         'ALTER TABLE `mod_', @module_key, '`
--         DROP COLUMN `', @field_key, '_tmp`'
--       )
--     );

--     SET i = i + 1;
--   END WHILE;
-- END;
-- ;;

-- CREATE PROCEDURE SAFE_UPDATE_IMAGES()
-- BEGIN
--   DECLARE `_rollback` BOOL DEFAULT 0;
--   DECLARE CONTINUE HANDLER FOR SQLEXCEPTION SET `_rollback` = 1;
--   START TRANSACTION;

--   CALL UPDATE_IMAGES();

--   IF `_rollback` THEN
--     ROLLBACK;
--   ELSE
--     COMMIT;
--   END IF;
-- END;
-- ;;

-- DELIMITER ;

-- CALL SAFE_UPDATE_IMAGES();

-- DROP PROCEDURE EXEC_QUERY;
-- DROP PROCEDURE UPDATE_IMAGES;
-- DROP PROCEDURE SAFE_UPDATE_IMAGES;

UPDATE `modules_fields_types`
SET `sql_type`='INT'
WHERE `id`=8;
