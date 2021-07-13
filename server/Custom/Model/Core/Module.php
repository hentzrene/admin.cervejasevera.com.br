<?php

namespace Custom\Model\Core;

use Core\Model\Module\Field;
use Core\Model\Module\Module as ModuleModule;

/**
 * Essa classe deve ser extendia pelas classes das views.
 */
class Module
{
  public string $key;

  public int $id;

  public function __construct(string $key)
  {
    $this->key = $key;
  }

  /**
   * Obter as categorias adicionada em um campo do tipo categoria.
   *
   * @param string $fieldKey
   * @return \Illuminate\Support\Collection
   */
  public function getCategories(string $fieldKey): \Illuminate\Support\Collection
  {
    $fieldId = $this->getFieldId($fieldKey);

    $categories = DB::table('categories')
      ->select('id', 'title')
      ->where('modules_fields_id', $fieldId)
      ->get();

    return $categories;
  }

  /**
   * Obter id do módulo.
   *
   * @return void
   */
  public function getId(): int
  {
    if (!isset($this->id)) $this->id = ModuleModule::getIdByKey($this->key);

    return $this->id;
  }

  /**
   * Obter id de um campo do módulo.
   *
   * @param string $fieldKey
   * @return integer
   */
  public function getFieldId(string $fieldKey): int
  {
    return Field::getId($this->getId(), $fieldKey);
  }
}
