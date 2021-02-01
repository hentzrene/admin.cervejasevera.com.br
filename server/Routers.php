<?php
$router = new CoffeeCode\Router\Router(ROOT);

$requireVue = function () {
  define('SHARE_TAG_NAME', 'Painel de Administração - MRX Web Sites');
  define('SHARE_TAG_TITLE', 'Painel de Administração - MRX Web Sites');
  define('SHARE_TAG_DESCRIPTION', '');
  define('SHARE_TAG_KEYWORDS', '');
  define('SHARE_TAG_IMAGE', '');
  require __DIR__ . '/Vue.php';
};

$router->namespace('Controller');

$router->group('rest');
require __DIR__ . '/Routers/Modules.php';
require __DIR__ . '/Routers/System.php';
require __DIR__ . '/Routers/Email.php';
require __DIR__ . '/Routers/Setup.php';

$router->group(null);
$router->get('/{page}', function () {
  header('Location: /');
});
require __DIR__ . '/Routers/Admin.php';
require __DIR__ . '/Routers/Share.php';

require __DIR__ . '/Routers/Custom.php';

$router->dispatch();

if ($router->error()) {
  if ($_SERVER['REQUEST_METHOD'] !== 'OPTIONS') {
    http_response_code($router->error());
  }
}
