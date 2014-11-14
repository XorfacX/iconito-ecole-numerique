<?php

use ActivityStream\Client\Model\Resource;


class EcoleNumeriqueActivityStreamResource extends Resource
{
  public function __construct($displayName, $objectType, $id = null, $url = null, array $attributes = array())
  {
    $objectType = preg_replace('/^Compiled/', '', $objectType);
    parent::__construct($displayName, $objectType, $id, $url, $attributes);
  }
}