<?php

namespace Module\Field\Category;

use Core\Model\Module\Module;
use Core\Model\Utility\Response;
use Core\Model\Utility\Request as Req;

class Controller
{
  public function getAllItems()
  {
    $moduleId = Req::get('moduleId');

    if (!$moduleId) {
      $moduleKey = Req::get('moduleKey');
      if ($moduleKey) {
        $moduleId = Module::getIdByKey($moduleKey);
      } else {
        Response::rawBody([]);
        Response::send();
      }
    }

    Response::rawBody(
      Model::getAllItems((int) $moduleId)
    );

    Response::send();
  }

  public function setLinkModule()
  {
    if (!ON || !Module::isAllowed((int) Req::get('moduleId'), ACCOUNT_ID)) {
      Response::set('status', 'error');
      Response::status(401);
    } else {
      if (Model::setLinkModule(
        (int) Req::get('moduleId'),
        (int) Req::get('fieldId'),
        Req::get('link')
      )) {
        Response::set('status', 'success');
        Response::set('success', 'Módulo vinculado.');
      }
    }

    Response::send();
  }

  public function setUnlinkModule()
  {
    if (!ON || !Module::isAllowed((int) Req::get('moduleId'), ACCOUNT_ID)) {
      Response::set('status', 'error');
      Response::status(401);
    } else {
      if (Model::setUnlinkModule(
        (int) Req::get('moduleId'),
        (int) Req::get('fieldId')
      )) {
        Response::set('status', 'success');
        Response::set('success', 'Módulo desvinculado.');
      }
    }

    Response::send();
  }

  public function addItem()
  {
    if (!ON || !Module::isAllowed((int) Req::get('moduleId'), ACCOUNT_ID)) {
      Response::set('status', 'error');
      Response::status(401);
    } else {
      Model::addItem(
        (int) Req::get('fieldId'),
        Req::get('title')
      );

      Response::rawBody(
        Model::getAllItems((int) Req::get('moduleId'))
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
      if (Model::setItemTitle((int) $d['categoryId'], Req::get('value'))) {
        Response::set('status', 'success');
        Response::set('success', 'Categoria alterada.');
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
      Model::removeItem((int) $d['categoryId']);
      Response::set('status', 'success');
    }
    Response::send();
  }
}
