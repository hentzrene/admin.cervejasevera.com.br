<?php

namespace Module\Field\Collection;

class Model
{
  public static function beforeTableExport(string $moduleKey, $fieldData, object $options, array $list): array
  {
    $list = array_map(function ($c) use ($fieldData) {
      if (!is_null($c[$fieldData['key']])) {
        $c[$fieldData['key']] = $c[$fieldData['key']] ? implode(', ', json_decode($c[$fieldData['key']]))  : '';
      }
      return $c;
    }, $list);

    return $list;
  }
}
