<?php

namespace Core\Model\Account;

use Core\Model\Utility\DB;
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

    DB::table(Table::SESSIONS)
      ->insert([
        'token' => $token,
        'accounts_id' => $id,
        'expireAt' => DB::raw('DATE_ADD(CURRENT_TIMESTAMP(), INTERVAL 1 DAY)')
      ]);

    Logger::auth(Logger::AUTH_LOGIN, $id);

    $account = Account::get($id);
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
    $rowsDeleteds = DB::table(Table::SESSIONS)
      ->where('token', $token)
      ->delete();

    if ($rowsDeleteds === 0) {
      throw new \Exception(Output::ACCOUNT_LOGOUT);
    }

    Logger::auth(Logger::AUTH_LOGOUT, ACCOUNT_ID);
    return true;
  }

  /**
   * Verificar se há sessão ativa com o token especificado.
   *
   * @param string $token
   * @return object Retornar dados da conta da sessão ativa caso houver, se não null.
   */
  public static function checkIn(string $token): ?object
  {
    $accountId = DB::table(Table::SESSIONS)
      ->where('token', $token)
      ->whereRaw('CURRENT_TIMESTAMP() <= expireAt')
      ->value('accounts_id');

    if (!$accountId) {
      return null;
    }

    $accountTypeId = DB::table(Table::ACCOUNTS)
      ->where('id', $accountId)
      ->value('accounts_types_id');

    if (!$accountTypeId) {
      return null;
    }

    Logger::auth(Logger::AUTH_CHECK_IN, $accountId);

    $accountData = (object) [
      'accountId' => $accountId,
      'accountTypeId' => $accountTypeId
    ];

    return $accountData;
  }
}