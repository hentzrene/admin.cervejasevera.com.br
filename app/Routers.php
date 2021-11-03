<?php
$router = new CoffeeCode\Router\Router(ROOT);

require __DIR__ . '/App/Routers.php';
require __DIR__ . '/Core/Routers.php';

$fields = array_slice(scandir(__DIR__ . '/Module/Field'), 2);

foreach ($fields as $f) {
  $r = __DIR__ . "/Module/Field/$f/Router.php";
  if (file_exists($r)) {
    require $r;
  }
}

require __DIR__ . '/Custom/Routers.php';

$router->dispatch();

if ($router->error()) {
  if ($_SERVER['REQUEST_METHOD'] !== 'OPTIONS') {
    http_response_code($router->error());
  }
}
