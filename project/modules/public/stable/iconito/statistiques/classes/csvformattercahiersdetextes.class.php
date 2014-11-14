<?php

class CsvFormatterCahiersDeTextes extends CsvFormatter
{
    /**
     * Retourne les lignes générées
     *
     * @return array
     */
    public function getLines()
    {
        _classInclude('statistiques|ApiCahierDeTexteRequest');

        $api = new ApiCahierDeTexteRequest($this->filter);

        $aFaire   = $api->getTravailAFaire();
        $enClasse = $api->getTravailEnClasse();
        $memos    = $api->getMemos();

        $data = array(
            'travail(aux) ont été donné(s) à faire'   => $aFaire['total'],
            'travaux par jour'                        => $aFaire['average'],
            'travail(aux) ont été donné(s) en classe' => $enClasse['total'],
            'travaux en classe par jour'              => $enClasse['average'],
            'mémo(s) créé(s)'                         => $memos['total'],
            'mémo(s) par jour'                        => $memos['average']
        );

        return array(
            array_keys($data),
            array_values($data)
        );
    }
}