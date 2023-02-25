<?php

namespace Core\Model\Utility;

use Illuminate\Database\Capsule\Manager;

class DB extends Manager
{
}

$capsule = new DB;

$capsule->addConnection([
  'driver' => 'mysql',
  'host' => DB_HOST,
  'database' => DB_DATABASE,
  'username' => DB_USER,
  'password' => DB_PASSWORD,
  'charset' => 'utf8',
  'collation' => 'utf8_unicode_ci',
  'prefix' => '',
]);

$capsule->setAsGlobal();