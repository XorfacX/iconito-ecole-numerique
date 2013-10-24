<?php

use \ActivityStream\Client\Model\Resource;
use \ActivityStream\Client\Model\ResourceInterface;

/**
 * @package     iconito
 * @subpackage  agenda
 * @author      Jérémy FOURNAISE <jeremy.fournaise@isics.fr>
 */
class DAORecordEvent implements ResourceInterface
{
  public function __toString()
  {
    return $this->getTitleEvent();
  }

  /**
   * Return a resource from the current Object
   *
   * @return Resource
   */
  public function toResource()
  {
    $resource = new EcoleNumeriqueActivityStreamResource(
      $this->title_event,
      get_class($this),
      $this->id_event
    );

    $resource->setAttributes(array(
      'type' => $this->type_event,
      'all_day_long_event' => $this->alldaylong_event,
      'datedeb_event' => $this->datedeb_event,
      'datefin_event' => $this->datefin_event,
      'desc_event' => $this->desc_event,
      'end_repeat_date_event' => $this->endrepeatdate_event,
      'every_day_event' => $this->everyday_event,
      'every_month_event' => $this->everymonth_event,
      'every_week_event' => $this->everyweek_event,
      'every_year_event' => $this->everyyear_event,
      'heure_deb_event' => $this->heuredeb_event,
      'heure_fin_event' => $this->heurefin_event,
      'place' => $this->place_event,
      'id_agenda' => $this->id_agenda,
    ));

    return $resource;
  }
}

class DAOEvent
{
  /**
   * Retourne les événements d'un agenda pour un intervalle donné
   *
   * @param integer  $agendaId    Identifiant de l'agenda
   * @param string   $dateDebut   Date de début de l'intervalle (format Ymd)
   * @param string   $dateFin     Date de fin de l'intervalle (format Ymd)
   *
   * @return CopixDAORecordIterator
   */
  public function findByAgendaAndDateInterval($agendaId, $dateDebut, $dateFin)
  {
    $c = _daoSp ();
    $c->addCondition ('id_agenda', '=', $agendaId);
    $c->addCondition ('datedeb_event', '<=', $dateFin);
    $c->startGroup ();
    $c->addCondition ('datefin_event', '>=', $dateDebut);
    $c->addCondition ('endrepeatdate_event', '>=', $dateDebut, 'or');
        $c->endGroup ();

    return $this->findBy ($c);
  }
}