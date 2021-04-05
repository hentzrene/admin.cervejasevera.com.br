<?php

namespace Custom\Model;

use Core\Model\Configuration;
use \PHPMailer\PHPMailer\PHPMailer;
use \PHPMailer\PHPMailer\Exception;

class Email
{
  public static function contactUs(array $d)
  {
    $name = $d['name'];
    $phone = $d['phone'];
    $subject = $d['subject'];
    $text = $d['text'];
    $email = $d['email'];

    $message = "$text<br><hr>";

    if ($name) $message .= "<strong>Nome:</strong> $name<br>";
    if ($subject) $message .= "<strong>Assunto:</strong> $subject<br>";
    if ($phone) $message .= "<strong>Telefone:</strong> $phone<br>";
    if ($email) $message .= "<strong>E-mail:</strong> $email<br>";


    self::send('CONTATO REALIZADO PELO SITE!!!', $message, Configuration::getConfig('email')->recipient);
  }


  private static function send(string $subject, string $message, string $recipient, ?array $file = null)
  {
    $mail = new PHPMailer(true);
    $config = Configuration::getConfig('email');

    try {
      // $mail->SMTPDebug = SMTP::DEBUG_SERVER;
      // $mail->Debugoutput = 'html';

      $mail->isSMTP();
      $mail->Host = $config->host;
      $mail->SMTPAuth = true;
      $mail->SMTPSecure = 'ssl';
      $mail->Username = $config->user;
      $mail->Password = $config->password;
      $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` also accepted
      $mail->Port = $config->smtpPort;

      $mail->setFrom($config->user, $config->name);
      $mail->addAddress($recipient, $recipient);

      $mail->isHTML(true);
      $mail->Subject = utf8_decode($subject);

      $mail->Body = utf8_decode($message);
      $mail->AltBody = utf8_decode('Erro: Esse cliente de email não suporta HTML');

      if ($file && $file['type'] === 'application/pdf' && $file['size'] <= 5120000) {
        $mail->AddAttachment(
          $file['tmp_name'],
          $file['name']
        );
      }

      $mail->send();

      return true;
    } catch (Exception $e) {
      \Logger::log($e->getMessage());
      http_response_code(500);
    }
  }
}
