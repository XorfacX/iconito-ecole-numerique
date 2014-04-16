<?php

use \ActivityStream\Client\Model\ResourceInterface;

class DAORecordGrVilles implements ResourceInterface
{
  /**
   * Return a resource from the current Object
   *
   * @return Resource
   */
  public function toResource()
  {
    $resource = new EcoleNumeriqueActivityStreamResource(
        $this->nom,
        get_class($this),
        $this->id
    );

    return $resource;
  }
}

class DAOGrVilles
{
  /**
   * Retourne les groupements de villes pour une ville
   *
   * @param $ville
   *
   * @return mixed
   */
  public function getGroupementByVille($ville)
  {
    $query = array();

    $query[] = 'SELECT *';
    $query[] = 'FROM module_regroupements_grvilles AS mrgv';
    $query[] = 'INNER JOIN module_regroupements_grvilles2villes AS mrgv2v ON mrgv.id = mrgv2v.id_groupe';
    $query[] = 'WHERE mrgv2v.id_ville = :id_ville';

    return new CopixDAORecordIterator(_doQuery(implode(' ', $query), array(':id_ville' => $ville->id_vi)), $this->getDAOId());
  }
}