<?php

namespace Core\Controller\Account;

use Enum\Output;
use Core\Model\Account\Account;
use Core\Model\Utility\Response;
use Core\Model\Account\Auth as ModelAuth;
use Core\Model\Utility\Request as Req;

class Auth
{
  public function login()
  {
    try {
      Response::rawBody(
        ModelAuth::login(
          Req::get('email'),
          Req::get('password')
        )
      );
    } catch (\Exception $e) {
      switch ($e->getCode()) {
        case Output::ACCOUNT_LOGIN['NONE_EMAIL']['code']:
          Response::set('status', 'error');
          Response::set('error', Output::ACCOUNT_LOGIN['NONE_EMAIL']['message']);
          Response::status(401);
          break;
        case Output::ACCOUNT_LOGIN['INVALID_PASSWORD']['code']:
          Response::set('status', 'error');
          Response::set('error', Output::ACCOUNT_LOGIN['INVALID_PASSWORD']['message']);
          Response::status(403);
          break;
        default:
          Response::set('status', 'error');
          Response::set('error', $e->getMessage());
          Response::status(500);
          break;
      }
    }

    Response::send();
  }

  public function logout()
  {
    if (ModelAuth::logout(TOKEN)) {
      Response::status(200);
    } else {
      Response::status(401);
    }

    Response::send();
  }

  public function checkIn()
  {
    if (!ON) {
      Response::set('status', 'error');
      Response::status(401);
    } else {
      Response::rawBody(Account::get(ACCOUNT_ID));
    }

    Response::send();
  }
}
