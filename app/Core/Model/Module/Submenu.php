<?php

namespace Core\Model\Module;

use Core\Model\Utility\Conn;
use Enum\Table;

class Submenu
{
  /**
   * Obter todos os submenus
   *
   * @return array
   */
  public static function getAllItems(): array
  {
    $q = Conn::table(Table::MODULES_SUBMENUS)
      ::select(['id', 'modules_menu_id', 'title'])
      ::send();

    return $q ? $q->fetch_all(MYSQLI_ASSOC) : [];
  }

  /**
   * Adicionar submenu.
   *
   * @param int $menuId
   * @param string $title
   * @return boolean
   */
  public static function add(int $menuId, string $title): object
  {
    $title = addslashes($title);

    $q = Conn::table(Table::MODULES_SUBMENUS)
      ::insert(['modules_menu_id', 'title'], [$menuId, "'$title'"])
      ::send();

    if (!$q) {
      throw new \Exception('Não foi possível adicionar o submenu');
    }

    $insertId = Conn::$conn->insert_id;

    return (object) [
      "id" => $insertId,
      "modules_menu_id" => $menuId,
      "title" => $title,
    ];
  }

  /**
   * Definir título do submenu.
   *
   * @param integer $id
   * @param string $title
   * @return array
   */
  public static function update(int $id, string $title): bool
  {
    $title = addslashes($title);

    return (bool) Conn::table(Table::MODULES_SUBMENUS)
      ::update(['title' => "'$title'"])
      ::where('id', $id)
      ::send();
  }

  /**
   * Remover submenu por id.
   *
   * @param integer $id
   * @return boolean
   */
  public static function remove(int $id): bool
  {
    return (bool) Conn::table(Table::MODULES_SUBMENUS)
      ::deleteWhere('id', $id)
      ::send();
  }
}
