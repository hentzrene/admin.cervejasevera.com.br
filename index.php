<?php

use Model\Auth;
use Model\Request;
use Model\Response;

define('APP', $_SERVER['REDIRECT_APP']);

// $DBConfigured = file_exists(__DIR__ . './server/DB.php');
$DBConfigured = false;

if (!$DBConfigured) {
  if (APP === 'rest') {
    Response::set('redirect', '/setup');
    Response::send();
  }

  if (APP !== 'setup') {
    header('Location: /setup');
    die(302);
  }
}

require __DIR__ . '/server/Resource/autoload.php';

if (APP === 'admin') {

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
}

require __DIR__ . '/server/Routers.php';
