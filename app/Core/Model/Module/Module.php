<?php

namespace Core\Model\Module;

use Core\Model\Utility\Conn;
use Enum\Table;
use Core\Model\Account\Account;

class Module
{
  /**
   * Obter módulo.
   *
   * @param int $id
   * @return object|null
   */
  public static function get(int $id): ?object
  {
    $permissions = array_values(array_unique(array_map(function ($permission) {
      return $permission['modules_id'];
    }, Account::getPermissions(ACCOUNT_ID))));

    if (ACCOUNT_TYPE !== 1 && !in_array($id, $permissions)) {
      return null;
    }

    $q = Conn::table(Table::VW_MODULES)
      ::select([
        'id',
        'name',
        '`key`',
        'icon',
        'view_options' => 'viewOptions',
        'view_id' => 'viewId',
        'view_key' => 'viewKey',
        'view_name' => 'viewName',
        'removable'
      ])
      ::where("id", $id)
      ::send();

    if (!$q) {
      return null;
    }

    $module = $q->fetch_object();
    $module->viewOptions = json_decode($module->viewOptions);

    $module->fields = Field::getAll($module->key);

    return $module;
  }

  /**
   * Obter id pela chave.
   *
   * @param string $key
   * @return int|null
   */
  public static function getIdByKey(string $key): ?int
  {
    $key = addslashes($key);

    $q = Conn::table(Table::MODULES)
      ::select(['id'])
      ::where("`key`", "'$key'")
      ::send();

    return !$q ? null : (int) $q->fetch_row()[0];
  }

  /**
   * Obter chave pelo id.
   *
   * @param int $id
   * @return string|null
   */
  public static function getKeyById(int $id): ?string
  {
    $q = Conn::table(Table::MODULES)
      ::select(['`key`'])
      ::where("id", $id)
      ::send();

    return !$q ? null : $q->fetch_row()[0];
  }

  /**
   * Obter opções da view pela chave.
   *
   * @param string $key
   * @return object|null
   */
  public static function getViewOptionsByKey(string $key): ?object
  {
    $key = addslashes($key);

    $q = Conn::table(Table::MODULES)
      ::select(['view_options'])
      ::where("`key`", "'$key'")
      ::send();

    return !$q ? null : json_decode($q->fetch_row()[0]);
  }

  /**
   * Obter todos os módulos.
   *
   * @return array
   */
  public static function getAll(): array
  {
    $q = null;

    if (ACCOUNT_TYPE !== 1) {
      $permissions = json_encode(array_values(array_unique(array_map(function ($permission) {
        return $permission['modules_id'];
      }, Account::getPermissions(ACCOUNT_ID)))));

      $q = Conn::table(Table::VW_MODULES)
        ::select([
          'id',
          'name',
          '`key`',
          'icon',
          'view_options' => 'viewOptions',
          'view_id' => 'viewId',
          'view_key' => 'viewKey',
          'view_name' => 'viewName',
          'menu_id' => 'menuId',
          'menu_title' => 'menuTitle',
          'submenu_id' => 'submenuId',
          'submenu_title' => 'submenuTitle',
          'removable'
        ])
        ::where("JSON_CONTAINS('$permissions', CONCAT('\"', id, '\"'), '$')", 1)
        ::send();
    } else {
      $q = Conn::table(Table::VW_MODULES)
        ::select([
          'id',
          'name',
          '`key`',
          'icon',
          'view_options' => 'viewOptions',
          'view_key' => 'viewKey',
          'view_name' => 'viewName',
          'menu_id' => 'menuId',
          'menu_title' => 'menuTitle',
          'submenu_id' => 'submenuId',
          'submenu_title' => 'submenuTitle',
          'removable'
        ])
        ::send();
    }

    if (!$q)
      return [];

    $modules = $q->fetch_all(MYSQLI_ASSOC);

    foreach ($modules as $i => $module) {
      $modules[$i]['viewOptions'] = json_decode($module['viewOptions']);
      $modules[$i]['fields'] = Field::getAll($module['key']);
    }

    return $modules;
  }

  /**
   * Obter as informações básicas de todos os módulos.
   *
   * @return array
   */
  public static function getBasicOfAll(): array
  {
    $q = Conn::table(Table::MODULES)
      ::select([
        'id',
        'name',
        '`key`',
        'icon',
      ])
      ::send();

    return $q ? $q->fetch_all(MYSQLI_ASSOC) : [];
  }

  /**
   * Verificar se existe um módulo com essa chave e retornar a chave de sua view se existir.
   *
   * @param string $key
   * @return string|null
   */
  public static function has(string $key): ?string
  {
    $q = Conn::table(Table::VW_MODULES)
      ::select(['view_key'])
      ::where('`key`', "'$key'")
      ::send();

    return $q ? $q->fetch_row()[0] : null;
  }

  /**
   * Verificar se o usuário tem permissão para o módulo.
   *
   * @param string $key
   * @return string|null
   */
  public static function isAllowed(int $id, int $accountId): bool
  {
    if (ACCOUNT_TYPE === 1)
      return true;

    $permissions = array_values(array_unique(array_map(function ($permission) {
      return $permission['modules_id'];
    }, Account::getPermissions($accountId))));

    return in_array((string) $id, $permissions);
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

    return (bool) Conn::table(Table::MODULES)
      ::update(["name" => "'$value'"])
      ::where('id', $id)
      ::send();
  }

