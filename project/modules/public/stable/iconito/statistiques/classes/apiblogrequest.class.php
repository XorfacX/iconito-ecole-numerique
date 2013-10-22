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
        $visiteCount = $this->getObjectTypeNumber(static::CLASS_VISITE);
        $blogCount = $this->getNombreBlogs();

        $ratio = $visiteCount/$blogCount;

        return array(
            'visites' => $visiteCount,
            'ratio' => $ratio
        );
    }

    public function getNombreRubriquesEtRatio()
    {
        $rubriqueCount = $this->getObjectTypeNumber(static::CLASS_RUBRIQUE);
        $blogCount = $this->getNombreBlogs();

        $ratio = $rubriqueCount/$blogCount;

        return array(
            'rubriques' => $rubriqueCount,
            'ratio' => $ratio
        );
    }

    public function getNombrePagesEtRatio()
    {
        $pageCount = $this->getObjectTypeNumber(static::CLASS_PAGE);
        $blogCount = $this->getNombreBlogs();

        $ratio = $pageCount/$blogCount;

        return array(
            'pages' => $pageCount,
            'ratio' => $ratio
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
        $filter = $this->createBaseFilter();
        $filter->setObjectObjectType(static::CLASS_ARTICLE);
        $filter->setVerb('create');
        $filter->setPeriod(static::PERIOD_DAILY);

        $articles = $this->sumResults($this->getResult($filter));
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
}