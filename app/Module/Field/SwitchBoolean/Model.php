<?php

namespace Module\Field\SwitchBoolean;

class Model
{
  public static function beforeTableExport(string $moduleKey, $fieldData, object $options, array $list): array
  {

    $list = array_map(function ($c) use ($fieldData) {
      if (!is_null($c[$fieldData['key']])) {
        $c[$fieldData['key']] = (int) $c[$fieldData['key']] === 1 ? 'Sim' : 'Não';
      }
      return $c;
    }, $list);

    return $list;
  }
}
