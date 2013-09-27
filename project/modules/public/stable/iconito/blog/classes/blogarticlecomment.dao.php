<?php

use \ActivityStream\Client\Model\Resource;
use \ActivityStream\Client\Model\ResourceInterface;

/**
 * @package     iconito
 * @author      Jérémy Hubert <jeremy.hubert@infogroom.fr>
 */
class DAORecordBlogarticlecomment implements ResourceInterface
{
  /**
   * Return a resource from the current Object
   *
   * @return Resource
   */
  public function toResource()
  {
    $resource = new EcoleNumeriqueActivityStreamResource(
      null,
      get_class($this),
      $this->id_bacc
    );

    $attributes = array(
      'id_bact',
      'authorid_bacc',
      'authorname_bacc',
      'authoremail_bacc',
      'authorweb_bacc',
      'authorip_bacc',
      'date_bacc',
      'time_bacc',
      'content_bacc',
      'is_online',
      'date_send',
      'id_blog',
    );

    $attributesValues = array();
    foreach ($attributes as $attribute) {
      $attributesValues[$attribute] = $this->$attribute;
    }

    $resource->setAttributes($attributesValues);

    return $resource;
  }
}

/**
* @package	copix
* @version	$Id: blogarticlecomment.dao.class.php,v 1.8 2007-05-15 10:08:39 cbeyer Exp $
* @author	Sylvain DACLIN see copix.aston.fr for other contributors.
* @copyright 2001-2005 Aston S.A.
* @link		http://copix.org
* @licence  http://www.gnu.org/licenses/lgpl.htmlGNU Leser General Public Licence, see LICENCE file
*/
class DAOBlogarticlecomment
{
    /**
    * findAllOrder
    * @param
    * @return
    */
    public function findCommentOrderBy($id_bact, $is_online, $orderby='cmt.date_bacc ASC, cmt.time_bacc ASC')
    {
      $critere = ' SELECT cmt.id_bacc as id_bacc, '.
                                           'cmt.id_bact as id_bact, '.
                                           'cmt.authorname_bacc as authorname_bacc, '.
                                           'cmt.authoremail_bacc as authoremail_bacc, '.
                                           'cmt.authorweb_bacc as authorweb_bacc, '.
                                           'cmt.authorip_bacc as authorip_bacc, '.
                                           'cmt.date_bacc as date_bacc, '.
                                           'cmt.time_bacc as time_bacc, '.
                                           'cmt.content_bacc as content_bacc, '.
                                           'cmt.is_online as is_online '.
                 ' FROM module_blog_articlecomment as cmt '.
                 ' WHERE cmt.id_bact = '.$id_bact;
            if ($is_online != NULL)
              $critere .= ' AND cmt.is_online='.$is_online;

            if($orderby!=NULL) {
              $critere .= ' ORDER BY '.$orderby;
          }
      return _doQuery($critere);
    }


   /**
    * @param
    * countNbCommentForArticle
    * @return
    */
   public function countNbCommentForArticle($id_bact, $is_online=1)
   {
      $sql = 'SELECT count(id_bacc) as nbComment FROM module_blog_articlecomment WHERE id_bact='.$id_bact.' AND is_online='.$is_online.' group by id_bact ';
      $result = _doQuery($sql);
      if ($result && $result[0]->nbComment > 0) {
         return $result[0]->nbComment;
      }else{
         return 0;
      }
   }


}
