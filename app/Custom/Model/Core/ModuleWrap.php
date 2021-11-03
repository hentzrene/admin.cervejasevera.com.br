<?php

namespace Custom\Model\Core;

use Custom\Model\Core\View\Item;
use Custom\Model\Core\View\Table;


/**
 * Essa classe deve ser extendida pelas classes dos módulos do projeto.
 */
class ModuleWrap
{
  /**
   * @var Table|Item
   */
  public $module;

  /**
   * Classes das views.
   */
  private const VIEWS = [
    'table' => Table::class,
    'item' => Item::class
  ];

  public function __construct(string $moduleKey)
  {
    // Obter chave da view.
    $viewKey = self::getViewKey($moduleKey);

    // Obter classe da view.
    $className = self::VIEWS[$viewKey];

    // Instanciar classe da view.
    $this->module = new $className($moduleKey);
  }

  /**
   * Obter a chave da view pela chave do módulo.
   *
   * @param string $moduleKey
   * @return string
   */
  private static function getViewKey(string $moduleKey): string
  {
    return DB::table('modules_views')
      ->join('modules', 'modules.modules_views_id', '=', 'modules_views.id')
      ->where('modules.key', $moduleKey)
      ->value('modules_views.key');
  }
}
