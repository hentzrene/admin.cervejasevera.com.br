<?php

namespace Core\Controller\Module;

use Core\Model\Utility\Response;
use Core\Model\Module\Module as ModelModule;
use Core\Model\Utility\Request as Req;

class Module
{
  public function get($d)
  {
    if (!ON || !ModelModule::isAllowed((int) $d['moduleId'], ACCOUNT_ID)) {
      Response::set('status', 'error');
      Response::status(401);
    } else {
      Response::rawBody(ModelModule::get((int) $d['moduleId']));
    }
    Response::send();
  }

  public function getAll()
  {
    if (!ON) {
      Response::set('status', 'error');
      Response::status(401);
    } else {
      Response::rawBody(ModelModule::getAll());
    }
    Response::send();
  }

  public function getBasicOfAll()
  {
    if (!ON) {
      Response::set('status', 'error');
      Response::status(401);
    } else {
      Response::rawBody(ModelModule::getBasicOfAll());
    }
    Response::send();
  }

  public function add()
  {
    if (!ON) {
      Response::set('status', 'error');
      Response::status(401);
      Response::send();
    }

    $t = ModelModule::add(Req::getAll());

    if ($t) {
      $this->getAll();
    } else {
      Response::status(500);
      Response::send();
    }
  }

  public function export($d)
  {
    if (!ON) {
      Response::set('status', 'error');
      Response::status(401);
    } else {
      $export = ModelModule::export((int) $d['moduleId']);
      if (!$export) {
        Response::status(400);
        Response::set('status', 'error');
        Response::set('error', 'Não foi possível realizar a exportação.');
      }
    }


    Response::rawBody($export);

    Response::send();
  }

  public function setName($d)
  {
    if (!ON || !ModelModule::isAllowed((int) $d['moduleId'], ACCOUNT_ID)) {
      Response::set('status', 'error');
      Response::status(401);
    } else {
      ModelModule::setName((int) $d['moduleId'], Req::get('value'));
      Response::set('status', 'success');
      Response::set('success', 'Nome alterado com sucesso.');
    }

    Response::send();
  }

  public function setMenu($d)
  {
    if (!ON || !ModelModule::isAllowed((int) $d['moduleId'], ACCOUNT_ID)) {
      Response::set('status', 'error');
      Response::status(401);
    } else {
      ModelModule::setMenu(
        (int) $d['moduleId'],
        Req::get('menuId') ? (int) Req::get('menuId') : null,
        Req::get('submenuId') ? (int) Req::get('submenuId') : null
      );
      Response::set('status', 'success');
      Response::set('success', 'Menu alterado com sucesso.');
    }

    Response::send();
  }

  public function setIcon($d)
  {
    if (!ON || !ModelModule::isAllowed((int) $d['moduleId'], ACCOUNT_ID)) {
      Response::set('status', 'error');
      Response::status(401);
    } else {
      ModelModule::setIcon((int) $d['moduleId'], Req::get('value'));
      Response::set('status', 'success');
      Response::set('success', 'Ícone alterado com sucesso.');
    }

    Response::send();
  }

  public function setViewOptions($d)
  {
    if (!ON || !ModelModule::isAllowed((int) $d['moduleId'], ACCOUNT_ID)) {
      Response::set('status', 'error');
      Response::status(401);
    } else {
      ModelModule::setViewOptions((int) $d['moduleId'], Req::get('value'));
      Response::set('status', 'success');
      Response::set('success', 'Alteração realizada com sucesso.');
    }

    Response::send();
  }

  public function remove($d)
  {
    if (!ON || !ModelModule::isAllowed((int) $d['moduleId'], ACCOUNT_ID)) {
      Response::set('status', 'error');
      Response::status(401);
    } else {
      if (ModelModule::remove((int) $d['moduleId'])) {
        Response::set('status', 'success');
      } else {
        Response::set('status', 'error');
        Response::status(500);
      }
    }
    Response::send();
  }
}
