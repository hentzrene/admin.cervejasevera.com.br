<?php

setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
date_default_timezone_set('America/Cuiaba');
error_reporting(E_ALL & ~E_NOTICE);
ini_set('display_errors', 1);

define('SYSTEM_ROOT', __DIR__ . '/..');
define('ROOT', $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST']);
define('URL', ROOT . $_SERVER['REQUEST_URI']);
define('IP', $_SERVER['REMOTE_ADDR']);

spl_autoload_register(
  function ($class) {
    $file = __DIR__ . '/' . str_replace('\\', '/', $class) . '.php';
    if (file_exists($file)) {
      require_once $file;
    }
  }
);


$cssPath = __DIR__ . "/../css/";
$css = [];
if (is_dir($cssPath)) {
  $cssDir = array_slice(scandir($cssPath), 2);
  foreach ($cssDir as $n => $v) {
    $css[] = "/setup/css/$v";
  }
}

define('STYLES', $css);

$jsPath = __DIR__ . "/../js/";
$js = [];
if (is_dir($jsPath)) {
  $jsDir = array_slice(scandir($jsPath), 2);
  foreach ($jsDir as $n => $v) {
    if (preg_match('/\.map$/', $v)) {
      continue;
    }
    $js[] = "/setup/js/$v";
  }
}

define('SCRIPTS', $js);
