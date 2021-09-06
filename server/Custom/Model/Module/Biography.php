<?php

namespace Custom\Model\Module;

use Custom\Model\Core\ModuleWrap;

class Biography extends ModuleWrap
{
  public function __construct()
  {
    parent::__construct('biography');
  }

  public function get(): object
  {
    return $this->module->get(['img', 'text'])->data;
  }
}
