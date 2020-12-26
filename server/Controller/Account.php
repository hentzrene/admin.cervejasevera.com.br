<?php

namespace Controller;

use Model\Response;
use Model\Account as ModelAccount;
use Model\Request as Req;

class Account
{
  public function get($d)
  {
    if (!ON) {
      Response::set('status', 'error');
      Response::status(401);
    } else {
      Response::rawBody(ModelAccount::get($d['accountId']));
    }

    Response::send();
  }

  public function getAll()
  {
    if (!ON) {
      Response::set('status', 'error');
      Response::status(401);
    } else {
      Response::rawBody(ModelAccount::getAll());
    }

    Response::send();
  }

  public function add()
  {
    if (!ON) {
      Response::set('status', 'error');
      Response::status(401);
      Response::send();
    }

    ModelAccount::add(Req::getAll());
    $this->getAll();
  }

  public function remove($d)
  {
    if (!ON) {
      Response::set('status', 'error');
      Response::status(401);
    } else {
      ModelAccount::remove((int) $d['accountId']);
      Response::set('status', 'success');
    }
    Response::send();
  }

  public function update($d)
  {
    if (!ON) {
      Response::set('status', 'error');
      Response::status(401);
    } else {
      ModelAccount::update((int) $d['accountId'], Req::getAll());
      Response::set('status', 'success');
      Response::set('success', 'Conta alterado com sucesso.');
    }

    Response::send();
  }
}
