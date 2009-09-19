<?php

/**
 * Fonctions diverses du module Groupe
 * 
 * @package Iconito
 * @subpackage	Groupe
 */
class GroupeService {

	/**
	 * Crée un groupe
	 *
	 * Crée le groupe, donne les droits de propriétaire à son créateur, et initialise les premiers modules.
	 *
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2005/11/08
	 * @param string $titre Titre
	 * @param string $description Description
	 * @param integer $is_open 1 si le groupe est public, 0 s'il est privé
	 * @param integer $createur Id utilisateur du créateur
	 * @param array $tab_membres Tableau avec les membres à inscrire de suite (clé = id user)
	 * @param array $his_modules Tableau avec les modules à installer et à relier à ce club
	 * @param string $parentClass Type du parent (BU_ECOLE...) 
	 * @param integer $parentRef Référence du parent
	 * @return integer l'Id du groupe créé ou NULL si erreur
	 */
	function createGroupe ($titre, $description, $is_open, $createur, $tab_membres, $his_modules, $parentClass, $parentRef) {
	
		$res = NULL;

		if (1) {
			
			$daoGroupe = _dao("groupe");
			
			$newGroupe = _record("groupe");
			$newGroupe->titre = $titre;
			$newGroupe->description = $description;
			$newGroupe->is_open = $is_open;
			$newGroupe->date_creation = date("Y-m-d H:i:s");
			$newGroupe->createur = $createur;
			$daoGroupe->insert ($newGroupe);
			
			if ($newGroupe->id!==NULL) {
				
				//print_r($his_modules);
				$kernelClasse = CopixClassesFactory::create("kernel|Kernel");
		
				// On ajoute les modules
				while (list($moduleType,) = each ($his_modules)) {
					
					list (,$module) = explode ("_", strtolower($moduleType));
					
					//print_r($module);
					
					$classeNew = CopixClassesFactory::create("$module|Kernel$module");
					$new = $classeNew->create(array('title'=>$titre, 'node_type'=>'CLUB', 'node_id'=>$newGroupe->id));
					//print_r("new=$new");
					if ($new) {	// Module bien crée, on le rattache
						$register = $kernelClasse->registerModule( $moduleType, $new, "CLUB", $newGroupe->id );
					}
				}

				// On insère le créateur
				$userInfo = $kernelClasse->getUserInfo("ID", $createur);
				$kernelClasse->setLevel("CLUB", $newGroupe->id, $userInfo["type"], $userInfo["id"], PROFILE_CCV_ADMIN);
				
				// On insère les éventuels membres
				while (list($userId,) = each ($tab_membres)) {
					$userInfo = $kernelClasse->getUserInfo("ID", $userId);
					$kernelClasse->setLevel("CLUB", $newGroupe->id, $userInfo["type"], $userInfo["id"], PROFILE_CCV_MEMBER);
				}
				
				// On rattache le groupe à son parent
				$kernelClasse->setClubParent( $newGroupe->id, $parentClass, $parentRef);

				$res = $newGroupe->id;
				//die();
			}
			
		}
		return $res;
	}


	/**
	 * Gestion des droits dans un groupe
	 *
	 * Teste si l'usager peut effectuer une certaine opération par rapport à son droit. Le droit sur le groupe nécessite d'être connu, renvoyé par le kernel avant l'entrée dans cette fonction.
	 *
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2006/01/02
	 * @param string $action Action pour laquelle on veut tester le droit
	 * @param integer $droit Le droit de l'usager
	 * @return bool true s'il a le droit d'effectuer l'action, false sinon
	 * @todo Limiter ADD_GROUP aux adultes
	 */
	function canMakeInGroupe ($action, $droit) {
		$can = false;
		switch ($action) {
			case "VIEW_HOME" :	// Accueil d'un groupe
				$can = ($droit >= PROFILE_CCV_READ);
				break;

			case "ADMIN" :	// Accueil de l'admin d'un groupe
				$can = ($droit >= PROFILE_CCV_ADMIN);	
				break;
					
			case "UNSUBSCRIBE_HIMSELF" :	// Se désinscrire (soi-même)
				$can = ($droit >= PROFILE_CCV_READ && $droit < PROFILE_CCV_ADMIN);	
				break;
					
			case "ADD_GROUP" :	// Création d'un groupe, ne dépend pas du droit sur un groupe mais du profil de l'usager
				$can = (Kernel::isEnseignant() || Kernel::isAgentVille() || Kernel::isPersonnelAdministratif() || Kernel::isAdmin() );
				break;

		}
		return $can;
	}

	/**
	 * Retourne le nom (en clair) d'un droit dans un groupe
	 *
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2006/01/23
	 * @param integer $droit Le droit de l'usager
	 * @return string Nom correspondant à ce droit, en clair
	 */
	function getRightName ($droit) {
		switch ($droit) {
			case PROFILE_CCV_ADMIN :	// Propriétaire
				$res = CopixI18N::get ('groupe|groupe.right.owner');
				break;
			case PROFILE_CCV_MODERATE :	// Modérateur
				$res = CopixI18N::get ('groupe|groupe.right.moderate');
				break;
			case PROFILE_CCV_VALID :	// Contributeur
				$res = CopixI18N::get ('groupe|groupe.right.valid');
				break;
			case PROFILE_CCV_MEMBER :	// Membre
				$res = CopixI18N::get ('groupe|groupe.right.member');
				break;
			case PROFILE_CCV_READ :	// Lecteur
				$res = CopixI18N::get ('groupe|groupe.right.read');
				break;
			case PROFILE_CCV_SHOW :	// Membre en attente
				$res = CopixI18N::get ('groupe|groupe.right.waiting');
				break;
			default :
				$res = 'N/C';
		}
		return $res;
	}


	/**
	 * Renvoie le nombre d'inscrits à un groupe
	 *
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2006/01/25
	 * @param integer $groupe	Id du groupe
	 * @return array Tableau avec le nombre de membres inscrits ['inscrits'] et en attente ['waiting']
	 */
	function getNbMembersInGroupe ($groupe) {
		$kernelService = & CopixClassesFactory::Create ('kernel|kernel');
		$childs = $kernelService->getNodeChilds( "CLUB", $groupe );
		$res = array('inscrits'=>0, 'waiting'=>0);
		foreach ($childs as $child) {
			//print_r($child);
      $ok = true;
      if ($child['debut'] && $child['debut']>date("Ymd")) $ok = false;
      if ($child['fin']   && $child['fin']  <date("Ymd")) $ok = false;
			if ($ok && GroupeService::canMakeInGroupe('VIEW_HOME',$child['droit']))
				$res['inscrits']++;
			elseif ($ok)
				$res['waiting']++;
		}
		return $res;
	}

	/**
	 * Renvoie le blog d'un groupe
	 *
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2006/03/09
	 * @param integer $groupe	Id du groupe
	 * @return mixed NULL si pas de blog, le recordset sinon
	 */
	function getGroupeBlog ($groupe) {
		$blog = NULL;
		$hisModules = Kernel::getModEnabled ("club", $groupe);
		foreach ($hisModules as $node) {
			//print_r($node);
			if ($node->module_type == 'MOD_BLOG') {
				$dao = _dao("blog|blog");
				$blog = $dao->get($node->module_id);
			}
		}
		return $blog;
	}

}


?>
