<?php

use Core\Model\Utility\Response;

setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
date_default_timezone_set('America/Cuiaba');
error_reporting(E_ALL & ~E_NOTICE);
ini_set('display_errors', 1);

define('IMAGE_RESIZER_PROCESS', (int) $_GET['resize']);

if (!IMAGE_RESIZER_PROCESS) {
  define('SYSTEM_ROOT', __DIR__ . '/..');
  define('ROOT', $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST']);
  define('URL', ROOT . $_SERVER['REQUEST_URI']);
  define('IP', $_SERVER['REMOTE_ADDR']);

  $dotenv = Dotenv\Dotenv::createImmutable(SYSTEM_ROOT);
  $dotenv->load();
  $dotenv->required('PREFIX_PATH');

  define('PREFIX', $_ENV['PREFIX_PATH']);
  define('BASE', ROOT . PREFIX);

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
    $file = $e->getFile();
    $line = $e->getLine();
    $message = $e->getMessage();
    $code = $e->getCode();

    Response::set('status', 'error');
    Response::set('error', $message);
    Response::set('fileLine', "$file:$line");
    Response::set('errno', $code);
    Response::set('date', date('d/m/Y H:i:s'));
    Response::status(500);
    Response::send();
  });

  require __DIR__ . '/App/Config.php';
}
