<?php

namespace Model\ModuleView;

use Config\Table as ConfigTable;
use Model\Conn;
use Model\Module;
use Model\Request as Req;

class Table
{
  /**
   * Obter item;
   *
   * @param string $module
   * @param integer $id
   * @return object
   */
  public static function get(string $module, int $id): object
  {
    $module = addslashes($module);
    $q = Conn::table("mod_$module")
      ::select()
      ::where('id', $id)
      ::send();

    return $q ? $q->fetch_object() : (object) [];
  }

  /**
   * Obter todos os itens.
   *
   * @param string $module
   * @return array
   */
  public static function getAll(string $module): array
  {
    $module = addslashes($module);
    $fields = Req::get('fields') ? explode(',', Req::get('fields')) : "*";

    $q = Conn::table("mod_$module")
      ::select($fields)
      ::send();

    return $q ? $q->fetch_all(MYSQLI_ASSOC) : [];
  }

  /**
   * Obter todos os itens públicos.
   *
   * @param string $module
   * @return array
   */
  public static function getAllToPublic(string $module): array
  {
    $module = addslashes($module);
    $publicFields = Module::getPublicFields($module);
    $requestedFields = Req::get('fields') ? explode(',', Req::get('fields')) : null;
    $fields = [];


    if ($requestedFields) {
      foreach ($requestedFields as $field) {
        if (in_array($field, $publicFields)) {
          $fields[] = $field;
        }
      }
    } else {
      $fields = $publicFields;
    }

    $q = Conn::table("mod_$module")
      ::select($fields)
      ::where('active', 1)
      ::send();

    return $q ? $q->fetch_all(MYSQLI_ASSOC) : [];
  }

  /**
   * Adicionar item.
   *
   * @param string $module
   * @return int Id do item adicinado.
   */
  public static function add(string $module): int
  {
    $module = addslashes($module);
    Conn::table("mod_$module")
      ::insert(['active'], [0])
      ::send();

    return Conn::$conn->insert_id;
  }

  /**
   * Atualizar item.
   *
   * @param string $module
   * @param integer $id
   * @param object $data
   * @return boolean
   */
  public static function update(string $module, int $id, object $data): bool
  {
    $data = array_map(function ($v) {
      $v = addslashes($v);
      return "'$v'";
    }, (array) $data);

    $module = addslashes($module);


    Conn::table("mod_$module")
      ::update($data)
      ::where('id', $id)
      ::send();

    return true;
  }



  /**
   * Mudar status de ativo do item.
   *
   * @param string $module
   * @param integer $id
   * @param boolean $value
   * @return void
   */
  public static function toggleActive(string $module, int $id, bool $value)
  {
    $module = addslashes($module);
    Conn::table("mod_$module")
      ::update([
        'active' => (int) $value
      ])
      ::where('id', $id)
      ::send();

    return true;
  }



  /**
   * Remover item.
   *
   * @param string $module
   * @param integer $id
   * @return boolean
   */
  public static function remove(string $module, int $id): bool
  {
    $moduleId = Module::getIdByKey($module);
    $dir = SYSTEM_ROOT . "/upload/$moduleId/$id";
    if (is_dir($dir)) {
      $files = scandir($dir);
      $files = array_slice($files, 2);;

      foreach ($files as $file) {
        unlink("$dir/$file");
      }

      rmdir($dir);
    }

    $q1 = Conn::table(ConfigTable::IMAGES)
      ::deleteWhere('item_id', $id)
      ::send();

    $q2 = Conn::table("mod_$module")
      ::deleteWhere('id', $id)
      ::send();

    return $q1 && $q2;
  }
}
