<?php

namespace Custom\Model\Conn;

class Table
{
  static function select($columns = "*")
  {
    return Conn::select($columns);
  }

  static function insert(array $columns, array $values, $multi = false)
  {
    return Conn::insert($columns, $values, $multi);
  }

  static function selectWhereId(int $id)
  {
    return Conn::selectWhereId($id);
  }

  static function deleteWhere($column, $value, $op = "="): ?Object
  {
    return Conn::deleteWhere($column, $value, $op);
  }

  static function update($columns)
  {
    return Conn::update($columns);
  }
}
