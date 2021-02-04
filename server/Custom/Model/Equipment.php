<?php

namespace Custom\Model;

use Model\Utility\Conn;

class Equipment
{
  public static function getAll()
  {
    $q = Conn::table('mod_equipments')
      ::select(['id', 'title', 'kw'])
      ::where('active', 1)
      ::send();

    return $q ? $q->fetch_all(MYSQLI_ASSOC) : [];
  }
}
