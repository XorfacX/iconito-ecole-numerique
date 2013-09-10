<?php

_classInclude('statistiques|consolidatedstatisticfilter');
_classInclude('statistiques|apimapping');
_classInclude('statistiques|apiuserrequest');
_classInclude('statistiques|apiblogrequest');
_classInclude('statistiques|apiagendarequest');
_classInclude('statistiques|apicahierdetexterequest');
_classInclude('statistiques|apiquizrequest');
_classInclude('statistiques|apiclasseurrequest');
_classInclude('statistiques|apiminimailrequest');
_classInclude('statistiques|apigroupedetravailrequest');

/**
 * Actiongroup du module Statistiques
 *
 * @package Iconito
 * @subpackage Statistiques
 */
class ActionGroupRequest extends CopixActionGroup
{
    public function beforeAction()
    {
        _currentUser()->assertCredential('module:*||access|@statistiques');
    }

    public function processGetStat ()
    {
        ini_set('display_errors', true);
        $ppo = new CopixPPO ();

        $ppo->user = _currentUser();

        $ppo->TITLE_PAGE = CopixConfig::get('statistiques|moduleTitle');

        if (!$baseFilter = $this->_getSessionConsolidationStatisticFilter()) {
            throw new Exception('Vous devez d\'abord saisir les dates et le contexte du filtre');
        }

        $mapping = new ApiMapping;
        $statName = _request('stat');
        extract($mapping->getFilter($statName)); // $label, $class
        $apiRequestClass = new $class($baseFilter);
        $method = 'get'.ucfirst($statName);
        $ppo->result = $apiRequestClass->$method();
        $ppo->filter = $baseFilter;

        return _arPPO($ppo, 'requests/'.$this->getUnderscoredString($statName).'.tpl');
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
