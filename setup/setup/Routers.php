<?php
$router = new CoffeeCode\Router\Router(ROOT);

$router->namespace('Controller');

$router->get("/", function () {
  require_once __DIR__ . '/index.html';
});

$router->dispatch();

if ($router->error()) {
  if ($_SERVER['REQUEST_METHOD'] !== 'OPTIONS') {
    http_response_code($router->error());
  }
}
