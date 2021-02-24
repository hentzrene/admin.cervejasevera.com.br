<?php

use Module\View\Item\Model as ItemModel;

header("Content-type: application/json");
define('BASE', $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST']);

$informations = ItemModel::get('informations');

$manifest = [
  "short_name" => $informations->short_name,
  "name" => $informations->name,
  "icons" => [
    [
      "src" => $informations->icon . '?resize=1&w=192&h=192',
      "sizes" => "192x192",
      "type" => "image/png"
    ],
    [
      "src" => $informations->icon . '?resize=1&w=512&h=512',
      "sizes" => "512x512",
      "type" => "image/png"
    ],
    [
      "src" => $informations->mask_icon . '?resize=1&w=512&h=512',
      "sizes" => "512x512",
      "type" => "image/png",
      "purpose" => "maskable"
    ]
  ],
  "start_url" => ".",
  "background_color" => "#ffffff",
  "display" => "standalone",
  "theme_color" => "#000000"
];

echo json_encode($manifest);
