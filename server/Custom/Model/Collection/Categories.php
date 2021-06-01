<?php

namespace Custom\Model\Collection;

use Core\Model\Module\Field;
use Core\Model\Module\Module;
use Custom\Model\Conn\Conn;

class Categories
{
  public array $data;

  public function __construct(string $moduleKey, string $fieldKey)
  {
    $moduleFieldId = $this->getModuleFieldId($moduleKey, $fieldKey);

    $q = Conn::table('categories')
      ::select(['id', 'title'])
      ::where('modules_fields_id', $moduleFieldId)
      ::send();

    $this->data = $q ? $q->fetch_all(MYSQLI_ASSOC) : [];
  }

  protected function getModuleFieldId(string $moduleKey, string $fieldKey): int
  {
    $moduleId = $this->getModuleId($moduleKey);

    $moduleFieldId = Field::getId($moduleId, $fieldKey);

    if (!$moduleFieldId) throw new \Exception("Não foi possíbel obter o id do campo de chave '$fieldKey'");

    return $moduleFieldId;
  }

  protected function getModuleId(string $moduleKey): int
  {
    $moduleId = Module::getIdByKey($moduleKey);

    if (!$moduleId) throw new \Exception("Não foi possíbel obter o id do módulo de chave '{$moduleKey}'");

    return $moduleId;
  }
}
