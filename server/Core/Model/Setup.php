<?php

namespace Core\Model;

class Setup
{
  const DB_FILE_PATH = __DIR__ . '/../DB.php';

  public static function exec(object $data): bool
  {
    if (!self::alreadyExec()) return false;

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

    file_put_contents(self::DB_FILE_PATH, $script);

    return true;
  }

  public static  function alreadyExec() {
    return file_exists(self::DB_FILE_PATH);
  }
}
