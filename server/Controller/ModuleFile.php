<?php

namespace Controller;

use Model\Module;
use Model\Response;
use Model\ModuleFile as ModelModuleFile;
use Model\Request as Req;

class ModuleFile
{
  public function getAll($d)
  {
    if (!ON || !Module::isAllowed((int) Req::get('moduleId'), ACCOUNT_ID)) {
      Response::set('status', 'error');
      Response::status(401);
    } else {
      Response::rawBody(
        ModelModuleFile::getAll(
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
        ModelModuleFile::add(
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

  public function setTitle($d)
  {
    if (!ON || !Module::isAllowed((int) Req::get('moduleId'), ACCOUNT_ID)) {
      Response::set('status', 'error');
      Response::status(401);
    } else {
      if (ModelModuleFile::setTitle((int) $d['fileId'], Req::get('value'))) {
        Response::set('status', 'success');
        Response::set('success', 'Título alterado.');
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
      ModelModuleFile::remove((int) $d['fileId']);
      Response::set('status', 'success');
    }
    Response::send();
  }
}
