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
    $item = new Item(
      'informations',
      ['img', 'name', 'local', 'tel', 'email', 'operation', 'map'],
      1
    );

    Response::rawBody($item->data);

    Response::send();
  }

  public function getAllSlides()
  {
    $collection = new Collection(
      'slides',
      ['title', 'link',  'desktop', 'mobile'],
    );

    Response::rawBody($collection->data);

    Response::send();
  }

  public function getAllEducation()
  {
    $collection = new Collection(
      'education',
      ['id', 'title'],
    );

    Response::rawBody($collection->data);

    Response::send();
  }

  public function getEducation($d)
  {
    $item = new Item(
      'education',
      ['id', 'title', 'text', 'img' => 'mainImg'],
      (int) $d['itemId']
    );

    $item->withImages('img');

    Response::rawBody($item->data);

    Response::send();
  }

  public function getJeanPiaget()
  {
    $item = new Item(
      'jean_piaget',
      ['intro', 'text', 'img'],
      1
    );

    Response::rawBody($item->data);

    Response::send();
  }

  public function getAbout()
  {
    $item = new Item(
      'about',
      ['text', 'img'],
      1
    );

    Response::rawBody($item->data);

    Response::send();
  }

  public function getPedagogicalProposal()
  {
    $item = new Item(
      'pedagogical_proposal',
      ['text', 'img'],
      1
    );

    Response::rawBody($item->data);

    Response::send();
  }

  public function getAllVideos()
  {
    $collection = new Collection(
      'videos',
      ['id', 'title', 'link'],
      new CollectionInit([
        'page' => (int) Req::get('page'),
        'itemsPerPage' => (int) Req::get('itemsPerPage'),
        'returnTotalItems' => (int) Req::get('returnTotalItems')
      ])
    );

    Response::rawBody($collection->data);

    Response::send();
  }

  public function getVideo($d)
  {
    $item = new Item(
      'videos',
      ['title', 'text', 'link', 'source'],
      (int) $d['itemId']
    );

    Response::rawBody($item->data);

    Response::send();
  }

  public function getAllProjects()
  {
    $collection = new Collection(
      'projects',
      [
        'id',
        'title',
        fn ($mod) => ([
          'column' => "SUBSTRING($mod.text, 1, 70)",
          'as' => 'text'
        ]),
        'img'
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

  public function getAllGalleries()
  {
    $collection = new Collection(
      'galleries',
      [
        'id',
        'title',
        'img'
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

  public function getGallery($d)
  {
    $item = new Item('galleries', [
      'id',
      'title',
      'date_start',
      'text'
    ], (int) $d['itemId']);

    $item->withImages('img');

    Response::rawBody($item->data);

    Response::send();
  }

  public function getAllCommunications()
  {
    $collection = new Collection(
      'communications',
      [
        'id',
        'title',
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
    $item = new Item('articles', [
      'id',
      'title',
      'source',
      'img' => 'mainImg',
      'video',
      'scheduled',
      'text'
    ], (int) $d['itemId']);

    $item->withImages('img');

    $item->withAttachments('file');

    Response::rawBody($item->data);

    Response::send();
  }

  public function getWebCanal()
  {
    $item = new Item(
      'webcanal',
      ['text', 'img', 'link'],
      1
    );

    Response::rawBody($item->data);

    Response::send();
  }

  public function getFeaturedVideo()
  {
    $item = new Item(
      'featured_video',
      ['title', 'text', 'link'],
      1
    );

    Response::rawBody($item->data);

    Response::send();
  }

  public function getAllQuickAccess()
  {
    $collection = new Collection(
      'quick_access',
      ['title', 'img', 'link'],
      new CollectionInit(['order' => 'ASC'])
    );

    Response::rawBody($collection->data);

    Response::send();
  }

  public function getAllCovid()
  {
    $collection = new Collection(
      'covid',
      ['title',  'file', 'source', 'scheduled']
    );

    Response::rawBody($collection->data);

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
