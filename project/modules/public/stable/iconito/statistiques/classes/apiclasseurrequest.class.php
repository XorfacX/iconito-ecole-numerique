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
        $classeurCount = $this->getNombreClasseurs();

        return array(
            'dossiers' => $dossierCount,
            'ratio' => $classeurCount > 0 ? round($dossierCount / $classeurCount, 2) : 0
        );
    }

    public function getNombreFichiersEtInfos()
    {
        // On récupère tous les classeurs du périmètre
        $target = $this->getFilter()->getTarget();

        $sql = <<<SQL
            SELECT module_id
            FROM kernel_mod_enabled kme
            WHERE kme.node_type = ?
                AND kme.node_id = ?
                AND kme.module_type = 'MOD_CLASSEUR'
SQL;

        $results = _doQuery($sql, array('BU_ECOLE', 1));

        $filter = $this->createBaseFilter();
        $filter->setObjectObjectType(static::CLASS_FICHIER);
        $filter->setObjectAttributes(array('is_casier' => 1));
        $filter->setPeriod(static::PERIOD_UNIT);
        $filter->setLastOnly(true);

        $fichiersCount = 0;
        $taille = 0;
        // Récupère le détail des fichiers qui sont des casiers dans le périmètre considéré
        foreach ($results as $result) {
            $filter->setTargetObjectType(static::CLASS_CLASSEUR);
            $filter->setTargetId($result->module_id);
            $fichiers = $this->getResult($filter);
            if (count($fichiers)){
                $fichier = reset($fichiers);
                $fichiersCount += $fichier->counter;
                $taille += $fichier->object_attributes->taille;
            }
        }

        $casiers = array(
                  'count' => $fichiersCount,
                  'total' => $taille,
                  'average' => $fichiersCount > 0 ? round($taille / $fichiersCount, 2) : 0
        );

        // On y ajoute le détail des fichier qui ne sont pas des casiers
        $filter->setObjectAttributes(array('is_casier' => 0));
        foreach ($results as $result) {
            $filter->setTargetObjectType(static::CLASS_CLASSEUR);
            $filter->setTargetId($result->module_id);
            $fichiers = $this->getResult($filter);
            if (count($fichiers)){
                $fichier = reset($fichiers);
                $fichiersCount += $fichier->counter;
                $taille += $fichier->object_attributes->taille;
            }
        }

        return array(
            'count' => $fichiersCount,
            'total' => $taille,
            'average' => $fichiersCount ? $taille / $fichiersCount : 0,
            'casiers' => $casiers
        );
    }
}