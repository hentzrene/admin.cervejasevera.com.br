<?php

namespace Core\Model\Module;

use Core\Model\Utility\Conn;
use Enum\Table;

class Menu
{
  /**
   * Obter todos os itens do menu.
   *
   * @return array
   */
  public static function getAllItems(): array
  {
    $q = Conn::table(Table::MODULES_MENU)
      ::select(['id', 'title'])
      ::send();

    $menus = $q ? $q->fetch_all(MYSQLI_ASSOC) : [];

    if (!count($menus)) return [];

    $submenus = Submenu::getAllItems();

    foreach ($menus as $key => $menu) {
      $menus[$key]['submenus'] = [];

      foreach ($submenus as $submenu) {
        if ($menu['id'] === $submenu['modules_menu_id']) {
          $menus[$key]['submenus'][] = $submenu;
        }
      }
    }

    return $menus;
  }

  /**
   * Adicionar menu.
   *
   * @param string $title
   * @return boolean
   */
  public static function addItem(string $title): bool
  {
    $title = addslashes($title);

    return (bool) Conn::table(Table::MODULES_MENU)
      ::insert(['title'], ["'$title'"])
      ::send();
  }

  /**
   * Definir título do menu.
   *
   * @param integer $menuId
   * @param string $title
   * @return array
   */
  public static function updateItem(int $menuId, string $title): bool
  {
    $title = addslashes($title);

    return (bool) Conn::table(Table::MODULES_MENU)
      ::update(['title' => "'$title'"])
      ::where('id', $menuId)
      ::send();
  }

  /**
   * Remover menu por id.
   *
   * @param integer $id
   * @return boolean
   */
  public static function removeItem(int $id): bool
  {
    return (bool) Conn::table(Table::MODULES_MENU)
      ::deleteWhere('id', $id)
      ::send();
  }
}
