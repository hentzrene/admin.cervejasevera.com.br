<?php

namespace Controller;

use Model\Module;
use Model\Response;
use Model\ModuleField as ModelModuleField;
use Model\Request as Req;

class ModuleField
{
  public function add()
  {
    if (!ON || !Module::isAllowed((int) Req::get('moduleId'), ACCOUNT_ID)) {
      Response::set('status', 'error');
      Response::status(401);
      Response::send();
    }

    ModelModuleField::add(Req::getAll(), (int) Req::get('moduleId'));

    Response::rawBody([]);
    Response::send();
  }

  public function setName($d)
  {
    if (!ON || !Module::isAllowed((int) Req::get('moduleId'), ACCOUNT_ID)) {
      Response::set('status', 'error');
      Response::status(401);
    } else {
      ModelModuleField::setName((int) $d['moduleFieldId'], Req::get('value'));
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
      ModelModuleField::setTypeId((int) $d['moduleFieldId'], (int) Req::get('value'));
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
      ModelModuleField::remove((int) $d['moduleFieldId']);
      Response::set('status', 'success');
    }
    Response::send();
  }
}
