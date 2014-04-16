<?php

_classInclude('kernel|Regroupement');

class RegroupementVilles extends Regroupement
{
  /**
   * @return array
   */
  public function getVilles()
  {
    return $this->getElements();
  }

  /**
   * @param array $villes
   */
  public function setVilles(array $villes)
  {
    $this->setElements($villes);
  }

  /**
   * Ajoute une ville
   *
   * @param $ville
   */
  public function addVille($ville)
  {
    $this->addElement($ville);
  }
}