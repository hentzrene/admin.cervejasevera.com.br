<?php

namespace Custom\Model;

use Core\Model\Configuration;
use \PHPMailer\PHPMailer\PHPMailer;
use \PHPMailer\PHPMailer\Exception;

class Email
{
  public static function contactUs(string $name, string $email, string $text)
  {
    $message = "$text<br>
          <hr>
          <br>
          <strong>Nome: $name </strong><br>
          <strong>E-mail: $email </strong>";

    self::send('Mensagem do formulário Fale Conosco!', $message);
  }

  public static function workWithUs(string $name, string $email, string $tel, array $file)
  {
    $message = "Contato realizado pelo formulário \"Trabalhe Conosco\".<br>
          Segue currículo em anexo.
          <hr>
          <br>
          <strong>Nome: $name </strong><br>
          <strong>E-mail: $email </strong><br>
          <strong>Telefone: $tel</strong>";

    self::send('Mensagem do formulário Trabalhe Conosco!', $message, null, $file);
  }

  public static function send(string $subject, string $message, ?string $recipient = null, ?array $file = null)
  {
    $mail = new PHPMailer(true);
    $config = Configuration::getConfig('email');
    $recipient = $recipient ?? $config->recipient;

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

      if ($file) {
        $acceptedAttachmentFormats = [
          'application/pdf',
          'application/msword',
          'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
        ];

        if ($file['size'] > 5120000) {
          throw new \Exception('O tamanho máximo de anexo permitido é 50MB.');
        }

        if (!in_array($file['type'], $acceptedAttachmentFormats)) {
          throw new \Exception('Os únicos arquivos permitidos são os doc, docx e pdf.');
        }

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
