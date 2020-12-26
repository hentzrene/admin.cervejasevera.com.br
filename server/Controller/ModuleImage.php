<?php

namespace Controller;

use Model\Module;
use Model\Response;
use Model\ModuleImage as ModelModuleImage;
use Model\Request as Req;

class ModuleImage
{
  public function getAll($d)
  {
    if (!ON || !Module::isAllowed((int) Req::get('moduleId'), ACCOUNT_ID)) {
      Response::set('status', 'error');
      Response::status(401);
    } else {
      Response::rawBody(
        ModelModuleImage::getAll(
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
        ModelModuleImage::add(
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
      ModelModuleImage::remove((int) $d['imageId']);
      Response::set('status', 'success');
    }
    Response::send();
  }
}
