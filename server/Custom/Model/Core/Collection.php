<?php

namespace Custom\Model\Core;

use Core\Model\Module\Field;
use Custom\Model\Core\Module;

class Collection
{
  public Module $module;

  const MAX_ITEMS_PER_PAGE = 30;

  public object $data;

  public ?object $init;

  public function __construct(
    Module $module,
    array $columns,
    ?CollectionInit $init = null,
    ?callable $where = null
  ) {
    $this->module = $module;

    if (!$init) $init = new CollectionInit();
    $this->init = $init;

    $offset = ($init->page - 1) * ($init->itemsPerPage ? $init->itemsPerPage : self::MAX_ITEMS_PER_PAGE);

    $limit = $init->itemsPerPage > self::MAX_ITEMS_PER_PAGE || !$init->itemsPerPage ? self::MAX_ITEMS_PER_PAGE  : $init->itemsPerPage;

    $list = DB::table("mod_{$this->module->key}")
      ->select(...$columns)
      ->where("mod_{$this->module->key}.active", 1)
      ->where(DB::raw("IF(mod_{$this->module->key}.showFrom, CURRENT_TIMESTAMP > mod_{$this->module->key}.showFrom, TRUE)"), 1)
      ->where(DB::raw("IF(mod_{$this->module->key}.showUp, CURRENT_TIMESTAMP < mod_{$this->module->key}.showUp, TRUE)"), 1);

    if ($where) {
      $list = $where($list);
    }

    $order = $init->order ? $init->order : "mod_{$this->module->key}.id";
    $direction = $init->direction ? $init->direction : 'DESC';

    $list = $list->orderByRaw("$order $direction")
      ->offset($offset)
      ->limit($limit)
      ->get();

    if (!$init->returnTotalItems) {
      $this->data = $list;
    } else {
      $totalItems = $this->getTotalItems($where);

      $this->data = (object) [
        "list" => $list,
        "totalItems" => $totalItems
      ];
    }
  }

  /**
   * Obter a quantidade de itens total do módulo.
   *
   * @param callable|null $custom
   * @return integer
   */
  private function getTotalItems(?callable $custom): int
  {
    $q = DB::table("mod_{$this->module->key}")->where('active', 1);

    if ($custom) $custom($q);

    return $q->count();
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
      ->select('id', 'path', 'item_id')
      ->where('modules_fields_id', $fieldId)
      ->get();

    if (!$this->init->returnTotalItems) $list = $this->data;
    else $list = $this->data->list;

    $list->transform(function ($item) use ($imgs, $fieldKey) {
      foreach ($imgs as $img) {
        if ($img->id === $item->{$fieldKey}) {
          $imgFeatured = $img->path;
          break;
        }
      }

      $item->{$fieldKey} = [
        'main' => $imgFeatured,
        'list' => []
      ];

      foreach ($imgs as $img) {
        if ($img->item_id == $item->id) {
          $item->{$fieldKey}['list'][] = $img->path;
        }
      }

      return $item;
    });

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

    $categories = DB::table($table)
      ->select()
      ->get();

    $list = null;

    if (!$this->init->returnTotalItems) $list = $this->data;
    else $list = $this->data->list;

    $list->transform(function ($item) use ($categories, $fieldKey) {

      foreach ($categories as $catg) {
        if ($catg->id == $item->{$fieldKey}) {
          $item->{$fieldKey} = $catg;
        }
      }

      return $item;
    });

    return true;
  }
}
