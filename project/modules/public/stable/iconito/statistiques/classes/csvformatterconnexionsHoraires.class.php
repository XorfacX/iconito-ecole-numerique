<?php

class CsvFormatterConnexionsHoraires extends CsvFormatter
{
    /**
     * Retourne les lignes générées
     *
     * @return array
     */
    public function getLines()
    {
        _classInclude('statistiques|ApiUserRequest');

        $api = new ApiUserRequest($this->filter);

        $connexionsHoraires = $api->getConnexionsHoraires($this->getOption('profile'));

        $connexionsHoraires = $connexionsHoraires['moyennes'];

        $hours           = array_keys($connexionsHoraires);
        $nombreConnexion = array_map(function($connexionHoraire){
            return $connexionHoraire['valeur'];
        }, array_values($connexionsHoraires));

        return array(
            $hours,
            $nombreConnexion
        );
    }
}