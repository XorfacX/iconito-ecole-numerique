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
    public $publishedBeginDateHR;

    /**
     * The max date the consolidatedStatistic must match
     * @var \DateTime
     */
    public $publishedEndDate;
    public $publishedEndDateHR;

    /**
     * Must we return the last (unit) only, or all units in the period
     * @var boolean
     */
    public $lastOnly;

    public function __construct()
    {
        $this->actorAttributes  = array();
        $this->objectAttributes = array();
        $this->targetAttributes = array();
    }


    /**
     * Set publishedBeginDate
     *
     * @param \Datetime $publishedBeginDate
     */
    public function setPublishedBeginDate(\DateTime $publishedBeginDate)
    {
        $this->publishedBeginDate = $publishedBeginDate;
        $this->publishedBeginDateHr = $publishedBeginDate->format('d/m/Y');
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
        $this->publishedEndDateHR = $publishedEndDate->format('d/m/Y');
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

    public function getToto()
    {
        return 'toto';
    }
}