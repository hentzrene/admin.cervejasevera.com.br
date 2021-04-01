<?php

namespace Custom\Controller;

use Core\Model\Utility\Response;
use Custom\Model\Content as Cont;

class Content
{
  public function getAllInformations()
  {
    Response::rawBody(
      Cont::getItem(
        'informations',
        ['img', 'name', 'instagram', 'facebook', 'email', 'whatsapp'],
        1
      )
    );

    Response::send();
  }
}
