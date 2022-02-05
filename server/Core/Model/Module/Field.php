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
        'type_key' => 'typeKey',
        'options'
      ])
      ::where('modules_key', "'$key'")
      ::send();

    if (!$q) {
      return [];
    }

    $fields = $q->fetch_all(MYSQLI_ASSOC);

    foreach ($fields as $n => $f) {
      $fields[$n]['options'] = json_decode($f['options']);
    }

    return $fields;
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
    $fieldClass = self::getFieldClassOfTypeId($type);

    [$sqlType, $defaultOptions] = FieldType::get($type, ['sql_type', 'default_options']);
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
        ['modules_id', 'name', '`key`', '`unique`', 'private', 'modules_fields_types_id', 'options'],
        [$moduleId, "'$name'", "'$key'", $unique, $private, $type, $defaultOptions ? "'$defaultOptions'" : "'{}'"]
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
    $fieldClassTo = self::getFieldClassOfTypeId($value);

    if (!$fieldClassTo) {
      throw new \Exception("Field não configurado corretamente no código fonte.");
    }

    $value = addslashes($value);
    [$moduleId, $key, $typeId] = self::get($id, ['modules_id', '`key`', 'modules_fields_types_id' => 'typeId']);

    $fieldClassFrom = self::getFieldClassOfTypeId($typeId);

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
   * Obter opções.
   *
   * @param integer $id
   * @return object
   */
  public static function getOptions(int $id): object
  {
    return json_decode(Conn::table(Table::MODULES_FIELDS)
      ::select(["options"])
      ::where('id', $id)
      ::send()
      ->fetch_row()[0]);
  }

  /**
   * Definir opção.
   *
   * @param integer $id
   * @param string $option
   * @param $value
   * @return boolean
   */
  public static function setOption(int $id, string $option, $value): bool
  {
    $option = addslashes($option);
    $value = addslashes($value);
    [$typeId] = self::get($id, ['modules_fields_types_id']);
    $fieldClass = self::getFieldClassOfTypeId($typeId);

    if (!$fieldClass) {
      throw new \Exception("Field não configurado corretamente no código fonte.");
    }

    $q1 = null;
    $q3 = null;
    $hasBeforeSetOption = method_exists($fieldClass, 'beforeSetOption');
    $hasAfterSetOption = method_exists($fieldClass, 'afterSetOption');

    if ($hasBeforeSetOption) {
      $q1 = call_user_func([$fieldClass, 'beforeSetOption'], $id, $option, $value);
    } else {
      $q1 = true;
    }

    $q2 = (bool) Conn::table(Table::MODULES_FIELDS)
      ::update(["options" => "JSON_SET(`options`, '$.$option', '$value')"])
      ::where('id', $id)
      ::send();

    if ($hasAfterSetOption) {
      $q3 = call_user_func([$fieldClass, 'afterSetOption'], $id, $option, $value);
    } else {
      $q3 = true;
    }

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
    $fieldClass = self::getFieldClassOfTypeId($typeId);

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

    $q4 = Module::removeFromViewOptionsListHeaders($moduleId, $key);

    return $q1 && $q2 && $q3 && $q4;
  }

  /**
   * Obter classe do tipo com seu id.
   *
   * @param integer $typeId
   * @return string
   */
  public static function getFieldClassOfTypeId(int $typeId): string
  {
    $field = FieldType::get($typeId, ['`key`']);
    $fieldDir = null;

    if (!$field) {
      throw new \Exception("Não existe um tipo de campo com esse id.");
    } else {
      $fieldDir = ucfirst($field[0]);
    }

    if (!file_exists(SYSTEM_ROOT . "/server/Module/Field/$fieldDir/Model.php")) {
      throw new \Exception("Model do field \"{$field[0]}\" não configurado corretamente no código fonte.");
    }

    return "Module\Field\\$fieldDir\Model";
  }

  /**
   * Obter id do campo por id do módulo e sua chave.
   *
   * @param integer $moduleId
   * @param string $key
   * @return void
   */
  public static function getId(int $moduleId, string $key): ?int
  {
    $key = addslashes($key);

    $q =  Conn::table(Table::MODULES_FIELDS)
      ::select(['id'])
      ::where('modules_id', $moduleId)
      ::and('`key`', "'$key'")
      ::send();

    return $q ? $q->fetch_row()[0] : null;
  }

  /**
   * Obter chave do campo por seu id.
   *
   * @param string $id
   * @return void
   */
  public static function getKey(int $id): ?string
  {
    $q =  Conn::table(Table::MODULES_FIELDS)
      ::select(['`key`'])
      ::where('id', $id)
      ::send();

    return $q ? $q->fetch_row()[0] : null;
  }
}
