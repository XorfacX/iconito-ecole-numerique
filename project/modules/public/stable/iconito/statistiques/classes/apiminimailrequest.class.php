<?php

_classInclude('statistiques|apibaserequest');
_classInclude('statistiques|consolidatedstatisticfilter');

class ApiMinimailRequest extends ApiBaseRequest
{
    /**
     * Récupère le nombre d'agendas créés à une date donnée
     *
     * @return integer
     */
    public function getNombreMinimails()
    {
        return $this->getObjectTypeNumber(static::CLASS_MINIMAIL);
    }

    /**
     * Récupère le nombre de minimails envoyés, et le ratio par comptes ouverts
     *
     * @return integer
     */
    public function getNombreMinimailsEtRatio()
    {
        $minimailCount = $this->getNombreMinimails();

        $userRequest = new ApiUserRequest($this->getFilter());
        $accountCount = $userRequest->getNombreComptes();

        $ratio = $minimailCount/$accountCount;

        return array(
            'minimails' => $minimailCount,
            'ratio' => $ratio
        );
    }
}