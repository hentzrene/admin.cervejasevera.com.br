<?php

namespace Module\Field\Category;

use Enum\Table;
use Core\Model\Utility\Conn;

class Model
{
  /**
   * Obter todas as categorias.
   *
   * @param integer $moduleId
   * @return array
   */
  public static function getAllItems(int $moduleId): array
  {
    $q = Conn::table(Table::VM_CATEGORIES)
      ::select(['id', 'title', 'modules_fields_id' => 'fieldId', 'modules_fields_key' => 'fieldKey'])
      ::where('modules_id', $moduleId)
      ::send();

    return $q ? $q->fetch_all(MYSQLI_ASSOC) : [];
  }

  /**
   * Adicionar.
   *
   * @param integer $fieldId
   * @param string $title
   * @return boolean
   */
  public static function addItem(int $fieldId, string $title): bool
  {
    $title = addslashes($title);

    return (bool) Conn::table(Table::CATEGORIES)
      ::insert(['modules_fields_id', 'title'], [$fieldId, "'$title'"])
      ::send();
  }

  /**
   * Definir título.
   *
   * @param integer $categoryId
   * @param string $value
   * @return array
   */
  public static function setItemTitle(int $categoryId, string $value): bool
  {
    $value = addslashes($value);

    return (bool) Conn::table(Table::CATEGORIES)
      ::update(['title' => "'$value'"])
      ::where('id', $categoryId)
      ::send();
  }

  /**
   * Remover categoria por id.
   *
   * @param integer $id
   * @return boolean
   */
  public static function removeItem(int $id): bool
  {
    return (bool) Conn::table(Table::CATEGORIES)
      ::deleteWhere('id', $id)
      ::send();
  }

  public static function beforeAdd(string $moduleKey, string $key, string $sqlType, int $unique): bool
  {
    $sql = "ALTER TABLE mod_$moduleKey ADD $key $sqlType DEFAULT NULL";

    if ($unique) $sql .= " UNIQUE";

    $sql .= ", ADD CONSTRAINT mod_{$moduleKey}_$key
      FOREIGN KEY ($key) REFERENCES categories(id)";

    return (bool) Conn::query($sql);
  }

  public static function beforeSetTypeTo(string $moduleKey, string $key, string $sqlType): bool
  {
    return (bool) Conn::query(
      "ALTER TABLE mod_$moduleKey
      MODIFY $key $sqlType,
      ADD CONSTRAINT mod_{$moduleKey}_$key FOREIGN KEY ($key) REFERENCES categories(id)"
    );
  }

  public static function beforeSetTypeFrom(string $moduleKey, int $id, string $key): bool
  {
    $q1 = Conn::query(
      "ALTER TABLE mod_$moduleKey
      DROP FOREIGN KEY mod_{$moduleKey}_$key;"
    );

    $q2 = Conn::table(Table::CATEGORIES)
      ::deleteWhere('modules_fields_id', $id)
      ::send();

    return $q1 && $q2;
  }

  public static function beforeRemove(string $moduleKey, string $key): bool
  {
    return (bool) Conn::query(
      "ALTER TABLE mod_$moduleKey
      DROP FOREIGN KEY mod_{$moduleKey}_$key;"
    );
  }
}
