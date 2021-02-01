<?php

namespace Model\Module;

use Model\Utility\Conn;
use Enum\Table;

class Field
{
  /**
   * Obter campo.
   *
   * @param integer $id
   * @param array $fields
   * @return void
   */
  public static function get(int $id, ?array $fields = null)
  {
    $q = Conn::table(Table::MODULES_FIELDS)
      ::select($fields)
      ::where('id', $id)
      ::send();

    return $q ? $q->fetch_row() : null;
  }

  /**
   * Obter campos públicos.
   *
   * @param string $key
   * @return array
   */
  public static function getAllPublics(string $key): array
  {
    $q = Conn::table(Table::VW_MODULES_FIELDS)
      ::select(['`key`'])
      ::where('modules_key', "'$key'")
      ::and('private', 0)
      ::send();

    if (!$q) {
      return [];
    }

    return array_merge(...$q->fetch_all(MYSQLI_NUM));
  }

  /**
   * Obter todos os campos.
   *
   * @param string $key
   * @return array
   */
  public static function getAll(string $key): array
  {
    $q = Conn::table(Table::VW_MODULES_FIELDS)
      ::select([
        'id',
        'name',
        '`key`',
        'type_id' => 'typeId',
        'type_key' => 'typeKey'
      ])
      ::where('modules_key', "'$key'")
      ::send();

    return $q ? $q->fetch_all(MYSQLI_ASSOC) : [];
  }

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

    $sqlType = FieldType::get($type, ['sql_type'])[0];
    $moduleKey = Module::getKeyById($moduleId);

    $sql = "ALTER TABLE mod_$moduleKey ADD $key $sqlType DEFAULT NULL";

    if ($unique) $sql .= " UNIQUE";


    if ($type === 4) { // Se o tipo do campo for Categoria.
      $sql .= ", ADD CONSTRAINT mod_{$moduleKey}_$key
      FOREIGN KEY ($key) REFERENCES categories(id)";
    } else if ($type === 12) { // Se o tipo do campo for Secretarias.
      $sql .= ", ADD CONSTRAINT mod_{$moduleKey}_$key
      FOREIGN KEY ($key) REFERENCES mod_secretariats(id)";
    }

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
    [$moduleId, $key] = self::get($id, ['modules_id', '`key`']);
    $sqlType = FieldType::get($value, ['sql_type'])[0];
    $moduleKey = Module::getKeyById($moduleId);;

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
    [$moduleId, $key, $typeId] = self::get($id, ['modules_id', '`key`', 'modules_fields_types_id']);
    $moduleKey = Module::getKeyById($moduleId);;

    if ($typeId === 4 || $typeId  === 12) {
      Conn::query(
        "ALTER TABLE mod_$moduleKey
        DROP FOREIGN KEY mod_{$moduleKey}_$key;"
      );
    }

    $q1 = Conn::query("ALTER TABLE mod_$moduleKey DROP COLUMN $key");

    $q2 = Conn::table(Table::MODULES_FIELDS)
      ::deleteWhere('id', $id)
      ::send();

    return $q1 && $q2;
  }
}
