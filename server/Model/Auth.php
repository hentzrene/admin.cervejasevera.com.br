<?php

namespace Model;

use Model\Conn;
use Enum\Table;
use Enum\Output;
use Logger;

class Auth
{
  /**
   * Criar sessão.
   *
   * @param string $email
   * @param string $password
   * @return string
   */
  public static function login(string $email, string $password): object
  {
    // Verificar se existe cadastro com esse e-mail.
    $registered = Account::registeredEmail($email);
    if (!$registered) {
      throw new \Exception(
        Output::ACCOUNT_LOGIN['NONE_EMAIL']['message'],
        Output::ACCOUNT_LOGIN['NONE_EMAIL']['code']
      );
    }
    [$id, $hash_password] = $registered;

    // Verificar se a senha está correta.
    if (!password_verify($password, $hash_password)) {
      throw new \Exception(
        Output::ACCOUNT_LOGIN['INVALID_PASSWORD']['message'],
        Output::ACCOUNT_LOGIN['INVALID_PASSWORD']['code']
      );
    }

    // Iniciar sessão.
    $token = bin2hex(random_bytes(20));
    $email = addslashes($email);

    Conn::table(Table::SESSIONS)
      ::deleteWhere(
        'accounts_id',
        $id
      )
      ::send();

    Conn::table(Table::SESSIONS)
      ::insert(
        [
          'token',
          'accounts_id',
          'expireAt'
        ],
        [
          "'$token'",
          $id,
          'DATE_ADD(CURRENT_TIMESTAMP(), INTERVAL 1 DAY)'
        ]
      )
      ::send();

    Logger::auth(Logger::AUTH_LOGIN, $id);

    $account =  Account::get($id);
    $account->token = $token;

    return $account;
  }

  /**
   * Encerrar sessão ativa.
   *
   * @return boolean
   */
  public static function logout($token): bool
  {
    $q = Conn::table(Table::SESSIONS)
      ::deleteWhere('token', "'$token'")
      ::send();

    if (!$q) {
      throw new \Exception(Output::ACCOUNT_LOGOUT);
    }

    Logger::auth(Logger::AUTH_LOGOUT, ACCOUNT_ID);
    return true;
  }

  /**
   * Verificar se há sessão ativa com o token especificado.
   *
   * @param string $token
   * @return int Retornar o id da conta da sessão ativa caso houver, se não null.
   */
  public static function checkIn(string $token): ?int
  {
    $token = addslashes($token);

    $q = Conn::table(Table::SESSIONS)
      ::select('accounts_id')
      ::where('token', "'$token'")
      ::and("CURRENT_TIMESTAMP()", "expireAt", "<=")
      ::send();

    if (!$q) {
      return null;
    }

    $accountId = $q->fetch_row()[0];

    Logger::auth(Logger::AUTH_CHECK_IN, $accountId);
    return $accountId;
  }
}
