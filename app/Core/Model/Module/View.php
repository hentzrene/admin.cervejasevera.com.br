<?php

namespace Core\Model\Module;

use Core\Model\Utility\Conn;
use Enum\Table;

class View
{
  /**
   * Obter todos os tipos de views.
   *
   * @return array
   */
  public static function getAll(): array
  {
    $q = Conn::table(Table::MODULES_VIEWS)
      ::select(['id', 'name', '`key`'])
      ::orderBy('name', 'ASC')
      ::send();

    $views = $q ? $q->fetch_all(MYSQLI_ASSOC) : [];

    $permissions = self::getAllPermissions();


    $views = array_map(function ($view) use ($permissions) {
      $view['permissions'] = [];

      foreach ($permissions as $permission) {
        if ($permission['modules_views_id'] === $view['id']) {
          $view['permissions'][] = $permission;
        }
      }

      return $view;
    }, $views);

    return $views;
  }

  public static function getAllPermissions()
  {
    $q = Conn::table(Table::MODULES_VIEWS_PERMISSIONS)
      ::select(['id', 'modules_views_id', 'title'])
      ::send();

    return $q ? $q->fetch_all(MYSQLI_ASSOC) : [];
  }


  /**
   * Obter chave pelo id.
   *
   * @param integer $id
   * @return string|null
   */
  public static function getKeyById(int $id): ?string
  {
    $q = Conn::table(Table::MODULES_VIEWS)
      ::select(['`key`'])
      ::where('id', $id)
      ::send();

    return $q ? $q->fetch_row()[0] : null;
  }
}
