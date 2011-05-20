<?php
/**
* @package    Iconito
* @subpackage Cahierdetextes
* @author     Jérémy FOURNAISE
*/


class KernelCahierDeTextes {

  /* 
	 * Crée un cahier de textes
	 * Renvoie son ID ou NULL si erreur
	*/
	function create () {
		$return = NULL;
		$cle = substr( md5(microtime()), 0, 10 );
		$dao = _dao("cahierdetextes|cahierdetextes");
		$new = _record("cahierdetextes|cahierdetextes");
		$dao->insert ($new);
		if ($new->id!==NULL) {

				$return = $new->id;
		}
		return $return;
	}
}

