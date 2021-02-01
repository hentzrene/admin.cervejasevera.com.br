<?php

namespace Controller\Module;

use Model\Utility\Response;
use Model\Module\FieldType as ModelFieldType;

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
