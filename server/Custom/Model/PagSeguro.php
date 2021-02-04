<?php

namespace Custom\Model;

use Logger;
use \PagSeguro\Configuration\Configure as PagConfigure;
use \PagSeguro\Domains\{Phone as PagPhone, Document as PagDocument, Address as PagAddress};
use \PagSeguro\Enum\PaymentMethod\Group as PaymentMethodGroup;

class PagSeguro
{
  const NOTIFICATION_URL = ROOT . '/pagseguro-notification';

  const AUTH_EMAIL = 'cabral@mrxweb.com.br';

  // const AUTH_TOKEN = 'cf10fb68-22ff-4af8-9215-2aaef8bbaed5f894a72a4c0f9c93b985388e10ba7da82762-a58e-4a35-93b3-adf492879dc0';
  const AUTH_TOKEN = '3EFFD269954D470D94E7E56AADDCF163';

  const ENVIRONMENT = 'sandbox'; // production or sandbox

  /**
   * @var \PagSeguro\Domains\Requests\Payment
   */
  private static $payment = null;

  /**
   * Inicialiar PagSeguro.
   *
   * @return void
   */
  private static  function initialize(): void
  {
    \PagSeguro\Library::initialize();
    self::$payment = new \PagSeguro\Domains\Requests\Payment();
    PagConfigure::setAccountCredentials(self::AUTH_EMAIL, self::AUTH_TOKEN);
    PagConfigure::setEnvironment(self::ENVIRONMENT);
  }

  /**
   * Obter forma de pagamento.
   *
   * @param object $data {phone, cnpj, name, email}
   * @param string $reference
   * @return \PagSeguro\Domains\Requests\Payment
   */

  public static function getPayment(object $data, string $reference, ?string $notificationUrl, ?string $redirectUrl): \PagSeguro\Domains\Requests\Payment
  {
    if (!self::$payment) {
      self::initialize();
    }

    preg_match('/\(([0-9]{2})\) ([0-9]{4,5})-([0-9]{4})/', $data->phone, $pieces);
    $phone = new PagPhone($pieces[1], $pieces[2] . $pieces[3]);
    self::$payment->setSender()->setPhone()->instance($phone);

    $document = new PagDocument('CNPJ', $data->cnpj);
    self::$payment->setSender()->setName($data->name);
    self::$payment->setSender()->setDocument()->instance($document);
    self::$payment->setSender()->setEmail($data->email);

    self::$payment->setShipping()->setAddressRequired()->withParameters('FALSE');

    self::$payment->setCurrency('BRL');

    self::$payment->setReference($reference);

    if ($notificationUrl) {
      self::$payment->setNotificationUrl($notificationUrl);
    }

    if ($redirectUrl) {
      self::$payment->setRedirectUrl($redirectUrl);
    }

    self::$payment->acceptPaymentMethod()->groups(
      PaymentMethodGroup::CREDIT_CARD,
      PaymentMethodGroup::BOLETO
    );

    return self::$payment;
  }

  /**
   * Comprar.
   *
   * @param object $product {id, title, price }
   * @param integer $quant
   * @param \PagSeguro\Domains\Requests\Payment $payment
   * @return string|null
   */
  public static function buy(object $product, int $quant, \PagSeguro\Domains\Requests\Payment $payment): ?string
  {
    $payment->addItems()->withParameters(
      $product->id,
      $product->title,
      $quant,
      number_format((int) $product->price, 2, '.', '')
    );

    try {
      $response = $payment->register(PagConfigure::getAccountCredentials());
      return $response;
    } catch (\Exception $e) {
      Logger::pagSeguro('Aconteceu um erro durante a compra: ' . $e->getMessage());
      throw new \Exception($e->getMessage());
      return null;
    }
  }
}
