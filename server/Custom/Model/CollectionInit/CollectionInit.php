<?php

namespace Custom\Model\CollectionInit;

use Custom\Model\CollectionInit\JoinSet\JoinSet;

class CollectionInit
{
  public int $page = 1;

  public ?int $itemsPerPage = null;

  public ?string $order = null;

  public int $returnTotalItems = 0;

  public ?JoinSet $join = null;

  public function __construct(
    array $configuration = []
  ) {
    $possibleOptions = array_keys(get_class_vars('Custom\Model\CollectionInit\CollectionInit'));

    foreach ($configuration as $k => $v) {
      if (in_array($k, $possibleOptions)) {
        $this->{$k} = $v;
      }
    }

    if (isset($this->page) && $this->page < 1) {
      $this->page = 1;
    }
  }
}
