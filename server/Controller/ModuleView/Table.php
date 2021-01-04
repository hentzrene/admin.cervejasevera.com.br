<?php

namespace Controller\ModuleView;

use Model\Response;
use Model\ModuleView\Table as ModelTable;

class Table
{
  public static function get(string $module, int $moduleItem)
  {
    if (!ON) {
      Response::set('status', 'error');
      Response::status(401);
    } else {
      Response::rawBody(ModelTable::get($module, $moduleItem));
    }

    Response::send();
  }

  public static function getAll(string $module)
  {
    if (!ON) {
      Response::rawBody(ModelTable::getAll($module, true));
    } else {
      Response::rawBody(ModelTable::getAll($module));
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

    Response::set('id', ModelTable::add($module));
    Response::send();
  }

  public static function remove(string $module, int $moduleItem)
  {
    if (!ON) {
      Response::set('status', 'error');
      Response::status(401);
    } else {
      ModelTable::remove($module, $moduleItem);
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
      ModelTable::update($module, $moduleItem, $data);
      Response::set('status', 'success');
      Response::set('success', 'Item alterado com sucesso.');
    }

    Response::send();
  }

  public static function toggleActive(string $module, int $moduleItem, bool $value)
  {
    if (!ON) {
      Response::set('status', 'error');
      Response::status(401);
    } else {
      ModelTable::toggleActive($module, $moduleItem, $value);
      Response::set('status', 'success');
      // if($value) {
      //   Response::set('success', 'Item inativado com sucesso.');
      // } else {
      //   Response::set('success', 'Item ativa com sucesso.');
      // }
    }

    Response::send();
  }
}
