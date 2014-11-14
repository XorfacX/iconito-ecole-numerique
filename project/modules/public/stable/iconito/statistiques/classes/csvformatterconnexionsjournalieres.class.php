<?php

class CsvFormatterConnexionsJournalieres extends CsvFormatter
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

        $connexionsJournalieres = $api->getConnexionsJournalieres($this->getOption('profile'));

        $connexionsJournalieres = $connexionsJournalieres['statistiques'];

        $days            = array_keys($connexionsJournalieres);
        $nombreConnexion = array_values($connexionsJournalieres);

        return array(
            $days,
            $nombreConnexion
        );
    }
}