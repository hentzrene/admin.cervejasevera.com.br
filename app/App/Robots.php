<?php
header("Content-Type: text/plain");
define('BASE', $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST']);
?>
User-agent: *
Disallow: /
Sitemap: <?= BASE ?>/sitemap.xml