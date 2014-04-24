<?php

use \ActivityStream\Client\Model\ResourceInterface;

class DAORecordGrEcoles implements ResourceInterface
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

class DAOGrEcoles
{
  /**
   * Retourne les groupements d'écoles pour une école
   *
   * @param $ecole
   *
   * @return mixed
   */
  public function getGroupementByEcole($ecole)
  {
    $query = array();

    $query[] = 'SELECT *';
    $query[] = 'FROM module_regroupements_grecoles AS mrge';
    $query[] = 'INNER JOIN module_regroupements_grecoles2ecoles AS mrge2e ON mrge.id = mrge2e.id_groupe';
    $query[] = 'WHERE mrge2e.id_ecole = :id_ecole';

    return new CopixDAORecordIterator(_doQuery(implode(' ', $query), array(':id_ecole' => $ecole->numero)), $this->getDAOId());
  }
}