<?php

namespace Controller\Module\View;

use Model\Module\View\Table as ViewTable;
use Model\Utility\Response;

class Table
{
  public static function get(string $module, int $moduleItem)
  {
    if (!ON) {
      Response::set('status', 'error');
      Response::status(401);
    } else {
      Response::rawBody(ViewTable::get($module, $moduleItem));
    }

    Response::send();
  }

  public static function getAll(string $module)
  {
    if (!ON) {
      Response::rawBody(ViewTable::getAll($module, true));
    } else {
      Response::rawBody(ViewTable::getAll($module));
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

    Response::set('id', ViewTable::add($module));
    Response::send();
  }

  public static function remove(string $module, int $moduleItem)
  {
    if (!ON) {
      Response::set('status', 'error');
      Response::status(401);
    } else {
      ViewTable::remove($module, $moduleItem);
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
      ViewTable::update($module, $moduleItem, $data);
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
      ViewTable::setProp($module, $moduleItem, $prop, $value);
      Response::set('status', 'success');
      Response::set('success', 'Propiedade alterada com sucesso.');
    }

    Response::send();
  }
}
