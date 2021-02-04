<?php

namespace Core\Controller;

class Email
{
  public function contactUs($d)
  {
    \Model\Email::contactUs($d['name'], $d['phone'], $d['subject'], $d['message']);
  }

  public function sendApproveAccount($d)
  {
    \Model\Email::sendApproveAccount($d['name'], $d['email']);
  }

  public function sendRecoverPassword($d)
  {
    \Model\Email::sendRecoverPassword($d['email']);
  }
}
