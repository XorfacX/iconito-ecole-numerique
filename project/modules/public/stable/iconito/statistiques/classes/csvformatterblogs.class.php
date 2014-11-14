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
        _classInclude('statistiques|ApiBlogRequest');

        $api = new ApiBlogRequest($this->filter);

        $blogVisibles           = $api->getBlogVisibleAnnuaireEtNonVisible();
        $visibilite             = $api->getBlogVisibleEtVisibilite();
        $nombreRubriquesEtRatio = $api->getNombreRubriquesEtRatio();
        $nombrePagesEtRatio     = $api->getNombrePagesEtRatio();

        $data = array(
            'blog(s) ouvert(s)'                              => $api->getNombreBlogs(),
            'blog(s) visible(s) dans l\'annuaire'            => $blogVisibles['visible_annuaire'],
            'blog(s) non-visible(s) dans l\'annuaire'        => $blogVisibles['non_visible'],
            'blog(s) visible(s) sur internet'                => $visibilite['visible_internet'],
            'blog(s) visible(s) par les membres Iconito'     => $visibilite['visible_membres_iconito'],
            'blog(s) visible(s) par les membres des groupes' => $visibilite['visible_membres_groupe'],
            'rubrique(s)'                                    => $nombreRubriquesEtRatio['rubriques'],
            'rubrique(s) par blog'                           => $nombreRubriquesEtRatio['ratio'],
            'page(s)'                                        => $nombrePagesEtRatio['pages'],
            'page(s) par blog'                               => $nombrePagesEtRatio['ratio']
        );

        return array(
            array_keys($data),
            array_values($data)
        );
    }
}