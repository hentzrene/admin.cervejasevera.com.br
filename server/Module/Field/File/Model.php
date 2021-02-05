<?php

namespace Module\Field\File;

use Core\Model\Utility\Conn;
use Enum\Table;
use League\Flysystem\Filesystem;
use League\Flysystem\Local\LocalFilesystemAdapter as Local;

class Model
{
  /**
   * Obter todas os arquivos.
   *
   * @param integer $fieldId
   * @param integer $itemId
   * @return array
   */
  public static function getAllItems(int $fieldId, int $itemId): array
  {
    $q = Conn::table(Table::FILES)
      ::select(['id', 'title', 'path'])
      ::where('modules_fields_id', $fieldId)
      ::and('item_id', $itemId)
      ::send();

    return $q ? $q->fetch_all(MYSQLI_ASSOC) : [];
  }

  /**
   * Adicionar arquivo.
   *
   * @param integer $moduleId
   * @param integer $fieldId
   * @param integer $itemId
   * @param string $title
   * @param array $file
   * @return string|null
   */
  public static function addItem(int $moduleId, int $fieldId, int $itemId, string $title, array $file): ?object
  {
    $title = addslashes($title);

    $adapter = new Local(SYSTEM_ROOT);
    $filesystem = new Filesystem($adapter);

    $name = dechex(time()) .  bin2hex(random_bytes(5)) . '.' . pathinfo($file['name'])['extension'];
    $path = "/upload/$moduleId/$itemId/$name";
    $stream = fopen($file['tmp_name'], 'r+');

    if (!is_dir(SYSTEM_ROOT . '/upload')) {
      mkdir(SYSTEM_ROOT . '/upload', 0755);
    }
    if (!is_dir(SYSTEM_ROOT . "/upload/$moduleId")) {
      mkdir(SYSTEM_ROOT . "/upload/$moduleId", 0755);
    }
    if (!is_dir(SYSTEM_ROOT . "/upload/$moduleId/$itemId")) {
      mkdir(SYSTEM_ROOT . "/upload/$moduleId/$itemId", 0755);
    }

    $filesystem->writeStream($path, $stream);

    if (is_resource($stream)) {
      fclose($stream);
    }

    $q = Conn::table(Table::FILES)
      ::insert(
        ['modules_id', 'modules_fields_id', 'item_id', 'path', 'title'],
        [$moduleId, $fieldId, $itemId, "'$path'", "'$title'"]
      )
      ::send();

    return $q ? (object) [
      'file' => $path,
      'title' => $title,
      'id' => Conn::$conn->insert_id
    ] : NULL;
  }

  /**
   * Definir título.
   *
   * @param integer $fileId
   * @param string $value
   * @return array
   */
  public static function setItemTitle(int $fileId, string $value): bool
  {
    $value = addslashes($value);

    return (bool) Conn::table(Table::FILES)
      ::update(['title' => "'$value'"])
      ::where('id', $fileId)
      ::send();
  }

  /**
   * Remover arquivo por id.
   *
   * @param integer $id
   * @return boolean
   */
  public static function removeItem(int $id): bool
  {
    $path = Conn::table(Table::FILES)
      ::select('path')
      ::where('id', $id)
      ::send()
      ->fetch_row()[0];

    if (is_file(SYSTEM_ROOT . $path)) {
      unlink(SYSTEM_ROOT . $path);
    }

    $q = Conn::table(Table::FILES)
      ::deleteWhere('id', $id)
      ::send();

    return $q;
  }
}
