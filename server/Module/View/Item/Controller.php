<?php

namespace Module\View\Item;

use Core\Model\Utility\Response;

class Controller
{
  public static function getAll(string $module, int $moduleItem)
  {
    if (!ON) {
      Response::set('status', 'error');
      Response::status(401);
    } else {
      Response::rawBody(Model::get($module));
    }

    Response::send();
  }

  public static function update(string $module, int $moduleItem, object $data)
  {
    if (!ON) {
      Response::set('status', 'error');
      Response::status(401);
    } else {
      Model::update($module, $data);
      Response::set('status', 'success');
      Response::set('success', 'Item alterado com sucesso.');
    }

    Response::send();
  }

  public static function setProp(string $module, int $moduleItem, string $prop, string $value)
  {
    if (!ON) {
      Response::set('status', 'error');
      Response::status(401);
    } else {
      Model::setProp($module, $prop, $value);
      Response::set('status', 'success');
      Response::set('success', 'Propiedade alterada com sucesso.');
    }

    Response::send();
  }
}
