<?php

namespace Model;

class Content
{

  /**
   * Obter dados de uma galeria.
   *
   * @param int $gallerieId
   * @return array
   */
  public static function getGallerie(int $gallerieId): object
  {
    $gallerie = self::get(['titulo' => 'title'], $gallerieId);

    $imgs = Conn::table('admin_fotos')
      ::select(['caminho' => "img"])
      ::where('cod_item', $gallerieId)
      ::send();

    if (!$imgs) {
      throw new \Exception('NO CONTENT');
    }

    $imgs = $imgs->fetch_all(MYSQLI_NUM);

    $imgs = array_map(function ($row) {
      return $row[0];
    }, $imgs);

    $gallerie->imgs = $imgs;

    return $gallerie;
  }
}
