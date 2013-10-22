<?php

_classInclude('statistiques|apibaserequest');
_classInclude('statistiques|consolidatedstatisticfilter');
_classInclude('statistiques|ApiUserRequest');

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
//        $minimailCount = $this->getNombreMinimails();
        $minimailCount = 100;

        $userRequest = new ApiUserRequest($this->getFilter());
        $accountCount = $userRequest->getNombreComptes();

        if ($accountCount == 0){
            $ratio = 0;
        }
        else {
            $ratio = round($minimailCount/$accountCount, 2);
        }

        return array(
            'minimails' => $minimailCount,
            'ratio' => $ratio
        );
    }
}