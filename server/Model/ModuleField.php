<?php

namespace Model;

use Model\Conn;
use Config\Table;

class ModuleField
{
  /**
   * Adicionar campo ao módulo.
   *
   * @param object $data
   * @param int $moduleId
   * @return boolean
   */
  public static function add(object $data, int $moduleId): bool
  {
    $name = addslashes($data->name);
    $key = addslashes($data->key);
    $unique = (int) $data->unique;
    $private = (int) $data->private;
    $type = (int) $data->type;

    Conn::table(Table::MODULES_FIELDS)
      ::insert(
        ['modules_id', 'name', '`key`', '`unique`', 'private', 'modules_fields_types_id'],
        [$moduleId, "'$name'", "'$key'", $unique, $private, $type]
      )
      ::send();

    $sqlType = Conn::table(Table::MODULES_FIELDS_TYPES)
      ::select(['sql_type'])
      ::where('id', $type)
      ::send()
      ->fetch_row()[0];

    $moduleKey = Conn::table(Table::MODULES)
      ::select(['`key`'])
      ::where('id', $moduleId)
      ::send()
      ->fetch_row()[0];

    $sql = "ALTER TABLE mod_$moduleKey ADD $key $sqlType DEFAULT NULL";

    if ($unique) $sql .= " UNIQUE";

    Conn::query($sql);

    return true;
  }

  /**
   * Definir nome.
   *
   * @param integer $id
   * @param string $value
   * @return boolean
   */
  public static function setName(int $id, string $value): bool
  {
    $value = addslashes($value);

    return (bool) Conn::table(Table::MODULES_FIELDS)
      ::update(["name" => "'$value'"])
      ::where('id', $id)
      ::send();
  }

  /**
   * Definir id to tipo.
   *
   * @param integer $id
   * @param int $value
   * @return boolean
   */
  public static function setTypeId(int $id, int $value): bool
  {
    $value = addslashes($value);

    [$moduleId, $key] = Conn::table(Table::MODULES_FIELDS)
      ::select(['modules_id', '`key`'])
      ::where('id', $id)
      ::send()
      ->fetch_row();

    $sqlType = Conn::table(Table::MODULES_FIELDS_TYPES)
      ::select(['sql_type'])
      ::where('id', $value)
      ::send()
      ->fetch_row()[0];

    $moduleKey = Conn::table(Table::MODULES)
      ::select(['`key`'])
      ::where('id', $moduleId)
      ::send()
      ->fetch_row()[0];


    $q1 = Conn::table(Table::MODULES_FIELDS)
      ::update(["modules_fields_types_id" => $value])
      ::where('id', $id)
      ::send();

    $q2 = Conn::query("ALTER TABLE mod_$moduleKey MODIFY $key $sqlType");

    return $q1 && $q2;
  }

  /**
   * Remover campo de módulo por id.
   *
   * @param integer $id
   * @return boolean
   */
  public static function remove(int $id): bool
  {
    [$moduleId, $key] = Conn::table(Table::MODULES_FIELDS)
      ::select(['modules_id', '`key`'])
      ::where('id', $id)
      ::send()
      ->fetch_row();

    $moduleKey = Conn::table(Table::MODULES)
      ::select(['`key`'])
      ::where('id', $moduleId)
      ::send()
      ->fetch_row()[0];

    $q1 = Conn::query("ALTER TABLE mod_$moduleKey DROP COLUMN $key");

    $q2 = Conn::table(Table::MODULES_FIELDS)
      ::deleteWhere('id', $id)
      ::send();

    return $q1 && $q2;
  }
}
