<?php

namespace Controller\Module;

use Model\Module\View as ModelView;
use Model\Utility\Response;

class View
{
  public function getAll()
  {
    if (!ON) {
      Response::set('status', 'error');
      Response::status(401);
    } else {
      Response::rawBody(ModelView::getAll());
    }
    Response::send();
  }
}
