<?php

namespace Custom\Model;

use Core\Model\Utility\Conn;

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

    $q = Conn::table("mod_$moduleKey")
      ::select($columns)
      ::where('active', 1)
      ::and('IF(showFrom, CURRENT_TIMESTAMP > showFrom, TRUE)', 1)
      ::and('IF(showUp, CURRENT_TIMESTAMP < showUp, TRUE)', 1)
      ::orderBy('id', $init->order ? $init->order : 'DESC')
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
