<?php

namespace Custom\Model\Conn;

class Conn
{
  private static $conn = null;

  private static $table = null;

  private static $sql = '';

  private static $where = false;

  private static $on = false;

  public static function init(): void
  {
    Conn::$conn = new \Mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
    Conn::$conn->set_charset('utf8');

    register_shutdown_function(
      function () {
        Conn::$conn->close();
      }
    );

    if (Conn::$conn->errno) {
      throw new \Exception(Conn::$conn->error);
    }
  }

  public static function query(string $sql)
  {
    Conn::$sql = '';

    if (is_null(Conn::$conn)) {
      Conn::init();
    }

    $q = Conn::$conn->query($sql);

    if (Conn::$conn->errno) {
      throw new \Exception(Conn::$conn->error);
    }

    if (!Conn::$conn->affected_rows > 0 && !$q->num_rows && $q !== true) {
      return null;
    }

    return $q;
  }

  public static function table(string $table): ?Object
  {
    Conn::$sql = '';
    Conn::$where = false;
    Conn::$table = $table;

    return new Table();
  }

  public static function select($columns = "*"): ?Object
  {
    if (!Conn::$table) {
      return null;
    }

    Conn::$where = false;
    Conn::$sql = 'SELECT';


    if (is_string($columns)) {
      Conn::$sql .= " $columns";
    } else if (is_array($columns)) {
      foreach ($columns as $k => $v) {
        if (is_int($k)) {
          Conn::$sql .= " $v,";
        } else {
          Conn::$sql .= " $k as $v,";
        }
      }
      Conn::$sql = substr(Conn::$sql, 0, -1);
    } else {
      return null;
    }


    Conn::$sql .= ' FROM ' . Conn::$table;

    return new Select();
  }

  public static function selectWhereId(int $id): ?Object
  {
    if (!Conn::$table) {
      return null;
    }

    Conn::$where = false;
    Conn::$sql = 'SELECT * FROM ' . Conn::$table . " WHERE id = $id";


    return new SelectWhereId();
  }

  public static function leftJoin(string $table): ?Object
  {
    if (!Conn::$sql) {
      return null;
    }

    Conn::$sql .= " LEFT JOIN $table";

    return new LeftJoin;
  }

  public static function on(string $column, string $value, $op = '='): ?Object
  {
    if (!Conn::$sql) {
      return null;
    }

    Conn::$on = true;
    Conn::$sql .= " ON $column $op $value";

    return new On;
  }

  public static function where(string $column, string $value, $op = '='): ?Object
  {
    if (!Conn::$table || !Conn::$sql) {
      return null;
    }

    Conn::$where = true;
    Conn::$sql .= " WHERE $column $op $value";

    return new Where;
  }

  public static function and(string $column, string $value, $op = '='): ?Object
  {
    if (!Conn::$table || !Conn::$sql || !Conn::$where) {
      var_dump(Conn::$sql);
      die();
      return null;
    }

    Conn::$sql .= " AND $column $op $value";

    return new WhereAnd();
  }

  public static function or(string $column, string $value, $op = '='): ?Object
  {
    if (!Conn::$table || !Conn::$sql || !Conn::$where) {
      return null;
    }

    Conn::$sql .= " OR $column $op $value";

    return new WhereOr();
  }

  public static function limit(int $limit, int $offset = 0): ?Object
  {
    if (!Conn::$table || !Conn::$sql) {
      return null;
    }

    Conn::$sql .= " LIMIT $limit OFFSET $offset";

    return new Limit();
  }

  public static function orderBy(string $column, string $direction): ?Object
  {
    if (!Conn::$table || !Conn::$sql) {
      return null;
    }

    Conn::$sql .= " ORDER BY $column $direction";

    return new OrderBy();
  }

  public static function insert(array $columns, array $values, $multi = false): ?Object
  {
    if (!Conn::$table) {
      return null;
    }

    Conn::$sql = "INSERT INTO " . Conn::$table;

    $columns = implode(', ', $columns);
    Conn::$sql .= "($columns) VALUES";

    if (!$multi) {
      $values = implode(', ', $values);
      Conn::$sql .= "($values)";
    } else {
      foreach ($values as $v) {
        Conn::$sql .= '(' . implode(', ', $v) . '),';
      }
      Conn::$sql = substr(Conn::$sql, 0, -1);
    }

    return new Insert();
  }

  public static function update($columns): ?Object
  {
    if (!Conn::$table) {
      return null;
    }

    Conn::$sql = "UPDATE " . Conn::$table .  " SET ";

    foreach ($columns as $column => $value) {
      Conn::$sql .= "$column = $value,";
    }

    Conn::$sql = substr(Conn::$sql, 0, -1);

    return new Update();
  }

  public static function deleteWhere($column, $value, $op = "="): ?Object
  {
    if (!Conn::$table) {
      return null;
    }

    Conn::$sql = "DELETE FROM " . Conn::$table;

    return Conn::where($column, $value, $op);
  }

  public static function send()
  {
    if (!Conn::$table || !Conn::$sql) {
      return null;
    }

    return Conn::query(Conn::$sql);
  }
}
