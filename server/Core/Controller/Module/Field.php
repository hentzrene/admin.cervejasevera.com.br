<?php

namespace Core\Controller\Module;

use Core\Model\Module\Module;
use Core\Model\Utility\Response;
use Core\Model\Module\Field as ModelField;
use Core\Model\Utility\Request as Req;

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
      ModelField::setName((int) $d['fieldId'], Req::get('value'));
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
      ModelField::setTypeId((int) $d['fieldId'], (int) Req::get('value'));
      Response::set('status', 'success');
      Response::set('success', 'Tipo alterado com sucesso.');
    }

    Response::send();
  }

  public function setOption($d)
  {
    if (!ON || !Module::isAllowed((int) Req::get('moduleId'), ACCOUNT_ID)) {
      Response::set('status', 'error');
      Response::status(401);
    } else {
      ModelField::setOption((int) $d['fieldId'], Req::get('option'), Req::get('value'));
      Response::set('status', 'success');
      Response::set('success', 'Opção alterada com sucesso.');
    }

    Response::send();
  }

  public function remove($d)
  {
    if (!ON || !Module::isAllowed((int) Req::get('moduleId'), ACCOUNT_ID)) {
      Response::set('status', 'error');
      Response::status(401);
    } else {
      ModelField::remove((int) $d['fieldId']);
      Response::set('status', 'success');
    }
    Response::send();
  }
}
