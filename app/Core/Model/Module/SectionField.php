<?php

namespace Core\Model\Module;

use Core\Model\Utility\Conn;
use Enum\Table;

class SectionField
{
  /**
   * Obter todos os itens da seção.
   *
   * @param integer|null $moduleId
   * @return array
   */
  public static function getAllItems(?int $moduleId = null): array
  {
    $q = Conn::table(Table::MODULES_SECTIONS_FIELDS)
      ::select();

    if ($moduleId) {
      $q = $q::where('modules_id', $moduleId);
    }

    $q = $q::send();

    return $q ? $q->fetch_all(MYSQLI_ASSOC) : [];
  }

  /**
   * Adicionar seção.
   *
   * @param string $title
   * @param int $moduleId
   * @return boolean
   */
  public static function addItem(string $title, int $moduleId): bool
  {
    $title = addslashes($title);

    return (bool) Conn::table(Table::MODULES_SECTIONS_FIELDS)
      ::insert(['title', 'modules_id'], ["'$title'", $moduleId])
      ::send();
  }

  /**
   * Definir título da seção.
   *
   * @param integer $sectionId
   * @param string $title
   * @return array
   */
  public static function updateItem(int $sectionId, string $title): bool
  {
    $title = addslashes($title);

    return (bool) Conn::table(Table::MODULES_SECTIONS_FIELDS)
      ::update(['title' => "'$title'"])
      ::where('id', $sectionId)
      ::send();
  }

  /**
   * Remover seção por id.
   *
   * @param integer $id
   * @return boolean
   */
  public static function removeItem(int $id): bool
  {
    return (bool) Conn::table(Table::MODULES_SECTIONS_FIELDS)
      ::deleteWhere('id', $id)
      ::send();
  }
}
