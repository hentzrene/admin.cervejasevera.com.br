<?php

use Core\Model\Account\Auth;
use Core\Model\Utility\Request;

define('APP', $_SERVER['REDIRECT_APP']);
define('ROUTE', $_GET['route']);

if (ROUTE === '/robots.txt') {
  require __DIR__ . '/../app/App/Robots.php';
} else if (ROUTE === '/sitemap.xml') {
  require __DIR__ . '/../app/App/Sitemap.php';
} else {
  define('INSTALLED', file_exists(__DIR__ . '/../app/Core/DB.php'));

  require __DIR__ . '/../vendor/autoload.php';

  if (!INSTALLED) {
    if (APP === 'admin-rest' && $_GET['route'] !== '/rest/setup') {
      throw new Exception('Database not configured.');
    }

    if (APP !== 'admin-rest' && APP !== 'setup' && $_GET['route'] !== '/setup') {
      header('Location: /setup');
      die(302);
    }
  } else {
    require __DIR__ . '/../app/Core/DB.php';

    if (ROUTE === '/manifest.json') {
      require __DIR__ . '/../app/App/Manifest.php';
      die();
    }

    $token = $_SERVER['REDIRECT_HTTP_AUTHORIZATION']
      ? $_SERVER['REDIRECT_HTTP_AUTHORIZATION']
      : Request::get('AUTH_TOKEN');

    define('TOKEN', $token);

    if (TOKEN) {
      $checkIn = Auth::checkIn(TOKEN);

      define('ON', (bool) $checkIn);
      define('ACCOUNT_ID', $checkIn->accountId);
      define('ACCOUNT_TYPE', $checkIn->accountTypeId);
    } else {
      define('ON', false);
      define('ACCOUNT_ID', 0);
      define('ACCOUNT_TYPE', 0);
    }
  }

  require __DIR__ . '/../app/Routers.php';
}
