<?php

namespace Core\Controller;

use Core\Model\Email as ModelEmail;

class Email
{
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
