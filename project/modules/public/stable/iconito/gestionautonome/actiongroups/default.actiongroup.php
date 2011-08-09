<?php
/**
* @package    Iconito
* @subpackage Gestionautonome
* @author     Jérémy FOURNAISE <jeremy.fournaise@isics.fr>, Sébastien CAS <sebastien.cas@isics.fr>
*/

class ActionGroupDefault extends enicActionGroup {

	private $menu;
	
	public function beforeAction () {
		_currentUser()->assertCredential('module:*||access|@gestionautonome');
		
		$this->menu = array();
		if($this->user->root || _currentUser()->hasAssistance('can_comptes') ) $this->menu[] = array( 'txt' => CopixI18N::get('comptes|comptes.strings.getext'), 'url' => CopixUrl::get ('comptes||getUserExt'), 'size'=>160 );
		if($this->user->root) $this->menu[] = array( 'txt' => CopixI18N::get('comptes|comptes.strings.getanim'), 'url' => CopixUrl::get ('comptes|animateurs|list'), 'size'=>120 );
		
		$this->addJs('js/iconito/module_gestionautonome.js');
	}

	/**
	 * Action par défaut => redirige vers showTree
	 */
	public function processDefault () {
	  
	  return _arRedirect (CopixUrl::get ('gestionautonome||showTree'));
	}
	
