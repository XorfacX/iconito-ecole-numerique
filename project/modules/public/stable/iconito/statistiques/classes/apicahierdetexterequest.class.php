<?php

_classInclude('statistiques|apibaserequest');

class ApiCahierDeTexteRequest extends ApiBaseRequest
{
    public function getTravailAfaire()
    {
        return $this->getTotalAndAverageOnPeriod(static::CLASS_TRAVAIL, 'given', array('travail à faire'));
    }

    public function getTravailEnClasse()
    {
        return $this->getTotalAndAverageOnPeriod(static::CLASS_TRAVAIL, 'given', array('travail en classe'));
    }

    public function getMemos()
    {
        return $this->getTotalAndAverageOnPeriod(static::CLASS_TRAVAIL, 'given');
    }
}