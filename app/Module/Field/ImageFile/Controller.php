<?php

namespace Module\Field\ImageFile;

use Core\Model\Module\Field;
use Core\Model\Module\Module;
use Core\Model\Utility\Response;
use Core\Model\Utility\Request as Req;

class Controller
{
  public function getAllItems($d)
  {
    $fieldId = Req::get('fieldId');
    if (!$fieldId) {
      $fieldKey = Req::get('fieldKey');
      $moduleKey = Req::get('moduleKey');
      if ($moduleKey) {
        $moduleId = Module::getIdByKey($moduleKey);
        if ($moduleId) $fieldId = Field::getId($moduleId, $fieldKey);
      }
    }

    Response::rawBody(
      Model::getAllItems(
        (int) $fieldId,
        (int) $d['itemId']
      )
    );

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
          Req::get('image')
        )
      );
    }

    Response::send();
  }

  public function updateOrder()
  {
    if (!ON || !Module::isAllowed((int) Req::get('moduleId'), ACCOUNT_ID)) {
      Response::set('status', 'error');
      Response::status(401);
    } else {
      Model::updateOrder(Req::get('images'));
      Response::set('status', 'success');
    }

    Response::send();
  }

  public function removeItem($d)
  {
    if (!ON) {
      Response::set('status', 'error');
      Response::status(401);
    } else {
      Model::removeItem((int) $d['imageId']);
      Response::set('status', 'success');
    }
    Response::send();
  }
}
