<?php

_classInclude('statistiques|baseStatistiques');
_classInclude('statistiques|StatistiquesApiFilter');

class Agenda extends baseStatistiques
{
    /** @var int Le nombre total d'agendas */
    protected $nombreAgendas;

    protected $nombreEvenements;

    /**
     * Récupère le nombre d'agendas créés à une date donnée
     *
     * @return integer
     */
    public function getNombreAgendas()
    {
        if (null === $this->nombreAgendas) {
            $this->nombreAgendas = $this->getCount(
                $this->getLastUnitApiFilter()
                    ->set('object_object_type', static::CLASS_AGENDA)
            );
        }

        return $this->nombreAgendas;
    }

    /**
     * Récupère le nombre d'événements créés sur la période
     *
     * @return integer
     */
    public function getNombreEvenements()
    {
        if (null === $this->nombreEvenements){
            $this->nombreEvenements = $this->getSum(
                $this->getPeriodApiFilter(static::PERIOD_DAILY)
                    ->set('verb', 'create')
                    ->set('object_object_type', static::CLASS_EVENT)
                    ->set('target_object_type', static::CLASS_AGENDA)
            );
        }

        return $this->nombreEvenements;
    }

    /**
     * Retourne le ratio du nombre d'évènement créé sur la période par agenda existant
     *
     * @return float
     */
    public function getRatioEvenementsParAgenda()
    {
        $nombreAgendas = $this->getNombreAgendas();

        if (0 !== $nombreAgendas){
            return $this->getNombreEvenements() / $nombreAgendas;
        }

        return 0;
    }
}