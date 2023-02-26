UPDATE
  `modules_fields_types`
SET
  `name` = 'Computado',
  `key` = 'computed'
WHERE
  `id` = 21;


UPDATE
  `modules_fields`
SET
  `options` = JSON_SET(
    `options`,
    '$.pattern',
    CONCAT(
      'url[',
      JSON_UNQUOTE(JSON_EXTRACT(`options`, "$.pattern")),
      ']'
    )
  )
WHERE
  `modules_fields_types_id` = 21;
