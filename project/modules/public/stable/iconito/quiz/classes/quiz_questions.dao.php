<?php

use \ActivityStream\Client\Model\Resource;
use \ActivityStream\Client\Model\ResourceInterface;

/**
 * @package     iconito
 * @subpackage  quiz
 * @author      Jérémy Hubert <jeremy.hubert@infogroom.fr>
 */
class DAORecordQuiz_questions implements ResourceInterface
{
  /**
   * Return a resource from the current Object
   *
   * @return Resource
   */
  public function toResource()
  {
    $resource = new EcoleNumeriqueActivityStreamResource(
      $this->name,
      get_class($this),
      $this->id
    );

    $attributes = array(
      'id_quiz',
      'content',
      'order',
      'opt_type',
    );

    $attributesValues = array();
    foreach ($attributes as $attribute) {
      $attributesValues[$attribute] = $this->$attribute;
    }

    $resource->setAttributes($attributesValues);

    return $resource;
  }
}