<?php


class KernelRessource {


	/*
		Renvoie diff�rentes infos chiffr�es d'un annuaire de ressources, dans un tableau
	*/
	function getStats ($id_ressource) {
		$dao = CopixDAOFactory::create("ressource|ressource_annuaires");
		$res = array();	
		$infos = $dao->getNbRessourcesInAnnuaire($id_ressource);
		$res["Ressources"] = $infos[0]->nb;
		return $res;
	}

}

?>