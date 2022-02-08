<?php

namespace Module\Field\ImageFile;

use Core\Model\Module\Field;
use Core\Model\Utility\Conn;
use Enum\Table;
use Intervention\Image\ImageManager as Img;
use League\Flysystem\Filesystem;
use League\Flysystem\Local\LocalFilesystemAdapter as Local;

class Model
{
  const CONVERT_MIME_TYPES = ['image/png', 'image/jpeg'];

  const MASK_SIZE = .20;

  const MASK_OPACITY = 50;

  /**
   * Obter todas as imagens.
   *
   * @param integer $fieldId
   * @param integer $itemId
   * @return array
   */
  public static function getAllItems(int $fieldId, int $itemId): array
  {
    $q = Conn::table(Table::IMAGES)
      ::select(['id', 'path', '`order`', 'title'])
      ::where('modules_fields_id', $fieldId)
      ::and('item_id', $itemId)
      ::send();

    return $q ? $q->fetch_all(MYSQLI_ASSOC) : [];
  }

  public static function getItem(int $id)
  {
    $q = Conn::table(Table::IMAGES)
      ::select()
      ::where('id', $id)
      ::send();

    if (!$q) {
      throw new \Exception();
    }

    return $q->fetch_object();
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
  public static function addItem(int $moduleId, int $fieldId, int $itemId, array $file): ?object
  {
    $options = Field::getOptions($fieldId);

    $path = '';
    $ext = pathinfo($file['name'])['extension'];

    if (!is_dir(SYSTEM_ROOT . '/upload')) {
      mkdir(SYSTEM_ROOT . '/upload', 0755);
    }
    if (!is_dir(SYSTEM_ROOT . "/upload/$moduleId")) {
      mkdir(SYSTEM_ROOT . "/upload/$moduleId", 0755);
    }
    if (!is_dir(SYSTEM_ROOT . "/upload/$moduleId/$itemId")) {
      mkdir(SYSTEM_ROOT . "/upload/$moduleId/$itemId", 0755);
    }

    if (in_array(mime_content_type($file['tmp_name']), self::CONVERT_MIME_TYPES)) {
      $manager = new Img(array('driver' => 'gd'));
      $img = $manager->make($file['tmp_name']);

      if ($options->mask) {
        $mask = $manager->make(SYSTEM_ROOT . $options->mask);
        $mask->resize($img->width() * self::MASK_SIZE, null, function ($constraint) {
          $constraint->aspectRatio();
        });

        $mask->opacity(self::MASK_OPACITY);

        $img->insert($mask, 'center');
      }

      $path = "/upload/$moduleId/$itemId/" . dechex(time()) . bin2hex(random_bytes(5)) . ".$ext";

      $img->save(SYSTEM_ROOT . $path);
    } else {
      $adapter = new Local(SYSTEM_ROOT);
      $filesystem = new Filesystem($adapter);

      $path = "/upload/$moduleId/$itemId/" . dechex(time()) . bin2hex(random_bytes(5)) . ".$ext";
      $stream = fopen($file['tmp_name'], 'r+');

      $filesystem->writeStream($path, $stream);

      if (is_resource($stream)) {
        fclose($stream);
      }
    }

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

  public static function updateOrder(array $images)
  {
    foreach ($images as $img) {
      Conn::table(Table::IMAGES)
        ::update(['`order`' => $img->order])
        ::where('id', $img->id)
        ::send();
    }

    return true;
  }

  /**
   * Alterar título.
   *
   * @param integer $id
   * @param string $title
   * @return boolean
   */
  public static function updateTitle(int $id, string $title): bool
  {
    $title = addslashes($title);

    $q = Conn::table(Table::IMAGES)
      ::update(['title' => "'$title'"])
      ::where('id', $id)
      ::send();

    return (bool) $q;
  }

  /**
   * Remover imagem por id.
   *
   * @param integer $id
   * @return boolean
   */
  public static function removeItem(int $id): bool
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

  public static function beforeAdd(string $moduleKey, string $key, string $sqlType, int $unique): bool
  {

    $sql = "ALTER TABLE `mod_$moduleKey` ADD `$key` $sqlType DEFAULT NULL";

    if ($unique) $sql .= " UNIQUE";

    $sql .= ", ADD CONSTRAINT `mod_{$moduleKey}_$key`
    FOREIGN KEY (`$key`)
    REFERENCES `images`(`id`)
    ON DELETE SET NULL
    ON UPDATE SET NULL";

    return (bool) Conn::query($sql);
  }

  public static function beforeSetTypeTo(string $moduleKey, string $key, string $sqlType): bool
  {
    return (bool) Conn::query(
      "ALTER TABLE `mod_$moduleKey`
      MODIFY `$key` $sqlType,
      ADD CONSTRAINT `mod_{$moduleKey}_$key`
      FOREIGN KEY (`$key`)
      REFERENCES `images`(`id`)
      ON DELETE SET NULL
      ON UPDATE SET NULL"
    );
  }

  public static function beforeSetTypeFrom(string $moduleKey, int $id, string $key): bool
  {
    $q1 = Conn::query(
      "ALTER TABLE `mod_$moduleKey`
      DROP FOREIGN KEY `mod_{$moduleKey}_$key`;"
    );

    $q2 = Conn::table(Table::IMAGES)
      ::deleteWhere('modules_fields_id', $id)
      ::send();

    return $q1 && $q2;
  }

  public static function beforeRemove(string $moduleKey, string $key): bool
  {
    return (bool) Conn::query(
      "ALTER TABLE `mod_$moduleKey`
      DROP FOREIGN KEY `mod_{$moduleKey}_$key`;"
    );
  }
}
