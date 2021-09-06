<?php

namespace Custom\Controller;

use Core\Model\Utility\Request as Req;
use Core\Model\Utility\Response as Res;
use Custom\Model\Module\Informations;


class Content
{
  public function getInformations()
  {
    Res::rawBody((new Informations())->get());

    Res::send();
  }
}
