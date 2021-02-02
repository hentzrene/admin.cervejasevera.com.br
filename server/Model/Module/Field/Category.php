<?php

namespace Model\Module\Field;

use Enum\Table;
use Model\Utility\Conn;

class Category
{
  public static function beforeAdd(string $moduleKey, string $key, string $sqlType, int $unique): bool
  {
    $sql = "ALTER TABLE mod_$moduleKey ADD $key $sqlType DEFAULT NULL";

    if ($unique) $sql .= " UNIQUE";

    $sql .= ", ADD CONSTRAINT mod_{$moduleKey}_$key
      FOREIGN KEY ($key) REFERENCES categories(id)";

    return (bool) Conn::query($sql);
  }

  public static function beforeSetTypeTo(string $moduleKey, string $key, string $sqlType): bool
  {
    return (bool) Conn::query(
      "ALTER TABLE mod_$moduleKey
      MODIFY $key $sqlType,
      ADD CONSTRAINT mod_{$moduleKey}_$key FOREIGN KEY ($key) REFERENCES categories(id)"
    );
  }

  public static function beforeSetTypeFrom(string $moduleKey, int $id, string $key): bool
  {
    $q1 = Conn::query(
      "ALTER TABLE mod_$moduleKey
      DROP FOREIGN KEY mod_{$moduleKey}_$key;"
    );

    $q2 = Conn::table(Table::CATEGORIES)
      ::deleteWhere('modules_fields_id', $id)
      ::send();

    return $q1 && $q2;
  }

  public static function beforeRemove(string $moduleKey, string $key): bool
  {
    return (bool) Conn::query(
      "ALTER TABLE mod_$moduleKey
      DROP FOREIGN KEY mod_{$moduleKey}_$key;"
    );
  }
}
