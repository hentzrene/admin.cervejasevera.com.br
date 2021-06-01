<?php

namespace Custom\Model\Conn;

class SelectWhereId
{
  static function limit(int $limit, int $offset = 0)
  {
    return Conn::limit($limit, $offset);
  }

  static function orderBy(string $column, string $direction)
  {
    return Conn::orderBy($column, $direction);
  }

  static function send()
  {
    return Conn::send();
  }
}
