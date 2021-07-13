<?php

namespace Custom\Model\Core;

class CollectionInit
{
  public int $page = 1;

  public ?int $itemsPerPage = null;

  public ?string $order = null;

  public ?string $direction = null;

  public int $returnTotalItems = 0;

  public function __construct(
    array $configuration = []
  ) {
    $possibleOptions = array_keys(get_class_vars(self::class));

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
