-- DROP PROCEDURE IF EXISTS EXEC_QUERY;
-- DROP PROCEDURE IF EXISTS UPDATE_COLLECTION_WITH_KEYS;
-- DROP PROCEDURE IF EXISTS SAFE_UPDATE_COLLECTION_WITH_KEYS;

-- DELIMITER ;;

-- CREATE PROCEDURE EXEC_QUERY(s varchar(255))
-- BEGIN
--   SET @q = s;
--   PREPARE stmt FROM @q;
--   EXECUTE stmt;
--   DEALLOCATE PREPARE stmt;
-- END;
-- ;;

-- DROP FUNCTION IF EXISTS JSON_OBJECT_TO_ARRAY;

-- CREATE FUNCTION JSON_OBJECT_TO_ARRAY(input JSON)
--   RETURNS JSON
--   BEGIN
--     DECLARE i INT DEFAULT 0;
--     DECLARE current_key VARCHAR(255);
--     DECLARE result JSON DEFAULT JSON_ARRAY();

--     -- Percorrer as chaves do objeto JSON
--     WHILE i < JSON_LENGTH(input) DO
--       SET i = i + 1;
--       SET current_key = JSON_UNQUOTE(JSON_EXTRACT(JSON_KEYS(input), CONCAT('$[', i - 1, ']')));

--       -- Adicionar o par chave-valor como um array JSON
--       SET result = JSON_ARRAY_APPEND(result, '$', JSON_ARRAY(current_key, JSON_EXTRACT(input, CONCAT('$.', current_key))));
--     END WHILE;

--     RETURN result;
--   END;
-- ;;

-- CREATE PROCEDURE UPDATE_COLLECTION_WITH_KEYS()
-- BEGIN
--   DECLARE n INT DEFAULT 0;
--   DECLARE i INT DEFAULT 0;

--   SELECT COUNT(*)
-- 		FROM modules_fields
-- 		INNER JOIN modules
-- 		ON modules_fields.modules_id = modules.id
-- 		WHERE modules_fields.modules_fields_types_id = 20
--     INTO n;

--   SET i=0;

--   WHILE i<n DO
-- 		SELECT @field_key:=modules_fields.key, @module_key:=modules.key
-- 		FROM modules_fields
-- 		INNER JOIN modules
-- 		ON modules_fields.modules_id = modules.id
-- 		WHERE modules_fields.modules_fields_types_id = 20
-- 		LIMIT i,1;

-- 		SET @table_name = CONCAT('mod_', @module_key);

--     CALL EXEC_QUERY(
--       CONCAT(
--         'UPDATE `mod_', @module_key, '`
--         SET `', @field_key, '` = JSON_OBJECT_TO_ARRAY(`', @field_key, '`)'
--       )
--     );

--     SET i = i + 1;
--   END WHILE;
-- END;
-- ;;

-- CREATE PROCEDURE SAFE_UPDATE_COLLECTION_WITH_KEYS()
-- BEGIN
--   DECLARE `_rollback` BOOL DEFAULT 0;
--   DECLARE CONTINUE HANDLER FOR SQLEXCEPTION SET `_rollback` = 1;
--   START TRANSACTION;

--   CALL UPDATE_COLLECTION_WITH_KEYS();

--   IF `_rollback` THEN
--     ROLLBACK;
--   ELSE
--     COMMIT;
--   END IF;
-- END;
-- ;;

-- DELIMITER ;

-- CALL SAFE_UPDATE_COLLECTION_WITH_KEYS();

-- DROP PROCEDURE EXEC_QUERY;
-- DROP PROCEDURE UPDATE_COLLECTION_WITH_KEYS;
-- DROP PROCEDURE SAFE_UPDATE_COLLECTION_WITH_KEYS;
