<?php

_classInclude('statistiques|consolidatedstatisticfilter');

/**
 * Actiongroup du module Statistiques
 *
 * @package Iconito
 * @subpackage Statistiques
 */
class ActionGroupDefault extends CopixActionGroup
{
    public function beforeAction()
    {
        _currentUser()->assertCredential('module:*||access|@statistiques');
    }

    public function processSetFilter ()
    {
        $ppo = new CopixPPO ();

        $ppo->user = _currentUser();

        $ppo->TITLE_PAGE = CopixConfig::get('statistiques|moduleTitle');

        if (!$filter = $this->_getSessionConsolidationStatisticFilter()) {
            $filter = new ConsolidatedStatisticFilter();
            $this->_setSessionConsolidationStatisticFilter($filter);
        }

        if (_request('publishedBeginDate')) {
            //demande de mettre l'objet � jour en fonction des valeurs saisies dans le formulaire
            $this->_validFromForm($filter);
            $this->_setSessionConsolidationStatisticFilter($filter);
        }
        $ppo->filter = $filter;

        // Get vocabulary catalog to use
        $nodeVocabularyCatalogDAO = _ioDAO('kernel|kernel_i18n_node_vocabularycatalog');
        $ppo->vocabularyCatalog = $nodeVocabularyCatalogDAO->getCatalogForNode($ppo->nodeType, $ppo->nodeId);

        CopixHTMLHeader::addCSSLink (_resource("styles/module_agenda.css"));

        CopixHTMLHeader::addJSLink (_resource("js/jquery/jquery.ui.datepicker-fr.js"));

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
        $toCheck = array ('publishedBeginDate', 'publishedEndDate', 'context');
        foreach ($toCheck as $elem){
            if (_request($elem)){
                if ($elem == 'publishedBeginDate' || $elem == 'publishedEndDate') {
                    // On utilise d'abord la validation proposée par le kernel, comme ça on est sûr du format
                    $value = Kernel::_validDateProperties(_request($elem));
                    list($d, $m, $y) = explode('/', $value);
                    $value = new \DateTime("$y-$m-$d");
                } else {
                    $value = $toUpdate->$elem = _request($elem);
                }

                $elem = 'set'.ucfirst($elem);
                $toUpdate->$elem($value);
            }
        }
    }
}
