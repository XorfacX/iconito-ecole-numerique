<?php

class CsvFormatterConnexionsMensuelles extends CsvFormatter
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

        $connexionsMensuelles = $api->getConnexionsMensuelles($this->getOption('profile'));

        $connexionsMensuelles = $connexionsMensuelles['statistiques'];

        $months          = array_keys($connexionsMensuelles);
        $nombreConnexion = array_values($connexionsMensuelles);

        return array(
            $months,
            $nombreConnexion
        );
    }
}