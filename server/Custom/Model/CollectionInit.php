<?php

namespace Custom\Model;

class CollectionInit
{
  public int $page = 1;

  public ?int $itemsPerPage = null;

  public ?string $order = null;

  public int $returnTotalItems = 0;

  public function __construct(
    array $configuration = []
  ) {
    $possibleOptions = array_keys(get_class_vars('Custom\Model\CollectionInit'));

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
