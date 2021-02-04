<?php

namespace Model\Module;

use Model\Utility\Conn;
use Enum\Table;

class Category
{
  /**
   * Obter todas as categorias.
   *
   * @param integer $fieldId
   * @param integer $itemId
   * @return array
   */
  public static function getAll(int $fieldId): array
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
  public static function add(int $moduleId, int $fieldId, string $title): bool
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
  public static function setTitle(int $categoryId, string $value): bool
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
  public static function remove(int $id): bool
  {
    return (bool) Conn::table(Table::CATEGORIES)
      ::deleteWhere('id', $id)
      ::send();
  }
}
