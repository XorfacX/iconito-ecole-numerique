<?php

_classInclude('liste|listeservice');

/**
 * Actiongroup du module Liste
 * 
 * @package Iconito
 * @subpackage	Liste
 */
class ActionGroupListe extends CopixActionGroup {

   /**
   * Accueil d'une liste
	 * 
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2005/11/23
	 * @param integer $id Id de la liste
   */
   function getListe () {
	 	
		$kernel_service = & CopixClassesFactory::Create ('kernel|kernel');
		
		$id = isset($this->vars["id"]) ? $this->vars["id"] : NULL;
		$errors = array();	
		
	 	$dao = CopixDAOFactory::create("liste|liste_listes");

		$liste = $dao->get($id);
		
		if (!$liste)
			$errors[] = CopixI18N::get ('liste|liste.error.noListe');
		else {
			$mondroit = $kernel_service->getLevel( "MOD_LISTE", $id );
			if (!ListeService::canMakeInListe('VIEW_HOME',$mondroit))
				$errors[] = CopixI18N::get ('kernel|kernel.error.noRights');
			else {
				$parent = $kernel_service->getModParentInfo( "MOD_LISTE", $id);
				$liste->parent = $parent;
			}
		}
		//print_r($liste);
		
		if ($errors) {
			return CopixActionGroup::process ('genericTools|Messages::getError', array ('message'=>implode('<br/>',$errors), 'back'=>CopixUrl::get('liste||')));
		} else {
      
     	CopixHTMLHeader::addCSSLink (_resource("styles/module_liste.css")); 

			$tpl = & new CopixTpl ();
			$tpl->assign ('TITLE_PAGE', $liste->parent["nom"]);
			$tpl->assign ('MENU', '<a href="'.CopixUrl::get ('minimail||getListSend').'">'.CopixI18N::get ('liste|liste.homeLinkMsgSend').'</a> :: <a href="'.CopixUrl::get (''.$liste->parent["module"].'||go', array("id"=>$liste->parent["id"])).'">'.CopixI18N::get ('liste|liste.backParent').'</a>');
			
			$tplListe = & new CopixTpl ();
			$tplListe->assign ('liste', $liste);
      $tplListe->assign ('canWrite', ListeService::canMakeInListe('WRITE',$mondroit));

			$result = $tplListe->fetch('getliste.tpl');
			$tpl->assign ('MAIN', $result);
			
			return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);
		}
	}

   
   /**
   * Formulaire d'�criture d'un message
	 * 
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2005/11/23
	 * @see doMessageForm()
	 * @param integer $liste Id de la liste sur laquelle on �crit
	 * @param string $title Titre du message
	 * @param string $message Corps du message
	 * @param integer $preview (option) Si 1, affichera la preview du message soumis, si 0 validera le formulaire
   * @param array $errors Erreurs d�j� rencontr�es
   */
	 function getMessageForm () {
		
		$kernel_service = & CopixClassesFactory::Create ('kernel|kernel');
		
		$criticErrors = array();
		$liste = isset($this->vars["liste"]) ? $this->vars["liste"] : NULL;
		$titre = isset($this->vars["titre"]) ? $this->vars["titre"] : NULL;
		$message = isset($this->vars["message"]) ? $this->vars["message"] : NULL;
		$preview = isset($this->vars["preview"]) ? $this->vars["preview"] : 0;
		$errors = isset($this->vars["errors"]) ? $this->vars["errors"] : array();

		if ($liste) {		// Nouveau message dans une liste
			$dao_listes = CopixDAOFactory::create("liste|liste_listes");
			$rListe = $dao_listes->get($liste);
			if (!$rListe)
				$criticErrors[] = CopixI18N::get ('liste|liste.error.noListe');
			else {
				$kernel_service = & CopixClassesFactory::Create ('kernel|kernel');
				$mondroit = $kernel_service->getLevel( "MOD_LISTE", $liste);
				if (!ListeService::canMakeInListe('WRITE',$mondroit))
					$criticErrors[] = CopixI18N::get ('kernel|kernel.error.noRights');
			}
		} else {
			$criticErrors[] = CopixI18N::get ('liste|liste.error.impossible');
		}
		
		if ($criticErrors) {
			return CopixActionGroup::process ('genericTools|Messages::getError', array ('message'=>implode('<br/>',$criticErrors), 'back'=>CopixUrl::get('liste||')));
		} else {

		
			// $contexte = $dao->get($id);
			// rustine PNL pour afficher le nom du groupe
			$id = isset($this->vars["liste"]) ? $this->vars["liste"] : NULL;
			$parent = $kernel_service->getModParentInfo( "MOD_LISTE", $id);
		
			$tpl = & new CopixTpl ();
			$title_page = $parent["nom"];
			$tpl->assign ('TITLE_PAGE', $title_page);
			
			$format = CopixConfig::get ('minimail|default_format');
			
			$tplForm = & new CopixTpl ();
			$tplForm->assign ('liste', $liste);
			$tplForm->assign ('titre', $titre);
			$tplForm->assign ('message', $message);
			$tplForm->assign ('preview', $preview);
			$tplForm->assign ('errors', $errors);
			//$tplForm->assign ('wikibuttons', CopixZone::process ('kernel|wikibuttons', array('field'=>'message', 'format'=>CopixConfig::get ('minimail|default_format'), 'object'=>array('type'=>'MOD_LISTE', 'id'=>$id))));
			$tplForm->assign ('message_edition', CopixZone::process ('kernel|edition', array('field'=>'message', 'format'=>$format, 'content'=>$message, 'height'=>200, 'object'=>array('type'=>'MOD_LISTE', 'id'=>$id))));


			$tplForm->assign ('format', $format);
			$result = $tplForm->fetch('getmessageform.tpl');
			$tpl->assign ('MAIN', $result);
			
			return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);
		}
	}
	
	
   /**
   * Soumission du formulaire d'�criture d'un message sur une liste
	 * 
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2005/11/23
	 * @see getMessageForm()
	 * @param integer $liste Id de la liste sur laquelle on �crit
	 * @param string $title Titre du minimail
	 * @param string $message Corps du minimail
	 * @param string $go Forme de soumission : preview (pr�visualiser) ou send (enregistrer)
   */
	function doMessageForm () {
	
		$errors = $criticErrors = array();	
		$liste = isset($this->vars["liste"]) ? $this->vars["liste"] : NULL;
		$titre = isset($this->vars["titre"]) ? $this->vars["titre"] : NULL;
		$message = isset($this->vars["message"]) ? $this->vars["message"] : NULL;
		$go = isset($this->vars["go"]) ? $this->vars["go"] : 'preview';
		
		if ($liste) {		// Nouveau message
			$dao_listes = CopixDAOFactory::create("liste|liste_listes");
			$rListe = $dao_listes->get($liste);
			if (!$rListe)
				$criticErrors[] = CopixI18N::get ('liste|liste.error.noListe');
			else {
				$kernel_service = & CopixClassesFactory::Create ('kernel|kernel');
				$mondroit = $kernel_service->getLevel( "MOD_LISTE", $liste);
				if (!ListeService::canMakeInListe('WRITE',$mondroit))
					$criticErrors[] = CopixI18N::get ('kernel|kernel.error.noRights');
			}
		} else {
			$criticErrors[] = CopixI18N::get ('liste|liste.error.impossible');
		}

		if (!$titre)	$errors[] = CopixI18N::get ('liste|liste.error.typeTitle');
		if (!$message)	$errors[] = CopixI18N::get ('liste|liste.error.typeMessage');

		if ($criticErrors) {
			return CopixActionGroup::process ('genericTools|Messages::getError', array ('message'=>implode('<br/>',$criticErrors), 'back'=>CopixUrl::get('liste||')));
		} else {

			$auteur = $_SESSION["user"]->bu["user_id"];
			
			if (!$errors && $go=='save') {	// Insertion
				$service = CopixClassesFactory::create("ListeService");
				$add = $service->addListeMessage ($liste, $auteur, $titre, $message);
				if (!$add)
					$errors[] = CopixI18N::get ('liste|liste.error.sendMessage');
				$urlReturn = CopixUrl::get ('liste||getListe', array("id"=>$liste));
				if (!$errors)
					return new CopixActionReturn (COPIX_AR_REDIRECT, $urlReturn);
			}
				
			return CopixActionGroup::process ('liste|liste::getMessageForm', array ('liste'=>$liste, 'titre'=>$titre, 'message'=>$message, 'errors'=>$errors, 'preview'=>(($go=='save')?0:1)));

		}
	}
	
}

?>