<?php

namespace Controller;

use Controller\ModuleView\Table;
use Model\Response;
use Model\Module as ModelModule;
use Model\Request as Req;

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

    ModelModule::add(Req::getAll());
    $this->getAll();
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
      ModelModule::remove((int) $d['moduleId']);
      Response::set('status', 'success');
    }
    Response::send();
  }

  public static function route($d)
  {
    $module = $d['module'];
    $viewKey = ModelModule::has($module);

    if (!$viewKey) {
      require __DIR__ . '/../Vue.php';
      die();
    }

    $method = $_SERVER['REQUEST_METHOD'];
    $moduleItem = $d['moduleItem'];
    $prop = $d['prop'];

    if ($viewKey === 'table') {
      if ($method === 'GET') {
        if ($moduleItem) {
          Table::get($module, (int) $moduleItem);
        } else {
          Table::getAll($module);
        }
      } elseif ($method === 'POST') {
        Table::add($module);
      } elseif ($method === 'PUT') {
        if ($prop === 'active') {
          Table::toggleActive($module, (int) $moduleItem, (bool) (int) Req::get('value'));
        } else {
          Table::update($module, (int) $moduleItem, Req::getAll());
        }
      } elseif ($method === 'DELETE') {
        Table::remove($module, (int) $moduleItem);
      }
    }
  }
}
