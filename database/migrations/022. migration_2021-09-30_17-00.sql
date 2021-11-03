UPDATE
  `configurations`
SET
  `data` = JSON_OBJECT(
    'head_after_begin',
    CONCAT(
      JSON_UNQUOTE(data -> "$.ads"),
      JSON_UNQUOTE(data -> "$.analytics"),
      JSON_UNQUOTE(data -> "$.facebookPixel")
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