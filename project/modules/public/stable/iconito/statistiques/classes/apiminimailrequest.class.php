<?php

_classInclude('statistiques|apibaserequest');
_classInclude('statistiques|consolidatedstatisticfilter');
_classInclude('statistiques|ApiUserRequest');

class ApiMinimailRequest extends ApiBaseRequest
{

    /**
     * Récupère le nombre de minimails envoyés, et le ratio par comptes ouverts
     *
     * @return integer
     */
    public function getNombreMinimailsEtRatio()
    {
        $minimailCount = $this->getNombreMinimail();

        $userRequest = new ApiUserRequest($this->getFilter());
        $accountCount = $userRequest->getNombreComptes();

        return array(
            'minimails' => $minimailCount,
            'ratio' => $accountCount > 0 ? round($minimailCount/$accountCount, 2) : 0
        );
    }

    public function getNombreMinimailParProfil()
    {
        $profils = array(
            'USER_ADM' => 'Équipe administrative',
            'USER_DIR' => 'Directeur',
            'USER_ELE' => 'Élève',
            'USER_ENS' => 'Enseignant',
            'USER_EXT' => 'Intervenant extérieur',
            'USER_RES' => 'Responsable',
            'USER_VIL' => 'Agent de ville'
        );

        $nombres = array();
        foreach ($profils as $profil => $libelle){
            $nombres[$libelle] = $this->getNombreMinimail($profil);
        }

        return $nombres;
    }

    public function getNombreMinimail($profil = null)
    {
        $filter = $this->createBaseFilter();
        $filter->setObjectObjectType(static::CLASS_MINIMAIL);
        $filter->setPeriod(static::PERIOD_DAILY);
        $filter->setVerb('send');

        if (null !== $profil){
            $filter->setActorAttributes(array('type' => $profil));
        }

        return $this->sumResults($this->getResult($filter));
    }
}