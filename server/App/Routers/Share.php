<?php

use App\ShareTags;

foreach (ShareTags::app() as $path => $tags) {
  $router->get($path, function () use ($tags) {
    if (is_callable($tags)) $tags = $tags();

    define('SHARE_TAG_NAME', $tags['name'] ? $tags['name'] : ShareTags::$defaultTags->name);
    define('SHARE_TAG_TITLE', $tags['title'] ? $tags['title'] : ShareTags::$defaultTags->title);
    define('SHARE_TAG_DESCRIPTION', $tags['description'] ? $tags['description'] : ShareTags::$defaultTags->description);
    define('SHARE_TAG_KEYWORDS', $tags['keywords'] ? $tags['keywords'] : ShareTags::$defaultTags->keywords);
    define('SHARE_TAG_IMAGE', $tags['image'] ? $tags['image'] : ShareTags::$defaultTags->image);

    require __DIR__ . '/../Vue.php';
  });
}
