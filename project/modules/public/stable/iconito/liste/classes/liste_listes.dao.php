<?php

use ActivityStream\Client\Model\ResourceInterface;
_classInclude('activitystream|ecolenumeriqueactivitystreamresource');

class DAORecordListe_Listes implements ResourceInterface
{
  /**
   * Return a resource from the current Object
   *
   * @return Resource
   */
  public function toResource()
  {
    $resource = new EcoleNumeriqueActivityStreamResource(
      $this->titre,
      get_class($this),
      $this->id
    );

    $attributes = array(
      'date_creation'
    );

    $attributesValues = array();
    foreach ($attributes as $attribute) {
      $attributesValues[$attribute] = $this->$attribute;
    }

    $resource->setAttributes($attributesValues);

    return $resource;
  }
}

/**
 * Surcharge de la DAO liste_listes
 *
 * @package Iconito
 * @subpackage Liste
 */
class DAOListe_Listes
{
    /**
     * Renvoie nb de messages envoy�s sur une liste
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2005/11/23
     * @param integer $id_liste Id de la liste
     * @return mixed Objet DAO
     */
    public function getNbMessagesInListe ($id_liste)
    {
        $critere = 'SELECT COUNT(MSG.id) AS nb FROM module_liste_messages MSG WHERE MSG.liste='.$id_liste.'';
        return _doQuery($critere);
    }


}




