<?php

class CsvFormatterClasseurs extends CsvFormatter
{
    /**
     * Retourne les lignes générées
     *
     * @return array
     */
    public function getLines()
    {
        _classInclude('statistiques|ApiClasseurRequest');

        $api = new ApiClasseurRequest($this->filter);

        $dossiersEtRatio        = $api->getNombreDossiersEtRatio();
        $fichiers               = $api->getNombreFichiersEtInfos();

        $data = array(
            'classeur(s)'                               => $api->getNombreClasseurs(),
            'dossier(s)'                                => $dossiersEtRatio['dossiers'],
            'dossier par classeur'                      => $dossiersEtRatio['ratio'],
            'fichier(s)'                                => $fichiers['count'],
            'poids total des fichiers'                  => sprintf('%.2f Ko', $fichiers['total']),
            'poids moyen par fichier'                   => sprintf('%.2f Ko', $fichiers['average']),
            'fichier(s) dans les casiers'               => $fichiers['casiers']['count'],
            'poids total des fichiers dans les casiers' => sprintf('%.2f Ko', $fichiers['casiers']['total']),
            'poids moyen par fichier dans les casiers'  => sprintf('%.2f Ko', $fichiers['casiers']['average']),
        );

        return array(
            array_keys($data),
            array_values($data)
        );
    }
}