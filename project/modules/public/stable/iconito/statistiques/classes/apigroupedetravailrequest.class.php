<?php

_classInclude('statistiques|apibaserequest');
_classInclude('statistiques|consolidatedstatisticfilter');

class ApiGroupeDeTravailRequest extends ApiBaseRequest
{
    /** @var array Cache du nombre de groupe de travail par module */
    protected $groupeDeTravailParModule = array();

    /**
     * Retourne le nombre de groupe de travail ayant le module $module activé
     *
     * @param string $module Le nom du module
     *
     * @return int
     */
    public function getNombreGroupDeTravail($module = 'TOTAL')
    {
        if (!isset($this->groupeDeTravailParModule[$module])) {
            $this->groupeDeTravailParModule[$module] = $this->getObjectTypeNumber(static::CLASS_GROUPETRAVAIL, array('module' => $module));
        }

        return $this->groupeDeTravailParModule[$module];
    }

    /**
     * Retourne la répartition des groupe de travail par module
     *
     * @return array
     */
    public function getRepartitionGroupDeTravailParModule()
    {
        $modules = array(
            'MOD_AGENDA' => 'Agenda',
            'MOD_BLOG' => 'Blog',
            'MOD_CLASSEUR' => 'Classeur',
            'MOD_FORUM' => 'Forum',
            'MOD_LISTE' => 'Liste de diffusion',
            'MOD_QUIZ' => 'Quiz'
        );

        $results = array();

        $nombreTotal = $this->getNombreGroupDeTravail();

        foreach ($modules as $moduleKey => $moduleLabel){
            $nombreModule = $this->getNombreGroupDeTravail($moduleKey);

            if ($nombreTotal > 0){
                $results[$moduleLabel] = round(($nombreModule / $nombreTotal) * 100, 2);
            }
            else{
                $results[$moduleLabel] = 0;
            }
        }

        return $results;
    }

    /**
     * Récupère le nombre d'agendas créés à une date donnée
     *
     * @return integer
     */
    public function getNombreMessages()
    {
        return $this->getObjectTypeNumber(static::CLASS_MESSAGE);
    }

    /**
     * Récupère le nombre de discussions à une date donnée
     *
     * @return integer
     */
    public function getNombreDiscussionsEtRatio()
    {
        $discussions = $this->getObjectTypeNumber(static::CLASS_DISCUSSION);
        $forums = $this->getNombreGroupDeTravail('MOD_FORUM');

        return array(
            'number' => $discussions,
            'ratio' => $forums > 0 ? round($discussions / $forums, 2) : 0
        );
    }

    /**
     * Récupère le nombre de messages à une date donnée
     *
     * @return integer
     */
    public function getNombreMessagesEtRatio()
    {
        $messages = $this->getObjectTypeNumber(static::CLASS_MESSAGE);
        $discussions = $this->getObjectTypeNumber(static::CLASS_DISCUSSION);

        return array(
            'number' => $messages,
            'ratio' => $discussions > 0 ? round($messages / $discussions, 2) : 0
        );
    }

    /**
     * Récupère le nombre d'agendas créés à une date donnée
     *
     * @return integer
     */
    public function getNombreMinimailEtRatio()
    {
        $filter = $this->createBaseFilter();
        $filter
            ->setObjectObjectType(static::CLASS_MINIMAIL_SENT)
            ->setVerb('send')
            ->setPeriod(static::PERIOD_DAILY);
        $results = $this->getResult($filter);

        $minimails = $this->sumResults($results);
        $listesDiff = $this->getNombreGroupDeTravail('MOD_LISTE');

        $nbDaysInFilter = $this->getFilter()->getpublishedFrom()->diff($this->getFilter()->getpublishedTo())->days;

        return array(
            'number'  => $minimails,
            'ratio'   => $listesDiff > 0 ? round($minimails / $listesDiff, 2) : 0,
            'average' => $nbDaysInFilter > 0 ? round($minimails / $nbDaysInFilter, 2) : 0
        );
    }
}