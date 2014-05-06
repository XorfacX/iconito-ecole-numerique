<?php

use \ActivityStream\Client\Model\Resource;
use \ActivityStream\Client\Model\ResourceInterface;

/**
* @package    Iconito
* @subpackage Classeur
*/

class DAORecordClasseur implements ResourceInterface
{
    public function __toString ()
    {
        return $this->titre;
    }

    /**
   * Indique si le classeur a des dossiers
   *
   * @param bool  $withLockers Prendre en compte les dossiers du type "casier"
   *
   * @return bool
   */
    public function hasDossiers ($withLockers = true)
    {
        $dossierDAO = _ioDAO('classeur|classeurdossier');

        return count($dossierDAO->getEnfantsDirects($this->id, null, $withLockers)->fetchAll()) > 0 ? true : false;
    }

    /**
   * Return a resource from the current Object
   *
   * @return Resource
   */
    public function toResource()
    {
        $resource = new EcoleNumeriqueActivityStreamResource($this->titre, get_class($this), $this->id);

        $attributes = array(
            'cle',
            'date_creation',
            'date_publication',
            'public',
            'upload_fs',
            'upload_db',
            'upload_pw',
        );

        $attributesValues = array();
        foreach ($attributes as $attribute) {
            $attributesValues[$attribute] = $this->$attribute;
        }

        $resource->setAttributes($attributesValues);

        return $resource;
    }
    
    public function realName() 
    {
        $node = Kernel::getModParentInfo("MOD_CLASSEUR", $this->id);
        
        switch ($node['type']){
            
            case "CLUB":
                return $node['nom'];
                break;
            
            case "BU_GRVILLE":
                return $node['nom_groupe'];
                break;
            
            case "BU_VILLE":
                return $node['nom'];
                break;
            
            case "BU_ECOLE":
                return $node['nom'];
                break;
            
            case "BU_CLASSE":
                return $node['nom'];
                break;
            
            
            default:
                return $this->titre;
                break;
        }
    }
}

class DAOClasseur
{
}