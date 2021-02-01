<?php

namespace Controller\Module;

use Model\Module\Module;
use Model\Utility\Response;
use Model\Module\Category as ModelCategory;
use Model\Utility\Request as Req;

class Category
{
  public function getAll()
  {
    if (!ON || !Module::isAllowed((int) Req::get('moduleId'), ACCOUNT_ID)) {
      Response::set('status', 'error');
      Response::status(401);
    } else {
      Response::rawBody(
        ModelCategory::getAll(
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
      ModelCategory::add(
        (int) Req::get('moduleId'),
        (int) Req::get('fieldId'),
        Req::get('title')
      );

      Response::rawBody(
        ModelCategory::getAll(
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
      if (ModelCategory::setTitle((int) $d['categoryId'], Req::get('value'))) {
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
      ModelCategory::remove((int) $d['categoryId']);
      Response::set('status', 'success');
    }
    Response::send();
  }
}
