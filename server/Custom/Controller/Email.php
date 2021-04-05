<?php

namespace Custom\Controller;

use Custom\Model\Email as ModelEmail;

class Email
{
  public function contactUs($d)
  {
    ModelEmail::contactUs($d);
  }
}
