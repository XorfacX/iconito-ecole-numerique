<?php

class CsvFormatterAgenda extends CsvFormatter
{
    /**
     * Retourne les lignes générées
     *
     * @return array
     */
    public function getLines()
    {
        _classInclude('statistiques|ApiAgendaRequest');

        $api = new ApiAgendaRequest($this->filter);

        $evenements = $api->getNombreEvenementsEtRatio();

        $data = array(
            'événement(s) ont été créé(s)' => $evenements['evenements'],
            'événement(s) par agenda'      => $evenements['ratio']
        );

        return array(
            array_keys($data),
            array_values($data)
        );
    }
}