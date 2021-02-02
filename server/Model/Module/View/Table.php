<?php

namespace Model\Module\View;

use Enum\Table as EnumTable;
use Model\Utility\Conn;
use Model\Module\Module;
use Model\Module\Field;
use Model\Utility\Request as Req;

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
  public static function getAll(string $module, bool $onlyPublic = false)
  {
    $module = addslashes($module);
    $fields = [];
    $page = Req::get('page') ? (int) Req::get('page') : 1;
    $itemsPerPage = Req::get('itemsPerPage') ? (int) Req::get('itemsPerPage') : PHP_INT_MAX;
    $offset = ($page - 1) * $itemsPerPage;
    $search = Req::get('search') ? addslashes(Req::get('search')) : null;
    $returnTotalItems = (int) Req::get('returnTotalItems');
    $list = null;
    $totalItems = null;

    if (!$onlyPublic) {
      $fields = Req::get('fields') ? explode(',', addslashes(Req::get('fields'))) : "*";
    } else {
      $publicFields = Field::getAllPublics($module);
      array_unshift($publicFields, 'id');
      $requestedFields = Req::get('fields') ? explode(',', Req::get('fields')) : null;

      if ($requestedFields) {
        foreach ($requestedFields as $field) {
          if (in_array($field, $publicFields)) {
            $fields[] = $field;
          }
        }
      } else {
        $fields = $publicFields;
      }
    }

    if ($search) {
      $tableHeaders = Module::getViewOptionsByKey($module)->listHeaders;

      $inStr =  'INSTR(CONCAT(' . implode(',', $tableHeaders) . "), '$search')";

      $list = Conn::table("mod_$module")
        ::select($fields)
        ::where($inStr, 0, '>');

      if ($onlyPublic) $list = $list::and('active', 1);

      $list = $list::orderBy('id', 'DESC')
        ::limit($itemsPerPage, $offset)
        ::send();

      if ($returnTotalItems) {
        $totalItems = Conn::query(
          "SELECT COUNT(*)
          FROM mod_$module
          WHERE $inStr > 0 " .
            ($onlyPublic ? "AND active = 1 " : "") .
            "ORDER BY id DESC"
        )->fetch_row()[0];
      }
    } else {
      $list = Conn::table("mod_$module")
        ::select($fields);

      if ($onlyPublic) $list = $list::where('active', 1);

      $list = $list::orderBy('id', 'DESC')
        ::limit($itemsPerPage, $offset)
        ::send();

      if ($returnTotalItems) {
        $totalItems = self::getTotalItems($module, $onlyPublic);
      }
    }

    $list = !$list ? [] : $list->fetch_all(MYSQLI_ASSOC);

    return !$returnTotalItems ? $list : (object) [
      'totalItems' => $totalItems,
      'list' => $list
    ];
  }


  /**
   * Obter a quantidade de itens.
   *
   * @param string $module
   * @return integer
   */
  public static function getTotalItems(string $module, bool $onlyPublic = false): int
  {
    $module = addslashes($module);
    $sql = "SELECT COUNT(id) FROM mod_$module";

    if ($onlyPublic) $sql .= " WHERE active = 1";

    return (int) Conn::query($sql)->fetch_row()[0];
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
    $data['alteredAt'] = "CURRENT_TIMESTAMP()";


    Conn::table("mod_$module")
      ::update($data)
      ::where('id', $id)
      ::send();

    return true;
  }

  /**
   * Atualizar propiedade do item.
   *
   * @param string $module
   * @param integer $id
   * @param string $prop
   * @param string $value
   * @return void
   */
  public static function setProp(string $module, int $id, string $prop, string $value): bool
  {
    if ($prop === 'active') {
      $value = (int) $value;
    } else {
      $value = addslashes($value);
    }

    $module = addslashes($module);

    Conn::table("mod_$module")
      ::update([
        $prop => $value
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

    $q1 = Conn::table(EnumTable::IMAGES)
      ::deleteWhere('item_id', $id)
      ::send();

    $q2 = Conn::table("mod_$module")
      ::deleteWhere('id', $id)
      ::send();

    return $q1 && $q2;
  }
}
