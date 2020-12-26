<?php

namespace Model;

use \PHPMailer\PHPMailer\PHPMailer;
use \PHPMailer\PHPMailer\Exception;
use \Config\Email as ConfigEmail;

class Email
{
  private static function send(string $subject, string $message, string $recipient, ?array $file = null)
  {
    $mail = new PHPMailer(true);

    try {
      // $mail->SMTPDebug = SMTP::DEBUG_SERVER;
      // $mail->Debugoutput = 'html';

      $mail->isSMTP();
      $mail->Host = ConfigEmail::HOST;
      $mail->SMTPAuth = true;
      $mail->SMTPSecure = 'ssl';
      $mail->Username = ConfigEmail::USER;
      $mail->Password = ConfigEmail::PASSWORD;
      $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` also accepted
      $mail->Port = ConfigEmail::SMTP_PORT;

      $mail->setFrom(ConfigEmail::USER, ConfigEmail::NAME);
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

  public static function sendApproveAccount(string $name, string $email)
  {
    $title = "Aprovar criação de conta!";
    $message = "$name $email";

    self::send($title, $message, ConfigEmail::RECIPIENT);
  }

  public static function sendRecoverPassword(string $email)
  {
    $title = "Recuperação de senha!";
    $message = "";

    self::send($title, $message, $email);
  }
}
