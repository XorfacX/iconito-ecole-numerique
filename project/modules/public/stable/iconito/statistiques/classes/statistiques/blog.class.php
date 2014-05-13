<?php

_classInclude('statistiques|baseStatistiques');

class Blog extends baseStatistiques
{
    /** @var int Le nombre de blog */
    protected $nombreBlogs;

    /** @var int Le nombre de visite */
    protected $nombreVisites;

    /** @var int Le nombre de rubriques */
    protected $nombreRubriques;

    /** @var int Le nombre de pages */
    protected $nombrePages;

    /**
     * Récupère le nombre de comptes créés à une date donnée
     *
     * @return integer
     */
    public function getNombreBlogs()
    {
        if (null === $this->nombreBlogs) {
            $this->nombreBlogs = $this->getCount(
                $this->getLastUnitApiFilter()
                    ->set('object_object_type', static::CLASS_BLOG)
            );
        }

        return $this->nombreBlogs;
    }

    /**
     * Récupère le nombre de visite
     *
     * @return int
     */
    public function getNombreVisites()
    {
        if (null === $this->nombreVisites){
            $this->nombreVisites = $this->getSum(
                $this->getPeriodApiFilter(static::PERIOD_DAILY)
                    ->set('object_object_type', static::CLASS_VISITE)
            );
        }

        return $this->nombreVisites;
    }

    /**
     * Retourne le nombre de visite par blog
     *
     * @return float
     */
    public function getRatioVisitesParBlog()
    {
        $nombreBlog = $this->getNombreBlogs();

        if (0 == $nombreBlog){
            return 0;
        }

        return $this->getNombreVisites() / $nombreBlog;
    }

    /**
     * Retourne le nombre de rubriques
     *
     * @return int
     */
    public function getNombreRubriques()
    {
        if (null === $this->nombreRubriques){
            $this->nombreRubriques = $this->getCount(
                $this->getLastUnitApiFilter()
                    ->set('object_object_class', static::CLASS_RUBRIQUE)
            );
        }

        return $this->nombreRubriques;
    }

    /**
     * Retourne le ratio de rubriques par blog
     *
     * @return float
     */
    public function getRatioRubriquesParBlog()
    {
        $nombreBlogs = $this->getNombreBlogs();

        if (0 === $nombreBlogs){
            return 0;
        }

        return $this->getNombreRubriques() / $nombreBlogs;
    }

    /**
     * Retourne le nombre de pages de blog
     */
    public function getNombrePages()
    {
        if (null === $this->nombrePages){
            $this->nombrePages = $this->getCount(
                $this->getLastUnitApiFilter()
                    ->set('object_object_class', static::CLASS_PAGE)
            );
        }

        return $this->nombrePages;
    }

    /**
     * Retourne le ratio du nombre de pages par blog
     *
     * @return float
     */
    public function getRatioNombrePagesParBlog()
    {
        $nombreBlogs = $this->getNombreBlogs();

        if (0 === $nombreBlogs){
            return 0;
        }

        return $this->getNombrePages() / $nombreBlogs;
    }

    /**
     * Récupère le nombre de blogs visbles dans l'annuaire et non visible
     *
     * @return array
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
            'visible_internet'        => $this->getBlogs(array('visible sur Internet')),
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
        $days = $this->getFilter()->getpublishedFrom()->diff($this->getFilter()->getpublishedTo(), true)->days;

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
        $days = $this->getFilter()->getpublishedFrom()->diff($this->getFilter()->getpublishedTo(), true)->days;

        return array(
            'commentaires' => $commentaires,
            'nb_moyen_par_jour' => $commentaires/$days
        );
    }
}