<?php

namespace Controller\Module;

use Model\Module\Module;
use Model\Utility\Response;
use Model\Module\Field as ModelField;
use Model\Utility\Request as Req;

class Field
{
  public function add()
  {
    if (!ON || !Module::isAllowed((int) Req::get('moduleId'), ACCOUNT_ID)) {
      Response::set('status', 'error');
      Response::status(401);
      Response::send();
    }

    ModelField::add(Req::getAll(), (int) Req::get('moduleId'));

    Response::rawBody([]);
    Response::send();
  }

  public function setName($d)
  {
    if (!ON || !Module::isAllowed((int) Req::get('moduleId'), ACCOUNT_ID)) {
      Response::set('status', 'error');
      Response::status(401);
    } else {
      ModelField::setName((int) $d['FieldId'], Req::get('value'));
      Response::set('status', 'success');
      Response::set('success', 'Nome alterado com sucesso.');
    }

    Response::send();
  }

  public function setTypeId($d)
  {
    if (!ON || !Module::isAllowed((int) Req::get('moduleId'), ACCOUNT_ID)) {
      Response::set('status', 'error');
      Response::status(401);
    } else {
      ModelField::setTypeId((int) $d['FieldId'], (int) Req::get('value'));
      Response::set('status', 'success');
      Response::set('success', 'Tipo alterado com sucesso.');
    }

    Response::send();
  }

  public function remove($d)
  {
    if (!ON || !Module::isAllowed((int) Req::get('moduleId'), ACCOUNT_ID)) {
      Response::set('status', 'error');
      Response::status(401);
    } else {
      ModelField::remove((int) $d['FieldId']);
      Response::set('status', 'success');
    }
    Response::send();
  }
}
