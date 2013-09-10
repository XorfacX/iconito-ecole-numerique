<?php

class ApiMapping
{
    public function getFilters()
    {
        return array(
            'Comptes et connexions' => array(
                'nombreComptes'                 => array('label' => 'Nombre de comptes', 'class' => 'ApiUserRequest'),
                'nombreConnexionsAnnuelles'     => array('label' => 'Nombre de connexions annuelles', 'class' => 'ApiUserRequest'),
                'nombreConnexionsMensuelles'    => array('label' => 'Nombre de connexions mensuelles', 'class' => 'ApiUserRequest'),
                'nombreConnexionsHebdomadaires' => array('label' => 'Nombre de connexions hebdomadaires', 'class' => 'ApiUserRequest'),
                'nombreConnexionsJournalieres'  => array('label' => 'Nombre de connexions journalières', 'class' => 'ApiUserRequest'),
            ),
            'Agendas' => array(
                'nombreAgendas' => array('label' => 'Nombre d\'agendas', 'class' => 'ApiAgendaRequest'),
                'nombreEvenementsEtRatio' => array('label' => 'Nombre d\'événements', 'class' => 'ApiAgendaRequest'),
            ),
            'Minimails' => array(
                'nombreMinimails' => array('label' => 'Nombre de minimails envoyés', 'class' => 'ApiMinimailRequest'),
            ),
            'Classeurs' => array(
                'nombreClasseurs' => array('label' => 'Nombre de classeurs', 'class' => 'ApiClasseurRequest'),
                'nombreDossiersEtRatio' => array('label' => 'Nombre de dossiers', 'class' => 'ApiClasseurRequest'),
            ),
            'Blogs' => array(
                'nombreBlogs' => array('label' => 'Nombre de blogs', 'class' => 'ApiBlogRequest'),
                'blogVisibleAnnuaireEtNonVisible' => array('label' => 'Nombre de blogs visibles dans l\annuaire, et non visibles', 'class' => 'ApiBlogRequest'),
                'blogVisibleEtVisibilite' => array('label' => 'Nombre de blogs visibles selon visibilité', 'class' => 'ApiBlogRequest'),
                'articlesRedigesSurPeriode' => array('label' => 'Nombre d\'articles rédigés sur la période', 'class' => 'ApiBlogRequest'),
                'commentairesRedigesSurPeriode' => array('label' => 'Nombre de commentaires rédigés sur la période', 'class' => 'ApiBlogRequest'),
            ),
            'Cahiers de textes' => array(
                'travailAfaire' => array('label' => 'Travail à faire', 'class' => 'ApiCahierDeTexteRequest'),
                'travailEnClasse' => array('label' => 'Travail en classe', 'class' => 'ApiCahierDeTexteRequest'),
                'memos' => array('label' => 'Mémos', 'class' => 'ApiCahierDeTexteRequest'),
            ),
            'Quiz' => array(
                'quiz' => array('label' => 'Nombre de quiz', 'class' => 'ApiQuizRequest'),
                'nombreQuestionsEtRatio' => array('label' => 'Questions', 'class' => 'ApiQuizRequest'),
            ),
            'Groupes de travail' => array(
                'nombreMessagesEtRatio' => array('label' => 'Nombre de messages', 'class' => 'ApiGroupeDeTravailRequest'),
                'nombreDiscussionsEtRatio' => array('label' => 'Nombre de discussions', 'class' => 'ApiGroupeDeTravailRequest'),
            )
        );
    }

    /**
     * Returns infos concering filter
     *
     * @param $key the key o the filter
     *
     * @return array
     */
    public function getFilter($key)
    {
        $filters = array();
        foreach ($this->getFilters() as $category)
        {
            $filters = array_merge($filters, $category);
        }

        return $filters[$key];
    }
}