<?php

namespace Custom\Model\Conn;

class Update
{
  static function where(string $column, string $value, string $op = '=')
  {
    return Conn::where($column, $value, $op);
  }

  static function send()
  {
    return Conn::send();
  }
}
