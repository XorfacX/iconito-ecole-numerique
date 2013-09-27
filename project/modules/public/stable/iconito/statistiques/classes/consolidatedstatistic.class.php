<?php

/**
 * ConsolidatedStatistic
 *
 * @author Jérémy Hubert <jeremy.hubert@infogroom.fr>
 *
 */
class ConsolidatedStatistic
{
    /**
     * @var integer
     */
    protected $id;

    /**
     * @var string
     */
    protected $period;

    /**
     * @var string
     */
    protected $applicationId;

    /**
     * @var \DateTime
     */
    private $published;

    /**
     * @var string
     */
    protected $actorObjectType;

    /**
     * @var string
     */
    protected $actorId;

    /**
     * @var string
     */
    protected $actorUrl;

    /**
     * @var string
     */
    protected $actorDisplayName;

    /**
     * @var string
     */
    protected $actorAttributes;

    /**
     * @var string
     */
    protected $verb;

    /**
     * @var integer
     */
    protected $counter;

    /**
     * @var string
     */
    protected $objectObjectType;

    /**
     * @var string
     */
    protected $objectId;

    /**
     * @var string
     */
    protected $objectUrl;

    /**
     * @var string
     */
    protected $objectDisplayName;

    /**
     * @var string
     */
    protected $objectAttributes;

    /**
     * @var string
     */
    protected $targetObjectType;

    /**
     * @var string
     */
    protected $targetId;

    /**
     * @var string
     */
    protected $targetUrl;

    /**
     * @var string
     */
    protected $targetDisplayName;

    /**
     * @var string
     */
    protected $targetAttributes;

    /**
     * @var string
     */
    protected $context;



    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set period
     *
     * @param string $period
     * @return ConsolidatedStatistic
     */
    public function setPeriod($period)
    {
        $this->period = $period;
    
        return $this;
    }

    /**
     * Get period
     *
     * @return string 
     */
    public function getPeriod()
    {
        return $this->period;
    }

    /**
     * Set applicationId
     *
     * @param string $applicationId
     * @return ConsolidatedStatistic
     */
    public function setApplicationId($applicationId)
    {
        $this->applicationId = $applicationId;
    
        return $this;
    }

    /**
     * Get applicationId
     *
     * @return string 
     */
    public function getApplicationId()
    {
        return $this->applicationId;
    }

    /**
     * Set published
     *
     * @param \DateTime $published
     * @return ConsolidatedStatistic
     */
    final public function setPublished($published)
    {
        $this->published = $published;
    
        return $this;
    }

    /**
     * Get published
     *
     * @return \DateTime 
     */
    final public function getPublished()
    {
        return $this->published;
    }

    /**
     * Set actorObjectType
     *
     * @param string $actorObjectType
     * @return ConsolidatedStatistic
     */
    public function setActorObjectType($actorObjectType)
    {
        $this->actorObjectType = $actorObjectType;
    
        return $this;
    }

    /**
     * Get actorObjectType
     *
     * @return string 
     */
    public function getActorObjectType()
    {
        return $this->actorObjectType;
    }

    /**
     * Set actorId
     *
     * @param string $actorId
     * @return ConsolidatedStatistic
     */
    public function setActorId($actorId)
    {
        $this->actorId = $actorId;
    
        return $this;
    }

    /**
     * Get actorId
     *
     * @return string 
     */
    public function getActorId()
    {
        return $this->actorId;
    }

    /**
     * Set actorUrl
     *
     * @param string $actorUrl
     * @return ConsolidatedStatistic
     */
    public function setActorUrl($actorUrl)
    {
        $this->actorUrl = $actorUrl;
    
        return $this;
    }

    /**
     * Get actorUrl
     *
     * @return string 
     */
    public function getActorUrl()
    {
        return $this->actorUrl;
    }

    /**
     * Set actorDisplayName
     *
     * @param string $actorDisplayName
     * @return ConsolidatedStatistic
     */
    public function setActorDisplayName($actorDisplayName)
    {
        $this->actorDisplayName = $actorDisplayName;
    
        return $this;
    }

    /**
     * Get actorDisplayName
     *
     * @return string 
     */
    public function getActorDisplayName()
    {
        return $this->actorDisplayName;
    }

    /**
     * Set actorAttributes
     *
     * @param string $actorAttributes
     * @return ConsolidatedStatistic
     */
    public function setActorAttributes($actorAttributes)
    {
        $this->actorAttributes = $actorAttributes;
    
        return $this;
    }

    /**
     * Get actorAttributes
     *
     * @return string 
     */
    public function getActorAttributes()
    {
        return $this->actorAttributes;
    }

    /**
     * Set verb
     *
     * @param string $verb
     * @return ConsolidatedStatistic
     */
    public function setVerb($verb)
    {
        $this->verb = $verb;
    
        return $this;
    }

