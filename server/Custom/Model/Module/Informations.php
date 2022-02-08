<?php

namespace Custom\Model\Module;

use Custom\Model\Core\ModuleWrap;

class Informations extends ModuleWrap
{
  public function __construct()
  {
    parent::__construct('informations');
  }

  public function get(): object
  {
    $item = $this->module->get([
      'img', 'name',
    ]);

    $item->withImages('img');

    $item->data->img = $item->data->img['featured']->path;

    return $item->data;
  }
}
