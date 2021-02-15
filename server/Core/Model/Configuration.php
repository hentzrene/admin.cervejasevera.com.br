<?php

namespace Core\Model;

use Core\Model\Utility\Conn;
use Enum\Table;

class Configuration
{
  /**
   * Obter configuração.
   *
   * @param string $key
   * @return object
   */
  public static function getConfig(string $key, bool $onlyPublic = false): object
  {
    $key = addslashes($key);

    $q = Conn::table(Table::CONFIGURATIONS)
      ::select('`data`')
      ::where('`key`', "'$key' ");

    if ($onlyPublic) $q = $q::and('public', 1);

    $q = $q::send();

    return !$q ? (object) []  : json_decode($q->fetch_row()[0]);
  }

  /**
   * Atualizar configuração.
   *
   * @param string $key
   * @param object $data
   * @return boolean
   */
  public static function updateConfig(string $key, object $data): bool
  {
    $key = addslashes($key);
    $data = json_encode($data, JSON_UNESCAPED_UNICODE);

    return (bool) Conn::table(Table::CONFIGURATIONS)
      ::update(['data' => "'$data'"])
      ::where('`key`', "'$key'")
      ::send();
  }
}
