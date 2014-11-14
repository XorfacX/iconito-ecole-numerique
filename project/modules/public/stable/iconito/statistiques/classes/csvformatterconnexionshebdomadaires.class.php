<?php

class CsvFormatterConnexionsHebdomadaires extends CsvFormatter
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

        $connexionsHebdomadaires = $api->getConnexionsHebdomadaires($this->getOption('profile'));

        $connexionsHebdomadaires = $connexionsHebdomadaires['statistiques'];

        $weeks           = array_keys($connexionsHebdomadaires);
        $nombreConnexion = array_values($connexionsHebdomadaires);

        return array(
            $weeks,
            $nombreConnexion
        );
    }
}