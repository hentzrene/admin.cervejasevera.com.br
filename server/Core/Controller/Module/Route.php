<?php

namespace Core\Controller\Module;

use Core\Model\Module\Module;
use Core\Model\Utility\Request as Req;

class Route
{
  public function getAll($d)
  {
    $viewClass = self::getViewClassOfModule($d['module']);
    self::callViewControllerMethod($viewClass, 'getAll', $d['module']);
  }

  public function get($d)
  {
    $viewClass = self::getViewClassOfModule($d['module']);
    self::callViewControllerMethod($viewClass, 'get', $d['module'], (int) $d['moduleItem']);
  }

  public function add($d)
  {
    $viewClass = self::getViewClassOfModule($d['module']);
    self::callViewControllerMethod($viewClass, 'add', $d['module']);
  }

  public function export($d)
  {
    $viewClass = self::getViewClassOfModule($d['module']);
    self::callViewControllerMethod($viewClass, 'export', $d['module'], Req::getAll());
  }

  public function remove($d)
  {
    $viewClass = self::getViewClassOfModule($d['module']);
    self::callViewControllerMethod($viewClass, 'remove', $d['module'], (int) $d['moduleItem']);
  }

  public function update($d)
  {
    $viewClass = self::getViewClassOfModule($d['module']);
    self::callViewControllerMethod($viewClass, 'update', $d['module'], (int) $d['moduleItem'], Req::getAll());
  }

  public function setProp($d)
  {
    $viewClass = self::getViewClassOfModule($d['module']);
    $value = Req::get($d['prop']) ? Req::get($d['prop']) : Req::get('value');
    self::callViewControllerMethod($viewClass, 'setProp', $d['module'], (int) $d['moduleItem'], $d['prop'], $value);
  }

  /**
   * Verifica se a view foi criada no código fonte, se sim retorna a classe dela.
   *
   * @param string $viewKey
   * @return string
   */
  private static function getViewClassOfModule(string $module): ?string
  {
    $viewKey = Module::has($module);

    if (!$viewKey) {
      throw new \Exception("Não existe um módulo com essa chave.");
    }

    $viewDir = ucfirst($viewKey);

    if (!file_exists(SYSTEM_ROOT . "/server/Module/View/$viewDir/Controller.php")) {
      throw new \Exception("Controller da view não configurado corretamente no código fonte.");
    }

    return "Module\View\\$viewDir\Controller";
  }

  /**
   * Chamar method do Controller da view.
   *
   * @param string $viewClass
   * @param string $method
   * @param [type] ...$params
   * @return void
   */
  private static function callViewControllerMethod(string $viewClass, string $method, ...$params)
  {
    if (method_exists($viewClass, $method)) {
      call_user_func([$viewClass, $method], ...$params);
    }
  }
}
