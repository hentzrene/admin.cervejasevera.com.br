<?php

namespace Custom\Controller;

use Core\Model\Utility\Request as Req;
use Core\Model\Utility\Response as Res;
use Custom\Model\Module\Biography;
use Custom\Model\Module\Informations;
use Custom\Model\Module\Schedule;
use Custom\Model\Module\Shows;

class Content
{
  public function getInformations()
  {
    Res::rawBody((new Informations())->get());

    Res::send();
  }


  public function getBiography()
  {
    Res::rawBody((new Biography())->get());

    Res::send();
  }

  public function getAllShows()
  {
    Res::rawBody((new Shows())->getList());

    Res::send();
  }

  public function getShow($d)
  {
    Res::rawBody((new Shows())->getItem((int) $d['itemId']));

    Res::send();
  }

  public function getAllSchedule()
  {
    Res::rawBody((new Schedule())->getList());

    Res::send();
  }
}
