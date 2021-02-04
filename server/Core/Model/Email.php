<?php

namespace Core\Model;

use \PHPMailer\PHPMailer\PHPMailer;
use \PHPMailer\PHPMailer\Exception;

class Email
{
  private static function getConfig(): object
  {
    return (object) [
      'user' => 'no-reply@mrxweb.com.br',
      'password' => '8{9v+#t0vX@{UC)Hxt',
      'name' => 'No Reply',
      'host' => 'mail.mrxweb.com.br',
      'smtpPort' => '587',
      'recipient' => 'comercial@mrxweb.com.br'
    ];
  }

  private static function send(string $subject, string $message, string $recipient, ?array $file = null)
  {
    $mail = new PHPMailer(true);
    $config = self::getConfig();

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

  public static function contactUs(string $name, string $phone, string $subject, string $text)
  {
    $message = "$text<br>
        <hr>
        <strong>Nome:</strong> $name<br>
        <strong>Assunto:</strong> $subject<br>
        <strong>Telefone:</strong> $phone";

    self::send('CONTATO REALIZADO PELO SITE!!!', $message, self::getConfig()->recipient);
  }

  public static function sendApproveAccount(string $name, string $email)
  {
    $title = "Aprovar criação de conta!";
    $message = "$name $email";

    self::send($title, $message, self::getConfig()->recipient);
  }

  public static function sendRecoverPassword(string $email)
  {
    $title = "Recuperação de senha!";
    $message = "";

    self::send($title, $message, $email);
  }
}
