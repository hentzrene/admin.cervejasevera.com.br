<?php

namespace Custom\Model\CollectionInit\JoinSet;

class Item
{
  public string $fieldKey;

  public string $type;

  const ALLOWEDS_TYPES = [
    'categories'
  ];

  public function __construct(string $fieldKey, string $type)
  {
    $this->fieldKey = $fieldKey;

    if (!in_array($type, self::ALLOWEDS_TYPES)) {
      throw new \Exception("O tipo '$type' não é permitido.");
    }

    $this->type = $type;
  }
}