    /**
     * Get verb
     *
     * @return string 
     */
    public function getVerb()
    {
        return $this->verb;
    }

    /**
     * Set counter
     *
     * @param integer $counter
     * @return ConsolidatedStatistic
     */
    public function setCounter($counter)
    {
        $this->counter = $counter;
    
        return $this;
    }

    /**
     * Get counter
     *
     * @return integer 
     */
    public function getCounter()
    {
        return $this->counter;
    }

    /**
     * Set objectObjectType
     *
     * @param string $objectObjectType
     * @return ConsolidatedStatistic
     */
    public function setObjectObjectType($objectObjectType)
    {
        $this->objectObjectType = $objectObjectType;
    
        return $this;
    }

    /**
     * Get objectObjectType
     *
     * @return string 
     */
    public function getObjectObjectType()
    {
        return $this->objectObjectType;
    }

    /**
     * Set objectId
     *
     * @param string $objectId
     * @return ConsolidatedStatistic
     */
    public function setObjectId($objectId)
    {
        $this->objectId = $objectId;
    
        return $this;
    }

    /**
     * Get objectId
     *
     * @return string 
     */
    public function getObjectId()
    {
        return $this->objectId;
    }

    /**
     * Set objectUrl
     *
     * @param string $objectUrl
     * @return ConsolidatedStatistic
     */
    public function setObjectUrl($objectUrl)
    {
        $this->objectUrl = $objectUrl;
    
        return $this;
    }

    /**
     * Get objectUrl
     *
     * @return string 
     */
    public function getObjectUrl()
    {
        return $this->objectUrl;
    }

    /**
     * Set objectDisplayName
     *
     * @param string $objectDisplayName
     * @return ConsolidatedStatistic
     */
    public function setObjectDisplayName($objectDisplayName)
    {
        $this->objectDisplayName = $objectDisplayName;
    
        return $this;
    }

    /**
     * Get objectDisplayName
     *
     * @return string 
     */
    public function getObjectDisplayName()
    {
        return $this->objectDisplayName;
    }

    /**
     * Set objectAttributes
     *
     * @param string $objectAttributes
     * @return ConsolidatedStatistic
     */
    public function setObjectAttributes($objectAttributes)
    {
        $this->objectAttributes = $objectAttributes;
    
        return $this;
    }

    /**
     * Get objectAttributes
     *
     * @return string 
     */
    public function getObjectAttributes()
    {
        return $this->objectAttributes;
    }

    /**
     * Set targetObjectType
     *
     * @param string $targetObjectType
     * @return ConsolidatedStatistic
     */
    public function setTargetObjectType($targetObjectType)
    {
        $this->targetObjectType = $targetObjectType;
    
        return $this;
    }

    /**
     * Get targetObjectType
     *
     * @return string 
     */
    public function getTargetObjectType()
    {
        return $this->targetObjectType;
    }

    /**
     * Set targetId
     *
     * @param string $targetId
     * @return ConsolidatedStatistic
     */
    public function setTargetId($targetId)
    {
        $this->targetId = $targetId;
    
        return $this;
    }

    /**
     * Get targetId
     *
     * @return string 
     */
    public function getTargetId()
    {
        return $this->targetId;
    }

    /**
     * Set targetUrl
     *
     * @param string $targetUrl
     * @return ConsolidatedStatistic
     */
    public function setTargetUrl($targetUrl)
    {
        $this->targetUrl = $targetUrl;
    
        return $this;
    }

    /**
     * Get targetUrl
     *
     * @return string 
     */
    public function getTargetUrl()
    {
        return $this->targetUrl;
    }

    /**
     * Set targetDisplayName
     *
     * @param string $targetDisplayName
     * @return ConsolidatedStatistic
     */
    public function setTargetDisplayName($targetDisplayName)
    {
        $this->targetDisplayName = $targetDisplayName;
    
        return $this;
    }

    /**
     * Get targetDisplayName
     *
     * @return string 
     */
    public function getTargetDisplayName()
    {
        return $this->targetDisplayName;
    }

    /**
     * Set targetAttributes
     *
     * @param string $targetAttributes
     * @return ConsolidatedStatistic
     */
    public function setTargetAttributes($targetAttributes)
    {
        $this->targetAttributes = $targetAttributes;
    
        return $this;
    }

    /**
     * Get targetAttributes
     *
     * @return string 
     */
    public function getTargetAttributes()
    {
        return $this->targetAttributes;
    }

    /**
     * Set context
     *
     * @param string $context
     * @return ConsolidatedStatistic
     */
    public function setContext($context)
    {
        $this->context = $context;
    
        return $this;
    }

    /**
     * Get context
     *
     * @return string 
     */
    public function getContext()
    {
        return $this->context;
    }
}