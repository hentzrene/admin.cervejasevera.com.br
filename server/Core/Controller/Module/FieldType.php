<?php

namespace Core\Controller\Module;

use Core\Model\Utility\Response;
use Core\Model\Module\FieldType as ModelFieldType;

class FieldType
{
  public function getAll()
  {
    if (!ON) {
      Response::set('status', 'error');
      Response::status(401);
    } else {
      Response::rawBody(ModelFieldType::getAll());
    }
    Response::send();
  }
}
