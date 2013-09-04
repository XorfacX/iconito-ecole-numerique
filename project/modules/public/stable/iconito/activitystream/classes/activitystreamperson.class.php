<?php

use ActivityStream\Client\Model\Resource;
use ActivityStream\Client\Model\ResourceInterface;

/**
 *
 */
class ActivityStreamPerson implements ResourceInterface
{
  /**
   * @var string
   */
  protected $id;

  /**
   * @var string
   */
  protected $firstname;

  /**
   * @var string
   */
  protected $lastname;

  /**
   * @var string
   */
  protected $type;

  /**
   * Constructeur
   *
   * @param string $type      Le type
   * @param string $id        L'identifiant
   * @param string $firstname Le prénom
   * @param string $lastname  Le nom de famille
   */
  public function __construct($type, $id, $firstname, $lastname)
  {
    $this->setId($id);
    $this->setType($type);
    $this->setFirstname($firstname);
    $this->setLastname($lastname);
  }

  /**
   * @param string $lastname
   */
  public function setLastname($lastname)
  {
    $this->lastname = $lastname;
  }

  /**
   * @return string
   */
  public function getLastname()
  {
    return $this->lastname;
  }

  /**
   * @param string $firstname
   */
  public function setFirstname($firstname)
  {
    $this->firstname = $firstname;
  }

  /**
   * @return string
   */
  public function getFirstname()
  {
    return $this->firstname;
  }

  /**
   * @param $id
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
   * Calcul l'identité de la personne
   *
   * @return string
   */
  public function getIdentity()
  {
    return implode(' ', array_filter(array($this->getFirstname(), $this->getLastname())));
  }

  /**
   * @param $type
   */
  public function setType($type)
  {
    $this->type = $type;
  }

  /**
   * @return mixed
   */
  public function getType()
  {
    return $this->type;
  }

  /**
   * Return a resource from the current Object
   *
   * @return Resource
   */
  public function toResource()
  {
    $resource = new Resource(
      $this->getIdentity(),
      get_class($this)
    );

    $resource->setAttributes(array(
      'type' => $this->getType()
    ));

    $resource->setId($this->getId());

    return $resource;
  }
}
