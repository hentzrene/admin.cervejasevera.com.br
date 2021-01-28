<?php
if (file_exists(__DIR__ . '/../../share-tags.php')) {
  define('SHARE_TAGS_DEFINEDS', true);
  require __DIR__ . '/../../share-tags.php';

  foreach (SHARE_TAGS as $path => $tags) {
    $router->get($path, function () use ($tags) {
      define('SHARE_TAG_NAME', $tags['name']);
      define('SHARE_TAG_TITLE', $tags['title']);
      define('SHARE_TAG_DESCRIPTION', $tags['description']);
      define('SHARE_TAG_KEYWORDS', $tags['keywords']);
      define('SHARE_TAG_IMAGE', $tags['image']);

      require __DIR__ . '/../Vue.php';
    });
  }
}
