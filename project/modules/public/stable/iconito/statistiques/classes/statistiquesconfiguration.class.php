<?php

_classInclude('statistiques|StatistiquesFormFilter');
_classInclude('statistiques|StatistiquesFactory');

class StatistiquesConfiguration
{
    public function getCategories()
    {
        return array(
            'comptesEtConnexions' => array('label' => 'Usagers', 'template' => 'requests/comptes_et_connexions.tpl', 'class' => function($filter) {_classInclude('statistiques|apiuserrequest'); return new ApiUserRequest($filter); }),
            'agendas' => array('label' => 'Agendas', 'template' => 'requests/agendas.tpl', 'class' => function($filter) {_classInclude('statistiques|apiagendarequest'); return new ApiAgendaRequest($filter); }),
            'minimails' => array('label' => 'Minimails', 'template' => 'requests/minimails.tpl', 'class' => function($filter) {_classInclude('statistiques|apiminimailrequest'); return new ApiMinimailRequest($filter); }),
            'classeurs' => array('label' => 'Classeurs', 'template' => 'requests/classeurs.tpl', 'class' => function($filter) {_classInclude('statistiques|apiclasseurrequest'); return new ApiClasseurRequest($filter); }),
            'blogs' => array('label' => 'Blogs', 'template' => 'requests/blogs.tpl', 'class' => function($filter) {_classInclude('statistiques|apiblogrequest'); return new ApiBlogRequest($filter); }),
            'cahiersDeTextes' => array('label' => 'Cahiers de textes', 'template' => 'requests/cahiers_de_textes.tpl', 'class' => function($filter) {_classInclude('statistiques|apicahierdetexterequest'); return new ApiCahierDeTexteRequest($filter); }),
            'quiz' => array('label' => 'Quiz', 'template' => 'requests/quiz.tpl', 'class' => function($filter) {_classInclude('statistiques|apiquizrequest'); return new ApiQuizRequest($filter); }),
            'groupesDeTravail' => array('label' => 'Groupes de travail', 'template' => 'requests/groupes_de_travail.tpl', 'class' => function($filter) {_classInclude('statistiques|apigroupedetravailrequest'); return new ApiGroupeDeTravailRequest($filter); }),
        );
    }

    /**
     * Retourne la configuration d'une catégorie de statistiques
     *
     * @param string $category La clé de la catégorie
     *
     * @throws Exception
     *
     * @return array
     */
    public function getConfiguration($category)
    {
        $categories = $this->getCategories();

        if (!isset($categories[$category])) {
            throw new Exception('The requested key is invalid');
        }

        return $categories[$category];
    }

    /**
     * Retourne le libellé d'une statistique donnée
     *
     * @param string $category
     *
     * @return string
     */
    public function getLabel($category)
    {
        $configuration = $this->getConfiguration($category);

        return $configuration['label'];
    }

    /**
     * Retourne le template d'une statistique donnée
     *
     * @param string $category
     *
     * @return string
     */
    public function getTemplate($category)
    {
        $configuration = $this->getConfiguration($category);

        return $configuration['template'];
    }

    /**
     * Retourne la classe de récupération des valeurs de statistiques
     *
     * @param string $category
     *
     * @return BaseStatistiques
     */
    public function getClass($category, StatistiquesFormFilter $formFilter)
    {
        $configuration = $this->getConfiguration($category);

        return StatistiquesFactory::get($configuration['class'], $formFilter);
    }
}