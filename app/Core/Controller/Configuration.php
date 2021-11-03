<?php

namespace Core\Controller;

use Core\Model\Configuration as  ModelConfiguration;
use Core\Model\Utility\Request as Req;
use Core\Model\Utility\Response;

class Configuration
{
  public function getConfig($d)
  {
    if (ACCOUNT_TYPE === 1) {
      Response::rawBody(ModelConfiguration::getConfig($d['key']));
    } else {
      Response::rawBody(ModelConfiguration::getConfig($d['key'], true));
    }

    Response::send();
  }

  public function updateConfig($d)
  {
    if (ACCOUNT_TYPE !== 1) {
      Response::set('status', 'error');
      Response::status(401);
    } else {
      ModelConfiguration::updateConfig($d['key'], Req::getAll());
      Response::set('status', 'succes');
      Response::set('success', 'Alteração realizada.');
    }

    Response::send();
  }
}
