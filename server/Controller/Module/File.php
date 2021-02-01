<?php

namespace Controller\Module;

use Model\Module\Module;
use Model\Utility\Response;
use Model\Module\File as ModelFile;
use Model\Utility\Request as Req;

class File
{
  public function getAll($d)
  {
    if (!ON || !Module::isAllowed((int) Req::get('moduleId'), ACCOUNT_ID)) {
      Response::set('status', 'error');
      Response::status(401);
    } else {
      Response::rawBody(
        ModelFile::getAll(
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
        ModelFile::add(
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
      if (ModelFile::setTitle((int) $d['fileId'], Req::get('value'))) {
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
      ModelFile::remove((int) $d['fileId']);
      Response::set('status', 'success');
    }
    Response::send();
  }
}
