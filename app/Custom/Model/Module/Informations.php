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
    return $this->module->get(['img', 'icon', 'name'])->data;
  }
}
