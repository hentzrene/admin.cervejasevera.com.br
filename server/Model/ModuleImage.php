<?php

namespace Model;

use Model\Conn;
use Enum\Table;
use Intervention\Image\ImageManager as Img;

class ModuleImage
{
  /**
   * Obter todas as imagens.
   *
   * @param integer $fieldId
   * @param integer $itemId
   * @return array
   */
  public static function getAll(int $fieldId, int $itemId): array
  {
    $q = Conn::table(Table::IMAGES)
      ::select(['id', 'path'])
      ::where('modules_fields_id', $fieldId)
      ::and('item_id', $itemId)
      ::send();

    return $q ? $q->fetch_all(MYSQLI_ASSOC) : [];
  }

  /**
   * Adicionar imagem.
   *
   * @param integer $moduleId
   * @param integer $fieldId
   * @param integer $itemId
   * @param array $file
   * @return string|null
   */
  public static function add(int $moduleId, int $fieldId, int $itemId, array $file): ?object
  {
    $manager = new Img(array('driver' => 'gd'));
    $make = $manager->make($file['tmp_name']);

    if (!is_dir(SYSTEM_ROOT . '/upload')) {
      mkdir(SYSTEM_ROOT . '/upload', 0755);
    }
    if (!is_dir(SYSTEM_ROOT . "/upload/$moduleId")) {
      mkdir(SYSTEM_ROOT . "/upload/$moduleId", 0755);
    }
    if (!is_dir(SYSTEM_ROOT . "/upload/$moduleId/$itemId")) {
      mkdir(SYSTEM_ROOT . "/upload/$moduleId/$itemId", 0755);
    }

    $path = "/upload/$moduleId/$itemId/" . dechex(time()) . bin2hex(random_bytes(5)) . '.png';
    $make->save(SYSTEM_ROOT . $path);

    $q = Conn::table(Table::IMAGES)
      ::insert(
        ['modules_id', 'modules_fields_id', 'item_id', 'path'],
        [$moduleId, $fieldId, $itemId, "'$path'"]
      )
      ::send();

    return $q ? (object) [
      'file' => $path,
      'id' => Conn::$conn->insert_id
    ] : NULL;
  }

  /**
   * Remover imagem por id.
   *
   * @param integer $id
   * @return boolean
   */
  public static function remove(int $id): bool
  {
    $path = Conn::table(Table::IMAGES)
      ::select('path')
      ::where('id', $id)
      ::send()
      ->fetch_row()[0];

    if (is_file(SYSTEM_ROOT . $path)) {
      unlink(SYSTEM_ROOT . $path);
    }

    $q = Conn::table(Table::IMAGES)
      ::deleteWhere('id', $id)
      ::send();

    return $q;
  }
}
