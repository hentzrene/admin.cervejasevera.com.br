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
        'title' => 'Início - ' . self::$defaultTags->name
      ],
      '/institucional/o-colegio' => [
        'title' => 'O Colégio - ' . self::$defaultTags->name
      ],
      '/institucional/equipe' => [
        'title' => 'Equipe - ' . self::$defaultTags->name
      ],
      '/institucional/quem-foi-jean-piaget' => [
        'title' => 'Quem foi Jean Piaget? - ' . self::$defaultTags->name
      ],
      '/institucional/proposta-pedagogica' => [
        'title' => 'Proposta Pedagógica - ' . self::$defaultTags->name
      ],
      '/ensino/{item}' => [
        'title' => 'Ensino - ' . self::$defaultTags->name
      ],
      '/ensino/projetos' => [
        'title' => 'Projetos - ' . self::$defaultTags->name
      ],
      '/ensino/projetos/{item}' => [
        'title' => 'Projetos - ' . self::$defaultTags->name
      ],
      '/novidades/noticias' => [
        'title' => 'Notícias - ' . self::$defaultTags->name
      ],
      '/novidades/noticias/{item}' => [
        'title' => 'Notícias - ' . self::$defaultTags->name
      ],
      '/novidades/galerias' => [
        'title' => 'Galerias - ' . self::$defaultTags->name
      ],
      '/novidades/galerias/{item}' => [
        'title' => 'Galerias - ' . self::$defaultTags->name
      ],
      '/novidades/videos' => [
        'title' => 'Vídeos - ' . self::$defaultTags->name
      ],
      '/novidades/videos/{item}' => [
        'title' => 'Vídeos - ' . self::$defaultTags->name
      ],
      '/novidades/comunicados' => [
        'title' => 'Comunicados da Escola - ' . self::$defaultTags->name
      ],
      '/novidades/comunicados/{item}' => [
        'title' => 'Comunicados da Escola - ' . self::$defaultTags->name
      ],
      '/aluno/atividades-e-compromissos' => [
        'title' => 'Atividades e Compromissos - ' . self::$defaultTags->name
      ],
      '/aluno/atividades-e-compromissos/{item}' => [
        'title' => 'Atividades e Compromissos - ' . self::$defaultTags->name
      ],
      '/aluno/links-uteis' => [
        'title' => 'Links Úteis - ' . self::$defaultTags->name
      ],
      '/aluno/dicas-interessantes' => [
        'title' => 'Dicas Interessantes - ' . self::$defaultTags->name
      ],
      '/aluno/licao-de-casa' => [
        'title' => 'Lição de Casa - ' . self::$defaultTags->name
      ],
      '/aluno/licao-de-casa/{item}' => [
        'title' => 'Lição de Casa - ' . self::$defaultTags->name
      ],
      '/horario-de-aulas' => [
        'title' => 'Horário de Aulas - ' . self::$defaultTags->name
      ],
      '/carta-aos-pais' => [
        'title' => 'Carta aos Pais - ' . self::$defaultTags->name
      ],
      '/destaques' => [
        'title' => 'Destaques - ' . self::$defaultTags->name
      ],
      '/contato/fale-conosco' => [
        'title' => 'Fale Conosco - ' . self::$defaultTags->name
      ],
      '/contato/trabalhe-conosco' => [
        'title' => 'Trabalhe Conosco - ' . self::$defaultTags->name
      ],
      '/leituras' => [
        'title' => 'Leituras - ' . self::$defaultTags->name
      ],
      '/pre-matricula' => [
        'title' => 'Pré Matrícula - ' . self::$defaultTags->name
      ],
      '/boletim-corona-virus' => [
        'title' => 'Boletim Corona Vírus - ' . self::$defaultTags->name
      ],
      '/error404' => [
        'title' => 'Erro 404 - ' . self::$defaultTags->name
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
