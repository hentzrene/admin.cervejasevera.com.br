<?php

namespace Module\Field\Tags;

use Enum\Table;
use Core\Model\Utility\Conn;

class Model
{
  /**
   * Obter todas as tags.
   *
   * @param integer $moduleId
   * @return array
   */
  public static function getAllItems(int $moduleId): array
  {
    $q = Conn::table(Table::VW_TAGS)
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

    return (bool) Conn::table(Table::TAGS)
      ::insert(['modules_fields_id', 'title'], [$fieldId, "'$title'"])
      ::send();
  }

  /**
   * Definir título.
   *
   * @param integer $tagId
   * @param string $value
   * @return array
   */
  public static function setItemTitle(int $tagId, string $value): bool
  {
    $value = addslashes($value);

    return (bool) Conn::table(Table::TAGS)
      ::update(['title' => "'$value'"])
      ::where('id', $tagId)
      ::send();
  }

  /**
   * Remover tag por id.
   *
   * @param integer $id
   * @return boolean
   */
  public static function removeItem(int $id): bool
  {
    return (bool) Conn::table(Table::TAGS)
      ::deleteWhere('id', $id)
      ::send();
  }

  public static function beforeSetTypeFrom(string $moduleKey, int $id, string $key): bool
  {
    $q = Conn::table(Table::TAGS)
      ::deleteWhere('modules_fields_id', $id)
      ::send();

    return $q && $q;
  }
}
