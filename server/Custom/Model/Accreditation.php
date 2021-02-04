<?php

namespace Custom\Model;

use Model\Utility\Conn;

class Accreditation
{
  /**
   * Gerar link de pagamento.
   *
   * @param object $data {
   *   provider,
   *   providerAddress,
   *   providerCNPJ,
   *   providerEmail,
   *   providerFantasyName,
   *   providerResponsibleEmail,
   *   providerResponsibleName,
   *   providerResponsibleTel,
   *   providerStateRegistration,
   *   providerTel
   *   contractorAuthorizedPersonnel,
   *   contractorCompanyName,
   *   contractorDateEnd,
   *   contractorDateStart,
   *   contractorEmail,
   *   contractorFantasyName,
   *   contractorResponsibleName,
   *   contractorService,
   *   contractorTel,
   *   electricityFeeEquipments,
   *  }
   * @return string
   */
  public static function generatePaymentLink(object $data): ?string
  {
    $requiredsParams = [
      'providerCompanyName',
      'providerAddress',
      'providerCNPJ',
      'providerEmail',
      'providerFantasyName',
      'providerResponsibleEmail',
      'providerResponsibleName',
      'providerResponsibleTel',
      'providerStateRegistration',
      'providerTel',
      'contractorAuthorizedPersonnel',
      'contractorCompanyName',
      'contractorDateEnd',
      'contractorDateStart',
      'contractorEmail',
      'contractorFantasyName',
      'contractorResponsibleName',
      'contractorService',
      'contractorTel',
      'electricityFeeEquipments',
    ];

    foreach ($requiredsParams as $param) {
      if (!property_exists($data, $param) || !$data->{$param}) {
        throw new \Exception("O parâmentro `$param` não foi fornecido.");
      }
    }

    foreach ($data as $param => $value) {
      $data->{$param} = addslashes($value);
    }

    $reference = strtoupper(uniqid('R-'));

    $allEquipments = Equipment::getAll();
    $electricityFeeEquipments = json_decode(preg_replace('/\\\"/', '"', $data->electricityFeeEquipments));
    $kws = 0;

    foreach ($electricityFeeEquipments as $k => $feeEquipment) {
      foreach ($allEquipments as $equipment) {
        if ($equipment['id'] == $feeEquipment->id) {
          $kws += ((float) str_replace(',', '.', $equipment['kw'])) * ((int) $feeEquipment->quant);
        }
      }
    }

    if ($kws <= 5) {
      return "/completado?protocolo=$reference";
    }

    $fee =  ($kws - 5) * 200;

    Conn::table('accreditation')
      ::insert(
        [
          'provider_company_name',
          'provider_address',
          'provider_CNPJ',
          'provider_email',
          'provider_fantasy_name',
          'provider_responsible_email',
          'provider_responsible_name',
          'provider_responsible_tel',
          'provider_state_registration',
          'provider_tel',
          'contractor_authorized_personnel',
          'contractor_company_name',
          'contractor_date_end',
          'contractor_date_start',
          'contractor_email',
          'contractor_fantasy_name',
          'contractor_responsible_name',
          'contractor_service',
          'contractor_tel',
          'fee',
          'reference',
          'electricity_fee_equipments'
        ],
        [
          "'$data->providerCompanyName'",
          "'$data->providerAddress'",
          "'$data->providerCNPJ'",
          "'$data->providerEmail'",
          "'$data->providerFantasyName'",
          "'$data->providerResponsibleEmail'",
          "'$data->providerResponsibleName'",
          "'$data->providerResponsibleTel'",
          "'$data->providerStateRegistration'",
          "'$data->providerTel'",
          "'$data->contractorAuthorizedPersonnel'",
          "'$data->contractorCompanyName'",
          "'$data->contractorDateEnd'",
          "'$data->contractorDateStart'",
          "'$data->contractorEmail'",
          "'$data->contractorFantasyName'",
          "'$data->contractorResponsibleName'",
          "'$data->contractorService'",
          "'$data->contractorTel'",
          "'$fee'",
          "'$reference'",
          "'$data->electricityFeeEquipments'"
        ]
      )::send();

    $companyInfo = (object) [
      'phone' => $data->providerTel,
      'cpnj' => $data->providerCNPJ,
      'name' => $data->providerCompanyName,
      'email' => $data->providerEmail
    ];

    $payment = PagSeguro::getPayment(
      $companyInfo,
      $reference,
      PagSeguro::NOTIFICATION_URL,
      ROOT . "/completado?protocolo=$reference"
    );

    $product = (object) [
      'id' => 1,
      'title' => 'Taxa',
      'price' => number_format($fee, 2)
    ];

    return PagSeguro::buy($product, 1, $payment);
  }
}
