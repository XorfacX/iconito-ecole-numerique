<?php

class CsvFormatterMinimailsParProfil extends CsvFormatter
{
    /**
     * Retourne les lignes générées
     *
     * @return array
     */
    public function getLines()
    {
        _classInclude('statistiques|ApiMinimailRequest');

        $api = new ApiMinimailRequest($this->filter);

        $data = $api->getNombreMinimailParProfil();

        return array(
            array_keys($data),
            array_values($data)
        );
    }
}