<?php

use \ActivityStream\Client\Model\Resource;
use \ActivityStream\Client\Model\ResourceInterface;

/**
 * @package     iconito
 * @subpackage  agenda
 * @author      Jérémy Hubert <jeremy.hubert@infogroom.fr>
 */
class DAORecordAgenda implements ResourceInterface
{
  /**
   * Return a resource from the current Object
   *
   * @return Resource
   */
  public function toResource()
  {
    $resource = new EcoleNumeriqueActivityStreamResource(
      $this->title_agenda,
      get_class($this),
      $this->id_agenda
    );

    $resource->setAttributes(array(
      'type' => $this->type_agenda,
      'desc_agenda' => $this->desc_agenda,
    ));

    return $resource;
  }
}

/**
* @filesource
* @package : copix
* @subpackage : agenda
* @author : Audrey Vassal
* surcharge  pour les dao
*/
class DAOAgenda
{
/**
    * R�cup�ration d'une liste d'agendas parmi une liste d'ids
    * @author Christophe Beyer <cbeyer@cap-tic.fr>
    * @since 2006/08/24
    */
    public function findAgendasInIds ($ids)
    {
        $critere = 'SELECT AG.* FROM module_agenda_agenda AG WHERE AG.id_agenda IN ('.implode(', ',$ids).')';
        return _doQuery($critere);
    }

    /**
     * Renvoie des stats sur les �v�nements d'un agenda : nb d'�v�nements (nbEvenements)
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2006/10/06
     * @param integer $agenda Id de l'agenda
     * @return mixed Objet DAO.
     */
    public function getNbsEvenementsInAgenda ($agenda)
    {
        $critere = 'SELECT COUNT(id_event) AS nbEvenements FROM module_agenda_event EV WHERE EV.id_agenda='.$agenda.'';
        return _doQuery($critere);
    }

}
