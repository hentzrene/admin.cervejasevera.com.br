<?php

namespace Controller\Module;

use Model\Module\Module;
use Model\Utility\Request as Req;

class Route
{
  const VIEWS_CLASSES = [
    'table' => 'Controller\Module\View\Table'
  ];

  public function getAll($d)
  {
    $module = $d['module'];
    $viewKey = Module::has($module);

    if (!self::VIEWS_CLASSES[$viewKey]) {
      throw new \Exception("View não configurada corretamente no código fonte.");
    }

    if (method_exists(self::VIEWS_CLASSES[$viewKey], 'getAll')) {
      call_user_func([self::VIEWS_CLASSES[$viewKey], 'getAll'], $module);
    }
  }

  public function get($d)
  {
    $module = $d['module'];
    $viewKey = Module::has($module);

    if (!self::VIEWS_CLASSES[$viewKey]) {
      throw new \Exception("View não configurada corretamente no código fonte.");
    }

    if (method_exists(self::VIEWS_CLASSES[$viewKey], 'get')) {
      call_user_func([self::VIEWS_CLASSES[$viewKey], 'get'], $module, (int) $d['moduleItem']);
    }
  }

  public function add($d)
  {
    $module = $d['module'];
    $viewKey = Module::has($module);

    if (!self::VIEWS_CLASSES[$viewKey]) {
      throw new \Exception("View não configurada corretamente no código fonte.");
    }

    if (method_exists(self::VIEWS_CLASSES[$viewKey], 'add')) {
      call_user_func([self::VIEWS_CLASSES[$viewKey], 'add'], $module);
    }
  }

  public function remove($d)
  {
    $module = $d['module'];
    $viewKey = Module::has($module);

    if (!self::VIEWS_CLASSES[$viewKey]) {
      throw new \Exception("View não configurada corretamente no código fonte.");
    }

    if (method_exists(self::VIEWS_CLASSES[$viewKey], 'remove')) {
      call_user_func([self::VIEWS_CLASSES[$viewKey], 'remove'], $module, (int) $d['moduleItem']);
    }
  }

  public function update($d)
  {
    $module = $d['module'];
    $viewKey = Module::has($module);

    if (!self::VIEWS_CLASSES[$viewKey]) {
      throw new \Exception("View não configurada corretamente no código fonte.");
    }

    if (method_exists(self::VIEWS_CLASSES[$viewKey], 'update')) {
      call_user_func([self::VIEWS_CLASSES[$viewKey], 'update'], $module, (int) $d['moduleItem'], Req::getAll());
    }
  }

  public function setProp($d)
  {
    $module = $d['module'];
    $viewKey = Module::has($module);

    if (!self::VIEWS_CLASSES[$viewKey]) {
      throw new \Exception("View não configurada corretamente no código fonte.");
    }


    if (method_exists(self::VIEWS_CLASSES[$viewKey], 'setProp')) {
      call_user_func([self::VIEWS_CLASSES[$viewKey], 'setProp'], $module, (int) $d['moduleItem'], $d['prop'], Req::get($d['prop']));
    }
  }
}
