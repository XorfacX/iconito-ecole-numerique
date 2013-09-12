<?php

use \ActivityStream\Client\Model\Resource;
use \ActivityStream\Client\Model\ResourceInterface;

/**
 * @package     iconito
 * @subpackage  quiz
 * @author      Jérémy Hubert <jeremy.hubert@infogroom.fr>
 */
class DAORecordQuiz_quiz implements ResourceInterface
{
  /**
   * Return a resource from the current Object
   *
   * @return Resource
   */
  public function toResource()
  {
    $resource = new Resource(
      $this->name,
      get_class($this),
      $this->id
    );

    $attributes = array(
      'id_owner',
      'date_start',
      'date_end',
      'description',
      'help',
      'pic',
      'opt_save',
      'opt_show_results',
      'lock',
      'gr_id',
    );

    $attributesValues = array();
    foreach ($attributes as $attribute) {
      $attributesValues[$attribute] = $this->$attribute;
    }

    $resource->setAttributes($attributesValues);

    return $resource;
  }
}