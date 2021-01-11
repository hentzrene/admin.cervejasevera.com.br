<?php

namespace Controller;

use Model\Request as Req;
use Model\Response;
use Model\Setup as ModelSetup;

class Setup
{
  public function exec()
  {
    if (ModelSetup::exec(Req::getAll())) {
      Response::set('status', 'succes');
      Response::set('success', 'Banco de dados configudado.');
    } else {
      Response::set('status', 'error');
      Response::set('error', 'Não foi possível configurar o banco de dados.');
    }

    Response::send();
  }
}
