<?php

_classInclude('statistiques|apibaserequest');

class ApiBlogRequest extends ApiBaseRequest
{
    /**
     * Récupère le nombre de comptes créés à une date donnée
     *
     * @return integer
     */
    public function getNombreBlogs()
    {
        return $this->getObjectTypeNumber(static::CLASS_BLOG);
    }

    public function getNombreVisitesEtRatio()
    {
        $filter = $this->createBaseFilter();
        $filter->setObjectObjectType(static::CLASS_BLOG);
        $filter->setVerb('watch');
        $filter->setPeriod(static::PERIOD_DAILY);

        $visiteCount = $this->sumResults($this->getResult($filter));
        $blogCount = $this->getNombreBlogs();

        return array(
            'visites' => $visiteCount,
            'ratio' => $blogCount > 0 ? round($visiteCount / $blogCount, 2) : 0
        );
    }

    public function getNombreRubriquesEtRatio()
    {
        $rubriqueCount = $this->getObjectTypeNumber(static::CLASS_BLOG_CATEGORY);
        $blogCount = $this->getNombreBlogs();

        return array(
            'rubriques' => $rubriqueCount,
            'ratio' => $blogCount > 0 ? round($rubriqueCount / $blogCount, 2) : 0
        );
    }

    public function getNombrePagesEtRatio()
    {
        $pageCount = $this->getObjectTypeNumber(static::CLASS_BLOG_PAGE);
        $blogCount = $this->getNombreBlogs();

        return array(
            'pages' => $pageCount,
            'ratio' => $blogCount > 0 ? round($pageCount / $blogCount, 2) : 0
        );
    }

    /**
     * Récupère le nombre de blogs visbles dans l'annuaire et non visible
     *
     * @return array
     */
    public function getBlogVisibleAnnuaireEtNonVisible()
    {
        return array(
            'visible_annuaire' => $this->getBlogs(array('is_public' => 1)),
            'non_visible'      => $this->getBlogs(array('is_public' => 0))
        );
    }

    /**
     * Récupère le nombre de blogs visbles dans l'annuaire et non visible
     *
     * @param string $objectType
     * @return mixed
     */
    public function getBlogVisibleEtVisibilite()
    {
        return array(
            'visible_internet'        => $this->getBlogs(array('privacy' => 0)),
            'visible_membres_iconito' => $this->getBlogs(array('privacy' => 10)),
            'visible_membres_groupe'  => $this->getBlogs(array('privacy' => 20))
        );
    }

    /**
     * Récupère le nombre de blogs ayant un des attributs passés en paramètres
     * @param array $objectAttributes
     * @return bool|mixed
     */
    protected function getBlogs(array $objectAttributes)
    {
        $filter = $this->createBaseFilter();
        $filter->setObjectObjectType(static::CLASS_BLOG);
        $filter->setObjectAttributes($objectAttributes);
        $filter->setPeriod(static::PERIOD_UNIT);
        $filter->setLastOnly(true);
        $result = $this->getResult($filter);

        return count($result) ? $result[0]->counter : 0;
    }

    public function getArticlesRedigesSurPeriode()
    {
        $articles = $this->getArticlesRediges();
        $days = $this->getFilter()->getpublishedFrom()->diff($this->getFilter()->getpublishedTo(), true)->days;

        return array(
            'articles' => $articles,
            'nb_moyen_par_jour' =>  $days > 0 ? round($articles/$days, 2) : 0
        );
    }

    public function getCommentairesRedigesSurPeriode()
    {
        $filter = $this->createBaseFilter();
        $filter->setObjectObjectType(static::CLASS_COMMENTAIRE);
        $filter->setVerb('create');
        $filter->setPeriod(static::PERIOD_DAILY);

        $commentaires = $this->sumResults($this->getResult($filter));
        $days = $this->getFilter()->getpublishedFrom()->diff($this->getFilter()->getpublishedTo(), true)->days;

        return array(
            'commentaires' => $commentaires,
            'nb_moyen_par_jour' => $days > 0 ? round($commentaires/$days, 2) : 0
        );
    }

    public function getNombreArticleParProfil()
    {
        $profils = array(
            'USER_ADM' => 'Équipe administrative',
            'USER_DIR' => 'Directeur',
            'USER_ELE' => 'Élève',
            'USER_ENS' => 'Enseignant',
            'USER_EXT' => 'Intervenant extérieur',
            'USER_RES' => 'Responsable',
            'USER_VIL' => 'Agent de ville'
        );

        $nombres = array();
        foreach ($profils as $profil => $libelle){
            $nombres[$libelle] = $this->getArticlesRediges($profil);
        }

        return $nombres;
    }

    public function getArticlesRediges($profil = null)
    {
        $filter = $this->createBaseFilter();
        $filter->setObjectObjectType(static::CLASS_BLOG_ARTICLE);
        $filter->setVerb('create');
        $filter->setPeriod(static::PERIOD_DAILY);

        if (null !== $profil){
            $filter->setActorAttributes(array('type' => $profil));
        }

        return $this->sumResults($this->getResult($filter));
    }
}