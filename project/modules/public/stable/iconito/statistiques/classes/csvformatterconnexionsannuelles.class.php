<?php

class CsvFormatterConnexionsAnnuelles extends CsvFormatter
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

        $connexionsAnnuelles = $api->getConnexionsAnnuelles($this->getOption('profile'));

        $years           = array_keys($connexionsAnnuelles);
        $nombreConnexion = array_values($connexionsAnnuelles);

        return array(
            $years,
            $nombreConnexion
        );
    }
}