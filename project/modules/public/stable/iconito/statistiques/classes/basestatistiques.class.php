<?php

_classInclude('statistiques|StatistiquesFilter');
_classInclude('statistiques|StatistiquesFormFilter');
_classInclude('statistiques|StatistiquesApiFilter');

/**
 * Class baseStatistiques
 *
 * Classe de base de toutes les classes de statistiques
 */
class baseStatistiques
{
    /** @var string L'url de base de l'api */
    protected $apiBaseUrl;

    protected $applicationId;

    /** @var StatistiqueFormFilter Le formulaire de filtres */
    protected $formFilter;

    const PERIOD_UNIT    = 'unit';
    const PERIOD_DAILY   = 'daily';
    const PERIOD_WEEKLY  = 'weekly';
    const PERIOD_MONTHLY = 'monthly';
    const PERIOD_YEARLY  = 'yearly';

    const CLASS_ACCOUNT       = 'Account';
    const CLASS_AGENDA        = 'DAORecordAgenda';
    const CLASS_EVENT         = 'DAORecordEvent';
    const CLASS_MINIMAIL      = 'Minimail';
    const CLASS_CLASSEUR      = 'Classeur';
    const CLASS_DOSSIER       = 'Dossier';
    const CLASS_BLOG          = 'Blog';
    const CLASS_VISITE        = 'Visite';
    const CLASS_RUBRIQUE      = 'Rubrique';
    const CLASS_PAGE          = 'Page';
    const CLASS_ARTICLE       = 'Article';
    const CLASS_COMMENTAIRE   = 'Commentaire';
    const CLASS_TRAVAIL       = 'Travail';
    const CLASS_CAHIERTEXTE   = 'CahierDeTexte';
    const CLASS_MEMO          = 'Memo';
    const CLASS_QUIZ          = 'Quiz';
    const CLASS_QUESTION      = 'Question';
    const CLASS_GROUPETRAVAIL = 'GroupeDeTravail';
    const CLASS_DISCUSSION    = 'Discussion';
    const CLASS_MESSAGE       = 'Message';

    /**
     * Constructeur
     */
    public function __construct($apiBaseUrl, $applicationId, StatistiquesFormFilter $formFilter)
    {
        $this->apiBaseUrl = $apiBaseUrl;

        $this->applicationId = $applicationId;

        $this->setFormFilter($formFilter);
    }

    /**
     * Défini le formulaire de filtre
     *
     * @param StatistiquesFormFilter $formFilter
     */
    public function setFormFilter(StatistiquesFormFilter $formFilter)
    {
        $this->formFilter = $formFilter;
    }

    public function getFormFilter()
    {
        return $this->formFilter;
    }

    /**
     * Retourne un nouveau filtre d'API préconfiguré pour ne sélectionner que les dernière statistiques unitaires
     *
     * @return StatistiquesApiFilter
     */
    protected function getLastUnitApiFilter()
    {
        return new StatistiquesApiFilter(array(
            'period'    => static::PERIOD_UNIT,
            'last_only' => true
        ));
    }

    /**
     * Retourne une filtre d'API pour une période donnée
     *
     * @param $period La période
     *
     * @return StatistiquesApiFilter
     */
    protected function getPeriodApiFilter($period)
    {
        return new StatistiquesApiFilter(array(
            'period' => $period
        ));
    }

    /**
     * Effectue une requête vers l'API
     *
     * @param StatistiquesApiFilter $apiFilter
     *
     * @return array
     */
    public function doSelect(StatistiquesApiFilter $apiFilter)
    {
        $filter = new
    }

    /**
     * Effectue une requête vers l'API (ne retourne que le premier enregistrement
     *
     * @param StatistiquesApiFilter $apiFilter
     *
     * @return array
     */
    public function doSelectOne(StatistiquesApiFilter $apiFilter)
    {
        return reset($this->doSelect($apiFilter));
    }

    /**
     * Retourne la valeur de count de la première ligne retournée
     * 0 si aucune ligne
     *
     * @return int
     */
    protected function getCount(StatistiquesApiFilter $apiFilter)
    {
        $result = $this->doSelectOne($apiFilter);

        return false !== $result ? $result->count : 0;
    }

    /**
     * Retourne la somme des compteur des lignes dans le résultat
     *
     * @param StatistiquesApiFilter $apiFilter
     *
     * @return int
     */
    protected function getSum(StatistiquesApiFilter $apiFilter)
    {
        $sum = 0;

        foreach ($this->doSelect($apiFilter) as $result){
            $sum += $result->count;
        }

        return $sum;
    }
}