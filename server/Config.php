<?php

use  Model\Response;

setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
date_default_timezone_set('America/Cuiaba');
error_reporting(E_ALL & ~E_NOTICE);
// header('Cache-Control: no-cache, no-store, must-revalidate'); // HTTP 1.1.
// header('Pragma: no-cache'); // HTTP 1.0.
// header('Expires: 0'); // Proxies.
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

set_error_handler(
  function ($errno, $error, $file, $line) {
    Response::set('status', 'error');
    Response::set('error', $error);
    Response::set('fileLine', "$file:$line");
    Response::set('date', date('d/m/Y H:i:s'));
    Response::status(500);
    Response::send();
  },
  E_ALL & ~E_NOTICE
);

set_exception_handler(function ($e) {
  Response::set('status', 'error');
  Response::set('error', $e->getMessage());
  Response::set('errno', $e->getCode());
  Response::set('date', date('d/m/Y H:i:s'));
  Response::status(500);
  Response::send();
});

$cssPath = __DIR__ . "/../css/";
$css = [];
if (is_dir($cssPath)) {
  $cssDir = array_slice(scandir($cssPath), 2);
  foreach ($cssDir as $n => $v) {
    $css[] = "/css/$v";
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
    $js[] = "/js/$v";
  }
}

define('SCRIPTS', $js);

// define('PROXY', '/proxy');
define('PROXY', null);
