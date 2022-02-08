<?php

namespace App;

use Core\Model\Utility\Response;
use Custom\Model\Core\DB;
use Module\View\Item\Model as ItemModel;
use Module\Field\ImageFile\Model as ImageFileModel;

class ShareTags
{
  public static $defaultTags;

  public static function app()
  {
    $_GET['fields'] = 'name,description,keywords,share';
    $informations = ItemModel::get('informations');

    $share = '';
    if ($informations->share) {
      $share = ImageFileModel::getItem($informations->share)->path;
    }

    self::$defaultTags = (object) [
      'name' => $informations->name,
      'title' => $informations->name,
      'description' => $informations->description,
      'keywords' => $informations->keywords,
      'image' =>  $share
    ];

    define('SHARE_TAGS_DEFINEDS', true);

    return [
      '/' => [
        'title' => 'Início - ' . self::$defaultTags->name,
      ],
      '/sobre' => [
        'title' => 'Sobre - ' . self::$defaultTags->name,
      ],
      '/noticias' => [
        'title' => 'Notícias - ' . self::$defaultTags->name,
      ],
      '/noticias/{category}' => function ($d) {
        $list = DB::table('mod_articles_categories')
          ->select('id', 'title')
          ->get();

        $title = null;

        foreach ($list as $category) {
          if (ShareTags::normalize($category->title) === $d['category']) {
            $title = $category->title;
            break;
          }
        }

        return [
          'title' => "$title - " . self::$defaultTags->name,
        ];
      },
      '/noticias/{category}/{item}' => function ($d) {
        try {
          $itemId =  ShareTags::getItemIdFromUrl($d['item']);
          $item = ShareTags::getItemFromId($itemId, 'articles', ['title', 'img']);

          $img = '';
          if ($item->img) {
            $img = ImageFileModel::getItem($item->img)->path;
          }

          return [
            'title' => html_entity_decode("$item->title - " . self::$defaultTags->name),
            'image' => BASE . $img,
          ];
        } catch (\Exception $err) {
          Response::status(404);
          Response::send();
        }
      },
      '/videos' => [
        'title' => 'Vídeos - ' . self::$defaultTags->name,
      ],
      '/videos/{item}' => function ($d) {
        try {
          $itemId =  ShareTags::getItemIdFromUrl($d['item']);
          $item = ShareTags::getItemFromId($itemId, 'videos', ['title', 'link']);

          preg_match(
            "/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:m\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user|shorts)\/))([^\?&\"'>]+)/",
            $item->link,
            $matches
          );

          return [
            'title' => html_entity_decode("$item->title - " . self::$defaultTags->name),
            'image' => "http://img.youtube.com/vi/{$matches[1]}/hqdefault.jpg"
          ];
        } catch (\Exception $err) {
          Response::status(404);
          Response::send();
        }
      },
      '/contato' => [
        'title' => 'Contato - ' . self::$defaultTags->name,
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

  public static function getItemIdFromUrl(string $url)
  {
    preg_match('/^.*-([0-9]+)$/', $url, $matches, PREG_OFFSET_CAPTURE);

    if (!$matches[1][0]) throw new \Exception();

    return $matches[1][0];
  }

  public static function getItemFromId(int $id, string $mod, $columns = [])
  {
    $item = DB::table("mod_$mod")
      ->select(...$columns)
      ->find((int) $id);

    if (!$item) throw new \Exception();

    return $item;
  }

  public static function normalize(string $str)
  {
    $caracters =      array(".", " ", "<", ">", "{", "}", "[", "]", "(", ")", "/", "\'", "\"", "~", "^", ";", ":", "!", "@", "#", "$", "%", "¨", "&", "*", "§", "+", "=", "www.", "www", ".com", ".br", "?", ",", "|", "´", "`", "°", "ª", "º", "á", "à", "ã", "â", "ä", "é", "ë", "è", "ê", "í", "ì", "î", "ï", "ó", "ò", "õ", "ô", "ö", "ç", "ú", "ù", "û", "ü", "Á", "À", "Ã", "Â", "Ä", "É", "È", "Ë", "Ê", "Í", "Ì", "Î", "Ï", "Ó", "Ò", "Õ", "Ô", "Ö", "Ç", "Ú", "Ù", "Û", "Ü");
    $substitui_caracters =  array("", "-", "",  "",  "",  "",  "",  "",  "",  "",  "",  "",   "",   "",  "",  "",  "",  "",  "",  "",  "",  "",  "",  "",  "",  "",  "",  "",  "",     "",    "",     "",    "",  "",  "",  "",  "",  "",  "",  "",  "a", "a", "a", "a", "a", "e", "e", "e", "e", "i", "i", "i", "i", "o", "o", "o", "o", "o", "c", "u", "u", "u", "u", "A", "A", "A", "A", "A", "E", "E", "E", "E", "I", "I", "I", "I", "O", "O", "O", "O", "O", "C", "U", "U", "U", "U");
    return strtolower(str_replace($caracters, $substitui_caracters, $str));
  }
}
