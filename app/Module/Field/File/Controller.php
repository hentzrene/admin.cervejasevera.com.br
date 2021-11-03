<?php

namespace Module\Field\File;

use Core\Model\Module\Module;
use Core\Model\Utility\Response;
use Core\Model\Utility\Request as Req;

class Controller
{
  public function getAllItems($d)
  {
    if (!ON || !Module::isAllowed((int) Req::get('moduleId'), ACCOUNT_ID)) {
      Response::set('status', 'error');
      Response::status(401);
    } else {
      Response::rawBody(
        Model::getAllItems(
          (int) Req::get('fieldId'),
          (int) $d['itemId']
        )
      );
    }

    Response::send();
  }

  public function addItem()
  {
    if (!ON || !Module::isAllowed((int) Req::get('moduleId'), ACCOUNT_ID)) {
      Response::set('status', 'error');
      Response::status(401);
    } else {
      Response::rawBody(
        Model::addItem(
          (int) Req::get('moduleId'),
          (int) Req::get('fieldId'),
          (int) Req::get('itemId'),
          Req::get('title'),
          Req::get('file')
        )
      );
    }

    Response::send();
  }

  public function setItemTitle($d)
  {
    if (!ON || !Module::isAllowed((int) Req::get('moduleId'), ACCOUNT_ID)) {
      Response::set('status', 'error');
      Response::status(401);
    } else {
      if (Model::setItemTitle((int) $d['fileId'], Req::get('value'))) {
        Response::set('status', 'success');
        Response::set('success', 'Título alterado.');
      }
    }

    Response::send();
  }

  public function removeItem($d)
  {
    if (!ON || !Module::isAllowed((int) Req::get('moduleId'), ACCOUNT_ID)) {
      Response::set('status', 'error');
      Response::status(401);
    } else {
      Model::removeItem((int) $d['fileId']);
      Response::set('status', 'success');
    }
    Response::send();
  }
}
