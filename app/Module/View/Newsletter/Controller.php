<?php

namespace Module\View\Newsletter;

use Core\Model\Utility\Response;

class Controller
{
  public static function get(string $module, int $moduleItem)
  {
    if (!ON) {
      Response::set('status', 'error');
      Response::status(401);
    } else {
      Response::rawBody(Model::get($module, $moduleItem));
    }

    Response::send();
  }

  public static function getAll(string $module)
  {
    if (!ON) {
      Response::set('status', 'error');
      Response::status(401);
    } else {
      Response::rawBody(Model::getAll($module));
    }

    Response::send();
  }


  public static function export(string $module, object $data)
  {
    if (!ON) {
      Response::set('status', 'error');
      Response::status(401);
    } else {
      if (!Model::export($module, $data)) {
        Response::status(400);
        Response::set('status', 'error');
        Response::set('error', 'Não foi possível realizar a exportação.');
      }
    }

    Response::send();
  }

  public static function broadcast(string $module, object $data)
  {
    if (!ON) {
      Response::set('status', 'error');
      Response::status(401);
    } else {
      if (!Model::broadcast($module, $data)) {
        Response::status(400);
        Response::set('status', 'error');
        Response::set('error', 'Não foi possível realizar a transmissão.');
      } else {
        Response::set('status', 'success');
        Response::set('success', 'Transmissão realizada com sucesso.');
      }
    }

    Response::send();
  }

  public static function remove(string $module, int $moduleItem)
  {
    if (!ON) {
      Response::set('status', 'error');
      Response::status(401);
    } else {
      Model::remove($module, $moduleItem);
      Response::set('status', 'success');
    }

    Response::send();
  }

  public static function setProp(string $module, int $moduleItem, string $prop, string $value)
  {
    if (!ON) {
      Response::set('status', 'error');
      Response::status(401);
    } else {
      Model::setProp($module, $moduleItem, $prop, $value);
      Response::set('status', 'success');
      Response::set('success', 'Propriedade alterada com sucesso.');
    }

    Response::send();
  }
}
