<?php

namespace Custom\Controller;

use Core\Model\Utility\Request as Req;
use Core\Model\Utility\Response;
use Custom\Model\Collection;
use Custom\Model\CollectionInit;
use Custom\Model\Item;

class Content
{
  public function getAllInformations()
  {
    $Item = new Item(
      'informations',
      ['img', 'name'],
      1
    );

    Response::rawBody($Item->data);

    Response::send();
  }

  public function getAllArticles()
  {
    $collection = new Collection(
      'articles',
      [
        'id',
        'title',
        'SUBSTRING(text, 1, 70) as text',
        'img',
        'scheduled'
      ],
      new CollectionInit([
        'page' => (int) Req::get('page'),
        'itemsPerPage' => (int) Req::get('itemsPerPage'),
        'returnTotalItems' => (int) Req::get('returnTotalItems')
      ])
    );

    Response::rawBody($collection->data);

    Response::send();
  }

  public function getArticle($d)
  {
    $Item = new Item('articles', [
      'id',
      'title',
      'source',
      'img as mainImg',
      'video',
      'scheduled',
      'text'
    ], (int) $d['itemId']);

    $Item->withImages('img');

    $Item->withAttachments('file');

    Response::rawBody($Item->data);

    Response::send();
  }
}
