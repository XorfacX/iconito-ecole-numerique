<?php

_classInclude('statistiques|apibaserequest');
_classInclude('statistiques|consolidatedstatisticfilter');

class ApiClasseurRequest extends ApiBaseRequest
{
    /**
     * Récupère le nombre de comptes créés à une date donnée
     *
     * @return integer
     */
    public function getNombreClasseurs()
    {
        return $this->getObjectTypeNumber(static::CLASS_CLASSEUR);
    }

    public function getNombreDossiersEtRatio()
    {
        $dossierCount = $this->getObjectTypeNumber(static::CLASS_DOSSIER);
        $classeurCount = $this->getNombreClasseurs() ? $this->getNombreClasseurs() : 1;

        $ratio = $dossierCount/$classeurCount;

        return array(
            'dossiers' => $dossierCount,
            'ratio' => $ratio
        );
    }
}