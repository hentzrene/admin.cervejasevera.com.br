<?php

namespace Model;

use Logger;
use \PagSeguro\Configuration\Configure as PagConfigure;
use \PagSeguro\Domains\{Phone as PagPhone, Document as PagDocument, Address as PagAddress};
use \PagSeguro\Enum\PaymentMethod\Group as PaymentMethodGroup;
use \PagSeguro\Enum\Shipping\Type as ShippingType;

class PagSeguro
{
  const NOTIFICATION_URL = '';

  const ACCOUNT_TOKEN = '';

  const ACCOUNT_EMAIL = '';

  /**
   * @var \PagSeguro\Domains\Requests\Payment
   */
  private $payment = null;

  /**
   * Inicialiar PagSeguro.
   *
   * @return void
   */
  private static  function initialize(): void
  {
    \PagSeguro\Library::initialize();
    self::$payment = new \PagSeguro\Domains\Requests\Payment();

    self::$payment->payment::setAccountCredentials(self::ACCOUNT_EMAIL, self::ACCOUNT_TOKEN);
  }

  /**
   * Obter forma de pagamento.
   *
   * @param object $data {phone, cpf, name, email}
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

    $document = new PagDocument('CPF', $data->cpf);
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
      // PaymentMethodGroup::CREDIT_CARD,
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
    $payment->payment->addItems()->withParameters(
      $product->id,
      $product->title,
      $quant,
      number_format($product->price, 2, '.', '')
    );

    try {
      $response = $payment->payment->register(PagConfigure::getAccountCredentials());
      return $response;
    } catch (\Exception $e) {
      Logger::pagSeguro('Aconteceu um erro durante a compra: ' . $e->getMessage());
    }
  }
}
