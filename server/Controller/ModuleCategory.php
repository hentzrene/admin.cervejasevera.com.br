<?php

namespace Controller;

use Model\Module;
use Model\Response;
use Model\ModuleCategory as ModelModuleCategory;
use Model\Request as Req;

class ModuleCategory
{
  public function getAll()
  {
    if (!ON || !Module::isAllowed((int) Req::get('moduleId'), ACCOUNT_ID)) {
      Response::set('status', 'error');
      Response::status(401);
    } else {
      Response::rawBody(
        ModelModuleCategory::getAll(
          (int) Req::get('fieldId'),
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
      ModelModuleCategory::add(
        (int) Req::get('moduleId'),
        (int) Req::get('fieldId'),
        Req::get('title')
      );

      Response::rawBody(
        ModelModuleCategory::getAll(
          (int) Req::get('fieldId'),
        )
      );
    }

    Response::send();
  }

  public function setTitle($d)
  {
    if (!ON || !Module::isAllowed((int) Req::get('moduleId'), ACCOUNT_ID)) {
      Response::set('status', 'error');
      Response::status(401);
    } else {
      if (ModelModuleCategory::setTitle((int) $d['categoryId'], Req::get('value'))) {
        Response::set('status', 'success');
        Response::set('success', 'Categoria alterada.');
      }
    }

    Response::send();
  }

  public function remove($d)
  {
    if (!ON || !Module::isAllowed((int) Req::get('moduleId'), ACCOUNT_ID)) {
      Response::set('status', 'error');
      Response::status(401);
    } else {
      ModelModuleCategory::remove((int) $d['categoryId']);
      Response::set('status', 'success');
    }
    Response::send();
  }
}
