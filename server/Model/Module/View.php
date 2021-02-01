<?php

namespace Model\Module;

use Model\Utility\Conn;
use Enum\Table;

class View
{
  /**
   * Obter todos os tipos de views.
   *
   * @return array
   */
  public static function getAll(): array
  {
    $q = Conn::table(Table::MODULES_VIEWS)
      ::select(['id', 'name', '`key`'])
      ::orderBy('name', 'ASC')
      ::send();

    return $q ? $q->fetch_all(MYSQLI_ASSOC) : [];
  }
}
