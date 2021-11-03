<?php

namespace Core\Model\Utility;

class Response
{
  /**
   * Corpo da resposta.
   *
   * @var object|null
   */
  private static $body = null;

  /**
   * Se foi utilizado a função rawBody;
   *
   * @var boolean
   */
  private static $rawModified = false;

  /**
   * Estado da resposta.
   *
   * @var integer
   */
  private static $status = 202;

  /**
   * Definir valor com chave.
   *
   * @param string $key
   * @param string $value
   * @return void
   */
  public static function set(string $key, string $value)
  {
    if (self::$rawModified) {
      throw new \Exception('O corpo de resposta já foi definido.');
    } else if (!self::$body) {
      self::$body = (object) [];
    }

    self::$body->{$key} = $value;
  }

  /**
   * Obter valor de uma chave.
   *
   * @param string $key
   * @return void
   */
  public static function get(string $key)
  {
    if (!self::$rawModified) {
      throw new \Exception('O corpo foi definido manualmente.');
    }

    return self::$body->{$key};
  }

  public static function rawBody($body)
  {
    self::$body = $body;
    self::$rawModified = true;
  }

  /**
   * Remover chave.
   *
   * @param string $key
   * @return void
   */
  public static function remove(string $key)
  {
    if (!self::$rawModified) {
      throw new \Exception('O corpo foi definido manualmente.');
    }

    unset(self::$body->{$key});
  }

  /**
   * Definir estado.
   *
   * @param integer $code
   * @return void
   */
  public static function status(int $code)
  {
    self::$status = $code;
  }

  /**
   * Redefinir.
   *
   * @param integer $code
   * @return void
   */
  public static function reset()
  {
    self::$status = 202;
    self::$rawModified = false;
    self::$body = (object) [];
  }

  /**
   * Enviar resposta.
   *
   * @return void
   */
  public static function send()
  {
    http_response_code(self::$status);
    if (self::$status !== 404) {
      echo json_encode(!is_null(self::$body) ? self::$body : (object) []);
    }
    die();
  }
}
