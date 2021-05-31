<?php

namespace Custom\Controller;

use Custom\Model\Email as ModelEmail;

class Email
{
  public function contactUs($d)
  {
    ModelEmail::contactUs($d['name'], $d['email'], $d['text']);
  }

  public function workWithUs($d)
  {
    ModelEmail::workWithUs($d['name'], $d['email'], $d['tel'], $_FILES['attachment']);
  }
}
