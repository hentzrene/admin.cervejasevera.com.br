<?php

namespace Core\Controller\Module;

use Core\Model\Module\SectionField as ModuleSectionField;
use Core\Model\Utility\Request as Req;
use Core\Model\Utility\Response;

class SectionField
{
  public function getAllItems()
  {
    if (!ON) {
      Response::set('status', 'error');
      Response::status(401);
      Response::send();
    }

    $moduleId = Req::get('moduleId') ? (int) Req::get('moduleId') : null;

    Response::rawBody(
      ModuleSectionField::getAllItems($moduleId)
    );

    Response::send();
  }

  public function addItem()
  {
    if (!ON) {
      Response::set('status', 'error');
      Response::status(401);
    } else {
      try {
        $moduleId = (int) Req::get('moduleId');
        ModuleSectionField::addItem(Req::get('title'), $moduleId);

        Response::rawBody(
          ModuleSectionField::getAllItems($moduleId)
        );
      } catch (\Exception $e) {
        Response::set('status', 'error');
        Response::set('error', 'Não foi possível adicionar a seção.');
      }
    }

    Response::send();
  }

  public function updateItem($d)
  {
    if (!ON) {
      Response::set('status', 'error');
      Response::status(401);
    } else {
      try {
        ModuleSectionField::updateItem((int) $d['sectionId'], Req::get('title'));

        Response::set('status', 'success');
        Response::set('success', 'Alterado título com sucesso.');
      } catch (\Exception $e) {
        Response::set('status', 'error');
        Response::set('error', 'Não foi possível alterar a seção.');
      }
    }

    Response::send();
  }

  public function removeItem($d)
  {
    if (!ON) {
      Response::set('status', 'error');
      Response::status(401);
      Response::send();
    } else {
      ModuleSectionField::removeItem((int) $d['sectionId']);
      Response::set('status', 'success');
    }
    Response::send();
  }
}
