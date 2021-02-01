<?php

namespace Model\Module;

use Model\Utility\Conn;
use Enum\Table;

class FieldType
{
  /**
   * Obter tipo de campo.
   *
   * @param integer $id
   * @param array $fields
   * @return void
   */
  public static function get(int $id, ?array $fields = null)
  {
    $q = Conn::table(Table::MODULES_FIELDS_TYPES)
      ::select($fields)
      ::where('id', $id)
      ::send();

    return $q ? $q->fetch_row() : null;
  }

  /**
   * Obter todos os tipos de campos.
   *
   * @return array
   */
  public static function getAll(): array
  {
    $q = Conn::table(Table::MODULES_FIELDS_TYPES)
      ::select(['id', 'name', '`key`'])
      ::where('active', 1)
      ::orderBy('name', 'ASC')
      ::send();

    return $q ? $q->fetch_all(MYSQLI_ASSOC) : [];
  }
}
