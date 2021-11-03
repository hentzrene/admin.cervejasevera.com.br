<?php
class Logger
{
  const AUTH_LOGIN = 0;

  const AUTH_LOGOUT = 1;

  const AUTH_CHECK_IN = 2;

  const ACCOUNT_ADD = 3;

  const ACCOUNT_REMOVE = 4;

  const ACCOUNT_SET_PERMISSIONS = 5;

  const ACCOUNT_UPDATE = 6;

  public static function log(string $msg, string $file = __DIR__ . "/Logs/logs.log")
  {
    $date = date('d/m/Y H:i:s');
    error_log("[$date] $msg\n", 3, $file);
  }

  public static function auth($action, $accountId)
  {
    $msg = "";

    switch ($action) {
      case self::AUTH_LOGIN:
        $msg = "User with id $accountId joined.";
        break;
      case self::AUTH_LOGOUT:
        $msg = "User with id $accountId left.";
        break;
      case self::AUTH_CHECK_IN:
        $msg = "User with id $accountId has returned.";
        break;
    }

    self::log($msg, __DIR__ . "/Logs/auth.log");
  }

  public static function account($action, $userAccountId,  $targetAccountId)
  {
    $msg = "";
    switch ($action) {
      case self::ACCOUNT_ADD:
        $msg = "A new account of id $targetAccountId has been added by the user of id $userAccountId.";
        break;
      case self::ACCOUNT_REMOVE:
        $msg = "Account $targetAccountId was removed by user $userAccountId.";
        break;
      case self::ACCOUNT_SET_PERMISSIONS:
        $msg = "Account $targetAccountId had permissions changed by user $userAccountId.";
        break;
      case self::ACCOUNT_UPDATE:
        $msg = "Account $targetAccountId was changed by user $userAccountId.";
        break;
    }

    self::log($msg, __DIR__ . "/Logs/account.log");
  }

  public static function pagSeguro($msg)
  {
    self::log($msg, __DIR__ . "/Logs/pagseguro.log");
  }
}
