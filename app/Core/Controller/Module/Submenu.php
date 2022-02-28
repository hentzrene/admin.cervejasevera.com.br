<?php

namespace Core\Controller\Module;

use Core\Model\Module\Submenu as ModuleSubmenu;
use Core\Model\Utility\Request as Req;
use Core\Model\Utility\Response;

class Submenu
{
  public function add()
  {
    if (!ON) {
      Response::set('status', 'error');
      Response::status(401);
    } else {
      try {
        Response::rawBody(
          ModuleSubmenu::add(Req::get('menuId'), Req::get('title'))
        );
      } catch (\Exception $e) {
        Response::set('status', 'error');
        Response::set('error', 'Não foi possível adicionar o submenu.');
      }
    }

    Response::send();
  }

  public function update($d)
  {
    if (!ON) {
      Response::set('status', 'error');
      Response::status(401);
    } else {
      try {
        ModuleSubmenu::update((int) $d['submenuId'], Req::get('title'));
      } catch (\Exception $e) {
        Response::set('status', 'error');
        Response::set('error', 'Não foi possível alterar o submenu.');
      }
    }

    Response::send();
  }

  public function remove($d)
  {
    if (!ON) {
      Response::set('status', 'error');
      Response::status(401);
      Response::send();
    } else {
      ModuleSubmenu::remove((int) $d['submenuId']);
      Response::set('status', 'success');
    }
    Response::send();
  }
}
