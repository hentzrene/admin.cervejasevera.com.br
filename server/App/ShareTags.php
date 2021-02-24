<?php

namespace App;

use Module\View\Item\Model as ItemModel;

class ShareTags
{
  public static $defaultTags;

  public static function app()
  {
    $_GET['fields'] = 'name,description,keywords,share';
    $informations = ItemModel::get('informations');

    self::$defaultTags = (object) [
      'name' => $informations->name,
      'title' => $informations->name,
      'description' => $informations->description,
      'keywords' => $informations->keywords,
      'image' =>  $informations->share
    ];

    define('SHARE_TAGS_DEFINEDS', true);

    return [
      '/' => [
        'title' => 'MRX Websites' . self::$defaultTags->name,
      ],
    ];
  }

  public static function admin(): void
  {
    define('SHARE_TAGS_DEFINEDS', true);

    define('SHARE_TAG_NAME', 'Painel de Administração - MRX Web Sites');
    define('SHARE_TAG_TITLE', 'Painel de Administração - MRX Web Sites');
    define('SHARE_TAG_DESCRIPTION', '');
    define('SHARE_TAG_KEYWORDS', '');
    define('SHARE_TAG_IMAGE', '');

    require __DIR__ . '/Vue.php';
  }

  public static function setup(): void
  {
    define('SHARE_TAGS_DEFINEDS', true);

    define('SHARE_TAG_NAME', 'Setup - MRX Web Sites');
    define('SHARE_TAG_TITLE', 'Setup - MRX Web Sites');
    define('SHARE_TAG_DESCRIPTION', '');
    define('SHARE_TAG_KEYWORDS', '');
    define('SHARE_TAG_IMAGE', '');

    require __DIR__ . '/Vue.php';
  }
}