	/**
	 * Retourne la liste globale des mots de passe
	 */
	public function processGetPasswordsList () {
	  
	  $passwords = _sessionGet('modules|gestionautonome|passwordsList');
	  if (!is_array ($passwords)) {

	    $passwords = array();
	  }
	  else {
	    
	    $toDisplay = array();
	    foreach ($passwords as $lines) {
	      
	      foreach ($lines as $data) {
	        
	        $toDisplay[] = $data;
	      }	      
	    }
	    $passwords = $toDisplay;
	  }
	  
	  $tplResult = & new CopixTpl ();
	  $tplResult->assign ('sessionDatas', $passwords);
	  
	  $format = _request ('format', 'html');
	  if ($format == 'html') {
	    
	    $html = $tplResult->fetch ('account_listing_html.tpl');
	    return _arContent ($html, array ('filename'=>'Logins-'.date('YmdHi').'.html', 'content-disposition'=>'inline', 'content-type' => 'text/html'));
	  }
	  elseif ($format == 'csv') {
	    
	    $csv = $tplResult->fetch ('account_listing_csv.tpl');
	    
	    header('Pragma: public');
      header('Date: '.gmdate('D, d M Y H:i:s', time()).' GMT');
      header('Last-Modified: '.gmdate('D, d M Y H:i:s', time()).' GMT');
      header('Expires: '.gmdate('D, d M Y H:i:s',time()).' GMT');
      header('Cache-Control: must-revalidate, pre-check=0, post-check=0, max-age=0');
      header('Content-Type: application/force-download');
			header('Content-Type: application/octet-stream', true);
			header('Content-Type: application/download', true);
			header('Content-Type: text/csv', true);
      header('Content-Disposition: attachment; filename="Comptes-'.date('YmdHi').'.csv"');
      header('Content-Transfer-Encoding: binary');
      header('Content-Length: '.strlen($csv));
      
      echo $csv;
      return _arNone();
	  }
	  
	  return CopixActionGroup::process ('generictools|Messages::getError', array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get ('gestionautonome||showTree')));
	}
	
	/**
	 * Affichage principal (arbre)
	 */
	public function processShowTree () {    
    
    $ppo = new CopixPPO ();
    
	  $ppo->user = _currentUser ();
	  
    // Y a t-il eu des modifications ?
    $ppo->save = _request ('save', null); 
    
    // Sélection de l'onglet courant
    $ppo->tab = _request ('tab', null);

    // Récupération de l'année scolaire : si non précisée, récupération de l'année scolaire courante
    if (is_null($ppo->grade = _sessionGet('grade'))) {
      
      $ppo->grade = Kernel::getAnneeScolaireCourante ()->id_as;
    }
    
    // Affectation des zones : accès dashboard
    if (!is_null($myNode = _sessionGet('myNode'))) {
      
      $ppo->targetId   = $myNode['id'];
      $ppo->targetType = $myNode['type'];
      
      $myNodeInfos = Kernel::getNodeInfo ($myNode['type'], $myNode['id']);

      switch ($ppo->targetType) {
        case 'BU_VILLE':
          $this->setNodeStatusInSession ('cities_groups_nodes', $myNodeInfos['ALL']->vil_id_grville, true);
          break;
        case 'BU_ECOLE':
          $this->setNodeStatusInSession ('cities_groups_nodes', $myNodeInfos['ALL']->vil_id_grville, true);
          $this->setNodeStatusInSession ('cities_nodes', $myNodeInfos['ALL']->eco_id_ville, true);
          break;
        case 'BU_CLASSE':
          $cityGroup = Kernel::getNodeInfo ('BU_VILLE', $myNodeInfos['ALL']->eco_id_ville, false);
          
          $this->setNodeStatusInSession ('cities_groups_nodes', $cityGroup['ALL']->grv_id_grv, true);
          $this->setNodeStatusInSession ('cities_nodes', $myNodeInfos['ALL']->eco_id_ville, true);
          $this->setNodeStatusInSession ('schools_nodes', $myNodeInfos['ALL']->eco_numero, true);  
    	    break;
      }
      
      _sessionSet('current', array ('node_type' => $myNode['type'], 'node_id' => $myNode['id']));
    }
    // Réaffectation des zones après retour d'action
    elseif (!is_null($currentInSession = _sessionGet ('current'))) {

      $ppo->targetId   = $currentInSession['node_id'];
      $ppo->targetType = $currentInSession['node_type'];
    }
    
    // Récupération de la liste des années scolaires disponibles pour select
	  $gradesDAO = _ioDAO ('kernel_bu_annee_scolaire');
	  $c = _daoSp ();
    $c->orderBy ('id_as');
	  $grades = $gradesDAO->findBy ($c);
	  foreach ($grades as $grade) {

	    $ppo->gradesIds[]   = $grade->id_as;
	    $ppo->gradesNames[] = $grade->annee_scolaire;
	  }
    
    $ppo->TITLE_PAGE = CopixConfig::get('gestionautonome|moduleTitle');
    $ppo->MENU = $this->menu;
    
    _sessionSet('myNode', null);

		return _arPPO ($ppo, 'show_tree.tpl');
	}
	
	public function processRefreshTree () {
	  
	  $grade = _request ('grade', Kernel::getAnneeScolaireCourante ()->id_as);
	  _sessionSet('grade', $grade);
	  
	  _sessionSet ('current', null);
	  
	  echo CopixZone::process ('gestionautonome|citiesGroup');
	  
	  return _arNone ();
	}
	
	/**
	 * Toggle d'un noeud
	 */
	public function processToggleNode () {

	  $nodeType = _request('node_type');
	  $nodeId = _request('node_id');
	  $showForced = _request('show_forced', false);
	  
	  if (!is_null($nodeType) && !is_null($nodeId)) {
	    
	    switch ($nodeType) {
	      
	      case 'cities-group':
	        $this->setNodeStatusInSession ('cities_groups_nodes', $nodeId, $showForced);
	        echo CopixZone::process ('gestionautonome|city', array ('cities_group_id' => $nodeId));
	        break;
	      case 'city':
	        $this->setNodeStatusInSession ('cities_nodes', $nodeId, $showForced);
	        echo CopixZone::process ('gestionautonome|school', array ('city_id' => $nodeId));
	        break;
	      case 'school':
	        $this->setNodeStatusInSession ('schools_nodes', $nodeId, $showForced);
	        echo CopixZone::process ('gestionautonome|classroom', array ('school_id' => $nodeId));
	        break;
	    }
	  }
	  
	  return _arNone ();
	}

  /**
   * Mise en session de l'état de l'arbre
   */ 
	private function setNodeStatusInSession ($type, $nodeId, $showForced) {
	  
	  // Récupération de l'état actuel ou initialisation
    $nodesAr = _sessionGet ($type);
    if (is_null($nodesAr)) {
      
      $nodesAr = array();
    }
      
    if (isset($nodesAr[$nodeId]) && !$showForced) {
      
      unset($nodesAr[$nodeId]);
    }
    else {
      
      $nodesAr[$nodeId] =	$nodeId;
    }
    
    _sessionSet ($type, $nodesAr);
	}
	
	/**
	 * Recherche de noeuds
	 */
	public function processSearch () {
	  
	  $value = _request ('value');
	  if (!is_null($value)) {
	    
	    // Réinitialisation des valeurs de session
	    _sessionSet ('current', null);
	    _sessionSet ('cities_groups_nodes', array());
	    _sessionSet ('cities_nodes', array());
	    _sessionSet ('schools_nodes', array());
	    
	    if (is_null($grade = _sessionGet('grade'))) {

        $grade = Kernel::getAnneeScolaireCourante ()->id_as;
      }
      
	    $ppo->matchedNodes = array(
	      'cities_groups' => array(),
	      'cities'        => array(),
	      'schools'       => array(),
	      'classrooms'    => array(),
	    );
	    
	    // Recherche dans les groupes de villes
	    $citiesGroupsDAO = _ioDAO ('kernel|kernel_bu_groupe_villes');
	    $criteria = _daoSp ();
	    $criteria->addCondition ('nom_groupe', 'LIKE', '%'.$value.'%');
	    
	    $citiesGroups = $citiesGroupsDAO->findBy ($criteria);
	    foreach ($citiesGroups as $citiesGroup) {
	     
	      $ppo->matchedNodes['cities_groups'][] = $citiesGroup->id_grv;
	    }
	    
	    // Recherche dans les villes
	    $citiesGroupNodesAr = array();
	    
	    $cityDAO = _ioDAO ('kernel|kernel_bu_ville');
	    $criteria = _daoSp ();
	    $criteria->addCondition ('nom', 'LIKE', '%'.$value.'%');
	    
	    $cities = $cityDAO->findBy ($criteria);
	    foreach ($cities as $city) {
	     
	      $ppo->matchedNodes['cities'][] = $city->id_vi;
	      
	      // Ouverture du path
	      if ($citiesGroup = $city->getCitiesGroup ()) {
	        
	        $citiesGroupNodesAr[] = $citiesGroup->id_grv;
	      }
	    }
	    
	    // Recherche dans les écoles
	    $cityNodesAr = array();
	    
	    $schoolDAO = _ioDAO ('kernel|kernel_bu_ecole');
	    $criteria = _daoSp ();
	    $criteria->addCondition ('nom', 'LIKE', '%'.$value.'%');
	    
	    $schools = $schoolDAO->findBy ($criteria);
	    foreach ($schools as $school) {
	     
	      $ppo->matchedNodes['schools'][] = $school->numero;
	      
	      // Ouverture du path
	      if ($city = $school->getCity ()) {
	        
	        $cityNodesAr[] = $city->id_vi;
	        if ($citiesGroup = $city->getCitiesGroup ()) {

  	        $citiesGroupNodesAr[] = $citiesGroup->id_grv;
  	      }
	      }	      
	    }
	    
	    // Recherche dans les classes
	    $schoolNodesAr = array();
	    
	    $classroomDAO = _ioDAO ('kernel|kernel_bu_ecole_classe');
	    $criteria = _daoSp ();
	    $criteria->addCondition ('nom', 'LIKE', '%'.$value.'%');
	    $criteria->addCondition ('annee_scol', '=', $grade);
	    
	    $classrooms = $classroomDAO->findBy ($criteria);
	    foreach ($classrooms as $classroom) {
	     
	      $ppo->matchedNodes['classrooms'][] = $classroom->id;
	      
	      // Ouverture du path
	      if ($school = $classroom->getSchool ()) {
	        
	        $schoolNodesAr[] = $school->numero;
	        if ($city = $school->getCity ()) {

  	        $cityNodesAr[] = $city->id_vi;
  	        if ($citiesGroup = $city->getCitiesGroup ()) {

    	        $citiesGroupNodesAr[] = $citiesGroup->id_grv;
    	      }
  	      }
	      }	      
	    }
	    
	    _sessionSet ('cities_groups_nodes', $citiesGroupNodesAr);
	    _sessionSet ('cities_nodes', $cityNodesAr);
	    _sessionSet ('schools_nodes', $schoolNodesAr);
	  }

	  echo CopixZone::process ('gestionautonome|searchresult', array('matched_nodes' => $ppo->matchedNodes));
	  
	  return _arNone();
	}
	
	/**
	 * displayPersonsData (Ajax)
	 *
	 * Récupération des personnes du noeud courant.
	 * 
	 */
	public function processDisplayPersonsData () {
	
	  $nodeId   = _request ('node_id', null);
	  $nodeType = _request ('node_type', null);

	  if (!is_null($nodeType) && !is_null($nodeId)) {
	    
	    $typeRef = null;
	    switch ($nodeType) {
	      
	      case 'cities-group':
	        $typeRef = 'BU_GRVILLE';
	        break;
	      case 'city':
	        $typeRef = 'BU_VILLE';
	        break;
	      case 'school':
	        $typeRef = 'BU_ECOLE';
	        break;
	      case 'classroom':
	        $typeRef = 'BU_CLASSE';
	    }
	    
		  echo CopixZone::process ('gestionautonome|PersonsData', array ('node_id' => $nodeId, 'node_type' => $typeRef));
    }
    return _arNone ();
	}
	
	/**
	 * updateTreeActions (Ajax)
	 *
	 * Récupération des actions disponibles pour le noeud courant.
	 * 
	 */
	public function processUpdateTreeActions () {

	  $nodeId   = _request ('node_id', null);
	  $nodeType = _request ('node_type', null);

	  if (!is_null($nodeType) && !is_null($nodeId)) {
	    
	    $typeRef = null;
	    switch ($nodeType) {
	      
	      case 'cities-group':
	        $typeRef = 'BU_GRVILLE';
	        break;
	      case 'city':
	        $typeRef = 'BU_VILLE';
	        break;
	      case 'school':
	        $typeRef = 'BU_ECOLE';
	        break;
	      case 'classroom':
	        $typeRef = 'BU_CLASSE';
	    }
	    	    
	    echo CopixZone::process ('gestionautonome|TreeActions', array ('node_id' => $nodeId, 'node_type' => $typeRef));
	    
	    // Stockage de l'état courrant
	    _sessionSet ('current', array('node_type' => $typeRef, 'node_id' => $nodeId));
	  }
		
    return _arNone ();
	}
	
	/**
	 * createCity
	 *
	 * Création d'une ville.
	 * 
	 */
	public function processCreateCity () {
	  
	  $ppo = new CopixPPO ();
	  
	  // Récupération des paramètres
	  $ppo->parentId    = _request ('nodeId', null);
	  $ppo->parentType  = _request ('nodeType', null);
	  
	  // La création d'une ville n'est possible qu'à partir d'un groupe de ville
	  if (is_null ($ppo->parentId) || is_null ($ppo->parentType) || $ppo->parentType != 'BU_GRVILLE') {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get('gestionautonome||showTree')));
	  }
	  
	  $citiesGroupDAO = _ioDAO ('kernel|kernel_bu_groupe_villes');
	  if (!$citiesGroup = $citiesGroupDAO->get ($ppo->parentId)) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get('gestionautonome||showTree')));
	  }
	  
	  _currentUser()->assertCredential('module:cities_group|'.$ppo->parentId.'|city|create@gestionautonome');

	  // Breadcrumbs
	  $breadcrumbs   = array();
	  $breadcrumbs[] = array('txt' => 'Gestion de la structure scolaire', 'url' => CopixUrl::get('gestionautonome||showTree'));
	  $breadcrumbs[] = array('txt' => 'Création d\'une ville');
	  
	  $ppo->breadcrumbs = Kernel::PetitPoucet ($breadcrumbs,' &raquo; ');
    
    $ppo->TITLE_PAGE = CopixConfig::get('gestionautonome|moduleTitle');
    
		return _arPPO ($ppo, 'create_city.tpl');
	}
	
	/**
	 * validateCityCreation
	 *
	 * Validation du formulaire de création de ville.
	 * 
	 */
	public function processValidateCityCreation () {
	  
	  $ppo = new CopixPPO ();
	  
	  // Récupération des paramètres
	  $ppo->parentId    = _request ('id_parent', null);
	  $ppo->parentType  = _request ('type_parent', null);
	  
	  if (is_null ($ppo->parentId) || is_null ($ppo->parentType) || $ppo->parentType != 'BU_GRVILLE') {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get('gestionautonome||showTree')));
	  }
	  
	  $citiesGroupDAO = _ioDAO ('kernel|kernel_bu_groupe_villes');
	  if (!$citiesGroup = $citiesGroupDAO->get ($ppo->parentId)) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get('gestionautonome||showTree')));
	  }
	  
	  _currentUser()->assertCredential('module:cities_group|'.$ppo->parentId.'|city|create@gestionautonome');
	  
    // Récupération des paramètres
    $cityName  = _request ('nom', null); 
    $ppo->city = _record ('kernel|kernel_bu_ville');

    $ppo->city->nom           = trim ($cityName);
    $ppo->city->canon         = Kernel::createCanon ($cityName);
    $ppo->city->id_grville    = $ppo->parentId;
    $ppo->city->date_creation = CopixDateTime::timestampToYYYYMMDDHHIISS (time ());
    
    // Traitement des erreurs
    $ppo->errors = array ();
    
    if (!$ppo->city->nom) {
      
      $ppo->errors[] = 'Saisissez un nom';
    }
    if (!$ppo->city->id_grville) {
      
      $ppo->errors[] = 'Une erreur est survenue';
    }
    
    if (!empty ($ppo->errors)) {
      
      // Breadcrumbs
  	  $breadcrumbs   = array();
  	  $breadcrumbs[] = array('txt' => 'Gestion de la structure scolaire', 'url' => CopixUrl::get('gestionautonome||showTree'));
  	  $breadcrumbs[] = array('txt' => 'Création d\'une ville');

  	  $ppo->breadcrumbs = Kernel::PetitPoucet ($breadcrumbs,' &raquo; ');
  	  
  	  $ppo->TITLE_PAGE = CopixConfig::get('gestionautonome|moduleTitle');
  	  
      return _arPPO ($ppo, 'create_city.tpl');
    }
     
	  $cityDAO = _ioDAO ('kernel|kernel_bu_ville');

    $cityDAO->insert ($ppo->city);
    
    // Mise en session du noeud courant
		_sessionSet ('current', array('node_type' => 'BU_VILLE', 'node_id' => $ppo->city->id_vi));

		return _arRedirect (CopixUrl::get ('gestionautonome||showTree', array ('save' => 1)));
  }
  
  /**
	 * updateCity
	 *
	 * Edition d'une ville.
	 * 
	 */
	public function processUpdateCity () {
	  
	  $ppo = new CopixPPO ();
	  
	  // Récupération des paramètres
	  $ppo->nodeId  = _request ('nodeId', null);
	  if (is_null ($ppo->nodeId)) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get('gestionautonome||showTree')));
	  }
	  
	  // Validation des paramètres
		$cityDAO = _ioDAO ('kernel|kernel_bu_ville');
	  if (!$ppo->city = $cityDAO->get ($ppo->nodeId)) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get('gestionautonome||showTree')));
	  }

	  _currentUser()->assertCredential('module:cities_group|'.$ppo->city->id_grville.'|city|update@gestionautonome');
	  
	  // Mise en session du noeud courant
		_sessionSet ('current', array('node_type' => 'BU_VILLE', 'node_id' => $ppo->city->id_vi));
		
	  // Breadcrumbs
	  $breadcrumbs   = array();
	  $breadcrumbs[] = array('txt' => 'Gestion de la structure scolaire', 'url' => CopixUrl::get('gestionautonome||showTree'));
	  $breadcrumbs[] = array('txt' => $ppo->city->nom);
	  
	  $ppo->breadcrumbs = Kernel::PetitPoucet ($breadcrumbs,' &raquo; ');
		
		$ppo->TITLE_PAGE = CopixConfig::get('gestionautonome|moduleTitle');
		
		return _arPPO ($ppo, 'update_city.tpl');
	}
	
	/**
	 * validateCityUpdate
	 *
	 * Validation du formulaire d'édition de ville.
	 * 
	 */
	public function processValidateCityUpdate () {
	  
	  $ppo = new CopixPPO ();
	  
	  $ppo->nodeId = _request ('id_node', null);
		if (is_null ($ppo->nodeId)) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get('gestionautonome||showTree')));
	  }
	  
		// Validation des paramètres
		$cityDAO = _ioDAO ('kernel|kernel_bu_ville');
	  if (!$ppo->city = $cityDAO->get ($ppo->nodeId)) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get('gestionautonome||showTree')));
	  }
	  
		_currentUser()->assertCredential('module:cities_group|'.$ppo->city->id_grville.'|city|update@gestionautonome');
    
    // Nom courrant pour fil d'ariane
    $name = $ppo->city->nom;
    
    // Récupération des paramètres
    $cityName = _request ('name', null);
    
    $ppo->city->nom   = trim ($cityName);
    $ppo->city->canon = Kernel::createCanon ($cityName);

    // Traitement des erreurs
    $ppo->errors = array ();
    
    if (!$ppo->city->nom) {
      
      $ppo->errors[] = 'Saisissez un nom';
    }
    
    if (!empty ($ppo->errors)) {
  	  
  	  // Breadcrumbs
  	  $breadcrumbs   = array();
  	  $breadcrumbs[] = array('txt' => 'Gestion de la structure scolaire', 'url' => CopixUrl::get('gestionautonome||showTree'));
  	  $breadcrumbs[] = array('txt' => $name);

  	  $ppo->breadcrumbs = Kernel::PetitPoucet ($breadcrumbs,' &raquo; ');
  	  
  	  $ppo->TITLE_PAGE = CopixConfig::get('gestionautonome|moduleTitle');
  	  
      return _arPPO ($ppo, 'update_city.tpl');
    }
      
    $cityDAO->update ($ppo->city);
		
		return _arRedirect (CopixUrl::get ('gestionautonome||showTree', array ('save' => 1)));
	}
	
	/**
	 * deleteVille
	 *
	 * Suppression d'une ville.
	 * 
	 */
	public function processDeleteCity () {
	  
	  $ppo = new CopixPPO ();
	  
	  // Récupération des paramètres
	  $nodeId = _request ('nodeId', null);
	  if (is_null ($nodeId)) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get('gestionautonome||showTree')));
	  }
	  
	  // Validation des paramètres
		$cityDAO = _ioDAO ('kernel|kernel_bu_ville');
	  if (!$city = $cityDAO->get ($nodeId)) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get('gestionautonome||showTree')));
	  }
	  
	  _currentUser()->assertCredential('module:cities_group|'.$ppo->city->id_grville.'|city|delete@gestionautonome');
	  
	  /**
	   * TODO : refactoring des suppressions
	   */
	  
	  // DAO
	  $schoolDAO              = _ioDAO ('kernel|kernel_bu_ecole');
	  $classDAO               = _ioDAO ('kernel|kernel_bu_ecole_classe');
	  $classLevelDAO          = _ioDAO ('kernel|kernel_bu_ecole_classe_niveau');
	  $studentDAO             = _ioDAO ('kernel|kernel_bu_ele');
	  $studentAssignmentDAO   = _ioDAO ('kernel|kernel_bu_ele_affect');
	  $studentAdmissionDAO    = _ioDAO ('kernel|kernel_bu_eleve_admission');
	  $studentRegistrationDAO = _ioDAO ('kernel|kernel_bu_eleve_inscription');
	                 
	  // Mise en session du noeud parent
	  _sessionSet ('current', array('node_type' => 'BU_GRVILLE', 'node_id' => $city->id_grville));
	  
	  // Récupération des écoles de la ville
	  $schools = $schoolDAO->getByCity ($nodeId);
	  
	  foreach ($schools as $school) {

	    // Récupération des classes de la ville
	    $classes = $classDAO->getBySchool ($school->numero);
	    
	    foreach ($classes as $class) {

	      // Récupération des associations classe-niveau
	      $classLevels = $classLevelDAO->getByClass ($class->id);
	      
	      foreach ($classLevels as $classLevel) {
	        
	        // Suppression des associations classe-niveau
  	      $classLevelDAO->delete ($classLevel->classe, $classLevel->niveau);
	      }
	      
	      // Récupération des élèves de la classe
	      $students = $studentDAO->getElevesInClasse ($class->id);
        foreach ($students as $student) {

          // Récupération de l'affectation de l'élève à la classe
          if ($studentAssignment = $studentAssignmentDAO->getByStudentAndClass ($student->id, $class->id)) {

            $studentAssignmentDAO->delete ($studentAssignment->affect_id);
          }

          // Récupérations des admission de l'élève
      	  if ($admission = $studentAdmissionDAO->getByStudentAndSchool ($student->id, $school->numero)) {

      	    $studentAdmissionDAO->delete ($admission->admission_numero);  
      	  }
      	  
      	  // Récupérations des inscriptions de l'élève
      	  if ($registration = $studentRegistrationDAO->getByStudentAndSchool ($student->id, $school->numero)) {

      	    $studentRegistrationDAO->delete ($registration->numero);  
      	  }
        }
	      
	      // Suppression de la classe
	      $classDAO->delete ($class->id);
	    }
	    
	    // Suppression de l'école
	    $schoolDAO->delete ($school->numero);
	  }
	  
	  // Suppression de la ville 
  	$cityDAO->delete ($city->id_vi);
		
		return _arRedirect (CopixUrl::get ('gestionautonome||showTree', array ('save' => 1)));
	}
	
	/**
	 * createEcole
	 *
	 * Création d'une école.
	 * 
	 */
	public function processCreateSchool () {
	  
	  $ppo = new CopixPPO ();
	  
	  // Récupération des paramètres
	  $ppo->parentId   = _request ('parentId', null);
	  $ppo->parentType = _request ('parentType', null);
	  
	  if (is_null ($ppo->parentId) || is_null ($ppo->parentType) || $ppo->parentType != 'BU_VILLE') {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get('gestionautonome||showTree')));
	  }
	  
	  $cityDAO = _ioDAO ('kernel|kernel_bu_ville');
	  if (!$city = $cityDAO->get ($ppo->parentId)) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get('gestionautonome||showTree')));
	  }
 
	  _currentUser()->assertCredential('module:city|'.$ppo->parentId.'|school|create@gestionautonome');
	  
	  $ppo->types = array ('Maternelle', 'Elémentaire', 'Primaire');

	  // Breadcrumbs
	  $breadcrumbs   = array();
	  $breadcrumbs[] = array('txt' => 'Gestion de la structure scolaire', 'url' => CopixUrl::get('gestionautonome||showTree'));
	  $breadcrumbs[] = array('txt' => $city->nom, 'url' => CopixUrl::get('gestionautonome||updateCity', array ('nodeId' => $ppo->parentId)));
	  $breadcrumbs[] = array('txt' => 'Création d\'une école');
	  
	  $ppo->breadcrumbs = Kernel::PetitPoucet ($breadcrumbs,' &raquo; ');
		
		$ppo->TITLE_PAGE = CopixConfig::get('gestionautonome|moduleTitle');
		
		return _arPPO ($ppo, 'create_school.tpl');
	}
	
	/**
	 * validateSchoolCreation
	 *
	 * Validation du formulaire de création d'une école.
	 * 
	 */
	public function processValidateSchoolCreation () {
	  
	  $ppo = new CopixPPO ();
	  
	  // Récupération des paramètres
	  $ppo->parentId   = _request ('id_parent', null);
	  $ppo->parentType = _request ('type_parent', null);
	  
	  if (is_null ($ppo->parentId) || is_null ($ppo->parentType) || $ppo->parentType != 'BU_VILLE') {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get('gestionautonome||showTree')));
	  }
	  
	  $cityDAO = _ioDAO ('kernel|kernel_bu_ville');
	  if (!$city = $cityDAO->get ($ppo->parentId)) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get('gestionautonome||showTree')));
	  }
	  
	  _currentUser()->assertCredential('module:city|'.$ppo->parentId.'|school|create@gestionautonome');
    
    // Récupération des paramètres
    $ppo->school = _record ('kernel|kernel_bu_ecole');
    
    $ppo->school->type      = _request ('type', null);
    $ppo->school->nom       = trim (_request ('nom', null));
    $ppo->school->id_ville  = $ppo->parentId;
    $ppo->school->num_rue   = _request ('num_rue', null);
    $ppo->school->adresse1  = _request ('adresse1', null);
    $ppo->school->adresse2  = _request ('adresse2', null);
    $ppo->school->code_postal = _request ('code_postal', null);
    $ppo->school->commune   = _request ('commune', null);
    $ppo->school->tel       = _request ('tel', null);

    // Traitement des erreurs
    $ppo->errors = array ();
    
    if (!$ppo->school->nom) {
      
      $ppo->errors[] = 'Saisissez un nom';
    }
    
    if (!empty ($ppo->errors)) {
      
      // Breadcrumbs
  	  $breadcrumbs   = array();
  	  $breadcrumbs[] = array('txt' => 'Gestion de la structure scolaire', 'url' => CopixUrl::get('gestionautonome||showTree'));
  	  $breadcrumbs[] = array('txt' => $city->nom, 'url' => CopixUrl::get('gestionautonome||updateCity', array ('nodeId' => $ppo->parentId, 'nodeType' => $ppo->parentType)));
  	  $breadcrumbs[] = array('txt' => 'Création d\'une école');

  	  $ppo->breadcrumbs = Kernel::PetitPoucet($breadcrumbs,' &raquo; ');
  	  
  	  $ppo->TITLE_PAGE = CopixConfig::get('gestionautonome|moduleTitle');
  	  
      $ppo->types = array ('Maternelle', 'Elémentaire', 'Primaire');

      return _arPPO ($ppo, 'create_school.tpl');
    }

    $schoolDAO = _ioDAO ('kernel|kernel_bu_ecole');
    
    $schoolDAO->insert ($ppo->school);
    
    // Mise en session du noeud courant
    _sessionSet ('current', array('node_type' => 'BU_ECOLE', 'node_id' => $ppo->school->numero));
    
		return _arRedirect (CopixUrl::get ('gestionautonome||showTree', array ('save' => 1)));
	}
	
	/**
	 * updateSchool
	 *
	 * Edition d'une école.
	 * 
	 */
	public function processUpdateSchool () {
	  
	  $ppo = new CopixPPO ();
	  
	  // Récupération des paramètres
	  $ppo->nodeId = _request ('nodeId', null);
	  if (is_null ($ppo->nodeId)) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get('gestionautonome||showTree')));
	  }
	  
	  $schoolDAO = _ioDAO ('kernel|kernel_bu_ecole');
	  if (!$ppo->school = $schoolDAO->get ($ppo->nodeId)) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get('gestionautonome||showTree')));
	  }
	  
    _currentUser()->assertCredential('module:city|'.$ppo->school->id_ville.'|school|update@gestionautonome');
	  
	  // Mise en session du noeud courant
		_sessionSet ('current', array('node_type' => 'BU_ECOLE', 'node_id' => $ppo->school->numero));
		
    // Liste des types d'école
	  $ppo->types = array ('Maternelle', 'Elémentaire', 'Primaire');
	  
	  // Récupération de la ville pour le fil d'ariane
	  $cityDAO = _ioDAO ('kernel|kernel_bu_ville');
	  $city = $cityDAO->get ($ppo->school->id_ville);

	  // Breadcrumbs
	  $breadcrumbs   = array();
	  $breadcrumbs[] = array('txt' => 'Gestion de la structure scolaire', 'url' => CopixUrl::get('gestionautonome||showTree'));
	  $breadcrumbs[] = array('txt' => $city->nom, 'url' => CopixUrl::get('gestionautonome||updateCity', array ('nodeId' => $city->id_vi)));
	  $breadcrumbs[] = array('txt' => $ppo->school->nom);
	  
	  $ppo->breadcrumbs = Kernel::PetitPoucet($breadcrumbs,' &raquo; ');
		
		$ppo->TITLE_PAGE = CopixConfig::get('gestionautonome|moduleTitle');
		
		return _arPPO ($ppo, 'update_school.tpl');
	}
	
	/**
	 * validateSchoolUpdate
	 *
	 * Validation du formulaire d'édition d'une école.
	 * 
	 */
	public function processValidateSchoolUpdate () {
	  
	  $ppo = new CopixPPO ();
	  
	  // Récupération des paramètres
	  $ppo->nodeId   = _request ('id_node', null);
	  if (is_null ($ppo->nodeId)) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get('gestionautonome||showTree')));
	  }
	  
	  $schoolDAO = _ioDAO ('kernel|kernel_bu_ecole');
	  if (!$ppo->school = $schoolDAO->get ($ppo->nodeId)) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get('gestionautonome||showTree')));
	  }
	  
	  _currentUser()->assertCredential('module:city|'.$ppo->school->id_ville.'|school|update@gestionautonome');
    
    // Nom pour fil d'ariane
    $name = $ppo->school->nom;
    
    // Récupération des paramètres
    $ppo->school->type      = _request ('type', null);
    $ppo->school->nom       = trim (_request ('nom', null));
    $ppo->school->num_rue   = _request ('num_rue', null);
    $ppo->school->adresse1  = _request ('adresse1', null);
    $ppo->school->adresse2  = _request ('adresse2', null);
    $ppo->school->code_postal = _request ('code_postal', null);
    $ppo->school->commune   = _request ('commune', null);
    $ppo->school->tel       = _request ('tel', null);
		
    // Traitement des erreurs
    $ppo->errors = array ();
    
    if (!$ppo->school->nom) {
      
      $ppo->errors[] = 'Saisissez un nom';
    }
    
    if (!empty ($ppo->errors)) {
      
      $cityDAO = _ioDAO ('kernel|kernel_bu_ville');
      $city = $cityDAO->get ($ppo->school->id_ville);
      
  	  // Breadcrumbs
  	  $breadcrumbs   = array();
  	  $breadcrumbs[] = array('txt' => 'Gestion de la structure scolaire', 'url' => CopixUrl::get('gestionautonome||showTree'));
  	  $breadcrumbs[] = array('txt' => $city->nom, 'url' => CopixUrl::get('gestionautonome||updateCity', array ('nodeId' => $city->id_vi)));
  	  $breadcrumbs[] = array('txt' => $name);

  	  $ppo->breadcrumbs = Kernel::PetitPoucet($breadcrumbs,' &raquo; ');
  	  
      $ppo->types = array ('Maternelle', 'Elémentaire', 'Primaire');
      
      $ppo->TITLE_PAGE = CopixConfig::get('gestionautonome|moduleTitle');
      
      return _arPPO ($ppo, 'update_school.tpl');
    }
      
    $schoolDAO->update ($ppo->school);
    
		return _arRedirect (CopixUrl::get ('gestionautonome||showTree', array ('save' => 1)));
	}
	
	/**
	 * deleteSchool
	 *
	 * Suppression d'une école.
	 * 
	 */
	public function processDeleteSchool () {
	  
	  $ppo = new CopixPPO ();
	  
	  // Récupération des paramètres
	  $nodeId = _request ('nodeId', null);
    if (is_null ($nodeId)) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get('gestionautonome||showTree')));
	  }
	  
	  $schoolDAO = _ioDAO ('kernel|kernel_bu_ecole');
	  if (!$school = $schoolDAO->get ($nodeId)) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get('gestionautonome||showTree')));
	  }
	  
	  _currentUser()->assertCredential('module:city|'.$school->id_ville.'|school|delete@gestionautonome');
    
    // Mise en session du noeud parent
	  _sessionSet ('current', array('node_type' => 'BU_VILLE', 'node_id' => $school->id_ville));
	  
    /**
     * TODO : refactoring des suppressions
     */
	  $schoolDAO     = _ioDAO ('kernel|kernel_bu_ecole');
	  $classDAO      = _ioDAO ('kernel|kernel_bu_ecole_classe');
	  $classLevelDAO = _ioDAO ('kernel|kernel_bu_ecole_classe_niveau');
	  $studentDAO    = _ioDAO ('kernel|kernel_bu_ele');
	  $studentAssignmentDAO = _ioDAO ('kernel|kernel_bu_ele_affect');
	  $studentAdmissionDAO = _ioDAO ('kernel|kernel_bu_eleve_admission');
	  
	  // Récupération des classes de l'école
	  $classes = $classDAO->getBySchool ($school->numero);
	    
	  foreach ($classes as $class) {

	    // Récupération de l'association classe-niveau
	    $classLevels = $classLevelDAO->getByClass ($class->id);
	    
	    foreach ($classLevels as $classLevel) {
	      
	      $classLevelDAO->delete ($classLevel->classe, $classLevel->niveau);
	    }
	    
	    $students = $studentDAO->getElevesInClasse ($class->id);
      foreach ($students as $student) {

        // Récupération de l'affectation de l'élève à la classe
        if ($studentAssignment = $studentAssignmentDAO->getByStudentAndClass ($student->id, $class->id)) {
          
          $studentAssignmentDAO->delete ($studentAssignment->affect_id);
        }
        
        // Récupérations des admission de l'élève
    	  if ($admission = $studentAdmissionDAO->getByStudentAndSchool ($student->id, $school->numero)) {
    	  
    	    $studentAdmissionDAO->delete ($admission->admission_numero);  
    	  }
      }
	      
	    // Suppression de la classe
	    $classDAO->delete ($class->id);
	  }
 
	  // Suppression de l'école
	  $schoolDAO->delete ($school->numero);

		return _arRedirect (CopixUrl::get ('gestionautonome||showTree', array ('save' => 1)));
	}
	
	/**
	 * createClass
	 *
	 * Création d'une classe.
	 * 
	 */
	public function processCreateClass () {
	  
	  $ppo = new CopixPPO ();
	  
	  $ppo->parentId   = _request ('parentId', null);
	  $ppo->parentType = _request ('parentType', null);
	  
	  if (is_null ($ppo->parentId) || is_null ($ppo->parentType) || $ppo->parentType != 'BU_ECOLE') {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get('gestionautonome||showTree')));
	  }

	  $schoolDAO = _ioDAO ('kernel|kernel_bu_ecole');
	  if (!$school = $schoolDAO->get ($ppo->parentId)) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get('gestionautonome||showTree')));
	  }
	  
	  _currentUser()->assertCredential('module:school|'.$ppo->parentId.'|classroom|create@gestionautonome');

	// Récupération des niveaux de classe
	$classLevelDAO = _ioDAO ('kernel_bu_classe_niveau');     
	$criteres = _daoSp ()->orderBy ('id_cycle', 'id_n');
    $ppo->levels = $classLevelDAO->findBy ($criteres);
    $ppo->levelsSelected = array();
    
    // Récupération des types de classe
    $classTypeDAO = _ioDAO ('kernel_bu_classe_type');
    $types = $classTypeDAO->findAll ();
    
    $ppo->typeNames = array ();
    $ppo->typeIds   = array ();
    
    foreach ($types as $type) {
      
      $ppo->typeNames[] = $type->type_classe;
      $ppo->typeIds[]   = $type->id_tycla;
    }

    $cityDAO = _ioDAO ('kernel|kernel_bu_ville');
    $city = $cityDAO->get ($school->id_ville);
    
	  // Breadcrumbs
	  $breadcrumbs   = array();
	  $breadcrumbs[] = array('txt' => 'Gestion de la structure scolaire', 'url' => CopixUrl::get('gestionautonome||showTree'));
	  $breadcrumbs[] = array('txt' => $city->nom, 'url' => CopixUrl::get('gestionautonome||updateCity', array ('nodeId' => $city->id_vi)));
	  $breadcrumbs[] = array('txt' => $school->nom, 'url' => CopixUrl::get('gestionautonome||updateSchool', array ('nodeId' => $school->numero)));
	  $breadcrumbs[] = array('txt' => 'Création d\'une classe');

	  $ppo->breadcrumbs = Kernel::PetitPoucet($breadcrumbs,' &raquo; ');
		
		$ppo->TITLE_PAGE = CopixConfig::get('gestionautonome|moduleTitle');
		
		return _arPPO ($ppo, 'create_class.tpl');
	}
	
	/**
	 * validateClassCreation
	 *
	 * Validation du formulaire de création d'une classe.
	 * 
	 */
	public function processValidateClassCreation () {
	  
	  $ppo = new CopixPPO ();
	  
	  // Récupération des paramètres
	  $ppo->parentId   = _request ('id_parent', null);
	  $ppo->parentType = _request ('type_parent', null);
	  
	  if (is_null ($ppo->parentId) || is_null ($ppo->parentType) || $ppo->parentType != 'BU_ECOLE') {
    
  	  return CopixActionGroup::process ('generictools|Messages::getError',
     	  array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get('gestionautonome||showTree')));
  	}
    
  	$schoolDAO = _ioDAO ('kernel|kernel_bu_ecole');
  	if (!$school = $schoolDAO->get ($ppo->parentId)) {
    
  	  return CopixActionGroup::process ('generictools|Messages::getError',
     	  array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get('gestionautonome||showTree')));
  	}
    
  	_currentUser()->assertCredential('module:school|'.$ppo->parentId.'|classroom|create@gestionautonome');
    
    // DAO
    $schoolClassLevelDAO = _ioDAO ('kernel|kernel_bu_ecole_classe_niveau');
    
    $ppo->levels  = _request ('levels', null);
    $ppo->type    = _request ('type', null);
    
    // Récupération de l'année scolaire
    if (is_null($grade = _sessionGet('grade'))) {
      
      $grade = Kernel::getAnneeScolaireCourante ()->id_as;
    }
    
    // Insertion de la classe
    $ppo->class = _record ('kernel|kernel_bu_ecole_classe');

    $ppo->class->ecole        = $ppo->parentId;
    $ppo->class->nom          = trim (_request ('nom', null));
    $ppo->class->annee_scol   = $grade;
    $ppo->class->is_validee   = 1;
    $ppo->class->is_supprimee = 0;

    // Traitement des erreurs
    $ppo->errors = array ();
    
    if (!$ppo->class->nom) {
      
      $ppo->errors[] = 'Saisissez un nom';
    }
    
    if (!$ppo->levels) {
      
      $ppo->errors[] = 'Saisissez un niveau';
	  $ppo->levelsSelected = array();
    }
	else
		$ppo->levelsSelected = $ppo->levels;
    
    if (!empty ($ppo->errors)) {
      
      // Récupération des niveaux de classe
      $classLevelDAO = _ioDAO ('kernel_bu_classe_niveau');     
      $criteres = _daoSp ()->orderBy ('id_cycle', 'id_n');
      $ppo->levels = $classLevelDAO->findBy ($criteres);
      
      // Récupération des types de classe
      $classTypeDAO   = _ioDAO ('kernel_bu_classe_type');
      $types     = $classTypeDAO->findAll ();
      
      $ppo->typeNames = array ();
      $ppo->typeIds   = array ();
      
      foreach ($types as $type) {

        $ppo->typeNames[] = $type->type_classe;
        $ppo->typeIds[]   = $type->id_tycla;
      }
      
      $cityDAO = _ioDAO ('kernel|kernel_bu_ville');
      $city = $cityDAO->get ($school->id_ville);

  	  // Breadcrumbs
  	  $breadcrumbs   = array();
  	  $breadcrumbs[] = array('txt' => 'Gestion de la structure scolaire', 'url' => CopixUrl::get('gestionautonome||showTree'));
  	  $breadcrumbs[] = array('txt' => $city->nom, 'url' => CopixUrl::get('gestionautonome||updateCity', array ('nodeId' => $city->id_vi)));
  	  $breadcrumbs[] = array('txt' => $school->nom, 'url' => CopixUrl::get('gestionautonome||updateSchool', array ('nodeId' => $school->numero)));
  	  $breadcrumbs[] = array('txt' => 'Création d\'une classe');

  	  $ppo->breadcrumbs = Kernel::PetitPoucet($breadcrumbs,' &raquo; ');
  	  
  	  $ppo->TITLE_PAGE = CopixConfig::get('gestionautonome|moduleTitle');
  	  
      return _arPPO ($ppo, 'create_class.tpl');
    }
    
    $schoolClassDAO = _ioDAO ('kernel|kernel_bu_ecole_classe');
    $schoolClassDAO->insert ($ppo->class);
    
    // Insertion des affectations classe-niveau
    foreach ($ppo->levels as $level) {
      
      $newSchoolClassLevel = _record ('kernel|kernel_bu_ecole_classe_niveau');
      
      $newSchoolClassLevel->classe = $ppo->class->id;
      $newSchoolClassLevel->niveau = $level;
      $newSchoolClassLevel->type   = $ppo->type;

      $schoolClassLevelDAO->insert ($newSchoolClassLevel);
    }	
                     
    // Mise en session du noeud courant
		_sessionSet ('current', array('node_type' => 'BU_CLASSE', 'node_id' => $ppo->class->id));

		return _arRedirect (CopixUrl::get ('gestionautonome||showTree', array ('save' => 1)));
	}
	
	/**
	 * updateClass
	 *
	 * Edition d'une classe
	 * 
	 */
	public function processUpdateClass () {
	  
	  $ppo = new CopixPPO ();
	  
	  // Récupération des paramètres
	  $ppo->nodeId = _request ('nodeId', null);
	  if (is_null ($ppo->nodeId)) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get('gestionautonome||showTree')));
	  }
	  
	  $classroomDAO = _ioDAO ('kernel|kernel_bu_ecole_classe');
	  if (!$ppo->class = $classroomDAO->get ($ppo->nodeId)) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get('gestionautonome||showTree')));
	  }
	  
	  _currentUser()->assertCredential('module:school|'.$ppo->class->ecole.'|classroom|update@gestionautonome');
    
    // Mise en session du noeud courant
		_sessionSet ('current', array('node_type' => 'BU_CLASSE', 'node_id' => $ppo->class->id));
		
    // Récupération des niveaux de la classe
    $schoolClassLevelDAO = _ioDAO ('kernel|kernel_bu_ecole_classe_niveau');
    $schoolClassLevels = $schoolClassLevelDAO->getByClass ($ppo->nodeId);

    $ppo->levels = array ();
    $ppo->levelsSelected = array ();
    
    foreach ($schoolClassLevels as $ecn) {
      
      $ppo->levelsSelected[] = $ecn->niveau;
      $ppo->type = $ecn->type;
    }
    
    // Récupération des niveaux de classe
    $classLevelDAO = _ioDAO ('kernel_bu_classe_niveau');
	$criteres = _daoSp ()->orderBy ('id_cycle', 'id_n');
    $ppo->levels = $classLevelDAO->findBy ($criteres);
    
    
    // Récupération des types de classe pour le sélecteur
    $classTypeDAO = _ioDAO ('kernel_bu_classe_type');
    $types = $classTypeDAO->findAll ();
    
    $ppo->typeNames = array ();
    $ppo->typeIds   = array ();
    
	  foreach ($types as $type) {
      
      $ppo->typeNames[] = $type->type_classe;
      $ppo->typeIds[]   = $type->id_tycla;
    }
    
    $school = _ioDAO ('kernel|kernel_bu_ecole')->get ($ppo->class->ecole);
    $city   = _ioDAO ('kernel|kernel_bu_ville')->get ($school->id_ville);
    
	  // Breadcrumbs
	  $breadcrumbs   = array();
	  $breadcrumbs[] = array('txt' => 'Gestion de la structure scolaire', 'url' => CopixUrl::get('gestionautonome||showTree'));
	  $breadcrumbs[] = array('txt' => $city->nom, 'url' => CopixUrl::get('gestionautonome||updateCity', array ('nodeId' => $city->id_vi)));
	  $breadcrumbs[] = array('txt' => $school->nom, 'url' => CopixUrl::get('gestionautonome||updateSchool', array ('nodeId' => $school->numero)));
	  $breadcrumbs[] = array('txt' => $ppo->class->nom);

	  $ppo->breadcrumbs = Kernel::PetitPoucet($breadcrumbs,' &raquo; ');
		
		$ppo->TITLE_PAGE = CopixConfig::get('gestionautonome|moduleTitle');
		
		return _arPPO ($ppo, 'update_class.tpl');
	}
	
	/**
	 * validateUpdateClass
	 *
	 * Validation de l'edition d'une classe
	 * 
	 */
	public function processValidateClassUpdate () {
	  
	  $ppo = new CopixPPO ();
	  
	  // Récupération des paramètres
	  $ppo->nodeId = _request ('id_node', null);
	  if (is_null ($ppo->nodeId)) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get('gestionautonome||showTree')));
	  }
	  
	  $classroomDAO = _ioDAO ('kernel|kernel_bu_ecole_classe');
	  if (!$ppo->class = $classroomDAO->get ($ppo->nodeId)) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get('gestionautonome||showTree')));
	  }
	  
	  _currentUser()->assertCredential('module:school|'.$ppo->class->ecole.'|classroom|update@gestionautonome');
	  
	  // Nom pour fil d'ariane
	  $name = $ppo->class->nom;
	  
	  // Traitement des paramètres
    $ppo->class->nom    = trim (_request ('nom', null));

    $ppo->levels = _request ('niveaux', null);
    $ppo->type   = _request ('type', null);
    
    // Traitement des erreurs
    $ppo->errors = array ();
    
    if (!$ppo->class->nom) {
      
      $ppo->errors[] = 'Saisissez un nom';
    }
    
    if (!$ppo->levels) {
      
      $ppo->errors[] = 'Saisissez un niveau';
    }
    
    if (!empty ($ppo->errors)) {

      // Récupération des niveaux de la classe
      $schoolClassLevelDAO = _ioDAO ('kernel|kernel_bu_ecole_classe_niveau');
      $schoolClassLevels = $schoolClassLevelDAO->getByClass ($ppo->nodeId);

      $ppo->levels = array ();
      
      foreach ($schoolClassLevels as $ecn) {

        $ppo->levels[] = $ecn->niveau;
        $ppo->type = $ecn->type;
      }

      // Récupération des niveaux de classe
      $classLevelDAO = _ioDAO ('kernel_bu_classe_niveau');
  	  $levels = $classLevelDAO->findAll ();

      $ppo->levelNames = array ();
      $ppo->levelIds   = array ();
      
      foreach ($levels as $level) {

        $ppo->levelNames[] = $level->niveau_court;
        $ppo->levelIds[]   = $level->id_n;
      }

      // Récupération des types de classe pour le sélecteur
      $classTypeDAO = _ioDAO ('kernel_bu_classe_type');
      $types = $classTypeDAO->findAll ();

      $ppo->typeNames = array ();
      $ppo->typeIds   = array ();
      
  	  foreach ($types as $type) {

        $ppo->typeNames[] = $type->type_classe;
        $ppo->typeIds[]   = $type->id_tycla;
      }
      
      $school = _ioDAO ('kernel|kernel_bu_ecole')->get ($ppo->class->ecole);
      $city   = _ioDAO ('kernel|kernel_bu_ville')->get ($school->id_ville);

  	  // Breadcrumbs
  	  $breadcrumbs   = array();
  	  $breadcrumbs[] = array('txt' => 'Gestion de la structure scolaire', 'url' => CopixUrl::get('gestionautonome||showTree'));
  	  $breadcrumbs[] = array('txt' => $city->nom, 'url' => CopixUrl::get('gestionautonome||updateCity', array ('nodeId' => $city->id_vi)));
  	  $breadcrumbs[] = array('txt' => $school->nom, 'url' => CopixUrl::get('gestionautonome||updateSchool', array ('nodeId' => $school->numero)));
  	  $breadcrumbs[] = array('txt' => $name);

  	  $ppo->breadcrumbs = Kernel::PetitPoucet($breadcrumbs,' &raquo; ');
  	  
  	  $ppo->TITLE_PAGE = CopixConfig::get('gestionautonome|moduleTitle');
  	  
      return _arPPO ($ppo, 'update_class.tpl');
    }
    
    /**
     * TODO : refactoring de suppression
     */
    // Suppression des anciennes affectations
    $schoolClassLevelDAO = _ioDAO ('kernel|kernel_bu_ecole_classe_niveau');
    $oldSchoolClassLevels = $schoolClassLevelDAO->getByClass ($ppo->class->id);   
    foreach ($oldSchoolClassLevels as $oldSchoolClassLevel) {
      
      $schoolClassLevelDAO->delete ($oldSchoolClassLevel->classe, $oldSchoolClassLevel->niveau);
    }
    
    // Insertions des nouvelles
    foreach ($ppo->levels as $level) {
      
      $newSchoolClassLevel = _record ('kernel|kernel_bu_ecole_classe_niveau');
      
      $newSchoolClassLevel->classe = $ppo->class->id;
      $newSchoolClassLevel->niveau = $level;
      $newSchoolClassLevel->type   = $ppo->type;

      $schoolClassLevelDAO->insert ($newSchoolClassLevel);
    }
    
    $classroomDAO->update ($ppo->class);

		return _arRedirect (CopixUrl::get ('gestionautonome||showTree', array ('save' => 1)));
	}
	
	public function processDeleteClass () {
	  
	  $ppo = new CopixPPO ();
	  
	  // Récupération des paramètres
	  $nodeId   = _request ('nodeId', null);
	  if (is_null ($nodeId)) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get('gestionautonome||showTree')));
	  }
	  
	  $classroomDAO = _ioDAO ('kernel|kernel_bu_ecole_classe');
	  if (!$class = $classroomDAO->get ($nodeId)) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get('gestionautonome||showTree')));
	  }
	  
	  _currentUser()->assertCredential('module:school|'.$class->ecole.'|classroom|update@gestionautonome');
    
    // Mise en session du noeud parent
		_sessionSet ('current', array('node_type' => 'BU_ECOLE', 'node_id' => $class->ecole));
	  
	  /**
	   * TODO = refactoring des méthodes de suppression
	   */
	  $classLevelDAO        = _ioDAO ('kernel|kernel_bu_ecole_classe_niveau');
	  $studentDAO           = _ioDAO ('kernel|kernel_bu_ele');
	  $studentAssignmentDAO = _ioDAO ('kernel|kernel_bu_ele_affect');
	  $personEntityDAO      = _ioDAO ('kernel|kernel_bu_personnel_entite');

	  // Récupération de l'association classe-niveau
    $classLevels = $classLevelDAO->getByClass ($class->id);
    foreach ($classLevels as $classLevel) {
      
      $classLevelDAO->delete ($classLevel->classe, $classLevel->niveau);
    }
    
    // Récupération de l'affectation des élèves de cette classe
    $students = $studentDAO->getElevesInClasse ($nodeId);
    foreach ($students as $student) {

      $studentAssignment = $studentAssignmentDAO->getByStudentAndClass ($student->id, $nodeId);
      $studentAssignmentDAO->delete ($studentAssignment->affect_id);
    }
    
    // Suppression des affectations enseignants
    $personEntities = $personEntityDAO->getByIdReferenceAndTypeReference ($nodeId, 'CLASSE');
    foreach ($personEntities as $personEntity) {
      
      $personEntityDAO->delete ($personEntity->pers_entite_id_per, $personEntity->pers_entite_reference, $personEntity->pers_entite_type_ref);
    }
    
	  // Suppression de la classe
	  $classroomDAO->delete ($class->id);
		
		return _arRedirect (CopixUrl::get ('gestionautonome||showTree', array ('save' => 1)));
	}
	
	/**
	 * createPersonnel
	 *
	 * Création d'un personnel
	 * 
	 */
	public function processCreatePersonnel () {
	  
	  $ppo = new CopixPPO ();
	  
	  // Récupération des paramètres
	  $ppo->nodeId   = _request ('parentId', null);
	  $ppo->nodeType = _request ('parentType', null);
	  $ppo->role     = _request ('role', null);
	  
	  if (is_null ($ppo->nodeId) || is_null ($ppo->nodeType) || is_null ($ppo->role)) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get('gestionautonome||showTree')));
	  }
	  
	  switch ($ppo->nodeType) {
	    
	    case 'BU_GRVILLE':
	      _currentUser()->assertCredential('module:cities_group|'.$ppo->nodeId.'|cities_group_agent|create@gestionautonome');
	      break;
	    case 'BU_VILLE':
	      _currentUser()->assertCredential('module:city|'.$ppo->nodeId.'|city_agent|create@gestionautonome');
	      break;
	    case 'BU_ECOLE':
	      if ($ppo->type == 'USER_ADM') {
	        
	        _currentUser()->assertCredential('module:school|'.$ppo->nodeId.'|administration_staff|create@gestionautonome');
	        break;
	      }
	      else {
	        
	        _currentUser()->assertCredential('module:school|'.$ppo->nodeId.'|principal|create@gestionautonome');
	        break;
	      }
	    case 'BU_CLASSE':
	      _currentUser()->assertCredential('module:classroom|'.$ppo->nodeId.'|teacher|create@gestionautonome');
	      break;
	  }

		$ppo->genderNames = array ('Homme', 'Femme');
    $ppo->genderIds = array ('1', '2');
    
		$roleDAO = _ioDAO ('kernel_bu_personnel_role');
    $ppo->roleName = $roleDAO->get ($ppo->role)->nom_role;
    
    // Breadcrumbs
    $nodeInfos = Kernel::getNodeInfo ($ppo->nodeType, $ppo->nodeId);
    
    $breadcrumbs = Kernel::generateBreadcrumbs ($nodeInfos);
	  $breadcrumbs[] = array('txt' => 'Création d\'un '.$ppo->roleName);

	  $ppo->breadcrumbs = Kernel::PetitPoucet($breadcrumbs,' &raquo; ');
	  
	  $ppo->TITLE_PAGE = CopixConfig::get('gestionautonome|moduleTitle');
	  
		return _arPPO ($ppo, 'create_personnel.tpl');
	}
	
	public function processValidatePersonnelCreation () {
	  
	  $ppo = new CopixPPO (); 
	  
	  // Récupération des paramètres
	  $ppo->nodeId   = _request ('id_parent', null);
	  $ppo->nodeType = _request ('type_parent', null);
	  $ppo->role     = _request ('role', null);
	  
	  if (is_null ($ppo->nodeId) || is_null ($ppo->nodeType) || is_null ($ppo->role)) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get('gestionautonome||showTree')));
	  }
	  
	  $node_infos = Kernel::getNodeInfo ($ppo->nodeType, $ppo->nodeId, false);
	  
	  switch ($ppo->nodeType) {
	    
	    case 'BU_GRVILLE':
	      _currentUser()->assertCredential('module:cities_group|'.$ppo->nodeId.'|cities_group_agent|create@gestionautonome');
	      $id_ville = null;
	      break;
	    case 'BU_VILLE':
	      _currentUser()->assertCredential('module:city|'.$ppo->nodeId.'|city_agent|create@gestionautonome');
	      $id_ville = $ppo->nodeId;
	      break;
	    case 'BU_ECOLE':
	      if ($ppo->type == 'USER_ADM') {
	        
	        _currentUser()->assertCredential('module:school|'.$ppo->nodeId.'|administration_staff|create@gestionautonome');
	      }
	      else {
	        
	        _currentUser()->assertCredential('module:school|'.$ppo->nodeId.'|principal|create@gestionautonome');
	      }
	      $id_ville = $node_infos['ALL']->eco_id_ville;
	      break;
	    case 'BU_CLASSE':
	      _currentUser()->assertCredential('module:classroom|'.$ppo->nodeId.'|teacher|create@gestionautonome');
	      $id_ville = $node_infos['ALL']->eco_id_ville;
	      break;
	  }

		_classInclude ('kernel|Tools');
		    
    // Enregistrement kernel_bu_personnel
    $ppo->personnel = _record ('kernel|kernel_bu_personnel');
                                   
    $ppo->personnel->pers_nom         = trim (_request ('nom', null));
    $ppo->personnel->pers_prenom1     = trim (_request ('prenom1', null));    
    $ppo->personnel->pers_date_nais   = CopixDateTime::dateToyyyymmdd(_request ('date_nais', null));
    $ppo->personnel->pers_id_sexe     = _request ('gender', null);
    $ppo->personnel->pers_id_ville    = $id_ville;
    
    $ppo->personnel->pers_civilite = "";
    if($ppo->personnel->pers_id_sexe==1) $ppo->personnel->pers_civilite = "Monsieur";
    if($ppo->personnel->pers_id_sexe==2) $ppo->personnel->pers_civilite = "Madame";

    $ppo->login    = _request ('login', null);
    $ppo->password = _request ('password', null);
    
    // Traitement des erreurs
    $ppo->errors = array ();
    
    if (!$ppo->personnel->pers_nom) {
      
      $ppo->errors[] = 'Saisissez un nom';
    }
    if (!$ppo->personnel->pers_prenom1) {
      
      $ppo->errors[] = 'Saisissez un prénom';
    }
    if (!$ppo->login) {
      
      $ppo->errors[] = 'Saisissez un login';
    }
    if (!$ppo->password) {
      
      $ppo->errors[] = 'Saisissez un mot de passe';
    }
    elseif (!Kernel::checkPasswordFormat ($ppo->password)) {
      
      $ppo->errors[] = 'Format du mot de passe incorrect : au moins 6 caractères dont 1 chiffre';
    }
    if (!Kernel::isLoginAvailable ($ppo->login)) {
      
      $ppo->errors[] = 'Login non disponible';
    }
    if (is_null($ppo->personnel->pers_id_sexe)) {
      
      $ppo->errors[] = 'Saisissez un sexe';
    }
   
    if (!empty ($ppo->errors)) {
  	  
  	  $ppo->personnel->pers_date_nais = _request ('date_nais', null);
  	  
  	  $ppo->genderNames = array ('Homme', 'Femme');
      $ppo->genderIds = array ('1', '2');
      
      $roleDAO = _ioDAO ('kernel_bu_personnel_role');
      $ppo->roleName    = $roleDAO->get ($ppo->role)->nom_role;
      
  	  // Breadcrumbs
  	  $nodeInfos = Kernel::getNodeInfo ($ppo->nodeType, $ppo->nodeId);

      $breadcrumbs = Kernel::generateBreadcrumbs ($nodeInfos);
  	  $breadcrumbs[] = array('txt' => 'Création d\'un '.$ppo->roleName);

  	  $ppo->breadcrumbs = Kernel::PetitPoucet($breadcrumbs,' &raquo; ');
  	  
  	  $ppo->TITLE_PAGE = CopixConfig::get('gestionautonome|moduleTitle');
  	  
      return _arPPO ($ppo, 'create_personnel.tpl');
    }
    
    // DAO
    $personnelDAO       = _ioDAO ('kernel|kernel_bu_personnel');
    $personnelEntiteDAO = _ioDAO ('kernel|kernel_bu_personnel_entite');
    $dbUserDAO          = _ioDAO ('kernel|kernel_copixuser');
    $dbLinkDAO          = _ioDAO ('kernel|kernel_bu2user2');
    $roleDAO            = _ioDAO ('kernel_bu_personnel_role');
    
    $personnelDAO->insert ($ppo->personnel);
    
    // Récupération du type_user et du type_ref                             
    switch ($ppo->nodeType) {
			case 'BU_GRVILLE' :
        $type_ref  = 'GVILLE';
        $type_user = 'USER_VIL';
				break;
		  case 'BU_VILLE' :
        $type_ref  = 'VILLE';
        $type_user = 'USER_VIL';
  			break;
  		case 'BU_ECOLE' :
        $type_ref  = 'ECOLE';
        if ($ppo->role == '3') {
          $type_user = 'USER_ADM';
        }                         
        else {
          $type_user = 'USER_ENS';
        }
    		break;		
    	case 'BU_CLASSE' :
        $type_ref  = 'CLASSE';
        $type_user = 'USER_ENS';
      	break;
		}
		
    // Enregistrement dbuser
    $dbuser = _record ('kernel|kernel_copixuser');

    $dbuser->login_dbuser    = $ppo->login;
    $dbuser->password_dbuser = md5 ($ppo->password);
    $dbuser->email_dbuser    = '';
    $dbuser->enabled_dbuser  = 1;
    
    $dbUserDAO->insert ($dbuser);
    
    // Enregistrement kernel_link_bu2user
    $dbLink = _record ('kernel|kernel_bu2user2');

    $dbLink->user_id = $dbuser->id_dbuser;
    $dbLink->bu_type = $type_user;
    $dbLink->bu_id   = $ppo->personnel->pers_numero;
    
    $dbLinkDAO->insert ($dbLink);                                    
    
    // Enregistrement kernel_bu_personnel_entite
    $newPersonnelEntite = _record ('kernel|kernel_bu_personnel_entite');
    
		$newPersonnelEntite->pers_entite_id_per    = $ppo->personnel->pers_numero; 
		$newPersonnelEntite->pers_entite_reference = $ppo->nodeId;
		$newPersonnelEntite->pers_entite_type_ref  = $type_ref;
		$newPersonnelEntite->pers_entite_role      = $ppo->role;
		
		$personnelEntiteDAO->insert ($newPersonnelEntite);

		// Enseignant : enregistrement kernel_bu_personnel_entite ecole
		if ($type_ref == 'CLASSE' && $type_user == 'USER_ENS') {
		  
		  $newPersonnelEntite = _record ('kernel|kernel_bu_personnel_entite');

  		$newPersonnelEntite->pers_entite_id_per    = $ppo->personnel->pers_numero; 
  		$newPersonnelEntite->pers_entite_reference = $node_infos['ALL']->cla_ecole;
  		$newPersonnelEntite->pers_entite_type_ref  = 'ECOLE';
  		$newPersonnelEntite->pers_entite_role      = $ppo->role;

  		$personnelEntiteDAO->insert ($newPersonnelEntite);
		}

		$personnel = array(
		  'lastname'  => $ppo->personnel->pers_nom,
			'firstname' => $ppo->personnel->pers_prenom1,
			'login'     => $ppo->login,
			'password'  => $ppo->password,
			'bu_type'   => $type_user,
			'bu_id'     => $ppo->personnel->pers_numero,
			'type_nom'  => Kernel::Code2Name($type_user),
			'node_nom'  => Kernel::Code2Name($ppo->nodeType)." ".$node_infos['nom'],
			'gender'    => $ppo->personnel->pers_id_sexe
		);
		_sessionSet ('modules|gestionautonome|createAccount', array($ppo->login => $personnel));
		
		// Mise en session globale des mots de passe
		$passwordsList = _sessionGet ('modules|gestionautonome|passwordsList');
		if (!is_array ($passwordsList)) {
		  
		  $passwordsList = array();
		}
		$passwordsList[$type_user][$ppo->personnel->pers_numero] = $personnel;
		_sessionSet ('modules|gestionautonome|passwordsList', $passwordsList);
		
		// Mise en session du noeud courant
		_sessionSet ('current', array('node_type' => $ppo->nodeType, 'node_id' => $ppo->nodeId));

		return _arRedirect (CopixUrl::get ('gestionautonome||showAccountListing'));
	}
	
	/**
	 * showAccountListing
	 *
	 * Confirmation de création de compte - Export HTML / CSV des listings
	 * 
	 */
	public function processShowAccountListing () {
	                                                                        
	  $ppo = new CopixPPO (); 
	  
	  $ppo->isUpdated = _request ('isUpdated', 0);

	  // Récupération des informations des comptes créés
	  $ppo->sessionDatas = _sessionGet ('modules|gestionautonome|createAccount'); 
    
    $ppo->TITLE_PAGE = CopixConfig::get('gestionautonome|moduleTitle');
    
    // Récupération du format de sortie demandé
	  if( !_request ('format') || trim (_request ('format')) == '' ) {
	    
			$format = "default";
		} 
		else {
		  
			$format = _request('format');
		} 
		
		$ppo->firstElement = reset ($ppo->sessionDatas);
		
		// Sortie suivant le format demandé
		$tplResult = & new CopixTpl ();
		$tplResult->assign ('sessionDatas', $ppo->sessionDatas);

	  switch ($format) {
			case 'default':
				return _arPPO ($ppo, 'account_listing.tpl');
			case 'html':
			  $result = $tplResult->fetch ('account_listing_html.tpl');
			  return _arContent ($result, array ('filename'=>'Logins-'.date('YmdHi').'.html', 'content-disposition'=>'inline', 'content-type'=>CopixMIMETypes::getFromExtension ('.html')));
			  break;
			case 'csv':
			  $result = $tplResult->fetch ('account_listing_csv.tpl');
			  
			  header('Pragma: public');
        header('Date: '.gmdate('D, d M Y H:i:s', time()).' GMT');
        header('Last-Modified: '.gmdate('D, d M Y H:i:s', time()).' GMT');
        header('Expires: '.gmdate('D, d M Y H:i:s',time()).' GMT');
        header('Cache-Control: must-revalidate, pre-check=0, post-check=0, max-age=0');
        header('Content-Type: application/force-download');
  			header('Content-Type: application/octet-stream', true);
  			header('Content-Type: application/download', true);
  			header('Content-Type: text/csv', true);
        header('Content-Disposition: attachment; filename="Comptes-'.date('YmdHi').'.csv"');
        header('Content-Transfer-Encoding: binary');
        header('Content-Length: '.strlen($result));

        echo $result;
        return _arNone();
		}
	}
	
	public function processUpdatePersonnel () {
    
	  $ppo = new CopixPPO ();
	  
	  // Récupération des paramètres
	  $ppo->nodeId   = _request ('nodeId', null);
	  $ppo->nodeType = _request ('nodeType', null);
	  $personnelId   = _request ('personnelId', null);
	  $ppo->type     = _request ('type', null);

    if (is_null ($ppo->nodeId) || is_null ($ppo->nodeType) || is_null ($personnelId) || is_null ($ppo->type)) {

	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get('gestionautonome||showTree')));
	  }

	  $personnelDAO = _ioDAO ('kernel|kernel_bu_personnel');
	  if (!$ppo->personnel = $personnelDAO->getByIdAndType ($personnelId, $ppo->type)) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get('gestionautonome||showTree')));
	  }
	  
	  switch ($ppo->nodeType) {
	    
	    case 'BU_GRVILLE':
	      _currentUser()->assertCredential('module:cities_group|'.$ppo->nodeId.'|cities_group_agent|update@gestionautonome');
	      break;
	    case 'BU_VILLE':
	      _currentUser()->assertCredential('module:city|'.$ppo->nodeId.'|city_agent|update@gestionautonome');
	      break;
	    case 'BU_ECOLE':
	      if ($ppo->type == 'USER_ADM') {
	        
	        _currentUser()->assertCredential('module:school|'.$ppo->nodeId.'|administration_staff|update@gestionautonome');
	        break;
	      }
	      else {
	        
	        _currentUser()->assertCredential('module:school|'.$ppo->nodeId.'|principal|update@gestionautonome');
	        break;
	      }
	    case 'BU_CLASSE':
	      _currentUser()->assertCredential('module:classroom|'.$ppo->nodeId.'|teacher|update@gestionautonome');
	      break;
	  }

    $ppo->genderNames = array ('Homme', 'Femme');
    $ppo->genderIds = array ('1', '2');
    
    $ppo->personnel->pers_date_nais = CopixDateTime::yyyymmddToDate ($ppo->personnel->pers_date_nais);

    // Récupération du compte dbuser
	  $dbuserDAO = _ioDAO ('kernel|kernel_copixuser');
	  $ppo->account = $dbuserDAO->getUserByBuIdAndBuType ($personnelId, $ppo->type);
    
    // Breadcrumbs
    $nodeInfos = Kernel::getNodeInfo ($ppo->nodeType, $ppo->nodeId);
    
    $breadcrumbs = Kernel::generateBreadcrumbs ($nodeInfos);
    $breadcrumbs[] = array('txt' => $ppo->personnel->pers_nom.' '.$ppo->personnel->pers_prenom1);
    
	  $ppo->breadcrumbs = Kernel::PetitPoucet($breadcrumbs,' &raquo; ');
	  
	  $ppo->TITLE_PAGE = CopixConfig::get('gestionautonome|moduleTitle');
	  
		return _arPPO ($ppo, 'update_personnel.tpl');
	}
	
	public function processValidatePersonnelUpdate () {
	  
	  $ppo = new CopixPPO ();
	  
	  // Récupération des paramètres
	  $ppo->nodeId   = _request ('id_node', null);
	  $ppo->nodeType = _request ('type_node', null);
	  $personnelId   = _request ('id_personnel', null);
	  $ppo->type     = _request ('type', null);
	  
	  if (is_null ($ppo->nodeId) || is_null ($ppo->nodeType) || is_null ($personnelId) || is_null ($ppo->type)) {

	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get('gestionautonome||showTree')));
	  }

	  $personnelDAO = _ioDAO ('kernel|kernel_bu_personnel');
	  if (!$ppo->personnel = $personnelDAO->getByIdAndType ($personnelId, $ppo->type)) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get('gestionautonome||showTree')));
	  }
	  
	  switch ($ppo->nodeType) {
	    
	    case 'BU_GRVILLE':
	      _currentUser()->assertCredential('module:cities_group|'.$ppo->nodeId.'|cities_group_agent|update@gestionautonome');
	      break;
	    case 'BU_VILLE':
	      _currentUser()->assertCredential('module:city|'.$ppo->nodeId.'|city_agent|update@gestionautonome');
	      break;
	    case 'BU_ECOLE':
	      if ($ppo->type == 'USER_ADM') {
	        
	        _currentUser()->assertCredential('module:school|'.$ppo->nodeId.'|administration_staff|update@gestionautonome');
	        break;
	      }
	      else {
	        
	        _currentUser()->assertCredential('module:school|'.$ppo->nodeId.'|principal|update@gestionautonome');
	        break;
	      }
	    case 'BU_CLASSE':
	      _currentUser()->assertCredential('module:classroom|'.$ppo->nodeId.'|teacher|update@gestionautonome');
	      break;
	  }

    $ppo->personnel->pers_nom       = trim (_request ('nom', null));
    $ppo->personnel->pers_prenom1   = trim (_request ('prenom1', null));
    $ppo->personnel->pers_date_nais = _request ('date_nais', null);
    $ppo->personnel->pers_id_sexe   = _request ('gender', null);
    $ppo->personnel->pers_date_nais = CopixDateTime::dateToyyyymmdd (_request ('date_nais', null)); 
    $newPassword                    = _request ('password', null);
    
    $dbuserDAO = _ioDAO ('kernel|kernel_copixuser');
    $ppo->account = $dbuserDAO->getUserByBuIdAndBuType ($personnelId, $ppo->type);

    // Traitement des erreurs
    $ppo->errors = array ();
    
    if (!$ppo->personnel->pers_nom) {
      
      $ppo->errors[] = 'Saisissez un nom';
    }
    if (!$ppo->personnel->pers_prenom1) {
      
      $ppo->errors[] = 'Saisissez un prénom';
    }
    if (!is_null ($newPassword) && !kernel::checkPasswordFormat ($newPassword)) {
      
      $ppo->errors['password_invalid'] = 'Format du mot de passe incorrect : au moins 6 caractères dont 1 chiffre';
    }
    
    if (!empty ($ppo->errors)) {
      
      $ppo->genderNames = array ('Homme', 'Femme');
      $ppo->genderIds = array ('1', '2');
      
      $ppo->personnel->pers_date_nais = _request ('date_nais', null);
      
      // Breadcrumbs
      $nodeInfos = Kernel::getNodeInfo ($ppo->nodeType, $ppo->nodeId);

      $breadcrumbs = Kernel::generateBreadcrumbs ($nodeInfos);
      $breadcrumbs[] = array('txt' => $ppo->personnel->pers_nom.' '.$ppo->personnel->pers_prenom1);

  	  $ppo->breadcrumbs = Kernel::PetitPoucet($breadcrumbs,' &raquo; ');
  	  
  	  $ppo->TITLE_PAGE = CopixConfig::get('gestionautonome|moduleTitle');
  	  
      return _arPPO ($ppo, 'update_personnel.tpl');
    }

    $personnelDAO->update ($ppo->personnel);
    
    // Mise en session du noeud courant
		_sessionSet ('current', array('node_type' => $ppo->nodeType, 'node_id' => $ppo->nodeId));
		
		// Liste des mots de passe globale
		$passwordsList = _sessionGet ('modules|gestionautonome|passwordsList');
		if (!is_array ($passwordsList)) {

		  $passwordsList = array();
		}
		
		// Nouveau mot de passe ?
    $newPassword = _request ('password', null);
    if (!is_null ($newPassword)) {
      
      $ppo->account->password_dbuser = md5 ($newPassword);
      $dbuserDAO->update ($ppo->account);
    }

    $personnel = array(
		  'lastname'  => $ppo->personnel->pers_nom,
			'firstname' => $ppo->personnel->pers_prenom1,
			'login'     => $ppo->account->login_dbuser,
			'password'  => !is_null ($newPassword) ? $newPassword : (isset ($passwordsList[$ppo->type][$ppo->personnel->pers_numero]) ? $passwordsList[$ppo->type][$ppo->personnel->pers_numero]['password'] : '******'),
			'bu_type'   => $ppo->type,
			'bu_id'     => $ppo->personnel->pers_numero,
			'type_nom'  => Kernel::Code2Name($ppo->type),
			'node_nom'  => Kernel::Code2Name($ppo->nodeType).' '.$nodeInfos['nom'],
			'gender'    => $ppo->personnel->pers_id_sexe
		);
		
    // Mise en session
    _sessionSet ('modules|gestionautonome|createAccount', array ($ppo->account->login_dbuser => $personnel));
    
    // Mise en session globale des mots de passe
		$passwordsList[$ppo->type][$ppo->personnel->pers_numero] = $personnel;
    _sessionSet ('modules|gestionautonome|passwordsList', $passwordsList);
    
    return _arRedirect (CopixUrl::get ('gestionautonome||showAccountListing', array('isUpdated' => 1)));
	}
	
	public function processRemovePersonnel () {
	  
	  $ppo->nodeId   = _request ('nodeId', null);
	  $ppo->nodeType = _request ('nodeType', null);
	  $personId      = _request ('personnelId', null);
	  
	  if (is_null ($ppo->nodeId) || is_null ($ppo->nodeType) || is_null ($personId)) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get('gestionautonome||showTree')));
	  }
	  
	  switch ($ppo->nodeType) {
			case 'BU_GRVILLE' :
        $type_ref  = 'GVILLE';
				break;
		  case 'BU_VILLE' :
        $type_ref  = 'VILLE';
  			break;
  		case 'BU_ECOLE' :
        $type_ref  = 'ECOLE';
    		break;		
    	case 'BU_CLASSE' :
        $type_ref  = 'CLASSE';
      	break;
		}
	  
	  $personEntityDAO = _ioDAO ('kernel|kernel_bu_personnel_entite');
    if ($personEntity = $personEntityDAO->get ($personId, $ppo->nodeId, $type_ref)) {
      
      // Si on se trouve sur une ecole et que la personne a une affectation dans une des classes
      if ($type_ref == 'ECOLE' && $personEntityDAO->hasTeacherRoleInSchool ($personId, $ppo->nodeId, true)) {
        
        // Mise à jour du rôle : directeur -> enseignant
        $personEntityDAO->updateRole ($personId, $ppo->nodeId, $type_ref, '1');
      }
      else {
        
        // Suppression de l'affectation
        $personEntityDAO->delete ($personId, $ppo->nodeId, $type_ref);
      }
    }
    
    // Mise en session du noeud courant
		_sessionSet ('current', array('node_type' => $ppo->nodeType, 'node_id' => $ppo->nodeId));
    
    if ($ppo->nodeType == 'BU_CLASSE') {
      
      return _arRedirect (CopixUrl::get ('gestionautonome||showTree', array ('tab' => 1, 'save' => 1)));
    }

	  return _arRedirect (CopixUrl::get ('gestionautonome||showTree', array ('save' => 1)));
	}
	
	public function processDeletePersonnel () {
	  
	  $ppo->nodeId   = _request ('nodeId', null);
	  $ppo->nodeType = _request ('nodeType', null);
	  $personnelId   = _request ('personnelId', null);
	  $type          = _request ('type', null);
	  
	  if (is_null ($ppo->nodeId) || is_null ($ppo->nodeType) || is_null ($personnelId) || is_null ($type)) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get('gestionautonome||showTree')));
	  }
	  
	  // Suppression du personnel
	  $personnelDAO = _ioDAO ('kernel|kernel_bu_personnel');
	  $personnelDAO->delete ($personnelId);

    // Suppression des affectations
	  $personnelLinkDAO = _ioDAO ('kernel|kernel_bu_personnel_entite');
	  $links = $personnelLinkDAO->getById ($personnelId);
	  
	  foreach ($links as $link) {                 
	    
	    $personnelLinkDAO->delete ($link->id_per);
	  }
	  
	  // Suppression du compte
	  $copixUserDAO = _ioDAO ('kernel|kernel_copixuser');
	  if ($dbUser = $copixUserDAO->getUserByBuIdAndBuType ($personnelId, $type)) {
	    
	    Kernel::disableCopixUser($dbUser->id_dbuser);
	  }
    
    // Suppression de la liste globale de mots de passe si présent
    $passwordsList = _sessionGet ('modules|gestionautonome|passwordsList');
		if (!is_array ($passwordsList)) {

		  $passwordsList = array();
		}
		if (isset($passwordsList[$type][$personnelId])) {
		  
		  unset($passwordsList[$type][$personnelId]);
		  _sessionSet ('modules|gestionautonome|passwordsList', $passwordsList);
		}
		
	  // Mise en session du noeud courant
		_sessionSet ('current', array('node_type' => $ppo->nodeType, 'node_id' => $ppo->nodeId));
	  
	  if ($ppo->nodeType == 'BU_CLASSE') {
      
      return _arRedirect (CopixUrl::get ('gestionautonome||showTree', array ('tab' => 1, 'save' => 1)));
    }
    
	  return _arRedirect (CopixUrl::get ('gestionautonome||showTree', array ('save' => 1)));
	}
	
	/**
	 * Création des étudiants
	 */
	public function processCreateStudent () {
	  
	  $ppo = new CopixPPO ();
	  
	  $ppo->nodeId   = _request ('parentId', null);
	  $ppo->nodeType = _request ('parentType', null);
	  
	  // Récupération des paramètres
	  if (is_null ($ppo->nodeId) || is_null ($ppo->nodeType) || $ppo->nodeType != 'BU_CLASSE') {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get('gestionautonome||showTree')));
	  }
	  
	  $classroomDAO = _ioDAO ('kernel|kernel_bu_ecole_classe');
	  if (!$classroom = $classroomDAO->get ($ppo->nodeId)) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get('gestionautonome||showTree')));
	  }
	  
	  _currentUser()->assertCredential('module:classroom|'.$ppo->nodeId.'|student|create@gestionautonome');

	  // Remise à zéro des sessions tmp
	  _sessionSet ('modules|gestionautonome|tmpAccount', array ()); // Pour responsable
	  _sessionSet ('modules|gestionautonome|createAccount', array ());
    
    // Récupération des niveaux de la classe
    $classSchoolLevelDAO = _ioDAO ('kernel|kernel_bu_ecole_classe_niveau');
    $classSchoolLevels   = $classSchoolLevelDAO->getByClass ($ppo->nodeId);
    
    $ppo->levelNames = array ();
    $ppo->levelIds   = array ();
    
    $classLevelDAO = _ioDAO ('kernel_bu_classe_niveau');
    
    foreach ($classSchoolLevels as $classSchoolLevel) {
      
      $level             = $classLevelDAO->get ($classSchoolLevel->niveau);
      $ppo->levelNames[] = $level->niveau_court;
      $ppo->levelIds[]   = $level->id_n;
    }
    
    $ppo->genderNames = array ('Garçon', 'Fille');
    $ppo->genderIds = array ('1', '2');
    
    // Compteur responsable
    $ppo->cpt = 1;
    
    // Breadcrumbs
	  $nodeInfos = Kernel::getNodeInfo ($ppo->nodeType, $ppo->nodeId);
	  
	  $breadcrumbs   = Kernel::generateBreadcrumbs ($nodeInfos);
	  $breadcrumbs[] = array('txt' => 'Création d\'un élève');
	  
	  $ppo->breadcrumbs = Kernel::PetitPoucet($breadcrumbs,' &raquo; ');
	  
	  $ppo->TITLE_PAGE = CopixConfig::get('gestionautonome|moduleTitle');
	  
		return _arPPO ($ppo, 'create_student.tpl');
	}
	
	public function processValidateStudentCreation () {
	  
	  $ppo = new CopixPPO ();     
	  
	  // Récupération des paramètres
	  $ppo->nodeId   = _request ('id_parent', null);
	  $ppo->nodeType = _request ('type_parent', null);
	  $ppo->level    = _request ('level', null);
	  
	  // Récupération des paramètres
	  if (is_null ($ppo->nodeId) || is_null ($ppo->nodeType) || $ppo->nodeType != 'BU_CLASSE') {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get('gestionautonome||showTree')));
	  }
	  
	  $classroomDAO = _ioDAO ('kernel|kernel_bu_ecole_classe');
	  if (!$classroom = $classroomDAO->get ($ppo->nodeId)) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get('gestionautonome||showTree')));
	  }
	  
	  _currentUser()->assertCredential('module:classroom|'.$ppo->nodeId.'|student|create@gestionautonome');

		_classInclude ('kernel|Tools');
        
    // Création de l'élève
    $ppo->student = _record ('kernel_bu_eleve');
                            
    $ppo->student->numero          = '';                        
    $ppo->student->nom             = trim (_request ('student_lastname', null));
    $ppo->student->prenom1         = trim (_request ('student_firstname', null));
    $ppo->student->id_sexe         = _request ('student_gender', null);
    $ppo->student->date_nais       = CopixDateTime::dateToyyyymmdd(_request ('student_birthdate', null));
    $ppo->student->flag            = 0;
    $ppo->student->ele_last_update = CopixDateTime::timestampToYYYYMMDDHHIISS (time ());
    
    $ppo->login    = _request ('student_login', null);
    $ppo->password = _request ('student_password', null); 
    
    $ppo->resp_on = _request ('person_in_charge', null);
    
    // Traitement des erreurs
    $ppo->errors = array ();
    
    if (!$ppo->student->nom) {
      
      $ppo->errors[] = 'Saisissez un nom';
    }
    if (!$ppo->student->prenom1) {
      
      $ppo->errors[] = 'Saisissez un prénom';
    }
    if (is_null($ppo->student->id_sexe)) {
      
      $ppo->errors[] = 'Saisissez un sexe';
    }
    if (!$ppo->login) {
      
      $ppo->errors[] = 'Saisissez un login';
    }
    elseif (!Kernel::isLoginAvailable ($ppo->login)) {
      
      $ppo->errors[] = 'Login non disponible';
    }
    if (!$ppo->password) {
      
      $ppo->errors[] = 'Saisissez un mot de passe';
    }
    elseif (!Kernel::checkPasswordFormat ($ppo->password)) {
      
      $ppo->errors[] = 'Format du mot de passe incorrect : au moins 6 caractères dont 1 chiffre';
    }
    
    if (!empty ($ppo->errors)) {
      
      // Récupération des niveaux de la classe
      $classSchoolLevelDAO = _ioDAO ('kernel|kernel_bu_ecole_classe_niveau');
      $classLevelDAO       = _ioDAO ('kernel_bu_classe_niveau');
      
      $classSchoolLevels   = $classSchoolLevelDAO->getByClass ($ppo->nodeId);

      $ppo->levelNames = array ();
      $ppo->levelIds   = array ();
      
      foreach ($classSchoolLevels as $classSchoolLevel) {

        $level              = $classLevelDAO->get ($classSchoolLevel->niveau);
        $ppo->levelNames[]  = $level->niveau_court;
        $ppo->levelIds[]    = $level->id_n;
      }
      
      $ppo->genderNames = array ('Garçon', 'Fille');
      $ppo->genderIds = array ('1', '2');
  	   
      $ppo->student->date_nais = _request('student_birthdate', null);
      
      // Breadcrumbs
      $nodeInfos = Kernel::getNodeInfo ($ppo->nodeType, $ppo->nodeId);
      
  	  $breadcrumbs      = Kernel::generateBreadcrumbs ($nodeInfos);
  	  $breadcrumbs[]    = array('txt' => 'Création d\'un élève');
  	  
  	  $ppo->breadcrumbs = Kernel::PetitPoucet($breadcrumbs,' &raquo; ');
  	  
  	  $ppo->TITLE_PAGE = CopixConfig::get('gestionautonome|moduleTitle');
  	  
      return _arPPO ($ppo, 'create_student.tpl');
    }

    $studentDAO = _ioDAO ('kernel_bu_eleve');
    $studentDAO->insert ($ppo->student);
    
    // Création du compte dbuser
    $dbuserDAO = _ioDAO ('kernel|kernel_copixuser'); 
    $dbuser = _record ('kernel|kernel_copixuser');

    $dbuser->login_dbuser    = $ppo->login;
    $dbuser->password_dbuser = md5 ($ppo->password);
    $dbuser->email_dbuser    = '';
    $dbuser->enabled_dbuser  = 1;
    
    $dbuserDAO->insert ($dbuser);
    
    // Création du link bu2user
    $dbLinkDAO = _ioDAO ('kernel_link_bu2user');
    $dbLink = _record ('kernel_link_bu2user');

    $dbLink->user_id = $dbuser->id_dbuser;
    $dbLink->bu_type = 'USER_ELE';
    $dbLink->bu_id   = $ppo->student->idEleve;
    
    $dbLinkDAO->insert ($dbLink);
                 
    // Récupération des données nécessaires à l'ajout des enregistrements inscription / adhésion / admission
    $class = $classroomDAO->get ($ppo->nodeId);
    
    $schoolClassLevelDAO = _ioDAO ('kernel|kernel_bu_ecole_classe_niveau');
    $schoolClassLevels = $schoolClassLevelDAO->getByClass ($class->id);
    $classType = $schoolClassLevels[0]->type;
    $schoolId = $class->ecole;
    
    // Récupération de l'année scolaire
    if (is_null($grade = _sessionGet('grade'))) {
      
      $grade = Kernel::getAnneeScolaireCourante ()->id_as;
    }
    
    // Inscription de l'élève dans l'école
    $studentRegistrationDAO = _ioDAO ('kernel|kernel_bu_eleve_inscription');
    $studentRegistration = _record ('kernel|kernel_bu_eleve_inscription');
    
    $studentRegistration->eleve                   = $ppo->student->idEleve;
    $studentRegistration->annee_scol              = $grade;
    $studentRegistration->date_preinscript        = CopixDateTime::timestampToYYYYMMDD (time ());
    $studentRegistration->date_effet_preinscript  = CopixDateTime::timestampToYYYYMMDD (time ());
    $studentRegistration->date_inscript           = CopixDateTime::timestampToYYYYMMDD (time ());
    $studentRegistration->date_effet_inscript     = CopixDateTime::timestampToYYYYMMDD (time ());
    $studentRegistration->etablissement           = $schoolId;
    $studentRegistration->etablissement_refus     = 0;
    $studentRegistration->id_niveau               = $ppo->level;
    $studentRegistration->id_typ_cla              = $classType;
    $studentRegistration->vaccins_aj              = 0;
    $studentRegistration->attente                 = 0;
    $studentRegistration->derogation_dem          = 0; 
    $studentRegistration->temporaire              = 0;
    $studentRegistration->current_inscr           = 1;

    $studentRegistrationDAO->insert ($studentRegistration);

    // Admission de l'élève dans l'école
    $studentAdmissionDAO = _ioDAO ('kernel_bu_eleve_admission');
    $studentAdmission = _record ('kernel_bu_eleve_admission');
    
    $studentAdmission->eleve          = $ppo->student->idEleve;
    $studentAdmission->etablissement  = $schoolId;
    $studentAdmission->annee_scol     = $grade;
    $studentAdmission->id_niveau      = $ppo->level;
    $studentAdmission->etat_eleve     = 1;
    $studentAdmission->date           = CopixDateTime::timestampToYYYYMMDD (time ());
    $studentAdmission->date_effet     = CopixDateTime::timestampToYYYYMMDD (time ());
    $studentAdmission->code_radiation = '';
    $studentAdmission->previsionnel   = '';
    
    $studentAdmissionDAO->insert ($studentAdmission);
    
    // Affectation de l'élève dans les classes  
    $studentAssignmentDAO = _ioDAO ('kernel_bu_eleve_affectation');
    $studentAssignment = _record ('kernel_bu_eleve_affectation');
    
    $studentAssignment->eleve           = $ppo->student->idEleve;
    $studentAssignment->annee_scol      = $grade;
    $studentAssignment->classe          = $ppo->nodeId;
    $studentAssignment->niveau          = $ppo->level;
    $studentAssignment->dateDebut       = CopixDateTime::timestampToYYYYMMDD (time ());
    $studentAssignment->current         = 1;
    $studentAssignment->previsionnel_cl = 0;

    $studentAssignmentDAO->insert ($studentAssignment);
    
    $type_user = 'USER_ELE';
    
    $node_infos = Kernel::getNodeInfo ($ppo->nodeType, $ppo->nodeId, false);
		
		// Mise en session des comptes créés : 0 -> élève / x -> responsables
		$session = _sessionGet ('modules|gestionautonome|createAccount');
		if (!$session || !is_array ($session)) {
		  
		  $session = array();
		}
		
		$session[$ppo->login] = array(
		  'lastname'  => $ppo->student->nom,
			'firstname' => $ppo->student->prenom1,
			'login'     => $ppo->login,
			'password'  => $ppo->password,
			'bu_type'   => $type_user,
			'bu_id'     => $ppo->student->idEleve,
			'type_nom'  => Kernel::Code2Name($type_user),
			'node_nom'  => Kernel::Code2Name($ppo->nodeType)." ".$node_infos['nom'],
			'gender'    => $ppo->student->id_sexe
		);
		
		// Mise en session globale des mots de passe
		$passwordsList = _sessionGet ('modules|gestionautonome|passwordsList');
		if (!is_array ($passwordsList)) {

		  $passwordsList = array();
		}
		$passwordsList[$type_user][$ppo->student->idEleve] = reset($session);
                                                                    
		// Récupérations des infos temporaires en session et ajout aux sessions => Ajouter via ajax
		$tmpSession = _sessionGet ('modules|gestionautonome|tmpAccount');                                                                
		if ($ppo->resp_on && !is_null ($tmpSession) && is_array ($tmpSession)) {
		  
		  $personDAO     = _ioDAO ('kernel_bu_responsable');
      $personLinkDAO = _ioDAO ('kernel|kernel_bu_res2ele');
       
  		foreach ($tmpSession as $personSession) {
        
        // Si le responsable existe déjà
        if (isset($personSession['res_numero'])) {
          
          // Création de l'association responsable / élève
          if (!$personLinkDAO->getByPersonAndStudent ($personSession['res_numero'], $ppo->student->idEleve)) {

            $newPersonLink = _record ('kernel|kernel_bu_res2ele');

        		$newPersonLink->res2ele_id_beneficiaire   = $ppo->student->idEleve; 
        		$newPersonLink->res2ele_type_beneficiaire = 'eleve';
        		$newPersonLink->res2ele_id_responsable    = $personSession['res_numero'];
        		$newPersonLink->res2ele_type_responsable  = 'responsable';
        		$newPersonLink->res2ele_auth_parentale    = '0';
        		$newPersonLink->res2ele_id_par            = $personSession['res_id_sexe'] == 1 ? '2' : '1';

        		$personLinkDAO->insert ($newPersonLink);
          }
          
          // Mise en session du responsable
          $session[$personSession['login']] = array(
      		  'lastname'  => $personSession['lastname'],
      			'firstname' => $personSession['firstname'],
      			'login'     => $personSession['login'],
      			'password'  => isset ($passwordsList['USER_RES']['res_numero']) ? $passwordsList['USER_RES']['res_numero']['password'] : '******',
      			'bu_type'   => 'USER_RES',
      			'bu_id'     => $ppo->student->idEleve,
      			'type_nom'  => Kernel::Code2Name('USER_RES'),
      			'node_nom'  => Kernel::Code2Name($ppo->nodeType).' '.$node_infos['nom'],
      			'gender'    => $personSession['gender']
      		);
        }
        // Sinon nouveau responsable : création seulement si la création du dbuser est possible
        elseif (Kernel::isLoginAvailable ($personSession['login'])) {
          
          // Création du responsable
          $ppo->person = _record ('kernel_bu_responsable');
      
          $ppo->person->nom     = trim ($personSession['lastname']);
          $ppo->person->prenom1 = trim ($personSession['firstname']);
          $ppo->person->id_sexe = $personSession['id_sexe'];//  $personSession['res_id_sexe'] == 1 ? '2' : '1';
          $ppo->res_id_par      = $personSession['id_par'];
      
    	    $personDAO->insert ($ppo->person);
      
          // Création de l'association personne->rôle
          $newPersonLink = _record ('kernel|kernel_bu_res2ele');
      
      		$newPersonLink->res2ele_id_beneficiaire   = $ppo->student->idEleve; 
      		$newPersonLink->res2ele_type_beneficiaire = 'eleve';
      		$newPersonLink->res2ele_id_responsable    = $ppo->person->numero;
      		$newPersonLink->res2ele_type_responsable  = 'responsable';
      		$newPersonLink->res2ele_auth_parentale    = '0';
      		$newPersonLink->res2ele_id_par            = $personSession['id_par'];
      
      		$personLinkDAO->insert ($newPersonLink);
      
      		// Création du compte dbuser
          $dbuser = _record ('kernel|kernel_copixuser');
      
          $dbuser->login_dbuser    = $personSession['login'];
          $dbuser->password_dbuser = md5 ($personSession['password']);
          $dbuser->email_dbuser    = '';
          $dbuser->enabled_dbuser  = 1;
      
          $dbuserDAO->insert ($dbuser);
      
          // Création du link bu2user
          $dbLink = _record ('kernel_link_bu2user');
      
          $dbLink->user_id = $dbuser->id_dbuser;
          $dbLink->bu_type = 'USER_RES';
          $dbLink->bu_id   = $ppo->person->numero;
      
          $dbLinkDAO->insert ($dbLink);
          
          // Mise en session du responsable
          $responsableAr = array(
      		  'lastname'  => $personSession['lastname'],
      			'firstname' => $personSession['firstname'],
      			'login'     => $personSession['login'],
      			'password'  => $personSession['password'],
      			'bu_type'   => 'USER_RES',
      			'bu_id'     => $ppo->student->idEleve,
      			'type_nom'  => Kernel::Code2Name('USER_RES'),
      			'node_nom'  => Kernel::Code2Name($ppo->nodeType)." ".$node_infos['nom'],
      			'gender'    => $personSession['gender']
      		);
      		$session[$personSession['login']] = $responsableAr;
      		
      		$passwordsList['USER_RES'][$ppo->person->numero] = $responsableAr;
        }
      }
		}
		
		_sessionSet ('modules|gestionautonome|passwordsList', $passwordsList);
		
		// Mise en session de l'élève et des responsables
		_sessionSet ('modules|gestionautonome|createAccount', $session);
		
		// Remise à zéro des sessions tmp
		_sessionSet ('modules|gestionautonome|tmpAccount', array ());
		
		// Mise en session du noeud courant
		_sessionSet ('current', array('node_type' => $ppo->nodeType, 'node_id' => $ppo->nodeId));
		
		return _arRedirect (CopixUrl::get ('gestionautonome||showAccountListing'));
	}
	
	public function processUpdateStudent () {
	  
	  $ppo = new CopixPPO ();
	  
	  // Récupération des paramètres
	  $ppo->nodeId   = _request ('nodeId', null);
	  $ppo->nodeType = _request ('nodeType', null);
	  $studentId     = _request ('studentId', null);
	  $ppo->personId = _request ('personId', false);
	  $ppo->save = _request ('save');
	  
	  if (is_null ($ppo->nodeId) || is_null ($ppo->nodeType) || is_null ($studentId)) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get('gestionautonome||showTree')));
	  }
	  
	  $studentDAO = _ioDAO ('kernel_bu_eleve');
	  if (!$ppo->student = $studentDAO->get ($studentId)) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get('gestionautonome||showTree')));
	  }
	  
	  if (false !== $ppo->personId) {

      $personDAO = _ioDAO ('kernel_bu_responsable');
  	  if (!$ppo->person = $personDAO->get ($ppo->personId)) {

  	    return CopixActionGroup::process ('generictools|Messages::getError',
    			array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get('gestionautonome||showTree')));
  	  }
  	  
  	  // Récupération du compte du responsable
      $dbuserDAO = _ioDAO ('kernel|kernel_copixuser');
  	  $ppo->account_res = $dbuserDAO->getUserByBuIdAndBuType ($ppo->personId, 'USER_RES');
  	  
  	  // Récupération des liens parentaux
  		$parentLinkDAO = _ioDAO ('kernel_bu_lien_parental');
  	  $parentLinks   = $parentLinkDAO->findAll ();

  	  $ppo->linkNames = array ();
  	  $ppo->linkIds   = array ();
  	  foreach ($parentLinks as $parentLink) {

        $ppo->linkNames[] = $parentLink->parente;
        $ppo->linkIds[]   = $parentLink->id_pa;
      }
      
      // Récupération du lien responsable-élève
      $res2eleDAO = _ioDAO ('kernel|kernel_bu_res2ele');
      $ppo->res2ele = $res2eleDAO->getByPersonAndStudent ($ppo->personId, $studentId);
	  }
	  // Remise à zéro des sessions tmp pour les responsables
	  elseif (is_null ($ppo->save)) {
      
      _sessionSet ('modules|gestionautonome|createAccount', array ());
    	_sessionSet ('modules|gestionautonome|tmpAccount', array ());
	  }
	  
	  $classroomDAO = _ioDAO ('kernel|kernel_bu_ecole_classe');
	  if (!$classroom = $classroomDAO->get ($ppo->nodeId)) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get('gestionautonome||showTree')));
	  }
	  
	  _currentUser()->assertCredential('module:classroom|'.$ppo->nodeId.'|student|update@gestionautonome');
	  
	  $ppo->student->date_nais = CopixDateTime::yyyymmddToDate ($ppo->student->date_nais);
	  
	  $dbuserDAO = _ioDAO ('kernel|kernel_copixuser');
    $ppo->account = $dbuserDAO->getUserByBuIdAndBuType ($studentId, 'USER_ELE');    
    
    $ppo->genderNames = array ('Garçon', 'Fille');
    $ppo->genderIds = array ('1', '2');
	  
    // Breadcrumbs
    $nodeInfos = Kernel::getNodeInfo ($ppo->nodeType, $ppo->nodeId, true);
    
	  $breadcrumbs   = Kernel::generateBreadcrumbs ($nodeInfos);
	  $breadcrumbs[] = array('txt' => $ppo->student->nom.' '.$ppo->student->prenom1);
	  
	  $ppo->breadcrumbs = Kernel::PetitPoucet($breadcrumbs,' &raquo; ');
	  
	  $ppo->TITLE_PAGE = CopixConfig::get('gestionautonome|moduleTitle');
	  
	  return _arPPO ($ppo, 'update_student.tpl');
	}
	
	public function processValidateStudentUpdate () {
	  
	  $ppo = new CopixPPO ();

	  // Récupération des paramètres
	  $ppo->nodeId   = _request ('id_node', null);
	  $ppo->nodeType = _request ('type_node', null);
	  $studentId     = _request ('id_student', null);
	  $ppo->personId = _request ('id_person', false);
	  $res_id_par    = _request ('id_par', null);
	  
	  if (is_null ($ppo->nodeId) || is_null ($ppo->nodeType) || is_null ($studentId)) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get('gestionautonome||showTree')));
	  }
	  
	  $studentDAO = _ioDAO ('kernel_bu_eleve');
	  if (!$ppo->student = $studentDAO->get ($studentId)) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get('gestionautonome||showTree')));
	  }
	  
	  if (false !== $ppo->personId) {

      $personDAO = _ioDAO ('kernel_bu_responsable');
  	  if (!$ppo->person = $personDAO->get ($ppo->personId)) {

  	    return CopixActionGroup::process ('generictools|Messages::getError',
    			array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get('gestionautonome||showTree')));
  	  }
  	  
  	  // Récupération du lien responsable-élève
      $res2eleDAO = _ioDAO ('kernel|kernel_bu_res2ele');
      $ppo->res2ele = $res2eleDAO->getByPersonAndStudent ($ppo->personId, $studentId);
  	}
	  
	  $classroomDAO = _ioDAO ('kernel|kernel_bu_ecole_classe');
	  if (!$classroom = $classroomDAO->get ($ppo->nodeId)) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get('gestionautonome||showTree')));
	  }
	  
	  _currentUser()->assertCredential('module:classroom|'.$ppo->nodeId.'|student|update@gestionautonome');
	  
	  $name = $ppo->student->nom.' '.$ppo->student->prenom1;
	  
    $ppo->student->numero          = '';                        
    $ppo->student->nom             = trim (_request ('nom', null));
    $ppo->student->prenom1         = trim (_request ('prenom1', null));
    $ppo->student->id_sexe         = _request ('gender', null);
    $ppo->student->date_nais       = CopixDateTime::dateToyyyymmdd (_request ('date_nais', null));
    $ppo->student->flag            = 0;
    $ppo->student->ele_last_update = CopixDateTime::timestampToYYYYMMDDHHIISS (time ());
    
    $newPassword = _request ('password', null);
    
    $dbuserDAO = _ioDAO ('kernel|kernel_copixuser'); 
    $ppo->account = $dbuserDAO->getUserByBuIdAndBuType ($studentId, 'USER_ELE');

    // Traitement des erreurs
    $ppo->errors = array ();
    
    if (!$ppo->student->nom) {
      
      $ppo->errors[] = 'Saisissez un nom';
    }
    if (!$ppo->student->prenom1) {
      
      $ppo->errors[] = 'Saisissez un prénom';
    }
    if (!is_null ($newPassword) && !kernel::checkPasswordFormat ($newPassword)) {
      
      $ppo->errors['password_invalid'] = 'Format du mot de passe incorrect : au moins 6 caractères dont 1 chiffre';
    }
    
    if (!empty ($ppo->errors)) {
      
      if (false !== $ppo->personId) {
        
        // Récupération du compte du responsable
        $dbuserDAO = _ioDAO ('kernel|kernel_copixuser');
    	  $ppo->account_res = $dbuserDAO->getUserByBuIdAndBuType ($ppo->personId, 'USER_RES');

    	  // Récupération des liens parentaux
    		$parentLinkDAO = _ioDAO ('kernel_bu_lien_parental');
    	  $parentLinks   = $parentLinkDAO->findAll ();

    	  $ppo->linkNames = array ();
    	  $ppo->linkIds   = array ();
    	  foreach ($parentLinks as $parentLink) {

          $ppo->linkNames[] = $parentLink->parente;
          $ppo->linkIds[]   = $parentLink->id_pa;
        }
      }
      
      // Récupération des niveaux de la classe
      $classSchoolLevelDAO = _ioDAO ('kernel|kernel_bu_ecole_classe_niveau');      
      $classSchoolLevels = $classSchoolLevelDAO->getByClass ($ppo->nodeId);

      $classLevelDAO = _ioDAO ('kernel_bu_classe_niveau');
      
      $ppo->levelNames = array ();
      $ppo->levelIds   = array ();      
      foreach ($classSchoolLevels as $classSchoolLevel) {

        $level              = $classLevelDAO->get ($classSchoolLevel->niveau);
        $ppo->levelNames[]  = $level->niveau_court;
        $ppo->levelIds[]    = $level->id_n;
      }
      
      $ppo->student->date_nais = _request ('date_nais', null);
      
      $ppo->genderNames = array ('Garçon', 'Fille');
      $ppo->genderIds = array ('1', '2');
  	  
  	  // Breadcrumbs
      $nodeInfos = Kernel::getNodeInfo ($ppo->nodeType, $ppo->nodeId, true);

  	  $breadcrumbs = Kernel::generateBreadcrumbs ($nodeInfos);
  	  $breadcrumbs[] = array('txt' => $name);

  	  $ppo->breadcrumbs = Kernel::PetitPoucet($breadcrumbs,' &raquo; ');
  	  
  	  $ppo->TITLE_PAGE = CopixConfig::get('gestionautonome|moduleTitle');
  	  
      return _arPPO ($ppo, 'update_student.tpl');
    }
      
    $studentDAO->update ($ppo->student);
		
		if (false !== $ppo->personId && $ppo->res2ele->res2ele_id_par != $res_id_par) {

      $ppo->res2ele->res2ele_id_par = $res_id_par;
      $res2eleDAO->update ($ppo->res2ele);
    }
    
		// Infos de session pour extraction des infos au format HTML ou CSV
		$session = _sessionGet ('modules|gestionautonome|createAccount');
		if (!$session || !is_array ($session)) {
		  
		  $session = array();
		}
		
		$node_infos = Kernel::getNodeInfo ($ppo->nodeType, $ppo->nodeId, false);
		
		// Modification du password dbuser si différent
    if (!is_null ($newPassword)) {
      
      $ppo->account->password_dbuser = md5 ($newPassword);
      $dbuserDAO->update ($ppo->account);
    }
    
    $passwordsList = _sessionGet ('modules|gestionautonome|passwordsList');
		if (!is_array ($passwordsList)) {

		  $passwordsList = array();
		}
		
    // Mise en session de l'élève pour l'export des infos
    $student = array(
		  'lastname'  => $ppo->student->nom,
			'firstname' => $ppo->student->prenom1,
			'login'     => $ppo->account->login_dbuser,
			'password'  => is_null ($newPassword) ? isset ($passwordsList['USER_ELE'][$ppo->student->idEleve]) ? $passwordsList['USER_ELE'][$ppo->student->idEleve]['password'] : '******' : $newPassword,
			'bu_type'   => 'USER_ELE',
			'bu_id'     => $ppo->student->idEleve,
			'type_nom'  => Kernel::Code2Name('USER_ELE'),
			'node_nom'  => Kernel::Code2Name($ppo->nodeType).' '.$node_infos['nom'],
			'gender'    => $ppo->student->id_sexe
		);
		
		// Mise en session globale des mots de passe
		$passwordsList['USER_ELE'][$ppo->student->idEleve] = $student;
		_sessionSet ('modules|gestionautonome|passwordsList', $passwordsList);
		
		// Session courante
	  $session[$ppo->account->login_dbuser] = $student;
	  $session = array_reverse ($session);
    
    $tmpAccounts = _sessionGet ('modules|gestionautonome|tmpAccount');
    if (is_array($tmpAccounts)) {
      
      foreach ($tmpAccounts as $tmpAccount) {
        
        $session[$tmpAccount['login']] = array(
    		  'lastname'  => $tmpAccount['lastname'],
    			'firstname' => $tmpAccount['firstname'],
    			'login'     => $tmpAccount['login'],
    			'password'  => $tmpAccount['password'],
    			'bu_type'   => 'USER_RES',
    			'bu_id'     => $ppo->student->idEleve,
    			'type_nom'  => Kernel::Code2Name('USER_RES'),
    			'node_nom'  => Kernel::Code2Name($ppo->nodeType)." ".$node_infos['nom'],
    			'gender'    => $tmpAccount['gender']
    		);
      }
      
      // Remise à zéro des sessions tmp
      _sessionSet ('modules|gestionautonome|tmpAccount', array ());      
    }
    
    // Mise en session de l'élève
    _sessionSet ('modules|gestionautonome|createAccount', $session);
    
    // Mise en session du noeud courant
		_sessionSet ('current', array('node_type' => $ppo->nodeType, 'node_id' => $ppo->nodeId));
    
    if (false !== $ppo->personId) {
      
      return _arRedirect (CopixUrl::get ('gestionautonome||updatePersonInCharge', array ('nodeId' => $ppo->nodeId, 'nodeType' => $ppo->nodeType, 'personId' => $ppo->personId, 'save' => 1)));
    }

		return _arRedirect (CopixUrl::get ('gestionautonome||showAccountListing', array('isUpdated' => 1)));
	}

	/**
	 * removeStudent
	 *
	 * Retrait d'un élève d'une classe donnée
	 * 
	 */	
	public function processRemoveStudent () {
	  
	  $ppo->nodeId   = _request ('nodeId', null);
	  $ppo->nodeType = _request ('nodeType', null);
	  $studentId     = _request ('studentId', null);
	  
	  if (is_null ($ppo->nodeId) || is_null ($ppo->nodeType) || is_null ($studentId)) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get('gestionautonome||showTree')));
	  }
	  
	  $studentDAO = _ioDAO ('kernel_bu_eleve');
	  if (!$ppo->student = $studentDAO->get ($studentId)) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get('gestionautonome||showTree')));
	  }
	  
	  $classroomDAO = _ioDAO ('kernel|kernel_bu_ecole_classe');
	  if (!$classroom = $classroomDAO->get ($ppo->nodeId)) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get('gestionautonome||showTree')));
	  }
	  
	  _currentUser()->assertCredential('module:classroom|'.$ppo->nodeId.'|student|update@gestionautonome');
    
	  // Récupération des informations du noeud
	  $nodeInfos = Kernel::getNodeInfo ($ppo->nodeType, $ppo->nodeId);
	  $schoolId = $nodeInfos['ALL']->cla_ecole;

	  // Récupération de l'année scolaire
    if (is_null($grade = _sessionGet('grade'))) {
      
      $grade = Kernel::getAnneeScolaireCourante ()->id_as;
    }
    
	  // Ajout d'un enregistrement de radiation
	  $studentAdmissionDAO = _ioDAO ('kernel_bu_eleve_admission');
	  $studentAdmission = _record ('kernel_bu_eleve_admission');
    
    $studentAdmission->eleve          = $studentId;
    $studentAdmission->etablissement  = $nodeInfos['ALL']->cla_ecole;
    $studentAdmission->annee_scol     = $grade;
    $studentAdmission->id_niveau      = '';
    $studentAdmission->etat_eleve     = 3;
    $studentAdmission->date           = CopixDateTime::timestampToYYYYMMDD (time ());
    $studentAdmission->date_effet     = CopixDateTime::timestampToYYYYMMDD (time ());
    $studentAdmission->code_radiation = '';
    $studentAdmission->previsionnel   = '';
    
    $studentAdmissionDAO->insert ($studentAdmission);
    
    // Récupération de l'affectation de l'élève à la classe pour passage du flag current à 0
    $studentAssignmentDAO = _ioDAO ('kernel|kernel_bu_ele_affect');
    $studentAssignment = $studentAssignmentDAO->getByStudentAndClass ($studentId, $nodeInfos['ALL']->cla_id);
    $studentAssignment->affect_current = 0;
    
    $studentAssignmentDAO->update ($studentAssignment);
    
    // Si l'utilisateur n'a pas d'autres affectations dans cette école : passage du flag inscr_current à 0
    if (!$studentAssignmentDAO->countCurrentAffectInSchool ($studentId, $schoolId) > 0) {

      $studentRegistrationDAO = _ioDAO ('kernel|kernel_bu_eleve_inscription');
      $studentRegistration = $studentRegistrationDAO->getByStudentAndSchool ($studentId, $schoolId);
      
      $studentRegistrationDAO->updateCurrentFlag ($studentRegistration->numero, 0);
    }
    
    // Mise en session du noeud courant
		_sessionSet ('current', array('node_type' => $ppo->nodeType, 'node_id' => $ppo->nodeId));
	  
	  return _arRedirect (CopixUrl::get ('gestionautonome||showTree', array ('save' => 1)));
	}
	
	public function processDeleteStudent () {
	  
	  $ppo->nodeId   = _request ('nodeId', null);
	  $ppo->nodeType = _request ('nodeType', null);
	  $studentId     = _request ('studentId', null);
	  $personId      = _request ('personId', null);
	  $target        = _request ('target', 'eleve');
	  
	  if (is_null ($ppo->nodeId) || is_null ($ppo->nodeType) || is_null ($studentId)) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get('gestionautonome||showTree')));
	  }
	  
	  $studentDAO = _ioDAO ('kernel_bu_eleve');
	  if (!$ppo->student = $studentDAO->get ($studentId)) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get('gestionautonome||showTree')));
	  }
	  
	  $classroomDAO = _ioDAO ('kernel|kernel_bu_ecole_classe');
	  if (!$classroom = $classroomDAO->get ($ppo->nodeId)) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get('gestionautonome||showTree')));
	  }
	  
	  _currentUser()->assertCredential('module:classroom|'.$ppo->nodeId.'|student|delete@gestionautonome');
	  
	  $dbuserDAO        = _ioDAO ('kernel|kernel_copixuser'); 
    $dbLinkDAO        = _ioDAO ('kernel|kernel_bu2user');
	  $studentDAO       = _ioDAO ('kernel_bu_eleve');
	  $assignmentDAO    = _ioDAO ('kernel|kernel_bu_ele_affect');
	  $registrationDAO  = _ioDAO ('kernel|kernel_bu_eleve_inscription');
    $admissionDAO     = _ioDAO ('kernel|kernel_bu_eleve_admission');
     
	  // Récupération et suppression du DbLink et dbuser
	  $dbLink = $dbLinkDAO->getByBUID ('USER_ELE', $studentId);
	  Kernel::disableCopixUser ($dbLink[0]->user_id);
	  $dbLinkDAO->delete ($dbLink[0]->user_id, $dbLink[0]->bu_type, $dbLink[0]->bu_id);

	  // Récupération des affectations de l'élève
	  $assignments = $assignmentDAO->getByStudent ($studentId);
	  foreach ($assignments as $assignment) {
	    
	    $assignmentDAO->delete ($assignment->affect_id);
	  }
	  
	  // Récupération des inscriptions de l'élève
	  $registrations = $registrationDAO->getByStudent ($studentId);
	  foreach ($registrations as $registration) {
	    
	    $registrationDAO->delete ($registration->numero);
	  }

	  // Récupérations des admission de l'élève
	  $admissions = $admissionDAO->getByStudent ($studentId);
	  foreach ($admissions as $admission) {
	    
	    $admissionDAO->delete ($admission->admission_numero);
	  }
	  
	  // Suppression de la liste globale de mots de passe si présent
    $passwordsList = _sessionGet ('modules|gestionautonome|passwordsList');
		if (!is_array ($passwordsList)) {

		  $passwordsList = array();
		}
		
	  // Récupération des liens responsables
	  $res2eleDAO = _ioDAO ('kernel|kernel_bu_res2ele');
	  $resDAO = _ioDAO ('kernel|kernel_bu_res');
	  $personInChargeLinks = $res2eleDAO->getByBeneficiaire ('eleve', $studentId);
    $isDeleted = false;
    
	  foreach ($personInChargeLinks as $personInChargeLink) {
	    
	    $res2eleDAO->delete ($personInChargeLink->res2ele_id_rel);
	    
	    // Si ce responsable n'a plus de lien, on le supprime
  	  $links = $res2eleDAO->getByPerson ($personInChargeLink->res2ele_id_responsable);
  	  
  	  if (count($links) == 0) {
  	      
  	    if (isset($passwordsList['USER_RES'][$personInChargeLink->res2ele_id_responsable])) {

    		  unset($passwordsList['USER_RES'][$personInChargeLink->res2ele_id_responsable]);
    		}

        $resDAO->delete($personInChargeLink->res2ele_id_responsable);
  	    if (!is_null($personId) && $personInChargeLink->res2ele_id_responsable == $personId) {
  	      
  	      $isDeleted = true;
  	    }
  	  }
	  }
	  
	  // Suppression de l'élève
	  $studentDAO->delete ($studentId);
	  
		if (isset($passwordsList['USER_ELE'][$studentId])) {
		  
		  unset($passwordsList['USER_ELE'][$studentId]);
		}
		
		_sessionSet ('modules|gestionautonome|passwordsList', $passwordsList);
		
	  // Mise en session du noeud courant
		_sessionSet ('current', array('node_type' => $ppo->nodeType, 'node_id' => $ppo->nodeId));

	  if ($target == 'eleve' || $isDeleted) {
	    
	    return _arRedirect (CopixUrl::get ('gestionautonome||showTree', array ('save' => 1)));
	  }
	  
	  return _arRedirect (CopixUrl::get ('gestionautonome||updatePersonInCharge', array ('nodeType' => $ppo->nodeType, 'nodeId' => $ppo->nodeId, 'personId' => $personId, 'save' => 1)));
	}
	
	public function processUpdatePersonInCharge () {

	  $ppo = new CopixPPO ();

	  // Récupération des paramètres
	  $ppo->nodeId    = _request ('nodeId', null);
	  $ppo->nodeType  = _request ('nodeType', null);
	  $personId       = _request ('personId', null);
    $ppo->studentId = _request ('studentId', false);
    $ppo->save      = _request ('save');

	  if (is_null ($ppo->nodeId) || is_null ($ppo->nodeType) || is_null ($personId)) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get('gestionautonome||showTree')));
	  }
	  
	  if (false !== $ppo->studentId) {
	    
	    $studentDAO = _ioDAO ('kernel_bu_eleve');
  	  if (!$ppo->student = $studentDAO->get ($ppo->studentId)) {

  	    return CopixActionGroup::process ('generictools|Messages::getError', array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get('gestionautonome||showTree')));
  	  }
      
      // Récupération du compte du responsable
      $dbuserDAO = _ioDAO ('kernel|kernel_copixuser');
  	  $ppo->student_account = $dbuserDAO->getUserByBuIdAndBuType ($ppo->studentId, 'USER_ELE');
  	  
  	  // Récupération des liens parentaux
  		$parentLinkDAO = _ioDAO ('kernel_bu_lien_parental');
  	  $parentLinks   = $parentLinkDAO->findAll ();

  	  $ppo->linkNames = array ();
  	  $ppo->linkIds   = array ();
  	  foreach ($parentLinks as $parentLink) {

        $ppo->linkNames[] = $parentLink->parente;
        $ppo->linkIds[]   = $parentLink->id_pa;
      }
      
      // Récupération du lien responsable-élève
      $res2eleDAO = _ioDAO ('kernel|kernel_bu_res2ele');
      $ppo->res2ele = $res2eleDAO->getByPersonAndStudent ($personId, $ppo->studentId);
	  }
	  // Remise à zéro des sessions tmp pour les responsables
	  elseif (is_null ($ppo->save)) {
      
      _sessionSet ('modules|gestionautonome|createAccount', array ());
	  }
	  
	  $personDAO = _ioDAO ('kernel_bu_responsable');
	  if (!$ppo->person = $personDAO->get ($personId)) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get('gestionautonome||showTree')));
	  }

	  $classroomDAO = _ioDAO ('kernel|kernel_bu_ecole_classe');
	  if (!$classroom = $classroomDAO->get ($ppo->nodeId)) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get('gestionautonome||showTree')));
	  }
	  
	  _currentUser()->assertCredential('module:classroom|'.$ppo->nodeId.'|person_in_charge|update@gestionautonome');
	  
	  $dbuserDAO = _ioDAO ('kernel|kernel_copixuser');
	  
	  // Récupération du compte du responsable
	  $ppo->account = $dbuserDAO->getUserByBuIdAndBuType ($personId, 'USER_RES');
	  
	  $ppo->genderNames = array ('Homme', 'Femme');
    $ppo->genderIds = array ('1', '2');
    
    // Breadcrumbs
    $nodeInfos = Kernel::getNodeInfo ($ppo->nodeType, $ppo->nodeId, true);
	  
	  $breadcrumbs = Kernel::generateBreadcrumbs ($nodeInfos);
    
    $breadcrumbs[] = array('txt' => 'Modification du responsable '.$ppo->person->nom.' '.$ppo->person->prenom1);
	  $ppo->breadcrumbs = Kernel::PetitPoucet($breadcrumbs,' &raquo; ');
	  
	  $ppo->TITLE_PAGE = CopixConfig::get('gestionautonome|moduleTitle');
	  
		return _arPPO ($ppo, 'update_person_in_charge.tpl');
	}
	
	public function processValidatePersonInChargeUpdate () {
	  
	  $ppo = new CopixPPO (); 
	  
	  $ppo->TITLE_PAGE = "Gestion de la structure scolaire";
	  
	  // Récupération des paramètres
	  $ppo->nodeId    = _request ('id_node', null);
	  $ppo->nodeType  = _request ('type_node', null);
	  $personId       = _request ('id_person', null);
	  $ppo->studentId = _request ('id_student', false);
	  
	  if (is_null ($ppo->nodeId) || is_null ($ppo->nodeType) || is_null ($personId)) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get('gestionautonome||showTree')));
	  }
	  
	  if (false !== $ppo->studentId) {

	    $studentDAO = _ioDAO ('kernel_bu_eleve');
  	  if (!$ppo->student = $studentDAO->get ($ppo->studentId)) {

  	    return CopixActionGroup::process ('generictools|Messages::getError', array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get('gestionautonome||showTree')));
  	  }
  	  
  	  // Récupération du lien responsable-élève
      $res2eleDAO = _ioDAO ('kernel|kernel_bu_res2ele');
      $ppo->res2ele = $res2eleDAO->getByPersonAndStudent ($personId, $ppo->studentId);
	  }
	  
	  $personDAO = _ioDAO ('kernel_bu_responsable');
	  if (!$ppo->person = $personDAO->get ($personId)) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get('gestionautonome||showTree')));
	  }

	  $classroomDAO = _ioDAO ('kernel|kernel_bu_ecole_classe');
	  if (!$classroom = $classroomDAO->get ($ppo->nodeId)) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get('gestionautonome||showTree')));
	  }
	  
	  _currentUser()->assertCredential('module:classroom|'.$ppo->nodeId.'|person_in_charge|update@gestionautonome');
    
    $name = $ppo->person->nom.' '.$ppo->person->prenom1;
    
    $ppo->person->nom         = trim (_request ('nom', null));
    $ppo->person->prenom1     = trim (_request ('prenom1', null));
    $ppo->person->date_nais   = _request ('date_nais', null);
    $ppo->person->id_sexe     = _request ('gender', null);
    $res_id_par               = _request ('id_par', null);
    $newPassword              = _request ('password', null);
    
    // Récupération du compte du responsable
    $dbuserDAO = _ioDAO ('kernel|kernel_copixuser');
	  $ppo->account = $dbuserDAO->getUserByBuIdAndBuType ($personId, 'USER_RES');
    
    // Traitement des erreurs
    $ppo->errors = array ();
    
    if (!$ppo->person->nom) {
      
      $ppo->errors[] = 'Saisissez un nom';
    }
    if (!$ppo->person->prenom1) {
      
      $ppo->errors[] = 'Saisissez un prénom';
    }
    if (!is_null ($newPassword) && !kernel::checkPasswordFormat ($newPassword)) {
      
      $ppo->errors['password_invalid'] = 'Format du mot de passe incorrect : au moins 6 caractères dont 1 chiffre';
    }
    
    $nodeInfos = Kernel::getNodeInfo ($ppo->nodeType, $ppo->nodeId, true);

    if (!empty ($ppo->errors)) {
      
      if (false !== $ppo->studentId) {
        
        // Récupération du compte du responsable
        $dbuserDAO = _ioDAO ('kernel|kernel_copixuser');
    	  $ppo->student_account = $dbuserDAO->getUserByBuIdAndBuType ($ppo->studentId, 'USER_ELE');

    	  // Récupération des liens parentaux
    		$parentLinkDAO = _ioDAO ('kernel_bu_lien_parental');
    	  $parentLinks   = $parentLinkDAO->findAll ();

    	  $ppo->linkNames = array ();
    	  $ppo->linkIds   = array ();
    	  foreach ($parentLinks as $parentLink) {

          $ppo->linkNames[] = $parentLink->parente;
          $ppo->linkIds[]   = $parentLink->id_pa;
        }
      }
       
      // Breadcrumbs
      $ppo->genderNames = array ('Homme', 'Femme');
      $ppo->genderIds = array ('1', '2');
      
      $breadcrumbs = Kernel::generateBreadcrumbs ($nodeInfos);
      $breadcrumbs[] = array('txt' => 'Modification du responsable '.$name);
  	  $ppo->breadcrumbs = Kernel::PetitPoucet($breadcrumbs,' &raquo; ');

  	  $ppo->TITLE_PAGE = CopixConfig::get('gestionautonome|moduleTitle');
  	  
      return _arPPO ($ppo, 'update_person_in_charge.tpl');
    }
    
    $personDAO->update ($ppo->person);
    
    if (false !== $ppo->studentId && $ppo->res2ele->res2ele_id_par != $res_id_par) {

      $ppo->res2ele->res2ele_id_par = $res_id_par;
      $res2eleDAO->update ($ppo->res2ele);
    }
    
	  // Modification du password dbuser si différent
    if (!is_null ($newPassword) && $ppo->account->password_dbuser != md5 ($newPassword)) {
      
      $ppo->account->password_dbuser = md5 ($newPassword);
      $dbuserDAO->update ($ppo->account);
    }
    
    // Mise en session globale des mots de passe
		$passwordsList = _sessionGet ('modules|gestionautonome|passwordsList');
		if (!is_array ($passwordsList)) {

		  $passwordsList = array();
		}

    // Mise en session du responsable pour l'export des infos
    $person = array(
		  'lastname'  => $ppo->person->nom,
			'firstname' => $ppo->person->prenom1,
			'login'     => $ppo->account->login_dbuser,
			'password'  => is_null ($newPassword) ? isset ($passwordsList['USER_RES'][$ppo->person->numero]) ? $passwordsList['USER_RES'][$ppo->person->numero]['password'] : '******' : $newPassword,
			'bu_type'   => 'USER_RES',
			'bu_id'     => $ppo->person->numero,
			'type_nom'  => Kernel::Code2Name('USER_RES'),
			'node_nom'  => Kernel::Code2Name($ppo->nodeType).' '.$nodeInfos['nom'],
			'gender'    => $ppo->person->id_sexe
		);

		$passwordsList['USER_RES'][$ppo->person->numero] = $person;
		_sessionSet ('modules|gestionautonome|passwordsList', $passwordsList);
		
		// Mise en session du responsable
		$createAccount = _sessionGet ('modules|gestionautonome|createAccount');
		$createAccount[$ppo->account->login_dbuser] = $person;
		$createAccount = array_reverse ($createAccount);
    _sessionSet ('modules|gestionautonome|createAccount', $createAccount);
    
    if (false !== $ppo->studentId) {
      
      return _arRedirect (CopixUrl::get ('gestionautonome||updateStudent', array ('nodeId' => $ppo->nodeId, 'nodeType' => $ppo->nodeType, 'studentId' => $ppo->studentId, 'save' => 1)));
    }
    
    return _arRedirect (CopixUrl::get ('gestionautonome||showAccountListing', array('isUpdated' => 1)));
	}
	
	// AJAX
	public function processRemovePersonInCharge () {
	  
	  $ppo = new CopixPPO ();
	  
	  $ppo->personId   = _request ('personId', null);
	  $ppo->studentId  = _request ('studentId', null);
	  $ppo->nodeId     = _request ('nodeId', null);
	  $ppo->target     = _request ('target', 'student');
	  $ppo->nodeType   = 'BU_CLASSE';
	  
	  if (is_null ($ppo->personId) || is_null ($ppo->studentId)) {

	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get('gestionautonome||showTree')));
	  }
    
    $ppo->user = _currentUser ();
	  $ppo->user->assertCredential('module:classroom|'.$ppo->nodeId.'|person_in_charge|update@gestionautonome');
	  
	  // Suppression de l'affectation du responsable
	  $personInChargeLinkDAO = _ioDAO ('kernel|kernel_bu_res2ele');
	  $personInChargeLinks = $personInChargeLinkDAO->getByPerson ($ppo->personId);
	  
	  foreach ($personInChargeLinks as $link) {
	    
	    if ($link->res2ele_id_beneficiaire == $ppo->studentId) {
	      
	      $personInChargeLinkDAO->delete ($link->res2ele_id_rel);
	      break;
	    }
	  }
	  
	  // Suppression de la liste globale de mots de passe si présent
    $passwordsList = _sessionGet ('modules|gestionautonome|passwordsList');
		if (!is_array ($passwordsList)) {

		  $passwordsList = array();
		}
		
	  $isDeleted = false;
	  if (count($personInChargeLinks) == 1) {
	    
	    $personsInChargeDAO = _ioDAO ('kernel|kernel_bu_res');
	    $personsInChargeDAO->delete ($ppo->personId);
	    
	    if (isset($passwordsList['USER_RES'][$ppo->personId])) {

  		  unset($passwordsList['USER_RES'][$ppo->personId]);
  		  _sessionSet ('modules|gestionautonome|passwordsList', $passwordsList);
  		}
  		
	    $isDeleted = true;
	  }
    
    if ($ppo->target == 'student') {
      
      return _arRedirect (CopixUrl::get ('gestionautonome||updateStudent', array ('nodeType' => $ppo->nodeType, 'nodeId' => $ppo->nodeId, 'studentId' => $ppo->studentId, 'save' => 1)));
    }
    
    if ($isDeleted) {
      
      return _arRedirect (CopixUrl::get ('gestionautonome||showTree', array ('save' => 1)));
    }
    
    return _arRedirect (CopixUrl::get ('gestionautonome||updatePersonInCharge', array ('nodeType' => $ppo->nodeType, 'nodeId' => $ppo->nodeId, 'personId' => $ppo->personId, 'save' => 1)));
	}
	
	/**
	 * AJAX - Suppression d'un responsable d'un élève
	 */
	public function processDeletePersonInCharge () {
	  
	  $ppo = new CopixPPO ();
	  
	  $ppo->personId   = _request ('personId', null);
	  $ppo->studentId  = _request ('studentId', false);
	  $ppo->nodeId     = _request ('nodeId', null); 
	  $ppo->nodeType   = 'BU_CLASSE';
	  
	  if (is_null ($ppo->personId)) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get('gestionautonome||showTree')));
	  }
	  
	  $ppo->user = _currentUser ();
	  $ppo->user->assertCredential('module:classroom|'.$ppo->nodeId.'|person_in_charge|delete@gestionautonome');
	  
	  $personsInChargeDAO = _ioDAO ('kernel|kernel_bu_res');
    $personInChargeLinkDAO = _ioDAO ('kernel|kernel_bu_res2ele');
	  
	  // Récupération des affectations du responsable
	  $assignments = $personInChargeLinkDAO->getByPerson ($ppo->personId);
	  foreach ($assignments as $assignment) {
	    
	    $personInChargeLinkDAO->delete ($assignment->res2ele_id_rel);
	  }
	  
	  $personsInChargeDAO->delete ($ppo->personId);
	  
	  // Suppression de la liste globale de mots de passe si présent
    $passwordsList = _sessionGet ('modules|gestionautonome|passwordsList');
		if (!is_array ($passwordsList)) {

		  $passwordsList = array();
		}
		if (isset($passwordsList['USER_RES'][$ppo->personId])) {
		  
		  unset($passwordsList['USER_RES'][$ppo->personId]);
		  _sessionSet ('modules|gestionautonome|passwordsList', $passwordsList);
		}
		
		if (false !== $ppo->studentId) {
		  
		  // Récupération des responsables de l'élève
  	  return _arRedirect (CopixUrl::get ('gestionautonome||updateStudent', array ('nodeType' => $ppo->nodeType, 'nodeId' => $ppo->nodeId, 'studentId' => $ppo->studentId, 'save' => 1)));
		}
    
	  return _arRedirect (CopixUrl::get ('gestionautonome||showTree', array ('tab' => 2, 'save' => 1)));
	} 
	
	/**
	 * AJAX - ajout des responsables d'un élève
	 */ 
	public function processPersonInChargeCreation () {
    
	  $ppo->nodeId   = _request ('nodeId', null);
	  $ppo->nodeType = _request ('nodeType', null);
	  $ppo->studentId = _request ('studentId', false);

	  if (is_null ($ppo->nodeId) || is_null ($ppo->nodeType)) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get('gestionautonome||showTree')));
	  }
	  
	  _currentUser()->assertCredential('module:classroom|'.$ppo->nodeId.'|person_in_charge|create@gestionautonome');
    
    $ppo->isNewParent = _request ('isNewParent', null);
    
    // Paramètres nouveau parent
	  $ppo->person->nom     = _request ('nom', null);
	  $ppo->person->prenom1 = _request ('prenom1', null);
	  $ppo->person->id_sexe = _request ('gender', 1);
		$ppo->person->id_par  = _request ('parId', null);
		
	  $ppo->account->login    = _request ('login', null);
	  $ppo->account->password = _request ('password', null);
	  $ppo->agreement         = _request ('agreement', false);
	  $ppo->cpt               = _request('cpt', 1);
    
    $ppo->user = _currentUser();
    
	  // Traitement des erreurs
    $ppo->errors = array ();
    
    if ($ppo->isNewParent) {
    
      if (!$ppo->person->nom) {
      
        $ppo->errors[] = 'Saisissez un nom';
      }
      if (!$ppo->person->prenom1) {
      
        $ppo->errors[] = 'Saisissez un prénom';
      }
      if (is_null($ppo->person->id_sexe)) {
      
        $ppo->errors[] = 'Saisissez un sexe';
      }
      if (!$ppo->account->login) {
      
        $ppo->errors[] = 'Saisissez un login';
      }
      if (!$ppo->account->password) {
      
        $ppo->errors[] = 'Saisissez un mot de passe';
      }
      elseif (!Kernel::checkPasswordFormat ($ppo->account->password)) {
      
        $ppo->errors[] = 'Format du mot de passe incorrect : au moins 6 caractères dont 1 chiffre';
      }
      if (!Kernel::isLoginAvailable ($ppo->account->login)) {
      
        $ppo->errors[] = 'Login non disponible';
      }
    }
    else {
      
      $personInChargeDAO = _ioDAO ('kernel|kernel_bu_res');

      // Vérification du login
      if (!$ppo->account->login) {

        $ppo->errors[] = 'Saisissez l\'identifiant du responsable à associer à l\'élève.';
      }
      // Responsable existant ?
      elseif (!$ppo->person = $personInChargeDAO->getByLogin($ppo->account->login)) {

        $ppo->errors[] = 'Cet identifiant ne correspond à aucun responsable.';
      }
      // Conditions validées ?
      if ($ppo->agreement == "false") {

        $ppo->errors[] = 'Merci de valider les conditions d\'association d\'un responsable à un élève.';
      }
      
      // Le responsable ne doit pas être présent parmi les responsables existants
      if (false !== $ppo->studentId) {
        
        $personsInChargeDAO = _ioDAO ('kernel|kernel_bu_res');
        $ppo->persons = $personsInChargeDAO->getByStudent ($ppo->studentId);
        
        if ($ppo->person) {
          
          $isPresent = false;
          foreach ($ppo->persons as $person) {

            if ($person->res_numero == $ppo->person->res_numero) {

              $ppo->errors[] = 'Ce responsable est déjà présent parmi les responsables de l\'élève.';
              break;
            }
          }
        }
      }
    }
    
    // Récupération des relations    
	  $parentLinkDAO = _ioDAO ('kernel_bu_lien_parental'); 
	  $parentLinks = $parentLinkDAO->findAll ();

	  $ppo->linkNames = array ();
	  $ppo->linkIds   = array ();
	  foreach ($parentLinks as $parentLink) {

      $ppo->linkNames[] = $parentLink->parente;
      $ppo->linkIds[]   = $parentLink->id_pa;
    }

    $ppo->genderNames = array ('Homme', 'Femme');
    $ppo->genderIds = array ('1', '2');
    
    if (!empty ($ppo->errors)) {

      return _arPPO ($ppo, array ('template' => '_create_person_in_charge.tpl', 'mainTemplate' => null));
    }
    
    $passwordsList = _sessionGet ('modules|gestionautonome|passwordsList');
		if (!is_array ($passwordsList)) {

		  $passwordsList = array();
		}
		
    if ($ppo->studentId) {
      
      $session = array();
      $node_infos = Kernel::getNodeInfo ($ppo->nodeType, $ppo->nodeId, false);

      $personDAO     = _ioDAO ('kernel_bu_responsable');
      $personLinkDAO = _ioDAO ('kernel|kernel_bu_res2ele');
      $dbLinkDAO = _ioDAO ('kernel_link_bu2user');
      
      $ppo->personsInSession = _sessionGet ('modules|gestionautonome|tmpAccount');
      if (!$ppo->personsInSession) {

        $ppo->personsInSession = array();
      }
  		
      if ($ppo->isNewParent) {
        
        if (Kernel::isLoginAvailable ($ppo->account->login)) {
        
          // Création du responsable
          $person = _record ('kernel_bu_responsable');

          $person->nom     = trim ($ppo->person->nom);
          $person->prenom1 = trim ($ppo->person->prenom1);
          $person->id_sexe = $ppo->person->id_sexe;

    	    $personDAO->insert ($person);
    	    
    	    // Création de l'association personne->rôle
          $newPersonLink = _record ('kernel|kernel_bu_res2ele');

      		$newPersonLink->res2ele_id_beneficiaire   = $ppo->studentId; 
      		$newPersonLink->res2ele_type_beneficiaire = 'eleve';
      		$newPersonLink->res2ele_id_responsable    = $person->numero;
      		$newPersonLink->res2ele_type_responsable  = 'responsable';
      		$newPersonLink->res2ele_auth_parentale    = '0';
      		$newPersonLink->res2ele_id_par            = $ppo->person->id_par;

      		$personLinkDAO->insert ($newPersonLink);

      		// Création du compte dbuser
      		$dbuserDAO = _ioDAO ('kernel|kernel_copixuser');
          $dbuser = _record ('kernel|kernel_copixuser');

          $dbuser->login_dbuser    = $ppo->account->login;
          $dbuser->password_dbuser = md5 ($ppo->account->password);
          $dbuser->email_dbuser    = '';
          $dbuser->enabled_dbuser  = 1;

          $dbuserDAO->insert ($dbuser);

          // Création du link bu2user
          $dbLink = _record ('kernel_link_bu2user');

          $dbLink->user_id = $dbuser->id_dbuser;
          $dbLink->bu_type = 'USER_RES';
          $dbLink->bu_id   = $person->numero;

          $dbLinkDAO->insert ($dbLink);
          
          // Initialisation de la variable de session
          $responsable = array(
      		  'lastname'  => $person->nom,
      			'firstname' => $person->prenom1,
      			'id_par'    => $ppo->person->id_par,
      			'id_sexe'   => $person->id_sexe,
      			'login'     => $ppo->account->login,
      			'password'  => $ppo->account->password,
      			'gender'    => $person->id_sexe
      		);
      		
      		$ppo->personsInSession[] = $responsable;
          _sessionSet ('modules|gestionautonome|tmpAccount', $ppo->personsInSession);
          
          // Mise en session globale des mots de passe
      		$passwordsList['USER_RES'][$person->numero] = $responsable;
      		_sessionSet ('modules|gestionautonome|passwordsList', $passwordsList);
        }
      }
      elseif (!$personLinkDAO->getByPersonAndStudent ($ppo->person->res_numero, $ppo->studentId)) {
    		
        $newPersonLink = _record ('kernel|kernel_bu_res2ele');

    		$newPersonLink->res2ele_id_beneficiaire   = $ppo->studentId; 
    		$newPersonLink->res2ele_type_beneficiaire = 'eleve';
    		$newPersonLink->res2ele_id_responsable    = $ppo->person->res_numero;
    		$newPersonLink->res2ele_type_responsable  = 'responsable';
    		$newPersonLink->res2ele_auth_parentale    = '0';
    		$newPersonLink->res2ele_id_par            = $ppo->person->res_id_sexe == 1 ? '2' : '1';

    		$personLinkDAO->insert ($newPersonLink);
    		
    		// Initialisation de la variable de session
        $responsable = array(
          'res_numero'    => $ppo->person->res_numero,
    		  'lastname'      => $ppo->person->res_nom,
    		  'firstname'     => $ppo->person->res_prenom1,
    		  'res_id_sexe'   => $ppo->person->res_id_sexe,
    		  'login'         => $ppo->account->login,
    		  'password'      => isset ($passwordsList['USER_RES'][$ppo->person->res_numero]) ? $passwordsList['USER_RES'][$ppo->person->res_numero]['password'] : '******',
    		  'gender'        => $ppo->person->res_id_sexe
        );
    		
    		$ppo->personsInSession[] = $responsable;
        _sessionSet ('modules|gestionautonome|tmpAccount', $ppo->personsInSession);
      }
    }
    else {

      // Initialisation de la variable de session
      $ppo->personsInSession = _sessionGet ('modules|gestionautonome|tmpAccount');
      if (!$ppo->personsInSession) {

        $ppo->personsInSession = array();
      }

      // S'il s'agit d'un nouveau parent, on stocke les informations le concernant.
      // S'il s'agit d'un parent existant son ID "responsable" est stocké.
      if ($ppo->isNewParent) {

        $ppo->personsInSession[$ppo->cpt] = array(
    		  'lastname'  => $ppo->person->nom,
    			'firstname' => $ppo->person->prenom1,
    			'id_par'    => $ppo->person->id_par,
    			'parente'   => $ppo->linkNames[$ppo->person->id_par-1],
    			'id_sexe'   => $ppo->person->id_sexe,
    			'login'     => $ppo->account->login,
    			'password'  => $ppo->account->password,
    			'gender'    => $ppo->person->id_sexe
    		);

    		$ppo->cpt++;
      }
      else {
        
        $parente = $ppo->person->res_id_sexe == 1 ? 1 : 0;
        
        $tmpArray = array(
          'res_numero'    => $ppo->person->res_numero,
    		  'lastname'      => $ppo->person->res_nom,
    		  'firstname'     => $ppo->person->res_prenom1,
    		  'res_id_sexe'   => $ppo->person->res_id_sexe,
    		  'login'         => $ppo->account->login,
    		  'parente'       => $ppo->linkNames[$parente],
    		  'password'      => isset ($passwordsList['USER_RES'][$ppo->person->res_numero]) ? $passwordsList['USER_RES'][$ppo->person->res_numero]['password'] : '******',
    		  'gender'        => $ppo->person->res_id_sexe
        );

        if (!in_array($tmpArray, array_values($ppo->personsInSession))) {

          $ppo->personsInSession[$ppo->cpt] = $tmpArray;
          $ppo->cpt++;
        }
      }

  		_sessionSet ('modules|gestionautonome|tmpAccount', $ppo->personsInSession);
    }

    // Le responsable ne doit pas être présent parmi les responsables existants
    if (false !== $ppo->studentId) {
      
      $personsInChargeDAO = _ioDAO ('kernel|kernel_bu_res');
      $ppo->persons = $personsInChargeDAO->getByStudent ($ppo->studentId);
    }
      
    // Réinitialisation des variables
		$ppo->person  = null;
		$ppo->account = null;
		$ppo->isNewParent = null;
		$ppo->login = null;
		$ppo->agreement = false;
	
		return _arPPO ($ppo, array ('template' => '_create_person_in_charge.tpl', 'mainTemplate' => null));
	}

	/**
	 * manageGrades
	 *
	 * Gestion des années scolaires
	 * 
	 */	
	public function processManageGrades () {
	  
	  _currentUser()->assertCredential('group:[Admin]');
	  
	  $ppo = new CopixPPO ();
    
	  // Breadcrumbs
	  $breadcrumbs   = array();
	  $breadcrumbs[] = array('txt' => 'Gestion des années scolaires');
	  
	  $ppo->breadcrumbs = Kernel::PetitPoucet ($breadcrumbs,' &raquo; ');
	  
	  // Récupérations des années scolaires
    $gradesDAO = _ioDAO ('kernel_bu_annee_scolaire');
	  $ppo->grades = $gradesDAO->findAll ();
	  
	  $ppo->TITLE_PAGE = CopixConfig::get('gestionautonome|gradesManagementTitle');
	  
	  return _arPPO ($ppo, 'manage_grades.tpl');
	}

	/**
	 * createGrade
	 *
	 * Création d'une année scolaire
	 * 
	 */	
	public function processCreateGrade () {
	  
	  _currentUser()->assertCredential('group:[Admin]');
	  
	  $ppo = new CopixPPO ();
    
	  // Breadcrumbs
	  $breadcrumbs   = array();
	  $breadcrumbs[] = array('txt' => 'Gestion des années scolaires', 'url' => CopixUrl::get('gestionautonome||manageGrades'));
	  $breadcrumbs[] = array('txt' => 'Ajout d\'une année scolaire');
	  
	  $ppo->breadcrumbs = Kernel::PetitPoucet ($breadcrumbs,' &raquo; ');
	  
	  $ppo->TITLE_PAGE = CopixConfig::get('gestionautonome|gradesManagementTitle');
	  
	  return _arPPO ($ppo, 'create_grade.tpl');
	}

	/**
	 * validateGradeCreation
	 *
	 * Validation de la création d'une année scolaire
	 * 
	 */	
	public function processValidateGradeCreation () {
	  
	  _currentUser()->assertCredential('group:[Admin]');
	  
	  $ppo = new CopixPPO ();
	  
    $dateDebut = _request ('dateDebut', null);
    $dateFin   = _request ('dateFin', null);
                               
    $ppo->grade = _record ('kernel|kernel_bu_annee_scolaire');
    
    $ppo->grade->id_as         = substr($dateDebut, 6, 10);
	  $ppo->grade->anneeScolaire = substr($dateDebut, 6, 10).'-'.substr($dateFin, 6, 10);
	  $ppo->grade->dateDebut     = CopixDateTime::dateToyyyymmdd($dateDebut);
	  $ppo->grade->dateFin       = CopixDateTime::dateToyyyymmdd($dateFin);

    $current = _request ('current', null);
    if ($current == 'on') {
      
      $ppo->grade->current = 1;
    }
    else {
      
      $ppo->grade->current = 0;
    }

    if (!$ppo->grade->dateDebut) {
      
      $ppo->errors[] = 'Saisissez une date de début';
    }
    if (!$ppo->grade->dateFin) {
      
      $ppo->errors[] = 'Saisissez une date de fin';
    }
    
    $gradeDAO = _ioDAO ('kernel|kernel_bu_annee_scolaire');
    if ($gradeDAO->get ($ppo->grade->id_as)) {
      
      $ppo->errors[] = 'Cette année scolaire existe déjà';
    }

    if (!empty ($ppo->errors)) {
      
      $ppo->grade->dateDebut = CopixDateTime::yyyymmddToDate($ppo->grade->dateDebut);
  	  $ppo->grade->dateFin   = CopixDateTime::yyyymmddToDate($ppo->grade->dateFin);
  	  
  	  // Breadcrumbs
  	  $breadcrumbs   = array();
  	  $breadcrumbs[] = array('txt' => 'Gestion des années scolaires', 'url' => CopixUrl::get('gestionautonome||manageGrades'));
  	  $breadcrumbs[] = array('txt' => 'Ajout d\'une année scolaire');

  	  $ppo->breadcrumbs = Kernel::PetitPoucet ($breadcrumbs,' &raquo; ');
  	  
      return _arPPO ($ppo, 'create_grade.tpl');
    }

    if ($ppo->grade->current == 1) {
      
      if ($currentGrade = $gradeDAO->getCurrent ()) {
        
        $currentGrade->dateDebut = str_replace('-', '', $currentGrade->dateDebut);
        $currentGrade->dateFin = str_replace('-', '', $currentGrade->dateFin);
        $currentGrade->current = 0;

        $gradeDAO->update ($currentGrade);
      }
    }
    
    $gradeDAO->insert ($ppo->grade); 

    return _arRedirect (CopixUrl::get ('gestionautonome||manageGrades', array ('save' => 1)));
	}

	/**
	 * setCurrentGrade
	 *
	 * Spécifier l'année courante
	 * 
	 */	
	public function processSetCurrentGrade () {
	  
	  _currentUser()->assertCredential('group:[Admin]');
	  
	  $ppo = new CopixPPO ();
	  
	  $gradeId = _request ('gradeId', null);
	  if (!is_null($gradeId)) {
	    
	    $gradeDAO = _ioDAO ('kernel|kernel_bu_annee_scolaire');
	    while ($currentGrade = $gradeDAO->getCurrent ()) {
	      
	      $currentGrade->dateDebut = str_replace('-', '', $currentGrade->dateDebut);
    	  $currentGrade->dateFin = str_replace('-', '', $currentGrade->dateFin);
        $currentGrade->current = 0;
        
        $gradeDAO->update ($currentGrade);
	    }

  	  if ($grade = $gradeDAO->get ($gradeId)) {
       
  	    $grade->current = 1;
    	  $gradeDAO->update ($grade);
  	  }
	  }

	  return _arRedirect (CopixUrl::get ('gestionautonome||manageGrades', array ('save' => 1)));
	}
	
	public function processDeleteGrade () {
	  
	  _currentUser()->assertCredential('group:[Admin]');
	  
	  $gradeId = _request ('gradeId', null);
	  
	  $gradesDAO = _ioDAO ('kernel_bu_annee_scolaire');
	  $gradesDAO->delete ($gradeId);

	  return _arRedirect (CopixUrl::get ('gestionautonome||manageGrades', array ('save' => 1)));
	}

	/**
	 * addExistingStudent
	 *
	 * Association classe - élève pour des élèves déjà existants
	 * 
	 */	
	public function processAddExistingStudent () {
	  
	  $ppo = new CopixPPO ();
	  
	  $ppo->nodeId   = _request ('parentId', null);
	  $ppo->nodeType = _request ('parentType', null);
	  
	  if (is_null ($ppo->nodeId) || is_null ($ppo->nodeType)) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get('gestionautonome||showTree')));
	  }
	  
	  _currentUser()->assertCredential('module:classroom|'.$ppo->nodeId.'|student|create@gestionautonome');

	  $nodeInfos = Kernel::getNodeInfo ($ppo->nodeType, $ppo->nodeId, true);
    
    // Breadcrumbs
	  $breadcrumbs      = Kernel::generateBreadcrumbs ($nodeInfos);
	  $breadcrumbs[]    = array('txt' => 'Ajout d\'un élève existant');
	  $ppo->breadcrumbs = Kernel::PetitPoucet($breadcrumbs,' &raquo; ');
	  
	  // Récupération de l'année scolaire
    if (is_null($grade = _sessionGet('grade'))) {
      
      $grade = Kernel::getAnneeScolaireCourante ()->id_as;
    }
    
	  _sessionSet ('gestionautonome|addExisting', array ());                                                                         

	  $ppo->listFilters = array ();

	  switch ($ppo->nodeType) {
			case 'BU_GRVILLE' :
        $type_ref  = 'GVILLE';
        $ppo->listFilters['groupcity'] = $nodeInfos['id'];
				break;
		  case 'BU_VILLE' :
        $type_ref  = 'VILLE';
        $ppo->listFilters['groupcity'] = $nodeInfos['ALL']->vil_id_grville;
        $ppo->listFilters['city']      = $nodeInfos['id'];
  			break;
  		case 'BU_ECOLE' :
        $type_ref  = 'ECOLE';
        $ppo->listFilters['groupcity'] = $nodeInfos['ALL']->vil_id_grville;
        $ppo->listFilters['city']      = $nodeInfos['ALL']->vil_id_vi;
        $ppo->listFilters['school']    = $nodeInfos['id'];
    		break;		
    	case 'BU_CLASSE' :
        $type_ref  = 'CLASSE';                            
        $ppo->listFilters['groupcity'] = '1';
        $ppo->listFilters['city']      = $nodeInfos['ALL']->eco_id_ville;
        $ppo->listFilters['school']    = $nodeInfos['ALL']->eco_numero;
        $ppo->listFilters['class']     = $nodeInfos['id'];
      	break;
		}
		
		$ppo->listFilters['grade']         = $grade;
 
		_sessionSet ('gestionautonome|addExisting', $ppo->listFilters);

	  // Récupération des niveaux de la classe
    $classSchoolLevelDAO = _ioDAO ('kernel|kernel_bu_ecole_classe_niveau');
    $classLevelDAO       = _ioDAO ('kernel_bu_classe_niveau');
    
	  $classSchoolLevels   = $classSchoolLevelDAO->getByClass ($ppo->nodeId);
	  
    $ppo->levelNames = array ();
    $ppo->levelIds   = array ();
    
    foreach ($classSchoolLevels as $classSchoolLevel) {
      
      $level             = $classLevelDAO->get ($classSchoolLevel->niveau);
      $ppo->levelNames[] = $level->niveau_court;
      $ppo->levelIds[]   = $level->id_n;
    }
	  
	  $studentDAO = _ioDAO ('kernel|kernel_bu_ele');
	  $ppo->students = $studentDAO->findStudentsForAssignment ($ppo->nodeId, $type_ref, $ppo->listFilters);
    
    $ppo->TITLE_PAGE = CopixConfig::get('gestionautonome|moduleTitle');
    
	  return _arPPO ($ppo, 'add_existing_student.tpl');
	}
	
	public function processFilterExistingStudents () {
	  
	  $ppo = new CopixPPO ();

	  $ppo->nodeId   = _request ('parentId', null);
	  $ppo->nodeType = _request ('parentType', null);
	  $ppo->role     = _request ('role', null);
	  
	  _currentUser()->assertCredential('module:classroom|'.$ppo->nodeId.'|student|create@gestionautonome');
	  
	  if (is_null ($ppo->nodeId) || is_null ($ppo->nodeType)) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get('gestionautonome||showTree')));
	  }
	  
	  $nodeInfos = Kernel::getNodeInfo ($ppo->nodeType, $ppo->nodeId, true);
    
    // Breadcrumbs
	  $breadcrumbs      = Kernel::generateBreadcrumbs ($nodeInfos);
	  $breadcrumbs[]    = array('txt' => 'Ajout d\'un élève existant');
	  $ppo->breadcrumbs = Kernel::PetitPoucet($breadcrumbs,' &raquo; ');
	  
	  // Récupération de l'année scolaire
    if (is_null($grade = _sessionGet('grade'))) {
      
      $grade = Kernel::getAnneeScolaireCourante ()->id_as;
    }
	  
	  switch ($ppo->nodeType) {
			case 'BU_GRVILLE' :
        $type_ref  = 'GVILLE';
				break;
		  case 'BU_VILLE' :
        $type_ref  = 'VILLE';
  			break;
  		case 'BU_ECOLE' :
        $type_ref  = 'ECOLE';
    		break;		
    	case 'BU_CLASSE' :
        $type_ref  = 'CLASSE';
      	break;
		}
	  
	  $ppo->listFilters = _sessionGet ('gestionautonome|addExisting');                                                                         
	  if (!is_array ($ppo->listFilters)) {
	    
	    $ppo->listFilters = array ();
	  }
	  
    $ppo->listFilters['withAssignment'] = _request ('withAssignment', null);
    $ppo->listFilters['lastname']       = _request ('lastname', null);
    $ppo->listFilters['firstname']      = _request ('firstname', null);
    $ppo->listFilters['groupcity']      = _request ('groupcity', null);
    $ppo->listFilters['city']           = _request ('city', null);
    $ppo->listFilters['school']         = _request ('school', null);
    $ppo->listFilters['class']          = _request ('class', null);
    $ppo->listFilters['grade']          = $grade;
    
    _sessionSet ('gestionautonome|addExisting', $ppo->listFilters);
    
    // Récupération des niveaux de la classe
    $classSchoolLevelDAO = _ioDAO ('kernel|kernel_bu_ecole_classe_niveau');
    $classLevelDAO       = _ioDAO ('kernel_bu_classe_niveau');
    
	  $classSchoolLevels   = $classSchoolLevelDAO->getByClass ($ppo->nodeId);
	  
    $ppo->levelNames = array ();
    $ppo->levelIds   = array ();
    
    foreach ($classSchoolLevels as $classSchoolLevel) {
      
      $level             = $classLevelDAO->get ($classSchoolLevel->niveau);
      $ppo->levelNames[] = $level->niveau_court;
      $ppo->levelIds[]   = $level->id_n;
    }
	  
	  $studentDAO = _ioDAO ('kernel|kernel_bu_ele');
	  $ppo->students = $studentDAO->findStudentsForAssignment ($ppo->nodeId, $type_ref, $ppo->listFilters);
    
    $ppo->TITLE_PAGE = CopixConfig::get('gestionautonome|moduleTitle');
    
	  return _arPPO ($ppo, 'add_existing_student.tpl');
	}
	
	public function processValidateExistingStudentsAdd () {
	  
	  $ppo = new CopixPPO ();

	  $ppo->nodeId      = _request ('id_node', null);
	  $ppo->nodeType    = _request ('type_node', null);
	  $ppo->role        = _request ('role', null);
	  $ppo->studentIds  = _request ('studentIds', null);
	  
	  _currentUser()->assertCredential('module:classroom|'.$ppo->nodeId.'|student|create@gestionautonome');
	  
	  if (is_null ($ppo->nodeId) || is_null ($ppo->nodeType)) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get('gestionautonome||showTree')));
	  }

	  switch ($ppo->nodeType) {
			case 'BU_GRVILLE' :
        $type_ref  = 'GVILLE';
				break;
		  case 'BU_VILLE' :
        $type_ref  = 'VILLE';
  			break;
  		case 'BU_ECOLE' :
        $type_ref  = 'ECOLE';
    		break;		
    	case 'BU_CLASSE' :
        $type_ref  = 'CLASSE';
      	break;
		}
	  
    $studentRegistrationDAO  = _ioDAO ('kernel|kernel_bu_eleve_inscription');
    $studentAdmissionDAO     = _ioDAO ('kernel|kernel_bu_eleve_admission');
    $studentAssignmentDAO    = _ioDAO ('kernel|kernel_bu_ele_affect');
    $classDAO                = _ioDAO ('kernel|kernel_bu_ecole_classe');
    $schoolClassLevelDAO     = _ioDAO ('kernel|kernel_bu_ecole_classe_niveau');
    
    // Récupération de l'année scolaire
    if (is_null($grade = _sessionGet('grade'))) {
      
      $grade = Kernel::getAnneeScolaireCourante ()->id_as;
    }
    
	  if (!is_null ($ppo->studentIds)) {
	    
	    $class = $classDAO->get ($ppo->nodeId);
	    $classId = $class->id; 
      $schoolClassLevels = $schoolClassLevelDAO->getByClass ($class->id);
      $classType = $schoolClassLevels[0]->type;
      $schoolId = $class->ecole;
	    
	    foreach ($ppo->studentIds as $studentId) {

        $level = _request ('level-'.$studentId, null);
        
        // Inscription de l'élève dans l'école s'il ne l'est pas déjà
        if (!$studentRegistrationDAO->getByStudentAndSchool ($studentId, $schoolId)) {
          
          $studentRegistration = _record ('kernel|kernel_bu_eleve_inscription');

          $studentRegistration->eleve                   = $studentId;
          $studentRegistration->annee_scol              = $grade; 
          $studentRegistration->date_preinscript        = CopixDateTime::timestampToYYYYMMDD (time ());
          $studentRegistration->date_effet_preinscript  = CopixDateTime::timestampToYYYYMMDD (time ());
          $studentRegistration->date_inscript           = CopixDateTime::timestampToYYYYMMDD (time ());
          $studentRegistration->date_effet_inscript     = CopixDateTime::timestampToYYYYMMDD (time ());
          $studentRegistration->etablissement           = $schoolId;
          $studentRegistration->etablissement_refus     = 0;
          $studentRegistration->id_niveau               = $level;
          $studentRegistration->id_typ_cla              = $classType;
          $studentRegistration->vaccins_aj              = 0;
          $studentRegistration->attente                 = 0;
          $studentRegistration->derogation_dem          = 0; 
          $studentRegistration->temporaire              = 0;
          $studentRegistration->current_inscr           = 1; 

          $studentRegistrationDAO->insert ($studentRegistration);
        }

        // Admission de l'élève dans l'école
        $studentAdmission = _record ('kernel|kernel_bu_eleve_admission');

        $studentAdmission->admission_eleve          = $studentId;
        $studentAdmission->admission_etablissement  = $schoolId;
        $studentAdmission->admission_annee_scol     = $grade;
        $studentAdmission->admission_id_niveau      = $level;
        $studentAdmission->admission_etat_eleve     = 1;
        $studentAdmission->admission_date           = CopixDateTime::timestampToYYYYMMDD (time ());
        $studentAdmission->admission_date_effet     = CopixDateTime::timestampToYYYYMMDD (time ());
        $studentAdmission->admission_code_radiation = '';
        $studentAdmission->admission_previsionnel   = '';

        $studentAdmissionDAO->insert ($studentAdmission);

        if (!$studentAssignment = $studentAssignmentDAO->getByStudentAndClass ($studentId, $classId)) {
          
          // Affectation de l'élève dans les classes
          $studentAssignment = _record ('kernel|kernel_bu_ele_affect');

          $studentAssignment->affect_eleve           = $studentId;
          $studentAssignment->affect_annee_scol      = $grade;
          $studentAssignment->affect_classe          = $ppo->nodeId;
          $studentAssignment->affect_niveau          = $level;
          $studentAssignment->affect_current         = 1;
          $studentAssignment->affect_previsionnel_cl = 0;

          $studentAssignmentDAO->insert ($studentAssignment);
        }
        else {
          
          $studentAssignment->affect_current = 1;
          $studentAssignmentDAO->update ($studentAssignment);
        }
  	  }
  	  
  	  $ppo->save = 1;
	  }
	  
	  $ppo->listFilters = _sessionGet ('gestionautonome|addExisting');
	  if (!is_array ($ppo->listFilters)) {
	    
	    $ppo->listFilters = array ();
	  }
	  
	  // Récupération des niveaux de la classe
    $classSchoolLevelDAO = _ioDAO ('kernel|kernel_bu_ecole_classe_niveau');
    $classLevelDAO       = _ioDAO ('kernel_bu_classe_niveau');
    
	  $classSchoolLevels   = $classSchoolLevelDAO->getByClass ($ppo->nodeId);
	  
    $ppo->levelNames = array ();
    $ppo->levelIds   = array ();
    
    foreach ($classSchoolLevels as $classSchoolLevel) {
      
      $level             = $classLevelDAO->get ($classSchoolLevel->niveau);
      $ppo->levelNames[] = $level->niveau_court;
      $ppo->levelIds[]   = $level->id_n;
    }
	  
	  $studentDAO = _ioDAO ('kernel|kernel_bu_ele');
	  $ppo->students = $studentDAO->findStudentsForAssignment ($ppo->nodeId, $type_ref, $ppo->listFilters);
    
    $ppo->TITLE_PAGE = CopixConfig::get('gestionautonome|moduleTitle');
    
    // Breadcrumbs
    $nodeInfos = Kernel::getNodeInfo ($ppo->nodeType, $ppo->nodeId);
    
	  $breadcrumbs      = Kernel::generateBreadcrumbs ($nodeInfos);
	  $breadcrumbs[]    = array('txt' => 'Ajout d\'un élève existant');
	  
	  $ppo->breadcrumbs = Kernel::PetitPoucet($breadcrumbs,' &raquo; ');
	  
	  return _arPPO ($ppo, 'add_existing_student.tpl');
	}
	
	public function processAddExistingPersonnel () {
	  
	  $ppo = new CopixPPO ();
	  
    $ppo->TITLE_PAGE = CopixConfig::get('gestionautonome|moduleTitle');
    
	  $ppo->nodeId   = _request ('parentId', null);
	  $ppo->nodeType = _request ('parentType', null);
	  $ppo->role     = _request ('role', null);
	  
	  switch ($ppo->role) {
	    
	    case 1:
	      _currentUser()->assertCredential('module:classroom|'.$ppo->nodeId.'|teacher|create@gestionautonome');
	      break;
	    case 2:
	      _currentUser()->assertCredential('module:school|'.$ppo->nodeId.'|principal|create@gestionautonome');
	      break;
	    case 3:
  	    _currentUser()->assertCredential('module:school|'.$ppo->nodeId.'|administration_staff|create@gestionautonome');
  	    break;
  	  case 4:
    	  _currentUser()->assertCredential('module:city|'.$ppo->nodeId.'|city_agent|create@gestionautonome');
    	  break;
    	case 5:
        _currentUser()->assertCredential('module:cities_group|'.$ppo->nodeId.'|city_group_agent|create@gestionautonome');
        break;
	  }
	  
	  if (is_null ($ppo->nodeId) || is_null ($ppo->nodeType) || is_null ($ppo->role)) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get('gestionautonome||showTree')));
	  }                                             
	  
	  $nodeInfos = Kernel::getNodeInfo ($ppo->nodeType, $ppo->nodeId, true);
    
    // Breadcrumbs
	  $breadcrumbs      = Kernel::generateBreadcrumbs ($nodeInfos);
	  $breadcrumbs[]    = array('txt' => 'Ajout d\'une personne existante');
	  $ppo->breadcrumbs = Kernel::PetitPoucet($breadcrumbs,' &raquo; ');
	  
	  _sessionSet ('gestionautonome|addExisting', array ());                                                                         

	  $ppo->listFilters = array ();
	  
	  switch ($ppo->role) {
	    
	    case '1' :
			case '2' :
				$ppo->listFilters['user_type'] = 'USER_ENS';
				break;
			case '3' :
			  $ppo->listFilters['user_type'] = 'USER_ADM';
				break;
			case '4' :
			case '5' :
  		  $ppo->listFilters['user_type'] = 'USER_VIL';
  		  break;
	  }

	  switch ($ppo->nodeType) {
			case 'BU_GRVILLE' :
        $type_ref  = 'GVILLE';
        $ppo->listFilters['groupcity'] = $nodeInfos['id'];
				break;
		  case 'BU_VILLE' :
        $type_ref  = 'VILLE';
        $ppo->listFilters['groupcity'] = $nodeInfos['ALL']->vil_id_grville;
        $ppo->listFilters['city']      = $nodeInfos['id'];
  			break;
  		case 'BU_ECOLE' :
        $type_ref  = 'ECOLE';
        $ppo->listFilters['groupcity'] = $nodeInfos['ALL']->vil_id_grville;
        $ppo->listFilters['city']      = $nodeInfos['ALL']->vil_id_vi;
        $ppo->listFilters['school']    = $nodeInfos['id'];
    		break;		
    	case 'BU_CLASSE' :
        $type_ref  = 'CLASSE';                            
        $ppo->listFilters['groupcity'] = '1';
        $ppo->listFilters['city']      = $nodeInfos['ALL']->eco_id_ville;
        $ppo->listFilters['school']    = $nodeInfos['ALL']->eco_numero;
        $ppo->listFilters['class']     = $nodeInfos['id'];
      	break;
		}
		
		$ppo->listFilters['withAssignment'] = true;
 
		_sessionSet ('gestionautonome|addExisting', $ppo->listFilters);

	  $personDAO = _ioDAO ('kernel|kernel_bu_personnel');
	  $ppo->persons = $personDAO->findPersonnelsForAssignment ($ppo->nodeId, $type_ref, $ppo->listFilters);

	  return _arPPO ($ppo, 'add_existing_personnel.tpl');
	}              
	
	public function processFilterExistingPersonnel () {
	  
	  $ppo = new CopixPPO ();
	  
	  $ppo->TITLE_PAGE = CopixConfig::get('gestionautonome|moduleTitle');
	  
	  $ppo->nodeId   = _request ('parentId', null);
	  $ppo->nodeType = _request ('parentType', null);
	  $ppo->role     = _request ('role', null);
	  
	  switch ($ppo->role) {
	    
	    case 1:
	      _currentUser()->assertCredential('module:classroom|'.$ppo->nodeId.'|teacher|create@gestionautonome');
	      break;
	    case 2:
	      _currentUser()->assertCredential('module:school|'.$ppo->nodeId.'|principal|create@gestionautonome');
	      break;
	    case 3:
  	    _currentUser()->assertCredential('module:school|'.$ppo->nodeId.'|administration_staff|create@gestionautonome');
  	    break;
  	  case 4:
    	  _currentUser()->assertCredential('module:city|'.$ppo->nodeId.'|city_agent|create@gestionautonome');
    	  break;
    	case 5:
        _currentUser()->assertCredential('module:cities_group|'.$ppo->nodeId.'|city_group_agent|create@gestionautonome');
        break;
	  }
	  
	  if (is_null ($ppo->nodeId) || is_null ($ppo->nodeType)) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get('gestionautonome||showTree')));
	  }
	  
	  $nodeInfos = Kernel::getNodeInfo ($ppo->nodeType, $ppo->nodeId, true);
    
    // Breadcrumbs
	  $breadcrumbs      = Kernel::generateBreadcrumbs ($nodeInfos);
	  $breadcrumbs[]    = array('txt' => 'Ajout d\'une personne existante');
	  $ppo->breadcrumbs = Kernel::PetitPoucet($breadcrumbs,' &raquo; ');
	  
	  switch ($ppo->nodeType) {
			case 'BU_GRVILLE' :
        $type_ref  = 'GVILLE';
				break;
		  case 'BU_VILLE' :
        $type_ref  = 'VILLE';
  			break;
  		case 'BU_ECOLE' :
        $type_ref  = 'ECOLE';
    		break;		
    	case 'BU_CLASSE' :
        $type_ref  = 'CLASSE';
      	break;
		}
	  
	  $ppo->listFilters = _sessionGet ('gestionautonome|addExisting');                                                                         
	  if (!is_array ($ppo->listFilters)) {
	    
	    $ppo->listFilters = array ();
	  }
	  
    $ppo->listFilters['withAssignment'] = _request ('withAssignment', null);
    $ppo->listFilters['lastname']       = _request ('lastname', null);
    $ppo->listFilters['firstname']      = _request ('firstname', null);
    $ppo->listFilters['groupcity']      = _request ('groupcity', null);
    $ppo->listFilters['city']           = _request ('city', null);
    $ppo->listFilters['school']         = _request ('school', null);
    $ppo->listFilters['class']          = _request ('class', null);
    
    _sessionSet ('gestionautonome|addExisting', $ppo->listFilters);
    
    $personDAO = _ioDAO ('kernel|kernel_bu_personnel');
	  $ppo->persons = $personDAO->findPersonnelsForAssignment ($ppo->nodeId, $type_ref, $ppo->listFilters);

	  return _arPPO ($ppo, 'add_existing_personnel.tpl');
	}
	
	public function processValidateExistingPersonsAdd () {
	  
	  $ppo = new CopixPPO ();
	  
	  $ppo->TITLE_PAGE = CopixConfig::get('gestionautonome|moduleTitle');
	  
	  $ppo->nodeId      = _request ('id_node', null);
	  $ppo->nodeType    = _request ('type_node', null);
	  $ppo->role        = _request ('role', null);
	  $ppo->personIds   = _request ('personIds', null); 
	  
	  switch ($ppo->role) {
	    
	    case 1:
	      _currentUser()->assertCredential('module:classroom|'.$ppo->nodeId.'|teacher|create@gestionautonome');
	      break;
	    case 2:
	      _currentUser()->assertCredential('module:school|'.$ppo->nodeId.'|principal|create@gestionautonome');
	      break;
	    case 3:
  	    _currentUser()->assertCredential('module:school|'.$ppo->nodeId.'|administration_staff|create@gestionautonome');
  	    break;
  	  case 4:
    	  _currentUser()->assertCredential('module:city|'.$ppo->nodeId.'|city_agent|create@gestionautonome');
    	  break;
    	case 5:
        _currentUser()->assertCredential('module:cities_group|'.$ppo->nodeId.'|city_group_agent|create@gestionautonome');
        break;
	  }
	  
	  if (is_null ($ppo->nodeId) || is_null ($ppo->nodeType)) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get('gestionautonome||showTree')));
	  }
	  
	  $nodeInfos = Kernel::getNodeInfo ($ppo->nodeType, $ppo->nodeId, true);

	  switch ($ppo->nodeType) {
			case 'BU_GRVILLE' :
        $type_ref  = 'GVILLE';
				break;
		  case 'BU_VILLE' :
        $type_ref  = 'VILLE';
  			break;
  		case 'BU_ECOLE' :
        $type_ref  = 'ECOLE';
    		break;		
    	case 'BU_CLASSE' :
        $type_ref  = 'CLASSE';
      	break;
		}
	  
	  $personEntityDAO = _ioDAO ('kernel|kernel_bu_personnel_entite');
   
	  if (is_array ($ppo->personIds) && !empty ($ppo->personIds)) {

	    foreach ($ppo->personIds as $personId) {

        if (!$personEntityDAO->getByIdReferenceAndType ($personId, $ppo->nodeId, $type_ref)) {

          // Création de l'association kernel_bu_personnel_entite
          $newPersonEntity = _record ('kernel|kernel_bu_personnel_entite');

          $newPersonEntity->pers_entite_id_per    = $personId;
          $newPersonEntity->pers_entite_reference = $ppo->nodeId;
          $newPersonEntity->pers_entite_type_ref  = $type_ref;
          $newPersonEntity->pers_entite_role      = $ppo->role;

          $personEntityDAO->insert ($newPersonEntity);
        }
        
        if ($type_ref == 'CLASSE' && !$personEntityDAO->getByIdReferenceAndType ($personId, $nodeInfos['ALL']->cla_ecole, 'ECOLE')) {

          // Création de l'association kernel_bu_personnel_entite ecole (pour les enseignants)
          $newPersonEntity = _record ('kernel|kernel_bu_personnel_entite');

          $newPersonEntity->pers_entite_id_per    = $personId;
          $newPersonEntity->pers_entite_reference = $nodeInfos['ALL']->cla_ecole;
          $newPersonEntity->pers_entite_type_ref  = 'ECOLE';
          $newPersonEntity->pers_entite_role      = $ppo->role;

          $personEntityDAO->insert ($newPersonEntity);
        }

        if ($type_ref == 'ECOLE' && $personEntityDAO->getByIdReferenceAndType ($personId, $nodeInfos['ALL']->eco_numero, 'ECOLE')) {

          // Mise à jour de l'affectation enseignant à l'école pour passage en directeur
          $personEntityDAO->updateRole ($personId, $nodeInfos['ALL']->eco_numero, 'ECOLE', $ppo->role);
        }
  	  }
	  }
	  
	  $ppo->listFilters = _sessionGet ('gestionautonome|addExisting');
	  if (!is_array ($ppo->listFilters)) {
	    
	    $ppo->listFilters = array ();
	  }
	  
	  $personDAO    = _ioDAO ('kernel|kernel_bu_personnel');
	  $ppo->persons = $personDAO->findPersonnelsForAssignment ($ppo->nodeId, $type_ref, $ppo->listFilters);
	  $ppo->save    = 1;

    // Breadcrumbs
	  $breadcrumbs      = Kernel::generateBreadcrumbs ($nodeInfos);
	  $breadcrumbs[]    = array('txt' => 'Ajout d\'une personne existante');
	  $ppo->breadcrumbs = Kernel::PetitPoucet($breadcrumbs,' &raquo; ');
	  
	  return _arPPO ($ppo, 'add_existing_personnel.tpl');
	}

	/**
	 * addMultipleStudents
	 *
	 * Ajout d'une liste d'élèves
	 * 
	 */	
	public function processAddMultipleStudents () {
	  
	  $ppo = new CopixPPO ();
	  
	  $ppo->nodeId   = _request ('parentId', null);
  	$ppo->nodeType = _request ('parentType', null);
  	
  	if (is_null ($ppo->nodeId) || is_null ($ppo->nodeType)) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get('gestionautonome||showTree')));
	  }
	  
	  $classroomDAO = _ioDAO ('kernel|kernel_bu_ecole_classe');
	  if (!$classroomDAO->get ($ppo->nodeId )) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get('gestionautonome||showTree')));
	  }

  	_currentUser()->assertCredential('module:classroom|'.$ppo->nodeId.'|student|create@gestionautonome');
  	
  	// Récupération de l'import par fichier
  	$ppo->import = _sessionGet ('modules|gestionautonome|studentsImport');
  	_sessionSet ('modules|gestionautonome|studentsImport', null);
  	
  	// RAZ des sessions
  	_sessionSet ('gestionautonome|addMultipleStudents', array ());
  	_sessionSet ('gestionautonome|addMultipleStudents|success', array ());
  	_sessionSet ('gestionautonome|addMultipleStudents|error', array ());
  	
  	// Traitement du formulaire
    if (CopixRequest::isMethod ('post')) {
      
      $ppo->errors = array ();
      switch (CopixUploadedFile::getError ('filename')) {
  			case UPLOAD_ERR_OK:
  			  $file = CopixUploadedFile::get ('filename');
  			  break;
  			case UPLOAD_ERR_INI_SIZE:
  			case UPLOAD_ERR_FORM_SIZE:
  			  $ppo->errors[] = 'Fichier trop volumineux';
  			  break;
  			case UPLOAD_ERR_NO_TMP_DIR:
  			case UPLOAD_ERR_CANT_WRITE:
  			case UPLOAD_ERR_PARTIAL:
  			case UPLOAD_ERR_EXTENSION:
  			  $ppo->errors[] = 'Une erreur est survenue';
  			  break;
  			case UPLOAD_ERR_NO_FILE:
  			  $ppo->errors[] = 'Vous devez sélectionner un fichier';
  		}
  		
  		if (empty ($ppo->errors)) {
  		  
  		  // Lecture du fichier
  		  if (($handle = fopen($file->getTempPath (), 'r')) === false) {
  		    
  		    $ppo->errors[] = 'Impossible de lire le fichier';
  		  }
  		  else {
  		    
  		    $infos = array ();
  		    
  		    // Vérification de la ligne d'entête
  		    if (($data = fgetcsv($handle, 0, ',', '"')) === false || !in_array(count($data), array(12, 8, 4, 3))) {

  		      $ppo->errors[] = 'Fichier invalide';
  		    }
  		    else {

  		      // Prend on en compte la première ligne ?
  		      if ($data[0] != 'Nom') {
  		        
  		        $infos[] = implode (',', $data);
  		      }
  		      
  		      // Lecture du reste du fichier
  		      while (($data = fgetcsv($handle, 0, ',', '"')) !== false) {
              
              if (!in_array(count($data), array(12, 8, 4, 3))) {
                
                $ppo->errors[] = 'Fichier invalide';
                break;
              }
              
              $infos[] = implode(',', $data);
    		    }
            
    		    // Redirection
    		    if (empty ($ppo->errors)) {
    		      
    		      _sessionSet ('modules|gestionautonome|studentsImport', implode ("\n", $infos));
      		    return _arRedirect (CopixUrl::get ('gestionautonome||AddMultipleStudents', array ('parentId' => $ppo->nodeId, 'parentType' => $ppo->nodeType))); 
    		    }
  		    }
  		  }
  		}
    }
    
    // Breadcrumbs
    $ppo->nodeInfos = Kernel::getNodeInfo ($ppo->nodeType, $ppo->nodeId, true);
    
	  $breadcrumbs      = Kernel::generateBreadcrumbs ($ppo->nodeInfos);
	  $breadcrumbs[]    = array('txt' => 'Ajout d\'une liste d\'élèves');
	  $ppo->breadcrumbs = Kernel::PetitPoucet($breadcrumbs,' &raquo; ');
  	
  	$ppo->TITLE_PAGE = CopixConfig::get('gestionautonome|moduleTitle');

	  return _arPPO ($ppo, 'add_multiple_students.tpl');
	}
	
	public function processValidateMultipleStudentsAdd () {
	  
	  $ppo = new CopixPPO ();
	  
	  $ppo->nodeId   = _request ('id_parent', null);
  	$ppo->nodeType = _request ('type_parent', null);
  	
  	if (is_null ($ppo->nodeId) || is_null ($ppo->nodeType)) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get('gestionautonome||showTree')));
	  }
	  
	  $classroomDAO = _ioDAO ('kernel|kernel_bu_ecole_classe');
	  if (!$classroomDAO->get ($ppo->nodeId )) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get('gestionautonome||showTree')));
	  }

  	_currentUser()->assertCredential('module:classroom|'.$ppo->nodeId.'|student|create@gestionautonome');
  	
  	$liste = _request ('liste', null);
  	$logins = array ();

    if (!is_null ($liste)) {
      
      $lines = explode("\n", nl2br($liste));

      $ppo->students = array ();

      foreach ($lines as $key => $line) {

         $datas = explode (',', $line);

         // Données de l'élève : nom - prénom - sexe - DDN
         if (isset ($datas[0])) {

           $ppo->students[$key]['lastname'] = $datas[0];
         }

         if (isset ($datas[1])) {

           $ppo->students[$key]['firstname'] = $datas[1];
         }

         if (isset ($datas[2])) {

           if (strpos($datas[2], 'M') === false) {

             $ppo->students[$key]['gender'] = 2;
           }
           else {

            $ppo->students[$key]['gender'] = 1;
           }
         }

         if (isset ($datas[3])) {

           $ppo->students[$key]['birthdate'] = $datas[3];
         }

         // Données des responsables : nom - prénom - sexe - relation
         $continue = true;
         $keyPerson=0;
         $cpt = 4;

         while ($continue) {

           if (isset($datas[$cpt]) && !empty($datas[$cpt])) {

             switch ($cpt - (4*($keyPerson+1))) {

               case 0:
                 $ppo->students[$key]['person'][$keyPerson]['lastname'] = $datas[$cpt];
                 break;
               case 1:       
                 $ppo->students[$key]['person'][$keyPerson]['firstname'] = $datas[$cpt];
                 break; 
               case 2:
                 if (substr($datas[$cpt], 0, 1) == 'M') {

                   $ppo->students[$key]['person'][$keyPerson]['gender'] = 1;
                 }
                 else {

                   $ppo->students[$key]['person'][$keyPerson]['gender'] = 2;
                 }
                 break;
               case 3: 
                 $ppo->students[$key]['person'][$keyPerson]['nom_pa'] = strip_tags(trim($datas[$cpt]));

                 switch (strip_tags(trim($datas[$cpt]))) {
                   case 'MERE':
                     $id_par = 1;
                     break;
                   case 'PERE':
                     $id_par = 2;
                     break;
                   case 'FRERE':
                     $id_par = 3;
                     break;
                   case 'SOEUR':
                     $id_par = 4;
                     break;
                   case 'GRAND-PERE':
                     $id_par = 5;
                     break;
                   case 'GRAND-MERE':
                     $id_par = 6;
                     break;
                   case 'ONCLE':
                     $id_par = 7;
                     break;
                   case 'TANTE':
                     $id_par = 8;
                     break;
                   case 'COLLATERAUX':
                     $id_par = 9;
                     break;
                   case 'TUTEUR':
                     $id_par = 10;
                     break;
                   case 'INCONNU':
                     $id_par = 11;
                     break;
                 }

                 $ppo->students[$key]['person'][$keyPerson]['id_par'] = $id_par;
                 break;
               }
           }
           else {

             $continue = false;
           }

           if (($cpt+1) % 4 == 0) {

             // Génération des logins avec exclusion des logins déjà générés pour cet import
             $ppo->students[$key]['person'][$keyPerson]['login']    = Kernel::createLogin (array ('nom' => $ppo->students[$key]['person'][$keyPerson]['lastname'], 'prenom' => $ppo->students[$key]['person'][$keyPerson]['firstname'], 'type' => 'USER_RES'), $logins);
             $ppo->students[$key]['person'][$keyPerson]['password'] = Kernel::createPasswd ();
             
             // On stocke le login pour ne pas proposer de doublon lors de la génération des logins
             $logins[] = $ppo->students[$key]['person'][$keyPerson]['login'];
             
             $keyPerson++;
           }       

           $cpt++; 
         }
         
         // Génération des logins avec exclusion des logins déjà générés pour cet import
         $ppo->students[$key]['login']    = Kernel::createLogin (array ('nom' => $ppo->students[$key]['lastname'], 'prenom' => $ppo->students[$key]['firstname'], 'type' => 'USER_ELE'), $logins);
         $ppo->students[$key]['password'] = Kernel::createPasswd ();
         
         // On stocke le login pour ne pas proposer de doublon lors de la génération des logins
         $logins[] = $ppo->students[$key]['login'];
      }

      // Mise en session des informations récupérées
      _sessionSet ('gestionautonome|addMultipleStudents', $ppo->students);

      // Récupération des niveaux de la classe
      $classSchoolLevelDAO = _ioDAO ('kernel|kernel_bu_ecole_classe_niveau');
      $classLevelDAO       = _ioDAO ('kernel_bu_classe_niveau');

      $classSchoolLevels   = $classSchoolLevelDAO->getByClass ($ppo->nodeId);

      $ppo->levelNames = array ();
      $ppo->levelIds   = array ();

      foreach ($classSchoolLevels as $classSchoolLevel) {

        $level             = $classLevelDAO->get ($classSchoolLevel->niveau);
        $ppo->levelNames[] = $level->niveau_court;
        $ppo->levelIds[]   = $level->id_n;
      }
    }
    
    // Breadcrumbs
    $nodeInfos = Kernel::getNodeInfo ($ppo->nodeType, $ppo->nodeId, true);
    
	  $breadcrumbs      = Kernel::generateBreadcrumbs ($nodeInfos);
	  $breadcrumbs[]    = array('txt' => 'Ajout d\'une liste d\'élèves');
	  $ppo->breadcrumbs = Kernel::PetitPoucet($breadcrumbs,' &raquo; ');
  	
  	$ppo->TITLE_PAGE = CopixConfig::get('gestionautonome|moduleTitle');
  	
	  return _arPPO ($ppo, 'add_multiple_students_listing.tpl');
	}
	
	public function processValidateMultipleStudentsListing () {
	  
	  $ppo = new CopixPPO ();

	  // Récupération des paramètres
	  $ppo->nodeId   = _request ('id_node', null);
  	$ppo->nodeType = _request ('type_node', null);
  	
  	if (is_null ($ppo->nodeId) || is_null ($ppo->nodeType)) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get('gestionautonome||showTree')));
	  }
	  
	  $classroomDAO = _ioDAO ('kernel|kernel_bu_ecole_classe');
	  if (!$classroomDAO->get ($ppo->nodeId )) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get('gestionautonome||showTree')));
	  }
	  
  	_currentUser()->assertCredential('module:classroom|'.$ppo->nodeId.'|student|create@gestionautonome');

  	// Récupération des informations du formulaire                                             
  	$keys      = _request ('keys', array ());       // Elèves de la liste à créer (checkbox de confirmation)
  	$logins    = _request ('logins', array ());     // Logins des élèves (possibilité de changer le login généré)
  	$passwords = _request ('passwords', array ());  // Passwords des élèves (possibilité de changer le mot de passe généré)
  	$levels    = _request ('levels', array ());     // Niveau des élèves dans la classe
                                  
    // Récupération des élèves déjà créés en session
  	if (!$ppo->studentsSuccess = _sessionGet ('gestionautonome|addMultipleStudents|success')) {
  	  
  	  $ppo->studentsSuccess = array ();
  	}
  	
  	// RAZ des créations en erreur
  	_sessionSet ('gestionautonome|addMultipleStudents|error', array());
  	
  	// DAO 
  	$studentDAO              = _ioDAO ('kernel_bu_eleve');
    $studentRegistrationDAO  = _ioDAO ('kernel|kernel_bu_eleve_inscription');
    $studentAdmissionDAO     = _ioDAO ('kernel|kernel_bu_eleve_admission');
    $studentAssignmentDAO    = _ioDAO ('kernel_bu_eleve_affectation');
    $dbuserDAO               = _ioDAO ('kernel|kernel_copixuser'); 
    $dbLinkDAO               = _ioDAO ('kernel_link_bu2user');
    $classDAO                = _ioDAO ('kernel|kernel_bu_ecole_classe');
    $personDAO               = _ioDAO ('kernel_bu_responsable');
    $personLinkDAO           = _ioDAO ('kernel_bu_responsables'); 
    $schoolClassLevelDAO     = _ioDAO ('kernel|kernel_bu_ecole_classe_niveau');
  	
  	$node_infos = Kernel::getNodeInfo ($ppo->nodeType, $ppo->nodeId, false);
  	
  	// Récupération des élèves en création
    $students = _sessionGet ('gestionautonome|addMultipleStudents');
    
    // Récupération des données nécessaires à l'ajout des enregistrements inscription / adhésion / admission
    $class             = $classDAO->get ($ppo->nodeId); 
    $schoolClassLevels = $schoolClassLevelDAO->getByClass ($ppo->nodeId);
    $classType         = $schoolClassLevels[0]->type;
    $schoolId          = $class->ecole;
    
    // Récupération de l'année scolaire
    if (is_null($grade = _sessionGet('grade'))) {
      
      $grade = Kernel::getAnneeScolaireCourante ()->id_as;
    }
    
    // Boucle sur les élèves de la liste à créer
    foreach ($keys as $key) {
      
      // La création n'est possible que si le login est disponible
      if (!is_null ($logins[$key]) && !is_null ($passwords[$key]) && Kernel::isLoginAvailable ($logins[$key])) {

        $students[$key]['login']    = $logins[$key];                                              // Récupération des logins pour les élèves en création
        $students[$key]['password'] = $passwords[$key];                                           // Récupération des passwords pour les élèves en création
        $students[$key]['level']    = $levels[$key];                                              // Récupération des niveaux pour les élèves en création 
  			$students[$key]['type_nom'] = Kernel::Code2Name('USER_ELE');
  			$students[$key]['node_nom'] = Kernel::Code2Name($ppo->nodeType)." ".$node_infos['nom'];
         
        // Création de l'élève
        $ppo->student = _record ('kernel_bu_eleve');

        $ppo->student->numero       = '';                        
        $ppo->student->nom          = $students[$key]['lastname'];
        $ppo->student->prenom1      = $students[$key]['firstname'];
        $ppo->student->id_sexe      = $students[$key]['gender'];             
        $ppo->student->date_nais    = (isset ($students[$key]['birthdate'])) ? CopixDateTime::dateToYYYYMMDD (strip_tags(trim($students[$key]['birthdate']))) : '';
        $ppo->student->flag         = 0;
        $ppo->student->ele_last_update = CopixDateTime::timestampToYYYYMMDDHHIISS (time ());
        
        $studentDAO->insert ($ppo->student);
        
        $students[$key]['id'] = $ppo->student->numero;
        
        // Création du compte dbuser
        $dbuser = _record ('kernel|kernel_copixuser');
        
        $dbuser->login_dbuser    = $students[$key]['login'];
        $dbuser->password_dbuser = md5 ($students[$key]['password']);
        $dbuser->email_dbuser    = '';
        $dbuser->enabled_dbuser  = 1;
        
        $dbuserDAO->insert ($dbuser);
        
        // Création du link bu2user
        $dbLink = _record ('kernel_link_bu2user');
        
        $dbLink->user_id = $dbuser->id_dbuser;
        $dbLink->bu_type = 'USER_ELE';
        $dbLink->bu_id   = $ppo->student->idEleve;
        
        $dbLinkDAO->insert ($dbLink);
        
        // Inscription de l'élève dans l'école
        $studentRegistration = _record ('kernel|kernel_bu_eleve_inscription');
        
        $studentRegistration->eleve                   = $ppo->student->idEleve;
        $studentRegistration->annee_scol              = $grade;
        $studentRegistration->date_preinscript        = CopixDateTime::timestampToYYYYMMDD (time ());
        $studentRegistration->date_effet_preinscript  = CopixDateTime::timestampToYYYYMMDD (time ());
        $studentRegistration->date_inscript           = CopixDateTime::timestampToYYYYMMDD (time ());
        $studentRegistration->date_effet_inscript     = CopixDateTime::timestampToYYYYMMDD (time ());
        $studentRegistration->etablissement           = $schoolId;
        $studentRegistration->etablissement_refus     = 0;
        $studentRegistration->id_niveau               = $students[$key]['level'];
        $studentRegistration->id_typ_cla              = $classType;
        $studentRegistration->vaccins_aj              = 0;
        $studentRegistration->attente                 = 0;
        $studentRegistration->derogation_dem          = 0; 
        $studentRegistration->temporaire              = 0;
        $studentRegistration->current_inscr           = 1; 
        
        $studentRegistrationDAO->insert ($studentRegistration);
        
        // Admission de l'élève dans l'école
        $studentAdmission = _record ('kernel|kernel_bu_eleve_admission');
        
        $studentAdmission->admission_eleve          = $ppo->student->idEleve;
        $studentAdmission->admission_etablissement  = $schoolId;
        $studentAdmission->admission_annee_scol     = $grade;
        $studentAdmission->admission_id_niveau      = $students[$key]['level'];
        $studentAdmission->admission_etat_eleve     = 1;
        $studentAdmission->admission_date           = CopixDateTime::timestampToYYYYMMDD (time ());
        $studentAdmission->admission_date_effet     = CopixDateTime::timestampToYYYYMMDD (time ());
        $studentAdmission->admission_code_radiation = '';
        $studentAdmission->admission_previsionnel   = '';
        
        $studentAdmissionDAO->insert ($studentAdmission);
        
        // Affectation de l'élève dans les classes
        $studentAssignment = _record ('kernel_bu_eleve_affectation');
        
        $studentAssignment->eleve           = $ppo->student->idEleve;
        $studentAssignment->annee_scol      = $grade;
        $studentAssignment->classe          = $ppo->nodeId;
        $studentAssignment->niveau          = $students[$key]['level'];
        $studentAssignment->dateDebut       = CopixDateTime::timestampToYYYYMMDD (time ());
        $studentAssignment->current         = 1;
        $studentAssignment->previsionnel_cl = 0;
        
        $studentAssignmentDAO->insert ($studentAssignment);
        
        // Récupération des responsables sélectionnés pour l'élève
        $personsKeys      = _request ('person-keys'.$key, array ());
        $personsLogins    = _request ('logins'.$key, array ());        // Récupération des logins des responsables de l'élève
        $personsPasswords = _request ('passwords'.$key, array ());  // Récupération des passwords des responsables de l'élève
        
        foreach ($personsKeys as $personKey) {

          // La création du responsable n'est possible que si le login est disponible
          if (!is_null ($personsLogins[$personKey]) && !is_null ($personsPasswords[$personKey]) && Kernel::isLoginAvailable ($personsLogins[$personKey])) {
            
            $students[$key]['person'][$personKey]['login']    = $personsLogins[$personKey];
            $students[$key]['person'][$personKey]['password'] = $personsPasswords[$personKey];
      			$students[$key]['person'][$personKey]['type_nom'] = Kernel::Code2Name('USER_RES');
      			$students[$key]['person'][$personKey]['node_nom'] = Kernel::Code2Name($ppo->nodeType)." ".$node_infos['nom'];
            
            // Création du responsable
            $ppo->person = _record ('kernel_bu_responsable');

            $ppo->person->nom        = $students[$key]['person'][$personKey]['lastname'];
            $ppo->person->prenom1    = $students[$key]['person'][$personKey]['firstname'];
            $ppo->person->id_sexe    = $students[$key]['person'][$personKey]['gender'];
          
            $personDAO->insert ($ppo->person);
            
             $students[$key]['person'][$personKey]['id'] = $ppo->person->numero;
            
            // Création du compte dbuser
            $dbuser = _record ('kernel|kernel_copixuser');

            $dbuser->login_dbuser    = $students[$key]['person'][$personKey]['login'];
            $dbuser->password_dbuser = md5 ($students[$key]['person'][$personKey]['password']);
            $dbuser->email_dbuser    = '';
            $dbuser->enabled_dbuser  = 1;

            $dbuserDAO->insert ($dbuser);

            // Création du link bu2user
            $dbLink = _record ('kernel_link_bu2user');

            $dbLink->user_id = $dbuser->id_dbuser;
            $dbLink->bu_type = 'USER_RES';
            $dbLink->bu_id   = $ppo->person->numero;

            $dbLinkDAO->insert ($dbLink);

            // Création de l'association personne->rôle
            $newPersonLink = _record ('kernel_bu_responsables');

        		$newPersonLink->id_beneficiaire   = $ppo->student->idEleve; 
        		$newPersonLink->type_beneficiaire = 'eleve';
        		$newPersonLink->id_responsable    = $ppo->person->numero;
        		$newPersonLink->type              = 'responsable';
        		$newPersonLink->auth_parentale    = '0';
        		$newPersonLink->id_par            = $students[$key]['person'][$personKey]['id_par'];

        		$personLinkDAO->insert ($newPersonLink);
      		}
        }
        
        // Récupération de l'élève (et de ses responsables) dans le tableau des élèves créés avec succès
        $ppo->studentsSuccess[] = $students[$key];
      }
      
      // Si le login n'est pas disponible, la création n'est pas possible
      else {
        
        // Ajout de l'élève (et de ses responsables) dans le tableau des créations en erreur
        $ppo->studentsError[] = $students[$key];
      }
    }

    // Si des créations ont bien eu lieu, les mettre en session pour affichage de confirmation 
    if (!empty ($ppo->studentsSuccess)) {
      
      _sessionSet ('gestionautonome|addMultipleStudents|success', $ppo->studentsSuccess);
    }
    
    // Si des créations sont en erreur, les mettre en session pour possibilité de recréation après correction des erreurs
    if (!empty ($ppo->studentsError)) {
      
      _sessionSet ('gestionautonome|addMultipleStudents|error', $ppo->studentsError);
      $ppo->students = $ppo->studentsError;
      
      _sessionSet ('gestionautonome|addMultipleStudents', $ppo->studentsError);
      
      // Récupération des niveaux de la classe
      $classSchoolLevelDAO = _ioDAO ('kernel|kernel_bu_ecole_classe_niveau');
      $classLevelDAO       = _ioDAO ('kernel_bu_classe_niveau');

      $classSchoolLevels   = $classSchoolLevelDAO->getByClass ($ppo->nodeId);

      $ppo->levelNames = array ();
      $ppo->levelIds   = array ();

      foreach ($classSchoolLevels as $classSchoolLevel) {

        $level             = $classLevelDAO->get ($classSchoolLevel->niveau);
        $ppo->levelNames[] = $level->niveau_court;
        $ppo->levelIds[]   = $level->id_n;
      }
      
      // Flag pour affichage du message d'erreur
      $ppo->error = 1;
      
      // Breadcrumbs
      $nodeInfos = Kernel::getNodeInfo ($ppo->nodeType, $ppo->nodeId, true);

  	  $breadcrumbs      = Kernel::generateBreadcrumbs ($nodeInfos);
  	  $breadcrumbs[]    = array('txt' => 'Ajout d\'une liste d\'élèves');
  	  $ppo->breadcrumbs = Kernel::PetitPoucet($breadcrumbs,' &raquo; ');

    	$ppo->TITLE_PAGE = CopixConfig::get('gestionautonome|moduleTitle');
    	
      return _arPPO ($ppo, 'add_multiple_students_listing.tpl');
    }
    
    // Mise en session des comptes créés
		$passwordsList = _sessionGet ('modules|gestionautonome|passwordsList');
		if (!is_array ($passwordsList)) {

		  $passwordsList = array();
		}
    
    foreach ($ppo->studentsSuccess as $student) {
      
      $studentAr = array(
  		  'lastname'  => $student['lastname'],
  			'firstname' => $student['firstname'],
  			'login'     => $student['login'],
  			'password'  => $student['password'],
  			'bu_type'   => 'USER_ELE',
  			'bu_id'     => $student['id'],
  			'type_nom'  => $student['type_nom'],
  			'node_nom'  => $student['node_nom'],
  			'gender'    => $student['gender']
  		);
  		
      $passwordsList['USER_ELE'][$student['id']] = $studentAr;
      
      // Enregistrement des personnes
      if (!empty($student['person'])) {
        
        foreach ($student['person'] as $person) {

          $personAr = array(
      		  'lastname'  => $person['lastname'],
      			'firstname' => $person['firstname'],
      			'login'     => $person['login'],
      			'password'  => $person['password'],
      			'bu_type'   => 'USER_RES',
      			'bu_id'     => $person['id'],
      			'type_nom'  => $person['type_nom'],
      			'node_nom'  => $person['node_nom'],
      			'gender'    => $person['gender']
      		);

          $passwordsList['USER_RES'][$person['id']] = $personAr;
        }
      }
    }
    $passwordsList = _sessionSet ('modules|gestionautonome|passwordsList', $passwordsList);
    
		return _arRedirect (CopixUrl::get ('gestionautonome||showMultipleAccountsListing'));
	}
	
	public function processShowMultipleAccountsListing () {
	                                                                        
	  $ppo = new CopixPPO (); 

	  // Récupération des informations des comptes créés
	  $ppo->students = _sessionGet ('gestionautonome|addMultipleStudents|success'); 

    // Récupération du format de sortie demandé
	  if( !_request ('format') || trim (_request ('format')) == '' ) {
	    
			$format = "default";
		} 
		else {
		  
			$format = _request('format');
		} 
		
		// Breadcrumbs
    $nodeInfos = Kernel::getNodeInfo ($ppo->nodeType, $ppo->nodeId, true);

	  $breadcrumbs      = Kernel::generateBreadcrumbs ($nodeInfos);
	  $breadcrumbs[]    = array('txt' => 'Ajout d\'une liste d\'élèves');
	  $ppo->breadcrumbs = Kernel::PetitPoucet($breadcrumbs,' &raquo; ');

  	$ppo->TITLE_PAGE = CopixConfig::get('gestionautonome|moduleTitle');
  	
		// Sortie suivant le format demandé
		$tplResult = & new CopixTpl ();
		$tplResult->assign ('sessionDatas', $ppo->students);
		
	  switch ($format) {
			case 'default':
				return _arPPO ($ppo, 'multiple_accounts_listing.tpl');
			case 'html':
			  $result = $tplResult->fetch ('account_listing_html.tpl');
			  return _arContent ($result, array ('filename'=>'Logins-'.date('YmdHi').'.html', 'content-disposition'=>'inline', 'content-type'=>CopixMIMETypes::getFromExtension ('.html')));
			  break;
			case 'csv':
			  $result = $tplResult->fetch ('account_listing_csv.tpl');
			  return _arContent ($result, array ('filename'=>'Logins-'.date('YmdHi').'.csv', 'content-disposition'=>'attachement', 'content-type'=>CopixMIMETypes::getFromExtension ('.csv')));
			  break;
		}
	}
	
	// AJAX
	public function processGenerateLogin () {
	                              
	  $users_infos ['nom']    = _request ('lastname', null);
	  $users_infos ['prenom'] = _request ('firstname', null); 
	  $users_infos ['type']   = _request ('type', null);
                                             
	  echo Kernel::createLogin ($users_infos); 
	  
	  return _arNone ();
	}
	
	// AJAX
	public function processGeneratePassword () {
	  
	  echo Kernel::createPasswd ();
	  
	  return _arNone ();
	}
	
	public function processRefreshCityFilter () {
	                                  
	  $cityGroupId = _request ('city_group_id', null);
	  if (!is_null ($cityGroupId)) {
	    
	    $groupDAO = _ioDAO ('kernel|kernel_bu_groupe_villes');
	    if ($group = $groupDAO->get($cityGroupId)) {
	      
	      echo CopixZone::process ('gestionautonome|filterCity', array('city_group_id' => $cityGroupId));
	    }
	  }
    
    return _arNone ();
	}
	
	public function processRefreshSchoolFilter () {

	  $cityId = _request ('city_id', null);
	  if (!is_null ($cityId)) {
	    
	    $cityDAO = _ioDAO ('kernel|kernel_bu_ville');
	    if ($city = $cityDAO->get($cityId)) {
	      
	      echo CopixZone::process ('gestionautonome|filterSchool', array('city_id' => $cityId));
	    }
	  }
	  
    return _arNone ();
	}
	
	public function processRefreshClassFilter () {
	  
	  $schoolId = _request ('school_id', null);
	  if (!is_null ($schoolId)) {
	    
	    $schoolDAO = _ioDAO ('kernel|kernel_bu_ecole');
	    if ($school = $schoolDAO->get($schoolId)) {
	      
	      echo CopixZone::process ('gestionautonome|filterClass', array('school_id' => $schoolId));
	    }
	  }

    return _arNone ();
	}

	/**
	 * changeStudentsAffect
	 *
	 * Changer l'affectation de plusieurs élèves
	 * 
	 */	
	public function processChangeStudentsAffect () {
	  
	  $ppo = new CopixPPO (); 
	  
	  // Récupération des paramètres
	  $ppo->nodeId   = _request ('parentId', null);
	  $ppo->nodeType = _request ('parentType', null);
	  
	  _currentUser()->assertCredential('module:classroom|'.$ppo->nodeId.'|student|update@gestionautonome');
	  
	  if (is_null ($ppo->nodeId) || is_null ($ppo->nodeType)) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get('gestionautonome||showTree')));
	  }
	  
	  // Récupération des informations du noeud
	  $nodeInfos = Kernel::getNodeInfo ($ppo->nodeType, $ppo->nodeId, true);
    
    // DAO
    $classLevelDAO = _ioDAO ('kernel_bu_classe_niveau');
    $schoolClassDAO = _ioDAO ('kernel|kernel_bu_ecole_classe');
    $schoolClassLevelDAO = _ioDAO ('kernel|kernel_bu_ecole_classe_niveau');
    
    // ID de l'école
    $schoolId = $nodeInfos['ALL']->cla_ecole;                                               
    
    // Récupération des classes de l'école
    $classes = $schoolClassDAO->getBySchool ($schoolId);
    
    $ppo->levelNames = array ();
    $ppo->levelIds   = array ();
    
    foreach ($classes as $class) {
      
      $schoolClassLevels = $schoolClassLevelDAO->getByClass ($class->id);

      foreach ($schoolClassLevels as $schoolClassLevel) {

        $level             = $classLevelDAO->get ($schoolClassLevel->niveau);

        $ppo->levelNames[] = $level->niveau_court.' - '.$class->nom;
        $ppo->levelIds[]   = $level->id_n.'-'.$class->id;
      }
    }
    
    $ppo->levelNames[] = '-- quitte la classe (sans affectation définie) --';
    $ppo->levelIds[] = '0-0';
                    
	  $studentDAO = _ioDAO ('kernel|kernel_bu_ele');
	  $ppo->students = $studentDAO->getStudentsByClass ($ppo->nodeId);
    
    // Breadcrumbs
	  $breadcrumbs      = Kernel::generateBreadcrumbs ($nodeInfos);
	  $breadcrumbs[]    = array('txt' => 'Changer d\'affectation plusieurs élèves');
	  $ppo->breadcrumbs = Kernel::PetitPoucet($breadcrumbs,' &raquo; ');
	  
	  $ppo->TITLE_PAGE = CopixConfig::get('gestionautonome|moduleTitle');
	  
	  return _arPPO ($ppo, 'change_students_affect.tpl');
	}
	
	public function processValidateChangeStudentsAffect () {
	  
	  $ppo = new CopixPPO (); 
	  
	  // Récupération des paramètres
	  $ppo->nodeId   = _request ('id_node', null);
	  $ppo->nodeType = _request ('type_node', null); 
	  
	  _currentUser()->assertCredential('module:classroom|'.$ppo->nodeId.'|student|update@gestionautonome');
	  
	  if (is_null ($ppo->nodeId) || is_null ($ppo->nodeType)) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get('gestionautonome||showTree')));
	  }
	  
	  // DAO
	  $studentAdmissionDAO  = _ioDAO ('kernel_bu_eleve_admission');
    $studentAssignmentDAO = _ioDAO ('kernel|kernel_bu_ele_affect'); 
	  
	  // Récupération des informations du noeud
	  $nodeInfos = Kernel::getNodeInfo ($ppo->nodeType, $ppo->nodeId, true);

	  // ID de l'école
    $schoolId = $nodeInfos['ALL']->cla_ecole;
	  
	  $students   = _request ('students', null);
	  $newAffects = _request ('newAffects', null);
	  
	  // Récupération de l'année scolaire
    if (is_null($grade = _sessionGet('grade'))) {
      
      $grade = Kernel::getAnneeScolaireCourante ()->id_as;
    }
	  
	  foreach ($newAffects as $key => $newAffect) {
	    
	    // Si l'affectation change 
	    if ($newAffect != "") {
	    
	      // Récupération de l'élève
	      $studentId = $students[$key];                          
	      
	      // Récupération des données de la nouvelle affectation : classe et niveau
  	    $datas = explode ('-', $newAffect);
  	    $level = $datas[0];
  	    $class = $datas[1];

        // Si option : -- quitte la classe (sans affectation définie) --
  	    if ($class == 0 && $level == 0) {
  	      
  	      // Ajout d'un enregistrement de radiation
      	  $studentAdmission = _record ('kernel_bu_eleve_admission');

          $studentAdmission->eleve          = $studentId;
          $studentAdmission->etablissement  = $schoolId;
          $studentAdmission->annee_scol     = $grade;
          $studentAdmission->id_niveau      = '';
          $studentAdmission->etat_eleve     = 3;
          $studentAdmission->date           = CopixDateTime::timestampToYYYYMMDD (time ());
          $studentAdmission->date_effet     = CopixDateTime::timestampToYYYYMMDD (time ());
          $studentAdmission->code_radiation = '';
          $studentAdmission->previsionnel   = '';

          $studentAdmissionDAO->insert ($studentAdmission);

          // Passage du flag de l'affectation de l'élève à 0
          $studentAssignment = $studentAssignmentDAO->getByStudentAndClass ($studentId, $ppo->nodeId);
          if ($studentAssignment) {                            
  	        
  	        $studentAssignment->affect_current = 0;
            $studentAssignmentDAO->update ($studentAssignment);
  	      }
  	    }
  	    else {
  	      
  	      // Passage du flag de l'affectation de l'élève à 0 
  	      $studentAssignment = $studentAssignmentDAO->getByStudentAndClass ($studentId, $ppo->nodeId);
  	      if ($studentAssignment) {                            
  	        
  	        $studentAssignment->affect_current = 0;
            $studentAssignmentDAO->update ($studentAssignment);
  	      }
          
          // Affectation de l'élève dans sa nouvelle classe
          $newStudentAssignment = _record ('kernel|kernel_bu_ele_affect');

          $newStudentAssignment->affect_eleve       = $studentId;
          $newStudentAssignment->affect_annee_scol  = $grade;
          $newStudentAssignment->affect_classe      = $class;
          $newStudentAssignment->affect_niveau      = $level;
          $newStudentAssignment->affect_dateDebut   = CopixDateTime::timestampToYYYYMMDD (time ());
          $newStudentAssignment->affect_current     = 1;
          
          $studentAssignmentDAO->insert ($newStudentAssignment);
  	    }  
	    }
	  }
	  
	  // Mise en session du noeud courant
		_sessionSet ('current', array('node_type' => $ppo->nodeType, 'node_id' => $ppo->nodeId));
		
	  return _arRedirect (CopixUrl::get ('gestionautonome||showTree', array ('save' => 1)));
	}
	
	/**
	 * Ré-génération des mots de passe d'une classe
	 */
	public function processResetClassroomPasswords () {
	  
	  $ppo = new CopixPPO ();
	  
	  $ppo->nodeId = _request ('nodeId', null);
	  
	  // Récupération des paramètres
	  if (is_null ($ppo->nodeId)) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError', array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get('gestionautonome||showTree')));
	  }
	  
	  $classroomDAO = _ioDAO ('kernel|kernel_bu_ecole_classe');
	  if (!$classroom = $classroomDAO->get ($ppo->nodeId)) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError', array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get('gestionautonome||showTree')));
	  }
	  
	  // Contrôle des droits
    $ppo->hasCredentialTeacherUpdate = _currentUser()->testCredential ('module:classroom|'.$ppo->nodeId.'|teacher|update@gestionautonome');
    $ppo->hasCredentialStudentUpdate = _currentUser()->testCredential ('module:classroom|'.$ppo->nodeId.'|student|update@gestionautonome');
    $ppo->hasCredentialPersonInChargeUpdate = _currentUser()->testCredential ('module:classroom|'.$ppo->nodeId.'|person_in_charge|update@gestionautonome');
    
    if (!$ppo->hasCredentialPersonInChargeUpdate && !$ppo->hasCredentialStudentUpdate && !$ppo->hasCredentialPersonInChargeUpdate) {
      
      throw new CopixCredentialException ();
    }
    
    // Infos du noeud
    $nodeInfos = Kernel::getNodeInfo ('BU_CLASSE', $ppo->nodeId);
    
    // Compteur max
    $counters = array ();
    
    // Récupération des enseignants de la classe
    $ppo->teachers = array ();
    if ($ppo->hasCredentialTeacherUpdate) {
      
      $personnelDAO = _ioDAO ('kernel|kernel_bu_personnel');
      $ppo->teachers = $personnelDAO->findTeachersByClassroomId ($ppo->nodeId);
      $counters[] = count ($ppo->teachers);
    }
    
    // Récupération des élèves de la classe
    $ppo->students = array ();
    if ($ppo->hasCredentialStudentUpdate) {
      
      $studentDAO = _ioDAO ('kernel|kernel_bu_ele');
      $ppo->students = $studentDAO->getStudentsByClass ($ppo->nodeId);
      $counters[] = count ($ppo->students);
    }
    
    // Récupération des parents de la classe
    $ppo->personsInCharge = array ();
    if ($ppo->hasCredentialPersonInChargeUpdate) {
      
      $responsableDAO = _ioDAO ('kernel|kernel_bu_res');
      $ppo->personsInCharge = $responsableDAO->getParentsInClasse($ppo->nodeId);
      $counters[] = count ($ppo->personsInCharge);
    }
    
    // Compteur MAX
    $ppo->counter = max ($counters);
    
    // Breadcrumbs & titre
	  $breadcrumbs   = Kernel::generateBreadcrumbs ($nodeInfos);
	  $breadcrumbs[] = array('txt' => 'Re-génération des mots de passe');
	  
	  $ppo->breadcrumbs = Kernel::PetitPoucet($breadcrumbs,' &raquo; ');
	  
	  $ppo->TITLE_PAGE = CopixConfig::get('gestionautonome|moduleTitle');
	  
    // Traitement du formulaire
    if (CopixRequest::isMethod('post')) {
      
      $studentsIds = _request ('students', array ());
      $teachersIds = _request ('teachers', array ());
      $personsInChargeIds = _request ('personsInCharge', array ());
      
      $dbuserDAO = _ioDAO ('kernel|kernel_copixuser'); 
      
      // Mise en session globale des mots de passe
  		$passwordsList = _sessionGet ('modules|gestionautonome|passwordsList');
  		if (!is_array ($passwordsList)) {

  		  $passwordsList = array();
  		}
      
      $ppo->accounts = array ();
      
      foreach ($ppo->students as $student) {
        
        if (in_array ($student->idEleve, $studentsIds)) {
          
          if ($account = $dbuserDAO->getUserByBuIdAndBuType ($student->idEleve, 'USER_ELE')) {
            
            $password = Kernel::createPasswd ();
            $account->password_dbuser = md5 ($password);
            $dbuserDAO->update($account);
            
            // Mise en session de l'élève pour l'export des infos
            $studentAr = array(
        		  'lastname'  => $student->nom,
        			'firstname' => $student->prenom1,
        			'login'     => $account->login_dbuser,
        			'password'  => $password,
        			'bu_type'   => 'USER_ELE',
        			'bu_id'     => $student->idEleve,
        			'type_nom'  => Kernel::Code2Name('USER_ELE'),
        			'node_nom'  => Kernel::Code2Name('BU_CLASSE').' '.$nodeInfos['nom'],
        			'gender'    => $student->id_sexe,
        		);
        		
            $passwordsList['USER_ELE'][$student->idEleve] = $studentAr;
            $ppo->accounts[] = $studentAr;
          }
        }
      }
      
      foreach ($ppo->personsInCharge as $personInCharge) {
        
        if (in_array ($personInCharge->id, $personsInChargeIds)) {
          
          if ($account = $dbuserDAO->getUserByBuIdAndBuType ($personInCharge->id, 'USER_RES')) {
            
            $password = Kernel::createPasswd ();
            $account->password_dbuser = md5 ($password);
            $dbuserDAO->update($account);
            
            // Mise en session de la personne responsable pour l'export des infos
            $personAr = array(
        		  'lastname'  => $personInCharge->nom,
        			'firstname' => $personInCharge->prenom,
        			'login'     => $account->login_dbuser,
        			'password'  => $password,
        			'bu_type'   => 'USER_RES',
        			'bu_id'     => $personInCharge->id,
        			'type_nom'  => Kernel::Code2Name('USER_RES'),
        			'node_nom'  => Kernel::Code2Name('BU_CLASSE').' '.$nodeInfos['nom'],
        			'gender'    => $personInCharge->sexe,
        		);
        		
            $passwordsList['USER_RES'][$personInCharge->id] = $personAr;
            $ppo->accounts[] = $personAr;
          }
        }
      }
      
      foreach ($ppo->teachers as $teacher) {
        
        if (in_array ($teacher->numero, $teachersIds)) {
          
          if ($account = $dbuserDAO->getUserByBuIdAndBuType ($teacher->numero, 'USER_ENS')) {
            
            $password = Kernel::createPasswd ();
            $account->password_dbuser = md5 ($password);
            $dbuserDAO->update($account);

            // Mise en session de l'enseignant pour l'export des infos
            $teacherAr = array(
        		  'lastname'  => $teacher->nom,
        			'firstname' => $teacher->prenom1,
        			'login'     => $account->login_dbuser,
        			'password'  => $password,
        			'bu_type'   => 'USER_ENS',
        			'bu_id'     => $teacher->numero,
        			'type_nom'  => Kernel::Code2Name('USER_ENS'),
        			'node_nom'  => Kernel::Code2Name('BU_CLASSE').' '.$nodeInfos['nom'],
        			'gender'    => $personInCharge->id_sexe,
        		);
        		
            $passwordsList['USER_ENS'][$teacher->numero] = $teacherAr;
            $ppo->accounts[] = $teacherAr;
          }
        }
      }
      
      if (!empty ($ppo->accounts)) {
        
        _sessionSet ('modules|gestionautonome|createAccount', $ppo->accounts);
        _sessionSet ('modules|gestionautonome|passwordsList', $passwordsList);

        return _arRedirect (CopixUrl::get ('gestionautonome||showNewClassroomPasswords', array ('nodeId' => $ppo->nodeId)));
      }
      else {
        
        $ppo->error = 1;
      }
    }
	  
		return _arPPO ($ppo, 'reset_classroom_passwords.tpl');
	}
	
	/**
	 * Ré-génération des mots de passe d'une classe
	 */
	public function processShowNewClassroomPasswords () {
	  
	  $ppo = new CopixPPO ();
	  
	  $ppo->nodeId = _request ('nodeId', null);
	  
	  // Récupération des paramètres
	  if (is_null ($ppo->nodeId)) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError', array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get('gestionautonome||showTree')));
	  }
	  
	  $classroomDAO = _ioDAO ('kernel|kernel_bu_ecole_classe');
	  if (!$classroom = $classroomDAO->get ($ppo->nodeId)) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError', array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get('gestionautonome||showTree')));
	  }
	  
	  // Contrôle des droits
    $ppo->hasCredentialTeacherUpdate = _currentUser()->testCredential ('module:classroom|'.$ppo->nodeId.'|teacher|update@gestionautonome');
    $ppo->hasCredentialStudentUpdate = _currentUser()->testCredential ('module:classroom|'.$ppo->nodeId.'|student|update@gestionautonome');
    $ppo->hasCredentialPersonInChargeUpdate = _currentUser()->testCredential ('module:classroom|'.$ppo->nodeId.'|person_in_charge|update@gestionautonome');
    
    if (!$ppo->hasCredentialPersonInChargeUpdate && !$ppo->hasCredentialStudentUpdate && !$ppo->hasCredentialPersonInChargeUpdate) {
      
      throw new CopixCredentialException ();
    }
    
    // Infos du noeud
    $nodeInfos = Kernel::getNodeInfo ('BU_CLASSE', $ppo->nodeId);
    
    // Breadcrumbs & titre
	  $breadcrumbs   = Kernel::generateBreadcrumbs ($nodeInfos);
	  $breadcrumbs[] = array('txt' => 'Re-génération des mots de passe');
	  
	  $ppo->breadcrumbs = Kernel::PetitPoucet($breadcrumbs,' &raquo; ');
	  
	  $ppo->TITLE_PAGE = CopixConfig::get('gestionautonome|moduleTitle');
	  
	  // Récupération des comptes modifiés
	  $ppo->accounts = _sessionGet ('modules|gestionautonome|createAccount');
	  
	  return _arPPO ($ppo, 'show_new_classroom_passwords.tpl');
	}
	
	/**
	 * Affecte des élèves à une classe (passage d'année)
	 */
	public function processSetStudentsToClass () {
	  
	  $gradesDAO = _ioDAO ('kernel_bu_annee_scolaire');
	  $classroomDAO = _ioDAO ('kernel|kernel_bu_ecole_classe');
	  $studentDAO = _ioDAO ('kernel|kernel_bu_ele');
	  
	  $ppo = new CopixPPO ();
    
    // Récupération de la classe destination
	  $ppo->nodeId = _request ('nodeId');  	
  	if (is_null ($ppo->nodeId)) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError', array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get ('gestionautonome||showTree')));
	  }
	  if (!$ppo->destinationClassroom = $classroomDAO->get ($ppo->nodeId)) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError', array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get ('gestionautonome||showTree')));
	  }

    // Contrôle des droits
  	_currentUser()->assertCredential('module:classroom|'.$ppo->nodeId.'|student|create@gestionautonome'); 	
    
    // Si l'année source est précisée, on doit la prendre en compte
    $ppo->previousGrade = null;
    $gradeId = _request ('gradeId');
	  if (!is_null ($gradeId)) {
	    
	    if (!$ppo->previousGrade = $gradesDAO->get ($gradeId)) {

  	    return CopixActionGroup::process ('generictools|Messages::getError', array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get ('gestionautonome||showTree')));
  	  }
	  }

    // Récupération des années scolaires
    $c = _daoSp ();
    $c->orderBy ('id_as');
	  $ppo->grades = $gradesDAO->findBy ($c);
	  
	  // Détermination de l'année scolaire courante et antérieure
	  foreach ($ppo->grades as $key => $grade) {
	    
	    if ($grade->current) {
	      
	      $ppo->currentGrade = $grade;
	      if (is_null ($ppo->previousGrade)) {
	        
	        $ppo->previousGrade = isset ($ppo->grades[$key-1]) ? $ppo->grades[$key-1] : $grade;
	      }
	    }
	  }
	  
	  // Si la classe source est précisée
	  $ppo->sourceClassroom = null;
	  $sourceClassroomId = _request ('sourceClassroomId');
	  if (!is_null ($sourceClassroomId)) {
	    
	    if (!$ppo->sourceClassroom = $classroomDAO->get ($sourceClassroomId)) {

  	    return CopixActionGroup::process ('generictools|Messages::getError', array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get ('gestionautonome||showTree')));
  	  }
  	  
  	  // Si l'année scolaire est différente de celle de la classe source sélectionnée, erreur de cohérence
  	  if ($ppo->sourceClassroom->annee_scol != $ppo->previousGrade->id_as) {
  	    
  	    $ppo->sourceClassroom = null;
  	  }
	  }
	      
	  // Récupération des classes de destination
	  $ppo->destinationClassrooms = $classroomDAO->getBySchool ($ppo->destinationClassroom->ecole, $ppo->currentGrade->id_as);
	  
	  // Récupération des classes sources
	  $ppo->sourceClassrooms = $classroomDAO->getBySchool ($ppo->destinationClassroom->ecole, $ppo->previousGrade->id_as);
	  
	  // Récupération des élèves sans affectation
	  if (!is_null ($ppo->sourceClassroom)) {
	    
	    // Récupération des niveaux
	    $ppo->sourceLevels = $ppo->sourceClassroom->getLevels ();
	    $ppo->destinationLevels = $ppo->destinationClassroom->getLevels ();
	    
	    // Récupération des élèves sans affectation
	    $ppo->students = $studentDAO->findOldStudentsAssignmentsForNewAssignement ($ppo->sourceClassroom->id, $ppo->previousGrade->id_as, $ppo->currentGrade->id_as);
	  }
	  
	  $ppo->error = null;
	  // Validation du formulaire d'affectation
	  if (CopixRequest::isMethod('post')) {
	    
	    // Récupération des élèves et contrôle des données
	    $ids = _request ('ids', array ());
	    if (empty ($ids)) {
	      
	      $ppo->error = 'Vous devez sélectionner au moins 1 élève.';
	    }
	    foreach ($ids as $id) {
	      
	      $exists = false;
	      foreach ($ppo->students as $student) {
	        
	        if ($student->id == $id) {
	          
	          $exists = true;
	          // Le niveau est-il précisé ?
	          if (is_null (_request ('level_'.$id))) {
	            
	            $ppo->error = 'Une erreur est survenue.';
	          }
	          break;
	        }
	      }
	      
	      // ERREUR
        if (!$exists) {

          $ppo->error = 'Une erreur est survenue.';
          break;
        }
	    }
	    
	    if (!$ppo->error) {
	      
	      $studentAssignmentDAO = _ioDAO ('kernel_bu_eleve_affectation');
	      foreach ($ids as $id) {
	        
	        $studentAssignment = _record ('kernel_bu_eleve_affectation');
	        
          $studentAssignment->eleve           = $id;
          $studentAssignment->annee_scol      = $ppo->currentGrade->id_as;
          $studentAssignment->classe          = $ppo->destinationClassroom->id;
          $studentAssignment->niveau          = _request ('level_'.$id);
          $studentAssignment->dateDebut       = CopixDateTime::timestampToYYYYMMDD (time ());
          $studentAssignment->current         = 1;
          $studentAssignment->previsionnel_cl = 0;

          $studentAssignmentDAO->insert ($studentAssignment);
	      }
        
	      // Redirection
	      return _arRedirect (CopixUrl::get ('gestionautonome||showTree', array ('save' => 1)));
	    }
	  }
	  
    // Breadcrumbs
    $ppo->nodeInfos = Kernel::getNodeInfo ('BU_CLASSE', $ppo->nodeId, true);

	  $breadcrumbs      = Kernel::generateBreadcrumbs ($ppo->nodeInfos);
	  $breadcrumbs[]    = array('txt' => 'Affectation de classe');
	  $ppo->breadcrumbs = Kernel::PetitPoucet($breadcrumbs,' &raquo; ');
  	
  	$ppo->TITLE_PAGE = CopixConfig::get('gestionautonome|moduleTitle');

	  return _arPPO ($ppo, 'set_students_to_class.tpl');
	}
	
	/**
	 * AJAX ONLY ! - Rafraichi un sélecteur de classe
	 */
	public function processRefreshClassroomSelector () {
	  
	  $schoolId = _request ('schoolId');
	  $gradeId = _request ('gradeId');
	  
	  if (is_null ($schoolId) || is_null ($gradeId)) {
	    
	    return new CopixActionReturn (CopixActionReturn::HTTPCODE, array('Content-Type: text/plain; charset=utf-8', 'HTTP/1.1 404 Not found'), 'Une erreur est survenue');
	  }
	  
	  $classroomDAO = _ioDAO ('kernel|kernel_bu_ecole_classe');
	  $ppo->classrooms = $classroomDAO->getBySchool ($schoolId, $gradeId);
	  
	  $ppo->withEmpty = _request ('withEmpty', false);
	  
	  return _arPPO ($ppo, array ('template' => '_classrooms_selector_options.tpl', 'mainTemplate' => null));
	}
	
	/**
	 * AJAX ONLY ! - Rafraichi le sélecteur d'élève à assigner
	 */
	public function processRefreshStudentsToAssign () {
	  
	  $gradesDAO = _ioDAO ('kernel_bu_annee_scolaire');
	  $classroomDAO = _ioDAO ('kernel|kernel_bu_ecole_classe');
	  
	  $destinationClassroomId = _request ('destinationClassroomId');
	  if (!$destinationClassroom = $classroomDAO->get ($destinationClassroomId)) {
	    
	    return new CopixActionReturn (CopixActionReturn::HTTPCODE, array('Content-Type: text/plain; charset=utf-8', 'HTTP/1.1 404 Not found'), 'Une erreur est survenue');
	  }
    
	  $sourceClassroomId = _request ('sourceClassroomId');
	  if (!$sourceClassroom = $classroomDAO->get ($sourceClassroomId)) {

	    return new CopixActionReturn (CopixActionReturn::HTTPCODE, array('Content-Type: text/plain; charset=utf-8', 'HTTP/1.1 404 Not found'), 'Une erreur est survenue');
	  }
	  
    $previousGradeId = _request ('previousGradeId');
	  if (!$previousGrade = $gradesDAO->get ($previousGradeId)) {

	    return new CopixActionReturn (CopixActionReturn::HTTPCODE, array('Content-Type: text/plain; charset=utf-8', 'HTTP/1.1 404 Not found'), 'Une erreur est survenue');
	  }
	  
    $currentGradeId = _request ('currentGradeId');
	  if (!$currentGrade = $gradesDAO->get ($currentGradeId)) {

	    return new CopixActionReturn (CopixActionReturn::HTTPCODE, array('Content-Type: text/plain; charset=utf-8', 'HTTP/1.1 404 Not found'), 'Une erreur est survenue');
	  }
	  
	  return new CopixActionReturn (CopixActionReturn::HTTPCODE, array('Content-Type: text/html; charset=utf-8', 'HTTP/1.1 200 OK'), CopixZone::process ('gestionautonome|studentsToAssign', array ('destinationClassroom' => $destinationClassroom, 'sourceClassroom' => $sourceClassroom, 'previousGrade' => $previousGrade, 'currentGrade' => $currentGrade)));
	  
	  return _arPPO ($ppo, array ('template' => '_students_for_assignment.tpl', 'mainTemplate' => null));
	}
}