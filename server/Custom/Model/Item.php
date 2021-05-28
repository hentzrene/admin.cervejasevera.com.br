<?php

namespace Custom\Model;

use Core\Model\Module\Field;
use Core\Model\Module\Module;
use Core\Model\Utility\Conn;

class Item
{
  protected string $moduleKey;

  protected int $itemId;

  protected int $moduleId;

  protected array $moduleFieldsIds = [];

  public object $data;

  public function __construct(string $moduleKey, array $columns, int $itemId)
  {
    $q = Conn::table("mod_$moduleKey")
      ::select($columns)
      ::where('id', $itemId)
      ::orderBy('id', 'DESC')
      ::send();

    if (!$q) throw new \Exception('Não foi possível obter os dados do Itemo.');

    $this->itemId = $itemId;
    $this->moduleKey = $moduleKey;

    $this->data = $q->fetch_object();
  }

  public function withImages(string $fieldKey): self
  {
    $moduleId = $this->getModuleId();
    $fieldId = $this->getModuleFieldId($fieldKey);

    $imgs = Conn::table('images')
      ::select(['path'])
      ::where('modules_id', $moduleId)
      ::and('modules_fields_id', $fieldId)
      ::and('item_id', $this->itemId)
      ::send();

    if ($imgs) {
      $this->data->imgs = array_map(function ($row) {
        return $row[0];
      }, $imgs->fetch_all(MYSQLI_NUM));
    } else $this->data->imgs = [];

    return $this;
  }

  public function withAttachments(string $fieldKey): self
  {
    $moduleId = $this->getModuleId();
    $fieldId = $this->getModuleFieldId($fieldKey);

    $attachments = Conn::table('files')
      ::select(['title', 'path'])
      ::where('modules_id', $moduleId)
      ::and('modules_fields_id', $fieldId)
      ::and('item_id', $this->itemId)
      ::send();


    $this->data->attachments = $attachments ? $attachments->fetch_all(MYSQLI_ASSOC) : [];

    return $this;
  }

  protected function getModuleId(): int
  {
    if (!isset($this->moduleId)) {
      $this->moduleId = Module::getIdByKey($this->moduleKey);
    }

    if (!$this->moduleId) throw new \Exception("Não foi possíbel obter o id do módulo de chave '{$this->moduleKey}'");

    return $this->moduleId;
  }

  protected function getModuleFieldId(string $fieldKey): int
  {
    if (isset($this->moduleFieldsIds[$fieldKey])) {
      return $this->moduleFieldsIds[$fieldKey];
    }

    if (!isset($this->moduleId)) {
      $this->moduleId = Module::getIdByKey($this->moduleKey);
    }

    $this->moduleFieldsIds[$fieldKey] = Field::getId($this->moduleId, $fieldKey);

    if (!$this->moduleFieldsIds[$fieldKey]) throw new \Exception("Não foi possíbel obter o id do campo de chave '$fieldKey'");

    return $this->moduleFieldsIds[$fieldKey];
  }
}
