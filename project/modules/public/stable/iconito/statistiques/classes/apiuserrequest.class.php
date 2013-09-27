<?php

_classInclude('statistiques|apibaserequest');
_classInclude('statistiques|consolidatedstatisticfilter');

class ApiUserRequest extends ApiBaseRequest
{
    /**
     * Récupère le nombre de comptes créés à une date donnée
     *
     * @return integer
     */
    public function getNombreComptes()
    {
        return $this->getObjectTypeNumber(static::CLASS_ACCOUNT);
    }

    /**
     * Récupère le nombre de connexions enregistrées dans la période (sur les statistiques mensuelles).
     *
     * @return integer
     */
    public function getNombreConnexionsAnnuelles()
    {
        return $this->getNombreConnexionParPeriode(static::PERIOD_YEARLY);
    }

    /**
     * Récupère le nombre de connexions enregistrées dans la période (sur les statistiques mensuelles).
     *
     * @return integer
     */
    public function getNombreConnexionsMensuelles()
    {
        return $this->getNombreConnexionParPeriode(static::PERIOD_MONTHLY);
    }

    /**
     * Récupère le nombre de connexions enregistrées dans la période (sur les statistiques journalières).
     *
     * @return integer
     */
    public function getNombreConnexionsHebdomadaires()
    {
        return $this->getNombreConnexionParPeriode(static::PERIOD_WEEKLY);
    }

    /**
     * Récupère le nombre de connexions enregistrées dans la période (sur les statistiques journalières).
     *
     * @return integer
     */
    public function getNombreConnexionsJournalieres()
    {
        return $this->getNombreConnexionParPeriode(static::PERIOD_DAILY);
    }

    /**
     * Retourne le nombre de connexions sur la période, pour le type de statistique passé en paramètres
     *
     * @param $periode
     * @return int
     */
    private function getNombreConnexionParPeriode($periode)
    {
        $filter = $this->createBaseFilter();
        $filter->setActorObjectType(static::CLASS_ACCOUNT);
        $filter->setVerb('login');
        $filter->setPeriod($periode);

        return $this->sumResults($this->getResult($filter));
    }
}