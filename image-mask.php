<?php

define('APP', $_SERVER['REDIRECT_APP']);

require __DIR__ . '/server/Resource/autoload.php';

use Intervention\Image\ImageManager as Img;

define('EXIF_IMAGE_TYPES_ALLOWEDS', [
  '1' => 'image/gif',
  '2' => 'image/jpeg',
  '3' => 'image/png',
  '6' => 'image/bmp',
  '17' => 'image/ico'
]);

function setHeaderContentType(string $filePath): void
{
  $contentType = EXIF_IMAGE_TYPES_ALLOWEDS[exif_imagetype($filePath)] ?? null;

  if ($contentType === null) {
    throw new Exception('Unable to determine content type of file.');
  }

  header("Content-type: $contentType");
}

$systemRoot = __DIR__;
$file = $_SERVER['REDIRECT_URL'];
$dir = dirname($file);
$basename = basename($file);


if (!file_exists($systemRoot . $file)) {
  http_response_code(404);
  die();
}

$newDir = "{$systemRoot}{$dir}/mask";

if (!is_dir($newDir)) {
  mkdir($newDir, 0755);
}

if (file_exists("$newDir/$basename")) {
  unlink("$newDir/$basename");
};

$manager = new Img(array('driver' => 'gd'));
$img = $manager->make($systemRoot . $file);

$mask = $manager->make($systemRoot . '/img/water-mark.png');
$mask->resize($img->width() * .25, null, function ($constraint) {
  $constraint->aspectRatio();
});

$img->insert($mask, 'center');

$img->save("$newDir/$basename");

setHeaderContentType("$newDir/$basename");
readfile("$newDir/$basename");
