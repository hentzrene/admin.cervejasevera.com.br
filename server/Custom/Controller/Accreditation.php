<?php

namespace Custom\Controller;

use Core\Model\Utility\Conn;
use Core\Model\Utility\Response;
use Custom\Model\Accreditation as ModelAccreditation;
use Custom\Model\PagSeguro;
use Core\Model\Utility\Request as Req;

class Accreditation
{
  public function generatePaymentLink()
  {
    $data = Req::getAll();
    $redirect = ModelAccreditation::generatePaymentLink($data);
    if ($redirect) {
      Response::set('redirect', $redirect);
    } else {
      Response::set('status', 'error');
      Response::status(500);
    }
    Response::send();
  }


  public function pagseguroNotification($d)
  {
    if (!isset($d['notificationCode'])) {
      http_response_code(400);
      die();
    }

    $email = "email=" . PagSeguro::AUTH_EMAIL;
    $token = "token=" . PagSeguro::AUTH_TOKEN;

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
