<?php

namespace Model;

use Model\Conn;
use Config\Table;

class ModuleView
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
