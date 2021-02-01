<?php

namespace Controller\Module;

use Model\Module\Module;
use Model\Utility\Request as Req;
use Model\Utility\Response;

class Route
{
  const VIEWS_CLASSES = [
    'table' => 'Controller\Module\RouteView\Table'
  ];

  public function getAll($d)
  {
    $module = $d['module'];
    $viewKey = Module::has($module);

    if (!$viewKey) Response::send();

    call_user_func([self::VIEWS_CLASSES[$viewKey], 'getAll'], $module);
  }

  public function get($d)
  {
    $module = $d['module'];
    $viewKey = Module::has($module);

    if (!$viewKey) Response::send();

    call_user_func([self::VIEWS_CLASSES[$viewKey], 'get'], $module, (int) $d['moduleItem']);
  }

  public function add($d)
  {
    $module = $d['module'];
    $viewKey = Module::has($module);

    if (!$viewKey) Response::send();

    call_user_func([self::VIEWS_CLASSES[$viewKey], 'add'], $module);
  }

  public function remove($d)
  {
    $module = $d['module'];
    $viewKey = Module::has($module);

    if (!$viewKey) Response::send();

    call_user_func([self::VIEWS_CLASSES[$viewKey], 'remove'], $module, (int) $d['moduleItem']);
  }

  public function update($d)
  {
    $module = $d['module'];
    $viewKey = Module::has($module);

    if (!$viewKey) Response::send();

    call_user_func([self::VIEWS_CLASSES[$viewKey], 'update'], $module, (int) $d['moduleItem'], Req::getAll());
  }

  public function setProp($d)
  {
    $module = $d['module'];
    $viewKey = Module::has($module);

    if (!$viewKey) Response::send();

    call_user_func([self::VIEWS_CLASSES[$viewKey], 'setProp'], $module, (int) $d['moduleItem'], $d['prop'], Req::get('value'));
  }
}
