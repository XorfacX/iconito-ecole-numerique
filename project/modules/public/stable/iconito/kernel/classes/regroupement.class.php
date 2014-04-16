<?php

use ActivityStream\Client\Model\ResourceInterface;

abstract class Regroupement implements ResourceInterface
{
  /**
   * L'identifiant du regroupement
   *
   * @var mixed
   */
  protected $id;

  /**
   * Le nom du groupement
   *
   * @var string
   */
  protected $name;

  /**
   * @var ResourceInterface L'entité attachée
   */
  protected $entity;

  /**
   * Les éléments faisant partie du groupement
   *
   * @var array
   */
  protected $elements = array();

  /**
   * Constructeur
   *
   * @param mixed  $id
   * @param string $name
   * @param array  $elements
   */
  public function __construct($id, $name, ResourceInterface $entity, array $elements = array())
  {
    $this->id       = $id;
    $this->name     = $name;
    $this->entity   = $entity;
    $this->elements = $elements;
  }

  /**
   * @param array $elements
   */
  protected function setElements(array $elements)
  {
    $this->elements = $elements;
  }

  /**
   * @return array
   */
  protected function getElements()
  {
    return $this->elements;
  }

  /**
   * @param mixed $element
   */
  protected function addElement($element)
  {
    $this->elements[] = $element;
  }

  /**
   * @param string $name
   */
  public function setName($name)
  {
    $this->name = $name;
  }

  /**
   * @return string
   */
  public function getName()
  {
    return $this->name;
  }

  /**
   * @param mixed $id
   */
  public function setId($id)
  {
    $this->id = $id;
  }

  /**
   * @return mixed
   */
  public function getId()
  {
    return $this->id;
  }

  /**
   * @param mixed $entity
   */
  public function setEntity($entity)
  {
    $this->entity = $entity;
  }

  /**
   * @return mixed
   */
  public function getEntity()
  {
    return $this->entity;
  }

  /**
   * Return an resource from the current Object
   *
   * @return Resource
   */
  public function toResource()
  {
    return $this->getEntity()->toResource();
  }
}