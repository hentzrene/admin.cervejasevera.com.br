<?php

namespace Core\Controller\Module;

use Core\Model\Module\Menu as ModuleMenu;
use Core\Model\Utility\Request as Req;
use Core\Model\Utility\Response;

class Menu
{
  public function getAllItems()
  {
    if (!ON) {
      Response::set('status', 'error');
      Response::status(401);
      Response::send();
    }

    Response::rawBody(
      ModuleMenu::getAllItems()
    );

    Response::send();
  }

  public function addItem()
  {
    if (!ON) {
      Response::set('status', 'error');
      Response::status(401);
    } else {
      try {
        ModuleMenu::addItem(Req::get('title'));

        Response::rawBody(
          ModuleMenu::getAllItems()
        );
      } catch (\Exception $e) {
        Response::set('status', 'error');
        Response::set('error', 'Não foi possível adicionar o menu.');
      }
    }

    Response::send();
  }

  public function setItemTitle($d)
  {
    if (!ON) {
      Response::set('status', 'error');
      Response::status(401);
      Response::send();
    } else {
      if (ModuleMenu::setItemTitle((int) $d['menuId'], Req::get('value'))) {
        Response::set('status', 'success');
        Response::set('success', 'Menu alterado.');
      } else {
        Response::set('status', 'error');
        Response::set('error', 'Não foi possível alterar o menu.');
      }
    }

    Response::send();
  }

  public function removeItem($d)
  {
    if (!ON) {
      Response::set('status', 'error');
      Response::status(401);
      Response::send();
    } else {
      ModuleMenu::removeItem((int) $d['menuId']);
      Response::set('status', 'success');
    }
    Response::send();
  }
}
