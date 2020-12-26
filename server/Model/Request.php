<?php

namespace Model;

class Request
{
  /**
   * Corpo da requisição.
   *
   * @var null|object
   */
  private static $body = null;

  /**
   * Se o corpo da requisição foi analizado.
   *
   * @var boolean
   */
  private static $initiated = false;

  /**
   * Analizar corpo da requisição.
   *
   * @return void
   */
  public static function init(): void
  {
    if (self::$initiated) {
      throw new \Exception('O corpo da requisição já foi analizado.');
    }

    $input = file_get_contents("php://input");
    self::$body = json_decode($input ? $input : '{}');

    foreach ($_GET as $key => $value) {
      self::$body->{$key} = $value;
    }
    foreach ($_POST as $key => $value) {
      self::$body->{$key} = $value;
    }
    foreach ($_FILES as $key => $value) {
      if (!$value['tmp_name']) continue;
      self::$body->{$key} = $value;
    }

    unset(self::$body->route);

    self::$initiated = true;
  }

  /**
   * Obter valor de uma chave.
   *
   * @param string $key
   */
  public static function get(string $key)
  {
    if (!self::$initiated) {
      self::init();
    }

    return self::$body->{$key};
  }

  /**
   * Obter corpo da requisição.
   * 
   * @return object
   */
  public static function getAll(): object
  {
    if (!self::$initiated) {
      self::init();
    }

    return self::$body;
  }

  /**
   * Redefinir.
   *
   * @return void
   */
  public static function reset(): void
  {
    if (self::$initiated) {
      throw new \Exception('O corpo da requisição já foi analizado.');
    }

    self::$body = null;
    self::$initiated = false;
  }
}
