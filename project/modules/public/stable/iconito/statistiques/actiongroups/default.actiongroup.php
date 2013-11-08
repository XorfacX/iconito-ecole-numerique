<?php

_classInclude('statistiques|consolidatedstatisticfilter');
_classInclude('statistiques|apimapping');

/**
 * Actiongroup du module Statistiques
 *
 * @package Iconito
 * @subpackage Statistiques
 */
class ActionGroupDefault extends enicActionGroup
{
    public function beforeAction()
    {
//        _currentUser()->assertCredential('module:*||access|@statistiques');
        $this->addJs('js/jquery/jquery.visualize.js');
        $this->addJs('js/iconito/module_statistiques.js');
		$this->addCss('js/jquery/css/jquery.visualize.css');
		$this->addCss('js/jquery/css/jquery.visualize-light.css');
    }

    public function processIndex ()
    {
        $ppo = new CopixPPO ();
        $ppo->user = _currentUser();

        $ppo->TITLE_PAGE = CopixConfig::get('statistiques|moduleTitle');

        if (!$filter = $this->_getSessionConsolidationStatisticFilter()) {
            $filter = new ConsolidatedStatisticFilter();
            $this->_setSessionConsolidationStatisticFilter($filter);
        }

        if (_request('publishedFrom')) {
            //demande de mettre l'objet à jour en fonction des valeurs saisies dans le formulaire
            $this->_validFromForm($filter);
            $this->_setSessionConsolidationStatisticFilter($filter);
        }
        $ppo->filter = $filter;

        // Get vocabulary catalog to use
        $nodeVocabularyCatalogDAO = _ioDAO('kernel|kernel_i18n_node_vocabularycatalog');
        $ppo->vocabularyCatalog = $nodeVocabularyCatalogDAO->getCatalogForNode($ppo->nodeType, $ppo->nodeId);

        CopixHTMLHeader::addCSSLink (_resource("styles/module_agenda.css"));

        CopixHTMLHeader::addJSLink (_resource("js/jquery/jquery.ui.datepicker-fr.js"));

        if ($ppo->filter) {
          $ppo->stat = _request('stat','comptesEtConnexions'); // On affiche l'onglet Comptes par défaut si aucun n'est sélectionné
        }

        $ppo->mapping = new ApiMapping;

        $ppo->contexts = Kernel::getContextTree(true);

        $userExtras = _currentUser()->getExtras();
        $ppo->isAdmin = is_array($userExtras['link']);

        $ppo->userGroups = array();
        foreach (Kernel::getGroupsFromUserInfos(_currentUser()->getExtras()) as $group) {
            $ppo->userGroups[] = $group['type'].'|'.$group['id'];
        }

        return _arPPO($ppo, 'set_filter.tpl');
    }

    /**
    * R�cup�ration en session des param�tres de l'�v�nement en �dition
    * @access: private.
    */
    public function _getSessionConsolidationStatisticFilter ()
    {
        $inSession = _sessionGet ('modules|statistiques|filter');
        return ($inSession) ? unserialize ($inSession) : null;
    }

    /**
    * Mise en session des param�tres de l'�v�nement en �dition
    * @access: private.
    */
    public function _setSessionConsolidationStatisticFilter ($toSet)
    {
        $toSession = ($toSet !== null) ? serialize($toSet) : null;
        _sessionSet('modules|statistiques|filter', $toSession);
    }

    /**
    * @access: private.
    */
    public function _validFromForm (& $toUpdate)
    {
        $toCheck = array ('publishedFrom', 'publishedTo', 'target');
        foreach ($toCheck as $elem){
            if (_request($elem)){
                if ($elem == 'publishedFrom' || $elem == 'publishedTo') {
                    // On utilise d'abord la validation proposée par le kernel, comme ça on est sûr du format
                    $value = Kernel::_validDateProperties(_request($elem));
                    list($d, $m, $y) = explode('/', $value);
                    $value = new \DateTime("$y-$m-$d");

                    if ($elem == 'publishedTo'){
                        $value->setTime(23, 59, 59);
                    }
                } else {
                    $value = $toUpdate->$elem = _request($elem);
                }

                $elem = 'set'.ucfirst($elem);
                $toUpdate->$elem($value);
            }
        }

        if ($toUpdate->getPublishedFrom() > $toUpdate->getPublishedTo() || !$toUpdate->getTarget()) {
          $toUpdate = null;
        }
    }

    /**
     * @param string $str camelCased string
     *
     * @return string underscored string
     */
    function getUnderscoredString($str) {
      $str[0] = strtolower($str[0]);
      $func = create_function('$c', 'return "_" . strtolower($c[1]);');
      return preg_replace_callback('/([A-Z])/', $func, $str);
    }
}
