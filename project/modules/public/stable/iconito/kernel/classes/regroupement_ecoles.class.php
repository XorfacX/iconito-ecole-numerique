<?php

_classInclude('kernel|Regroupement');

class RegroupementEcoles extends Regroupement
{
  public function getEcoles()
  {
    return $this->getElements();
  }

  public function setEcoles(array $ecoles)
  {
    $this->setElements($ecoles);
  }

  public function addEcole($ecole)
  {
    $this->addElement($ecole);
  }
}