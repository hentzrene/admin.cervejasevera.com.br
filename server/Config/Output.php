<?php

namespace Config;

class Output
{
  const ACCOUNT_CREATION = [
    'EXISTING_EMAIL' => [
      'code' => 1,
      'message' => 'Já foi utilizado este email na criação de uma conta.'
    ],
    'MISSING_FIELD' => [
      'code' => 3,
      'message' => 'Algum campo não foi informado.'
    ],
  ];

  const ACCOUNT_LOGIN = [
    'NONE_EMAIL' => [
      'code' => 1,
      'message' => 'Não existe uma conta com esse e-mail.'
    ],
    'INVALID_PASSWORD' => [
      'code' => 2,
      'message' => 'Senha incorreta.'
    ],
    'NO_ACTIVE' => [
      'code' => 3,
      'message' => 'Esta conta ainda não está ativada.'
    ],
  ];

  const ACCOUNT_LOGOUT = 'O usuário já está deslogado.';

  const ACCOUNT_NO_CONTENT = 'Não foi possível encontrar a conta.';

  const ACCOUNT_REMOVE_UNAUTHORIZED = 'Não permitido a remoção da conta.';
}
