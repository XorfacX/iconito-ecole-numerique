<?php

_classInclude('statistiques|consolidatedstatistic');

/**
 * ConsolidatedStatisticFilter
 * Filter to be used for database requests
 *
 * @author Jérémy Hubert <jeremy.hubert@infogroom.fr>
 */
class ConsolidatedStatisticFilter extends ConsolidatedStatistic
{
    /**
     * The min date the consolidatedStatistic must match
     * @var \Datetime
     */
    public $publishedBeginDate;

    /**
     * The max date the consolidatedStatistic must match
     * @var \DateTime
     */
    public $publishedEndDate;

    /**
     * Must we return the last (unit) only, or all units in the period
     * @var boolean
     */
    public $lastOnly;

    /**
     * Create a filter, possibly based on a base filter (for dates and context)
     * @param DateTime $publishedBeginDate
     * @param DateTime $publishedEndDate
     * @param null $context
     */
    public function __construct(\DateTime $publishedBeginDate = null, \DateTime $publishedEndDate = null, $context = null)
    {
        $this->publishedBeginDate = $publishedBeginDate;
        $this->publishedEndDate   = $publishedEndDate;
        $this->context            = $context;
        $this->actorAttributes    = array();
        $this->objectAttributes   = array();
        $this->targetAttributes   = array();
        $this->applicationId      = 'EN-LMG';
    }


    /**
     * Set publishedBeginDate
     *
     * @param \Datetime $publishedBeginDate
     */
    public function setPublishedBeginDate(\DateTime $publishedBeginDate)
    {
        $this->publishedBeginDate = $publishedBeginDate;
    }

    /**
     * Get PublishedBeginDate
     *
     * @return \Datetime
     */
    public function getPublishedBeginDate()
    {
        return $this->publishedBeginDate;
    }

    /**
     * Set PublistedEndDate
     *
     * @param \DateTime $publishedEndDate
     */
    public function setPublishedEndDate($publishedEndDate)
    {
        $this->publishedEndDate = $publishedEndDate;
    }

    /**
     * Get PublishedEndDate
     *
     * @return \DateTime
     */
    public function getPublishedEndDate()
    {
        return $this->publishedEndDate;
    }

    /**
     * Set LastOnly
     *
     * @param boolean $lastOnly
     */
    public function setLastOnly($lastOnly)
    {
        $this->lastOnly = $lastOnly;
    }

    /**
     * Get LasOnly
     * @return boolean
     */
    public function getLastOnly()
    {
        return $this->lastOnly;
    }
}