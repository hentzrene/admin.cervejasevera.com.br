<?php

namespace Controller;

use Model\Conn;
use Model\Response;
use Model\PagSeguro as ModelPagSeguro;
use Model\Request as Req;

class PagSeguro
{
  public function buy()
  {
    // { phone, cpf, name, email, fee }
    $data = Req::getAll();

    $reference = strtoupper(uniqid('R-'));

    $userInfo = (object) [
      'phone' => $data->phone,
      'cpf' => $data->cpf,
      'name' => $data->name,
      'email' => $data->email
    ];
    $payment = ModelPagSeguro::getPayment(
      $userInfo,
      $reference,
      ModelPagSeguro::NOTIFICATION_URL,
      $data->referer
    );

    $product = (object) [
      'id' => 1,
      'title' => 'Taxa',
      'price' => $data->fee
    ];
    $redirect = ModelPagSeguro::buy($product, 1, $payment);

    die(json_encode(['redirect' => $redirect]));
  }


  public function pagseguroNotification($d)
  {
    if (!isset($d['notificationCode'])) {
      http_response_code(400);
      die();
    }

    $email = "email=" . ModelPagSeguro::ACCOUNT_TOKEN;
    $token = "token=" . ModelPagSeguro::ACCOUNT_TOKEN;

    $session = curl_init(
      "https://ws.pagseguro.uol.com.br/v2/transactions/notifications/{$d['notificationCode']}?{$email}&{$token}"
    );
    curl_setopt($session, CURLOPT_RETURNTRANSFER, 1);
    $response = simplexml_load_string(curl_exec($session));
    curl_close($session);

    $date = strtotime($response->date);
    $reference = $response->reference;
    $code = $response->code;

    Conn::query(
      "INSERT INTO pagseguro_notifications(code, date, reference, type) 
      VALUES ({$code}, '{$date}', '{$reference}', '{$d['notificationType']}');"
    );
  }
}
