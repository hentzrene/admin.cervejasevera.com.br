<?php

use Model\Auth;
use Model\Request;

// $DBConfigured = file_exists(__DIR__ . './server/DB.php');
$DBConfigured = false;

if ($DBConfigured) {
  require __DIR__ . '/setup/Resource/autoload.php';
  die("Ok");
} else {
  require __DIR__ . '/server/Resource/autoload.php';

  $token = $_SERVER['HTTP_AUTHORIZATION'] ? $_SERVER['HTTP_AUTHORIZATION'] : Request::get('AUTH_TOKEN');
  define('TOKEN', $token);

  if (TOKEN) {
    $checkIn = Auth::checkIn(TOKEN);

    define('ON', (bool) $checkIn);
    define('ACCOUNT_ID', $checkIn);
  } else {
    define('ON', false);
    define('ACCOUNT_ID', 0);
  }

  require __DIR__ . '/server/Routers.php';
}
