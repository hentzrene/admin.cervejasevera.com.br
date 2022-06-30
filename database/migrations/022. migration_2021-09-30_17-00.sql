UPDATE
  `configurations`
SET
  `data` = JSON_OBJECT(
    'head_after_begin',
    CONCAT(
      JSON_UNQUOTE(JSON_EXTRACT(data, "$.ads")),
      JSON_UNQUOTE(JSON_EXTRACT(data, "$.analytics")),
      JSON_UNQUOTE(JSON_EXTRACT(data, "$.facebookPixel"))
    ),
    'head_before_end',
    '',
    'body_after_begin',
    '',
    'body_before_end',
    ''
  )
WHERE
  `id` = 2;
