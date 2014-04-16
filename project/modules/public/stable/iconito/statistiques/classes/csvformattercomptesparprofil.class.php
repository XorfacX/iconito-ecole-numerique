<?php

class CsvFormatterComptesParProfil extends CsvFormatter
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

        $comptesParProfil = $api->getNombreComptesParProfil();

        $profils       = array_keys($comptesParProfil);
        $nombreComptes = array_values($comptesParProfil);

        return array(
            $profils,
            $nombreComptes
        );
    }
}