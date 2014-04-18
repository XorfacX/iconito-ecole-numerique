<?php

class CsvFormatterGroupesDeTravail extends CsvFormatter
{
    /**
     * Retourne les lignes générées
     *
     * @return array
     */
    public function getLines()
    {
        _classInclude('statistiques|ApiGroupeDeTravailRequest');

        $api = new ApiGroupeDeTravailRequest($this->filter);

        $data = array(
            'groupe(s) de travail' => $api->getNombreGroupDeTravail()
        );

        $repartition = $api->getRepartitionGroupDeTravailParModule();

        foreach ($repartition as $moduleName => $percent) {
            $data['ayant le module "' . $moduleName . '" activé'] = $percent . '%';
        }

        $nombreDiscussionsEtRatio = $api->getNombreDiscussionsEtRatio();
        $nombreMessagesEtRatio    = $api->getNombreMessagesEtRatio();
        $nombreMinimailsEtRatio   = $api->getNombreMinimailEtRatio();

        $data = array_merge($data, array(
            'discussions ouvertes'                                           => $nombreDiscussionsEtRatio['nombre'],
            'discussions par groupe de travail ayant le module forum activé' => $nombreDiscussionsEtRatio['ratio'],
            'messages'                                                       => $nombreMessagesEtRatio['nombre'],
            'messages par discussion'                                        => $nombreMessagesEtRatio['ratio'],
            'minimails envoyés'                                              => $nombreMinimailsEtRatio['nombre'],
            'minimails par groupe de travail'                                => $nombreMinimailsEtRatio['ratio'],
            'minimails par jour'                                             => $nombreMinimailsEtRatio['average']
        ));

        return array(
            array_keys($data),
            array_values($data)
        );
    }
}