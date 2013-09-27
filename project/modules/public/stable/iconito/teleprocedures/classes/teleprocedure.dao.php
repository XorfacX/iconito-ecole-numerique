<?php
/**
* @package	copix
* @version	$Id: intervention.dao.class.php,v 1.3 2009-04-01 14:48:57 cbeyer Exp $
* @author	Christophe Beyer <cbeyer@cap-tic.fr>
* @copyright 2009 CAP-TIC
* @link		http://www.iconito.org
* @licence  http://www.gnu.org/licenses/lgpl.htmlGNU Leser General Public Licence, see LICENCE file
*/

_classInclude('activitystream|ecolenumeriqueactivitystreamresource');
use ActivityStream\Client\Model\ResourceInterface;

class DAORecordTeleprocedure implements ResourceInterface
{
    /**
     * Return an resource from the current Object
     *
     * @return Resource
     */
    public function toResource()
    {
        $resource = new EcoleNumeriqueActivityStreamResource(
            'Téléprocédure',
            get_class($this),
            $this->id
        );

        $attributes = array(
            'titre',
            'date_creation'
        );

        $attributesValues = array();
        foreach ($attributes as $attribute) {
            $attributesValues[$attribute] = $this->$attribute;
        }

        $resource->setAttributes($attributesValues);

        return $resource;
    }
}