<?php

namespace Controller;

use Model\Response;
use Model\ModuleFieldType as ModelModuleFieldType;

class ModuleFieldType
{
  public function getAll()
  {
    if (!ON) {
      Response::set('status', 'error');
      Response::status(401);
    } else {
      Response::rawBody(ModelModuleFieldType::getAll());
    }
    Response::send();
  }
}
