<?php

namespace Custom\Controller;

use Core\Model\Utility\Request as Req;
use Core\Model\Utility\Response;
use Custom\Model\Collection\Collection;
use Custom\Model\CollectionInit\CollectionInit;
use Custom\Model\CollectionInit\JoinSet\Item as JoinSetItem;
use Custom\Model\CollectionInit\JoinSet\JoinSet;
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

  public function getCommunication($d)
  {
    $item = new Item('articles', [
      'id',
      'title',
      'scheduled',
      'text'
    ], (int) $d['itemId']);


    Response::rawBody($item->data);

    Response::send();
  }

  public function getAllArticles()
  {
    $collection = new Collection(
      'articles',
      [
        'id',
        'title',
        fn ($mod) => ([
          'column' => "SUBSTRING($mod.text, 1, 70)",
          'as' => 'text'
        ]),
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
      'img' => 'mainImg',
      'video',
      'scheduled',
      'text'
    ], (int) $d['itemId']);

    $Item->withImages('img');

    $Item->withAttachments('file');

    Response::rawBody($Item->data);

    Response::send();
  }

  public function getAllUsefulLinks()
  {
    $collection = new Collection(
      'useful_links',
      ['title', 'link'],
      new CollectionInit([
        'join' => new JoinSet([
          new JoinSetItem('category', 'categories')
        ])
      ])
    );

    Response::rawBody($collection->data);

    Response::send();
  }
}
