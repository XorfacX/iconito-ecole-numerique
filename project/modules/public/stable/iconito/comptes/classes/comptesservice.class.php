<?php
/**
 * Fonctions diverses du module Comptes
 * 
 * @package Iconito
 * @subpackage	Comptes
 */

class ComptesService {
	var $user_service;

	function ComptesService() {
		$this->user_service = & CopixClassesFactory::Create ('auth|ProjectUser');
	}
	
	/**
	 * createLogin
	 *
	 * Propose un login en fonction des information de l'utilisateur (nom, prénom, rôle, etc.)
	 *
	 * @author Frédéric Mossmann <fmossmann@cap-tic.fr>
	 * @param array $user_infos Tableau des informations de l'utilisateur.
	 * @return string Login composé des information disponibles.
	 */
	function createLogin( $user_infos ) {
		
		// Caractères pouvant être dans un nom/prenom.
		$interdits = array(" ", "'", "-");
		
		$nom = $user_infos['nom'];
		$prenom = $user_infos['prenom'];
		
		// Recherche des initiales : la première lettre de chaque entité dans un nom/prenom.
		$separateur_init = implode( '', $interdits );
		$tok = strtok($nom, $separateur_init);
		while ($tok !== false) {
			$nom_init .= $tok{0};
			$tok = strtok($separateur_init);
		}
		$tok = strtok($prenom, $separateur_init);
		while ($tok !== false) {
			$prenom_init .= $tok{0};
			$tok = strtok($separateur_init);
		}
		
		// Retrait des caractères spéciaux des noms/prénoms.
		$nom       = str_replace($interdits, "", $nom);
		$prenom    = str_replace($interdits, "", $prenom);
		
		// Simplification (accents, majuscules, etc.)
		$nom         = Kernel::simpleName($nom);
		$nom_init    = Kernel::simpleName($nom_init);
		$prenom      = Kernel::simpleName($prenom);
		$prenom_init = Kernel::simpleName($prenom_init);
		
		$login_parts = array();
		switch( $user_infos['type'] ) {
			case 'USER_ELE': // Elèves : Prénom et initiale du nom
				if( trim($prenom)   != '' ) $login_parts[] = $prenom;
				// if( trim($nom_init) != '' ) $login_parts[] = $nom_init;
				$login = implode( '', $login_parts );
				break;
			case 'USER_VIL': // Officiels : prénom et nom séparés par un point
				if( trim($prenom) != '' ) $login_parts[] = $prenom;
				if( trim($nom)    != '' ) $login_parts[] = $nom;
				$login = implode( '.', $login_parts );
				break;
			default; // Par défaut : initiale du prénom et nom
				if( trim($prenom_init) != '' ) $login_parts[] = $prenom_init;
				if( trim($nom)         != '' ) $login_parts[] = $nom;
				$login = implode( '', $login_parts );
				break;
		}
		
		$ext=''; $fusible=1000; // Fusible pour éviter les boucles sans fin.
		while( $this->user_service->get($login.$ext) && $fusible-- ) {
			if( $ext=='' ) $ext=1;
			else $ext++;
		}
		
		return $login.$ext;
	}

	/**
	 * createPasswd
	 *
	 * Propose un mot de passe aléatoire.
	 *
	 * @author Frédéric Mossmann <fmossmann@cap-tic.fr>
	 * @return string Mot de passe aléatoire.
	 */
	function createPasswd() {
		$lettres  = 'abcdefghijklmnopqrstuvwxyz';
		$chiffres = '0123456789';
		$passwd = '';
		
		$passwd .= $lettres{mt_rand(0, strlen($lettres)-1)};
		$passwd .= $lettres{mt_rand(0, strlen($lettres)-1)};
		$passwd .= $chiffres{mt_rand(0, strlen($chiffres)-1)};
		$passwd .= $chiffres{mt_rand(0, strlen($chiffres)-1)};
		$passwd .= $lettres{mt_rand(0, strlen($lettres)-1)};
		$passwd .= $lettres{mt_rand(0, strlen($lettres)-1)};
		$passwd .= $chiffres{mt_rand(0, strlen($chiffres)-1)};
		$passwd .= $chiffres{mt_rand(0, strlen($chiffres)-1)};
		
		return( $passwd );
	}
	
	/*
	function canCreateCompte( $node_type, $node_id ) {
		return( Kernel::getLevel( $node_type, $node_id ) );
	}
	*/








	// Tri les enseignants 
	function order_tab_enseignants ($tab) {
		usort ($tab, array("AnnuaireService", "compare_nom"));
		return $tab;
	}

	// Tri les elèves
	function order_tab_eleves ($tab) {
		usort ($tab, array("AnnuaireService", "compare_info_nom"));
		return $tab;
	}

	function compare_nom($a, $b)
	{
		//print_r($a);
  	return strcmp($a["nom"], $b["nom"]);
	}
	
	function compare_info_nom ($a, $b)
	{
		//print_r($a);
  	return strcmp($a["info"]["nom"], $b["info"]["nom"]);
	}
	
	function checkLoginAccess( $login ) {
			if( trim($login)=='' ) {
			die( 'Pas de login dans l\'URL' );
		}
		
		$userinfo = Kernel::getUserInfo( 'LOGIN', $login );
		if( 0 == sizeof( $userinfo ) ) {
			die( 'Login inconnu' );
		}
		
		$level = 0;
		
		/*
		$userparents = Kernel::getNodeParents( $userinfo['type'], $userinfo['id'] );
		foreach( $userparents AS $parent_key=>$parent_val ) {
			$level = max( $level, Kernel::getLevel( $parent_val['type'], $parent_val['id'] ) );
			
			while( ereg( '^BU_', $parent_val['type']) && $node = Kernel::getNodeParents( $parent_val['type'], $parent_val['id'] ) ) {
				$parent_val['type'] = $node['0']['type'];
				$parent_val['id'] = $node['0']['id'];
				$level = max( $level, Kernel::getLevel( $parent_val['type'], $parent_val['id'] ) );
			}
		}
		*/
		// $level = ComptesService::getNodeLevel_r( $userinfo['type'], $userinfo['id'] );
		$level = Kernel::getLevel_r( $userinfo['type'], $userinfo['id'] );
		
		$level = max( $level, Kernel::getLevel_r( 'ROOT','0' ) );
		
		if( $level<70 ) { // A vérifier...
			die( 'Pas le droit !' );
		}
		
		return( $userinfo );
	}

	
}


?>
