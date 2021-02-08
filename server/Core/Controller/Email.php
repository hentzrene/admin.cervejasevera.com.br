<?php

namespace Core\Controller;

use Core\Model\Email as ModelEmail;
use Core\Model\Utility\Request as Req;
use Core\Model\Utility\Response;

class Email
{
  public function getConfig($d)
  {
    if (ACCOUNT_TYPE !== 1) {
      Response::set('status', 'error');
      Response::status(401);
    } else {
      Response::rawBody(ModelEmail::getConfig($d));
    }

    Response::send();
  }

  public function updateConfig()
  {
    if (ACCOUNT_TYPE !== 1) {
      Response::set('status', 'error');
      Response::status(401);
    } else {
      ModelEmail::updateConfig(Req::getAll());
      Response::set('status', 'succes');
      Response::set('success', 'Alteração realizada.');
    }

    Response::send();
  }

  public function contactUs($d)
  {
    ModelEmail::contactUs($d['name'], $d['phone'], $d['subject'], $d['message']);
  }

  public function sendApproveAccount($d)
  {
    ModelEmail::sendApproveAccount($d['name'], $d['email']);
  }

  public function sendRecoverPassword($d)
  {
    ModelEmail::sendRecoverPassword($d['email']);
  }
}
