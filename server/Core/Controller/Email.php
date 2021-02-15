<?php

namespace Core\Controller;

use Core\Model\Email as ModelEmail;

class Email
{
  public function contactUs($d)
  {
    ModelEmail::contactUs($d);
  }
}
