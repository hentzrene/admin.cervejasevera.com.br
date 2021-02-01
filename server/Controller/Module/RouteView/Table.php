<?php

namespace Controller\Module\RouteView;

use Model\Module\RouteView\Table as RouteViewTable;
use Model\Utility\Response;

class Table
{
  public static function get(string $module, int $moduleItem)
  {
    if (!ON) {
      Response::set('status', 'error');
      Response::status(401);
    } else {
      Response::rawBody(RouteViewTable::get($module, $moduleItem));
    }

    Response::send();
  }

  public static function getAll(string $module)
  {
    if (!ON) {
      Response::rawBody(RouteViewTable::getAll($module, true));
    } else {
      Response::rawBody(RouteViewTable::getAll($module));
    }

    Response::send();
  }

  public static function add(string $module)
  {
    if (!ON) {
      Response::set('status', 'error');
      Response::status(401);
      Response::send();
    }

    Response::set('id', RouteViewTable::add($module));
    Response::send();
  }

  public static function remove(string $module, int $moduleItem)
  {
    if (!ON) {
      Response::set('status', 'error');
      Response::status(401);
    } else {
      RouteViewTable::remove($module, $moduleItem);
      Response::set('status', 'success');
    }
    Response::send();
  }

  public static function update(string $module, int $moduleItem, object $data)
  {
    if (!ON) {
      Response::set('status', 'error');
      Response::status(401);
    } else {
      RouteViewTable::update($module, $moduleItem, $data);
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
      RouteViewTable::setProp($module, $moduleItem, $prop, $value);
      Response::set('status', 'success');
      Response::set('success', 'Propiedade alterada com sucesso.');
    }

    Response::send();
  }
}
