<?php

namespace Controller;

class Email
{
  public function sendApproveAccount($d)
  {
    \Model\Email::sendApproveAccount($d['name'], $d['email']);
  }

  public function sendRecoverPassword($d)
  {
    \Model\Email::sendRecoverPassword($d['email']);
  }
}
