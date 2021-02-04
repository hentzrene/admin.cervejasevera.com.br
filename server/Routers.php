<?php
$router = new CoffeeCode\Router\Router(ROOT);

require __DIR__ . '/Core/Routers.php';

$router->group(null);
$router->get('/{page}', function () {
  header('Location: /');
});

require __DIR__ . '/App/Routers.php';
require __DIR__ . '/Custom/Routers.php';

$router->dispatch();

if ($router->error()) {
  if ($_SERVER['REQUEST_METHOD'] !== 'OPTIONS') {
    http_response_code($router->error());
  }
}
