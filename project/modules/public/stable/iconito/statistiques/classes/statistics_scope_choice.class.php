<?php

class StatisticsScopeChoice
{
  /**
   * La clé pour le rendu du widget de choix
   * @var string
   */
  protected $key;

  /**
   * Le label du widget de choix
   * @var string
   */
  protected $label;

  /**
   * La resource liée au choix
   * @var
   */
  protected $resource;

  /** @var StatisticsScopeChoiceGroup */
  protected $group;

  /**
   * Construction
   *
   * @param string $key
   * @param string $label
   * @param mixed $resource
   */
  public function __construct($key, $label, $resource = null)
  {
    $this->key      = $key;
    $this->label    = $label;
    $this->resource = $resource;
  }

  /**
   * @param mixed $key
   */
  public function setKey($key)
  {
    $this->key = $key;
  }

  /**
   * @return mixed
   */
  public function getKey()
  {
    return $this->key;
  }

  /**
   * Retourne la clé calculé en fonction du niveau de profondeur
   */
  public function computeKey()
  {
    return implode('|', array_filter(array(
      null !== $this->getGroup() ? $this->getGroup()->computeKey() : null,
      $this->getKey()
    ), function($value){
      return null !== $value && '' !== $value;
    }));
  }

  /**
   * @param mixed $label
   */
  public function setLabel($label)
  {
    $this->label = $label;
  }

  /**
   * @return mixed
   */
  public function getLabel()
  {
    return $this->label;
  }

  /**
   * Défini le groupe auquel appartient le choix donné
   *
   * @param StatisticsScopeChoiceGroup $group
   */
  public function setGroup(StatisticsScopeChoiceGroup $group = null)
  {
    $this->group = $group;
  }

  /**
   * @return \StatisticsScopeChoiceGroup
   */
  public function getGroup()
  {
    return $this->group;
  }

  /**
   * @param mixed $resource
   */
  public function setResource($resource)
  {
    $this->resource = $resource;
  }

  /**
   * @return mixed
   */
  public function getResource()
  {
    return $this->resource;
  }

  /**
   * Retourne le choix sélectionné selon la valeur
   *
   * @param $value
   *
   * @return StatisticsScopeChoice
   */
  public function getSelectedChoice($value)
  {
    if ($this->isSelected($value)) {
      return $this;
    }

    return null;
  }

  /**
   * Le choix courant est-il celui coché selon la valeur sélectionnée ?
   *
   * @param $value
   *
   * @return bool
   */
  public function isSelected($value)
  {
    return $value === $this->computeKey();
  }
}