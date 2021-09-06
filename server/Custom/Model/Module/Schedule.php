<?php

namespace Custom\Model\Module;

use Custom\Model\Core\ModuleWrap;

class Schedule extends ModuleWrap
{
  public function __construct()
  {
    parent::__construct('schedule');
  }

  public function getList(): object
  {
    return $this->module->getList(['id', 'title', 'date', 'city', 'state'])->data;
  }
}
