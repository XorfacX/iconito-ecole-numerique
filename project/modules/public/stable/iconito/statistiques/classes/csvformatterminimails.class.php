<?php

class CsvFormatterBlogs extends CsvFormatter
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

        $minimails = $api->getNombreMinimailsEtRatio();

        $data = array(
            'minimail(s) envoyé(s)'         => $minimails['minimails'],
            'minimail(s) par compte ouvert' => $minimails['ratio']
        );

        return array(
            array_keys($data),
            array_values($data)
        );
    }
}