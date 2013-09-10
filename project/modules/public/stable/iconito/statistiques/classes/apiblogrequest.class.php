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
     * @param string $objectType
     * @return mixed
     */
    public function getBlogVisibleAnnuaireEtNonVisible()
    {
        return array(
            'visible_annuaire' => $this->getBlogs(array('visible dans l\'annuaire')),
            'non_visible'      => $this->getBlogs(array('non-visible'))
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
            'visible_internet'        => 5, #$this->getBlogs(array('visible sur Internet')),
            'visible_membres_iconito' => $this->getBlogs(array('visibles par membres ICONITO')),
            'visible_membres_groupe'  => $this->getBlogs(array('visibles par membres groupe'))
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

        return count($result) ? $result[0]->count : 0;
    }

    public function getArticlesRedigesSurPeriode()
    {
        $filter = $this->createBaseFilter();
        $filter->setObjectObjectType(static::CLASS_ARTICLE);
        $filter->setVerb('redacted');
        $filter->setPeriod(static::PERIOD_DAILY);

        $articles = $this->sumResults($this->getResult($filter));
        $days = $this->getFilter()->getPublishedBeginDate()->diff($this->getFilter()->getPublishedEndDate(), true)->days;

        return array(
            'articles' => $articles,
            'nb_moyen_par_jour' => $articles/$days
        );
    }

    public function getCommentairesRedigesSurPeriode()
    {
        $filter = $this->createBaseFilter();
        $filter->setObjectObjectType(static::CLASS_COMMENTAIRE);
        $filter->setVerb('redacted');
        $filter->setPeriod(static::PERIOD_DAILY);

        $commentaires = $this->sumResults($this->getResult($filter));
        $days = $this->getFilter()->getPublishedBeginDate()->diff($this->getFilter()->getPublishedEndDate(), true)->days;

        return array(
            'commentaires' => $commentaires,
            'nb_moyen_par_jour' => $commentaires/$days
        );
    }
}