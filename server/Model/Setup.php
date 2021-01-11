<?php

namespace Model;

class Setup
{
  public static function exec(object $data): bool
  {
    $dbFilePath = __DIR__ . '/../DB.php';

    if (file_exists($dbFilePath)) return false;

    $host = addslashes($data->host);
    $database = addslashes($data->database);
    $user = addslashes($data->user);
    $password = addslashes($data->password);

    $script = <<<EOT
    <?php
    define('DB_HOST', '$host');
    define('DB_DATABASE', '$database');
    define('DB_USER', '$user');
    define('DB_PASSWORD', '$password');
    EOT;

    file_put_contents($dbFilePath, $script);

    return true;
  }
}
