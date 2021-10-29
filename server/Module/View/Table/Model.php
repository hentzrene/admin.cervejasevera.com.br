<?php

namespace Module\View\Table;

use Enum\Table as EnumTable;
use Core\Model\Utility\Conn;
use Core\Model\Module\Module;
use Core\Model\Module\Field;
use Core\Model\Utility\Request as Req;
use League\Flysystem\Filesystem;
use League\Flysystem\Local\LocalFilesystemAdapter;

class Model
{
  const MAX_ITEMS_PER_PAGE = 100;

  /**
   * Obter item;
   *
   * @param string $module
   * @param integer $id
   * @return object
   */
  public static function get(string $module, int $id, bool $onlyPublic = false): object
  {
    $module = addslashes($module);

    $fields = [];

    if (!$onlyPublic) {
      $fields = Req::get('fields') ? explode(',', addslashes(Req::get('fields'))) : "*";
    } else {
      $publicFields = Field::getAllPublics($module);
      array_unshift($publicFields, 'id');
      $requestedFields = Req::get('fields') ? explode(',', Req::get('fields')) : null;

      if ($requestedFields) {
        foreach ($requestedFields as $field) {
          if (in_array($field, $publicFields)) {
            $fields[] = $field;
          }
        }
      } else {
        $fields = $publicFields;
      }
    }

    $q = Conn::table("mod_$module")
      ::select($fields)
      ::where('id', $id)
      ::send();

    return $q ? $q->fetch_object() : (object) [];
  }

  /**
   * Obter todos os itens.
   *
   * @param string $module
   * @return array
   */
  public static function getAll(string $module, bool $onlyPublic = false)
  {
    $module = addslashes($module);
    $fields = [];
    $page = Req::get('page') ? (int) Req::get('page') : 1;

    $itemsPerPage = Req::get('itemsPerPage') ? (int) Req::get('itemsPerPage') : self::MAX_ITEMS_PER_PAGE;
    if ($itemsPerPage > self::MAX_ITEMS_PER_PAGE) $itemsPerPage = self::MAX_ITEMS_PER_PAGE;

    $offset = ($page - 1) * $itemsPerPage;
    $search = Req::get('search') ? addslashes(Req::get('search')) : null;
    $returnTotalItems = (int) Req::get('returnTotalItems');
    $list = null;
    $totalItems = null;

    if (!$onlyPublic) {
      $fields = Req::get('fields') ? explode(',', addslashes(Req::get('fields'))) : "*";
    } else {
      $publicFields = Field::getAllPublics($module);
      array_unshift($publicFields, 'id');
      $requestedFields = Req::get('fields') ? explode(',', Req::get('fields')) : null;

      if ($requestedFields) {
        foreach ($requestedFields as $field) {
          if (in_array($field, $publicFields)) {
            $fields[] = $field;
          }
        }
      } else {
        $fields = $publicFields;
      }
    }

    if ($search) {
      $tableHeaders = Module::getViewOptionsByKey($module)->listHeaders;

      $inStr =  'INSTR(LOWER(CONCAT_WS(\'|\', ' . implode(',', $tableHeaders) . ")), LOWER('$search'))";

      $list = Conn::table("mod_$module")
        ::select($fields)
        ::where($inStr, 0, '>');

      if ($onlyPublic) $list = $list::and('active', 1);

      $list = $list::orderBy('id', 'DESC')
        ::limit($itemsPerPage, $offset)
        ::send();

      if ($returnTotalItems) {
        $totalItems = Conn::query(
          "SELECT COUNT(*)
          FROM mod_$module
          WHERE $inStr > 0 " .
            ($onlyPublic ? "AND active = 1 " : "") .
            "ORDER BY id DESC"
        )->fetch_row()[0];
      }
    } else {
      $list = Conn::table("mod_$module")
        ::select($fields);

      if ($onlyPublic) {
        $list = $list::where('active', 1)
          ::and('IF(showFrom, CURRENT_TIMESTAMP > showFrom, TRUE)', 1)
          ::and('IF(showUp, CURRENT_TIMESTAMP < showUp, TRUE)', 1);
      }

      $list = $list::orderBy('id', 'DESC')
        ::limit($itemsPerPage, $offset)
        ::send();

      if ($returnTotalItems) {
        $totalItems = self::getTotalItems($module, $onlyPublic);
      }
    }

    $list = !$list ? [] : $list->fetch_all(MYSQLI_ASSOC);

    return !$returnTotalItems ? $list : (object) [
      'totalItems' => $totalItems,
      'list' => $list
    ];
  }

