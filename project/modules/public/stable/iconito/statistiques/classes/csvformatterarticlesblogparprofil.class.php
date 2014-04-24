<?php

class CsvFormatterArticlesBlogParProfil extends CsvFormatter
{
    /**
     * Retourne les lignes générées
     *
     * @return array
     */
    public function getLines()
    {
        _classInclude('statistiques|ApiBlogRequest');

        $api = new ApiBlogRequest($this->filter);

        $data = $api->getNombreArticleParProfil();

        return array(
            array_keys($data),
            array_values($data)
        );
    }
}