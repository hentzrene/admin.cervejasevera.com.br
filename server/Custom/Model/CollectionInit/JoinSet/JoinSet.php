<?php

namespace Custom\Model\CollectionInit\JoinSet;

class JoinSet
{
  public array $items;

  public function __construct(array $items)
  {
    foreach ($items as $v) {
      $className = get_class($v);
      if (!$className === 'Custom\Model\CollectionInit\JoinSet\Item') {
        throw new \Exception("O objeto de classe '$className' não é permitido.");
      }
    }

    $this->items = $items;
  }
}
