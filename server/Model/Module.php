<?php

namespace Model;

use Model\Conn;
use Config\Table;

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
    $permissions = Account::getPermissions(ACCOUNT_ID);
    if (!in_array($id, $permissions)) {
      return null;
    }

    $q = Conn::table(Table::VW_MODULES)
      ::select([
        'id',
        'name',
        '`key`',
        'icon',
        'view_options' => 'viewOptions',
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

    $module->fields = ModuleField::getAll($module->key);

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
   * Obter todos os módulos.
   *
   * @return array
   */
  public static function getAll(): array
  {
    $permissions = json_encode(Account::getPermissions(ACCOUNT_ID));

    $q = Conn::table(Table::VW_MODULES)
      ::select([
        'id',
        'name',
        '`key`',
        'icon',
        'view_options' => 'viewOptions',
        'view_key' => 'viewKey',
        'view_name' => 'viewName',
        'removable'
      ])
      ::where("JSON_CONTAINS('$permissions', CONCAT('\"', id, '\"'), '$')", 1)
      ::send();

    if (!$q) return [];

    $modules = $q->fetch_all(MYSQLI_ASSOC);

    foreach ($modules as $i => $module) {
      $modules[$i]['viewOptions'] = json_decode($module['viewOptions']);
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

    Conn::table(Table::MODULES)
      ::insert(
        ['name', '`key`', 'icon', 'modules_views_id', 'view_options'],
        ["'$name'", "'$key'", "'$icon'", $viewId, "'$viewOptions'"]
      )
      ::send();

    $moduleId = Conn::$conn->insert_id;

    Conn::table(Table::MODULES_FIELDS)
      ::insert(
        ['modules_id', 'name', '`key`', '`unique`', 'private', 'modules_fields_types_id'],
        array_map(function ($f) use ($moduleId) {
          $name = addslashes($f->name);
          $key = addslashes($f->key);
          $unique = (int) $f->unique;
          $private = (int) $f->private;
          $type = (int) $f->type;

          return [
            $moduleId, "'$name'", "'$key'", $unique, $private, $type,
          ];
        }, $data->fields),
        true
      )
      ::send();

    $sql = "CREATE TABLE mod_$key(";

    $info = ["id INT NOT NULL AUTO_INCREMENT"];
    foreach ($data->fields as $f) {
      $sqlType = ModuleFieldType::get($f->type, ['sql_type'])[0];;

      $key = addslashes($f->key);
      $r = "`$key` $sqlType DEFAULT NULL";

      if ($f->unique) $r .= " UNIQUE";

      $info[] = $r;
    }

    $info[] = "`active` INT(1) NOT NULL DEFAULT '1'";
    $info[] = "`createdAt` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP";
    $info[] = "`alteredAt` TIMESTAMP NULL DEFAULT NULL";
    $info[] = "PRIMARY KEY(id)";

    $sql .= implode(",", $info);
    $sql .= ") COLLATE='utf8_general_ci' ENGINE=InnoDB;";

    Conn::query($sql);

    return true;
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
    $permissions = Account::getPermissions($accountId);
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
   * Remover módulo por id.
   *
   * @param integer $id
   * @return boolean
   */
  public static function remove(int $id): bool
  {
    $key = Conn::table(Table::MODULES)
      ::select(['`key`'])
      ::where('id', $id)
      ::send()
      ->fetch_row()[0];

    $q1 = Conn::query("DROP TABLE mod_$key");

    $q2 = Conn::table(Table::MODULES)
      ::deleteWhere('id', $id)
      ::send();

    return $q1 && $q2;
  }
}
