<?php

namespace Module\Field\Category;

use Enum\Table;
use Model\Utility\Conn;

class Model
{
  /**
   * Obter todas as categorias.
   *
   * @param integer $fieldId
   * @param integer $itemId
   * @return array
   */
  public static function getAllItems(int $fieldId): array
  {
    $q = Conn::table(Table::CATEGORIES)
      ::select(['id', 'title', 'modules_fields_id' => 'fieldId'])
      ::where('modules_fields_id', $fieldId)
      ::send();

    return $q ? $q->fetch_all(MYSQLI_ASSOC) : [];
  }

  /**
   * Adicionar.
   *
   * @param integer $moduleId
   * @param integer $fieldId
   * @param string $title
   * @return boolean
   */
  public static function addItem(int $moduleId, int $fieldId, string $title): bool
  {
    $title = addslashes($title);

    return (bool) Conn::table(Table::CATEGORIES)
      ::insert(['modules_id', 'modules_fields_id', 'title'], [$moduleId, $fieldId, "'$title'"])
      ::send();;
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
