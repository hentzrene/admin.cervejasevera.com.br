<?php

namespace Core\Model\Module;

use Core\Model\Utility\Conn;
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


  /**
   * Obter chave pelo id.
   *
   * @param integer $id
   * @return string|null
   */
  public static function getKeyById(int $id): ?string
  {
    $q = Conn::table(Table::MODULES_VIEWS)
      ::select(['`key`'])
      ::where('id', $id)
      ::send();

    return $q ? $q->fetch_row()[0] : null;
  }
}
