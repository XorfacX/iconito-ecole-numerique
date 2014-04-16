<?php

_classInclude('statistiques|statistics_scope_choice');

class StatisticsScopeChoiceGroup extends StatisticsScopeChoice implements IteratorAggregate
{
  /**
   * Les choix de périmètres
   *
   * @var array
   */
  protected $choices = array();

  /**
   * Constructeur
   *
   * @param string $key
   * @param string $label
   * @param array  $choices
   */
  public function __construct($key, $label, array $choices = array())
  {
    parent::__construct($key, $label);

    $this->setChoices($choices);
  }

  /**
   * @param array $choices
   */
  public function setChoices(array $choices)
  {
    $this->choices = array();
    foreach ($choices as $choice)
    {
      $this->addChoice($choice);
    }
  }

  /**
   * @return array
   */
  public function getChoices()
  {
    return $this->choices;
  }

  /**
   * Ajoute un choix de périmètre
   *
   * @param StatisticsScopeChoice $choice
   */
  public function addChoice(StatisticsScopeChoice $choice)
  {
    $this->choices[] = $choice;
    $choice->setGroup($this);
  }

  /**
   * (PHP 5 &gt;= 5.0.0)<br/>
   * Retrieve an external iterator
   *
   * @link http://php.net/manual/en/iteratoraggregate.getiterator.php
   * @return Traversable An instance of an object implementing <b>Iterator</b> or
   *       <b>Traversable</b>
   */
  public function getIterator()
  {
    return new ArrayIterator($this->choices);
  }

  /**
   * Surcharge de la méthode de récupération du choix coché pour un groupe afin d'appeler les sous-choix en récursif
   *
   * @param $value
   *
   * @return StatisticsScopeChoice
   */
  public function getSelectedChoice($value)
  {
    if (null === parent::getSelectedChoice($value)) {
      foreach ($this->getChoices() as $choice) {
        $choiceSelected = $choice->getSelectedChoice($value);

        if ($choiceSelected instanceof StatisticsScopeChoice) {
          return $choiceSelected;
        }
      }
    }

    return null;
  }
}