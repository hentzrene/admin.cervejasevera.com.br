<?php
require __DIR__ . '/../vendor/autoload.php';

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

$systemRoot = __DIR__ . '/..';
$file = $_SERVER['REDIRECT_URL'];
$dir = dirname($file);
$basename = basename($file);
$height = $_GET['h'] ? (int) $_GET['h'] : null;
$width = $_GET['w'] ? (int) $_GET['w'] : null;


if (!file_exists($systemRoot . $file)) {
  http_response_code(404);
  die();
}

if (!$height && !$width) {
  http_response_code(400);
  die();
}

$newDir = "{$systemRoot}{$dir}/{$width}x{$height}";

if (!is_dir($newDir)) {
  mkdir($newDir, 0755);
}

if (file_exists("$newDir/$basename")) {
  setHeaderContentType("$newDir/$basename");
  readfile("$newDir/$basename");
  die();
};

$manager = new Img(array('driver' => 'gd'));
$make = $manager->make($systemRoot . $file);

$makeWidth = $make->width();
$makeHeight = $make->height();

$newWidth = $width ? $width : (int) (($makeWidth * $height) / $makeHeight);
$newHeight = $height ? $height : (int) (($makeHeight * $width) / $makeWidth);

$make->resize($newWidth, $newHeight);

if ($_SERVER['REMOTE_ADDR'] === '177.155.216.230') {

  $exif = exif_read_data($systemRoot . $file);
  if (!empty($exif['Orientation'])) {
    switch ($exif['Orientation']) {
      case 8:
        $make->rotate(90);
        break;
      case 3:
        $make->rotate(180);
        break;
      case 6:
        $make->rotate(-90);
        break;
    }
  }
}


$make->save("$newDir/$basename");

setHeaderContentType("$newDir/$basename");
readfile("$newDir/$basename");
