<?php

namespace Module\Field\ImageFile;

use Model\Module\Module;
use Model\Utility\Response;
use Model\Utility\Request as Req;

class Image
{
  public function getAll($d)
  {
    if (!ON || !Module::isAllowed((int) Req::get('moduleId'), ACCOUNT_ID)) {
      Response::set('status', 'error');
      Response::status(401);
    } else {
      Response::rawBody(
        Model::getAll(
          (int) Req::get('fieldId'),
          (int) $d['itemId']
        )
      );
    }

    Response::send();
  }

  public function add()
  {
    if (!ON || !Module::isAllowed((int) Req::get('moduleId'), ACCOUNT_ID)) {
      Response::set('status', 'error');
      Response::status(401);
    } else {
      Response::rawBody(
        Model::add(
          (int) Req::get('moduleId'),
          (int) Req::get('fieldId'),
          (int) Req::get('itemId'),
          Req::get('image')
        )
      );
    }

    Response::send();
  }

  public function remove($d)
  {
    if (!ON) {
      Response::set('status', 'error');
      Response::status(401);
    } else {
      Model::remove((int) $d['imageId']);
      Response::set('status', 'success');
    }
    Response::send();
  }
}
