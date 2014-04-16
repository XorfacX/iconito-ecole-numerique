<?php

use \ActivityStream\Client\Model\Resource;
use \ActivityStream\Client\Model\ResourceInterface;

class DAORecordKernel_bu_ville implements ResourceInterface
{
  /** @var array Groupes de villes, à ne pas confondre avec les groupements de villes */
  protected $_citiesGroup = null;

  /** @var array les groupements de villes (notion en dehors de l'arbre des ressources) */
  protected $groupementsVilles = null;

  /**
   * Retoune les groupes de villes pour la ville courante
   *
   * @return array
   */
  public function getCitiesGroup ()
  {
    if (is_null($this->_citiesGroup)) {

      $citiesGroupsDAO = _ioDAO ('kernel|kernel_bu_groupe_villes');

      $this->_citiesGroup = $citiesGroupsDAO->get ($this->id_grville);
    }

    return $this->_citiesGroup;
  }

  /**
   * Retourne les groupements de villes
   *
   * @return array
   */
  public function getGroupementsVilles()
  {
    if (null === $this->groupementsVilles) {
      $this->groupementsVilles = _ioDAO('regroupements|grvilles')->getGroupementByVille($this);
    }

    return $this->groupementsVilles;
  }

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
      $this->id_vi
    );

    $attributes = array(
      'date_creation',
      'id_grville',
      'canon',
    );

    $attributesValues = array();
    foreach ($attributes as $attribute) {
      $attributesValues[$attribute] = $this->$attribute;
    }

    $resource->setAttributes($attributesValues);

    return $resource;
  }
}

class DAOKernel_bu_ville
{
    /**
     * Retourne une ville par son canon
     *
     * @param string $canon Canon d'une ville
     *
     * @return CopixDAORecordIterator
     */
    public function getByCanon ($canon)
    {
        $criteria = _daoSp ();
        $criteria->addCondition ('canon', '=', $canon);

        return $this->findBy ($criteria);
    }

    /**
     * Retourne les villes faisant partie d'un même groupe de villes
     *
     * @param int $idGrville ID du groupe de villes
     *
     * @return CopixDAORecordIterator
     */
    public function getByIdGrville ($idGrville)
    {
      $criteria = _daoSp ();
        $criteria->addCondition ('id_grville', '=', $idGrville);
        $criteria->orderBy ('nom');

        return $this->findBy ($criteria);
    }

    /**
     * Retourne les villes d'un groupe de ville accessibles pour un utilisateur
     *
     * @param int   $citiesGroupId  Identifiant du groupe de ville
     * @param array $groups         Groupes
   *
      * @return CopixDAORecordIterator
     */
    public function findByCitiesGroupIdAndUserGroups ($citiesGroupId, $groups)
    {
        $groupsIds = array(
      'citiesIds'       => array(),
      'schoolsIds'      => array(),
      'classroomsIds'   => array()
    );

    foreach ($groups as $key => $group) {

      $id = substr($key, strrpos($key, '_')+1);

      if (preg_match('/^city_agent/', $key)) {

        $groupsIds['citiesIds'][] = $id;
      } elseif (preg_match('/^administration_staff/', $key)) {

        $groupsIds['schoolsIds'][] = $id;
      } elseif (preg_match('/^principal/', $key)) {

        $groupsIds['schoolsIds'][] = $id;
      } elseif (preg_match('/^teacher_school/', $key)) {

        $groupsIds['schoolsIds'][] = $id;
      } elseif (preg_match('/^teacher/', $key)) {

        $groupsIds['classroomsIds'][] = $id;
      } elseif (preg_match('/^schools_group_animator/', $key)) {

        $groupsIds['schoolsIds'][] = $id;
      } elseif (preg_match('/^cities_group_animator/', $key)) {

        $groupsIds['schoolsIds'][] = $id;
      }
    }

    if (empty ($groupsIds['citiesIds']) && empty ($groupsIds['schoolsIds']) && empty ($groupsIds['classroomsIds'])) {

      return array();
    }

        $sql = $this->_selectQuery.' '
          . 'LEFT JOIN kernel_bu_ecole ON kernel_bu_ecole.id_ville = kernel_bu_ville.id_vi '
          . 'LEFT JOIN kernel_bu_ecole_classe ON kernel_bu_ecole_classe.ecole = kernel_bu_ecole.numero '
          . 'WHERE kernel_bu_ville.id_grville='.$citiesGroupId;

        $conditions = array();
        if (!empty ($groupsIds['citiesIds'])) {

          $conditions[] = 'kernel_bu_ville.id_vi IN ('.implode(',', $groupsIds['citiesIds']).')';
        }
        if (!empty ($groupsIds['schoolsIds'])) {

          $conditions[] = 'kernel_bu_ecole.numero IN ('.implode(',', $groupsIds['schoolsIds']).')';
        }
        if (!empty ($groupsIds['classroomsIds'])) {

          $conditions[] = 'kernel_bu_ecole_classe.id IN ('.implode(',', $groupsIds['classroomsIds']).')';
        }

        $sql .= ' AND ('.implode(' OR ', $conditions).')';
        $sql .= ' GROUP BY kernel_bu_ville.id_vi';
        $sql .= ' ORDER BY kernel_bu_ville.nom';

    return new CopixDAORecordIterator (_doQuery ($sql), $this->getDAOId ());
    }
}