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
     * @var string
     */
    public $target;

    /**
     * Create a filter, possibly based on a base filter (for dates and context)
     * @param DateTime $publishedFrom
     * @param DateTime $publishedTo
     * @param null $context
     */
    public function __construct(\DateTime $publishedFrom = null, \DateTime $publishedTo = null, $targetObjectType = null, $targetId = null)
    {
        $this->publishedFrom = $publishedFrom;
        $this->publishedTo = $publishedTo;
        $this->targetObjectType = $targetObjectType;
        $this->targetId = $targetId;
        $this->actorAttributes = array();
        $this->objectAttributes = array();
        $this->targetAttributes = array();
        $this->applicationId = CopixConfig::get('activitystream|activity_stream_application_id');
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
     *
     * @return $this
     */
    public function setLastOnly($lastOnly)
    {
        $this->lastOnly = $lastOnly;

        return $this;
    }

    /**
     * Get LasOnly
     * @return boolean
     */
    public function getLastOnly()
    {
        return $this->lastOnly;
    }

    public function setTarget($target)
    {
        $this->target = $target;

        list($type, $id) = explode('|', $target);
        $targetResource = Kernel::getNode($type, $id)->toResource();

        $this->setTargetObjectType($targetResource->getObjectType());
        $this->setTargetDisplayName($targetResource->getDisplayName());
        $this->setTargetId($targetResource->getId());
    }

    public function getTarget()
    {
        return $this->target;
    }
}