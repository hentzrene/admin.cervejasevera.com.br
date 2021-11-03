<?php

namespace Module\Field\Subcategory;

use Enum\Table;
use Core\Model\Utility\Conn;

class Model
{
  /**
   * Obter todas as subcategorias.
   *
   * @param integer $fieldId
   * @return array
   */
  public static function getAllItems(int $fieldId): array
  {
    $q = Conn::table(Table::SUBCATEGORIES)
      ::select(['id', 'title', 'modules_fields_id' => 'fieldId', 'categories_id' => 'categoryId'])
      ::where('modules_fields_id', $fieldId)
      ::send();

    return $q ? $q->fetch_all(MYSQLI_ASSOC) : [];
  }

  /**
   * Adicionar.
   *
   * @param integer $fieldId
   * @param integer $categoryId
   * @param string $title
   * @return boolean
   */
  public static function addItem(int $fieldId, int $categoryId, string $title): bool
  {
    $title = addslashes($title);

    return (bool) Conn::table(Table::SUBCATEGORIES)
      ::insert(['modules_fields_id', 'categories_id', 'title'], [$fieldId, $categoryId, "'$title'"])
      ::send();;
  }

  /**
   * Definir título.
   *
   * @param integer $subcategoryId
   * @param string $value
   * @return array
   */
  public static function setItemTitle(int $subcategoryId, string $value): bool
  {
    $value = addslashes($value);

    return (bool) Conn::table(Table::SUBCATEGORIES)
      ::update(['title' => "'$value'"])
      ::where('id', $subcategoryId)
      ::send();
  }

  /**
   * Remover subcategoria por id.
   *
   * @param integer $id
   * @return boolean
   */
  public static function removeItem(int $id): bool
  {
    return (bool) Conn::table(Table::SUBCATEGORIES)
      ::deleteWhere('id', $id)
      ::send();
  }

  public static function beforeAdd(string $moduleKey, string $key, string $sqlType, int $unique): bool
  {
    $sql = "ALTER TABLE mod_$moduleKey ADD $key $sqlType DEFAULT NULL";

    if ($unique) $sql .= " UNIQUE";

    $sql .= ", ADD CONSTRAINT mod_{$moduleKey}_$key
      FOREIGN KEY ($key) REFERENCES subcategories(id)";

    return (bool) Conn::query($sql);
  }

  public static function beforeSetTypeTo(string $moduleKey, string $key, string $sqlType): bool
  {
    return (bool) Conn::query(
      "ALTER TABLE mod_$moduleKey
      MODIFY $key $sqlType,
      ADD CONSTRAINT mod_{$moduleKey}_$key FOREIGN KEY ($key) REFERENCES subcategories(id)"
    );
  }

  public static function beforeSetTypeFrom(string $moduleKey, int $id, string $key): bool
  {
    $q1 = Conn::query(
      "ALTER TABLE mod_$moduleKey
      DROP FOREIGN KEY mod_{$moduleKey}_$key;"
    );

    $q2 = Conn::table(Table::SUBCATEGORIES)
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
