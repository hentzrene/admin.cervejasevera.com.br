<?php

namespace Custom\Model;

use Core\Model\Utility\Conn;

class Content
{
  public static function getAllItems(string $moduleKey, array $columns): array
  {
    $q = Conn::table("mod_$moduleKey")
      ::select($columns)
      ::where('active', 1)
      ::and('IF(showFrom, CURRENT_TIMESTAMP > showFrom, TRUE)', 1)
      ::and('IF(showUp, CURRENT_TIMESTAMP < showUp, TRUE)', 1)
      ::orderBy('id', 'DESC')
      ::send();

    return $q ? $q->fetch_all(MYSQLI_ASSOC) : [];
  }

  public static function getItem(string $moduleKey, array $columns, int $itemId)
  {
    $q = Conn::table("mod_$moduleKey")
      ::select($columns)
      ::where('id', $itemId)
      ::orderBy('id', 'DESC')
      ::send();

    return $q ? $q->fetch_object() : (object) [];
  }
}
