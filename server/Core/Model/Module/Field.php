<?php

namespace Core\Model\Module;

use Core\Model\Utility\Conn;
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
    $fieldClass = self::getFieldClasOfTypeId($type);

    $sqlType = FieldType::get($type, ['sql_type'])[0];
    $moduleKey = Module::getKeyById($moduleId);

    $q1 = null;

    if (method_exists($fieldClass, 'beforeAdd')) {
      $q1 = call_user_func([$fieldClass, 'beforeAdd'], $moduleKey, $key, $sqlType, $unique);
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
    $fieldClassTo = self::getFieldClasOfTypeId($value);
    $fieldClassFrom = self::getFieldClasOfTypeId($id);

    if (!$fieldClassTo) {
      throw new \Exception("Field não configurado corretamente no código fonte.");
    }

    $value = addslashes($value);
    [$moduleId, $key] = self::get($id, ['modules_id', '`key`']);
    $sqlType = FieldType::get($value, ['sql_type'])[0];
    $moduleKey = Module::getKeyById($moduleId);;

    $q1 = null;
    $q2 = null;
    $hasBeforeSetTypeFrom = method_exists($fieldClassFrom, 'beforeSetTypeFrom');
    $hasBeforeSetTypeTo = method_exists($fieldClassTo, 'beforeSetTypeTo');

    if (!$hasBeforeSetTypeFrom && !$hasBeforeSetTypeTo) {
      $q1 = $q2 = Conn::query("ALTER TABLE mod_$moduleKey MODIFY $key $sqlType");
    } else {
      if ($hasBeforeSetTypeFrom) {
        $q1 = call_user_func([$fieldClassFrom, 'beforeSetTypeFrom'], $moduleKey, $id, $key);
      } else {
        $q1 = true;
      }

      if ($hasBeforeSetTypeTo) {
        $q2 = call_user_func([$fieldClassTo, 'beforeSetTypeTo'], $moduleKey, $key, $sqlType);
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
    $fieldClass = self::getFieldClasOfTypeId($typeId);

    $q1 = null;

    if (method_exists($fieldClass, 'beforeRemove')) {
      $q1 = call_user_func([$fieldClass, 'beforeRemove'], $moduleKey, $key);
    } else {
      $q1 = true;
    }

    $q2 = Conn::query("ALTER TABLE mod_$moduleKey DROP COLUMN $key");

    $q3 = Conn::table(Table::MODULES_FIELDS)
      ::deleteWhere('id', $id)
      ::send();

    return $q1 && $q2 && $q3;
  }

  /**
   * Obter classe do tipo com seu id.
   *
   * @param integer $typeId
   * @return string
   */
  private static function getFieldClasOfTypeId(int $typeId): string
  {
    $field = FieldType::get($typeId, ['key']);
    $fieldDir = null;

    if (!$field) {
      throw new \Exception("Não existe um tipo de campo com esse id.");
    } else {
      $fieldDir = ucfirst($field[0]);
    }

    if (!file_exists(SYSTEM_ROOT . "/server/Module/Field/$fieldDir/Model.php")) {
      throw new \Exception("Model do field não configurado corretamente no código fonte.");
    }

    return "Module\Field\\$fieldDir\Model";
  }
}
