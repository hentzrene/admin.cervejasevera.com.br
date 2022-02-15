<?php

namespace App;

class ShareTags
{
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
