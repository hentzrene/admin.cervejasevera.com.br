<?php

namespace Module\View\Item;

use Core\Model\Utility\Conn;
use Core\Model\Module\Field;
use Core\Model\Utility\Request as Req;

class Model
{
  /**
   * Obter item;
   *
   * @param string $module
   * @return object
   */
  public static function get(string $module): object
  {
    $fields = [];

    $fields = Req::get('fields') ? explode(',', addslashes(Req::get('fields'))) : "*";

    $module = addslashes($module);
    $q = Conn::table("mod_$module")
      ::select($fields)
      ::where('id', 1)
      ::send();

    return $q ? $q->fetch_object() : (object) [];
  }


  /**
   * Atualizar item.
   *
   * @param string $module
   * @param integer $id
   * @param object $data
   * @return boolean
   */
  public static function update(string $module, object $data): bool
  {
    $data = array_map(function ($v) {
      $v = addslashes($v);
      return $v === '' ? 'NULL'  : "'$v'";
    }, (array) $data);

    $module = addslashes($module);
    $data['alteredAt'] = "CURRENT_TIMESTAMP()";


    Conn::table("mod_$module")
      ::update($data)
      ::where('id', 1)
      ::send();

    return true;
  }

  /**
   * Atualizar propriedade do item.
   *
   * @param string $module
   * @param integer $id
   * @param string $prop
   * @param string $value
   * @return void
   */
  public static function setProp(string $module, string $prop, string $value): bool
  {
    if (!$value) {
      $value = 'NULL';
    } else if ($prop === 'active') {
      $value = (int) $value;
    } else {
      $value = "'" . addslashes($value) . "'";
    }

    $module = addslashes($module);

    Conn::table("mod_$module")
      ::update([
        $prop => $value
      ])
      ::where('id', 1)
      ::send();

    return true;
  }


  /**
   * Adicionar módulo.
   *
   * @param integer $id
   * @param string $key
   * @param array $fields
   * @return boolean
   */
  public static function afterAddModule(int $id, string $key, array $fields): bool
  {
    Conn::query(
      "CREATE TABLE mod_$key(
        id INT NOT NULL AUTO_INCREMENT,
        `createdAt` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        `alteredAt` TIMESTAMP NULL DEFAULT NULL,
        `showFrom` DATETIME NULL DEFAULT NULL,
        `showUp` DATETIME NULL DEFAULT NULL,
        PRIMARY KEY(id)
      ) COLLATE='utf8_general_ci' ENGINE=InnoDB;"
    );

    foreach ($fields as $f) {
      Field::add($f, $id);
    }

    Conn::table("mod_$key")
      ::insert([], [])
      ::send();

    return true;
  }

  /**
   * Remover módulo.
   *
   * @param integer $id
   * @param string $key
   * @return boolean
   */
  public static function beforeRemoveModule(int $id, string $key): bool
  {
    return (bool) Conn::query("DROP TABLE mod_$key");
  }
}
