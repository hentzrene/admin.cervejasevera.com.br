<?php

namespace Custom\Model\Collection;

use Custom\Model\Conn\Conn;
use Custom\Model\CollectionInit\CollectionInit;

class Collection
{
  private string $moduleKey;

  public array $data;

  public int $totalItems;

  const MAX_ITEMS_PER_PAGE = 24;

  public function __construct(
    string $moduleKey,
    array $columns,
    ?CollectionInit $init = null
  ) {
    $this->moduleKey = $moduleKey;

    if (!$init) $init = new CollectionInit();

    $offset = ($init->page - 1) * ($init->itemsPerPage ? $init->itemsPerPage : self::MAX_ITEMS_PER_PAGE);

    $limit = $init->itemsPerPage > self::MAX_ITEMS_PER_PAGE || !$init->itemsPerPage ? self::MAX_ITEMS_PER_PAGE  : $init->itemsPerPage;

    $columnsWithTable = [];
    foreach ($columns as $k => $v) {
      if (!is_int($k)) {
        $columnsWithTable["mod_$moduleKey.$k"] = $v;
      } else {
        $columnsWithTable[$k] = "mod_$moduleKey.$v";
      }
    }

    if ($init->join) {
      foreach ($init->join->items as $joinItem) {
        if ($joinItem->type === 'categories') {
          $columnsWithTable[] = "categories.id as {$joinItem->fieldKey}_id";
          $columnsWithTable[] = "categories.title as {$joinItem->fieldKey}_title";
        }
      }
    }

    $q = Conn::table("mod_$moduleKey")
      ::select($columnsWithTable);

    if ($init->join) {
      foreach ($init->join->items as $joinItem) {
        $q = $q::leftJoin($joinItem->type);
      }

      $i = 0;
      foreach ($init->join->items as $joinItem) {
        if ($i === 0) {
          $q = $q::on("mod_$moduleKey.$joinItem->fieldKey", "$joinItem->type.id");
        } else {
          $q = $q::and("mod_$moduleKey.$joinItem->fieldKey", "$joinItem->type.id");
        }
        $i++;
      }
    }

    $q = $q::where('active', 1)
      ::and("IF(mod_$moduleKey.showFrom, CURRENT_TIMESTAMP > mod_$moduleKey.showFrom, TRUE)", 1)
      ::and("IF(mod_$moduleKey.showUp, CURRENT_TIMESTAMP < mod_$moduleKey.showUp, TRUE)", 1)
      ::orderBy("mod_$moduleKey.id", $init->order ? $init->order : 'DESC')
      ::limit($limit, $offset)
      ::send();

    if (!$q) throw new \Exception('Não foi possível obter os dados da coleção.');

    $list = $q->fetch_all(MYSQLI_ASSOC);
    if (!$init->returnTotalItems) {
      $this->data = $list;
    } else {
      $totalItems = $this->getTotalItems();

      $this->data = [
        "list" => $list,
        "totalItems" => $totalItems
      ];
    }
  }

  protected function getTotalItems(): int
  {
    return (int) Conn::query(
      "SELECT COUNT(id)
      FROM mod_$this->moduleKey"
    )->fetch_row()[0];
  }
}
