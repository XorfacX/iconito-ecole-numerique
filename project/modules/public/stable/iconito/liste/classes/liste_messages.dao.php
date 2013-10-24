<?php

use ActivityStream\Client\Model\ResourceInterface;
_classInclude('activitystream|ecolenumeriqueactivitystreamresource');

class DAORecordliste_messages implements ResourceInterface
{
  /**
   * Return a resource from the current Object
   *
   * @return Resource
   */
  public function toResource()
  {
    $resource = new EcoleNumeriqueActivityStreamResource(
      $this->titre,
      get_class($this),
      $this->id
    );

    $attributes = array(
      'liste',
      'message',
      'date',
      'auteur'
    );

    $attributesValues = array();
    foreach ($attributes as $attribute) {
      $attributesValues[$attribute] = $this->$attribute;
    }

    $resource->setAttributes($attributesValues);

    return $resource;
  }
}