<?php

namespace Core\Model;

use Core\Model\Utility\Conn;

class Setup
{
  const DB_FILE_PATH = __DIR__ . '/../DB.php';

  const SQL_FILES_PATH = __DIR__ . '/../../../.sql_files';

  public static function exec(object $data): bool
  {
    if (self::alreadyExec()) return false;

    $host = addslashes($data->host);
    $database = addslashes($data->database);
    $user = addslashes($data->user);
    $password = addslashes($data->password);

    define('DB_HOST', $host);
    define('DB_DATABASE', $database);
    define('DB_USER', $user);
    define('DB_PASSWORD', $password);

    self::execSQLFiles();

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

  private static  function alreadyExec(): bool
  {
    return file_exists(self::DB_FILE_PATH);
  }

  private static function execSQLFiles(): bool
  {
    $sqlFiles = array_slice(scandir(self::SQL_FILES_PATH), 3);
    $commands = '';
    foreach ($sqlFiles as $f) {
      $commands .= file_get_contents(self::SQL_FILES_PATH . "/$f");
    }

    // Apagar comentários.
    $commands = preg_replace('/(--.+\s*|\/\*([^*]|\s)+\*\/;*\s*)/m', '', $commands);

    $commands = explode(";", $commands);

    foreach ($commands as $command) {
      if (trim($command)) {
        try {
          Conn::query($command);
        } catch (\Exception $err) {

          throw new \Exception($err->getMessage() . "###\n$command");
        }
      }
    }

    return true;
  }
}
