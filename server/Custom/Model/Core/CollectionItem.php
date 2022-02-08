<?php

namespace Custom\Model\Core;

use Core\Model\Module\Field;
use Custom\Model\Core\Module;

class CollectionItem
{
  protected Module $module;

  protected int $itemId;

  public object $data;

  public function __construct(Module $module, array $columns, int $itemId)
  {
    $this->module = $module;

    $this->itemId = $itemId;

    $data = DB::table("mod_$module->key")
      ->select(...$columns)
      ->find($itemId);

    if ($data) $this->data = $data;
    else throw new \Exception('Não foi possível encontrar.');
  }

  /**
   * Obter as imagems de um campo.
   *
   * @param string $fieldKey
   * @return boolean
   */
  public function withImages(string $fieldKey): bool
  {
    $fieldId = $this->module->getFieldId($fieldKey);

    $imgs = DB::table('images')
      ->select('id', 'path', 'title')
      ->where('modules_fields_id', $fieldId)
      ->where('item_id', $this->itemId)
      ->get();

    foreach ($imgs as $img) {
      if ($img->id === $this->data->{$fieldKey}) {
        $imgFeatured = $img;
        break;
      }
    }

    $imgs = $imgs->map(fn ($img) => $img->path);

    $this->data->{$fieldKey} = [
      'featured' => $imgFeatured,
      'list' => $imgs
    ];

    return true;
  }

  /**
   * Obter a categoria de um campo
   *
   * @param string $fieldKey
   * @return boolean
   */
  public function withCategory(string $fieldKey): bool
  {
    $fieldId = $this->module->getFieldId($fieldKey);
    $fieldOptions = Field::getOptions($fieldId);


    $table = 'categories';

    if ($fieldOptions && $fieldOptions->linkModule) {
      $table = 'mod_' . $fieldOptions->linkModule;
    }

    $category = DB::table($table)
      ->select()
      ->find($this->data->category);

    $this->data->{$fieldKey} = $category;

    return true;
  }
}
