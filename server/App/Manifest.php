<?php

use Module\Field\ImageFile\Model as ImageFileModel;
use Module\View\Item\Model as ItemModel;

header("Content-type: application/json");
define('BASE', $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST']);

$informations = ItemModel::get('informations');

$icon = '';
if ($informations->icon) {
  $icon = ImageFileModel::getItem($informations->icon)->path;
}

$maskIcon = '';
if ($informations->mask_icon) {
  $maskIcon = ImageFileModel::getItem($informations->mask_icon)->path;
}

$manifest = [
  "short_name" => $informations->short_name,
  "name" => $informations->name,
  "icons" => [
    [
      "src" => $icon . '?resize=1&w=192&h=192',
      "sizes" => "192x192",
      "type" => "image/png"
    ],
    [
      "src" => $icon . '?resize=1&w=512&h=512',
      "sizes" => "512x512",
      "type" => "image/png"
    ],
    [
      "src" => $maskIcon . '?resize=1&w=512&h=512',
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
