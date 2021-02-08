<?php

use Core\Model\Account\Auth;
use Core\Model\Utility\Request;

define('APP', $_SERVER['REDIRECT_APP']);
define('INSTALLED', file_exists(__DIR__ . '/server/Core/DB.php'));

require __DIR__ . '/server/Resource/autoload.php';

if (!INSTALLED) {
  if (APP === 'rest' && $_GET['route'] !== '/rest/setup') {
    throw new Exception('Database not configured.');
  }

  if (APP !== 'rest' && APP !== 'setup' && $_GET['route'] !== '/setup') {
    header('Location: /admin/setup');
    die(302);
  }
} else {
  require __DIR__ . '/server/Core/DB.php';

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

require __DIR__ . '/server/Routers.php';
