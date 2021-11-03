<?php

namespace Core\Controller\Module;

use Core\Model\Module\View as ModelView;
use Core\Model\Utility\Response;

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
