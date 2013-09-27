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
    public $publishedFrom;

    /**
     * The max date the consolidatedStatistic must match
     * @var \DateTime
     */
    public $publishedTo;

    /**
     * Must we return the last (unit) only, or all units in the period
     * @var boolean
     */
    public $lastOnly;

    /**
     * Create a filter, possibly based on a base filter (for dates and context)
     * @param DateTime $publishedFrom
     * @param DateTime $publishedTo
     * @param null $context
     */
    public function __construct(\DateTime $publishedFrom = null, \DateTime $publishedTo = null, $context = null)
    {
        $this->publishedFrom      = $publishedFrom;
        $this->publishedTo        = $publishedTo;
        $this->context            = $context;
        $this->actorAttributes    = array();
        $this->objectAttributes   = array();
        $this->targetAttributes   = array();
        $this->applicationId      = 'EN-LMG';
    }


    /**
     * Set publishedFrom
     *
     * @param \Datetime $publishedFrom
     */
    public function setPublishedFrom(\DateTime $publishedFrom)
    {
        $this->publishedFrom = $publishedFrom;
    }

    /**
     * Get PublishedFrom
     *
     * @return \Datetime
     */
    public function getPublishedFrom()
    {
        return $this->publishedFrom;
    }

    /**
     * Set PublistedTo
     *
     * @param \DateTime $publishedTo
     */
    public function setPublishedTo($publishedTo)
    {
        $this->publishedTo = $publishedTo;
    }

    /**
     * Get PublishedEndDate
     *
     * @return \DateTime
     */
    public function getPublishedTo()
    {
        return $this->publishedTo;
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