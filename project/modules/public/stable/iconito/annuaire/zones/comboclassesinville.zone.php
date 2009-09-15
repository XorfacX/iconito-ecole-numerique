<?php

/**
 * Zone qui affiche la liste d�roulante avec toutes les classes d'une ville
 * 
 * @package Iconito
 * @subpackage	Annuaire
 */
class ZoneComboClassesInVille extends CopixZone {

	/**
	 * Affiche la liste d�roulante avec toutes les classes d'une ville
	 *
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2006/01/06
	 * @param integer $ville Id de la ville
	 * @param integer $value Valeur actuelle de la combo
	 * @param string $fieldName Nom du champ de type SELECT qui en r�sulte
	 * @param string $attribs Attributs HTML de la liste (STYLE, ONCHANGE...)
	 * @param array $linesSup Lignes suppl�mentaires � ajouter en haut de la liste au-dessus des dossiers (ex: "Choisissez l'�cole"). Chaque ligne est un tableau, de type array ("value"=>"", "libelle"=>"Choisissez")
	 */
	function _createContent (&$toReturn) {
		
		$annuaireService = & CopixClassesFactory::Create ('annuaire|AnnuaireService');
		
		$ville = isset($this->params['ville']) ? $this->params['ville'] : NULL;
		$value = isset($this->params['value']) ? $this->params['value'] : 0;
		$fieldName = isset($this->params['fieldName']) ? $this->params['fieldName'] : NULL;
		$attribs = isset($this->params['attribs']) ? $this->params['attribs'] : NULL;
		$linesSup = isset($this->params["linesSup"]) ? $this->params["linesSup"] : NULL;
		
		$classes = $annuaireService->getClassesInVille ($ville, array('getNodeInfo_light'=>1));
// echo "<pre>"; print_r($classes); die();
		$tpl = & new CopixTpl ();
		$tpl->assign('items', $classes);
		$tpl->assign('value', $value);
		$tpl->assign('fieldName', $fieldName);
		$tpl->assign('attribs', $attribs);
		$tpl->assign('linesSup', $linesSup);
		
    $toReturn = $tpl->fetch ('comboclasses.tpl');
    return true;
	}

}


?>
