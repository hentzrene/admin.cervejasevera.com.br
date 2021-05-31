<?php

namespace Custom\Model\Conn;

class LeftJoin
{
  static function on(string $column, string $value, string $op = '=')
  {
    return Conn::on($column, $value, $op);
  }

  static function where(string $column, string $value, string $op = '=')
  {
    return Conn::where($column, $value, $op);
  }

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
