<?php

namespace Module\View\Newsletter;

use Core\Model\Configuration;
use Enum\Table as EnumTable;
use Core\Model\Utility\Conn;
use Core\Model\Module\Module;
use Core\Model\Module\Field;
use Core\Model\Utility\Request as Req;
use League\Flysystem\Filesystem;
use League\Flysystem\Local\LocalFilesystemAdapter;
use PHPMailer\PHPMailer\PHPMailer;

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
  public static function get(string $module, int $id): object
  {
    $module = addslashes($module);

    $fields = [];

    $fields = Req::get('fields') ? explode(',', addslashes(Req::get('fields'))) : "*";

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
  public static function getAll(string $module)
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

    $fields = Req::get('fields') ? explode(',', addslashes(Req::get('fields'))) : "*";

    if ($search) {
      $tableHeaders = Module::getViewOptionsByKey($module)->listHeaders;

      $inStr =  'INSTR(LOWER(CONCAT_WS(\'|\', ' . implode(',', $tableHeaders) . ")), LOWER('$search'))";

      $list = Conn::table("mod_$module")
        ::select($fields)
        ::where($inStr, 0, '>');

      $list = $list::orderBy('id', 'DESC')
        ::limit($itemsPerPage, $offset)
        ::send();

      if ($returnTotalItems) {
        $totalItems = Conn::query(
          "SELECT COUNT(*)
          FROM mod_$module
          WHERE $inStr > 0
          ORDER BY id DESC"
        )->fetch_row()[0];
      }
    } else {
      $list = Conn::table("mod_$module")
        ::select($fields);

      $list = $list::orderBy('id', 'DESC')
        ::limit($itemsPerPage, $offset)
        ::send();

      if ($returnTotalItems) {
        $totalItems = self::getTotalItems($module);
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
   * Transmistir mensagem.
   *
   * @param string $module
   * @param object $data
   * @return void
   */
  public static function broadcast(string $module, object $data): bool
  {
    $module = addslashes($module);

    $text = $data->text;
    $subject = $data->subject;

    $list = Conn::table("mod_$module")
      ::select(['email'])
      ::where('active', 1)
      ::send();

    $list = !$list ? [] : $list->fetch_all(MYSQLI_ASSOC);

    if (!count($list)) {
      throw new \Exception('Não há nenhum contato registrado.');
    }


    $mail = new PHPMailer(true);
    $config = Configuration::getConfig('email');

    try {
      // $mail->SMTPDebug = SMTP::DEBUG_SERVER;
      // $mail->Debugoutput = 'html';

      $mail->isSMTP();
      $mail->Host = $config->host;
      $mail->SMTPAuth = true;
      $mail->SMTPSecure = 'ssl';
      $mail->Username = $config->user;
      $mail->Password = $config->password;
      $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` also accepted
      $mail->Port = $config->smtpPort;

      $mail->setFrom($config->user, $config->name);

      foreach ($list as $row) {
        $mail->addAddress($row['email'], $row['email']);
      }

      $mail->isHTML(true);
      $mail->Subject = utf8_decode($subject);

      $mail->Body = utf8_decode($text);
      $mail->AltBody = utf8_decode('Erro: Esse cliente de email não suporta HTML');

      $mail->send();

      return true;
    } catch (\PHPMailer\PHPMailer\Exception $e) {
      \Logger::log($e->getMessage());
      http_response_code(500);
    }

    return true;
  }

  /**
   * Obter a quantidade de itens.
   *
   * @param string $module
   * @return integer
   */
  public static function getTotalItems(string $module): int
  {
    $module = addslashes($module);
    $sql = "SELECT COUNT(id) FROM mod_$module";

    return (int) Conn::query($sql)->fetch_row()[0];
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
