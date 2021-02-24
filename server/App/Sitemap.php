<?php
header("Content-type: text/xml");
define('BASE', $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST']);

$urlset = [
  [
    'loc' => '/',
    'lastmod' => NULL
  ]
];

echo '<?xml version="1.0" encoding="UTF-8" ?>';
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
  <?php foreach ($urlset as $url) : ?>
    <url>
      <loc><?= BASE . $url['loc'] ?></loc>
      <?php if ($url['lastmod']) : ?>
        <lastmod><?= $url['lastmod'] ?></lastmod>
      <?php endif; ?>
    </url>
  <?php endforeach; ?>
</urlset>