  /**
   * Exportar itens.
   *
   * @param string $module
   * @param object $options
   * @return void
   */
  public static function export(string $module, object $options): bool
  {
    $module = addslashes($module);
    $exportFieldsKeys = array_map(function ($v) {
      return addslashes($v);
    }, $options->fields);

    $fields = Field::getAll($module);

    $exportFieldsData = array_filter(
      $fields,
      fn ($v) => in_array($v['key'], $exportFieldsKeys)
    );

    $header = array_map(
      fn ($v) => $v['name'],
      $exportFieldsData
    );

    if (in_array('id', $exportFieldsKeys)) {
      $key = array_search('id', $exportFieldsKeys);
      array_splice($header, $key, 0, 'Id');
    }

    $list = Conn::table("mod_$module")
      ::select($exportFieldsKeys)
      ::where('active', 1)
      ::and('IF(showFrom, CURRENT_TIMESTAMP > showFrom, TRUE)', 1)
      ::and('IF(showUp, CURRENT_TIMESTAMP < showUp, TRUE)', 1)
      ::send();

    $list = !$list ? [] : $list->fetch_all(MYSQLI_ASSOC);


    foreach ($exportFieldsData as $exportFieldData) {
      $fieldClass = Field::getFieldClassOfTypeId((int) $exportFieldData['typeId']);

      if (method_exists($fieldClass, 'beforeTableExport')) {
        $list = call_user_func([$fieldClass, 'beforeTableExport'], $module, $exportFieldData, $options, $list);
      }
    }

    if ($options->type === 'Excel') {
      array_unshift($list, $header);

      $spreadSheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
      $workSheet = $spreadSheet->getActiveSheet();

      $workSheet->fromArray($list);

      $spreadSheet->getProperties()
        ->setTitle('export');

      header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

      $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadSheet, 'Xlsx');
      $writer->save('php://output');

      return true;
    } else {
      return false;
    }
  }

  /**
   * Obter a quantidade de itens.
   *
   * @param string $module
   * @return integer
   */
  public static function getTotalItems(string $module, bool $onlyPublic = false): int
  {
    $module = addslashes($module);
    $sql = "SELECT COUNT(id) FROM mod_$module";

    if ($onlyPublic) $sql .= " WHERE active = 1";

    return (int) Conn::query($sql)->fetch_row()[0];
  }

  /**
   * Adicionar item.
   *
   * @param string $module
   * @return int Id do item adicinado.
   */
  public static function add(string $module): int
  {
    $module = addslashes($module);
    Conn::table("mod_$module")
      ::insert(['active'], [0])
      ::send();

    return Conn::$conn->insert_id;
  }

  /**
   * Atualizar item.
   *
   * @param string $module
   * @param integer $id
   * @param object $data
   * @return boolean
   */
  public static function update(string $module, int $id, object $data): bool
  {
    $data = array_map(function ($v) {
      $v = addslashes($v);
      return $v === '' ? 'NULL'  : "'$v'";
    }, (array) $data);

    $module = addslashes($module);
    $data['alteredAt'] = "CURRENT_TIMESTAMP()";


    Conn::table("mod_$module")
      ::update($data)
      ::where('id', $id)
      ::send();

    return true;
  }

  /**
   * Atualizar propiedade do item.
   *
   * @param string $module
   * @param integer $id
   * @param string $prop
   * @param string $value
   * @return void
   */
  public static function setProp(string $module, int $id, string $prop, string $value): bool
  {
    if (!$value) {
      $value = 'NULL';
    } else if ($prop === 'active') {
      $value = (int) $value;
    } else {
      $value = "'" . addslashes($value) . "'";
    }

    $module = addslashes($module);

    Conn::table("mod_$module")
      ::update([
        $prop => $value
      ])
      ::where('id', $id)
      ::send();

    return true;
  }

  /**
   * Remover item.
   *
   * @param string $module
   * @param integer $id
   * @return boolean
   */
  public static function remove(string $module, int $id): bool
  {
    $moduleId = Module::getIdByKey($module);
    $dir = "/upload/$moduleId/$id";
    if (is_dir($dir)) {
      $adapter = new LocalFilesystemAdapter(SYSTEM_ROOT);
      $filesystem = new Filesystem($adapter);
      $filesystem->deleteDirectory($dir);
    }

    $q1 = Conn::table(EnumTable::IMAGES)
      ::deleteWhere('item_id', $id)
      ::send();

    $q2 = Conn::table("mod_$module")
      ::deleteWhere('id', $id)
      ::send();

    return $q1 && $q2;
  }

  /**
   * Adicionar módulo.
   *
   * @param integer $id
   * @param string $key
   * @param array $fields
   * @return boolean
   */
  public static function afterAddModule(int $id, string $key, array $fields): bool
  {
    Conn::query(
      "CREATE TABLE mod_$key(
        id INT NOT NULL AUTO_INCREMENT,
        `active` INT(1) NOT NULL DEFAULT '1',
        `createdAt` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        `alteredAt` TIMESTAMP NULL DEFAULT NULL,
        `showFrom` DATETIME NULL DEFAULT NULL,
        `showUp` DATETIME NULL DEFAULT NULL,
        PRIMARY KEY(id)
      ) COLLATE='utf8_general_ci' ENGINE=InnoDB;"
    );

    foreach ($fields as $f) {
      Field::add($f, $id);
    }

    return true;
  }

  /**
   * Remover módulo.
   *
   * @param integer $id
   * @param string $key
   * @return boolean
   */
  public static function beforeRemoveModule(int $id, string $key): bool
  {
    return (bool) Conn::query("DROP TABLE mod_$key");
  }
}
