<?php

/**
 * Fonctions diverses du module Groupe
 * 
 * @package Iconito
 * @subpackage	Groupe
 */
class GroupeService {

	/**
	 * Cr�e un groupe
	 *
	 * Cr�e le groupe, donne les droits de propri�taire � son cr�ateur, et initialise les premiers modules.
	 *
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2005/11/08
	 * @param string $titre Titre
	 * @param string $description Description
	 * @param integer $is_open 1 si le groupe est public, 0 s'il est priv�
	 * @param integer $createur Id utilisateur du cr�ateur
	 * @param array $tab_membres Tableau avec les membres � inscrire de suite (cl� = id user)
	 * @param array $his_modules Tableau avec les modules � installer et � relier � ce club
	 * @param string $parentClass Type du parent (BU_ECOLE...) 
	 * @param integer $parentRef R�f�rence du parent
	 * @return integer l'Id du groupe cr�� ou NULL si erreur
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
					if ($new) {	// Module bien cr�e, on le rattache
						$register = $kernelClasse->registerModule( $moduleType, $new, "CLUB", $newGroupe->id );
					}
				}

				// On ins�re le cr�ateur
				$userInfo = $kernelClasse->getUserInfo("ID", $createur);
				$kernelClasse->setLevel("CLUB", $newGroupe->id, $userInfo["type"], $userInfo["id"], PROFILE_CCV_ADMIN);
				
				// On ins�re les �ventuels membres
				while (list($userId,) = each ($tab_membres)) {
					$userInfo = $kernelClasse->getUserInfo("ID", $userId);
					$kernelClasse->setLevel("CLUB", $newGroupe->id, $userInfo["type"], $userInfo["id"], PROFILE_CCV_MEMBER);
				}
				
				// On rattache le groupe � son parent
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
	 * Teste si l'usager peut effectuer une certaine op�ration par rapport � son droit. Le droit sur le groupe n�cessite d'�tre connu, renvoy� par le kernel avant l'entr�e dans cette fonction.
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
					
			case "UNSUBSCRIBE_HIMSELF" :	// Se d�sinscrire (soi-m�me)
				$can = ($droit >= PROFILE_CCV_READ && $droit < PROFILE_CCV_ADMIN);	
				break;
					
			case "ADD_GROUP" :	// Cr�ation d'un groupe, ne d�pend pas du droit sur un groupe mais du profil de l'usager
				$can = (Kernel::isEnseignant() || Kernel::isAgentVille() || Kernel::isPersonnelAdministratif() || Kernel::isAnimateur() || Kernel::isAdmin());
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
	 * @return string Nom correspondant � ce droit, en clair
	 */
	function getRightName ($droit) {
		switch ($droit) {
			case PROFILE_CCV_ADMIN :	// Propri�taire
				$res = CopixI18N::get ('groupe|groupe.right.owner');
				break;
			case PROFILE_CCV_MODERATE :	// Mod�rateur
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
	 * Renvoie le nombre d'inscrits � un groupe
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

	/**
	 * Renvoie la ville d'un groupe
	 *
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2006/09/25
	 * @param integer $pGroupe	Id du groupe
	 * @param array $pParent (option) Parent(s) du groupe. Si null ou pas passe, se charge de le chercher en base. Si passe, doit provenir de la fonction getNodeParents
	 * @return integer Id de la ville, ou 0 si aucune
	 */
	function getGroupeVille ($pGroupe, $pParent=null) {
		$ville = 0;
		$parent = ($pParent==null) ? Kernel::getNodeParents('CLUB', $pGroupe) : $pParent;
		//var_dump($parent);
		if ($parent && $parent[0]['type'] == 'BU_VILLE') {
			$ville = $parent[0]['id'];
		}	elseif ($parent && $parent[0]['type'] == 'BU_CLASSE') {
			$ville = $parent[0]['ALL']->eco_id_ville;
		}	elseif ($parent && $parent[0]['type'] == 'BU_ECOLE') {
			$ville = $parent[0]['ALL']->vil_id_vi;
		}		
		return $ville;
	}



}


?>
