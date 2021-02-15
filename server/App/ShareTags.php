<?php

namespace App;

use Core\Model\Configuration;

class ShareTags
{
  public static $defaultTags;

  public static function app()
  {
    $informations = Configuration::getConfig('informations');

    self::$defaultTags = (object) [
      'name' => $informations->name,
      'title' => $informations->name,
      'description' => $informations->description,
      'keywords' => $informations->keywords,
      'image' =>  '/img/share.png'
    ];

    define('SHARE_TAGS_DEFINEDS', true);

    return [
      '/' => [
        'title' => 'Início - ' . self::$defaultTags->name,
      ],
      '/historia' => [
        'title' => 'História - ' . self::$defaultTags->name,
      ],
      '/empresas/{itemId}' => function () {
        return [
          'title' => 'Empresa - ' . self::$defaultTags->name
        ];
      },
      '/enterprises' => [
        'title' => 'Empreendimentos - ' . self::$defaultTags->name,
      ],
      '/noticias' => [
        'title' => 'Notícias - ' . self::$defaultTags->name,
      ],
      '/noticias/{article}' => [
        'title' => 'Notícia - ' . self::$defaultTags->name
      ],
      '/videos' => [
        'title' => 'Vídeos - ' . self::$defaultTags->name
      ],
      '/videos/{video}' => [
        'title' => 'Vídeo - ' . self::$defaultTags->name
      ],
      '/obras' => [
        'title' => 'Obras - ' . self::$defaultTags->name
      ],
      '/obras/{work}' => [
        'title' => 'Obra - ' . self::$defaultTags->name
      ],
      '/classificados' => [
        'title' => 'Classificados - ' . self::$defaultTags->name,
      ],
      '/classificados/{classified}' => [
        'title' => 'Classificado - ' . self::$defaultTags->name
      ],
      '/contato' => [
        'title' => 'Contato - ' . self::$defaultTags->name
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
