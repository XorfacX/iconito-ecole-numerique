<?php

class ApiMapping
{
    public function getFiltersCategories()
    {
        return array(
            'comptesEtConnexions' => array('label' => 'Comptes et connexions', 'template' => 'requests/comptes_et_connexions.tpl', 'class' => function($filter) {_classInclude('statistiques|apiuserrequest'); return new ApiUserRequest($filter); }),
            'agendas' => array('label' => 'Agendas', 'template' => 'requests/agendas.tpl', 'class' => function($filter) {_classInclude('statistiques|apiagendarequest'); return new ApiAgendaRequest($filter); }),
            'minimails' => array('label' => 'Minimails', 'template' => 'requests/minimails.tpl', 'class' => function($filter) {_classInclude('statistiques|apiminimailrequest'); return new ApiMinimailRequest($filter); }),
            'classeurs' => array('label' => 'Classeurs', 'template' => 'requests/classeurs.tpl', 'class' => function($filter) {_classInclude('statistiques|apiclasseurrequest'); return new ApiClasseurRequest($filter); }),
            'blogs' => array('label' => 'Blogs', 'template' => 'requests/blogs.tpl', 'class' => function($filter) {_classInclude('statistiques|apiblogrequest'); return new ApiBlogRequest($filter); }),
            'cahiersDeTextes' => array('label' => 'Cahiers de textes', 'template' => 'requests/cahiers_de_textes.tpl', 'class' => function($filter) {_classInclude('statistiques|apicahierdetexterequest'); return new ApiCahierDeTexteRequest($filter); }),
            'quiz' => array('label' => 'Quiz', 'template' => 'requests/quiz.tpl', 'class' => function($filter) {_classInclude('statistiques|apiquizrequest'); return new ApiQuizRequest($filter); }),
            'groupesDeTravail' => array('label' => 'Groupes de travail', 'template' => 'requests/groupes_de_travail.tpl', 'class' => function($filter) {_classInclude('statistiques|apigroupedetravailrequest'); return new ApiGroupeDeTravailRequest($filter); }),
            'test' => array('label' => 'Valeurs de test', 'template' => 'requests/test.tpl', 'class' => function($filter) {_classInclude('statistiques|apifakerequest'); return new ApiFakeRequest; }),
        );
    }

    /**
     * Returns infos concering filter
     *
     * @param string $key the key o the filter
     *
     * @throws Exception
     *
     * @return array
     */
    public function getFilterCategory($key)
    {
        $filters = $this->getFiltersCategories();

        if (!isset($filters[$key])) {
            throw new Exception('The requested key is invalid');
        }

        return $filters[$key];
    }

    /**
     * Get label for selected key
     *
     * @param string $key
     * @return array
     */
    public function getLabel($key)
    {
        $filter = $this->getFilterCategory($key);

        return $filter['label'];
    }

    /**
     * Get label for selected key
     *
     * @param string $key
     * @return array
     */
    public function getTemplate($key)
    {
        $filter = $this->getFilterCategory($key);

        return $filter['template'];
    }

    /**
     * Get label for selected key
     *
     * @param string $key
     * @return array
     */
    public function getClass($key, $baseFilter)
    {
        $filter = $this->getFilterCategory($key);

        return $filter['class']($baseFilter);
    }
}