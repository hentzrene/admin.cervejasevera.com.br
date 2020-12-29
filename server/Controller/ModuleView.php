<?php

namespace Controller;

use Model\ModuleView as ModelModuleView;
use Model\Response;

class ModuleView
{
  public function getAll()
  {
    if (!ON) {
      Response::set('status', 'error');
      Response::status(401);
    } else {
      Response::rawBody(ModelModuleView::getAll());
    }
    Response::send();
  }
}
