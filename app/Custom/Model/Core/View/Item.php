<?php

namespace Custom\Model\Core\View;

use Custom\Model\Core\CollectionItem;
use Custom\Model\Core\Module;

class Item extends Module
{
  public function get(array $columns = []): CollectionItem
  {
    $this->data = new CollectionItem($this, $columns, 1);

    return $this->data;
  }
}