  /**
   * Definir menu.
   *
   * @param int $id
   * @param int $menuId
   * @param int $submenuId
   * @return boolean
   */
  public static function setMenu(int $id, ?int $menuId, ?int $submenuId): bool
  {
    return (bool) Conn::table(Table::MODULES)
      ::update([
        "modules_menu_id" => $menuId ? $menuId : 'NULL',
        "modules_submenus_id" => $submenuId ? $submenuId : 'NULL'
      ])
      ::where('id', $id)
      ::send();
  }

  /**
   * Definir ícone.
   *
   * @param integer $id
   * @param string $value
   * @return boolean
   */
  public static function setIcon(int $id, string $value): bool
  {
    $value = addslashes($value);

    return (bool) Conn::table(Table::MODULES)
      ::update(["icon" => "'$value'"])
      ::where('id', $id)
      ::send();
  }

  /**
   * Definir opções da view.
   *
   * @param integer $id
   * @param string $value
   * @return boolean
   */
  public static function setViewOptions(int $id, object $value): bool
  {
    $value = json_encode($value);

    return (bool) Conn::table(Table::MODULES)
      ::update(["view_options" => "'$value'"])
      ::where('id', $id)
      ::send();
  }

  /**
   * Remover item da propriedade listHeaders da viewOptions.
   *
   * @param integer $id
   * @param string $value
   * @return void
   */
  public static function removeFromViewOptionsListHeaders(int $id, string $value): bool
  {
    $value = addslashes($value);

    return (bool) Conn::query(
      "UPDATE modules
        SET view_options = IF(
          JSON_SEARCH(
            view_options->\"$.listHeaders\",
            'one',
            '$value'
          ) IS NOT NULL,
          JSON_REMOVE(
          view_options,
            CONCAT(
              '$.listHeaders',
              REPLACE(
                JSON_UNQUOTE(
                  JSON_SEARCH(
                    view_options->\"$.listHeaders\",
                    'one',
                    '$value'
                  )
                ),
                '$',
                ''
              )
            )
          ),
          view_options
        )
      WHERE id = $id;"
    );
  }

  /**
   * Adicionar módulo.
   *
   * @param object $data
   * @return boolean
   */
  public static function add(object $data): bool
  {
    $name = addslashes($data->name);
    $key = addslashes($data->key);
    $icon = addslashes($data->icon);
    $viewId = (int) $data->viewId;
    $viewOptions = json_encode($data->viewOptions);

    if (self::has($key)) {
      throw new \Exception("Já existe um módulo com essa chave.");
    }

    $viewKey = View::getKeyById($viewId);
    $viewClass = self::getViewClassOfKey($viewKey);

    $q1 = Conn::table(Table::MODULES)
      ::insert(
        ['name', '`key`', 'icon', 'modules_views_id', 'view_options'],
        ["'$name'", "'$key'", "'$icon'", $viewId, "'$viewOptions'"]
      )
      ::send();

    $moduleId = Conn::$conn->insert_id;

    $q2 = null;

    if (method_exists($viewClass, 'afterAddModule')) {
      $q2 = call_user_func([$viewClass, 'afterAddModule'], $moduleId, $key, $data->fields);
    } else {
      $q2 = true;
    }

    return $q1 && $q2;
  }

  /**
   * Exportar.
   *
   * @return boolean
   */
  public static function export(int $id)
  {
    $module = self::get($id);

    unset($module->id);
    unset($module->viewKey);
    unset($module->viewName);
    unset($module->removable);

    for ($i = 0; count($module->fields) > $i; $i++) {
      $module->fields[$i]['type'] = $module->fields[$i]['typeId'];

      unset($module->fields[$i]['id']);
      unset($module->fields[$i]['typeId']);
      unset($module->fields[$i]['typeKey']);
      unset($module->fields[$i]['options']);
    }

    return $module;
  }

  /**
   * Remover módulo por id.
   *
   * @param integer $id
   * @return boolean
   */
  public static function remove(int $id): bool
  {
    $module = Conn::table(Table::MODULES)
      ::select(['`key`', 'modules_views_id' => 'viewId'])
      ::where('id', $id)
      ::send()
      ->fetch_object();

    $viewKey = self::has($module->key);

    if (!$viewKey) {
      throw new \Exception("Não existe um módulo com essa chave.");
    }

    $viewClass = self::getViewClassOfKey($viewKey);

    $q1 = null;

    if (method_exists($viewClass, 'beforeRemoveModule')) {
      $q1 = call_user_func([$viewClass, 'beforeRemoveModule'], $id, $module->key);
    } else {
      $q1 = true;
    }

    $q2 = Conn::table(Table::MODULES)
      ::deleteWhere('id', $id)
      ::send();

    return $q1 && $q2;
  }

  /**
   * Verifica se a view foi criada no código fonte, se sim retorna a classe dela.
   *
   * @param string $viewKey
   * @return string
   */
  private static function getViewClassOfKey(string $viewKey): string
  {
    $viewDir = self::snakeToPascalCase($viewKey);

    if (!file_exists(SYSTEM_ROOT . "/app/Module/View/$viewDir/Model.php")) {
      throw new \Exception("Model da view não configurado corretamente no código fonte.");
    }

    return "Module\View\\$viewDir\Model";
  }

  /**
   * Transformar string snake_case para PascalCase.
   *
   * @param string $str
   * @return string
   */
  public static function snakeToPascalCase(string $str): string
  {
    return str_replace('_', '', ucwords($str, '_'));
  }
}