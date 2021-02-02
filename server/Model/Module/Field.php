<?php

namespace Model\Module;

use Model\Utility\Conn;
use Enum\Table;

class Field
{
  const FIELDS_CLASSES = [
    1 => 'Model\Module\Field\TinyText',
    2 => 'Model\Module\Field\Email',
    3 => 'Model\Module\Field\Password',
    4 => 'Model\Module\Field\Category',
    5 => 'Model\Module\Field\Date',
    6 => 'Model\Module\Field\MediumText',
    7 => 'Model\Module\Field\BigText',
    8 => 'Model\Module\Field\ImageFile',
    9 => 'Model\Module\Field\File',
    10 => 'Model\Module\Field\Subcategory',
    11 => 'Model\Module\Field\Url',
    12 => 'Model\Module\Field\SwitchField',
  ];

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

    $sqlType = FieldType::get($type, ['sql_type'])[0];
    $moduleKey = Module::getKeyById($moduleId);

    $q1 = null;

    if (method_exists(self::FIELDS_CLASSES[$type], 'beforeAdd')) {
      $q1 = call_user_func([self::FIELDS_CLASSES[$type], 'beforeAdd'], $moduleKey, $key, $sqlType, $unique);
    } else {
      $sql = "ALTER TABLE mod_$moduleKey ADD $key $sqlType DEFAULT NULL";

      if ($unique) $sql .= " UNIQUE";

      $q1 = Conn::query($sql);
    }


    $q2 = Conn::table(Table::MODULES_FIELDS)
      ::insert(
        ['modules_id', 'name', '`key`', '`unique`', 'private', 'modules_fields_types_id'],
        [$moduleId, "'$name'", "'$key'", $unique, $private, $type]
      )
      ::send();

    return $q1 && $q2;
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

    $q1 = null;
    $q2 = null;
    $hasBeforeSetTypeFrom = method_exists(self::FIELDS_CLASSES[$id], 'beforeSetTypeFrom');
    $hasBeforeSetTypeTo = method_exists(self::FIELDS_CLASSES[$value], 'beforeSetTypeTo');

    if (!$hasBeforeSetTypeFrom && !$hasBeforeSetTypeTo) {
      $q1 = $q2 = Conn::query("ALTER TABLE mod_$moduleKey MODIFY $key $sqlType");
    } else {
      if ($hasBeforeSetTypeFrom) {
        $q1 = call_user_func([self::FIELDS_CLASSES[$id], 'beforeSetTypeFrom'], $moduleKey, $id, $key);
      } else {
        $q1 = true;
      }

      if ($hasBeforeSetTypeTo) {
        $q2 = call_user_func([self::FIELDS_CLASSES[$value], 'beforeSetTypeTo'], $moduleKey, $key, $sqlType);
      } else {
        $q2 = Conn::query("ALTER TABLE mod_$moduleKey MODIFY $key $sqlType");
      }
    }

    $q3 = Conn::table(Table::MODULES_FIELDS)
      ::update(["modules_fields_types_id" => $value])
      ::where('id', $id)
      ::send();

    return $q1 && $q2 && $q3;
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
    $moduleKey = Module::getKeyById($moduleId);

    $q1 = null;

    if (method_exists(self::FIELDS_CLASSES[$typeId], 'beforeRemove')) {
      $q1 = call_user_func([self::FIELDS_CLASSES[$typeId], 'beforeRemove'], $moduleKey, $key);
    } else {
      $q1 = true;
    }

    $q2 = Conn::query("ALTER TABLE mod_$moduleKey DROP COLUMN $key");

    $q3 = Conn::table(Table::MODULES_FIELDS)
      ::deleteWhere('id', $id)
      ::send();

    return $q1 && $q2 && $q3;
  }
}
