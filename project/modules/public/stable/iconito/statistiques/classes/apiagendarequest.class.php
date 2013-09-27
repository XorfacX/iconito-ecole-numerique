<?php

_classInclude('statistiques|apibaserequest');
_classInclude('statistiques|consolidatedstatisticfilter');

class ApiAgendaRequest extends ApiBaseRequest
{
    /**
     * Récupère le nombre d'agendas créés à une date donnée
     *
     * @return integer
     */
    public function getNombreAgendas()
    {
        return $this->getObjectTypeNumber(static::CLASS_AGENDA);
    }

    /**
     * Récupère le nombre d'événements créés sur la période, et le ratio par agenda
     *
     * @return integer
     */
    public function getNombreEvenementsEtRatio()
    {
        $filter = $this->createBaseFilter();
        $filter->setObjectObjectType(static::CLASS_EVENT);
        $filter->setTargetObjectType(static::CLASS_AGENDA);
        $filter->setPeriod(static::PERIOD_DAILY);
        $results = $this->getResult($filter);

        $sum = 0;
        foreach ($results as $result) {
            $sum += $result->counter;
        }

        $agendas = $this->getNombreAgendas();
        $agendas = $agendas ? $agendas : 1;

        $ratio = $sum/$agendas;

        return array(
            'evenements' => $sum,
            'ratio' => $ratio
        );
    }
}