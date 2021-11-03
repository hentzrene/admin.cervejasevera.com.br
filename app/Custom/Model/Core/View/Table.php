<?php

namespace Custom\Model\Core\View;

use Custom\Model\Core\Collection;
use Custom\Model\Core\CollectionInit;
use Custom\Model\Core\CollectionItem;
use Custom\Model\Core\Module;

class Table extends Module
{
  public function getItem(array $columns = [], $itemId): CollectionItem
  {
    return new CollectionItem($this, $columns, $itemId);
  }

  public function getList(
    array $columns,
    ?CollectionInit $init = null,
    ?callable $where = null
  ) {
    return new Collection($this, $columns, $init, $where);
  }
}
