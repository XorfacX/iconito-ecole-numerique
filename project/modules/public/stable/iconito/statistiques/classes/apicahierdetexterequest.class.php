<?php

_classInclude('statistiques|apibaserequest');

class ApiCahierDeTexteRequest extends ApiBaseRequest
{
    public function getTravailAfaire()
    {
        return $this->getTotalAndAverageOnPeriod(static::CLASS_TRAVAIL, 'create', array('a_faire' => 1));
    }

    public function getTravailEnClasse()
    {
        return $this->getTotalAndAverageOnPeriod(static::CLASS_TRAVAIL, 'create', array('a_faire' => 0));
    }

    public function getMemos()
    {
        return $this->getTotalAndAverageOnPeriod(static::CLASS_MEMO, 'create');
    }
}