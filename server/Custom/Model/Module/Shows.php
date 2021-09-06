<?php

namespace Custom\Model\Module;

use Custom\Model\Core\ModuleWrap;

class Shows extends ModuleWrap
{
  public function __construct()
  {
    parent::__construct('shows');
  }

  public function getItem(int $itemId): object
  {
    $item = $this->module->getItem(['id', 'title', 'img', 'date', 'city', 'state'], $itemId);

    $item->withImages('img');

    return $item->data;
  }

  public function getList(): object
  {
    return $this->module->getList(['id', 'title', 'img', 'date', 'city', 'state'])->data;
  }
}
