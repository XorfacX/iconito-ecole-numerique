<?php

use \ActivityStream\Client\Model\Resource;
use \ActivityStream\Client\Model\ResourceInterface;

/**
 * @package     iconito
 * @subpackage  minimail
 * @author      Jérémy Hubert <jeremy.hubert@infogroom.fr>
 */
class DAORecordMinimail_to implements ResourceInterface
{
  /**
   * Return a resource from the current Object
   *
   * @return Resource
   */
  public function toResource()
  {
    $resource = new Resource(
      $this->title,
      get_class($this),
      $this->id2
    );

    $attributes = array(
      'id_message',
      'to_id',
      'date_read',
      'is_read',
      'is_replied',
      'is_deleted',
      'is_forwarded',
      'id',
      'from_id',
      'message',
      'date_send',
      'attachment1',
      'attachment2',
      'attachment3',
    );

    $attributesValues = array();
    foreach ($attributes as $attribute) {
      $attributesValues[$attribute] = $this->$attribute;
    }
    
    $resource->setAttributes($attributesValues);

    return $resource;
  }
}

class DAOMinimail_to
{
    /**
     * Renvoie le nb de minimails recus et non lus
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2010/06/24
     * @param $iUser Id de l'utilisateur destinataire
     */
  public function getNbRecvUnread ($iUser)
  {
    $criteres = _daoSp ()
      ->addCondition  ('to_id', '=', $iUser)
      ->addCondition  ('is_deleted', '=', '0')
      ->addCondition  ('is_read', '=', '0')
      ;
    $oNb = _dao ('minimail|minimail_to')->countBy ($criteres);
    return $oNb;
  }

}

